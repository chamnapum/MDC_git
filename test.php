<?php 

function distance($lat1, $lon1, $lat2, $lon2)
{
$lat1 = deg2rad($lat1);
$lat2 = deg2rad($lat2);
$lon1 = deg2rad($lon1);
$lon2 = deg2rad($lon2);

$R = 6371;
$dLat = $lat2 - $lat1;
$dLong = $lon2 - $lon1;
$var1= $dLong/2;
$var2= $dLat/2;
$a= pow(sin($dLat/2), 2) + cos($lat1) * cos($lat2) * pow(sin($dLong/2), 2);
$c= 2 * atan2(sqrt($a),sqrt(1-$a));
$d= $R * $c;

//CALCUL DE L'ANGLE
$H = asin(sin($lon1)*sin($lon2) + cos($lon1)*cos($lon2)*cos($lat2-$lat1));
$A = acos((sin($lon2)-sin($lon1)*sin($H))/(cos($lon1)*cos($H)));

$val[0] = $d; //distance
//$val[1] = $A; //angle (cap)
return $d;
}


echo distance(34.033315,-6.79985, 33.958716,-6.887097);