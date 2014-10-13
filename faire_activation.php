<?php require_once('Connections/magazinducoin.php'); ?>

<?php

mysql_select_db($database_magazinducoin, $magazinducoin);

$query = "UPDATE utilisateur SET activate = 1 WHERE id = ".$_GET['id'];

$u = mysql_query($query, $magazinducoin) or die(mysql_error());

//echo $_GET['id'].'<br/>';

//echo $_GET['date_abonement'].'<br/>';

//echo $_GET['date_echeance'].'<br/>';

//echo $_GET['prix'].'<br/>';

//echo $_GET['reduction'].'<br/>';

//echo $_GET['credit_total'].'<br/>';



	$test =  mysql_query("INSERT INTO abonement (id_user, date_abonement, date_echeance, mode_payement, active, montant, code_promo, credit_plus) VALUES ('".$_GET['id']."' ,'".$_GET['date_abonement']."', '".$_GET['date_echeance']."','paypal', '1', '".$_GET['prix']."','".$_GET['reduction']."','".$_GET['credit_total']."')", $magazinducoin) or die(mysql_error());

	//echo $test;

	

	$query = "UPDATE utilisateur SET activate = 1, credit = credit + '".$_GET['credit_total']."'  WHERE id = ".$_GET['id'];

	$p = mysql_query($query, $magazinducoin) or die(mysql_error());

	

	$mail = "SELECT nom, prenom, email FROM utilisateur WHERE id = '".$_GET['id']."'";

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

				<p>Un client a commander '.$_GET['prix'].' € de crédit</p>

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



//header('Location: authetification.php?info=ACTIVATED' );

?>

