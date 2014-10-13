<div style="float:left; text-align:left;">
	<div class="cart_inner" style="margin:10px !important;">
        <?php 
		//unset($_SESSION['coupons']);
		//unset($_SESSION['coupons']);
		if(isset($_SESSION['courses']) or isset($_SESSION['coupons']) or ($_SESSION['event'])){
			$nb_courses = count($_SESSION['courses']);
			echo "<span class='nombre'>$nb_courses</span> ". $xml->Produits_dans_liste_course;
			?><br />
		<?php 

			$nb_coupons = count($_SESSION['coupons']);
			echo "<span class='nombre'>$nb_coupons</span> ". $xml->coupon_de_reduction ." <br>";
			?>
            <?php 
			$nb_event = count($_SESSION['event']);
			echo "<span class='nombre'>$nb_event   </span>Événements(s) de reduction<br>";
			?>
            <div align="center" style="width:180px;"><br />
            <form action="imprimer.php" method="post" target="_blank" >
            	<input type="hidden" name="testing" id="testing" value="1"/>
                <input type="submit" value="<?php echo $xml->Imprimer_liste; ?>" class="btnOrange" style="width:125px;"/>
            </form><br />
            
            <!--<br /><a href="javascript:;" onclick="window.open ('imprimer.php', 'nom_interne_de_la_fenetre', config='height=700, width=1000, toolbar=no, menubar=no, scrollbars=yes, resizable=no, location=no, directories=no, status=no')" class="btnOrange" style="width:125px;"><?php echo $xml->Imprimer_liste; ?></a><br />-->
            <?php if(isset($_SESSION['kt_login_user'])) { ?>
             <a href="javascript:;" onclick="window.open ('envoyer_liste.php', 'envoyer_liste', config='height=300, width=400, toolbar=no, menubar=no, scrollbars=yes, resizable=no, location=no, directories=no, status=no')"  style="width:125px;" class="btnOrange"><?php echo $xml->Envoyer_liste; ?></a><br />
             <?php } ?>
			<a href="javascript:;" onClick="ajax('ajax/remiseazero.php?type=coupons','#carts');"  style="width:125px;" class="btnviolet">Remise &agrave; z&eacute;ro</a>
            </div>
            <?php
		} else {
			echo  $xml->Compteur_vide;
		}
?>
	</div>
</div>