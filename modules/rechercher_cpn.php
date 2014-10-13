<?php require_once('Connections/magazinducoin.php'); ?>
<?php if($default_region == 0){ //header('Location: index.php');
	echo'<script>window.location="index.php";</script>';
}
// en_tete_liste = 0 si la date est dépassé
mysql_query("UPDATE coupons SET en_tete_liste = 0 WHERE en_tete_liste_fin < NOW()");
 ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 
	<title>Bon de réduction - Code Promo | Magasinducoin </title>
    <?php if(!isset($_SESSION['kt_adresse']) or empty($_SESSION['kt_adresse'])) { ?>
    <script type="text/JavaScript" src="geo.js"></script>
    <?php } ?>
    <?php include("modules/head.php"); ?>
</head>
<body id="sp" onload="ajax('ajax/resultat_recherche_cpn.php?prixMax=0&prixMin=0<?php 
echo $_GET['categorie']		? "&categorie=".$_GET['categorie'] : "";
echo $_GET['sous_categorie']   ? "&sous_categorie=".$_GET['sous_categorie'] : "";
echo $_GET['mot_cle'] ? "&mot_cle=".$_GET['mot_cle'] : "";
echo $_GET['rayon']   ? "&rayon=".$_GET['rayon'] : "";
echo isset($_GET['magasin'])   ? "&magasin=".$_GET['magasin'] : "";
echo isset($_GET['id_coupon'])   ? "&coupon=".$_GET['id_coupon'] : "";
if(isset($_GET['adresse']) and !empty($_GET['adresse'])){
	$adresse = $_SESSION['kt_adresse'] = addslashes($_GET['adresse']);
	echo "&adresse=".$adresse;
}
else if(isset($_SESSION['kt_adresse'])){
	$villlle = '';
	if(isset($_SESSION['kt_ville'])){
		$rkt = "SELECT nom from map_ville where id_ville = ".$_SESSION['kt_ville'];
		die($rkt);
		$query=mysql_query($rkt);
		$ville=mysql_fetch_array($query);
		$villlle .= ','.$ville['nom'];
	}
	$_SESSION['kt_adresse_complet'] = $_SESSION['kt_adresse'].$villlle;
	echo "&adresse=".addslashes($_SESSION['kt_adresse_complet']);
}
?>','#result'); <?php 
if($_GET['categorie']){
	$default = 0;
	if($_GET['sous_categorie']) $default = $_GET['sous_categorie'];
 	echo "ajax('ajax/sous_categorie.php?default=".$default."&id_parent=".$_GET['categorie']."','#sous_categorie');";
 } ?>" >

 
<?php include("modules/header.php"); ?>
	
<div id="content">
	<style>
    /*#formHaut{
		margin-top:-11px;
	}*/
    </style>
    <?php include("modules/form_recherche_header.php"); ?>
    <div class="top reduit">
        <div id="head-menu" style="float:left;"></div>
		<div id="url-menu" style="float:left;">
        <?php include("assets/menu/url_menu.php"); ?>
        </div>
    </div>
    
    <div class="clear"></div>

    <div class="lister top" style="float:left; background:#F2EFEF; width:100%;">
        <?php include ("modules/menu_filtre_cpn.php"); ?>
        <div id="result" style="float:left; margin:10px 0 0 20px; width:755px;"></div>
    </div>

</div>

<div class="clear"></div>

<div id="footer">
	<?php include("modules/region_barre_recherche.php"); ?>
    <?php include("modules/footer.php"); ?>	
</div>

</body>
</html>