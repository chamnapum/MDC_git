<?php require_once('Connections/magazinducoin.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Resultat de recherche</title>

</head>
<link rel="stylesheet" href="stylesheets/style2.css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/prototype/1/prototype.js"></script>
<script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/scriptaculous/1/scriptaculous.js'></script>
<script type="text/javascript" src="lightview/js/lightview.js"></script>
<link rel="stylesheet" type="text/css" href="lightview/css/lightview.css" />
<link rel="stylesheet" type="text/css" href="lightview/css/style.css" />
<body>
<style>
.box{
width:1000px;
overflow:hidden;
background-color:#F3F3F3;
border:1px solid #C0C0C0;}

.box_img{
width:100px;
padding:10px;

float:left;}

.box_desc{
width:740px;
float:left;
padding:19px;
background-color:#F2FDFD;}

.box_prix{
width:100px;
padding-top:45px;
padding-bottom:45px;
float:left;
text-align:center;

}
</style>
<?php   
$sscat=$_POST['categorie'];
$cat=$_POST['sous_categorie'];
/*echo $sscat."/";
echo $cat."/";
echo $motcle;*/

$motcle=$_POST['mot_cle'];
$query_liste_produit = "SELECT * from produits ";
$tab = array();

if($cat) $tab[] = "categorie = ".$cat;
if($sscat) $tab[] = " sous_categorie = '$sscat' ";
if($motcle) $tab[] = " titre LIKE '%$motcle%' or description LIKE '%$motcle%'";
$where = "";
if(count($tab)) $where = "where ".implode(' and ',$tab);
$query_liste_produit .= $where;
$query_liste_produit .= " ORDER BY id DESC";

//echo $query_liste_produit;  
$rkt= mysql_query($query_liste_produit);
while($liste = mysql_fetch_assoc($rkt)){ ?>

<div class="box">
    <div class="box_img"> <img src="assets/images/magasins/<?php echo $liste['photo1']; ?> " width="95" height="90" /></div>
    <div class="box_desc" ><?php echo $liste['titre']; ?><p> <?php  echo $liste['description']; ?></p></div>
    <div class="box_prix"> 
		<?php echo $liste['prix']; ?>
        <?php if($liste['en_stock']==1){echo "<br>En stock";} ?>
    
    </div>
</div>

<?php } ?>
</body>
</html>
