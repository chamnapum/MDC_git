<?php require_once('../Connections/magazinducoin.php'); ?>
<?php
// Load the common classes
require_once('../includes/common/KT_common.php');

// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');

// Load the KT_back class
require_once('../includes/nxt/KT_back.php');

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("../");

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
$formValidation->addField("id_user", true, "numeric", "", "", "", "");
$formValidation->addField("credit", true, "numeric", "", "", "", "");
$formValidation->addField("date", true, "date", "", "", "", "");
$tNGs->prepareValidation($formValidation);
// End trigger

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}


mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset3 = "SELECT email, id FROM utilisateur ORDER BY email";
$Recordset3 = mysql_query($query_Recordset3, $magazinducoin) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);


mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset5 = "SELECT cat_id, cat_name FROM category WHERE type = 2 ORDER BY cat_name";
$Recordset5 = mysql_query($query_Recordset5, $magazinducoin) or die(mysql_error());
$row_Recordset5 = mysql_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysql_num_rows($Recordset5);

function Trigger_do_payment(&$tNG){
	global $magazinducoin;
	
	$query_Recordset9 = "SELECT credit FROM utilisateur WHERE id='".$_POST['id_user_1']."'";
	$Recordset9 = mysql_query($query_Recordset9, $magazinducoin) or die(mysql_error());
	$row_Recordset9 = mysql_fetch_array($Recordset9);
	$row_Recordset9['credit'];
	$credit = $row_Recordset9['credit']+$_POST['credit_1'];
	$query_update = "UPDATE utilisateur SET credit='".$credit."'";
	$update_query = mysql_query($query_update, $magazinducoin) or die(mysql_error());	
}

