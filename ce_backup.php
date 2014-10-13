<?php require_once('Connections/magazinducoin.php'); ?>

<?php

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

	<title>[DEBUG]Magasinducoin | Accueil </title>

    <?php include("modules/head-accueil.php"); ?>

</head>

<body>
<?php include("modules/header.php"); ?>

<div id="content" class="home">
	<?php
	$query_Recordset1 = "
	INSERT INTO coupons_backup (id_coupon, reduction, date_debut, date_fin, id_user, titre, categories, sous_categorie, id_magasin, code_bare, min_achat, description, count_click, count_print, active, gratuit, payer, en_avant, en_avant_payer, en_avant_fin, en_tete_liste, en_tete_liste_fin, en_tete_liste_payer, magasin_default, approuve, photo1, day_en_avant, day_en_tete_liste )
	SELECT id_coupon, reduction, date_debut, date_fin, id_user, titre, categories, sous_categorie, id_magasin, code_bare, min_achat, description, count_click, count_print, active, gratuit, payer, en_avant, en_avant_payer, en_avant_fin, en_tete_liste, en_tete_liste_fin, en_tete_liste_payer, magasin_default, approuve, photo1, day_en_avant, day_en_tete_liste
	FROM coupons
	WHERE date_fin < NOW()
	";
	$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
	
	$query_Recordset2 = "
	DELETE FROM coupons WHERE date_fin < NOW()
	";
	$Recordset2 = mysql_query($query_Recordset2, $magazinducoin) or die(mysql_error());
	
	$query_Recordset3 = "
	INSERT INTO evenements (event_id, date_debut, date_fin, id_user, titre, category_id, id_region, count_click, count_print, active, gratuit, payer, en_avant, en_avant_payer, en_avant_fin, description, id_magazin, en_tete_liste, en_tete_liste_fin, en_tete_liste_payer, approuve, day_en_avant, day_en_tete_liste)
	SELECT event_id, date_debut, date_fin, id_user, titre, category_id, id_region, count_click, count_print, active, gratuit, payer, en_avant, en_avant_payer, en_avant_fin, description, id_magazin, en_tete_liste, en_tete_liste_fin, en_tete_liste_payer, approuve, day_en_avant, day_en_tete_liste
	FROM evenements_backup
	WHERE date_fin < NOW();
	";
	$Recordset3 = mysql_query($query_Recordset3, $magazinducoin) or die(mysql_error());
	
	$query_Recordset4 = "
	DELETE FROM evenements_backup WHERE date_fin < NOW()
	";
	$Recordset4 = mysql_query($query_Recordset4, $magazinducoin) or die(mysql_error());
	?>
</div>


<div id="footer">
	<?php include("modules/footer.php"); ?>
</div>

</body>

</html>

<?php mysql_free_result($Recordset1);?>
<?php mysql_free_result($Recordset2);?>
<?php mysql_free_result($Recordset3);?>
<?php mysql_free_result($Recordset4);?>