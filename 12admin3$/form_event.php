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
$formValidation->addField("titre", true, "text", "", "1", "80", "80 caractéres");
$formValidation->addField("id_user", true, "numeric", "", "", "", "");
$formValidation->addField("active", true, "numeric", "", "", "", "");
$formValidation->addField("date_debut", true, "date", "", "", "", "");
$formValidation->addField("date_fin", true, "date", "", "", "", "");
$formValidation->addField("id_magazin", true, "numeric", "", "", "", "");
$formValidation->addField("description", true, "text", "", "1", "800", "800 caractéres");
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

if(isset($_GET['approuve'])){
	$id = $_GET['id'];
	$email = $_GET['email'];
	$sql_pro  = "UPDATE evenements SET approuve='1' WHERE event_id='".$id."'";
	$result_pro  = mysql_query($sql_pro ) or die (mysql_error());
	if($result_pro){
		$sql_select1 = "SELECT 
						  utilisateur.id,
						  utilisateur.nom,
						  utilisateur.prenom,
						  utilisateur.email,
						  evenements.event_id,
						  evenements.titre,
						  evenements.description,
						  magazins.nom_magazin,
						  magazins.adresse,
						  (SELECT 
							nom_region 
						  FROM
							region 
						  WHERE id_region = magazins.region) AS region,
						  (SELECT 
							nom 
						  FROM
							maps_ville 
						  WHERE id_ville = magazins.ville) AS ville 
						FROM
						  magazins 
						  INNER JOIN evenements 
							ON (
							  magazins.id_magazin = evenements.id_magazin
							) 
						  INNER JOIN utilisateur 
							ON (evenements.id_user = utilisateur.id)
						WHERE  evenements.event_id='".$_GET['id']."' AND utilisateur.email='".$email."'";
		$query_select1 = mysql_query($sql_select1);
		$rs1=mysql_fetch_array($query_select1);
		
		$type = 'Evenement';
		//$type = mb_convert_encoding($type1,'HTML-ENTITIES','utf-8');
		$date = date("Y-m-d");
		
		$sql_mail ="SELECT 
					  evenements.event_id,
					  utilisateur.email 
					FROM
					  magazins 
					  INNER JOIN evenements 
						ON (
						  magazins.id_magazin = evenements.id_magazin
						) 
					  INNER JOIN sabonne 
						ON (
						  evenements.id_magazin = sabonne.id_magasin
						) 
					  INNER JOIN utilisateur 
						ON (sabonne.id_user = utilisateur.id)
					WHERE evenements.event_id='".$_GET['id']."'";
		$query_mail = mysql_query($sql_mail);
		$email='';
		while($res=mysql_fetch_array($query_mail)){
			$email .=$res['email'].',';
		}
		SendMail_sabonne($email,$rs1['nom_magazin'],$type,$rs1['titre'],$rs1['description'],$date,$rs1['adresse'],$rs1['ville']);
		SendMail_Ownner_evenements_approve($rs1['email'],$rs1['nom'],$rs1['prenom'],$rs1['titre']);
		
	}
	echo'<script>window.location="evenements.php?info=ACTIVATED";</script>';
}

if(isset($_GET['unapprouve'])){
	$id = $_GET['id'];
	$email = $_GET['email'];
	$sql_pro  = "UPDATE evenements SET approuve='2' WHERE event_id='".$id."'";
	$result_pro  = mysql_query($sql_pro ) or die (mysql_error());
	if($result_pro){
		$sql_select1 = "SELECT utilisateur.id, utilisateur.nom, utilisateur.prenom, utilisateur.email, evenements.event_id, evenements.titre
FROM evenements
INNER JOIN utilisateur ON ( evenements.id_user = utilisateur.id ) WHERE  evenements.event_id='".$_GET['id']."' AND utilisateur.email='".$email."'";
		$query_select1 = mysql_query($sql_select1);
		$rs1=mysql_fetch_array($query_select1);
		SendMail_Ownner_evenements_unapprove($rs1['email'],$rs1['nom'],$rs1['prenom'],$rs1['titre']);
	}
}

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset3 = "SELECT email, id FROM utilisateur ORDER BY email";
$Recordset3 = mysql_query($query_Recordset3, $magazinducoin) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset4 = "SELECT nom_magazin, id_magazin FROM magazins ORDER BY nom_magazin";
$Recordset4 = mysql_query($query_Recordset4, $magazinducoin) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset5 = "SELECT cat_id, cat_name FROM category WHERE type = 2 ORDER BY cat_name";
$Recordset5 = mysql_query($query_Recordset5, $magazinducoin) or die(mysql_error());
$row_Recordset5 = mysql_fetch_assoc($Recordset5);
$totalRows_Recordset5 = mysql_num_rows($Recordset5);

