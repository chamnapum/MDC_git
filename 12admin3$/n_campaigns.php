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
$tfi_listcampaigns1 = new TFI_TableFilter($conn_magazinducoin, "tfi_listcampaigns1");
$tfi_listcampaigns1->addColumn("n_campaign.id", "NUMERIC_TYPE", "id", "=");
$tfi_listcampaigns1->addColumn("n_campaign.name", "STRING_TYPE", "name", "%");
$tfi_listcampaigns1->addColumn("n_campaign.subject", "STRING_TYPE", "subject", "%");
$tfi_listcampaigns1->Execute();

// Sorter
$tso_listcampaigns1 = new TSO_TableSorter("rscampaigns1", "tso_listcampaigns1");
$tso_listcampaigns1->addColumn("n_campaign.name");
$tso_listcampaigns1->addColumn("n_campaign.subject");
$tso_listcampaigns1->Execute();

// Navigation
if(isset($_GET['mag_per_page'])){$nb_par_page=$_GET['mag_per_page'];}else{$nb_par_page=10;}
$nav_listcampaigns1 = new NAV_Regular("nav_listcampaigns1", "rscampaigns1", "../", $_SERVER['PHP_SELF'], $nb_par_page);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset1 = "SELECT email, id FROM utilisateur ORDER BY email";
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset2 = "SELECT nom_region, id_region FROM region ORDER BY nom_region";
$Recordset2 = mysql_query($query_Recordset2, $magazinducoin) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

//NeXTenesio3 Special List Recordset
$maxRows_rscampaigns1 = $_SESSION['max_rows_nav_listcampaigns1'];
$pageNum_rscampaigns1 = 0;
if (isset($_GET['pageNum_rscampaigns1'])) {
  $pageNum_rscampaigns1 = $_GET['pageNum_rscampaigns1'];
}
$startRow_rscampaigns1 = $pageNum_rscampaigns1 * $maxRows_rscampaigns1;

// Defining List Recordset variable
$NXTFilter_rscampaigns1 = "1=1";
if (isset($_SESSION['filter_tfi_listcampaigns1'])) {
  $NXTFilter_rscampaigns1 = $_SESSION['filter_tfi_listcampaigns1'];
}
// Defining List Recordset variable
$NXTSort_rscampaigns1 = "id";
if (isset($_SESSION['sorter_tso_listcampaigns1'])) {
  $NXTSort_rscampaigns1 = $_SESSION['sorter_tso_listcampaigns1'];
}
mysql_select_db($database_magazinducoin, $magazinducoin);

$query_rscampaigns1 = "SELECT * FROM n_campaign   
WHERE {$NXTFilter_rscampaigns1} ORDER BY {$NXTSort_rscampaigns1}";
$query_limit_rscampaigns1 = sprintf("%s LIMIT %d, %d", $query_rscampaigns1, $startRow_rscampaigns1, $maxRows_rscampaigns1);
$rscampaigns1 = mysql_query($query_limit_rscampaigns1, $magazinducoin) or die(mysql_error());
$row_rscampaigns1 = mysql_fetch_assoc($rscampaigns1);
//echo $query_limit_rscampaigns1;
if (isset($_GET['totalRows_rscampaigns1'])) {
  $totalRows_rscampaigns1 = $_GET['totalRows_rscampaigns1'];
} else {
  $all_rscampaigns1 = mysql_query($query_rscampaigns1);
  $totalRows_rscampaigns1 = mysql_num_rows($all_rscampaigns1);
}
$totalPages_rscampaigns1 = ceil($totalRows_rscampaigns1/$maxRows_rscampaigns1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listcampaigns1->checkBoundries();
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
  row_effects: true,
  show_as_buttons: true,
  record_counter: true
}
    </script>
    
    <script type="text/javascript">
		$(document).ready(function(){
			
			$( "#mag_per_page" ).change(function() {
				var mag_per_page = $("#mag_per_page").val();
				document.location.href = 'magasins.php?mag_per_page='+mag_per_page; //relative to domain
			});
			
			$('#approv').click(function(){
				var final = '';
				var i = 0;
				var len = $('.id_checkbox:checked').length;
				      
				$('.id_checkbox:checked').each(function(){
					i++;
					var id = $(this).val();
					var email = $(this).attr("rel");
					var magasin = $(this).attr("magasin");
					var dataString = 'id='+id+'&email='+email;
					$.ajax({
							type: "POST",
							url: "approvMagasins.php",
							data: dataString,
							cache: false,
							success: function(datas){
								if(len == i){
									window.location.href = 'magasins.php';
								}
							}
						});	
				});
				return false;
				//window.location.href = 'magasins.php';
				//alert(final);
			});
		});
    </script>
    
    <style type="text/css">
  /* Dynamic List row settings */
  .KT_col_id_user {width:140px; overflow:hidden;}
  .KT_col_nom_magazin {width:140px; overflow:hidden;}
  .KT_col_region {width:140px; overflow:hidden;}
  .KT_col_photo1 {width:140px; overflow:hidden;}
    </style>
