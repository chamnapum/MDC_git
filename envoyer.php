<?php require_once('Connections/magazinducoin.php'); ?>
<?php 

$emeteur=$_SESSION['kt_login_id'];
$recepteur=$_GET['id'];
$objet=$_POST['objet'];
$msg=$_POST['msg'];

$requete="insert into message(id_emetteur,id_recepteur,message,objet)values($emeteur,$recepteur,'$msg','$objet')";
$query1=mysql_query($requete);
//echo $requete;
header('location:fiche_photographe.php?id='.$_GET['id']); 


?>