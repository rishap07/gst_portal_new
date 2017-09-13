<?php
$obj_gstr = new gstr();

$response = 1;
if (!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])) 
{
    $response = 1;
} 
else 
{
    if($obj_gstr->get_user_gstr_session_create() == true) {
        $otp = isset($_SESSION['otp'])?$_SESSION['otp']:'';
        $updated_date = isset($_SESSION['auth_date'])?$_SESSION['auth_date']:'';
        $diff =  strtotime(date("Y-m-d H:i:s")) - strtotime($updated_date);
        /*** OTP Exprire after 120 MIn ****/
        if($diff <= 7200) {
            /*** upload return will call ****/
            $authTokenReturn = $obj_gstr->authenticateToken($otp,'otp_from_table');

            if($authTokenReturn ==  true) {
                $response = 0;
            }
            if($authTokenReturn == 'AUTH4033' || $authTokenReturn == 'AUTH4034' || $authTokenReturn == 'AUTH4038') {
                /*** OTP Popup page ****/ 
                $response = 1;
            }
            else {
               /*** Reload page ****/ 
               $response = 2; 
            }
        }
    }

}
if($response == 1) {
    if($obj_gstr->requestOTP('otp_request') ==  false) {
        /*** Reload page ****/ 
        $response = 2;
    }
}

echo $response;
die;