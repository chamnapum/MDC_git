<?php require_once('Connections/magazinducoin.php'); 
if($default_region <= 0) header('Location: index.php');

$villlle = '';
if(isset($_SESSION['kt_ville']) and (!isset($_SESSION['kt_adresse_complet']) or !empty($_SESSION['kt_adresse_complet']))){
	$rkt = "SELECT nom from maps_ville where id_ville = ".$_SESSION['kt_ville'];
	//die($rkt);
	$query=mysql_query($rkt);
	$ville=mysql_fetch_array($query);
	$villlle .= ','.$ville['nom'];
	$_SESSION['kt_adresse_complet'] = $_SESSION['kt_adresse'].$villlle;
}

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_villes = "SELECT nom_region FROM region WHERE id_region = ".$default_region;
$villes = mysql_query($query_villes) or die(mysql_error());
$row_villes = mysql_fetch_array($villes);

$query_faurchette_prix = "SELECT produits.prix2
FROM (produits
LEFT JOIN magazins ON magazins.id_magazin=produits.id_magazin)
WHERE magazins.region = ".$default_region." AND produits.en_stock = 1
ORDER BY produits.prix2 ASC";
$faurchette_prix = mysql_query($query_faurchette_prix, $magazinducoin) or die(mysql_error());
$row_faurchette_prix = mysql_fetch_assoc($faurchette_prix);
$totalRows_faurchette_prix = mysql_num_rows($faurchette_prix);

$min_prix = $row_faurchette_prix['prix2'];
while($row_faurchette_prix = mysql_fetch_assoc($faurchette_prix))
	$max_prix = $row_faurchette_prix['prix2'];

 ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 
	<title>Liste des produits de la région <?php echo ($row_villes['nom_region']);?> | Magasin Du Coin</title>
    <meta name="description" content="Retrouver dans votre région <?php echo ($row_villes['nom_region']);?> tous les produits proches de vous. bons plans et évènements que vos commerçants proposent." />
    <?php include("modules/head.php"); ?>

</head>
<?php
$Value_ville='';
$ville = explode( ',', $_REQUEST['id_ville'] );
foreach($ville as $ville2){
	$List1 .= $ville2.",";
}
$Value_ville = substr($List1,0,strlen($List1)-1);
?>
<body id="sp" onload="ajax('ajax/resultat_recherche.php?<?php
echo $_REQUEST['region']		? "&amp;region=".$_REQUEST['region'] : "";
echo $_REQUEST['departement']	? "&amp;departement=".$_REQUEST['departement'] : "";
echo $_REQUEST['id_ville']		? "&amp;id_ville=".$Value_ville : "";
echo $_REQUEST['ville_near_all']	? "&amp;ville_near_all=".$_REQUEST['ville_near_all'] : "";
echo $_REQUEST['categorie']		? "&amp;categorie=".$_REQUEST['categorie'] : "";
echo $_REQUEST['sous_categorie']   ? "&amp;sous_categorie=".$_REQUEST['sous_categorie'] : "";
echo isset($_REQUEST['sous_categorie2'])   ? "&amp;sous_categorie2=".$_REQUEST['sous_categorie2'] : "";
echo $_REQUEST['mot_cle'] ? "&amp;mot_cle=".$_REQUEST['mot_cle'] : "";
echo isset($_REQUEST['magasin'])   ? "&amp;magasin=".$_REQUEST['magasin'] : "";
?>','#result'); <?php 
if($_REQUEST['categorie']){
	$default = 0;
	if($_REQUEST['sous_categorie']) $default = $_REQUEST['sous_categorie'];
 	echo "ajax('ajax/sous_categorie.php?default=".$default."&amp;id_parent=".$_REQUEST['categorie']."','#sous_categorie');";
 } ?>">

 
<?php 
mysql_select_db($database_magazinducoin, $magazinducoin);

$query_region_pub = "SELECT produits.titre AS titre_produit, pub.titre, pub.region, pub.emplacement, pub.id_produit, pub.date_debut, pub.date_fin, produits.prix, produits.photo1, produits.id_magazin, produits.categorie, produits.sous_categorie FROM (pub LEFT JOIN produits ON produits.id=pub.id_produit)  WHERE pub.region = $default_region and produits.en_stock = 1 and pub.payer = 1 ";
$region_pub = mysql_query($query_region_pub, $magazinducoin) or die(mysql_error());

$tous_regionpub = array();
while($row_region_pub = mysql_fetch_assoc($region_pub)){
	$tous_regionpub[$row_region_pub['emplacement']][] = $row_region_pub;
}

?>
 
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
    <h1 style="font-size:5px; color:#F2EFEF; margin:0; padding:0">Liste des produits dans la région <?php echo ($row_villes['nom_region']);?></h1>
    <h2 style="font-size:5px; color:#F2EFEF; margin:0; padding:0">Voir la liste des produits et bons plans</h2>

    <div class="lister top" style="float:left; background:#F2EFEF; width:100%;">
        <?php include ("modules/menu_filtre.php"); ?>
        <div id="result" style="float:right; width:685px;"></div>
    </div>

</div>

<div class="clear"></div>

<div id="footer">
    <?php include("modules/footer.php"); ?>	
</div>

</body>
</html>