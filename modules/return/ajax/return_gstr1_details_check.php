<?php

$obj_gstr = new gstr();

$error = 1;
if (!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])) 
{
    $error = 1;
} 
else 
{

    if($obj_gstr->get_user_gstr_session_create() == true) {
        $otp = isset($_SESSION['otp'])?$_SESSION['otp']:'';
        $added_date = isset($_SESSION['added_date'])?$_SESSION['added_date']:'';
        $diff =  strtotime(date("Y-m-d H:i:s")) - strtotime($added_date);
        // for 120  min only*/
        if($diff <= 7200) {
            //upload return will call
            if($obj_gstr->authenticateToken($otp,,'otp_submit') ==  true) {
                $error = 0;
            }
            else {
                $error = 3;
            }
        }
    }

}
if($error == 1) {
    if($obj_gstr->requestOTP('Check') ==  false) {
        $error = 3;
    }
}



echo $error;
die;