<?php require_once('../Connections/magazinducoin.php'); ?>
<script src="../template/js/jquery.js" type="text/javascript"></script>
<script type='text/javascript' src='../assets/autocomplete/jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="../assets/autocomplete/jquery.autocomplete.css" />
<script type="text/javascript">
	$(document).ready(function() {
		$("#ville").autocomplete("assets/autocomplete/ville_near_by.php?region=<?php echo $default_region;?>").result(function(e, data){
			$("#ville").val(data[0]);
			$("#id_ville").val(data[1]);
		});
	});
</script>

<input type="text" style="width:175px;" name="ville" id="ville" value="" placeholder="ville" class="width3"> 
