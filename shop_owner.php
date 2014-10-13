<?php require_once('Connections/magazinducoin.php'); ?>

<?php

$val1 = (rand(1,10));

$val2 = (rand(1,10));

$val_t = $val1 + $val2;







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



$id_mag = $_GET['id_mag'];

$req ='';



// Load the common classes

require_once('includes/common/KT_common.php');



// Load the tNG classes

require_once('includes/tng/tNG.inc.php');



// Make a transaction dispatcher instance

$tNGs = new tNG_dispatcher("");



function Trigger_shoper_owner(){

	global $magazinducoin;

	

	$query_Recordset1= "SELECT id FROM owner_shopper WHERE id_user='".$_SESSION['kt_login_id']."' AND id_magazin='".$_REQUEST['id_mag']."' AND sirens='".$_REQUEST['siren']."'";

	$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());

	$row_Recordset1 = mysql_fetch_assoc($Recordset1);

	$totalRows_Recordset1 = mysql_num_rows($Recordset1);

	if($totalRows_Recordset1){

		$req = '<p>you have request this shop already!</p>';

	}else{

		$sql_shopper_owner  = "INSERT INTO owner_shopper(id_user,id_magazin,sirens,date) VALUES ('".$_SESSION['kt_login_id']."','".$_REQUEST['id_mag']."','".$_REQUEST['siren']."',NOW())";

		$result_shopper_owner   = mysql_query($sql_shopper_owner  ) or die (mysql_error());

		$req ='';

	}

}













// Make unified connection variable

$conn_magazinducoin = new KT_connection($magazinducoin, $database_magazinducoin);

// Start trigger

$formValidation = new tNG_FormValidation();

$formValidation->addField("kt_login_user", true, "text", "", "", "", "");

$formValidation->addField("kt_login_password", true, "text", "", "", "", "");

$formValidation->addField("siren", true, "text", "", "", "", "");

$tNGs->prepareValidation($formValidation);

// End trigger



// Make a login transaction instance

$loginTransaction = new tNG_login($conn_magazinducoin);

$tNGs->addTransaction($loginTransaction);



// Register triggers

$loginTransaction->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "kt_login1");

$loginTransaction->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);

$loginTransaction->registerTrigger("AFTER", "Trigger_shoper_owner", 97);

$loginTransaction->registerTrigger("END", "Trigger_Default_Redirect", 99, "detail_magasin.php?region=$default_region&mag_id=$id_mag&s");

// Add columns

$loginTransaction->addColumn("kt_login_user", "STRING_TYPE", "POST", "kt_login_user","Adresse mail");

$loginTransaction->addColumn("kt_login_password", "STRING_TYPE", "POST", "kt_login_password");



$loginTransaction->addColumn("siren", "STRING_TYPE", "POST", "siren");

//$loginTransaction->addColumn("kt_login_rememberme", "CHECKBOX_1_0_TYPE", "POST", "kt_login_rememberme", "0");

// End of login transaction instance	



// Execute all the registered transactions

$tNGs->executeTransactions();



// Get the transaction recordset

$rscustom = $tNGs->getRecordset("custom");

$row_rscustom = mysql_fetch_assoc($rscustom);

$totalRows_rscustom = mysql_num_rows($rscustom);











mysql_select_db($database_magazinducoin, $magazinducoin);

$magazin=$_GET['id_mag'];





?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title></title>

<meta name="title" content="" />

<meta name="description" content="" />

<?php include("modules/head-detail.php"); ?>

<script type="text/javascript">

	$(document).ready(function(){

		$('#form2').hide();

		$(".rg").change(function () {

			if ($("#r1").attr("checked")) {

				//alert('1');

				$('#form1').show();

				$('#form2').hide();

			}

			else if($("#r2").attr("checked")){

				//alert('2');

				$('#form1').hide();

				$('#form2').show();

			}

		});

		

		

		$("#btn_Insert1").click(function() {

			var email = $("#email").val();

			var nom = $("#nom").val();

			var prenom = $("#prenom").val();

			var password = $("#password").val();

			var siren = $("#siren").val();

			

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

				

			}else if(siren==''){

				alert('Veuillez saisir des données :\n-siren');

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

				alert('Captcha not right');

				return false;

				

			}else{

				return true;

				$("#form2").submit();

				

			}

		});	

	});

