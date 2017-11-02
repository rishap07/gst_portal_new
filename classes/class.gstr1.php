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
    public function gstr1FinalSubmit() {
        $obj_gst = new gstr();
        $fmonth = isset($_GET['returnmonth']) ? $_GET['returnmonth'] : date('Y-m');
        $response = $obj_gst->returnSubmit($fmonth,'gstr1');

        if ($response['error'] == 0) {
            $flag = 1;

        } 
        else {
            $flag = 2;
            if(!empty($response['message'])) {
               $this->setError($response['message']); 
            }
            
        }
        if ($flag == 1) {
            //$this->setSuccess($response['message']);
            $this->setSuccess(" Congratulations! GSTR1 Data Submitted.");
            return true;
        }
        elseif ($flag == 2) {
            return false;
        }
       
        return false;
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
            //if (!empty($is_gross_turnover_check) && !empty($cur_gt)) {
                //$this->pr($dataRes);  
                
                $payload = $this->gstCreatePayload($_SESSION['user_detail']['user_id'], $fmonth,$ids,'',$invoice_type);

                $dataArr = $payload['data_arr'];
                $data_ids = $payload['data_ids'];
               // $this->pr($dataArr);
               //$this->pr(json_encode($dataArr));die;
                $response = $obj_gst->returnSave($dataArr, $fmonth,'gstr1');
                  //$this->pr($response);         
                if ($response['error'] == 0) {
                    $flag = 1;
                    if($invoice_type != 'HSN' && $invoice_type != 'NIL' && $invoice_type != 'DOCISSUE') {
                        //if (!empty($dataRes)) {
                            if (!empty($data_ids)) {

                                /********** Start Code for Update Invoice is upload ************* */
                                $flagup = 0;
                                /*$this->pr($data_ids);
                                die;*/
                                $this->query("UPDATE ".$this->getTableName('gstr1_return_summary')." SET is_uploaded='1' WHERE id in (".$data_ids.")");
                                
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
                        /*}
                        else {
                            $flag = 0;
                        }*/
                    }
                    elseif($invoice_type == 'HSN') {
                        //echo $flag;
                        $this->query("UPDATE ".$this->getTableName('return_upload_summary')." SET is_uploaded='1' WHERE added_by = '".$_SESSION['user_detail']['user_id']."' and financial_month = '".$fmonth."' and type = 'gstr1hsn' ");
                    }
                    elseif($invoice_type == 'NIL') {
                        $this->query("UPDATE ".$this->getTableName('return_upload_summary')." SET is_uploaded='1' WHERE added_by = '".$_SESSION['user_detail']['user_id']."' and financial_month = '".$fmonth."' and type = 'gstr1nil' ");
                    }
                    elseif($invoice_type == 'DOCISSUE') {
                        $this->query("UPDATE ".$this->getTableName('return_upload_summary')." SET is_uploaded='1' WHERE added_by = '".$_SESSION['user_detail']['user_id']."' and financial_month = '".$fmonth."' and type = 'gstr1document' ");
                    }
                    

                } 
                else {
                    $flag = 2;
                    $this->setError($response['message']);
                }
               //echo $flag.$invoice_type;
                if ($flag == 1) {
                    
                    $this->setSuccess(" Congratulations! GSTR1 Data Uploaded.");
                    return true;
                }
                elseif ($flag == 2) {
                    return false;
                }
                /*elseif ($flag == 0) {
                    $this->setError('No new data to upload');
                    return false;
                }*/
                return false;
            /*} else {
                $this->setError('Sorry! Please update your gross turnover');
                return false;
            }*/
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
        if(API_TYPE == 'Demo') {
           $gstin = API_GSTIN;
        }
        else {
            $gstin = $obj_gst->gstin();
        }
        $dataArr["gstin"] = $gstin;
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
            $txpd_data = $this->getTXPDPayload($user_id, $returnmonth,'',$ids,$type);
            if (!empty($txpd_data)) {
                $data_ids[] = $txpd_ids = $txpd_data['txpd_ids'];
                $txpd_arr = $txpd_data['txpd_arr'];
                $dataArr = array_merge($dataArr, $txpd_arr);
            }
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
        if($invoice_type == 'TXPD') {
            $txpd_data = $this->getTXPDPayload($user_id, $returnmonth,'',$ids,'all');
            if (!empty($txpd_data)) {
                $data_ids[] = $txpd_ids = $txpd_data['txpd_ids'];
                $txpd_arr = $txpd_data['txpd_arr'];
                $dataArr = array_merge($dataArr, $txpd_arr);
            }
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
            //if (!empty($is_gross_turnover_check) && !empty($cur_gt)) {

                $deletePayload = $this->gstDeletePayload($user_id, $returnmonth,$type,$data,$all);
                 /*$obj_gst->pr($deletePayload);
                 die;*/
                if(!empty($deletePayload)) {
                    $deletePayloadArr = $deletePayload['data_arr'];
                    $data_ids = $deletePayload['data_ids'];
                    $dataArr = array_merge($dataArr, $deletePayloadArr);
                    $response = $obj_gst->returnDeleteItems($dataArr,$returnmonth,'gstr1');
                    
                   // $obj_gst->pr($dataArr);
                    //$obj_gst->pr($response);
                    
                    $flag = 0;
                    $inum = isset($data['inum'])?$data['inum']:'';
                    
                    if ($response['error'] == 0) {
                        $flag =1;
                        if($type != 'HSN' && $type != 'NIL' && $type != 'DOC_ISSUE') {
                            if (!empty($data_ids)) {
                                foreach ($data_ids as $key => $data_id) {
                                    $this->query("UPDATE ".$this->getTableName('gstr1_return_summary')." SET is_uploaded='0' WHERE id = ".$data_id."");  
                                    $this->logMsg("GSTR1 Invoice id : " . $data_id . " upload status has been changed to not uploaded for return period : " . $returnmonth . " by User ID : " . $user_id . ".","gstr1");
                                }

                            }
                        }

                        elseif($type == 'HSN') {
                            $this->query("DELETE FROM ".$this->getTableName('return_upload_summary')."  WHERE added_by = '".$_SESSION['user_detail']['user_id']."' and financial_month = '".$returnmonth."' and type = 'gstr1hsn' ");
                            $this->logMsg("HSN Invoices deleted for return period : " . $returnmonth . " by User ID : " . $user_id . ".","gstr1hsn");
                        }
                        elseif($type == 'NIL') {
                            $this->query("DELETE FROM ".$this->getTableName('return_upload_summary')."  WHERE added_by = '".$_SESSION['user_detail']['user_id']."' and financial_month = '".$returnmonth."' and type = 'gstr1nil' ");
                            $this->logMsg("NIL Invoices deleted for return period : " . $returnmonth . " by User ID : " . $user_id . ".","gstr1nil");
                        }
                        elseif($type == 'DOC_ISSUE') {
                            $this->query("DELETE FROM ".$this->getTableName('return_upload_summary')."  WHERE added_by = '".$_SESSION['user_detail']['user_id']."' and financial_month = '".$returnmonth."' and type = 'gstr1document' ");
                            $this->logMsg("DOC_ISSUE Invoices deleted for return period : " . $returnmonth . " by User ID : " . $user_id . ".","gstr1document");
                        }
                         
                    }
                    if($flag == 1) {
                        $this->setSuccess("Your Invoice  ".$inum." has been deleted from GSTN.");
                        return true;
                    }
                    if ($response['error'] == 2) {
                        $this->setError($response['message']);
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
            /*} else {
                $this->setError('Sorry! Please update your gross turnover');
                return false;
            }*/
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
            if($type == 'B2B') {
                $ctin = isset($data['ctin'])?$data['ctin']:'';
                $inum = isset($data['inum'])?$data['inum']:'';
                $idt = isset($data['idt'])?$data['idt']:'';
                $json = isset($data['json'])?$data['json']:'';
                if($all == 'all') {
                    $b2b_data = $this->gstB2BPayload($user_id, $returnmonth,'D');
                    if (!empty($b2b_data)) {
                        $data_ids = $b2b_data['b2b_ids'];
                    }
                    $jstr1_array = json_decode($json,true);
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
                    }
                    
                }
                else {
                    $dataArr['b2b'][0]['ctin'] = $ctin;
                    $dataArr['b2b'][0]['inv'][0]['flag'] = 'D';
                    $dataArr['b2b'][0]['inv'][0]['inum'] = $inum;
                    $dataArr['b2b'][0]['inv'][0]['idt'] = $idt;

                    $data_arr = array();
                    $data_arr['user_id'] = $user_id;
                    $data_arr['returnmonth'] = $returnmonth;
                    $data_arr['invoice_nature'] = 'b2b';
                    $data_arr['ctin'] = $ctin;
                    $data_arr['inum'] = $inum;
                    $data_arr['idt'] = $idt;

                    $data_ids = $this->gstGetInvoiceIdForSingleDelete($data_arr);

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
                        $data_ids = $b2cl_data['b2cl_ids'];
                    }
                    $jstr1_array = json_decode($json,true);
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
                    }
                    
                }
                else {
                    $dataArr['b2cl'][0]['pos'] = $pos;
                    $dataArr['b2cl'][0]['inv'][0]['flag'] = 'D';
                    $dataArr['b2cl'][0]['inv'][0]['inum'] = $inum;
                    $dataArr['b2cl'][0]['inv'][0]['idt'] = $idt;

                    $data_arr = array();
                    $data_arr['user_id'] = $user_id;
                    $data_arr['returnmonth'] = $returnmonth;
                    $data_arr['invoice_nature'] = 'b2cl';
                    $data_arr['pos'] = $pos;
                    $data_arr['inum'] = $inum;
                    $data_arr['idt'] = $idt;

                    $data_ids = $this->gstGetInvoiceIdForSingleDelete($data_arr);
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
                        $data_ids = $cdnr_data['cdnr_ids'];
                    }
                    $jstr1_array = json_decode($json,true);
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
                    }
                    
                }
                else {
                    $dataArr['cdnr'][0]['ctin'] = $ctin;
                    $dataArr['cdnr'][0]['nt'][0]['flag'] = 'D';
                    $dataArr['cdnr'][0]['nt'][0]['nt_num'] = $nt_num;
                    $dataArr['cdnr'][0]['nt'][0]['nt_dt'] = $nt_dt;
                    $dataArr['cdnr'][0]['nt'][0]['inum'] = $inum;
                    $dataArr['cdnr'][0]['nt'][0]['idt'] = $idt;

                    $data_arr = array();
                    $data_arr['user_id'] = $user_id;
                    $data_arr['returnmonth'] = $returnmonth;
                    $data_arr['invoice_nature'] = 'cdnr';
                    $data_arr['nt_num'] = $nt_num;
                    $data_arr['nt_dt'] = $nt_dt;
                    $data_arr['inum'] = $inum;
                    $data_arr['idt'] = $idt;

                    $data_ids = $this->gstGetInvoiceIdForSingleDelete($data_arr);
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
                        $data_ids = $cdnur_data['cdnur_ids'];
                    }
                    $jstr1_array = json_decode($json,true);
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
                    }
                    
                }
                else {

                    $dataArr['cdnur'][0]['typ'] = $typ;
                    $dataArr['cdnur'][0]['flag'] = 'D';
                    $dataArr['cdnur'][0]['nt_num'] = $nt_num;
                    $dataArr['cdnur'][0]['nt_dt'] = $nt_dt;
                    $dataArr['cdnur'][0]['inum'] = $inum;
                    $dataArr['cdnur'][0]['idt'] = $idt;

                    $data_arr = array();
                    $data_arr['user_id'] = $user_id;
                    $data_arr['returnmonth'] = $returnmonth;
                    $data_arr['invoice_nature'] = 'cdnur';
                    $data_arr['nt_num'] = $nt_num;
                    $data_arr['nt_dt'] = $nt_dt;
                    $data_arr['inum'] = $inum;
                    $data_arr['idt'] = $idt;

                    $data_ids = $this->gstGetInvoiceIdForSingleDelete($data_arr);
                }
                
            }
            if($type == 'EXP') {
                $inum = isset($data['inum'])?$data['inum']:'';
                $idt = isset($data['idt'])?$data['idt']:'';
                $exp_typ = isset($data['exp_typ'])?$data['exp_typ']:'';
                $json = isset($data['json'])?$data['json']:'';
                if($all == 'all') {
                    $exp_data = $this->getEXPPayload($user_id, $returnmonth,'D');
                    if (!empty($exp_data)) {
                        $data_ids = $exp_data['exp_ids'];
                    }
                    $jstr1_array = json_decode($json,true);
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
                    }
                    
                }
                else {
                    $dataArr['exp'][0]['exp_typ'] = $exp_typ;
                    $dataArr['exp'][0]['inv'][0]['flag'] = 'D';
                    $dataArr['exp'][0]['inv'][0]['inum'] = $inum;
                    $dataArr['exp'][0]['inv'][0]['idt'] = $idt;
                    $data_ids = $this->gstEXPDeleteQuery($user_id,$returnmonth,$inum,$idt,$exp_typ,$all);

                    $data_arr = array();
                    $data_arr['user_id'] = $user_id;
                    $data_arr['returnmonth'] = $returnmonth;
                    $data_arr['invoice_nature'] = 'exp';
                    $data_arr['exp_typ'] = $exp_typ;
                    $data_arr['inum'] = $inum;
                    $data_arr['idt'] = $idt;

                    $data_ids = $this->gstGetInvoiceIdForSingleDelete($data_arr);
                }
                
            }
            
            if($all == 'all') {
                if($type == 'B2CS') {
                    $b2cs_data = $this->gstB2CSPayload($user_id, $returnmonth,'D');
                    if (!empty($b2cs_data)) {
                        $data_ids = $b2cs_data['b2cs_ids'];
                    }
                    $json = isset($data['json'])?$data['json']:'';
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
                }
                
                if($type == 'AT') {
                    $at_data = $this->gstATPayload($user_id, $returnmonth,'D');
                    if (!empty($at_data)) {
                        $data_ids = $at_data['at_ids'];
                    }
                    $json = isset($data['json'])?$data['json']:'';
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
                }
                if($type == 'TXPD') {
                    $txpd_data = $this->getTXPDPayload($user_id, $returnmonth,'D');
                    if (!empty($txpd_data)) {
                        $data_ids = $txpd_data['txpd_ids'];
                    }
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
                if($type == 'HSN') {
                    $json = isset($data['json'])?$data['json']:'';
                    $jstr1_array = json_decode($json,true);
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
                    }
                } 
                if($type == 'NIL') {
                    $json = isset($data['json'])?$data['json']:'';
                    $jstr1_array = json_decode($json,true);
                    if(isset($jstr1_array['nil'])) {
                        $dataArr["nill"][0]["inv"] = $jstr1_array['inv'];
                        $dataArr["nill"][0]["flag"] = 'D';
                    }
                } 
                if($type == 'DOC_ISSUE') {
                    $json = isset($data['json'])?$data['json']:'';
                    $jstr1_array = json_decode($json,true);
                    if(isset($jstr1_array['doc_issue'])) {
                        $i=0;
                        $dataArr['doc_issue']['chksum'] = $jstr1_array['doc_issue']['chksum'];
                        $dataArr['doc_issue']['flag'] = 'D';
                        $dataArr['doc_issue']['doc_det'] = $jstr1_array['doc_issue']['doc_det'];   
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

    public function gstGetInvoiceIdForSingleDelete($fields = array())
    {
        $user_id = isset($fields['user_id'])?$fields['user_id']:'';
        $returnmonth = isset($fields['returnmonth'])?$fields['returnmonth']:'';

        $invoice_nature = isset($fields['invoice_nature'])?$fields['invoice_nature']:'';
        $inum = isset($fields['inum'])?$fields['inum']:'';
        $idt = isset($fields['idt'])?date('Y-m-d', strtotime($fields['idt'])):'';
        $ctin = isset($fields['ctin'])?$fields['ctin']:'';
        $pos = isset($fields['pos'])?$fields['pos']:'';
        $nt_num = isset($fields['nt_num'])?$fields['nt_num']:'';
        $nt_dt = isset($fields['nt_dt'])?date('Y-m-d', strtotime($fields['nt_dt'])):'';
        $exp_typ = isset($fields['exp_typ'])?$fields['exp_typ']:'';

        $query =  "select a.id as invoice_id from ".$this->getTableName('gstr1_return_summary')." a  where a.is_uploaded='1' and a.status='1' and a.added_by = '".$user_id."'  and a.return_period like '%".$returnmonth."%'  ";

        if(!empty($invoice_nature)) {
            $query .= " and a.invoice_nature  = '".$invoice_nature."' ";
        }
        if(!empty($inum)) {
            $query .= " and a.invoice_number  = '".$inum."' ";
        }
        if(!empty($idt)) {
            $query .= " and a.invoice_date like '%".$idt."%' ";
        }
        if(!empty($ctin)) {
            $query .= " and a.recipient_gstin like '%".$ctin."%' ";
        }
        if(!empty($pos)) {
            $query .= " and a.place_of_supply = '".$pos."' ";
        }
        if(!empty($nt_num)) {
            $query .= " and a.original_invoice_number = '".$nt_num."' ";
        }
        if(!empty($nt_dt)) {
            $query .= " and a.original_invoice_date like '%".$nt_dt."%' ";
        }
        if(!empty($exp_typ)) {
            $query .= " and a.invoice_type = '".$exp_typ."' ";
        }

        
        $datas = $this->get_results($query);
        
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
           $dataInvB2B = $this->getAllInvoices($user_id, $returnmonth,'b2b','1'); 
        }
        else {
           $dataInvB2B = $this->getAllInvoices($user_id, $returnmonth,'b2b','',$ids); 
        }
        
        if (isset($dataInvB2B) && !empty($dataInvB2B)) {

            $x = 0;
            $y = 0;
            $z = 0;
            $a = 1;
            $temp_number = '';
            $ctin = '';
            foreach ($dataInvB2B as $dataIn) {
                if ($temp_number != '' && $temp_number != $dataIn->reference_number) {
                    $z = 0;
                    $y++;
                    $a=1;
                }
                if ($ctin != '' && $ctin != $dataIn->billing_gstin_number) {
                    $z = 0;
                    $y=0;
                    $x++;
                }
                
                $dataArr['b2b'][$x]['ctin'] = $dataIn->billing_gstin_number;
                $dataArr['b2b'][$x]['inv'][$y]['inum'] = $dataIn->reference_number;
                $dataArr['b2b'][$x]['inv'][$y]['idt'] = date('d-m-Y', strtotime($dataIn->invoice_date));
                if(!empty($flag)) {
                   $dataArr['b2b'][$x]['inv'][$y]['flag'] = $flag; 
                }
                $dataArr['b2b'][$x]['inv'][$y]['val'] = (float) $dataIn->invoice_total_value;
                $dataArr['b2b'][$x]['inv'][$y]['pos'] = strlen($dataIn->supply_place) == '1' ? '0' . $dataIn->supply_place : $dataIn->supply_place;
                if ($dataIn->invoice_type == 'taxinvoice' || $dataIn->invoice_type =='billofsupplyinvoice') {

                    if ($dataIn->company_state != $dataIn->supply_place ) {
                        $dataArr['b2b'][$x]['inv'][$y]['itms'][$z]['itm_det']['iamt'] = (float) $dataIn->igst_amount;
                    } else {
                        $dataArr['b2b'][$x]['inv'][$y]['itms'][$z]['itm_det']['samt'] = (float) $dataIn->sgst_amount;
                        $dataArr['b2b'][$x]['inv'][$y]['itms'][$z]['itm_det']['camt'] = (float) $dataIn->cgst_amount;
                    }
                } 
                else if ($dataIn->invoice_type == 'sezunitinvoice') {
                    
                    $dataArr['b2b'][$x]['inv'][$y]['itms'][$z]['itm_det']['iamt'] = (float) $dataIn->igst_amount;

                } else if ($dataIn->invoice_type == 'deemedexportinvoice') {

                    $dataArr['b2b'][$x]['inv'][$y]['itms'][$z]['itm_det']['iamt'] = (float) $dataIn->igst_amount;
                }
                /*if($dataIn->supply_type == 'tcs') {
                    $dataArr['b2b'][$x]['inv'][$y]['etin'] = $dataIn->ecommerce_gstin_number;
                }*/
                $dataArr['b2b'][$x]['inv'][$y]['inv_typ'] = $dataIn->invoice_type;
                $dataArr['b2b'][$x]['inv'][$y]['rchrg'] = $dataIn->reverse_charge;
                $dataArr['b2b'][$x]['inv'][$y]['itms'][$z]['num'] = (int) $a;
                $dataArr['b2b'][$x]['inv'][$y]['itms'][$z]['itm_det']['rt'] = (float) $dataIn->rate;
                $dataArr['b2b'][$x]['inv'][$y]['itms'][$z]['itm_det']['txval'] = (float) $dataIn->taxable_subtotal;
                //echo $dataIn->supply_type;
                if ($dataIn->supply_type == 'INTER') {
                    $dataArr['b2b'][$x]['inv'][$y]['itms'][$z]['itm_det']['iamt'] = (float) $dataIn->igst_amount;
                } 
                else {
                    $dataArr['b2b'][$x]['inv'][$y]['itms'][$z]['itm_det']['samt'] = (float) $dataIn->sgst_amount;
                    $dataArr['b2b'][$x]['inv'][$y]['itms'][$z]['itm_det']['camt'] = (float) $dataIn->cgst_amount;
                }
                $dataArr['b2b'][$x]['inv'][$y]['itms'][$z]['itm_det']['csamt'] = (float) $dataIn->cess_amount;
                $ctin = $dataIn->billing_gstin_number;

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
        if(!empty($flag)) {
           $dataInvB2CL = $this->getAllInvoices($user_id, $returnmonth,'b2cl','1'); 
        }
        else {
           $dataInvB2CL = $this->getAllInvoices($user_id, $returnmonth,'b2cl','',$ids);
        }
        
        //$this->pr($dataInvB2CL);die;
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
                    $a=1;
                }
                if ($ctin != '' && $ctin != $dataIn->supply_place) {
                    $x++;
                    $y = 0;
                }
                $dataArr['b2cl'][$x]['pos'] = strlen($dataIn->supply_place) == '1' ? '0' . $dataIn->supply_place : $dataIn->supply_place;
                if(!empty($flag)) {
                   $dataArr['b2cl'][$x]['inv'][$y]['flag'] = $flag; 
                }
                $dataArr['b2cl'][$x]['inv'][$y]['inum'] = $dataIn->reference_number;
                $dataArr['b2cl'][$x]['inv'][$y]['idt'] = date('d-m-Y', strtotime($dataIn->invoice_date));
                $dataArr['b2cl'][$x]['inv'][$y]['val'] = (float) $dataIn->invoice_total_value;

                $dataArr['b2cl'][$x]['inv'][$y]['rchrg'] = $dataIn->reverse_charge;

                $dataArr['b2cl'][$x]['inv'][$y]['itms'][$z]['num'] = (int) $a;
                $dataArr['b2cl'][$x]['inv'][$y]['itms'][$z]['itm_det']['rt'] = (float) $dataIn->rate;
                $dataArr['b2cl'][$x]['inv'][$y]['itms'][$z]['itm_det']['txval'] = (float) $dataIn->taxable_subtotal;
                if ($dataIn->supply_type == 'INTER') {
                    $dataArr['b2cl'][$x]['inv'][$y]['itms'][$z]['itm_det']['iamt'] = (float) $dataIn->igst_amount;
                } 
                else {
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
        //$dataInvB2CS = $this->getB2CSInvoices($user_id, $returnmonth,$type,$ids);
        if(!empty($flag)) {
           $dataInvB2CS = $this->getAllInvoices($user_id, $returnmonth,'b2cs','1'); 
        }
        else {
           $dataInvB2CS = $this->getAllInvoices($user_id, $returnmonth,'b2cs','',$ids);
        }
        if (isset($dataInvB2CS) && !empty($dataInvB2CS)) {

            $x = 0;
            $y = 0;
            $z = 0;
            $a = 1;
            $temp_number = '';
            $ctin = '';
            foreach ($dataInvB2CS as $dataIn) {
                
                if ($ctin != '' && $ctin != $dataIn->billing_gstin_number) {
                    $x++;
                }
                if ($temp_number != '' && $temp_number != $dataIn->reference_number) {
                    $z = 0;
                    $y++;
                }
                if(!empty($flag)) {
                   $dataArr['b2cs'][$x]['flag'] = $flag; 
                }
                $dataArr['b2cs'][$x]['sply_ty'] = $dataIn->supply_type;
                $dataArr['b2cs'][$x]['rt'] = (float) $dataIn->rate;

                if(!empty($ecommerce_gstin_number)) {
                    $dataArr['b2cs'][$x]['etin'] = $dataIn->ecommerce_gstin_number;
                }
                $dataArr['b2cs'][$x]['typ'] = $dataIn->type;
                
                $dataArr['b2cs'][$x]['pos'] = strlen($dataIn->supply_place) == '1' ? '0' . $dataIn->supply_place : $dataIn->supply_place;

                $dataArr['b2cs'][$x]['txval'] = (float) $dataIn->taxable_subtotal;
                if ($dataIn->supply_type == 'INTER') {
                    $dataArr['b2cs'][$x]['iamt'] = (float) $dataIn->igst_amount;
                } 
                else {
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
        //$dataInvCDNR = $this->getCDNRInvoices($user_id, $returnmonth,$type,$ids);
        //$this->pr($dataInvCDNR);
        if(!empty($flag)) {
           $dataInvCDNR = $this->getAllInvoices($user_id, $returnmonth,'cdnr','1'); 
        }
        else {
           $dataInvCDNR = $this->getAllInvoices($user_id, $returnmonth,'cdnr','',$ids);
        }
        if (isset($dataInvCDNR) && !empty($dataInvCDNR)) {

            $x = 0;
            $y = 0;
            $z = 0;
            $a = 1;
            $temp_number = '';
            $ctin = '';
            foreach ($dataInvCDNR as $dataIn) {
                if ($temp_number != '' && $temp_number != $dataIn->reference_number) {
                    $z = 0;
                    $y++;
                    $a=1;
                }
                if ($ctin != '' && $ctin != $dataIn->billing_gstin_number) {
                    $z = 0;
                    $y=0;
                    $x++;
                }

                $dataArr['cdnr'][$x]['ctin'] = $dataIn->billing_gstin_number;
                if(!empty($flag)) {
                   $dataArr['cdnr'][$x]['nt'][$y]['flag'] = $flag; 
                }
                $dataArr['cdnr'][$x]['nt'][$y]['ntty'] = $dataIn->document_type;
                $dataArr['cdnr'][$x]['nt'][$y]['nt_num'] =$dataIn->original_invoice_number;
                $dataArr['cdnr'][$x]['nt'][$y]['nt_dt'] = $dataIn->original_invoice_date;

                $dataArr['cdnr'][$x]['nt'][$y]['p_gst'] = $dataIn->pre_gst;
                $dataArr['cdnr'][$x]['nt'][$y]['rsn'] = $dataIn->reason_issuing_document;//"02-Post Sale Discount";
                $dataArr['cdnr'][$x]['nt'][$y]['inum'] = $dataIn->reference_number;
                $dataArr['cdnr'][$x]['nt'][$y]['idt'] = date('d-m-Y', strtotime($dataIn->invoice_date));
                $dataArr['cdnr'][$x]['nt'][$y]['val'] = (float) $dataIn->invoice_total_value;
                $dataArr['cdnr'][$x]['nt'][$y]['itms'][$z]['num'] = (int) $a;
                $dataArr['cdnr'][$x]['nt'][$y]['itms'][$z]['itm_det']['rt'] = (float) $dataIn->rate;
                $dataArr['cdnr'][$x]['nt'][$y]['itms'][$z]['itm_det']['txval'] = (float) $dataIn->taxable_subtotal;
                if ($dataIn->supply_type == 'INTER') {
                    $dataArr['cdnr'][$x]['nt'][$y]['itms'][$z]['itm_det']['iamt'] = (float) $dataIn->igst_amount;
                } else {
                    $dataArr['cdnr'][$x]['nt'][$y]['itms'][$z]['itm_det']['samt'] = (float) $dataIn->sgst_amount;
                    $dataArr['cdnr'][$x]['nt'][$y]['itms'][$z]['itm_det']['camt'] = (float) $dataIn->cgst_amount;
                }
                $dataArr['cdnr'][$x]['nt'][$y]['itms'][$z]['itm_det']['csamt'] = (float) $dataIn->cess_amount;
                $z++;
                $temp_number = $dataIn->reference_number;
                $ctin = $dataIn->billing_gstin_number;
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
        //$dataInvCDNUR = $this->getCDNURInvoices($user_id, $returnmonth,$type,$ids);
        if(!empty($flag)) {
           $dataInvCDNUR = $this->getAllInvoices($user_id, $returnmonth,'cdnur','1'); 
        }
        else {
           $dataInvCDNUR = $this->getAllInvoices($user_id, $returnmonth,'cdnur','',$ids);
        }
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

                $dataArr['cdnur'][$x]['typ'] = $dataIn->ur_type;
                if(!empty($flag)) {
                   $dataArr['cdnur'][$x]['flag'] = $flag; 
                }
                $dataArr['cdnur'][$x]['ntty'] = $dataIn->document_type;
                $dataArr['cdnur'][$x]['nt_num'] =$dataIn->original_invoice_number;
                $dataArr['cdnur'][$x]['nt_dt'] = $dataIn->pre_gst;
                $dataArr['cdnur'][$x]['p_gst'] = $dataIn->pre_gst;
                $dataArr['cdnur'][$x]['rsn'] = $dataIn->reason_issuing_document;//"02-Post Sale Discount";
                $dataArr['cdnur'][$x]['inum'] = $dataIn->reference_number;
                $dataArr['cdnur'][$x]['idt'] = date('d-m-Y', strtotime($dataIn->invoice_date));
                $dataArr['cdnur'][$x]['val'] = (float) $dataIn->invoice_total_value;
                $dataArr['cdnur'][$x]['itms'][$y]['num'] = (int) $a;
                $dataArr['cdnur'][$x]['itms'][$y]['itm_det']['rt'] = (float) $dataIn->rate;
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
                    if(!empty($flag)) {
                       $dataArr['hsn']['flag'] = $flag; 
                    }
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
        //$dataInvAt = $this->getATInvoices($user_id, $returnmonth,$type,$ids);
        //$this->pr($dataInvAt);
        if(!empty($flag)) {
           $dataInvAt = $this->getAllInvoices($user_id, $returnmonth,'at','1'); 
        }
        else {
           $dataInvAt = $this->getAllInvoices($user_id, $returnmonth,'at','',$ids);
        }
        if (isset($dataInvAt) && !empty($dataInvAt)) {
            $z = 0;
            $y = 0;
            $a = 1;
            $at_pos = '';
            $at_rate = '';
            foreach ($dataInvAt as $dataIn) {
                if ($at_pos != '' && $at_pos != $dataIn->supply_place) {
                    $y++;
                    $z = 0;
                }
                if(!empty($flag)) {
                   $dataArr['at'][$y]['flag'] = $flag; 
                }
                $dataArr['at'][$y]['pos'] = (strlen($dataIn->supply_place) == '1') ? '0' . $dataIn->supply_place : $dataIn->supply_place;
                $dataArr['at'][$y]['sply_ty'] = $dataIn->supply_type;

                if ($dataIn->supply_type == 'INTER') {
                    $dataArr['at'][$y]['itms'][$z]['iamt'] = (float) $dataIn->igst_amount;
                } 
                else {
                    $dataArr['at'][$y]['itms'][$z]['iamt'] = (float) $dataIn->igst_amount;
                    $dataArr['at'][$y]['itms'][$z]['samt'] = (float) $dataIn->sgst_amount;
                    $dataArr['at'][$y]['itms'][$z]['camt'] = (float) $dataIn->cgst_amount;
                }

                $dataArr['at'][$y]['itms'][$z]['rt'] = (float) $dataIn->rate;
                $dataArr['at'][$y]['itms'][$z]['ad_amt'] = (float) $dataIn->taxable_subtotal;
                
                $dataArr['at'][$y]['itms'][$z]['csamt'] = (float) $dataIn->cess_amount;
                $at_pos = $dataIn->supply_place;
                $at_rate = $dataIn->rate;
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

    public function getTXPDPayload($user_id, $returnmonth,$flag='',$ids='',$type='') {
        $dataArr = $response = $txpd_array = $txpd_ids = array();
         if(!empty($flag)) {
           $dataInvTXPD = $this->getAllInvoices($user_id, $returnmonth,'atadj','1'); 
        }
        else {
           $dataInvTXPD = $this->getAllInvoices($user_id, $returnmonth,'atadj','',$ids);
        }
        if (isset($dataInvTXPD) && !empty($dataInvTXPD)) {
            $z = 0;
            $y = 0;
            $a = 1;
            $at_pos = '';
            $at_rate = '';
            foreach ($dataInvTXPD as $dataIn) {
                if ($at_pos != '' && $at_pos != $dataIn->supply_place) {
                    $y++;
                    $z = 0;
                }
                if(!empty($flag)) {
                   $dataArr['txpd'][$y]['flag'] = $flag; 
                }
                $dataArr['txpd'][$y]['pos'] = (strlen($dataIn->supply_place) == '1') ? '0' . $dataIn->supply_place : $dataIn->supply_place;
                $dataArr['txpd'][$y]['sply_ty'] = $dataIn->supply_type;

                if ($dataIn->supply_type == 'INTER') {
                    $dataArr['txpd'][$y]['itms'][$z]['iamt'] = (float) $dataIn->igst_amount;
                } 
                else {
                    $dataArr['txpd'][$y]['itms'][$z]['iamt'] = (float) $dataIn->igst_amount;
                    $dataArr['txpd'][$y]['itms'][$z]['samt'] = (float) $dataIn->sgst_amount;
                    $dataArr['txpd'][$y]['itms'][$z]['camt'] = (float) $dataIn->cgst_amount;
                }

                $dataArr['txpd'][$y]['itms'][$z]['rt'] = (float) $dataIn->rate;
                $dataArr['txpd'][$y]['itms'][$z]['ad_amt'] = (float) $dataIn->taxable_subtotal;
                
                $dataArr['txpd'][$y]['itms'][$z]['csamt'] = (float) $dataIn->cess_amount;
                $at_pos = $dataIn->supply_place;
                $at_rate = $dataIn->rate;
                $z++;
                $txpd_array[] = (array) $dataIn;
            }
            if (!empty($txpd_array)) {
                $txpd_ids = array_unique(array_column($txpd_array, 'invoice_id'));
            }
        }
        $response['txpd_ids'] = $txpd_ids;
        $response['txpd_arr'] = $dataArr;
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
                if(!empty($flag)) {
                   $dataArr['nill'][0]['flag'] = $flag; 
                }
                
            }
        }
        $response['nil_ids'] = $nil_ids;
        $response['nil_arr'] = $dataArr;
        //$this->pr($response);die;
        return $response;
    }

    public function getEXPPayload($user_id, $returnmonth,$flag='',$ids='',$type='') {
        $dataArr = $response = $exp_ids = $exp_array = $dataArr1 = $dataArr2 = array();
        //$dataInvExp = $this->getEXPInvoices($user_id, $returnmonth,$type,$ids);
        if(!empty($flag)) {
           $dataInvExp = $this->getAllInvoices($user_id, $returnmonth,'exp','1'); 
        }
        else {
           $dataInvExp = $this->getAllInvoices($user_id, $returnmonth,'exp','',$ids);
        }
        // $this->pr($dataInvExp);
        if (isset($dataInvExp) && !empty($dataInvExp)) {
            $x = 0;
            $y = 0;
            $z = 0;
            $temp_number = '';
            foreach ($dataInvExp as $key => $dataIn) {
                //$this->pr($dataIn);
                if ($temp_number != '' && $temp_number != $dataIn->reference_number) {
                    $z = 0;
                    $y++;
                }
                $dataArr2['exp_typ'] = $dataIn->invoice_type;
                if(!empty($flag)) {
                   $dataArr2['inv'][$y]['flag'] = $flag; 
                }
                $dataArr2['inv'][$y]['inum'] = $dataIn->reference_number;
                $dataArr2['inv'][$y]['idt'] = date('d-m-Y', strtotime($dataIn->invoice_date));
                $dataArr2['inv'][$y]['val'] = (float) $dataIn->invoice_total_value;
                if(!empty($dataIn->port_code)) {
                    $dataArr2['inv'][$y]['sbpcode'] = $dataIn->port_code;
                }
                if(!empty($dataIn->shipping_bill_number)) {
                    $dataArr2['inv'][$y]['sbnum'] = (int)$dataIn->shipping_bill_number;
                }
                if(!empty($dataIn->shipping_bill_date)) {
                    $dataArr2['inv'][$y]['sbdt'] = $dataIn->shipping_bill_date > 0 ? date('d-m-Y', strtotime($dataIn->shipping_bill_date)) : '';
                }
                $dataArr2['inv'][$y]['itms'][$z]['txval'] = (float) $dataIn->taxable_subtotal;
                $dataArr2['inv'][$y]['itms'][$z]['rt'] = (float) $dataIn->rate;
                //$dataArr2['inv'][$y]['itms'][$z]['iamt'] = (float) $dataIn->igst_amount;
                $temp_number = $dataIn->reference_number;
                $z++;
                $exp_array[] = (array) $dataIn;
            }
           
            if (!empty($exp_array)) {
                $exp_ids = array_unique(array_column($exp_array, 'invoice_id'));
            }

            $x = 0;
            
            /*if (!empty($dataArr1)) {
                $dataArr['exp'][$x] = $dataArr1;
                $x++;
            }*/
            if (!empty($dataArr2)) {
                $dataArr['exp'][$x] = $dataArr2;
                $x++;
            }
        }
        $response['exp_ids'] = $exp_ids;
        $response['exp_arr'] = $dataArr;
        /*$this->pr($dataArr);
        die;*/
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
                    $counterflag=0;
                    foreach ($arr_value as $key => $Item) {
                        if(!empty($Item->from) && !empty($Item->to)) {
                            $counterflag=1;
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
                    if($counterflag==1)
                    {
                        $i++;
                    }
                }
                if(!empty($dataS)) {
                    $dataInvDoc['doc_issue']['doc_det']  = $dataS;
                    if(!empty($flag)) {
                       $dataInvDoc['doc_issue']['flag'] = $flag; 
                    }
                }
            }
            
        }       
        /*$this->pr($dataInvDoc); 
        die;*/
        $response['doc_ids'] = $doc_ids;
        $response['doc_arr'] = $dataInvDoc;

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
