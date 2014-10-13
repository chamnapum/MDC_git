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
$tfi_listcoupons3 = new TFI_TableFilter($conn_magazinducoin, "tfi_listcoupons3");
$tfi_listcoupons3->addColumn("coupons.reduction", "STRING_TYPE", "reduction", "%");
$tfi_listcoupons3->addColumn("coupons.date_debut", "DATE_TYPE", "date_debut", "=");
$tfi_listcoupons3->addColumn("coupons.date_fin", "DATE_TYPE", "date_fin", "=");
$tfi_listcoupons3->addColumn("utilisateur.id", "NUMERIC_TYPE", "id_user", "=");
$tfi_listcoupons3->addColumn("coupons.titre", "STRING_TYPE", "titre", "%");
$tfi_listcoupons3->addColumn("category.parent_id", "NUMERIC_TYPE", "categories", "=");
$tfi_listcoupons3->addColumn("category1.cat_id", "NUMERIC_TYPE", "sous_categorie", "=");
$tfi_listcoupons3->addColumn("magazins.id_magazin", "NUMERIC_TYPE", "id_magasin", "=");
$tfi_listcoupons3->addColumn("coupons.code_bare", "STRING_TYPE", "code_bare", "%");
$tfi_listcoupons3->addColumn("coupons.min_achat", "DOUBLE_TYPE", "min_achat", "=");
$tfi_listcoupons3->addColumn("coupons.description", "STRING_TYPE", "description", "%");
$tfi_listcoupons3->addColumn("coupons.approuve", "NUMERIC_TYPE", "approuve", "=");
$tfi_listcoupons3->Execute();

// Sorter
$tso_listcoupons3 = new TSO_TableSorter("rscoupons1", "tso_listcoupons3");
$tso_listcoupons3->addColumn("coupons.reduction");
$tso_listcoupons3->addColumn("coupons.date_debut");
$tso_listcoupons3->addColumn("coupons.date_fin");
$tso_listcoupons3->addColumn("utilisateur.email");
$tso_listcoupons3->addColumn("coupons.titre");
$tso_listcoupons3->addColumn("category.cat_name");
$tso_listcoupons3->addColumn("category1.cat_name");
$tso_listcoupons3->addColumn("magazins.nom_magazin");
$tso_listcoupons3->addColumn("coupons.code_bare");
$tso_listcoupons3->addColumn("coupons.min_achat");
$tso_listcoupons3->addColumn("coupons.description");
$tso_listcoupons3->addColumn("coupons.active");
$tso_listcoupons3->setDefault("coupons.date_debut DESC");
$tso_listcoupons3->Execute();

// Navigation
if(isset($_GET['mag_per_page'])){$nb_par_page=$_GET['mag_per_page'];}else{$nb_par_page=10;}

$nav_listcoupons3 = new NAV_Regular("nav_listcoupons3", "rscoupons1", "../", $_SERVER['PHP_SELF'], $nb_par_page);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset1 = "SELECT email, id FROM utilisateur ORDER BY email";
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset2 = "SELECT cat_name, parent_id FROM category ORDER BY cat_name";
$Recordset2 = mysql_query($query_Recordset2, $magazinducoin) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset3 = "SELECT cat_name, cat_id FROM category ORDER BY cat_name";
$Recordset3 = mysql_query($query_Recordset3, $magazinducoin) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

/*mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset4 = "SELECT nom_magazin, id_magazin FROM magazins ORDER BY nom_magazin";
$Recordset4 = mysql_query($query_Recordset4, $magazinducoin) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);*/

//NeXTenesio3 Special List Recordset
$maxRows_rscoupons1 = $_SESSION['max_rows_nav_listcoupons3'];
$pageNum_rscoupons1 = 0;
if (isset($_GET['pageNum_rscoupons1'])) {
  $pageNum_rscoupons1 = $_GET['pageNum_rscoupons1'];
}
$startRow_rscoupons1 = $pageNum_rscoupons1 * $maxRows_rscoupons1;

