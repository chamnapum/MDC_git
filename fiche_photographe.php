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



$colname_photographe = "-1";

if (isset($_GET['id'])) {

  $colname_photographe = $_GET['id'];

}

mysql_select_db($database_magazinducoin, $magazinducoin);

$query_photographe = sprintf("SELECT * FROM utilisateur,region where utilisateur.region=region.id_region and level=2 and id = %s", GetSQLValueString($colname_photographe, "int"));

$photographe = mysql_query($query_photographe, $magazinducoin) or die(mysql_error());

$row_photographe = mysql_fetch_assoc($photographe);

$totalRows_photographe = mysql_num_rows($photographe);
//echo $query_photographe;


$colname_comment = "-1";

if (isset($_GET['id'])) {

  $colname_comment = $_GET['id'];

}

mysql_select_db($database_magazinducoin, $magazinducoin);

$query_comment = sprintf("SELECT commentaire FROM vote WHERE commentaire!='' and id_utilisateur = %s order by id_vote desc", GetSQLValueString($colname_comment, "int"));

$comment = mysql_query($query_comment, $magazinducoin) or die(mysql_error());

//$row_comment = mysql_fetch_assoc($comment);

$totalRows_comment = mysql_num_rows($comment);
//echo $query_comment;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 

	<title>Magasin du coin | <?php echo $xml-> espace_membre ?> </title>

    <?php include("modules/head.php"); ?>



<link href="stylesheets/fiche.css" rel="stylesheet" type="text/css" media="all" />

</head>

<script>

function afficher()

{

alert('votre vote a bien été effecute');

window.location = 'fiche_photographe.php'

}

function valider()

{

alert('votre msg a bien été envoyer');

}

</script>
<style>
	#fiches{
		width:98%; 
		padding-left:2%;
		float:left;
	}
	#fiches .titres{
		background-color: #999999;
		color: #000000;
		width: 97%;
		font-size: 30px;
		border-bottom: 1px solid black;
		float:left;
		padding-left:1%;
	}
	.area1s{
		width: 96%;
		background-color: #CCCCCC;
		color: #000000;
		padding:10px;
		float:left;
	}
	.area2s{
		width: 96%;
		background-color: #C1C1C1;
		color: #000000;
		padding: 2%;
	}
	.area3s{
		
	}
	label{
		font-weight:bold;
		font-size:13px;
	}
	input[type="text"]{
		border: 1px solid #333;
		border-radius: 5px 5px 5px 5px;
		height: 16px;
		margin-top: 5px;
		padding-left: 5px;
		width: 180px;
		font-size:13px;
	}
	textarea {
		border: 1px solid #333;
		border-radius: 5px 5px 5px 5px;
		width:100%;
	}
	input[type="submit"]{
		background-color: #9D286E;
		border: medium none;
		color: #F8C263;
		cursor: pointer;
		font-size: 18px;
		margin: 0 0 0 5px;
		padding: 0 10px 3px;
	}
	</style>
</head>

<body id="sp">

<?php include("modules/header.php"); ?>

<div id="content">
<?php include("modules/member_menu.php"); ?>
<?php include("modules/credit.php"); ?>
    
    <div id="fiches">
        <div class="titres"><strong><?php echo $row_photographe['prenom']; ?> <?php echo $row_photographe['nom']; ?></strong></div>
    
        <div class="area1s">
            <ul>
                <li> <?php echo $xml->Note ?> : <?php echo $row_photographe['note']; ?> / 5</li>
                <li> <?php echo $xml->Adresse ?>:  <?php echo $row_photographe['adresse']; ?></li>
                <li> <?php echo $xml->Telephone ?> : <?php echo $row_photographe['telephone']; ?></li>
                <li> <?php echo $xml->Resume_de_competence ?> : <?php echo $row_photographe['description']; ?></li>
            </ul>
        </div>
    
        <div class="area1s">
            <form action="voter.php?id=<?php echo $row_photographe['id']; ?>" method="POST">
            <div class="area2s">
                <label><?php echo $xml->Choisissez_la_cote_du_vote  ?> :<label><br /><br/>
                <div class="area3">
                    <label><input type="radio" name="vote" value="1" id="vote_0" />1</label>
                    <label><input type="radio" name="vote" value="2" id="vote_1" />2</label>
                    <label><input type="radio" name="vote" value="3" id="vote_2" />3</label>
                    <label><input type="radio" name="vote" value="4" id="vote_3" />4</label>
                    <label><input type="radio" name="vote" value="5" id="vote_4" checked="checked"/>5</label> 
                </div>            
                <div class="area3">
                    <p>&nbsp;</p>
                    <label><?php echo $xml->Inserer_votre_commentaire ?> :</label>
                    <textarea name="txt" cols="110" rows="5"></textarea>
                    <input name="" value="valider votre vote" type="submit" onclick="afficher();" />
                </div>
            </div>
            </form>
        </div>
    	<div class="clear"></div>
        <div class="area1s">
			<div class="area2s">
			<form action="envoyer.php?id=<?php echo $row_photographe['id']; ?>" method="POST">
                <label><?php echo $xml->objet ?>   :</label> 
                <p><input name="objet" value="" type="text" /></br></p>
                <label><?php echo $xml->message ?>   :</label></br>
                <textarea name="msg" cols="110" rows="10"></textarea>
                <input name="" value="<?php echo $xml->envoyer_un_message ?>" type="submit"onclick="valider();" />
            </form>
        	</div>	
        </div>
    	<div class="clear"></div>
    <br /><label><?php echo $xml->La_liste_des_commentaires; ?> :</label><br /><br />
    
    <?php while($row_comment = mysql_fetch_assoc($comment)){ ?>
        <div class="comment" style="float:left; font-size:14px; padding: 1% 0 1% 2%; width:96%; height:auto; margin-bottom:5px;">
        <?php echo $row_comment['commentaire']; ?>
        </div>
    <?php }?>
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
<?php
mysql_free_result($comment);
?>


