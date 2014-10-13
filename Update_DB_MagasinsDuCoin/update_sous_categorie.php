<?php
set_time_limit(0);
/*require_once('Connections/magazinducoin.php'); 
mysql_select_db($database_magazinducoin, $magazinducoin);*/
require_once('../Connections/connection.php');
$magazinducoin = mysql_pconnect($hostname_magazinducoin, $username_magazinducoin, $password_magazinducoin); 
mysql_select_db($database_magazinducoin, $magazinducoin);
// start execut time
 $mtime = microtime();
 $mtime = explode(" ",$mtime);
 $mtime = $mtime[1] + $mtime[0];
 $starttime = $mtime;
 
//Update sous_categorie
$query = mysql_query("SELECT cat_id,cat_name FROM category WHERE type=3");
$count = 0;
while($row = mysql_fetch_array($query)){
	$cat_id = $row['cat_id'];
	$cat_name = addslashes(strtolower(trim(str_replace("-"," ",$row['cat_name']))));
	$sql = "UPDATE magazins_r SET sous_categorie='$cat_id' WHERE LOWER(sous_categorie) LIKE '%".$cat_name."%'";
	$updateR = mysql_query($sql);
	if(!$updateR){
		echo $sql;
		echo '<br />';
	}else{
		$count++;
	}
}
echo "Success: ".$count."<br />";

// end execut time
 $mtime = microtime();
 $mtime = explode(" ",$mtime);
 $mtime = $mtime[1] + $mtime[0];
 $endtime = $mtime;
 $totaltime = ($endtime - $starttime);
 $hours = floor($totaltime / 3600);
 $mins = floor(($totaltime - ($hours*3600)) / 60);
 $secs = floor($totaltime % 60);
 echo '<br>Ttime spended: '.$hours.'h '.$mins.'m '.$secs.'s';
?>