<?php require_once('Connections/magazinducoin.php'); ?>

<?php 
require "class/php_cat.class.php";
$params = array(
'separator'=> '&nbsp; > &nbsp;',
'area' => 'client', //or admin
'seo' => false
);

$phpcat = new php_cat($params);
$map_result = $phpcat->map();
?>

<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
mysql_select_db($database_magazinducoin, $magazinducoin);
$query_faurchette_prix = "SELECT produits.prix2
FROM (produits
LEFT JOIN magazins ON magazins.id_magazin=produits.id_magazin)
WHERE magazins.region = ".$default_region." AND produits.en_stock = 1
ORDER BY produits.prix2 ASC";
$faurchette_prix = mysql_query($query_faurchette_prix, $magazinducoin) or die(mysql_error());
$row_faurchette_prix = mysql_fetch_assoc($faurchette_prix);
$totalRows_faurchette_prix = mysql_num_rows($faurchette_prix);

$min_prix = $row_faurchette_prix['prix2'];
while($row_faurchette_prix = mysql_fetch_assoc($faurchette_prix))
	$max_prix = $row_faurchette_prix['prix2'];


?>
<script>
//<![CDATA[
var region = <?php echo $default_region ?>;
var prixMax = <?php echo $max_prix ?>;
var prixMin = <?php echo $min_prix ?>;
var rayon   = '<?php echo isset($_GET['rayon']) ? $_GET['rayon'] : "300"; ?>';
var adresse = '<?php 

if(isset($_GET['adresse']) and !empty($_GET['adresse'])){
	echo $_GET['adresse'];
}
else if(isset($_SESSION['kt_adresse']))
	echo $_SESSION['kt_adresse'];
else
	echo "Entrer votre adresse"; ?>';

var autre_criteres = '<?php 
echo isset($_GET['categorie'])		? "&amp;categorie=".$_GET['categorie'] : "";
echo isset($_GET['sous_categorie'])? "&amp;sous_categorie=".$_GET['sous_categorie'] : "";
echo ($_GET['mot_cle']) ? "&amp;mot_cle=".$_GET['mot_cle'] : "";
?>';

function sendAjax(){
	ajax('ajax/resultat_recherche.php?region='+region+'&amp;prixMax='+prixMax+'&amp;prixMin='+prixMin+'&amp;rayon='+rayon+'&amp;adresse='+adresse+autre_criteres,'#result');
}

function sendNoAjax(){
	window.location = 'rechercher.php?region='+region+'&amp;prixMax='+prixMax+'&amp;prixMin='+prixMin+'&amp;rayon='+rayon+'&amp;adresse='+adresse+autre_criteres;
}

function filtrerok(){
if(document.getElementById('adresse').value == 'Entrer votre adresse' && adresse == 'Entrer votre adresse') {  alert('Vous devez remplir le champ adresse!!'); } else { rayon=document.getElementById('kilometrage').value; sendAjax(); }
}
//]]>
</script>
<div style="float:left; width:220px">
	<div class="widget-container widget_search">
        <h3><?php echo $xml->Affiner_votre_recherche; ?> </h3>
        <input type="text" name="adresse" id="adresse" value="<?php
		if(isset($_GET['adresse']) and !empty($_GET['adresse'])){
			echo $_GET['adresse'];
		}
		else if(isset($_SESSION['kt_adresse']))
			echo $_SESSION['kt_adresse'];
		else
			echo "Entrer votre adresse"; ?>" onblur="adresse = this.value; if(this.value == '') this.value='Entrer votre adresse';" onfocus="if(this.value == 'Entrer votre adresse') this.value=''" class="adr" /> 
        
        <div class="critere_filtre"><strong><?php echo $xml->rayon_Km; ?>:</strong></div>
				<div class="demo" style="height:70px;">

                    <form  action="" method="get">
                        <p><input type="text"   id="kilometrage" /></p>
                    </form>
                 <div class="ok" style="margin-top:9px;">
                    <a onclick="filtrerok();" href="javascript:void(0)" ><?php echo $xml->ok; ?> </a>
               	 </div>
       			 <div id="slider-surface"></div>
			</div> 
            

        	 <div class="critere_filtre"> <strong><?php echo $xml->Par_prix; ?>:</strong></div>
				<div class="demo">
                   <form  action="" method="get">

                        <input type="text"  name="amount" id="amount" />
                        <input type="text" id="amount1" />
                    </form>
                    <div class="ok" style="margin-top:9px;">
                    <a href="javascript:prixMax=document.getElementById('amount1').value;prixMin=document.getElementById('amount').value; sendAjax();"><?php echo $xml->ok; ?></a>
                    </div>
					<div id="slider-range"></div>
				</div>
            <div> 
            
             <div class="critere_filtre" style="top:-7px;"><strong><?php echo $xml->Par_categories; ?>:</strong></div>			
             	<?php 
