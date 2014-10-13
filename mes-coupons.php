<?php require_once('Connections/magazinducoin.php'); ?>
<?php
if(!$_SESSION['kt_login_id']){
	echo'<script>window.location="message_aprouvation.php";</script>';
}
$now = date('Y-m-d H:i:s');
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
$tfi_listcoupons1 = new TFI_TableFilter($conn_magazinducoin, "tfi_listcoupons1");
$tfi_listcoupons1->addColumn("coupons.titre", "STRING_TYPE", "titre", "%");
$tfi_listcoupons1->addColumn("coupons.date_debut", "DATE_TYPE", "date_debut", "=");
$tfi_listcoupons1->addColumn("coupons.date_fin", "DATE_TYPE", "date_fin", "=");
$tfi_listcoupons1->Execute();

// Sorter
$tso_listcoupons1 = new TSO_TableSorter("rscoupons1", "tso_listcoupons1");
$tso_listcoupons1->addColumn("coupons.titre");
$tso_listcoupons1->addColumn("coupons.date_debut");
$tso_listcoupons1->addColumn("coupons.date_fin");
$tso_listcoupons1->setDefault("coupons.id_coupon DESC");
$tso_listcoupons1->Execute();

// Navigation
$nav_listcoupons1 = new NAV_Regular("nav_listcoupons1", "rscoupons1", "", $_SERVER['PHP_SELF'], 20);

//NeXTenesio3 Special List Recordset
$maxRows_rscoupons1 = $_SESSION['max_rows_nav_listcoupons1'];
$pageNum_rscoupons1 = 0;
if (isset($_GET['pageNum_rscoupons1'])) {
  $pageNum_rscoupons1 = $_GET['pageNum_rscoupons1'];
}
$startRow_rscoupons1 = $pageNum_rscoupons1 * $maxRows_rscoupons1;

// Defining List Recordset variable
$NXTFilter_rscoupons1 = "1=1";
if (isset($_SESSION['filter_tfi_listcoupons1'])) {
  $NXTFilter_rscoupons1 = $_SESSION['filter_tfi_listcoupons1'];
}
// Defining List Recordset variable
$NXTSort_rscoupons1 = "coupons.id_coupon";
if (isset($_SESSION['sorter_tso_listcoupons1'])) {
  $NXTSort_rscoupons1 = $_SESSION['sorter_tso_listcoupons1'];
}
mysql_select_db($database_magazinducoin, $magazinducoin);

$query_rscoupons1 = "SELECT coupons.* FROM coupons WHERE  coupons.id_user = {$_SESSION['kt_login_id']} AND {$NXTFilter_rscoupons1} ORDER BY {$NXTSort_rscoupons1}";
$query_limit_rscoupons1 = sprintf("%s LIMIT %d, %d", $query_rscoupons1, $startRow_rscoupons1, $maxRows_rscoupons1);
$rscoupons1 = mysql_query($query_limit_rscoupons1, $magazinducoin) or die(mysql_error());
$row_rscoupons1 = mysql_fetch_assoc($rscoupons1);
//echo $query_limit_rscoupons1;
if (isset($_GET['totalRows_rscoupons1'])) {
  $totalRows_rscoupons1 = $_GET['totalRows_rscoupons1'];
} else {
  $all_rscoupons1 = mysql_query($query_rscoupons1);
  $totalRows_rscoupons1 = mysql_num_rows($all_rscoupons1);
}
$totalPages_rscoupons1 = ceil($totalRows_rscoupons1/$maxRows_rscoupons1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listcoupons1->checkBoundries();


$query_test = "SELECT COUNT(*) AS nb FROM coupons WHERE id_user = ".$_SESSION['kt_login_id']." AND date_fin > NOW() AND active = 1 AND gratuit = 1";
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
			window.location.href = 'formCoupon.php?id_coupon='+id;
		}else{
			alert('Veuillez cocher un seul champ avant de modifier');
		}
	});
	$('.dupli').click(function(){
		var len = $(".id_checkbox:checked").length;
		if(len == 1){
			var id = $(".id_checkbox:checked").val();
			window.location.href = 'coupon_dupliquer-'+id+'-1.html';
		}else{
			alert('Veuillez cocher un seul champ avant de dupliquer');
		}
	});
	$('.mea').click(function(){
		var len = $(".id_checkbox:checked").length;
		if(len == 1){
			var id = $(".id_checkbox:checked").val();
			window.location.href = 'coupon_pay_avant-'+id+'-1-1.html';
		}else{
			alert('Veuillez cocher un seul champ avant de mettre en avant');
		}
	});
	$('.etdl').click(function(){
		var len = $(".id_checkbox:checked").length;
		if(len == 1){
			var id = $(".id_checkbox:checked").val();
			window.location.href = 'coupon_pay_teteliste-'+id+'-1-2.html';
		}else{
			alert('Veuillez cocher un seul champ avant de en tête de liste');
		}
	});
});
//]]> 
 
