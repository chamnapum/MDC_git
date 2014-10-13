<?php require_once('Connections/magazinducoin.php'); ?>
<?php
// Load the tNG classes
require_once('includes/tng/tNG.inc.php');
// Make unified connection variable
$conn_magazinducoin = new KT_connection($magazinducoin, $database_magazinducoin);
//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_magazinducoin, "");
//Grand Levels: Level
$restrict->addLevel("1");
$restrict->Execute();
//End Restrict Access To Page

      // recuperer id membre et id produit :
	  $idproduit=$_GET['p'];
	  $idmembre=$_SESSION['kt_login_id'];
	  
   	 //recuperer le titre de chaque produit :
	$rkt="SELECT magazins.region, produits.categorie, produits.titre
FROM (produits
LEFT JOIN magazins ON magazins.id_magazin=produits.id_magazin)
WHERE produits.id = $idproduit ";
			$query=mysql_query($rkt);
			$titreproduit=mysql_fetch_array($query);
			$titre= $titreproduit['titre'];
			$cat= $titreproduit['categorie'];
         	$region=$titreproduit['region'];
			$jr=30;
			$datefin = date("Y-m-d H:i:s", mktime(date("H"), date("i"), date("s"), date("m"), date("d")+$jr,  date("Y")));	
			$ids = array();
	if(count($_POST['pub'])){
	  foreach($_POST['pub'] as $k=>$v)
	  {
		  $sql="insert into pub (id_user,titre,region,emplacement,id_produit,date_fin)    values('$idmembre','$titre','$region','$k','$idproduit','$datefin')";
		  $query2=mysql_query($sql);
		  $ids[] = mysql_insert_id();
		  //	header('Location: payer_pub.php?id='.mysql_insert_id());
		  /*else {echo "insetion du produit non   reussi           ";}*/
      }
	 }
	if(count($ids))
	 	header('Location: payer_pub.php?ids='.implode(",",$ids));
	else
		header('Location: mes-produits.php');
	 
?>
