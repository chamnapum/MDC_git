<?php session_start();?>
<?php require_once('Connections/magazinducoin.php'); ?>

<?php
$val1 = (rand(1,10));
$val2 = (rand(1,10));
$val_t = $val1 + $val2;

if (array_key_exists("login", $_GET)) {
	
	if(($_GET['level']!='')){
		$_SESSION['level']=$_GET['level'];
	}
    $oauth_provider = $_GET['oauth_provider'];
    if ($oauth_provider == 'twitter') {
		echo '<script type="text/javascript">window.location="login-twitter.php";</script>';
        //header("Location:login-twitter.php");
    } else if ($oauth_provider == 'facebook') {
		echo '<script type="text/javascript">window.location="login-facebook.php";</script>';
        //header("Location:login-facebook.php");
    }
}
?>

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
//$formValidation->addField("nom_magazin", true, "text", "", "", "", "");
//$formValidation->addField("region", true, "numeric", "", "", "", "");
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
mysql_select_db($database_magazinducoin, $magazinducoin);
$query_categories = "SELECT cat_id, cat_name FROM category WHERE parent_id = 0 AND type='3' ORDER BY cat_name ASC";
$categories = mysql_query($query_categories, $magazinducoin) or die(mysql_error());
$row_categories = mysql_fetch_assoc($categories);
$totalRows_categories = mysql_num_rows($categories);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_regions = "SELECT * FROM region ORDER BY nom_region ASC";
$regions = mysql_query($query_regions, $magazinducoin) or die(mysql_error());
$row_regions = mysql_fetch_assoc($regions);
$totalRows_regions = mysql_num_rows($regions);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_ville = "SELECT departement.id_region, maps_ville.id_departement, maps_ville.nom, maps_ville.id_ville FROM (maps_ville LEFT JOIN departement ON departement.id_departement=maps_ville.id_departement) ORDER BY maps_ville.nom ASC ";
$ville = mysql_query($query_ville, $magazinducoin) or die(mysql_error());
$row_ville = mysql_fetch_assoc($ville);
$totalRows_ville = mysql_num_rows($ville);

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
</style>
<script type="text/javascript">
	$(document).ready(function(){
		
		$("#BK_Insert1_step2").click(function() {
			var email = $('#email').val();
			var nom_magazin = $('#nom_magazin').val();
			var region = $('#region1').val();
			var ville = $('#ville').val();
			//alert(region);
			//alert(ville);
			if(email==''){
				alert('Veuillez saisir des données :\n-email');
				return false;
			}else if(nom_magazin==''){
				alert('Veuillez saisir des données :\n-nom_magazin');
				return false;
			}else if(region==''){
				alert('Veuillez saisir des données :\n-region');
				return false;
			}else if(ville==''){
				alert('Veuillez saisir des données :\n-ville');
				return false;
			}else{
				$("#form1").submit();
				return true;
			}
		});
		
		$("#BK_Update1_step2").click(function() {
			var email = $('#email').val();
			var nom_magazin = $('#nom_magazin').val();
			var region = $('#region1').val();
			var ville = $('#ville').val();
			
			if(email==''){
				alert('Veuillez saisir des données :\n-email');
				return false;
			}else if(nom_magazin==''){
				alert('Veuillez saisir des données :\n-nom_magazin');
				return false;
			}else if(region==''){
				alert('Veuillez saisir des données :\n-region');
				return false;
			}else if(ville==''){
				alert('Veuillez saisir des données :\n-ville');
				return false;
			}else{
				return true;
				$("#update_user2").submit();
			}
		});
		
		
		
		$(".step2").hide();
		$("#send").hide();
		
		$( "#next_step" ).click(function() {
			
			var email = $("#email").val();
			var nom = $("#nom").val();
			var prenom = $("#prenom").val();
			var password = $("#password").val();
			var re_password = $("#re_password").val();
			var siren = $('#siren').val();
			
			var val_total = <?php echo $val_t;?>;
			var val3 = $("#val3").val();
			
			//alert(password);
			//alert(re_password);
			
			if((nom!='') && (prenom!='') && (password!='') && (siren!='')){
				if(password==re_password){
					if(val_total!=val3){
						alert("Captcha not right");	
						return false;
					}else{
						$(".step1").hide();
						$(".step2").show();
						$("#send").show();
					}
				}else{
					alert("Veuillez saisir des données :\n-Mot de passe\n-Retaper votre mot de passe\n C'est différent");	
				}
			}else{
				alert('Veuillez saisir des données :\n-nom\n-prenom\n-password\n-siren');
			}
		});
		
		$( "#back_step" ).click(function() {
			$(".step1").show();
			$("#send").hide();
			$(".step2").hide();
		});
		
		
		$('#subscribe').change(function () {
			 $("#subscribe").val(($(this).is(':checked')) ? "1" : "0");
		});
		
		$( "#btn_Insert1" ).click(function() {
			var email = $("#email").val();
			var nom = $("#nom").val();
			var prenom = $("#prenom").val();
			var password = $("#password").val();
			var re_password = $("#re_password").val();
			var val_total = <?php echo $val_t;?>;
			var val3 = $("#val3").val();
			//alert(val_total);
			if(nom==''){
				alert('Veuillez saisir des données :\n-nom');
				return false;
				
			}else if(prenom==''){
				alert('Veuillez saisir des données :\n-prenom');
				return false;
				
			}else if(email==''){
				alert('Veuillez saisir des données :\n-email');
				return false;
				
			}else if(password==''){
				alert('Veuillez saisir des données :\n-password');
				return false;
				
			}else if(re_password==''){
				alert('Veuillez saisir des données :\n-re_password');
				return false;
			
			}else if(password!=re_password){
				alert("Veuillez saisir des données :\n-Mot de passe\n-Retaper votre mot de passe\n C'est différent");	
				return false;
				
			}else if(val_total!=val3){
				alert("Captcha not right");	
				return false;
				
			}else{
				//alert('ssssss');
				return true;
				$("#insert_user").submit();
			}
		});
		
		/*$('#insert_user').submit(function(){
			alert('ffffff');
		});*/
		$( "#KT_Update1" ).click(function() {
			var email = $("#email").val();
			var nom = $("#nom").val();
			var prenom = $("#prenom").val();
			var password = $("#password").val();
			var re_password = $("#re_password").val();
			var val_total = <?php echo $val_t;?>;
			var val3 = $("#val3").val();
			
			if(nom==''){
				alert('Veuillez saisir des données :\n-nom');
				return false;
				
			}else if(prenom==''){
				alert('Veuillez saisir des données :\n-prenom');
				return false;
				
			}else if(email==''){
				alert('Veuillez saisir des données :\n-email');
				return false;
				
			}else if(password==''){
				alert('Veuillez saisir des données :\n-password');
				return false;
				
			}else if(re_password==''){
				alert('Veuillez saisir des données :\n-re_password');
				return false;
			
			}else if(password!=re_password){
				alert("Veuillez saisir des données :\n-Mot de passe\n-Retaper votre mot de passe\n C'est différent");	
				return false;
				
			}else if(val_total!=val3){
				alert("Catch not right");	
				return false;
				
			}else{
				//alert('ssssss');
				return true;
				$("#update_user").submit();
			}
		});
		
		
	});
</script>


</head>
<body id="sp">
<?php include("modules/header.php"); ?>


<div class="content_wrapper_sbr" id="contact">
    <div id="content" class="photographes">
        <?php /*?><div class="top reduit">
        	<div id="head-menu" style="float:left;">
            	<?php include("assets/menu/main-menu.php"); ?>
            </div>
        </div><?php */?>
        
	<style>
    #url_menu_bar{
         margin-top: 10px !important;
    }
    </style>
    <div id="url-menu" style="float:left;">
	<?php include("assets/menu/url_menu.php"); ?>
    </div>
<?php
	echo $tNGs->getErrorMsg();
?>
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
.loginForm input[type="submit"]{
    background-color: #9D286E;
    border: medium none;
    color: #F8C263;
    cursor: pointer;
    font-size: 18px;
    margin: 0 0 0 5px;
    padding: 0 10px 3px;
}
.loginForm td{
	line-height:20px;	
}

