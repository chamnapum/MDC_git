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
?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#ville_near_all').click(function(){
			if($(this).is(":checked")){
				$('#ville_near_all').val('1');
			}
			else if($(this).is(":not(:checked)")){
				$('#ville_near_all').val('');
			}
		});
	});
</script>

<style>
	.widget-container .id_ville{
		padding: 3px 0px 5px 5px;
		width: 225px;
		float:left;
	}
	.widget-container .select2-choices{
		width:220px !important;
	}
	/*.select2-search-field{
		width:225px !important;
	}*/
	.widget-container .select2-input{
		width:225px !important;
	}
	.widget-container .select2-drop{
		left:222px !important;
		width:200px !important;
	}
	.select2-drop{
		
	}
	.select2-results{
		
	}
	.km{
		width: 255px;
		float: left;
		background: #cbcbcb;
		-webkit-border-radius: 5px 5px 5px 5px;
		border-radius: 5px 5px 5px 5px;
		padding: 10px;
		margin-top: 15px;
	}
	.km .rayon{
		float: left;
		width: 255px;
		font-size:13px;
	}
	.rayon2{
		float: left;
		width: 35px;
		font-size:13px;
		margin:0px;
		padding:0px;
	}
	.rayon label{
		margin-right:78px;
		margin-top: 3px;
		float:left;
	}
	.rayon2 .btnOK {
		background: #9d216e;
		color:#FFF;
		padding: 6px 10px 6px 10px;
		margin-left:5px;
		-webkit-border-radius: 5px 5px 5px 5px;
		border-radius: 5px 5px 5px 5px;
		float:left;
		border:none;
		cursor:pointer;
	}
	#kilometrage {
		background-color: #FFF;
		border: 1px solid #C2C2C2;
		color: #9D216E;
		font-size: 11px;
		font-weight: 700;
		width: 40px!important;
		margin:0px;
	}
	
	
</style>

