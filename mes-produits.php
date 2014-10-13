<?php require_once('Connections/magazinducoin.php'); ?>
<?php
if(!$_SESSION['kt_login_id']){
	echo'<script>window.location="message_aprouvation.php";</script>';
}
?>
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
$restrict->addLevel("1");
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

// Filter
$tfi_listproduits2 = new TFI_TableFilter($conn_magazinducoin, "tfi_listproduits2");
$tfi_listproduits2->addColumn("produits.titre", "STRING_TYPE", "titre", "%");
$tfi_listproduits2->addColumn("category.cat_id", "NUMERIC_TYPE", "categorie", "=");
$tfi_listproduits2->addColumn("magazins.id_magazin", "NUMERIC_TYPE", "id_magazin", "=");
$tfi_listproduits2->addColumn("produits.reference", "NUMERIC_TYPE", "reference", "=");
$tfi_listproduits2->addColumn("produits.prix", "CHECKBOX_YN_TYPE", "prix", "%");
$tfi_listproduits2->addColumn("produits.en_stock", "CHECKBOX_1_0_TYPE", "en_stock", "%");
$tfi_listproduits2->addColumn("produits.photo1", "NUMERIC_TYPE", "photo1", "=");
$tfi_listproduits2->Execute();

// Sorter
$tso_listproduits2 = new TSO_TableSorter("rsproduits1", "tso_listproduits2");
$tso_listproduits2->addColumn("produits.titre");
$tso_listproduits2->addColumn("category.cat_name");
$tso_listproduits2->addColumn("magazins.nom_magazin");
$tso_listproduits2->addColumn("produits.reference");
$tso_listproduits2->addColumn("produits.prix");
$tso_listproduits2->addColumn("produits.en_stock");
$tso_listproduits2->addColumn("produits.photo1");
$tso_listproduits2->setDefault("produits.id DESC");
$tso_listproduits2->Execute();

// Navigation
$nav_listproduits2 = new NAV_Regular("nav_listproduits2", "rsproduits1", "", $_SERVER['PHP_SELF'], 20);

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset1 = "SELECT cat_name, cat_id FROM category WHERE parent_id = 0 ORDER BY cat_name";
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

/*mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset2 = "SELECT nom_magazin, id_magazin FROM magazins ORDER BY nom_magazin";
$Recordset2 = mysql_query($query_Recordset2, $magazinducoin) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);*/

//NeXTenesio3 Special List Recordset
$maxRows_rsproduits1 = $_SESSION['max_rows_nav_listproduits2'];
$pageNum_rsproduits1 = 0;
if (isset($_GET['pageNum_rsproduits1'])) {
  $pageNum_rsproduits1 = $_GET['pageNum_rsproduits1'];
}
$startRow_rsproduits1 = $pageNum_rsproduits1 * $maxRows_rsproduits1;

// Defining List Recordset variable
$NXTFilter_rsproduits1 = "1=1";
if (isset($_SESSION['filter_tfi_listproduits2'])) {
  $NXTFilter_rsproduits1 = $_SESSION['filter_tfi_listproduits2'];
}
// Defining List Recordset variable
$NXTSort_rsproduits1 = "produits.id";
if (isset($_SESSION['sorter_tso_listproduits2'])) {
  $NXTSort_rsproduits1 = $_SESSION['sorter_tso_listproduits2'];
}
mysql_select_db($database_magazinducoin, $magazinducoin);

$query_rsproduits1 = "SELECT 
  produits.titre,
  category.cat_name AS categorie,
  magazins.nom_magazin AS id_magazin,
  produits.reference,
  produits.prix,
  produits.en_stock,
  produits.vue,
  produits.photo1,
  produits.id,
  produits.count_click,
  produits.activate,
  produits.en_avant, 
  produits.en_tete_liste
FROM
  (
    produits 
    INNER JOIN category 
      ON produits.categorie = category.cat_id
  ) 
  INNER JOIN magazins 
    ON produits.id_magazin = magazins.id_magazin WHERE produits.id_user = ".$_SESSION['kt_login_id']." AND {$NXTFilter_rsproduits1} ORDER BY {$NXTSort_rsproduits1}";
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

