<?php require_once('Connections/magazinducoin.php'); ?>
<?php
// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

// Make unified connection variable
$conn_magazinducoin = new KT_connection($magazinducoin, $database_magazinducoin);

//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_magazinducoin, "");
//Grand Levels: Level
//$restrict->addLevel("1");
$restrict->Execute();
//End Restrict Access To Page
//echo $_SESSION['kt_adresse'];
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
    <?php include("modules/member_menu.php"); ?>
	<?php /*?><div id="head-menu" style="float:left;"></div>
		<div  style="font-size: 14px; font-weight: bold; position: absolute; right: 190px; top: 166px;">
		<?php echo $xml-> Votre_credit_publicite  ;?><?php 
			$query_Recordset1 = "SELECT credit FROM utilisateur WHERE id = ".$_SESSION['kt_login_id'];
			$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
			$row_credit = mysql_fetch_assoc($Recordset1);
			echo $row_credit['credit'];  ?> &euro;
		</div>
	</div> <?php */?>
    <?php //include("modules/membre_menu.php"); ?>
    
		<div style="width:95%; padding-left:5%; float:left;">
			<div class="error">votre compte est en attente de validation par nos administrateurs</div>
         </div>
</div>

<div id="footer">
	<div class="recherche">
    &nbsp;
    </div>
    <?php include("modules/footer.php"); ?>
</div>
</body>
</html>