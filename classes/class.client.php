<?php
/*
 * 
 *  Developed By        :   Rishap Gandhi
 *  Date Created        :   June 24, 2017
 *  Last Modified       :   June 24, 2017
 *  Last Modified By    :   Rishap Gandhi
 *  Last Modification   :   class for client 
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

			$dataCurrentSubsArr = $this->getUserDetailsById($this->sanitize($_SESSION['user_detail']['user_id']));

			$parentIds = array();
			if($dataCurrentSubsArr['data']->user_group == 3) {

				array_push($parentIds, $dataCurrentSubsArr['data']->user_id);
				$getSubUser = $this->get_results("select user_id from ".$this->tableNames['user']." where 1=1 AND user_group = '5' AND added_by = '" . $dataCurrentSubsArr['data']->user_id . "'");
				foreach($getSubUser as $subuser) {
					array_push($parentIds, $subuser->user_id);
				}

				$subscribePlanDetail = $this->getUserSubscribePlanDetails($dataCurrentSubsArr['data']->plan_id, $dataCurrentSubsArr['data']->user_id);
			} else if($dataCurrentSubsArr['data']->user_group == 5) {

				$getParent = $this->get_row("select added_by from ".$this->tableNames['user']." where 1=1 AND user_group = '5' AND user_id = '" . $dataCurrentSubsArr['data']->user_id . "'");
				array_push($parentIds, $getParent->added_by);

				$getSubUser = $this->get_results("select user_id from ".$this->tableNames['user']." where 1=1 AND user_group = '5' AND added_by = '" . $getParent->added_by . "'");
				foreach($getSubUser as $subuser) {
					array_push($parentIds, $subuser->user_id);
				}

				$dataParentSubsArr = $this->getUserDetailsById($getParent->added_by);
				$subscribePlanDetail = $this->getUserSubscribePlanDetails($dataParentSubsArr['data']->plan_id, $getParent->added_by);
			}

			$allParentIds = implode(",", $parentIds);

			$totalPANs = $this->get_results("select count(DISTINCT k.pan_card_number) as total_pan_card_number from ".$this->tableNames['user']." u left join " . $this->getTableName('client_kyc') ." k on u.user_id = k.added_by where 1=1 AND u.user_group = '4' AND u.added_by IN(".$allParentIds.")");
			$panPermission = isset($subscribePlanDetail['data']->pan_num) ? $subscribePlanDetail['data']->pan_num : 0;
		
			$panFromGTIN = substr(substr($dataArr['gstin_number'], 2), 0, -3);
			if($panFromGTIN !== $dataArr['pan_card_number']) {
				$this->setError('Pan Card number should be according to GSTIN.');
				return false;
			}
			
			if($totalPANs[0]->total_pan_card_number >= $panPermission && $panPermission != -1) {

				$panResult = $this->get_results("select pan_card_number from ".$this->tableNames['user']." u left join " . $this->tableNames['client_kyc'] ." k on u.user_id = k.added_by where 1=1 AND u.user_group = '4' AND k.pan_card_number = '".$dataArr['pan_card_number']."' AND u.added_by IN(".$allParentIds.")");
				if(count($panResult) == 0) {
					$this->setError('You have reach maximum company creation limit.');
					return false;
				}
			}

			$state_tin = isset($_POST['state_tin']) ? $_POST['state_tin'] : '';
			$state_gstin_tin = substr($dataArr['gstin_number'], 0, 2);

			if($state_gstin_tin != $state_tin) {
				$this->setError('State should be valid according to GSTIN.');
				return false;
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
                $this->logMsg("User KYC ID : " . $this->sanitize(base64_decode($_POST['clientID'])) . " has been updated.","client_kycupdate");
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
                $this->logMsg("User KYC Added. ID : " . $insertid . ".","client_kycupdate");
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

			$dataCurrentSubsArr = $this->getUserDetailsById($this->sanitize($dataCurrentArr['data']->added_by));

			$parentIds = array();
			if($dataCurrentSubsArr['data']->user_group == 3) {

				array_push($parentIds, $dataCurrentSubsArr['data']->user_id);
				$getSubUser = $this->get_results("select user_id from ".$this->tableNames['user']." where 1=1 AND user_group = '5' AND added_by = '" . $dataCurrentSubsArr['data']->user_id . "'");
				foreach($getSubUser as $subuser) {
					array_push($parentIds, $subuser->user_id);
				}

				$subscribePlanDetail = $this->getUserSubscribePlanDetails($dataCurrentSubsArr['data']->plan_id, $dataCurrentSubsArr['data']->user_id);
			} else if($dataCurrentSubsArr['data']->user_group == 5) {

				$getParent = $this->get_row("select added_by from ".$this->tableNames['user']." where 1=1 AND user_group = '5' AND user_id = '" . $dataCurrentSubsArr['data']->user_id . "'");
				array_push($parentIds, $getParent->added_by);

				$getSubUser = $this->get_results("select user_id from ".$this->tableNames['user']." where 1=1 AND user_group = '5' AND added_by = '" . $getParent->added_by . "'");
				foreach($getSubUser as $subuser) {
					array_push($parentIds, $subuser->user_id);
				}

				$dataParentSubsArr = $this->getUserDetailsById($getParent->added_by);
				$subscribePlanDetail = $this->getUserSubscribePlanDetails($dataParentSubsArr['data']->plan_id, $getParent->added_by);
			}
			
			$allParentIds = implode(",", $parentIds);

			$totalPANs = $this->get_results("select count(DISTINCT k.pan_card_number) as total_pan_card_number from ".$this->tableNames['user']." u left join " . $this->getTableName('client_kyc') ." k on u.user_id = k.added_by where 1=1 AND u.user_group = '4' AND u.added_by IN(".$allParentIds.")");
			$panPermission = isset($subscribePlanDetail['data']->pan_num) ? $subscribePlanDetail['data']->pan_num : 0;

			$panFromGTIN = substr(substr($dataArr['gstin_number'], 2), 0, -3);
			if($panFromGTIN !== $dataArr['pan_card_number']) {
				$this->setError('Pan Card number should be according to GSTIN.');
				return false;
			}

			if($totalPANs[0]->total_pan_card_number >= $panPermission && $panPermission != -1) {

				$panResult = $this->get_results("select pan_card_number from ".$this->tableNames['user']." u left join " . $this->tableNames['client_kyc'] ." k on u.user_id = k.added_by where 1=1 AND u.user_group = '4' AND k.pan_card_number = '".$dataArr['pan_card_number']."' AND u.added_by IN(".$allParentIds.")");
				if(count($panResult) == 0) {
					$this->setError('You have reach maximum company creation limit.');
					return false;
				}
			}

			$state_tin = isset($_POST['state_tin']) ? $_POST['state_tin'] : '';
			$state_gstin_tin = substr($dataArr['gstin_number'], 0, 2);

			if($state_gstin_tin != $state_tin) {
				$this->setError('State should be valid according to GSTIN.');
				return false;
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
			if ($this->update($this->tableNames['client_kyc'], $dataArr, $dataConditionArray)) {
                $this->setSuccess($this->validationMessage['kycupdated']);
                $this->logMsg("User KYC ID : " . $_SESSION['user_detail']['user_id'] . " has been updated.","client_kycupdate");
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
                $this->logMsg("User KYC Added. ID : " . $insertid . ".","client_kycupdate");
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
            'business_area' => 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Business Area',
            'business_type' => 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Business Type',
			'vendor_type' => 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Vendor Type',
			'registered_address' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Permanent Address',
            'state_id' => 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:State'
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
		$dataArr['unit_purchase_price'] = isset($_POST['unit_purchase_price']) ? $_POST['unit_purchase_price'] : '';
		$dataArr['cgst_tax_rate'] = isset($_POST['cgst_tax_rate']) ? $_POST['cgst_tax_rate'] : '';
		$dataArr['sgst_tax_rate'] = isset($_POST['sgst_tax_rate']) ? $_POST['sgst_tax_rate'] : '';
		$dataArr['igst_tax_rate'] = isset($_POST['igst_tax_rate']) ? $_POST['igst_tax_rate'] : '';
		$dataArr['cess_tax_rate'] = isset($_POST['cess_tax_rate']) ? $_POST['cess_tax_rate'] : '';
        $dataArr['item_unit'] = isset($_POST['item_unit']) ? $_POST['item_unit'] : '';
		$dataArr['item_description'] = isset($_POST['item_description']) ? $_POST['item_description'] : '';
        $dataArr['status'] = isset($_POST['status']) ? $_POST['status'] : '';
		$dataArr['is_applicable'] = isset($_POST['is_applicable']) ? $_POST['is_applicable'] : '';

        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }

        if (!$this->validateClientItem($dataArr)) {
            return false;
        }
		
		if ($dataArr['cgst_tax_rate'] != $dataArr['sgst_tax_rate']) {
            $this->setError("CGST and SGST rate should be same for item");
            return false;
        }

        $dataArr['added_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['added_date'] = date('Y-m-d H:i:s');

        if ($this->insert($this->tableNames['client_master_item'], $dataArr)) {

            $this->setSuccess($this->validationMessage['iteminserted']);
            $insertid = $this->getInsertID();
            $this->logMsg("New Item Added. ID : " . $insertid . ".","client_item_update");
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
		$dataArr['unit_purchase_price'] = isset($_POST['unit_purchase_price']) ? $_POST['unit_purchase_price'] : '';
		$dataArr['cgst_tax_rate'] = isset($_POST['cgst_tax_rate']) ? $_POST['cgst_tax_rate'] : '';
		$dataArr['sgst_tax_rate'] = isset($_POST['sgst_tax_rate']) ? $_POST['sgst_tax_rate'] : '';
		$dataArr['igst_tax_rate'] = isset($_POST['igst_tax_rate']) ? $_POST['igst_tax_rate'] : '';
		$dataArr['cess_tax_rate'] = isset($_POST['cess_tax_rate']) ? $_POST['cess_tax_rate'] : '';
		$dataArr['item_unit'] = isset($_POST['item_unit']) ? $_POST['item_unit'] : '';
		$dataArr['item_description'] = isset($_POST['item_description']) ? $_POST['item_description'] : '';
        $dataArr['status'] = isset($_POST['status']) ? $_POST['status'] : '';
		$dataArr['is_applicable'] = isset($_POST['is_applicable']) ? $_POST['is_applicable'] : '';

        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }

        if (!$this->validateClientItem($dataArr)) {
            return false;
        }

		if ($dataArr['cgst_tax_rate'] != $dataArr['sgst_tax_rate']) {
            $this->setError("CGST and SGST rate should be same for item");
            return false;
        }

        $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['updated_date'] = date('Y-m-d H:i:s');

        $dataConditionArray['item_id'] = $this->sanitize($_GET['id']);
        if ($this->update($this->tableNames['client_master_item'], $dataArr, $dataConditionArray)) {

            $this->setSuccess($this->validationMessage['itemupdated']);
            $this->logMsg("Item ID : " . $_GET['id'] . " has been updated","client_item_update");
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
            'item_category' => 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Item Category',
            'unit_price' => 'decimal|#|lable_name:Sales Price',
			'unit_purchase_price' => 'decimal|#|lable_name:Purchase Price',
			'cgst_tax_rate' => 'numeric|#|lable_name:CGST Tax Rate',
			'sgst_tax_rate' => 'numeric|#|lable_name:SGST Tax Rate',
			'igst_tax_rate' => 'numeric|#|lable_name:IGST Tax Rate',
			'cess_tax_rate' => 'numeric|#|lable_name:CESS Tax Rate',
            'item_unit' => 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Item Unit',
			'item_description' => 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Item Description',
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
		$dataConditionArray['added_by'] = $this->sanitize($_SESSION['user_detail']['user_id']);
        $dataUpdateArray['is_deleted'] = "1";
        $dataUpdateArray['deleted_by'] = $this->sanitize($_SESSION['user_detail']['user_id']);
        $dataUpdateArray['deleted_date'] = date('Y-m-d H:i:s');

		$clientSalesInvoiceItem = $this->get_results("select * from ".$this->tableNames['client_invoice_item']." where 1=1 AND item_id = '".$itemid."' AND added_by = '" . $this->sanitize($_SESSION['user_detail']['user_id']) . "'");
		$clientPurchaseInvoiceItem = $this->get_results("select * from ".$this->tableNames['client_purchase_invoice_item']." where 1=1 AND item_id = '".$itemid."' AND added_by = '" . $this->sanitize($_SESSION['user_detail']['user_id']) . "'");

		if(count($clientSalesInvoiceItem) != 0 || count($clientPurchaseInvoiceItem) != 0) {
			$this->setError("This item is already used in invoice so you can't delete.");
			return false;
		}

        if ($this->update($this->tableNames['client_master_item'], $dataUpdateArray, $dataConditionArray)) {

            $this->setSuccess($this->validationMessage['itemdeleted']);
            $this->logMsg("Item ID : " . $itemid . " in client master Item has been deleted","client_item_update");
            return true;
        } else {

            $this->setError($this->validationMessage['failed']);
            return false;
        }

        return true;
    }

	final public function uploadClientMasterItem() {

		$flag = true;
		$errorflag = false;
		$dataArray = array();
		$indexArray = array();
		$itemArray = array();
		$arrayCounter = 0;

		if ($_FILES['item_xlsx']['name'] != '' && $_FILES['item_xlsx']['error'] == 0) {

			$item_excel = $this->imageUploads($_FILES['item_xlsx'], 'master-docs', 'upload', $this->allowExcelExt);
			if ($item_excel == FALSE) {
				return false;
			}

			$item_excel_dir_path = PROJECT_ROOT . UPLOAD_DIR . "/master-docs/" . $item_excel;
			$item_excel_url_path = PROJECT_URL . UPLOAD_DIR . "/master-docs/" . $item_excel;

			$objPHPExcel = PHPExcel_IOFactory::load($item_excel_dir_path);
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
                $dataArray['item_name'] = isset($data['A']) ? $data['A'] : '';
				$item_hsnsac_code  = isset($data['B']) ? $data['B'] : '';

				if($item_hsnsac_code != '') {

					$datahsnsaccode = $this->get_row("select * from ".$this->tableNames['item']." where 1=1 AND hsn_code = '".$item_hsnsac_code."' AND status='1' AND is_deleted='0'");

					if(!empty($datahsnsaccode) && isset($datahsnsaccode->item_id) ) {
						$dataArray['item_category'] = $datahsnsaccode->item_id;
					} else {
						$errorflag = true;
						array_push($currentItemError, "Invalid HSN/SAC Code.");
					}

				} else {
					$errorflag = true;
					array_push($currentItemError, "Invalid HSN/SAC Code.");
				}

				$is_applicable = isset($data['C']) ? $data['C'] : '';
				if ($is_applicable != '' && strtoupper($is_applicable) == 'NON GST') {
					$dataArray['is_applicable'] = '1';
				} else if ($is_applicable != '' && strtoupper($is_applicable) == 'EXEMPTED') {
					$dataArray['is_applicable'] = '2';
				} else {
					$dataArray['is_applicable'] = '0';
				}

				$dataArray['unit_price'] = isset($data['D']) ? round($data['D'], 2) : 0.00;
				$dataArray['unit_purchase_price'] = isset($data['E']) ? round($data['E'], 2) : 0.00;
				$dataArray['cgst_tax_rate'] = isset($data['F']) ? round($data['F'], 3) : 0.000;
				$dataArray['sgst_tax_rate'] = isset($data['G']) ? round($data['G'], 3) : 0.000;
				$dataArray['igst_tax_rate'] = isset($data['H']) ? round($data['H'], 3) : 0.000;
				$dataArray['cess_tax_rate'] = isset($data['I']) ? round($data['I'], 3) : 0.000;

				$item_unit = isset($data['J']) ? $data['J'] : '';
				if ($item_unit != '') {

					$masterUnit = $this->get_row("select unit_id from " . $this->tableNames['unit'] . " as u where u.unit_code='".$item_unit."' and u.is_deleted='0' AND u.status = '1'");
					if(!empty($masterUnit)) {
						$dataArray['item_unit'] = $masterUnit->unit_id;
					} else {
						
						$masterNUnit = $this->get_row("select unit_id from " . $this->tableNames['unit'] . " as u where u.unit_code='NA' and u.is_deleted='0' AND u.status = '1'");
						if(!empty($masterNUnit)) {
							$dataArray['item_unit'] = $masterNUnit->unit_id;
						} else {
							$dataArray['item_unit'] = 0;
						}
					}

				} else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Unit Type.");
				}

                $item_status = isset($data['K']) ? $data['K'] : '';
				if ($item_status != '' && strtoupper($item_status) == 'ACTIVE') {
					$dataArray['status'] = '1';
				} else {
					$dataArray['status'] = '0';
				}
				
				$dataArray['item_description'] = isset($data['L']) ? $data['L'] : '';

				$itemErrors = $this->validateClientItemExcel($dataArray);
				if ($itemErrors !== true || !empty($currentItemError)) {

					$errorflag = true;
					if ($itemErrors === true) {
						$itemErrors = array();
					}
					$itemErrors = array_merge($itemErrors, $currentItemError);
					$itemErrors = implode(", ", $itemErrors);
					$objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowKey, $itemErrors);
				}

				$dataArray['added_by'] = $this->sanitize($_SESSION['user_detail']['user_id']);
				$dataArray['added_date'] = date('Y-m-d H:i:s');

				if ($errorflag === false) {

					$checkClientItem = $this->get_row("select * from ".$this->tableNames['client_master_item']." where 1=1 AND item_name = '".$dataArray['item_name']."' AND item_category = '".$dataArray['item_category']."' AND is_deleted='0'");
					if(count($checkClientItem) == 0) {

						$itemArray[$arrayCounter]['item_name'] = $dataArray['item_name'];
						$itemArray[$arrayCounter]['item_category'] = $dataArray['item_category'];
						$itemArray[$arrayCounter]['unit_price'] = $dataArray['unit_price'];
						$itemArray[$arrayCounter]['item_unit'] = $dataArray['item_unit'];
						$itemArray[$arrayCounter]['unit_purchase_price'] = $dataArray['unit_purchase_price'];
						$itemArray[$arrayCounter]['cgst_tax_rate'] = $dataArray['cgst_tax_rate'];
						$itemArray[$arrayCounter]['sgst_tax_rate'] = $dataArray['sgst_tax_rate'];
						$itemArray[$arrayCounter]['igst_tax_rate'] = $dataArray['igst_tax_rate'];
						$itemArray[$arrayCounter]['cess_tax_rate'] = $dataArray['cess_tax_rate'];
						$itemArray[$arrayCounter]['status'] = $dataArray['status'];
						$itemArray[$arrayCounter]['item_description'] = $dataArray['item_description'];
						$itemArray[$arrayCounter]['added_by'] = $dataArray['added_by'];
						$itemArray[$arrayCounter]['added_date'] = $dataArray['added_date'];
						$arrayCounter++;
					}
				}
            }

			if ($errorflag === true) {

				$objPHPExcel->getActiveSheet()->SetCellValue('M1', "Error Information");
				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
				$objWriter->save($item_excel_dir_path);
				$this->setError($this->validationMessage['excelerror']);
				$resultArray = array("status" => "error", "excelurl" => $item_excel_url_path);
				return json_encode($resultArray);
			} else {

				if ($this->insertMultiple($this->tableNames['client_master_item'], $itemArray)) {
					$iteminsertid = $this->getInsertID();
					$this->logMsg("Client Master Items Added. ID : " . $iteminsertid . ".", "client_item_excel_upload");
				}

				$this->setSuccess($this->validationMessage['itemadded']);
				return true;
            }
        }
    }

    final public function validateClientItemExcel($dataArr) {

		$rules = array(
            'item_name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Item Name',
            'item_category' => 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Item Category',			
			'unit_price' => 'decimal|#|lable_name:Sales Price',
			'unit_purchase_price' => 'decimal|#|lable_name:Purchase Price',
			'cgst_tax_rate' => 'numeric|#|lable_name:CGST Tax Rate',
			'sgst_tax_rate' => 'numeric|#|lable_name:SGST Tax Rate',
			'igst_tax_rate' => 'numeric|#|lable_name:IGST Tax Rate',
			'cess_tax_rate' => 'numeric|#|lable_name:CESS Tax Rate',
            'item_unit' => 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Item Unit',
			'item_description' => 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Item Description',
            'status' => 'required||pattern:/^[' . $this->validateType['onlyzeroone'] . ']*$/|#|lable_name:Status'
        );

		$valid = $this->vali_obj->validate($dataArr, $rules);
        if ($valid->hasErrors()) {
            cms_validate::$errors = array();
            $err_arr = $valid->allErrors();
            $valid->clearMessages();
            return $err_arr;
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
		$dataArr['allow_return_upload'] = isset($_POST['allow_return_upload']) ? $_POST['allow_return_upload'] : '';

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
            $this->logMsg("New User Added. ID : " . $insertid . ".","addClientUser");
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
		$dataArr['allow_return_upload'] = isset($_POST['allow_return_upload']) ? $_POST['allow_return_upload'] : '';

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
            $this->logMsg("User ID : " . $_GET['id'] . " has been updated","updateClientUser");
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
            $this->logMsg("User ID : " . $userid . " in User has been deleted","deleteClientUser");
            return true;
        } else {

            $this->setError($this->validationMessage['failed']);
            return false;
        }

        return true;
    }

    /* validate client invoice excel file */
    public function validateClientInvoiceExcel($dataArr) {

        if (array_key_exists("invoice_type", $dataArr)) {
			$rules['invoice_type'] = 'required||invoicetype|#|lable_name:Invoice Type';
		}

		if (array_key_exists("invoice_date", $dataArr)) {
			$rules['invoice_date'] = 'required||date|#|lable_name:Invoice Date';
		}

		if (array_key_exists("reference_number", $dataArr)) {
			$rules['reference_number'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/||max:16|#|lable_name:Reference Number';
		}

		if (array_key_exists("supply_type", $dataArr)) {
			$rules['supply_type'] = 'required||supplytype|#|lable_name:Supply Type';
		}

		if (array_key_exists("ecommerce_gstin_number", $dataArr)) {
			$rules['ecommerce_gstin_number'] = 'required||pattern:/^' . $this->validateType['gstinnumber'] . '+$/||min:15||max:15|#|lable_name:Ecommerce GSTIN Number';
		}
		
		if (array_key_exists("ecommerce_vendor_code", $dataArr)) {
			$rules['ecommerce_vendor_code'] = 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Ecommerce Vendor Code';
		}
		
		if (array_key_exists("export_bill_number", $dataArr)) {
			$rules['export_bill_number'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Export Bill Number';
		}
		
		if (array_key_exists("export_bill_port_code", $dataArr)) {
			$rules['export_bill_port_code'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Export Bill Port Code';
		}
		
		if (array_key_exists("export_bill_date", $dataArr)) {
			$rules['export_bill_date'] = 'required||date|#|lable_name:Export Bill Date';
		}

		if (array_key_exists("delivery_challan_type", $dataArr)) {
			$rules['delivery_challan_type'] = 'required||deliverychallantype|#|lable_name:Delivery Challan Type';
		}
		
		if (array_key_exists("invoice_corresponding_type", $dataArr)) {
			$rules['invoice_corresponding_type'] = 'required||invoiecorresponding|#|lable_name:Invoice Corresponding Type';
		}

		if (array_key_exists("corresponding_document_number", $dataArr)) {
			$rules['corresponding_document_number'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Corresponding Document Number';
		}

		if (array_key_exists("corresponding_document_date", $dataArr)) {
			$rules['corresponding_document_date'] = 'required||date|#|lable_name:Corresponding Document Date';
		}

		if (array_key_exists("is_tax_payable", $dataArr)) {
			$rules['is_tax_payable'] = 'required||pattern:/^[' . $this->validateType['onlyzeroone'] . ']*$/|#|lable_name:Tax Reverse Charge';
		}

		if (array_key_exists("supply_place", $dataArr)) {
			$rules['supply_place'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Place Of Supply';
		}
		
		if( array_key_exists("description", $dataArr) ) {
            $rules['description'] = 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Description';
        }

		if (array_key_exists("advance_adjustment", $dataArr)) {
			$rules['advance_adjustment'] = 'required||pattern:/^[' . $this->validateType['onlyzeroone'] . ']*$/|#|lable_name:Advance Adjustment';
		}

		if (array_key_exists("refund_voucher_receipt", $dataArr)) {
			$rules['refund_voucher_receipt'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Receipt Voucher';
		}
		
		if (array_key_exists("billing_name", $dataArr)) {
			$rules['billing_name'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Billing Name';
		}
		
		if (array_key_exists("billing_company_name", $dataArr)) {
			$rules['billing_company_name'] = 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Billing Company Name';
		}

		if (array_key_exists("billing_address", $dataArr)) {
			$rules['billing_address'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Billing Address';
		}

		if (array_key_exists("billing_state", $dataArr)) {
			$rules['billing_state'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Billing State';
		}
		
		if (array_key_exists("billing_state_name", $dataArr)) {
			$rules['billing_state_name'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Billing State Name';
		}

		if (array_key_exists("billing_country", $dataArr)) {
			$rules['billing_country'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Billing Country';
		}

		if (array_key_exists("billing_vendor_type", $dataArr)) {
			$rules['billing_vendor_type'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Billing Vendor Type';
		}

		if (array_key_exists("billing_gstin_number", $dataArr)) {
			$rules['billing_gstin_number'] = 'pattern:/^' . $this->validateType['gstinnumber'] . '+$/||min:15||max:15|#|lable_name:Billing GSTIN Number';
		}

		if (array_key_exists("shipping_name", $dataArr)) {
			$rules['shipping_name'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Shipping Name';
		}
		
		if (array_key_exists("shipping_company_name", $dataArr)) {
			$rules['shipping_company_name'] = 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Shipping Company Name';
		}

		if (array_key_exists("shipping_address", $dataArr)) {
			$rules['shipping_address'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Shipping Address';
		}

		if (array_key_exists("shipping_state", $dataArr)) {
			$rules['shipping_state'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Shipping State';
		}

		if (array_key_exists("shipping_state_name", $dataArr)) {
			$rules['shipping_state_name'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Shipping State Name';
		}

		if (array_key_exists("shipping_country", $dataArr)) {
			$rules['shipping_country'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Shipping Country';
		}

		if (array_key_exists("shipping_vendor_type", $dataArr)) {
			$rules['shipping_vendor_type'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Shipping Vendor Type';
		}

		if (array_key_exists("shipping_gstin_number", $dataArr)) {
			$rules['shipping_gstin_number'] = 'pattern:/^' . $this->validateType['gstinnumber'] . '+$/||min:15||max:15|#|lable_name:Shipping GSTIN Number';
		}

		if (array_key_exists("item_id", $dataArr)) {
			$rules['item_id'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Item Id';
		}

        if (array_key_exists("item_name", $dataArr)) {
            $rules['item_name'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Item Name';
        }

        if (array_key_exists("item_hsncode", $dataArr)) {
            $rules['item_hsncode'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Item HSN Code';
        }

		if (array_key_exists("item_description", $dataArr)) {
            $rules['item_description'] = 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Item Description';
        }

		if (array_key_exists("item_quantity", $dataArr)) {
			$rules['item_quantity'] = 'required||numeric||decimal|#|lable_name:Item Quantity';
		}
		
		if (array_key_exists("item_unit", $dataArr)) {
            $rules['item_unit'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Item Unit Code';
        }

		if (array_key_exists("item_rate", $dataArr)) {
			$rules['item_rate'] = 'required||numeric||decimal|#|lable_name:Item Price';
		}

		if (array_key_exists("item_discount", $dataArr)) {
			$rules['item_discount'] = 'numeric||decimalzero|#|lable_name:Item Discount';
		}

		if (array_key_exists("advance_amount", $dataArr)) {
			$rules['advance_amount'] = 'numeric||decimalzero|#|lable_name:Advance Amount';
		}

        if (array_key_exists("item_taxablevalue", $dataArr)) {
            $rules['item_taxablevalue'] = 'required||numeric||decimalzero|#|lable_name:Taxable Amount of Item';
        }

		if (array_key_exists("cgst_rate", $dataArr)) {
			$rules['cgst_rate'] = 'numeric|#|lable_name:CGST Rate of Item';
		}

		if (array_key_exists("sgst_rate", $dataArr)) {
			$rules['sgst_rate'] = 'numeric|#|lable_name:SGST Rate of Item';
		}

		if (array_key_exists("igst_rate", $dataArr)) {
			$rules['igst_rate'] = 'numeric|#|lable_name:IGST Rate of Item';
		}

		if (array_key_exists("cess_rate", $dataArr)) {
			$rules['cess_rate'] = 'numeric|#|lable_name:CESS Rate of Item';
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
		$currentFinancialYear = $this->generateFinancialYear();

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

                $supply_type = isset($data['C']) ? $data['C'] : '';
				if ($supply_type != '' && strtoupper($supply_type) === 'NORMAL') {
                    $dataArray['supply_type'] = "normal";
                } else if ($supply_type != '' && strtoupper($supply_type) === 'REVERSE CHARGE') {
                    $dataArray['supply_type'] = "reversecharge";
                } else if ($supply_type != '' && strtoupper($supply_type) === 'TDS') {
                    $dataArray['supply_type'] = "tds";
                } else if ($supply_type != '' && strtoupper($supply_type) === 'TCS') {
                    $dataArray['supply_type'] = "tcs";
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Supply Type.");
                }

				if(isset($dataArray['supply_type']) && $dataArray['supply_type'] == "tcs") {
					$dataArray['ecommerce_gstin_number'] = isset($data['D']) ? $data['D'] : '';
					$dataArray['ecommerce_vendor_code'] = isset($data['E']) ? $data['E'] : '';
				}

                $supply_place = isset($data['F']) ? $data['F'] : '';
                if ($supply_place != '') {

                    $supply_state_data = $this->getStateDetailByStateNameCode($supply_place);
                    if ($supply_state_data['status'] === "success") {
                        $dataArray['supply_place'] = $supply_state_data['data']->state_id;
                    } else {
                        $errorflag = true;
                        array_push($currentItemError, "Invalid Place Of Supply.");
                    }
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Place Of Supply.");
                }

                $advance_adjustment = isset($data['G']) ? $data['G'] : '';
                if ($advance_adjustment != '' && strtoupper($advance_adjustment) === 'Y') {
                    $dataArray['advance_adjustment'] = 1;
                } else if ($advance_adjustment != '' && strtoupper($advance_adjustment) === 'N') {
                    $dataArray['advance_adjustment'] = 0;
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Advance Adjustment.");
                }

				if(isset($dataArray['advance_adjustment']) && $dataArray['advance_adjustment'] == 1) {
					
					$receipt_voucher_serial = isset($data['H']) ? $data['H'] : '';
					$dataReceiptVoucherArrs = $this->get_row("select invoice_id, serial_number, reference_number, invoice_date, supply_place, is_canceled from ".$this->tableNames['client_invoice']." where 1=1 AND reference_number = '".$receipt_voucher_serial."' AND invoice_type = 'receiptvoucherinvoice' AND is_canceled='0' AND status='1' AND is_deleted='0' AND financial_year = '".$currentFinancialYear."' AND added_by = ".$this->sanitize($_SESSION['user_detail']['user_id']));
					if (!empty($dataReceiptVoucherArrs) && isset($dataReceiptVoucherArrs->invoice_id)) {
						$dataArray['receipt_voucher_number'] = $dataReceiptVoucherArrs->invoice_id;
                    } else {
                        $errorflag = true;
                        array_push($currentItemError, "Invalid Receipt Voucher.");
                    }
				}

                $dataArray['billing_name'] = isset($data['I']) ? $data['I'] : '';
				$dataArray['billing_company_name'] = isset($data['J']) ? $data['J'] : '';
                $dataArray['billing_address'] = isset($data['K']) ? $data['K'] : '';

				$billing_state = isset($data['L']) ? $data['L'] : '';
                if ($billing_state != '') {

                    $billing_state_data = $this->getStateDetailByStateNameCode($billing_state);
                    if ($billing_state_data['status'] === "success") {
						$dataArray['billing_state'] = $billing_state_data['data']->state_id;
						$dataArray['billing_state_name'] = $billing_state_data['data']->state_name;
                    } else {
                        $errorflag = true;
                        array_push($currentItemError, "Invalid Billing State.");
                    }
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Billing State.");
                }

				$billing_country = isset($data['M']) ? $data['M'] : '';
				if ($billing_country != '') {

                    $billing_country_data = $this->getCountryDetailByCountryCode($billing_country);
                    if ($billing_country_data['status'] === "success") {
						$dataArray['billing_country'] = $billing_country_data['data']->id;
                    } else {
                        $errorflag = true;
                        array_push($currentItemError, "Invalid Billing Country.");
                    }
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Billing Country.");
                }

				$billing_vendor_type = isset($data['N']) ? $data['N'] : '';
				if ($billing_vendor_type != '') {

					$dataVendorNameArrs = $this->get_row("select vendor_id, vendor_name from ".$this->tableNames['vendor_type']." where 1=1 AND UPPER(vendor_name) = '".strtoupper($billing_vendor_type)."' AND status='1' AND is_deleted='0'");
                    if (!empty($dataVendorNameArrs) && isset($dataVendorNameArrs->vendor_id)) {
						$dataArray['billing_vendor_type'] = $dataVendorNameArrs->vendor_id;
                    } else {
                        $errorflag = true;
                        array_push($currentItemError, "Invalid Billing Vendor Type.");
                    }
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Billing Vendor Type.");
                }

				$dataArray['billing_gstin_number'] = isset($data['O']) ? $data['O'] : '';

				$dataArray['shipping_name'] = isset($data['P']) ? $data['P'] : '';
				$dataArray['shipping_company_name'] = isset($data['Q']) ? $data['Q'] : '';
                $dataArray['shipping_address'] = isset($data['R']) ? $data['R'] : '';

				$shipping_state = isset($data['S']) ? $data['S'] : '';
                if ($shipping_state != '') {

                    $shipping_state_data = $this->getStateDetailByStateNameCode($shipping_state);
                    if ($shipping_state_data['status'] === "success") {
						$dataArray['shipping_state'] = $shipping_state_data['data']->state_id;
						$dataArray['shipping_state_name'] = $shipping_state_data['data']->state_name;
                    } else {
                        $errorflag = true;
                        array_push($currentItemError, "Invalid Shipping State.");
                    }
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Shipping State.");
                }

				$shipping_country = isset($data['T']) ? $data['T'] : '';
				if ($shipping_country != '') {

                    $shipping_country_data = $this->getCountryDetailByCountryCode($shipping_country);
                    if ($shipping_country_data['status'] === "success") {
						$dataArray['shipping_country'] = $shipping_country_data['data']->id;
                    } else {
                        $errorflag = true;
                        array_push($currentItemError, "Invalid Shipping Country.");
                    }
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Shipping Country.");
                }

				$shipping_vendor_type = isset($data['U']) ? $data['U'] : '';
				if ($shipping_vendor_type != '') {

					$dataVendorNameArrs = $this->get_row("select vendor_id, vendor_name from ".$this->tableNames['vendor_type']." where 1=1 AND UPPER(vendor_name) = '".strtoupper($shipping_vendor_type)."' AND status='1' AND is_deleted='0'");
                    if (!empty($dataVendorNameArrs) && isset($dataVendorNameArrs->vendor_id)) {
						$dataArray['shipping_vendor_type'] = $dataVendorNameArrs->vendor_id;
                    } else {
                        $errorflag = true;
                        array_push($currentItemError, "Invalid Shipping Vendor Type.");
                    }
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Shipping Vendor Type.");
                }

				$dataArray['shipping_gstin_number'] = isset($data['V']) ? $data['V'] : '';

                $item_name = isset($data['W']) ? trim($data['W']) : '';
                $item_hsncode = isset($data['X']) ? trim($data['X']) : '';

				$dataArray['item_description'] = isset($data['Y']) ? trim($data['Y']) : '';
				$item_description = $dataArray['item_description'];
				
				$applicable_tax = isset($data['Z']) ? $data['Z'] : '';
				if ($applicable_tax != '' && strtoupper($applicable_tax) == 'NON GST') {
					$dataArray['is_applicable'] = '1';
					$is_applicable = '1';
				} else if ($applicable_tax != '' && strtoupper($applicable_tax) == 'EXEMPTED') {
					$dataArray['is_applicable'] = '2';
					$is_applicable = '2';
				} else {
					$dataArray['is_applicable'] = '0';
					$is_applicable = '0';
				}

				$dataArray['item_quantity'] = isset($data['AA']) ? round($data['AA'], 2) : '';

				$dataArray['item_unit'] = isset($data['AB']) ? $data['AB'] : '';
				$item_unit =  $dataArray['item_unit'];
				
                $dataArray['item_rate'] = isset($data['AC']) ? round($data['AC'], 2) : 0.00;
				$item_rate = round($dataArray['item_rate'], 2);

                $dataArray['item_discount'] = isset($data['AD']) ? round($data['AD'], 2) : 0.00;
                $dataArray['advance_amount'] = isset($data['AE']) ? round($data['AE'], 2) : 0.00;
				$dataArray['cgst_rate'] = isset($data['AF']) ? round($data['AF'], 3) : 0.000;
				$dataArray['sgst_rate'] = isset($data['AG']) ? round($data['AG'], 3) : 0.000;
				$dataArray['igst_rate'] = isset($data['AH']) ? round($data['AH'], 3) : 0.000;
				$dataArray['cess_rate'] = isset($data['AI']) ? round($data['AI'], 3) : 0.000;
				
				if(!empty($item_name) && !empty($item_hsncode)) {

                    $checkClientMasterItem = $this->get_row("select cm.item_id, cm.is_applicable, cm.item_name, cm.unit_price, cm.item_description, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, m.igst_tax_rate, m.csgt_tax_rate, m.sgst_tax_rate, m.cess_tax_rate, cm.item_unit from " . $this->tableNames['client_master_item'] . " as cm, " . $this->tableNames['item'] . " as m where 1=1 AND cm.item_category = m.item_id AND cm.item_name = '" . $item_name . "' && m.hsn_code = '" . $item_hsncode . "' AND cm.is_deleted='0' AND cm.added_by = '" . $this->sanitize($_SESSION['user_detail']['user_id']) . "'");
					if (!empty($checkClientMasterItem)) {

						$dataArray['item_id'] = $checkClientMasterItem->item_id;
                        $dataArray['item_name'] = $item_name;
                        $dataArray['item_hsncode'] = $item_hsncode;
                    } else {

						$masterItem = $this->get_row("select item_id, item_name, hsn_code from " . $this->tableNames['item'] . " where hsn_code='".$item_hsncode."' and is_deleted='0' AND status='1'");						
						if(!empty($masterItem)) {

							$masterUnit = $this->get_row("select unit_id from " . $this->tableNames['unit'] . " as u where u.unit_code='".$item_unit."' and u.is_deleted='0' AND u.status = '1'");
							if(!empty($masterUnit)) {
								$master_unit_id = $masterUnit->unit_id;
							} else {
								
								$masterNUnit = $this->get_row("select unit_id from " . $this->tableNames['unit'] . " as u where u.unit_code='NA' and u.is_deleted='0' AND u.status = '1'");
								if(!empty($masterNUnit)) {
									$master_unit_id = $masterNUnit->unit_id;
								} else {
									$master_unit_id = 0;
								}
							}

							$dataInsertItemArray['item_name'] = $item_name;
							$dataInsertItemArray['item_category'] = $masterItem->item_id;
							$dataInsertItemArray['item_description'] = $item_description;
							$dataInsertItemArray['is_applicable'] = $is_applicable;
							$dataInsertItemArray['unit_price'] = $item_rate;
							$dataInsertItemArray['cgst_tax_rate'] = $dataArray['cgst_rate'];
							$dataInsertItemArray['sgst_tax_rate'] = $dataArray['sgst_rate'];
							$dataInsertItemArray['igst_tax_rate'] = $dataArray['igst_rate'];
							$dataInsertItemArray['cess_tax_rate'] = $dataArray['cess_rate'];
							$dataInsertItemArray['item_unit'] = $master_unit_id;
							$dataInsertItemArray['status'] = '1';
							$dataInsertItemArray['added_by'] = $this->sanitize($_SESSION['user_detail']['user_id']);
							$dataInsertItemArray['added_date'] = date('Y-m-d H:i:s');

							if ($this->insert($this->tableNames['client_master_item'], $dataInsertItemArray)) {

								$iteminsertid = $this->getInsertID();
								$dataArray['item_id'] = $iteminsertid;
								$dataArray['item_name'] = $item_name;
								$dataArray['item_hsncode'] = $masterItem->hsn_code;
							} else {
								$errorflag = true;
								array_push($currentItemError, $this->getValMsg('failed'));
							}
						} else {
							$errorflag = true;
							array_push($currentItemError, "Description of Goods and HSN Code should be valid.");
						}
                    }
                } else {
					$errorflag = true;
					array_push($currentItemError, "Description of Goods and HSN Code should be valid.");
                }

				/* get current user data */
                $dataCurrentUserArr = $this->getUserDetailsById($this->sanitize($_SESSION['user_detail']['user_id']));

				if(isset($dataArray['supply_place']) && $dataCurrentUserArr['data']->kyc->state_id === $dataArray['supply_place']) {

					if($dataArray['cgst_rate'] != $dataArray['sgst_rate']) {
						$errorflag = true;
						array_push($currentItemError, "CGST and SGST rate should be same for item number.");
					}
				}

				/* check reference number */
				$referenceStatus = $this->checkReferenceNumberExist($dataArray['reference_number'], $this->sanitize($_SESSION['user_detail']['user_id']));
				if($referenceStatus == true) {
					$errorflag = true;
					array_push($currentItemError, "You have already used this reference number.");
				}

				/* Invoice Description */
				$dataArray['description'] = isset($data['AJ']) ? $data['AJ'] : '';

                $invoiceErrors = $this->validateClientInvoiceExcel($dataArray);
                if ($invoiceErrors !== true || !empty($currentItemError)) {

                    $errorflag = true;
                    if ($invoiceErrors === true) {
                        $invoiceErrors = array();
                    }
                    $invoiceErrors = array_merge($invoiceErrors, $currentItemError);
                    $invoiceErrors = implode(", ", $invoiceErrors);
                    $objPHPExcel->getActiveSheet()->SetCellValue('AK' . $rowKey, $invoiceErrors);
                }
				
				if ($errorflag === false) {

					/* create invoice array */
					$arrayKey = $dataArray['reference_number'];

					$invoiceArray[$arrayKey]['invoice_type'] = 'taxinvoice';
					$invoiceArray[$arrayKey]['invoice_nature'] = 'salesinvoice';
					$invoiceArray[$arrayKey]['reference_number'] = $dataArray['reference_number'];
					$invoiceArray[$arrayKey]['company_name'] = $dataCurrentUserArr['data']->kyc->name;
					$invoiceArray[$arrayKey]['company_address'] = $dataCurrentUserArr['data']->kyc->full_address;
					$invoiceArray[$arrayKey]['company_email'] = $dataCurrentUserArr['data']->kyc->email;
					$invoiceArray[$arrayKey]['company_phone_number'] = $dataCurrentUserArr['data']->kyc->phone_number;
					$invoiceArray[$arrayKey]['company_state'] = $dataCurrentUserArr['data']->kyc->state_id;
					$invoiceArray[$arrayKey]['gstin_number'] = $dataCurrentUserArr['data']->kyc->gstin_number;
					$invoiceArray[$arrayKey]['invoice_date'] = $dataArray['invoice_date'];
					$invoiceArray[$arrayKey]['supply_type'] = $dataArray['supply_type'];

					if($dataArray['supply_type'] == "tcs") {
						$invoiceArray[$arrayKey]['ecommerce_gstin_number'] = $dataArray['ecommerce_gstin_number'];
						$invoiceArray[$arrayKey]['ecommerce_vendor_code'] = $dataArray['ecommerce_vendor_code'];
					}

					$invoiceArray[$arrayKey]['supply_place'] = $dataArray['supply_place'];
					$invoiceArray[$arrayKey]['billing_name'] = $dataArray['billing_name'];
					$invoiceArray[$arrayKey]['billing_company_name'] = $dataArray['billing_company_name'];
					$invoiceArray[$arrayKey]['billing_address'] = $dataArray['billing_address'];
					$invoiceArray[$arrayKey]['billing_state'] = $dataArray['billing_state'];
					$invoiceArray[$arrayKey]['billing_state_name'] = $dataArray['billing_state_name'];
					$invoiceArray[$arrayKey]['billing_country'] = $dataArray['billing_country'];
					$invoiceArray[$arrayKey]['billing_vendor_type'] = $dataArray['billing_vendor_type'];
					$invoiceArray[$arrayKey]['billing_gstin_number'] = $dataArray['billing_gstin_number'];
					$invoiceArray[$arrayKey]['shipping_name'] = $dataArray['shipping_name'];
					$invoiceArray[$arrayKey]['shipping_company_name'] = $dataArray['shipping_company_name'];
					$invoiceArray[$arrayKey]['shipping_address'] = $dataArray['shipping_address'];
					$invoiceArray[$arrayKey]['shipping_state'] = $dataArray['shipping_state'];
					$invoiceArray[$arrayKey]['shipping_state_name'] = $dataArray['shipping_state_name'];
					$invoiceArray[$arrayKey]['shipping_country'] = $dataArray['shipping_country'];
					$invoiceArray[$arrayKey]['shipping_vendor_type'] = $dataArray['shipping_vendor_type'];
					$invoiceArray[$arrayKey]['shipping_gstin_number'] = $dataArray['shipping_gstin_number'];

					$invoiceArray[$arrayKey]['advance_adjustment'] = $dataArray['advance_adjustment'];				
					if($dataArray['advance_adjustment'] == 1) {
						$invoiceArray[$arrayKey]['receipt_voucher_number'] = $dataArray['receipt_voucher_number'];
					} else {
						$invoiceArray[$arrayKey]['receipt_voucher_number'] = 0;
					}

					$invoiceArray[$arrayKey]['description'] = $dataArray['description'];

					//items
					$invoiceItemArray['item_id'] = $dataArray['item_id'];
					$invoiceItemArray['item_name'] = $dataArray['item_name'];
					$invoiceItemArray['item_hsncode'] = $dataArray['item_hsncode'];
					$invoiceItemArray['item_description'] = $dataArray['item_description'];
					$invoiceItemArray['is_applicable'] = $dataArray['is_applicable'];
					$invoiceItemArray['item_quantity'] = $dataArray['item_quantity'];
					$invoiceItemArray['item_unit'] = $dataArray['item_unit'];
					$invoiceItemArray['item_unit_price'] = $dataArray['item_rate'];
					$invoiceItemArray['item_discount'] = $dataArray['item_discount'];

					if($dataArray['advance_adjustment'] == 1) {
						$invoiceItemArray['advance_amount'] = $dataArray['advance_amount'];
					} else {
						$invoiceItemArray['advance_amount'] = 0.00;
					}

					$invoiceItemArray['cgst_rate'] = $dataArray['cgst_rate'];
					$invoiceItemArray['sgst_rate'] = $dataArray['sgst_rate'];
					$invoiceItemArray['igst_rate'] = $dataArray['igst_rate'];
					$invoiceItemArray['cess_rate'] = $dataArray['cess_rate'];

					$invoiceArray[$arrayKey]['items'][] = $invoiceItemArray;
				}
            }

            if ($errorflag === true) {

                $objPHPExcel->getActiveSheet()->SetCellValue('AK1', "Error Information");
                $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
                $objWriter->save($invoice_excel_dir_path);
                $this->setError($this->validationMessage['excelerror']);
                $resultArray = array("status" => "error", "excelurl" => $invoice_excel_url_path);
                return json_encode($resultArray);
            } else {

                foreach ($invoiceArray as $invoiceRow) {

                    $invoiceItemArray = array();
                    $invoiceTotalAmount = 0.00;
					$consolidateRate = 0.00;

                    foreach ($invoiceRow['items'] as $invoiceInnerRow) {

						$itemUnitPrice = (float) $invoiceInnerRow['item_unit_price'];
						$invoiceItemQuantity = (float) $invoiceInnerRow['item_quantity'];
						$invoiceItemDiscount = (float) $invoiceInnerRow['item_discount'];
						$invoiceItemAdvanceAmount = (float) $invoiceInnerRow['advance_amount'];

						$invoiceItemTotal = $invoiceItemQuantity * $itemUnitPrice;
						$invoiceItemDiscountAmount = ($invoiceItemDiscount / 100) * $invoiceItemTotal;
						$invoiceItemReduceAmount = $invoiceItemAdvanceAmount + $invoiceItemDiscountAmount;
						$invoiceItemTaxableAmount = $invoiceItemTotal - $invoiceItemReduceAmount;

						if($invoiceRow['company_state'] === $invoiceRow['supply_place']) {

							$itemCSGTTax = (float)$invoiceInnerRow['cgst_rate'];
							$itemSGSTTax = (float)$invoiceInnerRow['sgst_rate'];
							$itemIGSTTax = 0.00;
							$itemCESSTax = (float)$invoiceInnerRow['cess_rate'];
							$consolidateRate = $itemCSGTTax + $itemSGSTTax;

							$invoiceItemCSGTTaxAmount = ($itemCSGTTax/100) * $invoiceItemTaxableAmount;
							$invoiceItemSGSTTaxAmount = ($itemSGSTTax/100) * $invoiceItemTaxableAmount;
							$invoiceItemIGSTTaxAmount = 0.00;
							$invoiceItemCESSTaxAmount = ($itemCESSTax/100) * $invoiceItemTaxableAmount;
						} else {
							
							$itemCSGTTax = 0.00;
							$itemSGSTTax = 0.00;
							$itemIGSTTax = (float)$invoiceInnerRow['igst_rate'];
							$itemCESSTax = (float)$invoiceInnerRow['cess_rate'];
							$consolidateRate = $itemIGSTTax;

							$invoiceItemCSGTTaxAmount = 0.00;
							$invoiceItemSGSTTaxAmount = 0.00;
							$invoiceItemIGSTTaxAmount = ($itemIGSTTax/100) * $invoiceItemTaxableAmount;
							$invoiceItemCESSTaxAmount = ($itemCESSTax/100) * $invoiceItemTaxableAmount;
						}

						if ($invoiceRow['supply_type'] == "reversecharge") {

							$invoiceItemTotalAmount = $invoiceItemTaxableAmount;
							$invoiceTotalAmount += $invoiceItemTotalAmount;
						} else {

							$invoiceItemTotalAmount = ($invoiceItemTaxableAmount + $invoiceItemCSGTTaxAmount + $invoiceItemSGSTTaxAmount + $invoiceItemIGSTTaxAmount + $invoiceItemCESSTaxAmount);
							$invoiceTotalAmount += $invoiceItemTotalAmount;
						}

						$ItemArray = array(
							"item_id" => $invoiceInnerRow['item_id'],
							"item_name" => $invoiceInnerRow['item_name'],
							"item_hsncode" => $invoiceInnerRow['item_hsncode'],
							"item_description" => $invoiceInnerRow['item_description'],
							"is_applicable" => $invoiceInnerRow['is_applicable'],
							"item_quantity" => $invoiceItemQuantity,
							"item_unit" => $invoiceInnerRow['item_unit'],
							"item_unit_price" => $itemUnitPrice,
							"subtotal" => round($invoiceItemTotal, 2),
							"discount" => $invoiceItemDiscount,
							"advance_amount" => $invoiceItemAdvanceAmount,
							"taxable_subtotal" => round($invoiceItemTaxableAmount, 2),
							"cgst_rate" => $itemCSGTTax,
							"cgst_amount" => round($invoiceItemCSGTTaxAmount, 2),
							"sgst_rate" => $itemSGSTTax,
							"sgst_amount" => round($invoiceItemSGSTTaxAmount, 2),
							"igst_rate" => $itemIGSTTax,
							"igst_amount" => round($invoiceItemIGSTTaxAmount, 2),
							"cess_rate" => $itemCESSTax,
							"cess_amount" => round($invoiceItemCESSTaxAmount, 2),
							"consolidate_rate" => $consolidateRate,
							"total" => round($invoiceItemTotalAmount, 2),
							"status" => 1,
							"added_by" => $this->sanitize($_SESSION['user_detail']['user_id']),
							"added_date" => date('Y-m-d H:i:s')
						);

						array_push($invoiceItemArray, $ItemArray);
                    }

                    if (!empty($invoiceItemArray) && count($invoiceItemArray) > 0) {

                        $InsertArray['invoice_type'] = $invoiceRow['invoice_type'];
						$InsertArray['invoice_nature'] = $invoiceRow['invoice_nature'];
                        $InsertArray['reference_number'] = $invoiceRow['reference_number'];
                        $InsertArray['serial_number'] = $this->generateInvoiceNumber($this->sanitize($_SESSION['user_detail']['user_id']));
                        $InsertArray['company_name'] = $invoiceRow['company_name'];
                        $InsertArray['company_address'] = $invoiceRow['company_address'];
						$InsertArray['company_email'] = $invoiceRow['company_email'];
						$InsertArray['company_phone_number'] = $invoiceRow['company_phone_number'];
                        $InsertArray['company_state'] = $invoiceRow['company_state'];
                        $InsertArray['gstin_number'] = $invoiceRow['gstin_number'];
                        $InsertArray['invoice_date'] = $invoiceRow['invoice_date'];
                        $InsertArray['supply_place'] = $invoiceRow['supply_place'];
						$InsertArray['supply_type'] = $invoiceRow['supply_type'];

						if($invoiceRow['supply_type'] == "tcs") {
							$InsertArray['ecommerce_gstin_number'] = $invoiceRow['ecommerce_gstin_number'];
							$InsertArray['ecommerce_vendor_code'] = $invoiceRow['ecommerce_vendor_code'];
						}

                        $InsertArray['billing_name'] = $invoiceRow['billing_name'];
						$InsertArray['billing_company_name'] = $invoiceRow['billing_company_name'];
                        $InsertArray['billing_address'] = $invoiceRow['billing_address'];
                        $InsertArray['billing_state'] = $invoiceRow['billing_state'];
                        $InsertArray['billing_state_name'] = $invoiceRow['billing_state_name'];
						$InsertArray['billing_country'] = $invoiceRow['billing_country'];
						$InsertArray['billing_vendor_type'] = $invoiceRow['billing_vendor_type'];
                        $InsertArray['billing_gstin_number'] = $invoiceRow['billing_gstin_number'];
                        $InsertArray['shipping_name'] = $invoiceRow['shipping_name'];
                        $InsertArray['shipping_company_name'] = $invoiceRow['shipping_company_name'];
						$InsertArray['shipping_address'] = $invoiceRow['shipping_address'];
                        $InsertArray['shipping_state'] = $invoiceRow['shipping_state'];
                        $InsertArray['shipping_state_name'] = $invoiceRow['shipping_state_name'];
						$InsertArray['shipping_country'] = $invoiceRow['shipping_country'];
						$InsertArray['shipping_vendor_type'] = $invoiceRow['shipping_vendor_type'];
                        $InsertArray['shipping_gstin_number'] = $invoiceRow['shipping_gstin_number'];

						$InsertArray['advance_adjustment'] = $invoiceRow['advance_adjustment'];
						if($invoiceRow['advance_adjustment'] == 1) {
							$InsertArray['receipt_voucher_number'] = $invoiceRow['receipt_voucher_number'];
						} else {
							$InsertArray['receipt_voucher_number'] = 0;
						}

						$InsertArray['description'] = $invoiceRow['description'];						
                        $InsertArray['invoice_total_value'] = number_format($invoiceTotalAmount, 2, '.', '');
                        $InsertArray['financial_year'] = $this->generateFinancialYear();
                        $InsertArray['status'] = 1;
						$InsertArray['created_from'] = 'E';
                        $InsertArray['added_by'] = $_SESSION['user_detail']['user_id'];
                        $InsertArray['added_date'] = date('Y-m-d H:i:s');

						if($invoiceRow['supply_type'] == "reversecharge") {
							$InsertArray['is_tax_payable'] = "1";
						}

                        if ($this->insert($this->tableNames['client_invoice'], $InsertArray)) {

                            $insertid = $this->getInsertID();
                            $this->logMsg("Sales Tax Invoice Added. ID : " . $insertid . ".","client_create_invoice");

                            $processedInvoiceItemArray = array();
                            foreach ($invoiceItemArray as $itemArr) {

                                $itemArr['invoice_id'] = $insertid;
                                array_push($processedInvoiceItemArray, $itemArr);
                            }

                            if ($this->insertMultiple($this->tableNames['client_invoice_item'], $processedInvoiceItemArray)) {

                                $iteminsertid = $this->getInsertID();
                                $this->logMsg("Sales Tax Invoice Item Added. ID : " . $iteminsertid . ".","client_create_invoice");
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
		$currentFinancialYear = $this->generateFinancialYear();

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
				if ($invoice_type != '' && strtoupper($invoice_type) === 'DEEMED EXPORT') {
                    $dataArray['invoice_type'] = "deemedexportinvoice";
                } else if ($invoice_type != '' && strtoupper($invoice_type) === 'EXPORT') {
					$dataArray['invoice_type'] = "exportinvoice";
                } else if ($invoice_type != '' && strtoupper($invoice_type) === 'SEZ') {
                    $dataArray['invoice_type'] = "sezunitinvoice";
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Invoice Type.");
                }

				$supply_meant = isset($data['C']) ? $data['C'] : '';
				if ($supply_meant != '' && strtoupper($supply_meant) === 'WITH PAYMENT') {
                    $dataArray['export_supply_meant'] = "withpayment";
                } else if ($supply_meant != '' && strtoupper($supply_meant) === 'WITHOUT PAYMENT') {
                    $dataArray['export_supply_meant'] = "withoutpayment";
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Export Supply Meant.");
                }

				$dataArray['invoice_date'] = isset($data['D']) ? $data['D'] : '';

				$supply_place = isset($data['E']) ? $data['E'] : '';
                if ($supply_place != '') {

                    $supply_state_data = $this->getStateDetailByStateNameCode($supply_place);
                    if ($supply_state_data['status'] === "success") {
                        $dataArray['supply_place'] = $supply_state_data['data']->state_id;
                    } else {
                        $errorflag = true;
                        array_push($currentItemError, "Invalid Place Of Supply.");
                    }
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Place Of Supply.");
                }

                $advance_adjustment = isset($data['F']) ? $data['F'] : '';
                if ($advance_adjustment != '' && strtoupper($advance_adjustment) === 'Y') {
                    $dataArray['advance_adjustment'] = 1;
                } else if ($advance_adjustment != '' && strtoupper($advance_adjustment) === 'N') {
                    $dataArray['advance_adjustment'] = 0;
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Advance Adjustment.");
                }

				if(isset($dataArray['advance_adjustment']) && $dataArray['advance_adjustment'] == 1) {

					$receipt_voucher_serial = isset($data['G']) ? $data['G'] : '';
					$dataReceiptVoucherArrs = $this->get_row("select invoice_id, serial_number, reference_number, invoice_date, supply_place, is_canceled from ".$this->tableNames['client_invoice']." where 1=1 AND reference_number = '".$receipt_voucher_serial."' AND invoice_type = 'receiptvoucherinvoice' AND is_canceled='0' AND status='1' AND is_deleted='0' AND financial_year = '".$currentFinancialYear."' AND added_by = ".$this->sanitize($_SESSION['user_detail']['user_id']));
					if (!empty($dataReceiptVoucherArrs) && isset($dataReceiptVoucherArrs->invoice_id)) {
						$dataArray['receipt_voucher_number'] = $dataReceiptVoucherArrs->invoice_id;
                    } else {
                        $errorflag = true;
                        array_push($currentItemError, "Invalid Receipt Voucher.");
                    }
				}

                $dataArray['billing_name'] = isset($data['H']) ? $data['H'] : '';
				$dataArray['billing_company_name'] = isset($data['I']) ? $data['I'] : '';
                $dataArray['billing_address'] = isset($data['J']) ? $data['J'] : '';

				$billing_state = isset($data['K']) ? $data['K'] : '';
                if ($billing_state != '') {

                    $billing_state_data = $this->getStateDetailByStateNameCode($billing_state);
                    if ($billing_state_data['status'] === "success") {
						$dataArray['billing_state'] = $billing_state_data['data']->state_id;
						$dataArray['billing_state_name'] = $billing_state_data['data']->state_name;
                    } else {
                        $errorflag = true;
                        array_push($currentItemError, "Invalid Billing State.");
                    }
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Billing State.");
                }

				$billing_country = isset($data['L']) ? $data['L'] : '';
				if ($billing_country != '') {

                    $billing_country_data = $this->getCountryDetailByCountryCode($billing_country);
                    if ($billing_country_data['status'] === "success") {
						$dataArray['billing_country'] = $billing_country_data['data']->id;
                    } else {
                        $errorflag = true;
                        array_push($currentItemError, "Invalid Billing Country.");
                    }
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Billing Country.");
                }

				$billing_vendor_type = isset($data['M']) ? $data['M'] : '';
				if ($billing_vendor_type != '') {

					$dataVendorNameArrs = $this->get_row("select vendor_id, vendor_name from ".$this->tableNames['vendor_type']." where 1=1 AND UPPER(vendor_name) = '".strtoupper($billing_vendor_type)."' AND status='1' AND is_deleted='0'");
                    if (!empty($dataVendorNameArrs) && isset($dataVendorNameArrs->vendor_id)) {
						$dataArray['billing_vendor_type'] = $dataVendorNameArrs->vendor_id;
                    } else {
                        $errorflag = true;
                        array_push($currentItemError, "Invalid Billing Vendor Type.");
                    }
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Billing Vendor Type.");
                }

				$dataArray['billing_gstin_number'] = isset($data['N']) ? $data['N'] : '';

				$dataArray['shipping_name'] = isset($data['O']) ? $data['O'] : '';
				$dataArray['shipping_company_name'] = isset($data['P']) ? $data['P'] : '';
                $dataArray['shipping_address'] = isset($data['Q']) ? $data['Q'] : '';

				$shipping_state = isset($data['R']) ? $data['R'] : '';
                if ($shipping_state != '') {

                    $shipping_state_data = $this->getStateDetailByStateNameCode($shipping_state);
                    if ($shipping_state_data['status'] === "success") {
						$dataArray['shipping_state'] = $shipping_state_data['data']->state_id;
						$dataArray['shipping_state_name'] = $shipping_state_data['data']->state_name;
                    } else {
                        $errorflag = true;
                        array_push($currentItemError, "Invalid Shipping State.");
                    }
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Shipping State.");
                }

				$shipping_country = isset($data['S']) ? $data['S'] : '';
				if ($shipping_country != '') {

                    $shipping_country_data = $this->getCountryDetailByCountryCode($shipping_country);
                    if ($shipping_country_data['status'] === "success") {
						$dataArray['shipping_country'] = $shipping_country_data['data']->id;
                    } else {
                        $errorflag = true;
                        array_push($currentItemError, "Invalid Shipping Country.");
                    }
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Shipping Country.");
                }

				$shipping_vendor_type = isset($data['T']) ? $data['T'] : '';
				if ($shipping_vendor_type != '') {

					$dataVendorNameArrs = $this->get_row("select vendor_id, vendor_name from ".$this->tableNames['vendor_type']." where 1=1 AND UPPER(vendor_name) = '".strtoupper($shipping_vendor_type)."' AND status='1' AND is_deleted='0'");
                    if (!empty($dataVendorNameArrs) && isset($dataVendorNameArrs->vendor_id)) {
						$dataArray['shipping_vendor_type'] = $dataVendorNameArrs->vendor_id;
                    } else {
                        $errorflag = true;
                        array_push($currentItemError, "Invalid Shipping Vendor Type.");
                    }
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Shipping Vendor Type.");
                }

				$dataArray['shipping_gstin_number'] = isset($data['U']) ? $data['U'] : '';

				if(isset($dataArray['invoice_type']) && $dataArray['invoice_type'] == "exportinvoice") {

					$dataArray['export_bill_number'] = isset($data['V']) ? $data['V'] : '';
					$dataArray['export_bill_port_code'] = isset($data['W']) ? $data['W'] : '';
					$dataArray['export_bill_date'] = isset($data['X']) ? $data['X'] : '';
				}

                $item_name = isset($data['Y']) ? trim($data['Y']) : '';
                $item_hsncode = isset($data['Z']) ? trim($data['Z']) : '';

				$dataArray['item_description'] = isset($data['AA']) ? trim($data['AA']) : '';
				$item_description = $dataArray['item_description'];
				
				$applicable_tax = isset($data['AB']) ? $data['AB'] : '';
				if ($applicable_tax != '' && strtoupper($applicable_tax) == 'NON GST') {
					$dataArray['is_applicable'] = '1';
					$is_applicable = '1';
				} else if ($applicable_tax != '' && strtoupper($applicable_tax) == 'EXEMPTED') {
					$dataArray['is_applicable'] = '2';
					$is_applicable = '2';
				} else {
					$dataArray['is_applicable'] = '0';
					$is_applicable = '0';
				}

				$dataArray['item_quantity'] = isset($data['AC']) ? round($data['AC'], 2) : '';

				$dataArray['item_unit'] = isset($data['AD']) ? $data['AD'] : '';
				$item_unit =  $dataArray['item_unit'];

                $dataArray['item_rate'] = isset($data['AE']) ? round($data['AE'], 2) : 0.00;
				$item_rate = round($dataArray['item_rate'], 2);

                $dataArray['item_discount'] = isset($data['AF']) ? round($data['AF'], 2) : 0.00;
                $dataArray['advance_amount'] = isset($data['AG']) ? round($data['AG'], 2) : 0.00;
				$dataArray['igst_rate'] = isset($data['AH']) ? round($data['AH'], 3) : 0.000;
				$dataArray['cess_rate'] = isset($data['AI']) ? round($data['AI'], 3) : 0.000;

				if(!empty($item_name) && !empty($item_hsncode)) {

                    $checkClientMasterItem = $this->get_row("select cm.item_id, cm.is_applicable, cm.item_name, cm.unit_price, cm.item_description, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, m.igst_tax_rate, m.csgt_tax_rate, m.sgst_tax_rate, m.cess_tax_rate, cm.item_unit from " . $this->tableNames['client_master_item'] . " as cm, " . $this->tableNames['item'] . " as m where 1=1 AND cm.item_category = m.item_id AND cm.item_name = '" . $item_name . "' && m.hsn_code = '" . $item_hsncode . "' AND cm.is_deleted='0' AND cm.added_by = '" . $this->sanitize($_SESSION['user_detail']['user_id']) . "'");
					if (!empty($checkClientMasterItem)) {

						$dataArray['item_id'] = $checkClientMasterItem->item_id;
                        $dataArray['item_name'] = $item_name;
                        $dataArray['item_hsncode'] = $item_hsncode;
                    } else {

						$masterItem = $this->get_row("select item_id, item_name, hsn_code from " . $this->tableNames['item'] . " where hsn_code='".$item_hsncode."' and is_deleted='0' AND status='1'");						
						if(!empty($masterItem)) {

							$masterUnit = $this->get_row("select unit_id from " . $this->tableNames['unit'] . " as u where u.unit_code='".$item_unit."' and u.is_deleted='0' AND u.status = '1'");
							if(!empty($masterUnit)) {
								$master_unit_id = $masterUnit->unit_id;
							} else {
								
								$masterNUnit = $this->get_row("select unit_id from " . $this->tableNames['unit'] . " as u where u.unit_code='NA' and u.is_deleted='0' AND u.status = '1'");
								if(!empty($masterNUnit)) {
									$master_unit_id = $masterNUnit->unit_id;
								} else {
									$master_unit_id = 0;
								}
							}

							$dataInsertItemArray['item_name'] = $item_name;
							$dataInsertItemArray['item_category'] = $masterItem->item_id;
							$dataInsertItemArray['item_description'] = $item_description;
							$dataInsertItemArray['is_applicable'] = $is_applicable;
							$dataInsertItemArray['unit_price'] = $item_rate;
							$dataInsertItemArray['igst_tax_rate'] = $dataArray['igst_rate'];
							$dataInsertItemArray['cess_tax_rate'] = $dataArray['cess_rate'];
							$dataInsertItemArray['item_unit'] = $master_unit_id;
							$dataInsertItemArray['status'] = '1';
							$dataInsertItemArray['added_by'] = $this->sanitize($_SESSION['user_detail']['user_id']);
							$dataInsertItemArray['added_date'] = date('Y-m-d H:i:s');

							if ($this->insert($this->tableNames['client_master_item'], $dataInsertItemArray)) {

								$iteminsertid = $this->getInsertID();
								$dataArray['item_id'] = $iteminsertid;
								$dataArray['item_name'] = $item_name;
								$dataArray['item_hsncode'] = $masterItem->hsn_code;
							} else {
								$errorflag = true;
								array_push($currentItemError, $this->getValMsg('failed'));
							}
						} else {
							$errorflag = true;
							array_push($currentItemError, "Description of Goods and HSN Code should be valid.");
						}
                    }
                } else {
					$errorflag = true;
					array_push($currentItemError, "Description of Goods and HSN Code should be valid.");
                }

				/* get current user data */
                $dataCurrentUserArr = $this->getUserDetailsById($this->sanitize($_SESSION['user_detail']['user_id']));

				/* check reference number */
				$referenceStatus = $this->checkReferenceNumberExist($dataArray['reference_number'], $this->sanitize($_SESSION['user_detail']['user_id']));
				if($referenceStatus == true) {
					$errorflag = true;
					array_push($currentItemError, "You have already used this reference number.");
				}

				/* Invoice Description */
				$dataArray['description'] = isset($data['AJ']) ? $data['AJ'] : '';

                $invoiceErrors = $this->validateClientInvoiceExcel($dataArray);
                if ($invoiceErrors !== true || !empty($currentItemError)) {

                    $errorflag = true;
                    if ($invoiceErrors === true) {
                        $invoiceErrors = array();
                    }
                    $invoiceErrors = array_merge($invoiceErrors, $currentItemError);
                    $invoiceErrors = implode(", ", $invoiceErrors);
                    $objPHPExcel->getActiveSheet()->SetCellValue('AK' . $rowKey, $invoiceErrors);
                }

				if ($errorflag === false) {
				
					/* create invoice array */
					$arrayKey = $dataArray['reference_number'];

					$invoiceArray[$arrayKey]['invoice_type'] = $dataArray['invoice_type'];
					$invoiceArray[$arrayKey]['invoice_nature'] = 'salesinvoice';
					$invoiceArray[$arrayKey]['reference_number'] = $dataArray['reference_number'];
					$invoiceArray[$arrayKey]['company_name'] = $dataCurrentUserArr['data']->kyc->name;
					$invoiceArray[$arrayKey]['company_address'] = $dataCurrentUserArr['data']->kyc->full_address;
					$invoiceArray[$arrayKey]['company_email'] = $dataCurrentUserArr['data']->kyc->email;
					$invoiceArray[$arrayKey]['company_phone_number'] = $dataCurrentUserArr['data']->kyc->phone_number;
					$invoiceArray[$arrayKey]['company_state'] = $dataCurrentUserArr['data']->kyc->state_id;
					$invoiceArray[$arrayKey]['gstin_number'] = $dataCurrentUserArr['data']->kyc->gstin_number;
					$invoiceArray[$arrayKey]['invoice_date'] = $dataArray['invoice_date'];
					$invoiceArray[$arrayKey]['export_supply_meant'] = $dataArray['export_supply_meant'];
					$invoiceArray[$arrayKey]['supply_place'] = $dataArray['supply_place'];
					$invoiceArray[$arrayKey]['billing_name'] = $dataArray['billing_name'];
					$invoiceArray[$arrayKey]['billing_company_name'] = $dataArray['billing_company_name'];
					$invoiceArray[$arrayKey]['billing_address'] = $dataArray['billing_address'];
					$invoiceArray[$arrayKey]['billing_state'] = $dataArray['billing_state'];
					$invoiceArray[$arrayKey]['billing_state_name'] = $dataArray['billing_state_name'];
					$invoiceArray[$arrayKey]['billing_country'] = $dataArray['billing_country'];
					$invoiceArray[$arrayKey]['billing_vendor_type'] = $dataArray['billing_vendor_type'];
					$invoiceArray[$arrayKey]['billing_gstin_number'] = $dataArray['billing_gstin_number'];
					$invoiceArray[$arrayKey]['shipping_name'] = $dataArray['shipping_name'];
					$invoiceArray[$arrayKey]['shipping_company_name'] = $dataArray['shipping_company_name'];
					$invoiceArray[$arrayKey]['shipping_address'] = $dataArray['shipping_address'];
					$invoiceArray[$arrayKey]['shipping_state'] = $dataArray['shipping_state'];
					$invoiceArray[$arrayKey]['shipping_state_name'] = $dataArray['shipping_state_name'];
					$invoiceArray[$arrayKey]['shipping_country'] = $dataArray['shipping_country'];
					$invoiceArray[$arrayKey]['shipping_vendor_type'] = $dataArray['shipping_vendor_type'];
					$invoiceArray[$arrayKey]['shipping_gstin_number'] = $dataArray['shipping_gstin_number'];

					if($dataArray['invoice_type'] == "exportinvoice") {
						$invoiceArray[$arrayKey]['export_bill_number'] = $dataArray['export_bill_number'];
						$invoiceArray[$arrayKey]['export_bill_port_code'] = $dataArray['export_bill_port_code'];
						$invoiceArray[$arrayKey]['export_bill_date'] = $dataArray['export_bill_date'];
					}

					$invoiceArray[$arrayKey]['advance_adjustment'] = $dataArray['advance_adjustment'];
					if($dataArray['advance_adjustment'] == 1) {
						$invoiceArray[$arrayKey]['receipt_voucher_number'] = $dataArray['receipt_voucher_number'];
					} else {
						$invoiceArray[$arrayKey]['receipt_voucher_number'] = 0;
					}

					$invoiceArray[$arrayKey]['description'] = $dataArray['description'];

					//items
					$invoiceItemArray['item_id'] = $dataArray['item_id'];
					$invoiceItemArray['item_name'] = $dataArray['item_name'];
					$invoiceItemArray['item_hsncode'] = $dataArray['item_hsncode'];
					$invoiceItemArray['item_description'] = $dataArray['item_description'];
					$invoiceItemArray['is_applicable'] = $dataArray['is_applicable'];
					$invoiceItemArray['item_quantity'] = $dataArray['item_quantity'];
					$invoiceItemArray['item_unit'] = $dataArray['item_unit'];
					$invoiceItemArray['item_unit_price'] = $dataArray['item_rate'];
					$invoiceItemArray['item_discount'] = $dataArray['item_discount'];

					if($dataArray['advance_adjustment'] == 1) {
						$invoiceItemArray['advance_amount'] = $dataArray['advance_amount'];
					} else {
						$invoiceItemArray['advance_amount'] = 0.00;
					}

					$invoiceItemArray['igst_rate'] = $dataArray['igst_rate'];
					$invoiceItemArray['cess_rate'] = $dataArray['cess_rate'];

					$invoiceArray[$arrayKey]['items'][] = $invoiceItemArray;
				}
            }

            if ($errorflag === true) {

                $objPHPExcel->getActiveSheet()->SetCellValue('AK1', "Error Information");
                $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
                $objWriter->save($invoice_excel_dir_path);
                $this->setError($this->validationMessage['excelerror']);
                $resultArray = array("status" => "error", "excelurl" => $invoice_excel_url_path);
                return json_encode($resultArray);
            } else {

                foreach ($invoiceArray as $invoiceRow) {

                    $invoiceItemArray = array();
                    $invoiceTotalAmount = 0.00;
					$consolidateRate = 0.00;

                    foreach ($invoiceRow['items'] as $invoiceInnerRow) {

						$itemUnitPrice = (float) $invoiceInnerRow['item_unit_price'];
						$invoiceItemQuantity = (float) $invoiceInnerRow['item_quantity'];
						$invoiceItemDiscount = (float) $invoiceInnerRow['item_discount'];
						$invoiceItemAdvanceAmount = (float) $invoiceInnerRow['advance_amount'];

						$invoiceItemTotal = $invoiceItemQuantity * $itemUnitPrice;
						$invoiceItemDiscountAmount = ($invoiceItemDiscount / 100) * $invoiceItemTotal;
						$invoiceItemReduceAmount = $invoiceItemAdvanceAmount + $invoiceItemDiscountAmount;
						$invoiceItemTaxableAmount = $invoiceItemTotal - $invoiceItemReduceAmount;

						if($invoiceRow['export_supply_meant'] === "withpayment") {

							$itemCSGTTax = 0.00;
							$itemSGSTTax = 0.00;
							$itemIGSTTax = (float)$invoiceInnerRow['igst_rate'];
							$itemCESSTax = (float)$invoiceInnerRow['cess_rate'];
							$consolidateRate = $itemIGSTTax;

							$invoiceItemCSGTTaxAmount = 0.00;
							$invoiceItemSGSTTaxAmount = 0.00;
							$invoiceItemIGSTTaxAmount = ($itemIGSTTax/100) * $invoiceItemTaxableAmount;
							$invoiceItemCESSTaxAmount = ($itemCESSTax/100) * $invoiceItemTaxableAmount;
						} else {
							
							$itemCSGTTax = 0.00;
							$itemSGSTTax = 0.00;
							$itemIGSTTax = 0.00;
							$itemCESSTax = (float)$invoiceInnerRow['cess_rate'];
							$consolidateRate = $itemIGSTTax;

							$invoiceItemCSGTTaxAmount = 0.00;
							$invoiceItemSGSTTaxAmount = 0.00;
							$invoiceItemIGSTTaxAmount = 0.00;
							$invoiceItemCESSTaxAmount = ($itemCESSTax/100) * $invoiceItemTaxableAmount;
						}

						$invoiceItemTotalAmount = ($invoiceItemTaxableAmount + $invoiceItemCSGTTaxAmount + $invoiceItemSGSTTaxAmount + $invoiceItemIGSTTaxAmount + $invoiceItemCESSTaxAmount);
						$invoiceTotalAmount += $invoiceItemTotalAmount;

						$ItemArray = array(
							"item_id" => $invoiceInnerRow['item_id'],
							"item_name" => $invoiceInnerRow['item_name'],
							"item_hsncode" => $invoiceInnerRow['item_hsncode'],
							"item_description" => $invoiceInnerRow['item_description'],
							"is_applicable" => $invoiceInnerRow['is_applicable'],
							"item_quantity" => $invoiceItemQuantity,
							"item_unit" => $invoiceInnerRow['item_unit'],
							"item_unit_price" => $itemUnitPrice,
							"subtotal" => round($invoiceItemTotal, 2),
							"discount" => $invoiceItemDiscount,
							"advance_amount" => $invoiceItemAdvanceAmount,
							"taxable_subtotal" => round($invoiceItemTaxableAmount, 2),
							"cgst_rate" => $itemCSGTTax,
							"cgst_amount" => round($invoiceItemCSGTTaxAmount, 2),
							"sgst_rate" => $itemSGSTTax,
							"sgst_amount" => round($invoiceItemSGSTTaxAmount, 2),
							"igst_rate" => $itemIGSTTax,
							"igst_amount" => round($invoiceItemIGSTTaxAmount, 2),
							"cess_rate" => $itemCESSTax,
							"cess_amount" => round($invoiceItemCESSTaxAmount, 2),
							"consolidate_rate" => $consolidateRate,
							"total" => round($invoiceItemTotalAmount, 2),
							"status" => 1,
							"added_by" => $this->sanitize($_SESSION['user_detail']['user_id']),
							"added_date" => date('Y-m-d H:i:s')
						);

						array_push($invoiceItemArray, $ItemArray);
                    }

                    if (!empty($invoiceItemArray) && count($invoiceItemArray) > 0) {

                        $InsertArray['invoice_type'] = $invoiceRow['invoice_type'];
						$InsertArray['invoice_nature'] = $invoiceRow['invoice_nature'];
                        $InsertArray['reference_number'] = $invoiceRow['reference_number'];
                        $InsertArray['serial_number'] = $this->generateInvoiceNumber($this->sanitize($_SESSION['user_detail']['user_id']));
                        $InsertArray['company_name'] = $invoiceRow['company_name'];
                        $InsertArray['company_address'] = $invoiceRow['company_address'];
						$InsertArray['company_email'] = $invoiceRow['company_email'];
						$InsertArray['company_phone_number'] = $invoiceRow['company_phone_number'];
                        $InsertArray['company_state'] = $invoiceRow['company_state'];
                        $InsertArray['gstin_number'] = $invoiceRow['gstin_number'];
                        $InsertArray['invoice_date'] = $invoiceRow['invoice_date'];
                        $InsertArray['supply_place'] = $invoiceRow['supply_place'];
						$InsertArray['export_supply_meant'] = $invoiceRow['export_supply_meant'];
                        $InsertArray['billing_name'] = $invoiceRow['billing_name'];
						$InsertArray['billing_company_name'] = $invoiceRow['billing_company_name'];
                        $InsertArray['billing_address'] = $invoiceRow['billing_address'];
                        $InsertArray['billing_state'] = $invoiceRow['billing_state'];
                        $InsertArray['billing_state_name'] = $invoiceRow['billing_state_name'];
						$InsertArray['billing_country'] = $invoiceRow['billing_country'];
						$InsertArray['billing_vendor_type'] = $invoiceRow['billing_vendor_type'];
                        $InsertArray['billing_gstin_number'] = $invoiceRow['billing_gstin_number'];
                        $InsertArray['shipping_name'] = $invoiceRow['shipping_name'];
                        $InsertArray['shipping_company_name'] = $invoiceRow['shipping_company_name'];
						$InsertArray['shipping_address'] = $invoiceRow['shipping_address'];
                        $InsertArray['shipping_state'] = $invoiceRow['shipping_state'];
                        $InsertArray['shipping_state_name'] = $invoiceRow['shipping_state_name'];
						$InsertArray['shipping_country'] = $invoiceRow['shipping_country'];
						$InsertArray['shipping_vendor_type'] = $invoiceRow['shipping_vendor_type'];
                        $InsertArray['shipping_gstin_number'] = $invoiceRow['shipping_gstin_number'];

						if($invoiceRow['invoice_type'] == "exportinvoice") {
							$InsertArray['export_bill_number'] = $invoiceRow['export_bill_number'];
							$InsertArray['export_bill_port_code'] = $invoiceRow['export_bill_port_code'];
							$InsertArray['export_bill_date'] = $invoiceRow['export_bill_date'];
						}

                        $InsertArray['advance_adjustment'] = $invoiceRow['advance_adjustment'];
						if($invoiceRow['advance_adjustment'] == 1) {
							$InsertArray['receipt_voucher_number'] = $invoiceRow['receipt_voucher_number'];
						} else {
							$InsertArray['receipt_voucher_number'] = 0;
						}

						$InsertArray['description'] = $invoiceRow['description'];
						$InsertArray['invoice_total_value'] = number_format($invoiceTotalAmount, 2, '.', '');
                        $InsertArray['financial_year'] = $this->generateFinancialYear();
                        $InsertArray['status'] = 1;
						$InsertArray['created_from'] = 'E';
                        $InsertArray['added_by'] = $_SESSION['user_detail']['user_id'];
                        $InsertArray['added_date'] = date('Y-m-d H:i:s');

                        if ($this->insert($this->tableNames['client_invoice'], $InsertArray)) {

                            $insertid = $this->getInsertID();
                            $this->logMsg("Sales Export Tax Invoice Added. ID : " . $insertid . ".","Salesexport_tax_invoice");

                            $processedInvoiceItemArray = array();
                            foreach ($invoiceItemArray as $itemArr) {

                                $itemArr['invoice_id'] = $insertid;
                                array_push($processedInvoiceItemArray, $itemArr);
                            }

                            if ($this->insertMultiple($this->tableNames['client_invoice_item'], $processedInvoiceItemArray)) {

                                $iteminsertid = $this->getInsertID();
                                $this->logMsg("Sales Export Tax Invoice Item Added. ID : " . $iteminsertid . ".","Salesexport_tax_invoice");
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
		$currentFinancialYear = $this->generateFinancialYear();

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
				$dataArray['billing_company_name'] = isset($data['D']) ? $data['D'] : '';
				$dataArray['billing_address'] = isset($data['E']) ? $data['E'] : '';

				$billing_state = isset($data['F']) ? $data['F'] : '';
				if ($billing_state != '') {

					$billing_state_data = $this->getStateDetailByStateNameCode($billing_state);
					if ($billing_state_data['status'] === "success") {
						$dataArray['billing_state'] = $billing_state_data['data']->state_id;
						$dataArray['billing_state_name'] = $billing_state_data['data']->state_name;
					} else {
						$errorflag = true;
						array_push($currentItemError, "Invalid Billing State.");
					}
				} else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Billing State.");
				}

				$billing_country = isset($data['G']) ? $data['G'] : '';
				if ($billing_country != '') {

					$billing_country_data = $this->getCountryDetailByCountryCode($billing_country);
					if ($billing_country_data['status'] === "success") {
						$dataArray['billing_country'] = $billing_country_data['data']->id;
					} else {
						$errorflag = true;
						array_push($currentItemError, "Invalid Billing Country.");
					}
				} else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Billing Country.");
				}

				$billing_vendor_type = isset($data['H']) ? $data['H'] : '';
				if ($billing_vendor_type != '') {

					$dataVendorNameArrs = $this->get_row("select vendor_id, vendor_name from ".$this->tableNames['vendor_type']." where 1=1 AND UPPER(vendor_name) = '".strtoupper($billing_vendor_type)."' AND status='1' AND is_deleted='0'");
					if (!empty($dataVendorNameArrs) && isset($dataVendorNameArrs->vendor_id)) {
						$dataArray['billing_vendor_type'] = $dataVendorNameArrs->vendor_id;
					} else {
						$errorflag = true;
						array_push($currentItemError, "Invalid Billing Vendor Type.");
					}
				} else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Billing Vendor Type.");
				}

				$dataArray['billing_gstin_number'] = isset($data['I']) ? $data['I'] : '';

				$dataArray['shipping_name'] = isset($data['J']) ? $data['J'] : '';
				$dataArray['shipping_company_name'] = isset($data['K']) ? $data['K'] : '';
				$dataArray['shipping_address'] = isset($data['L']) ? $data['L'] : '';

				$shipping_state = isset($data['M']) ? $data['M'] : '';
				if ($shipping_state != '') {

					$shipping_state_data = $this->getStateDetailByStateNameCode($shipping_state);
					if ($shipping_state_data['status'] === "success") {
						$dataArray['shipping_state'] = $shipping_state_data['data']->state_id;
						$dataArray['shipping_state_name'] = $shipping_state_data['data']->state_name;
					} else {
						$errorflag = true;
						array_push($currentItemError, "Invalid Shipping State.");
					}
				} else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Shipping State.");
				}

				$shipping_country = isset($data['N']) ? $data['N'] : '';
				if ($shipping_country != '') {

					$shipping_country_data = $this->getCountryDetailByCountryCode($shipping_country);
					if ($shipping_country_data['status'] === "success") {
						$dataArray['shipping_country'] = $shipping_country_data['data']->id;
					} else {
						$errorflag = true;
						array_push($currentItemError, "Invalid Shipping Country.");
					}
				} else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Shipping Country.");
				}

				$shipping_vendor_type = isset($data['O']) ? $data['O'] : '';
				if ($shipping_vendor_type != '') {

					$dataVendorNameArrs = $this->get_row("select vendor_id, vendor_name from ".$this->tableNames['vendor_type']." where 1=1 AND UPPER(vendor_name) = '".strtoupper($shipping_vendor_type)."' AND status='1' AND is_deleted='0'");
					if (!empty($dataVendorNameArrs) && isset($dataVendorNameArrs->vendor_id)) {
						$dataArray['shipping_vendor_type'] = $dataVendorNameArrs->vendor_id;
					} else {
						$errorflag = true;
						array_push($currentItemError, "Invalid Shipping Vendor Type.");
					}
				} else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Shipping Vendor Type.");
				}

				$dataArray['shipping_gstin_number'] = isset($data['P']) ? $data['P'] : '';

				$item_name = isset($data['Q']) ? trim($data['Q']) : '';
				$item_hsncode = isset($data['R']) ? trim($data['R']) : '';

				$dataArray['item_description'] = isset($data['S']) ? trim($data['S']) : '';
				$item_description = $dataArray['item_description'];
				
				$applicable_tax = isset($data['T']) ? $data['T'] : '';
				if ($applicable_tax != '' && strtoupper($applicable_tax) == 'NON GST') {
					$dataArray['is_applicable'] = '1';
					$is_applicable = '1';
				} else if ($applicable_tax != '' && strtoupper($applicable_tax) == 'EXEMPTED') {
					$dataArray['is_applicable'] = '2';
					$is_applicable = '2';
				} else {
					$dataArray['is_applicable'] = '0';
					$is_applicable = '0';
				}

				$dataArray['item_quantity'] = isset($data['U']) ? round($data['U'], 2) : '';

				$dataArray['item_unit'] = isset($data['V']) ? $data['V'] : '';
				$item_unit =  $dataArray['item_unit'];
				
				$dataArray['item_rate'] = isset($data['W']) ? round($data['W'], 2) : 0.00;
				$item_rate = round($dataArray['item_rate'], 2);

				$dataArray['item_discount'] = isset($data['X']) ? round($data['X'], 2) : 0.00;

				if(!empty($item_name) && !empty($item_hsncode)) {

					$checkClientMasterItem = $this->get_row("select cm.item_id, cm.is_applicable, cm.item_name, cm.unit_price, cm.item_description, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, cm.item_unit from " . $this->tableNames['client_master_item'] . " as cm, " . $this->tableNames['item'] . " as m where 1=1 AND cm.item_category = m.item_id AND cm.item_name = '" . $item_name . "' && m.hsn_code = '" . $item_hsncode . "' AND cm.is_deleted='0' AND cm.added_by = '" . $this->sanitize($_SESSION['user_detail']['user_id']) . "'");
					if (!empty($checkClientMasterItem)) {

						$dataArray['item_id'] = $checkClientMasterItem->item_id;
						$dataArray['item_name'] = $item_name;
						$dataArray['item_hsncode'] = $item_hsncode;
					} else {

						$masterItem = $this->get_row("select item_id, item_name, hsn_code from " . $this->tableNames['item'] . " where hsn_code='".$item_hsncode."' and is_deleted='0' AND status='1'");						
						if(!empty($masterItem)) {

							$masterUnit = $this->get_row("select unit_id from " . $this->tableNames['unit'] . " as u where u.unit_code='".$item_unit."' and u.is_deleted='0' AND u.status = '1'");
							if(!empty($masterUnit)) {
								$master_unit_id = $masterUnit->unit_id;
							} else {
								
								$masterNUnit = $this->get_row("select unit_id from " . $this->tableNames['unit'] . " as u where u.unit_code='NA' and u.is_deleted='0' AND u.status = '1'");
								if(!empty($masterNUnit)) {
									$master_unit_id = $masterNUnit->unit_id;
								} else {
									$master_unit_id = 0;
								}
							}

							$dataInsertItemArray['item_name'] = $item_name;
							$dataInsertItemArray['item_category'] = $masterItem->item_id;
							$dataInsertItemArray['item_description'] = $item_description;
							$dataInsertItemArray['is_applicable'] = $is_applicable;
							$dataInsertItemArray['unit_price'] = $item_rate;
							$dataInsertItemArray['item_unit'] = $master_unit_id;
							$dataInsertItemArray['status'] = '1';
							$dataInsertItemArray['added_by'] = $this->sanitize($_SESSION['user_detail']['user_id']);
							$dataInsertItemArray['added_date'] = date('Y-m-d H:i:s');

							if ($this->insert($this->tableNames['client_master_item'], $dataInsertItemArray)) {

								$iteminsertid = $this->getInsertID();
								$dataArray['item_id'] = $iteminsertid;
								$dataArray['item_name'] = $item_name;
								$dataArray['item_hsncode'] = $masterItem->hsn_code;
							} else {
								$errorflag = true;
								array_push($currentItemError, $this->getValMsg('failed'));
							}
						} else {
							$errorflag = true;
							array_push($currentItemError, "Description of Goods and HSN Code should be valid.");
						}
					}
				} else {
					$errorflag = true;
					array_push($currentItemError, "Description of Goods and HSN Code should be valid.");
				}

				/* get current user data */
				$dataCurrentUserArr = $this->getUserDetailsById($this->sanitize($_SESSION['user_detail']['user_id']));

				/* check reference number */
				$referenceStatus = $this->checkReferenceNumberExist($dataArray['reference_number'], $this->sanitize($_SESSION['user_detail']['user_id']));
				if($referenceStatus == true) {
					$errorflag = true;
					array_push($currentItemError, "You have already used this reference number.");
				}

				/* Invoice Description */
				$dataArray['description'] = isset($data['Y']) ? $data['Y'] : '';

				$invoiceErrors = $this->validateClientInvoiceExcel($dataArray);
				if ($invoiceErrors !== true || !empty($currentItemError)) {

					$errorflag = true;
					if ($invoiceErrors === true) {
						$invoiceErrors = array();
					}
					$invoiceErrors = array_merge($invoiceErrors, $currentItemError);
					$invoiceErrors = implode(", ", $invoiceErrors);
					$objPHPExcel->getActiveSheet()->SetCellValue('Z' . $rowKey, $invoiceErrors);
				}

				if ($errorflag === false) {
				
					/* create invoice array */
					$arrayKey = $dataArray['reference_number'];

					$invoiceArray[$arrayKey]['invoice_type'] = 'billofsupplyinvoice';
					$invoiceArray[$arrayKey]['invoice_nature'] = 'salesinvoice';
					$invoiceArray[$arrayKey]['reference_number'] = $dataArray['reference_number'];
					$invoiceArray[$arrayKey]['company_name'] = $dataCurrentUserArr['data']->kyc->name;
					$invoiceArray[$arrayKey]['company_address'] = $dataCurrentUserArr['data']->kyc->full_address;
					$invoiceArray[$arrayKey]['company_email'] = $dataCurrentUserArr['data']->kyc->email;
					$invoiceArray[$arrayKey]['company_phone_number'] = $dataCurrentUserArr['data']->kyc->phone_number;
					$invoiceArray[$arrayKey]['company_state'] = $dataCurrentUserArr['data']->kyc->state_id;
					$invoiceArray[$arrayKey]['gstin_number'] = $dataCurrentUserArr['data']->kyc->gstin_number;
					$invoiceArray[$arrayKey]['invoice_date'] = $dataArray['invoice_date'];
					$invoiceArray[$arrayKey]['billing_name'] = $dataArray['billing_name'];
					$invoiceArray[$arrayKey]['billing_company_name'] = $dataArray['billing_company_name'];
					$invoiceArray[$arrayKey]['billing_address'] = $dataArray['billing_address'];
					$invoiceArray[$arrayKey]['billing_state'] = $dataArray['billing_state'];
					$invoiceArray[$arrayKey]['billing_state_name'] = $dataArray['billing_state_name'];
					$invoiceArray[$arrayKey]['billing_country'] = $dataArray['billing_country'];
					$invoiceArray[$arrayKey]['billing_vendor_type'] = $dataArray['billing_vendor_type'];
					$invoiceArray[$arrayKey]['billing_gstin_number'] = $dataArray['billing_gstin_number'];
					$invoiceArray[$arrayKey]['shipping_name'] = $dataArray['shipping_name'];
					$invoiceArray[$arrayKey]['shipping_company_name'] = $dataArray['shipping_company_name'];
					$invoiceArray[$arrayKey]['shipping_address'] = $dataArray['shipping_address'];
					$invoiceArray[$arrayKey]['shipping_state'] = $dataArray['shipping_state'];
					$invoiceArray[$arrayKey]['shipping_state_name'] = $dataArray['shipping_state_name'];
					$invoiceArray[$arrayKey]['shipping_country'] = $dataArray['shipping_country'];
					$invoiceArray[$arrayKey]['shipping_vendor_type'] = $dataArray['shipping_vendor_type'];
					$invoiceArray[$arrayKey]['shipping_gstin_number'] = $dataArray['shipping_gstin_number'];
					$invoiceArray[$arrayKey]['description'] = $dataArray['description'];

					//items
					$invoiceItemArray['item_id'] = $dataArray['item_id'];
					$invoiceItemArray['item_name'] = $dataArray['item_name'];
					$invoiceItemArray['item_hsncode'] = $dataArray['item_hsncode'];
					$invoiceItemArray['item_description'] = $dataArray['item_description'];
					$invoiceItemArray['is_applicable'] = $dataArray['is_applicable'];
					$invoiceItemArray['item_quantity'] = $dataArray['item_quantity'];
					$invoiceItemArray['item_unit'] = $dataArray['item_unit'];
					$invoiceItemArray['item_unit_price'] = $dataArray['item_rate'];
					$invoiceItemArray['item_discount'] = $dataArray['item_discount'];

					$invoiceArray[$arrayKey]['items'][] = $invoiceItemArray;
				}
            }

			if ($errorflag === true) {

				$objPHPExcel->getActiveSheet()->SetCellValue('Z1', "Error Information");
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

						$itemUnitPrice = (float) $invoiceInnerRow['item_unit_price'];
						$invoiceItemQuantity = (float) $invoiceInnerRow['item_quantity'];
						$invoiceItemDiscount = (float) $invoiceInnerRow['item_discount'];

						$invoiceItemTotal = $invoiceItemQuantity * $itemUnitPrice;
						$invoiceItemDiscountAmount = ($invoiceItemDiscount / 100) * $invoiceItemTotal;
						$invoiceItemTaxableAmount = $invoiceItemTotal - $invoiceItemDiscountAmount;

						$invoiceItemTotalAmount = $invoiceItemTaxableAmount;
						$invoiceTotalAmount += $invoiceItemTotalAmount;

						$ItemArray = array(
							"item_id" => $invoiceInnerRow['item_id'],
							"item_name" => $invoiceInnerRow['item_name'],
							"item_hsncode" => $invoiceInnerRow['item_hsncode'],
							"item_description" => $invoiceInnerRow['item_description'],
							"is_applicable" => $invoiceInnerRow['is_applicable'],
							"item_quantity" => $invoiceItemQuantity,
							"item_unit" => $invoiceInnerRow['item_unit'],
							"item_unit_price" => $itemUnitPrice,
							"subtotal" => round($invoiceItemTotal, 2),
							"discount" => $invoiceItemDiscount,
							"advance_amount" => 0.00,
							"taxable_subtotal" => round($invoiceItemTaxableAmount, 2),
							"cgst_rate" => 0.000,
							"cgst_amount" => 0.00,
							"sgst_rate" => 0.000,
							"sgst_amount" => 0.00,
							"igst_rate" => 0.000,
							"igst_amount" => 0.00,
							"cess_rate" => 0.000,
							"cess_amount" => 0.00,
							"consolidate_rate" => 0.00,
							"total" => round($invoiceItemTotalAmount, 2),
							"status" => 1,
							"added_by" => $this->sanitize($_SESSION['user_detail']['user_id']),
							"added_date" => date('Y-m-d H:i:s')
						);

						array_push($invoiceItemArray, $ItemArray);
                    }

                    if (!empty($invoiceItemArray) && count($invoiceItemArray) > 0) {

						$InsertArray['invoice_type'] = $invoiceRow['invoice_type'];
						$InsertArray['invoice_nature'] = $invoiceRow['invoice_nature'];
						$InsertArray['reference_number'] = $invoiceRow['reference_number'];
						$InsertArray['serial_number'] = $this->generateBillInvoiceNumber($this->sanitize($_SESSION['user_detail']['user_id']));
						$InsertArray['company_name'] = $invoiceRow['company_name'];
						$InsertArray['company_address'] = $invoiceRow['company_address'];
						$InsertArray['company_email'] = $invoiceRow['company_email'];
						$InsertArray['company_phone_number'] = $invoiceRow['company_phone_number'];
						$InsertArray['company_state'] = $invoiceRow['company_state'];
						$InsertArray['gstin_number'] = $invoiceRow['gstin_number'];
						$InsertArray['invoice_date'] = $invoiceRow['invoice_date'];
                        $InsertArray['billing_name'] = $invoiceRow['billing_name'];
						$InsertArray['billing_company_name'] = $invoiceRow['billing_company_name'];
						$InsertArray['billing_address'] = $invoiceRow['billing_address'];
						$InsertArray['billing_state'] = $invoiceRow['billing_state'];
						$InsertArray['billing_state_name'] = $invoiceRow['billing_state_name'];
						$InsertArray['billing_country'] = $invoiceRow['billing_country'];
						$InsertArray['billing_vendor_type'] = $invoiceRow['billing_vendor_type'];
						$InsertArray['billing_gstin_number'] = $invoiceRow['billing_gstin_number'];
						$InsertArray['shipping_name'] = $invoiceRow['shipping_name'];
						$InsertArray['shipping_company_name'] = $invoiceRow['shipping_company_name'];
						$InsertArray['shipping_address'] = $invoiceRow['shipping_address'];
						$InsertArray['shipping_state'] = $invoiceRow['shipping_state'];
						$InsertArray['shipping_state_name'] = $invoiceRow['shipping_state_name'];
						$InsertArray['shipping_country'] = $invoiceRow['shipping_country'];
						$InsertArray['shipping_vendor_type'] = $invoiceRow['shipping_vendor_type'];
						$InsertArray['shipping_gstin_number'] = $invoiceRow['shipping_gstin_number'];
						$InsertArray['description'] = $invoiceRow['description'];						
						$InsertArray['invoice_total_value'] = number_format($invoiceTotalAmount, 2, '.', '');
						$InsertArray['financial_year'] = $this->generateFinancialYear();
						$InsertArray['status'] = 1;
						$InsertArray['created_from'] = 'E';
						$InsertArray['added_by'] = $_SESSION['user_detail']['user_id'];
						$InsertArray['added_date'] = date('Y-m-d H:i:s');

						if ($this->insert($this->tableNames['client_invoice'], $InsertArray)) {

							$insertid = $this->getInsertID();
							$this->logMsg("BOS Invoice Added. ID : " . $insertid . ".","client_create_bill_of_supply_invoice");

							$processedInvoiceItemArray = array();
							foreach ($invoiceItemArray as $itemArr) {

								$itemArr['invoice_id'] = $insertid;
								array_push($processedInvoiceItemArray, $itemArr);
							}

							if ($this->insertMultiple($this->tableNames['client_invoice_item'], $processedInvoiceItemArray)) {

								$iteminsertid = $this->getInsertID();
								$this->logMsg("BOS Invoice Item Added. ID : " . $iteminsertid . ".","client_create_bill_of_supply_invoice");
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
		$currentFinancialYear = $this->generateFinancialYear();

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

				$is_tax_payable = isset($data['C']) ? $data['C'] : '';
                if ($is_tax_payable != '' && strtoupper($is_tax_payable) === 'Y') {
                    $dataArray['is_tax_payable'] = 1;
                } else if ($is_tax_payable != '' && strtoupper($is_tax_payable) === 'N') {
                    $dataArray['is_tax_payable'] = 0;
                } else {
                    $errorflag = true;
					array_push($currentItemError, "Invalid Tax Payable.");
                }

                $supply_place = isset($data['D']) ? $data['D'] : '';
				if ($supply_place != '') {

					$supply_state_data = $this->getStateDetailByStateNameCode($supply_place);
					if ($supply_state_data['status'] === "success") {
						$dataArray['supply_place'] = $supply_state_data['data']->state_id;
					} else {
						$errorflag = true;
						array_push($currentItemError, "Invalid Place Of Supply.");
					}
				} else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Place Of Supply.");
				}

				$dataArray['billing_name'] = isset($data['E']) ? $data['E'] : '';
				$dataArray['billing_company_name'] = isset($data['F']) ? $data['F'] : '';
				$dataArray['billing_address'] = isset($data['G']) ? $data['G'] : '';

				$billing_state = isset($data['H']) ? $data['H'] : '';
				if ($billing_state != '') {

					$billing_state_data = $this->getStateDetailByStateNameCode($billing_state);
					if ($billing_state_data['status'] === "success") {
						$dataArray['billing_state'] = $billing_state_data['data']->state_id;
						$dataArray['billing_state_name'] = $billing_state_data['data']->state_name;
					} else {
						$errorflag = true;
						array_push($currentItemError, "Invalid Billing State.");
					}
				} else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Billing State.");
				}

				$billing_country = isset($data['I']) ? $data['I'] : '';
				if ($billing_country != '') {

					$billing_country_data = $this->getCountryDetailByCountryCode($billing_country);
					if ($billing_country_data['status'] === "success") {
						$dataArray['billing_country'] = $billing_country_data['data']->id;
					} else {
						$errorflag = true;
						array_push($currentItemError, "Invalid Billing Country.");
					}
				} else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Billing Country.");
				}

				$billing_vendor_type = isset($data['J']) ? $data['J'] : '';
				if ($billing_vendor_type != '') {

					$dataVendorNameArrs = $this->get_row("select vendor_id, vendor_name from ".$this->tableNames['vendor_type']." where 1=1 AND UPPER(vendor_name) = '".strtoupper($billing_vendor_type)."' AND status='1' AND is_deleted='0'");
					if (!empty($dataVendorNameArrs) && isset($dataVendorNameArrs->vendor_id)) {
						$dataArray['billing_vendor_type'] = $dataVendorNameArrs->vendor_id;
					} else {
						$errorflag = true;
						array_push($currentItemError, "Invalid Billing Vendor Type.");
					}
				} else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Billing Vendor Type.");
				}

				$dataArray['billing_gstin_number'] = isset($data['K']) ? $data['K'] : '';

				$dataArray['shipping_name'] = isset($data['L']) ? $data['L'] : '';
				$dataArray['shipping_company_name'] = isset($data['M']) ? $data['M'] : '';
				$dataArray['shipping_address'] = isset($data['N']) ? $data['N'] : '';

				$shipping_state = isset($data['O']) ? $data['O'] : '';
				if ($shipping_state != '') {

					$shipping_state_data = $this->getStateDetailByStateNameCode($shipping_state);
					if ($shipping_state_data['status'] === "success") {
						$dataArray['shipping_state'] = $shipping_state_data['data']->state_id;
						$dataArray['shipping_state_name'] = $shipping_state_data['data']->state_name;
					} else {
						$errorflag = true;
						array_push($currentItemError, "Invalid Shipping State.");
					}
				} else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Shipping State.");
				}

				$shipping_country = isset($data['P']) ? $data['P'] : '';
				if ($shipping_country != '') {

					$shipping_country_data = $this->getCountryDetailByCountryCode($shipping_country);
					if ($shipping_country_data['status'] === "success") {
						$dataArray['shipping_country'] = $shipping_country_data['data']->id;
					} else {
						$errorflag = true;
						array_push($currentItemError, "Invalid Shipping Country.");
					}
				} else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Shipping Country.");
				}

				$shipping_vendor_type = isset($data['Q']) ? $data['Q'] : '';
				if ($shipping_vendor_type != '') {

					$dataVendorNameArrs = $this->get_row("select vendor_id, vendor_name from ".$this->tableNames['vendor_type']." where 1=1 AND UPPER(vendor_name) = '".strtoupper($shipping_vendor_type)."' AND status='1' AND is_deleted='0'");
					if (!empty($dataVendorNameArrs) && isset($dataVendorNameArrs->vendor_id)) {
						$dataArray['shipping_vendor_type'] = $dataVendorNameArrs->vendor_id;
					} else {
						$errorflag = true;
						array_push($currentItemError, "Invalid Shipping Vendor Type.");
					}
				} else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Shipping Vendor Type.");
				}

				$dataArray['shipping_gstin_number'] = isset($data['R']) ? $data['R'] : '';

                $item_name = isset($data['S']) ? trim($data['S']) : '';
				$item_hsncode = isset($data['T']) ? trim($data['T']) : '';

				$dataArray['item_description'] = isset($data['U']) ? trim($data['U']) : '';
				$item_description = $dataArray['item_description'];
				
				$applicable_tax = isset($data['V']) ? $data['V'] : '';
				if ($applicable_tax != '' && strtoupper($applicable_tax) == 'NON GST') {
					$dataArray['is_applicable'] = '1';
					$is_applicable = '1';
				} else if ($applicable_tax != '' && strtoupper($applicable_tax) == 'EXEMPTED') {
					$dataArray['is_applicable'] = '2';
					$is_applicable = '2';
				} else {
					$dataArray['is_applicable'] = '0';
					$is_applicable = '0';
				}

				if(!empty($item_name) && !empty($item_hsncode)) {

					$checkClientMasterItem = $this->get_row("select cm.item_id, cm.is_applicable, cm.item_name, cm.unit_price, cm.item_description, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, m.igst_tax_rate, m.csgt_tax_rate, m.sgst_tax_rate, m.cess_tax_rate, cm.item_unit from " . $this->tableNames['client_master_item'] . " as cm, " . $this->tableNames['item'] . " as m where 1=1 AND cm.item_category = m.item_id AND cm.item_name = '" . $item_name . "' && m.hsn_code = '" . $item_hsncode . "' AND cm.is_deleted='0' AND cm.added_by = '" . $this->sanitize($_SESSION['user_detail']['user_id']) . "'");
					if (!empty($checkClientMasterItem)) {

						$dataArray['item_id'] = $checkClientMasterItem->item_id;
						$dataArray['item_name'] = $item_name;
						$dataArray['item_hsncode'] = $item_hsncode;
					} else {

						$masterItem = $this->get_row("select item_id, item_name, hsn_code from " . $this->tableNames['item'] . " where hsn_code='".$item_hsncode."' and is_deleted='0' AND status='1'");						
						if(!empty($masterItem)) {

							$masterNUnit = $this->get_row("select unit_id from " . $this->tableNames['unit'] . " as u where u.unit_code='NA' and u.is_deleted='0' AND u.status = '1'");
							if(!empty($masterNUnit)) {
								$master_unit_id = $masterNUnit->unit_id;
							} else {
								$master_unit_id = 0;
							}

							$dataInsertItemArray['item_name'] = $item_name;
							$dataInsertItemArray['item_category'] = $masterItem->item_id;
							$dataInsertItemArray['item_description'] = $item_description;
							$dataInsertItemArray['is_applicable'] = $is_applicable;
							$dataInsertItemArray['item_unit'] = $master_unit_id;
							$dataInsertItemArray['status'] = '1';
							$dataInsertItemArray['added_by'] = $this->sanitize($_SESSION['user_detail']['user_id']);
							$dataInsertItemArray['added_date'] = date('Y-m-d H:i:s');

							if ($this->insert($this->tableNames['client_master_item'], $dataInsertItemArray)) {

								$iteminsertid = $this->getInsertID();
								$dataArray['item_id'] = $iteminsertid;
								$dataArray['item_name'] = $item_name;
								$dataArray['item_hsncode'] = $masterItem->hsn_code;
							} else {
								$errorflag = true;
								array_push($currentItemError, $this->getValMsg('failed'));
							}
						} else {
							$errorflag = true;
							array_push($currentItemError, "Description of Goods and HSN Code should be valid.");
						}
					}
				} else {
					$errorflag = true;
					array_push($currentItemError, "Description of Goods and HSN Code should be valid.");
				}

				$dataArray['advance_amount'] = isset($data['W']) ? round($data['W'], 2) : 0.000;
				$dataArray['cgst_rate'] = isset($data['X']) ? round($data['X'], 3) : 0.000;
				$dataArray['sgst_rate'] = isset($data['Y']) ? round($data['Y'], 3) : 0.000;
				$dataArray['igst_rate'] = isset($data['Z']) ? round($data['Z'], 3) : 0.000;
				$dataArray['cess_rate'] = isset($data['AA']) ? round($data['AA'], 3) : 0.000;

				/* get current user data */
				$dataCurrentUserArr = $this->getUserDetailsById($this->sanitize($_SESSION['user_detail']['user_id']));

				if(isset($dataArray['supply_place']) && $dataCurrentUserArr['data']->kyc->state_id === $dataArray['supply_place']) {

					if($dataArray['cgst_rate'] != $dataArray['sgst_rate']) {
						$errorflag = true;
						array_push($currentItemError, "CGST and SGST rate should be same for item number.");
					}
				}

				/* check reference number */
				$referenceStatus = $this->checkReferenceNumberExist($dataArray['reference_number'], $this->sanitize($_SESSION['user_detail']['user_id']));
				if($referenceStatus == true) {
					$errorflag = true;
					array_push($currentItemError, "You have already used this reference number.");
				}

				/* Invoice Description */
				$dataArray['description'] = isset($data['AB']) ? $data['AB'] : '';

				$invoiceErrors = $this->validateClientInvoiceExcel($dataArray);
				if ($invoiceErrors !== true || !empty($currentItemError)) {

					$errorflag = true;
					if ($invoiceErrors === true) {
						$invoiceErrors = array();
					}
					$invoiceErrors = array_merge($invoiceErrors, $currentItemError);
					$invoiceErrors = implode(", ", $invoiceErrors);
					$objPHPExcel->getActiveSheet()->SetCellValue('AC' . $rowKey, $invoiceErrors);
				}

				if ($errorflag === false) {

					/* create invoice array */
					$arrayKey = $dataArray['reference_number'];

					$invoiceArray[$arrayKey]['invoice_type'] = 'receiptvoucherinvoice';
					$invoiceArray[$arrayKey]['invoice_nature'] = 'salesinvoice';
					$invoiceArray[$arrayKey]['reference_number'] = $dataArray['reference_number'];
					$invoiceArray[$arrayKey]['company_name'] = $dataCurrentUserArr['data']->kyc->name;
					$invoiceArray[$arrayKey]['company_address'] = $dataCurrentUserArr['data']->kyc->full_address;
					$invoiceArray[$arrayKey]['company_email'] = $dataCurrentUserArr['data']->kyc->email;
					$invoiceArray[$arrayKey]['company_phone_number'] = $dataCurrentUserArr['data']->kyc->phone_number;
					$invoiceArray[$arrayKey]['company_state'] = $dataCurrentUserArr['data']->kyc->state_id;
					$invoiceArray[$arrayKey]['gstin_number'] = $dataCurrentUserArr['data']->kyc->gstin_number;
					$invoiceArray[$arrayKey]['invoice_date'] = $dataArray['invoice_date'];
					$invoiceArray[$arrayKey]['supply_place'] = $dataArray['supply_place'];				
					$invoiceArray[$arrayKey]['is_tax_payable'] = $dataArray['is_tax_payable'];
					$invoiceArray[$arrayKey]['billing_name'] = $dataArray['billing_name'];
					$invoiceArray[$arrayKey]['billing_company_name'] = $dataArray['billing_company_name'];
					$invoiceArray[$arrayKey]['billing_address'] = $dataArray['billing_address'];
					$invoiceArray[$arrayKey]['billing_state'] = $dataArray['billing_state'];
					$invoiceArray[$arrayKey]['billing_state_name'] = $dataArray['billing_state_name'];
					$invoiceArray[$arrayKey]['billing_country'] = $dataArray['billing_country'];
					$invoiceArray[$arrayKey]['billing_vendor_type'] = $dataArray['billing_vendor_type'];
					$invoiceArray[$arrayKey]['billing_gstin_number'] = $dataArray['billing_gstin_number'];
					$invoiceArray[$arrayKey]['shipping_name'] = $dataArray['shipping_name'];
					$invoiceArray[$arrayKey]['shipping_company_name'] = $dataArray['shipping_company_name'];
					$invoiceArray[$arrayKey]['shipping_address'] = $dataArray['shipping_address'];
					$invoiceArray[$arrayKey]['shipping_state'] = $dataArray['shipping_state'];
					$invoiceArray[$arrayKey]['shipping_state_name'] = $dataArray['shipping_state_name'];
					$invoiceArray[$arrayKey]['shipping_country'] = $dataArray['shipping_country'];
					$invoiceArray[$arrayKey]['shipping_vendor_type'] = $dataArray['shipping_vendor_type'];
					$invoiceArray[$arrayKey]['shipping_gstin_number'] = $dataArray['shipping_gstin_number'];
					$invoiceArray[$arrayKey]['description'] = $dataArray['description'];

					//items
					$invoiceItemArray['item_id'] = $dataArray['item_id'];
					$invoiceItemArray['item_name'] = $dataArray['item_name'];
					$invoiceItemArray['item_hsncode'] = $dataArray['item_hsncode'];
					$invoiceItemArray['item_description'] = $dataArray['item_description'];
					$invoiceItemArray['is_applicable'] = $dataArray['is_applicable'];
					$invoiceItemArray['advance_amount'] = $dataArray['advance_amount'];
					$invoiceItemArray['cgst_rate'] = $dataArray['cgst_rate'];
					$invoiceItemArray['sgst_rate'] = $dataArray['sgst_rate'];
					$invoiceItemArray['igst_rate'] = $dataArray['igst_rate'];
					$invoiceItemArray['cess_rate'] = $dataArray['cess_rate'];

					$invoiceArray[$arrayKey]['items'][] = $invoiceItemArray;
				}
            }

            if ($errorflag === true) {

				$objPHPExcel->getActiveSheet()->SetCellValue('AC1', "Error Information");
				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
				$objWriter->save($invoice_excel_dir_path);
				$this->setError($this->validationMessage['excelerror']);
				$resultArray = array("status" => "error", "excelurl" => $invoice_excel_url_path);
				return json_encode($resultArray);
			} else {

                foreach ($invoiceArray as $invoiceRow) {

					$invoiceItemArray = array();
					$invoiceTotalAmount = 0.00;
					$consolidateRate = 0.00;

					foreach ($invoiceRow['items'] as $invoiceInnerRow) {
						
						$invoiceItemTaxableAmount = (float) $invoiceInnerRow['advance_amount'];
						
						if($invoiceRow['company_state'] === $invoiceRow['supply_place']) {

							$itemCSGTTax = (float)$invoiceInnerRow['cgst_rate'];
							$itemSGSTTax = (float)$invoiceInnerRow['sgst_rate'];
							$itemIGSTTax = 0.00;
							$itemCESSTax = (float)$invoiceInnerRow['cess_rate'];
							$consolidateRate = $itemCSGTTax + $itemSGSTTax;

							$invoiceItemCSGTTaxAmount = ($itemCSGTTax/100) * $invoiceItemTaxableAmount;
							$invoiceItemSGSTTaxAmount = ($itemSGSTTax/100) * $invoiceItemTaxableAmount;
							$invoiceItemIGSTTaxAmount = 0.00;
							$invoiceItemCESSTaxAmount = ($itemCESSTax/100) * $invoiceItemTaxableAmount;
						} else {
							
							$itemCSGTTax = 0.00;
							$itemSGSTTax = 0.00;
							$itemIGSTTax = (float)$invoiceInnerRow['igst_rate'];
							$itemCESSTax = (float)$invoiceInnerRow['cess_rate'];
							$consolidateRate = $itemIGSTTax;

							$invoiceItemCSGTTaxAmount = 0.00;
							$invoiceItemSGSTTaxAmount = 0.00;
							$invoiceItemIGSTTaxAmount = ($itemIGSTTax/100) * $invoiceItemTaxableAmount;
							$invoiceItemCESSTaxAmount = ($itemCESSTax/100) * $invoiceItemTaxableAmount;
						}
						
						if ($invoiceRow['is_tax_payable'] == "1") {

							$invoiceItemTotalAmount = $invoiceItemTaxableAmount;
							$invoiceTotalAmount += $invoiceItemTotalAmount;
						} else {

							$invoiceItemTotalAmount = ($invoiceItemTaxableAmount + $invoiceItemCSGTTaxAmount + $invoiceItemSGSTTaxAmount + $invoiceItemIGSTTaxAmount + $invoiceItemCESSTaxAmount);
							$invoiceTotalAmount += $invoiceItemTotalAmount;
						}

						$ItemArray = array(
							"item_id" => $invoiceInnerRow['item_id'],
							"item_name" => $invoiceInnerRow['item_name'],
							"item_hsncode" => $invoiceInnerRow['item_hsncode'],
							"item_description" => $invoiceInnerRow['item_description'],
							"is_applicable" => $invoiceInnerRow['is_applicable'],
							"taxable_subtotal" => round($invoiceItemTaxableAmount, 2),
							"cgst_rate" => $itemCSGTTax,
							"cgst_amount" => round($invoiceItemCSGTTaxAmount, 2),
							"sgst_rate" => $itemSGSTTax,
							"sgst_amount" => round($invoiceItemSGSTTaxAmount, 2),
							"igst_rate" => $itemIGSTTax,
							"igst_amount" => round($invoiceItemIGSTTaxAmount, 2),
							"cess_rate" => $itemCESSTax,
							"cess_amount" => round($invoiceItemCESSTaxAmount, 2),
							"consolidate_rate" => $consolidateRate,
							"total" => round($invoiceItemTotalAmount, 2),
							"status" => 1,
							"added_by" => $this->sanitize($_SESSION['user_detail']['user_id']),
							"added_date" => date('Y-m-d H:i:s')
						);

						array_push($invoiceItemArray, $ItemArray);
					}

                    if (!empty($invoiceItemArray) && count($invoiceItemArray) > 0) {
						
						$InsertArray['invoice_type'] = $invoiceRow['invoice_type'];
						$InsertArray['invoice_nature'] = $invoiceRow['invoice_nature'];
						$InsertArray['reference_number'] = $invoiceRow['reference_number'];
						$InsertArray['serial_number'] = $this->generateRVInvoiceNumber($this->sanitize($_SESSION['user_detail']['user_id']));
						$InsertArray['company_name'] = $invoiceRow['company_name'];
						$InsertArray['company_address'] = $invoiceRow['company_address'];
						$InsertArray['company_email'] = $invoiceRow['company_email'];
						$InsertArray['company_phone_number'] = $invoiceRow['company_phone_number'];
						$InsertArray['company_state'] = $invoiceRow['company_state'];
						$InsertArray['gstin_number'] = $invoiceRow['gstin_number'];
						$InsertArray['invoice_date'] = $invoiceRow['invoice_date'];
						$InsertArray['is_tax_payable'] = $invoiceRow['is_tax_payable'];
						$InsertArray['supply_place'] = $invoiceRow['supply_place'];
                        $InsertArray['billing_name'] = $invoiceRow['billing_name'];
						$InsertArray['billing_company_name'] = $invoiceRow['billing_company_name'];
						$InsertArray['billing_address'] = $invoiceRow['billing_address'];
						$InsertArray['billing_state'] = $invoiceRow['billing_state'];
						$InsertArray['billing_state_name'] = $invoiceRow['billing_state_name'];
						$InsertArray['billing_country'] = $invoiceRow['billing_country'];
						$InsertArray['billing_vendor_type'] = $invoiceRow['billing_vendor_type'];
						$InsertArray['billing_gstin_number'] = $invoiceRow['billing_gstin_number'];
						$InsertArray['shipping_name'] = $invoiceRow['shipping_name'];
						$InsertArray['shipping_company_name'] = $invoiceRow['shipping_company_name'];
						$InsertArray['shipping_address'] = $invoiceRow['shipping_address'];
						$InsertArray['shipping_state'] = $invoiceRow['shipping_state'];
						$InsertArray['shipping_state_name'] = $invoiceRow['shipping_state_name'];
						$InsertArray['shipping_country'] = $invoiceRow['shipping_country'];
						$InsertArray['shipping_vendor_type'] = $invoiceRow['shipping_vendor_type'];
						$InsertArray['shipping_gstin_number'] = $invoiceRow['shipping_gstin_number'];
						$InsertArray['description'] = $invoiceRow['description'];						
						$InsertArray['invoice_total_value'] = number_format($invoiceTotalAmount, 2, '.', '');
						$InsertArray['financial_year'] = $this->generateFinancialYear();
						$InsertArray['status'] = 1;
						$InsertArray['created_from'] = 'E';
						$InsertArray['added_by'] = $_SESSION['user_detail']['user_id'];
						$InsertArray['added_date'] = date('Y-m-d H:i:s');

						if($invoiceRow['is_tax_payable'] == "1") {
							$InsertArray['supply_type'] = "reversecharge";
						}

						if ($this->insert($this->tableNames['client_invoice'], $InsertArray)) {

							$insertid = $this->getInsertID();
							$this->logMsg("RV Invoice Added. ID : " . $insertid . ".","client_create_Rv_invoice");

							$processedInvoiceItemArray = array();
							foreach ($invoiceItemArray as $itemArr) {

								$itemArr['invoice_id'] = $insertid;
								array_push($processedInvoiceItemArray, $itemArr);
							}

							if ($this->insertMultiple($this->tableNames['client_invoice_item'], $processedInvoiceItemArray)) {

								$iteminsertid = $this->getInsertID();
								$this->logMsg("RV Invoice Item Added. ID : " . $iteminsertid . ".");
							}
						}
                    }
                }

                $this->setSuccess($this->validationMessage['invoiceadded']);
                return true;
            }
        }
    }

    /* upload client Revised tax invoices / debit note / credit note invoice */
    public function uploadClientRTInvoice() {

		$flag = true;
		$errorflag = false;
		$dataArray = array();
		$indexArray = array();
		$invoiceArray = array();
		$invoiceItemArray = array();
		$currentFinancialYear = $this->generateFinancialYear();

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

				$reason_issuing_document = isset($data['C']) ? $data['C'] : '';
				if(in_array($reason_issuing_document, $this->validateCDnRReason)) {
					$dataArray['reason_issuing_document'] = $reason_issuing_document;
				} else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Issuing Reason.");
				}

				$invoice_type = isset($data['D']) ? $data['D'] : '';
				if ($invoice_type != '' && strtoupper($invoice_type) === 'REVISED TAX INVOICE') {
                    $dataArray['invoice_type'] = "revisedtaxinvoice";
                } else if ($invoice_type != '' && strtoupper($invoice_type) === 'CREDIT NOTE') {
					$dataArray['invoice_type'] = "creditnote";
                } else if ($invoice_type != '' && strtoupper($invoice_type) === 'DEBIT NOTE') {
                    $dataArray['invoice_type'] = "debitnote";
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Invoice Type.");
                }

				$invoice_corresponding_type = isset($data['E']) ? $data['E'] : '';
				if ($invoice_corresponding_type != '' && strtoupper($invoice_corresponding_type) === 'TAX INVOICE') {
                    $dataArray['invoice_corresponding_type'] = "taxinvoice";
                } else if ($invoice_corresponding_type != '' && strtoupper($invoice_corresponding_type) === 'BILL OF SUPPLY') {
                    $dataArray['invoice_corresponding_type'] = "billofsupplyinvoice";
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Corresponding Type.");
                }

                $corresponding_document_number = isset($data['F']) ? $data['F'] : '';
				$correspondingTypeData = $this->get_row("select 
													invoice_id, 
													serial_number, 
													reference_number, 
													invoice_type, 
													invoice_date 
													from " . $this->getTableName('client_invoice') . " 
													where 1=1 AND reference_number = '".$corresponding_document_number."' AND invoice_type = '".$dataArray['invoice_corresponding_type']."' AND is_canceled='0' AND is_deleted = '0' AND status = '1' AND financial_year = '".$currentFinancialYear."' AND added_by = '".$this->sanitize($_SESSION['user_detail']['user_id'])."'");

				if(!empty($correspondingTypeData)) {
					
					$dataArray['corresponding_document_number'] = $correspondingTypeData->invoice_id;
					$dataArray['corresponding_document_date'] = $correspondingTypeData->invoice_date;
				} else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Corresponding Document Number.");
                }

				$supply_place = isset($data['G']) ? $data['G'] : '';
				if ($supply_place != '') {

					$supply_state_data = $this->getStateDetailByStateNameCode($supply_place);
					if ($supply_state_data['status'] === "success") {
						$dataArray['supply_place'] = $supply_state_data['data']->state_id;
					} else {
						$errorflag = true;
						array_push($currentItemError, "Invalid Place Of Supply.");
					}
				} else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Place Of Supply.");
				}

				$dataArray['billing_name'] = isset($data['H']) ? $data['H'] : '';
				$dataArray['billing_company_name'] = isset($data['I']) ? $data['I'] : '';
				$dataArray['billing_address'] = isset($data['J']) ? $data['J'] : '';

				$billing_state = isset($data['K']) ? $data['K'] : '';
				if ($billing_state != '') {

					$billing_state_data = $this->getStateDetailByStateNameCode($billing_state);
					if ($billing_state_data['status'] === "success") {
						$dataArray['billing_state'] = $billing_state_data['data']->state_id;
						$dataArray['billing_state_name'] = $billing_state_data['data']->state_name;
					} else {
						$errorflag = true;
						array_push($currentItemError, "Invalid Billing State.");
					}
				} else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Billing State.");
				}

				$billing_country = isset($data['L']) ? $data['L'] : '';
				if ($billing_country != '') {

					$billing_country_data = $this->getCountryDetailByCountryCode($billing_country);
					if ($billing_country_data['status'] === "success") {
						$dataArray['billing_country'] = $billing_country_data['data']->id;
					} else {
						$errorflag = true;
						array_push($currentItemError, "Invalid Billing Country.");
					}
				} else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Billing Country.");
				}

				$billing_vendor_type = isset($data['M']) ? $data['M'] : '';
				if ($billing_vendor_type != '') {

					$dataVendorNameArrs = $this->get_row("select vendor_id, vendor_name from ".$this->tableNames['vendor_type']." where 1=1 AND UPPER(vendor_name) = '".strtoupper($billing_vendor_type)."' AND status='1' AND is_deleted='0'");
					if (!empty($dataVendorNameArrs) && isset($dataVendorNameArrs->vendor_id)) {
						$dataArray['billing_vendor_type'] = $dataVendorNameArrs->vendor_id;
					} else {
						$errorflag = true;
						array_push($currentItemError, "Invalid Billing Vendor Type.");
					}
				} else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Billing Vendor Type.");
				}

				$dataArray['billing_gstin_number'] = isset($data['N']) ? $data['N'] : '';

				$dataArray['shipping_name'] = isset($data['O']) ? $data['O'] : '';
				$dataArray['shipping_company_name'] = isset($data['P']) ? $data['P'] : '';
				$dataArray['shipping_address'] = isset($data['Q']) ? $data['Q'] : '';

				$shipping_state = isset($data['R']) ? $data['R'] : '';
				if ($shipping_state != '') {

					$shipping_state_data = $this->getStateDetailByStateNameCode($shipping_state);
					if ($shipping_state_data['status'] === "success") {
						$dataArray['shipping_state'] = $shipping_state_data['data']->state_id;
						$dataArray['shipping_state_name'] = $shipping_state_data['data']->state_name;
					} else {
						$errorflag = true;
						array_push($currentItemError, "Invalid Shipping State.");
					}
				} else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Shipping State.");
				}

				$shipping_country = isset($data['S']) ? $data['S'] : '';
				if ($shipping_country != '') {

					$shipping_country_data = $this->getCountryDetailByCountryCode($shipping_country);
					if ($shipping_country_data['status'] === "success") {
						$dataArray['shipping_country'] = $shipping_country_data['data']->id;
					} else {
						$errorflag = true;
						array_push($currentItemError, "Invalid Shipping Country.");
					}
				} else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Shipping Country.");
				}

				$shipping_vendor_type = isset($data['T']) ? $data['T'] : '';
				if ($shipping_vendor_type != '') {

					$dataVendorNameArrs = $this->get_row("select vendor_id, vendor_name from ".$this->tableNames['vendor_type']." where 1=1 AND UPPER(vendor_name) = '".strtoupper($shipping_vendor_type)."' AND status='1' AND is_deleted='0'");
					if (!empty($dataVendorNameArrs) && isset($dataVendorNameArrs->vendor_id)) {
						$dataArray['shipping_vendor_type'] = $dataVendorNameArrs->vendor_id;
					} else {
						$errorflag = true;
						array_push($currentItemError, "Invalid Shipping Vendor Type.");
					}
				} else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Shipping Vendor Type.");
				}

				$dataArray['shipping_gstin_number'] = isset($data['U']) ? $data['U'] : '';

				$item_name = isset($data['V']) ? trim($data['V']) : '';
				$item_hsncode = isset($data['W']) ? trim($data['W']) : '';

				$dataArray['item_description'] = isset($data['X']) ? trim($data['X']) : '';
				$item_description = $dataArray['item_description'];
				
				$applicable_tax = isset($data['Y']) ? $data['Y'] : '';
				if ($applicable_tax != '' && strtoupper($applicable_tax) == 'NON GST') {
					$dataArray['is_applicable'] = '1';
					$is_applicable = '1';
				} else if ($applicable_tax != '' && strtoupper($applicable_tax) == 'EXEMPTED') {
					$dataArray['is_applicable'] = '2';
					$is_applicable = '2';
				} else {
					$dataArray['is_applicable'] = '0';
					$is_applicable = '0';
				}

				$dataArray['item_quantity'] = isset($data['Z']) ? round($data['Z'], 2) : '';

				$dataArray['item_unit'] = isset($data['AA']) ? $data['AA'] : '';
				$item_unit =  $dataArray['item_unit'];
				
				$dataArray['item_rate'] = isset($data['AB']) ? round($data['AB'], 2) : 0.00;
				$item_rate = round($dataArray['item_rate'], 2);

				$dataArray['item_discount'] = isset($data['AC']) ? round($data['AC'], 2) : 0.00;
				$dataArray['cgst_rate'] = isset($data['AD']) ? round($data['AD'], 3) : 0.000;
				$dataArray['sgst_rate'] = isset($data['AE']) ? round($data['AE'], 3) : 0.000;
				$dataArray['igst_rate'] = isset($data['AF']) ? round($data['AF'], 3) : 0.000;
				$dataArray['cess_rate'] = isset($data['AG']) ? round($data['AG'], 3) : 0.000;

				if(!empty($item_name) && !empty($item_hsncode)) {

					$checkClientMasterItem = $this->get_row("select cm.item_id, cm.is_applicable, cm.item_name, cm.unit_price, cm.item_description, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, m.igst_tax_rate, m.csgt_tax_rate, m.sgst_tax_rate, m.cess_tax_rate, cm.item_unit from " . $this->tableNames['client_master_item'] . " as cm, " . $this->tableNames['item'] . " as m where 1=1 AND cm.item_category = m.item_id AND cm.item_name = '" . $item_name . "' && m.hsn_code = '" . $item_hsncode . "' AND cm.is_deleted='0' AND cm.added_by = '" . $this->sanitize($_SESSION['user_detail']['user_id']) . "'");
					if (!empty($checkClientMasterItem)) {

						$dataArray['item_id'] = $checkClientMasterItem->item_id;
						$dataArray['item_name'] = $item_name;
						$dataArray['item_hsncode'] = $item_hsncode;
					} else {

						$masterItem = $this->get_row("select item_id, item_name, hsn_code from " . $this->tableNames['item'] . " where hsn_code='".$item_hsncode."' and is_deleted='0' AND status='1'");						
						if(!empty($masterItem)) {

							$masterUnit = $this->get_row("select unit_id from " . $this->tableNames['unit'] . " as u where u.unit_code='".$item_unit."' and u.is_deleted='0' AND u.status = '1'");
							if(!empty($masterUnit)) {
								$master_unit_id = $masterUnit->unit_id;
							} else {
								
								$masterNUnit = $this->get_row("select unit_id from " . $this->tableNames['unit'] . " as u where u.unit_code='NA' and u.is_deleted='0' AND u.status = '1'");
								if(!empty($masterNUnit)) {
									$master_unit_id = $masterNUnit->unit_id;
								} else {
									$master_unit_id = 0;
								}
							}

							$dataInsertItemArray['item_name'] = $item_name;
							$dataInsertItemArray['item_category'] = $masterItem->item_id;
							$dataInsertItemArray['item_description'] = $item_description;
							$dataInsertItemArray['is_applicable'] = $is_applicable;
							$dataInsertItemArray['unit_price'] = $item_rate;
							$dataInsertItemArray['cgst_tax_rate'] = $dataArray['cgst_rate'];
							$dataInsertItemArray['sgst_tax_rate'] = $dataArray['sgst_rate'];
							$dataInsertItemArray['igst_tax_rate'] = $dataArray['igst_rate'];
							$dataInsertItemArray['cess_tax_rate'] = $dataArray['cess_rate'];
							$dataInsertItemArray['item_unit'] = $master_unit_id;
							$dataInsertItemArray['status'] = '1';
							$dataInsertItemArray['added_by'] = $this->sanitize($_SESSION['user_detail']['user_id']);
							$dataInsertItemArray['added_date'] = date('Y-m-d H:i:s');

							if ($this->insert($this->tableNames['client_master_item'], $dataInsertItemArray)) {

								$iteminsertid = $this->getInsertID();
								$dataArray['item_id'] = $iteminsertid;
								$dataArray['item_name'] = $item_name;
								$dataArray['item_hsncode'] = $masterItem->hsn_code;
							} else {
								$errorflag = true;
								array_push($currentItemError, $this->getValMsg('failed'));
							}
						} else {
							$errorflag = true;
							array_push($currentItemError, "Description of Goods and HSN Code should be valid.");
						}
					}
				} else {
					$errorflag = true;
					array_push($currentItemError, "Description of Goods and HSN Code should be valid.");
				}

				/* get current user data */
				$dataCurrentUserArr = $this->getUserDetailsById($this->sanitize($_SESSION['user_detail']['user_id']));

				if(isset($dataArray['supply_place']) && $dataCurrentUserArr['data']->kyc->state_id === $dataArray['supply_place']) {

					if($dataArray['cgst_rate'] != $dataArray['sgst_rate']) {
						$errorflag = true;
						array_push($currentItemError, "CGST and SGST rate should be same for item number.");
					}
				}

				/* check reference number */
				$referenceStatus = $this->checkReferenceNumberExist($dataArray['reference_number'], $this->sanitize($_SESSION['user_detail']['user_id']));
				if($referenceStatus == true) {
					$errorflag = true;
					array_push($currentItemError, "You have already used this reference number.");
				}

				/* Invoice Description */
				$dataArray['description'] = isset($data['AH']) ? $data['AH'] : '';

				$invoiceErrors = $this->validateClientInvoiceExcel($dataArray);
				if ($invoiceErrors !== true || !empty($currentItemError)) {

					$errorflag = true;
					if ($invoiceErrors === true) {
						$invoiceErrors = array();
					}
					$invoiceErrors = array_merge($invoiceErrors, $currentItemError);
					$invoiceErrors = implode(", ", $invoiceErrors);
					$objPHPExcel->getActiveSheet()->SetCellValue('AI' . $rowKey, $invoiceErrors);
				}
				
				if ($errorflag === false) {

					/* create invoice array */
					$arrayKey = $dataArray['reference_number'];

					$invoiceArray[$arrayKey]['invoice_type'] = $dataArray['invoice_type'];
					$invoiceArray[$arrayKey]['invoice_nature'] = 'salesinvoice';
					$invoiceArray[$arrayKey]['reference_number'] = $dataArray['reference_number'];
					$invoiceArray[$arrayKey]['company_name'] = $dataCurrentUserArr['data']->kyc->name;
					$invoiceArray[$arrayKey]['company_address'] = $dataCurrentUserArr['data']->kyc->full_address;
					$invoiceArray[$arrayKey]['company_email'] = $dataCurrentUserArr['data']->kyc->email;
					$invoiceArray[$arrayKey]['company_phone_number'] = $dataCurrentUserArr['data']->kyc->phone_number;
					$invoiceArray[$arrayKey]['company_state'] = $dataCurrentUserArr['data']->kyc->state_id;
					$invoiceArray[$arrayKey]['gstin_number'] = $dataCurrentUserArr['data']->kyc->gstin_number;
					$invoiceArray[$arrayKey]['invoice_date'] = $dataArray['invoice_date'];
					$invoiceArray[$arrayKey]['reason_issuing_document'] = $dataArray['reason_issuing_document'];
					$invoiceArray[$arrayKey]['invoice_corresponding_type'] = $dataArray['invoice_corresponding_type'];
					$invoiceArray[$arrayKey]['corresponding_document_number'] = $dataArray['corresponding_document_number'];
					$invoiceArray[$arrayKey]['corresponding_document_date'] = $dataArray['corresponding_document_date'];
					$invoiceArray[$arrayKey]['supply_place'] = $dataArray['supply_place'];
					$invoiceArray[$arrayKey]['billing_name'] = $dataArray['billing_name'];
					$invoiceArray[$arrayKey]['billing_company_name'] = $dataArray['billing_company_name'];
					$invoiceArray[$arrayKey]['billing_address'] = $dataArray['billing_address'];
					$invoiceArray[$arrayKey]['billing_state'] = $dataArray['billing_state'];
					$invoiceArray[$arrayKey]['billing_state_name'] = $dataArray['billing_state_name'];
					$invoiceArray[$arrayKey]['billing_country'] = $dataArray['billing_country'];
					$invoiceArray[$arrayKey]['billing_vendor_type'] = $dataArray['billing_vendor_type'];
					$invoiceArray[$arrayKey]['billing_gstin_number'] = $dataArray['billing_gstin_number'];
					$invoiceArray[$arrayKey]['shipping_name'] = $dataArray['shipping_name'];
					$invoiceArray[$arrayKey]['shipping_company_name'] = $dataArray['shipping_company_name'];
					$invoiceArray[$arrayKey]['shipping_address'] = $dataArray['shipping_address'];
					$invoiceArray[$arrayKey]['shipping_state'] = $dataArray['shipping_state'];
					$invoiceArray[$arrayKey]['shipping_state_name'] = $dataArray['shipping_state_name'];
					$invoiceArray[$arrayKey]['shipping_country'] = $dataArray['shipping_country'];
					$invoiceArray[$arrayKey]['shipping_vendor_type'] = $dataArray['shipping_vendor_type'];
					$invoiceArray[$arrayKey]['shipping_gstin_number'] = $dataArray['shipping_gstin_number'];
					$invoiceArray[$arrayKey]['description'] = $dataArray['description'];

					//items
					$invoiceItemArray['item_id'] = $dataArray['item_id'];
					$invoiceItemArray['item_name'] = $dataArray['item_name'];
					$invoiceItemArray['item_hsncode'] = $dataArray['item_hsncode'];
					$invoiceItemArray['item_description'] = $dataArray['item_description'];
					$invoiceItemArray['is_applicable'] = $dataArray['is_applicable'];
					$invoiceItemArray['item_quantity'] = $dataArray['item_quantity'];
					$invoiceItemArray['item_unit'] = $dataArray['item_unit'];
					$invoiceItemArray['item_unit_price'] = $dataArray['item_rate'];
					$invoiceItemArray['item_discount'] = $dataArray['item_discount'];
					$invoiceItemArray['cgst_rate'] = $dataArray['cgst_rate'];
					$invoiceItemArray['sgst_rate'] = $dataArray['sgst_rate'];
					$invoiceItemArray['igst_rate'] = $dataArray['igst_rate'];
					$invoiceItemArray['cess_rate'] = $dataArray['cess_rate'];

					$invoiceArray[$arrayKey]['items'][] = $invoiceItemArray;
				}
            }

            if ($errorflag === true) {

				$objPHPExcel->getActiveSheet()->SetCellValue('AI1', "Error Information");
				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
				$objWriter->save($invoice_excel_dir_path);
				$this->setError($this->validationMessage['excelerror']);
				$resultArray = array("status" => "error", "excelurl" => $invoice_excel_url_path);
				return json_encode($resultArray);
			} else {

				foreach ($invoiceArray as $invoiceRow) {

					$invoiceItemArray = array();
					$invoiceTotalAmount = 0.00;
					$consolidateRate = 0.00;

					foreach ($invoiceRow['items'] as $invoiceInnerRow) {
						
						$itemUnitPrice = (float) $invoiceInnerRow['item_unit_price'];
						$invoiceItemQuantity = (float) $invoiceInnerRow['item_quantity'];
						$invoiceItemDiscount = (float) $invoiceInnerRow['item_discount'];

						$invoiceItemTotal = $invoiceItemQuantity * $itemUnitPrice;
						$invoiceItemDiscountAmount = ($invoiceItemDiscount / 100) * $invoiceItemTotal;
						$invoiceItemTaxableAmount = $invoiceItemTotal - $invoiceItemDiscountAmount;

						if($invoiceRow['company_state'] === $invoiceRow['supply_place']) {

							$itemCSGTTax = (float)$invoiceInnerRow['cgst_rate'];
							$itemSGSTTax = (float)$invoiceInnerRow['sgst_rate'];
							$itemIGSTTax = 0.00;
							$itemCESSTax = (float)$invoiceInnerRow['cess_rate'];
							$consolidateRate = $itemCSGTTax + $itemSGSTTax;

							$invoiceItemCSGTTaxAmount = ($itemCSGTTax/100) * $invoiceItemTaxableAmount;
							$invoiceItemSGSTTaxAmount = ($itemSGSTTax/100) * $invoiceItemTaxableAmount;
							$invoiceItemIGSTTaxAmount = 0.00;
							$invoiceItemCESSTaxAmount = ($itemCESSTax/100) * $invoiceItemTaxableAmount;
						} else {
							
							$itemCSGTTax = 0.00;
							$itemSGSTTax = 0.00;
							$itemIGSTTax = (float)$invoiceInnerRow['igst_rate'];
							$itemCESSTax = (float)$invoiceInnerRow['cess_rate'];
							$consolidateRate = $itemIGSTTax;

							$invoiceItemCSGTTaxAmount = 0.00;
							$invoiceItemSGSTTaxAmount = 0.00;
							$invoiceItemIGSTTaxAmount = ($itemIGSTTax/100) * $invoiceItemTaxableAmount;
							$invoiceItemCESSTaxAmount = ($itemCESSTax/100) * $invoiceItemTaxableAmount;
						}

						$invoiceItemTotalAmount = ($invoiceItemTaxableAmount + $invoiceItemCSGTTaxAmount + $invoiceItemSGSTTaxAmount + $invoiceItemIGSTTaxAmount + $invoiceItemCESSTaxAmount);
						$invoiceTotalAmount += $invoiceItemTotalAmount;

						$ItemArray = array(
							"item_id" => $invoiceInnerRow['item_id'],
							"item_name" => $invoiceInnerRow['item_name'],
							"item_hsncode" => $invoiceInnerRow['item_hsncode'],
							"item_description" => $invoiceInnerRow['item_description'],
							"is_applicable" => $invoiceInnerRow['is_applicable'],
							"item_quantity" => $invoiceItemQuantity,
							"item_unit" => $invoiceInnerRow['item_unit'],
							"item_unit_price" => $itemUnitPrice,
							"subtotal" => round($invoiceItemTotal, 2),
							"discount" => $invoiceItemDiscount,
							"taxable_subtotal" => round($invoiceItemTaxableAmount, 2),
							"cgst_rate" => $itemCSGTTax,
							"cgst_amount" => round($invoiceItemCSGTTaxAmount, 2),
							"sgst_rate" => $itemSGSTTax,
							"sgst_amount" => round($invoiceItemSGSTTaxAmount, 2),
							"igst_rate" => $itemIGSTTax,
							"igst_amount" => round($invoiceItemIGSTTaxAmount, 2),
							"cess_rate" => $itemCESSTax,
							"cess_amount" => round($invoiceItemCESSTaxAmount, 2),
							"consolidate_rate" => $consolidateRate,
							"total" => round($invoiceItemTotalAmount, 2),
							"status" => 1,
							"added_by" => $this->sanitize($_SESSION['user_detail']['user_id']),
							"added_date" => date('Y-m-d H:i:s')
						);

						array_push($invoiceItemArray, $ItemArray);
                    }

					if (!empty($invoiceItemArray) && count($invoiceItemArray) > 0) {

						$InsertArray['invoice_type'] = $invoiceRow['invoice_type'];
						$InsertArray['invoice_nature'] = $invoiceRow['invoice_nature'];
						$InsertArray['reference_number'] = $invoiceRow['reference_number'];
						$InsertArray['serial_number'] = $this->generateRTInvoiceNumber($this->sanitize($_SESSION['user_detail']['user_id']));
						$InsertArray['company_name'] = $invoiceRow['company_name'];
						$InsertArray['company_address'] = $invoiceRow['company_address'];
						$InsertArray['company_email'] = $invoiceRow['company_email'];
						$InsertArray['company_phone_number'] = $invoiceRow['company_phone_number'];
						$InsertArray['company_state'] = $invoiceRow['company_state'];
						$InsertArray['gstin_number'] = $invoiceRow['gstin_number'];
						$InsertArray['invoice_date'] = $invoiceRow['invoice_date'];
						$InsertArray['reason_issuing_document'] = $invoiceRow['reason_issuing_document'];
						$InsertArray['invoice_corresponding_type'] = $invoiceRow['invoice_corresponding_type'];
						$InsertArray['corresponding_document_number'] = $invoiceRow['corresponding_document_number'];
						$InsertArray['corresponding_document_date'] = $invoiceRow['corresponding_document_date'];
						$InsertArray['supply_place'] = $invoiceRow['supply_place'];
						$InsertArray['billing_name'] = $invoiceRow['billing_name'];
						$InsertArray['billing_company_name'] = $invoiceRow['billing_company_name'];
						$InsertArray['billing_address'] = $invoiceRow['billing_address'];
						$InsertArray['billing_state'] = $invoiceRow['billing_state'];
						$InsertArray['billing_state_name'] = $invoiceRow['billing_state_name'];
						$InsertArray['billing_country'] = $invoiceRow['billing_country'];
						$InsertArray['billing_vendor_type'] = $invoiceRow['billing_vendor_type'];
						$InsertArray['billing_gstin_number'] = $invoiceRow['billing_gstin_number'];
						$InsertArray['shipping_name'] = $invoiceRow['shipping_name'];
						$InsertArray['shipping_company_name'] = $invoiceRow['shipping_company_name'];
						$InsertArray['shipping_address'] = $invoiceRow['shipping_address'];
						$InsertArray['shipping_state'] = $invoiceRow['shipping_state'];
						$InsertArray['shipping_state_name'] = $invoiceRow['shipping_state_name'];
						$InsertArray['shipping_country'] = $invoiceRow['shipping_country'];
						$InsertArray['shipping_vendor_type'] = $invoiceRow['shipping_vendor_type'];
						$InsertArray['shipping_gstin_number'] = $invoiceRow['shipping_gstin_number'];
						$InsertArray['description'] = $invoiceRow['description'];						
						$InsertArray['invoice_total_value'] = number_format($invoiceTotalAmount, 2, '.', '');
						$InsertArray['financial_year'] = $this->generateFinancialYear();
						$InsertArray['status'] = 1;
						$InsertArray['created_from'] = 'E';
						$InsertArray['added_by'] = $_SESSION['user_detail']['user_id'];
						$InsertArray['added_date'] = date('Y-m-d H:i:s');

						if ($this->insert($this->tableNames['client_invoice'], $InsertArray)) {

							$insertid = $this->getInsertID();
							$this->logMsg("Revised Tax Invoice Added. ID : " . $insertid . ".","client_create_revised_tax_invoice");

							$processedInvoiceItemArray = array();
							foreach ($invoiceItemArray as $itemArr) {

								$itemArr['invoice_id'] = $insertid;
								array_push($processedInvoiceItemArray, $itemArr);
							}

							if ($this->insertMultiple($this->tableNames['client_invoice_item'], $processedInvoiceItemArray)) {

								$iteminsertid = $this->getInsertID();
								$this->logMsg("Revised Tax Invoice Item Added. ID : " . $iteminsertid . ".");
							}
						}
					}
                }

                $this->setSuccess($this->validationMessage['invoiceadded']);
                return true;
            }
        }
    }

	/* upload client delivery challan invoice */
    public function uploadClientDCInvoice() {

		$flag = true;
		$errorflag = false;
		$dataArray = array();
		$indexArray = array();
		$invoiceArray = array();
		$invoiceItemArray = array();
		$currentFinancialYear = $this->generateFinancialYear();

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

				$delivery_challan_type = isset($data['C']) ? $data['C'] : '';
				if ($delivery_challan_type != '' && strtoupper($delivery_challan_type) === 'JOB WORK') {
                    $dataArray['delivery_challan_type'] = "jobwork";
                } else if ($delivery_challan_type != '' && strtoupper($delivery_challan_type) === 'SUPPLY OF LIQUID GAS') {
					$dataArray['delivery_challan_type'] = "supplyofliquidgas";
                } else if ($delivery_challan_type != '' && strtoupper($delivery_challan_type) === 'SUPPLY ON APPROVAL') {
                    $dataArray['delivery_challan_type'] = "supplyonapproval";
                } else if ($delivery_challan_type != '' && strtoupper($delivery_challan_type) === 'OTHERS') {
                    $dataArray['delivery_challan_type'] = "others";
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Delivery Challan.");
                }

				$supply_place = isset($data['D']) ? $data['D'] : '';
				if ($supply_place != '') {

					$supply_state_data = $this->getStateDetailByStateNameCode($supply_place);
					if ($supply_state_data['status'] === "success") {
						$dataArray['supply_place'] = $supply_state_data['data']->state_id;
					} else {
						$errorflag = true;
						array_push($currentItemError, "Invalid Place Of Supply.");
					}
				} else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Place Of Supply.");
				}

				$dataArray['billing_name'] = isset($data['E']) ? $data['E'] : '';
				$dataArray['billing_company_name'] = isset($data['F']) ? $data['F'] : '';
				$dataArray['billing_address'] = isset($data['G']) ? $data['G'] : '';

				$billing_state = isset($data['H']) ? $data['H'] : '';
				if ($billing_state != '') {

					$billing_state_data = $this->getStateDetailByStateNameCode($billing_state);
					if ($billing_state_data['status'] === "success") {
						$dataArray['billing_state'] = $billing_state_data['data']->state_id;
						$dataArray['billing_state_name'] = $billing_state_data['data']->state_name;
					} else {
						$errorflag = true;
						array_push($currentItemError, "Invalid Consignee State.");
					}
				} else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Consignee State.");
				}

				$billing_country = isset($data['I']) ? $data['I'] : '';
				if ($billing_country != '') {

					$billing_country_data = $this->getCountryDetailByCountryCode($billing_country);
					if ($billing_country_data['status'] === "success") {
						$dataArray['billing_country'] = $billing_country_data['data']->id;
					} else {
						$errorflag = true;
						array_push($currentItemError, "Invalid Consignee Country.");
					}
				} else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Consignee Country.");
				}

				$billing_vendor_type = isset($data['J']) ? $data['J'] : '';
				if ($billing_vendor_type != '') {

					$dataVendorNameArrs = $this->get_row("select vendor_id, vendor_name from ".$this->tableNames['vendor_type']." where 1=1 AND UPPER(vendor_name) = '".strtoupper($billing_vendor_type)."' AND status='1' AND is_deleted='0'");
					if (!empty($dataVendorNameArrs) && isset($dataVendorNameArrs->vendor_id)) {
						$dataArray['billing_vendor_type'] = $dataVendorNameArrs->vendor_id;
					} else {
						$errorflag = true;
						array_push($currentItemError, "Invalid Consignee Vendor Type.");
					}
				} else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Consignee Vendor Type.");
				}

				$dataArray['billing_gstin_number'] = isset($data['K']) ? $data['K'] : '';

				$item_name = isset($data['L']) ? trim($data['L']) : '';
				$item_hsncode = isset($data['M']) ? trim($data['M']) : '';

				$dataArray['item_description'] = isset($data['N']) ? trim($data['N']) : '';
				$item_description = $dataArray['item_description'];

				$applicable_tax = isset($data['O']) ? $data['O'] : '';
				if ($applicable_tax != '' && strtoupper($applicable_tax) == 'NON GST') {
					$dataArray['is_applicable'] = '1';
					$is_applicable = '1';
				} else if ($applicable_tax != '' && strtoupper($applicable_tax) == 'EXEMPTED') {
					$dataArray['is_applicable'] = '2';
					$is_applicable = '2';
				} else {
					$dataArray['is_applicable'] = '0';
					$is_applicable = '0';
				}

				$dataArray['item_quantity'] = isset($data['P']) ? round($data['P'], 3) : '';

				$dataArray['item_unit'] = isset($data['Q']) ? $data['Q'] : '';
				$item_unit =  $dataArray['item_unit'];

				$dataArray['item_rate'] = isset($data['R']) ? round($data['R'], 2) : 0.00;
				$item_rate = round($dataArray['item_rate'], 2);

				$dataArray['item_discount'] = isset($data['S']) ? round($data['S'], 3) : 0.00;
				$dataArray['cgst_rate'] = isset($data['T']) ? round($data['T'], 3) : 0.000;
				$dataArray['sgst_rate'] = isset($data['U']) ? round($data['U'], 3) : 0.000;
				$dataArray['igst_rate'] = isset($data['V']) ? round($data['V'], 3) : 0.000;
				$dataArray['cess_rate'] = isset($data['W']) ? round($data['W'], 3) : 0.000;

				if(!empty($item_name) && !empty($item_hsncode)) {

					$checkClientMasterItem = $this->get_row("select cm.item_id, cm.is_applicable, cm.item_name, cm.unit_price, cm.item_description, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, m.igst_tax_rate, m.csgt_tax_rate, m.sgst_tax_rate, m.cess_tax_rate, cm.item_unit from " . $this->tableNames['client_master_item'] . " as cm, " . $this->tableNames['item'] . " as m where 1=1 AND cm.item_category = m.item_id AND cm.item_name = '" . $item_name . "' && m.hsn_code = '" . $item_hsncode . "' AND cm.is_deleted='0' AND cm.added_by = '" . $this->sanitize($_SESSION['user_detail']['user_id']) . "'");
					if (!empty($checkClientMasterItem)) {

						$dataArray['item_id'] = $checkClientMasterItem->item_id;
						$dataArray['item_name'] = $item_name;
						$dataArray['item_hsncode'] = $item_hsncode;
					} else {

						$masterItem = $this->get_row("select item_id, item_name, hsn_code from " . $this->tableNames['item'] . " where hsn_code='".$item_hsncode."' and is_deleted='0' AND status='1'");						
						if(!empty($masterItem)) {

							$masterUnit = $this->get_row("select unit_id from " . $this->tableNames['unit'] . " as u where u.unit_code='".$item_unit."' and u.is_deleted='0' AND u.status = '1'");
							if(!empty($masterUnit)) {
								$master_unit_id = $masterUnit->unit_id;
							} else {
								
								$masterNUnit = $this->get_row("select unit_id from " . $this->tableNames['unit'] . " as u where u.unit_code='NA' and u.is_deleted='0' AND u.status = '1'");
								if(!empty($masterNUnit)) {
									$master_unit_id = $masterNUnit->unit_id;
								} else {
									$master_unit_id = 0;
								}
							}

							$dataInsertItemArray['item_name'] = $item_name;
							$dataInsertItemArray['item_category'] = $masterItem->item_id;
							$dataInsertItemArray['item_description'] = $item_description;
							$dataInsertItemArray['is_applicable'] = $is_applicable;
							$dataInsertItemArray['unit_price'] = $item_rate;
							$dataInsertItemArray['cgst_tax_rate'] = $dataArray['cgst_rate'];
							$dataInsertItemArray['sgst_tax_rate'] = $dataArray['sgst_rate'];
							$dataInsertItemArray['igst_tax_rate'] = $dataArray['igst_rate'];
							$dataInsertItemArray['cess_tax_rate'] = $dataArray['cess_rate'];
							$dataInsertItemArray['item_unit'] = $master_unit_id;
							$dataInsertItemArray['status'] = '1';
							$dataInsertItemArray['added_by'] = $this->sanitize($_SESSION['user_detail']['user_id']);
							$dataInsertItemArray['added_date'] = date('Y-m-d H:i:s');

							if ($this->insert($this->tableNames['client_master_item'], $dataInsertItemArray)) {

								$iteminsertid = $this->getInsertID();
								$dataArray['item_id'] = $iteminsertid;
								$dataArray['item_name'] = $item_name;
								$dataArray['item_hsncode'] = $masterItem->hsn_code;
							} else {
								$errorflag = true;
								array_push($currentItemError, $this->getValMsg('failed'));
							}
						} else {
							$errorflag = true;
							array_push($currentItemError, "Description of Goods and HSN Code should be valid.");
						}
					}
				} else {
					$errorflag = true;
					array_push($currentItemError, "Description of Goods and HSN Code should be valid.");
				}

				/* get current user data */
				$dataCurrentUserArr = $this->getUserDetailsById($this->sanitize($_SESSION['user_detail']['user_id']));

				if(isset($dataArray['supply_place']) && $dataCurrentUserArr['data']->kyc->state_id === $dataArray['supply_place']) {

					if($dataArray['cgst_rate'] != $dataArray['sgst_rate']) {
						$errorflag = true;
						array_push($currentItemError, "CGST and SGST rate should be same for item number.");
					}
				}

				/* check reference number */
				$referenceStatus = $this->checkReferenceNumberExist($dataArray['reference_number'], $this->sanitize($_SESSION['user_detail']['user_id']));
				if($referenceStatus == true) {
					$errorflag = true;
					array_push($currentItemError, "You have already used this reference number.");
				}

				/* Invoice Description */
				$dataArray['description'] = isset($data['X']) ? $data['X'] : '';

				$invoiceErrors = $this->validateClientInvoiceExcel($dataArray);
				if ($invoiceErrors !== true || !empty($currentItemError)) {

					$errorflag = true;
					if ($invoiceErrors === true) {
						$invoiceErrors = array();
					}
					$invoiceErrors = array_merge($invoiceErrors, $currentItemError);
					$invoiceErrors = implode(", ", $invoiceErrors);
					$objPHPExcel->getActiveSheet()->SetCellValue('Y' . $rowKey, $invoiceErrors);
				}
				
				if ($errorflag === false) {

					/* create invoice array */
					$arrayKey = $dataArray['reference_number'];

					$invoiceArray[$arrayKey]['invoice_type'] = 'deliverychallaninvoice';
					$invoiceArray[$arrayKey]['invoice_nature'] = 'salesinvoice';
					$invoiceArray[$arrayKey]['reference_number'] = $dataArray['reference_number'];
					$invoiceArray[$arrayKey]['company_name'] = $dataCurrentUserArr['data']->kyc->name;
					$invoiceArray[$arrayKey]['company_address'] = $dataCurrentUserArr['data']->kyc->full_address;
					$invoiceArray[$arrayKey]['company_email'] = $dataCurrentUserArr['data']->kyc->email;
					$invoiceArray[$arrayKey]['company_phone_number'] = $dataCurrentUserArr['data']->kyc->phone_number;
					$invoiceArray[$arrayKey]['company_state'] = $dataCurrentUserArr['data']->kyc->state_id;
					$invoiceArray[$arrayKey]['gstin_number'] = $dataCurrentUserArr['data']->kyc->gstin_number;
					$invoiceArray[$arrayKey]['invoice_date'] = $dataArray['invoice_date'];
					$invoiceArray[$arrayKey]['delivery_challan_type'] = $dataArray['delivery_challan_type'];
					$invoiceArray[$arrayKey]['supply_place'] = $dataArray['supply_place'];
					$invoiceArray[$arrayKey]['billing_name'] = $dataArray['billing_name'];
					$invoiceArray[$arrayKey]['billing_company_name'] = $dataArray['billing_company_name'];
					$invoiceArray[$arrayKey]['billing_address'] = $dataArray['billing_address'];
					$invoiceArray[$arrayKey]['billing_state'] = $dataArray['billing_state'];
					$invoiceArray[$arrayKey]['billing_state_name'] = $dataArray['billing_state_name'];
					$invoiceArray[$arrayKey]['billing_country'] = $dataArray['billing_country'];
					$invoiceArray[$arrayKey]['billing_vendor_type'] = $dataArray['billing_vendor_type'];
					$invoiceArray[$arrayKey]['billing_gstin_number'] = $dataArray['billing_gstin_number'];
					$invoiceArray[$arrayKey]['description'] = $dataArray['description'];

					//items
					$invoiceItemArray['item_id'] = $dataArray['item_id'];
					$invoiceItemArray['item_name'] = $dataArray['item_name'];
					$invoiceItemArray['item_hsncode'] = $dataArray['item_hsncode'];
					$invoiceItemArray['item_description'] = $dataArray['item_description'];
					$invoiceItemArray['is_applicable'] = $dataArray['is_applicable'];
					$invoiceItemArray['item_quantity'] = $dataArray['item_quantity'];
					$invoiceItemArray['item_unit'] = $dataArray['item_unit'];
					$invoiceItemArray['item_unit_price'] = $dataArray['item_rate'];
					$invoiceItemArray['item_discount'] = $dataArray['item_discount'];
					$invoiceItemArray['cgst_rate'] = $dataArray['cgst_rate'];
					$invoiceItemArray['sgst_rate'] = $dataArray['sgst_rate'];
					$invoiceItemArray['igst_rate'] = $dataArray['igst_rate'];
					$invoiceItemArray['cess_rate'] = $dataArray['cess_rate'];

					$invoiceArray[$arrayKey]['items'][] = $invoiceItemArray;
				}
            }

            if ($errorflag === true) {

				$objPHPExcel->getActiveSheet()->SetCellValue('Y1', "Error Information");
				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
				$objWriter->save($invoice_excel_dir_path);
				$this->setError($this->validationMessage['excelerror']);
				$resultArray = array("status" => "error", "excelurl" => $invoice_excel_url_path);
				return json_encode($resultArray);
			} else {

				foreach ($invoiceArray as $invoiceRow) {

					$invoiceItemArray = array();
					$invoiceTotalAmount = 0.00;
					$consolidateRate = 0.00;

					foreach ($invoiceRow['items'] as $invoiceInnerRow) {
						
						$itemUnitPrice = (float) $invoiceInnerRow['item_unit_price'];
						$invoiceItemQuantity = (float) $invoiceInnerRow['item_quantity'];
						$invoiceItemDiscount = (float) $invoiceInnerRow['item_discount'];

						$invoiceItemTotal = $invoiceItemQuantity * $itemUnitPrice;
						$invoiceItemDiscountAmount = ($invoiceItemDiscount / 100) * $invoiceItemTotal;
						$invoiceItemTaxableAmount = $invoiceItemTotal - $invoiceItemDiscountAmount;

						if($invoiceRow['company_state'] === $invoiceRow['supply_place']) {

							$itemCSGTTax = (float)$invoiceInnerRow['cgst_rate'];
							$itemSGSTTax = (float)$invoiceInnerRow['sgst_rate'];
							$itemIGSTTax = 0.00;
							$itemCESSTax = (float)$invoiceInnerRow['cess_rate'];
							$consolidateRate = $itemCSGTTax + $itemSGSTTax;

							$invoiceItemCSGTTaxAmount = ($itemCSGTTax/100) * $invoiceItemTaxableAmount;
							$invoiceItemSGSTTaxAmount = ($itemSGSTTax/100) * $invoiceItemTaxableAmount;
							$invoiceItemIGSTTaxAmount = 0.00;
							$invoiceItemCESSTaxAmount = ($itemCESSTax/100) * $invoiceItemTaxableAmount;
						} else {
							
							$itemCSGTTax = 0.00;
							$itemSGSTTax = 0.00;
							$itemIGSTTax = (float)$invoiceInnerRow['igst_rate'];
							$itemCESSTax = (float)$invoiceInnerRow['cess_rate'];
							$consolidateRate = $itemIGSTTax;

							$invoiceItemCSGTTaxAmount = 0.00;
							$invoiceItemSGSTTaxAmount = 0.00;
							$invoiceItemIGSTTaxAmount = ($itemIGSTTax/100) * $invoiceItemTaxableAmount;
							$invoiceItemCESSTaxAmount = ($itemCESSTax/100) * $invoiceItemTaxableAmount;
						}

						$invoiceItemTotalAmount = ($invoiceItemTaxableAmount + $invoiceItemCSGTTaxAmount + $invoiceItemSGSTTaxAmount + $invoiceItemIGSTTaxAmount + $invoiceItemCESSTaxAmount);
						$invoiceTotalAmount += $invoiceItemTotalAmount;

						$ItemArray = array(
							"item_id" => $invoiceInnerRow['item_id'],
							"item_name" => $invoiceInnerRow['item_name'],
							"item_hsncode" => $invoiceInnerRow['item_hsncode'],
							"item_description" => $invoiceInnerRow['item_description'],
							"is_applicable" => $invoiceInnerRow['is_applicable'],
							"item_quantity" => $invoiceItemQuantity,
							"item_unit" => $invoiceInnerRow['item_unit'],
							"item_unit_price" => $itemUnitPrice,
							"subtotal" => round($invoiceItemTotal, 2),
							"discount" => $invoiceItemDiscount,
							"taxable_subtotal" => round($invoiceItemTaxableAmount, 2),
							"cgst_rate" => $itemCSGTTax,
							"cgst_amount" => round($invoiceItemCSGTTaxAmount, 2),
							"sgst_rate" => $itemSGSTTax,
							"sgst_amount" => round($invoiceItemSGSTTaxAmount, 2),
							"igst_rate" => $itemIGSTTax,
							"igst_amount" => round($invoiceItemIGSTTaxAmount, 2),
							"cess_rate" => $itemCESSTax,
							"cess_amount" => round($invoiceItemCESSTaxAmount, 2),
							"consolidate_rate" => $consolidateRate,
							"total" => round($invoiceItemTotalAmount, 2),
							"status" => 1,
							"added_by" => $this->sanitize($_SESSION['user_detail']['user_id']),
							"added_date" => date('Y-m-d H:i:s')
						);

						array_push($invoiceItemArray, $ItemArray);
                    }

					if (!empty($invoiceItemArray) && count($invoiceItemArray) > 0) {

						$InsertArray['invoice_type'] = $invoiceRow['invoice_type'];
						$InsertArray['invoice_nature'] = $invoiceRow['invoice_nature'];
						$InsertArray['reference_number'] = $invoiceRow['reference_number'];
						$InsertArray['serial_number'] = $this->generateDCInvoiceNumber($this->sanitize($_SESSION['user_detail']['user_id']));
						$InsertArray['company_name'] = $invoiceRow['company_name'];
						$InsertArray['company_address'] = $invoiceRow['company_address'];
						$InsertArray['company_email'] = $invoiceRow['company_email'];
						$InsertArray['company_phone_number'] = $invoiceRow['company_phone_number'];
						$InsertArray['company_state'] = $invoiceRow['company_state'];
						$InsertArray['gstin_number'] = $invoiceRow['gstin_number'];
						$InsertArray['invoice_date'] = $invoiceRow['invoice_date'];
						$InsertArray['delivery_challan_type'] = $invoiceRow['delivery_challan_type'];
						$InsertArray['supply_place'] = $invoiceRow['supply_place'];
						$InsertArray['billing_name'] = $invoiceRow['billing_name'];
						$InsertArray['billing_company_name'] = $invoiceRow['billing_company_name'];
						$InsertArray['billing_address'] = $invoiceRow['billing_address'];
						$InsertArray['billing_state'] = $invoiceRow['billing_state'];
						$InsertArray['billing_state_name'] = $invoiceRow['billing_state_name'];
						$InsertArray['billing_country'] = $invoiceRow['billing_country'];
						$InsertArray['billing_vendor_type'] = $invoiceRow['billing_vendor_type'];
						$InsertArray['billing_gstin_number'] = $invoiceRow['billing_gstin_number'];
						$InsertArray['description'] = $invoiceRow['description'];						
						$InsertArray['invoice_total_value'] = number_format($invoiceTotalAmount, 2, '.', '');
						$InsertArray['financial_year'] = $this->generateFinancialYear();
						$InsertArray['status'] = 1;
						$InsertArray['created_from'] = 'E';
						$InsertArray['added_by'] = $_SESSION['user_detail']['user_id'];
						$InsertArray['added_date'] = date('Y-m-d H:i:s');

						if ($this->insert($this->tableNames['client_invoice'], $InsertArray)) {

							$insertid = $this->getInsertID();
							$this->logMsg("Delivery Challan Invoice Added. ID : " . $insertid . ".","client_create_delivery_challan_invoice");

							$processedInvoiceItemArray = array();
							foreach ($invoiceItemArray as $itemArr) {

								$itemArr['invoice_id'] = $insertid;
								array_push($processedInvoiceItemArray, $itemArr);
							}

							if ($this->insertMultiple($this->tableNames['client_invoice_item'], $processedInvoiceItemArray)) {

								$iteminsertid = $this->getInsertID();
								$this->logMsg("Delivery Challan Invoice Item Added. ID : " . $iteminsertid . ".","client_create_delivery_challan_invoice");
							}
						}
					}
                }

                $this->setSuccess($this->validationMessage['invoiceadded']);
                return true;
            }
        }
    }

    /* generate sales invoice html */
    public function generateInvoiceHtml($invid) {

        $currentFinancialYear = $this->generateFinancialYear();

		/* get current user data */
		$dataCurrentUserArr = $this->getUserDetailsById($this->sanitize($_SESSION['user_detail']['user_id']));

        $invoiceData = $this->get_results("select 
											ci.*, 
											cii.invoice_item_id, 
											cii.item_id, 
											cii.item_name, 
											cii.item_hsncode, 
											cii.item_description, 
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
										" . $this->tableNames['client_invoice'] . " as ci INNER JOIN " . $this->tableNames['client_invoice_item'] . " as cii ON ci.invoice_id = cii.invoice_id where ci.invoice_id = " . $invid . " AND ci.invoice_type IN('taxinvoice','exportinvoice','sezunitinvoice','deemedexportinvoice') AND ci.added_by = '" . $this->sanitize($_SESSION['user_detail']['user_id']) . "' AND cii.added_by = '" . $this->sanitize($_SESSION['user_detail']['user_id']) . "' AND ci.is_deleted='0' AND cii.is_deleted='0'");

        if (empty($invoiceData)) {
            return false;
        }

        $dataThemeSettingArr = $this->getUserThemeSetting($this->sanitize($_SESSION['user_detail']['user_id']));
		$dataInvoiceSettingArr = $this->getUserInvoiceSetting($this->sanitize($_SESSION['user_detail']['user_id']));

        $mpdfHtml = '';
        $mpdfHtml .= '<div style="margin:auto;font-size:16px;line-height:24px;color:#555;">';
        $mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;" cellpadding="0" cellspacing="0">';
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td colspan="2" style="vertical-align:top;">';
        $mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;">';
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td style="font-size:45px;line-height:45px;color:#333;padding:5px;vertical-align:top;padding-bottom:20px;">';

        if (isset($dataThemeSettingArr['data']->show_logo) && $dataThemeSettingArr['data']->show_logo == '1' && isset($dataThemeSettingArr['data']->theme_logo) && $dataThemeSettingArr['data']->theme_logo != "") {
            $mpdfHtml .= '<img src="upload/theme-logo/' . $dataThemeSettingArr['data']->theme_logo . '" style="max-width:300px;">';
        }

        $mpdfHtml .= '</td>';

		if($invoiceData[0]->invoice_type == "exportinvoice") { $invoiceType = "Export Invoice"; } 
		else if($invoiceData[0]->invoice_type == "sezunitinvoic") { $invoiceType = "SEZ Unit Invoice"; } 
		else if($invoiceData[0]->invoice_type == "deemedexportinvoice") { $invoiceType = "Deemed Export Invoice"; } 
		else { $invoiceType = "Tax Invoice"; }

		if(isset($dataInvoiceSettingArr['data']->invoice_label) && !empty($dataInvoiceSettingArr['data']->invoice_label)) { $invoice_label = $dataInvoiceSettingArr['data']->invoice_label; } else { $invoice_label = "Invoice #"; }
		if(isset($dataInvoiceSettingArr['data']->reference_label) && !empty($dataInvoiceSettingArr['data']->reference_label)) { $reference_label = $dataInvoiceSettingArr['data']->reference_label; } else { $reference_label = "Reference #"; }
		if(isset($dataInvoiceSettingArr['data']->type_label) && !empty($dataInvoiceSettingArr['data']->type_label)) { $type_label = $dataInvoiceSettingArr['data']->type_label; } else { $type_label = "Type"; }
		if(isset($dataInvoiceSettingArr['data']->nature_label) && !empty($dataInvoiceSettingArr['data']->nature_label)) { $nature_label = $dataInvoiceSettingArr['data']->nature_label; } else { $nature_label = "Nature"; }
		if(isset($dataInvoiceSettingArr['data']->date_label) && !empty($dataInvoiceSettingArr['data']->date_label)) { $date_label = $dataInvoiceSettingArr['data']->date_label; } else { $date_label = "Invoice Date"; }

		$mpdfHtml .= '<td style="vertical-align:top;text-align:right;padding-bottom:20px;">';
        $mpdfHtml .= '<b>'.$invoice_label.'</b>: ' . $invoiceData[0]->serial_number . '<br>';
        $mpdfHtml .= '<b>'.$reference_label.'</b>: ' . $invoiceData[0]->reference_number . '<br>';
		$mpdfHtml .= '<b>'.$type_label.'</b>: ' . $invoiceType . '<br>';
		$mpdfHtml .= '<b>'.$nature_label.'</b>: Sales Invoice<br>';
        $mpdfHtml .= '<b>'.$date_label.'</b>: ' . $invoiceData[0]->invoice_date;
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';
        $mpdfHtml .= '</table>';
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';

        $supply_place_data = $this->getStateDetailByStateId($invoiceData[0]->supply_place);

        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td colspan="2" style="vertical-align:top;">';
        $mpdfHtml .= '<table style="width:100%;line-height:inherit;">';
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td style="text-align:left;vertical-align:top;padding-bottom:20px;width:48%;padding-right:2%;">';
        $mpdfHtml .= html_entity_decode($invoiceData[0]->company_name) . '<br>';
        $mpdfHtml .= html_entity_decode($invoiceData[0]->company_address) . '<br>';
		if(!empty($invoiceData[0]->company_email)) { $mpdfHtml .= '<b>Email:</b> ' . $invoiceData[0]->company_email . '<br>'; }
        if(!empty($invoiceData[0]->company_phone_number)) { $mpdfHtml .= '<b>Phone:</b> ' . $invoiceData[0]->company_phone_number . '<br>'; }
		$panFromGTIN = substr(substr($invoiceData[0]->gstin_number, 2), 0, -3);
		$mpdfHtml .= '<b>PAN:</b> ' . $panFromGTIN  . '<br>';
		$mpdfHtml .= '<b>GSTIN:</b> ' . $invoiceData[0]->gstin_number;
        $mpdfHtml .= '</td>';

        $mpdfHtml .= '<td style="vertical-align:top;text-align:right;padding-bottom:20px;width:48%;padding-left:2%;">';

		if($invoiceData[0]->invoice_type === "exportinvoice") {

			if($invoiceData[0]->export_supply_meant == "withpayment") { $exportSupplyMeant = "Payment of Integrated Tax"; } 
			else { $exportSupplyMeant = "Without Payment of Integrated Tax"; }
			
			if (isset($invoiceData[0]->supply_place) && $invoiceData[0]->supply_place > 0) {
                if($supply_place_data['data']->state_tin == 97) {
					$mpdfHtml .= '<b>Place Of Supply:</b> ' . $supply_place_data['data']->state_name . '<br>';
				} else {
					$mpdfHtml .= '<b>Place Of Supply:</b> ' . $supply_place_data['data']->state_name . '(' . $supply_place_data['data']->state_tin . ')' . '<br>';
				}
            }
			
			$mpdfHtml .= '<b>Supply Meant:</b> ' . $exportSupplyMeant . '<br>';

			if ($invoiceData[0]->is_canceled == 1) { $mpdfHtml .= '<b>Canceled Invoice:</b> Canceled <br>'; }
            if ($invoiceData[0]->advance_adjustment == 1) { $mpdfHtml .= '<b>Advance Adjustment:</b> Yes <br>'; }

            if ($invoiceData[0]->advance_adjustment == 1) {

				$receiptVoucher = $this->get_row("select invoice_id, serial_number, invoice_date, supply_place, is_canceled from " . $this->tableNames['client_invoice'] . " where invoice_type = 'receiptvoucherinvoice' AND invoice_id = " . $invoiceData[0]->receipt_voucher_number . " AND is_deleted='0' AND financial_year = '" . $currentFinancialYear . "' AND added_by = " . $this->sanitize($_SESSION['user_detail']['user_id']) . " order by serial_number asc");
				if ($receiptVoucher) {
                    $mpdfHtml .= '<b>Receipt Voucher:</b> ' . $receiptVoucher->serial_number . '<br>';
                }
            }
			
			$mpdfHtml .= '<b>Export Bill Number:</b> ' . $invoiceData[0]->export_bill_number . '<br>';
			$mpdfHtml .= '<b>Export Bill Date:</b> ' . $invoiceData[0]->export_bill_date . '<br>';
			$mpdfHtml .= '<b>Export Bill Port Code:</b> ' . $invoiceData[0]->export_bill_port_code;
		
		} else if($invoiceData[0]->invoice_type === "sezunitinvoice" || $invoiceData[0]->invoice_type === "deemedexportinvoice") {

            if($invoiceData[0]->export_supply_meant == "withpayment") { $exportSupplyMeant = "Payment of Integrated Tax"; } 
			else { $exportSupplyMeant = "Without Payment of Integrated Tax"; }
			
			$mpdfHtml .= '<b>Supply Meant:</b> ' . $exportSupplyMeant . '<br>';
			
			if (isset($invoiceData[0]->supply_place) && $invoiceData[0]->supply_place > 0) {
				if($supply_place_data['data']->state_tin == 97) {
					$mpdfHtml .= '<b>Place Of Supply:</b> ' . $supply_place_data['data']->state_name . '<br>';
				} else {
					$mpdfHtml .= '<b>Place Of Supply:</b> ' . $supply_place_data['data']->state_name . '(' . $supply_place_data['data']->state_tin . ')' . '<br>';
				}
            }

			if ($invoiceData[0]->is_canceled == 1) { $mpdfHtml .= '<b>Canceled Invoice:</b> Canceled <br>'; }
            if ($invoiceData[0]->advance_adjustment == 1) { $mpdfHtml .= '<b>Advance Adjustment:</b> Yes <br>'; }

            if ($invoiceData[0]->advance_adjustment == 1) {

				$receiptVoucher = $this->get_row("select invoice_id, serial_number, invoice_date, supply_place, is_canceled from " . $this->tableNames['client_invoice'] . " where invoice_type = 'receiptvoucherinvoice' AND invoice_id = " . $invoiceData[0]->receipt_voucher_number . " AND is_deleted='0' AND financial_year = '" . $currentFinancialYear . "' AND added_by = " . $this->sanitize($_SESSION['user_detail']['user_id']) . " order by serial_number asc");
				if ($receiptVoucher) {
                    $mpdfHtml .= '<b>Receipt Voucher:</b> ' . $receiptVoucher->serial_number;
                }
            }

        } else {

			if($invoiceData[0]->supply_type == "reversecharge") { $supplyType = "Reverse Charge"; } 
			else if($invoiceData[0]->supply_type == "tds") { $supplyType = "TDS"; } 
			else if($invoiceData[0]->supply_type == "tcs") { $supplyType = "TCS"; } 
			else { $supplyType = "Normal"; }

			$mpdfHtml .= '<b>Supply Type:</b> ' . $supplyType . '<br>';
			if (isset($invoiceData[0]->supply_place) && $invoiceData[0]->supply_place > 0) {
                if($supply_place_data['data']->state_tin == 97) {
					$mpdfHtml .= '<b>Place Of Supply:</b> ' . $supply_place_data['data']->state_name . '<br>';
				} else {
					$mpdfHtml .= '<b>Place Of Supply:</b> ' . $supply_place_data['data']->state_name . '(' . $supply_place_data['data']->state_tin . ')' . '<br>';
				}
            }

			if ($invoiceData[0]->is_canceled == 1) { $mpdfHtml .= '<b>Canceled Invoice:</b> Canceled <br>'; }
            if ($invoiceData[0]->advance_adjustment == 1) { $mpdfHtml .= '<b>Advance Adjustment:</b> Yes <br>'; }

            if ($invoiceData[0]->advance_adjustment == 1) {

				$receiptVoucher = $this->get_row("select invoice_id, serial_number, invoice_date, supply_place, is_canceled from " . $this->tableNames['client_invoice'] . " where invoice_type = 'receiptvoucherinvoice' AND invoice_id = " . $invoiceData[0]->receipt_voucher_number . " AND is_deleted='0' AND financial_year = '" . $currentFinancialYear . "' AND added_by = " . $this->sanitize($_SESSION['user_detail']['user_id']) . " order by serial_number asc");
				if ($receiptVoucher) {
                    $mpdfHtml .= '<b>Receipt Voucher:</b> ' . $receiptVoucher->serial_number . '<br>';
                }
            }

            if ($invoiceData[0]->supply_type === "tcs") {
                $mpdfHtml .= '<br><b>Ecommerce GSTIN Number:</b> ' . $invoiceData[0]->ecommerce_gstin_number . '<br>';
                $mpdfHtml .= '<b>Ecommerce Vendor Code:</b> ' . $invoiceData[0]->ecommerce_vendor_code;
            }
		}

        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';
        $mpdfHtml .= '</table>';
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';

        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td colspan="2" style="vertical-align:top;">';
        $mpdfHtml .= '<table style="width:100%;line-height:inherit;">';
        $mpdfHtml .= '<tr>';
       
		$mpdfHtml .= '<td style="vertical-align:top;text-align:left;padding-bottom:40px;width:48%;padding-right:2%;">';
			$mpdfHtml .= '<b>Recipient Detail</b><br>';
			$mpdfHtml .= $invoiceData[0]->billing_name . '<br>';
			if ($invoiceData[0]->billing_company_name) { $mpdfHtml .= $invoiceData[0]->billing_company_name . '<br>'; }
			$mpdfHtml .= $invoiceData[0]->billing_address . '<br>';
			
			$billing_vendor_data = $this->getVendorDetailByVendorId($invoiceData[0]->billing_vendor_type);
			$mpdfHtml .= $billing_vendor_data['data']->vendor_name . '<br>';

			if (!empty($invoiceData[0]->billing_gstin_number)) { $mpdfHtml .= '<b>GSTIN/UIN:</b> ' . $invoiceData[0]->billing_gstin_number; }
		$mpdfHtml .= '</td>';

		$mpdfHtml .= '<td style="vertical-align:top;text-align:right;padding-bottom:40px;width:48%;padding-left:2%;">';
			$mpdfHtml .= '<b>Address Of Delivery / Shipping Detail</b><br>';
			$mpdfHtml .= $invoiceData[0]->shipping_name . '<br>';
			if ($invoiceData[0]->shipping_company_name) { $mpdfHtml .= $invoiceData[0]->shipping_company_name . '<br>'; }
			$mpdfHtml .= $invoiceData[0]->shipping_address . '<br>';
			
			$shipping_vendor_data = $this->getVendorDetailByVendorId($invoiceData[0]->shipping_vendor_type);
			$mpdfHtml .= $shipping_vendor_data['data']->vendor_name . '<br>';
			
			if (!empty($invoiceData[0]->shipping_gstin_number)) { $mpdfHtml .= '<b>GSTIN/UIN:</b> ' . $invoiceData[0]->shipping_gstin_number; }
		$mpdfHtml .= '</td>';

        $mpdfHtml .= '</tr>';
        $mpdfHtml .= '</table>';
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';
		
        $mpdfHtml .= '</table>';

        $mpdfHtml .= '<table border="1" style="border-collapse:collapse;width:100%;line-height:inherit;text-align:center;">';
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">S.No</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Goods/Services</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">HSN/SAC Code</td>';
		$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Item Description</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Qty</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Unit</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Rate (₹)</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Total (₹)</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Discount (%)</td>';

        if ($invoiceData[0]->advance_adjustment == 1) {
            $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Advance (₹)</td>';
        }

        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Taxable Value (₹)</td>';
        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">CGST</td>';
        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">SGST</td>';
        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">IGST</td>';
        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">CESS</td>';
        $mpdfHtml .= '</tr>';

        $mpdfHtml .= '<tr class="heading">';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
        $mpdfHtml .= '</tr>';

        $counter = 1;
        $total_taxable_subtotal = 0.00;
		$total_advance_subtotal = 0.00;
        $total_cgst_amount = 0.00;
        $total_sgst_amount = 0.00;
        $total_igst_amount = 0.00;
        $total_cess_amount = 0.00;
        foreach ($invoiceData as $invData) {

            $mpdfHtml .= '<tr>';
            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $counter;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->item_name;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->item_hsncode;
            $mpdfHtml .= '</td>';
			
			$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->item_description;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->item_quantity;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->item_unit;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->item_unit_price;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->subtotal;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->discount;
            $mpdfHtml .= '</td>';

            if ($invoiceData[0]->advance_adjustment == 1) {
                $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
                $mpdfHtml .= $invData->advance_amount;
                $mpdfHtml .= '</td>';
            }

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->taxable_subtotal;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->cgst_rate;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->cgst_amount;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->sgst_rate;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->sgst_amount;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->igst_rate;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->igst_amount;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->cess_rate;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->cess_amount;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '</tr>';

            $total_taxable_subtotal += $invData->taxable_subtotal;
			$total_advance_subtotal += $invData->advance_amount;
            $total_cgst_amount += $invData->cgst_amount;
            $total_sgst_amount += $invData->sgst_amount;
            $total_igst_amount += $invData->igst_amount;
            $total_cess_amount += $invData->cess_amount;

            $counter++;
        }

		$mpdfHtml .= '<tr style="background:#d9edf7;">';
		$mpdfHtml .= '<td colspan="9" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Total Invoice Value</td>';
		if($invoiceData[0]->advance_adjustment == 1) { $mpdfHtml .= '<td>'.$total_advance_subtotal.'</td>'; }
		$mpdfHtml .= '<td>'.$total_taxable_subtotal.'</td>';
		$mpdfHtml .= '<td>&nbsp;</td>';
		$mpdfHtml .= '<td>'.$total_cgst_amount.'</td>';
		$mpdfHtml .= '<td>&nbsp;</td>';
		$mpdfHtml .= '<td>'.$total_sgst_amount.'</td>';
		$mpdfHtml .= '<td>&nbsp;</td>';
		$mpdfHtml .= '<td>'.$total_igst_amount.'</td>';
		$mpdfHtml .= '<td>&nbsp;</td>';
		$mpdfHtml .= '<td>'.$total_cess_amount.'</td>';
		$mpdfHtml .= '</tr>';

        if ($invoiceData[0]->supply_type === "tds" || $invoiceData[0]->supply_type === "tcs") {

            if ($invoiceData[0]->company_state === $invoiceData[0]->supply_place) {

                $tdcsTaxValue = ((1 / 100) * $total_taxable_subtotal);

                $mpdfHtml .= '<tr style="background:#e9ffdb;font-size:14px;">';

                if ($invoiceData[0]->advance_adjustment == 1) {
                    $mpdfHtml .= '<td colspan="11" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Amount of Tax Subject to ' . strtoupper($invoiceData[0]->supply_type) . '</td>';
                } else {
                    $mpdfHtml .= '<td colspan="10" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Amount of Tax Subject to ' . strtoupper($invoiceData[0]->supply_type) . '</td>';
                }

                $mpdfHtml .= '<td>1%</td>';
                $mpdfHtml .= '<td>' . round(($tdcsTaxValue), 2) . '</td>';
                $mpdfHtml .= '<td>1%</td>';
                $mpdfHtml .= '<td>' . round(($tdcsTaxValue), 2) . '</td>';
                $mpdfHtml .= '<td>0%</td>';
                $mpdfHtml .= '<td>0.00</td>';
                $mpdfHtml .= '<td>0%</td>';
                $mpdfHtml .= '<td>0.00</td>';
                $mpdfHtml .= '</tr>';
            } else {

                $tdcsTaxValue = ((2 / 100) * $total_taxable_subtotal);

                $mpdfHtml .= '<tr style="background:#e9ffdb;font-size:14px;">';

                if ($invoiceData[0]->advance_adjustment == 1) {
                    $mpdfHtml .= '<td colspan="11" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Amount of Tax Subject to ' . strtoupper($invoiceData[0]->supply_type) . '</td>';
                } else {
                    $mpdfHtml .= '<td colspan="10" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Amount of Tax Subject to ' . strtoupper($invoiceData[0]->supply_type) . '</td>';
                }

                $mpdfHtml .= '<td>0%</td>';
                $mpdfHtml .= '<td>0.00</td>';
                $mpdfHtml .= '<td>0%</td>';
                $mpdfHtml .= '<td>0.00</td>';
                $mpdfHtml .= '<td>2%</td>';
                $mpdfHtml .= '<td>' . $tdcsTaxValue . '</td>';
                $mpdfHtml .= '<td>0%</td>';
                $mpdfHtml .= '<td>0.00</td>';
                $mpdfHtml .= '</tr>';
            }
        }

        if ($invoiceData[0]->supply_type === "reversecharge") {

            if ($invoiceData[0]->company_state === $invoiceData[0]->supply_place) {

                $mpdfHtml .= '<tr style="background:#e9ffdb;font-size:14px;">';

                if ($invoiceData[0]->advance_adjustment == 1) {
                    $mpdfHtml .= '<td colspan="11" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Amount of Tax Subject to Reverse Charge</td>';
                } else {
                    $mpdfHtml .= '<td colspan="10" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Amount of Tax Subject to Reverse Charge</td>';
                }

                $mpdfHtml .= '<td>-</td>';
                $mpdfHtml .= '<td>' . $total_cgst_amount . '</td>';
                $mpdfHtml .= '<td>-</td>';
                $mpdfHtml .= '<td>' . $total_sgst_amount . '</td>';
                $mpdfHtml .= '<td>-</td>';
                $mpdfHtml .= '<td>0.00</td>';
                $mpdfHtml .= '<td>-</td>';
                $mpdfHtml .= '<td>' . $total_cess_amount . '</td>';
                $mpdfHtml .= '</tr>';
            } else {

                $mpdfHtml .= '<tr style="background:#e9ffdb;font-size:14px;">';

                if ($invoiceData[0]->advance_adjustment == 1) {
                    $mpdfHtml .= '<td colspan="11" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Amount of Tax Subject to Reverse Charge</td>';
                } else {
                    $mpdfHtml .= '<td colspan="10" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Amount of Tax Subject to Reverse Charge</td>';
                }

                $mpdfHtml .= '<td>-</td>';
                $mpdfHtml .= '<td>0.00</td>';
                $mpdfHtml .= '<td>-</td>';
                $mpdfHtml .= '<td>0.00</td>';
                $mpdfHtml .= '<td>-</td>';
                $mpdfHtml .= '<td>' . $total_igst_amount . '</td>';
                $mpdfHtml .= '<td>-</td>';
                $mpdfHtml .= '<td>' . $total_cess_amount . '</td>';
                $mpdfHtml .= '</tr>';
            }
        }

		$mpdfHtml .= '<tr style="background:#ffefbf;">';
        if ($invoiceData[0]->advance_adjustment == 1) {
            $mpdfHtml .= '<td colspan="19" style="padding:5px;vertical-align:top;text-align:right;font-weight:bold;">';
        } else {
            $mpdfHtml .= '<td colspan="18" style="padding:5px;vertical-align:top;text-align:right;font-weight:bold;">';
        }
        $mpdfHtml .= 'Total Invoice Value (In Figure): ₹' . $invoiceData[0]->invoice_total_value;
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';

        $invoice_total_value_words = $this->convert_number_to_words($invoiceData[0]->invoice_total_value);

        $mpdfHtml .= '<tr style="background:#f2dede;">';
        if ($invoiceData[0]->advance_adjustment == 1) {
            $mpdfHtml .= '<td colspan="19" style="padding:5px;vertical-align:top;text-align:right;font-weight:bold;">';
        } else {
            $mpdfHtml .= '<td colspan="18" style="padding:5px;vertical-align:top;text-align:right;font-weight:bold;">';
        }
        $mpdfHtml .= 'Total Invoice Value (In Words): ' . ucwords($invoice_total_value_words);
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';

        $mpdfHtml .= '</table>';

		$mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;" cellpadding="0" cellspacing="0">';

			if(!empty($invoiceData[0]->description)) {
				$mpdfHtml .= '<tr class="description">';
					$mpdfHtml .= '<td colspan="2" style="padding-top:20px;vertical-align:top;">';
						$mpdfHtml .= '<p><b>Additional Notes:</b> '. $invoiceData[0]->description .'</p>';
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';
			}

			$mpdfHtml .= '<tr>';
				$mpdfHtml .= '<td colspan="2" style="padding-top:20px;vertical-align:top;">';
					$mpdfHtml .= '<table style="width:100%;line-height:inherit;">';
						
						$mpdfHtml .= '<tr>';

							$mpdfHtml .= '<td style="vertical-align:top;width:50%;">';
								
								if(
									!empty($dataCurrentUserArr['data']->kyc->bank_name) || 
									!empty($dataCurrentUserArr['data']->kyc->account_number) || 
									!empty($dataCurrentUserArr['data']->kyc->branch_name) || 
									!empty($dataCurrentUserArr['data']->kyc->ifsc_code)
								) {
								
									$mpdfHtml .= '<b>Bank Details :-</b><br>';

									$mpdfHtml .= '<table width="100%" border="1" style="border-collapse:collapse;width:100%;line-height:inherit;">';
										$mpdfHtml .= '<tr>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:40%;">';
												$mpdfHtml .= '<b>Bank Name</b>';
											$mpdfHtml .= '</td>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:60%;padding-left:5px;">';
												$mpdfHtml .= $dataCurrentUserArr['data']->kyc->bank_name;
											$mpdfHtml .= '</td>';
										
										$mpdfHtml .= '</tr>';

										$mpdfHtml .= '<tr>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:40%;">';
												$mpdfHtml .= '<b>Account Number</b>';
											$mpdfHtml .= '</td>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:60%;padding-left:5px;">';
												$mpdfHtml .= $dataCurrentUserArr['data']->kyc->account_number;
											$mpdfHtml .= '</td>';

										$mpdfHtml .= '</tr>';									

										$mpdfHtml .= '<tr>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:40%;">';
												$mpdfHtml .= '<b>Branch Name</b>';
											$mpdfHtml .= '</td>';
											
											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:60%;padding-left:5px;">';
												$mpdfHtml .= $dataCurrentUserArr['data']->kyc->branch_name;
											$mpdfHtml .= '</td>';
										
										$mpdfHtml .= '</tr>';

										$mpdfHtml .= '<tr>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:40%;">';
												$mpdfHtml .= '<b>IFSC Code</b>';
											$mpdfHtml .= '</td>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:60%;padding-left:5px;">';
												$mpdfHtml .= $dataCurrentUserArr['data']->kyc->ifsc_code;
											$mpdfHtml .= '</td>';

										$mpdfHtml .= '</tr>';

									$mpdfHtml .= '</table>';
								}

							$mpdfHtml .= '</td>';

							$mpdfHtml .= '<td style="padding-top:0px;vertical-align:top;padding-left:10%;width:40%;">';

								$mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:center;">';
									$mpdfHtml .= '<tr class="signature">';
										if (isset($dataThemeSettingArr['data']->show_signature) && $dataThemeSettingArr['data']->show_signature == '1' && isset($dataThemeSettingArr['data']->theme_signature) && $dataThemeSettingArr['data']->theme_signature != "") {
											$mpdfHtml .= '<td style="vertical-align:top;">';
										} else {
											$mpdfHtml .= '<td style="padding-top:50px;vertical-align:top;">';
										}

											if (isset($dataThemeSettingArr['data']->show_signature) && $dataThemeSettingArr['data']->show_signature == '1' && isset($dataThemeSettingArr['data']->theme_signature) && $dataThemeSettingArr['data']->theme_signature != "") {
												$mpdfHtml .= '<img src="upload/theme-signature/' . $dataThemeSettingArr['data']->theme_signature . '" style="max-width:300px;">';
											}

											$mpdfHtml .= '<p style="text-align:right;">';
												$mpdfHtml .= '<hr style="height:2px;">';
											$mpdfHtml .= '</p>';
											$mpdfHtml .= '<p style="text-align:center;">';
												$mpdfHtml .= 'For ' . $dataCurrentUserArr['data']->kyc->name;
											$mpdfHtml .= '</p>';
											$mpdfHtml .= '<p style="text-align:center;">';
												$mpdfHtml .= '<b>(Authorised Signatory)</b>';
											$mpdfHtml .= '</p>';
										$mpdfHtml .= '</td>';
									$mpdfHtml .= '</tr>';
								$mpdfHtml .= '</table>';

							$mpdfHtml .= '</td>';

						$mpdfHtml .= '</tr>';

					$mpdfHtml .= '</table>';
				$mpdfHtml .= '</td>';
			$mpdfHtml .= '</tr>';

        $mpdfHtml .= '</table>';
        $mpdfHtml .= '</div>';

        return $mpdfHtml;
    }

	/* generate Bill of Supply invoice html */
    public function generateBOSInvoiceHtml($invid) {

        $currentFinancialYear = $this->generateFinancialYear();

		/* get current user data */
		$dataCurrentUserArr = $this->getUserDetailsById($this->sanitize($_SESSION['user_detail']['user_id']));

        $invoiceData = $this->get_results("select 
											ci.*, 
											cii.invoice_item_id, 
											cii.item_id, 
											cii.item_name, 
											cii.item_description, 
											cii.item_hsncode, 
											cii.item_quantity, 
											cii.item_unit, 
											cii.item_unit_price, 
											cii.subtotal, 
											cii.discount, 
											cii.taxable_subtotal, 
											cii.total 
											from 
										" . $this->tableNames['client_invoice'] . " as ci INNER JOIN " . $this->tableNames['client_invoice_item'] . " as cii ON ci.invoice_id = cii.invoice_id where ci.invoice_id = " . $invid . " AND ci.invoice_type = 'billofsupplyinvoice' AND ci.added_by = '" . $this->sanitize($_SESSION['user_detail']['user_id']) . "' AND cii.added_by = '" . $this->sanitize($_SESSION['user_detail']['user_id']) . "' AND ci.is_deleted='0' AND cii.is_deleted='0'");

        if (empty($invoiceData)) {
            return false;
        }

        $dataThemeSettingArr = $this->getUserThemeSetting($this->sanitize($_SESSION['user_detail']['user_id']));
		$dataInvoiceSettingArr = $this->getUserInvoiceSetting($this->sanitize($_SESSION['user_detail']['user_id']));

        $mpdfHtml = '';
        $mpdfHtml .= '<div style="margin:auto;font-size:16px;line-height:24px;color:#555;">';
        $mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;" cellpadding="0" cellspacing="0">';
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td colspan="2" style="vertical-align:top;">';
		$mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;">';
		$mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td style="font-size:45px;line-height:45px;color:#333;padding:5px;vertical-align:top;padding-bottom:20px;">';

        if (isset($dataThemeSettingArr['data']->show_logo) && $dataThemeSettingArr['data']->show_logo == '1' && isset($dataThemeSettingArr['data']->theme_logo) && $dataThemeSettingArr['data']->theme_logo != "") {
            $mpdfHtml .= '<img src="upload/theme-logo/' . $dataThemeSettingArr['data']->theme_logo . '" style="max-width:300px;">';
        }

        $mpdfHtml .= '</td>';
		
		if(isset($dataInvoiceSettingArr['data']->invoice_label) && !empty($dataInvoiceSettingArr['data']->invoice_label)) { $invoice_label = $dataInvoiceSettingArr['data']->invoice_label; } else { $invoice_label = "Invoice #"; }
		if(isset($dataInvoiceSettingArr['data']->reference_label) && !empty($dataInvoiceSettingArr['data']->reference_label)) { $reference_label = $dataInvoiceSettingArr['data']->reference_label; } else { $reference_label = "Reference #"; }
		if(isset($dataInvoiceSettingArr['data']->type_label) && !empty($dataInvoiceSettingArr['data']->type_label)) { $type_label = $dataInvoiceSettingArr['data']->type_label; } else { $type_label = "Type"; }
		if(isset($dataInvoiceSettingArr['data']->nature_label) && !empty($dataInvoiceSettingArr['data']->nature_label)) { $nature_label = $dataInvoiceSettingArr['data']->nature_label; } else { $nature_label = "Nature"; }
		if(isset($dataInvoiceSettingArr['data']->date_label) && !empty($dataInvoiceSettingArr['data']->date_label)) { $date_label = $dataInvoiceSettingArr['data']->date_label; } else { $date_label = "Invoice Date"; }

		$mpdfHtml .= '<td style="vertical-align:top;text-align:right;padding-bottom:20px;">';
		$mpdfHtml .= '<b>'.$invoice_label.'</b>: ' . $invoiceData[0]->serial_number . '<br>';
        $mpdfHtml .= '<b>'.$reference_label.'</b>: ' . $invoiceData[0]->reference_number . '<br>';
		$mpdfHtml .= '<b>'.$type_label.'</b>: Bill of Supply Invoice<br>';
		$mpdfHtml .= '<b>'.$nature_label.'</b>: Sales Invoice<br>';
        $mpdfHtml .= '<b>'.$date_label.'</b>: ' . $invoiceData[0]->invoice_date;
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';
        $mpdfHtml .= '</table>';
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';

        $mpdfHtml .= '<tr>';
			$mpdfHtml .= '<td colspan="2" style="vertical-align:top;">';
				$mpdfHtml .= '<table style="width:100%;line-height:inherit;">';
				$mpdfHtml .= '<tr>';
				$mpdfHtml .= '<td style="text-align:left;vertical-align:top;padding-bottom:20px;width:48%;padding-right:2%;">';
				$mpdfHtml .= html_entity_decode($invoiceData[0]->company_name) . '<br>';
				$mpdfHtml .= html_entity_decode($invoiceData[0]->company_address) . '<br>';
				if(!empty($invoiceData[0]->company_email)) { $mpdfHtml .= '<b>Email:</b> ' . $invoiceData[0]->company_email . '<br>'; }
				if(!empty($invoiceData[0]->company_phone_number)) { $mpdfHtml .= '<b>Phone:</b> ' . $invoiceData[0]->company_phone_number . '<br>'; }
				$panFromGTIN = substr(substr($invoiceData[0]->gstin_number, 2), 0, -3);
				$mpdfHtml .= '<b>PAN:</b> ' . $panFromGTIN  . '<br>';
				$mpdfHtml .= '<b>GSTIN:</b> ' . $invoiceData[0]->gstin_number;
				$mpdfHtml .= '</td>';

				$mpdfHtml .= '<td style="vertical-align:top;text-align:right;padding-bottom:20px;width:48%;padding-left:2%;">';
				if ($invoiceData[0]->is_canceled == 1) { $mpdfHtml .= '<b>Canceled Invoice:</b> Canceled <br>'; }
				$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';
				$mpdfHtml .= '</table>';
			$mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';

        $mpdfHtml .= '<tr>';
			$mpdfHtml .= '<td colspan="2" style="vertical-align:top;">';
				$mpdfHtml .= '<table style="width:100%;line-height:inherit;">';
					$mpdfHtml .= '<tr>';
						$mpdfHtml .= '<td style="vertical-align:top;text-align:left;padding-bottom:40px;width:48%;padding-right:2%;">';
							$mpdfHtml .= '<b>Recipient Detail</b><br>';
							$mpdfHtml .= $invoiceData[0]->billing_name . '<br>';
							if ($invoiceData[0]->billing_company_name) { $mpdfHtml .= $invoiceData[0]->billing_company_name . '<br>'; }
							$mpdfHtml .= $invoiceData[0]->billing_address . '<br>';
							
							$billing_vendor_data = $this->getVendorDetailByVendorId($invoiceData[0]->billing_vendor_type);
							$mpdfHtml .= $billing_vendor_data['data']->vendor_name . '<br>';

							if (!empty($invoiceData[0]->billing_gstin_number)) { $mpdfHtml .= '<b>GSTIN/UIN:</b> ' . $invoiceData[0]->billing_gstin_number; }
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="vertical-align:top;text-align:right;padding-bottom:40px;width:48%;padding-left:2%;">';
							$mpdfHtml .= '<b>Address Of Delivery / Shipping Detail</b><br>';
							$mpdfHtml .= $invoiceData[0]->shipping_name . '<br>';
							if ($invoiceData[0]->shipping_company_name) { $mpdfHtml .= $invoiceData[0]->shipping_company_name . '<br>'; }
							$mpdfHtml .= $invoiceData[0]->shipping_address . '<br>';
							
							$shipping_vendor_data = $this->getVendorDetailByVendorId($invoiceData[0]->shipping_vendor_type);
							$mpdfHtml .= $shipping_vendor_data['data']->vendor_name . '<br>';
							
							if (!empty($invoiceData[0]->shipping_gstin_number)) { $mpdfHtml .= '<b>GSTIN/UIN:</b> ' . $invoiceData[0]->shipping_gstin_number; }
						$mpdfHtml .= '</td>';
					$mpdfHtml .= '</tr>';
				$mpdfHtml .= '</table>';
			$mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';

		$mpdfHtml .= '</table>';

		$mpdfHtml .= '<table border="1" style="border-collapse:collapse;width:100%;line-height:inherit;text-align:center;">';
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">S.No</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Goods/Services</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">HSN/SAC Code</td>';
		$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Item Description</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Qty</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Unit</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Rate (₹)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Total (₹)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Discount (%)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Net Total Value (₹)</td>';
        $mpdfHtml .= '</tr>';

        $counter = 1;
		$total_taxable_subtotal = 0.00;
        foreach ($invoiceData as $invData) {

            $mpdfHtml .= '<tr>';
            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $counter;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->item_name;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->item_hsncode;
            $mpdfHtml .= '</td>';
			
			$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->item_description;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->item_quantity;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->item_unit;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->item_unit_price;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->subtotal;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->discount;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->taxable_subtotal;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '</tr>';

			$total_taxable_subtotal += $invData->taxable_subtotal;
            $counter++;
        }
		
		$mpdfHtml .= '<tr style="background:#d9edf7;">';
		$mpdfHtml .= '<td colspan="9" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Total Invoice Value</td>';
		$mpdfHtml .= '<td>'.$total_taxable_subtotal.'</td>';
		$mpdfHtml .= '</tr>';

        $mpdfHtml .= '<tr style="background:#ffefbf;">';
        $mpdfHtml .= '<td colspan="10" style="padding:5px;vertical-align:top;text-align:right;border-top:2px solid #eee;font-weight:bold;">';
        $mpdfHtml .= 'Total Invoice Value (In Figure): ₹' . $invoiceData[0]->invoice_total_value;
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';

        $invoice_total_value_words = $this->convert_number_to_words($invoiceData[0]->invoice_total_value);

        $mpdfHtml .= '<tr style="background:#f2dede;">';
        $mpdfHtml .= '<td colspan="10" style="padding:5px;vertical-align:top;text-align:right;border-top:2px solid #eee;font-weight:bold;">';
        $mpdfHtml .= 'Total Invoice Value (In Words): ' . ucwords($invoice_total_value_words);
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';

        $mpdfHtml .= '</table>';

		$mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;" cellpadding="0" cellspacing="0">';

			if(!empty($invoiceData[0]->description)) {
				$mpdfHtml .= '<tr class="description">';
					$mpdfHtml .= '<td colspan="2" style="padding-top:20px;vertical-align:top;">';
						$mpdfHtml .= '<p><b>Additional Notes:</b> '. $invoiceData[0]->description .'</p>';
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';
			}

			$mpdfHtml .= '<tr>';
				$mpdfHtml .= '<td colspan="2" style="padding-top:20px;vertical-align:top;">';
					$mpdfHtml .= '<table style="width:100%;line-height:inherit;">';
						
						$mpdfHtml .= '<tr>';

							$mpdfHtml .= '<td style="vertical-align:top;width:50%;">';
								
								if(
									!empty($dataCurrentUserArr['data']->kyc->bank_name) || 
									!empty($dataCurrentUserArr['data']->kyc->account_number) || 
									!empty($dataCurrentUserArr['data']->kyc->branch_name) || 
									!empty($dataCurrentUserArr['data']->kyc->ifsc_code)
								) {
								
									$mpdfHtml .= '<b>Bank Details :-</b><br>';

									$mpdfHtml .= '<table width="100%" border="1" style="border-collapse:collapse;width:100%;line-height:inherit;">';
										$mpdfHtml .= '<tr>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:40%;">';
												$mpdfHtml .= '<b>Bank Name</b>';
											$mpdfHtml .= '</td>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:60%;padding-left:5px;">';
												$mpdfHtml .= $dataCurrentUserArr['data']->kyc->bank_name;
											$mpdfHtml .= '</td>';
										
										$mpdfHtml .= '</tr>';

										$mpdfHtml .= '<tr>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:40%;">';
												$mpdfHtml .= '<b>Account Number</b>';
											$mpdfHtml .= '</td>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:60%;padding-left:5px;">';
												$mpdfHtml .= $dataCurrentUserArr['data']->kyc->account_number;
											$mpdfHtml .= '</td>';

										$mpdfHtml .= '</tr>';									

										$mpdfHtml .= '<tr>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:40%;">';
												$mpdfHtml .= '<b>Branch Name</b>';
											$mpdfHtml .= '</td>';
											
											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:60%;padding-left:5px;">';
												$mpdfHtml .= $dataCurrentUserArr['data']->kyc->branch_name;
											$mpdfHtml .= '</td>';
										
										$mpdfHtml .= '</tr>';

										$mpdfHtml .= '<tr>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:40%;">';
												$mpdfHtml .= '<b>IFSC Code</b>';
											$mpdfHtml .= '</td>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:60%;padding-left:5px;">';
												$mpdfHtml .= $dataCurrentUserArr['data']->kyc->ifsc_code;
											$mpdfHtml .= '</td>';

										$mpdfHtml .= '</tr>';

									$mpdfHtml .= '</table>';
								}

							$mpdfHtml .= '</td>';

							$mpdfHtml .= '<td style="padding-top:0px;vertical-align:top;padding-left:10%;width:40%;">';

								$mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:center;">';
									$mpdfHtml .= '<tr class="signature">';
										if (isset($dataThemeSettingArr['data']->show_signature) && $dataThemeSettingArr['data']->show_signature == '1' && isset($dataThemeSettingArr['data']->theme_signature) && $dataThemeSettingArr['data']->theme_signature != "") {
											$mpdfHtml .= '<td style="vertical-align:top;">';
										} else {
											$mpdfHtml .= '<td style="padding-top:50px;vertical-align:top;">';
										}

											if (isset($dataThemeSettingArr['data']->show_signature) && $dataThemeSettingArr['data']->show_signature == '1' && isset($dataThemeSettingArr['data']->theme_signature) && $dataThemeSettingArr['data']->theme_signature != "") {
												$mpdfHtml .= '<img src="upload/theme-signature/' . $dataThemeSettingArr['data']->theme_signature . '" style="max-width:300px;">';
											}

											$mpdfHtml .= '<p style="text-align:right;">';
												$mpdfHtml .= '<hr style="height:2px;">';
											$mpdfHtml .= '</p>';
											$mpdfHtml .= '<p style="text-align:center;">';
												$mpdfHtml .= 'For ' . $dataCurrentUserArr['data']->kyc->name;
											$mpdfHtml .= '</p>';
											$mpdfHtml .= '<p style="text-align:center;">';
												$mpdfHtml .= '<b>(Authorised Signatory)</b>';
											$mpdfHtml .= '</p>';
										$mpdfHtml .= '</td>';
									$mpdfHtml .= '</tr>';
								$mpdfHtml .= '</table>';

							$mpdfHtml .= '</td>';

						$mpdfHtml .= '</tr>';

					$mpdfHtml .= '</table>';
				$mpdfHtml .= '</td>';
			$mpdfHtml .= '</tr>';

        $mpdfHtml .= '</table>';
        $mpdfHtml .= '</div>';

        return $mpdfHtml;
    }

	/* generate receipt voucher invoice html */
    public function generateRVInvoiceHtml($invid) {

        $currentFinancialYear = $this->generateFinancialYear();
		
		/* get current user data */
		$dataCurrentUserArr = $this->getUserDetailsById($this->sanitize($_SESSION['user_detail']['user_id']));

        $invoiceData = $this->get_results("select 
											ci.*, 
											cii.invoice_item_id, 
											cii.item_id, 
											cii.item_name, 
											cii.item_hsncode, 
											cii.item_description, 
											cii.item_quantity, 
											cii.item_unit, 
											cii.item_unit_price, 
											cii.subtotal, 
											cii.discount, 
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
										" . $this->tableNames['client_invoice'] . " as ci INNER JOIN " . $this->tableNames['client_invoice_item'] . " as cii ON ci.invoice_id = cii.invoice_id where ci.invoice_id = " . $invid . " AND ci.invoice_type = 'receiptvoucherinvoice' AND ci.added_by = '" . $this->sanitize($_SESSION['user_detail']['user_id']) . "' AND cii.added_by = '" . $this->sanitize($_SESSION['user_detail']['user_id']) . "' AND ci.is_deleted='0' AND cii.is_deleted='0'");

        if (empty($invoiceData)) {
            return false;
        }

        $dataThemeSettingArr = $this->getUserThemeSetting($this->sanitize($_SESSION['user_detail']['user_id']));
		$dataInvoiceSettingArr = $this->getUserInvoiceSetting($this->sanitize($_SESSION['user_detail']['user_id']));

        $mpdfHtml = '';
		$mpdfHtml .= '<div style="margin:auto;font-size:16px;line-height:24px;color:#555;">';
        $mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;" cellpadding="0" cellspacing="0">';
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td colspan="2" style="vertical-align:top;">';
        $mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;">';
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td style="font-size:45px;line-height:45px;color:#333;padding:5px;vertical-align:top;padding-bottom:20px;">';

        if (isset($dataThemeSettingArr['data']->show_logo) && $dataThemeSettingArr['data']->show_logo == '1' && isset($dataThemeSettingArr['data']->theme_logo) && $dataThemeSettingArr['data']->theme_logo != "") {
            $mpdfHtml .= '<img src="upload/theme-logo/' . $dataThemeSettingArr['data']->theme_logo . '" style="max-width:300px;">';
        }

        $mpdfHtml .= '</td>';
		
		if(isset($dataInvoiceSettingArr['data']->invoice_label) && !empty($dataInvoiceSettingArr['data']->invoice_label)) { $invoice_label = $dataInvoiceSettingArr['data']->invoice_label; } else { $invoice_label = "Invoice #"; }
		if(isset($dataInvoiceSettingArr['data']->reference_label) && !empty($dataInvoiceSettingArr['data']->reference_label)) { $reference_label = $dataInvoiceSettingArr['data']->reference_label; } else { $reference_label = "Reference #"; }
		if(isset($dataInvoiceSettingArr['data']->type_label) && !empty($dataInvoiceSettingArr['data']->type_label)) { $type_label = $dataInvoiceSettingArr['data']->type_label; } else { $type_label = "Type"; }
		if(isset($dataInvoiceSettingArr['data']->nature_label) && !empty($dataInvoiceSettingArr['data']->nature_label)) { $nature_label = $dataInvoiceSettingArr['data']->nature_label; } else { $nature_label = "Nature"; }
		if(isset($dataInvoiceSettingArr['data']->date_label) && !empty($dataInvoiceSettingArr['data']->date_label)) { $date_label = $dataInvoiceSettingArr['data']->date_label; } else { $date_label = "Invoice Date"; }

		$mpdfHtml .= '<td style="vertical-align:top;text-align:right;padding-bottom:20px;">';
		$mpdfHtml .= '<b>'.$invoice_label.'</b>: ' . $invoiceData[0]->serial_number . '<br>';
        $mpdfHtml .= '<b>'.$reference_label.'</b>: ' . $invoiceData[0]->reference_number . '<br>';
		$mpdfHtml .= '<b>'.$type_label.'</b>: Receipt Voucher<br>';
		$mpdfHtml .= '<b>'.$nature_label.'</b>: Sales Invoice<br>';
        $mpdfHtml .= '<b>'.$date_label.'</b>: ' . $invoiceData[0]->invoice_date;
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';
        $mpdfHtml .= '</table>';
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';

        $supply_place_data = $this->getStateDetailByStateId($invoiceData[0]->supply_place);

        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td colspan="2" style="vertical-align:top;">';
        $mpdfHtml .= '<table style="width:100%;line-height:inherit;">';
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td style="text-align:left;vertical-align:top;padding-bottom:20px;width:48%;padding-right:2%;">';
		$mpdfHtml .= html_entity_decode($invoiceData[0]->company_name) . '<br>';
		$mpdfHtml .= html_entity_decode($invoiceData[0]->company_address) . '<br>';
		if(!empty($invoiceData[0]->company_email)) { $mpdfHtml .= '<b>Email:</b> ' . $invoiceData[0]->company_email . '<br>'; }
        if(!empty($invoiceData[0]->company_phone_number)) { $mpdfHtml .= '<b>Phone:</b> ' . $invoiceData[0]->company_phone_number . '<br>'; }
		$panFromGTIN = substr(substr($invoiceData[0]->gstin_number, 2), 0, -3);
		$mpdfHtml .= '<b>PAN:</b> ' . $panFromGTIN  . '<br>';
		$mpdfHtml .= '<b>GSTIN:</b> ' . $invoiceData[0]->gstin_number;
        $mpdfHtml .= '</td>';

        $mpdfHtml .= '<td style="vertical-align:top;text-align:right;padding-bottom:20px;width:48%;padding-left:2%;">';

		if (isset($invoiceData[0]->supply_place) && $invoiceData[0]->supply_place > 0) {
			if($supply_place_data['data']->state_tin == 97) {
				$mpdfHtml .= '<b>Place Of Supply:</b> ' . $supply_place_data['data']->state_name . '<br>';
			} else {
				$mpdfHtml .= '<b>Place Of Supply:</b> ' . $supply_place_data['data']->state_name . '(' . $supply_place_data['data']->state_tin . ')' . '<br>';
			}
		}

		if ($invoiceData[0]->is_tax_payable == '1') {
			$mpdfHtml .= '<b>Reverse Charge:</b> Yes<br>';
		} else {
			$mpdfHtml .= '<b>Reverse Charge:</b> No<br>';
		}

		if ($invoiceData[0]->is_canceled == 1) { $mpdfHtml .= '<b>Canceled Invoice:</b> Canceled'; }

        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';
        $mpdfHtml .= '</table>';
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';

        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td colspan="2" style="vertical-align:top;">';
        $mpdfHtml .= '<table style="width:100%;line-height:inherit;">';
        $mpdfHtml .= '<tr>';
       
		$mpdfHtml .= '<td style="vertical-align:top;text-align:left;padding-bottom:40px;width:48%;padding-right:2%;">';
			$mpdfHtml .= '<b>Recipient Detail</b><br>';
			$mpdfHtml .= $invoiceData[0]->billing_name . '<br>';
			if ($invoiceData[0]->billing_company_name) { $mpdfHtml .= $invoiceData[0]->billing_company_name . '<br>'; }
			$mpdfHtml .= $invoiceData[0]->billing_address . '<br>';
			
			$billing_vendor_data = $this->getVendorDetailByVendorId($invoiceData[0]->billing_vendor_type);
			$mpdfHtml .= $billing_vendor_data['data']->vendor_name . '<br>';

			if (!empty($invoiceData[0]->billing_gstin_number)) { $mpdfHtml .= '<b>GSTIN/UIN:</b> ' . $invoiceData[0]->billing_gstin_number; }
		$mpdfHtml .= '</td>';

		$mpdfHtml .= '<td style="vertical-align:top;text-align:right;padding-bottom:40px;width:48%;padding-left:2%;">';
			$mpdfHtml .= '<b>Address Of Delivery / Shipping Detail</b><br>';
			$mpdfHtml .= $invoiceData[0]->shipping_name . '<br>';
			if ($invoiceData[0]->shipping_company_name) { $mpdfHtml .= $invoiceData[0]->shipping_company_name . '<br>'; }
			$mpdfHtml .= $invoiceData[0]->shipping_address . '<br>';
			
			$shipping_vendor_data = $this->getVendorDetailByVendorId($invoiceData[0]->shipping_vendor_type);
			$mpdfHtml .= $shipping_vendor_data['data']->vendor_name . '<br>';
			
			if (!empty($invoiceData[0]->shipping_gstin_number)) { $mpdfHtml .= '<b>GSTIN/UIN:</b> ' . $invoiceData[0]->shipping_gstin_number; }
		$mpdfHtml .= '</td>';

        $mpdfHtml .= '</tr>';
        $mpdfHtml .= '</table>';
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';
		
        $mpdfHtml .= '</table>';

		$mpdfHtml .= '<table border="1" style="border-collapse:collapse;width:100%;line-height:inherit;text-align:center;">';
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">S.No</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Goods/Services</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">HSN/SAC Code</td>';
		$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Item Description</td>';
		$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Advance Value (₹)</td>';
        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">CGST</td>';
        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">SGST</td>';
        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">IGST</td>';
        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">CESS</td>';
        $mpdfHtml .= '</tr>';

        $mpdfHtml .= '<tr class="heading">';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
        $mpdfHtml .= '</tr>';

        $counter = 1;
        $total_taxable_subtotal = 0.00;
        $total_cgst_amount = 0.00;
        $total_sgst_amount = 0.00;
        $total_igst_amount = 0.00;
        $total_cess_amount = 0.00;
        foreach ($invoiceData as $invData) {

            $mpdfHtml .= '<tr>';
            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $counter;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->item_name;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->item_hsncode;
            $mpdfHtml .= '</td>';
			
			$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->item_description;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->taxable_subtotal;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->cgst_rate;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->cgst_amount;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->sgst_rate;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->sgst_amount;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->igst_rate;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->igst_amount;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->cess_rate;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
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
		
		$mpdfHtml .= '<tr style="background:#d9edf7;">';
		$mpdfHtml .= '<td colspan="4" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Total Invoice Value</td>';
		$mpdfHtml .= '<td>'.$total_taxable_subtotal.'</td>';
		$mpdfHtml .= '<td>&nbsp;</td>';
		$mpdfHtml .= '<td>'.$total_cgst_amount.'</td>';
		$mpdfHtml .= '<td>&nbsp;</td>';
		$mpdfHtml .= '<td>'.$total_sgst_amount.'</td>';
		$mpdfHtml .= '<td>&nbsp;</td>';
		$mpdfHtml .= '<td>'.$total_igst_amount.'</td>';
		$mpdfHtml .= '<td>&nbsp;</td>';
		$mpdfHtml .= '<td>'.$total_cess_amount.'</td>';
		$mpdfHtml .= '</tr>';
		
		if ($invoiceData[0]->is_tax_payable == "1") {

            if ($invoiceData[0]->company_state === $invoiceData[0]->supply_place) {

                $mpdfHtml .= '<tr style="background:#e9ffdb;font-size:14px;">';
                $mpdfHtml .= '<td colspan="5" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Amount of Tax Subject to Reverse Charge</td>';
                $mpdfHtml .= '<td>-</td>';
                $mpdfHtml .= '<td>' . $total_cgst_amount . '</td>';
                $mpdfHtml .= '<td>-</td>';
                $mpdfHtml .= '<td>' . $total_sgst_amount . '</td>';
                $mpdfHtml .= '<td>-</td>';
                $mpdfHtml .= '<td>0.00</td>';
                $mpdfHtml .= '<td>-</td>';
                $mpdfHtml .= '<td>' . $total_cess_amount . '</td>';
                $mpdfHtml .= '</tr>';
            } else {

                $mpdfHtml .= '<tr style="background:#e9ffdb;font-size:14px;">';
                $mpdfHtml .= '<td colspan="5" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Amount of Tax Subject to Reverse Charge</td>';
                $mpdfHtml .= '<td>-</td>';
                $mpdfHtml .= '<td>0.00</td>';
                $mpdfHtml .= '<td>-</td>';
                $mpdfHtml .= '<td>0.00</td>';
                $mpdfHtml .= '<td>-</td>';
                $mpdfHtml .= '<td>' . $total_igst_amount . '</td>';
                $mpdfHtml .= '<td>-</td>';
                $mpdfHtml .= '<td>' . $total_cess_amount . '</td>';
                $mpdfHtml .= '</tr>';
            }
        }

        $mpdfHtml .= '<tr style="background:#ffefbf;">';
		$mpdfHtml .= '<td colspan="13" style="padding:5px;vertical-align:top;text-align:right;font-weight:bold;">';
        $mpdfHtml .= 'Total Invoice Value (In Figure): ₹' . $invoiceData[0]->invoice_total_value;
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';

        $invoice_total_value_words = $this->convert_number_to_words($invoiceData[0]->invoice_total_value);

        $mpdfHtml .= '<tr style="background:#f2dede;">';
        $mpdfHtml .= '<td colspan="13" style="padding:5px;vertical-align:top;text-align:right;font-weight:bold;">';
        $mpdfHtml .= 'Total Invoice Value (In Words): ' . ucwords($invoice_total_value_words);
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';

        $mpdfHtml .= '</table>';

        $mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;" cellpadding="0" cellspacing="0">';

			if(!empty($invoiceData[0]->description)) {
				$mpdfHtml .= '<tr class="description">';
					$mpdfHtml .= '<td colspan="2" style="padding-top:20px;vertical-align:top;">';
						$mpdfHtml .= '<p><b>Additional Notes:</b> '. $invoiceData[0]->description .'</p>';
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';
			}

			$mpdfHtml .= '<tr>';
				$mpdfHtml .= '<td colspan="2" style="padding-top:20px;vertical-align:top;">';
					$mpdfHtml .= '<table style="width:100%;line-height:inherit;">';
						
						$mpdfHtml .= '<tr>';

							$mpdfHtml .= '<td style="vertical-align:top;width:50%;">';
								
								if(
									!empty($dataCurrentUserArr['data']->kyc->bank_name) || 
									!empty($dataCurrentUserArr['data']->kyc->account_number) || 
									!empty($dataCurrentUserArr['data']->kyc->branch_name) || 
									!empty($dataCurrentUserArr['data']->kyc->ifsc_code)
								) {
								
									$mpdfHtml .= '<b>Bank Details :-</b><br>';

									$mpdfHtml .= '<table width="100%" border="1" style="border-collapse:collapse;width:100%;line-height:inherit;">';
										$mpdfHtml .= '<tr>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:40%;">';
												$mpdfHtml .= '<b>Bank Name</b>';
											$mpdfHtml .= '</td>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:60%;padding-left:5px;">';
												$mpdfHtml .= $dataCurrentUserArr['data']->kyc->bank_name;
											$mpdfHtml .= '</td>';
										
										$mpdfHtml .= '</tr>';

										$mpdfHtml .= '<tr>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:40%;">';
												$mpdfHtml .= '<b>Account Number</b>';
											$mpdfHtml .= '</td>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:60%;padding-left:5px;">';
												$mpdfHtml .= $dataCurrentUserArr['data']->kyc->account_number;
											$mpdfHtml .= '</td>';

										$mpdfHtml .= '</tr>';									

										$mpdfHtml .= '<tr>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:40%;">';
												$mpdfHtml .= '<b>Branch Name</b>';
											$mpdfHtml .= '</td>';
											
											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:60%;padding-left:5px;">';
												$mpdfHtml .= $dataCurrentUserArr['data']->kyc->branch_name;
											$mpdfHtml .= '</td>';
										
										$mpdfHtml .= '</tr>';

										$mpdfHtml .= '<tr>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:40%;">';
												$mpdfHtml .= '<b>IFSC Code</b>';
											$mpdfHtml .= '</td>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:60%;padding-left:5px;">';
												$mpdfHtml .= $dataCurrentUserArr['data']->kyc->ifsc_code;
											$mpdfHtml .= '</td>';

										$mpdfHtml .= '</tr>';

									$mpdfHtml .= '</table>';
								}

							$mpdfHtml .= '</td>';

							$mpdfHtml .= '<td style="padding-top:0px;vertical-align:top;padding-left:10%;width:40%;">';

								$mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:center;">';
									$mpdfHtml .= '<tr class="signature">';
										if (isset($dataThemeSettingArr['data']->show_signature) && $dataThemeSettingArr['data']->show_signature == '1' && isset($dataThemeSettingArr['data']->theme_signature) && $dataThemeSettingArr['data']->theme_signature != "") {
											$mpdfHtml .= '<td style="vertical-align:top;">';
										} else {
											$mpdfHtml .= '<td style="padding-top:50px;vertical-align:top;">';
										}

											if (isset($dataThemeSettingArr['data']->show_signature) && $dataThemeSettingArr['data']->show_signature == '1' && isset($dataThemeSettingArr['data']->theme_signature) && $dataThemeSettingArr['data']->theme_signature != "") {
												$mpdfHtml .= '<img src="upload/theme-signature/' . $dataThemeSettingArr['data']->theme_signature . '" style="max-width:300px;">';
											}

											$mpdfHtml .= '<p style="text-align:right;">';
												$mpdfHtml .= '<hr style="height:2px;">';
											$mpdfHtml .= '</p>';
											$mpdfHtml .= '<p style="text-align:center;">';
												$mpdfHtml .= 'For ' . $dataCurrentUserArr['data']->kyc->name;
											$mpdfHtml .= '</p>';
											$mpdfHtml .= '<p style="text-align:center;">';
												$mpdfHtml .= '<b>(Authorised Signatory)</b>';
											$mpdfHtml .= '</p>';
										$mpdfHtml .= '</td>';
									$mpdfHtml .= '</tr>';
								$mpdfHtml .= '</table>';

							$mpdfHtml .= '</td>';

						$mpdfHtml .= '</tr>';

					$mpdfHtml .= '</table>';
				$mpdfHtml .= '</td>';
			$mpdfHtml .= '</tr>';

        $mpdfHtml .= '</table>';
        $mpdfHtml .= '</div>';

        return $mpdfHtml;
    }

	/* generate refund voucher invoice html */
    public function generateRFInvoiceHtml($invid) {

        $currentFinancialYear = $this->generateFinancialYear();
		
		/* get current user data */
		$dataCurrentUserArr = $this->getUserDetailsById($this->sanitize($_SESSION['user_detail']['user_id']));

        $invoiceData = $this->get_results("select 
											ci.*, 
											cii.invoice_item_id, 
											cii.item_id, 
											cii.item_name, 
											cii.item_hsncode, 
											cii.item_description, 
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
										" . $this->tableNames['client_invoice'] . " as ci INNER JOIN " . $this->tableNames['client_invoice_item'] . " as cii ON ci.invoice_id = cii.invoice_id where ci.invoice_id = " . $invid . " AND ci.invoice_type = 'refundvoucherinvoice' AND ci.added_by = '" . $this->sanitize($_SESSION['user_detail']['user_id']) . "' AND cii.added_by = '" . $this->sanitize($_SESSION['user_detail']['user_id']) . "' AND ci.is_deleted='0' AND cii.is_deleted='0'");

        if (empty($invoiceData)) {
            return false;
        }

        $dataThemeSettingArr = $this->getUserThemeSetting($this->sanitize($_SESSION['user_detail']['user_id']));
		$dataInvoiceSettingArr = $this->getUserInvoiceSetting($this->sanitize($_SESSION['user_detail']['user_id']));

        $mpdfHtml = '';
		$mpdfHtml .= '<div style="margin:auto;font-size:16px;line-height:24px;color:#555;">';
        $mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;" cellpadding="0" cellspacing="0">';
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td colspan="2" style="vertical-align:top;">';
        $mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;">';
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td style="font-size:45px;line-height:45px;color:#333;padding:5px;vertical-align:top;padding-bottom:20px;">';

        if (isset($dataThemeSettingArr['data']->show_logo) && $dataThemeSettingArr['data']->show_logo == '1' && isset($dataThemeSettingArr['data']->theme_logo) && $dataThemeSettingArr['data']->theme_logo != "") {
            $mpdfHtml .= '<img src="upload/theme-logo/' . $dataThemeSettingArr['data']->theme_logo . '" style="max-width:300px;">';
        }

        $mpdfHtml .= '</td>';
		
		if(isset($dataInvoiceSettingArr['data']->invoice_label) && !empty($dataInvoiceSettingArr['data']->invoice_label)) { $invoice_label = $dataInvoiceSettingArr['data']->invoice_label; } else { $invoice_label = "Invoice #"; }
		if(isset($dataInvoiceSettingArr['data']->reference_label) && !empty($dataInvoiceSettingArr['data']->reference_label)) { $reference_label = $dataInvoiceSettingArr['data']->reference_label; } else { $reference_label = "Reference #"; }
		if(isset($dataInvoiceSettingArr['data']->type_label) && !empty($dataInvoiceSettingArr['data']->type_label)) { $type_label = $dataInvoiceSettingArr['data']->type_label; } else { $type_label = "Type"; }
		if(isset($dataInvoiceSettingArr['data']->nature_label) && !empty($dataInvoiceSettingArr['data']->nature_label)) { $nature_label = $dataInvoiceSettingArr['data']->nature_label; } else { $nature_label = "Nature"; }
		if(isset($dataInvoiceSettingArr['data']->date_label) && !empty($dataInvoiceSettingArr['data']->date_label)) { $date_label = $dataInvoiceSettingArr['data']->date_label; } else { $date_label = "Invoice Date"; }

		$mpdfHtml .= '<td style="vertical-align:top;text-align:right;padding-bottom:20px;">';
		$mpdfHtml .= '<b>'.$invoice_label.'</b>: ' . $invoiceData[0]->serial_number . '<br>';
        $mpdfHtml .= '<b>'.$reference_label.'</b>: ' . $invoiceData[0]->reference_number . '<br>';
		$mpdfHtml .= '<b>'.$type_label.'</b>: Refund Voucher<br>';
		$mpdfHtml .= '<b>'.$nature_label.'</b>: Sales Invoice<br>';
        $mpdfHtml .= '<b>'.$date_label.'</b>: ' . $invoiceData[0]->invoice_date;
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';
        $mpdfHtml .= '</table>';
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';

        $supply_place_data = $this->getStateDetailByStateId($invoiceData[0]->supply_place);

        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td colspan="2" style="vertical-align:top;">';
        $mpdfHtml .= '<table style="width:100%;line-height:inherit;">';
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td style="text-align:left;vertical-align:top;padding-bottom:20px;width:48%;padding-right:2%;">';
        $mpdfHtml .= html_entity_decode($invoiceData[0]->company_name) . '<br>';
		$mpdfHtml .= html_entity_decode($invoiceData[0]->company_address) . '<br>';
		if(!empty($invoiceData[0]->company_email)) { $mpdfHtml .= '<b>Email:</b> ' . $invoiceData[0]->company_email . '<br>'; }
        if(!empty($invoiceData[0]->company_phone_number)) { $mpdfHtml .= '<b>Phone:</b> ' . $invoiceData[0]->company_phone_number . '<br>'; }
		$panFromGTIN = substr(substr($invoiceData[0]->gstin_number, 2), 0, -3);
		$mpdfHtml .= '<b>PAN:</b> ' . $panFromGTIN  . '<br>';
		$mpdfHtml .= '<b>GSTIN:</b> ' . $invoiceData[0]->gstin_number;
        $mpdfHtml .= '</td>';

        $mpdfHtml .= '<td style="vertical-align:top;text-align:right;padding-bottom:20px;width:48%;padding-left:2%;">';

		if (isset($invoiceData[0]->supply_place) && $invoiceData[0]->supply_place > 0) {
			if($supply_place_data['data']->state_tin == 97) {
				$mpdfHtml .= '<b>Place Of Supply:</b> ' . $supply_place_data['data']->state_name . '<br>';
			} else {
				$mpdfHtml .= '<b>Place Of Supply:</b> ' . $supply_place_data['data']->state_name . '(' . $supply_place_data['data']->state_tin . ')' . '<br>';
			}
		}

		if ($invoiceData[0]->is_tax_payable == '1') {
			$mpdfHtml .= '<b>Reverse Charge:</b> Yes<br>';
		} else {
			$mpdfHtml .= '<b>Reverse Charge:</b> No<br>';
		}

		$dataReceiptVoucherRow = $this->get_row("select * from ".$this->tableNames['client_invoice']." where invoice_id = '".$invoiceData[0]->refund_voucher_receipt."' AND invoice_type = 'receiptvoucherinvoice' AND is_deleted='0' AND added_by = ".$this->sanitize($_SESSION['user_detail']['user_id']));
		if(!empty($dataReceiptVoucherRow)) {
			$mpdfHtml .= '<b>Receipt Voucher Serial:</b> '. $dataReceiptVoucherRow->serial_number .'<br>';
			$mpdfHtml .= '<b>Receipt Voucher Reference:</b> '. $dataReceiptVoucherRow->reference_number .'<br>';
			$mpdfHtml .= '<b>Receipt Voucher Date:</b> '. $dataReceiptVoucherRow->invoice_date .'<br>';
		}

		if ($invoiceData[0]->is_canceled == 1) { $mpdfHtml .= '<b>Canceled Invoice:</b> Canceled'; }

        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';
        $mpdfHtml .= '</table>';
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';

        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td colspan="2" style="vertical-align:top;">';
        $mpdfHtml .= '<table style="width:100%;line-height:inherit;">';
        $mpdfHtml .= '<tr>';
       
		$mpdfHtml .= '<td style="vertical-align:top;text-align:left;padding-bottom:40px;width:48%;padding-right:2%;">';
			$mpdfHtml .= '<b>Recipient Detail</b><br>';
			$mpdfHtml .= $invoiceData[0]->billing_name . '<br>';
			if ($invoiceData[0]->billing_company_name) { $mpdfHtml .= $invoiceData[0]->billing_company_name . '<br>'; }
			$mpdfHtml .= $invoiceData[0]->billing_address . '<br>';
			
			$billing_vendor_data = $this->getVendorDetailByVendorId($invoiceData[0]->billing_vendor_type);
			$mpdfHtml .= $billing_vendor_data['data']->vendor_name . '<br>';

			if (!empty($invoiceData[0]->billing_gstin_number)) { $mpdfHtml .= '<b>GSTIN/UIN:</b> ' . $invoiceData[0]->billing_gstin_number; }
		$mpdfHtml .= '</td>';

		$mpdfHtml .= '<td style="vertical-align:top;text-align:right;padding-bottom:40px;width:48%;padding-left:2%;">';
			$mpdfHtml .= '<b>Address Of Delivery / Shipping Detail</b><br>';
			$mpdfHtml .= $invoiceData[0]->shipping_name . '<br>';
			if ($invoiceData[0]->shipping_company_name) { $mpdfHtml .= $invoiceData[0]->shipping_company_name . '<br>'; }
			$mpdfHtml .= $invoiceData[0]->shipping_address . '<br>';
			
			$shipping_vendor_data = $this->getVendorDetailByVendorId($invoiceData[0]->shipping_vendor_type);
			$mpdfHtml .= $shipping_vendor_data['data']->vendor_name . '<br>';
			
			if (!empty($invoiceData[0]->shipping_gstin_number)) { $mpdfHtml .= '<b>GSTIN/UIN:</b> ' . $invoiceData[0]->shipping_gstin_number; }
		$mpdfHtml .= '</td>';

        $mpdfHtml .= '</tr>';
        $mpdfHtml .= '</table>';
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';
		
        $mpdfHtml .= '</table>';

		$mpdfHtml .= '<table border="1" style="border-collapse:collapse;width:100%;line-height:inherit;text-align:center;">';
        $mpdfHtml .= '<tr>';
		$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">S.No</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Goods/Services</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">HSN/SAC Code</td>';
		$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Item Description</td>';
		$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Refund Value (₹)</td>';
        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">CGST</td>';
        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">SGST</td>';
        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">IGST</td>';
        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">CESS</td>';
        $mpdfHtml .= '</tr>';

        $mpdfHtml .= '<tr class="heading">';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
        $mpdfHtml .= '</tr>';

        $counter = 1;
        $total_taxable_subtotal = 0.00;
        $total_cgst_amount = 0.00;
        $total_sgst_amount = 0.00;
        $total_igst_amount = 0.00;
        $total_cess_amount = 0.00;
        foreach ($invoiceData as $invData) {

            $mpdfHtml .= '<tr>';
            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $counter;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->item_name;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->item_hsncode;
            $mpdfHtml .= '</td>';
			
			$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->item_description;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->taxable_subtotal;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->cgst_rate;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->cgst_amount;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->sgst_rate;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->sgst_amount;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->igst_rate;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->igst_amount;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->cess_rate;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
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

        $mpdfHtml .= '<tr style="background:#d9edf7;">';
		$mpdfHtml .= '<td colspan="4" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Total Invoice Value</td>';
		$mpdfHtml .= '<td>'.$total_taxable_subtotal.'</td>';
		$mpdfHtml .= '<td>&nbsp;</td>';
		$mpdfHtml .= '<td>'.$total_cgst_amount.'</td>';
		$mpdfHtml .= '<td>&nbsp;</td>';
		$mpdfHtml .= '<td>'.$total_sgst_amount.'</td>';
		$mpdfHtml .= '<td>&nbsp;</td>';
		$mpdfHtml .= '<td>'.$total_igst_amount.'</td>';
		$mpdfHtml .= '<td>&nbsp;</td>';
		$mpdfHtml .= '<td>'.$total_cess_amount.'</td>';
		$mpdfHtml .= '</tr>';
		
		if ($invoiceData[0]->is_tax_payable == "1") {

            if ($invoiceData[0]->company_state === $invoiceData[0]->supply_place) {

                $mpdfHtml .= '<tr style="background:#e9ffdb;font-size:14px;">';
                $mpdfHtml .= '<td colspan="5" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Amount of Tax Subject to Reverse Charge</td>';
                $mpdfHtml .= '<td>-</td>';
                $mpdfHtml .= '<td>' . $total_cgst_amount . '</td>';
                $mpdfHtml .= '<td>-</td>';
                $mpdfHtml .= '<td>' . $total_sgst_amount . '</td>';
                $mpdfHtml .= '<td>-</td>';
                $mpdfHtml .= '<td>0.00</td>';
                $mpdfHtml .= '<td>-</td>';
                $mpdfHtml .= '<td>' . $total_cess_amount . '</td>';
                $mpdfHtml .= '</tr>';
            } else {

                $mpdfHtml .= '<tr style="background:#e9ffdb;font-size:14px;">';
                $mpdfHtml .= '<td colspan="5" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Amount of Tax Subject to Reverse Charge</td>';
                $mpdfHtml .= '<td>-</td>';
                $mpdfHtml .= '<td>0.00</td>';
                $mpdfHtml .= '<td>-</td>';
                $mpdfHtml .= '<td>0.00</td>';
                $mpdfHtml .= '<td>-</td>';
                $mpdfHtml .= '<td>' . $total_igst_amount . '</td>';
                $mpdfHtml .= '<td>-</td>';
                $mpdfHtml .= '<td>' . $total_cess_amount . '</td>';
                $mpdfHtml .= '</tr>';
            }
        }

        $mpdfHtml .= '<tr style="background:#ffefbf;">';
		$mpdfHtml .= '<td colspan="13" style="padding:5px;vertical-align:top;text-align:right;font-weight:bold;">';
        $mpdfHtml .= 'Total Invoice Value (In Figure): ₹' . $invoiceData[0]->invoice_total_value;
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';

        $invoice_total_value_words = $this->convert_number_to_words($invoiceData[0]->invoice_total_value);

        $mpdfHtml .= '<tr style="background:#f2dede;">';
        $mpdfHtml .= '<td colspan="13" style="padding:5px;vertical-align:top;text-align:right;font-weight:bold;">';
        $mpdfHtml .= 'Total Invoice Value (In Words): ' . ucwords($invoice_total_value_words);
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';

        $mpdfHtml .= '</table>';

        $mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;" cellpadding="0" cellspacing="0">';

			if(!empty($invoiceData[0]->description)) {
				$mpdfHtml .= '<tr class="description">';
					$mpdfHtml .= '<td colspan="2" style="padding-top:20px;vertical-align:top;">';
						$mpdfHtml .= '<p><b>Additional Notes:</b> '. $invoiceData[0]->description .'</p>';
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';
			}

			$mpdfHtml .= '<tr>';
				$mpdfHtml .= '<td colspan="2" style="padding-top:20px;vertical-align:top;">';
					$mpdfHtml .= '<table style="width:100%;line-height:inherit;">';
						
						$mpdfHtml .= '<tr>';

							$mpdfHtml .= '<td style="vertical-align:top;width:50%;">';
								
								if(
									!empty($dataCurrentUserArr['data']->kyc->bank_name) || 
									!empty($dataCurrentUserArr['data']->kyc->account_number) || 
									!empty($dataCurrentUserArr['data']->kyc->branch_name) || 
									!empty($dataCurrentUserArr['data']->kyc->ifsc_code)
								) {
								
									$mpdfHtml .= '<b>Bank Details :-</b><br>';

									$mpdfHtml .= '<table width="100%" border="1" style="border-collapse:collapse;width:100%;line-height:inherit;">';
										$mpdfHtml .= '<tr>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:40%;">';
												$mpdfHtml .= '<b>Bank Name</b>';
											$mpdfHtml .= '</td>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:60%;padding-left:5px;">';
												$mpdfHtml .= $dataCurrentUserArr['data']->kyc->bank_name;
											$mpdfHtml .= '</td>';
										
										$mpdfHtml .= '</tr>';

										$mpdfHtml .= '<tr>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:40%;">';
												$mpdfHtml .= '<b>Account Number</b>';
											$mpdfHtml .= '</td>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:60%;padding-left:5px;">';
												$mpdfHtml .= $dataCurrentUserArr['data']->kyc->account_number;
											$mpdfHtml .= '</td>';

										$mpdfHtml .= '</tr>';									

										$mpdfHtml .= '<tr>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:40%;">';
												$mpdfHtml .= '<b>Branch Name</b>';
											$mpdfHtml .= '</td>';
											
											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:60%;padding-left:5px;">';
												$mpdfHtml .= $dataCurrentUserArr['data']->kyc->branch_name;
											$mpdfHtml .= '</td>';
										
										$mpdfHtml .= '</tr>';

										$mpdfHtml .= '<tr>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:40%;">';
												$mpdfHtml .= '<b>IFSC Code</b>';
											$mpdfHtml .= '</td>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:60%;padding-left:5px;">';
												$mpdfHtml .= $dataCurrentUserArr['data']->kyc->ifsc_code;
											$mpdfHtml .= '</td>';

										$mpdfHtml .= '</tr>';

									$mpdfHtml .= '</table>';
								}

							$mpdfHtml .= '</td>';

							$mpdfHtml .= '<td style="padding-top:0px;vertical-align:top;padding-left:10%;width:40%;">';

								$mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:center;">';
									$mpdfHtml .= '<tr class="signature">';
										if (isset($dataThemeSettingArr['data']->show_signature) && $dataThemeSettingArr['data']->show_signature == '1' && isset($dataThemeSettingArr['data']->theme_signature) && $dataThemeSettingArr['data']->theme_signature != "") {
											$mpdfHtml .= '<td style="vertical-align:top;">';
										} else {
											$mpdfHtml .= '<td style="padding-top:50px;vertical-align:top;">';
										}
										
											if (isset($dataThemeSettingArr['data']->show_signature) && $dataThemeSettingArr['data']->show_signature == '1' && isset($dataThemeSettingArr['data']->theme_signature) && $dataThemeSettingArr['data']->theme_signature != "") {
												$mpdfHtml .= '<img src="upload/theme-signature/' . $dataThemeSettingArr['data']->theme_signature . '" style="max-width:300px;">';
											}
										
											$mpdfHtml .= '<p style="text-align:right;">';
												$mpdfHtml .= '<hr style="height:2px;">';
											$mpdfHtml .= '</p>';
											$mpdfHtml .= '<p style="text-align:center;">';
												$mpdfHtml .= 'For ' . $dataCurrentUserArr['data']->kyc->name;
											$mpdfHtml .= '</p>';
											$mpdfHtml .= '<p style="text-align:center;">';
												$mpdfHtml .= '<b>(Authorised Signatory)</b>';
											$mpdfHtml .= '</p>';
										$mpdfHtml .= '</td>';
									$mpdfHtml .= '</tr>';
								$mpdfHtml .= '</table>';

							$mpdfHtml .= '</td>';

						$mpdfHtml .= '</tr>';

					$mpdfHtml .= '</table>';
				$mpdfHtml .= '</td>';
			$mpdfHtml .= '</tr>';

        $mpdfHtml .= '</table>';
        $mpdfHtml .= '</div>';

        return $mpdfHtml;
    }

	/* generate revised tax invoice html */
    public function generateRTInvoiceHtml($invid) {

        $currentFinancialYear = $this->generateFinancialYear();
		
		/* get current user data */
		$dataCurrentUserArr = $this->getUserDetailsById($this->sanitize($_SESSION['user_detail']['user_id']));

        $invoiceData = $this->get_results("select 
											ci.*, 
											cii.invoice_item_id, 
											cii.item_id, 
											cii.item_name, 
											cii.item_hsncode, 
											cii.item_description, 
											cii.item_quantity, 
											cii.item_unit, 
											cii.item_unit_price, 
											cii.subtotal, 
											cii.discount, 
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
										" . $this->tableNames['client_invoice'] . " as ci INNER JOIN " . $this->tableNames['client_invoice_item'] . " as cii ON ci.invoice_id = cii.invoice_id where ci.invoice_id = " . $invid . " AND ci.invoice_type IN('revisedtaxinvoice', 'creditnote', 'debitnote') AND ci.added_by = '" . $this->sanitize($_SESSION['user_detail']['user_id']) . "' AND cii.added_by = '" . $this->sanitize($_SESSION['user_detail']['user_id']) . "' AND ci.is_deleted='0' AND cii.is_deleted='0'");

        if (empty($invoiceData)) {
            return false;
        }

        $dataThemeSettingArr = $this->getUserThemeSetting($this->sanitize($_SESSION['user_detail']['user_id']));
		$dataInvoiceSettingArr = $this->getUserInvoiceSetting($this->sanitize($_SESSION['user_detail']['user_id']));

        $mpdfHtml = '';
        $mpdfHtml .= '<div style="margin:auto;font-size:16px;line-height:24px;color:#555;">';
        $mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;" cellpadding="0" cellspacing="0">';
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td colspan="2" style="vertical-align:top;">';
        $mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;">';
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td style="font-size:45px;line-height:45px;color:#333;padding:5px;vertical-align:top;padding-bottom:20px;">';

        if (isset($dataThemeSettingArr['data']->show_logo) && $dataThemeSettingArr['data']->show_logo == '1' && isset($dataThemeSettingArr['data']->theme_logo) && $dataThemeSettingArr['data']->theme_logo != "") {
            $mpdfHtml .= '<img src="upload/theme-logo/' . $dataThemeSettingArr['data']->theme_logo . '" style="max-width:300px;">';
        }

        $mpdfHtml .= '</td>';

		if($invoiceData[0]->invoice_type == "creditnote") { $invoiceType = "Credit Note"; } 
		else if($invoiceData[0]->invoice_type == "debitnote") { $invoiceType = "Debit Note"; } 
		else { $invoiceType = "Revised Tax Invoice"; }

		if(isset($dataInvoiceSettingArr['data']->invoice_label) && !empty($dataInvoiceSettingArr['data']->invoice_label)) { $invoice_label = $dataInvoiceSettingArr['data']->invoice_label; } else { $invoice_label = "Invoice #"; }
		if(isset($dataInvoiceSettingArr['data']->reference_label) && !empty($dataInvoiceSettingArr['data']->reference_label)) { $reference_label = $dataInvoiceSettingArr['data']->reference_label; } else { $reference_label = "Reference #"; }
		if(isset($dataInvoiceSettingArr['data']->type_label) && !empty($dataInvoiceSettingArr['data']->type_label)) { $type_label = $dataInvoiceSettingArr['data']->type_label; } else { $type_label = "Type"; }
		if(isset($dataInvoiceSettingArr['data']->nature_label) && !empty($dataInvoiceSettingArr['data']->nature_label)) { $nature_label = $dataInvoiceSettingArr['data']->nature_label; } else { $nature_label = "Nature"; }
		if(isset($dataInvoiceSettingArr['data']->date_label) && !empty($dataInvoiceSettingArr['data']->date_label)) { $date_label = $dataInvoiceSettingArr['data']->date_label; } else { $date_label = "Invoice Date"; }

		$mpdfHtml .= '<td style="vertical-align:top;text-align:right;padding-bottom:20px;">';
		$mpdfHtml .= '<b>'.$invoice_label.'</b>: ' . $invoiceData[0]->serial_number . '<br>';
        $mpdfHtml .= '<b>'.$reference_label.'</b>: ' . $invoiceData[0]->reference_number . '<br>';
		$mpdfHtml .= '<b>'.$type_label.'</b>: ' . $invoiceType . '<br>';
		$mpdfHtml .= '<b>'.$nature_label.'</b>: Sales Invoice<br>';
        $mpdfHtml .= '<b>'.$date_label.'</b>: ' . $invoiceData[0]->invoice_date;
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';
        $mpdfHtml .= '</table>';
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';

        $supply_place_data = $this->getStateDetailByStateId($invoiceData[0]->supply_place);

        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td colspan="2" style="vertical-align:top;">';
        $mpdfHtml .= '<table style="width:100%;line-height:inherit;">';
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td style="text-align:left;vertical-align:top;padding-bottom:20px;width:48%;padding-right:2%;">';
		$mpdfHtml .= html_entity_decode($invoiceData[0]->company_name) . '<br>';
		$mpdfHtml .= html_entity_decode($invoiceData[0]->company_address) . '<br>';
		if(!empty($invoiceData[0]->company_email)) { $mpdfHtml .= '<b>Email:</b> ' . $invoiceData[0]->company_email . '<br>'; }
        if(!empty($invoiceData[0]->company_phone_number)) { $mpdfHtml .= '<b>Phone:</b> ' . $invoiceData[0]->company_phone_number . '<br>'; }
		$panFromGTIN = substr(substr($invoiceData[0]->gstin_number, 2), 0, -3);
		$mpdfHtml .= '<b>PAN:</b> ' . $panFromGTIN  . '<br>';
		$mpdfHtml .= '<b>GSTIN:</b> ' . $invoiceData[0]->gstin_number;
        $mpdfHtml .= '</td>';

        $mpdfHtml .= '<td style="vertical-align:top;text-align:right;padding-bottom:20px;width:48%;padding-left:2%;">';

		if (isset($invoiceData[0]->supply_place) && $invoiceData[0]->supply_place > 0) {
			if($supply_place_data['data']->state_tin == 97) {
				$mpdfHtml .= '<b>Place Of Supply:</b> ' . $supply_place_data['data']->state_name . '<br>';
			} else {
				$mpdfHtml .= '<b>Place Of Supply:</b> ' . $supply_place_data['data']->state_name . '(' . $supply_place_data['data']->state_tin . ')' . '<br>';
			}
		}

		$mpdfHtml .= '<b>Reason Issuing Document:</b> ' . $invoiceData[0]->reason_issuing_document . '<br>';

		if($invoiceData[0]->invoice_corresponding_type == "taxinvoice") { $invoiceType = "Tax Invoice"; } 
		else if($invoiceData[0]->invoice_corresponding_type == "billofsupplyinvoice") { $invoiceType = "Bill of Supply Invoice"; }

		$mpdfHtml .= '<b>Corresponding Type:</b> ' . $invoiceType . '<br>';

		$dataCorresDocumentRow = $this->get_row("select * from " . $this->tableNames['client_invoice'] . " where invoice_id = '".$invoiceData[0]->corresponding_document_number."' AND invoice_type = '".$invoiceData[0]->invoice_corresponding_type."' AND is_deleted='0' AND added_by = ".$this->sanitize($_SESSION['user_detail']['user_id']));

		if(!empty($dataCorresDocumentRow)) {
			$mpdfHtml .= '<b>Document Serial:</b> '. $dataCorresDocumentRow->serial_number .'<br>';
			$mpdfHtml .= '<b>Document Reference:</b> '. $dataCorresDocumentRow->reference_number .'<br>';
			$mpdfHtml .= '<b>Document Date:</b> '. $dataCorresDocumentRow->invoice_date .'<br>';
		}

		if ($invoiceData[0]->is_canceled == 1) { $mpdfHtml .= '<b>Canceled Invoice:</b> Canceled'; }

        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';
        $mpdfHtml .= '</table>';
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';

        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td colspan="2" style="vertical-align:top;">';
        $mpdfHtml .= '<table style="width:100%;line-height:inherit;">';
        $mpdfHtml .= '<tr>';
       
		$mpdfHtml .= '<td style="vertical-align:top;text-align:left;padding-bottom:40px;width:48%;padding-right:2%;">';
			$mpdfHtml .= '<b>Recipient Detail</b><br>';
			$mpdfHtml .= $invoiceData[0]->billing_name . '<br>';
			if ($invoiceData[0]->billing_company_name) { $mpdfHtml .= $invoiceData[0]->billing_company_name . '<br>'; }
			$mpdfHtml .= $invoiceData[0]->billing_address . '<br>';
			
			$billing_vendor_data = $this->getVendorDetailByVendorId($invoiceData[0]->billing_vendor_type);
			$mpdfHtml .= $billing_vendor_data['data']->vendor_name . '<br>';

			if (!empty($invoiceData[0]->billing_gstin_number)) { $mpdfHtml .= '<b>GSTIN/UIN:</b> ' . $invoiceData[0]->billing_gstin_number; }
		$mpdfHtml .= '</td>';

		$mpdfHtml .= '<td style="vertical-align:top;text-align:right;padding-bottom:40px;width:48%;padding-left:2%;">';
			$mpdfHtml .= '<b>Address Of Delivery / Shipping Detail</b><br>';
			$mpdfHtml .= $invoiceData[0]->shipping_name . '<br>';
			if ($invoiceData[0]->shipping_company_name) { $mpdfHtml .= $invoiceData[0]->shipping_company_name . '<br>'; }
			$mpdfHtml .= $invoiceData[0]->shipping_address . '<br>';
			
			$shipping_vendor_data = $this->getVendorDetailByVendorId($invoiceData[0]->shipping_vendor_type);
			$mpdfHtml .= $shipping_vendor_data['data']->vendor_name . '<br>';
			
			if (!empty($invoiceData[0]->shipping_gstin_number)) { $mpdfHtml .= '<b>GSTIN/UIN:</b> ' . $invoiceData[0]->shipping_gstin_number; }
		$mpdfHtml .= '</td>';

        $mpdfHtml .= '</tr>';
        $mpdfHtml .= '</table>';
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';
		
        $mpdfHtml .= '</table>';

        $mpdfHtml .= '<table border="1" style="border-collapse:collapse;width:100%;line-height:inherit;text-align:center;">';
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">S.No</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Goods/Services</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">HSN/SAC Code</td>';
		$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Item Description</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Qty</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Unit</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Rate (₹)</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Total (₹)</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Discount (%)</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Taxable Value (₹)</td>';
        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">CGST</td>';
        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">SGST</td>';
        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">IGST</td>';
        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">CESS</td>';
        $mpdfHtml .= '</tr>';

        $mpdfHtml .= '<tr class="heading">';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
        $mpdfHtml .= '</tr>';

        $counter = 1;
        $total_taxable_subtotal = 0.00;
        $total_cgst_amount = 0.00;
        $total_sgst_amount = 0.00;
        $total_igst_amount = 0.00;
        $total_cess_amount = 0.00;
        foreach ($invoiceData as $invData) {

            $mpdfHtml .= '<tr>';
            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $counter;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->item_name;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->item_hsncode;
            $mpdfHtml .= '</td>';
			
			$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->item_description;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->item_quantity;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->item_unit;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->item_unit_price;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->subtotal;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->discount;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->taxable_subtotal;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->cgst_rate;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->cgst_amount;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->sgst_rate;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->sgst_amount;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->igst_rate;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->igst_amount;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->cess_rate;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
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

		$mpdfHtml .= '<tr style="background:#d9edf7;">';
		$mpdfHtml .= '<td colspan="9" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Total Invoice Value</td>';
		$mpdfHtml .= '<td>'.$total_taxable_subtotal.'</td>';
		$mpdfHtml .= '<td>&nbsp;</td>';
		$mpdfHtml .= '<td>'.$total_cgst_amount.'</td>';
		$mpdfHtml .= '<td>&nbsp;</td>';
		$mpdfHtml .= '<td>'.$total_sgst_amount.'</td>';
		$mpdfHtml .= '<td>&nbsp;</td>';
		$mpdfHtml .= '<td>'.$total_igst_amount.'</td>';
		$mpdfHtml .= '<td>&nbsp;</td>';
		$mpdfHtml .= '<td>'.$total_cess_amount.'</td>';
		$mpdfHtml .= '</tr>';

		$mpdfHtml .= '<tr style="background:#ffefbf;">';
        $mpdfHtml .= '<td colspan="18" style="padding:5px;vertical-align:top;text-align:right;font-weight:bold;">';
        $mpdfHtml .= 'Total Invoice Value (In Figure): ₹' . $invoiceData[0]->invoice_total_value;
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';

        $invoice_total_value_words = $this->convert_number_to_words($invoiceData[0]->invoice_total_value);

        $mpdfHtml .= '<tr style="background:#f2dede;">';
        $mpdfHtml .= '<td colspan="18" style="padding:5px;vertical-align:top;text-align:right;font-weight:bold;">';
        $mpdfHtml .= 'Total Invoice Value (In Words): ' . ucwords($invoice_total_value_words);
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';

        $mpdfHtml .= '</table>';

		$mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;" cellpadding="0" cellspacing="0">';

			if(!empty($invoiceData[0]->description)) {
				$mpdfHtml .= '<tr class="description">';
					$mpdfHtml .= '<td colspan="2" style="padding-top:20px;vertical-align:top;">';
						$mpdfHtml .= '<p><b>Additional Notes:</b> '. $invoiceData[0]->description .'</p>';
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';
			}

			$mpdfHtml .= '<tr>';
				$mpdfHtml .= '<td colspan="2" style="padding-top:20px;vertical-align:top;">';
					$mpdfHtml .= '<table style="width:100%;line-height:inherit;">';
						
						$mpdfHtml .= '<tr>';

							$mpdfHtml .= '<td style="vertical-align:top;width:50%;">';
								
								if(
									!empty($dataCurrentUserArr['data']->kyc->bank_name) || 
									!empty($dataCurrentUserArr['data']->kyc->account_number) || 
									!empty($dataCurrentUserArr['data']->kyc->branch_name) || 
									!empty($dataCurrentUserArr['data']->kyc->ifsc_code)
								) {
								
									$mpdfHtml .= '<b>Bank Details :-</b><br>';

									$mpdfHtml .= '<table width="100%" border="1" style="border-collapse:collapse;width:100%;line-height:inherit;">';
										$mpdfHtml .= '<tr>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:40%;">';
												$mpdfHtml .= '<b>Bank Name</b>';
											$mpdfHtml .= '</td>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:60%;padding-left:5px;">';
												$mpdfHtml .= $dataCurrentUserArr['data']->kyc->bank_name;
											$mpdfHtml .= '</td>';
										
										$mpdfHtml .= '</tr>';

										$mpdfHtml .= '<tr>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:40%;">';
												$mpdfHtml .= '<b>Account Number</b>';
											$mpdfHtml .= '</td>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:60%;padding-left:5px;">';
												$mpdfHtml .= $dataCurrentUserArr['data']->kyc->account_number;
											$mpdfHtml .= '</td>';

										$mpdfHtml .= '</tr>';									

										$mpdfHtml .= '<tr>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:40%;">';
												$mpdfHtml .= '<b>Branch Name</b>';
											$mpdfHtml .= '</td>';
											
											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:60%;padding-left:5px;">';
												$mpdfHtml .= $dataCurrentUserArr['data']->kyc->branch_name;
											$mpdfHtml .= '</td>';
										
										$mpdfHtml .= '</tr>';

										$mpdfHtml .= '<tr>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:40%;">';
												$mpdfHtml .= '<b>IFSC Code</b>';
											$mpdfHtml .= '</td>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:60%;padding-left:5px;">';
												$mpdfHtml .= $dataCurrentUserArr['data']->kyc->ifsc_code;
											$mpdfHtml .= '</td>';

										$mpdfHtml .= '</tr>';

									$mpdfHtml .= '</table>';
								}

							$mpdfHtml .= '</td>';

							$mpdfHtml .= '<td style="padding-top:0px;vertical-align:top;padding-left:10%;width:40%;">';

								$mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:center;">';
									$mpdfHtml .= '<tr class="signature">';
										if (isset($dataThemeSettingArr['data']->show_signature) && $dataThemeSettingArr['data']->show_signature == '1' && isset($dataThemeSettingArr['data']->theme_signature) && $dataThemeSettingArr['data']->theme_signature != "") {
											$mpdfHtml .= '<td style="vertical-align:top;">';
										} else {
											$mpdfHtml .= '<td style="padding-top:50px;vertical-align:top;">';
										}

											if (isset($dataThemeSettingArr['data']->show_signature) && $dataThemeSettingArr['data']->show_signature == '1' && isset($dataThemeSettingArr['data']->theme_signature) && $dataThemeSettingArr['data']->theme_signature != "") {
												$mpdfHtml .= '<img src="upload/theme-signature/' . $dataThemeSettingArr['data']->theme_signature . '" style="max-width:300px;">';
											}

											$mpdfHtml .= '<p style="text-align:right;">';
												$mpdfHtml .= '<hr style="height:2px;">';
											$mpdfHtml .= '</p>';
											$mpdfHtml .= '<p style="text-align:center;">';
												$mpdfHtml .= 'For ' . $dataCurrentUserArr['data']->kyc->name;
											$mpdfHtml .= '</p>';
											$mpdfHtml .= '<p style="text-align:center;">';
												$mpdfHtml .= '<b>(Authorised Signatory)</b>';
											$mpdfHtml .= '</p>';
										$mpdfHtml .= '</td>';
									$mpdfHtml .= '</tr>';
								$mpdfHtml .= '</table>';

							$mpdfHtml .= '</td>';

						$mpdfHtml .= '</tr>';

					$mpdfHtml .= '</table>';
				$mpdfHtml .= '</td>';
			$mpdfHtml .= '</tr>';

        $mpdfHtml .= '</table>';
        $mpdfHtml .= '</div>';

        return $mpdfHtml;
    }

	/* generate sales invoice html */
    public function generateDCInvoiceHtml($invid) {

        $currentFinancialYear = $this->generateFinancialYear();
		
		/* get current user data */
		$dataCurrentUserArr = $this->getUserDetailsById($this->sanitize($_SESSION['user_detail']['user_id']));

        $invoiceData = $this->get_results("select 
											ci.*, 
											cii.invoice_item_id, 
											cii.item_id, 
											cii.item_name, 
											cii.item_hsncode, 
											cii.item_description, 
											cii.item_quantity, 
											cii.item_unit, 
											cii.item_unit_price, 
											cii.subtotal, 
											cii.discount, 
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
										" . $this->tableNames['client_invoice'] . " as ci INNER JOIN " . $this->tableNames['client_invoice_item'] . " as cii ON ci.invoice_id = cii.invoice_id where ci.invoice_id = " . $invid . " AND ci.invoice_type = 'deliverychallaninvoice' AND ci.added_by = '" . $this->sanitize($_SESSION['user_detail']['user_id']) . "' AND cii.added_by = '" . $this->sanitize($_SESSION['user_detail']['user_id']) . "' AND ci.is_deleted='0' AND cii.is_deleted='0'");

        if (empty($invoiceData)) {
            return false;
        }

        $dataThemeSettingArr = $this->getUserThemeSetting($this->sanitize($_SESSION['user_detail']['user_id']));
		$dataInvoiceSettingArr = $this->getUserInvoiceSetting($this->sanitize($_SESSION['user_detail']['user_id']));

        $mpdfHtml = '';
        $mpdfHtml .= '<div style="margin:auto;font-size:16px;line-height:24px;color:#555;">';
        $mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;" cellpadding="0" cellspacing="0">';
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td colspan="2" style="vertical-align:top;">';
        $mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;">';
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td style="font-size:45px;line-height:45px;color:#333;padding:5px;vertical-align:top;padding-bottom:20px;">';

        if (isset($dataThemeSettingArr['data']->show_logo) && $dataThemeSettingArr['data']->show_logo == '1' && isset($dataThemeSettingArr['data']->theme_logo) && $dataThemeSettingArr['data']->theme_logo != "") {
            $mpdfHtml .= '<img src="upload/theme-logo/' . $dataThemeSettingArr['data']->theme_logo . '" style="max-width:300px;">';
        }

        $mpdfHtml .= '</td>';
		
		if(isset($dataInvoiceSettingArr['data']->invoice_label) && !empty($dataInvoiceSettingArr['data']->invoice_label)) { $invoice_label = $dataInvoiceSettingArr['data']->invoice_label; } else { $invoice_label = "Invoice #"; }
		if(isset($dataInvoiceSettingArr['data']->reference_label) && !empty($dataInvoiceSettingArr['data']->reference_label)) { $reference_label = $dataInvoiceSettingArr['data']->reference_label; } else { $reference_label = "Reference #"; }
		if(isset($dataInvoiceSettingArr['data']->type_label) && !empty($dataInvoiceSettingArr['data']->type_label)) { $type_label = $dataInvoiceSettingArr['data']->type_label; } else { $type_label = "Type"; }
		if(isset($dataInvoiceSettingArr['data']->nature_label) && !empty($dataInvoiceSettingArr['data']->nature_label)) { $nature_label = $dataInvoiceSettingArr['data']->nature_label; } else { $nature_label = "Nature"; }
		if(isset($dataInvoiceSettingArr['data']->date_label) && !empty($dataInvoiceSettingArr['data']->date_label)) { $date_label = $dataInvoiceSettingArr['data']->date_label; } else { $date_label = "Invoice Date"; }

		$mpdfHtml .= '<td style="vertical-align:top;text-align:right;padding-bottom:20px;">';
		$mpdfHtml .= '<b>'.$invoice_label.'</b>: ' . $invoiceData[0]->serial_number . '<br>';
        $mpdfHtml .= '<b>'.$reference_label.'</b>: ' . $invoiceData[0]->reference_number . '<br>';
		$mpdfHtml .= '<b>'.$type_label.'</b>: Delivery Challan<br>';
		$mpdfHtml .= '<b>'.$nature_label.'</b>: Sales Invoice<br>';
        $mpdfHtml .= '<b>'.$date_label.'</b>: ' . $invoiceData[0]->invoice_date;
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';
        $mpdfHtml .= '</table>';
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';

        $supply_place_data = $this->getStateDetailByStateId($invoiceData[0]->supply_place);

        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td colspan="2" style="vertical-align:top;">';
        $mpdfHtml .= '<table style="width:100%;line-height:inherit;">';
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td style="text-align:left;vertical-align:top;padding-bottom:20px;width:48%;padding-right:2%;">';
        $mpdfHtml .= html_entity_decode($invoiceData[0]->company_name) . '<br>';
        $mpdfHtml .= html_entity_decode($invoiceData[0]->company_address) . '<br>';
		if(!empty($invoiceData[0]->company_email)) { $mpdfHtml .= '<b>Email:</b> ' . $invoiceData[0]->company_email . '<br>'; }
        if(!empty($invoiceData[0]->company_phone_number)) { $mpdfHtml .= '<b>Phone:</b> ' . $invoiceData[0]->company_phone_number . '<br>'; }
		$panFromGTIN = substr(substr($invoiceData[0]->gstin_number, 2), 0, -3);
		$mpdfHtml .= '<b>PAN:</b> ' . $panFromGTIN  . '<br>';
		$mpdfHtml .= '<b>GSTIN:</b> ' . $invoiceData[0]->gstin_number;
        $mpdfHtml .= '</td>';

        $mpdfHtml .= '<td style="vertical-align:top;text-align:right;padding-bottom:20px;width:48%;padding-left:2%;">';

		if (isset($invoiceData[0]->supply_place) && $invoiceData[0]->supply_place > 0) {
			if($supply_place_data['data']->state_tin == 97) {
				$mpdfHtml .= '<b>Place Of Supply:</b> ' . $supply_place_data['data']->state_name . '<br>';
			} else {
				$mpdfHtml .= '<b>Place Of Supply:</b> ' . $supply_place_data['data']->state_name . '(' . $supply_place_data['data']->state_tin . ')' . '<br>';
			}
		}

		if($invoiceData[0]->delivery_challan_type == "jobwork") { $challanType = "Job Work"; } 
		else if($invoiceData[0]->delivery_challan_type == "supplyofliquidgas") { $challanType = "Supply of Liquid Gas"; } 
		else if($invoiceData[0]->delivery_challan_type == "supplyonapproval") { $challanType = "Supply on Approval"; } 
		else { $challanType = "Others"; }

		$mpdfHtml .= '<b>Challan Type:</b> ' . $challanType . '<br>';
		if ($invoiceData[0]->is_canceled == 1) { $mpdfHtml .= '<b>Canceled Invoice:</b> Canceled'; }

		$mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';
        $mpdfHtml .= '</table>';
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';

        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td colspan="2" style="vertical-align:top;">';
        $mpdfHtml .= '<table style="width:100%;line-height:inherit;">';
        $mpdfHtml .= '<tr>';

		$mpdfHtml .= '<td style="vertical-align:top;text-align:left;padding-bottom:40px;width:48%;padding-right:2%;">';
			$mpdfHtml .= '<b>Consignee Detail</b><br>';
			$mpdfHtml .= $invoiceData[0]->billing_name . '<br>';
			if ($invoiceData[0]->billing_company_name) { $mpdfHtml .= $invoiceData[0]->billing_company_name . '<br>'; }
			$mpdfHtml .= $invoiceData[0]->billing_address . '<br>';
			
			$billing_vendor_data = $this->getVendorDetailByVendorId($invoiceData[0]->billing_vendor_type);
			$mpdfHtml .= $billing_vendor_data['data']->vendor_name . '<br>';

			if (!empty($invoiceData[0]->billing_gstin_number)) { $mpdfHtml .= '<b>GSTIN/UIN:</b> ' . $invoiceData[0]->billing_gstin_number; }
		$mpdfHtml .= '</td>';

        $mpdfHtml .= '</tr>';
        $mpdfHtml .= '</table>';
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';

        $mpdfHtml .= '</table>';

        $mpdfHtml .= '<table border="1" style="border-collapse:collapse;width:100%;line-height:inherit;text-align:center;">';
        $mpdfHtml .= '<tr>';
		$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">S.No</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Goods/Services</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">HSN/SAC Code</td>';
		$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Item Description</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Qty</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Unit</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Rate (₹)</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Total (₹)</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Discount (%)</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Taxable Value (₹)</td>';
        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">CGST</td>';
        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">SGST</td>';
        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">IGST</td>';
        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">CESS</td>';
        $mpdfHtml .= '</tr>';

        $mpdfHtml .= '<tr class="heading">';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
        $mpdfHtml .= '</tr>';

        $counter = 1;
        $total_taxable_subtotal = 0.00;
        $total_cgst_amount = 0.00;
        $total_sgst_amount = 0.00;
        $total_igst_amount = 0.00;
        $total_cess_amount = 0.00;
        foreach ($invoiceData as $invData) {

            $mpdfHtml .= '<tr>';
            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $counter;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->item_name;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->item_hsncode;
            $mpdfHtml .= '</td>';
			
			$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->item_description;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->item_quantity;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->item_unit;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->item_unit_price;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->subtotal;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->discount;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->taxable_subtotal;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->cgst_rate;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->cgst_amount;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->sgst_rate;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->sgst_amount;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->igst_rate;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->igst_amount;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
            $mpdfHtml .= $invData->cess_rate;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
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
		
		$mpdfHtml .= '<tr style="background:#d9edf7;">';
		$mpdfHtml .= '<td colspan="9" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Total Invoice Value</td>';
		$mpdfHtml .= '<td>'.$total_taxable_subtotal.'</td>';
		$mpdfHtml .= '<td>&nbsp;</td>';
		$mpdfHtml .= '<td>'.$total_cgst_amount.'</td>';
		$mpdfHtml .= '<td>&nbsp;</td>';
		$mpdfHtml .= '<td>'.$total_sgst_amount.'</td>';
		$mpdfHtml .= '<td>&nbsp;</td>';
		$mpdfHtml .= '<td>'.$total_igst_amount.'</td>';
		$mpdfHtml .= '<td>&nbsp;</td>';
		$mpdfHtml .= '<td>'.$total_cess_amount.'</td>';
		$mpdfHtml .= '</tr>';

        $mpdfHtml .= '<tr style="background:#ffefbf;">';
        $mpdfHtml .= '<td colspan="18" style="padding:5px;vertical-align:top;text-align:right;font-weight:bold;">';
        $mpdfHtml .= 'Total Invoice Value (In Figure): ₹' . $invoiceData[0]->invoice_total_value;
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';

        $invoice_total_value_words = $this->convert_number_to_words($invoiceData[0]->invoice_total_value);

        $mpdfHtml .= '<tr style="background:#f2dede;">';
        $mpdfHtml .= '<td colspan="18" style="padding:5px;vertical-align:top;text-align:right;font-weight:bold;">';
        $mpdfHtml .= 'Total Invoice Value (In Words): ' . ucwords($invoice_total_value_words);
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';

        $mpdfHtml .= '</table>';

        $mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;" cellpadding="0" cellspacing="0">';

			if(!empty($invoiceData[0]->description)) {
				$mpdfHtml .= '<tr class="description">';
					$mpdfHtml .= '<td colspan="2" style="padding-top:20px;vertical-align:top;">';
						$mpdfHtml .= '<p><b>Additional Notes:</b> '. $invoiceData[0]->description .'</p>';
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';
			}

			$mpdfHtml .= '<tr>';
				$mpdfHtml .= '<td colspan="2" style="padding-top:20px;vertical-align:top;">';
					$mpdfHtml .= '<table style="width:100%;line-height:inherit;">';
						
						$mpdfHtml .= '<tr>';

							$mpdfHtml .= '<td style="vertical-align:top;width:50%;">';
								
								if(
									!empty($dataCurrentUserArr['data']->kyc->bank_name) || 
									!empty($dataCurrentUserArr['data']->kyc->account_number) || 
									!empty($dataCurrentUserArr['data']->kyc->branch_name) || 
									!empty($dataCurrentUserArr['data']->kyc->ifsc_code)
								) {
								
									$mpdfHtml .= '<b>Bank Details :-</b><br>';

									$mpdfHtml .= '<table width="100%" border="1" style="border-collapse:collapse;width:100%;line-height:inherit;">';
										$mpdfHtml .= '<tr>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:40%;">';
												$mpdfHtml .= '<b>Bank Name</b>';
											$mpdfHtml .= '</td>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:60%;padding-left:5px;">';
												$mpdfHtml .= $dataCurrentUserArr['data']->kyc->bank_name;
											$mpdfHtml .= '</td>';
										
										$mpdfHtml .= '</tr>';

										$mpdfHtml .= '<tr>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:40%;">';
												$mpdfHtml .= '<b>Account Number</b>';
											$mpdfHtml .= '</td>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:60%;padding-left:5px;">';
												$mpdfHtml .= $dataCurrentUserArr['data']->kyc->account_number;
											$mpdfHtml .= '</td>';

										$mpdfHtml .= '</tr>';									

										$mpdfHtml .= '<tr>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:40%;">';
												$mpdfHtml .= '<b>Branch Name</b>';
											$mpdfHtml .= '</td>';
											
											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:60%;padding-left:5px;">';
												$mpdfHtml .= $dataCurrentUserArr['data']->kyc->branch_name;
											$mpdfHtml .= '</td>';
										
										$mpdfHtml .= '</tr>';

										$mpdfHtml .= '<tr>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:40%;">';
												$mpdfHtml .= '<b>IFSC Code</b>';
											$mpdfHtml .= '</td>';

											$mpdfHtml .= '<td style="vertical-align:top;text-align:left;width:60%;padding-left:5px;">';
												$mpdfHtml .= $dataCurrentUserArr['data']->kyc->ifsc_code;
											$mpdfHtml .= '</td>';

										$mpdfHtml .= '</tr>';

									$mpdfHtml .= '</table>';
								}

							$mpdfHtml .= '</td>';

							$mpdfHtml .= '<td style="padding-top:0px;vertical-align:top;padding-left:10%;width:40%;">';

								$mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:center;">';
									$mpdfHtml .= '<tr class="signature">';
										if (isset($dataThemeSettingArr['data']->show_signature) && $dataThemeSettingArr['data']->show_signature == '1' && isset($dataThemeSettingArr['data']->theme_signature) && $dataThemeSettingArr['data']->theme_signature != "") {
											$mpdfHtml .= '<td style="vertical-align:top;">';
										} else {
											$mpdfHtml .= '<td style="padding-top:50px;vertical-align:top;">';
										}

											if (isset($dataThemeSettingArr['data']->show_signature) && $dataThemeSettingArr['data']->show_signature == '1' && isset($dataThemeSettingArr['data']->theme_signature) && $dataThemeSettingArr['data']->theme_signature != "") {
												$mpdfHtml .= '<img src="upload/theme-signature/' . $dataThemeSettingArr['data']->theme_signature . '" style="max-width:300px;">';
											}

											$mpdfHtml .= '<p style="text-align:right;">';
												$mpdfHtml .= '<hr style="height:2px;">';
											$mpdfHtml .= '</p>';
											$mpdfHtml .= '<p style="text-align:center;">';
												$mpdfHtml .= 'For ' . $dataCurrentUserArr['data']->kyc->name;
											$mpdfHtml .= '</p>';
											$mpdfHtml .= '<p style="text-align:center;">';
												$mpdfHtml .= '<b>(Authorised Signatory)</b>';
											$mpdfHtml .= '</p>';
										$mpdfHtml .= '</td>';
									$mpdfHtml .= '</tr>';
								$mpdfHtml .= '</table>';

							$mpdfHtml .= '</td>';

						$mpdfHtml .= '</tr>';

					$mpdfHtml .= '</table>';
				$mpdfHtml .= '</td>';
			$mpdfHtml .= '</tr>';

        $mpdfHtml .= '</table>';
        $mpdfHtml .= '</div>';

        return $mpdfHtml;
    }

	/* validate client sales invoice */
    public function validateClientSalesInvoice($dataArr) {

		if (array_key_exists("invoice_type", $dataArr)) {
            $rules['invoice_type'] = 'required||invoicetype|#|lable_name:Invoice Type';
        }

        if (array_key_exists("invoice_nature", $dataArr)) {
            $rules['invoice_nature'] = 'required||invoicenature|#|lable_name:Invoice Nature';
        }
		
		if (array_key_exists("invoice_date", $dataArr)) {
            $rules['invoice_date'] = 'required||date|#|lable_name:Invoice Date';
        }
		
		if (array_key_exists("reference_number", $dataArr)) {
            $rules['reference_number'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/||max:16|#|lable_name:Reference Number';
        }
		
		if (array_key_exists("company_name", $dataArr)) {
            $rules['company_name'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Company Name';
        }

        if (array_key_exists("company_address", $dataArr)) {
            $rules['company_address'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Company Address';
        }

        if (array_key_exists("company_state", $dataArr)) {
            $rules['company_state'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Company State';
        }

        if (array_key_exists("gstin_number", $dataArr)) {
            $rules['gstin_number'] = 'required||pattern:/^' . $this->validateType['gstinnumber'] . '+$/||min:15||max:15|#|lable_name:Company GSTIN Number';
        }

        if (array_key_exists("supply_type", $dataArr)) {
            $rules['supply_type'] = 'required||supplytype|#|lable_name:Supply Type';
        }
		
		if( array_key_exists("export_supply_meant", $dataArr) ) {
            $rules['export_supply_meant'] = 'required||supplymeant|#|lable_name:Supply Meant';
        }

		if (array_key_exists("ecommerce_gstin_number", $dataArr)) {
            $rules['ecommerce_gstin_number'] = 'required||pattern:/^' . $this->validateType['gstinnumber'] . '+$/||min:15||max:15|#|lable_name:Ecommerce GSTIN Number';
        }
		
		if (array_key_exists("ecommerce_vendor_code", $dataArr)) {
            $rules['ecommerce_vendor_code'] = 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Ecommerce Vendor Code';
        }

		if (array_key_exists("export_bill_number", $dataArr)) {
            $rules['export_bill_number'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Export Bill Number';
        }
		
		if (array_key_exists("export_bill_port_code", $dataArr)) {
            $rules['export_bill_port_code'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/||max:6|#|lable_name:Export Bill Port Code';
        }
		
		if (array_key_exists("export_bill_date", $dataArr)) {
            $rules['export_bill_date'] = 'required||date|#|lable_name:Export Bill Date';
        }

		if (array_key_exists("delivery_challan_type", $dataArr)) {
            $rules['delivery_challan_type'] = 'required||deliverychallantype|#|lable_name:Delivery Challan Type';
        }
		
		if (array_key_exists("invoice_corresponding_type", $dataArr)) {
            $rules['invoice_corresponding_type'] = 'required||invoiecorresponding|#|lable_name:Invoice Corresponding Type';
        }

        if (array_key_exists("corresponding_document_number", $dataArr)) {
            $rules['corresponding_document_number'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Corresponding Document Number';
        }

        if (array_key_exists("corresponding_document_date", $dataArr)) {
            $rules['corresponding_document_date'] = 'required||date|#|lable_name:Corresponding Document Date';
        }

        if (array_key_exists("is_tax_payable", $dataArr)) {
            $rules['is_tax_payable'] = 'required||pattern:/^[' . $this->validateType['onlyzeroone'] . ']*$/|#|lable_name:Tax Reverse Charge';
        }

        if (array_key_exists("supply_place", $dataArr)) {
            $rules['supply_place'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Place Of Supply';
        }
		
		if( array_key_exists("description", $dataArr) ) {
            $rules['description'] = 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Description';
        }

		if (array_key_exists("advance_adjustment", $dataArr)) {
            $rules['advance_adjustment'] = 'required||pattern:/^[' . $this->validateType['onlyzeroone'] . ']*$/|#|lable_name:Advance Adjustment';
        }

		if (array_key_exists("refund_voucher_receipt", $dataArr)) {
            $rules['refund_voucher_receipt'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Receipt Voucher';
        }

        if (array_key_exists("billing_name", $dataArr)) {
            $rules['billing_name'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Billing Name';
        }
		
		if (array_key_exists("billing_company_name", $dataArr)) {
            $rules['billing_company_name'] = 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Billing Company Name';
        }

        if (array_key_exists("billing_address", $dataArr)) {
            $rules['billing_address'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Billing Address';
        }

        if (array_key_exists("billing_state", $dataArr)) {
            $rules['billing_state'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Billing State';
        }
		
		if (array_key_exists("billing_state_name", $dataArr)) {
            $rules['billing_state_name'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Billing State Name';
        }

		if (array_key_exists("billing_country", $dataArr)) {
            $rules['billing_country'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Billing Country';
        }

		if (array_key_exists("billing_vendor_type", $dataArr)) {
            $rules['billing_vendor_type'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Billing Vendor Type';
        }

        if (array_key_exists("billing_gstin_number", $dataArr)) {
            $rules['billing_gstin_number'] = 'pattern:/^' . $this->validateType['gstinnumber'] . '+$/||min:15||max:15|#|lable_name:Billing GSTIN Number';
        }

        if (array_key_exists("shipping_name", $dataArr)) {
            $rules['shipping_name'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Shipping Name';
        }
		
		if (array_key_exists("shipping_company_name", $dataArr)) {
            $rules['shipping_company_name'] = 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Shipping Company Name';
        }

        if (array_key_exists("shipping_address", $dataArr)) {
            $rules['shipping_address'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Shipping Address';
        }

        if (array_key_exists("shipping_state", $dataArr)) {
            $rules['shipping_state'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Shipping State';
        }

		if (array_key_exists("shipping_state_name", $dataArr)) {
            $rules['shipping_state_name'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Shipping State Name';
        }

		if (array_key_exists("shipping_country", $dataArr)) {
            $rules['shipping_country'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Shipping Country';
        }

		if (array_key_exists("shipping_vendor_type", $dataArr)) {
            $rules['shipping_vendor_type'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Shipping Vendor Type';
        }

        if (array_key_exists("shipping_gstin_number", $dataArr)) {
            $rules['shipping_gstin_number'] = 'pattern:/^' . $this->validateType['gstinnumber'] . '+$/||min:15||max:15|#|lable_name:Shipping GSTIN Number';
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
    /* end of validate client sales invoice */

    /* validate client sales invoice items */
    public function validateClientSalesInvoiceItem($dataArr, $serialno) {

		if (array_key_exists("invoice_itemid", $dataArr)) {
            $rules['invoice_itemid'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Invoice Item no. ' . $serialno;
        }

		if (array_key_exists("invoice_description", $dataArr)) {
            $rules['invoice_description'] = 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Description of Item no. ' . $serialno;
        }

		if (array_key_exists("invoice_quantity", $dataArr)) {
            $rules['invoice_quantity'] = 'required||numeric||decimal|#|lable_name:Quantity of Item no. ' . $serialno;
        }

        if (array_key_exists("invoice_discount", $dataArr)) {
            $rules['invoice_discount'] = 'numeric||decimalzero|#|lable_name:Discount of Item no. ' . $serialno;
        }

		if (array_key_exists("invoice_rate", $dataArr)) {
            $rules['invoice_rate'] = 'required||numeric||decimal|#|lable_name:Rate of Item no. ' . $serialno;
        }

        if (array_key_exists("invoice_taxablevalue", $dataArr)) {
            $rules['invoice_taxablevalue'] = 'required||numeric||decimalzero|#|lable_name:Taxable Amount of Item no. ' . $serialno;
        }

		if (array_key_exists("invoice_advancevalue", $dataArr)) {
            $rules['invoice_advancevalue'] = 'numeric||decimalzero|#|lable_name:Advance Amount of Item no. ' . $serialno;
        }

		if (array_key_exists("invoice_cgstrate", $dataArr)) {
            $rules['invoice_cgstrate'] = 'numeric|#|lable_name:CGST Rate of Item no. ' . $serialno;
        }

		if (array_key_exists("invoice_sgstrate", $dataArr)) {
            $rules['invoice_sgstrate'] = 'numeric|#|lable_name:SGST Rate of Item no. ' . $serialno;
        }

		if (array_key_exists("invoice_igstrate", $dataArr)) {
            $rules['invoice_igstrate'] = 'numeric|#|lable_name:IGST Rate of Item no. ' . $serialno;
        }

		if (array_key_exists("invoice_cessrate", $dataArr)) {
            $rules['invoice_cessrate'] = 'numeric|#|lable_name:CESS Rate of Item no. ' . $serialno;
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
	/* end of validate client sales invoice items */
}
?>