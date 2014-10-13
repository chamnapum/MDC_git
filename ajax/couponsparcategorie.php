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

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_categories = "SELECT cat_id, cat_name, (SELECT COUNT(*) FROM coupons WHERE coupons.categories = category.cat_id AND date_fin  >= CURDATE() AND date_debut <= CURDATE()) AS nb_coupons FROM category WHERE parent_id = 0 ORDER BY cat_name ASC";
$categories = mysql_query($query_categories, $magazinducoin) or die(mysql_error());
$row_categories = mysql_fetch_assoc($categories);
$totalRows_categories = mysql_num_rows($categories);


?>

<div id="categories">
<ul>
	<?php do { ?>
	  <li><a href="javascript:;" onClick="ajax('ajax/sous_categories_coupons.php?id_parent=<?php echo $row_categories['cat_id']; ?>&magasin=<?php echo $_GET['magasin']; ?>','#sous_categories'); $('#produits').html('');"><?php echo ($row_categories['cat_name']); ?> (<?php echo $row_categories['nb_coupons']; ?>)</a>
      </li>
	  <?php } while ($row_categories = mysql_fetch_assoc($categories)); ?>
</ul>
</div>
<div id="sous_categories"></div>
<div id="produits"></div>

<?php
mysql_free_result($categories);
?>
