<!DOCTYPE html>
<html>
<head>
<style>
@font-face { font-family: kitfont; src: url('1979 Dot Matrix Regular.TTF'); } 

#customers {
  font-family: Verdana, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}
#customers td{
  border: 1px solid black;
  padding: 2px;
  text-align: center;
   font-size: 50%;
}
 #customers th {
  border: 1px solid black;
  padding: 4px;
  text-align: center;
   font-size: 55%;
}
#product{
 
 border: 1px solid black;
 padding: 8px;
  font-size: 50%;
text-align: center;

}
.heading {
  font-family: verdana;
  font-size: 200%;  
}    
 h3 {
  text-align: center;
  font-family: verdana;
  font-size: 150%;'  
}
h6 {
  text-align: center;
  font-family: verdana;
  font-size: 100%;' 
}
.purchase {
  color: black;
  text-align: center;
  font-family: verdana;
  font-size: 110%;
  text-decoration: underline;
}
.left{
  color: black;
  font-family: verdana;
  font-size: 50%;
  text-align: left;
}
 .right{
  color: black;
  font-family: verdana;
  font-size: 90%;
  text-align: right;
}
 h5 {
 
  font-family: verdana;
  text-align: left;
}
.alignright {
  float: right;
}
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

   </style>
      
   </head>
    <body >
    
    <p class="purchase">Inv Dashboard Report</p>
      <table  id="customers">
      <thead>
        <tr>
          <th>Sl No</th>
          <th>Date</th>
          <th>Organization</th>
          <th>Department</th>
          <th>Purchase Payable</th>
          <th>Purchase Return</th>
          <th>Credit Note</th>
          <th>Supplier Payment</th>
          <th>Sales</th>
          <th>Sales Return</th> 
        </tr>
     </thead>
     <tbody>
         @foreach($report as $key =>$d)
          <tr>
              <td id="product"> {{$key+1}} </td>
              <td id="product">
              @if($d->filter=='0')
                <p>{{$d->created_at}}</p>
              @endif

              @if($d->filter=='1')
                @if($d->month=='01')
                  <p>{{$d->year}} January</p>
                @endif
                @if($d->month=='02')
                  <p>{{$d->year}} February</p>
                @endif
                @if($d->month=='03')
                  <p>{{$d->year}} March</p>
                @endif
                @if($d->month=='04')
                  <p>{{$d->year}} April</p>
                @endif
                @if($d->month=='05')
                  <p>{{$d->year}} May</p>
                @endif
                @if($d->month=='06')
                  <p>{{$d->year}} June</p>
                @endif
                @if($d->month=='07')
                  <p>{{$d->year}} July</p>
                @endif
                @if($d->month=='08')
                  <p>{{$d->year}} August</p>
                @endif
                @if($d->month=='09')
                  <p>{{$d->year}} September</p>
                @endif
                @if($d->month=='10')
                  <p>{{$d->year}} October</p>
                @endif
                @if($d->month=='11')
                  <p>{{$d->year}} November</p>
                @endif
                @if($d->month=='12')
                  <p>{{$d->year}} December</p>
                @endif
              @endif

              @if($d->filter=='2')
                <p>{{$d->year}}</p>
              @endif
               
              </td>
              <td id="product"> {{$d->org_name}} </td>
              <td id="product"> {{$d->dept_name}} </td>
              <td id="product"> {{number_format($d->purchase_entry_amount, 2, '.', ',')}}</td>
              <td id="product"> {{number_format($d->purchase_return_amount, 2, '.', ',')}}</td>
              <td id="product"> {{number_format($d->credit_amount, 2, '.', ',')}}</td>
              <td id="product"> {{number_format($d->supplier_payment_amount, 2, '.', ',')}}</td>
              <td id="product"> {{number_format($d->sales_amount, 2, '.', ',')}}</td>
              <td id="product"> {{number_format($d->sales_return_amount, 2, '.', ',')}}</td>
          </tr>
         @endforeach

         @foreach($total as $key =>$t)
         <tr> 
              <td colspan="4">Total</td>
              <td>{{$t->total_purchase_entry_amount}}</td>
              <td>{{$t->total_purchase_return_amount}}</td>
              <td>{{$t->total_credit_amount}}</td>
              <td>{{$t->total_supplier_payment_amount}}</td>
              <td>{{$t->total_sales_amount}}</td>   
              <td>{{$t->total_sales_return_amount}}</td>   
          </tr>
          @endforeach

   </tbody>
   </table><br>
          
</body >
</html>