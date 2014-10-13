<?php require_once('../Connections/magazinducoin.php'); ?>
<?php
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


if(!isset($_SESSION['courses'])) {
	$_SESSION['courses'] = array();
}
$id_produit  = $_GET['id_produit'];

if($_SESSION['courses'][$id_produit]!=$_GET['id_produit']){	
	$sql_select = "SELECT id, count_click FROM produits WHERE id='".$_GET['id_produit']."'";
	$query_select = mysql_query($sql_select);
	$rs=mysql_fetch_array($query_select);
	
	$count = $rs['count_click']+1;

	$sql_pro  = "UPDATE produits SET count_click='".$count."' WHERE id='".$_GET['id_produit']."'";
	$result_pro  = mysql_query($sql_pro ) or die (mysql_error());
	
}

if(!isset($_SESSION['courses'][$id_produit])){
	
	$_SESSION['courses'][$id_produit] = $id_produit ;
	
}

//echo count($_SESSION['courses']);
//print_r($_SESSION['courses']);

include("../modules/liste_course.php"); 

?>