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
$query_villes = "SELECT cat_id, cat_name, (SELECT COUNT(*) FROM coupons WHERE coupons.sous_categorie = category.cat_id AND date_fin  >= CURDATE() AND date_debut <= CURDATE()) AS nb_coupons FROM category WHERE parent_id = ".$_GET['id_parent']." ORDER BY cat_name ASC ";
$villes = mysql_query($query_villes, $magazinducoin) or die(mysql_error());
$row_villes = mysql_fetch_assoc($villes);
$totalRows_villes = mysql_num_rows($villes);
echo "<ul>";
do {  
?>
<li><a href="javascript:;" onClick="ajax('ajax/produits_coupons.php?sous_categorie=<?php echo $row_villes['cat_id']; ?>&id_parent=<?php echo $_GET['id_parent']; ?>&magasin=<?php echo $_GET['magasin']; ?>','#produits');" <?php echo $row_villes['nb_coupons'] > 0 ? "class='active'" : "" ?>><?php echo ($row_villes['cat_name']); ?></a></li>

<?php
} while ($row_villes = mysql_fetch_assoc($villes));
echo "</ul>";
?>