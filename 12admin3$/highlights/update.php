<?php
require "php_cat.class.php";

$params = array(
'separator'=> '&nbsp; > &nbsp;',
'area' => 'admin', //or client
'seo' => true
);

$phpcat = new php_cat($params);

$data['new_name'] = "LASER"; //new category name
$data['dsc'] = ""; //some info about your cat. //category description
//$data['cat_name'] = "TELEVISIONS"; //set a parent cat_name (old category name) or cat_id
$data['cat_id'] = "2"; //use cat_id for prevent collision

$phpcat->update_cat($data);

?>
