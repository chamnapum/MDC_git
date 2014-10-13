<?php require_once('Connections/magazinducoin.php');



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



$query_region = "SELECT * FROM region";



$region = mysql_query($query_region);



mysql_select_db($database_magazinducoin, $magazinducoin);



if(isset($_GET['id']) and !empty($_GET['id'])){



    var_dump('reset user');



	mysql_query("DELETE FROM utilisateur WHERE id = ".$_GET['id']);



}



$query_Recordset1 = "SELECT id_region, nom_region, island_region FROM region ORDER BY island_region , nom_region";



$Recordset1 = mysql_query($query_Recordset1, $magazinducoin) or die(mysql_error());







$totalRows_Recordset1 = mysql_num_rows($Recordset1);



?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<title>Magasin Du Coin - coupon de réduction et évènements à côté de chez vous</title>

<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />

<meta content="Profiter des codes promos, des bons de réduction et des évènements dans toutes les villes de France. Venez découvrir ce que vos commerçants vous proposent comme bon plan." name="description" />

<meta content="Code promo, Code promotion, Bon plan, Bon de réduction, Coupon de réduction, Bon plan Paris, Bon plan marseille, Bon plan Lyon, Bon plan ile de france, Bon plan aquitaine, Bon plan Lille, Bon plan Dijon, Bon plan Toulouse, Bon plan restaurant, bon plan soirée, ou sortir à paris, Ou sortir ce week-end, ou sortir , Evenement , evenement magasins, Evenement paris, Evenement marseille, Evenement lyon, Evenement ile de france, Goûter magasin, Anniversaire magasin, Commerce de proximité, Produit pas cher, Formation, Animation, Animation ville, réduction restaurant, réduction vetement, reduction concert,

créer gratuitement des annonces, commerçant, promotion, bonnes affaire, bonnes affaire Paris, Bonne affaire Lyon, Bonne affaire en France, Réduction voyage, bon plan commerce, cheque cadeau, cheque reduction" name="keywords" />

<?php include("modules/head-accueil.php"); ?>

</head>

<body>



<?php include("modules/header.php"); ?>



   <div id="content" class="home">

        <div class="top elargi">

        	<h1 style="font-size:5px; color:#F2EFEF; margin:0; padding:0">Bievenue sur magasin du coin</h1>

            <h2 style="font-size:3px; color:#F2EFEF; margin:0; padding:0">Des coupons de réduction et évènements à côté de chez vous</h2>

        	<h3 style="font-style: italic;"><?php echo $xml->choix_region; ?></h3>





<?php

$find = array(" ","(",")","é","è","ê","ë","ï","ö","à","Ë","Ê","É","Ä","Â","Ö","Ô","Ï","ô","'",".","/");

$replace = array("-","","","e","e","e","e","i","o","a","E","E","E","A","A","O","O","I","o","","","");



