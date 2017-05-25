<?php
if(isset($_POST['submit']))
{
    $post['user_name']= $_POST['username'];
    $post['password']= $_POST['password'];
    $post['api_code']= 'XYZ';
    $post['api_user']= 'ABC';
    $val = '';
    foreach($post as $key=>$value)
    {
        $val.=$value."|";
    }
    $post['secure_hash']= strtoupper(md5($val));
    $post['api_method']= 'login';
    $curl = curl_init("http://10.0.16.145/projects/gst_portal/api.php");
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    echo $server_output = curl_exec ($curl);
    curl_close ($curl);
}
?>
<form method="post" url="">
    <input type="type" name="username"><br>
    <input type="password"  name="password"><br>
    <input type="submit" name="submit">
</form>