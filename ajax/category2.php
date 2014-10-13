<?php require_once('../Connections/magazinducoin.php'); ?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#cate').change(function() {
			//var css = $('.sous_categorie option:selected').attr('class');
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
<?php
	if(isset($_REQUEST['val'])){
		$cat = "AND type='".$_REQUEST['val']."'";
	}else{
		$cat ='';
	}
?>
<select style="width:175px; margin:0px 5px 0px 0px !important; float:left;" name="cate" id="cate" class="width1">
    

<?php  
    $query_categories = "SELECT * FROM category where parent_id=0 $cat ORDER BY category.order ASC";
    $categories = mysql_query($query_categories, $magazinducoin) or die(mysql_error());
    $totalRows_categories = mysql_num_rows($categories);
?>

<option value=""><?php echo $xml->Tous_les_categories; ?></option>
<?php while($row_categories = mysql_fetch_assoc($categories)) { ?>
	<option class="categorie" style="background-color:#dcdcc3;" value="<?php echo ($row_categories['cat_id']); ?>" <?php if(isset($_REQUEST['categorie']) and $_REQUEST['categorie'] == $row_categories['cat_id']) echo "SELECTED"; ?>><?php echo ($row_categories['cat_name']); ?></option>
    <?php  
        $query_categories2 = "SELECT * FROM category where parent_id='".$row_categories['cat_id']."' ORDER BY category.order ASC";
        $categories2 = mysql_query($query_categories2, $magazinducoin) or die(mysql_error());
    ?>
    <?php while($row_categories2 = mysql_fetch_assoc($categories2)) { ?>
        <option class="sous_categorie" value="<?php echo $row_categories2['cat_id']?>" <?php if(isset($_REQUEST['sous_categorie']) and $_REQUEST['sous_categorie'] == $row_categories2['cat_id']) echo "SELECTED"; ?>>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ($row_categories2['cat_name']); ?></option>
    <?php }?>
<?php }?>
</select>
