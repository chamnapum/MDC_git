<?php require_once('Connections/magazinducoin.php'); ?>

<?php

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



function Reduire_Chaine($string, $word_limit)

{

  $string=strip_tags($string);

  $words = explode(' ', $string, ($word_limit + 1));

  if(count($words) > $word_limit){

    array_pop($words);$fin='...';

  }else

    $fin='';

  return implode(' ', $words).$fin;

}





if(count($_SESSION['coupons'])>0){

	$copn = array();

	foreach($_SESSION['coupons'] as $k => $v)

		$copn[] = $k;

	

	$les_ids = implode(',',$copn);

	

	mysql_select_db($database_magazinducoin, $magazinducoin);

	$query_Recordset1 = "SELECT magazins.nom_magazin, magazins.adresse, magazins.latlan, coupons.reduction, coupons.*, category.cat_name, maps_ville.nom AS ville, (SELECT COUNT(*)  FROM produits WHERE id_magazin = magazins.id_magazin) AS nb_produits, magazins.photo1

	FROM (((coupons

	LEFT JOIN magazins ON magazins.id_magazin=coupons.id_magasin)

	LEFT JOIN category ON category.cat_id=coupons.categories)

	LEFT JOIN maps_ville ON maps_ville.id_ville=magazins.ville) WHERE coupons.id_coupon IN ($les_ids)";

	$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());

	$totalRows_Recordset1 = mysql_num_rows($Recordset1);

}



if(count($_SESSION['courses'])){

	$copn = array();

	foreach($_SESSION['courses'] as $k => $v)

		$copn[] = $k;

	

	$les_ids = implode(',',$copn);

	$query_produits = "SELECT coupons.titre AS titre_coupon, magazins.*, magazins.nom_magazin, magazins.adresse, magazins.ville, magazins.latlan, produits.categorie,produits.id_magazin, produits.sous_categorie, produits.titre,produits.id, produits.reference, produits.prix, produits.en_stock, produits.description, produits.photo1, coupons.reduction, coupons.date_fin, coupons.date_debut, coupons.categories, (SELECT COUNT(*) FROM evenements WHERE id_magazin = produits.id_magazin) AS nb_events , (SELECT COUNT(*) FROM coupons WHERE id_magasin = produits.id_magazin) AS nb_coupons

	FROM ((produits

	LEFT JOIN magazins ON magazins.id_magazin=produits.id_magazin)

	LEFT JOIN coupons ON coupons.id_coupon=produits.reduction) WHERE produits.id IN ($les_ids)";

	$produits = mysql_query($query_produits, $magazinducoin) or die(mysql_error());

	$totalRows_produits = mysql_num_rows($produits);

}



if(count($_SESSION['event'])){

	$copn = array();

	foreach($_SESSION['event'] as $k => $v)

		$copn[] = $k;

	

	$les_ids = implode(',',$copn);

	$query_events = "SELECT 

	  evenements.*,

	  magazins.nom_magazin,

	  magazins.adresse,

	  magazins.latlan,

	  maps_ville.nom AS ville,

	  magazins.photo1 

	FROM

	  (

		(

		  (

			evenements 

			LEFT JOIN magazins 

			  ON magazins.id_magazin = evenements.id_magazin

		  ) 

		  LEFT JOIN category 

			ON category.cat_id = evenements.category_id

		) 

		LEFT JOIN maps_ville 

		  ON maps_ville.id_ville = magazins.ville

	  ) WHERE evenements.event_id IN ($les_ids)";

	

	$events = mysql_query($query_events, $magazinducoin) or die("kk".mysql_error());

	$totalRows_events = mysql_num_rows($events);

}



	$destinataire = $_SESSION['kt_login_user'];

	$objet = "Votre Liste des courses, coupons , evenements" ;

	$message = email();

	//echo $message;

	/* Si l’on veut envoyer un mail au format HTML, il faut configurer le type Content-type. */

	$headers = "MIME-Version: 1.0\n";

	$headers .= "Content-type: text/html; charset=iso-8859-1\n";

	

	/* Quelques types d’entêtes : errors, From cc's, bcc's, etc */

	$headers .= "From: Magasin Du Coin <contact@magasinducoin.fr>\n";

	

	

	// On envoi l’email

	if ( mail($destinataire, $objet, $message, $headers) ) die( "Envoi du mail réussi.");

	else die( "Echec de l’envoi du mail.");

	

