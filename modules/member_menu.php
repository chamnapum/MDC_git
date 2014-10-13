<?php

	$time_now = time(); // checking the time now when home page starts

    if ($time_now > $_SESSION['expire']) {

		session_destroy();

		/*echo "<script>alert('".date("H:i:s",$time_now).' > '.date("H:i:s",$_SESSION['expire'])."');</script>";*/

		echo'<script>window.location="authetification.html";</script>';

    } else {

		$_SESSION['start'] = time();

		$_SESSION['expire'] = $_SESSION['start'] + (15 * 60);

		/*echo "<script>alert('".date("H:i:s",$time_now ).' < '.date("H:i:s",$_SESSION['expire'])."');</script>";*/

    }

?>

<style>

	.menu_member{

		width:100%; 

		float:left;

		background: #372f2b;

	}

	.menu_member a{

		float:left;

		display: block;

		z-index: 510;

		height: 25px;

		line-height: 25px;

		padding: 5px 4px;

		

		font-family: Helvetica, Arial, sans-serif;

		font-size: 12px;

		/*font-weight:bold;*/

		color: #fcfcfc;

		text-shadow: 0 0 1px rgba(0,0,0,.35);

	

		background: #372f2b;

		border-left: 1px solid #4b4441;

		border-right: 1px solid #312a27;

	

		-webkit-transition: all .3s ease;

		-moz-transition: all .3s ease;

		-o-transition: all .3s ease;

		-ms-transition: all .3s ease;

		transition: all .3s ease;

	}

	.menu_member a:hover, .menu_member a.menu_current{

		background: #9D216E;

	}

</style>



<div class="menu_member" data-step="14" data-intro="14-	Ce menu reprend l'ensemble de l'espace membre pour vous faciliter l'accès aux possibilités du site sans retourner sur la page d'accueil de l'espace membre.">

    <a href="mon_profil.html" <?php if(strpos($_SERVER['PHP_SELF'],'mon-profil.php') !== FALSE){?>class="menu_current"<?php }?>>Mon profil</a>

    <?php if($_SESSION['kt_login_level'] == 1) { ?>

    <a href="mes_magazins.html" <?php if(strpos($_SERVER['PHP_SELF'],'mes-magazins.php') !== FALSE){?>class="menu_current"<?php }?>>Mes magasins</a>

    <a href="mes_coupons.html" <?php if(strpos($_SERVER['PHP_SELF'],'mes-coupons.php') !== FALSE){?>class="menu_current"<?php }?>>Mes coupons</a>

    <a href="mes_produits.html" <?php if(strpos($_SERVER['PHP_SELF'],'mes-produits.php') !== FALSE){?>class="menu_current"<?php }?>>Mes produits</a>

    <a href="mes_evenements.html" <?php if(strpos($_SERVER['PHP_SELF'],'mes-evenements.php') !== FALSE){?>class="menu_current"<?php }?>>Mes évènements</a>

    <?php }?>

    <a href="mes_favoris.html" <?php if(strpos($_SERVER['PHP_SELF'],'mes-favoris.php') !== FALSE){?>class="menu_current"<?php }?>>Mes favoris</a>

    <a href="mes_sabonner.html" <?php if(strpos($_SERVER['PHP_SELF'],'mes-sabonner.php') !== FALSE){?>class="menu_current"<?php }?>>Mes abonnements</a>

    <?php if($_SESSION['kt_login_level'] == 1) { ?>

    <a href="mon_abonnement.html" <?php if(strpos($_SERVER['PHP_SELF'],'mon-abonnement.php') !== FALSE){?>class="menu_current"<?php }?>>Crédit</a>

    <a href="photographes.html" <?php if(strpos($_SERVER['PHP_SELF'],'photographes.php') !== FALSE){?>class="menu_current"<?php }?>>Louer un photographe</a>

    <a href="Parrainage.html" <?php if(strpos($_SERVER['PHP_SELF'],'Parrainage.php') !== FALSE){?>class="menu_current"<?php }?>>Parrainage</a>

    <a href="#">Aide</a>

    <a href="#">Contactez-nous</a>

    <a href="paiements.html" <?php if(strpos($_SERVER['PHP_SELF'],'mes-payment.php') !== FALSE){?>class="menu_current"<?php }?>>Historique des paiements</a>

    <?php }?>

    <?php if($_SESSION['kt_login_level'] == 2) { ?>

    <a href="photographes.html" <?php if(strpos($_SERVER['PHP_SELF'],'photographes.php') !== FALSE){?>class="menu_current"<?php }?>>Louer un photographe</a>

    <?php }?>

</div> 









