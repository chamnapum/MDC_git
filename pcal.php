<?php require_once('Connections/magazinducoin.php'); 
if($_REQUEST['region'] <= 0){ 
	echo'<script>window.location="index.php";</script>';
}

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

$finds = array(" ","(",")","é","è","ê","ë","ï","ö","à","Ë","Ê","É","Ä","Â","Ö","Ô","Ï","ô","'",".","/");
$replaces = array("-","","","e","e","e","e","i","o","a","E","E","E","A","A","O","O","I","o","","","");
$nom_region=str_replace($finds,$replaces,(getRegionById($_SESSION['region'])));

mysql_select_db($database_magazinducoin, $magazinducoin);

$query_categories = "SELECT cat_id, cat_name FROM category WHERE parent_id = 0 AND type='2' ORDER BY cat_name ASC";

$categories = mysql_query($query_categories, $magazinducoin) or die(mysql_error());

$row_categories = mysql_fetch_assoc($categories);

$totalRows_categories = mysql_num_rows($categories);


function draw_calendar($month,$year){

	global $mes_dates;

	global  $magazinducoin;

	

	$filtre = "";

	if(isset($_REQUEST['id_cat']) and !empty($_REQUEST['id_cat']))  $filtre = "AND category_id = ".$_REQUEST['id_cat'];
	
	/* draw table */

	$calendar = '<table cellpadding="0" cellspacing="0" class="calendar1" border="0" style="border-collapse:collapse;">';



	/* table headings */

	$headings = array('Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche');

	$calendar.= '<tr class="calendar-row" valign="top"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';



	/* days and weeks vars now ... */

	$running_day = date('w',mktime(0,0,0,$month,0,$year));

	$days_in_month = date('t',mktime(0,0,0,$month,1,$year));

	$days_in_this_week = 1;

	$day_counter = 0;

	$dates_array = array();



	/* row for week one */

	$calendar.= '<tr class="calendar-rows" valign="top">';



	/* print "blank" days until the first of the current week */

	$compt = 0;
	for($x = 0; $x < $running_day; $x++):

		if($compt%2==0) $cla = "red"; else $cla= "";

		$compt++;

		$calendar.= '<td class="calendar-day-np-news '.$cla.'">&nbsp;</td>';

		$days_in_this_week++;

	endfor;



	/* keep going with days.... */

	for($list_day = 1; $list_day <= $days_in_month; $list_day++):

			if($compt%2==0) $cla = "red"; else $cla= "";

			$compt++;

			if($compt>6) $compt = 0;

			$calendar.= '<td class="calendar-day '.$cla.'';

				//if($list_day%2==0){$calendar.=' red';}

			$calendar.='">';

			/* add in the day number */

			$curnt_day = date('Y-m-d',mktime(0,0,0,$month,$list_day,$year));

			$calendar.= '<div class="positionner"><div class="day-number">'.$list_day .'</div>';



			/** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/

			//$calendar.= str_repeat('<p>&nbsp;</p>',2);
	$filtre = "";
	
	
	if($_REQUEST['id_cat']=='tout'){
		$filtre='';
	}elseif(($_REQUEST['id_cat']!='tout')and !empty($_REQUEST['id_cat'])){
		$filtre = "AND evenements.category_id = ".$_REQUEST['id_cat'];
	}
	//if(isset($_REQUEST['id_cat']) and !empty($_REQUEST['id_cat']))  $filtre = "AND evenements.category_id = ".$_REQUEST['id_cat'];
	
			$query_events = "SELECT 
							  evenements.titre,
							  evenements.date_debut,
							  evenements.date_fin,
							  evenements.description,
							  magazins.nom_magazin,
							  magazins.id_magazin,
							  magazins.adresse,
							  magazins.region,
							  (SELECT 
								COUNT(*) 
							  FROM
								evenements AS ccss 
							  WHERE (
								  ccss.event_id = evenements.event_id 
								  AND evenements.en_tete_liste_fin >= '".date('Y-m-d', mktime(1, 0, 0, $month, $list_day, $year))."' 
								  AND evenements.date_fin >= '".date('Y-m-d', mktime(1, 0, 0, $month, $list_day, $year))."' 
								  AND evenements.date_debut <= '".date('Y-m-d', mktime(1, 0, 0, $month, $list_day, $year))."' 
								  AND evenements.approuve = '1'
								) 
								OR (
								  ccss.event_id = evenements.event_id 
								  AND evenements.date_fin >= '".date('Y-m-d', mktime(1, 0, 0, $month, $list_day, $year))."' 
								  AND evenements.date_debut <= '".date('Y-m-d', mktime(1, 0, 0, $month, $list_day, $year))."' 
								  AND evenements.en_tete_liste_fin >= '".date('Y-m-d', mktime(1, 0, 0, $month, $list_day, $year))."' 
								  AND evenements.approuve = 0 
								  AND evenements.public = 1 
								  AND evenements.public_start < '".date('Y-m-d H:i:s', mktime(1, 0, 0, $month, $list_day, $year))."' 
								  AND (
									evenements.public_start + INTERVAL 20 MINUTE
								  ) < '".date('Y-m-d H:i:s', mktime(1, 0, 0, $month, $list_day, $year))."'
								) 
								OR (
								  ccss.event_id = evenements.event_id 
								  AND evenements.approuve = 0 
								  AND evenements.public = 0 
								  AND DATE_ADD(
									evenements.date_debut,
									INTERVAL - evenements.day_en_tete_liste DAY
								  ) = '".date('Y-m-d', mktime(1, 0, 0, $month, $list_day, $year))."' 
								  AND date_debut >= '".date('Y-m-d', mktime(1, 0, 0, $month, $list_day, $year))."'
								) 
								AND evenements.en_tete_liste_payer = 1 
								AND evenements.en_tete_liste = 1 
								AND evenements.payer = 1 
								AND evenements.active = 1) AS top,
							  evenements.date_debut,
							  DATE_ADD(
								date_debut,
								INTERVAL - evenements.day_en_tete_liste DAY
							  ) 
							FROM
							  magazins 
							  INNER JOIN evenements 
								ON magazins.id_magazin = evenements.id_magazin 
							  INNER JOIN region 
								ON region.id_region = magazins.region 
							  INNER JOIN departement 
								ON departement.code = magazins.department 
							  INNER JOIN maps_ville 
								ON maps_ville.id_ville = magazins.ville 
							WHERE (
								(
								  evenements.date_fin >= '".date('Y-m-d', mktime(1, 0, 0, $month, $list_day, $year))."' 
								  AND evenements.en_tete_liste = 1 
								  AND evenements.en_tete_liste_payer = 1 
								  AND evenements.date_debut <= '".date('Y-m-d', mktime(1, 0, 0, $month, $list_day, $year))."' 
								  AND evenements.en_tete_liste_fin >= '".date('Y-m-d', mktime(1, 0, 0, $month, $list_day, $year))."' 
								  AND evenements.approuve = '1'
								) 
								OR (
								  evenements.approuve = '0' 
								  AND evenements.en_tete_liste = 1 
								  AND evenements.en_tete_liste_payer = 1 
								  AND evenements.public = 0 
								  AND DATE_ADD(
									date_debut,
									INTERVAL - evenements.day_en_tete_liste DAY
								  ) = '".date('Y-m-d', mktime(1, 0, 0, $month, $list_day, $year))."' 
								  AND date_debut >= '".date('Y-m-d', mktime(1, 0, 0, $month, $list_day, $year))."'
								) 
								OR (
								  evenements.approuve = 0 
								  AND evenements.date_debut <= '".date('Y-m-d', mktime(1, 0, 0, $month, $list_day, $year))."'
								  AND evenements.date_fin >= '".date('Y-m-d', mktime(1, 0, 0, $month, $list_day, $year))."' 
								  AND evenements.public = 1 
								  AND evenements.public_start < '".date('Y-m-d H:i:s', mktime(1, 0, 0, $month, $list_day, $year))."' 
								  AND (
									evenements.public_start + INTERVAL 20 MINUTE
								  ) < '".date('Y-m-d H:i:s', mktime(1, 0, 0, $month, $list_day, $year))."'
								)
								OR (
								  evenements.approuve = 1 
								  AND evenements.date_debut <= '".date('Y-m-d', mktime(1, 0, 0, $month, $list_day, $year))."'
								  AND evenements.date_fin >= '".date('Y-m-d', mktime(1, 0, 0, $month, $list_day, $year))."'
								)
							  ) 
							  AND (
								evenements.payer = 1 
								AND evenements.active = 1 
								AND region.id_region = '".$_REQUEST['region']."'
								$filtre
							  ) 
							ORDER BY top DESC";
			//echo $query_events;
		$events = mysql_query($query_events, $magazinducoin) or die(mysql_error());
	$finds = array(" ","(",")","é","è","ê","ë","ï","ö","à","Ë","Ê","É","Ä","Â","Ö","Ô","Ï","ô","'",".","/");
	$replaces = array("-","","","e","e","e","e","i","o","a","E","E","E","A","A","O","O","I","o","","","");
		while($row_events = mysql_fetch_assoc($events)) {
 			$nom2=str_replace($finds,$replaces,$row_events['nom_magazin']);
			$nom_region2=str_replace($finds,$replaces,getRegionById($row_events['region']));
			//$calendar.= '<a class="openmodalbox1" href="detail_magasin.php?region='.$row_events['id_region'].'&mag_id='.$row_events['id_magazin'].'&#tabs-4">'.$row_events['titre'].'</a><br />';
			$calendar.= '<a class="openmodalbox1" href="md-'.$row_events['region'].'-'.$nom_region2.'-'.$row_events['id_magazin'].'-'.$nom2.'.html#tabs-4">'.$row_events['titre'].'</a><br />';

		}

			

		$calendar.= '</div></td>';

		if($running_day == 6):

			$calendar.= '</tr>';

			if(($day_counter+1) != $days_in_month):

				$calendar.= '<tr class="calendar-row" valign="top">';

			endif;

			$running_day = -1;

			$days_in_this_week = 0;

		endif;

		$days_in_this_week++; $running_day++; $day_counter++;

	endfor;



	/* finish the rest of the days in the week */

	

		

		

	

	

	if($days_in_this_week < 8):

	//$compt = 0;

		for($x = 1; $x <= (8 - $days_in_this_week); $x++):

				if($compt%2==0) $cla = "red"; else $cla= "";

			$calendar.= '<td class="calendar-day-np-new '.$cla.'">&nbsp;</td>';

			$compt++;	

		endfor;

	endif;



	/* final row */

	$calendar.= '</tr>';



	/* end the table */

	$calendar.= '</table>';

	

	/* all done, return result */

	return $calendar;

}



