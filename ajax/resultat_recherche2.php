<?php 
require_once('../Connections/magazinducoin.php');
include_once("../class/GoogleMap.php");
include_once("../class/JSMin.php");

function cmp($a, $b) {
    if ($a == $b) {
        return 0;
    }
    return ($a < $b) ? -1 : 1;
}

function cmp2($a, $b) {
    if ($a == $b) {
        return 0;
    }
    return ($a > $b) ? -1 : 1;
}
?>
	<script type="text/javascript">
		$(document).ready(function() {
			$(".various3").fancybox({
				'width'				: '100%',
				'height'			: '100%',
				'autoScale'			: true,
				'transitionIn'		: 'elastic',
				'transitionOut'		: 'none',
				'type'				: 'iframe',
				'speedIn'           : 700
			});
		});
	</script>


<?php   
$categorie		= isset($_GET['categorie'])?$_GET['categorie']:"";
$sous_categorie	= isset($_GET['sous_categorie'])?$_GET['sous_categorie']:"";
$mot_cle		= isset($_GET['mot_cle']) ? $_GET['mot_cle']:"";
if(isset($_GET['adresse']) and !empty($_GET['adresse']))
	$adresse = $_GET['adresse'];
else if(isset($_SESSION['kt_adresse']))
	$adresse = $_SESSION['kt_adresse'];
else
	$adresse = "";
$rayon			= isset($_GET['rayon'])?$_GET['rayon']:"";
$cpnMax		= isset($_GET['cpnMax'])?$_GET['cpnMax']:"";
$cpnMin		= isset($_GET['cpnMin'])?$_GET['cpnMin']:"";
$order			= isset($_GET['order'])?$_GET['order']:"date_debut";
$magasin 		= isset($_GET['magasin'])?$_GET['magasin']:"";
$coupon 		= isset($_GET['coupon'])?$_GET['coupon']:"";


$query_liste_produit = "SELECT magazins.nom_magazin, magazins.logo, magazins.id_magazin, magazins.adresse, magazins.heure_ouverture, magazins.jours_ouverture, magazins.latlan, coupons.reduction, coupons.id_coupon, coupons.date_debut, coupons.date_fin, coupons.titre, coupons.min_achat, category.cat_name, maps_ville.nom AS ville, (SELECT COUNT(*)  FROM produits WHERE id_magazin = magazins.id_magazin) AS nb_produits, magazins.photo1
FROM (((coupons
LEFT JOIN magazins ON magazins.id_magazin=coupons.id_magasin)
LEFT JOIN category ON category.cat_id=coupons.categories)
LEFT JOIN maps_ville ON maps_ville.id_ville=magazins.ville)";



$tab = array();

						$tab[] = " magazins.region = ".$_SESSION['region'];
						$tab[] = " coupons.date_debut <= '".date('Y-m-d')."'";
						$tab[] = " coupons.date_fin >= '".date('Y-m-d')."'";
if($mot_cle and strpos($mot_cle,'mot') === FALSE) $tab[] = " coupons.titre LIKE '%$mot_cle%' ";
if($magasin)			$tab[] = " magazins.id_magazin = $magasin ";
if($coupon) 			$tab[] = " coupons.id_coupon = ".$coupon;
/*if($sous_categorie)		$tab[] = " produits.sous_categorie = $sous_categorie ";

if($cpnMax) 			$tab[] = " produits.cpn BETWEEN $cpnMin AND $cpnMax ";*/


$where = "";
if(count($tab)) $where = "WHERE ".implode(' AND ',$tab);
$query_liste_produit .= $where;
$query_liste_produit .= " ORDER BY date_debut DESC";
//echo $query_liste_produit;
// pagination 
//nb_ total des produits 
$query=mysql_query($query_liste_produit);
$nbr_total=mysql_num_rows($query);

//initialiser les variables du pagination

if(isset($_GET['start'])){$start=$_GET['start'];}else{$start=0;}
if(isset($_GET['nb_par_page'])){$nb_par_page=$_GET['nb_par_page'];}else{$nb_par_page=10;}

