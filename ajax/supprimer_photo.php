<?php require_once('../Connections/magazinducoin.php'); 
 
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

 mysql_select_db($database_magazinducoin, $magazinducoin);

switch($_GET['t']){
	case 'produits';
		$id_name = 'id';
		unlink("../assets/images/produits/".$_GET['f']);
		break;
	case 'magazins';
		$id_name = 'id_magazin ';
		unlink("../assets/images/magasins/".$_GET['f']);
		break;
}

$query_villes = "UPDATE ".$_GET['t']." SET ".$_GET['c']." = '' WHERE $id_name = ".$_GET['id'];
$villes = mysql_query($query_villes, $magazinducoin) or die(mysql_error());

?>