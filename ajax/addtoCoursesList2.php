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

if(!isset($_SESSION['courses'][$id_produit])){
	
	$_SESSION['courses'][$id_produit] = $id_produit ;
	
}

//echo count($_SESSION['courses']);
//print_r($_SESSION['courses']);
echo "Produit &agrave; &eacute;t&eacute; ajout&eacute; avec succ&egrave;s!!";
//include("../modules/liste_course.php"); 

?>