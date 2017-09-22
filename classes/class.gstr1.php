<?php

/*
 * 
 *  Developed By        :   Monika Deswal 
 *  Description         :   API GSTR1
 *  Date Created        :   May 18, 2017
 *  Last Modified By    :   Monika Deswal 
 *  Last Modification   :   Upload Invoice
 * 
 */

final class gstr1 extends validation {

    function __construct() {
        parent::__construct();
    }

    public function gstr1Upload($ids= '',$invoice_type='') {
        //session_destroy();
        $fmonth = isset($_GET['returnmonth']) ? $_GET['returnmonth'] : date('Y-m');
        return $this->getGSTR1Data($fmonth,$ids,$invoice_type);
    }

    public function gstr1PayloadDownload($ids='',$type='',$invoice_type='') {
        //session_destroy();
        
        $fmonth = isset($_GET['returnmonth']) ? $_GET['returnmonth'] : date('Y-m');
        $payload = $this->gstCreatePayload($_SESSION['user_detail']['user_id'], $fmonth,$ids,$type,$invoice_type);
        $dataArr = json_encode($payload['data_arr']);
        header("Content-type: text/json");
        header("Content-Disposition: attachment; filename=gstr1.json");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $dataArr;
        exit;

    }

    public function selectgstr1Upload() {

        $fmonth = isset($_GET['returnmonth']) ? $_GET['returnmonth'] : date('Y-m');
        $this->selectgetGSTR1Data($fmonth);
    }

