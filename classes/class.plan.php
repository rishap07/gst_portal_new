<?php
/*
 * Created by Ishwar Lal Ghiya
 * Dated: 2017-05-18
 * Created Purpose : For Subscriber Plan Purpose
*/

class plan extends validation {

    public function __construct() {
        parent::__construct();
    }
    
    public function addPlanCategory() {
        
        $dataArr['category_name'] = isset($_POST['category_name']) ? $_POST['category_name'] : '';
        $dataArr['category_month'] = isset($_POST['category_month']) ? $_POST['category_month'] : '';
        $dataArr['category_description'] = isset($_POST['category_description']) ? $_POST['category_description'] : '';
        $dataArr['plan_category_status'] = isset($_POST['plan_category_status']) ? $_POST['plan_category_status'] : '';
        
        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }

        if(!$this->validatePlanCategory($dataArr)){
            return false;
        }

        if($this->checkPlanCategoryExist($dataArr['category_name'])){
            $this->setError($this->validationMessage['categoryexist']);
            return false;
        }

        $dataInsertArray['name'] = $dataArr['category_name'];
        $dataInsertArray['month'] = $dataArr['category_month'];
        $dataInsertArray['description'] = $dataArr['category_description'];
        $dataInsertArray['status'] = $dataArr['plan_category_status'];
        $dataInsertArray['added_by'] = $_SESSION['user_detail']['user_id'];
        $dataInsertArray['added_date'] = date('Y-m-d H:i:s');
        
        if ($this->insert($this->tableNames['subscriber_plan_category'], $dataInsertArray)) {
            
            $this->setSuccess( $this->validationMessage['plancategoryadd'] );
            $insertid = $this->getInsertID();
            $this->logMsg("New Plan Category Added. ID : " . $insertid . ".");
            return true;
        } else {
            
            $this->setError($this->validationMessage['failed']);
            return false;
        }
        
