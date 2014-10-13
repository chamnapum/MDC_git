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
$tfi_listinvoice3 = new TFI_TableFilter($conn_magazinducoin, "tfi_listinvoice3");
$tfi_listinvoice3->addColumn("invoice.orber_num", "STRING_TYPE", "orber_num", "%");
$tfi_listinvoice3->addColumn("invoice.invoice_id", "STRING_TYPE", "invoice_id", "%");
$tfi_listinvoice3->addColumn("invoice.amount", "STRING_TYPE", "amount", "%");
$tfi_listinvoice3->addColumn("invoice.type_payment", "STRING_TYPE", "type_payment", "%");
$tfi_listinvoice3->addColumn("invoice.date_payment", "STRING_TYPE", "date_payment", "%");
$tfi_listinvoice3->Execute();

// Sorter
$tso_listinvoice3 = new TSO_TableSorter("rsinvoice1", "tso_listinvoice3");
$tso_listinvoice3->addColumn("invoice.orber_num");
$tso_listinvoice3->addColumn("invoice.invoice_id");
$tso_listinvoice3->addColumn("invoice.amount");
$tso_listinvoice3->addColumn("invoice.type_payment");
$tso_listinvoice3->addColumn("invoice.date_payment");
$tso_listinvoice3->setDefault("invoice.invoice_id DESC");
$tso_listinvoice3->Execute();

// Navigation
$nav_listinvoice3 = new NAV_Regular("nav_listinvoice3", "rsinvoice1", "../", $_SERVER['PHP_SELF'], 20);

//NeXTenesio3 Special List Recordset
$maxRows_rsinvoice1 = $_SESSION['max_rows_nav_listinvoice3'];
$pageNum_rsinvoice1 = 0;
if (isset($_GET['pageNum_rsinvoice1'])) {
  $pageNum_rsinvoice1 = $_GET['pageNum_rsinvoice1'];
}
$startRow_rsinvoice1 = $pageNum_rsinvoice1 * $maxRows_rsinvoice1;

// Defining List Recordset variable
$NXTFilter_rsinvoice1 = "1=1";
if (isset($_SESSION['filter_tfi_listinvoice3'])) {
  $NXTFilter_rsinvoice1 = $_SESSION['filter_tfi_listinvoice3'];
}
// Defining List Recordset variable
$NXTSort_rsinvoice1 = "invoice.invoice_id DESC";
if (isset($_SESSION['sorter_tso_listinvoice3'])) {
  $NXTSort_rsinvoice1 = $_SESSION['sorter_tso_listinvoice3'];
}
mysql_select_db($database_magazinducoin, $magazinducoin);

$query_rsinvoice1 = "SELECT 
  invoice.*,
  utilisateur.email,
  utilisateur.telephone 
FROM
  invoice 
  INNER JOIN utilisateur 
    ON (invoice.id_user = utilisateur.id) 
WHERE {$NXTFilter_rsinvoice1} ORDER BY {$NXTSort_rsinvoice1}";
$query_limit_rsinvoice1 = sprintf("%s LIMIT %d, %d", $query_rsinvoice1, $startRow_rsinvoice1, $maxRows_rsinvoice1);
$rsinvoice1 = mysql_query($query_limit_rsinvoice1, $magazinducoin) or die(mysql_error());
$row_rsinvoice1 = mysql_fetch_assoc($rsinvoice1);
//echo $query_limit_rsinvoice1;

if (isset($_GET['totalRows_rsinvoice1'])) {
  $totalRows_rsinvoice1 = $_GET['totalRows_rsinvoice1'];
} else {
  $all_rsinvoice1 = mysql_query($query_rsinvoice1);
  $totalRows_rsinvoice1 = mysql_num_rows($all_rsinvoice1);
}
$totalPages_rsinvoice1 = ceil($totalRows_rsinvoice1/$maxRows_rsinvoice1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listinvoice3->checkBoundries();
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
          <div class="KT_tng" id="listinvoice3">
            <h1> Paiement
              <?php
  $nav_listinvoice3->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
            </h1>
            <div class="KT_tnglist">
            	<?php if($_GET['info']=='ACTIVATED') { ?><div class="succes">approve!!</div><?php } ?>
				<?php if($_GET['info']=='UNACTIVATED') { ?><div class="unsucces">no approve!!</div><?php } ?>
              <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
                <div class="KT_options"> <a href="<?php echo $nav_listinvoice3->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
                  <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listinvoice3'] == 1) {
?>
                    <?php echo $_SESSION['default_max_rows_nav_listinvoice3']; ?>
                    <?php 
  // else Conditional region1
  } else { ?>
                    <?php echo NXT_getResource("all"); ?>
                    <?php } 
  // endif Conditional region1
