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

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("nom_magazin", true, "text", "", "", "", "");
$formValidation->addField("region", true, "numeric", "", "", "", "");
$formValidation->addField("ville", true, "numeric", "", "", "", "");
$formValidation->addField("adresse", true, "text", "", "", "", "");
$formValidation->addField("photo1", true, "", "", "", "", "");
$formValidation->addField("description", true, "text", "", "", "", "");
$tNGs->prepareValidation($formValidation);
// End trigger

//start Trigger_FileDelete2 trigger
//remove this line if you want to edit the code by hand 
function Trigger_FileDelete2(&$tNG) {
  $deleteObj = new tNG_FileDelete($tNG);
  $deleteObj->setFolder("assets/images/magasins/");
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
  $uploadObj->setFolder("assets/images/magasins/");
  $uploadObj->setResize("true", 400, 400);
  $uploadObj->setMaxSize(1500);
  $uploadObj->setAllowedExtensions("gif, jpg, jpe, jpeg, png");
  $uploadObj->setRename("auto");
  return $uploadObj->Execute();
}
//end Trigger_ImageUpload2 trigger


function Trigger_FileDelete3(&$tNG) {
  $deleteObj = new tNG_FileDelete($tNG);
  $deleteObj->setFolder("assets/images/magasins/");
  $deleteObj->setDbFieldName("logo");
  return $deleteObj->Execute();
}
//end Trigger_FileDelete2 trigger

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

