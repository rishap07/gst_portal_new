<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

final class gstr2 extends validation {

    function __construct() {
        parent::__construct();
    }
	
	public function downloadGSTR2() {

        $response_b2b = $response_cdn = '';
		$flag = 0;
		$obj_api = new gstr();
		$dataUpdate = $dataUpdate1 = array();

        $gstr2ReturnMonth = isset($_POST['gstr2ReturnMonth']) ? $_POST['gstr2ReturnMonth'] : '';
		if (empty($gstr2ReturnMonth)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }
        $msg = array();
        $response_b2b = $obj_api->returnSummary($gstr2ReturnMonth, 'B2B', 'gstr2a');
		if ($response_b2b == true) {

			$flag = 1;
            /***** Start Code Insert/update to summary *****/
            $savedata['json'] = base64_encode(serialize($response_b2b));
            $obj_api->save_user_summary($savedata,'gstr2aB2B',$gstr2ReturnMonth);
            /***** End Code Insert/update to summary *****/
            

        }
        else {
            if(isset($_SESSION['error']) && !empty($_SESSION['error'])) {
                unset($_SESSION['error']);
                $msg[] = 'B2B Invoices are not found for the provided Inputs ';
            }
        }
		
		$response_cdn = $obj_api->returnSummary($gstr2ReturnMonth, 'CDN', 'gstr2a');
		if ($response_cdn == true) {
            
			$flag = 1;
            /***** Start Code Insert/update to summary *****/
            $savedata['json'] = base64_encode(serialize($response_cdn));
            $obj_api->save_user_summary($savedata,'gstr2aCDN',$gstr2ReturnMonth);
            /***** End Code Insert/update to summary *****/

        }
        else {
            if(isset($_SESSION['error']) && !empty($_SESSION['error'])) {
                unset($_SESSION['error']);
                $msg[] = 'CDN Invoices are not found for the provided Inputs ';
            }
        }

        if(isset($msg) && !empty($msg)) {
            $this->setError($msg);
        }

		if($flag == 0) {
            return false;
        }

		if(!empty($response_b2b) || !empty($response_cdn)) {
			
			$jstrb2b_array = json_decode($response_b2b, true);
            $jstrcdn_array = json_decode($response_cdn, true);
			//$this->pr($jstrb2b_array);
			//$this->pr($jstrcdn_array);
			//die;

			if (isset($jstrb2b_array['b2b'])) {

				$x = 0;
				foreach ($jstrb2b_array['b2b'] as $key1 => $inv_value) {
					
					if (isset($inv_value['inv'])) {

						$ctin = isset($inv_value['ctin']) ? $inv_value['ctin'] : '';

						foreach ($inv_value['inv'] as $key2 => $jstr1_value) {
							
							$val = isset($jstr1_value['val']) ? $jstr1_value['val'] : 0;
                            $itms = isset($jstr1_value['itms']) ? $jstr1_value['itms'] : array();
                            $inv_typ = isset($jstr1_value['inv_typ']) ? $jstr1_value['inv_typ'] : '';
                            $pos = isset($jstr1_value['pos']) ? $jstr1_value['pos'] : 0;
                            $updby = isset($jstr1_value['updby']) ? $jstr1_value['updby'] : '';
                            $rchrg = isset($jstr1_value['rchrg']) ? $jstr1_value['rchrg'] : '';
                            $inum = isset($jstr1_value['inum']) ? $jstr1_value['inum'] : '';
                            $chksum = isset($jstr1_value['chksum']) ? $jstr1_value['chksum'] : '';

                            $nt_num = isset($jstr1_value['nt_num']) ? $jstr1_value['nt_num'] : '';
                            $rsn = isset($jstr1_value['rsn']) ? $jstr1_value['rsn'] : 0;

                            $idt = isset($jstr1_value['idt']) ? $jstr1_value['idt'] : '';
                            $nt_dt = isset($jstr1_value['nt_dt']) ? $jstr1_value['nt_dt'] : '';
                            $p_gst = isset($jstr1_value['p_gst']) ? $jstr1_value['p_gst'] : '';
                            $ntty = isset($jstr1_value['ntty']) ? $jstr1_value['ntty'] : '';

                            if (!empty($itms)) {
								
								$i = 0;
								foreach ($itms as $key3 => $value) {
									
									$num = isset($value['num']) ? $value['num'] : 0;
                                    $csamt = isset($value['itm_det']['csamt']) ? $value['itm_det']['csamt'] : 0;
                                    $rt = isset($value['itm_det']['rt']) ? $value['itm_det']['rt'] : 0;
                                    $txval = isset($value['itm_det']['txval']) ? $value['itm_det']['txval'] : 0;
                                    $iamt = isset($value['itm_det']['iamt']) ? $value['itm_det']['iamt'] : 0;
                                    $samt = isset($value['itm_det']['samt']) ? $value['itm_det']['samt'] : 0;
                                    $camt = isset($value['itm_det']['camt']) ? $value['itm_det']['camt'] : 0;

                                    $dataUpdate[$x][$i]['type'] = 'B2B';
                                    $dataUpdate[$x][$i]['reference_number'] = $inum;
                                    $dataUpdate[$x][$i]['invoice_date'] = isset($idt) ? date('Y-m-d', strtotime($idt)) : '';

                                    $dataUpdate[$x][$i]['invoice_total_value'] = $val;
                                    $dataUpdate[$x][$i]['total_taxable_subtotal'] = $txval;
                                    $dataUpdate[$x][$i]['company_gstin_number'] = $ctin;
                                    $dataUpdate[$x][$i]['inv_typ'] = $inv_typ;
                                    $dataUpdate[$x][$i]['total_cgst_amount'] = $camt;
                                    $dataUpdate[$x][$i]['total_sgst_amount'] = $samt;

                                    $dataUpdate[$x][$i]['total_igst_amount'] = $iamt;
                                    $dataUpdate[$x][$i]['total_cess_amount'] = $csamt;
                                    $dataUpdate[$x][$i]['rchrg'] = $rchrg;

                                    $dataUpdate[$x][$i]['rate'] = $rt;
                                    $dataUpdate[$x][$i]['pos'] = $pos;
                                    $dataUpdate[$x][$i]['itms'] = $num;
                                    $dataUpdate[$x][$i]['chksum'] = $chksum;
                                    $dataUpdate[$x][$i]['nt_num'] = $nt_num;

                                    $dataUpdate[$x][$i]['nt_dt'] = isset($nt_dt) ? date('Y-m-d', strtotime($nt_dt)) : '';
                                    $dataUpdate[$x][$i]['p_gst'] = $p_gst;
                                    $dataUpdate[$x][$i]['ntty'] = $ntty;
                                    $dataUpdate[$x][$i]['rsn'] = $rsn;
                                    $dataUpdate[$x][$i]['financial_month'] = $gstr2ReturnMonth;
                                    $dataUpdate[$x][$i]['added_by'] = $_SESSION['user_detail']['user_id'];
                                    $dataUpdate[$x][$i]['added_date'] = date('Y-m-d h:i:s');
                                    $i++;
                                }
                            }
                            $x++;
                        }
                    }
                }
            }

            if (!empty($jstrcdn_array)) {
                
				$x = 0;
				foreach ($jstrcdn_array['cdn'] as $key1 => $inv_value) {

					$cfs = isset($inv_value['cfs']) ? $inv_value['cfs'] : '';
                    $nt = isset($inv_value['nt']) ? $inv_value['nt'] : array();
                    $ctin = isset($inv_value['ctin']) ? $inv_value['ctin'] : '';

					if (isset($nt) && !empty($nt)) {
						
						$y = 0;
                        foreach ($nt as $key2 => $jstr1_value) {

                            $val = isset($jstr1_value['val']) ? $jstr1_value['val'] : 0;
                            $itms = isset($jstr1_value['itms']) ? $jstr1_value['itms'] : array();
                            $updby = isset($jstr1_value['updby']) ? $jstr1_value['updby'] : '';
                            $nt_num = isset($jstr1_value['nt_num']) ? $jstr1_value['nt_num'] : '';
                            $inum = isset($jstr1_value['inum']) ? $jstr1_value['inum'] : '';
                            $rsn = isset($jstr1_value['rsn']) ? $jstr1_value['rsn'] : 0;

                            $idt = isset($jstr1_value['idt']) ? $jstr1_value['idt'] : '';
                            $nt_dt = isset($jstr1_value['nt_dt']) ? $jstr1_value['nt_dt'] : '';
                            $p_gst = isset($jstr1_value['p_gst']) ? $jstr1_value['p_gst'] : '';
                            $ntty = isset($jstr1_value['ntty']) ? $jstr1_value['ntty'] : '';
                            $rchrg = isset($jstr1_value['rchrg']) ? $jstr1_value['rchrg'] : '';
                            $chksum = isset($jstr1_value['chksum']) ? $jstr1_value['chksum'] : '';
                            $inv_typ = isset($jstr1_value['inv_typ']) ? $jstr1_value['inv_typ'] : '';
                            $pos = isset($jstr1_value['pos']) ? $jstr1_value['pos'] : 0;

                            if (!empty($itms)) {
								
								$i = 0;
								foreach ($itms as $key3 => $value) {
									
									$num = isset($value['num']) ? $value['num'] : 0;
                                    $csamt = isset($value['itm_det']['csamt']) ? $value['itm_det']['csamt'] : 0;
                                    $rt = isset($value['itm_det']['rt']) ? $value['itm_det']['rt'] : 0;
                                    $txval = isset($value['itm_det']['txval']) ? $value['itm_det']['txval'] : 0;
                                    $iamt = isset($value['itm_det']['iamt']) ? $value['itm_det']['iamt'] : 0;
                                    $samt = isset($value['itm_det']['samt']) ? $value['itm_det']['samt'] : 0;
                                    $camt = isset($value['itm_det']['camt']) ? $value['itm_det']['camt'] : 0;

                                    $dataUpdate1[$y][$i]['type'] = 'CDN';
                                    $dataUpdate1[$y][$i]['reference_number'] = $nt_num;
                                    $dataUpdate1[$y][$i]['invoice_date'] = isset($nt_dt) > 0 ? date('Y-m-d', strtotime($nt_dt)) : '';

                                    $dataUpdate1[$y][$i]['invoice_total_value'] = $val;
                                    $dataUpdate1[$y][$i]['total_taxable_subtotal'] = $txval;
                                    $dataUpdate1[$y][$i]['company_gstin_number'] = $ctin;
                                    $dataUpdate1[$y][$i]['inv_typ'] = $inv_typ;
                                    $dataUpdate1[$y][$i]['total_cgst_amount'] = $camt;
                                    $dataUpdate1[$y][$i]['total_sgst_amount'] = $samt;

                                    $dataUpdate1[$y][$i]['total_igst_amount'] = $iamt;
                                    $dataUpdate1[$y][$i]['total_cess_amount'] = $csamt;
                                    $dataUpdate1[$y][$i]['rchrg'] = $rchrg;

                                    $dataUpdate1[$y][$i]['rate'] = $rt;
                                    $dataUpdate1[$y][$i]['pos'] = $pos;
                                    $dataUpdate1[$y][$i]['itms'] = $num;
                                    $dataUpdate1[$y][$i]['chksum'] = $chksum;

                                    $dataUpdate1[$y][$i]['nt_num'] = $inum;
                                    $dataUpdate1[$y][$i]['nt_dt'] = isset($idt) ? date('Y-m-d', strtotime($idt)) : '';
                                    $dataUpdate1[$y][$i]['p_gst'] = $p_gst;
                                    $dataUpdate1[$y][$i]['ntty'] = $ntty;
                                    $dataUpdate1[$y][$i]['rsn'] = $rsn;
                                    $dataUpdate1[$y][$i]['financial_month'] = $gstr2ReturnMonth;
                                    $dataUpdate1[$y][$i]['added_by'] = $_SESSION['user_detail']['user_id'];
                                    $dataUpdate1[$y][$i]['added_date'] = date('Y-m-d h:i:s');
									$i++;
                                }
                            }
                            $y++;
                        }
                    }
                    $x++;
                }
                $dataUpdate = array_merge($dataUpdate, $dataUpdate1);
            }

            $data = $data1 = array();
            $y = 0;
            $data = array_reduce($dataUpdate, 'array_merge', $data1);

            if (!empty($data)) {

                $flagfailed = 0;
				
				/* Delete Existing GSTR2A Invoices */
				$dataConditionArray['financial_month'] = $gstr2ReturnMonth;
				$dataConditionArray['added_by'] = $this->sanitize($_SESSION['user_detail']['user_id']);
				$this->deletData($this->tableNames['gstr2_reconcile_final'], $dataConditionArray);
				$this->deletData($this->tableNames['client_reconcile_purchase_invoice1'], $dataConditionArray);
				$this->logMsg("GSTR2A Invoices deleted for return period : " . $gstr2ReturnMonth . " by User ID : " . $this->sanitize($_SESSION['user_detail']['user_id']) . ".", "gstr2a_deleted");

                if (!empty($data)) {
                    if (!$this->insertMultiple($this->getTableName('client_reconcile_purchase_invoice1'), $data)) {
                        $flagfailed = 1;
                    }
                }

                if ($flagfailed == 1) {
                    $this->setError('GSTR2A Download Failed');
                    return false;
                } 
                else {
                    $this->setSuccess('GSTR2A Download Successfully');
                    return true;
                }
            }
        } 
        else {
            return false;
        }
    }

