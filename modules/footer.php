<div class="page_footer">
        <div class="footer_blog">
        	<div class="title_blog">MAGASINDUCOIN</div>
            <ul>
               	  <li><a title="Qui sommes-nous ?" href="qui-sommes-nous.html">Qui sommes-nous?</a></li>
                  <li><a title="Tout savoir" href="bref.html">Tout savoir</a></li>
				  <li><a title="Code de réduction - Code promo" href="bon_reduction.html">Code de réduction - Code promo </a></li>
				  <li><a title="Evènements magasin de proximité" href="event.html">Evènements magasin de proximité</a></li>
				  <li><a title="Promotions dans vos magasins" href="promotions-mag.html">Promotions dans vos magasins</a></li>
                  <li><a title="Comment ça marche ?" href="comment-ca-marche.html">Comment ça marche ?</a></li>
				  <li><a title="Foire aux questions" href="question-reponse-prf.html">Foire aux questions </a></li>
				  <li><a title="Contactez-nous" href="contact.html">Contactez-nous</a></li>
                  <li><a title="Besoin d'aide ?" href="contact-form-3.html">Besoin d'aide ?</a></li>
            </ul>
        </div>
        <div class="footer_blog border_blog">
        	<div class="title_blog"><?php echo strtoupper($xml-> Villes_principales); ?></div>
            	<a title="Ajaccio" href="7-Corse.html">Ajaccio</a> 
				<a title="Angers" href="18-Pays-de-la-Loire.html">Angers</a> 
				<a title="Bordeaux" href="1-Aquitaine.html">Bordeaux</a> 
                <a title="Brest" href="4-Bretagne.html">Brest</a>
                <a title="Clermont-Ferrand" href="2-Auvergne.html">Clermont-Ferrand</a> 
                <a title="Dijon" href="3-Bourgogne.html">Dijon</a> 
				<a title="Grenoble" href="22-Rhone-Alpes.html">Grenoble</a>
                <a title="Le Havre" href="17-Haute-Normandie.html">Le Havre </a>
                <a title="Lille" href="15-Nord-Pas-de-Calais.html">Lille </a>
			    <a title="Limoges" href="12-Limousin.html">Limoges </a>
                <a title="Lyon" href="22-Rhone-Alpes.html">Lyon</a> 
                <a title="Marseille" href="21-Provence-Alpes-Cote-dazur.html">Marseille</a> 
                <a title="Metz" href="13-Lorraine.html">Metz </a>
                <a title="Montpellier" href="11-Languedoc-Roussillon.html">Montpellier</a> 
                <a title="Nancy" href="13-Lorraine.html">Nancy</a> 
                <a title="Nantes" href="18-Pays-de-la-Loire.html">Nantes</a> 
                <a title="Nice" href="21-Provence-Alpes-Cote-dazur.html">Nice</a> 
                <a title="Paris" href="10-Ile-de-France.html">Paris</a>
                <a title="Reims" href="6-Champagne-Ardenne.html">Reims</a> 
                <a title="Rennes" href="4-Bretagne.html">Rennes</a> 
                <a title="Rouen" href="17-Haute-Normandie.html">Rouen</a> 
                <a title="Strasbourg" href="23-Alsace.html">Strasbourg</a> 
                <a title="Toulon" href="14-Midi-Pyrenees.html">Toulon</a> 
                <a title="Toulouse" href="14-Midi-Pyrenees.html">Toulouse</a> 
        </div>
        <div class="footer_blog">
        	<div class="title_blog"><?php echo strtoupper($xml->Powered_by); ?></div>
            <img src="template/images/1c.png" width="76" height="26" alt="<?php echo strtoupper($xml->Powered_by); ?>" /><br /><br />
        	<div class="title_blog"><?php echo strtoupper('Paiment securise'); ?></div>
            <div id="sprites_footer"></div>
<?php /*?>            <!--<img src="template/images/visa.png" width="42" height="27" alt=""/> 
            <img src="template/images/moneycard.png"  width="41" height="26" alt=""/> 
            <img src="template/images/ax.png"  width="63" height="27" alt=""/>
            <img src="template/images/c4.png"  width="40" height="26" alt=""/>
            <img src="template/images/c5.png"  width="41" height="26" alt=""/>
            <img src="template/images/paypal.png"  width="38" height="25" alt=""/>--><?php */?><br />
            <div style="border-bottom:1px dotted #999999;"></div><br />
<?php /*?>            <!--<div class="title_blog"><?php echo strtoupper('Contactez-nous');?></div>
            <p style="line-height:20px;">
            Téléphone: xxx - xxx - xxx<br />
            Email: example@gmail.com<br />
            Webiste: www.magasinducoin.com
            </p>--><?php */?>
        </div>
        <div class="footer-bottom">
        <p style="color:#666666">Grâce au site magasinducoin.com, trouvez des <strong>coupons de réductions</strong>, des produits et des <em>évènements à proximité de chez vous</em>. Vous pouvez trouver des <strong><em>codes promotions</em></strong> pour aller au restaurant, chez le coiffeur, chez votre boulanger, votre boucher et même pour acheter des fleurs. Tous les commerçants de France peuvent <strong>publier gratuitement</strong> des <strong>bons plans</strong> et évènements pour leur magasin. Le <span style="text-decoration:underline">commerce de proximité</span> à valeur ajouté est en marche !</p>
       	  <div style="border-top: 1px dotted #666666; padding-top:10px; margin-top:10px">
            <a title="<?php echo $xml->engagements; ?>" href="engagement.html"><?php echo $xml->engagements; ?></a>
            <a title="<?php echo $xml->inscription; ?>" href="inscription.php"><?php echo $xml->inscription; ?></a>
            <a title="<?php echo $xml->Conditions_utilisation; ?>" href="conditions_utilisation.html"><?php echo $xml->Conditions_utilisation; ?></a>
            <a title="<?php echo $xml->Recrutement; ?>" href="Recrutement.html"><?php echo $xml->Recrutement; ?></a>
            <a title="Tout savoir" href="bref.html">Tout savoir</a>
            <a title="<?php echo $xml->Mentions_legales; ?>" href="assets/images/ML.pdf"><?php echo $xml->Mentions_legales; ?></a>
            <a title="Code de réduction" href="bon_reduction.html">Code de réduction</a>
            <a title="Code promo" href="bon_reduction.html">Code promo</a>
            <a title="Publicité" href="publicite.html">Publicité</a>
            </div>
            <div>
			<a title="<?php echo $xml->Protections_personnelles ; ?>" href="protectiondn.html"><?php echo $xml->Protections_personnelles ; ?></a>
            <a title="Foire aux questions" href="question-reponse-prf.html">F.A.Q</a>
        	</div>
            <br />
            <p style="color:#9d276d" >
            <b>Copyright &copy; 2013 </b>
            </p>
        </div>
    </div>
    
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-22747128-5', 'auto');
  ga('send', 'pageview');

</script>