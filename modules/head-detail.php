<style type="text/css">
	@import url(template/css/style.css);	
</style> 

<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>    
<script src="template/js/jquery.js" type="text/javascript"></script>
<script type="text/javascript" src="template/js/jquery.jcarousel.min.js"></script>
<script src="assets/js/jquery.bxSlider.min.js" type="text/javascript"></script>
<script src="assets/js/jquery.bxSlider.js" type="text/javascript"></script>
    
<link rel="stylesheet" type="text/css" href="assets/css/menu.css" />

<script type="text/javascript">
	$(document).ready(function()
	{
		 $("html,body").animate({scrollTop: 0}, 1000);
	});
	
  $(document).ready(function(){
    $('#slider1').bxSlider();
	  $('#slider3').bxSlider();
	  $('#slider2').bxSlider();
	  $('#slider5').bxSlider();
	  
	  
	$('#slider6').bxSlider();
	$('#slider7').bxSlider();
		
  });

jQuery(document).ready(function() {
	jQuery('#mycarousel').jcarousel({
		auto: 3,
		wrap: 'last',
		visible: 2,
		/*initCallback: mycarousel_initCallback*/
	});
}); 
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
	 <?php if($_SESSION['kt_login_level'] == 1) { ?>
	 initColors('magasin');
	 initColors('coupons');
	 initColors('produ');
	 initColors('even');
	 initColors('louer');
	 initColors('achpub');
	 initColors('monabon');
	 initColors('proposer');
	  <?php } ?>
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

<style type="text/css">
.hover-2{
	background:#9D216E;
	color:#FFF;
}
</style>

<script src="assets/ui/jquery.ui.core.js" type="text/javascript"></script>
<script src="assets/ui/jquery.ui.widget.js" type="text/javascript"></script>
<script src="assets/ui/jquery.ui.tabs.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function(){

	$( "#level" ).change(function() {
		//$( "#target" ).submit();
		var level = $("#level").val();
		document.location.href = 'inscriptionu_all.php?level='+level; //relative to domain
	});
	
	menu_date_load();

	function menu_date_load(){
		var region=<?php echo $default_region;?>;
		//alert(id);
		var dataString = 'region='+region;
		$.ajax({
				type: "POST",
				url: "assets/menu/main-menu-date.php",
				data: dataString,
				cache: false,
				success: function(datas){
					$("#menu_date").html(datas);
				}
			});	
		return false;
	}
	
	

	$(function() {
		$( "#tabs" ).tabs();
	});
});
</script>