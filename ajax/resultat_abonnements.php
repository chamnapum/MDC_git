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
		/*'onClosed': function() {
				   parent.location.reload(true);
				
				} */
	</script>

<style>
.nb_produits{
	font-size: 15px;
    font-weight: bold;
    position: absolute;
    right: 500px;
    top: 9px;
}
</style>
<?php   
$categorie		= isset($_GET['categorie'])?$_GET['categorie']:"";
$sous_categorie	= isset($_GET['sous_categorie'])?$_GET['sous_categorie']:"";
$sous_categorie2	= isset($_GET['sous_categorie2'])?$_GET['sous_categorie2']:"";
$mot_cle		= urldecode(isset($_GET['mot_cle']) ? $_GET['mot_cle']:"");
$magazin		= isset($_GET['magasin']) ? $_GET['magasin']:"";

/*if(isset($_GET['adresse']) and !empty($_GET['adresse']))
	$adresse = $_GET['adresse'];
else if(isset($_SESSION['kt_adresse']))
	$adresse = $_SESSION['kt_adresse'];
else
	$adresse = "";*/
	
$rayon			= isset($_GET['rayon'])?$_GET['rayon']:"100";
$prixMax		= isset($_GET['prixMax'])?$_GET['prixMax']:"";
$prixMin		= isset($_GET['prixMin'])?$_GET['prixMin']:"";
$order			= (isset($_GET['order']) and $_GET['order'] != "distance") ? $_GET['order']:"produits.id";
$asdec			= isset($_GET['asdec'])?$_GET['asdec']:"DESC";

$asdecnew = $asdec=="DESC" ? "ASC" : "DESC";

$query_liste_produit = "SELECT
  produits.id,
  magazins.latlan 
FROM
    produits
    INNER JOIN magazins 
        ON (produits.id_magazin = magazins.id_magazin)
    INNER JOIN sabonne 
        ON (magazins.id_magazin = sabonne.id_magasin)
    INNER JOIN utilisateur 
        ON (sabonne.id_user = utilisateur.id) WHERE utilisateur.id='".$_SESSION['kt_login_id']."'";
$tab = array();

						//$tab[] = " magazins.region = ".$_SESSION['region'];
if($categorie) 			$tab[] = " produits.categorie = ".$categorie;
if($sous_categorie)		$tab[] = " produits.sous_categorie = $sous_categorie ";
if($sous_categorie2)	$tab[] = " produits.sous_categorie2 = $sous_categorie2 ";
if($mot_cle and strpos($mot_cle,'mot') === FALSE)			$tab[] = " (produits.titre LIKE '%$mot_cle%' or produits.description LIKE '%$mot_cle%' )";
if($prixMax) 			$tab[] = " produits.prix2 BETWEEN $prixMin AND $prixMax ";
if($magazin) 			$tab[] = " produits.id_magazin = $magazin ";


$where = "";
if(count($tab)) $where = "WHERE ".implode(' AND ',$tab);
$query_liste_produit .= $where;
$query=mysql_query($query_liste_produit);
//echo $query_liste_produit;

$ids = array();
$MAP_OBJECT = new GoogleMapAPI(); 
$MAP_OBJECT->_minify_js = isset($_REQUEST["min"])?FALSE:TRUE;
if($adresse) 
	$adresse_client = $MAP_OBJECT->getGeoCode("$adresse, France");

while($mes_ids = mysql_fetch_array($query)){
	if(isset($adresse_client)){
		$adresse_produit = str_replace('(','',$mes_ids['latlan']);
		$adresse_produit = str_replace(')','',$adresse_produit);
		$adresse_produit_tab = explode(',',$adresse_produit);
		$distance =  $MAP_OBJECT->geoGetDistance($adresse_client['lat'],$adresse_client['lon'],$adresse_produit_tab[0],		$adresse_produit_tab[1],'K');
	}
	else{
		$distance = 0;
	}
	//echo "Distance entre adresse client et adresse produit est : $distance <br>";
	if($distance <= $rayon) {
		$ids[] = $mes_ids['id'];
		$les_distance[$mes_ids['id']] = $distance;
	}
}

