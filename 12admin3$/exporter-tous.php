<?php require_once('../Connections/magazinducoin.php'); ?>
<?php
// Load the common classes
require_once('../includes/common/KT_common.php');

// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');

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

function creerFichier($fichierChemin, $fichierNom, $fichierExtension, $fichierContenu, $droit=""){
	$fichierCheminComplet = $_SERVER["DOCUMENT_ROOT"].$fichierChemin."/".$fichierNom;
	if($fichierExtension!=""){
	$fichierCheminComplet = $fichierCheminComplet.".".$fichierExtension;
	}
	 
	// création du fichier sur le serveur
	$leFichier = fopen($fichierCheminComplet, "wb");
	fwrite($leFichier,$fichierContenu);
	fclose($leFichier);
	 
	// la permission
	if($droit==""){
	$droit="0777";
	}
	 
	// on vérifie que le fichier a bien été créé
	$t_infoCreation['fichierCreer'] = false;
	if(file_exists($fichierCheminComplet)==true){
	$t_infoCreation['fichierCreer'] = true;
	}
	 
	// on applique les permission au fichier créé
	$retour = chmod($fichierCheminComplet,intval($droit,8));
	$t_infoCreation['permissionAppliquer'] = $retour;
	 
	return $t_infoCreation;
}

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_deparetements = "SELECT journal.*, departement.id_region FROM journal LEFT JOIN departement ON journal.departement = departement.id_departement WHERE journal.date LIKE '".date('Y-m')."%' ";
$deparetements = mysql_query($query_deparetements, $magazinducoin) or die(mysql_error());
$totalRows_deparetements = mysql_num_rows($deparetements);
$produits = array();
$coupons = array();
$evenements = array();
$regions = array();
while($row_deparetements = mysql_fetch_assoc($deparetements)){
	if($row_deparetements['element_type'] == "produits")		
		$produits[$row_deparetements['departement']][] = $row_deparetements['element_id'];
	if($row_deparetements['element_type'] == "coupons")		
		$coupons[$row_deparetements['departement']][] = $row_deparetements['element_id'];
	if($row_deparetements['element_type'] == "evenements")	
		$evenements[$row_deparetements['departement']][] = $row_deparetements['element_id'];
	$regions[$row_deparetements['departement']] = $row_deparetements['id_region'];
}
if(count($produits)){
	foreach($produits as $k=>$p){
		$fichierContenu[$k] = "PRODUITS \n";
		if(count($p)){
			$detail = mysql_query("SELECT description, titre, prix2, photo1 FROM `produits` WHERE id IN (".implode(',',$p).") ", $magazinducoin) or die(mysql_error());
			while($rows_detail = mysql_fetch_assoc($detail)){
				$fichierContenu[$k] .= utf8_decode($rows_detail['titre'])." | ".$rows_detail['prix2']."€ | ".utf8_decode(substr($rows_detail['description'],0,50))." | ".$rows_detail['photo1']."\n";
			}
		}
	}
}
if(count($coupons)){
	foreach($coupons as $k=>$p){
		$fichierContenu[$k] .= "\nCOUPONS \n";
		if(count($p)){
			$detail = mysql_query("SELECT description, titre, reduction FROM `coupons` WHERE id_coupon IN (".implode(',',$p).") ", $magazinducoin) or die(mysql_error());
			while($rows_detail = mysql_fetch_assoc($detail)){
				$fichierContenu[$k] .= utf8_decode($rows_detail['titre'])." | ".$rows_detail['reduction']."% | ".utf8_decode(substr($rows_detail['description'],0,50))."\n";
			}
		}
	}
}
if(count($evenements)){
	foreach($evenements as $k=>$p){
		$fichierContenu[$k] .= "\nEvénements \n";
		if(count($p)){
			$detail = mysql_query("SELECT description, titre, date_debut, date_fin FROM `evenements` WHERE event_id IN (".implode(',',$p).") ", $magazinducoin) or die(mysql_error());
			while($rows_detail = mysql_fetch_assoc($detail)){
				$fichierContenu[$k] .= utf8_decode($rows_detail['titre'])." | ".$rows_detail['date_debut']." | ".$rows_detail['date_fin']." | ".utf8_decode(substr($rows_detail['description'],0,50))."\n";
			}
		}
	}
}

$fichierChemin = "/v3/pdfs";
$fichierExtension = "txt";
foreach($fichierContenu as $k=>$v){
	$fichierNom = $k ."-" .date('m-Y');
	$t_infoCreation = creerFichier($fichierChemin, $fichierNom, $fichierExtension, $v);
	mysql_query("INSERT INTO journal_export (mois,departement,region,active,fichier) VALUES ('".date('m-Y')."',$k,".$regions[$k].",1,'".$fichierNom.".".$fichierExtension."')", $magazinducoin) or die(mysql_error());
	echo "Cr&eacute;ation du journal : <a href='http://".$_SERVER['HTTP_HOST'].$fichierChemin. "/".$fichierNom.".".$fichierExtension."' target='_blank'>".$fichierNom.".".$fichierExtension."<a><br>";
}
echo "<br><br><a href='journals.php'>Retour</a>";
mysql_free_result($deparetements);
?>