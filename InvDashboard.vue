<!-- =========================================================================================
  File Name: InvDashboard.vue
  Description: InvDashboard List page
  ----------------------------------------------------------------------------------------
  Item Name: Hospital Solution
  Author: Radianz
  Author URL: https://www.radianzinfotech.com
========================================================================================== -->
<template> 
  <div id="page-patient-list">
   <vx-card ref="filterCard" title="Filters" class="patient-list-filters mb-8" actionButtons @refresh="resetColFilters" @remove="resetColFilters">
      <div class="vx-row">
       <div class="vx-col md:w-1/12 w-full">
          <label>Filter By</label><br/>
          <vs-radio v-model="sort.filter" vs-value="0">Day</vs-radio>
          <vs-radio v-model="sort.filter" vs-value="1">Month</vs-radio>
          <vs-radio v-model="sort.filter" vs-value="2">Year</vs-radio>
        </div>
        <div class="vx-col md:w-1/12 w-full" v-if="sort.filter==1">
            <label class="text-sm opacity-75">From Year</label>
            <v-select :options="years"  :clearable="false" :dir="$vs.rtl ? 'rtl' : 'ltr'" id="value" label="label" v-model="sort.m_from_year" class="mb-4 md:mb-0"/>
        </div>
        <div class="vx-col md:w-1/12 w-full" v-if="sort.filter==1">
            <label class="text-sm opacity-75">To Year</label>
            <v-select :options="years"  :clearable="false" :dir="$vs.rtl ? 'rtl' : 'ltr'" id="value" label="label" v-model="sort.m_to_year" class="mb-4 md:mb-0"/>
        </div>
        <div class="vx-col md:w-1/12 w-full" v-if="sort.filter==2">
            <label class="text-sm opacity-75">From Year</label>
            <v-select :options="years"  :clearable="false" :dir="$vs.rtl ? 'rtl' : 'ltr'" id="value" label="label" v-model="sort.from_year" class="mb-4 md:mb-0"/>
        </div>
        <div class="vx-col md:w-1/12 w-full" v-if="sort.filter==2">
            <label class="text-sm opacity-75">To Year</label>
            <v-select :options="years"  :clearable="false" :dir="$vs.rtl ? 'rtl' : 'ltr'" id="value" label="label" v-model="sort.to_year" class="mb-4 md:mb-0"/>
        </div>
        <div class="vx-col md:w-1/12 w-full" v-if="sort.filter==1">
            <label>From Month</label>
            <v-select :options="monthOptions" :dir="$vs.rtl ? 'rtl' : 'ltr'" id="value" label="label" v-model="from_month" />
        </div>
        <div class="vx-col md:w-1/12 w-full" v-if="sort.filter==1">
            <label>To Month</label>
            <v-select :options="monthOptions" :dir="$vs.rtl ? 'rtl' : 'ltr'" id="value" label="label" v-model="to_month" />
        </div>
        <div class="vx-col md:w-1/12 sm:w-1/6 w-full" v-if="sort.filter==0">
            <label class="text-sm opacity-75">Created From</label>
            <flat-pickr placeholder="dd-mm-yyyy" v-model="sort.from_date" name="from_date" :config="{ dateFormat: 'd-m-Y' }" class="w-full"/>
        </div>
        <div class="vx-col md:w-1/12 sm:w-1/6 w-full" v-if="sort.filter==0">
          <label class="text-sm opacity-75">Created To</label>
          <flat-pickr placeholder="dd-mm-yyyy" v-model="sort.to_date" name="to_date" :config="{ dateFormat: 'd-m-Y' }" class="w-full"/>
        </div>
        <div class="vx-col md:w-1/6">
          <div>
            <label>Organization</label>
            <v-select v-model="org_id" id="id" label="name" v-validate="'required'" :clearable="false"
             :options="parents" class="w-full select-large" name="Organization" placeholder="All"/>
          </div>
        </div>
        <div class="vx-col md:w-1/6">
          <div>
            <label>Department</label>
            <v-select class="mr-4" v-model="dept" id="id" label="name" :options="departments" :dir="$vs.rtl ? 'rtl' : 'ltr'" placeholder="All"/>
          </div>
        </div>
        <div class="vx-col md:w-1/12 w-full" v-if="dayReport.length>0">
           <div class="p-2 mt-6 w-full shadow-drop rounded-lg d-theme-dark-light-bg cursor-pointer flex items-end justify-center text-lg font-medium w-32 border" >
              <export-excel
                  class   = "btn btn-default text-primary text-base"
                  :data   = "dayReport"
                  :fields = "json_fields"
                  worksheet = "My Worksheet"
                  name    = "report.xls">
                  <feather-icon icon="ArrowUpIcon" svgClasses="h-4 w-4" /> Export
                </export-excel>
           </div>
        </div>
        <div class="vx-col md:w-1/12 w-full" v-if="dayReport.length>0" style="padding-top:2%">
           <a :href="'/api/day_report/download/'+sort.dept+'/'+sort.org_id+'/'+sort.to_date+'/'+sort.from_date+'/'+sort.filter" ><feather-icon  icon="PrinterIcon" style="color:#0000FF" svgClasses="h-5 w-5 mr-4 hover:text-primary cursor-pointer" > </feather-icon> </a>
        </div>
        <div class="vx-col md:w-1/12 w-full" v-if="monthReport.length>0">
           <div class="p-2 mt-6 w-full shadow-drop rounded-lg d-theme-dark-light-bg cursor-pointer flex items-end justify-center text-lg font-medium w-32 border" >
            <export-excel
                  class   = "btn btn-default text-primary text-base"
                  :data   = "monthReport"
                  :fields = "json_fields1"
                  worksheet = "My Worksheet"
                  name    = "report.xls">
                  <feather-icon icon="ArrowUpIcon" svgClasses="h-4 w-4" /> Export
                </export-excel>
           </div>
         </div>
         <div class="vx-col md:w-1/12 w-full" v-if="monthReport.length>0" style="padding-top:2%">
           <a :href="'/api/month_report/download/'+sort.dept+'/'+sort.org_id+'/'+sort.to_date+'/'+sort.from_date+'/'+sort.filter"><feather-icon  icon="PrinterIcon" style="color:#0000FF" svgClasses="h-5 w-5 mr-4 hover:text-primary cursor-pointer" > </feather-icon> </a>
        </div>
         <div class="vx-col md:w-1/12 w-full" v-if="yearReport.length>0">
           <div class="p-2 mt-6 w-full shadow-drop rounded-lg d-theme-dark-light-bg cursor-pointer flex items-end justify-center text-lg font-medium w-32 border" >
            <export-excel
                  class   = "btn btn-default text-primary text-base"
                  :data   = "yearReport"
                  :fields = "json_fields2"
                  worksheet = "My Worksheet"
                  name    = "report.xls">
                  <feather-icon icon="ArrowUpIcon" svgClasses="h-4 w-4" /> Export
                </export-excel>
           </div>
        </div>
         <div class="vx-col md:w-1/12 w-full" v-if="yearReport.length>0" style="padding-top:2%">
           <a :href="'/api/year_report/download/'+sort.dept+'/'+sort.org_id+'/'+sort.to_date+'/'+sort.from_date+'/'+sort.filter"><feather-icon  icon="PrinterIcon" style="color:#0000FF" svgClasses="h-5 w-5 mr-4 hover:text-primary cursor-pointer" > </feather-icon> </a>
        </div>
        <div class="vx-col md:w-1/12 sm:w-1/2 w-full">
         <br>
          <vs-button color="warning" class="mb-2 w-full" @click="fetchInv">Load</vs-button>
        </div>
    </div>
  </vx-card> 
  <div class="vx-row mb-6" >
    <div class="vx-col md:w-1/1 w-full">
      <vs-table :data="dayReport" class="stripe" v-if="sort.filter==0">
       <template slot="thead">
             <vs-th>Sl No</vs-th>
             <vs-th>Date</vs-th>
             <vs-th>Organization</vs-th>
             <vs-th>Department</vs-th>
             <vs-th>Purchase Payable</vs-th>
             <vs-th>Purchase Return</vs-th>
             <vs-th>Credit Note</vs-th>
             <vs-th>Supplier Payment</vs-th>
             <vs-th>Sales</vs-th>
             <vs-th>Sales Return</vs-th>
          </template>
          <template slot-scope="{data}">
             <vs-tr :key="indextr" v-for="(tr, indextr) in data">
                <vs-td>
                  {{tr.sl=indextr+1}}
                </vs-td>
                 <vs-td>
                   {{tr.date=displayDate(tr.created_at)}}
                </vs-td>
                <vs-td>
                  {{tr.org_name}}
                </vs-td>
                <vs-td>
                  {{tr.dept_name}}
                </vs-td>
                <vs-td>
                   {{tr.purchase_entry_amount.toFixed(2)}}
                </vs-td>
                <vs-td>
                   {{tr.purchase_return_amount.toFixed(2)}}
                </vs-td>
                <vs-td>
                  {{tr.credit_amount.toFixed(2)}}
                </vs-td>
                 <vs-td>
                   {{tr.supplier_payment_amount.toFixed(2)}}
                </vs-td>
                <vs-td>
                  {{tr.sales_amount.toFixed(2)}}
                </vs-td>
                <vs-td>
                 {{tr.sales_return_amount.toFixed(2)}}
                </vs-td>
             </vs-tr> 
          </template>
       </vs-table>
       <vs-table :data="monthReport" class="stripe" v-if="sort.filter==1">
          <template slot="thead">
             <vs-th>Sl No</vs-th>
             <vs-th>Date</vs-th>
             <vs-th>Organization</vs-th>
             <vs-th>Department</vs-th>
             <vs-th>Purchase Payable</vs-th>
             <vs-th>Purchase Return</vs-th>
             <vs-th>Credit Note</vs-th>
             <vs-th>Supplier Payment</vs-th>
             <vs-th>Sales</vs-th>
             <vs-th>Sales Return</vs-th>
          </template>
          <template slot-scope="{data}">
             <vs-tr :key="indextr" v-for="(tr, indextr) in data">
                <vs-td>
                  {{tr.sl=indextr+1}}
                </vs-td>
                <vs-td>
                  <p v-if="tr.month=='01'">{{tr.year}} January</p>
                  <p style="display:none" v-if="tr.month=='01'">{{tr.month_yr=tr.year + ' January'}} </p>
                  <p v-if="tr.month=='02'">{{tr.year}} February</p>
                  <p style="display:none" v-if="tr.month=='02'">{{tr.month_yr=tr.year + ' February'}} </p>
                  <p v-if="tr.month=='03'">{{tr.year}} March</p>
                  <p style="display:none" v-if="tr.month=='03'">{{tr.month_yr=tr.year + ' March'}} </p>
                  <p v-if="tr.month=='04'">{{tr.year}} April</p>
                  <p style="display:none" v-if="tr.month=='04'">{{tr.month_yr=tr.year + ' April'}} </p>
                  <p v-if="tr.month=='05'">{{tr.year}} May</p>
                  <p style="display:none" v-if="tr.month=='05'">{{tr.month_yr=tr.year + ' May'}} </p>
                  <p v-if="tr.month=='06'">{{tr.year}} June</p>
                  <p style="display:none" v-if="tr.month=='06'">{{tr.month_yr=tr.year + ' June'}} </p>
                  <p v-if="tr.month=='07'">{{tr.year}} July</p>
                  <p style="display:none" v-if="tr.month=='07'">{{tr.month_yr=tr.year + ' July'}} </p>
                  <p v-if="tr.month=='08'">{{tr.year}} August</p>
                  <p style="display:none" v-if="tr.month=='08'">{{tr.month_yr=tr.year + ' August'}} </p>
                  <p v-if="tr.month=='09'">{{tr.year}} September</p>
                  <p style="display:none" v-if="tr.month=='09'">{{tr.month_yr=tr.year + ' September'}} </p>
                  <p v-if="tr.month=='10'">{{tr.year}} October</p>
                  <p style="display:none" v-if="tr.month=='10'">{{tr.month_yr=tr.year + ' October'}} </p>
                  <p v-if="tr.month=='11'">{{tr.year}} November</p>
                  <p style="display:none" v-if="tr.month=='11'">{{tr.month_yr=tr.year + ' November'}} </p>
                  <p v-if="tr.month=='12'">{{tr.year}} December</p>
                  <p style="display:none" v-if="tr.month=='12'">{{tr.month_yr=tr.year + ' December'}} </p>
                </vs-td>
                 <vs-td>
                  {{tr.org_name}}
                </vs-td>
                <vs-td>
                  {{tr.dept_name}}
                </vs-td>
                <vs-td>
                   {{tr.purchase_entry_amount.toFixed(2)}}
                </vs-td>
                <vs-td>
                   {{tr.purchase_return_amount.toFixed(2)}}
                </vs-td>
                <vs-td>
                  {{tr.credit_amount.toFixed(2)}}
                </vs-td>
                <vs-td>
                  {{tr.supplier_payment_amount.toFixed(2)}}
                </vs-td>
                <vs-td>
                  {{tr.sales_amount.toFixed(2)}}
                </vs-td>
                <vs-td>
                 {{tr.sales_return_amount.toFixed(2)}}
                </vs-td>
             </vs-tr> 
          </template>
       </vs-table>
       <vs-table :data="yearReport" class="stripe" v-if="sort.filter==2">
          <template slot="thead">
             <vs-th>Sl No</vs-th>
             <vs-th>Date</vs-th>
             <vs-th>Organization</vs-th>
             <vs-th>Department</vs-th>
             <vs-th>Purchase Payable</vs-th>
             <vs-th>Purchase Return</vs-th>
             <vs-th>Credit Note</vs-th>
             <vs-th>Supplier Payment</vs-th>
             <vs-th>Sales</vs-th>
             <vs-th>Sales Return</vs-th>
          </template>
          <template slot-scope="{data}">
             <vs-tr :key="indextr" v-for="(tr, indextr) in data">
                <vs-td>
                  {{tr.sl=indextr+1}}
                </vs-td>
                <vs-td>
                   {{tr.year}}
                </vs-td>
                <vs-td>
                  {{tr.org_name}}
                </vs-td>
                <vs-td>
                  {{tr.dept_name}}
                </vs-td>
                <vs-td>
                   {{tr.purchase_entry_amount.toFixed(2)}}
                </vs-td>
                <vs-td>
                   {{tr.purchase_return_amount.toFixed(2)}}
                </vs-td>
                <vs-td>
                  {{tr.credit_amount.toFixed(2)}}
                </vs-td>
                <vs-td>
                  {{tr.supplier_payment_amount.toFixed(2)}}
                </vs-td>
                <vs-td>
                  {{tr.sales_amount.toFixed(2)}}
                </vs-td>
                <vs-td>
                 {{tr.sales_return_amount.toFixed(2)}}
                </vs-td>
             </vs-tr> 
          </template>
       </vs-table>
       <br><br><br>
       <vs-table :data="totalReport" class="stripe"  v-if="sort.filter==0 && dayReport.length>0">
          <template slot="thead">
             <vs-th></vs-th>
             <vs-th></vs-th>
             <vs-th></vs-th>
             <vs-th></vs-th>
             <vs-th></vs-th>
             <vs-th></vs-th>
             <vs-th></vs-th>
             <vs-th></vs-th>
             <vs-th>Total Purchase Payable</vs-th>
             <vs-th>Total Purchase Return</vs-th>
             <vs-th>Total Credit Note</vs-th>
             <vs-th>Total Supplier Payment</vs-th>
             <vs-th>Total Sales</vs-th>
             <vs-th>Total Sales Return</vs-th>
          </template>
          <template slot-scope="{data}">
             <vs-tr :key="indextr" v-for="(tr, indextr) in data">
                <vs-td>
                </vs-td>
                <vs-td>
                </vs-td>
                <vs-td>
                </vs-td>
                <vs-td>
                </vs-td>
                <vs-td>
                </vs-td>
                <vs-td>
                </vs-td>
                <vs-td>
                </vs-td>
                <vs-td>
                </vs-td> 
                <vs-td >
                   {{tr.total_purchase_entry_amount.toFixed(2)}}
                </vs-td>
                <vs-td >
                   {{tr.total_purchase_return_amount.toFixed(2)}}
                </vs-td>
                <vs-td >
                  {{tr.total_credit_amount.toFixed(2)}}
                </vs-td>
                <vs-td>
                  {{tr.total_supplier_payment_amount.toFixed(2)}}
                </vs-td>
                <vs-td>
                  {{tr.total_sales_amount.toFixed(2)}}
                </vs-td>
                <vs-td>
                 {{tr.total_sales_return_amount.toFixed(2)}}
                </vs-td>
             </vs-tr> 
          </template>
       </vs-table>
       <vs-table :data="totalReport" class="stripe"  v-if="sort.filter==1 && monthReport.length>0">
          <template slot="thead">
             <vs-th></vs-th>
             <vs-th></vs-th>
             <vs-th></vs-th>
             <vs-th></vs-th>
             <vs-th></vs-th>
             <vs-th></vs-th>
             <vs-th></vs-th>
             <vs-th></vs-th>
             <vs-th></vs-th> 
             <vs-th>Total Purchase Payable</vs-th>
             <vs-th>Total Purchase Return</vs-th>
             <vs-th>Total Credit Note</vs-th>
             <vs-th>Total Supplier Payment</vs-th>
             <vs-th>Total Sales</vs-th>
             <vs-th>Total Sales Return</vs-th>
          </template>
          <template slot-scope="{data}">
             <vs-tr :key="indextr" v-for="(tr, indextr) in data">
                <vs-td>
                </vs-td>
                <vs-td>
                </vs-td>
                <vs-td>
                </vs-td>
                <vs-td>
                </vs-td>
                <vs-td>
                </vs-td>
                <vs-td>
                </vs-td>
                <vs-td>
                </vs-td>
                <vs-td>
                </vs-td>
                <vs-td>
                </vs-td>
                <vs-td >
                   {{tr.total_purchase_entry_amount.toFixed(2)}}
                </vs-td>
                <vs-td >
                   {{tr.total_purchase_return_amount.toFixed(2)}}
                </vs-td>
                <vs-td >
                  {{tr.total_credit_amount.toFixed(2)}}
                </vs-td>
                <vs-td>
                  {{tr.total_supplier_payment_amount.toFixed(2)}}
                </vs-td>
                <vs-td>
                  {{tr.total_sales_amount.toFixed(2)}}
                </vs-td>
                <vs-td>
                 {{tr.total_sales_return_amount.toFixed(2)}}
                </vs-td>
             </vs-tr> 
          </template>
       </vs-table>
      <vs-table :data="totalReport" class="stripe"  v-if="sort.filter==2 && yearReport.length>0">
          <template slot="thead">
             <vs-th></vs-th>
             <vs-th></vs-th>
             <vs-th></vs-th>
             <vs-th></vs-th>
             <vs-th></vs-th>
             <vs-th></vs-th>
             <vs-th></vs-th> 
             <vs-th>Total Purchase Payable</vs-th>
             <vs-th>Total Purchase Return</vs-th>
             <vs-th>Total Credit Note</vs-th>
             <vs-th>Total Supplier Payment</vs-th>
             <vs-th>Total Sales</vs-th>
             <vs-th>Total Sales Return</vs-th>
          </template>
          <template slot-scope="{data}">
             <vs-tr :key="indextr" v-for="(tr, indextr) in data">
                <vs-td>
                </vs-td>
                <vs-td>
                </vs-td>
                <vs-td>
                </vs-td>
                <vs-td>
                </vs-td>
                <vs-td>
                </vs-td>
                <vs-td>
                </vs-td>
                <vs-td>
                </vs-td>
                <vs-td >
                   {{tr.total_purchase_entry_amount.toFixed(2)}}
                </vs-td>
                <vs-td >
                   {{tr.total_purchase_return_amount.toFixed(2)}}
                </vs-td>
                <vs-td >
                  {{tr.total_credit_amount.toFixed(2)}}
                </vs-td>
                <vs-td>
                  {{tr.total_supplier_payment_amount.toFixed(2)}}
                </vs-td>
                <vs-td>
                  {{tr.total_sales_amount.toFixed(2)}}
                </vs-td>
                <vs-td>
                 {{tr.total_sales_return_amount.toFixed(2)}}
                </vs-td>
             </vs-tr> 
          </template>
       </vs-table>
      </div>
    </div>
  </div>
