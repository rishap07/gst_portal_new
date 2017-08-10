<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$obj_client = new client();
if (isset($_GET['id']) && $obj_client->validateId($_GET['id'])) {

    $dataCurrentArr = $obj_client->getUserDetailsById($obj_client->sanitize($_GET['id']));
    if($dataCurrentArr['data']->added_by!=$_SESSION['user_detail']['user_id'])
    {
        $obj_client->setError("Invalid Login Access.");
        $obj_client->redirect(PROJECT_URL . "/?page=dashboard");
        exit();
    }
    if ($dataCurrentArr['data']->kyc == '') {
        $obj_client->setError("First update client KYC.");
        $obj_client->redirect(PROJECT_URL . "/?page=dashboard");
        exit();
    }
    
    $_SESSION['user_detail']['user_group'] = '4';
    $_SESSION['publisher']['user_id'] = $_SESSION['user_detail']['user_id'];
    $_SESSION['user_detail']['user_id'] = $_GET['id'];
    unset($_SESSION['user_role']);

    $query = "select b.role_page,a.can_read,a.can_create,a.can_update,a.can_delete from " . $obj_client->getTableName('user_role_permission') . " a left join " . $obj_client->getTableName('user_role') . " b on a.role_id=b.user_role_id where a.group_id='4' and a.is_deleted='0' and a.status='1'";
    $dataGrps = $obj_client->get_results($query);
    foreach ($dataGrps as $dataGrp) {

        $_SESSION['user_role'][$dataGrp->role_page]['can_read'] = $dataGrp->can_read;
        $_SESSION['user_role'][$dataGrp->role_page]['can_create'] = $dataGrp->can_create;
        $_SESSION['user_role'][$dataGrp->role_page]['can_update'] = $dataGrp->can_update;
        $_SESSION['user_role'][$dataGrp->role_page]['can_delete'] = $dataGrp->can_delete;
    }
    $obj_client->redirect(PROJECT_URL . "/?page=dashboard");
    exit();
} else if (isset($_GET['permission']) && $_GET['permission'] == 'revert') {

    $_SESSION['user_detail']['user_group'] = '3';
    $_SESSION['user_detail']['user_id'] = $_SESSION['publisher']['user_id'];

    if (isset($_SESSION['publisher'])) {
        unset($_SESSION['publisher']);
    }
    unset($_SESSION['user_role']);

    $query = "select b.role_page,a.can_read,a.can_create,a.can_update,a.can_delete from " . $obj_client->getTableName('user_role_permission') . " a left join " . $obj_client->getTableName('user_role') . " b on a.role_id=b.user_role_id where a.group_id='3' and a.is_deleted='0' and a.status='1'";
    $dataGrps = $obj_client->get_results($query);
    foreach ($dataGrps as $dataGrp) {

        $_SESSION['user_role'][$dataGrp->role_page]['can_read'] = $dataGrp->can_read;
        $_SESSION['user_role'][$dataGrp->role_page]['can_create'] = $dataGrp->can_create;
        $_SESSION['user_role'][$dataGrp->role_page]['can_update'] = $dataGrp->can_update;
        $_SESSION['user_role'][$dataGrp->role_page]['can_delete'] = $dataGrp->can_delete;
    }
    $obj_client->redirect(PROJECT_URL . "/?page=dashboard");
    exit();
}