if(!empty($_GET['sous_categorie2']))
	{
		$categorie=$_GET['sous_categorie2'];}
else{
		if(!empty($_GET['sous_categorie']))
		{	
		 $categorie=$_GET['sous_categorie'];
		}
		else{
			if(!empty($_GET['categorie']))
			{$categorie=$_GET['categorie'];
			}
		else
			{$categorie=0;
			}
		}
}	

if(isset($_GET['categorie']) and !empty($_GET['categorie']) ){
	$cat_en_cours=$_GET['categorie'];
	}
else{$cat_en_cours=0;}
$map2_result = $phpcat->map3();


if($cat_en_cours==0){
echo"<ul class=\"pasniveaux\">";
}else
{echo"<ul class=\"niveaux\">";}
$commancer = false;
$parent1 = 0;
$parent2 = 0;
$i=0; $j=0;
foreach($map2_result as $cat2){

//  gestion des liens du menu filtre
if($cat2['depth'] == 0){
	$parent1 = $cat2['cat_id'];
	$lien= "-".$cat2['cat_id'].".html";
}
else if($cat2['depth'] == 1){
	$parent2 = $cat2['cat_id'];
	$lien= "-$parent1-".$cat2['cat_id'].".html";
}
else {
	$lien= "-$parent1-$parent2-".$cat2['cat_id'].".html";
}
$lien.=isset($_GET['mot_cle']) ? "&mot_cle=".$_GET['mot_cle'] : "";
$lien.=isset($_GET['adresse']) ? "&adresse=".$_GET['adresse'] : "";

//
if($cat2['depth']==2){
	$i++;
	$listID = $j;
	$listSubID = 'subID="'.$i.'"';
	$subClass='niv_'.$cat2['depth'].'-'.$j;
	$listImg = '<div class="btnShowMenu-cate2 btnShowMenu-cate2-'.$listID.'-'.$i.'" '.$listSubID.' id="'.$listID.'"></div> <div class="btnHideMenu-cate2 btnHideMenu-cate2-'.$listID.'-'.$i.'" '.$listSubID.' id="'.$listID.'"></div>';
}else if($cat2['depth']==1){
	$j++;
	$listID = $j;
	$listSubID = '';
	$subClass='';
	$listImg = '<div class="btnShowMenu-cate btnShowMenu-cate-'.$listID.'" id="'.$listID.'"></div> <div class="btnHideMenu-cate btnHideMenu-cate-'.$listID.'" id="'.$listID.'"></div>';
}else if($cat2['depth']==3){
	$subClass='niv_'.$cat2['depth'].'-'.$j.'-'.$i;
	$listImg = '';
	$listSubID = '';
}

if(!empty($_GET['sous_categorie']) && $_GET['sous_categorie'] == $cat2['cat_id']){
	$firstCate = $j;
}
if(!empty($_GET['sous_categorie2']) && $_GET['sous_categorie2'] == $cat2['cat_id']){
	$firstCate2 = $i;
}

	if($cat_en_cours == 0) {
		if($cat2['depth'] == 0) {
			echo '<li class="toutcat bee" style="background:none; padding:0; margin-top:10px;"> <div class="btnShowMenu btnShowMenu-'.$parent1.'"></div> <div class="btnHideMenu btnHideMenu-p'.$parent1.'" id="p'.$parent1.'"></div>  <a href="javascript:location=\'sub_mproduits-'.$default_region.'-\'+prixMax+\'-\'+prixMin+\'-\'+rayon+\''.$lien.'\'">' .$cat2['cat_name'].'</a></li>';
			}
		else if ($cat2['depth'] == 1){
			echo '<li class="toutcat2 bee2 toutcat2-'.$parent1.'" style="display:none;">  <a href="javascript:location=\'sub_sub_mproduits-'.$default_region.'-\'+prixMax+\'-\'+prixMin+\'-\'+rayon+\''.$lien.'\'">' .$cat2['cat_name'].'</a></li>';
		}
		
	}
	else{
		if($cat2['depth']==0 and $cat2['cat_id'] == $cat_en_cours)
			$commancer = true;
		if($cat2['depth']==0 and $cat2['cat_id'] != $cat_en_cours){
			if($commancer == true)
			break;
			$commancer = false;
		}
		if($commancer){
			if($cat2['depth']>3){$cat2['depth']=3;
				echo '<li class="niv_'.$cat2['depth'].'" >' .$cat2['cat_name'].'</li>';
			}
			else{
				if($cat2['depth']=='0'){
					echo '<li class="niv_'.$cat2['depth'].' '.$subClass.'" '.$listSubID.' id="'.$listID.'" >'.$listImg.'<a href="javascript:location=\'sub_mproduits-'.$default_region.'-\'+prixMax+\'-\'+prixMin+\'-\'+rayon+\''.$lien.'\'">' .$cat2['cat_name'].'</a><div class="clear"></div></li>';
				}elseif($cat2['depth']=='1'){
					echo '<li class="niv_'.$cat2['depth'].' '.$subClass.'" '.$listSubID.' id="'.$listID.'" >'.$listImg.'<a href="javascript:location=\'sub_sub_mproduits-'.$default_region.'-\'+prixMax+\'-\'+prixMin+\'-\'+rayon+\''.$lien.'\'">' .$cat2['cat_name'].'</a><div class="clear"></div></li>';
				}elseif($cat2['depth']=='2'){
					echo '<li class="niv_'.$cat2['depth'].' '.$subClass.'" '.$listSubID.' id="'.$listID.'" >'.$listImg.'<a href="javascript:location=\'sub_sub_sub_mproduits-'.$default_region.'-\'+prixMax+\'-\'+prixMin+\'-\'+rayon+\''.$lien.'\'">' .$cat2['cat_name'].'</a><div class="clear"></div></li>';
				}
			}
			
		}
	}
	
}
echo"</ul>";

