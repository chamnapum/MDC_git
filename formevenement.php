<?php require_once('Connections/magazinducoin.php'); ?>

<?php

	$time_now = time(); // checking the time now when home page starts

    if ($time_now > $_SESSION['expire']) {

		session_destroy();

		echo'<script>window.location="authetification.html";</script>';

    } else {

		$_SESSION['start'] = time();

		$_SESSION['expire'] = $_SESSION['start'] + (15 * 60);

    }

	$now = date('Y-m-d H:i:s');

?>

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

$formValidation->addField("titre", true, "text", "", "1", "80", "80 caractéres");

//$formValidation->addField("reduction", true, "text", "", "", "", "");

$formValidation->addField("date_debut", true, "date", "", "", "", "");

$formValidation->addField("date_fin", true, "date", "", "", "", "");

$formValidation->addField("category_id", true, "text", "", "", "", "");

$formValidation->addField("sous_categorie", true, "text", "", "", "", "");

$formValidation->addField("description", true, "text", "", "1", "800", "800 caractéres");

$formValidation->addField("id_magazin", true, "text", "", "", "", "");

//$formValidation->addField("code_bare", false, "text", "zip_generic", "12", "13", "Le code bare doit contenir 12 ou 13 caractéres");

$tNGs->prepareValidation($formValidation);

// End trigger





//start Trigger_FileDelete2 trigger

//remove this line if you want to edit the code by hand 

function Trigger_FileDelete2(&$tNG) {

  $deleteObj = new tNG_FileDelete($tNG);

  $deleteObj->setFolder("assets/images/event/");

  $deleteObj->setDbFieldName("photo");

  return $deleteObj->Execute();

}

//end Trigger_FileDelete2 trigger



//start Trigger_ImageUpload2 trigger

//remove this line if you want to edit the code by hand 

function Trigger_ImageUpload2(&$tNG) {

  $uploadObj = new tNG_ImageUpload($tNG);

  $uploadObj->setFormFieldName("photo");

  $uploadObj->setDbFieldName("photo");

  $uploadObj->setFolder("assets/images/event/");

  $uploadObj->setResize("true", 220, 220);

  $uploadObj->setMaxSize(1500);

  $uploadObj->setAllowedExtensions("gif, jpg, jpe, jpeg, png");

  $uploadObj->setRename("auto");

  return $uploadObj->Execute();

}

//end Trigger_ImageUpload2 trigger







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

$query_category_id = "SELECT cat_id, cat_name FROM category WHERE parent_id = 0 AND type = 2 ORDER BY cat_name ASC";

$category_id = mysql_query($query_category_id, $magazinducoin) or die(mysql_error());

$row_category_id = mysql_fetch_assoc($category_id);

$totalRows_category_id = mysql_num_rows($category_id);



$colname_liste_magasins = "-1";

if (isset($_SESSION['kt_login_id'])) {

  $colname_liste_magasins = $_SESSION['kt_login_id'];

}

mysql_select_db($database_magazinducoin, $magazinducoin);

$query_liste_magasins = sprintf("SELECT id_magazin, nom_magazin FROM magazins WHERE id_user = %s AND magazins.activate='1' AND magazins.payer='1' AND magazins.approuve = '1' ORDER BY nom_magazin ASC", GetSQLValueString($colname_liste_magasins, "int"));

$liste_magasins = mysql_query($query_liste_magasins, $magazinducoin) or die(mysql_error());

$row_liste_magasins = mysql_fetch_assoc($liste_magasins);

$totalRows_liste_magasins = mysql_num_rows($liste_magasins);



if($_GET['event_id']!=''){

	$query_check = "SELECT event_id , id_user FROM evenements WHERE event_id='".$_GET['event_id']."' AND id_user= '".$_SESSION['kt_login_id']."'";

	$check = mysql_query($query_check) or die(mysql_error());

	$row_check = mysql_fetch_assoc($check);

	if(!$row_check){

		header('Location: mes_evenements.html');

	}

}



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

				WHERE tt.type = '4' AND tt.sub_type='1'

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

				WHERE tt.type = '4' AND tt.sub_type='2'

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

				WHERE tt.type = '4' AND tt.sub_type='3'

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

				WHERE tt.type = '4' AND tt.sub_type='4'

				ORDER BY sub_type ASC";

$Recordset13 = mysql_query($query_Recordset13, $magazinducoin) or die('0'.mysql_error());

$pub13 = mysql_fetch_assoc($Recordset13);

$query_Recordset1 = "SELECT 

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

				WHERE tt.type = '4' AND tt.sub_type='5'

				ORDER BY sub_type ASC";

$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die('0'.mysql_error());

$pub1 = mysql_fetch_assoc($Recordset1);



$query_Recordset2 = "SELECT 

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

				WHERE tt.type = '4' AND tt.sub_type='6'

				ORDER BY sub_type ASC";

$Recordset2 = mysql_query($query_Recordset2, $magazinducoin) or die('0'.mysql_error());

$pub2 = mysql_fetch_assoc($Recordset2);



function Trigger_DateValidation(&$tNG){

	$date1 = explode('.',$_POST['date_debut_1']);

	$date2 = explode('.',$_POST['date_fin_1']);

	$time0 = mktime(date('H'),date('i'),date('s'),$date1[1],$date1[0],$date1[2]);

	$time1 = mktime(date('H'),date('i'),date('s'),$date1[1],$date1[0]+7,$date1[2]);

	$time2 = mktime(date('H'),date('i'),date('s'),$date2[1],$date2[0],$date2[2]);

	//die(date('d/m/Y',$time1).'<br>'.date('d/m/Y',$time2));

	//$time3 = mktime(date('H'),date('i'),date('s'),$date1[1]+1,$date1[0],$date1[2]);

	if($time1 < $time2){

		$updateError = new tNG_error("Un evenement est valide une seule semaine max, veuillez modifier la date de fin",array(),array());

		return $updateError;

	}

	else if($time2 < $time0){

		$updateError = new tNG_error("La date de debut doit être inferieur a la date de fin",array(),array());

		return $updateError;

	}

}



