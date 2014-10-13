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
$formValidation->addField("id_magazin", true, "numeric", "", "", "", "");
$formValidation->addField("categorie", true, "numeric", "", "", "", "");
$formValidation->addField("sous_categorie", true, "numeric", "", "", "", "");
$formValidation->addField("titre", true, "text", "", "1", "80", "80 caractéres");
$formValidation->addField("prix", true, "double", "", "", "", "");
$formValidation->addField("en_stock", true, "numeric", "", "", "", "");
$formValidation->addField("photo1", true, "", "", "", "", "");
/*$formValidation->addField("code_bare", false, "text", "zip_generic", "12", "13", "Le code bare doit contenir 12 ou 13 caractéres");*/
$formValidation->addField("description", true, "text", "", "1", "800", "800 caractéres");
$tNGs->prepareValidation($formValidation);
// End trigger

//start Trigger_FileDelete2 trigger
//remove this line if you want to edit the code by hand 
function Trigger_FileDelete2(&$tNG) {
  $deleteObj = new tNG_FileDelete($tNG);
  $deleteObj->setFolder("../assets/images/produits/");
  $deleteObj->setDbFieldName("photo3");
  return $deleteObj->Execute();
}
//end Trigger_FileDelete2 trigger

//start Trigger_ImageUpload2 trigger
//remove this line if you want to edit the code by hand 
function Trigger_ImageUpload2(&$tNG) {
  $uploadObj = new tNG_ImageUpload($tNG);
  $uploadObj->setFormFieldName("photo3");
  $uploadObj->setDbFieldName("photo3");
  $uploadObj->setFolder("../assets/images/produits/");
  $uploadObj->setResize("true", 800, 600);
  $uploadObj->setMaxSize(1000);
  $uploadObj->setAllowedExtensions("gif, jpg, jpe, jpeg, png");
  $uploadObj->setRename("auto");
  return $uploadObj->Execute();
}
//end Trigger_ImageUpload2 trigger

//start Trigger_FileDelete1 trigger
//remove this line if you want to edit the code by hand 
function Trigger_FileDelete1(&$tNG) {
  $deleteObj = new tNG_FileDelete($tNG);
  $deleteObj->setFolder("../assets/images/produits/");
  $deleteObj->setDbFieldName("photo2");
  return $deleteObj->Execute();
}
//end Trigger_FileDelete1 trigger

//start Trigger_ImageUpload1 trigger
//remove this line if you want to edit the code by hand 
function Trigger_ImageUpload1(&$tNG) {
  $uploadObj = new tNG_ImageUpload($tNG);
  $uploadObj->setFormFieldName("photo2");
  $uploadObj->setDbFieldName("photo2");
  $uploadObj->setFolder("../assets/images/produits/");
  $uploadObj->setResize("true", 800, 600);
  $uploadObj->setMaxSize(1000);
  $uploadObj->setAllowedExtensions("gif, jpg, jpe, jpeg, png");
  $uploadObj->setRename("auto");
  return $uploadObj->Execute();
}
//end Trigger_ImageUpload1 trigger

//start Trigger_FileDelete trigger
//remove this line if you want to edit the code by hand 
function Trigger_FileDelete(&$tNG) {
  $deleteObj = new tNG_FileDelete($tNG);
  $deleteObj->setFolder("../assets/images/produits/");
  $deleteObj->setDbFieldName("photo1");
  return $deleteObj->Execute();
}
//end Trigger_FileDelete trigger

//start Trigger_ImageUpload trigger
//remove this line if you want to edit the code by hand 
function Trigger_ImageUpload(&$tNG) {
  $uploadObj = new tNG_ImageUpload($tNG);
  $uploadObj->setFormFieldName("photo1");
  $uploadObj->setDbFieldName("photo1");
  $uploadObj->setFolder("../assets/images/produits/");
  $uploadObj->setResize("true", 800, 600);
  $uploadObj->setMaxSize(1000);
  $uploadObj->setAllowedExtensions("gif, jpg, jpe, jpeg, png");
  $uploadObj->setRename("auto");
  return $uploadObj->Execute();
}
//end Trigger_ImageUpload trigger

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

