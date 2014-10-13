<?php require_once('Connections/magazinducoin.php');

$query_Recordset1 = "SELECT id_region, nom_region FROM region ORDER BY nom_region";
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());

$totalRows_Recordset1 = mysql_num_rows($Recordset1);
 ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php include("modules/head.php"); ?>
 <link rel="stylesheet" type="text/css" media="screen,projection" href="cssmap-europe/cssmap-europe.css" />
 <script  type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
 <script type="text/javascript" src="http://dl.dropbox.com/u/18926497/jquery.cssmap.js"></script> 
 <script type="text/javascript">
   $(function($){
    $('#map-europe').cssMap({'size' : 960});
    });
 </script>
<title>Magasin Du Coin</title>
</head>

<body>
<?php include("modules/header.php"); ?>
<div id="map-europe">
 <ul class="europe">
  <li class="eu1"><a href="#albania">Albania</a></li>
  <li class="eu2"><a href="#andorra">Andorra</a></li>
  <li class="eu3"><a href="#austria">Austria</a></li>
  <li class="eu4"><a href="#belarus">Belarus</a></li>
  <li class="eu5"><a href="#belgium">Belgium</a></li>
  <li class="eu6"><a href="#bosnia-and-herzegovina">Bosnia and Herzegovina</a></li>
  <li class="eu7"><a href="#bulgaria">Bulgaria</a></li>
  <li class="eu8"><a href="#croatia">Croatia</a></li>
  <li class="eu9"><a href="#cyprus">Cyprus</a></li>
  <li class="eu10"><a href="#czech-republic">Czech Republic</a></li>
  <li class="eu11"><a href="#denmark">Denmark</a></li>
  <li class="eu12"><a href="#estonia">Estonia</a></li>
  <li class="eu13"><a href="index-fr.php">France</a></li>
  <li class="eu14"><a href="#finland">Finland</a></li>
  <li class="eu15"><a href="#georgia">Georgia</a></li>
  <li class="eu16"><a href="#germany">Germany</a></li>
  <li class="eu17"><a href="#greece">Greece</a></li>
  <li class="eu18"><a href="#hungary">Hungary</a></li>
  <li class="eu19"><a href="#iceland">Iceland</a></li>
  <li class="eu20"><a href="#ireland">Ireland</a></li>
  <li class="eu21"><a href="#san-marino">San Marino</a></li>
  <li class="eu22"><a href="#italy">Italy</a></li>
  <li class="eu23"><a href="#kosovo">Kosovo</a></li>
  <li class="eu24"><a href="#latvia">Latvia</a></li>
  <li class="eu25"><a href="#liechtenstein">Liechtenstein</a></li>
  <li class="eu26"><a href="#lithuania">Lithuania</a></li>
  <li class="eu27"><a href="#luxembourg">Luxembourg</a></li>
  <li class="eu28"><a href="#macedonia">Macedonia</a></li>
  <li class="eu29"><a href="#malta">Malta</a></li>
  <li class="eu30"><a href="#moldova">Moldova</a></li>
  <li class="eu31"><a href="#monaco">Monaco</a></li>
  <li class="eu32"><a href="#montenegro">Montenegro</a></li>
  <li class="eu33"><a href="#netherlands">Netherlands</a></li>
  <li class="eu34"><a href="#norway">Norway</a></li>
  <li class="eu35"><a href="#poland">Poland</a></li>
  <li class="eu36"><a href="#portugal">Portugal</a></li>
  <li class="eu37"><a href="#romania">Romania</a></li>
  <li class="eu38"><a href="#russia">Russia</a></li>
  <li class="eu39"><a href="#serbia">Serbia</a></li>
  <li class="eu40"><a href="#slovakia">Slovakia</a></li>
  <li class="eu41"><a href="#slovenia">Slovenia</a></li>
  <li class="eu42"><a href="#spain">Spain</a></li>
  <li class="eu43"><a href="#sweden">Sweden</a></li>
  <li class="eu44"><a href="#switzerland">Switzerland</a></li>
  <li class="eu45"><a href="#turkey">Turkey</a></li>
  <li class="eu46"><a href="#ukraine">Ukraine</a></li>
  <li class="eu47"><a href="#united-kingdom">United Kingdom</a></li>
 </ul>
</div>
 <div id="content" class="home">
  		<div class="contenue large">
  	
  	  <div id="plan" >
      	<div class="plan_inner">
        	<div class="plan_title"><?php echo $xml->Qui_sommes_nous ?> </div>
            <div  class="plan_text">
           <?php echo $xml->Qui_somme_nous_text ?>
            </div>
        </div>
        <div class="plan_inner">
        	<div class="plan_title">Regions</div>
            <div  class="plan_text">
            	 <?php
			$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
          	while($row_Recordset2 = mysql_fetch_assoc($Recordset1)){
			?>
            <a href="region.php?region=<?php echo $row_Recordset2['id_region']; ?>">
			<?php echo ($row_Recordset2['nom_region']); ?></a><br />
			<?php 
			}
            ?> 
            
            </div>
        </div>
       	<div class="plan_inner">
        	<div class="plan_title"><?php echo $xml-> Villes_principales ?></div>
            <div  class="plan_text">
        	    <p><a href="region.php?region=1">Bordeaux</a> <br />
                  <a href="region.php?region=4">Brest</a><br />
                  <a href="region.php?region=2">Clermont- Ferrand</a><br /> 
                <a href="region.php?region=22">Grenoble</a></br>
                <a href="region.php?region=17">Le Havre </a></br>
               <a href="region.php?region=15"> Lille </a></br>
                <a href="region.php?region=22">Lyon</a> </br>
                <a href="region.php?region=21">Marseille</a> </br>
                <a href="region.php?region=13">Metz </a></br>
                <a href="region.php?region=11">Montpellier</a> </br>
                <a href="region.php?region=13">Nancy</a> </br>
                <a href="region.php?region=18">Nantes</a> </br>
                <a href="region.php?region=21">Nice</a> </br>
                <a href="region.php?region=10">Paris</a></br>
                <a href="region.php?region=6">Reims</a> </br>
                <a href="region.php?region=4">Rennes</a> </br>
                
                
                <a href="region.php?region=17">Rouen</a> </br>
                <a href="region.php?region=23">Strasbourg</a> </br>
                <a href="region.php?region=14">Toulon</a> </br>
                <a href="region.php?region=134">Toulouse</a> </br>
              </p>
       	    </div>
        </div>
        <div class="plan_inner">
        	<div class="plan_title">Magasin du coin </div>
            <div  class="plan_text">
            	<ul>
               		 <li><a href="#">  <?php echo $xml->Qui_sommes_nous ?> </a></li>
                  <li><a href="#"><?php echo $xml->Qui_peut_deposer_des_produits ?> </a></li>
                  <li><a href="#"><?php echo $xml->Comment_ca_marche ?></a></li>
                  <li><a href="#"> Facebook</a></li>
                  <li><a href="#"> <?php echo $xml->Nous_contacter ?></a></li>
                  <li> <a href="#"><?php echo$xml-> Aide ?></a></li>
                 

              </ul>
            </div>
        </div>
      </div>
      
    
        </div>
<div id="tooltip" style="position:absolute;visibility:hidden;background-color:#FFEEC7; border:1px solid black;padding:0.2em;font-size:0.8em;"></div>
  <div class="clear"></div>
</div>
</div>
 <div id="footer">
        <div class="liens">
      	 <?php include("modules/footer.php"); ?>
        </div>
 </div>

</body>
</html>
