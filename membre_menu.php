  <div id="tabs">
      <ul style=" float:left; width:197px;">
        <li><a href="mon-profil.php" onMouseOver="changeCouleur('#e39bc7','profil');" id="profil" >Mon profil</a></li>
        <?php if($_SESSION['kt_login_level'] == 1) { ?>
        <li><a href="mes-magazins.php" onMouseOver="changeCouleur('#9d216e','magasin');"  id="magasin" >Mes magasins</a></li>
        <li><a href="mes-coupons.php" onMouseOver="changeCouleur('#f6ae30','coupons');"  id="coupons">Mes coupons</a></li>
        <li><a href="mes-produits.php" onMouseOver="changeCouleur('#b35a91','produ');"  id="produ">Mes produits</a></li>
        <li><a href="calandrier.php" onMouseOver="changeCouleur('#ed8427','even');"  id="even">Mes &eacute;vénements</a></li>
        <li><a href="mes-pubs.php" onMouseOver="changeCouleur('#69164A','achpub');"  id="achpub">Acheter de la publicité</a></li>
        <li><a href="mon-abonnement.php" onMouseOver="changeCouleur('#D37116','monabon');"  id="monabon">Mon abonnement</a></li>
        <li><a href="photographes.php" onMouseOver="changeCouleur('#c6b95e','louer');"  id="louer">Louer les service d'un photographe</a></li>
        <li><a href="proposer-cat.php" onMouseOver="changeCouleur('#9d216e','proposer');"  id="proposer">Proposer une catégorie</a></li>
        <?php } ?>
      </ul>
      <div id="bordure"></div>
      <div class="clear"></div>
      </div>
      <br>
<br>
<script>
<?php if(strpos($_SERVER['PHP_SELF'],'mon-profil.php') !== FALSE) echo "changeCouleur('#e39bc7','profil');"; ?>
<?php if(strpos($_SERVER['PHP_SELF'],'mes-magazins.php') !== FALSE or strpos($_SERVER['PHP_SELF'],'magasinForm.php') !== FALSE ) echo "changeCouleur('#9d216e','magasin');"; ?>
<?php if(strpos($_SERVER['PHP_SELF'],'mes-coupons.php') !== FALSE or strpos($_SERVER['PHP_SELF'],'formCoupon.php') !== FALSE) echo "changeCouleur('#f6ae30','coupons');"; ?>
<?php if(strpos($_SERVER['PHP_SELF'],'mes-produits.php') !== FALSE) echo "changeCouleur('#b35a91','produ');"; ?>
<?php if(strpos($_SERVER['PHP_SELF'],'calandrier.php') !== FALSE) echo "changeCouleur('#ed8427','even');"; ?>
<?php if(strpos($_SERVER['PHP_SELF'],'photographes.php') !== FALSE) echo "changeCouleur('#c6b95e','louer');"; ?>
<?php if(strpos($_SERVER['PHP_SELF'],'mes-pubs.php') !== FALSE) echo "changeCouleur('#69164A','achpub');"; ?>
<?php if(strpos($_SERVER['PHP_SELF'],'proposer-cat.php') !== FALSE) echo "changeCouleur('#9d216e','proposer');"; ?>
</script>