$nav_listproduits2->checkBoundries();

$query_test = "SELECT COUNT(*) AS nb FROM produits WHERE id_user = ".$_SESSION['kt_login_id']." AND activate = 1";
$test_gratuit = mysql_query($query_test, $magazinducoin) or die('2'.mysql_error());
$data_test = mysql_fetch_array();
$nb_gratuit = $data_test[0];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
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
<script type='text/javascript'>
//<![CDATA[
$(document).ready(function() {
	$('.edit').click(function(){
		var len = $(".id_checkbox:checked").length;
		if(len == 1){
			var id = $(".id_checkbox:checked").val();
			window.location.href = 'produitForm.php?id='+id;
		}else{
			alert('Veuillez cocher un seul champ avant de modifier');
		}
	});
	$('.dupli').click(function(){
		var len = $(".id_checkbox:checked").length;
		if(len == 1){
			var id = $(".id_checkbox:checked").val();
			window.location.href = 'produit_dupliquer-'+id+'-1.html';
		}else{
			alert('Veuillez cocher un seul champ avant de dupliquer');
		}
	});
});
//]]> 
 
</script>
<style type="text/css">
  /* Dynamic List row settings */

  	.dupliquer{
	border: 0px;
	font-size: 12px;
	font-weight: bold;
	background-color: #9D286E;
	color: #FF9;
	padding: 0px 5px 3px 5px;
	margin-right: 5px;
	margin-bottom: 2px;
	margin-left: 2px;
	}
	.bar{
		margin-left:4px;
	}
	.bar input, .bar a{
		border: 0px;
		font-size: 12px;
		font-weight: bold;
		background-color: #9D286E;
		color: #F8C263;
		padding: 0px 5px 3px 5px;
		cursor: pointer;
	}
</style>
<link rel="stylesheet" href="assets/colorbox/colorbox.css" />
<script src="assets/colorbox/jquery.colorbox.js"></script>
<script>
	$(document).ready(function(){
		$.colorbox.resize();
		$('a.example').colorbox({open:true});
	});
</script>			
</head>
<body id="sp">
<?php include("modules/header.php"); ?>

