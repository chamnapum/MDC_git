<?php require_once('../Connections/magazinducoin.php'); ?>
<?php 
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
mysql_select_db($database_magazinducoin, $magazinducoin);

	$de = '';
	$vil = '';
	if($_REQUEST['de']=='0'){
		$de = ' WHERE departement.id_region='.$default_region;
	}else{
		$de = ' WHERE departement.code='.$_REQUEST['de'];
	}
?>

<script>

var preload_data = [
<?php
	$query_ville = "SELECT 
					  maps_ville.id_ville,
					  maps_ville.nom,
					  maps_ville.cp
					FROM
					  maps_ville 
					  INNER JOIN departement 
						ON maps_ville.id_departement = departement.id_departement $de ORDER BY nom ASC";
	$ville = mysql_query($query_ville, $magazinducoin) or die(mysql_error());
	while($row_ville= mysql_fetch_array($ville)) {
?>
  { id: '<?php echo $row_ville['id_ville'];?>', text: '<?php echo $row_ville['nom'].' '.$row_ville['cp'];?>'},
<?php }?>
];

<?php
if($_REQUEST['ville']!=''){
?>
	var preload_edit = [
	<?php
		$query_villes = "SELECT 
					  maps_ville.id_ville,
					  maps_ville.nom,
					  maps_ville.cp
					FROM
					  maps_ville 
					  INNER JOIN departement 
						ON maps_ville.id_departement = departement.id_departement WHERE departement.id_region='".$default_region."' AND maps_ville.id_ville IN (".$_REQUEST['ville'].") ORDER BY nom ASC";
		$villes = mysql_query($query_villes, $magazinducoin) or die(mysql_error());
		$totals = mysql_num_rows($villes);
		while($row_villes= mysql_fetch_array($villes)) {
	?>
	  { id: '<?php echo $row_villes['id_ville'];?>', text: '<?php echo $row_villes['nom'].' '.$row_villes['cp'];?>'},
	<?php }?>
	];
<?php } else {?>
	var preload_edit='';
<?php }?>
jQuery(document).ready(function () {
	$('.id_ville').select2({
	  placeholder: "Ville ou Code postal",
	  minimumInputLength: 2,
	  maximumSelectionSize: 3,
	  multiple: true,
	  query: function (query){
		  var data = {results: []};

		  jQuery.each(preload_data, function(){
			  if(query.term.length == 0 || this.text.toUpperCase().indexOf(query.term.toUpperCase()) >= 0 ){
				  data.results.push({id: this.id, text: this.text });
			  }
		  });

		  query.callback(data);
	  }
  });
  $('.id_ville').select2('data', preload_edit );
});
</script>

<style>
.select2-choices , .select2-input{
	width:178px !important;
	height:12px !important;
}
.select2-container-multi ul.select2-choices{
	min-height:24px !important;
	/*margin-bottom: 5px;*/
}
</style>
<input type="text" id="id_ville" name="id_ville" class="id_ville" style="width:175px; margin-bottom:9px;"/>

