<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_magazinducoin = "localhost";
$database_magazinducoin = "magasin3_bdd";
$username_magazinducoin = "magasin3_develop";
$password_magazinducoin = "Sikofiko12";
$magazinducoin = mysql_pconnect($hostname_magazinducoin, $username_magazinducoin, $password_magazinducoin) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_select_db($database_magazinducoin, $magazinducoin);

session_start();

if(strpos($_SERVER['PHP_SELF'],'admin/') === FALSE and strpos($_SERVER['PHP_SELF'],'ajax/') === FALSE){
	require_once 'admin/include/XMLEngine.php';
	if ( !isset( $_SESSION['Language'] ) )
	{
		$_SESSION['Language'] = 'fr'; //Fran�ais par d�faut.
	}
	$xml = new XMLEngine( 'admin/xml/website.xml', $_SESSION['Language'] );
}
else if(strpos($_SERVER['PHP_SELF'],'ajax/') !== FALSE){
	require_once '../admin/include/XMLEngine.php';
	if ( !isset( $_SESSION['Language'] ) )
	{
		$_SESSION['Language'] = 'fr'; //Fran�ais par d�faut.
	}
	$xml = new XMLEngine( '../admin/xml/website.xml', $_SESSION['Language'] );
}
else {
	require_once 'include/XMLEngine.php';
	if ( !isset( $_SESSION['Language'] ) )
	{
		$_SESSION['Language'] = 'fr'; //Fran�ais par d�faut.
	}
	$xml = new XMLEngine( 'xml/website.xml', $_SESSION['Language'] );
}

$default_lan = "37.0625";
$default_lon = "-95.677068";
$default_api_gmaps = 'ABQIAAAAB-FHeR1w_90UqkS6N_68TRQjmYocDTszwqxGpI5DZaqmGhUdBxTBGYbTy6f2wVDRoldNtE8TkYTvlg';

$default_region = 0;
if(isset($_SESSION['region']))
	$default_region = $_SESSION['region'];
if(isset($_GET['region'])){
	$default_region 	= $_GET['region'];
	$_SESSION['region'] = $_GET['region'];
}

define('NOUVEAUTE',1);
define('VENTE_FLASH',2);
define('PRIX_CHOC',3);
define('REGION_4',4);
define('REGION_5',5);
	
	
function getVilleById($id){
	$query_villes = "SELECT nom FROM maps_ville WHERE id_ville = $id";
	$villes = mysql_query($query_villes) or die(mysql_error());
	$row_villes = mysql_fetch_assoc($villes);
	return $row_villes['nom'];
}

function getRegionById($id){
	$query_villes = "SELECT nom_region FROM region WHERE id_region = $id";
	$villes = mysql_query($query_villes) or die(mysql_error());
	$row_villes = mysql_fetch_assoc($villes);
	return $row_villes['nom_region'];
}

function dbtodate($date){
	$tab = explode('-',$date);
	return $tab[2]."-".$tab[1]."-".$tab[0];
}

function espace_pub($i,$titre,$tous_regionpub){
	if(isset($tous_regionpub[$i])) {
		$nb = count($tous_regionpub[$i]);
		$rnd = $nb>1 ? (rand(0,$nb-1)) : 0;
		$produit = $tous_regionpub[$i][$rnd];
		?>
        <div class="espace_pub" >
    
             <h3><?php echo $titre ?></h3>
			 <div id="slider<?php echo $i;?>">
     				 <?php //debut de la boucle 
								foreach( $tous_regionpub[$i] as $compte){
								
							?>
           					 <div style="width:100%">
                             <div style="float:left;padding:12px; height:100px;">
                      		  <a href="detail_produit.php?id=<?php echo $compte['id_produit'];?>&cat_id=<?php echo $compte['categorie'];?>&mag_id=<?php echo $compte['id_magazin'];?>" class="various3" style="min-height: 282px; height: auto;">
                                  <div class="image"> 
                                    <img src="timthumb.php?src=assets/images/produits/<?php echo $compte['photo1'] ?>&w=118&z=1">                               </div>
               				 </a>
                       		    <div class="titre" style="overflow:hidden;">
							<a href="detail_produit.php?id=<?php echo $compte['id_produit'];?>&cat_id=<?php echo $compte['categorie'];?>&mag_id=<?php echo $compte['id_magazin'];?>" class="box prd various3" style="min-height: 282px; height: auto; font-size:14px; font-weight:bold;">
								<?php echo substr($compte['titre_produit'],0,55); ?>
                           	</a>
                       		 </div>
                             </div>
                             <div style="width:100%;float:left">
                             <div class="prix">
               				     <div class="prix_grand">
									   <?php
                                        $reduction = getReduction($compte['id_magazin'],$compte['categorie'],$compte['sous_categorie']);
                                         echo number_format($compte['prix'] -(($compte['prix']*$reduction)/100),2,',',' '); 
                                         ?>&nbsp;&euro;
                               </div>
							      <?php 
									if(!empty($reduction)){ ?>
										<div class="prix_petit">
											<?php echo $compte['prix'] ?>&nbsp;&euro;
										</div>
                             			<div class="reduction">
                                -<?php echo $reduction ?>%
                            </div>
                          		  <?php } ?>
                                  </div>
                             </div>
        	 				</div> <!--fin div pub_1--> <?php }//fin de la boucle?> 
              </div>              
       	</div> 
      
        <?php }
}

function getReduction($id_magazin, $categorie, $sous_categorie){
	$query_villes = "SELECT reduction FROM coupons 
	WHERE id_magasin = $id_magazin AND ((categories = $categorie AND sous_categorie = $sous_categorie) OR min_achat > 0)";
	//echo $query_villes ;
	$villes = mysql_query($query_villes) or die(mysql_error());
	$row_villes = mysql_fetch_assoc($villes);
	return $row_villes['reduction'];
}
?>