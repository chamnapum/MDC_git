<?php require_once('Connections/magazinducoin.php'); ?>
<?php
// Load the common classes
require_once('includes/common/KT_common.php');

// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

// Load the required classes
require_once('includes/tfi/TFI.php');
require_once('includes/tso/TSO.php');
require_once('includes/nav/NAV.php');

// Make unified connection variable
$conn_magazinducoin = new KT_connection($magazinducoin, $database_magazinducoin);

//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_magazinducoin, "");
//Grand Levels: Level
//$restrict->addLevel("1");
$restrict->Execute();
//End Restrict Access To Page
if(isset($_SESSION['kt_login_id']) and $_SESSION['kt_payer'] == 0) header('Location: message_aprouvation.php');

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

if(isset($_GET['id_fa'])){
	$detele="DELETE FROM favoris WHERE id='".$_GET['id_fa']."'";
	$detele_sql = mysql_query($detele, $magazinducoin) or die(mysql_error());
}

// Filter
$tfi_listfavoris1 = new TFI_TableFilter($conn_magazinducoin, "tfi_listfavoris1");
$tfi_listfavoris1->addColumn("magazins.id_magazin", "NUMERIC_TYPE", "id_magasin", "=");
$tfi_listfavoris1->Execute();

// Sorter
$tso_listfavoris1 = new TSO_TableSorter("rsfavoris1", "tso_listfavoris1");
$tso_listfavoris1->addColumn("magazins.nom_magazin");
$tso_listfavoris1->setDefault("favoris.id_magasin");
$tso_listfavoris1->Execute();

// Navigation
$nav_listfavoris1 = new NAV_Regular("nav_listfavoris1", "rsfavoris1", "", $_SERVER['PHP_SELF'], 20);

mysql_select_db($database_magazinducoin, $magazinducoin);
/*$query_Recordset1 = "SELECT nom_magazin, id_magazin FROM magazins ORDER BY nom_magazin";
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1)*/;

//NeXTenesio3 Special List Recordset
$maxRows_rsfavoris1 = $_SESSION['max_rows_nav_listfavoris1'];
$pageNum_rsfavoris1 = 0;
if (isset($_GET['pageNum_rsfavoris1'])) {
  $pageNum_rsfavoris1 = $_GET['pageNum_rsfavoris1'];
}
$startRow_rsfavoris1 = $pageNum_rsfavoris1 * $maxRows_rsfavoris1;

// Defining List Recordset variable
$NXTFilter_rsfavoris1 = "1=1";
if (isset($_SESSION['filter_tfi_listfavoris1'])) {
  $NXTFilter_rsfavoris1 = $_SESSION['filter_tfi_listfavoris1'];
}
// Defining List Recordset variable
$NXTSort_rsfavoris1 = "favoris.id_magasin";
if (isset($_SESSION['sorter_tso_listfavoris1'])) {
  $NXTSort_rsfavoris1 = $_SESSION['sorter_tso_listfavoris1'];
}
mysql_select_db($database_magazinducoin, $magazinducoin);

$query_rsfavoris1 = "SELECT magazins.nom_magazin AS id_magasin, magazins.id_magazin AS id_mag, magazins.logo, magazins.region, favoris.id, (SELECT COUNT(*) FROM produits WHERE id_magazin = magazins.id_magazin) AS nb_produits FROM favoris LEFT JOIN magazins ON favoris.id_magasin = magazins.id_magazin WHERE  favoris.id_user = {$_SESSION['kt_login_id']} AND {$NXTFilter_rsfavoris1} ORDER BY {$NXTSort_rsfavoris1}";
$query_limit_rsfavoris1 = sprintf("%s LIMIT %d, %d", $query_rsfavoris1, $startRow_rsfavoris1, $maxRows_rsfavoris1);
$rsfavoris1 = mysql_query($query_limit_rsfavoris1, $magazinducoin) or die(mysql_error());
$row_rsfavoris1 = mysql_fetch_assoc($rsfavoris1);

