<?php require_once('Connections/magazinducoin.php'); ?>
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

mysql_select_db($database_magazinducoin, $magazinducoin);
$categorie=$_GET['cat_id'];
$magazin=$_GET['id_mag'];

$query_Recordset1 = "SELECT produits.photo1, produits.id from produits WHERE produits.id_magazin=$magazin ";
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_liste_produits = sprintf("SELECT magazins.photo1 AS photo1_mag, magazins.photo2 AS photo2_mag, magazins.photo3 AS photo3_mag,  magazins.nom_magazin, magazins.siren, magazins.adresse, maps_ville.nom, magazins.code_postal, magazins.latlan, magazins.description AS description_mag, magazins.heure_ouverture, magazins.jours_ouverture, magazins.id_magazin, (SELECT COUNT(*) FROM produits WHERE id_magazin = magazins.id_magazin) AS nb_produits
FROM (magazins
LEFT JOIN maps_ville ON maps_ville.id_ville=magazins.ville) WHERE magazins.id_magazin = %s", GetSQLValueString($magazin, "int"));
$liste_produits = mysql_query($query_liste_produits, $magazinducoin) or die(mysql_error());
$row_liste_produits = mysql_fetch_assoc($liste_produits);
$totalRows_liste_produits = mysql_num_rows($liste_produits);

$id_magasin = $row_liste_produits['id_magazin'];

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_evenements = "SELECT evenements.event_id, evenements.titre, evenements.description, evenements.active, evenements.date_debut, evenements.date_fin, category.cat_name, category.cat_id FROM (evenements LEFT JOIN category ON category.cat_id=evenements.category_id) WHERE evenements.id_magazin = $id_magasin ORDER BY evenements.date_debut";
$evenements = mysql_query($query_evenements, $magazinducoin) or die(mysql_error());
$row_evenements = mysql_fetch_assoc($evenements);
$totalRows_evenements = mysql_num_rows($evenements);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_coupons = "SELECT coupons.id_coupon, coupons.titre, coupons.id_magasin, coupons.reduction, category.cat_name AS categories, category.cat_id AS cat, coupons.date_debut, coupons.date_fin, category1.cat_name AS sous_categorie,category1.cat_id AS sous_cat,  coupons.min_achat, coupons.id_coupon FROM (coupons LEFT JOIN category ON coupons.categories = category.cat_id) LEFT JOIN category AS category1 ON coupons.sous_categorie = category1.cat_id WHERE coupons.id_magasin = $id_magasin ORDER BY coupons.date_debut";
$coupons = mysql_query($query_coupons, $magazinducoin) or die(mysql_error());
$row_coupons = mysql_fetch_assoc($coupons);
$totalRows_coupons = mysql_num_rows($coupons);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $row_liste_produits['titre']; ?>-<?php echo $row_liste_produits['nom_magazin']; ?></title>
<meta name="title" content="<?php echo $row_liste_produits['titre']; ?> - <?php echo $row_liste_produits['nom_magazin']; ?>" />
<meta name="description" content="<?php echo $row_liste_produits['description']; ?>" />
<link rel="image_src" href="http://www.magazinducoin.com/produits/<?php echo ($row_liste_produits['photo1']); ?>" />
<!--Debut de script concernant leq onglets + 2) scroller -->
<link type="text/css" rel="stylesheet" href="template/css/style.css" />
<link type="text/css" rel="stylesheet" href="stylesheets/onglet.css" />
<link rel="stylesheet" type="text/css" href="scroller/imageScroller.css">
<!--scroller -->
<script type="text/javascript" src="http://jqueryjs.googlecode.com/files/jquery-1.3.2.min.js"></script>
<!--debut ongs  -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.js"></script>
<script type="text/javascript" src="assets/js/jquery-ui-1.8.16.custom.min.js"></script>
<!--fin ong  -->
<script src="zoom/magiczoom.js" type="text/javascript"></script>
<link href="zoom/magiczoom.css" rel="stylesheet" type="text/css" media="screen"/>
<link href="zoom/style.css" rel="stylesheet" type="text/css" media="screen"/>
<script type="text/javascript" src="scroller/scroller.js"></script>
<script type="text/javascript">
var isclicked = false;
$(document).ready(function() {
    $("#tabs").tabs();
/*	$("#mapc").click(function () {
			if(!isclicked){
				setTimeout("initialize()",500);
				isclicked = true;
			}
    	});*/
});
function mapclick(){
	if(!isclicked){
		setTimeout("initialize()",500);
		isclicked = true;
	}
}
</script>
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
</script>
<!-- fin de script concernant les onglets  -->
<?php 
		$map=$row_liste_produits['latlan'];
		$map=str_replace('(','',$map);
		$map=str_replace(')','',$map);
		$tab = explode(',', $map);
		//echo $tab[1];
		?>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=&sensor=true">
