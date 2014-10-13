<div class="espace_pub" >
    
             <h3>Coupons réduc</h3>
             <div id="slider6">
<?php //debut de la boucle 
	$query_Recordset1 = "
	SELECT c.id_coupon, c.titre, c.id_user, c.categories, 
		CASE c.id_magasin 
			WHEN -1 THEN (SELECT id_magazin FROM magazins WHERE id_user = c.id_user ORDER BY id_magazin ASC LIMIT 1)
			ELSE  c.id_magasin 
		END 'magasinde', (SELECT photo1 FROM magazins WHERE id_magazin = magasinde) AS photo
	FROM coupons AS c 
	WHERE c.en_avant = 1 AND c.en_avant_payer = 1 AND c.en_avant_fin > NOW() AND c.date_fin > NOW() AND c.date_debut < NOW()";
    $Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die('0'.mysql_error());
    while($coupon = mysql_fetch_assoc($Recordset1)){
								
							?>
                            <div style="width: 100%;">
                            <div style="float:left;padding:12px; height:100px;">
                                     <a style="min-height: 282px; height: auto;" href="detail_produit.php?cat_id=<?php echo $coupon['categories'];?>&amp;mag_id=<?php echo $coupon['magasinde']; ?>">
                                          <div class="image"> 
                                            <img src="timthumb.php?src=assets/images/produits/<?php echo $coupon['photo'] ?>&amp;w=118&amp;z=1">                               
                                          </div>
                                     </a>
                                    <div style="overflow:hidden;" class="titre">
                                        <a style="min-height: 282px; height: auto; font-size:14px; font-weight:bold;" class="box prd" href="detail_produit.php?cat_id=<?php echo $coupon['categories'];?>&amp;mag_id=<?php echo $coupon['magasinde']; ?>"><?php echo substr($coupon['titre'],0,55); ?></a>
                                    </div>
                                 </div>
                       </div>     
					<?php }//fin de la boucle?> 
              </div>              
       	</div> 