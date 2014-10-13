<?php require_once('../Connections/magazinducoin.php'); ?>
<?php
// Load the common classes
require_once('../includes/common/KT_common.php');

// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');

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
$tfi_listjournal_export1 = new TFI_TableFilter($conn_magazinducoin, "tfi_listjournal_export1");
$tfi_listjournal_export1->addColumn("journal_export.mois", "STRING_TYPE", "mois", "%");
$tfi_listjournal_export1->addColumn("region.id_region", "NUMERIC_TYPE", "region", "=");
$tfi_listjournal_export1->addColumn("departement.nom_departement", "STRING_TYPE", "departement", "%");
$tfi_listjournal_export1->addColumn("journal_export.active", "CHECKBOX_1_0_TYPE", "active", "%");
$tfi_listjournal_export1->Execute();

// Sorter
$tso_listjournal_export1 = new TSO_TableSorter("rsjournal_export1", "tso_listjournal_export1");
$tso_listjournal_export1->addColumn("journal_export.mois");
$tso_listjournal_export1->addColumn("region.nom_region");
$tso_listjournal_export1->addColumn("departement.nom_departement");
$tso_listjournal_export1->addColumn("journal_export.active");
$tso_listjournal_export1->setDefault("journal_export.mois");
$tso_listjournal_export1->Execute();

// Navigation
$nav_listjournal_export1 = new NAV_Regular("nav_listjournal_export1", "rsjournal_export1", "../", $_SERVER['PHP_SELF'], 20);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset1 = "SELECT nom_region, id_region FROM region ORDER BY nom_region";
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset2 = "SELECT nom_departement, id_departement FROM departement ORDER BY nom_departement";
$Recordset2 = mysql_query($query_Recordset2, $magazinducoin) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

//NeXTenesio3 Special List Recordset
$maxRows_rsjournal_export1 = $_SESSION['max_rows_nav_listjournal_export1'];
$pageNum_rsjournal_export1 = 0;
if (isset($_GET['pageNum_rsjournal_export1'])) {
  $pageNum_rsjournal_export1 = $_GET['pageNum_rsjournal_export1'];
}
$startRow_rsjournal_export1 = $pageNum_rsjournal_export1 * $maxRows_rsjournal_export1;

// Defining List Recordset variable
$NXTFilter_rsjournal_export1 = "1=1";
if (isset($_SESSION['filter_tfi_listjournal_export1'])) {
  $NXTFilter_rsjournal_export1 = $_SESSION['filter_tfi_listjournal_export1'];
}
// Defining List Recordset variable
$NXTSort_rsjournal_export1 = "journal_export.mois";
if (isset($_SESSION['sorter_tso_listjournal_export1'])) {
  $NXTSort_rsjournal_export1 = $_SESSION['sorter_tso_listjournal_export1'];
}
mysql_select_db($database_magazinducoin, $magazinducoin);

$query_rsjournal_export1 = "SELECT journal_export.mois, region.nom_region AS region, departement.nom_departement, journal_export.active, journal_export.id_journal FROM (journal_export LEFT JOIN region ON journal_export.region = region.id_region) LEFT JOIN departement ON journal_export.departement = departement.id_departement WHERE {$NXTFilter_rsjournal_export1} ORDER BY {$NXTSort_rsjournal_export1}";
$query_limit_rsjournal_export1 = sprintf("%s LIMIT %d, %d", $query_rsjournal_export1, $startRow_rsjournal_export1, $maxRows_rsjournal_export1);
$rsjournal_export1 = mysql_query($query_limit_rsjournal_export1, $magazinducoin) or die(mysql_error());
$row_rsjournal_export1 = mysql_fetch_assoc($rsjournal_export1);

