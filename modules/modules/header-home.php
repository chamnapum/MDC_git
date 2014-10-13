<!-- Start Top -->
<div class="top_full">
	<div id="top">
		<a href="index.php" class="logo">Logo Magasin Du Coin</a>
        <div style="float:right; margin-top:20px">
        <ul class="icon_haut">
        <?php if( isset($_SESSION['kt_login_id'])) { ?>
        	<li class="bonjour">Bonjour <?php echo $_SESSION['kt_prenom']; ?></li>
        	<li class="espace_membre"><a href="membre.php">Espace membre</a></li>
            <li class="deconnect"><a href="logout.php">Déconnexion</a></li>
         <?php } else { ?> 
         	<li class="inscription"><a href="choix_inscription.php">S'inscrire</a></li>  
            <li class="login"><a href="authetification.php">Se connecter</a></li>
         <?php } ?>
        </ul>
        </div>
	  <div class="clear"></div>
	</div>
</div>
<!-- End Top -->