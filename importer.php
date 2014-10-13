<?php
error_reporting(1);
require_once('Connections/magazinducoin.php'); 
mysql_select_db($database_magazinducoin, $magazinducoin);

/*$query_Recordset3 = "SELECT id_ville, nom FROM maps_ville2 ";
$Recordset3 = mysql_query($query_Recordset3, $magazinducoin) or die(mysql_error());
while($row_Recordset3 = mysql_fetch_assoc($Recordset3)){
	$nom = stripAccents($row_Recordset3['nom']);
	$nom = str_replace('-', ' ', $nom);
	mysql_query("UPDATE maps_ville2 SET nom = '$nom' WHERE id_ville = ".$row_Recordset3['id_ville'], $magazinducoin) or die(mysql_error());
}
function stripAccents($string){
	return strtr($string,'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ',
'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
}*/



include_once("class/GoogleMap.php");
include_once("class/JSMin.php");

$MAP_OBJECT = new GoogleMapAPI(); 
$MAP_OBJECT->_minify_js = isset($_REQUEST["min"])?FALSE:TRUE;

function get_idville($nom){
	global $magazinducoin;
	$query_Recordset3 = "SELECT * FROM maps_ville2 WHERE nom  = '".addslashes(strtolower(trim($nom)))."'";
	$Recordset3 = mysql_query($query_Recordset3, $magazinducoin) or die(mysql_error());
	$row_Recordset3 = mysql_fetch_assoc($Recordset3);
	return $row_Recordset3['id_ville'];
}

function get_categorie($nom){
	global $magazinducoin;
	$query_Recordset3 = "SELECT cat_id FROM category WHERE cat_name LIKE '%".$nom."%' AND type = 3";
	$Recordset3 = mysql_query($query_Recordset3, $magazinducoin) or die(mysql_error());
	$row_Recordset3 = mysql_fetch_assoc($Recordset3);
	return $row_Recordset3['cat_id'];
}
function getIdRegion($nom){
	global $magazinducoin;
	$query_Recordset3 = "SELECT id_region FROM region WHERE nom_region = '".strtolower(trim($nom))."'";
	$Recordset3 = mysql_query($query_Recordset3, $magazinducoin) or die(mysql_error());
	$row_Recordset3 = mysql_fetch_assoc($Recordset3);
	return $row_Recordset3['id_region'];
}
//mysql_query("DELETE FROM utilisateur WHERE level = 1", $magazinducoin) or die(mysql_error());
//mysql_query("DELETE FROM magazins", $magazinducoin) or die(mysql_error());

$email	= "magasinducoin1@gmail.com";
$nom 	= "magasinducoin1";
$passe	= "12345678";
			
/*$sql = "INSERT INTO `utilisateur` (
`id` , `nom` ,`prenom` ,`email` ,`password` ,`activate` ,`level` ,`civilite` ,`telephone` ,`nom_magazin` ,`siren` ,`region` ,`adresse` ,`code_postal` ,`ville` ,`description` ,`note` ,`payer` ,`date_naissance` ,`categorie` ,`credit` )
VALUES (NULL , '$nom', '$nom', '$email', '$passe' , '1', '1', 'M', '$tel' , '$nom_magasin', NULL , '$region', '$adresse', '$zip', '$ville', NULL , NULL , '0', NULL , '$categorie', '25.00')";
mysql_query($sql, $magazinducoin) or die(mysql_error());*/
$id_user = 684;//mysql_insert_id();

$row = 0; //&eacute; &ocirc;
if (($handle = fopen("liste6.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		//if($row>400){
			$champs = explode(';',$data[0]);
			$nom_magasin 	= addslashes($champs[0]);
			$adresse 	 	= addslashes(($champs[1]));
			$region		 	= ($champs[7] == "PAYS DE LOIRE"? 18 : getIdRegion(addslashes($champs[7])));
			$ville		 	= get_idville($champs[2]);
			$zip		 	= $champs[3];
			$tel		 	= $champs[4];
			$categorie	 	= get_categorie(addslashes($champs[5]));
			$sous_categorie	= get_categorie(addslashes($champs[6]));
			$image		 	= strtolower($champs[8]);
			
			if($categorie and $region){
				$sauve++;
				/*$adresse_geo = $MAP_OBJECT->getGeoCode($champs[1]." ".$champs[2].", France");
				echo $champs[1]." ".$champs[2].", France";
				$latlan = "(".$adresse_geo['lat'].",".$adresse_geo['lon'].")";
				echo $latlan;*/
				$latlan = "(,)";
				$sql2 = "INSERT INTO `magazins` (
					id_magazin, nom_magazin, region, ville, adresse, code_postal, photo1,id_user, activate, categorie, sous_categorie, approuve, payer, gratuit, latlan
				) VALUES (
					NULL, '$nom_magasin', '$region', '$ville','$adresse', '$zip', '$image', '$id_user', 1, '$categorie', '$sous_categorie', 1, 1, 0, '(0,0)')";
				mysql_query($sql2, $magazinducoin) or die($sql2);
			}
			$row++;
	}
    fclose($handle);
}
echo $row." - Tous <br> ".$sauve. " - Termin&eacute;";
?>