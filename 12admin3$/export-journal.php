<?php require_once('../Connections/magazinducoin.php'); ?>
<?php
// Load the common classes
require_once('../includes/common/KT_common.php');

// Load the common classes
require_once('../includes/common/KT_common.php');

// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');

// Load the KT_back class
require_once('../includes/nxt/KT_back.php');

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("../");

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
$formValidation->addField("mois", true, "text", "", "", "", "");
$formValidation->addField("region", true, "numeric", "", "", "", "");
$formValidation->addField("departement", true, "numeric", "", "", "", "");
$formValidation->addField("active", true, "", "", "", "", "");
$tNGs->prepareValidation($formValidation);
// End trigger

// Make an insert transaction instance
$ins_journal_export = new tNG_multipleInsert($conn_magazinducoin);
$tNGs->addTransaction($ins_journal_export);
// Register triggers
$ins_journal_export->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_journal_export->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_journal_export->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$ins_journal_export->setTable("journal_export");
$ins_journal_export->addColumn("mois", "STRING_TYPE", "POST", "mois");
$ins_journal_export->addColumn("region", "NUMERIC_TYPE", "POST", "region");
$ins_journal_export->addColumn("departement", "NUMERIC_TYPE", "POST", "departement");
$ins_journal_export->addColumn("active", "CHECKBOX_1_0_TYPE", "POST", "active", "0");
$ins_journal_export->setPrimaryKey("id_journal", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_journal_export = new tNG_multipleUpdate($conn_magazinducoin);
$tNGs->addTransaction($upd_journal_export);
// Register triggers
$upd_journal_export->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_journal_export->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_journal_export->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$upd_journal_export->setTable("journal_export");
$upd_journal_export->addColumn("mois", "STRING_TYPE", "POST", "mois");
$upd_journal_export->addColumn("region", "NUMERIC_TYPE", "POST", "region");
$upd_journal_export->addColumn("departement", "NUMERIC_TYPE", "POST", "departement");
$upd_journal_export->addColumn("active", "CHECKBOX_1_0_TYPE", "POST", "active");
$upd_journal_export->setPrimaryKey("id_journal", "NUMERIC_TYPE", "GET", "id_journal");

// Make an instance of the transaction object
$del_journal_export = new tNG_multipleDelete($conn_magazinducoin);
$tNGs->addTransaction($del_journal_export);
// Register triggers
$del_journal_export->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_journal_export->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_journal_export->setTable("journal_export");
$del_journal_export->setPrimaryKey("id_journal", "NUMERIC_TYPE", "GET", "id_journal");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsjournal_export = $tNGs->getRecordset("journal_export");
$row_rsjournal_export = mysql_fetch_assoc($rsjournal_export);
$totalRows_rsjournal_export = mysql_num_rows($rsjournal_export);

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
$query_departement = "SELECT id_departement, nom_departement FROM departement";
$departement = mysql_query($query_departement, $magazinducoin) or die(mysql_error());
$row_departement = mysql_fetch_assoc($departement);
$totalRows_departement = mysql_num_rows($departement);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset1 = "SELECT nom_region, id_region FROM region ORDER BY nom_region";
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
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
  merge_down_value: false
}
    </script>
</head>
<body id="sp">
<?php include("modules/header.php"); ?>

<div class="content_wrapper_sbr">
	<div>
  		<div id="content">
  		  <h2>Liste des journeaux</h2>
   
	  
          <?php
	echo $tNGs->getErrorMsg();
?>
          <div class="KT_tng">
            <h1>
              <?php 
// Show IF Conditional region1 
if (@$_GET['id_journal'] == "") {
?>
              <?php echo NXT_getResource("Insert_FH"); ?>
              <?php 
// else Conditional region1
} else { ?>
              <?php echo NXT_getResource("Update_FH"); ?>
              <?php } 
