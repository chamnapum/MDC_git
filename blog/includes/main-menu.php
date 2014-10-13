<?php

	$finds = array(" ","(",")","é","è","ê","ë","ï","ö","à","Ë","Ê","É","Ä","Â","Ö","Ô","Ï","ô","'",".","/");

	$replaces = array("-","","","e","e","e","e","i","o","a","E","E","E","A","A","O","O","I","o","","","");

?>

<?php $nom_region=str_replace($finds,$replaces,(getRegionById($default_region)));?>

<link rel="stylesheet" type="text/css" href="css/menu_top.css" />

<script type="text/javascript">

$(document).ready(function () {

	<?php if(strpos($_SERVER['PHP_SELF'],'liste_magasins.php') !== FALSE){?>

		$('#sub_menu .m1').show();

		$('#sub_menu .m2').hide();

		$('#sub_menu .m3').hide();

		$('#sub_menu .m4').hide();

	<?php }elseif(strpos($_SERVER['PHP_SELF'],'rechercher.php') !== FALSE){?>

		$('#sub_menu .m2').show();

		$('#sub_menu .m1').hide();

		$('#sub_menu .m3').hide();

		$('#sub_menu .m4').hide();

	<?php }elseif(strpos($_SERVER['PHP_SELF'],'rechercher_cpn.php') !== FALSE){?>

		$('#sub_menu .m3').show();

		$('#sub_menu .m2').hide();

		$('#sub_menu .m1').hide();

		$('#sub_menu .m4').hide();

	<?php }elseif(strpos($_SERVER['PHP_SELF'],'pcal.php') !== FALSE){?>

		$('#sub_menu .m4').show();

		$('#sub_menu .m2').hide();

		$('#sub_menu .m3').hide();

		$('#sub_menu .m1').hide();

	<?php }else{?>

		$('#sub_menu .m1').show();

		$('#sub_menu .m2').hide();

		$('#sub_menu .m3').hide();

		$('#sub_menu .m4').hide();

	<?php }?>

	$('#sub_menu').css('display','inline-block');



	$('#top_menu #m1').mouseover(function(){

		$('#sub_menu').css('display','inline-block');

		$('#sub_menu .m1').show();

		

		$('#sub_menu .m2').hide();

		$('#sub_menu .m3').hide();

		$('#sub_menu .m4').hide();

	});

	$('#top_menu #m2').mouseover(function(){

		$('#sub_menu').css('display','inline-block');

		$('#sub_menu .m2').show();

		

		$('#sub_menu .m1').hide();

		$('#sub_menu .m3').hide();

		$('#sub_menu .m4').hide();

	});

	

	$('#top_menu #m3').mouseover(function(){

		$('#sub_menu').css('display','inline-block');

		$('#sub_menu .m3').show();

		

		$('#sub_menu .m1').hide();

		$('#sub_menu .m2').hide();

		$('#sub_menu .m4').hide();

	});

	

	$('#top_menu #m4').mouseover(function(){

		$('#sub_menu').css('display','inline-block');

		$('#sub_menu .m4').show();

		

		$('#sub_menu .m1').hide();

		$('#sub_menu .m2').hide();

		$('#sub_menu .m3').hide();

	});

});

</script>

<div id="top_menu">

	<a href="../magasins-<?php echo $nom_region;?>-<?php echo $default_region;?>.html" id="m1" <?php if(strpos($_SERVER['PHP_SELF'],'liste_magasins.php') !== FALSE){?>class="menu_current"<?php }?> >Magasins</a>

	<a href="../produits-<?php echo $nom_region;?>-<?php echo $default_region;?>.html" id="m2" <?php if(strpos($_SERVER['PHP_SELF'],'rechercher.php') !== FALSE){?>class="menu_current"<?php }?> >Produits</a>

	<a href="../coupons-<?php echo $nom_region;?>-<?php echo $default_region;?>.html" id="m3" <?php if(strpos($_SERVER['PHP_SELF'],'rechercher_cpn.php') !== FALSE){?>class="menu_current"<?php }?> >Coupons de réduction</a>

	<a href="../evenements-<?php echo $nom_region;?>-<?php echo $default_region;?>.html" id="m4" <?php if(strpos($_SERVER['PHP_SELF'],'pcal.php') !== FALSE){?>class="menu_current"<?php }?> >Évènements</a>

	<a href="../map.html" <?php if(strpos($_SERVER['PHP_SELF'],'map_view.php') !== FALSE){?>class="menu_current"<?php }?> >Carte</a>

	<a href="accueil.html" <?php if(strpos($_SERVER['PHP_SELF'],'blog/index.php') !== FALSE){?>class="menu_current"<?php }?> >Blog</a>

</div>



<div id="sub_menu">

	<div class="m1">

    <?php

	$sqlcate = "SELECT cat_id, parent_id, cat_name FROM category WHERE parent_id='0' AND type='3' ORDER BY category.order ASC";

	$resultcate = mysql_query($sqlcate) or die (mysql_error());

	?>

	<!-- link to main categories : Produits, Coupons, Evenement-->

	<?php $i = 0; while ($querycate=mysql_fetch_array($resultcate)) { $i++;?>

    	<a href="../sub_magasins-<?php echo $nom_region;?>-<?php echo $default_region;?>-<?php echo $querycate['cat_id'];?>.html"><?php echo ($querycate['cat_name']); ?></a>

    <?php }?>

    </div>

	<div class="m2">

        <?php

		$sqlcate = "SELECT cat_id, parent_id, cat_name FROM category WHERE parent_id='0' AND type='0' ORDER BY category.order ASC";

		$resultcate = mysql_query($sqlcate) or die (mysql_error());

		?>

		<!-- link to main categories : Produits, Coupons, Evenement-->

		<?php $i = 0; while ($querycate=mysql_fetch_array($resultcate)) { $i++;?>

			<a href="../sub_produits-<?php echo $nom_region;?>-<?php echo $default_region;?>-<?php echo $querycate['cat_id'];?>.html"><?php echo ($querycate['cat_name']); ?></a>

		<?php }?>

    </div>

	<div class="m3">

        <?php

		$sqlcate = "SELECT cat_id, parent_id, cat_name FROM category WHERE parent_id='0' AND type='1' ORDER BY category.order ASC";

		$resultcate = mysql_query($sqlcate) or die (mysql_error());

		?>

		<!-- link to main categories : Produits, Coupons, Evenement-->

		<?php $i = 0; while ($querycate=mysql_fetch_array($resultcate)) { $i++;?>

			<a href="../sub_coupons-<?php echo $nom_region;?>-<?php echo $default_region;?>-<?php echo $querycate['cat_id'];?>.html"><?php echo ($querycate['cat_name']); ?></a>

		<?php }?>

    </div>

	<div class="m4">

        <?php

		$sqlcate = "SELECT cat_id, parent_id, cat_name FROM category WHERE parent_id='0' AND type='2' ORDER BY category.order ASC";

		$resultcate = mysql_query($sqlcate) or die (mysql_error());

		?>

		<!-- link to main categories : Produits, Coupons, Evenement-->

		<?php $i = 0; while ($querycate=mysql_fetch_array($resultcate)) { $i++;?>

			<a href="../sub_evenements-<?php echo $nom_region;?>-<?php echo $default_region;?>-<?php echo $querycate['cat_id'];?>.html"><?php echo ($querycate['cat_name']); ?></a>

		<?php }?>

    </div>

</div>