function draw_calendar_day($day, $month, $year){

	global  $magazinducoin;

	$get_date = $year.'-'.$month.'-'.$day;
	
	$filtre = "";
	if($_REQUEST['id_cat']=='tout'){
		$filtre='';
	}elseif(($_REQUEST['id_cat']!='tout')and !empty($_REQUEST['id_cat'])){
		$filtre = "AND evenements.category_id = ".$_REQUEST['id_cat'];
	}

	$query_events = "SELECT 
					  evenements.titre,
					  evenements.date_debut,
					  evenements.date_fin,
					  evenements.description,
					  magazins.nom_magazin,
					  magazins.id_magazin,
					  magazins.adresse,
					  magazins.region,
					  region.nom_region,
					  region.id_region,
					  (SELECT 
						COUNT(*) 
					  FROM
						evenements AS ccss 
					  WHERE (
						  ccss.event_id = evenements.event_id 
						  AND evenements.en_tete_liste_fin >= '".$get_date."' 
						  AND evenements.date_fin >= '".$get_date."' 
						  AND evenements.date_debut <= '".$get_date."' 
						  AND evenements.approuve = '1'
						) 
						OR (
						  ccss.event_id = evenements.event_id 
						  AND evenements.date_fin >= '".$get_date."'
						  AND evenements.date_debut <= '".$get_date."' 
						  AND evenements.en_tete_liste_fin >= '".$get_date."' 
						  AND evenements.approuve = '0' 
						  AND evenements.public = '1'
						  AND evenements.public_start < '".$get_date."' 
						  AND (
							evenements.public_start + INTERVAL 20 MINUTE
						  ) < '".date('Y-m-d H:i:s', mktime(1, 0, 0, $month, $day, $year))."'
						) 
						OR (
						  ccss.event_id = evenements.event_id 
						  AND evenements.approuve = '0' 
						  AND evenements.public = '0' 
						  AND DATE_ADD(
							evenements.date_debut,
							INTERVAL - evenements.day_en_tete_liste DAY
						  ) = '".$get_date."' 
						  AND date_debut >= '".$get_date."'
						) 
						AND evenements.en_tete_liste_payer = '1' 
						AND evenements.en_tete_liste = '1' 
						AND evenements.payer = '1' 
						AND evenements.active = '1') AS top
					FROM
					  magazins 
					  INNER JOIN evenements 
						ON magazins.id_magazin = evenements.id_magazin 
					  INNER JOIN region 
						ON region.id_region = magazins.region 
					  INNER JOIN departement 
						ON departement.code = magazins.department 
					  INNER JOIN maps_ville 
						ON maps_ville.id_ville = magazins.ville 
					WHERE (
						(
						  evenements.date_fin >= '".$get_date."' 
						  AND evenements.en_tete_liste = '1' 
						  AND evenements.en_tete_liste_payer = '1' 
						  AND evenements.date_debut <= '".$get_date."' 
						  AND evenements.en_tete_liste_fin >= '".$get_date."' 
						  AND evenements.approuve = '1'
						) 
						OR (
						  evenements.approuve = '0' 
						  AND evenements.en_tete_liste = '1' 
						  AND evenements.en_tete_liste_payer = '1' 
						  AND evenements.public = '0' 
						  AND DATE_ADD(
							date_debut,
							INTERVAL - evenements.day_en_tete_liste DAY
						  ) = '".$get_date."' 
						  AND date_debut >= '".$get_date."'
						) 
						OR (
						  evenements.approuve = '0' 
						  AND evenements.date_debut <= '".$get_date."'
						  AND evenements.date_fin >= '".$get_date."' 
						  AND evenements.public = '1' 
						  AND evenements.public_start < '".date('Y-m-d H:i:s', mktime(1, 0, 0, $month, $day, $year))."' 
						  AND (
							evenements.public_start + INTERVAL 20 MINUTE
						  ) < '".date('Y-m-d H:i:s', mktime(1, 0, 0, $month, $day, $year))."'
						)
						OR (
						  evenements.approuve = 1 
						  AND evenements.date_debut <= '".date('Y-m-d H:i:s', mktime(1, 0, 0, $month, $day, $year))."'
						  AND evenements.date_fin >= '".date('Y-m-d H:i:s', mktime(1, 0, 0, $month, $day, $year))."'
						)
					  ) 
					  AND (
						evenements.payer = '1'
						AND evenements.active = '1' 
						AND region.id_region = '".$_REQUEST['region']."'
						$filtre
					  ) 
					ORDER BY top DESC";

	//echo $query_events;
	$finds = array(" ","(",")","é","è","ê","ë","ï","ö","à","Ë","Ê","É","Ä","Â","Ö","Ô","Ï","ô","'",".","/");
	$replaces = array("-","","","e","e","e","e","i","o","a","E","E","E","A","A","O","O","I","o","","","");
	
	$events = mysql_query($query_events, $magazinducoin) or die(mysql_error());

	$totalRows_events = mysql_num_rows($events);

	//echo $totalRows_events;

	//$calendar = '<div class="">'.$day."-".$month."-".$year.'</div>';

	$calendar = '<table cellpadding="0" cellspacing="0" class="calendar2" width="915" border="1" bordercolor="#CCC" style="border-collapse:collapse;">';

	$calendar.= '<td class="calendar-day-np-new" >Titre</td>';

	$calendar.= '<td class="calendar-day-np-new">Date début</td>';

	$calendar.= '<td class="calendar-day-np-new">Date fin</td>';

	$calendar.= '<td class="calendar-day-np-new">nom du magasin</td>';

	$calendar.= '<td class="calendar-day-np-new">Adresse</td>';

	while($row_events = mysql_fetch_assoc($events)) {
		
		$nom2=str_replace($finds,$replaces,$row_events['nom_magazin']);
		$nom_region2=str_replace($finds,$replaces,getRegionById($row_events['id_region']));

		$calendar.= '<tr class="calendar-row">';

		//$mes_dates[$row_events['date_debut']] = $row_events['titre'];

		//echo "<div class='ligne'>".$row_events['titre']."</div>";

		$calendar.= '<td width="180" height="40"><a class="openmodalbox1" href="md-'.$row_events['id_region'].'-'.$nom_region2.'-'.$row_events['id_magazin'].'-'.$nom2.'.html#tabs-4">'.$row_events['titre'].'</a><br /></td>';

		$calendar.= '<td width="80" align="center">'.dbtodate($row_events['date_debut']).'</td>';

		$calendar.= '<td width="80" align="center">'.dbtodate($row_events['date_fin']).'</td>';

		

		$calendar.= '<td width="120" align="center">'.$row_events['nom_magazin'].'</td>';

		$calendar.= '<td width="120" align="left"><div style="margin-left:10px;">'.$row_events['adresse'].', '.$row_events['region'].'</div></td>';

		

		$calendar.= '</tr>';

	}

	/* end the table */

	$calendar.= '</table>';

	

	/* all done, return result */

	return $calendar;

}



