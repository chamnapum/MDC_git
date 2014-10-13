<?php require_once('Connections/magazinducoin.php'); ?>

<?php

// Load the tNG classes

require_once('includes/tng/tNG.inc.php');



// Make unified connection variable

$conn_magazinducoin = new KT_connection($magazinducoin, $database_magazinducoin);



//Start Restrict Access To Page

$restrict = new tNG_RestrictAccess($conn_magazinducoin, "");

//Grand Levels: Level

$restrict->addLevel("1");

$restrict->Execute();

//End Restrict Access To Page



mysql_select_db($database_magazinducoin, $magazinducoin);



$query_Recordset10 = "SELECT 

				  tt.* 

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

				WHERE tt.type = '1' AND tt.sub_type='1'

				ORDER BY sub_type ASC";

$Recordset10 = mysql_query($query_Recordset10, $magazinducoin) or die('0'.mysql_error());

$pub = mysql_fetch_assoc($Recordset10);



$query_Recordset11 = "SELECT 

				  tt.* 

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

				WHERE tt.type = '1' AND tt.sub_type='2'

				ORDER BY sub_type ASC";

$Recordset11 = mysql_query($query_Recordset11, $magazinducoin) or die('0'.mysql_error());

$pub11 = mysql_fetch_assoc($Recordset11);



$query_Recordset12 = "SELECT 

				  tt.* 

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

				WHERE tt.type = '1' AND tt.sub_type='3'

				ORDER BY sub_type ASC";

$Recordset12 = mysql_query($query_Recordset12, $magazinducoin) or die('0'.mysql_error());

$pub12 = mysql_fetch_assoc($Recordset12);





$query_Recordset13 = "SELECT 

				  tt.* 

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

				WHERE tt.type = '1' AND tt.sub_type='4'

				ORDER BY sub_type ASC";

$Recordset13 = mysql_query($query_Recordset13, $magazinducoin) or die('0'.mysql_error());

$pub13 = mysql_fetch_assoc($Recordset13);



if($_POST['paiement'] == 'cheque'){

	$max_coupon_free='1';

	echo'<script>window.location="payer_abonement.php?type=magazin&max_free='.$max_coupon_free.'";</script>';

}



if($_POST['paiement'] == 'paypal'){

	$query_user = "SELECT * FROM utilisateur WHERE id = ".$_SESSION['kt_login_id'];

	$user = mysql_query($query_user, $magazinducoin) or die(mysql_error());

	$row_user = mysql_fetch_assoc($user);

	

	define('PAYPAL_EMAIL','paiement@magasinducoin.com');

	define('SECUREURL','http://magasinducoin.fr/');

	define('PAYPAL_DEBUG','1');

	

	//$url = "https://www.paypal.com/cgi-bin/webscr";

	$url = "https://www.sandbox.paypal.com/cgi-bin/webscr";

	

	//$tax_total = $prix;

	$post_variables = Array(

	"cmd" => "_ext-enter",

	"redirect_cmd" => "_xclick",

	"upload" => "1",

	"business" => PAYPAL_EMAIL,

	"receiver_email" => PAYPAL_EMAIL,

	"item_name" => "Commande numero: ". $row_user['id'],

	"order_id" => $row_user['id'],

	/*"invoice" => $db->f("order_number"),*/

	"amount" => $_SESSION['montant_payer'],

	"shipping" => 0,

	"currency_code" => 'EUR',

	

	"address_override" => "1",

	"first_name" => $row_user['prenom'],

	"last_name" => $row_user['nom'],

	"address1" => $row_user['adresse'],

	"zip" => $row_user['code_postal'],

	"city" => getVilleById($row_user['ville']),

	"state" => getRegionById($row_user['region']),

	"country" => 'FR',

	"email" => $row_user['email'],

	"night_phone_b" => $row_user['telephone'],

	"cpp_header_image" => SECUREURL . "template/images/logo.png",

	

	"return" => SECUREURL ."payer_par4.php?type=pay&id_magazin=".$_REQUEST['id'],

	"notify_url" => SECUREURL ."notify.php",

	"cancel_return" => SECUREURL ."payer_par4.php?ids=".$_REQUEST['id']."&type=magazin",

	"undefined_quantity" => "0",

	

	"test_ipn" => PAYPAL_DEBUG,

	"pal" => "NRUBJXESJTY24",

	"no_shipping" => "1",

	"no_note" => "1"

	);

	

	

	$query_string = "?";

	foreach( $post_variables as $name => $value ) {

	$query_string .= $name. "=" . urlencode($value) ."&";

	}

	//echo $url . $query_string ;

	header('Location: '. $url . $query_string );

}



