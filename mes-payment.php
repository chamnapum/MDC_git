<?php require_once('Connections/magazinducoin.php'); ?>
<?php
if(!$_SESSION['kt_login_id']){
	echo'<script>window.location="message_aprouvation.php";</script>';
}
?>
<?php

// Load the common classes
require_once('includes/common/KT_common.php');

// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

// Load the required classes
require_once('includes/tfi/TFI.php');
require_once('includes/tso/TSO.php');
require_once('includes/nav/NAV.php');

// Make unified connection variable
$conn_magazinducoin = new KT_connection($magazinducoin, $database_magazinducoin);

//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_magazinducoin, "");
//Grand Levels: Level
$restrict->addLevel("1");
$restrict->Execute();
//End Restrict Access To Page
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

// Filter
$tfi_listIinvoice1 = new TFI_TableFilter($conn_magazinducoin, "tfi_listIinvoice1");
$tfi_listIinvoice1->addColumn("invoice.orber_num", "STRING_TYPE", "orber_num", "%");
$tfi_listIinvoice1->addColumn("invoice.invoice_id", "STRING_TYPE", "invoice_id", "%");
$tfi_listIinvoice1->addColumn("invoice.amount", "STRING_TYPE", "amount", "%");
$tfi_listIinvoice1->addColumn("invoice.type_payment", "STRING_TYPE", "type_payment", "%");
$tfi_listIinvoice1->addColumn("invoice.date_payment", "STRING_TYPE", "date_payment", "%");
$tfi_listIinvoice1->Execute();

// Sorter
$tso_listIinvoice1 = new TSO_TableSorter("rsInvoice1", "tso_listIinvoice1");
$tso_listIinvoice1->addColumn("invoice.orber_num");
$tso_listIinvoice1->addColumn("invoice.invoice_id");
$tso_listIinvoice1->addColumn("invoice.amount");
$tso_listIinvoice1->addColumn("invoice.type_payment");
$tso_listIinvoice1->addColumn("invoice.date_payment");
$tso_listIinvoice1->setDefault("invoice.invoice_id DESC");
$tso_listIinvoice1->Execute();

// Navigation
$nav_listIinvoice1 = new NAV_Regular("nav_listIinvoice1", "rsInvoice1", "", $_SERVER['PHP_SELF'], 10);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset1 = "SELECT nom_region, id_region FROM region ORDER BY nom_region";
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset2 = "SELECT nom, id_ville FROM maps_ville ORDER BY nom";
$Recordset2 = mysql_query($query_Recordset2, $magazinducoin) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

//NeXTenesio3 Special List Recordset
$maxRows_rsInvoice1 = $_SESSION['max_rows_nav_listIinvoice1'];
$pageNum_rsInvoice1 = 0;
if (isset($_GET['pageNum_rsInvoice1'])) {
  $pageNum_rsInvoice1 = $_GET['pageNum_rsInvoice1'];
}
$startRow_rsInvoice1 = $pageNum_rsInvoice1 * $maxRows_rsInvoice1;

// Defining List Recordset variable
$NXTFilter_rsInvoice1 = "1=1";
if (isset($_SESSION['filter_tfi_listIinvoice1'])) {
  $NXTFilter_rsInvoice1 = $_SESSION['filter_tfi_listIinvoice1'];
}
// Defining List Recordset variable
$NXTSort_rsInvoice1 = "invoice.invoice_id";
if (isset($_SESSION['sorter_tso_listIinvoice1'])) {
  $NXTSort_rsInvoice1 = $_SESSION['sorter_tso_listIinvoice1'];
}
mysql_select_db($database_magazinducoin, $magazinducoin);

$query_rsInvoice1 = "SELECT 
  invoice.*,
  utilisateur.email,
  utilisateur.telephone 
FROM
  invoice 
  INNER JOIN utilisateur 
    ON (invoice.id_user = utilisateur.id) WHERE {$NXTFilter_rsInvoice1} AND utilisateur.id = ".$_SESSION['kt_login_id']." ORDER BY {$NXTSort_rsInvoice1}";