</script>

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

.top p{

	font-weight: bold;

	font-size: 18px;

	text-align:center;

	margin-top:10px;

}

.top p span{

	color:#9D286E;

}



</style>

</head>

<body>

<?php include("modules/header.php"); ?>



<div id="content">

	<style>
    #url_menu_bar{
         margin-top: 10px !important;
    }
    </style>
    <div id="url-menu" style="float:left;">
	<?php include("assets/menu/url_menu.php"); ?>
    </div>
    <div class="clear"></div>

    



    <div class="top">

<p>

	Êtes-vous le propriétaire de magasin <span>"<?php echo $_REQUEST['nom_magasin'];?>"</span>?

</p>

<?php

$successful = '';

$alert = '';

	if($_REQUEST['btn_Insert1']){

		$nom = $_REQUEST['nom'];

		$prenom = $_REQUEST['prenom'];

		$siren = $_REQUEST['siren'];

		$email = $_REQUEST['email'];

		$password = $_REQUEST['password'];

		$telephone = $_REQUEST['telephone'];

		

		$sql_select = "SELECT email FROM utilisateur WHERE email='".$email."'";

		$query_select = mysql_query($sql_select);

		$rs=mysql_fetch_array($query_select);

		

		if($rs==''){

			$sql_pro  = "INSERT INTO utilisateur(nom,prenom,email,password,level,telephone) VALUES ('".trim($nom)."','".$prenom."','".$email."','".$password."','1','".$telephone."')";

			$result_pro  = mysql_query($sql_pro ) or die (mysql_error());

			

			$sql_select = "SELECT id FROM utilisateur WHERE email='".$email."'";

			$query_select = mysql_query($sql_select);

			$user=mysql_fetch_array($query_select);

			$id_user = $user['id'];

			

			$sql_shopper_owner  = "INSERT INTO owner_shopper(id_user,id_magazin,sirens,date) VALUES ('".$id_user."','".$_REQUEST['id_magasin']."','".$siren."',NOW())";

			$result_shopper_owner   = mysql_query($sql_shopper_owner  ) or die (mysql_error());

			

			echo'<p>Veuillez attendre la validation du webmaster</p>';

			$successful = '1';

			$alert = '';

			

			SendMail_Shopper($email,$nom,$prenom);

			

			echo'<script>window.location="detail_magasin.php?region='.$default_region.'&mag_id='.$id_mag.'&s";</script>';

			

		}else{

			

			$successful = '';

			$alert = '<p>Email existe déjà</p>';

		}

	}

?>



