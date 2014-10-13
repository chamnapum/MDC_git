<?php require_once('Connections/magazinducoin.php'); ?>
<?php
// Load the common classes
require_once('includes/common/KT_common.php');

// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

// Load the KT_back class
require_once('includes/nxt/KT_back.php');

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("");

// Make unified connection variable
$conn_magazinducoin = new KT_connection($magazinducoin, $database_magazinducoin);

//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_magazinducoin, "");
//Grand Levels: Level
$restrict->addLevel("1");
$restrict->Execute();
//End Restrict Access To Page

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

if(isset($_POST['add_product'])){
	$query_existe = mysql_query("SELECT COUNT(*) AS nb FROM journal WHERE id_user = ".$_SESSION['kt_login_id']." AND element_id = ".$_POST['id_produit']." AND element_type = 'produits' AND departement = ".$_POST['departement']);
	$row = mysql_fetch_array($query_existe);
	if($row['nb']>0)
		$msg = '<div class="error">Ce produit existe déjà dans le journal de département!</div>';
	else {
		$query_add = "INSERT INTO journal (element_id, element_type, departement, id_user, date) VALUES (".$_POST['id_produit'].",'produits',".$_POST['departement'].",".$_SESSION['kt_login_id'].",'".date('Y-m-d')."')";
		$query = mysql_query($query_add, $magazinducoin) or die(mysql_error());
		$msg = '<div class="succes">Produit ajouté avec succès au journal!</div>';
	}
}
else if(isset($_POST['add_coupon'])){
	$query_existe = mysql_query("SELECT COUNT(*) AS nb FROM journal WHERE id_user = ".$_SESSION['kt_login_id']." AND element_id = ".$_POST['id_coupon']." AND element_type = 'coupons' AND departement = ".$_POST['departement']);
	$row = mysql_fetch_array($query_existe);
	if($row['nb']>0)
		$msg = '<div class="error">Ce coupon existe déjà dans le journal de département!</div>';
	else {
		$query_add = "INSERT INTO journal (element_id, element_type, departement, id_user, date) VALUES (".$_POST['id_coupon'].",'coupons',".$_POST['departement'].",".$_SESSION['kt_login_id'].",'".date('Y-m-d')."')";
		$query = mysql_query($query_add, $magazinducoin) or die(mysql_error());
		$msg = '<div class="succes">Coupon ajouté avec succès au journal!</div>';
	}
}
else if(isset($_POST['add_event'])){
	$query_existe = mysql_query("SELECT COUNT(*) AS nb FROM journal WHERE id_user = ".$_SESSION['kt_login_id']." AND element_id = ".$_POST['id_event']." AND element_type = 'evenements' AND departement = ".$_POST['departement']);
	$row = mysql_fetch_array($query_existe);
	if($row['nb']>0)
		$msg = '<div class="error">Cet événement existe déjà dans le journal de département!</div>';
	else {
		$query_add = "INSERT INTO journal (element_id, element_type, departement, id_user, date) VALUES (".$_POST['id_event'].",'evenements',".$_POST['departement'].",".$_SESSION['kt_login_id'].",'".date('Y-m-d')."')";
		$query = mysql_query($query_add, $magazinducoin) or die(mysql_error());
		$msg = '<div class="succes">&Eacute;vénement ajouté avec succès au journal!</div>';
	}
}
$query_Recordset3 = "SELECT titre, id FROM produits WHERE id_user = ".$_SESSION['kt_login_id']." ORDER BY titre";
$Recordset3 = mysql_query($query_Recordset3, $magazinducoin) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

$query_Recordset2 = "SELECT id_coupon, titre FROM coupons WHERE id_user = ".$_SESSION['kt_login_id']." ORDER BY titre";
$Recordset2 = mysql_query($query_Recordset2, $magazinducoin) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

$query_Recordset1 = "SELECT event_id, titre FROM evenements WHERE user_id = ".$_SESSION['kt_login_id']." ORDER BY titre";
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$query_departement = "SELECT id_departement, nom_departement FROM departement LEFT JOIN utilisateur ON utilisateur.region = departement.id_region WHERE utilisateur.id = ".$_SESSION['kt_login_id']." ORDER BY departement.nom_departement";
$departement = mysql_query($query_departement, $magazinducoin) or die(mysql_error());
$row_departement = mysql_fetch_assoc($departement);
$totalRows_departement = mysql_num_rows($departement);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 
	<title>Magasin du coin | Espace membre </title>
    <?php include("modules/head.php"); ?>
<link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script src="includes/common/js/base.js" type="text/javascript"></script>
<script src="includes/common/js/utility.js" type="text/javascript"></script>
<script src="includes/skins/style.js" type="text/javascript"></script>
<?php echo $tNGs->displayValidationRules();?>
<script src="includes/nxt/scripts/form.js" type="text/javascript"></script>
<script src="includes/nxt/scripts/form.js.php" type="text/javascript"></script>
<script type="text/javascript">
$NXT_FORM_SETTINGS = {
  duplicate_buttons: false,
  show_as_grid: false,
  merge_down_value: true
}
</script>
</head>
<body id="sp">
<?php include("modules/header.php"); ?>
  		<div id="content" class="photographes">
        <div class="top reduit">
                    <?php include("modules/menu.php"); ?>
                    <div  style="font-size: 14px; font-weight: bold; position: absolute; right: 15px; top: 51px;">
                    <?php echo $xml->Votre_credit_publicite; ?> <?php 
