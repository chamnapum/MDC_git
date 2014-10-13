<?php require_once('Connections/magazinducoin.php'); ?>
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
$query_faurchette_prix = "SELECT produits.prix
FROM (produits
LEFT JOIN magazins ON magazins.id_magazin=produits.id_magazin)
WHERE magazins.region = ".$_SESSION['region']." AND produits.en_stock = 1
ORDER BY produits.prix ASC";
$faurchette_prix = mysql_query($query_faurchette_prix, $magazinducoin) or die(mysql_error());
$row_faurchette_prix = mysql_fetch_assoc($faurchette_prix);
$totalRows_faurchette_prix = mysql_num_rows($faurchette_prix);

$min_prix = $row_faurchette_prix['prix'];
while($row_faurchette_prix = mysql_fetch_assoc($faurchette_prix))
	$max_prix = $row_faurchette_prix['prix'];


?>
<script>
var prixMax = <?php echo $max_prix ?>;
var prixMin = <?php echo $min_prix ?>;
var rayon   = '<?php echo isset($_GET['rayon']) ? $_GET['rayon'] : "300"; ?>';
var adresse = '<?php 
if(isset($_GET['adresse']) and !empty($_GET['adresse']))
	echo $_GET['adresse'];
else if(isset($_COOKIE['kt_adresse']))
	echo $_COOKIE['kt_adresse'];
else
	echo "Entrer votre adresse"; ?>';

var autre_criteres = '<?php 
echo isset($_GET['categorie'])		? "&categorie=".$_GET['categorie'] : "";
echo isset($_GET['sous_categorie'])? "&sous_categorie=".$_GET['sous_categorie'] : "";
echo ($_GET['mot_cle']) ? "&mot_cle=".$_GET['mot_cle'] : "";
?>';

function sendAjax(){
	ajax('ajax/resultat_recherche.php?prixMax='+prixMax+'&prixMin='+prixMin+'&rayon='+rayon+'&adresse='+adresse+autre_criteres,'#result');
}
</script>
<div style="float:left; width:220px">
	<div class="widget-container widget_search">
    	<div class="listeCourse" id="courses"><?php include("modules/liste_course.php"); ?></div>
        <h3>Affiner votre recherche </h3>
        <input type="text" name="adresse" id="adresse" value="<?php
if(isset($_GET['adresse']) and !empty($_GET['adresse']))
	echo $_GET['adresse'];
else if(isset($_COOKIE['kt_adresse']))
	echo $_COOKIE['kt_adresse'];
else
	echo "Entrer votre adresse"; ?>" onblur="adresse = this.value; if(this.value == '') this.value='Entrer votre adresse';" onfocus="if(this.value == 'Entrer votre adresse') this.value=''" class="adr" /> 
        
        <div class="critere_filtre"><strong>Par rayon (Km):</strong></div>
				<div class="demo" style="height:70px;">

                    <form  action="" method="get">
                        <input type="text"   id="kilometrage" />
                    </form>
                 <div class="ok">
                    <a onClick="if(document.getElementById('adresse').value == 'Entrer votre adresse' && adresse == 'Entrer votre adresse') {  alert('Vous devez remplir le champ adresse!!'); } else { rayon=document.getElementById('kilometrage').value; sendAjax(); } " href="javascript:void(0)" >ok </a>
               	 </div>
       			 <div id="slider-surface"></div>
			</div> 
            

        	 <div class="critere_filtre"><strong>Par prix (&euro;):</strong></div>
				<div class="demo">
                  
                   <form  action="" method="get">

                        <input type="text"  name="amount" id="amount" />
                        <input type="text" id="amount1" />
                    </form>
                    <div class="ok">
                    <a href="javascript:prixMax=document.getElementById('amount1').value;prixMin=document.getElementById('amount').value; sendAjax();">ok</a>
                    </div>
					<div id="slider-range"></div>
				</div>
            
            <div> 
             <div class="critere_filtre" style="top:-7px;"><strong>Par categories:</strong></div>			
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
$map2_result = $phpcat->map2();


if($cat_en_cours==0){
echo"<ul class=\"pasniveaux\">";
}else
{echo"<ul class=\"niveaux\">";}
$commancer = false;
$parent1 = 0;
$parent2 = 0;
foreach($map2_result as $cat2){

//  gestion des liens du menu filtre
if($cat2['depth'] == 0){
	$parent1 = $cat2['cat_id'];
	$lien= "&categorie=".$cat2['cat_id']."&sous_categorie=&sous_categorie2=";
}
else if($cat2['depth'] == 1){
	$parent2 = $cat2['cat_id'];
	$lien= "&categorie=$parent1&sous_categorie=".$cat2['cat_id']."&sous_categorie2=";
}
else {
	$lien= "&categorie=$parent1&sous_categorie=$parent2&sous_categorie2=".$cat2['cat_id'];
}
$lien.=isset($_GET['mot_cle']) ? "&mot_cle=".$_GET['mot_cle'] : "";
$lien.=isset($_GET['adresse']) ? "&adresse=".$_GET['adresse'] : "";

//

	if($cat_en_cours == 0) {
		if($cat2['depth'] == 0) {
			echo '<li class="toutcat">  <a href="javascript:location=\'rechercher.php?prixMax=\'+prixMax+\'&prixMin=\'+prixMin+\'&rayon=\'+rayon+\''.$lien.'\'">' .$cat2['cat_name'].'</a></li>';
			}
		else if ($cat2['depth'] == 1){
		echo '<li class="toutcat2">  <a href="javascript:location=\'rechercher.php?prixMax=\'+prixMax+\'&prixMin=\'+prixMin+\'&rayon=\'+rayon+\''.$lien.'\'">' .$cat2['cat_name'].'</a></li>';
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
				echo '<li class="niv_'.$cat2['depth'].'" ><a href="javascript:location=\'rechercher.php?prixMax=\'+prixMax+\'&prixMin=\'+prixMin+\'&rayon=\'+rayon+\''.$lien.'\'">' .$cat2['cat_name'].'</a></li>';
			}
			
		}
	}
}
echo"</ul>";

?>
			</div>    
    </div>
</div>

<?php
mysql_free_result($faurchette_prix);
?>

<link type="text/css" href="sliderrange/css/ui-lightness/jquery-ui-1.8.17.custom.css" rel="stylesheet" />	
		<script type="text/javascript" src="sliderrange/js/jquery-ui-1.8.17.custom.min.js"></script>
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
			value: 100,
			min: 1,
			max: 700,
			slide: function( event, ui ) {
				$( "#kilometrage" ).val( ui.value );
			}
		});
		$( "#kilometrage" ).val( $( "#slider-surface" ).slider( "value" ) );
	});
	
	</script>
<?php echo echo $_COOKIE['kt_adresse']; ?>