?>
			</div>    
    </div>
    
    <style>
		
		.clear{
			clear:both;
		}
		.niv_1{
			line-height:16px;
			padding:0;
		}
		.niv_1 a{
			margin-left:0;
		}
		.niv_2{
			line-height:16px;
			padding-left:10px;
		}
		.niv_2 a{
			margin-left:0;
		}
		.niv_2, .niv_3{
			display:none;
		}
		.niv_3 a{
			padding-left:4px;
		}
    	.espace_pub1{
			background-image: url("template/images/espace_pub_1.png");
			background-position: left top;
			background-repeat: no-repeat;
			float: left;
			height: 240px;
			padding: 0;
			position: relative;
			width: 200px;
		}
		.espace_pub1 h3{
			color: #9D216E;
			font-size: 20px;
			font-weight: bold;
			margin: 25px 0 0;
			padding: 0;
			text-align: center;
			text-transform: uppercase;
		}
		.btnShowMenu, .btnHideMenu, .btnShowMenu-cate, .btnHideMenu-cate, .btnShowMenu-cate2, .btnHideMenu-cate2{
			float:left;
			cursor:pointer;
			margin-right:3px;
			background:url(template/images/bullet3.png) no-repeat top center;
			width:16px;
			height:16px;
		}
		.btnHideMenu, .btnHideMenu-cate, .btnHideMenu-cate2{
			background-position:bottom center;
			display:none;
		}
    </style>
<style>
	.magazin_cou{
		width: 96%;
		position: absolute;
		margin-top: 120px;
		margin-left: 2%;
		height: 20px;
		font-weight: bold;
		font-size: 16px;
		text-align: center;
	}
	.ville_cou{
		width: 96%;
		position: absolute;
		margin-top: 142px;
		margin-left: 2%;
		height: 20px;
		font-weight: bold;
		font-size: 16px;
		text-align: center;
		background: #9D216E;
		color: #ffffff;
		-webkit-border-radius: 0 0 10px 10px;
		border-radius: 0 0 10px 10px;
	}
	</style> 
    <div style=" float:left; margin-left:10px;width: 200px;