// Make an insert transaction instance
$ins_add_credits = new tNG_multipleInsert($conn_magazinducoin);
$tNGs->addTransaction($ins_add_credits);
// Register triggers
$ins_add_credits->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_add_credits->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_add_credits->registerTrigger("AFTER", "Trigger_do_payment", 98);
$ins_add_credits->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$ins_add_credits->setTable("add_credits");
$ins_add_credits->addColumn("id_user", "NUMERIC_TYPE", "POST", "id_user");
$ins_add_credits->addColumn("credit", "NUMERIC_TYPE", "POST", "credit");
$ins_add_credits->addColumn("date", "STRING_TYPE", "POST", "date",date('Y-m-d H:i:s'));
$ins_add_credits->setPrimaryKey("id_add_credit", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_add_credits = new tNG_multipleUpdate($conn_magazinducoin);
$tNGs->addTransaction($upd_add_credits);
// Register triggers
$upd_add_credits->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_add_credits->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_add_credits->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$upd_add_credits->setTable("add_credits");
$upd_add_credits->addColumn("id_user", "NUMERIC_TYPE", "POST", "id_user");
$upd_add_credits->addColumn("credit", "NUMERIC_TYPE", "POST", "credit");
$upd_add_credits->addColumn("date", "DATE_TYPE", "POST", "date");
$upd_add_credits->setPrimaryKey("id_add_credit", "NUMERIC_TYPE", "GET", "event_id");

// Make an instance of the transaction object
$del_add_credits = new tNG_multipleDelete($conn_magazinducoin);
$tNGs->addTransaction($del_add_credits);
// Register triggers
$del_add_credits->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_add_credits->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_add_credits->setTable("add_credits");
$del_add_credits->setPrimaryKey("id_add_credit", "NUMERIC_TYPE", "GET", "id_add_credit");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsadd_credits = $tNGs->getRecordset("add_credits");
$row_rsadd_credits = mysql_fetch_assoc($rsadd_credits);
$totalRows_rsadd_credits = mysql_num_rows($rsadd_credits);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 
	<title>Magazin Du Coin | </title>
    	<style type="text/css">
		@import url(../stylesheets/custom-bg.css);			/*link to CSS file where to change backgrounds of site headers */
		@import url(../stylesheets/styles-light.css);		/*link to the main CSS file for light theme color */
		@import url(../stylesheets/widgets-light.css);		/*link to the CSS file for widgets of light theme color */
		@import url(../stylesheets/superfish-admin.css);			/*link to the CSS file for superfish menu */
		@import url(../stylesheets/tipsy.css);				/*link to the CSS file for tips */
		@import url(../stylesheets/contact.css);				/*link to the CSS file for tips */
	</style>
    <style type="text/css">
body, a, li, td {font-family:arial;font-size:14px;}
hr{border:0;width:100%;color:#d8d8d8;background-color:#d8d8d8;height:1px;}
#path{font-weight:bold;}
table.list_category {
    width:500px;
	border-width: 0px;
	border-spacing: 0px;
	border-style: outset;
	border-color: #f0f0f0;
	border-collapse: collapse;
	background-color: #fff; /* #fffff0; */
}
table.list_category th {
	font-family: verdana,helvetica;
	color: #666;
	font-size: 14px;
	border-width: 1px;
	padding: 5px;
	border-style: solid;
	border-color: #D8D8D8;
    background-color: #D8D8D8;
	-moz-border-radius: 0px; /* 0px 0px 0px 0px */
}
table.list_category td {
	border-width: 1px;
	padding: 4px;
	border-style: solid;
	border-color: #ccc;
    color: #666;
	font-size: 14px;
	/*background-color: #fffff0;*/
	-moz-border-radius: 0px;
}
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
	<div>
  		<div id="content">
          <?php
	echo $tNGs->getErrorMsg();
?>
          <div class="KT_tng">
            <h1>
              <?php 
// Show IF Conditional region1 
if (@$_GET['event_id'] == "") {
?>
                <?php echo NXT_getResource("Insert_FH"); ?>
                <?php 
// else Conditional region1
} else { ?>
                <?php echo NXT_getResource("Update_FH"); ?>
                <?php } 
// endif Conditional region1
?>
              add_credits </h1>
            <div class="KT_tngform">
              <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
                <?php $cnt1 = 0; ?>
                <?php do { ?>
                  <?php $cnt1++; ?>
                  <?php 
// Show IF Conditional region1 
if (@$totalRows_rsadd_credits > 1) {
?>
                    <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
                    <?php } 
// endif Conditional region1
?>
                  <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                    <tr>
                      <td class="KT_th"><label for="id_user_<?php echo $cnt1; ?>">Utilisateur :</label></td>
                      <td><select name="id_user_<?php echo $cnt1; ?>" id="id_user_<?php echo $cnt1; ?>">
                          <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                          <?php 
do {  
?>
                          <option value="<?php echo $row_Recordset3['id']?>"<?php if (!(strcmp($row_Recordset3['id'], $row_rsadd_credits['id_user']))) {echo "SELECTED";} ?>>
						  <?php 
						  $vowels = array("@");
							echo $onlyconsonants = str_replace($vowels, "&#64;", $row_Recordset3['email']);
						  ?>
                          </option>
                          <?php
} while ($row_Recordset3 = mysql_fetch_assoc($Recordset3));
  $rows = mysql_num_rows($Recordset3);
  if($rows > 0) {
      mysql_data_seek($Recordset3, 0);
	  $row_Recordset3 = mysql_fetch_assoc($Recordset3);
  }
?>
                        </select>
                          <?php echo $tNGs->displayFieldError("add_credits", "id_user", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="credit_<?php echo $cnt1; ?>">Credit:</label></td>
                      <td><input type="text" name="credit_<?php echo $cnt1; ?>" id="credit_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsadd_credits['credit']); ?>" size="32" maxlength="255" />
                          <?php echo $tNGs->displayFieldHint("credit");?> <?php echo $tNGs->displayFieldError("add_credits", "credit", $cnt1); ?> </td>
                    </tr>
                    
                    </tr>
                  </table>
                  <input type="hidden" name="date_<?php echo $cnt1; ?>" id="date_<?php echo $cnt1; ?>" value="<?php echo date('Y-m-d H:i:s'); ?>" />
                  <input type="hidden" name="kt_pk_add_credits_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsadd_credits['kt_pk_add_credits']); ?>" />
                  
                  <?php } while ($row_rsadd_credits = mysql_fetch_assoc($rsadd_credits)); ?>
                <div class="KT_bottombuttons">
                  <div>
                    <?php 
      // Show IF Conditional region1
      if (@$_GET['event_id'] == "") {
      ?>
                      <input type="submit" name="KT_Insert1" id="KT_Insert1" value="<?php echo NXT_getResource("Insert_FB"); ?>" />
                      <?php 
      // else Conditional region1
      } else { ?>
                      <div class="KT_operations">
                        <input type="submit" name="KT_Insert1" value="<?php echo NXT_getResource("Insert as new_FB"); ?>" onclick="nxt_form_insertasnew(this, 'event_id')" />
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
</div>
</body>
</html>
<?php
mysql_free_result($Recordset3);

mysql_free_result($Recordset4);
?>