if (isset($_GET['totalRows_rsfavoris1'])) {
  $totalRows_rsfavoris1 = $_GET['totalRows_rsfavoris1'];
} else {
  $all_rsfavoris1 = mysql_query($query_rsfavoris1);
  $totalRows_rsfavoris1 = mysql_num_rows($all_rsfavoris1);
}
$totalPages_rsfavoris1 = ceil($totalRows_rsfavoris1/$maxRows_rsfavoris1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listfavoris1->checkBoundries();
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 
	<title>Magasinducoin | Espace membre </title>
    <?php include("modules/head.php"); ?>
    <link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
    <script src="includes/common/js/base.js" type="text/javascript"></script>
    <script src="includes/common/js/utility.js" type="text/javascript"></script>
    <script src="includes/skins/style.js" type="text/javascript"></script>
    <script src="includes/nxt/scripts/list.js" type="text/javascript"></script>
    <script src="includes/nxt/scripts/list.js.php" type="text/javascript"></script>
    <script type="text/javascript">
$NXT_LIST_SETTINGS = {
  duplicate_buttons: false,
  duplicate_navigation: false,
  row_effects: true,
  show_as_buttons: true,
  record_counter: true
}
    </script>
	<!--<script type="text/javascript">
		jQuery(document).ready(function(){
			$(".KT_delete_link").click(function() {
				var id = $(this).attr('id');
				alert(id);
				window.location = 'mes-favoris.php?id_fa='+id;
			});
		});
	</script>-->
    <style type="text/css">
  /* Dynamic List row settings */
  .KT_col_id_magasin {width:240px; overflow:hidden;}
    </style>
</head>
<body id="sp">
<?php include("modules/header.php"); ?>

<div id="content">
	<?php include("modules/member_menu.php"); ?>
		<?php if($_SESSION['kt_login_level'] == 1){ ?>
            <?php include("modules/credit.php"); ?>
        <?php } ?>        
		<?php //include("modules/membre_menu.php"); ?>
        
	<div style="padding-left:20px;">
		<h3><?php echo $xml->Ma_liste_des_magasins_favoris; ?></h3>
        <div style="margin-left:5px;">
            <p style="float:left; width:98%; font-size:14px; margin-top:10px;">
                Retrouver sur cette page tous vos magasins favoris. Ainsi, vous accédez directement aux magasins dont lequel vous avez le plus d'attachement.
            </p>
            <p style="float:left; width:98%; font-size:14px; margin-top:10px;">
                Pour ajouter un magasin, il suffit de cliquer sur le bouton <b>« Ajouter à mes favoris »</b> qui se situe sur chaque magasin
            </p>
            <p style="float:left; width:98%; font-size:14px; margin-top:10px; margin-bottom:20px;">
                Pour le supprimer, il suffit de cliquer sur le bouton <b>« Supprimer »</b>
            </p>
        </div> 
        
        
			<div class="KT_tng" id="listfavoris1" style="width:960px;">
            <h1>
              <?php
  $nav_listfavoris1->Prepare();
  //require("includes/nav/NAV_Text_Statistics.inc.php");
?>
            </h1>
            <div class="KT_tnglist">
              <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
                <!--<div class="KT_options"> <a href="<?php echo $nav_listfavoris1->getShowAllLink(); ?>">
                <?php echo $xml-> afficher_tous; ?>
                      <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listfavoris1'] == 1) {
?>
                      <?php echo $_SESSION['default_max_rows_nav_listfavoris1']; ?>
                      <?php 
  // else Conditional region1
  } else { ?>
                      <?php echo NXT_getResource("all"); ?>
                      <?php } 
  // endif Conditional region1
