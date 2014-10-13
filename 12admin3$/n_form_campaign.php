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
$formValidation->addField("id", true, "numeric", "", "", "", "");
$formValidation->addField("name", true, "text", "", "", "", "");
$formValidation->addField("subject", true, "text", "", "", "", "");
$formValidation->addField("from_name", true, "text", "", "", "", "");
$formValidation->addField("id_temaplate", true, "text", "", "", "", "");
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
$query_Recordset1 = "SELECT * FROM n_template ORDER BY id DESC";
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);


// Make an insert transaction instance
$ins_n_campaign = new tNG_multipleInsert($conn_magazinducoin);
$tNGs->addTransaction($ins_n_campaign);
// Register triggers
$ins_n_campaign->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_n_campaign->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_n_campaign->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$ins_n_campaign->registerTrigger("AFTER", "Trigger_SendMail", 97);

function Trigger_selectdata($contentdata,$nom='',$id='',$email='') {
	$regex = "/\[(.*?)\]/";
	preg_match_all($regex, $contentdata, $matches);
	for($i = 0; $i < count($matches[1]); $i++){
		$match = $matches[1][$i];
		if($match=='nom'){
			$name=$nom;
			$contentdata = str_replace($matches[0][$i], $name, $contentdata);
		}elseif($match=='id'){
			$name=$id;
			$contentdata = str_replace($matches[0][$i], $name, $contentdata);
		}elseif($match=='email'){
			$name=$email;
			$contentdata = str_replace($matches[0][$i], $name, $contentdata);
		}
	}
	return $contentdata;
} 

function Trigger_SendMail(&$tNG){
	global $magazinducoin;
	
	$query_liste_magasins = sprintf("SELECT	content FROM n_template WHERE id = %s ",
					 				GetSQLValueString($_POST['id_temaplate_1'], "int"));
	$liste_magasins = mysql_query($query_liste_magasins, $magazinducoin) or die(mysql_error());
	$magasin_en_cour = mysql_fetch_assoc($liste_magasins);
	
	$query_liste_magasins = sprintf("SELECT	id,nom,email FROM utilisateur WHERE subscribe='1' ");
	$liste_magasins = mysql_query($query_liste_magasins, $magazinducoin) or die(mysql_error());
	while($liste = mysql_fetch_assoc($liste_magasins)) {
	  //$nom 		= $liste['nom'];
	  $email 	= $liste['email'];
	  $nom 	= $liste['nom'];
	  $id 	= $liste['id'];
	  
		require_once('../PHPMailer/config.php');	
		require_once('../PHPMailer/class.phpmailer.php');		
		$mail  = new PHPMailer(); // defaults to using php "mail()"		
		$body = Trigger_selectdata(utf8_decode($magasin_en_cour['content']),$nom,$id,$email);
				
		if(Host<>'smtp.mandrillapp.com'){
			$mail->IsSMTP(); // telling the class to use SMTP
			$mail->SMTPAuth      = true;                  // enable SMTP authentication
			$mail->SMTPKeepAlive = true;                  // SMTP connection will not close after each email sent
			$mail->Host          = Host; // sets the SMTP server
			$mail->Port          = Port;                    // set the SMTP port for the GMAIL server
			$mail->Username      = Username; // SMTP account username
			$mail->Password      = Password;        // SMTP account password
		}
		//$mail->AddReplyTo(Email,Name);		
		//$mail->SetFrom(EmailFrom,NameFrom);
		$mail->SetFrom(EmailFrom,utf8_decode($_POST['from_name_1']));			
		//$mail->AddReplyTo(Email,Name);
		
		$address = $email;
		$mail->AddAddress($address, $address);		
		$mail->Subject  = utf8_decode($_POST['subject_1']);
		
		$mail->AltBody  = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test		
		$mail->MsgHTML($body);		
		//$mail->AddAttachment("Testing.pdf");      // attachment		
		if(!$mail->Send()) {
			echo "Mailer Error: " . $mail->ErrorInfo;
		} else {
			//echo " Message envoy&eacute;!";
		}
	  
	  //$emailObj = new tNG_Email($tNG);
//	  $emailObj->setFrom("contact@magasinducoin.com");
//	  $emailObj->setTo($email);
//	  $emailObj->setCC("");
//	  $emailObj->setBCC("");
//	  $emailObj->setSubject("".$_POST['subject_1']);
//	  //FromFile method
//	  $content = $magasin_en_cour['content'];
//	  $emailObj->setContent($content);
//	  $emailObj->setEncoding("ISO-8859-1");
//	  $emailObj->setFormat("HTML/Text");
//	  $emailObj->setImportance("Normal");
//	  return $emailObj->Execute();
	}	
	echo'<script>window.location="n_campaigns.php?info=ACTIVATED";</script>';
}

