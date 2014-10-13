<?php require_once('Connections/magazinducoin.php'); ?>
<?php
// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

// Make unified connection variable
$conn_magazinducoin = new KT_connection($magazinducoin, $database_magazinducoin);

//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_magazinducoin, "");
//Grand Levels: Level
$restrict->addLevel("2");
$restrict->Execute();
//End Restrict Access To Page
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 
	<title>Magasin du coin | Espace membre </title>
    <?php include("modules/head.php"); ?>
</head>
<body id="sp">
<?php include("modules/header.php"); ?>
<div id="breadcrumbs">
	<a href="index.php" title="Accueil">Accueil</a> » <a href="membre.php">Espace membre</a> 
</div>
<div class="content_wrapper_sbr">
	<div>
  		<div id="content">
  		  <h2>Espace membre</h2>
          <ul class="unordered bullet_blue_arrow">
          	<li><a href="#">Mon profil</a></li>
            <li><a href="#">Mes Messages</a></li>
            <li><a href="#">Changer mon mot de passe</a></li>
            <li><a href="logout.php">Déconnexion</a></li>
          </ul>
	  </div>
	</div>
  
  
  
  
  <!-- Sidebar Area -->
  <div id="sidebar">
    <div class="widget-container widget_categories">
      <?php //include("includes/dictionnaire.php");?>
    </div>
  </div>
  <div class="clear"></div>
</div>

    </div>
  </div>
</form>


<?php //----------------------------------------------------------------------------------------------------------------------?>
<!-- End Content Wrapper -->
<!-- Start Footer Sidebar -->
<?php include("modules/footer.php"); ?>
</body>
</html>