    private function selectgetGSTR1Data($fmonth) {
        $dataRes = $this->generalGSTR1InvoiceList($fmonth);
        $flag = 0;
        if (!empty($dataRes)) {
            $x = 0;
            $dataUpdate = array();

            if (!empty($_POST['name'])) {
                // Loop to store and display values of individual checked checkbox.
                foreach ($_POST['name'] as $selected) {
                    $dataUpdate[$x]['set']['is_gstr1_uploaded'] = '1';
                    $dataUpdate[$x]['where']['invoice_id'] = $selected;
                    $x++;
                }
                $this->updateMultiple($this->getTableName('client_invoice'), $dataUpdate);
				$this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . " update GSTR1 all invoices financial month ".$fmonth,"gstr1");
				
            }
            else
            {
                $this->setError('No invoice selected to upload');
                return false;
            }

            $flag = 1;

            $dataReturn = $this->get_results("select * from " . $this->getTableName('return') . " where return_month='" . $fmonth . "' and client_id='" . $_SESSION['user_detail']['user_id'] . "' and type='gstr1'");
            if (!empty($dataReturn)) {
                $dataGST1_set['financial_year'] = $this->generateFinancialYear();
                $dataGST1_set['return_month'] = $fmonth;
                $dataGST1_set['status'] = '2';


                $dataGST1['type'] = 'gstr1';
                $dataGST1['client_id'] = $_SESSION['user_detail']['user_id'];

                $this->update($this->getTableName('return'), $dataGST1_set, $dataGST1);
				$this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . " update GSTR1 upload status financial month ".$fmonth,"gstr1");
			
            } else {
                $dataGST1['financial_year'] = $this->generateFinancialYear();
                $dataGST1['return_month'] = $fmonth;
                $dataGST1['type'] = 'gstr1';
                $dataGST1['client_id'] = $_SESSION['user_detail']['user_id'];
                $dataGST1['status'] = '2';
                $this->insert($this->getTableName('return'), $dataGST1);
				$this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . " Upload GSTR1 for financial month ".$fmonth,"gstr1");
			
            }

            $this->setSuccess("GSTR1 Data Uploaded");
        }
        if ($flag == 1) {
            return true;
        }
        if ($flag == 2) {
            return false;
        }
        if ($flag == 0) {
            $this->setError('No new data to upload');
            return false;
        }
        return false;
    }

    private function getGSTR1Data($fmonth,$ids='',$invoice_type='') {
        $obj_gst = new gstr();
        //$obj_gst->gstr_session_destroy();
        $is_gross_turnover_check = (float)$obj_gst->is_gross_turnover_check($_SESSION['user_detail']['user_id']);
        $cur_gt=  (float)$obj_gst->cur_gross_turnover($_SESSION['user_detail']['user_id']);
        $is_username_check = $obj_gst->is_username_exists($_SESSION['user_detail']['user_id']);
        $dataRes = $this->generalGSTR1InvoiceList($fmonth);
		//$this->pr($dataRes);
        $flag = 0;
        if(!empty($is_username_check)) {
            if (!empty($is_gross_turnover_check) && !empty($cur_gt)) {
                if (!empty($dataRes)) {
                    $payload = $this->gstCreatePayload($_SESSION['user_detail']['user_id'], $fmonth,$ids,'',$invoice_type);

                    $dataArr = $payload['data_arr'];
                    $data_ids = $payload['data_ids'];

                    //$this->pr($payload);die;
                    $response = $obj_gst->returnSave($dataArr, $fmonth,'gstr1');
                                
                    if ($response['error'] == 0) {
                        $flag = 1;
                        if($invoice_type != 'HSN' && $invoice_type != 'NIL' && $invoice_type != 'DOCISSUE') {
                            if (!empty($data_ids)) {
                                /********** Start Code for Update Invoice is upload ************* */
                                $flagup = 0;
                                /*$this->pr($data_ids);
                                die;*/
                                $this->query("UPDATE ".$this->getTableName('client_invoice')." SET is_gstr1_uploaded='1' WHERE invoice_id in (".$data_ids.")");
        						
                                /*********** End code for Update Invoice is upload ********* */

                                /******************* Start Code Return Save **************** */
                                $dataReturn = $this->get_results("select * from " . $this->getTableName('return') . " where return_month='" . $fmonth . "' and client_id='" . $_SESSION['user_detail']['user_id'] . "' and type='gstr1'");
                                if ($flagup == '1') {
                                    if (!empty($dataReturn)) {
                                        $dataGST1_set['financial_year'] = $this->generateFinancialYear();
                                        $dataGST1_set['return_month'] = $fmonth;
                                        $dataGST1_set['status'] = '2';
                                        $dataGST1['type'] = 'gstr1';
                                        $dataGST1['client_id'] = $_SESSION['user_detail']['user_id'];
                                        $this->update($this->getTableName('return'), $dataGST1_set, $dataGST1);
        								$this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . " update GSTR1 upload status financial month ".$fmonth,"gstr1");
        			
                                    } else {
                                        $dataGST1['financial_year'] = $this->generateFinancialYear();
                                        $dataGST1['return_month'] = $fmonth;
                                        $dataGST1['type'] = 'gstr1';
                                        $dataGST1['client_id'] = $_SESSION['user_detail']['user_id'];
                                        $dataGST1['status'] = '2';
                                        $this->insert($this->getTableName('return'), $dataGST1);
        								$this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . " Uploaded GSTR1 for financial month ".$fmonth,"gstr1");
        			
                                    }
                                }
                                /* ******* End Code for Return Save ************************* */
                                //$this->setSuccess(" Congratulations! GSTR1 Data Uploaded.");
                            } 
                            else {
                                $flag = 2;
                                $this->setError('file not updated');
                            }
                        }
                        elseif($invoice_type == 'HSN') {
                            $this->query("UPDATE ".$this->getTableName('return_upload_summary')." SET is_uploaded='1' WHERE added_by = '".$_SESSION['user_detail']['user_id']."' and financial_month = '".$fmonth."' and type = 'gstr1hsn' ");
                        }
                        elseif($invoice_type == 'NIL') {
                            $this->query("UPDATE ".$this->getTableName('return_upload_summary')." SET is_uploaded='1' WHERE added_by = '".$_SESSION['user_detail']['user_id']."' and financial_month = '".$fmonth."' and type = 'gstr1nil' ");
                        }
                        elseif($invoice_type == 'DOCISSUE') {
                            $this->query("UPDATE ".$this->getTableName('return_upload_summary')." SET is_uploaded='1' WHERE added_by = '".$_SESSION['user_detail']['user_id']."' and financial_month = '".$fmonth."' and type = 'gstr1document' ");
                        }
                        else {
                            $flag = 2;
                            $this->setError('file not updated');
                        }

                    } 
                    else {
                        $flag = 2;
                        $this->setError($response['message']);
                    }
                }
                
                if ($flag == 1) {
                    
                    $this->setSuccess(" Congratulations! GSTR1 Data Uploaded.");
                    return true;
                }
                elseif ($flag == 2) {
                    return false;
                }
                elseif ($flag == 0) {
                    $this->setError('No new data to upload');
                    return false;
                }
                return false;
            } else {
                $this->setError('Sorry! Please update your gross turnover');
                return false;
            }
        }
        else {
            $this->setError('Sorry! Please update your GSTIN username');
            return false;
        }
        return false;
    }


    public function gstr1File() {
        $fmonth = isset($_GET['returnmonth']) ? $_GET['returnmonth'] : date('Y-m');
        $this->getGSTR1FileData($fmonth);
    }

    private function getGSTR1FileData($fmonth) {
        $dataRes = $this->generalGSTR1InvoiceList($fmonth, '1');
        $flag = 0;

        if (!empty($dataRes)) {
            $x = 0;
            $dataUpdate = array();
            foreach ($dataRes as $dataRe) {
                $dataUpdate[$x]['set']['is_gstr1_uploaded'] = '2';
                $dataUpdate[$x]['where']['invoice_id'] = $dataRe->invoice_id;
                $x++;
            }
            if ($this->updateMultiple($this->getTableName('client_invoice'), $dataUpdate)) {
                $flag = 1;
                $dataReturn = $this->get_results('select * from '.$this->getTableName('return')." where return_month='".$this->sanitize($_GET['returnmonth'])."' and type='gstr1'");
                if (!empty($dataReturn)) {
                    $dataGST1_set['financial_year'] = $this->generateFinancialYear();
                    $dataGST1_set['return_month'] = $fmonth;
                    $dataGST1_set['status'] = '3';

                    $dataGST1['type'] = 'gstr1';
                    $dataGST1['client_id'] = $_SESSION['user_detail']['user_id'];

                    $this->update($this->getTableName('return'), $dataGST1_set, $dataGST1);
					$this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . "Update GSTR1 File status financial month ".$fmonth,"gstr1");
			
                } else {
                    $dataGST1['financial_year'] = '2017-2018';
                    $dataGST1['return_month'] = $fmonth;
                    $dataGST1['type'] = 'gstr1';
                    $dataGST1['client_id'] = $_SESSION['user_detail']['user_id'];
                    $dataGST1['status'] = '3';
                    $this->insert($this->getTableName('return'), $dataGST1);
					$this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . " file GSTR1 for financial month ".$fmonth,"gstr1");
			
                }
                $this->setSuccess("GSTR1 is Filed");
            } else {
                $flag = 2;
                $this->setError("Failed to File GSTR1");
            }
        }
        if ($flag == 1) {
            return true;
        }
        if ($flag == 2) {
            return false;
        }
        if ($flag == 0) {
            $this->setError('No invoices are uploaded to GST');
            return false;
        }
        return false;
    }

    public function gstPayloadHeader($user_id, $returnmonth) {
        $obj_gst = new gstr();
        $dataArr = array();
        $api_return_period = $obj_gst->getRetrunPeriodFormat($returnmonth);
        $dataArr["gstin"] = $obj_gst->gstin();
        $dataArr["fp"] = $api_return_period;
        $dataArr["gt"] = (float) $obj_gst->gross_turnover($user_id);
        $dataArr["cur_gt"] = (float) $obj_gst->cur_gross_turnover($user_id);
        return $dataArr;
    }

    public function gstCreatePayload($user_id, $returnmonth,$ids='',$type='',$invoice_type='') {

        $data_ids = array();
        $dataArr = $this->gstPayloadHeader($user_id, $returnmonth);

        if($invoice_type == '') {
            /***** Start Code For B2B Payload ********** */
            $b2b_data = $this->gstB2BPayload($user_id, $returnmonth,'',$ids,$type);
            if (!empty($b2b_data)) {
                $data_ids[] = $b2b_ids = $b2b_data['b2b_ids'];
                $b2b_arr = $b2b_data['b2b_arr'];
                $dataArr = array_merge($dataArr, $b2b_arr);
            }
            /***** End Code For B2B Payload ********** */

            /***** Start Code For B2CL Payload ********** */
            $b2cl_data = $this->gstB2CLPayload($user_id, $returnmonth,'',$ids,$type);
            if (!empty($b2cl_data)) {
                $data_ids[] = $b2cl_ids = $b2cl_data['b2cl_ids'];
                $b2cl_arr = $b2cl_data['b2cl_arr'];
                $dataArr = array_merge($dataArr, $b2cl_arr);
            }
            /***** End Code For B2CL Payload ********** */

            /***** Start Code For B2CS Payload ********** */
            $b2cs_data = $this->gstB2CSPayload($user_id, $returnmonth,'',$ids,$type);
            if (!empty($b2cs_data)) {
                $data_ids[] = $b2cs_ids = $b2cs_data['b2cs_ids'];
                $b2cs_arr = $b2cs_data['b2cs_arr'];
                $dataArr = array_merge($dataArr, $b2cs_arr);
            }
            /***** End Code For B2CS Payload ********** */

            /** *** Start Code For CDNR Payload ********** */
            $cdnr_data = $this->gstCDNRPayload($user_id, $returnmonth,'',$ids,$type);
            if (!empty($cdnr_data)) {
                $data_ids[] = $cdnr_ids = $cdnr_data['cdnr_ids'];
                $cdnr_arr = $cdnr_data['cdnr_arr'];
                $dataArr = array_merge($dataArr, $cdnr_arr);
            }
            /****** End Code For CDNR Payload ********** */

            /** *** Start Code For CDNUR Payload ********** */
            $cdnur_data = $this->gstCDNURPayload($user_id, $returnmonth,'',$ids,$type);
            if (!empty($cdnur_data)) {
                $data_ids[] = $cdnur_ids = $cdnur_data['cdnur_ids'];
                $cdnur_arr = $cdnur_data['cdnur_arr'];
                $dataArr = array_merge($dataArr, $cdnur_arr);
            }
            /***** End Code For CDNUR Payload ********** */

            /***** Start Code For HSN Summary Payload ********** */
            $hsn_data = $this->gstHSNPayload($user_id, $returnmonth,'',$ids,$type);
            if (!empty($hsn_data)) {
                $data_ids[] = $hsn_ids = $hsn_data['hsn_ids'];
                $hsn_arr = $hsn_data['hsn_arr'];
                $dataArr = array_merge($dataArr, $hsn_arr);
            }
            /***** END Code For HSN Summary Payload ********** */

            /***** Start Code For AT Payload ********** */
            $at_data = $this->gstATPayload($user_id, $returnmonth,'',$ids,$type);
            if (!empty($at_data)) {
                $data_ids[] = $at_ids = $at_data['at_ids'];
                $at_arr = $at_data['at_arr'];
                $dataArr = array_merge($dataArr, $at_arr);
            }
            /***** End Code For AT Payload ********** */

            /***** Start Code For NIL Payload ********** */
            $nil_data = $this->getNILPayload($user_id, $returnmonth,'',$ids,$type);
            if (!empty($nil_data)) {
                $data_ids[] = $nil_ids = $nil_data['nil_ids'];
                $nil_arr = $nil_data['nil_arr'];
                $dataArr = array_merge($dataArr, $nil_arr);
            }
            /***** End Code For NIL Payload ********** */

            /***** Start Code For Doc Issue Payload ********** */
            $doc_data = $this->getDOCISSUEPayload($user_id, $returnmonth,'',$ids,$type);
            if (!empty($doc_data)) {
                $data_ids[] = $doc_ids = $doc_data['doc_ids'];
                $doc_arr = $doc_data['doc_arr'];
                $dataArr = array_merge($dataArr, $doc_arr);
            }
            /***** End Code For Doc Issue Payload ********** */

            /***** Start Code For Exp Payload ********** */
            $exp_data = $this->getEXPPayload($user_id, $returnmonth,'',$ids,$type);
            if (!empty($exp_data)) {
                $data_ids[] = $exp_ids = $exp_data['exp_ids'];
                $exp_arr = $exp_data['exp_arr'];
                $dataArr = array_merge($dataArr, $exp_arr);
            }
            /***** End Code For Exp Payload ********** */

            /***** Start Code For TXPD  Payload ********** */
            // $txpd_data = $this->getTXPDPayload($user_id, $returnmonth,,'',$ids);
            // if (!empty($txpd_data)) {
            //     $data_ids[] = $txpd_ids = $txpd_data['txpd_ids'];
            //     $txpd_arr = $txpd_data['txpd_arr'];
            //     $dataArr = array_merge($dataArr, $txpd_arr);
            // }
            /***** End Code For TXPD Payload ********** */ 
        }

        if($invoice_type == 'B2B') {
            /***** Start Code For B2B Payload ********** */
            $b2b_data = $this->gstB2BPayload($user_id, $returnmonth,'',$ids,'all');
            if (!empty($b2b_data)) {
                $data_ids[] = $b2b_ids = $b2b_data['b2b_ids'];
                $b2b_arr = $b2b_data['b2b_arr'];
                $dataArr = array_merge($dataArr, $b2b_arr);
            }
            /***** End Code For B2B Payload ********** */
        }
        if($invoice_type == 'B2CL') {
            /***** Start Code For B2CL Payload ********** */
            $b2cl_data = $this->gstB2CLPayload($user_id, $returnmonth,'',$ids,'all');
            if (!empty($b2cl_data)) {
                $data_ids[] = $b2cl_ids = $b2cl_data['b2cl_ids'];
                $b2cl_arr = $b2cl_data['b2cl_arr'];
                $dataArr = array_merge($dataArr, $b2cl_arr);
            }
            /***** End Code For B2CL Payload ********** */
        }
        if($invoice_type == 'B2CS') {
            /***** Start Code For B2CS Payload ********** */
            $b2cs_data = $this->gstB2CSPayload($user_id, $returnmonth,'',$ids,'all');
            if (!empty($b2cs_data)) {
                $data_ids[] = $b2cs_ids = $b2cs_data['b2cs_ids'];
                $b2cs_arr = $b2cs_data['b2cs_arr'];
                $dataArr = array_merge($dataArr, $b2cs_arr);
            }
            /***** End Code For B2CS Payload ********** */
        }
        if($invoice_type == 'CDNR') {
            /** *** Start Code For CDNR Payload ********** */
            $cdnr_data = $this->gstCDNRPayload($user_id, $returnmonth,'',$ids,'all');
            if (!empty($cdnr_data)) {
                $data_ids[] = $cdnr_ids = $cdnr_data['cdnr_ids'];
                $cdnr_arr = $cdnr_data['cdnr_arr'];
                $dataArr = array_merge($dataArr, $cdnr_arr);
            }
            /****** End Code For CDNR Payload ********** */
        }
        if($invoice_type == 'CDNUR') {
            /** *** Start Code For CDNUR Payload ********** */
            $cdnur_data = $this->gstCDNURPayload($user_id, $returnmonth,'',$ids,'all');
            if (!empty($cdnur_data)) {
                $data_ids[] = $cdnur_ids = $cdnur_data['cdnur_ids'];
                $cdnur_arr = $cdnur_data['cdnur_arr'];
                $dataArr = array_merge($dataArr, $cdnur_arr);
            }
            /***** End Code For CDNUR Payload ********** */
        }
        if($invoice_type == 'AT') {
            /***** Start Code For AT Payload ********** */
            $at_data = $this->gstATPayload($user_id, $returnmonth,'',$ids,'all');
            if (!empty($at_data)) {
                $data_ids[] = $at_ids = $at_data['at_ids'];
                $at_arr = $at_data['at_arr'];
                $dataArr = array_merge($dataArr, $at_arr);
            }
            /***** End Code For AT Payload ********** */
        }
        if($invoice_type == 'EXP') {
            /***** Start Code For Exp Payload ********** */
            $exp_data = $this->getEXPPayload($user_id, $returnmonth,'',$ids,'all');
            if (!empty($exp_data)) {
                $data_ids[] = $exp_ids = $exp_data['exp_ids'];
                $exp_arr = $exp_data['exp_arr'];
                $dataArr = array_merge($dataArr, $exp_arr);
            }
            /***** End Code For Exp Payload ********** */
        }
        if($invoice_type == 'HSN') {
            /***** Start Code For HSN Summary Payload ********** */
            $hsn_data = $this->gstHSNPayload($user_id, $returnmonth,'',$ids,$type);
            if (!empty($hsn_data)) {
                $data_ids[] = $hsn_ids = $hsn_data['hsn_ids'];
                $hsn_arr = $hsn_data['hsn_arr'];
                $dataArr = array_merge($dataArr, $hsn_arr);
            }
            /***** END Code For HSN Summary Payload ********** */
        }
        if($invoice_type == 'DOCISSUE') {
            /***** Start Code For Doc Issue Payload ********** */
            $doc_data = $this->getDOCISSUEPayload($user_id, $returnmonth,'',$ids,$type);
            if (!empty($doc_data)) {
                $data_ids[] = $doc_ids = $doc_data['doc_ids'];
                $doc_arr = $doc_data['doc_arr'];
                $dataArr = array_merge($dataArr, $doc_arr);
            }
            /***** End Code For Doc Issue Payload ********** */
        }
        if($invoice_type == 'NIL') {
            /***** Start Code For NIL Payload ********** */
            $nil_data = $this->getNILPayload($user_id, $returnmonth,'',$ids,$type);
            if (!empty($nil_data)) {
                $data_ids[] = $nil_ids = $nil_data['nil_ids'];
                $nil_arr = $nil_data['nil_arr'];
                $dataArr = array_merge($dataArr, $nil_arr);
            }
            /***** End Code For NIL Payload ********** */
        }
        
        
        
        $temp_id = '';
        $x = 0;
        $update_final_string = '';
        if(!empty($data_ids)) {
            foreach ($data_ids as $key => $value) {
                if (!empty($value)) {
                    foreach ($value as $key => $val) {
                        if (!empty($val)) {
                            $update_final_string .= $val.',';
                        }
                    }
                }
            }
            $update_final_string = rtrim($update_final_string, ',');
        }

        $response['data_ids'] = $update_final_string;
        $response['data_arr'] = $dataArr;
        return $response;
    }

    public function gstDeleteItemPayload($returnmonth,$type,$data,$all='') {
        $obj_gst = new gstr();
        $user_id = $_SESSION['user_detail']['user_id'];
        $dataArr = $this->gstPayloadHeader($user_id, $returnmonth);
        $is_username_check = $obj_gst->is_username_exists($_SESSION['user_detail']['user_id']);
        if (!empty($is_username_check)) {
            $is_gross_turnover_check =(float) $obj_gst->is_gross_turnover_check($_SESSION['user_detail']['user_id']);
            $cur_gt=  (float)$obj_gst->cur_gross_turnover($_SESSION['user_detail']['user_id']);
            if (!empty($is_gross_turnover_check) && !empty($cur_gt)) {

                $deletePayload = $this->gstDeletePayload($user_id, $returnmonth,$type,$data,$all);

                if(!empty($deletePayload)) {
                    $deletePayloadArr = $deletePayload['data_arr'];
                    $data_ids = $deletePayload['data_ids'];
                    $dataArr = array_merge($dataArr, $deletePayloadArr);
                    $response = $obj_gst->returnDeleteItems($dataArr,$returnmonth,'gstr1');
                    
                    $flag = 0;
                    //$this->pr($deletePayload);
                    if ($response['error'] == 0) {
                        $inum = isset($data['inum'])?$data['inum']:'';
                        $this->setSuccess("Your Invoice : ".$inum." has been deleted from GSTN.");
                        //if (!empty($data_ids)) {
                            foreach ($data_ids as $key => $data_id) {
                                $this->query("UPDATE ".$this->getTableName('client_invoice')." SET is_gstr1_uploaded='0' WHERE invoice_id = ".$data_id."");  
                            }
                        /*} 
                        else {
                            $this->setError('file not deleted from database.');
                            return false; 
                        }*/
                         return true; 
                    }
                    if ($response['error'] == 2) {
                        $this->setSuccess($response['message']);
                        return true; 
                    }
                    else {
                        $this->setError($response['message']);
                        return false; 
                    } 
                    
                }
                else {
                    $this->setError('Sorry! Something went wrong.');
                    return false; 
                }
            } else {
                $this->setError('Sorry! Please update your gross turnover');
                return false;
            }
        }
        else {
            $this->setError('Sorry! Please update your gstin username');
            return false;
        }
        return false;
    }

    public function gstDeletePayload($user_id, $returnmonth,$type,$data,$all) {
        $response = $data_ids = $dataArr = array();
        if(!empty($data)) {
            /*echo $all;
            die;*/
            if($type == 'B2B') {
                $ctin = isset($data['ctin'])?$data['ctin']:'';
                $inum = isset($data['inum'])?$data['inum']:'';
                $idt = isset($data['idt'])?$data['idt']:'';
                $json = isset($data['json'])?$data['json']:'';
                if($all == 'all') {
                    $b2b_data = $this->gstB2BPayload($user_id, $returnmonth,'D');
                    //$this->pr($b2b_data);
                    if (!empty($b2b_data)) {
                        $dataArr = $b2b_data['b2b_arr'];
                        $data_ids = $b2b_data['b2b_ids'];
                    }
                    /*$jstr1_array = json_decode($json,true);
                    if(isset($jstr1_array['b2b'])) {
                        $i=0;
                        foreach ($jstr1_array['b2b'] as $key1 => $inv_value) {
                            if(isset($inv_value['inv'])) {
                                $ctin = isset($inv_value['ctin'])?$inv_value['ctin']:'';
                                $j=0;
                                foreach ($inv_value['inv'] as $key2 => $jstr1_value) {
                                    $pos = isset($jstr1_value['pos'])?$jstr1_value['pos']:0;
                                    $idt = isset($jstr1_value['idt'])?$jstr1_value['idt']:'';
                                    $inum = isset($jstr1_value['inum'])?$jstr1_value['inum']:'';

                                    $dataArr['b2b'][$i]['ctin'] = $ctin;
                                    $dataArr['b2b'][$i]['inv'][$j]['flag'] = 'D';
                                    $dataArr['b2b'][$i]['inv'][$j]['inum'] = $inum;
                                    $dataArr['b2b'][$i]['inv'][$j]['idt'] = $idt;
                                    $j++;
                                }
                            }
                            $i++;
                        }  
                    }*/
                    
                }
                else {
                    $dataArr['b2b'][0]['ctin'] = $ctin;
                    $dataArr['b2b'][0]['inv'][0]['flag'] = 'D';
                    $dataArr['b2b'][0]['inv'][0]['inum'] = $inum;
                    $dataArr['b2b'][0]['inv'][0]['idt'] = $idt;
                    $data_ids = $this->gstB2BDeleteQuery($user_id,$returnmonth, $ctin,$inum,$idt,$all);

                }
            }
            if($type == 'B2CL') {
                $pos = isset($data['pos'])?$data['pos']:'';
                $inum = isset($data['inum'])?$data['inum']:'';
                $idt = isset($data['idt'])?$data['idt']:'';
                $json = isset($data['json'])?$data['json']:'';
                if($all == 'all') {
                    $b2cl_data = $this->gstB2CLPayload($user_id, $returnmonth,'D');
                    if (!empty($b2cl_data)) {
                        $dataArr = $b2cl_data['b2cl_arr'];
                        $data_ids = $b2cl_data['b2cl_ids'];
                    }
                    /*$jstr1_array = json_decode($json,true);
                    if(isset($jstr1_array['b2cl'])) {
                        $i=0;
                        foreach ($jstr1_array['b2cl'] as $key1 => $inv_value) {
                            if(isset($inv_value['inv'])) {
                                $j=0;
                                foreach ($inv_value['inv'] as $key2 => $jstr1_value) {
                                    $pos = isset($inv_value['pos'])?$inv_value['pos']:0;
                                    $idt = isset($jstr1_value['idt'])?$jstr1_value['idt']:'';
                                    $inum = isset($jstr1_value['inum'])?$jstr1_value['inum']:'';
                                    
                                    $dataArr['b2cl'][$i]['pos'] = $pos;
                                    $dataArr['b2cl'][$i]['inv'][$j]['flag'] = 'D';
                                    $dataArr['b2cl'][$i]['inv'][$j]['inum'] = $inum;
                                    $dataArr['b2cl'][$i]['inv'][$j]['idt'] = $idt;
                                    $j++;
                                }
                            }
                            $i++;
                        }  
                    }*/
                    
                }
                else {
                    $dataArr['b2cl'][0]['pos'] = $pos;
                    $dataArr['b2cl'][0]['inv'][0]['flag'] = 'D';
                    $dataArr['b2cl'][0]['inv'][0]['inum'] = $inum;
                    $dataArr['b2cl'][0]['inv'][0]['idt'] = $idt;
                    $data_ids = $this->gstB2CLDeleteQuery($user_id, $returnmonth,$returnmonth,$pos,$inum,$idt,$all);
                }
                
            }
            if($type == 'CDNR') {
                $ctin = isset($data['ctin'])?$data['ctin']:'';
                $inum = isset($data['inum'])?$data['inum']:'';
                $idt = isset($data['idt'])?$data['idt']:'';
                $nt_num = isset($data['nt_num'])?$data['nt_num']:'';
                $nt_dt = isset($data['nt_dt'])?$data['nt_dt']:'';
                $json = isset($data['json'])?$data['json']:'';
                if($all == 'all') {
                    $cdnr_data = $this->gstCDNRPayload($user_id, $returnmonth,'D');
                    if (!empty($cdnr_data)) {
                        $dataArr = $cdnr_data['cdnr_arr'];
                        $data_ids = $cdnr_data['cdnr_ids'];
                    }
                    /*$jstr1_array = json_decode($json,true);
                    if(isset($jstr1_array['cdnr'])) {
                        $i=0;
                        foreach ($jstr1_array['cdnr'] as $key1 => $inv_value) {
                            $cfs = isset($inv_value['cfs'])?$inv_value['cfs']:'';
                            $nt = isset($inv_value['nt'])?$inv_value['nt']:array();
                            $ctin = isset($inv_value['ctin'])?$inv_value['ctin']:'';
                            if(isset($nt) && !empty($nt)) {
                                $j=0;
                                foreach ($nt as $key2 => $jstr1_value) {
                                   
                                    $nt_num = isset($jstr1_value['nt_num'])?$jstr1_value['nt_num']:'';
                                    $inum = isset($jstr1_value['inum'])?$jstr1_value['inum']:'';
                                    
                                    $idt = isset($jstr1_value['idt'])?$jstr1_value['idt']:'';
                                    $nt_dt = isset($jstr1_value['nt_dt'])?$jstr1_value['nt_dt']:'';

                                    $dataArr['cdnr'][$i]['ctin'] = $ctin;
                                    $dataArr['cdnr'][$i]['nt'][$j]['flag'] = 'D';
                                    $dataArr['cdnr'][$i]['nt'][$j]['nt_num'] = $nt_num;
                                    $dataArr['cdnr'][$i]['nt'][$j]['nt_dt'] = $nt_dt;
                                    $dataArr['cdnr'][$i]['nt'][$j]['inum'] = $inum;
                                    $dataArr['cdnr'][$i]['nt'][$j]['idt'] = $idt;

                                    $j++;

                                }
                            }
                            $i++;
                        }  
                    }*/
                    
                }
                else {
                    $dataArr['cdnr'][0]['ctin'] = $ctin;
                    $dataArr['cdnr'][0]['nt'][0]['flag'] = 'D';
                    $dataArr['cdnr'][0]['nt'][0]['nt_num'] = $nt_num;
                    $dataArr['cdnr'][0]['nt'][0]['nt_dt'] = $nt_dt;
                    $dataArr['cdnr'][0]['nt'][0]['inum'] = $inum;
                    $dataArr['cdnr'][0]['nt'][0]['idt'] = $idt;
                    $data_ids = $this->gstCDNRDeleteQuery($user_id, $returnmonth,$ctin,$inum,$idt,$nt_num,$nt_dt,$all);
                }
                
            }
            if($type == 'CDNUR') {
                $inum = isset($data['inum'])?$data['inum']:'';
                $idt = isset($data['idt'])?$data['idt']:'';
                $nt_num = isset($data['nt_num'])?$data['nt_num']:'';
                $nt_dt = isset($data['nt_dt'])?$data['nt_dt']:'';
                $typ = isset($data['typ'])?$data['typ']:'';
                $json = isset($data['json'])?$data['json']:'';
                if($all == 'all') {
                    $cdnur_data = $this->gstCDNURPayload($user_id, $returnmonth,'D');
                    if (!empty($cdnur_data)) {
                        $dataArr = $cdnur_data['cdnur_arr'];
                        $data_ids = $cdnur_data['cdnur_ids'];
                    }
                    /*$jstr1_array = json_decode($json,true);
                    if(isset($jstr1_array['cdnur'])) {
                        $i=0;
                        foreach ($jstr1_array['cdnur'] as $key1 => $jstr1_value) {
                   
                            $nt_num = isset($jstr1_value['nt_num'])?$jstr1_value['nt_num']:'';
                            $inum = isset($jstr1_value['inum'])?$jstr1_value['inum']:'';
                            
                            $idt = isset($jstr1_value['idt'])?$jstr1_value['idt']:'';
                            $nt_dt = isset($jstr1_value['nt_dt'])?$jstr1_value['nt_dt']:'';
                            $typ = isset($jstr1_value['typ'])?$jstr1_value['typ']:'';

                            $dataArr['cdnur'][$i]['typ'] = $typ;
                            $dataArr['cdnur'][$i]['flag'] = 'D';
                            $dataArr['cdnur'][$i]['nt_num'] = $nt_num;
                            $dataArr['cdnur'][$i]['nt_dt'] = $nt_dt;
                            $dataArr['cdnur'][$i]['inum'] = $inum;
                            $dataArr['cdnur'][$i]['idt'] = $idt;
                            $i++;

                        }
                    }*/
                    
                }
                else {

                    $dataArr['cdnur'][0]['typ'] = $typ;
                    $dataArr['cdnur'][0]['flag'] = 'D';
                    $dataArr['cdnur'][0]['nt_num'] = $nt_num;
                    $dataArr['cdnur'][0]['nt_dt'] = $nt_dt;
                    $dataArr['cdnur'][0]['inum'] = $inum;
                    $dataArr['cdnur'][0]['idt'] = $idt;
                    $data_ids = $this->gstCDNURDeleteQuery($user_id, $returnmonth,$inum,$idt,$nt_num,$nt_dt,$all);
                }
                
            }
            if($type == 'EXP') {
                
                $inum = isset($data['inum'])?$data['inum']:'';
                $idt = isset($data['idt'])?$data['idt']:'';
                $exp_typ = isset($data['exp_typ'])?$data['exp_typ']:'';
                $json = isset($data['json'])?$data['json']:'';
                if($all == 'all') {
                    $exp_data = $this->gstEXPPayload($user_id, $returnmonth,'D');
                    if (!empty($exp_data)) {
                        $dataArr = $exp_data['exp_arr'];
                        $data_ids = $exp_data['exp_ids'];
                    }
                    /*$jstr1_array = json_decode($json,true);
                    if(isset($jstr1_array['exp'])) {
                        $i=0;
                        foreach ($jstr1_array['exp'] as $key1 => $inv_value) {
                            if(isset($inv_value['inv'])) {
                                $exp_typ = isset($inv_value['exp_typ'])?$inv_value['exp_typ']:'';
                                $j=0;
                                foreach ($inv_value['inv'] as $key2 => $jstr1_value) {
                                    $idt = isset($jstr1_value['idt'])?$jstr1_value['idt']:'';
                                    $inum = isset($jstr1_value['inum'])?$jstr1_value['inum']:'';

                                    $dataArr['exp'][$i]['exp_typ'] = $exp_typ;
                                    $dataArr['exp'][$i]['inv'][$j]['flag'] = 'D';
                                    $dataArr['exp'][$i]['inv'][$j]['inum'] = $inum;
                                    $dataArr['exp'][$i]['inv'][$j]['idt'] = $idt;
                                   
                                    $j++;
                                }
                            }
                            $i++;
                        }    
                    }*/
                    
                }
                else {
                    $dataArr['exp'][0]['exp_typ'] = $exp_typ;
                    $dataArr['exp'][0]['inv'][0]['flag'] = 'D';
                    $dataArr['exp'][0]['inv'][0]['inum'] = $inum;
                    $dataArr['exp'][0]['inv'][0]['idt'] = $idt;
                    $data_ids = $this->gstEXPDeleteQuery($user_id,$returnmonth,$inum,$idt,$exp_typ,$all);
                }
                
            }
            if($type == 'HSN') {
                $hsn_sc = isset($data['hsn_sc'])?$data['hsn_sc']:'';
                $chksum = isset($data['chksum'])?$data['chksum']:'';
                
                $json = isset($data['json'])?$data['json']:'';
                if($all == 'all') {
                    $hsn_data = $this->gstHSNPayload($user_id, $returnmonth,'D');
                    if (!empty($hsn_data)) {
                        $dataArr = $hsn_data['hsn_arr'];
                        //$data_ids = $hsn_data['hsn_ids'];
                    }
                    /*$jstr1_array = json_decode($json,true);
                    $this->pr($jstr1_array);
                    if(isset($jstr1_array['hsn']['data'])) {
                        $hsn = $jstr1_array['hsn']['data'];
                        $chksum = $jstr1_array['hsn']['chksum'];
                        $i=0;
                        foreach ($hsn as $key1 => $jstr1_value) {
                            $hsn_sc = isset($jstr1_value['hsn_sc'])?$jstr1_value['hsn_sc']:'';

                            $dataArr['hsn']['flag'] = 'D';
                            $dataArr['hsn']['data'][$i]['hsn_sc'] = $hsn_sc;
                            $dataArr['hsn']['chksum'] = $chksum;
                            $i++;
                        }   
                    }*/
                    
                }
                else {
                    $dataArr['hsn']['flag'] = 'D';
                    $dataArr['hsn']['data'][0]['hsn_sc'] = $hsn_sc;
                    $dataArr['hsn']['chksum'] = $chksum;
                }
            }
            if($all == 'all') {
                if($type == 'B2CS') {
                    $b2cs_data = $this->gstB2CSPayload($user_id, $returnmonth,'D');
                    if (!empty($b2cs_data)) {
                        $dataArr = $b2cs_data['b2cs_arr'];
                        $data_ids = $b2cs_data['b2cs_ids'];
                    }
                   /* $json = isset($data['json'])?$data['json']:'';
                    $jstr1_array = json_decode($json,true);
                    if(isset($jstr1_array['b2cs'])) {
                        $i=0;
                        foreach ($jstr1_array['b2cs'] as $key1 => $jstr1_value) {
                            $rt = isset($jstr1_value['rt'])?$jstr1_value['rt']:0;
                            $pos = isset($jstr1_value['pos'])?$jstr1_value['pos']:0;
                            $typ = isset($jstr1_value['typ'])?$jstr1_value['typ']:0;
                            $sply_ty = isset($jstr1_value['sply_ty'])?$jstr1_value['sply_ty']:0;
                            $chksum = isset($jstr1_value['chksum'])?$jstr1_value['chksum']:'';

                            $dataArr['b2cs'][$i]['flag'] = 'D';
                            $dataArr['b2cs'][$i]['rt'] = $rt;
                            $dataArr['b2cs'][$i]['sply_ty'] = $sply_ty;
                            $dataArr['b2cs'][$i]['typ'] = $typ;
                            $dataArr['b2cs'][$i]['pos'] = $pos;
                            $dataArr['b2cs'][$i]['chksum'] = $chksum;
                            $i++;
                        }   
                    }
                    $data_ids = $this->gstB2CSDeleteQuery($user_id, $returnmonth);*/
                }
                
                if($type == 'AT') {
                    $at_data = $this->gstATPayload($user_id, $returnmonth,'D');
                    if (!empty($at_data)) {
                        $dataArr = $at_data['at_arr'];
                        $data_ids = $at_data['at_ids'];
                    }
                    /*$json = isset($data['json'])?$data['json']:'';
                    $jstr1_array = json_decode($json,true);
                    if(isset($jstr1_array['at'])) {
                        $i=0;
                        foreach ($jstr1_array['at'] as $key1 => $jstr1_value) {
                            $pos = isset($jstr1_value['pos'])?$jstr1_value['pos']:0;
                            $chksum = isset($jstr1_value['chksum'])?$jstr1_value['chksum']:'';
                            $sply_ty = isset($jstr1_value['sply_ty'])?$jstr1_value['sply_ty']:'';
                            
                            $dataArr['at'][$i]['sply_ty'] = $sply_ty;
                            $dataArr['at'][$i]['pos'] = $pos;
                            $dataArr['at'][$i]['flag'] = 'D';
                            $dataArr['at'][$i]['chksum'] = $chksum; 
                            $i++;
                        }   
                    }
                    $data_ids = $this->gstAtDeleteQuery($user_id, $returnmonth);*/
                }
                if($type == 'TXPD') {
                    $json = isset($data['json'])?$data['json']:'';
                    $jstr1_array = json_decode($json,true);
                    if(isset($jstr1_array['txpd'])) {
                        $i=0;
                        foreach ($jstr1_array['txpd'] as $key1 => $jstr1_value) {
                            $pos = isset($jstr1_value['pos'])?$jstr1_value['pos']:0;
                            $sply_ty = isset($jstr1_value['sply_ty'])?$jstr1_value['sply_ty']:'';
                            $chksum = isset($jstr1_value['chksum'])?$jstr1_value['chksum']:'';

                            $dataArr['txpd'][$i]['flag'] = 'D';
                            $dataArr['txpd'][$i]['pos'] = $pos;
                            $dataArr['txpd'][$i]['sply_ty'] = $sply_ty;
                            $dataArr['txpd'][$i]['chksum'] = $chksum;

                            $i++;
                        }   
                    }
                } 
            }
            
        }
        $response['data_ids'] = $data_ids;
        $response['data_arr'] = $dataArr;
        /*$this->pr($response);
        die;*/
        return $response;
    }

    public function gstB2BDeleteQuery($user_id,$returnmonth,$ctin='',$inum='',$idt='',$all='') {
        if(!empty($all)) {
            $datas = $this->getB2BInvoices($user_id, $returnmonth,'1');
        }
        else {
            $idt=  date('Y-m-d', strtotime($idt));
            $query =  "select a.invoice_id from ".$this->getTableName('client_invoice')." a  where a.is_gstr1_uploaded='1' and a.status='1' and a.added_by='".$user_id."'  and a.invoice_date like '%".$idt."%'  and a.billing_gstin_number!='' and a.invoice_type='taxinvoice' and a.invoice_nature='salesinvoice' and a.is_canceled='0' and a.is_deleted='0' and a.reference_number ='".$inum."' and a.billing_gstin_number='".$ctin."'  ";
            $datas = $this->get_results($query);
        }
        $ids =  array();
        if(!empty($datas)) {
            foreach ($datas as $key => $data) {
                $ids[] = $data->invoice_id;
            }
            
        }
        return $ids; 
    }

    public function gstB2CLDeleteQuery($user_id, $returnmonth,$pos='',$inum='',$idt='',$all='') {
        if(!empty($all)) {
            $datas = $this->getB2CLInvoices($user_id, $returnmonth,'1');
        }
        else {
            $idt=  date('Y-m-d', strtotime($idt));
            $query =  "select a.invoice_id from ".$this->getTableName('client_invoice')." a inner join ".$this->getTableName('client_invoice_item')." b on a.invoice_id=b.invoice_id  where  a.is_gstr1_uploaded='1' and a.status='1' and a.added_by='".$user_id."' and a.invoice_date like '%".$idt."%' and a.billing_gstin_number='' and a.invoice_total_value>'250000' and a.supply_place!=a.company_state and a.invoice_type='taxinvoice' and a.invoice_nature='salesinvoice' and a.is_canceled='0' and a.is_deleted='0' and a.reference_number ='".$inum."' and a.supply_place='".$pos."'  group by a.reference_number, b.consolidate_rate order by a.supply_place";
            $datas = $this->get_results($query);
        }
        //$this->pr($data);
        $ids =  array();
        if(!empty($datas)) {
            foreach ($datas as $key => $data) {
                $ids[] = $data->invoice_id;
            }
            
        }
        return $ids; 
    }

    public function gstCDNRDeleteQuery($user_id,$returnmonth,$ctin='',$inum='',$idt='',$nt_num='',$nt_dt='',$all='') {
        if(!empty($all)) {
            $datas = $this->getCDNRInvoices($user_id, $returnmonth,'1');
        }
        else {
            $idt=  date('Y-m-d', strtotime($idt));
            $nt_dt=  date('Y-m-d', strtotime($nt_dt));
            $query =  "select a.invoice_id from ".$this->getTableName('client_invoice')." a inner join ".$this->getTableName('client_invoice_item')." b on a.invoice_id=b.invoice_id inner join ".$this->getTableName('client_invoice')." c  on a.corresponding_document_number=c.invoice_id where a.is_gstr1_uploaded='1' and a.status='1' and a.added_by='".$user_id."'  and a.corresponding_document_date like '%".$idt."%'  and a.billing_gstin_number!='' and (a.invoice_type='creditnote' or a.invoice_type='debitnote' or a.invoice_type='refundvoucherinvoice') and a.is_canceled='0' and a.is_deleted='0' and c.reference_number ='".$inum."' and a.billing_gstin_number='".$ctin."' and a.reference_number ='".$nt_num."' and a.invoice_date like '%".$nt_dt."%'  ";
            $datas = $this->get_results($query);
        }
        $ids =  array();
        if(!empty($datas)) {
            foreach ($datas as $key => $data) {
                $ids[] = $data->invoice_id;
            }
            
        }
        return $ids;
    }
    
    public function gstCDNURDeleteQuery($user_id, $returnmonth,$inum='',$idt='',$nt_num='',$nt_dt='',$all='') {
        if(!empty($all)) {
            $datas = $this->getCDNURInvoices($user_id, $returnmonth,'1');
        }
        else {
            $idt=  date('Y-m-d', strtotime($idt));
            $nt_dt=  date('Y-m-d', strtotime($nt_dt));
            $query =  "select a.invoice_id from ".$this->getTableName('client_invoice')." a inner join ".$this->getTableName('client_invoice_item')." b on a.invoice_id=b.invoice_id inner join ".$this->getTableName('client_invoice')." c on a.corresponding_document_number=c.invoice_id where a.status='1' and a.added_by='".$user_id."' and a.is_gstr1_uploaded='1' and a.invoice_date like '%".$idt."%' and a.supply_place!=a.company_state and a.invoice_corresponding_type='taxinvoice' and a.billing_gstin_number='' and a.invoice_total_value >'250000'  and (a.invoice_type='creditnote' or a.invoice_type='debitnote' or a.invoice_type='refundvoucherinvoice' ) and (c.invoice_type='exportinvoice' or c.invoice_type='sezunitinvoice' or c.invoice_type='deemedexportinvoice' or c.invoice_type='taxinvoice') and a.is_canceled='0' and a.is_deleted='0' and c.reference_number ='".$inum."' and a.reference_number ='".$nt_num."' and a.invoice_date like '%".$nt_dt."%' group by a.reference_number, b.consolidate_rate order by a.supply_place";
            $datas = $this->get_results($query);
        }
        $ids =  array();
        if(!empty($datas)) {
            foreach ($datas as $key => $data) {
                $ids[] = $data->invoice_id;
            }
            
        }
        return $ids;
    }

    public function gstEXPDeleteQuery($user_id,$returnmonth,$inum='',$idt='',$exp_typ='',$all='') {
        if(!empty($all)) {
            $datas = $this->getEXPInvoices($user_id, $returnmonth,'1');
        }
        else {
           $idt=  date('Y-m-d', strtotime($idt));
            if($exp_typ == 'WPAY') {
                $exp_typ = 'withpayment';
            }
            else {
                $exp_typ = 'withoutpayment';
            }
            $query =  "select a.invoice_id from ".$this->getTableName('client_invoice')." a where a.is_gstr1_uploaded='1' and a.status='1' and a.added_by='".$user_id."' and a.invoice_date like '%".$idt."%' and (a.invoice_type='exportinvoice' or a.invoice_type='sezunitinvoice' or a.invoice_type='deemedexportinvoice') and a.invoice_nature='salesinvoice' and a.is_canceled='0' and a.is_deleted='0' and a.export_bill_number !='' and a.export_bill_date != '' and a.export_bill_port_code != '' and a.reference_number ='".$inum."' and a.export_supply_meant ='".$exp_typ."'";
            $datas = $this->get_results($query); 
        }
        $ids =  array();
        if(!empty($datas)) {
            foreach ($datas as $key => $data) {
                $ids[] = $data->invoice_id;
            }
            
        }
        return $ids;
    }

    public function gstAtDeleteQuery($user_id,$returnmonth) {
        $datas = $this->getATInvoices($user_id, $returnmonth,'1');
        $ids =  array();
       
        if(!empty($datas)) {
            foreach ($datas as $key => $data) {
                $ids[] = $data->invoice_id;
            }
            
        }
        return $ids; 
    }

    public function gstB2CSDeleteQuery($user_id,$returnmonth) {
        $datas = $this->getB2CSInvoices($user_id, $returnmonth,'1');
        $ids =  array();
       
        if(!empty($datas)) {
            foreach ($datas as $key => $data) {
                $ids[] = $data->invoice_id;
            }
            
        }
        return $ids; 
    }

    public function gstB2BPayload($user_id, $returnmonth,$flag='',$ids='',$type='') {
        $dataArr = $response = $b2b_array = $b2b_ids = array();
        
        if(!empty($flag)) {
           $dataInvB2B = $this->getB2BInvoices($user_id, $returnmonth,'1');
        }
        else {
           $dataInvB2B = $this->getB2BInvoices($user_id, $returnmonth,$type,$ids); 
        }
        if (isset($dataInvB2B) && !empty($dataInvB2B)) {

            $x = 0;
            $y = 0;
            $z = 0;
            $a = 1;
            $temp_number = '';
            $ctin = '';
            foreach ($dataInvB2B as $dataIn) {
                if ($ctin != '' && $ctin != $dataIn->billing_gstin_number) {
                    $x++;
                }
                if ($temp_number != '' && $temp_number != $dataIn->reference_number) {
                    $z = 0;
                    $y++;
					$a=1;
                }
                $dataArr['b2b'][$x]['ctin'] = $dataIn->billing_gstin_number;
                $dataArr['b2b'][$x]['inv'][$y]['inum'] = $dataIn->reference_number;
                $dataArr['b2b'][$x]['inv'][$y]['idt'] = date('d-m-Y', strtotime($dataIn->invoice_date));
                if(!empty($flag)) {
                   $dataArr['b2b'][$x]['inv'][$y]['flag'] = $flag; 
                }
                $dataArr['b2b'][$x]['inv'][$y]['val'] = (float) $dataIn->invoice_total_value;
                $dataArr['b2b'][$x]['inv'][$y]['pos'] = strlen($dataIn->supply_place) == '1' ? '0' . $dataIn->supply_place : $dataIn->supply_place;
                $in_type = '';
                if ($dataIn->invoice_type == 'taxinvoice' || $dataIn->invoice_type =='billofsupplyinvoice') {
                    $in_type = 'R';

                    if ($dataIn->company_state != $dataIn->supply_place ) {
                        $dataArr['b2b'][$x]['inv'][$y]['itms'][$z]['itm_det']['iamt'] = (float) $dataIn->igst_amount;
                    } else {
                        $dataArr['b2b'][$x]['inv'][$y]['itms'][$z]['itm_det']['samt'] = (float) $dataIn->sgst_amount;
                        $dataArr['b2b'][$x]['inv'][$y]['itms'][$z]['itm_det']['camt'] = (float) $dataIn->cgst_amount;
                    }
                } 
                else if ($dataIn->invoice_type == 'sezunitinvoice') {
                    if($dataIn->export_supply_meant=='withpayment')
                    {
                            $in_type = 'SEWP';
                    }
                    else
                    {
                            $in_type = 'SEWOP';
                    }
                    $dataArr['b2b'][$x]['inv'][$y]['itms'][$z]['itm_det']['iamt'] = (float) $dataIn->igst_amount;

                } else if ($dataIn->invoice_type == 'deemedexportinvoice') {
                    $in_type = 'DE';

                    $dataArr['b2b'][$x]['inv'][$y]['itms'][$z]['itm_det']['iamt'] = (float) $dataIn->igst_amount;
                }
                $rever_charge = ($dataIn->supply_type == 'reversecharge') ? 'Y' : 'N';
                /*if($dataIn->supply_type == 'tcs') {
                    $dataArr['b2b'][$x]['inv'][$y]['etin'] = $dataIn->ecommerce_gstin_number;
                }*/
                $dataArr['b2b'][$x]['inv'][$y]['inv_typ'] = $in_type;
                $dataArr['b2b'][$x]['inv'][$y]['rchrg'] = $rever_charge;
                $dataArr['b2b'][$x]['inv'][$y]['itms'][$z]['num'] = (int) $a;
                $rt = ($dataIn->company_state == $dataIn->supply_place) ? ($dataIn->sgst_rate + $dataIn->cgst_rate) : $dataIn->igst_rate;
                $dataArr['b2b'][$x]['inv'][$y]['itms'][$z]['itm_det']['rt'] = (float) $rt;
                $dataArr['b2b'][$x]['inv'][$y]['itms'][$z]['itm_det']['txval'] = (float) $dataIn->taxable_subtotal;

                
                $dataArr['b2b'][$x]['inv'][$y]['itms'][$z]['itm_det']['csamt'] = (float) $dataIn->cess_amount;
                

                $z++;
                $temp_number = $dataIn->reference_number;
                $a++;
                $b2b_array[] = (array) $dataIn;
            }
            if (!empty($b2b_array)) {
                $b2b_ids = array_unique(array_column($b2b_array, 'invoice_id'));
            }
        }
        $response['b2b_ids'] = $b2b_ids;
        $response['b2b_arr'] = $dataArr;
        //$this->pr($dataArr);die;
        return $response;
    }

    public function gstB2CLPayload($user_id, $returnmonth,$flag='',$ids='',$type='') {
        $dataArr = $response = $b2cl_array = $b2cl_ids = array();

        $dataInvB2CL = $this->getB2CLInvoices($user_id, $returnmonth,$type,$ids);
        if (isset($dataInvB2CL) && !empty($dataInvB2CL)) {

            $x = 0;
            $y = 0;
            $z = 0;
            $a = 1;
            $temp_number = '';
            $ctin = '';
            foreach ($dataInvB2CL as $dataIn) {

                if ($temp_number != '' && $temp_number != $dataIn->reference_number) {
                    $z = 0;
                    $y++;
                }
                if ($ctin != '' && $ctin != $dataIn->supply_place) {
                    $x++;
                    $y = 0;
                }
                $dataArr['b2cl'][$x]['pos'] = strlen($dataIn->supply_place) == '1' ? '0' . $dataIn->supply_place : $dataIn->supply_place;
                $dataArr['b2cl'][$x]['inv'][$y]['inum'] = $dataIn->reference_number;
                $dataArr['b2cl'][$x]['inv'][$y]['idt'] = date('d-m-Y', strtotime($dataIn->invoice_date));
                $dataArr['b2cl'][$x]['inv'][$y]['val'] = (float) $dataIn->invoice_total_value;

                $rever_charge = ($dataIn->supply_type == 'reversecharge') ? 'Y' : 'N';

                $dataArr['b2cl'][$x]['inv'][$y]['itms'][$z]['num'] = (int) $a;
                $rt = ($dataIn->company_state == $dataIn->supply_place) ? ($dataIn->sgst_rate + $dataIn->cgst_rate) : $dataIn->igst_rate;
                $dataArr['b2cl'][$x]['inv'][$y]['itms'][$z]['itm_det']['rt'] = (float) $rt;
                $dataArr['b2cl'][$x]['inv'][$y]['itms'][$z]['itm_det']['txval'] = (float) $dataIn->taxable_subtotal;
                if ($dataIn->company_state != $dataIn->supply_place) {
                    $dataArr['b2cl'][$x]['inv'][$y]['itms'][$z]['itm_det']['iamt'] = (float) $dataIn->igst_amount;
                } else {
                    $dataArr['b2cl'][$x]['inv'][$y]['itms'][$z]['itm_det']['samt'] = (float) $dataIn->sgst_amount;
                    $dataArr['b2cl'][$x]['inv'][$y]['itms'][$z]['itm_det']['camt'] = (float) $dataIn->cgst_amount;
                }
                $dataArr['b2cl'][$x]['inv'][$y]['itms'][$z]['itm_det']['csamt'] = (float) $dataIn->cess_amount;
                $z++;
                $ctin = $dataIn->supply_place;
                $temp_number = $dataIn->reference_number;
                $a++;
                $b2cl_array[] = (array) $dataIn;
            }
            if (!empty($b2cl_array)) {
                $b2cl_ids = array_unique(array_column($b2cl_array, 'invoice_id'));
            }
        }
        $response['b2cl_ids'] = $b2cl_ids;
        $response['b2cl_arr'] = $dataArr;
        //$this->pr($dataArr);die;
        return $response;
    }

    public function gstB2CSPayload($user_id, $returnmonth,$flag='',$ids='',$type='') {
        $dataArr = $response = $b2cs_array = $b2cs_ids = array();
        $dataInvB2CS = $this->getB2CSInvoices($user_id, $returnmonth,$type,$ids);
        if (isset($dataInvB2CS) && !empty($dataInvB2CS)) {

            $x = 0;
            $y = 0;
            $z = 0;
            $a = 1;
            $temp_number = '';
            $ctin = '';
            foreach ($dataInvB2CS as $dataIn) {
                $rt = ($dataIn->company_state == $dataIn->supply_place) ? ($dataIn->sgst_rate + $dataIn->cgst_rate) : $dataIn->igst_rate;
                if ($ctin != '' && $ctin != $dataIn->billing_gstin_number) {
                    $x++;
                }
                if ($temp_number != '' && $temp_number != $dataIn->reference_number) {
                    $z = 0;
                    $y++;
                }


                if ($dataIn->company_state != $dataIn->supply_place) {
                    $dataArr['b2cs'][$x]['sply_ty'] = 'INTER';
                } else {
                    $dataArr['b2cs'][$x]['sply_ty'] = 'INTRA';
                }

                $dataArr['b2cs'][$x]['rt'] = (float) $rt;

                if($dataIn->supply_type == 'tcs') {
                    $dataArr['b2cs'][$x]['typ'] = 'E';
                    $dataArr['b2cs'][$x]['etin'] = $dataIn->ecommerce_gstin_number;
                }
                else {
                    $dataArr['b2cs'][$x]['typ'] = 'OE';
                }
                $dataArr['b2cs'][$x]['pos'] = strlen($dataIn->supply_place) == '1' ? '0' . $dataIn->supply_place : $dataIn->supply_place;

                $dataArr['b2cs'][$x]['txval'] = (float) $dataIn->taxable_subtotal;
                if ($dataIn->company_state != $dataIn->supply_place) {
                    $dataArr['b2cs'][$x]['iamt'] = (float) $dataIn->igst_amount;
                } else {
                    $dataArr['b2cs'][$x]['samt'] = (float) $dataIn->sgst_amount;
                    $dataArr['b2cs'][$x]['camt'] = (float) $dataIn->cgst_amount;
                }
                $dataArr['b2cs'][$x]['csamt'] = (float) $dataIn->cess_amount;
                $x++;
                $z++;
                $temp_number = $dataIn->reference_number;
                $a++;
                $b2cs_array[] = (array) $dataIn;
            }
            if (!empty($b2cs_array)) {
                $b2cs_ids = array_unique(array_column($b2cs_array, 'invoice_id'));
            }
        }
        $response['b2cs_ids'] = $b2cs_ids;
        $response['b2cs_arr'] = $dataArr;
        return $response;
    }

    public function gstCDNRPayload($user_id, $returnmonth,$flag='',$ids='',$type='') {
        $dataArr = $response = $cdnr_array = $cdnr_ids = array();
        $dataInvCDNR = $this->getCDNRInvoices($user_id, $returnmonth,$type,$ids);
        //$this->pr($dataInvCDNR);
        if (isset($dataInvCDNR) && !empty($dataInvCDNR)) {

            $x = 0;
            $y = 0;
            $z = 0;
            $a = 1;
            $temp_number = '';
            $ctin = '';
            foreach ($dataInvCDNR as $dataIn) {
                if ($ctin != '' && $ctin != $dataIn->billing_gstin_number) {
                    $x++;
                }
                if ($temp_number != '' && $temp_number != $dataIn->reference_number) {
                    $z = 0;
                    $y++;
                }
                $dataArr['cdnr'][$x]['ctin'] = $dataIn->billing_gstin_number;
                $nt_type = '';
                if ($dataIn->invoice_type == 'creditnote') {
                    $nt_type = 'C';
                }
                elseif ($dataIn->invoice_type == 'refundvoucherinvoice') {
                    $nt_type = 'R';
                } 
                else {
                    $nt_type = 'D';
                }
                $dataArr['cdnr'][$x]['nt'][$y]['ntty'] = $nt_type;
                $dataArr['cdnr'][$x]['nt'][$y]['nt_num'] = $dataIn->reference_number;
                $dataArr['cdnr'][$x]['nt'][$y]['nt_dt'] = date('d-m-Y', strtotime($dataIn->invoice_date));
                $dataArr['cdnr'][$x]['nt'][$y]['p_gst'] = "N";
                $dataArr['cdnr'][$x]['nt'][$y]['rsn'] = $dataIn->reason_issuing_document;//"02-Post Sale Discount";
                $dataArr['cdnr'][$x]['nt'][$y]['inum'] = $dataIn->corresponding_document_number;
                $dataArr['cdnr'][$x]['nt'][$y]['idt'] = date('d-m-Y', strtotime($dataIn->corresponding_document_date));
                $dataArr['cdnr'][$x]['nt'][$y]['val'] = (float) $dataIn->invoice_total_value;
                $dataArr['cdnr'][$x]['nt'][$y]['itms'][$z]['num'] = (int) $a;
                $rt = ($dataIn->company_state == $dataIn->supply_place) ? ($dataIn->sgst_rate + $dataIn->cgst_rate) : $dataIn->igst_rate;
                $dataArr['cdnr'][$x]['nt'][$y]['itms'][$z]['itm_det']['rt'] = (float) $rt;
                $dataArr['cdnr'][$x]['nt'][$y]['itms'][$z]['itm_det']['txval'] = (float) $dataIn->taxable_subtotal;
                if ($dataIn->company_state != $dataIn->supply_place) {
                    $dataArr['cdnr'][$x]['nt'][$y]['itms'][$z]['itm_det']['iamt'] = (float) $dataIn->igst_amount;
                } else {
                    $dataArr['cdnr'][$x]['nt'][$y]['itms'][$z]['itm_det']['samt'] = (float) $dataIn->sgst_amount;
                    $dataArr['cdnr'][$x]['nt'][$y]['itms'][$z]['itm_det']['camt'] = (float) $dataIn->cgst_amount;
                }
                $dataArr['cdnr'][$x]['nt'][$y]['itms'][$z]['itm_det']['csamt'] = (float) $dataIn->cess_amount;
                $z++;
                $temp_number = $dataIn->reference_number;
                $a++;
                $cdnr_array[] = (array) $dataIn;
            }
            if (!empty($cdnr_array)) {
                $cdnr_ids = array_unique(array_column($cdnr_array, 'invoice_id'));
            }
        }
        
        $response['cdnr_ids'] = $cdnr_ids;
        $response['cdnr_arr'] = $dataArr;
        return $response;
    }

    public function gstCDNURPayload($user_id, $returnmonth,$flag='',$ids='',$type='') {
        $dataArr = $response = $cdnur_array = $cdnur_ids = array();
        $dataInvCDNUR = $this->getCDNURInvoices($user_id, $returnmonth,$type,$ids);
       // $this->pr($dataInvCDNUR);
        if (isset($dataInvCDNUR) && !empty($dataInvCDNUR)) {

            $x = 0;
            $y = 0;
            $y = 0;
            $a = 1;
            $temp_number = '';
            $ctin = '';
            foreach ($dataInvCDNUR as $dataIn) {
                
                if ($temp_number != '' && $temp_number != $dataIn->reference_number) {
                    $y = 0;
                    $x++;
                }
                $nt_type = '';
                if ($dataIn->invoice_type == 'creditnote') {
                    $nt_type = 'C';
                } 
                elseif ($dataIn->invoice_type == 'refundvoucherinvoice') {
                    $nt_type = 'R';
                }
                else {
                    $nt_type = 'D';
                }
                $type = '';
                if ($dataIn->original_type == 'taxinvoice') {
                    $type = "B2CL";
                } 
                else {
                    if ($dataIn->export_supply_meant == 'withpayment') {
                        $type = "EXPWP";
                    }
                    else {
                        $type = "EXPWOP";
                    }
                }
                $dataArr['cdnur'][$x]['typ'] = $type;
                $dataArr['cdnur'][$x]['ntty'] = $nt_type;
                $dataArr['cdnur'][$x]['nt_num'] = $dataIn->reference_number;
                $dataArr['cdnur'][$x]['nt_dt'] = date('d-m-Y', strtotime($dataIn->invoice_date));
                $dataArr['cdnur'][$x]['p_gst'] = "N";
                $dataArr['cdnur'][$x]['rsn'] = $dataIn->reason_issuing_document;//"02-Post Sale Discount";
                $dataArr['cdnur'][$x]['inum'] = $dataIn->corresponding_document_number;
                $dataArr['cdnur'][$x]['idt'] = date('d-m-Y', strtotime($dataIn->corresponding_document_date));
                $dataArr['cdnur'][$x]['val'] = (float) $dataIn->invoice_total_value;
                $dataArr['cdnur'][$x]['itms'][$y]['num'] = (int) $a;
                $rt = ($dataIn->company_state == $dataIn->supply_place) ? ($dataIn->sgst_rate + $dataIn->cgst_rate) : $dataIn->igst_rate;
                $dataArr['cdnur'][$x]['itms'][$y]['itm_det']['rt'] = (float) $rt;
                $dataArr['cdnur'][$x]['itms'][$y]['itm_det']['txval'] = (float) $dataIn->taxable_subtotal;
                $dataArr['cdnur'][$x]['itms'][$y]['itm_det']['iamt'] = (float) $dataIn->igst_amount;
                $dataArr['cdnur'][$x]['itms'][$y]['itm_det']['csamt'] = (float) $dataIn->cess_amount;
                $y++;
                $temp_number = $dataIn->reference_number;
                $a++;
                $cdnur_array[] = (array) $dataIn;
            }
            if (!empty($cdnur_array)) {
                $cdnur_ids = array_unique(array_column($cdnur_array, 'invoice_id'));
            }
        }
        /*$this->pr($dataArr);
        die;*/
        $response['cdnur_ids'] = $cdnur_ids;
        $response['cdnur_arr'] = $dataArr;
        return $response;
    }

    public function gstHSNPayload($user_id, $returnmonth,$flag='',$ids='',$type='') {
        $dataArr = $response = $hsn_array = $hsn_ids = array();
        $dataInvHsn = $this->getReturnUploadSummary($user_id, $returnmonth,'gstr1hsn');
        if (isset($dataInvHsn) && !empty($dataInvHsn)) {
            $y = 0;
            $a = 1;
            $json = $dataInvHsn[0]->return_data;
            if(!empty($json)) {
                $dataInvHsn = json_decode(base64_decode($json));
                foreach ($dataInvHsn as $dataIn) {

                    $dataArr['hsn']['data'][$y]['num'] = (int) $a;
                    $dataArr['hsn']['data'][$y]['hsn_sc'] = $dataIn->hsn;
                    $dataArr['hsn']['data'][$y]['desc'] = substr($dataIn->description,0,30);
                    $dataArr['hsn']['data'][$y]['uqc'] = $dataIn->unit;
                    $dataArr['hsn']['data'][$y]['qty'] = (float) $dataIn->qty;
                    $dataArr['hsn']['data'][$y]['val'] = (float) $dataIn->invoice_total_value;
                    $dataArr['hsn']['data'][$y]['txval'] = (float) $dataIn->taxable_subtotal;
                    $dataArr['hsn']['data'][$y]['iamt'] = (float) $dataIn->igst;
                    $dataArr['hsn']['data'][$y]['samt'] = (float) $dataIn->sgst;
                    $dataArr['hsn']['data'][$y]['camt'] = (float) $dataIn->cgst;
                    $dataArr['hsn']['data'][$y]['csamt'] = (float) $dataIn->cess;
                    $a++;
                    $y++;
                    $hsn_array[] = (array) $dataIn;
                }
            }
        }
        //$this->pr($dataInvHsn);
        $response['hsn_ids'] = $hsn_ids;
        $response['hsn_arr'] = $dataArr;
        return $response;
    }

    public function gstATPayload($user_id, $returnmonth,$flag='',$ids='',$type='') {
        $dataArr = $response = $at_array = $at_ids = array();
        $dataInvAt = $this->getATInvoices($user_id, $returnmonth,$type,$ids);
        //$this->pr($dataInvAt);
        if (isset($dataInvAt) && !empty($dataInvAt)) {
            $z = 0;
            $y = 0;
            $a = 1;
            $at_pos = '';
            $at_rate = '';
            foreach ($dataInvAt as $dataIn) {
                $rt = $dataIn->igst_rate;
                //$rt = ($dataIn->company_state==$dataIn->supply_place) ? ($dataIn->sgst_rate+ $dataIn->cgst_rate) :  $dataIn->igst_rate;
                if ($at_pos != '' && $at_pos != $dataIn->supply_place) {
                    $y++;
                    $z = 0;
                }

                $dataArr['at'][$y]['pos'] = (strlen($dataIn->supply_place) == '1') ? '0' . $dataIn->supply_place : $dataIn->supply_place;
                if ($dataIn->company_state != $dataIn->supply_place) {
                    $dataArr['at'][$y]['sply_ty'] = 'INTER';
                } else {
                    $dataArr['at'][$y]['sply_ty'] = 'INTRA';
                }

                if ($dataIn->company_state != $dataIn->supply_place) {
                    $dataArr['at'][$y]['itms'][$z]['iamt'] = (float) $dataIn->igst_amount;
                } else {
                   $dataArr['at'][$y]['itms'][$z]['iamt'] = (float) $dataIn->igst_amount;
                    $dataArr['at'][$y]['itms'][$z]['samt'] = (float) $dataIn->sgst_amount;
                    $dataArr['at'][$y]['itms'][$z]['camt'] = (float) $dataIn->cgst_amount;
                }

                $dataArr['at'][$y]['itms'][$z]['rt'] = (float) $rt;
                $dataArr['at'][$y]['itms'][$z]['ad_amt'] = (float) $dataIn->taxable_subtotal;
                
                $dataArr['at'][$y]['itms'][$z]['csamt'] = (float) $dataIn->cess_amount;
                $at_pos = $dataIn->supply_place;
                $at_rate = $rt;
                $z++;
                $at_array[] = (array) $dataIn;
            }
            if (!empty($at_array)) {
                $at_ids = array_unique(array_column($at_array, 'invoice_id'));
            }
        }
        $response['at_ids'] = $at_ids;
        $response['at_arr'] = $dataArr;
        return $response;
    }


    public function getNILPayload($user_id, $returnmonth,$flag='',$ids='',$type='') {
        $dataArr = $response = $nil_array = $nil_ids = $nill_inv_array_b2b = array();
        $dataInvNil = $this->getReturnUploadSummary($user_id, $returnmonth,'gstr1nil');
        //$this->pr($dataInvNil);
        if (!empty($dataInvNil)) {
            $json = $dataInvNil[0]->return_data;
            if(!empty($json)) {
                $dataInvNil = json_decode(base64_decode($json));
                $i = $nil_amt = $ngsup_amt = $expt_amt = 0;
                foreach ($dataInvNil as $key => $dataIn) {
                    $nil_amt = $ngsup_amt = $expt_amt = 0;
                    if(isset($dataIn->nil_amt) && !empty($dataIn->nil_amt)) {
                        $nil_amt = $dataIn->nil_amt;
                    }
                    if(isset($dataIn->ngsup_amt) && !empty($dataIn->ngsup_amt)) {
                        $ngsup_amt = $dataIn->ngsup_amt;
                    }
                    if(isset($dataIn->expt_amt) && !empty($dataIn->expt_amt)) {
                        $expt_amt = $dataIn->expt_amt;
                    }
                    $nill_inv_array_b2b[$i]['sply_ty'] = $dataIn->sply_ty;
                    $nill_inv_array_b2b[$i]['nil_amt'] = (float)$nil_amt;
                    $nill_inv_array_b2b[$i]['ngsup_amt'] = (float)$ngsup_amt;
                    $nill_inv_array_b2b[$i]['expt_amt'] = (float)$expt_amt;
                    $i++;
                }
                $dataArr["nill"][0]["inv"] = $nill_inv_array_b2b;
                
            }
        }
        $response['nil_ids'] = $nil_ids;
        $response['nil_arr'] = $dataArr;
        //$this->pr($response);die;
        return $response;
    }

    public function getEXPPayload($user_id, $returnmonth,$flag='',$ids='',$type='') {
        $dataArr = $response = $exp_ids = $exp_array = $dataArr1 = $dataArr2 = array();
        $dataInvExp = $this->getEXPInvoices($user_id, $returnmonth,$type,$ids);
        if (isset($dataInvExp) && !empty($dataInvExp)) {
            $y = 0;
            $a = 1;
            $mydata = array();
            foreach ($dataInvExp as $key => $value) {
                $mydata[$value->export_supply_meant][] = $value;
            }
            if (!empty($mydata)) {
                if (isset($mydata['withpayment']) && !empty($mydata['withpayment'])) {
                    $x = 0;
                    $y = 0;
                    $z = 0;
                    $temp_number = '';
                    foreach ($mydata['withpayment'] as $dataIn) {
                        if ($temp_number != '' && $temp_number != $dataIn->reference_number) {
                            $z = 0;
                            $y++;
                        }
                        $rt = ($dataIn->company_state == $dataIn->supply_place) ? ($dataIn->sgst_rate + $dataIn->cgst_rate) : $dataIn->igst_rate;
                        
                        $dataArr1['exp_typ'] = ($dataIn->export_supply_meant=='withpayment') ? "WPAY" : 'WOPAY';
                        $dataArr1['inv'][$y]['inum'] = $dataIn->reference_number;
                        $dataArr1['inv'][$y]['idt'] = date('d-m-Y', strtotime($dataIn->invoice_date));
                        $dataArr1['inv'][$y]['val'] = (float) $dataIn->invoice_total_value;
                        if(!empty($dataIn->export_bill_port_code)) {
                            $dataArr1['inv'][$y]['sbpcode'] = $dataIn->export_bill_port_code;
                        }
                        if(!empty($dataIn->export_bill_number)) {
                            $dataArr1['inv'][$y]['sbnum'] = (int)$dataIn->export_bill_number;
                        }
                        if(!empty($dataIn->export_bill_date)) {
                            $dataArr1['inv'][$y]['sbdt'] = $dataIn->export_bill_date > 0 ? date('d-m-Y', strtotime($dataIn->export_bill_date)) : '';
                        }
                        
                        $dataArr1['inv'][$y]['itms'][$z]['txval'] = (float) $dataIn->taxable_subtotal;
                        $dataArr1['inv'][$y]['itms'][$z]['rt'] = (float) $rt;
                        $dataArr1['inv'][$y]['itms'][$z]['iamt'] = (float) $dataIn->igst_amount;
                        $temp_number = $dataIn->reference_number;
                        $z++;
                        $exp_array[] = (array) $dataIn;
                    }
                }
                if (isset($mydata['withoutpayment']) && !empty($mydata['withoutpayment'])) {
                    $x = 0;
                    $y = 0;
                    $z = 0;
                    $temp_number = '';
                    foreach ($mydata['withoutpayment'] as $dataIn) {
                        $rt = ($dataIn->company_state == $dataIn->supply_place) ? ($dataIn->sgst_rate + $dataIn->cgst_rate) : $dataIn->igst_rate;
                        if ($temp_number != '' && $temp_number != $dataIn->reference_number) {
                            $z = 0;
                            $y++;
                        }
                        $dataArr2['exp_typ'] = "WOPAY";
                        $dataArr2['inv'][$y]['inum'] = $dataIn->reference_number;
                        $dataArr2['inv'][$y]['idt'] = date('d-m-Y', strtotime($dataIn->invoice_date));
                        $dataArr2['inv'][$y]['val'] = (float) $dataIn->invoice_total_value;
                        if(!empty($dataIn->export_bill_port_code)) {
                            $dataArr2['inv'][$y]['sbpcode'] = $dataIn->export_bill_port_code;
                        }
                        if(!empty($dataIn->export_bill_number)) {
                            $dataArr2['inv'][$y]['sbnum'] = (int)$dataIn->export_bill_number;
                        }
                        if(!empty($dataIn->export_bill_date)) {
                            $dataArr2['inv'][$y]['sbdt'] = $dataIn->export_bill_date > 0 ? date('d-m-Y', strtotime($dataIn->export_bill_date)) : '';
                        }
                        $dataArr2['inv'][$y]['itms'][$z]['txval'] = (float) $dataIn->taxable_subtotal;
                        $dataArr2['inv'][$y]['itms'][$z]['rt'] = (float) $rt;
                        //$dataArr2['inv'][$y]['itms'][$z]['iamt'] = (float) $dataIn->igst_amount;
                        $temp_number = $dataIn->reference_number;
                        $z++;
                        $exp_array[] = (array) $dataIn;
                    }
                }
            }
            if (!empty($exp_array)) {
                $exp_ids = array_unique(array_column($exp_array, 'invoice_id'));
            }

            $x = 0;
            
            if (!empty($dataArr1)) {
                $dataArr['exp'][$x] = $dataArr1;
                $x++;
            }
            if (!empty($dataArr2)) {
                $dataArr['exp'][$x] = $dataArr2;
            }
        }
        $response['exp_ids'] = $exp_ids;
        $response['exp_arr'] = $dataArr;
        return $response;
    }

    public function getDOCISSUEPayload($user_id, $returnmonth,$flag='',$ids='',$type='')
    {
        $dataArr = $response = $doc_ids = $doc_array = $dataS = array();
        //Start Code For Doc
        $dataInvDoc = array();

        $docissueData = $this->getReturnUploadSummary($_SESSION['user_detail']['user_id'], $returnmonth,'gstr1document');
        $total = $totnum = $totcancel = $net_issue = 0;
        $invCount = 0;
        if (!empty($docissueData)) {
            $json = $docissueData[0]->return_data;
            if(!empty($json)) {
                $Data = $decodeJson = json_decode(base64_decode($json));
                $i=0;
                foreach ($decodeJson as $doc => $arr_value) {
                    $j=0;
                    $num = 1;
                    $a = substr($doc, 7,(strlen($doc)-1));
                    $flag=0;
                    foreach ($arr_value as $key => $Item) {
                        if(!empty($Item->from) && !empty($Item->to)) {
                            $flag=1;
                            $dataS[$i]['doc_num'] = (int)$a;
                            $dataS[$i]['docs'][$j]['num'] = (int)$num;
                            $dataS[$i]['docs'][$j]['from'] = $Item->from;
                            $dataS[$i]['docs'][$j]['to'] = $Item->to;
                            $dataS[$i]['docs'][$j]['totnum'] = (int)$Item->totnum;
                            $dataS[$i]['docs'][$j]['cancel'] = (int)$Item->cancel;
                            $dataS[$i]['docs'][$j]['net_issue'] = (int)$Item->net_issue;
                            $j++;
                        }
                    }
                    if($flag==1)
                    {
                        $i++;
                    }
                }
                if(!empty($dataS)) {
                    $dataInvDoc['doc_issue']['doc_det']  = $dataS;
                }
            }
            
        }       
        /*$this->pr($dataInvDoc); 
        die;*/
        $response['doc_ids'] = $doc_ids;
        $response['doc_arr'] = $dataInvDoc;

        return $response;
    }
    // public function getDOCISSUEPayloadOLD($user_id, $returnmonth,$flag='',$ids='',$type='')
    // {
    //     $dataArr = $response = $doc_ids = $doc_array = array();
    //     //Start Code For Doc
    //     $dataInvDoc = array();

    //     $final_array = $dataRevise = $dataRevised = $dataDebit = $dataCredit = $dataReceipt = $dataRefund = $dataDeliveryJobWork =  $dataDeliverySUAP = $dataDeliverySULGAS = $dataDeliverySupplyOther = array();

    //     /*********** Start code For Doc Sales *************/
    //     $docSales = $this->getDOCSalesInvoices($user_id, $returnmonth,$type,$ids);

    //     $dataInvSales =  $docSales[0];
    //     $dataInvCancelSales = $docSales[1];
    //     if(isset($dataInvSales) && !empty($dataInvSales))
    //     {
    //       $doc_num = 1;
    //       $z=0;
    //       $a = 1;
    //       $totnum= count($dataInvSales);
    //       $cancel = count($dataInvCancelSales);
    //       $net_issue = $totnum - $cancel;
    //       $dataSales['doc_num'] = (int)$doc_num;
    //       $dataSales['docs'][$z]['num'] = (int)$a;
    //       $dataSales['docs'][$z]['from'] = $dataInvSales[0]->reference_number;
    //       $dataSales['docs'][$z]['to'] = $dataInvSales[$totnum-1]->reference_number;
    //       $dataSales['docs'][$z]['totnum'] = (int)$totnum;
    //       $dataSales['docs'][$z]['cancel'] = (int)$cancel;
    //       $dataSales['docs'][$z]['net_issue'] = (int)$net_issue;
    //       $final_array[] = $dataSales;
          
    //     }
    //     /*********** End code For Doc Sales *************/

    //     /*********** Start code For Doc Revised *************/
    //     $docRevised = $this->getDOCRevisedInvoices($user_id, $returnmonth,$type,$ids);
    //     $dataInvSales =  $docRevised[0];
    //     $dataInvCancelSales = $docRevised[1];
    //     if(isset($dataInvRevised) && !empty($dataInvRevised))
    //     {
    //       $doc_num = 2;
    //       $z=0;
    //       $a = 1;
    //       $totnum= count($dataInvRevised);
    //       $cancel = count($dataInvCancleRevised);
    //       $net_issue = $totnum - $cancel;
    //       $dataRevised['doc_num'] = (int)$doc_num;
    //       $dataRevised['docs'][$z]['num'] = (int)$a;
    //       $dataRevised['docs'][$z]['from'] = $dataInvRevised[0]->reference_number;
    //       $dataRevised['docs'][$z]['to'] = $dataInvRevised[$totnum-1]->reference_number;
    //       $dataRevised['docs'][$z]['totnum'] = (int)$totnum;
    //       $dataRevised['docs'][$z]['cancel'] = (int)$cancel;
    //       $dataRevised['docs'][$z]['net_issue'] = (int)$net_issue;
    //       $final_array[] = $dataRevised;
    //     }
    //     /*********** End code For Doc Revised *************/

    //     /*********** Start code For Debit  *************/
    //     $docDebit = $this->getDOCDebitInvoices($user_id, $returnmonth,$type,$ids);
    //     $dataInvDebit = $docDebit[0];
    //     $dataInvCancleDebit = $docDebit[1];
    //     if(isset($dataInvDebit) && !empty($dataInvDebit))
    //     {
    //       $doc_num = 3;
    //       $z=0;
    //       $a = 1;
    //       $totnum= count($dataInvDebit);
    //       $cancel = count($dataInvCancleDebit);
    //       $net_issue = $totnum - $cancel;
    //       $dataDebit['doc_num'] = (int)$doc_num;
    //       $dataDebit['docs'][$z]['num'] = (int)$a;
    //       $dataDebit['docs'][$z]['from'] = $dataInvDebit[0]->reference_number;
    //       $dataDebit['docs'][$z]['to'] = $dataInvDebit[$totnum-1]->reference_number;
    //       $dataDebit['docs'][$z]['totnum'] = (int)$totnum;
    //       $dataDebit['docs'][$z]['cancel'] = (int)$cancel;
    //       $dataDebit['docs'][$z]['net_issue'] = (int)$net_issue;
    //       $final_array[] = $dataDebit;
    //     }
    //     /*********** End code For Debit  *************/

    //     /*********** Start code For Credit  *************/
    //     $docCredit = $this->getDOCCreditInvoices($user_id, $returnmonth,$type,$ids);
    //     $dataInvCredit = $docCredit[0];
    //     $dataInvCancleCredit = $docCredit[1];
    //     if(isset($dataInvCredit) && !empty($dataInvCredit))
    //     {
    //       $doc_num = 4;
    //       $z=0;
    //       $a = 1;
    //       $totnum= count($dataInvCredit);
    //       $cancel = count($dataInvCancleCredit);
    //       $net_issue = $totnum - $cancel;
    //       $dataCredit['doc_num'] = (int)$doc_num;
    //       $dataCredit['docs'][$z]['num'] = (int)$a;
    //       $dataCredit['docs'][$z]['from'] = $dataInvCredit[0]->reference_number;
    //       $dataCredit['docs'][$z]['to'] = $dataInvCredit[$totnum-1]->reference_number;
    //       $dataCredit['docs'][$z]['totnum'] = (int)$totnum;
    //       $dataCredit['docs'][$z]['cancel'] = (int)$cancel;
    //       $dataCredit['docs'][$z]['net_issue'] = (int)$net_issue;
    //       $final_array[] = $dataCredit;
    //     }
    //     /*********** End code For Credit  *************/

    //     /*********** Start code For Receipt   *************/
    //     $docReceipt = $this->getDOCReceiptInvoices($user_id, $returnmonth,$type,$ids);
    //     $dataInvReceipt =  $docReceipt[0];
    //     $dataInvCancleReceipt =  $docReceipt[1];
    //     if(isset($dataInvReceipt) && !empty($dataInvReceipt))
    //     {
    //       $doc_num = 5;
    //       $z=0;
    //       $a = 1;
    //       $totnum= count($dataInvReceipt);
    //       $cancel = count($dataInvCancleReceipt);
    //       $net_issue = $totnum - $cancel;
    //       $dataReceipt['doc_num'] = (int)$doc_num;
    //       $dataReceipt['docs'][$z]['num'] = (int)$a;
    //       $dataReceipt['docs'][$z]['from'] = $dataInvReceipt[0]->reference_number;
    //       $dataReceipt['docs'][$z]['to'] = $dataInvReceipt[$totnum-1]->reference_number;
    //       $dataReceipt['docs'][$z]['totnum'] = (int)$totnum;
    //       $dataReceipt['docs'][$z]['cancel'] = (int)$cancel;
    //       $dataReceipt['docs'][$z]['net_issue'] = (int)$net_issue;
    //       $final_array[] = $dataReceipt;
    //     }
    //     /*********** End code For Receipt   *************/

    //     /*********** Start code For Refund   *************/
    //     $docRefund = $this->getDOCRefundInvoices($user_id, $returnmonth,$type,$ids);
    //     $dataInvRefund = $docRefund[0];
    //     $dataInvCancleRefund = $docRefund[1];
    //     if(isset($dataInvRefund) && !empty($dataInvRefund))
    //     {
    //       $doc_num = 6;
    //       $z=0;
    //       $a = 1;
    //       $totnum= count($dataInvRefund);
    //       $cancel = count($dataInvCancleRefund);
    //       $net_issue = $totnum - $cancel;
    //       $dataRefund['doc_num'] = (int)$doc_num;
    //       $dataRefund['docs'][$z]['num'] = (int)$a;
    //       $dataRefund['docs'][$z]['from'] = $dataInvRefund[0]->reference_number;
    //       $dataRefund['docs'][$z]['to'] = $dataInvRefund[$totnum-1]->reference_number;
    //       $dataRefund['docs'][$z]['totnum'] = (int)$totnum;
    //       $dataRefund['docs'][$z]['cancel'] = (int)$cancel;
    //       $dataRefund['docs'][$z]['net_issue'] = (int)$net_issue;
    //       $final_array[] = $dataRefund;
    //     }
    //     /*********** End code For Refund   *************/

    //     /*********** Start code Delivery Challan for job work  *************/
    //     $docDeliveryJobWork = $this->getDOCDeliveryChallanJobWorkInvoices($user_id, $returnmonth,$type,$ids);
    //     $dataInvDeliveryJobWork = $docDeliveryJobWork[0];
    //     $dataInvCancleDeliveryJobWork = $docDeliveryJobWork[1];

    //     if(isset($dataInvDeliveryJobWork) && !empty($dataInvDeliveryJobWork))
    //     {
    //       $doc_num = 7;
    //       $z=0;
    //       $a = 1;
    //       $totnum= count($dataInvRefund);
    //       $cancel = count($dataInvCancleDeliveryJobWork);
    //       $net_issue = $totnum - $cancel;
    //       $dataDeliveryJobWork['doc_num'] = (int)$doc_num;
    //       $dataDeliveryJobWork['docs'][$z]['num'] = (int)$a;
    //       $dataDeliveryJobWork['docs'][$z]['from'] = $dataInvDeliveryJobWork[0]->reference_number;
    //       $dataDeliveryJobWork['docs'][$z]['to'] = $dataInvDeliveryJobWork[$totnum-1]->reference_number;
    //       $dataDeliveryJobWork['docs'][$z]['totnum'] = (int)$totnum;
    //       $dataDeliveryJobWork['docs'][$z]['cancel'] = (int)$cancel;
    //       $dataDeliveryJobWork['docs'][$z]['net_issue'] = (int)$net_issue;
    //       $final_array[] = $dataDeliveryJobWork;
    //     }
    //     /*********** End code Delivery Challan for job work *************/

    //     /*********** Start code Delivery Challan for supply on approval  *************/
    //     $docDeliverySUAP = $this->getDOCDeliveryChallanSupplyOnApprovalInvoices($user_id, $returnmonth,$type,$ids);
    //     $dataInvDeliverySUAP = $docDeliverySUAP[0];
    //     $dataInvCancleDeliverySUAP = $docDeliverySUAP[1];

    //     if(isset($dataInvDeliverySUAP) && !empty($dataInvDeliverySUAP) && !empty($dataInvDeliveryJobWork))
    //     {
    //       $doc_num = 8;
    //       $z=0;
    //       $a = 1;
    //       $totnum= count($dataInvDeliverySUAP);
    //       $cancel = count($dataInvCancleDeliverySUAP);
    //       $net_issue = $totnum - $cancel;
    //       $dataDeliverySUAP['doc_num'] = (int)$doc_num;
    //       $dataDeliverySUAP['docs'][$z]['num'] = (int)$a;
    //       $dataDeliverySUAP['docs'][$z]['from'] = $dataInvDeliverySUAP[0]->reference_number;
    //       $dataDeliverySUAP['docs'][$z]['to'] = $dataInvDeliverySUAP[$totnum-1]->reference_number;
    //       $dataDeliverySUAP['docs'][$z]['totnum'] = (int)$totnum;
    //       $dataDeliverySUAP['docs'][$z]['cancel'] = (int)$cancel;
    //       $dataDeliverySUAP['docs'][$z]['net_issue'] = (int)$net_issue;
    //       $final_array[] = $dataDeliverySUAP;
    //     }
    //     /*********** End code Delivery Challan for supply on approval *************/

    //     /*********** Start code Delivery Challan in case of liquid gas  *************/
    //     $docDeliverySULGAS = $this->getDOCDeliveryChallanInCaseLiquidGasInvoices($user_id, $returnmonth,$type,$ids);
    //     $dataInvDeliverySULGAS = $docDeliverySULGAS[0];
    //     $dataInvCancleDeliverySULGAS = $docDeliverySULGAS[1];

    //     if(isset($dataInvDeliverySULGAS) && !empty($dataInvDeliverySULGAS))
    //     {
    //       $doc_num = 9;
    //       $z=0;
    //       $a = 1;
    //       $totnum= count($dataInvDeliverySULGAS);
    //       $cancel = count($dataInvCancleDeliverySULGAS);
    //       $net_issue = $totnum - $cancel;
    //       $dataDeliverySULGAS['doc_num'] = (int)$doc_num;
    //       $dataDeliverySULGAS['docs'][$z]['num'] = (int)$a;
    //       $dataDeliverySULGAS['docs'][$z]['from'] = $dataInvDeliverySULGAS[0]->reference_number;
    //       $dataDeliverySULGAS['docs'][$z]['to'] = $dataInvDeliverySULGAS[$totnum-1]->reference_number;
    //       $dataDeliverySULGAS['docs'][$z]['totnum'] = (int)$totnum;
    //       $dataDeliverySULGAS['docs'][$z]['cancel'] = (int)$cancel;
    //       $dataDeliverySULGAS['docs'][$z]['net_issue'] = (int)$net_issue;
    //       $final_array[] = $dataDeliverySULGAS;
    //     }
    //     /*********** End code Delivery Challan in case of liquid gas *************/

    //     /*********** Start code Delivery Challan in cases other than by way of supply  *************/
    //     $docDeliveryOther = $this->getDOCDeliveryChallanInCaseOtherInvoices($user_id, $returnmonth,$type,$ids);
    //     $dataInvDeliverySupplyOther = $docDeliveryOther[0];
    //     $dataInvCancleDeliverySupplyOther= $docDeliveryOther[1];

    //     if(isset($dataInvDeliverySupplyOther) && !empty($dataInvDeliverySupplyOther))
    //     {
    //       $doc_num = 10;
    //       $z=0;
    //       $a = 1;
    //       $totnum= count($dataInvDeliverySupplyOther);
    //       $cancel = count($dataInvCancleDeliverySupplyOther);
    //       $net_issue = $totnum - $cancel;
    //       $dataDeliverySupplyOther['doc_num'] = (int)$doc_num;
    //       $dataDeliverySupplyOther['docs'][$z]['num'] = (int)$a;
    //       $dataDeliverySupplyOther['docs'][$z]['from'] = $dataInvDeliverySupplyOther[0]->reference_number;
    //       $dataDeliverySupplyOther['docs'][$z]['to'] = $dataInvDeliverySupplyOther[$totnum-1]->reference_number;
    //       $dataDeliverySupplyOther['docs'][$z]['totnum'] = (int)$totnum;
    //       $dataDeliverySupplyOther['docs'][$z]['cancel'] = (int)$cancel;
    //       $dataDeliverySupplyOther['docs'][$z]['net_issue'] = (int)$net_issue;
    //       $final_array[] = $dataDeliverySupplyOther;
    //     }
    //     /*********** End code Delivery Challan in cases other than by way of supply  *************/

    //     if(!empty($final_array)) {
    //         $dataInvDoc['doc_issue']['doc_det']  = $final_array;
    //     }
        
    //     $response['doc_ids'] = $doc_ids;
    //     $response['doc_arr'] = $dataInvDoc;

    //     return $response;
    // }


    public function getTXPDPayload($user_id, $returnmonth,$flag='',$ids='',$type='') {
        $dataArr = $response = $txpd_ids = array();
        $dataArr['txpd'][0]['pos'] = '05';
        $dataArr['txpd'][0]['sply_ty'] = 'INTER';
        $dataArr['txpd'][0]['itms'][0]['rt'] = (float) 5;
        $dataArr['txpd'][0]['itms'][0]['ad_amt'] = (float) 100;
        $dataArr['txpd'][0]['itms'][0]['iamt'] = (float) 9400;
        $dataArr['txpd'][0]['itms'][0]['csamt'] = (float) 500;
        $response['txpd_ids'] = $txpd_ids;
        $response['txpd_arr'] = $dataArr;
        return $response;
    }

    public function getNilFinalArray($user_id, $returnmonth) {
        $dataArr = $response = $nil_array = $nil_ids = array();
        $dataInvNil = $this->getNilInvoices($user_id, $returnmonth);
        //$this->pr($dataInvNil);
        if (!empty($dataInvNil)) {
            $dataInv1 = $dataInvNil[0];
            $dataInv2 = $dataInvNil[1];
            $nill_inv_array_b2b = $nill_inv_array_b2c = array();
            $totalExmp=$totalNonGst= $totalNil= $totalExmpInt=$totalNonGstInt= $totalNilInt = 0;

            if (isset($dataInv1)) {
                foreach ($dataInv1 as $dataIn) {
                    if ($dataIn->company_state != $dataIn->supply_place) {
                        $nill_inv_array_b2b[0]['sply_ty'] = 'INTERB2B';
                        if($dataIn->is_applicable =='0')
                        {
                            $totalNil += isset($dataIn->invoice_total_value)?$dataIn->invoice_total_value:0;
                        }
                        if($dataIn->is_applicable =='1')
                        {
                            $totalNonGst += isset($dataIn->invoice_total_value)?$dataIn->invoice_total_value:0;
                        }
                        if($dataIn->is_applicable =='2')
                        {
                            $totalExmp += isset($dataIn->invoice_total_value)?$dataIn->invoice_total_value:0;
                        }
                    }
                    else {
                        $nill_inv_array_b2b[0]['sply_ty'] = 'INTERB2B';
                        if($dataIn->is_applicable =='0')
                        {
                            $totalNilInt += isset($dataIn->invoice_total_value)?$dataIn->invoice_total_value:0;
                        }
                        if($dataIn->is_applicable =='1')
                        {
                            $totalNonGstInt += isset($dataIn->invoice_total_value)?$dataIn->invoice_total_value:0;
                        }
                        if($dataIn->is_applicable =='2')
                        {
                            $totalExmpInt += isset($dataIn->invoice_total_value)?$dataIn->invoice_total_value:0;
                        }
                    }
                    
                    $nil_array[] = (array) $dataIn;
                } 
                foreach ($dataInv1 as $dataIn) {   
                    if ($dataIn->company_state != $dataIn->supply_place) {
                        $nill_inv_array_b2b[0]['sply_ty'] = 'INTERB2B';
                        $nill_inv_array_b2b[0]['nil_amt'] = $totalNil;
                        $nill_inv_array_b2b[0]['ngsup_amt'] = $totalNonGst;
                        $nill_inv_array_b2b[0]['expt_amt'] = $totalExmp;
                        
                    } else {
                        $nill_inv_array_b2b[1]['sply_ty'] = 'INTRAB2B';
                        $nill_inv_array_b2b[1]['nil_amt'] = $totalNilInt;
                        $nill_inv_array_b2b[1]['ngsup_amt'] = $totalNonGstInt;
                        $nill_inv_array_b2b[1]['expt_amt'] = $totalExmpInt;
                    }
                }   
                  
            }
            if (isset($dataInv2)) {
                $totalExmp=$totalNonGst= $totalNil= $totalExmpInt=$totalNonGstInt= $totalNilInt = 0;
                foreach ($dataInv2 as $dataIn) {
                    if ($dataIn->company_state != $dataIn->supply_place) {
                        $nill_inv_array_b2b[0]['sply_ty'] = 'INTERB2B';
                        if($dataIn->is_applicable =='0')
                        {
                            $totalNil += isset($dataIn->invoice_total_value)?$dataIn->invoice_total_value:0;
                        }
                        if($dataIn->is_applicable =='1')
                        {
                            $totalNonGst += isset($dataIn->invoice_total_value)?$dataIn->invoice_total_value:0;
                        }
                        if($dataIn->is_applicable =='2')
                        {
                            $totalExmp += isset($dataIn->invoice_total_value)?$dataIn->invoice_total_value:0;
                        }
                    }
                    else {
                        $nill_inv_array_b2b[0]['sply_ty'] = 'INTERB2B';
                        if($dataIn->is_applicable =='0')
                        {
                            $totalNilInt += isset($dataIn->invoice_total_value)?$dataIn->invoice_total_value:0;
                        }
                        if($dataIn->is_applicable =='1')
                        {
                            $totalNonGstInt += isset($dataIn->invoice_total_value)?$dataIn->invoice_total_value:0;
                        }
                        if($dataIn->is_applicable =='2')
                        {
                            $totalExmpInt += isset($dataIn->invoice_total_value)?$dataIn->invoice_total_value:0;
                        }
                    }
                    
                    $nil_array[] = (array) $dataIn;
                } 
                foreach ($dataInv2 as $dataIn) {
                    if ($dataIn->company_state != $dataIn->supply_place) {
                        $nill_inv_array_b2c[0]['sply_ty'] = 'INTERB2C';
                        $nill_inv_array_b2c[0]['nil_amt'] = $totalNil;
                        $nill_inv_array_b2c[0]['ngsup_amt'] = $totalNonGst;
                        $nill_inv_array_b2c[0]['expt_amt'] = $totalExmp;

                        
                    } else {
                        $nill_inv_array_b2c[1]['sply_ty'] = 'INTRAB2C';
                        $nill_inv_array_b2c[1]['nil_amt'] = $totalNilInt;
                        $nill_inv_array_b2c[1]['ngsup_amt'] = $totalNonGstInt;
                        $nill_inv_array_b2c[1]['expt_amt'] = $totalExmpInt;
                    }

                    $nil_array[] = (array) $dataIn;
                }
            }
            if (!empty($nill_inv_array_b2c)) {
                $nill_inv_array_b2b = array_merge($nill_inv_array_b2b, $nill_inv_array_b2c);
            }
            
        }

        return $nill_inv_array_b2b;
    }

    public function startGstr1() {

        $sql = "select * from " . TAB_PREFIX . "return where client_id='" . $_SESSION['user_detail']['user_id'] . "' and return_month='" . $_GET["returnmonth"] . "' and type='gstr1'";

        $clientdata = $this->get_results($sql);

        if (empty($clientdata)) {

            $dataArr['return_month'] = $this->sanitize($_GET['returnmonth']);
            $dataArr['type'] = 'gstr1';
            $dataArr['client_id'] = $_SESSION['user_detail']['user_id'];
            $year = $this->generateFinancialYear();
            $dataArr['financial_year'] = $year;
            $dataArr['status'] = 1;

            if ($this->insert(TAB_PREFIX . 'return', $dataArr)) {
				$this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . "Initiated GSTR1 filling for financial month ".$this->sanitize($_GET['returnmonth']),"gstr1");
			
                //$this->setSuccess('GSTR2 Saved Successfully');
                return true;
            } else {
                $this->setError('Failed to save GSTR1 data');
                return false;
            }
        } else {
           
        }
    }

}
