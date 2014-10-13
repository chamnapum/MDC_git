<?php require_once('../../Connections/connection.php'); ?>
<?php
$magazinducoin = mysql_pconnect($hostname_magazinducoin, $username_magazinducoin, $password_magazinducoin); 
mysql_select_db($database_magazinducoin, $magazinducoin);

mysql_query("SET character_set_results=utf8",$magazinducoin);
mb_language('uni');
mb_internal_encoding('UTF-8');
mysql_query("set names 'utf8'" , $magazinducoin); 
$dbname=mysql_select_db($database_magazinducoin, $magazinducoin) or die("Can not select MySQL DB");

$id_user = $_REQUEST['id_user'];
$id_magasin = $_REQUEST['id_mag'];
$siren = $_REQUEST['siren'];

$sql_select = "SELECT id FROM owner_shopper WHERE id_user='".$id_user."' AND id_magazin='".$id_magasin."' AND sirens='".$siren."'";
$query_select = mysql_query($sql_select);
$user=mysql_fetch_array($query_select);

if($user){
	
}else{
$sql_shopper_owner  = "INSERT INTO owner_shopper(id_user,id_magazin,sirens,date) VALUES ('".$id_user."','".$id_magasin."','".$siren."',NOW())";
$result_shopper_owner   = mysql_query($sql_shopper_owner  ) or die (mysql_error());
}
echo 'Veuillez attendre la validation du webmaster';
?>

