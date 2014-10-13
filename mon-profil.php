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
//$restrict->addLevel("1");
$restrict->Execute();
if(isset($_SESSION['kt_login_id']) and $_SESSION['kt_payer'] == 0) header('Location: message_aprouvation.php');

$_GET['id'] = $_SESSION['kt_login_id'];

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("civilite", true, "text", "", "", "", "");
$formValidation->addField("nom", true, "text", "", "", "", "");
$formValidation->addField("prenom", true, "text", "", "", "", "");
$formValidation->addField("region", true, "numeric", "", "", "", "");
$formValidation->addField("department", true, "numeric", "", "", "", "");
$formValidation->addField("ville", true, "numeric", "", "", "", "");
$formValidation->addField("adresse", true, "text", "", "", "", "");
if($_SESSION['kt_login_level'] == 2){
	$formValidation->addField("photo_skill", true, "text", "", "", "", "");
}
$tNGs->prepareValidation($formValidation);
// End trigger
function Trigger_FileDelete(&$tNG) {
  $deleteObj = new tNG_FileDelete($tNG);
  $deleteObj->setFolder("assets/images/utilisateur/");
  $deleteObj->setDbFieldName("photo");
  return $deleteObj->Execute();
}

function Trigger_ImageUpload(&$tNG) {
  $uploadObj = new tNG_ImageUpload($tNG);
  $uploadObj->setFormFieldName("photo");
  $uploadObj->setDbFieldName("photo");
  $uploadObj->setFolder("assets/images/utilisateur/");
  $uploadObj->setResize("true", 400, 400);
  $uploadObj->setMaxSize(1500);
  $uploadObj->setAllowedExtensions("gif, jpg, jpe, jpeg, png");
  $uploadObj->setRename("auto");
  return $uploadObj->Execute();
}

function Trigger_FileDelete1(&$tNG) {
  $deleteObj = new tNG_FileDelete($tNG);
  $deleteObj->setFolder("assets/images/utilisateur/");
  $deleteObj->setDbFieldName("photo1");
  return $deleteObj->Execute();
}

function Trigger_ImageUpload1(&$tNG) {
  $uploadObj = new tNG_ImageUpload($tNG);
  $uploadObj->setFormFieldName("photo1");
  $uploadObj->setDbFieldName("photo1");
  $uploadObj->setFolder("assets/images/utilisateur/");
  $uploadObj->setResize("true", 400, 400);
  $uploadObj->setMaxSize(1500);
  $uploadObj->setAllowedExtensions("gif, jpg, jpe, jpeg, png");
  $uploadObj->setRename("auto");
  return $uploadObj->Execute();
}

function Trigger_FileDelete2(&$tNG) {
  $deleteObj = new tNG_FileDelete($tNG);
  $deleteObj->setFolder("assets/images/utilisateur/");
  $deleteObj->setDbFieldName("photo2");
  return $deleteObj->Execute();
}

function Trigger_ImageUpload2(&$tNG) {
  $uploadObj = new tNG_ImageUpload($tNG);
  $uploadObj->setFormFieldName("photo2");
  $uploadObj->setDbFieldName("photo2");
  $uploadObj->setFolder("assets/images/utilisateur/");
  $uploadObj->setResize("true", 400, 400);
  $uploadObj->setMaxSize(1500);
  $uploadObj->setAllowedExtensions("gif, jpg, jpe, jpeg, png");
  $uploadObj->setRename("auto");
  return $uploadObj->Execute();
}

function Trigger_FileDelete3(&$tNG) {
  $deleteObj = new tNG_FileDelete($tNG);
  $deleteObj->setFolder("assets/images/utilisateur/");
  $deleteObj->setDbFieldName("photo3");
  return $deleteObj->Execute();
}

function Trigger_ImageUpload3(&$tNG) {
  $uploadObj = new tNG_ImageUpload($tNG);
  $uploadObj->setFormFieldName("photo3");
  $uploadObj->setDbFieldName("photo3");
  $uploadObj->setFolder("assets/images/utilisateur/");
  $uploadObj->setResize("true", 400, 400);
  $uploadObj->setMaxSize(1500);
  $uploadObj->setAllowedExtensions("gif, jpg, jpe, jpeg, png");
  $uploadObj->setRename("auto");
  return $uploadObj->Execute();
}

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
$query_region = "SELECT * FROM region ORDER BY nom_region ASC";
$region = mysql_query($query_region, $magazinducoin) or die(mysql_error());
$row_region = mysql_fetch_assoc($region);
$totalRows_region = mysql_num_rows($region);