</template>
<script>
import { AgGridVue } from 'ag-grid-vue'
import '@sass/vuexy/extraComponents/agGridStyleOverride.scss'
import vSelect from 'vue-select'
import excel from 'vue-excel-export'
// Store Module
import moduleUserManagement from '@/store/user-management/moduleUserManagement.js'
import ImportExcel from '@/components/excel/ImportExcel.vue'
import moment from 'moment';
import moduleAuth from '@/store/auth/moduleAuth.js';
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";
import Vue from 'vue';
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
Vue.use(Loading);
Vue.use(excel)
export default {
  components: {
    AgGridVue,
    vSelect,
    ImportExcel,
    loader : false,
    flatPickr
  },
  data () {
    return {
      parents: [],
      departments: [],
      org_id:'',
      dept:'',
      json_fields: {  
           'Sl No' : 'sl',
           'Date': 'date',
           'Total Purchase Payable': 'purchase_entry_amount',
           'Total Purchase Return' :'purchase_return_amount',
           'Total Credit Note' : 'credit_amount',
           'Total Supplier Payment' : 'supplier_payment_amount',
           'Total Sales' : 'sales_amount',
           'Total Sales Return' : 'sales_return_amount',
        },
        json_fields1: {  
           'Sl No' : 'sl',
           'Month': 'month_yr',
           'Total Purchase Payable': 'purchase_entry_amount',
           'Total Purchase Return' :'purchase_return_amount',
           'Total Credit Note' : 'credit_amount',
           'Total Supplier Payment' : 'supplier_payment_amount',
           'Total Sales' : 'sales_amount',
           'Total Sales Return' : 'sales_return_amount',
        },
        json_fields2: {  
           'Sl No' : 'sl',
           'Year': 'year',
           'Total Purchase Payable': 'purchase_entry_amount',
           'Total Purchase Return' :'purchase_return_amount',
           'Total Credit Note' : 'credit_amount',
           'Total Supplier Payment' : 'supplier_payment_amount',
           'Total Sales' : 'sales_amount',
           'Total Sales Return' : 'sales_return_amount',
        },
        json_meta: [
            [
                {
                    'key': 'charset',
                    'value': 'utf-8'
                }
            ]
        ],
      org_id:null,
      departments:[],
      permission:[],
      parents:[],
      departmentnameList:[],
      length:'',
      dept:null,
      products:[],
      product:'',
      start : 0,
      end : 0,
      total : 0,
      total_page_count:0,
      old_total:0,
      type:'',
      index:0,
      TypeOptions: [
        { label: 'Purchase Entry', value: 'purchase_entry'},
        { label: 'Purchase Return', value: 'purchase_return'},   
        { label: 'Sales Bill', value: 'sales_bill'},
        { label: 'Sales Return', value: 'sales_return'},
        { label: 'Stock Adjustment', value: 'stock_adjustment'},   
        { label: 'Consumable Entry', value: 'consumable_entry'}     
       ],
       monthOptions: [
            { label: 'Jan', value: '1' },
            { label: 'Feb', value: '2' },
            { label: 'Mar', value: '3' },
            { label: 'Apr', value: '4' },
            { label: 'May', value: '5' },
            { label: 'June', value: '6' },
            { label: 'July', value: '7' },
            { label: 'Aug', value: '8' },
            { label: 'Sept', value: '9' },
            { label: 'Oct', value: '10' },
            { label: 'Nov', value: '11' },
            { label: 'Dec', value: '12' },
          ],
          dayOptions: [
            { label: '01', value: '01' },
            { label: '02', value: '02' },
            { label: '03', value: '03' },
            { label: '04', value: '04' },
            { label: '05', value: '05' },
            { label: '06', value: '06' },
            { label: '07', value: '07' },
            { label: '08', value: '08' },
            { label: '10', value: '11' },
            { label: '12', value: '13' },
            { label: '14', value: '14' },
            { label: '15', value: '15' },
            { label: '16', value: '16' },
            { label: '17', value: '17' },
            { label: '18', value: '18' },
            { label: '19', value: '19' },
            { label: '20', value: '20' },
            { label: '21', value: '21' },
            { label: '22', value: '22' },
            { label: '23', value: '23' },
            { label: '24', value: '24' },
            { label: '25', value: '25' },
            { label: '26', value: '26' },
            { label: '27', value: '27' },
            { label: '28', value: '28' },
            { label: '29', value: '29' },
            { label: '30', value: '30' },
            { label: '31', value: '31' },
          ],
        data: {
          dept:'',
          permission:'product',
          dup:'',
          sl:0,
       },
       from_month:[],
       to_month:[],
       sort:{
        org_id:'',
        from_date:new Date().getDate()+'-'+(new Date().getMonth()+1)+'-'+new Date().getFullYear(),
        to_date:new Date().getDate()+'-'+(new Date().getMonth()+1)+'-'+new Date().getFullYear(),
        from_month:'',
        to_month:'',
        from_year:new Date().getFullYear(),
        to_year:new Date().getFullYear(),
        m_from_year:new Date().getFullYear(),
        m_to_year:new Date().getFullYear(),
        filter:0,
        dept:'',
        user_id:'',
        batch:1,
        product:'',
        stock:5,
        sort_by:'ASC',
        type:'',
        batch:0,
        search:'',
        page:1,
        limit:100,
      },
      dayReport:[],
      monthReport:[],
      yearReport:[],
      totalReport:[],
    }
  },
  watch: {
    'sort.page' : function(value) {
      this.sort.page = value;
    },
   'sort.from_date': function(){
      this.sort.page = 1;
    },
   'sort.to_date': function(){
     this.sort.page = 1;
    },
    'sort.from_year': function(){
      if(this.sort.filter==2){
        this.sort.page = 1;
        this.sort.from_date='01'+'-'+'01'+'-'+this.sort.from_year;
      }
    },
    'sort.to_year': function(){
     if(this.sort.filter==2){
      this.sort.page = 1;
      this.sort.to_date='31'+'-'+'12'+'-'+this.sort.to_year;
     }
    },
    'from_month': function(newData){
      if(this.sort.filter==1){
        this.sort.from_month = newData ? newData.value : '';
        this.sort.page = 1;
        this.sort.from_date='01'+'-'+this.sort.from_month+'-'+this.sort.m_from_year;
      }
    },
    'to_month': function(newData){
      if(this.sort.filter==1){
        this.sort.to_month = newData ? newData.value : '';
        this.sort.page = 1;
        this.sort.to_date='31'+'-'+this.sort.to_month+'-'+this.sort.m_to_year;
      }
    },
    'sort.m_from_year': function(){
      if(this.sort.filter==1){
        this.sort.page = 1;
        this.sort.from_date='01'+'-'+this.sort.from_month+'-'+this.sort.m_from_year;
      }
    },
    'sort.m_to_year': function(){
     if(this.sort.filter==1){
      this.sort.page = 1;
      this.sort.to_date='01'+'-'+this.sort.to_month+'-'+this.sort.m_to_year;
     } 
    },
   'sort.filter': function(){
      this.sort.page = 1;
      this.dayReport=[];
      this.monthReport=[];
      this.yearReport=[];
      this.totalReport=[];
     if(this.sort.filter==0){
      this.sort.from_date=new Date().getDate()+'-'+(new Date().getMonth()+1)+'-'+new Date().getFullYear();
      this.sort.to_date=new Date().getDate()+'-'+(new Date().getMonth()+1)+'-'+new Date().getFullYear();
     }
     if(this.sort.filter==1){
      this.from_month = this.monthOptions.find(function (m) { return m.value ==  (new Date().getMonth()+1)});     
      this.to_month = this.monthOptions.find(function (m) { return m.value ==  (new Date().getMonth()+1)});
      this.sort.from_month = (new Date().getMonth()+1);     
      this.sort.to_month = (new Date().getMonth()+1);
      this.sort.m_from_year=new Date().getFullYear();
      this.sort.m_to_year=new Date().getFullYear();
      this.sort.from_date='01'+'-'+this.sort.from_month+'-'+this.sort.m_from_year;
      this.sort.to_date='31'+'-'+this.sort.to_month+'-'+this.sort.m_to_year;
     }
     if(this.sort.filter==2){
      this.sort.from_year=new Date().getFullYear();
      this.sort.to_year=new Date().getFullYear();
      this.sort.from_date='01'+'-'+'01'+'-'+this.sort.from_year;
      this.sort.to_date='31'+'-'+'12'+'-'+this.sort.to_year;
     } 
    },
    org_id: function (newData) {
      this.dept = "";
      this.sort.org_id = newData ? newData.id : "";
      if (this.sort.org_id != "All") {
        this.$store
          .dispatch("user_management/loadStockDepartments", {
            organizationId: newData.id,
          })
          .then((departments) => {
            this.departments = departments.data;
            if (localStorage.getItem("departmentId"))
              this.dept = this.departments.find(function (dept) {
                return dept.id == localStorage.getItem("departmentId");
              });
          })
          .catch((err) => {
          console.error(err);
          });
      } 
      else {
        this.dept = this.departments.find(function (dept) {
          return dept.id == "All";
        });
      }
    },
    dept: function (newData) {
      this.sort.dept = newData ? newData.id : "";
    },
  },
  computed: {
   years () {
      this.current_year = new Date().getFullYear()
      this.minDate = new Date();
      this.maxDate = new Date();
      const year = new Date().getFullYear()
      return Array.from({length: year - 2000}, (value, index) => year - index)
    },
    displayDate () {
         return (year) => {
           var date = new Date(year);
           return date.getDate().toString().padStart(2, "0")+"-"+(date.getMonth()+1).toString().padStart(2, "0")+"-"+date.getFullYear()
          }
    },
    displayTime () {
      return (year) => {
        var date = new Date(year);
        var hours = date.getHours();
        return hours + ":" + date.getMinutes() 
      }
    },
    usersData () {
      return this.$store.state.Purchase_entrys.stock_reports
    }, 
  },
  methods: {
    UserPointView(id){
      window.open(`/user_point/edit/${id}`).catch(() => {}, "_blank")
    },
     fetchInv() {
      this.dayReport=[];
      this.monthReport=[];
      this.yearReport=[];
      this.totalReport=[];
      this.openLoading();
       this.$store.dispatch('inv_report/fetchInvDashboardReport', { sort: this.sort })
         .then((report) => { 
          if(this.sort.filter==0){
            this.dayReport = report.data.result;
          }
          if(this.sort.filter==1){
            this.monthReport = report.data.month;
          }
          if(this.sort.filter==2){
            this.yearReport = report.data.year;
          }
          this.totalReport = report.data.total;
          this.$vs.loading.close();
         })
         .catch(err => { console.error(err)
         this.$vs.loading.close();
         })
         this.$vs.loading.close()
     },
  moment: function () {
    return moment();
  },
  openLoading(){
    this.$vs.loading()
  },
  currentDate(expiry_date) {
      var now = moment(new Date()); 
      var end = moment(expiry_date); 
      var duration =  end.diff(now, 'days') 
      return duration;
  },
  timeConvert (time) {
      time = time.substring(0,5)
      time = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];
      if (time.length > 1) { 
        time = time.slice (1); 
        time[5] = +time[0] < 12 ? 'AM' : 'PM';
        time[0] = +time[0] % 12 || 12; 
      }
      return time.join ('');
  },
 
  },
  mounted () { 
  },
  created () {
   if (!moduleUserManagement.isRegistered) {
      this.$store.registerModule('userManagement', moduleUserManagement)
      moduleUserManagement.isRegistered = true
    }
    this.$store.dispatch("organizations/loadParents").then((parents) => {
      this.parents = parents.data;
        this.org_id = this.parents.find(function (org_id) {
          return org_id.id == localStorage.getItem("organizationId");
        });
    });
  }
}
</script>
<style lang="scss">
 .verified-class{
    background-color: #A9A9A9;
    padding: 1px 16px 3px 4px;
    border: 1px solid #99ffe6;
    margin-right: 5px;
    border-radius: 5px;
  }
 .whitebg{
    background:white
  }
 .graybg{
    background:#A9A9A9
 }
#page-patient-list {
  .patient-list-filters {
    .vs__actions {
      position: absolute;
      right: 0;
      top: 50%;
      transform: translateY(-58%);
    }
  }
}
</style>
