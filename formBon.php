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

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("titre", true, "text", "", "", "", "");
$formValidation->addField("reduction", true, "text", "", "", "", "");
$formValidation->addField("date_debut", true, "date", "", "", "", "");
$formValidation->addField("date_fin", true, "date", "", "", "", "");
$formValidation->addField("categories", true, "text", "", "", "", "");
$formValidation->addField("sous_categorie", true, "text", "", "", "", "");
$formValidation->addField("id_magasin", true, "text", "", "", "", "");
$formValidation->addField("code_bare", true, "text", "zip_generic", "12", "13", "Le code bare doit contenir 12 ou 13 caractéres");
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

$colname_liste_magasins = "-1";
if (isset($_SESSION['kt_login_id'])) {
  $colname_liste_magasins = $_SESSION['kt_login_id'];
}
mysql_select_db($database_magazinducoin, $magazinducoin);
$query_liste_magasins = sprintf("SELECT id_magazin, nom_magazin FROM magazins WHERE id_user = %s ORDER BY nom_magazin ASC", GetSQLValueString($colname_liste_magasins, "int"));
$liste_magasins = mysql_query($query_liste_magasins, $magazinducoin) or die(mysql_error());
$row_liste_magasins = mysql_fetch_assoc($liste_magasins);
$totalRows_liste_magasins = mysql_num_rows($liste_magasins);

// Make an insert transaction instance
$ins_bons = new tNG_multipleInsert($conn_magazinducoin);
$tNGs->addTransaction($ins_bons);
// Register triggers
$ins_bons->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_bons->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_bons->registerTrigger("END", "Trigger_Default_Redirect", 99, "includes/nxt/back.php");
$ins_bons->registerTrigger("AFTER", "Trigger_send_newsletter", 98);

