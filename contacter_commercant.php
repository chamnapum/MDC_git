<?php require_once('Connections/magazinducoin.php'); ?>
<?php

// Load the common classes
require_once('includes/common/KT_common.php');

// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

// Load the KT_back class
require_once('includes/nxt/KT_back.php');

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("");

// Make unified connection variable
$conn_magazinducoin = new KT_connection($magazinducoin, $database_magazinducoin);
//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_magazinducoin, "");
//Grand Levels: Level
//$restrict->addLevel("1");
$restrict->Execute();

if($default_region == 0) header('Location: index.php');
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


$mag=$_GET['id'];
$row=mysql_fetch_array(mysql_query("select * from magazins, utilisateur where id_magazin='$mag' and magazins.id_user=utilisateur.id"));
$email_mag=$row['email'];
$emetteur = $_SESSION['kt_login_email'];


if(!empty($_POST['objet']) and !empty($_POST['message'])){
		//destinataire :
		$to = $email_mag ;
		// sujet :
		$subject = $_POST['objet'];
		// message :
		$message = "
		<html>
		<head>
		</head>
		<body>
		<p>From   :".$emetteur."</p></br>
		<p>Subject   :".addslashes($_POST['objet'])."</p></br>
		</body>
		</html>";
		$message="un utilisateurs vient de vous envoyer un message via le site www.magasinducoin.com";
		$message.="<p> ".$_POST['message']."</p></br>";
		
		//  configuration de type content-type :
		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "content-type: text/html; charset=iso-8859-1\r\n";
		// D'autres en-têtes : errors, From cc's, bcc's, etc :
		//$headers .= "From:".$emetteur."\r\n";
		//envoi du mail :
		if(mail($to, $subject, $message))
			{ $msg = "<p class='succes'>Votre message  a bien été envoyée ,Merci !</p>"; }
		else { $msg = "<p class='error'>Erreur Lors de l'envoi du message , Veuillez renvoyez SVP!</p>"; }
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 
	<title>Magazin Du Coin </title>
    <?php include("modules/head.php"); ?>
</head>
<body>
	<?php include("modules/header.php"); ?>
    <!--End header-->
    <div id="content">
    	<div class="top" style="height:100px;">
        	<?php include("modules/menu.php"); ?>
            <?php include("modules/menu1.php"); ?>          
        </div>
       <!--  End Top -->
       
      <!--  End Coupons -->
      <div class="contenue  form_insc1 ">
     		<form action="contacter_commercant.php?id=<?php echo $mag; ?>&send=ok" method="post">
            
                 <div class="champ"  style="width:70%;float:left;"> 
                           	 <div class="lbl">
                              <label for="Objet">Objet :</label>  
                             </div>
                             <div class="ctn">  
                                  <input type="text" name="Objet" id="Objet" value="" size="32" />
                            </div>
            	</div>
            	 <div style="width:70%;float:left;">
                          <div class="lbl">
                             <label for="Objet">Message :</label> 
                          </div> 
                          <div class="ctn">  
                             <textarea name="message" cols="50" rows="5" id="message"></textarea>
                              <input name="" type="submit" value="Envoyer votre message " />
                          </div>
                 </div>
                  <div style="width:70%;float:left;">
                         <?php 
						 if ($_GET['send']=="ok")
						 {echo $msg;}
						 ?>
                 </div>
            </form>
      </div>
    </div>
    <!--End Content-->
    <div id="footer">
    	<?php include("modules/region_barre_recherche.php"); ?>
        <div class="liens">
       		<?php include("modules/footer.php"); ?>
        </div>
    </div>
</body>
</html>
<?php
mysql_free_result($categories);
?>
<style>
.lbl{
	float:left;
	width:30%;
	
}
.ctn{
	float:left;
	width:69%;
	
}
</style>