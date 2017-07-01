<?php
include_once('conf/config.inc.php');
$db_obj = new validation();
$theme_data = $db_obj->getTheme();
if(isset($theme_data[0]->theme_name) && $theme_data[0]->theme_name!='') {
    
	define('THEME_PATH',THEME_DIR .$theme_data[0]->theme_folder);
    define('THEME_URL',PROJECT_URL."/template/" .$theme_data[0]->theme_folder);
} else {
    
	define('THEME_PATH',THEME_DIR .'gst_portal');
    define('THEME_URL',PROJECT_URL."/template/gst_portal");
}

$pagename='';
if (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) {
    
	$pagename = $_REQUEST['page'];
} else if (isset($_REQUEST['ajax']) && !empty($_REQUEST['ajax'])) {
	
	$pagename = $_REQUEST['ajax'];
}

if (!isset($_REQUEST['ajax']) && $_SERVER['REQUEST_URI'] != ROOT_DIR . "/") {
    
	if( !isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '' ) {
		$db_obj->redirect(PROJECT_URL);
		exit();
	}

	include(THEME_PATH . '/header.php');
}

if ($pagename == '' && !isset($_REQUEST['ajax'])) {

	if ($_SERVER['REQUEST_URI'] == ROOT_DIR . "/") {
        include_once THEME_PATH."/index.php";
    } else {

		if( !isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '' ) {
			$db_obj->redirect(PROJECT_URL);
			exit();
		}

		header("HTTP/1.0 404 Not Found");
        include_once PROJECT_ROOT . "/modules/404/404.php";
    }
} else {

	if (isset($_REQUEST['page'])) {

		$name = explode('_', $pagename);
		$folder = $name[0];

        if (file_exists(PROJECT_ROOT . "/modules/" . $name[0] . "/" . $pagename . ".php")) {
            include_once PROJECT_ROOT . "/modules/" . $name[0] . "/" . $pagename . ".php";
        } else {
            header("HTTP/1.0 404 Not Found");
            include_once PROJECT_ROOT . "/modules/404/404.php";
        }
		
    } else if (isset($_REQUEST['ajax'])) {
        
		$name = explode('_', $pagename);
        $folder = $name[0];
        if (file_exists(PROJECT_ROOT . "/modules/" . $name[0] . "/ajax/" . $pagename . ".php")) {
            
            include_once PROJECT_ROOT . "/modules/" . $name[0] . "/ajax/" . $pagename . ".php";
		} else {

			if (method_exists($db_obj, $_REQUEST['ajax'])) {
                echo json_encode($db_obj->$_REQUEST['ajax']());
            } else {

				header("HTTP/1.0 404 Not Found");
                $dataArr['msg'] = '404';
                $dataArr['status'] = '404';
                echo json_encode($dataArr);
            }
        }
    }
}

if (!isset($_REQUEST['ajax']) && $_SERVER['REQUEST_URI'] != ROOT_DIR . "/") {
    include(THEME_PATH . '/footer.php');
}
?>