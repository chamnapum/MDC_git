<?php require_once('Connections/magazinducoin.php'); ?>
<?php
// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

// Make unified connection variable
$conn_magazinducoin = new KT_connection($magazinducoin, $database_magazinducoin);

//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_magazinducoin, "");
//Grand Levels: Level
$restrict->addLevel("3");
$restrict->Execute();

if(isset($_GET['ini'])){
	$_SESSION['kt_adresse'] .= " ".getVilleById($_SESSION['kt_ville']);
	setcookie('kt_adresse', $_SESSION['kt_adresse']);
}
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

<div id="content">
    <div class="top reduit">
        <?php include("modules/menu.php"); ?>
    </div>
    <?php include("modules/membre_menu.php"); ?>
    
<div style="padding-left:40px;">
<h3>Espace membre</h3><br>
      
         </div>
</div>

<div id="footer">
    		<?php include("modules/region_barre_recherche.php"); ?>
        <div class="liens">
       		<?php include("modules/footer.php"); ?>
		</div>
</div>
</body>
</html>