<?php require_once('Connections/magazinducoin.php'); ?>
<?php $datetime = date('Y-m-d H:i:s');
	$date = date('Y-m-d');?>
<?php
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
$now = date('Y-m-d H:i:s');
$colname_Recordset1 = "-1";
if (isset($_GET['cat_id'])) {
  $colname_Recordset1 = $_GET['cat_id'];
}
mysql_select_db($database_magazinducoin, $magazinducoin);
$categorie=$_GET['cat_id'];
$magazin=$_GET['mag_id'];
if(isset($_GET['id'])){
	$produit = $_GET['id'];

	$sql = "UPDATE produits SET vue = vue+1 WHERE id = ".$produit;
	if(isset($_SESSION['kt_login_id'])) $sql .= " AND id_user != ".$_SESSION['kt_login_id'];
	mysql_query($sql, $magazinducoin) or die(mysql_error());

	$query_Recordset1 = sprintf("SELECT produits.photo1, produits.id, category.cat_id, magazins.id_magazin from produits,magazins,category where produits.categorie=category.cat_id and produits.id_magazin=magazins.id_magazin and produits.categorie=$categorie and produits.id_magazin=$magazin and produits.id <> $produit");
	$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());

	$totalRows_Recordset1 = mysql_num_rows($Recordset1);

	$colname_liste_produits = "-1";
	if (isset($_GET['id'])) {
	  $colname_liste_produits = $_GET['id'];
	}

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_liste_produits = sprintf("SELECT magazins.*, magazins.photo1 AS photo1_mag, magazins.photo2 AS photo2_mag, magazins.photo3 AS photo3_mag, category.cat_id, category.cat_name, produits.prix, produits.prix2, produits.reduction, produits.titre, produits.id, produits.reference, produits.en_stock, produits.photo1, produits.description, magazins.nom_magazin ,magazins.logo, magazins.siren, magazins.adresse, produits.photo2, maps_ville.nom, maps_ville.cp, magazins.code_postal, produits.photo3, magazins.latlan, magazins.description AS description_mag, magazins.heure_ouverture, magazins.jours_ouverture, produits.categorie, magazins.id_magazin, (SELECT COUNT(*) FROM produits WHERE id_magazin = magazins.id_magazin) AS nb_produits
,(SELECT 
    email
  FROM
    utilisateur 
  WHERE magazins.id_user= utilisateur.id ) AS email
  FROM (((produits
LEFT JOIN category ON category.cat_id=produits.sous_categorie)
LEFT JOIN magazins ON magazins.id_magazin=produits.id_magazin)
LEFT JOIN maps_ville ON maps_ville.id_ville=magazins.ville) WHERE id = %s", GetSQLValueString($colname_liste_produits, "int"));
$liste_produits = mysql_query($query_liste_produits, $magazinducoin) or die(mysql_error());
$row_liste_produits = mysql_fetch_assoc($liste_produits);
$totalRows_liste_produits = mysql_num_rows($liste_produits);
//echo $query_liste_produits;
$id_magasin = $row_liste_produits['id_magazin'];
}	


