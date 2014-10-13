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
$formValidation->addField("nom", true, "text", "", "", "", "");
$formValidation->addField("prenom", true, "text", "", "", "", "");
$formValidation->addField("email", true, "text", "", "", "", "");
$formValidation->addField("password", true, "text", "", "", "", "");
$formValidation->addField("activate", true, "numeric", "", "", "", "");
$formValidation->addField("level", true, "numeric", "", "", "", "");
$formValidation->addField("civilite", true, "text", "", "", "", "");
$formValidation->addField("siren", true, "text", "", "", "", "");
$formValidation->addField("region", true, "numeric", "", "", "", "");
$formValidation->addField("ville", true, "numeric", "", "", "", "");
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
$query_Recordset1 = "SELECT nom_magazin, id_magazin FROM magazins ORDER BY nom_magazin";
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset3 = "SELECT nom_region, id_region FROM region ORDER BY nom_region";
$Recordset3 = mysql_query($query_Recordset3, $magazinducoin) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

// Make an insert transaction instance
$ins_utilisateur = new tNG_multipleInsert($conn_magazinducoin);
$tNGs->addTransaction($ins_utilisateur);
// Register triggers
$ins_utilisateur->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_utilisateur->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_utilisateur->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$ins_utilisateur->registerConditionalTrigger("{POST.password} != {POST.re_password}", "BEFORE", "Trigger_CheckPasswords", 50);
// Add columns
$ins_utilisateur->setTable("utilisateur");
$ins_utilisateur->addColumn("nom", "STRING_TYPE", "POST", "nom");
$ins_utilisateur->addColumn("description", "STRING_TYPE", "POST", "description");
$ins_utilisateur->addColumn("note", "DOUBLE_TYPE", "POST", "note");
$ins_utilisateur->addColumn("payer", "NUMERIC_TYPE", "POST", "payer");
$ins_utilisateur->addColumn("date_naissance", "STRING_TYPE", "POST", "date_naissance");
$ins_utilisateur->addColumn("prenom", "STRING_TYPE", "POST", "prenom");
$ins_utilisateur->addColumn("email", "STRING_TYPE", "POST", "email");
$ins_utilisateur->addColumn("password", "STRING_TYPE", "POST", "password");
$ins_utilisateur->addColumn("activate", "NUMERIC_TYPE", "POST", "activate");
$ins_utilisateur->addColumn("level", "NUMERIC_TYPE", "POST", "level");
$ins_utilisateur->addColumn("civilite", "STRING_TYPE", "POST", "civilite");
$ins_utilisateur->addColumn("telephone", "STRING_TYPE", "POST", "telephone");
$ins_utilisateur->addColumn("nom_magazin", "STRING_TYPE", "POST", "nom_magazin");
$ins_utilisateur->addColumn("siren", "STRING_TYPE", "POST", "siren");
$ins_utilisateur->addColumn("region", "NUMERIC_TYPE", "POST", "region");
$ins_utilisateur->addColumn("adresse", "STRING_TYPE", "POST", "adresse");
$ins_utilisateur->addColumn("code_postal", "STRING_TYPE", "POST", "code_postal");
$ins_utilisateur->addColumn("ville", "NUMERIC_TYPE", "POST", "ville");
$ins_utilisateur->setPrimaryKey("id", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_utilisateur = new tNG_multipleUpdate($conn_magazinducoin);
$tNGs->addTransaction($upd_utilisateur);
// Register triggers
$upd_utilisateur->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_utilisateur->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_utilisateur->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$upd_utilisateur->registerConditionalTrigger("{POST.password} != {POST.re_password}", "BEFORE", "Trigger_CheckPasswords", 50);
// Add columns
$upd_utilisateur->setTable("utilisateur");
$upd_utilisateur->addColumn("nom", "STRING_TYPE", "POST", "nom");
$upd_utilisateur->addColumn("description", "STRING_TYPE", "POST", "description");
$upd_utilisateur->addColumn("note", "DOUBLE_TYPE", "POST", "note");
$upd_utilisateur->addColumn("payer", "NUMERIC_TYPE", "POST", "payer");
$upd_utilisateur->addColumn("date_naissance", "STRING_TYPE", "POST", "date_naissance");
$upd_utilisateur->addColumn("prenom", "STRING_TYPE", "POST", "prenom");
$upd_utilisateur->addColumn("email", "STRING_TYPE", "POST", "email");
$upd_utilisateur->addColumn("activate", "NUMERIC_TYPE", "POST", "activate");
$upd_utilisateur->addColumn("level", "NUMERIC_TYPE", "POST", "level");
$upd_utilisateur->addColumn("civilite", "STRING_TYPE", "POST", "civilite");
$upd_utilisateur->addColumn("telephone", "STRING_TYPE", "POST", "telephone");
$upd_utilisateur->addColumn("nom_magazin", "STRING_TYPE", "POST", "nom_magazin");
$upd_utilisateur->addColumn("siren", "STRING_TYPE", "POST", "siren");
$upd_utilisateur->addColumn("region", "NUMERIC_TYPE", "POST", "region");
$upd_utilisateur->addColumn("adresse", "STRING_TYPE", "POST", "adresse");
$upd_utilisateur->addColumn("code_postal", "STRING_TYPE", "POST", "code_postal");
$upd_utilisateur->addColumn("ville", "NUMERIC_TYPE", "POST", "ville");
$upd_utilisateur->setPrimaryKey("id", "NUMERIC_TYPE", "GET", "id");

// Make an instance of the transaction object
$del_utilisateur = new tNG_multipleDelete($conn_magazinducoin);
$tNGs->addTransaction($del_utilisateur);
// Register triggers
$del_utilisateur->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_utilisateur->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_utilisateur->setTable("utilisateur");
$del_utilisateur->setPrimaryKey("id", "NUMERIC_TYPE", "GET", "id");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsutilisateur = $tNGs->getRecordset("utilisateur");
$row_rsutilisateur = mysql_fetch_assoc($rsutilisateur);
$totalRows_rsutilisateur = mysql_num_rows($rsutilisateur);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 
<?php include("../modules/head.php");?>
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
              Utilisateur </h1>
            <div class="KT_tngform">
              <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
                <?php $cnt1 = 0; ?>
                <?php do { ?>
                  <?php $cnt1++; ?>
                  <?php 
// Show IF Conditional region1 
if (@$totalRows_rsutilisateur > 1) {
?>
                    <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
                    <?php } 
// endif Conditional region1
?>
                  <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                    <tr>
                      <td class="KT_th"><label for="nom_<?php echo $cnt1; ?>">Nom:</label></td>
                      <td><input type="text" name="nom_<?php echo $cnt1; ?>" id="nom_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsutilisateur['nom']); ?>" size="32" maxlength="50" />
                          <?php echo $tNGs->displayFieldHint("nom");?> <?php echo $tNGs->displayFieldError("utilisateur", "nom", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="prenom_<?php echo $cnt1; ?>">Prénom:</label></td>
                      <td><input type="text" name="prenom_<?php echo $cnt1; ?>" id="prenom_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsutilisateur['prenom']); ?>" size="32" maxlength="50" />
                          <?php echo $tNGs->displayFieldHint("prenom");?> <?php echo $tNGs->displayFieldError("utilisateur", "prenom", $cnt1); ?> </td>
                    </tr>
                     <tr>
                      <td class="KT_th"><label for="email_<?php echo $cnt1; ?>">Email:</label></td>
                      <td><input type="text" name="email_<?php echo $cnt1; ?>" id="email_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsutilisateur['email']); ?>" size="32" maxlength="255" />
                          <?php echo $tNGs->displayFieldHint("email");?> <?php echo $tNGs->displayFieldError("utilisateur", "email", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="description_<?php echo $cnt1; ?>">Description:</label></td>
                      <td><textarea name="description_<?php echo $cnt1; ?>" id="description_<?php echo $cnt1; ?>" cols="50" rows="5"><?php echo KT_escapeAttribute($row_rsutilisateur['description']); ?></textarea>
                          <?php echo $tNGs->displayFieldHint("description");?> <?php echo $tNGs->displayFieldError("utilisateur", "description", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="note_<?php echo $cnt1; ?>">Note:</label></td>
                      <td><input type="text" name="note_<?php echo $cnt1; ?>" id="note_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsutilisateur['note']); ?>" size="7" />
                          <?php echo $tNGs->displayFieldHint("note");?> <?php echo $tNGs->displayFieldError("utilisateur", "note", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="payer_<?php echo $cnt1; ?>">Payer:</label></td>
                      <td><input type="text" name="payer_<?php echo $cnt1; ?>" id="payer_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsutilisateur['payer']); ?>" size="2" />
                          <?php echo $tNGs->displayFieldHint("payer");?> <?php echo $tNGs->displayFieldError("utilisateur", "payer", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="date_naissance_<?php echo $cnt1; ?>">Date_naissance:</label></td>
                      <td><input type="text" name="date_naissance_<?php echo $cnt1; ?>" id="date_naissance_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsutilisateur['date_naissance']); ?>" size="32" maxlength="40" />
                          <?php echo $tNGs->displayFieldHint("date_naissance");?> <?php echo $tNGs->displayFieldError("utilisateur", "date_naissance", $cnt1); ?> </td>
                    </tr>
            
                   
                    <?php 
// Show IF Conditional show_password_on_insert_only 
if (@$_GET['id'] == "") {
?>
                      <tr>
                        <td class="KT_th"><label for="password_<?php echo $cnt1; ?>">Password:</label></td>
                        <td><input type="password" name="password_<?php echo $cnt1; ?>" id="password_<?php echo $cnt1; ?>" value="" size="32" maxlength="200" />
                            <?php echo $tNGs->displayFieldHint("password");?> <?php echo $tNGs->displayFieldError("utilisateur", "password", $cnt1); ?> </td>
                      </tr>
                      <tr>
                        <td class="KT_th"><label for="re_password_<?php echo $cnt1; ?>">Re-type Password:</label></td>
                        <td><input type="password" name="re_password_<?php echo $cnt1; ?>" id="re_password_<?php echo $cnt1; ?>" value="" size="32" maxlength="200" />
                        </td>
                      </tr>
                      <?php } 
// endif Conditional show_password_on_insert_only
?>
                    <tr>
                      <td class="KT_th"><label for="activate_<?php echo $cnt1; ?>">Activate:</label></td>
                      <td><select name="activate_<?php echo $cnt1; ?>" id="activate_<?php echo $cnt1; ?>">
                          <option value="1" <?php if (!(strcmp(1, KT_escapeAttribute($row_rsutilisateur['activate'])))) {echo "SELECTED";} ?>>Active</option>
                          <option value="0" <?php if (!(strcmp(0, KT_escapeAttribute($row_rsutilisateur['activate'])))) {echo "SELECTED";} ?>>Désactive</option>
                        </select>
                          <?php echo $tNGs->displayFieldError("utilisateur", "activate", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="level_<?php echo $cnt1; ?>">Type:</label></td>
                      <td><select name="level_<?php echo $cnt1; ?>" id="level_<?php echo $cnt1; ?>">
                          <option value="1" <?php if (!(strcmp(1, KT_escapeAttribute($row_rsutilisateur['level'])))) {echo "SELECTED";} ?>>Commerçant</option>
                          <option value="2" <?php if (!(strcmp(2, KT_escapeAttribute($row_rsutilisateur['level'])))) {echo "SELECTED";} ?>>Photographes</option>
                          <option value="3" <?php if (!(strcmp(3, KT_escapeAttribute($row_rsutilisateur['level'])))) {echo "SELECTED";} ?>>Normal</option>
                          <option value="4" <?php if (!(strcmp(4, KT_escapeAttribute($row_rsutilisateur['level'])))) {echo "SELECTED";} ?>>Admin</option>
                        </select>
                          <?php echo $tNGs->displayFieldError("utilisateur", "level", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="civilite_<?php echo $cnt1; ?>">Civilite:</label></td>
                      <td><select name="civilite_<?php echo $cnt1; ?>" id="civilite_<?php echo $cnt1; ?>">
                          <option value="Mlle" <?php if (!(strcmp("Mlle", KT_escapeAttribute($row_rsutilisateur['civilite'])))) {echo "SELECTED";} ?>>Mlle</option>
                          <option value="Mme" <?php if (!(strcmp("Mme", KT_escapeAttribute($row_rsutilisateur['civilite'])))) {echo "SELECTED";} ?>>Mme</option>
                          <option value="M" <?php if (!(strcmp("M", KT_escapeAttribute($row_rsutilisateur['civilite'])))) {echo "SELECTED";} ?>>M</option>
                        </select>
                          <?php echo $tNGs->displayFieldError("utilisateur", "civilite", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="nom_magazin_<?php echo $cnt1; ?>">Nom magazin:</label></td>
                      <td><input type="text" name="nom_magazin_<?php echo $cnt1; ?>" id="nom_magazin_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsutilisateur['nom_magazin']); ?>" size="32" maxlength="200" />
                          <?php echo $tNGs->displayFieldHint("nom_magazin");?> <?php echo $tNGs->displayFieldError("utilisateur", "nom_magazin", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="siren_<?php echo $cnt1; ?>">Siren:</label></td>
                      <td><input type="text" name="siren_<?php echo $cnt1; ?>" id="siren_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsutilisateur['siren']); ?>" size="30" maxlength="30" />
                          <?php echo $tNGs->displayFieldHint("siren");?> <?php echo $tNGs->displayFieldError("utilisateur", "siren", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="region_<?php echo $cnt1; ?>">Region:</label></td>
                      <td><select name="region_<?php echo $cnt1; ?>" id="region_<?php echo $cnt1; ?>"  onchange="ajax('../ajax/ville.php?default=0&id_region='+this.value,'#ville'); ">
                          <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                          <?php 
do {  
?>
                          <option value="<?php echo $row_Recordset3['id_region']?>"<?php if (!(strcmp($row_Recordset3['id_region'], $row_rsutilisateur['region']))) {echo "SELECTED";} ?>><?php echo $row_Recordset3['nom_region']?></option>
                          <?php
} while ($row_Recordset3 = mysql_fetch_assoc($Recordset3));
  $rows = mysql_num_rows($Recordset3);
  if($rows > 0) {
      mysql_data_seek($Recordset3, 0);
	  $row_Recordset3 = mysql_fetch_assoc($Recordset3);
  }
?>
                        </select>
                          <?php echo $tNGs->displayFieldError("utilisateur", "region", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="adresse_<?php echo $cnt1; ?>">Adresse:</label></td>
                      <td><textarea name="adresse_<?php echo $cnt1; ?>" id="adresse_<?php echo $cnt1; ?>" cols="50" rows="5"><?php echo KT_escapeAttribute($row_rsutilisateur['adresse']); ?></textarea>
                          <?php echo $tNGs->displayFieldHint("adresse");?> <?php echo $tNGs->displayFieldError("utilisateur", "adresse", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="code_postal_<?php echo $cnt1; ?>">Code_postal:</label></td>
                      <td><input type="text" name="code_postal_<?php echo $cnt1; ?>" id="code_postal_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsutilisateur['code_postal']); ?>" size="5" maxlength="5" />
                          <?php echo $tNGs->displayFieldHint("code_postal");?> <?php echo $tNGs->displayFieldError("utilisateur", "code_postal", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="ville_<?php echo $cnt1; ?>">Ville:</label></td>
                      <td><select name="ville_<?php echo $cnt1; ?>" id="ville">
                      
                    		<option value=""> choisissez une region </option>
                        </select>
                        
                        
                        
                        
                          <?php echo $tNGs->displayFieldError("utilisateur", "ville", $cnt1); ?> </td>
                    </tr>
                  </table>
                  <input type="hidden" name="kt_pk_utilisateur_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsutilisateur['kt_pk_utilisateur']); ?>" />
                  <?php } while ($row_rsutilisateur = mysql_fetch_assoc($rsutilisateur)); ?>
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

</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset3);
?>