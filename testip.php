<?php
function getLatLng($ip){

//get the contents from the site by file_get_contents.
$sXML = @file_get_contents('http://ipinfodb.com/ip_query.php?ip='.$ip.'&timezone=false');
echo 'http://ipinfodb.com/ip_query.php?ip='.$ip.'&timezone=false';

//make the response in a xml object so we can parse it.
$oXML = @new SimpleXMLElement($sXML);

$array = array(
'Latitude' => (string) $oXML->Latitude[0],
'Longitude' => (string) $oXML->Longitude[0]
);
return $array;
}


$array = getLatLng($_SERVER['REMOTE_ADDR']);
print_r($array);