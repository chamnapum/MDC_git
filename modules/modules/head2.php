	<style type="text/css">
		@import url(stylesheets/custom-bg.css);			/*link to CSS file where to change backgrounds of site headers */
		@import url(stylesheets/styles-light.css);		/*link to the main CSS file for light theme color */
		@import url(stylesheets/widgets-light.css);		/*link to the CSS file for widgets of light theme color */
		@import url(stylesheets/superfish.css);			/*link to the CSS file for superfish menu */
		@import url(stylesheets/tipsy.css);				/*link to the CSS file for tips */
		@import url(stylesheets/contact.css);				/*link to the CSS file for tips */
		@import url(stylesheets/simo.css);				/*link to the CSS file for tips */
	</style>
	<link rel="stylesheet" href="stylesheets/jquery.megamenu.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="stylesheets/example.css" type="text/css" media="screen" />
	<!-- Initialise jQuery Library -->
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript">
    </script>
    <script src="assets/js/jquery.megamenu.js" type="text/javascript"></script>
	<script type="text/javascript" src="assets/js/cufon/cufon-yui.js"></script>
	<script type="text/javascript" src="assets/js/cufon/ColaborateLight_400-Colaborate-Regular_400.font.js"></script>
	<script type="text/javascript">
		 jQuery(function(){
			jQuery(".megamenu").megamenu();
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
</script>

    

