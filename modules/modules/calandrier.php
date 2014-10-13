<?php if(file_exists('../Connections/magazinducoin.php'))
	require_once('../Connections/magazinducoin.php');
	else 
	require_once('Connections/magazinducoin.php'); ?>
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

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_categories = "SELECT cat_id, cat_name FROM category WHERE parent_id = 0 ORDER BY cat_name ASC";
$categories = mysql_query($query_categories, $magazinducoin) or die(mysql_error());
$row_categories = mysql_fetch_assoc($categories);
$totalRows_categories = mysql_num_rows($categories);

mysql_select_db($database_magazinducoin, $magazinducoin);
$filtre = "";
if(isset($_GET['id_cat']) and !empty($_GET['id_cat']))  $filtre = "AND category_id = ".$_GET['id_cat'];
$query_events = "SELECT event_id, titre, category_id, date_debut, date_fin FROM evenements WHERE active = 1 AND date_debut LIKE '".date('Y-m')."%' $filtre ORDER BY date_debut ASC";
$events = mysql_query($query_events, $magazinducoin) or die(mysql_error());
$totalRows_events = mysql_num_rows($events);

$mes_dates = array();
while($row_events = mysql_fetch_assoc($events)) {
	$mes_dates[$row_events['date_debut']] = $row_events['titre'];
}

/* draws a calendar */
function draw_calendar($month,$year){
	global $mes_dates;
	
	/* draw table */
	$calendar = '<div class="mois_en_cours">'.date("F").' <strong>'.date("Y").'</strong></div> ';
	$calendar .= '<div class="calendar">';

	/* table headings */
	$headings = array('Dim','Lun','Mar','Mer','Jeu','Ven','Sam');
	//$calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('.&nbsp;</td><td class="calendar-day-head">',$headings).'.&nbsp;</td></tr>';

	/* days and weeks vars now ... */
	$running_day = date('w',mktime(0,0,0,$month,1,$year));
	$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
	$days_in_this_week = 1;
	$day_counter = 0;
	$dates_array = array();

	/* row for week one */
	$calendar.= '<div class="calendar-row">';
	$compt = 0;
	/* print "blank" days until the first of the current week */
	for($x = 0; $x < $running_day; $x++):
		$calendar.= '<div class="calendar-day-np '.$compt.'">&nbsp;</div>';
		$compt++;
		$days_in_this_week++;
	endfor;

	/* keep going with days.... */
	for($list_day = 1; $list_day <= $days_in_month; $list_day++):
		$calendar.= '<div class="calendar-day c'.$compt.'">';
			$compt++;
			if($compt>6) $compt = 0;
			/* add in the day number */
			$curnt_day = date('Y-m-d',mktime(0,0,0,$month,$list_day,$year));
			if(isset($mes_dates[$curnt_day]))
				$calendar.= '<a href="pcal.php?d='.$list_day.'&m='.$month.'&y='.$year.'&view=day"><div class="day-number-active">'.$list_day.'</div></a>';
			else
				$calendar.= '<div class="day-number">'.$list_day.'</div>';

			/** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
			//$calendar.= str_repeat('<p>&nbsp;</p>',2);
			
		$calendar.= '</div>';
		if($running_day == 6):
			$calendar.= '</div>';
			if(($day_counter+1) != $days_in_month):
				$calendar.= '<div class="calendar-row">';
			endif;
			$running_day = -1;
			$days_in_this_week = 0;
		endif;
		$days_in_this_week++; $running_day++; $day_counter++;
	endfor;

	/* finish the rest of the days in the week */
	if($days_in_this_week < 8):
		for($x = 1; $x <= (8 - $days_in_this_week); $x++):
			$calendar.= '<div class="calendar-day-np">&nbsp;</div>';
		endfor;
	endif;

	/* final row */
	$calendar.= '</div>';

	/* end the table */
	$calendar.= '</div>';
	
	/* all done, return result */
	return $calendar;
}

/* sample usages */

echo '<div class="calandrier">'.draw_calendar(date('m')+1,date('Y'))."</div>";

echo '<div class="detail"><h3>Calendrier du mois</h3>';
?>
<div class="cat">Catégorie :</div>
 <select name="categories"  onchange="ajax('modules/calandrier.php?id_cat='+this.value,'#home-cal'); ">
<option value=""> -- Tous les catégories -- </option>
  <?php
do {  
?>
  <option value="<?php echo $row_categories['cat_id']?>" <?php echo (isset($_GET['id_cat']) and $row_categories['cat_id'] == $_GET['id_cat']) ? "selected" : ""; ?>><?php echo ($row_categories['cat_name']); ?></option>
  <?php
} while ($row_categories = mysql_fetch_assoc($categories));
  $rows = mysql_num_rows($categories);
  if($rows > 0) {
      mysql_data_seek($categories, 0);
	  $row_categories = mysql_fetch_assoc($categories);
  }
?>

</select>
</div>