        return true;
    }
    
    public function deletePlanCategory($category_id = '') {
        
        $dataConditionArray['id'] = $category_id;
        $dataUpdateArray['is_deleted'] = "1";
        $dataUpdateArray['deleted_by'] = $_SESSION['user_detail']['user_id'];
        $dataUpdateArray['deleted_date'] = date('Y-m-d H:i:s');
        
        if ($this->update($this->tableNames['subscriber_plan_category'], $dataUpdateArray, $dataConditionArray)) {
            
            $this->setSuccess( $this->validationMessage['plancategorydelete'] );
            $this->logMsg("Plan Category ID : " . $category_id . " in Subscriber Plan Category has been deleted");
            return true;
        } else {
            
            $this->setError($this->validationMessage['failed']);
            return false;
        }
        
        return true;
    }

    public function editPlanCategory() {
        
        $dataArr['category_id'] = isset($_POST['ecatid']) ? $_POST['ecatid'] : '';
        $dataArr['category_name'] = isset($_POST['category_name']) ? $_POST['category_name'] : '';
        $dataArr['category_month'] = isset($_POST['category_month']) ? $_POST['category_month'] : '';
        $dataArr['category_description'] = isset($_POST['category_description']) ? $_POST['category_description'] : '';
        $dataArr['plan_category_status'] = isset($_POST['plan_category_status']) ? $_POST['plan_category_status'] : '';

        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }

        if(!$this->validatePlanCategory($dataArr)){
            return false;
        }
        
        if($this->checkPlanCategoryExist($dataArr['category_name'], $dataArr['category_id'])){
            $this->setError($this->validationMessage['categoryexist']);
            return false;
        }
        
        $dataConditionArray['id'] = $dataArr['category_id'];
        $dataUpdateArray['name'] = $dataArr['category_name'];
        $dataUpdateArray['month'] = $dataArr['category_month'];
        $dataUpdateArray['description'] = $dataArr['category_description'];
        $dataUpdateArray['status'] = $dataArr['plan_category_status'];
        $dataUpdateArray['updated_by'] = $_SESSION['user_detail']['user_id'];
        $dataUpdateArray['update_date'] = date('Y-m-d H:i:s');
        
        if ($this->update($this->tableNames['subscriber_plan_category'], $dataUpdateArray, $dataConditionArray)) {
            
            $this->setSuccess( $this->validationMessage['plancategoryedit'] );
            $this->logMsg("Plan Category ID : " . $dataConditionArray['id'] . " in Subscriber Plan Category has been updated");
            return true;
        } else {
            
            $this->setError($this->validationMessage['failed']);
            return false;
        }
        
        return true;
    }
    
    public function validatePlanCategory($dataArr) {

        $rules = array(
            'category_name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Name',
            'category_month' => 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '*$/|#|lable_name:Month',
            'category_description' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Description',
            'plan_category_status' => 'required||pattern:/^[' . $this->validateType['onlyzeroone'] . ']*$/|#|lable_name:Status'
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
    
    public function checkPlanCategoryExist($category_name, $category_id = '') {

        if($category_id && $category_id != '') {
            $checkPlanCategory = $this->get_row("select * from " . $this->tableNames['subscriber_plan_category'] ." where 1=1 AND id != ".$category_id." AND name = '".$category_name."'");
        } else {
            $checkPlanCategory = $this->get_row("select * from " . $this->tableNames['subscriber_plan_category'] ." where 1=1 AND name = '".$category_name."'");
        }
        
        if(count($checkPlanCategory) == 1) {
            return true;
        }
    }
    
    public function getPlanCategories() {
        
        $allCategoties = $this->get_results("select * from " . $this->tableNames['subscriber_plan_category'] ." where 1=1 AND is_deleted='0'");

        $dataCategory = array();
        if ( !empty($allCategoties) ) {
            
            $dataCategory['data'] = $allCategoties;
            $dataCategory['status'] = 'success';
        } else {
            $dataCategory['data'] = '';
            $dataCategory['status'] = 'error';
        }

        return $dataCategory;
    }
    
    public function getPlanCategoryDetails( $categoryid = '' ) {
        
        $data = $this->get_row("select * from " . $this->tableNames['subscriber_plan_category'] ." where id = '".$categoryid."' AND is_deleted='0'");
        $dataArr = array();
        if (!empty($data)) {
            $dataArr['data'] = $data;
            $dataArr['message'] = $this->validationMessage['categoryexist'];
            $dataArr['status'] = 'success';
        } else {
            $dataArr['data'] = '';
            $dataArr['message'] = $this->validationMessage['nocategoryexist'];
            $dataArr['status'] = 'error';
        }

        return $dataArr;
    }
    
    public function addPlan() {
        
        $dataArr['plan_name'] = isset($_POST['plan_name']) ? $_POST['plan_name'] : '';
        $dataArr['plan_description'] = isset($_POST['plan_description']) ? $_POST['plan_description'] : '';
        $dataArr['no_of_client'] = isset($_POST['no_of_client']) ? $_POST['no_of_client'] : '';
        $dataArr['plan_period'] = isset($_POST['plan_period']) ? $_POST['plan_period'] : '';
        $dataArr['plan_price'] = isset($_POST['plan_price']) ? $_POST['plan_price'] : '';
        $dataArr['plan_visibility'] = isset($_POST['plan_visibility']) ? $_POST['plan_visibility'] : '';
        $dataArr['plan_status'] = isset($_POST['plan_status']) ? $_POST['plan_status'] : '';

        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }

        if(!$this->validatePlan($dataArr)){
            return false;
        }
        
        $dataInsertArray['name'] = $dataArr['plan_name'];
        $dataInsertArray['description'] = $dataArr['plan_description'];
        $dataInsertArray['no_of_client'] = $dataArr['no_of_client'];
        $dataInsertArray['plan_category'] = $dataArr['plan_period'];
        $dataInsertArray['plan_price'] = $dataArr['plan_price'];
        $dataInsertArray['status'] = $dataArr['plan_status'];
        $dataInsertArray['visible'] = $dataArr['plan_visibility'];        
        $dataInsertArray['added_by'] = $_SESSION['user_detail']['user_id'];
        $dataInsertArray['added_date'] = date('Y-m-d H:i:s');
        
        if ($this->insert($this->tableNames['subscriber_plan'], $dataInsertArray)) {
            
            $this->setSuccess( $this->validationMessage['planadd'] );
            $insertid = $this->getInsertID();
            $this->logMsg("New Plan Added. ID : " . $insertid . ".");
            return true;
        } else {
            $this->setError($this->validationMessage['failed']);
            return false;
        }

        return true;
    }
    
    public function deletePlan($plan_id = '') {
        
        $dataConditionArray['id'] = $plan_id;
        $dataUpdateArray['is_deleted'] = "1";
        $dataUpdateArray['deleted_by'] = $_SESSION['user_detail']['user_id'];
        $dataUpdateArray['deleted_date'] = date('Y-m-d H:i:s');
        
        if ($this->update($this->tableNames['subscriber_plan'], $dataUpdateArray, $dataConditionArray)) {
            
            $this->setSuccess( $this->validationMessage['plandelete'] );
            $this->logMsg("Plan ID : " . $plan_id . " in Subscriber Plan has been deleted");
            return true;
        } else {
            
            $this->setError($this->validationMessage['failed']);
            return false;
        }
        
        return true;
    }

    public function editPlan() {

        $dataArr['plan_id'] = isset($_POST['eplanid']) ? $_POST['eplanid'] : '';
        $dataArr['plan_name'] = isset($_POST['plan_name']) ? $_POST['plan_name'] : '';
        $dataArr['plan_description'] = isset($_POST['plan_description']) ? $_POST['plan_description'] : '';
        $dataArr['no_of_client'] = isset($_POST['no_of_client']) ? $_POST['no_of_client'] : '';
        $dataArr['plan_period'] = isset($_POST['plan_period']) ? $_POST['plan_period'] : '';
        $dataArr['plan_price'] = isset($_POST['plan_price']) ? $_POST['plan_price'] : '';
        $dataArr['plan_visibility'] = isset($_POST['plan_visibility']) ? $_POST['plan_visibility'] : '';
        $dataArr['plan_status'] = isset($_POST['plan_status']) ? $_POST['plan_status'] : '';
        $dataArr['pan_num'] = isset($_POST['pan_num']) ? $_POST['pan_num'] : '';
        $dataArr['company_no'] = isset($_POST['company_no']) ? $_POST['company_no'] : '';
        $dataArr['support'] = isset($_POST['support']) ? $_POST['support'] : '';
        $dataArr['cloud_storage_gb'] = isset($_POST['cloud_storage_gb']) ? $_POST['cloud_storage_gb'] : '';
        $dataArr['gst_expert_help'] = isset($_POST['gst_expert_help']) ? $_POST['gst_expert_help'] : '';
        
        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }

        if(!$this->validatePlan($dataArr)){
            return false;
        }
        
        /*if($this->checkPlanExist($dataArr['plan_period'], $dataArr['plan_id'])){
            $this->setError($this->validationMessage['categoryexist']);
            return false;
        }*/
        
        $dataConditionArray['id'] = $dataArr['plan_id'];
        $dataUpdateArray['name'] = $dataArr['plan_name'];
        $dataUpdateArray['description'] = $dataArr['plan_description'];
        $dataUpdateArray['no_of_client'] = $dataArr['no_of_client'];
        $dataUpdateArray['plan_category'] = $dataArr['plan_period'];
        $dataUpdateArray['plan_price'] = $dataArr['plan_price'];
        $dataUpdateArray['status'] = $dataArr['plan_status'];
        $dataUpdateArray['pan_num'] = $dataArr['pan_num'];
        $dataUpdateArray['company_no'] = $dataArr['company_no'];
        $dataUpdateArray['support'] = $dataArr['support'];
        $dataUpdateArray['cloud_storage_gb'] = $dataArr['cloud_storage_gb'];
        $dataUpdateArray['gst_expert_help'] = $dataArr['gst_expert_help'];
        $dataUpdateArray['visible'] = $dataArr['plan_visibility'];
        $dataUpdateArray['updated_by'] = $_SESSION['user_detail']['user_id'];
        $dataUpdateArray['update_date'] = date('Y-m-d H:i:s');
        
        if ($this->update($this->tableNames['subscriber_plan'], $dataUpdateArray, $dataConditionArray)) {
            
            $this->setSuccess( $this->validationMessage['planedit'] );
            $this->logMsg("Plan ID : " . $dataConditionArray['id'] . " in Subscriber Plan has been updated");
            return true;
        } else {
            
            $this->setError($this->validationMessage['failed']);
            return false;
        }
        
        return true;
    }

    public function getPlanDetails( $planid = '' ) {

        $data = $this->get_row("select * from " . $this->tableNames['subscriber_plan'] ." where id = '".$planid."' AND is_deleted='0'");
        $dataArr = array();
        if (!empty($data)) {
            $dataArr['data'] = $data;
            $dataArr['message'] = $this->validationMessage['planexist'];
            $dataArr['status'] = 'success';
        } else {
            $dataArr['data'] = '';
            $dataArr['message'] = $this->validationMessage['noplanexist'];
            $dataArr['status'] = 'error';
        }

        return $dataArr;
    }    
    
    public function validatePlan($dataArr) {

        $rules = array(
            'plan_name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Name',
            'plan_description' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Description',
            'no_of_client' => 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '*$/|#|lable_name:Client',
            'plan_period' => 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '*$/|#|lable_name:Month',
            'plan_price' => 'required||decimal|#|lable_name:Price',
            'plan_visibility' => 'required||pattern:/^[' . $this->validateType['onlyzeroone'] . ']*$/|#|lable_name:Visibility',
            'plan_status' => 'required||pattern:/^[' . $this->validateType['onlyzeroone'] . ']*$/|#|lable_name:Status'
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
}
?>