<?php require_once('../Connections/magazinducoin.php'); ?>
<?php

	$id = $_REQUEST['id'];
	$email = $_REQUEST['email'];
	$sql_pro  = "UPDATE magazins SET approuve='1' WHERE id_magazin='".$id."'";
	$result_pro  = mysql_query($sql_pro ) or die (mysql_error());
	if($result_pro){
		$sql_select1 = "SELECT
							utilisateur.id
							, utilisateur.nom
							, utilisateur.prenom
							, utilisateur.email
							, magazins.nom_magazin
							, magazins.id_magazin
						FROM
							magazins
							INNER JOIN utilisateur 
								ON (magazins.id_user = utilisateur.id)
						 WHERE magazins.id_magazin='".$_GET['id']."' AND utilisateur.email='".$email."'";
		$query_select1 = mysql_query($sql_select1);
		$rs1=mysql_fetch_array($query_select1);
		SendMail_Ownner_Magasin_approve($rs1['email'],$rs1['nom'],$rs1['prenom'],$rs1['nom_magazin']);
	}
?>