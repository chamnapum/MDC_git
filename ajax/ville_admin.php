<?php require_once('../Connections/connection.php'); ?>

<?php
$magazinducoin = mysql_pconnect($hostname_magazinducoin, $username_magazinducoin, $password_magazinducoin); 
	mysql_query("SET character_set_results=utf8", $magazinducoin);
    mb_language('uni'); 
    mb_internal_encoding('UTF-8');
    mysql_select_db($database_magazinducoin, $magazinducoin);
    mysql_query("set names 'utf8'",$magazinducoin);
?>

<?php 
	$de = '';
	$de = $_REQUEST['id_departement'];
	$symbol = ',';
	
	$condi='';
	
?>
<select multiple name="ville_admin[]" id="ville_admin" size="5" style="width:185px; height:auto !important;">
 <?php 
 	if($_GET['default']){
          $result=mysql_query("SELECT
									maps_ville.nom
									, maps_ville.cp
									, maps_ville.id_ville
									, ville_near.id_magazin
								FROM
									maps_ville
									INNER JOIN ville_near 
										ON (maps_ville.id_ville = ville_near.nom_ville_near) WHERE ville_near.id_magazin='".$_GET['default']."' ORDER BY nom ASC");
          
          $totalnum=mysql_num_rows($result);
         ?>
         <?php $num=0; while ($query=mysql_fetch_array($result)) { $num++;
		 	if($num==$totalnum)$symbol='';
        	 echo '<option value="'.$query['id_ville'].'" selected="selected">'.$query['nom'].' '.$query['cp'].'</option> ';
			 $val .= "'".$query['id_ville']."'".$symbol;
         }
		 
		 $condi=' AND maps_ville.id_ville NOT IN ('.$val.')';
	}?>




<?php
		$query_ville = "SELECT 
						  maps_ville.id_ville,
						  maps_ville.nom,
						  maps_ville.cp
						FROM
						  maps_ville 
						  INNER JOIN departement 
							ON maps_ville.id_departement = departement.id_departement WHERE departement.code='".$de."' $condi ORDER BY nom ASC";
							
		$ville = mysql_query($query_ville, $magazinducoin) or die(mysql_error());
		while($row_ville= mysql_fetch_array($ville)) {
			echo '<option value="'.$row_ville['id_ville'].'">'.$row_ville['nom'].' '.$row_ville['cp'].'</option> ';
		}
?>
</select>