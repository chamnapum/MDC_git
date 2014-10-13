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
$tfi_listabonement1 = new TFI_TableFilter($conn_magazinducoin, "tfi_listabonement1");
$tfi_listabonement1->addColumn("utilisateur.id", "NUMERIC_TYPE", "id_user", "=");
$tfi_listabonement1->addColumn("abonement.date_abonement", "DATE_TYPE", "date_abonement", "=");
$tfi_listabonement1->addColumn("abonement.date_echeance", "DATE_TYPE", "date_echeance", "=");
$tfi_listabonement1->Execute();

// Sorter
$tso_listabonement1 = new TSO_TableSorter("rsabonement1", "tso_listabonement1");
$tso_listabonement1->addColumn("utilisateur.email");
$tso_listabonement1->addColumn("abonement.date_abonement");
$tso_listabonement1->addColumn("abonement.date_echeance");
$tso_listabonement1->setDefault("abonement.id_user");
$tso_listabonement1->Execute();

// Navigation
$nav_listabonement1 = new NAV_Regular("nav_listabonement1", "rsabonement1", "../", $_SERVER['PHP_SELF'], 20);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset1 = "SELECT email, id FROM utilisateur ORDER BY email";
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

//NeXTenesio3 Special List Recordset
$maxRows_rsabonement1 = $_SESSION['max_rows_nav_listabonement1'];
$pageNum_rsabonement1 = 0;
if (isset($_GET['pageNum_rsabonement1'])) {
  $pageNum_rsabonement1 = $_GET['pageNum_rsabonement1'];
}
$startRow_rsabonement1 = $pageNum_rsabonement1 * $maxRows_rsabonement1;

// Defining List Recordset variable
$NXTFilter_rsabonement1 = "1=1";
if (isset($_SESSION['filter_tfi_listabonement1'])) {
  $NXTFilter_rsabonement1 = $_SESSION['filter_tfi_listabonement1'];
}
// Defining List Recordset variable
$NXTSort_rsabonement1 = "abonement.id_user";
if (isset($_SESSION['sorter_tso_listabonement1'])) {
  $NXTSort_rsabonement1 = $_SESSION['sorter_tso_listabonement1'];
}
mysql_select_db($database_magazinducoin, $magazinducoin);

$query_rsabonement1 = "SELECT utilisateur.email AS id_user, utilisateur.nom, utilisateur.prenom, abonement.* FROM abonement LEFT JOIN utilisateur ON abonement.id_user = utilisateur.id WHERE abonement.id_user = ".$_GET['id_user']." AND {$NXTFilter_rsabonement1} ORDER BY {$NXTSort_rsabonement1}";
$query_limit_rsabonement1 = sprintf("%s LIMIT %d, %d", $query_rsabonement1, $startRow_rsabonement1, $maxRows_rsabonement1);
$rsabonement1 = mysql_query($query_limit_rsabonement1, $magazinducoin) or die(mysql_error());
$row_rsabonement1 = mysql_fetch_assoc($rsabonement1);

if (isset($_GET['totalRows_rsabonement1'])) {
  $totalRows_rsabonement1 = $_GET['totalRows_rsabonement1'];
} else {
  $all_rsabonement1 = mysql_query($query_rsabonement1);
  $totalRows_rsabonement1 = mysql_num_rows($all_rsabonement1);
}
$totalPages_rsabonement1 = ceil($totalRows_rsabonement1/$maxRows_rsabonement1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listabonement1->checkBoundries();
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
    <script src="../includes/nxt/scripts/list.js" type="text/javascript"></script>
    <script src="../includes/nxt/scripts/list.js.php" type="text/javascript"></script>
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
  .KT_col_id_user {width:140px; overflow:hidden;}
  .KT_col_date_abonement {width:140px; overflow:hidden;}
  .KT_col_date_echeance {width:140px; overflow:hidden;}
    </style>
</head>
<body id="sp">
<?php include("modules/header.php"); ?>

<div class="content_wrapper_sbr">
	<div>
  		<div id="content">
          <div class="KT_tng" id="listabonement1">
            <h1><?php echo $row_rsabonement1['prenom']; ?> <?php echo $row_rsabonement1['nom']; ?> <small>(<?php echo $row_rsabonement1['id_user']; ?>)</small>
              <?php
  $nav_listabonement1->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
            </h1>
            <div class="KT_tnglist">
              <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
                <div class="KT_options"> <a href="<?php echo $nav_listabonement1->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
                  <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listabonement1'] == 1) {
?>
                    <?php echo $_SESSION['default_max_rows_nav_listabonement1']; ?>
                    <?php 
  // else Conditional region1
  } else { ?>
                    <?php echo NXT_getResource("all"); ?>
                    <?php } 
  // endif Conditional region1