// Add columns
$ins_n_campaign->setTable("n_campaign");
$ins_n_campaign->addColumn("name", "STRING_TYPE", "POST", "name");
$ins_n_campaign->addColumn("subject", "STRING_TYPE", "POST", "subject");
$ins_n_campaign->addColumn("from_name", "STRING_TYPE", "POST", "from_name");
$ins_n_campaign->addColumn("id_temaplate", "STRING_TYPE", "POST", "id_temaplate");
//$ins_n_campaign->addColumn("date_send", "STRING_TYPE", "POST", "date_send");
$ins_n_campaign->addColumn("date_process", "STRING_TYPE", "POST", "date_process");
$ins_n_campaign->setPrimaryKey("id", "NUMERIC_TYPE");
//die($_POST['latlan_1']."hhf");
// Make an update transaction instance
$upd_n_campaign = new tNG_multipleUpdate($conn_magazinducoin);
$tNGs->addTransaction($upd_n_campaign);
// Register triggers
$upd_n_campaign->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_n_campaign->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_n_campaign->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
//$upd_n_campaign->registerTrigger("AFTER", "Trigger_ImageUpload2", 97);
// Add columns
$upd_n_campaign->setTable("n_campaign");
$upd_n_campaign->addColumn("name", "STRING_TYPE", "POST", "name");
$upd_n_campaign->addColumn("subject", "STRING_TYPE", "POST", "subject");
$upd_n_campaign->addColumn("from_name", "STRING_TYPE", "POST", "from_name");
$upd_n_campaign->addColumn("id_temaplate", "STRING_TYPE", "POST", "id_temaplate");
//$upd_n_campaign->addColumn("date_send", "STRING_TYPE", "POST", "date_send");
$upd_n_campaign->addColumn("date_process", "STRING_TYPE", "POST", "date_process");
$upd_n_campaign->setPrimaryKey("id", "NUMERIC_TYPE", "GET", "id");

// Make an instance of the transaction object
$del_n_campaign = new tNG_multipleDelete($conn_magazinducoin);
$tNGs->addTransaction($del_n_campaign);
// Register triggers
$del_n_campaign->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_n_campaign->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
//$del_n_campaign->registerTrigger("AFTER", "Trigger_FileDelete2", 98);
// Add columns
$del_n_campaign->setTable("n_campaign");
$del_n_campaign->setPrimaryKey("id", "NUMERIC_TYPE", "GET", "id");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsarticles = $tNGs->getRecordset("n_campaign");
$row_rsarticles = mysql_fetch_assoc($rsarticles);
$totalRows_rsarticles = mysql_num_rows($rsarticles);

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
.KT_tng a{
	padding:0px !important;
}
.jqte{
	width:700px;
	margin:0px !important;
}
</style>
    <link href="../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
	<script src="../template/js/jquery.js" type="text/javascript"></script>
    <script type="text/javascript">
		function ajax(murl,mresult){
			$(mresult).addClass("en_cours");
			$.ajax({
				  url: murl,
				  cache: false,
				  success: function(html){
					$(mresult).html(html);
					$(mresult).removeClass("en_cours");
				  }
				});
			}
	</script>
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
    
<link type="text/css" rel="stylesheet" href="../assets/texteditor/jquery-te-1.4.0.css">
<script type="text/javascript" src="http://code.jquery.com/jquery.min.js" charset="utf-8"></script>
<script src="../assets/texteditor/jquery-te-1.4.0.min.js" type="text/javascript"></script>

<script type="text/javascript" src="../includes/common/js/sigslot_core.js"></script>
<script type="text/javascript" src="../includes/wdg/classes/MXWidgets.js"></script>
<script type="text/javascript" src="../includes/wdg/classes/MXWidgets.js.php"></script>
<script type="text/javascript" src="../includes/wdg/classes/Calendar.js"></script>
<script type="text/javascript" src="../includes/wdg/classes/SmartDate.js"></script>
<script type="text/javascript" src="../includes/wdg/calendar/calendar_stripped.js"></script>
<script type="text/javascript" src="../includes/wdg/calendar/calendar-setup_stripped.js"></script>
<script src="../includes/resources/calendar.js"></script>
<script src="../template/js/jquery.js" type="text/javascript"></script>
<script type="text/javascript">
		function ajax(murl,mresult){
			$(mresult).addClass("en_cours");
			$.ajax({
				  url: murl,
				  cache: false,
				  success: function(html){
					$(mresult).html(html);
					$(mresult).removeClass("en_cours");
				  }
				});
			}
	</script>
