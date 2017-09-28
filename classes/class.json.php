<?php
class json extends validation
{
	public function __construct()
	{
		parent ::__construct();
	}
	
	/*get*/
	public function getGstr2Payload($userid,$financialMonth)
	{
		$dataArr['b2b']=$this->getGstr2B2BPayload($userid,$financialMonth);
		$dataArr['b2bur']=$this->getGstr2B2BURPayload($userid,$financialMonth);
		$dataArr['cdn']=$this->getGstr2CDNPayload($userid,$financialMonth);
		$dataArr['cdnur']=$this->getGstr2CDNURPayload($userid,$financialMonth);
		
		return $dataArr;
	}
	
	/*get cdn B2B and json data*/
	public function getGstr2B2BQuery($userid,$financialMonth,$type='',$ids='',$group_by='')
	{
		$client_purchase_invoice= $this->tableNames['client_purchase_invoice'];
		$client_purchase_invoice_item= $this->tableNames['client_purchase_invoice_item'];
                $master_state = $this->tableNames['state'];
		$data=array();
		$query = 'select inv.company_gstin_number,sum(it.igst_amount) as igst,sum(it.sgst_amount) as sgst,sum(it.cgst_amount) as cgst,sum(it.cess_amount) as csgst,inv.supplier_billing_state,inv.supply_place, inv.supplier_billing_gstin_number,inv.reference_number,inv.invoice_date,inv.invoice_total_value,ms.state_tin as place_of_supply,inv.supply_type,inv.invoice_type,inv.import_supply_meant,it.consolidate_rate,it.taxable_subtotal from '.$client_purchase_invoice.' inv inner join '.$client_purchase_invoice_item.' it on   inv.purchase_invoice_id = it.purchase_invoice_id inner join '.$master_state.' ms on ms.state_id=inv.supply_place where inv.added_by="'.$userid.'" and inv.invoice_date like "'.$financialMonth.'%" and inv.is_deleted="0" and inv.is_canceled="0" and inv.status="1" and inv.invoice_nature="purchaseinvoice" and (inv.invoice_type="taxinvoice" or inv.invoice_type="deemedimportinvoice" or inv.invoice_type="sezunitinvoice") and inv.supplier_billing_gstin_number!="" ';
                if($group_by=='')
                {
                    $query .=" group by inv.reference_number,it.consolidate_rate ";
                }
                else
                {
                    $query .=" group by ".$group_by." ";
                }
                $query .=" order by inv.supplier_billing_gstin_number";
                return $this->get_results($query);
	}

	
	public function getGstr2B2BPayload($userid,$financialMonth)
	{
		$dataArr = array();
		$data = $this->getGstr2B2BQuery($userid,$financialMonth);
		//echo "<pre>";print_r($data);
		$count=1;
		$igst=array();
		$x=$y=$z=0;
		if(count($data)>0)
		{
			
			$temp_inv='';
			$temp_ctin = '';
			foreach($data as $gstr2)
			{
				if($temp_inv!='' and $temp_inv!=$gstr2->reference_number)
				{
					$z=0;
					$y++;
				}
				if($temp_ctin!='' and $temp_ctin!=$gstr2->supplier_billing_gstin_number)
				{
					$z=0;
					$y=0;
					$x++;
				}
				$dataArr[$x]['ctin'] = $gstr2->supplier_billing_gstin_number;
				$dataArr[$x]['inv'][$y]['inum'] = $gstr2->reference_number;
				$dataArr[$x]['inv'][$y]['idt'] = $gstr2->invoice_date;
				$dataArr[$x]['inv'][$y]['val'] = $gstr2->invoice_total_value;
				$dataArr[$x]['inv'][$y]['pos'] = $gstr2->place_of_supply;
				$dataArr[$x]['inv'][$y]['rchrg'] = ($gstr2->supply_type=='reversecharge')? 'Y' : 'N';
				if($gstr2->invoice_type == 'taxinvoice')
				{
					$dataArr[$x]['inv'][$y]['inv_typ'] = 'R';
				}
				else if($gstr2->invoice_type == 'deemedimportinvoice')
				{
					$dataArr[$x]['inv'][$y]['inv_typ'] = 'DE';
				}
				else if($gstr2->invoice_type == 'sezunitinvoice')
				{
					if($gstr2->import_supply_meant=='withpayment')
					{
						$dataArr[$x]['inv'][$y]['inv_typ'] = 'SEWP';
					}
					else
					{
						$dataArr[$x]['inv'][$y]['inv_typ'] = 'SEWOP';
					}
				}
				$dataArr[$x]['inv'][$y]['item'][$z]['num']=(int)$count++;
				$dataArr[$x]['inv'][$y]['item'][$z]['itm_det']['rt']=$gstr2->consolidate_rate;
				$dataArr[$x]['inv'][$y]['item'][$z]['itm_det']['txval']=$gstr2->taxable_subtotal;
				if($gstr2->supplier_billing_state!=$gstr2->supply_place)
				{
					$dataArr[$x]['inv'][$y]['item'][$z]['itm_det']['iamt']=$gstr2->igst;
				}
				else
				{
					$dataArr[$x]['inv'][$y]['item'][$z]['itm_det']['camt']=$gstr2->cgst;
					$dataArr[$x]['inv'][$y]['item'][$z]['itm_det']['samt']=$gstr2->sgst;
				}
				$dataArr[$x]['inv'][$y]['item'][$z]['itm_det']['csamt']=$gstr2->csgst;
				$temp_ctin=$gstr2->supplier_billing_gstin_number;
				$temp_inv=$gstr2->reference_number;
				$z++;
			}
		}
		//$this->pr($dataArr);
		return $dataArr;
	}
		
