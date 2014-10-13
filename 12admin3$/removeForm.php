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
<div style="height:500px;overflow:scroll;" align="center"><?php
	/*
	 * On prépare une fonction JavaScript pour confirmer la suppression
	 * d'un élément, cette fonction renvoi true ou false, permettant d'envoyer
	 * ou non le formulaire pour qu'il soit traité.
	 */ ?>
	<script type="text/javascript">
	<!--
	//Variable javascript globale pour savoir quel bouton submit est appuyé.
	var button;
	function confirmDelete()
	{
		if ( button == "xml" )
		{
			return confirm("<?php echo $xml->confirmDelete;?> '" + document.removeForm.xmlDataId.value + "' ?"); 
		}
		else if ( button == "translation" )
		{
			return confirm("<?php echo $xml->confirmDelete;?> 'translation' de langue '"
							 + document.removeForm.translationLangId.value + "' de l'element '"
							 + document.removeForm.xmlDataId.value + "' ?" );
		}
	}
	-->
	</script>
	<?php
	/*
	 *On ajoute l'attribut 'onsubmit' et 'name' afin d'envoyer le formulaire
	 *uniquement après confirmation de la suppression avec la fonction JS. 
	 */ ?>	
	<form action="removeForm.php" method="post" name="removeForm" onsubmit="return confirmDelete();">
		<table border="1">
			<tr>
				<td><?php 
					echo $xml->element.' <i>xmldata</i>';?>				
				</td>
				
				<td><?php 
					echo $xml->element.' <i>translation</i>';?>
				</td>				
			</tr>
			<tr>
				<td>
					<select name="xmlDataId"><?php 
						/*
						 * On récupère la liste des éléments pour ensuite
						 * l'afficher.
						 */
						$itemList = $xml->getItemNodeList();
						foreach ( $itemList as $item )
						{?>
							<option value="<?php echo $item;?>"><?php echo $item;?></option><?php							
						}?>
					</select>
				</td>
				<td><?php
					//On récupère toutes les langues de la base de données SQL
					$langs = $sql->selectQuery( '*', 'Languages', 0, '', '', '', '' );
				
					//Puis on créer la liste ?>
					<select name="translationLangId" style="width:100%;"><?php 
						foreach ( $langs as $language )
						{?>
							<option value="<?php echo $language['Language'];?>"><?php echo $xml->{ $language['Language'].'Lang' };?></option><?php
						}?>
					</select>
				</td>
			</tr>
			<tr>			
				<!-- Bouton d envoi pour supprimer un élément xmldata -->
				<td>
					<!-- On utilise l attribut ONCLICK dans lequel on place 
						 du code JavaScript, on assigne alors à la variable
						 button (variable JS) le mot XML ou TRANSLATION-->
					<input type="submit" name="submitDeleteXmlDataElement" value="<?php echo $xml->deleteElement;?>" onclick="button='xml';" />
				</td>
				
				<!-- Bouton d envoi pour supprimer un élément translation -->
				<td>
					<!-- Idem pour l attribut ONCLICK que précédement -->
					<input type="submit" name="submitDeleteTranslationElement" value="<?php echo $xml->deleteElement;?>" onclick="button='translation';"/>
				</td>				
			</tr>
		</table>	
	</form>
</div>
</div>
</div></body></html>