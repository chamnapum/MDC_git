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
$tfi_listproduits1 = new TFI_TableFilter($conn_magazinducoin, "tfi_listproduits1");
$tfi_listproduits1->addColumn("produits.titre", "STRING_TYPE", "titre", "%");
$tfi_listproduits1->addColumn("utilisateur.id", "NUMERIC_TYPE", "id_user", "=");
$tfi_listproduits1->addColumn("magazins.id_magazin", "NUMERIC_TYPE", "id_magazin", "=");
$tfi_listproduits1->addColumn("category.cat_id", "NUMERIC_TYPE", "categorie", "=");
$tfi_listproduits1->addColumn("produits.prix", "DOUBLE_TYPE", "prix", "=");
$tfi_listproduits1->addColumn("produits.photo1", "STRING_TYPE", "photo1", "%");
$tfi_listproduits1->addColumn("produits.date_echance", "DATE_TYPE", "date_echance", "=");
$tfi_listproduits1->addColumn("produits.activate", "NUMERIC_TYPE", "activate", "=");
$tfi_listproduits1->Execute();

// Sorter
$tso_listproduits1 = new TSO_TableSorter("rsproduits1", "tso_listproduits1");
$tso_listproduits1->addColumn("produits.titre");
$tso_listproduits1->addColumn("utilisateur.email");
$tso_listproduits1->addColumn("magazins.nom_magazin");
$tso_listproduits1->addColumn("category.cat_name");
$tso_listproduits1->addColumn("produits.prix");
$tso_listproduits1->addColumn("produits.photo1");
$tso_listproduits1->addColumn("produits.date_echance");
$tso_listproduits1->addColumn("produits.activate");
$tso_listproduits1->setDefault("produits.titre");
$tso_listproduits1->Execute();

// Navigation
if(isset($_GET['mag_per_page'])){$nb_par_page=$_GET['mag_per_page'];}else{$nb_par_page=10;}
$nav_listproduits1 = new NAV_Regular("nav_listproduits1", "rsproduits1", "../", $_SERVER['PHP_SELF'], $nb_par_page);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset1 = "SELECT email, id FROM utilisateur ORDER BY email";
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

/*mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset2 = "SELECT nom_magazin, id_magazin FROM magazins ORDER BY nom_magazin";
$Recordset2 = mysql_query($query_Recordset2, $magazinducoin) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);*/

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset3 = "SELECT cat_name, cat_id FROM category ORDER BY cat_name";
$Recordset3 = mysql_query($query_Recordset3, $magazinducoin) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

//NeXTenesio3 Special List Recordset
$maxRows_rsproduits1 = $_SESSION['max_rows_nav_listproduits1'];
$pageNum_rsproduits1 = 0;
if (isset($_GET['pageNum_rsproduits1'])) {
  $pageNum_rsproduits1 = $_GET['pageNum_rsproduits1'];
}
$startRow_rsproduits1 = $pageNum_rsproduits1 * $maxRows_rsproduits1;

// Defining List Recordset variable
$NXTFilter_rsproduits1 = "1=1";
if (isset($_SESSION['filter_tfi_listproduits1'])) {
  $NXTFilter_rsproduits1 = $_SESSION['filter_tfi_listproduits1'];
}
// Defining List Recordset variable
$NXTSort_rsproduits1 = "produits.titre";
if (isset($_SESSION['sorter_tso_listproduits1'])) {
  $NXTSort_rsproduits1 = $_SESSION['sorter_tso_listproduits1'];
}
mysql_select_db($database_magazinducoin, $magazinducoin);

