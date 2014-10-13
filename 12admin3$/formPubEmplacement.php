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
$formValidation->addField("titre", true, "text", "", "", "", "");
$formValidation->addField("prix", true, "double", "", "", "", "");
$formValidation->addField("date_debut", true, "date", "", "", "", "");
$formValidation->addField("type", true, "double", "", "", "", "");
$formValidation->addField("sub_type", true, "double", "", "", "", "");
$tNGs->prepareValidation($formValidation);
// End trigger

// Make an insert transaction instance
$ins_pub_emplacement = new tNG_multipleInsert($conn_magazinducoin);
$tNGs->addTransaction($ins_pub_emplacement);
// Register triggers
$ins_pub_emplacement->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_pub_emplacement->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_pub_emplacement->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$ins_pub_emplacement->setTable("pub_emplacement");
$ins_pub_emplacement->addColumn("titre", "STRING_TYPE", "POST", "titre");
$ins_pub_emplacement->addColumn("prix", "DOUBLE_TYPE", "POST", "prix");
$ins_pub_emplacement->addColumn("date_debut", "DATE_TYPE", "POST", "date_debut");
$ins_pub_emplacement->addColumn("type", "NUMERIC_TYPE", "POST", "type");
$ins_pub_emplacement->addColumn("sub_type", "NUMERIC_TYPE", "POST", "sub_type");
$ins_pub_emplacement->addColumn("description", "STRING_TYPE", "POST", "description");
$ins_pub_emplacement->setPrimaryKey("id", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_pub_emplacement = new tNG_multipleUpdate($conn_magazinducoin);
$tNGs->addTransaction($upd_pub_emplacement);
// Register triggers
$upd_pub_emplacement->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_pub_emplacement->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_pub_emplacement->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$upd_pub_emplacement->setTable("pub_emplacement");
$upd_pub_emplacement->addColumn("titre", "STRING_TYPE", "POST", "titre");
$upd_pub_emplacement->addColumn("prix", "DOUBLE_TYPE", "POST", "prix");
$upd_pub_emplacement->addColumn("date_debut", "DATE_TYPE", "POST", "date_debut");
$upd_pub_emplacement->addColumn("type", "NUMERIC_TYPE", "POST", "type");
$upd_pub_emplacement->addColumn("sub_type", "NUMERIC_TYPE", "POST", "sub_type");
$upd_pub_emplacement->addColumn("description", "STRING_TYPE", "POST", "description");
$upd_pub_emplacement->setPrimaryKey("id", "NUMERIC_TYPE", "GET", "id");

// Make an instance of the transaction object
$del_pub_emplacement = new tNG_multipleDelete($conn_magazinducoin);
$tNGs->addTransaction($del_pub_emplacement);
// Register triggers
$del_pub_emplacement->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_pub_emplacement->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_pub_emplacement->setTable("pub_emplacement");
$del_pub_emplacement->setPrimaryKey("id", "NUMERIC_TYPE", "GET", "id");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rspub_emplacement = $tNGs->getRecordset("pub_emplacement");
$row_rspub_emplacement = mysql_fetch_assoc($rspub_emplacement);
$totalRows_rspub_emplacement = mysql_num_rows($rspub_emplacement);
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
    
    <script type="text/javascript" src="../includes/common/js/sigslot_core.js"></script>
	<script type="text/javascript" src="../includes/wdg/classes/MXWidgets.js"></script>
    <script type="text/javascript" src="../includes/wdg/classes/MXWidgets.js.php"></script>
    <script type="text/javascript" src="../includes/wdg/classes/Calendar.js"></script>
    <script type="text/javascript" src="../includes/wdg/classes/SmartDate.js"></script>
    <script type="text/javascript" src="../includes/wdg/calendar/calendar_stripped.js"></script>
    <script type="text/javascript" src="../includes/wdg/calendar/calendar-setup_stripped.js"></script>
    <script src="../includes/resources/calendar.js"></script>
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
    <script src="../includes/skins/style.js" type="text/javascript"></script>
    
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
              Pub_emplacement </h1>
            <div class="KT_tngform">
              <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
                <?php $cnt1 = 0; ?>
                <?php do { ?>
                  <?php $cnt1++; ?>
                  <?php 
// Show IF Conditional region1 
if (@$totalRows_rspub_emplacement > 1) {
?>
                    <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
                    <?php } 
// endif Conditional region1
?>
                  <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                    <tr>
                      <td class="KT_th"><label for="titre_<?php echo $cnt1; ?>">Titre:</label></td>
                      <td><input type="text" name="titre_<?php echo $cnt1; ?>" id="titre_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rspub_emplacement['titre']); ?>" size="32" maxlength="100" />
                          <?php echo $tNGs->displayFieldHint("titre");?> <?php echo $tNGs->displayFieldError("pub_emplacement", "titre", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="prix_<?php echo $cnt1; ?>">Prix:</label></td>
                      <td><input type="text" name="prix_<?php echo $cnt1; ?>" id="prix_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rspub_emplacement['prix']); ?>" size="7" />
                          <?php echo $tNGs->displayFieldHint("prix");?> <?php echo $tNGs->displayFieldError("pub_emplacement", "prix", $cnt1); ?> </td>
                    </tr>
                    <tr><td class="KT_th"><label for="date_debut_<?php echo $cnt1; ?>">Date de debut:</label></td>
   					<td> <input name="date_debut_<?php echo $cnt1; ?>" id="date_debut_<?php echo $cnt1; ?>" value="<?php echo KT_formatDate($row_rspub_emplacement['date_debut']); ?>" size="10" maxlength="22" wdg:mondayfirst="true" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="yes" />
                          <?php echo $tNGs->displayFieldHint("date_debut");?> <?php echo $tNGs->displayFieldError("pub_emplacement", "date_debut", $cnt1); ?> </td></tr>
                     <tr>
                     	<td class="KT_th"><label for="type_<?php echo $cnt1; ?>">Type:</label></td>
                        <td>
                        <select name="type_<?php echo $cnt1; ?>" id="type_<?php echo $cnt1; ?>">
                            <option value=""><?php echo $xml->selectionner ;?></option>
                            <option value="1" <?php if ($row_rspub_emplacement['type'] == 1) {echo "SELECTED";} ?>>Magazin</option>
                            <option value="2" <?php if ($row_rspub_emplacement['type'] == 2) {echo "SELECTED";} ?>>Produit</option>
                            <option value="3" <?php if ($row_rspub_emplacement['type'] == 3) {echo "SELECTED";} ?>>Coupon</option>
                            <option value="4" <?php if ($row_rspub_emplacement['type'] == 4) {echo "SELECTED";} ?>>Événements</option>
                        </select>
							<?php echo $tNGs->displayFieldError("pub_emplacement", "type", $cnt1); ?>
                        </td>
                     </tr>  
                     <tr>
                     	<td class="KT_th"><label for="sub_type_<?php echo $cnt1; ?>">Sub Type:</label></td>
                        <td>
                        <select name="sub_type_<?php echo $cnt1; ?>" id="sub_type_<?php echo $cnt1; ?>">
                            <option value=""><?php echo $xml->selectionner ;?></option>
                            <option value="1" <?php if ($row_rspub_emplacement['sub_type'] == 1) {echo "SELECTED";} ?>>Pay</option>
                            <option value="2" <?php if ($row_rspub_emplacement['sub_type'] == 2) {echo "SELECTED";} ?>>Mettre en avant ce coupon</option>
                            <option value="3" <?php if ($row_rspub_emplacement['sub_type'] == 3) {echo "SELECTED";} ?>>Remonter le coupon en tête de liste</option>
                            <option value="4" <?php if ($row_rspub_emplacement['sub_type'] == 4) {echo "SELECTED";} ?>>Publication express</option>
                            <option value="5" <?php if ($row_rspub_emplacement['sub_type'] == 5) {echo "SELECTED";} ?>>Banner Région</option>
                            <option value="6" <?php if ($row_rspub_emplacement['sub_type'] == 6) {echo "SELECTED";} ?>>Banner National</option>
                        </select>
							<?php echo $tNGs->displayFieldError("pub_emplacement", "sub_type", $cnt1); ?>
                        </td>
                     </tr>  
                    <tr>
                      <td class="KT_th"><label for="description_<?php echo $cnt1; ?>">Description:</label></td>
                      <td><!--<input type="text" name="description_<?php echo $cnt1; ?>" id="description_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rspub_emplacement['description']); ?>" size="7" />-->
                          <textarea name="description_<?php echo $cnt1; ?>" id="description_<?php echo $cnt1; ?>" class="jqte-test"><?php echo KT_escapeAttribute($row_rspub_emplacement['description']); ?></textarea>
						  <?php echo $tNGs->displayFieldHint("description");?> <?php echo $tNGs->displayFieldError("pub_emplacement", "description", $cnt1); ?> </td>
                    </tr>  
                     
                  </table>
                  <input type="hidden" name="kt_pk_pub_emplacement_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rspub_emplacement['kt_pk_pub_emplacement']); ?>" />
                  <?php } while ($row_rspub_emplacement = mysql_fetch_assoc($rspub_emplacement)); ?>
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
                        <input type="submit" name="KT_Insert1" value="<?php echo NXT_getResource("Insert as new_FB"); ?>" onClick="nxt_form_insertasnew(this, 'id')" />
                      </div>
                      <input type="submit" name="KT_Update1" value="<?php echo NXT_getResource("Update_FB"); ?>" />
                      <input type="submit" name="KT_Delete1" value="<?php echo NXT_getResource("Delete_FB"); ?>" onClick="return confirm('<?php echo NXT_getResource("Are you sure?"); ?>');" />
                      <?php }
      // endif Conditional region1
      ?>
                    <input type="button" name="KT_Cancel1" value="<?php echo NXT_getResource("Cancel_FB"); ?>" onClick="return UNI_navigateCancel(event, '../includes/nxt/back.php')" />
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