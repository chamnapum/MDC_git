<?php require_once('Connections/magazinducoin.php'); ?><?php 
 if($default_region == 0) header('Location: index.php'); ?>
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

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_liste_magasins = "SELECT region.nom_region, magazins.nom_magazin, maps_ville.nom, magazins.adresse, magazins.photo1, magazins.id_magazin, magazins.region, (SELECT COUNT(*) FROM coupons WHERE coupons.id_magasin = magazins.id_magazin AND date_fin  >= CURDATE() AND date_debut <= CURDATE()) AS nb_coupons FROM ((magazins LEFT JOIN region ON region.id_region=magazins.region) LEFT JOIN maps_ville ON maps_ville.id_ville=magazins.ville) WHERE magazins.region=$default_region ";
//echo $query_liste_magasins;
$liste_magasins = mysql_query($query_liste_magasins, $magazinducoin) or die(mysql_error());
$row_liste_magasins = mysql_fetch_assoc($liste_magasins);
$totalRows_liste_magasins = mysql_num_rows($liste_magasins);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 
	<title>Magazin Du Coin </title>
    <?php include("modules/head.php"); ?>
</head>
<body id="sp" onload="<?php if(isset($_GET['page_ajax'])) echo "ajax('".$_GET['page_ajax']."','#result');"; ?>" >


	
<div id="content">
				 <?php include("modules/header.php"); ?>
        		 <div class="top reduit">
                    <?php include("modules/menu.php"); ?>
            	</div>
			   <?php //include ("modules/menu_filtre_coupons.php"); ?>
               <div class="contenue">
       			<div id="result" class="coupon">
                    <h3>Coupons valable pour le <?php echo date('d-m-Y'); ?></h3>
                    <?php do { 
                            if($row_liste_magasins['nb_coupons'] > 0) {
                                //AND coupons.date_debut < CURDATE() AND coupons.date_fin > CURDATE() 
                         ?>
                    <a class="magasins" href="javascript:;" onclick="ajax('ajax/coupons.php?magasin=<?php echo $row_liste_magasins['id_magazin']; ?>','#result');">
                      <div class="titre"><?php echo $row_liste_magasins['nom_magazin']; ?></div>
                      <div class="image"><img src="timthumb.php?w=70&h=50&zc=1&src=images/magasins/<?php echo $row_liste_magasins['photo1']; ?>" /></div>
                   </a>
                <?php }
				} while ($row_liste_magasins = mysql_fetch_assoc($liste_magasins)); ?></div>
  			 	
			<?php include("modules/cart.php"); ?>
            </div>
</div>
  		        
<div id="footer">
    		<?php include("modules/region_barre_recherche.php"); ?>
        <div class="liens">
       		<?php include("modules/footer.php"); ?>
		</div>
</div>
</body>
</html>
<?php
mysql_free_result($liste_magasins);
?>