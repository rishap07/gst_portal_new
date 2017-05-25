<?php
/*
 * 
 *  Developed By        :   Rishap Gandhi
 *  Date Created        :   May 22, 2017
 *  Last Modified       :   May 22, 2017
 *  Last Modified By    :   Rishap Gandhi
 *  Last Modification   :   Class for creating all the Masters
 * 
 */

final class master extends validation {
    protected $validateType = array(
        "alphanumeric" => "A-Za-z0-9\n\r\&\/\-\(\)\,\.",
        "mobilenumber" => "\d{10}",
        "content" => "^\\\"<>|",
        "pincode" => "\d{6}",
        "yearmonth" => "[0-9]{4}-(0[1-9]|1[0-2])",
        "datetime" => "[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) (2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]",
        "alphaspace"=>"a-zA-Z\s",
        "integergreaterzero"=>"[1-9][0-9]",
        "decimalgreaterzero"=>"\s*(?=.*[1-9])\d*(?:\.\d{1,2})?\s",
        "onlyzeroone"=>"01"
    );

    protected $validationMessage = array(
        'mandatory'=> 'Fill all mandatory fields',
        'inserted' => 'Added Successfully',
        'update' => 'Updated Successfully',
        'failed' => "Some error try again to submit.",
        'loginerror' => 'Username or Password Incorrect.',
        'passwordnotmatched' => 'Password not matched.',
        'usernameexist' => 'Username already exist.',
        'emailexist' => 'Email already exist.',
        'usernotexist' => 'User not exist.',
        'userexist' => 'User already exist.',
        'categoryexist' => 'Category already exist.',
        'plancategoryadd' => 'Plan Category added successfully.',
        'planadd' => 'Plan added successfully.',
        'enablecookie' => 'Please enable your cookies.',
        'apiDataBlank' => 'Enter all mandatory fields.',
        'invalidHashCode' => 'Invalid Hash Code generated.',
        'api' => 'Invalid API access.'
    );
    
    public function __construct() {
        parent::__construct();
    }
    
    /*
    * Start : State Add/Update/Delete Related All function
    */
    
    final public function addState()
    {
        $dataArr = $this->getStateData();
        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }
        if(!$this->validateState($dataArr))
        {
            return false;
        }
        $dataArr['added_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['added_date'] = date('Y-m-d H:i:s');
        if (!$this->insert($this->tableNames['state'], $dataArr)) {
            $this->setError($this->validationMessage['failed']);
            return false;
        }
        $this->setSuccess($this->validationMessage['inserted']);
        $insertid = $this->getInsertID();
        $this->logMsg("New State Added. ID : " . $insertid . ".");
        return true;
    }
    
    private function getStateData()
    {
        $dataArr = array();
        if(isset($_POST['submit']) && ($_POST['submit']=='submit' || ($_POST['submit']=='update' && isset($_GET['id']))))
        {
            $dataArr['state_name'] = isset($_POST['state_name']) ? $_POST['state_name'] : '';
            $dataArr['state_code'] = isset($_POST['state_code']) ? $_POST['state_code'] : '';
            $dataArr['status'] = isset($_POST['status']) ? $_POST['status'] : '';
        }
        return $dataArr;
    }
    
