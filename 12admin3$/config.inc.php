<?php
#############################################
# FIRST OF ALL CREATE SQL !! use SQL.sql file.
#############################################

## Mysql connection.....
$link = mysql_connect("localhost","magasin3_develop","Sikofiko12","magasin3_bdd") or die (mysql_error());
mysql_select_db("magasin3_bdd",$link) or die (mysql_error());
mysql_query("SET NAMES 'utf8'");

###########################################
# YOU CAN CONFIGURE TABLES AND FIELD NAMES!!
###########################################
// table
define ('TABLE_NAME', 'category');
// fields
define ('CAT_ID', 'cat_id');
define ('PARENT_ID', 'parent_id');
define ('CAT_NAME', 'cat_name');
define ('DSC', 'dsc'); //description..
define ('CAT_LINK', 'cat_link');
define ('LEFT', 'lft');
define ('RIGHT', 'rgt');
//define ('TOP', 'tp'); //deprecated !


?>