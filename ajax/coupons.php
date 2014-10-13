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
$tfi_listcoupons2 = new TFI_TableFilter($conn_magazinducoin, "tfi_listcoupons2");
$tfi_listcoupons2->addColumn("coupons.titre", "STRING_TYPE", "titre", "%");
$tfi_listcoupons2->addColumn("coupons.reduction", "STRING_TYPE", "reduction", "%");
$tfi_listcoupons2->addColumn("category.cat_id", "NUMERIC_TYPE", "categories", "=");
$tfi_listcoupons2->addColumn("coupons.date_debut", "DATE_TYPE", "date_debut", "=");
$tfi_listcoupons2->addColumn("coupons.date_fin", "DATE_TYPE", "date_fin", "=");
$tfi_listcoupons2->addColumn("category1.cat_id", "NUMERIC_TYPE", "sous_categorie", "=");
$tfi_listcoupons2->addColumn("coupons.min_achat", "DOUBLE_TYPE", "min_achat", "=");
$tfi_listcoupons2->Execute();

// Sorter
$tso_listcoupons2 = new TSO_TableSorter("rscoupons1", "tso_listcoupons2");
$tso_listcoupons2->addColumn("coupons.titre");
$tso_listcoupons2->addColumn("coupons.reduction");
$tso_listcoupons2->addColumn("category.cat_name");
$tso_listcoupons2->addColumn("coupons.date_debut");
$tso_listcoupons2->addColumn("coupons.date_fin");
$tso_listcoupons2->addColumn("category1.cat_name");
$tso_listcoupons2->addColumn("coupons.min_achat");
$tso_listcoupons2->setDefault("coupons.date_debut");
$tso_listcoupons2->Execute();

// Navigation
$nav_listcoupons2 = new NAV_Regular("nav_listcoupons2", "rscoupons1", "../", $_SERVER['PHP_SELF'], 20);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset1 = "SELECT cat_name, cat_id FROM category ORDER BY cat_name";
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset2 = "SELECT cat_name, cat_id FROM category ORDER BY cat_name";
$Recordset2 = mysql_query($query_Recordset2, $magazinducoin) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

//NeXTenesio3 Special List Recordset
$maxRows_rscoupons1 = $_SESSION['max_rows_nav_listcoupons2'];
$pageNum_rscoupons1 = 0;
if (isset($_GET['pageNum_rscoupons1'])) {
  $pageNum_rscoupons1 = $_GET['pageNum_rscoupons1'];
}
$startRow_rscoupons1 = $pageNum_rscoupons1 * $maxRows_rscoupons1;

// Defining List Recordset variable
$NXTFilter_rscoupons1 = "1=1";
if (isset($_SESSION['filter_tfi_listcoupons2'])) {
  $NXTFilter_rscoupons1 = $_SESSION['filter_tfi_listcoupons2'];
}
// Defining List Recordset variable
$NXTSort_rscoupons1 = "coupons.date_debut";
mysql_select_db($database_magazinducoin, $magazinducoin);

$query_rscoupons1 = "SELECT coupons.id_coupon, coupons.titre, coupons.reduction, category.cat_name AS categories, category.cat_id AS cat, coupons.date_debut, coupons.date_fin, category1.cat_name AS sous_categorie,category1.cat_id AS sous_cat,  coupons.min_achat, coupons.id_coupon FROM (coupons LEFT JOIN category ON coupons.categories = category.cat_id) LEFT JOIN category AS category1 ON coupons.sous_categorie = category1.cat_id WHERE {$NXTFilter_rscoupons1} AND coupons.id_magasin = ".$_GET['magasin']." ORDER BY {$NXTSort_rscoupons1}";
$query_limit_rscoupons1 = sprintf("%s LIMIT %d, %d", $query_rscoupons1, $startRow_rscoupons1, $maxRows_rscoupons1);
$rscoupons1 = mysql_query($query_limit_rscoupons1, $magazinducoin) or die(mysql_error());
$row_rscoupons1 = mysql_fetch_assoc($rscoupons1);

if (isset($_GET['totalRows_rscoupons1'])) {
  $totalRows_rscoupons1 = $_GET['totalRows_rscoupons1'];
} else {
  $all_rscoupons1 = mysql_query($query_rscoupons1);
  $totalRows_rscoupons1 = mysql_num_rows($all_rscoupons1);
}
$totalPages_rscoupons1 = ceil($totalRows_rscoupons1/$maxRows_rscoupons1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listcoupons2->checkBoundries();
?><html>
<head>

<link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<style type="text/css">
  /* Dynamic List row settings */
  body{
  	font-size:12px;
	}
</style>
</head>
<body>

<div class="KT_tng" id="listcoupons2">
  <h1> Coupons
    <?php
  $nav_listcoupons2->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
  </h1>
  <div class="KT_tnglist">
    <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
      <table cellpadding="2" cellspacing="0" class="KT_tngtable">
        <thead>
          <tr class="KT_row_order">
            	<th width="15%">Date debut</td>
                <th width="15%">Date fin</td>
                <th width="30%">Titre</td>
                <th width="40%">Reduction</td>
          </tr>
        </thead>
        <tbody>
          <?php if ($totalRows_rscoupons1 == 0) { // Show if recordset empty ?>
            <tr>
              <td colspan="9"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
            </tr>
            <?php } // Show if recordset empty ?>
          <?php if ($totalRows_rscoupons1 > 0) { // Show if recordset not empty ?>
            <?php do { ?>
              <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
             	<td><div class="KT_col_date_debut"><?php echo KT_formatDate($row_rscoupons1['date_debut']); ?></div></td>
                <td><div class="KT_col_date_fin"><?php echo KT_formatDate($row_rscoupons1['date_fin']); ?></div></td>
                <td><div class="KT_col_titre"><?php echo KT_FormatForList($row_rscoupons1['titre'], 20); ?></div></td>
                <td><div class="KT_col_reduction">
				<?php echo $row_rscoupons1['min_achat'] > 0 ? 
				"<a href=\"javascript:;\" onclick=\"ajax('ajax/tous_produits_magasin.php?id_coupon=".$row_rscoupons1['id_coupon']."','#result')\">R&eacute;duction de <strong>".$row_rscoupons1['reduction']."%</strong> pour un minimum d'achat de ".$row_rscoupons1['min_achat']." &euro;" :
				"<a href=\"javascript:;\" onclick=\"ajax('ajax/tous_produits_magasin.php?id_coupon=".$row_rscoupons1['id_coupon']."&categories=".$row_rscoupons1['cat']."&sous_categorie=".$row_rscoupons1['sous_cat']."','#result')\">R&eacute;duction de <strong>".$row_rscoupons1['reduction']."%</strong> sur tous les produits de la cat&eacute;gorie ".($row_rscoupons1['categories'])." &raquo; ".($row_rscoupons1['sous_categorie']); ?></div>
                </a>
                </td>
              </tr>
              <?php } while ($row_rscoupons1 = mysql_fetch_assoc($rscoupons1)); ?>
            <?php } // Show if recordset not empty ?>
        </tbody>
      </table>
    </form>
  </div>
  <br class="clearfixplain" />
</div>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);

mysql_free_result($rscoupons1);
?>