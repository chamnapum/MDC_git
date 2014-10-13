<?php require_once('Connections/magazinducoin.php'); ?>
<?php if($default_region == 0) header('Location: index.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 
	<title>Magazin Du Coin </title>
    <?php include("modules/head.php"); ?>
</head>
<body id="sp" onload="ajax('ajax/resultat_recherche.php?prixMax=0&prixMin=0<?php 
echo $_GET['categorie']		? "&categorie=".$_GET['categorie'] : "";
echo $_GET['sous_categorie']   ? "&sous_categorie=".$_GET['sous_categorie'] : "";
echo $_GET['mot_cle'] ? "&mot_cle=".$_GET['mot_cle'] : "";
echo $_GET['rayon']   ? "&rayon=".$_GET['rayon'] : "";
echo ($_GET['adresse'] and $_GET['adresse'] != "Entrer votre adresse") ? "&adresse=".$_GET['adresse'] : "";
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
			<?php include ("modules/menu_filtre.php"); ?>
       		<div id="result" style="float:left; margin:10px 0 0 20px"></div>
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