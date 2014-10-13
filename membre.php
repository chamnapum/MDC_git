<?php require_once('Connections/magazinducoin.php'); ?>

<?php

// Load the tNG classes

require_once('includes/tng/tNG.inc.php');



// Make unified connection variable

$conn_magazinducoin = new KT_connection($magazinducoin, $database_magazinducoin);



//Start Restrict Access To Page

$restrict = new tNG_RestrictAccess($conn_magazinducoin, "");

//Grand Levels: Level

//$restrict->addLevel("1");

$restrict->Execute();

//End Restrict Access To Page

//echo $_SESSION['kt_adresse'];

if(isset($_SESSION['kt_login_id']) and $_SESSION['kt_payer'] == 0) header('Location: message_aprouvation.php');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 

	<title>Magasinducoin | Espace membre </title>

    <?php include("modules/head.php"); ?>

    

</head>

<body id="sp">

<?php include("modules/header.php"); ?>
<!-- styles -->
<!--<link href="assets/intro/assets/css/bootstrap.min.css" rel="stylesheet">-->
<link href="assets/intro/assets/css/demo.css" rel="stylesheet">

<!-- Add IntroJs styles -->
<link href="assets/intro/introjs.css" rel="stylesheet">

<link href="assets/intro/assets/css/bootstrap-responsive.min.css" rel="stylesheet">