if(!$id_magasin) {
	$id_magasin = $_GET['mag_id'];
	mysql_select_db($database_magazinducoin, $magazinducoin);
$query_liste_produits = sprintf("SELECT magazins.*, magazins.date_mor, magazins.date_eve, magazins.photo1 AS photo1_mag, magazins.photo2 AS photo2_mag, magazins.photo3 AS photo3_mag, magazins.nom_magazin ,magazins.logo, magazins.siren, magazins.adresse, maps_ville.nom, magazins.code_postal, magazins.latlan, magazins.description AS description_mag, magazins.heure_ouverture, magazins.jours_ouverture, magazins.id_magazin, (SELECT COUNT(*) FROM produits WHERE id_magazin = $id_magasin) AS nb_produits,
  (SELECT 
    email
  FROM
    utilisateur 
  WHERE magazins.id_user= utilisateur.id ) AS email
FROM magazins LEFT JOIN maps_ville ON maps_ville.id_ville=magazins.ville WHERE magazins.id_magazin = %s", $id_magasin);
//die($query_liste_produits);
//echo $query_liste_produits;
$liste_produits = mysql_query($query_liste_produits, $magazinducoin) or die(mysql_error());
$row_liste_produits = mysql_fetch_assoc($liste_produits);
$totalRows_liste_produits = mysql_num_rows($liste_produits);

}

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_produits = "SELECT 
				  produits.id
				FROM
				  produits 
				  Inner JOIN magazins 
					ON magazins.id_magazin = produits.id_magazin 
				  INNER JOIN region 
					ON region.id_region = magazins.region 
				  INNER JOIN departement 
					ON departement.code = magazins.department 
				  INNER JOIN maps_ville 
					ON maps_ville.id_ville = magazins.ville 
				WHERE (
					magazins.region = '".$default_region."'
					AND magazins.id_magazin = '".$_GET['mag_id']."'
					AND produits.activate = '1' 
					OR (
					  magazins.region = '".$default_region."' 
					  AND magazins.id_magazin = '".$_GET['mag_id']."' 
					  AND produits.activate = 0 
					  AND produits.public = 1 
					  AND produits.public_start < '".$datetime."' 
					  AND (
						produits.public_start + INTERVAL 20 MINUTE
					  ) < '".$datetime."'
					)
				  )";
$produits = mysql_query($query_produits, $magazinducoin) or die(mysql_error());
$row_produits = mysql_fetch_assoc($produits);
$totalRows_produits = mysql_num_rows($produits);
//echo $query_produits;
mysql_select_db($database_magazinducoin, $magazinducoin);
$query_coupons = "SELECT 
				  coupons.id_coupon 
				FROM
				  coupons 
				  INNER JOIN magazins 
					ON magazins.id_magazin = coupons.id_magasin 
				  INNER JOIN category 
					ON category.cat_id = coupons.categories 
				  INNER JOIN region 
					ON region.id_region = magazins.region 
				  INNER JOIN departement 
					ON departement.code = magazins.department 
				  INNER JOIN maps_ville 
					ON maps_ville.id_ville = magazins.ville 
				WHERE (
					(
					  coupons.en_tete_liste_payer = 1 
					  AND coupons.en_tete_liste = 1 
					  AND coupons.approuve = 0 
					  AND coupons.public = 0 
					  AND DATE_ADD(
						coupons.date_debut,
						INTERVAL - coupons.day_en_tete_liste DAY
					  ) = '".$date."'  
					  AND date_debut >= '".$date."' 
					) 
					OR (
					  coupons.approuve = '1' 
					  AND coupons.date_fin >= '".$date."'  
					  AND coupons.date_debut <= '".$date."' 
					) 
					OR (
					  coupons.approuve = 0 
					  AND coupons.public = 1 
					  AND coupons.date_fin >= '".$date."'  
					  AND coupons.date_debut <= '".$date."'  
					  AND coupons.public_start < '".$datetime."' 
					  AND (
						coupons.public_start + INTERVAL 20 MINUTE
					  ) < '".$datetime."'
					)
				  ) 
				  AND coupons.payer = 1 
				  AND coupons.active = 1  
				  AND region.id_region = '".$default_region."'
				  AND coupons.id_magasin = '".$_GET['mag_id']."'";
$coupons = mysql_query($query_coupons, $magazinducoin) or die(mysql_error());
$row_coupons = mysql_fetch_assoc($coupons);
$totalRows_coupons = mysql_num_rows($coupons);
//echo $query_coupons;

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_evenements = "SELECT 
						evenements.event_id  
					FROM
					  evenements 
					  INNER JOIN magazins 
						ON magazins.id_magazin = evenements.id_magazin 
					  INNER JOIN category 
						ON category.cat_id = evenements.category_id 
					  INNER JOIN region 
						ON region.id_region = magazins.region 
					  INNER JOIN departement 
						ON departement.code = magazins.department 
					  INNER JOIN maps_ville 
						ON maps_ville.id_ville = magazins.ville 
					WHERE (
						(
						  evenements.en_tete_liste_payer = 1 
						  AND evenements.en_tete_liste = 1 
						  AND evenements.approuve = 0 
						  AND evenements.public = 0 
						  AND DATE_ADD(
							evenements.date_debut,
							INTERVAL - evenements.day_en_tete_liste DAY
						  ) = '".$date."'  
						  AND date_debut >= '".$date."' 
						) 
						OR (
						  evenements.approuve = '1' 
						  AND evenements.date_fin >= '".$date."'  
						  AND evenements.date_debut <= '".$date."' 
						) 
						OR (
						  evenements.approuve = 0 
						  AND evenements.public = 1 
						  AND evenements.date_fin >= '".$date."'  
						  AND evenements.date_debut <= '".$date."'  
						  AND evenements.public_start < '".$now."' 
						  AND (
							evenements.public_start + INTERVAL 20 MINUTE
						  ) < '".$now."'
						)
					  ) 
					  AND evenements.payer = 1 
					  AND evenements.active = 1 
					  AND region.id_region = '".$default_region."'
					  AND evenements.id_magazin = '".$_GET['mag_id']."'";
$evenements = mysql_query($query_evenements, $magazinducoin) or die(mysql_error());
$row_evenements = mysql_fetch_assoc($evenements);
$totalRows_evenements = mysql_num_rows($evenements);
//echo $query_evenements;
 ?>
<?php //if($default_region == 0) header('Location: index.php');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"  xmlns:fb="http://ogp.me/ns/fb#"> 
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 
<?php 
    $detail=mysql_fetch_array(mysql_query("select * from magazins where id_magazin='".$_GET['mag_id']."'"));
?>
        
<meta property="og:title" content="<?php echo $detail['nom_magazin'];?>" /> 
<meta property="og:description" content="<?php echo $detail['description'];?>" />  
<meta property="og:image" content="http://magasinducoin.fr/assets/images/magasins/<?php echo $detail['photo1'];?>" /> 
<meta property="og:video" content="" /> 
<meta property="og:video:width" content="" />  
<meta property="og:video:height" content="" />  
<meta property="og:video:type" content="application/x-shockwave-flash" /> 

<title><?php echo $detail['nom_magazin'];?></title>
    
    <script src="http://yandex.st/jquery/2.0.3/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="assets/social/social-likes.css">
	<script src="assets/social/src/social-likes.js" type="text/javascript"></script>

    
    <?php include("modules/head-detail.php"); ?>

<!--<link type="text/css" rel="stylesheet" href="template/css/style.css" />-->
<link type="text/css" rel="stylesheet" href="stylesheets/onglet.css" />
<link rel="stylesheet" type="text/css" href="scroller/imageScroller.css">

<script src="assets/zoom/magiczoom.js" type="text/javascript"></script>
<link href="assets/zoom/magiczoom.css" rel="stylesheet" type="text/css" media="screen"/>
<link href="assets/zoom/style.css" rel="stylesheet" type="text/css" media="screen"/>
<script type="text/javascript" src="scroller/scroller.js"></script>
</head>
<script type="text/javascript">
			function ajax(murl,mresult){
				$(mresult).addClass("en_cours");
				$.ajax({
				  url: murl,
				  cache: false,
				  success: function(html){
					$(mresult).html(html);
					$(mresult).removeClass("en_cours");
				  }
				});
			}
			function actualiser_cart(murl){
				$.ajax({
				  url: murl,
				  cache: false,
				  success: function(html){
					parent.$('#cart').html(html);
				  }
				});
			}
			function mapclick(){
					//setTimeout("initialize()",500);
					alert("test");
			}
</script>
<!-- fin de script concernant les onglets  -->
<script type="text/javascript">  
 function changeCouleur(nouvelleCouleur,parametremap) {  
   elem = document.getElementById("bordure");  
   elem.style.backgroundColor = nouvelleCouleur;
   if(parametremap =="1"){
  	setTimeout("initialize()",500);
   }
 } 
  
</script>
<?php 
	$map=$row_liste_produits['latlan'];
	$map=str_replace('(','',$map);
	$map=str_replace(')','',$map);
	$tab = explode(',', $map);
	$adresse = $row_liste_produits['adresse'].' '.$row_liste_produits['nom'].', '.$row_liste_produits['code_postal'];
	//echo $tab[1];
	$v ='';
	if($row_liste_produits['latlan']){
	$v = $map;
	}else{
	$v = $adresse;
	}
?>

<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=true&amp;key=ABQIAAAAH98Kqhz5RYb4hjqrVA6qhhRY2Dx3LGuleOdB77j29-vf3RDfbRTA5YX2stuicNxmZsqJF8BqoYAbFg" type="text/javascript"></script>

<script type="text/javascript" src="assets/googlemap/gps.jquery.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$("#getdirections").click(function() {
		$('#directions').html('');
	});
	$("#map").googleMap({
		zoomLevel: 15,  
		image: myIcon , 
		center: '<?php echo $v;?>' ,
		showTooltip: true,
		tooltip: '<strong><?php echo $row_liste_produits['nom_magazin']; ?></strong><br /><?php echo $row_liste_produits['adresse']; ?><br /><?php echo $row_liste_produits['nom']; ?> <?php echo $row_liste_produits['code_postal']; ?>' 
	}).load();
});
</script>
<style>
    #map {
        width: 1000px;
        height: 400px;
    }
