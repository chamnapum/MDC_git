<?php require_once('../Connections/magazinducoin.php'); ?>
<?php
// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');

// Make unified connection variable
$conn_magazinducoin = new KT_connection($magazinducoin, $database_magazinducoin);

//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_magazinducoin, "../");
//Grand Levels: Level
$restrict->addLevel("4");
$restrict->Execute();
//End Restrict Access To Page
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 
	<title>Admin Magasinducoin </title>
    	<style type="text/css">
		@import url(../stylesheets/custom-bg.css);			/*link to CSS file where to change backgrounds of site headers */
		@import url(../stylesheets/styles-light.css);		/*link to the main CSS file for light theme color */
		@import url(../stylesheets/widgets-light.css);		/*link to the CSS file for widgets of light theme color */
		@import url(../stylesheets/superfish-admin.css);			/*link to the CSS file for superfish menu */
		@import url(../stylesheets/tipsy.css);				/*link to the CSS file for tips */
		@import url(../stylesheets/contact.css);				/*link to the CSS file for tips */

hr{border:0;width:100%;color:#d8d8d8;background-color:#d8d8d8;height:1px;}
#path{font-weight:bold;}
table.list_category {
    width:500px;
	border-width: 0px;
	border-spacing: 0px;
	border-style: outset;
	border-color: #f0f0f0;
	border-collapse: collapse;
	background-color: #fff; /* #fffff0; */
}
table.list_category th {
	font-family: verdana,helvetica;
	color: #666;
	font-size: 14px;
	border-width: 1px;
	padding: 5px;
	border-style: solid;
	border-color: #D8D8D8;
    background-color: #D8D8D8;
	-moz-border-radius: 0px; /* 0px 0px 0px 0px */
}
table.list_category td {
	border-width: 1px;
	padding: 4px;
	border-style: solid;
	border-color: #ccc;
    color: #666;
	font-size: 14px;
	/*background-color: #fffff0;*/
	-moz-border-radius: 0px;
}
</style>
</head>
<body id="sp">
<?php include("modules/header.php"); ?>

<div class="content_wrapper_sbr">
	<div>
  		<div id="content">
  		  <?php
require "../class/php_cat2.class.php";
$params = array(
'separator'=> '&nbsp; / &nbsp;',
'area' => 'admin',              //or client
'seo' => true                //if false it can't produce seo link
);

$phpcat = new php_cat($params);
//var_dump($phpcat);
echo "<span id=\"path\"><a href=\"categories.php?type={$_GET['type']}\">Racine</a> ".$phpcat->separator."</span>";


$data['cat_id'] = isset($_GET['category_id'])?$_GET['category_id']:0; 

$path_row = $phpcat->path($data); //breadcrumb
foreach($path_row as $row){
$ahref = "<a href=\"categories.php?type={$_GET['type']}&category_id={$row['cat_id']}\">"; //you can set also $row['cat_id'];
$a = "</a>";
echo $ahref.$row['cat_name'].$a.$phpcat->separator;
}
?>
<hr />
<?php
/***************************************************************************/
  $add_data['cat_id'] = $_POST['parent'];         //under parent cat_id in mysql table (add under the...)
  $add_data['parent_id'] = $_POST['parent_id'];  //parent_id in mysql table
  $add_data['new_name'] = $_POST['new_name']; //new category name.
  $add_data['dsc'] = $_POST['dsc']; //category description.
  $add_data['type'] = $_POST['type']; //category type.
  $add_data['order'] = $_POST['order']; //category type.
/***************************************************************************/

  //if no record in database.
  if(isset($_POST['add']) && $phpcat->fetch_num() == 0){
  $phpcat->add_cat($add_data);

  //if category has children use add_cat
  }elseif(isset($_POST['add']) && $_POST['children'] > 0){
  $phpcat->add_cat($add_data);

  //if category has no children use add_subcat
  }
  elseif(isset($_POST['addMasse'])){
  	$allData = explode(',',$_POST['catMasse']);
	foreach($allData as $d){
		$adt['new_name'] = $d;
		$adt['cat_id'] = $_POST['parent'];         //under parent cat_id in mysql table (add under the...)
  		$adt['parent_id'] = $_POST['parent_id'];  //parent_id in mysql table
		$adt['type'] = $_POST['type'];  
		if($_POST['children'] > 0)
			$phpcat->add_cat($adt);
		else
			$phpcat->add_subcat($adt);
	}
  }
  elseif(isset($_POST['add']) && $_POST['children'] == 0){
  $phpcat->add_subcat($add_data);

  }elseif($_GET['del']){
  $del_data['cat_id'] = $_GET['cat_id'];
  $phpcat->del_cat($del_data);

  }elseif(isset($_POST['edit'])){
  $update_data['cat_id'] = $_POST['cat_id'];
  $update_data['new_name'] = $_POST['new_name'];
  //$update_data['dsc'] = $_POST['dsc'];
  $update_data['order'] = $_POST['order'];
  $phpcat->update_cat($update_data);
  }
  

?>
<style>
#cate_menu{
	float:left;
}
.current{
	background:#9D216E;
	color:#FFF;
	font-weight:bold;
	padding:5px 10px;
}
</style>
<div id="cate_menu">
<a <?php if($_GET['type']=='0') echo'class="current"';?> href="categories.php?type=0">Produits</a>&nbsp;&nbsp;&nbsp;&nbsp;<a <?php if($_GET['type']=='1') echo'class="current"';?> href="categories.php?type=1">Coupons de réduction</a>&nbsp;&nbsp;&nbsp;&nbsp;<a <?php if($_GET['type']=='2') echo'class="current"';?> href="categories.php?type=2">Évènements magasin</a>
&nbsp;&nbsp;&nbsp;&nbsp;<a <?php if($_GET['type']=='3') echo'class="current"';?> href="categories.php?type=3">Magasins</a>
</div>
<br />
<br />

