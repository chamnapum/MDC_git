<?php require_once('../../Connections/connection.php'); ?>
<?php

$magazinducoin = mysql_pconnect($hostname_magazinducoin, $username_magazinducoin, $password_magazinducoin); 
	mysql_query("SET character_set_results=utf8", $magazinducoin);
    mb_language('uni'); 
    mb_internal_encoding('UTF-8');
    mysql_select_db($database_magazinducoin, $magazinducoin);
    mysql_query("set names 'utf8'",$magazinducoin);
	
	$id_value = $_POST['id_value'];
	$del1 = "DELETE FROM ville_near WHERE id_ville_near = '".$id_value."'";
	$result1 = mysql_query($del1);
?>
