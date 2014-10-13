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
$tfi_listcategories1 = new TFI_TableFilter($conn_magazinducoin, "tfi_listcategories1");
$tfi_listcategories1->addColumn("categories.titre_categorie", "STRING_TYPE", "titre_categorie", "%");
$tfi_listcategories1->addColumn("categories.valide", "NUMERIC_TYPE", "valide", "=");
$tfi_listcategories1->Execute();

// Sorter
$tso_listcategories1 = new TSO_TableSorter("rscategories1", "tso_listcategories1");
$tso_listcategories1->addColumn("categories.titre_categorie");
$tso_listcategories1->addColumn("categories.valide");
$tso_listcategories1->setDefault("categories.titre_categorie");
$tso_listcategories1->Execute();

// Navigation
$nav_listcategories1 = new NAV_Regular("nav_listcategories1", "rscategories1", "../", $_SERVER['PHP_SELF'], 10);

//NeXTenesio3 Special List Recordset
$maxRows_rscategories1 = $_SESSION['max_rows_nav_listcategories1'];
$pageNum_rscategories1 = 0;
if (isset($_GET['pageNum_rscategories1'])) {
  $pageNum_rscategories1 = $_GET['pageNum_rscategories1'];
}
$startRow_rscategories1 = $pageNum_rscategories1 * $maxRows_rscategories1;

// Defining List Recordset variable
$NXTFilter_rscategories1 = "1=1";
if (isset($_SESSION['filter_tfi_listcategories1'])) {
  $NXTFilter_rscategories1 = $_SESSION['filter_tfi_listcategories1'];
}
// Defining List Recordset variable
$NXTSort_rscategories1 = "categories.titre_categorie";
if (isset($_SESSION['sorter_tso_listcategories1'])) {
  $NXTSort_rscategories1 = $_SESSION['sorter_tso_listcategories1'];
}
mysql_select_db($database_magazinducoin, $magazinducoin);

$query_rscategories1 = "SELECT categories.titre_categorie, categories.valide, categories.id_categorie FROM categories WHERE {$NXTFilter_rscategories1} ORDER BY {$NXTSort_rscategories1}";
$query_limit_rscategories1 = sprintf("%s LIMIT %d, %d", $query_rscategories1, $startRow_rscategories1, $maxRows_rscategories1);
$rscategories1 = mysql_query($query_limit_rscategories1, $magazinducoin) or die(mysql_error());
$row_rscategories1 = mysql_fetch_assoc($rscategories1);

if (isset($_GET['totalRows_rscategories1'])) {
  $totalRows_rscategories1 = $_GET['totalRows_rscategories1'];
} else {
  $all_rscategories1 = mysql_query($query_rscategories1);
  $totalRows_rscategories1 = mysql_num_rows($all_rscategories1);
}
$totalPages_rscategories1 = ceil($totalRows_rscategories1/$maxRows_rscategories1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listcategories1->checkBoundries();
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 
	<title>Magazin Du Coin | </title>
    	<style type="text/css">
		@import url(../stylesheets/custom-bg.css);			/*link to CSS file where to change backgrounds of site headers */
		@import url(../stylesheets/styles-light.css);		/*link to the main CSS file for light theme color */
		@import url(../stylesheets/widgets-light.css);		/*link to the CSS file for widgets of light theme color */
		@import url(../stylesheets/superfish.css);			/*link to the CSS file for superfish menu */
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
  duplicate_buttons: true,
  duplicate_navigation: true,
  row_effects: true,
  show_as_buttons: true,
  record_counter: true
}
        </script>
    	<style type="text/css">
  /* Dynamic List row settings */
  .KT_col_titre_categorie {width:140px; overflow:hidden;}
  .KT_col_valide {width:140px; overflow:hidden;}
        </style>
</head>
<body id="sp">
<?php include("modules/header.php"); ?>

<div class="content_wrapper_sbr">
	<div>
  		<div id="content">
  		  <h2>Gestion des catégories</h2>
  		  <p>

  		  <div class="KT_tng" id="listcategories1">
            <h1> Categories
              <?php
  $nav_listcategories1->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
            </h1>
  		    <div class="KT_tnglist">
              <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
                <div class="KT_options"> <a href="<?php echo $nav_listcategories1->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
                  <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listcategories1'] == 1) {
?>
                    <?php echo $_SESSION['default_max_rows_nav_listcategories1']; ?>
                    <?php 
  // else Conditional region1
  } else { ?>
                    <?php echo NXT_getResource("all"); ?>
                    <?php } 
  // endif Conditional region1
