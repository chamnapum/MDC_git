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

$query_categories = "SELECT cat_id, cat_name FROM category WHERE parent_id = 0 ORDER BY cat_name ASC";

$categories = mysql_query($query_categories, $magazinducoin) or die(mysql_error());



$query_region_pub = "SELECT produits.titre AS titre_produit, pub.titre, pub.region, pub.emplacement, pub.id_produit, pub.date_debut, pub.date_fin, produits.prix, produits.photo1, produits.id_magazin, produits.categorie, produits.sous_categorie FROM (pub LEFT JOIN produits ON produits.id=pub.id_produit)  WHERE pub.region = $default_region and produits.en_stock = 1 and pub.payer = 1 ";

$region_pub = mysql_query($query_region_pub, $magazinducoin) or die(mysql_error());

$totalRows_region_pub = mysql_num_rows($region_pub);

$tous_regionpub = array();

while($row_region_pub = mysql_fetch_assoc($region_pub)){

	$tous_regionpub[$row_region_pub['emplacement']][] = $row_region_pub;

}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>



<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<title>Magasinducoin | Accueil Région </title>

    <?php if(!isset($_SESSION['kt_adresse']) or empty($_SESSION['kt_adresse'])) { ?>

    <script type="text/JavaScript" src="geo.js"></script>

    <?php } ?>

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

                        

                     



<?php

$datetime = date('Y-m-d H:i:s');

$date = date('Y-m-d');

$query_Recordset1 = "SELECT DISTINCT 

  region.nom_region,
  magazins.id_magazin,
  magazins.nom_magazin,
  magazins.latlan,
  magazins.adresse,

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

	WHERE magazins.region='".$default_region."' AND magazins.activate='1' AND magazins.payer='1' AND magazins.approuve='1' ORDER BY nb_produits DESC, nb_coupons DESC, nb_events DESC LIMIT 0, 100";

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

  

	$('#test1').gmap3({

	  map:{

		options:{

		  center:[46.578498,2.457275],

		  zoom: 6

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

		

			{latLng:[<?php echo $str1;?>], data:"<a href='md-<?php echo $default_region;?>-<?php echo $nom_region;?>-<?php echo $coupon['id_magazin']; ?>-<?php echo $nom;?>.html'><b><?php echo $coupon['nom_magazin'];?></b></a><br/> Adresse: <?php echo $coupon['adresse'];?>, <?php echo $coupon['nom_region'];?>. <br /> <?php if($coupon['nb_coupons']>'0'){echo $coupon['nb_coupons'];?> COUPONS(s) <br /><?php }?><?php if($coupon['nb_events']>0){echo $coupon['nb_events'];?> ÉVÈNEMENTS(s) <br /><?php }?><?php if($coupon['nb_produits']>0){echo $coupon['nb_produits'];?> PRODUITS(s)<?php }?>"},

		  

		<?php } }?>  

		  //{latLng:[48.8620722, 2.352047], data:"Paris !"},

		  //{address:"86000 Poitiers, France", data:"Poitiers : great city !"},

		  //{address:"66000 Perpignan, France", data:"Perpignan ! <br> GO USAP !", options:{icon: "http://maps.google.com/mapfiles/marker_green.png"}}

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

		<style>

        	#test1{

				width:99%;

				height:500px;

				float:left;

			}

        </style>

		<div id="test1"></div>

            

            

            

		</div>

		<!--  End Top -->

		<div class="clear"></div>



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

mysql_free_result($categories);

?>



