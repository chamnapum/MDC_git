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
$tfi_listpub1 = new TFI_TableFilter($conn_magazinducoin, "tfi_listpub1");
$tfi_listpub1->addColumn("utilisateur.id", "NUMERIC_TYPE", "id_user", "=");
$tfi_listpub1->addColumn("produits.id", "NUMERIC_TYPE", "id_produit", "=");
$tfi_listpub1->addColumn("pub.titre", "STRING_TYPE", "titre", "%");
$tfi_listpub1->addColumn("region.id_region", "NUMERIC_TYPE", "region", "=");
$tfi_listpub1->addColumn("pub.date_fin", "DATE_TYPE", "date_fin", "=");
$tfi_listpub1->Execute();

// Sorter
$tso_listpub1 = new TSO_TableSorter("rspub1", "tso_listpub1");
$tso_listpub1->addColumn("utilisateur.email");
$tso_listpub1->addColumn("produits.titre");
$tso_listpub1->addColumn("pub.titre");
$tso_listpub1->addColumn("region.nom_region");
$tso_listpub1->addColumn("pub.date_fin");
$tso_listpub1->setDefault("pub.id_user");
$tso_listpub1->Execute();

// Navigation
$nav_listpub1 = new NAV_Regular("nav_listpub1", "rspub1", "../", $_SERVER['PHP_SELF'], 10);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset1 = "SELECT email, id FROM utilisateur ORDER BY email";
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset2 = "SELECT titre, id FROM produits ORDER BY titre";
$Recordset2 = mysql_query($query_Recordset2, $magazinducoin) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset3 = "SELECT nom_region, id_region FROM region ORDER BY nom_region";
$Recordset3 = mysql_query($query_Recordset3, $magazinducoin) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

//NeXTenesio3 Special List Recordset
$maxRows_rspub1 = $_SESSION['max_rows_nav_listpub1'];
$pageNum_rspub1 = 0;
if (isset($_GET['pageNum_rspub1'])) {
  $pageNum_rspub1 = $_GET['pageNum_rspub1'];
}
$startRow_rspub1 = $pageNum_rspub1 * $maxRows_rspub1;

// Defining List Recordset variable
$NXTFilter_rspub1 = "1=1";
if (isset($_SESSION['filter_tfi_listpub1'])) {
  $NXTFilter_rspub1 = $_SESSION['filter_tfi_listpub1'];
}
// Defining List Recordset variable
$NXTSort_rspub1 = "pub.id_user";
if (isset($_SESSION['sorter_tso_listpub1'])) {
  $NXTSort_rspub1 = $_SESSION['sorter_tso_listpub1'];
}
mysql_select_db($database_magazinducoin, $magazinducoin);

$query_rspub1 = "SELECT utilisateur.email AS id_user, produits.titre AS id_produit, pub.titre, region.nom_region AS region, pub.date_fin, pub.id FROM ((pub LEFT JOIN utilisateur ON pub.id_user = utilisateur.id) LEFT JOIN produits ON pub.id_produit = produits.id) LEFT JOIN region ON pub.region = region.id_region WHERE {$NXTFilter_rspub1} ORDER BY {$NXTSort_rspub1}";
$query_limit_rspub1 = sprintf("%s LIMIT %d, %d", $query_rspub1, $startRow_rspub1, $maxRows_rspub1);
$rspub1 = mysql_query($query_limit_rspub1, $magazinducoin) or die(mysql_error());
$row_rspub1 = mysql_fetch_assoc($rspub1);

