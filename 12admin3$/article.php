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
$tfi_lisarticles1 = new TFI_TableFilter($conn_magazinducoin, "tfi_lisarticles1");
$tfi_lisarticles1->addColumn("article.id_article", "NUMERIC_TYPE", "id_article", "=");
$tfi_lisarticles1->addColumn("article.titre", "STRING_TYPE", "titre", "%");
$tfi_lisarticles1->addColumn("article.image", "STRING_TYPE", "image", "%");
$tfi_lisarticles1->addColumn("article.excerpt", "STRING_TYPE", "excerpt", "%");
$tfi_lisarticles1->Execute();

// Sorter
$tso_listarticles1 = new TSO_TableSorter("rsarticles1", "tso_listarticles1");
$tso_listarticles1->addColumn("article.titre");
$tso_listarticles1->addColumn("article.image");
$tso_listarticles1->addColumn("article.excerpt");
$tso_listarticles1->Execute();

// Navigation
if(isset($_GET['mag_per_page'])){$nb_par_page=$_GET['mag_per_page'];}else{$nb_par_page=10;}
$nav_listarticles1 = new NAV_Regular("nav_listarticles1", "rsarticles1", "../", $_SERVER['PHP_SELF'], $nb_par_page);

//NeXTenesio3 Special List Recordset
$maxRows_rsarticles1 = $_SESSION['max_rows_nav_listarticles1'];
$pageNum_rsarticles1 = 0;
if (isset($_GET['pageNum_rsarticles1'])) {
  $pageNum_rsarticles1 = $_GET['pageNum_rsarticles1'];
}
$startRow_rsarticles1 = $pageNum_rsarticles1 * $maxRows_rsarticles1;

// Defining List Recordset variable
$NXTFilter_rsarticles1 = "1=1";
if (isset($_SESSION['filter_tfi_lisarticles1'])) {
  $NXTFilter_rsarticles1 = $_SESSION['filter_tfi_lisarticles1'];
}
// Defining List Recordset variable
$NXTSort_rsarticles1 = "article.id_article";
if (isset($_SESSION['sorter_tso_listarticles1'])) {
  $NXTSort_rsarticles1 = $_SESSION['sorter_tso_listarticles1'];
}
mysql_select_db($database_magazinducoin, $magazinducoin);

$query_rsarticles1 = "SELECT * FROM article WHERE {$NXTFilter_rsarticles1} ORDER BY {$NXTSort_rsarticles1}";
$query_limit_rsarticles1 = sprintf("%s LIMIT %d, %d", $query_rsarticles1, $startRow_rsarticles1, $maxRows_rsarticles1);
$rsarticles1 = mysql_query($query_limit_rsarticles1, $magazinducoin) or die(mysql_error());
$row_rsarticles1 = mysql_fetch_assoc($rsarticles1);

if (isset($_GET['totalRows_rsarticles1'])) {
  $totalRows_rsarticles1 = $_GET['totalRows_rsarticles1'];
} else {
  $all_rsarticles1 = mysql_query($query_rsarticles1);
  $totalRows_rsarticles1 = mysql_num_rows($all_rsarticles1);
}
$totalPages_rsarticles1 = ceil($totalRows_rsarticles1/$maxRows_rsarticles1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listarticles1->checkBoundries();
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
				document.location.href = 'article.php?mag_per_page='+mag_per_page; //relative to domain
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
									window.location.href = 'article.php';
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
  .KT_col_titre {width:240px; overflow:hidden;}
  .KT_col_nom_magazin {width:140px; overflow:hidden;}
  .KT_col_excerpt {width:340px; overflow:hidden;}
  .KT_col_photo1 {width:140px; overflow:hidden;}
    </style>
</head>
<body id="sp">
<?php include("modules/header.php"); ?>

<div class="content_wrapper_sbr">
	<div>
  		<div id="content">
          <div class="KT_tng" id="listarticles1">
            <h1> Articles
              <?php
  $nav_listarticles1->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
            </h1>
            <div class="KT_tnglist">
            	<?php if($_GET['info']=='ACTIVATED') { ?><div class="succes">approve!!</div><?php } ?>
				<?php if($_GET['info']=='UNACTIVATED') { ?><div class="unsucces">no approve!!</div><?php } ?>
              <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
                <div class="KT_options"> <a href="<?php echo $nav_listarticles1->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
                  <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listarticles1'] == 1) {
?>
                    <?php echo $_SESSION['default_max_rows_nav_listarticles1']; ?>
                    <?php 
  // else Conditional region1
  } else { ?>
                    <?php echo NXT_getResource("all"); ?>
                    <?php } 
  // endif Conditional region1
