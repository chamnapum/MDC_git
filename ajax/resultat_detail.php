<?php 

require_once('../Connections/magazinducoin.php');
include_once("../class/GoogleMap.php");
include_once("../class/JSMin.php");

function cmp($a, $b) {
    if ($a == $b) {
        return 0;
    }
    return ($a < $b) ? -1 : 1;
}

function cmp2($a, $b) {
    if ($a == $b) {
        return 0;
    }
    return ($a > $b) ? -1 : 1;
}
?>


<?php   

$region			= isset($_GET['region'])?$_GET['region']:"";
$order			= isset($_GET['order'])?$_GET['order']:"produits.id";
$magasin 		= isset($_GET['mag_id'])?$_GET['mag_id']:"";
$now = date('Y-m-d H:i:s');
$query_liste_produit = "SELECT 
  produits.id 
FROM
  produits 
  LEFT JOIN magazins 
    ON magazins.id_magazin = produits.id_magazin";

$tab = array();
	//$tab[] = " produits.activate = 1";
	//$tab[] = " pub_emplacement.type = '2'";
	//$tab[] = " pub_emplacement.sub_type = '1'";
	//$tab[] = " pub.payer = '1'";
	$tab[] = " (produits.activate = 1 OR (produits.activate = 0 AND produits.public=1 AND produits.public_start < '".$now."' AND (produits.public_start + INTERVAL 20 MINUTE) < '".$now."'))";
	$tab[] = " magazins.region = ".$region;
if($magasin) $tab[] = " magazins.id_magazin = $magasin ";
	//$tab[] = " magazins.latlan NOT IN ('(999,999)','(0,0)','(,)','')";
$where = "";

if(count($tab)) $where = " WHERE ".implode(' AND ',$tab);
$query_liste_produit .= $where;
$query_liste_produit .= " ORDER BY produits.id DESC";
//echo $query_liste_produit.'<br/>';
// pagination 
//nb_ total des produits 

$query=mysql_query($query_liste_produit) or die("ici".mysql_error());


$ids = array();

$MAP_OBJECT = new GoogleMapAPI(); 
$MAP_OBJECT->_minify_js = isset($_REQUEST["min"])?FALSE:TRUE;
if($adresse) 
	$adresse_client = $MAP_OBJECT->getGeoCode("$adresse, France");

while($mes_ids = mysql_fetch_array($query)){
	if(isset($adresse_client)){
		$adresse_produit = str_replace('(','',$mes_ids['latlan']);
		$adresse_produit = str_replace(')','',$adresse_produit);
		$adresse_produit_tab = explode(',',$adresse_produit);
		$distance =  $MAP_OBJECT->geoGetDistance($adresse_client['lat'],$adresse_client['lon'],$adresse_produit_tab[0],		$adresse_produit_tab[1],'K');
	}
	else{
		$distance = 0;
	}
	//echo "Distance entre adresse client et adresse produit est : $distance <br>";
	if($distance <= $rayon) {
		$ids[] = $mes_ids['id'];
		$les_distance[$mes_ids['id']] = $distance;
	}
}

if(count($ids)){
	
	$query_liste_produit_test = "SELECT 
					  produits.id,
					  magazins.nom_magazin,
					  magazins.id_magazin,
					  magazins.adresse,
					  magazins.ville,
					  magazins.latlan,
					  magazins.logo,
					  magazins.code_postal,
					  maps_ville.nom,
					  maps_ville.cp,
					  produits.categorie,
					  produits.id_magazin,
					  produits.sous_categorie,
					  produits.titre,
					  produits.id,
					  produits.reference,
					  produits.prix,
					  produits.prix2,
					  produits.reduction,
					  produits.en_stock,
					  produits.description,
					  produits.photo1
					FROM
					  (
						produits 
						LEFT JOIN magazins 
						  ON magazins.id_magazin = produits.id_magazin
						LEFT JOIN maps_ville 
						  ON maps_ville.id_ville = magazins.ville 
					  ) WHERE produits.id IN (".implode(',',$ids).")";
	
	$query_test = mysql_query($query_liste_produit_test);
	$nbr_total	= mysql_num_rows($query_test);
	
	//initialiser les variables du pagination
	if(isset($_GET['start'])){$start=$_GET['start'];}else{$start=0;}
	if(isset($_GET['nb_par_page'])){$nb_par_page=$_GET['nb_par_page'];}else{$nb_par_page=10;}
	$query_liste_produits=$query_liste_produit_test." ORDER BY produits.id DESC LIMIT $start, $nb_par_page";
	
	//echo $query_liste_produits;
	$nb_des_pages=ceil($nbr_total/$nb_par_page);
	$lastpage = ceil($nbr_total/$nb_par_page);
//fin code pagination
}
else $nbr_total = 0;
?>