<table width="545" align="center" class="list_category">
<tr>
<th width="56%"><b>Categories</b></th>
<th width="11%"><b>Ordres</b></th>
<th width="33%"><b>Options</b></th>
</tr>
<?php
$result = $phpcat->list_cat($data,$_GET['type']);          //list categories..
$children = count($result);                  //count how many sub categories ?

if(!empty($result)) {
foreach($result as $row){ ?>

  <tr>
  <td>&nbsp;
  <a href="categories.php?type=<?php echo $_GET['type'];?>&category_id=<?php echo $row['cat_id']?>"><b><?php echo $row['cat_name']?></b>
  </a>
  </td>
	
	<td> <b><?php echo $row['order']?></b> </td>
    
  <td>
  &nbsp;
  <a href="categories.php?type=<?php echo $_GET['type'];?>&category_id=<?php echo $_GET['category_id']?>&cat_id=<?php echo $row['cat_id']?>&del=true">Supprimer</a>
  &nbsp;|&nbsp;
  <a href="categories.php?type=<?php echo $_GET['type'];?>&category_id=<?php echo $_GET['category_id']?>&edit_id=<?php echo $row['cat_id']?>&cat_id=<?php echo $row['cat_id']?>">Modifier</a>
  </td>
  </tr>

  <!-- CAT EDIT START -->
  <?php if($_GET['edit_id'] == $row['cat_id']) {?>
  <tr>
  <td colspan="3">
  <div style="padding:8px;">
  <form action="<?php echo $_SERVER['REQUEST_URI']?>" method="post" name="update_form">
  <input type="text" name="new_name" size="30" value="<?php echo $row['cat_name']?>" />
  <input type="text" name="order" size="30" value="<?php echo $row['order']?>" />
  <!--<textarea name="dsc" cols="35"><?php echo $row['dsc']?></textarea>-->
  <input name="cat_id" type="hidden" value="<?php echo $_GET['cat_id']?>" />
  <input type="submit" name="edit" value="Update" />
  </form>
  </div>
  </td>
  </tr>
  <?php } ?>
  <!-- CAT EDIT END -->

<?php
} //end foreach result..
} else {  //if empty result !!
?>
  <tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  </tr>
<?php
} //empty result end.
?>
</table>