$query_liste_produits=$query_liste_produit." LIMIT $start, $nb_par_page";
//echo $query_liste_produits;
//echo $query_liste_produits;
$nb_des_pages=ceil($nbr_total/$nb_par_page);

$lastpage = ceil($nbr_total/$nb_par_page);
//fin code pagination
?>


<div  class="tri">
	 <div class="tri_inner" >
    	<div class="tri_inner_left"><?php echo $xml->Trier_par ; ?>:
        <select name="distance" onchange="ajax(this.value,'#result');">
        	<option value="<?php echo $_SERVER['PHP_SELF']."?categorie=$categorie&sous_categorie=$sous_categorie&mot_cle=$mot_cle&adresse=$adresse&rayon=$rayon&cpnMax=$cpnMax&cpnMin=$cpnMin&order=distance"; ?>" <?php if($order == 'distance') echo "selected"; ?>><?php echo $xml->Distance ?></option>
            <option value="<?php echo $_SERVER['PHP_SELF']."?categorie=$categorie&sous_categorie=$sous_categorie&mot_cle=$mot_cle&adresse=$adresse&rayon=$rayon&cpnMax=$cpnMax&cpnMin=$cpnMin&order=date_debut"; ?>" <?php if($order == 'date_debut') echo "selected"; ?>>Date</option>
        </select></div>
            <div class=" counte">
               <?php  echo $nbr_total . " " .$xml->produits_correspondent_a_votre_recherche; ?>
            </div>
    	</div>
	</div>
   
<?php



//echo $query_liste_produit; 
$MAP_OBJECT = new GoogleMapAPI(); 
$MAP_OBJECT->_minify_js = isset($_REQUEST["min"])?FALSE:TRUE;
if($adresse) 
	$adresse_client = $MAP_OBJECT->getGeoCode("$adresse, France");

