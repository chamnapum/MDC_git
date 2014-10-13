<?php require_once('../Connections/magazinducoin.php'); ?>
<?php
// Load the common classes
require_once('../includes/common/KT_common.php');
require_once 'include/XMLEngine.php';

// Load the required classes
require_once('../includes/tfi/TFI.php');
require_once('../includes/tso/TSO.php');
require_once('../includes/nav/NAV.php');

// Make unified connection variable
$conn_magazinducoin = new KT_connection($magazinducoin, $database_magazinducoin);

//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_magazinducoin, "../");
//Grand Levels: Level
$restrict->addLevel("4");
$restrict->Execute();
//End Restrict Access To Page


//On inclut la classe SQLManager et créer un objet.
require_once 'include/SQLManager.php';
//$sql = new SQLManager( 'localhost', 'magasin3_develop', 'Sikofiko12', 'magasin3_bdd' );
$sql = new SQLManager( 'localhost', 'root', 'vi8x0vgC', 'magasin3_bdd' );

//Création du formulaire de saisie :

require_once 'removeFormTreatment.php';

//Début du formulaire d'administration : ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Admin | MagasinDuCoin </title>
    	<style type="text/css">
		@import url(../stylesheets/custom-bg.css);			/*link to CSS file where to change backgrounds of site headers */
		@import url(../stylesheets/styles-light.css);		/*link to the main CSS file for light theme color */
		@import url(../stylesheets/widgets-light.css);		/*link to the CSS file for widgets of light theme color */
		@import url(../stylesheets/superfish-admin.css);			/*link to the CSS file for superfish menu */
		@import url(../stylesheets/tipsy.css);				/*link to the CSS file for tips */
		@import url(../stylesheets/contact.css);				/*link to the CSS file for tips */
	</style>
</head>
<body id="sp">
<?php include("modules/header.php"); ?>

<div class="content_wrapper_sbr">
	  <div id="content">
      <a href="addForm.php">Ajout d'éléments</a><br />
<a href="removeForm.php">Suppression d'éléments</a><br />
<a href="traduceForm.php">Traduction des éléments</a><br />
</div>

</div>
</body>
</html>
