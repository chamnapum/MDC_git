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

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("nom_magazin", true, "text", "", "1", "80", "80 caractéres");
$formValidation->addField("region", true, "numeric", "", "", "", "");
$formValidation->addField("department", true, "numeric", "", "", "", "");
$formValidation->addField("ville", true, "numeric", "", "", "", "");
$formValidation->addField("adresse", true, "text", "", "", "", "");
if(!isset($_GET['id_magazin'])){
	$formValidation->addField("logo", true, "", "", "", "", "");
	$formValidation->addField("photo1", true, "", "", "", "", "");
}
$formValidation->addField("categorie", true, "numeric", "", "", "", "");
//$formValidation->addField("sous_categorie", true, "numeric", "", "", "", "");
$formValidation->addField("description", true, "text", "", "1", "800", "800 caractéres");
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
  $uploadObj->setResize("true", 220, 220);
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
  $uploadObj->setResize("true", 220, 220);
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
  $uploadObj->setResize("true", 220, 220);
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
  $uploadObj->setResize("true", 220, 220);
  $uploadObj->setMaxSize(1500);
  $uploadObj->setAllowedExtensions("gif, jpg, jpe, jpeg, png");
  $uploadObj->setRename("auto");
  return $uploadObj->Execute();
}
//end Trigger_ImageUpload trigger

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


	$magazin_id = $_GET['id_magazin'];
	$ville_id = $_REQUEST['ville_1'];
	$query_payer1 = "DELETE FROM ville_near WHERE id_magazin='".$magazin_id."'";
	$payer1 = mysql_query($query_payer1, $magazinducoin) or die('0'.mysql_error());
	
	if(isset($_REQUEST['ville_admin'])){
		$ville_near = $_REQUEST['ville_admin'];
		$No = sizeof($ville_near);
		//echo $No;
		$List1 ='';
			for($i=0; $i<$No; $i++){
				//$x_ville_near = ($ville_near[$i])==""? 0:$ville_near[$i];
				if($ville_near[$i]==''){
					
				}else{
					$x_ville_near = $ville_near[$i];
					$List1 .="('".$magazin_id."','".$ville_id."','".$x_ville_near."'),";	
				}
			}
			$Value1 = substr($List1,0,strlen($List1)-1);
			//echo $Value1;
			$sqlContratDetail = "INSERT INTO ville_near(
					id_magazin,id_ville,nom_ville_near)
					VALUES $Value1					
					";	
					mysql_query($sqlContratDetail);
	}
					
					
	/*if(isset($_REQUEST['ville_admin'])){
		$ville_near = $_REQUEST['ville_admin'];
		$No = sizeof($ville_near);
		//echo $No;
		$List1 ='';
			for($i=0; $i<$No; $i++){
				//$x_ville_near = ($ville_near[$i])==""? 0:$ville_near[$i];
				if($ville_near[$i]==''){
					
				}else{
					$x_ville_near = $ville_near[$i];
					$List1 .="('".$magazin_id."','".$ville_id."','".$x_ville_near."'),";	
				}
			}
			$Value1 = substr($List1,0,strlen($List1)-1);
			//echo $Value1;
			$sqlContratDetail = "INSERT INTO ville_near(
					id_magazin,id_ville,nom_ville_near)
					VALUES $Value1					
					";	
					mysql_query($sqlContratDetail);
	}
*/
	
	
	$query_payer1 = "SELECT payer FROM magazins WHERE id_magazin = ".$_GET['id_magazin'];
	$payer1 = mysql_query($query_payer1, $magazinducoin) or die('0'.mysql_error());
	$payer = mysql_fetch_assoc($payer1);
	
	if($_POST['en_avant_1'] or $_POST['en_tete_liste_1'] or $_POST['en_website_1'] or $_POST['en_facebook_1'] or $_POST['public_1'] or $payer['payer']!='1'){
		$_SESSION['montant_payer'] = 0;
		$_SESSION['options'] = array();
		
		if($payer['payer']!='1'){
			$_SESSION['montant_payer'] = $pub['prix'];
			$_SESSION['options'][] = 'paiement';
		}
		
		if($_POST['en_avant_1']) {
			$_SESSION['day_en_avant'] = $_POST['day_en_avant_1'];
			$_SESSION['montant_payer'] += ($pub11['prix']*$_POST['day_en_avant_1']);
			$_SESSION['options'][] = 'en_avant';
		}
		if($_POST['en_tete_liste_1']) {
			$_SESSION['day_en_tete_liste'] = $_POST['day_en_tete_liste_1'];
			$_SESSION['montant_payer'] += ($pub12['prix']*$_POST['day_en_tete_liste_1']);
			$_SESSION['options'][] = 'en_tete_liste';
		}
		if($_POST['en_website_1']) {
			$_SESSION['montant_payer'] += 5;
			$_SESSION['options'][] = 'en_website';
		}
		if($_POST['en_facebook_1']) {
			$_SESSION['montant_payer'] += 5;
			$_SESSION['options'][] = 'en_facebook';
		}
		if($_POST['public_1']) {
			$_SESSION['montant_payer'] += $pub13['prix'];
			$_SESSION['options'][] = 'public';
		}
		header('Location: payer_par_credit4.php?ids='.$_GET['id_magazin']);
		exit();
	}
}


