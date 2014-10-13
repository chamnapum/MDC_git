<?php require_once('../Connections/magazinducoin.php'); ?>

<?php

// Load the common classes

require_once('../includes/common/KT_common.php');



// Load the required classes

require_once('../includes/tfi/TFI.php');

require_once('../includes/tso/TSO.php');

require_once('../includes/nav/NAV.php');



// Make unified connection variable

$conn_magazinducoin = new KT_connection($magazinducoin, $database_magazinducoin);



//Start Restrict Access To Page

$restrict = new tNG_RestrictAccess($conn_magazinducoin, "../");

//Grand Levels: Level

$restrict->addLevel("4");

$restrict->Execute();

//End Restrict Access To Page



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



// Filter

$tfi_listowner_shopper3 = new TFI_TableFilter($conn_magazinducoin, "tfi_listowner_shopper3");

$tfi_listowner_shopper3->addColumn("utilisateur.nom", "STRING_TYPE", "nom", "%");

$tfi_listowner_shopper3->addColumn("utilisateur.prenom", "STRING_TYPE", "prenom", "%");

$tfi_listowner_shopper3->addColumn("utilisateur.email", "STRING_TYPE", "email", "%");

$tfi_listowner_shopper3->addColumn("utilisateur.telephone", "STRING_TYPE", "telephone", "%");

$tfi_listowner_shopper3->addColumn("magazins.nom_magazin", "STRING_TYPE", "nom_magazin", "%");

$tfi_listowner_shopper3->addColumn("magazins.siren", "STRING_TYPE", "siren", "%");

$tfi_listowner_shopper3->addColumn("magazins.adresse", "STRING_TYPE", "adresse", "%");

$tfi_listowner_shopper3->addColumn("region.nom_region", "STRING_TYPE", "nom_region", "%");

$tfi_listowner_shopper3->addColumn("owner_shopper.sirens", "STRING_TYPE", "sirens", "%");

$tfi_listowner_shopper3->addColumn("owner_shopper.date", "DATE_TYPE", "date", "=");

$tfi_listowner_shopper3->addColumn("owner_shopper.id", "NUMERIC_TYPE", "id", "=");

$tfi_listowner_shopper3->Execute();



// Sorter

$tso_listowner_shopper3 = new TSO_TableSorter("rsowner_shopper1", "tso_listowner_shopper3");

$tso_listowner_shopper3->addColumn("utilisateur.nom");

$tso_listowner_shopper3->addColumn("utilisateur.prenom");

$tso_listowner_shopper3->addColumn("utilisateur.email");

$tso_listowner_shopper3->addColumn("utilisateur.telephone");

$tso_listowner_shopper3->addColumn("magazins.nom_magazin");

$tso_listowner_shopper3->addColumn("magazins.siren");

$tso_listowner_shopper3->addColumn("magazins.adresse");

$tso_listowner_shopper3->addColumn("region.nom_region");

$tso_listowner_shopper3->addColumn("owner_shopper.sirens");

$tso_listowner_shopper3->addColumn("owner_shopper.date");

$tso_listowner_shopper3->addColumn("owner_shopper.date_action");

$tso_listowner_shopper3->setDefault("owner_shopper.date DESC");

$tso_listowner_shopper3->Execute();



// Navigation

$nav_listowner_shopper3 = new NAV_Regular("nav_listowner_shopper3", "rsowner_shopper1", "../", $_SERVER['PHP_SELF'], 20);



mysql_select_db($database_magazinducoin, $magazinducoin);

$query_Recordset1 = "SELECT cat_name, cat_id FROM category ORDER BY cat_name";

$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());

$row_Recordset1 = mysql_fetch_assoc($Recordset1);

$totalRows_Recordset1 = mysql_num_rows($Recordset1);



mysql_select_db($database_magazinducoin, $magazinducoin);

$query_Recordset2 = "SELECT email, id FROM utilisateur ORDER BY email";

$Recordset2 = mysql_query($query_Recordset2, $magazinducoin) or die(mysql_error());

$row_Recordset2 = mysql_fetch_assoc($Recordset2);

