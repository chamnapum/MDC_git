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

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<title>Accueil de Région <?php echo ($row_villes['nom_region']);?> - Magasin Du Coin</title>

    <meta name="description" content="Retrouver dans votre région <?php echo ($row_villes['nom_region']);?> tous les coupons de réductions, bons plans et évènements que vos commerçants et votre centre ville proposent. Ne loupez plus les bonnes affaires" />

    <meta content="Code promo <?php echo ($row_villes['nom_region']);?>, Code promotion <?php echo ($row_villes['nom_region']);?>, Bon plan <?php echo ($row_villes['nom_region']);?>, Bon de réduction <?php echo ($row_villes['nom_region']);?>, Coupon de réduction <?php echo ($row_villes['nom_region']);?>, Bon plan" name="keywords" />

    <?php include("modules/head.php"); ?>

    <link rel="stylesheet" type="text/css" href="assets/silde_coupon/skins/tango/skin.css" />



</head>

<body>

<?php include("modules/header.php"); ?>

    <!--End header-->

    <div id="content">

    

       

    	<input type="hidden" id="adresse" name="adresse" value="<?php 

		if(isset($_GET['adresse']) and !empty($_GET['adresse'])){

			echo $_GET['adresse'];

		}

		else if(isset($_SESSION['kt_adresse']))

			echo $_SESSION['kt_adresse'];?>" onblur="adresse = this.value; if(this.value == '') this.value='Entrer votre adresse';" onfocus="if(this.value == 'Entrer votre adresse') this.value=''" class="adr" />

		<?php /*?><?php include("modules/form_recherche_header.php"); ?>

    	<div class="top reduit">

        	<div id="head-menu" style="float:left;">

            	<?php include("assets/menu/main-menu.php"); ?>

            </div>

            <div id="url-menu" style="float:left;">

            <?php include("assets/menu/url_menu.php"); ?>

            </div>

        </div><?php */?>
        <style>
    #url_menu_bar{
         margin-top: 10px !important;
    }
    </style>
    <div id="url-menu" style="float:left;">
	<?php include("assets/menu/url_menu.php"); ?>
    </div>

        <div class="clear"></div>

        



		<div class="top" style="margin:0px 20px;">

            

            <h2>Vous êtes un annonceur national :</h2>

            <p>Avec nos bannières, pavés, vignettes, habillages..., rendez votre marque visible auprès d'une population d'internautes en situation d'achat.</p>

            <div style="width:100%; float:left; padding-bottom:20px; background:#FFF; border:1px solid #999;">

            	<div style=" width:215px; margin:20px 0px 0px 20px; float:left;">

                    <div style="border:1px solid #CCC;">

                        <img src="assets/images/banner/home.png" alt="" />

                    </div>

                    <div style="width:215px;">Banner : 468x60</div>

                    <div style="width:215px;">HomePage</div>

                </div>

            	<div style=" width:215px; margin:20px 0px 0px 20px; float:left;">

                    <div style="border:1px solid #CCC;">

                        <img src="assets/images/banner/banner01.png" alt="" />

                    </div>

                    <div style="width:215px;">Banner : 215x260</div>

                    <div style="width:215px;">Region Page</div>

                </div>

            	<div style=" width:215px; margin:20px 0px 0px 20px; float:left;">

                    <div style="border:1px solid #CCC;">

                        <img src="assets/images/banner/banner02.png" alt="" />

                    </div>

                    <div style="width:215px;">Banner : 220x90</div>

                    <div style="width:215px;">Region page slider</div>

                </div>

            	<div style=" width:215px; margin:20px 0px 0px 20px; float:left;">

                    <div style="border:1px solid #CCC;">

                        <img src="assets/images/banner/banner03.png" alt="" />

                    </div>

                    <div style="width:215px;">Banner : 468x60</div>

                    <div style="width:215px;">Magazin Liste</div>

                </div>

            	<div style=" width:215px; margin:20px 0px 0px 20px; float:left;">

                    <div style="border:1px solid #CCC;">

                        <img src="assets/images/banner/home.png" alt="" />

                    </div>

                    <div style="width:215px;">Banner : 468x60</div>

                    <div style="width:215px;">HomePage</div>

                </div>

                

            </div>

		</div>

		<!--  End Top -->



        

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