height: 30px;
font-size: 20px;
text-align: center;
font-weight: bold;">
    	A LA UNE
    </div>
    <div id="slide_coupon" style=" float:left; margin-left:10px;">
    			<?php
                $query_coupon = "SELECT 
								  produits.titre,
								  magazins.id_magazin,
								  magazins.nom_magazin,
								  produits.photo1,
								  produits.prix,
								  produits.id,
								  produits.categorie,
								  maps_ville.nom 
								FROM
								  produits 
								  INNER JOIN pub 
									ON (produits.id = pub.id_produit) 
								  INNER JOIN magazins 
									ON (
									  produits.id_magazin = magazins.id_magazin
									) 
								  INNER JOIN maps_ville 
									ON (
									  magazins.ville = maps_ville.id_ville
									) 
								  INNER JOIN region 
									ON (
									  magazins.region = region.id_region
									) 
								  INNER JOIN pub_emplacement 
									ON (
									  pub.emplacement = pub_emplacement.id
									) 
								WHERE pub_emplacement.type = '2'   
								  AND pub_emplacement.sub_type = '1' 
								  AND pub.payer = '1' 
								  AND pub.date_fin > NOW() 
								  AND pub.date_debut < NOW()
								  AND produits.activate = '1' 
								  AND magazins.region='".$default_region."'
								ORDER BY RAND() 
								LIMIT 0, 3 ";
                $coupon = mysql_query($query_coupon, $magazinducoin) or die(mysql_error());
                $totalRows_coupon = mysql_num_rows($coupon);
				if($totalRows_coupon!='0'){
            ?>
            <?php while ($row_coupon=mysql_fetch_array($coupon)) {?>
            <div class="espace_pub1" style="margin:0px !important;">
            <h3>Produit</h3>
                <div style="width:100%; float:left;">
                    <div style="float:left; padding:0px 12px; height:100px;">
                        <div style="float:left; font-size:10px; width:75px; height:55px; text-align:center;">
                            <a href="detail_produit.php?region=<?php echo $default_region;?>&id=<?php echo $row_coupon['id'];?>&cat_id=<?php echo $row_coupon['categorie'];?>&mag_id=<?php echo $row_coupon['id_magazin'];?>&t=1" style="font-size:16px; font-weight:bold; color:#9D216E;">
                            <?php echo substr($row_coupon['titre'],0,55); ?>
                            </a>
                        </div>
                        <a href="detail_produit.php?region=<?php echo $default_region;?>&id=<?php echo $row_coupon['id'];?>&cat_id=<?php echo $row_coupon['categorie'];?>&mag_id=<?php echo $row_coupon['id_magazin'];?>&t=1"  style="float:left; margin-left:1px;">
                        <div class="image"> 
                            <img src="timthumb.php?src=assets/images/produits/<?php echo $row_coupon['photo1'] ?>&w=100&z=1">                               
                        </div>
                        </a>
                    </div>
                    <div style="width:100%;float:left">
                        <div class="prix">
                            <div class="prix_grand" style="margin-right:20px; color:#F49C00; text-align:right; font-size:20px; font-weight:bold;">
                               <?php
                                 echo $row_coupon['prix'];
                                 ?>&nbsp;&euro;
                            </div>
                        </div>
                    </div>
                </div> 
                
                <div class="magazin_cou">
                    <?php echo $row_coupon['nom_magazin']; ?>
                </div>
                <div class="ville_cou">
                   <?php echo $row_coupon['nom']; ?>
                </div>
            </div>  
            <?php }?>
        
        <?php }else{?>
        <div class="espace_pub1" style="margin:0px !important;">
            <h3>Produit</h3>
            <div style="width:100%; float:left;">
        	<img style="float:left; margin: -18px 0px 0px 10px;" src="timthumb.php?src=assets/de/produit_pub.png&amp;w=180&amp;z=1" alt="" />
        	</div>
        </div>
        <div class="espace_pub1" style="margin:0px !important;">
            <h3>Produit</h3>
            <div style="width:100%; float:left;">
        	<img style="float:left; margin: -18px 0px 0px 10px;" src="timthumb.php?src=assets/de/produit_pub.png&amp;w=180&amp;z=1" alt="" />
        	</div>
        </div>
        <div class="espace_pub1" style="margin:0px !important;">
            <h3>Produit</h3>
            <div style="width:100%; float:left;">
        	<img style="float:left; margin: -18px 0px 0px 10px;" src="timthumb.php?src=assets/de/produit_pub.png&amp;w=180&amp;z=1" alt="" />
        	</div>
        </div>
        <?php }?>
    </div>
    
    