.faceboo_register{
	width:237px;
	height:44px;
	background:url(assets/images/fb-inscrire.png) no-repeat;
	float:left;
	margin-top:20px;
	margin-left:40px;
}
.faceboo_register:hover{
	background:url(assets/images/fb-inscrire_over.png) no-repeat;
}
.twitter_register{
	width:237px;
	height:44px;
	background:url(assets/images/tw-inscrire.png) no-repeat;
	float:left;
	margin-top:20px;
	margin-left:40px;
	margin-bottom:45px;
}
.twitter_register:hover{
	background:url(assets/images/tw-inscrire_over.png) no-repeat;
}
.step{
	background: #9D286E;
	border: none;
	font-size: 19px;
	color: #F8C263;
	text-align: center;
	width: 100px;
	float: left;	
	cursor:pointer;
}
</style>
	<div style="width:100%; float:left; margin-bottom:10px; margin-top:10px;">
    <?php if(isset($_REQUEST['BK_Update1_step2'])){
		
		$alert='';
		$nom = $_REQUEST['nom'];
		$prenom = $_REQUEST['prenom'];
		$siren = $_REQUEST['siren'];
		$email = $_REQUEST['email'];
		$password = $_REQUEST['password'];
		$level = $_REQUEST['level'];
		
		$nom_magazin = $_REQUEST['nom_magazin'];
		$adresse = $_REQUEST['adresse'];
		$telephone = $_REQUEST['telephone'];
		$region = $_REQUEST['region'];
		$ville = $_REQUEST['ville'];
		$department = $_REQUEST['department'];
		
		$categorie = $_REQUEST['categorie'];
		$sous_categorie = $_REQUEST['sous_categorie'];
		$subscribe = $_REQUEST['subscribe'];
		//$image = $_REQUEST['image'];
		
		$sql_pro  = "UPDATE utilisateur SET nom='".$nom."',prenom='".$prenom."',email='".$email."',password='".tNG_encryptString($password)."',level='".$level."',siren='".$siren."',region='".$region."',ville='".$ville."',department='".$department."',subscribe='".$subscribe."' WHERE id='".$_SESSION['id']."' AND oauth_provider='".$_SESSION['oauth_provider']."'";
		$result_pro  = mysql_query($sql_pro ) or die (mysql_error());
		
		if($result_pro){
			$sql_select1 = "SELECT * FROM utilisateur WHERE email='".$email."'";
			$query_select1 = mysql_query($sql_select1);
			$rs1=mysql_fetch_array($query_select1);
				if($rs1){
					$imagelocation = "assets/images/magasins/";
					$imagelocation2 = "assets/images/magasins/";
					//////Resize Picture
					error_reporting(0);
					$change="";
					$abc="";
					define ("MAX_SIZE","1024");
					function getExtension($str) {
						 $i = strrpos($str,".");
						 if (!$i) { return ""; }
						 $l = strlen($str) - $i;
						 $ext = substr($str,$i+1,$l);
					 return $ext;
					 }
					$errors=0;
					if($_SERVER["REQUEST_METHOD"] == "POST"){
						$image =$_FILES["image"]["name"];
						$uploadedfile = $_FILES['image']['tmp_name'];
						///Rename Image
						 if(!empty($image)){
							$i=1;
							$str="SELECT * FROM magazins WHERE logo ='".$image."' ";
							$sql=mysql_query($str);
							while ($row=mysql_fetch_array($sql)){
							$str="SELECT * FROM magazins WHERE logo ='".$image."' ";
							$sql=mysql_query($str);
							$num_rows = mysql_num_rows($sql);
								 if($num_rows > 0) {
									$nimage=$_FILES["image"]["name"]; 
									list ($iname,$ext)=preg_split('/[\.,]+/',$nimage);
									$image=$iname."_".$i.".".$ext;
								 }
								 
								$i++;
						}
						///End Rename/////////////////////////////////////////////////////////
					}
					if ($image) {
					$filename = stripslashes($image);
					$extension = getExtension($filename);
					$extension = strtolower($extension);
					if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")){
							$change='<div class="msgdiv">Unknown Image extension</div> ';
							$errors=1;
						}else{
						$size=filesize($_FILES['image']['tmp_name']);
					if ($size > MAX_SIZE*1440){
						$change='<div class="msgdiv">You have exceeded the size limit!</div> ';
						$errors=1;
					}
					if($extension=="jpg" || $extension=="jpeg" ){
						$uploadedfile = $_FILES['image']['tmp_name'];
						$src = imagecreatefromjpeg($uploadedfile);}
					else if($extension=="png"){
						$uploadedfile = $_FILES['image']['tmp_name'];
						$src = imagecreatefrompng($uploadedfile);
					}else {
						$src = imagecreatefromgif($uploadedfile);
					}
					echo $scr;
					list($width,$height)=getimagesize($uploadedfile);
					$newwidth=143;
					$newheight=($height/$width)*$newwidth;
					$tmp=imagecreatetruecolor($newwidth,$newheight);
					
					/*$newwidth1=50;
					$newheight1=($height/$width)*$newwidth1;
					$tmp1=imagecreatetruecolor($newwidth1,$newheight1);*/
					
					if($extension=="png" || $extension=="gif")
					{
						imagealphablending($tmp, false);
						imagesavealpha($tmp,true);
						$transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
						imagefilledrectangle($tmp, 0, 0, $newwidth, $newheight, $transparent);
						
						/*imagealphablending($tmp1, false);
						imagesavealpha($tmp1,true);
						$transparent = imagecolorallocatealpha($tmp1, 255, 255, 255, 127);
						imagefilledrectangle($tmp1, 0, 0, $newwidth, $newheight, $transparent);*/
					}
					
					imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
					
					/*imagecopyresampled($tmp1,$src,0,0,0,0,$newwidth1,$newheight1,$width,$height);*/
			
					$filename = $imagelocation.$image;
					/*$filename1 = $bigimagelocation.$image;*/
			
					if($extension=="png" || $extension=="gif")
					{
						imagepng($tmp,$filename);
						/*imagepng($tmp1,$filename1);*/
					}
					else{
						imagejpeg($tmp,$filename,90);
						/*imagejpeg($tmp1,$filename1,90);*/
					}
					
					imagedestroy($src);
					imagedestroy($tmp);
					/*imagedestroy($tmp1);*/
					}}
					
					}
			///////  Start Image 2 //////////////		
						
						if($_SERVER["REQUEST_METHOD"] == "POST"){
							
							$image2 =$_FILES["image2"]["name"];
							$uploadedfile2 = $_FILES['image2']['tmp_name'];
							///Rename Image
							 if(!empty($image2)){
								$i=1;
								$str2="SELECT * FROM magazins WHERE photo1 ='".$image2."' or logo='".$image2."' ";
								$sql2=mysql_query($str2);
								while ($row2=mysql_fetch_array($sql2)){
								$str2="SELECT * FROM magazins WHERE photo1 ='".$image2."' or logo='".$image2."' ";
								$sql2=mysql_query($str2);
								$num_rows2 = mysql_num_rows($sql2);
									 if($num_rows2 > 0) {
										$nimage2=$_FILES["image2"]["name"]; 
										list ($iname2,$ext2)=preg_split('/[\.,]+/',$nimage2);
										$image2=$iname2."_".$i.".".$ext2;
									 }
									 
									$i++;
							}
							///End Rename/////////////////////////////////////////////////////////
						}
						if ($image2) {
						$filename2 = stripslashes($image2);
						$extension2 = getExtension($filename2);
						$extension2 = strtolower($extension2);
						if (($extension2 != "jpg") && ($extension2 != "jpeg") && ($extension2 != "png") && ($extension2 != "gif")){
								$change2='<div class="msgdiv">Unknown Image extension</div> ';
								$errors=1;
							}else{
							$size2=filesize($_FILES['image2']['tmp_name']);
						if ($size2 > MAX_SIZE*1440){
							$change2='<div class="msgdiv">You have exceeded the size limit!</div> ';
							$errors=1;
						}
						if($extension2=="jpg" || $extension2=="jpeg" ){
							$uploadedfile2 = $_FILES['image2']['tmp_name'];
							$src2 = imagecreatefromjpeg($uploadedfile2);}
						else if($extension2=="png"){
							$uploadedfile2 = $_FILES['image2']['tmp_name'];
							$src2 = imagecreatefrompng($uploadedfile2);
						}else {
							$src2 = imagecreatefromgif($uploadedfile2);
						}
						echo $scr2;
						list($width2,$height2)=getimagesize($uploadedfile2);
						$newwidth2=143;
						$newheight2=($height2/$width2)*$newwidth2;
						$tmp2=imagecreatetruecolor($newwidth2,$newheight2);
						
						/*$newwidth1=50;
						$newheight1=($height/$width)*$newwidth1;
						$tmp1=imagecreatetruecolor($newwidth1,$newheight1);*/
						
						if($extension2=="png" || $extension2=="gif")
						{
							imagealphablending($tmp2, false);
							imagesavealpha($tmp2,true);
							$transparent2 = imagecolorallocatealpha($tmp2, 255, 255, 255, 127);
							imagefilledrectangle($tmp2, 0, 0, $newwidth2, $newheight2, $transparent2);
							
							/*imagealphablending($tmp1, false);
							imagesavealpha($tmp1,true);
							$transparent = imagecolorallocatealpha($tmp1, 255, 255, 255, 127);
							imagefilledrectangle($tmp1, 0, 0, $newwidth, $newheight, $transparent);*/
						}
						
						imagecopyresampled($tmp2,$src2,0,0,0,0,$newwidth2,$newheight2,$width2,$height2);
						
						/*imagecopyresampled($tmp1,$src,0,0,0,0,$newwidth1,$newheight1,$width,$height);*/
				
						$filename2 = $imagelocation2.$image2;
						/*$filename1 = $bigimagelocation.$image;*/
				
						if($extension2=="png" || $extension2=="gif")
						{
							imagepng($tmp2,$filename2);
							/*imagepng($tmp1,$filename1);*/
						}
						else{
							imagejpeg($tmp2,$filename2,90);
							/*imagejpeg($tmp1,$filename1,90);*/
						}
						
						imagedestroy($src2);
						imagedestroy($tmp2);
						/*imagedestroy($tmp1);*/
						}}
						
						}
						
					//Condition when no error extension of image
					if(!$errors) {
							$photo = $image;
							$photo2 = $image2;
							$sql_ma  = "INSERT INTO magazins(nom_magazin,adresse,logo,photo1,id_user,region,ville,department,categorie,sous_categorie,activate,payer,gratuit) VALUES ('".trim($nom_magazin)."','".$adresse."','".$photo."','".$photo2."','".$rs1['id']."','".$region."','".$ville."','".$department."','".$categorie."','".$sous_categorie."','1','1','1')";
							$result_ma  = mysql_query($sql_ma ) or die (mysql_error());
							
							
							$pro="SELECT
							utilisateur.id
							, utilisateur.nom
							, utilisateur.prenom
							, utilisateur.email
							, magazins.nom_magazin
							, magazins.adresse
							, magazins.siren
						FROM
							magasin3_bdd.magazins
							INNER JOIN magasin3_bdd.utilisateur 
								ON (magazins.id_user = utilisateur.id) WHERE magazins.id_magazin = 
						  (SELECT 
							MAX(id_magazin) AS id_magazin 
						  FROM
							magazins 
						  WHERE magazins.id_user ='".$rs1['id']."')";
								$query_pro=mysql_query($pro);
								$result_pro=mysql_fetch_array($query_pro);
								SendMail_Create_Magasin_Shpper($result_pro['email'],$result_pro['nom'],$result_pro['prenom'],$result_pro['nom_magazin']);
								SendMail_Create_Magasin_Ownner($result_pro['email'],$result_pro['nom'],$result_pro['prenom'],$result_pro['nom_magazin'],$result_pro['adresse'],$result_pro['siren']);
								
						}
					}
				SendMail_Shopper($email,$rs1['nom'],$rs1['prenom']);
				SendMail_Shopper_Webmaster($email,$rs1['nom'],$rs1['prenom'],$rs1['nom_magazin'],$rs1['adresse'],$rs1['siren'],$rs1['telephone']);
				$alert='Successfull';
				
				unset($_SESSION['id']);
				unset($_SESSION['oauth_id']);
				unset($_SESSION['username']);
				unset($_SESSION['email']);
				unset($_SESSION['password']);
				unset($_SESSION['oauth_provider']);
				unset($_SESSION['level']);
				echo '<script type="text/javascript">window.location="authetification.html";</script>';
				
		}
		
	}
	?>
    
    
   <?php if(isset($_REQUEST['BK_Insert1_step2'])){
		
		$alert='';
		$nom = $_REQUEST['nom'];
		$prenom = $_REQUEST['prenom'];
		$siren = $_REQUEST['siren'];
		$email = $_REQUEST['email'];
		$password = $_REQUEST['password'];
		$level = $_REQUEST['level'];
		
		$nom_magazin = $_REQUEST['nom_magazin'];
		$adresse = $_REQUEST['adresse'];
		$telephone = $_REQUEST['telephone'];
		$region = $_REQUEST['region'];
		$ville = $_REQUEST['ville'];
		$department = $_REQUEST['department'];
		$categorie = $_REQUEST['categorie'];
		$sous_categorie = $_REQUEST['sous_categorie'];
		$subscribe = $_REQUEST['subscribe'];
		$invite = $_REQUEST['invite'];
		//$image = $_REQUEST['image'];
		
		
		$sql_select = "SELECT email FROM utilisateur WHERE email='".$email."'";
		$query_select = mysql_query($sql_select);
		$rs=mysql_fetch_array($query_select);
		
		if($rs==''){
			$sql_pro = '';
			$result_pro  = '';
			
			$sql_pro  = "INSERT INTO utilisateur(nom,prenom,email,password,level,siren,region,ville,department,invite,subscribe) VALUES ('".trim($nom)."','".$prenom."','".$email."','".$password."','".$level."','".$siren."','".$region."','".$ville."','".$department."','".$invite."','".$subscribe."')";
			//echo $sql_pro;
			$result_pro  = mysql_query($sql_pro ) or die (mysql_error());
			
			if($result_pro){
				//$alert='Successfull';
				
				$sql_select1 = "SELECT * FROM utilisateur WHERE email='".$email."'";
				$query_select1 = mysql_query($sql_select1);
				$rs1=mysql_fetch_array($query_select1);
					if($rs1){
						
						$imagelocation = "assets/images/magasins/";
						$imagelocation2 = "assets/images/magasins/";
						
						//////Resize Picture
						error_reporting(0);
						$change="";
						$abc="";
						define ("MAX_SIZE","1024");
						function getExtension($str) {
							 $i = strrpos($str,".");
							 if (!$i) { return ""; }
							 $l = strlen($str) - $i;
							 $ext = substr($str,$i+1,$l);
						 return $ext;
						 }
						$errors=0;
						
						
						if($_SERVER["REQUEST_METHOD"] == "POST"){
							
							$image =$_FILES["image"]["name"];
							$uploadedfile = $_FILES['image']['tmp_name'];
							///Rename Image
							 if(!empty($image)){
								$i=1;
								$str="SELECT * FROM magazins WHERE logo ='".$image."' or photo1='".$image."' ";
								$sql=mysql_query($str);
								while ($row=mysql_fetch_array($sql)){
								$str="SELECT * FROM magazins WHERE logo ='".$image."' or photo1='".$image."' ";
								$sql=mysql_query($str);
								$num_rows = mysql_num_rows($sql);
									 if($num_rows > 0) {
										$nimage=$_FILES["image"]["name"]; 
										list ($iname,$ext)=preg_split('/[\.,]+/',$nimage);
										$image=$iname."_".$i.".".$ext;
									 }
									 
									$i++;
							}
							///End Rename/////////////////////////////////////////////////////////
						}
						if ($image) {
						$filename = stripslashes($image);
						$extension = getExtension($filename);
						$extension = strtolower($extension);
						if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")){
								$change='<div class="msgdiv">Unknown Image extension</div> ';
								$errors=1;
							}else{
							$size=filesize($_FILES['image']['tmp_name']);
						if ($size > MAX_SIZE*1440){
							$change='<div class="msgdiv">You have exceeded the size limit!</div> ';
							$errors=1;
						}
						if($extension=="jpg" || $extension=="jpeg" ){
							$uploadedfile = $_FILES['image']['tmp_name'];
							$src = imagecreatefromjpeg($uploadedfile);}
						else if($extension=="png"){
							$uploadedfile = $_FILES['image']['tmp_name'];
							$src = imagecreatefrompng($uploadedfile);
						}else {
							$src = imagecreatefromgif($uploadedfile);
						}
						echo $scr;
						list($width,$height)=getimagesize($uploadedfile);
						$newwidth=143;
						$newheight=($height/$width)*$newwidth;
						$tmp=imagecreatetruecolor($newwidth,$newheight);
						
						/*$newwidth1=50;
						$newheight1=($height/$width)*$newwidth1;
						$tmp1=imagecreatetruecolor($newwidth1,$newheight1);*/
						
						if($extension=="png" || $extension=="gif")
						{
							imagealphablending($tmp, false);
							imagesavealpha($tmp,true);
							$transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
							imagefilledrectangle($tmp, 0, 0, $newwidth, $newheight, $transparent);
							
							/*imagealphablending($tmp1, false);
							imagesavealpha($tmp1,true);
							$transparent = imagecolorallocatealpha($tmp1, 255, 255, 255, 127);
							imagefilledrectangle($tmp1, 0, 0, $newwidth, $newheight, $transparent);*/
						}
						
						imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
						
						/*imagecopyresampled($tmp1,$src,0,0,0,0,$newwidth1,$newheight1,$width,$height);*/
				
						$filename = $imagelocation.$image;
						/*$filename1 = $bigimagelocation.$image;*/
				
						if($extension=="png" || $extension=="gif")
						{
							imagepng($tmp,$filename);
							/*imagepng($tmp1,$filename1);*/
						}
						else{
							imagejpeg($tmp,$filename,90);
							/*imagejpeg($tmp1,$filename1,90);*/
						}
						
						imagedestroy($src);
						imagedestroy($tmp);
						/*imagedestroy($tmp1);*/
						}}
						
						}
						
			///////  Start Image 2 //////////////		
						
						if($_SERVER["REQUEST_METHOD"] == "POST"){
							
							$image2 =$_FILES["image2"]["name"];
							$uploadedfile2 = $_FILES['image2']['tmp_name'];
							///Rename Image
							 if(!empty($image2)){
								$i=1;
								$str2="SELECT * FROM magazins WHERE photo1 ='".$image2."' or logo='".$image2."' ";
								$sql2=mysql_query($str2);
								while ($row2=mysql_fetch_array($sql2)){
								$str2="SELECT * FROM magazins WHERE photo1 ='".$image2."' or logo='".$image2."' ";
								$sql2=mysql_query($str2);
								$num_rows2 = mysql_num_rows($sql2);
									 if($num_rows2 > 0) {
										$nimage2=$_FILES["image2"]["name"]; 
										list ($iname2,$ext2)=preg_split('/[\.,]+/',$nimage2);
										$image2=$iname2."_".$i.".".$ext2;
									 }
									 
									$i++;
							}
							///End Rename/////////////////////////////////////////////////////////
						}
						if ($image2) {
						$filename2 = stripslashes($image2);
						$extension2 = getExtension($filename2);
						$extension2 = strtolower($extension2);
						if (($extension2 != "jpg") && ($extension2 != "jpeg") && ($extension2 != "png") && ($extension2 != "gif")){
								$change2='<div class="msgdiv">Unknown Image extension</div> ';
								$errors=1;
							}else{
							$size2=filesize($_FILES['image2']['tmp_name']);
						if ($size2 > MAX_SIZE*1440){
							$change2='<div class="msgdiv">You have exceeded the size limit!</div> ';
							$errors=1;
						}
						if($extension2=="jpg" || $extension2=="jpeg" ){
							$uploadedfile2 = $_FILES['image2']['tmp_name'];
							$src2 = imagecreatefromjpeg($uploadedfile2);}
						else if($extension2=="png"){
							$uploadedfile2 = $_FILES['image2']['tmp_name'];
							$src2 = imagecreatefrompng($uploadedfile2);
						}else {
							$src2 = imagecreatefromgif($uploadedfile2);
						}
						echo $scr2;
						list($width2,$height2)=getimagesize($uploadedfile2);
						$newwidth2=143;
						$newheight2=($height2/$width2)*$newwidth2;
						$tmp2=imagecreatetruecolor($newwidth2,$newheight2);
						
						/*$newwidth1=50;
						$newheight1=($height/$width)*$newwidth1;
						$tmp1=imagecreatetruecolor($newwidth1,$newheight1);*/
						
						if($extension2=="png" || $extension2=="gif")
						{
							imagealphablending($tmp2, false);
							imagesavealpha($tmp2,true);
							$transparent2 = imagecolorallocatealpha($tmp2, 255, 255, 255, 127);
							imagefilledrectangle($tmp2, 0, 0, $newwidth2, $newheight2, $transparent2);
							
							/*imagealphablending($tmp1, false);
							imagesavealpha($tmp1,true);
							$transparent = imagecolorallocatealpha($tmp1, 255, 255, 255, 127);
							imagefilledrectangle($tmp1, 0, 0, $newwidth, $newheight, $transparent);*/
						}
						
						imagecopyresampled($tmp2,$src2,0,0,0,0,$newwidth2,$newheight2,$width2,$height2);
						
						/*imagecopyresampled($tmp1,$src,0,0,0,0,$newwidth1,$newheight1,$width,$height);*/
				
						$filename2 = $imagelocation2.$image2;
						/*$filename1 = $bigimagelocation.$image;*/
				
						if($extension2=="png" || $extension2=="gif")
						{
							imagepng($tmp2,$filename2);
							/*imagepng($tmp1,$filename1);*/
						}
						else{
							imagejpeg($tmp2,$filename2,90);
							/*imagejpeg($tmp1,$filename1,90);*/
						}
						
						imagedestroy($src2);
						imagedestroy($tmp2);
						/*imagedestroy($tmp1);*/
						}}
						
						}
			
				
						
						//Condition when no error extension of image
						if(!$errors) {
							$photo = $image;
							$photo2 = $image2;
							$sql_ma  = "INSERT INTO magazins(nom_magazin,adresse,logo,photo1,id_user,region,ville,department,categorie,sous_categorie,activate,payer,gratuit) VALUES ('".trim($nom_magazin)."','".$adresse."','".$photo."','".$photo2."','".$rs1['id']."','".$region."','".$ville."','".$department."','".$categorie."','".$sous_categorie."','1','1','1')";
							$result_ma  = mysql_query($sql_ma ) or die (mysql_error());
							
							
							$pro="SELECT
							utilisateur.id
							, utilisateur.nom
							, utilisateur.prenom
							, utilisateur.email
							, magazins.nom_magazin
							, magazins.adresse
							, magazins.siren
						FROM
							magasin3_bdd.magazins
							INNER JOIN magasin3_bdd.utilisateur 
								ON (magazins.id_user = utilisateur.id) WHERE magazins.id_magazin = 
						  (SELECT 
							MAX(id_magazin) AS id_magazin 
						  FROM
							magazins 
						  WHERE magazins.id_user ='".$rs1['id']."')";
								$query_pro=mysql_query($pro);
								$result_pro=mysql_fetch_array($query_pro);
								SendMail_Create_Magasin_Shpper($result_pro['email'],$result_pro['nom'],$result_pro['prenom'],$result_pro['nom_magazin']);
								SendMail_Create_Magasin_Ownner($result_pro['email'],$result_pro['nom'],$result_pro['prenom'],$result_pro['nom_magazin'],$result_pro['adresse'],$result_pro['siren']);
								
						}
					}
				SendMail_Shopper($email,$rs1['nom'],$rs1['prenom']);
				SendMail_Shopper_Webmaster($email,$rs1['nom'],$rs1['prenom'],$rs1['nom_magazin'],$rs1['adresse'],$rs1['siren'],$rs1['telephone']);
				$alert='Successfull';
			}
		}
		else
		{
			$alert='Your Mail have already';
		}
		
		
		
	}
	?>
    
    
    
    
    
    
	<?php if(isset($_REQUEST['btn_Insert1'])){?>
		<?php
            $alert='';
            $nom = $_REQUEST['nom'];
            $prenom = $_REQUEST['prenom'];
            $email = $_REQUEST['email'];
            $password = $_REQUEST['password'];
            $level = $_REQUEST['level'];
			
            $categorie = $_REQUEST['categorie'];
			$sous_categorie = $_REQUEST['sous_categorie'];
            $nom_magazin = $_REQUEST['nom_magazin'];
            $siren = $_REQUEST['siren'];
            $region = $_REQUEST['region'];
			$department = $_REQUEST['department'];
            $ville = $_REQUEST['ville'];
            $adresse = $_REQUEST['adresse'];
            $code_postal = $_REQUEST['code_postal'];
            $telephone = $_REQUEST['telephone'];
			$subscribe = $_REQUEST['subscribe'];			
			
			$condition='';
			$value='';
			
			if($_REQUEST['level']=='1'){
				$condition.=',categorie, sous_categorie ,nom_magazin ,siren ,region ,ville ,adresse ,code_postal ,telephone ,department ';
				$value.=",'".$categorie."', '".$sous_categorie."','".$nom_magazin."','".$siren."','".$region."','".$ville."','".$adresse."','".$code_postal."','".$telephone."','".$department."'";
			}elseif($_REQUEST['level']=='2'){
				$condition.=',siren ,telephone ';
				$value.=",'".$siren."','".$telephone."'";
			}
            
            $sql_select = "SELECT email FROM utilisateur WHERE email='".$email."'";
            $query_select = mysql_query($sql_select);
            $rs=mysql_fetch_array($query_select);
            
            if($rs==''){
                $sql_pro = '';
                $result_pro  = '';
                
                $sql_pro  = "INSERT INTO utilisateur(nom,prenom,email,password,level,subscribe ".$condition.") VALUES ('".trim($nom)."','".$prenom."','".$email."','".$password."','".$level."','".$subscribe."' ".$value.")";
                //echo $sql_pro;
				$result_pro  = mysql_query($sql_pro ) or die (mysql_error());
				
                if($result_pro){
                    $alert='Successfull';
					
					$sql_select1 = "SELECT * FROM utilisateur WHERE email='".$email."'";
					$query_select1 = mysql_query($sql_select1);
					$rs1=mysql_fetch_array($query_select1);
										
					if($rs1['level']=='1'){//Commercants Or Shopper
						SendMail_Shopper($email,$rs1['nom'],$rs1['prenom']);
						SendMail_Shopper_Webmaster($email,$rs1['nom'],$rs1['prenom'],$rs1['nom_magazin'],$rs1['adresse'],$rs1['siren'],$rs1['telephone']);				
					}elseif($rs1['level']=='2'){//Photographe Or Photographer
						SendMail_Photographe($email,$rs1['nom'],$rs1['prenom']);
						SendMail_Photographe_Webmaster($email,$rs1['nom'],$rs1['prenom'],$rs1['siren'],$rs1['telephone']);	
					}elseif($rs1['level']=='3'){//User
						SendMail_User($email,$rs1['id'],$rs1['nom'],$rs1['prenom']);
					}
                }
            }
            else
            {
                $alert='Your Mail have already';
            }
        ?>
    <?php }?>
    
    <?php if (isset($_SESSION['id'])) { ?>
		
        <?php if(isset($_REQUEST['level'])){
			$_SESSION['level']=$_REQUEST['level'];
		}
		?>
    
    
    
    	<?php if(isset($_REQUEST['KT_Update1'])){
		$sql_pro = '';
		$result_pro  = '';
		$nom = $_REQUEST['nom'];
		$prenom = $_REQUEST['prenom'];
		$email = $_REQUEST['email'];
		$password = $_REQUEST['password'];
		$level = $_REQUEST['level'];
		
		$categorie = $_REQUEST['categorie'];
		$sous_categorie = $_REQUEST['sous_categorie'];
		$nom_magazin = $_REQUEST['nom_magazin'];
		$siren = $_REQUEST['siren'];
		$region = $_REQUEST['region'];
		$ville = $_REQUEST['ville'];
		$department = $_REQUEST['department'];
		$adresse = $_REQUEST['adresse'];
		$code_postal = $_REQUEST['code_postal'];
		$telephone = $_REQUEST['telephone'];
		$subscribe = $_REQUEST['subscribe'];
		$id=$_SESSION['id'];
		$condition='';
		if($_SESSION['level']=='1'){
			$condition.=",categorie='".$categorie."',sous_categorie='".$sous_categorie."',nom_magazin='".$nom_magazin."',siren='".$siren."',region='".$region."',ville='".$ville."',adresse='".$adresse."',code_postal='".$code_postal."',telephone='".$telephone."',department='".$department."' ";
			
		}elseif($_SESSION['level']=='2'){
			$condition.=",siren='".$siren."',telephone='".$telephone."' ";
			
		}
		
		$sql_pro  = "UPDATE utilisateur SET nom='".$nom."',prenom='".$prenom."',email='".$email."',password='".tNG_encryptString($password)."',level='".$level."',subscribe='".$subscribe."' ".$condition." WHERE id='".$_SESSION['id']."' AND oauth_provider='".$_SESSION['oauth_provider']."'";
		$result_pro  = mysql_query($sql_pro ) or die (mysql_error());
		
		if($result_pro){
			
			$sql_select1 = "SELECT * FROM utilisateur WHERE email='".$email."'";
			$query_select1 = mysql_query($sql_select1);
			$rs1=mysql_fetch_array($query_select1);
								
			if($rs1['level']=='1'){//Commercants Or Shopper
				SendMail_Shopper($email,$rs1['nom'],$rs1['prenom']);
				SendMail_Shopper_Webmaster($email,$rs1['nom'],$rs1['prenom'],$rs1['nom_magazin'],$rs1['adresse'],$rs1['siren'],$rs1['telephone']);				
			}elseif($rs1['level']=='2'){//Photographe Or Photographer
				SendMail_Photographe($email,$rs1['nom'],$rs1['prenom']);
				SendMail_Photographe_Webmaster($email,$rs1['nom'],$rs1['prenom'],$rs1['siren'],$rs1['telephone']);	
			}elseif($rs1['level']=='3'){//User
				SendMail_User($email,$rs1['id'],$rs1['nom'],$rs1['prenom']);
			}

			
			unset($_SESSION['id']);
			unset($_SESSION['oauth_id']);
			unset($_SESSION['username']);
			unset($_SESSION['email']);
			unset($_SESSION['password']);
			unset($_SESSION['oauth_provider']);
			unset($_SESSION['level']);
			echo '<script type="text/javascript">window.location="authetification.html";</script>';
		}
	}?>

    <div style="width:100%; float:left; padding-left:2%;" class="loginForm">
        	<h3><?php echo $xml->inscription_utilisateurs ?>:</h3>
		<?php if($_SESSION['level'] != "1"){?>
            <form method="post" id="update_user" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
        	<table cellpadding="0" cellspacing="0" border="0" style="margin-top:25px; width:60%;" align="center">
            	<tr>
                	<td>
                    	<label for="Level">Profil <span style="color:red;">*</span> :</label> 
                    </td>
                	<td>
                        <select name="level" id="level">
                        <option value="3" <?php echo ($_SESSION['level'] == "3") ? 'selected="selected"' : "";?>>Utilisateur</option>
                        <option value="1" <?php echo ($_SESSION['level'] == "1") ? 'selected="selected"' : "";?>>Commerçant</option>
                        <option value="2" <?php echo ($_SESSION['level'] == "2") ? 'selected="selected"' : "";?>>Photographe</option>
                      	</select>
                        <?php echo $tNGs->displayFieldError("utilisateur", "civilite"); ?>
                    </td>
                </tr>
                <tr>
                	<td>
                    	<label for="nom"><?php echo $xml-> Nom ?> <span style="color:red;">*</span> :</label>  
                    </td>
                	<td> 
                        <input type="text" name="nom" id="nom" value="<?php echo $_SESSION['username']; ?>" />
                        <?php echo $tNGs->displayFieldHint("nom");?> <?php echo $tNGs->displayFieldError("utilisateur", "nom"); ?> 
                    </td>
                </tr>
                <tr>
                	<td>
                    	<label for="prenom"><?php echo $xml->Prenom ?> <span style="color:red;">*</span> :</label> 
                    </td>
                	<td>
                        <input type="text" name="prenom" id="prenom" value="<?php echo $prenom; ?>" />
                        <?php echo $tNGs->displayFieldHint("prenom");?> <?php echo $tNGs->displayFieldError("utilisateur", "prenom"); ?> 
                    </td>
                </tr>
                
                <?php if($_SESSION['level'] == "2"){?>
                
                <tr>
                    <td>
                    <label for="siren">Siren :</label> 
                    </td>
                	<td>
               		<input type="text" name="siren" id="siren" value="<?php echo $siren; ?>" size="32" />
                    <?php echo $tNGs->displayFieldHint("siren");?> <?php echo $tNGs->displayFieldError("utilisateur", "siren"); ?> 
                    </td>
                </tr>
                <tr>
                	<td>
                    <label for="telephone"><?php echo $xml->Telephone ?> :</label> 
                    </td>
                	<td>
                	<input type="text" name="telephone" id="telephone" value="<?php echo $telephone; ?>" size="32" />
                    <?php echo $tNGs->displayFieldHint("telephone");?> <?php echo $tNGs->displayFieldError("utilisateur", "telephone"); ?>
                    </td>
                </tr>
                <?php }?>
                
                <tr>
                	<td>
                        <label for="email"><?php echo $xml-> Email ?> <span style="color:red;">*</span> :</label>   
                    </td>
                	<td>
                        <input type="text" name="email" id="email" value="<?php echo $_SESSION['email']; ?>" />
                        <?php echo $tNGs->displayFieldHint("email");?> <?php echo $tNGs->displayFieldError("utilisateur", "email"); ?>
                    </td>
                </tr>
                <tr>
                	<td>
                        <label for="password"><?php echo $xml->Mot_passe?> <span style="color:red;">*</span> :</label>   
                    </td>
                	<td>
                        <input type="password" name="password" id="password" value="" />
                        <?php echo $tNGs->displayFieldHint("password");?> <?php echo $tNGs->displayFieldError("utilisateur", "password"); ?>
                    </td>
                </tr>
                <tr>
                	<td>
                    	<label for="re_password"><?php echo $xml->Retaper_le_Mot_de_passe ?> <span style="color:red;">*</span> :</label>   
                    </td>
                	<td>
                        <input type="password" name="re_password" id="re_password" value="" />
                    </td>
                </tr>
                
                <tr>
                	<td><label for="catch">( <?php echo $val1;?> + <?php echo $val2;?> ) <span style="color:red;">*</span></label></td>
                	<td>
                    	= <input type="text" id="val3" name="val3" value="" style="width:78px;"/>
                    </td>
                </tr>  
                <tr>
                	<td><br />
            			<input type="submit" name="KT_Update1" id="KT_Update1" value="<?php echo $xml->Enregistrer ?>"/>
            		</td>
                </tr>
            </table>

            </form>
            
        <?php } else{?>
        
        	<form method="post" id="update_user2" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>"  enctype="multipart/form-data">
        	<div class="step1">
            	<table cellpadding="0" cellspacing="0" border="0" style="margin-top:25px; width:60%;" align="center">
            	<tr>
                	<td>
                    	<label for="Level">Profil <span style="color:red;">*</span> :</label> 
                    </td>
                	<td>
                        <select name="level" id="level">
                        <option value="3" <?php echo ($_SESSION['level'] == "3") ? 'selected="selected"' : "";?>>Utilisateur</option>
                        <option value="1" <?php echo ($_SESSION['level'] == "1") ? 'selected="selected"' : "";?>>Commerçant</option>
                        <option value="2" <?php echo ($_SESSION['level'] == "2") ? 'selected="selected"' : "";?>>Photographe</option>
                      	</select>
                        <?php echo $tNGs->displayFieldError("utilisateur", "civilite"); ?>
                    </td>
                </tr>
                <tr>
                	<td>
                    	<label for="nom"><?php echo $xml-> Nom ?> <span style="color:red;">*</span> :</label>  
                    </td>
                	<td> 
                        <input type="text" name="nom" id="nom" value="<?php echo $_SESSION['username']; ?>" />
                        <?php echo $tNGs->displayFieldHint("nom");?> <?php echo $tNGs->displayFieldError("utilisateur", "nom"); ?> 
                    </td>
                </tr>
                <tr>
                	<td>
                    	<label for="prenom"><?php echo $xml->Prenom ?> <span style="color:red;">*</span> :</label> 
                    </td>
                	<td>
                        <input type="text" name="prenom" id="prenom" value="<?php echo $prenom; ?>" />
                        <?php echo $tNGs->displayFieldHint("prenom");?> <?php echo $tNGs->displayFieldError("utilisateur", "prenom"); ?> 
                    </td>
                </tr>
                <tr>
                    <td>
                    <label for="siren"><?php echo $xml-> Siren ?>  <span style="color:red;">*</span>:</label>
                    </td>
                	<td>
               		<input type="text" name="siren" id="siren" value="<?php echo $siren; ?>" size="32" maxlength="14" />
                    <?php echo $tNGs->displayFieldHint("siren");?> <?php echo $tNGs->displayFieldError("utilisateur", "siren"); ?> 
                    </td>
                </tr>
                <tr>
                	<td>
                    <label for="telephone"><?php echo $xml->Telephone ?> :</label>
                    </td>
                	<td>
                	<input type="text" name="telephone" id="telephone" value="<?php echo $telephone; ?>" size="32" />
                    <?php echo $tNGs->displayFieldHint("telephone");?> <?php echo $tNGs->displayFieldError("utilisateur", "telephone"); ?>
                    </td>
                </tr>
                <tr>
                	<td>
                        <label for="email"><?php echo $xml-> Email ?> <span style="color:red;">*</span> :</label>   
                    </td>
                	<td>
                        <input type="text" name="email" id="email" value="<?php echo $_SESSION['email']; ?>" />
                        <?php echo $tNGs->displayFieldHint("email");?> <?php echo $tNGs->displayFieldError("utilisateur", "email"); ?>
                    </td>
                </tr>
                <tr>
                	<td>
                        <label for="password"><?php echo $xml->Mot_passe?> <span style="color:red;">*</span> :</label>   
                    </td>
                	<td>
                        <input type="password" name="password" id="password" value="" />
                        <?php echo $tNGs->displayFieldHint("password");?> <?php echo $tNGs->displayFieldError("utilisateur", "password"); ?>
                    </td>
                </tr>
                <tr>
                	<td>
                    	<label for="re_password"><?php echo $xml->Retaper_le_Mot_de_passe ?> <span style="color:red;">*</span> :</label>   
                    </td>
                	<td>
                        <input type="password" name="re_password" id="re_password" value="" />
                    </td>
                </tr>  
                <tr>
                	<td><label for="catch">( <?php echo $val1;?> + <?php echo $val2;?> ) <span style="color:red;">*</span></label></td>
                	<td>
                    	= <input type="text" id="val3" name="val3" value="" style="width:78px;"/>
                    </td>
                </tr> 
                </table>
                <input type="botton" name="next_step" id="next_step" class="step" value="Suivant"/>
            </div>
            
            <div class="step2" style="display:none;">
            
                <table cellpadding="0" cellspacing="0" border="0" style="margin-top:25px; width:60%;" align="center">
                <tr>
                    <td>
                    <label for="nom_magazin"><?php echo $xml->Nom_du_magasin ; ?> <span style="color:red;">*</span> : </label>
                    </td>
                    <td>
                    <input type="text" name="nom_magazin" id="nom_magazin" value="<?php echo $nom_magazin; ?>" size="32" />
                    <?php echo $tNGs->displayFieldHint("nom_magazin");?> <?php echo $tNGs->displayFieldError("utilisateur", "nom_magazin"); ?>
                    </td>
                </tr>
                <tr>
                    <td>
                    <label for="region"><?php echo $xml-> Region ?>  <span style="color:red;">*</span> : </label>
                    </td>
                    <td>
                    <select name="region" id="region1" onchange="ajax('ajax/department.php?default=<?php echo $row_rsmagazins['department']; ?>&id_region='+this.value,'#department');">
                    	<option value=""><?php echo $xml->selectionner ?></option>
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
                    </td>
                </tr>
                
                <tr>
                    <td>
                    <label for="department">Department <span style="color:red;">*</span> :</label>
                    </td>
                    <td>
                    <select name="department" id="department" onchange="ajax('ajax/ville.php?default=<?php echo $row_rsmagazins['ville']; ?>&id_departement='+this.value,'#ville');">
                        <option value="">Department</option>  
                        
                        <?php 
                        mysql_select_db($database_magazinducoin, $magazinducoin);
                        $query_department = "SELECT * FROM departement WHERE id_departement='".$row_rsmagazins['department']."' ORDER BY nom_departement ASC";
                        $department = mysql_query($query_department, $magazinducoin) or die(mysql_error());
                        $row_department = mysql_fetch_array($department);
                        //$totalRows_regions = mysql_num_rows($regions);
                        if($row_rsutilisateur['department']!=''){
                        ?>
                            <option value="<?php echo $row_department['id_departement']?>"<?php if (!(strcmp($row_department['id_departement'], $row_rsmagazins['department']))) {echo "SELECTED";} ?> title="<?php echo ($row_department['nom_departement']); ?>"><?php echo ($row_department['nom_departement']); ?></option>
                        <?php }?>   
                        
                
                    </select>
                    <?php echo $tNGs->displayFieldError("magazins", "department", $cnt1); ?>
                    </td>
                </tr>
                
                <tr>
                    <td>
                    <label for="ville"><?php echo $xml->Ville ?>  <span style="color:red;">*</span> : </label>
                    </td>
                    <td>
                    <select name="ville" id="ville">
                    	<option value=""><?php echo $xml->selectionner ?></option>   
                    </select>
                    <?php echo $tNGs->displayFieldError("magazins", "ville", $cnt1); ?>
                    </td>
                </tr>
                <tr>
                    <td>
                    <label for="adresse"><?php echo $xml-> Adresse ?> : </label>
                    </td>
                    <td>
                    <input type="text" name="adresse" id="adresse" value="<?php echo $adresse; ?>" size="32" />
                    <?php echo $tNGs->displayFieldHint("adresse");?> <?php echo $tNGs->displayFieldError("utilisateur", "adresse"); ?>
                    </td>
                </tr>
                <tr>
                	<td><div style="width:2px; height:5px; float:left;">&nbsp;</div></td>
                	<td></td>
                </tr>
                <tr>
                    <td>
                    <label for="logo">Logo : </label>
                    </td>
                    <td>
                    <input type="file" name="image" id="image" value="<?php echo $photo; ?>" size="32" />
                    <?php echo $tNGs->displayFieldHint("image");?> <?php echo $tNGs->displayFieldError("utilisateur", "image"); ?>
                    </td>
                </tr>
                <tr>
                    <td>
                    <label for="photo">Photo : </label>
                    </td>
                    <td>
                    <input type="file" name="image2" id="image2" value="<?php echo $image2; ?>" size="32" />
                    <?php echo $tNGs->displayFieldHint("image");?> <?php echo $tNGs->displayFieldError("utilisateur", "image2"); ?>
                    </td>
                </tr>
                <tr>
                    <td>
                    <label for="cateory">Catégory : </label>
                    </td>
                    <td>
                     <select name="categorie" id="categorie">
                    	<option value=""><?php echo $xml->selectionner ?></option>
						<?php 
                        do {  
                        ?>
                    	<option value="<?php echo $row_categories['cat_id']?>"<?php if (!(strcmp($row_categories['cat_id'], $row_default['cat_name']))) {echo "SELECTED";} ?> title="<?php echo ($row_categories['cat_name']); ?>"><?php echo ($row_categories['cat_name']); ?></option>
                    	<?php
                    	} while ($row_categories = mysql_fetch_assoc($categories));
                    		$rows = mysql_num_rows($categories);
                    			if($rows > 0) {
                    			mysql_data_seek($categories, 0);
                    			$row_categories = mysql_fetch_assoc($categories);
                   		}
                    ?>
                    </select>
                    <?php echo $tNGs->displayFieldError("magazins", "categorie", $cnt1); ?>
                    </td>
                </tr>
                
                <tr>
                	<td><div style="width:2px; height:5px; float:left;">&nbsp;</div></td>
                	<td></td>
                </tr>
                 <tr>
                	<td>
                    	<label for="subscribe">Newsletter </label> 
                    </td>
                	<td> 
                        <input type="checkbox" name="subscribe" id="subscribe" value="0"/>
                    </td>
                </tr>
            </table><br />
            	<input type="botton" name="back_step" id="back_step" class="step" value="Retour"/>
            </div>
            
            <div id="send" style="text-align:center;">
            <!--<input type="submit" name="BK_Insert1" id="BK_Insert1" value="old"/>-->
            <!--<input type="submit" name="BK_Update1_step1" id="BK_Update1_step1" value="Skip"/>-->
            <input type="submit" name="BK_Update1_step2" id="BK_Update1_step2" class="step" value="<?php echo $xml->Enregistrer ?>"/>
            </div>
        	</form>
            
        <?php }?>    
        </div>
    
    
    
    
    <?php } else { ?>
    	<div style="width:33%; float:left; margin-left:2%;">
        	<h3>Inscription :</h3><br /><br />
            <?php
				$ur_level='';
				if(($_REQUEST['level']!='')){
					$ur_level.='&level='.$_REQUEST['level'];
				}
			?>
            
        	<a href="?login&oauth_provider=facebook<?php echo $ur_level; ?>" class="faceboo_register"></a>
            <a href="?login&oauth_provider=twitter<?php echo $ur_level; ?>" class="twitter_register"></a>
        </div>
        <div style="width:62%; float:left; border-left:1px dotted #666666; padding-left:2%;" class="loginForm">
        	<h3><?php echo $xml->inscription_utilisateurs ?>:</h3>
           
            
            <?php
				if($alert=='Successfull'){
					echo '<h4>Votre inscription commerçant a bien été prise en compte. Notre équipe vérifie son bon contenu et validera le compte sous 24 heures. Vous recevrez un mail dès que le compte sera validé.
						L&acute;équipe Magasinducoin</h4>';
				}
				if($alert!='Successfull'){
					if($_REQUEST['level']=='1'){
			?>
            
            <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" enctype="multipart/form-data">
            <div class="step1">
            	<!--<div id="next_step">Next</div>-->
            <table cellpadding="0" cellspacing="0" border="0" style="margin-top:25px; width:100%;">
            	<tr>
                	<td>
                    	<label for="Level">Profil <span style="color:red;">*</span> :</label> 
                    </td>
                	<td>
                        <select name="level" id="level">
                        <option value="3" <?php echo ($_REQUEST['level'] == "3") ? 'selected="selected"' : "";?>>Utilisateur</option>
                        <option value="1" <?php echo ($_REQUEST['level'] == "1") ? 'selected="selected"' : "";?>>Commerçant</option>
                        <option value="2" <?php echo ($_REQUEST['level'] == "2") ? 'selected="selected"' : "";?>>Photographe</option>
                      	</select>
                        <?php echo $tNGs->displayFieldError("utilisateur", "civilite"); ?>
                    </td>
                </tr>
                <tr>
                	<td>
                    	<label for="nom"><?php echo $xml-> Nom ?> <span style="color:red;">*</span> :</label>  
                    </td>
                	<td>
                        <input type="text" name="nom" id="nom" value="<?php echo $nom; ?>" />
                        <?php echo $tNGs->displayFieldHint("nom");?> <?php echo $tNGs->displayFieldError("utilisateur", "nom"); ?> 
                    </td>
                </tr>
                <tr>
                	<td>
                    	<label for="prenom"><?php echo $xml->Prenom ?> <span style="color:red;">*</span> :</label>  
                    </td>
                	<td>
                        <input type="text" name="prenom" id="prenom" value="<?php echo $prenom; ?>" />
                        <?php echo $tNGs->displayFieldHint("prenom");?> <?php echo $tNGs->displayFieldError("utilisateur", "prenom"); ?> 
                    </td>
                </tr>
                <tr>
                    <td>
                    <label for="siren">Siren <span style="color:red;">*</span> :</label>
                    </td>
                	<td>
               		<input type="text" name="siren" id="siren" value="<?php echo $siren; ?>" size="32" maxlength="14" />
                    <?php echo $tNGs->displayFieldHint("siren");?> <?php echo $tNGs->displayFieldError("utilisateur", "siren"); ?> 
                    </td>
                </tr>
                <tr>
                	<td>
                    <label for="telephone"><?php echo $xml->Telephone ?> :</label>
                    </td>
                	<td>
                	<input type="text" name="telephone" id="telephone" value="<?php echo $telephone; ?>" size="32" />
                    <?php echo $tNGs->displayFieldHint("telephone");?> <?php echo $tNGs->displayFieldError("utilisateur", "telephone"); ?>
                    </td>
                </tr>
                <!--<tr>
                	<td>
                        <label for="email"><?php echo $xml-> Email ?> <span style="color:red;">*</span> :</label>  
                    </td>
                	<td>
                        <input type="text" name="email" id="email" value="<?php echo $email; ?>" />
                        <?php  echo $tNGs->displayFieldHint("email");?> <?php echo $tNGs->displayFieldError("utilisateur", "email"); ?>
                    </td>
                </tr>-->
                <tr>
                	<td>
                        <label for="password"><?php echo $xml->Mot_passe?> <span style="color:red;">*</span> :</label>  
                    </td>
                	<td>
                        <input type="password" name="password" id="password" value="" />
                        <?php echo $tNGs->displayFieldHint("password");?> <?php echo $tNGs->displayFieldError("utilisateur", "password"); ?>
                    </td>
                </tr>
                <tr>
                	<td>
                    	<label for="re_password"><?php echo $xml->Retaper_le_Mot_de_passe ?> <span style="color:red;">*</span> :</label>  
                    </td>
                	<td>
                        <input type="password" name="re_password" id="re_password" value="" />
                    </td>
                </tr>
                <tr>
                	<td><label for="catch">( <?php echo $val1;?> + <?php echo $val2;?> ) <span style="color:red;">*</span></label></td>
                	<td>
                    	= <input type="text" id="val3" name="val3" value="" style="width:78px;"/>
                    </td>
                </tr>
            </table><br />
            <input type="botton" name="next_step" id="next_step" class="step" value="Suivant"/>
			</div>
            <div class="step2">
            	<!--<div id="back_step">Back</div>-->
            <table cellpadding="0" cellspacing="0" border="0" style="margin-top:25px; width:100%;">
            	<tr>
                	<td>
                        <label for="email"><?php echo $xml-> Email ?> <span style="color:red;">*</span> :</label>  
                    </td>
                	<td>
                        <input type="text" name="email" id="email" value="<?php echo $email; ?>" />
                        <?php  echo $tNGs->displayFieldHint("email");?> <?php echo $tNGs->displayFieldError("utilisateur", "email"); ?>
                    </td>
                </tr>
                <tr>
                    <td>
                    <label for="nom_magazin"><?php echo $xml->Nom_du_magasin ; ?> <span style="color:red;">*</span> : </label>
                    </td>
                    <td>
                    <input type="text" name="nom_magazin" id="nom_magazin" value="<?php echo $nom_magazin; ?>" size="32" />
                    <?php echo $tNGs->displayFieldHint("nom_magazin");?> <?php echo $tNGs->displayFieldError("utilisateur", "nom_magazin"); ?>
                    </td>
                </tr>
                
                <tr>
                    <td>
                    <label for="region"><?php echo $xml-> Region ?>  <span style="color:red;">*</span> : </label>
                    </td>
                    <td>
                    <select name="region" id="region1" onchange="ajax('ajax/department.php?default=<?php echo $row_rsmagazins['department']; ?>&id_region='+this.value,'#department');">
                    	<option value=""><?php echo $xml->selectionner ?></option>
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
                    </td>
                </tr>
                
                <tr>
                    <td>
                    <label for="department">Department <span style="color:red;">*</span> :</label>
                    </td>
                    <td>
                    <select name="department" id="department" onchange="ajax('ajax/ville.php?default=<?php echo $row_rsmagazins['ville']; ?>&id_departement='+this.value,'#ville');">
                        <option value=""><?php echo $xml->selectionner ?></option>  
                        
                        <?php 
                        mysql_select_db($database_magazinducoin, $magazinducoin);
                        $query_department = "SELECT * FROM departement WHERE id_departement='".$row_rsmagazins['department']."' ORDER BY nom_departement ASC";
                        $department = mysql_query($query_department, $magazinducoin) or die(mysql_error());
                        $row_department = mysql_fetch_array($department);
                        //$totalRows_regions = mysql_num_rows($regions);
                        if($row_rsutilisateur['department']!=''){
                        ?>
                            <option value="<?php echo $row_department['id_departement']?>"<?php if (!(strcmp($row_department['id_departement'], $row_rsmagazins['department']))) {echo "SELECTED";} ?> title="<?php echo ($row_department['nom_departement']); ?>"><?php echo ($row_department['nom_departement']); ?></option>
                        <?php }?>   
                        
                
                    </select>
                    <?php echo $tNGs->displayFieldError("magazins", "department", $cnt1); ?>
                    </td>
                </tr>
                
                <tr>
                    <td>
                    <label for="ville"><?php echo $xml->Ville ?>  <span style="color:red;">*</span> :</label>
                    </td>
                    <td>
                    <select name="ville" id="ville">
                    	<option value=""><?php echo $xml->selectionner ?></option>   
                    </select>
                    <?php echo $tNGs->displayFieldError("magazins", "ville", $cnt1); ?>
                    </td>
                </tr>
                <tr>
                    <td>
                    <label for="adresse"><?php echo $xml-> Adresse ?> : </label>
                    </td>
                    <td>
                    <input type="text" name="adresse" id="adresse" value="<?php echo $adresse; ?>" size="32" />
                    <?php echo $tNGs->displayFieldHint("adresse");?> <?php echo $tNGs->displayFieldError("utilisateur", "adresse"); ?>
                    </td>
                </tr>
                
                <tr>
                	<td><div style="width:2px; height:5px; float:left;">&nbsp;</div></td>
                	<td></td>
                </tr>
                <tr>
                    <td>
                    <label for="logo">Logo : </label>
                    </td>
                    <td>
                    <!--<input type="file" name="image" id="image" value="<?php echo $photo; ?>" size="32" />-->
                    <div class="file-wrapper">
                        <input type="file" name="image" id="image" value="<?php echo $photo; ?>" size="32" />
                        <span class="button">Parcourir</span>
                    </div>
                    <?php echo $tNGs->displayFieldHint("image");?> <?php echo $tNGs->displayFieldError("utilisateur", "image"); ?>
                    </td>
                </tr>
                <tr>
                    <td>
                    <label for="photo">Photo : </label>
                    </td>
                    <td>
                    <!--<input type="file" name="image2" id="image2" value="<?php echo $photo; ?>" size="32" />-->
                    <div class="file-wrapper">
                        <input type="file" name="image2" id="image2" value="<?php echo $photo; ?>" size="32" />
                        <span class="button">Parcourir</span>
                    </div>
                    <?php echo $tNGs->displayFieldHint("image2");?> <?php echo $tNGs->displayFieldError("utilisateur", "image2"); ?>
                    </td>
                </tr>
                <tr>
                    <td>
                    <label for="cateory">Catégorie : </label>
                    </td>
                    <td>
                     <select name="categorie" id="categorie"  onchange="ajax('ajax/sous_categorie.php?default=<?php echo $row_categories['sous_categorie']; ?>&id_parent='+this.value,'#sous_categorie');">
                    	<option value=""><?php echo $xml->selectionner ?></option>
						<?php 
                        do {  
                        ?>
                    	<option value="<?php echo $row_categories['cat_id']?>"<?php if (!(strcmp($row_categories['cat_id'], $row_default['cat_name']))) {echo "SELECTED";} ?> title="<?php echo ($row_categories['cat_name']); ?>"><?php echo ($row_categories['cat_name']); ?></option>
                    	<?php
                    	} while ($row_categories = mysql_fetch_assoc($categories));
                    		$rows = mysql_num_rows($categories);
                    			if($rows > 0) {
                    			mysql_data_seek($categories, 0);
                    			$row_categories = mysql_fetch_assoc($categories);
                   		}
                    ?>
                    </select>
                    <?php echo $tNGs->displayFieldError("magazins", "categorie", $cnt1); ?>
                    </td>
                </tr>
                <tr>
                    <td>
                    <label for="cateory">Sous-catégorie : </label>
                    </td>
                    <td>
                    <select name="sous_categorie" id="sous_categorie" >
                    	<option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                    </select>
                    <?php echo $tNGs->displayFieldError("magazins", "sous_categorie", $cnt1); ?>
                    </td>
                </tr>
                
                <tr>
                	<td><div style="width:2px; height:5px; float:left;">&nbsp;</div></td>
                	<td></td>
                </tr>
                 <tr>
                	<td>
                    	<label for="subscribe">Newsletter </label> 
                    </td>
                	<td> 
                        <input type="checkbox" name="subscribe" id="subscribe" value="0"/>
                    </td>
                </tr>
            </table><br />
            <input type="botton" name="back_step" id="back_step" class="step" value="Retour"/>
            </div>
            <div id="send" style="text-align:center;">
            <!--<input type="submit" name="BK_Insert1" id="BK_Insert1" value="old"/>-->
            <!--<input type="submit" name="BK_Insert1_step1" id="BK_Insert1_step1" value="Skip"/>-->
            <input type="botton" name="BK_Insert1_step2" id="BK_Insert1_step2" class="step" value="<?php echo $xml->Enregistrer ?>"/>
            </div>
            </form>
            
            
			<?php }elseif($_REQUEST['level']=='2'){ ?>
                
                
             <form method="post" id="insert_user" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">   
             <table cellpadding="0" cellspacing="0" border="0" style="margin-top:25px; width:100%;">
            	<tr>
                	<td>
                    	<label for="Level">Profil <span style="color:red;">*</span></label>
                    </td>
                	<td>
                        <select name="level" id="level">
                        <option value="3" <?php echo ($_REQUEST['level'] == "3") ? 'selected="selected"' : "";?>>Utilisateur</option>
                        <option value="1" <?php echo ($_REQUEST['level'] == "1") ? 'selected="selected"' : "";?>>Commerçant</option>
                        <option value="2" <?php echo ($_REQUEST['level'] == "2") ? 'selected="selected"' : "";?>>Photographe</option>
                      	</select>
                        <?php echo $tNGs->displayFieldError("utilisateur", "civilite"); ?>
                    </td>
                </tr>
                <tr>
                	<td>
                    	<label for="nom"><?php echo $xml-> Nom ?> <span style="color:red;">*</span></label>  
                    </td>
                	<td>
                        <input type="text" name="nom" id="nom" value="<?php echo $nom; ?>" />
                        <?php echo $tNGs->displayFieldHint("nom");?> <?php echo $tNGs->displayFieldError("utilisateur", "nom"); ?> 
                    </td>
                </tr>
                <tr>
                	<td>
                    	<label for="prenom"><?php echo $xml->Prenom ?> <span style="color:red;">*</span></label> 
                    </td>
                	<td> 
                        <input type="text" name="prenom" id="prenom" value="<?php echo $prenom; ?>" />
                        <?php echo $tNGs->displayFieldHint("prenom");?> <?php echo $tNGs->displayFieldError("utilisateur", "prenom"); ?> 
                    </td>
                </tr>
                <tr>
                    <td>
                    <label for="siren"><?php echo $xml-> Siren ?> :</label>
                    </td>
                	<td>
               		<input type="text" name="siren" id="siren" value="<?php echo $siren; ?>" size="32" />
                    <?php echo $tNGs->displayFieldHint("siren");?> <?php echo $tNGs->displayFieldError("utilisateur", "siren"); ?> 
                    </td>
                </tr>
                <tr>
                	<td>
                    <label for="telephone"><?php echo $xml->Telephone ?> :</label>
                    </td>
                	<td>
                	<input type="text" name="telephone" id="telephone" value="<?php echo $telephone; ?>" size="32" />
                    <?php echo $tNGs->displayFieldHint("telephone");?> <?php echo $tNGs->displayFieldError("utilisateur", "telephone"); ?>
                    </td>
                </tr>
                <tr>
                	<td>
                        <label for="email"><?php echo $xml-> Email ?> <span style="color:red;">*</span> </label>  
                    </td>
                	<td>
                        <input type="text" name="email" id="email" value="<?php echo $email; ?>" />
                        <?php  echo $tNGs->displayFieldHint("email");?> <?php echo $tNGs->displayFieldError("utilisateur", "email"); ?>
                    </td>
                </tr>
                <tr>
                	<td>
                        <label for="password"><?php echo $xml->Mot_passe?> <span style="color:red;">*</span></label>  
                    </td>
                	<td>
                        <input type="password" name="password" id="password" value="" />
                        <?php echo $tNGs->displayFieldHint("password");?> <?php echo $tNGs->displayFieldError("utilisateur", "password"); ?>
                    </td>
                </tr>
                <tr>
                	<td>
                    	<label for="re_password"><?php echo $xml->Retaper_le_Mot_de_passe ?> <span style="color:red;">*</span></label> 
                    </td>
                	<td> 
                        <input type="password" name="re_password" id="re_password" value="" />
                    </td>
                </tr>
                
                <tr>
                	<td><div style="width:2px; height:5px; float:left;">&nbsp;</div></td>
                	<td></td>
                </tr>
                <tr>
                	<td>
                    	<label for="subscribe">Newsletter </label> 
                    </td>
                	<td> 
                        <input type="checkbox" name="subscribe" id="subscribe" value="0" />
                    </td>
                </tr>
                <tr>
                	<td><label for="catch">( <?php echo $val1;?> + <?php echo $val2;?> ) <span style="color:red;">*</span></label></td>
                	<td>
                    	= <input type="text" id="val3" name="val3" value="" style="width:78px;"/>
                    </td>
                </tr>
            </table><br />

            <input type="submit" name="btn_Insert1" id="btn_Insert1" value="<?php echo $xml->Enregistrer ?>"/>
            </form>   
                
            	<?php }else{ ?>
                
            <form method="post" id="insert_user" name="insert_user" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" enctype="multipart/form-data">
        	<table cellpadding="0" cellspacing="0" border="0" style="margin-top:25px; width:100%;">
            	<tr>
                	<td>
                    	<label for="Level">Profil <span style="color:red;">*</span></label>
                    </td>
                	<td>
                        <select name="level" id="level">
                        <option value="3" <?php echo ($_REQUEST['level'] == "3") ? 'selected="selected"' : "";?>>Utilisateur</option>
                        <option value="1" <?php echo ($_REQUEST['level'] == "1") ? 'selected="selected"' : "";?>>Commerçant</option>
                        <option value="2" <?php echo ($_REQUEST['level'] == "2") ? 'selected="selected"' : "";?>>Photographe</option>
                      	</select>
                        <?php echo $tNGs->displayFieldError("utilisateur", "civilite"); ?>
                    </td>
                </tr>
                <tr>
                	<td>
                    	<label for="nom"><?php echo $xml-> Nom ?> <span style="color:red;">*</span></label>  
                    </td>
                	<td>
                        <input type="text" name="nom" id="nom" value="<?php echo $nom; ?>" />
                        <?php echo $tNGs->displayFieldHint("nom");?> <?php echo $tNGs->displayFieldError("utilisateur", "nom"); ?> 
                    </td>
                </tr>
                <tr>
                	<td>
                    	<label for="prenom"><?php echo $xml->Prenom ?> <span style="color:red;">*</span></label> 
                    </td>
                	<td> 
                        <input type="text" name="prenom" id="prenom" value="<?php echo $prenom; ?>" />
                        <?php echo $tNGs->displayFieldHint("prenom");?> <?php echo $tNGs->displayFieldError("utilisateur", "prenom"); ?> 
                    </td>
                </tr>
                <tr>
                	<td>
                        <label for="email"><?php echo $xml-> Email ?> <span style="color:red;">*</span> </label>  
                    </td>
                	<td>
                        <input type="text" name="email" id="email" value="<?php echo $email; ?>" />
                        <?php  echo $tNGs->displayFieldHint("email");?> <?php echo $tNGs->displayFieldError("utilisateur", "email"); ?>
                    </td>
                </tr>
                <tr>
                	<td>
                        <label for="password"><?php echo $xml->Mot_passe?> <span style="color:red;">*</span></label>  
                    </td>
                	<td>
                        <input type="password" name="password" id="password" value="" />
                        <?php echo $tNGs->displayFieldHint("password");?> <?php echo $tNGs->displayFieldError("utilisateur", "password"); ?>
                    </td>
                </tr>
                <tr>
                	<td>
                    	<label for="re_password"><?php echo $xml->Retaper_le_Mot_de_passe ?> <span style="color:red;">*</span></label> 
                    </td>
                	<td> 
                        <input type="password" name="re_password" id="re_password" value="" />
                    </td>
                </tr>
                
                <tr>
                	<td><div style="width:2px; height:5px; float:left;">&nbsp;</div></td>
                	<td></td>
                </tr>
                 <tr>
                	<td>
                    	<label for="subscribe">Newsletter </label> 
                    </td>
                	<td> 
                        <input type="checkbox" name="subscribe" id="subscribe" value="0"/>
                    </td>
                </tr>
                <tr>
                	<td><label for="catch">( <?php echo $val1;?> + <?php echo $val2;?> ) <span style="color:red;">*</span></label></td>
                	<td>
                    	= <input type="text" id="val3" name="val3" value="" style="width:78px;"/>
                    </td>
                </tr>
            </table><br />

            <!--<input type="submit" name="BK_Insert1" id="BK_Insert1" value="<?php echo $xml->Enregistrer ?>"/>-->
            <input type="submit" name="btn_Insert1" id="btn_Insert1" value="<?php echo $xml->Enregistrer ?>"/>
            </form>
            	<?php }?>
            <?php }?>
        </div>
    </div>
    <?php }?>    
        
          
         <?php //echo(rand(1,10));?> 
  </div>

 
  </div>
</div>

<div style="clear:both;"></div>

<div id="footer">
	<?php include("modules/footer.php"); ?>
</div>

</body>
</html>
