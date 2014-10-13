                   
<?php
	$query_Recordset1 = "
	SELECT
	  produits.id,
	  magazins.photo1,
	  magazins.nom_magazin,
	  produits.id,
	  produits.titre,
	  produits.photo1 AS photo,
	  produits.prix,
	  produits.reduction,
	  produits.categorie,
	  produits.id_magazin,
	  maps_ville.nom 
	FROM
		produits
		INNER JOIN pub 
			ON (produits.id = pub.id_produit)
		INNER JOIN magazins 
			ON (produits.id_magazin = magazins.id_magazin)
		INNER JOIN maps_ville 
			ON (magazins.ville = maps_ville.id_ville)
		INNER JOIN region 
			ON (magazins.region = region.id_region)
		INNER JOIN pub_emplacement 
			ON ( pub.emplacement = pub_emplacement.id ) 
	WHERE (pub_emplacement.type = '2'   
	  AND pub_emplacement.sub_type = '1' 
	  AND pub.payer = '1' 
	  AND pub.date_fin > NOW() 
	  AND pub.date_debut < NOW()
	  AND produits.activate = '1' AND region.id_region='".$default_region."')";
	//echo $query_Recordset1;
    $Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die('0'.mysql_error());
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);
	if($totalRows_Recordset1 >= '2'){?>
    <div id="slideshow3" style=" width:248px; height:256px; float:left;">
		<?php
		while($coupon = mysql_fetch_assoc($Recordset1)){		
		?>
        <?php
		$finds = array(" ","(",")","é","è","ê","ë","ï","ö","à","Ë","Ê","É","Ä","Â","Ö","Ô","Ï","ô","'",".","/");
		$replaces = array("-","","","e","e","e","e","i","o","a","E","E","E","A","A","O","O","I","o","","","");
		?>
		<?php $nom=str_replace($finds,$replaces,($coupon['nom_magazin']));?>
        <?php $nom_pro=str_replace($finds,$replaces,($coupon['titre']));?>
        <?php $nom_region=str_replace($finds,$replaces,(getRegionById($default_region)));?>
		<div style="width:240px; margin:8px 0px 8px 8px; text-transform:uppercase; height:240px; float:left; background-color:#FFF; <?php if($coupon['photo']){?>  background:url(assets/images/produits/<?php echo $coupon['photo']; ?>) no-repeat; <?php }else{?> background:url(assets/images/magasins/<?php echo $coupon['photo1']; ?>) no-repeat; <?php }?> background-size:100% 100%;">
            <a href="md-<?php echo $default_region;?>-<?php echo $nom_region;?>-<?php echo $coupon['id_magazin']; ?>-<?php echo $nom;?>.html#tabs-5">
                <div class="slideHometop">
                    <div style="width:210px; padding:18px 15px 0px 15px; color:#FFF; font-size:14.5px; font-weight:bold;"><?php echo $coupon['nom_magazin']; ?></div>
                    <div style="width:210px; padding:0px 15px 0px 15px; color:#a9a9a9; font-size:14.5px; font-weight:bold;"><?php echo $coupon['nom']; ?></div>
                </div>
                <div style="width:240px; height:38px; float:left; ">
                    <div style="color:#FFF; float:left; padding:10px 15px; font-size:14.5px; font-weight:bold; background:rgba(109,21,76,0.7);">
                        COUPON RéDUC
                    </div>
                    <div style="color:#FFF; float:left; padding:10px 15px; font-size:14.5px; font-weight:bold; background:rgba(0,0,0,0.7); text-transform:lowercase;">
                        <?php echo substr($coupon['titre'],0,55); ?>
                    </div>
                </div>
            </a>
        </div>  
		<?php }?> 
     </div>
    <?php }elseif($totalRows_Recordset1 == '1'){?>
     <div style=" width:248px; height:256px; float:left;">
		<?php
		while($coupon = mysql_fetch_assoc($Recordset1)){		
		?>
        <?php
		$finds = array(" ","(",")","é","è","ê","ë","ï","ö","à","Ë","Ê","É","Ä","Â","Ö","Ô","Ï","ô","'",".","/");
		$replaces = array("-","","","e","e","e","e","i","o","a","E","E","E","A","A","O","O","I","o","","","");
		?>
		<?php $nom=str_replace($finds,$replaces,($coupon['nom_magazin']));?>
        <?php $nom_pro=str_replace($finds,$replaces,($coupon['titre']));?>
        <?php $nom_region=str_replace($finds,$replaces,(getRegionById($default_region)));?>
        
        <div style="width:240px; margin:8px 0px 8px 8px; text-transform:uppercase; height:240px; float:left; background-color:#FFF; <?php if($coupon['photo']){?>  background:url(assets/images/produits/<?php echo $coupon['photo']; ?>) no-repeat; <?php }else{?> background:url(assets/images/magasins/<?php echo $coupon['photo1']; ?>) no-repeat; <?php }?> background-size:100% 100%;">
            <a href="md-<?php echo $default_region;?>-<?php echo $nom_region;?>-<?php echo $coupon['id_magazin']; ?>-<?php echo $nom;?>.html#tabs-5">
                <div class="slideHometop">
                    <div style="width:210px; padding:18px 15px 0px 15px; color:#FFF; font-size:14.5px; font-weight:bold;"><?php echo $coupon['nom_magazin']; ?></div>
                    <div style="width:210px; padding:0px 15px 0px 15px; color:#a9a9a9; font-size:14.5px; font-weight:bold;"><?php echo $coupon['nom']; ?></div>
                </div>
                <div style="width:240px; height:38px; float:left; ">
                    <div style="color:#FFF; float:left; padding:10px 15px; font-size:14.5px; font-weight:bold; background:rgba(109,21,76,0.7);">
                        Produits
                    </div>
                    <div style="color:#FFF; float:left; padding:10px 15px; font-size:14.5px; font-weight:bold; background:rgba(0,0,0,0.7); text-transform:lowercase;">
                        <?php echo substr($coupon['titre'],0,55); ?>   
                        <span style="color:#f49c00;">
                        <?php if($coupon['reduction']!=''){
							echo number_format($coupon['prix'] -(($coupon['prix']*$coupon['reduction'])/100),2,',',' ').'&euro;';
						}else{
							echo $coupon['prix'].'&euro';
						}?>
                        </span>
                    </div>
                </div>
            </a>
        </div>  

		<?php }?> 
     </div>
    <?php }else{?>
    <div style="width:240px; margin:8px 0px 8px 8px; float:left;">
    <img style="float:left;" src="timthumb.php?src=assets/de/produit_pub.png&amp;w=240&amp;h=240&amp;z=1" alt="" />
    </div>
	<?php }?>  
