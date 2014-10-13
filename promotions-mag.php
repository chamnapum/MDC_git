<?php require_once('Connections/magazinducoin.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 

	<title> Magasinducoin | Promotion produits</title>

    



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

    <?php /*?><?php include("modules/form_recherche_header.php"); ?>

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

        <h3>Promotions dans vos commerces de proximité</h3>

         	<p>

            <h1>Magasinducoin propose à tous moments des centaines de promotions issu du commerce de proximité</h1>

			</p>

        <p>

			 Internet est devenu le premier endroit pour faire des bonnes affaires et trouver les meilleurs promotions. Magasinducoin vous propose de venir à la découverte des commerçants proche de chez vous qui sont capables de fournir des centaines de promotions sur leurs produits.

			 </p>

        <p>Chaque commerçant dispose d'un outil sur le site magasinducoin permettant de produire des promotions sur leur articles en seulement quelques clics de souris, qui permet au commerçant d'attirer plus de clients dans leur boutique et ne pas se limiter au simple passant qui passe devant le magasin.

			</p>

        <p> Grâce aux promotions, les commerçants peuvent attirer beaucoup plus de monde grâce à internet. En se connectant régulièrement sur magasinducoin,	vous découvrirez une multitude de bonnes affaires, des bons de réductions, des évènements que vos magasin proposent tout au long de l'année et ça à deux pas de chez vous.

			</p>

        <p> Soyez informé en temps réel en vous abonnant à la newsletter de vos commerçants. C'est très simple, trouver le commerçant que vous aimerez suivre et abonnez-vous à sa newsletter. A chaque fois que ce commerçant publiera un bon de réduction ou un évènement ou une promotion, vous serez immédiatement averti par mail.

			</p>

    </div>



</div>



<div class="clear"></div>



<div id="footer">

    <?php include("modules/footer.php"); ?>	

</div>





</body>

</html>