// endif Conditional region1
?>
              Journal_export </h1>
            <div class="KT_tngform">
              <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
                <?php $cnt1 = 0; ?>
                <?php do { ?>
                <?php $cnt1++; ?>
                <?php 
// Show IF Conditional region1 
if (@$totalRows_rsjournal_export > 1) {
?>
                <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
                <?php } 
// endif Conditional region1
?>
                <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                  <tr>
                    <td class="KT_th"><label for="mois_<?php echo $cnt1; ?>">Mois:</label></td>
                    <td><input type="text" name="mois_<?php echo $cnt1; ?>" id="mois_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsjournal_export['mois']); ?>" size="11" maxlength="11" />
                        <?php echo $tNGs->displayFieldHint("mois");?> <?php echo $tNGs->displayFieldError("journal_export", "mois", $cnt1); ?> </td>
                  </tr>
                  <tr>
                    <td class="KT_th"><label for="region_<?php echo $cnt1; ?>">Région:</label></td>
                    <td><select name="region_<?php echo $cnt1; ?>" id="region_<?php echo $cnt1; ?>">
                        <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                        <?php 
do {  
?>
                        <option value="<?php echo $row_Recordset1['id_region']?>"<?php if (!(strcmp($row_Recordset1['id_region'], $row_rsjournal_export['region']))) {echo "SELECTED";} ?>><?php echo ($row_Recordset1['nom_region']); ?></option>
                        <?php
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
  $rows = mysql_num_rows($Recordset1);
  if($rows > 0) {
      mysql_data_seek($Recordset1, 0);
	  $row_Recordset1 = mysql_fetch_assoc($Recordset1);
  }
?>
                      </select>
                        <?php echo $tNGs->displayFieldError("journal_export", "region", $cnt1); ?> </td>
                  </tr>
                  <tr>
                    <td class="KT_th"><label for="departement_<?php echo $cnt1; ?>">Departement:</label></td>
                    <td><select name="departement_<?php echo $cnt1; ?>" id="departement_<?php echo $cnt1; ?>">
                        <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                        <?php 
do {  
?>
                        <option value="<?php echo $row_departement['id_departement']?>"<?php if (!(strcmp($row_departement['id_departement'], $row_rsjournal_export['departement']))) {echo "SELECTED";} ?>><?php echo ($row_departement['nom_departement']); ?></option>
                        <?php
} while ($row_departement = mysql_fetch_assoc($departement));
  $rows = mysql_num_rows($departement);
  if($rows > 0) {
      mysql_data_seek($departement, 0);
	  $row_departement = mysql_fetch_assoc($departement);
  }
?>
                      </select>
                        <?php echo $tNGs->displayFieldError("journal_export", "departement", $cnt1); ?> </td>
                  </tr>
                  <tr>
                    <td class="KT_th"><label for="active_<?php echo $cnt1; ?>">Active:</label></td>
                    <td><input  <?php if (!(strcmp(KT_escapeAttribute($row_rsjournal_export['active']),"1"))) {echo "checked";} ?> type="checkbox" name="active_<?php echo $cnt1; ?>" id="active_<?php echo $cnt1; ?>" value="1" />
                        <?php echo $tNGs->displayFieldError("journal_export", "active", $cnt1); ?> </td>
                  </tr>
                </table>
                <input type="hidden" name="kt_pk_journal_export_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsjournal_export['kt_pk_journal_export']); ?>" />
                <?php } while ($row_rsjournal_export = mysql_fetch_assoc($rsjournal_export)); ?>
                <div class="KT_bottombuttons">
                  <div>
                    <?php 
      // Show IF Conditional region1
      if (@$_GET['id_journal'] == "") {
      ?>
                    <input type="submit" name="KT_Insert1" id="KT_Insert1" value="<?php echo NXT_getResource("Insert_FB"); ?>" />
                    <?php 
      // else Conditional region1
      } else { ?>
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
<?php include("modules/footer.php"); ?>
</body>
</html>