function draw_calendar_week($day, $month, $year){

	global  $magazinducoin;

	

	$running_day = date('w',mktime(0,0,0,$month,$day,$year));

	

	$filtre = "";

	if(isset($_REQUEST['id_cat']) and !empty($_REQUEST['id_cat']))  $filtre = "AND category_id = ".$_REQUEST['id_cat'];

	

	//echo $totalRows_events;

	//$calendar = '<div class="">'.$day."-".$month."-".$year.'</div>';

	$calendar = '<table cellpadding="0" cellspacing="0" class="calendar3" border="1" bordercolor="#CCC" style="border-collapse:collapse;">';

	//$calendar.= '<td class="calendar-day-np">Date</td>';

	//$calendar.= '<td class="calendar-day-np">Titre</td>';

	

		
	$i = $day - $running_day; 

	

		//$class = $j%2==0 ? "rouge":"blank";

		$calendar.= '<tr class="calendar-row '.$class.'">';
			$calendar.= '<td width="60" align="center" class="calendar-day-np-new">Date</td>';
			
			for($j=$i;$j<7+$i;$j++){
				$calendar.= '<td width="50" class="calendar-day-np-new">'.date('d-m-Y', mktime(1, 0, 0, $month, $j, $year)).'</td>';
			}
		$calendar.= '</tr>';
		
		
		$calendar.= '<tr class="calendar-row '.$class.'">';
			$calendar.= '<td align="center" class="calendar-day-np-new">Titre</td>';
			
			for($j=$i;$j<7+$i;$j++){
			$calendar.= '<td width="120" valign="top">';
			
	$filtre = "";
	if($_REQUEST['id_cat']=='tout'){
		$filtre='';
	}elseif(($_REQUEST['id_cat']!='tout')and !empty($_REQUEST['id_cat'])){
		$filtre = "AND evenements.category_id = ".$_REQUEST['id_cat'];
	}
	$finds = array(" ","(",")","é","è","ê","ë","ï","ö","à","Ë","Ê","É","Ä","Â","Ö","Ô","Ï","ô","'",".","/");
	$replaces = array("-","","","e","e","e","e","i","o","a","E","E","E","A","A","O","O","I","o","","","");
				
			$query_events = "SELECT 
							  evenements.titre,
							  evenements.date_debut,
							  evenements.date_fin,
							  evenements.description,
							  magazins.nom_magazin,
							  magazins.id_magazin,
							  magazins.adresse,
							  magazins.region,
							  (SELECT 
								COUNT(*) 
							  FROM
								evenements AS ccss 
							  WHERE (
								  ccss.event_id = evenements.event_id 
								  AND evenements.en_tete_liste_fin >= '".date('Y-m-d', mktime(1, 0, 0, $month, $j, $year))."' 
								  AND evenements.date_fin >= '".date('Y-m-d', mktime(1, 0, 0, $month, $j, $year))."' 
								  AND evenements.date_debut <= '".date('Y-m-d', mktime(1, 0, 0, $month, $j, $year))."' 
								  AND evenements.approuve = '1'
								) 
								OR (
								  ccss.event_id = evenements.event_id 
								  AND evenements.date_fin >= '".date('Y-m-d', mktime(1, 0, 0, $month, $j, $year))."'
								  AND evenements.date_debut <= '".date('Y-m-d', mktime(1, 0, 0, $month, $j, $year))."' 
								  AND evenements.en_tete_liste_fin >= '".date('Y-m-d', mktime(1, 0, 0, $month, $j, $year))."' 
								  AND evenements.approuve = 0 
								  AND evenements.public = 1 
								  AND evenements.public_start < '".date('Y-m-d H:i:s', mktime(1, 0, 0, $month, $j, $year))."' 
								  AND (
									evenements.public_start + INTERVAL 20 MINUTE
								  ) < '".date('Y-m-d H:i:s', mktime(1, 0, 0, $month, $j, $year))."'
								) 
								OR (
								  ccss.event_id = evenements.event_id 
								  AND evenements.approuve = 0 
								  AND evenements.public = 0 
								  AND DATE_ADD(
									evenements.date_debut,
									INTERVAL - evenements.day_en_tete_liste DAY
								  ) = '".date('Y-m-d', mktime(1, 0, 0, $month, $j, $year))."'  
								  AND date_debut >= '".date('Y-m-d', mktime(1, 0, 0, $month, $j, $year))."' 
								) 
								AND evenements.en_tete_liste_payer = 1 
								AND evenements.en_tete_liste = 1 
								AND evenements.payer = 1 
								AND evenements.active = 1) AS top,
							  evenements.date_debut,
							  DATE_ADD(
								date_debut,
								INTERVAL - evenements.day_en_tete_liste DAY
							  ) 
							FROM
							  magazins 
							  INNER JOIN evenements 
								ON magazins.id_magazin = evenements.id_magazin 
							  INNER JOIN region 
								ON region.id_region = magazins.region 
							  INNER JOIN departement 
								ON departement.code = magazins.department 
							  INNER JOIN maps_ville 
								ON maps_ville.id_ville = magazins.ville 
							WHERE (
								(
								  evenements.date_fin >= '".date('Y-m-d', mktime(1, 0, 0, $month, $j, $year))."' 
								  AND evenements.en_tete_liste = 1 
								  AND evenements.en_tete_liste_payer = 1 
								  AND evenements.date_debut <= '".date('Y-m-d', mktime(1, 0, 0, $month, $j, $year))."'  
								  AND evenements.en_tete_liste_fin >= '".date('Y-m-d', mktime(1, 0, 0, $month, $j, $year))."'  
								  AND evenements.approuve = '1'
								) 
								OR (
								  evenements.approuve = '0' 
								  AND evenements.en_tete_liste = 1 
								  AND evenements.en_tete_liste_payer = 1 
								  AND evenements.public = 0 
								  AND DATE_ADD(
									date_debut,
									INTERVAL - evenements.day_en_tete_liste DAY
								  ) = '".date('Y-m-d', mktime(1, 0, 0, $month, $j, $year))."'  
								  AND date_debut >= '".date('Y-m-d', mktime(1, 0, 0, $month, $j, $year))."' 
								) 
								OR(
								  evenements.approuve = 0  
								  AND evenements.date_debut <= '".date('Y-m-d', mktime(1, 0, 0, $month, $j, $year))."'
								  AND evenements.date_fin >= '".date('Y-m-d', mktime(1, 0, 0, $month, $j, $year))."' 
								  AND evenements.public = 1 
								  AND evenements.public_start < '".date('Y-m-d H:i:s', mktime(1, 0, 0, $month, $j, $year))."' 
								  AND (
									evenements.public_start + INTERVAL 20 MINUTE
								  ) < '".date('Y-m-d H:i:s', mktime(1, 0, 0, $month, $j, $year))."'
								)
								OR (
								  evenements.approuve = 1 
								  AND evenements.date_debut <= '".date('Y-m-d H:i:s', mktime(1, 0, 0, $month, $j, $year))."'
								  AND evenements.date_fin >= '".date('Y-m-d H:i:s', mktime(1, 0, 0, $month, $j, $year))."'
								)
							  ) 
							  AND (
								evenements.payer = 1 
								AND evenements.active = 1 
								AND region.id_region = '".$_REQUEST['region']."'
								$filtre
							  ) 
							ORDER BY evenements.date_debut DESC";
	
			$events = mysql_query($query_events, $magazinducoin) or die(mysql_error());
				//echo $query_events;
				while($row_events = mysql_fetch_assoc($events)) {
					$nom2=str_replace($finds,$replaces,$row_events['nom_magazin']);
					$nom_region2=str_replace($finds,$replaces,getRegionById($row_events['region']));

					$calendar.= '<a class="openmodalbox1" href="md-'.$row_events['region'].'-'.$nom_region2.'-'.$row_events['id_magazin'].'-'.$nom2.'.html#tabs-4" style="margin-bottom:20px;">'.$row_events['titre'].'</a>';
		
				}
	
			}
		$calendar.= '</td></tr>';

	
		
	

	/* end the table */

	$calendar.= '</table>';

	

	/* all done, return result */

	return $calendar;

}

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_villes = "SELECT nom_region FROM region WHERE id_region = ".$default_region;
$villes = mysql_query($query_villes) or die(mysql_error());
$row_villes = mysql_fetch_array($villes);


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 

	<title>Liste des évènements de la région <?php echo ($row_villes['nom_region']);?> | Magasin Du Coin</title>
	<meta name="description" content="Retrouver dans votre région <?php echo ($row_villes['nom_region']);?> tous les  évènements à proximité de chez vous" />
    <?php include("modules/head.php"); ?>
    <!--<link type="text/css" rel="stylesheet" href="assets/css/jquery.modalbox.css" />
    <script type="text/javascript" src="assets/js/jquery.modalbox-1.2.1-min.js"></script>-->
    <style type="text/css">