// Defining List Recordset variable
$NXTFilter_rscoupons1 = "1=1";
if (isset($_SESSION['filter_tfi_listcoupons3'])) {
  $NXTFilter_rscoupons1 = $_SESSION['filter_tfi_listcoupons3'];
}
// Defining List Recordset variable
$NXTSort_rscoupons1 = "coupons.reduction DESC";
if (isset($_SESSION['sorter_tso_listcoupons3'])) {
  $NXTSort_rscoupons1 = $_SESSION['sorter_tso_listcoupons3'];
}
mysql_select_db($database_magazinducoin, $magazinducoin);

$query_rscoupons1 = "SELECT coupons.id_coupon, coupons.approuve, coupons.description, coupons.active, coupons.sous_categorie AS sous_categorie__id, category_0.cat_name AS categorie, coupons.reduction, coupons.date_debut, coupons.date_fin, coupons.titre, coupons.min_achat, coupons.id_magasin, magazins.nom_magazin, magazins.photo1, category.cat_name AS sous_categorie, coupons.categories AS categorie_id, utilisateur.email, utilisateur.telephone, region.nom_region
FROM (((((coupons
LEFT JOIN category ON category.cat_id=coupons.sous_categorie)
LEFT JOIN magazins ON magazins.id_magazin=coupons.id_magasin)
LEFT JOIN category AS category_0 ON category_0.cat_id=coupons.categories)
LEFT JOIN utilisateur ON utilisateur.id=coupons.id_user)
LEFT JOIN region ON region.id_region = magazins.region)
 WHERE {$NXTFilter_rscoupons1} ORDER BY {$NXTSort_rscoupons1}";
$query_limit_rscoupons1 = sprintf("%s LIMIT %d, %d", $query_rscoupons1, $startRow_rscoupons1, $maxRows_rscoupons1);
$rscoupons1 = mysql_query($query_limit_rscoupons1, $magazinducoin) or die(mysql_error());
$row_rscoupons1 = mysql_fetch_assoc($rscoupons1);

