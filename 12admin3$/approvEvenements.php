<?php require_once('../Connections/magazinducoin.php'); ?>
<?php

	$id = $_REQUEST['id'];
	$email = $_REQUEST['email'];
	$sql_pro  = "UPDATE evenements SET approuve='1' WHERE event_id='".$id."'";
	$result_pro  = mysql_query($sql_pro ) or die (mysql_error());
	if($result_pro){
		$sql_select1 = "SELECT 
						  utilisateur.id,
						  utilisateur.nom,
						  utilisateur.prenom,
						  utilisateur.email,
						  evenements.event_id,
						  evenements.titre,
						  evenements.description,
						  magazins.nom_magazin,
						  magazins.adresse,
						  (SELECT 
							nom_region 
						  FROM
							region 
						  WHERE id_region = magazins.region) AS region,
						  (SELECT 
							nom 
						  FROM
							maps_ville 
						  WHERE id_ville = magazins.ville) AS ville 
						FROM
						  magazins 
						  INNER JOIN evenements 
							ON (
							  magazins.id_magazin = evenements.id_magazin
							) 
						  INNER JOIN utilisateur 
							ON (evenements.id_user = utilisateur.id)
						WHERE  evenements.event_id='".$_REQUEST['id']."' AND utilisateur.email='".$email."'";
		$query_select1 = mysql_query($sql_select1);
		$rs1=mysql_fetch_array($query_select1);
		
		$type = 'Evenement';
		//$type = mb_convert_encoding($type1,'HTML-ENTITIES','utf-8');
		$date = date("Y-m-d");
		
		$sql_mail ="SELECT 
					  evenements.event_id,
					  utilisateur.email 
					FROM
					  magazins 
					  INNER JOIN evenements 
						ON (
						  magazins.id_magazin = evenements.id_magazin
						) 
					  INNER JOIN sabonne 
						ON (
						  evenements.id_magazin = sabonne.id_magasin
						) 
					  INNER JOIN utilisateur 
						ON (sabonne.id_user = utilisateur.id)
					WHERE evenements.event_id='".$_REQUEST['id']."'";
		$query_mail = mysql_query($sql_mail);
		$email='';
		while($res=mysql_fetch_array($query_mail)){
			$email .=$res['email'].',';
		}
		SendMail_sabonne($email,$rs1['nom_magazin'],$type,$rs1['titre'],$rs1['description'],$date,$rs1['adresse'],$rs1['ville']);
		SendMail_Ownner_evenements_approve($rs1['email'],$rs1['nom'],$rs1['prenom'],$rs1['titre']);
		
	}
?>