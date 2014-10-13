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
    magazins.id_magazin
    , magazins.nom_magazin
FROM
    magazins
    INNER JOIN produits 
        ON (magazins.id_magazin = produits.id_magazin) WHERE magazins.nom_magazin LIKE '%".($q)."%' AND magazins.approuve='1' AND magazins.activate='1' AND magazins.payer='1' GROUP BY magazins.id_magazin ORDER BY magazins.nom_magazin ASC";
	$ville = mysql_query($query_ville, $magazinducoin) or die(mysql_error());
	$totalRows_ville = mysql_num_rows($ville);
	while($row_ville= mysql_fetch_assoc($ville)) {
		echo $row_ville['nom_magazin']."|".$row_ville['id_magazin']."\n";
	}
?>
