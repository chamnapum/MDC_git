<?php require_once('../../Connections/connection.php'); ?>
<?php
$magazinducoin = mysql_pconnect($hostname_magazinducoin, $username_magazinducoin, $password_magazinducoin); 
mysql_select_db($database_magazinducoin, $magazinducoin);

mysql_query("SET character_set_results=utf8",$magazinducoin);
mb_language('uni');
mb_internal_encoding('UTF-8');
mysql_query("set names 'utf8'" , $magazinducoin); 
$dbname=mysql_select_db($database_magazinducoin, $magazinducoin) or die("Can not select MySQL DB");
?>
<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script> 
<script type="text/javascript">
	$(document).ready(function() {
		$('.banner_type').change(function() {
			
			var banner_type = $('.banner_type').val();
			if(banner_type!=''){
				var banner_type = $('.banner_type').val();
				var dataString='banner_type='+banner_type;
				$.ajax({
					type: "POST",
					url: "assets/banner/event_type_date.php",
					data: dataString,
					cache: false,
					success: function(datas){
						$(".coupon_type_date").html(datas);
					}
				});	
			}else{
				$(".coupon_type_date").html('');
				$('#total_coupon').html('');
				$('#total_banner').val('');
			}
		});
	});
</script>
<style>

  span.tooltip {outline:none; color:#9D216E; } 
  span.tooltip strong {line-height:30px;} 
  span.tooltip:hover {text-decoration:none; cursor:pointer;} 
  span.tooltip span { z-index:10;display:none; padding:10px 15px; margin-top:-10px; margin-left:8px; line-height:16px; } 
  span.tooltip:hover span{ display:inline; position:absolute; color:#111; border:1px solid #DCA; background:#fffAF0;} 
  .callout {z-index:20;position:absolute;top:30px;border:0;left:-12px;} 
  /*CSS3 extras*/ 
  span.tooltip span { border-radius:4px; -moz-border-radius: 4px; -webkit-border-radius: 4px; -moz-box-shadow: 5px 5px 8px #CCC; -webkit-box-shadow: 5px 5px 8px #CCC; box-shadow: 5px 5px 8px #CCC; }
  
</style>
<?php
$nom = $_REQUEST['nom'];
$query_Recordset1 = "SELECT 
				  tt.* 
				FROM
				  pub_emplacement tt 
				  INNER JOIN 
					(SELECT 
					  sub_type,
					  MAX(date_debut) AS MaxDateTime 
					FROM
					  pub_emplacement 
					WHERE date_debut <= NOW() 
					GROUP BY sub_type) groupedtt 
					ON tt.sub_type = groupedtt.sub_type 
					AND tt.date_debut = groupedtt.MaxDateTime 
				WHERE tt.type = '4' AND tt.sub_type='5'
				ORDER BY sub_type ASC";
$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die('0'.mysql_error());
$pub1 = mysql_fetch_assoc($Recordset1);

$query_Recordset2 = "SELECT 
				  tt.* 
				FROM
				  pub_emplacement tt 
				  INNER JOIN 
					(SELECT 
					  sub_type,
					  MAX(date_debut) AS MaxDateTime 
					FROM
					  pub_emplacement 
					WHERE date_debut <= NOW() 
					GROUP BY sub_type) groupedtt 
					ON tt.sub_type = groupedtt.sub_type 
					AND tt.date_debut = groupedtt.MaxDateTime 
				WHERE tt.type = '4' AND tt.sub_type='6'
				ORDER BY sub_type ASC";
$Recordset2 = mysql_query($query_Recordset2, $magazinducoin) or die('0'.mysql_error());
$pub2 = mysql_fetch_assoc($Recordset2);
?>
<select name="banner_type" id="banner_type" class="banner_type" style="width:150px;">
    <option value="">Choisir</option>
    <option value="1">Région (<?php echo $pub1['prix'];?> &euro;/jour)</option> 
    <option value="2">National (<?php echo $pub2['prix'];?> &euro;/jour)</option>
</select>

