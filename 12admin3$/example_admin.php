<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html" charset="utf-8"; />
<title>Php_cat admin area example</title>
<style type="text/css">
body, a, li, td {font-family:arial;font-size:14px;}
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
<body>
<?php
require "../php_cat.class.php";
$params = array(
'separator'=> '&nbsp; / &nbsp;',
'area' => 'admin',              //or client
'seo' => true                //if false it can't produce seo link
);

$phpcat = new php_cat($parametres);
//var_dump($phpcat);
echo "<span id=\"path\"><a href=\"example_admin.php\">ROOT</a> ".$phpcat->separator."</span>";


$data['cat_id'] = $_GET['cat_id'];                   //you can list categories by cat_id

$path_row = $phpcat->path($data); //breadcrumb
foreach($path_row as $row){
$ahref = "<a href=\"example_admin.php?cat_id={$row['cat_id']}\">"; //you can set also $row['cat_id'];
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
/***************************************************************************/

  //if no record in database.
  if(isset($_POST['add']) && $phpcat->fetch_num() == 0){
  $phpcat->add_cat($add_data);

  //if category has children use add_cat
  }elseif(isset($_POST['add']) && $_POST['children'] > 0){
  $phpcat->add_cat($add_data);

  //if category has no children use add_subcat
  }elseif(isset($_POST['add']) && $_POST['children'] == 0){
  $phpcat->add_subcat($add_data);

  }elseif($_GET['del']){
  $del_data['cat_id'] = $_GET['cat_id'];
  $phpcat->del_cat($del_data);

  }elseif(isset($_POST['edit'])){
  $update_data['cat_id'] = $_POST['cat_id'];
  $update_data['new_name'] = $_POST['new_name'];
  $update_data['dsc'] = $_POST['dsc'];
  $phpcat->update_cat($update_data);
  }

?>
<table align="center" class="list_category">
<tr>
<th width="76%"><b>Category</b></th>
<th width="24%"><b>Options</b></th>
</tr>
<?php
$result = $phpcat->list_cat($data);          //list categories..
$children = count($result);                  //count how many sub categories ?

if(!empty($result)) {
foreach($result as $row){ ?>

  <tr>
  <td>&nbsp;
  <a href="example_admin.php?cat_id=<?=$row['cat_id']?>"><b><?=$row['cat_name']?></b>
  </a>
  </td>

  <td>
  &nbsp;
  <a href="example_admin.php?cat_id=<?=$_GET['cat_id']?>&cat_id=<?=$row['cat_id']?>&del=true">Delete</a>
  &nbsp;|&nbsp;
  <a href="example_admin.php?cat_id=<?=$_GET['cat_id']?>&edit_id=<?=$row['cat_id']?>&cat_id=<?=$row['cat_id']?>">Edit</a>
  </td>
  </tr>

  <!-- CAT EDIT START -->
  <? if($_GET['edit_id'] == $row['cat_id']) {?>
  <tr>
  <td colspan="2">
  <div style="padding:8px;">
  <form action="<?=$_SERVER['REQUEST_URI']?>" method="post" name="update_form">
  <input type="text" name="new_name" size="30" value="<?=$row['cat_name']?>" />
  <textarea name="dsc" cols="35"><?=$row['dsc']?></textarea>
  <input name="cat_id" type="hidden" value="<?=$_GET['cat_id']?>" />
  <input type="submit" name="edit" value="Update" />
  </form>
  <div>
  </td>
  </tr>
  <? } ?>
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
<form name="form2" method="post" action="<?=$_SERVER['REQUEST_URI'];?>">
<table align="center" class="list_category">
<tr>
<th colspan="2" align="center">
<strong>Add New <? if($children == 0 && $phpcat->fetch_num() !== 0) {
echo "<font color=red>SubCategory</font>";} else { echo "<font color=red>Category</font>";}?></strong>
</th>
</tr>

<tr>
<td><b>Add under the:</b></td>
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
echo "<input name=\"parent\" type=\"hidden\" value=\"{$_GET['cat_id']}\">"; //mysql cat_id
}
echo $_GET['cat_id'];
## PARENT ID ##
echo "<input name=\"parent_id\" type=\"hidden\" value=\"{$_GET['cat_id']}\">"; //mysql parent_id

?>
</td>
</tr>

<tr>
<td><b>Category Name:</b></td>
<td>
<input name="new_name" type="text" value="" size="35">
</td>
</tr>

<tr>
<td><b>Description:</b></td>
<td>
<textarea name="dsc" cols="35"></textarea>
</font></td>
</tr>

<tr>
<td>&nbsp;</td>
<td>
<input type="hidden" name="children" value="<?=$children;?>" />
<input type="submit" name="add" value="Add Category" />
</td>
</tr>

</table>
</form>
<!-- ADD CATEGORY TABLE END -->
<p>
<center><font size="1"><a href="http://developer.bloggum.com" target="_blank"><?=$phpcat->version();?> - GPL</a>
</font></center>
</p>
</body>
</html>