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
$tfi_listevenements1 = new TFI_TableFilter($conn_magazinducoin, "tfi_listevenements1");
$tfi_listevenements1->addColumn("evenements.titre", "STRING_TYPE", "titre", "%");
$tfi_listevenements1->addColumn("evenements.date_debut", "DATE_TYPE", "date_debut", "=");
$tfi_listevenements1->addColumn("evenements.date_fin", "DATE_TYPE", "date_fin", "=");
$tfi_listevenements1->Execute();

// Sorter
$tso_listevenements1 = new TSO_TableSorter("rsevenements1", "tso_listevenements1");
$tso_listevenements1->addColumn("evenements.titre");
$tso_listevenements1->addColumn("evenements.date_debut");
$tso_listevenements1->addColumn("evenements.date_fin");
$tso_listevenements1->setDefault("evenements.event_id DESC");
$tso_listevenements1->Execute();

// Navigation
$nav_listevenements1 = new NAV_Regular("nav_listevenements1", "rsevenements1", "", $_SERVER['PHP_SELF'], 20);

//NeXTenesio3 Special List Recordset
$maxRows_rsevenements1 = $_SESSION['max_rows_nav_listevenements1'];
$pageNum_rsevenements1 = 0;
if (isset($_GET['pageNum_rsevenements1'])) {
  $pageNum_rsevenements1 = $_GET['pageNum_rsevenements1'];
}
$startRow_rsevenements1 = $pageNum_rsevenements1 * $maxRows_rsevenements1;

// Defining List Recordset variable
$NXTFilter_rsevenements1 = "1=1";
if (isset($_SESSION['filter_tfi_listevenements1'])) {
  $NXTFilter_rsevenements1 = $_SESSION['filter_tfi_listevenements1'];
}
// Defining List Recordset variable
$NXTSort_rsevenements1 = "evenements.event_id";
if (isset($_SESSION['sorter_tso_listevenements1'])) {
  $NXTSort_rsevenements1 = $_SESSION['sorter_tso_listevenements1'];
}
mysql_select_db($database_magazinducoin, $magazinducoin);

$query_rsevenements1 = "SELECT evenements.* FROM evenements WHERE  evenements.id_user = {$_SESSION['kt_login_id']} AND {$NXTFilter_rsevenements1} ORDER BY {$NXTSort_rsevenements1}";
$query_limit_rsevenements1 = sprintf("%s LIMIT %d, %d", $query_rsevenements1, $startRow_rsevenements1, $maxRows_rsevenements1);
$rsevenements1 = mysql_query($query_limit_rsevenements1, $magazinducoin) or die(mysql_error());
$row_rsevenements1 = mysql_fetch_assoc($rsevenements1);
//echo $query_limit_rsevenements1;
if (isset($_GET['totalRows_rsevenements1'])) {
  $totalRows_rsevenements1 = $_GET['totalRows_rsevenements1'];
} else {
  $all_rsevenements1 = mysql_query($query_rsevenements1);
  $totalRows_rsevenements1 = mysql_num_rows($all_rsevenements1);
}
$totalPages_rsevenements1 = ceil($totalRows_rsevenements1/$maxRows_rsevenements1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listevenements1->checkBoundries();


$query_test = "SELECT COUNT(*) AS nb FROM evenements WHERE id_user = ".$_SESSION['kt_login_id']." AND date_fin > NOW() AND active = 1 AND gratuit = 1";
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
			window.location.href = 'formevenement.php?event_id='+id;
		}else{
			alert('Veuillez cocher un seul champ avant de modifier');
		}
	});
	$('.dupli').click(function(){
		var len = $(".id_checkbox:checked").length;
		if(len == 1){
			var id = $(".id_checkbox:checked").val();
			window.location.href = 'evenement_dupliquer-'+id+'-1.html';
		}else{
			alert('Veuillez cocher un seul champ avant de dupliquer');
		}
	});
	$('.mea').click(function(){
		var len = $(".id_checkbox:checked").length;
		if(len == 1){
			var id = $(".id_checkbox:checked").val();
			window.location.href = 'evenement_pay_avant-'+id+'-1-1.html';
		}else{
			alert('Veuillez cocher un seul champ avant de mettre en avant');
		}
	});
	$('.etdl').click(function(){
		var len = $(".id_checkbox:checked").length;
		if(len == 1){
			var id = $(".id_checkbox:checked").val();
			window.location.href = 'evenement_pay_teteliste-'+id+'-1-2.html';
		}else{
			alert('Veuillez cocher un seul champ avant de en tête de liste');
		}
	});
});
//]]> 
 
