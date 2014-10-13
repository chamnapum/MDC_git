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
$magasin		= isset($_GET['magasin']) ? $_GET['magasin']:"";
if(isset($_GET['adresse']) and !empty($_GET['adresse']))
	$adresse = $_GET['adresse'];
else if(isset($_SESSION['kt_adresse']))
	$adresse = $_SESSION['kt_adresse'];
else
	$adresse = "";
$adresse = addslashes($adresse);
$rayon			= isset($_GET['rayon'])?$_GET['rayon']:"";
$prixMax		= isset($_GET['prixMax'])?$_GET['prixMax']:"";
$prixMin		= isset($_GET['prixMin'])?$_GET['prixMin']:"";
$order			= (isset($_GET['order']) and $_GET['order'] == "prix") ? "produits.prix":"produits.id";
$asdec			= isset($_GET['asdec'])?$_GET['asdec']:"DESC";

$asdecnew = $asdec=="DESC" ? "ASC" : "DESC";

$query_liste_produit = "SELECT coupons.titre AS titre_coupon, magazins.nom_magazin, magazins.id_magazin, magazins.adresse, magazins.ville, magazins.latlan, produits.categorie,produits.id_magazin, produits.sous_categorie, produits.titre,produits.id, produits.reference, produits.prix, produits.en_stock, produits.description, produits.photo1, coupons.reduction, coupons.date_fin, coupons.date_debut, coupons.categories, (SELECT COUNT(*) FROM evenements WHERE id_magazin = produits.id_magazin) AS nb_events , (SELECT COUNT(*) FROM coupons WHERE id_magasin = produits.id_magazin) AS nb_coupons, (SELECT COUNT(*) FROM produits WHERE id_magazin = magazins.id_magazin) AS nb_produits 
FROM ((produits
LEFT JOIN magazins ON magazins.id_magazin=produits.id_magazin)
LEFT JOIN coupons ON coupons.id_coupon=produits.coupon_reduction) ";
$tab = array();

						$tab[] = " magazins.region = ".$_SESSION['region'];
if($categorie) 			$tab[] = " produits.categorie = ".$categorie;
if($sous_categorie)		$tab[] = " produits.sous_categorie = $sous_categorie ";
if($mot_cle)			$tab[] = " (produits.titre LIKE '%$mot_cle%' or produits.description LIKE '%$mot_cle%' )";
if($prixMax) 			$tab[] = " produits.prix BETWEEN $prixMin AND $prixMax ";
if($magasin) 			$tab[] = " produits.id_magazin = $magasin ";


