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

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_magasins = "SELECT id_magazin, nom_magazin FROM magazins WHERE region = $default_region ORDER BY nom_magazin ASC";
$magasins = mysql_query($query_magasins, $magazinducoin) or die(mysql_error());
$row_magasins = mysql_fetch_assoc($magasins);
$totalRows_magasins = mysql_num_rows($magasins);

$categorie		= isset($_GET['categorie'])?$_GET['categorie']:"";
$sous_categorie	= isset($_GET['sous_categorie'])?$_GET['sous_categorie']:"";
$mot_cle		= isset($_GET['mot_cle']) ? $_GET['mot_cle']:"";
$adresse		= isset($_GET['adresse'])?$_GET['adresse']:"";
$rayon			= isset($_GET['rayon'])?$_GET['rayon']:"";
$order			= isset($_GET['order'])?$_GET['order']:"date_debut";
$magasin 		= isset($_GET['magasin'])?$_GET['magasin']:"";
?>

<div style="float:left; width:220px">
	<div class="widget-container widget_search" id="coopons">
       <?php include("modules/cart.php"); ?>
      <h3><?php echo $xml->Affiner_votre_recherche; ?> </h3>
        	
            
            <div class="critere_filtre"><strong><?php echo $xml->Par_magasin; ?>:</strong></br></div>
             <div style="float:right; width:170px ; margin-top: 15px;">
             <select name="magasin" onchange="ajax(this.value,'#result');">
             <option value=""><?php echo $xml->tous_magasin; ?></option>
                       <?php do { ?>
                         
                           
                           <option value="<?php echo "ajax/resultat_recherche_cpn.php?categorie=$categorie&sous_categorie=$sous_categorie&mot_cle=$mot_cle&adresse=$adresse&rayon=$rayon&order=$order&magasin=".$row_magasins['id_magazin']; ?>" <?php if($row_magasins['id_magazin'] == $magasin) echo "selected"; ?>><?php echo $row_magasins['nom_magazin']; ?></option>
                                         
                         <?php } while ($row_magasins = mysql_fetch_assoc($magasins)); ?>
               </select>
                       
      </div>
            <div class="clear"></div>
         
            
    </div>
</div>


<?php

mysql_free_result($magasins);
?>
