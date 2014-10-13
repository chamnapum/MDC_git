<link rel="stylesheet" type="text/css" href="template/css/style.css" /> 
<link rel="stylesheet" type="text/css" href="assets/css/menu.css" />  
<link href="assets/select/select2.css" rel="stylesheet"/>
    
<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>    


<script src="assets/select/select2.js"></script>
<script src="assets/select/select2_locale_fr.js"></script>


<script type="text/javascript">
$(document).ready(function()
{
	$("html,body").animate({scrollTop: 0}, 1000);

}); 
function ajax(murl,mresult){
	$(mresult).addClass("en_cours");
	$(".en_cours").html('<img src="assets/images/chargement.gif" alt="" />');
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
		document.location.href = 'inscription.php?level='+level; //relative to domain
	});

	$(function() {
		$( "#tabs" ).tabs();
	});
});
</script>