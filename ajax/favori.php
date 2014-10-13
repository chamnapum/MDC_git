<?php require_once('../Connections/magazinducoin.php'); ?>
<script>
	jQuery(document).ready(function(){
		$( "#favori" ).click(function() {
			var id_mag=<?php echo $_REQUEST['id_mag'];?>;
			var id=<?php echo $_REQUEST['id'];?>;
			var dataString = 'id_mag='+id_mag+'&id='+id;
			$.ajax({
					type: "POST",
					url: "ajax/favori.php",
					data: dataString,
					cache: false,
					success: function(datas){
						$(".head_favori").html(datas);
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
						favoris.id
						, favoris.id_user
						, favoris.id_magasin
					FROM
						favoris
						INNER JOIN magazins 
							ON (favoris.id_magasin = magazins.id_magazin)
						INNER JOIN utilisateur 
							ON (favoris.id_user = utilisateur.id)
					WHERE magazins.id_magazin='".$mag."' and utilisateur.id='".$id."'";
$newsletter = mysql_query($query_newsletter, $magazinducoin) or die(mysql_error());
$row_newslettert = mysql_fetch_array($newsletter);
if(!isset($row_newslettert['id'])){
	$sabonner=" INSERT INTO favoris (id_magasin, id_user) VALUES ('".$mag."','".$id."')";
	
	$newsletter = mysql_query($sabonner, $magazinducoin) or die(mysql_error());
?>
	<span id="favori" style="vertical-align:middle; display: table-cell; float:left;">Enlever Favoris <img src="assets/images/star.png" alt="" style="margin-bottom: -2px;" /></span>
<?php
}else{
	$sabonner="DELETE FROM favoris WHERE id_magasin='".$mag."' and id_user='".$id."'";
	$newsletter = mysql_query($sabonner, $magazinducoin) or die(mysql_error());
?>
	<span id="favori" style="vertical-align:middle; display: table-cell; float:left;">Ajouter à vos favoris <img src="assets/images/star.png" alt="" style="margin-bottom: -2px;" /></span>
<?php }?>