$totalRows_Recordset2 = mysql_num_rows($Recordset2);



mysql_select_db($database_magazinducoin, $magazinducoin);

$query_Recordset3 = "SELECT email, id FROM utilisateur ORDER BY email";

$Recordset3 = mysql_query($query_Recordset3, $magazinducoin) or die(mysql_error());

$row_Recordset3 = mysql_fetch_assoc($Recordset3);

$totalRows_Recordset3 = mysql_num_rows($Recordset3);



/*mysql_select_db($database_magazinducoin, $magazinducoin);

$query_Recordset4 = "SELECT nom_magazin, id_magazin FROM magazins ORDER BY nom_magazin";

$Recordset4 = mysql_query($query_Recordset4, $magazinducoin) or die(mysql_error());

$row_Recordset4 = mysql_fetch_assoc($Recordset4);

$totalRows_Recordset4 = mysql_num_rows($Recordset4);*/



//NeXTenesio3 Special List Recordset

$maxRows_rsowner_shopper1 = $_SESSION['max_rows_nav_listowner_shopper3'];

$pageNum_rsowner_shopper1 = 0;

if (isset($_GET['pageNum_rsowner_shopper1'])) {

  $pageNum_rsowner_shopper1 = $_GET['pageNum_rsowner_shopper1'];

}

$startRow_rsowner_shopper1 = $pageNum_rsowner_shopper1 * $maxRows_rsowner_shopper1;



// Defining List Recordset variable

$NXTFilter_rsowner_shopper1 = "1=1";

if (isset($_SESSION['filter_tfi_listowner_shopper3'])) {

  $NXTFilter_rsowner_shopper1 = $_SESSION['filter_tfi_listowner_shopper3'];

}

// Defining List Recordset variable

$NXTSort_rsowner_shopper1 = "owner_shopper.date DESC";

if (isset($_SESSION['sorter_tso_listowner_shopper3'])) {

  $NXTSort_rsowner_shopper1 = $_SESSION['sorter_tso_listowner_shopper3'];

}

mysql_select_db($database_magazinducoin, $magazinducoin);



$query_rsowner_shopper1 = "SELECT 

  utilisateur.nom,

  utilisateur.prenom,

  utilisateur.email,

  utilisateur.telephone,

  magazins.nom_magazin,

  magazins.siren,

  magazins.adresse,

  region.nom_region,

  owner_shopper.sirens,

  owner_shopper.date, 

  owner_shopper.id,

  owner_shopper.id_user,

  owner_shopper.id_magazin,

  owner_shopper.approuve,

  owner_shopper.date_action

FROM

  owner_shopper 

  INNER JOIN utilisateur 

    ON (

      owner_shopper.id_user = utilisateur.id

    ) 

  INNER JOIN magazins 

    ON (

      owner_shopper.id_magazin = magazins.id_magazin

    ) 

  LEFT JOIN region 

    ON (

      magazins.region = region.id_region

    ) 

WHERE {$NXTFilter_rsowner_shopper1} ORDER BY {$NXTSort_rsowner_shopper1}";

$query_limit_rsowner_shopper1 = sprintf("%s LIMIT %d, %d", $query_rsowner_shopper1, $startRow_rsowner_shopper1, $maxRows_rsowner_shopper1);

$rsowner_shopper1 = mysql_query($query_limit_rsowner_shopper1, $magazinducoin) or die(mysql_error());

$row_rsowner_shopper1 = mysql_fetch_assoc($rsowner_shopper1);

//echo $query_limit_rsowner_shopper1;



if (isset($_GET['totalRows_rsowner_shopper1'])) {

  $totalRows_rsowner_shopper1 = $_GET['totalRows_rsowner_shopper1'];

} else {

  $all_rsowner_shopper1 = mysql_query($query_rsowner_shopper1);

  $totalRows_rsowner_shopper1 = mysql_num_rows($all_rsowner_shopper1);

}

$totalPages_rsowner_shopper1 = ceil($totalRows_rsowner_shopper1/$maxRows_rsowner_shopper1)-1;

//End NeXTenesio3 Special List Recordset



