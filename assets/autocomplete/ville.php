<?php require_once('../../Connections/connection.php'); ?>
<?php

$magazinducoin = mysql_pconnect($hostname_magazinducoin, $username_magazinducoin, $password_magazinducoin); 
	mysql_query("SET character_set_results=utf8", $magazinducoin);
    mb_language('uni'); 
    mb_internal_encoding('UTF-8');
    mysql_select_db($database_magazinducoin, $magazinducoin);
    mysql_query("set names 'utf8'",$magazinducoin);
	
	$q = strtolower($_GET["q"]);
	if (!$q) return;
	
	if($_GET['id_departement']=='0'){
		$dep = "";
	}else{
		$dep = "AND departement.code='".$_GET['id_departement']."'";
	}
		
		$query_ville = "SELECT
							maps_ville.nom
							, maps_ville.id_ville
							, maps_ville.cp
						FROM
							region
							INNER JOIN departement 
								ON (region.id_region = departement.id_region)
							INNER JOIN maps_ville 
								ON (departement.id_departement = maps_ville.id_departement) WHERE departement.id_region='".$_GET["region"]."' AND (maps_ville.nom LIKE '%".($q)."%' OR maps_ville.cp LIKE '%".($q)."%') ".$dep." ORDER BY nom ASC";
		$ville = mysql_query($query_ville, $magazinducoin) or die(mysql_error());
		while($row_ville= mysql_fetch_array($ville)) {
			echo $row_ville['nom'].' '.$row_ville['cp']."|".$row_ville['id_ville']."\n";
		}
?>
