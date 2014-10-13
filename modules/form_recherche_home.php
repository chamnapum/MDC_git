<?php  $query_categories = "SELECT * FROM category where parent_id=0";
$categories = mysql_query($query_categories, $magazinducoin) or die(mysql_error());

$totalRows_categories = mysql_num_rows($categories);?>
 <script type="text/javascript">
      function changeAction(page) {
        document.rech.action = page;
      }
</script>
<div class="recherche">
                	
                    <div class="recherche_outer">
                    <div class="recherche_form">
                    <h3><?php echo $xml->Rechercher; ?>:</h3>
                   
                    	<form method="get" action="" id="formHaut" name="rech">	
                        <div class="choix">
                       	  <p>
                        	  <label>
                        	    <input type="radio" name="choix" value="1" id="choix_0" <?php if(strpos($_SERVER['PHP_SELF'],'rechercher.php') !== FALSE) echo 'checked="checked"'; ?> onclick="changeAction('rechercher.php');" />
                        	    <?php echo $xml->produits; ?></label>
                        	  <label>
                        	    <input type="radio" name="choix" value="2" id="choix_1"  <?php if(strpos($_SERVER['PHP_SELF'],'rechercher_cpn.php') !== FALSE) echo 'checked="checked"'; ?> onclick="changeAction('rechercher_cpn.php');" />
                                 
                        	    <?php echo $xml->Coupons; ?></label>
                                <label>
                        	    <input type="radio" name="choix" value="3" id="choix_2" <?php if(strpos($_SERVER['PHP_SELF'],'liste_magasins.php') !== FALSE) echo 'checked="checked"'; ?> onclick="changeAction('liste_magasins.php');" />
                        	    <?php echo $xml->Magasins; ?></label>
                        	  <br />
                      	  </p>
                        </div>

 <input type="text" name="mot_cle" id="mot_cle" value="<?php if(isset($_GET['mot_cle']) and !empty($_GET['mot_cle']))  echo $_GET['mot_cle']; else echo "Mot clé" ; ?>" onblur="if(this.value == '') this.value='Mot clé';" onfocus="if(this.value == 'Mot clé') this.value=''" class="width3"> 

<input type="text" name="adresse" id="adresse" value="<?php if(isset($_GET['adresse']) and !empty($_GET['adresse']))  echo $_GET['adresse']; else echo $xml->Entrer_votre_adresse; ?>" onblur="if(this.value == '') this.value='<?php echo $xml->Entrer_votre_adresse ;?>';" onfocus="if(this.value == '<?php echo $xml->Entrer_votre_adresse ;?>') this.value=''" class="width1" > 

<select name="rayon" class="width2">
<option value="999"><?php echo $xml->Rayon1; ?></option>
<option value="1" <?php if(isset($_GET['rayon']) and $_GET['rayon'] == 1) echo "SELECTED"; ?>>1</option>
<option value="5" <?php if(isset($_GET['rayon']) and $_GET['rayon'] == 5) echo "SELECTED"; ?>>5</option>
<option value="10" <?php if(isset($_GET['rayon']) and $_GET['rayon'] == 10) echo "SELECTED"; ?>>10</option>
<option value="30" <?php if(isset($_GET['rayon']) and $_GET['rayon'] == 30) echo "SELECTED"; ?>>30</option>
<option value="50" <?php if(isset($_GET['rayon']) and $_GET['rayon'] == 50) echo "SELECTED"; ?>>50</option>
<option value="100" <?php if(isset($_GET['rayon']) and $_GET['rayon'] == 100) echo "SELECTED"; ?>>100</option>
<option value="200" <?php if(isset($_GET['rayon']) and $_GET['rayon'] == 200) echo "SELECTED"; ?>>200</option>
</select>


<select name="categorie" id="categorie" onchange="ajax('ajax/sous_categorie.php?default=0&id_parent='+this.value,'#sous_categorie'); " class="width1">

<option value=""><?php echo $xml->Tous_les_categories; ?></option>
<?php while($row_categories = mysql_fetch_assoc($categories)) { ?>
  <option value="<?php echo $row_categories['cat_id']?>" <?php if(isset($_GET['categorie']) and $_GET['categorie'] == $row_categories['cat_id']) echo "SELECTED"; ?>><?php echo ($row_categories['cat_name']); ?></option>
  <?php
} 
?>
</select>


<select name="sous_categorie" id="sous_categorie" class="width2" >
 <option value=""> <?php echo $xml->Sous_categorie; ?> </option>
</select>


              				
<input type="button" value="" onclick="if(document.getElementById('mot_cle').value == 'Mot clé') document.getElementById('mot_cle').value=''; if(document.getElementById('adresse').value == '<?php echo $xml->Entrer_votre_adresse ;?>') document.getElementById('adresse').value=''; this.form.submit();" class="search_ok" />
</form>
                    </div>
                    </div>
                </div>