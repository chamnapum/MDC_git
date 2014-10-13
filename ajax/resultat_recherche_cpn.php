<?php 



require_once('../Connections/magazinducoin.php');



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

	$(document).ready(function()

	{

		 $("html,body").animate({scrollTop: 0}, 1000);

	});

	</script>

<style>

.page-content{

	width:1000px; 

	float:left;

}

.page-content .page-item{

	width:690px; 

	float:left;

}

.page-item .content-itme{

	width:670px; 

	height:220px; 

	float:left; 

	background:#cbcbcb; 

	margin: 8px 0px 0px 8px;

	-webkit-border-radius: 5px 5px 5px 5px;

	border-radius: 5px 5px 5px 5px;

	-webkit-box-shadow: 0 2px 1px 0 #8e8d8d;

	box-shadow: 0 2px 1px 0 #8e8d8d;

}

.content-itme .itme-left{

	width:450px;

	height:220px;

	float:left;

}

.itme-left .ville{

	width:450px;

	height:35px;

	float:left;

	margin:10px 0px;

}

.ville .ville_title1{

	padding:9px 15px; 

	float:left; 

	font-size:14.5px; 

	background:#353535; 

	color:#FFF;

	text-transform:uppercase;

}

.ville .ville_title2{

	padding:6px 15px; 

	float:left; 

	font-size:18px;

	text-transform:uppercase;

}





.itme-left .item_descript{

	width:395px; 

	height:75px; 

	float:left; 

	background:#FFF;

	-webkit-border-radius: 5px 5px 5px 5px;

	border-radius: 5px 5px 5px 5px;

	margin:0px 12px;

	padding:15px;

	color:#353535;

}



.itme-left .item_link{

	width:425px; 

	/*height:40px;*/ 

	float:left; 

	margin:10px 12px;

	font-size:11px;

	color:#353535;

	-webkit-box-shadow: 0 2px 1px 0 #8e8d8d;

	box-shadow: 0 2px 1px 0 #8e8d8d;

	-webkit-border-radius: 5px 5px 5px 5px;

	border-radius: 5px 5px 5px 5px;

}

.item_link a{

	width:141.5px;

	float:left;

	text-align:center;

	padding:12px 0px;

	color:#FFF;

	font-weight:bold;

}

.item_link a:first-child{

	-webkit-border-radius: 5px 0px 0px 5px;

	border-radius: 5px 0px 0px 5px;

}

.item_link a:last-child{

	-webkit-border-radius: 0px 5px 5px 0px;

	border-radius: 0px 5px 5px 0px;

}





.content-itme .itme-right{

	width:220px;

	height:220px;

	float:left;

	background:#666;

	-webkit-border-radius: 0px 5px 5px 0px;

	border-radius: 0px 5px 5px 0px;

}

.itme-right a{

	padding:11px 20px; 

	padding:12px 20px 9px 20px\9; 

	margin:170px 0px 0px 30px; 

	margin:170px 0px 0px 26px\9;

	background-color:#9d216e; 

	float:left;

	-webkit-border-radius: 5px 5px 5px 5px;

	border-radius: 5px 5px 5px 5px;

	font-size:13px;

	color:#FFF;

	-webkit-box-shadow: 0 2px 1px 0 #272727;

	box-shadow: 0 2px 1px 0 #272727;

}



.page-content .sidebar-right{

	width:310px; 

	height:350px;

	float:left; 

}

#magazin_link{

	padding:10px 15px; 

	margin:8px;

	float:left;

	background:#535353;

	color:#FFF;

	-webkit-border-radius: 5px 5px 5px 5px;

	border-radius: 5px 5px 5px 5px;

}



.pageing{

	width:685px;

	padding:10px 0px;

	text-align:center;

	float: left;

}

.paginate {

	border: 0 none;

    display: inline;

    font-size: 11px;

    margin: 0;

    padding: 0;

	font-size:14px;

}



.paginate a {

	color:#9D216E;

    cursor: pointer;

    margin: 2px !important;

    padding: 1px 6px;

    text-decoration: none;

}

