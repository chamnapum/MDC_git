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
$tfi_listevenements3 = new TFI_TableFilter($conn_magazinducoin, "tfi_listevenements3");
$tfi_listevenements3->addColumn("evenements.titre", "STRING_TYPE", "titre", "%");
$tfi_listevenements3->addColumn("evenements.description", "STRING_TYPE", "description", "%");
$tfi_listevenements3->addColumn("utilisateur.id", "NUMERIC_TYPE", "user_id", "=");
$tfi_listevenements3->addColumn("evenements.approuve", "NUMERIC_TYPE", "approuve", "=");
$tfi_listevenements3->addColumn("evenements.date_debut", "DATE_TYPE", "date_debut", "=");
$tfi_listevenements3->addColumn("evenements.date_fin", "DATE_TYPE", "date_fin", "=");
$tfi_listevenements3->addColumn("magazins.id_magazin", "NUMERIC_TYPE", "id_magazin", "=");
$tfi_listevenements3->Execute();

// Sorter
$tso_listevenements3 = new TSO_TableSorter("rsevenements1", "tso_listevenements3");
$tso_listevenements3->addColumn("evenements.titre");
$tso_listevenements3->addColumn("evenements.description");
$tso_listevenements3->addColumn("utilisateur.email");
$tso_listevenements3->addColumn("evenements.approuve");
$tso_listevenements3->addColumn("evenements.date_debut");
$tso_listevenements3->addColumn("evenements.date_fin");
$tso_listevenements3->addColumn("magazins.nom_magazin");
$tso_listevenements3->setDefault("evenements.date_debut DESC");
$tso_listevenements3->Execute();

// Navigation
if(isset($_GET['mag_per_page'])){$nb_par_page=$_GET['mag_per_page'];}else{$nb_par_page=10;}
$nav_listevenements3 = new NAV_Regular("nav_listevenements3", "rsevenements1", "../", $_SERVER['PHP_SELF'], $nb_par_page);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset1 = "SELECT cat_name, cat_id FROM category ORDER BY cat_name";
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset2 = "SELECT email, id FROM utilisateur ORDER BY email";
$Recordset2 = mysql_query($query_Recordset2, $magazinducoin) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset3 = "SELECT email, id FROM utilisateur ORDER BY email";
$Recordset3 = mysql_query($query_Recordset3, $magazinducoin) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

/*mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset4 = "SELECT nom_magazin, id_magazin FROM magazins ORDER BY nom_magazin";
$Recordset4 = mysql_query($query_Recordset4, $magazinducoin) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);*/

//NeXTenesio3 Special List Recordset
$maxRows_rsevenements1 = $_SESSION['max_rows_nav_listevenements3'];
$pageNum_rsevenements1 = 0;
if (isset($_GET['pageNum_rsevenements1'])) {
  $pageNum_rsevenements1 = $_GET['pageNum_rsevenements1'];
}
$startRow_rsevenements1 = $pageNum_rsevenements1 * $maxRows_rsevenements1;

// Defining List Recordset variable
$NXTFilter_rsevenements1 = "1=1";
if (isset($_SESSION['filter_tfi_listevenements3'])) {
  $NXTFilter_rsevenements1 = $_SESSION['filter_tfi_listevenements3'];
}
// Defining List Recordset variable
$NXTSort_rsevenements1 = "evenements.titre DESC";
if (isset($_SESSION['sorter_tso_listevenements3'])) {
  $NXTSort_rsevenements1 = $_SESSION['sorter_tso_listevenements3'];
}
mysql_select_db($database_magazinducoin, $magazinducoin);

$query_rsevenements1 = "SELECT evenements.titre, evenements.description, utilisateur.email AS user_id, evenements.approuve, evenements.date_debut, evenements.date_fin, magazins.nom_magazin AS id_magazin, evenements.event_id, category.cat_name, utilisateur.telephone, region.nom_region FROM 
((((evenements
LEFT JOIN utilisateur ON utilisateur.id=evenements.id_user)
LEFT JOIN magazins ON magazins.id_magazin=evenements.id_magazin)
LEFT JOIN category ON category.cat_id=evenements.category_id) 
LEFT JOIN region ON region.id_region = magazins.region)
WHERE {$NXTFilter_rsevenements1} ORDER BY {$NXTSort_rsevenements1}";
$query_limit_rsevenements1 = sprintf("%s LIMIT %d, %d", $query_rsevenements1, $startRow_rsevenements1, $maxRows_rsevenements1);
$rsevenements1 = mysql_query($query_limit_rsevenements1, $magazinducoin) or die(mysql_error());
$row_rsevenements1 = mysql_fetch_assoc($rsevenements1);