	/*get cdn B2BUR and json data*/
	public function getGstr2B2BURQuery($userid,$financialMonth,$type='',$ids='',$group_by='')
	{
		$client_purchase_invoice= $this->tableNames['client_purchase_invoice'];
		$client_purchase_invoice_item= $this->tableNames['client_purchase_invoice_item'];
		$master_state = $this->tableNames['state'];
		$query='select 
		inv.reference_number,
		inv.invoice_date,
		sum(inv.invoice_total_value) as invoice_total,
		inv.supply_place,
		inv.company_state,
		inv.supply_place,
		inv.supplier_billing_gstin_number,
		it.consolidate_rate,
		sum(it.taxable_subtotal) as taxable_total,
		sum(it.cgst_amount) as cgst,
		sum(it.sgst_amount) as sgst,
		sum(it.igst_amount) as igst,
		sum(it.cess_amount) as cess
		from '.$client_purchase_invoice.' inv 
		inner join  '.$client_purchase_invoice_item.' it on inv.purchase_invoice_id=it.purchase_invoice_id where inv.added_by="'.$userid.'" and inv.invoice_date like "'.$financialMonth.'%" and inv.is_deleted="0" and inv.is_canceled="0" and inv.status="1" and inv.invoice_nature="purchaseinvoice" and (inv.invoice_type="taxinvoice" or inv.invoice_type="deemedimportinvoice" or inv.invoice_type="sezunitinvoice") and inv.supplier_billing_gstin_number="" ';
		if($group_by=='')
			{
				$query .=" group by inv.reference_number,it.consolidate_rate ";
			}
			else
			{
				$query .=" group by ".$group_by." ";
			}
		$query .='order by inv.supplier_billing_gstin_number';
		return $this->get_results($query);
	}
		
