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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Magazin Du Coin | </title>
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
      <div style="float:left; width:200px;">
      	<a href="addForm.php">Ajout d'&eacute;l&eacute;ments</a><br />
    	<a href="removeForm.php">Suppression d'&eacute;l&eacute;ments</a><br />
    	<a href="traduceForm.php">Traduction des &eacute;l&eacute;ments</a><br />
    </div>
<form action="addForm.php" method="post">

	<!-- On place un tableau dans le formulaire pour le mettre
	     en forme -->
	<table border="1">

		<!-- Titre -->
		<tr>
			<td colspan="2">
				<?php echo $xml->webPageName;?>
			</td>
		</tr>		

		<!-- Saisie de l ID de la balise -->
		<tr>
			<td>
				<?php echo $xml->inputID;?>
			</td>
			<td>
				<input type="text" name="itemId" />
			</td>
		</tr>

		<!-- Saisie du texte pour le nouveau contenu -->
		<tr>
			<td>
				<?php echo $xml->inputData;?>
			</td>
			<td>
				<textarea cols="20" rows="5" name="itemData"></textarea>
			</td>
		</tr>

		<!-- Création de la liste de choix des langues -->
		<tr>
			<td>
				<?php echo $xml->inputLang;?>
			</td>
			<td><?php 
				//On récupère toutes les langues de la base de données SQL
				$langs = $sql->selectQuery( '*', 'Languages', 0, '', '', '', '' );
				
				//Puis on créer la liste ?>
				<select name="itemLanguage" style="width:100%;"><?php 
					foreach ( $langs as $language )
					{?>
						<option value="<?php echo $language['Language'];?>"><?php echo $xml->{ $language['Language'].'Lang' };?></option><?php
					}?>
				</select>				
			</td>
		</tr>

		<!-- Bouton d envoi pour créer une balise XML -->
		<tr>
			<td colspan="2">
				<input style="width:100%;text-align:center;" type="submit" 
				name="submitCreateItem" value="<?php echo $xml->submitCreate;?>" />
			</td>
		</tr>
	</table>
</form>	<?php 
/*
 * Traitement du formulaire lorsqu'il est soumis
 */
if ( isset( $_POST['submitCreateItem'] ) )
{
	/*
	 * On fait alors appel à la fonction addXmlElement avec les
	 * variables du formulaire, comme la fonction renvoi un booleen
	 * on peut l inclure directement dans la condition du IF :
	 */
	if ( $xml->addXmlElement( $_POST['itemId'], $_POST['itemLanguage'], $_POST['itemData'] ) )
	{
		/* Réussite de l'ajout de l'élément
		 * On affiche un petit message javascript, toujours avec notre classe.
		 */?>
		<script type="text/javascript">
		<!--
			alert("<?php echo $xml->createXmlDone;?>");
		-->
		</script><?php		
	}
	else
	{?>
		<script type="text/javascript">
		<!--
			alert("<?php echo $xml->createXmlError;?>");
		-->
		</script><?php		
	}
}
?>
</div>
</div>
</body>
</html>