</style>

<script>
jQuery(document).ready(function(){
	$( "#abonner_alert" ).click(function() {
		alert("Vous devrez être connecté pour vous abonner. Merci de créer un compte si celà n'a pas encore été fait");
	});
	$( "#fav_alert" ).click(function() {
		alert("Vous devrez être connecté pour vous abonner. Merci de créer un compte si celà n'a pas encore été fait");
	});
});
</script>

<style type="text/css">
#map_canvas {
	height: 450px;
	width:900px;
}
</style>

   <style>
		#abonner{
			border: none;
			background: #9d216e;
			padding: 5px 10px;
			color: #FFF;
			font-weight:bold;
			cursor:pointer;
		}
		#fav{
			border: none;
			background: #9d216e;
			padding: 5px 10px;
			color: #FFF;
			font-weight:bold;
			cursor:pointer;
		}
		#resultForm{
			text-align:center;
		}
	</style>
<style><?php $tabs=$_GET['t'];?>
#bordure {
  background-color:#<?php if($tabs==2) {echo"9d216e";}
 						 if($tabs==3) {echo"f6ae30";}
						  if($tabs==4) {echo"b35a91";}
						  if($tabs==5) {echo"ed8427";}
						  if($tabs==6) {echo"8599ff";}
						 
					?>;
}
.popup #slogan {
    color:#<?php if($tabs==2) {echo"9d216e";}
 						 if($tabs==3) {echo"f6ae30";}
						  if($tabs==4) {echo"b35a91";}
						  if($tabs==5) {echo"ed8427";}
						  if($tabs==6) {echo"8599ff";}
						  
					?>;
}
</style>

<link rel="stylesheet" href="assets/colorbox/colorbox.css" />
<script src="assets/colorbox/jquery.colorbox.js"></script>
<script>
	$(document).ready(function(){
		$(".ajax").colorbox();
	});
</script>
<script>
jQuery(document).ready(function(){
	$( "#mapc" ).click(function() {
		$('#directions').html('');
		$('#gps_map').animate({width: '340px', padding:'5px'},1500);
		$('#map_p').hide();
		$('#map_n').show();
		$('#gps_map').animate({width: '0px', padding:'0px'},2000);
		$('#map_n').hide();
		$('#map_p').show();
	});
});
$( document ).ready(function() {
	var id_mag = <?php echo $row_liste_produits['id_magazin'];?>;
	var region = <?php echo $default_region;?>;
	var dataString = 'id_mag='+id_mag+'&region='+region;
	$.ajax({
			type: "POST",
			url: "assets/owner_shoper/shop_products.php",
			data: dataString,
			cache: false,
			success: function(datas){
				$("#shop_products").html(datas);
			}
		});	
	return false;
});
</script>

</head>

<body id="sp" onLoad="ajax('ajax/resultat_detail.php?mag_id=<?php echo $_GET['mag_id'];?>&amp;region=<?php echo $default_region;?>','#result'),  ajax('ajax/resultat_detail_cpn.php?mag_id=<?php echo $_GET['mag_id'];?>&amp;region=<?php echo $default_region;?>','#re_coupon'), ajax('ajax/resultat_detail_eve.php?mag_id=<?php echo $_GET['mag_id'];?>&amp;region=<?php echo $default_region;?>','#re_event')">

<?php include("modules/header.php"); ?>

<script type="text/javascript" src="assets/popup/pop.js"></script>
<link type="text/css" rel="stylesheet" href="assets/popup/popup.css" />
<!--css popup window 1-->
<div style="display: none;" id="blanket"></div>
<div style="display: none;" id="popUpDiv">
<style>
.btn{
	border: 0px;
	font-size: 12px;
	font-weight: bold;
	background-color: #9D286E;
	color: #FF9;
	padding: 5px 10px;
}
</style>
	
    <a onclick="popup('popUpDiv')" href="#"><img alt="" src="assets/popup/close.jpg" /></a><br />
    <p class="popup">Êtes-vous le propriétaire de magasin?</p>
    <p class="popup">
    <label>Siren <span style="color:red;">*</span> : </label><input type="text" id="siren" />
    <input type="button" class="btn" value="Validate" />
    </p>
</div>
<!--css popup window 1 close-->

