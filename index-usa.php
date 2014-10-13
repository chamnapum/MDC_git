<?php require_once('Connections/magazinducoin.php');

$query_Recordset1 = "SELECT id_region, nom_region FROM region ORDER BY nom_region";
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());

$totalRows_Recordset1 = mysql_num_rows($Recordset1);
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php include("modules/head.php"); ?>
 <link rel="stylesheet" type="text/css" media="screen,projection" href="cssmap-usa/cssmap-usa.css" />
 <script  type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
 <script type="text/javascript" src="http://dl.dropbox.com/u/18926497/jquery.cssmap.js"></script> 
 <script type="text/javascript">
   $(function($){
     $('#map-usa').cssMap({'size' : 960});
    });
 </script>
<title>Magasin Du Coin</title>
</head>

<body>
<?php include("modules/header.php"); ?>
<div id="map-usa">
 <ul class="usa">
   <li class="usa1"><a href="#alabama">Alabama</a></li>
   <li class="usa2"><a href="#alaska">Alaska</a></li>
   <li class="usa3"><a href="#arizona">Arizona</a></li>
   <li class="usa4"><a href="#arkansas">Arkansas</a></li>
   <li class="usa5"><a href="#california">California</a></li>
   <li class="usa6"><a href="#colorado">Colorado</a></li>
   <li class="usa7"><a href="#connecticut">Connecticut</a></li>
   <li class="usa8"><a href="#delaware">Delaware</a></li>
   <li class="usa9"><a href="#florida">Florida</a></li>
   <li class="usa10"><a href="#georgia">Georgia</a></li>
   <li class="usa11"><a href="#hawaii" class="tooltip-middle">Hawaii</a></li>
   <li class="usa12"><a href="#idaho">Idaho</a></li>
   <li class="usa13"><a href="#illinois">Illinois</a></li>
   <li class="usa14"><a href="#indiana">Indiana</a></li>
   <li class="usa15"><a href="#iowa">Iowa</a></li>
   <li class="usa16"><a href="#kansas">Kansas</a></li>
   <li class="usa17"><a href="#kentucky">Kentucky</a></li>
   <li class="usa18"><a href="#louisiana">Louisiana</a></li>
   <li class="usa19"><a href="#maine">Maine</a></li>
   <li class="usa20"><a href="#maryland">Maryland</a></li>
   <li class="usa21"><a href="#massachusetts">Massachusetts</a></li>
   <li class="usa22"><a href="#michigan">Michigan</a></li>
   <li class="usa23"><a href="#minnesota">Minnesota</a></li>
   <li class="usa24"><a href="#mississippi">Mississippi</a></li>
   <li class="usa25"><a href="#missouri">Missouri</a></li>
   <li class="usa26"><a href="#montana">Montana</a></li>
   <li class="usa27"><a href="#nebraska">Nebraska</a></li>
   <li class="usa28"><a href="#nevada">Nevada</a></li>
   <li class="usa29"><a href="#new-hampshire">New Hampshire</a></li>
   <li class="usa30"><a href="#new-jersey">New Jersey</a></li>
   <li class="usa31"><a href="#new-mexico">New Mexico</a></li>
   <li class="usa32"><a href="#new-york">New York</a></li>
   <li class="usa33"><a href="#north-carolina">North Carolina</a></li>
   <li class="usa34"><a href="#north-dakota">North Dakota</a></li>
   <li class="usa35"><a href="#ohio">Ohio</a></li>
   <li class="usa36"><a href="#oklahoma">Oklahoma</a></li>
   <li class="usa37"><a href="#oregon">Oregon</a></li>
   <li class="usa38"><a href="#pennsylvania">Pennsylvania</a></li>
   <li class="usa39"><a href="#rhode-island">Rhode Island</a></li>
   <li class="usa40"><a href="#south-carolina">South Carolina</a></li>
   <li class="usa41"><a href="#south-dakota">South Dakota</a></li>
   <li class="usa42"><a href="#tennessee">Tennessee</a></li>
   <li class="usa43"><a href="#texas">Texas</a></li>
   <li class="usa44"><a href="#utah">Utah</a></li>
   <li class="usa45"><a href="#vermont">Vermont</a></li>
   <li class="usa46"><a href="#virginia">Virginia</a></li>
   <li class="usa47"><a href="#washington">Washington</a></li>
   <li class="usa48"><a href="#washington-dc">Washington DC</a></li>
   <li class="usa49"><a href="#west-virginia">West Virginia</a></li>
   <li class="usa50"><a href="#wisconsin">Wisconsin</a></li>
   <li class="usa51"><a href="#wyoming">Wyoming</a></li>
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
