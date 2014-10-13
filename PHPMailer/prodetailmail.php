

	<form method="post" action="" id="myform">
			<label for="name">Objet:</label>
			<input type="text" name="subject" id="subject" class="input validate[required] TextInput" />
			
			<label for="email">Destinataire:</label>
			<input type="text" name="email" id="email" class="input validate[required,custom[email]] text-input" />
			
			
			
			<label for="about">Message:</label>
			<textarea name="about" id="about" rows="4" cols="40" class="validate[required] text-input"></textarea>
			
			<p><input type="submit" name="submit" value="Envoyer"  class="btnValider"/></p>
		</form>
	<?php
		
if(isset($_POST['submit'])){
		require_once('config.php');	
		require_once('class.phpmailer.php');		
		$mail  = new PHPMailer(); // defaults to using php "mail()"		
		$body = $_POST['about'];
				
		if(Host<>'smtp.mandrillapp.com'){
			$mail->IsSMTP(); // telling the class to use SMTP
			$mail->SMTPAuth      = true;                  // enable SMTP authentication
			$mail->SMTPKeepAlive = true;                  // SMTP connection will not close after each email sent
			$mail->Host          = Host; // sets the SMTP server
			$mail->Port          = Port;                    // set the SMTP port for the GMAIL server
			$mail->Username      = Username; // SMTP account username
			$mail->Password      = Password;        // SMTP account password
		}
		//$mail->AddReplyTo(Email,Name);		
		$mail->SetFrom(EmailFrom,NameFrom);		
		//$mail->AddReplyTo(Email,Name);
		
		$address = $_POST['email'];
		$mail->AddAddress($address, $address);		
		$mail->Subject  = $_POST['subject'];
		
		$mail->AltBody  = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test		
		$mail->MsgHTML($body);		
		//$mail->AddAttachment("Testing.pdf");      // attachment		
		if(!$mail->Send()) {
		  echo "Mailer Error: " . $mail->ErrorInfo;
		} else {
		  echo " Message envoy&eacute;!";
		}
}
?>
