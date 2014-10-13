<?php
$mail = "SELECT * FROM mail_send WHERE id = '19'";

	//$query_mail = mysql_query($mail) or die(mysql_error());

	//$row_mail = mysql_fetch_assoc($query_mail);

	

	//$sub_content=code_coupon($row_mail['subject'],$nom,$nom.' '.$prenom,$title,$nom_magasin,$categorie);

	

	$to2 = 'validation@magasinducoin.fr';//

	$subject2 = 'testing';

	//$content=code_coupon($row_mail['content'],$nom,$nom.' '.$prenom,$title,$nom_magasin,$categorie);

	$message2 = '<html>

				<head>

				<style>

				.heading {border: solid 1px #000000;}

				</style>

				</head>

				<body>
				<p>hello world</p>
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

	$headers2 .= 'From: <validation@magasinducoin.fr>' . "\r\n";

	//$headers .= 'Cc: myboss@example.com' . "\r\n";

	

	$send_contact2 = mail($to2,($subject2),($message2),$headers2);

								

	// Check, if message sent to your email

	// display message "We've recived your information"

	if($send_contact2){

		echo '<p style="font-size:26px; color:#FA9452;">Votre don est pris en compte.</p>';

	}else {

		echo "ERROR";

	}
?>