</script>
<style type="text/css">
  /* Dynamic List row settings */
  .KT_col_titre {width:120px; overflow:hidden;}
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
        <div style="padding-left:20px;">
        <h3>Ma liste de événements:</h3>
		<?php if(isset($_REQUEST['invoice'])){?>
        <div class='ajax' style='display:none'><a href="invoice.php?id=<?php echo $_REQUEST['invoice'];?>" class="example" title="Invoice"></a></div>
        <?php }?>        
        <div style="margin-left:5px;">
            <p style="float:left; width:98%; font-size:14px; margin-top:10px;">
                Créer votre ou vos Événements(s) de réduction en cliquant sur le bouton <b>« Ajouter nouveau ».</b><br />
                Vous pouvez ajouter un événement gratuitement ou plusieurs événement et les gérer entièrement depuis votre tableau de bord.
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
            Attention : Seul un événement active est gratuit. La création de tout autre événement alors qu'un est déjà active vous sera facturée.
            </p>
            <p style="float:left; width:98%; font-size:14px; margin-top:10px; margin-bottom:20px; text-align:center;">
            Nous vous recommandons vivement d'utiliser les options de crédit si vous payer une option. Vous gagnerez plus d'argent qu'en payant par carte de crédit
            </p>
        </div> 
        
        
        
          <div class="KT_tng" id="listevenements1" style="width:960px;">
            
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
                <div class="KT_options"> <?php /*?><a href="<?php echo $nav_listevenements1->getShowAllLink(); ?>">
				<?php echo $xml-> afficher_tous; ?>
                  <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listevenements1'] == 1) {
?>
                    <?php echo $_SESSION['default_max_rows_nav_listevenements1'];  }?>
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
                      <th class="KT_sorter KT_col_titre <?php echo $tso_listevenements1->getSortIcon('evenements.titre'); ?>"> <a href="<?php echo $tso_listevenements1->getSortLink('evenements.titre'); ?>">Titre</a>  </th>
                      <th id="date_debut" class="KT_sorter KT_col_date_debut <?php echo $tso_listevenements1->getSortIcon('evenements.date_debut'); ?>"> <a href="<?php echo $tso_listevenements1->getSortLink('evenements.date_debut'); ?>">
					  <?php echo $xml->Date_de_debut ;?></a> </th>
                      <th id="date_fin" class="KT_sorter KT_col_date_fin <?php echo $tso_listevenements1->getSortIcon('evenements.date_fin'); ?>"> <a href="<?php echo $tso_listevenements1->getSortLink('evenements.date_fin'); ?>">
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
                    <?php if ($totalRows_rsevenements1 == 0) { // Show if recordset empty ?>
                      <tr>
                        <td colspan="10">
		  <?php  echo $xml->la_table_est_vide ; ?>
        </td>
                      </tr>
                      <?php } // Show if recordset empty ?>
                    <?php if ($totalRows_rsevenements1 > 0) { // Show if recordset not empty ?>
                    <?php do { ?>
                        <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                          <td><input type="checkbox" name="kt_pk_evenements" class="id_checkbox" value="<?php echo $row_rsevenements1['event_id']; ?>" />
                              <input type="hidden" name="event_id" class="id_field" value="<?php echo $row_rsevenements1['event_id']; ?>" />
                          </td>
                          <td><div class="KT_col_titre"><?php echo $row_rsevenements1['titre']; ?></div>
                          <td><div class="KT_col_date_debut"><?php echo KT_formatDate($row_rsevenements1['date_debut']); ?></div></td>
                          <td><div class="KT_col_date_fin"><?php echo KT_formatDate($row_rsevenements1['date_fin']); ?></div></td>
                          <td><div class="KT_col_count_click"><?php echo $row_rsevenements1['count_click']; ?></div></td>
                          <td>
                          <?php if($row_rsevenements1['en_avant_fin']<$now){?>
                          	Non
                          <?php } else { ?>
						  	<?php echo $row_rsevenements1['en_avant']==1?"Oui":"Non"; ?>
                          <?php }?>
                          </td>
                          <td>
						  <?php if($row_rsevenements1['en_tete_liste_fin']<$now){?>
                          	Non
                          <?php } else { ?>	
							<?php echo $row_rsevenements1['en_tete_liste']==1?"Oui":"Non"; ?>
                          <?php }?>
                          </td>
                          <td><?php echo $row_rsevenements1['active']==1?"Oui":"Non"; ?></td>
                          <td><?php echo $row_rsevenements1['approuve']==1?"Oui":"Non"; ?></td>
                          <td>
                          
                          <a class="KT_edit_link" href="evenement_modify-<?php echo $row_rsevenements1['event_id']; ?>.html?KT_back=1"><?php echo $xml->editer ;?></a> 
                          
						<?php if($row_rsevenements1['date_fin'] > date("Y-m-d")){?>
							<?php if($row_rsevenements1['active']==0){ ?>
                                <a class="KT_edit_link" href="evenement_pay_activer-<?php echo $row_rsevenements1['event_id']; ?>-1.html"><?php if($row_rsevenements1['payer']==0) echo 'Payer';
                                    ?></a> 
                            <?php } else { ?>
                            <a class="KT_edit_link" href="payer_par_credit3.php?ids=<?php echo $row_rsevenements1['event_id']; ?>&desactiver=1">Désactiver</a> 
                            <?php } ?>
                        <?php } ?>
						<?php if($row_rsevenements1['gratuit']==1){ ?>
                        	<b>Gratuit</b>
                        <?php }?>

                          </td>
                        </tr>
                        <?php } while ($row_rsevenements1 = mysql_fetch_assoc($rsevenements1)); ?>
                      <?php } // Show if recordset not empty ?>
                  </tbody>
                </table>
                <div class="KT_bottomnav">
                  <div>
					<?php
                    $nav_listevenements1->Prepare();
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
					<a class="KT_additem_op_link" href="evenement_add.html?KT_back=1" onclick="return nxt_list_additem(this)"> <?php echo $xml->ajouter_nouveau ; ?></a> </div>
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
mysql_free_result($rsevenements1);
?>