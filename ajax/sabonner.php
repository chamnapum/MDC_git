<?php require_once('../Connections/magazinducoin.php'); ?>
<script>
jQuery(document).ready(function(){
	$( "#abonne" ).click(function() {
		var id_mag=<?php echo $_REQUEST['id_mag'];?>;
		var id=<?php echo $_REQUEST['id'];?>;
		var dataString = 'id_mag='+id_mag+'&id='+id;
		$.ajax({
				type: "POST",
				url: "ajax/sabonner.php",
				data: dataString,
				cache: false,
				success: function(datas){
					$(".head_abonne").html(datas);
				}
			});	
		return false;
	});					
});
</script>
<?php
$mag = $_REQUEST['id_mag'];
$id = $_REQUEST['id'];

$query_newsletter = "SELECT
						sabonne.id_magasin
						, sabonne.id_user
						, sabonne.id
					FROM
						sabonne
						INNER JOIN utilisateur 
							ON (sabonne.id_user = utilisateur.id)
						INNER JOIN magazins 
							ON (sabonne.id_magasin = magazins.id_magazin)
					WHERE magazins.id_magazin='".$mag."' and utilisateur.id='".$id."'";
$newsletter = mysql_query($query_newsletter, $magazinducoin) or die(mysql_error());
$row_newslettert = mysql_fetch_array($newsletter);
if(!isset($row_newslettert['id'])){
	$sabonner=" INSERT INTO sabonne (id_magasin, id_user) VALUES ('".$mag."','".$id."')";
	
	$newsletter = mysql_query($sabonner, $magazinducoin) or die(mysql_error());
?>
        <span id="abonne" style="vertical-align:middle; display: table-cell; float:left;">Enlever cet établissement <img src="assets/images/hom.png" alt="" style="margin-bottom: -2px;" /></span>

<?php
}else{
	$sabonner="DELETE FROM sabonne WHERE id_magasin='".$mag."' and id_user='".$id."'";
	$newsletter = mysql_query($sabonner, $magazinducoin) or die(mysql_error());
?>
        <span id="abonne" style="vertical-align:middle; display: table-cell; float:left;">S'abonner à cet établissement <img src="assets/images/hom.png" alt="" style="margin-bottom: -2px;" /></span>

<?php
}
?>