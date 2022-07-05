<?php
/**
 * DATABASE
 */

if (strpos($_SERVER['HTTP_HOST'], "localhost")) {
    define("CONF_DB_HOST", "localhost");
    define("CONF_DB_USER", "root");
    define("CONF_DB_PASS", "");
    define("CONF_DB_NAME", "php_upside");
}else{
    define("CONF_DB_HOST", "192.185.209.175");
    define("CONF_DB_USER", "proj5059_cafeadm");
    define("CONF_DB_PASS", "sennin90#$");
    define("CONF_DB_NAME", "proj5059_cafecontrol");
}



/**
 * PROJECT URLs
 */
define("CONF_URL_BASE", "https://www.projetosdeploy.com.br");
define("CONF_URL_TEST", "https://www.localhost/fsphp");
/*define("CONF_URL_ADMIN", "/admin");*/

/* SITE */
define("CONF_SITE_NAME", "CaféControl");
define("CONF_SITE_TITLE", "Gerencie suas contas no prazer do café");
define("CONF_SITE_DESC", "O CafeControl é um gerenciador de contas simples, poderoso e gratuito. O prazer de tomar um café e ter o controle total de suas contas.");
define("CONF_SITE_LANG", "pt_BR");
define("CONF_SITE_DOMAIN", "projetosdeploy.com.br");
define("CONF_SITE_ADDR_STREET", "Rua José Luiz Regal");
define("CONF_SITE_ADDR_NUMBER", "96");
define("CONF_SITE_ADDR_CITY", "Sorocaba");
define("CONF_SITE_ADDR_STATE", "São Paulo");
define("CONF_SITE_ADDR_ZIPCODE", "18074-135");


/* SOCIAL */
define("CONF_SOCIAL_TWITTER_CREATOR","@HenriqueJArauj1");
define("CONF_SOCIAL_TWITTER_PUBLISHER","@HenriqueJArauj1");
define("CONF_SOCIAL_FACEBOOK_APP","170478997864612");
define("CONF_SOCIAL_FACEBOOK_PAGE","henrique.araujo.1257604");
define("CONF_SOCIAL_FACEBOOK_AUTHOR","HenriqueAraujo");
define("CONF_SOCIAL_INSTAGRAM_PAGE","henrique_j_a");

/* DATES */

define("CONF_DATE_BR", "d/m/Y H:i:s");
define("CONF_DATE_APP", "Y-m-d H:i:s");


/* PASSWORD */

define("CONF_PASSWD_MIN_LEN", 8);
define("CONF_PASSWD_MAX_LEN", 40);
define("CONF_PASSWD_ALGO", PASSWORD_DEFAULT);
define("CONF_PASSWD_OPTION", ["cost" => 10]);

/* MESSAGE */

define("CONF_MESSAGE_CLASS", "message");
define("CONF_MESSAGE_INFO", "info icon-info");
define("CONF_MESSAGE_SUCCESS", "success icon-check-square-o");
define("CONF_MESSAGE_WARNING", "warning icon-warning");
define("CONF_MESSAGE_ERROR", "error icon-warning");

/* CONFIG VIEW */

define("CONF_VIEW_PATH", __DIR__ . "/../../shared/views");
define("CONF_VIEW_EXT", "php");
define("CONF_VIEW_THEME", "cafeweb");
define("CONF_VIEW_APP", "cafeapp");
define("CONF_VIEW_ADMIN", "cafeadm");

/* UPLOAD */

define("CONF_UPLOAD_DIR","storage");
define("CONF_UPLOAD_IMAGE_DIR", "images");
define("CONF_UPLOAD_FILE_DIR", "files");
define("CONF_UPLOAD_MEDIA_DIR", "medias");

/* IMAGES */

define("CONF_IMAGE_CACHE", CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR . "/cache" );
define("CONF_IMAGE_SIZE", 2000);
define("CONF_IMAGE_QUALITY", ["jpg" => 75, "png" => 5]);

/* CONFIG PHPMAILER */

//AUTH
define("CONF_MAIL_HOST", "smtp.gmail.com");
define("CONF_MAIL_PORT", "587");
define("CONF_MAIL_USER", "theenemybrasil@gmail.com");
define("CONF_MAIL_PASS", "sennin90");
define("CONF_MAIL_SENDER", ["name" => "Henirque.Dev", "address" => "liderhenrique@gmail.com"]);
define("CONF_MAIL_SUPPORT", "liderhenrique2@gmail.com");

//CONFIG
define("CONF_MAIL_OPTIONS_LANG", "br");
define("CONF_MAIL_OPTIONS_HTML", true);
define("CONF_MAIL_OPTIONS_AUTH", true);
define("CONF_MAIL_OPTIONS_SECURE", "tls");
define("CONF_MAIL_OPTIONS_CHARSET", "utf-8");

//Pagar.Me
define("CONF_PAGARME_MODE", "test");
define("CONF_PAGARME_LIVE", "ak_live_LqbEjWAt2DTxYYTyfaGoj9Rsrt6E7h");
define("CONF_PAGARME_TEST", "ak_test_LqbEjWAt2DTxYYTyfaGoj9Rsrt6E7h");
define("CONF_PAGARME_BACK", CONF_URL_BASE."/pay/callback");

