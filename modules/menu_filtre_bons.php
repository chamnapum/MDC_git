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
$query_faurchette_prix = " SELECT MIN(bons.reduction) as minimum , MAX(bons.reduction) as maximum 
FROM (bons
LEFT JOIN magazins ON magazins.id_magazin=bons.id_magasin)
WHERE magazins.region = ".$_SESSION['region']." AND bons.date_fin > curdate()";
$faurchette_prix = mysql_query($query_faurchette_prix, $magazinducoin) or die(mysql_error());
$row_faurchette_prix = mysql_fetch_assoc($faurchette_prix);
$totalRows_faurchette_prix = mysql_num_rows($faurchette_prix);

$min_prix = $row_faurchette_prix['minimum'];
$max_prix = $row_faurchette_prix['maximum'];
?>
<script>
var cpnMax = <?php echo $max_prix ?>;
var cpnMin = <?php echo $min_prix ?>;
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
echo isset($_GET['categorie'])		? "&categorie=".$_GET['categorie'] : "";
echo isset($_GET['sous_categorie'])? "&sous_categorie=".$_GET['sous_categorie'] : "";
echo ($_GET['mot_cle']) ? "&mot_cle=".$_GET['mot_cle'] : "";
?>';

function sendAjax(){
	ajax('ajax/resultat_recherche_bons.php?cpnMax='+cpnMax+'&cpnMin='+cpnMin+'&rayon='+rayon+'&adresse='+adresse+autre_criteres,'#result');
}
</script>
<div style="float:left; width:220px">
	<div class="widget-container widget_search">
       <?php include("modules/cart.php"); ?>
        <h3><?php echo $xml->Affiner_votre_recherche ; ?></h3>
        
        
       <input type="text" name="adresse" id="adresse" value="<?php
	   if(isset($_GET['adresse']) and !empty($_GET['adresse'])){
			echo $_GET['adresse'];
		}
		else if(isset($_SESSION['kt_adresse']))
			echo $_SESSION['kt_adresse'];
		else
			echo "Entrer votre adresse";
	 ?>" onblur="adresse = this.value; if(this.value == '') this.value='Entrer votre adresse';" onfocus="if(this.value == 'Entrer votre adresse') this.value=''" class="adr" /> 
       
        <div class="critere_filtre"><strong><?php echo $xml->par_rayon; ?>:</strong></div>
				<div class="demo2">
                    <form  action="" method="get">
                        <input type="text"   id="kilometrage" />
                    </form>
                    
              <div class="ok">
                    <a onClick="if(document.getElementById('adresse').value == 'Entrer votre adresse' && adresse == 'Entrer votre adresse' || adresse == '') {alert('Vous devez remplir le champ adresse!!'); } else { rayon=document.getElementById('kilometrage').value; sendAjax(); } " href="javascript:;" >ok </a>
               </div>
               
       			 <div id="slider-surface"></div>
			</div> 
            
            
        	 <div class="critere_filtre"><strong>Par bon<?php //echo $xml->Par_bon; ?>:</strong></div>
				<div class="demo">
                    <form  action="" method="get">
                        <input type="text"  name="amount" id="amount" />
                        <input type="text" id="amount1" />
                    </form>
                    <div class="ok">
                    <a href="javascript:cpnMax=document.getElementById('amount1').value; cpnMin=document.getElementById('amount').value; sendAjax();"><?php echo $xml->ok; ?></a>
                    
                    
                    </div>
					<div id="slider-range"></div>
				</div>
           
            <div> 
             <div class="critere_filtre" style="top:16px;"><strong><?php echo $xml->Par_categories; ?>:</strong></div><br/><br/>
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



echo"<ul class=\"pasniveaux\">";

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

		if($cat2['depth'] == 0) {
			echo '<li class="toutcat">  <a href="javascript:location=\'rechercher_cpn.php?cpnMax=\'+cpnMax+\'&cpnMin=\'+cpnMin+\'&rayon=\'+rayon+\''.$lien.'\'">' .$cat2['cat_name'].'</a></li>';
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
