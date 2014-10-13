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
	
	
		$query_ville = "SELECT
							magazins.ville
							, ville_near.nom_ville_near
						FROM
							magazins
							INNER JOIN ville_near 
								ON (magazins.id_magazin = ville_near.id_magazin)
							INNER JOIN departement 
								ON (magazins.department = departement.code) WHERE magazins.region='".$_GET["region"]."' AND ville_near.nom_ville_near LIKE '%".($q)."%' ORDER BY ville_near.nom_ville_near ASC";
		$ville = mysql_query($query_ville, $magazinducoin) or die(mysql_error());
		while($row_ville= mysql_fetch_array($ville)) {
			echo $row_ville['nom_ville_near']."|".$row_ville['ville']."\n";
		}
?>
