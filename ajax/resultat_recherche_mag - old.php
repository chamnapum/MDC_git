

<?php 

require_once('../Connections/magazinducoin.php');

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

	margin:170px 0px 0px 24px; 

	margin:170px 0px 0px 20px\9;

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

}

.paginate {

	border: 0 none;

    display: inline;

    font-size: 11px;

    margin: 0;

    padding: 0;

	font-size:16px;

	font-family: 'deliciousbold';

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

$region			= isset($_REQUEST['region'])?$_REQUEST['region']:$default_region;

//$departement	= isset($_REQUEST['departement'])?$_REQUEST['departement']:"";

if($_REQUEST['departement']=='region') $departement = '';

else if($_REQUEST['departement']=='near') $departement = '';

else if($_REQUEST['departement']=='near_ville') $departement = '';

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



$categorie		= isset($_REQUEST['categorie'])?$_REQUEST['categorie']:"";

$sous_categorie	= isset($_REQUEST['sous_categorie'])?$_REQUEST['sous_categorie']:"";

$sous_categorie2= isset($_REQUEST['sous_categorie2'])?$_REQUEST['sous_categorie2']:"";

$mot_cle		= isset($_REQUEST['mot_cle']) ? $_REQUEST['mot_cle']:"";



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

	

	$query_liste_produit_test = "SELECT  maps_ville.cp, magazins.nom_magazin, magazins.id_magazin, magazins.latlan, magazins.logo, magazins.adresse, region.nom_region, maps_ville.nom, magazins.description AS des,

		  (SELECT 

			COUNT(*) 

		  FROM

			magazins AS tt 

		  WHERE tt.id_magazin = magazins.id_magazin 

			AND magazins.en_tete_liste = '1' 

			AND magazins.en_tete_liste_payer = '1' 

			AND magazins.en_tete_liste_fin > '".$datetime."' 

			AND DATE_ADD(

			  en_tete_liste_fin,

			  INTERVAL -day_en_tete_liste DAY

			) <= '".$datetime."') AS top  

			FROM ( magazins 

				$inner_join

			  INNER JOIN departement 

				ON departement.code = magazins.department

			  INNER JOIN maps_ville 

				ON maps_ville.id_ville = magazins.ville)

			";



	$tab = array();

	

	if($_REQUEST['departement']=='near')

	$tab[] = " region.id_region =  ".$region;

	else

	$tab[] = " magazins.region = ".$region;

	//$tab[] = " magazins.latlan not in ('(999,999)','(0,0)','(,)','')";

		

	if($departement) $tab[] = " magazins.department = ".$departement;

	if($_REQUEST['ville_near_all']=='1'){

		if($ville) 				$tab[] = " magazins.ville IN ($ville $ville_near_val)";

	}else{

		if($ville) 				$tab[] = " magazins.ville IN ($ville)" ;

	}

	if($categorie) 			$tab[] = " magazins.categorie = ".$categorie;

	if($sous_categorie)		$tab[] = " magazins.sous_categorie = $sous_categorie ";

	if($sous_categorie2)	$tab[] = " magazins.sous_categorie2 = $sous_categorie2 ";

	if($mot_cle and strpos($mot_cle,'mot') === FALSE)			

	$tab[] = " magazins.nom_magazin LIKE '%$mot_cle%'";





	$where = "";



	if(count($tab)) $where = "WHERE ".implode(' AND ',$tab);

	$query_liste_produit_test .= $where;

	$query_liste_produit_test .= " AND magazins.activate='1' AND magazins.payer='1' AND (

    magazins.approuve = '1' 

    OR (

      magazins.approuve = 0 

      AND magazins.public = 1 

      AND magazins.public_start < '".$datetime."' 

      AND (

        magazins.public_start + INTERVAL 20 MINUTE

      ) < '".$datetime."'

    )

  )  ORDER BY top DESC ";

	

	

	$query_test = mysql_query($query_liste_produit_test);

	$nbr_total	= mysql_num_rows($query_test);

	//echo $nbr_total;

	//echo $ids[];



	//echo $query_liste_produit_test;

	

	$part = "region=$region&categorie=$categorie&sous_categorie=$sous_categorie&mot_cle=$mot_cle&departement=$departement&id_ville=$ville&ville_near_all=$ville_near_all";

	//echo $part;

	$limit = 10;

	$total_pages = $nbr_total;

	$stages = 3;

	$page ='';

	if(isset($_REQUEST['pa'])){

		$page =$_REQUEST['pa'];

	}else{

		$page =1;

	}

	if($page){

		$start = ($page - 1) * $limit; 

	}else{

		$start = 0;	

	}

	

	

	$query_liste_produits=$query_liste_produit_test." LIMIT $start, $limit";

	// $query_liste_produits=$query_liste_produit_test;

	echo $query_liste_produits;

	// Initial page num setup

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

			$paginate.= "<a href=\"javascript:ajax('".$_SERVER['PHP_SELF']."?$part&pa=$next','#result');\">SUIVANTE  &#62;&#62;</a>";	

		}else{

			$paginate.= "<span class='disabled'>SUIVANTE &#62;&#62;</span>";

		}

			

		$paginate.= "</div>";	

	}

	

	



	$rkt= mysql_query($query_liste_produits);



