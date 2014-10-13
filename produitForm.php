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
$restrict->addLevel("1");
$restrict->Execute();
//End Restrict Access To Page

//Test de limite d'ajout gratuit
$max_produit_free = 10;
$rkt = "SELECT count(*) as nb from produits where id_user = ".$_SESSION['kt_login_id'];
$query=mysql_query($rkt);
$nbproduit=mysql_fetch_array($query);

//if($nbproduit['nb'] >= $max_produit_free) {
//    $rkt = "SELECT credit from utilisateur where id = ".$_SESSION['kt_login_id'];
//    $query=mysql_query($rkt);
//    $creditrow=mysql_fetch_array($query);
//    if($creditrow['credit'] >= 3){
//        header('Location: payer_par_credit.php?ids=121&type=produit&redirect=mes-produits.php');
//        //Updater le seuil.
//        exit();
//    }else{            
//        header('Location: payer_abonement.php?type=produit&max_free=1');
//        //Updater le seuil.
//        exit();
//    }
//}

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("id_magazin", true, "numeric", "", "", "", "");
$formValidation->addField("categorie", true, "numeric", "", "", "", "");
$formValidation->addField("sous_categorie", true, "numeric", "", "", "", "");
$formValidation->addField("titre", true, "text", "", "1", "80", "80 caractéres");
//$formValidation->addField("reference", true, "text", "", "", "", "");
$formValidation->addField("en_stock", true, "numeric", "", "", "", "");
$formValidation->addField("prix2", true, "numeric", "", "", "", "");
if(!isset($_GET['id']))
$formValidation->addField("photo1", true, "", "", "", "", "");

$formValidation->addField("description", true, "text", "", "1", "800", "800 caractéres");
//$formValidation->addField("code_bare", true, "text", "zip_generic", "12", "13", "Le code bare doit contenir 12 ou 13 caractéres");
$tNGs->prepareValidation($formValidation);
// End trigger

//start Trigger_FileDelete2 trigger
//remove this line if you want to edit the code by hand 
function Trigger_FileDelete2(&$tNG) {
  $deleteObj = new tNG_FileDelete($tNG);
  $deleteObj->setFolder("assets/images/produits/");
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
  $uploadObj->setFolder("assets/images/produits/");
  $uploadObj->setResize("true", 400, 400);
  $uploadObj->setMaxSize(1500);
  $uploadObj->setAllowedExtensions("gif, jpg, jpe, jpeg, png");
  $uploadObj->setRename("auto");
  return $uploadObj->Execute();
}
//end Trigger_ImageUpload2 trigger

//start Trigger_FileDelete1 trigger
//remove this line if you want to edit the code by hand 
function Trigger_FileDelete1(&$tNG) {
  $deleteObj = new tNG_FileDelete($tNG);
  $deleteObj->setFolder("assets/images/produits/");
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
  $uploadObj->setFolder("assets/images/produits/");
  $uploadObj->setResize("true", 400, 400);
  $uploadObj->setMaxSize(1500);
  $uploadObj->setAllowedExtensions("gif, jpg, jpe, jpeg, png");
  $uploadObj->setRename("auto");
  return $uploadObj->Execute();
}
//end Trigger_ImageUpload1 trigger

//start Trigger_FileDelete trigger
//remove this line if you want to edit the code by hand 
function Trigger_FileDelete(&$tNG) {
  $deleteObj = new tNG_FileDelete($tNG);
  $deleteObj->setFolder("assets/images/produits/");
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
  $uploadObj->setFolder("assets/images/produits/");
  $uploadObj->setResize("true", 400, 400);
  $uploadObj->setMaxSize(1500);
  $uploadObj->setAllowedExtensions("gif, jpg, jpe, jpeg, png");
  $uploadObj->setRename("auto");
  return $uploadObj->Execute();
}
//end Trigger_ImageUpload trigger

