<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Inv_dashboard_report;
use Validator;
use Exception;
use PDF;


class InvReportController extends Controller
{
    public function InvReport(Request $request)
    {
        try{
            $checkPermission = $this->checkPermission('doctor','can_view');
            if($checkPermission['allow_access'])
            {
                $day_array=array();
                $DayArray=array();
                $month_array=array();
                $MonthArray=array();
                $year_array=array();
                $YearArray=array();
                $inv = Inv_dashboard_report::leftJoin('departments', 'inv_dashboard_reports.dept_id', 'departments.id')
                    ->leftJoin('organizations', 'inv_dashboard_reports.org_id', 'organizations.id')
                    ->orderBy('created_at','ASC')
                    ->select('inv_dashboard_reports.*'
                    ,DB::raw("CONCAT(departments.name,' [',departments.code,']') AS dept_name")
                    ,DB::raw("CONCAT(organizations.name,' [',organizations.code,']') AS org_name"));
                    if(isset($request->from_date)){
                        $inv=$inv->whereDate('inv_dashboard_reports.created_at','>=',date('Y-m-d',strtotime($request->from_date)));
                    }
                    if(isset($request->to_date)){
                        $inv=$inv->whereDate('inv_dashboard_reports.created_at','<=',date('Y-m-d',strtotime($request->to_date)));
                    }
                    if(isset($request->dept) && $request->dept!='All'){
                        $inv=$inv->where('inv_dashboard_reports.dept_id',$request->dept);
                    }
                    if(isset($request->org_id) && $request->org_id!='All'){
                        $inv=$inv->where('inv_dashboard_reports.org_id',$request->org_id);
                    }
                $inv=$inv->get();
                if($request->filter==0){
                    if(isset($inv)){
                        foreach ($inv as $key => $i){
                            $c = substr($i->created_at, 0,10);                           
                            $key=$c;
                            if(isset($day_array[$key])){
                                $day_array[$key]['purchase_entry_amount']=$day_array[$key]['purchase_entry_amount']+$i->purchase_entry_amount;
                                $day_array[$key]['purchase_return_amount']=$day_array[$key]['purchase_return_amount']+$i->purchase_return_amount;
                                $day_array[$key]['credit_amount']=$day_array[$key]['credit_amount']+$i->credit_amount;
                                $day_array[$key]['supplier_payment_amount']=$day_array[$key]['supplier_payment_amount']+$i->supplier_payment_amount;
                                $day_array[$key]['sales_amount']=$day_array[$key]['sales_amount']+$i->sales_amount;
                                $day_array[$key]['sales_return_amount']=$day_array[$key]['sales_return_amount']+$i->sales_return_amount;
                            }
                            else{
                                $day_array[$key]=array();
                                $day_array[$key]['created_at']=$c;
                                $day_array[$key]['org_name']=$i->org_name;
                                $day_array[$key]['dept_name']=$i->dept_name;
                                $day_array[$key]['purchase_entry_amount']=$i->purchase_entry_amount;
                                $day_array[$key]['purchase_entry_amount']=$i->purchase_entry_amount;
                                $day_array[$key]['purchase_return_amount']=$i->purchase_return_amount;
                                $day_array[$key]['credit_amount']=$i->credit_amount;
                                $day_array[$key]['supplier_payment_amount']=$i->supplier_payment_amount;
                                $day_array[$key]['sales_amount']=$i->sales_amount;
                                $day_array[$key]['sales_return_amount']=$i->sales_return_amount;
                            }
                        }   
                    }
                    $DayArray=array();
                    if(isset($day_array)){
                        foreach ($day_array as $key =>$d){
                            array_push($DayArray, $d);     
                        }
                    }  
                }
                if($request->filter==1){
                    if(isset($inv)){
                    foreach ($inv as $key => $i){
                        $y = substr($i->created_at, 0,4);
                        $m = substr($i->created_at, 5,2);
                        $key=$m.'_'.$y;
                        if(isset($month_array[$key])){
                            $month_array[$key]['purchase_entry_amount']=$month_array[$key]['purchase_entry_amount']+$i->purchase_entry_amount;
                            $month_array[$key]['purchase_return_amount']=$month_array[$key]['purchase_return_amount']+$i->purchase_return_amount;
                            $month_array[$key]['credit_amount']=$month_array[$key]['credit_amount']+$i->credit_amount;
                            $month_array[$key]['supplier_payment_amount']=$month_array[$key]['supplier_payment_amount']+$i->supplier_payment_amount;
                            $month_array[$key]['sales_amount']=$month_array[$key]['sales_amount']+$i->sales_amount;
                            $month_array[$key]['sales_return_amount']=$month_array[$key]['sales_return_amount']+$i->sales_return_amount;
                        }
                        else{
                            $month_array[$key]=array();
                            $month_array[$key]['month']=$m;
                            $month_array[$key]['year']=$y;
                            $month_array[$key]['org_name']=$i->org_name;
                            $month_array[$key]['dept_name']=$i->dept_name;
                            $month_array[$key]['purchase_entry_amount']=$i->purchase_entry_amount;
                            $month_array[$key]['purchase_return_amount']=$i->purchase_return_amount;
                            $month_array[$key]['credit_amount']=$i->credit_amount;
                            $month_array[$key]['supplier_payment_amount']=$i->supplier_payment_amount;
                            $month_array[$key]['sales_amount']=$i->sales_amount;
                            $month_array[$key]['sales_return_amount']=$i->sales_return_amount;
                        }
                    }
                    }
                    $MonthArray=array();
                    if(isset($month_array)){
                        foreach ($month_array as $key =>$m){
                            array_push($MonthArray, $m);     
                        }
                    }  
                }
                if($request->filter==2){
                    if(isset($inv)){
                    foreach ($inv as $key => $i){
                        $y = substr($i->created_at, 0,4);
                        $key=$y;
                        if(isset($year_array[$key])){
                            $year_array[$key]['purchase_entry_amount']=$year_array[$key]['purchase_entry_amount']+$i->purchase_entry_amount;
                            $year_array[$key]['purchase_return_amount']=$year_array[$key]['purchase_return_amount']+$i->purchase_return_amount;
                            $year_array[$key]['credit_amount']=$year_array[$key]['credit_amount']+$i->credit_amount;
                            $year_array[$key]['supplier_payment_amount']=$year_array[$key]['supplier_payment_amount']+$i->supplier_payment_amount;
                            $year_array[$key]['sales_amount']=$year_array[$key]['sales_amount']+$i->sales_amount;
                            $year_array[$key]['sales_return_amount']=$year_array[$key]['sales_return_amount']+$i->sales_return_amount;
                        }
                        else{
                            $year_array[$key]=array();
                            $year_array[$key]['year']=$y;
                            $year_array[$key]['org_name']=$i->org_name;
                            $year_array[$key]['dept_name']=$i->dept_name;
                            $year_array[$key]['purchase_entry_amount']=$i->purchase_entry_amount;
                            $year_array[$key]['purchase_return_amount']=$i->purchase_return_amount;
                            $year_array[$key]['credit_amount']=$i->credit_amount;
                            $year_array[$key]['supplier_payment_amount']=$i->supplier_payment_amount;
                            $year_array[$key]['sales_amount']=$i->sales_amount;
                            $year_array[$key]['sales_return_amount']=$i->sales_return_amount;
                        }
                    }
                }
                $YearArray=array();
                    if(isset($year_array)){
                        foreach ($year_array as $key =>$y){
                            array_push($YearArray, $y);     
                        }
                    }  
                }
                $total = Inv_dashboard_report::select(DB::raw("SUM(purchase_entry_amount) as total_purchase_entry_amount")
                                ,DB::raw("SUM(purchase_return_amount) as total_purchase_return_amount")
                                ,DB::raw("SUM(credit_amount) as total_credit_amount")
                                ,DB::raw("SUM(supplier_payment_amount) as total_supplier_payment_amount")
                                ,DB::raw("SUM(sales_amount) as total_sales_amount")
                                ,DB::raw("SUM(sales_return_amount) as total_sales_return_amount"))
                                ->orderBy('created_at','ASC');
                    if(isset($request->from_date)){
                        $total=$total->whereDate('created_at','>=',date('Y-m-d',strtotime($request->from_date)));
                    }
                    if(isset($request->to_date)){
                        $total=$total->whereDate('created_at','<=',date('Y-m-d',strtotime($request->to_date)));
                    }
                    if(isset($request->dept) && $request->dept!='All'){
                        $total=$total->where('dept_id',$request->dept);
                    }
                    if(isset($request->org_id) && $request->org_id!='All'){
                        $total=$total->where('org_id',$request->org_id);
                    }
                    $total=$total->get();
                return response()->json(['success' => true,'result' =>$DayArray
                ,'month' =>$MonthArray,'month' =>$MonthArray,'year' =>$YearArray,'total' =>$total],200);
            }else{
                return response()->json(['success' => false,'error' =>'Access Denied'],200);
            }
        }catch(Exception $e){
            return $this->logError("InvReportController.InvReport",$e->getMessage(),$request->user()->id,$request->all());
        }
    }