if(count($ids)){
	$query_liste_produit = "SELECT produits.id, magazins.nom_magazin, magazins.id_magazin, magazins.adresse, magazins.ville, magazins.latlan, magazins.logo, produits.categorie,produits.id_magazin, produits.sous_categorie, produits.titre,produits.id, produits.reference, produits.prix, produits.prix2, produits.reduction, produits.en_stock, produits.description, produits.photo1, (SELECT COUNT(*) FROM evenements WHERE id_magazin = produits.id_magazin AND evenements.date_fin > '".date('Y-m-d')."') AS nb_events , (SELECT COUNT(*) FROM coupons WHERE id_magasin = produits.id_magazin AND coupons.date_fin > '".date('Y-m-d')."') AS nb_coupons, (SELECT COUNT(*) FROM produits WHERE id_magazin = magazins.id_magazin) AS nb_produits, (SELECT COUNT(*) FROM pub WHERE id_produit = produits.id AND emplacement = 6 AND payer = 1) AS cadrer
	FROM (produits
	LEFT JOIN magazins ON magazins.id_magazin=produits.id_magazin) WHERE produits.id IN (".implode(',',$ids).")";
	$query_liste_produit .= " ORDER BY $order $asdec";
	//echo $query_liste_produit;
	$query=mysql_query($query_liste_produit);
	$nbr_total=mysql_num_rows($query);
	
	//initialiser les variables du pagination
	
	if(isset($_GET['start'])){$start=$_GET['start'];}else{$start=0;}
	if(isset($_GET['nb_par_page'])){$nb_par_page=$_GET['nb_par_page'];}else{$nb_par_page=10;}
	
	$query_liste_produits=$query_liste_produit." LIMIT $start, $nb_par_page";
	$rkt = mysql_query($query_liste_produits);
	//echo $query_liste_produits;
	//echo $query_liste_produits;
	$nb_des_pages=ceil($nbr_total/$nb_par_page);
	$lastpage = ceil($nbr_total/$nb_par_page);
	//fin code pagination
}
else $nbr_total = 0;
?>


