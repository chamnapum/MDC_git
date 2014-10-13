<?php require_once('Connections/magazinducoin.php'); ?>

<?php

$val1 = (rand(1,10));

$val2 = (rand(1,10));

$val_t = $val1 + $val2;

// Load the common classes

require_once('includes/common/KT_common.php');

// Load the tNG classes

require_once('includes/tng/tNG.inc.php');



// Make a transaction dispatcher instance

$tNGs = new tNG_dispatcher("");



// Make unified connection variable

$conn_magazinducoin = new KT_connection($magazinducoin, $database_magazinducoin);



// Start trigger

$formValidation = new tNG_FormValidation();

$formValidation->addField("nom", true, "text", "", "", "", "");

$formValidation->addField("prenom", true, "text", "", "", "", "");

$formValidation->addField("mobile", false, "text", "phone", "", "", "");

$formValidation->addField("email", true, "text", "email", "", "", "");

$formValidation->addField("type", true, "text", "", "", "", "");

$formValidation->addField("message", true, "text", "", "", "", "");

$formValidation->addField("val3", true, "text", "", "", "", "");

$tNGs->prepareValidation($formValidation);

// End trigger



//start Trigger_SendEmail trigger

//remove this line if you want to edit the code by hand

function Trigger_SendEmail(&$tNG) {

  $emailObj = new tNG_Email($tNG);

  $emailObj->setFrom("{email}");

  $emailObj->setTo("support@magasinducoin.fr");

  $emailObj->setCC("");

  $emailObj->setBCC("");

  $emailObj->setSubject("{type}");

  //WriteContent method

  $emailObj->setContent("Bonjour\nVous avez une nouvelle demande de contact: \n\nNom : {nom}\nCompany : {company}\nMobile : {mobile}\nEmail : {email}\nType : {type}\n\n{message}\n");

  $emailObj->setEncoding("UTF-8");

  $emailObj->setFormat("Text");

  $emailObj->setImportance("Normal");

  return $emailObj->Execute();

}

//end Trigger_SendEmail trigger



function Trigger_Send_Email(&$tNG) {

	

	$to2 = "support@magasinducoin.fr";

	$subject2 = $_POST['type'];

	$message2 = '<html>

				<head>

				</head>

				<body>

				<p>Bonjour</p>

				<p></p>

				<p>Vous avez une nouvelle demande de contact:</p>

				<p>Nom : '.$_POST['nom'].'</p>

				<p>Company : '.$_POST['company'].'</p>

				<p>Mobile : '.$_POST['mobile'].'</p>

				<p>email : '.$_POST['email'].'</p>

				<p>Type : '.$_POST['type'].'</p>

				<p>'.$_POST['message'].'</p>

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

	$headers2 .= 'From: <'.$_POST['email'].'>' . "\r\n";

	//$headers .= 'Cc: myboss@example.com' . "\r\n";

	

	$send_contact2 = mail($to2,$subject2,$message2,$headers2);

								

	// Check, if message sent to your email

	// display message "We've recived your information"

	if($send_contact2){

		//echo '<p style="font-size:26px; color:#FA9452;">Votre don est pris en compte.</p>';

	}else {

		echo "ERROR";

	}

}





// Make an insert transaction instance

$ins_contact = new tNG_insert($conn_magazinducoin);

$tNGs->addTransaction($ins_contact);

// Register triggers

$ins_contact->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");

$ins_contact->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);

$ins_contact->registerTrigger("END", "Trigger_Default_Redirect", 99, "contact-form.php?msg=OK");

$ins_contact->registerTrigger("AFTER", "Trigger_Send_Email", 98);

// Add columns

$ins_contact->setTable("contact");

$ins_contact->addColumn("nom", "STRING_TYPE", "POST", "nom");

$ins_contact->addColumn("prenom", "STRING_TYPE", "POST", "prenom");

$ins_contact->addColumn("company", "STRING_TYPE", "POST", "company");

$ins_contact->addColumn("mobile", "STRING_TYPE", "POST", "mobile");

$ins_contact->addColumn("email", "STRING_TYPE", "POST", "email");

$ins_contact->addColumn("type", "STRING_TYPE", "POST", "type");

