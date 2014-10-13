<?php require_once('Connections/magazinducoin.php'); ?>
<?php if($default_region == 0) header('Location: index.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 
	<title>Magazin Du Coin </title>
    <?php if(!isset($_SESSION['kt_adresse']) or empty($_SESSION['kt_adresse'])) { ?>
    <script type="text/JavaScript" src="geo/geo.js"></script>
    <?php } ?>
    <?php include("modules/head.php"); ?>
</head>
<body id="sp" onload="ajax('ajax/resultat_recherche_bons.php?prixMax=0&prixMin=0<?php 
echo $_GET['categorie']		? "&categorie=".$_GET['categorie'] : "";
echo $_GET['sous_categorie']   ? "&sous_categorie=".$_GET['sous_categorie'] : "";
echo $_GET['mot_cle'] ? "&mot_cle=".$_GET['mot_cle'] : "";
echo $_GET['rayon']   ? "&rayon=".$_GET['rayon'] : "";
echo isset($_GET['magasin'])   ? "&magasin=".$_GET['magasin'] : "";
echo isset($_GET['id_coupon'])   ? "&coupon=".$_GET['id_coupon'] : "";
if(isset($_GET['adresse']) and !empty($_GET['adresse'])){
	$adresse = $_GET['adresse'];
	$_SESSION['kt_adresse'] = $adresse;
	echo "&adresse=".$_GET['adresse'];
}
else if(isset($_SESSION['kt_adresse']))
	echo "&adresse=".$_SESSION['kt_adresse'];
else
	echo "Entrer votre adresse";
?>','#result'); <?php 
if($_GET['categorie']){
	$default = 0;
	if($_GET['sous_categorie']) $default = $_GET['sous_categorie'];
 	echo "ajax('ajax/sous_categorie.php?default=".$default."&id_parent=".$_GET['categorie']."','#sous_categorie');";
 } ?>">
<?php include("modules/header.php"); ?>

	<div class="content_wrapper_sbr">
    
  		<div id="content" class="lister" >
        	    <div class="top reduit">
                       <?php include("modules/menu.php"); ?>
            	       <?php include("modules/menu1.php"); ?>
  			    </div>
               <!-- debut recherche -->
               <div class="recherche_et_pub">
            	<?php include("modules/form_recherche_home.php"); ?>
	            </div>
               <!--fin de recherche-->
			<?php include ("modules/menu_filtre_bons.php"); ?>
       		<div id="result" style="float:left; margin:10px 0 0 20px"></div>
            <!-- <div id="testDirectCallHtmlContent" style="display:none;">
    			<h3>Entrer votre adresse ou votre code postale</h3>
                <p>
                    <form action="enregistrer_adresse.php" method="post"><br />

                        <input type="text" class="adr" id="adresse" name="adresse" style="width:405px !important; margin:0">
                        <input name="send" type="submit" class="image-submit" value="OK" />
                    </form>
                </p>
			</div>-->
  		</div>
  <div class="clear"></div>
</div>

<div id="footer">
    		<?php include("modules/region_barre_recherche.php"); ?>
        <div class="liens">
       		<?php include("modules/footer.php"); ?>
		</div>
</div>

</body>
</html>