<?php
	$datetime = date('Y-m-d H:i:s');
	$date = date('Y-m-d');
	$query_Recordset1 = " SELECT 
						  evenements.titre,
						  magazins.logo,
						  magazins.photo1,
						  magazins.id_magazin,
						  magazins.nom_magazin,
						  evenements.active,
						  evenements.payer,
						  evenements.photo,
						  evenements.en_avant_fin,
						  maps_ville.nom 
						FROM
						  magazins 
						  INNER JOIN evenements 
							ON magazins.id_magazin = evenements.id_magazin 
						  INNER JOIN region 
							ON region.id_region = magazins.region 
						  INNER JOIN departement 
							ON departement.code = magazins.department 
						  INNER JOIN maps_ville 
							ON maps_ville.id_ville = magazins.ville 
						WHERE (
							(
							  evenements.date_fin >= '".$date."' 
							  AND evenements.date_debut <= '".$date."' 
							  AND evenements.en_avant_fin >= '".$date."' 
							  AND evenements.approuve = '1'
							) 
							OR (
							  evenements.approuve = '0' 
							  AND evenements.public = 0 
							  AND DATE_ADD(
								date_debut,
								INTERVAL - evenements.day_en_avant DAY
							  ) = '".$date."' 
							  AND date_debut >= '".$date."'
							) 
							OR (
							  evenements.date_fin >= '".$date."' 
							  AND evenements.date_debut <= '".$date."' 
							  AND evenements.en_avant_fin >= '".$date."' 
							  AND evenements.approuve = 0 
							  AND evenements.public = 1 
							  AND evenements.public_start < '".$datetime."' 
							  AND (
								evenements.public_start + INTERVAL 20 MINUTE
							  ) <  '".$datetime."' 
							)
						  ) 
						  AND (
							evenements.payer = 1 
							AND evenements.active = 1 
							AND evenements.en_avant = 1 
							AND evenements.en_avant_payer = 1 
							AND region.id_region = '".$default_region."'
						  ) 
						ORDER BY evenements.date_debut DESC ";
		//echo $query_Recordset1;
    $Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die('0'.mysql_error());
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);
	if($totalRows_Recordset1 >= '2'){?>
     <div id="slideshow2" style=" width:248px; height:256px; float:left;">
		<?php
		while($coupon = mysql_fetch_assoc($Recordset1)){			
		?>
        <?php
		$finds = array(" ","(",")","é","è","ê","ë","ï","ö","à","Ë","Ê","É","Ä","Â","Ö","Ô","Ï","ô","'",".","/");
		$replaces = array("-","","","e","e","e","e","i","o","a","E","E","E","A","A","O","O","I","o","","","");
		?>
		<?php $nom=str_replace($finds,$replaces,$coupon['nom_magazin']);?>
        <?php $nom_region=str_replace($finds,$replaces,(getRegionById($default_region)));?>
<div style="width:240px; margin:8px 0px 8px 8px; text-transform:uppercase; height:240px; float:left; background-color:#FFF; <?php if($coupon['photo']){?>  background:url(assets/images/event/<?php echo $coupon['photo']; ?>) no-repeat; <?php }elseif($coupon['photo1']){?> background:url(assets/images/magasins/<?php echo $coupon['photo1']; ?>) no-repeat; <?php }else{?> background:url(assets/images/def.png) no-repeat; <?php }?> background-size:100% 100%;">
    <a href="md-<?php echo $default_region;?>-<?php echo $nom_region;?>-<?php echo $coupon['id_magazin']; ?>-<?php echo $nom;?>.html#tabs-4">
        <div class="slideHometop">
            <div style="width:210px; padding:18px 15px 0px 15px; color:#FFF; font-size:14.5px; font-weight:bold;"><?php echo $coupon['nom_magazin']; ?></div>
            <div style="width:210px; padding:0px 15px 0px 15px; color:#a9a9a9; font-size:14.5px; font-weight:bold;"><?php echo $coupon['nom']; ?></div>
        </div>
        <div style="width:240px; height:38px; float:left; ">
            <div style="color:#FFF; float:left; padding:10px 15px; font-size:14.5px; font-weight:bold; background:rgba(109,21,76,0.7);">
                Évènements
            </div>
            <div class="clear"></div>
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
        <?php $nom_region=str_replace($finds,$replaces,(getRegionById($default_region)));?>
        <div style="width:240px; margin:8px 0px 8px 8px; text-transform:uppercase; height:240px; float:left; background-color:#FFF; <?php if($coupon['photo']){?>  background:url(assets/images/event/<?php echo $coupon['photo']; ?>) no-repeat; <?php }elseif($coupon['photo1']){?> background:url(assets/images/magasins/<?php echo $coupon['photo1']; ?>) no-repeat; <?php }else{?> background:url(assets/images/def.png) no-repeat; <?php }?> background-size:100% 100%;">
            <a href="md-<?php echo $default_region;?>-<?php echo $nom_region;?>-<?php echo $coupon['id_magazin']; ?>-<?php echo $nom;?>.html#tabs-4">
                <div class="slideHometop">
                    <div style="width:210px; padding:18px 15px 0px 15px; color:#FFF; font-size:14.5px; font-weight:bold;"><?php echo $coupon['nom_magazin']; ?></div>
                    <div style="width:210px; padding:0px 15px 0px 15px; color:#a9a9a9; font-size:14.5px; font-weight:bold;"><?php echo $coupon['nom']; ?></div>
                </div>
                <div style="width:240px; height:38px; float:left; ">
                    <div style="color:#FFF; float:left; padding:10px 15px; font-size:14.5px; font-weight:bold; background:rgba(109,21,76,0.7);">
                        Évènements
                    </div>
                    <div class="clear"></div>
                    <div style="color:#FFF; float:left; padding:10px 15px; font-size:14.5px; font-weight:bold; background:rgba(0,0,0,0.7); text-transform:lowercase;">
                        <?php echo substr($coupon['titre'],0,55); ?>
                    </div>
                </div>
            </a>
        </div>   
		<?php }?> 
	</div>    
    <?php }else{?> 
    <div style="width:240px; margin:8px 0px 8px 8px; float:left;">
    <!--<img style="float:left;" src="timthumb.php?src=assets/de/event_pub.png&amp;w=240&amp;h=240&amp;z=1" alt="" />-->
    <script type="text/javascript"><!--
	google_ad_client = "ca-pub-0562242258908269";
	/* test */
	google_ad_slot = "2370230299";
	google_ad_width = 240;
	google_ad_height = 240;
	//-->
	</script>
	
	<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script> 
    </div>
    <?php }?>     