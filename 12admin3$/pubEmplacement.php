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
$tfi_listpub_emplacement1 = new TFI_TableFilter($conn_magazinducoin, "tfi_listpub_emplacement1");
$tfi_listpub_emplacement1->addColumn("pub_emplacement.titre", "STRING_TYPE", "titre", "%");
$tfi_listpub_emplacement1->addColumn("pub_emplacement.prix", "DOUBLE_TYPE", "prix", "=");
$tfi_listpub_emplacement1->Execute();

// Sorter
$tso_listpub_emplacement1 = new TSO_TableSorter("rspub_emplacement1", "tso_listpub_emplacement1");
$tso_listpub_emplacement1->addColumn("pub_emplacement.titre");
$tso_listpub_emplacement1->addColumn("pub_emplacement.prix");
$tso_listpub_emplacement1->setDefault("pub_emplacement.titre");
$tso_listpub_emplacement1->Execute();

// Navigation
$nav_listpub_emplacement1 = new NAV_Regular("nav_listpub_emplacement1", "rspub_emplacement1", "../", $_SERVER['PHP_SELF'], 20);

//NeXTenesio3 Special List Recordset
$maxRows_rspub_emplacement1 = $_SESSION['max_rows_nav_listpub_emplacement1'];
$pageNum_rspub_emplacement1 = 0;
if (isset($_GET['pageNum_rspub_emplacement1'])) {
  $pageNum_rspub_emplacement1 = $_GET['pageNum_rspub_emplacement1'];
}
$startRow_rspub_emplacement1 = $pageNum_rspub_emplacement1 * $maxRows_rspub_emplacement1;

// Defining List Recordset variable
$NXTFilter_rspub_emplacement1 = "1=1";
if (isset($_SESSION['filter_tfi_listpub_emplacement1'])) {
  $NXTFilter_rspub_emplacement1 = $_SESSION['filter_tfi_listpub_emplacement1'];
}
// Defining List Recordset variable
$NXTSort_rspub_emplacement1 = "pub_emplacement.id";
if (isset($_SESSION['sorter_tso_listpub_emplacement1'])) {
  $NXTSort_rspub_emplacement1 = $_SESSION['sorter_tso_listpub_emplacement1'];
}
mysql_select_db($database_magazinducoin, $magazinducoin);

$query_rspub_emplacement1 = "SELECT pub_emplacement.titre, pub_emplacement.prix, pub_emplacement.id, pub_emplacement.date_debut, pub_emplacement.type, pub_emplacement.sub_type, pub_emplacement.description FROM pub_emplacement WHERE {$NXTFilter_rspub_emplacement1} ORDER BY {$NXTSort_rspub_emplacement1}";
$query_limit_rspub_emplacement1 = sprintf("%s LIMIT %d, %d", $query_rspub_emplacement1, $startRow_rspub_emplacement1, $maxRows_rspub_emplacement1);
$rspub_emplacement1 = mysql_query($query_limit_rspub_emplacement1, $magazinducoin) or die(mysql_error());
$row_rspub_emplacement1 = mysql_fetch_assoc($rspub_emplacement1);

