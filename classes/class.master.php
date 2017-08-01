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
		
	    if( $this->checkStateCodeExist($dataArr['state_code'])){
            $this->setError($this->validationMessage['statecodeexist']);
            return false;
        }
		
		if( $this->checkStateTinExist($dataArr['state_tin'])){
            $this->setError($this->validationMessage['statetinexist']);
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
	private function getvendorData()
	{
		 $dataArr = array();
	   if(!empty($_GET["id"]))
	   {
		  $dataArr['vendor_name'] = isset($_POST['vendor_name']) ? $_POST['vendor_name'] : '';
		 $dataArr['status'] = isset($_POST['vendor_status']) ? $_POST['vendor_status'] : '';
		  $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
          $dataArr['updated_date'] = date('Y-m-d H:i:s');
			     
	   }
	   else
	   {
		     $dataArr['vendor_name'] = isset($_POST['vendor_name']) ? $_POST['vendor_name'] : '';
		 $dataArr['status'] = isset($_POST['vendor_status']) ? $_POST['vendor_status'] : '';
		 	  $dataArr['added_by'] = $_SESSION['user_detail']['user_id'];
          $dataArr['added_date'] = date('Y-m-d H:i:s');
	   }
		
	
		
		 return $dataArr;
		 
       
	}
	private function getbusinessareaData()
	{
		 $dataArr = array();
	   if(!empty($_GET["id"]))
	   {
		  $dataArr['business_area_name'] = isset($_POST['business_area_name']) ? $_POST['business_area_name'] : '';
		 $dataArr['status'] = isset($_POST['business_area_status']) ? $_POST['business_area_status'] : '';
		  $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
          $dataArr['updated_date'] = date('Y-m-d H:i:s');
			     
	   }
	   else
	   {
		  $dataArr['business_area_name'] = isset($_POST['business_area_name']) ? $_POST['business_area_name'] : '';
		 $dataArr['status'] = isset($_POST['business_area_status']) ? $_POST['business_area_status'] : '';
		 	  $dataArr['added_by'] = $_SESSION['user_detail']['user_id'];
          $dataArr['added_date'] = date('Y-m-d H:i:s');
	   }
		
	
		
		 return $dataArr;
		 
       
	}
	private function getbusinesstypeData()
	{
		 $dataArr = array();
	   if(!empty($_GET["id"]))
	   {
		  $dataArr['business_name'] = isset($_POST['business_type_name']) ? $_POST['business_type_name'] : '';
		 $dataArr['status'] = isset($_POST['business_type_status']) ? $_POST['business_type_status'] : '';
		  $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
          $dataArr['updated_date'] = date('Y-m-d H:i:s');
			     
	   }
	   else
	   {
		  $dataArr['business_name'] = isset($_POST['business_type_name']) ? $_POST['business_type_name'] : '';
		 $dataArr['status'] = isset($_POST['business_type_status']) ? $_POST['business_type_status'] : '';
		 	  $dataArr['added_by'] = $_SESSION['user_detail']['user_id'];
          $dataArr['added_date'] = date('Y-m-d H:i:s');
	   }
		
	
		
		 return $dataArr;
		 
       
	}
	
	
     public function updateBusinessArea()
    {
		$dataArr =array();
		$dataArr = $this->getbusinessareaData();
		if(!empty($_GET["id"]))
		{
			$sql="select count(business_area_id) as numcount from ".TAB_PREFIX."business_area WHERE business_area_id=".$_GET["id"]."";
			$dataCurrentArr = $this->get_results($sql);
			
			if($dataCurrentArr[0]->numcount > 0)
			{
				 $dataConditionArray['business_area_id'] = $_GET["id"];
				// $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
                // $dataArr['update_date'] = date('Y-m-d H:i:s');
			     
				if ($this->update(TAB_PREFIX.'business_area', $dataArr, $dataConditionArray)) {
                    
					$this->setSuccess("Business area information updated successfully");
					$this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . " update the Business Area info");
				 
					return true;
				} else {

					$this->setError($this->validationMessage['failed']);
					return false;
				}
				
  			}
			else
			{
				$sql="select count(business_area_id) as numcount from ".TAB_PREFIX."business_area WHERE business_area_name='".$dataArr["business_area_name"]."'";
				$dataCurrentArr = $this->get_results($sql);
				
				if($dataCurrentArr[0]->numcount > 0)

				{
					$this->setError('Business area Name already exists');
					   return false; 
				}	
				else
				{
				
					if ($this->insert(TAB_PREFIX.'business_area', $dataArr)) {
						$this->setSuccess('Business Area Saved Successfully');
						return true;
					}
					else
					{
						$this->setError('Failed to save Business area data');
					   return false;    	   
				   }
				}

			}
		}
		else
		{
				$sql="select count(business_area_id) as numcount from ".TAB_PREFIX."business_area WHERE business_area_name='".$dataArr["business_area_name"]."'";
			$dataCurrentArr = $this->get_results($sql);
			
			if($dataCurrentArr[0]->numcount > 0)

			{
				$this->setError('Business area Name already exists');
				   return false; 
			}	
			else
			{
				if ($this->insert(TAB_PREFIX.'business_area', $dataArr)) {
					$this->setSuccess('Business area Saved Successfully');
				
					return true;
				}
				else
				{
					$this->setError('Failed to save Business area data');
				   return false;    	   
			   }
			}
		}
		
   }
    public function updateBusinessType()
    {
		$dataArr =array();
		$dataArr = $this->getbusinesstypeData();
		if(!empty($_GET["id"]))
		{
			$sql="select count(business_id) as numcount from ".TAB_PREFIX."business_type WHERE business_id=".$_GET["id"]."";
			$dataCurrentArr = $this->get_results($sql);
			
			if($dataCurrentArr[0]->numcount > 0)
			{
				 $dataConditionArray['business_id'] = $_GET["id"];
				// $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
                // $dataArr['update_date'] = date('Y-m-d H:i:s');
			     
				if ($this->update(TAB_PREFIX.'business_type', $dataArr, $dataConditionArray)) {
                    
					$this->setSuccess("Business name information updated successfully");
					$this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . " update the Business type info");
				 
					return true;
				} else {

					$this->setError($this->validationMessage['failed']);
					return false;
				}
				
  			}
			else
			{
				$sql="select count(business_id) as numcount from ".TAB_PREFIX."business_type WHERE business_name='".$dataArr["business_name"]."'";
				$dataCurrentArr = $this->get_results($sql);
				
				if($dataCurrentArr[0]->numcount > 0)

				{
					$this->setError('BusinessType Name already exists');
					   return false; 
				}	
				else
				{
				
					if ($this->insert(TAB_PREFIX.'business_type', $dataArr)) {
						$this->setSuccess('Business type Saved Successfully');
						return true;
					}
					else
					{
						$this->setError('Failed to save Business type data');
					   return false;    	   
				   }
				}

			}
		}
		else
		{
				$sql="select count(business_id) as numcount from ".TAB_PREFIX."business_type WHERE business_name='".$dataArr["business_name"]."'";
			$dataCurrentArr = $this->get_results($sql);
			
			if($dataCurrentArr[0]->numcount > 0)

			{
				$this->setError('BusinessType Name already exists');
				   return false; 
			}	
			else
			{
				if ($this->insert(TAB_PREFIX.'business_type', $dataArr)) {
					$this->setSuccess('BusinessType Saved Successfully');
				
					return true;
				}
				else
				{
					$this->setError('Failed to save Business Type data');
				   return false;    	   
			   }
			}
		}
		
   }
    public function updateVendor()
    {
		$dataArr =array();
		$dataArr = $this->getvendorData();
		if(!empty($_GET["id"]))
		{
			$sql="select count(vendor_id) as numcount from ".TAB_PREFIX."vendor_type WHERE vendor_id=".$_GET["id"]."";
			$dataCurrentArr = $this->get_results($sql);
			
			if($dataCurrentArr[0]->numcount > 0)
			{
				 $dataConditionArray['vendor_id'] = $_GET["id"];
				// $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
                // $dataArr['update_date'] = date('Y-m-d H:i:s');
			     
				if ($this->update(TAB_PREFIX.'vendor_type', $dataArr, $dataConditionArray)) {
                    
					$this->setSuccess("Vendor information updated successfully");
					$this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . " update the vendor info");
				 
					return true;
				} else {

					$this->setError($this->validationMessage['failed']);
					return false;
				}
				
  			}
			else
			{
				$sql="select count(vendor_id) as numcount from ".TAB_PREFIX."vendor_type WHERE name='".$dataArr["vendor_name"]."'";
				$dataCurrentArr = $this->get_results($sql);
				
				if($dataCurrentArr[0]->numcount > 0)

				{
					$this->setError('Vendor Name already exists');
					   return false; 
				}	
				else
				{
				
					if ($this->insert(TAB_PREFIX.'vendor_type', $dataArr)) {
						$this->setSuccess('Vendor Saved Successfully');
						return true;
					}
					else
					{
						$this->setError('Failed to save Vendor data');
					   return false;    	   
				   }
				}

			}
		}
		else
		{
				$sql="select count(vendor_id) as numcount from ".TAB_PREFIX."vendor_type WHERE vendor_name='".$dataArr["vendor_name"]."'";
			$dataCurrentArr = $this->get_results($sql);
			
			if($dataCurrentArr[0]->numcount > 0)

			{
				$this->setError('vendor Name already exists');
				   return false; 
			}	
			else
			{
				if ($this->insert(TAB_PREFIX.'vendor_type', $dataArr)) {
					$this->setSuccess('vendor Saved Successfully');
				
					return true;
				}
				else
				{
					$this->setError('Failed to save vendor data');
				   return false;    	   
			   }
			}
		}   
	 }
    private function getStateData()
    {
        $dataArr = array();
        if(isset($_POST['submit']) && ($_POST['submit']=='submit' || ($_POST['submit']=='update' && isset($_GET['id']))))
        {
            $dataArr['state_name'] = isset($_POST['state_name']) ? $_POST['state_name'] : '';
            $dataArr['state_code'] = isset($_POST['state_code']) ? $_POST['state_code'] : '';
			$dataArr['state_tin'] = isset($_POST['state_tin']) ? $_POST['state_tin'] : '';
            $dataArr['status'] = isset($_POST['status']) ? $_POST['status'] : '';
        }
        return $dataArr;
    }

    private function validateState($dataArr) {

        $rules = array(
            'state_name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:State Name',
            'state_code' => 'required||alphabet||min:2||max:2|#|lable_name:State Code',
			'state_tin' => 'required||numeric||min:2||max:2|#|lable_name:State Tin',
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
		
		if( $this->checkStateCodeExist($dataArr['state_code'], $this->sanitize($_GET['id']))){
            $this->setError($this->validationMessage['statecodeexist']);
            return false;
        }
		
		if( $this->checkStateTinExist($dataArr['state_tin'], $this->sanitize($_GET['id']))){
            $this->setError($this->validationMessage['statetinexist']);
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
	
	public function checkStateCodeExist($state_code, $state_id = '') {
		
		if($state_id && $state_id != '') {
			$checkStateCode = $this->get_row("select * from " . $this->tableNames['state'] . " where 1=1 AND state_id != ".$state_id." AND state_code = '" . $state_code . "'");
		} else {
			$checkStateCode = $this->get_row("select * from " . $this->tableNames['state'] . " where 1=1 AND state_code = '" . $state_code . "'");
		}
		
		if (count($checkStateCode) == 1) {
            return true;
        }
    }
	
	public function checkStateTinExist($state_tin, $state_id = '') {

		if($state_id && $state_id != '') {
			$checkStateTin = $this->get_row("select * from " . $this->tableNames['state'] . " where 1=1 AND state_id != ".$state_id." AND state_tin = '" . $state_tin . "'");
		} else {
			$checkStateTin = $this->get_row("select * from " . $this->tableNames['state'] . " where 1=1 AND state_tin = '" . $state_tin . "'");
		}
		
		if (count($checkStateTin) == 1) {
            return true;
        }
    }
	
    /*
    * End : State Add/Update/Delete Related All function
    */
    
    /*
    * Start : Unit Add/Update/Delete Related All function
    */
    
    final public function addUnit() {

        $dataArr = $this->getUnitData();
        
		if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }
		
        if(!$this->validateUnit($dataArr)) {
            return false;
        }
		
		if( $this->checkUnitCodeExist($dataArr['unit_code'])){
            $this->setError($this->validationMessage['unitcodeexist']);
            return false;
        }
		
        $dataArr['added_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['added_date'] = date('Y-m-d H:i:s');
        
		if (!$this->insert($this->tableNames['unit'], $dataArr)) {
            $this->setError($this->validationMessage['failed']);
            return false;
        }
		
        $this->setSuccess($this->validationMessage['inserted']);
        $insertid = $this->getInsertID();
        $this->logMsg("New Unit Added. ID : " . $insertid . ".");
        return true;
    }
    
    private function getUnitData() {

        $dataArr = array();
        if(isset($_POST['submit']) && ($_POST['submit'] == 'submit' || ($_POST['submit'] == 'update' && isset($_GET['id']))))
        {
            $dataArr['unit_name'] = isset($_POST['unit_name']) ? $_POST['unit_name'] : '';
            $dataArr['unit_code'] = isset($_POST['unit_code']) ? $_POST['unit_code'] : '';
            $dataArr['status'] = isset($_POST['status']) ? $_POST['status'] : '';
        }
        return $dataArr;
    }
    
    private function validateUnit($dataArr) {

        $rules = array(
            'unit_name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Unit Name',
            'unit_code' => 'required||pattern:/^[' . $this->validateType['alphanumeric'] . ']+$/|#|lable_name:Unit Code',
            'status' => 'required||pattern:/^[' . $this->validateType['onlyzeroone'] . ']*$/|#|lable_name:Status'
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
    
    final public function updateUnit() {

        $dataArr = $this->getUnitData();
        
		if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }
        
		if(!$this->validateUnit($dataArr)) {
            return false;
        }
		
		if( $this->checkUnitCodeExist($dataArr['unit_code'], $this->sanitize($_GET['id']))){
            $this->setError($this->validationMessage['unitcodeexist']);
            return false;
        }
		
        $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['updated_date'] = date('Y-m-d H:i:s');
		
		if (!$this->update($this->tableNames['unit'], $dataArr, array('unit_id' => $this->sanitize($_GET['id'])))) {
            $this->setError($this->validationMessage['failed']);
            return false;
        }

		$this->logMsg("Unit ID : " . $_GET['id'] . " in unit Master has been updated");
        $this->setSuccess($this->validationMessage['update']);
        return true;
    }
    
	public function checkUnitCodeExist($unit_code, $unit_id = '') {
		
		if($unit_id && $unit_id != '') {
			$checkUserCode = $this->get_row("select * from " . $this->tableNames['unit'] . " where 1=1 AND unit_id != ".$unit_id." AND unit_code = '" . $unit_code . "'");
		} else {
			$checkUserCode = $this->get_row("select * from " . $this->tableNames['unit'] . " where 1=1 AND unit_code = '" . $unit_code . "'");
		}

        if (count($checkUserCode) == 1) {
            return true;
        }
    }
	
    /*
    * End : Unit Add/Update/Delete Related All function
    */
	
	/*
    * Start : Receiver Add/Update/Delete Related All function
    */

    final public function addReceiver() {

        $dataArr = $this->getReceiverData();
        
		if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }

        if(!$this->validateReceiver($dataArr)) {
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
    
    private function getReceiverData() {
        
        $dataArr = array();
        if(isset($_POST['submit']) && ($_POST['submit']=='submit' || ($_POST['submit']=='update' && isset($_GET['id'])))) {

			$dataArr['name'] = isset($_POST['name']) ? $_POST['name'] : '';
            $dataArr['company_name'] = isset($_POST['company_name']) ? $_POST['company_name'] : '';
			$dataArr['email'] = isset($_POST['email']) ? $_POST['email'] : '';
			$dataArr['address'] = isset($_POST['address']) ? $_POST['address'] : '';
			$dataArr['city'] = isset($_POST['city']) ? $_POST['city'] : '';
			$dataArr['state'] = isset($_POST['state']) ? $_POST['state'] : '';
			$dataArr['country'] = isset($_POST['country']) ? $_POST['country'] : '';
			$dataArr['zipcode'] = isset($_POST['zipcode']) ? $_POST['zipcode'] : '';
            $dataArr['phone'] = isset($_POST['phone']) ? $_POST['phone'] : '';
			$dataArr['fax'] = isset($_POST['fax']) ? $_POST['fax'] : '';
			$dataArr['pannumber'] = isset($_POST['pannumber']) ? $_POST['pannumber'] : '';
			$dataArr['gstid'] = isset($_POST['gstid']) ? $_POST['gstid'] : '';
			$dataArr['website'] = isset($_POST['website']) ? $_POST['website'] : '';
			$dataArr['remarks'] = isset($_POST['remarks']) ? trim($_POST['remarks']) : '';
            $dataArr['status'] = isset($_POST['status']) ? $_POST['status'] : '';
			$dataArr['vendor_type'] = isset($_POST['vendor_type']) ? $_POST['vendor_type'] : '';
        }
        return $dataArr;
    }
    
    private function validateReceiver($dataArr) {

		$rules = array(
            'name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Contact Name',
            'company_name' => 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Business Name',
			'email' => 'required||email|#|lable_name:Email Address',
			'address' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Address',
			'city' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:City',
			'state' => 'required|#|lable_name:State',
			'country' => 'required|#|lable_name:Country'
		);

		if( array_key_exists("zipcode",$dataArr) ) {
            $rules['zipcode'] = 'required||numeric|#|lable_name:Zipcode';
        }

		if( array_key_exists("phone",$dataArr) ) {
            $rules['phone'] = 'pattern:/^[' . $this->validateType['mobilenumber'] . ']+$/|#|lable_name:Phone Number';
        }

		if( array_key_exists("fax",$dataArr) ) {
            $rules['fax'] = 'numeric|#|lable_name:Fax';
        }

		if( array_key_exists("pannumber",$dataArr) ) {
            $rules['pannumber'] = 'pattern:/^' . $this->validateType['pancard'] . '*$/|#|lable_name:PAN Number';
        }

        if( array_key_exists("gstid",$dataArr) ) {
            $rules['gstid'] = 'pattern:/^' . $this->validateType['gstinnumber'] . '+$/||min:15||max:15|#|lable_name:GSTIN';
        }

		if( array_key_exists("website",$dataArr) ) {
            $rules['website'] = 'url|#|lable_name:Website';
        }

		if( array_key_exists("remarks",$dataArr) ) {
            $rules['remarks'] = 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Remarks';
        }

        $valid = $this->vali_obj->validate($dataArr, $rules);
        if ($valid->hasErrors()) {
            $err_arr = $valid->allErrors();
            $this->setError($err_arr);
            $valid->clearMessages();
            return false;
        }
        return true;
    }

    final public function updateReceiver() {

        $dataArr = $this->getReceiverData();

        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }

        if(!$this->validateReceiver($dataArr)) {
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

    final public function addSupplier() {

        $dataArr = $this->getSupplierData();

		if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }

        if(!$this->validateSupplier($dataArr)) {
            return false;
        }

        $dataArr['added_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['added_date'] = date('Y-m-d H:i:s');
        
        if (!$this->insert($this->tableNames['supplier'], $dataArr)) {
            $this->setError($this->validationMessage['failed']);
            return false;
        }
        
        $this->setSuccess($this->validationMessage['inserted']);
        $insertid = $this->getInsertID();
        $this->logMsg("New Supplier Added. ID : " . $insertid . ".");
        return true;
    }

    private function getSupplierData() {

        $dataArr = array();
        if(isset($_POST['submit']) && ($_POST['submit']=='submit' || ($_POST['submit']=='update' && isset($_GET['id'])))) {

            $dataArr['name'] = isset($_POST['name']) ? $_POST['name'] : '';
            $dataArr['company_name'] = isset($_POST['company_name']) ? $_POST['company_name'] : '';
			$dataArr['email'] = isset($_POST['email']) ? $_POST['email'] : '';
			$dataArr['address'] = isset($_POST['address']) ? $_POST['address'] : '';
			$dataArr['city'] = isset($_POST['city']) ? $_POST['city'] : '';
			$dataArr['state'] = isset($_POST['state']) ? $_POST['state'] : '';
			$dataArr['country'] = isset($_POST['country']) ? $_POST['country'] : '';
			$dataArr['zipcode'] = isset($_POST['zipcode']) ? $_POST['zipcode'] : '';
            $dataArr['phone'] = isset($_POST['phone']) ? $_POST['phone'] : '';
			$dataArr['fax'] = isset($_POST['fax']) ? $_POST['fax'] : '';
			$dataArr['pannumber'] = isset($_POST['pannumber']) ? $_POST['pannumber'] : '';
			$dataArr['gstid'] = isset($_POST['gstid']) ? $_POST['gstid'] : '';
			$dataArr['website'] = isset($_POST['website']) ? $_POST['website'] : '';
			$dataArr['remarks'] = isset($_POST['remarks']) ? trim($_POST['remarks']) : '';
            $dataArr['status'] = isset($_POST['status']) ? $_POST['status'] : '';
			$dataArr['vendor_type'] = isset($_POST['vendor_type']) ? $_POST['vendor_type'] : '';
        }
        return $dataArr;
    }

    private function validateSupplier($dataArr) {

        $rules = array(
            'name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Contact Name',
            'company_name' => 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Business Name',
			'email' => 'required||email|#|lable_name:Email Address',
			'address' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Address',
			'city' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:City',
			'state' => 'required|#|lable_name:State',
			'country' => 'required|#|lable_name:Country'
		);

		if( array_key_exists("zipcode",$dataArr) ) {
            $rules['zipcode'] = 'required||numeric|#|lable_name:Zipcode';
        }

		if( array_key_exists("phone",$dataArr) ) {
            $rules['phone'] = 'pattern:/^[' . $this->validateType['mobilenumber'] . ']+$/|#|lable_name:Phone Number';
        }

		if( array_key_exists("fax",$dataArr) ) {
            $rules['fax'] = 'numeric|#|lable_name:Fax';
        }

		if( array_key_exists("pannumber",$dataArr) ) {
            $rules['pannumber'] = 'pattern:/^' . $this->validateType['pancard'] . '*$/|#|lable_name:PAN Number';
        }

        if( array_key_exists("gstid",$dataArr) ) {
            $rules['gstid'] = 'pattern:/^' . $this->validateType['gstinnumber'] . '+$/||min:15||max:15|#|lable_name:GSTIN';
        }

		if( array_key_exists("website",$dataArr) ) {
            $rules['website'] = 'url|#|lable_name:Website';
        }

		if( array_key_exists("remarks",$dataArr) ) {
            $rules['remarks'] = 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Remarks';
        }

        $valid = $this->vali_obj->validate($dataArr, $rules);
        if ($valid->hasErrors()) {
            $err_arr = $valid->allErrors();
            $this->setError($err_arr);
            $valid->clearMessages();
            return false;
        }
        return true;
    }

    final public function updateSupplier() {

        $dataArr = $this->getSupplierData();

        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }

        if(!$this->validateSupplier($dataArr)) {
            return false;
        }

        $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['update_date'] = date('Y-m-d H:i:s');
        
        if (!$this->update($this->tableNames['supplier'], $dataArr, array('supplier_id' => $this->sanitize($_GET['id'])))) {
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
        if(!$this->validateItem($dataArr)) {
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
            $dataArr['item_type'] = isset($_POST['item_type']) ? $_POST['item_type'] : '';
            $dataArr['applicable'] = isset($_POST['applicable']) ? $_POST['applicable'] : '';
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
            'item_type' => 'required||numeric||min:0||max:1|#|lable_name:Item Type',
            'applicable' => 'required||numeric||min:0||max:2|#|lable_name:applicable',
            'igst_tax_rate' => 'required||decimalzero||max:100|#|lable_name:IGST Tax Rate',
            'csgt_tax_rate' => 'required||decimalzero||max:100|#|lable_name:CSGT Tax Rate',
            'sgst_tax_rate' => 'required||decimalzero||max:100|#|lable_nameSGST Tax Rate',
            'cess_tax_rate' => 'required||decimalzero||max:100|#|lable_name:Cess Tax Rate',
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