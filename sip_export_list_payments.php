<?php

$search_member = "";
$all_sanitized_payments = array();
$all_filtered_payments = array();

if(current_user_can( 'executive_dashboards' )){

    
    $payments = pods('payment', array('orderby' => 'id asc', 'limit' => -1));

    $all_payments = $payments->export_data();
    
    if(isset($_POST["submit"])){
          $search_member = $_POST["fname"];
          echo $search_member;
      }

    foreach($all_payments as $payment){
    
    $sanitized_payment = array("ID"=>$payment['id'],"Date"=>$payment['created'],"Description"=>$payment['name'],"Category"=>$payment['category'],"Payer"=>$payment['payer']['display_name'],"Amount"=>$payment['amount'],"Payment Mode"=>$payment['mode']);
    
    array_push($all_sanitized_payments,$sanitized_payment);
    }
    if($search_member==""){
        $all_filtered_payments = $all_sanitized_payments;
    } else {
        $all_filtered_payments = array_filter($array, function ($item) use ($search_member) {
    if (stripos($item['payer'], $search_member) !== false) {
        return true;
    }
    return false;
        });
    }
    
    
    
   
}
else{
    echo 'You are not authorized to view this page';
}
    
?>
<?php if (count($all_filtered_payments) > 0): ?>
<html>
<head>
<style>
table {
  border-collapse: collapse;
  width: 99%;
  margin-left: 8px;
}

th{
  text-align: left;
  padding: 8px;
  font-size: 15px;
}

td {
  text-align: left;
  padding: 8px;
  font-size: 14px;
}

tr:nth-child(even){background-color: #ffebcc}

th {
  background-color: #FFAF06;
  color: white;
}
</style>
</head>
<body>
<table>
    <tr>
        <td></td><h2>All Payments</h2></td>
    </tr>
</table>

<table>
  <thead>
    <tr>
      <th><?php echo implode('</th><th>', array_keys(current($all_filtered_payments))); ?></th>
    </tr>
  </thead>
  <tbody>
<?php foreach ($all_filtered_payments as $row): array_map('htmlentities', $row); ?>
    <tr>
      <td><?php echo implode('</td><td>', $row); ?></td>
    </tr>
<?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>
