<?php if ($_GET['id_parent'] == -1) {
echo '<option value="-1">Tout le magasin</option>';
} else { ?><option value="">Tous les sous cat&eacute;gories</option>
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
$query_villes = "SELECT cat_id, cat_name FROM category WHERE parent_id = ".$_GET['id_parent']." ORDER BY category.order ASC ";
$villes = mysql_query($query_villes, $magazinducoin) or die(mysql_error());
$row_villes = mysql_fetch_assoc($villes);
$totalRows_villes = mysql_num_rows($villes);

do {  
?>
                          <option value="<?php echo $row_villes['cat_id']?>"<?php if (!(strcmp($row_villes['cat_id'], $_GET['default']))) {echo "SELECTED";} ?>><?php echo (($row_villes['cat_name'])); ?></option>
                          <?php
} while ($row_villes = mysql_fetch_assoc($villes));
  $rows = mysql_num_rows($villes);
  if($rows > 0) {
      mysql_data_seek($villes, 0);
	  $row_villes = mysql_fetch_assoc($villes);
  }
 }
?>