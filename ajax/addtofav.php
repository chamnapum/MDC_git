<?php require_once('../Connections/magazinducoin.php'); ?>
<?php

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_fav = "SELECT COUNT(*) AS nb FROM favoris WHERE id_magasin = ".$_GET['id_magasin']." AND id_user = ".$_SESSION['kt_login_id'];
$fav= mysql_query($query_fav, $magazinducoin) or die(mysql_error());
$row_fav = mysql_fetch_array($fav);
if($row_fav['nb']>0)
	echo "Magasin d&eacute;ja en favoris";
else {
	mysql_query("INSERT INTO favoris (id_user, id_magasin) VALUES (".$_SESSION['kt_login_id'].",".$_GET['id_magasin'].")", $magazinducoin) or die(mysql_error());
	echo "Magasin ajout&eacute; avec succ&egrave;s";
}
?>