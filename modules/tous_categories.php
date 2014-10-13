<?php
$query_liste_produit = "SELECT DISTINCT 
  region.nom_region,
  magazins.*,
  maps_ville.nom,
  magazins.description,
  (SELECT 
  COUNT(*) 
FROM
  produits 
  INNER JOIN pub 
    ON (produits.id = pub.id_produit)
  INNER JOIN pub_emplacement 
    ON (pub.emplacement = pub_emplacement.id)  
WHERE id_magazin = magazins.id_magazin 
  AND pub_emplacement.type = '2' 
  AND pub_emplacement.sub_type = '1'
  AND pub.payer = '1'  
  AND produits.activate = '1') AS nb_produits,
  (SELECT 
    COUNT(*) 
  FROM
    coupons 
  WHERE id_magasin = magazins.id_magazin 
    AND coupons.date_fin > NOW() 
    AND coupons.date_debut < NOW() 
    AND coupons.payer = 1 
    AND coupons.approuve = 1 
    AND coupons.active = 1) AS nb_coupons,
  (SELECT 
    COUNT(*) 
  FROM
    evenements 
  WHERE id_magazin = magazins.id_magazin 
    AND evenements.active = '1' 
    AND evenements.payer = '1' 
    AND evenements.approuve = '1' 
    AND evenements.date_fin > NOW() 
    AND evenements.date_debut < NOW()) AS nb_events 
FROM
  (
    (
      magazins 
      LEFT JOIN region 
        ON region.id_region = magazins.region
    ) 
    LEFT JOIN maps_ville 
      ON maps_ville.id_ville = magazins.ville
  ) WHERE magazins.region = $default_region 
  AND magazins.activate='1' 
  AND magazins.payer='1' 
  AND magazins.approuve='1' 
  AND magazins.latlan NOT IN ('(999,999)', '(0,0)', '(,)', '') 
  ORDER BY RAND() LIMIT 0,5";
//echo $query_liste_produit;
	$query = mysql_query($query_liste_produit);
	
	
?>


<div style="float:left; width:360px; margin-bottom:25px;">

	<!-- Date Search Even -->
	<div id="menu_date" style="width:317px; float:left; margin-left:16px; margin-bottom:15px;"></div>
    
    <div style="float:left; width:300px; height:400px; margin-right:40px; margin-left:18px;">
    
        <div style="width:300px; height:250px; background:#FFF;">
        
        	<?php 
			function getArticleById($id){
				$query_villes = "SELECT titre FROM article WHERE id_article = $id";
				$villes = mysql_query($query_villes) or die(mysql_error());
				$row_villes = mysql_fetch_assoc($villes);
				return $row_villes['titre'];
			}
			
				$query_pop ="SELECT * FROM article ORDER BY count_article DESC";
				$result_pop = mysql_query($query_pop) or die (mysql_error());
				$row_pop = mysql_fetch_array($result_pop);
			?>
            <div style="width:100%; text-align:center; font-size:24px; font-weight:bold;">BLOG</div>
            <style>
				.post-wrapper{
					/*position:absolute;*/
					margin-left:50px;
				}
            	.post-wrapper a.img-post {
					border: 3px solid #FFFFFF;
					border-radius: 10px 10px 10px 10px;
					box-shadow: 3px 4px 10px #CCCCCC;
					display: block;
					height: 110px;
					margin: 0;
					width: 190px;
					overflow: hidden;
				}
				.post-wrapper a.img-post img {
					background: none repeat scroll 0 0 rgba(0, 0, 0, 0);
					border: medium none;
					height: 110px;
					margin: 0;
					padding: 0;
					width: 190px;
				}
				.post-wrapper .wrap-title {
					margin-left: -10px;
					margin-top: -80px;
					position: absolute;
				}
				.post-wrapper a.title-post {
					background-color: rgba(157, 33, 110, 0.6);
					border-radius: 0 0 10px 0;
					color: #FFFFFF;
					display: block;
					padding-bottom: 3px;
					padding-left: 15px;
					padding-top: 3px;
					text-align: left;
					width: 160px;
				}
				.triangle_left1 {
					background: url("blog/images/arrow.png") no-repeat scroll left bottom rgba(0, 0, 0, 0);
					height: 10px;
					width: 20px;
				}
            </style>
            <?php $namede=str_replace($find,$replace,(getArticleById($row_pop['id_article'])));?>
            <div style="width:100%; float:left; margin:10px 0px;">
                <div class="post-wrapper">
                    <a href="blog/article-<?php echo $row_pop['id_article'];?>-<?php echo $namede;?>.html" class="img-post">
                        <img src="timthumb.php?src=assets/images/blog/<?php echo $row_pop['image'];?>&amp;z=1&amp;w=150&amp;h=100" alt="<?php echo $row_pop['titre'];?>"/>
                    </a>
                    <div class="wrap-title">
                        <a href="blog/article-<?php echo $row_pop['id_article'];?>-<?php echo $namede;?>.html" class="title-post">
                            <?php echo $row_pop['titre'];?>
                        </a>
                        <div class="triangle_left1"></div>
                    </div>
                </div>
            </div>
            <div style="width:280px; float:left; margin:5px 10px;">
            	<a href="blog/article-<?php echo $row_pop['id_article'];?>-<?php echo $namede;?>.html">
                <?php echo $row_pop['excerpt'];?>
                </a>
            </div>
        </div>
        <br /><br />
    
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-0562242258908269";
        /* test */
        google_ad_slot = "2370230299";
        google_ad_width = 300;
        google_ad_height = 250;
        //-->
        </script>
        <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>  
          <br /><br />
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-0562242258908269";
        /* test */
        google_ad_slot = "2370230299";
        google_ad_width = 300;
        google_ad_height = 250;
        //-->
        </script>
        <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
    </div>
	
    
