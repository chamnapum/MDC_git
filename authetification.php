<?php session_start();?>
<?php require_once('Connections/magazinducoin.php'); ?>
<?php
if (array_key_exists("login", $_GET)) {
    $oauth_provider = $_GET['oauth_provider'];
    if ($oauth_provider == 'twitter') {
		echo '<script type="text/javascript">window.location="login-twitter.php";</script>';
        //header("Location:login-twitter.php");
    } else if ($oauth_provider == 'facebook') {
		echo '<script type="text/javascript">window.location="login-facebook.php";</script>';
        //header("Location:login-facebook.php");
    }
}
?>
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
$formValidation->addField("kt_login_user", true, "text", "", "", "", "");
$formValidation->addField("kt_login_password", true, "text", "", "", "", "");
$tNGs->prepareValidation($formValidation);
// End trigger

// Make a login transaction instance
$loginTransaction = new tNG_login($conn_magazinducoin);
$tNGs->addTransaction($loginTransaction);
if ($_REQUEST['action']=='1') {
// Register triggers
$loginTransaction->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "GET", "kt_login1");
$loginTransaction->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$loginTransaction->registerTrigger("END", "Trigger_Default_Redirect", 99, "membre.html");
$loginTransaction->registerTrigger("AFTER", "Trigger_time", 97);
// Add columns
$loginTransaction->addColumn("kt_login_user", "STRING_TYPE", "GET", "kt_login_user","Adresse mail");
$loginTransaction->addColumn("kt_login_password", "STRING_TYPE", "GET", "kt_login_password");
$loginTransaction->addColumn("kt_login_rememberme", "CHECKBOX_1_0_TYPE", "GET", "kt_login_rememberme", "0");
// End of login transaction instance
}else{
// Register triggers
$loginTransaction->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "kt_login1");
$loginTransaction->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$loginTransaction->registerTrigger("END", "Trigger_Default_Redirect", 99, "membre.html");
$loginTransaction->registerTrigger("AFTER", "Trigger_time", 97);
// Add columns
$loginTransaction->addColumn("kt_login_user", "STRING_TYPE", "POST", "kt_login_user","Adresse mail");
$loginTransaction->addColumn("kt_login_password", "STRING_TYPE", "POST", "kt_login_password");
$loginTransaction->addColumn("kt_login_rememberme", "CHECKBOX_1_0_TYPE", "POST", "kt_login_rememberme", "0");
// End of login transaction instance	
}

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rscustom = $tNGs->getRecordset("custom");
$row_rscustom = mysql_fetch_assoc($rscustom);
$totalRows_rscustom = mysql_num_rows($rscustom);

function Trigger_time(){
	$_SESSION['start'] = time();
	$_SESSION['expire'] = $_SESSION['start'] + (15 * 60);
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 
<title><?php echo $xml-> Authentification ;?> </title>
    <?php include("modules/head.php"); ?>
<link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script src="includes/common/js/base.js" type="text/javascript"></script>
<script src="includes/common/js/utility.js" type="text/javascript"></script>
<script src="includes/skins/style.js" type="text/javascript"></script>
<?php echo $tNGs->displayValidationRules();?>
</head>
<body id="sp">
<?php include("modules/header.php"); ?>


  		<div id="content" class="authentification choix_inscription">
  		   <!--  start Top -->
            <?php /*?><?php include("modules/form_recherche_header.php"); ?>
            <div class="top reduit">
                <div id="head-menu" style="float:left;">
                	<?php include("assets/menu/main-menu.php"); ?>
                </div>
            </div><?php */?>
            <div class="clear"></div>
       	 <!--  End Top -->  		
                   
<style>
.faceboo_register{
	width:237px;
	height:44px;
	background:url(assets/images/fb-connect.png) no-repeat;
	float:left;
	margin-top:20px;
	margin-left:40px;
}
.faceboo_register:hover{
	background:url(assets/images/fb-connect_over.png) no-repeat;
}
.twitter_register{
	width:237px;
	height:44px;
	background:url(assets/images/tw-connect.png) no-repeat;
	float:left;
	margin-top:20px;
	margin-left:40px;
}
.twitter_register:hover{
	background:url(assets/images/tw-connect_over.png) no-repeat;
}

</style>		
        
        <div style="width:100%; float:left;" class="loginForm">
            <div style="width:33%; float:left; margin-left:2%;">
                <h3>Se connecter :</h3><br /><br />
                <a href="?login&oauth_provider=facebook" class="faceboo_register"></a>
                <a href="?login&oauth_provider=twitter" class="twitter_register"></a>
            </div>
            <div style="width:62%; float:left; border-left:1px dotted #666666; padding-left:2%;" class="loginForm">
            	<h3><?php echo $xml-> Authentification ;?> :</h3>
            	<div style="min-height:250px;">
                
                      <p>
                        <?php
            
                            echo $tNGs->getLoginMsg();
                        ?>
                        <?php
                            //echo $tNGs->getErrorMsg();
                    
                        ?> 
                        <div class="errorMSG">
                            <?php echo $tNGs->displayFieldError("custom", "kt_login_user"); ?>
                            <?php echo $tNGs->displayFieldError("custom", "kt_login_password"); ?>
                            <?php echo $tNGs->displayFieldError("custom", "kt_login_rememberme"); ?>
                        </div>
                        </p>
                        <form method="post" id="form1" class="KT_tngformerror" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
                         <div id="login_to" >
                     
                            <input type="text" name="kt_login_user" id="kt_login_user" value="<?php echo KT_escapeAttribute($row_rscustom['kt_login_user']); ?>" size="32" placeholder="Adresse mail" />
                            
                            <input type="password" name="kt_login_password" id="kt_login_password" placeholder="Mot de passe"  size="32" />
                            <label for="kt_login_rememberme" id="garder_session"><?php echo $xml->save_session ; ?>:</label>
                            <input  <?php if (!(strcmp(KT_escapeAttribute($row_rscustom['kt_login_rememberme']),"1"))) {echo "checked";} ?> type="checkbox" name="kt_login_rememberme" id="kt_login_rememberme" value="1" />
                            
                              
                          <input type="submit" name="kt_login1" class="btn_login" id="kt_login1" value="Login" />
                             <!--<input type="image" src="template/images/login2.png" class="btn_login" name="kt_login1" id="kt_login1" value="Login" />-->
                        </div>
                      <a href="forgot_password.php"><b style="padding-left:5px;"><?php echo $xml->Mot_passe_oublie ; ?></b></a>
                            <div id="creercompte"> 
                               <ul>
                                <li><span style="color:#b35a91;"><?php echo $xml->Nouv_membre ; ?>?</span>
                                    <span><a href="inscriptionu_all.php"><u><?php echo $xml->creer_compte ; ?></u></a></span>
                                </li>
                               </ul>
                            </div>
                      </form>
                      </div>
            
            
            </div>
        </div>
        
        
        
        
        
        
       
</div>
</div>
<div style="clear:both;"></div>
<div id="footer">
<?php include("modules/footer.php"); ?>
</div>
</body>
</html>