<?php

$obj_gstr1 = new gstr1();
if(isset($_POST['submit']) && $_POST['submit']=='Download GSTR1')
{
    if (!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])) 
    {
        $obj_gstr1->setError('Invalid access to files');
    } 
    else 
    {
        if ($obj_gstr1->gstr1PayloadDownload()) 
        {
        }
    }
}
