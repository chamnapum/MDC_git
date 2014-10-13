<?php require_once('../Connections/magazinducoin.php'); ?>
<?php
//MX Widgets3 include
require_once('../includes/wdg/WDG.php');

// Load the common classes
require_once('../includes/common/KT_common.php');

// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');

// Load the KT_back class
require_once('../includes/nxt/KT_back.php');

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("../");

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("");

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
$formValidation->addField("reduction", true, "text", "", "", "", "");
$formValidation->addField("date_debut", true, "date", "", "", "", "");
$formValidation->addField("date_fin", true, "date", "", "", "", "");
$formValidation->addField("categories", true, "text", "", "", "", "");
$formValidation->addField("sous_categorie", true, "text", "", "", "", "");
$formValidation->addField("id_user", true, "text", "", "", "", "");
$formValidation->addField("id_magasin", true, "text", "", "", "", "");
$formValidation->addField("description", true, "text", "", "1", "800", "800 caractéres");
//$formValidation->addField("code_bare", true, "text", "zip_generic", "12", "13", "Le code bare doit contenir 12 ou 13 caractéres");
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
if(isset($_GET['active'])){
	$id = $_GET['id'];
	$email = $_GET['email'];
	$sql_pro  = "UPDATE coupons SET approuve='1' WHERE id_coupon='".$id."'";
	$result_pro  = mysql_query($sql_pro ) or die (mysql_error());
	if($result_pro){
		$sql_select1 = "SELECT
							utilisateur.id
							, utilisateur.nom
							, utilisateur.prenom
							, utilisateur.email
							, coupons.id_coupon
							, coupons.titre
							, coupons.description
							, magazins.nom_magazin
							, magazins.adresse
							,(SELECT nom_region FROM region WHERE id_region = magazins.region) AS region
							,(SELECT nom FROM maps_ville WHERE id_ville = magazins.ville) AS ville
						FROM
							magazins
							INNER JOIN coupons 
								ON (magazins.id_magazin = coupons.id_magasin)
							INNER JOIN utilisateur 
								ON (coupons.id_user = utilisateur.id)
						 WHERE coupons.id_coupon='".$_GET['id']."' AND utilisateur.email='".$email."'";
		$query_select1 = mysql_query($sql_select1);
		$rs1=mysql_fetch_array($query_select1);
		
		
		$type = 'Coupons';
		$date = date("Y-m-d");
		
		$sql_mail ="SELECT
						coupons.id_coupon
						, utilisateur.email
					FROM
						magazins
						INNER JOIN coupons 
							ON (magazins.id_magazin = coupons.id_magasin)
						INNER JOIN sabonne 
							ON (coupons.id_magasin = sabonne.id_magasin)
						INNER JOIN utilisateur 
							ON (sabonne.id_user = utilisateur.id)
					WHERE coupons.id_coupon='".$_GET['id']."'";
		$query_mail = mysql_query($sql_mail);
		$email='';
		while($res=mysql_fetch_array($query_mail)){
			$email .=$res['email'].',';
		}
		
		SendMail_sabonne($email,$rs1['nom_magazin'],$type,$rs1['titre'],$rs1['description'],$date,$rs1['adresse'],$rs1['ville']);
		SendMail_Ownner_Coupon_approve($rs1['email'],$rs1['nom'],$rs1['prenom'],$rs1['titre']);
	}
	echo'<script>window.location="coupons.php?info=ACTIVATED";</script>';
}

if(isset($_GET['unactive'])){
	$id = $_GET['id'];
	$email = $_GET['email'];
	$sql_pro  = "UPDATE coupons SET approuve='2' WHERE id_coupon='".$id."'";
	$result_pro  = mysql_query($sql_pro ) or die (mysql_error());
	if($result_pro){
		$sql_select1 = "SELECT
							utilisateur.id
							, utilisateur.nom
							, utilisateur.prenom
							, utilisateur.email
							, coupons.id_coupon
							, coupons.titre
						FROM
							coupons
							INNER JOIN utilisateur 
								ON (coupons.id_user = utilisateur.id)
						 WHERE coupons.id_coupon='".$_GET['id']."' AND utilisateur.email='".$email."'";
		$query_select1 = mysql_query($sql_select1);
		$rs1=mysql_fetch_array($query_select1);
		SendMail_Ownner_Coupon_unapprove($rs1['email'],$rs1['nom'],$rs1['prenom'],$rs1['titre']);
	}
}
mysql_select_db($database_magazinducoin, $magazinducoin);
$query_categories = "SELECT cat_id, cat_name FROM category WHERE parent_id = 0 AND type='1' ORDER BY cat_name ASC";
$categories = mysql_query($query_categories, $magazinducoin) or die(mysql_error());
$row_categories = mysql_fetch_assoc($categories);
$totalRows_categories = mysql_num_rows($categories);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset1 = "SELECT * FROM utilisateur ORDER BY email ASC";
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
//echo $totalRows_Recordset1;
//echo $query_Recordset1;

