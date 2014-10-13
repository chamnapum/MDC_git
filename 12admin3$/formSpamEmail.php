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
$tfi_listspam_email3 = new TFI_TableFilter($conn_magazinducoin, "tfi_listspam_email3");
$tfi_listspam_email3->addColumn("spam_email.id", "STRING_TYPE", "id", "%");
$tfi_listspam_email3->addColumn("spam_email.nom", "STRING_TYPE", "nom", "%");
$tfi_listspam_email3->addColumn("spam_email.email", "STRING_TYPE", "email", "%");
$tfi_listspam_email3->addColumn("spam_email.maessage", "STRING_TYPE", "maessage", "%");
$tfi_listspam_email3->addColumn("spam_email.date", "STRING_TYPE", "date", "%");
$tfi_listspam_email3->addColumn("magazins.id_magazin", "NUMERIC_TYPE", "id_magazin", "=");
$tfi_listspam_email3->addColumn("produits.id", "NUMERIC_TYPE", "id", "=");
$tfi_listspam_email3->Execute();

// Sorter
$tso_listspam_email3 = new TSO_TableSorter("rsspam_email1", "tso_listspam_email3");
$tso_listspam_email3->addColumn("spam_email.id");
$tso_listspam_email3->addColumn("spam_email.nom");
$tso_listspam_email3->addColumn("spam_email.maessage");
$tso_listspam_email3->addColumn("spam_email.date");
$tso_listspam_email3->addColumn("magazins.id_magazin");
$tso_listspam_email3->addColumn("produits.id");
$tso_listspam_email3->setDefault("spam_email.id DESC");
$tso_listspam_email3->Execute();

// Navigation
$nav_listspam_email3 = new NAV_Regular("nav_listspam_email3", "rsspam_email1", "../", $_SERVER['PHP_SELF'], 20);

//NeXTenesio3 Special List Recordset
$maxRows_rsspam_email1 = $_SESSION['max_rows_nav_listspam_email3'];
$pageNum_rsspam_email1 = 0;
if (isset($_GET['pageNum_rsspam_email1'])) {
  $pageNum_rsspam_email1 = $_GET['pageNum_rsspam_email1'];
}
$startRow_rsspam_email1 = $pageNum_rsspam_email1 * $maxRows_rsspam_email1;

// Defining List Recordset variable
$NXTFilter_rsspam_email1 = "1=1";
if (isset($_SESSION['filter_tfi_listspam_email3'])) {
  $NXTFilter_rsspam_email1 = $_SESSION['filter_tfi_listspam_email3'];
}
// Defining List Recordset variable
$NXTSort_rsspam_email1 = "spam_email.id DESC";
if (isset($_SESSION['sorter_tso_listspam_email3'])) {
  $NXTSort_rsspam_email1 = $_SESSION['sorter_tso_listspam_email3'];
}
mysql_select_db($database_magazinducoin, $magazinducoin);

$query_rsspam_email1 = "SELECT
    magazins.nom_magazin
    , spam_email.nom
    , spam_email.email
    , spam_email.maessage
    , spam_email.date
    , spam_email.id_produit
    , spam_email.id_magazin
FROM
    spam_email
    INNER JOIN magazins 
        ON (spam_email.id_magazin = magazins.id_magazin)
WHERE {$NXTFilter_rsspam_email1} ORDER BY {$NXTSort_rsspam_email1}";
$query_limit_rsspam_email1 = sprintf("%s LIMIT %d, %d", $query_rsspam_email1, $startRow_rsspam_email1, $maxRows_rsspam_email1);
$rsspam_email1 = mysql_query($query_limit_rsspam_email1, $magazinducoin) or die(mysql_error());
$row_rsspam_email1 = mysql_fetch_assoc($rsspam_email1);
//echo $query_limit_rsspam_email1;

if (isset($_GET['totalRows_rsspam_email1'])) {
  $totalRows_rsspam_email1 = $_GET['totalRows_rsspam_email1'];
} else {
  $all_rsspam_email1 = mysql_query($query_rsspam_email1);
  $totalRows_rsspam_email1 = mysql_num_rows($all_rsspam_email1);
}
$totalPages_rsspam_email1 = ceil($totalRows_rsspam_email1/$maxRows_rsspam_email1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listspam_email3->checkBoundries();
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
	<script src="http://code.jquery.com/jquery-latest.js" type="text/javascript"></script>
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
  row_effects: false,
  show_as_buttons: true,
  record_counter: true
}
    </script>


</head>
<body id="sp">
<?php include("modules/header.php"); ?>