if (isset($_GET['totalRows_rsevenements1'])) {
  $totalRows_rsevenements1 = $_GET['totalRows_rsevenements1'];
} else {
  $all_rsevenements1 = mysql_query($query_rsevenements1);
  $totalRows_rsevenements1 = mysql_num_rows($all_rsevenements1);
}
$totalPages_rsevenements1 = ceil($totalRows_rsevenements1/$maxRows_rsevenements1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listevenements3->checkBoundries();
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
			
			$("#magasin_name").autocomplete("../assets/autocomplete/magazin_event.php").result(function(e, data){
				$("#magasin_name").val(data[0]);
				$("#tfi_listevenements3_id_magazin").val(data[1]);
			});
			
			$( "#mag_per_page" ).change(function() {
				var mag_per_page = $("#mag_per_page").val();
				document.location.href = 'evenements.php?mag_per_page='+mag_per_page; //relative to domain
			});
			
			$('#approv').click(function(){
				var final = '';
				var i = 0;
				var len = $('.id_checkbox:checked').length;
				
				$('.id_checkbox:checked').each(function(){ 
					i++;       
					var id = $(this).val();
					var email = $(this).attr("rel");
					var events = $(this).attr("event");
					alert('This Event "'+events+'" have to approve');
					var dataString = 'id='+id+'&email='+email;
					$.ajax({
							type: "POST",
							url: "approvEvenements.php",
							data: dataString,
							cache: false,
							success: function(datas){
								if(len == i){
									window.location.href = 'evenements.php';
								}
							}
						});	
					
				});
				return false;
				//window.location.href = 'evenements.php';
				//alert(final);
			});
		});
    </script>
    <style type="text/css">
  /* Dynamic List row settings */
  .KT_col_titre {width:140px; overflow:hidden;}
  .KT_col_user_id {width:120px; overflow:hidden;}
  .KT_col_approuve {width:40px; overflow:hidden;}
  .KT_col_date_debut {width:80px; overflow:hidden;}
  .KT_col_date_fin {width:80px; overflow:hidden;}
  .KT_col_id_magazin {width:140px; overflow:hidden;}
  .KT_col_description {width:200px; overflow:hidden;}
    </style>
</head>
<body id="sp">
<?php include("modules/header.php"); ?>

<div class="content_wrapper_sbr">
	<div>
  		<div id="content">
          <div class="KT_tng" id="listevenements3">
            <h1> &Eacute;vénements
              <?php
  $nav_listevenements3->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
            </h1>
            <div class="KT_tnglist">
            	<?php if($_GET['info']=='ACTIVATED') { ?><div class="succes">approve!!</div><?php } ?>
				<?php if($_GET['info']=='UNACTIVATED') { ?><div class="unsucces">no approve!!</div><?php } ?>
              <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
                <div class="KT_options"> <a href="<?php echo $nav_listevenements3->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
                  <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listevenements3'] == 1) {
?>
                    <?php echo $_SESSION['default_max_rows_nav_listevenements3']; ?>
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
  if (@$_SESSION['has_filter_tfi_listevenements3'] == 1) {
?>
                  <a href="<?php echo $tfi_listevenements3->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
                  <?php 
  // else Conditional region2
  } else { ?>
                  <a href="<?php echo $tfi_listevenements3->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
                  <?php } 
  // endif Conditional region2