function Trigger_verifier_limite_free(&$tNG){

	global $magazinducoin;

	

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

					WHERE tt.type = '4' AND tt.sub_type='1'

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

					WHERE tt.type = '4' AND tt.sub_type='2'

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

					WHERE tt.type = '4' AND tt.sub_type='3'

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

				WHERE tt.type = '4' AND tt.sub_type='4'

				ORDER BY sub_type ASC";

	$Recordset13 = mysql_query($query_Recordset13, $magazinducoin) or die('0'.mysql_error());

	$pub13 = mysql_fetch_assoc($Recordset13);

	

	$query_Recordset9 = "SELECT  max(event_id) as id FROM evenements WHERE id_user = ".$_SESSION['kt_login_id'];

    $Recordset9 = mysql_query($query_Recordset9, $magazinducoin) or die('0'.mysql_error());

    $row_coupons = mysql_fetch_assoc($Recordset9);

	$event_id = $row_coupons['id'];

	

	$max_evenement_free=1;

	$rkt = "SELECT count(*) as nb FROM evenements where id_user = ".$_SESSION['kt_login_id']."";

	$query=mysql_query($rkt);

	$nbevenement=mysql_fetch_array($query);

	

	$check_free = "SELECT COUNT(event_id) AS free_event FROM evenements WHERE active='1' AND id_user = ".$_SESSION['kt_login_id'];

	$query_free=mysql_query($check_free);

	$coupon_free=mysql_fetch_array($query_free);

		

	//if($nbevenement['nb'] > $max_evenement_free) {

	if($coupon_free['free_event']!='0'){

		$rkt = "SELECT credit from utilisateur where id = ".$_SESSION['kt_login_id'];

		$query=mysql_query($rkt);

		$creditrow=mysql_fetch_array($query);

		

		$sile = 0;

		$top = 0;

		$public = 0;

		$total_banner = 0;

		if($_POST['en_avant_1']) {

			$sile = ($pub11['prix']*$_POST['en_avant_day_1']);

		}

		if($_POST['en_tete_liste_1']) {

			$top = ($pub12['prix']*$_POST['en_tete_liste_day_1']);

		}

		if($_POST['public_1']) {

			$public = $pub13['prix'];

		}

		if($_POST['total_banner']!='') {

			$total_banner = $_POST['total_banner'];

		}

		

		$total = $pub['prix']+$sile+$top+$public;

		

		if($creditrow['credit'] >= $total){

			$_SESSION['options'] = array();

			$_SESSION['montant_payer'] = $pub['prix'];

			$_SESSION['options'][] = 'paiement';

			

			if($_POST['en_avant_1']) {

				$_SESSION['en_avant_day'] = $_POST['en_avant_day_1'];

				$_SESSION['montant_payer'] += ($pub11['prix']*$_POST['en_avant_day_1']);

				$_SESSION['options'][] = 'en_avant';

			}

			if($_POST['en_tete_liste_1']) {

				$_SESSION['en_tete_liste_day'] = $_POST['en_tete_liste_day_1'];

				$_SESSION['montant_payer'] += ($pub12['prix']*$_POST['en_tete_liste_day_1']);

				$_SESSION['options'][] = 'en_tete_liste';

			}

			if($_POST['public_1']) {

				$_SESSION['montant_payer'] += $pub13['prix'];

				$_SESSION['options'][] = 'public';

			}

			if($_POST['total_banner']!='') {

				$_SESSION['montant_payer'] += $_POST['total_banner'];

				$_SESSION['options'][] = 'banner';

				$_SESSION['banner_type'] = $_POST['banner_type'];

				$_SESSION['banner_month'] = $_POST['banner_month'];

				$_SESSION['total_banner'] = $_POST['total_banner'];

			}

			

			$sql_select1 = "SELECT 

							  utilisateur.id,

							  utilisateur.nom,

							  utilisateur.prenom,

							  utilisateur.email,

							  evenements.event_id,

							  evenements.titre,

							  magazins.nom_magazin,

							  category.cat_name 

							FROM

							  evenements 

							  INNER JOIN utilisateur 

								ON (

								  evenements.id_user = utilisateur.id

								) 

							  INNER JOIN magazins 

								ON (

								  magazins.id_magazin = evenements.id_magazin

								) 

							  INNER JOIN category 

								ON (

								  category.cat_id = evenements.category_id

								) WHERE utilisateur.id='".$_SESSION['kt_login_id']."' AND evenements.event_id='".$event_id."'";

			$query_select1 = mysql_query($sql_select1);

			$rs1=mysql_fetch_array($query_select1);

			

			SendMail_Create_Evnt_Shpper($rs1['email'],$rs1['nom'],$rs1['prenom'],$rs1['titre']);

			SendMail_Create_Event_Ownner($rs1['email'],$rs1['nom'],$rs1['prenom'],$rs1['titre'],$rs1['nom_magazin'],$rs1['cat_name']);

			

			header('Location: evenement_pay-'.$event_id.'.html');

			//Updater le seuil.

			exit();

		}else{      

		

			$_SESSION['options'] = array();

			$_SESSION['montant_payer'] = $pub['prix'];

			$_SESSION['options'][] = 'paiement';

			

			if($_POST['en_avant_1']) {

				$_SESSION['en_avant_day'] = $_POST['en_avant_day_1'];

				$_SESSION['montant_payer'] += ($pub11['prix']*$_POST['en_avant_day_1']);

				$_SESSION['options'][] = 'en_avant';

			}

			if($_POST['en_tete_liste_1']) {

				$_SESSION['en_tete_liste_day'] = $_POST['en_tete_liste_day_1'];

				$_SESSION['montant_payer'] += ($pub12['prix']*$_POST['en_tete_liste_day_1']);

				$_SESSION['options'][] = 'en_tete_liste';

			}

			if($_POST['public_1']) {

				$_SESSION['montant_payer'] += $pub13['prix'];

				$_SESSION['options'][] = 'public';

			}

			if($_POST['total_banner']!='') {

				$_SESSION['montant_payer'] += $_POST['total_banner'];

				$_SESSION['options'][] = 'banner';

				$_SESSION['banner_type'] = $_POST['banner_type'];

				$_SESSION['banner_month'] = $_POST['banner_month'];

				$_SESSION['total_banner'] = $_POST['total_banner'];

			}

		

		

			$sql_select1 = "SELECT 

							  utilisateur.id,

							  utilisateur.nom,

							  utilisateur.prenom,

							  utilisateur.email,

							  evenements.event_id,

							  evenements.titre,

							  magazins.nom_magazin,

							  category.cat_name 

							FROM

							  evenements 

							  INNER JOIN utilisateur 

								ON (

								  evenements.id_user = utilisateur.id

								) 

							  INNER JOIN magazins 

								ON (

								  magazins.id_magazin = evenements.id_magazin

								) 

							  INNER JOIN category 

								ON (

								  category.cat_id = evenements.category_id

								) WHERE utilisateur.id='".$_SESSION['kt_login_id']."' AND evenements.event_id='".$event_id."'";

			$query_select1 = mysql_query($sql_select1);

			$rs1=mysql_fetch_array($query_select1);

			

			SendMail_Create_Evnt_Shpper($rs1['email'],$rs1['nom'],$rs1['prenom'],$rs1['titre']);

			SendMail_Create_Event_Ownner($rs1['email'],$rs1['nom'],$rs1['prenom'],$rs1['titre'],$rs1['nom_magazin'],$rs1['cat_name']);

			

			echo'<script>window.location="evenement_pays-'.$event_id.'.html";</script>';

			//header('Location: payer_abonement.php?type=evenement&max_free='.$max_evenement_free);

			//Updater le seuil.

			exit();

		}

	}

	else {

		$new_id = $event_id;

		$query_Recordset2 = "UPDATE evenements SET active = 1, gratuit = 1, payer = 1 WHERE event_id = ".$new_id;

		$Recordset2 = mysql_query($query_Recordset2, $magazinducoin) or die(mysql_error());

		

		$query_Recordset2 = "UPDATE evenements SET gratuit = 0 WHERE gratuit = 1 AND active = 0 AND id_user=".$_SESSION['kt_login_id']." AND event_id != ".$new_id;

		$Recordset2 = mysql_query($query_Recordset2, $magazinducoin) or die('2'.mysql_error());

		

		/*$query_Recordset2 = "UPDATE utilisateur SET free_event = 1 WHERE id = ".$_SESSION['kt_login_id'];

		$Recordset2 = mysql_query($query_Recordset2, $magazinducoin) or die('2'.mysql_error());*/

		

		if($_POST['en_avant_1'] or $_POST['en_tete_liste_1'] or $_POST['public_1'] or $_POST['total_banner']){

			$_SESSION['montant_payer'] = 0;

			$_SESSION['options'] = array();

			

			if($_POST['en_avant_1']) {

				$_SESSION['en_avant_day'] = $_POST['en_avant_day_1'];

				$_SESSION['montant_payer'] += ($pub11['prix']*$_POST['en_avant_day_1']);

				$_SESSION['options'][] = 'en_avant';

			}

			if($_POST['en_tete_liste_1']) {

				$_SESSION['en_tete_liste_day'] = $_POST['en_tete_liste_day_1'];

				$_SESSION['montant_payer'] += ($pub12['prix']*$_POST['en_tete_liste_day_1']);

				$_SESSION['options'][] = 'en_tete_liste';

			}

			if($_POST['public_1']) {

				$_SESSION['montant_payer'] += $pub13['prix'];

				$_SESSION['options'][] = 'public';

			}

			if($_POST['total_banner']!='') {

				$_SESSION['montant_payer'] += $_POST['total_banner'];

				$_SESSION['options'][] = 'banner';

				$_SESSION['banner_type'] = $_POST['banner_type'];

				$_SESSION['banner_month'] = $_POST['banner_month'];

				$_SESSION['total_banner'] = $_POST['total_banner'];

			}

			

			$sql_select1 = "SELECT 

							  utilisateur.id,

							  utilisateur.nom,

							  utilisateur.prenom,

							  utilisateur.email,

							  evenements.event_id,

							  evenements.titre,

							  magazins.nom_magazin,

							  category.cat_name 

							FROM

							  evenements 

							  INNER JOIN utilisateur 

								ON (

								  evenements.id_user = utilisateur.id

								) 

							  INNER JOIN magazins 

								ON (

								  magazins.id_magazin = evenements.id_magazin

								) 

							  INNER JOIN category 

								ON (

								  category.cat_id = evenements.category_id

								) WHERE utilisateur.id='".$_SESSION['kt_login_id']."' AND evenements.event_id='".$event_id."'";

			$query_select1 = mysql_query($sql_select1);

			$rs1=mysql_fetch_array($query_select1);

			

			SendMail_Create_Evnt_Shpper($rs1['email'],$rs1['nom'],$rs1['prenom'],$rs1['titre']);

			SendMail_Create_Event_Ownner($rs1['email'],$rs1['nom'],$rs1['prenom'],$rs1['titre'],$rs1['nom_magazin'],$rs1['cat_name']);

			

			

			echo'<script>window.location="payer_par_credit3.php?ids='.$new_id.'&type=mise_avant";</script>';

			//header('Location: payer_par_credit2.php?ids='.$new_id.'&type=mise_avant');

			exit();

		}

		else{

			echo'<script>window.location="mes_evenements.html";</script>';

			//header ('location: mes-coupons.php');

			exit();

		}

	}

}