$query_limit_rsInvoice1 = sprintf("%s LIMIT %d, %d", $query_rsInvoice1, $startRow_rsInvoice1, $maxRows_rsInvoice1);
$rsInvoice1 = mysql_query($query_limit_rsInvoice1, $magazinducoin) or die(mysql_error());
$row_rsInvoice1 = mysql_fetch_assoc($rsInvoice1);
//echo $query_limit_rsInvoice1;
if (isset($_GET['totalRows_rsInvoice1'])) {
  $totalRows_rsInvoice1 = $_GET['totalRows_rsInvoice1'];
} else {
  $all_rsInvoice1 = mysql_query($query_rsInvoice1);
  $totalRows_rsInvoice1 = mysql_num_rows($all_rsInvoice1);
}
$totalPages_rsInvoice1 = ceil($totalRows_rsInvoice1/$maxRows_rsInvoice1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listIinvoice1->checkBoundries();

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 
	<title>Magasinducoin</title>
    <?php include("modules/head.php"); ?>
<link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script src="includes/common/js/base.js" type="text/javascript"></script>
<script src="includes/common/js/utility.js" type="text/javascript"></script>
<script src="includes/skins/style.js" type="text/javascript"></script>
<script src="includes/nxt/scripts/list.js" type="text/javascript"></script>
<script src="includes/nxt/scripts/list.js.php" type="text/javascript"></script>
<script type="text/javascript">
$NXT_LIST_SETTINGS = {
  duplicate_buttons: false,
  duplicate_navigation: false,
  row_effects: true,
  show_as_buttons: true,
  record_counter: true
}
</script>
<style type="text/css">
  /* Dynamic List row settings */
.KT_col_nom_magazin {width:70px; overflow:hidden;}
.KT_col_region {width:140px; overflow:hidden;}
.KT_col_ville {width:140px; overflow:hidden;}
.KT_magasin, .KT_coupon, .KT_produit, .KT_event{
	width:70px; overflow:hidden;
}
.indicator{
	border: 0px;
	font-size: 12px;
	font-weight: bold;
	background-color: #9D286E;
	color: #FFF;
	padding: 0px 5px 3px 5px;
	margin-right: 5px;
	margin-bottom: 2px;
	margin-left:3px;
}
	.bar{
		margin-left:4px;
	}
.bar input, .bar a{
	border: 0px;
	font-size: 12px;
	font-weight: bold;
	background-color: #9D286E;
	color: #F8C263;
	padding: 0px 5px 3px 5px;
	cursor: pointer;
}
</style>
<link rel="stylesheet" href="assets/colorbox/colorbox.css" />
<script src="assets/colorbox/jquery.colorbox.js"></script>
<script>
	$(document).ready(function(){
		//$.colorbox.resize();
		//$('a.example').colorbox({open:true});
		$(".ajax").colorbox();
	});
</script>
</head>
<body id="sp">
<?php include("modules/header.php"); ?>
<div id="content">
	<?php include("modules/member_menu.php"); ?>
	<?php include("modules/credit.php"); ?>
	<?php //include("modules/membre_menu.php"); ?>
    
	<div style="padding-left:20px;">
  		  <h3>Ma liste des paiements</h3>
	<div style="margin-left:5px;">
 
    </div>         
		<div class="KT_tng" id="listIinvoice1" style="960px;">
  		    <div class="KT_tnglist">
			<form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
     
                <div class="KT_options"> <a href="<?php echo $nav_listIinvoice1->getShowAllLink(); ?>">
				<?php echo  $xml->afficher_tous?>
                  <?php 
  // Show IF Conditional region1
 if (@$_GET['show_all_nav_listIinvoice1'] == 1) {
?>
                    <?php  echo $_SESSION['default_max_rows_nav_listIinvoice1']; ?>
                    <?php 
  // else Conditional region1
 } else { ?>
                    <?php echo NXT_getResource("all"); ?>
                    <?php  } 
   //endif Conditional region1
?>
                      <?php //echo NXT_getResource("records"); ?></a> &nbsp;
                  &nbsp; </div>
                <table cellpadding="2" cellspacing="0" class="KT_tngtable" style="width:950px;">
                  <thead>
                    <tr class="KT_row_order">
                      <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/></th>
                      
                      <th class="KT_sorter  <?php echo $tso_listIinvoice1->getSortIcon('invoice.invoice_id'); ?>"> <a href="<?php echo $tso_listIinvoice1->getSortLink('invoice.invoice_id'); ?>">Facture n°</a> </th>
                      <th class="KT_sorter  <?php echo $tso_listIinvoice1->getSortIcon('invoice.orber_num'); ?>"> <a href="<?php echo $tso_listIinvoice1->getSortLink('invoice.orber_num'); ?>">Commande n° </a> </th>
                      <th class="KT_sorter  <?php echo $tso_listIinvoice1->getSortIcon('invoice.amount'); ?>"> <a href="<?php echo $tso_listIinvoice1->getSortLink('invoice.amount'); ?>">Montant TTC </a> </th>
                      <th class="KT_sorter  <?php echo $tso_listIinvoice1->getSortIcon('invoice.type_payment'); ?>"> <a href="<?php echo $tso_listIinvoice1->getSortLink('invoice.type_payment'); ?>">Mode de paiement</a> </th>
                      <th class="KT_sorter  <?php echo $tso_listIinvoice1->getSortIcon('invoice.date_payment'); ?>"> <a href="<?php echo $tso_listIinvoice1->getSortLink('invoice.date_payment'); ?>">Payée le</a> </th>
                      <th class="KT_sorter KT_type"> Type </th>
                      <th class="KT_sorter KT_magasin"> Magasin </th>
                      <th class="KT_sorter KT_produit"> Produit </th>
                      <th class="KT_sorter KT_coupon"> Coupon </th>
                      <th class="KT_sorter KT_event"> Événement </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if ($totalRows_rsInvoice1 == 0) { // Show if recordset empty ?>
                      <tr>
                        <td colspan="5" style="font-size:14px;"></td>
                      </tr>
                      <?php } // Show if recordset empty ?>
                    <?php if ($totalRows_rsInvoice1 > 0) { // Show if recordset not empty ?>
                      <?php do { ?>
                        <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                          <td><input type="checkbox" name="kt_pk_Invoice" class="id_checkbox" value="<?php echo $row_rsInvoice1['id_magazin']; ?>" />
                              <input type="hidden" name="id_magazin" class="id_field" value="<?php echo $row_rsInvoice1['id_magazin']; ?>" />
                          </td>
                          
                          <td style="white-space: normal;"><div class="KT_col_nom"><a class='ajax' href="invoice.php?id=<?php echo $row_rsInvoice1['invoice_id'];?>" style="color:#9D216E; cursor:pointer;" title="<?php echo ($row_rsInvoice1['invoice_id']); ?>"><?php echo ($row_rsInvoice1['invoice_id']); ?></a></div></td>
                          <td style="white-space: normal;"><div class="KT_col_email"><?php echo $row_rsInvoice1['orber_num']; ?></div></td>
                          <td style="white-space: normal;"><div class="KT_col_telephone"><?php echo $row_rsInvoice1['amount']; ?> &euro;</div></td>
                          <td style="white-space: normal;"><div class="KT_col_siren">
						  	<?php 
						  		if($row_rsinvoice1['type_payment']=='1')
								echo'PayPal';
								else
								echo'Crédit'; 
							?>
                          </div></td>
                          <td style="white-space: normal;"><div class="KT_col_nom_magazin"><?php echo $row_rsInvoice1['date_payment']; ?></div></td>
                          <td style="white-space: normal;"><div class="KT_col_siren">
						  	<?php 
						  		if($row_rsInvoice1['payon']=='1')
								echo'Magazin';
								elseif($row_rsInvoice1['payon']=='2')
								echo'Produit'; 
								elseif($row_rsInvoice1['payon']=='3')
								echo'Coupon'; 
								elseif($row_rsInvoice1['payon']=='4')
								echo'Événement'; 
							?>
                          </div></td>
                          
                          <td style="white-space: normal;"><div class="KT_col_nom_magazin">
						  		<?php 
									if(isset($row_rsInvoice1['id_magazin'])){
										mysql_select_db($database_magazinducoin, $magazinducoin);
										$query_Recordset1 = "SELECT nom_magazin FROM magazins WHERE id_magazin='".$row_rsInvoice1['id_magazin']."' ORDER BY nom_magazin";
										$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
										$row_Recordset1 = mysql_fetch_assoc($Recordset1);
										echo $row_Recordset1['nom_magazin'];
									} 
								?>
                          </div></td>
                          <td style="white-space: normal;"><div class="KT_col_nom_magazin">
						  		<?php 
									if(isset($row_rsInvoice1['id_produit'])){
										mysql_select_db($database_magazinducoin, $magazinducoin);
										$query_Recordset1 = "SELECT titre FROM produits WHERE id='".$row_rsInvoice1['id_produit']."' ";
										$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
										$row_Recordset1 = mysql_fetch_assoc($Recordset1);
										echo $row_Recordset1['titre'];
									} 
								?>
                          </div></td>
                          <td style="white-space: normal;"><div class="KT_col_nom_magazin">
						  		<?php 
									if(isset($row_rsInvoice1['id_coupon'])){
										mysql_select_db($database_magazinducoin, $magazinducoin);
										$query_Recordset1 = "SELECT titre FROM coupons WHERE id_coupon='".$row_rsInvoice1['id_coupon']."' ";
										$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
										$row_Recordset1 = mysql_fetch_assoc($Recordset1);
										echo $row_Recordset1['titre'];
									} 
								?>
                          </div></td>
                          <td style="white-space: normal;"><div class="KT_col_nom_magazin">
						  		<?php 
									if(isset($row_rsInvoice1['id_event'])){
										mysql_select_db($database_magazinducoin, $magazinducoin);
										$query_Recordset1 = "SELECT titre FROM evenements WHERE event_id='".$row_rsInvoice1['id_event']."' ";
										$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
										$row_Recordset1 = mysql_fetch_assoc($Recordset1);
										echo $row_Recordset1['titre'];
									} 
								?>
                          </div></td>
                        
                        </tr>
                        <?php } while ($row_rsInvoice1 = mysql_fetch_assoc($rsInvoice1)); ?>
                      <?php } // Show if recordset not empty ?>
                  </tbody>
                </table>
                <div class="KT_bottomnav">
                  <div>
                    <?php
            $nav_listIinvoice1->Prepare();
            require("includes/nav/NAV_Text_Navigation.inc.php");
          ?>
                  </div>
                </div>
                <div class="KT_bottombuttons">
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

mysql_free_result($Recordset2);

mysql_free_result($rsInvoice1);
?>