.paginate a:hover, .paginate a:active {

	color:#F6AE30;

}

.paginate span.current, .paginate span.disabled {

	color:#F6AE30;

    cursor: pointer;

    margin: 2px !important;

    padding: 1px 6px;

    text-decoration: none;

	}

</style>

<?php   

$categorie = isset($_REQUEST['categorie'])?$_REQUEST['categorie']:"";



$sous_categorie	= isset($_REQUEST['sous_categorie'])?$_REQUEST['sous_categorie']:"";

$mot_cle		= isset($_REQUEST['mot_cle']) ? $_REQUEST['mot_cle']:"";



$region			= isset($_REQUEST['region'])?$_REQUEST['region']:$default_region;	

//$departement	= isset($_REQUEST['departement'])?$_REQUEST['departement']:"";

if($_REQUEST['departement']=='region') $departement = '';

else if($_REQUEST['departement']=='near') $departement = '';

else $departement = $_REQUEST['departement'];



$ville			= isset($_REQUEST['id_ville'])?$_REQUEST['id_ville']:"";

if($ville){

	if($_REQUEST['ville_near_all']=='1'){

		$query = "SELECT nom_ville_near, id_ville FROM ville_near WHERE id_ville IN ($ville) GROUP BY nom_ville_near ORDER BY nom_ville_near ASC ";

		$result = mysql_query($query);

		$total	= mysql_num_rows($result);

		$ville_near_val = '';

		$symbol=',';

		$i=0;

		while($liste = mysql_fetch_assoc($result)){

			$i++;

			if($total==$i){$symbol='';}

			$ville_near_val .= "'".$liste['nom_ville_near']."'".$symbol;

		}

		$ville_near_all = $_REQUEST['ville_near_all'];

		$ville_near_val = ','.$ville_near_val;

	}

}



$order = 'top DESC, id_coupon DESC';

//$asdec = "DESC";



$magasin 		= isset($_REQUEST['magasin'])?$_REQUEST['magasin']:"";

$coupon 		= isset($_REQUEST['coupon'])?$_REQUEST['coupon']:"";

$categorie		= isset($_REQUEST['categorie'])?$_REQUEST['categorie']:"";

$sous_categorie	= isset($_REQUEST['sous_categorie'])?$_REQUEST['sous_categorie']:"";



$now = date('Y-m-d H:i:s');

$datetime = date('Y-m-d H:i:s');

$date = date('Y-m-d');



if($_REQUEST['departement']=='near'){

	$inner_join = "INNER JOIN region_near 

					  ON magazins.region = region_near.region_id_2 

					INNER JOIN region 

					  ON region_near.region_id_1 = region.id_region";

}else{

	$inner_join = "INNER JOIN region 

			ON region.id_region = magazins.region";

}

	

$tabs = array();

						//$tabs[] = " magazins.region = ".$region;

if($_REQUEST['departement']=='near')

	$tabs[] = " region.id_region = ".$region;

else

	$tabs[] = " magazins.region = ".$region;

							

if($departement) 		$tabs[] = " magazins.department = ".$departement;



if($_REQUEST['ville_near_all']=='1'){

	if($ville) 				$tabs[] = " magazins.ville IN ($ville $ville_near_val)";

}else{

	if($ville) 				$tabs[] = " magazins.ville IN ($ville)" ;

}



if($sous_categorie)		$tabs[] = " coupons.sous_categorie = ".$sous_categorie;

if($categorie)			$tabs[] = " coupons.categories  = ".$categorie;

if($mot_cle and strpos($mot_cle,'mot') === FALSE) $tabs[] = " coupons.titre LIKE '%$mot_cle%' ";

if($magasin)			$tabs[] = " magazins.id_magazin = $magasin ";