a.openmodalbox1{
	color:#9D216E;
	background:url("template/images/arrow.png") no-repeat scroll 5px 5px rgba(0, 0, 0, 0);
	padding-left:14px;
	font-size:12px;
	float:left;
}
a.openmodalbox1:hover{
	color:#F6AE30;
}
.calendar-day-np-new, .calendar-day-np-news{
	border:1px solid #CCC;
	text-align: center;
	font-size: 14px;
	font-weight: bold;
	color: #9D216E;
	height: 30px;
}
.calendar-row{
	border: 1px solid #CCC;
}




.ui-datepicker-inline{
	width:919px !important;
}
.ui-datepicker-group{
	
}
.ui-datepicker-multi .ui-datepicker-group-last .ui-datepicker-header, .ui-datepicker-multi .ui-datepicker-group-middle .ui-datepicker-header{
	border-left-width:1px !important;	
}
.ui-datepicker-multi .ui-datepicker-group table{
	width:100% !important;
	margin:0px !important;
}

</style>
<?php 
$day 	= (isset($_REQUEST['d']) and !empty($_REQUEST['d'])) 		? $_REQUEST['d'] : date('d');
$month 	= (isset($_REQUEST['m']) and !empty($_REQUEST['m'])) 		? $_REQUEST['m'] : date('n');
$year 	= (isset($_REQUEST['y']) and !empty($_REQUEST['y'])) 		? $_REQUEST['y'] : date('Y');
$view 	= (isset($_REQUEST['view']) and !empty($_REQUEST['view'])) 	? $_REQUEST['view'] : "default";
$id_cat	= (isset($_REQUEST['id_cat']) and !empty($_REQUEST['id_cat'])) 	? $_REQUEST['id_cat'] : "tout";
$current_date = $year.'-'.$month.'-'.$day;
?>
<link rel="stylesheet" href="assets/themes/base/jquery.ui.datepicker.css" />
<link rel="stylesheet" href="assets/themes/base/jquery.ui.core.css" />

