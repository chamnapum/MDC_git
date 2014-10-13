<?php require_once('Connections/magazinducoin.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 
	<title>Magasinducoin | Tout savoir </title>
    

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
		margin:0px 20px 10px 20px;
	}
    </style>
    <?php include("modules/form_recherche_header.php"); ?>
    <div class="top reduit">
        <div id="head-menu" style="float:left;">
        	<?php include("assets/menu/main-menu.php"); ?>
        </div>
		<div id="url-menu" style="float:left;">
        <?php include("assets/menu/url_menu.php"); ?>
        </div>
    </div>
    
    <div class="clear"></div>

    <div class="lister top" style="float:left; background:#F2EFEF; width:100%;">
        <div style="font-size:550px; color:#CCC; text-align:center;">
        	404
        </div>
    </div>

</div>

<div class="clear"></div>

<div id="footer">
    <?php include("modules/footer.php"); ?>	
</div>

</body>
</html>
