<?php
mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset1 = "SELECT coupons.sous_categorie AS sous_categorie__id, category_0.cat_name AS categorie, coupons.reduction, coupons.date_debut, coupons.date_fin, coupons.titre, coupons.min_achat, coupons.id_magasin, magazins.nom_magazin, magazins.ville, magazins.photo1, category.cat_name AS sous_categorie, coupons.categories AS categorie_id, coupons.id_coupon
FROM (((coupons
LEFT JOIN category ON category.cat_id=coupons.sous_categorie)
LEFT JOIN magazins ON magazins.id_magazin=coupons.id_magasin)
LEFT JOIN category AS category_0 ON category_0.cat_id=coupons.categories) WHERE magazins.region = ".$_SESSION['region']." AND coupons.date_debut <= '".date('Y-m-d')."' AND coupons.date_fin >= '".date('Y-m-d')."'";
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$row_coupons = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>

<h3><?php echo $xml->coupons_reduction; ?></h3>
<div class="liste_reductions" style="width:100%;">
<?php if($totalRows_Recordset1) { ?>       
	<script type="text/javascript">
 
//Width
var sliderwidth="1000px"
 
//Height
var sliderheight="88px"
 
//Speed (1=slowest, 10=fastest)
var slidespeed=1
 
//Background color:
slidebgcolor="#F2EFEF"
 
//Images
var leftrightslide=new Array()
var finalslide=''
 
 <?php $i= 0; do { ?>
leftrightslide[<?php echo $i; $i++;?>] = '<div class="reduction-item"><div class="titre"><?php
				  echo $row_coupons['min_achat'] > 0 ? 
				"<a href=\"rechercher_cpn.php?adresse=&rayon=999&mot_cle=&categorie=&sous_categorie=&id_coupon=".$row_coupons['id_coupon']."\" target=\"_top\"><strong>".$row_coupons['nom_magazin']."</strong><br>R&eacute;duction de <strong>".$row_coupons['reduction']."%</strong><br>pour un minimum <br>d\'achat de ".$row_coupons['min_achat']." &euro;<br>&agrave; <strong>".getVilleById($row_coupons['ville'])."</strong>" :
				"<a href=\"rechercher_cpn.php?adresse=&rayon=999&mot_cle=&id_coupon=".$row_coupons['id_coupon']."&categorie=".$row_coupons['categorie_id']."&sous_categorie=".$row_coupons['sous_categorie__id']."\" target=\"_top\"><strong>".$row_coupons['nom_magazin']."</strong><br>R&eacute;duction de <strong>".$row_coupons['reduction']."%</strong> sur<br />tous les produits <br />de ".($row_coupons['categorie'])."<br>&agrave; <strong>".getVilleById($row_coupons['ville'])."</strong>";  ?></a></div><div class="img"><img src="timthumb.php?src=assets/images/magasins/<?php echo $row_coupons['photo1'] ?>&w=80&h=65&z=1" width="80" height="65" alt="<?php echo ($row_coupons['nom_magazin']) ?>" /></div></div>'
 
 <?php } while ($row_coupons = mysql_fetch_assoc($Recordset1)); ?>
//Gap between images
var imagegap=" "
 
//Gap between eaxh rotation (pixel) 
var slideshowgap=1
 
var copyspeed=slidespeed
leftrightslide='<nobr>'+leftrightslide.join(imagegap)+'</nobr>'
var iedom=document.all||document.getElementById
if (iedom)
document.write('<span id="temp" style="visibility:hidden;position:absolute;top:-100px;left:-9000px">'+leftrightslide+'</span>')
var actualwidth=''
var cross_slide, ns_slide
 
function fillup(){
	if (iedom){
		cross_slide=document.getElementById? document.getElementById("test2") : document.all.test2
		cross_slide2=document.getElementById? document.getElementById("test3") : document.all.test3
		cross_slide.innerHTML=cross_slide2.innerHTML=leftrightslide
		actualwidth=document.all? cross_slide.offsetWidth : document.getElementById("temp").offsetWidth
		cross_slide2.style.left=actualwidth+slideshowgap+"px"
	}
	else if (document.layers){
		ns_slide=document.ns_slidemenu.document.ns_slidemenu2
		ns_slide2=document.ns_slidemenu.document.ns_slidemenu3
		ns_slide.document.write(leftrightslide)
		ns_slide.document.close()
		actualwidth=ns_slide.document.width
		ns_slide2.left=actualwidth+slideshowgap
		ns_slide2.document.write(leftrightslide)
		ns_slide2.document.close()
	}
	lefttime=setInterval("slideleft()",30)
}
window.onload=fillup
 
function slideleft(){
	if (iedom){
		if (parseInt(cross_slide.style.left)>(actualwidth*(-1)+8))
			cross_slide.style.left=parseInt(cross_slide.style.left)-copyspeed+"px"
		else
			cross_slide.style.left=parseInt(cross_slide2.style.left)+actualwidth+slideshowgap+"px"
		 
		if (parseInt(cross_slide2.style.left)>(actualwidth*(-1)+8))
			cross_slide2.style.left=parseInt(cross_slide2.style.left)-copyspeed+"px"
		else
			cross_slide2.style.left=parseInt(cross_slide.style.left)+actualwidth+slideshowgap+"px" 
	}
	else if (document.layers){
		if (ns_slide.left>(actualwidth*(-1)+8))
			ns_slide.left-=copyspeed
		else
			ns_slide.left=ns_slide2.left+actualwidth+slideshowgap
		 
		if (ns_slide2.left>(actualwidth*(-1)+8))
			ns_slide2.left-=copyspeed
		else
			ns_slide2.left=ns_slide.left+actualwidth+slideshowgap
	}
}
 
if (iedom||document.layers){
	with (document){
		document.write('<table border="0" cellspacing="0" cellpadding="0" style="width:100%;"><td>')
		if (iedom){
			write('<div style="position:relative;width:'+sliderwidth+';height:'+sliderheight+';overflow:hidden">')
			write('<div style="position:absolute;width:'+sliderwidth+';height:'+sliderheight+';background-color:'+slidebgcolor+'" onMouseover="copyspeed=0" onMouseout="copyspeed=slidespeed">')
			write('<div id="test2" style="position:absolute;left:0px;top:0px"></div>')
			write('<div id="test3" style="position:absolute;left:-1000px;top:0px"></div>')
			write('</div></div>')
		}
		else if (document.layers){
			write('<ilayer width='+sliderwidth+' height='+sliderheight+' name="ns_slidemenu" bgColor='+slidebgcolor+'>')
			write('<layer name="ns_slidemenu2" left=0 top=0 onMouseover="copyspeed=0" onMouseout="copyspeed=slidespeed"></layer>')
			write('<layer name="ns_slidemenu3" left=0 top=0 onMouseover="copyspeed=0" onMouseout="copyspeed=slidespeed"></layer>')
			write('</ilayer>')
		}
		document.write('</td></table>')
	}
}
</script>
         <?php } ?>
</div>