<script src="assets/ui/jquery.ui.datepicker.js" type="text/javascript"></script>
<script src="assets/ui/jquery.ui.datepicker-fr.js" type="text/javascript"></script>
<script type="text/javascript">
	$(function() {
		var d = new Date('<?php echo $current_date;?>');
		$('#datepickers').datepicker({
			//changeMonth: true,
			//changeYear: true,
			 numberOfMonths: 3,
			 defaultDate: d,
			 dateFormat: 'yy-mm-dd',
			 //showButtonPanel: true,
			onSelect: function(dateText, inst) { 
				var even = $("#even").val();
				var region = $("#regions").val();
				//alert(even+' , '+dateText);
				var date = $(this).datepicker('getDate'),
				day  = date.getDate(),  
				month = date.getMonth() + 1,              
				year =  date.getFullYear();
				window.location = 'evenement-<?php echo $nom_region;?>-'+region+'-'+even+'-'+day+'-'+month+'-'+year+'-<?php echo $view;?>.html';
				
			}
		});
	});
</script>
</head>
<body id="sp" class="pcal">
<?php include("modules/header.php"); ?>
	<div id="content" >
        <?php include("modules/form_recherche_header.php"); ?>
        <div class="top reduit">
            <div id="head-menu" style="float:left;">
            	<?php include("assets/menu/main-menu.php"); ?>
            </div>
            <div id="url-menu" style="float:left;">
            <?php include("assets/menu/url_menu.php"); ?>
            </div>
        </div>

        <div class="contenue" id="calendrier" >    
    <div style="width:920px; float:left; margin-bottom:-40px;">
    <h1 style="font-size:5px; color:#F2EFEF; margin:0; padding:0">Liste des évènements dans la région <?php echo ($row_villes['nom_region']);?></h1>
    <h2 style="font-size:5px; color:#F2EFEF; margin:0; padding:0">Voir la liste des évènements à coté de chez vous</h2>
    
    
        <h3 style="font-size:16px; margin:0px 5px 8px 5px; font-weight:bold; text-align:center; width:900px;">Calendrier des évènements</h3>
			<?php
            $sqlcate = "SELECT cat_id, parent_id, cat_name FROM category WHERE parent_id='0' AND type='2' ORDER BY cat_name ASC";
            $resultcate = mysql_query($sqlcate) or die (mysql_error());
            ?>
            <form action="pcal.php" method="post" style="margin-top:4px; margin-left:15px; position:relative;">
                <div style="margin-left: -17px;"><select style="width:300px; margin-left:3px;" id="even">
                <option value="tout">Toutes les catégories d'évènement
                </option>
                <?php while ($querycate=mysql_fetch_array($resultcate)){?>
                <option value="<?php echo $querycate['cat_id']; ?>" <?php if (!(strcmp($querycate['cat_id'], $_GET['id_cat']))) {echo "SELECTED";} ?>><?php echo ($querycate['cat_name']); ?></option>
                <?php }?>
                </select>
                <input type="hidden" id="regions" value="<?php echo $_REQUEST['region'];?>" /></div>
            </form>
            <div id="datepickers"></div>
        </div>
    <div class="clear"></div>
    