    public function downloadDayReport(Request $request,$dept,$org_id,$to_date,$from_date)
    {
        try{
            $checkPermission = $this->checkPermission('product_batchs','can_view');
            if($checkPermission['allow_access'])
            {
                $day_array=array();
                $DayArray=array();
                $inv = Inv_dashboard_report::leftJoin('departments', 'inv_dashboard_reports.dept_id', 'departments.id')
                    ->leftJoin('organizations', 'inv_dashboard_reports.org_id', 'organizations.id')
                    ->orderBy('created_at','ASC')
                    ->select('inv_dashboard_reports.*'
                    ,DB::raw("CONCAT(departments.name,' [',departments.code,']') AS dept_name")
                    ,DB::raw("CONCAT(organizations.name,' [',organizations.code,']') AS org_name"));
                    if(isset($request->from_date)){
                        $inv=$inv->whereDate('inv_dashboard_reports.created_at','>=',date('Y-m-d',strtotime($request->from_date)));
                    }
                    if(isset($request->to_date)){
                        $inv=$inv->whereDate('inv_dashboard_reports.created_at','<=',date('Y-m-d',strtotime($request->to_date)));
                    }
                    if(isset($request->dept) && $request->dept!='All'){
                        $inv=$inv->where('inv_dashboard_reports.dept_id',$request->dept);
                    }
                    if(isset($request->org_id) && $request->org_id!='All'){
                        $inv=$inv->where('inv_dashboard_reports.org_id',$request->org_id);
                    }
                $inv=$inv->get();
                if(isset($inv)){
                        foreach ($inv as $key => $i){
                            $c = substr($i->created_at, 0,10);                           
                            $key=$c;
                            if(isset($day_array[$key])){
                                $day_array[$key]['purchase_entry_amount']=$day_array[$key]['purchase_entry_amount']+$i->purchase_entry_amount;
                                $day_array[$key]['purchase_return_amount']=$day_array[$key]['purchase_return_amount']+$i->purchase_return_amount;
                                $day_array[$key]['credit_amount']=$day_array[$key]['credit_amount']+$i->credit_amount;
                                $day_array[$key]['supplier_payment_amount']=$day_array[$key]['supplier_payment_amount']+$i->supplier_payment_amount;
                                $day_array[$key]['sales_amount']=$day_array[$key]['sales_amount']+$i->sales_amount;
                                $day_array[$key]['sales_return_amount']=$day_array[$key]['sales_return_amount']+$i->sales_return_amount;
                            }
                            else{
                                $day_array[$key]=array();
                                $day_array[$key]['filter']=0;
                                $day_array[$key]['created_at']=$c;
                                $day_array[$key]['org_name']=$i->org_name;
                                $day_array[$key]['dept_name']=$i->dept_name;
                                $day_array[$key]['purchase_entry_amount']=$i->purchase_entry_amount;
                                $day_array[$key]['purchase_entry_amount']=$i->purchase_entry_amount;
                                $day_array[$key]['purchase_return_amount']=$i->purchase_return_amount;
                                $day_array[$key]['credit_amount']=$i->credit_amount;
                                $day_array[$key]['supplier_payment_amount']=$i->supplier_payment_amount;
                                $day_array[$key]['sales_amount']=$i->sales_amount;
                                $day_array[$key]['sales_return_amount']=$i->sales_return_amount;
                            }
                        }  
                        $report=array();
                        if(isset($day_array)){
                            foreach ($day_array as $key =>$d){
                                array_push($report, $d);     
                            }
                        }  
                       
                    }
                $total = Inv_dashboard_report::select(DB::raw("SUM(purchase_entry_amount) as total_purchase_entry_amount")
                            ,DB::raw("SUM(purchase_return_amount) as total_purchase_return_amount")
                            ,DB::raw("SUM(credit_amount) as total_credit_amount")
                            ,DB::raw("SUM(supplier_payment_amount) as total_supplier_payment_amount")
                            ,DB::raw("SUM(sales_amount) as total_sales_amount")
                            ,DB::raw("SUM(sales_return_amount) as total_sales_return_amount"))
                            ->orderBy('created_at','ASC');
                if(isset($request->from_date)){
                    $total=$total->whereDate('created_at','>=',date('Y-m-d',strtotime($request->from_date)));
                }
                if(isset($request->to_date)){
                    $total=$total->whereDate('created_at','<=',date('Y-m-d',strtotime($request->to_date)));
                }
                if(isset($request->dept) && $request->dept!='All'){
                    $total=$total->where('dept_id',$request->dept);
                }
                if(isset($request->org_id) && $request->org_id!='All'){
                    $total=$total->where('org_id',$request->org_id);
                }
                $total=$total->get();
                $report=json_encode($report);
                $report=json_decode($report);
                view()->share('report',$report);
                view()->share('total',$total);
                $pdf = PDF::loadView('inventory.day_report_print',compact('report','total'));
                return $pdf->download('Day Report.pdf');
                return response()->json(['success' => true,'result' => $download],200);
            }else{
              return response()->json(['success' => false,'error' => 'Access Denied'],200);
           }
        }catch(Exception $e){
            return $this->logError("InvReportController.downloadDayReport",$e->getMessage());
        }
    }