if (isset($_GET['totalRows_rscoupons1'])) {
  $totalRows_rscoupons1 = $_GET['totalRows_rscoupons1'];
} else {
  $all_rscoupons1 = mysql_query($query_rscoupons1);
  $totalRows_rscoupons1 = mysql_num_rows($all_rscoupons1);
}
$totalPages_rscoupons1 = ceil($totalRows_rscoupons1/$maxRows_rscoupons1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listcoupons3->checkBoundries();
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
    <script src="../template/js/jquery.js" type="text/javascript"></script>
    
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
    
	<script type='text/javascript' src='../assets/autocomplete/jquery.autocomplete.js'></script>
	<link rel="stylesheet" type="text/css" href="../assets/autocomplete/jquery.autocomplete.css" />
    
    <script type="text/javascript">
		$(document).ready(function(){
			
			$("#magasin_name").autocomplete("../assets/autocomplete/magazin_coupon.php").result(function(e, data){
				$("#magasin_name").val(data[0]);
				$("#tfi_listcoupons3_id_magasin").val(data[1]);
			});
			
			$( "#mag_per_page" ).change(function() {
				var mag_per_page = $("#mag_per_page").val();
				document.location.href = 'coupons.php?mag_per_page='+mag_per_page; //relative to domain
			});
			
			$('#approv').click(function(){
				var final = '';
				var i = 0;
				var len = $('.id_checkbox:checked').length;
				$('.id_checkbox:checked').each(function(){ 
					i++;        
					var id = $(this).val();
					var email = $(this).attr("rel");
					var coupons = $(this).attr("coupons");
					alert('This coupons "'+coupons+'" have to approve');
					var dataString = 'id='+id+'&email='+email;
					$.ajax({
							type: "POST",
							url: "approvCoupons.php",
							data: dataString,
							cache: false,
							success: function(datas){
								if(len == i){
									window.location.href = 'coupons.php';
								}
							}
						});	
					
				});
				return false;
				//window.location.href = 'coupons.php';
				//alert(final);
			});
		});
    </script>
    <style type="text/css">
  /* Dynamic List row settings */
  .KT_col_date_debut {width:60px; overflow:hidden;}
  .KT_col_date_fin {width:60px; overflow:hidden;}
  .KT_col_id_user {width:120px; overflow:hidden;}
  .KT_col_titre {width:140px; overflow:hidden;}
  .KT_col_categories {width:140px; overflow:hidden;}
  .KT_col_id_magasin {width:80px; overflow:hidden;}
    </style>
</head>
<body id="sp">
<?php include("modules/header.php"); ?>

<div class="content_wrapper_sbr">
	  <div id="content">
        <div class="KT_tng" id="listcoupons3">
          <h1> Coupons
            <?php
  $nav_listcoupons3->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
          </h1>
          <div class="KT_tnglist">
          	<?php if($_GET['info']=='ACTIVATED') { ?><div class="succes">Approv�!!</div><?php } ?>
			<?php if($_GET['info']=='UNACTIVATED') { ?><div class="unsucces">Refus�!!</div><?php } ?>
            <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
              <div class="KT_options"> <a href="<?php echo $nav_listcoupons3->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
                <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listcoupons3'] == 1) {
?>
                  <?php echo $_SESSION['default_max_rows_nav_listcoupons3']; ?>
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
  if (@$_SESSION['has_filter_tfi_listcoupons3'] == 1) {
?>
                  <a href="<?php echo $tfi_listcoupons3->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
                  <?php 
  // else Conditional region2
  } else { ?>
                  <a href="<?php echo $tfi_listcoupons3->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
                  <?php } 
  // endif Conditional region2
?>
              </div>
              <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                <thead>
                  <tr class="KT_row_order">
                    <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
                    </th>
                    <th id="titre" class="KT_sorter KT_col_titre <?php echo $tso_listcoupons3->getSortIcon('coupons.titre'); ?>"> <a href="<?php echo $tso_listcoupons3->getSortLink('coupons.titre'); ?>">Titre</a> </th>
                    
                   <th id="description" class="KT_sorter KT_col_description <?php echo $tso_listcoupons3->getSortIcon('coupons.description'); ?>"> <a href="<?php echo $tso_listcoupons3->getSortLink('coupons.description'); ?>">Description</a> </th>
                    <th id="date_debut" class="KT_sorter KT_col_date_debut <?php echo $tso_listcoupons3->getSortIcon('coupons.date_debut'); ?>"> <a href="<?php echo $tso_listcoupons3->getSortLink('coupons.date_debut'); ?>">Date début</a> </th>
                    <th id="date_fin" class="KT_sorter KT_col_date_fin <?php echo $tso_listcoupons3->getSortIcon('coupons.date_fin'); ?>"> <a href="<?php echo $tso_listcoupons3->getSortLink('coupons.date_fin'); ?>">Date fin</a> </th>
                    <th id="id_user" class="KT_sorter KT_col_id_user <?php echo $tso_listcoupons3->getSortIcon('utilisateur.email'); ?>"> <a href="<?php echo $tso_listcoupons3->getSortLink('utilisateur.email'); ?>">Utilisateur</a> </th>
                    <th id="id_magasin" class="KT_sorter KT_col_id_magasin <?php echo $tso_listcoupons3->getSortIcon('magazins.nom_magazin'); ?>"> <a href="<?php echo $tso_listcoupons3->getSortLink('magazins.nom_magazin'); ?>">Magasin</a> </th>
                    <th>Region</th>
                    <th>Téléphone</th>
                    <th>Catégorie</th>
                    <th>Sous catégorie</th>
                    <th id="active" class="KT_sorter KT_col_active <?php echo $tso_listcoupons3->getSortIcon('coupons.approuve'); ?>"> <a href="<?php echo $tso_listcoupons3->getSortLink('coupons.approuve'); ?>">Active</a> </th>

                    <th>&nbsp;</th>
                  </tr>
                  <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listcoupons3'] == 1) {
?>
                    <tr class="KT_row_filter">
                      <td>&nbsp;</td>
                      <td><input type="text" name="tfi_listcoupons3_titre" id="tfi_listcoupons3_titre" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listcoupons3_titre']); ?>" size="20" maxlength="100" /></td>
          				<td></td>
                      <td><input type="text" name="tfi_listcoupons3_date_debut" id="tfi_listcoupons3_date_debut" value="<?php echo @$_SESSION['tfi_listcoupons3_date_debut']; ?>" size="10" maxlength="22" /></td>
                      <td><input type="text" name="tfi_listcoupons3_date_fin" id="tfi_listcoupons3_date_fin" value="<?php echo @$_SESSION['tfi_listcoupons3_date_fin']; ?>" size="10" maxlength="22" /></td>
                      <td><select name="tfi_listcoupons3_id_user" id="tfi_listcoupons3_id_user">
                          <option value="" <?php if (!(strcmp("", @$_SESSION['tfi_listcoupons3_id_user']))) {echo "SELECTED";} ?>><?php echo NXT_getResource("None"); ?></option>
                          <?php
							do {  
							?>
							  <option value="<?php echo $row_Recordset1['id']?>"<?php if (!(strcmp($row_Recordset1['id'], @$_SESSION['tfi_listcoupons3_id_user']))) {echo "SELECTED";} ?>>
							  	<?php 
									$vowels = array("@");
									echo $onlyconsonants = str_replace($vowels, "&#64;", $row_Recordset1['email']);
								?>
                              </option>
							  <?php
							} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
							$rows = mysql_num_rows($Recordset1);
							if($rows > 0) {
							mysql_data_seek($Recordset1, 0);
							$row_Recordset1 = mysql_fetch_assoc($Recordset1);
							}
							?>
                        </select>
                      </td>
                     
                      
                      <td>
                      
                      <input type="text" name="magasin_name" id="magasin_name" size="20" maxlength="200" />
                      <input type="hidden" name="tfi_listcoupons3_id_magasin" id="tfi_listcoupons3_id_magasin" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listcoupons3_id_magasin']); ?>" size="20" maxlength="200" />
                        
                      
                      <?php /*?><select name="tfi_listcoupons3_id_magasin" id="tfi_listcoupons3_id_magasin">
                          <option value="" <?php if (!(strcmp("", @$_SESSION['tfi_listcoupons3_id_magasin']))) {echo "SELECTED";} ?>><?php echo NXT_getResource("None"); ?></option>
                          <?php
							do {  
							?>
							  <option value="<?php echo $row_Recordset4['id_magazin']?>"<?php if (!(strcmp($row_Recordset4['id_magazin'], @$_SESSION['tfi_listcoupons3_id_magasin']))) {echo "SELECTED";} ?>><?php echo $row_Recordset4['nom_magazin']?></option>
							  <?php
							} while ($row_Recordset4 = mysql_fetch_assoc($Recordset4));
							$rows = mysql_num_rows($Recordset4);
							if($rows > 0) {
							mysql_data_seek($Recordset4, 0);
							$row_Recordset4 = mysql_fetch_assoc($Recordset4);
							}
							?>
                        </select><?php */?>
                      </td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>
                      	<select name="tfi_listcoupons3_approuve" id="tfi_listcoupons3_approuve">
                            <option value="1" <?php if (!(strcmp(1, KT_escapeAttribute(@$_SESSION['tfi_listcoupons3_approuve'])))) {echo "SELECTED";} ?>>Accepté</option>
                            <option value="2" <?php if (!(strcmp(2, KT_escapeAttribute(@$_SESSION['tfi_listcoupons3_approuve'])))) {echo "SELECTED";} ?>>Refusé</option>
                            <option value="0" <?php if (!(strcmp(0, KT_escapeAttribute(@$_SESSION['tfi_listcoupons3_approuve'])))) {echo "SELECTED";} ?>>Nom</option>
                        </select>
                      </td>
                      <td><input type="submit" name="tfi_listcoupons3" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
                    </tr>
                    <?php } 
  // endif Conditional region3