<!--fin de recherche -->

<br/>

<br/>

<br/>
<?php
	$cls='';
	$cls ='class="current"';
?>

<div class="event" style="margin-top:20px;">
    
    <a <?php if($_REQUEST['view']=='default' or $_REQUEST['view']=='') echo $cls;?> href="evenement-<?php echo $nom_region;?>-<?php echo $default_region;?>-<?php echo $id_cat ?>-<?php echo $day ?>-<?php echo $month ?>-<?php echo $year ?>-default.html"><?php echo $xml->les_event_du_mois ?></a>  |                                                                                           

    <a <?php if($_REQUEST['view']=='week') echo $cls;?> href="evenement-<?php echo $nom_region;?>-<?php echo $default_region;?>-<?php echo $id_cat ?>-<?php echo $day ?>-<?php echo $month ?>-<?php echo $year ?>-week.html"><?php echo $xml->Les_evenements_du_semaine?></a> |

    <a <?php if($_REQUEST['view']=='day') echo $cls;?> href="evenement-<?php echo $nom_region;?>-<?php echo $default_region;?>-<?php echo $id_cat ?>-<?php echo $day ?>-<?php echo $month ?>-<?php echo $year ?>-day.html"><?php echo $xml->Les_evenements_du_jour ?></a>  
</div>
<br/>
<b style="font-size:14px;"><?php 
$number = array("1","2","3","4","5","6","7","8","9","10","11","12");
$fr = array("Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Novembre");
echo str_replace($number, $fr, $month).' '.$year;?>
</b>
<br/>
<br/>

