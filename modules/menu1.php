<?php 
require "class/php_cat.class.php";
$params = array(
'separator'=> '&nbsp; > &nbsp;',
'area' => 'client', //or admin
'seo' => false
);

$phpcat = new php_cat($params);
$map_result = $phpcat->map();
/*for($i=0;$i<count($map_result);$i++){
	if($map_result[$i]['depth']>2)
		unset($map_result[$i]);
}*/
//die(print_r($map_result));
?><ul id="menu">
<?php 
$i = 0;
$j = 0;
$depth_1_closed = true;
$parent = 0;
foreach($map_result as $cats) {
//echo $cats['depth'] .':'.$cats['cat_name'];

	switch($cats['depth']) {
		case 0:
			if($i<20 and $i>0){
				echo "</ul>";
			}
			$i=0;
			if(!$depth_1_closed) echo '</div></li>';
			echo '<li><a href="rechercher.php?adresse=&rayon=999&mot_cle=&categorie='.$cats['cat_id'].'&sous_categorie=" class="drop">'.$cats['cat_name'].'</a><div class="dropdown_4columns  class'.$j.'">';
			$parent = $cats['cat_id'];
			$j++;
			$depth_1_closed = false;
			break;
			
		default:
			if($i==0){
				echo '<ul class="bloc">';
			}
			if($cats['depth']==1){
				$sous_parent = $cats['cat_id'];
				$sous_parent2 = "";
			}
			else if($cats['depth']==2)
				$sous_parent2 = $cats['cat_id'];
			
			/*if($cats['cat_id'] == 902){
				$sous_parent = $cats['cat_id'];
				echo '<li class="niv1"><a href="rechercher.php?adresse=&rayon=999&mot_cle=&categorie='.$parent.'&sous_categorie='.$sous_parent.'&sous_categorie2=">Cuisine</a></li>';
			}*/
			echo '<li class="niv'.($cats['depth']>2 ? 2 : $cats['depth']).'"><a href="rechercher.php?adresse=&rayon=999&mot_cle=&categorie='.$parent.'&sous_categorie='.$sous_parent.'&sous_categorie2='.$sous_parent2.'">'.$cats['cat_name'].'</a></li>';
			if($i==17){
				echo '</ul>';
				$i=-1;
			}
			$i++;
			break;
	}
}
echo '</div></li>';
?>

</ul>

