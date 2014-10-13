<?php require_once('../Connections/magazinducoin.php'); ?>
<?php

	$id = $_REQUEST['id'];
	$email = $_REQUEST['email'];
	$sql_pro  = "UPDATE coupons SET approuve='1' WHERE id_coupon='".$id."'";
	$result_pro  = mysql_query($sql_pro ) or die (mysql_error());
	if($result_pro){
		$sql_select1 = "SELECT
							utilisateur.id
							, utilisateur.nom
							, utilisateur.prenom
							, utilisateur.email
							, coupons.id_coupon
							, coupons.titre
							, coupons.description
							, magazins.nom_magazin
							, magazins.adresse
							,(SELECT nom_region FROM region WHERE id_region = magazins.region) AS region
							,(SELECT nom FROM maps_ville WHERE id_ville = magazins.ville) AS ville
						FROM
							magazins
							INNER JOIN coupons 
								ON (magazins.id_magazin = coupons.id_magasin)
							INNER JOIN utilisateur 
								ON (coupons.id_user = utilisateur.id)
						 WHERE coupons.id_coupon='".$_REQUEST['id']."' AND utilisateur.email='".$email."'";
		$query_select1 = mysql_query($sql_select1);
		$rs1=mysql_fetch_array($query_select1);
		
		
		$type = 'Coupons';
		$date = date("Y-m-d");
		
		$sql_mail ="SELECT
						coupons.id_coupon
						, utilisateur.email
					FROM
						magazins
						INNER JOIN coupons 
							ON (magazins.id_magazin = coupons.id_magasin)
						INNER JOIN sabonne 
							ON (coupons.id_magasin = sabonne.id_magasin)
						INNER JOIN utilisateur 
							ON (sabonne.id_user = utilisateur.id)
					WHERE coupons.id_coupon='".$_REQUEST['id']."'";
		$query_mail = mysql_query($sql_mail);
		$email='';
		while($res=mysql_fetch_array($query_mail)){
			$email .=$res['email'].',';
		}
		
		SendMail_sabonne($email,$rs1['nom_magazin'],$type,$rs1['titre'],$rs1['description'],$date,$rs1['adresse'],$rs1['ville']);
		SendMail_Ownner_Coupon_approve($rs1['email'],$rs1['nom'],$rs1['prenom'],$rs1['titre']);
	}
?>