<div style="float:left; width:310px;">
  	<div class="widget-container widget_search" style="margin:0px 20px 0px 20px; float:left;">
    	<div style="font-size:19px; color:#372f2b; margin-bottom:10px; float:left;">Affiner votre recherche</div>
        
        
		<script id="script_e21">
        
        var preload_data = [
        <?php
            $query_ville = "SELECT 
                              maps_ville.id_ville,
                              maps_ville.nom,
                              maps_ville.cp
                            FROM
                              maps_ville 
                              INNER JOIN departement 
                                ON maps_ville.id_departement = departement.id_departement WHERE departement.id_region='".$default_region."' ORDER BY nom ASC";
            $ville = mysql_query($query_ville, $magazinducoin) or die(mysql_error());
            while($row_ville= mysql_fetch_array($ville)) {
        ?>
          { id: '<?php echo $row_ville['id_ville'];?>', text: '<?php echo $row_ville['nom'].' '.$row_ville['cp'];?>'},
        <?php }?>
        ];
        
        <?php
        if($Value_ville!=''){
        ?>
            var preload_edit = [
            <?php
                $query_villes = "SELECT 
                              maps_ville.id_ville,
                              maps_ville.nom,
                              maps_ville.cp
                            FROM
                              maps_ville 
                              INNER JOIN departement 
                                ON maps_ville.id_departement = departement.id_departement WHERE maps_ville.id_ville IN (".$Value_ville.") ORDER BY nom ASC";
                $villes = mysql_query($query_villes, $magazinducoin) or die(mysql_error());
                $totals = mysql_num_rows($villes);
                while($row_villes= mysql_fetch_array($villes)) {
            ?>
              { id: '<?php echo $row_villes['id_ville'];?>', text: '<?php echo $row_villes['nom'].' '.$row_villes['cp'];?>'},
            <?php }?>
            ];
        <?php } else {?>
            var preload_edit='';
        <?php }?>
        $(document).ready(function () {
          $('#id_ville').select2({
              placeholder: "Ville ou Code postal",
			  minimumInputLength: 2,
              maximumSelectionSize: 3,
              multiple: true
              ,query: function (query){
                  var data = {results: []};
        
                  $.each(preload_data, function(){
                      if(query.term.length == 0 || this.text.toUpperCase().indexOf(query.term.toUpperCase()) >= 0 ){
                          data.results.push({id: this.id, text: this.text });
                      }
                  });
        
                  query.callback(data);
              }
          });
          $('#id_ville').select2('data', preload_edit )
        });
        </script>
       
        <form method="post" action="magasin.html">
        	<input type="text" id="id_ville" name="id_ville" class="id_ville" style="width:230px; margin-bottom:9px;"/>
            
            <input type="hidden" id="mot_cle" name="mot_cle" value="<?php echo $_REQUEST['mot_cle'];?>"/>
            <input type="hidden" id="categorie" name="categorie" value="<?php echo $_REQUEST['categorie'];?>" />
            <input type="hidden" id="sous_categorie" name="sous_categorie" value="<?php echo $_REQUEST['sous_categorie'];?>" />
             
            <div class="rayon2">
            <input type="submit" class="btnOK" value="OK" />
            </div>
            <div style="font-size:16px; color:#372f2b; margin-bottom:10px; float:left;"><input type="checkbox" name="ville_near_all" id="ville_near_all" <?php if($_REQUEST['ville_near_all']=='1'){?> checked="checked"<?php }?> value="<?php echo $_REQUEST['ville_near_all'];?>"> Inclure les petites ville voisines</div>
        </form>
	</div> 
    
    <!--<div class="clear"></div> -->
	
    <div class="km" style="margin:8px 20px 20px 20px;">
    	<div class="rayon">
			<label>Par catégories</label>
        </div>
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
		else{
			$cat_en_cours=0;
		}
		$map2_result = $phpcat->map4();
		
		
		if($cat_en_cours==0){
		echo"<ul class=\"pasniveaux\">";
		}else
		{echo"<ul class=\"niveaux\" style=\"padding:10px 0px !important;\">";}
		$commancer = false;
		$parent1 = 0;
		$parent2 = 0;
		$i=0; $j=0;
		foreach($map2_result as $cat2){
		
			$lien='';
			//$lien.=isset($_GET['mot_cle']) ? "&mot_cle=".$_GET['mot_cle'] : "";
			//$lien .= isset($_REQUEST['mot_cle']) ? '-'.$_REQUEST['mot_cle']:"";
			
			$listImg = '<div class="btnShowMenu-cate"></div>';
		
			if($cat2['parent_id']==$_GET['sous_categorie']){
				$parent1 = $_GET['categorie'];
				$parent2 = $_GET['sous_categorie'];
				$lien= "-$parent1-$parent2-".$cat2['cat_id'].".html";
				echo'<li class="hide3 niv_1 toutcat" style="background:none; padding:0;">'.$listImg.'<a href="javascript:location=\'sub_sub_sub_magasins-'.$nom_region.'-'.$default_region.''.$lien.'\'">' .$cat2['cat_name'].'</a></li>';
				?>
				<style>
					.hide2{
						display:none !important;
					}
				</style>
				<?php
			}
			
			if($cat2['parent_id']==$_GET['categorie']){
				$parent1 = $_GET['categorie'];
				$parent2 = $cat2['cat_id'];
				$lien= "-$parent1-".$cat2['cat_id'].".html";
				echo'<li class="hide2 niv_1 toutcat" style="background:none; padding:0; margin-bottom: 5px !important;">'.$listImg.'<a href="javascript:location=\'sub_sub_magasins-'.$nom_region.'-'.$default_region.''.$lien.'\'">' .$cat2['cat_name'].'</a></li>';
				?>
				<style>
					.hide1{
						display:none !important;
					}
				</style>
				<?php
			}
			
			if($cat2['parent_id']=='0'){
				$parent1 = $cat2['cat_id'];
				$lien.= "-".$cat2['cat_id'].".html";
				echo'<li class="hide1 toutcat" style="background:none; padding:0; margin-top:10px;">'.$listImg.'<a href="javascript:location=\'sub_magasins-'.$nom_region.'-'.$default_region.''.$lien.'\'">' .$cat2['cat_name'].'</a></li>';
			}
		}
		echo"</ul>";
		
		
		?>
		</div> 
    	
        <style>
        	.ad{
				width:265px; 
				margin-left:-10px; 
				font-size:14.5px; 
				float:left;
			}
			.ad .ad_ville{
				padding:10px; 
				float:left; 
				background:#353535; 
				color:#FFF;
				text-transform:uppercase;
			}
			.ad img{
				float:right;
			}
			.ad_title{
				font-size:15px; 
				float:left; 
				width:255px; 
				margin:10px 0px;
				text-transform:uppercase;
			}
			.ad_type{
				margin-left:-10px; 
				margin-bottom:-10px; 
				padding-left:10px; 
				padding-top: 10px; 
				padding-bottom: 10px; 
				width:265px; 
				background-color:#FFF; 
				float:left; 
				font-size:13px;
				-webkit-border-radius: 0px 0px 5px 5px;
				border-radius: 0px 0px 5px 5px;
			}
			.ad_type a{
				width:100%; 
				float:left;
				font-weight:bold;
			}
        </style>
        
        <?php
			$datetime = date('Y-m-d H:i:s');
			$date = date('Y-m-d');
			$query_coupon = "SELECT 
							  magazins.id_magazin,
							  magazins.nom_magazin,
							  magazins.logo,
							  magazins.id_magazin,
							  maps_ville.nom AS ville,
							  magazins.photo1,
							  magazins.en_avant_fin 
							FROM
							  (
								magazins 
								INNER JOIN region 
								  ON region.id_region = magazins.region 
								INNER JOIN departement 
								  ON departement.id_departement = magazins.department 
								INNER JOIN maps_ville 
								  ON maps_ville.id_ville = magazins.ville
							  ) 
							WHERE magazins.region = '".$default_region."' 
							  AND magazins.activate = '1' 
							  AND magazins.payer = '1' 
							  AND magazins.en_avant = '1' 
							  AND magazins.en_avant_payer = '1' 
							  AND magazins.en_avant_fin >= '".$date."' 
							  AND DATE_ADD(
								en_avant_fin,
								INTERVAL - day_en_avant DAY
							  ) <= '".$date."' 
							  AND (
								magazins.approuve = '1' 
								OR (
								  magazins.approuve = 0 
								  AND magazins.public = 1 
								  AND magazins.public_start < '".$date."' 
								  AND (
									magazins.public_start + INTERVAL 20 MINUTE
								  ) < '".$datetime."'
								)
							  ) 
							ORDER BY RAND() 
							LIMIT 0, 3 ";
			$coupon = mysql_query($query_coupon, $magazinducoin) or die(mysql_error());
			$totalRows_coupon = mysql_num_rows($coupon);
		if($totalRows_coupon!='0'){
		?>
			<?php $now = date('Y-m-d H:i:s');?>
            <?php while ($row_coupon=mysql_fetch_array($coupon)) {?>
                <?php $nom=str_replace($finds,$replaces,($row_coupon['nom_magazin']));?>
                <?php $nom_region=str_replace($finds,$replaces,(getRegionById($default_region)));?>
            <div class="km" style="margin:0px 20px 20px 20px;">
                <div class="ad">
                    <div class="ad_ville">
                        <?php echo $row_coupon['ville']; ?>
                    </div>
                    <?php if($row_coupon['photo1']){ ?>
                    <img src="timthumb.php?src=assets/images/magasins/<?php echo $row_coupon['photo1'] ?>&amp;w=37&amp;h=37&z=1">  
                    <?php }?> 
                </div>
                <div class="ad_title">
                    <?php echo substr(utf8_decode($row_coupon['nom_magazin']),0,55); ?>
                </div>
                <div class="ad_type">
                    <div style="color:#000;">Cet établissement propose</div>
                    <a href="md-<?php echo $default_region;?>-<?php echo $nom_region;?>-<?php echo $row_coupon['id_magazin'];?>-<?php echo $nom;?>.html#tabs-5" style="color:#cf8400;">
                    	<?php echo count_coupon($row_coupon['id_magazin'],$default_region); ?> Coupon(s) de réduction   
                    </a>
                    <a href="md-<?php echo $default_region;?>-<?php echo $nom_region;?>-<?php echo $row_coupon['id_magazin'];?>-<?php echo $nom;?>.html#tabs-4" style="color:#9d216e;">
                    	<?php echo count_event($row_coupon['id_magazin'],$default_region); ?> Événement(s) 
                    </a>
                    <a href="md-<?php echo $default_region;?>-<?php echo $nom_region;?>-<?php echo $row_coupon['id_magazin'];?>-<?php echo $nom;?>.html#tabs-6" style="color:#b45f93;">
                    	<?php echo count_product($row_coupon['id_magazin'],$default_region);?> Produit(s)
                	</a>
                </div>
            </div>  
            <?php }?>
        <?php }?>

    </div>
