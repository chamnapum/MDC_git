<?php require_once('Connections/magazinducoin.php');
require "class/php_cat.class.php";
require "class/actions.class.php";
require "class/seo.class.inc.php"; ?>
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

// les produits en semaine d'echeance 
mysql_select_db($database_magazinducoin, $magazinducoin);
$query = "SELECT * FROM proposer_cat WHERE id  = ".$_GET['id'];
$proposition = mysql_query($query, $magazinducoin) or die(mysql_error());
$row_proposition = mysql_fetch_assoc($proposition);



$params = array(
'separator'=> '&nbsp; > &nbsp;',
'area' => 'client', //or client
'seo' => true
);

$phpcat = new php_cat($params);

$data['new_name'] = $row_proposition['proposition']; //new category name
$data['dsc'] = ""; //category description.
if($row_proposition['id_cat2'] and $row_proposition['id_cat3']){
	$query = "SELECT cat_id FROM category WHERE parent_id  = ".$row_proposition['id_cat3']." ORDER BY lft DESC";
	$fils = mysql_query($query, $magazinducoin) or die(mysql_error());
	$row_fils = mysql_fetch_assoc($fils);
	$data['cat_id'] = $row_fils['cat_id']?$row_fils['cat_id']:$row_proposition['id_cat3'];
	$data['parent_id'] = $row_proposition['id_cat3'];
	$children = $phpcat->children($data);
	if(count($children) == 0) {
		$phpcat->add_subcat($data);
	}else{
		$phpcat->add_cat($data);
	}
}
else if($row_proposition['id_cat2']){
	$query = "SELECT cat_id, lft FROM category WHERE parent_id  = ".$row_proposition['id_cat2']." ORDER BY lft DESC";
	$fils = mysql_query($query, $magazinducoin) or die(mysql_error());
	$row_fils = mysql_fetch_assoc($fils);
	$data['cat_id'] = $row_fils['cat_id'];
	$data['parent_id'] = $row_proposition['id_cat2']; 
	$phpcat->add_cat($data);
}
else {
	$query = "SELECT cat_id FROM category WHERE parent_id  = ".$row_proposition['id_cat1']." ORDER BY lft DESC";
	$fils = mysql_query($query, $magazinducoin) or die(mysql_error());
	$row_fils = mysql_fetch_assoc($fils);
	$data['cat_id'] = $row_fils['cat_id'];
	$data['parent_id'] = $row_proposition['id_cat1'];
	$phpcat->add_cat($data);
}

/*$children = $phpcat->children($data);
if(count($children) == 0) {
###  if category has got 0 child categories use add_subcat
$phpcat->add_subcat($data);
}else{*/
###  if category has got 1+ child categories use add_cat.
//$phpcat->add_cat($data);
//}

//mysql_query("DELETE FROM proposer_cat WHERE id = ".$_GET['id']);





?>