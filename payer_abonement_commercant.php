<?php require_once('Connections/magazinducoin.php'); ?>

<?php

	if(!empty($_POST['code_promo'])){

		$query_code = "SELECT reduction FROM code_promo WHERE code = '".$_POST['code_promo']."' AND valide = 1";

		$code = mysql_query($query_code, $magazinducoin) or die(mysql_error());

		$row_code = mysql_fetch_assoc($code);

		$nb = mysql_num_rows($code);

		if($nb>0){

			//die(">0");

			$reduction = $row_code['reduction'];

		}

		else{

			//die("<0");

			header('Location: payer_abonement.php?error=code_promo');

			exit();

		}

	}



	if($_POST['credit_p']){

		$query_code = "SELECT * FROM sale_credit WHERE id=".$_POST['credit_p'];

		$code = mysql_query($query_code, $magazinducoin) or die(mysql_error());

		$row_code = mysql_fetch_assoc($code);

		$credit = $row_code['credit'];

		$credit_plus = $row_code['credit_plus'];

		$credit_total = $row_code['credit'] + $row_code['credit_plus'];

	}else{

		$credit = $_POST['credit'];

	}



	$query_re = "SELECT * FROM abonement WHERE id_user = ".$_POST['id']." AND active = 1";

	$re = mysql_query($query_re, $magazinducoin) or die(mysql_error());

	$row_re = mysql_fetch_assoc($re);

	if($row_re['date_echeance']){

		$date = explode('-',$row_re['date_echeance']);

		$date_abonement = date('Y-m-d',mktime(0,0,0,$date[1],$date[2]+1,$date[0]));

		$date_echeance = date('Y-m-d',mktime(0,0,0,$date[1]+2,$date[2]+1,$date[0]));

		}

	else {

		$date_abonement = date('Y-m-d',mktime(0,0,0,date('m'),date('d'),date('Y')));

		$date_echeance = date('Y-m-d',mktime(0,0,0,date('m')+2,date('d'),date('Y')));

	}



$credit_total = (isset($credit_total)) ? $credit_total : $credit;

if(!isset($reduction)) $reduction = 0;



$radio = (isset($credit)) ? $credit : 30;

$prix = $radio - (($radio* $reduction)/100);	