// Make an insert transaction instance
$ins_utilisateur = new tNG_multipleInsert($conn_magazinducoin);
$tNGs->addTransaction($ins_utilisateur);
// Register triggers
$ins_utilisateur->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_utilisateur->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_utilisateur->registerTrigger("END", "Trigger_Default_Redirect", 99, "membre.html");
if($_SESSION['kt_login_level'] == 2){
	$ins_utilisateur->registerTrigger("AFTER", "Trigger_ImageUpload", 97);
	$ins_utilisateur->registerTrigger("AFTER", "Trigger_ImageUpload1", 97);
	$ins_utilisateur->registerTrigger("AFTER", "Trigger_ImageUpload2", 97);
	$ins_utilisateur->registerTrigger("AFTER", "Trigger_ImageUpload3", 97);
}
// Add columns
$ins_utilisateur->setTable("utilisateur");
$ins_utilisateur->setPrimaryKey("id", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_utilisateur = new tNG_multipleUpdate($conn_magazinducoin);
$tNGs->addTransaction($upd_utilisateur);

function Trigger_CheckOldPassword(&$tNG) {
  return Trigger_UpdatePassword_CheckOldPassword($tNG);
}
// Register triggers
$upd_utilisateur->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_utilisateur->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_utilisateur->registerTrigger("END", "Trigger_Default_Redirect", 99, "membre.php");
$upd_utilisateur->registerConditionalTrigger("{POST.password} != {POST.re_password}", "BEFORE", "Trigger_CheckPasswords", 50);
$upd_utilisateur->registerTrigger("BEFORE", "Trigger_CheckOldPassword", 60);
if($_SESSION['kt_login_level'] == 2){
	$upd_utilisateur->registerTrigger("AFTER", "Trigger_ImageUpload", 97);
	$upd_utilisateur->registerTrigger("AFTER", "Trigger_ImageUpload1", 97);
	$upd_utilisateur->registerTrigger("AFTER", "Trigger_ImageUpload2", 97);
	$upd_utilisateur->registerTrigger("AFTER", "Trigger_ImageUpload3", 97);
}
// Add columns
$upd_utilisateur->setTable("utilisateur");
$upd_utilisateur->addColumn("civilite", "STRING_TYPE", "POST", "civilite");
$upd_utilisateur->addColumn("nom", "STRING_TYPE", "POST", "nom");
$upd_utilisateur->addColumn("prenom", "STRING_TYPE", "POST", "prenom");
$upd_utilisateur->addColumn("adresse", "STRING_TYPE", "POST", "adresse");
$upd_utilisateur->addColumn("region", "NUMERIC_TYPE", "POST", "region");
$upd_utilisateur->addColumn("department", "NUMERIC_TYPE", "POST", "department");
$upd_utilisateur->addColumn("ville", "NUMERIC_TYPE", "POST", "ville");
$upd_utilisateur->addColumn("telephone", "STRING_TYPE", "POST", "telephone");
if($_SESSION['kt_login_level'] == 2){
	$upd_utilisateur->addColumn("description", "STRING_TYPE", "POST", "description");
	$upd_utilisateur->addColumn("photo_skill", "STRING_TYPE", "POST", "photo_skill");
	$upd_utilisateur->addColumn("website", "STRING_TYPE", "POST", "website");
	$upd_utilisateur->addColumn("facebook_page", "STRING_TYPE", "POST", "facebook_page");
	$upd_utilisateur->addColumn("photo", "FILE_TYPE", "FILES", "photo");
	$upd_utilisateur->addColumn("photo1", "FILE_TYPE", "FILES", "photo1");
	$upd_utilisateur->addColumn("photo2", "FILE_TYPE", "FILES", "photo2");
	$upd_utilisateur->addColumn("photo3", "FILE_TYPE", "FILES", "photo3");
}
$upd_utilisateur->addColumn("password", "STRING_TYPE", "POST", "password");
$upd_utilisateur->setPrimaryKey("id", "NUMERIC_TYPE", "GET", "id");

// Make an instance of the transaction object
$del_utilisateur = new tNG_multipleDelete($conn_magazinducoin);
$tNGs->addTransaction($del_utilisateur);
// Register triggers
$del_utilisateur->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_utilisateur->registerTrigger("END", "Trigger_Default_Redirect", 99, "membre.php");
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
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://ns.adobe.com/addt">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 
	<title>Magasinducoin | Espace membre </title>
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
<script type="text/javascript">
var SITE = SITE || {};
 
SITE.fileInputs = function() {
  var $this = $(this),
      $val = $this.val(),
      valArray = $val.split('\\'),
      newVal = valArray[valArray.length-1],
      $button = $this.siblings('.button'),
      $fakeFile = $this.siblings('.file-holder');
  if(newVal !== '') {
    $button.text('File Chosen');
    if($fakeFile.length === 0) {
      $button.after('<span class="file-holder">' + newVal + '</span>');
    } else {
      $fakeFile.text(newVal);
    }
  }
};
 
$(document).ready(function() {
  $('.file-wrapper input[type=file]').bind('change focus click', SITE.fileInputs);
});
</script>
<style type="text/css">
.file-wrapper {
    position: relative;
    display: inline-block;
    overflow: hidden;
    cursor: pointer;
}
.file-wrapper input {
    position: absolute;
    top: 0;
    right: 0;
    filter: alpha(opacity=1);
    opacity: 0.01;
    -moz-opacity: 0.01;
    cursor: pointer;
}
.file-wrapper .button {
    color: #fff;
    background: #9D216E;
    padding: 4px 18px;
    margin-right: 5px; 
    border-radius: 5px;
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    display: inline-block;
    font-weight: bold;
    cursor: pointer;
}
.file-holder{
    color: #000;
	font-size:10px;
}
#credit_page{
	padding:0px !important;
}
</style>
</head>
<body id="sp"  onload="ajax('ajax/ville.php?default=<?php echo $row_rsutilisateur['ville']; ?>&id_departement=<?php echo $row_rsutilisateur['department']; ?>&id_region=<?php echo $row_rsutilisateur['region']; ?>','#ville_1');">
<?php include("modules/header.php"); ?>


