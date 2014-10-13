<?php require_once('Connections/magazinducoin.php'); ?>

<?php

if (array_key_exists("logout", $_GET)) {

    session_start();

    unset($_SESSION['id']);

	unset($_SESSION['oauth_id']);

	unset($_SESSION['username']);

	unset($_SESSION['email']);

	unset($_SESSION['password']);

	unset($_SESSION['oauth_provider']);

	unset($_SESSION['level']);

    session_destroy();

}

?>

<?php

// Load the common classes

require_once('includes/common/KT_common.php');



// Load the tNG classes

require_once('includes/tng/tNG.inc.php');



// Make a transaction dispatcher instance

$tNGs = new tNG_dispatcher("");



// Make unified connection variable

$conn_magazinducoin = new KT_connection($magazinducoin, $database_magazinducoin);



unset($_SESSION['cart']);

// Make a logout transaction instance

$logoutTransaction = new tNG_logoutTransaction($conn_magazinducoin);

$tNGs->addTransaction($logoutTransaction);

// Register triggers

$logoutTransaction->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "VALUE", "true");

$logoutTransaction->registerTrigger("END", "Trigger_Default_Redirect", 99, "accueil.html");

// Add columns

// End of logout transaction instance



// Execute all the registered transactions

$tNGs->executeTransactions();



// Get the transaction recordset

$rscustom = $tNGs->getRecordset("custom");

$row_rscustom = mysql_fetch_assoc($rscustom);

$totalRows_rscustom = mysql_num_rows($rscustom);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Document sans titre</title>

<link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />

<script src="includes/common/js/base.js" type="text/javascript"></script>

<script src="includes/common/js/utility.js" type="text/javascript"></script>

<script src="includes/skins/style.js" type="text/javascript"></script>

</head>



<body>

<?php

	echo $tNGs->getErrorMsg();

?>



</body>

</html>