function Trigger_send_newsletter(&$tNG){
	global $magazinducoin;
	
	$query_liste_magasins = sprintf("SELECT	nom_magazin FROM magazins WHERE id_magazin = %s ",
					 				GetSQLValueString($_POST['id_magasin_1'], "int"));
	$liste_magasins = mysql_query($query_liste_magasins, $magazinducoin) or die(mysql_error());
	$magasin_en_cour = mysql_fetch_assoc($liste_magasins);
	
	$query_liste_magasins = sprintf("SELECT	nom, email FROM newsletter WHERE id_magasin = %s ",
					 				GetSQLValueString($_POST['id_magasin_1'], "int"));
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
	  <p>Le magasin ".$magasin_en_cour['nom_magazin']." a un bon du reduction de {reduction} &euro; du {date_debut} à {date_fin}</p>
	   <p>Titre de bon est: {titre}</p>
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
$ins_bons->setTable("bons");
$ins_bons->addColumn("titre", "STRING_TYPE", "POST", "titre");
$ins_bons->addColumn("reduction", "STRING_TYPE", "POST", "reduction");
$ins_bons->addColumn("date_debut", "DATE_TYPE", "POST", "date_debut");
$ins_bons->addColumn("date_fin", "DATE_TYPE", "POST", "date_fin");
$ins_bons->addColumn("categories", "STRING_TYPE", "POST", "categories");
$ins_bons->addColumn("sous_categorie", "STRING_TYPE", "POST", "sous_categorie");
$ins_bons->addColumn("id_magasin", "STRING_TYPE", "POST", "id_magasin");
$ins_bons->addColumn("code_bare", "STRING_TYPE", "POST", "code_bare");
$ins_bons->addColumn("min_achat", "STRING_TYPE", "POST", "min_achat", "0");
$ins_bons->addColumn("id_user", "NUMERIC_TYPE", "POST", "id_user", "{SESSION.kt_login_id}");
$ins_bons->setPrimaryKey("id_bon", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_bons = new tNG_multipleUpdate($conn_magazinducoin);
$tNGs->addTransaction($upd_bons);
// Register triggers
$upd_bons->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_bons->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_bons->registerTrigger("END", "Trigger_Default_Redirect", 99, "includes/nxt/back.php");
// Add columns
$upd_bons->setTable("bons");
$upd_bons->addColumn("titre", "STRING_TYPE", "POST", "titre");
$upd_bons->addColumn("reduction", "STRING_TYPE", "POST", "reduction");
$upd_bons->addColumn("date_debut", "DATE_TYPE", "POST", "date_debut");
$upd_bons->addColumn("date_fin", "DATE_TYPE", "POST", "date_fin");
$upd_bons->addColumn("categories", "STRING_TYPE", "POST", "categories");
$upd_bons->addColumn("sous_categorie", "STRING_TYPE", "POST", "sous_categorie");
$upd_bons->addColumn("id_magasin", "STRING_TYPE", "POST", "id_magasin");
$upd_bons->addColumn("code_bare", "STRING_TYPE", "POST", "code_bare");
$upd_bons->addColumn("min_achat", "STRING_TYPE", "POST", "min_achat");
$upd_bons->addColumn("id_user", "NUMERIC_TYPE", "POST", "id_user");
$upd_bons->setPrimaryKey("id_bon", "NUMERIC_TYPE", "GET", "id_bon");

// Make an instance of the transaction object
$del_bons = new tNG_multipleDelete($conn_magazinducoin);
$tNGs->addTransaction($del_bons);
// Register triggers
$del_bons->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_bons->registerTrigger("END", "Trigger_Default_Redirect", 99, "includes/nxt/back.php");
// Add columns
$del_bons->setTable("bons");
$del_bons->setPrimaryKey("id_bon", "NUMERIC_TYPE", "GET", "id_bon");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsbons = $tNGs->getRecordset("bons");
$row_rsbons = mysql_fetch_assoc($rsbons);
$totalRows_rsbons = mysql_num_rows($rsbons);
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
  merge_down_value: false
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
<body id="sp" 
<?php if(isset($_GET['id_bon'])) { ?>
onload="ajax('ajax/sous_categorie.php?default=<?php echo $row_rsbons['sous_categorie']; ?>&id_parent=<?php echo $row_rsbons['categories']; ?>','#sous_categorie_1');"
<?php } ?>>
<?php include("modules/header.php"); ?>

  		<div id="content" class="photographes">
        	<div class="top reduit">
                    <?php include("modules/menu.php"); ?>
                    <div  style="font-size: 14px; font-weight: bold; position: absolute; right: 15px; top: 51px;">Votre Crédit Publicité: <?php 
$query_Recordset1 = "SELECT credit FROM utilisateur WHERE id = ".$_SESSION['kt_login_id'];
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$row_credit = mysql_fetch_assoc($Recordset1);
echo $row_credit['credit'];  ?> &euro;</div>
            </div>
  		   <div style="float:left; width:200px;">           
			<?php include("modules/membre_menu.php"); ?>
           </div>
		  <div style="float:left; width:780px;">
          <h3 style="margin-left:20px;">Coupon: <?php echo $row_rsbons['titre'] ? $row_rsbons['titre'] : ""; ?></h3>
          
				<?php echo $tNGs->getErrorMsg();?>
			
          <div class="KT_tng">
            <div class="KT_tngform">
              <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
                <?php $cnt1 = 0; ?>
                <?php do { ?>
                  <?php $cnt1++; ?>
                <?php 
// Show IF Conditional region1 
if (@$totalRows_rsbons > 1) {
?>
                  <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
      <?php } 
// endif Conditional region1
?>
<div style="position:relative; padding-left:20px;" class="form_insc2">
    <div class="champ"><label for="titre_<?php echo $cnt1; ?>">Titre:</label>
    <input type="text" name="titre_<?php echo $cnt1; ?>" id="titre_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsbons['titre']); ?>" size="32" />
                          <?php echo $tNGs->displayFieldHint("titre");?> <?php echo $tNGs->displayFieldError("bons", "titre", $cnt1); ?> </div>
                          
    <div class="champ"><label for="reduction_<?php echo $cnt1; ?>">Valeur du bon (en €): </label>
    <input name="reduction_<?php echo $cnt1; ?>" id="reduction_<?php echo $cnt1; ?>" type="text" value="<?php echo KT_escapeAttribute($row_rsbons['reduction']); ?>" size="32" />
                          <?php echo $tNGs->displayFieldError("bons", "reduction", $cnt1); ?> </div>
                          
    <div class="champ"><label for="date_debut_<?php echo $cnt1; ?>">Date de début:</label>
    <input name="date_debut_<?php echo $cnt1; ?>" id="date_debut_<?php echo $cnt1; ?>" value="<?php echo KT_formatDate($row_rsbons['date_debut']); ?>" size="10" maxlength="22" wdg:mondayfirst="true" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="yes" />
                          <?php echo $tNGs->displayFieldHint("date_debut");?> <?php echo $tNGs->displayFieldError("bons", "date_debut", $cnt1); ?> </div>
                          
    <div class="champ"><label for="date_fin_<?php echo $cnt1; ?>">Date de fin:</label>
    <input name="date_fin_<?php echo $cnt1; ?>" id="date_fin_<?php echo $cnt1; ?>" value="<?php echo KT_formatDate($row_rsbons['date_fin']); ?>" size="10" maxlength="22" wdg:mondayfirst="true" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="yes" />
                          <?php echo $tNGs->displayFieldHint("date_fin");?> <?php echo $tNGs->displayFieldError("bons", "date_fin", $cnt1); ?> </div>
                          
    <div class="champ"><label for="id_magasin_<?php echo $cnt1; ?>">Magasin:</label>
    <select name="id_magasin_<?php echo $cnt1; ?>" id="id_magasin_<?php echo $cnt1; ?>">
                        <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                        <option value="-1" <?php if ($row_rsbons['id_magasin'] == -1) {echo "SELECTED";} ?>>Tous les magasins</option>
                          <?php 
do {  
?>
                        <option value="<?php echo $row_liste_magasins['id_magazin']?>"<?php if (!(strcmp($row_liste_magasins['id_magazin'], $row_rsbons['id_magasin']))) {echo "SELECTED";} ?>><?php echo $row_liste_magasins['nom_magazin']?></option>
                          <?php
} while ($row_liste_magasins = mysql_fetch_assoc($liste_magasins));
  $rows = mysql_num_rows($liste_magasins);
  if($rows > 0) {
      mysql_data_seek($liste_magasins, 0);
	  $row_liste_magasins = mysql_fetch_assoc($liste_magasins);
  }
?>
                                                  </select>
                              <?php echo $tNGs->displayFieldError("bons", "id_magasin", $cnt1); ?> </div>
	
    <div class="champ"><label for="code_bare_<?php echo $cnt1; ?>">Code barre:</label>
    <input type="text" name="code_bare_<?php echo $cnt1; ?>" id="code_bare_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsbons['code_bare']); ?>" size="32" />
                          <?php //echo $tNGs->displayFieldHint("code_bare");?>
                          <?php echo $tNGs->displayFieldError("bons", "code_bare", $cnt1); ?> </div>
</div>

<div class="clear"></div>

<div style="position:relative; padding-left:20px;" class="form_insc3">
	<div class="champ"><label for="categories_<?php echo $cnt1; ?>">Catégorie:</label>
    <select name="categories_<?php echo $cnt1; ?>" id="categories_<?php echo $cnt1; ?>"  onchange="ajax('ajax/sous_categorie.php?default=<?php echo $row_rsbons['sous_categorie']; ?>&id_parent='+this.value,'#sous_categorie_<?php echo $cnt1; ?>');">
                          <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                          <option value="-1" <?php if($row_rsbons['categories'] == -1) echo "selected"; ?>>Tout le magasin</option>
                          <?php 
do {  
?>
                          <option value="<?php echo $row_categories['cat_id']?>"<?php if (!(strcmp($row_categories['cat_id'], $row_rsbons['categories']))) {echo "SELECTED";} ?>><?php echo ($row_categories['cat_name']); ?></option>
                          <?php
} while ($row_categories = mysql_fetch_assoc($categories));
  $rows = mysql_num_rows($categories);
  if($rows > 0) {
      mysql_data_seek($categories, 0);
	  $row_categories = mysql_fetch_assoc($categories);
  }
?>
                        </select>
                          <?php echo $tNGs->displayFieldError("bons", "categories", $cnt1); ?> </div>
                          
    <div class="clear"></div>
   <div class="champ"> <label for="sous_categorie_<?php echo $cnt1; ?>">Sous catégorie:</label>
    <select name="sous_categorie_<?php echo $cnt1; ?>" id="sous_categorie_<?php echo $cnt1; ?>">
                          <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                        </select>
                          <?php echo $tNGs->displayFieldError("bons", "sous_categorie", $cnt1); ?></div>
     <div class="clear"></div>               
    <div class="champ" id="min_achat_tr">
    <label for="min_achat_<?php echo $cnt1; ?>">Minimum d'achat pour appliquer le bon (en €):</label>
    <input type="text" name="min_achat_<?php echo $cnt1; ?>" id="min_achat_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsbons['min_achat']); ?>" size="32" />
                          <?php echo $tNGs->displayFieldHint("min_achat");?> <?php echo $tNGs->displayFieldError("bons", "min_achat", $cnt1); ?> </div>
                          
</div>
<div class="clear"></div>

                
                  <input type="hidden" name="kt_pk_bons_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsbons['kt_pk_bons']); ?>" />
                  <input type="hidden" name="id_user_<?php echo $cnt1; ?>" id="id_user_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsbons['id_user']); ?>" />
                  <?php } while ($row_rsbons = mysql_fetch_assoc($rsbons)); ?>

<div style="padding-left:20px; position:relative;" class="form_insc2"> 
                    <?php 
      // Show IF Conditional region1
      if (@$_GET['id_bon'] == "") {
      ?>
                      <input type="submit" name="KT_Insert1" class="image-submit" id="KT_Insert1" value="<?php echo NXT_getResource("Insert_FB"); ?>" />
                      <?php 
      // else Conditional region1
      } else { ?>
                      <input type="submit" class="image-submit" name="KT_Update1" value="<?php echo NXT_getResource("Update_FB"); ?>" />
                      <input type="submit" class="image-submit" style="margin-left:130px;" name="KT_Delete1" value="<?php echo NXT_getResource("Delete_FB"); ?>" onclick="return confirm('<?php echo NXT_getResource("Are you sure?"); ?>');" />
                      <?php }
      // endif Conditional region1
      ?>
                    <input type="button" class="image-submit" name="KT_Cancel1" <?php if (@$_GET['id_bon'] == "") echo 'style="margin-left:85px;"'; else echo 'style="margin-left:242px;"'; ?> value="<?php echo NXT_getResource("Cancel_FB"); ?>" onclick="return UNI_navigateCancel(event, 'includes/nxt/back.php')" />
</div>
              </form>
            </div>
            <br class="clearfixplain" />
          </div>
</div>
	</div>
  <!-- Sidebar Area -->
</div>


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

mysql_free_result($liste_magasins);
?>