$ins_contact->addColumn("message", "STRING_TYPE", "POST", "message");

$ins_contact->setPrimaryKey("id", "NUMERIC_TYPE");



// Execute all the registered transactions

$tNGs->executeTransactions();



// Get the transaction recordset

$rscontact = $tNGs->getRecordset("contact");

$row_rscontact = mysql_fetch_assoc($rscontact);

$totalRows_rscontact = mysql_num_rows($rscontact);



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 

	<title> Magasinducoin | Formulaire de contact </title>

    <?php include("modules/head.php"); ?>

   <link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />

 <script src="includes/common/js/base.js" type="text/javascript"></script>

 <script src="includes/common/js/utility.js" type="text/javascript"></script>

 <script src="includes/skins/style.js" type="text/javascript"></script>

<script type="text/javascript">

	$(document).ready(function(){

		$("#KT_Insert1").click(function() {

			var val_total = <?php echo $val_t;?>;

			var val3 = $("#val3").val();

			//alert(val_total);

			if(val_total!=val3){

				//alert("Catch not right");	

				$('.alert').html('Captcha not right');

				return false;

				

			}else{

				//alert('ssssss');

				$('.alert').html('');

				return true;

				$("#form1").submit();

			}

		});	

	});

</script>

 <?php echo $tNGs->displayValidationRules();?>

</head>

<body id="sp" >

<?php include("modules/header.php"); ?>

 

 <div id="content">

	<style>

	.lister h3{

		color:#9D216E;

		font-size:24px;

	}

	.lister p{

		font-size:14px;

		text-align:justify;

		margin:0px 20px 10px 20px;

	}

    </style>

    <style>

	.loginForm label{

		font-weight:bold;

		font-size:13px;

	}

	.loginForm input[type="text"], .loginForm input[type="password"]{

		border: 1px solid #CCCCCC;

		border-radius: 5px 5px 5px 5px;

		height: 16px;

		margin-top: 5px;

		padding-left: 5px;

		width: 180px;

		font-size:13px;

	}

	.loginForm select {

		border: 1px solid #CCCCCC;

		border-radius: 5px 5px 5px 5px;

		height: 25px;

		margin-top: 5px;

		padding-left: 5px;

		width: 185px;

		font-size:13px;

	}

	.loginForm input[type="submit"]{

		background-color: #9D286E;

		border: medium none;

		color: #F8C263;

		cursor: pointer;

		font-size: 18px;

		margin: 0 0 0 5px;

		padding: 0 10px 3px;

	}

	.loginForm td{

		line-height:20px;	

	}

	.loginForm textarea {

		border: 1px solid #CCCCCC;

		border-radius: 5px 5px 5px 5px;

	}

	</style>

    <?php /*?><?php include("modules/form_recherche_header.php"); ?>

    <div class="top reduit">

        <div id="head-menu" style="float:left;">

        	<?php include("assets/menu/main-menu.php"); ?>

        </div>

		<div id="url-menu" style="float:left;">

        <?php include("assets/menu/url_menu.php"); ?>

        </div>

    </div><?php */?>
    
    <style>
    #url_menu_bar{
         margin-top: 10px !important;
    }
    </style>
    <div id="url-menu" style="float:left;">
	<?php include("assets/menu/url_menu.php"); ?>
    </div>

    

    <div class="clear"></div>



    <div class="lister top" style="float:left; background:#F2EFEF; width:100%;">

        <h3>Contact</h3>

      <?php

	echo $tNGs->getErrorMsg();