$where = "";
if(count($tab)) $where = "WHERE ".implode(' AND ',$tab);
$query_liste_produit .= $where;
$query_liste_produit .= " ORDER BY $order $asdec";
echo $query_liste_produit;
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
    	<div class="tri_inner_left">Trier par:
        <select name="distance" onchange="ajax(this.value,'#result');">
        	<option value="<?php echo $_SERVER['PHP_SELF']."?categorie=$categorie&sous_categorie=$sous_categorie&mot_cle=$mot_cle&adresse=$adresse&rayon=$rayon&prixMax=$prixMax&prixMin=$prixMin&order=&asdec=$asdecnew"; ?>">-- S&eacute;l&eacute;ctionner -- </option>
        	<option value="<?php echo $_SERVER['PHP_SELF']."?categorie=$categorie&sous_categorie=$sous_categorie&mot_cle=$mot_cle&adresse=$adresse&rayon=$rayon&prixMax=$prixMax&prixMin=$prixMin&order=distance&asdec=$asdecnew"; ?>" <?php if($_GET['order'] == 'distance') echo "selected"; ?>>Distance</option>
            <option value="<?php echo $_SERVER['PHP_SELF']."?categorie=$categorie&sous_categorie=$sous_categorie&mot_cle=$mot_cle&adresse=$adresse&rayon=$rayon&prixMax=$prixMax&prixMin=$prixMin&order=prix&asdec=$asdecnew"; ?>" <?php if($_GET['order'] == 'prix') echo "selected"; ?>>Prix</option>
        </select></div>
            <div class=" counte">
               <?php echo $nbr_total ;?> produits correspondent a votre recherche
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
if((isset($_GET['order']) and $_GET['order'] != "distance") or !isset($_GET['order'])){
//die("gg");



while($liste = mysql_fetch_assoc($rkt)){
	if(isset($adresse_client)){
		$adresse_produit = str_replace('(','',$liste['latlan']);
		$adresse_produit = str_replace(')','',$adresse_produit);
		$adresse_produit_tab = explode(',',$adresse_produit);
		$distance =  $MAP_OBJECT->geoGetDistance($adresse_client['lat'],$adresse_client['lon'],$adresse_produit_tab[0],		$adresse_produit_tab[1],'K');
	}
	else{
		$distance = 0;
	}
	//echo "Distance entre adresse client et adresse produit est : $distance <br>";
	if($distance <= $rayon) {
?>
	
	<div class="box">
        <div class="box_inner">
            	<div class="boxtitre">
                 <a class="various3"   href="detail_produit.php?id=<?php echo $liste['id'];?>&cat_id=<?php echo $liste['categorie'];?>&mag_id=<?php echo $liste['id_magazin'];?>#tabs-1"><?php echo $liste['titre']; ?></a>
                 <span class="produi_dispo">  - <a href="rechercher.php?adresse=&rayon=999&mot_cle=&categorie=&sous_categorie=&magasin=<?php echo $liste['id_magazin'];  ?>" style="color: #9F2570;"><?php echo $liste['nb_produits']; ?> produits disponibles</a></span>
                </div>
                <div class="box_img"> 
                <a class="various3" href="detail_produit.php?id=<?php echo $liste['id'];?>&cat_id=<?php echo $liste['categorie'];?>&mag_id=<?php echo $liste['id_magazin'];?>&t=1#tabs-1">
                
                            <img src="timthumb.php?src=assets/images/produits/<?php echo $liste['photo1']; ?>&z=1&w=125&h=90" />
               </a>
               <span class="boxville"><?php echo getVilleById($liste['ville']); ?></span>
                </div>
                <div class="box_desc" >
                     <div class="desc_inner"> <?php  echo substr($liste['description'],0,150); ?></div>
                      <div class="prix_inner">
                              <div class="box_prix">
                              	<span class="prix">
								<?php echo $liste['prix']."&#8364;"; ?>
                                </span>
                             	 <?php if($liste['en_stock']==1){echo "<br>En stock";} ?>
                             </div>
                             <div class="box_mag">
                                
                                 <a class="various3" href="detail_produit.php?id=<?php echo $liste['id'];?>&cat_id=<?php echo $liste['categorie'];?>&mag_id=<?php echo $liste['id_magazin'];?>&t=1#tabs-2"><span class="magazin">
                                <?php echo $liste['nom_magazin'];?></span></a><br />
                                <span style="color:#000000; font-size:15px; font-weight:bold; margin-left:25px"><?php echo $liste['adresse'];?></span>
                                  
                             </br>
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
            <div class="box_cpn"><p> <a class="various3" href="detail_produit.php?id=<?php echo $liste['id'];?>&cat_id=<?php echo $liste['categorie'];?>&mag_id=<?php echo $liste['id_magazin'];?>&t=5#tabs-5"> <span style="font-size:18px"><?php echo $liste['nb_coupons'];?></span> <?php if($liste['nb_coupons']<=1){echo"coupon <br /> de reduction";}else{echo"coupons <br /> de reduction";}?> </a> </p></div>
            <div class="box_evnt"><p><a class="various3" href="detail_produit.php?id=<?php echo $liste['id'];?>&cat_id=<?php echo $liste['categorie'];?>&mag_id=<?php echo $liste['id_magazin'];?>&t=4#tabs-4"><span style="font-size:18px"><?php echo $liste['nb_events'];?></span><?php if($liste['nb_events']<=1){echo" Evenement";}else{echo" Evenements";}?> </a></p></div>
            
             <span class="boxville" style="margin-left:5px; width:175px; background-color:#F6AE30;"><a style="color:#FFFFFF" href="javascript:;" onClick="ajax('ajax/addtoCoursesList.php?id_produit=<?php echo $liste['id']; ?>','#courses');">ajouter &agrave; ma liste des courses</a></span>
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
			$distance = 0;
			$liste_produits_trie[] = array(0,$liste);
		}
	}
	
	$asdec	= isset($_GET['asdec'])?$_GET['asdec']:"DESC";
	if($asdec == "ASC")
		uasort($liste_produits_trie, 'cmp');
	else
		uasort($liste_produits_trie, 'cmp2');
	
	foreach($liste_produits_trie as $l) { ?>
	
	<div class="box">
        <div class="box_inner">
            	<div class="boxtitre">
                 <a class="various3"   href="detail_produit.php?id=<?php echo $l[1]['id'];?>&cat_id=<?php echo $l[1]['categorie'];?>&mag_id=<?php echo $l[1]['id_magazin'];?>#tabs-1"><?php echo $l[1]['titre']; ?></a>
                </div>
                <div class="box_img"> 
                <a class="various3" href="detail_produit.php?id=<?php echo $l[1]['id'];?>&cat_id=<?php echo $l[1]['categorie'];?>&mag_id=<?php echo $l[1]['id_magazin'];?>&t=1#tabs-1">
                
                            <img src="timthumb.php?src=assets/images/produits/<?php echo $l[1]['photo1']; ?>&z=1&w=125&h=90" />
               </a>
               <span class="boxville"><?php echo getVilleById($l[1]['ville']); ?></span>
                </div>
                <div class="box_desc" >
                     <div class="desc_inner"> <?php  echo substr($l[1]['description'],0,150); ?></div>
                      <div class="prix_inner">
                              <div class="box_prix">
                              	<span class="prix">
								<?php echo $l[1]['prix']."&#8364;"; ?>
                                </span>
                             	 <?php if($l[1]['en_stock']==1){echo "<br>En stock";} ?>
                             </div>
                             <div class="box_mag">
                                
                                 <a class="various3" href="detail_produit.php?id=<?php echo $l[1]['id'];?>&cat_id=<?php echo $l[1]['categorie'];?>&mag_id=<?php echo $l[1]['id_magazin'];?>&t=1#tabs-2"><span class="magazin">
                                <?php echo $l[1]['nom_magazin'];?></span></a>
                                  
                             </br>
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
            <div class="box_cpn"><p> <a class="various3" href="detail_produit.php?id=<?php echo $l[1]['id'];?>&cat_id=<?php echo $l[1]['categorie'];?>&mag_id=<?php echo $l[1]['id_magazin'];?>&t=5#tabs-5"> <span style="font-size:18px"><?php echo $l[1]['nb_coupons'];?></span> <?php if($l[1]['nb_coupons']<=1){echo"coupon <br /> de reduction";}else{echo"coupons <br /> de reduction";}?> </a> </p></div>
            <div class="box_evnt"><p><a class="various3" href="detail_produit.php?id=<?php echo $l[1]['id'];?>&cat_id=<?php echo $l[1]['categorie'];?>&mag_id=<?php echo $l[1]['id_magazin'];?>&t=4#tabs-4"><span style="font-size:18px"><?php echo $l[1]['nb_events'];?></span><?php if($l[1]['nb_events']<=1){echo" Evenement";}else{echo" Evenements";}?> </a></p></div>
         </div>
</div>
    <?php }
}

