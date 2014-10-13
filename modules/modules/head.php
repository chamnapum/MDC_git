	<style type="text/css">
		@import url(template/css/style.css);	
		@import url(template/css/menu.css);			/*link to CSS file where to change backgrounds of site headers */
	</style> 

	<script src="http://code.jquery.com/jquery-latest.js" type="text/javascript"></script>
  	<script src="template/js/jquery.js" type="text/javascript"></script>
	<script type="text/javascript" src="assets/iframe/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
	<script type="text/javascript" src="assets/iframe/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
    <script type="text/javascript" src="template/js/jquery.jcarousel.min.js"></script>
	<link rel="stylesheet" type="text/css" href="template/skin/skin.css" />
	<link rel="stylesheet" type="text/css" href="assets/iframe/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
	<script src="assets/js/jquery.bxSlider.min.js" type="text/javascript"></script>
    <script src="assets/js/jquery.bxSlider.js" type="text/javascript"></script>

<script type="text/javascript">
  $(document).ready(function(){
    $('#slider1').bxSlider();
	  $('#slider3').bxSlider();
	  $('#slider2').bxSlider();
	  $('#slider5').bxSlider();
		
  });
</script>
	<script type="text/javascript">
		$(document).ready(function() {
			$(".various3").fancybox({
				'width'				: '100%',
				'height'			: '100%',
				'autoScale'			: true,
				'transitionIn'		: 'elastic',
				'transitionOut'		: 'none',
				'type'				: 'iframe',
				'speedIn'           : 700
			});
		});
		
	</script>
    <script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#mycarousel').jcarousel({
		auto: 3,
		wrap: 'last',
		visible: 2,
		/*initCallback: mycarousel_initCallback*/
	});
}); 
</script>
	<script type="text/javascript">
		function ajax(murl,mresult){
			$(mresult).addClass("en_cours");
			$.ajax({
				  url: murl,
				  cache: false,
				  success: function(html){
					$(mresult).html(html);
					$(mresult).removeClass("en_cours");
				  }
				});
			}
		function afficher_menu(menu){
			$('#menu_produits').removeClass('active');
			$('#menu_coupons').removeClass('active');
			$('#menu_events').removeClass('active');
			$('#menu_promos').removeClass('active');
			$('#menu_'+menu).addClass('active');
			
		}
		
  function changeCouleur(nouvelleCouleur,element) { 
 	 initColors('profil');
	 initColors('magasin');
	 initColors('coupons');
	 initColors('produ');
	 initColors('even');
	 initColors('louer');
	 initColors('achpub');
	 initColors('monabon');
	 elem = document.getElementById("bordure");  
	 elem.style.backgroundColor = nouvelleCouleur;
	 elem2 = document.getElementById(element);  
	 elem2.style.backgroundColor = nouvelleCouleur;
	 elem2.style.color = '#FFF';
 } 
 
 function initColors(element){
 	 elem2 = document.getElementById(element);  
	 elem2.style.backgroundColor = 'transparent'; 
	 elem2.style.color='#000';
 }
		
</script>