<?php 





if($view == "day"){

	echo draw_calendar_day($day, $month, $year);

}

else if($view == "week"){

	echo draw_calendar_week($day, $month, $year);

}

else 

	echo draw_calendar($month, $year);

?>



      <!--  fin de contenue -->

    </div>
	<div style="width:100%; height:220px;">
<style>
	.km{
		background-color:#cbcbcb;
		width:220px;
		padding:10px 0px 10px 10px;
		float:left;
		margin:10px 0px 10px 10px;
		-webkit-border-radius: 5px 5px 5px 5px;
		border-radius: 5px 5px 5px 5px;
	}
	.ad{
		width:220px; 
		margin-left:-10px; 
		font-size:14.5px; 
		float:left;
	}
	.ad .ad_ville{
		padding:10px; 
		float:left; 
		background:#353535; 
		color:#FFF;
		text-transform:uppercase;
	}
	.ad img{
		float:right;
	}
	.ad_title{
		font-size:15px; 
		float:left; 
		width:220px; 
		margin:10px 0px;
		text-transform:uppercase;
	}
	.ad_title_pro{
		font-size:15px;
		font-weight:bold; 
		float:left; 
		width:220px; 
		margin:10px 0px;
		color:#9d216e;
	}
	.ad_type{
		margin-left:-10px; 
		margin-bottom:-10px; 
		padding-left:10px; 
		padding-top: 10px; 
		padding-bottom: 10px; 
		width:220px; 
		background-color:#FFF; 
		float:left; 
		font-size:13px;
		-webkit-border-radius: 0px 0px 5px 5px;
		border-radius: 0px 0px 5px 5px;
	}
	.ad_type a{
		width:100%; 
		float:left;
		font-weight:bold;
	}
