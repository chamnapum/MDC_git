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



mysql_select_db($database_magazinducoin, $magazinducoin);

$query_Recordset1 = "SELECT * FROM utilisateur ORDER BY id DESC";

if(isset($_SESSION['kt_login_id']))

	$query_Recordset1 = "SELECT * FROM utilisateur WHERE id = ".$_SESSION['kt_login_id'];

	$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());

	$row_Recordset1 = mysql_fetch_assoc($Recordset1);

	$totalRows_Recordset1 = mysql_num_rows($Recordset1);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 

	<title>Magasinducoin | Achat de crédit publicitaire </title>

    <?php include("modules/head.php"); ?>

</head>

<body id="sp">

<?php include("modules/header.php"); ?>

<style>

	h4{

		font-size:14px;

		margin:10px 0px;

	}

	input[type=submit]{

		background-color: #9D286E;

		border: 0 none;

		color: #F8C263;

		font-size: 12px;

		font-weight: bold;

		padding: 5px 10px;

		cursor:pointer;

	}

</style>

<div id="content"> 

<?php include("modules/member_menu.php"); ?>

<?php include("modules/credit.php"); ?>       

    <div style="padding-left:25px;">

    	<h3><?php echo $xml->Paiement ; ?></h3>

    </div>

    <div style="width:100%;">

    	<div style="width:50%; margin:0px auto;">

        <h4><?php echo $xml->Adresse_de_facturation ?>: </h4>

        <?php echo $row_Recordset1['prenom']; ?> <?php echo $row_Recordset1['nom']; ?> <br />

        <?php echo $row_Recordset1['adresse']; ?><br />

        <?php echo $row_Recordset1['code_postal']; ?> <?php echo getVilleById($row_Recordset1['ville']); ?>

        <br /><br />

        

        <h4><?php echo $xml-> choisir_method_paiement ;?></h4>

        <?php if(isset($_GET['error']) and $_GET['error'] == 'code_promo') 

        echo '<div class="error">'.$xml->code_promo_incorrect.'!</div>'; ?>

<form action="payer_abonement_commercant.php" method="post">

<?php if(isset($_GET['max_free']) and $_GET['max_free'] == '1') { ?>

	<?php if(isset($_GET['type'])){ ?>

    <p>Vous avez atteint le nombre maximum de <?php echo $_GET['type'].'s'; ?> gratuits. Pour tout <?php echo $_GET['type']; ?> supplémentaire, merci de payer <?php echo $_SESSION['montant_payer'];?> €.</p>              

    <ul>

        <li><input type="hidden" name="credit" checked value="<?php echo $_SESSION['montant_payer'];?>"></li>

    </ul>  

    <?php }else{?>

    <p>Vous avez atteint le nombre maximum de <?php echo $_GET['type'].'s'; ?> gratuits. Pour tout <?php echo $_GET['type']; ?> supplémentaire, merci de payer 3€.</p>              

    <ul>

        <li><input type="hidden" name="credit" checked value="3"></li>

    </ul> 

    <?php }?>



<?php }elseif(isset($_GET['prix'])){ ?>

<p>merci de payer <?php echo $_GET['prix'];?> €</p>

<ul>

    <li><input type="hidden" name="credit" checked value="<?php echo $_GET['prix']; ?>"></li>

</ul>  

<?php }else{ ?>

<ul>

	<?php

		$query_Recordset2 = "SELECT * FROM sale_credit";

		$Recordset2 = mysql_query($query_Recordset2, $magazinducoin) or die(mysql_error());

		while($row_Recordset2 = mysql_fetch_array($Recordset2)){

	?>

    <li><input type="radio" name="credit_p" <?php if($row_Recordset2['credit']=='30') echo'checked';?> value="<?php echo $row_Recordset2['id'];?>"><?php echo $row_Recordset2['credit'];?> € de crédit pub (<?php echo $row_Recordset2['credit_plus'];?> € offert)</li>

    <?php }?>

    <!--<li><input type="radio" name=credit value="10">10€ de crédit pub (3€ offert)</li>

    <li><input type="radio" name=credit value="20">20€ de crédit pub (8€ offert)</li>

    <li><input type="radio" name=credit checked value="30">30€ de crédit pub (15€ offert)</li>

    <li><input type="radio" name=credit value="50">50€ de crédit pub (25€ offert)</li>

    <li><input type="radio" name=credit value="100">100€ de crédit pub (75€ offert)</li>-->

</ul>

<?php } ?>

              <br /><br />

              

<table width="100%" border="0" cellspacing="1" cellpadding="1">

	<tr valign="top">

        <td width="5%" height="60">

        	<input name="paiement" type="radio" value="paypal" checked="checked" />&nbsp;

        </td>

        <td width="10%"><img src="assets/images/paypal.jpg" alt="Paypal" /><br /><img src="assets/images/CB.gif" alt="Carte bleu" /></td>

        <td>

        	<div style="margin:0 0 10px 0;">

            <strong><?php echo $xml->payer_paypal ?>.</strong><br>

            <span><?php echo $xml->txt_payer_paypal ?></span>

            </div>

        </td>

	</tr>



	<tr valign="top">

        <td height="60"><input name="paiement" type="radio" value="cheque" />&nbsp;</td>

        <td><img src="assets/images/cheque.gif" alt="Chèque" />&nbsp;</td>

        <td>

            <div style="margin:0 0 10px 0;">

            <strong><?php echo $xml->payer_cheque ?></strong><br>

            <span><?php echo $xml->txt_payer_cheque ?></span>

            </div>

        </td>

	</tr>

	<tr valign="top">

        <td height="60"><input name="paiement" type="radio" value="virement" />&nbsp;</td>

        <td><img src="assets/images/banktransfer.png" alt="Virement bancaire" />&nbsp;</td>

        <td>

            <div style="margin:0 0 10px 0;">

            <strong><?php echo $xml->payer_virement ?>.</strong><br>

            <span>Pour accéllrer l'activation de vos crédits, nous vous prions de noter le numéro de votre commande sur le reçu bancaire de virement et de l'envoyer par fax au numéro 03.68.38.81.21 ou par email à <b>sales@magasinducoin.fr</b></span>

            </div>&nbsp;

        </td>

	</tr>

</table>



<table>

    <tr valign="top">

    	<td style="font-size:14px; font-weight:bold; padding:5px;"><label><?php echo $xml->Code_promo ?></label></td><td>

    	<input name="code_promo" type="text" />

        </td>

    </tr>

</table>

    <input name="id" type="hidden" value="<?php echo $row_Recordset1['id']; ?>" />

    <input name="send" type="submit" value="Valider" />

</form>

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

?>