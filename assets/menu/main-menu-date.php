<?php session_start() ?>
<?php require_once('../../Connections/connection.php'); ?>
<?php
$magazinducoin = mysql_pconnect($hostname_magazinducoin, $username_magazinducoin, $password_magazinducoin); 
mysql_select_db($database_magazinducoin, $magazinducoin);

	mysql_query("SET character_set_results=utf8", $magazinducoin);
    mb_language('uni'); 
    mb_internal_encoding('UTF-8');
    mysql_select_db($database_magazinducoin, $magazinducoin);
    mysql_query("set names 'utf8'",$magazinducoin);

?>
<?php
function getRegionById($id){
	$query_villes = "SELECT nom_region FROM region WHERE id_region = $id";
	$villes = mysql_query($query_villes) or die(mysql_error());
	$row_villes = mysql_fetch_array($villes);
	return $row_villes['nom_region'];
}

$finds = array(" ","(",")","é","è","ê","ë","ï","ö","à","Ë","Ê","É","Ä","Â","Ö","Ô","Ï","ô","'",".","/");
$replaces = array("-","","","e","e","e","e","i","o","a","E","E","E","A","A","O","O","I","o","","","");
?>
<?php $nom_region=str_replace($finds,$replaces,(getRegionById($_SESSION['region'])));?>

<link rel="stylesheet" href="assets/themes/base/jquery.ui.datepicker.css">
<link rel="stylesheet" href="assets/themes/base/jquery.ui.core.css">

<script src="assets/ui/jquery.ui.core.js"></script>
<script src="assets/ui/jquery.ui.widget.js"></script>
<script src="assets/ui/jquery.ui.datepicker.js"></script>
<script src="assets/ui/jquery.ui.datepicker-fr.js"></script>
<script>
	$(function() {
		$('#datepicker').datepicker({
			//changeMonth: true,
			//changeYear: true,
			onSelect: function(dateText, inst) { 
				var even = $("#even").val();
				var region = $("#regions").val();
				//alert(even+' , '+dateText);
				var date = $(this).datepicker('getDate'),
				day  = date.getDate(),  
				month = date.getMonth() + 1,              
				year =  date.getFullYear();
				//alert(day + '-' + month + '-' + year);
				
				//window.location = 'http://test.com?dt=' + dateText;
				window.location = 'evenement-<?php echo $nom_region;?>-'+region+'-'+even+'-'+day+'-'+month+'-'+year+'-day.html';
				
			}
		});
	});
</script>
<style>
.ui-datepicker{
	padding:0px;
}
</style>
<div style="font-size:16px; margin:0px 5px 8px 5px; font-weight:bold; text-align:center; width:95%;">Calendrier des évènements</div>
<?php
$sqlcate = "SELECT cat_id, parent_id, cat_name FROM category WHERE parent_id='0' AND type='2' ORDER BY cat_name ASC";
$resultcate = mysql_query($sqlcate) or die (mysql_error());
?>
<select style="width:300px;" id="even">
<option value="tout">Toutes les catégories d'évènement</option>
<?php while ($querycate=mysql_fetch_array($resultcate)){?>
<option value="<?php echo $querycate['cat_id']; ?>"><?php echo $querycate['cat_name']; ?></option>
<?php }?>
</select>
<input type="hidden" id="regions" value="<?php echo $_REQUEST['region'];?>" />
<div id="datepicker"></div>