<div id="content" class="photographes" >
	<?php //include("modules/member_menu.php"); ?> 
	<?php if($_SESSION['kt_login_level'] == 1){ ?>
        <?php include("modules/credit.php"); ?>
    <?php } ?>     
	<?php //include("modules/membre_menu.php"); ?>
	<style>
	.loginForm label{
		font-weight:bold;
		font-size:13px;
	}
	.loginForm input[type="text"], .loginForm input[type="password"]{
		border: 1px solid #CCCCCC;
		border-radius: 5px 5px 5px 5px;
		height: 16px;
		margin-top: 5px;
		padding-left: 5px;
		width: 180px;
		font-size:13px;
	}
	.loginForm select {
		border: 1px solid #CCCCCC;
		border-radius: 5px 5px 5px 5px;
		height: 25px;
		margin-top: 5px;
		padding-left: 5px;
		width: 185px;
		font-size:13px;
	}
	.loginForm input[type="submit"]{
		background-color: #9D286E;
		border: medium none;
		color: #F8C263;
		cursor: pointer;
		font-size: 18px;
		margin: 0 0 0 5px;
		padding: 0 10px 3px;
	}
	.loginForm textarea {
		border: 1px solid #CCCCCC;
		border-radius: 5px 5px 5px 5px;
	}
	.loginForm td{
		line-height:25px;	
	}
	</style>
	
	<h3 style="padding-left:20px; float:left; width:98%;"><?php echo $xml-> Mon_profil ?></h3>
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" enctype="multipart/form-data">
	<div style="padding-left:20px; float:left; width:98%;" class="loginForm">  	
	<?php
    echo $tNGs->getErrorMsg();
    ?>  

          
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
    <table cellpadding="0" cellspacing="0" border="0" width="50%" align="center">
    	<tr>
        	<td>
            	<label for="civilite_<?php echo $cnt1; ?>"><?php echo $xml->Civilite ?> :</label>
            </td>
        	<td>
                <select name="civilite_<?php echo $cnt1; ?>" id="civilite_<?php echo $cnt1; ?>">
                    <option value="M" <?php if (!(strcmp("M", KT_escapeAttribute($row_rsutilisateur['civilite'])))) {echo "SELECTED";} ?>>M</option>
                    <option value="Mlle" <?php if (!(strcmp("Mlle", KT_escapeAttribute($row_rsutilisateur['civilite'])))) {echo "SELECTED";} ?>>Mlle</option>
                    <option value="Mme" <?php if (!(strcmp("Mme", KT_escapeAttribute($row_rsutilisateur['civilite'])))) {echo "SELECTED";} ?>>Mme</option>
                </select>
                <?php echo $tNGs->displayFieldError("utilisateur", "civilite", $cnt1); ?>
            </td>
        </tr>
    	<tr>
        	<td>
            	<label for="nom_<?php echo $cnt1; ?>"><?php echo $xml->Nom ?> :</label>
            </td>
        	<td>
                <input type="text" name="nom_<?php echo $cnt1; ?>" id="nom_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsutilisateur['nom']); ?>" size="32" maxlength="50" />
                <?php echo $tNGs->displayFieldHint("nom");?> <?php echo $tNGs->displayFieldError("utilisateur", "nom", $cnt1); ?> 
            </td>
        </tr>
    	<tr>
        	<td>
            	<label for="prenom_<?php echo $cnt1; ?>"><?php echo $xml->Prenom ?> :</label>
            </td>
        	<td>
                <input type="text" name="prenom_<?php echo $cnt1; ?>" id="prenom_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsutilisateur['prenom']); ?>" size="32" maxlength="50" />
                <?php echo $tNGs->displayFieldHint("prenom");?> <?php echo $tNGs->displayFieldError("utilisateur", "prenom", $cnt1); ?>
            </td>
        </tr>
    	<tr>
        	<td>
            	<label for="adresse_<?php echo $cnt1; ?>"><?php echo $xml-> Adresse ?> :</label>
            </td>
        	<td>
            	<input type="text" name="adresse_<?php echo $cnt1; ?>" id="adresse_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsutilisateur['adresse']); ?>" size="32" maxlength="100" />
				<?php echo $tNGs->displayFieldHint("adresse");?> <?php echo $tNGs->displayFieldError("utilisateur", "adresse", $cnt1); ?>
            </td>
        </tr>
        
    	<tr>
        	<td>
            	<label for="region_<?php echo $cnt1; ?>"><?php echo $xml->Region ?> :</label>
            </td>
        	<td>
                <select name="region_<?php echo $cnt1; ?>" id="region_<?php echo $cnt1; ?>" onChange="ajax('ajax/department.php?default=<?php echo $row_rsutilisateur['department']; ?>&id_region='+this.value,'#department_1');">
                    <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                    <?php 
                    do {  
                    ?>
                    <option value="<?php echo $row_region['id_region']?>"<?php if (!(strcmp($row_region['id_region'], $row_rsutilisateur['region']))) {echo "SELECTED";} ?>><?php echo ($row_region['nom_region']); ?></option>
                    <?php
                    } while ($row_region = mysql_fetch_assoc($region));
                        $rows = mysql_num_rows($region);
                        if($rows > 0) {
                            mysql_data_seek($region, 0);
                            $row_region = mysql_fetch_assoc($region);
                        }
                    ?>
                </select>
                <?php echo $tNGs->displayFieldError("utilisateur", "region", $cnt1); ?>
            </td>
        </tr>
        
        <tr>
            <td>
            <label for="department_<?php echo $cnt1; ?>">Department:</label>
            </td>
            <td>
            <select name="department_<?php echo $cnt1; ?>" id="department_<?php echo $cnt1; ?>" onchange="ajax('ajax/ville.php?default=<?php echo $row_rsutilisateur['ville']; ?>&id_departement='+this.value,'#ville_1');">
                <option value="">Department</option>  
                
                <?php 
                mysql_select_db($database_magazinducoin, $magazinducoin);
                $query_department = "SELECT * FROM departement WHERE id_departement='".$row_rsutilisateur['department']."' ORDER BY nom_departement ASC";
                $department = mysql_query($query_department, $magazinducoin) or die(mysql_error());
                $row_department = mysql_fetch_array($department);
                //$totalRows_regions = mysql_num_rows($regions);
                if($row_rsutilisateur['department']!=''){
                ?>
                    <option value="<?php echo $row_department['id_departement']?>"<?php if (!(strcmp($row_department['id_departement'], $row_rsutilisateur['department']))) {echo "SELECTED";} ?> title="<?php echo ($row_department['nom_departement']); ?>"><?php echo ($row_department['nom_departement']); ?></option>
                <?php }?>   
                
        
            </select>
            <?php echo $tNGs->displayFieldError("utilisateur", "department", $cnt1); ?>
            </td>
        </tr>
        <tr>
            <td>
            <label for="ville_<?php echo $cnt1; ?>"><?php echo $xml->Ville ?>:</label>
            </td>
            <td>
            <?php 
            mysql_select_db($database_magazinducoin, $magazinducoin);
                $query_ville2 = "SELECT * FROM maps_ville WHERE id_ville='".$row_rsutilisateur['ville']."' ORDER BY nom ASC";
                $ville2 = mysql_query($query_ville2, $magazinducoin) or die(mysql_error());
                $row_ville2 = mysql_fetch_array($ville2);
                //$totalRows_regions = mysql_num_rows($regions);
            ?>
            <select name="ville_<?php echo $cnt1; ?>" id="ville_<?php echo $cnt1; ?>">
                <option value=""><?php echo $xml->Region ?></option>  
                <?php if($row_rsutilisateur['ville']!=''){?>
                    <option value="<?php echo $row_ville2['id_ville']?>"<?php if (!(strcmp($row_ville2['id_ville'], $row_rsutilisateur['ville']))) {echo "SELECTED";} ?> title="<?php echo ($row_ville2['nom']); ?>"><?php echo ($row_ville2['nom']); ?> <?php echo ($row_ville2['cp']); ?></option>
                <?php }?>    
            </select>
            <?php echo $tNGs->displayFieldError("utilisateur", "ville", $cnt1); ?>
            </td>
        </tr>
        