<br>
<!-- ADD CATEGORY TABLE START -->
<form name="form2" method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
<table align="center" class="list_category">
<tr>
<th colspan="2" align="center">
<strong>Ajouter nouvelle 
<?php if($children == 0 && $phpcat->fetch_num() !== 0) {
echo "<font color=red>SubCategory</font>";} else { echo "<font color=red>Category</font>";}?></strong></th>
</tr>

<tr>
<td><b>Parent:</b></td>
<td>
<?php
## PARENT ##
if(!empty($result)) {
echo "<select name=\"parent\" size=\"1\">";
foreach($result as $row){
echo "<option value=\"{$row['cat_id']}\">{$row['cat_name']}</option>";
}
echo "</select>";
}else{
echo "<input name=\"parent\" type=\"hidden\" value=\"{$_GET['category_id']}\">"; //mysql cat_id
}
echo $_GET['category_id'];
## PARENT ID ##
echo "<input name=\"parent_id\" type=\"hidden\" value=\"{$_GET['category_id']}\">"; //mysql parent_id

?>
<input type="hidden" name="type" value="<?php echo $_GET['type']; ?>" />
</td>
</tr>

<!--<tr>
<td><b>Type:</b></td>
<td>
<select name="type">
  <option value="0">Produits </option>
  <option value="1">Coupons de réduction</option>
  <option value="2">évènements magasin</option>
</select> 
</td>
</tr>-->

<tr>
<td><b>Titre de catégorie:</b></td>
<td>
<input name="new_name" type="text" value="" size="35">
</td>
</tr>

<tr>
<tr>
<td><b>Order:</b></td>
<td>
<input name="order" type="text" value="" size="35">
</td>
</tr>

<tr>
<td>&nbsp;</td>
<td>
<input type="hidden" name="children" value="<?php echo $children;?>" />
<input type="submit" name="add" value="Ajouter" />
</td>
</tr>

</table>
</form>
<br />
<br />

<!-- ADD CATEGORY TABLE START -->
<form name="form2" method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
<table align="center" class="list_category">
<tr>
<th colspan="2" align="center">
<strong>Catégories en masse</strong></th>
</tr>

<tr>
<td><b>Parent:</b></td>
<td>
<?php
## PARENT ##
if(!empty($result)) {
echo "<select name=\"parent\" size=\"1\">";
foreach($result as $row){
echo "<option value=\"{$row['cat_id']}\">{$row['cat_name']}</option>";
}
echo "</select>";
}else{
echo "<input name=\"parent\" type=\"hidden\" value=\"{$_GET['category_id']}\">"; //mysql cat_id
}
echo $_GET['category_id'];
## PARENT ID ##
echo "<input name=\"parent_id\" type=\"hidden\" value=\"{$_GET['category_id']}\">"; //mysql parent_id

?>
<input type="hidden" name="type" value="<?php echo $_GET['type']; ?>" />
</td>
</tr>

<!--<tr>
<td><b>Type:</b></td>
<td>
<select name="type">
  <option value="0">Produits </option>
  <option value="1">Coupons de réduction</option>
  <option value="2">évènements magasin</option>
</select> 
</td>
</tr>-->

<tr>
<td><b>Catégories en masse:</b></td>
<td>
<textarea name="catMasse" cols="50" rows="6"></textarea>
</font></td>
</tr>

<tr>
<td>&nbsp;</td>
<td>
<input type="hidden" name="children" value="<?php echo $children;?>" />
<input type="submit" name="addMasse" value="Ajouter" />
</td>
</tr>

</table>
</form>
<!-- ADD CATEGORY TABLE END -->
      </div>
	</div>
</div>
<?php //include("modules/footer.php"); ?>
</body>
</html>