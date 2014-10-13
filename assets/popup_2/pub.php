<?php require_once('../../Connections/connection.php'); ?>
<?php
$magazinducoin = mysql_pconnect($hostname_magazinducoin, $username_magazinducoin, $password_magazinducoin); 
mysql_select_db($database_magazinducoin, $magazinducoin);

/*mysql_query("SET character_set_results=utf8",$magazinducoin);
mb_language('uni');
mb_internal_encoding('UTF-8');
mysql_query("set names 'utf8'" , $magazinducoin); 
$dbname=mysql_select_db($database_magazinducoin, $magazinducoin) or die("Can not select MySQL DB");*/

$id = $_REQUEST['id'];

$query_Recordset2 = "SELECT 
				  tt.* 
				FROM
				  pub_emplacement tt 
				  INNER JOIN 
					(SELECT 
					  sub_type,
					  MAX(date_debut) AS MaxDateTime 
					FROM
					  pub_emplacement 
					WHERE date_debut <= NOW() 
					GROUP BY sub_type) groupedtt 
					ON tt.sub_type = groupedtt.sub_type 
					AND tt.date_debut = groupedtt.MaxDateTime 
				WHERE tt.id='".$id."'
				ORDER BY sub_type ASC";
$Recordset2 = mysql_query($query_Recordset2, $magazinducoin) or die('0'.mysql_error());
$pub2 = mysql_fetch_assoc($Recordset2);
echo $pub2['description'];
?>