function Trigger_do_payment(){
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
	
	$query_Recordset9 = "SELECT  max(id_magazin) as id, ville FROM magazins WHERE id_user = ".$_SESSION['kt_login_id'];
    $Recordset9 = mysql_query($query_Recordset9, $magazinducoin) or die('0'.mysql_error());
    $row_magazin = mysql_fetch_assoc($Recordset9);
	$magazin_id = $row_magazin['id'];
	$ville_id = $row_magazin['ville'];
	
	
	
	if(isset($_REQUEST['ville_admin'])){
		$ville_near = $_REQUEST['ville_admin'];
		$No = sizeof($ville_near);
		//echo $No;
		$List1 ='';
			for($i=0; $i<$No; $i++){
				//$x_ville_near = ($ville_near[$i])==""? 0:$ville_near[$i];
				if($ville_near[$i]==''){
					
				}else{
					$x_ville_near = $ville_near[$i];
					$List1 .="('".$magazin_id."','".$ville_id."','".$x_ville_near."'),";	
				}
			}
			$Value1 = substr($List1,0,strlen($List1)-1);
			//echo $Value1;
			$sqlContratDetail = "INSERT INTO ville_near(
					id_magazin,id_ville,nom_ville_near)
					VALUES $Value1					
					";	
					mysql_query($sqlContratDetail);
	}
	
	/*if(isset($_REQUEST['ville_near'])){
		$ville_near = $_REQUEST['ville_near'];
		$No = sizeof($ville_near);
		//echo $No;
		$List1 ='';
			for($i=0; $i<$No; $i++){
				//$x_ville_near = ($ville_near[$i])==""? 0:$ville_near[$i];
				if($ville_near[$i]==''){
					
				}else{
					$x_ville_near = $ville_near[$i];
					$List1 .="('".$magazin_id."','".$x_ville_near."'),";	
				}
			}
			$Value1 = substr($List1,0,strlen($List1)-1);
			//echo $Value1;
			$sqlContratDetail = "INSERT INTO ville_near(
					id_magazin,
					nom_ville_near)
					VALUES $Value1					
					";	
					mysql_query($sqlContratDetail);
	}*/
	
	
	$max_coupon_free='1';
	$query_regions = "SELECT COUNT(*) AS nb FROM magazins WHERE id_user = ".$_SESSION['kt_login_id'];
	$regions = mysql_query($query_regions) or die(mysql_error());
	$row_regions = mysql_fetch_assoc($regions);
	
	if($row_regions['nb']>$max_coupon_free){
		$rkt = "SELECT credit from utilisateur where id = ".$_SESSION['kt_login_id'];
		$query=mysql_query($rkt);
		$creditrow=mysql_fetch_array($query);
		if($creditrow['credit'] >= $pub['prix']){
			$_SESSION['options'] = array();
			$_SESSION['montant_payer'] = $pub['prix'];
			$_SESSION['options'][] = 'paiement';
			
			if($_POST['en_avant_1']) {
				$_SESSION['day_en_avant'] = $_POST['day_en_avant_1'];
				$_SESSION['montant_payer'] += ($pub11['prix']*$_POST['day_en_avant_1']);
				$_SESSION['options'][] = 'en_avant';
			}
			if($_POST['en_tete_liste_1']) {
				$_SESSION['day_en_tete_liste'] = $_POST['day_en_tete_liste_1'];
				$_SESSION['montant_payer'] += ($pub12['prix']*$_POST['day_en_tete_liste_1']);
				$_SESSION['options'][] = 'en_tete_liste';
			}
			if($_POST['en_website_1']) {
				$_SESSION['montant_payer'] += 5;
				$_SESSION['options'][] = 'en_website';
			}
			if($_POST['en_facebook_1']) {
				$_SESSION['montant_payer'] += 5;
				$_SESSION['options'][] = 'en_facebook';
			}
			if($_POST['public_1']) {
				$_SESSION['montant_payer'] += $pub13['prix'];
				$_SESSION['options'][] = 'public';
			}

			$pro="SELECT
				utilisateur.id
				, utilisateur.nom
				, utilisateur.prenom
				, utilisateur.email
				, magazins.nom_magazin
				, magazins.adresse
				, magazins.siren
			FROM
				magazins
				INNER JOIN utilisateur 
					ON (magazins.id_user = utilisateur.id) WHERE magazins.id_magazin = 
			  (SELECT 
				MAX(id_magazin) AS id_magazin 
			  FROM
				magazins 
			  WHERE magazins.id_user ='".$_SESSION['kt_login_id']."')";
			  
				$query_pro=mysql_query($pro);
				$result_pro=mysql_fetch_array($query_pro);
				SendMail_Create_Magasin_Shpper($result_pro['email'],$result_pro['nom'],$result_pro['prenom'],$result_pro['nom_magazin']);
				SendMail_Create_Magasin_Ownner($result_pro['email'],$result_pro['nom'],$result_pro['prenom'],$result_pro['nom_magazin'],$result_pro['adresse'],$result_pro['siren']);
					
			echo'<script>window.location="magazin_pay-'.$magazin_id.'.html";</script>';
			
			//header('Location: payer_par_credit2.php?ids='.mysql_insert_id().'&type=coupon');
			//Updater le seuil.
			exit();
		}else{
			
			$_SESSION['options'] = array();
			$_SESSION['montant_payer'] = $pub['prix'];
			$_SESSION['options'][] = 'paiement';
			
			if($_POST['en_avant_1']) {
				$_SESSION['day_en_avant'] = $_POST['day_en_avant_1'];
				$_SESSION['montant_payer'] += ($pub11['prix']*$_POST['day_en_avant_1']);
				$_SESSION['options'][] = 'en_avant';
			}
			if($_POST['en_tete_liste_1']) {
				$_SESSION['day_en_tete_liste'] = $_POST['day_en_tete_liste_1'];
				$_SESSION['montant_payer'] += ($pub12['prix']*$_POST['day_en_tete_liste_1']);
				$_SESSION['options'][] = 'en_tete_liste';
			}
			if($_POST['en_website_1']) {
				$_SESSION['montant_payer'] += 5;
				$_SESSION['options'][] = 'en_website';
			}
			if($_POST['en_facebook_1']) {
				$_SESSION['montant_payer'] += 5;
				$_SESSION['options'][] = 'en_facebook';
			}
			if($_POST['public_1']) {
				$_SESSION['montant_payer'] += $pub13['prix'];
				$_SESSION['options'][] = 'public';
			}
		
			$pro="SELECT
				utilisateur.id
				, utilisateur.nom
				, utilisateur.prenom
				, utilisateur.email
				, magazins.nom_magazin
				, magazins.adresse
				, magazins.siren
			FROM
				magazins
				INNER JOIN utilisateur 
					ON (magazins.id_user = utilisateur.id) WHERE magazins.id_magazin = 
			  (SELECT 
				MAX(id_magazin) AS id_magazin 
			  FROM
				magazins 
			  WHERE magazins.id_user ='".$_SESSION['kt_login_id']."')";
			  
					$query_pro=mysql_query($pro);
					$result_pro=mysql_fetch_array($query_pro);
					SendMail_Create_Magasin_Shpper($result_pro['email'],$result_pro['nom'],$result_pro['prenom'],$result_pro['nom_magazin']);
					SendMail_Create_Magasin_Ownner($result_pro['email'],$result_pro['nom'],$result_pro['prenom'],$result_pro['nom_magazin'],$result_pro['adresse'],$result_pro['siren']);
			
			/*echo'<script>window.location="payer_abonement.php?type=magazin&max_free='.$max_coupon_free.'&ids='.$magazin_id.'";</script>';*/  
			echo'<script>window.location="magazin_pays-'.$magazin_id.'.html";</script>';
			exit();
		
		}
			
	}else{
		$new_id = $magazin_id;
		$query_Recordset2 = "UPDATE magazins SET activate = 1, gratuit = 1, payer = 1 WHERE id_magazin = ".$new_id;
		$Recordset2 = mysql_query($query_Recordset2, $magazinducoin) or die(mysql_error());
		
		if($_POST['en_avant_1'] or $_POST['en_tete_liste_1'] or $_POST['en_website_1'] or $_POST['en_facebook_1'] or $_POST['public_1']){
		//if($_POST['en_tete_liste_1']){
			$_SESSION['montant_payer'] = 0;
			$_SESSION['options'] = array();
			if($_POST['en_avant_1']) {
				$_SESSION['day_en_avant'] = $_POST['day_en_avant_1'];
				$_SESSION['montant_payer'] += ($pub11['prix']*$_POST['day_en_avant_1']);
				$_SESSION['options'][] = 'en_avant';
			}
			if($_POST['en_tete_liste_1']) {
				$_SESSION['day_en_tete_liste'] = $_POST['day_en_tete_liste_1'];
				$_SESSION['montant_payer'] += ($pub12['prix']*$_POST['day_en_tete_liste_1']);
				$_SESSION['options'][] = 'en_tete_liste';
			}
			if($_POST['en_website_1']) {
				$_SESSION['montant_payer'] += 5;
				$_SESSION['options'][] = 'en_website';
			}
			if($_POST['en_facebook_1']) {
				$_SESSION['montant_payer'] += 5;
				$_SESSION['options'][] = 'en_facebook';
			}
			if($_POST['public_1']) {
				$_SESSION['montant_payer'] += $pub13['prix'];
				$_SESSION['options'][] = 'public';
			}
				$pro="SELECT
			utilisateur.id
			, utilisateur.nom
			, utilisateur.prenom
			, utilisateur.email
			, magazins.nom_magazin
			, magazins.adresse
			, magazins.siren
		FROM
			magazins
			INNER JOIN utilisateur 
				ON (magazins.id_user = utilisateur.id) WHERE magazins.id_magazin = 
		  (SELECT 
			MAX(id_magazin) AS id_magazin 
		  FROM
			magazins 
		  WHERE magazins.id_user ='".$_SESSION['kt_login_id']."')";
		  
				$query_pro=mysql_query($pro);
				$result_pro=mysql_fetch_array($query_pro);
				SendMail_Create_Magasin_Shpper($result_pro['email'],$result_pro['nom'],$result_pro['prenom'],$result_pro['nom_magazin']);
				SendMail_Create_Magasin_Ownner($result_pro['email'],$result_pro['nom'],$result_pro['prenom'],$result_pro['nom_magazin'],$result_pro['adresse'],$result_pro['siren']);
				
				echo'<script>window.location="payer_par_credit4.php?ids='.$new_id.'&type=mise_avant";</script>';
				//header('Location: payer_par_credit2.php?ids='.$new_id.'&type=mise_avant');
				exit();
		}else{
		
			$pro="SELECT
				utilisateur.id
				, utilisateur.nom
				, utilisateur.prenom
				, utilisateur.email
				, magazins.nom_magazin
				, magazins.adresse
				, magazins.siren
			FROM
				magazins
				INNER JOIN utilisateur 
					ON (magazins.id_user = utilisateur.id) WHERE magazins.id_magazin = 
			  (SELECT 
				MAX(id_magazin) AS id_magazin 
			  FROM
				magazins 
			  WHERE magazins.id_user ='".$_SESSION['kt_login_id']."')";
			  
					$query_pro=mysql_query($pro);
					$result_pro=mysql_fetch_array($query_pro);
					SendMail_Create_Magasin_Shpper($result_pro['email'],$result_pro['nom'],$result_pro['prenom'],$result_pro['nom_magazin']);
					SendMail_Create_Magasin_Ownner($result_pro['email'],$result_pro['nom'],$result_pro['prenom'],$result_pro['nom_magazin'],$result_pro['adresse'],$result_pro['siren']);
					
					header('Location: mes_magazins.html');
		
		}
		
	}
	
	
	
	
	
	
	
	
	
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

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_categories = "SELECT cat_id, cat_name FROM category WHERE parent_id = 0 AND type='3' ORDER BY cat_name ASC";
$categories = mysql_query($query_categories, $magazinducoin) or die(mysql_error());
$row_categories = mysql_fetch_assoc($categories);
$totalRows_categories = mysql_num_rows($categories);