    public function downloadMonthReport(Request $request,$dept,$org_id,$to_date,$from_date)
    {
        try{
            $checkPermission = $this->checkPermission('product_batchs','can_view');
            if($checkPermission['allow_access'])
            {
                $month_array=array();
                $MonthArray=array();
                $inv = Inv_dashboard_report::leftJoin('departments', 'inv_dashboard_reports.dept_id', 'departments.id')
                    ->leftJoin('organizations', 'inv_dashboard_reports.org_id', 'organizations.id')
                    ->orderBy('created_at','ASC')
                    ->select('inv_dashboard_reports.*'
                    ,DB::raw("CONCAT(departments.name,' [',departments.code,']') AS dept_name")
                    ,DB::raw("CONCAT(organizations.name,' [',organizations.code,']') AS org_name"));
                    if(isset($request->from_date)){
                        $inv=$inv->whereDate('inv_dashboard_reports.created_at','>=',date('Y-m-d',strtotime($request->from_date)));
                    }
                    if(isset($request->to_date)){
                        $inv=$inv->whereDate('inv_dashboard_reports.created_at','<=',date('Y-m-d',strtotime($request->to_date)));
                    }
                    if(isset($request->dept) && $request->dept!='All'){
                        $inv=$inv->where('inv_dashboard_reports.dept_id',$request->dept);
                    }
                    if(isset($request->org_id) && $request->org_id!='All'){
                        $inv=$inv->where('inv_dashboard_reports.org_id',$request->org_id);
                    }
                $inv=$inv->get();
                if(isset($inv)){
                    foreach ($inv as $key => $i){
                        $y = substr($i->created_at, 0,4);
                        $m = substr($i->created_at, 5,2);
                        $key=$m.'_'.$y;
                        if(isset($month_array[$key])){
                            $month_array[$key]['purchase_entry_amount']=$month_array[$key]['purchase_entry_amount']+$i->purchase_entry_amount;
                            $month_array[$key]['purchase_return_amount']=$month_array[$key]['purchase_return_amount']+$i->purchase_return_amount;
                            $month_array[$key]['credit_amount']=$month_array[$key]['credit_amount']+$i->credit_amount;
                            $month_array[$key]['supplier_payment_amount']=$month_array[$key]['supplier_payment_amount']+$i->supplier_payment_amount;
                            $month_array[$key]['sales_amount']=$month_array[$key]['sales_amount']+$i->sales_amount;
                            $month_array[$key]['sales_return_amount']=$month_array[$key]['sales_return_amount']+$i->sales_return_amount;
                        }
                        else{
                            $month_array[$key]=array();
                            $month_array[$key]['filter']=1;
                            $month_array[$key]['month']=$m;
                            $month_array[$key]['year']=$y;
                            $month_array[$key]['org_name']=$i->org_name;
                            $month_array[$key]['dept_name']=$i->dept_name;
                            $month_array[$key]['purchase_entry_amount']=$i->purchase_entry_amount;
                            $month_array[$key]['purchase_return_amount']=$i->purchase_return_amount;
                            $month_array[$key]['credit_amount']=$i->credit_amount;
                            $month_array[$key]['supplier_payment_amount']=$i->supplier_payment_amount;
                            $month_array[$key]['sales_amount']=$i->sales_amount;
                            $month_array[$key]['sales_return_amount']=$i->sales_return_amount;
                        }
                    }
                    }
                    $MonthArray=array();
                    if(isset($month_array)){
                        foreach ($month_array as $key =>$m){
                            array_push($MonthArray, $m);     
                        }
                    }  
                $total = Inv_dashboard_report::select(DB::raw("SUM(purchase_entry_amount) as total_purchase_entry_amount")
                            ,DB::raw("SUM(purchase_return_amount) as total_purchase_return_amount")
                            ,DB::raw("SUM(credit_amount) as total_credit_amount")
                            ,DB::raw("SUM(supplier_payment_amount) as total_supplier_payment_amount")
                            ,DB::raw("SUM(sales_amount) as total_sales_amount")
                            ,DB::raw("SUM(sales_return_amount) as total_sales_return_amount"))
                            ->orderBy('created_at','ASC');
                if(isset($request->from_date)){
                    $total=$total->whereDate('created_at','>=',date('Y-m-d',strtotime($request->from_date)));
                }
                if(isset($request->to_date)){
                    $total=$total->whereDate('created_at','<=',date('Y-m-d',strtotime($request->to_date)));
                }
                if(isset($request->dept) && $request->dept!='All'){
                    $total=$total->where('dept_id',$request->dept);
                }
                if(isset($request->org_id) && $request->org_id!='All'){
                    $total=$total->where('org_id',$request->org_id);
                }
                $total=$total->get();
                $report=json_encode($MonthArray);
                $report=json_decode($report);
                view()->share('report',$report);
                view()->share('total',$total);
                $pdf = PDF::loadView('inventory.day_report_print',compact('report','total'));
                return $pdf->download('Day Report.pdf');
                return response()->json(['success' => true,'result' => $download],200);
            }else{
              return response()->json(['success' => false,'error' => 'Access Denied'],200);
           }
        }catch(Exception $e){
            return $this->logError("InvReportController.downloadMonthReport",$e->getMessage());
        }
    }

