<?php require_once('Connections/magazinducoin.php'); ?>
<?php
// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

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

$colname_Recordset1 = "-1";
if (isset($_SESSION['kt_login_id'])) {
  $colname_Recordset1 = $_SESSION['kt_login_id'];
}
mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset1 = sprintf("SELECT * FROM utilisateur WHERE id = %s", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset2 = "SELECT * FROM magazins WHERE id_user = $colname_Recordset1 ORDER BY id_magazin DESC";
$Recordset2 = mysql_query($query_Recordset2, $magazinducoin) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2)
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
    
<div style="padding-left:250px;height:500px;">
  		  <h3>Paiement de 25&euro; pour l'ajout de: <?php echo $row_Recordset2['nom_magazin'] ?></h3>
          <h4>Adresse de facturation : </h4>
          <?php echo $row_Recordset1['prenom']; ?> <?php echo $row_Recordset1['nom']; ?> <br />
		  <?php echo $row_Recordset1['adresse']; ?><br />
		  <?php echo $row_Recordset1['code_postal']; ?> <?php echo getVilleById($row_Recordset1['ville']); ?>
          <br />
<br />

          
          <h4>Choisir la méthode de paiement</h4>
          <form action="payer_magasin2.php" method="post">
          <table width="100%" border="0" cellspacing="1" cellpadding="1">
  <tr valign="top">
  	<td width="5%" height="60"><input name="paiement" type="radio" value="paypal" checked="checked" />&nbsp;</td>
    <td width="10%"><img src="assets/images/paypal.jpg" alt="Paypal" /><br /><img src="assets/images/CB.gif" alt="Carte bleu" /></td>
    <td><div style="margin:0 0 10px 0;">
<strong>Payez par Paypal.</strong><br>
<span>Envoyer le cheque a l'adresse suivante : Association Magasinducoin 3 Rue Copernic 57300 HAGONDANGE</span>
</div></td>
    
  </tr>
  <tr valign="top">
  	<td height="60"><input name="paiement" type="radio" value="cheque" />&nbsp;</td>
    <td><img src="assets/images/cheque.gif" alt="Chèque" />&nbsp;</td>
    <td><div style="margin:0 0 10px 0;">
<strong>Payez par Chèque.</strong><br>
<span>Envoyer le cheque a l'adresse suivante : Association Magasinducoin 3 Rue Copernic 57300 HAGONDANGE</span>
</div></td>
    
  </tr>
  <tr valign="top">
  	<td height="60"><input name="paiement" type="radio" value="virement" />&nbsp;</td>
    <td><img src="assets/images/banktransfer.png" alt="Virement bancaire" />&nbsp;</td>
    <td><div style="margin:0 0 10px 0;">
<strong>Payez par Virement ou Versement bancaire.</strong><br>
<span>Pour accélérer l'activation de votre compte nous vous prions de noter le numéro de votre commande sur le reçu bancaire de virement et l'envoyer par fax sur le numéro 05-28-21-4507 ou par email à <b>sales@magasinducoin.fr</b></span>
</div>&nbsp;</td>
    
  </tr>
</table>
<input name="id" type="hidden" value="<?php echo $row_Recordset2['id_magazin'] ?>" />
<input name="titre" type="hidden" value="<?php echo $row_Recordset2['nom_magazin'] ?>" />
<input name="prix" type="hidden" value="25" />
<input name="send" type="submit" value="Valider" />
<input name="send" type="button" value="Annuler" onclick="window.location='mes-magazins.php';" />
</form>
</div>
	  </div>
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
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);
?>