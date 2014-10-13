<?php

require_once('Connections/magazinducoin.php'); 
mysql_select_db($database_magazinducoin, $magazinducoin);

include_once("class/GoogleMap.php");
include_once("class/JSMin.php");

$MAP_OBJECT = new GoogleMapAPI(); 
$MAP_OBJECT->_minify_js = isset($_REQUEST["min"])?FALSE:TRUE;

$query_liste_produit = "SELECT magazins.id_magazin, 
		region.nom_region, 
		maps_ville.nom AS ville, 
		magazins.adresse,
		magazins.code_postal
	FROM magazins 
	LEFT JOIN region ON region.id_region=magazins.region
	LEFT JOIN maps_ville ON maps_ville.id_ville=magazins.ville
	WHERE latlan = '(0,0)'
	ORDER BY rand() LIMIT 50";
$query = mysql_query($query_liste_produit);
while($row = mysql_fetch_array($query)){
	$adresse = array();
	$adresse[] = $row['adresse'];
	$adresse[] = $row['code_postal'];
	if($row['ville'])	$adresse[] = $row['ville'];
	$adresse[] = $row['nom_region'];
	$adresse[] = "France";
	
	$adresse_complet = implode(', ',$adresse);
	$adresse_complet = urlencode($adresse_complet);
	$adresse_geo = $MAP_OBJECT->getGeoCode($adresse_complet);
	$latlan = "(".$adresse_geo['lat'].",".$adresse_geo['lon'].")";
	$envoyer = false;
	echo $latlan;
	if($latlan != "(,)"){
		mysql_query("UPDATE magazins SET latlan = '$latlan' WHERE id_magazin = ".$row['id_magazin']);
		/*if(!$envoyer){
			mail("freelance4@gmail.com","un latlan sauvé","Bonjour, un latitude et lontitude est sauvgardé!");
			$envoyer = true;
		}*/
	}
}
?>