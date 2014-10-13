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
$tfi_listlists1 = new TFI_TableFilter($conn_magazinducoin, "tfi_listlists1");
$tfi_listlists1->addColumn("utilisateur.id", "NUMERIC_TYPE", "id", "=");
$tfi_listlists1->addColumn("utilisateur.email", "STRING_TYPE", "email", "%");
$tfi_listlists1->Execute();

// Sorter
$tso_listlists1 = new TSO_TableSorter("rslists1", "tso_listlists1");
$tso_listlists1->addColumn("utilisateur.email");
$tso_listlists1->Execute();

// Navigation
if(isset($_GET['mag_per_page'])){$nb_par_page=$_GET['mag_per_page'];}else{$nb_par_page=10;}
$nav_listlists1 = new NAV_Regular("nav_listlists1", "rslists1", "../", $_SERVER['PHP_SELF'], $nb_par_page);

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
$maxRows_rslists1 = $_SESSION['max_rows_nav_listlists1'];
$pageNum_rslists1 = 0;
if (isset($_GET['pageNum_rslists1'])) {
  $pageNum_rslists1 = $_GET['pageNum_rslists1'];
}
$startRow_rslists1 = $pageNum_rslists1 * $maxRows_rslists1;

// Defining List Recordset variable
$NXTFilter_rslists1 = "1=1";
if (isset($_SESSION['filter_tfi_listlists1'])) {
  $NXTFilter_rslists1 = $_SESSION['filter_tfi_listlists1'];
}
// Defining List Recordset variable
$NXTSort_rslists1 = "id";
if (isset($_SESSION['sorter_tso_listlists1'])) {
  $NXTSort_rslists1 = $_SESSION['sorter_tso_listlists1'];
}
mysql_select_db($database_magazinducoin, $magazinducoin);

$query_rslists1 = "SELECT * FROM utilisateur   
WHERE {$NXTFilter_rslists1} AND subscribe='1' ORDER BY {$NXTSort_rslists1}";
$query_limit_rslists1 = sprintf("%s LIMIT %d, %d", $query_rslists1, $startRow_rslists1, $maxRows_rslists1);
$rslists1 = mysql_query($query_limit_rslists1, $magazinducoin) or die(mysql_error());
$row_rslists1 = mysql_fetch_assoc($rslists1);
//echo $query_limit_rslists1;
if (isset($_GET['totalRows_rslists1'])) {
  $totalRows_rslists1 = $_GET['totalRows_rslists1'];
} else {
  $all_rslists1 = mysql_query($query_rslists1);
  $totalRows_rslists1 = mysql_num_rows($all_rslists1);
}
$totalPages_rslists1 = ceil($totalRows_rslists1/$maxRows_rslists1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listlists1->checkBoundries();
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
          <div class="KT_tng" id="listlists1">
            <h1> Subscripe Liste
              <?php
  $nav_listlists1->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
            </h1>
            <div class="KT_tnglist">
            	<?php if($_GET['info']=='ACTIVATED') { ?><div class="succes">approve!!</div><?php } ?>
				<?php if($_GET['info']=='UNACTIVATED') { ?><div class="unsucces">no approve!!</div><?php } ?>
              <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
                <div class="KT_options"> <a href="<?php echo $nav_listlists1->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
                  <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listlists1'] == 1) {
?>
                    <?php echo $_SESSION['default_max_rows_nav_listlists1']; ?>
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
  if (@$_SESSION['has_filter_tfi_listlists1'] == 1) {
?>
                  <a href="<?php echo $tfi_listlists1->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
                  <?php 
  // else Conditional region2
  } else { ?>
                  <a href="<?php echo $tfi_listlists1->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
                  <?php } 
  // endif Conditional region2
?><?php */?>
 </div>
                <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                  <thead>
                    <tr class="KT_row_order">
                      <?php /*?><th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
                      </th><?php */?>
                      <?php /*?><th id="email" class="KT_sorter KT_col_email <?php echo $tso_listlists1->getSortIcon('utilisateur.email'); ?>"> <a href="<?php echo $tso_listlists1->getSortLink('utilisateur.email'); ?>">Titre</a> </th>
                      <th id="nom" class="KT_sorter KT_col_nom <?php echo $tso_listlists1->getSortIcon('utilisateur.nom'); ?>"> <a href="<?php echo $tso_listlists1->getSortLink('utilisateur.nom'); ?>">Nom</a> </th>
                      <?php */?>
                      <th id="email">Titre</th>
                      <th id="nom">Nom</th>
                      
                    </tr>
                   
                  </thead>
                  
                  <tbody>
                    <?php if ($totalRows_rslists1 == 0) { // Show if recordset empty ?>
                      <tr>
                        <td colspan="6"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
                      </tr>
                      <?php } // Show if recordset empty ?>
                    <?php if ($totalRows_rslists1 > 0) { // Show if recordset not empty ?>
                      <?php do { ?>
                        <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                          <?php /*?><td><input type="checkbox" name="kt_pk_magazins" class="id_checkbox" rel="<?php echo $row_rslists1['id']; ?>" magasin="<?php echo $row_rslists1['email']; ?>" value="<?php echo $row_rslists1['id']; ?>" />
                              <!--<input type="hidden" name="id_magazin" class="id_field" value="<?php echo $row_rslists1['id_magazin']; ?>" />-->
                          </td><?php */?>
                          <td><div class="KT_col_nom_magazin"><?php echo ($row_rslists1['email']); ?></div></td>
                          <td><div class="KT_col_region"><?php echo ($row_rslists1['nom']); ?></div></td>
                          
                          <!--<td><a class="KT_edit_link" href="n_form_teamplate.php?id=<?php echo $row_rslists1['id']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a>  
							
                          </td>-->
                        </tr>
                        <?php } while ($row_rslists1 = mysql_fetch_assoc($rslists1)); ?>
                      <?php } // Show if recordset not empty ?>
                  </tbody>
                </table>
                <div class="KT_bottomnav">
                  <div>
                    <?php
            $nav_listlists1->Prepare();
            require("../includes/nav/NAV_Text_Navigation.inc.php");
          ?>
                  </div>
                </div>
                <div class="KT_bottombuttons">
                  <?php /*?><div class="KT_operations">
                  	<input type="button" id="approv" value="TOUT Accepter" /> 
                    <!--<a class="KT_edit_op_link" href="#" onclick="nxt_list_edit_link_form(this); return false;"><?php echo NXT_getResource("edit_all"); ?></a>--> 
                    <a class="KT_delete_op_link" href="#" onclick="nxt_list_delete_link_form(this); return false;"><?php echo NXT_getResource("delete_all"); ?></a> 
                    <select name="mag_per_page" id="mag_per_page" style="width:54px;height:25px;padding:0px;">
                    	<option <?php if($nb_par_page == 10) echo "SELECTED"; ?>>10</option>
                        <option <?php if($nb_par_page == 20) echo "SELECTED"; ?>>20</option>
                        <option <?php if($nb_par_page == 50) echo "SELECTED"; ?>>50</option>
                        <option <?php if($nb_par_page == 100) echo "SELECTED"; ?>>100</option>
                    </select>
                    </div><?php */?>
<span>&nbsp;</span>
                  <!--<select name="no_new" id="no_new">
                    <option value="1">1</option>
                    <option value="3">3</option>
                    <option value="6">6</option>
                  </select>-->
                  <!--<a class="KT_additem_op_link" href="n_form_teamplate.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a>--> </div>
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

mysql_free_result($rslists1);
?>