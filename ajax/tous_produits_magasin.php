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

$colname_coupon = "-1";
if (isset($_GET['id_coupon'])) {
  $colname_coupon = $_GET['id_coupon'];
}
mysql_select_db($database_magazinducoin, $magazinducoin);
$query_coupon = sprintf("SELECT * FROM coupons WHERE id_coupon = %s", GetSQLValueString($colname_coupon, "int"));
$coupon = mysql_query($query_coupon, $magazinducoin) or die(mysql_error());
$row_coupon = mysql_fetch_assoc($coupon);
$totalRows_coupon = mysql_num_rows($coupon);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_categories = "SELECT cat_id, cat_name, (SELECT COUNT(*) FROM produits WHERE produits.categorie = category.cat_id) AS nb_produits FROM category WHERE parent_id = 0 ORDER BY cat_name ASC";
$categories = mysql_query($query_categories, $magazinducoin) or die(mysql_error());
$row_categories = mysql_fetch_assoc($categories);
$totalRows_categories = mysql_num_rows($categories);

$cat = 0;
$sous_cat = 0;
if(isset($_GET['categories']))		$cat = $_GET['categories'];
if(isset($_GET['sous_categorie']))	$sous_cat = $_GET['sous_categorie'];

?>
<html>
<head></head>
<body>

Filtrer par cat&eacute;gories : 
<form id="form1" name="form1" style="width:600px">
<select name="categories" id="categories" style="float:none; width:160px"   onchange="ajax('ajax/sous_categorie.php?default=0&id_parent='+this.value,'#sous_categorie2');" >
<option value=""> -- Tous les cat&eacute;gories --</option>
	<?php do {
			if($row_categories['nb_produits'] > 0){ ?>
	  <option value="<?php echo $row_categories['cat_id']; ?>" <?php echo $row_categories['cat_id']==$cat ? 'selected="selected"' : ''; ?>><?php echo ($row_categories['cat_name']); ?></option>
      </li>
	  <?php }} while ($row_categories = mysql_fetch_assoc($categories)); ?>
</select>
&nbsp;&nbsp;&nbsp;&nbsp;
Sous cat&eacute;gories :
<select name="sous_categorie2" id="sous_categorie2" style="float:none; width:160px">
<?php if($cat > 0) {
	$query_categories = "SELECT cat_id, cat_name FROM category WHERE parent_id = $cat ORDER BY cat_name ASC";
	$categories = mysql_query($query_categories, $magazinducoin) or die(mysql_error());
	echo '<option value=""> -- Tous les sous cat&eacute;gories --</option>';
	while($row_categories = mysql_fetch_assoc($categories)){
	?>
    <option value="<?php echo $row_categories['cat_id']; ?>" <?php echo $row_categories['cat_id']==$sous_cat ? 'selected="selected"' : ''; ?>><?php echo ($row_categories['cat_name']); ?></option>
    <?php
	}
	}
	else echo '<option value=""> -- S&eacute;lectionner une cat&eacute;gorie -- </option>';
?>
</select><br />
<input name="filtrer" type="button" value="Filtrer" onclick="ajax('ajax/tous_produits_magasin.php?id_coupon=<?php echo $colname_coupon ?>&categories='+document.form1.categories.value+'&sous_categorie='+document.form1.sous_categorie2.value,'#result')" style="float:right" />
</form>
<br />

<div id="produits">
<?php
if($row_coupon['min_achat']>0){
	mysql_select_db($database_magazinducoin, $magazinducoin);
	
	$query_Recordset1 = "SELECT id, titre, prix, en_stock, photo1 FROM produits WHERE id_magazin = ".$row_coupon['id_magasin'];
	if($cat>0) 		$query_Recordset1 .= " AND categorie = $cat ";
	if($sous_cat>0) $query_Recordset1 .= " AND sous_categorie = $sous_cat ";
	$query_Recordset1 .= " ORDER BY titre ASC";
	//echo $query_Recordset1;
	$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
	while($row_Recordset1 = mysql_fetch_assoc($Recordset1)){
		?>
        <div class="produit">
        	<div class="titre"><?php echo $row_Recordset1['titre'] ?></div>
            <div class="image"><img src="timthumb.php?w=70&h=50&zc=1&src=images/produits/<?php echo $row_Recordset1['photo1'] ?>" /></div>
            <div class="prix"><?php echo $row_Recordset1['prix'] ?> &euro;</div>
            <div class="addtocart"><a href="javascript:;" onClick="ajax('ajax/addtocart.php?id_produit=<?php echo $row_Recordset1['id']; ?>&id_coupon=<?php echo $colname_coupon ?>','#cart');">Ajouter au Compteur</a></div>
        </div>
        <?php
	}
	
}
?>
</div>
</body>
</html>

<?php

mysql_free_result($coupon);

?>
