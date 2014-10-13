<?php
// Array definitions
  $tNG_login_config = array();
  $tNG_login_config_session = array();
  $tNG_login_config_redirect_success  = array();
  $tNG_login_config_redirect_failed  = array();
  $tNG_login_config_redirect_success = array();
  $tNG_login_config_redirect_failed = array();

// Start Variable definitions
  $tNG_debug_mode = "DEVELOPMENT"; //PRODUCTION
  $tNG_debug_log_type = "";
  $tNG_debug_email_to = "you@yoursite.com";
  $tNG_debug_email_subject = "[BUG] The site went down";
  $tNG_debug_email_from = "webserver@yoursite.com";
  $tNG_email_host = "mail.magasinducoin.com";
  $tNG_email_user = "webmaster@magasinducoin.com";
  $tNG_email_port = "26";
  $tNG_email_password = "Sikofiko12";
  $tNG_email_defaultFrom = "webmaster@magasinducoin.com";
  $tNG_login_config["connection"] = "magazinducoin";
  $tNG_login_config["table"] = "utilisateur";
  $tNG_login_config["pk_field"] = "id";
  $tNG_login_config["pk_type"] = "NUMERIC_TYPE";
  $tNG_login_config["email_field"] = "email";
  $tNG_login_config["user_field"] = "email";
  $tNG_login_config["password_field"] = "password";
  $tNG_login_config["level_field"] = "level";
  $tNG_login_config["level_type"] = "NUMERIC_TYPE";
  $tNG_login_config["randomkey_field"] = "";
  $tNG_login_config["activation_field"] = "activate";
  $tNG_login_config["password_encrypt"] = "true";
  $tNG_login_config["autologin_expires"] = "30";
  $tNG_login_config["redirect_failed"] = "authetification.php";
  $tNG_login_config["redirect_success"] = "membre.php?ini=1";
  $tNG_login_config["login_page"] = "authetification.php";
  $tNG_login_config["max_tries"] = "";
  $tNG_login_config["max_tries_field"] = "";
  $tNG_login_config["max_tries_disableinterval"] = "";
  $tNG_login_config["max_tries_disabledate_field"] = "";
  $tNG_login_config["registration_date_field"] = "";
  $tNG_login_config["expiration_interval_field"] = "";
  $tNG_login_config["expiration_interval_default"] = "";
  $tNG_login_config["logger_pk"] = "";
  $tNG_login_config["logger_table"] = "";
  $tNG_login_config["logger_user_id"] = "";
  $tNG_login_config["logger_ip"] = "";
  $tNG_login_config["logger_datein"] = "";
  $tNG_login_config["logger_datelastactivity"] = "";
  $tNG_login_config["logger_session"] = "";
  $tNG_login_config_redirect_success["1"] = "membre.php?ini=1";
  $tNG_login_config_redirect_failed["1"] = "authetification.php";
  $tNG_login_config_session["kt_login_id"] = "id";
  $tNG_login_config_session["kt_login_user"] = "email";
  $tNG_login_config_session["kt_login_level"] = "level";
  $tNG_login_config_session["kt_prenom"] = "prenom";
  $tNG_login_config_session["kt_nom"] = "nom";
  $tNG_login_config_session["kt_region"] = "region";
  $tNG_login_config_redirect_success["3"] = "membrep.php";
  $tNG_login_config_redirect_failed["3"] = "authetification.php";
  $tNG_login_config_session["kt_payer"] = "payer";
  $tNG_login_config_session["kt_adresse"] = "adresse";
  $tNG_login_config_session["kt_ville"] = "ville";
  $tNG_login_config_session["kt_credit"] = "credit";
  $tNG_login_config_redirect_success["4"] = "12admin3$/index.php";
  $tNG_login_config_redirect_failed["4"] = "authetification.php";
// End Variable definitions
?>