$nav_listowner_shopper3->checkBoundries();

?>



<?php

	if($_REQUEST['approuve']){

		$id = $_REQUEST['id'];

		$id_user = $_REQUEST['id_user'];

		$id_magazin = $_REQUEST['id_magazin'];

		$sql_shopper_owner  = "UPDATE owner_shopper SET approuve = '1', date_action = NOW() WHERE id='".$id."'";

		$result_shopper_owner   = mysql_query($sql_shopper_owner) or die (mysql_error());

		

		$sql_magazin  = "UPDATE magazins SET id_user = '".$id_user."' WHERE id_magazin='".$id_magazin."'";

		$result_magazin   = mysql_query($sql_magazin) or die (mysql_error());

			

			$query_email= "SELECT email FROM utilisateur WHERE id='".$id_user."'";

			$Recordset_email = mysql_query($query_email, $magazinducoin) or die(mysql_error());

			$email_val = mysql_fetch_array($Recordset_email);

			

			$query_magazin= "SELECT * FROM magazins WHERE id_magazin='".$id_magazin."'";

			$Recordset_magazin = mysql_query($query_magazin, $magazinducoin) or die(mysql_error());

			$magazin_val = mysql_fetch_array($Recordset_magazin);

			

			//$sub_content=selectdatacntent($row_mail['subject'],$nom,$nom.' '.$prenom);

			

			$sub_content = 'La prise en charge du commerce "'.$magazin_val['nom_magazin'].'" a été « Acceptée »';

			$to2 = $email_val['email'];

			$subject2 = $sub_content;

			//$content=selectdatacntent($row_mail['content'],$nom,$nom.' '.$prenom);

			//$content = '';

			$message2 = '<html>

						<head>

						<style>

						.heading {border: solid 1px #000000;}

						</style>

						</head>

						<body>

						<p>Chère commerçant,</p>

						<p></p>

						<p>Le commerce "'.$magazin_val['nom_magazin'].'" a bien été ajouté dans votre menu « Mes magasins », vous pouvez le gérer entièrement depuis votre espace membre en y ajoutant :</p>

						<p></p>

						<p>- Les photos </p>

						<p>- Les horaires d&acute;ouverture</p>

						<p>- Votre site internet</p>

						<p>- Votre page Facebook </p>

						<p>- Une description complète</p>

						<p>- …</p>

						<p></p>

						<p>L&acute;équipe magasinducoin.com vous remercie et vous souhaite un bon succès,</p>

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

			//$headers .= 'Cc: myboss@example.com' . "\r\n";

			

			$send_contact2 = mail($to2,$subject2,$message2,$headers2);

										

			// Check, if message sent to your email

			// display message "We've recived your information"

			if($send_contact2){

				//echo '<p style="font-size:26px; color:#FA9452;">Votre don est pris en compte.</p>';

				echo'<script>window.location="formOwnerShopper.php";</script>';

			}else {

				echo "ERROR";

			}

			/*echo'<script>window.location="formOwnerShopper.php";</script>';*/

		

	}

	if($_REQUEST['unapprouve']){

		$id = $_REQUEST['id'];

		$id_user = $_REQUEST['id_user'];

		$id_magazin = $_REQUEST['id_magazin'];

		$sql_shopper_owner  = "UPDATE owner_shopper SET approuve = '2', date_action = NOW() WHERE id='".$id."'";

		$result_shopper_owner   = mysql_query($sql_shopper_owner) or die (mysql_error());

		

			$query_email= "SELECT email FROM utilisateur WHERE id='".$id_user."'";

			$Recordset_email = mysql_query($query_email, $magazinducoin) or die(mysql_error());

			$email_val = mysql_fetch_array($Recordset_email);

			

			$query_magazin= "SELECT * FROM magazins WHERE id_magazin='".$id_magazin."'";

			$Recordset_magazin = mysql_query($query_magazin, $magazinducoin) or die(mysql_error());

			$magazin_val = mysql_fetch_array($Recordset_magazin);

			

			//$sub_content=selectdatacntent($row_mail['subject'],$nom,$nom.' '.$prenom);

			

			$sub_content='La prise en charge du commerce "'.$magazin_val['nom_magazin'].'" a été « Refusée »';

			$to2 = $email_val['email'];

			$subject2 = $sub_content;

			//$content=selectdatacntent($row_mail['content'],$nom,$nom.' '.$prenom);

			//$content = '';

			$message2 = '<html>

						<head>

						<style>

						.heading {border: solid 1px #000000;}

						</style>

						</head>

						<body>

						<p>Chère commerçant,</p>

						<p></p>

						<p>La prise en charge du commerce "'.$magazin_val['nom_magazin'].'" n&acute;a pas été accepté, Veuillez contacter dès à présent notre support téléphonique <br />au 0825 700 047, afin de nous certifier que ce commerce vous appartient.</p>

						<p></p>

						<p>L&acute;équipe magasinducoin.com vous remercie et vous souhaite un bon succès,</p>

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

			//$headers .= 'Cc: myboss@example.com' . "\r\n";

			

			$send_contact2 = mail($to2,$subject2,$message2,$headers2);

										

			// Check, if message sent to your email

			// display message "We've recived your information"

			if($send_contact2){

				//echo '<p style="font-size:26px; color:#FA9452;">Votre don est pris en compte.</p>';

				echo'<script>window.location="formOwnerShopper.php";</script>';

			}else {

				echo "ERROR";

			}

			/*echo'<script>window.location="formOwnerShopper.php";</script>';*/

	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

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

	<script src="http://code.jquery.com/jquery-latest.js" type="text/javascript"></script>

    <link href="../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />

    <script src="../includes/common/js/base.js" type="text/javascript"></script>

    <script src="../includes/common/js/utility.js" type="text/javascript"></script>

    <script src="../includes/skins/style.js" type="text/javascript"></script>

    <script src="../includes/nxt/scripts/list.js" type="text/javascript"></script>

    <script src="../includes/nxt/scripts/list.js.php" type="text/javascript"></script>

    <script type="text/javascript">

$NXT_LIST_SETTINGS = {

  duplicate_buttons: false,

  duplicate_navigation: false,

  row_effects: false,

  show_as_buttons: true,

  record_counter: true

}

    </script>





</head>

<body id="sp">

<?php include("modules/header.php"); ?>



<div class="content_wrapper_sbr">

	<div>

  		<div id="content">

          <div class="KT_tng" id="listowner_shopper3">

            <h1> &Eacute;vénements

              <?php

  $nav_listowner_shopper3->Prepare();

  require("../includes/nav/NAV_Text_Statistics.inc.php");

?>

            </h1>

            <div class="KT_tnglist">

            	<?php if($_GET['info']=='ACTIVATED') { ?><div class="succes">approve!!</div><?php } ?>

				<?php if($_GET['info']=='UNACTIVATED') { ?><div class="unsucces">no approve!!</div><?php } ?>

              <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">

                <div class="KT_options"> <a href="<?php echo $nav_listowner_shopper3->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>

                  <?php 

  // Show IF Conditional region1

  if (@$_GET['show_all_nav_listowner_shopper3'] == 1) {

?>

                    <?php echo $_SESSION['default_max_rows_nav_listowner_shopper3']; ?>

                    <?php 

  // else Conditional region1

  } else { ?>

                    <?php echo NXT_getResource("all"); ?>

                    <?php } 

  // endif Conditional region1

?>

                      <?php echo NXT_getResource("records"); ?></a> &nbsp;

                  &nbsp;

<?php /*?>                <?php 

  // Show IF Conditional region2

  if (@$_SESSION['has_filter_tfi_listowner_shopper3'] == 1) {

?>

                  <a href="<?php echo $tfi_listowner_shopper3->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>

                  <?php 

  // else Conditional region2

  } else { ?>

                  <a href="<?php echo $tfi_listowner_shopper3->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>

                  <?php } 

  // endif Conditional region2

?><?php */?>

                </div>

                <table cellpadding="2" cellspacing="0" class="KT_tngtable">

                  <thead>

                    <tr class="KT_row_order">

                      <!--<th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>

                      </th>-->

                      <th class="KT_sorter KT_col_nom <?php echo $tso_listowner_shopper3->getSortIcon('utilisateur.nom'); ?>"> <a href="<?php echo $tso_listowner_shopper3->getSortLink('utilisateur.nom'); ?>">Nom</a> </th>

                      <!--<th class="KT_sorter KT_col_prenom <?php echo $tso_listowner_shopper3->getSortIcon('utilisateur.prenom'); ?>"> <a href="<?php echo $tso_listowner_shopper3->getSortLink('utilisateur.prenom'); ?>">Prenom </a> </th>-->

                      <th class="KT_sorter KT_col_email <?php echo $tso_listowner_shopper3->getSortIcon('utilisateur.email'); ?>"> <a href="<?php echo $tso_listowner_shopper3->getSortLink('utilisateur.email'); ?>">Email </a> </th>

                      <th class="KT_sorter KT_col_telephone <?php echo $tso_listowner_shopper3->getSortIcon('utilisateur.telephone'); ?>"> <a href="<?php echo $tso_listowner_shopper3->getSortLink('utilisateur.telephone'); ?>">Téléphone </a> </th>

                      <th class="KT_sorter KT_col_sirens <?php echo $tso_listowner_shopper3->getSortIcon('owner_shopper.sirens'); ?>"> <a href="<?php echo $tso_listowner_shopper3->getSortLink('owner_shopper.sirens'); ?>">Siren Owner </a> </th>

                      <th class="KT_sorter KT_col_nom_magazin <?php echo $tso_listowner_shopper3->getSortIcon('magazins.nom_magazin'); ?>"> <a href="<?php echo $tso_listowner_shopper3->getSortLink('magazins.nom_magazin'); ?>">Magazin </a> </th>

                      <th class="KT_sorter KT_col_siren <?php echo $tso_listowner_shopper3->getSortIcon('magazins.siren'); ?>"> <a href="<?php echo $tso_listowner_shopper3->getSortLink('magazins.siren'); ?>">Siren </a> </th>

                      <th class="KT_sorter KT_col_adresse <?php echo $tso_listowner_shopper3->getSortIcon('magazins.adresse'); ?>"> <a href="<?php echo $tso_listowner_shopper3->getSortLink('magazins.adresse'); ?>">Adresse </a> </th>

                      <th class="KT_sorter KT_col_nom_region <?php echo $tso_listowner_shopper3->getSortIcon('region.nom_region'); ?>"> <a href="<?php echo $tso_listowner_shopper3->getSortLink('region.nom_region'); ?>">Region </a> </th>

                      <th class="KT_sorter KT_col_date <?php echo $tso_listowner_shopper3->getSortIcon('owner_shopper.date'); ?>"> <a href="<?php echo $tso_listowner_shopper3->getSortLink('owner_shopper.date'); ?>">Date </a> </th>

                      <th class="KT_sorter KT_col_date_action <?php echo $tso_listowner_shopper3->getSortIcon('owner_shopper.date_action'); ?>"> <a href="<?php echo $tso_listowner_shopper3->getSortLink('owner_shopper.date_action'); ?>">Date Action</a> </th>

                      <th>&nbsp;</th>

                    </tr>



                  </thead>

                  <tbody>

                    <?php if ($totalRows_rsowner_shopper1 == 0) { // Show if recordset empty ?>

                      <tr>

                        <td colspan="9"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>

                      </tr>

                      <?php } // Show if recordset empty ?>

                    <?php if ($totalRows_rsowner_shopper1 > 0) { // Show if recordset not empty ?>

                      <?php do { ?>

                        <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">

                          <!--<td><input type="checkbox" name="kt_pk_evenements" class="id_checkbox" rel="<?php echo $row_rsowner_shopper1['user_id']; ?>" event="<?php echo $row_rsowner_shopper1['titre']; ?>" value="<?php echo $row_rsowner_shopper1['event_id']; ?>" />

                              <input type="hidden" name="event_id" class="id_field" value="<?php echo $row_rsowner_shopper1['event_id']; ?>" />

                          </td>-->

                          <td style="white-space: normal;"><div class="KT_col_nom"><?php echo ($row_rsowner_shopper1['nom']); ?></div></td>

                          <!--<td style="white-space: normal;"><div class="KT_col_prenom"><?php echo $row_rsowner_shopper1['prenom']; ?></div></td>-->

                          <td style="white-space: normal;"><div class="KT_col_email"><?php echo $row_rsowner_shopper1['email']; ?></div></td>

                          <td style="white-space: normal;"><div class="KT_col_telephone"><?php echo $row_rsowner_shopper1['telephone']; ?></div></td>

                          <td style="white-space: normal;"><div class="KT_col_siren"><?php echo $row_rsowner_shopper1['sirens']; ?></div></td>

                          <td style="white-space: normal;"><div class="KT_col_nom_magazin"><?php echo $row_rsowner_shopper1['nom_magazin']; ?></div></td>

                          <td style="white-space: normal;"><div class="KT_col_siren"><?php echo $row_rsowner_shopper1['siren']; ?></div></td>

                          <td style="white-space: normal;"><div class="KT_col_adresse"><?php echo $row_rsowner_shopper1['adresse']; ?></div></td>

                          <td style="white-space: normal;"><div class="KT_col_nom_region"><?php echo $row_rsowner_shopper1['nom_region']; ?></div></td>

                          <td style="white-space: normal;"><div class="KT_col_nom_date"><?php echo $row_rsowner_shopper1['date']; ?></div></td>

                          <td style="white-space: normal;"><div class="KT_col_nom_date_action"><?php echo $row_rsowner_shopper1['date_action']; ?></div></td>

                          <td>

							<?php if($row_rsowner_shopper1['approuve']== '0' ){ ?><a class="KT_edit_link" href="formOwnerShopper.php?id=<?php echo $row_rsowner_shopper1['id']; ?>&amp;id_user=<?php echo $row_rsowner_shopper1['id_user']; ?>&amp;id_magazin=<?php echo $row_rsowner_shopper1['id_magazin']; ?>&amp;approuve=1">Accepter</a><?php }?>

                        	<?php if($row_rsowner_shopper1['approuve']== '0' ){ ?><a class="KT_edit_link" href="formOwnerShopper.php?id=<?php echo $row_rsowner_shopper1['id']; ?>&amp;id_user=<?php echo $row_rsowner_shopper1['id_user']; ?>&amp;id_magazin=<?php echo $row_rsowner_shopper1['id_magazin']; ?>&amp;unapprouve=1">Refuser</a><?php }?>

                          	<?php 

							if($row_rsowner_shopper1['approuve']== '1' ){

								echo"Accepter";

							}elseif($row_rsowner_shopper1['approuve']== '2' ){

								echo"Refuser";	

							}

							?>

                          </td>

                        </tr>

                        <?php } while ($row_rsowner_shopper1 = mysql_fetch_assoc($rsowner_shopper1)); ?>

                      <?php } // Show if recordset not empty ?>

                  </tbody>

                </table>

                <div class="KT_bottomnav">

                  <div>

                    <?php

            $nav_listowner_shopper3->Prepare();

            require("../includes/nav/NAV_Text_Navigation.inc.php");

          ?>

                  </div>

                </div>

                <div class="KT_bottombuttons">

                  <!--<div class="KT_operations"><input type="button" id="approv" value="TOUT Accepter" /> <a class="KT_delete_op_link" href="#" onclick="nxt_list_delete_link_form(this); return false;"><?php echo NXT_getResource("delete_all"); ?></a> </div>

<span>&nbsp;</span>

                  <a class="KT_additem_op_link" href="form_event.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a>--> </div>

              </form>

            </div>

            <br class="clearfixplain" />

          </div>

          <p>&nbsp;</p>

  		</div>

	</div>

</div>

</body>

</html>

<?php

mysql_free_result($Recordset1);



mysql_free_result($Recordset2);



mysql_free_result($Recordset3);



mysql_free_result($rsowner_shopper1);

?>