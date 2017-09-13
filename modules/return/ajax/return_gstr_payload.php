<?php

$obj_gstr1 = new gstr1();
if((isset($_POST['submit']) && $_POST['submit']=='Download GSTR1')||(isset($_POST['submit_dwn']) && $_POST['submit_dwn']=='Download GSTR1'))
{
    if (!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])) 
    {
        $obj_gstr1->setError('Invalid access to files');
    } 
    else 
    {
        $ids = '';
        
        if(isset($_POST['name']))
        {
            $ids = implode(',',  $_POST['name']);
        }
        if(!empty($ids)) {
            if ($obj_gstr1->gstr1PayloadDownload($ids,'all')) 
            {
            } 
        }
        else {
            if ($obj_gstr1->gstr1PayloadDownload()) 
            {
            }
        }
        
    }
}