</div>

<?php
mysql_free_result($faurchette_prix);
?>

<link type="text/css" href="assets/sliderrange/css/search_address.css" rel="stylesheet" />	
		<script type="text/javascript" src="assets/sliderrange/js/jquery-ui-1.8.17.custom.min.js"></script>
		<style>
	#demo-frame > div.demo { padding: 10px !important; };
	</style>
	<script>
	$(function() {
		$( "#slider-range" ).slider({
			range: true,
			min: <?php echo $min_prix ;?>,
			max: <?php echo $max_prix ;?>,
			values: [  <?php echo $min_prix ;?>,  <?php echo $max_prix ;?>],
			slide: function( event, ui ) {
				
				$( "#amount" ).val(ui.values[ 0 ] );
				$( "#amount1" ).val( ui.values[ 1 ] );
			}
		});
		
		$( "#amount" ).val( $( "#slider-range" ).slider( "values", 0 )) ;
		$( "#amount1" ).val( $( "#slider-range" ).slider( "values", 1 )) ;
	});
	
	</script>
       <script>
	$(function() {
		$( "#slider-surface" ).slider({
			range: "min",
			value: 250,
			min: 1,
			max: 700,
			slide: function( event, ui ) {
				$( "#kilometrage" ).val( ui.value );
			}
		});
		$( "#kilometrage" ).val( $( "#slider-surface" ).slider( "value" ) );
	});
	
	</script>
    <script type="text/javascript">
function loadSub(id) {
	$('div.btnShowMenu-cate-'+id).hide();
	$('div.btnHideMenu-cate-'+id).show();
	$('li.niv_2-'+id).fadeIn();
};
function loadSub2(id, subid) {
	$('div.btnShowMenu-cate2-'+id+'-'+subid).hide();
	$('div.btnHideMenu-cate2-'+id+'-'+subid).show();
	$('li.niv_3-'+id+'-'+subid).fadeIn();
};

$(document).ready(function() {
	$('div.btnShowMenu').click(function() {
		var id = $(this).attr('id');
		$(this).hide();
		$('div.btnHideMenu-'+id).show();
		$('li.toutcat2-'+id).fadeIn();
		return false;
	});
	$('div.btnHideMenu').click(function() {
		var id = $(this).attr('id');
		$(this).hide();
		$('div.btnShowMenu-'+id).show();
		$('li.toutcat2-'+id).hide();
		return false;
	});
	$('div.btnShowMenu-cate').click(function() {
		var id = $(this).attr('id');
		loadSub(id);
		return false;
	});
	$('div.btnHideMenu-cate').click(function() {
		var id = $(this).attr('id');
		$(this).hide();
		$('div.btnShowMenu-cate-'+id).show();
		$('li.niv_2-'+id).hide();
		return false;
	});
	$('div.btnShowMenu-cate2').click(function() {
		var id = $(this).attr('id');
		var subid = $(this).attr('subid');
		loadSub2(id, subid);
		return false;
	});
	$('div.btnHideMenu-cate2').click(function() {
		var id = $(this).attr('id');
		var subid = $(this).attr('subid');
		$(this).hide();
		$('div.btnShowMenu-cate2-'+id+'-'+subid).show();
		$('li.niv_3-'+id+'-'+subid).hide();
		return false;
	});
	
	<?php 
		if(isset($firstCate) && !empty($firstCate)){
	?>
		loadSub(<?php echo $firstCate;?>);
		
		<?php
			if(isset($firstCate2) && !empty($firstCate2)){
		?>
				loadSub2(<?php echo $firstCate;?>, <?php echo $firstCate2;?>);
		<?php
			}
		?>
	<?php
		}
	?>
});
</script>
    
    