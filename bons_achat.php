<?php require_once('Connections/magazinducoin.php'); ?>
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
$tfi_listbons1 = new TFI_TableFilter($conn_magazinducoin, "tfi_listbons1");
$tfi_listbons1->addColumn("bons.titre", "STRING_TYPE", "titre", "%");
$tfi_listbons1->addColumn("bons.reduction", "STRING_TYPE", "reduction", "%");
$tfi_listbons1->addColumn("bons.date_debut", "DATE_TYPE", "date_debut", "=");
$tfi_listbons1->addColumn("bons.date_fin", "DATE_TYPE", "date_fin", "=");
$tfi_listbons1->Execute();

// Sorter
$tso_listbons1 = new TSO_TableSorter("rsbons1", "tso_listbons1");
$tso_listbons1->addColumn("bons.titre");
$tso_listbons1->addColumn("bons.reduction");
$tso_listbons1->addColumn("bons.date_debut");
$tso_listbons1->addColumn("bons.date_fin");
$tso_listbons1->setDefault("bons.reduction");
$tso_listbons1->Execute();

// Navigation
$nav_listbons1 = new NAV_Regular("nav_listbons1", "rsbons1", "", $_SERVER['PHP_SELF'], 20);

//NeXTenesio3 Special List Recordset
$maxRows_rsbons1 = $_SESSION['max_rows_nav_listbons1'];
$pageNum_rsbons1 = 0;
if (isset($_GET['pageNum_rsbons1'])) {
  $pageNum_rsbons1 = $_GET['pageNum_rsbons1'];
}
$startRow_rsbons1 = $pageNum_rsbons1 * $maxRows_rsbons1;

// Defining List Recordset variable
$NXTFilter_rsbons1 = "1=1";
if (isset($_SESSION['filter_tfi_listbons1'])) {
  $NXTFilter_rsbons1 = $_SESSION['filter_tfi_listbons1'];
}
// Defining List Recordset variable
$NXTSort_rsbons1 = "bons.reduction";
if (isset($_SESSION['sorter_tso_listbons1'])) {
  $NXTSort_rsbons1 = $_SESSION['sorter_tso_listbons1'];
}
mysql_select_db($database_magazinducoin, $magazinducoin);

$query_rsbons1 = "SELECT bons.titre, bons.reduction, bons.date_debut, bons.date_fin, bons.id_bon FROM bons WHERE {$NXTFilter_rsbons1} ORDER BY {$NXTSort_rsbons1}";
$query_limit_rsbons1 = sprintf("%s LIMIT %d, %d", $query_rsbons1, $startRow_rsbons1, $maxRows_rsbons1);
$rsbons1 = mysql_query($query_limit_rsbons1, $magazinducoin) or die(mysql_error());
$row_rsbons1 = mysql_fetch_assoc($rsbons1);

