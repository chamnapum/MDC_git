<?php require_once('Connections/magazinducoin.php'); ?>
<?php

$adresse = $_GET['adresse'];
setcookie('kt_adresse', NULL, -1); 
setcookie('kt_adresse', $adresse, (time() + (3600*24*365)),'/');
$_COOKIE['kt_adresse'] = $adresse;

header('Location: rechercher.php?adresse='.$_COOKIE['kt_adresse'].'&rayon=999&mot_cle=&categorie=&sous_categorie=');

?>