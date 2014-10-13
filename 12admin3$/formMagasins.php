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
$formValidation->addField("nom_magazin", true, "text", "", "1", "80", "80 caractéres");
$formValidation->addField("region", true, "numeric", "", "", "", "");
$formValidation->addField("ville", true, "numeric", "", "", "", "");
$formValidation->addField("adresse", true, "text", "", "", "", "");
$formValidation->addField("logo", true, "", "", "", "", "");
$formValidation->addField("photo1", true, "", "", "", "", "");
$formValidation->addField("description", true, "text", "", "1", "800", "800 caractéres");
$tNGs->prepareValidation($formValidation);
// End trigger

//start Trigger_FileDelete2 trigger
//remove this line if you want to edit the code by hand 
function Trigger_FileDelete2(&$tNG) {
  $deleteObj = new tNG_FileDelete($tNG);
  $deleteObj->setFolder("../assets/images/magasins/");
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
  $uploadObj->setFolder("../assets/images/magasins/");
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
  $deleteObj->setFolder("../assets/images/magasins/");
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
  $uploadObj->setFolder("../assets/images/magasins/");
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
  $deleteObj->setFolder("../assets/images/magasins/");
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
  $uploadObj->setFolder("../assets/images/magasins/");
  $uploadObj->setMaxSize(1000);
  $uploadObj->setAllowedExtensions("gif, jpg, jpe, jpeg, png");
  $uploadObj->setRename("auto");
  return $uploadObj->Execute();
}
//end Trigger_ImageUpload trigger

//start Trigger_ImageUpload2 trigger
//remove this line if you want to edit the code by hand 
function Trigger_ImageUpload3(&$tNG) {
  $uploadObj = new tNG_ImageUpload($tNG);
  $uploadObj->setFormFieldName("logo");
  $uploadObj->setDbFieldName("logo");
  $uploadObj->setFolder("assets/images/magasins/");
  $uploadObj->setResize("true", 400, 400);
  $uploadObj->setMaxSize(1500);
  $uploadObj->setAllowedExtensions("gif, jpg, jpe, jpeg, png");
  $uploadObj->setRename("auto");
  return $uploadObj->Execute();
}

function Trigger_FileDelete3(&$tNG) {
  $deleteObj = new tNG_FileDelete($tNG);
  $deleteObj->setFolder("assets/images/magasins/");
  $deleteObj->setDbFieldName("logo");
  return $deleteObj->Execute();
}
//end Trigger_FileDelete2 trigger

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
	$sql_pro  = "UPDATE magazins SET approuve='1' WHERE id_magazin='".$id."'";
	$result_pro  = mysql_query($sql_pro ) or die (mysql_error());
	if($result_pro){
		$sql_select1 = "SELECT
							utilisateur.id
							, utilisateur.nom
							, utilisateur.prenom
							, utilisateur.email
							, magazins.nom_magazin
							, magazins.id_magazin
						FROM
							magazins
							INNER JOIN utilisateur 
								ON (magazins.id_user = utilisateur.id)
						 WHERE magazins.id_magazin='".$_GET['id']."' AND utilisateur.email='".$email."'";
		$query_select1 = mysql_query($sql_select1);
		$rs1=mysql_fetch_array($query_select1);
		SendMail_Ownner_Magasin_approve($rs1['email'],$rs1['nom'],$rs1['prenom'],$rs1['nom_magazin']);
		echo'<script>window.location="magasins.php?info=ACTIVATED";</script>';
	}
}

if(isset($_GET['unactive'])){
	$id = $_GET['id'];
	$email = $_GET['email'];
	$sql_pro  = "UPDATE magazins SET approuve='2' WHERE id_magazin='".$id."'";
	$result_pro  = mysql_query($sql_pro ) or die (mysql_error());
	if($result_pro){
		$sql_select1 = "SELECT
							utilisateur.id
							, utilisateur.nom
							, utilisateur.prenom
							, utilisateur.email
							, magazins.nom_magazin
							, magazins.id_magazin
						FROM
							magazins
							INNER JOIN utilisateur 
								ON (magazins.id_user = utilisateur.id)
						 WHERE magazins.id_magazin='".$_GET['id']."' AND utilisateur.email='".$email."'";
		$query_select1 = mysql_query($sql_select1);
		$rs1=mysql_fetch_array($query_select1);
		SendMail_Ownner_Magasin_unapprove($rs1['email'],$rs1['nom'],$rs1['prenom'],$rs1['nom_magazin']);
	}
}

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset1 = "SELECT email, id FROM utilisateur ORDER BY email";
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset2 = "SELECT nom_region, id_region FROM region ORDER BY nom_region";
$Recordset2 = mysql_query($query_Recordset2, $magazinducoin) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset3 = "SELECT cat_name, cat_id FROM category WHERE parent_id = 0 AND type='3' ORDER BY cat_name";
$Recordset3 = mysql_query($query_Recordset3, $magazinducoin) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);


// Make an insert transaction instance
$ins_magazins = new tNG_multipleInsert($conn_magazinducoin);
$tNGs->addTransaction($ins_magazins);
// Register triggers
$ins_magazins->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_magazins->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_magazins->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$ins_magazins->registerTrigger("AFTER", "Trigger_ImageUpload", 97);
$ins_magazins->registerTrigger("AFTER", "Trigger_ImageUpload1", 97);
$ins_magazins->registerTrigger("AFTER", "Trigger_ImageUpload2", 97);
$ins_magazins->registerTrigger("AFTER", "Trigger_ImageUpload3", 97);
// Add columns
$ins_magazins->setTable("magazins");
$ins_magazins->addColumn("id_user", "NUMERIC_TYPE", "POST", "id_user");
$ins_magazins->addColumn("nom_magazin", "STRING_TYPE", "POST", "nom_magazin");
$ins_magazins->addColumn("siren", "STRING_TYPE", "POST", "siren");
$ins_magazins->addColumn("region", "NUMERIC_TYPE", "POST", "region");
$ins_magazins->addColumn("department", "NUMERIC_TYPE", "POST", "department");
$ins_magazins->addColumn("ville", "NUMERIC_TYPE", "POST", "ville");
$ins_magazins->addColumn("adresse", "STRING_TYPE", "POST", "adresse");
$ins_magazins->addColumn("code_postal", "STRING_TYPE", "POST", "code_postal");
$ins_magazins->addColumn("categorie", "NUMERIC_TYPE", "POST", "categorie");
$ins_magazins->addColumn("sous_categorie", "NUMERIC_TYPE", "POST", "sous_categorie");
/*$ins_magazins->addColumn("sous_categorie2", "NUMERIC_TYPE", "POST", "sous_categorie2");*/
$ins_magazins->addColumn("logo", "FILE_TYPE", "FILES", "logo");
$ins_magazins->addColumn("photo1", "FILE_TYPE", "FILES", "photo1");
$ins_magazins->addColumn("photo2", "FILE_TYPE", "FILES", "photo2");
$ins_magazins->addColumn("photo3", "FILE_TYPE", "FILES", "photo3");

$ins_magazins->addColumn("telephone", "STRING_TYPE", "POST", "telephone");
$ins_magazins->addColumn("website", "STRING_TYPE", "POST", "website");
$ins_magazins->addColumn("facebook", "STRING_TYPE", "POST", "facebook");

$ins_magazins->addColumn("day1", "STRING_TYPE", "POST", "day1");
$ins_magazins->addColumn("day2", "STRING_TYPE", "POST", "day2");
$ins_magazins->addColumn("day3", "STRING_TYPE", "POST", "day3");
$ins_magazins->addColumn("day4", "STRING_TYPE", "POST", "day4");
$ins_magazins->addColumn("day5", "STRING_TYPE", "POST", "day5");
$ins_magazins->addColumn("day6", "STRING_TYPE", "POST", "day6");
$ins_magazins->addColumn("day7", "STRING_TYPE", "POST", "day7");

$ins_magazins->addColumn("date1_m", "STRING_TYPE", "POST", "date1_m");
$ins_magazins->addColumn("date2_m", "STRING_TYPE", "POST", "date2_m");
$ins_magazins->addColumn("date3_m", "STRING_TYPE", "POST", "date3_m");
$ins_magazins->addColumn("date4_m", "STRING_TYPE", "POST", "date4_m");
$ins_magazins->addColumn("date5_m", "STRING_TYPE", "POST", "date5_m");
$ins_magazins->addColumn("date6_m", "STRING_TYPE", "POST", "date6_m");
$ins_magazins->addColumn("date7_m", "STRING_TYPE", "POST", "date7_m");