function Trigger_verifier_mise_en_avant(&$tNG){

	global $magazinducoin;

	

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

					WHERE tt.type = '4' AND tt.sub_type='1'

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

					WHERE tt.type = '4' AND tt.sub_type='2'

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

					WHERE tt.type = '4' AND tt.sub_type='3'

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

					WHERE tt.type = '4' AND tt.sub_type='4'

					ORDER BY sub_type ASC";

	$Recordset13 = mysql_query($query_Recordset13, $magazinducoin) or die('0'.mysql_error());

	$pub13 = mysql_fetch_assoc($Recordset13);

	

	$query_payer1 = "SELECT payer FROM evenements WHERE event_id = ".$_GET['event_id'];

	$payer1 = mysql_query($query_payer1, $magazinducoin) or die('0'.mysql_error());

	$payer = mysql_fetch_assoc($payer1);

	

	if($_POST['en_avant_1'] or $_POST['en_tete_liste_1'] or $_POST['public_1'] or $_POST['total_banner'] or $payer['payer']!='1'){

		$_SESSION['montant_payer'] = 0;

		$_SESSION['options'] = array();

		

		if($payer['payer']!='1'){

			$_SESSION['montant_payer'] = $pub['prix'];

			$_SESSION['options'][] = 'paiement';

		}

		

		if($_POST['en_avant_1']) {

			$_SESSION['en_avant_day'] = $_POST['en_avant_day_1'];

			$_SESSION['montant_payer'] += ($pub11['prix']*$_POST['en_avant_day_1']);

			$_SESSION['options'][] = 'en_avant';

		}

		if($_POST['en_tete_liste_1']) {

			$_SESSION['en_tete_liste_day'] = $_POST['en_tete_liste_day_1'];

			$_SESSION['montant_payer'] += ($pub12['prix']*$_POST['en_tete_liste_day_1']);

			$_SESSION['options'][] = 'en_tete_liste';

		}

		if($_POST['public_1']) {

			$_SESSION['montant_payer'] += $pub13['prix'];

			$_SESSION['options'][] = 'public';

		}

		if($_POST['total_banner']!='') {

			$_SESSION['montant_payer'] += $_POST['total_banner'];

			$_SESSION['options'][] = 'banner';

			$_SESSION['banner_type'] = $_POST['banner_type'];

			$_SESSION['banner_month'] = $_POST['banner_month'];

			$_SESSION['total_banner'] = $_POST['total_banner'];

		}

		header('Location: evenement_pay-'.$_GET['event_id'].'.html');

		exit();

	}

}



// Make an insert transaction instance

$ins_evenements = new tNG_multipleInsert($conn_magazinducoin);

$tNGs->addTransaction($ins_evenements);

// Register triggers

$ins_evenements->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");

$ins_evenements->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);

//$ins_evenements->registerTrigger("BEFORE", "Trigger_DateValidation", 10, $formValidation);

$ins_evenements->registerTrigger("END", "Trigger_Default_Redirect", 99, "includes/nxt/back.php");

//$ins_evenements->registerTrigger("AFTER", "Trigger_send_newsletter", 98);



$ins_evenements->registerTrigger("AFTER", "Trigger_ImageUpload2", 97);

$ins_evenements->registerTrigger("AFTER", "Trigger_verifier_limite_free", 98);

// Add columns

$ins_evenements->setTable("evenements");

$ins_evenements->addColumn("titre", "STRING_TYPE", "POST", "titre");

$ins_evenements->addColumn("date_debut", "DATE_TYPE", "POST", "date_debut");

$ins_evenements->addColumn("date_fin", "DATE_TYPE", "POST", "date_fin");

$ins_evenements->addColumn("category_id", "STRING_TYPE", "POST", "category_id");

$ins_evenements->addColumn("id_magazin", "STRING_TYPE", "POST", "id_magazin");

$ins_evenements->addColumn("id_region", "STRING_TYPE", "SESSION", "kt_region");

$ins_evenements->addColumn("photo", "FILE_TYPE", "FILES", "photo");

$ins_evenements->addColumn("description", "STRING_TYPE", "POST", "description");

$ins_evenements->addColumn("day_en_avant", "STRING_TYPE", "POST", "day_en_avant", "0");

$ins_evenements->addColumn("day_en_tete_liste", "STRING_TYPE", "POST", "day_en_tete_liste", "0");

$ins_evenements->addColumn("id_user", "NUMERIC_TYPE", "POST", "id_user", "{SESSION.kt_login_id}");

$ins_evenements->setPrimaryKey("event_id", "NUMERIC_TYPE");



// Make an update transaction instance

$upd_evenements = new tNG_multipleUpdate($conn_magazinducoin);

$tNGs->addTransaction($upd_evenements);

// Register triggers

$upd_evenements->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");

$upd_evenements->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);

$upd_evenements->registerTrigger("AFTER", "Trigger_verifier_mise_en_avant", 98);

$upd_evenements->registerTrigger("AFTER", "Trigger_ImageUpload2", 97);

