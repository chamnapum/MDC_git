<?php require_once('Connections/magazinducoin.php'); ?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 

	<title>Magasinducoin | Recrutement</title>

    



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

	.lister p{

		font-size:14px;

		text-align:justify;

		margin:0px 20px 0px 20px;

	}

	.tit{

		color:#9D216E;

		font-weight:bold;

	}

	.bull{

		font-size:24px;

		color:#9D216E;

	}

	.gt{

		font-size:12px;

		font-weight:bold;

		color:#F6AE30;

		margin-left: 20px;

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

        <h3>Conditions d'utilisation</h3>

		<p>

            <span class="bull">&bull;</span> Magasinducoin recrute sans cesse de nouveaux talents. Si vous pensez que votre talent peut aider dans la conception et stratégie de magasinducoin, votre curriculum vitae est le bienvenue. 

			Pour se faire n'hésitez pas à nous envoyer une lettre de motivation et votre c.v à l'adresse: recrutement@magasinducoin.fr 

       </p>

    </div>



</div>



  	 
<div class="clear"></div>



<div id="footer">

    <?php include("modules/footer.php"); ?>	

</div>



</body>

</html>