?>
                </thead>
                <tbody>
                  <?php if ($totalRows_rscoupons1 == 0) { // Show if recordset empty ?>
                    <tr>
                      <td colspan="13"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
                    </tr>
                    <?php } // Show if recordset empty ?>
                  <?php if ($totalRows_rscoupons1 > 0) { // Show if recordset not empty ?>
                    <?php do { ?>
                      <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                        <td><input type="checkbox" name="kt_pk_coupons" class="id_checkbox" rel="<?php echo $row_rscoupons1['email']; ?>" coupons="<?php echo $row_rscoupons1['titre']; ?>" value="<?php echo $row_rscoupons1['id_coupon']; ?>" />
                            <input type="hidden" name="id_coupon" class="id_field" value="<?php echo $row_rscoupons1['id_coupon']; ?>" />
                        </td>
                        <td style="white-space: normal;"><div style="width:150px;"><?php echo $row_rscoupons1['titre']; ?></div></td>
                        <td style="white-space: normal;"><div style="width:200px;"><?php echo $row_rscoupons1['description']; ?></div></td>
                        <td><div class="KT_col_date_debut"><?php echo KT_formatDate($row_rscoupons1['date_debut']); ?></div></td>
                        <td><div class="KT_col_date_fin"><?php echo KT_formatDate($row_rscoupons1['date_fin']); ?></div></td>
                        <td><div class="KT_col_id_user"><?php echo KT_FormatForList($row_rscoupons1['email'], 20); ?></div></td>
                        <td><div class="KT_col_id_magasin"><?php echo KT_FormatForList($row_rscoupons1['nom_magazin'], 20); ?></div></td>
                        <td><?php echo ($row_rscoupons1['nom_region']) ?></td>
                        <td><?php echo ($row_rscoupons1['telephone']) ?></td>
                        <td><?php echo ($row_rscoupons1['categorie']) ?></td>
                        <td><?php echo ($row_rscoupons1['sous_categorie']) ?></td>
                        <td><div class="KT_col_active">
							<?php if($row_rscoupons1['approuve']== 1 ){echo "Accepté" ;}elseif($row_rscoupons1['approuve']== 2 ){echo "Refusé";}else{echo "Non";}  ?> 
                          </div></td>
                        <td><a class="KT_edit_link" href="formCoupons.php?id_coupon=<?php echo $row_rscoupons1['id_coupon']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a> 
                        
                        	<?php if($row_rscoupons1['approuve']== '0'){ ?><a class="KT_edit_link" href="formCoupons.php?id=<?php echo $row_rscoupons1['id_coupon']; ?>&amp;KT_back=1&amp;active=1&amp;email=<?php echo $row_rscoupons1['email']; ?>">Accepter</a><?php } ?>
                        	<?php if($row_rscoupons1['approuve']== '0'){ ?><a class="KT_edit_link" href="formCoupons.php?id=<?php echo $row_rscoupons1['id_coupon']; ?>&amp;KT_back=1&amp;unactive=1&amp;email=<?php echo $row_rscoupons1['email']; ?>">Refuser</a><?php } ?>
                        </td>
                      </tr>
                      <?php } while ($row_rscoupons1 = mysql_fetch_assoc($rscoupons1)); ?>
                    <?php } // Show if recordset not empty ?>
                </tbody>
              </table>
              <div class="KT_bottomnav">
                <div>
                  <?php
            $nav_listcoupons3->Prepare();
            require("../includes/nav/NAV_Text_Navigation.inc.php");
          ?>
                </div>
              </div>
              <div class="KT_bottombuttons">
                <div class="KT_operations">
                    <input type="button" id="approv" value="TOUT Accepter" /> 
                    <!--<a class="KT_edit_op_link" href="#" onclick="nxt_list_edit_link_form(this); return false;"><?php echo NXT_getResource("edit_all"); ?></a>--> 
                    <a class="KT_delete_op_link" href="#" onclick="nxt_list_delete_link_form(this); return false;"><?php echo NXT_getResource("delete_all"); ?></a> 
                    <select name="mag_per_page" id="mag_per_page" style="width:54px;height:25px;padding:0px;">
                    	<option <?php if($nb_par_page == 10) echo "SELECTED"; ?>>10</option>
                        <option <?php if($nb_par_page == 20) echo "SELECTED"; ?>>20</option>
                        <option <?php if($nb_par_page == 50) echo "SELECTED"; ?>>50</option>
                        <option <?php if($nb_par_page == 100) echo "SELECTED"; ?>>100</option>
                    </select>
                </div>
				<span>&nbsp;</span>
                <!--<select name="no_new" id="no_new">
                  <option value="1">1</option>
                  <option value="3">3</option>
                  <option value="6">6</option>
                </select>-->
                <a class="KT_additem_op_link" href="formCoupons.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a> </div>
            </form>
          </div>
          <br class="clearfixplain" />
        </div>
        <p>&nbsp;</p>
	  </div>
</div>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);

mysql_free_result($Recordset3);

mysql_free_result($Recordset4);

mysql_free_result($rscoupons1);
?>