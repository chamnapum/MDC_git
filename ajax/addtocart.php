<?php require_once('../Connections/magazinducoin.php');

session_start();
if (!function_exists("GetSQLValueString")) {

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 

{

  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;



  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);



  switch ($theType) {

    case "text":

      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";

      break;    

    case "long":

    case "int":

      $theValue = ($theValue != "") ? intval($theValue) : "NULL";

      break;

    case "double":

      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";

      break;

    case "date":

      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";

      break;

    case "defined":

      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;

      break;

  }

  return $theValue;

}

}


if(!isset($_SESSION['coupons'])) {

	$_SESSION['coupons'] = array();

}

$id_coupon  = $_GET['id_coupon'];

//echo $_SESSION['coupons'][$id_coupon];

if($_SESSION['coupons'][$id_coupon]!=$_GET['id_coupon']){
	//echo $_SESSION['coupons'][$id_coupon];
	
	$sql_select = "SELECT id_coupon, count_click FROM coupons WHERE id_coupon='".$_GET['id_coupon']."'";
	$query_select = mysql_query($sql_select);
	$rs=mysql_fetch_array($query_select);
	
	$count = $rs['count_click']+1;

	$sql_pro  = "UPDATE coupons SET count_click='".$count."' WHERE id_coupon='".$_GET['id_coupon']."'";
	$result_pro  = mysql_query($sql_pro ) or die (mysql_error());
	
}

if(!isset($_SESSION['coupons'][$id_coupon])){

	$_SESSION['coupons'][$id_coupon] = $id_coupon;
	
}



//print_r($_SESSION['coupons']);

//print_r($_SESSION['cart'][$id]);



include("../modules/cart.php"); 



?>