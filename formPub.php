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
$restrict->addLevel("1");
$restrict->Execute();
//End Restrict Access To Page

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("titre", true, "text", "", "", "", "");
$formValidation->addField("region", true, "numeric", "", "", "", "");
$formValidation->addField("emplacement", true, "numeric", "", "", "", "");
$formValidation->addField("id_produit", true, "numeric", "", "", "", "");
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
$query_Recordset1 = "SELECT * FROM pub_emplacement ORDER BY titre";
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$liste_pub = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset3 = "SELECT titre, id FROM produits WHERE id_user = ".$_SESSION['kt_login_id']." ORDER BY titre";
$Recordset3 = mysql_query($query_Recordset3, $magazinducoin) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

if(isset($_POST['KT_Insert1'])){
	$id_produit = $_POST['id_produit_1'];
	if(count($_POST['pub'])){
		$rkt="SELECT magazins.region, produits.categorie, produits.titre
FROM (produits
LEFT JOIN magazins ON magazins.id_magazin=produits.id_magazin)
WHERE produits.id = $id_produit ";
		$query=mysql_query($rkt);
		$titreproduit=mysql_fetch_array($query);
		$titre= $titreproduit['titre'];
		$cat= $titreproduit['categorie'];
                $region=$titreproduit['region'];
		$jr=30;
		$datefin = date("Y-m-d H:i:s", mktime(date("H"), date("i"), date("s"), date("m"), date("d")+$jr,  date("Y")));	
		$ids = array();
	  	foreach($_POST['pub'] as $k=>$v){
		  	$sql="insert into pub (id_user,titre,region,emplacement,id_produit,date_fin) values (".$_SESSION['kt_login_id'].",'$titre','$region','$k','$id_produit','$datefin')";
		  	$query2=mysql_query($sql);
		  	$ids[] = mysql_insert_id();
      	}
        
                $query_Recordset1 = "SELECT credit FROM utilisateur WHERE id = ".$_SESSION['kt_login_id'];
                $Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
                $row_credit = mysql_fetch_assoc($Recordset1);
		$credit= $row_credit['credit'];
                
		if(count($ids)){
			if($credit<0)
				header('Location: payer_pub.php?ap=1&ids='.implode(",",$ids));
			else
	 			header('Location: payer_par_credit.php?ids='.implode(",",$ids));
		}
		else
			header('Location: mes-pubs.php');	
	}
	else $error = "Vous devez choisir au moins un emplacement de publicité!";
}

// Make an instance of the transaction object
$del_pub = new tNG_multipleDelete($conn_magazinducoin);
$tNGs->addTransaction($del_pub);
// Register triggers
$del_pub->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_pub->registerTrigger("END", "Trigger_Default_Redirect", 99, "includes/nxt/back.php");
// Add columns
$del_pub->setTable("pub");
$del_pub->setPrimaryKey("id", "NUMERIC_TYPE", "GET", "id");

// Execute all the registered transactions
$tNGs->executeTransactions();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 
	<title>Magasin du coin |  </title>
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
  show_as_grid: false,
  merge_down_value: true
}
</script>
</head>
<body id="sp">
<?php include("modules/header.php"); ?>
  		<div id="content" class="photographes">
        <?php include("modules/credit.php"); ?> 
		<?php include("modules/membre_menu.php"); ?>
<div style="padding-left:250px;height:500px;">
          <?php
	echo $tNGs->getErrorMsg();
?>
          <div class="KT_tng">
            <h1>
              <?php 
	// Show IF Conditional region1 
if (@$_GET['id'] == "") {
?>
                <?php echo $xml->insertion; ?>
                <?php 
// else Conditional region1
} else { ?>
                <?php echo $xml->modification; ?>
                <?php } 
// endif Conditional region1
?>
              <?php echo $xml->Pub ?> </h1>
            <div class="KT_tngform">
              <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
                <?php $cnt1 = 0; ?>
                <?php //do { ?>
                  <?php $cnt1++; ?>
                  <?php 
