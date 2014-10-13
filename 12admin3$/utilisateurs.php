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
$tfi_listutilisateur2 = new TFI_TableFilter($conn_magazinducoin, "tfi_listutilisateur2");
$tfi_listutilisateur2->addColumn("utilisateur.email", "STRING_TYPE", "email", "%");
$tfi_listutilisateur2->addColumn("utilisateur.nom", "STRING_TYPE", "nom", "%");
$tfi_listutilisateur2->addColumn("utilisateur.prenom", "STRING_TYPE", "prenom", "%");
$tfi_listutilisateur2->addColumn("utilisateur.level", "NUMERIC_TYPE", "level", "=");
$tfi_listutilisateur2->addColumn("region.id_region", "NUMERIC_TYPE", "region", "=");
$tfi_listutilisateur2->addColumn("utilisateur.activate", "NUMERIC_TYPE", "active", "=");
$tfi_listutilisateur2->Execute();

// Sorter
$tso_listutilisateur2 = new TSO_TableSorter("rsutilisateur1", "tso_listutilisateur2");
$tso_listutilisateur2->addColumn("utilisateur.email");
$tso_listutilisateur2->addColumn("utilisateur.nom");
$tso_listutilisateur2->addColumn("utilisateur.prenom");
$tso_listutilisateur2->addColumn("utilisateur.level");
$tso_listutilisateur2->addColumn("region.nom_region");
$tso_listutilisateur2->addColumn("utilisateur.activate");
$tso_listutilisateur2->setDefault("utilisateur.email DESC");
$tso_listutilisateur2->Execute();

// Navigation
$nav_listutilisateur2 = new NAV_Regular("nav_listutilisateur2", "rsutilisateur1", "../", $_SERVER['PHP_SELF'], 20);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset2 = "SELECT nom_region, id_region FROM region ORDER BY nom_region";
$Recordset2 = mysql_query($query_Recordset2, $magazinducoin) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset3 = "SELECT nom_region, id_region FROM region ORDER BY nom_region";
$Recordset3 = mysql_query($query_Recordset3, $magazinducoin) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

//NeXTenesio3 Special List Recordset
$maxRows_rsutilisateur1 = $_SESSION['max_rows_nav_listutilisateur2'];
$pageNum_rsutilisateur1 = 0;
if (isset($_GET['pageNum_rsutilisateur1'])) {
  $pageNum_rsutilisateur1 = $_GET['pageNum_rsutilisateur1'];
}
$startRow_rsutilisateur1 = $pageNum_rsutilisateur1 * $maxRows_rsutilisateur1;

// Defining List Recordset variable
$NXTFilter_rsutilisateur1 = "1=1";
if (isset($_SESSION['filter_tfi_listutilisateur2'])) {
  $NXTFilter_rsutilisateur1 = $_SESSION['filter_tfi_listutilisateur2'];
}
// Defining List Recordset variable
$NXTSort_rsutilisateur1 = "utilisateur.email DESC";
if (isset($_SESSION['sorter_tso_listutilisateur2'])) {
  $NXTSort_rsutilisateur1 = $_SESSION['sorter_tso_listutilisateur2'];
}
mysql_select_db($database_magazinducoin, $magazinducoin);

$query_rsutilisateur1 = "SELECT utilisateur.email, utilisateur.nom, utilisateur.prenom, utilisateur.activate, utilisateur.level, region.nom_region AS region, utilisateur.id, utilisateur.credit FROM utilisateur LEFT JOIN region ON utilisateur.region = region.id_region WHERE {$NXTFilter_rsutilisateur1} ORDER BY {$NXTSort_rsutilisateur1}";
$query_limit_rsutilisateur1 = sprintf("%s LIMIT %d, %d", $query_rsutilisateur1, $startRow_rsutilisateur1, $maxRows_rsutilisateur1);
$rsutilisateur1 = mysql_query($query_limit_rsutilisateur1, $magazinducoin) or die(mysql_error());
$row_rsutilisateur1 = mysql_fetch_assoc($rsutilisateur1);

