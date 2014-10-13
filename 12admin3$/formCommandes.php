<?php require_once('../Connections/magazinducoin.php'); ?>

<?php

// Load the common classes

require_once('../includes/common/KT_common.php');



// Load the tNG classes

require_once('../includes/tng/tNG.inc.php');



// Load the KT_back class

require_once('../includes/nxt/KT_back.php');



// Make a transaction dispatcher instance

$tNGs = new tNG_dispatcher("../");



// Make unified connection variable

$conn_magazinducoin = new KT_connection($magazinducoin, $database_magazinducoin);



//Start Restrict Access To Page

$restrict = new tNG_RestrictAccess($conn_magazinducoin, "../");

//Grand Levels: Level

$restrict->addLevel("4");

$restrict->Execute();

//End Restrict Access To Page



// Start trigger

$formValidation = new tNG_FormValidation();

$formValidation->addField("id_user", true, "numeric", "", "", "", "");

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



function Trigger_Desable_All(){

	$query_Recordset1 = "UPDATE abonement SET  active  = 0 WHERE id_user = ".$_GET['id_user'];

	mysql_query($query_Recordset1) or die(mysql_error());

}

// Make an insert transaction instance

$ins_abonement = new tNG_multipleInsert($conn_magazinducoin);

$tNGs->addTransaction($ins_abonement);

// Register triggers

$ins_abonement->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");

$ins_abonement->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);

$ins_abonement->registerTrigger("BEFORE", "Trigger_Desable_All", 11);

$ins_abonement->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");

// Add columns

$ins_abonement->setTable("abonement");

$ins_abonement->addColumn("id_user", "NUMERIC_TYPE", "POST", "id_user");

$ins_abonement->addColumn("date_abonement", "STRING_TYPE", "POST", "date_abonement",date('Y-m-d'));

$ins_abonement->addColumn("date_echeance", "STRING_TYPE", "POST", "date_echeance",date('Y-m-d',mktime(0,0,0,date('m')+2,date('d'),date('Y'))));

$ins_abonement->addColumn("mode_payement", "STRING_TYPE", "POST", "mode_payement");

$ins_abonement->addColumn("montant", "STRING_TYPE", "POST", "montant");

$ins_abonement->setPrimaryKey("id", "NUMERIC_TYPE");



// Make an update transaction instance

$upd_abonement = new tNG_multipleUpdate($conn_magazinducoin);

$tNGs->addTransaction($upd_abonement);

// Register triggers

$upd_abonement->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");

$upd_abonement->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);

$upd_abonement->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");

// Add columns

$upd_abonement->setTable("abonement");

$upd_abonement->addColumn("id_user", "NUMERIC_TYPE", "POST", "id_user");

$upd_abonement->addColumn("date_abonement", "STRING_TYPE", "POST", "date_abonement");

$upd_abonement->addColumn("date_echeance", "STRING_TYPE", "POST", "date_echeance");

$upd_abonement->addColumn("mode_payement", "STRING_TYPE", "POST", "mode_payement");

$upd_abonement->addColumn("montant", "STRING_TYPE", "POST", "montant");

$upd_abonement->addColumn("code_promo", "STRING_TYPE", "POST", "code_promo");

$upd_abonement->addColumn("credit_plus", "STRING_TYPE", "POST", "credit_plus");

$upd_abonement->setPrimaryKey("id", "NUMERIC_TYPE", "GET", "id");



// Make an instance of the transaction object

$del_abonement = new tNG_multipleDelete($conn_magazinducoin);

$tNGs->addTransaction($del_abonement);

// Register triggers

$del_abonement->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");

$del_abonement->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");

// Add columns

$del_abonement->setTable("abonement");

$del_abonement->setPrimaryKey("id", "NUMERIC_TYPE", "GET", "id");



// Execute all the registered transactions

$tNGs->executeTransactions();



// Get the transaction recordset

$rsabonement = $tNGs->getRecordset("abonement");

$row_rsabonement = mysql_fetch_assoc($rsabonement);

$totalRows_rsabonement = mysql_num_rows($rsabonement);



if (@$_GET['id'] != "")

	$id_user = $row_rsabonement['id_user'];

else

	$id_user = $_GET['id_user'];

	

