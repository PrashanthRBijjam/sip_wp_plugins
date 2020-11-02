<?php

/* Template Name: Download Payments list */
if(current_user_can( 'executive_dashboards' )){


$payments = pods('payment', array('orderby' => 'id asc', 'limit' => -1));

$all_payments = $payments->export_data();

$all_sanitized_payments = array();
$datetime = date("Ymd_hi");


foreach($all_payments as $payment){
    
    $sanitized_payment = array("id"=>$payment['id'],"date"=>$payment['created'],"description"=>$payment['name'],"category"=>$payment['category'],"payer"=>$payment['payer']['display_name'],"amount"=>$payment['amount'],"mode"=>$payment['mode'],"status"=>$payment['status'], "reference"=>$payment['reference'], "author"=>$payment['author']['display_name'] , "remarks"=>$payment['remarks']);
    
    array_push($all_sanitized_payments,$sanitized_payment);
}
 
$output = fopen("php://output",'w') or die("Can't open php://output");
header("Content-Type:application/csv"); 
header("Content-Disposition:attachment;filename=payments_$datetime.csv"); 
fputcsv($output, array('id', 'date','description','category','payer','amount', 'mode','status','reference','author','remarks'));
foreach($all_sanitized_payments as $product) {
    fputcsv($output, $product);
}

fclose($output) or die("Can't close php://output");
}

?>