if($_REQUEST['type']=='pay'){

	$id_magazin = $_REQUEST['id_magazin'];

	

	$query_Recordset1 = "SELECT MAX(invoice_id) AS invoice_id, MAX(date_payment) as date_payment, MAX(orber_num) as orber_num  FROM invoice ORDER BY invoice_id DESC";

    $Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die('0'.mysql_error());

    $row_credit = mysql_fetch_assoc($Recordset1);

	$date_now = date('ym');

	$date_data = date('ym',strtotime($row_credit['date_payment']));

	

	if($row_credit['orber_num'])

	$order_num = $row_credit['orber_num']+1;

	else

	$order_num ='1001';

	

	if($date_now == $date_data){

		$num_invoice = substr($row_credit['invoice_id'], -5);

		$num = $num_invoice + 1;

		$invoice_id='';

		if($num < 10){

			$invoice_id = 'F'.$date_now.'0000'.$num;	

		}elseif($num < 100){

			$invoice_id = 'F'.$date_now.'000'.$num;

		}elseif($num < 1000){

			$invoice_id = 'F'.$date_now.'00'.$num;

		}elseif($num < 10000){

			$invoice_id = 'F'.$date_now.'0'.$num;

		}elseif($num < 100000){

			$invoice_id = 'F'.$date_now.''.$num;

		}

	}else{

		$invoice_id = 'F'.$date_now.'00001';

	}

	$pay ='';

	

	$query_Recordset2 = "UPDATE magazins SET ";	

	if(in_array('paiement',$_SESSION['options'])){

		 $set[] = "activate = 1, payer = 1";

		 $text ="Le frais de nouveau magasin";

		 $pay[] = "('".$invoice_id."','".$pub['prix']."','".mysql_real_escape_string($text)."')";

	}

	if(in_array('en_avant',$_SESSION['options'])){

		 $pay_en_avant = $pub11['prix']*$_SESSION['day_en_avant'];

		 $set[] = "en_avant = 1, en_avant_payer = 1, en_avant_fin = DATE_ADD( now(), INTERVAL ".$_SESSION['day_en_avant']." DAY ), day_en_avant ='".$_SESSION['day_en_avant']."'";

		 $text ="Le frais de mise en avant de votre magasin pendant une semaine";

		 $pay[] = "('".$invoice_id."','".$pay_en_avant."','".mysql_real_escape_string($text)."')";

	}

	if(in_array('en_tete_liste',$_SESSION['options'])){

		 $pay_en_tete_liste = $pub12['prix']*$_SESSION['day_en_tete_liste'];

		 $set[] = "en_tete_liste = 1, en_tete_liste_payer = 1, en_tete_liste_fin = DATE_ADD( now(), INTERVAL ".$_SESSION['day_en_tete_liste']." DAY ), day_en_tete_liste ='".$_SESSION['day_en_tete_liste']."' ";

		 $text ="Le frais de monter en tête de liste de votre magasin pendant une semaine";

		 $pay[] = "('".$invoice_id."','".$pay_en_tete_liste."','".mysql_real_escape_string($text)."')";

	}

	if(in_array('en_website',$_SESSION['options'])){

		 $set[] = "en_website = 1";

		 $text ="Le frais d'affichage le lien du siteweb";

		 $pay[] = "('".$invoice_id."','5','".mysql_real_escape_string($text)."')";

	}

	if(in_array('en_facebook',$_SESSION['options'])){

		 $set[] = "en_facebook = 1";

		 $text ="Le frais d'affichage votre lien Facebook";

		 $pay[] = "('".$invoice_id."','5','".mysql_real_escape_string($text)."')";

	}

	if(in_array('public',$_SESSION['options'])){

		$set[] = "public = 1, public_start = '".date('Y-m-d H:i:s')."'";

		$text ="publication express";

		$pay[] = "('".$invoice_id."','".$pub13['prix']."','".mysql_real_escape_string($text)."')";

		

		$mail = "SELECT

					magazins.nom_magazin

					, utilisateur.email

					, utilisateur.nom

					, utilisateur.prenom

				FROM

					utilisateur

					INNER JOIN magazins 

						ON (utilisateur.id = magazins.id_user) WHERE magazins.id_magazin=".$id_magazin;

		$query_mail = mysql_query($mail) or die(mysql_error());

		$row_mail = mysql_fetch_assoc($query_mail);

		$row_mail['email'];

		$row_mail['titre'];

		$row_mail['nom_magazin'];

		

		//$to2 = 'urgence@magasinducoin.com';

		$to2 = 'urgence@magasinducoin.fr';

		$subject2 = "Public Express, Produit ".$row_mail['titre']."";

		$content=selectdatacntent($row_mail['content'],$nom,$nom.' '.$prenom);

		$message2 = '<html>

				<head>

				<style>

				.heading {border: solid 1px #000000;}

				</style>

				</head>

				<body>

				<p>Produit :</p>

				<p>'.$row_mail['nom'].' '.$row_mail['prenom'].'</p>

				<p>'.$row_mail['nom_magazin'].'</p>

				</body>

				</html>

				';

	

			// Always set content-type when sending HTML email

			$headers2 = "MIME-Version: 1.0" . "\r\n";

			$headers2 .= "Content-type:text/html; charset=UTF-8" . "\r\n";

			

			// More headers

			$headers2 .= 'From: <'.$row_mail['email'].'>' . "\r\n";

			//$headers .= 'Cc: myboss@example.com' . "\r\n";

			

			$send_contact2 = mail($to2,($subject2),($message2),$headers2);

			if($send_contact2){

				//echo '<p style="font-size:26px; color:#FA9452;">Votre don est pris en compte.</p>';

			}else {

				echo "ERROR";

			}

	}

	

	$query_payment = "INSERT INTO payment (invoice_id, total, description ) VALUES ".implode(' , ',$pay)." ";

	$payment = mysql_query($query_payment, $magazinducoin) or die('2'.mysql_error());

	

	$pay_invoice="('".$invoice_id."','".$order_num."','".$_SESSION['montant_payer']."','1','".date('Y-m-d H:i:s')."','".$_SESSION['kt_login_id']."','1','".$id_magazin."')";

	$query_invoice = "INSERT INTO invoice (invoice_id, orber_num, amount, type_payment, date_payment, id_user, payon, id_magazin) VALUES ".$pay_invoice." ";

	$invoice = mysql_query($query_invoice, $magazinducoin) or die('3'.mysql_error());

	

	$query_Recordset2 .= implode(' , ',$set)." WHERE id_magazin = ".$id_magazin;

	//die($query_Recordset2);

	$Recordset2 = mysql_query($query_Recordset2, $magazinducoin) or die('2'.mysql_error());

	 header ('location: mes-magazins.php?invoice='.$invoice_id);

	exit();

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



$colname_Recordset1 = "-1";

if (isset($_SESSION['kt_login_id'])) {

  $colname_Recordset1 = $_SESSION['kt_login_id'];

}

mysql_select_db($database_magazinducoin, $magazinducoin);

$query_Recordset1 = sprintf("SELECT * FROM utilisateur WHERE id = %s", GetSQLValueString($colname_Recordset1, "int"));

$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());

$row_Recordset1 = mysql_fetch_assoc($Recordset1);

$totalRows_Recordset1 = mysql_num_rows($Recordset1);



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 

	<title>Magasin du coin | Espace membre </title>

    <?php include("modules/head.php"); ?>

</head>

<body id="sp">

<?php include("modules/header.php"); ?>

    <style>

    	h4{

			font-size:14px;

			margin:10px 0px;

		}

		input[type=submit],input[type=button]{

			background-color: #9D286E;

			border: 0 none;

			color: #F8C263;

			font-size: 12px;

			font-weight: bold;

			padding: 5px 10px;

			cursor:pointer;

		}

    </style>

<div id="content">

<?php include("modules/member_menu.php"); ?>

<?php include("modules/credit.php"); ?>

<?php //include("modules/membre_menu.php"); ?>

    <div style="padding-left:25px;">

  		  <h3>Paiement</h3>

    </div>

    <div style="width:100%;">

    	<div style="width:50%; margin:0px auto;">

          <h4>Adresse de facturation : </h4>

          <?php echo $row_Recordset1['prenom']; ?> <?php echo $row_Recordset1['nom']; ?> <br />

		  <?php echo $row_Recordset1['adresse']; ?><br />

		  <?php echo $row_Recordset1['code_postal']; ?> <?php echo getVilleById($row_Recordset1['ville']); ?>

          <br /><br />

          

          <h4>Paiement par Crédit Publicité</h4>

          <form action="payer_par4.php?ids=<?php echo $_GET['ids'] ?>" method="post">

          <ul>

		<?php if(in_array('paiement',$_SESSION['options'])) { ?> 

              <li>Vous avez atteint le nombre maximum de magasins gratuits. Pour tout événement supplémentaire, merci de payer <?php echo $pub['prix'];?> €.</li>

        <?php } ?>

        <?php if(in_array('en_avant',$_SESSION['options'])) { ?> 

             <li>Frais de mise en avant de votre magasin pour une duré d'une semaine est <?php echo ($pub11['prix']*$_SESSION['day_en_avant']);?> €.</li>

        <?php } ?>

        <?php if(in_array('en_tete_liste',$_SESSION['options'])) { ?> 

             <li>Frais de la remontée en tête de liste de votre magasin pour une duré d'une semaine est <?php echo ($pub12['prix']*$_SESSION['day_en_tete_liste']);?> €.</li>

        <?php } ?>

		<?php if(in_array('en_website',$_SESSION['options'])) { ?> 

             <li>Frais pour afficher le lien du siteweb est 5 €.</li>

        <?php } ?>

        <?php if(in_array('en_facebook',$_SESSION['options'])) { ?> 

             <li>Frais pour afficher le lien Facebook est 5 €.</li>

        <?php } ?>

		<?php if(in_array('public',$_SESSION['options'])) { ?> 

            <li><?php echo $pub13['titre'];?> <?php echo $pub13['prix'];?> €.</li>

        <?php } ?>

        </ul>

        <p style="color:#9D216E;"><strong>Total a payé est <?php echo $_SESSION['montant_payer']; ?> €</strong>.</p>

        <h4>Veuillez choisir votre mode de paiement :</h4>

        <input name="paiement" type="radio" value="paypal" checked="checked" /><b>PayPal</b><br />



        <input name="paiement" type="radio" value="cheque" /><b>Crédit</b><br /><br />



        <input name="id" type="hidden" value="<?php echo $_GET['ids'] ?>" />

        <!--Voulez-vous vraiment payer par Votre Crédit Publicité ?--> <input name="send" type="submit" value="Payer maintenent" /> &nbsp;&nbsp;&nbsp;<input name="notsend" onclick="window.location.href='mes-magazins.php'" type="button" value="Retour" /> 

        </form>

        </div>

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

mysql_free_result($Recordset1);

mysql_free_result($Recordset2);

?>