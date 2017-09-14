<?php 
$obj_api =  new gstr_bk_for_dev_api();
//session_destroy();die;

echo "<br/>session call fromtable <br/>";
$obj_api->get_user_gstr_session_create();
echo 'otp '.$_SESSION['otp'].'<br/>';
echo 'hexcode '.$_SESSION['hexcode'].'<br/>';
echo 'hkey '.$_SESSION['hkey'].'<br/>';
echo 'hkey '.$_SESSION['hkey'].'<br/>';
echo 'auth_token '.$_SESSION['auth_token'].'<br/>';
echo 'decrypt_sess_key '.$_SESSION['decrypt_sess_key'].'<br/>';
echo 'inputToken '.$_SESSION['inputToken'].'<br/>';
echo 'keyhash '.$_SESSION['keyhash'].'<br/>';
echo 'key '.$_SESSION['key'].'<br/>';
echo 'iv '.$_SESSION['iv'].'<br/>';

echo 'ciphertext '.$_SESSION['ciphertext'].'<br/>';
echo 'ciphertext_enc '.$_SESSION['ciphertext_enc'].'<br/>';
echo 'auth_date '.$_SESSION['auth_date'].'<br/>';

$obj_api->authenticateToken('575757');


echo "<br/>auth call again <br/>";
echo 'otp '.$_SESSION['otp'].'<br/>';
echo 'hexcode '.$_SESSION['hexcode'].'<br/>';
echo 'hkey '.$_SESSION['hkey'].'<br/>';
echo 'hkey '.$_SESSION['hkey'].'<br/>';
echo 'auth_token '.$_SESSION['auth_token'].'<br/>';
echo 'decrypt_sess_key '.$_SESSION['decrypt_sess_key'].'<br/>';
echo 'inputToken '.$_SESSION['inputToken'].'<br/>';
echo 'keyhash '.$_SESSION['keyhash'].'<br/>';
echo 'key '.$_SESSION['key'].'<br/>';
echo 'iv '.$_SESSION['iv'].'<br/>';

echo 'ciphertext '.$_SESSION['ciphertext'].'<br/>';
echo 'ciphertext_enc '.$_SESSION['ciphertext_enc'].'<br/>';
echo 'auth_date '.$_SESSION['auth_date'].'<br/>';


?>