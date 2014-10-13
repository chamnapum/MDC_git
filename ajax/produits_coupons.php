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

$colname_produits = "-1";
if (isset($_GET['sous_categorie'])) {
  $colname_produits = $_GET['sous_categorie'];
}
$colname_produits2 = "-1";
if (isset($_GET['magasin'])) {
  $colname_produits2 = $_GET['magasin'];
}
mysql_select_db($database_magazinducoin, $magazinducoin);
$query_produits = sprintf("SELECT id, titre FROM produits WHERE sous_categorie = %s AND id_magazin = %s ORDER BY titre ASC", GetSQLValueString($colname_produits, "int"),
GetSQLValueString($colname_produits2, "int"));
$produits = mysql_query($query_produits, $magazinducoin) or die(mysql_error());
$row_produits = mysql_fetch_assoc($produits);
$totalRows_produits = mysql_num_rows($produits);

if($totalRows_produits == 0)
	echo "Pas des produits disponibles dans la catégorie!";
else { ?>
<ul>
<?php do {  ?>
	<li><a href="javascript:;" onClick="ajax('ajax/addtocart.php?id_produit=<?php echo $row_produits['id']; ?>','#cart');"><?php echo ($row_produits['titre']); ?></a></li>
 <?php } while ($row_produits = mysql_fetch_assoc($produits)); ?>
</ul>
<?php 
}
mysql_free_result($produits);
?>