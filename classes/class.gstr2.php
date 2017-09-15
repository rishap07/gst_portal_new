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
        $obj_api = new gstr();
        $response_b2b = $response_cdn = '';
        $gstr2ReturnMonth = isset($_POST['gstr2ReturnMonth']) ? $_POST['gstr2ReturnMonth'] : '';
        if (empty($gstr2ReturnMonth)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }
        $dataUpdate = $dataUpdate1 = array();
        $response_b2b = $obj_api->returnSummary($gstr2ReturnMonth, 'B2B', 'gstr2a');
        if ($response_b2b == false) {
            return false;
        }
        $response_cdn = $obj_api->returnSummary($gstr2ReturnMonth, 'CDN', 'gstr2a');
        if ($response_cdn == false) {
            return false;
        }

        if (!empty($response_b2b) || !empty($response_cdn)) {
            $jstrb2b_array = json_decode($response_b2b, true);
            $jstrcdn_array = json_decode($response_cdn, true);
            //$this->pr($jstrb2b_array);
            //$this->pr($jstrcdn_array);
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
                            $idt = isset($jstr1_value['idt']) ? $jstr1_value['idt'] : '';
                            $rchrg = isset($jstr1_value['rchrg']) ? $jstr1_value['rchrg'] : '';
                            $inum = isset($jstr1_value['inum']) ? $jstr1_value['inum'] : '';
                            $chksum = isset($jstr1_value['chksum']) ? $jstr1_value['chksum'] : '';

                            $nt_num = isset($jstr1_value['nt_num']) ? $jstr1_value['nt_num'] : '';
                            $inum = isset($jstr1_value['inum']) ? $jstr1_value['inum'] : '';
                            $rsn = isset($jstr1_value['rsn']) ? $jstr1_value['rsn'] : 0;

                            $idt = isset($jstr1_value['idt']) ? $jstr1_value['idt'] : '';
                            $nt_dt = isset($jstr1_value['nt_dt']) ? $jstr1_value['nt_dt'] : '';
                            $p_gst = isset($jstr1_value['p_gst']) ? $jstr1_value['p_gst'] : '';
                            $ntty = isset($jstr1_value['ntty']) ? $jstr1_value['ntty'] : '';
                            $rsn = isset($jstr1_value['rsn']) ? $jstr1_value['rsn'] : '';

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
                                    $dataUpdate[$x][$i]['invoice_date'] = $idt > 0 ? date('Y-m-d', strtotime($idt)) : '';

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
                                    $dataUpdate[$x][$i]['rchrg'] = $rchrg;
                                    $dataUpdate[$x][$i]['chksum'] = $chksum;

                                    $dataUpdate[$x][$i]['nt_num'] = $nt_num;

                                    $dataUpdate[$x][$i]['nt_dt'] = $nt_dt > 0 ? date('Y-m-d', strtotime($nt_dt)) : '';
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
                $a = 0;
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
                            $updby = isset($jstr1_value['updby']) ? $jstr1_value['updby'] : '';

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
                                    $dataUpdate1[$y][$i]['reference_number'] = $inum;
                                    $dataUpdate1[$y][$i]['invoice_date'] = $idt > 0 ? date('Y-m-d', strtotime($idt)) : '';

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
                                    $dataUpdate1[$y][$i]['rchrg'] = $rchrg;
                                    $dataUpdate1[$y][$i]['chksum'] = $chksum;

                                    $dataUpdate1[$y][$i]['nt_num'] = $nt_num;
                                    $dataUpdate1[$y][$i]['nt_dt'] = $nt_dt > 0 ? date('Y-m-d', strtotime($nt_dt)) : '';
                                    $dataUpdate1[$y][$i]['p_gst'] = $p_gst;
                                    $dataUpdate1[$y][$i]['ntty'] = $ntty;
                                    $dataUpdate1[$y][$i]['rsn'] = $rsn;
                                    $dataUpdate1[$y][$i]['financial_month'] = $gstr2ReturnMonth;
                                    $dataUpdate1[$y][$i]['added_by'] = $_SESSION['user_detail']['user_id'];
                                    $dataUpdate1[$y][$i]['added_date'] = date('Y-m-d h:i:s');
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

            /* $this->pr($data);
              die; */
            if (!empty($data)) {
                $results_old = $this->checkUserInvoices($this->sanitize($_SESSION['user_detail']['user_id']), $gstr2ReturnMonth);
                //$this->pr($results_old);
                $data_update = $data_insert = array();
                $x = $y = 0;
                foreach ($data as $key => $value) {
                    $flag = 0;
                    if (!empty($results_old)) {
                        foreach ($results_old as $dkey => $old_value) {

                            if ($value['reference_number'] == $old_value->reference_number && (float) $value['rate'] == (float) $old_value->rate && $value['company_gstin_number'] == $old_value->company_gstin_number) {
                                $flag = 1;
                                if (($value['invoice_date'] != $old_value->invoice_date || (float) $value['invoice_total_value'] != (float) $old_value->invoice_total_value || (float) $value['total_taxable_subtotal'] != (float) $old_value->total_taxable_subtotal || $value['inv_typ'] != $old_value->inv_typ || (float) $value['total_cgst_amount'] != (float) $old_value->total_cgst_amount || (float) $value['total_sgst_amount'] != (float) $old_value->total_sgst_amount || (float) $value['total_igst_amount'] != (float) $old_value->total_igst_amount || (float) $value['total_cess_amount'] != (float) $old_value->total_cess_amount || $value['type'] != $old_value->type || $value['rchrg'] != $old_value->rchrg || $value['pos'] != $old_value->pos || $value['chksum'] != $old_value->chksum || $value['itms'] != $old_value->itms || $value['nt_num'] != $old_value->nt_num || $value['nt_dt'] != $old_value->nt_dt || $value['ntty'] != $old_value->ntty || $value['p_gst'] != $old_value->p_gst || $value['rsn'] != $old_value->rsn)) {


                                    $data_update[$x]['set']['invoice_date'] = $value['invoice_date'] > 0 ? date('Y-m-d', strtotime($value['invoice_date'])) : '';
                                    $data_update[$x]['set']['rate'] = $value['rate'];
                                    $data_update[$x]['set']['invoice_total_value'] = $value['invoice_total_value'];
                                    $data_update[$x]['set']['total_taxable_subtotal'] = $value['total_taxable_subtotal'];
                                    $data_update[$x]['set']['inv_typ'] = $value['inv_typ'];
                                    $data_update[$x]['set']['company_gstin_number'] = $value['company_gstin_number'];
                                    $data_update[$x]['set']['total_cgst_amount'] = $value['total_cgst_amount'];
                                    $data_update[$x]['set']['total_sgst_amount'] = $value['total_sgst_amount'];
                                    $data_update[$x]['set']['total_igst_amount'] = $value['total_igst_amount'];
                                    $data_update[$x]['set']['total_cess_amount'] = $value['total_cess_amount'];
                                    $data_update[$x]['set']['rchrg'] = $value['rchrg'];
                                    $data_update[$x]['set']['pos'] = $value['pos'];
                                    $data_update[$x]['set']['chksum'] = $value['chksum'];
                                    $data_update[$x]['set']['itms'] = $value['itms'];
                                    $data_update[$x]['set']['nt_num'] = $value['nt_num'];
                                    $data_update[$x]['set']['nt_dt'] = $value['nt_dt'] > 0 ? date('Y-m-d', strtotime($value['nt_dt'])) : '';
                                    $data_update[$x]['set']['ntty'] = $value['ntty'];
                                    $data_update[$x]['set']['p_gst'] = $value['p_gst'];
                                    $data_update[$x]['set']['rsn'] = $value['rsn'];
                                    $data_update[$x]['set']['updated_date'] = date('Y-m-d h:i:s');
                                    $data_update[$x]['where']['added_by'] = $this->sanitize($_SESSION['user_detail']['user_id']);
                                    $data_update[$x]['where']['reference_number'] = $value['reference_number'];
                                    $data_update[$x]['where']['financial_month'] = $this->sanitize($gstr2ReturnMonth);
                                    $data_update[$x]['where']['rate'] = $value['rate'];
                                    $data_update[$x]['where']['company_gstin_number'] = $value['company_gstin_number'];

                                    $x++;
                                }
                            }
                        }
                    }

                    if ($flag == 0) {
                        $data_insert[$y] = $value;
                        $y++;
                    }
                }
                $flagfailed = 0;
                //echo '<br/>insert====> ';$this->pr($data_insert);
                if (!empty($data_insert)) {
                    if (!$this->insertMultiple($this->getTableName('client_reconcile_purchase_invoice1'), $data_insert)) {
                        $flagfailed = 1;
                    }
                }

                //echo '<br/>update====> ';$this->pr($data_update);
                if (!empty($data_update)) {
                    if (!$this->updateMultiple($this->getTableName('client_reconcile_purchase_invoice1'), $data_update)) {
                        $flagfailed = 1;
                    }
                }
                // die;
                if ($flagfailed == 1) {
                    $this->setError('GSTR2 Download Failed');
                    return false;
                } else {
                    $this->setSuccess('GSTR2 Download Successfully');
                    return true;
                }
            }
        } else {
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

    public function claimItc() {
        $dataQuery = "select re.category,re.claim_rate,re.claim_value, re.id,pur.supplier_billing_gstin_number as gstin_number,re.reference_number,pur.company_name,pur_it.taxable_subtotal,re.invoice_status,re.status,sum(pur_it.cgst_amount) as cgst_amount,sum(pur_it.sgst_amount) as sgst_amount,sum(pur_it.igst_amount) as igst_amount,sum(pur_it.cess_amount) as cess_amount,pur.invoice_total_value,re.invoice_date,re.invoice_status,re.status from " . $this->getTableName('client_reconcile_purchase_invoice1') . " re inner join " . $this->getTableName('client_purchase_invoice') . " pur on re.reference_number=pur.reference_number inner join " . $this->getTableName('client_purchase_invoice_item') . " pur_it on pur.purchase_invoice_id=pur_it.purchase_invoice_id where re.invoice_date like('%" . $this->sanitize($_GET['returnmonth']) . "%') and re.added_by='" . $_SESSION['user_detail']['user_id'] . "' and ((re.invoice_status='0' and re.status='3')or(re.invoice_status='2' and re.status='1')or(re.invoice_status='2' and re.status='2')or(re.invoice_status='2' and re.status='3')or(re.invoice_status='2' and re.status='4')or(re.invoice_status='3' and re.status='3')) and re.is_uploaded='0' group by pur.reference_number  ";
        $dataPur = $this->get_results($dataQuery);
        //Sales Data;
        $dataQuery = "select re.category,re.claim_rate,re.claim_value, re.id,pur.billing_gstin_number as gstin_number,re.reference_number,pur.company_name,pur_it.taxable_subtotal,re.invoice_status,re.status,sum(pur_it.cgst_amount) as cgst_amount,sum(pur_it.sgst_amount) as sgst_amount,sum(pur_it.igst_amount) as igst_amount,sum(pur_it.cess_amount) as cess_amount,pur.invoice_total_value,re.invoice_date,re.invoice_status,re.status from " . $this->getTableName('client_reconcile_purchase_invoice1') . " re inner join " . $this->getTableName('client_invoice') . " pur on re.reference_number=pur.reference_number inner join " . $this->getTableName('client_invoice_item') . " pur_it on pur.invoice_id=pur_it.invoice_id where  re.invoice_date like('%" . $this->sanitize($_GET['returnmonth']) . "%') and re.added_by='" . $_SESSION['user_detail']['user_id'] . "' and ((re.invoice_status='0' and re.status='1')or(re.invoice_status='0' and re.status='2')or(re.invoice_status='0' and re.status='4')or(re.invoice_status='1' and re.status='1')or(re.invoice_status='1' and re.status='2')or(re.invoice_status='1' and re.status='3')or(re.invoice_status='1' and re.status='4')or(re.invoice_status='3' and re.status='1')or(re.invoice_status='3' and re.status='2')or(re.invoice_status='3' and re.status='4')) and re.is_uploaded='0'  group by pur.reference_number ";
        $dataSale = $this->get_results($dataQuery);
        // print_r($this->sanitize($_GET['returnmonth']));
        $data = array_merge($dataPur, $dataSale);
        if (!empty($data)) {
            return $data;
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
i.supplier_billing_gstin_number='' and i.invoice_total_value < '250000' and (i.invoice_type='taxinvoice' or i.invoice_type='sezunitinvoice' or i.invoice_type='importinvoice')  and i.invoice_nature='purchaseinvoice'  AND i.invoice_date like '%" . $returnmonth . "%'";
        return $this->get_results($queryB2B);
    }

    private function getPurchaseB2csmallInvoicesDetails($user_id, $returnmonth) {

        $queryB2B = "SELECT it.consolidate_rate as rateof_invoice,i.purchase_invoice_id,it.purchase_invoice_item_id,i.recipient_shipping_gstin_number,i.supplier_billing_gstin_number,i.serial_number as invoice_number,i.invoice_date,i.invoice_total_value,i.supply_place,i.supply_type,it.total,it.taxable_subtotal,it.igst_amount,it.cgst_amount,it.sgst_amount,it.cess_amount from gst_client_purchase_invoice as i INNER JOIN gst_client_purchase_invoice_item as it on it.purchase_invoice_id = i.purchase_invoice_id where i.invoice_nature='purchaseinvoice' and i.added_by='" . $user_id . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and 
i.supplier_billing_gstin_number='' and i.invoice_total_value < '250000' and (i.invoice_type='taxinvoice' or i.invoice_type='sezunitinvoice' or i.invoice_type='importinvoice')  and i.invoice_nature='purchaseinvoice'  AND i.invoice_date like '%" . $returnmonth . "%'";
        return $this->get_results($queryB2B);
    }

    private function getPurchaseImportInvoices($user_id, $returnmonth) {
        echo $queryB2B = "select i.purchase_invoice_id, i.recipient_shipping_gstin_number,i.supplier_billing_gstin_number,i.serial_number as invoice_number,i.invoice_date,i.invoice_total_value,i.supply_place,i.supply_type from gst_client_purchase_invoice as i where i.invoice_nature='purchaseinvoice' and i.added_by='" . $user_id . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and 
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
        $data = array();
        $data['table1_srno_from'] = '';
        $data['table1_srno_to'] = '';
        $data['table1_totalno'] = '';
        $data['table1_cancelled'] = '';
        $data['table1_netissued'] = '';
        $data['table2_srno_from'] = '';
        $data['table2_srno_to'] = '';
        $data['table2_totalno'] = '';
        $data['table2_cancelled'] = '';
        $data['table2_netissued'] = '';
        $data['table3_srno_from'] = '';
        $data['table3_srno_to'] = '';
        $data['table3_totalno'] = '';
        $data['table3_cancelled'] = '';
        $data['table3_netissued'] = '';
        $data['table4_srno_from'] = '';
        $data['table4_srno_to'] = '';
        $data['table4_totalno'] = '';
        $data['table4_cancelled'] = '';
        $data['table4_netissued'] = '';
        $data['table5_srno_from'] = '';
        $data['table5_srno_to'] = '';
        $data['table5_totalno'] = '';
        $data['table5_cancelled'] = '';
        $data['table5_netissued'] = '';
        $data['table6_srno_from'] = '';
        $data['table6_srno_to'] = '';
        $data['table6_totalno'] = '';
        $data['table6_cancelled'] = '';
        $data['table6_netissued'] = '';
        $data['table7_srno_from'] = '';
        $data['table7_srno_to'] = '';
        $data['table7_totalno'] = '';
        $data['table7_cancelled'] = '';
        $data['table7_netissued'] = '';
        $data['table8_srno_from'] = '';
        $data['table8_srno_to'] = '';
        $data['table8_totalno'] = '';
        $data['table8_cancelled'] = '';
        $data['table8_netissued'] = '';
        $data['table9_srno_from'] = '';
        $data['table9_srno_to'] = '';
        $data['table9_totalno'] = '';
        $data['table9_cancelled'] = '';
        $data['table9_netissued'] = '';
        $data['table10_srno_from'] = '';
        $data['table10_srno_to'] = '';
        $data['table10_totalno'] = '';
        $data['table10_cancelled'] = '';
        $data['table10_netissued'] = '';
        $data['table11_srno_from'] = '';
        $data['table11_srno_to'] = '';
        $data['table11_totalno'] = '';
        $data['table11_cancelled'] = '';
        $data['table11_netissued'] = '';
        $data['table12_srno_from'] = '';
        $data['table12_srno_to'] = '';
        $data['table12_totalno'] = '';
        $data['table12_cancelled'] = '';
        $data['table12_netissued'] = '';

        if (!empty($_POST['table1_srno_from'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table1_srno_from'] as $selected) {

                $data['table1_srno_from'] = $data['table1_srno_from'] . $selected . ',';
            }
            $data['table1_srno_from'] = rtrim($data['table1_srno_from'], ",");
        }

        if (!empty($_POST['table1_srno_to'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table1_srno_to'] as $selected) {

                $data['table1_srno_to'] = $data['table1_srno_to'] . $selected . ',';
            }
            $data['table1_srno_to'] = rtrim($data['table1_srno_to'], ",");
        }
        if (!empty($_POST['table1_totalno'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table1_totalno'] as $selected) {

                $data['table1_totalno'] = $data['table1_totalno'] . $selected . ',';
            }
            $data['table1_totalno'] = rtrim($data['table1_totalno'], ",");
        }
        if (!empty($_POST['table1_cancelled'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table1_cancelled'] as $selected) {

                $data['table1_cancelled'] = $data['table1_cancelled'] . $selected . ',';
            }
            $data['table1_cancelled'] = rtrim($data['table1_cancelled'], ",");
        }
        if (!empty($_POST['table1_netissued'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table1_netissued'] as $selected) {

                $data['table1_netissued'] = $data['table1_netissued'] . $selected . ',';
            }
            $data['table1_netissued'] = rtrim($data['table1_netissued'], ",");
        }
        if (!empty($_POST['table2_srno_from'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table2_srno_from'] as $selected) {

                $data['table2_srno_from'] = $data['table2_srno_from'] . $selected . ',';
            }
            $data['table2_srno_from'] = rtrim($data['table2_srno_from'], ",");
        }

        if (!empty($_POST['table2_srno_to'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table2_srno_to'] as $selected) {

                $data['table2_srno_to'] = $data['table2_srno_to'] . $selected . ',';
            }
            $data['table2_srno_to'] = rtrim($data['table2_srno_to'], ",");
        }
        if (!empty($_POST['table2_totalno'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table2_totalno'] as $selected) {

                $data['table2_totalno'] = $data['table2_totalno'] . $selected . ',';
            }
            $data['table2_totalno'] = rtrim($data['table2_totalno'], ",");
        }
        if (!empty($_POST['table2_cancelled'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table2_cancelled'] as $selected) {

                $data['table2_cancelled'] = $data['table2_cancelled'] . $selected . ',';
            }
            $data['table2_cancelled'] = rtrim($data['table2_cancelled'], ",");
        }
        if (!empty($_POST['table2_netissued'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table2_netissued'] as $selected) {

                $data['table2_netissued'] = $data['table2_netissued'] . $selected . ',';
            }
            $data['table2_netissued'] = rtrim($data['table2_netissued'], ",");
        }
        //code for table3 post data
        if (!empty($_POST['table3_srno_from'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table3_srno_from'] as $selected) {

                $data['table3_srno_from'] = $data['table3_srno_from'] . $selected . ',';
            }
            $data['table3_srno_from'] = rtrim($data['table3_srno_from'], ",");
        }

        if (!empty($_POST['table3_srno_to'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table3_srno_to'] as $selected) {

                $data['table3_srno_to'] = $data['table3_srno_to'] . $selected . ',';
            }
            $data['table3_srno_to'] = rtrim($data['table3_srno_to'], ",");
        }
        if (!empty($_POST['table3_totalno'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table3_totalno'] as $selected) {

                $data['table3_totalno'] = $data['table3_totalno'] . $selected . ',';
            }
            $data['table3_totalno'] = rtrim($data['table3_totalno'], ",");
        }
        if (!empty($_POST['table3_cancelled'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table3_cancelled'] as $selected) {

                $data['table3_cancelled'] = $data['table3_cancelled'] . $selected . ',';
            }
            $data['table3_cancelled'] = rtrim($data['table3_cancelled'], ",");
        }
        if (!empty($_POST['table3_netissued'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table3_netissued'] as $selected) {

                $data['table3_netissued'] = $data['table3_netissued'] . $selected . ',';
            }
            $data['table3_netissued'] = rtrim($data['table3_netissued'], ",");
        }
        //code end here for table3 post data
        //code for table4 post data
        if (!empty($_POST['table4_srno_from'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table4_srno_from'] as $selected) {

                $data['table4_srno_from'] = $data['table4_srno_from'] . $selected . ',';
            }
            $data['table4_srno_from'] = rtrim($data['table4_srno_from'], ",");
        }

        if (!empty($_POST['table4_srno_to'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table4_srno_to'] as $selected) {

                $data['table4_srno_to'] = $data['table4_srno_to'] . $selected . ',';
            }
            $data['table4_srno_to'] = rtrim($data['table4_srno_to'], ",");
        }
        if (!empty($_POST['table4_totalno'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table4_totalno'] as $selected) {

                $data['table4_totalno'] = $data['table4_totalno'] . $selected . ',';
            }
            $data['table4_totalno'] = rtrim($data['table4_totalno'], ",");
        }
        if (!empty($_POST['table4_cancelled'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table4_cancelled'] as $selected) {

                $data['table4_cancelled'] = $data['table4_cancelled'] . $selected . ',';
            }
            $data['table4_cancelled'] = rtrim($data['table4_cancelled'], ",");
        }
        if (!empty($_POST['table4_netissued'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table4_netissued'] as $selected) {

                $data['table4_netissued'] = $data['table4_netissued'] . $selected . ',';
            }
            $data['table4_netissued'] = rtrim($data['table4_netissued'], ",");
        }
        //code end here for table4 post data
        //code for table5 post data start here
        if (!empty($_POST['table5_srno_from'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table5_srno_from'] as $selected) {

                $data['table5_srno_from'] = $data['table5_srno_from'] . $selected . ',';
            }
            $data['table5_srno_from'] = rtrim($data['table5_srno_from'], ",");
        }

        if (!empty($_POST['table5_srno_to'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table5_srno_to'] as $selected) {

                $data['table5_srno_to'] = $data['table5_srno_to'] . $selected . ',';
            }
            $data['table5_srno_to'] = rtrim($data['table5_srno_to'], ",");
        }
        if (!empty($_POST['table5_totalno'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table5_totalno'] as $selected) {

                $data['table5_totalno'] = $data['table5_totalno'] . $selected . ',';
            }
            $data['table5_totalno'] = rtrim($data['table5_totalno'], ",");
        }
        if (!empty($_POST['table5_cancelled'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table5_cancelled'] as $selected) {

                $data['table5_cancelled'] = $data['table5_cancelled'] . $selected . ',';
            }
            $data['table5_cancelled'] = rtrim($data['table5_cancelled'], ",");
        }
        if (!empty($_POST['table5_netissued'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table5_netissued'] as $selected) {

                $data['table5_netissued'] = $data['table5_netissued'] . $selected . ',';
            }
            $data['table5_netissued'] = rtrim($data['table5_netissued'], ",");
        }
        //code for table5 post data end here
        //code start here for table 6 post data
        if (!empty($_POST['table6_srno_from'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table6_srno_from'] as $selected) {

                $data['table6_srno_from'] = $data['table6_srno_from'] . $selected . ',';
            }
            $data['table6_srno_from'] = rtrim($data['table6_srno_from'], ",");
        }

        if (!empty($_POST['table6_srno_to'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table6_srno_to'] as $selected) {

                $data['table6_srno_to'] = $data['table6_srno_to'] . $selected . ',';
            }
            $data['table6_srno_to'] = rtrim($data['table6_srno_to'], ",");
        }
        if (!empty($_POST['table6_totalno'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table6_totalno'] as $selected) {

                $data['table6_totalno'] = $data['table6_totalno'] . $selected . ',';
            }
            $data['table6_totalno'] = rtrim($data['table6_totalno'], ",");
        }
        if (!empty($_POST['table6_cancelled'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table6_cancelled'] as $selected) {

                $data['table6_cancelled'] = $data['table6_cancelled'] . $selected . ',';
            }
            $data['table6_cancelled'] = rtrim($data['table6_cancelled'], ",");
        }
        if (!empty($_POST['table6_netissued'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table6_netissued'] as $selected) {

                $data['table6_netissued'] = $data['table6_netissued'] . $selected . ',';
            }
            $data['table6_netissued'] = rtrim($data['table6_netissued'], ",");
        }
        //code end here for table 6 post data	
        //code start here for table 7 post data
        if (!empty($_POST['table7_srno_from'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table7_srno_from'] as $selected) {

                $data['table7_srno_from'] = $data['table7_srno_from'] . $selected . ',';
            }
            $data['table7_srno_from'] = rtrim($data['table7_srno_from'], ",");
        }

        if (!empty($_POST['table7_srno_to'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table7_srno_to'] as $selected) {

                $data['table7_srno_to'] = $data['table7_srno_to'] . $selected . ',';
            }
            $data['table7_srno_to'] = rtrim($data['table7_srno_to'], ",");
        }
        if (!empty($_POST['table7_totalno'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table7_totalno'] as $selected) {

                $data['table7_totalno'] = $data['table7_totalno'] . $selected . ',';
            }
            $data['table7_totalno'] = rtrim($data['table7_totalno'], ",");
        }
        if (!empty($_POST['table7_cancelled'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table7_cancelled'] as $selected) {

                $data['table7_cancelled'] = $data['table7_cancelled'] . $selected . ',';
            }
            $data['table7_cancelled'] = rtrim($data['table7_cancelled'], ",");
        }
        if (!empty($_POST['table7_netissued'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table7_netissued'] as $selected) {

                $data['table7_netissued'] = $data['table7_netissued'] . $selected . ',';
            }
            $data['table7_netissued'] = rtrim($data['table7_netissued'], ",");
        }
        // code end here for table 7 post data
        // code start here for table 8 post data
        if (!empty($_POST['table8_srno_from'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table8_srno_from'] as $selected) {

                $data['table8_srno_from'] = $data['table8_srno_from'] . $selected . ',';
            }
            $data['table8_srno_from'] = rtrim($data['table8_srno_from'], ",");
        }

        if (!empty($_POST['table8_srno_to'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table8_srno_to'] as $selected) {

                $data['table8_srno_to'] = $data['table8_srno_to'] . $selected . ',';
            }
            $data['table8_srno_to'] = rtrim($data['table8_srno_to'], ",");
        }
        if (!empty($_POST['table8_totalno'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table8_totalno'] as $selected) {

                $data['table8_totalno'] = $data['table8_totalno'] . $selected . ',';
            }
            $data['table8_totalno'] = rtrim($data['table8_totalno'], ",");
        }
        if (!empty($_POST['table8_cancelled'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table8_cancelled'] as $selected) {

                $data['table8_cancelled'] = $data['table8_cancelled'] . $selected . ',';
            }
            $data['table8_cancelled'] = rtrim($data['table8_cancelled'], ",");
        }
        if (!empty($_POST['table8_netissued'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table8_netissued'] as $selected) {

                $data['table8_netissued'] = $data['table8_netissued'] . $selected . ',';
            }
            $data['table8_netissued'] = rtrim($data['table8_netissued'], ",");
        }
        // code end here for table 8 post data		   
        //code start for table 9 post data
        if (!empty($_POST['table9_srno_from'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table9_srno_from'] as $selected) {

                $data['table9_srno_from'] = $data['table9_srno_from'] . $selected . ',';
            }
            $data['table9_srno_from'] = rtrim($data['table9_srno_from'], ",");
        }

        if (!empty($_POST['table9_srno_to'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table9_srno_to'] as $selected) {

                $data['table9_srno_to'] = $data['table9_srno_to'] . $selected . ',';
            }
            $data['table9_srno_to'] = rtrim($data['table9_srno_to'], ",");
        }
        if (!empty($_POST['table9_totalno'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table9_totalno'] as $selected) {

                $data['table9_totalno'] = $data['table9_totalno'] . $selected . ',';
            }
            $data['table9_totalno'] = rtrim($data['table9_totalno'], ",");
        }
        if (!empty($_POST['table9_cancelled'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table9_cancelled'] as $selected) {

                $data['table9_cancelled'] = $data['table9_cancelled'] . $selected . ',';
            }
            $data['table9_cancelled'] = rtrim($data['table9_cancelled'], ",");
        }
        if (!empty($_POST['table9_netissued'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table9_netissued'] as $selected) {

                $data['table9_netissued'] = $data['table9_netissued'] . $selected . ',';
            }
            $data['table9_netissued'] = rtrim($data['table9_netissued'], ",");
        }
        //code end here for table 9 post data
        //code for table10 post data
        if (!empty($_POST['table10_srno_from'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table10_srno_from'] as $selected) {

                $data['table10_srno_from'] = $data['table10_srno_from'] . $selected . ',';
            }
            $data['table10_srno_from'] = rtrim($data['table10_srno_from'], ",");
        }

        if (!empty($_POST['table10_srno_to'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table10_srno_to'] as $selected) {

                $data['table10_srno_to'] = $data['table10_srno_to'] . $selected . ',';
            }
            $data['table10_srno_to'] = rtrim($data['table10_srno_to'], ",");
        }
        if (!empty($_POST['table10_totalno'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table10_totalno'] as $selected) {

                $data['table10_totalno'] = $data['table10_totalno'] . $selected . ',';
            }
            $data['table10_totalno'] = rtrim($data['table10_totalno'], ",");
        }
        if (!empty($_POST['table10_cancelled'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table10_cancelled'] as $selected) {

                $data['table10_cancelled'] = $data['table10_cancelled'] . $selected . ',';
            }
            $data['table10_cancelled'] = rtrim($data['table10_cancelled'], ",");
        }
        if (!empty($_POST['table10_netissued'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table10_netissued'] as $selected) {

                $data['table10_netissued'] = $data['table10_netissued'] . $selected . ',';
            }
            $data['table10_netissued'] = rtrim($data['table10_netissued'], ",");
        }
        //code end here for table 10 post data
        //code start here for table11 post data
        if (!empty($_POST['table11_srno_from'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table11_srno_from'] as $selected) {

                $data['table11_srno_from'] = $data['table11_srno_from'] . $selected . ',';
            }
            $data['table11_srno_from'] = rtrim($data['table11_srno_from'], ",");
        }

        if (!empty($_POST['table11_srno_to'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table11_srno_to'] as $selected) {

                $data['table11_srno_to'] = $data['table11_srno_to'] . $selected . ',';
            }
            $data['table11_srno_to'] = rtrim($data['table11_srno_to'], ",");
        }
        if (!empty($_POST['table11_totalno'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table11_totalno'] as $selected) {

                $data['table11_totalno'] = $data['table11_totalno'] . $selected . ',';
            }
            $data['table11_totalno'] = rtrim($data['table11_totalno'], ",");
        }
        if (!empty($_POST['table11_cancelled'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table11_cancelled'] as $selected) {

                $data['table11_cancelled'] = $data['table11_cancelled'] . $selected . ',';
            }
            $data['table11_cancelled'] = rtrim($data['table11_cancelled'], ",");
        }
        if (!empty($_POST['table11_netissued'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table11_netissued'] as $selected) {

                $data['table11_netissued'] = $data['table11_netissued'] . $selected . ',';
            }
            $data['table11_netissued'] = rtrim($data['table11_netissued'], ",");
        }
        //code end here for table 11 post data
        //code for table 12 post data
        if (!empty($_POST['table12_srno_from'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table12_srno_from'] as $selected) {

                $data['table12_srno_from'] = $data['table12_srno_from'] . $selected . ',';
            }
            $data['table12_srno_from'] = rtrim($data['table12_srno_from'], ",");
        }

        if (!empty($_POST['table12_srno_to'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table12_srno_to'] as $selected) {

                $data['table12_srno_to'] = $data['table12_srno_to'] . $selected . ',';
            }
            $data['table12_srno_to'] = rtrim($data['table12_srno_to'], ",");
        }
        if (!empty($_POST['table12_totalno'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table12_totalno'] as $selected) {

                $data['table12_totalno'] = $data['table12_totalno'] . $selected . ',';
            }
            $data['table12_totalno'] = rtrim($data['table12_totalno'], ",");
        }
        if (!empty($_POST['table12_cancelled'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table12_cancelled'] as $selected) {

                $data['table12_cancelled'] = $data['table12_cancelled'] . $selected . ',';
            }
            $data['table12_cancelled'] = rtrim($data['table12_cancelled'], ",");
        }
        if (!empty($_POST['table12_netissued'])) {
            // Loop to store and display values of individual checked checkbox.
            foreach ($_POST['table12_netissued'] as $selected) {

                $data['table12_netissued'] = $data['table12_netissued'] . $selected . ',';
            }
            $data['table12_netissued'] = rtrim($data['table12_netissued'], ",");
        }
        //code end here for table 12 post data

        $data5a[] = array("table1_srno_from" => $data['table1_srno_from'], "table1_srno_to" => $data['table1_srno_to'], "table1_totalno" => $data['table1_totalno'], "table1_cancelled" => $data['table1_cancelled'], "table1_netissued" => $data['table1_netissued'], "table2_srno_from" => $data['table2_srno_from'], "table2_srno_to" => $data['table2_srno_to'], "table2_totalno" => $data['table2_totalno'], "table2_cancelled" => $data['table2_cancelled'], "table2_netissued" => $data['table2_netissued'], "table3_srno_from" => $data['table3_srno_from'], "table3_srno_to" => $data['table3_srno_to'], "table3_totalno" => $data['table3_totalno'], "table3_cancelled" => $data['table3_cancelled'], "table3_netissued" => $data['table3_netissued'], "table4_srno_from" => $data['table4_srno_from'], "table4_srno_to" => $data['table4_srno_to'], "table4_totalno" => $data['table4_totalno'], "table4_cancelled" => $data['table4_cancelled'], "table4_netissued" => $data['table4_netissued'], "table5_srno_from" => $data['table5_srno_from'], "table5_srno_to" => $data['table5_srno_to'], "table5_totalno" => $data['table5_totalno'], "table5_cancelled" => $data['table5_cancelled'], "table5_netissued" => $data['table5_netissued'], "table6_srno_from" => $data['table6_srno_from'], "table6_srno_to" => $data['table6_srno_to'], "table6_totalno" => $data['table6_totalno'], "table6_cancelled" => $data['table6_cancelled'], "table6_netissued" => $data['table6_netissued'], "table7_srno_from" => $data['table7_srno_from'], "table7_srno_to" => $data['table7_srno_to'], "table7_totalno" => $data['table7_totalno'], "table7_cancelled" => $data['table7_cancelled'], "table7_netissued" => $data['table7_netissued'], "table8_srno_from" => $data['table8_srno_from'], "table8_srno_to" => $data['table8_srno_to'], "table8_totalno" => $data['table8_totalno'], "table8_cancelled" => $data['table8_cancelled'], "table8_netissued" => $data['table8_netissued'], "table9_srno_from" => $data['table9_srno_from'], "table9_srno_to" => $data['table9_srno_to'], "table9_totalno" => $data['table9_totalno'], "table9_cancelled" => $data['table9_cancelled'], "table9_netissued" => $data['table9_netissued'], "table10_srno_from" => $data['table10_srno_from'], "table10_srno_to" => $data['table10_srno_to'], "table10_totalno" => $data['table10_totalno'], "table10_cancelled" => $data['table10_cancelled'], "table10_netissued" => $data['table10_netissued'], "table11_srno_from" => $data['table11_srno_from'], "table11_srno_to" => $data['table11_srno_to'], "table11_totalno" => $data['table11_totalno'], "table11_cancelled" => $data['table11_cancelled'], "table11_netissued" => $data['table11_netissued'], "table12_srno_from" => $data['table12_srno_from'], "table12_srno_to" => $data['table12_srno_to'], "table12_totalno" => $data['table12_totalno'], "table12_cancelled" => $data['table12_cancelled'], "table12_netissued" => $data['table12_netissued']);
        $dataArr['return_data'] = base64_encode(json_encode($data5a));

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
        
       //$this->pr($data);die;
		
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

            if ($this->insert('gst_return_upload_summary', $dataArr)) {
                $this->setSuccess('GSTR1 nilexempt summary form Saved Successfully');
                $this->logMsg("GSTR1 hsn summary Inserted financial month : " . $returnmonth, "gstr1");
                return true;
            } else {
                $this->setError('Failed to save GSTR1 document summary data');
                return false;
            }
        } else {

            if ($this->update('gst_return_upload_summary', $dataArr, array('added_by' => $_SESSION['user_detail']['user_id'], 'financial_month' => $this->sanitize($_GET['returnmonth'])))) {

                $this->setSuccess('GSTR1 nilexempt summary month of ' . $returnmonth . "updated Successfully");
                //$this->logMsg("GSTR3B updated financial month : " . $returnmonth,"gstr_3b");
                return true;
            } else {
                $this->setError('Failed to save GSTR3B data');
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
            if ($this->insert('gst_return_upload_summary', $dataArr)) {
                $this->setSuccess('GSTR1 hsn summary form Saved Successfully');
                $this->logMsg("GSTR1 hsn summary Inserted financial month : " . $returnmonth, "gstr1");
                return true;
            } else {
                $this->setError('Failed to save GSTR1 document summary data');
                return false;
            }
        } else {

            if ($this->update('gst_return_upload_summary', $dataArr, array('added_by' => $_SESSION['user_detail']['user_id'], 'financial_month' => $this->sanitize($_GET['returnmonth'])))) {

                $this->setSuccess('GSTR1 hsn summary month of ' . $returnmonth . "updated Successfully");
                //$this->logMsg("GSTR3B updated financial month : " . $returnmonth,"gstr_3b");
                return true;
            } else {
                $this->setError('Failed to save GSTR3B data');
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

            if ($this->insert('gst_return_upload_summary', $dataArr)) {
                $this->setSuccess('GSTR1 document summary form Saved Successfully');
                $this->logMsg("GSTR1 document summary Inserted financial month : " . $returnmonth, "gstr1");
                return true;
            } else {
                $this->setError('Failed to save GSTR1 document summary data');
                return false;
            }
        } else {

            if ($this->update('gst_return_upload_summary', $dataArr, array('added_by' => $_SESSION['user_detail']['user_id'], 'financial_month' => $this->sanitize($_GET['returnmonth'])))) {

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

}
