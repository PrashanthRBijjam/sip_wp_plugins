<?php

get_header();

if(current_user_can("executive_dashboards")){
    if(pods_v( 'last', 'url' )==0){
    echo "You are not authorised";
    } else
    $payment = pods('payment', pods_v( 'last', 'url' ));
} elseif(current_user_can("user_dashboards")){
    $row = pods('payment', pods_v( 'last', 'url' ));
    $payer_id = ($row->field("payer"))['ID'];
    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;
    if($user_id==$payer_id){
        $payment = pods('payment', pods_v( 'last', 'url' ));
    }else{
        echo "You are not allowed to view others receipts";
        exit();
    }
} else {
    
    echo "You are not authorised, please contact the site administrator.";
    exit();
}

?>


<html>
    <head>
    <style>
        table {
                font-family: arial, sans-serif;
                border: 5px solid #dddddd;
                width: 800px;
                margin: 20px, 20px, 20px, 20px;
                }
        table.center {
            margin-left:auto; 
            margin-right:auto;
        }
        th {
            text-align: center;
            padding: 8px;
            border: 0px
        }
        th {
            text-align: center;
            padding: 20px;
            border: 0px
        }
        td {
            border: 0px
        }
        .btn-print{
            background-color: white; 
            color: black; 
            border: 2px solid #FFAF06;
        }
        .btn-print:hover{
            background-color: #FFAF06; 
            color: white; 
            border: 2px solid white;
        }
</style>
    </head>
    <table class="center">
        <tr>
            <th style="text-align:center" colspan="3"><h4>Sri Indraprastha Colony Residents' Welfare Association</h4><br/><h5>Regd.No.3052 of 1990</h5></th>
        </tr>
        <tr><td style="text-align:center" colspan="3">Sri Indraprastha Colony, GSI Post, Bandlaguda, Nagole, Hyderabad - 500 068.</td></tr>
        <tr><td style="text-align:center" colspan="3"><h4><u>RECEIPT</u></h4></td></tr>
        <tr><td style="text-align:left"colspan="2"><b>Record No. </b><?php echo $payment->field("id") ?></td><td style="text-align:right"><b>Date:</b> <?php echo $payment->field("created") ?></td></tr>
        <tr><td colspan="3">RECEIVED with thanks from Sri <u>  <?php echo $payment->field("payer")["display_name"] ?>  </u> House No. / Plot No. <u><?php echo $payment->field("house_no")['house_no'] ?>   </u>  
        <?php 
        
        $mode = $payment->field("mode");
        if($mode=="Cash") echo "by <u>Cash</u> an";
        else if ($mode=="Cheque") echo "by <u>Cheque</u> an";
        else if ($mode=="Water Fund") echo "available <u>Water Fund</u>";
        ?> amount of Rs. <u><?php echo $payment->field("amount") ?></u>
        <?php echo "( " .getIndianCurrency($payment->field("amount")) . " only )" ?> towards <b><?php echo $payment->display("category") ?></b> .
        </td></tr>
        
        <tr><td colspan="3"><b>References / Remarks : </b><?php echo $payment->field("reference") ?></td></tr>
        <tr><td colspan="3"><?php echo $payment->display("attachments") ?></td></tr>
        
        
        <tr><td colspan="3" style="text-align:right"> <b>Generated by</b> </td></tr>
        <tr><td colspan="3" style="text-align:right"> <?php echo $payment->field("author")['display_name'] ?> </td></tr>
    </table>
</html>
<?php
get_footer();

function getIndianCurrency(float $number)
{
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $hundred = null;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(0 => '', 1 => 'One', 2 => 'Two',
        3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
        7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
        10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
        13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
        16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
        19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
        40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
        70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
    $digits = array('', 'Hundred','Thousand','Lakh', 'Crore');
    while( $i < $digits_length ) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
        } else $str[] = null;
    }
    $Rupees = implode('', array_reverse($str));
    $paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
    return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
}