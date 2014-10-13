<?php

include('connection.php');



# FileName="Connection_php_mysql.htm"

# Type="MYSQL"

# HTTP="true"

/*$hostname_magazinducoin = "bikaycom.fatcowmysql.fr";

$database_magazinducoin = "magasin3_bdd";

$username_magazinducoin = "magasin3_develop";

$password_magazinducoin = "Sikofiko12";*/

$magazinducoin = mysql_pconnect($hostname_magazinducoin, $username_magazinducoin, $password_magazinducoin) or trigger_error(mysql_error(),E_USER_ERROR); 

//mysql_select_db($database_magazinducoin, $magazinducoin);



	mysql_query("SET character_set_results=utf8", $magazinducoin);

    mb_language('uni'); 

    mb_internal_encoding('UTF-8');

    mysql_select_db($database_magazinducoin, $magazinducoin);

    mysql_query("set names 'utf8'",$magazinducoin);



session_start();

if ( !isset( $_SESSION['Language'] )){

	$_SESSION['Language'] = 'fr'; //Français par défaut.

}

if(strpos($_SERVER['PHP_SELF'],'12admin3$/') === FALSE and strpos($_SERVER['PHP_SELF'],'ajax/') === FALSE){

	require_once '12admin3$/include/XMLEngine.php';

	$xml = new XMLEngine( '12admin3$/xml/website.xml', $_SESSION['Language'] );

}

else if(strpos($_SERVER['PHP_SELF'],'ajax/') !== FALSE){

	require_once '../12admin3$/include/XMLEngine.php';

	$xml = new XMLEngine( '../12admin3$/xml/website.xml', $_SESSION['Language'] );

}

else {

	$_SESSION['Language'] = 'fr'; 

	require_once 'include/XMLEngine.php';

	$xml = new XMLEngine( 'xml/website.xml', $_SESSION['Language'] );

}

			

$default_lan = "37.0625";

$default_lon = "-95.677068";

$default_api_gmaps = 'ABQIAAAAB-FHeR1w_90UqkS6N_68TRQjmYocDTszwqxGpI5DZaqmGhUdBxTBGYbTy6f2wVDRoldNtE8TkYTvlg';



$default_region = $_REQUEST['region'];



if($default_region){

	//$default_region = $_GET['region'];

	$_SESSION['region'] = $_REQUEST['region'];

}else if($_SESSION['region']){

	$default_region = $_SESSION['region'];

}else{

	$default_region = 0;	

}

//$default_region = 0;

//

//if(isset($_SESSION['region']))

//	$default_region = $_SESSION['region'];

//	

//if(isset($_GET['region'])){

//	$default_region 	= $_GET['region'];

//	$_SESSION['region'] = $_GET['region'];

//}



define('NOUVEAUTE',1);

define('VENTE_FLASH',2);

define('PRIX_CHOC',3);

define('REGION_4',4);

define('REGION_5',5);

	

	$URL_PATH='';

	$URL='';

	if(strpos($_SERVER['PHP_SELF'],'region.php') !== FALSE){  

		$URL_PATH='Produits';

		$URL=$_SERVER['REQUEST_URI'];

		if(isset($URL)){

			$_SESSION['url']='';

			$_SESSION['url_path']='';

		}

		$_SESSION['url_sub']='';

		$_SESSION['url_path_sub']='';

	}elseif(strpos($_SERVER['PHP_SELF'],'rechercher.php') !== FALSE){  

		$URL_PATH='Produits';

		$URL=$_SERVER['REQUEST_URI'];

		if(isset($URL)){

			$_SESSION['url']=$URL;

			$_SESSION['url_path']=$URL_PATH;

		}

		$_SESSION['url_sub']='';

		$_SESSION['url_path_sub']='';

	}elseif(strpos($_SERVER['PHP_SELF'],'rechercher_cpn.php') !== FALSE){

		$URL_PATH='Coupons';

		$URL=$_SERVER['REQUEST_URI'];

		if(isset($URL)){

			$_SESSION['url']=$URL;

			$_SESSION['url_path']=$URL_PATH;

		}

		$_SESSION['url_sub']='';

		$_SESSION['url_path_sub']='';

	}elseif(strpos($_SERVER['PHP_SELF'],'pcal.php') !== FALSE){

		$URL_PATH='&Eacute;v&egrave;nements';

		$URL=$_SERVER['REQUEST_URI'];

		if(isset($URL)){

			$_SESSION['url']=$URL;

			$_SESSION['url_path']=$URL_PATH;

		}

		$_SESSION['url_sub']='';

		$_SESSION['url_path_sub']='';

	}elseif(strpos($_SERVER['PHP_SELF'],'liste_magasins.php') !== FALSE){ 

		$URL_PATH='Magasins';

		$URL=$_SERVER['REQUEST_URI'];

		if(isset($URL)){

			$_SESSION['url']=$URL;

			$_SESSION['url_path']=$URL_PATH;

		}

		$_SESSION['url_sub']='';

		$_SESSION['url_path_sub']='';

	}

	

	if(strpos($_SERVER['PHP_SELF'],'detail_produit.php') !== FALSE){

		$URL_PATH_SUB='Produits detail';

		$URL_SUB=$_SERVER['REQUEST_URI'];

		if(isset($URL_SUB)){

			$_SESSION['url_sub']=$URL_SUB;

			$_SESSION['url_path_sub']=$URL_PATH_SUB;

		}

	}elseif(strpos($_SERVER['PHP_SELF'],'detail_magasin.php') !== FALSE){

		$URL_PATH_SUB='Magasins detail';

		$URL_SUB=$_SERVER['REQUEST_URI'];

		if(isset($URL_SUB)){

			$_SESSION['url_sub']=$URL_SUB;

			$_SESSION['url_path_sub']=$URL_PATH_SUB;

		}

	}elseif(strpos($_SERVER['PHP_SELF'],'detail_event.php') !== FALSE){

		$URL_PATH_SUB='Even detail';

		$URL_SUB=$_SERVER['REQUEST_URI'];

		if(isset($URL_SUB)){

			$_SESSION['url_sub']=$URL_SUB;

			$_SESSION['url_path_sub']=$URL_PATH_SUB;

		}

	}	

	

function getVilleById($id){

	$query_villes = "SELECT nom FROM maps_ville WHERE cp = $id";

	$villes = mysql_query($query_villes) or die(mysql_error());

	$row_villes = mysql_fetch_assoc($villes);

	return $row_villes['nom'];

}



function getRegionById($id){

	$query_villes = "SELECT nom_region FROM region WHERE id_region = $id";

	$villes = mysql_query($query_villes) or die(mysql_error());

	$row_villes = mysql_fetch_assoc($villes);

	return $row_villes['nom_region'];

}



function dbtodate($date){

	$tab = explode('-',$date);

	return $tab[2]."-".$tab[1]."-".$tab[0];

}





function getReduction($id_magazin, $categorie, $sous_categorie){

	$query_villes = "SELECT reduction FROM coupons 

	WHERE id_magasin = $id_magazin AND ((categories = $categorie AND sous_categorie = $sous_categorie) OR min_achat > 0)";

	//echo $query_villes ;

	$villes = mysql_query($query_villes) or die(mysql_error());

	$row_villes = mysql_fetch_assoc($villes);

	return $row_villes['reduction'];

}

// Short Code

function selectjob ($type) {

	$namepro=mysql_fetch_array(mysql_query("select info from tbl_home where type='".$type."'"));

	return $namepro['info'];

} 

