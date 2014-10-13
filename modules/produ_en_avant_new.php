<div class="espace_pub" >
    
             <h3>Nouveauté</h3>
             <div id="slideshow4">
<style>
.magazin_cou{
	width: 96%;
	position: absolute;
	margin-top: 165px;
	margin-left: 2%;
	height: 20px;
	font-weight: bold;
	font-size: 16px;
	text-align: center;
}
.ville_cou{
	width: 96%;
	position: absolute;
	margin-top: 188px;
	margin-left: 2%;
	height: 20px;
	font-weight: bold;
	font-size: 16px;
	text-align: center;
	background: #9D216E;
	color: #ffffff;
	-webkit-border-radius: 0 0 10px 10px;
	border-radius: 0 0 10px 10px;
}
</style>  
<?php
	$query_Recordset1 = "
	SELECT
        magazins.logo
    , magazins.nom_magazin
    , produits.id
    , produits.titre
    , produits.photo1
    , produits.prix
    , produits.reduction
    , produits.categorie
    , produits.id_magazin
	, maps_ville.nom
FROM
    region
    INNER JOIN magazins 
        ON (region.id_region = magazins.region)
    INNER JOIN produits 
        ON (magazins.id_magazin = produits.id_magazin) 
	INNER JOIN maps_ville 
		ON ( maps_ville.id_ville = magazins.ville ) 
	WHERE region.id_region='".$default_region."' ORDER BY produits.id ASC";
    $Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die('0'.mysql_error());
	
    while($coupon = mysql_fetch_assoc($Recordset1)){		
	?>
<div style="float:left; width:100%;">         
	<div style="float:left; width:90%; margin-top:10px; margin-left:10px;">
        <a href="detail_produit.php?id=<?php echo $coupon['id'];?>&cat_id=<?php echo $coupon['categorie'];?>&mag_id=<?php echo $coupon['id_magazin'];?>#tabs-1" style="width:50%; float:left; font-size:14px; font-weight:bold; text-align:center;"><?php echo substr($coupon['titre'],0,55); ?></a>
        <a href="detail_produit.php?id=<?php echo $coupon['id'];?>&cat_id=<?php echo $coupon['categorie'];?>&mag_id=<?php echo $coupon['id_magazin'];?>#tabs-1">
            <img style="float:left; width:50%;" src="timthumb.php?src=assets/images/produits/<?php echo $coupon['photo1'] ?>&amp;w=118&amp;z=1" alt="" />
        </a>
        <div style="float:left; width:100%; margin-top:30px;">
            <div style="float:right; color:#F59100; font-weight:bold; font-size:23px;">
                <?php if($coupon['reduction']!=''){
                	echo number_format($coupon['prix'] -(($coupon['prix']*$coupon['reduction'])/100),2,',',' ').'&euro;';
                }else{
                	echo $coupon['prix'].'&euro';
                }?>
            </div>
        </div>
    </div>  
    <div class="magazin_cou">
        <?php echo $coupon['nom_magazin']; ?>
    </div>
    <div class="ville_cou">
       <?php echo $coupon['nom']; ?>
    </div>
</div>
	<?php }?> 
	</div>              
</div> 