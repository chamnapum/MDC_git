<?php require_once('Connections/alhodhod.php'); ?>
<?php
// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

// Make unified connection variable
$conn_alhodhod = new KT_connection($alhodhod, $database_alhodhod);

//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_alhodhod, "");
//Grand Levels: Level
$restrict->addLevel("1");
$restrict->Execute();
//End Restrict Access To Page

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

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
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO commentaires (id_reve, texte, is_user) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['id_reve'], "int"),
                       GetSQLValueString($_POST['commentaire'], "text"),
                       GetSQLValueString($_POST['is_user'], "int"));

  mysql_select_db($database_alhodhod, $alhodhod);
  $Result1 = mysql_query($insertSQL, $alhodhod) or die(mysql_error());
	
	//envoyer l'email
  $to = "webmaster@alhodhod.com" . ', ';
  $to .= "abdelhaq3@gmail.com";
  
  //$to = "contact@mohamedbelgaila.com";
  $subject = "Vous avez un nouveau commentaire";
  $message = "<html><body><br /><b>Asalamu Alaykom</b><br />
  <p>Vous avez un nouveau commentaire</p>
  <p>Veuillez visiter ce lien suivant pour consulter le commentaire : <a href='http://www.alhodhod.com/admin/ajouter-reve.php?id=".$_POST['id_reve']."'>http://www.alhodhod.com/admin/ajouter-reve/commentaires.php?id=".$_POST['id_reve']."</a></p>
  <p>Al Hodhod</p>
  </body></html>";
  //  configuration de type content-type :
	$headers   = 'From: "'.$_SESSION['kt_prenom'].'"<'.$_SESSION['kt_login_user'].'>'."\n"; 
	$headers  .= 'Content-Type: text/html; charset="iso-8859-1"'."\n";
    $headers  .= 'Content-Transfer-Encoding: 8bit'; 
	mail($to, $subject, $message, $headers);
	
  $insertGoTo = "commentaires.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_ennonce = "-1";
if (isset($_GET['id'])) {
  $colname_ennonce = $_GET['id'];
}
mysql_select_db($database_alhodhod, $alhodhod);
$query_ennonce = sprintf("SELECT date_reve, enence_reve FROM mes_reves WHERE id = %s", GetSQLValueString($colname_ennonce, "int"));
$ennonce = mysql_query($query_ennonce, $alhodhod) or die(mysql_error());
$row_ennonce = mysql_fetch_assoc($ennonce);
$totalRows_ennonce = mysql_num_rows($ennonce);

$colname_commentaires = "-1";
if (isset($_GET['id'])) {
  $colname_commentaires = $_GET['id'];
}
mysql_select_db($database_alhodhod, $alhodhod);
$query_commentaires = sprintf("SELECT * FROM commentaires WHERE id_reve = %s ORDER BY date_ajout DESC", GetSQLValueString($colname_commentaires, "int"));
$commentaires = mysql_query($query_commentaires, $alhodhod) or die(mysql_error());
$row_commentaires = mysql_fetch_assoc($commentaires);
$totalRows_commentaires = mysql_num_rows($commentaires);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 
	<title>Alhodhod | Interpreteur des rêves </title>
    <?php include("includes/head.php"); ?>
</head>
<body id="sp">
<?php include("includes/header.php"); ?>
<div id="breadcrumbs">
	<a title="Home" href="index.php">Accueil</a> » <a title="Home" href="membre.php">Espace membre</a> » <a title="Home" href="mes-reves.php">Mon journal des rêves</a> » Liste des commentaires
</div>
<div class="content_wrapper_sbr">
	<div>
  		<div id="content">
  		  <h2>Ennoncé du rêve</h2>
          <p><?php echo $row_ennonce['enence_reve']; ?></p>
          <br />
		  <h2>Liste des commentaires</h2>
          <?php if($totalRows_commentaires){
			  do { ?>
          <p class="dropcap">
          	<div class="date"><strong><?php echo $row_commentaires['date_ajout']; ?></strong> par <strong><?php echo $row_commentaires['is_user']?"Moi":"Al Hodhod"; ?></strong></div>
            <div class="texte"><?php echo nl2br($row_commentaires['texte']); ?>&nbsp;</div>
          </p>
          <div class="gototop"><a href="#top">top</a></div>
            <?php } while ($row_commentaires = mysql_fetch_assoc($commentaires));
			}else echo "Il n'y a pas des commentaires pour le moment!";?>
            
            <br />
<h2>Ajouter un commentaire</h2>
<p>
<form action="<?php echo $editFormAction; ?>" method="POST" name="form1" id="form1" onsubmit="if(document.getElementById('commentaire').value == '') {alert('Vous devez remplir le champ de commentaire!'); return false;} ">
<textarea name="commentaire" id="commentaire" cols="90" rows="10"></textarea>
<input name="id_reve" type="hidden" value="<?php echo $_GET['id']; ?>" />
<input name="is_user" type="hidden" value="1" />

<div ><input name="submit" type="submit" value="Envoyer" />&nbsp;&nbsp;&nbsp;
<input onclick="document.location = 'mes-reves.php';" value="&laquo; Retour a mes rêves" type="button" />&nbsp;&nbsp;&nbsp;
             <input onclick="document.location = 'membre.php';" value="&laquo; Retour a l'espace membre" type="button" /></div>
<input type="hidden" name="MM_insert" value="form1" /> 
</form>


             </p>
      </div>
	</div>
  
  
  
  
  <!-- Sidebar Area -->
  <div id="sidebar">
    <div class="widget-container widget_categories">
      <?php include("includes/dictionnaire.php");?>
    </div>
  </div>
  <div class="clear"></div>
</div>

    </div>
  </div>
</form>


<?php //----------------------------------------------------------------------------------------------------------------------?>
<!-- End Content Wrapper -->
<!-- Start Footer Sidebar -->
<?php include("includes/footer.php"); ?>
</body>
</html>
<?php
mysql_free_result($ennonce);

mysql_free_result($commentaires);
?>
