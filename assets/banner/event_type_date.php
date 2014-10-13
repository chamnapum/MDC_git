<?php require_once('../../Connections/connection.php'); ?>
<?php
$magazinducoin = mysql_pconnect($hostname_magazinducoin, $username_magazinducoin, $password_magazinducoin); 
mysql_select_db($database_magazinducoin, $magazinducoin);

mysql_query("SET character_set_results=utf8",$magazinducoin);
mb_language('uni');
mb_internal_encoding('UTF-8');
mysql_query("set names 'utf8'" , $magazinducoin); 
$dbname=mysql_select_db($database_magazinducoin, $magazinducoin) or die("Can not select MySQL DB");

$banner_type = $_REQUEST['banner_type'];

if($banner_type=='1'){
	$query_Recordset = "SELECT 
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
	$Recordset = mysql_query($query_Recordset, $magazinducoin) or die('0'.mysql_error());
	$pub = mysql_fetch_assoc($Recordset);
}else{
	$query_Recordset = "SELECT 
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
	$Recordset = mysql_query($query_Recordset, $magazinducoin) or die('0'.mysql_error());
	$pub = mysql_fetch_assoc($Recordset);
}

?>
pendant <select name="banner_month" id="banner_month" class="banner_month" style="width:80px;">
	<option value="">Choisir</option>
    <option value="1">1 jour</option>
    <option value="2">2 jour(s)</option>
    <option value="3">3 jour(s)</option>
    <option value="4">4 jour(s)</option>
    <option value="5">5 jour(s)</option>
    <option value="6">6 jour(s)</option>
</select>

<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script> 
<script type="text/javascript">
	$(document).ready(function() {
		$('.banner_month').change(function() {
			var banner_type_date = $('.banner_month').val();
			if(banner_type_date!=''){
				var total = banner_type_date * <?php echo $pub['prix'];?>;
				//alert(total);
				$('#total_coupon').html('+ '+total+ ' &euro;');
				$('#total_banner').val(total);
			}else{
				$('#total_coupon').html('');
				$('#total_banner').val('');
			}
		});
	});
</script>
