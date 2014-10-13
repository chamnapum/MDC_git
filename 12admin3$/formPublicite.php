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
$formValidation->addField("id_produit", true, "numeric", "", "", "", "");
$formValidation->addField("titre", true, "text", "", "", "", "");
$formValidation->addField("region", true, "numeric", "", "", "", "");
$formValidation->addField("emplacement", true, "numeric", "", "", "", "");
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
$query_emplacement = "SELECT * FROM pub_emplacement ORDER BY titre ASC";
$emplacement = mysql_query($query_emplacement, $magazinducoin) or die(mysql_error());
$row_emplacement = mysql_fetch_assoc($emplacement);
$totalRows_emplacement = mysql_num_rows($emplacement);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset1 = "SELECT email, id FROM utilisateur ORDER BY email";
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset2 = "SELECT titre, id FROM produits ORDER BY titre";
$Recordset2 = mysql_query($query_Recordset2, $magazinducoin) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset3 = "SELECT nom_region, id_region FROM region ORDER BY nom_region";
$Recordset3 = mysql_query($query_Recordset3, $magazinducoin) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

// Make an insert transaction instance
$ins_pub = new tNG_multipleInsert($conn_magazinducoin);
$tNGs->addTransaction($ins_pub);
// Register triggers
$ins_pub->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_pub->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_pub->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$ins_pub->setTable("pub");
$ins_pub->addColumn("id_user", "NUMERIC_TYPE", "POST", "id_user");
$ins_pub->addColumn("id_produit", "NUMERIC_TYPE", "POST", "id_produit");
$ins_pub->addColumn("titre", "STRING_TYPE", "POST", "titre");
$ins_pub->addColumn("region", "NUMERIC_TYPE", "POST", "region");
$ins_pub->addColumn("emplacement", "NUMERIC_TYPE", "POST", "emplacement");
$ins_pub->addColumn("date_debut", "DATE_TYPE", "POST", "date_debut");
$ins_pub->addColumn("date_fin", "DATE_TYPE", "POST", "date_fin");
$ins_pub->addColumn("payer", "NUMERIC_TYPE", "POST", "payer");
$ins_pub->setPrimaryKey("id", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_pub = new tNG_multipleUpdate($conn_magazinducoin);
$tNGs->addTransaction($upd_pub);
// Register triggers
$upd_pub->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_pub->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_pub->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$upd_pub->setTable("pub");
$upd_pub->addColumn("id_user", "NUMERIC_TYPE", "POST", "id_user");
$upd_pub->addColumn("id_produit", "NUMERIC_TYPE", "POST", "id_produit");
$upd_pub->addColumn("titre", "STRING_TYPE", "POST", "titre");
$upd_pub->addColumn("region", "NUMERIC_TYPE", "POST", "region");
$upd_pub->addColumn("emplacement", "NUMERIC_TYPE", "POST", "emplacement");
$upd_pub->addColumn("date_debut", "DATE_TYPE", "POST", "date_debut");
$upd_pub->addColumn("date_fin", "DATE_TYPE", "POST", "date_fin");
$upd_pub->addColumn("payer", "NUMERIC_TYPE", "POST", "payer");
$upd_pub->setPrimaryKey("id", "NUMERIC_TYPE", "GET", "id");

// Make an instance of the transaction object
$del_pub = new tNG_multipleDelete($conn_magazinducoin);
$tNGs->addTransaction($del_pub);
// Register triggers
$del_pub->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_pub->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_pub->setTable("pub");
$del_pub->setPrimaryKey("id", "NUMERIC_TYPE", "GET", "id");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rspub = $tNGs->getRecordset("pub");
$row_rspub = mysql_fetch_assoc($rspub);
$totalRows_rspub = mysql_num_rows($rspub);
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
              Pub </h1>
            <div class="KT_tngform">
              <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
                <?php $cnt1 = 0; ?>
                <?php do { ?>
                  <?php $cnt1++; ?>
                  <?php 
// Show IF Conditional region1 
if (@$totalRows_rspub > 1) {
?>
                    <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
                    <?php } 
// endif Conditional region1
?>
                  <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                    <tr>
                      <td class="KT_th"><label for="id_user_<?php echo $cnt1; ?>">Utilisateur:</label></td>
                      <td><select name="id_user_<?php echo $cnt1; ?>" id="id_user_<?php echo $cnt1; ?>" onchange="ajax('../ajax/produits.php?default=<?php echo $row_rspub['id_produit']; ?>&id_user='+this.value,'#id_produit_<?php echo $cnt1; ?>');">
                          <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                          <?php 
do {  
?>
                          <option value="<?php echo $row_Recordset1['id']?>"<?php if (!(strcmp($row_Recordset1['id'], $row_rspub['id_user']))) {echo "SELECTED";} ?>>
						  <?php 
						  $vowels = array("@");
						  echo $onlyconsonants = str_replace($vowels, "&#64;", $row_Recordset1['email']);
						  ?></option>
                          <?php
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
  $rows = mysql_num_rows($Recordset1);
  if($rows > 0) {
      mysql_data_seek($Recordset1, 0);
	  $row_Recordset1 = mysql_fetch_assoc($Recordset1);
  }
?>
                        </select>
                          <?php echo $tNGs->displayFieldError("pub", "id_user", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="id_produit_<?php echo $cnt1; ?>">Produit:</label></td>
                      <td><select name="id_produit_<?php echo $cnt1; ?>" id="id_produit_<?php echo $cnt1; ?>">
                          <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                          <?php 
do {  
?>
                          <option value="<?php echo $row_Recordset2['id']?>"<?php if (!(strcmp($row_Recordset2['id'], $row_rspub['id_produit']))) {echo "SELECTED";} ?>><?php echo $row_Recordset2['titre']?></option>
                          <?php
} while ($row_Recordset2 = mysql_fetch_assoc($Recordset2));
  $rows = mysql_num_rows($Recordset2);
  if($rows > 0) {
      mysql_data_seek($Recordset2, 0);
	  $row_Recordset2 = mysql_fetch_assoc($Recordset2);
  }
?>
                        </select>
                          <?php echo $tNGs->displayFieldError("pub", "id_produit", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="titre_<?php echo $cnt1; ?>">Titre:</label></td>
                      <td><input type="text" name="titre_<?php echo $cnt1; ?>" id="titre_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rspub['titre']); ?>" size="32" maxlength="100" />
                          <?php echo $tNGs->displayFieldHint("titre");?> <?php echo $tNGs->displayFieldError("pub", "titre", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="region_<?php echo $cnt1; ?>">Région:</label></td>
                      <td><select name="region_<?php echo $cnt1; ?>" id="region_<?php echo $cnt1; ?>">
                          <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                          <?php 
do {  
?>
                          <option value="<?php echo $row_Recordset3['id_region']?>"<?php if (!(strcmp($row_Recordset3['id_region'], $row_rspub['region']))) {echo "SELECTED";} ?>><?php echo ($row_Recordset3['nom_region']); ?></option>
                          <?php
} while ($row_Recordset3 = mysql_fetch_assoc($Recordset3));
  $rows = mysql_num_rows($Recordset3);
  if($rows > 0) {
      mysql_data_seek($Recordset3, 0);
	  $row_Recordset3 = mysql_fetch_assoc($Recordset3);
  }
?>
                        </select>
                          <?php echo $tNGs->displayFieldError("pub", "region", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="emplacement_<?php echo $cnt1; ?>">Emplacement:</label></td>
                      <td><select name="emplacement_<?php echo $cnt1; ?>" id="emplacement_<?php echo $cnt1; ?>">
                          <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                          <?php 
do {  
?>
                          <option value="<?php echo $row_emplacement['id']?>"<?php if (!(strcmp($row_emplacement['id'], $row_rspub['emplacement']))) {echo "SELECTED";} ?>><?php echo ($row_emplacement['titre']); ?></option>
                          <?php
} while ($row_emplacement = mysql_fetch_assoc($emplacement));
  $rows = mysql_num_rows($emplacement);
  if($rows > 0) {
      mysql_data_seek($emplacement, 0);
	  $row_emplacement = mysql_fetch_assoc($emplacement);
  }
?>
                        </select>
                          <?php echo $tNGs->displayFieldError("pub", "emplacement", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="date_debut_<?php echo $cnt1; ?>">Date début:</label></td>
                      <td><input type="text" name="date_debut_<?php echo $cnt1; ?>" id="date_debut_<?php echo $cnt1; ?>" value="<?php echo KT_formatDate($row_rspub['date_debut']); ?>" size="10" maxlength="22" />
                          <?php echo $tNGs->displayFieldHint("date_debut");?> <?php echo $tNGs->displayFieldError("pub", "date_debut", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="date_fin_<?php echo $cnt1; ?>">Date fin:</label></td>
                      <td><input type="text" name="date_fin_<?php echo $cnt1; ?>" id="date_fin_<?php echo $cnt1; ?>" value="<?php echo KT_formatDate($row_rspub['date_fin']); ?>" size="10" maxlength="22" />
                          <?php echo $tNGs->displayFieldHint("date_fin");?> <?php echo $tNGs->displayFieldError("pub", "date_fin", $cnt1); ?> </td>
                    </tr>
                  </table>
                  <input type="hidden" name="kt_pk_pub_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rspub['kt_pk_pub']); ?>" />
                  <input type="hidden" name="payer_<?php echo $cnt1; ?>" value="1" />
                  <?php } while ($row_rspub = mysql_fetch_assoc($rspub)); ?>
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
mysql_free_result($emplacement);

mysql_free_result($Recordset1);

mysql_free_result($Recordset2);

mysql_free_result($Recordset3);
?>