?> 

<?php

$finds = array(" ","(",")","é","è","ê","ë","ï","ö","à","Ë","Ê","É","Ä","Â","Ö","Ô","Ï","ô","'",".","/");

$replaces = array("-","","","e","e","e","e","i","o","a","E","E","E","A","A","O","O","I","o","","","");

?>



<div class="page-item">

<?php	

	while($liste = mysql_fetch_assoc($rkt)){

?>

<?php $nom=str_replace($finds,$replaces,$liste['nom_magazin']);?>

<?php $nom_region=str_replace($finds,$replaces,(getRegionById($default_region)));?>





				<!--  START PAGE ITME -->



            	<div class="content-itme">

                	<!--  START ITME LEFT -->

                    <div class="itme-left">

                    	<div class="ville">

                        	<div class="ville_title1">

                                <?php echo (substr($liste['nom'],0,13));?>

                            </div>

                            <div class="ville_title2">

                                <a href="md-<?php echo $default_region;?>-<?php echo $nom_region;?>-<?php echo $liste['id_magazin'];?>-<?php echo $nom;?>.html"><?php echo substr($liste['nom_magazin'],0,23);?></a>

                            </div>

                        </div>

                        

                        <div class="item_descript">

                        	<div style="width:290px; float:left; font-size:13px;">

                                <?php echo substr($liste['des'],0,60);?>

                            </div>

                            <div style="width:100px; height:75px; float:right; display: table;">

                            	<span style="vertical-align:middle; display: table-cell; text-align:right; font-size:13px;">

                                	<?php echo $liste['adresse'];?><br />

                                    <?php echo $liste['cp'];?> <?php echo ($liste['nom']);?>

                                </span>

                            </div>

                        </div>

                        

                        <div class="item_link">

                        	

                        	<a href="md-<?php echo $default_region;?>-<?php echo $nom_region;?>-<?php echo $liste['id_magazin'];?>-<?php echo $nom;?>.html#tabs-5" style="background-color:#f49c00;">

							<?php echo count_coupon($liste['id_magazin'],$default_region); ?> <?php echo $xml->coupons_reduction ; ?></a>

        

                        	<a href="md-<?php echo $default_region;?>-<?php echo $nom_region;?>-<?php echo $liste['id_magazin'];?>-<?php echo $nom;?>.html#tabs-4" style="background-color:#9d216e;">

							<?php echo count_event($liste['id_magazin'],$default_region); ?> <?php echo $xml->evenement ?></a>

                        	

                            <a href="md-<?php echo $default_region;?>-<?php echo $nom_region;?>-<?php echo $liste['id_magazin'];?>-<?php echo $nom;?>.html#tabs-6" style="background-color:#b45f93;">

							<?php echo count_product($liste['id_magazin'],$default_region);?> <?php if(count_product($liste['id_magazin'],$default_region)<=1){echo $xml->produit;}else{echo $xml->produits;}?></a>

                        </div>

                    </div>

                    

                    <!--  START ITME RIGHT -->

                    <div class="itme-right" style="background-size:100% 100%; <?php if($liste['logo']){?> background:url(assets/images/magasins/<?php echo $liste['logo']; ?>);<?php }else{?> background:url(assets/images/def.png);<?php }?> ">

                    	<?php if(isset($_SESSION['kt_login_id'])){ ?> 

                        	<a href="javascript:;" onclick="ajax('ajax/addtofav.php?id_magasin=<?php echo $liste['id_magazin']; ?>','#favoris<?php echo $liste['id_magazin']; ?>');" style="float:left; display: table;"><span style="vertical-align:middle; display: table-cell; float:left; width:139px; width:139px\9;">Ajouter à vos favoris <img src="assets/images/star.png" alt="" style="margin-bottom:-2px;"/></span></a>

						<?php } else { ?>

                        	<a href="javascript:;" onclick="alert('Vous devrez être connecté pour vous abonner. Merci de créer un compte si celà n&acute;a pas encore été fait');" style="float:left; display: table;"><span style="vertical-align:middle; display: table-cell; float:left; width:139px; width:139px\9;">Ajouter à vos favoris <img src="assets/images/star.png" alt="" style="margin-bottom:-2px;"/></span></a>

						<?php }?>

                    </div>

                </div>

   

            <!--  END PAGE ITME -->



<?php  } ?>



</div>

<div class="clear"></div>

<div class="pageing">

<?php

echo $paginate;

?>

</div>