<div id="content">
	<style>
    /*#formHaut{
		margin-top:-11px;
	}*/
    </style>
    <?php //include("modules/form_recherche_header.php"); ?>
    <div class="top reduit">
        <div id="head-menu" style="float:left;">
        	<?php include("assets/menu/main-menu.php"); ?>
        </div>
		<div id="url-menu" style="float:left;">
        <?php include("assets/menu/url_menu.php"); ?>
        </div>
    </div>
    
    <div class="clear"></div>

    <div id="content" class="lister top popup" style="float:left; background:#F2EFEF; width:100%;">

		
            
            <style>
			.popup #tabs ul{
				/*padding-left:165px;*/ 
			}
            .content_right{
				float:left;
				width:1000px; 
			}
			.content_body{
				width:100%;
				float:left;
			}
			.content_right #tabs{
				float:left;
			}
			.content_body #tabs ul{
				width: 990px;
				float: left;
				background-color: #cbcbcb;
				padding: 5px;
			}
			.content_body #tabs ul li{
				
			}
			.content_body #tabs ul li a{
				list-style: none;
				float: left;
				cursor: pointer;
				font-weight: bold;
				font-size: 15px;
				padding: 7px 7px;
				margin-top: 1px;
				color:#353535;
			}
			.content_body #tabs ul li.ui-state-active a{
				color:#353535;
				background:#f2efef;
				-webkit-border-radius: 5px 5px 5px 5px;
				border-radius: 5px 5px 5px 5px;
			}
			.content_body #tabs ul li.tab_s, .content_body #tabs ul li.tab_s2{
				float:right;
				margin-left:5px;
			}
			.content_body #tabs ul li.tab_s a, .content_body #tabs ul li.tab_s2 span{
				display: table;
				float: left;
				color:#FFF;
				background:#9d216e;
				font-weight: bold;
				font-size: 13px;
				padding: 7px 7px;
				margin-top: 2.5px;
				-webkit-border-radius: 5px 5px 5px 5px;
				border-radius: 5px 5px 5px 5px;
				cursor:pointer;
			}
            </style>
        
		<div class="content_body">
        	<div style="width:1000px; height:38px; float:left; padding:10px 0px;">
            	<div style="float:left; padding:10px 15px; background-color:#3f3e3e; color:#FFF; font-size:14.5px;"><?php echo $row_liste_produits['nom'];?></div>
                <div style="float:left; padding:1px 15px; color:#353535; font-size:28px;"><?php echo $row_liste_produits['nom_magazin'];?></div>
            </div>
            
                <?php $nom=str_replace($finds,$replaces,($row_liste_produits['nom_magazin']));?>
				<?php $nom_pro=str_replace($finds,$replaces,($row_liste_produits['titre']));?>
                <?php $nom_region=str_replace($finds,$replaces,(getRegionById($default_region)));?>
                <script>
				jQuery(document).ready(function(){
					$( "#favori" ).click(function() {
						var id_mag=<?php echo $id_magasin;?>;
						var id=<?php echo $_SESSION['kt_login_id'];?>;
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
					
					$( "#abonne" ).click(function() {
						var id_mag=<?php echo $id_magasin;?>;
						var id=<?php echo $_SESSION['kt_login_id'];?>;
						var dataString = 'id_mag='+id_mag+'&id='+id;
						$.ajax({
								type: "POST",
								url: "ajax/sabonner.php",
								data: dataString,
								cache: false,
								success: function(datas){
									$(".head_abonne").html(datas);
								}
							});	
						return false;
					});					
				});
                </script>
            <div id="tabs">
				<ul >
                	<?php if($_GET['id']>0){ ?>
                    <li id="des"><a href="#tabs-1"><?php echo $xml->Description; ?></a></li>
					<?php } ?>
                    
					<li id="mag"><a href="#tabs-2"><?php echo $xml->Magasin ;?></a></li>
					<li id="mapc"><a href="#tabs-3"><?php echo $xml->Map; ?></a></li>
                    
                    <?php if($totalRows_produits!='0'){?>
                    <li id="pro"><a href="#tabs-6">Produits</a></li>
                    <?php }?>
                    <?php if($totalRows_coupons!='0'){?>
					<li id="reduc"><a href="#tabs-5"><?php echo $xml->Coupons_reduction; ?></a></li>
                    <?php }?>
                    <?php if($totalRows_evenements!='0'){?>
					<li id="event"><a href="#tabs-4"><?php echo $xml->Evenements; ?></a></li>
                    <?php }?>
                    
                    <?php if($_SESSION['kt_login_id']){ ?>
                    <li class="tab_s2 head_favori">
                    <?php
						$query_favar = "SELECT
											favoris.id
											, favoris.id_user
											, favoris.id_magasin
										FROM
											favoris
											INNER JOIN magazins 
												ON (favoris.id_magasin = magazins.id_magazin)
											INNER JOIN utilisateur 
												ON (favoris.id_user = utilisateur.id) 
										WHERE magazins.id_magazin='".$_GET['mag_id']."' and utilisateur.id='".$_SESSION['kt_login_id']."'";
						$favar = mysql_query($query_favar, $magazinducoin) or die(mysql_error());
						//echo $query_newsletter;
						$row_favar = mysql_fetch_array($favar);
						?>
							<?php if(!isset($row_favar['id'])){?>
                                <span id="favori" style="vertical-align:middle; display: table-cell; float:left;">Ajouter à vos favoris <img src="assets/images/star.png" alt="" style="margin-bottom: -2px;" /></span>
							<?php }else{?>
                                <span id="favori" style="vertical-align:middle; display: table-cell; float:left;">Enlever Favoris <img src="assets/images/star.png" alt="" style="margin-bottom: -2px;" /></span>
							<?php }?>
                    </li>
                    <?php }else{?>
                    <li class="tab_s2">
                    	<span id="fav_alert" style="vertical-align:middle; display: table-cell; float:left;">Ajouter à vos favoris <img src="assets/images/star.png" alt="" style="margin-bottom: -2px;" /></span>
                    </li>
                    <?php }?>
                    
                    
                    <?php if($_SESSION['kt_login_id']){ ?>
                        <li class="tab_s2 head_abonne">
                        <?php
                        $query_newsletter = "SELECT
                                                sabonne.id_magasin
                                                , sabonne.id_user
                                                , sabonne.id
                                            FROM
                                                sabonne
                                                INNER JOIN utilisateur 
                                                    ON (sabonne.id_user = utilisateur.id)
                                                INNER JOIN magazins 
                                                    ON (sabonne.id_magasin = magazins.id_magazin) 
                                            WHERE magazins.id_magazin='".$_GET['mag_id']."' and utilisateur.id='".$_SESSION['kt_login_id']."'";
                        $newsletter = mysql_query($query_newsletter, $magazinducoin) or die(mysql_error());
                        $row_newslettert = mysql_fetch_array($newsletter);
                        ?>
                        <?php if(!isset($row_newslettert['id'])){?>
                            <span id="abonne" style="vertical-align:middle; display: table-cell; float:left;">S'abonner à cet établissement <img src="assets/images/hom.png" alt="" style="margin-bottom: -2px;" /></span>
                        <?php }else{?>
                           <span id="abonne" style="vertical-align:middle; display: table-cell; float:left;">Enlever cet établissement <img src="assets/images/hom.png" alt="" style="margin-bottom: -2px;" /></span>
                        <?php }?>
                        </li>
                    <?php }else{?>
                     	<li class="tab_s2">
                            <span id="abonner_alert" style="vertical-align:middle; display: table-cell; float:left;">S'abonner à cet établissement <img src="assets/images/hom.png" alt="" style="margin-bottom: -2px;" /></span>
                        </li>
					<?php }?>
				</ul>
				<!--<div id="bordure"></div>-->
			  
			
            <!-- content -->
            <div class="content_right">
             <style>
             	.content_right .tab_body{
					width:980px;
					/*height:220px;*/
					margin:10px;
					float:left;
					background:#cbcbcb;
					-webkit-border-radius: 5px 5px 5px 5px;
					border-radius: 5px 5px 5px 5px;
					-webkit-box-shadow: 0 2px 1px 0 #8e8d8d;
					box-shadow: 0 2px 1px 0 #8e8d8d;
				}
				.tab_body .tab_img{
					width:220px;
					height:220px;
					min-height:220px;
					float:left;
				}
				.tab_body .tab_img img.maga{
					position:relative;
					-webkit-border-radius: 5px 0px 5px 5px;
					border-radius: 5px 0px 0px 5px;
				}
				.tab_img .tab_list_img{
					width:220px; 
					height:50px; 
					min-height:50px;
					position:absolute; 
					bottom:30px;
					float:left;
				}
				.tab_list_img img{
					border:1px solid #FFF;
					float:left;
					margin-left:10px;
				}
				.tab_body .tab_info{
					float:left; 
					width:195px; 
					height:220px;
					min-height:220px;
				}
				.tab_info .tab_address{
					width:185px; 
					height:50px; 
					min-height:50px;
					margin:10px 0px 0px 10px; 
					font-size:13px;
					color:#9d216e;
					background:#FFF; 
					float:left;
					-webkit-border-radius: 5px 5px 5px 5px;
					border-radius: 5px 5px 5px 5px;
					display: table;
				}
				.tab_info .tab_des{
					width:185px; 
					height:140px; 
					min-height:140px;
					margin:10px 0px 0px 10px; 
					font-size:13px;
					background:#FFF; 
					float:left;
					-webkit-border-radius: 5px 5px 5px 5px;
					border-radius: 5px 5px 5px 5px;
					display: table;
				}
				.tab_body .tab_dateshop{
					width:185px;
					height:200px; 
					min-height:200px;
					margin:10px 0px 0px 10px; 
					float:left;
					font-size:13px;
					background:#FFF;
					-webkit-border-radius: 5px 5px 5px 5px;
					border-radius: 5px 5px 5px 5px;
					display: table;
				}
				.tab_dateshop table td{
					width:80px;
					margin:0px 0px 5px 0px;
					float:left;
					font-size:13px;
				}
				
				.tab_body .tab_contact{
					width:330px;
					height:200px; 
					min-height:200px;
					margin:10px 0px 0px 10px; 
					float:left;
					font-size:13px;
					background:#FFF;
					-webkit-border-radius: 5px 5px 5px 5px;
					border-radius: 5px 5px 5px 5px;
					display: table;
				}
				.tab_contact .contact_info{
					padding:10px; 
					float:left;
					color:#9d216e;
					font-size:13px;
				}
				.contact_info img {
					margin-bottom:-2px;
				}
				
				.tab_contact .tab_social{
					width:310px;
					margin:10px 0px 20px 10px;
					float:left;
					bottom:30px;
					position:absolute;
				}
				.tab_social ul.social-likes{
					background:#FFF !important;
					width:310px !important;
					float:left !important;
					margin:0px;
					line-height:0px !important;
				}
				.social-likes li{
					height:25px !important;
					float:left;
					margin-right:15px !important;
				}
				
				
				.tab_address span, .tab_des span, .tab_dateshop span{
					vertical-align:middle; 
					display: table-cell;
					padding:0px 10px;
				}
				
			 	.tab_bottom{
					float:right; 
					width:980px; 
					text-align:left; 
					margin:5px 10px 10px 10px; 
				}
				.tab_bottom a{
					float:right;
					color:#9d216e;
					margin-left:10px;
					font-size:12px;
				}
				.redu{
					background: url(template/images/flech_prix.png) no-repeat -4px 0px;
					width: 70px;
					height: 34px;
					font-size: 28px;
					padding: 9px;
					color: #fff;
					float:left;
				}
             </style>
				
				<?php if($_GET['id']>'0'){ ?> 
				<div id="tabs-1" style="position:relative">
                
					<div class="tab_body">
                    	<div class="tab_img">
                        	<span id="img_show">
                        	<img class="maga" src="timthumb.php?src=assets/images/produits/<?php echo $row_liste_produits['photo1'];  ?>&amp;cz=1&amp;w=220&amp;h=220" alt="" />
                            </span>
                            <div class="tab_list_img" >
                            	<?php if($row_liste_produits['photo1']){?>
                            	<img src="timthumb.php?src=assets/images/produits/<?php echo $row_liste_produits['photo1'];  ?>&amp;cz=1&amp;w=30&amp;h=30" alt="" />
                                <?php }?>
								<?php if($row_liste_produits['photo2']){?>
                            	<img src="timthumb.php?src=assets/images/produits/<?php echo $row_liste_produits['photo2'];  ?>&amp;cz=1&amp;w=30&amp;h=30" alt="" />
                                <?php }?>
                                <?php if($row_liste_produits['photo3']){?>
                                <img src="timthumb.php?src=assets/images/produits/<?php echo $row_liste_produits['photo3'];  ?>&amp;cz=1&amp;w=30&amp;h=30" alt="" />
                                <?php }?>
                            </div>
                        </div>
                        <div class="tab_info">
                            <div class="tab_address">
                                <span>
                                    <b><?php echo $row_liste_produits['titre'];?></b>
                                </span>
                            </div>
                            <div class="tab_des">
                            	<span>
                                	<?php echo $row_liste_produits['description'];  ?>  
                            	</span>
                            </div>
                        </div>
                        
                        <div class="tab_dateshop">
                        	<h4 style="float:left; color:#9d216e; width:185px; padding:10px; margin:0px; font-size:13px; font-weight:bold;">Information</h4>
                            <div style="float:left; padding:0px 10px;">
                                <table cellpadding="0" cellspacing="0" border="0">
                                	<tr>
                                    	<td>En stock :</td>
                                        <td>
                                		<?php 
											if($row_liste_produits['en_stock']==1) echo "Oui";
											else echo "Non";
										?>
                                        </td>
                                    </tr>
                                    
                                	<tr>
                                    	<td>Catégorie :</td>
                                        <td>
                                		<?php echo $row_liste_produits['cat_name'];?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <div class="tab_contact">
                        	<div class="contact_info" style="float:right !important;">
                            	<div class="redu"><?php echo $row_liste_produits['reduction']; ?>%</div>
                                <div style="float:left; width:100px; margin-top:9px;">
                                    <span <?php if($row_liste_produits['reduction']>0) echo 'style="text-decoration:line-through; font-size:24px; color:#F6AE30; font-weight:bold;"'; ?>><?php echo $row_liste_produits['prix']."€";  ?> </span>
                                    <span style="color:#9D286E; font-size:24px; font-weight:bold; float:left;"><?php echo $row_liste_produits['prix2']."&euro;"; ?></span>
                                </div>
                            </div>
                            <div class="tab_social">
                            	<h4 style="float:left; color:#9d216e; width:310px; padding:5px; margin:0px; font-size:13px; font-weight:bold;">Partager ce magasin dans vos réseau sociaux:</h4>
                                <ul class="social-likes" data-url="http://www.magasinducoin.fr<?php echo $_SESSION['url_sub'];?>" data-counters="no">
                                    <li class="facebook" title="<?php echo $row_liste_produits['nom_magazin'];  ?>">Facebook</li>
                                    <li class="twitter" data-via="Magainzin" data-related="" data-url="http://www.magasinducoin.fr<?php echo $_SESSION['url_sub'];?>" data-title="<?php echo $row_liste_produits['nom_magazin'];  ?>" title="<?php echo $row_liste_produits['nom_magazin'];  ?>">Twitter</li>
                                    <li class="plusone" title="<?php echo $row_liste_produits['nom_magazin'];  ?>">Google+</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tab_bottom">
                        <a onClick="ajax('ajax/addtoCoursesList.php?id_produit=<?php echo $row_liste_produits['id'];  ?>','#carts');" href="javascript:;"><?php echo $xml->Ajouter_au_panier ; ?></a>
                    	<a class='ajax' href="spam_email.php?id_mag=<?php echo $_GET['mag_id'];?>&pro_id=<?php echo $_GET['id'];?>"><img src="assets/images/wraning.png" alt="" /> Signaler le contenu</a>
                    </div>
				</div>
				<?php } ?>
				
				<div id="tabs-2" style="position:relative">
					<div class="tab_body">
                    	<div class="tab_img">
                        	<span id="img_show">
                            <a href="assets/images/magasins/<?php echo $row_liste_produits['photo1_mag'];  ?>" class="MagicZoom" id="Zoomer2" rel="selectors-effect-speed: 600; disable-zoom: true;">
                        		<img class="maga" src="timthumb.php?src=assets/images/magasins/<?php echo $row_liste_produits['photo1_mag'];  ?>&amp;cz=1&amp;w=220&amp;h=220" alt="" />
                            </a>
                            </span>
                            <div class="tab_list_img" >
                            	<?php if($row_liste_produits['photo1_mag']){?>
                            	<a href="assets/images/magasins/<?php echo $row_liste_produits['photo1_mag'];  ?>" rel="zoom-id: Zoomer2;" rev="timthumb.php?src=assets/images/magasins/<?php echo $row_liste_produits['photo1_mag'];  ?>&h=220&w=220&zc=1">
                            		<img src="timthumb.php?src=assets/images/magasins/<?php echo $row_liste_produits['photo1_mag'];  ?>&amp;cz=1&amp;w=30&amp;h=30" alt="" />
                                </a>
                                <?php }?>
                                <?php if($row_liste_produits['photo2_mag']){?>
                                <a href="assets/images/magasins/<?php echo $row_liste_produits['photo2_mag'];  ?>" rel="zoom-id: Zoomer2;" rev="timthumb.php?src=assets/images/magasins/<?php echo $row_liste_produits['photo2_mag'];  ?>&h=220&w=220&zc=1">
                            		<img src="timthumb.php?src=assets/images/magasins/<?php echo $row_liste_produits['photo2_mag'];  ?>&amp;cz=1&amp;w=30&amp;h=30" alt="" />
                                </a>
                                <?php }?>
                                <?php if($row_liste_produits['photo3_mag']){?>
                                <a href="assets/images/magasins/<?php echo $row_liste_produits['photo3_mag'];  ?>" rel="zoom-id: Zoomer2;" rev="timthumb.php?src=assets/images/magasins/<?php echo $row_liste_produits['photo3_mag'];  ?>&h=220&w=220&zc=1">
                                	<img src="timthumb.php?src=assets/images/magasins/<?php echo $row_liste_produits['photo3_mag'];  ?>&amp;cz=1&amp;w=30&amp;h=30" alt="" />
                                </a>
                                <?php }?>
                            </div>
                        </div>
                        <div class="tab_info">
                            <div class="tab_address">
                                <span>
                                    <?php echo $row_liste_produits['adresse'];  ?><br />
                                    <?php echo $row_liste_produits['cp'];  ?> <?php echo $row_liste_produits['nom'];?>
                                </span>
                            </div>
                            <div class="tab_des">
                            	<span>
                                	<?php echo $row_liste_produits['description_mag'];  ?>  
                            	</span>
                            </div>
                        </div>
                        
                        <div class="tab_dateshop">
                        	<h4 style="float:left; color:#9d216e; width:185px; padding:10px; margin:0px; font-size:13px; font-weight:bold;">Heures d'ouverture</h4>
                            <div style="float:left; padding:0px 10px;">
                                <table cellpadding="0" cellspacing="0" border="0">
                                	<tr>
                                    	<td>Lundi :</td>
                                        <td>
                                		<?php if($row_liste_produits['day1']=='1'){?>
                                        <b><?php echo $row_liste_produits['date1_m'];?> <?php echo $row_liste_produits['date1_e'];?></b>
                                        <?php }?>
                                        </td>
                                    </tr>
                                    
                                	<tr>
                                    	<td>Mardi :</td>
                                        <td>
                                		<?php if($row_liste_produits['day2']=='1'){?>
                                        <b><?php echo $row_liste_produits['date2_m'];?> <?php echo $row_liste_produits['date2_e'];?></b>
                                        <?php }?>
                                        </td>
                                    </tr>
                                	<tr>
                                    	<td>Mercredi :</td>
                                        <td>
                                		<?php if($row_liste_produits['day3']=='1'){?>
                                        <b><?php echo $row_liste_produits['date3_m'];?> <?php echo $row_liste_produits['date3_e'];?></b>
                                        <?php }?>
                                        </td>
                                    </tr>
                                	<tr>
                                    	<td>Jeudi :</td>
                                        <td>
                                		<?php if($row_liste_produits['day4']=='1'){?>
                                        <b><?php echo $row_liste_produits['date4_m'];?> <?php echo $row_liste_produits['date4_e'];?></b>
                                        <?php }?>
                                        </td>
                                    </tr>
                                	<tr>
                                    	<td>Vendredi :</td>
                                        <td>
                                		<?php if($row_liste_produits['day5']=='1'){?>
                                        <b><?php echo $row_liste_produits['date5_m'];?> <?php echo $row_liste_produits['date5_e'];?></b>
                                        <?php }?>
                                        </td>
                                    </tr>
                                	<tr>
                                    	<td>Samedi :</td>
                                        <td>
                                		<?php if($row_liste_produits['day6']=='1'){?>
                                        <b><?php echo $row_liste_produits['date6_m'];?> <?php echo $row_liste_produits['date6_e'];?></b>
                                        <?php }?>
                                        </td>
                                    </tr>
                                	<tr>
                                    	<td>Dimanche :</td>
                                        <td>
                                		<?php if($row_liste_produits['day7']=='1'){?>
                                        <b><?php echo $row_liste_produits['date7_m'];?> <?php echo $row_liste_produits['date7_e'];?></b>
                                        <?php }?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <div class="tab_contact">
                        	<div class="contact_info">
                            	<table cellpadding="5" cellspacing="0" border="0" >
                                	<?php if($row_liste_produits['telephone']!=''){?>
                                	<tr>
                                    	<td><img src="assets/images/1.png" alt="" /></td>
                                    	<td><?php echo $row_liste_produits['telephone'];?></td>
                                    </tr>
                                    <?php }?>
                                    <?php if($row_liste_produits['en_website']=='1'){?>
                                	<tr>
                                    	<td><img src="assets/images/2.png" alt="" /></td>
                                    	<td><a href="http://<?php echo $row_liste_produits['website']; ?>"><?php echo $row_liste_produits['website']; ?></a></td>
                                    </tr>
                                    <?php }?>
                                    <?php if($row_liste_produits['en_facebook']=='1'){?>
                                	<tr>
                                    	<td><img src="assets/images/3.png" alt="" /></td>
                                    	<td>Suivez-nous sur <a href="http://<?php echo $row_liste_produits['facebook'];  ?>"><span style="color:#4b5998; font-size:15px; font-weight:bold;">facebook</span></a></td>
                                    </tr>
                                    <?php }?>
                                </table>
                            </div>
                            <div class="tab_social">
                            	<h4 style="float:left; color:#9d216e; width:310px; padding:5px; margin:0px; font-size:13px; font-weight:bold;">Partager ce magasin dans vos réseau sociaux:</h4>
                                <ul class="social-likes" data-url="http://www.magasinducoin.fr<?php echo $_SESSION['url_sub'];?>" data-counters="no">
                                    <li class="facebook" title="<?php echo $row_liste_produits['nom_magazin'];  ?>">Facebook</li>
                                    <li class="twitter" data-via="Magainzin" data-related="" data-url="http://www.magasinducoin.fr<?php echo $_SESSION['url_sub'];?>" data-title="<?php echo $row_liste_produits['nom_magazin'];  ?>" title="<?php echo $row_liste_produits['nom_magazin'];  ?>">Twitter</li>
                                    <li class="plusone" title="<?php echo $row_liste_produits['nom_magazin'];  ?>">Google+</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="tab_bottom">
                    	<?php if($row_liste_produits['email']=='magasinducoin1@gmail.com' || $row_liste_produits['id_user']=='0'){?>
							<?php if(array_key_exists("s", $_GET)) {?>
                                <span id="owner" style="color:#9d216e; float:right; font-size:12px; margin-left:10px;">
                                 Veuillez attendre la validation du webmaster
                                </span>
                            <?php }else{?>
                                <?php if($_SESSION['kt_login_id']){ ?>
                                <script>
                                    jQuery(document).ready(function(){
                                        $('.btn').click(function() {
                                            var id_user = <?php echo $_SESSION['kt_login_id'];?>;
                                            var id_mag = <?php echo $row_liste_produits['id_magazin'];?>;
											var siren = $('#siren').val();
											if(siren!=''){
                                            var dataString = 'id_mag='+id_mag+'&id_user='+id_user+'&siren='+siren;
                                            $.ajax({
                                                    type: "POST",
                                                    url: "assets/owner_shoper/owner_shoper.php",
                                                    data: dataString,
                                                    cache: false,
                                                    success: function(datas){
														window.location="detail_magasin.php?region=<?php echo $default_region;?>&mag_id=<?php echo $row_liste_produits['id_magazin'];?>&s";
                                                    }
                                                });	
                                            return false;
											}else{
												alert('Saisissez le Siren!');
												return false;
											}
                                        });
                                    });
                                    </script>
                        			<a onclick="popup('popUpDiv')" href="#popUpDiv">Ce commerce vous appartient?</a>
                        
                        		<?php }else{?>  
                    				<a href="shop_owner.php?id_mag=<?php echo $row_liste_produits['id_magazin'];?>&nom_magasin=<?php echo $row_liste_produits['nom_magazin'];?>">Ce commerce vous appartient?</a>
								<?php }?>
                            <?php }?>
                        <?php }?>
                    	<a class='ajax' href="spam_email.php?id_mag=<?php echo $_GET['mag_id'];?>&pro_id=<?php echo $_GET['id'];?>"><img src="assets/images/wraning.png" alt="" /> Signaler le contenu</a>
                    </div>
                    
				</div>
			  
				<div id="tabs-3"  style="position:relative">
					<style>
                    .adp-text{
						vertical-align:middle;
						font-weight:bold;
						font-size:20px;
					}
					.adp-summary {
						padding: 0 3px 3px;
						font-size: 14px;
						font-weight: bold;
					}
					#getdirections{
						width: 112px;
						margin-top: 8px;
						background: #9d216e;
						border: none;
						padding: 5px 0px;
						color: #fff;
					}
					.copy{
						background:url(assets/images/swap_inputs.gif) no-repeat;
						width:20px;
						height:30px;
						border:none;
					}
                    </style>
                    <script type="text/javascript">
						$(document).ready(function() {
							//$('#gps_map').hide();
							$('#map_n').hide();
							$('#map_p').click(function() {
								$('#gps_map').animate({width: '370px', padding:'5px'},1500);
								$('#map_p').hide();
								$('#map_n').show();
							});
							
							$('#map_n').click(function() {
								$('#gps_map').animate({width: '0px', padding:'0px'},1500);
								$('#map_n').hide();
								$('#map_p').show();
							});

							$(".copy").click(function() {
								$('#keep').val($('#end').val());
								$('#end').val($('#start').val());
								$('#start').val($('#keep').val());
							});
						});
					</script>
                    <div>&nbsp;</div>
                    <div id="map"></div>
                    <div style="width:auto; height:370px; position:absolute; top:45px; right:0px;">
                    	
                        <div style="width:20px; height:360px; float:left;">
                        	<img src="assets/images/map_p.png" alt="" id="map_p" />
                        	<img src="assets/images/map_n.png" alt="" id="map_n" />
                        </div>
                        <div id="gps_map" style=" display:none; float:left; width:325px; height:340px; background-color:#FFF; overflow:auto;">
                            <h1 style="width:325px; height:25px; margin:0px; padding:0px; text-align:center; color:#9D286E;">Plan&nbsp;d'accès</h1>
                            <form action="directions.php" method="post" style="width:100%; float:left;">
                            <input type="hidden" id="adds" value="<?php echo $row_liste_produits['adresse']; ?> <?php echo $row_liste_produits['nom']; ?>, <?php echo $row_liste_produits['code_postal']; ?>"/>
                            <input type="hidden" id="keep" />
                            <table cellpadding="0" cellspacing="0" border="0" width="325" style="text-align:center;">
                            	<tr>
                                	<td width="10%"><img src="http://mt.googleapis.com/vt/icon/name=icons/spotlight/spotlight-waypoint-a.png&text=A&psize=16&font=fonts/Roboto-Regular.ttf&color=ff333333&ax=44&ay=48&scale=1" alt="" /></td>
                                	<td width="35%" style="vertical-align:middle;"><input style="width:125px !important;  margin:0px 5px !important" id="start" type="text" value="<?php
									if(isset($_SESSION['kt_adresse']))
										echo $_SESSION['kt_adresse'];
									else
										echo "Entrer votre adresse"; ?>"/></td>
                                	<td width="10%"><img src="http://mt.googleapis.com/vt/icon/name=icons/spotlight/spotlight-waypoint-b.png&text=B&psize=16&font=fonts/Roboto-Regular.ttf&color=ff333333&ax=44&ay=48&scale=1" alt="" /></td>
                                	<td width="35%" style="vertical-align:middle;"><input style="width:125px !important;  margin:0px 5px !important" id="end" type="text" value="<?php echo $row_liste_produits['adresse']; ?> <?php echo $row_liste_produits['nom']; ?>, <?php echo $row_liste_produits['code_postal']; ?>" /></td>
                                    <td width="10%" style="vertical-align:middle;"><input type="button" class="copy"/></td>
                                </tr>
                                <tr>
                                	<td colspan="5" style="text-align:center;"><input name="submit" id="getdirections" type="submit" value="Plan&nbsp;d'accès" /></td>
                                </tr>
                            </table>
                            </form>
                            <div id="directions" style="width:350px; float:left;"></div>
                        </div>
                    </div>
				</div>
                
                <?php if($totalRows_produits!='0'){?>
			  	<div id="tabs-6">
                    <div class="tab_body" style="width:960px; padding:10px;">
                        <div class="clear"></div>
                        <div id="shop_products" style="width:210px; margin-right:10px; float:left;"></div>
                        <div style="width:740px; float:left;" id="result"></div>
                    </div>   
				</div>
                <?php }?>
                
                <?php if($totalRows_coupons!='0'){?>
				<div id="tabs-5">
                	<div class="tab_body" style="width:960px; padding:10px;">
                        <div class="clear"></div>
                        <div style="width:100%; float:left;" id="re_coupon"></div>
                    </div>
				</div>
                <?php }?>
                
                <?php if($totalRows_evenements!='0'){?>
				<div id="tabs-4">
                	<div class="tab_body" style="width:960px; padding:10px;">
                        <div class="clear"></div>
                        <div style="width:100%; float:left;" id="re_event"></div>
                    </div>
				</div>
                <?php }?>
                

            </div>
            
            
        </div>
    </div>
</div>

</div>

<div class="clear"></div>


<div id="footer">
    <?php include("modules/footer.php"); ?>
</div>



</body>

</html>