function selectdatacntent ($contentdata,$Name='',$Username='',$nom_magazin='',$adresse='',$siren='',$telephone='',$title='') {

	

	// []

	//selectdatacntent($title['info'],$query['nom'])

	

	$regex = "/\[(.*?)\]/";

	preg_match_all($regex, $contentdata, $matches);

	for($i = 0; $i < count($matches[1]); $i++){

		$match = $matches[1][$i];

			if($match=='namewebsite'){

				// [namewebsite]

				$name=selectjob('namewebsite');

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}else if($match=='job'){

				$name=selectjob('job');

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}else if($match=='Name'){

				$name=$Name;

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}else if($match=='username'){

				$name=$Username;

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}else if($match=='nom_magazin'){

				$name=$nom_magazin;

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}else if($match=='adresse'){

				$name=$adresse;

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}else if($match=='siren'){

				$name=$siren;

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}else if($match=='telephone'){

				$name=$telephone;

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}else if($match=='Title'){

				$name=$title;

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}

	}

	return $contentdata;

}



// Send Mail when (subscribe) 

function SendMail_User($email,$id,$nom,$prenom){
	

	$mail = "SELECT * FROM mail_send WHERE id = '1'";

	$query_mail = mysql_query($mail) or die(mysql_error());

	$row_mail = mysql_fetch_assoc($query_mail);

	

	

	$to2 = $email;

	$subject2 = $row_mail['subject'];

	$content=selectdatacntent($row_mail['content'],$nom,$nom.' '.$prenom);

	$message2 = '<html>

				<head>

				<style>

				.heading {border: solid 1px #000000;}

				</style>

				</head>

				<body>

				'.$content.'

				<a href="http://magasinducoin.fr/activated.php?kt_login_id='.$id.'&kt_login_email='.$email.'" target="_blank">Activation</a></p>

				<p>Pour vous connecter sur magasinducoin, veuillez cliquer sur le lien suivant:<br>

				<a href="http://magasinducoin.fr/authetification.php" target="_blank">authetification</a></p>

				<p><a href="http://magasinducoin.fr/" target="_blank">

					<img src="http://magasinducoin.fr/assets/images/logo.png" alt=""/>

				</a></p>

				</body>

				</html>

				';

	

	// Always set content-type when sending HTML email

	$headers2 = "MIME-Version: 1.0" . "\r\n";

	$headers2 .= "Content-type:text/html; charset=UTF-8" . "\r\n";

	

	// More headers

	$headers2 .= 'From: <noreply@magasinducoin.fr>' . "\r\n";

	//$headers .= 'Cc: myboss@example.fr' . "\r\n";

	

	$send_contact2 = mail($to2,($subject2),($message2),$headers2);

								

	// Check, if message sent to your email

	// display message "We've recived your information"

	if($send_contact2){

		//echo '<p style="font-size:26px; color:#FA9452;">Votre don est pris en compte.</p>';

	}else {

		echo "ERROR";

	}

}



function SendMail_Shopper($email,$nom,$prenom){

	$mail = "SELECT * FROM mail_send WHERE id = '2'";

	$query_mail = mysql_query($mail) or die(mysql_error());

	$row_mail = mysql_fetch_assoc($query_mail);

	

	

	$to2 = $email;

	$subject2 = $row_mail['subject'];

	$content=selectdatacntent($row_mail['content'],$nom,$nom.' '.$prenom);

	$message2 = '<html>

				<head>

				<style>

				.heading {border: solid 1px #000000;}

				</style>

				</head>

				<body>

				'.$content.'

				<p><a href="http://magasinducoin.fr/" target="_blank">

					<img src="http://magasinducoin.fr/assets/images/logo.png" alt=""/>

				</a></p>

				</body>

				</html>

				';

	

	// Always set content-type when sending HTML email

	$headers2 = "MIME-Version: 1.0" . "\r\n";

	$headers2 .= "Content-type:text/html; charset=UTF-8" . "\r\n";

	

	// More headers

	$headers2 .= 'From: <noreply@magasinducoin.fr>' . "\r\n";

	//$headers .= 'Cc: myboss@example.fr' . "\r\n";

	

	$send_contact2 = mail($to2,($subject2),($message2),$headers2);

								

	// Check, if message sent to your email

	// display message "We've recived your information"

	if($send_contact2){

		//echo '<p style="font-size:26px; color:#FA9452;">Votre don est pris en compte.</p>';

	}else {

		echo "ERROR";

	}

}



function SendMail_Shopper_Webmaster($email,$nom,$prenom,$nom_magazin,$adresse,$siren,$telephone){

	$mail = "SELECT * FROM mail_send WHERE id = '4'";

	$query_mail = mysql_query($mail) or die(mysql_error());

	$row_mail = mysql_fetch_assoc($query_mail);

	

	$sub_content=selectdatacntent($row_mail['subject'],$nom);

	

	$to2 = 'validation@magasinducoin.fr';//

	$subject2 = $sub_content;

	$content=selectdatacntent($row_mail['content'],$nom,$nom.' '.$prenom,$nom_magazin,$adresse,$siren,$telephone);

	$message2 = '<html>

				<head>

				<style>

				.heading {border: solid 1px #000000;}

				</style>

				</head>

				<body>

				'.$content.'

				<p><a href="http://magasinducoin.fr/" target="_blank">

					<img src="http://magasinducoin.fr/assets/images/logo.png" alt=""/>

				</a></p>

				</body>

				</html>

				';

	

	// Always set content-type when sending HTML email

	$headers2 = "MIME-Version: 1.0" . "\r\n";

	$headers2 .= "Content-type:text/html; charset=UTF-8" . "\r\n";

	

	// More headers

	$headers2 .= 'From: <validation@magasinducoin.fr>' . "\r\n";

	//$headers .= 'Cc: myboss@example.fr' . "\r\n";

	

	$send_contact2 = mail($to2,($subject2),($message2),$headers2);

								

	// Check, if message sent to your email

	// display message "We've recived your information"

	if($send_contact2){

		//echo '<p style="font-size:26px; color:#FA9452;">Votre don est pris en compte.</p>';

	}else {

		echo "ERROR";

	}

}



function SendMail_Photographe($email,$nom,$prenom){

	$mail = "SELECT * FROM mail_send WHERE id = '3'";

	$query_mail = mysql_query($mail) or die(mysql_error());

	$row_mail = mysql_fetch_assoc($query_mail);

	

	

	$to2 = $email;

	$subject2 = $row_mail['subject'];

	$content=selectdatacntent($row_mail['content'],$nom,$nom.' '.$prenom);

	$message2 = '<html>

				<head>

				<style>

				.heading {border: solid 1px #000000;}

				</style>

				</head>

				<body>

				'.$content.'

				<p><a href="http://magasinducoin.fr/" target="_blank">

					<img src="http://magasinducoin.fr/assets/images/logo.png" alt=""/>

				</a></p>

				</body>

				</html>

				';

	

	// Always set content-type when sending HTML email

	$headers2 = "MIME-Version: 1.0" . "\r\n";

	$headers2 .= "Content-type:text/html; charset=UTF-8" . "\r\n";

	

	// More headers

	$headers2 .= 'From: <noreply@magasinducoin.fr>' . "\r\n";

	//$headers .= 'Cc: myboss@example.fr' . "\r\n";

	

	$send_contact2 = mail($to2,($subject2),($message2),$headers2);

								

	// Check, if message sent to your email

	// display message "We've recived your information"

	if($send_contact2){

		//echo '<p style="font-size:26px; color:#FA9452;">Votre don est pris en compte.</p>';

	}else {

		echo "ERROR";

	}

}



function SendMail_Photographe_Webmaster($email,$nom,$prenom,$siren,$telephone){

	$mail = "SELECT * FROM mail_send WHERE id = '5'";

	$query_mail = mysql_query($mail) or die(mysql_error());

	$row_mail = mysql_fetch_assoc($query_mail);

	

	$sub_content=selectdatacntent($row_mail['subject'],$nom);

	

	$to2 = 'validation@magasinducoin.fr';//

	$subject2 = $sub_content;

	$content=selectdatacntent($row_mail['content'],$nom,$nom.' '.$prenom,'','',$siren,$telephone);

	$message2 = '<html>

				<head>

				<style>

				.heading {border: solid 1px #000000;}

				</style>

				</head>

				<body>

				'.$content.'

				<p><a href="http://magasinducoin.fr/" target="_blank">

					<img src="http://magasinducoin.fr/assets/images/logo.png" alt=""/>

				</a></p>

				</body>

				</html>

				';

	

	// Always set content-type when sending HTML email

	$headers2 = "MIME-Version: 1.0" . "\r\n";

	$headers2 .= "Content-type:text/html; charset=UTF-8" . "\r\n";

	

	// More headers

	$headers2 .= 'From: <validation@magasinducoin.fr>' . "\r\n";

	//$headers .= 'Cc: myboss@example.fr' . "\r\n";

	

	$send_contact2 = mail($to2,($subject2),($message2),$headers2);

								

	// Check, if message sent to your email

	// display message "We've recived your information"

	if($send_contact2){

		//echo '<p style="font-size:26px; color:#FA9452;">Votre don est pris en compte.</p>';

	}else {

		echo "ERROR";

	}

}



function SendMail_Ownner_Shpper_approve($email,$nom,$prenom){

	$mail = "SELECT * FROM mail_send WHERE id = '6'";

	$query_mail = mysql_query($mail) or die(mysql_error());

	$row_mail = mysql_fetch_assoc($query_mail);

	

	$sub_content=selectdatacntent($row_mail['subject'],$nom,$nom.' '.$prenom);

	

	$to2 = $email;

	$subject2 = $sub_content;

	$content=selectdatacntent($row_mail['content'],$nom,$nom.' '.$prenom);

	$message2 = '<html>

				<head>

				<style>

				.heading {border: solid 1px #000000;}

				</style>

				</head>

				<body>

				'.$content.'

				<p><a href="http://magasinducoin.fr/" target="_blank">

					<img src="http://magasinducoin.fr/assets/images/logo.png" alt=""/>

				</a></p>

				</body>

				</html>

				';

	

	// Always set content-type when sending HTML email

	$headers2 = "MIME-Version: 1.0" . "\r\n";

	$headers2 .= "Content-type:text/html; charset=UTF-8" . "\r\n";

	

	// More headers

	$headers2 .= 'From: <noreply@magasinducoin.fr>' . "\r\n";

	//$headers .= 'Cc: myboss@example.fr' . "\r\n";

	

	$send_contact2 = mail($to2,($subject2),($message2),$headers2);

								

	// Check, if message sent to your email

	// display message "We've recived your information"

	if($send_contact2){

		//echo '<p style="font-size:26px; color:#FA9452;">Votre don est pris en compte.</p>';

		echo'<script>window.location="utilisateurs.php?info=ACTIVATED";</script>';

	}else {

		echo "ERROR";

	}

}



function SendMail_Ownner_Shpper_unapprove($email,$nom,$prenom){

	$mail = "SELECT * FROM mail_send WHERE id = '7'";

	$query_mail = mysql_query($mail) or die(mysql_error());

	$row_mail = mysql_fetch_assoc($query_mail);

	

	$sub_content=selectdatacntent($row_mail['subject'],$nom,$nom.' '.$prenom);

	

	$to2 = $email;

	$subject2 = $sub_content;

	$content=selectdatacntent($row_mail['content'],$nom,$nom.' '.$prenom);

	$message2 = '<html>

				<head>

				<style>

				.heading {border: solid 1px #000000;}

				</style>

				</head>

				<body>

				'.$content.'

				<p><a href="http://magasinducoin.fr/" target="_blank">

					<img src="http://magasinducoin.fr/assets/images/logo.png" alt=""/>

				</a></p>

				</body>

				</html>

				';

	

	// Always set content-type when sending HTML email

	$headers2 = "MIME-Version: 1.0" . "\r\n";

	$headers2 .= "Content-type:text/html; charset=UTF-8" . "\r\n";

	

	// More headers

	$headers2 .= 'From: <noreply@magasinducoin.fr>' . "\r\n";

	//$headers .= 'Cc: myboss@example.fr' . "\r\n";

	

	$send_contact2 = mail($to2,($subject2),($message2),$headers2);

								

	// Check, if message sent to your email

	// display message "We've recived your information"

	if($send_contact2){

		//echo '<p style="font-size:26px; color:#FA9452;">Votre don est pris en compte.</p>';

		echo'<script>window.location="utilisateurs.php?info=UNACTIVATED";</script>';

	}else {

		echo "ERROR";

	}

}



function SendMail_Ownner_Photographe_approve($email,$nom,$prenom){

	$mail = "SELECT * FROM mail_send WHERE id = '8'";

	$query_mail = mysql_query($mail) or die(mysql_error());

	$row_mail = mysql_fetch_assoc($query_mail);

	

	$sub_content=selectdatacntent($row_mail['subject'],$nom,$nom.' '.$prenom);

	

	$to2 = $email;

	$subject2 = $sub_content;

	$content=selectdatacntent($row_mail['content'],$nom,$nom.' '.$prenom);

	$message2 = '<html>

				<head>

				<style>

				.heading {border: solid 1px #000000;}

				</style>

				</head>

				<body>

				'.$content.'

				<p><a href="http://magasinducoin.fr/" target="_blank">

					<img src="http://magasinducoin.fr/assets/images/logo.png" alt=""/>

				</a></p>

				</body>

				</html>

				';

	

	// Always set content-type when sending HTML email

	$headers2 = "MIME-Version: 1.0" . "\r\n";

	$headers2 .= "Content-type:text/html; charset=UTF-8" . "\r\n";

	

	// More headers

	$headers2 .= 'From: <noreply@magasinducoin.fr>' . "\r\n";

	//$headers .= 'Cc: myboss@example.fr' . "\r\n";

	

	$send_contact2 = mail($to2,($subject2),($message2),$headers2);

								

	// Check, if message sent to your email

	// display message "We've recived your information"

	if($send_contact2){

		//echo '<p style="font-size:26px; color:#FA9452;">Votre don est pris en compte.</p>';

		echo'<script>window.location="utilisateurs.php?info=ACTIVATED";</script>';

	}else {

		echo "ERROR";

	}

}



function SendMail_Ownner_Photographe_unapprove($email,$nom,$prenom){

	$mail = "SELECT * FROM mail_send WHERE id = '9'";

	$query_mail = mysql_query($mail) or die(mysql_error());

	$row_mail = mysql_fetch_assoc($query_mail);

	

	$sub_content=selectdatacntent($row_mail['subject'],$nom,$nom.' '.$prenom);

	

	$to2 = $email;

	$subject2 = $sub_content;

	$content=selectdatacntent($row_mail['content'],$nom,$nom.' '.$prenom);

	$message2 = '<html>

				<head>

				<style>

				.heading {border: solid 1px #000000;}

				</style>

				</head>

				<body>

				'.$content.'

				<p><a href="http://magasinducoin.fr/" target="_blank">

					<img src="http://magasinducoin.fr/assets/images/logo.png" alt=""/>

				</a></p>

				</body>

				</html>

				';

	

	// Always set content-type when sending HTML email

	$headers2 = "MIME-Version: 1.0" . "\r\n";

	$headers2 .= "Content-type:text/html; charset=UTF-8" . "\r\n";

	

	// More headers

	$headers2 .= 'From: <noreply@magasinducoin.fr>' . "\r\n";

	//$headers .= 'Cc: myboss@example.fr' . "\r\n";

	

	$send_contact2 = mail($to2,($subject2),($message2),$headers2);

								

	// Check, if message sent to your email

	// display message "We've recived your information"

	if($send_contact2){

		//echo '<p style="font-size:26px; color:#FA9452;">Votre don est pris en compte.</p>';

		echo'<script>window.location="utilisateurs.php?info=UNACTIVATED";</script>';

	}else {

		echo "ERROR";

	}

}



function SendMail_Ownner_Magasin_approve($email,$nom,$prenom,$title){

	$mail = "SELECT * FROM mail_send WHERE id = '10'";

	$query_mail = mysql_query($mail) or die(mysql_error());

	$row_mail = mysql_fetch_assoc($query_mail);

	

	$sub_content=selectdatacntent($row_mail['subject'],$nom,$nom.' '.$prenom,'','','','',$title);

	

	$to2 = $email;

	$subject2 = $sub_content;

	$content=selectdatacntent($row_mail['content'],$nom,$nom.' '.$prenom);

	$message2 = '<html>

				<head>

				<style>

				.heading {border: solid 1px #000000;}

				</style>

				</head>

				<body>

				'.$content.'

				<p><a href="http://magasinducoin.fr/" target="_blank">

					<img src="http://magasinducoin.fr/assets/images/logo.png" alt=""/>

				</a></p>

				</body>

				</html>

				';

	

	// Always set content-type when sending HTML email

	$headers2 = "MIME-Version: 1.0" . "\r\n";

	$headers2 .= "Content-type:text/html; charset=UTF-8" . "\r\n";

	

	// More headers

	$headers2 .= 'From: <noreply@magasinducoin.fr>' . "\r\n";

	//$headers .= 'Cc: myboss@example.fr' . "\r\n";

	

	$send_contact2 = mail($to2,($subject2),($message2),$headers2);

								

	// Check, if message sent to your email

	// display message "We've recived your information"

	if($send_contact2){

		//echo '<p style="font-size:26px; color:#FA9452;">Votre don est pris en compte.</p>';

		/*echo'<script>window.location="magasins.php?info=ACTIVATED";</script>';*/

	}else {

		echo "ERROR";

	}

}



function SendMail_Ownner_Magasin_unapprove($email,$nom,$prenom,$title){

	$mail = "SELECT * FROM mail_send WHERE id = '11'";

	$query_mail = mysql_query($mail) or die(mysql_error());

	$row_mail = mysql_fetch_assoc($query_mail);

	

	$sub_content=selectdatacntent($row_mail['subject'],$nom,$nom.' '.$prenom);

	

	$to2 = $email;

	$subject2 = $sub_content;

	$content=selectdatacntent($row_mail['content'],$nom,$nom.' '.$prenom,'','','','',$title);

	$message2 = '<html>

				<head>

				<style>

				.heading {border: solid 1px #000000;}

				</style>

				</head>

				<body>

				'.$content.'

				<p><a href="http://magasinducoin.fr/" target="_blank">

					<img src="http://magasinducoin.fr/assets/images/logo.png" alt=""/>

				</a></p>

				</body>

				</html>

				';

	

	// Always set content-type when sending HTML email

	$headers2 = "MIME-Version: 1.0" . "\r\n";

	$headers2 .= "Content-type:text/html; charset=UTF-8" . "\r\n";

	

	// More headers

	$headers2 .= 'From: <noreply@magasinducoin.fr>' . "\r\n";

	//$headers .= 'Cc: myboss@example.fr' . "\r\n";

	

	$send_contact2 = mail($to2,($subject2),($message2),$headers2);

								

	// Check, if message sent to your email

	// display message "We've recived your information"

	if($send_contact2){

		//echo '<p style="font-size:26px; color:#FA9452;">Votre don est pris en compte.</p>';

		echo'<script>window.location="magasins.php?info=UNACTIVATED";</script>';

	}else {

		echo "ERROR";

	}

}



function SendMail_Ownner_Coupon_approve($email,$nom,$prenom,$title){

	$mail = "SELECT * FROM mail_send WHERE id = '12'";

	$query_mail = mysql_query($mail) or die(mysql_error());

	$row_mail = mysql_fetch_assoc($query_mail);

	

	$sub_content=selectdatacntent($row_mail['subject'],$nom,$nom.' '.$prenom,'','','','',$title);

	

	$to2 = $email;

	$subject2 = $sub_content;

	$content=selectdatacntent($row_mail['content'],$nom,$nom.' '.$prenom,'','','','',$title);

	$message2 = '<html>

				<head>

				<style>

				.heading {border: solid 1px #000000;}

				</style>

				</head>

				<body>

				'.$content.'

				<p><a href="http://magasinducoin.fr/" target="_blank">

					<img src="http://magasinducoin.fr/assets/images/logo.png" alt=""/>

				</a></p>

				</body>

				</html>

				';

	

	// Always set content-type when sending HTML email

	$headers2 = "MIME-Version: 1.0" . "\r\n";

	$headers2 .= "Content-type:text/html; charset=UTF-8" . "\r\n";

	

	// More headers

	$headers2 .= 'From: <noreply@magasinducoin.fr>' . "\r\n";

	//$headers .= 'Cc: myboss@example.fr' . "\r\n";

	

	$send_contact2 = mail($to2,($subject2),($message2),$headers2);

								

	// Check, if message sent to your email

	// display message "We've recived your information"

	if($send_contact2){

		//echo '<p style="font-size:26px; color:#FA9452;">Votre don est pris en compte.</p>';

		/*echo'<script>window.location="coupons.php?info=ACTIVATED";</script>';*/

	}else {

		echo "ERROR";

	}

}



function SendMail_Ownner_Coupon_unapprove($email,$nom,$prenom,$title){

	$mail = "SELECT * FROM mail_send WHERE id = '13'";

	$query_mail = mysql_query($mail) or die(mysql_error());

	$row_mail = mysql_fetch_assoc($query_mail);

	

	$sub_content=selectdatacntent($row_mail['subject'],$nom,$nom.' '.$prenom,'','','','',$title);

	

	$to2 = $email;

	$subject2 = $sub_content;

	$content=selectdatacntent($row_mail['content'],$nom,$nom.' '.$prenom,'','','','',$title);

	$message2 = '<html>

				<head>

				<style>

				.heading {border: solid 1px #000000;}

				</style>

				</head>

				<body>

				'.$content.'

				<p><a href="http://magasinducoin.fr/" target="_blank">

					<img src="http://magasinducoin.fr/assets/images/logo.png" alt=""/>

				</a></p>

				</body>

				</html>

				';

	

	// Always set content-type when sending HTML email

	$headers2 = "MIME-Version: 1.0" . "\r\n";

	$headers2 .= "Content-type:text/html; charset=UTF-8" . "\r\n";

	

	// More headers

	$headers2 .= 'From: <noreply@magasinducoin.fr>' . "\r\n";

	//$headers .= 'Cc: myboss@example.fr' . "\r\n";

	

	$send_contact2 = mail($to2,($subject2),($message2),$headers2);

								

	// Check, if message sent to your email

	// display message "We've recived your information"

	if($send_contact2){

		//echo '<p style="font-size:26px; color:#FA9452;">Votre don est pris en compte.</p>';

		echo'<script>window.location="coupons.php?info=UNACTIVATED";</script>';

	}else {

		echo "ERROR";

	}

}



function SendMail_Ownner_produits_approve($email,$nom,$prenom,$title){

	$mail = "SELECT * FROM mail_send WHERE id = '14'";

	$query_mail = mysql_query($mail) or die(mysql_error());

	$row_mail = mysql_fetch_assoc($query_mail);

	

	$sub_content=selectdatacntent($row_mail['subject'],$nom,$nom.' '.$prenom,'','','','',$title);

	

	$to2 = $email;

	$subject2 = $sub_content;

	$content=selectdatacntent($row_mail['content'],$nom,$nom.' '.$prenom,'','','','',$title);

	$message2 = '<html>

				<head>

				<style>

				.heading {border: solid 1px #000000;}

				</style>

				</head>

				<body>

				'.$content.'

				<p><a href="http://magasinducoin.fr/" target="_blank">

					<img src="http://magasinducoin.fr/assets/images/logo.png" alt=""/>

				</a></p>

				</body>

				</html>

				';

	

	// Always set content-type when sending HTML email

	$headers2 = "MIME-Version: 1.0" . "\r\n";

	$headers2 .= "Content-type:text/html; charset=UTF-8" . "\r\n";

	

	// More headers

	$headers2 .= 'From: <noreply@magasinducoin.fr>' . "\r\n";

	//$headers .= 'Cc: myboss@example.fr' . "\r\n";

	

	$send_contact2 = mail($to2,($subject2),($message2),$headers2);

								

	// Check, if message sent to your email

	// display message "We've recived your information"

	if($send_contact2){

		//echo '<p style="font-size:26px; color:#FA9452;">Votre don est pris en compte.</p>';

		/*echo'<script>window.location="coupons.php?info=ACTIVATED";</script>';*/

	}else {

		echo "ERROR";

	}

}



function SendMail_Ownner_produits_unapprove($email,$nom,$prenom,$title){

	$mail = "SELECT * FROM mail_send WHERE id = '15'";

	$query_mail = mysql_query($mail) or die(mysql_error());

	$row_mail = mysql_fetch_assoc($query_mail);

	

	$sub_content=selectdatacntent($row_mail['subject'],$nom,$nom.' '.$prenom,'','','','',$title);

	

	$to2 = $email;

	$subject2 = $sub_content;

	$content=selectdatacntent($row_mail['content'],$nom,$nom.' '.$prenom,'','','','',$title);

	$message2 = '<html>

				<head>

				<style>

				.heading {border: solid 1px #000000;}

				</style>

				</head>

				<body>

				'.$content.'

				<p><a href="http://magasinducoin.fr/" target="_blank">

					<img src="http://magasinducoin.fr/assets/images/logo.png" alt=""/>

				</a></p>

				</body>

				</html>

				';

	

	// Always set content-type when sending HTML email

	$headers2 = "MIME-Version: 1.0" . "\r\n";

	$headers2 .= "Content-type:text/html; charset=UTF-8" . "\r\n";

	

	// More headers

	$headers2 .= 'From: <noreply@magasinducoin.fr>' . "\r\n";

	//$headers .= 'Cc: myboss@example.fr' . "\r\n";

	

	$send_contact2 = mail($to2,($subject2),($message2),$headers2);

								

	// Check, if message sent to your email

	// display message "We've recived your information"

	if($send_contact2){

		//echo '<p style="font-size:26px; color:#FA9452;">Votre don est pris en compte.</p>';

		echo'<script>window.location="coupons.php?info=ACTIVATED";</script>';

	}else {

		echo "ERROR";

	}

}



function SendMail_Ownner_evenements_approve($email,$nom,$prenom,$title){

	$mail = "SELECT * FROM mail_send WHERE id = '16'";

	$query_mail = mysql_query($mail) or die(mysql_error());

	$row_mail = mysql_fetch_assoc($query_mail);

	

	$sub_content=selectdatacntent($row_mail['subject'],$nom,$nom.' '.$prenom,'','','','',$title);

	

	$to2 = $email;

	$subject2 = $sub_content;

	$content=selectdatacntent($row_mail['content'],$nom,$nom.' '.$prenom,'','','','',$title);

	$message2 = '<html>

				<head>

				<style>

				.heading {border: solid 1px #000000;}

				</style>

				</head>

				<body>

				'.$content.'

				<p><a href="http://magasinducoin.fr/" target="_blank">

					<img src="http://magasinducoin.fr/assets/images/logo.png" alt=""/>

				</a></p>

				</body>

				</html>

				';

	

	// Always set content-type when sending HTML email

	$headers2 = "MIME-Version: 1.0" . "\r\n";

	$headers2 .= "Content-type:text/html; charset=UTF-8" . "\r\n";

	

	// More headers

	$headers2 .= 'From: <noreply@magasinducoin.fr>' . "\r\n";

	//$headers .= 'Cc: myboss@example.fr' . "\r\n";

	

	$send_contact2 = mail($to2,($subject2),($message2),$headers2);

								

	// Check, if message sent to your email

	// display message "We've recived your information"

	if($send_contact2){

		//echo '<p style="font-size:26px; color:#FA9452;">Votre don est pris en compte.</p>';

		/*echo'<script>window.location="evenements.php?info=ACTIVATED";</script>';*/

	}else {

		echo "ERROR";

	}

}



function SendMail_Ownner_evenements_unapprove($email,$nom,$prenom,$title){

	$mail = "SELECT * FROM mail_send WHERE id = '17'";

	$query_mail = mysql_query($mail) or die(mysql_error());

	$row_mail = mysql_fetch_assoc($query_mail);

	

	$sub_content=selectdatacntent($row_mail['subject'],$nom,$nom.' '.$prenom,'','','','',$title);

	

	$to2 = $email;

	$subject2 = $sub_content;

	$content=selectdatacntent($row_mail['content'],$nom,$nom.' '.$prenom,'','','','',$title);

	$message2 = '<html>

				<head>

				<style>

				.heading {border: solid 1px #000000;}

				</style>

				</head>

				<body>

				'.$content.'

				<p><a href="http://magasinducoin.fr/" target="_blank">

					<img src="http://magasinducoin.fr/assets/images/logo.png" alt=""/>

				</a></p>

				</body>

				</html>

				';

	

	// Always set content-type when sending HTML email

	$headers2 = "MIME-Version: 1.0" . "\r\n";

	$headers2 .= "Content-type:text/html; charset=UTF-8" . "\r\n";

	

	// More headers

	$headers2 .= 'From: <noreply@magasinducoin.fr>' . "\r\n";

	//$headers .= 'Cc: myboss@example.fr' . "\r\n";

	

	$send_contact2 = mail($to2,($subject2),($message2),$headers2);

								

	// Check, if message sent to your email

	// display message "We've recived your information"

	if($send_contact2){

		//echo '<p style="font-size:26px; color:#FA9452;">Votre don est pris en compte.</p>';

		echo'<script>window.location="evenements.php?info=UNACTIVATED";</script>';

	}else {

		echo "ERROR";

	}

}



function code_coupon ($contentdata,$Name='',$Username='',$title='',$nom_magazin='',$categorie='') {

	$regex = "/\[(.*?)\]/";

	preg_match_all($regex, $contentdata, $matches);

	for($i = 0; $i < count($matches[1]); $i++){

		$match = $matches[1][$i];

			if($match=='Name'){

				$name=$Name;

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}else if($match=='username'){

				$name=$Username;

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}else if($match=='title'){

				$name=$title;

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}else if($match=='nom_magazin'){

				$name=$nom_magazin;

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}else if($match=='categorie'){

				$name=$categorie;

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}

	}

	return $contentdata;

}



function SendMail_Create_Coupon_Shpper($email,$nom,$prenom,$title){

	$mail = "SELECT * FROM mail_send WHERE id = '18'";

	$query_mail = mysql_query($mail) or die(mysql_error());

	$row_mail = mysql_fetch_assoc($query_mail);

	

	$sub_content=code_coupon($row_mail['subject'],$nom,$nom.' '.$prenom);

	

	$to2 = $email;

	$subject2 = $sub_content;

	$content=code_coupon($row_mail['content'],$nom,$nom.' '.$prenom,$title);

	$message2 = '<html>

				<head>

				<style>

				.heading {border: solid 1px #000000;}

				</style>

				</head>

				<body>

				'.$content.'

				<p><a href="http://magasinducoin.fr/" target="_blank">

					<img src="http://magasinducoin.fr/assets/images/logo.png" alt=""/>

				</a></p>

				</body>

				</html>

				';

	

	// Always set content-type when sending HTML email

	$headers2 = "MIME-Version: 1.0" . "\r\n";

	$headers2 .= "Content-type:text/html; charset=UTF-8" . "\r\n";

	

	// More headers

	$headers2 .= 'From: <noreply@magasinducoin.fr>' . "\r\n";

	//$headers .= 'Cc: myboss@example.fr' . "\r\n";

	

	$send_contact2 = mail($to2,($subject2),($message2),$headers2);

								

	// Check, if message sent to your email

	// display message "We've recived your information"

	if($send_contact2){

		//echo '<p style="font-size:26px; color:#FA9452;">Votre don est pris en compte.</p>';/

	}else {

		echo "ERROR";

	}

}



function SendMail_Create_Coupon_Ownner($email,$nom,$prenom,$title,$nom_magasin,$categorie){

	$mail = "SELECT * FROM mail_send WHERE id = '19'";

	$query_mail = mysql_query($mail) or die(mysql_error());

	$row_mail = mysql_fetch_assoc($query_mail);

	

	$sub_content=code_coupon($row_mail['subject'],$nom,$nom.' '.$prenom,$title,$nom_magasin,$categorie);

	

	$to2 = 'validation@magasinducoin.fr';//

	$subject2 = $sub_content;

	$content=code_coupon($row_mail['content'],$nom,$nom.' '.$prenom,$title,$nom_magasin,$categorie);

	$message2 = '<html>

				<head>

				<style>

				.heading {border: solid 1px #000000;}

				</style>

				</head>

				<body>

				'.$content.'

				<p><a href="http://magasinducoin.fr/" target="_blank">

					<img src="http://magasinducoin.fr/assets/images/logo.png" alt=""/>

				</a></p>

				</body>

				</html>

				';

	

	// Always set content-type when sending HTML email

	$headers2 = "MIME-Version: 1.0" . "\r\n";

	$headers2 .= "Content-type:text/html; charset=UTF-8" . "\r\n";

	

	// More headers

	$headers2 .= 'From: <validation@magasinducoin.fr>' . "\r\n";

	//$headers .= 'Cc: myboss@example.fr' . "\r\n";

	

	$send_contact2 = mail($to2,($subject2),($message2),$headers2);

								

	// Check, if message sent to your email

	// display message "We've recived your information"

	if($send_contact2){

		//echo '<p style="font-size:26px; color:#FA9452;">Votre don est pris en compte.</p>';/

	}else {

		echo "ERROR";

	}

}



function code_product ($contentdata,$Name='',$Username='',$title='',$nom_magazin='',$categorie='') {

	$regex = "/\[(.*?)\]/";

	preg_match_all($regex, $contentdata, $matches);

	for($i = 0; $i < count($matches[1]); $i++){

		$match = $matches[1][$i];

			if($match=='Name'){

				$name=$Name;

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}else if($match=='username'){

				$name=$Username;

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}else if($match=='title'){

				$name=$title;

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}else if($match=='nom_magazin'){

				$name=$nom_magazin;

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}else if($match=='categorie'){

				$name=$categorie;

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}

	}

	return $contentdata;

}



function SendMail_Create_Product_Shpper($email,$nom,$prenom,$title){

	$mail = "SELECT * FROM mail_send WHERE id = '20'";

	$query_mail = mysql_query($mail) or die(mysql_error());

	$row_mail = mysql_fetch_assoc($query_mail);

	

	$sub_content=code_product($row_mail['subject'],$nom,$nom.' '.$prenom);

	

	$to2 = $email;

	$subject2 = $sub_content;

	$content=code_product($row_mail['content'],$nom,$nom.' '.$prenom,$title);

	$message2 = '<html>

				<head>

				<style>

				.heading {border: solid 1px #000000;}

				</style>

				</head>

				<body>

				'.$content.'

				<p><a href="http://magasinducoin.fr/" target="_blank">

					<img src="http://magasinducoin.fr/assets/images/logo.png" alt=""/>

				</a></p>

				</body>

				</html>

				';

	

	// Always set content-type when sending HTML email

	$headers2 = "MIME-Version: 1.0" . "\r\n";

	$headers2 .= "Content-type:text/html; charset=UTF-8" . "\r\n";

	

	// More headers

	$headers2 .= 'From: <noreply@magasinducoin.fr >' . "\r\n";

	//$headers .= 'Cc: myboss@example.fr' . "\r\n";

	

	$send_contact2 = mail($to2,($subject2),($message2),$headers2);

								

	// Check, if message sent to your email

	// display message "We've recived your information"

	if($send_contact2){

		//echo '<p style="font-size:26px; color:#FA9452;">Votre don est pris en compte.</p>';/

	}else {

		echo "ERROR";

	}

}



function SendMail_Create_Product_Ownner($email,$nom,$prenom,$title,$nom_magasin,$categorie){

	$mail = "SELECT * FROM mail_send WHERE id = '21'";

	$query_mail = mysql_query($mail) or die(mysql_error());

	$row_mail = mysql_fetch_assoc($query_mail);

	

	$sub_content=code_product($row_mail['subject'],$nom,$nom.' '.$prenom,$title,$nom_magasin,$categorie);

	

	$to2 = 'validation@magasinducoin.fr';//

	$subject2 = $sub_content;

	$content=code_product($row_mail['content'],$nom,$nom.' '.$prenom,$title,$nom_magasin,$categorie);

	$message2 = '<html>

				<head>

				<style>

				.heading {border: solid 1px #000000;}

				</style>

				</head>

				<body>

				'.$content.'

				<p><a href="http://magasinducoin.fr/" target="_blank">

					<img src="http://magasinducoin.fr/assets/images/logo.png" alt=""/>

				</a></p>

				</body>

				</html>

				';

	

	// Always set content-type when sending HTML email

	$headers2 = "MIME-Version: 1.0" . "\r\n";

	$headers2 .= "Content-type:text/html; charset=UTF-8" . "\r\n";

	

	// More headers

	$headers2 .= 'From: <validation@magasinducoin.fr>' . "\r\n";

	//$headers .= 'Cc: myboss@example.fr' . "\r\n";

	

	$send_contact2 = mail($to2,($subject2),($message2),$headers2);

								

	// Check, if message sent to your email

	// display message "We've recived your information"

	if($send_contact2){

		//echo '<p style="font-size:26px; color:#FA9452;">Votre don est pris en compte.</p>';/

	}else {

		echo "ERROR";

	}

}



function code_event ($contentdata,$Name='',$Username='',$title='',$nom_magazin='',$categorie='') {

	$regex = "/\[(.*?)\]/";

	preg_match_all($regex, $contentdata, $matches);

	for($i = 0; $i < count($matches[1]); $i++){

		$match = $matches[1][$i];

			if($match=='Name'){

				$name=$Name;

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}else if($match=='username'){

				$name=$Username;

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}else if($match=='title'){

				$name=$title;

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}else if($match=='nom_magazin'){

				$name=$nom_magazin;

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}else if($match=='categorie'){

				$name=$categorie;

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}

	}

	return $contentdata;

}



function SendMail_Create_Evnt_Shpper($email,$nom,$prenom,$title){

	$mail = "SELECT * FROM mail_send WHERE id = '22'";

	$query_mail = mysql_query($mail) or die(mysql_error());

	$row_mail = mysql_fetch_assoc($query_mail);

	

	$sub_content=code_product($row_mail['subject'],$nom,$nom.' '.$prenom);

	

	$to2 = $email;

	$subject2 = $sub_content;

	$content=code_product($row_mail['content'],$nom,$nom.' '.$prenom,$title);

	$message2 = '<html>

				<head>

				<style>

				.heading {border: solid 1px #000000;}

				</style>

				</head>

				<body>

				'.$content.'

				<p><a href="http://magasinducoin.fr/" target="_blank">

					<img src="http://magasinducoin.fr/assets/images/logo.png" alt=""/>

				</a></p>

				</body>

				</html>

				';

	

	// Always set content-type when sending HTML email

	$headers2 = "MIME-Version: 1.0" . "\r\n";

	$headers2 .= "Content-type:text/html; charset=UTF-8" . "\r\n";

	

	// More headers

	$headers2 .= 'From: <noreply@magasinducoin.fr >' . "\r\n";

	//$headers .= 'Cc: myboss@example.fr' . "\r\n";

	

	$send_contact2 = mail($to2,($subject2),($message2),$headers2);

								

	// Check, if message sent to your email

	// display message "We've recived your information"

	if($send_contact2){

		//echo '<p style="font-size:26px; color:#FA9452;">Votre don est pris en compte.</p>';/

	}else {

		echo "ERROR";

	}

}



function SendMail_Create_Event_Ownner($email,$nom,$prenom,$title,$nom_magasin,$categorie){

	$mail = "SELECT * FROM mail_send WHERE id = '23'";

	$query_mail = mysql_query($mail) or die(mysql_error());

	$row_mail = mysql_fetch_assoc($query_mail);

	

	$sub_content=code_product($row_mail['subject'],$nom,$nom.' '.$prenom,$title,$nom_magasin,$categorie);

	

	$to2 = 'validation@magasinducoin.fr';//

	$subject2 = $sub_content;

	$content=code_product($row_mail['content'],$nom,$nom.' '.$prenom,$title,$nom_magasin,$categorie);

	$message2 = '<html>

				<head>

				<style>

				.heading {border: solid 1px #000000;}

				</style>

				</head>

				<body>

				'.$content.'

				<p><a href="http://magasinducoin.fr/" target="_blank">

					<img src="http://magasinducoin.fr/assets/images/logo.png" alt=""/>

				</a></p>

				</body>

				</html>

				';

	

	// Always set content-type when sending HTML email

	$headers2 = "MIME-Version: 1.0" . "\r\n";

	$headers2 .= "Content-type:text/html; charset=UTF-8" . "\r\n";

	

	// More headers

	$headers2 .= 'From: <validation@magasinducoin.fr>' . "\r\n";

	//$headers .= 'Cc: myboss@example.fr' . "\r\n";

	

	$send_contact2 = mail($to2,($subject2),($message2),$headers2);

								

	// Check, if message sent to your email

	// display message "We've recived your information"

	if($send_contact2){

		//echo '<p style="font-size:26px; color:#FA9452;">Votre don est pris en compte.</p>';/

	}else {

		echo "ERROR";

	}

}



function code_magasin ($contentdata,$Name='',$Username='',$title='',$nom_magazin='',$adresse='',$siren='') {

	$regex = "/\[(.*?)\]/";

	preg_match_all($regex, $contentdata, $matches);

	for($i = 0; $i < count($matches[1]); $i++){

		$match = $matches[1][$i];

			if($match=='Name'){

				$name=$Name;

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}else if($match=='username'){

				$name=$Username;

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}else if($match=='title'){

				$name=$title;

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}else if($match=='nom_magazin'){

				$name=$nom_magazin;

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}else if($match=='categorie'){

				$name=$categorie;

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}else if($match=='adresse'){

				$name=$adresse;

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}else if($match=='siren'){

				$name=$siren;

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}

	}

	return $contentdata;

}





function SendMail_Create_Magasin_Shpper($email,$nom,$prenom,$nom_magazin){

	$mail = "SELECT * FROM mail_send WHERE id = '24'";

	$query_mail = mysql_query($mail) or die(mysql_error());

	$row_mail = mysql_fetch_assoc($query_mail);

	

	$sub_content=code_magasin($row_mail['subject'],$nom,$nom.' '.$prenom);

	

	$to2 = $email;

	$subject2 = $sub_content;

	$content=code_magasin($row_mail['content'],$nom,$nom.' '.$prenom,'',$nom_magazin);

	$message2 = '<html>

				<head>

				<style>

				.heading {border: solid 1px #000000;}

				</style>

				</head>

				<body>

				'.$content.'

				<p><a href="http://magasinducoin.fr/" target="_blank">

					<img src="http://magasinducoin.fr/assets/images/logo.png" alt=""/>

				</a></p>

				</body>

				</html>

				';

	

	// Always set content-type when sending HTML email

	$headers2 = "MIME-Version: 1.0" . "\r\n";

	$headers2 .= "Content-type:text/html; charset=UTF-8" . "\r\n";

	

	// More headers

	$headers2 .= 'From: <noreply@magasinducoin.fr >' . "\r\n";

	//$headers .= 'Cc: myboss@example.fr' . "\r\n";

	

	$send_contact2 = mail($to2,($subject2),($message2),$headers2);

								

	// Check, if message sent to your email

	// display message "We've recived your information"

	if($send_contact2){

		//echo '<p style="font-size:26px; color:#FA9452;">Votre don est pris en compte.</p>';/

	}else {

		echo "ERROR";

	}

}



function SendMail_Create_Magasin_Ownner($email,$nom,$prenom,$nom_magasin,$adresse,$siren){

	$mail = "SELECT * FROM mail_send WHERE id = '25'";

	$query_mail = mysql_query($mail) or die(mysql_error());

	$row_mail = mysql_fetch_assoc($query_mail);

	

	$sub_content=code_magasin($row_mail['subject'],$nom,$nom.' '.$prenom,$nom_magasin,$adresse,$siren);

	

	$to2 = 'validation@magasinducoin.fr';//

	$subject2 = $sub_content;

	$content=code_magasin($row_mail['content'],$nom,$nom.' '.$prenom,$nom_magasin,$adresse,$siren);

	$message2 = '<html>

				<head>

				<style>

				.heading {border: solid 1px #000000;}

				</style>

				</head>

				<body>

				'.$content.'

				<p><a href="http://magasinducoin.fr/" target="_blank">

					<img src="http://magasinducoin.fr/assets/images/logo.png" alt=""/>

				</a></p>

				</body>

				</html>

				';

	

	// Always set content-type when sending HTML email

	$headers2 = "MIME-Version: 1.0" . "\r\n";

	$headers2 .= "Content-type:text/html; charset=UTF-8" . "\r\n";

	

	// More headers

	$headers2 .= 'From: <validation@magasinducoin.fr>' . "\r\n";

	//$headers .= 'Cc: myboss@example.fr' . "\r\n";

	

	$send_contact2 = mail($to2,($subject2),($message2),$headers2);

								

	// Check, if message sent to your email

	// display message "We've recived your information"

	if($send_contact2){

		//echo '<p style="font-size:26px; color:#FA9452;">Votre don est pris en compte.</p>';/

	}else {

		echo "ERROR";

	}

}



function code_sabonne ($contentdata,$nom_magazin='',$type='',$title='',$description='',$date='',$adresse='',$ville='') {

	$regex = "/\[(.*?)\]/";

	preg_match_all($regex, $contentdata, $matches);

	for($i = 0; $i < count($matches[1]); $i++){

		$match = $matches[1][$i];

			if($match=='nom_magazin'){

				$name=$nom_magazin;

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}else if($match=='type'){

				$name=$type;

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}else if($match=='title'){

				$name=$title;

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}else if($match=='description'){

				$name=$description;

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}else if($match=='date'){

				$name=$date;

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}else if($match=='adresse'){

				$name=$adresse;

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}else if($match=='ville'){

				$name=$ville;

				$contentdata = str_replace($matches[0][$i], $name, $contentdata);

			}

	}

	return $contentdata;

}



function SendMail_sabonne($email,$nom_magazin,$type,$title,$description,$date,$adresse,$ville){

	$mail = "SELECT * FROM mail_send WHERE id = '26'";

	$query_mail = mysql_query($mail) or die(mysql_error());

	$row_mail = mysql_fetch_assoc($query_mail);

	

	$sub_content=code_sabonne($row_mail['subject'],$nom_magazin,$type,$title,$description,$date,$adresse,$ville);

	

	$to2 = $email;//

	$subject2 = $sub_content;

	$content=code_sabonne($row_mail['content'],$nom_magazin,$type,$title,$description,$date,$adresse,$ville);

	$message2 = '<html>

				<head>

				<style>

				.heading {border: solid 1px #000000;}

				</style>

				</head>

				<body>

				'.$content.'

				<p><a href="http://magasinducoin.fr/" target="_blank">

					<img src="http://magasinducoin.fr/assets/images/logo.png" alt=""/>

				</a></p>

				</body>

				</html>

				';

	

	// Always set content-type when sending HTML email

	$headers2='';

	$headers2 .= "MIME-Version: 1.0" . "\r\n";

	$headers2 .= "Content-type:text/html; charset=UTF-8" . "\r\n";

	

	// More headers

	$headers2 .= 'From: <noreply@magasinducoin.fr>' . "\r\n";

	//$headers .= 'Cc: myboss@example.fr' . "\r\n";

	

	$send_contact2 = mail($to2,($subject2),($message2),$headers2);

								

	// Check, if message sent to your email

	// display message "We've recived your information"

	if($send_contact2){

		//echo '<p style="font-size:26px; color:#FA9452;">Votre don est pris en compte.</p>';/

	}else {

		echo "ERROR";

	}

}



function encode5t($str){

	for($i=0; $i<5;$i++){

		$str=strrev(base64_encode($str)); 

	}

	return $str;

}



function decode5t($str){

	for($i=0; $i<5;$i++){

		$str=base64_decode(strrev($str)); 

	}

	return $str;

}



function count_coupon($id_magazin, $region){

	include('connection.php');

	

	$datetime = date('Y-m-d H:i:s');

	$date = date('Y-m-d');

	

	$con= mysql_connect($hostname_magazinducoin,$username_magazinducoin,$password_magazinducoin) or die("Unable to connect to MySQL");

	mysql_query("SET character_set_results=utf8",$con);

	mb_language('uni');

	mb_internal_encoding('UTF-8');

	mysql_query("set names 'utf8'" , $con); 

	$dbname=mysql_select_db($database_magazinducoin,$con) or die("Can not select MySQL DB");

		

		$List = '';

	

		$sql2 = "SELECT 

				  COUNT(*) AS nb_coupons 

				FROM

				  coupons 

				  INNER JOIN magazins 

					ON magazins.id_magazin = coupons.id_magasin 

				  INNER JOIN category 

					ON category.cat_id = coupons.categories 

				  INNER JOIN region 

					ON region.id_region = magazins.region 

				  INNER JOIN departement 

					ON departement.code = magazins.department 

				  INNER JOIN maps_ville 

					ON maps_ville.id_ville = magazins.ville 

				WHERE (

					(

					  coupons.en_tete_liste_payer = 1 

					  AND coupons.en_tete_liste = 1 

					  AND coupons.approuve = 0 

					  AND coupons.public = 0 

					  AND DATE_ADD(

						coupons.date_debut,

						INTERVAL - coupons.day_en_tete_liste DAY

					  ) = '".$date."'  

					  AND date_debut >= '".$date."' 

					) 

					OR (

					  coupons.approuve = '1' 

					  AND coupons.date_fin >= '".$date."'  

					  AND coupons.date_debut <= '".$date."' 

					) 

					OR (

					  coupons.approuve = 0 

					  AND coupons.public = 1 

					  AND coupons.date_fin >= '".$date."'  

					  AND coupons.date_debut <= '".$date."'  

					  AND coupons.public_start < '".$datetime."' 

					  AND (

						coupons.public_start + INTERVAL 20 MINUTE

					  ) < '".$datetime."'

					)

				  ) 

				  AND coupons.payer = 1 

				  AND coupons.active = 1  

				  AND region.id_region = '".$region."'

				  AND coupons.id_magasin = '".$id_magazin."'";

		

	$result2 = mysql_query($sql2) or die (mysql_error());

	$query2 = mysql_fetch_array($result2);

		$List .= $query2['nb_coupons'];

	

		return $List;

		

	mysql_close($con);

}



function count_event($id_magazin, $region){

	include('connection.php');

	

	$datetime = date('Y-m-d H:i:s');

	$date = date('Y-m-d');

	

	$con= mysql_connect($hostname_magazinducoin,$username_magazinducoin,$password_magazinducoin) or die("Unable to connect to MySQL");

	mysql_query("SET character_set_results=utf8",$con);

	mb_language('uni');

	mb_internal_encoding('UTF-8');

	mysql_query("set names 'utf8'" , $con); 

	$dbname=mysql_select_db($database_magazinducoin,$con) or die("Can not select MySQL DB");

		

		$List = '';

	

		$sql2 = "SELECT 

					count(*) as nb_event 

				FROM

				  evenements 

				  INNER JOIN magazins 

					ON magazins.id_magazin = evenements.id_magazin 

				  INNER JOIN category 

					ON category.cat_id = evenements.category_id 

				  INNER JOIN region 

					ON region.id_region = magazins.region 

				  INNER JOIN departement 

					ON departement.code = magazins.department 

				  INNER JOIN maps_ville 

					ON maps_ville.id_ville = magazins.ville 

				WHERE (

					(

					  evenements.en_tete_liste_payer = 1 

					  AND evenements.en_tete_liste = 1 

					  AND evenements.approuve = 0 

					  AND evenements.public = 0 

					  AND DATE_ADD(

						evenements.date_debut,

						INTERVAL - evenements.day_en_tete_liste DAY

					  ) = '".$date."'  

					  AND date_debut >= '".$date."' 

					) 

					OR (

					  evenements.approuve = '1' 

					  AND evenements.date_fin >= '".$date."'  

					  AND evenements.date_debut <= '".$date."' 

					) 

					OR (

					  evenements.approuve = 0 

					  AND evenements.public = 1 

					  AND evenements.date_fin >= '".$date."'  

					  AND evenements.date_debut <= '".$date."'  

					  AND evenements.public_start < '".$datetime."' 

					  AND (

						evenements.public_start + INTERVAL 20 MINUTE

					  ) < '".$datetime."'

					)

				  ) 

				  AND evenements.payer = 1 

				  AND evenements.active = 1 

				  AND region.id_region = '".$region."'

				  AND evenements.id_magazin = '".$id_magazin."'";

		

	$result2 = mysql_query($sql2) or die (mysql_error());

	$query2 = mysql_fetch_array($result2);

		$List .= $query2['nb_event'];

	

		return $List;

		

	mysql_close($con);

}



function count_product($id_magazin, $region){

	include('connection.php');

	

	$datetime = date('Y-m-d H:i:s');

	$date = date('Y-m-d');

	

	$con= mysql_connect($hostname_magazinducoin,$username_magazinducoin,$password_magazinducoin) or die("Unable to connect to MySQL");

	mysql_query("SET character_set_results=utf8",$con);

	mb_language('uni');

	mb_internal_encoding('UTF-8');

	mysql_query("set names 'utf8'" , $con); 

	$dbname=mysql_select_db($database_magazinducoin,$con) or die("Can not select MySQL DB");

		

		$List = '';

	

		$sql2 = "SELECT 

				  count(*) AS nb_produits

				FROM

				  produits 

				  Inner JOIN magazins 

					ON magazins.id_magazin = produits.id_magazin 

				  INNER JOIN region 

					ON region.id_region = magazins.region 

				  INNER JOIN departement 

					ON departement.code = magazins.department 

				  INNER JOIN maps_ville 

					ON maps_ville.id_ville = magazins.ville 

				WHERE (

					magazins.region = '".$region."'

					AND magazins.id_magazin = '".$id_magazin."'

					AND produits.activate = '1' 

					OR (

					  magazins.region = '".$region."' 

					  AND magazins.id_magazin = '".$id_magazin."' 

					  AND produits.activate = 0 

					  AND produits.public = 1 

					  AND produits.public_start < '".$datetime."' 

					  AND (

						produits.public_start + INTERVAL 20 MINUTE

					  ) < '".$datetime."'

					)

				  )";

		

	$result2 = mysql_query($sql2) or die (mysql_error());

	$query2 = mysql_fetch_array($result2);

		$List .= $query2['nb_produits'];

	

		return $List;

		

	mysql_close($con);

}











?>