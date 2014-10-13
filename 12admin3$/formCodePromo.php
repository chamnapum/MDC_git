<?php require_once('../Connections/magazinducoin.php'); ?>
<?php
// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');

// Load the KT_back class
require_once('../includes/nxt/KT_back.php');

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("../");

// Load the common classes
require_once('../includes/common/KT_common.php');
require_once 'include/XMLEngine.php';

// Load the required classes
require_once('../includes/tfi/TFI.php');
require_once('../includes/tso/TSO.php');
require_once('../includes/nav/NAV.php');

// Make unified connection variable
$conn_magazinducoin = new KT_connection($magazinducoin, $database_magazinducoin);

//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_magazinducoin, "../");
//Grand Levels: Level
$restrict->addLevel("4");
$restrict->Execute();
//End Restrict Access To Page

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("code", true, "text", "", "", "", "");
$formValidation->addField("reduction", true, "numeric", "", "", "", "");
$tNGs->prepareValidation($formValidation);
// End trigger

// Make an insert transaction instance
$ins_code_promo = new tNG_multipleInsert($conn_magazinducoin);
$tNGs->addTransaction($ins_code_promo);
// Register triggers
$ins_code_promo->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_code_promo->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_code_promo->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$ins_code_promo->setTable("code_promo");
$ins_code_promo->addColumn("code", "STRING_TYPE", "POST", "code");
$ins_code_promo->addColumn("reduction", "NUMERIC_TYPE", "POST", "reduction");
$ins_code_promo->addColumn("valide", "CHECKBOX_1_0_TYPE", "POST", "valide", "1");
$ins_code_promo->setPrimaryKey("id", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_code_promo = new tNG_multipleUpdate($conn_magazinducoin);
$tNGs->addTransaction($upd_code_promo);
// Register triggers
$upd_code_promo->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_code_promo->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_code_promo->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$upd_code_promo->setTable("code_promo");
$upd_code_promo->addColumn("code", "STRING_TYPE", "POST", "code");
$upd_code_promo->addColumn("reduction", "NUMERIC_TYPE", "POST", "reduction");
$upd_code_promo->addColumn("valide", "CHECKBOX_1_0_TYPE", "POST", "valide");
$upd_code_promo->setPrimaryKey("id", "NUMERIC_TYPE", "GET", "id");

// Make an instance of the transaction object
$del_code_promo = new tNG_multipleDelete($conn_magazinducoin);
$tNGs->addTransaction($del_code_promo);
// Register triggers
$del_code_promo->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_code_promo->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_code_promo->setTable("code_promo");
$del_code_promo->setPrimaryKey("id", "NUMERIC_TYPE", "GET", "id");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rscode_promo = $tNGs->getRecordset("code_promo");
$row_rscode_promo = mysql_fetch_assoc($rscode_promo);
$totalRows_rscode_promo = mysql_num_rows($rscode_promo);
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Magazin Du Coin | </title>
    	<style type="text/css">
		@import url(../stylesheets/custom-bg.css);			/*link to CSS file where to change backgrounds of site headers */
		@import url(../stylesheets/styles-light.css);		/*link to the main CSS file for light theme color */
		@import url(../stylesheets/widgets-light.css);		/*link to the CSS file for widgets of light theme color */
		@import url(../stylesheets/superfish-admin.css);			/*link to the CSS file for superfish menu */
		@import url(../stylesheets/tipsy.css);				/*link to the CSS file for tips */
		@import url(../stylesheets/contact.css);				/*link to the CSS file for tips */
	</style>
    	<link href="../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
    	<script src="../includes/common/js/base.js" type="text/javascript"></script>
    	<script src="../includes/common/js/utility.js" type="text/javascript"></script>
    	<script src="../includes/skins/style.js" type="text/javascript"></script>
    	<?php echo $tNGs->displayValidationRules();?>
    	<script src="../includes/nxt/scripts/form.js" type="text/javascript"></script>
    	<script src="../includes/nxt/scripts/form.js.php" type="text/javascript"></script>
    	<script type="text/javascript">
$NXT_FORM_SETTINGS = {
  duplicate_buttons: false,
  show_as_grid: true,
  merge_down_value: true
}
        </script>
</head>
<body id="sp">
<?php include("modules/header.php"); ?>

<div class="content_wrapper_sbr">
	<div id="content">
      <?php
	echo $tNGs->getErrorMsg();
?>
      <div class="KT_tng">
        <h1>
          <?php 
// Show IF Conditional region1 
if (@$_GET['id'] == "") {
?>
            <?php echo NXT_getResource("Insert_FH"); ?>
            <?php 
// else Conditional region1
} else { ?>
            <?php echo NXT_getResource("Update_FH"); ?>
            <?php } 
// endif Conditional region1
?>
          Code promo </h1>
        <div class="KT_tngform">
          <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
            <?php $cnt1 = 0; ?>
            <?php do { ?>
              <?php $cnt1++; ?>
              <?php 
// Show IF Conditional region1 
if (@$totalRows_rscode_promo > 1) {
?>
                <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
                <?php } 
// endif Conditional region1
?>
              <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                <tr>
                  <td class="KT_th"><label for="code_<?php echo $cnt1; ?>">Code</label></td>
                  <td><input type="text" name="code_<?php echo $cnt1; ?>" id="code_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rscode_promo['code']); ?>" size="32" maxlength="100" />
                      <?php echo $tNGs->displayFieldHint("code");?> <?php echo $tNGs->displayFieldError("code_promo", "code", $cnt1); ?> </td>
                </tr>
                <tr>
                  <td class="KT_th"><label for="reduction_<?php echo $cnt1; ?>">Réduction</label></td>
                  <td><input type="text" name="reduction_<?php echo $cnt1; ?>" id="reduction_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rscode_promo['reduction']); ?>" size="32" maxlength="100" />
                      <?php echo $tNGs->displayFieldHint("reduction");?> <?php echo $tNGs->displayFieldError("code_promo", "reduction", $cnt1); ?> </td>
                </tr>
                <tr>
                  <td class="KT_th"><label for="valide_<?php echo $cnt1; ?>">Valide</label></td>
                  <td><input  <?php if (!(strcmp(KT_escapeAttribute($row_rscode_promo['valide']),"1"))) {echo "checked";} ?> type="checkbox" name="valide_<?php echo $cnt1; ?>" id="valide_<?php echo $cnt1; ?>" value="1" />
                      <?php echo $tNGs->displayFieldError("code_promo", "valide", $cnt1); ?> </td>
                </tr>
              </table>
              <input type="hidden" name="kt_pk_code_promo_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rscode_promo['kt_pk_code_promo']); ?>" />
              <?php } while ($row_rscode_promo = mysql_fetch_assoc($rscode_promo)); ?>
            <div class="KT_bottombuttons">
              <div>
                <?php 
      // Show IF Conditional region1
      if (@$_GET['id'] == "") {
      ?>
                  <input type="submit" name="KT_Insert1" id="KT_Insert1" value="<?php echo NXT_getResource("Insert_FB"); ?>" />
                  <?php 
      // else Conditional region1
      } else { ?>
                  <div class="KT_operations">
                    <input type="submit" name="KT_Insert1" value="<?php echo NXT_getResource("Insert as new_FB"); ?>" onclick="nxt_form_insertasnew(this, 'id')" />
                  </div>
                  <input type="submit" name="KT_Update1" value="<?php echo NXT_getResource("Update_FB"); ?>" />
                  <input type="submit" name="KT_Delete1" value="<?php echo NXT_getResource("Delete_FB"); ?>" onclick="return confirm('<?php echo NXT_getResource("Are you sure?"); ?>');" />
                  <?php }
      // endif Conditional region1
      ?>
                <input type="button" name="KT_Cancel1" value="<?php echo NXT_getResource("Cancel_FB"); ?>" onclick="return UNI_navigateCancel(event, '../includes/nxt/back.php')" />
              </div>
            </div>
          </form>
        </div>
        <br class="clearfixplain" />
      </div>
      <p>&nbsp;</p>
	</div>
</div>
</body>
</html>