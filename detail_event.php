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

$id = $_GET['id'];
mysql_select_db($database_magazinducoin, $magazinducoin);
$query_Recordset1 = "SELECT magazins.nom_magazin, magazins.adresse, evenements.description, evenements.date_debut, evenements.date_fin, evenements.titre, category.cat_name FROM ((evenements LEFT JOIN magazins ON magazins.id_magazin=evenements.id_magazin) LEFT JOIN category ON category.cat_id=evenements.category_id) WHERE event_id = $id ";
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 
	<title>Magasin du coin | Dé </title>
    <?php include("modules/head.php"); ?>
</head>
<body id="sp">
<?php include("modules/header.php"); ?>

  		<div id="content">
        <?php include("modules/form_recherche_header.php"); ?>
    	<div class="top reduit">
        	<div id="head-menu" style="float:left;"></div>
            <div id="url-menu" style="float:left;">
            <?php include("assets/menu/url_menu.php"); ?>
            </div>
        </div>
        <div class="clear"></div>
        
  		  <h3><?php echo $row_Recordset1['titre']; ?></h3>
			<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <th scope="row" width="100">Date début&nbsp;</th>
    <td style="padding:5px"><?php echo $row_Recordset1['date_debut']; ?>&nbsp;</td>
  </tr>
  <tr>
    <th scope="row">Date fin&nbsp;</th>
    <td style="padding:5px"><?php echo $row_Recordset1['date_fin']; ?>&nbsp;</td>
  </tr>
  <tr>
    <th scope="row">Magasin&nbsp;</th>
    <td style="padding:5px"><?php echo $row_Recordset1['nom_magazin']; ?>&nbsp;</td>
  </tr>
  <tr>
    <th scope="row">Adresse &nbsp;</th>
    <td style="padding:5px"><?php echo $row_Recordset1['adresse']; ?>&nbsp;</td>
  </tr>
  <tr>
    <th scope="row">Description&nbsp;</th>
    <td style="padding:5px"><?php echo $row_Recordset1['description']; ?>&nbsp;</td>
  </tr>
  <tr>
    <th scope="row">Catégorie&nbsp;</th>
    <td style="padding:5px"><?php echo $row_Recordset1['cat_name']; ?>&nbsp;</td>
  </tr>
</table>

    	</div>



</form>
<div id="footer">
    		<?php include("modules/region_barre_recherche.php"); ?>
        <div class="liens">
       		<?php include("modules/footer.php"); ?>
		</div>
</div>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>