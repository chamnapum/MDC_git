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
$copn = array();
foreach($_SESSION['coupons'] as $k => $v)
	$copn[] = $k;

$les_ids = implode(',',$copn);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset1 = "SELECT coupons.titre AS titre_coupon, magazins.nom_magazin, magazins.ville, magazins.latlan, produits.categorie,produits.id_magazin, produits.sous_categorie, produits.titre,produits.id, produits.reference, produits.prix, produits.en_stock, produits.description, produits.photo1, coupons.reduction, coupons.date_fin, coupons.date_debut, coupons.categories, (SELECT COUNT(*) FROM evenements WHERE id_magazin = produits.id_magazin) AS nb_events , (SELECT COUNT(*) FROM coupons WHERE id_magasin = produits.id_magazin) AS nb_coupons
FROM ((produits
LEFT JOIN magazins ON magazins.id_magazin=produits.id_magazin)
LEFT JOIN coupons ON coupons.id_coupon=produits.coupon_reduction) WHERE produits.id IN ($les_ids)";
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("modules/head.php"); ?>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 
	<title>Magasin du coin | Imprimer les coupons </title>
    <style media="print">
		.imp{
			display:none;
		}
	</style>
<body>
<h1 style="margin:10px auto; width:946px">www.magasinducoin.com - Liste du <?php echo date('d/m/Y'); ?><a class="imp" style="float:right" href="javascript:print();"><?php echo $xml->Imprimer; ?></a></h1>

<?php while($liste = mysql_fetch_assoc($Recordset1)){ ?>
<div class="box">
        <div class="box_inner">
            	<div class="boxtitre">
                 <a class="various3"   href="detail_produit.php?id=<?php echo $liste['id'];?>&cat_id=<?php echo $liste['categorie'];?>&mag_id=<?php echo $liste['id_magazin'];?>#tabs-1"><?php echo $liste['titre']; ?></a>
                </div>
                <div class="box_img"> 
                <a class="various3" href="detail_produit.php?id=<?php echo $liste['id'];?>&cat_id=<?php echo $liste['categorie'];?>&mag_id=<?php echo $liste['id_magazin'];?>&t=1#tabs-1">
                
                            <img src="timthumb.php?src=assets/images/produits/<?php echo $liste['photo1']; ?>&z=1&w=125&h=90" />
               </a>
               <span class="boxville"><?php echo getVilleById($liste['ville']); ?></span>
                </div>
                <div class="box_desc" >
                     <div class="desc_inner"> <?php  echo substr($liste['description'],0,150); ?></div>
                      <div class="prix_inner">
                              <div class="box_prix">
                              	<span class="prix">
								<?php echo $liste['prix']."&#8364;"; ?>
                                </span>
                             	 <?php if($liste['en_stock']==1){echo "<br>En stock";} ?>
                             </div>
                             <div class="box_mag">
                                
                                 <a class="various3" href="detail_produit.php?id=<?php echo $liste['id'];?>&cat_id=<?php echo $liste['categorie'];?>&mag_id=<?php echo $liste['id_magazin'];?>&t=1#tabs-2"><span class="magazin">
                                <?php echo $liste['nom_magazin'];?></span></a>
                             </div>
                    </div> 
                </div>
               
        </div>
        <div class="box_event"> 
            <div class="box_cpn"><p> <a class="various3" href="detail_produit.php?id=<?php echo $liste['id'];?>&cat_id=<?php echo $liste['categorie'];?>&mag_id=<?php echo $liste['id_magazin'];?>&t=5#tabs-5"> <span style="font-size:18px"><?php echo $liste['nb_coupons'];?></span> <?php if($liste['nb_coupons']<=1){echo"coupon <br /> de reduction";}else{echo"coupons <br /> de reduction";}?> </a> </p></div>
            <div class="box_evnt"><p><a class="various3" href="detail_produit.php?id=<?php echo $liste['id'];?>&cat_id=<?php echo $liste['categorie'];?>&mag_id=<?php echo $liste['id_magazin'];?>&t=4#tabs-4"><span style="font-size:18px"><?php echo $liste['nb_events'];?></span><?php if($liste['nb_events']<=1){echo" Evenement";}else{echo" Evenements";}?> </a></p></div>
         </div>
</div>

<?php } ?>
</body>
</html>