if (isset($_GET['totalRows_rspub_emplacement1'])) {
  $totalRows_rspub_emplacement1 = $_GET['totalRows_rspub_emplacement1'];
} else {
  $all_rspub_emplacement1 = mysql_query($query_rspub_emplacement1);
  $totalRows_rspub_emplacement1 = mysql_num_rows($all_rspub_emplacement1);
}
$totalPages_rspub_emplacement1 = ceil($totalRows_rspub_emplacement1/$maxRows_rspub_emplacement1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listpub_emplacement1->checkBoundries();
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
  .KT_col_titre {width:140px; overflow:hidden;}
  .KT_col_prix {width:140px; overflow:hidden;}
  
  a.popupwindow{text-decoration:none; color:#9D216E !important;}
  a.popupwindow:hover{ color:#F8C263 !important}
  
 /* span.tooltip {outline:none; color:#9D216E; } 
  span.tooltip strong {line-height:30px;} 
  span.tooltip:hover {text-decoration:none; cursor:pointer;} 
  span.tooltip div { z-index:10;display:none; padding:10px 15px; margin-top:-10px; margin-left:8px; line-height:16px; } 
  span.tooltip:hover div{ display:inline; position:absolute; color:#111; border:1px solid #DCA; background:#fffAF0;} 
  span.tooltip div {border-radius:4px; -moz-border-radius: 4px; -webkit-border-radius: 4px; -moz-box-shadow: 5px 5px 8px #CCC; -webkit-box-shadow: 5px 5px 8px #CCC; box-shadow: 5px 5px 8px #CCC; }
  */
    </style>
	<script type="text/javascript"src="http://code.jquery.com/jquery-latest.js"></script>
    <script type="text/javascript" src="../assets/popup_2/jquery.popupwindow.js"></script>
    <script type="text/javascript">
	var profiles =
	{

		window800:
		{
			height:800,
			width:800,
			status:1
		},

		window200:
		{
			height:200,
			width:200,
			status:1,
			resizable:0
		},

		windowCenter:
		{
			height:300,
			width:400,
			center:1
		},

		windowNotNew:
		{
			height:300,
			width:400,
			center:1,
			createnew:0
		},

		windowCallUnload:
		{
			height:300,
			width:400,
			center:1,
			onUnload:unloadcallback
		},

	};

	function unloadcallback(){
		alert("unloaded");
	};


   	$(function()
	{
   		$(".popupwindow").popupwindow(profiles);
   	});
	</script>
</head>
<body id="sp">
<?php include("modules/header.php"); ?>

<div class="content_wrapper_sbr">
	<div>
  		<div id="content">
          <div class="KT_tng" id="listpub_emplacement1">
            <h1> Emplacement de pub
              <?php
  $nav_listpub_emplacement1->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
            </h1>
            <div class="KT_tnglist">
              <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
                <div class="KT_options"> <a href="<?php echo $nav_listpub_emplacement1->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
                  <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listpub_emplacement1'] == 1) {
?>
                    <?php echo $_SESSION['default_max_rows_nav_listpub_emplacement1']; ?>
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
                      <th id="titre" class="KT_sorter KT_col_titre <?php echo $tso_listpub_emplacement1->getSortIcon('pub_emplacement.titre'); ?>"> <a href="<?php echo $tso_listpub_emplacement1->getSortLink('pub_emplacement.titre'); ?>">Titre</a> </th>
                      <th id="prix" class="KT_sorter KT_col_prix <?php echo $tso_listpub_emplacement1->getSortIcon('pub_emplacement.prix'); ?>"> <a href="<?php echo $tso_listpub_emplacement1->getSortLink('pub_emplacement.prix'); ?>">Prix</a> </th>
                      <th>Date</th>
                      <th>Type</th>
                      <th>Sub Type</th>
                      <th>&nbsp;</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if ($totalRows_rspub_emplacement1 == 0) { // Show if recordset empty ?>
                      <tr>
                        <td colspan="4"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
                      </tr>
                      <?php } // Show if recordset empty ?>
                    <?php if ($totalRows_rspub_emplacement1 > 0) { // Show if recordset not empty ?>
                      <?php do { ?>
                        <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                          <td><input type="checkbox" name="kt_pk_pub_emplacement" class="id_checkbox" value="<?php echo $row_rspub_emplacement1['id']; ?>" />
                              <input type="hidden" name="id" class="id_field" value="<?php echo $row_rspub_emplacement1['id']; ?>" />
                          </td>
                          <td><?php echo $row_rspub_emplacement1['titre']; ?> <?php if($row_rspub_emplacement1['description']!=''){?><a href="../assets/popup_2/pub.php?id=<?php echo $row_rspub_emplacement1['id'];?>" class="popupwindow" rel="windowCenter">(?)</a><?php }?></td>
                          <td><div class="KT_col_prix"><?php echo $row_rspub_emplacement1['prix']; ?></div></td>
                          <td><div class="KT_col_prix"><?php echo $row_rspub_emplacement1['date_debut']; ?></div></td>
                          <td><div class="KT_col_prix">
						  	<?php if($row_rspub_emplacement1['type']=='1') echo"Magazin"; ?>
                           	<?php if($row_rspub_emplacement1['type']=='2') echo"Produit"; ?>
                           	<?php if($row_rspub_emplacement1['type']=='3') echo"Coupon"; ?>
                           	<?php if($row_rspub_emplacement1['type']=='4') echo"Événements"; ?>
                           </div></td>
                          <td>
						  	<?php if($row_rspub_emplacement1['sub_type']=='1') echo"Pay"; ?>
                           	<?php if($row_rspub_emplacement1['sub_type']=='2') echo"Mettre en avant ce coupon"; ?>
                           	<?php if($row_rspub_emplacement1['sub_type']=='3') echo"Remonter le coupon en tête de liste"; ?>
                           	<?php if($row_rspub_emplacement1['sub_type']=='4') echo"Publication express"; ?>
                           	<?php if($row_rspub_emplacement1['sub_type']=='5') echo"Banner Région"; ?>
                           	<?php if($row_rspub_emplacement1['sub_type']=='6') echo"Banner National"; ?>
                          
                          </td>
                          <td><a class="KT_edit_link" href="formPubEmplacement.php?id=<?php echo $row_rspub_emplacement1['id']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a> </td>
                        </tr>
                        <?php } while ($row_rspub_emplacement1 = mysql_fetch_assoc($rspub_emplacement1)); ?>
                      <?php } // Show if recordset not empty ?>
                  </tbody>
                </table>
                <div class="KT_bottomnav">
                  <div>
                    <?php
            $nav_listpub_emplacement1->Prepare();
            require("../includes/nav/NAV_Text_Navigation.inc.php");
          ?>
                  </div>
                </div>
                <div class="KT_bottombuttons">
                  <div class="KT_operations"> <a class="KT_edit_op_link" href="#" onClick="nxt_list_edit_link_form(this); return false;"><?php echo NXT_getResource("edit_all"); ?></a> <a class="KT_delete_op_link" href="#" onClick="nxt_list_delete_link_form(this); return false;"><?php echo NXT_getResource("delete_all"); ?></a> </div>
                  <span>&nbsp;</span>
                  <select name="no_new" id="no_new">
                    <option value="1">1</option>
                    <option value="3">3</option>
                    <option value="6">6</option>
                  </select>
                  <a class="KT_additem_op_link" href="formPubEmplacement.php?KT_back=1" onClick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a> </div>
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
mysql_free_result($rspub_emplacement1);
?>