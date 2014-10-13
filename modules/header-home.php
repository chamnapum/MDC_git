<!-- Start Top -->
<div class="top_full">
	<div id="top">
		<a href="index.php" class="logo">Magasin Du Coin</a>
        <div style="float:right; margin-top:20px">
        <ul class="icon_haut">
        <?php if( isset($_SESSION['kt_login_id'])) { ?>
        	<li class="bonjour"><?php echo $xml->bjr; ?> <?php echo $_SESSION['kt_prenom']; ?></li>
        	<li class="espace_membre"><a href="membre.php"><?php echo $xml->Espace_membre; ?></a></li>
            <li class="deconnect"><a href="logout.php"><?php echo $xml->Deconnexion; ?></a></li>
         <?php } else { ?> 
         	<li class="inscription"><a href="choix_inscription.php"><?php echo $xml->inscription; ?></a></li>  
            <li class="login"><a href="authetification.php"><?php echo $xml->connexion; ?></a></li>
         <?php } ?>
        </ul>
        </div>
	  <div class="clear"></div>
	</div>
</div>
<!-- End Top -->