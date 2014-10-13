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
$tfi_listevenements1 = new TFI_TableFilter($conn_magazinducoin, "tfi_listevenements1");
$tfi_listevenements1->addColumn("evenements.titre", "STRING_TYPE", "titre", "%");
$tfi_listevenements1->addColumn("category.cat_id", "NUMERIC_TYPE", "category_id", "=");
$tfi_listevenements1->addColumn("evenements.active", "NUMERIC_TYPE", "active", "=");
$tfi_listevenements1->addColumn("evenements.date_debut", "DATE_TYPE", "date_debut", "=");
$tfi_listevenements1->addColumn("evenements.date_fin", "DATE_TYPE", "date_fin", "=");
$tfi_listevenements1->Execute();

// Sorter
$tso_listevenements1 = new TSO_TableSorter("rsevenements1", "tso_listevenements1");
$tso_listevenements1->addColumn("evenements.titre");
$tso_listevenements1->addColumn("category.cat_name");
$tso_listevenements1->addColumn("evenements.active");
$tso_listevenements1->addColumn("evenements.date_debut");
$tso_listevenements1->addColumn("evenements.date_fin");
$tso_listevenements1->setDefault("evenements.date_debut DESC");
$tso_listevenements1->Execute();

// Navigation
$nav_listevenements1 = new NAV_Regular("nav_listevenements1", "rsevenements1", "", $_SERVER['PHP_SELF'], 20);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset1 = "SELECT cat_name, cat_id FROM category ORDER BY cat_name";
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

//NeXTenesio3 Special List Recordset
$maxRows_rsevenements1 = $_SESSION['max_rows_nav_listevenements1'];
$pageNum_rsevenements1 = 0;
if (isset($_GET['pageNum_rsevenements1'])) {
  $pageNum_rsevenements1 = $_GET['pageNum_rsevenements1'];
}
$startRow_rsevenements1 = $pageNum_rsevenements1 * $maxRows_rsevenements1;

// Defining List Recordset variable
$NXTFilter_rsevenements1 = "1=1";
if (isset($_SESSION['filter_tfi_listevenements1'])) {
  $NXTFilter_rsevenements1 = $_SESSION['filter_tfi_listevenements1'];
}
// Defining List Recordset variable
//unset($_SESSION['sorter_tso_listevenements1']);
$NXTSort_rsevenements1 = "evenements.date_debut DESC";
if (isset($_SESSION['sorter_tso_listevenements1'])) {
  $NXTSort_rsevenements1 = $_SESSION['sorter_tso_listevenements1'];
}
mysql_select_db($database_magazinducoin, $magazinducoin);

$query_rsevenements1 = "SELECT evenements.titre, category.cat_name AS category_id, evenements.active, evenements.date_debut, evenements.date_fin, evenements.event_id FROM evenements LEFT JOIN category ON evenements.category_id = category.cat_id WHERE {$NXTFilter_rsevenements1} AND evenements.user_id = {$_SESSION['kt_login_id']} ORDER BY {$NXTSort_rsevenements1}";
$query_limit_rsevenements1 = sprintf("%s LIMIT %d, %d", $query_rsevenements1, $startRow_rsevenements1, $maxRows_rsevenements1);
$rsevenements1 = mysql_query($query_limit_rsevenements1, $magazinducoin) or die(mysql_error());
$row_rsevenements1 = mysql_fetch_assoc($rsevenements1);