if(isset($_GET['active'])){
	$id = $_GET['id'];
	$email = $_GET['email'];
	$sql_pro  = "UPDATE produits SET activate='1' WHERE id='".$id."'";
	$result_pro  = mysql_query($sql_pro ) or die (mysql_error());
	if($result_pro){
		$sql_select1 = "SELECT
							utilisateur.id AS user_id
							, utilisateur.nom
							, utilisateur.prenom
							, utilisateur.email
							, produits.titre
							, produits.id
						FROM
							produits
							INNER JOIN utilisateur 
								ON (produits.id_user = utilisateur.id)
						 WHERE produits.id='".$_GET['id']."' AND utilisateur.email='".$email."'";
		$query_select1 = mysql_query($sql_select1);
		$rs1=mysql_fetch_array($query_select1);
		SendMail_Ownner_produits_approve($rs1['email'],$rs1['nom'],$rs1['prenom'],$rs1['titre']);
		/*echo'<script>window.location="produits.php?info=ACTIVATED";</script>';*/
	}
}

if(isset($_GET['unactive'])){
	$id = $_GET['id'];
	$email = $_GET['email'];
	$sql_pro  = "UPDATE produits SET activate='2' WHERE id='".$id."'";
	$result_pro  = mysql_query($sql_pro ) or die (mysql_error());
	if($result_pro){
		$sql_select1 = "SELECT
							utilisateur.id AS user_id
							, utilisateur.nom
							, utilisateur.prenom
							, utilisateur.email
							, produits.titre
							, produits.id
						FROM
							produits
							INNER JOIN utilisateur 
								ON (produits.id_user = utilisateur.id)
						 WHERE produits.id='".$_GET['id']."' AND utilisateur.email='".$email."'";
		$query_select1 = mysql_query($sql_select1);
		$rs1=mysql_fetch_array($query_select1);
		SendMail_Ownner_produits_unapprove($rs1['email'],$rs1['nom'],$rs1['prenom'],$rs1['titre']);
	}
}

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset1 = "SELECT email, id FROM utilisateur ORDER BY email";
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset3 = "SELECT cat_name, cat_id FROM category WHERE parent_id = 0 AND type='0' ORDER BY cat_name";
$Recordset3 = mysql_query($query_Recordset3, $magazinducoin) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

