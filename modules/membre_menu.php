<div style="float:left; width:200px; background:#F2EFEF; border-right:4px solid #E39BC7;">
<style>
.left_menu_use{
	float:left; 
	width:197px;
}
.left_menu_use a{
	width:185px;
	height:15px;
	float:left;
	padding: 10px 0px 10px 10px;
	font-size: 14px;
}
.left_menu_use a#profil:hover{
	color:#e39bc7;
}
.left_menu_use a#favoris:hover{
	color:#69164A;
}
.left_menu_use a#magasin{
	
}
.left_menu_use a#bons{
	
}
.left_menu_use a#produ{
	
}
.left_menu_use a#even{
	
}
.left_menu_use a#monabon{
	
}
.left_menu_use a#achpub{
	
}
.left_menu_use a#louer{
	
}
.left_menu_use a#proposer{
	
}
.left_menu_use a#journal{
	
}
</style>
<div>
	<?php /*?><ul style=" float:left; width:197px;">
        <li><a href="mon-profil.php" onMouseOver="changeCouleur('#e39bc7','profil');" id="profil" ><?php echo $xml->Mon_profil; ?></a></li>
        <li><a href="mes-favoris.php" onMouseOver="changeCouleur('#69164A','favoris');" id="favoris" ><?php echo $xml->Mes_favoris; ?></a></li>
        <?php if($_SESSION['kt_login_level'] == 1) { ?>
        <li><a href="mes-magazins.php" onMouseOver="changeCouleur('#9d216e','magasin');"  id="magasin" ><?php echo $xml->Mes_magasins; ?></a></li>
        <li><a href="mes-coupons.php" onMouseOver="changeCouleur('#f6ae30','coupons');"  id="bons"><?php echo $xml->Mes_coupons; ?></a></li>
		<!-- <li><a href="bons_achat.php" onMouseOver="changeCouleur('#f6ae30','bons');"  id="coupons"><?php echo $xml->Mes_bons_achat; ?></a></li>-->
        <li><a href="mes-produits.php" onMouseOver="changeCouleur('#b35a91','produ');"  id="produ"><?php echo $xml->Mes_produits; ?></a></li>
        <li><a href="mes-evenements.php" onMouseOver="changeCouleur('#ed8427','even');"  id="even"><?php echo $xml->mes_evenements; ?></a></li>
        <li><a href="mon-abonnement.php" onMouseOver="changeCouleur('#D37116','monabon');"  id="monabon"><?php echo $xml->Mon_abonnement; ?></a></li>
		<li><a href="mes-pubs.php" onMouseOver="changeCouleur('#69164A','achpub');"  id="achpub"><?php echo $xml->Acheter_publicite; ?></a></li>
		<li><a href="photographes.php" onMouseOver="changeCouleur('#c6b95e','louer');"  id="louer">Louer un photographe<?php //echo $xml->Louer_les_service_photographe; ?></a></li>
        <li><a href="proposer-cat.php" onMouseOver="changeCouleur('#9d216e','proposer');"  id="proposer"><?php echo $xml->Proposer_une_categorie; ?></a></li>
        <li><a href="ajouter-journal.php" onMouseOver="changeCouleur('#ed8427','journal');"  id="journal"><?php echo $xml->Ajouter_tous_journal; ?></a></li>
        <?php } ?>
	</ul><?php */?>

    <div class="left_menu_use">
        <a href="mon-profil.php" onMouseOver="changeCouleur('#e39bc7','profil');" id="profil" ><?php echo $xml->Mon_profil; ?></a>
        <a href="mes-favoris.php" onMouseOver="changeCouleur('#69164A','favoris');" id="favoris" ><?php echo $xml->Mes_favoris; ?></a>
        <a href="mes-sabonner.php" id="sabonner" >Mes abonnements</a>
        
        <?php if($_SESSION['kt_login_level'] == 1) { ?>
        <a href="mes-magazins.php" onMouseOver="changeCouleur('#9d216e','magasin');"  id="magasin" ><?php echo $xml->Mes_magasins; ?></a>
        <a href="mes-coupons.php" onMouseOver="changeCouleur('#f6ae30','coupons');"  id="bons"><?php echo $xml->Mes_coupons; ?></a>
        <a href="mes-produits.php" onMouseOver="changeCouleur('#b35a91','produ');"  id="produ"><?php echo $xml->Mes_produits; ?></a>
        <a href="mes-evenements.php" onMouseOver="changeCouleur('#ed8427','even');"  id="even"><?php echo $xml->mes_evenements; ?></a>
        <a href="mon-abonnement.php" onMouseOver="changeCouleur('#D37116','monabon');"  id="monabon">Crédit</a>
        <a href="photographes.php" onMouseOver="changeCouleur('#c6b95e','louer');"  id="louer">Louer un photographe</a></li>
        <a href="proposer-cat.php" onMouseOver="changeCouleur('#9d216e','proposer');"  id="proposer"><?php echo $xml->Proposer_une_categorie; ?></a></li>
        <?php } ?>
    </div>
	<!--<div id="bordure"></div>-->
	<div class="clear"></div>
</div>
      <br>
<br>

</div>