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
//$order			= isset($_GET['order'])?$_GET['order']:"produits.id";
$magasin 		= isset($_GET['mag_id'])?$_GET['mag_id']:"";

$tab = array();
	$tab[] = " magazins.region = ".$region;
if($magasin) $tab[] = " magazins.id_magazin = $magasin ";
	//$tab[] = " magazins.latlan NOT IN ('(999,999)','(0,0)','(,)','')";


$datetime = date('Y-m-d H:i:s');
$date = date('Y-m-d');

	$query_liste_produit_test = "SELECT 
								  magazins.nom_magazin,
								  magazins.logo,
								  magazins.adresse,
								  magazins.latlan,
								  magazins.photo1,
								  coupons.id_coupon,
								  coupons.date_debut,
								  coupons.date_fin,
								  coupons.titre,
								  coupons.min_achat,
								  coupons.description,
  								  coupons.photo1 AS photo,
								  magazins.id_magazin,
								  magazins.ville AS cp,
								  magazins.region,
								  category.cat_name,
								  DATE_ADD(
									coupons.date_debut,
									INTERVAL - coupons.day_en_tete_liste DAY
								  ) AS dates,
								  coupons.date_debut 
								FROM
								  coupons 
								  LEFT JOIN magazins 
									ON magazins.id_magazin = coupons.id_magasin 
								  LEFT JOIN category 
									ON category.cat_id = coupons.categories 
								  INNER JOIN region 
									ON region.id_region = magazins.region 
								  INNER JOIN departement 
									ON departement.code = magazins.department 
								  INNER JOIN maps_ville 
									ON maps_ville.id_ville = magazins.ville  
								WHERE (".implode(' AND ',$tab)."  
									AND coupons.en_tete_liste_payer = 1 
									AND coupons.en_tete_liste = 1 
									AND coupons.payer = 1 
									AND coupons.active = 1 
									AND coupons.approuve = 0 
									AND coupons.public= 0  
									AND DATE_ADD(
									  coupons.date_debut,
									  INTERVAL - coupons.day_en_tete_liste DAY
									) = '".$date."' 
									AND date_debut >= '".$date."' 
								  ) OR
								  ( ".implode(' AND ',$tab)."   
									AND coupons.date_fin >= '".$date."'  
									AND coupons.date_debut <= '".$date."'  
									AND coupons.payer = 1 
									AND coupons.active = 1 
									AND coupons.approuve = '1'
								  )
									OR
								  ( ".implode(' AND ',$tab)."  
									AND coupons.date_fin >= '".$date."'  
									AND coupons.date_debut <= '".$date."'  
									AND coupons.payer = 1 
									AND coupons.active = 1 
									AND coupons.approuve = 0 
									AND coupons.public=1 
									AND coupons.public_start <= '".$datetime."' 
									AND (coupons.public_start + INTERVAL 20 MINUTE) < '".$datetime."'
								  )";
	
	$query_test = mysql_query($query_liste_produit_test);
	$nbr_total	= mysql_num_rows($query_test);
	
	//initialiser les variables du pagination
	if(isset($_GET['start'])){$start=$_GET['start'];}else{$start=0;}
	if(isset($_GET['nb_par_page'])){$nb_par_page=$_GET['nb_par_page'];}else{$nb_par_page=10;}
	$query_liste_produits=$query_liste_produit_test." ORDER BY coupons.id_coupon DESC LIMIT $start, $nb_par_page";
	
	//echo $query_liste_produits;
	$nb_des_pages=ceil($nbr_total/$nb_par_page);
	$lastpage = ceil($nbr_total/$nb_par_page);
//fin code pagination

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
	.continued, .adjust{
		float:left;
		margin-right:10px;
		color:#9d216e;
	}
</style>
<script type="text/javascript">

$(function(){

// The height of the content block when it's not expanded
var adjustheight = 50;
// The "more" link text
var moreText = "+  Lire la suite";
// The "less" link text
var lessText = "- Afficher moins";

// Sets the .more-block div to the specified height and hides any content that overflows
$(".more-less .more-block").css('height', adjustheight).css('overflow', 'hidden');

// The section added to the bottom of the "more-less" div
$(".more-less").append('<p class="continued">[&hellip;]</p><a href="#" class="adjust"></a>');

$("a.adjust").text(moreText);

$(".adjust").toggle(function() {
		$(this).parents("div:first").find(".more-block").css('height', 'auto').css('overflow', 'visible');
		// Hide the [...] when expanded
		$(this).parents("div:first").find("p.continued").css('display', 'none');
		$(this).text(lessText);
	}, function() {
		$(this).parents("div:first").find(".more-block").css('height', adjustheight).css('overflow', 'hidden');
		$(this).parents("div:first").find("p.continued").css('display', 'block');
		$(this).text(moreText);
});
});

</script>
<div class="tri">
  
<?php

$rkt= mysql_query($query_liste_produits);
if((isset($_GET['order']) and $_GET['order'] != "distance") or !isset($_GET['order']) or empty($_GET['adresse'])){
	while($liste = mysql_fetch_assoc($rkt)){
?>
<div class="box" style="min-height:110px;">
    <div class="box_inner">
    	<div style="margin:10px; width:125px; float:left;">
        	<?php if($liste['photo']){?>
    		<img src="timthumb.php?src=assets/images/coupon/<?php echo $liste['photo']; ?>&z=1&w=125&h=90" />
            <?php }else{?>
    		<img src="timthumb.php?src=assets/images/magasins/<?php echo $liste['photo1']; ?>&z=1&w=125&h=90" />
            <?php }?>
        </div>
        <div style="margin:15px 10px 10px 10px; width:560px; float:left;">
        	<span style="font-weight:bold; font-size:18px; width:100%; float:left;">
			<?php echo $liste['titre']; ?>
            </span>
            <div style="font-size:14px; width:100%; margin-top:15px; float:left;">
                <div class="more-less">
                    <div class="more-block">
                        <p>&diams; <?php  echo $liste['description']; ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div style="margin:10px; width:200px; float:left;">
        	<span style="color:#999; font-weight:bold; font-style:italic; bottom:0px; float:right;">Valide Du <?php  echo $liste['date_debut']; ?> au <?php  echo $liste['date_fin']; ?></span>
            <a onClick="ajax('ajax/addtocart.php?region=<?php echo $region;?>&id_coupon=<?php echo $liste['id_coupon']; ?>','#carts');" href="javascript:;" style="color:#FFFFFF; background:#F6AE30; padding:5px 10px; margin-top:10px; float:right;">Ajouter à Ma liste</a>
        </div>
    </div>
</div>
<?php
	}
}
?>



<div class="tri_inner">
    <div class="tri_inner_right">
		<div class="pagination">
            <div style="padding-top:4px;"><?php echo $xml->page ; ?> <?php echo $start+1;?> <?php echo $xml->sur; ?>  <?php echo $nb_des_pages; ?> </div>
            <div style="color:#9d216e;padding-top:4px;">
                <?php
					if($nb_des_pages > 1)
					{
						for($i=1;$i<= $nb_des_pages;$i++){
							$p = ($i-1) * $nb_par_page;
							
							echo "<a href=\"javascript:ajax('".$_SERVER['PHP_SELF']."?mag_id=".$magasin."&region=".$region."&order=distance&start=".$p."&nb_par_page=".$nb_par_page."','#re_coupon');\">".$i."  </a>";					
						}	
					}
			   ?>
			</div>
		</div>
	</div>
</div>
