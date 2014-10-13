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
$tfi_listpub1 = new TFI_TableFilter($conn_magazinducoin, "tfi_listpub1");
$tfi_listpub1->addColumn("pub.titre", "STRING_TYPE", "titre", "%");
$tfi_listpub1->addColumn("pub_emplacement.id", "NUMERIC_TYPE", "emplacement", "=");
$tfi_listpub1->addColumn("region.id_region", "NUMERIC_TYPE", "region", "=");
$tfi_listpub1->addColumn("produits.id", "NUMERIC_TYPE", "id_produit", "=");
$tfi_listpub1->addColumn("pub.date_debut", "DATE_TYPE", "date_debut", "=");
$tfi_listpub1->addColumn("pub.date_fin", "DATE_TYPE", "date_fin", "=");
$tfi_listpub1->Execute();

// Sorter
$tso_listpub1 = new TSO_TableSorter("rspub1", "tso_listpub1");
$tso_listpub1->addColumn("pub.titre");
$tso_listpub1->addColumn("pub_emplacement.titre");
$tso_listpub1->addColumn("region.nom_region");
$tso_listpub1->addColumn("produits.titre");
$tso_listpub1->addColumn("pub.date_debut");
$tso_listpub1->addColumn("pub.date_fin");
$tso_listpub1->setDefault("pub.id_produit");
$tso_listpub1->Execute();

// Navigation
$nav_listpub1 = new NAV_Regular("nav_listpub1", "rspub1", "", $_SERVER['PHP_SELF'], 20);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset1 = "SELECT titre, id FROM pub_emplacement ORDER BY titre";
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset2 = "SELECT nom_region, id_region FROM region ORDER BY nom_region";
$Recordset2 = mysql_query($query_Recordset2, $magazinducoin) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset3 = "SELECT titre, id FROM produits ORDER BY titre";
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
$NXTSort_rspub1 = "pub.id_produit";
if (isset($_SESSION['sorter_tso_listpub1'])) {
  $NXTSort_rspub1 = $_SESSION['sorter_tso_listpub1'];
}
mysql_select_db($database_magazinducoin, $magazinducoin);

$query_rspub1 = "SELECT pub.titre, pub_emplacement.titre AS emplacement, region.nom_region AS region, produits.titre AS id_produit, pub.date_debut, pub.date_fin, pub.id FROM ((pub LEFT JOIN pub_emplacement ON pub.emplacement = pub_emplacement.id) LEFT JOIN region ON pub.region = region.id_region) LEFT JOIN produits ON pub.id_produit = produits.id WHERE pub.id_user =  ".$_SESSION['kt_login_id']." AND pub.payer = 1 AND {$NXTFilter_rspub1} ORDER BY {$NXTSort_rspub1}";
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
  .KT_col_emplacement {width:100px; overflow:hidden;}
  .KT_col_region {width:100px; overflow:hidden;}
  .KT_col_id_produit {width:140px; overflow:hidden;}
  .KT_col_date_debut {width:110px; overflow:hidden;}
  
  .KT_col_date_fin {width:120px; overflow:hidden;}
</style>
</head>
<body id="sp">
<?php include("modules/header.php"); ?>


  		<div id="content" class="photographes" >
		<?php include("modules/credit.php"); ?>          
		<?php include("modules/membre_menu.php"); ?>
<div style="padding-left:250px;height:500px;">
<h3 style="margin-left:20px;">Acheter de la publicité<?php //echo $xml->Mes_publicites ?></h3>
                    <div class="KT_tng" id="listpub1">
           			<!-- <h1> Pub
              <?php
  //$nav_listpub1->Prepare();
  //require("includes/nav/NAV_Text_Statistics.inc.php");