<div id="content">

	<?php include("modules/member_menu.php"); ?>   

    

	<?php if($_SESSION['kt_login_level'] == 1){ ?>

        <?php include("modules/credit.php"); ?>

		<?php

            $query_test = "UPDATE coupons SET active='0' , gratuit='0' WHERE date_fin < '".date("Y-m-d ")."' AND id_user='".$_SESSION['kt_login_id']."'"  ;

            $test_gratuit = mysql_query($query_test, $magazinducoin) or die('2'.mysql_error());

			

			$query_test2 = "UPDATE evenements SET active='0' , gratuit='0' WHERE date_fin < '".date("Y-m-d ")."' AND id_user='".$_SESSION['kt_login_id']."'"  ;

            $test_gratuit2 = mysql_query($query_test2, $magazinducoin) or die('2'.mysql_error());

        ?>

    <?php } ?>

    

    <div style="padding-left:8%; float:left; width:84%;">

    <?php if($_SESSION['kt_login_level'] == 1){ ?>

    	<span style="padding-left:10px; float:left; width:98%; font-size:18px;">Bonjour et bienvenue sur votre tableau de bord</span>

        <p style="padding-left:10px; float:left; width:98%; font-size:14px; margin-top:10px;">

        	Le tableau de bord vous permet de gérer entièrement votre magasin. Vous pouvez décrire en profondeur votre magasin, ajouter vos produits, créer des coupons de réductions, créer des évènements, louer un photographe pour donner une image professionnelle à votre magasin et toutes ces possibilités sont gratuites.

        </p>

        <p style="padding-left:10px; float:left; width:98%; font-size:14px; margin-top:10px;">

        	Attention : Chaque ajout effectué sur le site sera vérifié par nos modérateurs avant d'être validé (délai maximum de 24 heures avant validation).

        </p>

        <p style="padding-left:10px; float:left; width:98%; font-size:14px; margin-top:10px; text-align:center;">

        	Consultez notre page d'aide ou contactez-nous par email.

        </p>

	<?php } ?>

    </div>

    <div class="clear"></div>

	<div style="padding-left:8%; float:left; width:92%;">

			<ul class="profil_membre">

                <li data-step="2" data-intro="Pour commencer, vous devez configurer correctement votre profil si ça n'a pas encore été fait"><a href="mon_profil.html"><?php echo $xml->Mon_profil; ?></a> </li>

		<?php if($_SESSION['kt_login_level'] == '1') { ?>

                <li data-step="3" data-intro="Ensuite Vous devez ajouter  votre ou vos  magasin(s) en y mettant tous les détails possible (Description, Photos, Logos …)"><a href="mes_magazins.html"><?php echo $xml->Mes_magasins; ?></a> </li>

                <li data-step="4" data-intro="Ajouter dans cette section tous vos coupons de réduction ou bons plans de votre  magasin. 1 seul coupon ACTIF est gratuit. Lorsqu'il sera périmé ou supprimé, vous pourrez en créer un autre gratuitement"><a href="mes_coupons.html"><?php echo $xml->Mes_coupons; ?></a> </li>

                <li data-step="5" data-intro="« Mes produits » - Ajouter dans cette section tous vos produits phare et ajoutez-y des promotions. Tous les produits déposés sont gratuits. La date de validité d'un produit est de 2 mois"><a href="mes_produits.html"><?php echo $xml->Mes_produits; ?></a></li>

                <li data-step="6" data-intro="« Mes événements » - Ajouter dans cette section tous vos événements et animations de votre magasin. 1 seul événement ACTIF est gratuit. Lorsqu'il sera périmé ou supprimé, vous pourrez en créer un autre gratuitement"><a href="mes_evenements.html"><?php echo $xml->mes_evenements; ?></a></li>

		<?php } ?>       

                <li data-step="7" data-intro="« Mes favoris » - - Sur chaque magasin visible sur le site, il existe un bouton « Ajouter à vos Favoris ». Cette section permet d'avoir accès rapidement aux magasins que l'on préfère afin d'y trouver immédiatement ces produits, ou ces événements, ou ces coupons."><a href="mes_favoris.html"><?php echo $xml->Mes_favoris; ?></a></li>

                <li data-step="8" data-intro="« Mes abonnements » - Sur chaque magasin visible sur le site, il existe un bouton « S'abonner ». Cette section permet d'être informé par mail dès que le magasin en question publiera un coupon ou un événement."><a href="mes_sabonner.html">Mes abonnements</a></li>

		<?php if($_SESSION['kt_login_level'] == '1') { ?>       

                <li data-step="9" data-intro="« Crédits » - Cette section permet de recharger du crédit pour pouvoir acheter des options de publication. Toutes les options sont disponible lorsque vous créerez un produit ou un coupon ou un événement."><a href="mon_abonnement.html">Crédit</a></li>

     	        <li data-step="10" data-intro="« Louer un photographe » - Cette section vous permet de trouver des photographes dans votre région au cas où vous avez besoin de photo professionnelle de votre magasin ou de vos produits."><a href="photographes.html">Louer un photographe</a></li>

     	        <li data-step="11" data-intro="« Parrainage » – Cette section permet de parrainer vos collègues commerçants. Vous  devez insérer leur adresse email pour les avertir de l'existence du site pour qu'ils s'y inscrivent. Chaque parrainage vous apportera 10€ de crédit."><a href="Parrainage.html">Parrainage</a></li>

                <li data-step="12" data-intro="« Aide » – Cette section vous permettra d'accéder aux Foires aux questions et vidéo de façon à bien comprendre l'utilisation du site magasinducoin."><a href="#">Aide</a></li>

                <li data-step="13" data-intro="« Contactez-nous » - En cas de problème ou de question, vous pouvez nous contacter en cliquant sur cette section."><a href="contact.html">Contactez-nous</a></li>

        <?php } ?> 

		<?php if($_SESSION['kt_login_level'] == '2') {?>

                <li><a href="photographes.html">Louer un photographe</a></li>

        <?php }?>
				<li style="padding:0px 0px 20px; text-align:center; width:90%; background:none;"><a href="javascript:void(0);" onclick="javascript:introJs().start();"><img src="assets/images/intro.png" alt="" data-step="1" data-intro="Bienvenue dans la visite guidée de Magasinducoin, la 1ère communauté des commerçants de France incluant les bons plans, les produits et les événements  des commerces de votre ville!"/></a></li>
            </ul>

	</div>

</div>



<div id="footer">

    <div class="recherche">

    &nbsp;

    </div>

	<?php include("modules/footer.php"); ?>

</div>
<script type="text/javascript" src="assets/intro/intro.js"></script>
</body>

</html>