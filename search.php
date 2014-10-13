<?php
	if (isset($_GET["choice"]) && !isset($_GET["key"])  )
	{
		$choice = $_GET["choice"];
		if ($choice == '0' )
		{
			getProductsList();
		}
		if ($choice == '1' )
		{
			getDetailsProduits();
		}
		if ($choice == '2' )
		{
			getMagasinsList();
		}
		if ($choice == '3' )
		{
			getDetailsMagasin();
		}
		if ($choice == '4' )
		{
			getCouponsList();
		}
		if ($choice == '5' )
		{
			getDetailsCoupons();
		}
		if ($choice == '6' )
		{
			getEvenementsList();
		}
		if ($choice == '7' )
		{
			getDetailsEvenement();
		}
		if ($choice == '8' )
		{
			getProduitsForMagasin();
		}
		if ($choice == '9' )
		{
			getCategories();
		}
	}
	
	if (isset($_GET["choice"]) && isset($_GET["key"])  )
	{
		$choice = $_GET["choice"];
		$key = $_GET["key"];
		if ($choice == '1' )
		{
			SearchCoupons($key);
		}
		if ($choice == '2' )
		{
			searchProduits($key);
		}	
		if ($choice == '3' )
		{
			searchMagasins($key);
		}
	}
	
	function getCategories() {
		mysql_connect("localhost","magasin3_develop","Sikofiko12");
		mysql_select_db("magasin3_bdd");
		//mysql_connect("localhost","root","");
		//mysql_select_db("magasin3_db");
		$q=mysql_query("SELECT cat_name
					FROM category
					WHERE parent_id=0");
		while($e=mysql_fetch_assoc($q))
			$output[]=$e;
		print(json_encode($output));
		mysql_close();
	}
	
	function getDetailsMagasin() {
		mysql_connect("localhost","magasin3_develop","Sikofiko12");
		mysql_select_db("magasin3_bdd");
		//mysql_connect("localhost","root","");
		//mysql_select_db("magasin3_db");
		$q=mysql_query("SELECT id_magazin, nom_magazin, adresse, code_postal, photo1, heure_ouverture, jours_ouverture, description, latlan, nom_region, nom
					FROM magazins, region, maps_ville
					WHERE magazins.ville = maps_ville.id_ville
					AND magazins.region = region.id_region 
					AND id_magazin = '".$_REQUEST['name']."'");
		while($e=mysql_fetch_assoc($q))
			$output[]=$e;
		print(json_encode($output));
		mysql_close();
	}
	
	function getMagasinsList(){
		mysql_connect("localhost","magasin3_develop","Sikofiko12");
		mysql_select_db("magasin3_bdd");
		if ($_REQUEST['searcheAreaDiam']> 0) {
		   $q = "SELECT DISTINCT magazins.id_magazin, nom_magazin, magazins.adresse, code_postal, magazins.photo1, heure_ouverture, jours_ouverture, magazins.description, latlan, nom_region, nom ,cat_name,(((acos(sin((".$_REQUEST['currentLatitude']."*pi()/180)) * sin((SUBSTRING_INDEX(REPLACE(latlan, '(', ''), ',', 1)*pi()/180))+cos((".$_REQUEST['currentLatitude']."*pi()/180)) * cos((SUBSTRING_INDEX(REPLACE(latlan, '(', ''), ',', 1)*pi()/180)) * cos(((".$_REQUEST['currentLongitude']."- SUBSTRING_INDEX(REPLACE(latlan, ')', ''), ',', -1))*pi()/180))))*180/pi())*60*1.1515*1.609344) as distance
				FROM magazins, region, maps_ville, category, produits
				WHERE magazins.ville = maps_ville.id_ville
				AND magazins.region = region.id_region 
				AND magazins.id_magazin  =  produits.id_magazin
				AND produits.categorie  = category.cat_id
				AND  cat_name like '%".$_REQUEST['category']."%' 
				AND  nom_magazin like '%".$_REQUEST['name']."%' 
				HAVING latlan <>  '' 
				AND distance <= ".$_REQUEST['searcheAreaDiam'] ;
		}
		else{ $q = "SELECT DISTINCT magazins.id_magazin, nom_magazin, adresse, code_postal, magazins.photo1, heure_ouverture, jours_ouverture, magazins.description, latlan, nom_region, nom
					FROM magazins, region, maps_ville,category, produits
					WHERE magazins.ville = maps_ville.id_ville
					AND magazins.region = region.id_region 
					AND magazins.id_magazin  =  produits.id_magazin
					AND produits.categorie  = category.cat_id
					AND  cat_name like '%".$_REQUEST['category']."%' 
					AND nom_magazin like '%".$_REQUEST['name']."%'"; 
		}
		if($_REQUEST['startRow'] <> 0 && $_REQUEST['limit'] <> 0)
		   $q .= " LIMIT ".$_REQUEST['startRow'].",".$_REQUEST['limit'];
		else if($_REQUEST['limit'] <> 0)
		   $q .= " LIMIT ".$_REQUEST['limit'];
		$q=mysql_query($q);
		while($e=mysql_fetch_assoc($q))
			$output[]=$e;
		print(json_encode($output));
		mysql_close();
	}

	function getProductsList(){
		mysql_connect("localhost","magasin3_develop","Sikofiko12");
		mysql_select_db("magasin3_bdd");
		if ($_REQUEST['searcheAreaDiam']> 0) {
		   $q = "SELECT  produits.id,  produits.titre,  produits.reference, produits.photo1, latlan ,(((acos(sin((".$_REQUEST['currentLatitude']."*pi()/180)) * sin((SUBSTRING_INDEX(REPLACE(latlan, '(', ''), ',', 1)*pi()/180))+cos((".$_REQUEST['currentLatitude']."*pi()/180)) * cos((SUBSTRING_INDEX(REPLACE(latlan, '(', ''), ',', 1)*pi()/180)) * cos(((".$_REQUEST['currentLongitude']."- SUBSTRING_INDEX(REPLACE(latlan, ')', ''), ',', -1))*pi()/180))))*180/pi())*60*1.1515*1.609344) as distance
				FROM produits, magazins,category 
				WHERE produits.id_magazin=magazins.id_magazin 
				AND produits.categorie  = category.cat_id
				AND  cat_name like '%".$_REQUEST['category']."%' 
				AND  produits.titre like '%".$_REQUEST['name']."%' 
				HAVING latlan <>  '' 
				AND distance <= ".$_REQUEST['searcheAreaDiam'] ;
		}
		else{ $q = "SELECT id, titre, reference,produits.photo1, latlan 
					from produits, magazins ,category 
					WHERE produits.id_magazin=magazins.id_magazin 
					AND produits.categorie  = category.cat_id
					AND  cat_name like '%".$_REQUEST['category']."%' 
					AND produits.titre like '%".$_REQUEST['name']."%'"; }
		if($_REQUEST['startRow'] <> 0 && $_REQUEST['limit'] <> 0)
		   $q .= " LIMIT ".$_REQUEST['startRow'].",".$_REQUEST['limit'];
		else if($_REQUEST['limit'] <> 0)
		   $q .= " LIMIT ".$_REQUEST['limit'];
		$q=mysql_query($q);
		while($e=mysql_fetch_assoc($q))
			$output[]=$e;
		print(json_encode($output));
		mysql_close();
	}
	
	function getDetailsProduits() {
		mysql_connect("localhost","magasin3_develop","Sikofiko12");
		mysql_select_db("magasin3_bdd");
		//mysql_connect("localhost","root","");
		//mysql_select_db("magasin3_db");
		$q=mysql_query("SELECT titre, reference, produits.photo1, latlan, cat_name, id, prix, nom_magazin, produits.description, magazins.id_magazin
		FROM produits, magazins, category
		WHERE produits.id_magazin=magazins.id_magazin 
		AND produits.categorie = category.cat_id
		AND id = '".$_REQUEST['name']."'");
		while($e=mysql_fetch_assoc($q))
			$output[]=$e;
		print(json_encode($output));
		mysql_close();
	}
	

	function getCouponsList(){
		mysql_connect("localhost","magasin3_develop","Sikofiko12");
		mysql_select_db("magasin3_bdd");
		if ($_REQUEST['searcheAreaDiam']> 0) {
		   $q = "SELECT id_coupon, titre, reduction, latlan, photo1 ,(((acos(sin((".$_REQUEST['currentLatitude']."*pi()/180)) * sin((SUBSTRING_INDEX(REPLACE(latlan, '(', ''), ',', 1)*pi()/180))+cos((".$_REQUEST['currentLatitude']."*pi()/180)) * cos((SUBSTRING_INDEX(REPLACE(latlan, '(', ''), ',', 1)*pi()/180)) * cos(((".$_REQUEST['currentLongitude']."- SUBSTRING_INDEX(REPLACE(latlan, ')', ''), ',', -1))*pi()/180))))*180/pi())*60*1.1515*1.609344) as distance
				FROM coupons, magazins,category 
				WHERE coupons.id_magasin=magazins.id_magazin
				AND coupons.categories=category.cat_id
				AND  cat_name like '%".$_REQUEST['category']."%' 
				AND  titre like '%".$_REQUEST['name']."%' 
				HAVING latlan <>  '' 
				AND distance <= ".$_REQUEST['searcheAreaDiam'] ;
		}
		else{ $q = "SELECT id_coupon, titre, reduction, latlan, photo1  
					FROM coupons, magazins,category  
					WHERE coupons.id_magasin=magazins.id_magazin 
					AND coupons.categories=category.cat_id
					AND  cat_name like '%".$_REQUEST['category']."%' 
					AND titre like '%".$_REQUEST['name']."%'"; }
		if($_REQUEST['startRow'] <> 0 && $_REQUEST['limit'] <> 0)
		   $q .= " LIMIT ".$_REQUEST['startRow'].",".$_REQUEST['limit'];
		else if($_REQUEST['limit'] <> 0)
		   $q .= " LIMIT ".$_REQUEST['limit'];
		$q=mysql_query($q);
		while($e=mysql_fetch_assoc($q))
			$output[]=$e;
		print(json_encode($output));
		mysql_close();
	}
	
	function getDetailsCoupons(){
		mysql_connect("localhost","magasin3_develop","Sikofiko12");
		mysql_select_db("magasin3_bdd");
		$q=mysql_query("SELECT id_magazin, reduction, DATE_FORMAT(date_debut,  GET_FORMAT(DATE, 'EUR')) as date_debut , DATE_FORMAT(date_fin,  GET_FORMAT(DATE, 'EUR')) as date_fin, titre, photo1, nom_magazin,  latlan, coupons.description 
						FROM coupons, magazins
						WHERE coupons.id_magasin=magazins.id_magazin 
						AND id_coupon = '".$_REQUEST['name']."'");
		while($e=mysql_fetch_assoc($q))
			$output[]=$e;
		print(json_encode($output));
		mysql_close();
	}
	
	
	function getEvenementsList(){
		mysql_connect("localhost","magasin3_develop","Sikofiko12");
		mysql_select_db("magasin3_bdd");
		if ($_REQUEST['searcheAreaDiam']> 0) {
		   $q = "SELECT event_id, titre, latlan, photo1,nom_magazin ,(((acos(sin((".$_REQUEST['currentLatitude']."*pi()/180)) * sin((SUBSTRING_INDEX(REPLACE(latlan, '(', ''), ',', 1)*pi()/180))+cos((".$_REQUEST['currentLatitude']."*pi()/180)) * cos((SUBSTRING_INDEX(REPLACE(latlan, '(', ''), ',', 1)*pi()/180)) * cos(((".$_REQUEST['currentLongitude']."- SUBSTRING_INDEX(REPLACE(latlan, ')', ''), ',', -1))*pi()/180))))*180/pi())*60*1.1515*1.609344) as distance
				FROM evenements, magazins,category  
				WHERE evenements.id_magazin=magazins.id_magazin
				AND evenements.category_id=category.cat_id
				AND  cat_name like '%".$_REQUEST['category']."%' 
				AND  titre like '%".$_REQUEST['name']."%' 
				HAVING latlan <>  '' 
				AND distance <= ".$_REQUEST['searcheAreaDiam'] ;
		}
		else{ $q = "SELECT event_id, titre, latlan, photo1,nom_magazin  
					FROM evenements, magazins, category  
					WHERE evenements.id_magazin=magazins.id_magazin
					AND evenements.category_id=category.cat_id
					AND  cat_name like '%".$_REQUEST['category']."%' 
					AND  titre like '%".$_REQUEST['name']."%'"; 
		}
		if($_REQUEST['startRow'] <> 0 && $_REQUEST['limit'] <> 0)
		   $q .= " LIMIT ".$_REQUEST['startRow'].",".$_REQUEST['limit'];
		else if($_REQUEST['limit'] <> 0)
		   $q .= " LIMIT ".$_REQUEST['limit'];
		$q=mysql_query($q);
		while($e=mysql_fetch_assoc($q))
			$output[]=$e;
		print(json_encode($output));
		mysql_close();
	}
	
	function getDetailsEvenement(){
		mysql_connect("localhost","magasin3_develop","Sikofiko12");
		mysql_select_db("magasin3_bdd");
		$q=mysql_query("SELECT magazins.id_magazin, titre, evenements.description, DATE_FORMAT(date_debut,  GET_FORMAT(DATE, 'EUR')) as date_debut, DATE_FORMAT(date_fin,  GET_FORMAT(DATE, 'EUR')) as date_fin, latlan, photo1, nom_magazin 
						FROM evenements, magazins 
						WHERE evenements.id_magazin=magazins.id_magazin 
						AND event_id = '".$_REQUEST['name']."'");
		while($e=mysql_fetch_assoc($q))
			$output[]=$e;
		print(json_encode($output));
		mysql_close();
	}
	
	function getMagasinsByLatLong($lat,$lng,$dist,$start,$limit) {
		mysql_connect("localhost","magasin3_develop","Sikofiko12");
		mysql_select_db("magasin3_bdd");
	   
	   if ($dist > 0) {
		   $sql = "SELECT *,(((acos(sin((".$lat."*pi()/180)) * sin((SUBSTRING_INDEX(REPLACE(latlan, '(', ''), ',', 1)*pi()/180))+cos((".$lat."*pi()/180)) * cos((SUBSTRING_INDEX(REPLACE(latlan, '(', ''), ',', 1)*pi()/180)) * cos(((".$lng."- SUBSTRING_INDEX(REPLACE(latlan, ')', ''), ',', -1))*pi()/180))))*180/pi())*60*1.1515*1.609344) as distance FROM `magazins` HAVING latlan <>  '' AND distance <= ".$dist." ORDER BY distance ASC";
	   }else{
		   $sql = "SELECT nom_magazin, adresse, code_postal, photo1, heure_ouverture, jours_ouverture, description, latlan, nom_region, nom FROM magazins, region, maps_ville  WHERE magazins.ville = maps_ville.id_ville AND magazins.region = region.id_region ";
	   }
	   if($start <> 0 && $limit <> 0)
		   $sql .= " LIMIT $start,$limit";
	   else if($limit <> 0)
		   $sql .= " LIMIT $limit";
	   $res = mysql_query($sql,$con) or die(mysql_error());
	   $arr = array();
	   while($obj = mysql_fetch_object($res)) {
		   $arr[] = $obj;
	   }
       echo '{"magasins":'.json_encode($arr).'}';
       mysql_close($con);
       if ($result) {
           return  $result;
       }else{
           return  0;
       }
   }
   
   	function getProduitsForMagasin() {
		mysql_connect("localhost","magasin3_develop","Sikofiko12");
		mysql_select_db("magasin3_bdd");
		//mysql_connect("localhost","root","");
		//mysql_select_db("magasin3_db");
		$q="SELECT id, titre, reference, produits.photo1, latlan 
						FROM produits, magazins 
						WHERE produits.id_magazin=magazins.id_magazin 
						AND produits.id_magazin = '".$_REQUEST['magId']."'
						AND titre like '%".$_REQUEST['name']."%'";
		if($_REQUEST['startRow'] <> 0 && $_REQUEST['limit'] <> 0)
		   $q .= " LIMIT ".$_REQUEST['startRow'].",".$_REQUEST['limit'];
		else if($_REQUEST['limit'] <> 0)
		   $q .= " LIMIT ".$_REQUEST['limit'];
		$q=mysql_query($q);
		while($e=mysql_fetch_assoc($q))
			$output[]=$e;
		print(json_encode($output));
		mysql_close();
	}
?>


