<?php
/*
 * Created by Ishwar Lal Ghiya
 * Dated: 2017-05-18
 * Created Purpose : For Subscriber Plan Purpose
*/

class emailtemplate extends validation {

    public function __construct() {
        parent::__construct();
    }
    
    
    public function addEmailTemplate() {
        
        $dataArr['name'] = isset($_POST['emailtemplate_name']) ? $_POST['emailtemplate_name'] : '';
        $dataArr['subject'] = isset($_POST['emailtemplate_subject']) ? $_POST['emailtemplate_subject'] : '';
        $dataArr['body'] = isset($_POST['emailtemplate_body']) ? $_POST['emailtemplate_body'] : '';
        $dataArr['status'] = isset($_POST['emailtemplate_status']) ? $_POST['emailtemplate_status'] : '';
        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }

        if(!$this->validateEmailTemplate($dataArr)){
            return false;
        }
        
        $dataInsertArray['name'] = $dataArr['name'];
        $dataInsertArray['subject'] = $dataArr['subject'];
        $dataInsertArray['body'] = $dataArr['body'];   
        $dataInsertArray['added_by'] = $_SESSION['user_detail']['user_id'];
        $dataInsertArray['added_date'] = date('Y-m-d H:i:s');
        $dataInsertArray['status'] = $dataArr['status'];
        $dataInsertArray['status'] = '1';

        if ($this->insert($this->tableNames['email_templates'], $dataInsertArray)) {
            
            $this->setSuccess('Email template added succussfuly!');
            $insertid = $this->getInsertID();
            $this->logMsg("New Email Template Added. ID : " . $insertid . ".","emailtemplate_add");
            return true;
        } else {
            $this->setError($this->validationMessage['failed']);
            return false;
        }

        return true;
    }
    
    public function deleteEmailTemplate($id = '') {
        
        $dataConditionArray['id'] = $id;
        $dataUpdateArray['is_deleted'] = "1";
        $dataUpdateArray['deleted_date'] = date('Y-m-d H:i:s');
        
        if ($this->update($this->tableNames['email_templates'], $dataUpdateArray, $dataConditionArray)) {
            
            $this->setSuccess( $this->validationMessage['emailtemplatedelete'] );
            $this->logMsg("Email Template  : " . $id . " has been deleted","emailtemplate_delete");
            return true;
        } else {
            
            $this->setError($this->validationMessage['failed']);
            return false;
        }
        
        return true;
    }

    public function editEmailTemplate() {
        $dataArr['id'] = isset($_POST['emailtemplate_id']) ? $_POST['emailtemplate_id'] : '';
        $dataArr['name'] = isset($_POST['emailtemplate_name']) ? $_POST['emailtemplate_name'] : '';
        $dataArr['subject'] = isset($_POST['emailtemplate_subject']) ? $_POST['emailtemplate_subject'] : '';
        $dataArr['body'] = isset($_POST['emailtemplate_body']) ? $_POST['emailtemplate_body'] : '';
        $dataArr['status'] = isset($_POST['emailtemplate_status']) ? $_POST['emailtemplate_status'] : '';
        
        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }

        if(!$this->validateEmailTemplate($dataArr)){
            return false;
        }
        
       
        $dataConditionArray['id'] = $dataArr['id'];
        $dataUpdateArray['name'] = $dataArr['name'];
        $dataUpdateArray['subject'] = $dataArr['subject'];
        $dataUpdateArray['body'] = $dataArr['body'];
        $dataUpdateArray['status'] = $dataArr['status'];
        $dataUpdateArray['update_date'] = date('Y-m-d H:i:s');
        
        if ($this->update($this->tableNames['email_templates'], $dataUpdateArray, $dataConditionArray)) {
            
            $this->setSuccess('Email template updated succussfuly!');
            $this->logMsg("Email Template ID : " . $dataConditionArray['id'] . "  has been updated","emailtemplate_edit");
            return true;
        } else {
            
            $this->setError($this->validationMessage['failed']);
            return false;
        }
        
        return true;
    }

    public function getEmailTemplateDetails( $emailtemplateid = '' ) {

        $data = $this->get_row("select * from " . $this->tableNames['email_templates'] ." where id = '".$emailtemplateid."' AND is_deleted='0'");
        $dataArr = array();
        if (!empty($data)) {
            $dataArr['data'] = $data;
            $dataArr['message'] = 'Email Template Already exists';
            $dataArr['status'] = 'success';
        } else {
            $dataArr['data'] = '';
            $dataArr['message'] = 'Sorry! Email Template bot found';
            $dataArr['status'] = 'error';
        }

        return $dataArr;
    } 
    
    public function validateEmailTemplate($dataArr) {

        $rules = array(
            'name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Name',
            'subject' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Subject',
           // 'body' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Body',
            
        );
        
        $valid = $this->vali_obj->validate($dataArr, $rules);
        if ($valid->hasErrors()) {
            $err_arr = $valid->allErrors();
            $this->setError($err_arr);
            $valid->clearMessages();
            return false;
        }
        return true;
    }

    public function addUserModuleEmail() {
        
    }
}
?>