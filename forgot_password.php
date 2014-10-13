<?php require_once('Connections/magazinducoin.php'); ?>
<?php

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

$formValidation->addField("email", true, "text", "email", "", "", "");

$tNGs->prepareValidation($formValidation);

// End trigger

//start Trigger_ForgotPasswordCheckEmail trigger

//remove this line if you want to edit the code by hand

function Trigger_ForgotPasswordCheckEmail(&$tNG) {

  return Trigger_ForgotPassword_CheckEmail($tNG);

}

//end Trigger_ForgotPasswordCheckEmail trigger

//start Trigger_ForgotPassword_Email trigger

//remove this line if you want to edit the code by hand

function Trigger_ForgotPassword_Email(&$tNG) {

  $emailObj = new tNG_Email($tNG);

  $emailObj->setFrom("{KT_defaultSender}");

  $emailObj->setTo("{email}");

  $emailObj->setCC("");

  $emailObj->setBCC("");

  $emailObj->setSubject("Vos informations de connexion");

  //FromFile method

  $emailObj->setContentFile("includes/mailtemplates/forgot.html");

  $emailObj->setEncoding("ISO-8859-1");

  $emailObj->setFormat("HTML/Text");

  $emailObj->setImportance("Normal");

  return $emailObj->Execute();

}

//end Trigger_ForgotPassword_Email trigger

// Make an update transaction instance

$forgotpass_transaction = new tNG_update($conn_magazinducoin);

$tNGs->addTransaction($forgotpass_transaction);

// Register triggers

$forgotpass_transaction->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");

$forgotpass_transaction->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);

$forgotpass_transaction->registerTrigger("BEFORE", "Trigger_ForgotPasswordCheckEmail", 20);

$forgotpass_transaction->registerTrigger("AFTER", "Trigger_ForgotPassword_Email", 1);

$forgotpass_transaction->registerTrigger("END", "Trigger_Default_Redirect", 99, "{kt_login_redirect}");

// Add columns

$forgotpass_transaction->setTable("utilisateur");

$forgotpass_transaction->addColumn("email", "STRING_TYPE", "POST", "email");

$forgotpass_transaction->setPrimaryKey("email", "STRING_TYPE", "POST", "email");


// Execute all the registered transactions

$tNGs->executeTransactions();


// Get the transaction recordset

$rsutilisateur = $tNGs->getRecordset("utilisateur");

$row_rsutilisateur = mysql_fetch_assoc($rsutilisateur);

$totalRows_rsutilisateur = mysql_num_rows($rsutilisateur);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 

	<title>Alhodhod | Interpreteur des rêves </title>

    <?php include("modules/head.php"); ?>

<link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />

<script src="includes/common/js/base.js" type="text/javascript"></script>

<script src="includes/common/js/utility.js" type="text/javascript"></script>

<script src="includes/skins/style.js" type="text/javascript"></script>

<?php echo $tNGs->displayValidationRules();?>

</head>
<style>
#url_menu_bar{
	 margin-top: 10px !important;
}
</style>
<body id="sp">
<?php include("modules/header.php"); ?>
<div id="content">
    <?php /*?><?php include("modules/form_recherche_header.php"); ?>
    <div class="top reduit">
        <div id="head-menu" style="float:left;">
        	<?php include("assets/menu/main-menu.php"); ?>
        </div>
		<div id="url-menu" style="float:left;">
        <?php include("assets/menu/url_menu.php"); ?>
        </div>
    </div><?php */?>
    <div id="url-menu" style="float:left;">
	<?php include("assets/menu/url_menu.php"); ?>
    </div>
    
    <div class="clear"></div>
    <div style="text-align:center;">
        <h3>Mot de passe oublié</h3>
        
        <?php
        
        echo $tNGs->getErrorMsg();
        
        ?><form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
        
            <p><label for="email"> Email:</label>
            
            <input type="text" name="email" id="email" value="<?php echo KT_escapeAttribute($row_rsutilisateur['email']); ?>" size="32" />
            
            <?php echo $tNGs->displayFieldHint("email");?>
            
            <?php echo $tNGs->displayFieldError("utilisateur", "email"); ?>
            
            </p>
            
            <div >
            
            <input type="submit" name="KT_Update1" id="KT_Update1" value="Envoyer" />
            
            </div>
        </form>
        
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