<div  class="tri" style="margin-top:10px;">
	 <div class="tri_inner" >
    	<div class="tri_inner_left" style="font-size:20px;"><?php echo $xml->Trier_par; ?> :
        <?php if($_GET['order']=='distance' or $_GET['order']==''){ ?> 
        <a href="#" onclick="ajax('<?php echo $_SERVER['PHP_SELF']."?categorie=$categorie&sous_categorie=$sous_categorie&mot_cle=$mot_cle&adresse=$adresse&rayon=$rayon&prixMax=$prixMax&prixMin=$prixMin&order=prix&asdec=ASC"; ?>','#result');">Prix</a>
        <?php }elseif($_GET['order']=='prix'){?>
        <a href="#" onclick="ajax('<?php echo $_SERVER['PHP_SELF']."?categorie=$categorie&sous_categorie=$sous_categorie&mot_cle=$mot_cle&adresse=$adresse&rayon=$rayon&prixMax=$prixMax&prixMin=$prixMin&order=distance&asdec=ASC"; ?>','#result');">Distance</a>
        <?php }?>
		<?php /*?><select name="distance" onchange="ajax(this.value,'#result');">
        	<option value="<?php echo $_SERVER['PHP_SELF']."?categorie=$categorie&sous_categorie=$sous_categorie&mot_cle=$mot_cle&adresse=$adresse&rayon=$rayon&prixMax=$prixMax&prixMin=$prixMin&order=&asdec=$asdecnew"; ?>">-- <?php echo $xml->selectionner ; ?> -- </option>
        	<option value="<?php echo $_SERVER['PHP_SELF']."?categorie=$categorie&sous_categorie=$sous_categorie&mot_cle=$mot_cle&adresse=$adresse&rayon=$rayon&prixMax=$prixMax&prixMin=$prixMin&order=distance&asdec=DESC"; ?>" <?php if($_GET['order'] == 'distance' and $asdec == "DESC") echo "selected"; ?>><?php echo $xml->Distance ?>-> <?php echo $xml->Le_plus_Proche ?></option>
            
            <option value="<?php echo $_SERVER['PHP_SELF']."?categorie=$categorie&sous_categorie=$sous_categorie&mot_cle=$mot_cle&adresse=$adresse&rayon=$rayon&prixMax=$prixMax&prixMin=$prixMin&order=distance&asdec=ASC"; ?>" <?php if($_GET['order'] == 'distance' and $asdec == "ASC") echo "selected"; ?>> <?php echo $xml->Distance ?>-> <?php echo $xml->Le_moins_Proche ?></option>
            
            <option value="<?php echo $_SERVER['PHP_SELF']."?categorie=$categorie&sous_categorie=$sous_categorie&mot_cle=$mot_cle&adresse=$adresse&rayon=$rayon&prixMax=$prixMax&prixMin=$prixMin&order=prix&asdec=DESC"; ?>" <?php if($_GET['order'] ==  'prix' and $asdec == "DESC") echo "selected"; ?>><?php echo $xml->prix ?> -><?php echo  $xml->le_plus_cher ?></option>
            
            <option value="<?php echo $_SERVER['PHP_SELF']."?categorie=$categorie&sous_categorie=$sous_categorie&mot_cle=$mot_cle&adresse=$adresse&rayon=$rayon&prixMax=$prixMax&prixMin=$prixMin&order=prix&asdec=ASC"; ?>" <?php if($_GET['order'] ==  'prix' and $asdec == "ASC") echo "selected"; ?>><?php  echo $xml->prix ?> -> <?php echo $xml->le_moins_cher ?></option>
            
            <option value="<?php echo $_SERVER['PHP_SELF']."?categorie=$categorie&sous_categorie=$sous_categorie&mot_cle=$mot_cle&adresse=$adresse&rayon=$rayon&prixMax=$prixMax&prixMin=$prixMin&order=titre&asdec=ASC"; ?>" <?php if($_GET['order'] ==  'titre' and $asdec == "ASC") echo "selected"; ?>><?php echo $xml->Titre ?> -> A-Z</option>
            
            <option value="<?php echo $_SERVER['PHP_SELF']."?categorie=$categorie&sous_categorie=$sous_categorie&mot_cle=$mot_cle&adresse=$adresse&rayon=$rayon&prixMax=$prixMax&prixMin=$prixMin&order=titre&asdec=DESC"; ?>" <?php if($_GET['order'] == 'titre' and $asdec == "DESC") echo "selected"; ?>><?php echo $xml->Titre ?> -> Z-A</option>
            
        </select><?php */?>
        </div>
            <div class=" counte" style="font-size:20px;">
               <?php echo $nbr_total; ?> <?php echo $xml->produits_correspondent_a_votre_recherche; ?>
            </div>
            <!--<div style="float:right">
            	<div class="grid2"  style="margin-right:5px;"></div>
            	<a class="grid1" href="javascript:;" onclick="ajax('ajax/resultat_recherche2.php?<?php echo "categorie=$categorie&sous_categorie=$sous_categorie&mot_cle=$mot_cle&adresse=$adresse&rayon=$rayon&prixMax=$prixMax&prixMin=$prixMin"; ?>','#result')"></a>
                
            </div>-->
    	</div>
	</div>
   
<?php

