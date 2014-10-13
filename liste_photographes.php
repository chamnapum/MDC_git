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
$query_photo = "SELECT * FROM utilisateur WHERE `level` = 3";
$photo = mysql_query($query_photo, $magazinducoin) or die(mysql_error());

$totalRows_photo = mysql_num_rows($photo);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 
	<title>Magazin Du Coin </title>
    <?php include("modules/head.php"); ?>
</head>
<title>Document sans nom</title>
<link href="stylesheets/style2.css" media="screen" rel="stylesheet" type="text/css" />

</head>
<body><?php include("modules/header.php"); ?>



 
<div id="main" class="ab-border ">

<div id="pleft">


<div id="profil_content " >
                           <div id="contactList" class="clearfix ab-border ">
                             <ul id="contactList_0">
                             
                                 <?php  while($row_photo = mysql_fetch_assoc($photo)){?>
<a href="javascript:;" onclick="ajax('ajax/photographes.php?id=<?php echo $row_photo['id']; ?>','#result');">                         <li class="contact Fch" rel="00223u15cfuutdnp-contact" name="b"><span class="fn"><span class="avat"><img src="icons/nophoto.jpg" alt="" height="35" width="26" border="0" align="" class="lazy-hide lazy-show" data-top="50" data-bottom="85" data-left="380"  /></span><?php echo $row_photo['nom']; ?></span></li></a>
                                 
                                 <div class="clear"></div>
                                 <?php }?>
                             </ul>
                             
                              </div>
    </div>








</div>
<div id="result">
<!--<div id="pright">
<div id="head_profil"></div>

<div  style="margin-left:10px;">
    <div id="tabs">
        <ul >
            <li><a href="#tabs-1">Pro</a></li>
            <li><a href="#tabs-2">Perso</a></li>
            
        </ul>
	</div>
 <div id="content" >   
      <div id="tabs-1" class="cart"> 
          <div id="infosprofil" >
                <div id="title">bla bla bla </div>
                <div id="fonction"> fsdfqsdfsdfs</div>
                <div id="fonction"> qsdqsdgqsd</div>
                <div id="fonction"> sdgfgsd</div>
                  <div id="fonction"> qsdqsdgqsd</div>
                <div id="fonction"> sdgfgsd</div>
                <br />
                <div class="ab-separator"></div>
          </div>
            <div id="imgprofil">
              <img src="icons/nophoto.jpg"  width="90" height="99"/>
          </div>
      </div>
      
       <a style="text-decoration:none;" >
<div class="petit_bouton" id="btn_voter"><span style="text-decoration:none">Voter pour lui</span></div></a>
              <div id="div_vote">
                                  <ul id="btn_action">
                                    <li><img height="24" align="absmiddle" width="24" src="icons/icone-message.png"> <a href="javascript:popbug(4,'pseudo_r=Hado1973')">Lui écrire un message</a></li>
                                 <!--   <li><img height="24" align="absmiddle" width="24" src="icons/icone-comment.png"> <a href="javascript:popbug(5,'QRMM6RHE5KD6HOC')">Lui laisser un commentaire</a></li>
                                    <li><img height="24" align="absmiddle" width="24" src="icons/icone-favoris.png"> <a href="javascript:ajouter_favoris(99,'Hado1973',253998)">L'ajouter à mes favoris</a></li>
                                    <li><img height="24" align="absmiddle" width="24" src="icons/icone-comment.png"> <a href="javascript:ajouter_favoris(99,'Hado1973',253998)">Lui laisser un commentaire</a></li>
                                  </ul>
                            </div> 
                  </br></br></br>
          <div class="ab-separator"></div>         
   -->
      </div> </div>
</div>    






</div>-->
</div>
</div>
</body>
</html>
<?php
mysql_free_result($photo);
?>