$query_Recordset1 = "SELECT credit FROM utilisateur WHERE id = ".$_SESSION['kt_login_id'];
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$row_credit = mysql_fetch_assoc($Recordset1);
echo $row_credit['credit'];  ?> &euro;</div>
            </div>
             <div style="float:left; width:200px;">           
<?php include("modules/membre_menu.php"); ?>
</div>
	<div style="float:left; width:800px">
    <div style="padding-left:20px; position:relative;">
    <h2><?php echo $xml->Journal ;?></h2>
    <?php if(isset($msg)) echo $msg; ?>
		<fieldset>
        <legend>Produits</legend>
        <form action="" method="post">
        <label for="id_produit"><?php echo $xml->Ajouter_un_produit ;?>:</label>
 			<select name="id_produit" id="id_produit">
            	<option value=""><?php echo $xml->selectionner ?></option>
                          <?php 
do {  
?>
                          <option value="<?php echo $row_Recordset3['id']?>"<?php if (!(strcmp($row_Recordset3['id'], $row_rspub['id_produit']))) {echo "SELECTED";} ?>><?php echo $row_Recordset3['titre']?></option>
                          <?php
} while ($row_Recordset3 = mysql_fetch_assoc($Recordset3));
  $rows = mysql_num_rows($Recordset3);
  if($rows > 0) {
      mysql_data_seek($Recordset3, 0);
	  $row_Recordset3 = mysql_fetch_assoc($Recordset3);
  }
?>
        </select>
        <select name="departement" id="departement">
            	<option value=""><?php echo $xml->selectionner ?></option>
                          <?php 
do {  
?>
                          <option value="<?php echo $row_departement['id_departement']?>"><?php echo $row_departement['nom_departement']?></option>
                          <?php
} while ($row_departement = mysql_fetch_assoc($departement));
  $rows = mysql_num_rows($departement);
  if($rows > 0) {
      mysql_data_seek($departement, 0);
	  $row_departement = mysql_fetch_assoc($departement);
  }
?>
        </select>
        <input name="add_product" type="submit" value="<?php echo $xml->Ajouter ?>" />
        </form>
        </fieldset>
        <br /><br />
        <fieldset>
        <legend><?php echo $xml->Coupons_de_reduction ?></legend>
        <form action="" method="post">
        <label for="coupon"><?php echo $xml->Ajouter_coupon ?>:</label>
 			<select name="id_coupon" id="id_coupon">
            	<option value=""><?php echo $xml->selectionner ?></option>
                          <?php 
do {  
?>
                          <option value="<?php echo $row_Recordset2['id_coupon']?>"><?php echo $row_Recordset2['titre']?></option>
                          <?php
} while ($row_Recordset2 = mysql_fetch_assoc($Recordset2));
  $rows = mysql_num_rows($Recordset2);
  if($rows > 0) {
      mysql_data_seek($Recordset2, 0);
	  $row_Recordset2 = mysql_fetch_assoc($Recordset2);
  }
?>
        </select>
        
        <select name="departement" id="departement">
            	<option value=""><?php echo $xml->selectionner ?></option>
                          <?php 
do {  
?>
                          <option value="<?php echo $row_departement['id_departement']?>"><?php echo $row_departement['nom_departement']?></option>
                          <?php
} while ($row_departement = mysql_fetch_assoc($departement));
  $rows = mysql_num_rows($departement);
  if($rows > 0) {
      mysql_data_seek($departement, 0);
	  $row_departement = mysql_fetch_assoc($departement);
  }
?>
        </select>
        <input name="add_coupon" type="submit" value="<?php echo $xml->Ajouter ?>" />
        </form>
        </fieldset><br /><br />
        <fieldset>
        <legend><?php echo $xml->evenement ?></legend>
        <form action="" method="post">
        <label for="coupon"><?php echo $xml-> ajouter_evenement ?>:</label>
 			<select name="id_event" id="id_event">
            	<option value=""><?php echo $xml->selectionner ?></option>
                          <?php 
do {  
?>
      			<option value="<?php echo $row_Recordset1['event_id']?>">
				<?php echo $row_Recordset1['titre']?></option>
                          <?php
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
  $rows = mysql_num_rows($Recordset1);
  if($rows > 0) {
      mysql_data_seek($Recordset1, 0);
	  $row_Recordset1 = mysql_fetch_assoc($Recordset1);
  }
?>
        </select>
        <select name="departement" id="departement">
            	<option value=""><?php echo $xml->selectionner ?></option>
                          <?php 
do {  
?>
                          <option value="<?php echo $row_departement['id_departement']?>"><?php echo $row_departement['nom_departement']?></option>
                          <?php
} while ($row_departement = mysql_fetch_assoc($departement));
  $rows = mysql_num_rows($departement);
  if($rows > 0) {
      mysql_data_seek($departement, 0);
	  $row_departement = mysql_fetch_assoc($departement);
  }
?>
        </select>
        <input name="add_event" type="submit" value="<?php echo $xml->Ajouter ?>"/>
        </form>
        </fieldset>
    </div>   
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
<?php

mysql_free_result($Recordset1);

mysql_free_result($Recordset3);
?>