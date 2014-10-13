<div id="header">
    	<div class="inner">
        	<div class="logo"><h1><a href="index.php" title="Magasin du coin"><img src="template/images/logo.png" alt="Logo - Magasin du coin" /></a></h1><div id="region">
			<?php if(strpos($_SERVER['PHP_SELF'],'index.php') == FALSE)
			 { echo getRegionById($default_region); } ?> 
            
            </div>
            
            </div>
            <div class="pub"><script type="text/javascript"><!--
google_ad_client = "ca-pub-0562242258908269";
/* espace membre */
google_ad_slot = "4070180649";
google_ad_width = 468;
google_ad_height = 60;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script></div>
            <div class="menu1">
            	<ul>
                	<li><a href="region.php"><img src="template/images/home_icon.gif" alt="Accueil" /></a></li>
                    <?php if( isset($_SESSION['kt_login_id'])) { ?>
                        <li class="button"><a href="membre.php">Espace membre</a></li>
                        <li class="button"><a href="logout.php">D&eacute;connexion</a></li>
                     <?php } else { ?> 
                        <li class="button"><a href="choix_inscription.php">S'inscrire</a></li>  
                        <li class="button"><a href="authetification.php">Se connecter</a></li>
                     <?php } ?>
                </ul>
            </div>
            <div class="slogan"><h2>A cot&eacute; de chez vous</h2></div>
            <div class="menu2">
            	<ul>
                	<li><a href="#">Le mag</a></li>
                    <li><a href="#">1ers prix </a></li>
                    <li><a href="#">Marques</a></li>
                    <li><a href="#">Services</a></li>
                    <li class="last"><a href="#">Site Pro</a></li>
                </ul>
            </div>
        </div>
</div>