    public function checkUserInvoices($user_id, $returnmonth = '', $type = '') {
        $sql = "select * from " . $this->getTableName('client_reconcile_purchase_invoice1') . " where 1=1 AND added_by='" . $user_id . "' and financial_month='" . $returnmonth . "' ";
        if (!empty($type)) {
            $sql .= " and type='" . $type . "' ";
        }
        //echo  $sql;
        $clientdata = $this->get_results($sql);
        return $clientdata;
    }

    public function startGstr2() {
        $sql = "select * from " . TAB_PREFIX . "return where client_id='" . $_SESSION['user_detail']['user_id'] . "' and return_month='" . $_GET["returnmonth"] . "' and type='gstr2'";

        $clientdata = $this->get_results($sql);

        if (empty($clientdata)) {

            $dataArr['return_month'] = $this->sanitize($_GET['returnmonth']);
            $dataArr['type'] = 'gstr2';
            $dataArr['client_id'] = $_SESSION['user_detail']['user_id'];
            $year = $this->generateFinancialYear();
            $dataArr['financial_year'] = $year;
            $dataArr['status'] = 1;

            if ($this->insert(TAB_PREFIX . 'return', $dataArr)) {
                //$this->setSuccess('GSTR2 Saved Successfully');
                $this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . "Initiated the GSTR2 Filling", "gstr2");

                return true;
            } else {
                $this->setError('Failed to save GSTR2 data');
                return false;
            }
        }
    }

    public function gstr2Upload() {
        //Purchase Data;
        $dataQuery = "select re.id,pur.supplier_billing_gstin_number as gstin_number,re.reference_number,re.invoice_status,re.status,sum(pur_it.cgst_amount) as cgst_amount,sum(pur_it.sgst_amount) as sgst_amount,sum(pur_it.igst_amount) as igst_amount,sum(pur_it.cess_amount) as cess_amount,pur.invoice_total_value,re.invoice_date,re.invoice_status,re.status from " . $this->getTableName('client_reconcile_purchase_invoice1') . " re inner join " . $this->getTableName('client_purchase_invoice') . " pur on re.reference_number=pur.reference_number inner join " . $this->getTableName('client_purchase_invoice_item') . " pur_it on pur.purchase_invoice_id=pur_it.purchase_invoice_id where re.invoice_date like('%" . $this->sanitize($_GET['returnmonth']) . "%') and re.added_by='" . $_SESSION['user_detail']['user_id'] . "' and ((re.invoice_status='0' and re.status='3')or(re.invoice_status='2' and re.status='1')or(re.invoice_status='2' and re.status='2')or(re.invoice_status='2' and re.status='3')or(re.invoice_status='2' and re.status='4')or(re.invoice_status='3' and re.status='3')) and re.is_uploaded='0' group by pur.reference_number  ";
        $dataPur = $this->get_results($dataQuery);
        //Sales Data;
        $dataQuery = "select re.id,pur.billing_gstin_number as gstin_number,re.reference_number,re.invoice_status,re.status,sum(pur_it.cgst_amount) as cgst_amount,sum(pur_it.sgst_amount) as sgst_amount,sum(pur_it.igst_amount) as igst_amount,sum(pur_it.cess_amount) as cess_amount,pur.invoice_total_value,re.invoice_date,re.invoice_status,re.status from " . $this->getTableName('client_reconcile_purchase_invoice1') . " re inner join " . $this->getTableName('client_invoice') . " pur on re.reference_number=pur.reference_number inner join " . $this->getTableName('client_invoice_item') . " pur_it on pur.invoice_id=pur_it.invoice_id where  re.invoice_date like('%" . $this->sanitize($_GET['returnmonth']) . "%') and re.added_by='" . $_SESSION['user_detail']['user_id'] . "' and ((re.invoice_status='0' and re.status='1')or(re.invoice_status='0' and re.status='2')or(re.invoice_status='0' and re.status='4')or(re.invoice_status='1' and re.status='1')or(re.invoice_status='1' and re.status='2')or(re.invoice_status='1' and re.status='3')or(re.invoice_status='1' and re.status='4')or(re.invoice_status='3' and re.status='1')or(re.invoice_status='3' and re.status='2')or(re.invoice_status='3' and re.status='4')) and re.is_uploaded='0'  group by pur.reference_number ";
        $dataSale = $this->get_results($dataQuery);

        $data = array_merge($dataPur, $dataSale);
        if (!empty($data)) {
            foreach ($data as $da) {

                $da->added_by = $_SESSION['user_detail']['user_id'];
                $da->added_date = date('Y-m-d H:i:s');
                $id = $da->id;
                unset($da->id);
                $this->insert($this->getTableName('client_upload_gstr2'), $da);
                $this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . "Upload GSTR2 Data", "gstr2");

                $this->update($this->getTableName('client_reconcile_purchase_invoice1'), array('is_uploaded' => '1'), array('id' => $id));
                $this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . "upload reconcile purchase invoice data", "gstr2");

                $dataReturn = $this->get_results('select * from ' . $this->getTableName('return') . " where return_month='" . $this->sanitize($_GET['returnmonth']) . "' and type='gstr2'");
                if (!empty($dataReturn)) {
                    $this->update($this->getTableName('return'), array('status' => '2'), array('return_id' => $dataReturn[0]->return_id));
                    $this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . "Uploaded GSTR2 data", "gstr2");
                } else {
                    $dataRet['financial_year'] = $this->generateFinancialYear();
                    $dataRet['return_month'] = $this->sanitize($_GET['returnmonth']);
                    $dataRet['type'] = 'gstr2';
                    $dataRet['client_id'] = $_SESSION['user_detail']['user_id'];
                    $dataRet['status'] = '2';
                    $this->insert($this->getTableName('return'), $dataRet);
                    $this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . "Upload GSTR2 Data", "gstr2");
                }
            }
            $this->setSuccess('Invoice Uploaded Successfully');
            $this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . "uploaded GSTR2 Invoice", "gstr2");

            return true;
        } else {
            $this->setError('No Data to upload');
            return false;
        }
    }
	
	private function getPurchaseB2BInvoices($user_id, $returnmonth) {
        $queryB2B = "select i.purchase_invoice_id, i.recipient_shipping_gstin_number,i.supplier_billing_gstin_number,i.serial_number as invoice_number,i.invoice_date,i.invoice_total_value,i.supply_place,i.supply_type from gst_client_purchase_invoice as i where i.invoice_nature='purchaseinvoice' and i.added_by='" . $user_id . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and 
i.supplier_billing_gstin_number!='' and (i.invoice_type='taxinvoice' or i.invoice_type='sezunitinvoice' or i.invoice_type='importinvoice')  and i.invoice_nature='purchaseinvoice'  AND i.invoice_date like '%" . $returnmonth . "%'";
        return $this->get_results($queryB2B);
    }

    private function getPurchaseB2BInvoicesDetails($user_id, $returnmonth) {

        $queryB2B = "SELECT it.consolidate_rate as rateof_invoice,i.purchase_invoice_id,it.purchase_invoice_item_id,i.recipient_shipping_gstin_number,i.supplier_billing_gstin_number,i.serial_number as invoice_number,i.invoice_date,i.invoice_total_value,i.supply_place,i.supply_type,it.total,it.taxable_subtotal,it.igst_amount,it.cgst_amount,it.sgst_amount,it.cess_amount from gst_client_purchase_invoice as i INNER JOIN gst_client_purchase_invoice_item as it on it.purchase_invoice_id = i.purchase_invoice_id where i.invoice_nature='purchaseinvoice' and i.added_by='" . $user_id . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and 
i.supplier_billing_gstin_number!='' and (i.invoice_type='taxinvoice' or i.invoice_type='sezunitinvoice' or i.invoice_type='importinvoice')  and i.invoice_nature='purchaseinvoice'  AND i.invoice_date like '%" . $returnmonth . "%'";

        return $this->get_results($queryB2B);
    }

    private function getPurchaseB2clInvoices($user_id, $returnmonth) {
         $queryB2B = "select i.purchase_invoice_id, i.recipient_shipping_gstin_number,i.supplier_billing_gstin_number,i.serial_number as invoice_number,i.invoice_date,i.invoice_total_value,i.supply_place,i.supply_type from gst_client_purchase_invoice as i where i.invoice_nature='purchaseinvoice' and i.added_by='" . $user_id . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and 
i.supplier_billing_gstin_number='' and i.invoice_total_value>'250000' and (i.invoice_type='taxinvoice' or i.invoice_type='sezunitinvoice' or i.invoice_type='importinvoice')  and i.invoice_nature='purchaseinvoice'  AND i.invoice_date like '%" . $returnmonth . "%'";
        return $this->get_results($queryB2B);
    }

    private function getPurchaseB2clInvoicesDetails($user_id, $returnmonth) {

        $queryB2B = "SELECT it.consolidate_rate as rateof_invoice,i.purchase_invoice_id,it.purchase_invoice_item_id,i.recipient_shipping_gstin_number,i.supplier_billing_gstin_number,i.serial_number as invoice_number,i.invoice_date,i.invoice_total_value,i.supply_place,i.supply_type,it.total,it.taxable_subtotal,it.igst_amount,it.cgst_amount,it.sgst_amount,it.cess_amount from gst_client_purchase_invoice as i INNER JOIN gst_client_purchase_invoice_item as it on it.purchase_invoice_id = i.purchase_invoice_id where i.invoice_nature='purchaseinvoice' and i.added_by='" . $user_id . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and 
i.supplier_billing_gstin_number='' and i.invoice_total_value>'250000' and (i.invoice_type='taxinvoice' or i.invoice_type='sezunitinvoice' or i.invoice_type='importinvoice')  and i.invoice_nature='purchaseinvoice'  AND i.invoice_date like '%" . $returnmonth . "%'";
        return $this->get_results($queryB2B);
    }

    private function getPurchaseB2csmallInvoices($user_id, $returnmonth) {
        $queryB2B = "select i.purchase_invoice_id, i.recipient_shipping_gstin_number,i.supplier_billing_gstin_number,i.serial_number as invoice_number,i.invoice_date,i.invoice_total_value,i.supply_place,i.supply_type from gst_client_purchase_invoice as i where i.invoice_nature='purchaseinvoice' and i.added_by='" . $user_id . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and 
i.supplier_billing_gstin_number='' and i.invoice_total_value <= '250000' and (i.invoice_type='taxinvoice' or i.invoice_type='sezunitinvoice' or i.invoice_type='importinvoice')  and i.invoice_nature='purchaseinvoice'  AND i.invoice_date like '%" . $returnmonth . "%'";
        return $this->get_results($queryB2B);
    }

    private function getPurchaseB2csmallInvoicesDetails($user_id, $returnmonth) {

        $queryB2B = "SELECT it.consolidate_rate as rateof_invoice,i.purchase_invoice_id,it.purchase_invoice_item_id,i.recipient_shipping_gstin_number,i.supplier_billing_gstin_number,i.serial_number as invoice_number,i.invoice_date,i.invoice_total_value,i.supply_place,i.supply_type,it.total,it.taxable_subtotal,it.igst_amount,it.cgst_amount,it.sgst_amount,it.cess_amount from gst_client_purchase_invoice as i INNER JOIN gst_client_purchase_invoice_item as it on it.purchase_invoice_id = i.purchase_invoice_id where i.invoice_nature='purchaseinvoice' and i.added_by='" . $user_id . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and 
i.supplier_billing_gstin_number='' and i.invoice_total_value <= '250000' and (i.invoice_type='taxinvoice' or i.invoice_type='sezunitinvoice' or i.invoice_type='importinvoice')  and i.invoice_nature='purchaseinvoice'  AND i.invoice_date like '%" . $returnmonth . "%'";
        return $this->get_results($queryB2B);
    }

    private function getPurchaseImportInvoices($user_id, $returnmonth) {
         $queryB2B = "select i.purchase_invoice_id, i.recipient_shipping_gstin_number,i.supplier_billing_gstin_number,i.serial_number as invoice_number,i.invoice_date,i.invoice_total_value,i.supply_place,i.supply_type from gst_client_purchase_invoice as i where i.invoice_nature='purchaseinvoice' and i.added_by='" . $user_id . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and 
(i.invoice_type='deemedimportinvoice')  and i.invoice_nature='purchaseinvoice'  AND i.invoice_date like '%" . $returnmonth . "%'";
        return $this->get_results($queryB2B);
    }

    private function getPurchaseImportInvoicesDetails($user_id, $returnmonth) {

        $queryB2B = "SELECT it.consolidate_rate as rateof_invoice,i.purchase_invoice_id,it.purchase_invoice_item_id,i.recipient_shipping_gstin_number,i.supplier_billing_gstin_number,i.serial_number as invoice_number,i.invoice_date,i.invoice_total_value,i.supply_place,i.supply_type,it.total,it.taxable_subtotal,it.igst_amount,it.cgst_amount,it.sgst_amount,it.cess_amount from gst_client_purchase_invoice as i INNER JOIN gst_client_purchase_invoice_item as it on it.purchase_invoice_id = i.purchase_invoice_id where i.invoice_nature='purchaseinvoice' and i.added_by='" . $user_id . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and 
(i.invoice_type='deemedimportinvoice')  and i.invoice_nature='purchaseinvoice'  AND i.invoice_date like '%" . $returnmonth . "%'";
        return $this->get_results($queryB2B);
    }

    private function getPurchaseCdnrInvoices($user_id, $returnmonth) {
        $queryB2B = "select i.purchase_invoice_id, i.recipient_shipping_gstin_number,i.supplier_billing_gstin_number,i.serial_number as invoice_number,i.invoice_date,i.invoice_total_value,i.supply_place,i.supply_type from gst_client_purchase_invoice as i where i.invoice_nature='purchaseinvoice' and i.added_by='" . $user_id . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and 
i.supplier_billing_gstin_number!='' and (i.invoice_type='debitnote' or i.invoice_type='creditnote')  and i.invoice_nature='purchaseinvoice'  AND i.invoice_date like '%" . $returnmonth . "%'";
        return $this->get_results($queryB2B);
    }

    private function getPurchaseCdnrInvoicesDetails($user_id, $returnmonth) {

        $queryB2B = "SELECT it.consolidate_rate as rateof_invoice,i.purchase_invoice_id,it.purchase_invoice_item_id,i.recipient_shipping_gstin_number,i.supplier_billing_gstin_number,i.serial_number as invoice_number,i.invoice_date,i.invoice_total_value,i.supply_place,i.supply_type,it.total,it.taxable_subtotal,it.igst_amount,it.cgst_amount,it.sgst_amount,it.cess_amount from gst_client_purchase_invoice as i INNER JOIN gst_client_purchase_invoice_item as it on it.purchase_invoice_id = i.purchase_invoice_id where i.invoice_nature='purchaseinvoice' and i.added_by='" . $user_id . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and 
i.supplier_billing_gstin_number!='' and (i.invoice_type='debitnote' or i.invoice_type='creditnote') and i.invoice_nature='purchaseinvoice'  AND i.invoice_date like '%" . $returnmonth . "%'";
        return $this->get_results($queryB2B);
    }

    public function insertGstr2B2bInvoice($userid, $returnmonth) {
        $this->query("DELETE FROM gst_gstr2summary_purchase_invoice_item WHERE added_by='" . $this->sanitize($_SESSION["user_detail"]["user_id"]) . "' and financial_month='" . $returnmonth . "'");
        $this->query("DELETE FROM gst_gstr2summary_purchase_invoice WHERE added_by='" . $this->sanitize($_SESSION["user_detail"]["user_id"]) . "' and financial_month='" . $returnmonth . "'");
        $b2bdata = array();
        $b2bdata = $this->getPurchaseB2BInvoices($userid, $returnmonth);
        if (!empty($b2bdata)) {
            $flag = 0;
            $dataArr = array();
            $x = 0;
            foreach ($b2bdata as $data) {

                $dataArr[$x]["purchase_invoice_id"] = $data->purchase_invoice_id;
                $dataArr[$x]["company_gstin_number"] = $data->recipient_shipping_gstin_number;
                $dataArr[$x]["financial_month"] = $returnmonth;
                $dataArr[$x]["invoice_number"] = $data->invoice_number;
                $dataArr[$x]["invoice_date"] = $data->invoice_date;
                $dataArr[$x]["invoice_total_value"] = $data->invoice_total_value;
                $dataArr[$x]["placeof_supply"] = $data->supply_place;
                $dataArr[$x]["added_by"] = $this->sanitize($_SESSION["user_detail"]["user_id"]);
                $dataArr[$x]["added_date"] = date('Y-m-d H:i:s');
				$dataArr[$x]["invoice_type"] = 'B2B';
				


                if ($data->supply_place == 'normal') {
                    $dataArr[$x]["reverse_charge"] = 'N';
                } elseif ($data->supply_place == 'reversecharge') {
                    $dataArr[$x]["reverse_charge"] = 'Y';
                }
                $dataArr[$x]["supplier_billing_gstin_number"] = $data->supplier_billing_gstin_number;
                $x++;
            }
            if (!empty($dataArr)) {
                if ($this->insertMultiple('gst_gstr2summary_purchase_invoice', $dataArr)) {
                    $flag = 1;
                    if ($flag == 1) {
                        $this->setSuccess('GSTR2 B2B summary invoices added successfully');
                        $this->logMsg("User ID GSTR2 B2B invoice added : " . $_SESSION['user_detail']['user_id'], "gstr2_summary");
                        return true;
                    }
                } else {
                    $flag = 0;
                    $this->setError('Failed to save returnfile data');
                    return false;
                }
            }
        }
    }

    public function insertGstr2B2bInvoiceDetails($userid, $returnmonth) {
        $b2bdata = array();
        $b2bdata = $this->getPurchaseB2BInvoicesDetails($userid, $returnmonth);
        if (!empty($b2bdata)) {
            $flag = 0;
            $x = 0;
            $dataArr = array();
            foreach ($b2bdata as $data) {
                $dataArr[$x]["purchase_invoice_id"] = $data->purchase_invoice_id;
                $dataArr[$x]["purchase_invoice_item_id"] = $data->purchase_invoice_item_id;
                $dataArr[$x]["rateof_invoice"] = $data->rateof_invoice;
                $dataArr[$x]["tax_value"] = $data->taxable_subtotal;
                $dataArr[$x]["igst_amount"] = $data->igst_amount;
                $dataArr[$x]["cgst_amount"] = $data->cgst_amount;
                $dataArr[$x]["sgst_amount"] = $data->sgst_amount;
                $dataArr[$x]["cess_amount"] = $data->cess_amount;
                $dataArr[$x]["added_by"] = $this->sanitize($_SESSION["user_detail"]["user_id"]);
                $dataArr[$x]["added_date"] = date('Y-m-d H:i:s');
                $dataArr[$x]["financial_month"] = $returnmonth;
                $x++;
            }
            if (!empty($dataArr)) {
                if ($this->insertMultiple('gst_gstr2summary_purchase_invoice_item', $dataArr)) {
                    $flag = 1;
                    if ($flag == 1) {
                        $this->setSuccess('GSTR2 B2B summary invoice details added successfully');
                        $this->logMsg("User ID GSTR2 B2B invoice added : " . $_SESSION['user_detail']['user_id'], "gstr2_summary");
                        return true;
                    }
                } else {
                    $flag = 0;
                    $this->setError('Failed to save returnfile data');
                    return false;
                }
            }
        }
    }

    public function insertGstr2B2clInvoice($userid, $returnmonth) {
        $b2bdata = array();
        $b2bdata = $this->getPurchaseB2clInvoices($userid, $returnmonth);
        if (!empty($b2bdata)) {
            $flag = 0;
            $dataArr = array();
            $x = 0;
            foreach ($b2bdata as $data) {

                $dataArr[$x]["purchase_invoice_id"] = $data->purchase_invoice_id;
                $dataArr[$x]["company_gstin_number"] = $data->recipient_shipping_gstin_number;
                $dataArr[$x]["financial_month"] = $returnmonth;
                $dataArr[$x]["invoice_number"] = $data->invoice_number;
                $dataArr[$x]["invoice_date"] = $data->invoice_date;
                $dataArr[$x]["invoice_total_value"] = $data->invoice_total_value;
                $dataArr[$x]["placeof_supply"] = $data->supply_place;
                $dataArr[$x]["added_by"] = $this->sanitize($_SESSION["user_detail"]["user_id"]);
                $dataArr[$x]["added_date"] = date('Y-m-d H:i:s');
				$dataArr[$x]["invoice_type"] = 'B2CL';
			 if ($data->supply_place == 'normal') {
                    $dataArr[$x]["reverse_charge"] = 'N';
                } elseif ($data->supply_place == 'reversecharge') {
                    $dataArr[$x]["reverse_charge"] = 'Y';
                }
                $dataArr[$x]["supplier_billing_gstin_number"] = $data->supplier_billing_gstin_number;
                $x++;
            }
            if (!empty($dataArr)) {
                if ($this->insertMultiple('gst_gstr2summary_purchase_invoice', $dataArr)) {
                    $flag = 1;
                    if ($flag == 1) {
                        $this->setSuccess('GSTR2 B2B summary invoices added successfully');
                        $this->logMsg("User ID GSTR2 B2B invoice added : " . $_SESSION['user_detail']['user_id'], "gstr2_summary");
                        return true;
                    }
                } else {
                    $flag = 0;
                    $this->setError('Failed to save returnfile data');
                    return false;
                }
            }
        }
    }

    public function insertGstr2B2clInvoiceDetails($userid, $returnmonth) {
        $b2bdata = array();
        $b2bdata = $this->getPurchaseB2clInvoicesDetails($userid, $returnmonth);
        if (!empty($b2bdata)) {
            $flag = 0;
            $x = 0;
            $dataArr = array();
            foreach ($b2bdata as $data) {
                $dataArr[$x]["purchase_invoice_id"] = $data->purchase_invoice_id;
                $dataArr[$x]["purchase_invoice_item_id"] = $data->purchase_invoice_item_id;
                $dataArr[$x]["rateof_invoice"] = $data->rateof_invoice;
                $dataArr[$x]["tax_value"] = $data->taxable_subtotal;
                $dataArr[$x]["igst_amount"] = $data->igst_amount;
                $dataArr[$x]["cgst_amount"] = $data->cgst_amount;
                $dataArr[$x]["sgst_amount"] = $data->sgst_amount;
                $dataArr[$x]["cess_amount"] = $data->cess_amount;
                $dataArr[$x]["added_by"] = $this->sanitize($_SESSION["user_detail"]["user_id"]);
                $dataArr[$x]["added_date"] = date('Y-m-d H:i:s');
                $dataArr[$x]["financial_month"] = $returnmonth;
                $x++;
            }
            if (!empty($dataArr)) {
                if ($this->insertMultiple('gst_gstr2summary_purchase_invoice_item', $dataArr)) {
                    $flag = 1;
                    if ($flag == 1) {
                        $this->setSuccess('GSTR2 B2B summary invoice details added successfully');
                        $this->logMsg("User ID GSTR2 B2B invoice added : " . $_SESSION['user_detail']['user_id'], "gstr2_summary");
                        return true;
                    }
                } else {
                    $flag = 0;
                    $this->setError('Failed to save returnfile data');
                    return false;
                }
            }
        }
    }

    public function insertGstr2B2csmallInvoice($userid, $returnmonth) {
        $b2bdata = array();
        $b2bdata = $this->getPurchaseB2csmallInvoices($userid, $returnmonth);
        if (!empty($b2bdata)) {
            $flag = 0;
            $dataArr = array();
            $x = 0;
            foreach ($b2bdata as $data) {

                $dataArr[$x]["purchase_invoice_id"] = $data->purchase_invoice_id;
                $dataArr[$x]["company_gstin_number"] = $data->recipient_shipping_gstin_number;
                $dataArr[$x]["financial_month"] = $returnmonth;
                $dataArr[$x]["invoice_number"] = $data->invoice_number;
                $dataArr[$x]["invoice_date"] = $data->invoice_date;
                $dataArr[$x]["invoice_total_value"] = $data->invoice_total_value;
                $dataArr[$x]["placeof_supply"] = $data->supply_place;
                $dataArr[$x]["added_by"] = $this->sanitize($_SESSION["user_detail"]["user_id"]);
                $dataArr[$x]["added_date"] = date('Y-m-d H:i:s');
				$dataArr[$x]["invoice_type"] = 'B2CSMALL';
				


                if ($data->supply_place == 'normal') {
                    $dataArr[$x]["reverse_charge"] = 'N';
                } elseif ($data->supply_place == 'reversecharge') {
                    $dataArr[$x]["reverse_charge"] = 'Y';
                }
                $dataArr[$x]["supplier_billing_gstin_number"] = $data->supplier_billing_gstin_number;
                $x++;
            }
            if (!empty($dataArr)) {
                if ($this->insertMultiple('gst_gstr2summary_purchase_invoice', $dataArr)) {
                    $flag = 1;
                    if ($flag == 1) {
                        $this->setSuccess('GSTR2 B2B summary invoices added successfully');
                        $this->logMsg("User ID GSTR2 B2B invoice added : " . $_SESSION['user_detail']['user_id'], "gstr2_summary");
                        return true;
                    }
                } else {
                    $flag = 0;
                    $this->setError('Failed to save returnfile data');
                    return false;
                }
            }
        }
    }

    public function insertGstr2B2csmallInvoiceDetails($userid, $returnmonth) {
        $b2bdata = array();
        $b2bdata = $this->getPurchaseB2csmallInvoicesDetails($userid, $returnmonth);
        if (!empty($b2bdata)) {
            $flag = 0;
            $x = 0;
            $dataArr = array();
            foreach ($b2bdata as $data) {
                $dataArr[$x]["purchase_invoice_id"] = $data->purchase_invoice_id;
                $dataArr[$x]["purchase_invoice_item_id"] = $data->purchase_invoice_item_id;
                $dataArr[$x]["rateof_invoice"] = $data->rateof_invoice;
                $dataArr[$x]["tax_value"] = $data->taxable_subtotal;
                $dataArr[$x]["igst_amount"] = $data->igst_amount;
                $dataArr[$x]["cgst_amount"] = $data->cgst_amount;
                $dataArr[$x]["sgst_amount"] = $data->sgst_amount;
                $dataArr[$x]["cess_amount"] = $data->cess_amount;
                $dataArr[$x]["added_by"] = $this->sanitize($_SESSION["user_detail"]["user_id"]);
                $dataArr[$x]["added_date"] = date('Y-m-d H:i:s');
                $dataArr[$x]["financial_month"] = $returnmonth;
                $x++;
            }

            if (!empty($dataArr)) {

                if ($this->insertMultiple('gst_gstr2summary_purchase_invoice_item', $dataArr)) {
                    $flag = 1;
                    if ($flag == 1) {
                        $this->setSuccess('GSTR2 B2B summary invoice details added successfully');
                        $this->logMsg("User ID GSTR2 B2B invoice added : " . $_SESSION['user_detail']['user_id'], "gstr2_summary");
                        return true;
                    }
                } else {
                    $flag = 0;
                    $this->setError('Failed to save returnfile data');
                    return false;
                }
            }
        }
    }

    public function insertGstr2ImportInvoice($userid, $returnmonth) {
        $b2bdata = array();
        $b2bdata = $this->getPurchaseImportInvoices($userid, $returnmonth);
        if (!empty($b2bdata)) {
            $flag = 0;
            $dataArr = array();
            $x = 0;
            foreach ($b2bdata as $data) {

                $dataArr[$x]["purchase_invoice_id"] = $data->purchase_invoice_id;
                $dataArr[$x]["company_gstin_number"] = $data->recipient_shipping_gstin_number;
                $dataArr[$x]["financial_month"] = $returnmonth;
                $dataArr[$x]["invoice_number"] = $data->invoice_number;
                $dataArr[$x]["invoice_date"] = $data->invoice_date;
                $dataArr[$x]["invoice_total_value"] = $data->invoice_total_value;
                $dataArr[$x]["placeof_supply"] = $data->supply_place;
                $dataArr[$x]["added_by"] = $this->sanitize($_SESSION["user_detail"]["user_id"]);
                $dataArr[$x]["added_date"] = date('Y-m-d H:i:s');
				$dataArr[$x]["invoice_type"] = 'IMPORT';


                if ($data->supply_place == 'normal') {
                    $dataArr[$x]["reverse_charge"] = 'N';
                } elseif ($data->supply_place == 'reversecharge') {
                    $dataArr[$x]["reverse_charge"] = 'Y';
                }
                $dataArr[$x]["supplier_billing_gstin_number"] = $data->supplier_billing_gstin_number;
                $x++;
            }
            if (!empty($dataArr)) {
                if ($this->insertMultiple('gst_gstr2summary_purchase_invoice', $dataArr)) {
                    $flag = 1;
                    if ($flag == 1) {
                        $this->setSuccess('GSTR2 B2B summary invoices added successfully');
                        $this->logMsg("User ID GSTR2 B2B invoice added : " . $_SESSION['user_detail']['user_id'], "gstr2_summary");
                        return true;
                    }
                } else {
                    $flag = 0;
                    $this->setError('Failed to save returnfile data');
                    return false;
                }
            }
        }
    }

    public function insertGstr2ImportInvoiceDetails($userid, $returnmonth) {
        $b2bdata = array();
        $b2bdata = $this->getPurchaseImportInvoicesDetails($userid, $returnmonth);
        if (!empty($b2bdata)) {
            $flag = 0;
            $x = 0;
            $dataArr = array();
            foreach ($b2bdata as $data) {
                $dataArr[$x]["purchase_invoice_id"] = $data->purchase_invoice_id;
                $dataArr[$x]["purchase_invoice_item_id"] = $data->purchase_invoice_item_id;
                $dataArr[$x]["rateof_invoice"] = $data->rateof_invoice;
                $dataArr[$x]["tax_value"] = $data->taxable_subtotal;
                $dataArr[$x]["igst_amount"] = $data->igst_amount;
                $dataArr[$x]["cgst_amount"] = $data->cgst_amount;
                $dataArr[$x]["sgst_amount"] = $data->sgst_amount;
                $dataArr[$x]["cess_amount"] = $data->cess_amount;
                $dataArr[$x]["added_by"] = $this->sanitize($_SESSION["user_detail"]["user_id"]);
                $dataArr[$x]["added_date"] = date('Y-m-d H:i:s');
                $dataArr[$x]["financial_month"] = $returnmonth;
                $x++;
            }

            if (!empty($dataArr)) {

                if ($this->insertMultiple('gst_gstr2summary_purchase_invoice_item', $dataArr)) {
                    $flag = 1;
                    if ($flag == 1) {
                        $this->setSuccess('GSTR2 B2B summary invoice details added successfully');
                        $this->logMsg("User ID GSTR2 B2B invoice added : " . $_SESSION['user_detail']['user_id'], "gstr2_summary");
                        return true;
                    }
                } else {
                    $flag = 0;
                    $this->setError('Failed to save returnfile data');
                    return false;
                }
            }
        }
    }

    public function insertGstr2CdnrInvoice($userid, $returnmonth) {
        $b2bdata = array();
        $b2bdata = $this->getPurchaseCdnrInvoices($userid, $returnmonth);
        if (!empty($b2bdata)) {
            $flag = 0;
            $dataArr = array();
            $x = 0;
            foreach ($b2bdata as $data) {

                $dataArr[$x]["purchase_invoice_id"] = $data->purchase_invoice_id;
                $dataArr[$x]["company_gstin_number"] = $data->recipient_shipping_gstin_number;
                $dataArr[$x]["financial_month"] = $returnmonth;
                $dataArr[$x]["invoice_number"] = $data->invoice_number;
                $dataArr[$x]["invoice_date"] = $data->invoice_date;
                $dataArr[$x]["invoice_total_value"] = $data->invoice_total_value;
                $dataArr[$x]["placeof_supply"] = $data->supply_place;
                $dataArr[$x]["added_by"] = $this->sanitize($_SESSION["user_detail"]["user_id"]);
                $dataArr[$x]["added_date"] = date('Y-m-d H:i:s');


                if ($data->supply_place == 'normal') {
                    $dataArr[$x]["reverse_charge"] = 'N';
                } elseif ($data->supply_place == 'reversecharge') {
                    $dataArr[$x]["reverse_charge"] = 'Y';
                }
                $dataArr[$x]["supplier_billing_gstin_number"] = $data->supplier_billing_gstin_number;
                $x++;
            }
            if (!empty($dataArr)) {
                if ($this->insertMultiple('gst_gstr2summary_purchase_invoice', $dataArr)) {
                    $flag = 1;
                    if ($flag == 1) {
                        $this->setSuccess('GSTR2 B2B summary invoices added successfully');
                        $this->logMsg("User ID GSTR2 B2B invoice added : " . $_SESSION['user_detail']['user_id'], "gstr2_summary");
                        return true;
                    }
                } else {
                    $flag = 0;
                    $this->setError('Failed to save returnfile data');
                    return false;
                }
            }
        }
    }

    public function insertGstr2CdnrInvoiceDetails($userid, $returnmonth) {
        $b2bdata = array();
        $b2bdata = $this->getPurchaseCdnrInvoicesDetails($userid, $returnmonth);
        if (!empty($b2bdata)) {
            $flag = 0;
            $x = 0;
            $dataArr = array();
            foreach ($b2bdata as $data) {
                $dataArr[$x]["purchase_invoice_id"] = $data->purchase_invoice_id;
                $dataArr[$x]["purchase_invoice_item_id"] = $data->purchase_invoice_item_id;
                $dataArr[$x]["rateof_invoice"] = $data->rateof_invoice;
                $dataArr[$x]["tax_value"] = $data->taxable_subtotal;
                $dataArr[$x]["igst_amount"] = $data->igst_amount;
                $dataArr[$x]["cgst_amount"] = $data->cgst_amount;
                $dataArr[$x]["sgst_amount"] = $data->sgst_amount;
                $dataArr[$x]["cess_amount"] = $data->cess_amount;
                $dataArr[$x]["added_by"] = $this->sanitize($_SESSION["user_detail"]["user_id"]);
                $dataArr[$x]["added_date"] = date('Y-m-d H:i:s');
                $dataArr[$x]["financial_month"] = $returnmonth;
                $x++;
            }

            if (!empty($dataArr)) {

                if ($this->insertMultiple('gst_gstr2summary_purchase_invoice_item', $dataArr)) {
                    $flag = 1;
                    if ($flag == 1) {
                        $this->setSuccess('GSTR2 B2B summary invoice details added successfully');
                        $this->logMsg("User ID GSTR2 B2B invoice added : " . $_SESSION['user_detail']['user_id'], "gstr2_summary");
                        return true;
                    }
                } else {
                    $flag = 0;
                    $this->setError('Failed to save returnfile data');
                    return false;
                }
            }
        }
    }

    public function gstDocumentSummaryData() {
		$dataArr = array();
        $data1 = array();
		$data2 = array();
		$data3 = array();
		$data4 = array();
		$data5 = array();
		$data6 = array();
		$data7 = array();
		$data8 = array();
		$data9 = array();
		$data10 = array();
		$data11 = array();
		$data12 = array();
        $data1[0]['num'] = '';		
        $data1[0]['from'] = '';
        $data1[0]['to'] = '';
        $data1[0]['totnum'] = '';
        $data1[0]['cancel'] = '';
        $data1[0]['net_issue'] = '';
		$data2[0]['num'] = '';		
        $data2[0]['from'] = '';
        $data2[0]['to'] = '';
        $data2[0]['totnum'] = '';
        $data2[0]['cancel'] = '';
        $data2[0]['net_issue'] = '';
		$data3[0]['num'] = '';		
        $data3[0]['from'] = '';
        $data3[0]['to'] = '';
        $data3[0]['totnum'] = '';
        $data3[0]['cancel'] = '';
        $data3[0]['net_issue'] = '';
		$data4[0]['num'] = '';		
        $data4[0]['from'] = '';
        $data4[0]['to'] = '';
        $data4[0]['totnum'] = '';
        $data4[0]['cancel'] = '';
        $data4[0]['net_issue'] = '';
		 $data5[0]['num'] = '';		
        $data5[0]['from'] = '';
        $data5[0]['to'] = '';
        $data5[0]['totnum'] = '';
        $data5[0]['cancel'] = '';
        $data5[0]['net_issue'] = '';
		 $data6[0]['num'] = '';		
        $data6[0]['from'] = '';
        $data6[0]['to'] = '';
        $data6[0]['totnum'] = '';
        $data6[0]['cancel'] = '';
        $data6[0]['net_issue'] = '';
		 $data7[0]['num'] = '';		
        $data7[0]['from'] = '';
        $data7[0]['to'] = '';
        $data7[0]['totnum'] = '';
        $data7[0]['cancel'] = '';
        $data7[0]['net_issue'] = '';
		 $data8[0]['num'] = '';		
        $data8[0]['from'] = '';
        $data8[0]['to'] = '';
        $data8[0]['totnum'] = '';
        $data8[0]['cancel'] = '';
        $data8[0]['net_issue'] = '';
		 $data9[0]['num'] = '';		
        $data9[0]['from'] = '';
        $data9[0]['to'] = '';
        $data9[0]['totnum'] = '';
        $data9[0]['cancel'] = '';
        $data9[0]['net_issue'] = '';
		 $data10[0]['num'] = '';		
        $data10[0]['from'] = '';
        $data10[0]['to'] = '';
        $data10[0]['totnum'] = '';
        $data10[0]['cancel'] = '';
        $data10[0]['net_issue'] = '';
		 $data11[0]['num'] = '';		
        $data11[0]['from'] = '';
        $data11[0]['to'] = '';
        $data11[0]['totnum'] = '';
        $data11[0]['cancel'] = '';
        $data11[0]['net_issue'] = '';
		 $data12[0]['num'] = '';		
        $data12[0]['from'] = '';
        $data12[0]['to'] = '';
        $data12[0]['totnum'] = '';
        $data12[0]['cancel'] = '';
        $data12[0]['net_issue'] = '';
		

        if (!empty($_POST['table1_srno_from'])) {
            for ($x = 0; $x < count($_POST['table1_srno_from']); $x++) {
				$data1[$x]['num'] = 1;
                $data1[$x]['from'] = isset($_POST['table1_srno_from'][$x]) ? $_POST['table1_srno_from'][$x] : '';
                $data1[$x]['to'] = isset($_POST['table1_srno_to'][$x]) ? $_POST['table1_srno_to'][$x] : '';
                $data1[$x]['totnum'] = isset($_POST['table1_totalno'][$x]) ? $_POST['table1_totalno'][$x] : '';
                $data1[$x]['cancel'] = isset($_POST['table1_cancelled'][$x]) ? $_POST['table1_cancelled'][$x] : '';
                $data1[$x]['net_issue'] = isset($_POST['table1_netissued'][$x]) ? $_POST['table1_netissued'][$x] : '';
			}
        }
		if (!empty($_POST['table2_srno_from'])) {
            for ($x = 0; $x < count($_POST['table2_srno_from']); $x++) {
				$data2[$x]['num'] = 2;
                $data2[$x]['from'] = isset($_POST['table2_srno_from'][$x]) ? $_POST['table2_srno_from'][$x] : '';
                $data2[$x]['to'] = isset($_POST['table2_srno_to'][$x]) ? $_POST['table2_srno_to'][$x] : '';
                $data2[$x]['totnum'] = isset($_POST['table2_totalno'][$x]) ? $_POST['table2_totalno'][$x] : '';
                $data2[$x]['cancel'] = isset($_POST['table2_cancelled'][$x]) ? $_POST['table2_cancelled'][$x] : '';
                $data2[$x]['net_issue'] = isset($_POST['table2_netissued'][$x]) ? $_POST['table2_netissued'][$x] : '';
			}
        }
		if (!empty($_POST['table3_srno_from'])) {
            for ($x = 0; $x < count($_POST['table3_srno_from']); $x++) {
				$data3[$x]['num'] = 3;
                $data3[$x]['from'] = isset($_POST['table3_srno_from'][$x]) ? $_POST['table3_srno_from'][$x] : '';
                $data3[$x]['to'] = isset($_POST['table3_srno_to'][$x]) ? $_POST['table3_srno_to'][$x] : '';
                $data3[$x]['totnum'] = isset($_POST['table3_totalno'][$x]) ? $_POST['table3_totalno'][$x] : '';
                $data3[$x]['cancel'] = isset($_POST['table3_cancelled'][$x]) ? $_POST['table3_cancelled'][$x] : '';
                $data3[$x]['net_issue'] = isset($_POST['table3_netissued'][$x]) ? $_POST['table3_netissued'][$x] : '';
			}
        }
		if (!empty($_POST['table4_srno_from'])) {
            for ($x = 0; $x < count($_POST['table4_srno_from']); $x++) {
				$data4[$x]['num'] = 4;
                $data4[$x]['from'] = isset($_POST['table4_srno_from'][$x]) ? $_POST['table4_srno_from'][$x] : '';
                $data4[$x]['to'] = isset($_POST['table4_srno_to'][$x]) ? $_POST['table4_srno_to'][$x] : '';
                $data4[$x]['totnum'] = isset($_POST['table4_totalno'][$x]) ? $_POST['table4_totalno'][$x] : '';
                $data4[$x]['cancel'] = isset($_POST['table4_cancelled'][$x]) ? $_POST['table4_cancelled'][$x] : '';
                $data4[$x]['net_issue'] = isset($_POST['table4_netissued'][$x]) ? $_POST['table4_netissued'][$x] : '';
			}
        }
		if (!empty($_POST['table5_srno_from'])) {
            for ($x = 0; $x < count($_POST['table5_srno_from']); $x++) {
				$data5[$x]['num'] = 5;
                $data5[$x]['from'] = isset($_POST['table5_srno_from'][$x]) ? $_POST['table5_srno_from'][$x] : '';
                $data5[$x]['to'] = isset($_POST['table5_srno_to'][$x]) ? $_POST['table5_srno_to'][$x] : '';
                $data5[$x]['totnum'] = isset($_POST['table5_totalno'][$x]) ? $_POST['table5_totalno'][$x] : '';
                $data5[$x]['cancel'] = isset($_POST['table5_cancelled'][$x]) ? $_POST['table5_cancelled'][$x] : '';
                $data5[$x]['net_issue'] = isset($_POST['table5_netissued'][$x]) ? $_POST['table5_netissued'][$x] : '';
			}
        }
		if (!empty($_POST['table6_srno_from'])) {
            for ($x = 0; $x < count($_POST['table6_srno_from']); $x++) {
				$data6[$x]['num'] = 6;
                $data6[$x]['from'] = isset($_POST['table6_srno_from'][$x]) ? $_POST['table6_srno_from'][$x] : '';
                $data6[$x]['to'] = isset($_POST['table6_srno_to'][$x]) ? $_POST['table6_srno_to'][$x] : '';
                $data6[$x]['totnum'] = isset($_POST['table6_totalno'][$x]) ? $_POST['table6_totalno'][$x] : '';
                $data6[$x]['cancel'] = isset($_POST['table6_cancelled'][$x]) ? $_POST['table6_cancelled'][$x] : '';
                $data6[$x]['net_issue'] = isset($_POST['table6_netissued'][$x]) ? $_POST['table6_netissued'][$x] : '';
			}
        }
		if (!empty($_POST['table7_srno_from'])) {
            for ($x = 0; $x < count($_POST['table7_srno_from']); $x++) {
				$data7[$x]['num'] = 7;
                $data7[$x]['from'] = isset($_POST['table7_srno_from'][$x]) ? $_POST['table7_srno_from'][$x] : '';
                $data7[$x]['to'] = isset($_POST['table7_srno_to'][$x]) ? $_POST['table7_srno_to'][$x] : '';
                $data7[$x]['totnum'] = isset($_POST['table7_totalno'][$x]) ? $_POST['table7_totalno'][$x] : '';
                $data7[$x]['cancel'] = isset($_POST['table7_cancelled'][$x]) ? $_POST['table7_cancelled'][$x] : '';
                $data7[$x]['net_issue'] = isset($_POST['table7_netissued'][$x]) ? $_POST['table7_netissued'][$x] : '';
			}
        }
		if (!empty($_POST['table8_srno_from'])) {
            for ($x = 0; $x < count($_POST['table8_srno_from']); $x++) {
				$data8[$x]['num'] = 8;
                $data8[$x]['from'] = isset($_POST['table8_srno_from'][$x]) ? $_POST['table8_srno_from'][$x] : '';
                $data8[$x]['to'] = isset($_POST['table8_srno_to'][$x]) ? $_POST['table8_srno_to'][$x] : '';
                $data8[$x]['totnum'] = isset($_POST['table8_totalno'][$x]) ? $_POST['table8_totalno'][$x] : '';
                $data8[$x]['cancel'] = isset($_POST['table8_cancelled'][$x]) ? $_POST['table8_cancelled'][$x] : '';
                $data8[$x]['net_issue'] = isset($_POST['table8_netissued'][$x]) ? $_POST['table8_netissued'][$x] : '';
			}
        }
		if (!empty($_POST['table9_srno_from'])) {
            for ($x = 0; $x < count($_POST['table9_srno_from']); $x++) {
				$data9[$x]['num'] = 9;
                $data9[$x]['from'] = isset($_POST['table9_srno_from'][$x]) ? $_POST['table9_srno_from'][$x] : '';
                $data9[$x]['to'] = isset($_POST['table9_srno_to'][$x]) ? $_POST['table9_srno_to'][$x] : '';
                $data9[$x]['totnum'] = isset($_POST['table9_totalno'][$x]) ? $_POST['table9_totalno'][$x] : '';
                $data9[$x]['cancel'] = isset($_POST['table9_cancelled'][$x]) ? $_POST['table9_cancelled'][$x] : '';
                $data9[$x]['net_issue'] = isset($_POST['table9_netissued'][$x]) ? $_POST['table9_netissued'][$x] : '';
			}
        }
		if (!empty($_POST['table10_srno_from'])) {
            for ($x = 0; $x < count($_POST['table10_srno_from']); $x++) {
				$data10[$x]['num'] = 10;
                $data10[$x]['from'] = isset($_POST['table10_srno_from'][$x]) ? $_POST['table10_srno_from'][$x] : '';
                $data10[$x]['to'] = isset($_POST['table10_srno_to'][$x]) ? $_POST['table10_srno_to'][$x] : '';
                $data10[$x]['totnum'] = isset($_POST['table10_totalno'][$x]) ? $_POST['table10_totalno'][$x] : '';
                $data10[$x]['cancel'] = isset($_POST['table10_cancelled'][$x]) ? $_POST['table10_cancelled'][$x] : '';
                $data10[$x]['net_issue'] = isset($_POST['table10_netissued'][$x]) ? $_POST['table10_netissued'][$x] : '';
			}
        }
		if (!empty($_POST['table11_srno_from'])) {
            for ($x = 0; $x < count($_POST['table11_srno_from']); $x++) {
				$data11[$x]['num'] = 11;
                $data11[$x]['from'] = isset($_POST['table11_srno_from'][$x]) ? $_POST['table11_srno_from'][$x] : '';
                $data11[$x]['to'] = isset($_POST['table11_srno_to'][$x]) ? $_POST['table11_srno_to'][$x] : '';
                $data11[$x]['totnum'] = isset($_POST['table11_totalno'][$x]) ? $_POST['table11_totalno'][$x] : '';
                $data11[$x]['cancel'] = isset($_POST['table11_cancelled'][$x]) ? $_POST['table11_cancelled'][$x] : '';
                $data11[$x]['net_issue'] = isset($_POST['table11_netissued'][$x]) ? $_POST['table11_netissued'][$x] : '';
			}
        }
		if (!empty($_POST['table12_srno_from'])) {
            for ($x = 0; $x < count($_POST['table12_srno_from']); $x++) {
				$data12[$x]['num'] = 12;
                $data12[$x]['from'] = isset($_POST['table12_srno_from'][$x]) ? $_POST['table12_srno_from'][$x] : '';
                $data12[$x]['to'] = isset($_POST['table12_srno_to'][$x]) ? $_POST['table12_srno_to'][$x] : '';
                $data12[$x]['totnum'] = isset($_POST['table12_totalno'][$x]) ? $_POST['table12_totalno'][$x] : '';
                $data12[$x]['cancel'] = isset($_POST['table12_cancelled'][$x]) ? $_POST['table12_cancelled'][$x] : '';
                $data12[$x]['net_issue'] = isset($_POST['table12_netissued'][$x]) ? $_POST['table12_netissued'][$x] : '';
			}
        }
        //$this->pr($data=array("doc_num1"=>$data1,"doc_num2"=>$data2,"doc_num3"=>$data3,"doc_num4"=>$data4,"doc_num5"=>$data5,"doc_num6"=>$data6,"doc_num7"=>$data7,"doc_num8"=>$data8,"doc_num9"=>$data9,"doc_num10"=>$data10,"doc_num11"=>$data11,"doc_num12"=>$data12));
	    $data=array("doc_num1"=>$data1,"doc_num2"=>$data2,"doc_num3"=>$data3,"doc_num4"=>$data4,"doc_num5"=>$data5,"doc_num6"=>$data6,"doc_num7"=>$data7,"doc_num8"=>$data8,"doc_num9"=>$data9,"doc_num10"=>$data10,"doc_num11"=>$data11,"doc_num12"=>$data12);
		$dataArr['return_data'] = base64_encode(json_encode($data));
        return $dataArr;
     
    }

    public function gstHsnSummaryData() {
        $dataArr = array();
        $data = array();
        $data[0]['hsn'] = '';
        $data[0]['description'] = '';
        $data[0]['unit'] = '';
        $data[0]['qty'] = '';
        $data[0]['taxable_subtotal'] = '';
        $data[0]['invoice_total_value'] = '';
        $data[0]['igst'] = '';
        $data[0]['cgst'] = '';
        $data[0]['sgst'] = '';
        $data[0]['cess'] = '';

        if (!empty($_POST['hsn'])) {
            for ($x = 0; $x < count($_POST['hsn']); $x++) {
                $data[$x]['hsn'] = isset($_POST['hsn'][$x]) ? $_POST['hsn'][$x] : '';
                $data[$x]['description'] = isset($_POST['description'][$x]) ? $_POST['description'][$x] : '';
                $data[$x]['unit'] = isset($_POST['unit'][$x]) ? $_POST['unit'][$x] : '';
                $data[$x]['qty'] = isset($_POST['qty'][$x]) ? $_POST['qty'][$x] : '';
                $data[$x]['taxable_subtotal'] = isset($_POST['taxable_subtotal'][$x]) ? $_POST['taxable_subtotal'][$x] : '';
                $data[$x]['invoice_total_value'] = isset($_POST['invoice_total_value'][$x]) ? $_POST['invoice_total_value'][$x] : '';
                $data[$x]['igst'] = isset($_POST['igst'][$x]) ? $_POST['igst'][$x] : '';
                $data[$x]['cgst'] = isset($_POST['cgst'][$x]) ? $_POST['cgst'][$x] : '';
                $data[$x]['sgst'] = isset($_POST['sgst'][$x]) ? $_POST['sgst'][$x] : '';
                $data[$x]['cess'] = isset($_POST['cess'][$x]) ? $_POST['cess'][$x] : '';
            }
        }
       
        $dataArr['return_data'] = base64_encode(json_encode($data));

        return $dataArr;
    }
	public function getGstr2HsnSummaryData() {
        $dataArr = array();
        $data = array();
        $data[0]['hsn'] = '';
        $data[0]['description'] = '';
        $data[0]['unit'] = '';
        $data[0]['qty'] = '';
        $data[0]['taxable_subtotal'] = '';
        $data[0]['invoice_total_value'] = '';
        $data[0]['igst'] = '';
        $data[0]['cgst'] = '';
        $data[0]['sgst'] = '';
        $data[0]['cess'] = '';

        if (!empty($_POST['hsn'])) {
            for ($x = 0; $x < count($_POST['hsn']); $x++) {
                $data[$x]['hsn'] = isset($_POST['hsn'][$x]) ? $_POST['hsn'][$x] : '';
                $data[$x]['description'] = isset($_POST['description'][$x]) ? $_POST['description'][$x] : '';
                $data[$x]['unit'] = isset($_POST['unit'][$x]) ? $_POST['unit'][$x] : '';
                $data[$x]['qty'] = isset($_POST['qty'][$x]) ? $_POST['qty'][$x] : '';
                $data[$x]['taxable_subtotal'] = isset($_POST['taxable_subtotal'][$x]) ? $_POST['taxable_subtotal'][$x] : '';
                $data[$x]['invoice_total_value'] = isset($_POST['invoice_total_value'][$x]) ? $_POST['invoice_total_value'][$x] : '';
                $data[$x]['igst'] = isset($_POST['igst'][$x]) ? $_POST['igst'][$x] : '';
                $data[$x]['cgst'] = isset($_POST['cgst'][$x]) ? $_POST['cgst'][$x] : '';
                $data[$x]['sgst'] = isset($_POST['sgst'][$x]) ? $_POST['sgst'][$x] : '';
                $data[$x]['cess'] = isset($_POST['cess'][$x]) ? $_POST['cess'][$x] : '';
            }
        }
        //$this->pr($data);
        $dataArr['return_data'] = base64_encode(json_encode($data));

        return $dataArr;
    }
	public function gstNilExemptSummaryData() {
        $dataArr = array();
        $data = array();
        $data[0]['sply_ty'] = '';
        $data[0]['nil_amt'] = '';
        $data[0]['ngsup_amt'] = '';
        $data[0]['expt_amt'] = '';
       

     
            for ($x = 0; $x < 4; $x++) {
			if($x==0)
			 {				 
                $data[$x]['sply_ty'] = 'INTERB2B';
                 $data[$x]['nil_amt'] = isset($_POST['inter_reg_nil_amt']) ? $_POST['inter_reg_nil_amt'] : 0.00;
                $data[$x]['expt_amt'] = isset($_POST['inter_reg_expt_amt']) ? $_POST['inter_reg_expt_amt'] : 0.00;
                $data[$x]['ngsup_amt'] = isset($_POST['inter_reg_ngsup_amt']) ? $_POST['inter_reg_ngsup_amt'] : 0.00;
			 }	
			 if($x==1)
			 {				 
                $data[$x]['sply_ty'] = 'INTRAB2B';
                $data[$x]['nil_amt'] = isset($_POST['intra_reg_nil_amt']) ? $_POST['intra_reg_nil_amt'] : '';
                $data[$x]['expt_amt'] = isset($_POST['intra_reg_expt_amt']) ? $_POST['intra_reg_expt_amt'] : '';
                $data[$x]['ngsup_amt'] = isset($_POST['intra_reg_ngsup_amt']) ? $_POST['intra_reg_ngsup_amt'] : '';
			 }
             if($x==2)
			 {
				$data[$x]['sply_ty'] = 'INTERB2C';
                $data[$x]['nil_amt'] = isset($_POST['inter_unreg_nil_amt']) ? $_POST['inter_unreg_nil_amt'] : '';
                $data[$x]['expt_amt'] = isset($_POST['inter_unreg_expt_amt']) ? $_POST['inter_unreg_expt_amt'] : '';
                $data[$x]['ngsup_amt'] = isset($_POST['inter_unreg_ngsup_amt']) ? $_POST['inter_unreg_ngsup_amt'] : '';
		 
			 }	
            if($x==3)
			 {
				$data[$x]['sply_ty'] = 'INTRAB2C';
                $data[$x]['nil_amt'] = isset($_POST['intra_unreg_nil_amt']) ? $_POST['intra_unreg_nil_amt'] : '';
                $data[$x]['expt_amt'] = isset($_POST['intra_unreg_expt_amt']) ? $_POST['intra_unreg_expt_amt'] : '';
                $data[$x]['ngsup_amt'] = isset($_POST['intra_unreg_ngsup_amt']) ? $_POST['intra_unreg_ngsup_amt'] : '';
		 
			 }				 
            }
        
       
		
		$dataArr['return_data'] = base64_encode(json_encode($data));
           
        return $dataArr;
    }
	public function gstr2NilExemptSummaryData() {
        $dataArr = array();
        $data1 = array();
		$data2 = array();
        $data1[0]['cpddr'] = isset($_POST['inter_valueofsupply_compound']) ? $_POST['inter_valueofsupply_compound'] : 0.00;
	    $data1[0]['exptdsply'] = isset($_POST['inter_valueofexempt']) ? $_POST['inter_valueofexempt'] : 0.00;
	    $data1[0]['ngsply'] = isset($_POST['inter_totalnongst']) ? $_POST['inter_totalnongst'] : 0.00;
		$data1[0]['nilsply'] = isset($_POST['inter_nilrated']) ? $_POST['inter_nilrated'] : 0.00;
	    $data2[0]['cpddr'] = isset($_POST['intra_valueofsupply_compound']) ? $_POST['intra_valueofsupply_compound'] : 0.00;
        $data2[0]['exptdsply'] = isset($_POST['intra_valueofexempt']) ? $_POST['intra_valueofexempt'] : 0.00;
        $data2[0]['ngsply'] = isset($_POST['intra_totalnongst']) ? $_POST['intra_totalnongst'] : 0.00;
        $data2[0]['nilsply'] = isset($_POST['intra_nilrated']) ? $_POST['intra_nilrated'] : 0.00;
	    //$this->pr($data);die;
	    $data=array("inter"=>$data1,"intra"=>$data2);    
		//$this->pr($data);
		//die;
		$dataArr['return_data'] = base64_encode(json_encode($data));

        return $dataArr;
    }
	public function gstr2ItcReversalData() {
           $dataArr = array();
        $data1 = array();
		$data2 = array();
		$data3 = array();
		$data4 = array();
		$data5 = array();
		$data6 = array();
		$data7 = array();
        $data1[0]['iamt'] = isset($_POST['rule2_2_igst']) ? $_POST['rule2_2_igst'] : 0.00;
	    $data1[0]['camt'] = isset($_POST['rule2_2_cgst']) ? $_POST['rule2_2_cgst'] : 0.00;
	    $data1[0]['samt'] = isset($_POST['rule2_2_sgst']) ? $_POST['rule2_2_sgst'] : 0.00;
		$data1[0]['csamt'] = isset($_POST['rule2_2_cess']) ? $_POST['rule2_2_cess'] : 0.00;
	    $data2[0]['iamt'] = isset($_POST['rule7_1_m_igst']) ? $_POST['rule7_1_m_igst'] : 0.00;
        $data2[0]['camt'] = isset($_POST['rule7_1_m_cgst']) ? $_POST['rule7_1_m_cgst'] : 0.00;
        $data2[0]['samt'] = isset($_POST['rule7_1_m_sgst']) ? $_POST['rule7_1_m_sgst'] : 0.00;
        $data2[0]['csamt'] = isset($_POST['rule7_1_m_cess']) ? $_POST['rule7_1_m_cess'] : 0.00;
		$data3[0]['iamt'] = isset($_POST['rule8_1_h_igst']) ? $_POST['rule8_1_h_igst'] : 0.00;
	    $data3[0]['camt'] = isset($_POST['rule8_1_h_cgst']) ? $_POST['rule8_1_h_cgst'] : 0.00;
	    $data3[0]['samt'] = isset($_POST['rule8_1_h_sgst']) ? $_POST['rule8_1_h_sgst'] : 0.00;
		$data3[0]['csamt'] = isset($_POST['rule8_1_h_cess']) ? $_POST['rule8_1_h_cess'] : 0.00;
		$data4[0]['iamt'] = isset($_POST['rule7_2_a_igst']) ? $_POST['rule7_2_a_igst'] : 0.00;
	    $data4[0]['camt'] = isset($_POST['rule7_2_a_cgst']) ? $_POST['rule7_2_a_cgst'] : 0.00;
	    $data4[0]['samt'] = isset($_POST['rule7_2_a_sgst']) ? $_POST['rule7_2_a_sgst'] : 0.00;
		$data4[0]['csamt'] = isset($_POST['rule7_2_a_cess']) ? $_POST['rule7_2_a_cess'] : 0.00;
	    $data5[0]['iamt'] = isset($_POST['rule7_2_b_igst']) ? $_POST['rule7_2_b_igst'] : 0.00;
	    $data5[0]['camt'] = isset($_POST['rule7_2_b_cgst']) ? $_POST['rule7_2_b_cgst'] : 0.00;
	    $data5[0]['samt'] = isset($_POST['rule7_2_b_sgst']) ? $_POST['rule7_2_b_sgst'] : 0.00;
		$data5[0]['csamt'] = isset($_POST['rule7_2_b_cess']) ? $_POST['rule7_2_b_cess'] : 0.00;
	    $data6[0]['iamt'] = isset($_POST['revitc_igst']) ? $_POST['revitc_igst'] : 0.00;
	    $data6[0]['camt'] = isset($_POST['revitc_cgst']) ? $_POST['revitc_cgst'] : 0.00;
	    $data6[0]['samt'] = isset($_POST['revitc_sgst']) ? $_POST['revitc_sgst'] : 0.00;
		$data6[0]['csamt'] = isset($_POST['revitc_cess']) ? $_POST['revitc_cess'] : 0.00;
	    $data7[0]['iamt'] = isset($_POST['other_igst']) ? $_POST['other_igst'] : 0.00;
	    $data7[0]['camt'] = isset($_POST['other_cgst']) ? $_POST['other_cgst'] : 0.00;
	    $data7[0]['samt'] = isset($_POST['other_sgst']) ? $_POST['other_sgst'] : 0.00;
		$data7[0]['csamt'] = isset($_POST['other_cess']) ? $_POST['other_cess'] : 0.00;
	
	   
	    $data=array("rule2_2"=>$data1,"rule7_1_m"=>$data2,"rule8_1_h"=>$data3,"rule7_2_a"=>$data4,"rule7_2_b"=>$data5,"revitc"=>$data6,"other"=>$data7);    
		$dataArr['return_data'] = base64_encode(json_encode($data));

        return $dataArr;
    }
	

    public function saveGstr1nilexemptSummary() {
        $data = $this->get_results("select * from gst_return_upload_summary where added_by='" . $_SESSION['user_detail']['user_id'] . "' and financial_month='" . $this->sanitize($_GET['returnmonth']) . "' and type='gstr1nil'");
        $dataArr = $this->gstNilExemptSummaryData();
        $returnmonth = $this->sanitize($_GET['returnmonth']);
        if (empty($data)) {
            $dataArr['financial_month'] = $this->sanitize($_GET['returnmonth']);
            $dataArr['added_by'] = $this->sanitize($_SESSION["user_detail"]["user_id"]);
            $dataArr['type'] = 'gstr1nil';
			$dataArr['added_date']=  date('Y-m-d h:i:s');

            if ($this->insert('gst_return_upload_summary', $dataArr)) {
                $this->setSuccess('GSTR1 nilexempt summary form Saved Successfully');
                $this->logMsg("GSTR1 hsn summary Inserted financial month : " . $returnmonth, "gstr1");
                return true;
            } else {
                $this->setError('Failed to save GSTR1 document summary data');
                return false;
            }
        } else {
            $dataArr['updated_date']=  date('Y-m-d h:i:s');
			$dataArr['updated_by'] = $this->sanitize($_SESSION["user_detail"]["user_id"]);
          
            if ($this->update('gst_return_upload_summary', $dataArr, array('added_by' => $_SESSION['user_detail']['user_id'],'type' => 'gstr1nil','financial_month' => $this->sanitize($_GET['returnmonth'])))) {

                $this->setSuccess('GSTR1 nilexempt summary month of ' . $returnmonth . "updated Successfully");
                //$this->logMsg("GSTR3B updated financial month : " . $returnmonth,"gstr_3b");
                return true;
            } else {
                $this->setError('Failed to save GSTR3B data');
                return false;
            }
        }
    }
	public function saveGstr2nilexemptSummary() {
        $data = $this->get_results("select * from gst_return_upload_summary where added_by='" . $_SESSION['user_detail']['user_id'] . "' and financial_month='" . $this->sanitize($_GET['returnmonth']) . "' and type='gstr2nil'");
        $dataArr = $this->gstr2NilExemptSummaryData();
        $returnmonth = $this->sanitize($_GET['returnmonth']);
        if (empty($data)) {
            $dataArr['financial_month'] = $this->sanitize($_GET['returnmonth']);
            $dataArr['added_by'] = $this->sanitize($_SESSION["user_detail"]["user_id"]);
            $dataArr['type'] = 'gstr2nil';
			$dataArr['added_date']=  date('Y-m-d h:i:s');

            if ($this->insert('gst_return_upload_summary', $dataArr)) {
                $this->setSuccess('GSTR2 nilexempt summary form Saved Successfully');
                $this->logMsg("GSTR2 nilexempt summary Inserted financial month : " . $returnmonth, "gstr1");
                return true;
            } else {
                $this->setError('Failed to save GSTR2 nil summary data');
                return false;
            }
        } else {
            $dataArr['updated_date']=  date('Y-m-d h:i:s');
			$dataArr['updated_by'] = $this->sanitize($_SESSION["user_detail"]["user_id"]);
          
            if ($this->update('gst_return_upload_summary', $dataArr, array('added_by' => $_SESSION['user_detail']['user_id'],'type' => 'gstr2nil','financial_month' => $this->sanitize($_GET['returnmonth'])))) {

                $this->setSuccess('GSTR2 nilexempt summary month of ' . $returnmonth . "updated Successfully");
                //$this->logMsg("GSTR3B updated financial month : " . $returnmonth,"gstr_3b");
                return true;
            } else {
                $this->setError('Failed to save nilexempt data');
                return false;
            }
        }
    }
	public function saveGstr2itcreversalSummary() {
        $data = $this->get_results("select * from gst_return_upload_summary where added_by='" . $_SESSION['user_detail']['user_id'] . "' and financial_month='" . $this->sanitize($_GET['returnmonth']) . "' and type='gstr2itcreversal'");
        $dataArr = $this->gstr2ItcReversalData();
        $returnmonth = $this->sanitize($_GET['returnmonth']);
        if (empty($data)) {
            $dataArr['financial_month'] = $this->sanitize($_GET['returnmonth']);
            $dataArr['added_by'] = $this->sanitize($_SESSION["user_detail"]["user_id"]);
            $dataArr['type'] = 'gstr2itcreversal';
			$dataArr['added_date']=  date('Y-m-d h:i:s');

            if ($this->insert('gst_return_upload_summary', $dataArr)) {
                $this->setSuccess('GSTR2 itc reversal summary form Saved Successfully');
                $this->logMsg("GSTR2 itc reversal summary Inserted financial month : " . $returnmonth, "gstr1");
                return true;
            } else {
                $this->setError('Failed to save GSTR2 itc reversal summary data');
                return false;
            }
        } else {
            $dataArr['updated_date']=  date('Y-m-d h:i:s');
			$dataArr['updated_by'] = $this->sanitize($_SESSION["user_detail"]["user_id"]);
          
            if ($this->update('gst_return_upload_summary', $dataArr, array('added_by' => $_SESSION['user_detail']['user_id'],'type' => 'gstr2itcreversal','financial_month' => $this->sanitize($_GET['returnmonth'])))) {

                $this->setSuccess('GSTR2 itc reversal summary month of ' . $returnmonth . "updated Successfully");
                //$this->logMsg("GSTR3B updated financial month : " . $returnmonth,"gstr_3b");
                return true;
            } else {
                $this->setError('Failed to save nilexempt data');
                return false;
            }
        }
    }
	
    public function saveGstr1HsnSummary() {
        $data = $this->get_results("select * from gst_return_upload_summary where added_by='" . $_SESSION['user_detail']['user_id'] . "' and financial_month='" . $this->sanitize($_GET['returnmonth']) . "' and type='gstr1hsn'");
        $dataArr = $this->gstHsnSummaryData();
        $returnmonth = $this->sanitize($_GET['returnmonth']);
        if (empty($data)) {
            $dataArr['financial_month'] = $this->sanitize($_GET['returnmonth']);
            $dataArr['added_by'] = $this->sanitize($_SESSION["user_detail"]["user_id"]);
            $dataArr['type'] = 'gstr1hsn';
			$dataArr['added_date']=  date('Y-m-d h:i:s');
            if ($this->insert('gst_return_upload_summary', $dataArr)) {
                $this->setSuccess('GSTR1 hsn summary form Saved Successfully');
                $this->logMsg("GSTR1 hsn summary Inserted financial month : " . $returnmonth, "gstr1");
                return true;
            } else {
                $this->setError('Failed to save GSTR1 document summary data');
                return false;
            }
        } else {
            $dataArr['updated_date']=  date('Y-m-d h:i:s');
			$dataArr['updated_by'] = $this->sanitize($_SESSION["user_detail"]["user_id"]);
          
            if ($this->update('gst_return_upload_summary', $dataArr, array('added_by' => $_SESSION['user_detail']['user_id'],'type' => 'gstr1hsn', 'financial_month' => $this->sanitize($_GET['returnmonth'])))) {

                $this->setSuccess('GSTR1 hsn summary month of ' . $returnmonth . "updated Successfully");
                //$this->logMsg("GSTR3B updated financial month : " . $returnmonth,"gstr_3b");
                return true;
            } else {
                $this->setError('Failed to save GSTR3B data');
                return false;
            }
        }
    }
	 public function saveGstr2HsnSummary() {
        $data = $this->get_results("select * from gst_return_upload_summary where added_by='" . $_SESSION['user_detail']['user_id'] . "' and financial_month='" . $this->sanitize($_GET['returnmonth']) . "' and type='gstr2hsn'");
        $dataArr = $this->getGstr2HsnSummaryData();
        $returnmonth = $this->sanitize($_GET['returnmonth']);
        if (empty($data)) {
            $dataArr['financial_month'] = $this->sanitize($_GET['returnmonth']);
            $dataArr['added_by'] = $this->sanitize($_SESSION["user_detail"]["user_id"]);
            $dataArr['type'] = 'gstr2hsn';
			$dataArr['added_date']=  date('Y-m-d h:i:s');
            if ($this->insert('gst_return_upload_summary', $dataArr)) {
                $this->setSuccess('GSTR2 hsn summary data Saved Successfully');
                $this->logMsg("GSTR2 hsn summary Inserted financial month : " . $returnmonth, "gstr1");
                return true;
            } else {
                $this->setError('Failed to save GSTR2 hsn summary data');
                return false;
            }
        } else {
            $dataArr['updated_date']=  date('Y-m-d h:i:s');
			$dataArr['updated_by'] = $this->sanitize($_SESSION["user_detail"]["user_id"]);
          
            if ($this->update('gst_return_upload_summary', $dataArr, array('added_by' => $_SESSION['user_detail']['user_id'],'type' => 'gstr2hsn', 'financial_month' => $this->sanitize($_GET['returnmonth'])))) {

                $this->setSuccess('GSTR2 hsn summary month of ' . $returnmonth . "updated Successfully");
                //$this->logMsg("GSTR3B updated financial month : " . $returnmonth,"gstr_3b");
                return true;
            } else {
                $this->setError('Failed to save GSTR2 hsn data');
                return false;
            }
        }
    }

    public function saveGstr1DocumentSummary() {
        $data = $this->get_results("select * from gst_return_upload_summary where added_by='" . $_SESSION['user_detail']['user_id'] . "' and financial_month='" . $this->sanitize($_GET['returnmonth']) . "' and type='gstr1document'");
        $dataArr = $this->gstDocumentSummaryData();
        $returnmonth = $this->sanitize($_GET['returnmonth']);
        if (empty($data)) {
            $dataArr['financial_month'] = $this->sanitize($_GET['returnmonth']);
            $dataArr['added_by'] = $this->sanitize($_SESSION["user_detail"]["user_id"]);
            $dataArr['type'] = 'gstr1document';
            $dataArr['added_date']=  date('Y-m-d h:i:s');
            if ($this->insert('gst_return_upload_summary', $dataArr)) {
                $this->setSuccess('GSTR1 document summary form Saved Successfully');
                $this->logMsg("GSTR1 document summary Inserted financial month : " . $returnmonth, "gstr1");
                return true;
            } else {
                $this->setError('Failed to save GSTR1 document summary data');
                return false;
            }
        } else {
            $dataArr['updated_date']=  date('Y-m-d h:i:s');
			$dataArr['updated_by'] = $this->sanitize($_SESSION["user_detail"]["user_id"]);
          
            if ($this->update('gst_return_upload_summary', $dataArr, array('added_by' => $_SESSION['user_detail']['user_id'],'type' => 'gstr1document','financial_month' => $this->sanitize($_GET['returnmonth'])))) {

                $this->setSuccess('GSTR1 document summary month of ' . $returnmonth . "updated Successfully");
                //$this->logMsg("GSTR3B updated financial month : " . $returnmonth,"gstr_3b");
                return true;
            } else {
                $this->setError('Failed to save GSTR3B data');
                return false;
            }
        }
    }

    public function gstr2File() {
        $fmonth = isset($_GET['returnmonth']) ? $_GET['returnmonth'] : date('Y-m');

        $dataReturn = $this->get_results('select * from ' . $this->getTableName('return') . " where return_month='" . $this->sanitize($_GET['returnmonth']) . "' and type='gstr1'");
        if (!empty($dataReturn)) {
            $dataGST1_set['financial_year'] = $this->generateFinancialYear();
            $dataGST1_set['return_month'] = $fmonth;
            $dataGST1_set['status'] = '3';


            $dataGST1['type'] = 'gstr2';
            $dataGST1['client_id'] = $_SESSION['user_detail']['user_id'];

            $this->update($this->getTableName('return'), $dataGST1_set, $dataGST1);
            $this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . "update GSTR2 File " . $fmonth, "gstr2");
        } else {
            $dataGST1['financial_year'] = $this->generateFinancialYear();
            $dataGST1['return_month'] = $fmonth;
            $dataGST1['type'] = 'gstr2';
            $dataGST1['client_id'] = $_SESSION['user_detail']['user_id'];
            $dataGST1['status'] = '3';
            $this->insert($this->getTableName('return'), $dataGST1);
            $this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . "update GSTR2 File " . $fmonth, "gstr2");
        }
        $this->setSuccess("GSTR2 is Filed");
        return true;
    }

    public function submitITCClaim() {
        $dataArr = $this->getITCClaimData();
        if ($this->updateMultiple($this->getTableName('client_reconcile_purchase_invoice1'), $dataArr)) {
            $this->setSuccess('ITC Claim data is saved');
            return true;
        }
        $this->setError('Failed to save try again.');
        return false;
    }

    private function getITCClaimData() {
        $dataArr = array();
        if (isset($_POST['sub']) && $_POST['sub'] == "Save ITC Values") {
            for ($x = 0; $x < count($_POST['category']); $x++) {
                $dataArr[$x]['set']['category'] = isset($_POST['category'][$x]) ? $_POST['category'][$x] : '';
                $dataArr[$x]['set']['claim_rate'] = isset($_POST['claim_rate'][$x]) ? $_POST['claim_rate'][$x] : '';

                $dataArr[$x]['where']['reference_number'] = isset($_POST['id'][$x]) ? $_POST['id'][$x] : '';
            }
        }
        return $dataArr;
    }

	public function generateGSTR2ClaimITCData($returnMonth, $array_type = true) {
		
		$gstr2_claim_itc_query = 'Select * from ' . $this->tableNames['gstr2_reconcile_final'] . ' where 1=1 AND added_by = ' . $this->sanitize($_SESSION['user_detail']['user_id']) . ' AND financial_month = "' . $returnMonth . '" AND reconciliation_status != "pending" AND status = "1"';
		$gstr2_claim_itc_result = $this->get_results($gstr2_claim_itc_query, $array_type);
		return $gstr2_claim_itc_result;
    }
}