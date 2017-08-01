<?php
/*
 * 
 *  Developed By        :   Sheetal
 *  Description         :   API GSTR1 encryprtion n decryption 
 *  Date Created        :   May 18, 2017
 *  Last Modified By    :   Monika Deswal 
 *  Last Modification   :   API encryprtion n decryption convert to class structure
 * 
*/

 final class gstr1 extends validation {
    
    function __construct() {
        parent::__construct();
    }
    
    public function gstr1Upload()
    {

 
        $fmonth = isset($_GET['returnmonth']) ? $_GET['returnmonth'] : date('Y-m');
        $this->getGSTR1Data($fmonth);
    }
	 public function selectgstr1Upload()
    {
        $fmonth = isset($_GET['returnmonth']) ? $_GET['returnmonth'] : date('Y-m');
        $this->selectgetGSTR1Data($fmonth);
    }
   
    private function selectgetGSTR1Data($fmonth)
    {
        $dataRes = $this->generalGSTR1InvoiceList($fmonth);
        $flag=0;
        if(!empty($dataRes))
        {
            $x=0;
            $dataUpdate= array();
            
			if(!empty($_POST['name'])){
            // Loop to store and display values of individual checked checkbox.
			foreach($_POST['name'] as $selected){
			  $dataUpdate[$x]['set']['is_gstr1_uploaded']= '1';
                $dataUpdate[$x]['where']['invoice_id']= $selected;
				$x++;
			} 
			}
			else
			{
				$this->setError("No invoice selected to Upload");
			}
            if($this->updateMultiple($this->getTableName('client_invoice'), $dataUpdate))
            {
				
                $flag=1;
                
                $dataReturn = $this->get_results("select * from ".TAB_PREFIX."return where where return_month='".$fmonth."' and client_id='".$_SESSION['user_detail']['user_id']."' and type='gstr1'");
                if(!empty($dataReturn))
                {
                    $dataGST1_set['financial_year']='2017-2018';
                    $dataGST1_set['return_month']=$fmonth;
                    $dataGST1_set['status']='2';
                    
                    
                    $dataGST1['type']='gstr1';
                    $dataGST1['client_id']=$_SESSION['user_detail']['user_id'];
                    
                    $this->update(TAB_PREFIX."return",$dataGST1_set,$dataGST1);
                }
                else {
                    $dataGST1['financial_year']='2017-2018';
                    $dataGST1['return_month']=$fmonth;
                    $dataGST1['type']='gstr1';
                    $dataGST1['client_id']=$_SESSION['user_detail']['user_id'];
                    $dataGST1['status']='2';
                    $this->insert(TAB_PREFIX."return",$dataGST1);
                }
				
                $this->setSuccess("GSTR1 Data Uploaded") ;
            }
            else 
            {
                $flag=2;
                $this->setError("Failed to Upload Data");
            }
        }
        if($flag==1)
        {
            return true;
        }
        if($flag==2)
        {
            return false;
        }
        if($flag==0)
        {
            $this->setError('No new data to upload');
            return false;
        }
        return false;
    }
    
    private function getGSTR1Data($fmonth)
    {     
        $dataRes = $this->generalGSTR1InvoiceList($fmonth);
        $flag=0;
        if(empty($dataRes))
        {
            $obj_gst = new gstr();
            $payload = $this->gstCreatePayload($_SESSION['user_detail']['user_id'],$fmonth);
            $dataArr = $payload['data_arr'];
            $data_ids = $payload['data_ids'];
            $response = $obj_gst->returnSave($dataArr,$fmonth); 
            
            if(!empty($response['error'] == 1)) {
                $flag=1;

                /******************** Start Code for Update Invoice is upload **************************/
                if(!empty($data_ids)) {
                    foreach ($data_ids as $key => $data_id) {
                        echo $this->pr($data_id);
                    }
                }
                /******************** End code for Update Invoice is upload **************************/
                $this->setSuccess("GSTR1 Data Uploaded");

            }
            else {
                $flag=2;
                $this->setError($response['message']);
            }   
            
            
        }
        echo 'sdgdsgds';die;
        if($flag==1)
        {
            return true;
        }
        if($flag==2)
        {
            return false;
        }
        if($flag==0)
        {
            $this->setError('No new data to upload');
            return false;
        }
        return false;
    }
    
    public function gstr1File()
    {
        $fmonth = isset($_GET['returnmonth']) ? $_GET['returnmonth'] : date('Y-m');
        $this->getGSTR1FileData($fmonth);
    }
    
    private function getGSTR1FileData($fmonth)
    {
        $dataRes = $this->generalGSTR1InvoiceList($fmonth,'1');
        $flag=0;
        if(!empty($dataRes))
        {
            $x=0;
            $dataUpdate= array();
            foreach($dataRes as $dataRe)
            {
                $dataUpdate[$x]['set']['is_gstr1_uploaded']= '2';
                $dataUpdate[$x]['where']['invoice_id']= $dataRe->invoice_id;
                $x++;
            }
            if($this->updateMultiple($this->getTableName('client_invoice'), $dataUpdate))
            {
                $flag=1;
                if(!empty($dataReturn))
                {
                    $dataGST1_set['financial_year']='2017-2018';
                    $dataGST1_set['return_month']=$fmonth;
                    $dataGST1_set['status']='3';
                    
                    
                    $dataGST1['type']='gstr1';
                    $dataGST1['client_id']=$_SESSION['client_detail']['user_id'];
                    
                    $this->update(TAB_PREFIX."return",$dataGST1_set,$dataGST1);
                }
                else {
                    $dataGST1['financial_year']='2017-2018';
                    $dataGST1['return_month']=$fmonth;
                    $dataGST1['type']='gstr1';
                    $dataGST1['client_id']=$_SESSION['user_detail']['user_id'];
                    $dataGST1['status']='3';
                    $this->insert(TAB_PREFIX."return",$dataGST1);
                }
                $this->setSuccess("GSTR1 is Filed") ;
            }
            else 
            {
                $flag=2;
                $this->setError("Failed to File GSTR1");
            }
        }
        if($flag==1)
        {
            return true;
        }
        if($flag==2)
        {
            return false;
        }
        if($flag==0)
        {
            $this->setError('No invoices are uploaded to GST');
            return false;
        }
        return false;
    }

    
    private function gstPayloadUpload($user_id,$returnmonth) {
        /*$obj_gst = new gstr();
        $payload = $this->gstCreatePayload($user_id,$returnmonth);
        $dataArr = $payload['data_arr'];
        $data_ids = $payload['data_ids'];
        $response = $obj_gst->returnSave($dataArr,$returnmonth); 
        
        if(empty($response['error'] == 1)) {
            return true;
        }
        else {
            $this->setError($response['message']);
            return false;
        }*/
    }

    private function gstCreatePayload($user_id,$returnmonth) {
        $obj_gst = new gstr();
        $dataArr = $data_ids = $response =  array();
        if(!empty($returnmonth)) {
            $api_return_period_array = explode('-',$returnmonth);
            $api_return_period = $api_return_period_array[1].$api_return_period_array[0];
        }
        $dataArr["gstin"]= $obj_gst->gstin();
        $dataArr["fp"]= $api_return_period;
        $dataArr["gt"]= (float)"53782969.00";
        $dataArr["cur_gt"]= (float)"53782969.00";

        /***** Start Code For B2B Payload ***********/
        $b2b_data = $this->gstB2BPayload($user_id,$returnmonth);
        if(!empty($b2b_data)) {
            $data_ids[] = $b2b_ids = $b2b_data['b2b_ids'];
            $b2b_arr = $b2b_data['b2b_arr'];
            $dataArr = array_merge($dataArr,$b2b_arr);

        }
        /***** End Code For B2B Payload ***********/

        /***** Start Code For B2CL Payload ***********/
        $b2cl_data = $this->gstB2CLPayload($user_id,$returnmonth);
        if(!empty($b2cl_data)) {
            $data_ids[] = $b2cl_ids = $b2cl_data['b2cl_ids'];
            $b2cl_arr = $b2cl_data['b2cl_arr'];
            $dataArr = array_merge($dataArr,$b2cl_arr);
        }
        /***** End Code For B2CL Payload ***********/

        /***** Start Code For B2CS Payload ***********/
        $b2cs_data = $this->gstB2CSPayload($user_id,$returnmonth);
        if(!empty($b2cs_data)) {
           $data_ids[] =  $b2cs_ids = $b2cs_data['b2cs_ids'];
            $b2cs_arr = $b2cs_data['b2cs_arr'];
            $dataArr = array_merge($dataArr,$b2cs_arr);
        }
        /***** End Code For B2CS Payload ***********/

        /***** Start Code For CDNR Payload ***********/
        $cdnr_data = $this->gstCDNRPayload($user_id,$returnmonth);
        if(!empty($cdnr_data)) {
            $data_ids[] = $cdnr_ids = $cdnr_data['cdnr_ids'];
            $cdnr_arr = $cdnr_data['cdnr_arr'];
            $dataArr = array_merge($dataArr,$cdnr_arr);
        }
        /***** End Code For CDNR Payload ***********/

        /***** Start Code For CDNUR Payload ***********/
        $cdnur_data = $this->gstCDNURPayload($user_id,$returnmonth);     
        if(!empty($cdnur_data)) {
            $data_ids[] = $cdnur_ids = $cdnur_data['cdnur_ids'];
            $cdnur_arr =  $cdnur_data['cdnur_arr'];
            $dataArr = array_merge($dataArr,$cdnur_arr);
        }
        /***** End Code For CDNUR Payload ***********/

        /***** Start Code For HSN Summary Payload ***********/
        $hsn_data = $this->gstHSNPayload($user_id,$returnmonth);
        if(!empty($hsn_data)) {
            $data_ids[] = $hsn_ids = $hsn_data['hsn_ids'];
            $hsn_arr =  $hsn_data['hsn_arr'];
            $dataArr = array_merge($dataArr,$hsn_arr);
        }
        /***** END Code For HSN Summary Payload ***********/

        /***** Start Code For AT Payload ***********/
        $at_data = $this->gstATPayload($user_id,$returnmonth);
        if(!empty($at_data)) {
            $data_ids[] = $at_ids = $at_data['at_ids'];
            $at_arr =  $at_data['at_arr'];
            $dataArr = array_merge($dataArr,$at_arr);
        }
        /***** End Code For AT Payload ***********/

        $temp_id='';
        $update_final_ids = array();
        $x=0;
        foreach($data_ids as $key=>$value)
        {
            $x=0;
            foreach($value as $key=>$val)
            {
                if(isset($update_final_ids[$key]) && !in_array($val, $update_final_ids[$key]))
                {
                    if(!empty($val))
                    {
                        $y=0;
                        foreach($val['invoice_id'] as $va)
                        {
                            $update_final_ids[$key][$y]['invoice_id']=$va;
                            $y++;
                        }
                    }
                }
                else
                { 
                    if(!empty($val))
                    {
                        $y=0;
                        foreach($val['invoice_id'] as $va)
                        {
                            $update_final_ids[$key][$y]['invoice_id']=$va;
                            $y++;
                        }
                    }
                }
            }
        }
        
        $response['data_ids'] = $update_final_ids;
        $response['data_arr'] = $dataArr;
        return $response;

    }

    private function gstB2BPayload($user_id,$returnmonth) {
        $dataArr = $response =  $b2b_array = $b2b_ids=  array();
        $queryB2B =  "select a.invoice_id,a.company_state,a.billing_gstin_number,a.reference_number,a.invoice_date,a.invoice_total_value,a.supply_place,a.invoice_type,a.supply_type,b.igst_rate,b.cgst_rate,b.sgst_rate,b.taxable_subtotal, sum(b.igst_amount) as igst_amount, sum(b.cgst_amount) as cgst_amount, sum(b.sgst_amount) as sgst_amount,sum(b.cess_amount) as cess_amount from ".TAB_PREFIX."client_invoice a inner join ".TAB_PREFIX."client_invoice_item b on a.invoice_id=b.invoice_id where a.is_gstr1_uploaded='0' and a.status='1' and a.added_by='".$user_id."'  and a.invoice_date like '%".$returnmonth."%' and a.billing_gstin_number!='' group by a.reference_number, b.igst_rate";
        $dataInvB2B = $this->get_results($queryB2B);
        if(isset($dataInvB2B) && !empty($dataInvB2B))
        {

            $x=0;
            $y=0;
            $z=0;
            $a=1;
            $temp_number='';
            $ctin = '';
            foreach($dataInvB2B as $dataIn)
            {
                if($ctin!='' && $ctin!=$dataIn->billing_gstin_number)
                {
                    $x++;
                }
                if($temp_number!='' && $temp_number!=$dataIn->reference_number)
                {
                    $z=0;
                    $y++;
                }
                $dataArr['b2b'][$x]['ctin']=$dataIn->billing_gstin_number;
                $dataArr['b2b'][$x]['inv'][$y]['inum']=$dataIn->reference_number;
                $dataArr['b2b'][$x]['inv'][$y]['idt']=date('d-m-Y',strtotime($dataIn->invoice_date));
                $dataArr['b2b'][$x]['inv'][$y]['val']=(float)$dataIn->invoice_total_value;
                $dataArr['b2b'][$x]['inv'][$y]['pos']=strlen($dataIn->supply_place)=='1' ? '0'.$dataIn->supply_place : $dataIn->supply_place;
                $in_type='';
                if($dataIn->invoice_type!='taxinvoice')
                {
                    $in_type='R';
                }
                else if($dataIn->invoice_type!='sezunitinvoice')
                {
                    $in_type='SEWP';
                }
                else if($dataIn->invoice_type!='deemedexportinvoice')
                {
                    $in_type='DE';
                } 
                $rever_charge = ($dataIn->supply_type=='reversecharge') ? 'Y' : 'N';
                $dataArr['b2b'][$x]['inv'][$y]['inv_typ']=$in_type;
                $dataArr['b2b'][$x]['inv'][$y]['rchrg']=$rever_charge;
                $dataArr['b2b'][$x]['inv'][$y]['itms'][$z]['num']=(int)$a;
                $rt = ($dataIn->company_state==$dataIn->supply_place) ? ($dataIn->sgst_rate+ $dataIn->cgst_rate) :  $dataIn->igst_rate;
                $dataArr['b2b'][$x]['inv'][$y]['itms'][$z]['itm_det']['rt']=(float)$rt;
                $dataArr['b2b'][$x]['inv'][$y]['itms'][$z]['itm_det']['txval']=(float)$dataIn->taxable_subtotal;
                if($dataIn->company_state!=$dataIn->supply_place)
                {
                    $dataArr['b2b'][$x]['inv'][$y]['itms'][$z]['itm_det']['iamt']=(float)$dataIn->igst_amount;
                }
                else
                {
                    $dataArr['b2b'][$x]['inv'][$y]['itms'][$z]['itm_det']['samt']=(float)$dataIn->sgst_amount;
                    $dataArr['b2b'][$x]['inv'][$y]['itms'][$z]['itm_det']['camt']=(float)$dataIn->cgst_amount;
                } 
                $dataArr['b2b'][$x]['inv'][$y]['itms'][$z]['itm_det']['csamt']=(float)$dataIn->cess_amount;
                $z++;
                $temp_number=  $dataIn->reference_number;
                $a++;
                $b2b_array[] = (array)$dataIn;
            }
            if(!empty($b2b_array)) {
                $b2b_ids['client_invoice']['invoice_id'] = array_unique(array_column($b2b_array, 'invoice_id'));   
            }
            
        }
        $response['b2b_ids'] = $b2b_ids;
        $response['b2b_arr'] = $dataArr;
        return $response;
    }

    private function gstB2CLPayload($user_id,$returnmonth) {
        $dataArr = $response =   $b2cl_array = $b2cl_ids =array();
        $queryB2CL =  "select a.invoice_id,a.company_state,a.billing_gstin_number,a.reference_number,a.invoice_date,a.invoice_total_value,a.supply_place,a.invoice_type,a.supply_type,b.igst_rate,b.cgst_rate,b.sgst_rate,b.taxable_subtotal, sum(b.igst_amount) as igst_amount, sum(b.cgst_amount) as cgst_amount, sum(b.sgst_amount) as sgst_amount,sum(b.cess_amount) as cess_amount from ".TAB_PREFIX."client_invoice a inner join ".TAB_PREFIX."client_invoice_item b on a.invoice_id=b.invoice_id where a.is_gstr1_uploaded='0' and a.status='1' and a.added_by='".$user_id."' and a.invoice_date like '%".$returnmonth."%' and a.billing_gstin_number!='' and a.invoice_total_value>'250000' and a.supply_place!=a.company_state group by a.reference_number, b.igst_rate order by a.supply_place "; 
        $dataInvB2CL = $this->get_results($queryB2CL);
        if(isset($dataInvB2CL) && !empty($dataInvB2CL))
        {
              
            $x=0;
            $y=0;
            $z=0;
            $a=1;
            $temp_number='';
            $ctin = '';
            foreach($dataInvB2CL as $dataIn)
            {

                if($temp_number!='' && $temp_number!=$dataIn->reference_number)
                {
                $z=0;
                $y++;
                }
                if($ctin!='' && $ctin!=$dataIn->supply_place)
                {
                $x++;
                $y=0;
                }
                $dataArr['b2cl'][$x]['pos']=strlen($dataIn->supply_place)=='1' ? '0'.$dataIn->supply_place: $dataIn->supply_place;
                $dataArr['b2cl'][$x]['inv'][$y]['inum']=$dataIn->reference_number;
                $dataArr['b2cl'][$x]['inv'][$y]['idt']=date('d-m-Y',strtotime($dataIn->invoice_date));
                $dataArr['b2cl'][$x]['inv'][$y]['val']=(float)$dataIn->invoice_total_value;

                $in_type='';
                if($dataIn->billing_gstin_number!='taxinvoice')
                {
                    $in_type='R';
                }
                else if($dataIn->billing_gstin_number!='sezunitinvoice')
                {
                    $in_type='SEWP';
                }
                else if($dataIn->billing_gstin_number!='deemedexportinvoice')
                {
                    $in_type='DE';
                }
                $rever_charge = ($dataIn->supply_type=='reversecharge') ? 'Y' : 'N';

                $dataArr['b2cl'][$x]['inv'][$y]['itms'][$z]['num']=(int)$a;
                $rt = ($dataIn->company_state==$dataIn->supply_place) ? ($dataIn->sgst_rate+ $dataIn->cgst_rate) :  $dataIn->igst_rate;
                $dataArr['b2cl'][$x]['inv'][$y]['itms'][$z]['itm_det']['rt']=(float)$rt;
                $dataArr['b2cl'][$x]['inv'][$y]['itms'][$z]['itm_det']['txval']=(float)$dataIn->taxable_subtotal;
                if($dataIn->company_state!=$dataIn->supply_place)
                {
                    $dataArr['b2cl'][$x]['inv'][$y]['itms'][$z]['itm_det']['iamt']=(float)$dataIn->igst_amount;
                }
                else
                {
                    $dataArr['b2cl'][$x]['inv'][$y]['itms'][$z]['itm_det']['samt']=(float)$dataIn->sgst_amount;
                    $dataArr['b2cl'][$x]['inv'][$y]['itms'][$z]['itm_det']['camt']=(float)$dataIn->cgst_amount;
                }
                $dataArr['b2cl'][$x]['inv'][$y]['itms'][$z]['itm_det']['csamt']=(float)$dataIn->cess_amount;
                $z++;
                $ctin=$dataIn->supply_place;
                $temp_number=  $dataIn->reference_number;
                $a++;
                $b2cl_array[] = (array)$dataIn;
            }
            if(!empty($b2cl_array)) {
                $b2cl_ids['client_invoice']['invoice_id'] = array_unique(array_column($b2cl_array, 'invoice_id'));
                
            }
            
        }
        $response['b2cl_ids'] = $b2cl_ids;
        $response['b2cl_arr'] = $dataArr;
        return $response;
    }

    private function gstB2CSPayload($user_id,$returnmonth) {
        $dataArr = $response =   $b2cs_array = $b2cs_ids = array();
        $queryB2CS =  "select a.invoice_id,a.company_state,a.billing_gstin_number,a.reference_number,a.invoice_date,a.invoice_total_value,a.supply_place,a.invoice_type,a.supply_type,b.igst_rate,b.cgst_rate,b.sgst_rate,b.taxable_subtotal, sum(b.igst_amount) as igst_amount, sum(b.cgst_amount) as cgst_amount, sum(b.sgst_amount) as sgst_amount,sum(b.cess_amount) as cess_amount from ".TAB_PREFIX."client_invoice a inner join ".TAB_PREFIX."client_invoice_item b on a.invoice_id=b.invoice_id where a.is_gstr1_uploaded='0' and a.status='1' and a.added_by='".$user_id."'  and a.invoice_date like '%".$returnmonth."%' and a.billing_gstin_number='' and (a.supply_place=a.company_state  or (a.supply_place!=a.company_state and a.invoice_total_value<='250000')) group by a.reference_number, b.igst_rate order by a.supply_place ";
        $dataInvB2CS = $this->get_results($queryB2CS);
        if(isset($dataInvB2CS) && !empty($dataInvB2CS))
        {

            $x=0;
            $y=0;
            $z=0;
            $a=1;
            $temp_number='';
            $ctin = '';
            foreach($dataInvB2CS as $dataIn)
            {
                if($ctin!='' && $ctin!=$dataIn->billing_gstin_number)
                {
                    $x++;
                }
                if($temp_number!='' && $temp_number!=$dataIn->reference_number)
                {
                    $z=0;
                    $y++;
                }

                if($dataIn->company_state!=$dataIn->supply_place)
                {
                    $dataArr['b2cs'][$x]['sply_ty']='INTER';
                }
                else
                {
                    $dataArr['b2cs'][$x]['sply_ty']='INTRA';
                }

                $dataArr['b2cs'][$x]['rt']=(float)$rt;
                $dataArr['b2cs'][$x]['typ']='OE';
                $dataArr['b2cs'][$x]['pos']=strlen($dataIn->supply_place)=='1' ? '0'.$dataIn->supply_place : $dataIn->supply_place ;
                $dataArr['b2cs'][$x]['txval']=(float)$rt;
                if($dataIn->company_state!=$dataIn->supply_place)
                {
                    $dataArr['b2cs'][$x]['iamt']=(float)$dataIn->igst_amount;
                }
                else
                {
                    $dataArr['b2cs'][$x]['samt']=(float)$dataIn->sgst_amount;
                    $dataArr['b2cs'][$x]['camt']=(float)$dataIn->cgst_amount;
                }
                $dataArr['b2cs'][$x]['csamt']=(float)$dataIn->cess_amount;
                $x++;
                $z++;
                $temp_number=  $dataIn->reference_number;
                $a++;
                $b2cs_array[] = (array)$dataIn;
            }
            if(!empty($b2cs_array)) {
                $b2cs_ids['client_invoice']['invoice_id'] = array_unique(array_column($b2cs_array, 'invoice_id'));
            }
        }
        $response['b2cs_ids'] = $b2cs_ids;
        $response['b2cs_arr'] = $dataArr;
        return $response;
    }

    private function gstCDNRPayload($user_id,$returnmonth) {
        $dataArr = $response =   $cdnr_array = $cdnr_ids = array();
        $queryCDNR =  "select a.invoice_id,a.corresponding_invoice_number,a.corresponding_invoice_date,a.invoice_document_nature,a.invoice_id,a.company_state,a.billing_gstin_number,a.reference_number,a.invoice_date,a.invoice_total_value,a.supply_place,b.igst_rate,b.cgst_rate,b.sgst_rate,b.taxable_subtotal, sum(b.igst_amount) as igst_amount, sum(b.cgst_amount) as cgst_amount, sum(b.sgst_amount) as sgst_amount,sum(b.cess_amount) as cess_amount from ".TAB_PREFIX."client_rt_invoice a inner join ".TAB_PREFIX."client_rt_invoice_item b on a.invoice_id=b.invoice_id where a.status='1' and a.added_by='".$user_id."' and a.invoice_date like '%".$returnmonth."%' and a.invoice_corresponding_type='taxinvoice' and (a.billing_gstin_number!='') and a.invoice_document_nature!='revisedtaxinvoice' group by a.reference_number, b.igst_rate order by a.supply_place";
        $dataInvCDNR =$this->get_results($queryCDNR);
        if(isset($dataInvCDNR) && !empty($dataInvCDNR))
        {
          
            $x=0;
            $y=0;
            $z=0;
            $a=1;
            $temp_number='';
            $ctin = '';
            foreach($dataInvCDNR as $dataIn)
            {
                if($ctin!='' && $ctin!=$dataIn->billing_gstin_number)
                {
                    $x++;
                }
                if($temp_number!='' && $temp_number!=$dataIn->reference_number)
                {
                    $z=0;
                    $y++;
                }
                $dataArr['cdnr'][$x]['ctin']=$dataIn->billing_gstin_number;
                $nt_type='';
                if($dataIn->invoice_document_nature=='creditnote')
                {
                    $nt_type='C';
                }
                else 
                {
                    $nt_type='D';
                }
                $dataArr['cdnr'][$x]['nt'][$y]['ntty']=$nt_type;
                $dataArr['cdnr'][$x]['nt'][$y]['nt_num']=$dataIn->reference_number;
                $dataArr['cdnr'][$x]['nt'][$y]['nt_dt']=date('d-m-Y',strtotime($dataIn->invoice_date));
                $dataArr['cdnr'][$x]['nt'][$y]['p_gst']="N";
                $dataArr['cdnr'][$x]['nt'][$y]['rsn']="Post Sale Discount";
                $dataArr['cdnr'][$x]['nt'][$y]['inum']=$dataIn->corresponding_invoice_number;
                $dataArr['cdnr'][$x]['nt'][$y]['idt']=date('d-m-Y',strtotime($dataIn->corresponding_invoice_date));
                $dataArr['cdnr'][$x]['nt'][$y]['val']=(float)$dataIn->invoice_total_value;
                $dataArr['cdnr'][$x]['nt'][$y]['itms'][$z]['num']=(int)$a;
                $rt = ($dataIn->company_state==$dataIn->supply_place) ? ($dataIn->sgst_rate+ $dataIn->cgst_rate) :  $dataIn->igst_rate;
                $dataArr['cdnr'][$x]['nt'][$y]['itms'][$z]['itm_det']['rt']=(float)$rt;
                $dataArr['cdnr'][$x]['nt'][$y]['itms'][$z]['itm_det']['txval']=(float)$dataIn->taxable_subtotal;
                if($dataIn->company_state!=$dataIn->supply_place)
                {
                    $dataArr['cdnr'][$x]['nt'][$y]['itms'][$z]['itm_det']['iamt']=(float)$dataIn->igst_amount;
                }
                else
                {
                    $dataArr['cdnr'][$x]['nt'][$y]['itms'][$z]['itm_det']['samt']=(float)$dataIn->sgst_amount;
                    $dataArr['cdnr'][$x]['nt'][$y]['itms'][$z]['itm_det']['camt']=(float)$dataIn->cgst_amount;
                }
                $dataArr['cdnr'][$x]['nt'][$y]['itms'][$z]['itm_det']['csamt']=(float)$dataIn->cess_amount;
                $z++;
                $temp_number=  $dataIn->reference_number;
                $a++;
                $cdnr_array[] = (array)$dataIn;
            }
            if(!empty($cdnr_array)) {
                $cdnr_ids['client_rt_invoice']['invoice_id'] = array_unique(array_column($cdnr_array, 'invoice_id'));
            }
        }
        $response['cdnr_ids'] = $cdnr_ids;
        $response['cdnr_arr'] = $dataArr;
        return $response;
    }

    private function gstCDNURPayload($user_id,$returnmonth) {
        $dataArr = $response =    $cdnur_array = $cdnur_ids = array();
        $queryCDNUR =  "select a.invoice_id,a.corresponding_invoice_number,a.corresponding_invoice_date,a.invoice_document_nature,a.invoice_id,a.company_state,a.billing_gstin_number,a.reference_number,a.invoice_date,a.invoice_total_value,a.supply_place,b.igst_rate,b.cgst_rate,b.sgst_rate,b.taxable_subtotal, sum(b.igst_amount) as igst_amount, sum(b.cgst_amount) as cgst_amount, sum(b.sgst_amount) as sgst_amount,sum(b.cess_amount) as cess_amount from ".TAB_PREFIX."client_rt_invoice a inner join ".TAB_PREFIX."client_rt_invoice_item b on a.invoice_id=b.invoice_id where a.status='1' and a.added_by='".$user_id."' and a.invoice_date like '%".$returnmonth."%' and a.supply_place!=a.company_state and a.invoice_corresponding_type='taxinvoice' and a.billing_gstin_number='' and a.invoice_total_value >'250000' and a.invoice_document_nature!='revisedtaxinvoice' group by a.reference_number, b.igst_rate order by a.supply_place";
        $dataInvCDNUR = $this->get_results($queryCDNUR);
        if(isset($dataInvCDNUR) && !empty($dataInvCDNUR))
        {
          
            $x=0;
            $y=0;
            $y=0;
            $a=1;
            $temp_number='';
            $ctin = '';
            foreach($dataInvCDNUR as $dataIn)
            {
                if($ctin!='' && $ctin!=$dataIn->billing_gstin_number)
                {
                    $x++;
                }
                if($temp_number!='' && $temp_number!=$dataIn->reference_number)
                {
                    $y=0;
                    $y++;
                }

                $dataArr['cdnur'][$x]['typ']="B2CL";
                $dataArr['cdnur'][$x]['ntty']=$dataIn->reference_number;
                $dataArr['cdnur'][$x]['nt_num']=date('d-m-Y',strtotime($dataIn->invoice_date));
                $dataArr['cdnur'][$x]['nt_dt']="N";
                $dataArr['cdnur'][$x]['p_gst']="Post Sale Discount";
                $dataArr['cdnur'][$x]['rsn']=$dataIn->corresponding_invoice_number;
                $dataArr['cdnur'][$x]['inum']=date('d-m-Y',strtotime($dataIn->corresponding_invoice_date));
                $dataArr['cdnur'][$x]['idt']=(float)$dataIn->invoice_total_value;
                $dataArr['cdnur'][$x]['val'][$y]=(int)$a;
                $dataArr['cdnur'][$x]['itms'][$y]['num']=(int)$a;
                $rt = ($dataIn->company_state==$dataIn->supply_place) ? ($dataIn->sgst_rate+ $dataIn->cgst_rate) :  $dataIn->igst_rate;
                $dataArr['cdnur'][$x]['itms'][$y]['itm_det']['rt']=(float)$rt;
                $dataArr['cdnur'][$x]['itms'][$y]['itm_det']['txval']=(float)$dataIn->taxable_subtotal;
                if($dataIn->company_state!=$dataIn->supply_place)
                {
                    $dataArr['cdnur'][$x]['itms'][$y]['itm_det']['iamt']=(float)$dataIn->igst_amount;
                }
                else
                {
                    $dataArr['cdnur'][$x]['itms'][$y]['itm_det']['samt']=(float)$dataIn->sgst_amount;
                    $dataArr['cdnur'][$x]['itms'][$y]['itm_det']['camt']=(float)$dataIn->cgst_amount;
                }
                $dataArr['cdnur'][$x]['itms'][$y]['itm_det']['csamt']=(float)$dataIn->cess_amount;
                $y++;
                $temp_number=  $dataIn->reference_number;
                $a++;
                $cdnur_array[] = (array)$dataIn;
            } 
            if(!empty($cdnur_array)) {
                $cdnur_ids['client_rt_invoice']['invoice_id'] = array_unique(array_column($cdnur_array, 'invoice_id'));
            }
        }
        $response['cdnur_ids'] = $cdnur_ids;
        $response['cdnur_arr'] = $dataArr;
        return $response;
    }

    private function gstHSNPayload($user_id,$returnmonth) {
        $dataArr = $response =   $hsn_array = $hsn_ids = array();
        $queryHsn =  "select a.invoice_id,a.company_state,a.invoice_date,a.invoice_total_value,b.item_name,a.supply_place,a.invoice_type,b.item_hsncode,b.item_quantity,b.item_unit,b.taxable_subtotal, sum(b.igst_amount) as igst_amount, sum(b.cgst_amount) as cgst_amount, sum(b.sgst_amount) as sgst_amount,sum(b.cess_amount) as cess_amount from ".TAB_PREFIX."client_invoice a inner join ".TAB_PREFIX."client_invoice_item b on a.invoice_id=b.invoice_id where a.is_gstr1_uploaded='0' and a.status='1' and a.added_by='".$user_id."' and a.invoice_date like '%".$returnmonth."%' group by b.item_hsncode";

        $dataInvHsn = $this->get_results($queryHsn);   
        if(isset($dataInvHsn) && !empty($dataInvHsn))
        {
            $y=0;
            $a=1;
            foreach($dataInvHsn as $dataIn)
            {
                
                $dataArr['hsn']['data'][$y]['num']=(int)$a;
                $dataArr['hsn']['data'][$y]['hsn_sc']=$dataIn->item_hsncode;
                $dataArr['hsn']['data'][$y]['desc']= $dataIn->item_name;
                $dataArr['hsn']['data'][$y]['uqc']= $dataIn->item_unit;
                $dataArr['hsn']['data'][$y]['qty']= (float)$dataIn->item_quantity;
                $dataArr['hsn']['data'][$y]['val']=(float)$dataIn->invoice_total_value;
                $dataArr['hsn']['data'][$y]['txval']=(float)$dataIn->taxable_subtotal;
                $dataArr['hsn']['data'][$y]['iamt']=(float)$dataIn->igst_amount;
                $dataArr['hsn']['data'][$y]['samt']=(float)$dataIn->sgst_amount;
                $dataArr['hsn']['data'][$y]['camt']=(float)$dataIn->cgst_amount;
                $dataArr['hsn']['data'][$y]['csamt']=(float)$dataIn->cess_amount;
                $a++;
                $y++;
                $hsn_array[] = (array)$dataIn;

            }
            
            if(!empty($hsn_array)) {
                $hsn_ids['client_invoice']['invoice_id'] = array_unique(array_column($hsn_array, 'invoice_id'));
            }
        }
        $response['hsn_ids'] = $hsn_ids;
        $response['hsn_arr'] = $dataArr;
        return $response;
    }

    private function gstATPayload($user_id,$returnmonth) {
        $dataArr = $response =  $at_array = $at_ids = array();
        $queryAt =  "select a.invoice_id,a.company_state,a.reference_number,a.billing_gstin_number,a.reference_number,a.supply_place,a.invoice_date,a.invoice_total_value,b.item_name,b.taxable_subtotal, sum(b.igst_amount) as igst_amount, sum(b.cgst_amount) as cgst_amount, sum(b.sgst_amount) as sgst_amount,sum(b.cess_amount) as cess_amount,b.igst_rate,b.cgst_rate,b.sgst_rate from ".TAB_PREFIX."client_rv_invoice a inner join ".TAB_PREFIX."client_rv_invoice_item b on a.invoice_id=b.invoice_id  where  a.status='1'  and a.added_by='".$user_id."' and a.invoice_date like '%".$returnmonth."%' group by a.supply_place ,b.igst_rate order by a.supply_place ";

        $dataInvAt = $this->get_results($queryAt);
        if(isset($dataInvAt) && !empty($dataInvAt))
        {
            $z=0;
            $y=0;
            $a=1;
            $at_pos='';
            $at_rate = '';
            foreach($dataInvAt as $dataIn)
            {
                $rt = $dataIn->igst_rate;
                //$rt = ($dataIn->company_state==$dataIn->supply_place) ? ($dataIn->sgst_rate+ $dataIn->cgst_rate) :  $dataIn->igst_rate;
                if($at_pos!='' && $at_pos!=$dataIn->supply_place)
                {
                    $y++;
                    $z=0;
                }

                $dataArr['at'][$y]['pos'] = (strlen($dataIn->supply_place)=='1')? '0'.$dataIn->supply_place : $dataIn->supply_place;
                if($dataIn->company_state!=$dataIn->supply_place)
                {
                    $dataArr['at'][$y]['sply_ty']='INTER';
                }
                else
                {
                    $dataArr['at'][$y]['sply_ty']='INTRA';
                }

                $dataArr['at'][$y]['itms'][$z]['rt']=(float)$rt;
                $dataArr['at'][$y]['itms'][$z]['ad_amt']=(float)$dataIn->taxable_subtotal;
                $dataArr['at'][$y]['itms'][$z]['iamt']=(float)$dataIn->igst_amount;
                $dataArr['at'][$y]['itms'][$z]['samt']=(float)$dataIn->sgst_amount;
                $dataArr['at'][$y]['itms'][$z]['camt']=(float)$dataIn->cgst_amount;
                $dataArr['at'][$y]['itms'][$z]['csamt']=(float)$dataIn->cess_amount;
                $at_pos=$dataIn->supply_place;
                $at_rate=$rt;
                $z++;
                $at_array[] = (array)$dataIn;

            }
            if(!empty($at_array)) {
                $at_ids['client_rv_invoice']['invoice_id'] = array_unique(array_column($at_array, 'invoice_id'));
            }
        }
        $response['at_ids'] = $at_ids;
        $response['at_arr'] = $dataArr;
        return $response;
    }



}