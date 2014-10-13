<script type="text/javascript">
	function changeAction(page) {
		//alert(page);
		//document.rech.action = page;
		$('#formHaut').attr('action',page);
	}
	function myAutoComplete(de){
		var de = $('#departement').val();
		var ville = '<?php echo $Value_ville;?>';
		$(".ville_nearby").html('loading..');
		if(de=='near'){
			jQuery('.ville_nearby').html('<input type="text" style="width:175px; margin:0px !important; " disabled="disabled">');
		}else if(de=='region'){
			jQuery('.ville_nearby').html('<input type="text" style="width:175px; margin:0px !important; " disabled="disabled">');
		}else{
				
			var dataString = 'de='+de+'&ville='+ville;
			jQuery.ajax({
					type: "POST",
					url: "ajax/ville_near_multi.php",
					data: dataString,
					cache: false,
					success: function(datas){
						$(".ville_nearby").html(datas);
					}
				});	
			return false;
		}
	}
	
	jQuery(document).ready(function() {

		var de = $('#departement').val();
		myAutoComplete(de);
		
		jQuery('#departement').change(function() {
			de = $('#departement').val();
			myAutoComplete(de);
		});	
		
		
		
		$('input:radio[name=choix]').change(function() {
			cate_search();
		});	
		
				
		function cate_search(){
			
			var val = $('input:radio[name=choix]:checked').val();
			
			if(val==null){
				val = 3;
			}
			
			if(val == 0){
				changeAction('produit.html');
			}else if(val == 1){
				changeAction('coupon.html');
			}else if(val == 3){
				changeAction('magasin.html');
			}
			
			var dataString = 'val='+val+'&categorie=<?php echo $_REQUEST['categorie'];?>'+'&sous_categorie=<?php echo $_REQUEST['sous_categorie'];?>';
			$.ajax({
					type: "POST",
					url: "ajax/category2.php",
					data: dataString,
					cache: false,
					success: function(datas){
						$("#cate_search").html(datas);
					}
				});	
			return false;
		}
		
		
		$('#cate').change(function() {
			var css = $(this).find('option:selected').attr("class");
			var value = $('#cate').val();
			if(css=='categorie'){
				$('#categorie').val(value);
				$('#sous_categorie').val('');
			}else if(css=='sous_categorie'){
				$('#sous_categorie').val(value);
				$('#categorie').val('');
			}
		});
		
		
		
	});
</script>

<style>
	#btnOKH{
		border:none; 
		cursor:pointer; 
		background:#9D216E; 
		padding:12px 11px 13px;
		padding:12px 14px\9; 
		margin:0px; 
		color:#FFF; 
		font-weight:bold;
		border-left:2px solid #FFF;
	}
	@media screen and (-webkit-min-device-pixel-ratio:0) {
		#btnOKH{
			border:none; 
			cursor:pointer; 
			background:#9D216E; 
			padding:13px 14px 13px 14px; 
			margin:0px; 
			color:#FFF; 
			font-weight:bold;
			border-left:2px solid #FFF;
		}
	}
</style>

