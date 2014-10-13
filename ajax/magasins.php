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
$query_villes = "SELECT id_magazin,	nom_magazin FROM magazins WHERE id_user = ".$_GET['id_user']." AND magazins.activate='1' AND magazins.payer='1' AND magazins.approuve='1' ORDER BY nom_magazin ASC ";
$villes = mysql_query($query_villes, $magazinducoin) or die(mysql_error());
$row_villes = mysql_fetch_assoc($villes);
$totalRows_villes = mysql_num_rows($villes);
?>
<option value="">Selectionnez</option>
<?php if(isset($_GET['coupon'])) { ?>
<option value="-1" <?php if (!(strcmp(-1, $_GET['default']))) {echo "SELECTED";} ?>>Tous les magasins</option>
<?php } ?>
<?php do {?>
<option value="<?php echo $row_villes['id_magazin']?>"<?php if (!(strcmp($row_villes['id_magazin'], $_GET['default']))) {echo "SELECTED";} ?> title="<?php echo ($row_villes['nom_magazin']); ?>"><?php echo ($row_villes['nom_magazin']); ?></option>
<?php
} while ($row_villes = mysql_fetch_assoc($villes));
  $rows = mysql_num_rows($villes);
  if($rows > 0) {
      mysql_data_seek($villes, 0);
	  $row_villes = mysql_fetch_assoc($villes);
  }
?>