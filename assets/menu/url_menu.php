<?php

$magazinducoin = mysql_pconnect($hostname_magazinducoin, $username_magazinducoin, $password_magazinducoin); 

	mysql_query("SET character_set_results=utf8", $magazinducoin);

    mb_language('uni'); 

    mb_internal_encoding('UTF-8');

    mysql_select_db($database_magazinducoin, $magazinducoin);

    mysql_query("set names 'utf8'",$magazinducoin);

?>

<div id="url_menu_bar" style="width:985px; height:21px; margin-top:88px; padding-left:15px; font-size:12px; float:left; border-bottom:1px solid #CBCBCB;">

    <?php

    $query_villes = "SELECT nom_region FROM region WHERE id_region = ".$default_region;

    $villes = mysql_query($query_villes) or die(mysql_error());

    $row_villes = mysql_fetch_array($villes);

	

	if($_GET['categorie']!=''){

		$query_category = "SELECT cat_name FROM category WHERE cat_id='".$_GET['categorie']."' ORDER BY category.order ASC";

		$category = mysql_query($query_category) or die(mysql_error());

		$row_category = mysql_fetch_array($category);

	}

	if($_GET['sous_categorie']!=''){

		$query_category1 = "SELECT cat_name FROM category WHERE cat_id='".$_GET['sous_categorie']."' ORDER BY category.order ASC";

		$category1 = mysql_query($query_category1) or die(mysql_error());

		$row_category1 = mysql_fetch_array($category1);

	}

	if($_GET['sous_categorie2']!=''){

		$query_category2 = "SELECT cat_name FROM category WHERE cat_id='".$_GET['sous_categorie2']."' ORDER BY category.order ASC";

		$category2 = mysql_query($query_category2) or die(mysql_error());

		$row_category2 = mysql_fetch_array($category2);

	}

	if($_GET['id_cat']!=''){

		$query_category = "SELECT cat_name FROM category WHERE cat_id='".$_GET['id_cat']."' ORDER BY category.order ASC";

		$category = mysql_query($query_category) or die(mysql_error());

		$row_category = mysql_fetch_array($category);

	}

	

    

    $find = array(" ","(",")","é","è","ê","ë","ï","ö","à","Ë","Ê","É","Ä","Â","Ö","Ô","Ï","ô","'",".","/");

    $replace = array("-","","","e","e","e","e","i","o","a","E","E","E","A","A","O","O","I","o","","","");

    ?>

    <?php $namede=str_replace($find,$replace,($row_villes['nom_region']));?>

    <?php $nom_region=str_replace($finds,$replaces,(getRegionById($default_region)));?>

    <a href="accueil.html">Accueil </a> 

    <?php if($default_region!=''){?>

    &gt; <a href="<?php echo $default_region;?>-<?php echo $namede;?>.html">Région <?php echo ($row_villes['nom_region']);?></a>

    <?php }?>

    <?php /*?><?php if($_SESSION['url_path']!=''){?>

    > <a href="<?php echo $_SESSION['url'];?>"><?php echo $_SESSION['url_path'];?></a>

    <?php }?><?php */?>

    <?php if(strpos($_SERVER['PHP_SELF'],'liste_magasins.php') !== FALSE){ ?>

		&gt; <a href="magasins-<?php echo $nom_region;?>-<?php echo $default_region;?>.html">Magasins</a>

        

        <?php if($_GET['categorie']!=''){?>

        &gt; <a href="sub_magasins-<?php echo $nom_region;?>-<?php echo $default_region;?>-<?php echo $_GET['categorie'];?>.html"><?php echo ($row_category['cat_name']);?></a>

        <?php }?>

        <?php if($_GET['sous_categorie']!=''){?>

        &gt; <a href="sub_magasins-<?php echo $nom_region;?>-<?php echo $default_region;?>-<?php echo $_GET['categorie'];?>-<?php echo $_GET['sous_categorie'];?>.html"><?php echo ($row_category1['cat_name']);?></a>

        <?php }?>

         

    <?php }?>

    

	<?php if(strpos($_SERVER['PHP_SELF'],'rechercher.php') !== FALSE){ ?>

		&gt; <a href="produits-<?php echo $nom_region;?>-<?php echo $default_region;?>.html">Produits</a>

        

        <?php if($_GET['categorie']!=''){?>

        &gt; <a href="sub_produits-<?php echo $nom_region;?>-<?php echo $default_region;?>-<?php echo $_GET['categorie'];?>.html"><?php echo ($row_category['cat_name']);?></a>

        <?php }?>

        <?php if($_GET['sous_categorie']!=''){?>

        &gt; <a href="sub_sub_produits-<?php echo $nom_region;?>-<?php echo $default_region;?>-<?php echo $_GET['categorie'];?>-<?php echo $_GET['sous_categorie'];?>.html"><?php echo ($row_category1['cat_name']);?></a>

        <?php }?>

        <?php if($_GET['sous_categorie2']!=''){?>

        &gt; <a href="sub_sub_produits-<?php echo $nom_region;?>-<?php echo $default_region;?>-<?php echo $_GET['categorie'];?>-<?php echo $_GET['sous_categorie'];?>-<?php echo $_GET['sous_categorie2'];?>.html"><?php echo ($row_category2['cat_name']);?></a>

        <?php }?>

         

    <?php }?>

    

	<?php if(strpos($_SERVER['PHP_SELF'],'rechercher_cpn.php') !== FALSE){ ?>

		> <a href="coupons-<?php echo $nom_region;?>-<?php echo $default_region;?>.html">Coupons</a>

        

        <?php if($_GET['categorie']!=''){?>

        > <a href="sub_coupons-<?php echo $nom_region;?>-<?php echo $default_region;?>-<?php echo $_GET['categorie'];?>.html"><?php echo ($row_category['cat_name']);?></a>

        <?php }?>

        <?php if($_GET['sous_categorie']!=''){?>

        > <a href="sub_sub_coupons-<?php echo $nom_region;?>-<?php echo $default_region;?>-<?php echo $_GET['categorie'];?>-<?php echo $_GET['sous_categorie'];?>.html"><?php echo ($row_category1['cat_name']);?></a>

        <?php }?>

         

    <?php }?>

    

	<?php if(strpos($_SERVER['PHP_SELF'],'pcal.php') !== FALSE){ ?>

		> <a href="evenements-<?php echo $nom_region;?>-<?php echo $default_region;?>.html">&Eacute;v&egrave;nements</a>

        

        <?php if($_GET['id_cat']!=''){?>

        > <a href="sub_evenements-<?php echo $nom_region;?>-<?php echo $default_region;?>-<?php echo $_GET['id_cat'];?>.html"><?php echo ($row_category['cat_name']);?></a>

        <?php }?>

         

    <?php }?>

    

    <?php if($_SESSION['url_path_sub']!='' and $_SESSION['url_path_sub']=='Produits detail'){?>

        <?php

			$query_pro = "SELECT 

						  produits.titre

						FROM

						  produits,

						  magazins,

						  category 

						WHERE produits.categorie = category.cat_id 

						  AND produits.id_magazin = magazins.id_magazin 

						  AND produits.categorie = '".$_GET['cat_id']."' 

						  AND produits.id_magazin = '".$_GET['mag_id']."' 

						  AND produits.id = '".$_GET['id']."'";

			$pro= mysql_query($query_pro) or die(mysql_error());

			$row_pro = mysql_fetch_array($pro);

		?>

    > <a href="<?php echo $_SESSION['url_sub'];?>"><?php echo $row_pro['titre'];?></a>

    

    <?php }elseif($_SESSION['url_path_sub']!='' and $_SESSION['url_path_sub']=='Magasins detail'){?>

    <?php

			$query_pro = "SELECT 

						  magazins.nom_magazin

						FROM

						  magazins

						WHERE magazins.id_magazin = '".$_GET['mag_id']."'";

			$pro= mysql_query($query_pro) or die(mysql_error());

			$row_pro = mysql_fetch_array($pro);

		?>

    > <a href="<?php echo $_SESSION['url_sub'];?>"><?php echo $row_pro['nom_magazin'];?></a>

    <?php }?>

    

    <?php 

	if($_REQUEST['choix']){

		echo '> '.$_REQUEST['departement_text'];	

	}

	?>

    

    

</div>



