?>
                      <?php echo NXT_getResource("records"); ?></a> &nbsp;
                  &nbsp;
                <?php 
  // Show IF Conditional region2
  if (@$_SESSION['has_filter_tfi_listcategories1'] == 1) {
?>
                  <a href="<?php echo $tfi_listcategories1->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
                  <?php 
  // else Conditional region2
  } else { ?>
                  <a href="<?php echo $tfi_listcategories1->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
                  <?php } 
  // endif Conditional region2
?>
                </div>
                <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                  <thead>
                    <tr class="KT_row_order">
                      <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
                      </th>
                      <th id="titre_categorie" class="KT_sorter KT_col_titre_categorie <?php echo $tso_listcategories1->getSortIcon('categories.titre_categorie'); ?>"> <a href="<?php echo $tso_listcategories1->getSortLink('categories.titre_categorie'); ?>">Titre_categorie</a> </th>
                      <th id="valide" class="KT_sorter KT_col_valide <?php echo $tso_listcategories1->getSortIcon('categories.valide'); ?>"> <a href="<?php echo $tso_listcategories1->getSortLink('categories.valide'); ?>">Valide</a> </th>
                      <th>&nbsp;</th>
                    </tr>
                    <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listcategories1'] == 1) {
?>
                      <tr class="KT_row_filter">
                        <td>&nbsp;</td>
                        <td><input type="text" name="tfi_listcategories1_titre_categorie" id="tfi_listcategories1_titre_categorie" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listcategories1_titre_categorie']); ?>" size="20" maxlength="100" /></td>
                        <td><input type="text" name="tfi_listcategories1_valide" id="tfi_listcategories1_valide" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listcategories1_valide']); ?>" size="20" maxlength="100" /></td>
                        <td><input type="submit" name="tfi_listcategories1" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
                      </tr>
                      <?php } 
  // endif Conditional region3
?>
                  </thead>
                  <tbody>
                    <?php if ($totalRows_rscategories1 == 0) { // Show if recordset empty ?>
                      <tr>
                        <td colspan="4"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
                      </tr>
                      <?php } // Show if recordset empty ?>
                    <?php if ($totalRows_rscategories1 > 0) { // Show if recordset not empty ?>
                      <?php do { ?>
                        <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                          <td><input type="checkbox" name="kt_pk_categories" class="id_checkbox" value="<?php echo $row_rscategories1['id_categorie']; ?>" />
                              <input type="hidden" name="id_categorie" class="id_field" value="<?php echo $row_rscategories1['id_categorie']; ?>" />
                          </td>
                          <td><div class="KT_col_titre_categorie"><?php echo KT_FormatForList($row_rscategories1['titre_categorie'], 20); ?></div></td>
                          <td><div class="KT_col_valide"><?php echo KT_FormatForList($row_rscategories1['valide'], 20); ?></div></td>
                          <td><a class="KT_edit_link" href="formCat.php?id_categorie=<?php echo $row_rscategories1['id_categorie']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a> </td>
                        </tr>
                        <?php } while ($row_rscategories1 = mysql_fetch_assoc($rscategories1)); ?>
                      <?php } // Show if recordset not empty ?>
                  </tbody>
                </table>
                <div class="KT_bottomnav">
                  <div>
                    <?php
            $nav_listcategories1->Prepare();
            require("../includes/nav/NAV_Text_Navigation.inc.php");
          ?>
                  </div>
                </div>
                <div class="KT_bottombuttons">
                  <div class="KT_operations"> <a class="KT_edit_op_link" href="#" onclick="nxt_list_edit_link_form(this); return false;"><?php echo NXT_getResource("edit_all"); ?></a> <a class="KT_delete_op_link" href="#" onclick="nxt_list_delete_link_form(this); return false;"><?php echo NXT_getResource("delete_all"); ?></a> </div>
<span>&nbsp;</span>
                  <select name="no_new" id="no_new" style="width:40px">
                    <option value="1">1</option>
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                  </select>
                  <a class="KT_additem_op_link" href="formCat.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a> </div>
              </form>
	        </div>
  		    <br class="clearfixplain" />
          </div>
  		  <p>&nbsp;</p>
  		  </p>
</div>
	</div>
</div>
<?php include("modules/footer.php"); ?>
</body>
</html>
<?php
mysql_free_result($rscategories1);
?>