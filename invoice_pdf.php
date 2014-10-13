<?php require_once('Connections/connection.php');

function encode5t($str){

	for($i=0; $i<5;$i++){

		$str=strrev(base64_encode($str)); 

	}

	return $str;

}



function decode5t($str){

	for($i=0; $i<5;$i++){

		$str=base64_decode(strrev($str)); 

	}

	return $str;

}

$magazinducoin = mysql_pconnect($hostname_magazinducoin, $username_magazinducoin, $password_magazinducoin); 

mysql_select_db($database_magazinducoin, $magazinducoin);



 ?>



<?php

$id = decode5t($_REQUEST['id']);
$user = decode5t($_REQUEST['user']);

$query_user = "SELECT

    utilisateur.nom

    , utilisateur.prenom

    , utilisateur.email

    , utilisateur.adresse

    , region.nom_region

    , maps_ville.nom AS nom_ville

    , maps_ville.cp

FROM

    maps_ville

    INNER JOIN utilisateur 

        ON (maps_ville.id_ville = utilisateur.ville)

    INNER JOIN region 

        ON (utilisateur.region = region.id_region) WHERE utilisateur.id='".$user."'";

$user = mysql_query($query_user, $magazinducoin) or die("user".mysql_error());

$row_user = mysql_fetch_array($user);



$query_payment = "SELECT

    invoice.invoice_id

    , payment.tax

    , payment.total

    , payment.description

FROM

    payment

    INNER JOIN invoice 

        ON (payment.invoice_id = invoice.invoice_id) WHERE invoice.invoice_id='".$id."'";

$payment = mysql_query($query_payment, $magazinducoin) or die("Invoice".mysql_error());

//$row_payment = mysql_fetch_assoc($payment);



$query_invoice = "SELECT * FROM invoice WHERE invoice_id='".$id."'";

$invoice = mysql_query($query_invoice, $magazinducoin) or die("Invoice".mysql_error());

$row_invoice = mysql_fetch_array($invoice);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 

	<title>Magasin du coin | Imprimer les coupons </title>

    <style>

		table, p{

			font-size:12px;

		}

		td span{

			font-size:12px;

			font-weight:bold;

		}

		 .space{

			width:100%;

			height:10px; 

			clear:both;

		 }

    </style>

<body>



<div class="lister" style="margin:10px auto; width:100%;">

	<table cellpadding="5" cellspacing="0" border="0" width="100%">

		<tr>

            <td>

    			<img src="template/images/logo.png" width="274" height="110" alt="Logo - Magasin du coin" />

        	</td>

        </tr>

    </table>

    <div class="space"></div>

    <table cellpadding="0" cellspacing="0" border="0" width="100%">

        <tr>

            <td align="left"><h2>Contact facturation</h2></td>

            <td align="right"><h2>Magasinducoin</h2></td>

        </tr>

        <tr>

            <td align="left"><?php echo $row_user['nom'].' '.$row_user['prenom'];?></td>

            <td align="right">BP60068 - 57300 Hagondange Cedex2 – France </td>

        </tr>

        <tr>

            <td align="left"><?php echo $row_user['adresse'];?></td>

            <td align="right">contact@magasinducoin.fr </td>

        </tr>

        <tr>

            <td align="left"><?php echo $row_user['cp'].' '.$row_user['nom_ville'];?></td>

            <td align="right">Tél: 0825 700 047</td>

        </tr>

        <tr>

            <td align="left"><?php echo $row_user['email'];?></td>

            <td align="right">Numéro SIREN : 789 064 482</td>

        </tr>

        <tr>

            <td></td>

            <td align="right">Numéro SIRET : 789 064 482 00019</td>

        </tr>

    </table>

    <div class="space"></div>

    <table cellpadding="5" cellspacing="0" border="0" width="50%">

        <tr>

            <td align="left">Commande n&deg; :</td>

            <td align="right"><?php echo $row_invoice['orber_num'];?></td>

        </tr>

        <tr>

            <td align="left">Facture n&deg; :</td>

            <td align="right"><?php echo $row_invoice['invoice_id'];?></td>

        </tr>

        <tr>

            <td align="left">Payée le :</td>

            <td align="right"><?php echo date('Y/m/d h:i:s');?></td>

        </tr>

        <tr>

            <td align="left">Mode de paiement :</td>

            <td align="right">

            <?php 

                echo $type_payment = ($row_invoice['type_payment']== "1") ? "PayPal" : "Crédit";

            ?>

            </td>

        </tr>

    </table>

    <div class="space"></div>

    <table cellpadding="5" cellspacing="0" border="0" width="100%" style="border-collapse:collapse;">

        <tr>

            <th width="70%" align="left">Description</th>

            <th width="15%">Montant HT</th>

            <!--<th width="15%">Montant TTC</th>-->

        </tr>

        <?php while($row_payment = mysql_fetch_array($payment)){?>

        <tr style="background:#CCC;">

            <td style="border:1px solid #999;"><?php echo $row_payment['description'];?></td>

            <td style="border:1px solid #999;" align="center"><?php echo $row_payment['total'];?> &euro;</td>

            <!--<td align="center"><?php echo $row_payment['tax']+$row_payment['total'];?> &euro;</td>-->

        </tr>

        <?php }?>

        <tr>

            <td align="right"><span style="font-size:20px;">TTC</span></td>

            <td align="center"><span  style="font-size:20px;"><?php echo $row_invoice['amount'];?> &euro;</span></td>

        </tr>

    </table>

    

    

</div>









</body>

</html>







