<?php
require 'facebook/facebook.php';
require 'config/fbconfig.php';
require 'config/functions.php';

$facebook = new Facebook(array(
            'appId' => APP_ID,
            'secret' => APP_SECRET,
            ));

$user = $facebook->getUser();
/*echo"<script>alert('".$user."');</script>";*/
if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
    if (!empty($user_profile )) {
        # User info ok? Let's print it (Here we will be adding the login and registering routines)
  
        $username = $user_profile['name'];
		$uid = $user_profile['id'];
		$email = $user_profile['email'];
				
        $user = new User();
        $userdata = $user->checkUser($uid, 'facebook', $username,$email,$twitter_otoken,$twitter_otoken_secret);
        if(empty($userdata['password'])){
            session_start();
			
            $_SESSION['id'] = $userdata['id'];
 			$_SESSION['oauth_id'] = $uid;
            $_SESSION['username'] = $userdata['nom'];
			$_SESSION['email'] = $email;
			$_SESSION['password'] = $userdata['password'];
            $_SESSION['oauth_provider'] = $userdata['oauth_provider'];
			
            header("Location: inscription.php");
         }else{
			$checktype=mysql_fetch_array(mysql_query("select * from utilisateur where id='".$userdata['id']."' AND activate='0'"));
			if($checktype){
				header("Location:inscription.php");
			}else{
				header("Location:authetification.php?kt_login1&kt_login_user=".$userdata['email']."&kt_login_password=".$userdata['password']."&action=1");
			}
		}
    } else {
        # For testing purposes, if there was an error, let's kill the script
        die("There was an error.");
    }
} else {
    # There's no active session, let's generate one
	$login_url = $facebook->getLoginUrl(array( 'scope' => 'email'));
    header("Location:".$login_url);
}
?>
