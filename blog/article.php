<?php session_start();?>
<?php include("includes/connection.php");; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
function getArticleById($id){
	$query_villes = "SELECT titre FROM article WHERE id_article = $id";
	$villes = mysql_query($query_villes) or die(mysql_error());
	$row_villes = mysql_fetch_assoc($villes);
	return $row_villes['titre'];
}
?>
<?php
	$find = array(" ","(",")","é","è","ê","ë","ï","ö","à","Ë","Ê","É","Ä","Â","Ö","Ô","Ï","ô","'",".","/");
	$replace = array("-","","","e","e","e","e","i","o","a","E","E","E","A","A","O","O","I","o","","","");
?>
<?php 
	$query ="SELECT * FROM article WHERE id_article='".$_GET['id']."' ORDER BY date_post DESC";
	$result = mysql_query($query) or die (mysql_error());
	$row = mysql_fetch_array($result);
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="<?php echo $row['description'];?> "/>
<meta name="keywords" content="<?php echo $row['keywords'];?>"/>
	
<title><?php echo $row['titre'];?></title>
<link rel="stylesheet" href="css/style.css" type="text/css"/>
<script src="../template/js/jquery.js" type="text/javascript"></script>
<script src="http://code.jquery.com/jquery-latest.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function () {
	$('#show_cart').mouseover(function() {
		$('#carts').show();
	});
	$('#carts').mouseleave(function() {
		$('#carts').hide();
	});
});
</script>
</head>

<body>
<?php include("includes/header.php"); ?>
<div id="head-menu">
	<?php include("includes/main-menu.php"); ?>
</div>

<div id="content">
    <div class="bar">
    	<a href="../accueil.html">Accueil</a> > <a href="../blog/accueil.html">Blog</a> > <?php echo strtoupper($row['titre']);?>
    </div>
	<div id="content_left"> 
		
        
    	<h1><?php echo strtoupper($row['titre']);?></h1>
        <div class="shadow-img"></div> 
        <img id="img" src="../timthumb.php?src=assets/images/blog/<?php echo $row['image'];?>&amp;z=1&amp;w=778&amp;h=450" alt="<?php echo strtoupper($row['titre']);?>" width="778" height="450" />  
        <div style="clear:both;"></div> 
        
        <div style="margin:0px 20px;">
        	<?php echo str_replace('<br>','<br />',$row['article']);?>
        </div>
           
    </div>

    <div id="content_right">
    	<?php
			$query_pop ="SELECT * FROM article ORDER BY count_article DESC";
			if(isset($_REQUEST['start_pop'])){$start_pop=$_REQUEST['start_pop'];}else{$start_pop=1;}
			if(isset($_REQUEST['nb_par_page_pop'])){$nb_par_page_pop=$_REQUEST['nb_par_page_pop'];}else{$nb_par_page_pop=10;}
			$next_pop = ($start_pop-1) * $nb_par_page_pop;
			$start_pop++;
			$query_liste_pop=$query_pop." LIMIT $next_pop, $nb_par_page_pop";
			$result_pop = mysql_query($query_liste_pop) or die (mysql_error());
		?>
        <div id="content_pop">
			<?php
                while($row_pop = mysql_fetch_array($result_pop)){
            ?>
            <?php $namede=str_replace($find,$replace,(getArticleById($row_pop['id_article'])));?>
            <div class="post-wrapper">
                <a href="../blog/article-<?php echo $row_pop['id_article'];?>-<?php echo $namede;?>.html" class="img-post">
                    <img src="../timthumb.php?src=assets/images/blog/<?php echo $row_pop['image'];?>&amp;z=1&amp;w=150&amp;h=100" alt="<?php echo $row_pop['titre'];?>" width="150" height="100" />
                </a>
                <div class="wrap-title">
                    <a href="../blog/article-<?php echo $row_pop['id_article'];?>-<?php echo $namede;?>.html" class="title-post">
                        <?php echo $row_pop['titre'];?>
                    </a>
                    <div class="triangle_left1"></div>
                </div>
            </div>
            <?php }?>
        </div>
        <div class="navigations">
			<?php
                echo "<a href='index.php?start_pop=$start_pop&amp;nb_par_page_pop=$nb_par_page_pop'>NEXT</a>";
            ?>
        </div>
        
    </div>
</div>

<?php include("includes/footer.php"); ?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js" type="text/javascript"></script>
<script src="../assets/scroll_pages/jquery.infinitescroll.js" type="text/javascript"></script>
<script type="text/javascript">
var $j = jQuery.noConflict();
$j(document).ready(function() {
	$j('#content_show').infinitescroll({ 
		navSelector  : "div.navigation", // selector for the paged navigation (it will be hidden)
		nextSelector : "div.navigation a:first", // selector for the NEXT link (to page 2)
		itemSelector : "#content_show div.box", // selector for all items you'll retrieve 
		loading: {
			finishedMsg: "you've reached the end of post.",
			img: "../assets/scroll_pages/ajax-loader.gif",
			msgText: "En chargement..."
		}
	});
	
	$j('#content_pop').infinitescroll({ 
		navSelector  : "div.navigations", // selector for the paged navigation (it will be hidden)
		nextSelector : "div.navigations a:first", // selector for the NEXT link (to page 2)
		itemSelector : "#content_pop div.post-wrapper", // selector for all items you'll retrieve 
		loading: {
			finishedMsg: "you've reached the end of post.",
			img: "../assets/scroll_pages/ajax-loader.gif",
			msgText: "En chargement..."
		}
	});
});
</script>
</body>
</html>