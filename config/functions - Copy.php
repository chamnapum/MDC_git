<?php
require 'dbconfig.php';
class User {
    function checkUser($uid, $oauth_provider, $username,$email,$twitter_otoken,$twitter_otoken_secret) 
	{
		if($oauth_provider=='facebook'){
			$querycheck = mysql_query("SELECT * FROM `abonnes` WHERE (fb_oauth_uid = '$uid' or email = '$email') and password<>''") or die(mysql_error());
			$checkuser = mysql_fetch_array($querycheck );
			if(!empty($checkuser)){
				
				# Have User Login
				return $checkuser;
			}else{
				$query = mysql_query("SELECT * FROM `abonnes` WHERE fb_oauth_uid = '$uid' or email = '$email'") or die(mysql_error());
				$result = mysql_fetch_array($query);
	
				if (!empty($result)) {
					# User is already present
					$query = mysql_query("SELECT * FROM `abonnes` WHERE fb_oauth_uid = '$uid' and oauth_provider = '$oauth_provider'");
					$result = mysql_fetch_array($query);
					return $result;
					
				} else {
					#user not present. Insert a new Record
					$query = mysql_query("INSERT INTO `abonnes` (oauth_provider, fb_oauth_uid, login,email) VALUES ('$oauth_provider', $uid, '$username','$email')") or die(mysql_error());
					$query = mysql_query("SELECT * FROM `abonnes` WHERE fb_oauth_uid = '$uid' and oauth_provider = '$oauth_provider'");
					$result = mysql_fetch_array($query);
					return $result;
				}
			}
			return $checkuser;
			
		}elseif($oauth_provider=='twitter'){
			$query = mysql_query("SELECT * FROM `users` WHERE tw_oauth_uid = '$uid' or email = '$email'") or die(mysql_error());
			$result = mysql_fetch_array($query);
			if (!empty($result)) {
				# User is already present
			} else {
				#user not present. Insert a new Record
				$query = mysql_query("INSERT INTO `users` (oauth_provider, tw_oauth_uid, username,email) VALUES ('$oauth_provider', $uid, '$username','$email')") or die(mysql_error());
				$query = mysql_query("SELECT * FROM `users` WHERE tw_oauth_uid = '$uid' and oauth_provider = '$oauth_provider'");
				$result = mysql_fetch_array($query);
				return $result;
			}
			return $result;
		}
		
		
        
    }

    

}

?>