$ins_magazins->addColumn("date1_e", "STRING_TYPE", "POST", "date1_e");
$ins_magazins->addColumn("date2_e", "STRING_TYPE", "POST", "date2_e");
$ins_magazins->addColumn("date3_e", "STRING_TYPE", "POST", "date3_e");
$ins_magazins->addColumn("date4_e", "STRING_TYPE", "POST", "date4_e");
$ins_magazins->addColumn("date5_e", "STRING_TYPE", "POST", "date5_e");
$ins_magazins->addColumn("date6_e", "STRING_TYPE", "POST", "date6_e");
$ins_magazins->addColumn("date7_e", "STRING_TYPE", "POST", "date7_e");
//$ins_magazins->addColumn("heure_ouverture", "STRING_TYPE", "POST", "heure_ouverture");
//$ins_magazins->addColumn("date_mor", "STRING_TYPE", "POST", "date_mor");
//$ins_magazins->addColumn("date_eve", "STRING_TYPE", "POST", "date_eve");
//$ins_magazins->addColumn("jours_ouverture", "STRING_TYPE", "POST", "jours_ouverture");
$ins_magazins->addColumn("description", "STRING_TYPE", "POST", "description");
$ins_magazins->addColumn("latlan", "STRING_TYPE", "POST", "latlan");
$ins_magazins->setPrimaryKey("id_magazin", "NUMERIC_TYPE");
//die($_POST['latlan_1']."hhf");
// Make an update transaction instance
$upd_magazins = new tNG_multipleUpdate($conn_magazinducoin);
$tNGs->addTransaction($upd_magazins);
// Register triggers
$upd_magazins->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_magazins->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_magazins->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$upd_magazins->registerTrigger("AFTER", "Trigger_ImageUpload", 97);
$upd_magazins->registerTrigger("AFTER", "Trigger_ImageUpload1", 97);
$upd_magazins->registerTrigger("AFTER", "Trigger_ImageUpload2", 97);
// Add columns
$upd_magazins->setTable("magazins");
$upd_magazins->addColumn("id_user", "NUMERIC_TYPE", "POST", "id_user");
$upd_magazins->addColumn("nom_magazin", "STRING_TYPE", "POST", "nom_magazin");
$upd_magazins->addColumn("siren", "STRING_TYPE", "POST", "siren");
$upd_magazins->addColumn("region", "NUMERIC_TYPE", "POST", "region");
$upd_magazins->addColumn("department", "NUMERIC_TYPE", "POST", "department");
$upd_magazins->addColumn("ville", "NUMERIC_TYPE", "POST", "ville");
$upd_magazins->addColumn("adresse", "STRING_TYPE", "POST", "adresse");
$upd_magazins->addColumn("code_postal", "STRING_TYPE", "POST", "code_postal");
$upd_magazins->addColumn("categorie", "NUMERIC_TYPE", "POST", "categorie");
$upd_magazins->addColumn("sous_categorie", "NUMERIC_TYPE", "POST", "sous_categorie");
//$upd_magazins->addColumn("sous_categorie2", "NUMERIC_TYPE", "POST", "sous_categorie2");
$upd_magazins->addColumn("logo", "FILE_TYPE", "FILES", "logo");
$upd_magazins->addColumn("photo1", "FILE_TYPE", "FILES", "photo1");
$upd_magazins->addColumn("photo2", "FILE_TYPE", "FILES", "photo2");
$upd_magazins->addColumn("photo3", "FILE_TYPE", "FILES", "photo3");

$upd_magazins->addColumn("telephone", "STRING_TYPE", "POST", "telephone");
$upd_magazins->addColumn("website", "STRING_TYPE", "POST", "website");
$upd_magazins->addColumn("facebook", "STRING_TYPE", "POST", "facebook");

$upd_magazins->addColumn("day1", "STRING_TYPE", "POST", "day1");
$upd_magazins->addColumn("day2", "STRING_TYPE", "POST", "day2");
$upd_magazins->addColumn("day3", "STRING_TYPE", "POST", "day3");
$upd_magazins->addColumn("day4", "STRING_TYPE", "POST", "day4");
$upd_magazins->addColumn("day5", "STRING_TYPE", "POST", "day5");
$upd_magazins->addColumn("day6", "STRING_TYPE", "POST", "day6");
$upd_magazins->addColumn("day7", "STRING_TYPE", "POST", "day7");

$upd_magazins->addColumn("date1_m", "STRING_TYPE", "POST", "date1_m");
$upd_magazins->addColumn("date2_m", "STRING_TYPE", "POST", "date2_m");
$upd_magazins->addColumn("date3_m", "STRING_TYPE", "POST", "date3_m");
$upd_magazins->addColumn("date4_m", "STRING_TYPE", "POST", "date4_m");
$upd_magazins->addColumn("date5_m", "STRING_TYPE", "POST", "date5_m");
$upd_magazins->addColumn("date6_m", "STRING_TYPE", "POST", "date6_m");
$upd_magazins->addColumn("date7_m", "STRING_TYPE", "POST", "date7_m");

$upd_magazins->addColumn("date1_e", "STRING_TYPE", "POST", "date1_e");
$upd_magazins->addColumn("date2_e", "STRING_TYPE", "POST", "date2_e");
$upd_magazins->addColumn("date3_e", "STRING_TYPE", "POST", "date3_e");
$upd_magazins->addColumn("date4_e", "STRING_TYPE", "POST", "date4_e");
$upd_magazins->addColumn("date5_e", "STRING_TYPE", "POST", "date5_e");
$upd_magazins->addColumn("date6_e", "STRING_TYPE", "POST", "date6_e");
$upd_magazins->addColumn("date7_e", "STRING_TYPE", "POST", "date7_e");
//$upd_magazins->addColumn("heure_ouverture", "STRING_TYPE", "POST", "heure_ouverture");
//$upd_magazins->addColumn("date_mor", "STRING_TYPE", "POST", "date_mor");
//$upd_magazins->addColumn("date_eve", "STRING_TYPE", "POST", "date_eve");
//$upd_magazins->addColumn("jours_ouverture", "STRING_TYPE", "POST", "jours_ouverture");
$upd_magazins->addColumn("description", "STRING_TYPE", "POST", "description");
$upd_magazins->addColumn("latlan", "STRING_TYPE", "POST", "latlan");
$upd_magazins->setPrimaryKey("id_magazin", "NUMERIC_TYPE", "GET", "id_magazin");

// Make an instance of the transaction object
$del_magazins = new tNG_multipleDelete($conn_magazinducoin);
$tNGs->addTransaction($del_magazins);
// Register triggers
$del_magazins->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_magazins->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$del_magazins->registerTrigger("AFTER", "Trigger_FileDelete", 98);
$del_magazins->registerTrigger("AFTER", "Trigger_FileDelete1", 98);
$del_magazins->registerTrigger("AFTER", "Trigger_FileDelete2", 98);
$del_magazins->registerTrigger("AFTER", "Trigger_FileDelete3", 98);
// Add columns
$del_magazins->setTable("magazins");
$del_magazins->setPrimaryKey("id_magazin", "NUMERIC_TYPE", "GET", "id_magazin");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsmagazins = $tNGs->getRecordset("magazins");
$row_rsmagazins = mysql_fetch_assoc($rsmagazins);
$totalRows_rsmagazins = mysql_num_rows($rsmagazins);
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
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=&sensor=true">
</script>

<script type="text/javascript">
// Note that using Google Gears requires loading the Javascript
// at http://code.google.com/apis/gears/gears_init.js

var initialLocation;
var geocoder;
var browserSupportFlag =  new Boolean();
var map;
var marker;
var infowindow;

function placeMarker(location) {
  marker.setPosition(location);
  map.setCenter(location);
  infowindow.setPosition(location);
  document.getElementById('latlan_1').value = location;
}

var geocoder;
  var map;
  function initialize() {
    geocoder = new google.maps.Geocoder();
	 <?php if(!empty($row_rsmagazins['latlan'])){ 
	  $latlan = str_replace('(','',$row_rsmagazins['latlan']);
	  $latlan = str_replace(')','',$latlan);
	  $ll = explode(',', $latlan );
	  ?>
		var latlng = new google.maps.LatLng(<?php echo $ll[0]; ?>, <?php echo $ll[1]; ?>);
		var myOptions = {
		  zoom: 15,
		  center: latlng,
		  mapTypeId: google.maps.MapTypeId.ROADMAP
		}
		map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
		marker = new google.maps.Marker({
		  position: latlng, 
		  map: map,
		  title:"<?php echo $row_rsmagazins['nom_magazin']; ?>"
	  	});
		google.maps.event.addListener(map, 'click', function(event) {
			placeMarker(event.latLng);
	    });
		infowindow = new google.maps.InfoWindow({
		 	content: "<strong><?php echo $row_rsmagazins['nom_magazin']; ?></strong><br /><?php echo $row_rsmagazins['adresse']; ?><br /><?php echo getVilleById($row_rsmagazins['ville']); ?> <?php echo getRegionById($row_rsmagazins['region']); ?>",
        	size: new google.maps.Size(50,50),
        	position: latlng
    	});
  		infowindow.open(map);

  	  <?php } else { ?>
	  	var latlng = new google.maps.LatLng(<?php echo $default_lan; ?>, <?php echo $default_lon; ?>);
		var myOptions = {
		  zoom: 15,
		  center: latlng,
		  mapTypeId: google.maps.MapTypeId.ROADMAP
		}
		map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
		google.maps.event.addListener(map, 'click', function(event) {
			placeMarker(event.latLng);
	    });
		codeAddress();
	  <?php } ?>
	
  }

  function codeAddress() {
    var address = "France";
    geocoder.geocode( { 'address': address}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        map.setCenter(results[0].geometry.location);
        marker = new google.maps.Marker({
            map: map,
            position: results[0].geometry.location
        });
		infowindow = new google.maps.InfoWindow({
		 	content: "Veuillez remplir les champs!",
        	size: new google.maps.Size(50,50),
        	position: results[0].geometry.location
    	});
  		infowindow.open(map);
		document.getElementById('latlan_1').value = results[0].geometry.location;
      } else {
        alert("Geocode was not successful for the following reason: " + status);
      }
    });
  }
  
  function codeAddress2(adresse_actuel) {
    var address = adresse_actuel;
    geocoder.geocode( { 'address': address}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        placeMarker(results[0].geometry.location);
		document.getElementById('latlan_1').value = results[0].geometry.location;
      } else {
        alert("Geocode was not successful for the following reason: " + status);
      }
    });
  }
  
  function localiser_adresse(){
  	
	var location_ = document.getElementById('latlan_1').value;
	var nom      = document.getElementById('nom_magazin_1').value;
	var adresse  = document.getElementById('adresse_1').value;
	var region   = document.getElementById('region_1').options[document.getElementById('region_1').selectedIndex].title;
	var ville    = document.getElementById('ville_1').options[document.getElementById('ville_1').selectedIndex].title;

	var adresse_actuel = "France";
	var adresse_info = "";	
	if(nom != ""){
		adresse_info += "<strong>"+nom+"</strong><br />";
	}
	if(adresse != ""){
		adresse_actuel = adresse +" "+ adresse_actuel;
		adresse_info += adresse+"<br />";
	}
	if(ville != ""){
		adresse_actuel = ville +" "+ adresse_actuel;
		adresse_info += ville + " ";
	}
	if(region != ""){
		adresse_actuel = region +" "+ adresse_actuel;
		adresse_info += region;
	}
	infowindow.setContent(adresse_info);
	if(adresse != "" || ville != "" || region != "")
		codeAddress2(adresse_actuel);
  }