if (isset($_GET['totalRows_rsjournal_export1'])) {
  $totalRows_rsjournal_export1 = $_GET['totalRows_rsjournal_export1'];
} else {
  $all_rsjournal_export1 = mysql_query($query_rsjournal_export1);
  $totalRows_rsjournal_export1 = mysql_num_rows($all_rsjournal_export1);
}
$totalPages_rsjournal_export1 = ceil($totalRows_rsjournal_export1/$maxRows_rsjournal_export1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listjournal_export1->checkBoundries();
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
  .KT_col_mois {width:77px; overflow:hidden;}
  .KT_col_region {width:140px; overflow:hidden;}
  .KT_col_departement {width:140px; overflow:hidden;}
  .KT_col_active {width:140px; overflow:hidden;}
        </style>
</head>
<body id="sp">
<?php include("modules/header.php"); ?>

<div class="content_wrapper_sbr">
	<div>
  		<div id="content">
  		  <h2>Liste des journeaux</h2>
  		  
      
                    <div class="KT_tng" id="listjournal_export1">
            <h1> Journal_export
              <?php
  $nav_listjournal_export1->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
            </h1>
            <div class="KT_tnglist">
              <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
                <div class="KT_options"> <a href="<?php echo $nav_listjournal_export1->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
                  <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listjournal_export1'] == 1) {
?>
                    <?php echo $_SESSION['default_max_rows_nav_listjournal_export1']; ?>
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
  if (@$_SESSION['has_filter_tfi_listjournal_export1'] == 1) {
?>
                  <a href="<?php echo $tfi_listjournal_export1->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
                  <?php 
  // else Conditional region2
  } else { ?>
                  <a href="<?php echo $tfi_listjournal_export1->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
                  <?php } 
  // endif Conditional region2
?>
                </div>
                <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                  <thead>
                    <tr class="KT_row_order">
                      <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
                      </th>
                      <th id="mois" class="KT_sorter KT_col_mois <?php echo $tso_listjournal_export1->getSortIcon('journal_export.mois'); ?>"> <a href="<?php echo $tso_listjournal_export1->getSortLink('journal_export.mois'); ?>">Mois</a> </th>
                      <th id="region" class="KT_sorter KT_col_region <?php echo $tso_listjournal_export1->getSortIcon('region.nom_region'); ?>"> <a href="<?php echo $tso_listjournal_export1->getSortLink('region.nom_region'); ?>">Région</a> </th>
                      <th id="departement" class="KT_sorter KT_col_departement <?php echo $tso_listjournal_export1->getSortIcon('departement.nom_departement'); ?>"> <a href="<?php echo $tso_listjournal_export1->getSortLink('departement.nom_departement'); ?>">Departement</a> </th>
                      <th id="active" class="KT_sorter KT_col_active <?php echo $tso_listjournal_export1->getSortIcon('journal_export.active'); ?>"> <a href="<?php echo $tso_listjournal_export1->getSortLink('journal_export.active'); ?>">Active</a> </th>
                      <th>&nbsp;</th>
                    </tr>
                    <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listjournal_export1'] == 1) {
?>
                      <tr class="KT_row_filter">
                        <td>&nbsp;</td>
                        <td><input type="text" name="tfi_listjournal_export1_mois" id="tfi_listjournal_export1_mois" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listjournal_export1_mois']); ?>" size="11" maxlength="11" /></td>
                        <td><select name="tfi_listjournal_export1_region" id="tfi_listjournal_export1_region">
                            <option value="" <?php if (!(strcmp("", @$_SESSION['tfi_listjournal_export1_region']))) {echo "SELECTED";} ?>><?php echo NXT_getResource("None"); ?></option>
                            <?php
do {  
?>
                            <option value="<?php echo $row_Recordset1['id_region']?>"<?php if (!(strcmp($row_Recordset1['id_region'], @$_SESSION['tfi_listjournal_export1_region']))) {echo "SELECTED";} ?>><?php echo $row_Recordset1['nom_region']?></option>
                            <?php
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
  $rows = mysql_num_rows($Recordset1);
  if($rows > 0) {
      mysql_data_seek($Recordset1, 0);
	  $row_Recordset1 = mysql_fetch_assoc($Recordset1);
  }
?>
                          </select>
                        </td>
                        <td>&nbsp;</td>
                        <td><input  <?php if (!(strcmp(KT_escapeAttribute(@$_SESSION['tfi_listjournal_export1_active']),"1"))) {echo "checked";} ?> type="checkbox" name="tfi_listjournal_export1_active" id="tfi_listjournal_export1_active" value="1" /></td>
                        <td><input type="submit" name="tfi_listjournal_export1" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
                      </tr>
                      <?php } 
  // endif Conditional region3
?>
                  </thead>
                  <tbody>
                    <?php if ($totalRows_rsjournal_export1 == 0) { // Show if recordset empty ?>
                      <tr>
                        <td colspan="6"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
                      </tr>
                      <?php } // Show if recordset empty ?>
                    <?php if ($totalRows_rsjournal_export1 > 0) { // Show if recordset not empty ?>
                      <?php do { ?>
                        <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                          <td><input type="checkbox" name="kt_pk_journal_export" class="id_checkbox" value="<?php echo $row_rsjournal_export1['id_journal']; ?>" />
                              <input type="hidden" name="id_journal" class="id_field" value="<?php echo $row_rsjournal_export1['id_journal']; ?>" />
                          </td>
                          <td><div class="KT_col_mois"><?php echo KT_FormatForList($row_rsjournal_export1['mois'], 11); ?></div></td>
                          <td><div class="KT_col_region"><?php echo KT_FormatForList($row_rsjournal_export1['region'], 20); ?></div></td>
                          <td><div class="KT_col_departement"><?php echo KT_FormatForList($row_rsjournal_export1['nom_departement'], 20); ?></div></td>
                          <td><div class="KT_col_active"><?php echo $row_rsjournal_export1['active']==1?"Oui":"Non"; ?></div></td>
                          <td><a class="KT_edit_link" href="export-journal.php?id_journal=<?php echo $row_rsjournal_export1['id_journal']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a> </td>
                        </tr>
                        <?php } while ($row_rsjournal_export1 = mysql_fetch_assoc($rsjournal_export1)); ?>
                      <?php } // Show if recordset not empty ?>
                  </tbody>
                </table>
                <div class="KT_bottomnav">
                  <div>
                    <?php
            $nav_listjournal_export1->Prepare();
            require("../includes/nav/NAV_Text_Navigation.inc.php");
          ?>
                  </div>
                </div>
                <div class="KT_bottombuttons">
                  <div class="KT_operations"> <a class="KT_edit_op_link" href="exporter-tous.php" onclick="window.location='exporter-tous.php';">Exporter les journaux de ce mois<?php //echo NXT_getResource("edit_all"); ?></a> <a class="KT_delete_op_link" href="#" onclick="nxt_list_delete_link_form(this); return false;"><?php echo NXT_getResource("delete_all"); ?></a> </div>
<span>&nbsp;</span>
                  </div>
              </form>
            </div>
            <br class="clearfixplain" />
          </div>
          <p>&nbsp;</p>
	  </div>
	</div>
</div>
<?php include("modules/footer.php"); ?>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);

mysql_free_result($rsjournal_export1);
?>