?>

 <table id="TableContentBottom" border="0">

        <tbody><tr valign="top">

        	<td class="CountyList">

            

             <div id="region">

                <ul>

                <?php $num=0; while($row_Recordset1 = mysql_fetch_assoc($Recordset1)){?>

				<?php $namede=str_replace($find,$replace,($row_Recordset1['nom_region']));?>

				<?php if($row_Recordset1['island_region']=='1' and $num==0){ $num++;?>

					<li class="region"><a href="<?php echo $row_Recordset1['id_region']; ?>-<?php echo $namede; ?>.html" id="county_<?php echo $row_Recordset1['id_region']; ?>" title="<?php echo ($row_Recordset1['nom_region']); ?>" onmouseover="change_image('<?php echo $url;?>', <?php echo $row_Recordset1['id_region']; ?>);" onmouseout="hide_image('<?php echo $url;?>', <?php echo $row_Recordset1['id_region']; ?>);"><?php echo ($row_Recordset1['nom_region']); ?></a></li>

				<?php }else{?>

                

					<li><a href="<?php echo $row_Recordset1['id_region']; ?>-<?php echo $namede; ?>.html" id="county_<?php echo $row_Recordset1['id_region']; ?>" title="<?php echo ($row_Recordset1['nom_region']); ?>" onmouseover="change_image('<?php echo $url;?>', <?php echo $row_Recordset1['id_region']; ?>);" onmouseout="hide_image('<?php echo $url;?>', <?php echo $row_Recordset1['id_region']; ?>);"><?php echo ($row_Recordset1['nom_region']); ?></a></li>

                <?php }?>

                

				<?php }?>

                </ul>  

			 </div>

            </td>

            <td>

             <div id="carte">



          	<div class="carte_inner">

            

                <div class="Map MapContainer">

                    <div style="background-image: url('assets/maphome/none.gif');" class="Map cmap" id="area_image">

                        <img src="assets/maphome/transparent.png" alt="Map magasin du coin" usemap="#france_map" width="410" height="480" />

                    </div>

                </div>

                

             </div>



          </div>

            </td>

          

        </tr>

    </tbody></table>



    <map name="france_map" id="franceMap">



    <area shape="poly" title="Alsace" coords="383,88,381,92,372,93,367,89,361,92,364,98,369,99,371,108,365,114,370,124,362,146,370,149,366,154,369,161,378,162,385,157,381,151,385,122,397,96,397,89,385,87" href="23-Alsace.html" onmouseover="change_image('<?php echo $url;?>', 23)" onmouseout="hide_image('<?php echo $url;?>', 23)" alt="Alsace" />



    <area shape="poly" title="Aquitaine" coords="87,349,79,356,89,359,91,369,95,366,111,376,116,376,122,380,128,380,131,369,140,355,135,347,130,345,131,328,132,325,142,329,154,323,171,319,171,312,173,306,174,296,181,294,190,279,183,275,181,255,171,253,164,249,138,274,121,264,103,254,87,349" href="1-Aquitaine.html" onmouseover="change_image('<?php echo $url;?>', 1)" onmouseout="hide_image('<?php echo $url;?>', 1)" alt="Aquitaine" />



    <area shape="poly" title="Auvergne" coords="242,200,219,217,227,226,229,239,223,243,226,263,216,267,212,283,213,298,230,289,230,283,240,295,251,284,270,292,290,271,282,265,271,263,271,255,263,243,266,224,272,217,261,203,243,200" href="2-Auvergne.html" onmouseover="change_image('<?php echo $url;?>', 2)" onmouseout="hide_image('<?php echo $url;?>', 2)" alt="Auvergne" />



    <area shape="poly" title="Basse-Normandie" coords="152,74,162,102,171,107,175,120,166,130,154,121,144,121,140,115,116,117,106,115,101,118,98,113,98,101,89,57,111,62,118,76" href="24-Basse-Normandie.html" onmouseover="change_image('<?php echo $url;?>', 24)" onmouseout="hide_image('<?php echo $url;?>', 24)" alt="Basse-Normandie" />



    <area shape="poly" title="Bourgogne" coords="253,124,243,126,240,133,243,145,236,154,241,198,262,202,272,216,272,222,277,229,288,224,289,219,300,222,302,210,313,211,318,195,311,189,318,173,315,159,311,161,300,154,298,144,293,140,289,144,272,145,260,136,253,123" href="3-Bourgogne.html" onmouseover="change_image('<?php echo $url;?>', 3)" onmouseout="hide_image('<?php echo $url;?>', 3)" alt="Bourgogne" />



    <area shape="poly" title="Bretagne" coords="68,163,92,152,105,146,114,137,112,117,102,119,98,116,96,111,88,105,60,107,44,96,0,106,2,142,48,166" href="4-Bretagne.html" onmouseover="change_image('<?php echo $url;?>', 4)" onmouseout="hide_image('<?php echo $url;?>', 4)" alt="Bretagne" />



    <area shape="poly" title="Centre" coords="190,103,179,109,173,109,176,118,172,125,174,134,161,159,149,161,147,180,156,189,168,193,173,200,174,209,183,217,202,216,215,215,225,211,230,201,239,199,239,181,235,152,242,144,239,134,227,137,222,128,208,130,205,120,196,116,192,102" href="5-Centre.html" onmouseover="change_image('<?php echo $url;?>', 5)" onmouseout="hide_image('<?php echo $url;?>', 5)" alt="Centre" />



    <area shape="poly" title="Champagne-Ardenne" coords="292,41,292,41,277,57,270,65,272,76,260,82,260,96,253,105,257,113,254,122,260,135,272,144,289,142,292,139,298,142,301,152,311,159,319,156,325,156,323,147,328,143,323,139,321,128,315,126,313,120,301,110,298,98,300,93,299,82,303,77,301,68,311,66" href="6-Champagne-Ardenne.html" onmouseover="change_image('<?php echo $url;?>', 6)" onmouseout="hide_image('<?php echo $url;?>', 6)" alt="Champagne-Ardenne" />



    <area shape="poly" title="Corse" coords="403,347,374,376,376,396,385,421,400,422,413,381,405,346" href="7-Corse.html" onmouseover="change_image('<?php echo $url;?>', 7)" onmouseout="hide_image('<?php echo $url;?>', 7)" alt="Corse" />



    <area shape="poly" title="Franche-Comté" coords="339,214,333,220,326,216,319,219,315,212,319,195,313,190,320,173,317,159,322,156,329,155,326,149,334,141,348,141,366,149,366,155,374,170" href="9-Franche-Comte.html" onmouseover="change_image('<?php echo $url;?>', 9)" onmouseout="hide_image('<?php echo $url;?>', 9)" alt="Franche-Comté" />



    <area shape="poly" title="Haute-Normandie" coords="190,47,190,47,202,59,200,67,202,84,196,88,189,102,178,108,164,102,153,74,148,57" href="17-Haute-Normandie.html" onmouseover="change_image('<?php echo $url;?>', 17)" onmouseout="hide_image('<?php echo $url;?>', 17)" alt="Haute-Normandie" />



    <area shape="poly" title="Ile-de-France" coords="202,86,191,101,197,114,205,120,209,127,223,127,226,134,238,133,242,124,252,123,255,114,253,105,243,92,231,94,216,86" href="10-Ile-de-France.html" onmouseover="change_image('<?php echo $url;?>', 10)" onmouseout="hide_image('<?php echo $url;?>', 10)" alt="Ile-de-France" />



    <area shape="poly" title="Languedoc-Roussillon" coords="246,400,230,406,197,404,200,393,214,391,213,386,207,384,206,371,198,359,202,354,229,354,231,346,251,337,256,323,248,316,241,296,251,287,270,292,276,311,284,316,296,313,303,327,295,331,294,340,282,351" href="11-Languedoc-Roussillon.html" onmouseover="change_image('<?php echo $url;?>', 11)" onmouseout="hide_image('<?php echo $url;?>', 11)" alt="Languedoc-Roussillon" />



    <area shape="poly" title="Limousin" coords="181,219,171,225,170,229,173,238,166,246,168,251,180,253,182,255,185,273,191,278,211,284,214,265,224,262,222,243,226,239,226,226,215,217,202,217,182,219" href="12-Limousin.html" onmouseover="change_image('<?php echo $url;?>', 12)" onmouseout="hide_image('<?php echo $url;?>', 12)" alt="Limousin" />



    <area shape="poly" title="Lorraine" coords="322,62,311,68,303,70,305,79,300,83,302,94,299,98,302,110,314,119,316,125,323,127,325,140,330,142,334,140,347,139,359,145,369,124,364,114,370,108,368,100,362,96,360,92,368,86,372,91,380,91,386,82" href="13-Lorraine.html" onmouseover="change_image('<?php echo $url;?>', 13)" onmouseout="hide_image('<?php echo $url;?>', 13)" alt="Lorraine" />



    <area shape="poly" title="Midi-Pyrénées" coords="192,280,181,296,175,299,173,319,142,330,134,328,132,343,142,355,132,370,129,385,197,397,200,391,212,391,212,388,206,385,205,371,197,360,200,352,227,353,229,345,248,336,255,325,247,318,239,297,232,287,225,295,212,300,210,286" href="14-Midi-Pyrenees.html" onmouseover="change_image('<?php echo $url;?>', 14)" onmouseout="hide_image('<?php echo $url;?>', 14)" alt="Midi-Pyrénées" />



    <area shape="poly" title="Nord-Pas-de-Calais" coords="191,30,199,35,207,36,213,41,224,38,224,43,248,50,263,46,281,50,279,29,231,0,190,2" href="15-Nord-Pas-de-Calais.html" onmouseover="change_image('<?php echo $url;?>', 15)" onmouseout="hide_image('<?php echo $url;?>', 15)" alt="Nord-Pas-de-Calais" />



    <area shape="poly" title="Pays de la Loire" coords="113,118,115,140,106,147,67,164,67,173,80,205,104,222,111,214,115,219,121,215,125,217,126,214,117,185,130,181,142,183,145,181,148,161,161,156,173,134,169,130,159,130,154,123,144,123,140,118" href="18-Pays-de-la-Loire.html" onmouseover="change_image('<?php echo $url;?>', 18)" onmouseout="hide_image('<?php echo $url;?>', 18)" alt="Pays de la Loire" />



    <area shape="poly" title="Picardie" coords="189,43,204,58,202,65,204,84,216,84,231,93,244,91,253,101,258,96,258,82,271,75,269,65,280,52,264,48,247,53,223,45,222,40,215,42,205,37,193,32" href="19-Picardie.html" onmouseover="change_image('<?php echo $url;?>', 19)" onmouseout="hide_image('<?php echo $url;?>', 19)" alt="Picardie" />



    <area shape="poly" title="Poitou-Charentes" coords="131,184,119,187,127,214,125,219,114,221,111,217,102,224,101,244,114,258,139,273,172,239,168,227,181,217,173,209,167,194,156,191,147,183,141,186,132,184" href="20-Poitou-Charentes.html" onmouseover="change_image('<?php echo $url;?>', 20)" onmouseout="hide_image('<?php echo $url;?>', 20)" alt="Poitou-Charentes" />



    <area shape="poly" title="Provence-Alpes-Côte d'Azur" coords="364,275,350,272,347,275,352,284,343,284,324,303,332,313,328,321,317,318,313,313,299,313,305,328,298,332,295,340,282,354,339,372,377,359,405,325,374,284,366,275" href="21-Provence-Alpes-Cote-dazur.html" onmouseover="change_image('<?php echo $url;?>', 21)" onmouseout="hide_image('<?php echo $url;?>', 21)" alt="Provence-Alpes-Côte d'Azur" />



    <area shape="poly" title="Rhône-Alpes" coords="362,212,340,214,333,222,325,218,321,221,313,212,304,211,301,225,290,221,289,226,275,231,270,224,267,233,265,242,271,252,273,260,289,265,292,270,272,292,277,309,284,313,301,311,316,311,318,316,327,318,330,314,323,302,343,282,348,282,345,275,350,270,369,273,382,262,378,228,365,212" href="22-Rhone-Alpes.html" onmouseover="change_image('<?php echo $url;?>', 22)" onmouseout="hide_image('<?php echo $url;?>', 22)" alt="Rhône-Alpes" />



    <area shape="poly" title="Guadeloupe" coords="71,473,81,463,79,448,88,451,96,446,111,448,101,439,97,431,89,423,83,430,86,435,81,436,80,442,74,440,66,435,60,441,62,449,63,458,70,473,104,466,110,473,106,478,100,476,98,470,104,468,115,442,119,445,126,441,124,437,118,439,73,481,72,476,71,477,80,476,81,481,72,479" href="27-Guadeloupe.html" onmouseover="change_image('<?php echo $url;?>', 27)" onmouseout="hide_image('<?php echo $url;?>', 27)" alt="Guadeloupe" />



    <area shape="poly" title="Martinique" coords="146,426,143,434,151,440,149,445,162,457,158,466,164,472,168,470,183,473,186,480,192,470,184,452,176,448,181,444,176,441,185,439,184,435,175,438,169,434,165,429,157,426,148,427" href="28-Martinique.html" onmouseover="change_image('<?php echo $url;?>', 28)" onmouseout="hide_image('<?php echo $url;?>', 28)" alt="Martinique" />



    <area shape="poly" title="Guyane" coords="227,431,234,436,252,442,259,452,252,461,250,471,243,480,239,478,237,477,228,480,220,477,222,472,223,465,228,463,220,454,221,448,219,445,218,442,223,437,227,432" href="30-Guyane.html" onmouseover="change_image('<?php echo $url;?>', 30)" onmouseout="hide_image('<?php echo $url;?>', 30)" alt="Guyane" />



    <area shape="poly" title="Réunion" coords="298,439,318,440,324,446,325,451,333,461,330,469,332,477,318,480,310,479,300,472,293,473,292,468,285,456,286,451,290,449,291,445,298,441" href="29-Reunion.html" onmouseover="change_image('<?php echo $url;?>', 29)" onmouseout="hide_image('<?php echo $url;?>', 29)" alt="Réunion" />



    </map>

    

    <script type="text/javascript">

        preload_image('<?php echo $url;?>/assets/maphome/map/map_23.png'); 

        preload_image('<?php echo $url;?>/assets/maphome/map/map_1.png'); 

        preload_image('<?php echo $url;?>/assets/maphome/map/map_2.png'); 

        preload_image('<?php echo $url;?>/assets/maphome/map/map_24.png'); 

        preload_image('<?php echo $url;?>/assets/maphome/map/map_3.png'); 

        preload_image('<?php echo $url;?>/assets/maphome/map/map_4.png'); 

        preload_image('<?php echo $url;?>/assets/maphome/map/map_5.png'); 

        preload_image('<?php echo $url;?>/assets/maphome/map/map_6.png'); 

        preload_image('<?php echo $url;?>/assets/maphome/map/map_7.png'); 

        preload_image('<?php echo $url;?>/assets/maphome/map/map_9.png'); 

        preload_image('<?php echo $url;?>/assets/maphome/map/map_17.png'); 

        preload_image('<?php echo $url;?>/assets/maphome/map/map_10.png'); 

        preload_image('<?php echo $url;?>/assets/maphome/map/map_11.png'); 

        preload_image('<?php echo $url;?>/assets/maphome/map/map_12.png'); 

        preload_image('<?php echo $url;?>/assets/maphome/map/map_13.png'); 

        preload_image('<?php echo $url;?>/assets/maphome/map/map_14.png'); 

        preload_image('<?php echo $url;?>/assets/maphome/map/map_15.png'); 

        preload_image('<?php echo $url;?>/assets/maphome/map/map_18.png'); 

        preload_image('<?php echo $url;?>/assets/maphome/map/map_19.png'); 

        preload_image('<?php echo $url;?>/assets/maphome/map/map_20.png'); 

        preload_image('<?php echo $url;?>/assets/maphome/map/map_21.png'); 

        preload_image('<?php echo $url;?>/assets/maphome/map/map_22.png'); 

        preload_image('<?php echo $url;?>/assets/maphome/map/map_27.png'); 

        preload_image('<?php echo $url;?>/assets/maphome/map/map_28.png'); 

        preload_image('<?php echo $url;?>/assets/maphome/map/map_30.png'); 

        preload_image('<?php echo $url;?>/assets/maphome/map/map_29.png'); 

    </script>



	 </div>

</div>





<div id="footer">

	<?php include("modules/footer.php"); ?>

</div>

</body>

</html>