//echo $query_liste_produit; 
if((isset($_GET['order']) and $_GET['order'] != "distance") or !isset($_GET['order'])){
if($nbr_total) {
	while($liste = mysql_fetch_assoc($rkt)){
?>
	
<div class="box" <?php if($liste['cadrer']>0) echo 'style="border:1px solid #9D216E; box-shadow: 2px 2px 6px #9D216E; background-color: #ffe4b6"'; ?>>
	<div class="box_inner">
		<div class="boxtitre">
			<a  href="detail_produit.php?id=<?php echo $liste['id'];?>&cat_id=<?php echo $liste['categorie'];?>&mag_id=<?php echo $liste['id_magazin'];?>"><?php echo $liste['titre']; ?></a>
		</div>
        <div class="box_img"> 
            <a  href="detail_produit.php?id=<?php echo $liste['id'];?>&cat_id=<?php echo $liste['categorie'];?>&mag_id=<?php echo $liste['id_magazin'];?>&t=1">
                <?php if($liste['photo1']){ ?><img src="timthumb.php?src=assets/images/produits/<?php echo $liste['photo1']; ?>&z=1&w=125&h=90" /><?php } else {?><img src="timthumb.php?src=assets/images/logo.png&z=1&w=125&h=90" /><?php }?>
            </a>
            <span class="boxville"><?php echo getVilleById($liste['ville']); ?></span>
        </div>
		<div class="box_desc" >
			<div class="desc_inner"> <?php  echo substr($liste['description'],0,150); ?></div>
			<div class="prix_inner">
      			<div class="box_prix">
                    <span class="prix" <?php if($liste['reduction']>0) echo 'style="text-decoration:line-through"'; ?>>
                        <?php echo $liste['prix']."&euro;"; ?>
                    </span>
                    <?php if($liste['en_stock']==1){echo "<br>".$xml->En_stock;} ?>
                    <?php if($liste['reduction']>0) { ?>
                        <div class="reductionR"><?php echo $liste['reduction']; ?>%</div> 
                        <div class="prixR"><?php echo $liste['prix2']."&euro;"; ?></div>
                    <?php } ?>
     			</div>
     			<div class="box_mag">
					<a  href="detail_produit.php?id=<?php echo $liste['id'];?>&cat_id=<?php echo $liste['categorie'];?>&mag_id=<?php echo $liste['id_magazin'];?>&t=1#tabs-2">
                    <span class="magazin">
       				<?php if($liste['logo']){ ?> <img src="timthumb.php?src=assets/images/magasins/<?php echo $liste['logo']; ?>&z=1&w=80" /><?php } else {?><img src="timthumb.php?src=assets/images/logo.png&z=1&w=80" /><?php }?>
        			</span></a><br />
        			<span style="color:#000000; font-size:15px; font-weight:bold; margin-left:25px"><?php echo $liste['adresse'];?></span>
					<br />
            		<?php /*?><div class="distance">
             		<?php echo $xml->Distance; ?>: <?php echo number_format($les_distance[$liste['id']],2,',',' '); ?> KM
             		</div><?php */?>
             		<br />
				</div>
			</div> 
		</div>
	</div>
	<div class="box_event"> 
		<div class="mag_propose"><?php echo $xml->Ce_magasin_propose; ?></div>
		<div class="box_cpn"><p> <a  href="detail_produit.php?id=<?php echo $liste['id'];?>&cat_id=<?php echo $liste['categorie'];?>&mag_id=<?php echo $liste['id_magazin'];?>&t=5#tabs-5"> <?php echo $liste['nb_coupons'];?> <?php if($liste['nb_coupons']<=1){echo $xml->coupon_reduction;}else{echo $xml->coupons_reduction;} ?> </a> </p></div>
		<div class="box_evnt"><p><a  href="detail_produit.php?id=<?php echo $liste['id'];?>&cat_id=<?php echo $liste['categorie'];?>&mag_id=<?php echo $liste['id_magazin'];?>&t=4#tabs-4"><?php echo $liste['nb_events'];?> <?php if($liste['nb_events']<=1){echo $xml->evenement;}else{echo $xml->evenements;}?> </a></p></div>
		<div class="box_propose"><p><a href="rechercher.php?adresse=&rayon=999&mot_cle=&categorie=&sous_categorie=&magasin=<?php echo $liste['id_magazin'];  ?>"><?php echo $liste['nb_produits'];?> <?php if($liste['nb_produits']<=1){echo $xml->produit;}else{echo $xml->produits;}?></a></p></div>
		<span class="boxville" style="margin-left:5px; width:175px; background-color:#F6AE30;"><a style="color:#FFFFFF" href="javascript:;" onClick="ajax('ajax/addtoCoursesList.php?id_produit=<?php echo $liste['id']; ?>','#courses');"><?php echo $xml->Ajouter_au_panier ?></a></span>
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
	
	foreach($liste_produits_trie as $l) {
	$nb_produits++;
	 ?>
	
	<div class="box">
        <div class="box_inner">
            	<div class="boxtitre">
                 <a    href="detail_produit.php?id=<?php echo $l[1]['id'];?>&cat_id=<?php echo $l[1]['categorie'];?>&mag_id=<?php echo $l[1]['id_magazin'];?>#tabs-1"><?php echo $l[1]['titre']; ?></a>
                </div>
                <div class="box_img"> 
                <a  href="detail_produit.php?id=<?php echo $l[1]['id'];?>&cat_id=<?php echo $l[1]['categorie'];?>&mag_id=<?php echo $l[1]['id_magazin'];?>&t=1#tabs-1">
                
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
                                
                                 <a  href="detail_produit.php?id=<?php echo $l[1]['id'];?>&cat_id=<?php echo $l[1]['categorie'];?>&mag_id=<?php echo $l[1]['id_magazin'];?>&t=1#tabs-2"><span class="magazin">
                                <?php echo $l[1]['nom_magazin'];?></span></a>
                                  
                             </br>
                              <?php if(isset($distance)) { ?>
                                    <div class="distance">
                                     <?php echo $xml-> Distance ; ?>: <?php echo number_format($distance,2,',',' '); ?> KM
                                     </div>
       			 				<?php } ?>
                             </div>
                    </div> 
                </div>
               
        </div>
        <div class="box_event"> 
        <div class="mag_propose"><?php echo $xml->Ce_magasin_propose; ?></div>
            <div class="box_cpn"><p> <a  href="detail_produit.php?id=<?php echo $liste['id'];?>&cat_id=<?php echo $liste['categorie'];?>&mag_id=<?php echo $liste['id_magazin'];?>&t=5#tabs-5"> <?php echo $liste['nb_coupons'];?> <?php if($liste['nb_coupons']<=1){echo $xml->coupon_reduction;}else{echo $xml->coupons_reduction ;}?> </a> </p></div>
            <div class="box_evnt"><p><a  href="detail_produit.php?id=<?php echo $liste['id'];?>&cat_id=<?php echo $liste['categorie'];?>&mag_id=<?php echo $liste['id_magazin'];?>&t=4#tabs-4"><?php echo $liste['nb_events'];?> <?php if($liste['nb_events']<=1){echo $xml->evenement;}else{echo $xml->evenements;}?> </a></p></div>
            
             <span class="boxville" style="margin-left:5px; width:175px; background-color:#F6AE30;"><a style="color:#FFFFFF" href="javascript:;" onClick="ajax('ajax/addtoCoursesList.php?id_produit=<?php echo $liste['id']; ?>','#courses');"><?php echo $xml-> Ajouter_au_panier ;?></a></span>
         </div>
</div>
    <?php }
	//echo '<div class="nb_produits">'.$nb_produits.'</div>';
}

?>
   <div class="tri_inner">
    	<div class="tri_inner_left"><?php echo $xml->Produits_par_page ;?> :
         <select name="mag_per_page" style="width:54px;height:25px;padding:0px;" onchange="<?php echo "ajax('".$_SERVER['PHP_SELF']."?categorie=$categorie&sous_categorie=$sous_categorie&mot_cle=$mot_cle&adresse=$adresse&rayon=$rayon&prixMax=$prixMax&prixMin=$prixMin&order=distance&asdec=$asdecnew&start=0&nb_par_page='+this.value,'#result');"; ?>">
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