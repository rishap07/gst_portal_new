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
        $gstr2ReturnMonth = isset($_POST['gstr2ReturnMonth']) ? $_POST['gstr2ReturnMonth'] : '';
        if (empty($gstr2ReturnMonth)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }
        $dataRes = $this->generalGSTR2InvoiceList($gstr2ReturnMonth);
        if (!empty($dataRes)) {
            $x = 0;
            $dataUpdate = array();
            foreach ($dataRes as $dataRe) {
                $dataUpdate[$x]['set']['is_gstr2_downloaded'] = '1';
                $dataUpdate[$x]['where']['invoice_id'] = $dataRe->invoice_id;
                $x++;
            }
            if ($this->updateMultiple($this->getTableName('client_invoice'), $dataUpdate)) {
                $this->setSuccess("GSTR2 Data Downloaded");
				$this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . " Download The GSTR2","gstr2");
			
            } else {
                $this->setError("Failed to Download Data");
            }
        } else {
            $this->setError("There is no invoice to Download");
        }
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
				$this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . "Initiated the GSTR2 Filling","gstr2");
			
                return true;
            } else {
                $this->setError('Failed to save GSTR2 data');
                return false;
            }
        }
    }

    public function gstr2Upload()
    {
        //Purchase Data;
        $dataQuery = "select re.id,pur.supplier_billing_gstin_number as gstin_number,re.reference_number,re.invoice_status,re.status,sum(pur_it.cgst_amount) as cgst_amount,sum(pur_it.sgst_amount) as sgst_amount,sum(pur_it.igst_amount) as igst_amount,sum(pur_it.cess_amount) as cess_amount,pur.invoice_total_value,re.invoice_date,re.invoice_status,re.status from ".$this->getTableName('client_reconcile_purchase_invoice1')." re inner join ".$this->getTableName('client_purchase_invoice')." pur on re.reference_number=pur.reference_number inner join ".$this->getTableName('client_purchase_invoice_item')." pur_it on pur.purchase_invoice_id=pur_it.purchase_invoice_id where re.invoice_date like('%".$this->sanitize($_GET['returnmonth'])."%') and re.added_by='".$_SESSION['user_detail']['user_id']."' and ((re.invoice_status='0' and re.status='3')or(re.invoice_status='2' and re.status='1')or(re.invoice_status='2' and re.status='2')or(re.invoice_status='2' and re.status='3')or(re.invoice_status='2' and re.status='4')or(re.invoice_status='3' and re.status='3')) and re.is_uploaded='0' group by pur.reference_number  ";
        $dataPur = $this->get_results($dataQuery);
        //Sales Data;
        $dataQuery = "select re.id,pur.billing_gstin_number as gstin_number,re.reference_number,re.invoice_status,re.status,sum(pur_it.cgst_amount) as cgst_amount,sum(pur_it.sgst_amount) as sgst_amount,sum(pur_it.igst_amount) as igst_amount,sum(pur_it.cess_amount) as cess_amount,pur.invoice_total_value,re.invoice_date,re.invoice_status,re.status from ".$this->getTableName('client_reconcile_purchase_invoice1')." re inner join ".$this->getTableName('client_invoice')." pur on re.reference_number=pur.reference_number inner join ".$this->getTableName('client_invoice_item')." pur_it on pur.invoice_id=pur_it.invoice_id where  re.invoice_date like('%".$this->sanitize($_GET['returnmonth'])."%') and re.added_by='".$_SESSION['user_detail']['user_id']."' and ((re.invoice_status='0' and re.status='1')or(re.invoice_status='0' and re.status='2')or(re.invoice_status='0' and re.status='4')or(re.invoice_status='1' and re.status='1')or(re.invoice_status='1' and re.status='2')or(re.invoice_status='1' and re.status='3')or(re.invoice_status='1' and re.status='4')or(re.invoice_status='3' and re.status='1')or(re.invoice_status='3' and re.status='2')or(re.invoice_status='3' and re.status='4')) and re.is_uploaded='0'  group by pur.reference_number ";
        $dataSale = $this->get_results($dataQuery);
        $data = array_merge($dataPur,$dataSale);
		if(!empty($data))
		{
			foreach($data as $da)
			{

				$da->added_by= $_SESSION['user_detail']['user_id'];
				$da->added_date= date('Y-m-d H:i:s');
				$id = $da->id;
				unset($da->id);
				$this->insert($this->getTableName('client_upload_gstr2'),$da);
				$this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . "Upload GSTR2 Data","gstr2");
			
				$this->update($this->getTableName('client_reconcile_purchase_invoice1'),array('is_uploaded'=>'1'),array('id'=>$id));
					$this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . "upload reconcile purchase invoice data","gstr2");
			
				$dataReturn = $this->get_results('select * from '.$this->getTableName('return')." where return_month='".$this->sanitize($_GET['returnmonth'])."' and type='gstr2'");
				if(!empty($dataReturn))
				{
					$this->update($this->getTableName('return'),array('status'=>'2'),array('return_id'=>$dataReturn[0]->return_id));
					$this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . "Uploaded GSTR2 data","gstr2");
			
				}
				else
				{
					$dataRet['financial_year']=$this->generateFinancialYear();
					$dataRet['return_month']=$this->sanitize($_GET['returnmonth']);
					$dataRet['type']='gstr2';
					$dataRet['client_id']=$_SESSION['user_detail']['user_id'];
					$dataRet['status']='2';
					$this->insert($this->getTableName('return'),$dataRet);
					$this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . "Upload GSTR2 Data","gstr2");
			
				}
			}
			$this->setSuccess('Invoice Uploaded Successfully');
			$this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . "uploaded GSTR2 Invoice","gstr2");
			
			return true;
        }
		else
		{
			$this->setError('No Data to upload');
			return false;
		}
    }

    public function claimItc(){
        $dataQuery = "select re.id,pur.supplier_billing_gstin_number as gstin_number,re.reference_number,pur.company_name,pur_it.taxable_subtotal,re.invoice_status,re.status,sum(pur_it.cgst_amount) as cgst_amount,sum(pur_it.sgst_amount) as sgst_amount,sum(pur_it.igst_amount) as igst_amount,sum(pur_it.cess_amount) as cess_amount,pur.invoice_total_value,re.invoice_date,re.invoice_status,re.status from ".$this->getTableName('client_reconcile_purchase_invoice1')." re inner join ".$this->getTableName('client_purchase_invoice')." pur on re.reference_number=pur.reference_number inner join ".$this->getTableName('client_purchase_invoice_item')." pur_it on pur.purchase_invoice_id=pur_it.purchase_invoice_id where re.invoice_date like('%".$this->sanitize($_GET['returnmonth'])."%') and re.added_by='".$_SESSION['user_detail']['user_id']."' and ((re.invoice_status='0' and re.status='3')or(re.invoice_status='2' and re.status='1')or(re.invoice_status='2' and re.status='2')or(re.invoice_status='2' and re.status='3')or(re.invoice_status='2' and re.status='4')or(re.invoice_status='3' and re.status='3')) and re.is_uploaded='0' group by pur.reference_number  ";
        $dataPur = $this->get_results($dataQuery);
        //Sales Data;
        $dataQuery = "select re.id,pur.billing_gstin_number as gstin_number,re.reference_number,pur.company_name,pur_it.taxable_subtotal,re.invoice_status,re.status,sum(pur_it.cgst_amount) as cgst_amount,sum(pur_it.sgst_amount) as sgst_amount,sum(pur_it.igst_amount) as igst_amount,sum(pur_it.cess_amount) as cess_amount,pur.invoice_total_value,re.invoice_date,re.invoice_status,re.status from ".$this->getTableName('client_reconcile_purchase_invoice1')." re inner join ".$this->getTableName('client_invoice')." pur on re.reference_number=pur.reference_number inner join ".$this->getTableName('client_invoice_item')." pur_it on pur.invoice_id=pur_it.invoice_id where  re.invoice_date like('%".$this->sanitize($_GET['returnmonth'])."%') and re.added_by='".$_SESSION['user_detail']['user_id']."' and ((re.invoice_status='0' and re.status='1')or(re.invoice_status='0' and re.status='2')or(re.invoice_status='0' and re.status='4')or(re.invoice_status='1' and re.status='1')or(re.invoice_status='1' and re.status='2')or(re.invoice_status='1' and re.status='3')or(re.invoice_status='1' and re.status='4')or(re.invoice_status='3' and re.status='1')or(re.invoice_status='3' and re.status='2')or(re.invoice_status='3' and re.status='4')) and re.is_uploaded='0'  group by pur.reference_number ";
        $dataSale = $this->get_results($dataQuery);
        $data = array_merge($dataPur,$dataSale);
		if(!empty($data))
		{
          return $data;
		}



    }
	
	public function gstr2File() {
        $fmonth = isset($_GET['returnmonth']) ? $_GET['returnmonth'] : date('Y-m');
        
		$dataReturn = $this->get_results('select * from '.$this->getTableName('return')." where return_month='".$this->sanitize($_GET['returnmonth'])."' and type='gstr1'");
		if (!empty($dataReturn)) {
			$dataGST1_set['financial_year'] = $this->generateFinancialYear();
			$dataGST1_set['return_month'] = $fmonth;
			$dataGST1_set['status'] = '3';


			$dataGST1['type'] = 'gstr2';
			$dataGST1['client_id'] = $_SESSION['user_detail']['user_id'];

			$this->update($this->getTableName('return'), $dataGST1_set, $dataGST1);
			$this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . "update GSTR2 File ".$fmonth,"gstr2");
			
		} else {
			$dataGST1['financial_year'] = $this->generateFinancialYear();
			$dataGST1['return_month'] = $fmonth;
			$dataGST1['type'] = 'gstr2';
			$dataGST1['client_id'] = $_SESSION['user_detail']['user_id'];
			$dataGST1['status'] = '3';
			$this->insert($this->getTableName('return'), $dataGST1);
			$this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . "update GSTR2 File ".$fmonth,"gstr2");
			
		}
		$this->setSuccess("GSTR2 is Filed");
        return true;
    }
}
