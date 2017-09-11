<?php

$obj_gstr1 = new gstr1();
$obj_gstr = new gstr();
$error = 1;
$msg = '';
if (!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])) 
{
    $obj_gstr1->setError('Invalid access to files');
} 
else 
{
    $otp = isset($_POST['otp'])?$_POST['otp']:'';
    if(!empty($otp)) {
        if (is_numeric($otp)) {
            if($obj_gstr->authenticateToken($otp,'otp_submit') ==  true) {
                $error = 0;
            }
        } 
        else {
            $obj_gstr1->setError('Invalid OTP, please try again.');
        }
    }
    else {
        $obj_gstr1->setError('Enter OTP First.');
    }

}

echo json_encode(array('error' => $error));
die;