if($coupon) 			$tabs[] = " coupons.id_coupon = ".$coupon;

	//$tabs[] = " magazins.latlan not in ('(999,999)','(0,0)','(,)','')";

	

	

	$query_liste_produit_test = "SELECT 

	  magazins.nom_magazin,

	  magazins.logo,

	  magazins.adresse,

	  magazins.latlan,

	  magazins.photo1,

	  coupons.id_coupon,

	  coupons.date_debut,

	  coupons.date_fin,

	  coupons.titre,

	  coupons.description,

	  coupons.photo1 as photo,

	  magazins.id_magazin,

	  maps_ville.cp,

	  maps_ville.nom,

  	  magazins.code_postal,

	  magazins.region,

	  category.cat_name,

	  (SELECT 

		COUNT(*) 

	  FROM

		coupons AS ccss 

	  WHERE (

		  ccss.id_coupon = coupons.id_coupon 

		  AND coupons.en_tete_liste = 1 

		  AND coupons.en_tete_liste_payer = 1 

		  AND coupons.en_tete_liste_fin >= '".$date."' 

		  AND coupons.date_fin >= '".$date."' 

		  AND coupons.date_debut <= '".$date."' 

		  AND coupons.payer = 1 

		  AND coupons.active = 1 

		  AND coupons.approuve = '1'

		) 

		OR (

		  ccss.id_coupon = coupons.id_coupon 

		  AND coupons.en_tete_liste_payer = 1 

		  AND coupons.en_tete_liste = 1 

		  AND coupons.payer = 1 

		  AND coupons.active = 1 

		  AND coupons.approuve = 0 

		  AND coupons.public = 0 

		  AND DATE_ADD(

			coupons.date_debut,

			INTERVAL - coupons.day_en_tete_liste DAY

		  ) = '".$date."' 

		  AND date_debut >= '".$date."'

		)) AS top,

    DATE_ADD(

      coupons.date_debut,

      INTERVAL - coupons.day_en_tete_liste DAY

    ) AS dates,

    coupons.date_debut

	FROM

	  coupons 

	  LEFT JOIN magazins 

		ON magazins.id_magazin = coupons.id_magasin 

	  LEFT JOIN category 

		ON category.cat_id = coupons.categories 

	  $inner_join

	  INNER JOIN departement 

		ON departement.code = magazins.department 

	  INNER JOIN maps_ville 

		ON maps_ville.id_ville = magazins.ville  

	WHERE ( ".implode(' AND ',$tabs)." 

	AND coupons.en_tete_liste_payer = 1 

    AND coupons.en_tete_liste = 1 

    AND coupons.payer = 1 

    AND coupons.active = 1 

	AND coupons.approuve = 0 

    AND coupons.public= 0  

    AND DATE_ADD(

      coupons.date_debut,

      INTERVAL - coupons.day_en_tete_liste DAY

    ) = '".$date."' 

    AND date_debut >= '".$date."' 

  ) OR

  ( ".implode(' AND ',$tabs)." 

	AND coupons.date_fin >= '".$date."'  

    AND coupons.date_debut <= '".$date."'  

    AND coupons.payer = 1 

    AND coupons.active = 1 

    AND coupons.approuve = '1'

  )

	OR

  ( ".implode(' AND ',$tabs)." 

	AND coupons.date_fin >= '".$date."'  

    AND coupons.date_debut <= '".$date."'  

    AND coupons.payer = 1 

    AND coupons.active = 1 

	AND coupons.approuve = 0 

	AND coupons.public=1 

	AND coupons.public_start <= '".$datetime."' 

	AND (coupons.public_start + INTERVAL 20 MINUTE) < '".$datetime."'

  )";

	

	$query_liste_produit_test .= " ORDER BY $order $asdec";

	//echo $query_liste_produit_test;

	$query_test = mysql_query($query_liste_produit_test);

	$nbr_total	= mysql_num_rows($query_test);

	

	//initialiser les variables du pagination

	$part = "region=$region&categorie=$categorie&sous_categorie=$sous_categorie&mot_cle=$mot_cle&departement=$departement&id_ville=$ville&ville_near_all=$ville_near_all&order=$orderBy&asdec=$asdecnew";

	$limit = 10;

	$total_pages = $nbr_total;

	$stages = 3;

	$page ='';

	if(isset($_GET['pa'])){

		$page =$_GET['pa'];

	}else{

		$page =1;

	}

	if($page){

		$start = ($page - 1) * $limit; 

	}else{

		$start = 0;	

	}



		$query_liste_produits = $query_liste_produit_test." LIMIT $start, $limit";

		//echo $query_liste_produits;

	

	if ($page == 0){$page = 1;}

	$prev = $page - 1;	

	$next = $page + 1;							

	$lastpage = ceil($total_pages/$limit);		

	$LastPagem1 = $lastpage - 1;					

	

	

	$paginate = '';

	if($lastpage > 1)

	{	

		$paginate .= "<div class='paginate'>";

		// Previous

		if ($page > 1){

			//$paginate.= "<a href='$targetpage?pa=$prev'>Précédent</a>";

			$paginate.= "<a href=\"javascript:ajax('".$_SERVER['PHP_SELF']."?$part&pa=$prev','#result');\">&#60;&#60; PRÉCÉDENT</a>";	

		}else{

			$paginate.= "<span class='disabled'>&#60;&#60; PRÉCÉDENT</span>";	

		}

			



		

		// Pages	

		if ($lastpage < 7 + ($stages * 2))	// Not enough pages to breaking it up

		{	

			for ($counter = 1; $counter <= $lastpage; $counter++)

			{

				if ($counter == $page){

					$paginate.= "<span class='current'>$counter</span>";

				}else{

					//$paginate.= "<a href='$targetpage?pa=$counter'>$counter</a>";}

					$paginate.= "<a href=\"javascript:ajax('".$_SERVER['PHP_SELF']."?$part&pa=$counter','#result');\">$counter</a>";

				}

			}

		}

		elseif($lastpage > 5 + ($stages * 2))	// Enough pages to hide a few?

		{

			// Beginning only hide later pages

			if($page < 1 + ($stages * 2))		

			{

				for ($counter = 1; $counter < 4 + ($stages * 2); $counter++)

				{

					if ($counter == $page){

						$paginate.= "<span class='current'>$counter</span>";

					}else{

						//$paginate.= "<a href='$targetpage?pa=$counter'>$counter</a>";

						$paginate.= "<a href=\"javascript:ajax('".$_SERVER['PHP_SELF']."?$part&pa=$counter','#result');\">$counter</a>";

					}					

				}

				$paginate.= "...";

				//$paginate.= "<a href='$targetpage?pa=$LastPagem1'>$LastPagem1</a>";

				$paginate.= "<a href=\"javascript:ajax('".$_SERVER['PHP_SELF']."?$part&pa=$LastPagem1','#result');\">$LastPagem1</a>";

				//$paginate.= "<a href='$targetpage?pa=$lastpage'>$lastpage</a>";

				$paginate.= "<a href=\"javascript:ajax('".$_SERVER['PHP_SELF']."?$part&pa=$lastpage','#result');\">$lastpage</a>";	

			}

			// Middle hide some front and some back

			elseif($lastpage - ($stages * 2) > $page && $page > ($stages * 2))

			{

				//$paginate.= "<a href='$targetpage?pa=1'>1</a>";

				$paginate.= "<a href=\"javascript:ajax('".$_SERVER['PHP_SELF']."?$part&pa=1','#result');\">1</a>";	

				//$paginate.= "<a href='$targetpage?pa=2'>2</a>";

				$paginate.= "<a href=\"javascript:ajax('".$_SERVER['PHP_SELF']."?$part&pa=2','#result');\">2</a>";

				$paginate.= "...";

				for ($counter = $page - $stages; $counter <= $page + $stages; $counter++)

				{

					if ($counter == $page){

						$paginate.= "<span class='current'>$counter</span>";

					}else{

						//$paginate.= "<a href='$targetpage?pa=$counter'>$counter</a>";

						$paginate.= "<a href=\"javascript:ajax('".$_SERVER['PHP_SELF']."?$part&pa=$counter','#result');\">$counter</a>";

					}					

				}

				$paginate.= "...";

				//$paginate.= "<a href='$targetpage?pa=$LastPagem1'>$LastPagem1</a>";

				$paginate.= "<a href=\"javascript:ajax('".$_SERVER['PHP_SELF']."?$part&pa=$LastPagem1','#result');\">$LastPagem1</a>";

				//$paginate.= "<a href='$targetpage?pa=$lastpage'>$lastpage</a>";	

				$paginate.= "<a href=\"javascript:ajax('".$_SERVER['PHP_SELF']."?$part&pa=$lastpage','#result');\">$lastpage</a>";	

			}

			// End only hide early pages

			else

			{

				//$paginate.= "<a href='$targetpage?pa=1'>1</a>";

				$paginate.= "<a href=\"javascript:ajax('".$_SERVER['PHP_SELF']."?$part&pa=1','#result');\">1</a>";	

				//$paginate.= "<a href='$targetpage?pa=2'>2</a>";

				$paginate.= "<a href=\"javascript:ajax('".$_SERVER['PHP_SELF']."?$part&pa=2','#result');\">2</a>";

				$paginate.= "...";

				for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++)

				{

					if ($counter == $page){

						$paginate.= "<span class='current'>$counter</span>";

					}else{

						//$paginate.= "<a href='$targetpage?pa=$counter'>$counter</a>";

						$paginate.= "<a href=\"javascript:ajax('".$_SERVER['PHP_SELF']."?$part&pa=$counter','#result');\">$counter</a>";

					}					

				}

			}

		}

					

				// Next

		if ($page < $counter - 1){ 

			//$paginate.= "<a href='$targetpage?pa=$next'>Suivant</a>";

			$paginate.= "<a href=\"javascript:ajax('".$_SERVER['PHP_SELF']."?$part&pa=$next','#result');\">SUIVANT  &#62;&#62;</a>";	

		}else{

			$paginate.= "<span class='disabled'>SUIVANT &#62;&#62;</span>";

		}

			

		$paginate.= "</div>";	

	}

	

	$rkt= mysql_query($query_liste_produits);

	//echo $query_liste_produits;

	//$nb_des_pages=ceil($nbr_total/$nb_par_page);

	//$lastpage = ceil($nbr_total/$nb_par_page);