if (isset($_GET['totalRows_rsevenements1'])) {
  $totalRows_rsevenements1 = $_GET['totalRows_rsevenements1'];
} else {
  $all_rsevenements1 = mysql_query($query_rsevenements1);
  $totalRows_rsevenements1 = mysql_num_rows($all_rsevenements1);
}
$totalPages_rsevenements1 = ceil($totalRows_rsevenements1/$maxRows_rsevenements1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listevenements1->checkBoundries();
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
  .KT_col_titre {width:140px; overflow:hidden;}
  .KT_col_category_id {width:100px; overflow:hidden;}
  .KT_col_active {width:70px; overflow:hidden;}
  .KT_col_date_debut {width:80px; overflow:hidden;}
  .KT_col_date_fin {width:80px; overflow:hidden;}
</style>
</head>
<body id="sp">
<?php include("modules/header.php"); ?>
  		<div id="content">
        	 <div class="top reduit">
                    <?php include("modules/menu.php"); ?>
                    <div  style="font-size: 14px; font-weight: bold; position: absolute; right: 15px; top: 51px;">    
					<?php echo $xml-> Votre_credit_publicite  ;?>  <?php 
$query_Recordset1 = "SELECT credit FROM utilisateur WHERE id = ".$_SESSION['kt_login_id'];
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$row_credit = mysql_fetch_assoc($Recordset1);
echo $row_credit['credit'];  ?> &euro;</div>
             </div>
<?php include("modules/membre_menu.php"); ?>
            <div style="padding-left:40px;">
         
           
	  
                    <div class="KT_tng" id="listevenements1">
    
            <div class="KT_tnglist">
             <h3><?php echo $xml->Liste_des_evenements?></h3>
              <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
                <div class="KT_options"> <a href="<?php echo $nav_listevenements1->getShowAllLink(); ?>"><?php echo $xml->afficher_tous; ?></a> &nbsp;
                &nbsp; </div>
                <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                  <thead>
                    <tr class="KT_row_order">
                      <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
                      </th>
                      <th id="titre" class="KT_sorter KT_col_titre <?php echo $tso_listevenements1->getSortIcon('evenements.titre'); ?>"> <a href="<?php echo $tso_listevenements1->getSortLink('evenements.titre'); ?>"><?php echo $xml->Titre ?></a> </th>
                      <th id="category_id" class="KT_sorter KT_col_category_id <?php echo $tso_listevenements1->getSortIcon('category.cat_name'); ?>"> <a href="<?php echo $tso_listevenements1->getSortLink('category.cat_name'); ?>"><?php echo $xml-> Categorie ?></a> </th>
                      <th id="active" class="KT_sorter KT_col_active <?php echo $tso_listevenements1->getSortIcon('evenements.active'); ?>"> <a href="<?php echo $tso_listevenements1->getSortLink('evenements.active'); ?>"><?php echo $xml->Active ?></a> </th>
                      <th id="date_debut" class="KT_sorter KT_col_date_debut <?php echo $tso_listevenements1->getSortIcon('evenements.date_debut'); ?>"> <a href="<?php echo $tso_listevenements1->getSortLink('evenements.date_debut'); ?>"><?php echo $xml->Date_debut ?></a> </th>
                      <th id="date_fin" class="KT_sorter KT_col_date_fin <?php echo $tso_listevenements1->getSortIcon('evenements.date_fin'); ?>"> <a href="<?php echo $tso_listevenements1->getSortLink('evenements.date_fin'); ?>"><?php echo $xml->Date_fin ?></a> </th>
                      <th>&nbsp;</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if ($totalRows_rsevenements1 == 0) { // Show if recordset empty ?>
                      <tr>
                        <td colspan="7"><?php echo $xml-> la_table_est_vide; ?></td>
                      </tr>
                      <?php } // Show if recordset empty ?>
                    <?php if ($totalRows_rsevenements1 > 0) { // Show if recordset not empty ?>
                      <?php do { ?>
                        <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                          <td><input type="checkbox" name="kt_pk_evenements" class="id_checkbox" value="<?php echo $row_rsevenements1['event_id']; ?>" />
                              <input type="hidden" name="event_id" class="id_field" value="<?php echo $row_rsevenements1['event_id']; ?>" />
                          </td>
                          <td><div class="KT_col_titre"><?php echo KT_FormatForList($row_rsevenements1['titre'], 20); ?></div></td>
                          <td><div class="KT_col_category_id"><?php echo (KT_FormatForList($row_rsevenements1['category_id'], 20)); ?></div></td>
                          <td><div class="KT_col_active"><?php echo $row_rsevenements1['active']?"Oui":"Non"; ?></div></td>
                          <td><div class="KT_col_date_debut"><?php echo KT_formatDate($row_rsevenements1['date_debut']); ?></div></td>
                          <td><div class="KT_col_date_fin"><?php echo KT_formatDate($row_rsevenements1['date_fin']); ?></div></td>
                          <td><a class="KT_edit_link" href="add_events.php?event_id=<?php echo $row_rsevenements1['event_id']; ?>&amp;KT_back=1"><?php echo $xml->editer ?></a> <a class="KT_delete_link" href="#delete"><?php echo $xml->supprimer?></a> </td>
                        </tr>
                        <?php } while ($row_rsevenements1 = mysql_fetch_assoc($rsevenements1)); ?>
                      <?php } // Show if recordset not empty ?>
                  </tbody>
                </table>
                <div class="KT_bottomnav">
                  <div>
                    <?php
            $nav_listevenements1->Prepare();
            require("includes/nav/NAV_Text_Navigation.inc.php");
          ?>
                  </div>
                </div>
                <div class="KT_bottombuttons">
                  <div class="KT_operations"> <a class="KT_edit_op_link" href="#" onclick="nxt_list_edit_link_form(this); return false;">&Eacute;diter tous</a> <a class="KT_delete_op_link" href="#" onclick="nxt_list_delete_link_form(this); return false;"><?php echo   $xml->supprimer_tous; ?></a> </div>
<span>&nbsp;</span>
                  <select name="no_new" id="no_new" style="width:90px">
                    <option value="1">1</option>
                    <option value="5">5</option>
                    <option value="10">10</option>
                  </select>
                  <a class="KT_additem_op_link" href="add_events.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo $xml->ajouter_nouveau?></a> </div>
              </form>
            </div>
            <br class="clearfixplain" />
          </div>
        
	  </div>
	</div>
  </div>

</div>



<!-- End Content Wrapper -->
<div id="footer">
    	<?php include("modules/region_barre_recherche.php"); ?>
        <div class="liens">
      	 <?php include("modules/footer.php"); ?>
        </div>
</div>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($rsevenements1);
?>