    private function validateState($dataArr) 
    {
        $rules = array(
            'state_name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:State Name',
            'state_code' => 'required||pattern:/^[' . $this->validateType['alphanumeric'] . ']+$/|#|lable_name:State Code',
            'status' => 'required||numeric|#|lable_name:Status'
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
    
    final public function updateState()
    {
        $dataArr = $this->getStateData();
        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }
        if(!$this->validateState($dataArr))
        {
            return false;
        }
        $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['update_date'] = date('Y-m-d H:i:s');
        if (!$this->update($this->tableNames['state'], $dataArr, array('state_id'=>$this->sanitize($_GET['id'])))) {
            $this->setError($this->validationMessage['failed']);
            return false;
        }
        $this->logMsg("State ID : " . $_GET['id'] . " in State Master has been updated");
        $this->setSuccess($this->validationMessage['update']);
        return true;
    }
    
    /*
    * End : State Add/Update/Delete Related All function
    */
    
    
    
    
    /*
    * Start : Receiver Add/Update/Delete Related All function
    */
    
    final public function addReceiver()
    {
        $dataArr = $this->getReceiverData();
        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }
        if(!$this->validateReceiver($dataArr))
        {
            return false;
        }
        $dataArr['added_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['added_date'] = date('Y-m-d H:i:s');
        if (!$this->insert($this->tableNames['receiver'], $dataArr)) {
            $this->setError($this->validationMessage['failed']);
            return false;
        }
        $this->setSuccess($this->validationMessage['inserted']);
        $insertid = $this->getInsertID();
        $this->logMsg("New Receiver Added. ID : " . $insertid . ".");
        return true;
    }
    
    private function getReceiverData()
    {
        $dataArr = array();
        if(isset($_POST['submit']) && ($_POST['submit']=='submit' || ($_POST['submit']=='update' && isset($_GET['id']))))
        {
            $dataArr['gstid'] = isset($_POST['gstid']) ? $_POST['gstid'] : '';
            $dataArr['name'] = isset($_POST['name']) ? $_POST['name'] : '';
            $dataArr['address'] = isset($_POST['address']) ? $_POST['address'] : '';
            $state = isset($_POST['state']) ? $_POST['state'] : '';
            $state = explode(":", $state);
            $dataArr['state'] = isset($state[0]) ? $state[0] : '';
            $dataArr['state_code'] = isset($_POST['state_code']) ? $_POST['state_code'] : '';
            $dataArr['status'] = isset($_POST['status']) ? $_POST['status'] : '';
        }
        return $dataArr;
    }
    
    private function validateReceiver($dataArr) 
    {
        $rules = array(
            'gstid' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/||min:10||max:20|#|lable_name:GSTID',
            'name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Name',
            'address' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Address',
            'state' => 'required|#|lable_name:State',
            'state_code' => 'required|#|lable_name:State Code',
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
    final public function updateReceiver()
    {
        $dataArr = $this->getReceiverData();
        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }
        if(!$this->validateReceiver($dataArr))
        {
            return false;
        }
        $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['update_date'] = date('Y-m-d H:i:s');
        if (!$this->update($this->tableNames['receiver'], $dataArr, array('receiver_id'=>$this->sanitize($_GET['id'])))) {
            $this->setError($this->validationMessage['failed']);
            return false;
        }
        $this->logMsg("Receiver ID : " . $_GET['id'] . " in Receiver Master has been updated");
        $this->setSuccess($this->validationMessage['update']);
        return true;
    }
    /*
    * End : Receiver Add/Update/Delete Related All function
    */
    
    
    
    /*
    * Start : Supplier Add/Update/Delete Related All function
    */
    
    final public function addSupplier()
    {
        $dataArr = $this->getSupplierData();
        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }
        if(!$this->validateSupplier($dataArr))
        {
            return false;
        }
        $dataArr['added_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['added_date'] = date('Y-m-d H:i:s');
        if (!$this->insert($this->tableNames['receiver'], $dataArr)) {
            $this->setError($this->validationMessage['failed']);
            return false;
        }
        $this->setSuccess($this->validationMessage['inserted']);
        $insertid = $this->getInsertID();
        $this->logMsg("New Supplier Added. ID : " . $insertid . ".");
        return true;
    }
    
    private function getSupplierData()
    {
        $dataArr = array();
        if(isset($_POST['submit']) && ($_POST['submit']=='submit' || ($_POST['submit']=='update' && isset($_GET['id']))))
        {
            $dataArr['gstid'] = isset($_POST['gstid']) ? $_POST['gstid'] : '';
            $dataArr['name'] = isset($_POST['name']) ? $_POST['name'] : '';
            $dataArr['address'] = isset($_POST['address']) ? $_POST['address'] : '';
            $state = isset($_POST['state']) ? $_POST['state'] : '';
            $state = explode(":", $state);
            $dataArr['state'] = isset($state[0]) ? $state[0] : '';
            $dataArr['state_code'] = isset($_POST['state_code']) ? $_POST['state_code'] : '';
            $dataArr['status'] = isset($_POST['status']) ? $_POST['status'] : '';
        }
        return $dataArr;
    }
    
    private function validateSupplier($dataArr) 
    {
        $rules = array(
            'gstid' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/||min:10||max:20|#|lable_name:GSTID',
            'name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Name',
            'address' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Address',
            'state' => 'required|#|lable_name:State',
            'state_code' => 'required|#|lable_name:State Code',
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
    final public function updateSupplier()
    {
        $dataArr = $this->getReceiverData();
        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }
        if(!$this->validateReceiver($dataArr))
        {
            return false;
        }
        $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['update_date'] = date('Y-m-d H:i:s');
        if (!$this->update($this->tableNames['supplier'], $dataArr, array('supplier_id'=>$this->sanitize($_GET['id'])))) {
            $this->setError($this->validationMessage['failed']);
            return false;
        }
        $this->logMsg("Supplier ID : " . $_GET['id'] . " in Supplier Master has been updated");
        $this->setSuccess($this->validationMessage['update']);
        return true;
    }
    /*
    * End : Supplier Add/Update/Delete Related All function
    */
    
    
       
    /*
    * Start : Supplier Add/Update/Delete Related All function
    */
    
    final public function addItem()
    {
        $dataArr = $this->getItemData();
        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }
        if(!$this->validateItem($dataArr))
        {
            return false;
        }
        $dataArr['added_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['added_date'] = date('Y-m-d H:i:s');
        if (!$this->insert($this->tableNames['item'], $dataArr)) {
            $this->setError($this->validationMessage['failed']);
            return false;
        }
        $this->setSuccess($this->validationMessage['inserted']);
        $insertid = $this->getInsertID();
        $this->logMsg("New Item Added. ID : " . $insertid . ".");
        return true;
    }
    
    private function getItemData()
    {
        $dataArr = array();
        if(isset($_POST['submit']) && ($_POST['submit']=='submit' || ($_POST['submit']=='update' && isset($_GET['id']))))
        {
            $dataArr['item_name'] = isset($_POST['item_name']) ? $_POST['item_name'] : '';
            $dataArr['hsn_code'] = isset($_POST['hsn_code']) ? $_POST['hsn_code'] : '';
            $dataArr['unit_price'] = isset($_POST['unit_price']) ? $_POST['unit_price'] : '';
            $dataArr['igst_tax_rate'] = isset($_POST['igst_tax_rate']) ? $_POST['igst_tax_rate'] : '';
            $dataArr['csgt_tax_rate'] = isset($_POST['csgt_tax_rate']) ? $_POST['csgt_tax_rate'] : '';
            $dataArr['sgst_tax_rate'] = isset($_POST['sgst_tax_rate']) ? $_POST['sgst_tax_rate'] : '';
            $dataArr['cess_tax_rate'] = isset($_POST['cess_tax_rate']) ? $_POST['cess_tax_rate'] : '';
            $dataArr['status'] = isset($_POST['status']) ? $_POST['status'] : '';
        }
        return $dataArr;
    }
    
    private function validateItem($dataArr) 
    {
        $rules = array(
            'item_name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Item',
            'hsn_code' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:HSN Code',
            'unit_price' => 'required||decimal|#|lable_name:Unit Price',
            'igst_tax_rate' => 'required||decimal||max:100|#|lable_name:IGST Tax Rate',
            'csgt_tax_rate' => 'required||decimal||max:100|#|lable_name:CSGT Tax Rate',
            'sgst_tax_rate' => 'required||decimal||max:100|#|lable_nameSGST Tax Rate',
            'cess_tax_rate' => 'required||decimal||max:100|#|lable_name:Cess Tax Rate',
            'status' => 'required|#|lable_name:State'
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
    final public function updateItem()
    {
        $dataArr = $this->getItemData();
        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }
        if(!$this->validateItem($dataArr))
        {
            return false;
        }
        $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['update_date'] = date('Y-m-d H:i:s');
        if (!$this->update($this->tableNames['item'], $dataArr, array('item_id'=>$this->sanitize($_GET['id'])))) {
            $this->setError($this->validationMessage['failed']);
            return false;
        }
        $this->logMsg("Item ID : " . $_GET['id'] . " in Item Master has been updated");
        $this->setSuccess($this->validationMessage['update']);
        return true;
    }
    /*
    * End : Supplier Add/Update/Delete Related All function
    */
}