function email(){

global $produits, $Recordset1;



$msg ='<link rel="stylesheet" type="text/css" href="http://magasinducoin.fr/template/css/style.css" />';

$msg .= '<h1 style="margin:10px auto; width:946px">www.magasinducoin.com - Liste du '.date('d/m/Y').'</h1>

<h2>Liste des coupons</h2>';

if(count($_SESSION['coupons'])){

while($row_Recordset1 = mysql_fetch_assoc($Recordset1)){ 

	$msg.= '<table cellpadding="0" cellspacing="0" border="0" width="963">

<tr ><td height="14"colspan="3"><img src="http://magasinducoin.fr/template/images/trh1.png"></td></tr>

<tr><td><img src="http://magasinducoin.fr/template/images/trv1.png" height="311"></td><td height="311">



<div id="imprimer" style="position:relative">

  <div id="imp_inner1">

    <div id="imp_inner_img"><img src="http://magasinducoin.fr/timthumb.php?src=assets/images/magasins/'.$row_Recordset1['photo1'].'&w=293&h=193&zc=1" /></div>

    <div id="imp_inner_txt">

      <div id="inner1">

        <div id="titre_imp">'. substr($row_Recordset1['titre'],0,19).'</div>

        <div id="date_validation">Valable du </br>  '. dbtodate($row_Recordset1['date_debut']).' au ' .dbtodate($row_Recordset1['date_fin']).'</div>

      </div>

      <div id="inner2">'. $row_Recordset1['adresse'].'<br />'.$row_Recordset1['ville'].'</div>

      <div id="inner3">

        <div id="valeur"></div>

        <div id="nom_valeur">Valable chez<br />'.  $row_Recordset1['nom_magazin'].'</div>

      </div>

    </div>

  </div>

  <div id="imp_inner2">

    <div id="imp_inner2_con1">

      <div id="img"><img src="http://magasinducoin.fr/sample-gd.php?code_bare='.  $row_Recordset1['code_bare'].'"  /></div>

    </div>

    <div id="imp_inner2_con2">

    <div style="width:425px;float:left;"> 

    '.Reduire_Chaine($row_Recordset1['description'],40).'

      </div>

      <div style="width:268px;float:left;position:absolute;right:0px;bottom:0px;">

      <img src="http://magasinducoin.fr/template/images/logo2.png" alt="" />

      </div>

      

    <div style="position:absolute; bottom:0; left:300px;"><img src="http://magasinducoin.fr/template/images/lien.png" alt="Magasin Du Coin" /></div>

       <br />

      </div>

  </div>

</div>

</td><td><img src="http://magasinducoin.fr/template/images/trv2.png"></td></tr>

<tr><td colspan="3" height="21"><img src="http://magasinducoin.fr/template/images/trh2.png"></td></tr>

</table>

';}}

else $msg.= 'Liste des coupons vide!!';



$msg.= '<h2>Liste des courses</h2>

<div class="lister">';



if(count($_SESSION['courses'])){

while($liste = mysql_fetch_assoc($produits)){

$msg.= '<div class="box">

        <div class="box_inner">

            	<div class="boxtitre">

  <a class="various3"   href="http://magasinducoin.fr/detail_produit.php?id='.$liste['id'].'&cat_id='.$liste['categorie'].'&mag_id='.$liste['id_magazin'].'#tabs-1">'.$liste['titre'].'</a>

                </div>

                <div class="box_img"> 

                <a class="various3" href="http://magasinducoin.fr/detail_produit.php?id='. $liste['id'].'&cat_id='. $liste['categorie'].'&mag_id='.  $liste['id_magazin'].'&t=1#tabs-1">

                

                            <img src="http://magasinducoin.fr/timthumb.php?src=assets/images/produits/'.$liste['photo1'].'&z=1&w=125&h=90" />

               </a>

               <span class="boxville">'.getVilleById($liste['ville']).'</span>

                </div>

                <div class="box_desc" >

                     <div class="desc_inner"> '. substr($liste['description'],0,150).'</div>

                      <div class="prix_inner">

                              <div class="box_prix">

                              	<span class="prix"><strong>'.$liste['prix'].'&#8364;</strong></span>';

								if($liste['en_stock']==1){

								$msg.= "<br>En stock";

								}

                             $msg.= '</div>

                             <div class="box_mag">

                                 <a href="detail_produit.php?id='.$liste['id'].'&cat_id='.$liste['categorie'].'&mag_id='.$liste['id_magazin'].'&t=1#tabs-2"><span class="magazin">

                                '.$liste['nom_magazin'].'</span></a><br />

                                <span style="color:#000000; font-size:15px; font-weight:bold">'.$liste['adresse'].'</span>

                             </div>

                    </div> 

                </div>

               

        </div><hr />

</div>';



 }}

else $msg.= "Liste des courses vide!!"; 

$msg.= '</div>';



$msg.= '<h2>Liste des Événements</h2>';



if(count($_SESSION['event'])){

while($liste = mysql_fetch_assoc($events)){

$msg.= '<table cellpadding="0" cellspacing="0" border="0" width="963">

<tr ><td height="14"colspan="3"><img src="http://magasinducoin.fr/template/images/trh1.png"></td></tr>

<tr><td><img src="http://magasinducoin.fr/template/images/trv1.png" height="311"></td><td height="311">



<div id="imprimer" style="position:relative">

  <div id="imp_inner1">

    <div id="imp_inner_img"><img src="http://magasinducoin.fr/timthumb.php?w=293&h=193&zc=1&src=images/magasins/'.$liste['photo1'].'" /></div>

    <div id="imp_inner_txt">

      <div id="inner1">

        <div id="titre_imp">'. substr($liste['titre'],0,19).'</div>

        <div id="date_validation">Valable du </br>  '. dbtodate($liste['date_debut']).' au ' .dbtodate($liste['date_fin']).'</div>

      </div>

      <div id="inner2">'. $liste['adresse'].'<br />'.$liste['ville'].'</div>

      <div id="inner3">

        <div id="valeur"></div>

        <div id="nom_valeur">Valable chez<br />'.  $liste['nom_magazin'].'</div>

      </div>

    </div>

  </div>

  <div id="imp_inner2">

    <div id="imp_inner2_con1">

      <div id="img"><img src="sample-gd.php?code_bare='.  $liste['code_bare'].'"  /></div>

    </div>

    <div id="imp_inner2_con2">

    <div style="width:425px;float:left;"> 

    '.Reduire_Chaine($liste['description'],40).'

      </div>

      <div style="width:268px;float:left;position:absolute;right:0px;bottom:0px;">

      <img src="http://magasinducoin.fr/template/images/logo2.png" alt="" />

      </div>

      

    <div style="position:absolute; bottom:0; left:300px;"><img src="http://magasinducoin.fr/template/images/lien.png" alt="Magasin Du Coin" /></div>

       <br />

      </div>

  </div>

</div>

</td><td><img src="http://magasinducoin.fr/template/images/trv2.png"></td></tr>

<tr><td colspan="3" height="21"><img src="http://magasinducoin.fr/template/images/trh2.png"></td></tr>

</table>

';}}

else $msg.= 'Liste des Événements vide!!

</body>

</html>';



return $msg;

 } ?>

