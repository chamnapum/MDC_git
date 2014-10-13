<?php require_once('Connections/magazinducoin.php'); ?>

<?php

if(!$_SESSION['kt_login_id']){

	echo'<script>window.location="message_aprouvation.php";</script>';

}

$now = date('Y-m-d H:i:s');

?>

<?php

if(isset($_GET['envoi'])){

	$destinataire = "webmaster@magasinducoin.fr";

	$objet = "Vous avez un paiement en attente!" ;

	$message = '<html>

	<head>

	<title>Vous avez un paiement en attente</title>

	</head>

	<body>

	<p>Bonjour Webmaster</p>

	<p>Vous avez un magasin en attente de validation</p>

	<p>Nom du magasin     : '.$_GET['titre'].'</p>

	<p>Numéro de commande : '.$_GET['id'].'</p>

	<p>Mode de paiement   : '.$_GET['type'].'</p>

	<p><br></p>

	<p><strong>L\'&Eacute;quipe Magasinducoin.com</strong></p>

	</body>

	</html>

	';

	/* Si l’on veut envoyer un mail au format HTML, il faut configurer le type Content-type. */

	$headers = "MIME-Version: 1.0\n";

	$headers .= "Content-type: text/html; charset=iso-8859-1\n";

	

	/* Quelques types d’entêtes : errors, From cc's, bcc's, etc */

	$headers .= "From: Magasin Du Coin <contact@magasinducoin.fr>\n";

	

	

	// On envoi l’email

	 mail($destinataire, $objet, $message, $headers);

}



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

$tfi_listmagazins1 = new TFI_TableFilter($conn_magazinducoin, "tfi_listmagazins1");

$tfi_listmagazins1->addColumn("magazins.nom_magazin", "STRING_TYPE", "nom_magazin", "%");

$tfi_listmagazins1->addColumn("region.id_region", "NUMERIC_TYPE", "region", "=");

$tfi_listmagazins1->addColumn("maps_ville.id_ville", "NUMERIC_TYPE", "ville", "=");

$tfi_listmagazins1->Execute();



// Sorter

$tso_listmagazins1 = new TSO_TableSorter("rsmagazins1", "tso_listmagazins1");

$tso_listmagazins1->addColumn("magazins.nom_magazin");

$tso_listmagazins1->addColumn("region.nom_region");

$tso_listmagazins1->addColumn("maps_ville.nom");

$tso_listmagazins1->setDefault("magazins.id_magazin DESC");

$tso_listmagazins1->Execute();



// Navigation

$nav_listmagazins1 = new NAV_Regular("nav_listmagazins1", "rsmagazins1", "", $_SERVER['PHP_SELF'], 10);



mysql_select_db($database_magazinducoin, $magazinducoin);

$query_Recordset1 = "SELECT nom_region, id_region FROM region ORDER BY nom_region";

$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());

$row_Recordset1 = mysql_fetch_assoc($Recordset1);

$totalRows_Recordset1 = mysql_num_rows($Recordset1);



mysql_select_db($database_magazinducoin, $magazinducoin);

$query_Recordset2 = "SELECT nom, id_ville FROM maps_ville ORDER BY nom";

$Recordset2 = mysql_query($query_Recordset2, $magazinducoin) or die(mysql_error());

$row_Recordset2 = mysql_fetch_assoc($Recordset2);

$totalRows_Recordset2 = mysql_num_rows($Recordset2);



//NeXTenesio3 Special List Recordset

$maxRows_rsmagazins1 = $_SESSION['max_rows_nav_listmagazins1'];

$pageNum_rsmagazins1 = 0;

if (isset($_GET['pageNum_rsmagazins1'])) {

  $pageNum_rsmagazins1 = $_GET['pageNum_rsmagazins1'];

}

$startRow_rsmagazins1 = $pageNum_rsmagazins1 * $maxRows_rsmagazins1;



// Defining List Recordset variable

$NXTFilter_rsmagazins1 = "1=1";

if (isset($_SESSION['filter_tfi_listmagazins1'])) {

  $NXTFilter_rsmagazins1 = $_SESSION['filter_tfi_listmagazins1'];

}

// Defining List Recordset variable

$NXTSort_rsmagazins1 = "magazins.id_magazin";

if (isset($_SESSION['sorter_tso_listmagazins1'])) {

  $NXTSort_rsmagazins1 = $_SESSION['sorter_tso_listmagazins1'];

}

mysql_select_db($database_magazinducoin, $magazinducoin);



$query_rsmagazins1 = "SELECT 

  magazins.*,

  region.nom_region AS region,

  departement.nom_departement,

  maps_ville.nom AS ville,

  magazins.id_magazin 

FROM

  (

    magazins 

    INNER JOIN region 

      ON region.id_region = magazins.region 

    INNER JOIN departement 

      ON departement.code = magazins.department 

    INNER JOIN maps_ville 

      ON maps_ville.id_ville = magazins.ville

  ) WHERE {$NXTFilter_rsmagazins1} AND magazins.id_user = ".$_SESSION['kt_login_id']." ORDER BY {$NXTSort_rsmagazins1}";