<?php /*?>    	<tr>
        	<td>
            	<label for="ville_<?php echo $cnt1; ?>"><?php echo $xml->Ville ?> :</label>
            </td>
        	<td>
                <select name="ville_<?php echo $cnt1; ?>" id="ville_<?php echo $cnt1; ?>">
                    <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                </select>
                <?php echo $tNGs->displayFieldError("utilisateur", "ville", $cnt1); ?> 
            </td>
        </tr><?php */?>
        
    	<tr>
        	<td>
            	<label for="telephone_<?php echo $cnt1; ?>"><?php echo $xml-> Telephone ?> :</label>
            </td>
        	<td>
                <input type="text" name="telephone_<?php echo $cnt1; ?>" id="telephone_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsutilisateur['telephone']); ?>" size="32" maxlength="100" />
                <?php echo $tNGs->displayFieldHint("telephone");?> <?php echo $tNGs->displayFieldError("utilisateur", "telephone", $cnt1); ?>
            </td>
        </tr>
        <?php if($_SESSION['kt_login_level'] == 2) { ?> 
    	<tr>
        	<td>
            	<label for="description_<?php echo $cnt1; ?>"><?php echo $xml->Description ?> :</label>
            </td>
        	<td>
            <textarea name="description_<?php echo $cnt1; ?>" cols="32" rows="3" id="description_<?php echo $cnt1; ?>"><?php echo KT_escapeAttribute($row_rsutilisateur['description']); ?></textarea>
			<?php echo $tNGs->displayFieldHint("description");?> <?php echo $tNGs->displayFieldError("utilisateur", "description", $cnt1); ?>
		
            </td>
        </tr>
    	<tr>
        	<td>
            	<label for="photo_skill_<?php echo $cnt1; ?>">Type de photographe :</label>
            </td>
        	<td>
            
            <select name="photo_skill_<?php echo $cnt1; ?>" id="photo_skill_<?php echo $cnt1; ?>">
            	<option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                <option value="1" <?php if (!(strcmp("1", KT_escapeAttribute($row_rsutilisateur['photo_skill'])))) {echo "SELECTED";} ?>>Amateur</option>
                <option value="2" <?php if (!(strcmp("2", KT_escapeAttribute($row_rsutilisateur['photo_skill'])))) {echo "SELECTED";} ?>>Semi-professtionnel</option>
                <option value="3" <?php if (!(strcmp("3", KT_escapeAttribute($row_rsutilisateur['photo_skill'])))) {echo "SELECTED";} ?>>Professionnel</option>
            </select>
			<?php echo $tNGs->displayFieldHint("photo_skill");?> <?php echo $tNGs->displayFieldError("utilisateur", "photo_skill", $cnt1); ?>
		
            </td>
        </tr>        
        <tr>
        	<td>
            	<label for="website_<?php echo $cnt1; ?>">Site Internet :</label>
            </td>
        	<td>
            <input type="text" name="website_<?php echo $cnt1; ?>" id="website_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsutilisateur['website']); ?>" size="32" maxlength="100" />
			<?php echo $tNGs->displayFieldHint("website");?> <?php echo $tNGs->displayFieldError("utilisateur", "website", $cnt1); ?>
		
            </td>
        </tr>       
        <tr>
        	<td>
            	<label for="facebook_page_<?php echo $cnt1; ?>">Page Facebook :</label>
            </td>
        	<td>
            <input type="text" name="facebook_page_<?php echo $cnt1; ?>" id="facebook_page_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsutilisateur['facebook_page']); ?>" size="32" maxlength="100" />
			<?php echo $tNGs->displayFieldHint("facebook_page");?> <?php echo $tNGs->displayFieldError("utilisateur", "facebook_page", $cnt1); ?>
		
            </td>
        </tr>
        <tr valign="top">
            <td>
            <label for="photo_<?php echo $cnt1; ?>">Photo:</label>
            </td>
            <td>
            <?php if($row_rsutilisateur['photo']){?>
                <div class="file-wrapper" style="margin-top:5px;">
                    <input type="file" name="photo_<?php echo $cnt1; ?>" id="photo_<?php echo $cnt1; ?>"/>
                    <span class="button">Parcourir</span>
                </div>
                <div id="img">
                    <img src="assets/images/utilisateur/<?php echo KT_escapeAttribute($row_rsutilisateur['photo']); ?>" width="60" />
                </div> 
            <?php }else{?>
                <div class="file-wrapper" style="margin-top:5px;">
                    <input type="file" name="photo_<?php echo $cnt1; ?>" id="photo_<?php echo $cnt1; ?>"/>
                    <span class="button">Parcourir</span>
                </div>
            <?php }?>
            <?php echo $tNGs->displayFieldError("utilisateur", "photo", $cnt1); ?> 
            </td>
        </tr>
        <tr valign="top">
            <td>
            <label for="photo1_<?php echo $cnt1; ?>">Photo:</label>
            </td>
            <td>
            <?php if($row_rsutilisateur['photo1']){?>
                <div class="file-wrapper">
                    <input type="file" name="photo1_<?php echo $cnt1; ?>" id="photo1_<?php echo $cnt1; ?>"/>
                    <span class="button">Parcourir</span>
                </div>
                <div id="img">
                    <img src="assets/images/utilisateur/<?php echo KT_escapeAttribute($row_rsutilisateur['photo1']); ?>" width="60" />
                </div> 
            <?php }else{?>
                <div class="file-wrapper">
                    <input type="file" name="photo1_<?php echo $cnt1; ?>" id="photo1_<?php echo $cnt1; ?>"/>
                    <span class="button">Parcourir</span>
                </div>
            <?php }?>
            <?php echo $tNGs->displayFieldError("utilisateur", "photo1", $cnt1); ?> 
            </td>
        </tr>
        <tr valign="top">
            <td>
            <label for="photo2_<?php echo $cnt1; ?>">Photo:</label>
            </td>
            <td>
            <?php if($row_rsutilisateur['photo2']){?>
                <div class="file-wrapper">
                    <input type="file" name="photo2_<?php echo $cnt1; ?>" id="photo2_<?php echo $cnt1; ?>"/>
                    <span class="button">Parcourir</span>
                </div>
                <div id="img">
                    <img src="assets/images/utilisateur/<?php echo KT_escapeAttribute($row_rsutilisateur['photo2']); ?>" width="60" />
                </div> 
            <?php }else{?>
                <div class="file-wrapper">
                    <input type="file" name="photo2_<?php echo $cnt1; ?>" id="photo2_<?php echo $cnt1; ?>"/>
                    <span class="button">Parcourir</span>
                </div>
            <?php }?>
            <?php echo $tNGs->displayFieldError("utilisateur", "photo2", $cnt1); ?> 
            </td>
        </tr>
        <tr valign="top">
            <td>
            <label for="photo3_<?php echo $cnt1; ?>">Photo:</label>
            </td>
            <td>
            <?php if($row_rsutilisateur['photo3']){?>
                <div class="file-wrapper">
                    <input type="file" name="photo3_<?php echo $cnt1; ?>" id="photo3_<?php echo $cnt1; ?>"/>
                    <span class="button">Parcourir</span>
                </div>
                <div id="img">
                    <img src="assets/images/utilisateur/<?php echo KT_escapeAttribute($row_rsutilisateur['photo3']); ?>" width="60" />
                </div> 
            <?php }else{?>
                <div class="file-wrapper">
                    <input type="file" name="photo3_<?php echo $cnt1; ?>" id="photo3_<?php echo $cnt1; ?>"/>
                    <span class="button">Parcourir</span>
                </div>
            <?php }?>
            <?php echo $tNGs->displayFieldError("utilisateur", "photo3", $cnt1); ?> 
            </td>
        </tr>
        <?php } ?>
    	<tr>
        	<td>
            	<label for="old_password_<?php echo $cnt1; ?>"><?php echo $xml->Ancien_mot_de_passe ?> :</label>
            </td>
        	<td>
                <input type="password" name="old_password_<?php echo $cnt1; ?>" id="old_password_<?php echo $cnt1; ?>" value="" size="32" maxlength="200" />
				<?php echo $tNGs->displayFieldError("utilisateur", "old_password", $cnt1); ?>
            </td>
        </tr>
    	<tr>
        	<td>
            	<label for="password_<?php echo $cnt1; ?>"><?php echo $xml->Nouveau_mot_de_passe ; ?> :</label>
            </td>
        	<td>
            	<input type="password" name="password_<?php echo $cnt1; ?>" id="password_<?php echo $cnt1; ?>" value="" size="32" maxlength="200" />
            	<?php echo $tNGs->displayFieldHint("password");?> <?php echo $tNGs->displayFieldError("utilisateur", "password", $cnt1); ?>
            </td>
        </tr>
    	<tr>
        	<td>
            	<label for="re_password_<?php echo $cnt1; ?>"><?php echo $xml-> Ressaisir_le_mot_de_passe ?> :</label>
            </td>
        	<td>
            	<input type="password" name="re_password_<?php echo $cnt1; ?>" id="re_password_<?php echo $cnt1; ?>" value="" size="32" maxlength="200" />
            </td>
        </tr>
    	<tr>
        	<td colspan="2">
            <input type="submit" name="KT_Update1" class="image-submit" value="<?php echo NXT_getResource("Update_FB"); ?>" style="position:static;"/>
            <input type="button" name="KT_Cancel1" class="image-submit" value="<?php echo NXT_getResource("Cancel_FB"); ?>" style="position:static;" onClick="return UNI_navigateCancel(event, 'membre.php')" />
            <input type="hidden" name="kt_pk_utilisateur_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsutilisateur['kt_pk_utilisateur']); ?>" />
            </td>
        </tr>
	</table>              
			  
			  <?php } while ($row_rsutilisateur = mysql_fetch_assoc($rsutilisateur)); ?>
 </div>
  </form>
  <div class="clear"></div>
</div>
<div id="footer">
    <div class="recherche">
    &nbsp;
    </div>
    <?php include("modules/footer.php"); ?>
</div>

</body>
</html>
<?php
mysql_free_result($region);
?>