//fin code pagination

?>



<?php

$finds = array(" ","(",")","é","è","ê","ë","ï","ö","à","Ë","Ê","É","Ä","Â","Ö","Ô","Ï","ô","'",".","/");

$replaces = array("-","","","e","e","e","e","i","o","a","E","E","E","A","A","O","O","I","o","","","");

?>



<?php /*?><div class="tri" style="margin-bottom:10px; float:left;">

	 <div class="head_tri_inner" >

    	<div class="head_tri_inner_left"><?php echo $xml->Trier_par; ?> :

            <a href="#" style="background:#cbcbcb;" onclick="ajax('<?php echo $_SERVER['PHP_SELF']."?region=$region&categorie=$categorie&sous_categorie=$sous_categorie&mot_cle=$mot_cle&departement=$departement&id_ville=$ville&order=date_debut"; ?>','#result');">Date</a>

            <a href="#" style="background:#cbaaaa;" onclick="ajax('<?php echo $_SERVER['PHP_SELF']."?region=$region&categorie=$categorie&sous_categorie=$sous_categorie&mot_cle=$mot_cle&departement=$departement&id_ville=$ville&order=distance"; ?>','#result');">Distance</a>

        </div>

        <div class="head_tri_inner_right">

           <?php  echo $nbr_total; ?> produits correspondent à votre recherche

        </div>

    </div>

</div><?php */?>

  