// Make an insert transaction instance
$ins_produits = new tNG_multipleInsert($conn_magazinducoin);
$tNGs->addTransaction($ins_produits);
// Register triggers
$ins_produits->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_produits->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_produits->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$ins_produits->registerTrigger("AFTER", "Trigger_ImageUpload", 97);
$ins_produits->registerTrigger("AFTER", "Trigger_ImageUpload1", 97);
$ins_produits->registerTrigger("AFTER", "Trigger_ImageUpload2", 97);
// Add columns
$ins_produits->setTable("produits");
$ins_produits->addColumn("id_user", "NUMERIC_TYPE", "POST", "id_user");
$ins_produits->addColumn("id_magazin", "NUMERIC_TYPE", "POST", "id_magazin");
$ins_produits->addColumn("categorie", "NUMERIC_TYPE", "POST", "categorie");
$ins_produits->addColumn("sous_categorie", "NUMERIC_TYPE", "POST", "sous_categorie");
/*$ins_produits->addColumn("sous_categorie2", "NUMERIC_TYPE", "POST", "sous_categorie2");
$ins_produits->addColumn("sous_categorie3", "NUMERIC_TYPE", "POST", "sous_categorie3");*/
$ins_produits->addColumn("titre", "STRING_TYPE", "POST", "titre");
$ins_produits->addColumn("reference", "STRING_TYPE", "POST", "reference");
$ins_produits->addColumn("prix", "DOUBLE_TYPE", "POST", "prix");
$ins_produits->addColumn("en_stock", "NUMERIC_TYPE", "POST", "en_stock");
$ins_produits->addColumn("description", "STRING_TYPE", "POST", "description");
$ins_produits->addColumn("photo1", "FILE_TYPE", "FILES", "photo1");
$ins_produits->addColumn("photo2", "FILE_TYPE", "FILES", "photo2");
$ins_produits->addColumn("photo3", "FILE_TYPE", "FILES", "photo3");
$ins_produits->addColumn("date_ajout", "STRING_TYPE", "POST", "date_ajout");
$ins_produits->addColumn("date_echance", "STRING_TYPE", "POST", "date_echance");
$ins_produits->addColumn("code_bare", "STRING_TYPE", "POST", "code_bare");
$ins_produits->setPrimaryKey("id", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_produits = new tNG_multipleUpdate($conn_magazinducoin);
$tNGs->addTransaction($upd_produits);
// Register triggers
$upd_produits->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_produits->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_produits->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$upd_produits->registerTrigger("AFTER", "Trigger_ImageUpload", 97);
$upd_produits->registerTrigger("AFTER", "Trigger_ImageUpload1", 97);
$upd_produits->registerTrigger("AFTER", "Trigger_ImageUpload2", 97);
// Add columns
$upd_produits->setTable("produits");
$upd_produits->addColumn("id_user", "NUMERIC_TYPE", "POST", "id_user");
$upd_produits->addColumn("id_magazin", "NUMERIC_TYPE", "POST", "id_magazin");
$upd_produits->addColumn("categorie", "NUMERIC_TYPE", "POST", "categorie");
$upd_produits->addColumn("sous_categorie", "NUMERIC_TYPE", "POST", "sous_categorie");
/*$upd_produits->addColumn("sous_categorie2", "NUMERIC_TYPE", "POST", "sous_categorie2");
$upd_produits->addColumn("sous_categorie3", "NUMERIC_TYPE", "POST", "sous_categorie3");*/
$upd_produits->addColumn("titre", "STRING_TYPE", "POST", "titre");
$upd_produits->addColumn("reference", "STRING_TYPE", "POST", "reference");
$upd_produits->addColumn("prix", "DOUBLE_TYPE", "POST", "prix");
$upd_produits->addColumn("en_stock", "NUMERIC_TYPE", "POST", "en_stock");
$upd_produits->addColumn("description", "STRING_TYPE", "POST", "description");
$upd_produits->addColumn("photo1", "FILE_TYPE", "FILES", "photo1");
$upd_produits->addColumn("photo2", "FILE_TYPE", "FILES", "photo2");
$upd_produits->addColumn("photo3", "FILE_TYPE", "FILES", "photo3");
$upd_produits->addColumn("date_ajout", "DATE_TYPE", "CURRVAL", "");
$upd_produits->addColumn("date_echance", "DATE_TYPE", "CURRVAL", "");
$upd_produits->addColumn("code_bare", "STRING_TYPE", "POST", "code_bare");
$upd_produits->setPrimaryKey("id", "NUMERIC_TYPE", "GET", "id");

// Make an instance of the transaction object
$del_produits = new tNG_multipleDelete($conn_magazinducoin);
$tNGs->addTransaction($del_produits);
// Register triggers
$del_produits->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_produits->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$del_produits->registerTrigger("AFTER", "Trigger_FileDelete", 98);
$del_produits->registerTrigger("AFTER", "Trigger_FileDelete1", 98);
$del_produits->registerTrigger("AFTER", "Trigger_FileDelete2", 98);
// Add columns
$del_produits->setTable("produits");
$del_produits->setPrimaryKey("id", "NUMERIC_TYPE", "GET", "id");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsproduits = $tNGs->getRecordset("produits");
$row_rsproduits = mysql_fetch_assoc($rsproduits);
$totalRows_rsproduits = mysql_num_rows($rsproduits);
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
</head>
<body id="sp" 
<?php if(isset($_GET['id'])) { ?> onload="
ajax('../ajax/sous_categorie.php?default=<?php echo $row_rsproduits['sous_categorie']; ?>&id_parent=<?php echo $row_rsproduits['categorie']; ?>','#sous_categorie_1');
ajax('../ajax/sous_categorie.php?default=<?php echo $row_rsproduits['sous_categorie2']; ?>&id_parent=<?php echo $row_rsproduits['sous_categorie']; ?>','#sous_categorie2_1');
ajax('../ajax/sous_categorie.php?default=<?php echo $row_rsproduits['sous_categorie3']; ?>&id_parent=<?php echo $row_rsproduits['sous_categorie2']; ?>','#sous_categorie3_1');
ajax('../ajax/magasins.php?default=<?php echo $row_rsproduits['id_magazin']; ?>&id_user=<?php echo $row_rsproduits['id_user']; ?>', '#id_magazin_1'); "
<?php } ?>>
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
              Produits </h1>
            <div class="KT_tngform">
              <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" enctype="multipart/form-data">
                <?php $cnt1 = 0; ?>
                <?php do { ?>
                  <?php $cnt1++; ?>
                  <?php 
// Show IF Conditional region1 
if (@$totalRows_rsproduits > 1) {
?>
                    <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
                    <?php } 
// endif Conditional region1
?>
                  <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                    <tr>
                      <td class="KT_th"><label for="id_user_<?php echo $cnt1; ?>">Utilisateur:</label></td>
                      <td><select name="id_user_<?php echo $cnt1; ?>" id="id_user_<?php echo $cnt1; ?>"  onchange="ajax('../ajax/magasins.php?default=<?php echo $row_rsproduits['id_magazin']; ?>&id_user='+this.value, '#id_magazin_<?php echo $cnt1; ?>');">
                          <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
							<?php  do { ?>
                            <option value="<?php echo $row_Recordset1['id']?>"<?php if (!(strcmp($row_Recordset1['id'], $row_rsproduits['id_user']))) {echo "SELECTED";} ?>>
								<?php 
                                $vowels = array("@");
                                echo $onlyconsonants = str_replace($vowels, "&#64;", $row_Recordset1['email']);
                                ?>
                            </option>
                            <?php
                            } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
                            $rows = mysql_num_rows($Recordset1);
								if($rows > 0) {
									mysql_data_seek($Recordset1, 0);
									$row_Recordset1 = mysql_fetch_assoc($Recordset1);
								}
                            ?>
                        </select>
                          <?php echo $tNGs->displayFieldError("produits", "id_user", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="id_magazin_<?php echo $cnt1; ?>">Magasin:</label></td>
                      <td>
                      	<select name="id_magazin_<?php echo $cnt1; ?>" id="id_magazin_<?php echo $cnt1; ?>">
							<option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                        </select>
						<?php echo $tNGs->displayFieldError("produits", "id_magazin", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="categorie_<?php echo $cnt1; ?>">Categorie:</label></td>
                      <td><select name="categorie_<?php echo $cnt1; ?>" id="categorie_<?php echo $cnt1; ?>" onchange="ajax('../ajax/sous_categorie.php?default=<?php echo $row_rsproduits['sous_categorie']; ?>&id_parent='+this.value,'#sous_categorie_<?php echo $cnt1; ?>');">
                          <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                          <?php 
do {  
?>
                          <option value="<?php echo $row_Recordset3['cat_id']?>"<?php if (!(strcmp($row_Recordset3['cat_id'], $row_rsproduits['categorie']))) {echo "SELECTED";} ?>><?php echo ($row_Recordset3['cat_name']); ?></option>
                          <?php
} while ($row_Recordset3 = mysql_fetch_assoc($Recordset3));
  $rows = mysql_num_rows($Recordset3);
  if($rows > 0) {
      mysql_data_seek($Recordset3, 0);
	  $row_Recordset3 = mysql_fetch_assoc($Recordset3);
  }
?>
                        </select>
                          <?php echo $tNGs->displayFieldError("produits", "categorie", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="sous_categorie_<?php echo $cnt1; ?>">Sous categorie:</label></td>
                      <td> <select name="sous_categorie_<?php echo $cnt1; ?>" id="sous_categorie_<?php echo $cnt1; ?>" onchange="ajax('../ajax/sous_categorie.php?default=<?php echo $row_rsproduits['sous_categorie2']; ?>&id_parent='+this.value, '#sous_categorie2_<?php echo $cnt1; ?>');">
                          <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                        </select>
                          <?php echo $tNGs->displayFieldHint("sous_categorie");?> <?php echo $tNGs->displayFieldError("produits", "sous_categorie", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="titre_<?php echo $cnt1; ?>">Titre:</label></td>
                      <td><input type="text" name="titre_<?php echo $cnt1; ?>" id="titre_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsproduits['titre']); ?>" size="32" maxlength="200" />
                          <?php echo $tNGs->displayFieldHint("titre");?> <?php echo $tNGs->displayFieldError("produits", "titre", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="reference_<?php echo $cnt1; ?>">Reference:</label></td>
                      <td><input type="text" name="reference_<?php echo $cnt1; ?>" id="reference_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsproduits['reference']); ?>" size="32" maxlength="200" />
                          <?php echo $tNGs->displayFieldHint("reference");?> <?php echo $tNGs->displayFieldError("produits", "reference", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="prix_<?php echo $cnt1; ?>">Prix:</label></td>
                      <td><input type="text" name="prix_<?php echo $cnt1; ?>" id="prix_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsproduits['prix']); ?>" size="7" />
                          <?php echo $tNGs->displayFieldHint("prix");?> <?php echo $tNGs->displayFieldError("produits", "prix", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="en_stock_<?php echo $cnt1; ?>_1">En stock:</label></td>
                      <td><div>
                          <input <?php if (!(strcmp(KT_escapeAttribute($row_rsproduits['en_stock']),"1"))) {echo "CHECKED";} ?> type="radio" name="en_stock_<?php echo $cnt1; ?>" id="en_stock_<?php echo $cnt1; ?>_1" value="1" />
                          <label for="en_stock_<?php echo $cnt1; ?>_1">Oui</label>
                        </div>
                          <div>
                            <input <?php if (!(strcmp(KT_escapeAttribute($row_rsproduits['en_stock']),"0"))) {echo "CHECKED";} ?> type="radio" name="en_stock_<?php echo $cnt1; ?>" id="en_stock_<?php echo $cnt1; ?>_2" value="0" />
                            <label for="en_stock_<?php echo $cnt1; ?>_2">Non</label>
                          </div>
                        <?php echo $tNGs->displayFieldError("produits", "en_stock", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="description_<?php echo $cnt1; ?>">Description:</label></td>
                      <td><textarea name="description_<?php echo $cnt1; ?>" id="description_<?php echo $cnt1; ?>" cols="50" rows="5"><?php echo KT_escapeAttribute($row_rsproduits['description']); ?></textarea>
                          <?php echo $tNGs->displayFieldHint("description");?> <?php echo $tNGs->displayFieldError("produits", "description", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="photo1_<?php echo $cnt1; ?>">Photo: *</label></td>
                      <td><input type="file" name="photo1_<?php echo $cnt1; ?>" id="photo1_<?php echo $cnt1; ?>" size="32" />
                      <?php if($row_rsproduits['photo1']) { ?>
                      <div style="float:left" id="imgContiner1">
                      	<img src="../assets/images/produits/<?php echo KT_escapeAttribute($row_rsproduits['photo1']); ?>" width="60" />&nbsp;&nbsp; <a href="javascript:ajax('../ajax/supprimer_photo.php?t=produits&c=photo1&id=<?php echo $_GET['id']; ?>&f=<?php echo KT_escapeAttribute($row_rsproduits['photo1']); ?>','#imgContiner1');" style="color:#333333">Supprimer photo</a>
                      </div> 
					<?php } ?>
                          <?php echo $tNGs->displayFieldError("produits", "photo1", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="photo2_<?php echo $cnt1; ?>">Photo2:</label></td>
                      <td><input type="file" name="photo2_<?php echo $cnt1; ?>" id="photo2_<?php echo $cnt1; ?>" size="32" />
                          <?php echo $tNGs->displayFieldError("produits", "photo2", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="photo3_<?php echo $cnt1; ?>">Photo3:</label></td>
                      <td><input type="file" name="photo3_<?php echo $cnt1; ?>" id="photo3_<?php echo $cnt1; ?>" size="32" />
                          <?php echo $tNGs->displayFieldError("produits", "photo3", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th">Date d'ajout:</td>
                      <td><?php echo KT_formatDate($row_rsproduits['date_ajout']); ?></td>
                    </tr>
                    <tr>
                      <td class="KT_th">Date d'échéance:</td>
                      <td><?php echo KT_formatDate($row_rsproduits['date_echance']); ?></td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="code_bare_<?php echo $cnt1; ?>">Code bare:</label></td>
                      <td><input type="text" name="code_bare_<?php echo $cnt1; ?>" id="code_bare_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsproduits['code_bare']); ?>" size="20" maxlength="20" />
                          <?php echo $tNGs->displayFieldHint("code_bare");?> <?php echo $tNGs->displayFieldError("produits", "code_bare", $cnt1); ?> </td>
                    </tr>
                  </table>
                                            <?php 
      // Show IF Conditional region1
      if (@$_GET['id'] == "") {
      ?>
  <input type="hidden" name="date_ajout_<?php echo $cnt1; ?>" id="date_ajout_<?php echo $cnt1; ?>" value="<?php echo date('Y-m-d'); ?>" />
  <input type="hidden" name="date_echance_<?php echo $cnt1; ?>" id="date_echance_<?php echo $cnt1; ?>" value="<?php 
  echo date('Y-m-d',mktime(0,0,0,date('m')+2,date('d'),date('Y'))); ?>" />   
      <?php }
      // endif Conditional region1
      ?>
                  <input type="hidden" name="kt_pk_produits_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsproduits['kt_pk_produits']); ?>" />
                  <?php } while ($row_rsproduits = mysql_fetch_assoc($rsproduits)); ?>
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
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset3);
?>