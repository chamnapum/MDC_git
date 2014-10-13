<?php require_once('../Connections/magazinducoin.php'); ?>
<?php
// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');

// Load the common classes
require_once('../includes/common/KT_common.php');
require_once 'include/XMLEngine.php';

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
$tfi_listcode_promo1 = new TFI_TableFilter($conn_magazinducoin, "tfi_listcode_promo1");
$tfi_listcode_promo1->addColumn("code_promo.code", "STRING_TYPE", "code", "%");
$tfi_listcode_promo1->addColumn("code_promo.reduction", "NUMERIC_TYPE", "reduction", "=");
$tfi_listcode_promo1->addColumn("code_promo.valide", "NUMERIC_TYPE", "valide", "=");
$tfi_listcode_promo1->Execute();

// Sorter
$tso_listcode_promo1 = new TSO_TableSorter("rscode_promo1", "tso_listcode_promo1");
$tso_listcode_promo1->addColumn("code_promo.code");
$tso_listcode_promo1->addColumn("code_promo.valide");
$tso_listcode_promo1->addColumn("code_promo.reduction");
$tso_listcode_promo1->setDefault("code_promo.code");
$tso_listcode_promo1->Execute();

// Navigation
$nav_listcode_promo1 = new NAV_Regular("nav_listcode_promo1", "rscode_promo1", "../", $_SERVER['PHP_SELF'], 20);

//NeXTenesio3 Special List Recordset
$maxRows_rscode_promo1 = $_SESSION['max_rows_nav_listcode_promo1'];
$pageNum_rscode_promo1 = 0;
if (isset($_GET['pageNum_rscode_promo1'])) {
  $pageNum_rscode_promo1 = $_GET['pageNum_rscode_promo1'];
}
$startRow_rscode_promo1 = $pageNum_rscode_promo1 * $maxRows_rscode_promo1;

// Defining List Recordset variable
$NXTFilter_rscode_promo1 = "1=1";
if (isset($_SESSION['filter_tfi_listcode_promo1'])) {
  $NXTFilter_rscode_promo1 = $_SESSION['filter_tfi_listcode_promo1'];
}
// Defining List Recordset variable
$NXTSort_rscode_promo1 = "code_promo.code";
if (isset($_SESSION['sorter_tso_listcode_promo1'])) {
  $NXTSort_rscode_promo1 = $_SESSION['sorter_tso_listcode_promo1'];
}
mysql_select_db($database_magazinducoin, $magazinducoin);

$query_rscode_promo1 = "SELECT code_promo.code, code_promo.valide, code_promo.id, code_promo.reduction FROM code_promo WHERE {$NXTFilter_rscode_promo1} ORDER BY {$NXTSort_rscode_promo1}";
$query_limit_rscode_promo1 = sprintf("%s LIMIT %d, %d", $query_rscode_promo1, $startRow_rscode_promo1, $maxRows_rscode_promo1);
$rscode_promo1 = mysql_query($query_limit_rscode_promo1, $magazinducoin) or die(mysql_error());
$row_rscode_promo1 = mysql_fetch_assoc($rscode_promo1);