$upd_evenements->registerTrigger("END", "Trigger_Default_Redirect", 99, "includes/nxt/back.php");

// Add columns

$upd_evenements->setTable("evenements");

$upd_evenements->addColumn("titre", "STRING_TYPE", "POST", "titre");

$upd_evenements->addColumn("category_id", "STRING_TYPE", "POST", "category_id");

$upd_evenements->addColumn("id_magazin", "STRING_TYPE", "POST", "id_magazin");

$upd_evenements->addColumn("description", "STRING_TYPE", "POST", "description");

$upd_evenements->addColumn("photo", "FILE_TYPE", "FILES", "photo");

$upd_evenements->addColumn("id_user", "NUMERIC_TYPE", "POST", "id_user");

$upd_evenements->addColumn("approuve", "NUMERIC_TYPE", "POST", "approuve", "0");

$upd_evenements->addColumn("day_en_avant", "STRING_TYPE", "POST", "day_en_avant", "0");

$upd_evenements->addColumn("day_en_tete_liste", "STRING_TYPE", "POST", "day_en_tete_liste", "0");

$upd_evenements->setPrimaryKey("event_id", "NUMERIC_TYPE", "GET", "event_id");



// Make an instance of the transaction object

$del_evenements = new tNG_multipleDelete($conn_magazinducoin);

$tNGs->addTransaction($del_evenements);

// Register triggers

$del_evenements->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");

$del_evenements->registerTrigger("END", "Trigger_Default_Redirect", 99, "mes-evenements.php");

// Add columns

$del_evenements->setTable("evenements");

$del_evenements->setPrimaryKey("event_id", "NUMERIC_TYPE", "GET", "event_id");



// Execute all the registered transactions

$tNGs->executeTransactions();



// Get the transaction recordset

$rsevenements = $tNGs->getRecordset("evenements");

$row_rsevenements = mysql_fetch_assoc($rsevenements);

$totalRows_rsevenements = mysql_num_rows($rsevenements);

?>

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



<script type="text/javascript" src="includes/common/js/sigslot_core.js"></script>

<script type="text/javascript" src="includes/wdg/classes/MXWidgets.js"></script>

<script type="text/javascript" src="includes/wdg/classes/MXWidgets.js.php"></script>

<script type="text/javascript" src="includes/wdg/classes/Calendar.js"></script>

<script type="text/javascript" src="includes/wdg/classes/SmartDate.js"></script>

<script type="text/javascript" src="includes/wdg/calendar/calendar_stripped.js"></script>

<script type="text/javascript" src="includes/wdg/calendar/calendar-setup_stripped.js"></script>

<script src="includes/resources/calendar.js"></script>



<script type="text/javascript">

$NXT_FORM_SETTINGS = {

  duplicate_buttons: false,

  show_as_grid: false,

  merge_down_value: false

}



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

	  

	/*$('.en_avant').click(function() {

		var en_avant_day = $('#en_avant_day_1').val();

		var en_avant = $('#en_avant_1').val();

		var total_en_avant = en_avant_day * en_avant;

		

		if($(".en_avant").is(':checked')){

			$('#en_avant_day_1').show();

			$('#total_en_avant').html('+ '+total_en_avant+' €');

			en_avan();

		}else if($(".en_avant").is(":not(:checked)")){

			$('#en_avant_day_1').hide();

			$('#total_en_avant').html('');

		}

	});

	

	function en_avan(){

		$('#en_avant_day_1').change(function() {

			var en_avant_day = $('#en_avant_day_1').val();

			var en_avant = $('#en_avant_1').val();

			var total_en_avant = en_avant_day * en_avant;

			$('#total_en_avant').html('+ '+total_en_avant+' €');

		});

	}*/

	

	$('.en_avant').click(function() {

		var total_en_avant = 0;

		var public_hidden = +$('#public_hidden').val();

		var en_tete_liste_hidden = +$('#en_tete_liste_hidden').val();

		

		var en_avant_day = $('#en_avant_day_1').val();

		var en_avant = $('#en_avant_1').val();

		total_en_avant = en_avant_day * en_avant;

		

		if($(".en_avant").is(':checked')){

			$('#en_avant_day_1').show();

			$('#en_avant_hidden').val(total_en_avant);

			var en_avant_hidden = $('#en_avant_hidden').val();

			$('#show').html(total_en_avant+public_hidden+en_tete_liste_hidden);

			

			en_avanss();

		}else if($(".en_avant").is(":not(:checked)")){

			$('#en_avant_day_1').hide();

			$('#en_avant_hidden').val('0');

			$('#show').html(public_hidden+en_tete_liste_hidden);

		}

	});

	

	function en_avanss(){

		$('#en_avant_day_1').change(function() {

			var en_avant_day = $('#en_avant_day_1').val();

			var en_avant = $('#en_avant_1').val();

			var total_en_avant = en_avant_day * en_avant;

			

			var en_avant_hiddens = $('#en_avant_hidden').val(total_en_avant);

			var public_hidden = +$('#public_hidden').val();

			var en_tete_liste_hidden = +$('#en_tete_liste_hidden').val();

			$('#show').html(total_en_avant+public_hidden+en_tete_liste_hidden);

			

		});

	}

	

	/*$('.en_tete_liste').click(function() {

		var en_tete_liste_day = $('#en_tete_liste_day_1').val();

		var en_tete_liste = $('#en_tete_liste_1').val();

		var total_en_tete_liste = en_tete_liste_day * en_tete_liste;

		

		if($(".en_tete_liste").is(':checked')){

			$('#en_tete_liste_day_1').show();

			$('#total_en_tete_liste').html('+ '+total_en_tete_liste+' €');

			en_tete_listes();

		}else if($(".en_tete_liste").is(":not(:checked)")){

			$('#en_tete_liste_day_1').hide();

			$('#total_en_tete_liste').html('');

		}

	});

	

	function en_tete_listes(){

		$('#en_tete_liste_day_1').change(function() {

			var en_tete_liste_day = $('#en_tete_liste_day_1').val();

			var en_tete_liste = $('#en_tete_liste_1').val();

			var total_en_tete_liste = en_tete_liste_day * en_tete_liste;

			$('#total_en_tete_liste').html('+ '+total_en_tete_liste+' €');

		});

	} */

	

	$('.en_tete_liste').click(function() {

		var total_en_tete_liste = 0;

		var public_hidden = +$('#public_hidden').val();

		var en_avant_hidden = +$('#en_avant_hidden').val();

		

		var en_tete_liste_day = $('#en_tete_liste_day_1').val();

		var en_tete_liste = $('#en_tete_liste_1').val();

		var total_en_tete_liste = en_tete_liste_day * en_tete_liste;

		

		if($(".en_tete_liste").is(':checked')){

			$('#en_tete_liste_day_1').show();

			$('#en_tete_liste_hidden').val(total_en_tete_liste);

			var en_tete_liste_hidden = $('#en_tete_liste_hidden').val();

			$('#show').html(total_en_tete_liste+public_hidden+en_avant_hidden);

			

			en_tete_listess();

		}else if($(".en_tete_liste").is(":not(:checked)")){

			$('#en_tete_liste_day_1').hide();

			$('#en_tete_liste_hidden').val('0');

			$('#show').html(public_hidden+en_avant_hidden);

		}

	});

	

	function en_tete_listess(){

		$('#en_tete_liste_day_1').change(function() {

			var en_tete_liste_day = $('#en_tete_liste_day_1').val();

			var en_tete_liste = $('#en_tete_liste_1').val();

			var total_en_tete_liste = en_tete_liste_day * en_tete_liste;

						

			var en_tete_liste_hidden = $('#en_tete_liste_hidden').val(total_en_tete_liste);

			var public_hidden = +$('#public_hidden').val();

			var en_avant_hidden = +$('#en_avant_hidden').val();

			$('#show').html(total_en_tete_liste+public_hidden+en_avant_hidden);

		});

	}

	  

	/*$('.en_avant').click(function() {

		var total = $('#show').html();

		$('#show').html(this.checked ? parseInt(this.value) + parseInt(total) : parseInt(total) - parseInt(this.value) );

	});

	$('.en_tete_liste').click(function() {

		var total = $('#show').html();

		$('#show').html(this.checked ? parseInt(this.value) + parseInt(total) : parseInt(total) - parseInt(this.value) );

	});*/

	$('.public').click(function() {

		var total = $('#public_hidden').val();

		var en_avant_hidden = $('#en_avant_hidden').val();

		var en_tete_liste_hidden = $('#en_tete_liste_hidden').val();

		

		$('#public_hidden').val(this.checked ? parseInt(this.value) + parseInt(total) : parseInt(total) - parseInt(this.value) );

		$('#show').html(this.checked ? parseInt(this.value) + parseInt(total) + parseInt(en_avant_hidden) + parseInt(en_tete_liste_hidden) :  parseInt(total) - parseInt(this.value) + parseInt(en_avant_hidden) + parseInt(en_tete_liste_hidden));

	});

	

  	$('.banner').click(function() {

		if($(".banner").is(':checked')){

			// checked

			var dataString='';

			$.ajax({

				type: "POST",

				url: "assets/banner/event_type.php",

				data: dataString,

				cache: false,

				success: function(datas){

					$(".coupon_type").html(datas);

				}

			});	

		}else{

			// unchecked

			$(".coupon_type").html('');

			$(".coupon_type_date").html('');

			$("#total_coupon").html('');

			$('#total_banner').val('');

		}

	});

	

});