</div>



<div style="float:left; width:570px" class="lister">
<?php while($liste = mysql_fetch_array($query)){ ?>
	<?php
	$finds = array(" ","(",")","é","è","ê","ë","ï","ö","à","Ë","Ê","É","Ä","Â","Ö","Ô","Ï","ô","'",".","/");
	$replaces = array("-","","","e","e","e","e","i","o","a","E","E","E","A","A","O","O","I","o","","","");
	?>
	<?php $nom=str_replace($finds,$replaces,($liste['nom_magazin']));?>
	<?php $nom_region=str_replace($finds,$replaces,(getRegionById($default_region)));?>
	<div class="box" style="position:relative; width:570px;">

		<div class="box_inner" style="width:370px;">
            
            
            <div class="box_img"> 
                <a class="various31" href="md-<?php echo $default_region;?>-<?php echo $nom_region;?>-<?php echo $liste['id_magazin'];?>-<?php echo $nom;?>.html">
                    <img src="timthumb.php?src=assets/images/magasins/<?php echo $liste['photo1']; ?>&amp;w=125&amp;h=90&amp;z=1" alt="<?php echo $liste['nom_magazin']; ?>" />
                </a>
                <span class="boxville"><?php echo ($liste['nom']); ?></span>
            </div>
        
            <div class="box_desc" style="width:205px; float:left; padding:0px;">
            	<span style="font-weight:bold; font-size:26px; text-align:center; width:100%; float:left; color:#9D216E; margin-bottom: 20px;"><?php echo $liste['nom_magazin'];?></span>
                <div class="prix_inner conten_mag" style="float:left;">
                    <p><?php  echo substr($liste['description'],0,100); ?></p>
                    <div class="box_mag" style="margin-left:0px; float:left;">
                        <span class="magazin"><?php echo ($liste['adresse']); ?><br /><?php echo $liste['code_postal']; ?> <?php echo ($liste['nom']); ?></span> 
                    </div>
                </div> 
                <div class="boxtitre" style="float:left; width:100px;"> 
                <a class="various31" href="magasin_detail-<?php echo $default_region;?>-<?php echo $liste['id_magazin'];?>.html">
                <?php if($liste['logo']){ ?> <img src="timthumb.php?src=assets/images/magasins/<?php echo $liste['logo']; ?>&amp;z=1&amp;w=80" alt="<?php echo $liste['nom_magazin']; ?>" /><?php }?></a>
            	</div> 
            </div>
        </div>

        <div class="box_event"> 
            <div class="mag_propose"><?php echo $xml->Ce_magasin_propose; ?></div>
            <div class="box_cpn"><p><a href="md-<?php echo $default_region;?>-<?php echo $nom_region;?>-<?php echo $liste['id_magazin'];?>-<?php echo $nom;?>.html#tabs-5"><?php echo $liste['nb_coupons']; ?> <?php echo $xml->Couponsp; ?> <br /><?php echo $xml-> de_reduction; ?> </a></p></div>
            
            <div class="box_evnt"><p><a href="md-<?php echo $default_region;?>-<?php echo $nom_region;?>-<?php echo $liste['id_magazin'];?>-<?php echo $nom;?>.html#tabs-4"><?php echo $liste['nb_events']; ?> <?php echo $xml->evenement; ?></a></p></div>
            
            <div class="box_propose" style="margin-bottom:12px;"><p><a href="md-<?php echo $default_region;?>-<?php echo $nom_region;?>-<?php echo $liste['id_magazin'];?>-<?php echo $nom;?>.html#tabs-6"><?php echo $liste['nb_produits'];?> <?php if($liste['nb_produits']<=1){echo" Produit";}else{echo" Produits";}?></a></p></div>

            <?php if(isset($_SESSION['kt_login_id'])){ ?> 
                    <a href="javascript:;" style=" background-color:#F6AE30; color:#FFF; font-weight: bold; margin-left: 5px; padding: 7px 30px 7px 31px;" onclick="ajax('ajax/addtofav.php?id_magasin=<?php echo $liste['id_magazin']; ?>','#favoris<?php echo $liste['id_magazin']; ?>');">Ajouter à vos favoris</a>
            <?php } else { ?>
                    <a style=" background-color:#F6AE30; color:#FFF; font-weight: bold; margin-left: 5px; padding: 7px 30px 7px 31px;" href="javascript:;" onclick="alert('Vous devrez être connecté pour vous abonner. Merci de créer un compte si celà n&acute;a pas encore été fait');">Ajouter à vos favoris</a>
            <?php }?><br /><br />

        </div>
        
	</div>
<?php } ?>
	<?php $nom_regions=str_replace($finds,$replaces,(getRegionById($_REQUEST['region'])));?>
	<a href="magasins-<?php echo $nom_regions;?>-<?php echo $_REQUEST['region'];?>-999.html" style="float:right; font-size:15px; text-decoration:underline;">Accéder à tous les Magasins</a>
</div>