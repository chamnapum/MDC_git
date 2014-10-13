<?php require_once('../Connections/magazinducoin.php'); ?>
<?php

	$id = $_REQUEST['id'];
	$email = $_REQUEST['email'];
	$sql_pro  = "UPDATE produits SET activate='1' WHERE id='".$id."'";
	$result_pro  = mysql_query($sql_pro ) or die (mysql_error());
	if($result_pro){
		$sql_select1 = "SELECT
							utilisateur.id AS user_id
							, utilisateur.nom
							, utilisateur.prenom
							, utilisateur.email
							, produits.titre
							, produits.id
						FROM
							produits
							INNER JOIN utilisateur 
								ON (produits.id_user = utilisateur.id)
						 WHERE produits.id='".$_GET['id']."' AND utilisateur.email='".$email."'";
		$query_select1 = mysql_query($sql_select1);
		$rs1=mysql_fetch_array($query_select1);
		SendMail_Ownner_produits_approve($rs1['email'],$rs1['nom'],$rs1['prenom'],$rs1['titre']);
	}
?>