</head>
<body id="sp">
<?php include("modules/header.php"); ?>

<div class="content_wrapper_sbr">
	<a href="n_campaigns.php">Campaigns</a>
    <a href="n_list.php">Lists</a>
    <a href="n_template.php">Templates</a>
	<div>
  		<div id="content">
          <div class="KT_tng" id="listcampaigns1">
            <h1> Campaign
              <?php
  $nav_listcampaigns1->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
            </h1>
            <div class="KT_tnglist">
            	<?php if($_GET['info']=='ACTIVATED') { ?><div class="succes">Send successfully</div><?php } ?>
				<?php if($_GET['info']=='UNACTIVATED') { ?><div class="unsucces">no approve!!</div><?php } ?>
              <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
                <div class="KT_options"> <a href="<?php echo $nav_listcampaigns1->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
                  <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listcampaigns1'] == 1) {
?>
                    <?php echo $_SESSION['default_max_rows_nav_listcampaigns1']; ?>
                    <?php 
  // else Conditional region1
  } else { ?>
                    <?php echo NXT_getResource("all"); ?>
                    <?php } 
  // endif Conditional region1
?>
                      <?php echo NXT_getResource("records"); ?></a> &nbsp;
                  &nbsp;
  <?php /*?>              <?php 
  // Show IF Conditional region2
  if (@$_SESSION['has_filter_tfi_listcampaigns1'] == 1) {
?>
                  <a href="<?php echo $tfi_listcampaigns1->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
                  <?php 
  // else Conditional region2
  } else { ?>
                  <a href="<?php echo $tfi_listcampaigns1->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
                  <?php } 
  // endif Conditional region2
?><?php */?>
 </div>
                <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                  <thead>
                    <tr class="KT_row_order">
                      <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
                      </th>
                      <th id="id_user" class="KT_sorter KT_col_id_user <?php echo $tso_listcampaigns1->getSortIcon('n_campaign.name'); ?>"> <a href="<?php echo $tso_listcampaigns1->getSortLink('n_campaign.name'); ?>">Titre</a> </th>
                      <th id="nom_magazin" class="KT_sorter KT_col_nom_magazin <?php echo $tso_listcampaigns1->getSortIcon('n_campaign.subject'); ?>"> <a href="<?php echo $tso_listcampaigns1->getSortLink('n_campaign.subject'); ?>">Content</a> </th>
                      <th id="ville">From Name </th>
                      <th id="ville">Date </th>
                    </tr>
                   
                  </thead>
                  
                  <tbody>
                    <?php if ($totalRows_rscampaigns1 == 0) { // Show if recordset empty ?>
                      <tr>
                        <td colspan="6"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
                      </tr>
                      <?php } // Show if recordset empty ?>
                    <?php if ($totalRows_rscampaigns1 > 0) { // Show if recordset not empty ?>
                      <?php do { ?>
                        <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                          <td><input type="checkbox" name="kt_pk_magazins" class="id_checkbox" rel="<?php echo $row_rscampaigns1['id']; ?>" magasin="<?php echo $row_rscampaigns1['name']; ?>" value="<?php echo $row_rscampaigns1['id']; ?>" />
                              <!--<input type="hidden" name="id_magazin" class="id_field" value="<?php echo $row_rscampaigns1['id_magazin']; ?>" />-->
                          </td>
                          <td><div class="KT_col_nom_magazin"><?php echo ($row_rscampaigns1['name']); ?></div></td>
                          <td><div class="KT_col_region"><?php echo ($row_rscampaigns1['subject']); ?></div></td>
                          <td><div class="KT_col_region"><?php echo ($row_rscampaigns1['from_name']); ?></div></td>
                          <td><div class="KT_col_region"><?php echo ($row_rscampaigns1['date_process']); ?></div></td>
                          
                          <?php /*?><td><a class="KT_edit_link" href="n_form_campaign.php?id=<?php echo $row_rscampaigns1['id']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a>  
							
                          </td><?php */?>
                        </tr>
                        <?php } while ($row_rscampaigns1 = mysql_fetch_assoc($rscampaigns1)); ?>
                      <?php } // Show if recordset not empty ?>
                  </tbody>
                </table>
                <div class="KT_bottomnav">
                  <div>
                    <?php
            $nav_listcampaigns1->Prepare();
            require("../includes/nav/NAV_Text_Navigation.inc.php");
          ?>
                  </div>
                </div>
                <div class="KT_bottombuttons">

<span>&nbsp;</span>
                  <!--<select name="no_new" id="no_new">
                    <option value="1">1</option>
                    <option value="3">3</option>
                    <option value="6">6</option>
                  </select>-->
                  <a class="KT_additem_op_link" href="n_form_campaign.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a> </div>
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

mysql_free_result($rscampaigns1);
?>