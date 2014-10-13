<?php
set_time_limit(0);
require_once('Connections/magazinducoin.php'); 
mysql_select_db($database_magazinducoin, $magazinducoin);

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
//echo $query_liste_produit.'<br>';
$num_rows = mysql_num_rows($query);
echo 'count '.$num_rows.'<br>';
$i=0;
while($row = mysql_fetch_array($query)){
	$i++;
	$adresse = array();
	$adresse[] = ($row['adresse']);
	$adresse[] = $row['code_postal'];
	if($row['ville'])	$adresse[] = $row['ville'];
	$adresse[] = ($row['nom_region']);
	$adresse[] = "France";
	
	$adresse_complet = implode(' ',$adresse);
	//$adresse_complet = urlencode($adresse_complet);
	//$adresse_geo_xml = "http://nominatim.openstreetmap.org/search/$adresse_complet?format=xml&polygon=1&addressdetails=1";
	//echo $adresse_complet.'<br>';
	

	$find = array(" ");
	$replace = array("%20");
	$namede=str_replace($find,$replace,$adresse_complet);
	
	//echo $i." http://nominatim.openstreetmap.org/search/$namede?format=xml&polygon=1&addressdetails=1".'<br>';
	
	$xml_str= file_get_contents("http://nominatim.openstreetmap.org/search/$namede?format=xml&polygon=1&addressdetails=1");
	$xml = simplexml_load_string($xml_str);
	
	if( isset($xml->place) ){
		$attr = $xml->place[0]->attributes();
		//var_dump($attr).'<br>';
		//mysql_query("UPDATE magazins SET latlan = '$latlan' WHERE id_magazin = ".$row['id_magazin']);
		echo $i."<span style='color:blue;'> Update LatLon</span> ";
		echo $latlan = '('.$attr->lat .','. $attr->lon .')'.'<br>';
	}else{
		echo $i."<span style='color:red;'> No LatLon</span>".'<br>';
	}
}
?>