if($_GET['id_magazin']!=''){
	$query_check = "SELECT id_magazin , id_user FROM magazins WHERE id_magazin='".$_GET['id_magazin']."' AND id_user= '".$_SESSION['kt_login_id']."'";
	$check = mysql_query($query_check) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	if(!$row_check){
		header('Location: mes-magazins.php');
	}
}



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
$ins_magazins->addColumn("department", "NUMERIC_TYPE", "POST", "department");
$ins_magazins->addColumn("ville", "NUMERIC_TYPE", "POST", "ville");
$ins_magazins->addColumn("adresse", "STRING_TYPE", "POST", "adresse");
$ins_magazins->addColumn("code_postal", "STRING_TYPE", "POST", "code_postal");
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

$ins_magazins->addColumn("categorie", "NUMERIC_TYPE", "POST", "categorie");
$ins_magazins->addColumn("sous_categorie", "NUMERIC_TYPE", "POST", "sous_categorie");
/*$ins_magazins->addColumn("sous_categorie2", "NUMERIC_TYPE", "POST", "sous_categorie2");*/


//$ins_magazins->addColumn("day_en_tete_liste", "STRING_TYPE", "POST", "day_en_tete_liste", "0");
$ins_magazins->addColumn("id_user", "STRING_TYPE", "SESSION", "kt_login_id");
$ins_magazins->setPrimaryKey("id_magazin", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_magazins = new tNG_multipleUpdate($conn_magazinducoin);
$tNGs->addTransaction($upd_magazins);
// Register triggers
$upd_magazins->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_magazins->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_magazins->registerTrigger("END", "Trigger_Default_Redirect", 99, "includes/nxt/back.php");
//$upd_magazins->registerTrigger("END", "Trigger_do_payment", 99);
$upd_magazins->registerTrigger("AFTER", "Trigger_verifier_mise_en_avant", 98);
$upd_magazins->registerTrigger("AFTER", "Trigger_ImageUpload", 97);
$upd_magazins->registerTrigger("AFTER", "Trigger_ImageUpload1", 97);
$upd_magazins->registerTrigger("AFTER", "Trigger_ImageUpload2", 97);
$upd_magazins->registerTrigger("AFTER", "Trigger_ImageUpload3", 97);
// Add columns
$upd_magazins->setTable("magazins");
$upd_magazins->addColumn("nom_magazin", "STRING_TYPE", "POST", "nom_magazin");
$upd_magazins->addColumn("siren", "STRING_TYPE", "POST", "siren");
$upd_magazins->addColumn("region", "NUMERIC_TYPE", "POST", "region");
$upd_magazins->addColumn("department", "NUMERIC_TYPE", "POST", "department");
$upd_magazins->addColumn("ville", "NUMERIC_TYPE", "POST", "ville");
$upd_magazins->addColumn("adresse", "STRING_TYPE", "POST", "adresse");
$upd_magazins->addColumn("code_postal", "STRING_TYPE", "POST", "code_postal");
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
$upd_magazins->addColumn("categorie", "NUMERIC_TYPE", "POST", "categorie");
$upd_magazins->addColumn("sous_categorie", "NUMERIC_TYPE", "POST", "sous_categorie");
//$upd_magazins->addColumn("day_en_tete_liste", "STRING_TYPE", "POST", "day_en_tete_liste", "0");
/*$upd_magazins->addColumn("sous_categorie2", "NUMERIC_TYPE", "POST", "sous_categorie2");*/
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
?>
<?php if(isset($_GET['id_magazin'])){
	$query_Recordset1 = "SELECT public, public_start, public_end, en_facebook, en_website, payer, en_avant_payer, en_avant_fin, en_tete_liste_payer, en_tete_liste_fin FROM magazins WHERE id_magazin = ".$_GET['id_magazin'];
	//echo $query_Recordset1;
	$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die('0'.mysql_error());
	$magasin = mysql_fetch_assoc($Recordset1);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 
	<title>Magasinducoin | Espace membre </title>
    <?php include("modules/head-detail.php"); ?>

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

$(document).ready(function() {
	
	$('.en_avant').click(function() {
		var total_en_avant = 0;
		var public_hidden = +$('#public_hidden').val();
		var en_tete_liste_hidden = +$('#en_tete_liste_hidden').val();
		
		var day_en_avant = $('#day_en_avant_1').val();
		var en_avant = $('#en_avant_1').val();
		total_en_avant = day_en_avant * en_avant;
		
		if($(".en_avant").is(':checked')){
			$('#day_en_avant_1').show();
			$('#en_avant_hidden').val(total_en_avant);
			var en_avant_hidden = $('#en_avant_hidden').val();
			$('#show').html(total_en_avant+public_hidden+en_tete_liste_hidden);
			
			en_avanss();
		}else if($(".en_avant").is(":not(:checked)")){
			$('#day_en_avant_1').hide();
			$('#en_avant_hidden').val('0');
			$('#show').html(public_hidden+en_tete_liste_hidden);
		}
	});
	
	function en_avanss(){
		$('#day_en_avant_1').change(function() {
			var day_en_avant = $('#day_en_avant_1').val();
			var en_avant = $('#en_avant_1').val();
			var total_en_avant = day_en_avant * en_avant;
			
			var en_avant_hiddens = $('#en_avant_hidden').val(total_en_avant);
			var public_hidden = +$('#public_hidden').val();
			var en_tete_liste_hidden = +$('#en_tete_liste_hidden').val();
			$('#show').html(total_en_avant+public_hidden+en_tete_liste_hidden);
			
		});
	}
	
	$('.en_tete_liste').click(function() {
		var total_en_tete_liste = 0;
		var public_hidden = +$('#public_hidden').val();
		var en_avant_hidden = +$('#en_avant_hidden').val();
		
		var day_en_tete_liste = $('#day_en_tete_liste_1').val();
		var en_tete_liste = $('#en_tete_liste_1').val();
		var total_en_tete_liste = day_en_tete_liste * en_tete_liste;
		
		if($(".en_tete_liste").is(':checked')){
			$('#day_en_tete_liste_1').show();
			$('#en_tete_liste_hidden').val(total_en_tete_liste);
			var en_tete_liste_hidden = $('#en_tete_liste_hidden').val();
			$('#show').html(total_en_tete_liste+public_hidden+en_avant_hidden);
			
			en_tete_listess();
		}else if($(".en_tete_liste").is(":not(:checked)")){
			$('#day_en_tete_liste_1').hide();
			$('#en_tete_liste_hidden').val('0');
			$('#show').html(public_hidden+en_avant_hidden);
		}
	});
	
	function en_tete_listess(){
		$('#day_en_tete_liste_1').change(function() {
			var day_en_tete_liste = $('#day_en_tete_liste_1').val();
			var en_tete_liste = $('#en_tete_liste_1').val();
			var total_en_tete_liste = day_en_tete_liste * en_tete_liste;
						
			var en_tete_liste_hidden = $('#en_tete_liste_hidden').val(total_en_tete_liste);
			var public_hidden = +$('#public_hidden').val();
			var en_avant_hidden = +$('#en_avant_hidden').val();
			$('#show').html(total_en_tete_liste+public_hidden+en_avant_hidden);
		});
	}

	$('.en_website').click(function() {
		var total = $('#public_hidden').val();
		var en_avant_hidden = $('#en_avant_hidden').val();
		var en_tete_liste_hidden = $('#en_tete_liste_hidden').val();
		
		$('#public_hidden').val(this.checked ? parseInt(this.value) + parseInt(total) : parseInt(total) - parseInt(this.value) );
		$('#show').html(this.checked ? parseInt(this.value) + parseInt(total) + parseInt(en_avant_hidden) + parseInt(en_tete_liste_hidden) :  parseInt(total) - parseInt(this.value) + parseInt(en_avant_hidden) + parseInt(en_tete_liste_hidden));
	});
	$('.en_facebook').click(function() {
		var total = $('#public_hidden').val();
		var en_avant_hidden = $('#en_avant_hidden').val();
		var en_tete_liste_hidden = $('#en_tete_liste_hidden').val();
		
		$('#public_hidden').val(this.checked ? parseInt(this.value) + parseInt(total) : parseInt(total) - parseInt(this.value) );
		$('#show').html(this.checked ? parseInt(this.value) + parseInt(total) + parseInt(en_avant_hidden) + parseInt(en_tete_liste_hidden) :  parseInt(total) - parseInt(this.value) + parseInt(en_avant_hidden) + parseInt(en_tete_liste_hidden));
	});
	$('.public').click(function() {
		//var total = $('#show').html();
		var total = $('#public_hidden').val();
		var en_avant_hidden = $('#en_avant_hidden').val();
		var en_tete_liste_hidden = $('#en_tete_liste_hidden').val();
		
		$('#public_hidden').val(this.checked ? parseInt(this.value) + parseInt(total) : parseInt(total) - parseInt(this.value) );
		$('#show').html(this.checked ? parseInt(this.value) + parseInt(total) + parseInt(en_avant_hidden) + parseInt(en_tete_liste_hidden) :  parseInt(total) - parseInt(this.value) + parseInt(en_avant_hidden) + parseInt(en_tete_liste_hidden));
	});
	
	
	$('#department_1').change(function() {
		var de = $(this).val();
		var dataString = 'id_departement='+de;
		$.ajax({
				type: "POST",
				url: "ajax/ville_admin.php",
				data: dataString,
				cache: false,
				success: function(datas){
					$("#ville_admin_1").html(datas);
				}
			});	
		return false;
	});	
	
	
	
});
</script>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=&sensor=true"></script>
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
    var address = "<?php echo ($row_default['adresse']); ?> <?php echo (getVilleById($row_default['ville'])); ?> <?php echo (getRegionById($row_default['region'])); ?> France";
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
        //alert("Geocode was not successful for the following reason: " + status);
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
        //alert("Geocode was not successful for the following reason: " + status);
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
  
</style>

	<!--<script type="text/javascript"src="http://code.jquery.com/jquery-latest.js"></script>-->
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
<?php /*?><body id="sp" onload=" 
<?php if(isset($_GET['id_magazin'])) { ?> 
	ajax('ajax/sous_categorie.php?default=<?php echo $row_rsmagazins['sous_categorie']; ?>&id_parent=<?php echo $row_rsmagazins['categorie']; ?>','#sous_categorie_1'); 
    ajax('ajax/department.php?default_1=<?php echo $row_rsmagazins['department']; ?>&id_region=<?php echo $row_rsmagazins['region']; ?>,'#department_1');
    ajax('ajax/ville.php?default=<?php echo $row_rsmagazins['ville']; ?>&id_departement=<?php echo $row_rsmagazins['department']; ?>,'#ville_1');
	<?php } ?>
	<?php if(!isset($_GET['no_new']) and !isset($_GET['conf'])) { ?>
    	 ajax('ajax/department.php?default_1=<?php echo $row_rsmagazins['department']; ?>&id_region=<?php echo $row_rsmagazins['region']; ?>,'#department_1');
        ajax('ajax/ville.php?default=<?php echo $row_rsmagazins['ville']; ?>&id_departement=<?php echo $row_rsmagazins['department']; ?>,'#ville_1');
	<?php } else if(isset($_GET['conf'])) { ?>
         ajax('ajax/department.php?default_1=<?php echo $row_rsmagazins['department']; ?>&id_region=<?php echo $row_rsmagazins['region']; ?>,'#department_1');
    	ajax('ajax/ville.php?default=<?php echo $row_rsmagazins['ville']; ?>&id_departement=<?php echo $row_rsmagazins['department']; ?>,'#ville_1');
	<?php } ?> initialize();"><?php */?>
<body id="sp" 
onload="<?php if(isset($_GET['id_magazin'])) { ?> 
	ajax('ajax/sous_categorie.php?default=<?php echo $row_rsmagazins['sous_categorie']; ?>&id_parent=<?php echo $row_rsmagazins['categorie']; ?>','#sous_categorie_1') ; 
    ajax('ajax/ville_admin.php?default=<?php echo $row_rsmagazins['id_magazin']; ?>&id_departement=<?php echo $row_rsmagazins['department']; ?>','#ville_admin_1') ; 
	<?php } ?>
	<?php if(!isset($_GET['no_new']) and !isset($_GET['conf'])) { ?>
    ajax('ajax/ville.php?default=<?php echo $row_rsmagazins['ville']; ?>&id_departement=<?php echo $row_rsmagazins['department']; ?>&id_region=<?php echo $row_rsmagazins['region']; ?>','#ville_1');
	<?php } else if(isset($_GET['conf'])) { ?>
    ajax('ajax/ville.php?default=<?php echo $row_default['ville']; ?>&id_departement=<?php echo $row_rsmagazins['department']; ?>&id_region=<?php echo $row_default['region']; ?>','#ville_1');
	<?php } ?> initialize()">




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
		margin: 7px 5px 0 0 !important;
	}
		  a.popupwindow{text-decoration:none; color:#9D216E !important;}
  a.popupwindow:hover{ color:#F8C263 !important}
	</style>
	<div style="float:left; width:100%;">
  	<h3 style="margin-left:20px;"><?php echo $xml->Magasin ?> : <?php if(array_key_exists("dupliquer", $_GET)){ }else{echo $row_rsmagazins['nom_magazin'];}?></h3>
          <?php
	echo $tNGs->getErrorMsg();
?>
          <!--<div class="KT_tng">
            <div class="KT_tngform">-->
              <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" enctype="multipart/form-data">
              <div style="margin-left:20px; float:left; width:98%;" class="loginForm">
              
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
<table cellpadding="0" cellspacing="0" border="0" width="50%" align="center">
<?php echo $_GET['conf'];?>
<?php if(!isset($_GET['conf'])) { ?>
<tr>
	<td>
    <label for="nom_magazin_<?php echo $cnt1; ?>"><?php echo $xml->Nom_du_magasin ;?>:</label>
    </td>
	<td>
    <input type="text" name="nom_magazin_<?php echo $cnt1; ?>" id="nom_magazin_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsmagazins['nom_magazin']); ?>" size="32" maxlength="250" onblur="localiser_adresse();" />
	<?php echo $tNGs->displayFieldHint("nom_magazin");?> <?php echo $tNGs->displayFieldError("magazins", "nom_magazin", $cnt1); ?>
    </td>
</tr>
<tr>
	<td>
    <label for="siren_<?php echo $cnt1; ?>">Siren :</label>
    </td>
	<td>
    <input type="text" name="siren_<?php echo $cnt1; ?>" id="siren_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsmagazins['siren']); ?>" size="30" maxlength="30" />
	<?php echo $tNGs->displayFieldHint("siren");?> <?php echo $tNGs->displayFieldError("magazins", "siren", $cnt1); ?>
    </td>
</tr>

<tr>
	<td>
    <label for="region_<?php echo $cnt1; ?>"><?php echo $xml->Region ?>:</label>
    </td>
	<td>
    <select name="region_<?php echo $cnt1; ?>" id="region_<?php echo $cnt1; ?>" onchange="ajax('ajax/department.php?default_1=<?php echo $row_rsmagazins['department']; ?>&id_region='+this.value,'#department_1'); localiser_adresse();">
    	<option value=""><?php echo $xml->selectionner ?></option>
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
    </td>
</tr>

<tr>
	<td>
    <label for="department_<?php echo $cnt1; ?>">Department:</label>
    </td>
	<td>
    <select name="department_<?php echo $cnt1; ?>" id="department_<?php echo $cnt1; ?>" onchange="ajax('ajax/ville.php?default=<?php echo $row_rsmagazins['ville']; ?>&id_departement='+this.value,'#ville_1'); localiser_adresse();">
    	<option value="">Department</option>  
        
     	<?php 
		mysql_select_db($database_magazinducoin, $magazinducoin);
		$query_department = "SELECT * FROM departement WHERE departement.code='".$row_rsmagazins['department']."' ORDER BY nom_departement ASC";
		$department = mysql_query($query_department, $magazinducoin) or die(mysql_error());
		$row_department = mysql_fetch_array($department);
		//$totalRows_regions = mysql_num_rows($regions);
		if($row_rsmagazins['department']!=''){
		?>
			<option value="<?php echo $row_department['code']?>" <?php if (!(strcmp($row_department['code'], $row_rsmagazins['department']))) {echo "SELECTED";} ?> title="<?php echo ($row_department['nom_departement']); ?>"><?php echo ($row_department['nom_departement']); ?></option>
		<?php }?>   
        

    </select>
    <?php echo $tNGs->displayFieldError("magazins", "department", $cnt1); ?> 
    </td>
</tr>

<tr>
	<td>
    <label for="ville_<?php echo $cnt1; ?>"><?php echo $xml->Ville ?>:</label>
    </td>
	<td>
    <?php 
	mysql_select_db($database_magazinducoin, $magazinducoin);
		$query_ville2 = "SELECT * FROM maps_ville WHERE id_ville='".$row_rsmagazins['ville']."' ORDER BY nom ASC";
		$ville2 = mysql_query($query_ville2, $magazinducoin) or die(mysql_error());
		$row_ville2 = mysql_fetch_array($ville2);
		//$totalRows_regions = mysql_num_rows($regions);
	?>
    <select name="ville_<?php echo $cnt1; ?>" id="ville_<?php echo $cnt1; ?>">
    	<option value=""><?php echo $xml->Region ?></option>  
        <?php if($row_rsmagazins['ville']!=''){?>
			<option value="<?php echo $row_ville2['id_ville']?>"<?php if (!(strcmp($row_ville2['id_ville'], $row_rsmagazins['ville']))) {echo "SELECTED";} ?> title="<?php echo ($row_ville2['nom']); ?>"><?php echo ($row_ville2['nom']); ?> <?php echo ($row_ville2['cp']); ?></option>
		<?php }?>    
    </select>
    <?php echo $tNGs->displayFieldError("magazins", "ville", $cnt1); ?> 
    </td>
</tr>

<tr>
	<td>
    
    </td>
	<td id="ville_admin_1">
		
    </td>
</tr>




<tr>
	<td>
    <label for="adresse_<?php echo $cnt1; ?>"><?php echo $xml->Adresse ?>:</label>
    </td>
	<td>
    <input type="text" name="adresse_<?php echo $cnt1; ?>" id="adresse_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsmagazins['adresse']); ?>" size="32" onblur="localiser_adresse();"  />
	<?php echo $tNGs->displayFieldHint("adresse");?> <?php echo $tNGs->displayFieldError("magazins", "adresse", $cnt1); ?>
    </td>
</tr>
<tr>
	<td>
    <label for="telephone_<?php echo $cnt1; ?>">Telephone:</label>
    </td>
	<td>
    <input type="text" name="telephone_<?php echo $cnt1; ?>" id="telephone_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsmagazins['telephone']); ?>" size="32" onblur="localiser_adresse();"  />
	<?php echo $tNGs->displayFieldHint("telephone");?> <?php echo $tNGs->displayFieldError("magazins", "telephone", $cnt1); ?>
    </td>
</tr>
<tr>
	<td>
    <label for="code_postal_<?php echo $cnt1; ?>">Code postal:</label>
    </td>
	<td>
    <input type="text" name="code_postal_<?php echo $cnt1; ?>" id="code_postal_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsmagazins['code_postal']); ?>" size="5" maxlength="5" />
    </td>
</tr>
<?php } else { ?>
<tr>
	<td>
    <label for="nom_magazin_<?php echo $cnt1; ?>"><?php echo $xml->Nom_du_magasin ;?>:</label>
    </td>
	<td>
    <input type="text" name="nom_magazin_<?php echo $cnt1; ?>" id="nom_magazin_<?php echo $cnt1; ?>" value="<?php echo $row_default['nom_magazin']; ?>" size="32" maxlength="250" onblur="localiser_adresse();" />
	<?php echo $tNGs->displayFieldHint("nom_magazin");?> <?php echo $tNGs->displayFieldError("magazins", "nom_magazin", $cnt1); ?>
    </td>
</tr>
<tr>
	<td>
    <label for="siren_<?php echo $cnt1; ?>">Siren</label>
    </td>
	<td>
    <input type="text" name="siren_<?php echo $cnt1; ?>" id="siren_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_default['siren']); ?>" size="30" maxlength="30" />
	<?php echo $tNGs->displayFieldHint("siren");?> <?php echo $tNGs->displayFieldError("magazins", "siren", $cnt1); ?>
    </td>
</tr>
<tr>
	<td>
    <label for="region_<?php echo $cnt1; ?>"><?php echo $xml->Region ?>:</label>
    </td>
	<td>
	<select name="region_<?php echo $cnt1; ?>" id="region_<?php echo $cnt1; ?>" onchange="ajax('ajax/ville.php?default=<?php echo $row_rsmagazins['ville']; ?>&id_region='+this.value,'#ville_1'); localiser_adresse();">
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
    <label for="ville_<?php echo $cnt1; ?>"><?php echo $xml->Ville ?>:</label>
    </td>
	<td>
    <select name="ville_<?php echo $cnt1; ?>" id="ville_<?php echo $cnt1; ?>" onchange="localiser_adresse();">
    	<option value=""><?php echo $xml->selectionner ?></option>   
    </select>
    <?php echo $tNGs->displayFieldError("magazins", "ville", $cnt1); ?>
    </td>
</tr>

<tr>
	<td>
    <label for="adresse_<?php echo $cnt1; ?>"><?php echo $xml->Adresse ?>:</label>
    </td>
	<td>
    <input type="text" name="adresse_<?php echo $cnt1; ?>" id="adresse_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_default['adresse']); ?>" size="32" onblur="localiser_adresse();"  />
	<?php echo $tNGs->displayFieldHint("adresse");?> <?php echo $tNGs->displayFieldError("magazins", "adresse", $cnt1); ?>
    </td>
</tr>
<tr>
	<td>
    <label for="code_postal_<?php echo $cnt1; ?>">Code postal:</label>
    </td>
	<td>
    <input type="text" name="code_postal_<?php echo $cnt1; ?>" id="code_postal_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_default['code_postal']); ?>" size="5" maxlength="5" />
	<?php echo $tNGs->displayFieldHint("code_postal");?> <?php echo $tNGs->displayFieldError("magazins", "code_postal", $cnt1); ?>
    </td>
</tr>
<?php } ?>

<tr>
	<td>
    <label for="categorie_<?php echo $cnt1; ?>"><?php echo $xml->Categorie ?>:</label>
    </td>
	<td>
    <select name="categorie_<?php echo $cnt1; ?>" id="categorie_<?php echo $cnt1; ?>" onchange="ajax('ajax/sous_categorie.php?default=<?php echo $row_rsmagazins['sous_categorie']; ?>&id_parent='+this.value,'#sous_categorie_<?php echo $cnt1; ?>');">
    <option value=""><?php echo $xml->selectionner ;?></option>
    <?php 
    do {  
    ?>
    <option value="<?php echo $row_categories['cat_id']?>"<?php if (!(strcmp($row_categories['cat_id'], $row_rsmagazins['categorie']))) {echo "SELECTED";} ?>><?php echo ($row_categories['cat_name']); ?></option>
    <?php } while ($row_categories = mysql_fetch_assoc($categories));
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
    <label for="sous_categorie_<?php echo $cnt1; ?>"><?php echo $xml->Sous_categorie; ?>:</label>
    </td>
    <td>
    <select name="sous_categorie_<?php echo $cnt1; ?>" id="sous_categorie_<?php echo $cnt1; ?>" onchange="ajax('ajax/sous_categorie.php?default=<?php echo $row_rsmagazins['sous_categorie']; ?>&id_parent='+this.value,'#sous_categorie2_<?php echo $cnt1; ?>');">
        <option value=""><?php echo $xml->selectionner ;?></option>
    </select>
    <?php echo $tNGs->displayFieldHint("sous_categorie");?> <?php echo $tNGs->displayFieldError("magazins", "sous_categorie", $cnt1); ?> 
    </td>
</tr>
<tr>
	<td>
    <label for="website_<?php echo $cnt1; ?>">Site Internet:</label>
    </td>
	<td>
    <input type="text" name="website_<?php echo $cnt1; ?>" id="website_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsmagazins['website']); ?>" size="32"/>
	<?php echo $tNGs->displayFieldHint("website");?> <?php echo $tNGs->displayFieldError("magazins", "website", $cnt1); ?>
    </td>
</tr>
<?php if(!$magasin['en_website']) { ?>
<tr>
    <td colspan="2">
    <label for="en_website_<?php echo $cnt1; ?>">Vous devez payer pour afficher votre siteweb sur front-end (5.00 &euro;)</label>
    <input type="checkbox" name="en_website_<?php echo $cnt1; ?>" id="en_website_<?php echo $cnt1; ?>" class="en_website" value="5" />
    </td>
</tr>
<?php }else{?>
	<?php if (array_key_exists("dupliquer", $_GET)) {?>
    <tr>
        <td colspan="2">
        <label for="en_website_<?php echo $cnt1; ?>">Vous devez payer pour afficher votre siteweb sur front-end (5.00 &euro;)</label>
        <input type="checkbox" name="en_website_<?php echo $cnt1; ?>" id="en_website_<?php echo $cnt1; ?>" class="en_website" value="5" />
        </td>
    </tr>
    <?php }else{?>
	<tr>
        <td>
        <label for="en_website_<?php echo $cnt1; ?>"><?php echo "Vous avez déjà payé pour cette option"; ?></label>
        </td>
    </tr>
    <?php }?>
<?php }?>
<tr>
	<td>
    <label for="facebook_<?php echo $cnt1; ?>">Facebook:</label>
    </td>
	<td>
    <input type="text" name="facebook_<?php echo $cnt1; ?>" id="facebook_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsmagazins['facebook']); ?>" size="32"/>
	<?php echo $tNGs->displayFieldHint("facebook");?> <?php echo $tNGs->displayFieldError("magazins", "facebook", $cnt1); ?>
    </td>
</tr>
<?php if(!$magasin['en_facebook']) { ?>
<tr>
    <td colspan="2">
    <label for="en_facebook_<?php echo $cnt1; ?>">Vous devez payer  pour afficher votre lien de Facebook sur front-end (5.00 &euro;)</label>
    <input type="checkbox" name="en_facebook_<?php echo $cnt1; ?>" id="en_facebook_<?php echo $cnt1; ?>" class="en_facebook" value="5" />
    </td>
</tr>
<?php }else{?>
	<?php if (array_key_exists("dupliquer", $_GET)) {?>
    <tr>
        <td colspan="2">
        <label for="en_facebook_<?php echo $cnt1; ?>">Vous devez payer  pour afficher votre lien de Facebook sur front-end (5.00 &euro;)</label>
        <input type="checkbox" name="en_facebook_<?php echo $cnt1; ?>" id="en_facebook_<?php echo $cnt1; ?>" class="en_facebook" value="5" />
        </td>
    </tr>
    <?php }else{?>
	<tr>
        <td>
        <label for="en_facebook_<?php echo $cnt1; ?>"><?php echo "Vous avez déjà payé pour cette option"; ?></label>
        </td>
    </tr>
    <?php }?>
<?php }?>
<tr>
	<td colspan="2" height="10">&nbsp;</td>
</tr>

<tr valign="top">
	<td>
    <label for="logo_<?php echo $cnt1; ?>"><?php echo $xml->Logo_de_magasin ?>:</label>
    </td>
	<td>
    
    <?php if($row_rsmagazins['logo']) { ?>
    	<?php if(array_key_exists("dupliquer", $_GET)) {?>
        	<div class="file-wrapper">
                <input type="file" name="logo_<?php echo $cnt1; ?>" id="logo_<?php echo $cnt1; ?>"/>
                <span class="button">Parcourir</span>
            </div>
        <?php }else{?>
        	<div class="file-wrapper">
                <input type="file" name="logo_<?php echo $cnt1; ?>" id="logo_<?php echo $cnt1; ?>"/>
                <span class="button">Parcourir</span>
            </div>
            <div id="img1">
                <img src="assets/images/magasins/<?php echo KT_escapeAttribute($row_rsmagazins['logo']); ?>" width="60" />&nbsp;&nbsp; <a href="javascript:ajax('ajax/supprimer_photo.php?t=magazins&c=logo&id=<?php echo $row_rsmagazins['id_magazin']; ?>&f=<?php echo KT_escapeAttribute($row_rsmagazins['logo']); ?>','#img1');" style="color:#333;"><?php echo $xml->Supprimer_logo ?></a>
            </div>
        <?php }?>
	<?php }else{?>
        <div class="file-wrapper">
            <input type="file" name="logo_<?php echo $cnt1; ?>" id="logo_<?php echo $cnt1; ?>"/>
            <span class="button">Parcourir</span>
        </div>
    <?php } ?>
    <?php echo $tNGs->displayFieldError("magazins", "logo", $cnt1); ?>
    </td>
</tr>

<tr valign="top">
	<td>
    <label for="photo1_<?php echo $cnt1; ?>">Photo du magasin:</label>
    </td>
	<td>
    <?php if($row_rsmagazins['photo1']) { ?>
    	<?php if(array_key_exists("dupliquer", $_GET)) {?>
        <div class="file-wrapper">
            <input type="file" name="photo1_<?php echo $cnt1; ?>" id="photo1_<?php echo $cnt1; ?>"/>
            <span class="button">Parcourir</span>
        </div>
        <?php }else{?>
        <div class="file-wrapper">
            <input type="file" name="photo1_<?php echo $cnt1; ?>" id="photo1_<?php echo $cnt1; ?>"/>
            <span class="button">Parcourir</span>
        </div>
        <div id="img2">
        	<img src="assets/images/magasins/<?php echo KT_escapeAttribute($row_rsmagazins['photo1']); ?>" width="60" />&nbsp;&nbsp; <a href="javascript:ajax('ajax/supprimer_photo.php?t=magazins&c=photo1&id=<?php echo $row_rsmagazins['id_magazin']; ?>&f=<?php echo KT_escapeAttribute($row_rsmagazins['photo1']); ?>','#img2');" style="color:#333;"><?php echo $xml->Supprimer_photo ?></a>
        </div> 
        <?php }?>
	<?php }else{?>
    	<div class="file-wrapper">
            <input type="file" name="photo1_<?php echo $cnt1; ?>" id="photo1_<?php echo $cnt1; ?>"/>
            <span class="button">Parcourir</span>
        </div>
    <?php } ?>
    <?php echo $tNGs->displayFieldError("magazins", "photo1", $cnt1); ?>
    </td>
</tr>
<tr valign="top">
	<td>
    <label for="photo2_<?php echo $cnt1; ?>">Photo 2 du magasin :</label>
    </td>
	<td>
    
    <?php if($row_rsmagazins['photo2']) { ?>
    	<?php if(array_key_exists("dupliquer", $_GET)) {?>
        	<div class="file-wrapper">
                <input type="file" name="photo2_<?php echo $cnt1; ?>" id="photo2_<?php echo $cnt1; ?>"/>
                <span class="button">Parcourir</span>
            </div>
        <?php }else{?>
        	<div class="file-wrapper">
                <input type="file" name="photo2_<?php echo $cnt1; ?>" id="photo2_<?php echo $cnt1; ?>"/>
                <span class="button">Parcourir</span>
            </div>
            <div id="img3">
                <img src="assets/images/magasins/<?php echo KT_escapeAttribute($row_rsmagazins['photo2']); ?>" width="60" />&nbsp;&nbsp; <a href="javascript:ajax('ajax/supprimer_photo.php?t=magazins&c=photo2&id=<?php echo $row_rsmagazins['id_magazin']; ?>&f=<?php echo KT_escapeAttribute($row_rsmagazins['photo2']); ?>','#img3');" style="color:#333;">Supprimer photo</a>
            </div> 
        <?php }?>
    <?php }else{?>
    	<div class="file-wrapper">
            <input type="file" name="photo2_<?php echo $cnt1; ?>" id="photo2_<?php echo $cnt1; ?>"/>
            <span class="button">Parcourir</span>
        </div>
    <?php } ?>
    <?php echo $tNGs->displayFieldError("magazins", "photo2", $cnt1); ?>
    </td>
</tr>
<tr valign="top">
	<td>
    <label for="photo3_<?php echo $cnt1; ?>">Photo 3 du magasin:</label>
    </td>
	<td>
    

    <?php if($row_rsmagazins['photo3']) { ?>
    	<?php if(array_key_exists("dupliquer", $_GET)) {?>
            <div class="file-wrapper">
                <input type="file" name="photo3_<?php echo $cnt1; ?>" id="photo3_<?php echo $cnt1; ?>"/>
                <span class="button">Parcourir</span>
            </div>
        <?php }else{?>
            <div class="file-wrapper">
                <input type="file" name="photo3_<?php echo $cnt1; ?>" id="photo3_<?php echo $cnt1; ?>"/>
                <span class="button">Parcourir</span>
            </div>
            <div id="img4">
                <img src="assets/images/magasins/<?php echo KT_escapeAttribute($row_rsmagazins['photo3']); ?>" width="60" />&nbsp;&nbsp; <a href="javascript:ajax('ajax/supprimer_photo.php?t=magazins&c=photo3&id=<?php echo $row_rsmagazins['id_magazin']; ?>&f=<?php echo KT_escapeAttribute($row_rsmagazins['photo3']); ?>','#img4');" style="color:#333;">Supprimer photo</a>
            </div>
        <?php }?>
	<?php }else{?>
        <div class="file-wrapper">
            <input type="file" name="photo3_<?php echo $cnt1; ?>" id="photo3_<?php echo $cnt1; ?>"/>
            <span class="button">Parcourir</span>
        </div>
    <?php } ?>
    <?php echo $tNGs->displayFieldError("magazins", "photo3", $cnt1); ?>    
    </td>
</tr>
<style>
	.day select{
		margin-top:0px;
	}
	.day input[type='checkbox']{
		margin:0px;
	}
</style>
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
<tr>
	<td colspan="2">
    	<table cellpadding="0" cellspacing="0" border="0" class="day" width="100%">
        	<tr>
            	<th align="left">Ouvert</th>
                <th colspan="3" align="left">Ouvert le midi</th>
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

<tr>
	<td>
    <label for="description_<?php echo $cnt1; ?>"><?php echo $xml->Description ; ?>:</label>
    </td>
	<td>
    <textarea name="description_<?php echo $cnt1; ?>" id="description_<?php echo $cnt1; ?>" cols="50" rows="3"><?php echo KT_escapeAttribute($row_rsmagazins['description']); ?></textarea>
	<?php echo $tNGs->displayFieldHint("description");?> <?php echo $tNGs->displayFieldError("magazins", "description", $cnt1); ?>
    </td>
</tr>
<tr>
	<td colspan="2">
    <label for="description_<?php echo $cnt1; ?>"><?php echo $xml->Localisation ?>:</label>
    </td>
</tr>
<tr>
	<td colspan="2">
    <div id="map_canvas" style="width:700px; height:380px;"></div>
	<input id="latlan" name="latlan" type="hidden" value="<?php echo KT_escapeAttribute($row_rsmagazins['latlan']); ?>" />
    </td>
</tr>

<tr>
	<td colspan="2">
    	&nbsp;
    	<table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid; font-size:13px; font-weight:bold;">
            	<tr>
                	<td colspan="2"><span style="font-size:16px; font-weight:bold;">Faites votre publicité</span></td>
                </tr>
        	<?php if(!$magasin['en_avant_payer']) { ?> 
            <tr>
            	<td width="5">
    				<input type="checkbox" name="en_avant_<?php echo $cnt1; ?>" id="en_avant_<?php echo $cnt1; ?>" class="en_avant" value="<?php echo $pub11['prix'];?>" />
                </td>
                <td>
                	<label for="en_avant_<?php echo $cnt1; ?>"><?php echo $pub11['titre'];?></label>
                    Pendant
                    <select name="day_en_avant_<?php echo $cnt1; ?>" id="day_en_avant_<?php echo $cnt1; ?>" style="width:150px;">
                    	<?php for($j=1; $j<=15; $j++){?>
                        	<option value="<?php echo $j;?>"><?php echo $j;?> jour <?php if($j>1){?>(s)<?php }?> = <?php echo ($j*$pub11['prix']);?> &euro;</option>
                        <?php }?>
                    </select>
                    <?php if($pub11['description']!=''){?><a href="assets/popup_2/<?php echo $pub11['id'];?>.html" class="popupwindow" rel="windowCenter">(?)</a><?php }?>
                    
                </td>
            </tr>
            <?php } else {  ?> 
				<?php if (array_key_exists("dupliquer", $_GET)) {?>
                <tr>
                    <td width="5">
                        <input type="checkbox" name="en_avant_<?php echo $cnt1; ?>" id="en_avant_<?php echo $cnt1; ?>" class="en_avant" value="<?php echo $pub11['prix'];?>" />
                    </td>
                    <td>
                        <label for="en_avant_<?php echo $cnt1; ?>"><?php echo $pub11['titre'];?></label>
                        Pendant
                        <select name="day_en_avant_<?php echo $cnt1; ?>" id="day_en_avant_<?php echo $cnt1; ?>" style="width:150px;">
                            <?php for($j=1; $j<=15; $j++){?>
                                <option value="<?php echo $j;?>"><?php echo $j;?> jour <?php if($j>1){?>(s)<?php }?> = <?php echo ($j*$pub11['prix']);?> &euro;</option>
                            <?php }?>
                        </select>
                        <?php if($pub11['description']!=''){?><a href="assets/popup_2/<?php echo $pub11['id'];?>.html" class="popupwindow" rel="windowCenter">(?)</a><?php }?>
                        
                    </td>
                </tr>
                <?php }else{?>
                	<?php if($magasin['en_avant_fin']<$now){?>
						<tr>
                            <td width="5">
                                <input type="checkbox" name="en_avant_<?php echo $cnt1; ?>" id="en_avant_<?php echo $cnt1; ?>" class="en_avant" value="<?php echo $pub11['prix'];?>" />
                            </td>
                            <td>
                                <label for="en_avant_<?php echo $cnt1; ?>"><?php echo $pub11['titre'];?></label>
                                Pendant
                                <select name="day_en_avant_<?php echo $cnt1; ?>" id="day_en_avant_<?php echo $cnt1; ?>" style="width:150px;">
                                    <?php for($j=1; $j<=15; $j++){?>
                                        <option value="<?php echo $j;?>"><?php echo $j;?> jour <?php if($j>1){?>(s)<?php }?> = <?php echo ($j*$pub11['prix']);?> &euro;</option>
                                    <?php }?>
                                </select>
                                <?php if($pub11['description']!=''){?><a href="assets/popup_2/<?php echo $pub11['id'];?>.html" class="popupwindow" rel="windowCenter">(?)</a><?php }?>
                                
                            </td>
                        </tr>
					<?php }else{?>
                    <tr>
                        <td></td>
                        <td>
                        <label for="en_avant_<?php echo $cnt1; ?>"><?php echo "Le magasin est en avant jusqu'à le ".KT_formatDate($magasin['en_avant_fin']); ?></label>
                        </td>
                    </tr>
                	<?php }?>
                <?php }?>
            <?php } ?>
        
        	<?php if(!$magasin['en_tete_liste_payer']) { ?>
            <tr>
                <td>	
                <input type="checkbox" name="en_tete_liste_<?php echo $cnt1; ?>" id="en_tete_liste_<?php echo $cnt1; ?>" class="en_tete_liste" value="<?php echo $pub12['prix'];?>" />
                </td>
                <td>
                	<?php /*?><label for="en_tete_liste_<?php echo $cnt1; ?>"><?php echo $pub12['titre'];?> (<?php echo $pub12['prix'];?> &euro;) <?php if($pub12['description']!=''){?><a href="assets/popup_2/<?php echo $pub12['id'];?>.html" class="popupwindow" rel="windowCenter">(?)</a><?php }?></label><?php */?>
                    <label for="en_tete_liste_<?php echo $cnt1; ?>"><?php echo $pub12['titre'];?></label>
                        Pendant
                        <select name="day_en_tete_liste_<?php echo $cnt1; ?>" id="day_en_tete_liste_<?php echo $cnt1; ?>" style="width:150px;">
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
                    <label for="en_tete_liste_<?php echo $cnt1; ?>"><?php echo $pub12['titre'];?></label>
                        Pendant
                        <select name="day_en_tete_liste_<?php echo $cnt1; ?>" id="day_en_tete_liste_<?php echo $cnt1; ?>" style="width:150px;">
                            <?php for($j=1; $j<=15; $j++){?>
                                <option value="<?php echo $j;?>"><?php echo $j;?> jour <?php if($j>1){?>(s)<?php }?> = <?php echo ($j*$pub12['prix']);?> &euro;</option>
                            <?php }?>
                        </select>
                        <?php if($pub12['description']!=''){?><a href="assets/popup_2/<?php echo $pub12['id'];?>.html" class="popupwindow" rel="windowCenter">(?)</a><?php }?>
                	
                    </td>
                </tr>
                <?php }else{?>
                	<?php if($magasin['en_tete_liste_fin']<$now){?>
                    <tr>
                    	<td>	
                        <input type="checkbox" name="en_tete_liste_<?php echo $cnt1; ?>" id="en_tete_liste_<?php echo $cnt1; ?>" class="en_tete_liste" value="<?php echo $pub12['prix'];?>" />
                        </td>
                        <td>
                        <label for="en_tete_liste_<?php echo $cnt1; ?>"><?php echo $pub12['titre'];?></label>
                            Pendant
                            <select name="day_en_tete_liste_<?php echo $cnt1; ?>" id="day_en_tete_liste_<?php echo $cnt1; ?>" style="width:150px;">
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
                        <label for="en_tete_liste_<?php echo $cnt1; ?>"><?php echo "Le magasin est en tête de liste jusqu'à le ".KT_formatDate($magasin['en_tete_liste_fin']); ?></label>
                        </td>
                    </tr>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
        	
            <?php if(!$magasin['public']) { ?>
            <tr>
                <td>
                <input type="checkbox" name="public_<?php echo $cnt1; ?>" id="public_<?php echo $cnt1; ?>" class="public" value="<?php echo $pub13['prix'];?>" />
                </td>
                <td>
                <label for="public_<?php echo $cnt1; ?>"><?php echo $pub13['titre'];?> (<?php echo $pub13['prix'];?> &euro;) </label><?php if($pub13['description']!=''){?><a href="assets/popup_2/<?php echo $pub13['id'];?>.html" class="popupwindow" rel="windowCenter">(?)</a><?php }?>
                </td>
            </tr>
            <?php } else {  ?>
                <?php if (array_key_exists("dupliquer", $_GET)) {?>
                <tr>
                    <td>
                    <input type="checkbox" name="public_<?php echo $cnt1; ?>" id="public_<?php echo $cnt1; ?>" class="public" value="<?php echo $pub13['prix'];?>" />
                    </td>
                    <td>
                    <label for="public_<?php echo $cnt1; ?>"><?php echo $pub13['titre'];?> (<?php echo $pub13['prix'];?> &euro;) </label><?php if($pub13['description']!=''){?><a href="assets/popup_2/<?php echo $pub13['id'];?>.html" class="popupwindow" rel="windowCenter">(?)</a><?php }?>
                    </td>
                </tr>
                <?php }else{?>
                <tr>
                	<td></td>
                    <td>
                    <label for="public_<?php echo $cnt1; ?>"><?php echo "Magasin est sur ​​la liste publique jusqu'à ce que ".KT_formatDate($magasin['public_start']); ?></label>
                    </td>
                </tr>
                <?php } ?>
            <?php } ?>
        
        </table>
    </td>
</tr>






<tr>

<?php
	$max_coupon_free='1';
	$query_regions = "SELECT COUNT(*) AS nb FROM magazins WHERE id_user = ".$_SESSION['kt_login_id'];
	$regions = mysql_query($query_regions) or die(mysql_error());
	$row_regions = mysql_fetch_assoc($regions);
	
	
	?>
    <tr>
    	<td>
        	<input type="hidden" id="public_hidden" value="<?php if($row_regions['nb']<=$max_coupon_free){?>0<?php }elseif($magasin['payer']!='1'){?><?php echo $pub['prix'];?><?php }else{?>0<?php }?>" />
        	<input type="hidden" id="en_avant_hidden" value="" />
            <input type="hidden" id="en_tete_liste_hidden" value="" />
            
            <label id="total">Total : <span id="show"><?php if($row_regions['nb']<=$max_coupon_free){?>0<?php }elseif($magasin['payer']!='1'){?><?php echo $pub['prix'];?><?php }else{?>0<?php }?></span> &euro; <!--<span id='total_en_avant'></span> <span id='total_en_tete_liste'></span>--></label>
        </td>
    </tr>     

	<td colspan="2">
    <p></p>

        <input type="hidden" name="kt_pk_magazins_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsmagazins['kt_pk_magazins']); ?>" />
        <?php } while ($row_rsmagazins = mysql_fetch_assoc($rsmagazins)); ?>
                    <?php 
      // Show IF Conditional region1
      if (@$_GET['id_magazin'] == "") {
      ?>
                      <input type="submit" class="image-submit" style="position:static;" name="KT_Insert1" id="KT_Insert1" value="<?php echo NXT_getResource("Insert_FB"); ?>" />
                      <?php 
      // else Conditional region1
      } else { ?>
		<?php if (array_key_exists("dupliquer", $_GET)) {?>
        	<input type="submit" class="image-submit" style="position:static;" name="KT_Insert1" id="KT_Insert1" value="<?php echo NXT_getResource("Insert_FB"); ?>" />
        <?php }else{?>
        	<input type="submit" class="image-submit" style="position:static;" name="KT_Update1" value="Valider" />
        <?php }?>
	<?php }
      // endif Conditional region1
      ?>
                    &nbsp;<input type="button" class="image-submit" style="position:static;" name="KT_Cancel1" value="Annuler" onclick="return UNI_navigateCancel(event, 'includes/nxt/back.php')"/>
                        
    </td>
</tr>  


                        </table>
                    </div>
                </form>
           <!-- </div>
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
mysql_free_result($day);

mysql_free_result($regions);

mysql_free_result($default);

mysql_free_result($Recordset9);
?>

