<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
*/
$client_obj = new client();
if(isset($_GET['id']))
{
    $_SESSION['user_detail']['user_group']='4';

    $_SESSION['publisher']['user_id']=$_SESSION['user_detail']['user_id'];
    $_SESSION['user_detail']['user_id']=$_GET['id'];
    unset($_SESSION['user_role']);
    $query = "select b.role_page,a.can_read,a.can_create,a.can_update,a.can_delete from ".$client_obj->getTableName('user_role_permission')." a left join ".$client_obj->getTableName('user_role')." b on a.role_id=b.user_role_id where a.group_id='4' and a.is_deleted='0' and a.status='1'";
    $dataGrps= $client_obj->get_results($query);
    foreach($dataGrps as $dataGrp)
    {
        $_SESSION['user_role'][$dataGrp->role_page]['can_read'] = $dataGrp->can_read;
        $_SESSION['user_role'][$dataGrp->role_page]['can_create'] = $dataGrp->can_create;
        $_SESSION['user_role'][$dataGrp->role_page]['can_update'] = $dataGrp->can_update;
        $_SESSION['user_role'][$dataGrp->role_page]['can_delete'] = $dataGrp->can_delete;
    }
    $client_obj->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}
else if(isset($_GET['permission']) && $_GET['permission']=='revert')
{
    $_SESSION['user_detail']['user_group']='3';
    $_SESSION['user_detail']['user_id']=$_SESSION['publisher']['user_id'];
    if(isset($_SESSION['publisher']))
    {
         unset($_SESSION['publisher']);
    }
    unset($_SESSION['user_role']);
    $query = "select b.role_page,a.can_read,a.can_create,a.can_update,a.can_delete from ".$client_obj->getTableName('user_role_permission')." a left join ".$client_obj->getTableName('user_role')." b on a.role_id=b.user_role_id where a.group_id='3' and a.is_deleted='0' and a.status='1'";
    $dataGrps= $client_obj->get_results($query);
    foreach($dataGrps as $dataGrp)
    {
        $_SESSION['user_role'][$dataGrp->role_page]['can_read'] = $dataGrp->can_read;
        $_SESSION['user_role'][$dataGrp->role_page]['can_create'] = $dataGrp->can_create;
        $_SESSION['user_role'][$dataGrp->role_page]['can_update'] = $dataGrp->can_update;
        $_SESSION['user_role'][$dataGrp->role_page]['can_delete'] = $dataGrp->can_delete;
    }
    $client_obj->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}