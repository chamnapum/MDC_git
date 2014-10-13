<?php session_start();?>
<?php require_once('Connections/magazinducoin.php'); ?>

<?php
//MX Widgets3 include
require_once('includes/wdg/WDG.php');

// Load the common classes
require_once('includes/common/KT_common.php');

// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("");

// Make unified connection variable
$conn_magazinducoin = new KT_connection($magazinducoin, $database_magazinducoin);

//start Trigger_CheckPasswords trigger


// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("civilite", true, "text", "", "", "", "");
$formValidation->addField("nom", true, "text", "", "", "", "");
$formValidation->addField("prenom", true, "text", "", "", "", "");
$formValidation->addField("nom_magazin", true, "text", "", "", "", "");
$formValidation->addField("region", true, "numeric", "", "", "", "");
$formValidation->addField("email", true, "text", "email", "", "", "");
$formValidation->addField("password", true, "text", "", "", "", "");
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://ns.adobe.com/addt">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 
	<title>Inscription Utilisateur </title>
    <?php include("modules/head.php"); ?>
<link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script src="includes/common/js/base.js" type="text/javascript"></script>
<script src="includes/common/js/utility.js" type="text/javascript"></script>
<script src="includes/skins/style.js" type="text/javascript"></script>
<?php echo $tNGs->displayValidationRules();?>
<script type="text/javascript" src="includes/common/js/sigslot_core.js"></script>


</head>
<body id="sp">
<?php include("modules/header.php"); ?>


<div class="content_wrapper_sbr" id="contact">
  		<div id="content" class="photographes">
        
        <div class="top reduit">
        	<?php include("modules/member_menu.php"); ?>
                <?php //include("modules/menu.php"); ?>
                <?php //include("modules/menu1.php"); ?>
        </div>
          <?php
	echo $tNGs->getErrorMsg();
?>
    <div style="width:100%; float:left; margin-bottom:10px; margin-top:10px; font-size:13px;">
    	<?php
		$sql_pro  = "UPDATE utilisateur SET subscribe='0' WHERE id='".$_REQUEST['code']."' AND email='".$_REQUEST['email']."'";
		$result_pro  = mysql_query($sql_pro ) or die (mysql_error());
		if($result_pro){
			echo 'Vous êtes désabonné';
		}else{
			echo 'Sorry';
		}
		?>
    </div>

 
  </div>
</div>

<div style="clear:both;"></div>

<div id="footer">
	<?php include("modules/footer.php"); ?>
</div>

</body>
</html>
