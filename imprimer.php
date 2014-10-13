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
	$query_Recordset1 = "SELECT magazins.nom_magazin, magazins.adresse, magazins.latlan, magazins.telephone , coupons.reduction, coupons.*, category.cat_name, maps_ville.nom AS ville, (SELECT COUNT(*)  FROM produits WHERE id_magazin = magazins.id_magazin) AS nb_produits, magazins.photo1
	FROM (((coupons
	LEFT JOIN magazins ON magazins.id_magazin=coupons.id_magasin)
	LEFT JOIN category ON category.cat_id=coupons.categories)
	LEFT JOIN maps_ville ON maps_ville.id_ville=magazins.ville) WHERE coupons.id_coupon IN ($les_ids)";
	$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die("jj".mysql_error());
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);
}

if(count($_SESSION['courses'])){
	$copn = array();
	foreach($_SESSION['courses'] as $k => $v)
		$copn[] = $k;
	
	$les_ids = implode(',',$copn);
	$query_produits = "SELECT coupons.titre AS titre_coupon, magazins.*, magazins.nom_magazin, magazins.adresse, magazins.ville, magazins.latlan, produits.categorie,produits.id_magazin, produits.sous_categorie, produits.titre,produits.id, produits.reference, produits.prix, produits.prix2, produits.en_stock, produits.code_bare, produits.description, produits.photo1, produits.reduction, produits.count_print, coupons.date_fin, coupons.date_debut, coupons.categories, magazins.date_mor, magazins.date_eve, magazins.jours_ouverture, (SELECT COUNT(*) FROM evenements WHERE id_magazin = produits.id_magazin) AS nb_events , (SELECT COUNT(*) FROM coupons WHERE id_magasin = produits.id_magazin) AS nb_coupons
	FROM ((produits
	LEFT JOIN magazins ON magazins.id_magazin=produits.id_magazin)
	LEFT JOIN coupons ON coupons.id_coupon=produits.reduction) WHERE produits.id IN ($les_ids)";
	
	$produits = mysql_query($query_produits, $magazinducoin) or die("kk".mysql_error());
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
	  magazins.telephone,
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("modules/head.php"); ?>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 
	<title>Magasin du coin | Imprimer les coupons </title>
    <style media="print">
		.imp{
			display:none;
		}
	</style>
<body>
<h1 style="margin:10px auto; width:946px">www.magasinducoin.com - Liste du <?php echo date('d/m/Y'); ?><a class="imp" style="float:right" href="javascript:print();">Imprimer</a></h1>


<h2 style="margin:10px auto; width:946px">Liste des coupons</h2>
<div class="lister" style="margin:10px auto; width:946px">
<?php 
if(count($_SESSION['coupons'])){
while($row_Recordset1 = mysql_fetch_assoc($Recordset1)){ ?>
<table cellpadding="0" cellspacing="0" border="0" width="963" align="center">
<tr ><td height="14"colspan="3"><img src="template/images/trh1.png"></td></tr>
<tr><td><img src="template/images/trv1.png" height="311"></td><td height="311">
<?php
	if(isset($_REQUEST['testing'])){
	$count = $row_Recordset1['count_print']+1;

	$sql_pro  = "UPDATE coupons SET count_print='".$count."' WHERE id_coupon='".$row_Recordset1['id_coupon']."'";
	$result_pro  = mysql_query($sql_pro ) or die (mysql_error());
	}
?>
<div id="imprimer" style="position:relative">
  <div id="imp_inner1">
    <div id="imp_inner_img"><img src="timthumb.php?w=293&h=193&zc=1&src=assets/images/magasins/<?php echo $row_Recordset1['photo1']; ?>" /></div>
    <div id="imp_inner_txt">
      <div id="inner1">
        <div id="titre_imp"><?php echo $row_Recordset1['titre']; ?></div>
        <div id="date_validation">Valable du </br>  <?php echo dbtodate($row_Recordset1['date_debut']); ?> au <?php  echo dbtodate($row_Recordset1['date_fin']); ?></div>
      </div>
      <div id="inner2"><?php echo $row_Recordset1['adresse']; ?><br /><?php echo $row_Recordset1['ville']; ?><br /><?php echo $row_Recordset1['telephone']; ?>
</div>
      <div id="inner3">
        <div id="valeur"><?php //echo $row_Recordset1['reduction']."%"; ?></div>
        <div id="nom_valeur">Valable chez<br /><?php echo $row_Recordset1['nom_magazin']; ?> </div>
      </div>
    </div>
  </div>
  <div id="imp_inner2">
    <div id="imp_inner2_con1">
      <div id="img"><img src="sample-gd.php?code_bare=<?php echo $row_Recordset1['code_bare']; ?>"  /></div>
    </div>
    <div id="imp_inner2_con2">
    <div style="width:425px;float:left;"> 
   <?php echo Reduire_Chaine($row_Recordset1['description'],40); ?>
      </div>
      <div style="width:268px;float:left;position:absolute;right:0px;bottom:0px;">
      <img src="template/images/logo2.png" alt="" />
      </div>
      
    <div style="position:absolute; bottom:0; left:300px;"><img src="template/images/lien.png" alt="Magasin Du Coin" /></div>
       <br />
      </div>
  </div>
</div>
</td><td><img src="template/images/trv2.png"></td></tr>
<tr><td colspan="3" height="21"><img src="template/images/trh2.png"></td></tr>
</table>
<?php }}
else echo "Liste des coupons vide!!"; ?>
</div>


<h2  style="margin:10px auto; width:946px">Liste des courses</h2>
<div class="lister" style="margin:10px auto; width:946px">
<?php
if(count($_SESSION['courses'])){
while($liste = mysql_fetch_assoc($produits)){ ?>
<table cellpadding="0" cellspacing="0" border="0" width="963" align="center">
<tr ><td height="14"colspan="3"><img src="template/images/trh1.png"></td></tr>
<tr><td><img src="template/images/trv1.png" height="311"></td><td height="311">
<?php
	if(isset($_REQUEST['testing'])){
	$count = $liste['count_print']+1;

	$sql_pro  = "UPDATE produits SET count_print='".$count."' WHERE id='".$liste['id']."'";
	$result_pro  = mysql_query($sql_pro ) or die (mysql_error());
	}
?>
<div id="imprimer" style="position:relative">
  <div id="imp_inner1">
    <div id="imp_inner_img"><img src="timthumb.php?w=293&h=193&zc=1&src=assets/images/produits/<?php echo $liste['photo1']; ?>" /></div>
    <div id="imp_inner_txt">
      <div id="inner1">
        <div id="titre_imp"><?php echo $liste['titre']; ?></div>
        <div id="date_validation">
        
        <!--Horaire d'ouverture-->
        <div style="font-size:12px; position: absolute; right:0px;">
        <?php /*?><table cellpadding="2" cellspacing="0" border="0">
		<?php if($liste['day1']=='1'){?>
        <tr>
            <td align="right">
                Lundi :
            </td>
            <td align="left">
                <?php echo $liste['date1_m'];?> <?php echo $liste['date1_e'];?>
            </td>
        </tr>
        <?php }?>
        <?php if($liste['day2']=='1'){?>
        <tr>
            <td align="right">
                Mardi :
            </td>
            <td align="left">
                <?php echo $liste['date2_m'];?> <?php echo $liste['date2_e'];?>
            </td>
        </tr>
        <?php }?>
        <?php if($liste['day3']=='1'){?>
        <tr>
            <td align="right">
                Mercredi :
            </td>
            <td align="left">
                <?php echo $liste['date3_m'];?> <?php echo $liste['date3_e'];?>
            </td>
        </tr>
        <?php }?>
        <?php if($liste['day4']=='1'){?>
        <tr>
            <td align="right">
                Jeudi :
            </td>
            <td align="left">
                <?php echo $liste['date4_m'];?> <?php echo $liste['date4_e'];?>
            </td>
        </tr>
        <?php }?>
        <?php if($liste['day5']=='1'){?>
        <tr>
            <td align="right">
                Vendredi :
            </td>
            <td align="left">
                <?php echo $liste['date5_m'];?> <?php echo $liste['date5_e'];?>
            </td>
        </tr>
        <?php }?>
        <?php if($liste['day6']=='1'){?>
        <tr>
            <td align="right">
                Samdi :
            </td>
            <td align="left">
                <?php echo $liste['date6_m'];?> <?php echo $liste['date6_e'];?>
            </td>
        </tr>
        <?php }?>
        <?php if($liste['day7']=='1'){?>
        <tr>
            <td align="right">
                Dimanche :
            </td>
            <td align="left">
                <?php echo $liste['date7_m'];?> <?php echo $liste['date7_e'];?>
            </td>
        </tr>
        <?php }?>
    </table><?php */?>
        

		</div>
	</div>
    
      </div>
      <div id="inner2"><?php echo $liste['adresse']; ?><br /><?php echo getVilleById($liste['ville']); ?><br /><?php echo $liste['telephone']; ?>
</div>
      <div id="inner3">
     	<div class="prix_inner" style="float:left; width:150px; position:relative; margin-top: 18px;">
      		<div class="box_prix" style="width:165px; color: #9D216E; font-weight:normal;  font-size: 14px;   text-align: center;">
                    <span class="prix" style="font-size: 27px; font-weight:bold; <?php if($liste['reduction']>0) echo 'text-decoration:line-through'; ?>">
                        <?php echo $liste['prix']."&euro;"; ?>
                    </span>
                    <?php if($liste['en_stock']==1){echo "<br>".$xml->En_stock;} ?>
                    <?php if($liste['reduction']>0) { ?>
                        <div class="reductionR"><?php echo $liste['reduction']; ?>%</div> 
                        <div class="prixR" style="font-weight: bold;    left: 55px;    position: relative;    top: 21px;;"><?php echo $liste['prix2']."&euro;"; ?></div>
                    <?php } ?>
     		</div>
            </div>
        <div id="nom_valeur">Valable chez<br /><?php echo $liste['nom_magazin']; ?> </div>
      </div>
    </div>
  </div>
  <div id="imp_inner2">
    <div id="imp_inner2_con1">
      <div id="img"><img src="sample-gd.php?code_bare=<?php echo $liste['code_bare']; ?>"  /></div>
    </div>
    <div id="imp_inner2_con2">
    <div style="width:425px;float:left;"> 
   <?php echo Reduire_Chaine($liste['description'],40); ?>
      </div>
      <div style="width:268px;float:left;position:absolute;right:0px;bottom:0px;">
      <img src="template/images/logo2.png" alt="" />
      </div>
      
    <div style="position:absolute; bottom:0; left:300px;"><img src="template/images/lien.png" alt="Magasin Du Coin" /></div>
       <br />
      </div>
  </div>
</div>
</td><td><img src="template/images/trv2.png"></td></tr>
<tr><td colspan="3" height="21"><img src="template/images/trh2.png"></td></tr>
</table>
<?php }}
else echo "Liste des courses vide!!"; ?>
</div>


<h2 style="margin:10px auto; width:946px">Liste des Événements</h2>
<div class="lister" style="margin:10px auto; width:946px">
<?php 
if(count($_SESSION['event'])){
while($liste = mysql_fetch_assoc($events)){ ;?>
<table cellpadding="0" cellspacing="0" border="0" width="963" align="center">
<tr ><td height="14"colspan="3"><img src="template/images/trh1.png"></td></tr>
<tr><td><img src="template/images/trv1.png" height="311"></td><td height="311">
<?php
	if(isset($_REQUEST['testing'])){
	$count = $liste['count_print']+1;

	$sql_pro  = "UPDATE evenements SET count_print='".$count."' WHERE event_id='".$liste['event_id']."'";
	$result_pro  = mysql_query($sql_pro ) or die (mysql_error());
	}
?>
<div id="imprimer" style="position:relative">
  <div id="imp_inner1">
    <div id="imp_inner_img"><img src="timthumb.php?w=293&h=193&zc=1&src=assets/images/magasins/<?php echo $liste['photo1']; ?>" /></div>
    <div id="imp_inner_txt">
      <div id="inner1">
        <div id="titre_imp"><?php echo $liste['titre']; ?></div>
        <div id="date_validation">Valable du </br>  <?php echo dbtodate($liste['date_debut']); ?> au <?php  echo dbtodate($liste['date_fin']); ?></div>
      </div>
      <div id="inner2"><?php echo $liste['adresse']; ?><br /><?php echo $liste['ville']; ?><br /><?php echo $liste['telephone']; ?>
</div>
      <div id="inner3">
        <div id="valeur"><?php //echo $liste['reduction']."%"; ?></div>
        <div id="nom_valeur">Valable chez<br /><?php echo $liste['nom_magazin']; ?> </div>
      </div>
    </div>
  </div>
  <div id="imp_inner2">
    <div id="imp_inner2_con1">
      <div id="img"><img src="sample-gd.php?code_bare=<?php echo $liste['code_bare']; ?>"  /></div>
    </div>
    <div id="imp_inner2_con2">
    <div style="width:425px;float:left;"> 
   <?php echo Reduire_Chaine($liste['description'],40); ?>
      </div>
      <div style="width:268px;float:left;position:absolute;right:0px;bottom:0px;">
      <img src="template/images/logo2.png" alt="" />
      </div>
      
    <div style="position:absolute; bottom:0; left:300px;"><img src="template/images/lien.png" alt="Magasin Du Coin" /></div>
       <br />
      </div>
  </div>
</div>
</td><td><img src="template/images/trv2.png"></td></tr>
<tr><td colspan="3" height="21"><img src="template/images/trh2.png"></td></tr>
</table>
<?php }}
else echo "Liste des Événements vide!!"; ?>
</div>


</body>
</html>



