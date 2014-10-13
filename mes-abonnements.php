<?php require_once('Connections/magazinducoin.php'); ?>
<?php
// Load the common classes
require_once('includes/common/KT_common.php');

// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

// Load the required classes
require_once('includes/tfi/TFI.php');
require_once('includes/tso/TSO.php');
require_once('includes/nav/NAV.php');

// Make unified connection variable
$conn_magazinducoin = new KT_connection($magazinducoin, $database_magazinducoin);

//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_magazinducoin, "");
//Grand Levels: Level
//$restrict->addLevel("1");
$restrict->Execute();
//End Restrict Access To Page
if(isset($_SESSION['kt_login_id']) and $_SESSION['kt_payer'] == 0) header('Location: message_aprouvation.php');

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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 
	<title>Magasinducoin | Espace membre </title>
    <?php include("modules/head.php"); ?>
    <link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
    <script src="includes/common/js/base.js" type="text/javascript"></script>
    <script src="includes/common/js/utility.js" type="text/javascript"></script>
    <script src="includes/skins/style.js" type="text/javascript"></script>
    <script src="includes/nxt/scripts/list.js" type="text/javascript"></script>
    <script src="includes/nxt/scripts/list.js.php" type="text/javascript"></script>
    <script type="text/javascript">
$NXT_LIST_SETTINGS = {
  duplicate_buttons: false,
  duplicate_navigation: false,
  row_effects: true,
  show_as_buttons: true,
  record_counter: true
}
    </script>
	<!--<script type="text/javascript">
		jQuery(document).ready(function(){
			$(".KT_delete_link").click(function() {
				var id = $(this).attr('id');
				alert(id);
				window.location = 'mes-favoris.php?id_fa='+id;
			});
		});
	</script>-->
    <style type="text/css">
  /* Dynamic List row settings */
  .KT_col_id_magasin {width:240px; overflow:hidden;}
    </style>
</head>
<body id="sp"onLoad="ajax('ajax/resultat_abonnements.php?prixMax=0&prixMin=0<?php 
echo $_GET['categorie']		? "&categorie=".$_GET['categorie'] : "";
echo $_GET['sous_categorie']   ? "&sous_categorie=".$_GET['sous_categorie'] : "";
echo isset($_GET['sous_categorie2'])   ? "&sous_categorie2=".$_GET['sous_categorie2'] : "";
echo $_GET['mot_cle'] ? "&mot_cle=".$_GET['mot_cle'] : "";
echo (isset($_GET['rayon']) and !empty($_GET['rayon']))   ? "&rayon=".$_GET['rayon'] : "100";
echo isset($_GET['magasin'])   ? "&magasin=".$_GET['magasin'] : "";

//if(isset($_GET['adresse']) and !empty($_GET['adresse'])){
//	$adresse = addslashes($_GET['adresse']);
//	$_SESSION['kt_adresse'] = $adresse;
//	echo "&adresse=".$_GET['adresse'];
//}
//else if(isset($_SESSION['kt_adresse']))
//	echo "&adresse=".addslashes($_SESSION['kt_adresse']);
//else
	//echo "Entrer votre adresse";
?>','#result'); <?php 
if($_GET['categorie']){
	$default = 0;
	if($_GET['sous_categorie']) $default = $_GET['sous_categorie'];
 	echo "ajax('ajax/sous_categorie.php?default=".$default."&id_parent=".$_GET['categorie']."','#sous_categorie');";
 } ?>">
<?php include("modules/header.php"); ?>

<div id="content">
	<div class="top reduit">
		<div id="head-menu" style="float:left;"></div>
			<?php if($_SESSION['kt_login_level'] == 1){ ?>
				<?php include("modules/credit.php"); ?>
        	<?php } ?>
		</div>           
		<?php include("modules/membre_menu.php"); ?>
        
	<div class="lister top" style="float:left; width:775px; padding-left:15px;">
		<div id="result" style="float:left; margin:10px 0 0 20px; width:755px;"></div>
    </div>
<div id="footer">
    <div class="recherche">
    &nbsp;
    </div>
    <?php include("modules/footer.php"); ?>
</div>

</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($rsabonnement);
?>