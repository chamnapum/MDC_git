<?php require_once('Connections/magazinducoin.php'); ?>
<?php
// Load the common classes
require_once('includes/common/KT_common.php');

// Load the required classes
require_once('includes/tfi/TFI.php');
require_once('includes/tso/TSO.php');
require_once('includes/nav/NAV.php');

// Make unified connection variable
$conn_magazinducoin = new KT_connection($magazinducoin, $database_magazinducoin);

$restrict = new tNG_RestrictAccess($conn_magazinducoin, "");
//$restrict->addLevel("1");
$restrict->Execute();

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
$tfi_listphotographes4 = new TFI_TableFilter($conn_magazinducoin, "tfi_listphotographes4");
$tfi_listphotographes4->addColumn("nom", "STRING_TYPE", "nom", "%");
$tfi_listphotographes4->addColumn("prenom", "STRING_TYPE", "prenom", "%");
$tfi_listphotographes4->addColumn("telephone", "STRING_TYPE", "telephone", "%");
$tfi_listphotographes4->addColumn("adresse", "STRING_TYPE", "adresse", "%");
$tfi_listphotographes4->addColumn("note", "NUMERIC_TYPE", "note", "=");
$tfi_listphotographes4->Execute();

// Sorter
$tso_listphotographes4 = new TSO_TableSorter("photographes", "tso_listphotographes4");
$tso_listphotographes4->addColumn("nom");
$tso_listphotographes4->addColumn("prenom");
$tso_listphotographes4->addColumn("telephone");
$tso_listphotographes4->addColumn("adresse");
$tso_listphotographes4->addColumn("note");
$tso_listphotographes4->setDefault("note DESC");
$tso_listphotographes4->Execute();

// Navigation
$nav_listphotographes4 = new NAV_Regular("nav_listphotographes4", "photographes", "", $_SERVER['PHP_SELF'], 10);

//NeXTenesio3 Special List Recordset
$maxRows_photographes = $_SESSION['max_rows_nav_listphotographes4'];
$pageNum_photographes = 0;
if (isset($_GET['pageNum_photographes'])) {
  $pageNum_photographes = $_GET['pageNum_photographes'];
}
$startRow_photographes = $pageNum_photographes * $maxRows_photographes;

// Defining List Recordset variable
$NXTFilter_photographes = "1=1";
if (isset($_SESSION['filter_tfi_listphotographes4'])) {
  $NXTFilter_photographes = $_SESSION['filter_tfi_listphotographes4'];
}
// Defining List Recordset variable
$NXTSort_photographes = "note DESC";
if (isset($_SESSION['sorter_tso_listphotographes4'])) {
  $NXTSort_photographes = $_SESSION['sorter_tso_listphotographes4'];
}
mysql_select_db($database_magazinducoin, $magazinducoin);

$reg = $_SESSION['kt_region'];
$query_photographes = "SELECT id, nom, prenom, telephone, adresse, note FROM utilisateur WHERE level=2 and region =$reg and {$NXTFilter_photographes}  ORDER BY  {$NXTSort_photographes} ";

$query_limit_photographes = sprintf("%s LIMIT %d, %d", $query_photographes, $startRow_photographes, $maxRows_photographes);
$photographes = mysql_query($query_limit_photographes, $magazinducoin) or die(mysql_error());
$row_photographes = mysql_fetch_assoc($photographes);

if (isset($_GET['totalRows_photographes'])) {
  $totalRows_photographes = $_GET['totalRows_photographes'];
} else {
  $all_photographes = mysql_query($query_photographes);
  $totalRows_photographes = mysql_num_rows($all_photographes);
}
$totalPages_photographes = ceil($totalRows_photographes/$maxRows_photographes)-1;
//End NeXTenesio3 Special List Recordset

$nav_listphotographes4->checkBoundries();
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Magasinducoin | Espace membre </title>
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
  row_effects: false,
  show_as_buttons: true,
  record_counter: false
}
</script>
<style type="text/css">
  /* Dynamic List row settings */
  .KT_col_nom {width:140px; overflow:hidden;}
  .KT_col_prenom {width:140px; overflow:hidden;}
  .KT_col_telephone {width:140px; overflow:hidden;}
  .KT_col_adresse {width:140px; overflow:hidden;}
  .KT_col_note {width:140px; overflow:hidden;}
