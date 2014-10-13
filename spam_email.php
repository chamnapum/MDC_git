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
if($_GET['pro_id']){
	$pro = "AND produits.id='".$_GET['pro_id']."'";
}else{
	$pro="";
}
$query_payment = "SELECT
    produits.titre
    , magazins.nom_magazin
FROM
    produits
    INNER JOIN magazins 
        ON (produits.id_magazin = magazins.id_magazin) WHERE magazins.id_magazin='".$_GET['id_mag']."'".$pro;
$payment = mysql_query($query_payment, $magazinducoin) or die("Invoice".mysql_error());

$row_magasin = mysql_fetch_assoc($payment);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("modules/head.php"); ?>
<script type="text/javascript">
	$(document).ready(function(){
		$( "#send" ).click(function() {
			var nom = $('#nom').val();
			var email = $('#email').val();
			var maessage = $('#maessage').val();
			var id_mag = <?php echo $_GET['id_mag'];?>;
			<?php if($_GET['pro_id']){?>
			var id_pro	= <?php echo $_GET['pro_id'];?>;
			<?php }else{?>
			var id_pro ='';
			<?php }?>
			var dataString = 'nom='+nom+'&email='+email+'&maessage='+maessage+'&id_mag='+id_mag+'&id_pro='+id_pro;
			
			$('.required').css("border", "solid #ffffff 1px");
			var emptyTextBoxes = $('.required').filter(function() { return this.value == ""; });
			var string = "The blank textbox ids are - \n";
			var j=1;
			emptyTextBoxes.each(function() {
				var emptyID=this.id;
				$("#"+emptyID).css("border-color", "red");
				j++;
			});
			if (j<=1){
			$.ajax({
					type: "POST",
					url: "assets/spam/spam_pro.php",
					data: dataString,
					cache: false,
					success: function(datas){
						$("#send_success").html('Email a bien été envoyé');
						$(".show").css('display','none');
						/*setTimeout(function() {
						    // Do something after 5 seconds
							window.location.href = "http://stackoverflow.com";
						}, 5000);*/
					}
				});	
			}
			return false;
		});
	});
</script>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/> 
	<title>Magasin du coin | Imprimer les coupons </title>
    <style>
		table, p{
			font-size:12px;
		}
		td span{
			font-size:12px;
			font-weight:bold;
		}
    </style>
<body>
<div class="lister" style="margin:10px auto; width:546px;">
	<div style="width:100%; float:left;">
    	<img src="template/images/logo.png" width="274" height="110" alt="Logo - Magasin du coin" />
    </div>
    <div style="width:100%; text-align:center; float:left;">
    <h1 id="send_success"></h1>
    <h1 class="show">Magasin : <?php echo $row_magasin['nom_magazin'];?><?php if($_GET['pro_id']){?>,  Produit : <?php echo $row_magasin['titre'];?><?php }?></h1>
    	<table class="show" cellpadding="0" cellspacing="0" border="0" width="100%">
        	<tr>
            	<td><b>Nom : </b></td>
                <td>
                	<input type="text" id="nom" class="required" style="width:350px;"/>
                </td>
            </tr>
        	<tr>
            	<td><b>Email : </b></td>
                <td>
                	<input type="text" id="email" class="required" style="width:350px;"/>
                </td>
            </tr>
            <tr>
            	<td><b>Message : </b></td>
                <td>
                	<textarea id="maessage" class="required" style="width:350px; height:150px;"></textarea>
                </td>
            </tr>
            <tr>
            	<td></td>
                <td align="center">
                	<input type="button" value="Envoyer" id="send" />
                </td>
            </tr>
        </table>
    </div>
    
    
</div>




</body>
</html>



