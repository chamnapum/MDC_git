<?php require_once('Connections/magazinducoin.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

// les produits en semaine d'echeance 
mysql_select_db($database_magazinducoin, $magazinducoin);
$query = "SELECT produits.titre, produits.id, utilisateur.nom, utilisateur.prenom, utilisateur.email FROM 
 (produits LEFT JOIN utilisateur ON utilisateur.id = produits.id_user)
 WHERE date_echance = '".date('Y-m-d',mktime(0,0,0,date('m'),date('d')+7,date('Y')))."'";
$semaine = mysql_query($query, $magazinducoin) or die(mysql_error());
while($row_semaine = mysql_fetch_assoc($semaine)){
	envoyer_semaine($row_semaine);
}


// efacer tous les produits périmé
mysql_query("DELETE FROM produits WHERE date_echance = '".date('Y-m-d')."'");



function envoyer_semaine($row_semaine){
	// On initialise les variables
	$lien = $_SERVER['HTTP_HOST'].'/dev/produitForm.php?republier=1&id='.$row_semaine['id'];
	$destinataire = $row_semaine['email'];
	$objet = "Votre produit vient d'étres périmé." ;
	$message = '<html>
	<head>
	<title>Votre produit vient d\'étres périmé</title>
	</head>
	<body>
	<p>Bonjour '.$row_semaine['prenom'].' '.$row_semaine['nom'].'</p>
	<p>Le produit <strong>'.$row_semaine['titre'].'</strong> vient d\'étres périmé au bout d\'une semaine, Veuillez le republier on cliquant sur ce lien <a href="'.$lien.'">'.$lien.'</a></p>
	<p></p>
	<p><strong>L\'&Eacute;quipe Magasinducoin.com</strong></p>
	</body>
	</html>
	';


	/* Si l’on veut envoyer un mail au format HTML, il faut configurer le type Content-type. */
	$headers = "MIME-Version: 1.0\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\n";
	
	/* Quelques types d’entêtes : errors, From cc's, bcc's, etc */
	$headers .= "From: Magasin Du Coin <belgaila6@hotmail.com>\n";
	
	
	// On envoi l’email
	if ( mail($destinataire, $objet, $message, $headers) ) die( "Envoi du mail réussi.");
	else die( "Echec de l’envoi du mail.");
}


?>