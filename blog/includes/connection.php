<?php 

	/*$hostname="localhost";
	$user_name="root";
	$password="root";
	$DB="magasin_db";*/	
	
	$hostname="localhost";
	$user_name="root";
	$password="vi8x0vgC";
	$DB="magasin3_bdd";


	$con= mysql_connect($hostname,$user_name,$password) or die("Unable to connect to MySQL");
	mysql_query("SET character_set_results=utf8",$con);
	mb_language('uni');
	mb_internal_encoding('UTF-8');
	mysql_query("set names 'utf8'" , $con); 
	$dbname=mysql_select_db($DB,$con) or die("Can not select MySQL DB");
?>