if($_POST['paiement_p']){

	mysql_query("INSERT INTO abonement (id_user, date_abonement, date_echeance, mode_payement, active, montant, code_promo, credit_plus) VALUES (".$_POST['id']." ,'$date_abonement', '$date_echeance','".$_POST['paiement_p']."', 0, '".$_POST['credit']."','".$reduction."','".$credit_total."')", $magazinducoin) or die(mysql_error());

	

	$mail = "SELECT nom, prenom, email FROM utilisateur WHERE id = '".$_POST['id']."'";

	$query_mail = mysql_query($mail) or die(mysql_error());

	$row_mail = mysql_fetch_assoc($query_mail);

	

	$to2 = $row_mail['email'];

	$subject2 = 'Nouvelle commande de credit';

	$message2 = '<html>

				<head>

				<style>

				.heading {border: solid 1px #000000;}

				</style>

				</head>

				<body>

				<p>Bonjour</p>

				<p>Un client a commander '.$_POST['credit'].' € de crédit</p>

				<p>'.$row_mail['nom'].' '.$row_mail['prenom'].'</p>

				<p>'.$row_mail['email'].'</p>

				<p></p>

				<p><a href="http://magasinducoin.fr/" target="_blank">

					<img src="http://magasinducoin.fr/assets/images/logo.png" alt=""/>

				</a></p>

				</body>

				</html>

				';

	

	// Always set content-type when sending HTML email

	$headers2 = "MIME-Version: 1.0" . "\r\n";

	$headers2 .= "Content-type:text/html; charset=UTF-8" . "\r\n";

	

	// More headers

	$headers2 .= 'From: <noreply@magasinducoin.fr>' . "\r\n";

	$headers .= 'Cc: contact@magasinducoin.fr' . "\r\n";

	

	$send_contact2 = mail($to2,$subject2,$message2,$headers2);

								

	// Check, if message sent to your email

	// display message "We've recived your information"

	if($send_contact2){

		header('Location: mon-abonnement.php' );

	}else {

		echo "ERROR";

	}

	

}





/*echo "<script>alert('".$prix."');</script>";*/

if($_POST['paiement'] == 'paypal'){

	

	

	$query_user = "SELECT * FROM utilisateur WHERE id = ".$_POST['id'];

	$user = mysql_query($query_user, $magazinducoin) or die(mysql_error());

	$row_user = mysql_fetch_assoc($user);

	//echo "('".$row_user['id']."&date_abonement=".$date_abonement."&date_echeance=".$date_echeance."&prix=".$prix."&reduction=".$reduction."&credit_total=".$credit_total."')";

	

	

	define('PAYPAL_EMAIL','paiement@magasinducoin.com');

	define('SECUREURL','http://magasinducoin.fr/');

	//define('SECUREURL','http://localhost:90/Magasinducoin.v9/');

	define('PAYPAL_DEBUG','1');

	

	//$url = "https://www.paypal.com/cgi-bin/webscr";

	$url = "https://www.sandbox.paypal.com/cgi-bin/webscr";

	

	$tax_total = $prix;

	$post_variables = Array(

	"cmd" => "_ext-enter",

	"redirect_cmd" => "_xclick",

	"upload" => "1",

	"business" => PAYPAL_EMAIL,

	"receiver_email" => PAYPAL_EMAIL,

	"item_name" => "Commande numero: ". $row_user['id'],

	"order_id" => $row_user['id'],

	/*"invoice" => $db->f("order_number"),*/

	"amount" => $prix,

	"shipping" => 0,

	"currency_code" => 'EUR',

	

	"address_override" => "1",

	"first_name" => $row_user['prenom'],

	"last_name" => $row_user['nom'],

	"address1" => $row_user['adresse'],

	"zip" => $row_user['code_postal'],

	"city" => getVilleById($row_user['ville']),

	"state" => getRegionById($row_user['region']),

	"country" => 'FR',

	"email" => $row_user['email'],

	"night_phone_b" => $row_user['telephone'],

	"cpp_header_image" => SECUREURL . "template/images/logo.png",

	

	"return" => SECUREURL ."faire_activation.php?id=".$row_user['id']."&date_abonement=".$date_abonement."&date_echeance=".$date_echeance."&prix=".$prix."&reduction=".$reduction."&credit_total=".$credit_total,

	"notify_url" => SECUREURL ."notify.php",

	"cancel_return" => SECUREURL ."payer_abonement.php",

	"undefined_quantity" => "0",

	

	"test_ipn" => PAYPAL_DEBUG,

	"pal" => "NRUBJXESJTY24",

	"no_shipping" => "1",

	"no_note" => "1"

	);

	

	

	$query_string = "?";

	foreach( $post_variables as $name => $value ) {

	$query_string .= $name. "=" . urlencode($value) ."&";

	}

	//echo $url . $query_string ;

	header('Location: '. $url . $query_string );

}

else {

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 

	<title>Magasinducoin | Achat crédit publicité </title>

    <?php include("modules/head.php"); ?>

</head>

<style>

input[type=submit] {

	background-color: #9D286E;

	border: 0 none;

	color: #F8C263;

	font-size: 12px;

	font-weight: bold;

	padding: 5px 10px;

	cursor: pointer;

}

</style>

<body id="sp">

<?php include("modules/header.php"); ?>

<div id="content">  

<?php include("modules/member_menu.php"); ?>

<?php include("modules/credit.php"); ?> 

	<div style="padding-left:25px;">

		<h3>Paiement - Etape 2</h3>

    </div>

    <div style="width:50%; margin:0px auto;"> 

    <table width="300" border="0" cellspacing="2" cellpadding="2">

        <tr>

            <th scope="row" align="right">Sous-total&nbsp;</th>

            <td><?php echo $prix; ?>&nbsp;&euro;</td>

        </tr>

        <tr>

            <th scope="row" align="right">Réduction de code promo&nbsp;</th>

            <td><?php echo number_format($reduction,2,","," "); ?>&nbsp;%</td>

        </tr>

        <tr>

            <th scope="row" align="right">Total à payer&nbsp;</th>

            <td><?php echo number_format($prix,2,","," "); ?>&nbsp;&euro;</td>

        </tr>

    </table>

	<?php

		if($_POST['paiement']=='cheque'){

			echo '<form action="payer_abonement_commercant.php" method="post">

				<h4>Paiement par Chèque</h4>

				Envoyer le chèque de la somme de '.$prix .' &euro; à l\'adresse suivante : Magasinducoin BP60068 57302 HAGONDANGE Cedex02<br><br />

				<input type="hidden" name="id" id="id" value="'.$_POST['id'].'">

				<input type="hidden" name="credit_p" id="credit_p" value="'.$_POST['credit_p'].'">

				<input type="hidden" name="credit" id="credit" value="'.$prix.'">

				<input type="hidden" name="code_promo" id="code_promo" value="'.$_POST['code_promo'].'">

				<input type="hidden" name="paiement_p" id="paiement_p" value="cheque">

				<input name="terminer" type="submit" value="Terminer" />

			</form>

			';

		}elseif($_POST['paiement']=='virement'){

			echo '

			<form action="payer_abonement_commercant.php" method="post">

				<h4>Virement bancaire</h4>

				Total de la commande : '.$prix.' &euro;

				Pour accélérer l\'activation de votre crédit publicité, nous vous prions de noter le numéro de votre commande sur le reçu bancaire de virement et de l\'envoyer par fax au 03.68.38.81.21 ou par email à sales@magasinducoin.fr<br><br />

				<input type="hidden" name="id" id="id" value="'.$_POST['id'].'">

				<input type="hidden" name="credit_p" id="credit_p" value="'.$_POST['credit_p'].'">

				<input type="hidden" name="credit" id="credit" value="'.$prix.'">

				<input type="hidden" name="code_promo" id="code_promo" value="'.$_POST['code_promo'].'">

				<input type="hidden" name="paiement_p" id="paiement_p" value="virement">

				<input name="terminer" type="submit" value="Terminer la commande" />

			</form>

			';

		}

	?>

    

    

<?php /*?>	<?php

       switch($_POST['paiement']){

    case 'cheque':

        echo '<h4>Paiement par Chèque</h4>

    Envoyer le chèque de la somme de '.$prix .' &euro; à l\'adresse suivante : Magasinducoin BP60068 57302 HAGONDANGE Cedex02<br><br />

    

    <input name="terminer" type="button" value="Terminer" onclick="window.location=\'mon-abonnement.php\'" />';

        break;

        

    default:

        echo '<h4>Virement bancaire</h4>

        Total de la commande : '.$prix.' &euro;

    Pour accélérer l\'activation de votre crédit publicité, nous vous prions de noter le numéro de votre commande sur le reçu bancaire de virement et de l\'envoyer par fax au 03.68.38.81.21 ou par email à sales@magasinducoin.fr<br><br />

    

    <input name="terminer" type="button" value="Terminer la commande"  onclick="window.location=\'mon-abonnement.php\'" />';

        break;

    }

    ?><?php */?>

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

}

?>

