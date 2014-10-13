<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Sending PDF Attachment</title>
    <link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css"/>
    <link rel="stylesheet" href="css/jquery.selectBox.css" type="text/css"/>
    
    <script src="js/jquery-1.6.min.js" type="text/javascript"></script>
	<script src="js/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
    <script src="js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
    <script>
        $(document).ready(function(){
            $("#myform").validationEngine();
        });
    </script>
	<style type="text/css">
		html, body, h1, h2, h3, h4, h5, h6, p, span, ul, li, div, form, input, select, textarea, button {margin:0; padding:0;}
		ul {list-style:none;}
		a, a:hover {text-decoration:none; outline:0;}
		a img {border:0;}
		
		body {font:12px/16px Verdana, Arial, sans-serif; background:#fff;}
		#container {width:400px; margin:10px auto; padding:10px; overflow:hidden; border:1px solid #000; border-radius:10px; -moz-border-radius:10px; -webkit-border-radius:10px; background:#F9F9F9;}
		#container h1 {margin-bottom:20px; font-size:40px; line-height:40px; font-family:'HelveticaNeue-Light', 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight:normal;}
		.message {margin-bottom:10px; padding:5px;}
		.success {color:#4F8A10; border:1px solid #4F8A10; background:#DFF2BF;}
		.error {color:#D8000C; border:1px solid #D8000C; background:#FFBABA;}
		label {display:block; margin-bottom:3px; cursor:pointer;}
		.input, textarea, select, button {display:block; width:390px; margin-bottom:10px; padding:3px; font:22px/22px 'HelveticaNeue-Light', 'Helvetica Neue', Helvetica, Arial, sans-serif; border:1px solid #CCC; border-top-width:2px;}
		textarea {font-size:13px; line-height:16px;}
		select {width:396px;}
		button {float:right; width:auto; margin-bottom:0; padding:3px 30px; cursor:pointer; font-size:16px; border:1px solid #999; border-bottom-width:2px; border-radius:5px; -moz-border-radius:5px; -webkit-border-radius:5px; background:#EEE;}
		button:active {border-bottom-width:1px; padding:4px 30px 3px; background:#E9E9E9;}
	</style>
</head>
<body>
<div id="container">

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
		$group = $_GET['group'];
		$idSalary = $_GET['id_salarie'];
		$valuedate = $_GET['valuegetdates'];
		$getpage = $_GET['pagename'];
		// copy file pdf from  http://archi-graphi.com/arcancianev/pdf.php to new file sejour.pdf
		$ourFileName = "Planning agence Report.pdf";
		$ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");		
		$htmlFile = file_get_contents('http://www.archi-graphi.com/activite/v1/newpdfplan.php?pagename='.$getpage.'&group='.$group.'&id_salarie='.$idSalary.'&valuegetdates='.$valuedate);
		//$pdfHtml = ('sejour.pdf');
	    file_put_contents($ourFileName,$htmlFile);
		fclose($ourFileHandle);
		// end copy
	if(isset($_POST['submit'])){
		require_once('config.php');	
		require_once('class.phpmailer.php');		
		$mail  = new PHPMailer(); // defaults to using php "mail()"		
		$body = $_POST['about'];
				
		if(Host<>'mail.yourdomain.com'){
			$mail->IsSMTP(); // telling the class to use SMTP
			$mail->SMTPAuth      = true;                  // enable SMTP authentication
			$mail->SMTPKeepAlive = true;                  // SMTP connection will not close after each email sent
			$mail->Host          = Host; // sets the SMTP server
			$mail->Port          = Port;                    // set the SMTP port for the GMAIL server
			$mail->Username      = Username; // SMTP account username
			$mail->Password      = Password;        // SMTP account password
		}
		$mail->AddReplyTo(Email,Name);		
		$mail->SetFrom(EmailFrom,NameFrom);		
		$mail->AddReplyTo(Email,Name);	
		
		$address = $_POST['email'];
		$mail->AddAddress($address, $address);		
		$mail->Subject  = $_POST['subject'];
		
		$mail->AltBody  = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test		
		$mail->MsgHTML($body);		
		$mail->AddAttachment("Planning agence Report.pdf");      // attachment		
		if(!$mail->Send()) {
		  echo "Mailer Error: " . $mail->ErrorInfo;
		} else {
		  echo " Message envoy&eacute;!";
		}
}
?>
	</div>

</body>
</html>