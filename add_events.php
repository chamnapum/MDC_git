<?php require_once('Connections/magazinducoin.php'); ?>
<?php
//MX Widgets3 include
require_once('includes/wdg/WDG.php');

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
$restrict->addLevel("1");
$restrict->Execute();
//End Restrict Access To Page

//Test de limite d'ajout gratuit

$max_event_free=1;
$rkt = "SELECT count(*) as nb FROM evenements WHERE user_id = ".$_SESSION['kt_login_id'];
$query=mysql_query($rkt);
$nbevent=mysql_fetch_array($query);

if($nbevent['nb'] >= $max_event_free) {
    $rkt = "SELECT credit from utilisateur where id = ".$_SESSION['kt_login_id'];
    $query=mysql_query($rkt);
    $creditrow=mysql_fetch_array($query);
    if($creditrow['credit'] >= 3){
        header('Location: payer_par_credit.php?ids=121&type=événement&redirect=calandrier.php');
        //Updater le seuil.
        exit();
    }else{            
        header('Location: payer_abonement.php?type=événement&max_free=1');
        //Updater le seuil.
        exit();
    }
}
// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("titre", true, "text", "", "", "", "");
$formValidation->addField("date_debut", true, "date", "", "", "", "");
$formValidation->addField("category_id", true, "numeric", "", "", "", "");
$formValidation->addField("id_magazin", true, "text", "", "", "", "");
$formValidation->addField("active", true, "numeric", "", "", "", "");
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
$query_categories = "SELECT cat_id, cat_name FROM category WHERE parent_id = 0 ORDER BY cat_name ASC";
$categories = mysql_query($query_categories, $magazinducoin) or die(mysql_error());
$row_categories = mysql_fetch_assoc($categories);
$totalRows_categories = mysql_num_rows($categories);

$colname_magasin = "-1";
if (isset($_SESSION['kt_login_id'])) {
  $colname_magasin = $_SESSION['kt_login_id'];
}
mysql_select_db($database_magazinducoin, $magazinducoin);
$query_magasin = sprintf("SELECT id_magazin, nom_magazin FROM magazins WHERE id_user = %s ORDER BY nom_magazin ASC", GetSQLValueString($colname_magasin, "int"));
$magasin = mysql_query($query_magasin, $magazinducoin) or die(mysql_error());
$row_magasin = mysql_fetch_assoc($magasin);
$totalRows_magasin = mysql_num_rows($magasin);

// Make an insert transaction instance
$ins_evenements = new tNG_multipleInsert($conn_magazinducoin);
$tNGs->addTransaction($ins_evenements);
// Register triggers
$ins_evenements->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_evenements->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_evenements->registerTrigger("END", "Trigger_Default_Redirect", 99, "calandrier.php");
$ins_evenements->registerTrigger("AFTER", "Trigger_send_newsletter", 98);

