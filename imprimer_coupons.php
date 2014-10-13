<?php require_once('Connections/magazinducoin.php'); ?>
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
$tab_id = array();
foreach($_SESSION['cart'] as $k => $cart){
	$tab_id[] = $k;
}
$les_id = implode(',',$tab_id);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset1 = "SELECT magazins.photo1 AS photo_magasin, magazins.nom_magazin, magazins.id_magazin, magazins.region, produits.categorie, produits.description, produits.sous_categorie, produits.titre, produits.prix, produits.en_stock, produits.photo1 AS photo_produit, magazins.adresse, maps_ville.nom, produits.id FROM ((produits LEFT JOIN magazins ON magazins.id_magazin=produits.id_magazin) LEFT JOIN maps_ville ON maps_ville.id_ville=magazins.ville) WHERE produits.id IN ($les_id)";
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("modules/head.php"); ?>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 
	<title>Magasin du coin | Imprimer les coupons </title>
    <style>
	.reduction, .c3{
float:left;
width:215px;
}
.img_produit{
float:left;
width:330px;
}
h3{
font-size:14px;
padding:0;
margin:0;
}
	</style>
    
</head>
<body id="sp" >
<?php include("modules/header.php"); ?>

<div id="content">
<div class="top reduit">
                    <?php include("modules/menu.php"); ?>
</div>
<?php do {
$query_coupon = "SELECT * FROM coupons WHERE id_coupon = ".$_SESSION['cart'][$row_Recordset1['id']]['id_coupon'];
$coupon = mysql_query($query_coupon, $magazinducoin) or die(mysql_error());
$row_coupon = mysql_fetch_assoc($coupon);
 ?>
  <div id="content"> 
  			<div class="top reduit">
                    <?php include("modules/menu.php"); ?>
            </div>
  	<div class="img_produit">
    <h3><?php echo $row_Recordset1['titre']; ?></h3>
    <img src="timthumb.php?w=300&h=200&zc=1&src=images/produits/<?php echo $row_Recordset1['photo_produit']; ?>" />
    <div class="code_bare"><img src="sample-gd.php?code_bare=<?php echo $row_coupon['code_bare']; ?>"  /></div>
    </div>
    
    <div class="reduction"><?php echo ($_SESSION['cart'][$row_Recordset1['id']]['prix']/$_SESSION['cart'][$row_Recordset1['id']]['qt']) ?> € de reduction immédiate</div>
    <div class="c3">
        <div class="info_magasin">
            <h4><?php echo $row_Recordset1['nom_magazin']; ?></h4>
            <?php echo $row_Recordset1['adresse']; ?> <br /><?php echo $row_Recordset1['nom']; ?>
        </div>
        <div class="valable"><br />
<br />
Valable jusqu'à <?php echo dbtodate($row_coupon['date_fin']); ?></div>
  </div>
  </div>
 <div style="clear:both"></div>
 <hr />
  <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
 </div> 
 <div id="footer">
    		<?php include("modules/region_barre_recherche.php"); ?>
        <div class="liens">
       		<?php include("modules/footer.php"); ?>
		</div>
</div>
 </body>
</html>
<?php
mysql_free_result($Recordset1);
mysql_free_result($coupon);
?>