<style>
	.prix{
		float: left;
		width: 100%;
		text-align: center;
		font-size:26px;
		color:#9D216E;
	}
	.stock{
		text-align: center;
		width: 100%;
		float: left;
		color:#9D216E;
		font-size: 14px;
	}
	.reductionB{
		background-image: url("template/images/fleche_orange_small.png");
		background-position: left top;
		background-repeat: no-repeat;
		color: #FFFFFF;
		font-size: 15px;
		font-weight: bold;
		height:30px;
		width: 51px;
		padding:5px;
		float:left;
		margin-top:5px;
	}
	.prixB{
		color: #F6AE30;
		font-size: 18px;
		float: right;
		margin-top:7px;
	}
</style>
<div class="tri">
  
<?php

$rkt= mysql_query($query_liste_produits);
if((isset($_GET['order']) and $_GET['order'] != "distance") or !isset($_GET['order']) or empty($_GET['adresse'])){
	while($liste = mysql_fetch_assoc($rkt)){
?>
<?php $nom=str_replace($finds,$replaces,($liste['nom_magazin']));?>
<?php $nom_pro=str_replace($finds,$replaces,($liste['titre']));?>
<?php $nom_region=str_replace($finds,$replaces,(getRegionById($region)));?>

<div class="box" style="min-height:110px;">
    <div class="box_inner">
    	<div style="margin:10px; width:125px; float:left;">
    		<a href="pd-<?php echo $region;?>-<?php echo $nom_region;?>-<?php echo $liste['id_magazin'];?>-<?php echo $nom;?>-<?php echo $liste['id'];?>-<?php echo $nom_pro;?>-<?php echo $liste['categorie'];?>.html#tabs6">
            	<img src="timthumb.php?src=assets/images/produits/<?php echo $liste['photo1']; ?>&z=1&w=125&h=90" />
            </a>
        </div>
        <div style="margin:10px; width:415px; float:left;">
        	<span style="font-weight:bold; font-size:18px; width:100%; float:left;">
			<a href="pd-<?php echo $region;?>-<?php echo $nom_region;?>-<?php echo $liste['id_magazin'];?>-<?php echo $nom;?>-<?php echo $liste['id'];?>-<?php echo $nom_pro;?>-<?php echo $liste['categorie'];?>.html#tabs6">
			<?php echo $liste['titre']; ?>
            </a>
            </span>
            <div style="font-size:14px; width:100%; margin-top:15px; float:left;">&diams; <span><?php  echo $liste['description']; ?></span></div>
        </div>
        <div style="margin:10px; width:135px; float:left;">
        	<span class="prix" <?php if($liste['reduction']>0) echo 'style="text-decoration:line-through"'; ?>>
				<?php echo $liste['prix']."&euro;"; ?>
            </span>
            <?php if($liste['en_stock']==1){echo "<span class='stock'>".$xml->En_stock."</span>";} ?>
			<?php if($liste['reduction']>0) { ?>
                <div class="reductionB"><?php echo $liste['reduction']; ?>%</div> 
                <div class="prixB"><?php echo $liste['prix2']."&euro;"; ?></div>
            <?php } ?>
        </div>
    </div>
</div>
<?php
	}
}
?>



<div class="tri_inner" style="width:740px !important;">
    <div class="tri_inner_right">
		<div class="pagination">
            <div style="padding-top:4px;"><?php echo $xml->page ; ?> <?php echo $start+1;?> <?php echo $xml->sur; ?>  <?php echo $nb_des_pages; ?> </div>
            <div class="clear"></div>
            <div style="color:#9d216e;padding-top:4px;">
                <?php
					if($nb_des_pages > 1)
					{
						for($i=1;$i<= $nb_des_pages;$i++){
							$p = ($i-1) * $nb_par_page;
							
							echo "<a href=\"javascript:ajax('".$_SERVER['PHP_SELF']."?mag_id=".$magasin."&region=".$region."&order=distance&start=".$p."&nb_par_page=".$nb_par_page."','#result');\">".$i."  </a>";					
						}	
					}
			   ?>
			</div>
		</div>
	</div>
</div>
