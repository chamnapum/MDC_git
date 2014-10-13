<?php require_once('Connections/magazinducoin.php'); ?>
<?php
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
</head>
<body id="sp">
<?php include("modules/header.php"); ?>
  		<div id="content">
         <div class="top reduit">
                    <?php include("modules/menu.php"); ?>
            </div>
             <?php include("modules/membre_menu.php"); ?>
    
<div style="padding-left:250px;height:500px;">
        
  		  <h2>Paiement - Etape 2</h2>
         
          
          
          
          <?php switch($_POST['paiement']){
		  	case 'paypal':
				paypal();
				break;
			
			case 'cheque':
				cheque();
				break;
				
			default:
				virement();
				break;
		  }
		  
		  function paypal() { 
		  	echo '<h3>Paypal</h3>
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="57P7H5VEUJQAA">
				<input type="image" src="https://www.paypalobjects.com/en_US/FR/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" style="height:47px">
				<img alt="" border="0" src="https://www.paypalobjects.com/fr_XC/i/scr/pixel.gif" width="1" height="1">
				</form>';
		 }
		 
		 function cheque(){
		 	echo '<h3>Chèque</h3>
			envoyer le cheque a l\'adresse suivante : xxxxxxxxxx<br>
			<input name="terminer" type="button" value="Terminer" />';
		 }
		 
		 function virement(){
		 	echo '<h3>Virement bancaire</h3>
			envoyer la somme au compte numero : xxxxxxxxxx<br>
			<input name="terminer" type="button" value="Terminer" />';
		 }
		?>

</div>
	  </div>
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