</script>



<style type="text/css">

.file-wrapper {

    position: relative;

    display: inline-block;

    overflow: hidden;

    cursor: pointer;

	float:left;

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

<?php if(isset($_GET['event_id'])) { ?>

onload="ajax('ajax/sous_categorie.php?default=<?php echo $row_rsevenements['sous_categorie']; ?>&id_parent=<?php echo $row_rsevenements['category_id']; ?>','#sous_categorie_1');"

<?php } ?>>

<?php include("modules/header.php"); ?>

<div id="content" class="photographes">

	<?php //include("modules/member_menu.php"); ?>

	<?php include("modules/credit.php"); ?>



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

		line-height:25px;	

	}

	.loginForm input[type="checkbox"]{

		margin: 15px 5px 0 0 !important;

	}

	  a.popupwindow{text-decoration:none; color:#9D216E !important;}

  a.popupwindow:hover{ color:#F8C263 !important}

	</style>

	<div style="float:left; width:100%;">

          <h3 style="margin-left:20px;">Insertion Événements: <?php if(array_key_exists("dupliquer", $_GET)){}else{echo $row_rsevenements['titre'];} ?></h3>

          

				<?php echo $tNGs->getErrorMsg();?>

			

          <!--<div class="KT_tng">

            <div class="KT_tngform">-->

              <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" enctype="multipart/form-data">

              <div style="margin-left:20px; float:left; width:98%;" class="loginForm">

                <?php $cnt1 = 0; ?>

                <?php do { ?>

                  <?php $cnt1++; ?>

                <?php 

// Show IF Conditional region1 

if (@$totalRows_rsevenements > 1) {?>

	<h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>

<?php } 

// endif Conditional region1

?>



<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">

    <tr>

       	<td>

        	<label for="titre_<?php echo $cnt1; ?>"><?php echo $xml->Titre ?>:</label>

        </td>

       	<td>

        	<input type="text" name="titre_<?php echo $cnt1; ?>" id="titre_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsevenements['titre']); ?>" size="64" style="width:300px;" />

			<?php echo $tNGs->displayFieldHint("titre");?> <?php echo $tNGs->displayFieldError("evenements", "titre", $cnt1); ?> 

        </td>

    </tr>

    <tr>

       	<td>

        	<label for="date_debut_<?php echo $cnt1; ?>"><?php echo $xml->Date_de_debut ?>:</label>

        </td>

	<?php if(isset($_GET['event_id'])){

	$query_Recordset1 = "SELECT * FROM evenements WHERE event_id = ".$_GET['event_id'];

    $Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die('0'.mysql_error());

    $evenement = mysql_fetch_assoc($Recordset1);

	 ?>

     <?php if(array_key_exists("dupliquer", $_GET)) {?>

     	<td>

            <input type="text" name="date_debut_<?php echo $cnt1; ?>" id="date_debut_<?php echo $cnt1; ?>" value="<?php echo KT_formatDate($row_rsevenements['date_debut']); ?>" size="10" maxlength="22" wdg:mondayfirst="true" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="yes" />

            <?php echo $tNGs->displayFieldHint("date_debut");?> <?php echo $tNGs->displayFieldError("evenements", "date_debut", $cnt1); ?> 

        </td> 

     <?php }else{?>

        <td>

            <input type="text" disabled="disabled" value="<?php echo KT_formatDate($evenement['date_debut']); ?>" />

		</td>	

     <?php }?>

        

    <?php } else { ?>

        <td>

            <input type="text" name="date_debut_<?php echo $cnt1; ?>" id="date_debut_<?php echo $cnt1; ?>" value="<?php echo KT_formatDate($row_rsevenements['date_debut']); ?>" size="10" maxlength="22" wdg:mondayfirst="true" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="yes" />

            <?php echo $tNGs->displayFieldHint("date_debut");?> <?php echo $tNGs->displayFieldError("evenements", "date_debut", $cnt1); ?> 

         </td>                     

     <?php } ?>

    </tr>  

    <tr>

       	<td>

       		<label for="date_fin_<?php echo $cnt1; ?>"><?php echo $xml->Date_fin ?>:</label>

        </td>

	<?php if(isset($_GET['event_id'])){ ?>

    	<?php if (array_key_exists("dupliquer", $_GET)) {?>

        <td>

            <input type="text" name="date_fin_<?php echo $cnt1; ?>" id="date_fin_<?php echo $cnt1; ?>" value="<?php echo KT_formatDate($row_rsevenements['date_fin']); ?>" size="10" maxlength="22" wdg:mondayfirst="true" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="yes" />

            <?php echo $tNGs->displayFieldHint("date_fin");?> <?php echo $tNGs->displayFieldError("evenements", "date_fin", $cnt1); ?> 

        </td>

        <?php }else{?>

        <td>

            <input type="text" disabled="disabled" value="<?php echo KT_formatDate($evenement['date_fin']); ?>" />

        </td>

        <?php }?>	

	<?php } else { ?>

        <td>

        <input type="text" name="date_fin_<?php echo $cnt1; ?>" id="date_fin_<?php echo $cnt1; ?>" value="<?php echo KT_formatDate($row_rsevenements['date_fin']); ?>" size="10" maxlength="22" wdg:mondayfirst="true" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="yes" />

        <?php echo $tNGs->displayFieldHint("date_fin");?> <?php echo $tNGs->displayFieldError("evenements", "date_fin", $cnt1); ?> 

        </td>

	<?php } ?>

    </tr>  

    <tr>

       	<td>

        	<label for="id_magazin_<?php echo $cnt1; ?>"><?php echo $xml->Magasin ?>:</label>

        </td>

       	<td>

        <select name="id_magazin_<?php echo $cnt1; ?>" id="id_magazin_<?php echo $cnt1; ?>">

            <option value=""><?php echo NXT_getResource("Select one..."); ?></option>

              <?php 

			do {  

			?>

			<option value="<?php echo $row_liste_magasins['id_magazin']?>"<?php if (!(strcmp($row_liste_magasins['id_magazin'], $row_rsevenements['id_magazin']))) {echo "SELECTED";} ?>><?php echo $row_liste_magasins['nom_magazin']?></option>

			<?php

			} while ($row_liste_magasins = mysql_fetch_assoc($liste_magasins));

			  $rows = mysql_num_rows($liste_magasins);

			  if($rows > 0) {

				  mysql_data_seek($liste_magasins, 0);

				  $row_liste_magasins = mysql_fetch_assoc($liste_magasins);

			  }

			?>

		</select>

		<?php echo $tNGs->displayFieldError("evenements", "id_magazin", $cnt1); ?>

        </td>

    </tr>  

    <tr valign="top">

       	<td>

        	<label for="description_<?php echo $cnt1; ?>">Description:</label>

        </td>

       	<td>

        	<textarea name="description_<?php echo $cnt1; ?>" cols="64" rows="6" id="description_<?php echo $cnt1; ?>" style="width:300px;"><?php echo KT_escapeAttribute($row_rsevenements['description']); ?></textarea>

			<?php echo $tNGs->displayFieldHint("description");?> <?php echo $tNGs->displayFieldError("evenements", "description", $cnt1); ?>

        </td>

    </tr>  

    <tr valign="top">

    	<td>

        	<label style="margin-right: 10px; vertical-align: top;">Ajouter une photo :</label>

        </td>

    	<td style="padding-top:10px;">

        

        <?php if($row_rsevenements['photo']) { ?>

        	<?php if(array_key_exists("dupliquer", $_GET)) {?>

            

            <div class="file-wrapper">

                <input type="file" name="photo_<?php echo $cnt1; ?>" id="photo_<?php echo $cnt1; ?>"/>

                <span class="button">Parcourir</span>

            </div>            

            <?php }else{?>

            <div class="file-wrapper">

                <input type="file" name="photo_<?php echo $cnt1; ?>" id="photo_<?php echo $cnt1; ?>"/>

                <span class="button">Parcourir</span>

            </div>

            <div id="imgContiner3">

                <img src="assets/images/event/<?php echo KT_escapeAttribute($row_rsevenements['photo']); ?>" width="60"/>

            </div> 

            <?php } ?>

        <?php }else{?>

        <div class="file-wrapper">

            <input type="file" name="photo_<?php echo $cnt1; ?>" id="photo_<?php echo $cnt1; ?>"/>

            <span class="button">Parcourir</span>

        </div>

        <?php }?>

        

        <?php echo $tNGs->displayFieldError("evenements", "photo", $cnt1); ?>

        </td>

    </tr>   

    <tr>

       	<td>

        	<label for="category_id_<?php echo $cnt1; ?>"><?php echo $xml->Categorie ?>:</label>

        </td>

       	<td>

            <select name="category_id_<?php echo $cnt1; ?>" id="category_id_<?php echo $cnt1; ?>"  <?php /*?>onchange="ajax('ajax/sous_categorie.php?default=<?php echo $row_rsevenements['sous_categorie']; ?>&id_parent='+this.value,'#sous_categorie_<?php echo $cnt1; ?>');<?php */?> <?php /*?>if(this.value == -1) document.getElementById('min_achat_tr').style.display=''; else document.getElementById('min_achat_tr').style.display='none';<?php */?>>

				<option value=""><?php echo NXT_getResource("Select one..."); ?></option>

    

              <?php 

            do {  

            ?>

              <option value="<?php echo $row_category_id['cat_id']?>"<?php if (!(strcmp($row_category_id['cat_id'], $row_rsevenements['category_id']))) {echo "SELECTED";} ?>><?php echo ($row_category_id['cat_name']); ?></option>

              <?php

            } while ($row_category_id = mysql_fetch_assoc($category_id));

            $rows = mysql_num_rows($category_id);

            if($rows > 0) {

            mysql_data_seek($category_id, 0);

            $row_category_id = mysql_fetch_assoc($category_id);

            }

            ?>

            </select>

			<?php echo $tNGs->displayFieldError("evenements", "category_id", $cnt1); ?>

        </td>

    </tr>

    

    

    <tr>

    	<td colspan="2">

        	<table cellpadding="0" cellspacing="0" border="0"  width="98%" style="border-top:2px solid; font-size:13px; font-weight:bold;">

            	<tr>

                	<td colspan="2"><span style="font-size:16px; font-weight:bold;">Faites votre publicité</span></td>

                </tr>

            

            	<?php if(!$evenement['en_avant_payer']) { ?>

            	<tr>

                	<td width="5">

                    	<input type="checkbox" name="en_avant_<?php echo $cnt1; ?>" id="en_avant_<?php echo $cnt1; ?>" class="en_avant" value="<?php echo $pub11['prix'];?>" />

            		</td>

                    <td>

                    	<label for="en_avant_<?php echo $cnt1; ?>"><?php echo $pub11['titre'];?> (<?php echo $pub11['prix'];?> &euro;)</label>

                            <select name="day_en_avant_<?php echo $cnt1; ?>" id="day_en_avant_<?php echo $cnt1; ?>" style="width:80px;">

                                <option value="">0 jour</option>

                                <option value="1">1 jour</option>

                                <option value="2">2 jour(s)</option>

                                <option value="3">3 jour(s)</option>

                            </select> Avant la date de début 

                            Pendant

                            <select name="en_avant_day_<?php echo $cnt1; ?>" id="en_avant_day_<?php echo $cnt1; ?>" style="width:150px;">

                                <?php for($j=1; $j<=15; $j++){?>

                                    <option value="<?php echo $j;?>"><?php echo $j;?> jour <?php if($j>1){?>(s)<?php }?> = <?php echo ($j*$pub11['prix']);?> &euro;</option>

                                <?php }?>

                            </select>

                            <?php if($pub11['description']!=''){?><a href="assets/popup_2/pub.php?id=<?php echo $pub11['id'];?>" class="popupwindow" rel="windowCenter">(?)</a><?php }?>

                        

                    </td>

                </tr>

                <?php } else {  ?> 

    				<?php if (array_key_exists("dupliquer", $_GET)) {?>

                <tr>

                	<td width="5">

                    	<input type="checkbox" name="en_avant_<?php echo $cnt1; ?>" id="en_avant_<?php echo $cnt1; ?>" class="en_avant" value="<?php echo $pub11['prix'];?>" />

            		</td>

                    <td>

                    	<label for="en_avant_<?php echo $cnt1; ?>"><?php echo $pub11['titre'];?> (<?php echo $pub11['prix'];?> &euro;) </label>

                            <select name="day_en_avant_<?php echo $cnt1; ?>" id="day_en_avant_<?php echo $cnt1; ?>" style="width:80px;">

                                <option value="">0 jour</option>

                                <option value="1">1 jour</option>

                                <option value="2">2 jour(s)</option>

                                <option value="3">3 jour(s)</option>

                            </select> Avant la date de début  

                            Pendant

                            <select name="en_avant_day_<?php echo $cnt1; ?>" id="en_avant_day_<?php echo $cnt1; ?>" style="width:150px;">

                                <?php for($j=1; $j<=15; $j++){?>

                                    <option value="<?php echo $j;?>"><?php echo $j;?> jour <?php if($j>1){?>(s)<?php }?> = <?php echo ($j*$pub11['prix']);?> &euro;</option>

                                <?php }?>

                            </select>

                            <?php if($pub11['description']!=''){?><a href="assets/popup_2/pub.php?id=<?php echo $pub11['id'];?>" class="popupwindow" rel="windowCenter">(?)</a><?php }?>

                       

                    </td>

                </tr>

                <?php }else{?>

                	<?php if($evenement['en_avant_fin']<$now){?>

                    <tr>

                        <td width="5">

                            <input type="checkbox" name="en_avant_<?php echo $cnt1; ?>" id="en_avant_<?php echo $cnt1; ?>" class="en_avant" value="<?php echo $pub11['prix'];?>" />

                        </td>

                        <td>

                            <label for="en_avant_<?php echo $cnt1; ?>"><?php echo $pub11['titre'];?> (<?php echo $pub11['prix'];?> &euro;)</label>

                                <select name="day_en_avant_<?php echo $cnt1; ?>" id="day_en_avant_<?php echo $cnt1; ?>" style="width:80px;">

                                    <option value="">0 jour</option>

                                    <option value="1">1 jour</option>

                                    <option value="2">2 jour(s)</option>

                                    <option value="3">3 jour(s)</option>

                                </select> Avant la date de début  

                                Pendant

                                <select name="en_avant_day_<?php echo $cnt1; ?>" id="en_avant_day_<?php echo $cnt1; ?>" style="width:150px;">

                                    <?php for($j=1; $j<=15; $j++){?>

                                        <option value="<?php echo $j;?>"><?php echo $j;?> jour <?php if($j>1){?>(s)<?php }?> = <?php echo ($j*$pub11['prix']);?> &euro;</option>

                                    <?php }?>

                                </select>

                                <?php if($pub11['description']!=''){?><a href="assets/popup_2/pub.php?id=<?php echo $pub11['id'];?>" class="popupwindow" rel="windowCenter">(?)</a><?php }?>

                            

                        </td>

                    </tr>

                    <?php }else{?>

                    

                    <tr>  

                        <td></td>           

                        <td>

                            <label for="en_avant_<?php echo $cnt1; ?>"><?php echo "Le evenement est en avant jusqu'au ".KT_formatDate($evenement['en_avant_fin']); ?></label>

                        </td>

                    </tr>

                	<?php } ?>

                <?php } ?>

			<?php } ?>

            

            <?php if(!$evenement['en_tete_liste_payer']) { ?> 

            <tr>

            	<td>

                	<input type="checkbox" name="en_tete_liste_<?php echo $cnt1; ?>" id="en_tete_liste_<?php echo $cnt1; ?>" class="en_tete_liste" value="<?php echo $pub12['prix'];?>" />

                </td>

                <td>

                	<label for="en_tete_liste_<?php echo $cnt1; ?>"><?php echo $pub12['titre'];?> (<?php echo $pub12['prix'];?> &euro;)</label>

                        <select name="day_en_tete_liste_<?php echo $cnt1; ?>" id="day_en_tete_liste_<?php echo $cnt1; ?>" style="width:80px;">

                            <option value="">0 jour</option>

                            <option value="1">1 jour</option>

                            <option value="2">2 jour(s)</option>

                            <option value="3">3 jour(s)</option>

                        </select> Avant la date de début 

                        Pendant

                        <select name="en_tete_liste_day_<?php echo $cnt1; ?>" id="en_tete_liste_day_<?php echo $cnt1; ?>" style="width:150px;">

                            <?php for($j=1; $j<=15; $j++){?>

                                <option value="<?php echo $j;?>"><?php echo $j;?> jour <?php if($j>1){?>(s)<?php }?> = <?php echo ($j*$pub12['prix']);?> &euro;</option>

                            <?php }?>

                        </select>

                        <?php if($pub12['description']!=''){?><a href="assets/popup_2/<?php echo $pub12['id'];?>.html" class="popupwindow" rel="windowCenter">(?)</a><?php }?>

                    

                </td>

            </tr>

            <?php } else {  ?> 

		 		<?php if (array_key_exists("dupliquer", $_GET)) {?>

            <tr>

            	<td>

                	<input type="checkbox" name="en_tete_liste_<?php echo $cnt1; ?>" id="en_tete_liste_<?php echo $cnt1; ?>" class="en_tete_liste" value="<?php echo $pub12['prix'];?>" />

                </td>

                <td>

                	<label for="en_tete_liste_<?php echo $cnt1; ?>"><?php echo $pub12['titre'];?> (<?php echo $pub12['prix'];?> &euro;)</label>

                        <select name="day_en_tete_liste_<?php echo $cnt1; ?>" id="day_en_tete_liste_<?php echo $cnt1; ?>" style="width:80px;">

                            <option value="">0 jour</option>

                            <option value="1">1 jour</option>

                            <option value="2">2 jour(s)</option>

                            <option value="3">3 jour(s)</option>

                        </select> Avant la date de début 

                        Pendant

                        <select name="en_tete_liste_day_<?php echo $cnt1; ?>" id="en_tete_liste_day_<?php echo $cnt1; ?>" style="width:150px;">

                            <?php for($j=1; $j<=15; $j++){?>

                                <option value="<?php echo $j;?>"><?php echo $j;?> jour <?php if($j>1){?>(s)<?php }?> = <?php echo ($j*$pub12['prix']);?> &euro;</option>

                            <?php }?>

                        </select>

                        <?php if($pub12['description']!=''){?><a href="assets/popup_2/<?php echo $pub12['id'];?>.html" class="popupwindow" rel="windowCenter">(?)</a><?php }?>

                    

                </td>

            </tr>

            <?php }else{?>

            		<?php if($evenement['en_tete_liste_fin']<$now){?>

                    <tr>

                        <td>

                            <input type="checkbox" name="en_tete_liste_<?php echo $cnt1; ?>" id="en_tete_liste_<?php echo $cnt1; ?>" class="en_tete_liste" value="<?php echo $pub12['prix'];?>" />

                        </td>

                        <td>

                            <label for="en_tete_liste_<?php echo $cnt1; ?>"><?php echo $pub12['titre'];?> (<?php echo $pub12['prix'];?> &euro;)</label>

                                <select name="day_en_tete_liste_<?php echo $cnt1; ?>" id="day_en_tete_liste_<?php echo $cnt1; ?>" style="width:80px;">

                                    <option value="">0 jour</option>

                                    <option value="1">1 jour</option>

                                    <option value="2">2 jour(s)</option>

                                    <option value="3">3 jour(s)</option>

                                </select> Avant la date de début 

                                Pendant

                                <select name="en_tete_liste_day_<?php echo $cnt1; ?>" id="en_tete_liste_day_<?php echo $cnt1; ?>" style="width:150px;">

                                    <?php for($j=1; $j<=15; $j++){?>

                                        <option value="<?php echo $j;?>"><?php echo $j;?> jour <?php if($j>1){?>(s)<?php }?> = <?php echo ($j*$pub12['prix']);?> &euro;</option>

                                    <?php }?>

                                </select>

                                <?php if($pub12['description']!=''){?><a href="assets/popup_2/<?php echo $pub12['id'];?>.html" class="popupwindow" rel="windowCenter">(?)</a><?php }?>

                            

                        </td>

                    </tr>

                	<?php }else{?>

                    <tr>

                        <td></td>

                        <td>

                            <label for="en_tete_liste_<?php echo $cnt1; ?>"><?php echo "L'évènement est en tête de liste jusqu'au ".KT_formatDate($evenement['en_tete_liste_fin']); ?></label>

                        </td>

                    </tr>

            		<?php } ?>

            	<?php } ?>

    		<?php } ?>

            

            <?php if(!$evenement['banner']) { ?>

            <tr>

            	<td>

                	<input type="checkbox" name="banner_<?php echo $cnt1; ?>" id="banner_<?php echo $cnt1; ?>" class="banner" value="" />

                </td>

                <td>

                	<label for="banner_<?php echo $cnt1; ?>">Publier le coupon dans la bannière haute</label> 

                    <span class="coupon_type"></span>

                    <span class="coupon_type_date"></span><?php if($pub1['description']!='' || $pub2['description']!=''){?><a href="assets/popup_2/<?php echo $pub1['id'];?>.html" class="popupwindow" rel="windowCenter">(?)</a> <a href="assets/popup_2/<?php echo $pub2['id'];?>.html" class="popupwindow" rel="windowCenter">(?)</a><?php }?>

                	

                </td>

            </tr>

            <?php } else {  ?>

				<?php if (array_key_exists("dupliquer", $_GET)) {?>

            <tr>

            	<td>

                	<input type="checkbox" name="banner_<?php echo $cnt1; ?>" id="banner_<?php echo $cnt1; ?>" class="banner" value="" />

                </td>

                <td>

                	<label for="banner_<?php echo $cnt1; ?>">Publier le coupon dans la bannière haute </label>

                    <span class="coupon_type"></span>

                    <span class="coupon_type_date"></span><?php if($pub1['description']!='' || $pub2['description']!=''){?><a href="assets/popup_2/<?php echo $pub1['id'];?>.html" class="popupwindow" rel="windowCenter">(?)</a> <a href="assets/popup_2/<?php echo $pub2['id'];?>.html" class="popupwindow" rel="windowCenter">(?)</a><?php }?>

                	

                </td>

            </tr>

            <?php }else{?>

            <tr>

            	<td></td>

                <td>

                <label for="banner_<?php echo $cnt1; ?>"><?php echo "Votre évènement est sur la bannière haute jusqu'au  ".KT_formatDate($evenement['banner_start']); ?></label>

                </td>

            </tr>

            	<?php } ?>

			<?php } ?> 



            

            <?php if(!$evenement['public']) { ?>

            <tr>

                <td>

                    <input style="margin: 7px 5px 0 0 !important;" type="checkbox" name="public_<?php echo $cnt1; ?>" id="public_<?php echo $cnt1; ?>" class="public" value="<?php echo $pub13['prix'];?>" />

                </td>

                <td>

                    <label for="public_<?php echo $cnt1; ?>"><?php echo $pub13['titre'];?> (<?php echo $pub13['prix'];?> &euro;)</label> <?php if($pub13['description']!=''){?><a href="assets/popup_2/<?php echo $pub13['id'];?>.html" class="popupwindow" rel="windowCenter">(?)</a><?php }?>

                </td>

            </tr>

            <?php } else {  ?> 

		 		<?php if (array_key_exists("dupliquer", $_GET)) {?>

            <tr>

                <td>

                    <input style="margin: 7px 5px 0 0 !important;" type="checkbox" name="public_<?php echo $cnt1; ?>" id="public_<?php echo $cnt1; ?>" class="public" value="<?php echo $pub13['prix'];?>" />

                </td>

                <td>

                    <label for="public_<?php echo $cnt1; ?>"><?php echo $pub13['titre'];?> (<?php echo $pub13['prix'];?> &euro;) </label><?php if($pub13['description']!=''){?><a href="assets/popup_2/<?php echo $pub13['id'];?>.html" class="popupwindow" rel="windowCenter">(?)</a><?php }?>

                </td>

            </tr>

            <?php }else{?>

            <tr>

            	<td></td>

                <td>

                    <label for="en_tete_liste_<?php echo $cnt1; ?>"><?php echo "L'évènement est en tête de liste jusqu'au ".KT_formatDate($evenement['public_start']); ?></label>

                </td>

            </tr>

            	<?php } ?>

    		<?php } ?>    

                

            </table>

        </td>

    </tr>

    

    

    

    

     

     

     

	<?php

	$check_free = "SELECT COUNT(event_id) AS free_event FROM evenements WHERE active='1' AND id_user = ".$_SESSION['kt_login_id']."";

	$query_free=mysql_query($check_free);

	$coupon_free=mysql_fetch_array($query_free);

	

	?>

    <tr>

    	<td colspan="2">

        	<input type="hidden" id="public_hidden" value="<?php 

			if($coupon_free['free_coupon']=='0'){

				echo '0';

			}else if($coupon['payer']=='1'){

				echo '0';

			}else{

				echo $pub['prix'];

			}?>" />

        	<input type="hidden" id="en_avant_hidden" value="" />

            <input type="hidden" id="en_tete_liste_hidden" value="" />

            

        	<label id="total">Total : <span id="show">

			<?php 

			if($coupon_free['free_coupon']=='0'){

				echo '0';

			}else if($coupon['payer']=='1'){

				echo '0';

			}else{

				echo $pub['prix'];

			}?>

            </span> &euro; <span id='total_coupon'></span><!--<span id='total_en_avant'></span> <span id='total_en_tete_liste'></span>--></label>

        </td>

    </tr> 

    

    

	<tr>

    	<td colspan="2">

    		<input type="hidden" name="total_banner" class="total_banner" id="total_banner" value=""> 

            <input type="hidden" name="kt_pk_evenements_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsevenements['kt_pk_evenements']); ?>" />

            <input type="hidden" name="approuve_<?php echo $cnt1; ?>" value="0" />

            <input type="hidden" name="id_user_<?php echo $cnt1; ?>" id="id_user_<?php echo $cnt1; ?>" value="<?php if($row_rsevenements['id_user']!=''){echo KT_escapeAttribute($row_rsevenements['id_user']);}else{echo $_SESSION['kt_login_id'];} ?>" />

            <?php } while ($row_rsevenements = mysql_fetch_assoc($rsevenements)); ?>

    

    

            <?php 

            // Show IF Conditional region1

            if (@$_GET['event_id'] == "") {

            ?>

            <input type="submit" name="KT_Insert1" class="image-submit" id="KT_Insert1" value="Valider" style="position:static;"/>

            <?php 

            // else Conditional region1

            } else { ?>

            	<?php if (array_key_exists("dupliquer", $_GET)) {?>

                <input type="submit" name="KT_Insert1" class="image-submit" id="KT_Insert1" value="Valider" style="position:static;"/>

                <?php }else{?>

                <input type="submit" class="image-submit" name="KT_Update1" style="position:static;" value="Valider"/>

                <input type="submit" class="image-submit" name="KT_Delete1" style="position:static;" value="<?php echo NXT_getResource("Delete_FB"); ?>" onclick="return confirm('<?php echo NXT_getResource("Are you sure?"); ?>');" />

            	<?php }?>

			<?php }

            // endif Conditional region1

            ?>

            &nbsp;<input type="button" class="image-submit" name="KT_Cancel1" value="<?php echo NXT_getResource("Cancel_FB"); ?>" onclick="return UNI_navigateCancel(event, 'includes/nxt/back.php')" />

        </td>

    </tr>

</table>

            </div>

              </form>

            <br class="clearfixplain" />



	</div>

  <!-- Sidebar Area -->

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

mysql_free_result($category_id);



mysql_free_result($liste_magasins);

?>