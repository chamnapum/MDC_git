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

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("id_cat1", true, "numeric", "", "", "", "");
$formValidation->addField("id_cat2", true, "numeric", "", "", "", "");
$formValidation->addField("proposition", true, "text", "", "", "", "");
$tNGs->prepareValidation($formValidation);
// End trigger

//start Trigger_SendEmail trigger
//remove this line if you want to edit the code by hand
function Trigger_SendEmail(&$tNG) {
  $emailObj = new tNG_Email($tNG);
  $emailObj->setFrom("{SESSION.kt_login_user}");
  $emailObj->setTo("contact@mohamedbelgaila.com");//{KT_defaultSender}
  $emailObj->setCC("");
  $emailObj->setBCC("");
  $emailObj->setSubject("Proposition de catégorie");
  //WriteContent method
  
  $emailObj->setContent("Bonjour Admin,\nUn commercant à proposé une catégorie sous le nom de: {POST.proposition}  dans la catégorie: {POST.id_cat1} et sous catégorie: {POST.id_cat2} \n\n
  Veuillez cliquer sur ce lien pour valider:   http://www.magasinducoin.fr/dev/proposer-action.php?titre={POST.proposition}&cat1={POST.id_cat1}&cat2={POST.id_cat2} \n\n
  Cordialement.");
  $emailObj->setEncoding("ISO-8859-1");
  $emailObj->setFormat("Text");
  $emailObj->setImportance("Normal");
  return $emailObj->Execute();
}
//end Trigger_SendEmail trigger

// Make an insert transaction instance
$ins_proposer_cat = new tNG_multipleInsert($conn_magazinducoin);
$tNGs->addTransaction($ins_proposer_cat);
// Register triggers
$ins_proposer_cat->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_proposer_cat->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_proposer_cat->registerTrigger("END", "Trigger_Default_Redirect", 99, "membre.php");
$ins_proposer_cat->registerTrigger("AFTER", "Trigger_SendEmail", 98);
// Add columns
$ins_proposer_cat->setTable("proposer_cat");
$ins_proposer_cat->addColumn("id_cat1", "NUMERIC_TYPE", "POST", "id_cat1");
$ins_proposer_cat->addColumn("id_cat2", "NUMERIC_TYPE", "POST", "id_cat2");
$ins_proposer_cat->addColumn("proposition", "STRING_TYPE", "POST", "proposition");
$ins_proposer_cat->addColumn("id_user", "NUMERIC_TYPE", "POST", "id_user", "{SESSION.kt_login_id}");
$ins_proposer_cat->setPrimaryKey("id", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_proposer_cat = new tNG_multipleUpdate($conn_magazinducoin);
$tNGs->addTransaction($upd_proposer_cat);
// Register triggers
$upd_proposer_cat->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_proposer_cat->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_proposer_cat->registerTrigger("END", "Trigger_Default_Redirect", 99, "includes/nxt/back.php");
$upd_proposer_cat->registerTrigger("AFTER", "Trigger_SendEmail", 98);
// Add columns
$upd_proposer_cat->setTable("proposer_cat");
$upd_proposer_cat->setPrimaryKey("id", "NUMERIC_TYPE", "GET", "id");

// Make an instance of the transaction object
$del_proposer_cat = new tNG_multipleDelete($conn_magazinducoin);
$tNGs->addTransaction($del_proposer_cat);
// Register triggers
$del_proposer_cat->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_proposer_cat->registerTrigger("END", "Trigger_Default_Redirect", 99, "includes/nxt/back.php");
// Add columns
$del_proposer_cat->setTable("proposer_cat");
$del_proposer_cat->setPrimaryKey("id", "NUMERIC_TYPE", "GET", "id");

// Execute all the registered transactions
$tNGs->executeTransactions();


mysql_select_db($database_magazinducoin, $magazinducoin);
$query_categories = "SELECT cat_id, cat_name FROM category WHERE parent_id = 0 ORDER BY cat_name ASC";
$categories = mysql_query($query_categories, $magazinducoin) or die(mysql_error());
$row_categories = mysql_fetch_assoc($categories);
$totalRows_categories = mysql_num_rows($categories);

// Get the transaction recordset
$rsproposer_cat = $tNGs->getRecordset("proposer_cat");
$row_rsproposer_cat = mysql_fetch_assoc($rsproposer_cat);
$totalRows_rsproposer_cat = mysql_num_rows($rsproposer_cat);
//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_magazinducoin, "");
//Grand Levels: Level
//$restrict->addLevel("1");
$restrict->Execute();

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://ns.adobe.com/addt">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 
	<title>Magasin du Coin | Proposer une catégorie</title>
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
  show_as_grid: true,
  merge_down_value: true
}
</script>
</head>
<body id="sp">
<?php include("modules/header.php"); ?>

 	<div id="content" class="photographes" >
     	<div class="top reduit">
                    <?php include("modules/menu.php"); ?>
     	</div>
 	 	<div style="float:left; width:200px;">           
			<?php include("modules/membre_menu.php"); ?>
		</div>
		<div style="float:left; width:800px">
			<h3 style="margin-left:20px;">Proposer une catégorie</h3>
                        <?php
	echo $tNGs->getErrorMsg();
?>
            
            <div class="KT_tng">              
              <div class="KT_tngform">
                <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
                  <?php $cnt1 = 0; ?>
                  <?php do { ?>
                    <?php $cnt1++; ?>
                    <?php 
// Show IF Conditional region1 
if (@$totalRows_rsproposer_cat > 1) {
?>
                      <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
                      <?php } 
// endif Conditional region1
?>
<div style="padding-left:20px; position:relative; height:230px;" class="form_insc2">
<div class="champ">            
<label for="id_cat1_<?php echo $cnt1; ?>">Catégorie principale</label>
<select name="id_cat1_<?php echo $cnt1; ?>" id="id_cat1_<?php echo $cnt1; ?>" onchange="ajax('ajax/sous_categorie.php?default=&id_parent='+this.value,'#id_cat2_<?php echo $cnt1; ?>');">
<option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                          <?php 
do {  
?>
                          <option value="<?php echo $row_categories['cat_id']?>"<?php if (!(strcmp($row_categories['cat_id'], $row_rsproduits['categorie']))) {echo "SELECTED";} ?>><?php echo ($row_categories['cat_name']); ?></option>
                          <?php
} while ($row_categories = mysql_fetch_assoc($categories));
  $rows = mysql_num_rows($categories);
  if($rows > 0) {
      mysql_data_seek($categories, 0);
	  $row_categories = mysql_fetch_assoc($categories);
  }
?>
</select>
<?php echo $tNGs->displayFieldHint("id_cat1");?> <?php echo $tNGs->displayFieldError("proposer_cat", "id_cat1", $cnt1); ?>
</div>

<div class="champ">  
 <label for="id_cat2_<?php echo $cnt1; ?>">Sous catégorie</label>
<select name="id_cat2_<?php echo $cnt1; ?>" id="id_cat2_<?php echo $cnt1; ?>">
       <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
</select>
<?php echo $tNGs->displayFieldHint("id_cat1");?> <?php echo $tNGs->displayFieldError("proposer_cat", "id_cat1", $cnt1); ?> 
 </div>  
  <div class="clear"></div> 
 <div class="champ">  
<label for="proposition_<?php echo $cnt1; ?>">Votre proposition</label>
<input type="text" name="proposition_<?php echo $cnt1; ?>" id="proposition_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsproposer_cat['proposition']); ?>" size="7" />
                              <?php echo $tNGs->displayFieldHint("proposition");?> <?php echo $tNGs->displayFieldError("proposer_cat", "proposition", $cnt1); ?>
 </div> 
 <div class="clear"></div>                          
   </div>                                            
                    <input type="hidden" name="kt_pk_proposer_cat_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsproposer_cat['kt_pk_proposer_cat']); ?>" />
                    <input type="hidden" name="id_user_<?php echo $cnt1; ?>" id="id_user_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsproposer_cat['id_user']); ?>" />
                    <?php } while ($row_rsproposer_cat = mysql_fetch_assoc($rsproposer_cat)); ?>
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
                      <input type="button" name="KT_Cancel1" value="<?php echo NXT_getResource("Cancel_FB"); ?>" onclick="return UNI_navigateCancel(event, 'includes/nxt/back.php')" />
                    </div>
                  </div>
                </form>
              </div>
              <br class="clearfixplain" />
            </div>
            <p>&nbsp;</p>
	  </div>
	</div>


    <div id="footer">
                <?php include("modules/region_barre_recherche.php"); ?>
            <div class="liens">
                <?php include("modules/footer.php"); ?>
            </div>
    </div>

</body>
</html>