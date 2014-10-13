function preload_image(a){var b=new Image;b.src=a}
	function change_image(d,c){
		var a=document.getElementById("area_image");
		var b=document.getElementById("county_"+c);
		//var x=document.getElementById("li_"+c);
		a.style.backgroundImage="url("+d+"/assets/maphome/map/map_"+c+".png)";
		//b.style.textDecoration="underline";
		b.style.color="#F6AE30";
		//x.style.backgroundImage="url("+d+"/assets/images/flech_orange.png)";
		return true
		}
	function hide_image(d,c){
		var a=document.getElementById("area_image");
		var b=document.getElementById("county_"+c);
		//var x=document.getElementById("li_"+c);
		a.style.backgroundImage="url("+d+"/assets/maphome/map/none.gif)";
		//b.style.textDecoration="none";
		b.style.color="#999999";
		//x.style.backgroundImage="url("+d+"/assets/images/flech.png)";
		return true
	}
	function add_bookmark(){var b=navigator.userAgent.toLowerCase();var a=(b.indexOf("konqueror")!=-1);var c=(b.indexOf("webkit")!=-1);var e=(b.indexOf("mac")!=-1);var d=e?"Command/Cmd":"CTRL";if(window.external&&(!document.createTextNode||(typeof(window.external.AddFavorite)=="unknown"))){window.external.AddFavorite("http://www.leboncoin.fr/","Petites annonces gratuites d'occasion - leboncoin.fr")}else{if(a){alert("Veuillez appuyer sur CTRL + B pour ajouter ce site à vos favoris.")}else{if(window.opera){void (0)}else{if(window.home||c){alert("Veuillez appuyer sur "+d+" + D pour ajouter ce site à vos favoris.")}else{if(!window.print||e){alert("Veuillez appuyer sur Command/Cmd + D pour ajouter ce site à vos favoris.")}else{alert("Votre navigateur internet n'étant pas reconnu, vous devrez ajouter ce site manuellement à vos favoris.")}}}}}}
	function hasClass(b,a){return b.className.match(new RegExp("(\\s|^)"+a+"(\\s|$)"))}function addClass(b,a){if(!this.hasClass(b,a)){b.className+=" "+a}}
	function removeClass(c,a){if(hasClass(c,a)){var b=new RegExp("(\\s|^)"+a+"(\\s|$)");c.className=c.className.replace(b,"")}}
	function show_account_submenu(){var a=document.getElementById("nav_main");var c="account_submenu";var b=document.getElementById(c);if(b){if(b.style.display=="none"||b.style.display==""){addClass(a,"account_on");b.style.display="block"}else{removeClass(a,"account_on");b.style.display="none"}}return false}var current_screen="screen_form";function show_account_screen(c){var a=document.getElementById(c);var b=document.getElementById(current_screen);switch(current_screen){case"pass_lost_f":document.forms.passlostform.reset();break;default:document.forms.loginform.reset();break}b.style.display="none";a.style.display="block";current_screen=c}function toggle_blocks_display(d,c){var b=document.getElementById(d);var a=document.getElementById(c);if(b.style.display=="none"){b.style.display="block"}else{b.style.display="none"}if(a.style.display=="none"){a.style.display="block"}else{a.style.display="none"}};