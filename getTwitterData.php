<?php



require("twitter/twitteroauth.php");

require 'config/twconfig.php';

require 'config/functions.php';

session_start();





if (!empty($_GET['oauth_verifier']) && !empty($_SESSION['oauth_token']) && !empty($_SESSION['oauth_token_secret'])) {

    // We've got everything we need

    $twitteroauth = new TwitterOAuth(YOUR_CONSUMER_KEY, YOUR_CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

// Let's request the access token

    $access_token = $twitteroauth->getAccessToken($_GET['oauth_verifier']);

// Save it in a session var

    $_SESSION['access_token'] = $access_token;

// Let's get the user's info

    $user_info = $twitteroauth->get('account/verify_credentials');

// Print user's info

    //echo '<pre>';

    //print_r($user_info);

    //echo '</pre><br/>';

    if (isset($user_info->error)) {

        // Something's wrong, go back to square 1  

        header('Location: login-twitter.php');

    } else {

	   $twitter_otoken=$_SESSION['oauth_token'];

	   $twitter_otoken_secret=$_SESSION['oauth_token_secret'];

	   $email='';

        $uid = $user_info->id;

        $username = $user_info->name;

        $user = new User();

		

        $userdata = $user->checkUser($uid, 'twitter', $username,$email,$twitter_otoken,$twitter_otoken_secret);

		

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

    }

} else {

    // Something's missing, go back to square 1

    header('Location: login-twitter.php');

}

?>

