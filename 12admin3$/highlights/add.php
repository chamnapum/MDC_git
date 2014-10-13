<?php
require "php_cat.class.php";

$params = array(
'separator'=> '&nbsp; > &nbsp;',
'area' => 'admin', //or client
'seo' => true
);

$phpcat = new php_cat($params);

$data['new_name'] = ""; //new category name
$data['dsc'] = ""; //category description.
$data['cat_id'] = 1; //add under the this category (add under the id)
$data['parent_id'] = 0; //if it hasn't got parent use 0.
$data['type'] = ""; //new category type

/*  What is the parent id forexample

ca_id  parent_id  cat_name                            depth (level)
 1         0        ELECTRONICS      (1 children)       0
 2         1            -TELEVISIONS (3 children)       1
 3         2                -LCD                        2
 4         2                -TUBE                       2
 5         2                -PLASMA                     2
 6         0        FURNITURE        (0 children)       0
*/

//if you want add category inside TELEVISIONS cat_id= 2,parent_id = 2
//if you want add category inside TELEVISIONS but you want add category under the TUBE cat_id = 4, parent_id = 2
//if you want add category inside TELEVISIONS but you want add category under the LCD cat_id = 3, parent_id = 2
//if you want add category inside LCD cat_id = 3, parent_id = 3

$children = $phpcat->children($data);

if(count($children) == 0) {
###  if category has got 0 child categories use add_subcat
$phpcat->add_subcat($data);
}else{
###  if category has got 1+ child categories use add_cat.
$phpcat->add_cat($data);
}
?>
