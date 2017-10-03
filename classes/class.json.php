<?php
class json extends validation
{
	public function __construct()
	{
		parent ::__construct();
	}
	
	/*get all data*/
	public function getGstr2Payload($userid,$financialMonth)
	{
		$dataArr['b2b']		=	$this->getGstr2B2BPayload($userid,$financialMonth);
		$dataArr['b2bur']	=	$this->getGstr2B2BURPayload($userid,$financialMonth);
		$dataArr['cdn']		=	$this->getGstr2CDNPayload($userid,$financialMonth);
		$dataArr['cdnur']	=	$this->getGstr2CDNURPayload($userid,$financialMonth);
		$dataArr['imp_g']	=	$this->getGstrIMPGPayload($userid,$financialMonth);
		$dataArr['imp_s']	=	$this->getGstrIMPSPayload($userid,$financialMonth);
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
				$dataArr[$x]['inv'][$y]['val'] = (float)$gstr2->invoice_total_value;
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
				$dataArr[$x]['inv'][$y]['item'][$z]['itm_det']['rt']=(float)$gstr2->consolidate_rate;
				$dataArr[$x]['inv'][$y]['item'][$z]['itm_det']['txval']=(float)$gstr2->taxable_subtotal;
				if($gstr2->supplier_billing_state!=$gstr2->supply_place)
				{
					$dataArr[$x]['inv'][$y]['item'][$z]['itm_det']['iamt']=(float)$gstr2->igst;
				}
				else
				{
					$dataArr[$x]['inv'][$y]['item'][$z]['itm_det']['camt']=(float)$gstr2->cgst;
					$dataArr[$x]['inv'][$y]['item'][$z]['itm_det']['samt']=(float)$gstr2->sgst;
				}
				$dataArr[$x]['inv'][$y]['item'][$z]['itm_det']['csamt']=(float)$gstr2->csgst;
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
				$dataArr[$x]['inv'][$y]['pos']=(float)$b2bur->supply_place;
				
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
				$dataArr[$x]['inv'][$y]['itms'][$z]['num']=(float)$count++;
				$dataArr[$x]['inv'][$y]['itms'][$z]['itm_det']['rt']=(float)$b2bur->consolidate_rate;
				$dataArr[$x]['inv'][$y]['itms'][$z]['itm_det']['txval']=(float)$b2bur->taxable_total;
				$dataArr[$x]['inv'][$y]['itms'][$z]['itm_det']['camt']=(float)$b2bur->cgst;
				$dataArr[$x]['inv'][$y]['itms'][$z]['itm_det']['samt']=(float)$b2bur->sgst;
				$dataArr[$x]['inv'][$y]['itms'][$z]['itm_det']['iamt']=(float)$b2bur->igst;
				
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
				$dataArr[$x]['nt'][$y]['itms'][$z]['itm_det']= (float)$GstrCDN->consolidate_rate;
				$dataArr[$x]['nt'][$y]['itms'][$z]['txval']= (float)$GstrCDN->taxable_total;
				$dataArr[$x]['nt'][$y]['itms'][$z]['iamt']= (float)$GstrCDN->igstamount;
				$dataArr[$x]['nt'][$y]['itms'][$z]['camt']= (float)$GstrCDN->cgstamount;
				$dataArr[$x]['nt'][$y]['itms'][$z]['samt']= (float)$GstrCDN->sgstamount;
				$dataArr[$x]['nt'][$y]['itms'][$z]['csamt']= (float)$GstrCDN->cessamount;
				
				
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
				$dataArr[$x]['nt'][$y]['itms'][$z]['itm_det']= (float)$GstrCDN->consolidate_rate;
				$dataArr[$x]['nt'][$y]['itms'][$z]['txval']= (float)$GstrCDN->taxable_total;
				$dataArr[$x]['nt'][$y]['itms'][$z]['iamt']= (float)$GstrCDN->igstamount;
				$dataArr[$x]['nt'][$y]['itms'][$z]['camt']= (float)$GstrCDN->cgstamount;
				$dataArr[$x]['nt'][$y]['itms'][$z]['samt']= (float)$GstrCDN->sgstamount;
				$dataArr[$x]['nt'][$y]['itms'][$z]['csamt']=  (float)$GstrCDN->cessamount;
				
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
	
	/*get IMPG query and json data without supplier_billing_gstin_number*/
	public function getGstrIMPGQuery($userid,$financialMonth,$type='',$ids='',$group_by='')
	{
		$client_purchase_invoice= $this->tableNames['client_purchase_invoice'];
		$client_purchase_invoice_item= $this->tableNames['client_purchase_invoice_item'];
		$gst_master_item= $this->tableNames['item'];
		$client_master_item= $this->tableNames['client_master_item'];
		$query='select
		inv.invoice_type,
		inv.reference_number,
		inv.company_gstin_number,
		inv.import_bill_number, 
		inv.import_bill_date, 
		sum(inv.invoice_total_value) as invoice_total, 
		inv.import_bill_port_code, 
		inv.supplier_billing_gstin_number, 
		sum(it.taxable_subtotal) as taxable_total, 
		it.consolidate_rate, 
		sum(it.igst_amount) as igst,
		sum(it.cess_amount) as cess,
		ms.item_type 
		from '.$client_purchase_invoice.' inv 
		inner join '.$client_purchase_invoice_item.' it on inv.purchase_invoice_id=it.purchase_invoice_id 
		inner join '.$client_master_item.' cms on it.item_id=cms.item_id 
		inner join '.$gst_master_item.' ms on cms.item_category=ms.item_id 
		where inv.added_by="194" 
		and inv.invoice_date like "2017-09%" 
		and inv.is_deleted="0"
		and ms.item_type="1" 
		and inv.is_canceled="0" 
		and inv.status="1" 
		and inv.invoice_nature="purchaseinvoice" 
		and (inv.invoice_type="sezunitinvoice" or inv.invoice_type="importinvoice" or inv.invoice_type="deemedimportinvoice") 
		and inv.supplier_billing_gstin_number!="" ';
		
		if($group_by=='')
			{
				$query .=" group by inv.reference_number,it.consolidate_rate ";
			}
			else
			{
				$query .=" group by ".$group_by." ";
			}
		$query .='order by inv.reference_number';
		return $this->get_results($query);
	}
	public function getGstrIMPGPayload($userid,$financialMonth)
	{
		$data= $this->getGstrIMPGQuery($userid,$financialMonth);
		$dataArr=array();
		$x=$y=$z=0;
		$sply_ty=$temp_ctin=$temp_inv='';
		$count=1;
		if(count($data)>0)
		{
			foreach($data as $GstrIMPG)
			{
				
				if($temp_inv!='' and $temp_inv!=$GstrIMPG->reference_number)
				{
					$y=0;
					$x++;
				}
				
				$dataArr[$x]['is_sez']=($GstrIMPG->invoice_type=='sezunitinvoice')?'Y':'N';
				$dataArr[$x]['stin']=$GstrIMPG->company_gstin_number;
				$dataArr[$x]['boe_num']= $GstrIMPG->import_bill_number;
				$dataArr[$x]['boe_dt']= ($GstrIMPG->import_bill_date=='0000-00-00')?'':date('d-m-Y',strtotime($GstrIMPG->import_bill_date));
				$dataArr[$x]['boe_val']= (float)$GstrIMPG->invoice_total;
				$dataArr[$x]['port_code']= $GstrIMPG->import_bill_port_code;
				$dataArr[$x]['itms'][$y]['num']= $y+$count;
				
				$dataArr[$x]['itms'][$y]['txval']= (float)$GstrIMPG->taxable_total;
				$dataArr[$x]['itms'][$y]['rt']= (float)$GstrIMPG->consolidate_rate;
				$dataArr[$x]['itms'][$y]['iamt']= (float)$GstrIMPG->igst;
				$dataArr[$x]['itms'][$y]['csamt']=(float) $GstrIMPG->cess;
				
				$temp_ctin=$GstrIMPG->supplier_billing_gstin_number;
				$temp_inv=$GstrIMPG->reference_number;
				$y++;
			}
			
		}else
		{
			echo "data not found";
		}
		return $dataArr;
	}	
	
	/*get IMPS query and json data without supplier_billing_gstin_number*/
	public function getGstrIMPSQuery($userid,$financialMonth,$type='',$ids='',$group_by='')
	{
		$client_purchase_invoice= $this->tableNames['client_purchase_invoice'];
		$client_purchase_invoice_item= $this->tableNames['client_purchase_invoice_item'];
		$gst_master_item= $this->tableNames['item'];
		$client_master_item= $this->tableNames['client_master_item'];
		$query='select
		inv.reference_number,
		inv.supplier_billing_gstin_number,
		inv.invoice_date,
		inv.company_gstin_number,
		sum(inv.invoice_total_value) as invoice_total, 
		inv.supply_place,
		sum(it.taxable_subtotal) as taxable_total, 
		it.consolidate_rate, 
		sum(it.igst_amount) as igst,
		sum(it.cess_amount) as cess,
		ms.item_type 
		from '.$client_purchase_invoice.' inv 
		inner join '.$client_purchase_invoice_item.' it on inv.purchase_invoice_id=it.purchase_invoice_id 
		inner join '.$client_master_item.' cms on it.item_id=cms.item_id 
		inner join '.$gst_master_item.' ms on cms.item_category=ms.item_id 
		where inv.added_by="194" 
		and inv.invoice_date like "2017-09%" 
		and inv.is_deleted="0"
		and ms.item_type="1" 
		and inv.is_canceled="0" 
		and inv.status="1" 
		and inv.invoice_nature="purchaseinvoice" 
		and (inv.invoice_type="sezunitinvoice" or inv.invoice_type="importinvoice" or inv.invoice_type="deemedimportinvoice") 
		and inv.supplier_billing_gstin_number!="" ';
		
		if($group_by=='')
			{
				$query .=" group by inv.reference_number,it.consolidate_rate ";
			}
			else
			{
				$query .=" group by ".$group_by." ";
			}
		$query .='order by inv.reference_number';
		return $this->get_results($query);
	}
	public function getGstrIMPSPayload($userid,$financialMonth)
	{
		$data= $this->getGstrIMPSQuery($userid,$financialMonth);
		$dataArr=array();
		$a=$x=$y=$z=0;
		$temp_ctin=$temp_inv='';
		$count=1;
		if(count($data)>0)
		{
			foreach($data as $GstrIMPS)
			{
				if($temp_inv!='' and $temp_inv!=$GstrIMPS->reference_number)
				{
					$y=0;
					$x++;
				}
				if($temp_ctin!='' and $temp_ctin!=$GstrIMPS->supplier_billing_gstin_number)
				{
					$z=0;
					$y=0;
					$y++;
				} 
				
				$dataArr[$x]['inum']=$GstrIMPS->reference_number;
				$dataArr[$x]['idt']=$GstrIMPS->invoice_date;
				$dataArr[$x]['ival']= (float)$GstrIMPS->invoice_total;
				$dataArr[$x]['pos']= $GstrIMPS->supply_place;
				$dataArr[$x]['boe_val']= (float)$GstrIMPS->invoice_total;
				$dataArr[$x]['itms'][$y]['num']= $y+$count;
				
				$dataArr[$x]['itms'][$y]['itm_det']['txval']= (float)$GstrIMPS->taxable_total;
				$dataArr[$x]['itms'][$y]['itm_det']['rt']= (float)$GstrIMPS->consolidate_rate;
				$dataArr[$x]['itms'][$y]['itm_det']['iamt']= (float)$GstrIMPS->igst;
				$dataArr[$x]['itms'][$y]['itm_det']['camt']=(float)$GstrIMPS->cess;
				
				$temp_ctin=$GstrIMPS->supplier_billing_gstin_number;
				$temp_inv=$GstrIMPS->reference_number;
				$y++;
			}
		}else
		{
			echo "data not found";
		}
		return $dataArr;
	}	
	
}

 ?>