    public function downloadYearReport(Request $request,$dept,$org_id,$to_date,$from_date)
    {
        try{
            $checkPermission = $this->checkPermission('product_batchs','can_view');
            if($checkPermission['allow_access'])
            {
                $year_array=array();
                $YearArray=array();
                $inv = Inv_dashboard_report::leftJoin('departments', 'inv_dashboard_reports.dept_id', 'departments.id')
                    ->leftJoin('organizations', 'inv_dashboard_reports.org_id', 'organizations.id')
                    ->orderBy('created_at','ASC')
                    ->select('inv_dashboard_reports.*'
                    ,DB::raw("CONCAT(departments.name,' [',departments.code,']') AS dept_name")
                    ,DB::raw("CONCAT(organizations.name,' [',organizations.code,']') AS org_name"));
                    if(isset($request->from_date)){
                        $inv=$inv->whereDate('inv_dashboard_reports.created_at','>=',date('Y-m-d',strtotime($request->from_date)));
                    }
                    if(isset($request->to_date)){
                        $inv=$inv->whereDate('inv_dashboard_reports.created_at','<=',date('Y-m-d',strtotime($request->to_date)));
                    }
                    if(isset($request->dept) && $request->dept!='All'){
                        $inv=$inv->where('inv_dashboard_reports.dept_id',$request->dept);
                    }
                    if(isset($request->org_id) && $request->org_id!='All'){
                        $inv=$inv->where('inv_dashboard_reports.org_id',$request->org_id);
                    }
                $inv=$inv->get();
                if(isset($inv)){
                    foreach ($inv as $key => $i){
                        $y = substr($i->created_at, 0,4);
                        $key=$y;
                        if(isset($year_array[$key])){
                            $year_array[$key]['purchase_entry_amount']=$year_array[$key]['purchase_entry_amount']+$i->purchase_entry_amount;
                            $year_array[$key]['purchase_return_amount']=$year_array[$key]['purchase_return_amount']+$i->purchase_return_amount;
                            $year_array[$key]['credit_amount']=$year_array[$key]['credit_amount']+$i->credit_amount;
                            $year_array[$key]['supplier_payment_amount']=$year_array[$key]['supplier_payment_amount']+$i->supplier_payment_amount;
                            $year_array[$key]['sales_amount']=$year_array[$key]['sales_amount']+$i->sales_amount;
                            $year_array[$key]['sales_return_amount']=$year_array[$key]['sales_return_amount']+$i->sales_return_amount;
                        }
                        else{
                            $year_array[$key]=array();
                            $year_array[$key]['year']=$y;
                            $year_array[$key]['filter']=2;
                            $year_array[$key]['org_name']=$i->org_name;
                            $year_array[$key]['dept_name']=$i->dept_name;
                            $year_array[$key]['purchase_entry_amount']=$i->purchase_entry_amount;
                            $year_array[$key]['purchase_return_amount']=$i->purchase_return_amount;
                            $year_array[$key]['credit_amount']=$i->credit_amount;
                            $year_array[$key]['supplier_payment_amount']=$i->supplier_payment_amount;
                            $year_array[$key]['sales_amount']=$i->sales_amount;
                            $year_array[$key]['sales_return_amount']=$i->sales_return_amount;
                        }
                    }
                }
                $YearArray=array();
                    if(isset($year_array)){
                        foreach ($year_array as $key =>$y){
                            array_push($YearArray, $y);     
                        }
                    }  
                $total = Inv_dashboard_report::select(DB::raw("SUM(purchase_entry_amount) as total_purchase_entry_amount")
                            ,DB::raw("SUM(purchase_return_amount) as total_purchase_return_amount")
                            ,DB::raw("SUM(credit_amount) as total_credit_amount")
                            ,DB::raw("SUM(supplier_payment_amount) as total_supplier_payment_amount")
                            ,DB::raw("SUM(sales_amount) as total_sales_amount")
                            ,DB::raw("SUM(sales_return_amount) as total_sales_return_amount"))
                            ->orderBy('created_at','ASC');
                if(isset($request->from_date)){
                    $total=$total->whereDate('created_at','>=',date('Y-m-d',strtotime($request->from_date)));
                }
                if(isset($request->to_date)){
                    $total=$total->whereDate('created_at','<=',date('Y-m-d',strtotime($request->to_date)));
                }
                if(isset($request->dept) && $request->dept!='All'){
                    $total=$total->where('dept_id',$request->dept);
                }
                if(isset($request->org_id) && $request->org_id!='All'){
                    $total=$total->where('org_id',$request->org_id);
                }
                $total=$total->get();
                $report=json_encode($YearArray);
                $report=json_decode($report);
                view()->share('report',$report);
                view()->share('total',$total);
                $pdf = PDF::loadView('inventory.day_report_print',compact('report','total'));
                return $pdf->download('Inv Dashboard Report.pdf');
                return response()->json(['success' => true,'result' => $download],200);
            }else{
              return response()->json(['success' => false,'error' => 'Access Denied'],200);
           }
        }catch(Exception $e){
            return $this->logError("InvReportController.downloadYearReport",$e->getMessage());
        }
    }
   
}
