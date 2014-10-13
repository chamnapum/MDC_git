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
$formValidation->addField("reduction", true, "text", "", "", "", "");
$formValidation->addField("date_debut", true, "date", "date", "", "", "");
$formValidation->addField("date_fin", true, "date", "date", "", "", "");
$formValidation->addField("titre", true, "text", "", "", "", "");
$formValidation->addField("categories", true, "numeric", "", "", "", "");
$formValidation->addField("id_magasin", true, "numeric", "", "", "", "");
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



$colname_Recordset1 = "-1";
if (isset($_GET['0'])) {
  $colname_Recordset1 = $_GET['0'];
}
mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset1 = ("SELECT cat_id, parent_id, cat_name FROM category WHERE parent_id = 0");
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset3 = "SELECT cat_name, cat_id FROM category WHERE type='1' ORDER BY cat_name";
$Recordset3 = mysql_query($query_Recordset3, $magazinducoin) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset2 = "SELECT * FROM magazins";
$Recordset2 = mysql_query($query_Recordset2, $magazinducoin) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset4 = "SELECT nom_magazin, id_magazin FROM magazins ORDER BY nom_magazin";
$Recordset4 = mysql_query($query_Recordset4, $magazinducoin) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset5 = "SELECT email, id FROM utilisateur ORDER BY email";
$Recordset5 = mysql_query($query_Recordset5, $magazinducoin) or die(mysql_error());
$row_Recordset5 = mysql_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysql_num_rows($Recordset5);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset6 = "SELECT cat_name, parent_id FROM category WHERE type='1' ORDER BY cat_name";
$Recordset6 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$row_Recordset6 = mysql_fetch_assoc($Recordset6);
$totalRows_Recordset6 = mysql_num_rows($Recordset6);