?>
                      <?php echo NXT_getResource("records"); ?></a> &nbsp;
                  &nbsp;
                <?php 
  // Show IF Conditional region2
  if (@$_SESSION['has_filter_tfi_lisarticles1'] == 1) {
?>
                  <a href="<?php echo $tfi_lisarticles1->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
                  <?php 
  // else Conditional region2
  } else { ?>
                  <a href="<?php echo $tfi_lisarticles1->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
                  <?php } 
  // endif Conditional region2
?> </div>
                <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                  <thead>
                    <tr class="KT_row_order">
                      <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
                      </th>
                      <th id="titre" class="KT_sorter KT_col_titre <?php echo $tso_listarticles1->getSortIcon('article.titre'); ?>"> <a href="<?php echo $tso_listarticles1->getSortLink('article.titre'); ?>">Titre</a> </th>
                      <th id="nom_magazin" class="KT_sorter KT_col_image <?php echo $tso_listarticles1->getSortIcon('article.image'); ?>"> <a href="<?php echo $tso_listarticles1->getSortLink('article.image'); ?>">Image</a> </th>
                      <th id="region" class="KT_sorter KT_col_excerpt <?php echo $tso_listarticles1->getSortIcon('article.excerpt'); ?>"> <a href="<?php echo $tso_listarticles1->getSortLink('article.excerpt'); ?>">excerpt</a> </th>
                     <th>&nbsp;</th>
                    </tr>
                    <?php if (@$_SESSION['has_filter_tfi_lisarticles1'] == 1) { ?>
                    
                    <tr class="KT_row_filter">
                        <td>&nbsp;</td>
                        <td><input type="text" name="tfi_lisarticles1_titre" id="tfi_lisarticles1_titre" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_lisarticles1_titre']); ?>" size="20" maxlength="255" /></td>
                        <td></td>
                        <td><input type="text" name="tfi_lisarticles1_excerpt" id="tfi_lisarticles1_excerpt" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_lisarticles1_excerpt']); ?>" size="20" maxlength="255" /></td>
                       
                        <td><input type="submit" name="tfi_lisarticles1" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
                    </tr>
                    <?php }?>
                  </thead>
                  
                  <tbody>
                    <?php if ($totalRows_rsarticles1 == 0) { // Show if recordset empty ?>
                      <tr>
                        <td colspan="6"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
                      </tr>
                      <?php } // Show if recordset empty ?>
                    <?php if ($totalRows_rsarticles1 > 0) { // Show if recordset not empty ?>
                      <?php do { ?>
                        <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                          <td><input type="checkbox" name="kt_pk_articles" class="id_checkbox" rel="<?php echo $row_rsarticles1['id_article']; ?>" value="<?php echo $row_rsarticles1['id_article']; ?>" />
                              
                          </td>
                          <td><div class="KT_col_titre"><?php echo $row_rsarticles1['titre']; ?></div></td>
                          <td><div class="KT_col_image"><img src="../timthumb.php?src=assets/images/blog/<?php echo $row_rsarticles1['image']; ?>&z=1&w=60&h=40" /></div></td>
                          <td><div class="KT_col_excerpt"><?php echo KT_FormatForList($row_rsarticles1['excerpt'],220); ?></div></td>
                          <td><a class="KT_edit_link" href="formArticles.php?id_article=<?php echo $row_rsarticles1['id_article']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a> 
						  </td>
                        </tr>
                        <?php } while ($row_rsarticles1 = mysql_fetch_assoc($rsarticles1)); ?>
                      <?php } // Show if recordset not empty ?>
                  </tbody>
                </table>
                <div class="KT_bottomnav">
                  <div>
					<?php
                    $nav_listarticles1->Prepare();
                    require("../includes/nav/NAV_Text_Navigation.inc.php");
                    ?>
                  </div>
                </div>
                <div class="KT_bottombuttons">
                  <div class="KT_operations">
                   <a class="KT_delete_op_link" href="#" onclick="nxt_list_delete_link_form(this); return false;"><?php echo NXT_getResource("delete_all"); ?></a> 
                    <select name="mag_per_page" id="mag_per_page" style="width:54px;height:25px;padding:0px;">
                    	<option <?php if($nb_par_page == 10) echo "SELECTED"; ?>>10</option>
                        <option <?php if($nb_par_page == 20) echo "SELECTED"; ?>>20</option>
                        <option <?php if($nb_par_page == 50) echo "SELECTED"; ?>>50</option>
                        <option <?php if($nb_par_page == 100) echo "SELECTED"; ?>>100</option>
                    </select>
                    </div>
					<span>&nbsp;</span>
                  <a class="KT_additem_op_link" href="formArticles.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a> </div>
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

mysql_free_result($rsarticles1);
?>