</script>
<script type="text/javascript">
    var latlng;
	var map;
	var directionsDisplay;
	var directionsService = new google.maps.DirectionsService();

	function initialize() {
	   latlng = new google.maps.LatLng(<?php echo $tab[0]; ?>, <?php echo $tab[1]; ?>);
	   directionsDisplay = new google.maps.DirectionsRenderer();
       var myOptions = {
          center: latlng,
          zoom: 15,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
		
        map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);
		directionsDisplay.setMap(map);
			
		marker = new google.maps.Marker({
		  position: latlng, 
		  map: map,
		  title:"<?php echo $row_liste_produits['nom_magazin']; ?>"
	  	});
		
		infowindow = new google.maps.InfoWindow({
		 	content: "<strong><?php echo $row_liste_produits['nom_magazin']; ?></strong><br /><?php echo $row_liste_produits['adresse']; ?><br /><?php echo $row_liste_produits['nom']; ?> <?php echo $row_liste_produits['code_postal']; ?>",
        	size: new google.maps.Size(50,50),
        	position: latlng
    	});
		infowindow.open(map);
		
      }
	  
	  function calcRoute() {
		  var start = document.getElementById("start").value;
		  var request = {
			origin:start,
			destination:latlng,
			travelMode: google.maps.TravelMode.DRIVING
		  };
		  directionsService.route(request, function(result, status) {
			if (status == google.maps.DirectionsStatus.OK) {
			  directionsDisplay.setDirections(result);
			}
			else{
				alert("erreu de calcule!!!!");
			}
		  });
		}
	
</script>
<style type="text/css">
#map_canvas {
	height: 450px;
	width:900px;
}
</style>
</head>
<script type="text/javascript">  
 function changeCouleur(nouvelleCouleur) {  
   elem = document.getElementById("bordure");  
   elem.style.backgroundColor = nouvelleCouleur;
   elem2 = document.getElementById("slogan");  
   elem2.style.color = nouvelleCouleur;
 } 
  
</script>
<style><?php $tabs=$_GET['t'];?>
#bordure {
  background-color:#<?php if($tabs==2) {echo"9d216e";}
 						 if($tabs==3) {echo"f6ae30";}
						  if($tabs==4) {echo"b35a91";}
						  if($tabs==5) {echo"ed8427";}
						 
					?>;
}
.popup #slogan {
    color:#<?php if($tabs==2) {echo"9d216e";}
 						 if($tabs==3) {echo"f6ae30";}
						  if($tabs==4) {echo"b35a91";}
						  if($tabs==5) {echo"ed8427";}
						  
					?>;
}
</style>
<body onload="initialize(); calcRoute();">

