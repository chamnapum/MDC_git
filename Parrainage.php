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

//$restrict->addLevel("1");

$restrict->Execute();

if(isset($_SESSION['kt_login_id']) and $_SESSION['kt_payer'] == 0) header('Location: message_aprouvation.php');



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

$ture = '';

$alert = '';

?>



<?php if(isset($_REQUEST['send'])){

	$objet = $_REQUEST['objet'];

	$magasin = "SELECT 

				  magazins.nom_magazin,

				  magazins.adresse,

				  utilisateur.telephone,

				  utilisateur.email,

				  region.nom_region 

				FROM

				  magazins 

				  INNER JOIN utilisateur 

					ON (

					  magazins.id_user = utilisateur.id

					) 

				  INNER JOIN region 

					ON (

					  magazins.region = region.id_region

					) 

				where utilisateur.id = '".$_SESSION['kt_login_id']."' 

				  and magazins.activate = '1' 

				  and magazins.payer = '1' 

				order by magazins.id_magazin asc 

				limit 0, 1";

	$query_magasin = mysql_query($magasin) or die(mysql_error());

	$row_magasin = mysql_fetch_array($query_magasin);

	

	$mail_send = $_REQUEST['email'];

	$expload_mail = explode(",", $mail_send);

	

	foreach($expload_mail as $check_mail){

		$mail = 'SELECT * FROM parrainage WHERE email ="'.$check_mail.'"';

		$query_mail = mysql_query($mail) or die(mysql_error());

		$row_mail = mysql_fetch_array($query_mail);

		if(!$row_mail){

			

			$insert_parrainage = "INSERT INTO parrainage (email, id_user, date) VALUES ('".$check_mail."', '".$_SESSION['kt_login_id']."', NOW())";

			$query_parrainage = mysql_query($insert_parrainage) or die(mysql_error());

			

			$emaiil = $check_mail;

			$to      = $emaiil;

			$subject = $objet;

			$message = '<html>

						<head>

						<style>

						.heading {border: solid 1px #000000;}

						</style>

						</head>

						<body>

						<p>Cher collègue commerçant,</p>

						<p>Je vous invite à découvrir et à vous inscrire sur le site magasinducoin.com</p>

						<p></p>

						<p>Sur magasinducoin.com, vous avez la possibilité de créer des coupons de réductions, créer des èvénements, ajouter les produits de votre magasin, louer un photographe proche de chez vous et tout ça <b>GRATUITEMENT!</b></p>

						<p>Des milliers de clients se connectent chaque jour pour trouver la bonne affaire ou un évènement proche de chez vous. Afficher votre magasin sur internet en proposant un condensé de service gratuit est la solution pour attirer et fidéliser de nouveaux clients.</p>

						<p></p>

						<p>Relançons ensemble le commerce de ville et de proximité, créons des évènements, créons des coupons de réductions et attirons ensemble de nouveaux clients.</p>

						<p></p>

						<p>Les cartes sont dans nos mains.</p>

						<p></p>

						<p>Ce message vous est envoyé par votre collègue commercant:</p>

						<p>"'.$row_magasin['email'].'",</p>

						<p>"'.$row_magasin['nom_magazin'].'",</p>

						<p>"'.$row_magasin['adresse'].'",</p>

						<p>"'.$row_magasin['nom_region'].'",</p>

						<p>"'.$row_magasin['telephone'].'"</p>

						<a href="http://magasinducoin.fr/inscription-1-'.$_SESSION['kt_login_id'].'.html" target="_blank">Inscrivez-vous dès maintenant</a></p>

						

						<p><a href="http://magasinducoin.fr/" target="_blank">

							<img src="http://magasinducoin.fr/assets/images/logo.png" alt=""/>

						</a></p>

						</body>

						</html>';

			$headers = "MIME-Version: 1.0" . "\r\n";

			$headers .= "Content-type:text/html; charset=UTF-8" . "\r\n";

			$headers .= 'From: <parrainage@magasinducoin.fr>' . "\r\n";

			$headers .= 'Cc: '.$_SESSION['kt_login_user'].'' . "\r\n";

			$send_contact2 = mail($to, $subject, $message, $headers);

			if($send_contact2){

				$ture = '1';

				$alert = 'Mail a été envoyé';

			}else {

				$ture = '0';

			}

			

		}else{

			$alert = 'Cette adresse email existe déjà';

		}

	}

	

	

}?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://ns.adobe.com/addt">

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

  show_as_grid: true,

  merge_down_value: true

}

</script>