function Trigger_Dexx(&$tNG) {
	/*if($_POST['public_1']) {
		$_SESSION['montant_payer'] += 4;
		$_SESSION['options'][] = 'public';
	}*/
	return true;
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
if(isset($_GET['republier'])){
	mysql_select_db($database_magazinducoin, $magazinducoin);
	$magasins = mysql_query("UPDATE produits SET date_echance = '".date('Y-m-d',mktime(0,0,0,date('m')+2,date('d'),date('Y')))."' WHERE id = ".$_GET['id'], $magazinducoin) or die(mysql_error());
	header('Location: mes-produits.php?msg=repok');
}

$colname_magasins = "-1";
if (isset($_SESSION['kt_login_id'])) {
  $colname_magasins = $_SESSION['kt_login_id'];
}
mysql_select_db($database_magazinducoin, $magazinducoin);
$query_magasins = sprintf("SELECT id_magazin, nom_magazin FROM magazins WHERE id_user = %s AND magazins.activate='1' AND magazins.payer='1' AND magazins.approuve = '1' ORDER BY nom_magazin ASC", GetSQLValueString($colname_magasins, "int"));
$magasins = mysql_query($query_magasins, $magazinducoin) or die(mysql_error());
$row_magasins = mysql_fetch_assoc($magasins);
$totalRows_magasins = mysql_num_rows($magasins);
//echo $query_magasins;

$query_Recordset1 = "SELECT credit FROM utilisateur WHERE id = ".$_SESSION['kt_login_id'];
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$row_credit = mysql_fetch_assoc($Recordset1);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_categories = "SELECT cat_id, cat_name FROM category WHERE parent_id = 0 AND type='0' ORDER BY cat_name ASC";
$categories = mysql_query($query_categories, $magazinducoin) or die(mysql_error());
$row_categories = mysql_fetch_assoc($categories);
$totalRows_categories = mysql_num_rows($categories);

$colname_coupons = "-1";
if (isset($_SESSION['kt_login_id'])) {
  $colname_coupons = $_SESSION['kt_login_id'];
}
mysql_select_db($database_magazinducoin, $magazinducoin);
$query_coupons = sprintf("SELECT id_coupon, titre FROM coupons WHERE id_user = %s ORDER BY reduction ASC", GetSQLValueString($colname_coupons, "int"));
$coupons = mysql_query($query_coupons, $magazinducoin) or die(mysql_error());
$row_coupons = mysql_fetch_assoc($coupons);
$totalRows_coupons = mysql_num_rows($coupons);


$colname_pub = "-1";
if (isset($_GET['id'])) {
	if(array_key_exists("dupliquer", $_GET)){
		$colname_pub = "-1";
	}else{
		$colname_pub = $_GET['id'];	
	}
}
mysql_select_db($database_magazinducoin, $magazinducoin);
$query_liste_pub = "SELECT 
  tt.*, (SELECT 
    COUNT(*) 
  FROM
    pub 
  WHERE id_user = ".$_SESSION['kt_login_id']." 
    AND emplacement = tt.id 
    AND date_fin > '".date(' Y- m - d H :i :s ')."' 
    AND id_produit = $colname_pub 
    AND payer = 1) AS is_existe 
FROM
  pub_emplacement tt 
  INNER JOIN 
    (SELECT 
      sub_type,
      MAX(date_debut) AS MaxDateTime 
    FROM
      pub_emplacement 
    WHERE date_debut <= NOW() 
    GROUP BY sub_type) groupedtt 
    ON tt.sub_type = groupedtt.sub_type 
    AND tt.date_debut = groupedtt.MaxDateTime 
WHERE tt.type = '2' ORDER BY sub_type ASC ";
$liste_pub = mysql_query($query_liste_pub, $magazinducoin) or die(mysql_error());
$totalRows_liste_pub = mysql_num_rows($liste_pub);
//echo $query_liste_pub;

if($_GET['id']!=''){
	$query_check = "SELECT id , id_user FROM produits WHERE id='".$_GET['id']."' AND id_user= '".$_SESSION['kt_login_id']."'";
	$check = mysql_query($query_check) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	if(!$row_check){
		header('Location: mes-produits.php');
	}
}

// Make an insert transaction instance
$ins_produits = new tNG_multipleInsert($conn_magazinducoin);
$tNGs->addTransaction($ins_produits);
// Register triggers
$ins_produits->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_produits->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_produits->registerTrigger("END", "Trigger_Dexx", 99);
$ins_produits->registerTrigger("AFTER", "Trigger_ImageUpload", 97);
$ins_produits->registerTrigger("AFTER", "Trigger_ImageUpload1", 97);
$ins_produits->registerTrigger("AFTER", "Trigger_ImageUpload2", 97);
// Add columns
$ins_produits->setTable("produits");
$ins_produits->addColumn("id_user", "NUMERIC_TYPE", "POST", "id_user", "{SESSION.kt_login_id}");
$ins_produits->addColumn("id_magazin", "NUMERIC_TYPE", "POST", "id_magazin");
$ins_produits->addColumn("categorie", "NUMERIC_TYPE", "POST", "categorie");
$ins_produits->addColumn("sous_categorie", "NUMERIC_TYPE", "POST", "sous_categorie");
//$ins_produits->addColumn("sous_categorie2", "NUMERIC_TYPE", "POST", "sous_categorie2");
$ins_produits->addColumn("titre", "STRING_TYPE", "POST", "titre");
$ins_produits->addColumn("reference", "STRING_TYPE", "POST", "reference");
$ins_produits->addColumn("code_bare", "STRING_TYPE", "POST", "code_bare");
$ins_produits->addColumn("reduction", "NUMERIC_TYPE", "POST", "reduction");
$ins_produits->addColumn("prix", "DOUBLE_TYPE", "POST", "prix");
$ins_produits->addColumn("prix2", "DOUBLE_TYPE", "POST", "prix2");
$ins_produits->addColumn("en_stock", "NUMERIC_TYPE", "POST", "en_stock");
$ins_produits->addColumn("description", "STRING_TYPE", "POST", "description");
$ins_produits->addColumn("photo2", "FILE_TYPE", "FILES", "photo2");
$ins_produits->addColumn("photo3", "FILE_TYPE", "FILES", "photo3");
$ins_produits->addColumn("photo1", "FILE_TYPE", "FILES", "photo1");
$ins_produits->addColumn("date_ajout", "STRING_TYPE", "POST", "date_ajout");
$ins_produits->addColumn("date_echance", "STRING_TYPE", "POST", "date_echance");
$ins_produits->setPrimaryKey("id", "NUMERIC_TYPE");


// Make an update transaction instance
$upd_produits = new tNG_multipleUpdate($conn_magazinducoin);
$tNGs->addTransaction($upd_produits);
// Register triggers
$upd_produits->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_produits->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_produits->registerTrigger("END", "Trigger_Dexx", 99);
$upd_produits->registerTrigger("AFTER", "Trigger_ImageUpload", 97);
$upd_produits->registerTrigger("AFTER", "Trigger_ImageUpload1", 97);
$upd_produits->registerTrigger("AFTER", "Trigger_ImageUpload2", 97);


// Add columns
$upd_produits->setTable("produits");
$upd_produits->addColumn("id_user", "NUMERIC_TYPE", "POST", "id_user");
$upd_produits->addColumn("id_magazin", "NUMERIC_TYPE", "POST", "id_magazin");
$upd_produits->addColumn("categorie", "NUMERIC_TYPE", "POST", "categorie");
$upd_produits->addColumn("sous_categorie", "NUMERIC_TYPE", "POST", "sous_categorie");
//$upd_produits->addColumn("sous_categorie2", "NUMERIC_TYPE", "POST", "sous_categorie2");
$upd_produits->addColumn("titre", "STRING_TYPE", "POST", "titre");
$upd_produits->addColumn("reference", "STRING_TYPE", "POST", "reference");
$upd_produits->addColumn("code_bare", "STRING_TYPE", "POST", "code_bare");
$upd_produits->addColumn("reduction", "NUMERIC_TYPE", "POST", "reduction");
$upd_produits->addColumn("prix", "DOUBLE_TYPE", "POST", "prix");
$upd_produits->addColumn("prix2", "DOUBLE_TYPE", "POST", "prix2");
$upd_produits->addColumn("en_stock", "NUMERIC_TYPE", "POST", "en_stock");
$upd_produits->addColumn("description", "STRING_TYPE", "POST", "description");
$upd_produits->addColumn("photo2", "FILE_TYPE", "FILES", "photo2");
$upd_produits->addColumn("photo3", "FILE_TYPE", "FILES", "photo3");
$upd_produits->addColumn("photo1", "FILE_TYPE", "FILES", "photo1");
$upd_produits->setPrimaryKey("id", "NUMERIC_TYPE", "GET", "id");

// Make an instance of the transaction object
$del_produits = new tNG_multipleDelete($conn_magazinducoin);
$tNGs->addTransaction($del_produits);
// Register triggers
$del_produits->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_produits->registerTrigger("END", "Trigger_Default_Redirect", 99, "includes/nxt/back.php");
$del_produits->registerTrigger("AFTER", "Trigger_FileDelete", 98);
$del_produits->registerTrigger("AFTER", "Trigger_FileDelete1", 98);
$del_produits->registerTrigger("AFTER", "Trigger_FileDelete2", 98);
// Add columns
$del_produits->setTable("produits");
$del_produits->setPrimaryKey("id", "NUMERIC_TYPE", "GET", "id");

// Execute all the registered transactions
$tNGs->executeTransactions();


if(isset($_POST['KT_Insert1'])){
	$query_Recordset9 = "SELECT  max(id) as id FROM produits WHERE id_user = ".$_SESSION['kt_login_id'];
    $Recordset9 = mysql_query($query_Recordset9, $magazinducoin) or die('0'.mysql_error());
    $row_magazin = mysql_fetch_assoc($Recordset9);
	$id_produit = $row_magazin['id'];
	
	//$id_produit = mysql_insert_id();
	if(isset($_POST['autres'])){
		
		foreach($_POST['autres'] as $k=>$v){
			$k1 = explode('-',$k);
			$query = sprintf("INSERT INTO autres_champs_data (id_champs, name, value, id_produit) VALUES (%s, %s, %s, %s) ",
									  GetSQLValueString($k1[0], "int"),
									  GetSQLValueString($k1[0], "text"),
									  GetSQLValueString($v, "text"),
									  GetSQLValueString($id_produit, "int"));
			mysql_query($query, $magazinducoin) or die(mysql_error());
		}
	}
	
		$pro="SELECT  utilisateur.id
    , utilisateur.nom
    , utilisateur.prenom
    , utilisateur.email
    , magazins.nom_magazin
    , category.cat_name
    , produits.titre
FROM
    produits
    INNER JOIN utilisateur 
        ON (produits.id_user = utilisateur.id)
    INNER JOIN magazins 
        ON (produits.id_magazin = magazins.id_magazin)
    INNER JOIN category 
        ON (produits.sous_categorie = category.cat_id) WHERE produits.id = (SELECT MAX(id) AS id FROM produits WHERE id_user='".$_SESSION['kt_login_id']."')";
		$query_pro=mysql_query($pro);
		$result_pro=mysql_fetch_array($query_pro);
		
		SendMail_Create_Product_Shpper($result_pro['email'],$result_pro['nom'],$result_pro['prenom'],$result_pro['titre']);
		SendMail_Create_Product_Ownner($result_pro['email'],$result_pro['nom'],$result_pro['prenom'],$result_pro['titre'],$result_pro['nom_magazin'],$result_pro['cat_name']);
	
	if(count($_POST['pub'])){
		$rkt="SELECT magazins.region, produits.categorie, produits.titre
		FROM (produits
		LEFT JOIN magazins ON magazins.id_magazin=produits.id_magazin)
		WHERE produits.id = $id_produit ";
		
		$query=mysql_query($rkt);
		$titreproduit=mysql_fetch_array($query);
		$titre= $titreproduit['titre'];
		$cat= $titreproduit['categorie'];
        $region=$titreproduit['region'];
		$jr=7;
		$datefin = date("Y-m-d H:i:s", mktime(date("H"), date("i"), date("s"), date("m"), date("d")+$jr,  date("Y")));	
		$ids = array();
	  	foreach($_POST['pub'] as $k=>$v){
		  	$sql="insert into pub (id_user,titre,region,emplacement,id_produit,date_fin) values (".$_SESSION['kt_login_id'].",'$titre','$region','$k','$id_produit','$datefin')";
		  	$query2=mysql_query($sql);
		  	$ids[] = mysql_insert_id();
      	}
		if(count($ids)){
	 		if($row_credit['credit']<0){
				/*echo"<script>alert('1');</script>";*/
				//header('Location: payer_pub.php?ap=1&ids='.implode(",",$ids));
				echo'<script>window.location="payer_pub.php?ap=1&ids='.implode(",",$ids).'";</script>';  
				exit();
			}
			else{
				if($_POST['day_en_avant']!=''){
					$_SESSION['day_en_avant']=$_POST['day_en_avant'];
				}
				if($_POST['day_en_flash']!=''){
					$_SESSION['day_en_flash']=$_POST['day_en_flash'];
				}
				if($_POST['day_en_tete_liste']!=''){
					$_SESSION['day_en_tete_liste']=$_POST['day_en_tete_liste'];
				}
	 			//header('Location: payer_par_credit.php?ids='.implode(",",$ids));
				echo'<script>window.location="produit_pay-'.implode(",",$ids).'.html";</script>'; 
				exit();
			}
		}
		else{
				/*echo"<script>alert('3');</script>";*/
			//header('Location: mes-produits.php');	
				echo'<script>window.location="mes_produits.html";</script>'; 
			exit();
		}
	}
	else{
				/*echo"<script>alert('4');</script>";*/
		//header('Location: mes-produits.php');
				echo'<script>window.location="mes_produits.html";</script>';
		exit();
	}
}

if(isset($_POST['KT_Update1'])){
	$id_produit = $_GET['id'];
	if(isset($_POST['autres'])){
		foreach($_POST['autres'] as $k=>$v){
			$k1 = explode('-',$k);
			$query = sprintf("UPDATE autres_champs_data SET value = %s WHERE id_produit = %s AND id_champs = %s",
									  GetSQLValueString($v, "text"),
									  GetSQLValueString($id_produit, "int"),
									  GetSQLValueString($k1[0], "int"));
			mysql_query($query, $magazinducoin) or die(mysql_error());
		}
	}
	if(count($_POST['pub'])){
		$rkt="SELECT magazins.region, produits.categorie, produits.titre
FROM (produits
LEFT JOIN magazins ON magazins.id_magazin=produits.id_magazin)
WHERE produits.id = $id_produit ";
		$query=mysql_query($rkt);
		$titreproduit=mysql_fetch_array($query);
		$titre= $titreproduit['titre'];
		$cat= $titreproduit['categorie'];
        $region=$titreproduit['region'];
		$jr=30;
		$datefin = date("Y-m-d H:i:s", mktime(date("H"), date("i"), date("s"), date("m"), date("d")+$jr,  date("Y")));	
		$ids = array();
	  	foreach($_POST['pub'] as $k=>$v){
		  	$sql="insert into pub (id_user,titre,region,emplacement,id_produit,date_fin) values ('".$_SESSION['kt_login_id']."','$titre','$region','$k','$id_produit','$datefin')";
		  	$query2=mysql_query($sql);
		  	$ids[] = mysql_insert_id();
      	}
		if(count($ids))
	 		if($row_credit['credit']<0){
				header('Location: payer_pub.php?ap=1&ids='.implode(",",$ids));
				exit();
			}
			else{
				if($_POST['day_en_avant']!=''){
					$_SESSION['day_en_avant']=$_POST['day_en_avant'];
				}
				if($_POST['day_en_flash']!=''){
					$_SESSION['day_en_flash']=$_POST['day_en_flash'];
				}
				if($_POST['day_en_tete_liste']!=''){
					$_SESSION['day_en_tete_liste']=$_POST['day_en_tete_liste'];
				}
	 			header('Location: produit_pay-'.implode(",",$ids).'.html');
				exit();
			}
		else
			header('Location: mes_produits.html');
			
	}
	else
		header('Location: mes_produits.html');
}


// Get the transaction recordset
$rsproduits = $tNGs->getRecordset("produits");
$row_rsproduits = mysql_fetch_assoc($rsproduits);
$totalRows_rsproduits = mysql_num_rows($rsproduits);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
  show_as_grid: false,
  merge_down_value: false
}
function calculer_prix(){
	if(document.getElementById('prix_1').value == "")
		var prix = 0;
	else
		var prix = parseFloat(document.getElementById('prix_1').value);
	var reduction = parseInt(document.getElementById('reduction_1').value);
	var prix_total = parseFloat(prix - ((prix*reduction)/100));
	document.getElementById('prix2_1').value = prix_total;
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
	  a.popupwindow{text-decoration:none; color:#9D216E !important;}
  a.popupwindow:hover{ color:#F8C263 !important}
</style>

    <script type="text/javascript" src="assets/popup_2/jquery.popupwindow.js"></script>
    <script type="text/javascript">
	var profiles =
	{

		window800:
		{
			height:800,
			width:800,
			status:1
		},

		window200:
		{
			height:200,
			width:200,
			status:1,
			resizable:0
		},

		windowCenter:
		{
			height:300,
			width:400,
			center:1
		},

		windowNotNew:
		{
			height:300,
			width:400,
			center:1,
			createnew:0
		},

		windowCallUnload:
		{
			height:300,
			width:400,
			center:1,
			onUnload:unloadcallback
		},

	};

	function unloadcallback(){
		alert("unloaded");
	};


   	$(function()
	{
   		$(".popupwindow").popupwindow(profiles);
   	});
	</script>
</head>
<body id="sp"
<?php if(isset($_GET['id'])) { ?>
onload="ajax('ajax/sous_categorie.php?default=<?php echo $row_rsproduits['sous_categorie']; ?>&id_parent=<?php echo $row_rsproduits['categorie']; ?>','#sous_categorie_1'); ajax('ajax/autres_champs.php?categorie=<?php echo $row_rsproduits['categorie']; if(isset($_GET['id'])) echo "&id=".$_GET['id']; ?>','#autres_champs');"
<?php } ?>>
<?php include("modules/header.php"); ?>
<div id="content" class="photographes">
<?php //include("modules/member_menu.php"); ?>
<?php include("modules/credit.php"); ?>
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
	.loginForm textarea {
		border: 1px solid #CCCCCC;
		border-radius: 5px 5px 5px 5px;
	}
	.loginForm input[type="submit"], .loginForm input[type="button"]{
		background-color: #9D286E;
		border: medium none;
		color: #F8C263;
		cursor: pointer;
		font-size: 18px;
		margin: 0 0 0 5px;
		padding: 0 10px 3px;
	}
	.loginForm td{
		line-height:25px;	
	}
	</style>	
	<div style="float:left; width:100%;">
    		<?php echo $tNGs->getErrorMsg(); ?>
	<!--<div class="KT_tng">-->
            <h3  style="margin-left:20px;">
				  <?php // Show IF Conditional region1 
                        if (@$_GET['id'] == "") {
                  ?>
                  Insertion
                  <?php 
                        // else Conditional region1
                    } else { ?>
                   <?php echo $xml->modification; ?>
                  <?php } 
                // endif Conditional region1
                ?>
                  Produit
            </h3>
            <!--<div class="KT_tngform">-->
              <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" enctype="multipart/form-data">
              <div style="margin-left:20px; float:left; width:98%;" class="loginForm">
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

<table cellpadding="0" cellspacing="0" border="0" width="80%" align="center">

     <?php if($totalRows_magasins>1) { ?>
     <tr>
     	<td>
        <label for="id_magazin_<?php echo $cnt1; ?>"><?php echo $xml->Magasin ;?>:</label>
        </td>
        <td>
        <select name="id_magazin_<?php echo $cnt1; ?>" id="id_magazin_<?php echo $cnt1; ?>">
        	<option value=""><?php echo $xml->selectionner ;?></option>
        	<!--<option value="-1" <?php if ($row_rsproduits['id_magazin'] == -1) {echo "SELECTED";} ?>>Tous les magasins</option>-->
        <?php 
   		do {  
    	?>
		<option value="<?php echo $row_magasins['id_magazin']?>"<?php if (!(strcmp($row_magasins['id_magazin'], $row_rsproduits['id_magazin']))) {echo "SELECTED";} ?>><?php echo $row_magasins['nom_magazin']?></option>
		<?php
    	} while ($row_magasins = mysql_fetch_assoc($magasins));
			$rows = mysql_num_rows($magasins);
			if($rows > 0) {
			mysql_data_seek($magasins, 0);
			$row_magasins = mysql_fetch_assoc($magasins);
			}
		?>
		</select>
		<?php echo $tNGs->displayFieldError("produits", "id_magazin", $cnt1); ?>
        </td>
	</tr>
		<?php } else { ?>
			<input type="hidden" name="id_magazin_<?php echo $cnt1; ?>" id="id_magazin_<?php echo $cnt1; ?>" value="<?php echo $row_magasins['id_magazin']?>" />
		<?php } ?>
	<tr>
    	<td>
        <label for="categorie_<?php echo $cnt1; ?>"><?php echo $xml->Categorie ?>:</label>
        </td>
    	<td>
        <select name="categorie_<?php echo $cnt1; ?>" id="categorie_<?php echo $cnt1; ?>" onchange="ajax('ajax/sous_categorie.php?default=<?php echo $row_rsproduits['sous_categorie']; ?>&id_parent='+this.value,'#sous_categorie_<?php echo $cnt1; ?>'); ajax('ajax/autres_champs.php?categorie='+this.value+'<?php if(isset($_GET['id'])) echo "&id=".$_GET['id']; ?>','#autres_champs');">
        <option value=""><?php echo $xml->selectionner ;?></option>
        <?php 
        do {  
        ?>
        <option value="<?php echo $row_categories['cat_id']?>"<?php if (!(strcmp($row_categories['cat_id'], $row_rsproduits['categorie']))) {echo "SELECTED";} ?>><?php echo ($row_categories['cat_name']); ?></option>
        <?php } while ($row_categories = mysql_fetch_assoc($categories));
          $rows = mysql_num_rows($categories);
          if($rows > 0) {
              mysql_data_seek($categories, 0);
              $row_categories = mysql_fetch_assoc($categories);
          }
        ?>
        </select>
        <?php echo $tNGs->displayFieldError("produits", "categorie", $cnt1); ?>
        </td>
    </tr>
	<tr>
    	<td>
        <label for="sous_categorie_<?php echo $cnt1; ?>"><?php echo $xml->Sous_categorie; ?>:</label>
        </td>
    	<td>
        <select name="sous_categorie_<?php echo $cnt1; ?>" id="sous_categorie_<?php echo $cnt1; ?>" onchange="ajax('ajax/sous_categorie.php?default=<?php echo $row_rsproduits['sous_categorie']; ?>&id_parent='+this.value,'#sous_categorie2_<?php echo $cnt1; ?>');">
            <option value=""><?php echo $xml->selectionner ;?></option>
        </select>
        <?php echo $tNGs->displayFieldHint("sous_categorie");?> <?php echo $tNGs->displayFieldError("produits", "sous_categorie", $cnt1); ?> 
        </td>
    </tr>
	<?php /*?><tr>
    	<td>
        <label for="sous_categorie2_<?php echo $cnt1; ?>">Sous sous categorie:</label>
        </td>
    	<td>
        <select name="sous_categorie2_<?php echo $cnt1; ?>" id="sous_categorie2_<?php echo $cnt1; ?>">
            <option value=""><?php echo $xml->selectionner ;?></option>
        </select>
        <?php echo $tNGs->displayFieldHint("sous_categorie");?> <?php echo $tNGs->displayFieldError("produits", "sous_categorie", $cnt1); ?>
        </td>
    </tr><?php */?>
	<tr>
    	<td>
        <label for="titre_<?php echo $cnt1; ?>"><?php echo $xml->Titre ?>:</label>
        </td>
    	<td>
        	<input type="text" name="titre_<?php echo $cnt1; ?>" id="titre_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsproduits['titre']); ?>" size="32" maxlength="200" />
			<?php echo $tNGs->displayFieldHint("titre");?> <?php echo $tNGs->displayFieldError("produits", "titre", $cnt1); ?> 
        </td>
    </tr>
	<tr>
    	<td>
        <label for="reference_<?php echo $cnt1; ?>"><?php echo $xml-> Reference ?></label>
        </td>
    	<td>
        <input type="text" name="reference_<?php echo $cnt1; ?>" id="reference_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsproduits['reference']); ?>" size="7" />
		<?php echo $tNGs->displayFieldHint("reference");?> <?php echo $tNGs->displayFieldError("produits", "reference", $cnt1); ?>
        </td>
    </tr>
	<tr>
    	<td>
        <label for="prix_<?php echo $cnt1; ?>"><?php echo $xml->prix ?>:</label>
        </td>
    	<td>
        	<input type="text" name="prix_<?php echo $cnt1; ?>" id="prix_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsproduits['prix']); ?>" size="7" onkeyup="if(document.getElementById('reference_<?php echo $cnt1; ?>').value != '') calculer_prix(); " />
			<?php echo $tNGs->displayFieldHint("prix");?> <?php echo $tNGs->displayFieldError("produits", "prix", $cnt1); ?> 
        </td>
    </tr>
	<tr>
    	<td>
        <label for="reduction_<?php echo $cnt1; ?>"><?php echo $xml->Reduction ?>:</label>
        </td>
    	<td>
        <select name="reduction_<?php echo $cnt1; ?>" id="reduction_<?php echo $cnt1; ?>" onchange="calculer_prix();">
        	<option value="0"><?php echo NXT_getResource("Select one..."); ?></option>
            <option value="5" <?php if (!(strcmp(5, KT_escapeAttribute($row_rsproduits['reduction'])))) {echo "SELECTED";} ?>>5%</option>
            <option value="10" <?php if (!(strcmp(10, KT_escapeAttribute($row_rsproduits['reduction'])))) {echo "SELECTED";} ?>>10%</option>
            <option value="20" <?php if (!(strcmp(20, KT_escapeAttribute($row_rsproduits['reduction'])))) {echo "SELECTED";} ?>>20%</option>
            <option value="30" <?php if (!(strcmp(30, KT_escapeAttribute($row_rsproduits['reduction'])))) {echo "SELECTED";} ?>>30%</option>
            <option value="40" <?php if (!(strcmp(40, KT_escapeAttribute($row_rsproduits['reduction'])))) {echo "SELECTED";} ?>>40%</option>
            <option value="50" <?php if (!(strcmp(50, KT_escapeAttribute($row_rsproduits['reduction'])))) {echo "SELECTED";} ?>>50%</option>
            <option value="60" <?php if (!(strcmp(60, KT_escapeAttribute($row_rsproduits['reduction'])))) {echo "SELECTED";} ?>>60%</option>
            <option value="70" <?php if (!(strcmp(70, KT_escapeAttribute($row_rsproduits['reduction'])))) {echo "SELECTED";} ?>>70%</option>
            <option value="70" <?php if (!(strcmp(70, KT_escapeAttribute($row_rsproduits['reduction'])))) {echo "SELECTED";} ?>>80%</option>
            <option value="80" <?php if (!(strcmp(80, KT_escapeAttribute($row_rsproduits['reduction'])))) {echo "SELECTED";} ?>>90%</option>
            <option value="95" <?php if (!(strcmp(95, KT_escapeAttribute($row_rsproduits['reduction'])))) {echo "SELECTED";} ?>>95%</option>
		</select>
		<?php echo $tNGs->displayFieldHint("reduction");?> <?php echo $tNGs->displayFieldError("produits", "reduction", $cnt1); ?>
        </td>
    </tr>
	<tr>
    	<td>
        <label for="prix2_<?php echo $cnt1; ?>"><?php echo $xml->Prix_finale; ?>:</label>
        </td>
    	<td>
        <input type="text" name="prix2_<?php echo $cnt1; ?>" id="prix2_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsproduits['prix2']); ?>" size="7" />
		<?php echo $tNGs->displayFieldHint("prix2");?> <?php echo $tNGs->displayFieldError("produits", "prix2", $cnt1); ?>
        </td>
    </tr>
	<tr>
    	<td>
        <label for="code_bare_<?php echo $cnt1; ?>"><?php echo $xml->Code_bare ?>:</label>
        </td>
    	<td>
        <input type="text" name="code_bare_<?php echo $cnt1; ?>" id="code_bare_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsproduits['code_bare']); ?>" size="7" />
		<?php //echo $tNGs->displayFieldHint("code_bare");?> <?php echo $tNGs->displayFieldError("produits", "code_bare", $cnt1); ?> 
        </td>
    </tr>
	<tr>
    	<td>
        <label for="en_stock_<?php echo $cnt1; ?>_1"><?php echo $xml->En_stock ?>:</label>
        </td>
    	<td>
        Oui <input <?php if (!(strcmp(KT_escapeAttribute($row_rsproduits['en_stock']),"1"))) {echo "CHECKED";} ?> type="radio" name="en_stock_<?php echo $cnt1; ?>" id="en_stock_<?php echo $cnt1; ?>_1" value="1" />
        Non <input <?php if (!(strcmp(KT_escapeAttribute($row_rsproduits['en_stock']),"0"))) {echo "CHECKED";} ?> type="radio" name="en_stock_<?php echo $cnt1; ?>" id="en_stock_<?php echo $cnt1; ?>_2" value="0" />
        <?php echo $tNGs->displayFieldHint("en_stock");?> <?php echo $tNGs->displayFieldError("produits", "en_stock", $cnt1); ?>
        </td>
    </tr>
	<tr>
    	<td>
        <label for="description_<?php echo $cnt1; ?>"><?php echo $xml->Description ?>:</label>
        </td>
    	<td>
        <textarea name="description_<?php echo $cnt1; ?>" id="description_<?php echo $cnt1; ?>" cols="50" rows="5"><?php echo KT_escapeAttribute($row_rsproduits['description']); ?></textarea>
        <?php echo $tNGs->displayFieldHint("description");?> <?php echo $tNGs->displayFieldError("produits", "description", $cnt1); ?> 
        </td>
    </tr>
    
	<tr valign="top">
    	<td>
        <label for="photo1_<?php echo $cnt1; ?>">Photo de produit:</label>
        </td>
    	<td>
        
         
        <?php if($row_rsproduits['photo1']) { ?>
        	<?php if(array_key_exists("dupliquer", $_GET)){?>
            <div class="file-wrapper">
                <input type="file" name="photo1_<?php echo $cnt1; ?>" id="photo1_<?php echo $cnt1; ?>"/>
                <span class="button">Parcourir</span>
            </div>
            <?php }else{?>
            <div class="file-wrapper">
                <input type="file" name="photo1_<?php echo $cnt1; ?>" id="photo1_<?php echo $cnt1; ?>"/>
                <span class="button">Parcourir</span>
            </div>
            <div id="img1">
                <img src="assets/images/produits/<?php echo KT_escapeAttribute($row_rsproduits['photo1']); ?>" width="60" />&nbsp;&nbsp; <a href="javascript:ajax('ajax/supprimer_photo.php?t=produits&c=photo1&id=<?php echo $_GET['id']; ?>&f=<?php echo KT_escapeAttribute($row_rsproduits['photo1']); ?>','#img1');" style="color:#333;"><?php echo $xml->Supprimer_photo ?></a>
            </div> 
            <?php }?>
        <?php }else{?>
            <div class="file-wrapper">
                <input type="file" name="photo1_<?php echo $cnt1; ?>" id="photo1_<?php echo $cnt1; ?>"/>
                <span class="button">Parcourir</span>
            </div>
        <?php } ?>
        <?php echo $tNGs->displayFieldError("produits", "photo1", $cnt1); ?> 
        </td>
    </tr>
	<tr valign="top">
    	<td>
        <label for="photo2_<?php echo $cnt1; ?>">Photo de produit 2<?php //echo $xml->Photo_2 ?>:</label>
        </td>
    	<td>
        
        <?php if($row_rsproduits['photo2']) { ?>
        	<?php if(array_key_exists("dupliquer", $_GET)){?>
            <div class="file-wrapper">
                <input type="file" name="photo2_<?php echo $cnt1; ?>" id="photo2_<?php echo $cnt1; ?>"/>
                <span class="button">Parcourir</span>
            </div> 
            <?php }else{?>
            <div class="file-wrapper">
                <input type="file" name="photo2_<?php echo $cnt1; ?>" id="photo2_<?php echo $cnt1; ?>"/>
                <span class="button">Parcourir</span>
            </div> 
            <div id="img2">
                <img src="assets/images/produits/<?php echo KT_escapeAttribute($row_rsproduits['photo2']); ?>" width="60" />&nbsp;&nbsp; <a href="javascript:ajax('ajax/supprimer_photo.php?t=produits&c=photo2&id=<?php echo $_GET['id']; ?>&f=<?php echo KT_escapeAttribute($row_rsproduits['photo2']); ?>','#img2');" style="color:#333;">Supprimer photo</a>
            </div>
            <?php }?> 
        <?php }else{?>
            <div class="file-wrapper">
                <input type="file" name="photo2_<?php echo $cnt1; ?>" id="photo2_<?php echo $cnt1; ?>"/>
                <span class="button">Parcourir</span>
            </div> 
        <?php } ?>
        <?php echo $tNGs->displayFieldError("produits", "photo2", $cnt1); ?> 
        </td>
    </tr>
	<tr valign="top">
    	<td>
        <label for="photo3_<?php echo $cnt1; ?>">Photo de produit 3<?php //echo $xml->Photo_3 ?>:</label>
        </td>
    	<td>
       	
        <?php if($row_rsproduits['photo3']) { ?>
        	<?php if(array_key_exists("dupliquer", $_GET)){?>
            <div class="file-wrapper">
                <input type="file" name="photo3_<?php echo $cnt1; ?>" id="photo3_<?php echo $cnt1; ?>"/>
                <span class="button">Parcourir</span>
            </div>
            <?php }else{?>
            <div class="file-wrapper">
                <input type="file" name="photo3_<?php echo $cnt1; ?>" id="photo3_<?php echo $cnt1; ?>"/>
                <span class="button">Parcourir</span>
            </div>
            <div id="img3">
                <img src="assets/images/produits/<?php echo KT_escapeAttribute($row_rsproduits['photo3']); ?>" width="60" />&nbsp;&nbsp; <a href="javascript:ajax('ajax/supprimer_photo.php?t=produits&c=photo3&id=<?php echo $_GET['id']; ?>&f=<?php echo KT_escapeAttribute($row_rsproduits['photo3']); ?>','#img3');" style="color:#333;">Supprimer photo</a>
            </div>
            <?php }?> 
		<?php }else{?>
            <div class="file-wrapper">
                <input type="file" name="photo3_<?php echo $cnt1; ?>" id="photo3_<?php echo $cnt1; ?>"/>
                <span class="button">Parcourir</span>
            </div>
        <?php } ?>
        <?php echo $tNGs->displayFieldError("produits", "photo3", $cnt1); ?>
        </td>
    </tr>
	<tr>
    	<td colspan="2">
		<input type="hidden" name="kt_pk_produits_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsproduits['kt_pk_produits']); ?>" />
		<input type="hidden" name="id_user_<?php echo $cnt1; ?>" id="id_user_<?php echo $cnt1; ?>" value="<?php if($row_rsproduits['id_user']!=''){echo KT_escapeAttribute($row_rsproduits['id_user']);} else{echo $_SESSION['kt_login_id'];} ?>" />
          
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
            
            <?php if(array_key_exists("dupliquer", $_GET)){?>
            <input type="hidden" name="date_ajout_<?php echo $cnt1; ?>" id="date_ajout_<?php echo $cnt1; ?>" value="<?php echo date('Y-m-d'); ?>" />
            <input type="hidden" name="date_echance_<?php echo $cnt1; ?>" id="date_echance_<?php echo $cnt1; ?>" value="<?php 
            echo date('Y-m-d',mktime(0,0,0,date('m')+2,date('d'),date('Y'))); ?>" /> 
            <?php }?>

      
        <div id="autres_champs"></div>                 
        <?php } while ($row_rsproduits = mysql_fetch_assoc($rsproduits)); ?>
        <div class="clear"></div>
        <!--<div style="float:left; width:370px;">-->
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-top:2px solid; font-size:13px; font-weight:bold;" >
            <tr>
            	<td colspan="2"><span style="font-size:16px; font-weight:bold;">Faites votre publicité</span></td>
            </tr>
            <!--<tr>
                <th></th>
                <th width="85%"><?php echo $xml->Faites_publicite ?></th>
                <th width="10%"><?php echo $xml->prix ?></th>
            </tr>-->
            <?php
            while($row_liste_pub = mysql_fetch_assoc($liste_pub)){?>
            <tr>
                <td width="5"><?php if($row_liste_pub['is_existe']==0) { ?>
                <input  type="checkbox" id="c<?php echo $row_liste_pub['id']; ?>" <?php /*?>onchange="mafon(this)"<?php */?> value="<?php echo $row_liste_pub['prix']; ?>" name="pub[<?php echo $row_liste_pub['id']; ?>]">
                <?php } ?></td>
                <td>
                <label for="c<?php echo $row_liste_pub['id']; ?>" style="font-weight:bold;"><?php echo $row_liste_pub['titre'];?> (<?php echo $row_liste_pub['prix'];?> &euro;) </label>
				<?php if($row_liste_pub['sub_type']=='1'){;?>
                	<script type="text/javascript">
						$(document).ready(function() {
							$('.day_en_avant').hide();
							$("#c<?php echo $row_liste_pub['id']; ?>").click(function() {
								var day_en_avant = $('#day_en_avant_1').val();
								if($("#c<?php echo $row_liste_pub['id']; ?>").is(':checked')){
									$('.day_en_avant').show();
									var en_avant = $('#day_en_avant').val(day_en_avant);
									en_avants();
								}else if($("#c<?php echo $row_liste_pub['id']; ?>").is(":not(:checked)")){
									$('.day_en_avant').hide();
									$('#day_en_avant').val('');
								}
							});
							function en_avants(){
								$('#day_en_avant_1').change(function() {
									var day_en_avant = $('#day_en_avant_1').val();
									var en_avant = $('#day_en_avant').val(day_en_avant);
								});
							}
						});
					</script>
                    <span class="day_en_avant">
                	Pendant
                    <input type="hidden" name="day_en_avant" id="day_en_avant" />
                    <select name="day_en_avant_<?php echo $cnt1; ?>" id="day_en_avant_<?php echo $cnt1; ?>" style="width:150px;">
                    	<?php for($j=1; $j<=15; $j++){?>
                        	<option value="<?php echo $j;?>"><?php echo $j;?> jour <?php if($j>1){?>(s)<?php }?> = <?php echo ($j*$row_liste_pub['prix']);?> &euro;</option>
                        <?php }?>
                    </select>
                    </span>
                <?php }?>
                
                <?php if($row_liste_pub['sub_type']=='2'){;?>
                	<script type="text/javascript">
						$(document).ready(function() {
							$('.day_en_flash').hide();
							$("#c<?php echo $row_liste_pub['id']; ?>").click(function() {
								var day_en_flash = $('#day_en_flash_1').val();
								if($("#c<?php echo $row_liste_pub['id']; ?>").is(':checked')){
									$('.day_en_flash').show();
									var en_flash = $('#day_en_flash').val(day_en_flash);
									en_flashsss();
								}else if($("#c<?php echo $row_liste_pub['id']; ?>").is(":not(:checked)")){
									$('.day_en_flash').hide();
									$('#day_en_flash').val('');
								}
							});
							function en_flashsss(){
								$('#day_en_flash_1').change(function() {
									var day_en_flash = $('#day_en_flash_1').val();
									var en_flash = $('#day_en_flash').val(day_en_flash);
								});
							}
						});
					</script>
                    <span class="day_en_flash">
                	Pendant
                    <input type="hidden" name="day_en_flash" id="day_en_flash" />
                    <select name="day_en_flash_<?php echo $cnt1; ?>" id="day_en_flash_<?php echo $cnt1; ?>" style="width:150px;">
                    	<?php for($j=1; $j<=15; $j++){?>
                        	<option value="<?php echo $j;?>"><?php echo $j;?> jour <?php if($j>1){?>(s)<?php }?> = <?php echo ($j*$row_liste_pub['prix']);?> &euro;</option>
                        <?php }?>
                    </select>
                    </span>
                <?php }?>
                
                <?php if($row_liste_pub['sub_type']=='3'){;?>
                	<script type="text/javascript">
						$(document).ready(function() {
							$('.day_en_tete_liste_show').hide();
							$("#c<?php echo $row_liste_pub['id']; ?>").click(function() {
								var day_en_tete_liste = $('#day_en_tete_liste_1').val();
								
								if($("#c<?php echo $row_liste_pub['id']; ?>").is(':checked')){
									$('.day_en_tete_liste_show').show();
									var en_tete_liste = $('#day_en_tete_liste').val(day_en_tete_liste);
									en_tete_listes();
								}else if($("#c<?php echo $row_liste_pub['id']; ?>").is(":not(:checked)")){
									$('.day_en_tete_liste_show').hide();
									$('#day_en_tete_liste').val('');
								}
							});
							function en_tete_listes(){
								$('#day_en_tete_liste_1').change(function() {
									var day_en_tete_liste = $('#day_en_tete_liste_1').val();
									var en_tete_liste = $('#day_en_tete_liste').val(day_en_tete_liste);
								});
							}
							
						});
					</script>
                    <span class="day_en_tete_liste_show">
                	Pendant
                    <input type="hidden" name="day_en_tete_liste" id="day_en_tete_liste" />
                    
                    <select name="day_en_tete_liste_<?php echo $cnt1; ?>" id="day_en_tete_liste_<?php echo $cnt1; ?>" style="width:150px;">
                    	<?php for($j=1; $j<=15; $j++){?>
                        	<option value="<?php echo $j;?>"><?php echo $j;?> jour <?php if($j>1){?>(s)<?php }?> = <?php echo ($j*$row_liste_pub['prix']);?> &euro;</option>
                        <?php }?>
                    </select>
                    </span>
                <?php }?>
				<?php if($row_liste_pub['description']!=''){?><a href="assets/popup_2/<?php echo $row_liste_pub['id'];?>.html" class="popupwindow" rel="windowCenter">(?)</a><?php }?></td>
                
            </tr>
            <?php } ?>
          

            </table>
        <!--</div>-->
          
		<!--<div style="float:right; width:370px;"><img src="assets/images/img_positionnement.jpg" /></div>-->
        </td>
    </tr>           
	<tr>
    	<td colspan="2">          	
     	<div class="clear"></div>
        <div class="KT_bottombuttons" style="border:none; float:left">
			<?php 
            // Show IF Conditional region1
            if (@$_GET['id'] == "") {
            ?>
            
            <input type="submit" name="KT_Insert1" id="KT_Insert1" value="Valider" />
            <?php 
            // else Conditional region1
            } else { ?>
				<?php if(array_key_exists("dupliquer", $_GET)){?>
                    <input type="submit" name="KT_Insert1" id="KT_Insert1" value="Valider" />
                <?php }else{?>
                    <input type="submit" name="KT_Update1" value="Valider" />
                <?php }?>
            <?php }
            // endif Conditional region1 
            ?>
            <input type="button" name="KT_Cancel1" value="Annuler" onclick="return UNI_navigateCancel(event, 'includes/nxt/back.php')" />
        </div>
		</td>
	</tr>
</table>
</div>
</form>
<!--</div>
</div>-->
</div>
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
mysql_free_result($magasins);

mysql_free_result($categories);

mysql_free_result($coupons);
?>