// Show IF Conditional region1 
if (@$totalRows_rspub > 1) {
?>
                    <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
                    <?php } 
// endif Conditional region1

?>
<?php if(isset($error)) echo '<div class="error">'.$error.'</div>'; ?>
     <div style="padding-left:20px; position:relative; height:60px;" class="form_insc2">
     

     <input type="hidden" name="titre_<?php echo $cnt1; ?>" id="titre_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rspub['titre']); ?>" size="32" maxlength="100" />
                          <?php echo $tNGs->displayFieldHint("titre");?> <?php echo $tNGs->displayFieldError("pub", "titre", $cnt1); ?>
                 
 
<div class="champ"> 
 <label for="id_produit_<?php echo $cnt1; ?>"><?php echo $xml->produit ?>:</label>
 <select name="id_produit_<?php echo $cnt1; ?>" id="id_produit_<?php echo $cnt1; ?>" onchange="document.getElementById('titre_<?php echo $cnt1; ?>').value = this[this.selectedIndex].text; ajax('ajax/getListePub.php?id='+this.value,'#resultat')">
                          <option value=""><?php echo $xml->selectionner ?></option>
                          <?php 
do {  
?>
                          <option value="<?php echo $row_Recordset3['id']?>"<?php if (!(strcmp($row_Recordset3['id'], $row_rspub['id_produit']))) {echo "SELECTED";} ?>><?php echo $row_Recordset3['titre']?></option>
                          <?php
} while ($row_Recordset3 = mysql_fetch_assoc($Recordset3));
  $rows = mysql_num_rows($Recordset3);
  if($rows > 0) {
      mysql_data_seek($Recordset3, 0);
	  $row_Recordset3 = mysql_fetch_assoc($Recordset3);
  }
?>
                        </select>
                          <?php echo $tNGs->displayFieldError("pub", "id_produit", $cnt1); ?>
 </div>                                                
    </div>  
   <div class="clear"></div> 
    <div style="padding-left:20px; position:relative;">
     <div style="width:400px; float:left" id="resultat">
                  <?php echo $xml->Veuillez_selectionner_un_produit ?>
      </div>
     <!--<div style="float:right; width:370px;"><img src="assets/images/img_positionnement.jpg" /></div>-->	                      

                  <input type="hidden" name="kt_pk_pub_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rspub['kt_pk_pub']); ?>" />
                  <input type="hidden" name="id_user_<?php echo $cnt1; ?>" id="id_user_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rspub['id_user']); ?>" />
                  <input type="hidden" name="date_fin_<?php echo $cnt1; ?>" id="date_fin_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rspub['date_fin']); ?>" />
                  <?php //} while ($row_rspub = mysql_fetch_assoc($rspub)); ?>
                  <div class="clear"></div>
                  </div>
                <div class="KT_bottombuttons">
                  <div>
                    <?php 
      // Show IF Conditional region1
      if (@$_GET['id'] == "") {
      ?>
                      <input type="submit" name="KT_Insert1" id="KT_Insert1" value="<?php echo $xml->valider   ?>" />
                      <?php 
      // else Conditional region1
      } else { ?>
                      <input type="submit" name="KT_Update1" value="<?php echo $xml->editer ?>" />
                      <input type="submit" name="KT_Delete1" value="<?php echo $xml->supprimer; ?>" onclick="return confirm('<?php echo $xml->vous_etes_sur ;; ?>');" />
                      <?php }
      // endif Conditional region1
      ?>
                    <input type="button" name="KT_Cancel1" value="<?php echo $xml->annuler   ?>" onclick="return UNI_navigateCancel(event, 'includes/nxt/back.php')" />
                  </div>
                </div>
              </form>
            </div>
            <br class="clearfixplain" />
          </div>
          </div>
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

mysql_free_result($Recordset3);
?>