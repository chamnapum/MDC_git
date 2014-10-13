<?php require_once('../Connections/magazinducoin.php'); ?><?php require_once('../Connections/magazinducoin.php'); 
 
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

$colname_champs = "-1";
if (isset($_GET['categorie'])) {
  $colname_champs = $_GET['categorie'];
}
$colname_id = "-1";
if (isset($_GET['id'])) {
  $colname_id = $_GET['id'];
}
mysql_select_db($database_magazinducoin, $magazinducoin);
if (isset($_GET['id'])) {
	$query_champs = sprintf("SELECT autres_champs_data.value, autres_champs.id_champs, autres_champs.type, autres_champs.name, autres_champs.default_value, autres_champs.valide, autres_champs.required, autres_champs.in_search, autres_champs.categorie, autres_champs.ordre, autres_champs.label FROM (autres_champs_data LEFT JOIN autres_champs ON autres_champs.id_champs=autres_champs_data.id_champs) WHERE autres_champs.valide=1  AND autres_champs.categorie= %s AND autres_champs_data.id_produit = %s ORDER BY ordre ASC", GetSQLValueString($colname_champs, "int"), GetSQLValueString($colname_id, "int"));
}
else {
	$query_champs = sprintf("SELECT * FROM autres_champs WHERE categorie = %s ORDER BY ordre ASC", GetSQLValueString($colname_champs, "int"));
}
$champs = mysql_query($query_champs, $magazinducoin) or die(mysql_error());
$totalRows_champs = mysql_num_rows($champs);
while($row_champs = mysql_fetch_assoc($champs)){
	if (!isset($_GET['id'])) $row_champs['value'] = "";
	switch($row_champs['type']){
		case 'select':
			echo '<tr><td class="KT_th" style="width:100px"><label for="'.$row_champs['name'].'">'.$row_champs['label'];
			echo $row_champs['required']==1 ? ' <span classname="KT_required" class="KT_required">*</span>':"";
			echo '</label></td>';
			echo '<td><select name="autres['.$row_champs['id_champs'].'-'.$row_champs['name'].']" id="'.$row_champs['name'].'">';
			$tab = explode(',',$row_champs['default_value']);
			echo '<option value=""></option>';
			foreach($tab as $t){
				echo '<option value="'.trim($t).'"';
				if(trim($t) == $row_champs['value']) echo " SELECTED";
				echo '>'.trim($t).'</option>';
			}
			echo '</select></td></tr>';
			break;
			
		case 'text':
			echo '<tr><td class="KT_th" style="width:100px"><label for="'.$row_champs['name'].'">'.($row_champs['label']);
			echo $row_champs['required']==1 ? ' <span classname="KT_required" class="KT_required">*</span>' :"";
			echo '</label></td>';
			echo '<td><input name="autres['.$row_champs['id_champs'].'-'.$row_champs['name'].']" id="'.$row_champs['name'].'" type="text" value="';
			if(!empty($row_champs['value'])) 
				echo $row_champs['value'];
			else 
				echo $row_champs['default_value'];
			echo '" size="32" maxlength="200" />';
			echo '</td></tr>';
			break;
		
		case 'hidden':
			echo '<input name="autres['.$row_champs['id_champs'].'-'.$row_champs['name'].']" type="hidden" value="'.$row_champs['default_value'].'" />';
			break;
	}
}

mysql_free_result($champs);
?>