<?php if($successful==''){?>

<p>

<input id='r1' type='radio' class='rg' name="asdf" checked="checked"/> Se connecter

<input id='r2' type='radio' class='rg' name="asdf"/> S'enregistrer

</p>

<?php echo $alert;?>

<form action="" method="post" id="form2">

<input type="hidden" name="id_magasin" value="<?php echo $_REQUEST['id_mag'];?>" />

<input type="hidden" name="nom_magasin" value="<?php echo $_REQUEST['nom_magasin'];?>" />

<table class="loginForm" cellpadding="0" cellspacing="0" border="0" align="center" style="margin-top:10px; width:50%;">

    <tr>

        <td>

            <label for="nom"><?php echo $xml-> Nom ?> <span style="color:red;">*</span> :</label>  

        </td>

        <td>

            <input type="text" name="nom" id="nom" value="<?php echo $_REQUEST['nom']; ?>" />

        </td>

    </tr>

    <tr>

        <td>

            <label for="prenom"><?php echo $xml->Prenom ?> <span style="color:red;">*</span> :</label>  

        </td>

        <td>

            <input type="text" name="prenom" id="prenom" value="<?php echo $_REQUEST['prenom']; ?>" />

        </td>

    </tr>

    <tr>

        <td>

        <label for="siren">Siren <span style="color:red;">*</span> :</label>

        </td>

        <td>

        <input type="text" name="siren" id="siren" value="<?php echo $_REQUEST['siren'];; ?>" size="32" maxlength="14" />

        </td>

    </tr>

    <tr>

        <td>

        <label for="telephone"><?php echo $xml->Telephone ?> :</label>

        </td>

        <td>

        <input type="text" name="telephone" id="telephone" value="<?php echo $_REQUEST['telephone']; ?>" size="32" />

        </td>

    </tr>

    <tr>

        <td>

            <label for="email"><?php echo $xml-> Email ?> <span style="color:red;">*</span> :</label>  

        </td>

        <td>

            <input type="text" name="email" id="email" value="<?php echo $_REQUEST['email']; ?>" />

        </td>

    </tr>

    <tr>

        <td>

            <label for="password"><?php echo $xml->Mot_passe?> <span style="color:red;">*</span> :</label>  

        </td>

        <td>

            <input type="password" name="password" id="password" value="" />

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

            = <input type="text" id="val3" name="val3" value="" style="width:78px;"/> <span class="alert"></span>

        </td>

    </tr>

    <tr>

    	<td></td>

    	<td>

        	<input type="submit" name="btn_Insert1" id="btn_Insert1" value="Valider"/>

        </td>

    </tr>

</table>

</form>



<!--<form action="" method="post" id="form2">-->



<form method="post" id="form1" class="KT_tngformerror" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">



<p>

<?php

	echo $tNGs->getLoginMsg();

?>

	<?php //echo $tNGs->displayFieldError("custom", "kt_login_user"); ?>

	<?php //echo $tNGs->displayFieldError("custom", "kt_login_password"); ?>

	<?php //echo $tNGs->displayFieldError("custom", "kt_login_rememberme"); ?>

</p>

<input type="hidden" name="id_magasin" value="<?php echo $_REQUEST['id_mag'];?>" />

<input type="hidden" name="nom_magasin" value="<?php echo $_REQUEST['nom_magasin'];?>" />

<table class="loginForm" cellpadding="0" cellspacing="0" border="0" align="center" style="margin-top:10px; width:40%;">

    <tr>

        <td>

            <label for="email"><?php echo $xml-> Email ?> <span style="color:red;">*</span> :</label>  

        </td>

        <td>

            <!--<input type="text" name="email" id="email" value="<?php echo $email; ?>" />-->

            <span style="color:red;"><?php echo $tNGs->displayFieldError("custom", "kt_login_user"); ?></span>

            <input type="text" name="kt_login_user" id="kt_login_user" value="<?php echo KT_escapeAttribute($row_rscustom['kt_login_user']); ?>" size="32" placeholder="Adresse mail" />

        </td>

    </tr>

    <tr>

        <td>

            <label for="password"><?php echo $xml->Mot_passe?> <span style="color:red;">*</span> :</label>  

        </td>

        <td>

           	<span style="color:red;"> <?php echo $tNGs->displayFieldError("custom", "kt_login_password"); ?></span>

            <input type="password" name="kt_login_password" id="kt_login_password" placeholder="Mot de passe"  size="32" />

        </td>

    </tr>

    <tr>

        <td>

        <label for="siren">Siren <span style="color:red;">*</span> :</label>

        </td>

        <td>

            <span style="color:red;"><?php echo $tNGs->displayFieldError("custom", "siren"); ?></span>

            <input type="text" name="siren" id="siren" value="<?php echo $siren; ?>" size="32" maxlength="14" />

        </td>

    </tr>

    <tr>

    	<td></td>

    	<td>

        	<!--<input type="submit" name="btn_Insert2" id="btn_Insert2" value="<?php echo $xml->Enregistrer ?>"/>-->

            <input type="submit" name="kt_login1" value="Login" />

        </td>

    </tr>

</table>

</form>

<?php }?>

		</div>

    </div>

    <!--End Content-->

    <div id="footer">

    	<div class="recherche">

        &nbsp;

        </div>

        <?php include("modules/footer.php"); ?>

    </div>

</body>

</html>