<script src="../includes/skins/style.js" type="text/javascript"></script>

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
              Campaign </h1>
            <div class="KT_tngform">
              <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" enctype="multipart/form-data">
                <?php $cnt1 = 0; ?>
                <?php do { ?>
                  <?php $cnt1++; ?>
                  <?php 
// Show IF Conditional region1 
if (@$totalRows_rsarticles > 1) {
?>
                    <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
                    <?php } 
// endif Conditional region1
?>
                  <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                    <tr>
                      <td class="KT_th" style="width:150px;"><label for="name_<?php echo $cnt1; ?>">Name :</label></td>
                      <td><input type="text" name="name_<?php echo $cnt1; ?>" id="name_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsarticles['name']); ?>" size="32" maxlength="250" />
                          <?php echo $tNGs->displayFieldHint("name");?> <?php echo $tNGs->displayFieldError("n_campaign", "name", $cnt1); ?> </td>
                    </tr>

                    <tr>
                      <td class="KT_th"><label for="subject_<?php echo $cnt1; ?>">Subject :</label></td>
                      <td><input type="text" name="subject_<?php echo $cnt1; ?>" id="subject_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsarticles['subject']); ?>" size="32" maxlength="250" />
                          <?php echo $tNGs->displayFieldHint("subject");?> <?php echo $tNGs->displayFieldError("n_campaign", "subject", $cnt1); ?> </td>
                    </tr>

                    <tr>
                      <td class="KT_th"><label for="from_name_<?php echo $cnt1; ?>">From Name :</label></td>
                      <td><input type="text" name="from_name_<?php echo $cnt1; ?>" id="from_name_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsarticles['from_name']); ?>" size="32" maxlength="250" />
                          <?php echo $tNGs->displayFieldHint("from_name");?> <?php echo $tNGs->displayFieldError("n_campaign", "from_name", $cnt1); ?> </td>
                    </tr>

                    <tr>
                      <td class="KT_th"><label for="id_temaplate_<?php echo $cnt1; ?>">Template :</label></td>
                      <td><select name="id_temaplate_<?php echo $cnt1; ?>" id="id_temaplate_<?php echo $cnt1; ?>">
                          <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
                          <?php 
							do {  
							?>
                          <option value="<?php echo $row_Recordset1['id']?>"<?php if (!(strcmp($row_Recordset1['id'], $row_rsarticles['id_temaplate']))) {echo "SELECTED";} ?>>
						  <?php 
						  $vowels = array("@");
						  echo $onlyconsonants = str_replace($vowels, "&#64;", $row_Recordset1['titre']);
						  ?></option>
                          <?php
							} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
							  $rows = mysql_num_rows($Recordset1);
							  if($rows > 0) {
								  mysql_data_seek($Recordset1, 0);
								  $row_Recordset1 = mysql_fetch_assoc($Recordset1);
							  }
							?>
                        </select>
                          <?php echo $tNGs->displayFieldError("n_campaign", "id_temaplate", $cnt1); ?> </td>
                    </tr>
                    <?php /*?><tr><td class="KT_th"><label for="date_send_<?php echo $cnt1; ?>">Date Send :</label></td>
                   <td> <input name="date_send_<?php echo $cnt1; ?>" id="date_send_<?php echo $cnt1; ?>" value="<?php echo KT_formatDate($row_rsarticles['date_send']); ?>" size="10" maxlength="22" wdg:mondayfirst="true" wdg:subtype="Calendar" wdg:mask="<?php echo $KT_screen_date_format; ?>" wdg:type="widget" wdg:singleclick="true" wdg:restricttomask="yes" />
                   <?php echo $tNGs->displayFieldHint("date_send");?> <?php echo $tNGs->displayFieldError("n_campaign", "date_send", $cnt1); ?> 
                    </td></tr><?php */?>
                                          
                  </table>
                  <input type="hidden" name="date_process" value="<?php echo date('Y-m-d');?>" />
                  <input type="hidden" name="kt_pk_n_campaign_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsarticles['kt_pk_n_campaign']); ?>" />
                  <?php } while ($row_rsarticles = mysql_fetch_assoc($rsarticles)); ?>
                <div class="KT_bottombuttons">
                  <div>
                    <?php 
      // Show IF Conditional region1
      if (@$_GET['id'] == "") {
      ?>
                      <input type="submit" name="KT_Insert1" id="KT_Insert1" value="<?php echo NXT_getResource("Insert_FB"); ?>" />
                      <?php 
      // else Conditional region1
      } else { ?>
                      <div class="KT_operations">
                        <input type="submit" name="KT_Insert1" value="<?php echo NXT_getResource("Insert as new_FB"); ?>" onclick="nxt_form_insertasnew(this, 'id')" />
                      </div>
                      <input type="submit" name="KT_Update1" value="<?php echo NXT_getResource("Update_FB"); ?>" />
                      <input type="submit" name="KT_Delete1" value="<?php echo NXT_getResource("Delete_FB"); ?>" onclick="return confirm('<?php echo NXT_getResource("Are you sure?"); ?>');" />
                      <?php }
      // endif Conditional region1
      ?>
                    <input type="button" name="KT_Cancel1" value="<?php echo NXT_getResource("Cancel_FB"); ?>" onclick="return UNI_navigateCancel(event, '../includes/nxt/back.php')" />
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
<script>
	$('.jqte-test').jqte();
	
	// settings of status
	var jqteStatus = true;
	$(".status").click(function()
	{
		jqteStatus = jqteStatus ? false : true;
		$('.jqte-test').jqte({"status" : jqteStatus})
	});
</script>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);



?>