?>
                </div>
                <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                  <thead>
                    <tr class="KT_row_order">
                      <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
                      </th>
                      <th id="titre" class="KT_sorter KT_col_titre <?php echo $tso_listevenements3->getSortIcon('evenements.titre'); ?>"> <a href="<?php echo $tso_listevenements3->getSortLink('evenements.titre'); ?>">Titre</a> </th>
                      <th id="user_id" class="KT_sorter KT_col_description <?php echo $tso_listevenements3->getSortIcon('utilisateur.description'); ?>"> <a href="<?php echo $tso_listevenements3->getSortLink('utilisateur.description'); ?>">Description </a> </th>
                      <th id="user_id" class="KT_sorter KT_col_user_id <?php echo $tso_listevenements3->getSortIcon('utilisateur.email'); ?>"> <a href="<?php echo $tso_listevenements3->getSortLink('utilisateur.email'); ?>">Utilisateur </a> </th>
                     <th id="date_debut" class="KT_sorter KT_col_date_debut <?php echo $tso_listevenements3->getSortIcon('evenements.date_debut'); ?>"> <a href="<?php echo $tso_listevenements3->getSortLink('evenements.date_debut'); ?>">Date début</a> </th>
                      <th id="date_fin" class="KT_sorter KT_col_date_fin <?php echo $tso_listevenements3->getSortIcon('evenements.date_fin'); ?>"> <a href="<?php echo $tso_listevenements3->getSortLink('evenements.date_fin'); ?>">Date fin</a> </th>
                      <th id="id_magazin" class="KT_sorter KT_col_id_magazin <?php echo $tso_listevenements3->getSortIcon('magazins.nom_magazin'); ?>"> <a href="<?php echo $tso_listevenements3->getSortLink('magazins.nom_magazin'); ?>">Magasin</a> </th>
                      <th id="region">Region</th>
                      <th id="telephone">Téléphone</th>
                      <th id="category">Catégorie</th>
                       <th id="approuve" class="KT_sorter KT_col_approuve <?php echo $tso_listevenements3->getSortIcon('evenements.approuve'); ?>"> <a href="<?php echo $tso_listevenements3->getSortLink('evenements.approuve'); ?>">approuve</a> </th>
                      <th>&nbsp;</th>
                    </tr>
                    <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listevenements3'] == 1) {