</style>
</head>

<body id="sp">
<?php include("modules/header.php"); ?>
<div id="content">
<?php include("modules/member_menu.php"); ?>
	<?php //include("modules/membre_menu.php"); ?>
	<div style="padding-left:20px;">
		<div class="KT_tng" id="listphotographes4">
  
  <div class="KT_tnglist">
  <h3> <?php echo $xml->Liste_des_photographes; ?></h3>
    <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
      <div class="KT_options"> <a href="<?php echo $nav_listphotographes4->getShowAllLink(); ?>">
	  <?php echo $xml->afficher_tous; ?>
     </a> &nbsp;
      &nbsp; </div>
      <table cellpadding="2" cellspacing="0" class="KT_tngtable" width="950">
        <thead>
          <tr class="KT_row_order">
            <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>            </th>
            <th id="nom" class="KT_sorter KT_col_nom <?php echo $tso_listphotographes4->getSortIcon('nom'); ?>"> <a href="<?php echo $tso_listphotographes4->getSortLink('nom'); ?>"><?php echo $xml-> Nom ;?></a> </th>
            <th id="prenom" class="KT_sorter KT_col_prenom <?php echo $tso_listphotographes4->getSortIcon('prenom'); ?>"> <a href="<?php echo $tso_listphotographes4->getSortLink('prenom'); ?>"><?php echo $xml-> Prenom ;?></a> </th>
            <th id="telephone" class="KT_sorter KT_col_telephone <?php echo $tso_listphotographes4->getSortIcon('telephone'); ?>"> <a href="<?php echo $tso_listphotographes4->getSortLink('telephone'); ?>"><?php echo $xml->Telephone ?></a> </th>
            <th id="adresse" class="KT_sorter KT_col_adresse <?php echo $tso_listphotographes4->getSortIcon('adresse'); ?>"> <a href="<?php echo $tso_listphotographes4->getSortLink('adresse'); ?>"><?php echo $xml->Adresse ?></a> </th>
           
            <th>&nbsp;</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($totalRows_photographes == 0) { // Show if recordset empty ?>
            <tr>
              <td colspan="7"><?php echo $xml->la_table_est_vide; ?></td>
            </tr>
            <?php } // Show if recordset empty ?>
          <?php if ($totalRows_photographes > 0) { // Show if recordset not empty ?>
            <?php do { ?>
              <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                <td><input type="checkbox" name="kt_pk_utilisateur" class="id_checkbox" value="<?php echo $row_photographes['id']; ?>" />
                    <input type="hidden" name="id" class="id_field" value="<?php echo $row_photographes['id']; ?>" />                </td>
                <td><div class="KT_col_nom"><?php echo KT_FormatForList($row_photographes['nom'], 20); ?></div></td>
                <td><div class="KT_col_prenom"><?php echo KT_FormatForList($row_photographes['prenom'], 20); ?></div></td>
                <td><div class="KT_col_telephone"><?php echo KT_FormatForList($row_photographes['telephone'], 20); ?></div></td>
                <td><div class="KT_col_adresse"><?php echo KT_FormatForList($row_photographes['adresse'], 20); ?></div></td>
                
                <td><a class="KT_edit_link btnviolet" style="width:150px; height:18px; padding-top:4px;" href="fiche_photographe.php?id=<?php echo $row_photographes['id']; ?>"><?php echo $xml->Fiche_technique  ?></a> </td>
              </tr>
              <?php } while ($row_photographes = mysql_fetch_assoc($photographes)); ?>
            <?php } // Show if recordset not empty ?>
        </tbody>
      </table>
      <?php /*?><div class="KT_bottomnav">
        <div>
          <?php
            $nav_listphotographes4->Prepare();
            require("includes/nav/NAV_Text_Navigation.inc.php");
          ?>
        </div>
      </div><?php */?>
    </form>
  </div>
  </div>
  <br class="clearfixplain" />
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
mysql_free_result($photographes);
?>
