<?php require_once('../Connections/connection.php'); ?>

<?php
$magazinducoin = mysql_pconnect($hostname_magazinducoin, $username_magazinducoin, $password_magazinducoin); 
	mysql_query("SET character_set_results=utf8", $magazinducoin);
    mb_language('uni'); 
    mb_internal_encoding('UTF-8');
    mysql_select_db($database_magazinducoin, $magazinducoin);
    mysql_query("set names 'utf8'",$magazinducoin);
?>

<link href="assets/select/select2.css" rel="stylesheet"/>
<script src="assets/select/jquery.js"></script>
<script src="assets/select/select2.js"></script>
<?php
$query_ville = "SELECT 
			  maps_ville.id_ville,
			  maps_ville.nom,
			  maps_ville.cp
			FROM
			  maps_ville 
			  INNER JOIN departement 
				ON maps_ville.id_departement = departement.id_departement WHERE departement.code='".$_GET['id_departement']."' ORDER BY nom ASC";
$ville = mysql_query($query_ville, $magazinducoin) or die(mysql_error());
?>

<script>
       $(document).ready(function () {
        
         var preload_data = [
         <?php $num=0; while ($query=mysql_fetch_array($ville)) { $num++;?>
         
          <?php if($totalnum==$num){?>
           { id: '<?php echo $query['id_ville'];?>', text: '<?php echo $query['nom'].' '.$query['cp'];?>'}
          <?php }else{?>
           { id: '<?php echo $query['id_ville'];?>', text: '<?php echo $query['nom'].' '.$query['cp'];?>'},
          <?php }?>
          
         <?php }?>
        ];
        
        var preload_data_edit = [
         <?php 
          $result=mysql_query("SELECT
									maps_ville.nom
									, maps_ville.cp
									, maps_ville.id_ville
									, ville_near.id_magazin
								FROM
									maps_ville
									INNER JOIN ville_near 
										ON (maps_ville.id_ville = ville_near.nom_ville_near) WHERE ville_near.id_magazin='".$_GET['default']."' ORDER BY nom ASC");
          
          $totalnum=mysql_num_rows($result);
         ?>
         <?php $num=0; while ($query=mysql_fetch_array($result)) { $num++;?>
         
          <?php if($totalnum==$num){?>
           { id: '<?php echo $query['id_ville'];?>', text: '<?php echo $query['nom'].' '.$query['cp'];?>'}
          <?php }else{?>
           { id: '<?php echo $query['id_ville'];?>', text: '<?php echo $query['nom'].' '.$query['cp'];?>'},
          <?php }?>
          
         <?php }?>
        ];
        
        
        $('#ville_admin').select2({
			multiple: true
			,query: function (query){
			var data = {results: []};
			 
			$.each(preload_data, function(){
			if(query.term.length == 0 || this.text.toUpperCase().indexOf(query.term.toUpperCase()) >= 0 ){
			data.results.push({id: this.id, text: this.text });
			}
        });
         
        query.callback(data);
        }
        });
        $('#ville_admin').select2('data', preload_data_edit )
        
        
        
});
</script>

 <input type="text" id="ville_admin" name="ville_admin" style="width:220px;"/>



