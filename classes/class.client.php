<?php
/*
 * 
 *  Developed By        :   Rishap Gandhi
 *  Date Created        :   Sep 24, 2016
 *  Last Modified       :   Sep 24, 2016
 *  Last Modified By    :   Rishap Gandhi
 *  Last Modification   :   class for Gallery 
 * 
*/

final class client extends validation {

    public function __construct() {
        parent::__construct();
    }
	
	public function saveClientKYCBySubscriber() {

		include(CLASSES_ROOT . "/digitalsignlib/X509.php");
		$dataCurrentArr = $this->getUserDetailsById($this->sanitize(base64_decode($_POST['clientID'])));

        $dataArr['name'] = isset($_POST['name']) ? $_POST['name'] : '';
        $dataArr['email'] = isset($_POST['email']) ? $_POST['email'] : '';
        $dataArr['phone_number'] = isset($_POST['phone_number']) ? $_POST['phone_number'] : '';
        $dataArr['date_of_birth'] = isset($_POST['date_of_birth']) ? $_POST['date_of_birth'] : '';

		if (empty($dataCurrentArr['data']->kyc) && $dataCurrentArr['data']->kyc == '') {

			$dataArr['gstin_number'] = isset($_POST['gstin_number']) ? $_POST['gstin_number'] : '';
			$dataArr['pan_card_number'] = isset($_POST['pan_card_number']) ? $_POST['pan_card_number'] : '';

			$obj_plan = new plan();
			$dataCurrentSubsArr = $this->getUserDetailsById($this->sanitize($_SESSION['user_detail']['user_id']));

			$clientIds = $this->getClient("GROUP_CONCAT(user_id) as clientIds", "added_by=".$this->sanitize($_SESSION['user_detail']['user_id']));
			$totalPANs = $this->get_results("select count(DISTINCT pan_card_number) as total_pan_card_number from " . $this->getTableName('client_kyc') ." where 1=1 AND added_by IN(".$clientIds[0]->clientIds.")");

			$subscribePlanDetail = $obj_plan->getPlanDetails($dataCurrentSubsArr['data']->plan_id);
			$panPermission = isset($subscribePlanDetail['data']->pan_num) ? $subscribePlanDetail['data']->pan_num : 0;

			if($totalPANs[0]->total_pan_card_number >= $panPermission) {

				$panResult = $this->get_results("select pan_card_number from " . $this->tableNames['client_kyc'] ." where 1=1 AND pan_card_number IN('".$dataArr['pan_card_number']."') AND added_by IN(".$clientIds[0]->clientIds.")");
				if(count($panResult) == 0) {
					$this->setError('You have reach maximum company creation limit.');
					return false;
				}
			}
		} else {

			$state_tin = isset($_POST['state_tin']) ? $_POST['state_tin'] : '';
			$state_gstin_number = isset($dataCurrentArr['data']->kyc->gstin_number) ? $dataCurrentArr['data']->kyc->gstin_number : '';
			$state_gstin_tin = substr($state_gstin_number, 0, 2);
			
			if($state_gstin_tin != $state_tin) {
				$this->setError('State should be valid according to GSTIN.');
				return false;
			}
		}

		$dataArr['identity_proof'] = isset($_POST['identity_proof']) ? $_POST['identity_proof'] : '';
		$dataArr['business_area'] = isset($_POST['business_area']) ? $_POST['business_area'] : '';
		$dataArr['business_type'] = isset($_POST['business_type']) ? $_POST['business_type'] : '';
        $dataArr['vendor_type'] = isset($_POST['vendor_type']) ? $_POST['vendor_type'] : '';
        $dataArr['registered_address'] = isset($_POST['registered_address']) ? $_POST['registered_address'] : '';
        $dataArr['state_id'] = isset($_POST['state']) ? $_POST['state'] : '';
		$dataArr['city'] = isset($_POST['city']) ? $_POST['city'] : '';
		$dataArr['zipcode'] = isset($_POST['zipcode']) ? $_POST['zipcode'] : '';
		$dataArr['gross_turnover'] = isset($_POST['gross_turnover']) ? $_POST['gross_turnover'] : '';
		$dataArr['cur_gross_turnover'] = isset($_POST['cur_gross_turnover']) ? $_POST['cur_gross_turnover'] : '';

        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }

        $dataArr['uid_number'] = isset($_POST['uid_number']) ? $_POST['uid_number'] : '';
		$dataArr['registration_type'] = 'gstin';

        if (!$this->validateClientKYC($dataArr)) {
            return false;
        }
		
        if ($dataArr['date_of_birth'] > date("Y-m-d")) {
            $this->setError("Date should be less than or equals to today's date.");
            return false;
        }

        if ($_FILES['proof_photograph']['name'] != '') {

            $proof_photograph = $this->imageUploads($_FILES['proof_photograph'], 'kyc-docs', 'upload', $this->allowImageExt, 1048576, 'Max file Size 1 MB');
            if ($proof_photograph == FALSE) {
                return false;
            } else {
                $dataArr['proof_photograph'] = $proof_photograph;
            }
        }

        if ($_FILES['address_proof']['name'] != '') {

            $address_proof = $this->imageUploads($_FILES['address_proof'], 'kyc-docs', 'upload', $this->allowImageExt, 1048576, 'Max file Size 1 MB');
            if ($address_proof == FALSE) {
                return false;
            } else {
                $dataArr['address_proof'] = $address_proof;
            }
        }

        if ($_FILES['certificate']['name'] != '' && $_FILES['certificate']['tmp_name'] != '') {

			$extension = pathinfo($_FILES['certificate']['name'], PATHINFO_EXTENSION);
            if($extension == 'cer' || $extension == 'crt' || $extension == 'der' || $extension == 'pem') {

				$path = $_FILES['certificate']['tmp_name'];
				$certfilesize = $_FILES['certificate']['size'];
				if($certfilesize  > 0) {
					
					$cert_content = file_get_contents($path);
					if(!empty($cert_content)){

						$x509 = new File_X509();
						$cert = $x509->loadX509($cert_content);
						if(!empty($cert)) {
							
							if(!empty($x509->validateDate())) {

								if($this->validateDigital($cert)) {
									$dataArr['digital_certificate'] = base64_encode(json_encode($cert));
									$dataArr['digital_certificate_status'] = '1';
								} else {
									$this->setError('Invalid File.');
									return false;
								}
							
							} else {
								$this->setError('File Expired.');
								return false;
							}

						} else {
							$this->setError('Invalid File.');
							return false;
						}

					} else {
						$this->setError('Invalid File.');
						return false;
					}
				} else {
					$this->setError('Empty File.');
                    return false;
				}
			} else {
                $this->setError('Invalid File Extension');
                return false;
            }
        }