<script type="text/javascript">

	$(document).ready(function(){

		$( "#send" ).click(function() {

			var email = $("#email").val();

			if(email==''){

				alert('Veuillez saisir des données :\n-email');

				return false;	

			}else{

				$("#insert_user").submit();

				return true;

				alert('ssssss');

			}

		});

	});

</script>		



</head>

<body id="sp">

<?php include("modules/header.php"); ?>





<div id="content" class="photographes" >

	<?php include("modules/member_menu.php"); ?> 

	<?php if($_SESSION['kt_login_level'] == 1){ ?>

        <?php include("modules/credit.php"); ?>

    <?php } ?>     

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

		width: 300px;

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

	.loginForm input[type="button"]{

		background-color: #9D286E;

		border: medium none;

		color: #F8C263;

		cursor: pointer;

		font-size: 18px;

		margin: 0 0 0 5px;

		padding: 0 10px 3px;

	}

	.loginForm textarea {

		border: 1px solid #CCCCCC;

		border-radius: 5px 5px 5px 5px;

		width:300px;

		height:80px;

	}

	.loginForm td{

		line-height:25px;	

	}

	</style>

	

	<h3 style="padding-left:20px; float:left; width:98%;">Parrainage</h3>

    <div style="float:left; width:98%;"">

    <?php echo '<p style="text-align:center; font-size:18px;">'.$alert;'</p>'?>

    <?php if($ture==''){?>

    <?php 

	$magasin = "SELECT 

				  magazins.nom_magazin,

				  magazins.adresse,

				  utilisateur.telephone,

				  utilisateur.email,

				  region.nom_region 

				FROM

				  magazins 

				  INNER JOIN utilisateur 

					ON (

					  magazins.id_user = utilisateur.id

					) 

				  INNER JOIN region 

					ON (

					  magazins.region = region.id_region

					) 

				where utilisateur.id = '".$_SESSION['kt_login_id']."' 

				  and magazins.activate = '1' 

				  and magazins.payer = '1' 

				order by magazins.id_magazin asc 

				limit 0, 1";

	$query_magasin = mysql_query($magasin) or die(mysql_error());

	$row_magasin = mysql_fetch_array($query_magasin);

	?>

    <form method="post" id="insert_user" name="insert_user" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">

    	<table cellpadding="0" cellspacing="0" border="0" class="loginForm" width="50%" align="center">

            <tr>

            	<td width="30%">

                <label for="email">Entrez les emails <span style="color:red;">*</span> :</label>

                </td>

                <td width="70%">

                <!--<input type="text" name="email" id="email"/>-->

                <textarea name="email" id="email" style="width:300px; height:50px;"></textarea><br />

				(Ex : xxx@xxx.com, xx2@xxx.com, ...)

                </td>

            </tr>

            <tr>

            	<td width="30%">

                <label for="email">Objet <span style="color:red;">*</span> :</label>

                </td>

                <td width="70%">

                <input type="text" name="objet" id="objet" value='Votre commerçant <?php echo $row_magasin['nom_magazin'];?> vous invite sur magasinducoin.com'/>

                </td>

            </tr>

            <tr>

            	<td colspan="2">&nbsp;

                

                </td>

            </tr>

            <tr>

            	<td colspan="2">

            	<input type="button" name="send" id="send" value="Valider" />

                </td>

            </tr>

        </table>

	</form>

    <?php }?>

    <?php

	$mail = "SELECT * FROM parrainage WHERE id_user =".$_SESSION['kt_login_id']." ORDER BY id DESC";

	$query_mail = mysql_query($mail) or die(mysql_error());

	$total = mysql_num_rows($query_mail);

	if($total){

	?>

        <h3 style="float:left; margin-left:25%; margin-top:25px; width:100%; font-size:18px;">Mes filleuls:</h3>

        <table cellpadding="0" cellspacing="0" border="0" class="KT_tngtable" width="50%" align="center" style="float:none; margin:auto; margin-top:20px;" >

            <tr>

                <th>Email</th>

                <tH>Date</th>

            </tr>

        <?php 

        while($row_mail = mysql_fetch_array($query_mail)){

        ?>

            <tr>

                <td><?php echo $row_mail['email'];?></td>

                <td><?php echo $row_mail['date'];?></td>

            </tr>

        <?php }?>

        </table>

	<?php }else{?>

    	<p style="width:100%; float:left; text-align:center;">Vous n'avez aucun filleul</p>

    <?php }?>

    

    </div>

	<div class="clear"></div>

</div>

<div id="footer">

    <div class="recherche">

    &nbsp;

    </div>

    <?php include("modules/footer.php"); ?>

</div>



</body>

</html>