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

        ON (utilisateur.region = region.id_region) WHERE utilisateur.id=".$_SESSION['kt_login_id'];

$user = mysql_query($query_user, $magazinducoin) or die("user".mysql_error());

$row_user = mysql_fetch_assoc($user);



$query_payment = "SELECT

    invoice.invoice_id

    , payment.tax

    , payment.total

    , payment.description

FROM

    payment

    INNER JOIN invoice 

        ON (payment.invoice_id = invoice.invoice_id) WHERE invoice.invoice_id='".$_GET['id']."'";

$payment = mysql_query($query_payment, $magazinducoin) or die("Invoice".mysql_error());

//$row_payment = mysql_fetch_assoc($payment);



$query_invoice = "SELECT * FROM invoice WHERE invoice_id='".$_GET['id']."'";

$invoice = mysql_query($query_invoice, $magazinducoin) or die("Invoice".mysql_error());

$row_invoice = mysql_fetch_assoc($invoice);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<?php include("modules/head.php"); ?>

<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 

	<title>Magasin du coin | Imprimer les coupons </title>

    <style media="print">

		.imp{

			display:none;

		}

	</style>

    <style>

		table, p{

			font-size:12px;

		}

		td span{

			font-size:12px;

			font-weight:bold;

		}

    </style>

<body>

<!--<h1 class="imp" style="margin:10px auto; width:546px;">www.magasinducoin.com - <?php echo date('d/m/Y'); ?><a class="imp" style="float:right" href="javascript:print();">Imprimer</a></h1>-->



<div class="lister" style="margin:10px auto; width:546px;">

	<div style="width:100%; float:right; text-align:right; font-size:12px;"><a href="pdf.php?id=<?php echo encode5t($_GET['id']); ?>&user=<?php echo encode5t($_SESSION['kt_login_id']); ?>" target="_new">générer le pdf</a></div>

	<div style="width:100%; float:left;">

    	<img src="template/images/logo.png" width="274" height="110" alt="Logo - Magasin du coin" />

    </div>

    <div style="width:50%; float:left;">

    	<h2>Contact facturation</h2>

        <p><?php echo $row_user['nom'].' '.$row_user['prenom'];?></p>

        <p><?php echo $row_user['adresse'];?></p>

        <p><?php echo $row_user['cp'].' '.$row_user['nom_ville'];?></p>

        <p><?php echo $row_user['email'];?></p>

        

    </div>

    <div style="width:50%; float:right; text-align:right;">

    	<h2>Magasinducoin</h2>

        <p>BP60068 - 57300 Hagondange Cedex2 – France </p>

        <p>contact@magasinducoin.fr </p>

        <p>Tél: 0825 700 047</p>

        <p>Numéro SIREN : 789 064 482</p> 

        <p>Numéro SIRET : 789 064 482 00019</p>   	

    </div>

    

    <div style="width:540px; float:left; margin-top:30px;">

    	<table cellpadding="5" cellspacing="0" border="0" width="50%">

        	<tr>

            	<td align="left"><span>Commande n&deg; :</span></td>

                <td align="right"><span><?php echo $row_invoice['orber_num'];?></span></td>

            </tr>

        	<tr>

            	<td align="left"><span>Facture n&deg; :</span></td>

                <td align="right"><span><?php echo $row_invoice['invoice_id'];?></span></td>

            </tr>

        	<tr>

            	<td align="left"><span>Payée le :</span></td>

                <td align="right"><span><?php echo date('Y/m/d h:i:s');?></span></td>

            </tr>

        	<tr>

            	<td align="left"><span>Mode de paiement :</span></td>

                <td align="right"><span>

				<?php 

					echo $type_payment = ($row_invoice['type_payment']== "1") ? "PayPal" : "Crédit";

				?>

                </span></td>

            </tr>

        </table>

    	

    </div> 

    <div style="width:540px; float:left; margin-top:30px;">

    	<table cellpadding="5" cellspacing="0" border="0" width="100%" style="border-collapse:collapse;">

        	<tr>

            	<th width="70%" align="left">Description</th>

            	<th width="15%">Montant HT</th>

            	<!--<th width="15%">Montant TTC</th>-->

            </tr>

            <?php while($row_payment = mysql_fetch_assoc($payment)){?>

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

    

    

</div>









</body>

</html>