if (isset($_GET['totalRows_rscode_promo1'])) {
  $totalRows_rscode_promo1 = $_GET['totalRows_rscode_promo1'];
} else {
  $all_rscode_promo1 = mysql_query($query_rscode_promo1);
  $totalRows_rscode_promo1 = mysql_num_rows($all_rscode_promo1);
}
$totalPages_rscode_promo1 = ceil($totalRows_rscode_promo1/$maxRows_rscode_promo1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listcode_promo1->checkBoundries();
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Magazin Du Coin | </title>
    	<style type="text/css">
		@import url(../stylesheets/custom-bg.css);			/*link to CSS file where to change backgrounds of site headers */
		@import url(../stylesheets/styles-light.css);		/*link to the main CSS file for light theme color */
		@import url(../stylesheets/widgets-light.css);		/*link to the CSS file for widgets of light theme color */
		@import url(../stylesheets/superfish-admin.css);			/*link to the CSS file for superfish menu */
		@import url(../stylesheets/tipsy.css);				/*link to the CSS file for tips */
		@import url(../stylesheets/contact.css);				/*link to the CSS file for tips */
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
  .KT_col_code {width:140px; overflow:hidden;}
  .KT_col_valide {width:140px; overflow:hidden;}
        </style>
</head>
<body id="sp">
<?php include("modules/header.php"); ?>

<div class="content_wrapper_sbr">
	<div id="content">
      <div class="KT_tng" id="listcode_promo1">
        <h1> Codes promo
          <?php
  $nav_listcode_promo1->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
        </h1>
        <div class="KT_tnglist">
          <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
            <div class="KT_options"> <a href="<?php echo $nav_listcode_promo1->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
              <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listcode_promo1'] == 1) {
?>
                <?php echo $_SESSION['default_max_rows_nav_listcode_promo1']; ?>
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
                  <th id="code" class="KT_sorter KT_col_code <?php echo $tso_listcode_promo1->getSortIcon('code_promo.code'); ?>"> <a href="<?php echo $tso_listcode_promo1->getSortLink('code_promo.code'); ?>">Code</a> </th>
                  <th id="reduction" class="KT_sorter KT_col_code <?php echo $tso_listcode_promo1->getSortIcon('code_promo.reduction'); ?>"> <a href="<?php echo $tso_listcode_promo1->getSortLink('code_promo.reduction'); ?>">Reduction</a> </th>
                  <th id="valide" class="KT_sorter KT_col_valide <?php echo $tso_listcode_promo1->getSortIcon('code_promo.valide'); ?>"> <a href="<?php echo $tso_listcode_promo1->getSortLink('code_promo.valide'); ?>">Valide</a> </th>
                  <th>&nbsp;</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($totalRows_rscode_promo1 == 0) { // Show if recordset empty ?>
                  <tr>
                    <td colspan="4"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
                  </tr>
                  <?php } // Show if recordset empty ?>
                <?php if ($totalRows_rscode_promo1 > 0) { // Show if recordset not empty ?>
                  <?php do { ?>
                    <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                      <td><input type="checkbox" name="kt_pk_code_promo" class="id_checkbox" value="<?php echo $row_rscode_promo1['id']; ?>" />
                          <input type="hidden" name="id" class="id_field" value="<?php echo $row_rscode_promo1['id']; ?>" />
                      </td>
                      <td><div class="KT_col_code"><?php echo KT_FormatForList($row_rscode_promo1['code'], 20); ?></div></td>
                      <td><div class="KT_col_code"><?php echo KT_FormatForList($row_rscode_promo1['reduction'], 20); ?> %</div></td>
                      <td><div class="KT_col_valide"><?php echo $row_rscode_promo1['valide']==1?"Oui":"Non"; ?></div></td>
                      <td><a class="KT_edit_link" href="../admin/formCodePromo.php?id=<?php echo $row_rscode_promo1['id']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a> </td>
                    </tr>
                    <?php } while ($row_rscode_promo1 = mysql_fetch_assoc($rscode_promo1)); ?>
                  <?php } // Show if recordset not empty ?>
              </tbody>
            </table>
            <div class="KT_bottomnav">
              <div>
                <?php
            $nav_listcode_promo1->Prepare();
            require("../includes/nav/NAV_Text_Navigation.inc.php");
          ?>
              </div>
            </div>
            <div class="KT_bottombuttons">
              <div class="KT_operations"> <a class="KT_edit_op_link" href="#" onclick="nxt_list_edit_link_form(this); return false;"><?php echo NXT_getResource("edit_all"); ?></a> <a class="KT_delete_op_link" href="#" onclick="nxt_list_delete_link_form(this); return false;"><?php echo NXT_getResource("delete_all"); ?></a> </div>
<span>&nbsp;</span>
              <select name="no_new" id="no_new">
                <option value="1">1</option>
                <option value="3">3</option>
                <option value="6">6</option>
              </select>
              <a class="KT_additem_op_link" href="../admin/formCodePromo.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a> </div>
          </form>
        </div>
        <br class="clearfixplain" />
      </div>
      <p>&nbsp;</p>
	</div>
</div>
</body>
</html>
<?php
mysql_free_result($rscode_promo1);
?>
