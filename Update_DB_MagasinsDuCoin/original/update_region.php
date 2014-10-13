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
 
//Update region
$query = mysql_query("SELECT * FROM region");
$count = 0;
while($row = mysql_fetch_array($query)){
	$id_region = $row['id_region'];
	$nom_region = $row['nom_region'];
	$nom_region = addslashes(strtolower(trim(str_replace("-"," ",$nom_region))));
	if($id_region==18){
		$sql = "UPDATE magazins_r SET region='18' WHERE region='PAYS DE LOIRE'";
	}else{
		$sql = "UPDATE magazins_r SET region='$id_region' WHERE LOWER(region) = '$nom_region'";
	}
	$updateR = mysql_query($sql);
	$count += mysql_affected_rows();
	if(!$updateR){
		echo $sql;
		echo '<br />';
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