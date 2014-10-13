<?php

session_start();

unset($_SESSION['Language']);
$_SESSION['Language'] = $_GET['lang'];

header('Location: '.$_SERVER['HTTP_REFERER']);