if (isset($_GET['totalRows_rspub1'])) {
  $totalRows_rspub1 = $_GET['totalRows_rspub1'];
} else {
  $all_rspub1 = mysql_query($query_rspub1);
  $totalRows_rspub1 = mysql_num_rows($all_rspub1);
}
$totalPages_rspub1 = ceil($totalRows_rspub1/$maxRows_rspub1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listpub1->checkBoundries();
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
  .KT_col_id_produit {width:140px; overflow:hidden;}
  .KT_col_titre {width:140px; overflow:hidden;}
  .KT_col_region {width:140px; overflow:hidden;}
  .KT_col_date_fin {width:140px; overflow:hidden;}
    </style>
</head>
<body id="sp">
<?php include("modules/header.php"); ?>

<div class="content_wrapper_sbr">
	<div>
  		<div id="content">
          <div class="KT_tng" id="listpub1">
            <h1> Liste de publicités
              <?php
  $nav_listpub1->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
            </h1>
            <div class="KT_tnglist">
              <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
                <div class="KT_options"> <a href="<?php echo $nav_listpub1->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
                  <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listpub1'] == 1) {
?>
                    <?php echo $_SESSION['default_max_rows_nav_listpub1']; ?>
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
  if (@$_SESSION['has_filter_tfi_listpub1'] == 1) {
?>
                  <a href="<?php echo $tfi_listpub1->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
                  <?php 
  // else Conditional region2
  } else { ?>
                  <a href="<?php echo $tfi_listpub1->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
                  <?php } 
  // endif Conditional region2
?>
                </div>
                <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                  <thead>
                    <tr class="KT_row_order">
                      <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
                      </th>
                      <th id="id_user" class="KT_sorter KT_col_id_user <?php echo $tso_listpub1->getSortIcon('utilisateur.email'); ?>"> <a href="<?php echo $tso_listpub1->getSortLink('utilisateur.email'); ?>">Utilisateur</a> </th>
                      <th id="id_produit" class="KT_sorter KT_col_id_produit <?php echo $tso_listpub1->getSortIcon('produits.titre'); ?>"> <a href="<?php echo $tso_listpub1->getSortLink('produits.titre'); ?>">Produit</a> </th>
                      <th id="titre" class="KT_sorter KT_col_titre <?php echo $tso_listpub1->getSortIcon('pub.titre'); ?>"> <a href="<?php echo $tso_listpub1->getSortLink('pub.titre'); ?>">Titre</a> </th>
                      <th id="region" class="KT_sorter KT_col_region <?php echo $tso_listpub1->getSortIcon('region.nom_region'); ?>"> <a href="<?php echo $tso_listpub1->getSortLink('region.nom_region'); ?>">Région</a> </th>
                      <th id="date_fin" class="KT_sorter KT_col_date_fin <?php echo $tso_listpub1->getSortIcon('pub.date_fin'); ?>"> <a href="<?php echo $tso_listpub1->getSortLink('pub.date_fin'); ?>">Date fin</a> </th>
                      <th>&nbsp;</th>
                    </tr>
                    <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listpub1'] == 1) {
?>
                      <tr class="KT_row_filter">
                        <td>&nbsp;</td>
                        <td><select name="tfi_listpub1_id_user" id="tfi_listpub1_id_user">
                            <option value="" <?php if (!(strcmp("", @$_SESSION['tfi_listpub1_id_user']))) {echo "SELECTED";} ?>><?php echo NXT_getResource("None"); ?></option>
                            <?php
do {  
?>
                            <option value="<?php echo $row_Recordset1['id']?>"<?php if (!(strcmp($row_Recordset1['id'], @$_SESSION['tfi_listpub1_id_user']))) {echo "SELECTED";} ?>><?php echo $row_Recordset1['email']?></option>
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
                        <td><select name="tfi_listpub1_id_produit" id="tfi_listpub1_id_produit">
                            <option value="" <?php if (!(strcmp("", @$_SESSION['tfi_listpub1_id_produit']))) {echo "SELECTED";} ?>><?php echo NXT_getResource("None"); ?></option>
                            <?php
do {  
?>
                            <option value="<?php echo $row_Recordset2['id']?>"<?php if (!(strcmp($row_Recordset2['id'], @$_SESSION['tfi_listpub1_id_produit']))) {echo "SELECTED";} ?>><?php echo $row_Recordset2['titre']?></option>
                            <?php
} while ($row_Recordset2 = mysql_fetch_assoc($Recordset2));
  $rows = mysql_num_rows($Recordset2);
  if($rows > 0) {
      mysql_data_seek($Recordset2, 0);
	  $row_Recordset2 = mysql_fetch_assoc($Recordset2);
  }
?>
                          </select>
                        </td>
                        <td><input type="text" name="tfi_listpub1_titre" id="tfi_listpub1_titre" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listpub1_titre']); ?>" size="20" maxlength="100" /></td>
                        <td><select name="tfi_listpub1_region" id="tfi_listpub1_region">
                            <option value="" <?php if (!(strcmp("", @$_SESSION['tfi_listpub1_region']))) {echo "SELECTED";} ?>><?php echo NXT_getResource("None"); ?></option>
                            <?php
do {  
?>
                            <option value="<?php echo $row_Recordset3['id_region']?>"<?php if (!(strcmp($row_Recordset3['id_region'], @$_SESSION['tfi_listpub1_region']))) {echo "SELECTED";} ?>><?php echo $row_Recordset3['nom_region']?></option>
                            <?php
} while ($row_Recordset3 = mysql_fetch_assoc($Recordset3));
  $rows = mysql_num_rows($Recordset3);
  if($rows > 0) {
      mysql_data_seek($Recordset3, 0);
	  $row_Recordset3 = mysql_fetch_assoc($Recordset3);
  }
?>
                          </select>
                        </td>
                        <td>&nbsp;</td>
                        <td><input type="submit" name="tfi_listpub1" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
                      </tr>
                      <?php } 
  // endif Conditional region3
?>
                  </thead>
                  <tbody>
                    <?php if ($totalRows_rspub1 == 0) { // Show if recordset empty ?>
                      <tr>
                        <td colspan="7"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
                      </tr>
                      <?php } // Show if recordset empty ?>
                    <?php if ($totalRows_rspub1 > 0) { // Show if recordset not empty ?>
                      <?php do { ?>
                        <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                          <td><input type="checkbox" name="kt_pk_pub" class="id_checkbox" value="<?php echo $row_rspub1['id']; ?>" />
                              <input type="hidden" name="id" class="id_field" value="<?php echo $row_rspub1['id']; ?>" />
                          </td>
                          <td><div class="KT_col_id_user"><?php echo KT_FormatForList($row_rspub1['id_user'], 20); ?></div></td>
                          <td><div class="KT_col_id_produit"><?php echo KT_FormatForList($row_rspub1['id_produit'], 20); ?></div></td>
                          <td><div class="KT_col_titre"><?php echo KT_FormatForList($row_rspub1['titre'], 20); ?></div></td>
                          <td><div class="KT_col_region"><?php echo KT_FormatForList($row_rspub1['region'], 20); ?></div></td>
                          <td><div class="KT_col_date_fin"><?php echo KT_formatDate($row_rspub1['date_fin']); ?></div></td>
                          <td><a class="KT_edit_link" href="formPublicite.php?id=<?php echo $row_rspub1['id']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a> </td>
                        </tr>
                        <?php } while ($row_rspub1 = mysql_fetch_assoc($rspub1)); ?>
                      <?php } // Show if recordset not empty ?>
                  </tbody>
                </table>
                <div class="KT_bottomnav">
                  <div>
                    <?php
            $nav_listpub1->Prepare();
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
                  <a class="KT_additem_op_link" href="formPublicite.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a> </div>
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

mysql_free_result($Recordset2);

mysql_free_result($Recordset3);

mysql_free_result($rspub1);
?>