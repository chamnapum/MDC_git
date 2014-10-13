<?php require_once('Connections/magazinducoin.php');

$query_Recordset1 = "SELECT id_region, nom_region FROM region ORDER BY nom_region";
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());

$totalRows_Recordset1 = mysql_num_rows($Recordset1);
 ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php include("modules/head.php"); ?>
 <link rel="stylesheet" type="text/css" media="screen,projection" href="mapbrasil/br-map-960px.css" />
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js"></script>
    <script type="text/javascript" src="mapbrasil/br-map.js"></script> 
<title>Magasin Du Coin</title>
</head>

<body>
<?php include("modules/header.php"); ?>
 <div id="map-br">
  <ul id="brasil">
   <li id="br1"><a href="#acre">Acre</a></li>
   <li id="br2"><a href="#alagoas">Alagoas</a></li>
   <li id="br3"><a href="#amapa">Amapá</a></li>
   <li id="br4"><a href="#amazonas">Amazonas</a></li>
   <li id="br5"><a href="#bahia">Bahia</a></li>
   <li id="br6"><a href="#ceara">Ceará</a></li>
   <li id="br7"><a href="#distrito-federal">Distrito Federal</a></li>
   <li id="br8"><a href="#espirito-santo">Espírito Santo</a></li>
   <li id="br9"><a href="#goias">Goiás</a></li>
   <li id="br10"><a href="#maranhao">Maranhão</a></li>
   <li id="br11"><a href="#mato-grosso">Mato Grosso</a></li>
   <li id="br12"><a href="#mato-grosso-do-sul">Mato Grosso do Sul</a></li>
   <li id="br13"><a href="#minas-gerais">Minas Gerais</a></li>
   <li id="br14"><a href="#para">Pará</a></li>
   <li id="br15"><a href="#paraiba">Paraíba</a></li>
   <li id="br16"><a href="#parana">Paraná</a></li>
   <li id="br17"><a href="#pernambuco">Pernambuco</a></li>
   <li id="br18"><a href="#piaui">Piauí</a></li>
   <li id="br19"><a href="#rio-de-janeiro">Rio de Janeiro</a></li>
   <li id="br20"><a href="#rio-grande-do-norte">Rio Grande do Norte</a></li>
   <li id="br21"><a href="#rio-grande-do-sul">Rio Grande do Sul</a></li>
   <li id="br22"><a href="#rondonia">Rondônia</a></li>
   <li id="br23"><a href="#roraima">Roraima</a></li>
   <li id="br24"><a href="#santa-catarina">Santa Catarina</a></li>
   <li id="br25"><a href="#sao-paulo">São Paulo</a></li>
   <li id="br26"><a href="#sergipe">Sergipe</a></li>
   <li id="br27"><a href="#tocantins">Tocantins</a></li>
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