if (isset($_GET['totalRows_rsbons1'])) {
  $totalRows_rsbons1 = $_GET['totalRows_rsbons1'];
} else {
  $all_rsbons1 = mysql_query($query_rsbons1);
  $totalRows_rsbons1 = mysql_num_rows($all_rsbons1);
}
$totalPages_rsbons1 = ceil($totalRows_rsbons1/$maxRows_rsbons1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listbons1->checkBoundries();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 
	<title>Magasin du coin | Espace membre </title>
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
  .KT_col_reduction {width:35px; overflow:hidden;}
  .KT_col_date_debut {width:140px; overflow:hidden;}
  .KT_col_date_fin {width:140px; overflow:hidden;}
</style>
<style type="text/css">
  /* Dynamic List row settings */
  .KT_col_titre {width:140px; overflow:hidden;}
  .KT_col_reduction {width:35px; overflow:hidden;}
  .KT_col_date_debut {width:140px; overflow:hidden;}
  .KT_col_date_fin {width:140px; overflow:hidden;}
</style>
</head>
<body id="sp">
<?php include("modules/header.php"); ?>
  		<div id="content">
        	 <div class="top reduit">
                    <?php include("modules/menu.php"); ?>
                    <div  style="font-size: 14px; font-weight: bold; position: absolute; right: 15px; top: 51px;">Votre Crédit Publicité: <?php 
$query_Recordset1 = "SELECT credit FROM utilisateur WHERE id = ".$_SESSION['kt_login_id'];
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$row_credit = mysql_fetch_assoc($Recordset1);
echo $row_credit['credit'];  ?> &euro;</div>
            </div>
           <div style="float:left; width:200px;">           
<?php include("modules/membre_menu.php"); ?>
</div>
<div style="float:left; width:780px; padding-left:20px;">
<h3>Ma liste des bons</h3>
          <div class="KT_tng" id="listbons1">
            
            <div class="KT_tnglist">
              <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
                <div class="KT_options"> <a href="<?php echo $nav_listbons1->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
                  <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listbons1'] == 1) {
?>
                    <?php echo $_SESSION['default_max_rows_nav_listbons1']; ?>
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
                      <th id="reduction" class="KT_sorter KT_col_titre"> Titre </th>
                      <th id="reduction" class="KT_sorter KT_col_reduction"> Réduction </th>
                      <th id="date_debut" class="KT_sorter KT_col_date_debut <?php echo $tso_listbons1->getSortIcon('bons.date_debut'); ?>"> <a href="<?php echo $tso_listbons1->getSortLink('bons.date_debut'); ?>">Date de debut</a> </th>
                      <th id="date_fin" class="KT_sorter KT_col_date_fin <?php echo $tso_listbons1->getSortIcon('bons.date_fin'); ?>"> <a href="<?php echo $tso_listbons1->getSortLink('bons.date_fin'); ?>">Date de fin</a> </th>
                      <th>&nbsp;</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if ($totalRows_rsbons1 == 0) { // Show if recordset empty ?>
                      <tr>
                        <td colspan="5"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
                      </tr>
                      <?php } // Show if recordset empty ?>
                    <?php if ($totalRows_rsbons1 > 0) { // Show if recordset not empty ?>
                    <?php do { ?>
                        <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                          <td><input type="checkbox" name="kt_pk_bons" class="id_checkbox" value="<?php echo $row_rsbons1['id_bon']; ?>" />
                              <input type="hidden" name="id_bon" class="id_field" value="<?php echo $row_rsbons1['id_bon']; ?>" />
                          </td>
                          <td><div class="KT_col_titre"><?php echo KT_FormatForList($row_rsbons1['titre'], 20); ?></div>
                          <td><div class="KT_col_reduction"><?php echo KT_FormatForList($row_rsbons1['reduction'], 10); ?> €</div></td>
                          <td><div class="KT_col_date_debut"><?php echo KT_formatDate($row_rsbons1['date_debut']); ?></div></td>
                          <td><div class="KT_col_date_fin"><?php echo KT_formatDate($row_rsbons1['date_fin']); ?></div></td>
                          <td><a class="KT_edit_link" href="formBon.php?id_bon=<?php echo $row_rsbons1['id_bon']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a> </td>
                        </tr>
                        <?php } while ($row_rsbons1 = mysql_fetch_assoc($rsbons1)); ?>
                      <?php } // Show if recordset not empty ?>
                  </tbody>
                </table>
                <div class="KT_bottomnav">
                  <div>
                    <?php
            $nav_listbons1->Prepare();
            require("includes/nav/NAV_Text_Navigation.inc.php");
          ?>
                  </div>
                </div>
                <div class="KT_bottombuttons">
                  <div class="KT_operations"> <a class="KT_edit_op_link" href="#" onclick="nxt_list_edit_link_form(this); return false;"><?php echo NXT_getResource("edit_all"); ?></a> <a class="KT_delete_op_link" href="#" onclick="nxt_list_delete_link_form(this); return false;"><?php echo NXT_getResource("delete_all"); ?></a> </div>
<span>&nbsp;</span>
                  <select name="no_new" id="no_new" style="width:50px">
                    <option value="1">1</option>
                    <option value="3">3</option>
                    <option value="6">6</option>
                  </select>
                  <a class="KT_additem_op_link" href="formBon.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a> </div>
              </form>
            </div>
            <br class="clearfixplain" />
          </div>
  		</div>
	</div>
    </div>
  
  
  
  
  <!-- Sidebar Area -->
 
</div>

    </div>
</form>
<div id="footer">
    		<?php include("modules/region_barre_recherche.php"); ?>
        <div class="liens">
       		<?php include("modules/footer.php"); ?>
		</div>
</div>


</body>
</html>
<?php
mysql_free_result($rsbons1);
?>