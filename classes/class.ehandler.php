<?php

function shutDownFunction() {
    $error = error_get_last();
    if ($error['message'] == "")
        return;
    $errstr = $error['message'] . " " . $error['file'] . " line no." . $error['line'];
    $retval = json_encode(array("responsestatus" => "ERROR", "result" => array("user_message" => "Something error, Please try after some time..", "sys_message" => $errstr)));
    echo $retval;
}

function myErrorHandler($errno, $errstr, $errfile, $errline) {
    $estr = $errstr . " " . $errfile . " line no." . $errline;
    $retval = json_encode(array("responsestatus" => "ERROR", "result" => array("user_message" => "Something error, Please try after some time..", "sys_message" => $estr)));
    die($retval);
}

register_shutdown_function('shutDownFunction');
set_error_handler('myErrorHandler');

Class ehandler {

    public static $message;
    private static $is_header_set = 0;

    function ehandler() {
        self::$message = array();
    }

    static function setMessage($str, $doflush = TRUE) {
        if ($doflush == false)
            self::$message = $str; // append to array
        else
            self::$message = $str;
    }

    static function getMessage() {
        return self::$message;
    }

    static function buildErrorResponse($str) {
        if (isset($_SERVER["HTTP_DATACONTENT"]) && self::$is_header_set == 0) {
            header("HTTP/1.1 400 Bad Request");
        }
        return json_encode(array("responsestatus" => "ERROR", "result" => $str));
    }

    // for window app sync uploading

    static function setHeader() {
        self::$is_header_set = 1;
    }

}
?>