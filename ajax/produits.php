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
$query_villes = "SELECT id, titre FROM produits WHERE id_user = ".$_GET['id_user']." ORDER BY titre ASC ";
echo $query_villes;
$villes = mysql_query($query_villes, $magazinducoin) or die(mysql_error());
$row_villes = mysql_fetch_assoc($villes);
$totalRows_villes = mysql_num_rows($villes);
?>
<option value="">Selectionnez</option>
<?php 
do {  
?>
     <option value="<?php echo $row_villes['id']?>"<?php if (!(strcmp($row_villes['id'], $_GET['default']))) {echo "SELECTED";} ?> title="<?php echo ($row_villes['titre']); ?>"><?php echo ($row_villes['titre']); ?></option>
                          <?php
} while ($row_villes = mysql_fetch_assoc($villes));
  $rows = mysql_num_rows($villes);
  if($rows > 0) {
      mysql_data_seek($villes, 0);
	  $row_villes = mysql_fetch_assoc($villes);
  }
?>