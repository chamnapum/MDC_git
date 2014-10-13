<div id="cart">
        <h3>Compteur de réductions</h3>
        <div class="cart_inner">
		<?php 
		//unset($_SESSION['coupons']);
		if(isset($_SESSION['coupons'])){
			$nb_coupons = count($_SESSION['coupons']);
			echo "<span class='nombre'>$nb_coupons</span> coupon(s) de réduction <br>";
			?>
			<br /><a href="javascript:;" onclick="window.open ('imprimer.php', 'nom_interne_de_la_fenetre', config='height=700, width=1000, toolbar=no, menubar=no, scrollbars=yes, resizable=no, location=no, directories=no, status=no')" class="btnOrange">Imprimer les coupons</a><br />
			<a href="javascript:;" onClick="ajax('ajax/remiseazero.php','#cart');"  class="btnviolet">Remise &agrave; z&eacute;ro</a>
            <?php
		} else {
			echo "Compteur vide!";
		}
?>
</div>
</div>