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

mysql_select_db($database_magazinducoin, $magazinducoin);
$query_journals = "SELECT departement.nom_departement, departement.id_departement, journal_export.mois, journal_export.region, journal_export.departement, journal_export.active, journal_export.fichier FROM (journal_export LEFT JOIN departement ON departement.id_departement=journal_export.departement) WHERE journal_export.region = $default_region AND journal_export.mois = '".date('m-Y')."'";
$journals = mysql_query($query_journals, $magazinducoin) or die(mysql_error());
$row_journals = mysql_fetch_assoc($journals);
$totalRows_journals = mysql_num_rows($journals);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 
	<title>Magazin Du Coin | </title>
<?php include("modules/head.php"); ?>
</head>
<body id="sp" >
<?php include("modules/header.php"); ?>
<div id="content" class="contact">
   	<div class="top reduit">
                    <?php include("modules/menu.php"); ?>
                    <?php include("modules/menu1.php"); ?>
            </div>
   <h3> Journal de région </h3><br />
   <ul>
   <?php do { ?>
     <li style="margin-left:10px;"><a href="download.php?fichier=<?php echo $row_journals['fichier']; ?>" target="_blank">Télécharger le Journal de  <?php echo $row_journals['nom_departement']; ?></a></li>
     <?php } while ($row_journals = mysql_fetch_assoc($journals)); ?>
     </ul>
</div>
 <div id="footer">
        <div class="liens">
      	 <?php include("modules/footer.php"); ?>
        </div>
 </div>

</body>
</html>
<?php
mysql_free_result($journals);
?>