$rkt= mysql_query($query_liste_produits);
if((isset($_GET['order']) and $_GET['order'] != "distance") or !isset($_GET['order']) or empty($_GET['adresse'])){
//die("gg");



while($liste = mysql_fetch_assoc($rkt)){
	if(isset($adresse_client)){
		$adresse_produit = str_replace('(','',$liste['latlan']);
		$adresse_produit = str_replace(')','',$adresse_produit);
		$adresse_produit_tab = explode(',',$adresse_produit);
		$distance =  $MAP_OBJECT->geoGetDistance($adresse_client['lat'],$adresse_client['lon'],$adresse_produit_tab[0],$adresse_produit_tab[1],'K');
	}
	else{
		$distance = 0;
	}
	//echo "Distance entre adresse client et adresse produit est : $distance <br>";
	if($distance <= $rayon) {
?>

	<div class="box" id="coopons">
   
        <div class="box_inner">
            	<div class="boxtitre">
                
                
                        <div class="cpn1_titre"><?php echo $xml->Valide_du ?>  <?php echo dbtodate($liste['date_debut']); ?> <?php echo $xml->au ?>  <?php echo dbtodate($liste['date_fin']); ?></div>
                        <div class="cpn1_cat"><?php echo empty($liste['cat_name']) ? "Tout le magasin" : $liste['cat_name'];?></div>
                        <div class="cpn1_horaire"><?php echo $xml->ouvert_de; ?> <?php //echo utf8_decode($liste['jours_ouverture']); ?>
 <?php echo str_replace('-',' '.$xml->A.' ',$liste['heure_ouverture']); ?></div>
                </div>
                 <div class="box_img"> 
                    <img src="timthumb.php?src=assets/images/magasins/<?php echo $liste['photo1']; ?>&z=1&w=125&h=90" />
                    <span class="boxville"><?php echo $liste['ville']; ?></span>
                </div>
                <div class="box_desc" >
                     <div class="desc_inner"><?php echo $liste['titre'];?></div>
                     <span class="magazin"><a href="rechercher.php?adresse=&rayon=999&mot_cle=&categorie=&sous_categorie=&magasin=<?php echo $liste['id_magazin'];  ?>" style="color: #F6AE30;"><?php echo $liste['nb_produits'];?> <?php echo $xml->produits_disponibles_dans_ce_magasin ; ?></a></span> 
                      <div class="prix_inner">
                              <div class="box_prix">
                              	<span class="prix">
								<?php echo $liste['reduction']." %"; ?>
                                </span><br />
                             	 <?php if($liste['min_achat'] > 0)  echo "Pour ".$liste['min_achat']."&euro; d'achat"; ?>
                             </div>
                             <div class="box_position">
                              	<span class="marque"><?php if($liste['logo']){ ?> <img src="timthumb.php?src=assets/images/magasins/<?php echo $liste['logo']; ?>&z=1&w=80" /><?php } else echo $liste['nom_magazin'];?></span><div class="clear"></div>
                                <span style="color:#000000; font-size:15px; font-weight:bold; margin-left:25px"><?php echo $liste['adresse'];?></span>
                             	<?php if(isset($distance)) { ?>
                                    <div class="distance">
                                     Distance: <?php echo number_format($distance,2,',',' '); ?> KM
                                     </div>
       			 				<?php } ?>
                             </div>
                    </div> 
                </div>
        </div>
        <div class="box_event"> 
            <div class="box_evnt" style="width:176px; margin-right:0"><p><a href="javascript:;" onClick="ajax('ajax/addtocart.php?id_coupon=<?php echo $liste['id_coupon']; ?>','#cart');"><?php echo $xml->Ajouter_au_panier ; ?></a> </p></div><br />
            <div class="box_evnt" style="margin-top:0; background-color:#9D216E; width:176px; margin-right:0"><p><a class="various3" href="itener-mag.php?id_mag=<?php echo $liste['id_magazin'];?>"><?php echo $xml->Itineraire ; ?></a> </p>
            </div>
            <div class="box_evnt" style="margin-top:0; width:176px; margin-right:0"><p><a href="rechercher_cpn.php?adresse=&rayon=999&mot_cle=&categorie=&sous_categorie=&magasin=<?php echo $liste['id_magazin'];?>">
           <?php echo $xml->Tous_les_coupons_du_magasin ;?></a> </p>
            </div>
         </div>
	</div>

<?php }
}
}
else {
	$liste_produits_trie = array();
	while($liste = mysql_fetch_assoc($rkt)){
		if(isset($adresse_client)){
			$adresse_produit = str_replace('(','',$liste['latlan']);
			$adresse_produit = str_replace(')','',$adresse_produit);
			$adresse_produit_tab = explode(',',$adresse_produit);
			$distance =  $MAP_OBJECT->geoGetDistance($adresse_client['lat'],$adresse_client['lon'],$adresse_produit_tab[0],$adresse_produit_tab[1],'K');
			$liste_produits_trie[] = array($distance,$liste);
		}
		else {
			$liste_produits_trie[] = array(0,$liste);
		}
	}
	
	$asdec	= isset($_GET['asdec'])?$_GET['asdec']:"DESC";
	if($asdec == "ASC")
		uasort($liste_produits_trie, 'cmp');
	else
		uasort($liste_produits_trie, 'cmp2');
	
	foreach($liste_produits_trie as $l) { ?>
		<div class="box" id="coopons">
   
        <div class="box_inner">
            	<div class="boxtitre">
                        <div class="cpn1_titre"><?php echo $xml->Valide_du ?> 
						<?php echo dbtodate($l[1]['date_debut']); ?> <?php echo $xml->au ; ?> 
						<?php echo dbtodate($l[1]['date_fin']); ?></div>
                        <div class="cpn1_cat"><?php echo empty($l[1]['cat_name']) ? "Tout le magasin" : $l[1]['cat_name'];?></div>
                </div>
                 <div class="box_img"> 
                    <img src="timthumb.php?src=assets/images/magasins/<?php echo $l[1]['photo1']; ?>&z=1&w=140&h=100" />
                </div>
                <div class="box_desc" >
                     <div class="desc_inner"><?php echo $l[1]['titre'];?></div>
                     <span class="magazin"><?php echo $l[1]['nb_produits'];?>
					 <?php echo $xml->produits_disponibles_dans_ce_magasin ; ?>:</span> 
                      <div class="prix_inner">
                              <div class="box_prix">
                              	<span class="prix">
								<?php echo $l[1]['reduction']." %"; ?>
                                </span><br />
                             	 <?php if($l[1]['min_achat'] > 0)  echo "Pour ".$l[1]['min_achat']."&euro; d'achat"; ?>
                             </div>
                             <div class="box_position">
                              	<span class="marque"><?php echo $l[1]['nom_magazin'];?></span>
                             	<?php if(isset($distance)) { ?>
                                    <div class="distance">
                                     <?php echo $l[1]['ville'];?>  -  <?php $xml->Distance?>: <?php echo number_format($distance,2,',',' '); ?> KM
                                     </div>
       			 				<?php } ?>
                             </div>
                    </div> 
                </div>
        </div>
        <div class="box_event"> 
            <div class="box_evnt"><p><a href="javascript:;" onClick="ajax('ajax/addtocart.php?id_coupon=<?php echo $l[1]['id_coupon']; ?>','#cart');">Ajouter au panier</a> </p>
            <p><a class="various3" href="itener-mag.php?id_mag=<?php echo $liste['id_magazin'];?>">Itineraire</a> </p></div>
         </div>
	</div>

    <?php }
}

