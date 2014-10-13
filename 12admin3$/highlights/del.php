<?php
require "php_cat.class.php";

$params = array(
'separator'=> '&nbsp; > &nbsp;',
'area' => 'admin', //or client
'seo' => true
);

$phpcat = new php_cat($params);

//$data['cat_name'] = "MP3 PLAYERS";  //prevent for collision use cat_id
$data['cat_id'] = "8";   //use cat_id for all operations (del,update,add)
$phpcat->del_cat($data);

?>

