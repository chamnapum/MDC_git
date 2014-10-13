<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html" charset="utf-8"; />
<title>Php_cat example</title>
<style type="text/css">
body, a, li, td {font-family:arial;font-size:14px;}
hr{border:0;width:100%;color:#d8d8d8;background-color:#d8d8d8;height:1px;}
</style>
</head>
<body>
<?php
require "../php_cat.class.php";

$params = array(
'separator'=> '&nbsp; > &nbsp;',
'area' => 'client', //or admin
'seo' => true
);

$phpcat = new php_cat($params);
$map_result = $phpcat->map();

foreach($map_result as $row){
$output = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $row['depth']);
$output.= $row['cat_name']."&nbsp;&nbsp; <font color=red>Level:".$row['depth']."</font><br />";
echo $output;
}

?>
</body>
</html>