// Make an insert transaction instance
$ins_coupons = new tNG_multipleInsert($conn_magazinducoin);
$tNGs->addTransaction($ins_coupons);
// Register triggers
$ins_coupons->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_coupons->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_coupons->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
//$ins_coupons->registerTrigger("AFTER", "Trigger_send_newsletter", 98);

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
	  <p>Le magasin ".$magasin_en_cour['nom_magazin']." a un coupon du reduction de {reduction}% du {date_debut} à {date_fin}</p>
	   <p>Titre du coupon est: {titre}</p>
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
$ins_coupons->setTable("coupons");
$ins_coupons->addColumn("titre", "STRING_TYPE", "POST", "titre");
$ins_coupons->addColumn("reduction", "STRING_TYPE", "POST", "reduction");
$ins_coupons->addColumn("date_debut", "DATE_TYPE", "POST", "date_debut");
$ins_coupons->addColumn("date_fin", "DATE_TYPE", "POST", "date_fin");
$ins_coupons->addColumn("categories", "STRING_TYPE", "POST", "categories");
$ins_coupons->addColumn("sous_categorie", "STRING_TYPE", "POST", "sous_categorie");
$ins_coupons->addColumn("description", "STRING_TYPE", "POST", "description");
$ins_coupons->addColumn("id_magasin", "STRING_TYPE", "POST", "id_magasin");
$ins_coupons->addColumn("code_bare", "STRING_TYPE", "POST", "code_bare");
$ins_coupons->addColumn("min_achat", "STRING_TYPE", "POST", "min_achat", "0");
$ins_coupons->addColumn("id_user", "NUMERIC_TYPE", "POST", "id_user");
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
$upd_coupons->addColumn("titre", "STRING_TYPE", "POST", "titre");
$upd_coupons->addColumn("reduction", "STRING_TYPE", "POST", "reduction");
$upd_coupons->addColumn("date_debut", "DATE_TYPE", "POST", "date_debut");
$upd_coupons->addColumn("date_fin", "DATE_TYPE", "POST", "date_fin");
$upd_coupons->addColumn("categories", "STRING_TYPE", "POST", "categories");
$upd_coupons->addColumn("sous_categorie", "STRING_TYPE", "POST", "sous_categorie");
$upd_coupons->addColumn("description", "STRING_TYPE", "POST", "description");
$upd_coupons->addColumn("id_magasin", "STRING_TYPE", "POST", "id_magasin");
$upd_coupons->addColumn("code_bare", "STRING_TYPE", "POST", "code_bare");
$upd_coupons->addColumn("min_achat", "STRING_TYPE", "POST", "min_achat");
$upd_coupons->addColumn("id_user", "NUMERIC_TYPE", "POST", "id_user");
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
<script src="includes/skins/style.js" type="text/javascript"></script>
<?php echo $tNGs->displayValidationRules();?>
<script src="../includes/nxt/scripts/form.js" type="text/javascript"></script>
<script src="../includes/nxt/scripts/form.js.php" type="text/javascript"></script>
<script type="text/javascript">
$NXT_FORM_SETTINGS = {
  duplicate_buttons: false,
  show_as_grid: false,
  merge_down_value: false
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
<body id="sp" <?php if(isset($_GET['id_coupon'])) { ?>
onload="ajax('../ajax/sous_categorie.php?default=<?php echo $row_rscoupons['sous_categorie']; ?>&id_parent=<?php echo $row_rscoupons['categories']; ?>','#sous_categorie_1');
ajax('../ajax/magasins.php?default=<?php echo $row_rscoupons['id_magasin']; ?>&id_user=<?php echo $row_rscoupons['id_user']; ?>', '#id_magasin_1'); "
<?php } ?>>
<?php include("modules/header.php"); ?>

<div class="content_wrapper_sbr">
	<div>
  		<div id="content">
  		           <div class="KT_tng">
            <div class="KT_tngform">
            <h1>Coupon</h1>
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
<?php
	echo $tNGs->getErrorMsg();
?>
<table cellpadding="2" cellspacing="0" class="KT_tngtable">
    <tr><td class="KT_th"><label for="titre_<?php echo $cnt1; ?>">Titre:</label></td>
    <td><input type="text" name="titre_<?php echo $cnt1; ?>" id="titre_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rscoupons['titre']); ?>" size="32" />
                          <?php echo $tNGs->displayFieldHint("titre");?> <?php echo $tNGs->displayFieldError("coupons", "titre", $cnt1); ?> </td></tr>
                          
    <tr><td class="KT_th"><label for="reduction_<?php echo $cnt1; ?>">Réduction:</label></td>
   <td> <select name="reduction_<?php echo $cnt1; ?>" id="reduction_<?php echo $cnt1; ?>">
                          <option value="5" <?php if (!(strcmp(5, KT_escapeAttribute($row_rscoupons['reduction'])))) {echo "SELECTED";} ?>>5%</option>
                          <option value="10" <?php if (!(strcmp(10, KT_escapeAttribute($row_rscoupons['reduction'])))) {echo "SELECTED";} ?>>10%</option>
                          <option value="20" <?php if (!(strcmp(20, KT_escapeAttribute($row_rscoupons['reduction'])))) {echo "SELECTED";} ?>>20%</option>
                          <option value="30" <?php if (!(strcmp(30, KT_escapeAttribute($row_rscoupons['reduction'])))) {echo "SELECTED";} ?>>30%</option>
                          <option value="40" <?php if (!(strcmp(40, KT_escapeAttribute($row_rscoupons['reduction'])))) {echo "SELECTED";} ?>>40%</option>
                          <option value="50" <?php if (!(strcmp(50, KT_escapeAttribute($row_rscoupons['reduction'])))) {echo "SELECTED";} ?>>50%</option>
                          <option value="60" <?php if (!(strcmp(60, KT_escapeAttribute($row_rscoupons['reduction'])))) {echo "SELECTED";} ?>>60%</option>
                          <option value="70" <?php if (!(strcmp(70, KT_escapeAttribute($row_rscoupons['reduction'])))) {echo "SELECTED";} ?>>70%</option>
                        </select>
                          <?php echo $tNGs->displayFieldError("coupons", "reduction", $cnt1); ?> </td></tr>
                          
    <tr><td class="KT_th"><label for="date_debut_<?php echo $cnt1; ?>">Date de debut:</label></td>
   <td> <input name="date_debut_<?php echo $cnt1; ?>" id="date_debut_<?php echo $cnt1; ?>" value="<?php echo KT_formatDate($row_rscoupons['date_debut']); ?>" size="10" maxlength="22" wdg:mondayfirst="true" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="yes" />
                          <?php echo $tNGs->displayFieldHint("date_debut");?> <?php echo $tNGs->displayFieldError("coupons", "date_debut", $cnt1); ?> </td></tr>
                          
    <tr><td class="KT_th"><label for="date_fin_<?php echo $cnt1; ?>">Date fin</label></td>
   <td> <input name="date_fin_<?php echo $cnt1; ?>" id="date_fin_<?php echo $cnt1; ?>" value="<?php echo KT_formatDate($row_rscoupons['date_fin']); ?>" size="10" maxlength="22" wdg:mondayfirst="true" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="yes" />
                          <?php echo $tNGs->displayFieldHint("date_fin");?> <?php echo $tNGs->displayFieldError("coupons", "date_fin", $cnt1); ?> </td></tr>
    <tr>
        <td class="KT_th"><label for="id_user_<?php echo $cnt1; ?>">Utilisateur:</label></td>
        <td>
        <select name="id_user_<?php echo $cnt1; ?>" id="id_user_<?php echo $cnt1; ?>"  onchange="ajax('../ajax/magasins.php?default=<?php echo $row_rscoupons['id_magasin']; ?>&coupon=1&id_user='+this.value, '#id_magasin_<?php echo $cnt1; ?>');">
        
        <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
        <?php do { ?>
        <option value="<?php echo $row_Recordset1['id'];?>"<?php if (!(strcmp($row_Recordset1['id'], $row_rscoupons['id_user']))) {echo "SELECTED";} ?>>
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
        <?php echo $tNGs->displayFieldError("coupons", "id_user", $cnt1); ?> 
		</td>
	</tr>
       
                          
    <tr><td class="KT_th"><label for="id_magasin_<?php echo $cnt1; ?>">Magasin:</label></td>
    <td>
    <select name="id_magasin_<?php echo $cnt1; ?>" id="id_magasin_<?php echo $cnt1; ?>">
        <option value=""><?php echo NXT_getResource("Select one..."); ?></option>        
 	</select>
    <?php echo $tNGs->displayFieldError("coupons", "id_magasin", $cnt1); ?> </td></tr>
	
    
    <tr><td class="KT_th"><label for="code_bare_<?php echo $cnt1; ?>">Code bare:</label></td>
   <td> <input type="text" name="code_bare_<?php echo $cnt1; ?>" id="code_bare_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rscoupons['code_bare']); ?>" size="32" />
                          <?php //echo $tNGs->displayFieldHint("code_bare");?>
                          <?php echo $tNGs->displayFieldError("coupons", "code_bare", $cnt1); ?> </div>
</td></tr>

	<tr><td class="KT_th"><label for="categories_<?php echo $cnt1; ?>">Catégorie</label></td>
    <td><select name="categories_<?php echo $cnt1; ?>" id="categories_<?php echo $cnt1; ?>"  onchange="ajax('../ajax/sous_categorie.php?default=<?php echo $row_rscoupons['sous_categorie']; ?>&id_parent='+this.value,'#sous_categorie_<?php echo $cnt1; ?>'); if(this.value == -1) document.getElementById('min_achat_tr').style.display=''; else document.getElementById('min_achat_tr').style.display='none';">
                          <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                          <option value="-1" <?php if($row_rscoupons['categories'] == -1) echo "selected"; ?>>Tout le magasin</option>
                          <?php 
do {  
?>
                          <option value="<?php echo $row_categories['cat_id']?>"<?php if (!(strcmp($row_categories['cat_id'], $row_rscoupons['categories']))) {echo "SELECTED";} ?>><?php echo ($row_categories['cat_name']); ?></option>
                          <?php
} while ($row_categories = mysql_fetch_assoc($categories));
  $rows = mysql_num_rows($categories);
  if($rows > 0) {
      mysql_data_seek($categories, 0);
	  $row_categories = mysql_fetch_assoc($categories);
  }
?>
                        </select>
                          <?php echo $tNGs->displayFieldError("coupons", "categories", $cnt1); ?> </td></tr>
                          
   <tr><td class="KT_th"> <label for="sous_categorie_<?php echo $cnt1; ?>">Sous catégorie:</label></td>
   <td> <select name="sous_categorie_<?php echo $cnt1; ?>" id="sous_categorie_<?php echo $cnt1; ?>">
                          <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                        </select>
                          <?php echo $tNGs->displayFieldError("coupons", "sous_categorie", $cnt1); ?></td></tr>
                                       
   </td></tr> 
   <tr><td class="KT_th"><label for="description_<?php echo $cnt1; ?>">Description:</label></td>
    <td><input type="text" name="description_<?php echo $cnt1; ?>" id="description_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rscoupons['description']); ?>" size="32" />
                          <?php echo $tNGs->displayFieldHint("description");?> <?php echo $tNGs->displayFieldError("coupons", "description", $cnt1); ?> </td></tr>
                          
   <tr id="min_achat_tr" <?php if($row_rscoupons['categories'] != -1) echo 'style="display:none;"'; ?>> <td class="KT_th">
    <label for="min_achat_<?php echo $cnt1; ?>">Minimum achat pour appliquer le coupon (en €):</label></td>
    <td>
    <input type="text" name="min_achat_<?php echo $cnt1; ?>" id="min_achat_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rscoupons['min_achat']); ?>" size="32" />
                          <?php echo $tNGs->displayFieldHint("min_achat");?> <?php echo $tNGs->displayFieldError("coupons", "min_achat", $cnt1); ?> </td></tr>
   </table>

                
                  <input type="hidden" name="kt_pk_coupons_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rscoupons['kt_pk_coupons']); ?>" />
                  <?php } while ($row_rscoupons = mysql_fetch_assoc($rscoupons)); ?>

<div class="KT_bottombuttons">
                  <div>
                    <?php 
      // Show IF Conditional region1
      if (@$_GET['id_coupon'] == "") {
      ?>
                      <input type="submit" name="KT_Insert1" class="image-submit" id="KT_Insert1" value="<?php echo NXT_getResource("Insert_FB"); ?>" />
                      <?php 
      // else Conditional region1
      } else { ?>
                      <input type="submit" class="image-submit" name="KT_Update1" value="<?php echo NXT_getResource("Update_FB"); ?>" />
                      <input type="submit" class="image-submit" name="KT_Delete1" value="<?php echo NXT_getResource("Delete_FB"); ?>" onclick="return confirm('<?php echo NXT_getResource("Are you sure?"); ?>');" />
                      <?php }
      // endif Conditional region1
      ?>
                    <input type="button" class="image-submit" name="KT_Cancel1" value="<?php echo NXT_getResource("Cancel_FB"); ?>" onclick="return UNI_navigateCancel(event, '../includes/nxt/back.php')" />
</div>
</div>
              </form>
            </div>
            <br class="clearfixplain" />
      </div>
      </div>
	</div>
</div>
<?php //include("modules/footer.php"); ?>




</body>
</html>
<?php
mysql_free_result($categories);

mysql_free_result($Recordset1);
?>