<div id="content">
<?php include("modules/member_menu.php"); ?>
<?php include("modules/credit.php"); ?>
		<div style="width:98%; padding-left:20px;">
		<h3><?php echo $xml->Ma_liste_des_produits;?></h3>
        <?php if(isset($_REQUEST['invoice'])){?>
        <div class='ajax' style='display:none'><a href="invoice.php?id=<?php echo $_REQUEST['invoice'];?>" class="example" title="Invoice"></a></div>
        <?php }?>
        <div style="margin-left:5px;">
            <p style="float:left; width:98%; font-size:14px; margin-top:10px;">
                Ajouter vos produits en cliquant sur le bouton <b>« Ajouter nouveau »</b>.
            </p>
            <p style="float:left; width:98%; font-size:14px; margin-top:10px;">
                Vous pouvez ajouter tous vos produits et les gérer entièrement depuis votre tableau de bord.
            </p>
            <p style="float:left; width:98%; font-size:14px; margin-top:10px;">
                Différentes options sont disponibles :
            </p>
            <!--<p style="float:left; width:98%; font-size:14px; margin-top:10px;">
            <b>« Mettre en avant »</b> permet d'afficher votre produit en page d'accueil de votre région et d'être affiché également sur le coté gauche de la liste des produits.Permet une visibilité importante et donne un accès à tous votre magasin en un seul clic
            </p>
            <p style="float:left; width:98%; font-size:14px; margin-top:10px;">
            <b>« En tête de liste »</b> permet de remonter votre produit en tête de liste et d'avoir plus de visibilité sur votre magasin dans tous ses détails
            </p>-->
            <p style="float:left; width:98%; font-size:14px; margin-top:10px; margin-bottom:20px;">
            <b>&bull; « Dupliquer »</b> permet de recréer le même contenu d'un magasin, il vous suffit simplement de changer l'adresse du nouveau magasin et de publier
            </p>
        </div> 
		<?php if(isset($_GET['msg']) and $_GET['msg']=='repok') echo '<div class="succes">'.$xml->produit_publie.'!</div>'; ?>
			<div class="KT_tng" id="listproduits2" style="960px;">
            	<div class="KT_tnglist">
				<form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
                    <div class="bar">
                        <!--<input type="button" class="edit" value="<?php echo $xml->editer ; ?>">--> 
                        <input type="button" onclick="nxt_list_delete_link_form(this); return false;" value="Supprimer"> 
                        <input type="button" class="btn_dupli dupli" value="Dupliquer">
                    </div> 
                	<div class="KT_options"> 
                	<?php /*?><a href="<?php echo $nav_listproduits2->getShowAllLink(); ?>"><?php echo $xml->afficher_tous; ?></a>
					<?php 
                        // Show IF Conditional region2
                        if (@$_SESSION['has_filter_tfi_listproduits2'] == 1) {
                    ?>
                        <a href="<?php echo $tfi_listproduits2->getResetFilterLink(); ?>"><?php echo $xml->annuler_filtre; ?></a>
                    <?php 
                      // else Conditional region2
                        } else { ?>
                        <a href="<?php echo $tfi_listproduits2->getShowFilterLink(); ?>"><?php echo $xml->afficher_filtre ?></a>
                    <?php } 
                      // endif Conditional region2
                    ?><?php */?>
                	</div>
					<table cellpadding="2" cellspacing="0" class="KT_tngtable" style="width:950px;">
					<thead>
                    <tr class="KT_row_order">
						<th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/></th>
                        <th id="titre" class="KT_sorter KT_col_titre <?php echo $tso_listproduits2->getSortIcon('produits.titre'); ?>"> <a href="<?php echo $tso_listproduits2->getSortLink('produits.titre'); ?>"><?php echo $xml->Titre; ?></a> </th>
                        <th id="categorie" class="KT_sorter KT_col_categorie <?php echo $tso_listproduits2->getSortIcon('category.cat_name'); ?>"> <a href="<?php echo $tso_listproduits2->getSortLink('category.cat_name'); ?>"><?php echo $xml-> Categorie ?></a> </th>
                        <th id="id_magazin" class="KT_sorter KT_col_id_magazin <?php echo $tso_listproduits2->getSortIcon('magazins.nom_magazin'); ?>"> <a href="<?php echo $tso_listproduits2->getSortLink('magazins.nom_magazin'); ?>">
                        <?php echo $xml->Magasin ?></a> </th>
                        <th id="prix" class="KT_sorter KT_col_prix <?php echo $tso_listproduits2->getSortIcon('produits.prix'); ?>"> <a href="<?php echo $tso_listproduits2->getSortLink('produits.prix'); ?>"><?php echo $xml->prix ?></a> </th>
                        <th id="en_stock" class="KT_sorter KT_col_en_stock <?php echo $tso_listproduits2->getSortIcon('produits.en_stock'); ?>"> <a href="<?php echo $tso_listproduits2->getSortLink('produits.en_stock'); ?>"><?php echo $xml-> En_stock ;?></a> </th>
                        <th id="photo1" class="KT_sorter KT_col_photo1 <?php echo $tso_listproduits2->getSortIcon('produits.photo1'); ?>"> <a href="<?php echo $tso_listproduits2->getSortLink('produits.photo1'); ?>"><?php echo $xml->Photo ?></a> </th>
                        <!--<th><?php echo $xml-> vue ?></th>-->
                        <th>Nombre de vues</th>
                        <th>Mettre en avant</th>
                        <th>En tête de liste</th>
                      	<th>Approuvé</th>
                        <th>&nbsp;</th>
					</tr>

                  </thead>
                  <tbody>
                    <?php if ($totalRows_rsproduits1 == 0) { // Show if recordset empty ?>
                      <tr>
                        <td colspan="12"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
                      </tr>
                      <?php } // Show if recordset empty ?>
                    <?php if ($totalRows_rsproduits1 > 0) { // Show if recordset not empty ?>
                      <?php do { ?>
                        <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                          <td><input type="checkbox" name="kt_pk_produits" class="id_checkbox" value="<?php echo $row_rsproduits1['id']; ?>" />
                              <input type="hidden" name="id" class="id_field" value="<?php echo $row_rsproduits1['id']; ?>" />
                          </td>
                          <td><div class="KT_col_titre"><?php echo KT_FormatForList($row_rsproduits1['titre'], 20); ?></div></td>
                          <td><div class="KT_col_categorie"><?php echo (KT_FormatForList($row_rsproduits1['categorie'], 20)); ?></div></td>
                          <td><div class="KT_col_id_magazin"><?php echo $row_rsproduits1['id_magazin'] == "" ? "Tous les magasins" : (KT_FormatForList($row_rsproduits1['id_magazin'], 20)); ?></div></td>
                          <td><div class="KT_col_prix"><?php echo KT_FormatForList($row_rsproduits1['prix'], 20); ?> €</div></td>
                          <td><div class="KT_col_en_stock">
						  <?php echo $row_rsproduits1['en_stock']==1?$xml->Oui : $xml->Non ; ?>
						  </div></td>
                          <td><div class="KT_col_photo1">
						  <img src="timthumb.php?w=70&h=50&zc=1&src=assets/images/produits/<?php echo $row_rsproduits1['photo1']; ?>" />
                          </div></td>
                          <td><?php echo $row_rsproduits1['count_click']?></td>
                          <td><?php echo $row_rsproduits1['en_avant']==1?"Oui":"Non"; ?></td>
                          <td><?php echo $row_rsproduits1['en_tete_liste']==1?"Oui":"Non"; ?></td>
                          <td><?php echo $row_rsproduits1['activate']==1?"Oui":"Non"; ?></td>
                          <td>
                          <a class="KT_edit_link" href="produit_modify-<?php echo $row_rsproduits1['id']; ?>.html?KT_back=1"><?php echo $xml->editer; ?></a> 			  
                          <!--<a class="KT_delete_link" href="#delete"><?php echo $xml->supprimer; ?></a> 
                          <a href="produitForm.php?id=<?php echo $row_rsproduits1['id']; ?>&amp;dupliquer"><span class='dupliquer'>Dupliquer</span></a>--> 
                          </td>
                        </tr>
                        <?php } while ($row_rsproduits1 = mysql_fetch_assoc($rsproduits1)); ?>
                      <?php } // Show if recordset not empty ?>
                  </tbody>
                </table>
                <div class="KT_bottomnav">
                  <div>
                    <?php
            $nav_listproduits2->Prepare();
            require("includes/nav/NAV_Text_Navigation.inc.php");
          ?>
                  </div>
                </div>
                <div class="KT_bottombuttons">
                    <div class="KT_operations">
                    	<!--<input type="button" class="edit" value="<?php echo $xml->editer ; ?>">-->
                    	<a class="KT_delete_op_link" href="#" onclick="nxt_list_delete_link_form(this); return false;">Supprimer</a> 
                        <input type="button" class="btn_dupli dupli" value="Dupliquer">
                    </div>
                    <span>&nbsp;</span>
                    <input type="hidden" name="no_new" id="no_new" value="addnew" />
                    <a class="KT_additem_op_link" href="produit_add.html?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo $xml->ajouter_nouveau;?></a> 
                </div>
                  
              </form>
            </div>
            <br class="clearfixplain" />
          </div>
	  </div>
	</div>
  <div class="clear"></div>
</div>
</div>

    </div>
</form>

<div id="footer">
    <div class="recherche">
    &nbsp;
    </div>
    <?php include("modules/footer.php"); ?>
</div>

</body>
</html>
<?php
mysql_free_result($Recordset1);
/*mysql_free_result($Recordset2);*/
mysql_free_result($rsproduits1);
?>