?>

 <?php if(isset($_GET['msg']) and $_GET['msg'] == 'OK'){ ?>

                        <div class="success" style=" background-color: #D1F3D3;

    border: 1px solid #A4E684;

    color: #565656;

    margin: 5px 20px 10px;

    padding: 12px 10px 10px 35px;

    width: 90%;">Votre message a été envoyé avec succès !</div>

      <?php } ?>

	<form method="post" id="form1" class="loginForm" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">

      <table cellpadding="2" cellspacing="0" style="margin-left:20px; margin-top:20px;" border="0">

        <tr>

          <td class="KT_th"><label for="nom">Nom :</label> <span style="color:red;">*</span><br />

		  <input type="text" name="nom" id="nom" value="<?php echo KT_escapeAttribute($row_rscontact['nom']); ?>" size="32" />

          <?php echo $tNGs->displayFieldHint("nom");?> <?php echo $tNGs->displayFieldError("contact", "nom"); ?></td>

          

          <td class="KT_th"><label for="prenom">Prenom :</label> <span style="color:red;">*</span><br />

          <input type="text" name="prenom" id="prenom" value="<?php echo KT_escapeAttribute($row_rscontact['prenom']); ?>" size="32" />

          <?php echo $tNGs->displayFieldHint("prenom");?> <?php echo $tNGs->displayFieldError("contact", "prenom"); ?></td>

        </tr>

        

        

        <tr>

          <td class="KT_th"><label for="email">Adresse email:</label> <span style="color:red;">*</span><br />

          <input type="text" name="email" id="email" value="<?php echo KT_escapeAttribute($row_rscontact['email']); ?>" size="32" />

            <?php //echo $tNGs->displayFieldHint("email");?> <?php echo $tNGs->displayFieldError("contact", "email"); ?></td>

          <td class="KT_th"><label for="mobile">Téléphone:</label><br />

          <input type="text" name="mobile" id="mobile" value="<?php echo KT_escapeAttribute($row_rscontact['mobile']); ?>" size="32" />

            <?php /*?><?php echo $tNGs->displayFieldHint("mobile");?><?php */?> <?php echo $tNGs->displayFieldError("contact", "mobile"); ?></td>

        </tr>

        

        <tr>

          <td class="KT_th"><label for="type">Type de demande:</label><br />

          <select name="type" id="type">

                <option value="Passer une commande" <?php if (!(strcmp(1,$_GET['t']))) {echo "SELECTED";} ?>>Une question</option>

                <option value="Suite a une commande" <?php if (!(strcmp(2,$_GET['t']))) {echo "SELECTED";} ?>>Suite à une inscription</option>

                <option value="Une suggestion" <?php if (!(strcmp(3,$_GET['t']))) {echo "SELECTED";} ?>>Une suggestion</option>

                <option value="Devenez affilié" <?php if (!(strcmp(4,$_GET['t']))) {echo "SELECTED";} ?>>Devenir affilié</option>

                <option value="Devenir partenaire" <?php if (!(strcmp(5,$_GET['t']))) {echo "SELECTED";} ?>>Devenir partenaire</option>

          </select>

            <?php echo $tNGs->displayFieldError("contact", "type"); ?></td>

          <td class="KT_th"><label for="company">Entreprise:</label><br />

          <input type="text" name="company" id="company" value="<?php echo KT_escapeAttribute($row_rscontact['company']); ?>" size="32" />

            <?php echo $tNGs->displayFieldHint("company");?> <?php echo $tNGs->displayFieldError("contact", "company"); ?></td>

        </tr>

        <tr>

          <td class="KT_th" colspan="2"><label for="message">Message:</label> <span style="color:red;">*</span><br />

          <textarea name="message" id="message" cols="50" rows="5"><?php echo KT_escapeAttribute($row_rscontact['message']); ?></textarea>

            <?php echo $tNGs->displayFieldHint("message");?> <?php echo $tNGs->displayFieldError("contact", "message"); ?></td>

        </tr>

        <tr>

        	<td class="KT_th" colspan="2">

            	<label for="catch">( <?php echo $val1;?> + <?php echo $val2;?> ) <span style="color:red;">*</span></label> = <input type="text" id="val3" name="val3" value="" style="width:78px;"/>

            	<?php echo $tNGs->displayFieldHint("val3");?> 

                <span class="alert" style="color:#cc0000; font-weight:bold; clear:left;"></span>

            </td>

        </tr>

        <tr>

          <td colspan="2" align="left"><input type="submit" name="KT_Insert1" id="KT_Insert1" value="Envoyer" /></td>

        </tr>

      </table>

    </form>

    <p>&nbsp;</p>

    </div>



</div>



<div class="clear"></div>



<div id="footer">

    <?php include("modules/footer.php"); ?>	

</div>



</body>

</html>

