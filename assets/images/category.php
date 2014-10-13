<?php render('_header',array('title'=>$title))?>
<nav id="menu" style="margin-top:60px;">
		<ul>
			<?php 
			$action = "";
			if(isset($_GET['action']))
				$action = "&action=".$_GET['action'];
			foreach($realCat as $cat) :
					if(isset($cat['p'])){
						if(count($cat['f'])){
		    				echo '<li><a href="#">'.$cat['p']->cat_name.'</a>';
							echo "<ul>";
							echo '<li';
							if(@$_GET['filtre1'] == $cat['p']->cat_id and !isset($_GET['filtre2'])) echo ' class="mm-selected"';
							echo '><a data-ajax="false" href="?category='.$_GET['category'].'&filtre1='.$cat['p']->cat_id.$action.'">Tous</a>';
							foreach($cat['f'] as $f){
									echo '<li ';
									if(@$_GET['filtre1'] == $cat['p']->cat_id and @$_GET['filtre2'] == $f->cat_id) echo 'class="mm-selected" ';
									echo '><a data-ajax="false" href="?category='.$_GET['category'].'&filtre1='.$cat['p']->cat_id.'&filtre2='.$f->cat_id.$action.'" ';
									echo '>'.$f->cat_name.'</a>';
							}
							echo "</ul>";
						}
						else{
							echo '<li ';
							if(@$_GET['filtre1'] == $cat['p']->cat_id) echo 'class="mm-selected"';
							echo '><a data-ajax="false" href="?category='.$_GET['category'].'&filtre1='.$cat['p']->cat_id.$action.'">'.$cat['p']->cat_name.'</a>';
						}
					 echo "</li>";	
					}
			endforeach; ?>
               
		</ul>
</nav>
<?php if($_GET['action'] == "evenements") : ?>
	<div id="calendar"></div>
</div>
<script type="text/javascript">
$( document ).ready(function() {
var calEvents = [
	<?php foreach($products as $p) : ?>
	{"eid":"someID <?php echo $p->event_id; ?>","name":"<?php echo utf8_decode($p->titre) ?>","url":"?id=<?php echo $p->event_id; ?>&action=evenements","start":new Date("<?php echo $p->date_debut; ?>T00:00:00.000Z"),"end":new Date("<?php echo $p->date_fin; ?>T10:00:00.000Z"),"summary":"<?php  $desc = utf8_decode(strip_tags($p->description)); 
	echo str_replace(array("\r\n", "\r"), " ", $desc);?>"},
	<?php endforeach; ?>
];


   $("#calendar").jqmCalendar({
      events : calEvents,
      months : ["Janvier", "Fevrier", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"],
      days : ["Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa"],
      startOfWeek : 1
   });
});
</script>
<?php else : ?>
<script type="text/javascript">
var s = 0;
var l = 10;
var action="<?php echo @$_GET['action']; ?>";
var filtre1=""
var filtre2="";
var ville="";

function chargerplus(){
	s = s + 10;
	l = l + 10;
	getNewsList(s,l,action,filtre1,filtre2,ville);
}
function getNewsList(start,limit,action,filtre1,filtre2,ville) {
	myUrl = "?ajax=13&name=Lorraine&start="+start+"&limit="+limit;
	if(action != "") 	myUrl += "&type="+action;
	if(filtre1 != "") 	myUrl += "&filtre1="+filtre1;
	if(filtre2 != "") 	myUrl += "&filtre2="+filtre2;
	if(ville != "") 	myUrl += "&ville="+ville;
	
    if(start == 0) {$('#sublist li').remove();}
	$.ajax({
		type: "GET",
		url: myUrl,
		success: function(data) {
			$("#sublist").append(data);
			$('#sublist').listview('refresh');
		}
	});
}
/*function filtrerParVille(v){
	ville = v;
	getNewsList(0,10,action,filtre1,filtre2,ville);
}
*/

$( document ).ready(function() {
	$("#voirplus").on('click', function() {
		chargerplus();
	});
});
</script>
<ul data-role="listview" id="sublist" >
<?php if(count($products)) :
		foreach($products as $p) : ?>
    <li>
        <a data-ajax="false" href="?id=<?php echo $p->id; echo isset($_GET['action'])?"&action=".$_GET['action']:""; ?>">
        	<?php if($p->photo1!='empty_name'): ?>
            <img src="http://www.magasinducoin.fr/timthumb.php?src=assets/images/<?php echo isset($_GET['action'])?$_GET['action']:"magasins"; ?>/<?php echo $p->photo1 ?>&z=1&w=120&h=120" alt="<?php echo $p->titre ?>">
            <?php endif;?>
            <h2><?php echo utf8_decode($p->titre) ?></h2>
            <p><?php echo utf8_encode($p->nom_magazin); ?></p>
            <p><?php echo $p->cat_name; ?></p>
				<?php if(isset($_GET['action']) and $_GET['action'] == 'magasins'): ?>
                    <p><?php echo $p->nb_coupons; ?> Coupon(s) de réduction</p>
                    <p><?php echo $p->nb_events; ?> &Eacute;vènement(s)</p>
                    <p><?php echo $p->nb_produits; ?> Produit(s)</p>
                <?php endif; ?>
            <?php /*?><div class="distance"><?php echo formatFloat($p->distance); ?> Km</div><?php */?>
            <div class="ville"><?php echo $p->nom_ville ?></div>
        </a>
    </li>
<?php endforeach;
else :
	echo "<li>Liste vide !</li>";
endif; ?>
</ul>
<?php if(count($products)) : ?><br>
<br>
	<a id="voirplus" class="ui-btn" data-icon="arrow-d" data-ajax="false">Plus</a>
<?php endif; ?>
<?php endif; ?>	
</div>
