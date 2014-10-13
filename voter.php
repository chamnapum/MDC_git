<?php require_once('Connections/magazinducoin.php'); ?>
<?php 
if($_POST['vote']){
$commenteur=$_SESSION['kt_login_id'];
$vote=$_POST['vote'];
$id=$_GET['id'];
$com=$_POST['txt'];
$requete="insert into vote(id_vote,id_utilisateur,valeur,commentaire,id_commenteur)values('',$id,$vote,'$com',$commenteur)";
$query1=mysql_query($requete);
$rkt="SELECT sum(vote.valeur) AS somme, count(vote.id_utilisateur) AS nbr FROM vote where id_utilisateur=$id";
$query=mysql_fetch_array(mysql_query($rkt));
$somme=$query['somme'];
$nbr=$query['nbr'];
$note=$somme/$nbr;
//echo $note;
$update=mysql_query("update utilisateur set note=$note where id=$id");
header('location:fiche_photographe.php?id='.$id); 

}
?>