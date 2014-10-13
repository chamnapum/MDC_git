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

$colname_is_abone = "-1";
if (isset($_GET['email'])) {
  $colname_is_abone = $_GET['email'];
}
$id_mag = "-1";
if (isset($_GET['id_mag'])) {
  $id_mag = $_GET['id_mag'];
}
mysql_select_db($database_magazinducoin, $magazinducoin);
$query_is_abone = sprintf("SELECT * FROM newsletter WHERE email = %s AND id_magasin = %s ", GetSQLValueString($colname_is_abone, "text"), GetSQLValueString($id_mag, "text"));

$is_abone = mysql_query($query_is_abone, $magazinducoin) or die(mysql_error());
$row_is_abone = mysql_fetch_assoc($is_abone);
$totalRows_is_abone = mysql_num_rows($is_abone);
if($totalRows_is_abone){
	echo "Vous etres deja abonée dans la liste du magsin";
}
else 
{
	//$detele="INSERT INTO newsletter (email,id_magasin,nom) VALUES ('".$colname_is_abon."','".$id_mag."','".$_GET['nom']."')";
	//$detele_sql = mysql_query($detele, $magazinducoin) or die(mysql_error());
	
	$query_insert = sprintf("INSERT INTO newsletter (email,id_magasin,nom) VALUES (%s, %s, %s) ", 
	GetSQLValueString($colname_is_abone, "text"), 
	GetSQLValueString($id_mag, "text"),
	GetSQLValueString($_GET['nom'], "text"));
	mysql_query($query_insert, $magazinducoin) or die(mysql_error());
	echo "Vous etres inscrit avec succes!";
}
?>