$query_limit_rsmagazins1 = sprintf("%s LIMIT %d, %d", $query_rsmagazins1, $startRow_rsmagazins1, $maxRows_rsmagazins1);

$rsmagazins1 = mysql_query($query_limit_rsmagazins1, $magazinducoin) or die(mysql_error());

$row_rsmagazins1 = mysql_fetch_assoc($rsmagazins1);

//echo $query_limit_rsmagazins1;

if (isset($_GET['totalRows_rsmagazins1'])) {

  $totalRows_rsmagazins1 = $_GET['totalRows_rsmagazins1'];

} else {

  $all_rsmagazins1 = mysql_query($query_rsmagazins1);

  $totalRows_rsmagazins1 = mysql_num_rows($all_rsmagazins1);

}

$totalPages_rsmagazins1 = ceil($totalRows_rsmagazins1/$maxRows_rsmagazins1)-1;

//End NeXTenesio3 Special List Recordset



$nav_listmagazins1->checkBoundries();



$query_test = "SELECT COUNT(*) AS nb FROM magazins WHERE id_user = ".$_SESSION['kt_login_id']." AND activate = 1 AND gratuit = 1";

$test_gratuit = mysql_query($query_test, $magazinducoin) or die('2'.mysql_error());

$data_test = mysql_fetch_array();

$nb_gratuit = $data_test[0];



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

<script type='text/javascript'>

//<![CDATA[

$(document).ready(function() {

	$('.edit').click(function(){

		var len = $(".id_checkbox:checked").length;

		if(len == 1){

			var id = $(".id_checkbox:checked").val();

			window.location.href = 'magasinForm.php?id_magazin='+id;

		}else{

			alert('Veuillez cocher un seul champ avant de modifier');

		}

	});

	$('.dupli').click(function(){

		var len = $(".id_checkbox:checked").length;

		if(len == 1){

			var id = $(".id_checkbox:checked").val();

			window.location.href = 'magazin_dupliquer-'+id+'-1.html';

		}else{

			alert('Veuillez cocher un seul champ avant de dupliquer');

		}

	});

	$('.mea').click(function(){

		var len = $(".id_checkbox:checked").length;

		if(len == 1){

			var id = $(".id_checkbox:checked").val();

			window.location.href = 'magazin_pay_avant-'+id+'-1-1.html';

		}else{

			alert('Veuillez cocher un seul champ avant de mettre en avant');

		}

	});

	$('.etdl').click(function(){

		var len = $(".id_checkbox:checked").length;

		if(len == 1){

			var id = $(".id_checkbox:checked").val();

			window.location.href = 'magazin_pay_teteliste-'+id+'-1-2.html';

		}else{

			alert('Veuillez cocher un seul champ avant de en tête de liste');

		}

	});

});

//]]> 

 

</script>

<style type="text/css">

  /* Dynamic List row settings */

.KT_col_nom_magazin {width:140px; overflow:hidden;}

.KT_col_region {width:140px; overflow:hidden;}

