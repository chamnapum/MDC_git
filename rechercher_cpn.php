<?php require_once('Connections/magazinducoin.php'); ?>
<?php if($default_region <= 0){ 
	echo'<script>window.location="index.php";</script>';
}
// en_tete_liste = 0 si la date est dépassé
mysql_query("UPDATE coupons SET en_tete_liste = 0 WHERE en_tete_liste_fin < NOW()");

$villlle = '';
if(isset($_SESSION['kt_ville']) and (!isset($_SESSION['kt_adresse_complet']) or !empty($_SESSION['kt_adresse_complet']))){
	$rkt = "SELECT nom from maps_ville where id_ville = ".$_SESSION['kt_ville'];
	$query=mysql_query($rkt);
	$ville=mysql_fetch_array($query);
	$villlle .= ','.$ville['nom'];
	$_SESSION['kt_adresse_complet'] = $_SESSION['kt_adresse'].$villlle;
}
mysql_select_db($database_magazinducoin, $magazinducoin);
$query_villes = "SELECT nom_region FROM region WHERE id_region = ".$default_region;
$villes = mysql_query($query_villes) or die(mysql_error());
$row_villes = mysql_fetch_array($villes);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 
	<title>Bon de réduction - Code Promo de la région <?php echo ($row_villes['nom_region']);?> | Magasin Du Coin</title>
    <meta name="description" content="Retrouver dans votre région <?php echo ($row_villes['nom_region']);?> tous les coupons de reduction, Bons de réduction et Codes Promo proposé par vos commerçants les plus proche." />
    <?php include("modules/head.php"); ?>
    <link type="text/css" href="assets/sliderrange/css/search_address.css" rel="stylesheet" />	
</head>
<?php
$Value_ville='';
$ville = explode( ',', $_REQUEST['id_ville'] );
foreach($ville as $ville2){
	$List1 .= $ville2.",";
}
$Value_ville = substr($List1,0,strlen($List1)-1);
?>
<body id="sp" onload="ajax('ajax/resultat_recherche_cpn.php?<?php 
echo $_REQUEST['region']		? "&amp;region=".$_REQUEST['region'] : "";
echo $_REQUEST['departement']	? "&amp;departement=".$_REQUEST['departement'] : "";
echo $_REQUEST['id_ville']		? "&amp;id_ville=".$Value_ville : "";
echo $_REQUEST['ville_near_all']	? "&amp;ville_near_all=".$_REQUEST['ville_near_all'] : "";
echo $_REQUEST['categorie']		? "&amp;categorie=".$_REQUEST['categorie'] : "";
echo $_REQUEST['sous_categorie']   ? "&amp;sous_categorie=".$_REQUEST['sous_categorie'] : "";
echo $_REQUEST['mot_cle'] ? "&amp;mot_cle=".$_REQUEST['mot_cle'] : "";
echo isset($_REQUEST['magasin'])   ? "&amp;magasin=".$_REQUEST['magasin'] : "";
echo isset($_REQUEST['id_coupon'])   ? "&amp;coupon=".$_REQUEST['id_coupon'] : "";
?>','#result'); <?php 
if($_REQUEST['categorie']){
	$default = 0;
	if($_REQUEST['sous_categorie']) $default = $_REQUEST['sous_categorie'];
 	echo "ajax('ajax/sous_categorie.php?default=".$default."&amp;id_parent=".$_REQUEST['categorie']."','#sous_categorie');";
 } ?>">
<?php include("modules/header.php"); ?>
<div id="content">
    <?php include("modules/form_recherche_header.php"); ?>
    <div class="top reduit">
        <div id="head-menu" style="float:left;">
        	<?php include("assets/menu/main-menu.php"); ?>
        </div>
		<div id="url-menu" style="float:left;">
        <?php include("assets/menu/url_menu.php"); ?>
        </div>
    </div>
    
    <div class="clear"></div>
<h1 style="font-size:5px; color:#F2EFEF; margin:0; padding:0">Liste des coupons de reduction dans la région <?php echo ($row_villes['nom_region']);?></h1>
    <h2 style="font-size:5px; color:#F2EFEF; margin:0; padding:0">Voir la liste des bons  de réduction et codes promo</h2>
    
    <div class="lister top" style="float:left; background:#F2EFEF; width:100%;">
        <?php include ("modules/menu_filtre_cpn.php"); ?>
        <div id="result" style="float:right; width:685px;"></div>
    </div>

</div>

<div class="clear"></div>

<div id="footer">
    <?php include("modules/footer.php"); ?>	
</div>

</body>
</html>