</script>
</head>
<body id="sp" onload="<?php 
if (@$_GET['id_magazin'] != "") {
?> 
ajax('../ajax/sous_categorie.php?default=<?php echo $row_rsmagazins['sous_categorie']; ?>&id_parent=<?php echo $row_rsmagazins['categorie']; ?>','#sous_categorie_1');
ajax('../ajax/sous_categorie.php?default=<?php echo $row_rsmagazins['sous_categorie2']; ?>&id_parent=<?php echo $row_rsmagazins['sous_categorie']; ?>','#sous_categorie2_1');
ajax('../ajax/sous_categorie.php?default=<?php echo $row_rsmagazins['sous_categorie3']; ?>&id_parent=<?php echo $row_rsmagazins['sous_categorie2']; ?>','#sous_categorie3_1');
ajax('../ajax/ville.php?default=<?php echo $row_rsmagazins['ville']; ?>&id_departement=<?php echo $row_rsmagazins['department']; ?>&id_region=<?php echo $row_rsmagazins['region']; ?>','#ville_1');
<?php } 
?> initialize()">
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
if (@$_GET['id_magazin'] == "") {
?>
                <?php echo NXT_getResource("Insert_FH"); ?>
                <?php 
// else Conditional region1
} else { ?>
                <?php echo NXT_getResource("Update_FH"); ?>
                <?php } 
