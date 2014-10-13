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


if(!isset($_SESSION['event'])) {
	$_SESSION['event'] = array();
}
$id_event = $_GET['event'];

if($_SESSION['event'][$id_event]!=$_GET['event']){	
	$sql_select = "SELECT event_id, count_click FROM evenements WHERE event_id='".$_GET['event']."'";
	$query_select = mysql_query($sql_select);
	$rs=mysql_fetch_array($query_select);
	
	$count = $rs['count_click']+1;

	$sql_pro  = "UPDATE evenements SET count_click='".$count."' WHERE event_id='".$_GET['event']."'";
	$result_pro  = mysql_query($sql_pro ) or die (mysql_error());
	
}

if(!isset($_SESSION['event'][$id_event])){
	
	$_SESSION['event'][$id_event] = $id_event ;
	
}

//echo count($_SESSION['courses']);
//print_r($_SESSION['courses']);

include("../modules/addcartevent.php"); 

?>