?>
                      <?php echo NXT_getResource("records"); ?></a> &nbsp;
                &nbsp; </div>
                <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                  <thead>
                    <tr class="KT_row_order">
                      <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
                      </th>
                      <th id="date_abonement" class="KT_sorter KT_col_date_abonement <?php echo $tso_listabonement1->getSortIcon('abonement.date_abonement'); ?>"> <a href="<?php echo $tso_listabonement1->getSortLink('abonement.date_abonement'); ?>">Date d'abonement</a> </th>
                      <th id="date_echeance" class="KT_sorter KT_col_date_echeance <?php echo $tso_listabonement1->getSortIcon('abonement.date_echeance'); ?>"> <a href="<?php echo $tso_listabonement1->getSortLink('abonement.date_echeance'); ?>">Date d'écheance</a> </th>
                      <th>Mode de paiement</th>
                      <th id="mode_payement">Montant</th>
                      <th>Promo</th>
                      <th>Total</th>
                      <th>&nbsp;</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if ($totalRows_rsabonement1 == 0) { // Show if recordset empty ?>
                      <tr>
                        <td colspan="5"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
                      </tr>
                      <?php } // Show if recordset empty ?>
                    <?php if ($totalRows_rsabonement1 > 0) { // Show if recordset not empty ?>
                      <?php do { ?>
                        <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                          <td><input type="checkbox" name="kt_pk_abonement" class="id_checkbox" value="<?php echo $row_rsabonement1['id']; ?>" />
                              <input type="hidden" name="id" class="id_field" value="<?php echo $row_rsabonement1['id']; ?>" />
                          </td>
                          <td><div class="KT_col_date_abonement"><?php echo KT_formatDate($row_rsabonement1['date_abonement']); ?></div></td>
                          <td><div class="KT_col_date_echeance"><?php echo KT_formatDate($row_rsabonement1['date_echeance']); ?></div></td>
                           <td><div class="KT_col_date_echeance"><?php echo $row_rsabonement1['mode_payement']; ?></div></td>
                           <td><div class="KT_col_mode_payement"><?php echo $row_rsabonement1['montant']; ?> €</div></td>
                           <td><div class="KT_col_mode_payement"><?php echo $row_rsabonement1['code_promo']; ?> %</div></td>
                           <td><div class="KT_col_mode_payement"><?php echo $row_rsabonement1['credit_plus']; ?> €</div></td>
                          <td><a class="KT_edit_link" href="formCommandes.php?id=<?php echo $row_rsabonement1['id']; ?>&amp;KT_back=1">Voir les détails<?php //echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a> <?php if($row_rsabonement1['active'] == 0){ ?><a class="KT_edit_link" href="formCommandes.php?id=<?php echo $row_rsabonement1['id']; ?>&id_user=<?php echo $_GET['id_user']; ?>&activer=1">Activer la commande</a><?php } ?></td>
                        </tr>
                        <?php } while ($row_rsabonement1 = mysql_fetch_assoc($rsabonement1)); ?>
                      <?php } // Show if recordset not empty ?>
                  </tbody>
                </table>
                <div class="KT_bottomnav">
                  <div>
                    <?php
            $nav_listabonement1->Prepare();
            require("../includes/nav/NAV_Text_Navigation.inc.php");
          ?>
                  </div>
                </div>
                <div class="KT_bottombuttons">
<input type="hidden" name="no_new" value="1" />
                  <a class="KT_additem_op_link" href="formCommandes.php?KT_back=1&amp;id_user=<?php echo $_GET['id_user']; ?>" onclick="return nxt_list_additem(this)"><?php //echo NXT_getResource("add new"); ?>Nouvelle commande</a> </div>
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

mysql_free_result($rsabonement1);
?>