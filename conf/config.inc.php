<?php

/*
 * 
 *  Developed By        :   Mukesh Tiwari
 *  Description         :   A simple PHP file for configuration settings of the website.
 *  Date Created        :   October 21, 2015
 *  Last Modified       :   October 21, 2015
 *  Last Modified By    :   Mukesh Tiwari
 *  Last Modification   :   file creation started
 * 
 */
header('Content-Type: text/html; charset=utf-8');
@session_start();
@ob_start();
ini_set('default_charset', 'UTF-8');

ini_set('allow_url_fopen', '1');
#-------------------------------------------------------------------------------
# TIMEZONE SETTINGS 
#-------------------------------------------------------------------------------
date_default_timezone_set("Asia/Kolkata");


#-------------------------------------------------------------------------------
# ERROR REPORTING AND OTHER PHP SETTINGS
#-------------------------------------------------------------------------------
error_reporting(0);
ini_set('error_reporting', E_ALL ^ E_DEPRECATED);
error_reporting(E_ALL ^ E_DEPRECATED);


#-------------------------------------------------------------------------------
# DATABASE CONNECTION SETTINGS
#-------------------------------------------------------------------------------
define('DB_HOST', "localhost");
define('DB_USER', "root");
define('DB_PASS', "123");
define('DB_NAME', "gst_portal");

#-------------------------------------------------------------------------------
# SMTP CONNECTION PARAMETERS
#-------------------------------------------------------------------------------
define('SMTP_HOST', "");
define('SMTP_PORT', 25);
define('SMTP_USERNAME', "");
define('SMTP_PASSWORD', "");


#-------------------------------------------------------------------------------
# WEBSITE DEFAULT PARAMETERS
#-------------------------------------------------------------------------------
define('CMS_FRONT_TITLE', "GSTKEEPER ADMIN PORTAL");
define('CMS_ADMIN_TITLE', "GSTKEEPER ADMIN PANEL");

define('ROOT_DIR', "/projects/gst_portal_new");
#-------------------------------------------------------------------------------
# PROJECT ROOT
#-------------------------------------------------------------------------------
$lastchar = substr($_SERVER['DOCUMENT_ROOT'], -1, 1);
if ($lastchar == '/') {
    $root = substr($_SERVER['DOCUMENT_ROOT'], 0, -1);
    define('PROJECT_ROOT', $root . ROOT_DIR);
} else {
    define('PROJECT_ROOT', $_SERVER['DOCUMENT_ROOT'] . ROOT_DIR);
}


#-------------------------------------------------------------------------------
# PATH SETTINGS
#-------------------------------------------------------------------------------

define('ADMIN_DIR', "/admin");
define('IMAGES_DIR', "/images");
define('CSS_DIR', "/css");
define('THEME_DIR', PROJECT_ROOT."/template/");
define('SCRIPT_DIR', "/script");
define('MODULE_DIR', "/modules");
define('ADMIN_MODULE_DIR', "/modules");
define('CLASSES_DIR', "/classes");
define('CONFIG_DIR', "/conf");
define('INCLUDE_DIR', "/includes");
define('UPLOAD_DIR', "/upload");



define('CLASSES_ROOT', PROJECT_ROOT . CLASSES_DIR);
define('CONFIG_ROOT', PROJECT_ROOT . CONFIG_DIR);
define('INCLUDE_ROOT', PROJECT_ROOT . INCLUDE_DIR);
define('MODULE_ROOT', PROJECT_ROOT . MODULE_DIR);
define('ADMIN_ROOT', PROJECT_ROOT . ADMIN_DIR);
define('ADMIN_MODULE_ROOT', ADMIN_ROOT . ADMIN_MODULE_DIR);


#-------------------------------------------------------------------------------
# DEFINE HOST NAME
#-------------------------------------------------------------------------------
define('HOST_NAME', $_SERVER['HTTP_HOST']);

#-------------------------------------------------------------------------------
# DEFINE TABLE PREFIX
#-------------------------------------------------------------------------------
define('TAB_PREFIX',"gst_");


#-------------------------------------------------------------------------------
# URL SETTINGS
#-------------------------------------------------------------------------------