// endif Conditional region1
?>
              Magazins </h1>
            <div class="KT_tngform">
              <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" enctype="multipart/form-data">
                <?php $cnt1 = 0; ?>
                <?php do { ?>
                  <?php $cnt1++; ?>
                  <?php 
// Show IF Conditional region1 
if (@$totalRows_rsmagazins > 1) {
?>
                    <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
                    <?php } 
// endif Conditional region1
?>
                  <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                    <tr>
                      <td class="KT_th"><label for="id_user_<?php echo $cnt1; ?>">Utilisateur</label></td>
                      <td><select name="id_user_<?php echo $cnt1; ?>" id="id_user_<?php echo $cnt1; ?>">
                          <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                          <?php 
do {  
?>
                          <option value="<?php echo $row_Recordset1['id']?>"<?php if (!(strcmp($row_Recordset1['id'], $row_rsmagazins['id_user']))) {echo "SELECTED";} ?>>
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
                          <?php echo $tNGs->displayFieldError("magazins", "id_user", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="nom_magazin_<?php echo $cnt1; ?>">Nom de magazin:</label></td>
                      <td><input type="text" name="nom_magazin_<?php echo $cnt1; ?>" id="nom_magazin_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsmagazins['nom_magazin']); ?>" size="32" maxlength="250" onblur="localiser_adresse();"  />
                          <?php echo $tNGs->displayFieldHint("nom_magazin");?> <?php echo $tNGs->displayFieldError("magazins", "nom_magazin", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="siren_<?php echo $cnt1; ?>">Siren:</label></td>
                      <td><input type="text" name="siren_<?php echo $cnt1; ?>" id="siren_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsmagazins['siren']); ?>" size="32" maxlength="100" />
                          <?php echo $tNGs->displayFieldHint("siren");?> <?php echo $tNGs->displayFieldError("magazins", "siren", $cnt1); ?> </td>
                    </tr>
                    
                    
                    
                    <tr>
                      <td class="KT_th"><label for="region_<?php echo $cnt1; ?>">Région:</label></td>
                      <td><select name="region_<?php echo $cnt1; ?>" id="region_<?php echo $cnt1; ?>" onchange="ajax('../ajax/department.php?default=<?php echo $row_rsmagazins['department']; ?>&id_region='+this.value,'#department_<?php echo $cnt1; ?>'); localiser_adresse();">
                          <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                          <?php 
do {  
?>
                          <option value="<?php echo $row_Recordset2['id_region']?>"<?php if (!(strcmp($row_Recordset2['id_region'], $row_rsmagazins['region']))) {echo "SELECTED";} ?>><?php echo $row_Recordset2['nom_region']?></option>
                          <?php
} while ($row_Recordset2 = mysql_fetch_assoc($Recordset2));
  $rows = mysql_num_rows($Recordset2);
  if($rows > 0) {
      mysql_data_seek($Recordset2, 0);
	  $row_Recordset2 = mysql_fetch_assoc($Recordset2);
  }
?>
                        </select>
                          <?php echo $tNGs->displayFieldError("magazins", "region", $cnt1); ?> </td>
                    </tr>
                    
                    <tr>
                        <td  class="KT_th">
                        <label for="department_<?php echo $cnt1; ?>">Department:</label>
                        </td>
                        <td>
                        <select name="department_<?php echo $cnt1; ?>" id="department_<?php echo $cnt1; ?>" onchange="ajax('../ajax/ville.php?default=<?php echo $row_rsmagazins['ville']; ?>&id_departement='+this.value,'#ville_1'); localiser_adresse();">
                            <option value="">Department</option>  
                            
                            <?php 
                            mysql_select_db($database_magazinducoin, $magazinducoin);
                            $query_department = "SELECT * FROM departement WHERE id_departement='".$row_rsmagazins['department']."' ORDER BY nom_departement ASC";
                            $department = mysql_query($query_department, $magazinducoin) or die(mysql_error());
                            $row_department = mysql_fetch_array($department);
                            //$totalRows_regions = mysql_num_rows($regions);
                            if($row_rsmagazins['department']!=''){
                            ?>
                                <option value="<?php echo $row_department['id_departement']?>"<?php if (!(strcmp($row_department['id_departement'], $row_rsmagazins['department']))) {echo "SELECTED";} ?> title="<?php echo ($row_department['nom_departement']); ?>"><?php echo ($row_department['nom_departement']); ?></option>
                            <?php }?>   
                            
                    
                        </select>
                        <?php echo $tNGs->displayFieldError("magazins", "department", $cnt1); ?>
                        </td>
                    </tr>
                    
                    
                    <tr>
                      <td class="KT_th"><label for="ville_<?php echo $cnt1; ?>">Ville:</label></td>
                      <td>
                      <?php 
						mysql_select_db($database_magazinducoin, $magazinducoin);
							$query_ville2 = "SELECT * FROM maps_ville WHERE id_ville='".$row_rsmagazins['ville']."' ORDER BY nom ASC";
							$ville2 = mysql_query($query_ville2, $magazinducoin) or die(mysql_error());
							$row_ville2 = mysql_fetch_array($ville2);
							//$totalRows_regions = mysql_num_rows($regions);
						?>
						<select name="ville_<?php echo $cnt1; ?>" id="ville_<?php echo $cnt1; ?>" onchange="localiser_adresse();">
							<option value=""><?php echo $xml->Region ?></option>  
							<?php if($row_rsmagazins['ville']!=''){?>
								<option value="<?php echo $row_ville2['id_ville']?>"<?php if (!(strcmp($row_ville2['id_ville'], $row_rsmagazins['ville']))) {echo "SELECTED";} ?> title="<?php echo ($row_ville2['nom']); ?>"><?php echo ($row_ville2['nom']); ?> <?php echo ($row_ville2['cp']); ?></option>
							<?php }?> 
                        </select>
                          <?php echo $tNGs->displayFieldHint("ville");?> <?php echo $tNGs->displayFieldError("magazins", "ville", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="adresse_<?php echo $cnt1; ?>">Adresse:</label></td>
                      <td><input type="text" name="adresse_<?php echo $cnt1; ?>" id="adresse_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsmagazins['adresse']); ?>" size="32" onblur="localiser_adresse();" />
                          <?php echo $tNGs->displayFieldHint("adresse");?> <?php echo $tNGs->displayFieldError("magazins", "adresse", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="telephone_<?php echo $cnt1; ?>">Telephone:</label></td>
                      <td><input type="text" name="telephone_<?php echo $cnt1; ?>" id="telephone_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsmagazins['telephone']); ?>"  />
                          <?php echo $tNGs->displayFieldHint("telephone");?> <?php echo $tNGs->displayFieldError("magazins", "telephone", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="code_postal_<?php echo $cnt1; ?>">Code postal:</label></td>
                      <td><input type="text" name="code_postal_<?php echo $cnt1; ?>" id="code_postal_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsmagazins['code_postal']); ?>" size="5" maxlength="5" />
                          <?php echo $tNGs->displayFieldHint("code_postal");?> <?php echo $tNGs->displayFieldError("magazins", "code_postal", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="categorie_<?php echo $cnt1; ?>">Categorie:</label></td>
                      <td><select name="categorie_<?php echo $cnt1; ?>" id="categorie_<?php echo $cnt1; ?>" onchange="ajax('../ajax/sous_categorie.php?default=<?php echo $row_rsmagazins['sous_categorie']; ?>&id_parent='+this.value,'#sous_categorie_<?php echo $cnt1; ?>');">
                          <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                          <?php 
						do {  
						?>
                          <option value="<?php echo $row_Recordset3['cat_id']?>"<?php if (!(strcmp($row_Recordset3['cat_id'], $row_rsmagazins['categorie']))) {echo "SELECTED";} ?>><?php echo ($row_Recordset3['cat_name']); ?></option>
                          <?php
						} while ($row_Recordset3 = mysql_fetch_assoc($Recordset3));
						  $rows = mysql_num_rows($Recordset3);
						  if($rows > 0) {
							  mysql_data_seek($Recordset3, 0);
							  $row_Recordset3 = mysql_fetch_assoc($Recordset3);
						  }
						?>
                        </select>
                          <?php echo $tNGs->displayFieldError("magazins", "categorie", $cnt1); ?> </td>
                    </tr>
                   <tr>
                      <td class="KT_th"><label for="sous_categorie_<?php echo $cnt1; ?>">Sous categorie:</label></td>
                      <td> <select name="sous_categorie_<?php echo $cnt1; ?>" id="sous_categorie_<?php echo $cnt1; ?>" onchange="ajax('../ajax/sous_categorie.php?default=<?php echo $row_rsmagazins['sous_categorie2']; ?>&id_parent='+this.value, '#sous_categorie2_<?php echo $cnt1; ?>');">
                          <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                        </select>
                          <?php echo $tNGs->displayFieldHint("sous_categorie");?> <?php echo $tNGs->displayFieldError("magazins", "sous_categorie", $cnt1); ?> </td>
                    </tr>
                    <?php /*?> <tr>
                      <td class="KT_th"><label for="sous_categorie2_<?php echo $cnt1; ?>">Sous categorie 2:</label></td>
                      <td> <select name="sous_categorie2_<?php echo $cnt1; ?>" id="sous_categorie2_<?php echo $cnt1; ?>" onchange="ajax('../ajax/sous_categorie.php?default=<?php echo $row_rsmagazins['sous_categorie3']; ?>&id_parent='+this.value, '#sous_categorie3_<?php echo $cnt1; ?>');">
                          <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                        </select>
                          <?php echo $tNGs->displayFieldHint("sous_categorie2");?> <?php echo $tNGs->displayFieldError("magazins", "sous_categorie2", $cnt1); ?> </td>
                    </tr><?php */?>
                    <tr>
                      <td class="KT_th"><label for="website_<?php echo $cnt1; ?>">Website:</label></td>
                      <td><input type="text" name="website_<?php echo $cnt1; ?>" id="website_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsmagazins['website']); ?>"  />
                          <?php echo $tNGs->displayFieldHint("website");?> <?php echo $tNGs->displayFieldError("magazins", "website", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="facebook_<?php echo $cnt1; ?>">Facebook:</label></td>
                      <td><input type="text" name="facebook_<?php echo $cnt1; ?>" id="facebook_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsmagazins['facebook']); ?>"  />
                          <?php echo $tNGs->displayFieldHint("facebook");?> <?php echo $tNGs->displayFieldError("magazins", "facebook", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="logo_<?php echo $cnt1; ?>"><?php echo $xml->Logo_de_magasin ?>:</label></td>
                      <td><input type="file" name="logo_<?php echo $cnt1; ?>" id="logo_<?php echo $cnt1; ?>" size="32" />
                        <?php if($row_rsmagazins['logo']) { ?>
                      <div style="float:left" id="imgContiner0">
                      	<img src="../assets/images/magasins/<?php echo KT_escapeAttribute($row_rsmagazins['logo']); ?>" width="60" />&nbsp;&nbsp; <a href="javascript:ajax('ajax/supprimer_photo.php?t=magazins&c=logo&id=<?php echo $row_rsmagazins['id_magazin']; ?>&f=<?php echo KT_escapeAttribute($row_rsmagazins['logo']); ?>','#imgContiner0');" style="color:#333333">Supprimer Logo</a>
                      </div> 
					<?php } ?>
                          <?php echo $tNGs->displayFieldError("magazins", "logo", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="photo1_<?php echo $cnt1; ?>">Photo1:</label></td>
                      <td><input type="file" name="photo1_<?php echo $cnt1; ?>" id="photo1_<?php echo $cnt1; ?>" size="32" />
                        <?php if($row_rsmagazins['photo1']) { ?>
                      <div style="float:left" id="imgContiner1">
                      	<img src="../assets/images/magasins/<?php echo KT_escapeAttribute($row_rsmagazins['photo1']); ?>" width="60" />&nbsp;&nbsp; <a href="javascript:ajax('../ajax/supprimer_photo.php?t=magazins&c=photo1&id=<?php echo $_GET['id_magazin']; ?>&f=<?php echo KT_escapeAttribute($row_rsmagazins['photo1']); ?>','#imgContiner1');" style="color:#333333">Supprimer photo</a>
                      </div> 
					<?php } ?>
                          <?php echo $tNGs->displayFieldError("magazins", "photo1", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="photo2_<?php echo $cnt1; ?>">Photo2:</label></td>
                      <td><input type="file" name="photo2_<?php echo $cnt1; ?>" id="photo2_<?php echo $cnt1; ?>" size="32" />
                         <?php if($row_rsmagazins['photo2']) { ?>
                      <div style="float:left" id="imgContiner2">
                      	<img src="../assets/images/magasins/<?php echo KT_escapeAttribute($row_rsmagazins['photo2']); ?>" width="60" />&nbsp;&nbsp; <a href="javascript:ajax('../ajax/supprimer_photo.php?t=magazins&c=photo2&id=<?php echo $_GET['id_magazin']; ?>&f=<?php echo KT_escapeAttribute($row_rsmagazins['photo2']); ?>','#imgContiner2');" style="color:#333333">Supprimer photo</a>
                      </div> 
					<?php } ?>
                          <?php echo $tNGs->displayFieldError("magazins", "photo2", $cnt1); ?> </td>
                    </tr>
                    <tr>
                      <td class="KT_th"><label for="photo3_<?php echo $cnt1; ?>">Photo3:</label></td>
                      <td><input type="file" name="photo3_<?php echo $cnt1; ?>" id="photo3_<?php echo $cnt1; ?>" size="32" />
                       <?php if($row_rsmagazins['photo3']) { ?>
                      <div style="float:left" id="imgContiner3">
                      	<img src="../assets/images/magasins/<?php echo KT_escapeAttribute($row_rsmagazins['photo3']); ?>" width="60" />&nbsp;&nbsp; <a href="javascript:ajax('../ajax/supprimer_photo.php?t=magazins&c=photo3&id=<?php echo $_GET['id_magazin']; ?>&f=<?php echo KT_escapeAttribute($row_rsmagazins['photo3']); ?>','#imgContiner3');" style="color:#333333">Supprimer photo</a>
                      </div> 
					<?php } ?>
                          <?php echo $tNGs->displayFieldError("magazins", "photo3", $cnt1); ?> </td>
                    </tr>
                    <?php 
						$check_1 = '';
						$check_2 = '';
						$check_3 = '';
						$check_4 = '';
						$check_5 = '';
						$check_6 = '';
						$check_7 = '';
						$day1_m = '';
						$day1_e = '';
						$day2_m = '';
						$day2_e = '';
						$day3_m = '';
						$day3_e = '';
						$day4_m = '';
						$day4_e = '';
						$day5_m = '';
						$day5_e = '';
						$day6_m = '';
						$day6_e = '';
						$day7_m = '';
						$day7_e = '';
					if($_GET['id_magazin']){
					
						$check_1 = $row_rsmagazins['day1'];
						$check_2 = $row_rsmagazins['day2'];
						$check_3 = $row_rsmagazins['day3'];
						$check_4 = $row_rsmagazins['day4'];
						$check_5 = $row_rsmagazins['day5'];
						$check_6 = $row_rsmagazins['day6'];
						$check_7 = $row_rsmagazins['day7'];
					
						$day1_m = $row_rsmagazins['date1_m'];
						$day1_e = $row_rsmagazins['date1_e'];
						$day2_m = $row_rsmagazins['date2_m'];
						$day2_e = $row_rsmagazins['date2_e'];
						$day3_m = $row_rsmagazins['date3_m'];
						$day3_e = $row_rsmagazins['date3_e'];
						$day4_m = $row_rsmagazins['date4_m'];
						$day4_e = $row_rsmagazins['date4_e'];
						$day5_m = $row_rsmagazins['date5_m'];
						$day5_e = $row_rsmagazins['date5_e'];
						$day6_m = $row_rsmagazins['date6_m'];
						$day6_e = $row_rsmagazins['date6_e'];
						$day7_m = $row_rsmagazins['date7_m'];
						$day7_e = $row_rsmagazins['date7_e'];
					}
					?>
                    <style>
                    	.day td{
							border:none;
							color:#FFF;
						}
                    </style>
                    <tr>
                        <td colspan="2" style="background:#9D216E;">
                            <table cellpadding="0" cellspacing="0" border="0" class="day" width="100%">
                                <tr>
                                    <td align="left"><b>Ouvert</b></td>
                                    <td colspan="3" align="left"><b>Ouvert le midi</b></td>
                                </tr>
                                <tr valign="top">
                                    <td width="15%">
                                        <input type="checkbox" id="day1" name="day1" <?php if($check_1=='1') echo 'checked="checked" value="1"'; else echo'value="0"';?> /> Lundi
                                    </td>
                                    <td width="4%" class="show_date_1">
                                        <input type="checkbox" id="day1_check" name="day1_check" <?php if($day1_e=='') echo 'checked="checked"';?> />
                                    </td>
                                    <td class="show_date_1">
                                        <?php $heures_day1 = explode('-',$day1_m);
                                           $heures1_day1 = explode('h',$heures_day1[0]);
                                           $heures2_day1 = explode('h',$heures_day1[1]);
                                        ?>
                                        
                                        
                                        <select id="heures1_day1" style="width:50px; margin:0" onchange="getHeures_day1();">
                                            <?php for ($i=0; $i<24;$i++)
                                                 echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures1_day1[0] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select> : 
                                        <select id="minutes1_day1" style="width:50px;" onchange="getHeures_day1();">
                                            <?php for ($i=0; $i<60;$i+=15)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures1_day1[1] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select>
                                        <?php echo $xml->A; ?> 
                                        <select id="heures2_day1" style="width:50px; margin:0" onchange="getHeures_day1();">
                                            <?php for ($i=0; $i<24;$i++)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures2_day1[0] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select> : 
                                        <select id="minutes2_day1" style="width:50px;" onchange="getHeures_day1();">
                                            <?php for ($i=0; $i<60;$i+=15)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures2_day1[1] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select>
                                        
                                        <input name="date1_m" type="hidden" id="date1_m" value="<?php echo KT_escapeAttribute($day1_m); ?>" size="50" />
                                        <?php echo $tNGs->displayFieldHint("date1_m");?> <?php echo $tNGs->displayFieldError("magazins", "date1_m", $cnt1); ?>
                                        <script>
                                        function getHeures_day1(){
                                            var heure = ($('#heures1_day1').val())+"h"+($('#minutes1_day1').val())+"-"+($('#heures2_day1').val())+"h"+($('#minutes2_day1').val());
                                            $('#date1_m').val(heure);
                                        }
                                        </script>
                                    </td>
                                    <td class="day1_2 show_date_1">
                                        <?php $heures_day1_2 = explode('-',$day1_e);
                                           $heures1_day1_2 = explode('h',$heures_day1_2[0]);
                                           $heures2_day1_2 = explode('h',$heures_day1_2[1]);
                                        ?>
                                        
                                        
                                        <select id="heures1_day1_2" style="width:50px; margin:0" onchange="getHeures_day1_2();">
                                            <?php for ($i=0; $i<24;$i++)
                                                 echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures1_day1_2[0] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select> : 
                                        <select id="minutes1_day1_2" style="width:50px;" onchange="getHeures_day1_2();">
                                            <?php for ($i=0; $i<60;$i+=15)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures1_day1_2[1] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select>
                                        <?php echo $xml->A; ?> 
                                        <select id="heures2_day1_2" style="width:50px; margin:0" onchange="getHeures_day1_2();">
                                            <?php for ($i=0; $i<24;$i++)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures2_day1_2[0] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select> : 
                                        <select id="minutes2_day1_2" style="width:50px;" onchange="getHeures_day1_2();">
                                            <?php for ($i=0; $i<60;$i+=15)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures2_day1_2[1] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select>
                                        
                                        <input name="date1_e" type="hidden" id="date1_e" value="<?php echo KT_escapeAttribute($day1_e); ?>" size="50" />
                                        <?php echo $tNGs->displayFieldHint("date1_e");?> <?php echo $tNGs->displayFieldError("magazins", "date1_e", $cnt1); ?>
                                        <script>
                                        function getHeures_day1_2(){
                                            var heure = ($('#heures1_day1_2').val())+"h"+($('#minutes1_day1_2').val())+"-"+($('#heures2_day1_2').val())+"h"+($('#minutes2_day1_2').val());
                                            $('#date1_e').val(heure);
                                        }
                                        </script>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td>
                                        <input type="checkbox" id="day2" name="day2" <?php if($check_2=='1') echo 'checked="checked" value="1"'; else echo'value="0"';?> /> Mardi
                                    </td>
                                    <td class="show_date_2">
                                        <input type="checkbox" id="day2_check" name="day2_check" <?php if($day2_e=='') echo 'checked="checked"';?> />
                                    </td>
                                    <td class="show_date_2">
                                        <?php $heures_day2 = explode('-',$day2_m);
                                           $heures1_day2 = explode('h',$heures_day2[0]);
                                           $heures2_day2 = explode('h',$heures_day2[1]);
                                        ?>
                                        
                                        
                                        <select id="heures1_day2" style="width:50px; margin:0" onchange="getHeures_day2();">
                                            <?php for ($i=0; $i<24;$i++)
                                                 echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures1_day2[0] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select> : 
                                        <select id="minutes1_day2" style="width:50px;" onchange="getHeures_day2();">
                                            <?php for ($i=0; $i<60;$i+=15)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures1_day2[1] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select>
                                        <?php echo $xml->A; ?> 
                                        <select id="heures2_day2" style="width:50px; margin:0" onchange="getHeures_day2();">
                                            <?php for ($i=0; $i<24;$i++)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures2_day2[0] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select> : 
                                        <select id="minutes2_day2" style="width:50px;" onchange="getHeures_day2();">
                                            <?php for ($i=0; $i<60;$i+=15)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures2_day2[1] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select>
                                        
                                        <input name="date2_m" type="hidden" id="date2_m" value="<?php echo KT_escapeAttribute($day2_m); ?>" size="50" />
                                        <?php echo $tNGs->displayFieldHint("date2_m");?> <?php echo $tNGs->displayFieldError("magazins", "date2_m", $cnt1); ?>
                                        <script>
                                        function getHeures_day2(){
                                            var heure = ($('#heures1_day2').val())+"h"+($('#minutes1_day2').val())+"-"+($('#heures2_day2').val())+"h"+($('#minutes2_day2').val());
                                            $('#date2_m').val(heure);
                                        }
                                        </script>
                                    </td>
                                    <td class="day2_2 show_date_2" >
                                        <?php $heures_day2_2 = explode('-',$day2_e);
                                           $heures1_day2_2 = explode('h',$heures_day2_2[0]);
                                           $heures2_day2_2 = explode('h',$heures_day2_2[1]);
                                        ?>
                                        
                                        
                                        <select id="heures1_day2_2" style="width:50px; margin:0" onchange="getHeures_day2_2();">
                                            <?php for ($i=0; $i<24;$i++)
                                                 echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures1_day2_2[0] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select> : 
                                        <select id="minutes1_day2_2" style="width:50px;" onchange="getHeures_day2_2();">
                                            <?php for ($i=0; $i<60;$i+=15)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures1_day2_2[1] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select>
                                        <?php echo $xml->A; ?> 
                                        <select id="heures2_day2_2" style="width:50px; margin:0" onchange="getHeures_day2_2();">
                                            <?php for ($i=0; $i<24;$i++)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures2_day2_2[0] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select> : 
                                        <select id="minutes2_day2_2" style="width:50px;" onchange="getHeures_day2_2();">
                                            <?php for ($i=0; $i<60;$i+=15)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures2_day2_2[1] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select>
                                        
                                        <input name="date2_e" type="hidden" id="date2_e" value="<?php echo KT_escapeAttribute($day2_e); ?>" size="50" />
                                        <?php echo $tNGs->displayFieldHint("date2_e");?> <?php echo $tNGs->displayFieldError("magazins", "date2_e", $cnt1); ?>
                                        <script>
                                        function getHeures_day2_2(){
                                            var heure = ($('#heures1_day2_2').val())+"h"+($('#minutes1_day2_2').val())+"-"+($('#heures2_day2_2').val())+"h"+($('#minutes2_day2_2').val());
                                            $('#date2_e').val(heure);
                                        }
                                        </script>
                                    </td>
                    
                                </tr>
                                
                                <tr>
                                    <td>
                                        <input type="checkbox" id="day3" name="day3" <?php if($check_3=='1') echo 'checked="checked" value="1"'; else echo'value="0"';?> /> Mercredi
                                    </td>
                                    <td class="show_date_3">
                                        <input type="checkbox" id="day3_check" name="day3_check" <?php if($day3_e=='') echo 'checked="checked" value="1"'; else echo'value="0"'?>/>
                                    </td>
                                    <td class="show_date_3">
                                        <?php $heures_day3 = explode('-',$day3_m);
                                           $heures1_day3 = explode('h',$heures_day3[0]);
                                           $heures2_day3 = explode('h',$heures_day3[1]);
                                        ?>
                                        
                                        
                                        <select id="heures1_day3" style="width:50px; margin:0" onchange="getHeures_day3();">
                                            <?php for ($i=0; $i<24;$i++)
                                                 echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures1_day3[0] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select> : 
                                        <select id="minutes1_day3" style="width:50px;" onchange="getHeures_day3();">
                                            <?php for ($i=0; $i<60;$i+=15)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures1_day3[1] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select>
                                        <?php echo $xml->A; ?> 
                                        <select id="heures2_day3" style="width:50px; margin:0" onchange="getHeures_day3();">
                                            <?php for ($i=0; $i<24;$i++)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures2_day3[0] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select> : 
                                        <select id="minutes2_day3" style="width:50px;" onchange="getHeures_day3();">
                                            <?php for ($i=0; $i<60;$i+=15)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures2_day3[1] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select>
                                        
                                        <input name="date3_m" type="hidden" id="date3_m" value="<?php echo KT_escapeAttribute($day3_m); ?>" size="50" />
                                        <?php echo $tNGs->displayFieldHint("date3_m");?> <?php echo $tNGs->displayFieldError("magazins", "date3_m", $cnt1); ?>
                                        <script>
                                        function getHeures_day3(){
                                            var heure = ($('#heures1_day3').val())+"h"+($('#minutes1_day3').val())+"-"+($('#heures2_day3').val())+"h"+($('#minutes2_day3').val());
                                            $('#date3_m').val(heure);
                                        }
                                        </script>
                                    </td>
                                    <td class="day3_2 show_date_3">
                                        <?php $heures_day3_2 = explode('-',$day3_e);
                                           $heures1_day3_2 = explode('h',$heures_day3_2[0]);
                                           $heures2_day3_2 = explode('h',$heures_day3_2[1]);
                                        ?>
                                        
                                        
                                        <select id="heures1_day3_2" style="width:50px; margin:0" onchange="getHeures_day3_2();">
                                            <?php for ($i=0; $i<24;$i++)
                                                 echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures1_day3_2[0] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select> : 
                                        <select id="minutes1_day3_2" style="width:50px;" onchange="getHeures_day3_2();">
                                            <?php for ($i=0; $i<60;$i+=15)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures1_day3_2[1] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select>
                                        <?php echo $xml->A; ?> 
                                        <select id="heures2_day3_2" style="width:50px; margin:0" onchange="getHeures_day3_2();">
                                            <?php for ($i=0; $i<24;$i++)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures2_day3_2[0] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select> : 
                                        <select id="minutes2_day3_2" style="width:50px;" onchange="getHeures_day3_2();">
                                            <?php for ($i=0; $i<60;$i+=15)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures2_day3_2[1] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select>
                                        
                                        <input name="date3_e" type="hidden" id="date3_e" value="<?php echo KT_escapeAttribute($day3_e); ?>" size="50" />
                                        <?php echo $tNGs->displayFieldHint("day3_e");?> <?php echo $tNGs->displayFieldError("magazins", "day3_e", $cnt1); ?>
                                        <script>
                                        function getHeures_day3_2(){
                                            var heure = ($('#heures1_day3_2').val())+"h"+($('#minutes1_day3_2').val())+"-"+($('#heures2_day3_2').val())+"h"+($('#minutes2_day3_2').val());
                                            $('#date3_e').val(heure);
                                        }
                                        </script>
                                    </td>
                    
                                </tr>
                                
                                <tr>
                                    <td>
                                        <input type="checkbox" id="day4" name="day4" <?php if($check_4=='1') echo 'checked="checked" value="1"'; else echo'value="0"';?> /> Jeudi
                                    </td>
                                    <td class="show_date_4">
                                        <input type="checkbox" id="day4_check" name="day4_check" <?php if($day4_e=='') echo 'checked="checked"';?>/>
                                    </td>
                                    <td class="show_date_4">
                                        <?php $heures_day4 = explode('-',$day4_m);
                                           $heures1_day4 = explode('h',$heures_day4[0]);
                                           $heures2_day4 = explode('h',$heures_day4[1]);
                                        ?>
                                        
                                        
                                        <select id="heures1_day4" style="width:50px; margin:0" onchange="getHeures_day4();">
                                            <?php for ($i=0; $i<24;$i++)
                                                 echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures1_day4[0] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select> : 
                                        <select id="minutes1_day4" style="width:50px;" onchange="getHeures_day4();">
                                            <?php for ($i=0; $i<60;$i+=15)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures1_day4[1] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select>
                                        <?php echo $xml->A; ?> 
                                        <select id="heures2_day4" style="width:50px; margin:0" onchange="getHeures_day4();">
                                            <?php for ($i=0; $i<24;$i++)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures2_day4[0] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select> : 
                                        <select id="minutes2_day4" style="width:50px;" onchange="getHeures_day4();">
                                            <?php for ($i=0; $i<60;$i+=15)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures2_day4[1] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select>
                                        
                                        <input name="date4_m" type="hidden" id="date4_m" value="<?php echo KT_escapeAttribute($day4_m); ?>" size="50" />
                                        <?php echo $tNGs->displayFieldHint("date4_m");?> <?php echo $tNGs->displayFieldError("magazins", "date4_m", $cnt1); ?>
                                        <script>
                                        function getHeures_day4(){
                                            var heure = ($('#heures1_day4').val())+"h"+($('#minutes1_day4').val())+"-"+($('#heures2_day4').val())+"h"+($('#minutes2_day4').val());
                                            $('#date4_m').val(heure);
                                        }
                                        </script>
                                    </td>
                                    <td class="day4_2 show_date_4">
                                        <?php $heures_day4_2 = explode('-',$day4_e);
                                           $heures1_day4_2 = explode('h',$heures_day4_2[0]);
                                           $heures2_day4_2 = explode('h',$heures_day4_2[1]);
                                        ?>
                                        
                                        
                                        <select id="heures1_day4_2" style="width:50px; margin:0" onchange="getHeures_day4_2();">
                                            <?php for ($i=0; $i<24;$i++)
                                                 echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures1_day4_2[0] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select> : 
                                        <select id="minutes1_day4_2" style="width:50px;" onchange="getHeures_day4_2();">
                                            <?php for ($i=0; $i<60;$i+=15)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures1_day4_2[1] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select>
                                        <?php echo $xml->A; ?> 
                                        <select id="heures2_day4_2" style="width:50px; margin:0" onchange="getHeures_day4_2();">
                                            <?php for ($i=0; $i<24;$i++)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures2_day4_2[0] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select> : 
                                        <select id="minutes2_day4_2" style="width:50px;" onchange="getHeures_day4_2();">
                                            <?php for ($i=0; $i<60;$i+=15)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures2_day4_2[1] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select>
                                        
                                        <input name="date4_e" type="hidden" id="date4_e" value="<?php echo KT_escapeAttribute($day4_e); ?>" size="50" />
                                        <?php echo $tNGs->displayFieldHint("date4_e");?> <?php echo $tNGs->displayFieldError("magazins", "date4_e", $cnt1); ?>
                                        <script>
                                        function getHeures_day4_2(){
                                            var heure = ($('#heures1_day4_2').val())+"h"+($('#minutes1_day4_2').val())+"-"+($('#heures2_day4_2').val())+"h"+($('#minutes2_day4_2').val());
                                            $('#date4_e').val(heure);
                                        }
                                        </script>
                                    </td>
                    
                                </tr>
                                
                                <tr>
                                    <td>
                                        <input type="checkbox" id="day5" name="day5" <?php if($check_5=='1') echo 'checked="checked" value="1"'; else echo'value="0"';?> /> Vendredi
                                    </td>
                                    <td class="show_date_5">
                                        <input type="checkbox" id="day5_check" name="day5_check" <?php if($day5_e=='') echo 'checked="checked"';?>/>
                                    </td>
                                    <td class="show_date_5">
                                        <?php $heures_day5 = explode('-',$day5_m);
                                           $heures1_day5 = explode('h',$heures_day5[0]);
                                           $heures2_day5 = explode('h',$heures_day5[1]);
                                        ?>
                                        
                                        
                                        <select id="heures1_day5" style="width:50px; margin:0" onchange="getHeures_day5();">
                                            <?php for ($i=0; $i<24;$i++)
                                                 echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures1_day5[0] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select> : 
                                        <select id="minutes1_day5" style="width:50px;" onchange="getHeures_day5();">
                                            <?php for ($i=0; $i<60;$i+=15)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures1_day5[1] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select>
                                        <?php echo $xml->A; ?> 
                                        <select id="heures2_day5" style="width:50px; margin:0" onchange="getHeures_day5();">
                                            <?php for ($i=0; $i<24;$i++)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures2_day5[0] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select> : 
                                        <select id="minutes2_day5" style="width:50px;" onchange="getHeures_day5();">
                                            <?php for ($i=0; $i<60;$i+=15)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures2_day5[1] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select>
                                        
                                        <input name="date5_m" type="hidden" id="date5_m" value="<?php echo KT_escapeAttribute($day5_m); ?>" size="50" />
                                        <?php echo $tNGs->displayFieldHint("date5_m");?> <?php echo $tNGs->displayFieldError("magazins", "date5_m", $cnt1); ?>
                                        <script>
                                        function getHeures_day5(){
                                            var heure = ($('#heures1_day5').val())+"h"+($('#minutes1_day5').val())+"-"+($('#heures2_day5').val())+"h"+($('#minutes2_day5').val());
                                            $('#date5_m').val(heure);
                                        }
                                        </script>
                                    </td>
                                    <td class="day5_2 show_date_5">
                                        <?php $heures_day5_2 = explode('-',$day5_e);
                                           $heures1_day5_2 = explode('h',$heures_day5_2[0]);
                                           $heures2_day5_2 = explode('h',$heures_day5_2[1]);
                                        ?>
                                        
                                        
                                        <select id="heures1_day5_2" style="width:50px; margin:0" onchange="getHeures_day5_2();">
                                            <?php for ($i=0; $i<24;$i++)
                                                 echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures1_day5_2[0] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select> : 
                                        <select id="minutes1_day5_2" style="width:50px;" onchange="getHeures_day5_2();">
                                            <?php for ($i=0; $i<60;$i+=15)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures1_day5_2[1] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select>
                                        <?php echo $xml->A; ?> 
                                        <select id="heures2_day5_2" style="width:50px; margin:0" onchange="getHeures_day5_2();">
                                            <?php for ($i=0; $i<24;$i++)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures2_day5_2[0] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select> : 
                                        <select id="minutes2_day5_2" style="width:50px;" onchange="getHeures_day5_2();">
                                            <?php for ($i=0; $i<60;$i+=15)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures2_day5_2[1] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select>
                                        
                                        <input name="date5_e" type="hidden" id="date5_e" value="<?php echo KT_escapeAttribute($day5_e); ?>" size="50" />
                                        <?php echo $tNGs->displayFieldHint("date5_e");?> <?php echo $tNGs->displayFieldError("magazins", "date5_e", $cnt1); ?>
                                        <script>
                                        function getHeures_day5_2(){
                                            var heure = ($('#heures1_day5_2').val())+"h"+($('#minutes1_day5_2').val())+"-"+($('#heures2_day5_2').val())+"h"+($('#minutes2_day5_2').val());
                                            $('#date5_e').val(heure);
                                        }
                                        </script>
                                    </td>
                    
                                </tr>
                                
                                <tr>
                                    <td>
                                        <input type="checkbox" id="day6" name="day6" <?php if($check_6=='1') echo 'checked="checked" value="1"'; else echo'value="0"';?> /> Samedi
                                    </td>
                                    <td class="show_date_6">
                                        <input type="checkbox" id="day6_check" name="day6_check" <?php if($day6_e=='') echo 'checked="checked"';?>/>
                                    </td>
                                    <td class="show_date_6">
                                        <?php $heures_day6 = explode('-',$day6_m);
                                           $heures1_day6 = explode('h',$heures_day6[0]);
                                           $heures2_day6 = explode('h',$heures_day6[1]);
                                        ?>
                                        
                                        
                                        <select id="heures1_day6" style="width:50px; margin:0" onchange="getHeures_day6();">
                                            <?php for ($i=0; $i<24;$i++)
                                                 echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures1_day6[0] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select> : 
                                        <select id="minutes1_day6" style="width:50px;" onchange="getHeures_day6();">
                                            <?php for ($i=0; $i<60;$i+=15)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures1_day6[1] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select>
                                        <?php echo $xml->A; ?> 
                                        <select id="heures2_day6" style="width:50px; margin:0" onchange="getHeures_day6();">
                                            <?php for ($i=0; $i<24;$i++)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures2_day6[0] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select> : 
                                        <select id="minutes2_day6" style="width:50px;" onchange="getHeures_day6();">
                                            <?php for ($i=0; $i<60;$i+=15)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures2_day6[1] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select>
                                        
                                        <input name="date6_m" type="hidden" id="date6_m" value="<?php echo KT_escapeAttribute($day6_m); ?>" size="50" />
                                        <?php echo $tNGs->displayFieldHint("date6_m");?> <?php echo $tNGs->displayFieldError("magazins", "date6_m", $cnt1); ?>
                                        <script>
                                        function getHeures_day6(){
                                            var heure = ($('#heures1_day6').val())+"h"+($('#minutes1_day6').val())+"-"+($('#heures2_day6').val())+"h"+($('#minutes2_day6').val());
                                            $('#date6_m').val(heure);
                                        }
                                        </script>
                                    </td>
                                    <td class="day6_2 show_date_6">
                                        <?php $heures_day6_2 = explode('-',$day6_e);
                                           $heures1_day6_2 = explode('h',$heures_day6_2[0]);
                                           $heures2_day6_2 = explode('h',$heures_day6_2[1]);
                                        ?>
                                        
                                        
                                        <select id="heures1_day6_2" style="width:50px; margin:0" onchange="getHeures_day6_2();">
                                            <?php for ($i=0; $i<24;$i++)
                                                 echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures1_day6_2[0] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select> : 
                                        <select id="minutes1_day6_2" style="width:50px;" onchange="getHeures_day6_2();">
                                            <?php for ($i=0; $i<60;$i+=15)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures1_day6_2[1] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select>
                                        <?php echo $xml->A; ?> 
                                        <select id="heures2_day6_2" style="width:50px; margin:0" onchange="getHeures_day6_2();">
                                            <?php for ($i=0; $i<24;$i++)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures2_day6_2[0] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select> : 
                                        <select id="minutes2_day6_2" style="width:50px;" onchange="getHeures_day6_2();">
                                            <?php for ($i=0; $i<60;$i+=15)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures2_day6_2[1] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select>
                                        
                                        <input name="date6_e" type="hidden" id="date6_e" value="<?php echo KT_escapeAttribute($day6_e); ?>" size="50" />
                                        <?php echo $tNGs->displayFieldHint("date6_e");?> <?php echo $tNGs->displayFieldError("magazins", "date6_e", $cnt1); ?>
                                        <script>
                                        function getHeures_day6_2(){
                                            var heure = ($('#heures1_day6_2').val())+"h"+($('#minutes1_day6_2').val())+"-"+($('#heures2_day6_2').val())+"h"+($('#minutes2_day6_2').val());
                                            $('#date6_e').val(heure);
                                        }
                                        </script>
                                    </td>
                    
                                </tr>
                                
                                <tr>
                                    <td>
                                        <input type="checkbox" id="day7" name="day7" <?php if($check_7=='1') echo 'checked="checked" value="1"'; else echo'value="0"';?> /> Dimanche
                                    </td>
                                    <td class="show_date_7">
                                        <input type="checkbox" id="day7_check" name="day7_check" <?php if($day7_e=='') echo 'checked="checked"';?>/>
                                    </td>
                                    <td class="show_date_7">
                                        <?php $heures_day7 = explode('-',$day7_m);
                                           $heures1_day7 = explode('h',$heures_day7[0]);
                                           $heures2_day7 = explode('h',$heures_day7[1]);
                                        ?>
                                        
                                        
                                        <select id="heures1_day7" style="width:50px; margin:0" onchange="getHeures_day7();">
                                            <?php for ($i=0; $i<24;$i++)
                                                 echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures1_day7[0] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select> : 
                                        <select id="minutes1_day7" style="width:50px;" onchange="getHeures_day7();">
                                            <?php for ($i=0; $i<60;$i+=15)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures1_day7[1] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select>
                                        <?php echo $xml->A; ?> 
                                        <select id="heures2_day7" style="width:50px; margin:0" onchange="getHeures_day7();">
                                            <?php for ($i=0; $i<24;$i++)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures2_day7[0] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select> : 
                                        <select id="minutes2_day7" style="width:50px;" onchange="getHeures_day7();">
                                            <?php for ($i=0; $i<60;$i+=15)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures2_day7[1] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select>
                                        
                                        <input name="date7_m" type="hidden" id="date7_m" value="<?php echo KT_escapeAttribute($day7_m); ?>" size="50" />
                                        <?php echo $tNGs->displayFieldHint("date7_m");?> <?php echo $tNGs->displayFieldError("magazins", "date7_m", $cnt1); ?>
                                        <script>
                                        function getHeures_day7(){
                                            var heure = ($('#heures1_day7').val())+"h"+($('#minutes1_day7').val())+"-"+($('#heures2_day7').val())+"h"+($('#minutes2_day7').val());
                                            $('#date7_m').val(heure);
                                        }
                                        </script>
                                    </td>
                                    <td class="day7_2 show_date_7">
                                        <?php $heures_day7_2 = explode('-',$day7_e);
                                           $heures1_day7_2 = explode('h',$heures_day7_2[0]);
                                           $heures2_day7_2 = explode('h',$heures_day7_2[1]);
                                        ?>
                                        
                                        
                                        <select id="heures1_day7_2" style="width:50px; margin:0" onchange="getHeures_day7_2();">
                                            <?php for ($i=0; $i<24;$i++)
                                                 echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures1_day7_2[0] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select> : 
                                        <select id="minutes1_day7_2" style="width:50px;" onchange="getHeures_day7_2();">
                                            <?php for ($i=0; $i<60;$i+=15)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures1_day7_2[1] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select>
                                        <?php echo $xml->A; ?> 
                                        <select id="heures2_day7_2" style="width:50px; margin:0" onchange="getHeures_day7_2();">
                                            <?php for ($i=0; $i<24;$i++)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures2_day7_2[0] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select> : 
                                        <select id="minutes2_day7_2" style="width:50px;" onchange="getHeures_day7_2();">
                                            <?php for ($i=0; $i<60;$i+=15)
                                                echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures2_day7_2[1] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
                                            ?>
                                        </select>
                                        
                                        <input name="date7_e" type="hidden" id="date7_e" value="<?php echo KT_escapeAttribute($day7_e); ?>" size="50" />
                                        <?php echo $tNGs->displayFieldHint("date7_e");?> <?php echo $tNGs->displayFieldError("magazins", "date7_e", $cnt1); ?>
                                        <script>
                                        function getHeures_day7_2(){
                                            var heure = ($('#heures1_day7_2').val())+"h"+($('#minutes1_day7_2').val())+"-"+($('#heures2_day7_2').val())+"h"+($('#minutes2_day7_2').val());
                                            $('#date7_e').val(heure);
                                        }
                                        </script>
                                    </td>
                    
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <script type="text/javascript">
						$(document).ready(function(){
							<?php for($l=1;$l<=7;$l++){ ?>
							$('.show_date_<?php echo $l;?>').hide();
							var d<?php echo $l;?> = $('#day<?php echo $l;?>').val();
							var day<?php echo $l;?> = $('#date<?php echo $l;?>_e').val();
							
							if(d<?php echo $l;?>=='0'){
								$('#day<?php echo $l;?>_check').attr('checked',false);
								$('#date<?php echo $l;?>_m').val('').attr('disabled', false);
								$('#date<?php echo $l;?>_e').val('').attr('disabled', false);
								
							}else if(d<?php echo $l;?>=='1'){
								$('.show_date_<?php echo $l;?>').show();
								if(day<?php echo $l;?>!=''){
									$('.day<?php echo $l;?>_2').show();
									
									
								}else{
									$('#day<?php echo $l;?>_check').attr('checked','checked');
									$('.date<?php echo $l;?>_e').val('');
									$('.day<?php echo $l;?>_2').hide();
								}
							}
						
							
							$('#day<?php echo $l;?>').live("click", function() {
								if (this.checked) {
									$('#day<?php echo $l;?>').val('1');
									$('.show_date_<?php echo $l;?>').show();
								}
								else {
									$('#day<?php echo $l;?>').val('0');
									$('.show_date_<?php echo $l;?>').hide();
								}
							});	
							
							$('#day<?php echo $l;?>_check').live("click", function() {
								if (this.checked) {
									$('.day<?php echo $l;?>_2').hide();
									$('#date<?php echo $l;?>_e').val('').attr('disabled', false);
								}
								else {
									$('.day<?php echo $l;?>_2').show();
								}
							});	
							
							
							<?php }?>	
						});
						</script>
                    
                    <!--<script type="text/javascript">
					$(document).ready(function(){
					
						<?php if($_GET['id_magazin']){?>
						var times = $("#heure_ouverture_1").val();
						var date_mor = $("#date_mor_1").val();
					
					
						//alert(date_mor);
						if(date_mor!=''){
							$('#divCaption1').hide();
							$('.divCaption2').show();
							
							$('#chkCaption').live("click", function() {
								if (this.checked) {
									$('.divCaption2').hide();
									$('#divCaption1').show();
								}
								else {
									$('.divCaption2').show();
									$('#divCaption1').hide();
								}
							});	
							
						}else{
							$('#chkCaption').attr('checked','checked');
							$('#divCaption1').show();
							$('.divCaption2').hide();
							$('#chkCaption').live("click", function() {
								if (this.checked) {
									$('.divCaption2').hide();
									$('#divCaption1').show();
								}
								else {
									$('.divCaption2').show();
									$('#divCaption1').hide();
								}
							});	
						}
						<?php } else {?>
							$('#chkCaption').attr('checked','checked');
							$('.divCaption2').hide();
							$('#chkCaption').live("click", function() {
								if (this.checked) {
									$('.divCaption2').hide();
									$('#divCaption1').show();
								}
								else {
									$('.divCaption2').show();
									$('#divCaption1').hide();
								}
							});	
						<?php }?>
					});
					</script>-->
                    
                    <?php /*?><tr>
                    	<td class="KT_th"></td>
                        <td><input type="checkbox" id="chkCaption" /> Ouvert entre midi</td>
                    </tr>
                    
                    <tr id="divCaption1">
                      <td class="KT_th"><label for="heure_ouverture_<?php echo $cnt1; ?>">Heure d'ouverture:</label></td>
                      <td><?php $heures = explode('-',$row_rsmagazins['heure_ouverture']);
							   $heures1 = explode('h',$heures[0]);
							   $heures2 = explode('h',$heures[1]);
						?>
						de <select id="heures1" style="width:50px; margin:0" onchange="getHeures();">
						<?php for ($i=0; $i<24;$i++)
								 echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures1[0] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
						?>
						</select> : 
						<select id="minutes1" style="width:50px;" onchange="getHeures();">
						<?php for ($i=0; $i<60;$i+=15)
								 echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures1[1] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
						?>
						</select>
						à&nbsp;&nbsp; <select id="heures2" style="width:50px; margin:0" onchange="getHeures();">
						<?php for ($i=0; $i<24;$i++)
								 echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures2[0] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
						?>
						</select> : 
						<select id="minutes2" style="width:50px;" onchange="getHeures();">
						<?php for ($i=0; $i<60;$i+=15)
								 echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$heures2[1] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
						?>
						</select>
						
						
						<input name="heure_ouverture_<?php echo $cnt1; ?>" type="hidden" id="heure_ouverture_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsmagazins['heure_ouverture']); ?>" size="50" />
												  <?php echo $tNGs->displayFieldHint("heure_ouverture");?> <?php echo $tNGs->displayFieldError("magazins", "heure_ouverture", $cnt1); ?>
						<script>
						function getHeures(){
							var heure = ($('#heures1').val())+"h"+($('#minutes1').val())+"-"+($('#heures2').val())+"h"+($('#minutes2').val());
							$('#heure_ouverture_1').val(heure);
						}
						</script>
						 </td>
                    </tr>
                    
                    
                    
                    <tr class="divCaption2">
                    	<td class="KT_th">Le matin :</td>
                        <td>
                        <?php $m_heures = explode('-',$row_rsmagazins['date_mor']);
							   $m_heures1 = explode('h',$m_heures[0]);
							   $m_heures2 = explode('h',$m_heures[1]);
						?>
						<?php echo $xml->de ; ?>
						
						<select id="m_heures1" style="width:50px; margin:0" onchange="m_getHeures();">
							<?php for ($i=0; $i<=12;$i++)
								 echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$m_heures1[0] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
							?>
						</select> : 
						<select id="m_minutes1" style="width:50px;" onchange="m_getHeures();">
							<?php for ($i=0; $i<60;$i+=15)
								echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$m_heures1[1] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
							?>
						</select>
						<?php echo $xml->A; ?> 
						<select id="m_heures2" style="width:50px; margin:0" onchange="m_getHeures();">
							<?php for ($i=0; $i<=12;$i++)
								echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$m_heures2[0] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
							?>
						</select> : 
						<select id="m_minutes2" style="width:50px;" onchange="m_getHeures();">
							<?php for ($i=0; $i<60;$i+=15)
								echo '<option vlaue='.($i<10 ? "0".$i : $i).' '.($i==$m_heures2[1] ? ' selected':'').'>'.($i<10 ? "0".$i : $i).'</option>';
							?>
						</select>
						
						<input name="date_mor_<?php echo $cnt1; ?>" type="hidden" id="date_mor_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsmagazins['date_mor']); ?>" size="50" />
						<?php echo $tNGs->displayFieldHint("date_mor");?> <?php echo $tNGs->displayFieldError("magazins", "date_mor", $cnt1); ?>
						<script>
						function m_getHeures(){
							var heure = ($('#m_heures1').val())+"h"+($('#m_minutes1').val())+"-"+($('#m_heures2').val())+"h"+($('#m_minutes2').val());
							$('#date_mor_1').val(heure);
						}
						</script>
                        </td>
                    </tr>
                    <tr class="divCaption2">
                    	<td class="KT_th">L'après-midi :</td>
                        <td>
                        	<?php $e_heures = explode('-',$row_rsmagazins['date_eve']);
								   $e_heures1 = explode('h',$e_heures[0]);
								   $e_heures2 = explode('h',$e_heures[1]);
							?>
							<?php echo $xml->de ; ?>
							
							<select id="e_heures1" style="width:50px; margin:0" onchange="e_getHeures();">
								<?php for ($j=12; $j<24;$j++)
									 echo '<option vlaue='.($j<10 ? "0".$j : $j).' '.($j==$e_heures1[0] ? ' selected':'').'>'.($j<10 ? "0".$j : $j).'</option>';
								?>
							</select> : 
							<select id="e_minutes1" style="width:50px;" onchange="e_getHeures();">
								<?php for ($j=0; $j<60;$j+=15)
									echo '<option vlaue='.($j<10 ? "0".$j : $j).' '.($j==$e_heures1[1] ? ' selected':'').'>'.($j<10 ? "0".$j : $j).'</option>';
								?>
							</select>
							<?php echo $xml->A; ?> 
							<select id="e_heures2" style="width:50px; margin:0" onchange="e_getHeures();">
								<?php for ($j=12; $j<24;$j++)
									echo '<option vlaue='.($j<10 ? "0".$j : $j).' '.($j==$e_heures2[0] ? ' selected':'').'>'.($j<10 ? "0".$j : $j).'</option>';
								?>
							</select> : 
							<select id="e_minutes2" style="width:50px;" onchange="e_getHeures();">
								<?php for ($j=0; $j<60;$j+=15)
									echo '<option vlaue='.($j<10 ? "0".$j : $j).' '.($j==$e_heures2[1] ? ' selected':'').'>'.($j<10 ? "0".$j : $j).'</option>';
								?>
							</select>
							
							<input name="date_eve_<?php echo $cnt1; ?>" type="hidden" id="date_eve_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsmagazins['date_eve']); ?>" size="50" />
							<?php echo $tNGs->displayFieldHint("date_eve");?> <?php echo $tNGs->displayFieldError("magazins", "date_eve", $cnt1); ?>
							<script>
							function e_getHeures(){
								var heure = ($('#e_heures1').val())+"h"+($('#e_minutes1').val())+"-"+($('#e_heures2').val())+"h"+($('#e_minutes2').val());
								$('#date_eve_1').val(heure);
							}
							</script>
                        </td>
                    </tr>
                    
                    
                    
                    
                    
                    
                    <tr>
                      <td class="KT_th"><label for="jours_ouverture_<?php echo $cnt1; ?>">Jours d'ouverture:</label></td>
                      <td><?php $jours = explode(',',$row_rsmagazins['jours_ouverture']); ?>
Lundi&nbsp;<input name="jours" id="jour1" type="checkbox" value="1" <?php if (@$_GET['id_magazin'] == "" or in_array(1,$jours)) echo 'checked="checked"'; ?> />
Mardi&nbsp;<input name="jours" id="jour2" type="checkbox" value="2" <?php if (@$_GET['id_magazin'] == "" or in_array(2,$jours)) echo 'checked="checked"'; ?> />
Mercredi&nbsp;<input name="jours" id="jour3" type="checkbox" value="3" <?php if (@$_GET['id_magazin'] == "" or in_array(3,$jours)) echo 'checked="checked"'; ?>/>
Jeudi&nbsp;<input name="jours" id="jour4" type="checkbox" value="4" <?php if (@$_GET['id_magazin'] == "" or in_array(4,$jours)) echo 'checked="checked"'; ?> />
Vendredi&nbsp;<input name="jours" id="jour5" type="checkbox" value="5" <?php if (@$_GET['id_magazin'] == "" or in_array(5,$jours)) echo 'checked="checked"'; ?> /><br />
Samdi&nbsp;<input name="jours" id="jour6" type="checkbox" value="6" <?php if (@$_GET['id_magazin'] == "" or in_array(6,$jours)) echo 'checked="checked"'; ?> />
Dimanche&nbsp;<input name="jours" id="jour7" type="checkbox" value="7" <?php if (@$_GET['id_magazin'] == "" or in_array(7,$jours)) echo 'checked="checked"'; ?> />
<input name="jours_ouverture_<?php echo $cnt1; ?>" type="hidden" id="jours_ouverture_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsmagazins['jours_ouverture']); ?>" size="50" />
                          <?php echo $tNGs->displayFieldHint("jours_ouverture");?> <?php echo $tNGs->displayFieldError("magazins", "jours_ouverture", $cnt1); ?>
<script>
function getJours(){
	var jours = '';
	for(i=1;i<=7;i++)
		if($('#jour'+i).is(':checked'))
			jours += $('#jour'+i).val()+',';
	$('#jours_ouverture_1').val(jours);
}
getJours();
$(":checkbox").click(getJours);
</script> </td>
                    </tr><?php */?>
                    <tr>
                      <td class="KT_th"><label for="description_<?php echo $cnt1; ?>">Description:</label></td>
                      <td><textarea name="description_<?php echo $cnt1; ?>" id="description_<?php echo $cnt1; ?>" cols="50" rows="5"><?php echo KT_escapeAttribute($row_rsmagazins['description']); ?></textarea>
                          <?php echo $tNGs->displayFieldHint("description");?> <?php echo $tNGs->displayFieldError("magazins", "description", $cnt1); ?> </td>
                    </tr>
                    <tr><td class="KT_th">Localisation</td>
                    <td><div id="map_canvas" style="width:400px; height:380px"></div>
<input id="latlan_1" name="latlan_<?php echo $cnt1; ?>" type="hidden" value="<?php echo KT_escapeAttribute($row_rsmagazins['latlan']); ?>" /></td>
                  </table>
                  <input type="hidden" name="kt_pk_magazins_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsmagazins['kt_pk_magazins']); ?>" />
                  <?php } while ($row_rsmagazins = mysql_fetch_assoc($rsmagazins)); ?>
                <div class="KT_bottombuttons">
                  <div>
                    <?php 
      // Show IF Conditional region1
      if (@$_GET['id_magazin'] == "") {
      ?>
                      <input type="submit" name="KT_Insert1" id="KT_Insert1" value="<?php echo NXT_getResource("Insert_FB"); ?>" />
                      <?php 
      // else Conditional region1
      } else { ?>
                      <div class="KT_operations">
                        <input type="submit" name="KT_Insert1" value="<?php echo NXT_getResource("Insert as new_FB"); ?>" onclick="nxt_form_insertasnew(this, 'id_magazin')" />
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

mysql_free_result($Recordset2);
?>