        if ($dataCurrentArr['data']->kyc != '') {

            $dataArr['updated_by'] = $this->sanitize(base64_decode($_POST['clientID']));
            $dataArr['updated_date'] = date('Y-m-d H:i:s');
            $dataConditionArray['added_by'] = $this->sanitize(base64_decode($_POST['clientID']));
            
			if ($this->update($this->tableNames['client_kyc'], $dataArr, $dataConditionArray)) {
                $this->setSuccess($this->validationMessage['kycupdated']);
                $this->logMsg("User KYC ID : " . $this->sanitize(base64_decode($_POST['clientID'])) . " has been updated.");
                return true;
            } else {
                $this->setError($this->validationMessage['failed']);
                return false;
            }
		} else {

			$dataArr['added_by'] = $this->sanitize(base64_decode($_POST['clientID']));
            $dataArr['added_date'] = date('Y-m-d H:i:s');

			if ($this->insert($this->tableNames['client_kyc'], $dataArr)) {
                $this->setSuccess($this->validationMessage['kycupdated']);
                $insertid = $this->getInsertID();
                $this->logMsg("User KYC Added. ID : " . $insertid . ".");
                return true;
            } else {
                $this->setError($this->validationMessage['failed']);
                return false;
            }
        }
    }

    public function saveClientKYC() {

		include(CLASSES_ROOT . "/digitalsignlib/X509.php");
		$dataCurrentArr = $this->getUserDetailsById($this->sanitize($_SESSION['user_detail']['user_id']));

        $dataArr['name'] = isset($_POST['name']) ? $_POST['name'] : '';
        $dataArr['email'] = isset($_POST['email']) ? $_POST['email'] : '';
        $dataArr['phone_number'] = isset($_POST['phone_number']) ? $_POST['phone_number'] : '';
        $dataArr['date_of_birth'] = isset($_POST['date_of_birth']) ? $_POST['date_of_birth'] : '';

		if ($dataCurrentArr['data']->kyc == '') {

			$dataArr['gstin_number'] = isset($_POST['gstin_number']) ? $_POST['gstin_number'] : '';
			$dataArr['pan_card_number'] = isset($_POST['pan_card_number']) ? $_POST['pan_card_number'] : '';

			$obj_plan = new plan();
			$dataCurrentSubsArr = $this->getUserDetailsById($this->sanitize($dataCurrentArr['data']->added_by));

			$clientIds = $this->getClient("GROUP_CONCAT(user_id) as clientIds", "added_by=".$this->sanitize($dataCurrentArr['data']->added_by));
			$totalPANs = $this->get_results("select count(DISTINCT pan_card_number) as total_pan_card_number from " . $this->getTableName('client_kyc') ." where 1=1 AND added_by IN(".$clientIds[0]->clientIds.")");

			$subscribePlanDetail = $obj_plan->getPlanDetails($dataCurrentSubsArr['data']->plan_id);
			$panPermission = isset($subscribePlanDetail['data']->pan_num) ? $subscribePlanDetail['data']->pan_num : 0;

			if($totalPANs[0]->total_pan_card_number >= $panPermission) {

				$panResult = $this->get_results("select pan_card_number from " . $this->tableNames['client_kyc'] ." where 1=1 AND pan_card_number IN('".$dataArr['pan_card_number']."') AND added_by IN(".$clientIds[0]->clientIds.")");
				if(count($panResult) == 0) {
					$this->setError('You have reach maximum company creation limit.');
					return false;
				}
			}

		} else {

			$state_tin = isset($_POST['state_tin']) ? $_POST['state_tin'] : '';
			$state_gstin_number = isset($dataCurrentArr['data']->kyc->gstin_number) ? $dataCurrentArr['data']->kyc->gstin_number : '';
			$state_gstin_tin = substr($state_gstin_number, 0, 2);
			
			if($state_gstin_tin != $state_tin) {
				$this->setError('State should be valid according to GSTIN.');
				return false;
			}
		}

		$dataArr['identity_proof'] = isset($_POST['identity_proof']) ? $_POST['identity_proof'] : '';
		$dataArr['business_area'] = isset($_POST['business_area']) ? $_POST['business_area'] : '';
		$dataArr['business_type'] = isset($_POST['business_type']) ? $_POST['business_type'] : '';
        $dataArr['vendor_type'] = isset($_POST['vendor_type']) ? $_POST['vendor_type'] : '';
        $dataArr['registered_address'] = isset($_POST['registered_address']) ? $_POST['registered_address'] : '';
        $dataArr['state_id'] = isset($_POST['state']) ? $_POST['state'] : '';
		$dataArr['city'] = isset($_POST['city']) ? $_POST['city'] : '';
		$dataArr['zipcode'] = isset($_POST['zipcode']) ? $_POST['zipcode'] : '';
		$dataArr['gross_turnover'] = isset($_POST['gross_turnover']) ? $_POST['gross_turnover'] : '';
		$dataArr['cur_gross_turnover'] = isset($_POST['cur_gross_turnover']) ? $_POST['cur_gross_turnover'] : '';

        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }

        $dataArr['uid_number'] = isset($_POST['uid_number']) ? $_POST['uid_number'] : '';
		$dataArr['registration_type'] = 'gstin';

        if (!$this->validateClientKYC($dataArr)) {
            return false;
        }

        if ($dataArr['date_of_birth'] > date("Y-m-d")) {
            $this->setError("Date should be less than or equals to today's date.");
            return false;
        }

        if ($_FILES['proof_photograph']['name'] != '') {

            $proof_photograph = $this->imageUploads($_FILES['proof_photograph'], 'kyc-docs', 'upload', $this->allowImageExt, 1048576, 'Max file Size 1 MB');
            if ($proof_photograph == FALSE) {
                return false;
            } else {
                $dataArr['proof_photograph'] = $proof_photograph;
            }
        }

        if ($_FILES['address_proof']['name'] != '') {

            $address_proof = $this->imageUploads($_FILES['address_proof'], 'kyc-docs', 'upload', $this->allowImageExt, 1048576, 'Max file Size 1 MB');
            if ($address_proof == FALSE) {
                return false;
            } else {
                $dataArr['address_proof'] = $address_proof;
            }
        }

        if ($_FILES['certificate']['name'] != '' && $_FILES['certificate']['tmp_name'] != '') {

			$extension = pathinfo($_FILES['certificate']['name'], PATHINFO_EXTENSION);
            if($extension == 'cer' || $extension == 'crt' || $extension == 'der' || $extension == 'pem') {

				$path = $_FILES['certificate']['tmp_name'];
				$certfilesize = $_FILES['certificate']['size'];
				if($certfilesize  > 0) {
					
					$cert_content = file_get_contents($path);
					if(!empty($cert_content)){

						$x509 = new File_X509();
						$cert = $x509->loadX509($cert_content);
						if(!empty($cert)) {
							
							if(!empty($x509->validateDate())) {

								if($this->validateDigital($cert)) {
									$dataArr['digital_certificate'] = base64_encode(json_encode($cert));
									$dataArr['digital_certificate_status'] = '1';
								} else {
									$this->setError('Invalid File.');
									return false;
								}
							
							} else {
								$this->setError('File Expired.');
								return false;
							}

						} else {
							$this->setError('Invalid File.');
							return false;
						}

					} else {
						$this->setError('Invalid File.');
						return false;
					}
				} else {
					$this->setError('Empty File.');
                    return false;
				}
			} else {
                $this->setError('Invalid File Extension');
                return false;
            }
        }

        if ($dataCurrentArr['data']->kyc != '') {

            $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
            $dataArr['updated_date'] = date('Y-m-d H:i:s');
            $dataConditionArray['added_by'] = $this->sanitize($_SESSION['user_detail']['user_id']);
            //$this->pr($dataArr);die;
			if ($this->update($this->tableNames['client_kyc'], $dataArr, $dataConditionArray)) {
                $this->setSuccess($this->validationMessage['kycupdated']);
                $this->logMsg("User KYC ID : " . $_SESSION['user_detail']['user_id'] . " has been updated.");
                return true;
            } else {
                $this->setError($this->validationMessage['failed']);
                return false;
            }
		} else {

			$dataArr['added_by'] = $this->sanitize($_SESSION['user_detail']['user_id']);
            $dataArr['added_date'] = date('Y-m-d H:i:s');

			if ($this->insert($this->tableNames['client_kyc'], $dataArr)) {
                $this->setSuccess($this->validationMessage['kycupdated']);
                $insertid = $this->getInsertID();
                $this->logMsg("User KYC Added. ID : " . $insertid . ".");
                return true;
            } else {
                $this->setError($this->validationMessage['failed']);
                return false;
            }
        }
    }
	
	/**** Start Code to check digital signature valid or not ***/
	final private function validateDigital($data) {

		$return = false;
		if(!empty($data)) {

			if(isset($data['tbsCertificate']) && !empty($data['tbsCertificate']) && isset($data['signature']) && !empty($data['signature'])) {
				/**** index key check ***/
				if(isset($data['tbsCertificate']['signature']['algorithm']) && !empty($data['tbsCertificate']['signature']['algorithm']) && isset($data['tbsCertificate']['issuer']) && !empty($data['tbsCertificate']['issuer']) && isset($data['tbsCertificate']['subjectPublicKeyInfo']) && !empty($data['tbsCertificate']['subjectPublicKeyInfo']) && isset($data['tbsCertificate']['validity']) && !empty($data['tbsCertificate']['validity'])) {

					/**** Public key check ***/
					if(isset($data['tbsCertificate']['subjectPublicKeyInfo']['subjectPublicKey']) && !empty($data['tbsCertificate']['subjectPublicKeyInfo']['subjectPublicKey'])){
						$return = true;
					}
				}
			}
		}
		return $return;     
	}
	/**** End Code to check digital signature valid or not ***/

    public function validateClientKYC($dataArr) {

        $rules = array(
            'name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Name',
            'email' => 'required||email|#|lable_name:Email',
            'phone_number' => 'required||pattern:/^[' . $this->validateType['mobilenumber'] . ']+$/|#|lable_name:Phone Number',
            'date_of_birth' => 'required||date|#|lable_name:Date of birth',
			'uid_number' => 'pattern:/^[' . $this->validateType['alphanumeric'] . ']+$/|#|lable_name:UID',
            'identity_proof' => 'required||identityproof|#|lable_name:Identity Proof',
            'business_area' => 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '*$/|#|lable_name:Business Area',
            'business_type' => 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '*$/|#|lable_name:Business Type',
			'vendor_type' => 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '*$/|#|lable_name:Vendor Type',
			'registered_address' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Permanent Address',
            'state_id' => 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '*$/|#|lable_name:State'
        );

		if (array_key_exists("gstin_number", $dataArr)) {
            $rules['gstin_number'] = 'required||pattern:/^' . $this->validateType['gstinnumber'] . '+$/||min:15||max:15|#|lable_name:GSTIN';
        }

		if (array_key_exists("pan_card_number", $dataArr)) {
            $rules['pan_card_number'] = 'required||pattern:/^' . $this->validateType['pancard'] . '*$/|#|lable_name:PAN Card';
        }

		if (array_key_exists("city", $dataArr)) {
            $rules['city'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:City';
        }

		if( array_key_exists("zipcode",$dataArr) ) {
            $rules['zipcode'] = 'required||numeric|#|lable_name:Zipcode';
        }

        if (array_key_exists("proof_photograph", $dataArr)) {
            $rules['proof_photograph'] = 'image|#|lable_name:Proof Photograph';
        }

        if (array_key_exists("address_proof", $dataArr)) {
            $rules['address_proof'] = 'image|#|lable_name:Address Proof';
        }
        if( array_key_exists("gross_turnover",$dataArr) ) {
            $rules['gross_turnover'] = 'required||numeric||min:1|#|lable_name:Gross Turnover';
        }
        if( array_key_exists("cur_gross_turnover",$dataArr) ) {
            $rules['cur_gross_turnover'] = 'required||numeric||min:1|#|lable_name:Cur Gross Turnover';
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

    public function addClientItem() {

        $dataArr['item_name'] = isset($_POST['item_name']) ? $_POST['item_name'] : '';
        $dataArr['item_category'] = isset($_POST['item_category']) ? $_POST['item_category'] : '';
        $dataArr['unit_price'] = isset($_POST['unit_price']) ? $_POST['unit_price'] : '';
        $dataArr['item_unit'] = isset($_POST['item_unit']) ? $_POST['item_unit'] : '';
        $dataArr['status'] = isset($_POST['status']) ? $_POST['status'] : '';

        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }

        if (!$this->validateClientItem($dataArr)) {
            return false;
        }

        $dataArr['added_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['added_date'] = date('Y-m-d H:i:s');

        if ($this->insert($this->tableNames['client_master_item'], $dataArr)) {

            $this->setSuccess($this->validationMessage['iteminserted']);
            $insertid = $this->getInsertID();
            $this->logMsg("New Item Added. ID : " . $insertid . ".");
            return true;
        } else {

            $this->setError($this->validationMessage['failed']);
            return false;
        }

        return true;
    }

    public function updateClientItem() {

        $dataArr['item_name'] = isset($_POST['item_name']) ? $_POST['item_name'] : '';
        $dataArr['item_category'] = isset($_POST['item_category']) ? $_POST['item_category'] : '';
        $dataArr['unit_price'] = isset($_POST['unit_price']) ? $_POST['unit_price'] : '';
        $dataArr['item_unit'] = isset($_POST['item_unit']) ? $_POST['item_unit'] : '';
        $dataArr['status'] = isset($_POST['status']) ? $_POST['status'] : '';

        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }

        if (!$this->validateClientItem($dataArr)) {
            return false;
        }

        $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['updated_date'] = date('Y-m-d H:i:s');

        $dataConditionArray['item_id'] = $this->sanitize($_GET['id']);
        if ($this->update($this->tableNames['client_master_item'], $dataArr, $dataConditionArray)) {

            $this->setSuccess($this->validationMessage['itemupdated']);
            $this->logMsg("Item ID : " . $_GET['id'] . " has been updated");
            return true;
        } else {

            $this->setError($this->validationMessage['failed']);
            return false;
        }

        return true;
    }

    public function validateClientItem($dataArr) {

        $rules = array(
            'item_name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Item Name',
            'item_category' => 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '*$/|#|lable_name:Item Category',
            'unit_price' => 'decimal|#|lable_name:Price',
            'item_unit' => 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '*$/|#|lable_name:Item Unit',
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

    public function deleteClientItem($itemid = '') {

        $dataConditionArray['item_id'] = $itemid;
        $dataUpdateArray['is_deleted'] = "1";
        $dataUpdateArray['deleted_by'] = $_SESSION['user_detail']['user_id'];
        $dataUpdateArray['deleted_date'] = date('Y-m-d H:i:s');

        if ($this->update($this->tableNames['client_master_item'], $dataUpdateArray, $dataConditionArray)) {

            $this->setSuccess($this->validationMessage['itemdeleted']);
            $this->logMsg("Item ID : " . $itemid . " in Client Master Item has been deleted");
            return true;
        } else {

            $this->setError($this->validationMessage['failed']);
            return false;
        }

        return true;
    }

    public function addClientUser() {

        $dataArr['first_name'] = isset($_POST['first_name']) ? $_POST['first_name'] : '';
        $dataArr['last_name'] = isset($_POST['last_name']) ? $_POST['last_name'] : '';
        $dataArr['company_name'] = isset($_POST['company_name']) ? $_POST['company_name'] : '';
        $dataArr['username'] = isset($_POST['username']) ? $_POST['username'] : '';
        $dataArr['password'] = isset($_POST['password']) ? $_POST['password'] : '';
        $dataArr['email'] = isset($_POST['emailaddress']) ? $_POST['emailaddress'] : '';
        $dataArr['phone_number'] = isset($_POST['phonenumber']) ? $_POST['phonenumber'] : '';
        $dataArr['status'] = isset($_POST['user_status']) ? $_POST['user_status'] : '';

        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }

        if (!$this->validateClientUser($dataArr)) {
            return false;
        }

        $dataCurrentArr = $this->getUserDetailsById($this->sanitize($_SESSION['user_detail']['user_id']));

        $dataArr['username'] = $dataCurrentArr['data']->subscriber_code . "_" . $dataArr['username'];
        if ($this->checkUsernameExist($dataArr['username'])) {
            $this->setError($this->validationMessage['usernameexist']);
            return false;
        }

        /*
		if($this->checkEmailAddressExist($dataArr['email'])){
			$this->setError($this->validationMessage['emailexist']);
			return false;
		}
		*/

        $dataArr['password'] = $this->password_encrypt($dataArr['password']); /* encrypt password */
        $dataArr['user_group'] = 4;
        $dataArr['added_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['added_date'] = date('Y-m-d H:i:s');

        if ($this->insert($this->tableNames['user'], $dataArr)) {

            $this->setSuccess($this->validationMessage['useradded']);
            $insertid = $this->getInsertID();
            $this->logMsg("New User Added. ID : " . $insertid . ".");
            return true;
        } else {

            $this->setError($this->validationMessage['failed']);
            return false;
        }

        return true;
    }

    public function updateClientUser() {

        $dataArr['first_name'] = isset($_POST['first_name']) ? $_POST['first_name'] : '';
        $dataArr['last_name'] = isset($_POST['last_name']) ? $_POST['last_name'] : '';
        $dataArr['company_name'] = isset($_POST['company_name']) ? $_POST['company_name'] : '';

        if (isset($_POST['password']) && $_POST['password'] != '') {
            $dataArr['password'] = isset($_POST['password']) ? $_POST['password'] : '';
        }

        $dataArr['email'] = isset($_POST['emailaddress']) ? $_POST['emailaddress'] : '';
        $dataArr['phone_number'] = isset($_POST['phonenumber']) ? $_POST['phonenumber'] : '';
        $dataArr['status'] = isset($_POST['user_status']) ? $_POST['user_status'] : '';

        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }

        if (!$this->validateClientUser($dataArr)) {
            return false;
        }

        if (isset($dataArr['password']) && $dataArr['password'] != '') {
            $dataArr['password'] = $this->password_encrypt($dataArr['password']);
        } /* encrypt password */
        $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['updated_date'] = date('Y-m-d H:i:s');

        $dataConditionArray['user_id'] = $this->sanitize($_GET['id']);
        if ($this->update($this->tableNames['user'], $dataArr, $dataConditionArray)) {

            $this->setSuccess($this->validationMessage['useredited']);
            $this->logMsg("User ID : " . $_GET['id'] . " has been updated");
            return true;
        } else {

            $this->setError($this->validationMessage['failed']);
            return false;
        }

        return true;
    }

    public function validateClientUser($dataArr) {

        $rules = array(
            'first_name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:First Name',
            'last_name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Last Name',
            'company_name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Company Name',
            'phone_number' => 'required||pattern:/^[' . $this->validateType['mobilenumber'] . ']+$/|#|lable_name:Phone Number',
            'email' => 'required||email|#|lable_name:Email',
            'status' => 'required||pattern:/^[' . $this->validateType['onlyzeroone'] . ']*$/|#|lable_name:Status'
        );

        if (array_key_exists("username", $dataArr)) {
            $rules['username'] = 'required||pattern:/^' . $this->validateType['username'] . '+$/|#|lable_name:Username';
        }

        if (array_key_exists("password", $dataArr)) {
            $rules['password'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/||min:8||max:20|#|lable_name:Password';
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

    public function deleteClientUser($userid = '') {

        $dataConditionArray['user_id'] = $userid;
        $dataUpdateArray['is_deleted'] = "1";
        $dataUpdateArray['deleted_by'] = $_SESSION['user_detail']['user_id'];
        $dataUpdateArray['deleted_date'] = date('Y-m-d H:i:s');

        if ($this->update($this->tableNames['user'], $dataUpdateArray, $dataConditionArray)) {

            $this->setSuccess($this->validationMessage['userdeleted']);
            $this->logMsg("User ID : " . $userid . " in User has been deleted");
            return true;
        } else {

            $this->setError($this->validationMessage['failed']);
            return false;
        }

        return true;
    }

    /* validate client invoice */

    public function validateClientInvoice($dataArr) {

        if (array_key_exists("is_canceled", $dataArr)) {
            $rules['is_canceled'] = 'required||pattern:/^[' . $this->validateType['onlyzeroone'] . ']*$/|#|lable_name:Canceled Value';
        }

        if (array_key_exists("invoice_type", $dataArr)) {
            $rules['invoice_type'] = 'required||invoicetype|#|lable_name:Invoice Type';
        }

        if (array_key_exists("invoice_nature", $dataArr)) {
            $rules['invoice_nature'] = 'required||invoicenature|#|lable_name:Invoice Nature';
        }

        if (array_key_exists("supply_type", $dataArr)) {
            $rules['supply_type'] = 'required||supplytype|#|lable_name:Supply Type';
        }

        if (array_key_exists("ecommerce_gstin_number", $dataArr)) {
            $rules['ecommerce_gstin_number'] = 'pattern:/^' . $this->validateType['gstinnumber'] . '+$/||min:15||max:15|#|lable_name:Ecommerce GSTIN Number';
        }

        if (array_key_exists("invoice_corresponding_type", $dataArr)) {
            $rules['invoice_corresponding_type'] = 'required||invoiecorresponding|#|lable_name:Invoice Corresponding Type';
        }
		
		if (array_key_exists("delivery_challan_type", $dataArr)) {
            $rules['delivery_challan_type'] = 'required||deliverychallantype|#|lable_name:Delivery Challan Type';
        }

        if (array_key_exists("corresponding_invoice_number", $dataArr)) {
            $rules['corresponding_invoice_number'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Corresponding Invoice Number';
        }

        if (array_key_exists("corresponding_invoice_date", $dataArr)) {
            $rules['corresponding_invoice_date'] = 'required||date|#|lable_name:Corresponding Invoice Date';
        }

        if (array_key_exists("is_tax_payable", $dataArr)) {
            $rules['is_tax_payable'] = 'required||pattern:/^[' . $this->validateType['onlyzeroone'] . ']*$/|#|lable_name:Tax Reverse Charge';
        }

        if (array_key_exists("advance_adjustment", $dataArr)) {
            $rules['advance_adjustment'] = 'required||pattern:/^[' . $this->validateType['onlyzeroone'] . ']*$/|#|lable_name:Advance Adjustment';
        }

        if (array_key_exists("company_name", $dataArr)) {
            $rules['company_name'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Company Name';
        }

        if (array_key_exists("reference_number", $dataArr)) {
            $rules['reference_number'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Reference Number';
        }

        if (array_key_exists("invoice_date", $dataArr)) {
            $rules['invoice_date'] = 'required||date|#|lable_name:Invoice Date';
        }

        if (array_key_exists("company_address", $dataArr)) {
            $rules['company_address'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Company Address';
        }

        if (array_key_exists("company_state", $dataArr)) {
            $rules['company_state'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '*$/|#|lable_name:Company State';
        }

        if (array_key_exists("gstin_number", $dataArr)) {
            $rules['gstin_number'] = 'pattern:/^' . $this->validateType['gstinnumber'] . '+$/||min:15||max:15|#|lable_name:Company GSTIN Number';
        }

        if (array_key_exists("supply_place", $dataArr)) {
            $rules['supply_place'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '*$/|#|lable_name:Place Of Supply';
        }
		
		if (array_key_exists("refund_voucher_receipt", $dataArr)) {
            $rules['refund_voucher_receipt'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '*$/|#|lable_name:Receipt Voucher';
        }

        if (array_key_exists("billing_name", $dataArr)) {
            $rules['billing_name'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Billing Name';
        }

        if (array_key_exists("billing_address", $dataArr)) {
            $rules['billing_address'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Billing Address';
        }

        if (array_key_exists("billing_state", $dataArr)) {
            $rules['billing_state'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '*$/|#|lable_name:Billing State';
        }

        if (array_key_exists("billing_gstin_number", $dataArr)) {
            $rules['billing_gstin_number'] = 'pattern:/^' . $this->validateType['gstinnumber'] . '+$/||min:15||max:15|#|lable_name:Billing GSTIN Number';
        }

        if (array_key_exists("shipping_name", $dataArr)) {
            $rules['shipping_name'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Shipping Name';
        }

        if (array_key_exists("shipping_address", $dataArr)) {
            $rules['shipping_address'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Shipping Address';
        }

        if (array_key_exists("shipping_state", $dataArr)) {
            $rules['shipping_state'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '*$/|#|lable_name:Shipping State';
        }

        if (array_key_exists("shipping_gstin_number", $dataArr)) {
            $rules['shipping_gstin_number'] = 'pattern:/^' . $this->validateType['gstinnumber'] . '+$/||min:15||max:15|#|lable_name:Shipping GSTIN Number';
        }

		$valid = $this->vali_obj->validate($dataArr, $rules);
		if ($valid->hasErrors()) {
			$err_arr = $valid->allErrors();
			$this->setError($err_arr);
			$valid->clearMessages();
			return false;
		}
		return true;

		/*
		$valid = $this->vali_obj->validate($dataArr, $rules);
        if ($valid->hasErrors()) {
            cms_validate::$errors = array();
            $err_arr = $valid->allErrors();
            $valid->clearMessages();
            return $err_arr;
        }
        return true;
		*/
    }

    /* end of validate client invoice */

    /* validate client invoice items */

    public function validateClientInvoiceItem($dataArr, $serialno) {

        $rules = array('invoice_itemid' => 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '*$/|#|lable_name:Invoice Item no. ' . $serialno);

		if (array_key_exists("invoice_quantity", $dataArr)) {
            $rules['invoice_quantity'] = 'required||numeric|#|lable_name:Quantity of Item no. ' . $serialno;
        }

        if (array_key_exists("invoice_discount", $dataArr)) {
            $rules['invoice_discount'] = 'numeric|#|lable_name:Discount of Item no. ' . $serialno;
        }

        if (array_key_exists("invoice_taxablevalue", $dataArr)) {
            $rules['invoice_taxablevalue'] = 'required||numeric|#|lable_name:Advance Amount of Item no. ' . $serialno;
        }

		$valid = $this->vali_obj->validate($dataArr, $rules);
		if ($valid->hasErrors()) {
			$err_arr = $valid->allErrors();
			$this->setError($err_arr);
			$valid->clearMessages();
			return false;
		}
		return true;

		/*
		$valid = $this->vali_obj->validate($dataArr, $rules);
        if ($valid->hasErrors()) {
            cms_validate::$errors = array();
            $err_arr = $valid->allErrors();
            $valid->clearMessages();
            return $err_arr;
        }
        return true;
		*/
    }

    /* end of validate client invoice items */

    /* validate client invoice excel file */

    public function validateClientInvoiceExcel($dataArr) {

        if (array_key_exists("is_canceled", $dataArr)) {
            $rules['is_canceled'] = 'required||pattern:/^[' . $this->validateType['onlyzeroone'] . ']*$/|#|lable_name:Canceled Value';
        }

        if (array_key_exists("invoice_number", $dataArr)) {
            $rules['invoice_number'] = 'required||pattern:/^' . $this->validateType['invoicenumber'] . '+$/|#|lable_name:Invoice Number';
        }

        if (array_key_exists("invoice_type", $dataArr)) {
            $rules['invoice_type'] = 'required||invoicetype|#|lable_name:Invoice Type';
        }

        if (array_key_exists("invoice_nature", $dataArr)) {
            $rules['invoice_nature'] = 'required||invoicenature|#|lable_name:Invoice Nature';
        }

        if (array_key_exists("supply_type", $dataArr)) {
            $rules['supply_type'] = 'required||supplytype|#|lable_name:Supply Type';
        }

        if (array_key_exists("ecommerce_gstin_number", $dataArr)) {
            $rules['ecommerce_gstin_number'] = 'pattern:/^' . $this->validateType['gstinnumber'] . '+$/||min:15||max:15|#|lable_name:Ecommerce GSTIN Number';
        }

        if (array_key_exists("invoice_corresponding_type", $dataArr)) {
            $rules['invoice_corresponding_type'] = 'required||invoiecorresponding|#|lable_name:Invoice Corresponding Type';
        }
		
		if (array_key_exists("delivery_challan_type", $dataArr)) {
            $rules['delivery_challan_type'] = 'required||deliverychallantype|#|lable_name:Delivery Challan Type';
        }

        if (array_key_exists("corresponding_invoice_number", $dataArr)) {
            $rules['corresponding_invoice_number'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Corresponding Invoice Number';
        }

        if (array_key_exists("corresponding_invoice_date", $dataArr)) {
            $rules['corresponding_invoice_date'] = 'required||date|#|lable_name:Corresponding Invoice Date';
        }

        if (array_key_exists("is_tax_payable", $dataArr)) {
            $rules['is_tax_payable'] = 'required||pattern:/^[' . $this->validateType['onlyzeroone'] . ']*$/|#|lable_name:Tax Reverse Charge';
        }

        if (array_key_exists("advance_adjustment", $dataArr)) {
            $rules['advance_adjustment'] = 'required||pattern:/^[' . $this->validateType['onlyzeroone'] . ']*$/|#|lable_name:Advance Adjustment';
        }

        if (array_key_exists("reference_number", $dataArr)) {
            $rules['reference_number'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Reference Number';
        }

        if (array_key_exists("invoice_date", $dataArr)) {
            $rules['invoice_date'] = 'required||date|#|lable_name:Invoice Date';
        }

        if (array_key_exists("supply_place", $dataArr)) {
            $rules['supply_place'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '*$/|#|lable_name:Place Of Supply';
        }

        if (array_key_exists("billing_name", $dataArr)) {
            $rules['billing_name'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Billing Name';
        }

        if (array_key_exists("billing_address", $dataArr)) {
            $rules['billing_address'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Billing Address';
        }

        if (array_key_exists("billing_state", $dataArr)) {
            $rules['billing_state'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '*$/|#|lable_name:Billing State';
        }

        if (array_key_exists("billing_gstin_number", $dataArr)) {
            $rules['billing_gstin_number'] = 'pattern:/^' . $this->validateType['gstinnumber'] . '+$/||min:15||max:15|#|lable_name:Billing GSTIN Number';
        }

        if (array_key_exists("shipping_name", $dataArr)) {
            $rules['shipping_name'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Shipping Name';
        }

        if (array_key_exists("shipping_address", $dataArr)) {
            $rules['shipping_address'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Shipping Address';
        }

        if (array_key_exists("shipping_state", $dataArr)) {
            $rules['shipping_state'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '*$/|#|lable_name:Shipping State';
        }

        if (array_key_exists("shipping_gstin_number", $dataArr)) {
            $rules['shipping_gstin_number'] = 'pattern:/^' . $this->validateType['gstinnumber'] . '+$/||min:15||max:15|#|lable_name:Shipping GSTIN Number';
        }

        if (array_key_exists("item_name", $dataArr)) {
            $rules['item_name'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Item Name';
        }

        if (array_key_exists("item_hsn_code", $dataArr)) {
            $rules['item_hsn_code'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Item HSN Code';
        }

        if (array_key_exists("item_quantity", $dataArr)) {
            $rules['item_quantity'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '*$/|#|lable_name:Item Quantity';
        }

        if (array_key_exists("item_unit", $dataArr)) {
            $rules['item_unit'] = 'required||pattern:/^[' . $this->validateType['alphanumeric'] . ']+$/|#|lable_name:Item Unit Code';
        }

        if (array_key_exists("item_rate", $dataArr)) {
            $rules['item_rate'] = 'required||decimal|#|lable_name:Item Price';
        }

        if (array_key_exists("item_discount", $dataArr)) {
            $rules['item_discount'] = 'numeric|#|lable_name:Item Discount';
        }

        if (array_key_exists("advance_amount", $dataArr)) {
            $rules['advance_amount'] = 'numeric|#|lable_name:Advance Amount';
        }

        if (array_key_exists("advance_value", $dataArr)) {
            $rules['advance_value'] = 'numeric|#|lable_name:Advance Amount';
        }

        if (array_key_exists("refund_value", $dataArr)) {
            $rules['refund_value'] = 'numeric|#|lable_name:Refund Amount';
        }

        if (array_key_exists("item_taxablevalue", $dataArr)) {
            $rules['item_taxablevalue'] = 'required||numeric|#|lable_name:Amount of Item';
        }

        if (array_key_exists("receipt_voucher_number", $dataArr)) {
            $rules['receipt_voucher_number'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Receipt Voucher Number';
        }

        if (array_key_exists("receipt_voucher_date", $dataArr)) {
            $rules['receipt_voucher_date'] = 'required||date|#|lable_name:Receipt Voucher Date';
        }

        $valid = $this->vali_obj->validate($dataArr, $rules);
        if ($valid->hasErrors()) {
            cms_validate::$errors = array();
            $err_arr = $valid->allErrors();
            $valid->clearMessages();
            return $err_arr;
        }
        return true;
    }

    /* upload client invoice */

    public function uploadClientInvoice() {

        $flag = true;
        $errorflag = false;
        $dataArray = array();
        $indexArray = array();
        $invoiceArray = array();
        $invoiceItemArray = array();

        if ($_FILES['invoice_xlsx']['name'] != '' && $_FILES['invoice_xlsx']['error'] == 0) {

            $invoice_excel = $this->imageUploads($_FILES['invoice_xlsx'], 'invoice-docs', 'upload', $this->allowExcelExt);
            if ($invoice_excel == FALSE) {
                return false;
            }

            $invoice_excel_dir_path = PROJECT_ROOT . UPLOAD_DIR . "/invoice-docs/" . $invoice_excel;
            $invoice_excel_url_path = PROJECT_URL . UPLOAD_DIR . "/invoice-docs/" . $invoice_excel;

            $objPHPExcel = PHPExcel_IOFactory::load($invoice_excel_dir_path);
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
			$sheetData = array_map('array_filter', $sheetData);
			$sheetData = array_filter($sheetData);

			foreach ($sheetData as $rowKey => $data) {

                if ($flag) {
                    $indexArray = $data;
                    $flag = false;
                    continue;
                }

                $currentItemError = array();
                $dataArray['reference_number'] = isset($data['A']) ? $data['A'] : '';
                $invoice_type = isset($data['B']) ? $data['B'] : '';

                if ($invoice_type != '' && strtoupper($invoice_type) === 'TAX') {
                    $dataArray['invoice_type'] = "taxinvoice";
                } else if ($invoice_type != '' && strtoupper($invoice_type) === 'DEEMED EXPORT') {
                    $dataArray['invoice_type'] = "deemedexportinvoice";
                } else if ($invoice_type != '' && strtoupper($invoice_type) === 'SEZ UNIT') {
                    $dataArray['invoice_type'] = "sezunitinvoice";
                } else {
                    $dataArray['invoice_type'] = $invoice_type;
                }

                $dataArray['invoice_date'] = isset($data['C']) ? $data['C'] : '';

                $supply_type = isset($data['D']) ? $data['D'] : '';

                if ($supply_type != '' && strtoupper($supply_type) === 'NORMAL') {
                    $dataArray['supply_type'] = "normal";
                } else if ($supply_type != '' && strtoupper($supply_type) === 'REVERSE CHARGE') {
                    $dataArray['supply_type'] = "reversecharge";
                } else if ($supply_type != '' && strtoupper($supply_type) === 'TDS') {
                    $dataArray['supply_type'] = "tds";
                } else if ($supply_type != '' && strtoupper($supply_type) === 'TCS') {

                    $dataArray['supply_type'] = "tcs";
                } else {
                    $dataArray['supply_type'] = $supply_type;
                }

                $dataArray['ecommerce_gstin_number'] = isset($data['E']) ? $data['E'] : '';
                $dataArray['ecommerce_vendor_code'] = isset($data['F']) ? $data['F'] : '';

                $supply_place = isset($data['G']) ? $data['G'] : '';

                if ($supply_place != '') {

                    $supply_state_data = $this->getStateDetailByStateCode($supply_place);
                    if ($supply_state_data['status'] === "success") {
                        $dataArray['supply_place'] = $supply_state_data['data']->state_id;
                    } else {

                        $errorflag = true;
                        array_push($currentItemError, "Invalid supply state code.");
                        $dataArray['supply_place'] = 'Invalid State';
                    }
                } else {
                    $dataArray['supply_place'] = $supply_place;
                }

                $dataArray['billing_name'] = isset($data['H']) ? $data['H'] : '';
                $dataArray['billing_address'] = isset($data['I']) ? $data['I'] : '';
                $billing_state = isset($data['J']) ? $data['J'] : '';

                if ($billing_state != '') {

                    $billing_state_data = $this->getStateDetailByStateCode($billing_state);
                    if ($billing_state_data['status'] === "success") {
                        $dataArray['billing_state'] = $billing_state_data['data']->state_id;
                        $dataArray['billing_state_name'] = $billing_state_data['data']->state_name;
                    } else {

                        $errorflag = true;
                        array_push($currentItemError, "Invalid billing state code.");
                        $dataArray['billing_state'] = 'Invalid State';
                    }
                } else {
                    $dataArray['billing_state'] = $billing_state;
                }

                $dataArray['billing_gstin_number'] = isset($data['K']) ? $data['K'] : '';
                $dataArray['shipping_name'] = isset($data['L']) ? $data['L'] : '';
                $dataArray['shipping_address'] = isset($data['M']) ? $data['M'] : '';
                $shipping_state = isset($data['N']) ? $data['N'] : '';

                if ($shipping_state != '') {

                    $shipping_state_data = $this->getStateDetailByStateCode($shipping_state);
                    if ($shipping_state_data['status'] === "success") {
                        $dataArray['shipping_state'] = $shipping_state_data['data']->state_id;
                        $dataArray['shipping_state_name'] = $shipping_state_data['data']->state_name;
                    } else {
                    
				         
                        $errorflag = true;
                        array_push($currentItemError, "Invalid shipping state code.");
                        $dataArray['shipping_state'] = 'Invalid State';
                    }
                } else {
                    $dataArray['shipping_state'] = $shipping_state;
                }

                $dataArray['shipping_gstin_number'] = isset($data['O']) ? $data['O'] : '';

                $item_name = isset($data['P']) ? trim($data['P']) : '';
                $item_hsn_code = isset($data['Q']) ? trim($data['Q']) : '';

                //if(!empty($item_hsn_code)) { $item_hsn_code = str_pad($item_hsn_code, 8, "0", STR_PAD_LEFT); }

                $dataArray['item_quantity'] = isset($data['R']) ? $data['R'] : '';
                $dataArray['item_unit'] = isset($data['S']) ? $data['S'] : '';
				
                $dataArray['item_rate'] = isset($data['T']) ? $data['T'] : '';
				$item_unit =  $dataArray['item_unit'];
				$item_rate =  $dataArray['item_rate'];
				
                $dataArray['item_discount'] = isset($data['U']) ? $data['U'] : '';
                $advance_adjustment = isset($data['V']) ? $data['V'] : '';
                $dataArray['advance_amount'] = isset($data['W']) ? $data['W'] : 0;

                if ($advance_adjustment != '' && strtoupper($advance_adjustment) === 'Y') {
                    $dataArray['advance_adjustment'] = 1;
                } else if ($advance_adjustment != '' && strtoupper($advance_adjustment) === 'N') {
                    $dataArray['advance_adjustment'] = 0;
                } else {
                    $dataArray['advance_adjustment'] = $advance_adjustment;
                }

                if ($item_name != '' && $item_hsn_code != '') {

                    $checkClientMasterItem = $this->get_row("select cm.item_id, cm.item_name, cm.unit_price, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, m.igst_tax_rate, m.csgt_tax_rate, m.sgst_tax_rate, m.cess_tax_rate, cm.item_unit, u.unit_id, u.unit_name, u.unit_code from " . $this->tableNames['client_master_item'] . " as cm, " . $this->tableNames['item'] . " as m, " . $this->tableNames['unit'] . " as u where 1=1 AND cm.item_category = m.item_id AND cm.item_unit = u.unit_id AND cm.item_name = '" . $item_name . "' && m.hsn_code = '" . $item_hsn_code . "' AND cm.is_deleted='0' AND cm.status = '1' AND cm.added_by = '" . $this->sanitize($_SESSION['user_detail']['user_id']) . "'");

                    if (count($checkClientMasterItem) == 1) {

                        $dataArray['item_name'] = $item_name;
                        $dataArray['item_hsn_code'] = $item_hsn_code;
                    } else {
				
					  
					   $sql="select count(cm.item_id) as itemcount,cm.item_id from " . $this->tableNames['item'] . " as cm  where cm.hsn_code='".$item_hsn_code."' and cm.is_deleted='0' AND cm.status = '1'";
				   
				   $dataHsncode = $this->get_results($sql);
					if($dataHsncode[0]->itemcount > 0)
					{
						$unit_id;
				    $sql="select count(unit_id) as unit_id_count,unit_id from " . $this->tableNames['unit'] . " as u where u.unit_name='".$item_unit."' and u.is_deleted='0' AND u.status = '1'";
						 	 
					$dataUnit = $this->get_results($sql);
						if($dataUnit[0]->unit_id_count > 0)
						{
							$unit_id = $dataUnit[0]->unit_id;
						}
						$dataInsertArray['item_name'] = $item_name;
						$dataInsertArray['item_category'] = $dataHsncode[0]->item_id;
						$dataInsertArray['unit_price'] = $item_rate;
						$dataInsertArray['item_unit'] = $unit_id;
						$dataInsertArray['added_by'] = $_SESSION['user_detail']['user_id'];
						$dataInsertArray['added_date'] = date('Y-m-d H:i:s');
						$dataInsertArray['status'] =1;
						$dataInsertArray['is_deleted'] =0;
						
						 $sql="select count(item_id) as itemcount from " . $this->tableNames['client_master_item'] . " as i where i.is_deleted='0' AND i.status = '1' AND i.added_by = '" . $this->sanitize($_SESSION['user_detail']['user_id']) . "' and item_category='".$dataHsncode[0]->item_id."'";
							 
					    $dataItem = $this->get_results($sql);
						if($dataItem[0]->itemcount == 0)
						{
						 if ($this->insert($this->tableNames['client_master_item'], $dataInsertArray)) {
							// return true
							  $dataArray['item_name'] = $item_name;
                             $dataArray['item_hsn_code'] = $item_hsn_code;
						 }
						}
						$dataArray['item_name'] = $item_name;
                        $dataArray['item_hsn_code'] = $item_hsn_code;
					}
					else
					{

                        $errorflag = true;
                        array_push($currentItemError, "Item not exist with this hsn code.");
                        $dataArray['item_name'] = "#####Item not exist#####";
                        $dataArray['item_hsn_code'] = "#####HSN code not exist#####";
					}
                    }
                } else {
                    $dataArray['item_name'] = $item_name;
                    $dataArray['item_hsn_code'] = $item_hsn_code;
                }

                $invoiceErrors = $this->validateClientInvoiceExcel($dataArray);
                if ($invoiceErrors !== true || !empty($currentItemError)) {

                    $errorflag = true;
                    if ($invoiceErrors === true) {
                        $invoiceErrors = array();
                    }
                    $invoiceErrors = array_merge($invoiceErrors, $currentItemError);
                    $invoiceErrors = implode(", ", $invoiceErrors);
                    $objPHPExcel->getActiveSheet()->SetCellValue('X' . $rowKey, $invoiceErrors);
                }

                /* get current user data */
                $dataCurrentUserArr = $this->getUserDetailsById($this->sanitize($_SESSION['user_detail']['user_id']));

                /* create invoice array */
                $arrayKey = $dataArray['reference_number'];

                $invoiceArray[$arrayKey]['invoice_type'] = $dataArray['invoice_type'];
                $invoiceArray[$arrayKey]['reference_number'] = $dataArray['reference_number'];
                $invoiceArray[$arrayKey]['company_name'] = $dataCurrentUserArr['data']->kyc->name;
                $invoiceArray[$arrayKey]['company_address'] = $dataCurrentUserArr['data']->kyc->registered_address;
                $invoiceArray[$arrayKey]['company_state'] = $dataCurrentUserArr['data']->kyc->state_id;
                $invoiceArray[$arrayKey]['gstin_number'] = $dataCurrentUserArr['data']->kyc->gstin_number;
                $invoiceArray[$arrayKey]['supply_type'] = $dataArray['supply_type'];
                $invoiceArray[$arrayKey]['invoice_date'] = $dataArray['invoice_date'];
                $invoiceArray[$arrayKey]['supply_place'] = $dataArray['supply_place'];
                $invoiceArray[$arrayKey]['ecommerce_gstin_number'] = $dataArray['ecommerce_gstin_number'];
                $invoiceArray[$arrayKey]['ecommerce_vendor_code'] = $dataArray['ecommerce_vendor_code'];
                $invoiceArray[$arrayKey]['billing_name'] = $dataArray['billing_name'];
                $invoiceArray[$arrayKey]['billing_address'] = $dataArray['billing_address'];
                $invoiceArray[$arrayKey]['billing_state'] = $dataArray['billing_state'];
                $invoiceArray[$arrayKey]['billing_state_name'] = $dataArray['billing_state_name'];
                $invoiceArray[$arrayKey]['billing_gstin_number'] = $dataArray['billing_gstin_number'];
                $invoiceArray[$arrayKey]['shipping_name'] = $dataArray['shipping_name'];
                $invoiceArray[$arrayKey]['shipping_address'] = $dataArray['shipping_address'];
                $invoiceArray[$arrayKey]['shipping_state'] = $dataArray['shipping_state'];
                $invoiceArray[$arrayKey]['shipping_state_name'] = $dataArray['shipping_state_name'];
                $invoiceArray[$arrayKey]['shipping_gstin_number'] = $dataArray['shipping_gstin_number'];
                $invoiceArray[$arrayKey]['advance_adjustment'] = $dataArray['advance_adjustment'];

                //items
                $invoiceItemArray['item_name'] = $dataArray['item_name'];
                $invoiceItemArray['item_hsncode'] = $dataArray['item_hsn_code'];
                $invoiceItemArray['item_quantity'] = $dataArray['item_quantity'];
                $invoiceItemArray['item_unit'] = $dataArray['item_unit'];
                $invoiceItemArray['item_unit_price'] = $dataArray['item_rate'];
                $invoiceItemArray['item_discount'] = $dataArray['item_discount'];
                $invoiceItemArray['advance_amount'] = $dataArray['advance_amount'];

                $invoiceArray[$arrayKey]['items'][] = $invoiceItemArray;
            }

            if ($errorflag === true) {

                $objPHPExcel->getActiveSheet()->SetCellValue('X1', "Error Information");
                $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
                $objWriter->save($invoice_excel_dir_path);
                $this->setError($this->validationMessage['excelerror']);

                $resultArray = array("status" => "error", "excelurl" => $invoice_excel_url_path);
                return json_encode($resultArray);
            } else {

                foreach ($invoiceArray as $invoiceRow) {

                    $invoiceItemArray = array();
                    $invoiceTotalAmount = 0.00;

                    foreach ($invoiceRow['items'] as $invoiceInnerRow) {

                        $clientMasterItem = $this->get_row("select cm.item_id, cm.item_name, cm.unit_price, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, m.igst_tax_rate, m.csgt_tax_rate, m.sgst_tax_rate, m.cess_tax_rate, cm.item_unit, u.unit_id, u.unit_name, u.unit_code from " . $this->tableNames['client_master_item'] . " as cm, " . $this->tableNames['item'] . " as m, " . $this->tableNames['unit'] . " as u where 1=1 AND cm.item_category = m.item_id AND cm.item_unit = u.unit_id AND cm.item_name = '" . $invoiceInnerRow['item_name'] . "' && m.hsn_code = '" . $invoiceInnerRow['item_hsncode'] . "' AND cm.is_deleted='0' AND cm.status = '1' AND cm.added_by = '" . $_SESSION['user_detail']['user_id'] . "'");
                        if (!empty($clientMasterItem)) {

                            $itemUnitPrice = (float) $invoiceInnerRow['item_unit_price'];
                            $invoiceItemQuantity = (int) $invoiceInnerRow['item_quantity'];
                            $invoiceItemDiscount = (float) $invoiceInnerRow['item_discount'];
                            $invoiceItemAdvanceAmount = (float) $invoiceInnerRow['advance_amount'];

                            $invoiceItemTotal = round(($invoiceItemQuantity * $itemUnitPrice), 2);
                            $invoiceItemDiscountAmount = ($invoiceItemDiscount / 100) * $invoiceItemTotal;
                            $invoiceItemReduceAmount = $invoiceItemAdvanceAmount + $invoiceItemDiscountAmount;
                            $invoiceItemTaxableAmount = round(($invoiceItemTotal - $invoiceItemReduceAmount), 2);

                            if (
                                    $invoiceRow['invoice_type'] === "sezunitinvoice" ||
                                    $invoiceRow['invoice_type'] === "deemedexportinvoice"
                            ) {

                                $itemCSGTTax = 0.00;
                                $invoiceItemCSGTTaxAmount = 0.00;

                                $itemSGSTTax = 0.00;
                                $invoiceItemSGSTTaxAmount = 0.00;

                                $itemIGSTTax = (float) $clientMasterItem->igst_tax_rate;
                                $itemCESSTax = (float) $clientMasterItem->cess_tax_rate;

                                $invoiceItemIGSTTaxAmount = round((($itemIGSTTax / 100) * $invoiceItemTaxableAmount), 2);
                                $invoiceItemCESSTaxAmount = round((($itemCESSTax / 100) * $invoiceItemTaxableAmount), 2);
                            } else {

                                if ($invoiceRow['company_state'] === $invoiceRow['supply_place']) {

                                    $itemCSGTTax = (float) $clientMasterItem->csgt_tax_rate;
                                    $itemSGSTTax = (float) $clientMasterItem->sgst_tax_rate;
                                    $itemIGSTTax = 0.00;
                                    $itemCESSTax = (float) $clientMasterItem->cess_tax_rate;

                                    $invoiceItemCSGTTaxAmount = round((($itemCSGTTax / 100) * $invoiceItemTaxableAmount), 2);
                                    $invoiceItemSGSTTaxAmount = round((($itemSGSTTax / 100) * $invoiceItemTaxableAmount), 2);
                                    $invoiceItemIGSTTaxAmount = 0.00;
                                    $invoiceItemCESSTaxAmount = round((($itemCESSTax / 100) * $invoiceItemTaxableAmount), 2);
                                } else {

                                    $itemCSGTTax = 0.00;
                                    $itemSGSTTax = 0.00;
                                    $itemIGSTTax = (float) $clientMasterItem->igst_tax_rate;
                                    $itemCESSTax = (float) $clientMasterItem->cess_tax_rate;

                                    $invoiceItemCSGTTaxAmount = 0.00;
                                    $invoiceItemSGSTTaxAmount = 0.00;
                                    $invoiceItemIGSTTaxAmount = round((($itemIGSTTax / 100) * $invoiceItemTaxableAmount), 2);
                                    $invoiceItemCESSTaxAmount = round((($itemCESSTax / 100) * $invoiceItemTaxableAmount), 2);
                                }
                            }

                            if ($invoiceRow['supply_type'] == "reversecharge") {

                                $invoiceItemTotalAmount = round(($invoiceItemTaxableAmount), 2);
                                $invoiceTotalAmount += $invoiceItemTotalAmount;
                            } else {

                                $invoiceItemTotalAmount = round(($invoiceItemTaxableAmount + $invoiceItemCSGTTaxAmount + $invoiceItemSGSTTaxAmount + $invoiceItemIGSTTaxAmount + $invoiceItemCESSTaxAmount), 2);
                                $invoiceTotalAmount += $invoiceItemTotalAmount;
                            }

                            $ItemArray = array(
                                "item_id" => $clientMasterItem->item_id,
                                "item_name" => $clientMasterItem->item_name,
                                "item_hsncode" => $clientMasterItem->hsn_code,
                                "item_quantity" => $invoiceItemQuantity,
                                "item_unit" => $clientMasterItem->unit_code,
                                "item_unit_price" => $itemUnitPrice,
                                "subtotal" => $invoiceItemTotal,
                                "discount" => $invoiceItemDiscount,
                                "advance_amount" => $invoiceItemAdvanceAmount,
                                "taxable_subtotal" => $invoiceItemTaxableAmount,
                                "cgst_rate" => $itemCSGTTax,
                                "cgst_amount" => $invoiceItemCSGTTaxAmount,
                                "sgst_rate" => $itemSGSTTax,
                                "sgst_amount" => $invoiceItemSGSTTaxAmount,
                                "igst_rate" => $itemIGSTTax,
                                "igst_amount" => $invoiceItemIGSTTaxAmount,
                                "cess_rate" => $itemCESSTax,
                                "cess_amount" => $invoiceItemCESSTaxAmount,
                                "total" => $invoiceItemTotalAmount,
                                "status" => 1,
                                "added_by" => $_SESSION['user_detail']['user_id'],
                                "added_date" => date('Y-m-d H:i:s')
                            );

                            array_push($invoiceItemArray, $ItemArray);
                        }
                    }

                    if (!empty($invoiceItemArray) && count($invoiceItemArray) > 0) {

                        $InsertArray['invoice_type'] = $invoiceRow['invoice_type'];
                        $InsertArray['reference_number'] = $invoiceRow['reference_number'];
                        $InsertArray['serial_number'] = $this->generateInvoiceNumber($this->sanitize($_SESSION['user_detail']['user_id']));
                        $InsertArray['company_name'] = $invoiceRow['company_name'];
                        $InsertArray['company_address'] = $invoiceRow['company_address'];
                        $InsertArray['company_state'] = $invoiceRow['company_state'];
                        $InsertArray['gstin_number'] = $invoiceRow['gstin_number'];
                        $InsertArray['supply_type'] = $invoiceRow['supply_type'];
                        $InsertArray['invoice_date'] = $invoiceRow['invoice_date'];
                        $InsertArray['supply_place'] = $invoiceRow['supply_place'];
                        $InsertArray['ecommerce_gstin_number'] = $invoiceRow['ecommerce_gstin_number'];
                        $InsertArray['ecommerce_vendor_code'] = $invoiceRow['ecommerce_vendor_code'];
                        $InsertArray['billing_name'] = $invoiceRow['billing_name'];
                        $InsertArray['billing_address'] = $invoiceRow['billing_address'];
                        $InsertArray['billing_state'] = $invoiceRow['billing_state'];
                        $InsertArray['billing_state_name'] = $invoiceRow['billing_state_name'];
                        $InsertArray['billing_gstin_number'] = $invoiceRow['billing_gstin_number'];
                        $InsertArray['shipping_name'] = $invoiceRow['shipping_name'];
                        $InsertArray['shipping_address'] = $invoiceRow['shipping_address'];
                        $InsertArray['shipping_state'] = $invoiceRow['shipping_state'];
                        $InsertArray['shipping_state_name'] = $invoiceRow['shipping_state_name'];
                        $InsertArray['shipping_gstin_number'] = $invoiceRow['shipping_gstin_number'];
                        $InsertArray['advance_adjustment'] = $invoiceRow['advance_adjustment'];
                        $InsertArray['invoice_total_value'] = $invoiceTotalAmount;
                        $InsertArray['financial_year'] = $this->generateFinancialYear();
                        $InsertArray['status'] = 1;
                        $InsertArray['added_by'] = $_SESSION['user_detail']['user_id'];
                        $InsertArray['added_date'] = date('Y-m-d H:i:s');

                        if ($this->insert($this->tableNames['client_invoice'], $InsertArray)) {

                            $insertid = $this->getInsertID();
                            $this->logMsg("New Invoice Added. ID : " . $insertid . ".");

                            $processedInvoiceItemArray = array();
                            foreach ($invoiceItemArray as $itemArr) {

                                $itemArr['invoice_id'] = $insertid;
                                array_push($processedInvoiceItemArray, $itemArr);
                            }

                            if ($this->insertMultiple($this->tableNames['client_invoice_item'], $processedInvoiceItemArray)) {

                                $iteminsertid = $this->getInsertID();
                                $this->logMsg("New Invoice Item Added. ID : " . $iteminsertid . ".");
                            }
                        }
                    }
                }

                $this->setSuccess($this->validationMessage['invoiceadded']);
                return true;
            }
        }
    }

    /* upload client export invoice */

    public function uploadClientExportInvoice() {

        $flag = true;
        $errorflag = false;
        $dataArray = array();
        $indexArray = array();
        $invoiceArray = array();
        $invoiceItemArray = array();

        if ($_FILES['invoice_xlsx']['name'] != '' && $_FILES['invoice_xlsx']['error'] == 0) {

            $invoice_excel = $this->imageUploads($_FILES['invoice_xlsx'], 'invoice-docs', 'upload', $this->allowExcelExt);
            if ($invoice_excel == FALSE) {
                return false;
            }

            $invoice_excel_dir_path = PROJECT_ROOT . UPLOAD_DIR . "/invoice-docs/" . $invoice_excel;
            $invoice_excel_url_path = PROJECT_URL . UPLOAD_DIR . "/invoice-docs/" . $invoice_excel;

            $objPHPExcel = PHPExcel_IOFactory::load($invoice_excel_dir_path);
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
			$sheetData = array_map('array_filter', $sheetData);
			$sheetData = array_filter($sheetData);

            foreach ($sheetData as $rowKey => $data) {

                if ($flag) {
                    $indexArray = $data;
                    $flag = false;
                    continue;
                }

                $currentItemError = array();
                $dataArray['reference_number'] = isset($data['A']) ? $data['A'] : '';
                $export_supply_meant = isset($data['B']) ? $data['B'] : '';

                if ($export_supply_meant != '' && strtoupper($export_supply_meant) === 'WITH PAYMENT') {
                    $dataArray['export_supply_meant'] = "withpayment";
                } else if ($export_supply_meant != '' && strtoupper($export_supply_meant) === 'WITHOUT PAYMENT') {
                    $dataArray['export_supply_meant'] = "withoutpayment";
                } else {
                    $dataArray['export_supply_meant'] = $export_supply_meant;
                }

                $dataArray['invoice_date'] = isset($data['C']) ? $data['C'] : '';

                $dataArray['billing_name'] = isset($data['D']) ? $data['D'] : '';
                $dataArray['billing_address'] = isset($data['E']) ? $data['E'] : '';
                $dataArray['billing_state'] = 0;
                $dataArray['billing_state_name'] = isset($data['F']) ? $data['F'] : '';
                $billing_country = isset($data['G']) ? $data['G'] : '';

                if ($billing_country != '') {

                    $billing_country_data = $this->getCountryDetailByCountryCode($billing_country);
                    if ($billing_country_data['status'] === "success") {
                        $dataArray['billing_country'] = $billing_country_data['data']->id;
                    } else {

                        $errorflag = true;
                        array_push($currentItemError, "Invalid billing country code.");
                        $dataArray['billing_country'] = 'Invalid Country';
                    }
                } else {
                    $dataArray['billing_country'] = $billing_country;
                }

                $dataArray['shipping_name'] = isset($data['H']) ? $data['H'] : '';
                $dataArray['shipping_address'] = isset($data['I']) ? $data['I'] : '';

                $dataArray['shipping_state'] = 0;
                $dataArray['shipping_state_name'] = isset($data['J']) ? $data['J'] : '';
                $shipping_country = isset($data['K']) ? $data['K'] : '';

                if ($shipping_country != '') {

                    $shipping_country_data = $this->getCountryDetailByCountryCode($shipping_country);
                    if ($shipping_country_data['status'] === "success") {
                        $dataArray['shipping_country'] = $shipping_country_data['data']->id;
                    } else {

                        $errorflag = true;
                        array_push($currentItemError, "Invalid shipping country code.");
                        $dataArray['shipping_country'] = 'Invalid Country';
                    }
                } else {
                    $dataArray['shipping_country'] = $shipping_country;
                }

                $item_name = isset($data['L']) ? trim($data['L']) : '';
                $item_hsn_code = isset($data['M']) ? trim($data['M']) : '';

                //if(!empty($item_hsn_code)) { $item_hsn_code = str_pad($item_hsn_code, 8, "0", STR_PAD_LEFT); }

                $dataArray['item_quantity'] = isset($data['N']) ? $data['N'] : '';
                $dataArray['item_unit'] = isset($data['O']) ? $data['O'] : '';
                $dataArray['item_rate'] = isset($data['P']) ? $data['P'] : '';
                $dataArray['item_discount'] = isset($data['Q']) ? $data['Q'] : '';
                $advance_adjustment = isset($data['R']) ? $data['R'] : '';
                $dataArray['advance_amount'] = isset($data['S']) ? $data['S'] : 0;

                if ($advance_adjustment != '' && strtoupper($advance_adjustment) === 'Y') {
                    $dataArray['advance_adjustment'] = 1;
                } else if ($advance_adjustment != '' && strtoupper($advance_adjustment) === 'N') {
                    $dataArray['advance_adjustment'] = 0;
                } else {
                    $dataArray['advance_adjustment'] = $advance_adjustment;
                }

                if ($item_name != '' && $item_hsn_code != '') {

                    $checkClientMasterItem = $this->get_row("select cm.item_id, cm.item_name, cm.unit_price, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, m.igst_tax_rate, m.csgt_tax_rate, m.sgst_tax_rate, m.cess_tax_rate, cm.item_unit, u.unit_id, u.unit_name, u.unit_code from " . $this->tableNames['client_master_item'] . " as cm, " . $this->tableNames['item'] . " as m, " . $this->tableNames['unit'] . " as u where 1=1 AND cm.item_category = m.item_id AND cm.item_unit = u.unit_id AND cm.item_name = '" . $item_name . "' && m.hsn_code = '" . $item_hsn_code . "' AND cm.is_deleted='0' AND cm.status = '1' AND cm.added_by = '" . $this->sanitize($_SESSION['user_detail']['user_id']) . "'");

                    if (count($checkClientMasterItem) == 1) {

                        $dataArray['item_name'] = $item_name;
                        $dataArray['item_hsn_code'] = $item_hsn_code;
                    } else {

                        $errorflag = true;
                        array_push($currentItemError, "Item not exist with this hsn code.");
                        $dataArray['item_name'] = "#####Item not exist#####";
                        $dataArray['item_hsn_code'] = "#####HSN code not exist#####";
                    }
                } else {
                    $dataArray['item_name'] = $item_name;
                    $dataArray['item_hsn_code'] = $item_hsn_code;
                }

                $invoiceErrors = $this->validateClientInvoiceExcel($dataArray);
                if ($invoiceErrors !== true || !empty($currentItemError)) {

                    $errorflag = true;
                    if ($invoiceErrors === true) {
                        $invoiceErrors = array();
                    }
                    $invoiceErrors = array_merge($invoiceErrors, $currentItemError);
                    $invoiceErrors = implode(", ", $invoiceErrors);
                    $objPHPExcel->getActiveSheet()->SetCellValue('T' . $rowKey, $invoiceErrors);
                }

                /* get current user data */
                $dataCurrentUserArr = $this->getUserDetailsById($this->sanitize($_SESSION['user_detail']['user_id']));

                /* create invoice array */
                $arrayKey = $dataArray['reference_number'];

                $invoiceArray[$arrayKey]['invoice_type'] = "exportinvoice";
                $invoiceArray[$arrayKey]['export_supply_meant'] = $dataArray['export_supply_meant'];
                $invoiceArray[$arrayKey]['reference_number'] = $dataArray['reference_number'];
                $invoiceArray[$arrayKey]['company_name'] = $dataCurrentUserArr['data']->kyc->name;
                $invoiceArray[$arrayKey]['company_address'] = $dataCurrentUserArr['data']->kyc->registered_address;
                $invoiceArray[$arrayKey]['company_state'] = $dataCurrentUserArr['data']->kyc->state_id;
                $invoiceArray[$arrayKey]['gstin_number'] = $dataCurrentUserArr['data']->kyc->gstin_number;
                $invoiceArray[$arrayKey]['invoice_date'] = $dataArray['invoice_date'];
                $invoiceArray[$arrayKey]['supply_place'] = 0;
                $invoiceArray[$arrayKey]['billing_name'] = $dataArray['billing_name'];
                $invoiceArray[$arrayKey]['billing_address'] = $dataArray['billing_address'];
                $invoiceArray[$arrayKey]['billing_state'] = $dataArray['billing_state'];
                $invoiceArray[$arrayKey]['billing_state_name'] = $dataArray['billing_state_name'];
                $invoiceArray[$arrayKey]['billing_country'] = $dataArray['billing_country'];
                $invoiceArray[$arrayKey]['shipping_name'] = $dataArray['shipping_name'];
                $invoiceArray[$arrayKey]['shipping_address'] = $dataArray['shipping_address'];
                $invoiceArray[$arrayKey]['shipping_state'] = $dataArray['shipping_state'];
                $invoiceArray[$arrayKey]['shipping_state_name'] = $dataArray['shipping_state_name'];
                $invoiceArray[$arrayKey]['shipping_country'] = $dataArray['shipping_country'];
                $invoiceArray[$arrayKey]['advance_adjustment'] = $dataArray['advance_adjustment'];

                //items
                $invoiceItemArray['item_name'] = $dataArray['item_name'];
                $invoiceItemArray['item_hsncode'] = $dataArray['item_hsn_code'];
                $invoiceItemArray['item_quantity'] = $dataArray['item_quantity'];
                $invoiceItemArray['item_unit'] = $dataArray['item_unit'];
                $invoiceItemArray['item_unit_price'] = $dataArray['item_rate'];
                $invoiceItemArray['item_discount'] = $dataArray['item_discount'];
                $invoiceItemArray['advance_amount'] = $dataArray['advance_amount'];

                $invoiceArray[$arrayKey]['items'][] = $invoiceItemArray;
            }

            if ($errorflag === true) {

                $objPHPExcel->getActiveSheet()->SetCellValue('T1', "Error Information");
                $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
                $objWriter->save($invoice_excel_dir_path);
                $this->setError($this->validationMessage['excelerror']);

                $resultArray = array("status" => "error", "excelurl" => $invoice_excel_url_path);
                return json_encode($resultArray);
            } else {

                foreach ($invoiceArray as $invoiceRow) {

                    $invoiceItemArray = array();
                    $invoiceTotalAmount = 0.00;

                    foreach ($invoiceRow['items'] as $invoiceInnerRow) {

                        $clientMasterItem = $this->get_row("select cm.item_id, cm.item_name, cm.unit_price, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, m.igst_tax_rate, m.csgt_tax_rate, m.sgst_tax_rate, m.cess_tax_rate, cm.item_unit, u.unit_id, u.unit_name, u.unit_code from " . $this->tableNames['client_master_item'] . " as cm, " . $this->tableNames['item'] . " as m, " . $this->tableNames['unit'] . " as u where 1=1 AND cm.item_category = m.item_id AND cm.item_unit = u.unit_id AND cm.item_name = '" . $invoiceInnerRow['item_name'] . "' && m.hsn_code = '" . $invoiceInnerRow['item_hsncode'] . "' AND cm.is_deleted='0' AND cm.status = '1' AND cm.added_by = '" . $_SESSION['user_detail']['user_id'] . "'");
                        if (!empty($clientMasterItem)) {

                            $itemUnitPrice = (float) $invoiceInnerRow['item_unit_price'];
                            $invoiceItemQuantity = (int) $invoiceInnerRow['item_quantity'];
                            $invoiceItemDiscount = (float) $invoiceInnerRow['item_discount'];
                            $invoiceItemAdvanceAmount = (float) $invoiceInnerRow['advance_amount'];

                            $invoiceItemTotal = round(($invoiceItemQuantity * $itemUnitPrice), 2);
                            $invoiceItemDiscountAmount = ($invoiceItemDiscount / 100) * $invoiceItemTotal;
                            $invoiceItemReduceAmount = $invoiceItemAdvanceAmount + $invoiceItemDiscountAmount;
                            $invoiceItemTaxableAmount = round(($invoiceItemTotal - $invoiceItemReduceAmount), 2);

                            if ($invoiceRow['export_supply_meant'] === "withpayment") {

                                $itemIGSTTax = (float) $clientMasterItem->igst_tax_rate;
                                $itemCESSTax = (float) $clientMasterItem->cess_tax_rate;

                                $invoiceItemIGSTTaxAmount = round((($itemIGSTTax / 100) * $invoiceItemTaxableAmount), 2);
                                $invoiceItemCESSTaxAmount = round((($itemCESSTax / 100) * $invoiceItemTaxableAmount), 2);
                            } else {

                                $itemIGSTTax = 0.00;
                                $itemCESSTax = 0.00;

                                $invoiceItemIGSTTaxAmount = 0.00;
                                $invoiceItemCESSTaxAmount = 0.00;
                            }

                            $invoiceItemTotalAmount = round(($invoiceItemTaxableAmount + $invoiceItemIGSTTaxAmount + $invoiceItemCESSTaxAmount), 2);
                            $invoiceTotalAmount += $invoiceItemTotalAmount;

                            $ItemArray = array(
                                "item_id" => $clientMasterItem->item_id,
                                "item_name" => $clientMasterItem->item_name,
                                "item_hsncode" => $clientMasterItem->hsn_code,
                                "item_quantity" => $invoiceItemQuantity,
                                "item_unit" => $clientMasterItem->unit_code,
                                "item_unit_price" => $itemUnitPrice,
                                "subtotal" => $invoiceItemTotal,
                                "discount" => $invoiceItemDiscount,
                                "advance_amount" => $invoiceItemAdvanceAmount,
                                "taxable_subtotal" => $invoiceItemTaxableAmount,
                                "cgst_rate" => 0.00,
                                "cgst_amount" => 0.00,
                                "sgst_rate" => 0.00,
                                "sgst_amount" => 0.00,
                                "igst_rate" => $itemIGSTTax,
                                "igst_amount" => $invoiceItemIGSTTaxAmount,
                                "cess_rate" => $itemCESSTax,
                                "cess_amount" => $invoiceItemCESSTaxAmount,
                                "total" => $invoiceItemTotalAmount,
                                "status" => 1,
                                "added_by" => $_SESSION['user_detail']['user_id'],
                                "added_date" => date('Y-m-d H:i:s')
                            );

                            array_push($invoiceItemArray, $ItemArray);
                        }
                    }

                    if (!empty($invoiceItemArray) && count($invoiceItemArray) > 0) {

                        $InsertArray['invoice_type'] = $invoiceRow['invoice_type'];
                        $InsertArray['reference_number'] = $invoiceRow['reference_number'];
                        $InsertArray['serial_number'] = $this->generateInvoiceNumber($this->sanitize($_SESSION['user_detail']['user_id']));
                        $InsertArray['company_name'] = $invoiceRow['company_name'];
                        $InsertArray['company_address'] = $invoiceRow['company_address'];
                        $InsertArray['company_state'] = $invoiceRow['company_state'];
                        $InsertArray['gstin_number'] = $invoiceRow['gstin_number'];
                        $InsertArray['invoice_date'] = $invoiceRow['invoice_date'];
                        $InsertArray['supply_place'] = $invoiceRow['supply_place'];
                        $InsertArray['billing_name'] = $invoiceRow['billing_name'];
                        $InsertArray['billing_address'] = $invoiceRow['billing_address'];
                        $InsertArray['billing_state'] = $invoiceRow['billing_state'];
                        $InsertArray['billing_state_name'] = $invoiceRow['billing_state_name'];
                        $InsertArray['billing_country'] = $invoiceRow['billing_country'];
                        $InsertArray['shipping_name'] = $invoiceRow['shipping_name'];
                        $InsertArray['shipping_address'] = $invoiceRow['shipping_address'];
                        $InsertArray['shipping_state'] = $invoiceRow['shipping_state'];
                        $InsertArray['shipping_state_name'] = $invoiceRow['shipping_state_name'];
                        $InsertArray['shipping_country'] = $invoiceRow['shipping_country'];
                        $InsertArray['advance_adjustment'] = $invoiceRow['advance_adjustment'];
                        $InsertArray['invoice_total_value'] = $invoiceTotalAmount;
                        $InsertArray['financial_year'] = $this->generateFinancialYear();
                        $InsertArray['status'] = 1;
                        $InsertArray['added_by'] = $_SESSION['user_detail']['user_id'];
                        $InsertArray['added_date'] = date('Y-m-d H:i:s');

                        if ($this->insert($this->tableNames['client_invoice'], $InsertArray)) {

                            $insertid = $this->getInsertID();
                            $this->logMsg("New Invoice Added. ID : " . $insertid . ".");

                            $processedInvoiceItemArray = array();
                            foreach ($invoiceItemArray as $itemArr) {

                                $itemArr['invoice_id'] = $insertid;
                                array_push($processedInvoiceItemArray, $itemArr);
                            }

                            if ($this->insertMultiple($this->tableNames['client_invoice_item'], $processedInvoiceItemArray)) {

                                $iteminsertid = $this->getInsertID();
                                $this->logMsg("New Invoice Item Added. ID : " . $iteminsertid . ".");
                            }
                        }
                    }
                }

                $this->setSuccess($this->validationMessage['invoiceadded']);
                return true;
            }
        }
    }

    /* upload client bill of supply invoice */

    public function uploadClientBOSInvoice() {

        $flag = true;
        $errorflag = false;
        $dataArray = array();
        $indexArray = array();
        $invoiceArray = array();
        $invoiceItemArray = array();

        if ($_FILES['invoice_xlsx']['name'] != '' && $_FILES['invoice_xlsx']['error'] == 0) {

            $invoice_excel = $this->imageUploads($_FILES['invoice_xlsx'], 'invoice-docs', 'upload', $this->allowExcelExt);
            if ($invoice_excel == FALSE) {
                return false;
            }

            $invoice_excel_dir_path = PROJECT_ROOT . UPLOAD_DIR . "/invoice-docs/" . $invoice_excel;
            $invoice_excel_url_path = PROJECT_URL . UPLOAD_DIR . "/invoice-docs/" . $invoice_excel;

            $objPHPExcel = PHPExcel_IOFactory::load($invoice_excel_dir_path);
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
			$sheetData = array_map('array_filter', $sheetData);
			$sheetData = array_filter($sheetData);

            foreach ($sheetData as $rowKey => $data) {

                if ($flag) {
                    $indexArray = $data;
                    $flag = false;
                    continue;
                }

                $currentItemError = array();
                $dataArray['reference_number'] = isset($data['A']) ? $data['A'] : '';
                $dataArray['invoice_date'] = isset($data['B']) ? $data['B'] : '';

                $dataArray['billing_name'] = isset($data['C']) ? $data['C'] : '';
                $dataArray['billing_address'] = isset($data['D']) ? $data['D'] : '';
                $billing_state = isset($data['E']) ? $data['E'] : '';

                if ($billing_state != '') {

                    $billing_state_data = $this->getStateDetailByStateCode($billing_state);
                    if ($billing_state_data['status'] === "success") {
                        $dataArray['billing_state'] = $billing_state_data['data']->state_id;
                        $dataArray['billing_state_name'] = $billing_state_data['data']->state_name;
                    } else {

                        $errorflag = true;
                        array_push($currentItemError, "Invalid billing state code.");
                        $dataArray['billing_state'] = 'Invalid State';
                    }
                } else {
                    $dataArray['billing_state'] = $billing_state;
                }

                $dataArray['billing_gstin_number'] = isset($data['F']) ? $data['F'] : '';
                $dataArray['shipping_name'] = isset($data['G']) ? $data['G'] : '';
                $dataArray['shipping_address'] = isset($data['H']) ? $data['H'] : '';
                $shipping_state = isset($data['I']) ? $data['I'] : '';

                if ($shipping_state != '') {

                    $shipping_state_data = $this->getStateDetailByStateCode($shipping_state);
                    if ($shipping_state_data['status'] === "success") {
                        $dataArray['shipping_state'] = $shipping_state_data['data']->state_id;
                        $dataArray['shipping_state_name'] = $shipping_state_data['data']->state_name;
                    } else {

                        $errorflag = true;
                        array_push($currentItemError, "Invalid shipping state code.");
                        $dataArray['shipping_state'] = 'Invalid State';
                    }
                } else {
                    $dataArray['shipping_state'] = $shipping_state;
                }

                $dataArray['shipping_gstin_number'] = isset($data['J']) ? $data['J'] : '';

                $item_name = isset($data['K']) ? trim($data['K']) : '';
                $item_hsn_code = isset($data['L']) ? trim($data['L']) : '';

                //if(!empty($item_hsn_code)) { $item_hsn_code = str_pad($item_hsn_code, 8, "0", STR_PAD_LEFT); }

                $dataArray['item_quantity'] = isset($data['M']) ? $data['M'] : '';
                $dataArray['item_unit'] = isset($data['N']) ? $data['N'] : '';
                $dataArray['item_rate'] = isset($data['O']) ? $data['O'] : '';
                $dataArray['item_discount'] = isset($data['P']) ? $data['P'] : '';

                if ($item_name != '' && $item_hsn_code != '') {

                    $checkClientMasterItem = $this->get_row("select cm.item_id, cm.item_name, cm.unit_price, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, m.igst_tax_rate, m.csgt_tax_rate, m.sgst_tax_rate, m.cess_tax_rate, cm.item_unit, u.unit_id, u.unit_name, u.unit_code from " . $this->tableNames['client_master_item'] . " as cm, " . $this->tableNames['item'] . " as m, " . $this->tableNames['unit'] . " as u where 1=1 AND cm.item_category = m.item_id AND cm.item_unit = u.unit_id AND cm.item_name = '" . $item_name . "' && m.hsn_code = '" . $item_hsn_code . "' AND cm.is_deleted='0' AND cm.status = '1' AND cm.added_by = '" . $this->sanitize($_SESSION['user_detail']['user_id']) . "'");

                    if (count($checkClientMasterItem) == 1) {

                        $dataArray['item_name'] = $item_name;
                        $dataArray['item_hsn_code'] = $item_hsn_code;
                    } else {

                        $errorflag = true;
                        array_push($currentItemError, "Item not exist with this hsn code.");
                        $dataArray['item_name'] = "#####Item not exist#####";
                        $dataArray['item_hsn_code'] = "#####HSN code not exist#####";
                    }
                } else {
                    $dataArray['item_name'] = $item_name;
                    $dataArray['item_hsn_code'] = $item_hsn_code;
                }

                $invoiceErrors = $this->validateClientInvoiceExcel($dataArray);
                if ($invoiceErrors !== true || !empty($currentItemError)) {

                    $errorflag = true;
                    if ($invoiceErrors === true) {
                        $invoiceErrors = array();
                    }
                    $invoiceErrors = array_merge($invoiceErrors, $currentItemError);
                    $invoiceErrors = implode(", ", $invoiceErrors);
                    $objPHPExcel->getActiveSheet()->SetCellValue('Q' . $rowKey, $invoiceErrors);
                }

                /* get current user data */
                $dataCurrentUserArr = $this->getUserDetailsById($this->sanitize($_SESSION['user_detail']['user_id']));

                /* create invoice array */
                $arrayKey = $dataArray['reference_number'];

                $invoiceArray[$arrayKey]['reference_number'] = $dataArray['reference_number'];
                $invoiceArray[$arrayKey]['company_name'] = $dataCurrentUserArr['data']->kyc->name;
                $invoiceArray[$arrayKey]['company_address'] = $dataCurrentUserArr['data']->kyc->registered_address;
                $invoiceArray[$arrayKey]['company_state'] = $dataCurrentUserArr['data']->kyc->state_id;
                $invoiceArray[$arrayKey]['gstin_number'] = $dataCurrentUserArr['data']->kyc->gstin_number;
                $invoiceArray[$arrayKey]['invoice_date'] = $dataArray['invoice_date'];
                $invoiceArray[$arrayKey]['billing_name'] = $dataArray['billing_name'];
                $invoiceArray[$arrayKey]['billing_address'] = $dataArray['billing_address'];
                $invoiceArray[$arrayKey]['billing_state'] = $dataArray['billing_state'];
                $invoiceArray[$arrayKey]['billing_state_name'] = $dataArray['billing_state_name'];
                $invoiceArray[$arrayKey]['billing_gstin_number'] = $dataArray['billing_gstin_number'];
                $invoiceArray[$arrayKey]['shipping_name'] = $dataArray['shipping_name'];
                $invoiceArray[$arrayKey]['shipping_address'] = $dataArray['shipping_address'];
                $invoiceArray[$arrayKey]['shipping_state'] = $dataArray['shipping_state'];
                $invoiceArray[$arrayKey]['shipping_state_name'] = $dataArray['shipping_state_name'];
                $invoiceArray[$arrayKey]['shipping_gstin_number'] = $dataArray['shipping_gstin_number'];

                //items
                $invoiceItemArray['item_name'] = $dataArray['item_name'];
                $invoiceItemArray['item_hsncode'] = $dataArray['item_hsn_code'];
                $invoiceItemArray['item_quantity'] = $dataArray['item_quantity'];
                $invoiceItemArray['item_unit'] = $dataArray['item_unit'];
                $invoiceItemArray['item_unit_price'] = $dataArray['item_rate'];
                $invoiceItemArray['item_discount'] = $dataArray['item_discount'];

                $invoiceArray[$arrayKey]['items'][] = $invoiceItemArray;
            }

            if ($errorflag === true) {

                $objPHPExcel->getActiveSheet()->SetCellValue('Q1', "Error Information");
                $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
                $objWriter->save($invoice_excel_dir_path);
                $this->setError($this->validationMessage['excelerror']);

                $resultArray = array("status" => "error", "excelurl" => $invoice_excel_url_path);
                return json_encode($resultArray);
            } else {

                foreach ($invoiceArray as $invoiceRow) {

                    $invoiceItemArray = array();
                    $invoiceTotalAmount = 0.00;

                    foreach ($invoiceRow['items'] as $invoiceInnerRow) {

                        $clientMasterItem = $this->get_row("select cm.item_id, cm.item_name, cm.unit_price, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, m.igst_tax_rate, m.csgt_tax_rate, m.sgst_tax_rate, m.cess_tax_rate, cm.item_unit, u.unit_id, u.unit_name, u.unit_code from " . $this->tableNames['client_master_item'] . " as cm, " . $this->tableNames['item'] . " as m, " . $this->tableNames['unit'] . " as u where 1=1 AND cm.item_category = m.item_id AND cm.item_unit = u.unit_id AND cm.item_name = '" . $invoiceInnerRow['item_name'] . "' && m.hsn_code = '" . $invoiceInnerRow['item_hsncode'] . "' AND cm.is_deleted='0' AND cm.status = '1' AND cm.added_by = '" . $_SESSION['user_detail']['user_id'] . "'");
                        if (!empty($clientMasterItem)) {

                            $itemUnitPrice = (float) $invoiceInnerRow['item_unit_price'];
                            $invoiceItemQuantity = (int) $invoiceInnerRow['item_quantity'];
                            $invoiceItemDiscount = (float) $invoiceInnerRow['item_discount'];

                            $invoiceItemTotal = round(($invoiceItemQuantity * $itemUnitPrice), 2);
                            $invoiceItemDiscountAmount = ($invoiceItemDiscount / 100) * $invoiceItemTotal;
                            $invoiceItemTaxableAmount = round(($invoiceItemTotal - $invoiceItemDiscountAmount), 2);

                            $invoiceItemTotalAmount = round($invoiceItemTaxableAmount, 2);
                            $invoiceTotalAmount += $invoiceItemTotalAmount;

                            $ItemArray = array(
                                "item_id" => $clientMasterItem->item_id,
                                "item_name" => $clientMasterItem->item_name,
                                "item_hsncode" => $clientMasterItem->hsn_code,
                                "item_quantity" => $invoiceItemQuantity,
                                "item_unit" => $clientMasterItem->unit_code,
                                "item_unit_price" => $itemUnitPrice,
                                "subtotal" => $invoiceItemTotal,
                                "discount" => $invoiceItemDiscount,
                                "taxable_subtotal" => $invoiceItemTaxableAmount,
                                "total" => $invoiceItemTotalAmount,
                                "status" => 1,
                                "added_by" => $_SESSION['user_detail']['user_id'],
                                "added_date" => date('Y-m-d H:i:s')
                            );

                            array_push($invoiceItemArray, $ItemArray);
                        }
                    }

                    if (!empty($invoiceItemArray) && count($invoiceItemArray) > 0) {

                        $InsertArray['reference_number'] = $invoiceRow['reference_number'];
                        $InsertArray['serial_number'] = $this->generateBillInvoiceNumber($this->sanitize($_SESSION['user_detail']['user_id']));
                        $InsertArray['company_name'] = $invoiceRow['company_name'];
                        $InsertArray['company_address'] = $invoiceRow['company_address'];
                        $InsertArray['company_state'] = $invoiceRow['company_state'];
                        $InsertArray['gstin_number'] = $invoiceRow['gstin_number'];
                        $InsertArray['invoice_date'] = $invoiceRow['invoice_date'];
                        $InsertArray['billing_name'] = $invoiceRow['billing_name'];
                        $InsertArray['billing_address'] = $invoiceRow['billing_address'];
                        $InsertArray['billing_state'] = $invoiceRow['billing_state'];
                        $InsertArray['billing_state_name'] = $invoiceRow['billing_state_name'];
                        $InsertArray['billing_gstin_number'] = $invoiceRow['billing_gstin_number'];
                        $InsertArray['shipping_name'] = $invoiceRow['shipping_name'];
                        $InsertArray['shipping_address'] = $invoiceRow['shipping_address'];
                        $InsertArray['shipping_state'] = $invoiceRow['shipping_state'];
                        $InsertArray['shipping_state_name'] = $invoiceRow['shipping_state_name'];
                        $InsertArray['shipping_gstin_number'] = $invoiceRow['shipping_gstin_number'];
                        $InsertArray['invoice_total_value'] = $invoiceTotalAmount;
                        $InsertArray['financial_year'] = $this->generateFinancialYear();
                        $InsertArray['status'] = 1;
                        $InsertArray['added_by'] = $_SESSION['user_detail']['user_id'];
                        $InsertArray['added_date'] = date('Y-m-d H:i:s');

                        if ($this->insert($this->tableNames['client_bos_invoice'], $InsertArray)) {

                            $insertid = $this->getInsertID();
                            $this->logMsg("New BOS Invoice Added. ID : " . $insertid . ".");

                            $processedInvoiceItemArray = array();
                            foreach ($invoiceItemArray as $itemArr) {

                                $itemArr['invoice_id'] = $insertid;
                                array_push($processedInvoiceItemArray, $itemArr);
                            }

                            if ($this->insertMultiple($this->tableNames['client_bos_invoice_item'], $processedInvoiceItemArray)) {

                                $iteminsertid = $this->getInsertID();
                                $this->logMsg("New BOS Invoice Item Added. ID : " . $iteminsertid . ".");
                            }
                        }
                    }
                }

                $this->setSuccess($this->validationMessage['invoiceadded']);
                return true;
            }
        }
    }

    /* upload client receipt voucher invoice */

    public function uploadClientRVInvoice() {

        $flag = true;
        $errorflag = false;
        $dataArray = array();
        $indexArray = array();
        $invoiceArray = array();
        $invoiceItemArray = array();

        if ($_FILES['invoice_xlsx']['name'] != '' && $_FILES['invoice_xlsx']['error'] == 0) {

            $invoice_excel = $this->imageUploads($_FILES['invoice_xlsx'], 'invoice-docs', 'upload', $this->allowExcelExt);
            if ($invoice_excel == FALSE) {
                return false;
            }

            $invoice_excel_dir_path = PROJECT_ROOT . UPLOAD_DIR . "/invoice-docs/" . $invoice_excel;
            $invoice_excel_url_path = PROJECT_URL . UPLOAD_DIR . "/invoice-docs/" . $invoice_excel;

            $objPHPExcel = PHPExcel_IOFactory::load($invoice_excel_dir_path);
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
			$sheetData = array_map('array_filter', $sheetData);
			$sheetData = array_filter($sheetData);

            foreach ($sheetData as $rowKey => $data) {

                if ($flag) {
                    $indexArray = $data;
                    $flag = false;
                    continue;
                }

                $currentItemError = array();
                $dataArray['reference_number'] = isset($data['A']) ? $data['A'] : '';
                $dataArray['invoice_date'] = isset($data['B']) ? $data['B'] : '';

                $supply_place = isset($data['C']) ? $data['C'] : '';
                if ($supply_place != '') {

                    $supply_state_data = $this->getStateDetailByStateCode($supply_place);
                    if ($supply_state_data['status'] === "success") {
                        $dataArray['supply_place'] = $supply_state_data['data']->state_id;
                    } else {

                        $errorflag = true;
                        array_push($currentItemError, "Invalid supply state code.");
                        $dataArray['supply_place'] = 'Invalid State';
                    }
                } else {
                    $dataArray['supply_place'] = $supply_place;
                }

                $dataArray['billing_name'] = isset($data['D']) ? $data['D'] : '';
                $dataArray['billing_address'] = isset($data['E']) ? $data['E'] : '';
                $billing_state = isset($data['F']) ? $data['F'] : '';

                if ($billing_state != '') {

                    $billing_state_data = $this->getStateDetailByStateCode($billing_state);
                    if ($billing_state_data['status'] === "success") {
                        $dataArray['billing_state'] = $billing_state_data['data']->state_id;
                        $dataArray['billing_state_name'] = $billing_state_data['data']->state_name;
                    } else {

                        $errorflag = true;
                        array_push($currentItemError, "Invalid billing state code.");
                        $dataArray['billing_state'] = 'Invalid State';
                    }
                } else {
                    $dataArray['billing_state'] = $billing_state;
                }

                $dataArray['billing_gstin_number'] = isset($data['G']) ? $data['G'] : '';
                $dataArray['shipping_name'] = isset($data['H']) ? $data['H'] : '';
                $dataArray['shipping_address'] = isset($data['I']) ? $data['I'] : '';
                $shipping_state = isset($data['J']) ? $data['J'] : '';

                if ($shipping_state != '') {

                    $shipping_state_data = $this->getStateDetailByStateCode($shipping_state);
                    if ($shipping_state_data['status'] === "success") {
                        $dataArray['shipping_state'] = $shipping_state_data['data']->state_id;
                        $dataArray['shipping_state_name'] = $shipping_state_data['data']->state_name;
                    } else {

                        $errorflag = true;
                        array_push($currentItemError, "Invalid shipping state code.");
                        $dataArray['shipping_state'] = 'Invalid State';
                    }
                } else {
                    $dataArray['shipping_state'] = $shipping_state;
                }

                $dataArray['shipping_gstin_number'] = isset($data['K']) ? $data['K'] : '';

                $item_name = isset($data['L']) ? trim($data['L']) : '';
                $item_hsn_code = isset($data['M']) ? trim($data['M']) : '';

                //if(!empty($item_hsn_code)) { $item_hsn_code = str_pad($item_hsn_code, 8, "0", STR_PAD_LEFT); }

                $dataArray['advance_value'] = isset($data['N']) ? $data['N'] : '';

                $is_tax_payable = isset($data['O']) ? $data['O'] : '';
                if ($is_tax_payable != '' && strtoupper($is_tax_payable) === 'Y') {
                    $dataArray['is_tax_payable'] = 1;
                } else if ($is_tax_payable != '' && strtoupper($is_tax_payable) === 'N') {
                    $dataArray['is_tax_payable'] = 0;
                } else {
                    $dataArray['is_tax_payable'] = $is_tax_payable;
                }

                if ($item_name != '' && $item_hsn_code != '') {

                    $checkClientMasterItem = $this->get_row("select cm.item_id, cm.item_name, cm.unit_price, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, m.igst_tax_rate, m.csgt_tax_rate, m.sgst_tax_rate, m.cess_tax_rate, cm.item_unit, u.unit_id, u.unit_name, u.unit_code from " . $this->tableNames['client_master_item'] . " as cm, " . $this->tableNames['item'] . " as m, " . $this->tableNames['unit'] . " as u where 1=1 AND cm.item_category = m.item_id AND cm.item_unit = u.unit_id AND cm.item_name = '" . $item_name . "' && m.hsn_code = '" . $item_hsn_code . "' AND cm.is_deleted='0' AND cm.status = '1' AND cm.added_by = '" . $this->sanitize($_SESSION['user_detail']['user_id']) . "'");

                    if (count($checkClientMasterItem) == 1) {

                        $dataArray['item_name'] = $item_name;
                        $dataArray['item_hsn_code'] = $item_hsn_code;
                    } else {

                        $errorflag = true;
                        array_push($currentItemError, "Item not exist with this hsn code.");
                        $dataArray['item_name'] = "#####Item not exist#####";
                        $dataArray['item_hsn_code'] = "#####HSN code not exist#####";
                    }
                } else {
                    $dataArray['item_name'] = $item_name;
                    $dataArray['item_hsn_code'] = $item_hsn_code;
                }

                $invoiceErrors = $this->validateClientInvoiceExcel($dataArray);
                if ($invoiceErrors !== true || !empty($currentItemError)) {

                    $errorflag = true;
                    if ($invoiceErrors === true) {
                        $invoiceErrors = array();
                    }
                    $invoiceErrors = array_merge($invoiceErrors, $currentItemError);
                    $invoiceErrors = implode(", ", $invoiceErrors);
                    $objPHPExcel->getActiveSheet()->SetCellValue('P' . $rowKey, $invoiceErrors);
                }

                /* get current user data */
                $dataCurrentUserArr = $this->getUserDetailsById($this->sanitize($_SESSION['user_detail']['user_id']));

                /* create invoice array */
                $arrayKey = $dataArray['reference_number'];

                $invoiceArray[$arrayKey]['reference_number'] = $dataArray['reference_number'];
                $invoiceArray[$arrayKey]['company_name'] = $dataCurrentUserArr['data']->kyc->name;
                $invoiceArray[$arrayKey]['company_address'] = $dataCurrentUserArr['data']->kyc->registered_address;
                $invoiceArray[$arrayKey]['company_state'] = $dataCurrentUserArr['data']->kyc->state_id;
                $invoiceArray[$arrayKey]['gstin_number'] = $dataCurrentUserArr['data']->kyc->gstin_number;
                $invoiceArray[$arrayKey]['invoice_date'] = $dataArray['invoice_date'];
                $invoiceArray[$arrayKey]['supply_place'] = $dataArray['supply_place'];
                $invoiceArray[$arrayKey]['is_tax_payable'] = $dataArray['is_tax_payable'];
                $invoiceArray[$arrayKey]['billing_name'] = $dataArray['billing_name'];
                $invoiceArray[$arrayKey]['billing_address'] = $dataArray['billing_address'];
                $invoiceArray[$arrayKey]['billing_state'] = $dataArray['billing_state'];
                $invoiceArray[$arrayKey]['billing_state_name'] = $dataArray['billing_state_name'];
                $invoiceArray[$arrayKey]['billing_gstin_number'] = $dataArray['billing_gstin_number'];
                $invoiceArray[$arrayKey]['shipping_name'] = $dataArray['shipping_name'];
                $invoiceArray[$arrayKey]['shipping_address'] = $dataArray['shipping_address'];
                $invoiceArray[$arrayKey]['shipping_state'] = $dataArray['shipping_state'];
                $invoiceArray[$arrayKey]['shipping_state_name'] = $dataArray['shipping_state_name'];
                $invoiceArray[$arrayKey]['shipping_gstin_number'] = $dataArray['shipping_gstin_number'];

                //items
                $invoiceItemArray['item_name'] = $dataArray['item_name'];
                $invoiceItemArray['item_hsncode'] = $dataArray['item_hsn_code'];
                $invoiceItemArray['advance_value'] = $dataArray['advance_value'];

                $invoiceArray[$arrayKey]['items'][] = $invoiceItemArray;
            }

            if ($errorflag === true) {

                $objPHPExcel->getActiveSheet()->SetCellValue('P1', "Error Information");
                $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
                $objWriter->save($invoice_excel_dir_path);
                $this->setError($this->validationMessage['excelerror']);

                $resultArray = array("status" => "error", "excelurl" => $invoice_excel_url_path);
                return json_encode($resultArray);
            } else {

                foreach ($invoiceArray as $invoiceRow) {

                    $invoiceItemArray = array();
                    $invoiceTotalAmount = 0.00;

                    foreach ($invoiceRow['items'] as $invoiceInnerRow) {

                        $clientMasterItem = $this->get_row("select cm.item_id, cm.item_name, cm.unit_price, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, m.igst_tax_rate, m.csgt_tax_rate, m.sgst_tax_rate, m.cess_tax_rate, cm.item_unit, u.unit_id, u.unit_name, u.unit_code from " . $this->tableNames['client_master_item'] . " as cm, " . $this->tableNames['item'] . " as m, " . $this->tableNames['unit'] . " as u where 1=1 AND cm.item_category = m.item_id AND cm.item_unit = u.unit_id AND cm.item_name = '" . $invoiceInnerRow['item_name'] . "' && m.hsn_code = '" . $invoiceInnerRow['item_hsncode'] . "' AND cm.is_deleted='0' AND cm.status = '1' AND cm.added_by = '" . $_SESSION['user_detail']['user_id'] . "'");
                        if (!empty($clientMasterItem)) {

                            $advanceValue = (float) $invoiceInnerRow['advance_value'];
                            $invoiceItemTaxableAmount = round($advanceValue, 2);

                            if ($invoiceRow['supply_place'] === $invoiceRow['shipping_state']) {

                                $itemCSGTTax = (float) $clientMasterItem->csgt_tax_rate;
                                $itemSGSTTax = (float) $clientMasterItem->sgst_tax_rate;
                                $itemIGSTTax = 0.00;
                                $itemCESSTax = (float) $clientMasterItem->cess_tax_rate;

                                $invoiceItemCSGTTaxAmount = round((($itemCSGTTax / 100) * $invoiceItemTaxableAmount), 2);
                                $invoiceItemSGSTTaxAmount = round((($itemSGSTTax / 100) * $invoiceItemTaxableAmount), 2);
                                $invoiceItemIGSTTaxAmount = 0.00;
                                $invoiceItemCESSTaxAmount = round((($itemCESSTax / 100) * $invoiceItemTaxableAmount), 2);
                            } else {

                                $itemCSGTTax = 0.00;
                                $itemSGSTTax = 0.00;
                                $itemIGSTTax = (float) $clientMasterItem->igst_tax_rate;
                                $itemCESSTax = (float) $clientMasterItem->cess_tax_rate;

                                $invoiceItemCSGTTaxAmount = 0.00;
                                $invoiceItemSGSTTaxAmount = 0.00;
                                $invoiceItemIGSTTaxAmount = round((($itemIGSTTax / 100) * $invoiceItemTaxableAmount), 2);
                                $invoiceItemCESSTaxAmount = round((($itemCESSTax / 100) * $invoiceItemTaxableAmount), 2);
                            }

                            $invoiceItemTotalAmount = round(($invoiceItemTaxableAmount + $invoiceItemCSGTTaxAmount + $invoiceItemSGSTTaxAmount + $invoiceItemIGSTTaxAmount + $invoiceItemCESSTaxAmount), 2);
                            $invoiceTotalAmount += $invoiceItemTotalAmount;

                            $ItemArray = array(
                                "item_id" => $clientMasterItem->item_id,
                                "item_name" => $clientMasterItem->item_name,
                                "item_hsncode" => $clientMasterItem->hsn_code,
                                "taxable_subtotal" => $invoiceItemTaxableAmount,
                                "cgst_rate" => $itemCSGTTax,
                                "cgst_amount" => $invoiceItemCSGTTaxAmount,
                                "sgst_rate" => $itemSGSTTax,
                                "sgst_amount" => $invoiceItemSGSTTaxAmount,
                                "igst_rate" => $itemIGSTTax,
                                "igst_amount" => $invoiceItemIGSTTaxAmount,
                                "cess_rate" => $itemCESSTax,
                                "cess_amount" => $invoiceItemCESSTaxAmount,
                                "total" => $invoiceItemTotalAmount,
                                "status" => 1,
                                "added_by" => $_SESSION['user_detail']['user_id'],
                                "added_date" => date('Y-m-d H:i:s')
                            );

                            array_push($invoiceItemArray, $ItemArray);
                        }
                    }

                    if (!empty($invoiceItemArray) && count($invoiceItemArray) > 0) {

                        $InsertArray['reference_number'] = $invoiceRow['reference_number'];
                        $InsertArray['serial_number'] = $this->generateRVInvoiceNumber($this->sanitize($_SESSION['user_detail']['user_id']));
                        $InsertArray['company_name'] = $invoiceRow['company_name'];
                        $InsertArray['company_address'] = $invoiceRow['company_address'];
                        $InsertArray['company_state'] = $invoiceRow['company_state'];
                        $InsertArray['gstin_number'] = $invoiceRow['gstin_number'];
                        $InsertArray['invoice_date'] = $invoiceRow['invoice_date'];
                        $InsertArray['is_tax_payable'] = $invoiceRow['is_tax_payable'];
                        $InsertArray['supply_place'] = $invoiceRow['supply_place'];
                        $InsertArray['billing_name'] = $invoiceRow['billing_name'];
                        $InsertArray['billing_address'] = $invoiceRow['billing_address'];
                        $InsertArray['billing_state'] = $invoiceRow['billing_state'];
                        $InsertArray['billing_state_name'] = $invoiceRow['billing_state_name'];
                        $InsertArray['billing_gstin_number'] = $invoiceRow['billing_gstin_number'];
                        $InsertArray['shipping_name'] = $invoiceRow['shipping_name'];
                        $InsertArray['shipping_address'] = $invoiceRow['shipping_address'];
                        $InsertArray['shipping_state'] = $invoiceRow['shipping_state'];
                        $InsertArray['shipping_state_name'] = $invoiceRow['shipping_state_name'];
                        $InsertArray['shipping_gstin_number'] = $invoiceRow['shipping_gstin_number'];
                        $InsertArray['invoice_total_value'] = $invoiceTotalAmount;
                        $InsertArray['financial_year'] = $this->generateFinancialYear();
                        $InsertArray['status'] = 1;
                        $InsertArray['added_by'] = $_SESSION['user_detail']['user_id'];
                        $InsertArray['added_date'] = date('Y-m-d H:i:s');

                        if ($this->insert($this->tableNames['client_rv_invoice'], $InsertArray)) {

                            $insertid = $this->getInsertID();
                            $this->logMsg("New RV Invoice Added. ID : " . $insertid . ".");

                            $processedInvoiceItemArray = array();
                            foreach ($invoiceItemArray as $itemArr) {

                                $itemArr['invoice_id'] = $insertid;
                                array_push($processedInvoiceItemArray, $itemArr);
                            }

                            if ($this->insertMultiple($this->tableNames['client_rv_invoice_item'], $processedInvoiceItemArray)) {

                                $iteminsertid = $this->getInsertID();
                                $this->logMsg("New RV Invoice Item Added. ID : " . $iteminsertid . ".");
                            }
                        }
                    }
                }

                $this->setSuccess($this->validationMessage['invoiceadded']);
                return true;
            }
        }
    }

    /* upload client refund voucher invoice */

    public function uploadClientRFInvoice() {

        $flag = true;
        $errorflag = false;
        $dataArray = array();
        $indexArray = array();
        $invoiceArray = array();
        $invoiceItemArray = array();

        if ($_FILES['invoice_xlsx']['name'] != '' && $_FILES['invoice_xlsx']['error'] == 0) {

            $invoice_excel = $this->imageUploads($_FILES['invoice_xlsx'], 'invoice-docs', 'upload', $this->allowExcelExt);
            if ($invoice_excel == FALSE) {
                return false;
            }

            $invoice_excel_dir_path = PROJECT_ROOT . UPLOAD_DIR . "/invoice-docs/" . $invoice_excel;
            $invoice_excel_url_path = PROJECT_URL . UPLOAD_DIR . "/invoice-docs/" . $invoice_excel;

            $objPHPExcel = PHPExcel_IOFactory::load($invoice_excel_dir_path);
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
			$sheetData = array_map('array_filter', $sheetData);
			$sheetData = array_filter($sheetData);

            foreach ($sheetData as $rowKey => $data) {

                if ($flag) {
                    $indexArray = $data;
                    $flag = false;
                    continue;
                }

                $currentItemError = array();
                $dataArray['reference_number'] = isset($data['A']) ? $data['A'] : '';
                $dataArray['invoice_date'] = isset($data['B']) ? $data['B'] : '';

                $currentFinancialYear = $this->generateFinancialYear();
                $receipt_voucher_number = isset($data['C']) ? $data['C'] : '';
                $dataReceiptVoucherArrs = $this->get_row("select serial_number, invoice_date, supply_place, shipping_state, is_canceled from " . $this->tableNames['client_rv_invoice'] . " where status='1' and is_deleted='0' AND financial_year = '" . $currentFinancialYear . "' AND added_by = " . $this->sanitize($_SESSION['user_detail']['user_id']) . " AND serial_number = '" . $receipt_voucher_number . "'");

                if ($dataReceiptVoucherArrs) {
                    $dataArray['receipt_voucher_number'] = isset($dataReceiptVoucherArrs->serial_number) ? $dataReceiptVoucherArrs->serial_number : '';
                    $dataArray['receipt_voucher_date'] = isset($dataReceiptVoucherArrs->invoice_date) ? $dataReceiptVoucherArrs->invoice_date : '0000-00-00';
                    $dataArray['supply_place'] = isset($dataReceiptVoucherArrs->supply_place) ? $dataReceiptVoucherArrs->supply_place : '';
                    $dataArray['shipping_state'] = isset($dataReceiptVoucherArrs->shipping_state) ? $dataReceiptVoucherArrs->shipping_state : '';
                } else {
                    $dataArray['receipt_voucher_number'] = $receipt_voucher_number;
                    $dataArray['receipt_voucher_date'] = '0000-00-00';
                    $dataArray['supply_place'] = 'Invalid Supply State';
                    $dataArray['shipping_state'] = 'Invalid Shipping State';

                    $errorflag = true;
                    array_push($currentItemError, "Invalid receipt voucher number.");
                    $dataArray['receipt_voucher_number'] = 'Invalid receipt voucher number.';

                    array_push($currentItemError, "Invalid receipt voucher date.");
                    $dataArray['receipt_voucher_date'] = 'Invalid receipt voucher date.';
                }

                $item_name = isset($data['D']) ? trim($data['D']) : '';
                $item_hsn_code = isset($data['E']) ? trim($data['E']) : '';

                //if(!empty($item_hsn_code)) { $item_hsn_code = str_pad($item_hsn_code, 8, "0", STR_PAD_LEFT); }

                $dataArray['refund_value'] = isset($data['F']) ? $data['F'] : '';

                $is_tax_payable = isset($data['G']) ? $data['G'] : '';
                if ($is_tax_payable != '' && strtoupper($is_tax_payable) === 'Y') {
                    $dataArray['is_tax_payable'] = 1;
                } else if ($is_tax_payable != '' && strtoupper($is_tax_payable) === 'N') {
                    $dataArray['is_tax_payable'] = 0;
                } else {
                    $dataArray['is_tax_payable'] = $is_tax_payable;
                }

                if ($item_name != '' && $item_hsn_code != '') {

                    $checkClientMasterItem = $this->get_row("select cm.item_id, cm.item_name, cm.unit_price, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, m.igst_tax_rate, m.csgt_tax_rate, m.sgst_tax_rate, m.cess_tax_rate, cm.item_unit, u.unit_id, u.unit_name, u.unit_code from " . $this->tableNames['client_master_item'] . " as cm, " . $this->tableNames['item'] . " as m, " . $this->tableNames['unit'] . " as u where 1=1 AND cm.item_category = m.item_id AND cm.item_unit = u.unit_id AND cm.item_name = '" . $item_name . "' && m.hsn_code = '" . $item_hsn_code . "' AND cm.is_deleted='0' AND cm.status = '1' AND cm.added_by = '" . $this->sanitize($_SESSION['user_detail']['user_id']) . "'");

                    if (count($checkClientMasterItem) == 1) {

                        $dataArray['item_name'] = $item_name;
                        $dataArray['item_hsn_code'] = $item_hsn_code;
                    } else {

                        $errorflag = true;
                        array_push($currentItemError, "Item not exist with this hsn code.");
                        $dataArray['item_name'] = "#####Item not exist#####";
                        $dataArray['item_hsn_code'] = "#####HSN code not exist#####";
                    }
                } else {
                    $dataArray['item_name'] = $item_name;
                    $dataArray['item_hsn_code'] = $item_hsn_code;
                }

                $invoiceErrors = $this->validateClientInvoiceExcel($dataArray);
                if ($invoiceErrors !== true || !empty($currentItemError)) {

                    $errorflag = true;
                    if ($invoiceErrors === true) {
                        $invoiceErrors = array();
                    }
                    $invoiceErrors = array_merge($invoiceErrors, $currentItemError);
                    $invoiceErrors = implode(", ", $invoiceErrors);
                    $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowKey, $invoiceErrors);
                }

                /* get current user data */
                $dataCurrentUserArr = $this->getUserDetailsById($this->sanitize($_SESSION['user_detail']['user_id']));

                /* create invoice array */
                $arrayKey = $dataArray['reference_number'];

                $invoiceArray[$arrayKey]['reference_number'] = $dataArray['reference_number'];
                $invoiceArray[$arrayKey]['invoice_date'] = $dataArray['invoice_date'];
                $invoiceArray[$arrayKey]['is_tax_payable'] = $dataArray['is_tax_payable'];
                $invoiceArray[$arrayKey]['receipt_voucher_number'] = $dataArray['receipt_voucher_number'];
                $invoiceArray[$arrayKey]['receipt_voucher_date'] = $dataArray['receipt_voucher_date'];
                $invoiceArray[$arrayKey]['supply_place'] = $dataArray['supply_place'];
                $invoiceArray[$arrayKey]['shipping_state'] = $dataArray['shipping_state'];

                //items
                $invoiceItemArray['item_name'] = $dataArray['item_name'];
                $invoiceItemArray['item_hsncode'] = $dataArray['item_hsn_code'];
                $invoiceItemArray['refund_value'] = $dataArray['refund_value'];

                $invoiceArray[$arrayKey]['items'][] = $invoiceItemArray;
            }

            if ($errorflag === true) {

                $objPHPExcel->getActiveSheet()->SetCellValue('H1', "Error Information");
                $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
                $objWriter->save($invoice_excel_dir_path);
                $this->setError($this->validationMessage['excelerror']);

                $resultArray = array("status" => "error", "excelurl" => $invoice_excel_url_path);
                return json_encode($resultArray);
            } else {

                foreach ($invoiceArray as $invoiceRow) {

                    $invoiceItemArray = array();
                    $invoiceTotalAmount = 0.00;

                    foreach ($invoiceRow['items'] as $invoiceInnerRow) {

                        $clientMasterItem = $this->get_row("select cm.item_id, cm.item_name, cm.unit_price, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, m.igst_tax_rate, m.csgt_tax_rate, m.sgst_tax_rate, m.cess_tax_rate, cm.item_unit, u.unit_id, u.unit_name, u.unit_code from " . $this->tableNames['client_master_item'] . " as cm, " . $this->tableNames['item'] . " as m, " . $this->tableNames['unit'] . " as u where 1=1 AND cm.item_category = m.item_id AND cm.item_unit = u.unit_id AND cm.item_name = '" . $invoiceInnerRow['item_name'] . "' && m.hsn_code = '" . $invoiceInnerRow['item_hsncode'] . "' AND cm.is_deleted='0' AND cm.status = '1' AND cm.added_by = '" . $_SESSION['user_detail']['user_id'] . "'");
                        if (!empty($clientMasterItem)) {

                            $refundValue = (float) $invoiceInnerRow['refund_value'];
                            $invoiceItemTaxableAmount = round($refundValue, 2);

                            if ($invoiceRow['supply_place'] === $invoiceRow['shipping_state']) {

                                $itemCSGTTax = (float) $clientMasterItem->csgt_tax_rate;
                                $itemSGSTTax = (float) $clientMasterItem->sgst_tax_rate;
                                $itemIGSTTax = 0.00;
                                $itemCESSTax = (float) $clientMasterItem->cess_tax_rate;

                                $invoiceItemCSGTTaxAmount = round((($itemCSGTTax / 100) * $invoiceItemTaxableAmount), 2);
                                $invoiceItemSGSTTaxAmount = round((($itemSGSTTax / 100) * $invoiceItemTaxableAmount), 2);
                                $invoiceItemIGSTTaxAmount = 0.00;
                                $invoiceItemCESSTaxAmount = round((($itemCESSTax / 100) * $invoiceItemTaxableAmount), 2);
                            } else {

                                $itemCSGTTax = 0.00;
                                $itemSGSTTax = 0.00;
                                $itemIGSTTax = (float) $clientMasterItem->igst_tax_rate;
                                $itemCESSTax = (float) $clientMasterItem->cess_tax_rate;

                                $invoiceItemCSGTTaxAmount = 0.00;
                                $invoiceItemSGSTTaxAmount = 0.00;
                                $invoiceItemIGSTTaxAmount = round((($itemIGSTTax / 100) * $invoiceItemTaxableAmount), 2);
                                $invoiceItemCESSTaxAmount = round((($itemCESSTax / 100) * $invoiceItemTaxableAmount), 2);
                            }

                            $invoiceItemTotalAmount = round(($invoiceItemTaxableAmount + $invoiceItemCSGTTaxAmount + $invoiceItemSGSTTaxAmount + $invoiceItemIGSTTaxAmount + $invoiceItemCESSTaxAmount), 2);
                            $invoiceTotalAmount += $invoiceItemTotalAmount;

                            $ItemArray = array(
                                "item_id" => $clientMasterItem->item_id,
                                "item_name" => $clientMasterItem->item_name,
                                "item_hsncode" => $clientMasterItem->hsn_code,
                                "taxable_subtotal" => $invoiceItemTaxableAmount,
                                "cgst_rate" => $itemCSGTTax,
                                "cgst_amount" => $invoiceItemCSGTTaxAmount,
                                "sgst_rate" => $itemSGSTTax,
                                "sgst_amount" => $invoiceItemSGSTTaxAmount,
                                "igst_rate" => $itemIGSTTax,
                                "igst_amount" => $invoiceItemIGSTTaxAmount,
                                "cess_rate" => $itemCESSTax,
                                "cess_amount" => $invoiceItemCESSTaxAmount,
                                "total" => $invoiceItemTotalAmount,
                                "status" => 1,
                                "added_by" => $_SESSION['user_detail']['user_id'],
                                "added_date" => date('Y-m-d H:i:s')
                            );

                            array_push($invoiceItemArray, $ItemArray);
                        }
                    }

                    if (!empty($invoiceItemArray) && count($invoiceItemArray) > 0) {

                        $InsertArray['reference_number'] = $invoiceRow['reference_number'];
                        $InsertArray['serial_number'] = $this->generateRFInvoiceNumber($this->sanitize($_SESSION['user_detail']['user_id']));
                        $InsertArray['receipt_voucher_number'] = $invoiceRow['receipt_voucher_number'];
                        $InsertArray['receipt_voucher_date'] = $invoiceRow['receipt_voucher_date'];
                        $InsertArray['invoice_date'] = $invoiceRow['invoice_date'];
                        $InsertArray['is_tax_payable'] = $invoiceRow['is_tax_payable'];
                        $InsertArray['invoice_total_value'] = $invoiceTotalAmount;
                        $InsertArray['financial_year'] = $this->generateFinancialYear();
                        $InsertArray['status'] = 1;
                        $InsertArray['added_by'] = $_SESSION['user_detail']['user_id'];
                        $InsertArray['added_date'] = date('Y-m-d H:i:s');

                        if ($this->insert($this->tableNames['client_rf_invoice'], $InsertArray)) {

                            $insertid = $this->getInsertID();
                            $this->logMsg("New RF Invoice Added. ID : " . $insertid . ".");

                            $processedInvoiceItemArray = array();
                            foreach ($invoiceItemArray as $itemArr) {

                                $itemArr['invoice_id'] = $insertid;
                                array_push($processedInvoiceItemArray, $itemArr);
                            }

                            if ($this->insertMultiple($this->tableNames['client_rf_invoice_item'], $processedInvoiceItemArray)) {

                                $iteminsertid = $this->getInsertID();
                                $this->logMsg("New RF Invoice Item Added. ID : " . $iteminsertid . ".");
                            }
                        }
                    }
                }

                $this->setSuccess($this->validationMessage['invoiceadded']);
                return true;
            }
        }
    }

    /* upload client payment voucher invoice */

    public function uploadClientPVInvoice() {

        $flag = true;
        $errorflag = false;
        $dataArray = array();
        $indexArray = array();
        $invoiceArray = array();
        $invoiceItemArray = array();

        if ($_FILES['invoice_xlsx']['name'] != '' && $_FILES['invoice_xlsx']['error'] == 0) {

            $invoice_excel = $this->imageUploads($_FILES['invoice_xlsx'], 'invoice-docs', 'upload', $this->allowExcelExt);
            if ($invoice_excel == FALSE) {
                return false;
            }

            $invoice_excel_dir_path = PROJECT_ROOT . UPLOAD_DIR . "/invoice-docs/" . $invoice_excel;
            $invoice_excel_url_path = PROJECT_URL . UPLOAD_DIR . "/invoice-docs/" . $invoice_excel;

            $objPHPExcel = PHPExcel_IOFactory::load($invoice_excel_dir_path);
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
			$sheetData = array_map('array_filter', $sheetData);
			$sheetData = array_filter($sheetData);

            foreach ($sheetData as $rowKey => $data) {

                if ($flag) {
                    $indexArray = $data;
                    $flag = false;
                    continue;
                }

                $currentItemError = array();
                $dataArray['reference_number'] = isset($data['A']) ? $data['A'] : '';
                $dataArray['invoice_date'] = isset($data['B']) ? $data['B'] : '';

                $supply_place = isset($data['C']) ? $data['C'] : '';
                if ($supply_place != '') {

                    $supply_state_data = $this->getStateDetailByStateCode($supply_place);
                    if ($supply_state_data['status'] === "success") {
                        $dataArray['supply_place'] = $supply_state_data['data']->state_id;
                    } else {

                        $errorflag = true;
                        array_push($currentItemError, "Invalid supply state code.");
                        $dataArray['supply_place'] = 'Invalid State';
                    }
                } else {
                    $dataArray['supply_place'] = $supply_place;
                }

                $dataArray['billing_name'] = isset($data['D']) ? $data['D'] : '';
                $dataArray['billing_address'] = isset($data['E']) ? $data['E'] : '';
                $billing_state = isset($data['F']) ? $data['F'] : '';

                if ($billing_state != '') {

                    $billing_state_data = $this->getStateDetailByStateCode($billing_state);
                    if ($billing_state_data['status'] === "success") {
                        $dataArray['billing_state'] = $billing_state_data['data']->state_id;
                        $dataArray['billing_state_name'] = $billing_state_data['data']->state_name;
                    } else {

                        $errorflag = true;
                        array_push($currentItemError, "Invalid billing state code.");
                        $dataArray['billing_state'] = 'Invalid State';
                    }
                } else {
                    $dataArray['billing_state'] = $billing_state;
                }

                $dataArray['billing_gstin_number'] = isset($data['G']) ? $data['G'] : '';
                $dataArray['shipping_name'] = isset($data['H']) ? $data['H'] : '';
                $dataArray['shipping_address'] = isset($data['I']) ? $data['I'] : '';
                $shipping_state = isset($data['J']) ? $data['J'] : '';

                if ($shipping_state != '') {

                    $shipping_state_data = $this->getStateDetailByStateCode($shipping_state);
                    if ($shipping_state_data['status'] === "success") {
                        $dataArray['shipping_state'] = $shipping_state_data['data']->state_id;
                        $dataArray['shipping_state_name'] = $shipping_state_data['data']->state_name;
                    } else {

                        $errorflag = true;
                        array_push($currentItemError, "Invalid shipping state code.");
                        $dataArray['shipping_state'] = 'Invalid State';
                    }
                } else {
                    $dataArray['shipping_state'] = $shipping_state;
                }

                $dataArray['shipping_gstin_number'] = isset($data['K']) ? $data['K'] : '';

                $item_name = isset($data['L']) ? trim($data['L']) : '';
                $item_hsn_code = isset($data['M']) ? trim($data['M']) : '';

                //if(!empty($item_hsn_code)) { $item_hsn_code = str_pad($item_hsn_code, 8, "0", STR_PAD_LEFT); }

                $dataArray['amount_paid'] = isset($data['N']) ? $data['N'] : '';

                if ($item_name != '' && $item_hsn_code != '') {

                    $checkClientMasterItem = $this->get_row("select cm.item_id, cm.item_name, cm.unit_price, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, m.igst_tax_rate, m.csgt_tax_rate, m.sgst_tax_rate, m.cess_tax_rate, cm.item_unit, u.unit_id, u.unit_name, u.unit_code from " . $this->tableNames['client_master_item'] . " as cm, " . $this->tableNames['item'] . " as m, " . $this->tableNames['unit'] . " as u where 1=1 AND cm.item_category = m.item_id AND cm.item_unit = u.unit_id AND cm.item_name = '" . $item_name . "' && m.hsn_code = '" . $item_hsn_code . "' AND cm.is_deleted='0' AND cm.status = '1' AND cm.added_by = '" . $this->sanitize($_SESSION['user_detail']['user_id']) . "'");

                    if (count($checkClientMasterItem) == 1) {

                        $dataArray['item_name'] = $item_name;
                        $dataArray['item_hsn_code'] = $item_hsn_code;
                    } else {

                        $errorflag = true;
                        array_push($currentItemError, "Item not exist with this hsn code.");
                        $dataArray['item_name'] = "#####Item not exist#####";
                        $dataArray['item_hsn_code'] = "#####HSN code not exist#####";
                    }
                } else {
                    $dataArray['item_name'] = $item_name;
                    $dataArray['item_hsn_code'] = $item_hsn_code;
                }

                $invoiceErrors = $this->validateClientInvoiceExcel($dataArray);
                if ($invoiceErrors !== true || !empty($currentItemError)) {

                    $errorflag = true;
                    if ($invoiceErrors === true) {
                        $invoiceErrors = array();
                    }
                    $invoiceErrors = array_merge($invoiceErrors, $currentItemError);
                    $invoiceErrors = implode(", ", $invoiceErrors);
                    $objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowKey, $invoiceErrors);
                }

                /* get current user data */
                $dataCurrentUserArr = $this->getUserDetailsById($this->sanitize($_SESSION['user_detail']['user_id']));

                /* create invoice array */
                $arrayKey = $dataArray['reference_number'];

                $invoiceArray[$arrayKey]['reference_number'] = $dataArray['reference_number'];
                $invoiceArray[$arrayKey]['company_name'] = $dataCurrentUserArr['data']->kyc->name;
                $invoiceArray[$arrayKey]['company_address'] = $dataCurrentUserArr['data']->kyc->registered_address;
                $invoiceArray[$arrayKey]['company_state'] = $dataCurrentUserArr['data']->kyc->state_id;
                $invoiceArray[$arrayKey]['gstin_number'] = $dataCurrentUserArr['data']->kyc->gstin_number;
                $invoiceArray[$arrayKey]['invoice_date'] = $dataArray['invoice_date'];
                $invoiceArray[$arrayKey]['supply_place'] = $dataArray['supply_place'];
                $invoiceArray[$arrayKey]['billing_name'] = $dataArray['billing_name'];
                $invoiceArray[$arrayKey]['billing_address'] = $dataArray['billing_address'];
                $invoiceArray[$arrayKey]['billing_state'] = $dataArray['billing_state'];
                $invoiceArray[$arrayKey]['billing_state_name'] = $dataArray['billing_state_name'];
                $invoiceArray[$arrayKey]['billing_gstin_number'] = $dataArray['billing_gstin_number'];
                $invoiceArray[$arrayKey]['shipping_name'] = $dataArray['shipping_name'];
                $invoiceArray[$arrayKey]['shipping_address'] = $dataArray['shipping_address'];
                $invoiceArray[$arrayKey]['shipping_state'] = $dataArray['shipping_state'];
                $invoiceArray[$arrayKey]['shipping_state_name'] = $dataArray['shipping_state_name'];
                $invoiceArray[$arrayKey]['shipping_gstin_number'] = $dataArray['shipping_gstin_number'];

                //items
                $invoiceItemArray['item_name'] = $dataArray['item_name'];
                $invoiceItemArray['item_hsncode'] = $dataArray['item_hsn_code'];
                $invoiceItemArray['amount_paid'] = $dataArray['amount_paid'];

                $invoiceArray[$arrayKey]['items'][] = $invoiceItemArray;
            }

            if ($errorflag === true) {

                $objPHPExcel->getActiveSheet()->SetCellValue('O1', "Error Information");
                $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
                $objWriter->save($invoice_excel_dir_path);
                $this->setError($this->validationMessage['excelerror']);

                $resultArray = array("status" => "error", "excelurl" => $invoice_excel_url_path);
                return json_encode($resultArray);
            } else {

                foreach ($invoiceArray as $invoiceRow) {

                    $invoiceItemArray = array();
                    $invoiceTotalAmount = 0.00;

                    foreach ($invoiceRow['items'] as $invoiceInnerRow) {

                        $clientMasterItem = $this->get_row("select cm.item_id, cm.item_name, cm.unit_price, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, m.igst_tax_rate, m.csgt_tax_rate, m.sgst_tax_rate, m.cess_tax_rate, cm.item_unit, u.unit_id, u.unit_name, u.unit_code from " . $this->tableNames['client_master_item'] . " as cm, " . $this->tableNames['item'] . " as m, " . $this->tableNames['unit'] . " as u where 1=1 AND cm.item_category = m.item_id AND cm.item_unit = u.unit_id AND cm.item_name = '" . $invoiceInnerRow['item_name'] . "' && m.hsn_code = '" . $invoiceInnerRow['item_hsncode'] . "' AND cm.is_deleted='0' AND cm.status = '1' AND cm.added_by = '" . $_SESSION['user_detail']['user_id'] . "'");
                        if (!empty($clientMasterItem)) {

                            $amountPaid = (float) $invoiceInnerRow['amount_paid'];
                            $invoiceItemTaxableAmount = round($amountPaid, 2);

                            if ($invoiceRow['supply_place'] === $invoiceRow['shipping_state']) {

                                $itemCSGTTax = (float) $clientMasterItem->csgt_tax_rate;
                                $itemSGSTTax = (float) $clientMasterItem->sgst_tax_rate;
                                $itemIGSTTax = 0.00;
                                $itemCESSTax = (float) $clientMasterItem->cess_tax_rate;

                                $invoiceItemCSGTTaxAmount = round((($itemCSGTTax / 100) * $invoiceItemTaxableAmount), 2);
                                $invoiceItemSGSTTaxAmount = round((($itemSGSTTax / 100) * $invoiceItemTaxableAmount), 2);
                                $invoiceItemIGSTTaxAmount = 0.00;
                                $invoiceItemCESSTaxAmount = round((($itemCESSTax / 100) * $invoiceItemTaxableAmount), 2);
                            } else {

                                $itemCSGTTax = 0.00;
                                $itemSGSTTax = 0.00;
                                $itemIGSTTax = (float) $clientMasterItem->igst_tax_rate;
                                $itemCESSTax = (float) $clientMasterItem->cess_tax_rate;

                                $invoiceItemCSGTTaxAmount = 0.00;
                                $invoiceItemSGSTTaxAmount = 0.00;
                                $invoiceItemIGSTTaxAmount = round((($itemIGSTTax / 100) * $invoiceItemTaxableAmount), 2);
                                $invoiceItemCESSTaxAmount = round((($itemCESSTax / 100) * $invoiceItemTaxableAmount), 2);
                            }

                            $invoiceItemTotalAmount = round(($invoiceItemTaxableAmount + $invoiceItemCSGTTaxAmount + $invoiceItemSGSTTaxAmount + $invoiceItemIGSTTaxAmount + $invoiceItemCESSTaxAmount), 2);
                            $invoiceTotalAmount += $invoiceItemTotalAmount;

                            $ItemArray = array(
                                "item_id" => $clientMasterItem->item_id,
                                "item_name" => $clientMasterItem->item_name,
                                "item_hsncode" => $clientMasterItem->hsn_code,
                                "taxable_subtotal" => $invoiceItemTaxableAmount,
                                "cgst_rate" => $itemCSGTTax,
                                "cgst_amount" => $invoiceItemCSGTTaxAmount,
                                "sgst_rate" => $itemSGSTTax,
                                "sgst_amount" => $invoiceItemSGSTTaxAmount,
                                "igst_rate" => $itemIGSTTax,
                                "igst_amount" => $invoiceItemIGSTTaxAmount,
                                "cess_rate" => $itemCESSTax,
                                "cess_amount" => $invoiceItemCESSTaxAmount,
                                "total" => $invoiceItemTotalAmount,
                                "status" => 1,
                                "added_by" => $_SESSION['user_detail']['user_id'],
                                "added_date" => date('Y-m-d H:i:s')
                            );

                            array_push($invoiceItemArray, $ItemArray);
                        }
                    }

                    if (!empty($invoiceItemArray) && count($invoiceItemArray) > 0) {

                        $InsertArray['reference_number'] = $invoiceRow['reference_number'];
                        $InsertArray['serial_number'] = $this->generatePVInvoiceNumber($this->sanitize($_SESSION['user_detail']['user_id']));
                        $InsertArray['company_name'] = $invoiceRow['company_name'];
                        $InsertArray['company_address'] = $invoiceRow['company_address'];
                        $InsertArray['company_state'] = $invoiceRow['company_state'];
                        $InsertArray['gstin_number'] = $invoiceRow['gstin_number'];
                        $InsertArray['invoice_date'] = $invoiceRow['invoice_date'];
                        $InsertArray['supply_place'] = $invoiceRow['supply_place'];
                        $InsertArray['billing_name'] = $invoiceRow['billing_name'];
                        $InsertArray['billing_address'] = $invoiceRow['billing_address'];
                        $InsertArray['billing_state'] = $invoiceRow['billing_state'];
                        $InsertArray['billing_state_name'] = $invoiceRow['billing_state_name'];
                        $InsertArray['billing_gstin_number'] = $invoiceRow['billing_gstin_number'];
                        $InsertArray['shipping_name'] = $invoiceRow['shipping_name'];
                        $InsertArray['shipping_address'] = $invoiceRow['shipping_address'];
                        $InsertArray['shipping_state'] = $invoiceRow['shipping_state'];
                        $InsertArray['shipping_state_name'] = $invoiceRow['shipping_state_name'];
                        $InsertArray['shipping_gstin_number'] = $invoiceRow['shipping_gstin_number'];
                        $InsertArray['invoice_total_value'] = $invoiceTotalAmount;
                        $InsertArray['financial_year'] = $this->generateFinancialYear();
                        $InsertArray['status'] = 1;
                        $InsertArray['added_by'] = $_SESSION['user_detail']['user_id'];
                        $InsertArray['added_date'] = date('Y-m-d H:i:s');

                        if ($this->insert($this->tableNames['client_pv_invoice'], $InsertArray)) {

                            $insertid = $this->getInsertID();
                            $this->logMsg("New PV Invoice Added. ID : " . $insertid . ".");

                            $processedInvoiceItemArray = array();
                            foreach ($invoiceItemArray as $itemArr) {

                                $itemArr['invoice_id'] = $insertid;
                                array_push($processedInvoiceItemArray, $itemArr);
                            }

                            if ($this->insertMultiple($this->tableNames['client_pv_invoice_item'], $processedInvoiceItemArray)) {

                                $iteminsertid = $this->getInsertID();
                                $this->logMsg("New PV Invoice Item Added. ID : " . $iteminsertid . ".");
                            }
                        }
                    }
                }

                $this->setSuccess($this->validationMessage['invoiceadded']);
                return true;
            }
        }
    }

    /* upload client special tax invoice */

    public function uploadClientSTInvoice() {

        $flag = true;
        $errorflag = false;
        $dataArray = array();
        $indexArray = array();
        $invoiceArray = array();
        $invoiceItemArray = array();

        if ($_FILES['invoice_xlsx']['name'] != '' && $_FILES['invoice_xlsx']['error'] == 0) {

            $invoice_excel = $this->imageUploads($_FILES['invoice_xlsx'], 'invoice-docs', 'upload', $this->allowExcelExt);
            if ($invoice_excel == FALSE) {
                return false;
            }

            $invoice_excel_dir_path = PROJECT_ROOT . UPLOAD_DIR . "/invoice-docs/" . $invoice_excel;
            $invoice_excel_url_path = PROJECT_URL . UPLOAD_DIR . "/invoice-docs/" . $invoice_excel;

            $objPHPExcel = PHPExcel_IOFactory::load($invoice_excel_dir_path);
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
			$sheetData = array_map('array_filter', $sheetData);
			$sheetData = array_filter($sheetData);

            foreach ($sheetData as $rowKey => $data) {

                if ($flag) {
                    $indexArray = $data;
                    $flag = false;
                    continue;
                }

                $currentItemError = array();
                $dataArray['reference_number'] = isset($data['A']) ? $data['A'] : '';
                $dataArray['invoice_date'] = isset($data['B']) ? $data['B'] : '';

                $supply_place = isset($data['C']) ? $data['C'] : '';

                if ($supply_place != '') {

                    $supply_state_data = $this->getStateDetailByStateCode($supply_place);
                    if ($supply_state_data['status'] === "success") {
                        $dataArray['supply_place'] = $supply_state_data['data']->state_id;
                    } else {

                        $errorflag = true;
                        array_push($currentItemError, "Invalid supply state code.");
                        $dataArray['supply_place'] = 'Invalid State';
                    }
                } else {
                    $dataArray['supply_place'] = $supply_place;
                }

                $dataArray['billing_name'] = isset($data['D']) ? $data['D'] : '';
                $dataArray['billing_address'] = isset($data['E']) ? $data['E'] : '';
                $billing_state = isset($data['F']) ? $data['F'] : '';

                if ($billing_state != '') {

                    $billing_state_data = $this->getStateDetailByStateCode($billing_state);
                    if ($billing_state_data['status'] === "success") {
                        $dataArray['billing_state'] = $billing_state_data['data']->state_id;
                        $dataArray['billing_state_name'] = $billing_state_data['data']->state_name;
                    } else {

                        $errorflag = true;
                        array_push($currentItemError, "Invalid billing state code.");
                        $dataArray['billing_state'] = 'Invalid State';
                    }
                } else {
                    $dataArray['billing_state'] = $billing_state;
                }

                $dataArray['billing_gstin_number'] = isset($data['G']) ? $data['G'] : '';
                $dataArray['shipping_name'] = isset($data['H']) ? $data['H'] : '';
                $dataArray['shipping_address'] = isset($data['I']) ? $data['I'] : '';
                $shipping_state = isset($data['J']) ? $data['J'] : '';

                if ($shipping_state != '') {

                    $shipping_state_data = $this->getStateDetailByStateCode($shipping_state);
                    if ($shipping_state_data['status'] === "success") {
                        $dataArray['shipping_state'] = $shipping_state_data['data']->state_id;
                        $dataArray['shipping_state_name'] = $shipping_state_data['data']->state_name;
                    } else {

                        $errorflag = true;
                        array_push($currentItemError, "Invalid shipping state code.");
                        $dataArray['shipping_state'] = 'Invalid State';
                    }
                } else {
                    $dataArray['shipping_state'] = $shipping_state;
                }

                $dataArray['shipping_gstin_number'] = isset($data['K']) ? $data['K'] : '';

                $item_name = isset($data['L']) ? trim($data['L']) : '';
                $item_hsn_code = isset($data['M']) ? trim($data['M']) : '';

                //if(!empty($item_hsn_code)) { $item_hsn_code = str_pad($item_hsn_code, 8, "0", STR_PAD_LEFT); }

                $dataArray['item_quantity'] = isset($data['N']) ? $data['N'] : '';

                $dataArray['item_unit'] = isset($data['O']) ? $data['O'] : '';
                $dataArray['item_rate'] = isset($data['P']) ? $data['P'] : '';
                $dataArray['item_discount'] = isset($data['Q']) ? $data['Q'] : '';

                if ($item_name != '' && $item_hsn_code != '') {

                    $checkClientMasterItem = $this->get_row("select cm.item_id, cm.item_name, cm.unit_price, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, m.igst_tax_rate, m.csgt_tax_rate, m.sgst_tax_rate, m.cess_tax_rate, cm.item_unit, u.unit_id, u.unit_name, u.unit_code from " . $this->tableNames['client_master_item'] . " as cm, " . $this->tableNames['item'] . " as m, " . $this->tableNames['unit'] . " as u where 1=1 AND cm.item_category = m.item_id AND cm.item_unit = u.unit_id AND cm.item_name = '" . $item_name . "' && m.hsn_code = '" . $item_hsn_code . "' AND m.item_type = '1' AND cm.is_deleted='0' AND cm.status = '1' AND cm.added_by = '" . $this->sanitize($_SESSION['user_detail']['user_id']) . "'");

                    if (count($checkClientMasterItem) == 1) {

                        $dataArray['item_name'] = $item_name;
                        $dataArray['item_hsn_code'] = $item_hsn_code;
                    } else {

                        $errorflag = true;
                        array_push($currentItemError, "Service not exist with this hsn code.");
                        $dataArray['item_name'] = "#####Item not exist#####";
                        $dataArray['item_hsn_code'] = "#####HSN code not exist#####";
                    }
                } else {
                    $dataArray['item_name'] = $item_name;
                    $dataArray['item_hsn_code'] = $item_hsn_code;
                }

                $invoiceErrors = $this->validateClientInvoiceExcel($dataArray);
                if ($invoiceErrors !== true || !empty($currentItemError)) {

                    $errorflag = true;
                    if ($invoiceErrors === true) {
                        $invoiceErrors = array();
                    }
                    $invoiceErrors = array_merge($invoiceErrors, $currentItemError);
                    $invoiceErrors = implode(", ", $invoiceErrors);
                    $objPHPExcel->getActiveSheet()->SetCellValue('R' . $rowKey, $invoiceErrors);
                }

                /* get current user data */
                $dataCurrentUserArr = $this->getUserDetailsById($this->sanitize($_SESSION['user_detail']['user_id']));

                /* create invoice array */
                $arrayKey = $dataArray['reference_number'];

                $invoiceArray[$arrayKey]['reference_number'] = $dataArray['reference_number'];
                $invoiceArray[$arrayKey]['company_name'] = $dataCurrentUserArr['data']->kyc->name;
                $invoiceArray[$arrayKey]['company_address'] = $dataCurrentUserArr['data']->kyc->registered_address;
                $invoiceArray[$arrayKey]['company_state'] = $dataCurrentUserArr['data']->kyc->state_id;
                $invoiceArray[$arrayKey]['gstin_number'] = $dataCurrentUserArr['data']->kyc->gstin_number;
                $invoiceArray[$arrayKey]['invoice_date'] = $dataArray['invoice_date'];
                $invoiceArray[$arrayKey]['supply_place'] = $dataArray['supply_place'];
                $invoiceArray[$arrayKey]['billing_name'] = $dataArray['billing_name'];
                $invoiceArray[$arrayKey]['billing_address'] = $dataArray['billing_address'];
                $invoiceArray[$arrayKey]['billing_state'] = $dataArray['billing_state'];
                $invoiceArray[$arrayKey]['billing_state_name'] = $dataArray['billing_state_name'];
                $invoiceArray[$arrayKey]['billing_gstin_number'] = $dataArray['billing_gstin_number'];
                $invoiceArray[$arrayKey]['shipping_name'] = $dataArray['shipping_name'];
                $invoiceArray[$arrayKey]['shipping_address'] = $dataArray['shipping_address'];
                $invoiceArray[$arrayKey]['shipping_state'] = $dataArray['shipping_state'];
                $invoiceArray[$arrayKey]['shipping_state_name'] = $dataArray['shipping_state_name'];
                $invoiceArray[$arrayKey]['shipping_gstin_number'] = $dataArray['shipping_gstin_number'];

                //items
                $invoiceItemArray['item_name'] = $dataArray['item_name'];
                $invoiceItemArray['item_hsncode'] = $dataArray['item_hsn_code'];
                $invoiceItemArray['item_quantity'] = $dataArray['item_quantity'];
                $invoiceItemArray['item_unit'] = $dataArray['item_unit'];
                $invoiceItemArray['item_unit_price'] = $dataArray['item_rate'];
                $invoiceItemArray['item_discount'] = $dataArray['item_discount'];

                $invoiceArray[$arrayKey]['items'][] = $invoiceItemArray;
            }

            if ($errorflag === true) {

                $objPHPExcel->getActiveSheet()->SetCellValue('R1', "Error Information");
                $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
                $objWriter->save($invoice_excel_dir_path);
                $this->setError($this->validationMessage['excelerror']);

                $resultArray = array("status" => "error", "excelurl" => $invoice_excel_url_path);
                return json_encode($resultArray);
            } else {

                foreach ($invoiceArray as $invoiceRow) {

                    $invoiceItemArray = array();
                    $invoiceTotalAmount = 0.00;

                    foreach ($invoiceRow['items'] as $invoiceInnerRow) {

                        $clientMasterItem = $this->get_row("select cm.item_id, cm.item_name, cm.unit_price, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, m.igst_tax_rate, m.csgt_tax_rate, m.sgst_tax_rate, m.cess_tax_rate, cm.item_unit, u.unit_id, u.unit_name, u.unit_code from " . $this->tableNames['client_master_item'] . " as cm, " . $this->tableNames['item'] . " as m, " . $this->tableNames['unit'] . " as u where 1=1 AND cm.item_category = m.item_id AND cm.item_unit = u.unit_id AND cm.item_name = '" . $invoiceInnerRow['item_name'] . "' && m.hsn_code = '" . $invoiceInnerRow['item_hsncode'] . "' AND cm.is_deleted='0' AND cm.status = '1' AND cm.added_by = '" . $_SESSION['user_detail']['user_id'] . "'");
                        if (!empty($clientMasterItem)) {

                            $itemUnitPrice = (float) $invoiceInnerRow['item_unit_price'];
                            $invoiceItemQuantity = (int) $invoiceInnerRow['item_quantity'];
                            $invoiceItemDiscount = (float) $invoiceInnerRow['item_discount'];

                            $invoiceItemTotal = round(($invoiceItemQuantity * $itemUnitPrice), 2);
                            $invoiceItemDiscountAmount = ($invoiceItemDiscount / 100) * $invoiceItemTotal;
                            $invoiceItemTaxableAmount = round(($invoiceItemTotal - $invoiceItemDiscountAmount), 2);

                            if ($invoiceRow['company_state'] === $invoiceRow['supply_place']) {

                                $itemCSGTTax = (float) $clientMasterItem->csgt_tax_rate;
                                $itemSGSTTax = (float) $clientMasterItem->sgst_tax_rate;
                                $itemIGSTTax = 0.00;
                                $itemCESSTax = (float) $clientMasterItem->cess_tax_rate;

                                $invoiceItemCSGTTaxAmount = round((($itemCSGTTax / 100) * $invoiceItemTaxableAmount), 2);
                                $invoiceItemSGSTTaxAmount = round((($itemSGSTTax / 100) * $invoiceItemTaxableAmount), 2);
                                $invoiceItemIGSTTaxAmount = 0.00;
                                $invoiceItemCESSTaxAmount = round((($itemCESSTax / 100) * $invoiceItemTaxableAmount), 2);
                            } else {

                                $itemCSGTTax = 0.00;
                                $itemSGSTTax = 0.00;
                                $itemIGSTTax = (float) $clientMasterItem->igst_tax_rate;
                                $itemCESSTax = (float) $clientMasterItem->cess_tax_rate;

                                $invoiceItemCSGTTaxAmount = 0.00;
                                $invoiceItemSGSTTaxAmount = 0.00;
                                $invoiceItemIGSTTaxAmount = round((($itemIGSTTax / 100) * $invoiceItemTaxableAmount), 2);
                                $invoiceItemCESSTaxAmount = round((($itemCESSTax / 100) * $invoiceItemTaxableAmount), 2);
                            }

                            $invoiceItemTotalAmount = round(($invoiceItemTaxableAmount + $invoiceItemCSGTTaxAmount + $invoiceItemSGSTTaxAmount + $invoiceItemIGSTTaxAmount + $invoiceItemCESSTaxAmount), 2);
                            $invoiceTotalAmount += $invoiceItemTotalAmount;

                            $ItemArray = array(
                                "item_id" => $clientMasterItem->item_id,
                                "item_name" => $clientMasterItem->item_name,
                                "item_hsncode" => $clientMasterItem->hsn_code,
                                "item_quantity" => $invoiceItemQuantity,
                                "item_unit" => $clientMasterItem->unit_code,
                                "item_unit_price" => $itemUnitPrice,
                                "subtotal" => $invoiceItemTotal,
                                "discount" => $invoiceItemDiscount,
                                "taxable_subtotal" => $invoiceItemTaxableAmount,
                                "cgst_rate" => $itemCSGTTax,
                                "cgst_amount" => $invoiceItemCSGTTaxAmount,
                                "sgst_rate" => $itemSGSTTax,
                                "sgst_amount" => $invoiceItemSGSTTaxAmount,
                                "igst_rate" => $itemIGSTTax,
                                "igst_amount" => $invoiceItemIGSTTaxAmount,
                                "cess_rate" => $itemCESSTax,
                                "cess_amount" => $invoiceItemCESSTaxAmount,
                                "total" => $invoiceItemTotalAmount,
                                "status" => 1,
                                "added_by" => $_SESSION['user_detail']['user_id'],
                                "added_date" => date('Y-m-d H:i:s')
                            );

                            array_push($invoiceItemArray, $ItemArray);
                        }
                    }

                    if (!empty($invoiceItemArray) && count($invoiceItemArray) > 0) {

                        $InsertArray['reference_number'] = $invoiceRow['reference_number'];
                        $InsertArray['serial_number'] = $this->generateSTInvoiceNumber($this->sanitize($_SESSION['user_detail']['user_id']));
                        $InsertArray['company_name'] = $invoiceRow['company_name'];
                        $InsertArray['company_address'] = $invoiceRow['company_address'];
                        $InsertArray['company_state'] = $invoiceRow['company_state'];
                        $InsertArray['gstin_number'] = $invoiceRow['gstin_number'];
                        $InsertArray['invoice_date'] = $invoiceRow['invoice_date'];
                        $InsertArray['supply_place'] = $invoiceRow['supply_place'];
                        $InsertArray['billing_name'] = $invoiceRow['billing_name'];
                        $InsertArray['billing_address'] = $invoiceRow['billing_address'];
                        $InsertArray['billing_state'] = $invoiceRow['billing_state'];
                        $InsertArray['billing_state_name'] = $invoiceRow['billing_state_name'];
                        $InsertArray['billing_gstin_number'] = $invoiceRow['billing_gstin_number'];
                        $InsertArray['shipping_name'] = $invoiceRow['shipping_name'];
                        $InsertArray['shipping_address'] = $invoiceRow['shipping_address'];
                        $InsertArray['shipping_state'] = $invoiceRow['shipping_state'];
                        $InsertArray['shipping_state_name'] = $invoiceRow['shipping_state_name'];
                        $InsertArray['shipping_gstin_number'] = $invoiceRow['shipping_gstin_number'];
                        $InsertArray['invoice_total_value'] = $invoiceTotalAmount;
                        $InsertArray['financial_year'] = $this->generateFinancialYear();
                        $InsertArray['status'] = 1;
                        $InsertArray['added_by'] = $_SESSION['user_detail']['user_id'];
                        $InsertArray['added_date'] = date('Y-m-d H:i:s');

                        if ($this->insert($this->tableNames['client_st_invoice'], $InsertArray)) {

                            $insertid = $this->getInsertID();
                            $this->logMsg("New Special Tax Added. ID : " . $insertid . ".");

                            $processedInvoiceItemArray = array();
                            foreach ($invoiceItemArray as $itemArr) {

                                $itemArr['invoice_id'] = $insertid;
                                array_push($processedInvoiceItemArray, $itemArr);
                            }

                            if ($this->insertMultiple($this->tableNames['client_st_invoice_item'], $processedInvoiceItemArray)) {

                                $iteminsertid = $this->getInsertID();
                                $this->logMsg("New Special Tax Item Added. ID : " . $iteminsertid . ".");
                            }
                        }
                    }
                }

                $this->setSuccess($this->validationMessage['invoiceadded']);
                return true;
            }
        }
    }

    /* generate invoice html */

    public function generateInvoiceHtml($invid) {

        $currentFinancialYear = $this->generateFinancialYear();

        $invoiceData = $this->get_results("select 
													ci.invoice_id, 
													(case 
														when ci.invoice_type='taxinvoice' Then 'Tax Invoice' 
														when ci.invoice_type='exportinvoice' then 'Export Invoice' 
														when ci.invoice_type='sezunitinvoice' then 'SEZ Unit Invoice' 
														when ci.invoice_type='deemedexportinvoice' then 'Deemed Export Invoice' 
													end) as invoice_type, 
													(case 
														when ci.invoice_nature='salesinvoice' Then 'Sales Invoice' 
														when ci.invoice_nature='purchaseinvoice' then 'Purchase Invoice' 
													end) as invoice_nature, 
													ci.reference_number, 
													ci.serial_number, 
													ci.company_name, 
													ci.company_address, 
													ci.company_state, 
													ci.gstin_number, 
													(case 
														when ci.supply_type='normal' Then 'Normal' 
														when ci.supply_type='reversecharge' then 'Reverse Charge' 
														when ci.supply_type='tds' then 'TDS' 
														when ci.supply_type='tcs' then 'TCS' 
													end) as supply_type, 
													(case 
														when ci.export_supply_meant='withpayment' Then 'With Payment' 
														when ci.export_supply_meant='withoutpayment' then 'Without Payment' 
													end) as export_supply_meant, 
													ci.invoice_date, 
													ci.supply_place, 
													ci.ecommerce_gstin_number, 
													ci.ecommerce_vendor_code, 
													ci.advance_adjustment, 
													ci.receipt_voucher_number, 
													ci.billing_name, 
													ci.billing_company_name, 
													ci.billing_address, 
													ci.billing_state, 
													ci.billing_state_name, 
													ci.billing_country, 
													ci.billing_gstin_number, 
													ci.shipping_name, 
													ci.shipping_company_name, 
													ci.shipping_address, 
													ci.shipping_state, 
													ci.shipping_state_name, 
													ci.shipping_country, 
													ci.export_bill_number, 
													ci.export_bill_date, 
													ci.shipping_gstin_number, 
													ci.description, 
													ci.invoice_total_value, 
													(case 
														when ci.status='0' Then 'Active' 
														when ci.status='1' then 'Inactive' 
													end) as status, 
													ci.is_canceled, 
													cii.invoice_item_id, 
													cii.item_id, 
													cii.item_name, 
													cii.item_hsncode, 
													cii.item_quantity, 
													cii.item_unit, 
													cii.item_unit_price, 
													cii.subtotal, 
													cii.discount, 
													cii.advance_amount, 
													cii.taxable_subtotal, 
													cii.cgst_rate, 
													cii.cgst_amount, 
													cii.sgst_rate, 
													cii.sgst_amount, 
													cii.igst_rate, 
													cii.igst_amount, 
													cii.cess_rate, 
													cii.cess_amount, 
													cii.total 
													from 
												" . $this->tableNames['client_invoice'] . " as ci INNER JOIN " . $this->tableNames['client_invoice_item'] . " as cii ON ci.invoice_id = cii.invoice_id where ci.invoice_id = " . $invid . " AND ci.added_by = '" . $this->sanitize($_SESSION['user_detail']['user_id']) . "' AND cii.added_by = '" . $this->sanitize($_SESSION['user_detail']['user_id']) . "' AND ci.is_deleted='0' AND cii.is_deleted='0'");

        if (empty($invoiceData)) {
            return false;
        }

        $dataThemeSettingArr = $this->getUserThemeSetting($this->sanitize($_SESSION['user_detail']['user_id']));

        $mpdfHtml = '';
        $mpdfHtml .= '<div style="margin:auto;font-size:16px;line-height:24px;color:#555;">';
        $mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;" cellpadding="0" cellspacing="0">';
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;">';
        $mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;">';
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td style="font-size:45px;line-height:45px;color:#333;padding:5px;vertical-align:top;padding-bottom:20px;">';

        if (isset($dataThemeSettingArr['data']->theme_logo) && $dataThemeSettingArr['data']->theme_logo != "") {
            $mpdfHtml .= '<img src="' . PROJECT_URL . '/upload/theme-logo/' . $dataThemeSettingArr['data']->theme_logo . '" style="width:100%;max-width:300px;">';
        } else {
            $mpdfHtml .= '<img src="' . PROJECT_URL . '/image/gst-k-logo.png" style="width:100%;max-width:300px;">';
        }

        $mpdfHtml .= '</td>';

        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;text-align:right;padding-bottom:20px;">';
        $mpdfHtml .= '<b>Invoice #</b>: ' . $invoiceData[0]->serial_number . '<br>';
        $mpdfHtml .= '<b>Reference #</b>: ' . $invoiceData[0]->reference_number . '<br>';
        $mpdfHtml .= '<b>Type:</b> ' . $invoiceData[0]->invoice_type . '<br>';
        $mpdfHtml .= '<b>Nature:</b> ' . $invoiceData[0]->invoice_nature . '<br>';
        $mpdfHtml .= '<b>Invoice Date:</b>' . $invoiceData[0]->invoice_date;
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';
        $mpdfHtml .= '</table>';
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';

        $company_state_data = $this->getStateDetailByStateId($invoiceData[0]->company_state);
        $supply_place_data = $this->getStateDetailByStateId($invoiceData[0]->supply_place);

        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;">';
        $mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;">';
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;padding-bottom:20px;">';
        $mpdfHtml .= $invoiceData[0]->company_name . '<br>';
        $mpdfHtml .= $invoiceData[0]->company_address . '<br>';
        $mpdfHtml .= $company_state_data['data']->state_name . '<br>';
        $mpdfHtml .= '<b>GSTIN:</b> ' . $invoiceData[0]->gstin_number;
        $mpdfHtml .= '</td>';

        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;text-align:right;padding-bottom:20px;">';

        if ($invoiceData[0]->invoice_type === "Export Invoice") {

            $mpdfHtml .= '<b>Export Supply Meant:</b> ' . $invoiceData[0]->export_supply_meant . '<br>';
            if ($invoiceData[0]->is_canceled == 1) {
                $mpdfHtml .= '<b>Canceled Invoice:</b> Canceled <br>';
            }
            if ($invoiceData[0]->advance_adjustment == 1) {
                $mpdfHtml .= '<b>Advance Adjustment:</b> Yes <br>';
            }

            if ($invoiceData[0]->advance_adjustment == 1) {

                $receiptVoucher = $this->get_row("select invoice_id, serial_number, invoice_date, supply_place, is_canceled from " . $this->tableNames['client_rv_invoice'] . " where status='1' AND invoice_id = " . $invoiceData[0]->receipt_voucher_number . " AND is_deleted='0' AND financial_year = '" . $currentFinancialYear . "' AND added_by = " . $this->sanitize($_SESSION['user_detail']['user_id']) . " order by serial_number asc");
                if ($receiptVoucher) {
                    $mpdfHtml .= '<b>Receipt Voucher:</b> ' . $receiptVoucher->serial_number;
                }
            }
        } else {

            $mpdfHtml .= '<b>Supply Type:</b>' . $invoiceData[0]->supply_type . '<br>';
            if (isset($invoiceData[0]->supply_place) && $invoiceData[0]->supply_place > 0) {
                $mpdfHtml .= '<b>Place Of Supply:</b>' . $supply_place_data['data']->state_name . '<br>';
            }
            if ($invoiceData[0]->is_canceled == 1) {
                $mpdfHtml .= '<b>Canceled Invoice:</b> Canceled <br>';
            }
            if ($invoiceData[0]->advance_adjustment == 1) {
                $mpdfHtml .= '<b>Advance Adjustment:</b> Yes <br>';
            }

            if ($invoiceData[0]->advance_adjustment == 1) {

                $receiptVoucher = $this->get_row("select invoice_id, serial_number, invoice_date, supply_place, is_canceled from " . $this->tableNames['client_rv_invoice'] . " where status='1' AND invoice_id = " . $invoiceData[0]->receipt_voucher_number . " AND is_deleted='0' AND financial_year = '" . $currentFinancialYear . "' AND added_by = " . $this->sanitize($_SESSION['user_detail']['user_id']) . " order by serial_number asc");
                if ($receiptVoucher) {
                    $mpdfHtml .= '<b>Receipt Voucher:</b> ' . $receiptVoucher->serial_number . '<br>';
                }
            }

            if ($invoiceData[0]->supply_type === "TCS") {
                $mpdfHtml .= '<br><b>Ecommerce GSTIN Number:</b>' . $invoiceData[0]->ecommerce_gstin_number . '<br>';
                $mpdfHtml .= '<b>Ecommerce Vendor Code:</b>' . $invoiceData[0]->ecommerce_vendor_code;
            }
        }

        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';
        $mpdfHtml .= '</table>';
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';

        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;">';
        $mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;">';
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;padding-bottom:40px;width:50%;">';

        $mpdfHtml .= '<b>Recipient Detail</b><br>';
        $mpdfHtml .= $invoiceData[0]->billing_name . '<br>';
        if ($invoiceData[0]->billing_company_name) {
            $mpdfHtml .= $invoiceData[0]->billing_company_name . '<br>';
        }
        $mpdfHtml .= $invoiceData[0]->billing_address . '<br>';

        if (intval($invoiceData[0]->billing_state) > 0) {
            $billing_state_data = $this->getStateDetailByStateId($invoiceData[0]->billing_state);
            $mpdfHtml .= $billing_state_data['data']->state_name . '<br>';
        } else {
            $mpdfHtml .= $invoiceData[0]->billing_state_name . '<br>';
            $billing_country_data = $this->getCountryDetailByCountryId($invoiceData[0]->billing_country);
            $mpdfHtml .= $billing_country_data['data']->country_name . '<br>';
        }

        if ($invoiceData[0]->billing_gstin_number > 0) {
            $mpdfHtml .= '<b>GSTIN:</b>' . $invoiceData[0]->billing_gstin_number;
        }

        $mpdfHtml .= '</td>';

        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;text-align:right;padding-bottom:40px;width:50%;">';

        $mpdfHtml .= '<b>Address Of Delivery / Shipping Detail</b><br>';
        $mpdfHtml .= $invoiceData[0]->shipping_name . '<br>';
        if ($invoiceData[0]->shipping_company_name) {
            $mpdfHtml .= $invoiceData[0]->shipping_company_name . '<br>';
        }
        $mpdfHtml .= $invoiceData[0]->shipping_address . '<br>';

        if (intval($invoiceData[0]->shipping_state) > 0) {
            $shipping_state_data = $this->getStateDetailByStateId($invoiceData[0]->shipping_state);
            $mpdfHtml .= $shipping_state_data['data']->state_name . '<br>';
        } else {
            $mpdfHtml .= $invoiceData[0]->shipping_state_name . '<br>';
            $shipping_country_data = $this->getCountryDetailByCountryId($invoiceData[0]->shipping_country);
            $mpdfHtml .= $shipping_country_data['data']->country_name . '<br>';
        }

        if ($invoiceData[0]->invoice_type === "Export Invoice") {
            $mpdfHtml .= '<b>Export Bill Number:</b>' . $invoiceData[0]->export_bill_number . '<br>';
            $mpdfHtml .= '<b>Export Bill Date:</b>' . $invoiceData[0]->export_bill_date . '<br>';
        }

        if ($invoiceData[0]->shipping_gstin_number > 0) {
            $mpdfHtml .= '<b>GSTIN:</b>' . $invoiceData[0]->shipping_gstin_number;
        }

        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';
        $mpdfHtml .= '</table>';
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';

        $mpdfHtml .= '<tr>';

        $mpdfHtml .= '<td colspan="2">';

        $mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:center;">';
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">S.No</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Goods/Services</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">HSN/SAC Code</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Qty</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Unit</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Rate</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Total</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Discount(%)</td>';

        if ($invoiceData[0]->advance_adjustment == 1) {
            $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Discount(%)</td>';
        }

        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Taxable Value</td>';
        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">CGST</td>';
        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">SGST</td>';
        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">IGST</td>';
        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">CESS</td>';
        $mpdfHtml .= '</tr>';

        $mpdfHtml .= '<tr class="heading">';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">(%)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Amt (₹)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">(%)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Amt (₹)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">(%)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Amt (₹)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">(%)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Amt (₹)</td>';
        $mpdfHtml .= '</tr>';

        $counter = 1;
        $total_taxable_subtotal = 0.00;
        $total_cgst_amount = 0.00;
        $total_sgst_amount = 0.00;
        $total_igst_amount = 0.00;
        $total_cess_amount = 0.00;
        foreach ($invoiceData as $invData) {

            $mpdfHtml .= '<tr>';
            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
            $mpdfHtml .= $counter;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
            $mpdfHtml .= $invData->item_name;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
            $mpdfHtml .= $invData->item_hsncode;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
            $mpdfHtml .= $invData->item_quantity;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
            $mpdfHtml .= $invData->item_unit;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
            $mpdfHtml .= $invData->item_unit_price;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
            $mpdfHtml .= $invData->subtotal;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
            $mpdfHtml .= $invData->discount;
            $mpdfHtml .= '</td>';

            if ($invoiceData[0]->advance_adjustment == 1) {
                $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
                $mpdfHtml .= $invData->advance_amount;
                $mpdfHtml .= '</td>';
            }

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
            $mpdfHtml .= $invData->taxable_subtotal;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
            $mpdfHtml .= $invData->cgst_rate;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
            $mpdfHtml .= $invData->cgst_amount;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
            $mpdfHtml .= $invData->sgst_rate;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
            $mpdfHtml .= $invData->sgst_amount;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
            $mpdfHtml .= $invData->igst_rate;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
            $mpdfHtml .= $invData->igst_amount;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
            $mpdfHtml .= $invData->cess_rate;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
            $mpdfHtml .= $invData->cess_amount;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '</tr>';

            $total_taxable_subtotal += $invData->taxable_subtotal;
            $total_cgst_amount += $invData->cgst_amount;
            $total_sgst_amount += $invData->sgst_amount;
            $total_igst_amount += $invData->igst_amount;
            $total_cess_amount += $invData->cess_amount;

            $counter++;
        }

        $mpdfHtml .= '<tr>';
        if ($invoiceData[0]->advance_adjustment == 1) {
            $mpdfHtml .= '<td colspan="18" style="padding:5px;vertical-align:top;text-align:right;border-top:2px solid #eee;font-weight:bold;">';
        } else {
            $mpdfHtml .= '<td colspan="17" style="padding:5px;vertical-align:top;text-align:right;border-top:2px solid #eee;font-weight:bold;">';
        }
        $mpdfHtml .= 'Total Invoice Value (In Figure): ' . $invoiceData[0]->invoice_total_value;
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';

        $invoice_total_value_words = $this->convert_number_to_words($invoiceData[0]->invoice_total_value);

        $mpdfHtml .= '<tr>';
        if ($invoiceData[0]->advance_adjustment == 1) {
            $mpdfHtml .= '<td colspan="18" style="padding:5px;vertical-align:top;text-align:right;border-top:2px solid #eee;font-weight:bold;">';
        } else {
            $mpdfHtml .= '<td colspan="17" style="padding:5px;vertical-align:top;text-align:right;border-top:2px solid #eee;font-weight:bold;">';
        }
        $mpdfHtml .= 'Total Invoice Value (In Words): ' . ucwords($invoice_total_value_words);
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';

        if ($invoiceData[0]->supply_type === "TDS" || $invoiceData[0]->supply_type === "TCS") {

            if ($invoiceData[0]->company_state === $invoiceData[0]->supply_place) {

                $withoutTaxValue = ((1 / 100) * $total_taxable_subtotal);

                $mpdfHtml .= '<tr style="background:#e9ffdb;font-size:14px;">';

                if ($invoiceData[0]->advance_adjustment == 1) {
                    $mpdfHtml .= '<td colspan="10" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Amount of Tax Subject to ' . strtoupper($invoiceData[0]->supply_type) . '</td>';
                } else {
                    $mpdfHtml .= '<td colspan="9" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Amount of Tax Subject to ' . strtoupper($invoiceData[0]->supply_type) . '</td>';
                }

                $mpdfHtml .= '<td>1%</td>';
                $mpdfHtml .= '<td>₹' . round(($withoutTaxValue), 2) . '</td>';
                $mpdfHtml .= '<td>1%</td>';
                $mpdfHtml .= '<td>₹' . round(($withoutTaxValue), 2) . '</td>';
                $mpdfHtml .= '<td>0%</td>';
                $mpdfHtml .= '<td>₹0.00</td>';
                $mpdfHtml .= '<td>0%</td>';
                $mpdfHtml .= '<td>₹0.00</td>';
                $mpdfHtml .= '</tr>';
            } else {

                $withoutTaxValue = ((2 / 100) * $total_taxable_subtotal);

                $mpdfHtml .= '<tr style="background:#e9ffdb;font-size:14px;">';

                if ($invoiceData[0]->advance_adjustment == 1) {
                    $mpdfHtml .= '<td colspan="10" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Amount of Tax Subject to ' . strtoupper($invoiceData[0]->supply_type) . '</td>';
                } else {
                    $mpdfHtml .= '<td colspan="9" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Amount of Tax Subject to ' . strtoupper($invoiceData[0]->supply_type) . '</td>';
                }

                $mpdfHtml .= '<td>0%</td>';
                $mpdfHtml .= '<td>₹0.00</td>';
                $mpdfHtml .= '<td>0%</td>';
                $mpdfHtml .= '<td>₹0.00</td>';
                $mpdfHtml .= '<td>2%</td>';
                $mpdfHtml .= '<td>₹' . $total_igst_amount . '</td>';
                $mpdfHtml .= '<td>0%</td>';
                $mpdfHtml .= '<td>₹0.00</td>';
                $mpdfHtml .= '</tr>';
            }
        }

        if ($invoiceData[0]->supply_type === "Reverse Charge") {

            if ($invoiceData[0]->company_state === $invoiceData[0]->supply_place) {

                $mpdfHtml .= '<tr style="background:#e9ffdb;font-size:14px;">';

                if ($invoiceData[0]->advance_adjustment == 1) {
                    $mpdfHtml .= '<td colspan="10" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Amount of Tax Subject to Reverse Charge</td>';
                } else {
                    $mpdfHtml .= '<td colspan="9" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Amount of Tax Subject to Reverse Charge</td>';
                }

                $mpdfHtml .= '<td>-</td>';
                $mpdfHtml .= '<td>₹' . $total_cgst_amount . '</td>';
                $mpdfHtml .= '<td>-</td>';
                $mpdfHtml .= '<td>₹' . $total_sgst_amount . '</td>';
                $mpdfHtml .= '<td>-</td>';
                $mpdfHtml .= '<td>₹0.00</td>';
                $mpdfHtml .= '<td>-</td>';
                $mpdfHtml .= '<td>₹' . $total_cess_amount . '</td>';
                $mpdfHtml .= '</tr>';
            } else {

                $mpdfHtml .= '<tr style="background:#e9ffdb;font-size:14px;">';

                if ($invoiceData[0]->advance_adjustment == 1) {
                    $mpdfHtml .= '<td colspan="10" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Amount of Tax Subject to Reverse Charge</td>';
                } else {
                    $mpdfHtml .= '<td colspan="9" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Amount of Tax Subject to Reverse Charge</td>';
                }

                $mpdfHtml .= '<td>-</td>';
                $mpdfHtml .= '<td>₹0.00</td>';
                $mpdfHtml .= '<td>-</td>';
                $mpdfHtml .= '<td>₹0.00</td>';
                $mpdfHtml .= '<td>-</td>';
                $mpdfHtml .= '<td>₹' . $total_igst_amount . '</td>';
                $mpdfHtml .= '<td>-</td>';
                $mpdfHtml .= '<td>₹' . $total_cess_amount . '</td>';
                $mpdfHtml .= '</tr>';
            }
        }

        $mpdfHtml .= '</table>';

        $mpdfHtml .= '</td>';

        $mpdfHtml .= '</tr>';

        $mpdfHtml .= '</table>';
        $mpdfHtml .= '</div>';

        return $mpdfHtml;
    }

}