<form method="post" action="magasin.html" id="formHaut" name="rech">	
<div style="width:950px; min-height:38px; padding-top:5px; float:left; background:#372f2b;">

    <div class="choix" style="float:left; width:220px; height:25px; padding-top:3px; color:#FFF;">
        <label>
            <input type="radio" name="choix" value="3" id="choix_2" <?php if((strpos($_SERVER['PHP_SELF'],'liste_magasins.php') !== FALSE) || (strpos($_SERVER['PHP_SELF'],'region.php') !== FALSE)) echo 'checked="checked"'; ?> onclick="changeAction('magasin.html');" />
            <?php echo $xml->Magasins; ?>
        </label>
        <label>
        	<input type="radio" name="choix" value="1" id="choix_1" <?php if(strpos($_SERVER['PHP_SELF'],'rechercher_cpn.php') !== FALSE) echo 'checked="checked"'; ?> onclick="changeAction('coupon.html');" />
            <?php echo $xml->Coupons; ?>
        </label>
        <label>
        	<input type="radio" name="choix" value="0" id="choix_0" <?php if(strpos($_SERVER['PHP_SELF'],'rechercher.php') !== FALSE) echo 'checked="checked"'; ?> onclick="changeAction('produit.html');" />
            <?php echo $xml->produits; ?>
        </label>
    </div>
    <div style="float:left; width:730px; min-height:28px; padding-top:3px; padding-top:2px\9; display: table;">
    <input type="text" style="width:170px;  margin:0px 5px 0px 0px !important; float:left;" name="mot_cle" id="mot_cle" placeholder="mot clé" value="<?php if(isset($_REQUEST['mot_cle']) and !empty($_REQUEST['mot_cle']))  echo $_REQUEST['mot_cle'];?>" class="width3"> 
    
    <span id="cate_search">
    <select style="width:175px; margin:0px 5px 0px 0px !important; float:left;" name="cate" id="cate" class="width1">
    
    
    <?php  
        $query_categories = "SELECT * FROM category where parent_id=0 AND type=3 ORDER BY category.order ASC";
        $categories = mysql_query($query_categories, $magazinducoin) or die(mysql_error());
        $totalRows_categories = mysql_num_rows($categories);
    ?>
    
    <option value=""><?php echo $xml->Tous_les_categories; ?></option>
    <?php while($row_categories = mysql_fetch_assoc($categories)) { ?>
        <option class="categorie" style="background-color:#dcdcc3;" value="<?php echo ($row_categories['cat_id']); ?>" <?php if(isset($_REQUEST['categorie']) and $_REQUEST['categorie'] == $row_categories['cat_id']) echo "SELECTED"; ?>><?php echo ($row_categories['cat_name']); ?></option>
		<?php  
            $query_categories2 = "SELECT * FROM category where parent_id='".$row_categories['cat_id']."' AND type=3 ORDER BY category.order ASC";
            $categories2 = mysql_query($query_categories2, $magazinducoin) or die(mysql_error());
        ?>
        <?php while($row_categories2 = mysql_fetch_assoc($categories2)) { ?>
            <option class="sous_categorie" value="<?php echo $row_categories2['cat_id']?>" <?php if(isset($_REQUEST['sous_categorie']) and $_REQUEST['sous_categorie'] == $row_categories2['cat_id']) echo "SELECTED"; ?>>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ($row_categories2['cat_name']); ?></option>
        <?php }?>
        
    <?php }?>
    </select>
    </span>
    
	<?php  
        $query_departement = "SELECT * FROM departement WHERE id_region='".$default_region ."' ORDER BY code ASC";
        $departement = mysql_query($query_departement, $magazinducoin) or die(mysql_error());
        $totalRows_departement = mysql_num_rows($departement);
    ?>
    
    <select style="width:175px; margin:0px 5px 0px 0px !important; float:left;" name="departement" id="departement" class="width2">
    	<option value="0" <?php if($_REQUEST['departement'] == '0') echo "SELECTED"; ?>><?php echo getRegionById($default_region);?></option>
    	<option value="near" <?php if($_REQUEST['departement'] == 'near') echo "SELECTED"; ?>>Région voisine</option>
        <option value="region" <?php if($_REQUEST['departement'] == 'region') echo "SELECTED"; ?> style="background-color:#dcdcc3;">Departement</option>
        <?php while($row_departement= mysql_fetch_assoc($departement)) { ?>
        	<option value="<?php echo $row_departement['code']?>" <?php if(isset($_REQUEST['departement']) and $_REQUEST['departement'] == $row_departement['code']) echo "SELECTED"; ?> ><?php echo $row_departement['nom_departement']?></option>
        <?php }?>
    </select>
          
    <span class="ville_nearby" style="float:left; "> </span>
    
    <input type="hidden" id="region" name="region" value="<?php echo $_REQUEST['region'];?>" />
    <input type="hidden" id="categorie" name="categorie" value="<?php echo $_REQUEST['categorie'];?>" />
    <input type="hidden" id="sous_categorie" name="sous_categorie" value="<?php echo $_REQUEST['sous_categorie'];?>" />
    
     <input type="hidden" id="departement_text" name="departement_text" value="<?php echo $_REQUEST['departement_text'];?>" />
    
    </div>
</div>    
<div style="width:50px; float:left;">                            
<input type="submit" value="OK" id="btnOKH" />
</div>


</form>