.KT_col_ville {width:140px; overflow:hidden;}

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

  		  <h3><?php echo $xml->Ma_liste_des_magasins ; ?></h3>

        <?php if(isset($_REQUEST['invoice'])){?>

        <div class='ajax' style='display:none'><a href="invoice.php?id=<?php echo $_REQUEST['invoice'];?>" class="example" title="Invoice"></a></div>

        <?php }?>  

	<div style="margin-left:5px;">

        <p style="float:left; width:98%; font-size:14px; margin-top:10px;">

        	Ajouter votre ou vos magasin(s) en cliquant sur le bouton <b>« Ajouter nouveau ».</b>

        </p>

        <p style="float:left; width:98%; font-size:14px; margin-top:10px;">

        	Vous pouvez ajouter tous vos magasins et les gérer entièrement depuis votre tableau de bord.

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

        <p style="float:left; width:98%; font-size:14px; margin-top:10px; margin-bottom:20px;">

        <b>&bull; « Dupliquer »</b> permet de recréer le même contenu d'un magasin, il vous suffit simplement de changer l'adresse du nouveau magasin et de publier

        </p>

    </div>         

		<div class="KT_tng" id="listmagazins1" style="960px;">

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

                <div class="KT_options"> 

                 </div>

                <table cellpadding="2" cellspacing="0" class="KT_tngtable" style="width:950px;">

                  <thead>

                    <tr class="KT_row_order">

                      <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>

                      </th>

                      <th id="nom_magazin" class="KT_sorter KT_col_nom_magazin <?php echo $tso_listmagazins1->getSortIcon('magazins.nom_magazin'); ?>"> <a href="<?php echo $tso_listmagazins1->getSortLink('magazins.nom_magazin'); ?>">

                     <?php echo $xml->Nom_du_magasin; ?></a> </th>

                      <th id="region" class="KT_sorter KT_col_region <?php echo $tso_listmagazins1->getSortIcon('region.nom_region'); ?>"> <a href="<?php echo $tso_listmagazins1->getSortLink('region.nom_region'); ?>"><?php echo $xml-> Region ?></a> </th>

                      <th>Département</th>

                      <th id="ville" class="KT_sorter KT_col_ville <?php echo $tso_listmagazins1->getSortIcon('maps_ville.nom'); ?>"> <a href="<?php echo $tso_listmagazins1->getSortLink('maps_ville.nom'); ?>"><?php echo $xml-> Ville ?></a> </th>

                      

                      <th>Mettre en avant</th>

                      <th>En tête de liste</th>

                      <th>Active</th>

                      <th>Approuvé</th>

                      <th>&nbsp;</th>

                    </tr>

                  </thead>

                  <tbody>

                    <?php if ($totalRows_rsmagazins1 == 0) { // Show if recordset empty ?>

                      <tr>

                        <td colspan="10" style="font-size:14px;">

                        

                        <?php //echo $xml->magasin_non_configure ; ?> Vous n'avez pas encore configuré votre magasin par défaut !   Vous devez  <a href="magazin_add.html?no_new=addnew&KT_back=1" style="color:#666">cliquer ici</a> pour la configurer.

                  <?php //echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>

                      </tr>

                      <?php } // Show if recordset empty ?>

                    <?php if ($totalRows_rsmagazins1 > 0) { // Show if recordset not empty ?>

                      <?php do { ?>

                        <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">

                          <td><input type="checkbox" name="kt_pk_magazins" class="id_checkbox" value="<?php echo $row_rsmagazins1['id_magazin']; ?>" />

                              <input type="hidden" name="id_magazin" class="id_field" value="<?php echo $row_rsmagazins1['id_magazin']; ?>" />

                          </td>

                          <td><div class="KT_col_nom_magazin"><?php echo KT_FormatForList($row_rsmagazins1['nom_magazin'], 20); ?></div></td>

                          <td><?php echo KT_FormatForList(($row_rsmagazins1['region']), 20); ?></td>

                          <td><?php echo KT_FormatForList(($row_rsmagazins1['nom_departement']), 20); ?></td>

                          <td><?php echo KT_FormatForList(($row_rsmagazins1['ville']), 20); ?></td>

                          <td>

                          	<?php if($row_rsmagazins1['en_avant_fin']<$now){?>

						  		Non

                          	<?php }else{?>

                            	<?php echo $row_rsmagazins1['en_avant']==1?"Oui":"Non"; ?>

                            <?php }?>

                          </td>

                          <td>

						  	<?php if($row_rsmagazins1['en_tete_liste_fin']<$now){?>

                            Non

                          	<?php }else{?>

                            	<?php echo $row_rsmagazins1['en_tete_liste']==1?"Oui":"Non"; ?>

                            <?php }?>

						  </td>

                          <td><?php echo $row_rsmagazins1['activate']==1?"Oui":"Non"; ?></td>

                          <td><?php echo $row_rsmagazins1['approuve']==1?"Oui":"Non"; ?></td>

						<td>

                        <a class="KT_edit_link" href="magazin_modify-<?php echo $row_rsmagazins1['id_magazin']; ?>.html?KT_back=1"><?php echo $xml->editer ; ?></a> 

						

							<?php if($row_rsmagazins1['activate']==0){ ?>

                            <a class="KT_edit_link" href="magazin_pay_activer-<?php echo $row_rsmagazins1['id_magazin']; ?>-1.html"><?php if($row_rsmagazins1['payer']==1) echo 'Activer';

                            else if($row_rsmagazins1['payer']==0) echo 'Payer';?></a> 

                            <?php } else { ?>

                            <a class="KT_edit_link" href="payer_par_credit4.php?ids=<?php echo $row_rsmagazins1['id_magazin']; ?>&desactiver=1">Désactiver</a> 

                            <?php } ?>

                            

                            <?php if($row_rsmagazins1['gratuit']==1){ ?>

                            	<b>Gratuit</b>

                            <?php }?>	

                            

                          </td>

                        

                        </tr>

                        <?php } while ($row_rsmagazins1 = mysql_fetch_assoc($rsmagazins1)); ?>

                      <?php } // Show if recordset not empty ?>

                  </tbody>

                </table>

                <div class="KT_bottomnav">

                  <div>

                    <?php

            $nav_listmagazins1->Prepare();

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

                    <a class="KT_additem_op_link" href="magazin_add.html?KT_back=1" onclick="return nxt_list_additem(this)"><?php echo $xml->ajouter_nouveau; ?></a> 

                </div>

              </form>

	        </div>

  		    <br class="clearfixplain" />

          </div>

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

mysql_free_result($Recordset1);



mysql_free_result($Recordset2);



mysql_free_result($rsmagazins1);

?>