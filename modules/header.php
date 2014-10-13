<style>

#slideshow_bk { 

    position: relative; 

    width: 468px; 

    height: 60px; 

    box-shadow: 0 0 20px rgba(0,0,0,0.4); 

	line-height:15.5px;

	

}



#slideshow_bk > div { 

    position: absolute; 

    top: 0px; 

    left: 0px; 

    right: 0px; 

    bottom: 0px; 

	background-color:#F49C00;

}

.en_cours{

	/*text-align:center;*/

}





</style>

<script>

$(window).load(function() {

    $("#slideshow_bk > div:gt(0)").hide();



setInterval(function() { 

  $('#slideshow_bk > div:first')

    .fadeOut(1000)

    .next()

    .fadeIn(1000)

    .end()

    .appendTo('#slideshow_bk');

},  5000);

});



$(document).ready(function () {

	$('#show_cart').mouseover(function() {

		$('#carts').show();

	});

	$('#carts').mouseleave(function() {

		$('#carts').hide();

	});

});



</script>



<div id="header">

    	<div class="inner">

        	<div class="logo">

            	<a href="accueil.html" title="Magasin du coin"><img src="template/images/logo.png" width="197" height="75" alt="Logo - Magasin du coin" title="Logo - Magasin du coin" /></a>

            	<div id="regionHeader">

                	<?php echo isset($_SESSION['kt_prenom']) ? 'Bonjour '.$_SESSION['kt_prenom']:''; ?>

            	</div>

            </div>

            

            <div class="connection">

            	<ul>

                	<?php if( isset($_SESSION['kt_login_id'])) { ?>

                        <li class="button"><a href="membre.html"><?php echo $xml->espace_membre; ?></a></li>

                        <li class="button"><a href="logout.php?logout"><?php echo $xml->deconnexion; ?></a></li>

                     <?php } else { ?> 

                        <li>

                    	<span id="inscrire"></span><a href="inscription.php"> <?php echo $xml->inscription; ?></a>

                        </li>

                        <li>

                            <span id="seconnecter"></span><a href="authetification.php"> <?php echo $xml->connexion; ?></a> 

                        </li>

                     <?php } ?>

                     	<li>

                            <a href="#" id="show_cart">Ma liste</a>

                        </li>

                        

                </ul> 

                <div id="carts" style="display:none; width:200px; background:#FFF; border:1px solid; clear:both; position:absolute; top:13px; right:0px; z-index:999999">

					<?php include("modules/liste_course.php"); ?>

                </div>

            </div>

            

            <div class="pub" style="width:468px; height:60px;">

                <div id="slideshow_bk">

				<?php

					$finds = array(" ","(",")","é","è","ê","ë","ï","ö","à","Ë","Ê","É","Ä","Â","Ö","Ô","Ï","ô","'",".","/");

					$replaces = array("-","","","e","e","e","e","i","o","a","E","E","E","A","A","O","O","I","o","","","");

				?>

                    <?php if(strpos($_SERVER['PHP_SELF'],'region.php') !== FALSE){?>

                    <?php 

                        $query_villes = "SELECT 

										    magazins.id_magazin,

										  magazins.nom_magazin,

										  magazins.logo,

										  magazins.region,

										  maps_ville.nom,

										  coupons.titre,

										  coupons.date_debut,

										  coupons.date_fin  

										FROM

										  magazins 

										  INNER JOIN coupons 

											ON (

											  magazins.id_magazin = coupons.id_magasin

											) 

										  INNER JOIN maps_ville 

											ON (

											  maps_ville.id_ville = magazins.ville

											) 

										WHERE banner_start > CURDATE() ORDER BY RAND() LIMIT 0,10";

                        

                        $villes = mysql_query($query_villes) or die(mysql_error());

                        while ($row_villes = mysql_fetch_assoc($villes)){

							$nom=str_replace($finds,$replaces,($row_villes['nom_magazin']));

							$nom_region=str_replace($finds,$replaces,(getRegionById($row_villes['region'])));



                            echo'<div>

							<a href="md-'.$row_villes['region'].'-'.$nom_region.'-'.$row_villes['id_magazin'].'-'.$nom.'.html#tabs-5">

							<div style="float:left; padding-left:10px; width:350px;">

							<b>'.$row_villes['nom_magazin'].'</b><br />

							<b>'.$row_villes['titre'].'</b><br />

							Type : COUPON, Ville : '.$row_villes['nom'].'<br />

							Date debut: '.$row_villes['date_debut'].' Date fin: '.$row_villes['date_fin'].'

							</div>

							<div style="float:left; width:100px;">';?>

                            	<?php if($row_villes['logo']!=''){

								echo '<img src="timthumb.php?src=assets/images/magasins/'.$row_villes['logo'].'&z=1&w=100&h=60"  alt=""/>';

								}?>

							<?php

							echo '</div>

							</a>

							</div>';

                        }

                    ?>

                    <?php 

                        $query_villes = "SELECT

											  magazins.id_magazin,

											  magazins.nom_magazin,

											  magazins.logo,

											  magazins.region,

											  maps_ville.nom,

											  evenements.titre,

											  evenements.date_debut,

											  evenements.date_fin 

										FROM

											maps_ville

											INNER JOIN magazins 

												ON (maps_ville.id_ville = magazins.ville)

											INNER JOIN evenements 

												ON (magazins.id_magazin = evenements.id_magazin)

												 where banner_start > CURDATE() ORDER BY RAND() LIMIT 0,10";

                        

                        $villes = mysql_query($query_villes) or die(mysql_error());

                        while ($row_villes = mysql_fetch_assoc($villes)){

							$nom=str_replace($finds,$replaces,($row_villes['nom_magazin']));

							$nom_region=str_replace($finds,$replaces,(getRegionById($row_villes['region'])));

                            echo'<div>

							<a href="md-'.$row_villes['region'].'-'.$nom_region.'-'.$row_villes['id_magazin'].'-'.$nom.'.html#tabs-4">

							<div style="float:left; padding-left:10px; width:350px;">

							<b>'.$row_villes['nom_magazin'].'</b><br />

							<b>'.$row_villes['titre'].'</b><br />

							Type : évènements, Ville : '.$row_villes['nom'].'<br />

							Date debut: '.$row_villes['date_debut'].' Date fin: '.$row_villes['date_fin'].'

							</div>

							<div style="float:left; width:100px;">';?>

                            	<?php if($row_villes['logo']!=''){

								echo '<img src="timthumb.php?src=assets/images/magasins/'.$row_villes['logo'].'&z=1&w=100&h=60"  alt=""/>';

								}?>

							<?php

							echo '</div>

							</a>

							</div>';

                        }

						echo'<div></div>';

                    ?>

                    <?php }else{?>

                    <?php 

                        $query_villes = "SELECT 

										  magazins.id_magazin,

										  magazins.nom_magazin,

										  magazins.logo,

										  magazins.region,

										  maps_ville.nom,

										  coupons.titre,

										  coupons.date_debut,

										  coupons.date_fin 

										FROM

										  magazins 

										  INNER JOIN coupons 

											ON (

											  magazins.id_magazin = coupons.id_magasin

											) 

										  INNER JOIN maps_ville 

											ON (

											  maps_ville.id_ville = magazins.ville

											) 

										WHERE banner = '1' 

										  AND banner_type = '2' 

										  AND banner_start > CURDATE() ORDER BY RAND() LIMIT 0,10";

                        

                        $villes = mysql_query($query_villes) or die(mysql_error());

                        while ($row_villes = mysql_fetch_assoc($villes)){

							$nom=str_replace($finds,$replaces,($row_villes['nom_magazin']));

							$nom_region=str_replace($finds,$replaces,(getRegionById($row_villes['region'])));

                            echo'<div>

							<a href="md-'.$row_villes['region'].'-'.$nom_region.'-'.$row_villes['id_magazin'].'-'.$nom.'.html#tabs-5">

							<div style="float:left; padding-left:10px; width:350px;">

							<b>'.$row_villes['nom_magazin'].'</b><br />

							<b>'.$row_villes['titre'].'</b><br />

							Type : COUPON, Ville : '.$row_villes['nom'].'<br />

							Date debut: '.$row_villes['date_debut'].' Date fin: '.$row_villes['date_fin'].'

							</div>

							<div style="float:left; width:100px;">';?>

                            	<?php if($row_villes['logo']!=''){

								echo '<img src="timthumb.php?src=assets/images/magasins/'.$row_villes['logo'].'&z=1&w=100&h=60"  alt=""/>';

								}?>

							<?php

							echo '</div>

							</a>

							</div>';

                        }

                    ?>

                    <?php 

                        $query_evet = "SELECT

										  magazins.id_magazin,

										  magazins.nom_magazin,

										  magazins.logo,

										  magazins.region,

										  maps_ville.nom,

										  evenements.titre,

										  evenements.date_debut,

										  evenements.date_fin 

										FROM

											maps_ville

											INNER JOIN magazins 

												ON (maps_ville.id_ville = magazins.ville)

											INNER JOIN evenements 

												ON (magazins.id_magazin = evenements.id_magazin)

												 where banner='1' and banner_type='2' and banner_start > CURDATE() ORDER BY RAND() LIMIT 0,10";

                        

                        $evet = mysql_query($query_evet) or die(mysql_error());

                        while ($row_evet = mysql_fetch_assoc($evet)){

							$nom=str_replace($finds,$replaces,($row_evet['nom_magazin']));

							$nom_region=str_replace($finds,$replaces,(getRegionById($row_evet['region'])));

                            //echo'<div><img src="assets/images/banner/'.$row_evet['banner_image'].'" alt=""/></div>';

							echo'<div>

							<a href="md-'.$row_evet['region'].'-'.$nom_region.'-'.$row_evet['id_magazin'].'-'.$nom.'.html#tabs-4">

							<div style="float:left; padding-left:10px; width:350px;">

							<b>'.$row_evet['nom_magazin'].'</b><br />

							<b>'.$row_evet['titre'].'</b><br />

							Type : évènements, Ville : '.$row_evet['nom'].'<br />

							Date debut: '.$row_evet['date_debut'].' Date fin: '.$row_evet['date_fin'].'

							</div>

							<div style="float:left; width:100px;">';?>

                            	<?php if($row_evet['logo']!=''){

								echo '<img src="timthumb.php?src=assets/images/magasins/'.$row_evet['logo'].'&z=1&w=100&h=60"  alt=""/>';

								}?>

							<?php

							echo '</div>

							</a>

							</div>';

                        }

						echo'<div></div>';

                    ?>

                    <?php }?>



                   



                </div>

			</div>
			<?php if(strpos($_SERVER['PHP_SELF'],'index.php') !== FALSE){?>
            <div class="partage">

            	<!-- AddThis Button BEGIN -->

                    <div class="addthis_toolbox addthis_default_style ">

                    <a class="addthis_button_facebook_like"></a>

                    <a class="addthis_button_tweet"></a>

                    <?php /*?><a class="addthis_button_google_plusone" g:plusone:size="medium"></a><?php */?>

                    <script type="text/javascript">

					//<![CDATA[

					document.write('<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>');

					//]]>

					</script>

                   

                    </div>

                    <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f6c9c70607e093b" defer="defer"></script>

                    <!-- AddThis Button END -->            

            </div>
			<?php }?>
            <div class="bonjour">

            	À côté de chez vous<br />

                <?php

					$find = array(" ","(",")","é","è","ê","ë","ï","ö","à","Ë","Ê","É","Ä","Â","Ö","Ô","Ï","ô","'",".","/");

					$replace = array("-","","","e","e","e","e","i","o","a","E","E","E","A","A","O","O","I","o","","","");

					?>

                    <?php $namede=str_replace($find,$replace,(getRegionById($default_region)));?>

					<?php if(strpos($_SERVER['PHP_SELF'],'index.php') == FALSE)

                     {

						  //echo getRegionById($default_region); 

						  //echo $default_region;

						  $reg = '<a href="'.$default_region.'-'.$namede.'.html" style="font-weight:bold; color:#F6AE30; width:200px;">'.(getRegionById($default_region)).'</a>';

					 } ?> 

                

                <?php if(isset($_GET['region'])){?>

                <span>en <?php echo $reg;?></span>

                <?php }?>

            </div>

          

        </div>

</div>