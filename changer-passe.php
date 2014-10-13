<?php require_once('Connections/magazinducoin.php'); ?>
<?php
// Load the common classes
require_once('includes/common/KT_common.php');

// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

// Load the KT_back class
require_once('includes/nxt/KT_back.php');

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("");

// Make unified connection variable
$conn_magazinducoin = new KT_connection($magazinducoin, $database_magazinducoin);

//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_magazinducoin, "");
//Grand Levels: Level
$restrict->addLevel("1");
$restrict->Execute();
//End Restrict Access To Page

//start Trigger_CheckPasswords trigger
//remove this line if you want to edit the code by hand
function Trigger_CheckPasswords(&$tNG) {
  $myThrowError = new tNG_ThrowError($tNG);
  $myThrowError->setErrorMsg("Could not create account.");
  $myThrowError->setField("password");
  $myThrowError->setFieldErrorMsg("The two passwords do not match.");
  return $myThrowError->Execute();
}
//end Trigger_CheckPasswords trigger

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("password", true, "text", "", "", "", "");
$tNGs->prepareValidation($formValidation);
// End trigger

//start Trigger_CheckOldPassword trigger
//remove this line if you want to edit the code by hand
function Trigger_CheckOldPassword(&$tNG) {
  return Trigger_UpdatePassword_CheckOldPassword($tNG);
}
//end Trigger_CheckOldPassword trigger

// Make an insert transaction instance
$ins_utilisateur = new tNG_multipleInsert($conn_magazinducoin);
$tNGs->addTransaction($ins_utilisateur);
// Register triggers
$ins_utilisateur->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_utilisateur->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_utilisateur->registerTrigger("END", "Trigger_Default_Redirect", 99, "includes/nxt/back.php");
$ins_utilisateur->registerConditionalTrigger("{POST.password} != {POST.re_password}", "BEFORE", "Trigger_CheckPasswords", 50);
// Add columns
$ins_utilisateur->setTable("utilisateur");
$ins_utilisateur->addColumn("password", "STRING_TYPE", "POST", "password");
$ins_utilisateur->setPrimaryKey("id", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_utilisateur = new tNG_multipleUpdate($conn_magazinducoin);
$tNGs->addTransaction($upd_utilisateur);
// Register triggers
$_GET['id'] = $_SESSION['kt_login_id'];
$upd_utilisateur->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_utilisateur->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_utilisateur->registerTrigger("END", "Trigger_Default_Redirect", 99, "membre.php");
$upd_utilisateur->registerConditionalTrigger("{POST.password} != {POST.re_password}", "BEFORE", "Trigger_CheckPasswords", 50);
$upd_utilisateur->registerTrigger("BEFORE", "Trigger_CheckOldPassword", 60);
// Add columns
$upd_utilisateur->setTable("utilisateur");
$upd_utilisateur->addColumn("password", "STRING_TYPE", "POST", "password");
$upd_utilisateur->setPrimaryKey("id", "NUMERIC_TYPE", "GET", "id");

// Make an instance of the transaction object
$del_utilisateur = new tNG_multipleDelete($conn_magazinducoin);
$tNGs->addTransaction($del_utilisateur);
// Register triggers
$del_utilisateur->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_utilisateur->registerTrigger("END", "Trigger_Default_Redirect", 99, "includes/nxt/back.php");
// Add columns
$del_utilisateur->setTable("utilisateur");
$del_utilisateur->setPrimaryKey("id", "NUMERIC_TYPE", "GET", "id");

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
	<title>Magasin Du Coin |</title>
    <?php include("modules/head.php"); ?>
	<link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
	<script src="includes/common/js/base.js" type="text/javascript"></script>
	<script src="includes/common/js/utility.js" type="text/javascript"></script>
	<script src="includes/skins/style.js" type="text/javascript"></script>
	<?php echo $tNGs->displayValidationRules();?>
	<script src="includes/nxt/scripts/form.js" type="text/javascript"></script>
	<script src="includes/nxt/scripts/form.js.php" type="text/javascript"></script>
	<script type="text/javascript">
$NXT_FORM_SETTINGS = {
  duplicate_buttons: false,
  show_as_grid: false,
  merge_down_value: false
}
    </script>
</head>
<body id="sp">
<?php include("modules/header.php"); ?>
  		<div id="content">
        	 <div class="top reduit">
            	<?php include("modules/menu.php"); ?>
       		 </div>
  		  <h3>Changer le mot de passe</h3>
			<?php
                echo $tNGs->getErrorMsg();
            ?>
          <div id="contact">
            <div>
              <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
                <?php $cnt1 = 0; ?>
                <?php do { ?>
                  <?php $cnt1++; ?>
                  <?php 
// Show IF Conditional region1 
if (@$totalRows_rsutilisateur > 1) {
?>
                    <h3><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h3>
                    <?php } 
// endif Conditional region1
?>
<p><label for="old_password_<?php echo $cnt1; ?>">Ancien mot de passe</label>
                        <input type="password" name="old_password_<?php echo $cnt1; ?>" id="old_password_<?php echo $cnt1; ?>" value="" size="32" maxlength="200" />
                          <?php echo $tNGs->displayFieldError("utilisateur", "old_password", $cnt1); ?></p>

<p><label for="password_<?php echo $cnt1; ?>">Nouveau mot de passe</label>
                      <input type="password" name="password_<?php echo $cnt1; ?>" id="password_<?php echo $cnt1; ?>" value="" size="32" maxlength="200" />
                        <?php echo $tNGs->displayFieldHint("password");?> <?php echo $tNGs->displayFieldError("utilisateur", "password", $cnt1); ?></p>

<p><label for="re_password_<?php echo $cnt1; ?>">Ressaisir le mot de passe</label>
                      <input type="password" name="re_password_<?php echo $cnt1; ?>" id="re_password_<?php echo $cnt1; ?>" value="" size="32" maxlength="200" /></p>
                  
                  <input type="hidden" name="kt_pk_utilisateur_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsutilisateur['kt_pk_utilisateur']); ?>" />
                  <?php } while ($row_rsutilisateur = mysql_fetch_assoc($rsutilisateur)); ?>
                <div style="margin-left: 280px; margin-top: 20px;">
                  <div>
                    <?php 
      // Show IF Conditional region1
      if (@$_GET['id'] == "") {
      ?>
                      <input type="submit" name="KT_Insert1" id="KT_Insert1" value="<?php echo NXT_getResource("Insert_FB"); ?>" />
                      <?php 
      // else Conditional region1
      } else { ?>
                      <input type="submit" name="KT_Update1" value="<?php echo NXT_getResource("Update_FB"); ?>" />
                      <?php }
      // endif Conditional region1
      ?>
                    <input type="button" name="KT_Cancel1" value="Retour a l'espace membre<?php ///echo NXT_getResource("Cancel_FB"); ?>" onclick="return UNI_navigateCancel(event, 'membre.php')" />
                  </div>
                </div>
              </form>
            </div>
            <br class="clearfixplain" />
          </div>
         
      </div>
	</div>
  
  

</form>



<div id="footer">
    	<?php include("modules/region_barre_recherche.php"); ?>
        <div class="liens">
      	 <?php include("modules/footer.php"); ?>
        </div>
</div>
</body>
</html>