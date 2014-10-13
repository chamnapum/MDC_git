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
$query_Recordset1 = "SELECT titre,id,categorie,`description`,id_magazin FROM produits";
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());

$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Document sans titre</title>
    <?php include("modules/head.php"); ?>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/prototype/1/prototype.js"></script>
<script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/scriptaculous/1/scriptaculous.js'></script>
<script type="text/javascript" src="lightview/js/lightview.js"></script>
<link rel="stylesheet" type="text/css" href="lightview/css/lightview.css" />
<link rel="stylesheet" type="text/css" href="lightview/css/style.css" />
</head>

<body>
<?php include("modules/header.php"); ?>
<div id="content">
         	<div class="top reduit">
                    <?php include("modules/menu.php"); ?>
            </div>
<table width="849" border="1">
<?php while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)){?>
  <tr>
    <td width="240"><a href="detail_produit.php?id=<?php echo $row_Recordset1['id'];?>&cat_id=<?php echo $row_Recordset1['categorie'];?>&mag_id=<?php echo $row_Recordset1['id_magazin'];?>"rel='iframe' title=' :: Produit :: width: 1000, height: 1000' class='lightview'>
	<?php echo $row_Recordset1['titre']; ?></a>
    </td>
    <td width="593"><?php echo$row_Recordset1['description']; ?>
    </td>
  </tr>
  <?php } ?>
</table>
</div>

</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
