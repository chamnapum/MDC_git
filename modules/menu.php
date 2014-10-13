<div class="menu">
     <div class="menu_inner">
         <ul class="parent_menu">
             <li><a id="menu_produits"  href="rechercher.php?adresse=&rayon=999&mot_cle=&categorie=&sous_categorie=" 
              <?php if(strpos($_SERVER['PHP_SELF'],'rechercher.php') !== FALSE)echo 'style="background-color:#E39BC7; color:#fff;"'; ?>><?php echo $xml->produits; ?></a></li>
             
             <li><a id="menu_coupons"  href="rechercher_cpn.php?adresse=&rayon=999&mot_cle=&categorie=&sous_categorie=" 
              <?php if(strpos($_SERVER['PHP_SELF'],'rechercher_cpn.php') !== FALSE) echo 'style="background-color:#9D216E; color:#fff;"'; ?>>
              <?php echo $xml->Coupons_reduction; ?></a></li>
             
              <li><a id="menu_events"   
			 <?php if(strpos($_SERVER['PHP_SELF'],'pcal.php') !== FALSE) echo 'style="background-color:#B35A91; color:#fff;"'; ?>  
             href="pcal.php"><?php echo $xml->Evenements_magasin; ?></a></li>
             
             <li><a id="menu_magasins"   <?php if(strpos($_SERVER['PHP_SELF'],'liste_magasins.php') !== FALSE) echo 'style="background-color:#ED8427; color:#fff;"';?> href="liste_magasins.php?adresse=&rayon=999&mot_cle=&categorie=&sous_categorie="> <?php echo $xml->Magasins; ?></a></li>
             
       </ul>
                    
   </div>
</div>
<style>

  #content .top .menu {
    background-image: 
		 <?php 
		 if(strpos($_SERVER['PHP_SELF'],'rechercher.php'))
			{echo'url("'.$url.'template/images/menu_top_bg1.gif") !important';}
		 else if(strpos($_SERVER['PHP_SELF'],'rechercher_cpn.php')) 
			 {echo'url("'.$url.'template/images/menu_top_bg2.gif") !important';}
		 else if(strpos($_SERVER['PHP_SELF'],'pcal.php')) 
		   {echo'url("'.$url.'template/images/menu_top_bg3.gif") !important';}
		   else if(strpos($_SERVER['PHP_SELF'],'liste_magasins.php')) 
		   {echo'url("'.$url.'template/images/menu_top_bg.gif") !important';}
		  
		  ?>	
	;
}
  
}
  
#menu_produits:hover{
background-color:#E39BC7 !important;
}

#menu_coupons:hover{
background-color:#9D216E !important;
}
#menu_events:hover{
background-color:#B35A91 !important;
}
#menu_magasins:hover{
background-color:#ed8427 !important;
}

</style>