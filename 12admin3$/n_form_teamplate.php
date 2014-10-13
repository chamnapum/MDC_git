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
$formValidation->addField("id", true, "numeric", "", "", "", "");
$formValidation->addField("titre", true, "text", "", "", "", "");
$formValidation->addField("content", true, "text", "", "", "", "");
$tNGs->prepareValidation($formValidation);
// End trigger

//start Trigger_FileDelete2 trigger
//remove this line if you want to edit the code by hand 
function Trigger_FileDelete2(&$tNG) {
  $deleteObj = new tNG_FileDelete($tNG);
  $deleteObj->setFolder("../assets/images/blog/");
  $deleteObj->setDbFieldName("image");
  return $deleteObj->Execute();
}
//end Trigger_FileDelete2 trigger

//start Trigger_ImageUpload2 trigger
//remove this line if you want to edit the code by hand 
function Trigger_ImageUpload2(&$tNG) {
  $uploadObj = new tNG_ImageUpload($tNG);
  $uploadObj->setFormFieldName("image");
  $uploadObj->setDbFieldName("image");
  $uploadObj->setFolder("../assets/images/blog/");
  $uploadObj->setMaxSize(1000);
  $uploadObj->setAllowedExtensions("gif, jpg, jpe, jpeg, png");
  $uploadObj->setRename("auto");
  return $uploadObj->Execute();
}
//end Trigger_ImageUpload2 trigger


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


// Make an insert transaction instance
$ins_n_teamplate = new tNG_multipleInsert($conn_magazinducoin);
$tNGs->addTransaction($ins_n_teamplate);
// Register triggers
$ins_n_teamplate->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_n_teamplate->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_n_teamplate->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
//$ins_n_teamplate->registerTrigger("AFTER", "Trigger_ImageUpload2", 97);
// Add columns
$ins_n_teamplate->setTable("n_template");
$ins_n_teamplate->addColumn("titre", "STRING_TYPE", "POST", "titre");
$ins_n_teamplate->addColumn("content", "STRING_TYPE", "POST", "content");
$ins_n_teamplate->addColumn("date", "STRING_TYPE", "POST", "date");
$ins_n_teamplate->setPrimaryKey("id", "NUMERIC_TYPE");
//die($_POST['latlan_1']."hhf");
// Make an update transaction instance
$upd_n_teamplate = new tNG_multipleUpdate($conn_magazinducoin);
$tNGs->addTransaction($upd_n_teamplate);
// Register triggers
$upd_n_teamplate->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_n_teamplate->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_n_teamplate->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
//$upd_n_teamplate->registerTrigger("AFTER", "Trigger_ImageUpload2", 97);
// Add columns
$upd_n_teamplate->setTable("n_template");
$upd_n_teamplate->addColumn("titre", "STRING_TYPE", "POST", "titre");
$upd_n_teamplate->addColumn("content", "STRING_TYPE", "POST", "content");
$upd_n_teamplate->addColumn("date", "STRING_TYPE", "POST", "date");
$upd_n_teamplate->setPrimaryKey("id", "NUMERIC_TYPE", "GET", "id");

// Make an instance of the transaction object
$del_n_teamplate = new tNG_multipleDelete($conn_magazinducoin);
$tNGs->addTransaction($del_n_teamplate);
// Register triggers
$del_n_teamplate->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_n_teamplate->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
//$del_n_teamplate->registerTrigger("AFTER", "Trigger_FileDelete2", 98);
// Add columns
$del_n_teamplate->setTable("n_template");
$del_n_teamplate->setPrimaryKey("id", "NUMERIC_TYPE", "GET", "id");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsarticles = $tNGs->getRecordset("n_template");
$row_rsarticles = mysql_fetch_assoc($rsarticles);
$totalRows_rsarticles = mysql_num_rows($rsarticles);
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
.KT_tng a{
	padding:0px !important;
}
.jqte{
	width:700px;
	margin:0px !important;
}
</style>
    <link href="../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
	<script src="../template/js/jquery.js" type="text/javascript"></script>
    <script type="text/javascript">
		function ajax(murl,mresult){
			$(mresult).addClass("en_cours");
			$.ajax({
				  url: murl,
				  cache: false,
				  success: function(html){
					$(mresult).html(html);
					$(mresult).removeClass("en_cours");
				  }
				});
			}
	</script>
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
    
<link type="text/css" rel="stylesheet" href="../assets/texteditor/jquery-te-1.4.0.css">
<script type="text/javascript" src="http://code.jquery.com/jquery.min.js" charset="utf-8"></script>
<script src="../assets/texteditor/jquery-te-1.4.0.min.js" type="text/javascript"></script>


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
if (@$_GET['id_magazin'] == "") {
?>
                <?php echo NXT_getResource("Insert_FH"); ?>
                <?php 
// else Conditional region1
} else { ?>
                <?php echo NXT_getResource("Update_FH"); ?>
                <?php } 
// endif Conditional region1
?>
              Teamplate </h1>
            <div class="KT_tngform">
              <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" enctype="multipart/form-data">
                <?php $cnt1 = 0; ?>
                <?php do { ?>
                  <?php $cnt1++; ?>
                  <?php 
// Show IF Conditional region1 
if (@$totalRows_rsarticles > 1) {
?>
                    <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
                    <?php } 
// endif Conditional region1
?>
                  <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                    <tr>
                      <td class="KT_th" style="width:150px;"><label for="titre_<?php echo $cnt1; ?>">Titre:</label></td>
                      <td><input type="text" name="titre_<?php echo $cnt1; ?>" id="titre_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsarticles['titre']); ?>" size="32" maxlength="250" />
                          <?php echo $tNGs->displayFieldHint("titre");?> <?php echo $tNGs->displayFieldError("n_template", "titre", $cnt1); ?> </td>
                    </tr>

                    <tr>
                      <td class="KT_th"><label for="content_<?php echo $cnt1; ?>">Content:</label></td>
                      <td><textarea name="content_<?php echo $cnt1; ?>" id="content_<?php echo $cnt1; ?>" class="jqte-test" style="width:300px;" ><?php echo KT_escapeAttribute($row_rsarticles['content']); ?></textarea>
                          <?php echo $tNGs->displayFieldHint("content");?> <?php echo $tNGs->displayFieldError("n_template", "content", $cnt1); ?> </td>
                    </tr>
                  </table>
                  <input type="hidden" name="date" value="<?php echo date('Y-m-d');?>" />
                  <input type="hidden" name="kt_pk_n_template_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsarticles['kt_pk_n_template']); ?>" />
                  <?php } while ($row_rsarticles = mysql_fetch_assoc($rsarticles)); ?>
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
</div>
<?php //include("modules/footer.php"); ?>
<script>
	$('.jqte-test').jqte();
	
	// settings of status
	var jqteStatus = true;
	$(".status").click(function()
	{
		jqteStatus = jqteStatus ? false : true;
		$('.jqte-test').jqte({"status" : jqteStatus})
	});
</script>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);
?>