?>
            </h1>-->
            <div class="KT_tnglist">
              <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
                <div class="KT_options"> 
                <a href="<?php echo $nav_listpub1->getShowAllLink(); ?>">
				<?php echo $xml->afficher_tous; ?> </a>  </div>
                <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                  <thead>
                    <tr class="KT_row_order">
                      <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
                      </th>
                      <th id="titre" class="KT_sorter KT_col_titre <?php echo $tso_listpub1->getSortIcon('pub.titre'); ?>"> <a href="<?php echo $tso_listpub1->getSortLink('pub.titre'); ?>"><?php echo $xml->Titre ?></a> </th>
                      <th id="emplacement" class="KT_sorter KT_col_emplacement <?php echo $tso_listpub1->getSortIcon('pub_emplacement.titre'); ?>"> <a href="<?php echo $tso_listpub1->getSortLink('pub_emplacement.titre'); ?>">Emplacement</a> </th>
                      <th id="region" class="KT_sorter KT_col_region <?php echo $tso_listpub1->getSortIcon('region.nom_region'); ?>"> <a href="<?php echo $tso_listpub1->getSortLink('region.nom_region'); ?>"><?php echo $xml->Region ?></a> </th>
                      <th id="id_produit" class="KT_sorter KT_col_id_produit <?php echo $tso_listpub1->getSortIcon('produits.titre'); ?>"> <a href="<?php echo $tso_listpub1->getSortLink('produits.titre'); ?>"><?php echo $xml->produit ?></a> </th>
                      <th id="date_fin" class="KT_sorter KT_col_date_fin <?php echo $tso_listpub1->getSortIcon('pub.date_fin'); ?>"> <a href="<?php echo $tso_listpub1->getSortLink('pub.date_fin'); ?>"><?php echo $xml->Date_fin ?></a> </th>
                      <th>&nbsp;</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if ($totalRows_rspub1 == 0) { // Show if recordset empty ?>
                      <tr>
                        <td colspan="8"><?php echo $xml->la_table_est_vide; ?></td>
                      </tr>
                      <?php } // Show if recordset empty ?>
                    <?php if ($totalRows_rspub1 > 0) { // Show if recordset not empty ?>
                      <?php do { ?>
                        <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                          <td><input type="checkbox" name="kt_pk_pub" class="id_checkbox" value="<?php echo $row_rspub1['id']; ?>" />
                              <input type="hidden" name="id" class="id_field" value="<?php echo $row_rspub1['id']; ?>" />
                          </td>
                          <td><div class="KT_col_titre"><?php echo KT_FormatForList($row_rspub1['titre'], 20); ?></div></td>
                          <td><div class="KT_col_emplacement"><?php echo KT_FormatForList($row_rspub1['emplacement'], 20); ?></div></td>
                          <td><div class="KT_col_region"><?php echo KT_FormatForList($row_rspub1['region'], 20); ?></div></td>
                          <td><div class="KT_col_id_produit"><?php echo KT_FormatForList($row_rspub1['id_produit'], 20); ?></div></td>
                          
                          <td><div class="KT_col_date_fin"><?php echo KT_formatDate($row_rspub1['date_fin']); ?></div></td>
                          <td><a class="KT_edit_link" href="formPub.php?id=<?php echo $row_rspub1['id']; ?>&amp;KT_back=1"><?php //echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo $xml->supprimer; ?></a> </td>
                        </tr>
                        <?php } while ($row_rspub1 = mysql_fetch_assoc($rspub1)); ?>
                      <?php } // Show if recordset not empty ?>
                  </tbody>
                </table>
                <div class="KT_bottomnav">
                  <div>
                    <?php
            $nav_listpub1->Prepare();
            require("includes/nav/NAV_Text_Navigation.inc.php");
          ?>
                  </div>
                </div>
                <div class="KT_bottombuttons">
                  <div class="KT_operations"> <a class="KT_edit_op_link" href="#" onclick="nxt_list_edit_link_form(this); return false;"><?php //echo NXT_getResource("edit_all"); ?></a> <a class="KT_delete_op_link" href="#" onclick="nxt_list_delete_link_form(this); return false;"><?php echo $xml->supprimer_tous ?></a> </div>
<span>&nbsp;</span>
<input name="no_new" id="no_new" type="hidden" value="1" />
                  <a class="KT_additem_op_link" href="formPub.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo $xml->ajouter_nouveau ?></a> </div>
              </form>
            </div>
            <br class="clearfixplain" />
          </div>
         
	  </div>
	</div>

    </div>
    </div>
  </div>
</form>

<div id="footer">
    <div class="recherche">
    &nbsp;
    </div>
    <?php include("modules/footer.php"); ?>
</div>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);

mysql_free_result($Recordset3);

mysql_free_result($rspub1);
?>