<div class="page-item"> 

<?php

while($liste = mysql_fetch_assoc($rkt)){

?>

<?php $nom=str_replace($finds,$replaces,$liste['nom_magazin']);?>

<?php $nom_region=str_replace($finds,$replaces,getRegionById($default_region));?>





<!--  START PAGE ITME -->



    <div class="content-itme">

        <!--  START ITME LEFT -->

        <div class="itme-left">

            <div class="ville">

                <div class="ville_title1">

                    <?php echo substr($liste['nom'],0,13);?>

                </div>

                <div class="ville_title2">

                    <a href="md-<?php echo $default_region;?>-<?php echo $nom_region;?>-<?php echo $liste['id_magazin']; ?>-<?php echo $nom;?>.html"><?php echo substr($liste['nom_magazin'],0,23);?></a>

                </div>

            </div>

            

            <div class="item_descript">

                <div style="width:200px; height:35px; float:left; display: table; font-size:13px;">

                    <span style="vertical-align:middle; display: table-cell; text-align:left; font-size:13px;">

                    <?php /*?><?php if($les_distance[$liste['id_coupon']]!='0.00'){ ?>

                        <?php echo $xml->Distance; ?>: <span style="color:#9d216e;"><?php echo number_format($les_distance[$liste['id_coupon']],2,',',' '); ?> Km</span> <br />

                    <?php }?><?php */?>

                    </span>

                </div>

                <div style="width:190px; height:35px; float:right; display: table;">

                    <span style="vertical-align:middle; display: table-cell; text-align:right; font-size:13px; ">

                        <?php echo $liste['adresse'];?><br />

                        <?php echo $liste['cp'];?> <?php echo $liste['nom'];?>

                    </span>

                </div>

                <div style="width:390px; height:40px; float:left; display:table;">

                	<span style="vertical-align:middle; display: table-cell;">

                    <a style="color:#9d216e; font-size:15px; font-weight:bold;" href="md-<?php echo $default_region;?>-<?php echo $nom_region;?>-<?php echo $liste['id_magazin']; ?>-<?php echo $nom;?>.html">

                    	<?php echo substr($liste['titre'],0,60);?>

                    </a>

                    </span>

                </div>

            </div>

            

            <div class="item_link">

                

                <a href="md-<?php echo $default_region;?>-<?php echo $nom_region;?>-<?php echo $liste['id_magazin']; ?>-<?php echo $nom;?>.html#tabs-5" style="background-color:#f49c00;">

                <?php echo count_coupon($liste['id_magazin'],$default_region); ?> <?php echo $xml->coupons_reduction ; ?></a>

				

                <a href="md-<?php echo $default_region;?>-<?php echo $nom_region;?>-<?php echo $liste['id_magazin']; ?>-<?php echo $nom;?>.html#tabs-4" style="background-color:#9d216e;">

                <?php echo count_event($liste['id_magazin'],$default_region); ?> <?php echo $xml->evenement ?></a>

                

                <a href="md-<?php echo $default_region;?>-<?php echo $nom_region;?>-<?php echo $liste['id_magazin']; ?>-<?php echo $nom;?>.html#tabs-6" style="background-color:#b45f93;">

                <?php echo count_product($liste['id_magazin'],$default_region);?> <?php if(count_product($liste['id_magazin'],$default_region)<=1){echo $xml->produit;}else{echo $xml->produits;}?></a>

            </div>

        </div>

        

        <!--  START ITME RIGHT -->

        <div class="itme-right" style="float:left; <?php if($liste['photo']){?> background:url(assets/images/coupon/<?php echo $liste['photo']; ?>);<?php }elseif($liste['logo']){?> background:url(assets/images/magasins/<?php echo $liste['logo']; ?>);<?php }else{?> background:url(assets/images/def.png); <?php }?>  background-size:100% 100%; background-color:#FFF; ">

			<a href="javascript:;" onClick="ajax('ajax/addtocart.php?region=<?php echo $region;?>&id_coupon=<?php echo $liste['id_coupon']; ?>','#carts');" style="float:left; display: table;"><span style="vertical-align:middle; display: table-cell; float:left; width:120px; width:123px\9;"><?php echo $xml->Ajouter_au_panier ?>&nbsp;&nbsp;<img src="assets/images/icon_magazin.png" alt="" style="margin-bottom:-2px;"/></span></a>

		</div>

    </div>



<!--  END PAGE ITME -->





<?php }?>

</div>



<div class="pageing">

<?php

echo $paginate;

?>

</div>

   

    

    

