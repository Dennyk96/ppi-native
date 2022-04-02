<?php
 $errors = array();

 /*--------------------------------------------------------------*/
 /* Function for Remove escapes special
 /* characters in a string for use in an SQL statement
 /*--------------------------------------------------------------*/
function real_escape($str){
  global $con;
  $escape = mysqli_real_escape_string($con,$str);
  return $escape;
}
/*--------------------------------------------------------------*/
/* Function for Remove html characters
/*--------------------------------------------------------------*/
function remove_junk($str){
  $str = nl2br($str);
  $str = htmlspecialchars(strip_tags($str, ENT_QUOTES));
  return $str;
}
/*--------------------------------------------------------------*/
/* Function for Uppercase first character
/*--------------------------------------------------------------*/
function first_character($str){
  $val = str_replace('-'," ",$str);
  $val = ucfirst($val);
  return $val;
}
/*--------------------------------------------------------------*/
/* Function for Checking input fields not empty
/*--------------------------------------------------------------*/
function validate_fields($var){
  global $errors;
  foreach ($var as $field) {
    $val = remove_junk($_POST[$field]);
    if(isset($val) && $val==''){
      $errors = $field ." can't be blank.";
      return $errors;
    }
  }
}
/*--------------------------------------------------------------*/
/* Function for Display Session Message
   Ex echo display_msg($message);
/*--------------------------------------------------------------*/
function display_msg($msg ='Welcome PPI Report'){
   $output = array();
   if(!empty($msg)) {
      foreach ($msg as $key => $value) {
         $output  = "<div class=\"alert alert-{$key}\">";
         $output .= "<a href=\"#\" class=\"close\" data-dismiss=\"alert\">&times;</a>";
         $output .= remove_junk(first_character($value));
         $output .= "</div>";
      }
      return $output;
   } else {
     return "" ;
   }
}
/*--------------------------------------------------------------*/
/* Function for redirect
/*--------------------------------------------------------------*/
function redirect($url, $permanent = false)
{
    if (headers_sent() === false)
    {
      header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
    }

    exit();
}
/*--------------------------------------------------------------*/
/* Function for find out total saleing price, buying price and profit
/*--------------------------------------------------------------*/
function grand_total($grandTotals){
   $grand_total = 0;
   foreach($grandTotals as $total ){
     $grand_total += $total['invoice_grand_total'];
   }
   return array($grand_total);
}
/*--------------------------------------------------------------*/
/* Function for find out total saleing price, buying price and profit
/*--------------------------------------------------------------*/
function sub_total($subTotals){
  $sub_total = 0;
  foreach($subTotals as $total ){
    $sub_total += $total['invoiceSubTotal'];
  }
  return array($sub_total);
}
/*--------------------------------------------------------------*/
/* Function for find out total Qty
/*--------------------------------------------------------------*/
function total_qty($totalQty){
  $qty = 0;
  foreach($totalQty as $total ){
    $qty += $total['quantityTotal'];
  }
  return array($qty);
}
/*--------------------------------------------------------------*/
/* Function for find out total saleing price, buying price and profit
/*--------------------------------------------------------------*/
function total_payment($totalPayment){
  $pay = 0;
  foreach($totalPayment as $total ){
    $pay += $total['totalPay'];
  }
  return array($pay);
}
/*--------------------------------------------------------------*/
/* Function for Readable date time
/*--------------------------------------------------------------*/
function read_date($str){
     if($str)
      return date('d F Y', strtotime($str));
     else
      return null;
  }
/*--------------------------------------------------------------*/
/* Function for  Readable Make date time
/*--------------------------------------------------------------*/
function make_date(){
  return strftime("%Y-%m-%d %H:%M:%S", time());
}
/*--------------------------------------------------------------*/
/* Function for  Readable date time
/*--------------------------------------------------------------*/
function count_id(){
  static $count = 1;
  return $count++;
}
/*--------------------------------------------------------------*/
/* Function for Creting random string
/*--------------------------------------------------------------*/
function randString($length = 5)
{
  $str='';
  $cha = "0123456789abcdefghijklmnopqrstuvwxyz";

  for($x=0; $x<$length; $x++)
   $str .= $cha[mt_rand(0,strlen($cha))];
  return $str;
}
/*--------------------------------------------------------------*/
/* Function for Number Format
/*--------------------------------------------------------------*/
function price_format($value)
{
        return number_format($value, 2, ".", ",");
}
/*--------------------------------------------------------------*/
/* Function for Number Format dollar
/*--------------------------------------------------------------*/
function formatDollars($dollars){
  return '$ '.sprintf('%0.2f', $dollars);
}
/*--------------------------------------------------------------*/
/* Function for Format Date
/*--------------------------------------------------------------*/
function format_date($date)
{
  return date('Y-m-d', strtotime($date));
}
?>
