<?php require_once('Connections/magazinducoin.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wdg="http://ns.adobe.com/addt">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 
	<title>Choix d'Inscription </title>
    <?php include("modules/head.php"); ?>
</head>
<body id="sp">
<?php include("modules/header.php"); ?>
<style>

.user{
  background-color: #9D286E;
    color: #F8C263;
    float: left;
    font-size: 18px;
    height: auto;
    margin-left: 105px;
    margin-right: 10px;
    padding: 5px 0 0;
	margin-bottom: 75px;
    text-align: center;
}
.user a, .user a:hover, .user a:visited{
color:#f8c263;
}
.photo{
width:160px;
height:250px;

}
</style>

    
<div id="content" class="choix_inscription">
            <div class="top reduit ">
                    <?php include("modules/menu.php"); ?>
            </div>
            <div  class="form_insc1">
  		    <h3><?php echo $xml->Inscription ?></h3>
            </div>
            <div style="margin:29px 0 0 39px; min-height:250px;">
                <span style="font-size:14px"><b><?php echo $xml->Vous_etes ?> :</b></span><br /><br />
                <div class="user">
                		<a href="inscriptionu.php"><?php echo $xml-> Utilisateur ;?></a> 
                		<a href="inscriptionu.php"><div class="photo"><img src="template/images/user.jpg" alt="Utilisateur" height="250" width="160" ></div></a> 
                </div>
                
                
                <div class="user">
                		<a href="inscription.php"><?php echo $xml-> commercant ;?></a> 
                		<a href="inscription.php"><div class="photo"><img src="template/images/comer.jpg" alt="Commerçant"  height="250" width="160"></div></a>
                 </div>
                 
                <div class="user">
                		 <a href="inscriptionp.php"><?php echo $xml-> photographe ;?></a> 
                		 <a href="inscriptionp.php"><div class="photo"><img src="template/images/photo.jpg" alt="Photographes"  height="250" width="160"></div></a>
                </div>
            </div>
</div>
<div id="footer">
    	
        <div class="liens">
       <?php include("modules/footer.php"); ?>
		</div> 
</div>  
</body>
</html>
