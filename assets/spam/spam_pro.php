<?php require_once('../../Connections/connection.php'); ?>
<?php
$magazinducoin = mysql_pconnect($hostname_magazinducoin, $username_magazinducoin, $password_magazinducoin); 
mysql_select_db($database_magazinducoin, $magazinducoin);

mysql_query("SET character_set_results=utf8",$magazinducoin);
mb_language('uni');
mb_internal_encoding('UTF-8');
mysql_query("set names 'utf8'" , $magazinducoin); 
$dbname=mysql_select_db($database_magazinducoin, $magazinducoin) or die("Can not select MySQL DB");

$nom = $_REQUEST['nom'];
$email = $_REQUEST['email'];
$maessage = $_REQUEST['maessage'];
$id_mag = $_REQUEST['id_mag'];
$id_pro = $_REQUEST['id_pro'];

	$to2 = 'abuse@magasinducoin.com';
	$subject2 = 'Signaler un contenu abusif';
	$message2 = '<html>
				<head>
				<style>
				.heading {border: solid 1px #000000;}
				</style>
				</head>
				<body>
				<p>'.$maessage.'<p>
				</body>
				</html>
				';
	
	// Always set content-type when sending HTML email
	$headers2 = "MIME-Version: 1.0" . "\r\n";
	$headers2 .= "Content-type:text/html; charset=UTF-8" . "\r\n";
	
	// More headers
	$headers2 .= 'From: <'.$email.'>' . "\r\n";
	//$headers .= 'Cc: myboss@example.com' . "\r\n";
	
	$send_contact2 = mail($to2,($subject2),($message2),$headers2);
								
	// Check, if message sent to your email
	// display message "We've recived your information"
	if($send_contact2){
		//echo '<p style="font-size:26px; color:#FA9452;">Votre don est pris en compte.</p>';
	}else {
		echo "ERROR";
	}

$sql_shopper_owner  = "INSERT INTO spam_email(nom,email,maessage,date,id_magazin,id_produit) VALUES ('".$nom."','".$email."','".$maessage."',NOW(),'".$id_mag."','".$id_pro."')";
$result_shopper_owner   = mysql_query($sql_shopper_owner  ) or die (mysql_error());

?>