// Make an insert transaction instance
$ins_coupons = new tNG_multipleInsert($conn_magazinducoin);
$tNGs->addTransaction($ins_coupons);
// Register triggers
$ins_coupons->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_coupons->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_coupons->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$ins_coupons->setTable("coupons");
$ins_coupons->addColumn("id_user", "NUMERIC_TYPE", "POST", "id_user");
$ins_coupons->addColumn("reduction", "STRING_TYPE", "POST", "reduction");
$ins_coupons->addColumn("date_debut", "DATE_TYPE", "POST", "date_debut");
$ins_coupons->addColumn("date_fin", "DATE_TYPE", "POST", "date_fin");
$ins_coupons->addColumn("titre", "STRING_TYPE", "POST", "titre");
$ins_coupons->addColumn("categories", "NUMERIC_TYPE", "POST", "categories");
$ins_coupons->addColumn("sous_categorie", "NUMERIC_TYPE", "POST", "sous_categorie");
$ins_coupons->addColumn("id_magasin", "NUMERIC_TYPE", "POST", "id_magasin");
$ins_coupons->addColumn("code_bare", "STRING_TYPE", "POST", "code_bare");
$ins_coupons->addColumn("min_achat", "DOUBLE_TYPE", "POST", "min_achat");
$ins_coupons->addColumn("description", "STRING_TYPE", "POST", "description");
$ins_coupons->setPrimaryKey("id_coupon", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_coupons = new tNG_multipleUpdate($conn_magazinducoin);
$tNGs->addTransaction($upd_coupons);
// Register triggers
$upd_coupons->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_coupons->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_coupons->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$upd_coupons->setTable("coupons");
$upd_coupons->addColumn("id_user", "NUMERIC_TYPE", "POST", "id_user");
$upd_coupons->addColumn("reduction", "STRING_TYPE", "POST", "reduction");
$upd_coupons->addColumn("date_debut", "DATE_TYPE", "POST", "date_debut");
$upd_coupons->addColumn("date_fin", "DATE_TYPE", "POST", "date_fin");
$upd_coupons->addColumn("titre", "STRING_TYPE", "POST", "titre");
$upd_coupons->addColumn("categories", "NUMERIC_TYPE", "POST", "categories");
$upd_coupons->addColumn("sous_categorie", "NUMERIC_TYPE", "POST", "sous_categorie");
$upd_coupons->addColumn("id_magasin", "NUMERIC_TYPE", "POST", "id_magasin");
$upd_coupons->addColumn("code_bare", "STRING_TYPE", "POST", "code_bare");
$upd_coupons->addColumn("min_achat", "DOUBLE_TYPE", "POST", "min_achat");
$upd_coupons->addColumn("description", "STRING_TYPE", "POST", "description");
$upd_coupons->setPrimaryKey("id_coupon", "NUMERIC_TYPE", "GET", "id_coupon");

// Make an instance of the transaction object
$del_coupons = new tNG_multipleDelete($conn_magazinducoin);
$tNGs->addTransaction($del_coupons);
// Register triggers
$del_coupons->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_coupons->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_coupons->setTable("coupons");
$del_coupons->setPrimaryKey("id_coupon", "NUMERIC_TYPE", "GET", "id_coupon");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rscoupons = $tNGs->getRecordset("coupons");
$row_rscoupons = mysql_fetch_assoc($rscoupons);
$totalRows_rscoupons = mysql_num_rows($rscoupons);
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
if (@$_GET['id_coupon'] == "") {
?>
                <?php echo NXT_getResource("Insert_FH"); ?>
                <?php 
// else Conditional region1
} else { ?>
                <?php echo NXT_getResource("Update_FH"); ?>
                <?php } 
// endif Conditional region1
?>
              Coupons </h1>
            <div class="KT_tngform">
              <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
                <?php $cnt1 = 0; ?>
                <?php do { ?>
                  <?php $cnt1++; ?>
                  <?php 
// Show IF Conditional region1 
if (@$totalRows_rscoupons > 1) {
?>
                    <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
                    <?php } 
// endif Conditional region1
?>
                  <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                    <tr>
                      <td class="KT_th"><label for="id_user_<?php echo $cnt1; ?>">user:</label></td>
                      <td><select name="id_user_<?php echo $cnt1; ?>" id="id_user_<?php echo $cnt1; ?>">
                          <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                          <?php 
do {  
?>
                          <option value="<?php echo $row_Recordset5['id']?>"<?php if (!(strcmp($row_Recordset5['id'], $row_rscoupons['id_user']))) {echo "SELECTED";} ?>><?php echo $row_Recordset5['email']?></option>
                          <?php
} while ($row_Recordset5 = mysql_fetch_assoc($Recordset5));
  $rows = mysql_num_rows($Recordset5);
  if($rows > 0) {
      mysql_data_seek($Recordset5, 0);
	  $row_Recordset5 = mysql_fetch_assoc($Recordset5);
  }
?>
                        </select>
                          <?php echo $tNGs->displayFieldError("coupons", "id_user", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="reduction_<?php echo $cnt1; ?>">Reduction:</label></td>
                      <td><input type="text" name="reduction_<?php echo $cnt1; ?>" id="reduction_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rscoupons['reduction']); ?>" size="5" maxlength="5" />
                          <?php echo $tNGs->displayFieldHint("reduction");?> <?php echo $tNGs->displayFieldError("coupons", "reduction", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="date_debut_<?php echo $cnt1; ?>">Date_debut:</label></td>
                      <td><input type="text" name="date_debut_<?php echo $cnt1; ?>" id="date_debut_<?php echo $cnt1; ?>" value="<?php echo KT_formatDate($row_rscoupons['date_debut']); ?>" size="10" maxlength="22" />
                          <?php echo $tNGs->displayFieldHint("date_debut");?> <?php echo $tNGs->displayFieldError("coupons", "date_debut", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="date_fin_<?php echo $cnt1; ?>">Date:</label></td>
                      <td><input type="text" name="date_fin_<?php echo $cnt1; ?>" id="date_fin_<?php echo $cnt1; ?>" value="<?php echo KT_formatDate($row_rscoupons['date_fin']); ?>" size="10" maxlength="22" />
                          <?php echo $tNGs->displayFieldHint("date_fin");?> <?php echo $tNGs->displayFieldError("coupons", "date_fin", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="titre_<?php echo $cnt1; ?>">Titre:</label></td>
                      <td><input type="text" name="titre_<?php echo $cnt1; ?>" id="titre_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rscoupons['titre']); ?>" size="32" maxlength="100" />
                          <?php echo $tNGs->displayFieldHint("titre");?> <?php echo $tNGs->displayFieldError("coupons", "titre", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="categories_<?php echo $cnt1; ?>">Categories:</label></td>
                      <td><select name="categories_<?php echo $cnt1; ?>" id="categories_<?php echo $cnt1; ?>"
  onchange="ajax('../ajax/sous_categorie.php?default=0&parent_id='+this.value,'#sous_categorie'); "
                      
                      >
                          <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                          <?php 
do {  
?>
                          <option value="<?php echo $row_Recordset6['parent_id']?>"<?php if (!(strcmp($row_Recordset6['parent_id'], $row_rscoupons['categories']))) {echo "SELECTED";} ?>><?php echo $row_Recordset6['cat_name']?></option>
                          <?php
} while ($row_Recordset6 = mysql_fetch_assoc($Recordset6));
  $rows = mysql_num_rows($Recordset6);
  if($rows > 0) {
      mysql_data_seek($Recordset6, 0);
	  $row_Recordset6 = mysql_fetch_assoc($Recordset6);
  }
?>
                        </select>
                          <?php echo $tNGs->displayFieldError("coupons", "categories", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="sous_categorie_<?php echo $cnt1; ?>">Sous_categorie:</label></td>
                      <td><select name="sous_categorie_<?php echo $cnt1; ?>" id="sous_categorie"
                      
                      
                      >
                          <option value="" >Selectionnez une categorie</option>

                        </select>
                          <?php echo $tNGs->displayFieldError("coupons", "sous_categorie", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="id_magasin_<?php echo $cnt1; ?>">Id_magasin:</label></td>
                      <td><select name="id_magasin_<?php echo $cnt1; ?>" id="id_magasin_<?php echo $cnt1; ?>">
                          <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                          <?php 
do {  
?>
                          <option value="<?php echo $row_Recordset4['id_magazin']?>"<?php if (!(strcmp($row_Recordset4['id_magazin'], $row_rscoupons['id_magasin']))) {echo "SELECTED";} ?>><?php echo $row_Recordset4['nom_magazin']?></option>
                          <?php
} while ($row_Recordset4 = mysql_fetch_assoc($Recordset4));
  $rows = mysql_num_rows($Recordset4);
  if($rows > 0) {
      mysql_data_seek($Recordset4, 0);
	  $row_Recordset4 = mysql_fetch_assoc($Recordset4);
  }
?>
                        </select>
                          <?php echo $tNGs->displayFieldError("coupons", "id_magasin", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="code_bare_<?php echo $cnt1; ?>">Code_bare:</label></td>
                      <td><input type="text" name="code_bare_<?php echo $cnt1; ?>" id="code_bare_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rscoupons['code_bare']); ?>" size="32" maxlength="100" />
                          <?php echo $tNGs->displayFieldHint("code_bare");?> <?php echo $tNGs->displayFieldError("coupons", "code_bare", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="min_achat_<?php echo $cnt1; ?>">Min_achat:</label></td>
                      <td><input type="text" name="min_achat_<?php echo $cnt1; ?>" id="min_achat_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rscoupons['min_achat']); ?>" size="7" />
                          <?php echo $tNGs->displayFieldHint("min_achat");?> <?php echo $tNGs->displayFieldError("coupons", "min_achat", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="description_<?php echo $cnt1; ?>">Description:</label></td>
                      <td><textarea name="description_<?php echo $cnt1; ?>" id="description_<?php echo $cnt1; ?>" cols="50" rows="5"><?php echo KT_escapeAttribute($row_rscoupons['description']); ?></textarea>
                          <?php echo $tNGs->displayFieldHint("description");?> <?php echo $tNGs->displayFieldError("coupons", "description", $cnt1); ?> </td>
                    </tr>
                  </table>
                  <input type="hidden" name="kt_pk_coupons_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rscoupons['kt_pk_coupons']); ?>" />
                  <?php } while ($row_rscoupons = mysql_fetch_assoc($rscoupons)); ?>
                <div class="KT_bottombuttons">
                  <div>
                    <?php 
      // Show IF Conditional region1
      if (@$_GET['id_coupon'] == "") {
      ?>
                      <input type="submit" name="KT_Insert1" id="KT_Insert1" value="<?php echo NXT_getResource("Insert_FB"); ?>" />
                      <?php 
      // else Conditional region1
      } else { ?>
                      <div class="KT_operations">
                        <input type="submit" name="KT_Insert1" value="<?php echo NXT_getResource("Insert as new_FB"); ?>" onclick="nxt_form_insertasnew(this, 'id_coupon')" />
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

mysql_free_result($Recordset2);

mysql_free_result($Recordset4);

mysql_free_result($Recordset5);

mysql_free_result($Recordset6);
?>