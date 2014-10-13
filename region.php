<?php require_once('Connections/magazinducoin.php'); ?>
<?php
if($default_region <= 0) header('Location: index.php');
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_magazinducoin, $magazinducoin);

$query_villes = "SELECT nom_region FROM region WHERE id_region = ".$default_region;
$villes = mysql_query($query_villes) or die(mysql_error());
$row_villes = mysql_fetch_array($villes);
	
	
/*$query_categories = "SELECT cat_id, cat_name FROM category WHERE parent_id = 0 ORDER BY cat_name ASC";
$categories = mysql_query($query_categories, $magazinducoin) or die(mysql_error());

$query_region_pub = "SELECT produits.titre AS titre_produit, pub.titre, pub.region, pub.emplacement, pub.id_produit, pub.date_debut, pub.date_fin, produits.prix, produits.photo1, produits.id_magazin, produits.categorie, produits.sous_categorie FROM (pub LEFT JOIN produits ON produits.id=pub.id_produit)  WHERE pub.region = $default_region and produits.en_stock = 1 and pub.payer = 1 ";
$region_pub = mysql_query($query_region_pub, $magazinducoin) or die(mysql_error());
$totalRows_region_pub = mysql_num_rows($region_pub);
$tous_regionpub = array();
while($row_region_pub = mysql_fetch_assoc($region_pub)){
	$tous_regionpub[$row_region_pub['emplacement']][] = $row_region_pub;
}*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Accueil de Région <?php echo ($row_villes['nom_region']);?> - Magasin Du Coin</title>
    <meta name="description" content="Retrouver dans votre région <?php echo ($row_villes['nom_region']);?> tous les coupons de réductions, bons plans et évènements que vos commerçants et votre centre ville proposent. Ne loupez plus les bonnes affaires" />
    <meta content="Code promo <?php echo ($row_villes['nom_region']);?>, Code promotion <?php echo ($row_villes['nom_region']);?>, Bon plan <?php echo ($row_villes['nom_region']);?>, Bon de réduction <?php echo ($row_villes['nom_region']);?>, Coupon de réduction <?php echo ($row_villes['nom_region']);?>, Bon plan" name="keywords" />

    <?php include("modules/head.php"); ?>
</head>
<body>
<?php include("modules/header.php"); ?>
    <!--End header-->
    <div id="content">

		<?php include("modules/form_recherche_header.php"); ?>
    	<div class="top reduit">
        	<div id="head-menu" style="float:left;">
            	<?php include("assets/menu/main-menu.php"); ?>
            </div>
            <div id="url-menu" style="float:left;">
            <?php include("assets/menu/url_menu.php"); ?>
            </div>
        </div>
        <div class="clear"></div>
        

		<div class="top">
		<script type="text/javascript">
        jQuery(function() {
        
            jQuery("#slideshow1 > div:gt(0)").hide();
            jQuery("#slideshow2 > div:gt(0)").hide();
            jQuery("#slideshow3 > div:gt(0)").hide();
            jQuery("#slideshow4 > div:gt(0)").hide();
        
            setInterval(function() { 
              jQuery('#slideshow1 > div:first')
                .fadeOut(2000)
                .next()
                .fadeIn(2000)
                .end()
                .appendTo('#slideshow1');
            },  5000);
            
            setInterval(function() { 
              jQuery('#slideshow2 > div:first')
                .fadeOut(2000)
                .next()
                .fadeIn(2000)
                .end()
                .appendTo('#slideshow2');
            },  5000);
            
            setInterval(function() { 
              jQuery('#slideshow3 > div:first')
                .fadeOut(2000)
                .next()
                .fadeIn(2000)
                .end()
                .appendTo('#slideshow3');
            },  5000);
            
            setInterval(function() { 
              jQuery('#slideshow4 > div:first')
                .fadeOut(2000)
                .next()
                .fadeIn(2000)
                .end()
                .appendTo('#slideshow4');
            },  5000);
            
        });
        </script> 

<?php
$datetime = date('Y-m-d H:i:s');

$date = date('Y-m-d');
	
$query_Recordset1 = "SELECT DISTINCT 

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

      ON (

        pub.emplacement = pub_emplacement.id

      ) 

  WHERE id_magazin = magazins.id_magazin 

    AND produits.activate = '1' 

    AND pub_emplacement.type = '2' 

    AND pub_emplacement.sub_type = '1' 

    AND pub.payer = '1') AS nb_produits,

  (SELECT 

    COUNT(*) 

  FROM

    coupons 

  WHERE id_magasin = magazins.id_magazin 

    AND coupons.date_fin >= '".$date."' 

    AND coupons.date_debut < '".$date."' 

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

    AND evenements.date_fin >= '".$date."' 

    AND evenements.date_debut < '".$date."') AS nb_events 

FROM

  (

    (

      magazins 

      LEFT JOIN region 

        ON region.id_region = magazins.region

    ) 

    LEFT JOIN maps_ville 

      ON maps_ville.id_ville = magazins.ville

  )

	WHERE magazins.region='".$default_region."' AND magazins.activate='1' AND magazins.payer='1' AND magazins.approuve='1' ORDER BY nb_produits DESC, nb_coupons DESC, nb_events DESC LIMIT 0, 100 ";
	//echo $query_Recordset1;
?>
<?php
$finds = array(" ","(",")","é","è","ê","ë","ï","ö","à","Ë","Ê","É","Ä","Â","Ö","Ô","Ï","ô","'",".","/");
$replaces = array("-","","","e","e","e","e","i","o","a","E","E","E","A","A","O","O","I","o","","","");
?>

<script src="http://maps.googleapis.com/maps/api/js?sensor=false" type="text/javascript"></script>
<script type="text/javascript" src="assets/googlemap/gmap3.js"></script> 

<script type="text/javascript">
        
  $(function(){
  
	$('#map_home').gmap3({
	  map:{
		options:{
		  center:[46.578498,2.457275],
		  zoom: 4
		}
	  },
	  marker:{
		values:[
		<?php
	    $Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die('0'.mysql_error());
		
   		while($coupon = mysql_fetch_assoc($Recordset1)){
		
		$str = str_replace("(", "", $coupon['latlan']);
		$str1 = str_replace(")", "", $str);
		//echo $latlan
		if(($coupon['nb_coupons']=='0') && ($coupon['nb_events']=='0') && ($coupon['nb_produits']=='0')){
			
		}else{
			
		?>
		<?php $nom=str_replace($finds,$replaces,($coupon['nom_magazin']));?>
		<?php $nom_region=str_replace($finds,$replaces,(getRegionById($default_region)));?>
		
			{latLng:[<?php echo $str1;?>], data:"<a href='md-<?php echo $default_region;?>-<?php echo $nom_region;?>-<?php echo $coupon['id_magazin']; ?>-<?php echo $nom;?>.html'><b><?php echo $coupon['nom_magazin'];?></b></a><br /> Adresse: <?php echo $coupon['adresse'];?>, <?php echo $coupon['nom_region'];?>. <br /> <?php if($coupon['nb_coupons']>'0'){echo $coupon['nb_coupons'];?> COUPONS(s) <br /><?php }?><?php if($coupon['nb_events']>0){echo $coupon['nb_events'];?> ÉVÈNEMENTS(s) <br /><?php }?><?php if($coupon['nb_produits']>0){echo $coupon['nb_produits'];?> PRODUITS(s)<?php }?>"},
		  
		<?php } }?>  
		],
		options:{
		  draggable: false
		},
		events:{
		  click: function(marker, event, context){
			var map = $(this).gmap3("get"),
			  infowindow = $(this).gmap3({get:{name:"infowindow"}});
			if (infowindow){
			  infowindow.open(map, marker);
			  infowindow.setContent(context.data);
			} else {
			  $(this).gmap3({
				infowindow:{
				  anchor:marker, 
				  options:{content: context.data}
				}
			  });
			}
		  },
		  closeclick: function(){
			var infowindow = $(this).gmap3({get:{name:"infowindow"}});
			if (infowindow){
			  infowindow.close();
			}
		  }
		}
	  }
	});
  });
</script>

            <div class="recherche_et_pub">
            <h1 style="font-size:5px; color:#F2EFEF; margin:0; padding:0">Accueil de Région <?php echo ($row_villes['nom_region']);?></h1>
                <div style="width:1000px; float:left; margin:0px;">
                
                    
                	 <?php include("modules/coupons_en_avant.php"); ?>
                     <?php include("modules/even_en_avant.php"); ?>
                     <?php include("modules/prod_en_avant.php"); ?>
                     <div style="float:left; margin:8px 0px 8px 8px; ">
                     	<div id="map_home" style="width:240px; height:240px; position:relative;" ></div>
                        <div style="width:200px; height:20px; font-size:15px; color:#FFF; position:absolute; padding:10px 20px; text-align:center; margin-top:-41px;  background:rgba(109,21,76,0.7);">
                        	<a href="map.html" style="color:#FFF;">Voir la carte</a>
                        </div>
                     </div>
             
                </div>
                <div style="width:1000px; padding:10px 0px; float:left; text-align:center; font-size:15px; background:#372f2b;">
                <?php $nom_region=str_replace($finds,$replaces,(getRegionById($_REQUEST['region'])));?>
                    <a href="produits-<?php echo $nom_region;?>-<?php echo $_REQUEST['region'];?>.html" style="width:330px; float:left; border-right:1px solid; color:#a4a4a4;">Accéder à tous les produits</a>
                    <a href="coupons-<?php echo $nom_region;?>-<?php echo $_REQUEST['region'];?>.html" style="width:330px; float:left; border-right:1px solid; color:#a4a4a4;">Accéder à tous les coupons</a>
                    <a href="evenements-<?php echo $nom_region;?>-<?php echo $_REQUEST['region'];?>.html" style="width:330px; float:left; color:#a4a4a4;">Accéder à tous les évènements</a>
                </div>
            </div>
            
            
            
		</div>
		<!--  End Top -->
		<div class="clear"></div>
        <style>
        	.page-content{
				width:1000px; 
				float:left;
			}
			.page-content .page-item{
				width:690px; 
				min-height: 1100px;
				float:left;
			}
			.page-item .content-itme{
				width:670px; 
				height:220px; 
				float:left; 
				background:#cbcbcb; 
				margin: 8px 0px 0px 8px;
				-webkit-border-radius: 5px 5px 5px 5px;
				border-radius: 5px 5px 5px 5px;
				-webkit-box-shadow: 0 2px 1px 0 #8e8d8d;
				box-shadow: 0 2px 1px 0 #8e8d8d;
			}
			.content-itme .itme-left{
				width:450px;
				height:220px;
				float:left;
			}
			.itme-left .ville{
				width:450px;
				height:35px;
				float:left;
				margin:10px 0px;
			}
			.ville .ville_title1{
				padding:9px 15px; 
				float:left; 
				font-size:14.5px; 
				background:#353535; 
				color:#FFF;
				text-transform:uppercase;
			}
			.ville .ville_title2{
				padding:6px 15px; 
				float:left; 
				font-size:18px;
				text-transform:uppercase;
			}
			
			
			.itme-left .item_descript{
				width:395px; 
				height:75px; 
				float:left; 
				background:#FFF;
				-webkit-border-radius: 5px 5px 5px 5px;
				border-radius: 5px 5px 5px 5px;
				margin:0px 12px;
				padding:15px;
				color:#353535;
			}
			
			.itme-left .item_link{
				width:425px;  
				float:left; 
				margin:10px 12px;
				font-size:11px;
				color:#353535;
				-webkit-box-shadow: 0 2px 1px 0 #8e8d8d;
				box-shadow: 0 2px 1px 0 #8e8d8d;
				-webkit-border-radius: 5px 5px 5px 5px;
				border-radius: 5px 5px 5px 5px;
			}
			.item_link a{
				width:141.5px;
				float:left;
				text-align:center;
				padding:12px 0px;
				color:#FFF;
				font-weight:bold;
			}
			.item_link a:first-child{
				-webkit-border-radius: 5px 0px 0px 5px;
				border-radius: 5px 0px 0px 5px;
			}
			.item_link a:last-child{
				-webkit-border-radius: 0px 5px 5px 0px;
				border-radius: 0px 5px 5px 0px;
			}
			
			
			.content-itme .itme-right{
				width:220px;
				height:220px;
				float:left;
				background:#666;
				-webkit-border-radius: 0px 5px 5px 0px;
				border-radius: 0px 5px 5px 0px;
			}
			.itme-right a{
				padding:11px 20px; 
				padding:10px 20px\9; 
				margin:170px 0px 0px 24px; 
				margin:170px 0px 0px 20px\9;
				background-color:#9d216e; 
				float:left;
				-webkit-border-radius: 5px 5px 5px 5px;
				border-radius: 5px 5px 5px 5px;
				font-size:13px;
				color:#FFF;
				-webkit-box-shadow: 0 2px 1px 0 #272727;
				box-shadow: 0 2px 1px 0 #272727;
			}
			
			.page-content .sidebar-right{
				width:310px; 
				height:350px;
				float:left; 
			}
			#magazin_link{
				padding:10px 15px; 
				margin:8px;
				float:left;
				background:#535353;
				color:#FFF;
				-webkit-border-radius: 5px 5px 5px 5px;
				border-radius: 5px 5px 5px 5px;
				font-size:14px;
			}
        </style>
        
        
        <div class="page-content">
        	
            <!--  START PAGE ITME -->
        	<div class="page-item">
            <?php
				$now = date('Y-m-d H:i:s');
				$datetime = date('Y-m-d H:i:s');
				$date = date('Y-m-d');
				/*$query_magazin="SELECT 
								  magazins.*,
								  maps_ville.nom,
								  region.nom_region,
								  magazins.description AS des 
								FROM
								  (
									magazins 
									INNER JOIN region 
									  ON region.id_region = magazins.region 
									INNER JOIN departement 
									  ON departement.code = magazins.department 
									INNER JOIN maps_ville 
									  ON maps_ville.id_ville = magazins.ville
								  ) 
								WHERE magazins.region = '".$default_region."' 
								  AND magazins.activate = '1' 
								  AND magazins.payer = '1' 
								  AND magazins.en_avant = '1' 
								  AND magazins.en_avant_payer = '1' 
								  AND magazins.en_avant_fin >= '".$now."' 
								  AND DATE_ADD(
									en_avant_fin,
									INTERVAL -day_en_avant DAY
								  ) <= '".$now."'
								  AND (
									magazins.approuve = '1' 
									OR (
									  magazins.approuve = 0 
									  AND magazins.public = 1 
									  AND magazins.public_start < '".$now."' 
									  AND (
										magazins.public_start + INTERVAL 20 MINUTE
									  ) < '".$now."'
									)
								  ) 
								ORDER BY RAND() 
								LIMIT 0, 5 ";*/
				$query_magazin="SELECT 
								  magazins.*,
								  maps_ville.nom,
								  region.nom_region,
								  magazins.description AS des 
								FROM
								  (
									magazins 
									INNER JOIN region 
									  ON region.id_region = magazins.region 
									INNER JOIN departement 
									  ON departement.code = magazins.department 
									INNER JOIN maps_ville 
									  ON maps_ville.id_ville = magazins.ville
								  ) 
								WHERE magazins.region = '".$default_region."' 
								  AND magazins.activate = '1' 
								  AND magazins.payer = '1'  
								  AND magazins.approuve = '1' 
								ORDER BY RAND() 
								LIMIT 0, 5 ";				
								//echo $query_magazin;
				$sql_magazin= mysql_query($query_magazin);		
				while($row_magazin = mysql_fetch_array($sql_magazin)){ ?>
                <?php $nom=str_replace($finds,$replaces,($row_magazin['nom_magazin']));?>
                <?php $nom_region=str_replace($finds,$replaces,(getRegionById($default_region)));?>
            	<div class="content-itme">
                	<!--  START ITME LEFT -->
                    <div class="itme-left">
                    	<div class="ville">
                        	<div class="ville_title1">
                                <?php echo (substr($row_magazin['nom'],0,13));?>
                            </div>
                            <div class="ville_title2">
                                <a href="md-<?php echo $default_region;?>-<?php echo $nom_region;?>-<?php echo $row_magazin['id_magazin'];?>-<?php echo $nom;?>.html"><?php echo substr($row_magazin['nom_magazin'],0,23);?></a>
                            </div>
                        </div>
                        
                        <div class="item_descript">
                        	<div style="width:290px; float:left; font-size:13px;">
                            	<?php echo substr($row_magazin['des'],0,60);?>
                            </div>
                            <div style="width:100px; height:75px; float:right; display: table;">
                            	<span style="vertical-align:middle; display: table-cell; text-align:right; font-size:13px;">
                                	<?php echo $row_magazin['adresse'];?><br />
                                    <?php echo $row_magazin['code_postal'];?> <?php echo $row_magazin['nom'];?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="item_link">
                        	
                        	<a href="md-<?php echo $default_region;?>-<?php echo $nom_region;?>-<?php echo $row_magazin['id_magazin'];?>-<?php echo $nom;?>.html#tabs-5" style="background-color:#f49c00;">
							<?php echo count_coupon($row_magazin['id_magazin'],$default_region); ?> <?php echo $xml->coupons_reduction ; ?></a>
                            
                        	<a href="md-<?php echo $default_region;?>-<?php echo $nom_region;?>-<?php echo $row_magazin['id_magazin'];?>-<?php echo $nom;?>.html#tabs-4" style="background-color:#9d216e;">
							<?php echo count_event($row_magazin['id_magazin'],$default_region); ?> <?php echo $xml->evenement ?></a>
                            
                        	<a href="md-<?php echo $default_region;?>-<?php echo $nom_region;?>-<?php echo $row_magazin['id_magazin'];?>-<?php echo $nom;?>.html#tabs-6" style="background-color:#b45f93;">
							<?php echo count_product($row_magazin['id_magazin'],$default_region);?> <?php if(count_product($row_magazin['id_magazin'],$default_region)<=1){echo $xml->produit;}else{echo $xml->produits;}?></a>
                        </div>
                    </div>
                    
                    <!--  START ITME RIGHT -->
                    <div class="itme-right" style="background-size:100% 100%; <?php if($row_magazin['logo']){?> background:url(assets/images/magasins/<?php echo $row_magazin['logo']; ?>);<?php }else{?> background:url(assets/images/def.png);<?php }?> ">
                    	<?php if(isset($_SESSION['kt_login_id'])){ ?> 
                        	<a href="javascript:;" onclick="ajax('ajax/addtofav.php?id_magasin=<?php echo $liste['id_magazin']; ?>','#favoris<?php echo $liste['id_magazin']; ?>');" style="float:left; display: table;"><span style="vertical-align:middle; display: table-cell; float:left; width:139px; width:139px\9;">Ajouter à vos favoris <img src="assets/images/star.png" alt="" style="margin-bottom:-2px;"/></span></a>
						<?php } else { ?>
                        	<a href="javascript:;" onclick="alert('Vous devrez être connecté pour vous abonner. Merci de créer un compte si celà n&acute;a pas encore été fait');" style="float:left; display: table;"><span style="vertical-align:middle; display: table-cell; float:left; width:139px; width:139px\9;">Ajouter à vos favoris <img src="assets/images/star.png" alt="" style="margin-bottom:-2px;"/></span></a>
						<?php }?>
                        </div>
                </div>
                <?php }?>
                <a href="magasins-<?php echo $nom_region;?>-<?php echo $_REQUEST['region'];?>.html" id="magazin_link">Accéder à tous les magasins</a>
            </div>
            <!--  END PAGE ITME -->
            
            <!--  START SIDEBAR RIGHT -->
            <div class="sidebar-right">
            	<div id="menu_date" style="width:300px; float:left; margin-top:10px; margin-bottom:15px;">
                    <link rel="stylesheet" href="assets/themes/base/jquery.ui.datepicker.css">
                    <link rel="stylesheet" href="assets/themes/base/jquery.ui.core.css">
                    <script src="assets/ui/jquery.ui.datepicker.js"></script>
                    <script src="assets/ui/jquery.ui.datepicker-fr.js"></script>
                    <script>
                        $(function() {
                            $('#datepicker').datepicker({
                                onSelect: function(dateText, inst) { 
                                    var even = $("#even").val();
                                    var region = $("#regions").val();
                                    var date = $(this).datepicker('getDate'),
                                    day  = date.getDate(),  
                                    month = date.getMonth() + 1,              
                                    year =  date.getFullYear();
                                    window.location = 'evenement-<?php echo $nom_region;?>-'+region+'-'+even+'-'+day+'-'+month+'-'+year+'-day.html';
                                    
                                }
                            });
                        });
                    </script>
                    <style>
					.ui-datepicker{
						padding:0px;
					}
					</style>
                    <div style="font-size:16px; margin:0px 5px 8px 5px; font-weight:bold; text-align:center; width:95%;">Calendrier des évènements</div>
                    <?php
                    $sqlcate = "SELECT cat_id, parent_id, cat_name FROM category WHERE parent_id='0' AND type='2' ORDER BY cat_name ASC";
                    $resultcate = mysql_query($sqlcate) or die (mysql_error());
                    ?>
                    <select style="width:300px;" id="even">
                    <option value="tout">Toutes les catégories d'évènement</option>
                    <?php while ($querycate=mysql_fetch_array($resultcate)){?>
                    <option value="<?php echo $querycate['cat_id']; ?>"><?php echo $querycate['cat_name']; ?></option>
                    <?php }?>
                    </select>
                    <input type="hidden" id="regions" value="<?php echo $_REQUEST['region'];?>" />
                    <div id="datepicker"></div>
                
                
                </div>
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
                <style>
					.blog-home{
						width:270px;  
						float:left;
						background:#FFF;
						padding:15px;
						-webkit-border-radius: 5px 5px 5px 5px;
						border-radius: 5px 5px 5px 5px;
						-webkit-box-shadow: 0 2px 1px 0 #8e8d8d;
						box-shadow: 0 2px 1px 0 #8e8d8d;
					}
                </style>
                <div class="blog-home">
                	<a href="blog/article-<?php echo $row_pop['id_article'];?>-<?php echo $namede;?>.html">
                        <img src="timthumb.php?src=assets/images/blog/<?php echo $row_pop['image'];?>&amp;z=1&amp;w=270&amp;h=200" alt="<?php echo $row_pop['titre'];?>"/>
                        <span style="font-size:20px; color:#9d216e;">BLOG</span><br />
                        <span style="font-size:12px;"><?php echo $row_pop['titre'];?></span>
                    </a>
                </div>
                <div class="clear"></div>
                <br />
                <script type="text/javascript"><!--
				google_ad_client = "ca-pub-0562242258908269";
				google_ad_slot = "2370230299";
				google_ad_width = 300;
				google_ad_height = 250;
				//-->
				</script>
                
				<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>  
				  <br /><br />
				<script type="text/javascript"><!--
				google_ad_client = "ca-pub-0562242258908269";
				google_ad_slot = "2370230299";
				google_ad_width = 300;
				google_ad_height = 250;
				//-->
				</script>
				<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
                
                
            </div>
            <!--  END SIDEBAR RIGHT -->
        </div>
        
    </div>
    <!--End Content-->
    <div id="footer">
    	<div class="recherche">
        &nbsp;
        </div>
        <?php include("modules/footer.php"); ?>
    </div>
</body>
</html>
<?php
//mysql_free_result($categories);
?>

