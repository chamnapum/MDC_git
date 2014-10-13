<?php require_once('../Connections/magazinducoin.php');  

$colname_pub = "";
if ($_GET['id']!="") {
  $colname_pub = $_GET['id'];
  
	mysql_select_db($database_magazinducoin, $magazinducoin);
	$query_Recordset1 = "SELECT pub_emplacement.*, (SELECT COUNT(*) FROM pub WHERE id_user = ".$_SESSION['kt_login_id']." AND emplacement = pub_emplacement.id AND date_fin > '".date('Y-m-d H:i:s')."' AND id_produit = '".$colname_pub."') AS is_existe FROM pub_emplacement ORDER BY titre";
	$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
	$liste_pub = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>
<table width="400" border="0" cellspacing="2" cellpadding="2">
    <tr>
    	<th></th>
        <th>Faites de la publicité</th>
        <th>Prix</th>
    </tr>
	<?php
    while($row_liste_pub = mysql_fetch_assoc($liste_pub)){?>
    <tr>
        <td width="40"><?php if($row_liste_pub['is_existe']==0) { ?>
        <input  type="checkbox" id="c<?php echo $row_liste_pub['id']; ?>"
        onchange="mafon(this)" value="<?php echo $row_liste_pub['prix']; ?>" 
        name="pub[<?php echo $row_liste_pub['id']; ?>]">
        <?php } ?></td>
        <td><label for="c<?php echo $row_liste_pub['id']; ?>"><?php echo $row_liste_pub['titre'];?></label></td>
        <td><?php echo $row_liste_pub['prix'];?> &euro;</td>
    </tr>
    <?php } ?>
</table>
      
<?php	
}else{
	$colname_pub='-1';
}
?>
   