function Trigger_send_newsletter(&$tNG){
	global $magazinducoin;
	
	$query_liste_magasins = sprintf("SELECT	nom_magazin FROM magazins WHERE id_magazin = %s ",
					 				GetSQLValueString($_POST['id_magazin_1'], "int"));
	$liste_magasins = mysql_query($query_liste_magasins, $magazinducoin) or die(mysql_error());
	$magasin_en_cour = mysql_fetch_assoc($liste_magasins);
	
	$query_liste_magasins = sprintf("SELECT	nom, email FROM newsletter WHERE id_magasin = %s ",
					 				GetSQLValueString($_POST['id_magazin_1'], "int"));
	$liste_magasins = mysql_query($query_liste_magasins, $magazinducoin) or die(mysql_error());
	while($liste = mysql_fetch_assoc($liste_magasins)) {
	  $nom 		= $liste['nom'];
	  $email 	= $liste['email'];
	  
	  $emailObj = new tNG_Email($tNG);
	  $emailObj->setFrom("{KT_defaultSender}");
	  $emailObj->setTo($email);
	  $emailObj->setCC("");
	  $emailObj->setBCC("");
	  $emailObj->setSubject("Newsletter du magasin: ".$magasin_en_cour['nom_magazin']);
	  //FromFile method
	  $content = "<h3>Newsletter du ".$magasin_en_cour['nom_magazin'].". </h3>
	  <p>Bonjour $nom,</p> 
	  <p>Le magasin ".$magasin_en_cour['nom_magazin']." a un évènement du {date_debut} à {date_fin}</p>
	  <p>Titre de l'évènement est: {titre}</p>
	  <p></p>
	  <p><strong>L'équipe du MagasinDuCoin.fr</strong></p>";
	  $emailObj->setContent($content);
	  $emailObj->setEncoding("ISO-8859-1");
	  $emailObj->setFormat("HTML/Text");
	  $emailObj->setImportance("Normal");
	  return $emailObj->Execute();
	}	
}
// Add columns
$ins_evenements->setTable("evenements");
$ins_evenements->addColumn("titre", "STRING_TYPE", "POST", "titre");
$ins_evenements->addColumn("description", "STRING_TYPE", "POST", "description");
$ins_evenements->addColumn("date_debut", "DATE_TYPE", "POST", "date_debut");
$ins_evenements->addColumn("date_fin", "DATE_TYPE", "POST", "date_fin");
$ins_evenements->addColumn("category_id", "NUMERIC_TYPE", "POST", "category_id");
$ins_evenements->addColumn("id_magazin", "STRING_TYPE", "POST", "id_magazin");
$ins_evenements->addColumn("user_id", "NUMERIC_TYPE", "POST", "user_id", "{SESSION.kt_login_id}");
$ins_evenements->addColumn("active", "NUMERIC_TYPE", "POST", "active", "1");
$ins_evenements->setPrimaryKey("event_id", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_evenements = new tNG_multipleUpdate($conn_magazinducoin);
$tNGs->addTransaction($upd_evenements);
// Register triggers
$upd_evenements->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_evenements->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_evenements->registerTrigger("END", "Trigger_Default_Redirect", 99, "calandrier.php");
// Add columns
$upd_evenements->setTable("evenements");
$upd_evenements->addColumn("titre", "STRING_TYPE", "POST", "titre");
$upd_evenements->addColumn("description", "STRING_TYPE", "POST", "description");
$upd_evenements->addColumn("date_debut", "DATE_TYPE", "POST", "date_debut");
$upd_evenements->addColumn("date_fin", "DATE_TYPE", "POST", "date_fin");
$upd_evenements->addColumn("category_id", "NUMERIC_TYPE", "POST", "category_id");
$upd_evenements->addColumn("id_magazin", "STRING_TYPE", "POST", "id_magazin");
$upd_evenements->addColumn("user_id", "NUMERIC_TYPE", "POST", "user_id");
$upd_evenements->addColumn("active", "NUMERIC_TYPE", "POST", "active");
$upd_evenements->setPrimaryKey("event_id", "NUMERIC_TYPE", "GET", "event_id");

// Make an instance of the transaction object
$del_evenements = new tNG_multipleDelete($conn_magazinducoin);
$tNGs->addTransaction($del_evenements);
// Register triggers
$del_evenements->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_evenements->registerTrigger("END", "Trigger_Default_Redirect", 99, "includes/nxt/back.php");
// Add columns
$del_evenements->setTable("evenements");
$del_evenements->setPrimaryKey("event_id", "NUMERIC_TYPE", "GET", "event_id");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsevenements = $tNGs->getRecordset("evenements");
$row_rsevenements = mysql_fetch_assoc($rsevenements);
$totalRows_rsevenements = mysql_num_rows($rsevenements);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://ns.adobe.com/addt">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 
	<title>Magasin du coin | Espace membre </title>
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
  show_as_grid: false,
  merge_down_value: true
}
</script>
<script type="text/javascript" src="includes/common/js/sigslot_core.js"></script>
<script type="text/javascript" src="includes/wdg/classes/MXWidgets.js"></script>
<script type="text/javascript" src="includes/wdg/classes/MXWidgets.js.php"></script>
<script type="text/javascript" src="includes/wdg/classes/Calendar.js"></script>
<script type="text/javascript" src="includes/wdg/classes/SmartDate.js"></script>
<script type="text/javascript" src="includes/wdg/calendar/calendar_stripped.js"></script>
<script type="text/javascript" src="includes/wdg/calendar/calendar-setup_stripped.js"></script>
<script src="includes/resources/calendar.js"></script>
</head>
<body id="sp">
<?php include("modules/header.php"); ?>
<div class="content_wrapper_sbr">
	<div>
  		<div id="content">
        	<!--Start Menu-->
        	<div class="top reduit">
        	<?php include("modules/menu.php"); ?>
            <div  style="font-size: 14px; font-weight: bold; position: absolute; right: 15px; top: 51px;">
            
            
              <?php echo $xml->Votre_credit_publicite; ?>  <?php 
