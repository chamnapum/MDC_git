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
$query_villes = "SELECT
    departement.id_departement
    , departement.nom_departement
    , departement.code
FROM
    region
    INNER JOIN departement 
        ON (region.id_region = departement.id_region) WHERE region.id_region=".$_GET['id_region']." ORDER BY departement.code ASC ";
$villes = mysql_query($query_villes, $magazinducoin) or die(mysql_error());
$row_villes = mysql_fetch_assoc($villes);
$totalRows_villes = mysql_num_rows($villes);
?>
<option value="">Selectionnez</option>
<?php 
do {  
?>
	<option value="<?php echo $row_villes['code']?>"<?php if (!(strcmp($row_villes['code'], $_GET['default_1']))) {echo "SELECTED";} ?> title="<?php echo ($row_villes['nom_departement']); ?>"><?php echo ($row_villes['nom_departement']); ?></option>
                          <?php
} while ($row_villes = mysql_fetch_assoc($villes));
  $rows = mysql_num_rows($villes);
  if($rows > 0) {
      mysql_data_seek($villes, 0);
	  $row_villes = mysql_fetch_assoc($villes);
  }
?>