<div class="content_wrapper_sbr">
	<div>
  		<div id="content">
          <div class="KT_tng" id="listspam_email3">
            <h1> Email Spam
              <?php
  $nav_listspam_email3->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
            </h1>
            <div class="KT_tnglist">
            	<?php if($_GET['info']=='ACTIVATED') { ?><div class="succes">approve!!</div><?php } ?>
				<?php if($_GET['info']=='UNACTIVATED') { ?><div class="unsucces">no approve!!</div><?php } ?>
              <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
                <div class="KT_options"> <a href="<?php echo $nav_listspam_email3->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
                  <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listspam_email3'] == 1) {
?>
                    <?php echo $_SESSION['default_max_rows_nav_listspam_email3']; ?>
                    <?php 
  // else Conditional region1
  } else { ?>
                    <?php echo NXT_getResource("all"); ?>
                    <?php } 
  // endif Conditional region1
?>
                      <?php echo NXT_getResource("records"); ?></a> &nbsp;

                </div>
                <table cellpadding="2" cellspacing="0" class="KT_tngtable" style="width:1000px;">
                  <thead>
                    <tr class="KT_row_order">
                      <!--<th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
                      </th>-->
                      <th class="KT_sorter  <?php echo $tso_listspam_email3->getSortIcon('spam_email.nom'); ?>"> <a href="<?php echo $tso_listspam_email3->getSortLink('spam_email.nom'); ?>">Nom </a> </th>
                      <th class="KT_sorter  <?php echo $tso_listspam_email3->getSortIcon('spam_email.email'); ?>"> <a href="<?php echo $tso_listspam_email3->getSortLink('spam_email.email'); ?>">Email</a> </th>
                      <th class="KT_sorter  <?php echo $tso_listspam_email3->getSortIcon('spam_email.maessage'); ?>"> <a href="<?php echo $tso_listspam_email3->getSortLink('spam_email.maessage'); ?>">Maessage</a> </th>
                      <th class="KT_sorter  <?php echo $tso_listspam_email3->getSortIcon('spam_email.date'); ?>"> <a href="<?php echo $tso_listspam_email3->getSortLink('spam_email.date'); ?>">Date</a> </th>
                      <th class="KT_sorter "> Magasin </th>
                      <th class="KT_sorter "> Produit </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if ($totalRows_rsspam_email1 == 0) { // Show if recordset empty ?>
                      <tr>
                        <td colspan="12"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
                      </tr>
                      <?php } // Show if recordset empty ?>
                    <?php if ($totalRows_rsspam_email1 > 0) { // Show if recordset not empty ?>
                      <?php do { ?>
                        <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                          <td style="white-space: normal;"><div class="KT_col_nom"><?php echo $row_rsspam_email1['nom']; ?></div></td>
                          <td style="white-space: normal;"><div class="KT_col_email"><?php echo $row_rsspam_email1['email']; ?></div></td>
                          <td style="white-space: normal;"><div class="KT_col_email"><?php echo $row_rsspam_email1['maessage']; ?></div></td>
                          <td style="white-space: normal;"><div class="KT_col_email"><?php echo $row_rsspam_email1['date']; ?></div></td>
                          <td style="white-space: normal;"><div class="KT_col_nom_magazin">
						  		<?php 
									if(isset($row_rsspam_email1['id_magazin'])){
										mysql_select_db($database_magazinducoin, $magazinducoin);
										$query_Recordset1 = "SELECT nom_magazin FROM magazins WHERE id_magazin='".$row_rsspam_email1['id_magazin']."' ORDER BY nom_magazin";
										$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
										$row_Recordset1 = mysql_fetch_assoc($Recordset1);
										echo $row_Recordset1['nom_magazin'];
									} 
								?>
                          </div></td>
                          <td style="white-space: normal;"><div class="KT_col_nom_magazin">
						  		<?php 
									if(isset($row_rsspam_email1['id_produit'])){
										mysql_select_db($database_magazinducoin, $magazinducoin);
										$query_Recordset1 = "SELECT titre FROM produits WHERE id='".$row_rsspam_email1['id_produit']."' ";
										$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
										$row_Recordset1 = mysql_fetch_assoc($Recordset1);
										echo $row_Recordset1['titre'];
									} 
								?>
                          </div></td>
                          
                        </tr>
                        <?php } while ($row_rsspam_email1 = mysql_fetch_assoc($rsspam_email1)); ?>
                      <?php } // Show if recordset not empty ?>
                  </tbody>
                </table>
                <div class="KT_bottomnav">
                  <div>
                    <?php
            $nav_listspam_email3->Prepare();
            require("../includes/nav/NAV_Text_Navigation.inc.php");
          ?>
                  </div>
                </div>
                <div class="KT_bottombuttons">
                	
                </div>
              </form>
            </div>
            <br class="clearfixplain" />
          </div>
          <p>&nbsp;</p>
  		</div>
	</div>
</div>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);

mysql_free_result($Recordset3);

mysql_free_result($Recordset4);

mysql_free_result($rsspam_email1);
?>