mysql_select_db($database_magazinducoin, $magazinducoin);

$query_Recordset1 = "SELECT * FROM utilisateur WHERE id = $id_user";

$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());

$row_Recordset1 = mysql_fetch_assoc($Recordset1);





if(isset($_GET['re'])){

	$query_re = "SELECT * FROM abonement WHERE id_user = $id_user AND active = 1";

	$re = mysql_query($query_re, $magazinducoin) or die(mysql_error());

	$row_re = mysql_fetch_assoc($re);

	$date = explode('-',$row_re['date_echeance']);

	$row_rsabonement['date_abonement'] = date('Y-m-d',mktime(0,0,0,$date[1],$date[2]+1,$date[0]));

	$row_rsabonement['date_echeance'] = date('Y-m-d',mktime(0,0,0,$date[1]+2,$date[2]+1,$date[0]));

}

if(isset($_GET['activer'])){

	$query_re = "UPDATE abonement SET active = 1 WHERE id = ".$_GET['id'];

	mysql_query($query_re, $magazinducoin) or die(mysql_error());

	

	// avoir le montant de la commande

	$query_re = "SELECT montant FROM abonement WHERE id = ".$_GET['id'];

	$com = mysql_query($query_re, $magazinducoin) or die(mysql_error());

	$row_com = mysql_fetch_assoc($com);

	

	$query_re = "UPDATE utilisateur SET credit = credit + ".$row_com['montant']." WHERE id = $id_user";

	mysql_query($query_re, $magazinducoin) or die(mysql_error());

	

	// ici la notification par email

	$headers = 'From: contact@magasinducoin.fr' . "\r\n" .

     'X-Mailer: PHP/' . phpversion();

	mail($row_Recordset1['email'],'Votre commande est active','Bonjour, votre commande numero #'.$_GET['id'].' du montant '.$row_com['montant'].' € est valide !',$headers);

		

	header('Location: commandes.php?id_user='.$_GET['id_user']);

}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

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

    <link href="../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />

    <script src="../includes/common/js/base.js" type="text/javascript"></script>

    <script src="../includes/common/js/utility.js" type="text/javascript"></script>

    <script src="../includes/skins/style.js" type="text/javascript"></script>

    <?php echo $tNGs->displayValidationRules();?>

    <script src="../includes/nxt/scripts/form.js" type="text/javascript"></script>

    <script src="../includes/nxt/scripts/form.js.php" type="text/javascript"></script>

    <script type="text/javascript">

$NXT_FORM_SETTINGS = {

  duplicate_buttons: false,

  show_as_grid: true,

  merge_down_value: true

}

    </script>

</head>

<body id="sp">

<?php include("modules/header.php"); ?>



<div class="content_wrapper_sbr">

	<div>

  		<div id="content">

          <?php

	echo $tNGs->getErrorMsg();

?>

          <div class="KT_tng">

            <h1>

              <?php 

// Show IF Conditional region1 

if (@$_GET['id'] == "") {

?>

                <?php echo NXT_getResource("Insert_FH"); ?>

                <?php 

// else Conditional region1

} else { ?>

                <?php echo NXT_getResource("Update_FH"); ?>

                <?php } 

// endif Conditional region1

