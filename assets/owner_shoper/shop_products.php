<?php require_once('../../Connections/connection.php'); ?>

<?php

$magazinducoin = mysql_pconnect($hostname_magazinducoin, $username_magazinducoin, $password_magazinducoin); 

mysql_select_db($database_magazinducoin, $magazinducoin);



mysql_query("SET character_set_results=utf8",$magazinducoin);

mb_language('uni');

mb_internal_encoding('UTF-8');

mysql_query("set names 'utf8'" , $magazinducoin); 

$dbname=mysql_select_db($database_magazinducoin, $magazinducoin) or die("Can not select MySQL DB");



$datetime = date('Y-m-d H:i:s');

$date = date('Y-m-d');

$default_region = $_REQUEST['region'];

$mag_id = $_REQUEST['id_mag'];

function getRegionById($id){

	$query_villes = "SELECT nom_region FROM region WHERE id_region = $id";

	$villes = mysql_query($query_villes) or die(mysql_error());

	$row_villes = mysql_fetch_assoc($villes);

	return $row_villes['nom_region'];

}

?>



<style>

	.cate{

		padding-left:10px;

		font-size:16px;

		font-weight:bold;

	}

	.cate2{

		width:240px;

	}

	.cate2 span{

		float:left;

		font-size:14px;

		width:200px;

		height:20px;

		padding-top:5px;

		padding-left:10px;

	}

</style>
<?php
$finds = array(" ","(",")","é","è","ê","ë","ï","ö","à","Ë","Ê","É","Ä","Â","Ö","Ô","Ï","ô","'",".","/");
$replaces = array("-","","","e","e","e","e","i","o","a","E","E","E","A","A","O","O","I","o","","","");
$nom_region=str_replace($finds,$replaces,(getRegionById($default_region)));
?>
<?php

$query_cate = "SELECT 

				  count(produits.id) as id,

				  category.cat_id,

				  category.cat_name 

				FROM

				  category 

				  INNER JOIN produits 

					ON category.cat_id = produits.categorie 

				  INNER JOIN magazins 

					ON produits.id_magazin = magazins.id_magazin 

				where (

					produits.activate = 1 

					OR (

					  produits.activate = 0 

					  AND produits.public = 1 

					  AND produits.public_start < '".$datetime."' 

					  AND (

						produits.public_start + INTERVAL 20 MINUTE

					  ) < '".$datetime."' 

					)

				  ) 

				  and category.type = '0' 

				  and category.parent_id = '0' 

				  and magazins.region = '".$default_region."' 

				  AND magazins.id_magazin = '".$mag_id."' 

				group by category.cat_id 

				order by category.cat_name asc";

$cate = mysql_query($query_cate) or die(mysql_error());

$nbr_total	= mysql_num_rows($cate);

//echo $query_cate;

if($nbr_total){

?>

	<?php

	$query_total = "SELECT 

					  count(produits.id) as id 

					FROM

					   category 

					  INNER JOIN produits 

						ON category.cat_id = produits.categorie 

					  INNER JOIN magazins 

						ON produits.id_magazin = magazins.id_magazin

					where (

					produits.activate = 1 

					OR (

					  produits.activate = 0 

					  AND produits.public = 1 

					  AND produits.public_start < '".$datetime."' 

					  AND (

						produits.public_start + INTERVAL 20 MINUTE

					  ) < '".$datetime."' 

					)

				  ) 

				  and category.type = '0' 

				  and category.parent_id = '0' 

				  and magazins.region = '".$default_region."' 

				  AND magazins.id_magazin = '".$mag_id."' ";

	$total = mysql_query($query_total) or die(mysql_error());

	$row_total = mysql_fetch_array($total);

	?>

<div class="show_categories" style="float:left; width:210px;">

	<span class="cate">Catégorie :</span>

	<div class="cate2">

		<span>- <a href="produits-<?php echo $nom_region;?>-<?php echo $default_region;?>.html">Toutes catégories (<?php echo $row_total['id'];?>)</a></span>

	<?php while ($row_cate = mysql_fetch_array($cate)){ ?>

		

		<span>- <a href="sub_produits-<?php echo $nom_region;?>-<?php echo $default_region;?>-<?php echo $row_cate['cat_id'];?>.html"><?php echo $row_cate['cat_name'];?> (<?php echo $row_cate['id'];?>)</a></span>

	<?php }?>

	</div>

</div>

<?php }?>



