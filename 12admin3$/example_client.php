<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html" charset="utf-8"; />
<title>Php_cat example</title>
<style type="text/css">
body, a, li, td {font-family:arial;font-size:14px;}
hr{border:0;width:100%;color:#d8d8d8;background-color:#d8d8d8;height:1px;}
#path{font-weight:bold;}
</style>
</head>
<body>
<?php
require "../php_cat.class.php";

$params = array(
'separator'=> '&nbsp; / &nbsp;',
'area' => 'client', //or admin
'seo' => true
);

$phpcat = new php_cat($params);

//$data['cat_id'] = "";   -->>  //you can list categories by cat_id
//$data['cat_name'] = ""; -->>    //you can list categories by cat_name
$data['cat_link'] = $_GET['cat_link'];  //you can list categories by cat_link
echo "<span id=\"path\">";
echo "<a href=\"example_client.php\">ROOT</a>".$phpcat->separator;

$path_row = $phpcat->path($data);
foreach($path_row as $row){
$ahref = "<a href=\"example_client.php?cat_link={$row['cat_link']}\">"; //you can set also $row['cat_id'];
$a = "</a>";
echo $ahref.$row['cat_name'].$a.$phpcat->separator;
}

echo "</span>";
echo "<hr>";

$result = $phpcat->list_cat($data);
//print_r($result);

foreach($result as $row){
$ahref = "<a href=\"example_client.php?cat_link={$row['cat_link']}\">";
$a = "</a>";
echo $ahref.$row['cat_name'].$a."<br />";
}
?>

</body>
</html>
