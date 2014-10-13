<?php require_once('Connections/magazinducoin.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 

	<title>Magasinducoin | Les évènements </title>

    



    <?php include("modules/head.php"); ?>

  

</head>

<body id="sp" >

<?php include("modules/header.php"); ?>

<div id="content">

	<style>

	.lister h3{

		color:#9D216E;

		font-size:24px;

	}	

	.lister h1{

		font-size:24px;

		margin-left:20px;

	}

	.lister p{

		font-size:14px;

		text-align:justify;

		margin:0px 20px 10px 20px;

	}

    </style>

   <?php /*?> <?php include("modules/form_recherche_header.php"); ?>

    <div class="top reduit">

        <div id="head-menu" style="float:left;">

        	<?php include("assets/menu/main-menu.php"); ?>

        </div>

		<div id="url-menu" style="float:left;">

        <?php include("assets/menu/url_menu.php"); ?>

        </div>

    </div><?php */?>
    
    <style>
    #url_menu_bar{
         margin-top: 10px !important;
    }
    </style>
    <div id="url-menu" style="float:left;">
	<?php include("assets/menu/url_menu.php"); ?>
    </div>

    

    <div class="clear"></div>



    <div class="lister top" style="float:left; background:#F2EFEF; width:100%;">

        <h3>Les évènements magasin ?</h3>

         	<p>

            <h1>Magasinducoin propose à tous moments des centaines d'évènements dans vos commerces de proximité.</h1>

			</p>

        <p>

			 Chaque magasin proche de chez vous peut organiser un évènement, que soit un goûter, un anniversaire, une promotion spéciale, un jeu concours etc..

			</p>

        <p>

			 Chaque commerçant est libre d'imaginer n'importe quelqu'évènement que se soit dans son magasin. Un commerçant peut très bien programmer un coupon de réduction depuis le site magasinducoin et le rendre disponible un jour bien précis ou à un créneau horaire de 2heures! Tout devient possible avec magasinducoin même l'impossible.

			</p>

        <p>

			 En se connectant régulièrement sur magasinducoin,	vous découvrirez une multitude de bonnes affaires, des bons de réductions, des évènements que vos magasins proposent tout au long de l'année et ça à deux pas de chez vous.

			</p>

        <p>

			 Soyez informé en temps réel en vous abonnant à la newsletter de vos commerçants. C'est très simple, trouver le commerçant que vous aimerez suivre et abonnez-vous à sa newsletter. A chaque fois que ce commerçant publiera un bon de réduction ou un évènement ou une promotion, vous serez immédiatement averti par mail.

			</p>

    </div>



</div>



<div class="clear"></div>



<div id="footer">

    <?php include("modules/footer.php"); ?>	

</div>



</body>

</html>

