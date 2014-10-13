<?php require_once('../Connections/magazinducoin.php'); ?>
<?php
	if(isset($_REQUEST['val'])){
		$cat = "AND type='".$_REQUEST['val']."'";
	}else{
		$cat ='';
	}
?>
<select style="width:150px;" name="categorie" id="categorie" onchange="ajax('ajax/sous_categorie.php?default=0&id_parent='+this.value,'#sous_categorie'); " class="width1">
	<?php  
        $query_categories = "SELECT * FROM category where parent_id=0 $cat ORDER BY category.order ASC";
        $categories = mysql_query($query_categories, $magazinducoin) or die(mysql_error());
        //$totalRows_categories = mysql_num_rows($categories);
		//echo $query_categories;
    ?>
    	<option value=""><?php echo $xml->Tous_les_categories; ?></option>
    <?php while ($row_categories=mysql_fetch_array($categories)) {?>
    	<option value="<?php echo $row_categories['cat_id']?>" ><?php echo ($row_categories['cat_name']); ?></option>
    <?php
    } 
    ?>
</select>