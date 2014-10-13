<?php require_once('Connections/magazinducoin.php'); 
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
$query_liste_pub = "SELECT pub_emplacement.*, (SELECT COUNT(*) FROM pub WHERE id_user = ".$_SESSION['kt_login_id']." AND emplacement = pub_emplacement.id AND date_fin > '".date('Y-m-d H:i:s')."' ) AS is_existe FROM pub_emplacement";
$liste_pub = mysql_query($query_liste_pub, $magazinducoin) or die(mysql_error());
$totalRows_liste_pub = mysql_num_rows($liste_pub);
// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

// Make unified connection variable
$conn_magazinducoin = new KT_connection($magazinducoin, $database_magazinducoin);

//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_magazinducoin, "");
//Grand Levels: Level
$restrict->addLevel("1");
$restrict->Execute();
//End Restrict Access To Page
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 
	<title>Magasin du coin | Espace membre </title>
    <?php include("modules/head.php"); ?>
    <script>
		var total = 0;
		function mafon(champ){
			//var lechamp = document.getElementById('champ');
			if(champ.checked)
				total += parseFloat(champ.value);
			else
				total -= parseFloat(champ.value);
			document.getElementById('result').innerHTML ="prix = "+ total;
			
		}
		function calculer(){
			document.location="m1.php?var="+total;
		} 
	</script>
</head>
<body id="sp">
<?php include("modules/header.php"); ?>

<div id="content">
    <div class="top reduit">
                        <?php include("modules/menu.php"); ?>
                        <div  style="font-size: 14px; font-weight: bold; position: absolute; right: 15px; top: 51px;">Votre Crédit Publicité: <?php 
$query_Recordset1 = "SELECT credit FROM utilisateur WHERE id = ".$_SESSION['kt_login_id'];
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
$row_credit = mysql_fetch_assoc($Recordset1);
echo $row_credit['credit'];  ?> &euro;</div>
    </div>
    <?php include("modules/membre_menu.php"); ?>
    
<div style="padding-left:250px;height:500px;">
		<?php 
	   //recuperation des valeurs :
	   $idm=isset($_SESSION['kt_login_id']);
	   $idp=$_GET['id'];
	   ?>
      <form action="traitement.php?p=<?php echo $idp;?>" method="post">
      <table width="300" border="0" cellspacing="2" cellpadding="2">
 
<th></th>
<th>Publicité</th>
<th>Prix</th>

      <?php
         while($row_liste_pub = mysql_fetch_assoc($liste_pub)){?>
          <tr>
    <td width="40"><?php if($row_liste_pub['is_existe']==0) { ?>
    <input  type="checkbox" id="c<?php echo $row_liste_pub['id']; ?>"
                        		onchange="mafon(this)" value="<?php echo $row_liste_pub['prix']; ?>" 
                                name="pub[<?php echo $row_liste_pub['id']; ?>]">
      <?php } ?></td>
    <td><?php echo $row_liste_pub['titre'];?> </td>
    <td><?php echo $row_liste_pub['prix'];?> &euro;</td>
  </tr>
      <?php } ?>
      </table>	
      <input type="submit" value="Continuer la soumission" />
      </form>
       </div>
  <div id="result"></div>
 
</form>
         </div>
</div>

<div id="footer">
    		<?php include("modules/region_barre_recherche.php"); ?>
        <div class="liens">
       		<?php include("modules/footer.php"); ?>
		</div>
</div>
</body>
</html>