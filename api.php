<?php
include_once('conf/config.inc.php');
$api_obj = new api();
if (method_exists($api_obj, $_REQUEST['api_method'])) {
    echo json_encode($api_obj->$_REQUEST['api_method']());
} else {
    header("HTTP/1.0 404 Not Found");
    $dataArr['msg'] = '404';
    $dataArr['status'] = '404';
    echo json_encode($dataArr);
}