</style>
<?php
	$datetime = date('Y-m-d H:i:s');
	$date = date('Y-m-d');
	$query_coupon = "SELECT 
					  evenements.titre,
					  magazins.logo,
					  magazins.photo1,
					  magazins.id_magazin,
					  magazins.nom_magazin,
					  evenements.active,
					  evenements.payer,
					  evenements.photo,
					  evenements.en_avant_fin,
					  maps_ville.nom 
					FROM
					  magazins 
					  INNER JOIN evenements 
						ON magazins.id_magazin = evenements.id_magazin 
					  INNER JOIN region 
						ON region.id_region = magazins.region 
					  INNER JOIN departement 
						ON departement.code = magazins.department 
					  INNER JOIN maps_ville 
						ON maps_ville.id_ville = magazins.ville 
					WHERE (
						(
						  evenements.date_fin >= '".$date."' 
						  AND evenements.date_debut <= '".$date."' 
						  AND evenements.en_avant_fin >= '".$date."' 
						  AND evenements.approuve = '1'
						) 
						OR (
						  evenements.approuve = '0' 
						  AND evenements.public = 0 
						  AND DATE_ADD(
							date_debut,
							INTERVAL - evenements.day_en_avant DAY
						  ) = '".$date."' 
						  AND date_debut >= '".$date."'
						) 
						OR (
						  evenements.date_fin >= '".$date."' 
						  AND evenements.date_debut <= '".$date."' 
						  AND evenements.en_avant_fin >= '".$date."' 
						  AND evenements.approuve = 0 
						  AND evenements.public = 1 
						  AND evenements.public_start < '".$datetime."' 
						  AND (
							evenements.public_start + INTERVAL 20 MINUTE
						  ) <  '".$datetime."' 
						)
					  ) 
					  AND (
						evenements.payer = 1 
						AND evenements.active = 1 
						AND evenements.en_avant = 1 
						AND evenements.en_avant_payer = 1 
						AND region.id_region = '".$default_region."'
					  )  
						ORDER BY RAND() 
						LIMIT 0, 3 ";
						//echo $query_coupon;
	$coupon = mysql_query($query_coupon, $magazinducoin) or die(mysql_error());
	$totalRows_coupon = mysql_num_rows($coupon);
	if($totalRows_coupon!='0'){
?>
	<?php while ($row_coupon=mysql_fetch_array($coupon)) {?>
		<?php $nom=str_replace($finds,$replaces,($row_coupon['nom_magazin']));?>
		<?php $nom_region=str_replace($finds,$replaces,(getRegionById($default_region)));?>
        <?php $nom_pro=str_replace($finds,$replaces,($row_coupon['titre']));?>
	<div class="km">
		<div class="ad">
			<div class="ad_ville">
				<?php echo $row_coupon['nom']; ?>
			</div>
			<?php if($row_coupon['photo1']){ ?>
			<img src="timthumb.php?src=assets/images/event/<?php echo $row_coupon['photo'] ?>&amp;w=37&amp;h=37&z=1" >  
			<?php }?> 
		</div>
		<div class="ad_title">
			<?php echo substr(utf8_decode($row_coupon['nom_magazin']),0,55); ?>
		</div>
		<div class="ad_title_pro">
			<?php echo substr(utf8_decode($row_coupon['titre']),0,55); ?>
		</div>
		<div class="ad_type">
			<div style="color:#000;">Cet établissement propose</div>
			<a href="pd-<?php echo $default_region;?>-<?php echo $nom_region;?>-<?php echo $row_coupon['id_magazin'];?>-<?php echo $nom;?>-<?php echo $row_coupon['id'];?>-<?php echo $nom_pro;?>-<?php echo $row_coupon['categorie'];?>.html#tabs-5" style="color:#cf8400;">
				<?php echo count_coupon($row_coupon['id_magazin'],$default_region); ?> Coupon(s) de réduction   
			</a>
			<a href="pd-<?php echo $default_region;?>-<?php echo $nom_region;?>-<?php echo $row_coupon['id_magazin'];?>-<?php echo $nom;?>-<?php echo $row_coupon['id'];?>-<?php echo $nom_pro;?>-<?php echo $row_coupon['categorie'];?>.html#tabs-4" style="color:#9d216e;">
            	<?php echo count_event($row_coupon['id_magazin'],$default_region); ?> Événement(s) 
			</a>
			<a href="pd-<?php echo $default_region;?>-<?php echo $nom_region;?>-<?php echo $row_coupon['id_magazin'];?>-<?php echo $nom;?>-<?php echo $row_coupon['id'];?>-<?php echo $nom_pro;?>-<?php echo $row_coupon['categorie'];?>.html#tabs-6" style="color:#b45f93;">
				<?php echo count_product($row_coupon['id_magazin'],$default_region);?> Produit(s)
			</a>
		</div>
	</div>  
	<?php }?>

    
<?php }else{?>
	<div class="km"><img src="timthumb.php?src=assets/de/event_pub.png&w=210&h=183" alt="" width="210" /></div>
	<div class="km"><img src="timthumb.php?src=assets/de/event_pub.png&w=210&h=183" alt="" width="210" /></div>
	<div class="km"><img src="timthumb.php?src=assets/de/event_pub.png&w=210&h=183" alt="" width="210" /></div>
<?php }?>
    
        <div class="km" style="width:250px !important; float:left; ">
            <script type="text/javascript">
				google_ad_client = "ca-pub-0562242258908269";
				google_ad_slot = "2370230299";
				google_ad_width = 240;
				google_ad_height = 183;
            </script>
            <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
        </div>
        
    </div>
</div>

<div id="footer">
	<div class="recherche">
    &nbsp;
    </div>
	<?php include("modules/footer.php"); ?>
</div>

</body>

</html>