if (isset($_GET['totalRows_rsutilisateur1'])) {
  $totalRows_rsutilisateur1 = $_GET['totalRows_rsutilisateur1'];
} else {
  $all_rsutilisateur1 = mysql_query($query_rsutilisateur1);
  $totalRows_rsutilisateur1 = mysql_num_rows($all_rsutilisateur1);
}
$totalPages_rsutilisateur1 = ceil($totalRows_rsutilisateur1/$maxRows_rsutilisateur1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listutilisateur2->checkBoundries();
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
  row_effects: false,
  show_as_buttons: true,
  record_counter: true
}
    </script>
    <style type="text/css">
  /* Dynamic List row settings */
  .KT_col_email {width:160px; overflow:hidden;}
  .KT_col_nom {width:100px; overflow:hidden;}
  .KT_col_prenom {width:100px; overflow:hidden;}
  .KT_col_level {width:70px; overflow:hidden;}
  .KT_col_region {width:120px; overflow:hidden;}
    </style>
</head>
<body id="sp">
<?php include("modules/header.php"); ?>

<div class="content_wrapper_sbr">
	  <div id="content">
        <div class="KT_tng" id="listutilisateur2">
          <h1> Utilisateurs
            <?php
  $nav_listutilisateur2->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
          </h1>
          <div class="KT_tnglist">
          
         <?php if($_GET['info']=='ACTIVATED') { ?><div class="succes">L'Utilisateur à été validé avec succes!!</div><?php } ?>
         <?php if($_GET['info']=='UNACTIVATED') { ?><div class="unsucces">your account no approve!!</div><?php } ?>
         
         
            <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
              <div class="KT_options"> <a href="<?php echo $nav_listutilisateur2->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
                <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listutilisateur2'] == 1) {
?>
                  <?php echo $_SESSION['default_max_rows_nav_listutilisateur2']; ?>
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
  if (@$_SESSION['has_filter_tfi_listutilisateur2'] == 1) {
?>
                  <a href="<?php echo $tfi_listutilisateur2->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
                  <?php 
  // else Conditional region2
  } else { ?>
                  <a href="<?php echo $tfi_listutilisateur2->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
                  <?php } 
  // endif Conditional region2
?>
              </div>
              <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                <thead>
                  <tr class="KT_row_order">
                    <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
                    </th>
                    <th id="credit">Crédit</th>
                    <th id="email" class="KT_sorter KT_col_email <?php echo $tso_listutilisateur2->getSortIcon('utilisateur.email'); ?>"> <a href="<?php echo $tso_listutilisateur2->getSortLink('utilisateur.email'); ?>">Email</a> </th>
                    <th id="nom" class="KT_sorter KT_col_nom <?php echo $tso_listutilisateur2->getSortIcon('utilisateur.nom'); ?>"> <a href="<?php echo $tso_listutilisateur2->getSortLink('utilisateur.nom'); ?>">Nom</a> </th>
                    <th id="prenom" class="KT_sorter KT_col_prenom <?php echo $tso_listutilisateur2->getSortIcon('utilisateur.prenom'); ?>"> <a href="<?php echo $tso_listutilisateur2->getSortLink('utilisateur.prenom'); ?>">Prénom</a> </th>
                    <th id="level" class="KT_sorter KT_col_level <?php echo $tso_listutilisateur2->getSortIcon('utilisateur.level'); ?>"> <a href="<?php echo $tso_listutilisateur2->getSortLink('utilisateur.level'); ?>">Type</a> </th>
                    <th id="region" class="KT_sorter KT_col_region <?php echo $tso_listutilisateur2->getSortIcon('region.nom_region'); ?>"> <a href="<?php echo $tso_listutilisateur2->getSortLink('region.nom_region'); ?>">Région</a> </th>
                    <th id="active" class="KT_sorter KT_col_active <?php echo $tso_listutilisateur2->getSortIcon('utilisateur.activate'); ?>"> <a href="<?php echo $tso_listutilisateur2->getSortLink('utilisateur.activate'); ?>">Active</a> </th>
                    <th>&nbsp;</th>
                  </tr>
                  <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listutilisateur2'] == 1) {
?>
                    <tr class="KT_row_filter">
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td><input type="text" name="tfi_listutilisateur2_email" id="tfi_listutilisateur2_email" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listutilisateur2_email']); ?>" size="20" maxlength="255" /></td>
                      <td><input type="text" name="tfi_listutilisateur2_nom" id="tfi_listutilisateur2_nom" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listutilisateur2_nom']); ?>" size="20" maxlength="50" /></td>
                      <td><input type="text" name="tfi_listutilisateur2_prenom" id="tfi_listutilisateur2_prenom" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listutilisateur2_prenom']); ?>" size="20" maxlength="50" /></td>
                      <td><select name="tfi_listutilisateur2_level" id="tfi_listutilisateur2_level">
                      <option value="" <?php if (!(strcmp("", @$_SESSION['tfi_listutilisateur2_level']))) {echo "SELECTED";} ?>><?php echo NXT_getResource("None"); ?></option>
                          <option value="2" <?php if (!(strcmp(2, KT_escapeAttribute(@$_SESSION['tfi_listutilisateur2_level'])))) {echo "SELECTED";} ?>>Photographes</option>
                          <option value="3" <?php if (!(strcmp(3, KT_escapeAttribute(@$_SESSION['tfi_listutilisateur2_level'])))) {echo "SELECTED";} ?>>Normal</option>
                          <option value="1" <?php if (!(strcmp(1, KT_escapeAttribute(@$_SESSION['tfi_listutilisateur2_level'])))) {echo "SELECTED";} ?>>Commerçant</option>
                          <option value="4" <?php if (!(strcmp(4, KT_escapeAttribute(@$_SESSION['tfi_listutilisateur2_level'])))) {echo "SELECTED";} ?>>Admin</option>
                        </select>
                      </td>
                      <td><select name="tfi_listutilisateur2_region" id="tfi_listutilisateur2_region">
                          <option value="" <?php if (!(strcmp("", @$_SESSION['tfi_listutilisateur2_region']))) {echo "SELECTED";} ?>><?php echo NXT_getResource("None"); ?></option>
                          <?php
do {  
?>
                          <option value="<?php echo $row_Recordset3['id_region']?>"<?php if (!(strcmp($row_Recordset3['id_region'], @$_SESSION['tfi_listutilisateur2_region']))) {echo "SELECTED";} ?>><?php echo $row_Recordset3['nom_region']?></option>
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
                      <td><select name="tfi_listutilisateur2_active" id="tfi_listutilisateur2_active">
                            <option value="1" <?php if (!(strcmp(1, KT_escapeAttribute(@$_SESSION['tfi_listutilisateur2_active'])))) {echo "SELECTED";} ?>>Accepter</option>
                            <option value="2" <?php if (!(strcmp(2, KT_escapeAttribute(@$_SESSION['tfi_listutilisateur2_active'])))) {echo "SELECTED";} ?>>Refuser</option>
                            <option value="0" <?php if (!(strcmp(0, KT_escapeAttribute(@$_SESSION['tfi_listutilisateur2_active'])))) {echo "SELECTED";} ?>>Non</option>
                          </select>
                        </td>
                      <td><input type="submit" name="tfi_listutilisateur2" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
                    </tr>
                    <?php } 
  // endif Conditional region3
?>
                </thead>
                <tbody>
                  <?php if ($totalRows_rsutilisateur1 == 0) { // Show if recordset empty ?>
                    <tr>
                      <td colspan="7"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
                    </tr>
                    <?php } // Show if recordset empty ?>
                  <?php if ($totalRows_rsutilisateur1 > 0) { // Show if recordset not empty ?>
                    <?php do { ?>
                      <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                        <td><input type="checkbox" name="kt_pk_utilisateur" class="id_checkbox" value="<?php echo $row_rsutilisateur1['id']; ?>" />
                            <input type="hidden" name="id" class="id_field" value="<?php echo $row_rsutilisateur1['id']; ?>" />
                        </td>
                        <td><?php echo $row_rsutilisateur1['credit']; ?> &euro;</td>
                        <td><div class="KT_col_email"><?php echo KT_FormatForList($row_rsutilisateur1['email'], 30); ?></div></td>
                        <td><div class="KT_col_nom"><?php echo KT_FormatForList($row_rsutilisateur1['nom'], 20); ?></div></td>
                        <td><div class="KT_col_prenom"><?php echo KT_FormatForList($row_rsutilisateur1['prenom'], 20); ?></div></td>
                        <td><div class="KT_col_level">
                        <?php if($row_rsutilisateur1['level']==1){echo"commerçant";}
							  else if($row_rsutilisateur1['level']==2){echo"Photographes";}
							  else if($row_rsutilisateur1['level']==3){echo"Normal";}
							   else if($row_rsutilisateur1['level']==4){echo"Admin";}
						?>
                        
                        
                        </div></td>
                        <td><div class="KT_col_region"><?php echo KT_FormatForList($row_rsutilisateur1['region'], 20); ?></div></td>
                        <td><div class="KT_col_active">
				<?php if($row_rsutilisateur1['activate']== 1 ){echo "Accepté" ;}elseif($row_rsutilisateur1['activate']== 2 ){echo "Refusé";}else{echo "Non";}  ?> 
                          </div></td>
                        <td><a class="KT_edit_link" href="formUtilisateurs.php?id=<?php echo $row_rsutilisateur1['id']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a> <a class="KT_delete_link" href="#delete"><?php echo NXT_getResource("delete_one"); ?></a>
                         <a class="KT_edit_link" href="commandes.php?id_user=<?php echo $row_rsutilisateur1['id']; ?>">Commandes</a> 
						<?php if($row_rsutilisateur1['activate']== '0' ){ ?><a class="KT_edit_link" href="formUtilisateurs.php?kt_login_id=<?php echo $row_rsutilisateur1['id']; ?>&amp;KT_back=1&amp;active=1&amp;kt_login_email=<?php echo $row_rsutilisateur1['email']; ?>">Accepter</a><?php } ?>
                        <?php if($row_rsutilisateur1['activate']== '0' ){ ?><a class="KT_edit_link" href="formUtilisateurs.php?kt_login_id=<?php echo $row_rsutilisateur1['id']; ?>&amp;KT_back=1&amp;unactive=1&amp;kt_login_email=<?php echo $row_rsutilisateur1['email']; ?>">Refuser</a><?php } ?>
<?php
$query_Recordset1 = "SELECT COUNT(*) AS nb FROM abonement WHERE date_echeance <= '".date('Y-m-d',mktime(0,0,0,date('m'),date('d')+10,date('Y')))."' AND date_echeance >= '".date('Y-m-d')."' AND active = 1 AND id_user = ".$row_rsutilisateur1['id'];
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
if($row_Recordset1['nb'] > 0) { ?>
                        <a class="KT_edit_link" href="formCommandes.php?no_new=1&id_user=<?php echo $row_rsutilisateur1['id']; ?>&re=1">Renouveler</a>
<?php } ?></td>
                      </tr>
                      <?php } while ($row_rsutilisateur1 = mysql_fetch_assoc($rsutilisateur1)); ?>
                    <?php } // Show if recordset not empty ?>
                </tbody>
              </table>
              <div class="KT_bottomnav">
                <div>
                  <?php
            $nav_listutilisateur2->Prepare();
            require("../includes/nav/NAV_Text_Navigation.inc.php");
          ?>
                </div>
              </div>
              <div class="KT_bottombuttons">
                <div class="KT_operations"> <!--<a class="KT_edit_op_link" href="#" onclick="nxt_list_edit_link_form(this); return false;"><?php echo NXT_getResource("edit_all"); ?></a>--> <a class="KT_delete_op_link" href="#" onclick="nxt_list_delete_link_form(this); return false;"><?php echo NXT_getResource("delete_all"); ?></a> </div>
<span>&nbsp;</span>
                <!--<select name="no_new" id="no_new">
                  <option value="1">1</option>
                  <option value="3">3</option>
                  <option value="6">6</option>
                </select>-->
                <a class="KT_additem_op_link" href="formUtilisateurs.php?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a> </div>
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

mysql_free_result($rsutilisateur1);
?>