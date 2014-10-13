<?php require_once('Connections/magazinducoin.php'); ?>
<?php
if(!$_SESSION['kt_login_id']){
	echo'<script>window.location="message_aprouvation.php";</script>';
}
?>
<?php
// Load the common classes
require_once('includes/common/KT_common.php');

// Load the required classes
require_once('includes/tfi/TFI.php');
require_once('includes/tso/TSO.php');
require_once('includes/nav/NAV.php');

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
$tfi_listabonement2 = new TFI_TableFilter($conn_magazinducoin, "tfi_listabonement2");
$tfi_listabonement2->addColumn("abonement.date_abonement", "DATE_TYPE", "date_abonement", "=");
//$tfi_listabonement2->addColumn("abonement.date_echeance", "DATE_TYPE", "date_echeance", "=");
$tfi_listabonement2->addColumn("abonement.mode_payement", "STRING_TYPE", "mode_payement", "%");
$tfi_listabonement2->Execute();

// Sorter
$tso_listabonement2 = new TSO_TableSorter("rsabonement1", "tso_listabonement2");
$tso_listabonement2->addColumn("abonement.date_abonement");
$tso_listabonement2->addColumn("abonement.date_echeance");
$tso_listabonement2->addColumn("abonement.mode_payement");
$tso_listabonement2->setDefault("abonement.date_abonement");
$tso_listabonement2->Execute();

// Navigation
$nav_listabonement2 = new NAV_Regular("nav_listabonement2", "rsabonement1", "", $_SERVER['PHP_SELF'], 200);

$colname_abonement = "-1";
if (isset($_SESSION['kt_login_id'])) {
  $colname_abonement = $_SESSION['kt_login_id'];
}
mysql_select_db($database_magazinducoin, $magazinducoin);
mysql_query("UPDATE abonement SET active = 0 WHERE date_echeance < '".date('Y-m-d')."' AND id_user = $colname_abonement" , $magazinducoin) or die(mysql_error());

$query_abonement = sprintf("SELECT * FROM abonement WHERE id_user = %s ORDER BY date_abonement ASC", GetSQLValueString($colname_abonement, "int"));
$abonement = mysql_query($query_abonement, $magazinducoin) or die(mysql_error());
$row_abonement = mysql_fetch_assoc($abonement);
$totalRows_abonement = mysql_num_rows($abonement);

//NeXTenesio3 Special List Recordset
$maxRows_rsabonement1 = $_SESSION['max_rows_nav_listabonement2'];
$pageNum_rsabonement1 = 0;
if (isset($_GET['pageNum_rsabonement1'])) {
  $pageNum_rsabonement1 = $_GET['pageNum_rsabonement1'];
}
$startRow_rsabonement1 = $pageNum_rsabonement1 * $maxRows_rsabonement1;

// Defining List Recordset variable
$NXTFilter_rsabonement1 = "1=1";
if (isset($_SESSION['filter_tfi_listabonement2'])) {
  $NXTFilter_rsabonement1 = $_SESSION['filter_tfi_listabonement2'];
}
// Defining List Recordset variable
$NXTSort_rsabonement1 = "abonement.id DESC";
//$_SESSION['sorter_tso_listabonement2'] = "abonement.date_abonement DESC";
/*if (isset($_SESSION['sorter_tso_listabonement2'])) {
  $NXTSort_rsabonement1 = $_SESSION['sorter_tso_listabonement2'];
}*/
mysql_select_db($database_magazinducoin, $magazinducoin);

$query_rsabonement1 = "SELECT abonement.* FROM abonement WHERE {$NXTFilter_rsabonement1} AND id_user = ".$_SESSION['kt_login_id']." ORDER BY {$NXTSort_rsabonement1}";
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

$nav_listabonement2->checkBoundries();

// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

// Make unified connection variable
$conn_magazinducoin = new KT_connection($magazinducoin, $database_magazinducoin);

//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_magazinducoin, "");
//Grand Levels: Level
$restrict->addLevel("1");
$restrict->Execute();


//End Restrict Access To Page
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 
	<title>Magasinducoin | Espace membre </title>
    <?php include("modules/head.php"); ?>
<link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />

</head>
<body id="sp">
<?php include("modules/header.php"); ?>