<div class="popup">
  <div  id="content" >
    <div class="head_detail">
    	<div class="head_detail_logo">
              <div id="logo"> <img src="template/images/logo.png" width="132" height="53" /> </div>
              <div id="slogan"> A coté de chez vous </div>
     	</div>
    	 <div class="head_form">
          <div class="selection">Soyez informé des réductions et évènements de ce magasin</div>

          <form id="form1" name="form1" method="post" action="">
              	<div id="form">
                <input type="text" name="nom" id="nom" value="Votre nom" />
                <input type="text" name="email" id="email" value="Votre email" />
                
                <input name="send"  class="send"type="image" src="template/images/send.png" value="Envoyer" onclick="ajax('ajax/send_newsletter.php?id_mag=<?php echo $id_magasin ?>&nom='+document.form1.nom.value+'&email='+document.form1.email.value,'#resultForm')" />
                </div>
                <div id="resultForm"></div>
              </form>
     	</div>
      
    </div>
    <div id="tabs">
      <ul >
      <li id="mapc"><a href="#tabs-3" onclick="changeCouleur('#f6ae30'); mapclick(); " >Map</a></li>
      <li id="mag"><a href="#tabs-2" onclick="changeCouleur('#9d216e');" >Magasin</a></li>
        
      </ul>
      <div id="bordure"> </div>
     
      <div id="tabs-2">
        <div id="desc">
          <div id="area1">
            <div id="img">
              <!-- define Magic Zoom -->
              <!-- define Magic Zoom -->
              <a href="assets/images/magasins/<?php echo $row_liste_produits['photo1_mag'];  ?>" class="MagicZoom" id="Zoomer2" rel="selectors-effect-speed: 600; disable-zoom: true;" title="Blue Yamaha YZF-R1"> <img src="timthumb.php?src=assets/images/magasins/<?php echo $row_liste_produits['photo1_mag'];  ?>&h=167&w=237&zc=1"/></a> 
              </div>
              <!-- selector with own title -->
              <div class="thumb_img">
              <a href="assets/images/magasins/<?php echo $row_liste_produits['photo1_mag'];  ?>" rel="zoom-id: Zoomer2;" rev="timthumb.php?src=assets/images/magasins/<?php echo $row_liste_produits['photo1_mag'];  ?>&h=167&w=237&zc=1" title="Red Yahama YZF-R1"><img src="timthumb.php?src=assets/images/magasins/<?php echo $row_liste_produits['photo1_mag'];  ?>&h=42&w=42&zc=1" /></a>
              
               <a style="margin-top:10px;" href="assets/images/magasins/<?php echo $row_liste_produits['photo2_mag'];  ?>" rel="zoom-id: Zoomer2;" rev="timthumb.php?src=assets/images/magasins/<?php echo $row_liste_produits['photo2_mag'];  ?>&h=167&w=237&zc=1" title=""><img src="timthumb.php?src=assets/images/magasins/<?php echo $row_liste_produits['photo2_mag'];  ?>&h=42&w=42&zc=1" /></a>
               
               
                <a href="assets/images/magasins/<?php echo $row_liste_produits['photo3_mag'];  ?>" rel="zoom-id: Zoomer2;" rev="timthumb.php?src=assets/images/magasins/<?php echo $row_liste_produits['photo3_mag'];  ?>&h=167&w=237&zc=1" title="Red Yahama YZF-R1"><img src="timthumb.php?src=assets/images/magasins/<?php echo $row_liste_produits['photo3_mag'];  ?>&h=42&w=42&zc=1" /></a><br/>
            </div>
             
            
          </div>
          <div id="area2">
           <div class="titre"> <?php echo $row_liste_produits['nom_magazin'];  ?> </div>
           <div>	<span class="selection"> jours d'ouverture:</span>
                 		<?php echo $row_liste_produits['jours_ouverture'];  ?> </div>
            <div>	<span class="selection"> heure d'ouverture:</span>
                 		<?php echo $row_liste_produits['heure_ouverture'];  ?> </div>
            <div> <span class="selection"> Adresse:</span> <?php echo $row_liste_produits['adresse'];  ?> </div>

              
                <div>  <span class="selection">Code postal: </span>
				<?php echo $row_liste_produits['code_postal'];  ?> </div>
                <div class="selection bgdesc">Description  :</div>
              </br>
              <?php echo $row_liste_produits['description_mag'];  ?> 
               <br /> <br/>
            <div> <span class="selection"><a style="color: #9F2570;" target="_parent" href="rechercher.php?adresse=&rayon=999&mot_cle=&categorie=&sous_categorie=&magasin=<?php echo $row_liste_produits['id_magazin'];  ?>"><strong><?php echo $row_liste_produits['nb_produits'];  ?></strong> produit(s) disponible dans <?php echo $row_liste_produits['nom_magazin'];  ?></a></span>  </div> </br>
            <div id="outerContainer">
            <?php  if($totalRows_Recordset1>=1) {?>
            <h4 style="margin-left:50px;">Autres produits en rapport avec votre choix :</h4>
            <div id="imageScroller">
              <div id="viewer" class="js-disabled">
                <?php while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)){?>
                <a class="wrapper" href=""><img class="logo" 
                          src="assets/images/produits/<?php echo 	$row_Recordset1['photo1'];   ?>" 
                          alt="robe" width="80" height="80"></a>
                <?php }?>
              </div>
            </div>
            <?php } ?>
          </div>
          </div>
        </div>
        
      </div>
      <div id="tabs-3" >
      	<div id="map_calculer"> 
        <input name="start" type="text" id="start" value="<?php echo $_COOKIE['kt_adresse']; ?>" />
        <input name="iteneraire" class="btnOrange"  type="button" value="Calculer itin&eacute;raire" onclick="calcRoute();" />
        </div>
        <div id="map_canvas"></div>
      </div>

      
    </div>
  </div>
</div>
</body>
</html>
<?php
mysql_free_result($evenements);

mysql_free_result($coupons);

mysql_free_result($Recordset1);

mysql_free_result($liste_produits);
?>