?>

    
    <div class="tri_inner">
    	<div class="tri_inner_left"><?php echo $xml->Produits_par_page ;?> :
        <select name="" style="width:54px;height:25px;padding:0px;"  onchange="<?php echo "ajax('".$_SERVER['PHP_SELF']."?categorie=$categorie&sous_categorie=$sous_categorie&mot_cle=$mot_cle&adresse=$adresse&rayon=$rayon&cpnMax=$cpnMax&cpnMin=$cpnMin&order=$order&start=".$start."&nb_par_page='+this.value,'#result');"; ?>">
        			<option <?php if($nb_par_page == 5) echo "SELECTED"; ?>>5</option>
                    <option <?php if($nb_par_page == 10) echo "SELECTED"; ?>>10</option>
                    <option <?php if($nb_par_page == 25) echo "SELECTED"; ?>>25</option>
                    <option <?php if($nb_par_page == 50) echo "SELECTED"; ?>>50</option>
        
        </select>
        
        </div>
       
        <div class="tri_inner_right">
        		<div class="pagination">
               		<div style="padding-top:4px; width:92px"><?php echo $xml->page ; ?> 1 <?php echo $xml->sur; ?>  <?php echo $nb_des_pages; ?> </div>
                    <div style="color:#9d216e;padding-top:4px;width:65px">
                    	<?php
									if($nb_des_pages > 1)
									{
										for($i=1;$i<= $nb_des_pages;$i++){
											$p = ($i-1) * $nb_par_page;
											
											echo "<a href=\"javascript:ajax('".$_SERVER['PHP_SELF']."?categorie=$categorie&sous_categorie=$sous_categorie&mot_cle=$mot_cle&adresse=$adresse&rayon=$rayon&cpnMax=$cpnMax&cpnMin=$cpnMin&order=distance&start=".$p."&nb_par_page=".$nb_par_page."','#result');\">".$i."  </a>";					
										}	
									}
							
							echo"</div>";
							$n=$start+$nb_par_page;
							if($n<$lastpage){
							echo"<a href=\"javascript:ajax('".$_SERVER['PHP_SELF']."?categorie=$categorie&sous_categorie=$sous_categorie&mot_cle=$mot_cle&adresse=$adresse&rayon=$rayon&cpnMax=$cpnMax&cpnMin=$cpnMin&order=distance&start=".$n."&nb_par_page=".$nb_par_page."','#result');\"><div class=\"suivant\" width:\"20px\"></div></a>";
							}
                   		 ?>
                </div>
        </div>
    </div>
