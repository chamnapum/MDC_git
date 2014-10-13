<?php
	$sqlcoupon = "SELECT coupons.sous_categorie AS sous_categorie__id, category_0.cat_name AS categorie, coupons.reduction, coupons.date_debut, coupons.date_fin, coupons.titre, coupons.min_achat, coupons.id_magasin, magazins.nom_magazin, magazins.ville, magazins.photo1, category.cat_name AS sous_categorie, coupons.categories AS categorie_id, coupons.id_coupon
FROM (((coupons
LEFT JOIN category ON category.cat_id=coupons.sous_categorie)
LEFT JOIN magazins ON magazins.id_magazin=coupons.id_magasin)
LEFT JOIN category AS category_0 ON category_0.cat_id=coupons.categories) WHERE magazins.region = ".$default_region." 
AND magazins.latlan NOT IN ('(999,999)', '(0,0)', '(,)', '') 
AND coupons.date_debut <= '".date('Y-m-d')."' 
AND coupons.date_fin >= '".date('Y-m-d')."' 
AND coupons.payer = 1
AND coupons.approuve = 1  
AND coupons.active = 1";
	$resultcoupon = mysql_query($sqlcoupon) or die (mysql_error());
	//echo $sqlcoupon;
?>
<div style="width:1000px; padding:10px 0px; float:left; text-align:center; font-size:15px; background:#372f2b;">
    <a href="produits-<?php echo $_REQUEST['region'];?>-999.html" style="width:330px; float:left; border-right:1px solid; color:#a4a4a4;">Accéder à tous les produits</a>
	<a href="coupons-<?php echo $_REQUEST['region'];?>-999.html" style="width:330px; float:left; border-right:1px solid; color:#a4a4a4;">Accéder à tous les coupons</a>
    <a href="pcal.php?region=<?php echo $_REQUEST['region'];?>" style="width:330px; float:left; color:#a4a4a4;">Accéder à tous les évènements</a>
</div>

<script type="text/javascript">

function mycarousel_initCallback(carousel)
{
    // Disable autoscrolling if the user clicks the prev or next button.
    carousel.buttonNext.bind('click', function() {
        carousel.startAuto(0);
    });

    carousel.buttonPrev.bind('click', function() {
        carousel.startAuto(0);
    });

    // Pause autoscrolling if the user moves with the cursor over the clip.
    carousel.clip.hover(function() {
        carousel.stopAuto();
    }, function() {
        carousel.startAuto();
    });
};

jQuery(document).ready(function() {
    jQuery('#mycarousels').jcarousel({
        auto: 3,
       	wrap: 'circular',
		scroll: 4,
		animation: 3000,
        initCallback: mycarousel_initCallback
    });
});

</script>

<div id="bikay">
    <ul id="mycarousels" class="jcarousel-skin-tango">
    	<?php while ($querycoupon=mysql_fetch_array($resultcoupon)) { ?> 
        <?php
		$finds = array(" ","(",")","é","è","ê","ë","ï","ö","à","Ë","Ê","É","Ä","Â","Ö","Ô","Ï","ô","'",".","/");
		$replaces = array("-","","","e","e","e","e","i","o","a","E","E","E","A","A","O","O","I","o","","","");
		?>
		<?php $nom=str_replace($finds,$replaces,($querycoupon['nom_magazin']));?>
        <?php $nom_region=str_replace($finds,$replaces,(getRegionById($default_region)));?>
        <li>
            <div class="slide-body">
            
                <?php
				echo "<a style='width:130px; float:left;' href=\"md-".$default_region."-".$nom_region."-".$querycoupon['id_magasin']."-".$nom.".html#tabs-5\" target=\"_top\"><strong>".$querycoupon['nom_magazin']."</strong><br /><strong>".$querycoupon['titre']."</strong></a>";   
				?>
                 <img style="float:left;" src="timthumb.php?src=assets/images/magasins/<?php echo $querycoupon['photo1'] ?>&amp;w=80&amp;h=65&amp;z=1" width="80" height="65" alt="<?php echo ($querycoupon['nom_magazin']) ?>" />
				
            </div>
            <div class="ville"><strong><?php echo getVilleById($querycoupon['ville']);?></strong></div>
        </li>
        <?php }?>
	</ul>
</div>