?>
                      <?php echo NXT_getResource("records"); ?></a> &nbsp;
                  &nbsp; </div>-->
                <table cellpadding="2" cellspacing="0" class="KT_tngtable" style="width:950px;">
                  <thead>
                    <tr class="KT_row_order">
                    <th><?php echo $xml->logo ?></th>
                      <th id="id_magasin" class="KT_sorter KT_col_id_magasin <?php echo $tso_listfavoris1->getSortIcon('magazins.nom_magazin'); ?>"> <a href="<?php echo $tso_listfavoris1->getSortLink('magazins.nom_magazin'); ?>">Magasin</a> </th>
                      
                      <th><?php echo $xml->produits ?></th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if ($totalRows_rsfavoris1 == 0) { // Show if recordset empty ?>
                    <tr>
                      <td colspan="4">
                      <?php  echo $xml-> la_table_est_vide ?>
					   </td>
                    </tr>
                    <?php } // Show if recordset empty ?>
                    <?php if ($totalRows_rsfavoris1 > 0) { // Show if recordset not empty ?>
                    <?php do { ?>
                    <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
						<td> 
                        	<?php if($row_rsfavoris1['logo']){?>
                        		<img src="timthumb.php?src=assets/images/magasins/<?php echo $row_rsfavoris1['logo']; ?>&z=1&w=80" />
                            <?php }else{?>
                            	<img src="timthumb.php?src=assets/images/logo.png&z=1&w=80" />
                            <?php }?>
                        </td>
                        <?php $nom=str_replace($finds,$replaces,($row_rsfavoris1['id_magasin']));?>
						<?php $nom_region=str_replace($finds,$replaces,(getRegionById($row_rsfavoris1['region'])));?>
						<td>
                        	<div class="KT_col_id_magasin">
							<a href="md-<?php echo $row_rsfavoris1['region'];?>-<?php echo $nom_region;?>-<?php echo $row_rsfavoris1['id_mag'];?>-<?php echo $nom;?>.html" style="color:#333333"><?php echo KT_FormatForList($row_rsfavoris1['id_magasin'], 20); ?></a>
                            </div>
                        </td>
						<!--<td><a href="rechercher.php?adresse=&rayon=999&mot_cle=&categorie=&sous_categorie=&magasin=<?php echo $row_rsfavoris1['id_mag'];?>" style="color:#333333"><?php echo $row_rsfavoris1['nb_produits'] ?> <?php echo $xml->produit ?>(s) <?php echo $xml->disponible ?>(s)</a></td>-->
                        <td><span style="font-weight:bold; color:#333333; font-size:14px;"><?php echo $xml->produit ?>(s) <?php echo $xml->disponible ?>(s)</span></td>
                        <td>
                        	<!--<span class="KT_delete_link" id="<?php echo $row_rsfavoris1['id']?>" style="background-color: #9D216E; color:#F8C263; padding:2px 4px; display:block;"><?php echo $xml->supprimer; ?></span>-->
                            <a href="mes-favoris.php?id_fa=<?php echo $row_rsfavoris1['id']?>" style="background-color: #9D216E; color:#F8C263; padding:2px 4px; display:block; float:left;"><?php echo $xml->supprimer; ?></a>
                        </td>
                    </tr>
                    <?php } while ($row_rsfavoris1 = mysql_fetch_assoc($rsfavoris1)); ?>
                    <?php } // Show if recordset not empty ?>
                  </tbody>
                </table>
                <div class="KT_bottomnav">
                  <div>
                    <?php
            $nav_listfavoris1->Prepare();
            //require("includes/nav/NAV_Text_Navigation.inc.php");
          ?>
                  </div>
                </div>
                <div class="KT_bottombuttons">
                  <?php /*?><div class="KT_operations"> <a class="KT_edit_op_link" href="#" onclick="nxt_list_edit_link_form(this); return false;"><?php echo NXT_getResource("edit_all"); ?></a> <a class="KT_delete_op_link" href="#" onclick="nxt_list_delete_link_form(this); return false;"><?php //echo NXT_getResource("delete_all"); ?></a> </div>
<span>&nbsp;</span><?php */?>
                 </div>
              </form>
            </div>
            <br class="clearfixplain" />
          </div>
          <p>&nbsp;</p>
</div>	  
</div>
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
/*mysql_free_result($Recordset1);*/

mysql_free_result($rsfavoris1);
?>