if (!isset($_SERVER['HTTPS']) || ($_SERVER['HTTPS'] == "off")) {
    define('HTTPS_MODE', "off");
    define('PROJECT_URL', "http://" . $_SERVER["HTTP_HOST"] . ROOT_DIR);
} else {
    define('HTTPS_MODE', "on");
    define('PROJECT_URL', "https://" . $_SERVER["HTTP_HOST"] . ROOT_DIR);
}

#-------------------------------------------------------------------------------
# DEFINE ADMIN URL
#-------------------------------------------------------------------------------
define('ADMIN_URL', PROJECT_URL . ADMIN_DIR);


#-------------------------------------------------------------------------------
# DEFINE MODULE URL
#-------------------------------------------------------------------------------
define('MODULE_URL', PROJECT_URL . MODULE_DIR);
define('ADMIN_MODULE_URL', ADMIN_URL . ADMIN_MODULE_DIR);

#-------------------------------------------------------------------------------
# DEFINE IMAGE FOLDER URL
#-------------------------------------------------------------------------------
define('IMAGES_URL', PROJECT_URL . IMAGES_DIR);



#-------------------------------------------------------------------------------
# DEFINE DIFFERENT URLS
#-------------------------------------------------------------------------------
define('CSS_URL', PROJECT_URL . CSS_DIR);
define('SCRIPT_URL', PROJECT_URL . SCRIPT_DIR);


#-------------------------------------------------------------------------------
# INCLUDE CLASSES FILES NEEDED WHEN AN OBJECT IS INITIALIZED
#-------------------------------------------------------------------------------

#-------------------------------------------------------------------------------
    # API URLS

define('API_TYPE', 'Demo'); //Demo or Live

if(API_TYPE == 'Demo') {

    #------------------------- DEMO ----------------------------------------------
    define('API_CLIENT_ID', 'l7xx2909cd95daee418b8118e070b6b24dd6'); 
    //'l7xx1ed437f1e18347c38bd2aad6e6dd3b3c' for dev api
    define('API_CLIENT_SECRET', 'fa6f03446473400fa21240241affe2a5');
    //'a9bcf665fe424883b7b94791eb31f667 ' for dev api

    define('API_KARVI_ID', API_CLIENT_ID);
    define('API_KARVI_SECRET', API_CLIENT_SECRET);

    define('API_OTP_URL', 'http://gsp.karvygst.com/v0.3/authenticate');
    define('API_AUTH_URL', 'http://gsp.karvygst.com/v0.3/authenticate');
    define('API_RETURN_URL', 'http://gsp.karvygst.com/v0.3/returns');

    define('API_USERNAME', 'Cyfuture1.MH.TP.2');//Cyfuture1.MH.TP.1
    define('API_GSTIN', '27GSPMH6182G1ZD');//27GSPMH6181G1ZE
    define('API_STATE_CD', '27');
    //27GSPMH6182G1ZD //Cyfuture1.MH.TP.2
}
elseif(API_TYPE == 'Live') {
    #---------------------- LIVE ------------------------------------------
    define('API_CLIENT_ID', 'l7xx77bf62d48fd045ffbbe6ad958d11a372');
    define('API_CLIENT_SECRET', 'b4afd81551ec4f8f969b171bc4373340');

    define('API_KARVI_ID', 'VYFKG###fdkfjf');
    define('API_KARVI_SECRET', 'VYFdd##fdkfjf');

    define('API_OTP_URL', 'https://gspapi.karvygst.com/Authenticate');
    define('API_AUTH_URL', 'https://gspapi.karvygst.com/Authenticate');
    define('API_RETURN_URL', 'https://gspapi.karvygst.com/returns');

}

define('API_TXN', 'TXN789123456789');
define('API_IP', '203.197.205.110');

function __autoload($class) {
    $class = strtolower($class);
    //echo CLASSES_ROOT . "/class." . $class . ".php<br>";
    if (file_exists(CLASSES_ROOT . "/class." . $class . ".php")) {
        include(CLASSES_ROOT . "/class." . $class . ".php");
    } else {
        die($class . ' Class Not exists');
    }
}

include(CLASSES_ROOT . "/PHPExcel.php");

extract($_GET);
extract($_POST);

/* CHECK WHETHER THE FOLLOWING REQUIRED EXTENSIONS ARE ENABLED IN PHP OR NOT */
if (!extension_loaded('fileinfo')) {
    die('Please enable php fileinfo extension first to run this application properly.');
}