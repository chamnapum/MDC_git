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

if($_GET['id_user']!=''){

 mysql_select_db($database_magazinducoin, $magazinducoin);
$query_villes = "SELECT DISTINCT magazins.nom_magazin
    , owner_shopper.id_user
    , owner_shopper.id_magazin
FROM
    owner_shopper
    INNER JOIN magazins 
        ON (owner_shopper.id_magazin = magazins.id_magazin) WHERE owner_shopper.id_user = ".$_GET['id_user']." AND magazins.id_user='' ORDER BY magazins.nom_magazin ASC ";
$villes = mysql_query($query_villes, $magazinducoin) or die(mysql_error());
//echo $query_villes;
?>
<?php
while($row_villes = mysql_fetch_array($villes)){
?>
<input type="checkbox" name="magazin[]" value="<?php echo $row_villes['id_magazin'];?>"><?php echo $row_villes['nom_magazin'];?><br />
	
<?php }?>

<?php }?>