$query_Recordset1 = "SELECT credit FROM utilisateur WHERE id = ".$_SESSION['kt_login_id'];
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$row_credit = mysql_fetch_assoc($Recordset1);
echo $row_credit['credit'];  ?> &euro;</div>
            </div>
            <!--End Menu-->
        <div style="float:left; width:200px;">           
					<?php include("modules/membre_menu.php"); ?>
			</div>
  		
	<div style="float:left; width:780px;">
  		  <h3 style="margin-left:20px;"><?php echo $xml->Calandrier ?></h3>
          <?php
			echo $tNGs->getErrorMsg();
		  ?>
          <div class="KT_tng">
            <h4 style="margin-left:20px;">
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
              <?php echo $xml->Evenements ;?> </h4>
            <div class="KT_tngform"  style="margin-left:20px;">
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
                      <td class="KT_th"><label for="titre_<?php echo $cnt1; ?>"><?php echo $xml->Titre ?>:</label></td>
                      <td><input type="text" name="titre_<?php echo $cnt1; ?>" id="titre_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsevenements['titre']); ?>" size="32" maxlength="255" />
                          <?php echo $tNGs->displayFieldHint("titre");?> <?php echo $tNGs->displayFieldError("evenements", "titre", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="description_<?php echo $cnt1; ?>"><?php echo $xml->Description ?>:</label></td>
                      <td><textarea name="description_<?php echo $cnt1; ?>" id="description_<?php echo $cnt1; ?>" cols="50" rows="5"><?php echo KT_escapeAttribute($row_rsevenements['description']); ?></textarea>
                          <?php echo $tNGs->displayFieldHint("description");?> <?php echo $tNGs->displayFieldError("evenements", "description", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="date_debut_<?php echo $cnt1; ?>"><?php echo $xml->Date_debut; ?>:</label></td>
                      <td><input name="date_debut_<?php echo $cnt1; ?>" id="date_debut_<?php echo $cnt1; ?>" value="<?php echo KT_formatDate($row_rsevenements['date_debut']); ?>" size="10" maxlength="22" wdg:mondayfirst="true" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="yes" />
                          <?php echo $tNGs->displayFieldHint("date_debut");?> <?php echo $tNGs->displayFieldError("evenements", "date_debut", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="date_fin_<?php echo $cnt1; ?>"><?php echo $xml->Date_fin ;?></label></td>
                      <td><input name="date_fin_<?php echo $cnt1; ?>" id="date_fin_<?php echo $cnt1; ?>" value="<?php echo KT_formatDate($row_rsevenements['date_fin']); ?>" size="10" maxlength="22" wdg:mondayfirst="true" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="yes" />
                          <?php echo $tNGs->displayFieldHint("date_fin");?> <?php echo $tNGs->displayFieldError("evenements", "date_fin", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="category_id_<?php echo $cnt1; ?>"><?php echo $xml->Categorie ?></label></td>
                      <td><select name="category_id_<?php echo $cnt1; ?>" id="category_id_<?php echo $cnt1; ?>">
                          <option value=""><?php echo $xml->selectionner ;?></option>
                          <?php 
do {  
?>
                          <option value="<?php echo $row_categories['cat_id']?>"<?php if (!(strcmp($row_categories['cat_id'], $row_rsevenements['category_id']))) {echo "SELECTED";} ?>><?php echo ($row_categories['cat_name']); ?></option>
                          <?php
} while ($row_categories = mysql_fetch_assoc($categories));
  $rows = mysql_num_rows($categories);
  if($rows > 0) {
      mysql_data_seek($categories, 0);
	  $row_categories = mysql_fetch_assoc($categories);
  }
?>
                        </select>
                          <?php echo $tNGs->displayFieldError("evenements", "category_id", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="id_magazin_<?php echo $cnt1; ?>"><?php echo $xml->Lieu ?></label></td>
                      <td><select name="id_magazin_<?php echo $cnt1; ?>" id="id_magazin_<?php echo $cnt1; ?>">
                          <option value=""><?php echo $xml->selectionner ;?></option>
                          <?php 
do {  
?>
                          <option value="<?php echo $row_magasin['id_magazin']?>"<?php if (!(strcmp($row_magasin['id_magazin'], $row_rsevenements['id_magazin']))) {echo "SELECTED";} ?>><?php echo $row_magasin['nom_magazin']?></option>
                          <?php
} while ($row_magasin = mysql_fetch_assoc($magasin));
  $rows = mysql_num_rows($magasin);
  if($rows > 0) {
      mysql_data_seek($magasin, 0);
	  $row_magasin = mysql_fetch_assoc($magasin);
  }
?>
                        </select>
                          <?php echo $tNGs->displayFieldError("evenements", "id_magazin", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="active_<?php echo $cnt1; ?>_1"><?php echo $xml->Active ?>:</label></td>
                      <td><div>
                          <input <?php if (!(strcmp(KT_escapeAttribute($row_rsevenements['active']),"1"))) {echo "CHECKED";} ?> type="radio" name="active_<?php echo $cnt1; ?>" id="active_<?php echo $cnt1; ?>_1" value="1" />
                          <label for="active_<?php echo $cnt1; ?>_1">Oui</label>
                        </div>
                          <div>
                            <input <?php if (!(strcmp(KT_escapeAttribute($row_rsevenements['active']),"0"))) {echo "CHECKED";} ?> type="radio" name="active_<?php echo $cnt1; ?>" id="active_<?php echo $cnt1; ?>_2" value="0" />
                            <label for="active_<?php echo $cnt1; ?>_2"><?php echo $xml-> Non ?></label>
                          </div>
                          <?php echo $tNGs->displayFieldError("evenements", "active", $cnt1); ?> </td>
                    </tr>
                  </table>
                  <input type="hidden" name="kt_pk_evenements_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsevenements['kt_pk_evenements']); ?>" />
                  <input type="hidden" name="user_id_<?php echo $cnt1; ?>" id="user_id_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsevenements['user_id']); ?>" />
                  <?php } while ($row_rsevenements = mysql_fetch_assoc($rsevenements)); ?>
                <div class="KT_bottombuttons">
                  <div>
                    <?php 
      // Show IF Conditional region1
      if (@$_GET['event_id'] == "") {
      ?>
                      <input type="submit" name="KT_Insert1" id="KT_Insert1" value="<?php echo $xml->valider   ?>" />
                      <?php 
      // else Conditional region1
      } else { ?>
                      <input type="submit" name="KT_Update1" value="<?php echo $xml->editer ?>" />
                      <input type="submit" name="KT_Delete1" value="<?php echo $xml->supprimer   ?>" onclick="return confirm('<?php echo $xml->vous_etes_sur ; ?>');" />
                      <?php }
      // endif Conditional region1
      ?>
                    <input type="button" name="KT_Cancel1" value="<?php echo $xml->annuler   ?>" onclick="return UNI_navigateCancel(event, 'includes/nxt/back.php')" />
                  </div>
                </div>
              </form>
            </div>
            <br class="clearfixplain" />
          </div>
          <p>&nbsp;</p>
  		</div>
	</div>
  <!-- Sidebar Area -->
</div>

    </div>
  </div>
</form>

<!-- End Content Wrapper -->
<!-- Start Footer Sidebar -->
   <div id="footer">
    	<?php include("modules/region_barre_recherche.php"); ?>
        <div class="liens">
      	 <?php include("modules/footer.php"); ?>
        </div>
    </div>
</body>
</html>
<?php
mysql_free_result($categories);

mysql_free_result($magasin);
?>