	public function getGstr2B2BURPayload($userid,$financialMonth)
	{
		$data= $this->getGstr2B2BURQuery($userid,$financialMonth);
		$dataArr=array();
		$x=$y=$z=0;
		$sply_ty='';
		$count=1;
		
		if(count($data)>0)
		{
			$temp_inv='';
			$temp_ctin = '';
			foreach($data as $b2bur)
			{
				if($temp_inv!='' and $temp_inv!=$b2bur->reference_number)
				{
					$z=0;
					$y++;
				}
				if($temp_ctin!='' and $temp_ctin!=$b2bur->supplier_billing_gstin_number)
				{
					$z=0;
					$y=0;
					$x++;
				} 
				
				$dataArr[$x]['inv'][$y]['chksum']='';
				$dataArr[$x]['inv'][$y]['inum']=$b2bur->reference_number;
				$dataArr[$x]['inv'][$y]['idt']=$b2bur->invoice_date;
				$dataArr[$x]['inv'][$y]['val']=$b2bur->invoice_total;
				$dataArr[$x]['inv'][$y]['pos']=(int)$b2bur->supply_place;
				
				if($b2bur->company_state!='' and $b2bur->supply_place!='')
				{
					if($b2bur->company_state===$b2bur->supply_place)
					{
						$sply_ty='INTER';
					}else
					{
						$sply_ty='INTRA';
					}
				}
				
				$dataArr[$x]['inv'][$y]['sply_ty']=$sply_ty;
				$dataArr[$x]['inv'][$y]['itms'][$z]['num']=(int)$count++;
				$dataArr[$x]['inv'][$y]['itms'][$z]['itm_det']['rt']=(int)$b2bur->consolidate_rate;
				$dataArr[$x]['inv'][$y]['itms'][$z]['itm_det']['txval']=(int)$b2bur->taxable_total;
				$dataArr[$x]['inv'][$y]['itms'][$z]['itm_det']['camt']=(int)$b2bur->cgst;
				$dataArr[$x]['inv'][$y]['itms'][$z]['itm_det']['samt']=(int)$b2bur->sgst;
				$dataArr[$x]['inv'][$y]['itms'][$z]['itm_det']['iamt']=(int)$b2bur->igst;
				
				$temp_ctin=$b2bur->supplier_billing_gstin_number;
				$temp_inv=$b2bur->reference_number;
				$z++;
			}	
		}else
		{
			echo "record not found";
		}
		
		return $dataArr;
	}
	