?>
                      <?php echo NXT_getResource("records"); ?></a> &nbsp;

                </div>
                <table cellpadding="2" cellspacing="0" class="KT_tngtable" style="width:1000px;">
                  <thead>
                    <tr class="KT_row_order">
                      <!--<th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
                      </th>-->
                      <th class="KT_sorter  <?php echo $tso_listinvoice3->getSortIcon('invoice.invoice_id'); ?>"> <a href="<?php echo $tso_listinvoice3->getSortLink('invoice.invoice_id'); ?>">Facture n°</a> </th>
                      <th class="KT_sorter  <?php echo $tso_listinvoice3->getSortIcon('invoice.orber_num'); ?>"> <a href="<?php echo $tso_listinvoice3->getSortLink('invoice.orber_num'); ?>">Commande n° </a> </th>
                      <th class="KT_sorter  <?php echo $tso_listinvoice3->getSortIcon('invoice.amount'); ?>"> <a href="<?php echo $tso_listinvoice3->getSortLink('invoice.amount'); ?>">Montant TTC </a> </th>
                      <th class="KT_sorter  <?php echo $tso_listinvoice3->getSortIcon('invoice.type_payment'); ?>"> <a href="<?php echo $tso_listinvoice3->getSortLink('invoice.type_payment'); ?>">Mode de paiement</a> </th>
                      <th class="KT_sorter  <?php echo $tso_listinvoice3->getSortIcon('invoice.date_payment'); ?>"> <a href="<?php echo $tso_listinvoice3->getSortLink('invoice.date_payment'); ?>">Payée le</a> </th>
                      <th class="KT_sorter "> Type </th>
                      <th class="KT_sorter "> Email </th>
                      <th class="KT_sorter "> Téléphone </th>
                      <th class="KT_sorter "> Magasin </th>
                      <th class="KT_sorter "> Produit </th>
                      <th class="KT_sorter "> Coupon </th>
                      <th class="KT_sorter "> Événement </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if ($totalRows_rsinvoice1 == 0) { // Show if recordset empty ?>
                      <tr>
                        <td colspan="12"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
                      </tr>
                      <?php } // Show if recordset empty ?>
                    <?php if ($totalRows_rsinvoice1 > 0) { // Show if recordset not empty ?>
                      <?php do { ?>
                        <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                          <td style="white-space: normal;"><div class="KT_col_nom"><?php echo ($row_rsinvoice1['invoice_id']); ?></div></td>
                          <td style="white-space: normal;"><div class="KT_col_email"><?php echo $row_rsinvoice1['orber_num']; ?></div></td>
                          <td style="white-space: normal;"><div class="KT_col_telephone"><?php echo $row_rsinvoice1['amount']; ?> &euro;</div></td>
                          <td style="white-space: normal;"><div class="KT_col_siren">
						  	<?php 
						  		if($row_rsinvoice1['type_payment']=='1')
								echo'PayPal';
								else
								echo'Crédit'; 
							?>
                          </div></td>
                          <td style="white-space: normal;"><div class="KT_col_nom_magazin"><?php echo $row_rsinvoice1['date_payment']; ?></div></td>
                          <td style="white-space: normal;"><div class="KT_col_siren">
						  	<?php 
						  		if($row_rsinvoice1['payon']=='1')
								echo'Magazin';
								elseif($row_rsinvoice1['payon']=='2')
								echo'Produit'; 
								elseif($row_rsinvoice1['payon']=='3')
								echo'Coupon'; 
								elseif($row_rsinvoice1['payon']=='4')
								echo'Événement'; 
							?>
                          </div></td>
                          
                          <td style="white-space: normal;"><div class="KT_col_nom_magazin"><?php echo $row_rsinvoice1['email']; ?></div></td>
                          <td style="white-space: normal;"><div class="KT_col_nom_magazin"><?php echo $row_rsinvoice1['telephone']; ?></div></td>
                          <td style="white-space: normal;"><div class="KT_col_nom_magazin">
						  		<?php 
									if(isset($row_rsinvoice1['id_magazin'])){
										mysql_select_db($database_magazinducoin, $magazinducoin);
										$query_Recordset1 = "SELECT nom_magazin FROM magazins WHERE id_magazin='".$row_rsinvoice1['id_magazin']."' ORDER BY nom_magazin";
										$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
										$row_Recordset1 = mysql_fetch_assoc($Recordset1);
										echo $row_Recordset1['nom_magazin'];
									} 
								?>
                          </div></td>
                          <td style="white-space: normal;"><div class="KT_col_nom_magazin">
						  		<?php 
									if(isset($row_rsinvoice1['id_produit'])){
										mysql_select_db($database_magazinducoin, $magazinducoin);
										$query_Recordset1 = "SELECT titre FROM produits WHERE id='".$row_rsinvoice1['id_produit']."' ";
										$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
										$row_Recordset1 = mysql_fetch_assoc($Recordset1);
										echo $row_Recordset1['titre'];
									} 
								?>
                          </div></td>
                          <td style="white-space: normal;"><div class="KT_col_nom_magazin">
						  		<?php 
									if(isset($row_rsinvoice1['id_coupon'])){
										mysql_select_db($database_magazinducoin, $magazinducoin);
										$query_Recordset1 = "SELECT titre FROM coupons WHERE id_coupon='".$row_rsinvoice1['id_coupon']."' ";
										$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
										$row_Recordset1 = mysql_fetch_assoc($Recordset1);
										echo $row_Recordset1['titre'];
									} 
								?>
                          </div></td>
                          <td style="white-space: normal;"><div class="KT_col_nom_magazin">
						  		<?php 
									if(isset($row_rsinvoice1['id_event'])){
										mysql_select_db($database_magazinducoin, $magazinducoin);
										$query_Recordset1 = "SELECT titre FROM evenements WHERE event_id='".$row_rsinvoice1['id_event']."' ";
										$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
										$row_Recordset1 = mysql_fetch_assoc($Recordset1);
										echo $row_Recordset1['titre'];
									} 
								?>
                          </div></td>
                        </tr>
                        <?php } while ($row_rsinvoice1 = mysql_fetch_assoc($rsinvoice1)); ?>
                      <?php } // Show if recordset not empty ?>
                  </tbody>
                </table>
                <div class="KT_bottomnav">
                  <div>
                    <?php
            $nav_listinvoice3->Prepare();
            require("../includes/nav/NAV_Text_Navigation.inc.php");
          ?>
                  </div>
                </div>
                <div class="KT_bottombuttons">
                	
                </div>
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

mysql_free_result($Recordset4);

mysql_free_result($rsinvoice1);
?>