//start Trigger_FileDelete1 trigger
//remove this line if you want to edit the code by hand 
function Trigger_FileDelete1(&$tNG) {
  $deleteObj = new tNG_FileDelete($tNG);
  $deleteObj->setFolder("assets/images/magasins/");
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
  $uploadObj->setFolder("assets/images/magasins/");
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
  $deleteObj->setFolder("assets/images/magasins/");
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
  $uploadObj->setFolder("assets/images/magasins/");
  $uploadObj->setResize("true", 400, 400);
  $uploadObj->setMaxSize(1500);
  $uploadObj->setAllowedExtensions("gif, jpg, jpe, jpeg, png");
  $uploadObj->setRename("auto");
  return $uploadObj->Execute();
}
//end Trigger_ImageUpload trigger
function Trigger_do_payment(){
	$query_regions = "SELECT COUNT(*) AS nb FROM magazins WHERE id_user = ".$_SESSION['kt_login_id'];
	$regions = mysql_query($query_regions) or die(mysql_error());
	$row_regions = mysql_fetch_assoc($regions);
	if($row_regions['nb']>0)
		header('Location: paiement_magasin.php');
	else
		header('Location: mes-magazins.php');
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
$query_regions = "SELECT * FROM region ORDER BY nom_region ASC";
$regions = mysql_query($query_regions, $magazinducoin) or die(mysql_error());
$row_regions = mysql_fetch_assoc($regions);
$totalRows_regions = mysql_num_rows($regions);

$colname_default = "-1";
if (isset($_SESSION['kt_login_id'])) {
  $colname_default = $_SESSION['kt_login_id'];
}
mysql_select_db($database_magazinducoin, $magazinducoin);
$query_default = sprintf("SELECT nom_magazin, siren, region, adresse, code_postal, ville FROM utilisateur WHERE id = %s", GetSQLValueString($colname_default, "int"));
$default = mysql_query($query_default, $magazinducoin) or die(mysql_error());
$row_default = mysql_fetch_assoc($default);
$totalRows_default = mysql_num_rows($default);

// Make an insert transaction instance
$ins_magazins = new tNG_multipleInsert($conn_magazinducoin);
$tNGs->addTransaction($ins_magazins);
// Register triggers
$ins_magazins->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_magazins->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_magazins->registerTrigger("END", "Trigger_do_payment", 99);
$ins_magazins->registerTrigger("AFTER", "Trigger_ImageUpload", 97);
$ins_magazins->registerTrigger("AFTER", "Trigger_ImageUpload1", 97);
$ins_magazins->registerTrigger("AFTER", "Trigger_ImageUpload2", 97);
$ins_magazins->registerTrigger("AFTER", "Trigger_ImageUpload3", 97);
// Add columns
$ins_magazins->setTable("magazins");
$ins_magazins->addColumn("nom_magazin", "STRING_TYPE", "POST", "nom_magazin");
$ins_magazins->addColumn("siren", "STRING_TYPE", "POST", "siren");
$ins_magazins->addColumn("region", "NUMERIC_TYPE", "POST", "region");
$ins_magazins->addColumn("ville", "NUMERIC_TYPE", "POST", "ville");
$ins_magazins->addColumn("adresse", "STRING_TYPE", "POST", "adresse");
$ins_magazins->addColumn("code_postal", "STRING_TYPE", "POST", "code_postal");
$ins_magazins->addColumn("logo", "FILE_TYPE", "FILES", "logo");
$ins_magazins->addColumn("photo1", "FILE_TYPE", "FILES", "photo1");
$ins_magazins->addColumn("photo2", "FILE_TYPE", "FILES", "photo2");
$ins_magazins->addColumn("photo3", "FILE_TYPE", "FILES", "photo3");
$ins_magazins->addColumn("heure_ouverture", "STRING_TYPE", "POST", "heure_ouverture");
$ins_magazins->addColumn("jours_ouverture", "STRING_TYPE", "POST", "jours_ouverture");
$ins_magazins->addColumn("description", "STRING_TYPE", "POST", "description");
$ins_magazins->addColumn("latlan", "STRING_TYPE", "POST", "latlan");
$ins_magazins->addColumn("id_user", "STRING_TYPE", "SESSION", "kt_login_id");
$ins_magazins->setPrimaryKey("id_magazin", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_magazins = new tNG_multipleUpdate($conn_magazinducoin);
$tNGs->addTransaction($upd_magazins);
// Register triggers
$upd_magazins->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_magazins->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_magazins->registerTrigger("END", "Trigger_Default_Redirect", 99, "includes/nxt/back.php");
$upd_magazins->registerTrigger("AFTER", "Trigger_ImageUpload", 97);
$upd_magazins->registerTrigger("AFTER", "Trigger_ImageUpload1", 97);
$upd_magazins->registerTrigger("AFTER", "Trigger_ImageUpload2", 97);
$upd_magazins->registerTrigger("AFTER", "Trigger_ImageUpload3", 97);
// Add columns
$upd_magazins->setTable("magazins");
$upd_magazins->addColumn("nom_magazin", "STRING_TYPE", "POST", "nom_magazin");
$upd_magazins->addColumn("siren", "STRING_TYPE", "POST", "siren");
$upd_magazins->addColumn("region", "NUMERIC_TYPE", "POST", "region");
$upd_magazins->addColumn("ville", "NUMERIC_TYPE", "POST", "ville");
$upd_magazins->addColumn("adresse", "STRING_TYPE", "POST", "adresse");
$upd_magazins->addColumn("code_postal", "STRING_TYPE", "POST", "code_postal");
$upd_magazins->addColumn("logo", "FILE_TYPE", "FILES", "logo");
$upd_magazins->addColumn("photo1", "FILE_TYPE", "FILES", "photo1");
$upd_magazins->addColumn("photo2", "FILE_TYPE", "FILES", "photo2");
$upd_magazins->addColumn("photo3", "FILE_TYPE", "FILES", "photo3");
$upd_magazins->addColumn("heure_ouverture", "STRING_TYPE", "POST", "heure_ouverture");
$upd_magazins->addColumn("jours_ouverture", "STRING_TYPE", "POST", "jours_ouverture");
$upd_magazins->addColumn("description", "STRING_TYPE", "POST", "description");
$upd_magazins->addColumn("latlan", "STRING_TYPE", "POST", "latlan");
$upd_magazins->setPrimaryKey("id_magazin", "NUMERIC_TYPE", "GET", "id_magazin");

// Make an instance of the transaction object
$del_magazins = new tNG_multipleDelete($conn_magazinducoin);
$tNGs->addTransaction($del_magazins);
// Register triggers
$del_magazins->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_magazins->registerTrigger("END", "Trigger_Default_Redirect", 99, "includes/nxt/back.php");
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
	<title>Magazin Du Coin | Magasin</title>
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
  document.getElementById('latlan').value = location;
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
    var address = "<?php echo $row_default['adresse']; ?> <?php echo getVilleById($row_default['ville']); ?> <?php echo getRegionById($row_default['region']); ?> France";
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
		document.getElementById('latlan').value = results[0].geometry.location;
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
		document.getElementById('latlan').value = results[0].geometry.location;
      } else {
        alert("Geocode was not successful for the following reason: " + status);
      }
    });
  }
  
  function localiser_adresse(){
  	
	var location_ = document.getElementById('latlan').value;
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
<body id="sp" onload="<?php if(!isset($_GET['no_new'])) { ?>ajax('ajax/ville.php?default=<?php echo $row_rsmagazins['ville']; ?>&id_region=<?php echo $row_rsmagazins['region']; ?>','#ville_1');<?php } ?> initialize()">
<?php include("modules/header.php"); ?>
  			<div id="content" class="photographes">
        	 <div class="top reduit">
                    <?php include("modules/menu.php"); ?>
            </div>
            <div style="float:left; width:200px;">           
					<?php include("modules/membre_menu.php"); ?>
			</div>
            
            
            
<div style="float:left; width:780px;">
  	<h3 style="margin-left:20px;">Magasin : <?php echo $row_rsmagazins['nom_magazin'] ? $row_rsmagazins['nom_magazin'] : ""; ?></h3>
          <?php
	echo $tNGs->getErrorMsg();
?>
          <div class="KT_tng">
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
<div style="position:relative; padding-left:20px;" class="form_insc2">
<?php if(!isset($_GET['conf'])) { ?>
<div class="champ"> 
<label for="nom_magazin_<?php echo $cnt1; ?>">Nom de magasin:</label>
<input type="text" name="nom_magazin_<?php echo $cnt1; ?>" id="nom_magazin_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsmagazins['nom_magazin']); ?>" size="32" maxlength="250" onblur="localiser_adresse();" />
                          <?php echo $tNGs->displayFieldHint("nom_magazin");?> <?php echo $tNGs->displayFieldError("magazins", "nom_magazin", $cnt1); ?>
</div>

<div class="champ"> 
<label for="siren_<?php echo $cnt1; ?>">Siren:</label>
<input type="text" name="siren_<?php echo $cnt1; ?>" id="siren_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsmagazins['siren']); ?>" size="30" maxlength="30" />
                          <?php echo $tNGs->displayFieldHint("siren");?> <?php echo $tNGs->displayFieldError("magazins", "siren", $cnt1); ?>
</div>

<div class="champ"> 
 <label for="region_<?php echo $cnt1; ?>">Région:</label>
 <select name="region_<?php echo $cnt1; ?>" id="region_<?php echo $cnt1; ?>" onchange="ajax('ajax/ville.php?default=<?php echo $row_rsmagazins['ville']; ?>&id_region='+this.value,'#ville_1'); localiser_adresse();">
                          <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                          <?php 
do {  
?>
                          <option value="<?php echo $row_regions['id_region']?>"<?php if (!(strcmp($row_regions['id_region'], $row_rsmagazins['region']))) {echo "SELECTED";} ?> title="<?php echo ($row_regions['nom_region']); ?>"><?php echo ($row_regions['nom_region']); ?></option>
                          <?php
} while ($row_regions = mysql_fetch_assoc($regions));
  $rows = mysql_num_rows($regions);
  if($rows > 0) {
      mysql_data_seek($regions, 0);
	  $row_regions = mysql_fetch_assoc($regions);
  }
?>
                        </select>
                          <?php echo $tNGs->displayFieldError("magazins", "region", $cnt1); ?>
</div>

<div class="champ"> 
<label for="ville_<?php echo $cnt1; ?>">Ville:</label>
<select name="ville_<?php echo $cnt1; ?>" id="ville_<?php echo $cnt1; ?>" onchange="localiser_adresse();">
                          <option value=""><?php echo NXT_getResource("Select one..."); ?></option>   
                        </select>
                          <?php echo $tNGs->displayFieldError("magazins", "ville", $cnt1); ?>
</div>

<div class="champ"> 
<label for="adresse_<?php echo $cnt1; ?>">Adresse:</label>
<input type="text" name="adresse_<?php echo $cnt1; ?>" id="adresse_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsmagazins['adresse']); ?>" size="32" onblur="localiser_adresse();"  />
                          <?php echo $tNGs->displayFieldHint("adresse");?> <?php echo $tNGs->displayFieldError("magazins", "adresse", $cnt1); ?>
</div>

<div class="champ"> 
<label for="code_postal_<?php echo $cnt1; ?>">Code postal:</label>
<input type="text" name="code_postal_<?php echo $cnt1; ?>" id="code_postal_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsmagazins['code_postal']); ?>" size="5" maxlength="5" />
                          <?php echo $tNGs->displayFieldHint("code_postal");?> <?php echo $tNGs->displayFieldError("magazins", "code_postal", $cnt1); ?>
</div>
 <?php } else { ?>
            
            <div class="champ"> 
<label for="nom_magazin_<?php echo $cnt1; ?>">Nom de magasin:</label>
<input type="text" name="nom_magazin_<?php echo $cnt1; ?>" id="nom_magazin_<?php echo $cnt1; ?>" value="<?php echo $row_default['nom_magazin']; ?>" size="32" maxlength="250" onblur="localiser_adresse();" />
                          <?php echo $tNGs->displayFieldHint("nom_magazin");?> <?php echo $tNGs->displayFieldError("magazins", "nom_magazin", $cnt1); ?>
</div>

<div class="champ"> 
<label for="siren_<?php echo $cnt1; ?>">Siren:</label>
<input type="text" name="siren_<?php echo $cnt1; ?>" id="siren_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_default['siren']); ?>" size="30" maxlength="30" />
                          <?php echo $tNGs->displayFieldHint("siren");?> <?php echo $tNGs->displayFieldError("magazins", "siren", $cnt1); ?>
</div>

<div class="champ"> 
 <label for="region_<?php echo $cnt1; ?>">Région:</label>
 <select name="region_<?php echo $cnt1; ?>" id="region_<?php echo $cnt1; ?>" onchange="ajax('ajax/ville.php?default=<?php echo $row_rsmagazins['ville']; ?>&id_region='+this.value,'#ville_1'); localiser_adresse();">
                          <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                          <?php 
do {  
?>
                          <option value="<?php echo $row_regions['id_region']?>"<?php if (!(strcmp($row_regions['id_region'], $row_default['region']))) {echo "SELECTED";} ?> title="<?php echo ($row_regions['nom_region']); ?>"><?php echo ($row_regions['nom_region']); ?></option>
                          <?php
} while ($row_regions = mysql_fetch_assoc($regions));
  $rows = mysql_num_rows($regions);
  if($rows > 0) {
      mysql_data_seek($regions, 0);
	  $row_regions = mysql_fetch_assoc($regions);
  }
?>
                        </select>
                          <?php echo $tNGs->displayFieldError("magazins", "region", $cnt1); ?>
</div>

<div class="champ"> 
<label for="ville_<?php echo $cnt1; ?>">Ville:</label>
<select name="ville_<?php echo $cnt1; ?>" id="ville_<?php echo $cnt1; ?>" onchange="localiser_adresse();">
                          <option value=""><?php echo NXT_getResource("Select one..."); ?></option>   
                        </select>
                          <?php echo $tNGs->displayFieldError("magazins", "ville", $cnt1); ?>
</div>

<div class="champ"> 
<label for="adresse_<?php echo $cnt1; ?>">Adresse:</label>
<input type="text" name="adresse_<?php echo $cnt1; ?>" id="adresse_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_default['adresse']); ?>" size="32" onblur="localiser_adresse();"  />
                          <?php echo $tNGs->displayFieldHint("adresse");?> <?php echo $tNGs->displayFieldError("magazins", "adresse", $cnt1); ?>
</div>

<div class="champ"> 
<label for="code_postal_<?php echo $cnt1; ?>">Code postal:</label>
<input type="text" name="code_postal_<?php echo $cnt1; ?>" id="code_postal_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_default['code_postal']); ?>" size="5" maxlength="5" />
                          <?php echo $tNGs->displayFieldHint("code_postal");?> <?php echo $tNGs->displayFieldError("magazins", "code_postal", $cnt1); ?>
</div>
					<?php } ?>
 </div>
 <div style="position:relative; padding-left:20px; height:780px" class="form_insc3">
<div class="clear"></div>
<div class="champ"> 
<label for="logo_<?php echo $cnt1; ?>">Logo de magasin:</label>
<input type="file" name="logo_<?php echo $cnt1; ?>" id="logo_<?php echo $cnt1; ?>" size="32" />
                      <?php if($row_rsmagazins['logo']) { ?>
                      <div id="imgContiner1" style="right:85px;">
                      	<img src="assets/images/magasins/<?php echo KT_escapeAttribute($row_rsmagazins['logo']); ?>" width="60" />&nbsp;&nbsp; <a href="javascript:ajax('ajax/supprimer_photo.php?t=magazins&c=logo&id=<?php echo $row_rsmagazins['id_magazin']; ?>&f=<?php echo KT_escapeAttribute($row_rsmagazins['logo']); ?>','#imgContiner1');">Supprimer logo</a>
                      </div> 
					<?php } ?>
                          <?php echo $tNGs->displayFieldError("magazins", "logo", $cnt1); ?>
</div>
<div class="clear"></div>

<div class="champ"> 
<label for="photo1_<?php echo $cnt1; ?>">Photo 1:</label>
<input type="file" name="photo1_<?php echo $cnt1; ?>" id="photo1_<?php echo $cnt1; ?>" size="32" />
                      <?php if($row_rsmagazins['photo1']) { ?>
                      <div id="imgContiner1" style="top:57px;">
                      	<img src="assets/images/magasins/<?php echo KT_escapeAttribute($row_rsmagazins['photo1']); ?>" width="60" />&nbsp;&nbsp; <a href="javascript:ajax('ajax/supprimer_photo.php?t=magazins&c=photo1&id=<?php echo $row_rsmagazins['id_magazin']; ?>&f=<?php echo KT_escapeAttribute($row_rsmagazins['photo1']); ?>','#imgContiner1');">Supprimer photo</a>
                      </div> 
					<?php } ?>
                          <?php echo $tNGs->displayFieldError("magazins", "photo1", $cnt1); ?>
</div>
<div class="clear"></div>
<div class="champ"> 
<label for="photo2_<?php echo $cnt1; ?>">Photo 2:</label>
<input type="file" name="photo2_<?php echo $cnt1; ?>" id="photo2_<?php echo $cnt1; ?>" size="32" />
                      <?php if($row_rsmagazins['photo2']) { ?>
                      <div style="float:left" id="imgContiner2">
                      	<img src="assets/images/magasins/<?php echo KT_escapeAttribute($row_rsmagazins['photo2']); ?>" width="60" />&nbsp;&nbsp; <a href="javascript:ajax('ajax/supprimer_photo.php?t=magazins&c=photo2&id=<?php echo $row_rsmagazins['id_magazin']; ?>&f=<?php echo KT_escapeAttribute($row_rsmagazins['photo2']); ?>','#imgContiner2');">Supprimer photo</a>
                      </div> 
					<?php } ?>
                          <?php echo $tNGs->displayFieldError("magazins", "photo2", $cnt1); ?>
</div>
<div class="clear"></div>
<div class="champ">
<label for="photo3_<?php echo $cnt1; ?>">Photo 3:</label>
<input type="file" name="photo3_<?php echo $cnt1; ?>" id="photo3_<?php echo $cnt1; ?>" size="32" />
                      <?php if($row_rsmagazins['photo3']) { ?>
                      <div style="float:left" id="imgContiner3">
                      	<img src="assets/images/magasins/<?php echo KT_escapeAttribute($row_rsmagazins['photo3']); ?>" width="60" />&nbsp;&nbsp; <a href="javascript:ajax('ajax/supprimer_photo.php?t=magazins&c=photo3&id=<?php echo $row_rsmagazins['id_magazin']; ?>&f=<?php echo KT_escapeAttribute($row_rsmagazins['photo3']); ?>','#imgContiner3');">Supprimer photo</a>
                      </div> 
					<?php } ?>
                          <?php echo $tNGs->displayFieldError("magazins", "photo3", $cnt1); ?>
</div>
<div class="clear"></div>
<div class="champ">
<label for="heure_ouverture_<?php echo $cnt1; ?>">Heure d'ouverture:</label>

<input name="heure_ouverture_<?php echo $cnt1; ?>" type="text" id="heure_ouverture_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsmagazins['heure_ouverture']); ?>" size="50" />
                          <?php echo $tNGs->displayFieldHint("heure_ouverture");?> <?php echo $tNGs->displayFieldError("magazins", "heure_ouverture", $cnt1); ?>
</div>
<div class="clear"></div>
<div class="champ" >
<label for="jours_ouverture_<?php echo $cnt1; ?>">Jour d'ouverture:</label>

<input name="jours_ouverture_<?php echo $cnt1; ?>" type="text" id="jours_ouverture_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsmagazins['jours_ouverture']); ?>" size="50" />
                          <?php echo $tNGs->displayFieldHint("jours_ouverture");?> <?php echo $tNGs->displayFieldError("magazins", "jours_ouverture", $cnt1); ?>
</div>
<div class="clear"></div>
<div class="champ" style="height:90px;">
<label for="description_<?php echo $cnt1; ?>">Description:</label>
<textarea name="description_<?php echo $cnt1; ?>" id="description_<?php echo $cnt1; ?>" cols="50" rows="3"><?php echo KT_escapeAttribute($row_rsmagazins['description']); ?></textarea>
                          <?php echo $tNGs->displayFieldHint("description");?> <?php echo $tNGs->displayFieldError("magazins", "description", $cnt1); ?>
</div>
<div class="clear"></div>
<div class="champ" style="height:90px;">
<label for="description_<?php echo $cnt1; ?>">Localisation:</label>
<div id="map_canvas" style="width:400px; height:380px"></div>
<input id="latlan" name="latlan" type="hidden" value="<?php echo KT_escapeAttribute($row_rsmagazins['latlan']); ?>" />
</div>

</div>
  

                  <input type="hidden" name="kt_pk_magazins_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsmagazins['kt_pk_magazins']); ?>" />
                  <?php } while ($row_rsmagazins = mysql_fetch_assoc($rsmagazins)); ?>
                  
                  
<div style="padding-left:20px; position:relative;" class="form_insc2">  
                    <?php 
      // Show IF Conditional region1
      if (@$_GET['id_magazin'] == "") {
      ?>
                      <input type="submit" class="image-submit" name="KT_Insert1" id="KT_Insert1" value="<?php echo NXT_getResource("Insert_FB"); ?>" />
                      <?php 
      // else Conditional region1
      } else { ?>
                      <input type="submit" class="image-submit" name="KT_Update1" value="<?php echo NXT_getResource("Update_FB"); ?>" />
                      <?php }
      // endif Conditional region1
      ?>
                    <input type="button" class="image-submit" name="KT_Cancel1" value="<?php echo NXT_getResource("Cancel_FB"); ?>" onclick="return UNI_navigateCancel(event, 'includes/nxt/back.php')"  style="left:170px;" />
                    </div>
              </form>
            </div>
            </div>
          </div>
  </div>
</div>

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
mysql_free_result($regions);

mysql_free_result($default);
?>