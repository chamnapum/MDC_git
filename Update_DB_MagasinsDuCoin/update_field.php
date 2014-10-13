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
//$find = array("-","(",")","é","è","ê","ë","ï","ö","à","Ë","Ê","É","Ä","Â","Ö","Ô","Ï","ô","'","\"",".","/");
$find = array(" ","(",")","é","è","ê","ë","ï","î","ö","à","ù","ÿ","Ë","Ê","É","Ä","Â","Ö","Ô","Ï","ô","'",".","/");
$replace = array("-","","","e","e","e","e","i","i","o","a","u","y","E","E","E","A","A","O","O","I","o"," ","","");
addnewcolumnville();



function strReplaceChar($str){
    $unwanted_array = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
        'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
        'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
        'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
        'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', '\''=>' ', '.'=>' ' );
    $str = strtr( $str, $unwanted_array );
	$str = addslashes(strtolower(trim(str_replace("-"," ",$str))));
    return $str;
}
function addnewcolumnville(){
	$col_name = 'nom_lower';
	$col = mysql_query("SELECT ".$col_name." FROM maps_ville2");
	if (!$col){
		mysql_query("ALTER TABLE maps_ville2 ADD COLUMN ".$col_name." varchar(255) NOT NULL AFTER `nom` ");
		echo $col_name.' has been added to table maps_ville2 <br>';
	} else {
		echo $col_name.' is already exists <br>';
	}
	updatecolumn_ville();
}

function updatecolumn_ville(){
	$query=mysql_query(" SELECT id_ville, nom FROM maps_ville2 ");
	while($row=mysql_fetch_array($query)){
		$nom = $row['nom'];
		$id = $row['id_ville'];
		$nom = strReplaceChar($nom);
		mysql_query(" UPDATE maps_ville2 SET nom_lower = '$nom' WHERE id_ville=$id ");
	}
	echo 'nom_lower has updated <br>';
	addnewcolumnmagasins();
	
}

function addnewcolumnmagasins(){
	$col_name = 'ville_lower,id_ville';
	$col = mysql_query("SELECT ".$col_name." FROM magazins_r");
	if (!$col){
		mysql_query("ALTER TABLE magazins_r ADD COLUMN `ville_lower` varchar(255) NOT NULL AFTER `ville`,ADD COLUMN `id_ville` INT(10) NOT NULL AFTER `ville_lower` ");
		echo $col_name.' has been added to table magazins <br>';
	} else {
		echo $col_name.' is already exists <br>';
	}
	updatecolumn_magasins();
}

function updatecolumn_magasins(){
	$query=mysql_query(" SELECT id_magazin, ville FROM magazins_r ");
	while($row=mysql_fetch_array($query)){
		$ville = $row['ville'];
		$id_magazin = $row['id_magazin'];
		$ville = strReplaceChar($ville);
		mysql_query(" UPDATE magazins_r SET ville_lower = '$ville' WHERE id_magazin=$id_magazin ");
	}
	echo 'Ville_Lower has updated <br>';
	
}

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