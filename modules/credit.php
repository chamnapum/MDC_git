<div id="credit_page" style="font-size: 14px; font-weight: bold; padding: 40px 10px 5px 5px; text-align:right;">
    <span style="float:right;" data-step="15" data-intro="Le crédit est le montant disponible que vous possédez afin d'acheter des options pour vos coupons, vos événements, vos produits ou votre magasin.">
        <a href="mon-abonnement.php" style="text-decoration:underline;">Votre Crédit</a> :
        
        <?php 
        
        $query_Recordset1 = "SELECT credit FROM utilisateur WHERE id = ".$_SESSION['kt_login_id'];
        
        $Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());
        
        $row_credit = mysql_fetch_assoc($Recordset1);
        
        echo $row_credit['credit'];  ?> &euro;
	</span>
</div>