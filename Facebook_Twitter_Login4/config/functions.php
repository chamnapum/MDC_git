<?php
require 'dbconfig.php';
class User {

    function checkUser($uid, $oauth_provider, $username,$email,$twitter_otoken,$twitter_otoken_secret) 
	{
		if($oauth_provider=='facebook'){
			$querycheck = mysql_query("SELECT * FROM `utilisateur` WHERE (fb_oauth_uid = '$uid' or email = '$email') and password <>''") or die(mysql_error());
			$checkuser = mysql_fetch_array($querycheck );
			if(!empty($checkuser)){
				return $checkuser;
			}else{
				$query = mysql_query("SELECT * FROM `utilisateur` WHERE fb_oauth_uid = '$uid' or email = '$email'") or die(mysql_error());
				$result = mysql_fetch_array($query);
	
				if (!empty($result)) {
					# User is already present
					return $result;
					
				} else {
					#user not present. Insert a new Record
					$query = mysql_query("INSERT INTO `utilisateur` (oauth_provider, fb_oauth_uid, nom, email) VALUES ('$oauth_provider', $uid, '$username','$email')") or die(mysql_error());
					$query = mysql_query("SELECT * FROM `utilisateur` WHERE fb_oauth_uid = '$uid' and oauth_provider = '$oauth_provider'");
					$result = mysql_fetch_array($query);
					return $result;
				}
			}
			
			
		}
	}
}
?>