?>

              Abonement </h1>

            <div class="KT_tngform">

              <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">

                <?php $cnt1 = 0; ?>

                <?php do { ?>

                  <?php $cnt1++; ?>

                  <?php 

// Show IF Conditional region1 

if (@$totalRows_rsabonement > 1) {

?>

                    <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>

                    <?php } 

// endif Conditional region1

?>

                  <table cellpadding="2" cellspacing="0" class="KT_tngtable">

                                                      <?php 

// Show IF Conditional region1 

if (@$_GET['id'] != "") {

?>

    

                  <tr>

                      <td class="KT_th"><label for="id_user_<?php echo $cnt1; ?>">Numero de la commande:</label></td>

                      <td>

                      #<?php echo str_pad($_GET['id'], 7, "0", STR_PAD_LEFT); ?></td>

                    </tr>

<?php } ?>

                    <tr>

                      <td class="KT_th"><label for="id_user_<?php echo $cnt1; ?>">Utilisateur:</label></td>

                      <td>

                      <input name="id_user_<?php echo $cnt1; ?>" type="hidden" value="<?php echo $id_user; ?>" />

                      <?php echo $row_Recordset1['prenom']?> <?php echo $row_Recordset1['nom']?></td>

                    </tr>

                    

                    <tr>

                      <td class="KT_th"><label for="id_user_<?php echo $cnt1; ?>">Date d'abonnement:</label></td>

                      <td>

                      <?php echo KT_formatDate($row_rsabonement['date_abonement']); ?>

                      <input name="date_abonement_<?php echo $cnt1; ?>" type="hidden" value="<?php echo $row_rsabonement['date_abonement']?>" />

                      </td>

                    </tr>

                    

                    <tr>

                      <td class="KT_th"><label for="id_user_<?php echo $cnt1; ?>">Date d'écheance:</label></td>

                      <td>

                      <?php echo KT_formatDate($row_rsabonement['date_echeance']); ?>

                      <input name="date_echeance_<?php echo $cnt1; ?>" type="hidden" value="<?php echo $row_rsabonement['date_echeance']?>" />

                      </td>

                    </tr>

                    

                    <tr>

                      <td class="KT_th"><label for="id_user_<?php echo $cnt1; ?>">Montant</label></td>

                      <td>

                      <?php echo KT_formatDate($row_rsabonement['montant']); ?> € 

                      <input name="montant_<?php echo $cnt1; ?>" type="hidden" value="<?php echo $row_rsabonement['montant']?>" />

                      </td>

                    </tr>

                    <tr>

                      <td class="KT_th"><label for="id_user_<?php echo $cnt1; ?>">Promo</label></td>

                      <td>

                      <?php echo KT_formatDate($row_rsabonement['code_promo']); ?> %

                      <input name="montant_<?php echo $cnt1; ?>" type="hidden" value="<?php echo $row_rsabonement['code_promo']?>" />

                      </td>

                    </tr>

                    

                     <tr>

                      <td class="KT_th"><label for="id_user_<?php echo $cnt1; ?>">Total</label></td>

                      <td>

                      <?php echo KT_formatDate($row_rsabonement['credit_plus']); ?> € 

                      <input name="montant_<?php echo $cnt1; ?>" type="hidden" value="<?php echo $row_rsabonement['credit_plus']?>" />

                      </td>

                    </tr>

                    <tr>

                      <td class="KT_th"><label for="id_user_<?php echo $cnt1; ?>">Mode de paiement:</label></td>

                      <td>

                      <?php echo $row_rsabonement['mode_payement']; ?>

                                    <?php 

// Show IF Conditional region1 

if (@$_GET['id'] == "") {

?>

                      <select name="mode_payement_<?php echo $cnt1; ?>" id="mode_payement_<?php echo $cnt1; ?>">

                        <option value="Paypal">Paypal</option>

                        <option value="Chèque">Chèque</option>

                        <option value="Virement bancaire">Virement bancaire</option>

                      </select>

<?php } ?>

                      </td>

                    </tr>

                    

                  </table>

                  <input type="hidden" name="kt_pk_abonement_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsabonement['kt_pk_abonement']); ?>" />

                  <?php } while ($row_rsabonement = mysql_fetch_assoc($rsabonement)); ?>

                <div class="KT_bottombuttons">

                  <div>

                    <?php 

      // Show IF Conditional region1

      if (@$_GET['id'] == "") {

      ?>

                      <input type="submit" name="KT_Insert1" id="KT_Insert1" value="<?php echo NXT_getResource("Insert_FB"); ?>" />

                      <?php 

      // else Conditional region1

      }  ?>

                 <div class="KT_operations">

                    <input type="button" name="KT_Cancel1" value="Retour<?php //echo NXT_getResource("Cancel_FB"); ?>" onclick="return UNI_navigateCancel(event, '../includes/nxt/back.php')" />

                  </div>

                </div>

              </form>

            </div>

            <br class="clearfixplain" />

          </div>

          <p>&nbsp;</p>

  		</div>

  </div>

</div>

<?php //include("modules/footer.php"); ?>

</body>

</html>

<?php

mysql_free_result($Recordset1);

?>