	/*get cdn query and json data with supplier_billing_gstin_number*/
	public function getGstrCDNQuery($userid,$financialMonth,$type='',$ids='',$group_by='')
	{
		$client_purchase_invoice= $this->tableNames['client_purchase_invoice'];
		$client_purchase_invoice_item= $this->tableNames['client_purchase_invoice_item'];
		$query='select 
		inv.company_gstin_number,
		inv.invoice_type,
		inv.reference_number,
		inv.invoice_date,
		inv.reason_issuing_document,
		inv.corresponding_document_number,	
		inv.corresponding_document_date,	
		inv.invoice_total_value,	
		inv.supplier_billing_gstin_number,
		it.consolidate_rate,
		sum(it.taxable_subtotal) as taxable_total,
		sum(it.igst_amount) as igstamount,
		sum(it.cgst_amount) as cgstamount,
		sum(it.sgst_amount) as sgstamount,
		sum(it.cess_amount) as cessamount
		
		from '.$client_purchase_invoice.' inv 
		inner join  '.$client_purchase_invoice_item.' it on inv.purchase_invoice_id=it.purchase_invoice_id where inv.added_by="'.$userid.'" 
		and inv.invoice_date like "'.$financialMonth.'%" 
		and inv.is_deleted="0" 
		and inv.is_canceled="0" 
		and inv.status="1" 
		and inv.invoice_nature="purchaseinvoice" 
		and (inv.invoice_type="debitnote" or inv.invoice_type="creditnote" or inv.invoice_type="refundvoucherinvoice") 
		and inv.supplier_billing_gstin_number!=""';
		
		if($group_by=='')
		{
			$query .=" group by inv.reference_number,it.consolidate_rate ";
		}
		else
		{
			$query .=" group by ".$group_by." ";
		}
		$query .='order by inv.supplier_billing_gstin_number';
		return $this->get_results($query);
	}	
	public function getGstr2CDNPayload($userid,$financialMonth)
	{
		$data= $this->getGstrCDNQuery($userid,$financialMonth);
		$dataArr=array();
		$x=$y=$z=0;
		$sply_ty=$temp_ctin=$temp_inv='';
		$count=1;
		if(count($data)>0)
		{
			foreach($data as $GstrCDN )
			{
				
				if($temp_inv!='' and $temp_inv!=$GstrCDN->reference_number)
				{
					$z=0;
					$y++;
				}
				if($temp_ctin!='' and $temp_ctin!=$GstrCDN->supplier_billing_gstin_number)
				{
					$z=0;
					$y=0;
					$x++;
				} 
				$dataArr[$x]['ctin']=$GstrCDN->company_gstin_number;
				if($GstrCDN->invoice_type=='debitnote')
				{
					$ntty='D';
				}elseif($GstrCDN->invoice_type==creditnote)
				{
					$ntty='C';
				}elseif($GstrCDN->invoice_type==refundvoucherinvoice)
				{
					$ntty='R';
				}
				$dataArr[$x]['nt'][$y]['ntty']=$ntty;
				$dataArr[$x]['nt'][$y]['nt_num']=$GstrCDN->reference_number;
				$dataArr[$x]['nt'][$y]['nt_dt']= date('d-m-Y',strtotime($GstrCDN->invoice_date));
				$dataArr[$x]['nt'][$y]['rsn']= 'Y';
				$dataArr[$x]['nt'][$y]['p_gst']= $GstrCDN->reason_issuing_document;
				$dataArr[$x]['nt'][$y]['inum']= $GstrCDN->corresponding_document_number;
				$dataArr[$x]['nt'][$y]['idt']= date('d-m-Y',strtotime($GstrCDN->corresponding_document_date));
				$dataArr[$x]['nt'][$y]['val']= $GstrCDN->invoice_total_value;
				
				$dataArr[$x]['nt'][$y]['itms'][$z]['num']= $count++;
				$dataArr[$x]['nt'][$y]['itms'][$z]['itm_det']= $GstrCDN->consolidate_rate;
				$dataArr[$x]['nt'][$y]['itms'][$z]['txval']= $GstrCDN->taxable_total;
				$dataArr[$x]['nt'][$y]['itms'][$z]['iamt']= $GstrCDN->igstamount;
				$dataArr[$x]['nt'][$y]['itms'][$z]['camt']= $GstrCDN->cgstamount;
				$dataArr[$x]['nt'][$y]['itms'][$z]['samt']= $GstrCDN->sgstamount;
				$dataArr[$x]['nt'][$y]['itms'][$z]['csamt']= $GstrCDN->cessamount;
				
				
				$temp_ctin=$GstrCDN->supplier_billing_gstin_number;
				$temp_inv=$GstrCDN->reference_number;
				$z++;
			}
		}else
		{
			echo "Record not found";
		}
		return $dataArr;
		
	}
	