// Make an insert transaction instance
$ins_evenements = new tNG_multipleInsert($conn_magazinducoin);
$tNGs->addTransaction($ins_evenements);
// Register triggers
$ins_evenements->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_evenements->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_evenements->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$ins_evenements->setTable("evenements");
$ins_evenements->addColumn("titre", "STRING_TYPE", "POST", "titre");
$ins_evenements->addColumn("description", "STRING_TYPE", "POST", "description");
$ins_evenements->addColumn("id_user", "NUMERIC_TYPE", "POST", "id_user");
$ins_evenements->addColumn("active", "NUMERIC_TYPE", "POST", "active");
$ins_evenements->addColumn("date_debut", "DATE_TYPE", "POST", "date_debut");
$ins_evenements->addColumn("date_fin", "DATE_TYPE", "POST", "date_fin");
$ins_evenements->addColumn("id_magazin", "NUMERIC_TYPE", "POST", "id_magazin");
$ins_evenements->addColumn("category_id", "NUMERIC_TYPE", "POST", "category_id");
$ins_evenements->setPrimaryKey("event_id", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_evenements = new tNG_multipleUpdate($conn_magazinducoin);
$tNGs->addTransaction($upd_evenements);
// Register triggers
$upd_evenements->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_evenements->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_evenements->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$upd_evenements->setTable("evenements");
$upd_evenements->addColumn("titre", "STRING_TYPE", "POST", "titre");
$upd_evenements->addColumn("description", "STRING_TYPE", "POST", "description");
$upd_evenements->addColumn("id_user", "NUMERIC_TYPE", "POST", "id_user");
$upd_evenements->addColumn("active", "NUMERIC_TYPE", "POST", "active");
$upd_evenements->addColumn("date_debut", "DATE_TYPE", "POST", "date_debut");
$upd_evenements->addColumn("date_fin", "DATE_TYPE", "POST", "date_fin");
$upd_evenements->addColumn("id_magazin", "NUMERIC_TYPE", "POST", "id_magazin");
$upd_evenements->addColumn("category_id", "NUMERIC_TYPE", "POST", "category_id");
$upd_evenements->setPrimaryKey("event_id", "NUMERIC_TYPE", "GET", "event_id");

// Make an instance of the transaction object
$del_evenements = new tNG_multipleDelete($conn_magazinducoin);
$tNGs->addTransaction($del_evenements);
// Register triggers
$del_evenements->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_evenements->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_evenements->setTable("evenements");
$del_evenements->setPrimaryKey("event_id", "NUMERIC_TYPE", "GET", "event_id");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsevenements = $tNGs->getRecordset("evenements");
$row_rsevenements = mysql_fetch_assoc($rsevenements);
$totalRows_rsevenements = mysql_num_rows($rsevenements);
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
</head>
<body id="sp"
	<?php if(isset($_GET['event_id'])) { ?> onload="
    ajax('../ajax/magasins.php?default=<?php echo $row_rsevenements['id_magazin']; ?>&id_user=<?php echo $row_rsevenements['id_user']; ?>', '#id_magazin_1'); "
    <?php } ?>
>
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
              Evenements </h1>
            <div class="KT_tngform">
              <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
                <?php $cnt1 = 0; ?>
                <?php do { ?>
                  <?php $cnt1++; ?>
                  <?php 
// Show IF Conditional region1 
if (@$totalRows_rsevenements > 1) {
?>
                    <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
                    <?php } 
// endif Conditional region1
?>
                  <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                    <tr>
                      <td class="KT_th"><label for="titre_<?php echo $cnt1; ?>">Titre:</label></td>
                      <td><input type="text" name="titre_<?php echo $cnt1; ?>" id="titre_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsevenements['titre']); ?>" size="32" maxlength="255" />
                          <?php echo $tNGs->displayFieldHint("titre");?> <?php echo $tNGs->displayFieldError("evenements", "titre", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="description_<?php echo $cnt1; ?>">Description:</label></td>
                      <td><textarea name="description_<?php echo $cnt1; ?>" id="description_<?php echo $cnt1; ?>" cols="50" rows="5"><?php echo KT_escapeAttribute($row_rsevenements['description']); ?></textarea>
                          <?php echo $tNGs->displayFieldHint("description");?> <?php echo $tNGs->displayFieldError("evenements", "description", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="id_user_<?php echo $cnt1; ?>">Utilisateur :</label></td>
                      <td>
                        <select name="id_user_<?php echo $cnt1; ?>" id="id_user_<?php echo $cnt1; ?>"  onchange="ajax('../ajax/magasins.php?default=<?php echo $row_rsevenements['id_magazin']; ?>&coupon=1&id_user='+this.value, '#id_magazin_<?php echo $cnt1; ?>');">
                        
                        <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                        <?php do { ?>
                        <option value="<?php echo $row_Recordset3['id'];?>"<?php if (!(strcmp($row_Recordset3['id'], $row_rsevenements['id_user']))) {echo "SELECTED";} ?>>
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
                      
                      
                      
                      <?php /*?><select name="id_user_<?php echo $cnt1; ?>" id="id_user_<?php echo $cnt1; ?>"  onchange="ajax('../ajax/magasins.php?default=<?php echo $row_rsevenements['id_magasin']; ?>&coupon=1&id_user='+this.value, '#id_magasin_<?php echo $cnt1; ?>');">
                          <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                          <?php 
do {  
?>
                          <option value="<?php echo $row_Recordset3['id']?>"<?php if (!(strcmp($row_Recordset3['id'], $row_rsevenements['id_user']))) {echo "SELECTED";} ?>>
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
                        </select><?php */?>
                          <?php echo $tNGs->displayFieldError("evenements", "id_user", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="id_magazin_<?php echo $cnt1; ?>">Magasin:</label></td>
                      <td>
                      <select name="id_magazin_<?php echo $cnt1; ?>" id="id_magazin_<?php echo $cnt1; ?>">
                            <option value=""><?php echo NXT_getResource("Select one..."); ?></option>        
                        </select>
                        
                      <?php /*?><select name="id_magazin_<?php echo $cnt1; ?>" id="id_magazin_<?php echo $cnt1; ?>">
                          <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                          <?php do{?>
                          <option value="<?php echo $row_Recordset4['id_magazin']?>"<?php if (!(strcmp($row_Recordset4['id_magazin'], $row_rsevenements['id_magazin']))) {echo "SELECTED";} ?>><?php echo $row_Recordset4['nom_magazin']?></option>
                          <?php
							} while ($row_Recordset4 = mysql_fetch_assoc($Recordset4));
							  $rows = mysql_num_rows($Recordset4);
							  if($rows > 0) {
								  mysql_data_seek($Recordset4, 0);
								  $row_Recordset4 = mysql_fetch_assoc($Recordset4);
							  }
							?>
                        </select><?php */?>
						<?php echo $tNGs->displayFieldError("evenements", "id_magazin", $cnt1); ?> </td>
                    </tr>
                    
                    
                    
                    
                    
                      <tr>
                      <td class="KT_th"><label for="id_user_<?php echo $cnt1; ?>">Catégorie :</label></td>
                      <td><select name="category_id_<?php echo $cnt1; ?>" id="category_id_<?php echo $cnt1; ?>">
                          <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                          <?php 
do {  
?>
                          <option value="<?php echo $row_Recordset5['cat_id']?>"<?php if (!(strcmp($row_Recordset5['cat_id'], $row_rsevenements['category_id']))) {echo "SELECTED";} ?>><?php echo ($row_Recordset5['cat_name']); ?></option>
                          <?php
} while ($row_Recordset5 = mysql_fetch_assoc($Recordset5));
  $rows = mysql_num_rows($Recordset5);
  if($rows > 0) {
      mysql_data_seek($Recordset5, 0);
	  $row_Recordset5 = mysql_fetch_assoc($Recordset5);
  }
?>
                        </select>
                          <?php echo $tNGs->displayFieldError("evenements", "category_id", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="active_<?php echo $cnt1; ?>_1">Active:</label></td>
                      <td><div>
                          <input <?php if (!(strcmp(KT_escapeAttribute($row_rsevenements['active']),"1"))) {echo "CHECKED";} ?> type="radio" name="active_<?php echo $cnt1; ?>" id="active_<?php echo $cnt1; ?>_1" value="1" />
                          <label for="active_<?php echo $cnt1; ?>_1">Oui</label>
&nbsp;&nbsp;&nbsp;&nbsp;
                            <input <?php if (!(strcmp(KT_escapeAttribute($row_rsevenements['active']),"0"))) {echo "CHECKED";} ?> type="radio" name="active_<?php echo $cnt1; ?>" id="active_<?php echo $cnt1; ?>_2" value="0" />
                            <label for="active_<?php echo $cnt1; ?>_2">Non</label>
                          </div>
                        <?php echo $tNGs->displayFieldError("evenements", "active", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="date_debut_<?php echo $cnt1; ?>">Date début:</label></td>
                      <td><input type="text" name="date_debut_<?php echo $cnt1; ?>" id="date_debut_<?php echo $cnt1; ?>" value="<?php echo KT_formatDate($row_rsevenements['date_debut']); ?>" size="10" maxlength="22" />
                          <?php echo $tNGs->displayFieldHint("date_debut");?> <?php echo $tNGs->displayFieldError("evenements", "date_debut", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="date_fin_<?php echo $cnt1; ?>">Date fin:</label></td>
                      <td><input type="text" name="date_fin_<?php echo $cnt1; ?>" id="date_fin_<?php echo $cnt1; ?>" value="<?php echo KT_formatDate($row_rsevenements['date_fin']); ?>" size="10" maxlength="22" />
                          <?php echo $tNGs->displayFieldHint("date_fin");?> <?php echo $tNGs->displayFieldError("evenements", "date_fin", $cnt1); ?> </td>
                    </tr>
                    
                  </table>
                  <input type="hidden" name="kt_pk_evenements_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsevenements['kt_pk_evenements']); ?>" />
                  <?php } while ($row_rsevenements = mysql_fetch_assoc($rsevenements)); ?>
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