$query_rsproduits1 = "SELECT produits.titre, produits.activate, utilisateur.email AS id_user, magazins.nom_magazin AS id_magazin, category.cat_name AS categorie, produits.prix, produits.photo1, produits.date_echance, produits.id, utilisateur.telephone, region.nom_region FROM ((((produits INNER JOIN utilisateur ON produits.id_user = utilisateur.id) INNER JOIN magazins ON produits.id_magazin = magazins.id_magazin) INNER JOIN category ON produits.categorie = category.cat_id) LEFT JOIN region ON region.id_region = magazins.region ) WHERE {$NXTFilter_rsproduits1} ORDER BY {$NXTSort_rsproduits1}";
$query_limit_rsproduits1 = sprintf("%s LIMIT %d, %d", $query_rsproduits1, $startRow_rsproduits1, $maxRows_rsproduits1);
$rsproduits1 = mysql_query($query_limit_rsproduits1, $magazinducoin) or die(mysql_error());
$row_rsproduits1 = mysql_fetch_assoc($rsproduits1);
//echo $query_limit_rsproduits1;
if (isset($_GET['totalRows_rsproduits1'])) {
  $totalRows_rsproduits1 = $_GET['totalRows_rsproduits1'];
} else {
  $all_rsproduits1 = mysql_query($query_rsproduits1);
  $totalRows_rsproduits1 = mysql_num_rows($all_rsproduits1);
}
$totalPages_rsproduits1 = ceil($totalRows_rsproduits1/$maxRows_rsproduits1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listproduits1->checkBoundries();
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
			
			$("#magasin_name").autocomplete("../assets/autocomplete/magazin.php").result(function(e, data){
				$("#magasin_name").val(data[0]);
				$("#tfi_listproduits1_id_magazin").val(data[1]);
			});
			
			$( "#mag_per_page" ).change(function() {
				var mag_per_page = $("#mag_per_page").val();
				document.location.href = 'produits.php?mag_per_page='+mag_per_page; //relative to domain
			});
			
			$('#approv').click(function(){
				var final = '';
				var i = 0;
				var len = $('.id_checkbox:checked').length;
				
				$('.id_checkbox:checked').each(function(){
					i++;         
					var id = $(this).val();
					var email = $(this).attr("rel");
					var product = $(this).attr("product");
					alert('This product "'+product+'" have to approve');
					var dataString = 'id='+id+'&email='+email;
					$.ajax({
							type: "POST",
							url: "approvProduits.php",
							data: dataString,
							cache: false,
							success: function(datas){
								if(len == i){
									window.location.href = 'produits.php';
								}
							}
						});	
					
				});
				return false;
				//window.location.href = 'produits.php';
				//alert(final);
			});
		});
    </script>
    <style type="text/css">
  /* Dynamic List row settings */
  .KT_col_titre {width:140px; overflow:hidden;}
  .KT_col_id_user {width:140px; overflow:hidden;}
  .KT_col_id_magazin {width:100px; overflow:hidden;}
  .KT_col_categorie {width:120px; overflow:hidden;}
  .KT_col_prix {width:80px; overflow:hidden;}
  .KT_col_photo1 {width:90px; overflow:hidden;}
  .KT_col_date_echance {width:140px; overflow:hidden;}
    </style>
</head>
<body id="sp">
<?php include("modules/header.php"); ?>

<div class="content_wrapper_sbr">
	<div>
  		<div id="content">
          <div class="KT_tng" id="listproduits1">
            <h1> Produits
              <?php
  $nav_listproduits1->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
            </h1>
            <div class="KT_tnglist">
            	<?php if($_GET['info']=='ACTIVATED') { ?><div class="succes">approve!!</div><?php } ?>
				<?php if($_GET['info']=='UNACTIVATED') { ?><div class="unsucces">no approve!!</div><?php } ?>
              <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
                <div class="KT_options"> <a href="<?php echo $nav_listproduits1->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
                  <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listproduits1'] == 1) {
?>
                    <?php echo $_SESSION['default_max_rows_nav_listproduits1']; ?>
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
  if (@$_SESSION['has_filter_tfi_listproduits1'] == 1) {
?>
                  <a href="<?php echo $tfi_listproduits1->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
                  <?php 
  // else Conditional region2
  } else { ?>
                  <a href="<?php echo $tfi_listproduits1->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
                  <?php } 
  // endif Conditional region2
?>
                </div>
                <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                  <thead>
                    <tr class="KT_row_order">
                      <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
                      </th>
                      <th id="titre" class="KT_sorter KT_col_titre <?php echo $tso_listproduits1->getSortIcon('produits.titre'); ?>"> <a href="<?php echo $tso_listproduits1->getSortLink('produits.titre'); ?>">Titre</a> </th>
                      <th id="id_user" class="KT_sorter KT_col_id_user <?php echo $tso_listproduits1->getSortIcon('utilisateur.email'); ?>"> <a href="<?php echo $tso_listproduits1->getSortLink('utilisateur.email'); ?>">Utilisateur</a> </th>
                      <th id="id_magazin" class="KT_sorter KT_col_id_magazin <?php echo $tso_listproduits1->getSortIcon('magazins.nom_magazin'); ?>"> <a href="<?php echo $tso_listproduits1->getSortLink('magazins.nom_magazin'); ?>">Magasin</a> </th>
                      <th id="region">Region</th>
                      <th id="telephone">Téléphone</th>
                      <th id="categorie" class="KT_sorter KT_col_categorie <?php echo $tso_listproduits1->getSortIcon('category.cat_name'); ?>"> <a href="<?php echo $tso_listproduits1->getSortLink('category.cat_name'); ?>">Catégorie</a> </th>
                      <th id="prix" class="KT_sorter KT_col_prix <?php echo $tso_listproduits1->getSortIcon('produits.prix'); ?>"> <a href="<?php echo $tso_listproduits1->getSortLink('produits.prix'); ?>">Prix</a> </th>
                      <th id="photo1" class="KT_sorter KT_col_photo1 <?php echo $tso_listproduits1->getSortIcon('produits.photo1'); ?>"> <a href="<?php echo $tso_listproduits1->getSortLink('produits.photo1'); ?>">Photo</a> </th>
                      <th id="date_echance" class="KT_sorter KT_col_date_echance <?php echo $tso_listproduits1->getSortIcon('produits.date_echance'); ?>"> <a href="<?php echo $tso_listproduits1->getSortLink('produits.date_echance'); ?>">Date d'échance</a> </th>
                      <th id="active" class="KT_sorter KT_col_active <?php echo $tso_listproduits1->getSortIcon('produits.activate'); ?>"> <a href="<?php echo $tso_listproduits1->getSortLink('produits.activate'); ?>">Active</a> </th>
                      <th>&nbsp;</th>
                    </tr>
                    <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listproduits1'] == 1) {