<div id="content">
<?php include("modules/member_menu.php"); ?>
<?php include("modules/credit.php"); ?>
<div style="float:left; width:100%;">     
    	<h3 style="margin-left:20px;">Acheter du crédit</h3><br>
        <?php /*?><h2 style="margin-left:20px;"><?php echo $xml->vous_etes_abone_depuis;  ?>
		<?php echo KT_formatDate($row_rsabonement1['date_abonement']); ?></h2>
        <h2 style="margin-left:20px;"><?php echo $xml->Liste_des_commandes; ?></h2><?php */?>
        <div style="padding-left:20px;">
            <p style="float:left; width:98%; font-size:14px; margin-top:10px;">
                En rechargeant votre compte de crédit, vous pourrez économiser vos coûts de publicité en gagnant du crédit supplémentaire.
            </p>
            <p style="float:left; width:98%; font-size:14px; margin-top:10px; margin-bottom:20px;">
                Le crédit vous permet d'acheter des coupons de réductions supplémentaires, des évènements, de la mise en avant pour faire de la publicité de votre cntenu, de remonter en tête de liste, etc...
            </p>
        </div>
		<div class="KT_tng" id="listabonement2">
			<div class="KT_tnglist">
            <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
			<div style="margin-left:20px; float:left; width:98%;" class="loginForm">
              <table cellpadding="2" cellspacing="0" class="KT_tngtable" width="950">
                <thead>
                  <tr class="KT_row_order">
                    <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
                    </th>
                    <th id="date_abonement" class="KT_sorter KT_col_date_abonement <?php echo $tso_listabonement2->getSortIcon('abonement.date_abonement'); ?>"><?php echo $xml->Date_abonnement; ?></th>
                   <!--  <th id="date_echeance" class="KT_sorter KT_col_date_echeance <?php echo $tso_listabonement2->getSortIcon('abonement.date_echeance'); ?>"><?php echo $xml->Date_echeance; ?></th>-->
                    <th id="mode_payement" class="KT_sorter KT_col_mode_payement <?php echo $tso_listabonement2->getSortIcon('abonement.mode_payement'); ?>"><?php echo $xml->Mode_de_paiement ?></th>
                    <th id="mode_payement">Montant</th>
                    <th id="mode_payement">Discount</th>
                    <th id="mode_payement">Total</th>
                  <th id="mode_payement" class="KT_sorter KT_col_mode_payement <?php echo $tso_listabonement2->getSortIcon('abonement.active'); ?>"><?php echo $xml->Active ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($totalRows_rsabonement1 == 0) { // Show if recordset empty ?>
                    <tr>
                      <td colspan="7">
                      <?php echo $xml->la_table_est_vide; ?>
                      </td>
                    </tr>
                    <?php } // Show if recordset empty ?>
                  <?php if ($totalRows_rsabonement1 > 0) { // Show if recordset not empty ?>
                    <?php do { ?>
                      <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                        <td><input type="checkbox" name="kt_pk_abonement" class="id_checkbox" value="<?php echo $row_rsabonement1['id']; ?>" />
                            <input type="hidden" name="id" class="id_field" value="<?php echo $row_rsabonement1['id']; ?>" />
                        </td>
                        <td><div class="KT_col_date_abonement"><?php echo KT_formatDate($row_rsabonement1['date_abonement']); ?></div></td>
                   <!--     <td><div class="KT_col_date_echeance"><?php echo KT_formatDate($row_rsabonement1['date_echeance']); ?></div></td>-->
                        <td><div class="KT_col_mode_payement"><?php echo KT_FormatForList($row_rsabonement1['mode_payement'], 20); ?></div></td>
                        <td><div class="KT_col_mode_payement"><?php echo $row_rsabonement1['montant']; ?></div></td>
                        <td><div class="KT_col_mode_payement"><?php echo $row_rsabonement1['code_promo']; ?></div></td>
                        <td><div class="KT_col_mode_payement"><?php echo $row_rsabonement1['credit_plus']; ?></div></td>
                            <td><div class="KT_col_mode_payement"><?php echo $row_rsabonement1['active']==1? $xml->Oui:$xml->Non; ?></div></td>
                   <!--     <td><a class="KT_edit_link" href="admin/formAbonnement.php?id=<?php echo $row_rsabonement1['id']; ?>&amp;KT_back=1"><?php //echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php //echo NXT_getResource("delete_one"); ?></a> </td>-->
                      </tr>
                      <?php } while ($row_rsabonement1 = mysql_fetch_assoc($rsabonement1)); ?>
                    <?php } // Show if recordset not empty ?>
                </tbody>
              </table>
             <!-- <div class="KT_bottomnav">
                <div>
                  <?php
           // $nav_listabonement2->Prepare();
           // require("includes/nav/NAV_Text_Navigation.inc.php");
          ?>
                </div>
              </div>-->
              <div class="KT_bottombuttons" style="border:none;">
                <a style="background-color: #9D286E;  border: 0 none;  color: #F8C263;  cursor: pointer; font-size: 12px;   font-weight: bold; margin-right: 5px; padding: 3px 5px 3px;" href="payer_abonement.html">Ajouter du crédit<?php //echo NXT_getResource("add new"); ?></a> </div>
			</div>
            </form>
		</div>
		<br class="clearfixplain" />
        </div>
        <p>&nbsp;</p>
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
mysql_free_result($abonement);

mysql_free_result($rsabonement1);
?>