</script>
<style type="text/css">
  /* Dynamic List row settings */
  .KT_col_titre {width:100px; overflow:hidden;}
  .KT_col_reduction {width:35px; overflow:hidden;}
  .KT_col_date_debut {width:120px; overflow:hidden;}
  .KT_col_date_fin {width:100px; overflow:hidden;}
  .indicator{
	border: 0px;
	font-size: 12px;
	font-weight: bold;
	background-color: #9D286E;
	color: #FFF;
	padding: 0px 5px 3px 5px;
	margin-right: 5px;
	margin-bottom: 2px;
	margin-left:3px;
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
	<?php //include("modules/membre_menu.php"); ?>

    <div style="padding-left:20px;">
    <h3><?php echo $xml-> Ma_liste_des_coupons ?></h3>
    <?php if(isset($_REQUEST['invoice'])){?>
    <div class='ajax' style='display:none'><a href="invoice.php?id=<?php echo $_REQUEST['invoice'];?>" class="example" title="Invoice"></a></div>
    <?php }?>
	<div style="margin-left:5px;">
        <p style="float:left; width:98%; font-size:14px; margin-top:10px;">
        	Créer votre ou vos coupons(s) de réduction en cliquant sur le bouton <b>« Ajouter nouveau ».</b><br />
            Vous pouvez ajouter un coupon gratuitement ou plusieurs coupons et les gérer entièrement depuis votre tableau de bord.
        </p>
        <p style="float:left; width:98%; font-size:14px; margin-top:10px;">
        	Différentes options sont disponibles :
        </p>
        <p style="float:left; width:98%; font-size:14px; margin-top:10px;">
        <b>&bull; « Mettre en avant »</b> permet d'afficher votre magasin en page d'accueil de votre région et d'être affiché également sur le coté gauche de l'annuaire des magasins. Permet une visibilité importante et résume tout ce que votre magasin offre comme Coupons de réduction, produits et évènements
    	</p>
        <p style="float:left; width:98%; font-size:14px; margin-top:10px;">
        <b>&bull; « En tête de liste »</b> permet de remonter votre magasin en tête de liste et d'avoir plus de visibilité sur les avantages que votre magasin proposent
    	</p>
        <p style="float:left; width:98%; font-size:14px; margin-top:10px;">
        <b>&bull; « Dupliquer »</b> permet de recréer le même contenu d'un magasin, il vous suffit simplement de changer l'adresse du nouveau magasin et de publier
        </p>
        <p style="float:left; width:98%; font-size:14px; margin-top:10px; text-align:center;">
		Attention : Seul un coupon active est gratuit. La création de tout autre coupon alors qu'un est déjà active vous sera facturée.
        </p>
        <p style="float:left; width:98%; font-size:14px; margin-top:10px; margin-bottom:20px; text-align:center;">
        Nous vous recommandons vivement d'utiliser les options de crédit si vous payer une option. Vous gagnerez plus d'argent qu'en payant par carte de crédit
        </p>
    </div> 
    
          <div class="KT_tng" id="listcoupons1" style="960px;">
            <div class="KT_tnglist">
              <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
				<div class="bar">
                    <!--<input type="button" class="edit" value="<?php echo $xml->editer ; ?>">--> 
                    <input type="button" onclick="nxt_list_delete_link_form(this); return false;" value="Supprimer"> 
                    <!--<a href="#" onclick="nxt_list_delete_link_form(this); return false;">Supprimer</a> -->
                    <input type="button" class="btn_dupli dupli" value="Dupliquer">
                    <input type="button" class="mea" value="Mettre en avant">
                    <input type="button" class="etdl" value="En tête de liste">
                </div>  
                <div class="KT_options"> <?php /*?><a href="<?php echo $nav_listcoupons1->getShowAllLink(); ?>">
				<?php echo $xml-> afficher_tous; ?>
                  <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listcoupons1'] == 1) {
?>
                    <?php echo $_SESSION['default_max_rows_nav_listcoupons1'];  }?>
                    <?php 
  // else Conditional region1
//  } else { ?>
                    <?php // echo NXT_getResource("all"); ?>
                    <?php  //} 
  // endif Conditional region1
?>
                      <?php // echo NXT_getResource("records"); ?></a> &nbsp;
                  &nbsp; <?php */?></div>
                <table cellpadding="2" cellspacing="0" class="KT_tngtable" style="width:950px;">
                  <thead>
                    <tr class="KT_row_order">
                      <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
                      </th>
                      <th id="reduction" class="KT_sorter KT_col_titre <?php echo $tso_listcoupons1->getSortIcon('coupons.titre'); ?>"><a href="<?php echo $tso_listcoupons1->getSortLink('coupons.titre'); ?>"><?php echo $xml-> Titre; ?></a></th>
                      <th id="date_debut" class="KT_sorter KT_col_date_debut <?php echo $tso_listcoupons1->getSortIcon('coupons.date_debut'); ?>"> <a href="<?php echo $tso_listcoupons1->getSortLink('coupons.date_debut'); ?>">
					  <?php echo $xml->Date_de_debut ;?></a> </th>
                      <th id="date_fin" class="KT_sorter KT_col_date_fin <?php echo $tso_listcoupons1->getSortIcon('coupons.date_fin'); ?>"> <a href="<?php echo $tso_listcoupons1->getSortLink('coupons.date_fin'); ?>">
                      <?php echo $xml->Date_fin ; ?>
                      
                      </a> </th>
                      <th>Nombre de vues</th>
                      <th>Mettre en avant</th>
                      <th>En tête de liste</th>
                      <th>Active</th>
                      <th>Approuvé</th>
                      <th>&nbsp;</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if ($totalRows_rscoupons1 == 0) { // Show if recordset empty ?>
                      <tr>
                        <td colspan="10">
							<?php  echo $xml->la_table_est_vide ; ?>
                        </td>
                      </tr>
                      <?php } // Show if recordset empty ?>
                    <?php if ($totalRows_rscoupons1 > 0) { // Show if recordset not empty ?>
                    <?php do { ?>
                        <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                          <td><input type="checkbox" name="kt_pk_coupons" class="id_checkbox" value="<?php echo $row_rscoupons1['id_coupon']; ?>" />
                              <input type="hidden" name="id_coupon" class="id_field" value="<?php echo $row_rscoupons1['id_coupon']; ?>" />
                          </td>
                          <td style="white-space: normal;"> <div class="KT_col_titre"><?php echo $row_rscoupons1['titre']; ?></div>
                        <?php /*?>  <td><div class="KT_col_reduction"><?php echo KT_FormatForList($row_rscoupons1['reduction'], 5); ?>%</div></td><?php */?>
                          <td><div class="KT_col_date_debut"><?php echo KT_formatDate($row_rscoupons1['date_debut']); ?></div></td>
                          <td><div class="KT_col_date_fin"><?php echo KT_formatDate($row_rscoupons1['date_fin']); ?></div></td>
                          <td><div class="KT_col_count_click"><?php echo $row_rscoupons1['count_click']; ?></div></td>
                          <td>
                          <?php if($row_rscoupons1['en_avant_fin']<$now){?>
                          	Non
                          <?php } else { ?>
						  	<?php echo $row_rscoupons1['en_avant']==1?"Oui":"Non"; ?>
                          <?php } ?>
                          </td>
                          <td>
						  <?php if($row_rscoupons1['en_tete_liste_fin']<$now){?>
                          	Non
                          <?php } else { ?>
						  	<?php echo $row_rscoupons1['en_tete_liste']==1?"Oui":"Non"; ?>
                          <?php } ?>
                          </td>
                          <td>
							<?php echo $row_rscoupons1['active']==1?"Oui":"Non"; ?>
                          </td>
                          <td><?php echo $row_rscoupons1['approuve']==1?"Oui":"Non"; ?></td>
                          <td>
                          
                          <a class="KT_edit_link" href="coupon_modify-<?php echo $row_rscoupons1['id_coupon']; ?>.html?KT_back=1"><?php echo $xml->editer ;?></a> 
                          <?php if($row_rscoupons1['date_fin'] > date("Y-m-d")){?>
                          
							  <?php if($row_rscoupons1['active']==0){ ?>
                                  <a class="KT_edit_link" href="coupon_pay_activer-<?php echo $row_rscoupons1['id_coupon']; ?>-1.html"><?php if($row_rscoupons1['payer']==0) echo 'Payer';
                                      ?></a> 
                              <?php } else { ?>
                              <a class="KT_edit_link" href="payer_par_credit2.php?ids=<?php echo $row_rscoupons1['id_coupon']; ?>&desactiver=1">Désactiver</a> 
                              <?php } ?>
                              
                          <?php }?>
                          <?php if($row_rscoupons1['gratuit']==1){ ?>
								<b>Gratuit</b>
                          <?php }?>
                          
                          <?php /*?><?php if($row_rscoupons1['en_avant']==0){ ?>
                          	<a style="margin-top:4px;" class="KT_edit_link" href="payer_par_credit2.php?ids=<?php echo $row_rscoupons1['id_coupon']; ?>&miseenavant=1">Mettre en avant</a> 
                          <?php } ?>
                          
                          <?php if($row_rscoupons1['en_tete_liste']==0){ ?>
                          	<a style="margin-top:4px;" class="KT_edit_link" href="payer_par_credit2.php?ids=<?php echo $row_rscoupons1['id_coupon']; ?>&enteteliste=1">En tête de liste</a> 
                          <?php } ?>
                          <a class="KT_delete_link" href="#delete"><?php echo $xml->supprimer; ?></a><?php */?> </td>
                        </tr>
                        <?php } while ($row_rscoupons1 = mysql_fetch_assoc($rscoupons1)); ?>
                      <?php } // Show if recordset not empty ?>
                  </tbody>
                </table>
                <div class="KT_bottomnav">
                  <div>
                    <?php
            $nav_listcoupons1->Prepare();
            require("includes/nav/NAV_Text_Navigation.inc.php");
          ?>
                  </div>
                </div>
                <div class="KT_bottombuttons">
                    <div class="KT_operations"> 
                    	<!--<input type="button" class="edit" value="<?php echo $xml->editer ; ?>">--> 
                        <a class="KT_delete_op_link" href="#" onclick="nxt_list_delete_link_form(this); return false;">Supprimer</a>
                        <input type="button" class="btn_dupli dupli" value="Dupliquer">
                        <input type="button" class="mea" value="Mettre en avant">
                        <input type="button" class="etdl" value="En tête de liste">
                    </div>
                    <span>&nbsp;</span>
					<input type="hidden" name="no_new" id="no_new" value="addnew" />
					<a class="KT_additem_op_link" href="coupon_add.html?KT_back=1" onclick="return nxt_list_additem(this)"> <?php echo $xml->ajouter_nouveau ; ?></a> </div>
              </form>
            </div>
            <br class="clearfixplain" />
          </div>
  		</div>
	</div>
    </div>
  
  
  
  
  <!-- Sidebar Area -->
 
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
mysql_free_result($rscoupons1);
?>