?>
                      <tr class="KT_row_filter">
                        <td>&nbsp;</td>
                        <td><input type="text" name="tfi_listproduits1_titre" id="tfi_listproduits1_titre" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listproduits1_titre']); ?>" size="20" maxlength="200" /></td>
                        <td><select name="tfi_listproduits1_id_user" id="tfi_listproduits1_id_user">
                            <option value="" <?php if (!(strcmp("", @$_SESSION['tfi_listproduits1_id_user']))) {echo "SELECTED";} ?>><?php echo NXT_getResource("None"); ?></option>
                            <?php
do {  
?>
                            <option value="<?php echo $row_Recordset1['id']?>"<?php if (!(strcmp($row_Recordset1['id'], @$_SESSION['tfi_listproduits1_id_user']))) {echo "SELECTED";} ?>>
							<?php 
							$vowels = array("@");
						  	echo $onlyconsonants = str_replace($vowels, "&#64;", $row_Recordset1['email']);
							?></option>
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
                        <input type="hidden" name="tfi_listproduits1_id_magazin" id="tfi_listproduits1_id_magazin" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listproduits1_id_magazin']); ?>" size="20" maxlength="200" />
                        <?php /*?><select name="tfi_listproduits1_id_magazin" id="tfi_listproduits1_id_magazin">
                            	<option value="" <?php if (!(strcmp("", @$_SESSION['tfi_listproduits1_id_magazin']))) {echo "SELECTED";} ?>><?php echo NXT_getResource("None"); ?></option>
                            <?php do { ?>
                            	<option value="<?php echo $row_Recordset2['id_magazin']?>"<?php if (!(strcmp($row_Recordset2['id_magazin'], @$_SESSION['tfi_listproduits1_id_magazin']))) {echo "SELECTED";} ?>><?php echo $row_Recordset2['nom_magazin']?></option>
                            <?php
                            } while ($row_Recordset2 = mysql_fetch_assoc($Recordset2));
								$rows = mysql_num_rows($Recordset2);
								if($rows > 0) {	
									mysql_data_seek($Recordset2, 0);
									$row_Recordset2 = mysql_fetch_assoc($Recordset2);
                            	}
                            ?>
                        </select><?php */?>
                          
                        </td>
                        <td></td>
                        <td></td>
                        <td><select name="tfi_listproduits1_categorie" id="tfi_listproduits1_categorie">
                            <option value="" <?php if (!(strcmp("", @$_SESSION['tfi_listproduits1_categorie']))) {echo "SELECTED";} ?>><?php echo NXT_getResource("None"); ?></option>
                            <?php
do {  
?>
                            <option value="<?php echo $row_Recordset3['cat_id']?>"<?php if (!(strcmp($row_Recordset3['cat_id'], @$_SESSION['tfi_listproduits1_categorie']))) {echo "SELECTED";} ?>><?php echo $row_Recordset3['cat_name']?></option>
                            <?php
} while ($row_Recordset3 = mysql_fetch_assoc($Recordset3));
  $rows = mysql_num_rows($Recordset3);
  if($rows > 0) {
      mysql_data_seek($Recordset3, 0);
	  $row_Recordset3 = mysql_fetch_assoc($Recordset3);
  }
?>
                          </select>
                        </td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td><input type="text" name="tfi_listproduits1_date_echance" id="tfi_listproduits1_date_echance" value="<?php echo @$_SESSION['tfi_listproduits1_date_echance']; ?>" size="10" maxlength="22" /></td>
                        
                        <td><select name="tfi_listproduits1_activate" id="tfi_listproduits1_activate">
                            <option value="1" <?php if (!(strcmp(1, KT_escapeAttribute(@$_SESSION['tfi_listproduits1_activate'])))) {echo "SELECTED";} ?>>Accepté</option>
                            <option value="2" <?php if (!(strcmp(2, KT_escapeAttribute(@$_SESSION['tfi_listproduits1_activate'])))) {echo "SELECTED";} ?>>Refusé</option>
                            <option value="0" <?php if (!(strcmp(0, KT_escapeAttribute(@$_SESSION['tfi_listproduits1_activate'])))) {echo "SELECTED";} ?>>Non</option>
                          </select>
                        </td>
                        <td><input type="submit" name="tfi_listproduits1" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
                      </tr>
                      <?php } 
  // endif Conditional region3