	/*get cdn query and json data without supplier_billing_gstin_number*/
	public function getGstrCDNURQuery($userid,$financialMonth,$type='',$ids='',$group_by='')
	{
		$client_purchase_invoice= $this->tableNames['client_purchase_invoice'];
		$client_purchase_invoice_item= $this->tableNames['client_purchase_invoice_item'];
		$query='select 
		inv.company_gstin_number,
		inv.invoice_type,
		inv.reference_number,
		inv.invoice_date,
		inv.reason_issuing_document,
		inv.corresponding_document_number,	
		inv.corresponding_document_date,	
		inv.invoice_total_value,	
		inv.supplier_billing_gstin_number,
		it.consolidate_rate,
		sum(it.taxable_subtotal) as taxable_total,
		sum(it.igst_amount) as igstamount,
		sum(it.cgst_amount) as cgstamount,
		sum(it.sgst_amount) as sgstamount,
		sum(it.cess_amount) as cessamount
		
		from '.$client_purchase_invoice.' inv 
		inner join  '.$client_purchase_invoice_item.' it on inv.purchase_invoice_id=it.purchase_invoice_id where inv.added_by="'.$userid.'" 
		and inv.invoice_date like "'.$financialMonth.'%" 
		and inv.is_deleted="0" 
		and inv.is_canceled="0" 
		and inv.status="1" 
		and inv.invoice_nature="purchaseinvoice" 
		and (inv.invoice_type="debitnote" or inv.invoice_type="creditnote" or inv.invoice_type="refundvoucherinvoice") 
		and inv.supplier_billing_gstin_number=""';
		
		if($group_by=='')
			{
				$query .=" group by inv.reference_number,it.consolidate_rate ";
			}
			else
			{
				$query .=" group by ".$group_by." ";
			}
		$query .='order by inv.supplier_billing_gstin_number';
		return $this->get_results($query);
	}	
	public function getGstr2CDNURPayload($userid,$financialMonth)
	{
		$data= $this->getGstrCDNURQuery($userid,$financialMonth);
		$dataArr=array();
		$x=$y=$z=0;
		$sply_ty=$temp_ctin=$temp_inv='';
		$count=1;
		if(count($data)>0)
		{
			foreach($data as $GstrCDN )
			{
				
				if($temp_inv!='' and $temp_inv!=$GstrCDN->reference_number)
				{
					$z=0;
					$y++;
				}
				if($temp_ctin!='' and $temp_ctin!=$GstrCDN->supplier_billing_gstin_number)
				{
					$z=0;
					$y=0;
					$x++;
				} 
				$dataArr[$x]['ctin']=$GstrCDN->company_gstin_number;
				if($GstrCDN->invoice_type=='debitnote')
				{
					$ntty='D';
				}elseif($GstrCDN->invoice_type=='creditnote')
				{
					$ntty='C';
				}elseif($GstrCDN->invoice_type=='refundvoucherinvoice')
				{
					$ntty='R';
				}
				$dataArr[$x]['nt'][$y]['ntty']=$ntty;
				$dataArr[$x]['nt'][$y]['nt_num']=$GstrCDN->reference_number;
				$dataArr[$x]['nt'][$y]['nt_dt']= date('d-m-Y',strtotime($GstrCDN->invoice_date));
				$dataArr[$x]['nt'][$y]['rsn']= 'Y';
				$dataArr[$x]['nt'][$y]['p_gst']= $GstrCDN->reason_issuing_document;
				$dataArr[$x]['nt'][$y]['inum']= $GstrCDN->corresponding_document_number;
				$dataArr[$x]['nt'][$y]['idt']= date('d-m-Y',strtotime($GstrCDN->corresponding_document_date));
				$dataArr[$x]['nt'][$y]['val']= $GstrCDN->invoice_total_value;
				
				$dataArr[$x]['nt'][$y]['itms'][$z]['num']= $count++;
				$dataArr[$x]['nt'][$y]['itms'][$z]['itm_det']= $GstrCDN->consolidate_rate;
				$dataArr[$x]['nt'][$y]['itms'][$z]['txval']= $GstrCDN->taxable_total;
				$dataArr[$x]['nt'][$y]['itms'][$z]['iamt']= $GstrCDN->igstamount;
				$dataArr[$x]['nt'][$y]['itms'][$z]['camt']= $GstrCDN->cgstamount;
				$dataArr[$x]['nt'][$y]['itms'][$z]['samt']= $GstrCDN->sgstamount;
				$dataArr[$x]['nt'][$y]['itms'][$z]['csamt']= $GstrCDN->cessamount;
				
				$temp_ctin=$GstrCDN->supplier_billing_gstin_number;
				$temp_inv=$GstrCDN->reference_number;
				$z++;
			}
		}else
		{
			echo "Record not found";
		}
		return $dataArr;
		
	}
}

 ?>