?>
                      <tr class="KT_row_filter">
                        <td>&nbsp;</td>
                        <td><input type="text" name="tfi_listevenements3_titre" id="tfi_listevenements3_titre" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listevenements3_titre']); ?>" size="20" maxlength="255" /></td>
                        <td></td>
                        <td><select name="tfi_listevenements3_user_id" id="tfi_listevenements3_user_id">
                                <option value="" <?php if (!(strcmp("", @$_SESSION['tfi_listevenements3_user_id']))) {echo "SELECTED";} ?>><?php echo NXT_getResource("None"); ?></option>
                                <?php
                            do {  
                            ?>
                                <option value="<?php echo $row_Recordset3['id']?>"<?php if (!(strcmp($row_Recordset3['id'], @$_SESSION['tfi_listevenements3_user_id']))) {echo "SELECTED";} ?>>
								<?php 
									$vowels = array("@");
									echo $onlyconsonants = str_replace($vowels, "&#64;", $row_Recordset3['email']);
								?>
                                </option>
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
                        <td><input type="text" name="tfi_listevenements3_date_debut" id="tfi_listevenements3_date_debut" value="<?php echo @$_SESSION['tfi_listevenements3_date_debut']; ?>" size="10" maxlength="22" /></td>
                        <td><input type="text" name="tfi_listevenements3_date_fin" id="tfi_listevenements3_date_fin" value="<?php echo @$_SESSION['tfi_listevenements3_date_fin']; ?>" size="10" maxlength="22" /></td>
                        
                        <td>
                        
                        <input type="text" name="magasin_name" id="magasin_name" size="20" maxlength="200" />
                      	<input type="hidden" name="tfi_listevenements3_id_magazin" id="tfi_listevenements3_id_magazin" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listevenements3_id_magazin']); ?>" size="20" maxlength="200" />
                       
                        
                        <?php /*?><select name="tfi_listevenements3_id_magazin" id="tfi_listevenements3_id_magazin">
                                <option value="" <?php if (!(strcmp("", @$_SESSION['tfi_listevenements3_id_magazin']))) {echo "SELECTED";} ?>><?php echo NXT_getResource("None"); ?></option>
                                <?php
                            do {  
                            ?>
                                <option value="<?php echo $row_Recordset4['id_magazin']?>"<?php if (!(strcmp($row_Recordset4['id_magazin'], @$_SESSION['tfi_listevenements3_id_magazin']))) {echo "SELECTED";} ?>><?php echo $row_Recordset4['nom_magazin']?></option>
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
                        <td><select name="tfi_listevenements3_approuve" id="tfi_listevenements3_approuve">
                            <option value="1" <?php if (!(strcmp(1, KT_escapeAttribute(@$_SESSION['tfi_listevenements3_approuve'])))) {echo "SELECTED";} ?>>Accepté</option>
                            <option value="2" <?php if (!(strcmp(2, KT_escapeAttribute(@$_SESSION['tfi_listevenements3_approuve'])))) {echo "SELECTED";} ?>>Refusé</option>
                            <option value="0" <?php if (!(strcmp(0, KT_escapeAttribute(@$_SESSION['tfi_listevenements3_approuve'])))) {echo "SELECTED";} ?>>Non</option>
                          </select>
                        </td>
                        <td><input type="submit" name="tfi_listevenements3" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
                      </tr>
                      <?php } 
  // endif Conditional region3
?>
                  </thead>
                  <tbody>
                    <?php if ($totalRows_rsevenements1 == 0) { // Show if recordset empty ?>
                      <tr>
                        <td colspan="9"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
                      </tr>
                      <?php } // Show if recordset empty ?>
                    <?php if ($totalRows_rsevenements1 > 0) { // Show if recordset not empty ?>
                      <?php do { ?>
                        <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                          <td><input type="checkbox" name="kt_pk_evenements" class="id_checkbox" rel="<?php echo $row_rsevenements1['user_id']; ?>" event="<?php echo $row_rsevenements1['titre']; ?>" value="<?php echo $row_rsevenements1['event_id']; ?>" />
                              <input type="hidden" name="event_id" class="id_field" value="<?php echo $row_rsevenements1['event_id']; ?>" />
                          </td>
                          <td style="white-space: normal;"><div class="KT_col_titre"><?php echo ($row_rsevenements1['titre']); ?></div></td>
                          <td style="white-space: normal;"><div class="KT_col_description"><?php echo $row_rsevenements1['description']; ?></div></td>
                          <td><div class="KT_col_user_id"><?php echo KT_FormatForList($row_rsevenements1['user_id'], 20); ?></div></td>
                          <td><div class="KT_col_date_debut"><?php echo KT_formatDate($row_rsevenements1['date_debut']); ?></div></td>
                          <td><div class="KT_col_date_fin"><?php echo KT_formatDate($row_rsevenements1['date_fin']); ?></div></td>
                          <td><div class="KT_col_id_magazin"><?php echo KT_FormatForList($row_rsevenements1['id_magazin'], 20); ?></div></td>
                          <td><div class="KT_col_region"><?php echo ($row_rsevenements1['nom_region']); ?></div></td>
                          <td><div class="KT_col_telephone"><?php echo ($row_rsevenements1['telephone']); ?></div></td>
                          <td><div class="KT_col_category"><?php echo ($row_rsevenements1['cat_name']); ?></div></td>
                          <td><div class="KT_col_approuve">
							<?php if($row_rsevenements1['approuve']== 1 ){echo "Accepté" ;}elseif($row_rsevenements1['approuve']== 2 ){echo "Refusé";}else{echo "Non";}  ?> 
                          </div></td>
                          <td><a class="KT_edit_link" href="form_event.php?event_id=<?php echo $row_rsevenements1['event_id']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a> 
                          	<?php if($row_rsevenements1['approuve']== '0' ){ ?><a class="KT_edit_link" href="form_event.php?id=<?php echo $row_rsevenements1['event_id']; ?>&amp;KT_back=1&amp;approuve=1&amp;email=<?php echo $row_rsevenements1['user_id']; ?>">Accepter</a><?php } ?>
                        	<?php if($row_rsevenements1['approuve']== '0' ){ ?><a class="KT_edit_link" href="form_event.php?id=<?php echo $row_rsevenements1['event_id']; ?>&amp;KT_back=1&amp;unapprouve=1&amp;email=<?php echo $row_rsevenements1['user_id']; ?>">Refuser</a><?php } ?>
                         
                          </td>
                        </tr>
                        <?php } while ($row_rsevenements1 = mysql_fetch_assoc($rsevenements1)); ?>
                      <?php } // Show if recordset not empty ?>
                  </tbody>
                </table>
                <div class="KT_bottomnav">
                  <div>
                    <?php
            $nav_listevenements3->Prepare();
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
                  <a class="KT_additem_op_link" href="form_event.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a> </div>
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

mysql_free_result($rsevenements1);
?>