?>
   <div class="tri_inner">
    	<div class="tri_inner_left">Produits par page :
         <select name="mag_per_page" style="width:54px;height:25px;padding:0px;" onchange="<?php echo "ajax('".$_SERVER['PHP_SELF']."?categorie=$categorie&sous_categorie=$sous_categorie&mot_cle=$mot_cle&adresse=$adresse&rayon=$rayon&prixMax=$prixMax&prixMin=$prixMin&order=distance&asdec=$asdecnew&start=0&nb_par_page='+this.value,'#result');"; ?>">
                    <option <?php if($nb_par_page == 5) echo "SELECTED"; ?>>5</option>
                    <option <?php if($nb_par_page == 10) echo "SELECTED"; ?>>10</option>
                    <option <?php if($nb_par_page == 25) echo "SELECTED"; ?>>25</option>
                    <option <?php if($nb_par_page == 50) echo "SELECTED"; ?>>50</option>
                </select>
        </div>
       
        <div class="tri_inner_right">
        		<div class="pagination">
               		<div style="padding-top:4px; width:92px">Page 1 sur  <?php echo $nb_des_pages; ?> </div>
                    <div style="color:#9d216e;padding-top:4px;width:65px">
                    	<?php
							if($nb_des_pages > 1)
								{			
									for($i=1;$i<= $nb_des_pages;$i++){
										$p = ($i-1) * $nb_par_page;
										
										echo "<a href=\"javascript:ajax('".$_SERVER['PHP_SELF']."?categorie=$categorie&sous_categorie=$sous_categorie&mot_cle=$mot_cle&adresse=$adresse&rayon=$rayon&prixMax=$prixMax&prixMin=$prixMin&order=distance&asdec=$asdecnew&start=".$p."&nb_par_page=".$nb_par_page."','#result');\">".$i."  </a>";					
										
									}
								}
							echo"</div>";
							$n=$start+$nb_par_page;
							if($n<$lastpage){
							echo"<a href=\"javascript:ajax('".$_SERVER['PHP_SELF']."?categorie=$categorie&sous_categorie=$sous_categorie&mot_cle=$mot_cle&adresse=$adresse&rayon=$rayon&prixMax=$prixMax&prixMin=$prixMin&order=distance&asdec=$asdecnew&start=".$n."&nb_par_page=".$nb_par_page."','#result');\"><div class=\"suivant\" width:\"20px\"></div></a>";
							}
                   		 ?>
                    
                    
                	
                     
                     
                     
                </div>
        </div>
    </div>