?>
                  </thead>
                  <tbody>
                    <?php if ($totalRows_rsproduits1 == 0) { // Show if recordset empty ?>
                      <tr>
                        <td colspan="9"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
                      </tr>
                      <?php } // Show if recordset empty ?>
                    <?php if ($totalRows_rsproduits1 > 0) { // Show if recordset not empty ?>
                      <?php do { ?>
                        <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                          <td><input type="checkbox" name="kt_pk_produits" class="id_checkbox" rel="<?php echo $row_rsproduits1['id_user']; ?>" product="<?php echo $row_rsproduits1['titre']; ?>"  value="<?php echo $row_rsproduits1['id']; ?>" />
                              <input type="hidden" name="id" class="id_field" value="<?php echo $row_rsproduits1['id']; ?>" />
                          </td>
                          <td><div class="KT_col_titre"><?php echo KT_FormatForList($row_rsproduits1['titre'], 20); ?></div></td>
                          <td><div class="KT_col_id_user"><?php echo KT_FormatForList($row_rsproduits1['id_user'], 20); ?></div></td>
                          <td><div class="KT_col_id_magazin"><?php echo KT_FormatForList($row_rsproduits1['id_magazin'], 20); ?></div></td>
                          <td><div class="KT_col_telephone"><?php echo ($row_rsproduits1['nom_region']); ?></div></td>
                          <td><div class="KT_col_category"><?php echo ($row_rsproduits1['telephone']); ?></div></td>
                          <td><div class="KT_col_categorie"><?php echo KT_FormatForList($row_rsproduits1['categorie'], 20); ?></div></td>
                          <td><div class="KT_col_prix"><?php echo KT_FormatForList($row_rsproduits1['prix'], 20); ?> &euro;</div></td>
                          <td><div class="KT_col_photo1"><img src="../timthumb.php?w=70&h=50&zc=1&src=assets/images/produits/<?php echo $row_rsproduits1['photo1']; ?>" /></div></td>
                          <td><div class="KT_col_date_echance"><?php echo KT_formatDate($row_rsproduits1['date_echance']); ?></div></td>
                          <td><div class="KT_col_active">
							<?php if($row_rsproduits1['activate']== 1 ){echo "Accepté" ;}elseif($row_rsproduits1['activate']== 2 ){echo "Refusé";}else{echo "Non";}  ?> 
                          </div></td>
                          <td><a class="KT_edit_link" href="formProduits.php?id=<?php echo $row_rsproduits1['id']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a>
                          	<?php if($row_rsproduits1['activate']== '0' ){ ?><a class="KT_edit_link" href="formProduits.php?id=<?php echo $row_rsproduits1['id']; ?>&amp;KT_back=1&amp;active=1&amp;email=<?php echo $row_rsproduits1['id_user']; ?>">Accepter</a><?php } ?>
                        	<?php if($row_rsproduits1['activate']== '0' ){ ?><a class="KT_edit_link" href="formProduits.php?id=<?php echo $row_rsproduits1['id']; ?>&amp;KT_back=1&amp;unactive=1&amp;email=<?php echo $row_rsproduits1['id_user']; ?>">Refuser</a><?php } ?>
                          </td>
                        </tr>
                        <?php } while ($row_rsproduits1 = mysql_fetch_assoc($rsproduits1)); ?>
                      <?php } // Show if recordset not empty ?>
                  </tbody>
                </table>
                <div class="KT_bottomnav">
                  <div>
                    <?php
            $nav_listproduits1->Prepare();
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
                  <a class="KT_additem_op_link" href="formProduits.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a> </div>
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

mysql_free_result($rsproduits1);
?>