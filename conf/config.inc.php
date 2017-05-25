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
define('CMS_FRONT_TITLE', "OBC ADMIN PORTAL");
define('CMS_ADMIN_TITLE', "OBC ADMIN PANEL");

define('ROOT_DIR', "/projects/gst_portal");
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

function __autoload($class) {
    $class = strtolower($class);
    //echo CLASSES_ROOT . "/class." . $class . ".php";
    if (file_exists(CLASSES_ROOT . "/class." . $class . ".php")) {
        include(CLASSES_ROOT . "/class." . $class . ".php");
    } else {
        die($class . ' Class Not exists');
    }
}

extract($_GET);
extract($_POST);

/* CHECK WHETHER THE FOLLOWING REQUIRED EXTENSIONS ARE ENABLED IN PHP OR NOT */
if (!extension_loaded('fileinfo')) {
    die('Please enable php fileinfo extension first to run this application properly.');
}