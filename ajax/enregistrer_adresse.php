<?php require_once('Connections/magazinducoin.php'); ?>
<?php

setcookie('kt_adresse', $_POST['adresse'], (time() + (3600*24*365)));

header('Location: rechercher.php?adresse='.$_COOKIE['kt_adresse'].'&rayon=999&mot_cle=&categorie=&sous_categorie=');

?>