<?php
set_time_limit(0);
//require_once('Connections/magazinducoin.php'); 
//mysql_select_db($database_magazinducoin, $magazinducoin);

require_once('../Connections/connection.php');
$magazinducoin = mysql_pconnect($hostname_magazinducoin, $username_magazinducoin, $password_magazinducoin); 
mysql_select_db($database_magazinducoin, $magazinducoin);

// start execut time
 $mtime = microtime();
 $mtime = explode(" ",$mtime);
 $mtime = $mtime[1] + $mtime[0];
 $starttime = $mtime;
//Update Ville
$query = mysql_query("SELECT id_ville,nom_lower,cp FROM maps_ville2");
$count = 0;
while($row = mysql_fetch_array($query)){
	$id_ville = $row['id_ville'];
	$nom = $row['nom_lower'];
	$cp = intval($row['cp']);
	//$nom = addslashes(strtolower(trim(str_replace("-"," ",$nom))));
	//$sql = "UPDATE magazins SET ville='$id_ville' WHERE ville COLLATE UTF8_GENERAL_CI LIKE '%".$nom."%' AND code_postal='$cp'";
	
	
	//$sql = "UPDATE magazins SET id_ville='$id_ville' WHERE ville_lower LIKE '%".$nom."%' AND code_postal='$cp' AND id_ville='0'";
	$sql = "UPDATE magazins_r SET id_ville='$id_ville' WHERE ville_lower LIKE '%".substr($nom,0,2)."%' AND code_postal='$cp' AND id_ville='0'";
	//$sql = "UPDATE magazins SET id_ville='$id_ville' WHERE ville_lower LIKE '%".substr($nom,0,1)."%' AND code_postal='$cp' AND id_ville='0'";
	//$sql = "UPDATE magazins SET id_ville='$id_ville' WHERE code_postal='$cp' AND id_ville='0'";
	
	
	//$sql = "UPDATE magazins SET ville='$id_ville' WHERE ((nom_lower LIKE '%aix en provences%' OR nom_lower LIKE 'ai%' OR nom_lower LIKE 'a%') AND cp='01500') OR cp='01500'";
	
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