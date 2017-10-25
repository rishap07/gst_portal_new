<?php
class json extends validation

{
	public function __construct()

	{
		parent::__construct();
	}  
	// get Gstr2 Payload
	public function getGstr2Payload($userid, $financialMonth)

	{
		if (!empty($this->getGstr2B2BPayload($userid, $financialMonth)))
		{
			$dataArr['b2b'] = $this->getGstr2B2BPayload($userid, $financialMonth);
		}
		if (!empty($this->getGstr2B2BURPayload($userid, $financialMonth)))
		{
			$dataArr['b2bur'] = $this->getGstr2B2BURPayload($userid, $financialMonth);
		}
		if (!empty($this->getGstr2CDNPayload($userid, $financialMonth)))
		{
			$dataArr['cdn'] = $this->getGstr2CDNPayload($userid, $financialMonth);
		}
		if (!empty($this->getGstr2CDNURPayload($userid, $financialMonth)))
		{
			$dataArr['cdnur'] = $this->getGstr2CDNURPayload($userid, $financialMonth);
		}
		if (!empty($this->getGstr2IMPGPayload($userid, $financialMonth)))
		{
			$dataArr['imp_g'] = $this->getGstr2IMPGPayload($userid, $financialMonth);
		}
		if (!empty($this->getGstr2IMPSPayload($userid, $financialMonth)))
		{
			$dataArr['imp_s'] = $this->getGstr2IMPSPayload($userid, $financialMonth);
		}
		if (!empty($this->getGstr2NilRatedQuery($userid, $financialMonth)))
		{
			$dataArr['NilRated'] = $this->getGstr2NilRatedQuery($userid, $financialMonth);
		}
		// $dataArr['advanced']=    $this->getGstr2AdvanceQuery($userid,$financialMonth);
		// $dataArr['advancedAdjust']=    $this->getGstr2AdvanceAdjustQuery($userid,$financialMonth);
		return $dataArr;
	}
	// get Gstr2 Query
	public function getGstr2Query($userid, $financialMonth, $arr_type = true)

	{
		$dataArr['b2b'] = $this->getGstr2B2BQuery($userid, $financialMonth, $arr_type);
		$dataArr['b2bur'] = $this->getGstr2B2BURQuery($userid, $financialMonth, $arr_type);
		$dataArr['cdn'] = $this->getGstr2CDNQuery($userid, $financialMonth, $arr_type);
		$dataArr['cdnur'] = $this->getGstr2CDNURQuery($userid, $financialMonth, $arr_type);
		$dataArr['imp_g'] = $this->getGstr2IMPGQuery($userid, $financialMonth, $arr_type);
		$dataArr['imp_s'] = $this->getGstr2IMPSQuery($userid, $financialMonth, $arr_type);
		return $dataArr;
	}
	// Gstr2 array Data for  gstr2 return summary
	public function addGstr2Data($userid, $financialMonth, $arr_type = true)

	{
		$dataArr = array();
		$dataArr['b2b'] = $this->addGstr2B2BData($userid, $financialMonth, $arr_type = true);
		$dataArr['b2bur'] = $this->addGstr2B2B2URData($userid, $financialMonth, $arr_type = true);
		$dataArr['cdn'] = $this->addGstr2CDNData($userid, $financialMonth, $arr_type = true);
		$dataArr['cdnur'] = $this->addGstr2CDNDURata($userid, $financialMonth, $arr_type = true);
		$dataArr['imp_g'] = $this->addGstr2IMPGData($userid, $financialMonth, $arr_type = true);
		$dataArr['imp_s'] = $this->addGstr2IMPSData($userid, $financialMonth, $arr_type = true);
		return array_merge($dataArr['b2b'], $dataArr['b2bur'], $dataArr['cdn'], $dataArr['cdnur'], $dataArr['imp_g'], $dataArr['imp_s']);
	}
	// Gstr2 B2B2 array Data
	public function addGstr2B2BData($userid, $financialMonth, $arr_type = true)
	{
		$dataArry = $this->getGstr2Query($userid, $financialMonth, $arr_type);
		$arrayB2BData = array();
		for ($i = 0; $i < count($dataArry['b2b']); $i++)
		{
			$dataArry['b2b'][$i]['supply_place'];
			$dataArry['b2b'][$i]['company_state'];
			// supply_type
			if ($dataArry['b2b'][$i]['supply_place'] != $dataArry['b2b'][$i]['company_state'])
			{
				$supply_type = 'INTER';
			}
			else
			{
				$supply_type = 'INTRA';
			}
			// reversecharge
			if ($dataArry['b2b'][$i]['supply_type'] == 'reversecharge')
			{
				$reverse_charge = 'Y';
			}
			else
			{
				$reverse_charge = 'N';
			}
			// ur_type
			if ($dataArry['b2b'][$i]['invoice_total'] > '250000' && $dataArry['b2b'][$i]['supplier_billing_gstin_number'])
			{
				$ur_type = 'B2CL';
			}
			elseif ($dataArry['b2b'][$i]['import_supply_meant'] == 'withpayment')
			{
				$ur_type = 'EXPWP';
			}
			elseif ($dataArry['b2b'][$i]['import_supply_meant'] == 'withoutpayment')
			{
				$ur_type = 'EXPWOP';
			}
			elseif ($dataArry['b2b'][$i]['import_supply_meant'] == '')
			{
				$ur_type = '';
			}
			// document_type
			if ($dataArry['b2b'][$i]['invoice_type'] == 'creditnote')
			{
				$document_type = 'C';
			}
			elseif ($dataArry['b2b'][$i]['invoice_type'] == 'debitnote')
			{
				$document_type = 'D';
			}
			elseif ($dataArry['b2b'][$i]['invoice_type'] == 'receiptvoucherinvoice')
			{
				$document_type = 'R';
			}
			elseif ($dataArry['b2b'][$i]['invoice_type'] != '')
			{
				$document_type = $dataArry['b2b'][$i]['invoice_type'];
			}
			elseif ($dataArry['b2b'][$i]['invoice_type'] == '')
			{
				$document_type = '';
			}
			// invoice_type
			if ($dataArry['b2b'][$i]['invoice_type'] == 'taxinvoice')
			{
				$invoice_type = 'R';
			}
			elseif ($dataArry['b2b'][$i]['invoice_type'] == 'deemedimportinvoice')
			{
				$invoice_type = 'DE';
			}
			elseif ($dataArry['b2b'][$i]['invoice_type'] == 'sezunitinvoice' && $dataArry['b2b'][$i]['import_supply_meant'] == 'withpayment')
			{
				$invoice_type = 'SEWP';
			}
			elseif ($dataArry['b2b'][$i]['invoice_type'] == 'sezunitinvoice' && $dataArry['b2b'][$i]['import_supply_meant'] == 'withoutpayment')
			{
				$invoice_type = 'SEWOP';
			}
			elseif ($dataArry['b2b'][$i]['invoice_type'] == '')
			{
				$invoice_type = '';
			}
			$arrayB2BData[] = array(
				'invoice_nature' => 'b2b',
				'invoice_type' => $invoice_type,
				'recipient_gstin' => $dataArry['b2b'][$i]['supplier_billing_gstin_number'],
				'invoice_number' => $dataArry['b2b'][$i]['reference_number'],
				'invoice_date' => $dataArry['b2b'][$i]['invoice_date'],
				'invoice_value' => $dataArry['b2b'][$i]['invoice_total'],
				'place_of_supply' => $dataArry['b2b'][$i]['supply_place'],
				'supply_type' => $supply_type,
				'reverse_charge' => $reverse_charge,
				'rate' => $dataArry['b2b'][$i]['consolidate_rate'],
				'taxable_value' => $dataArry['b2b'][$i]['taxable_total'],
				'cgst_amount' => $dataArry['b2b'][$i]['cgst'],
				'sgst_amount' => $dataArry['b2b'][$i]['sgst'],
				'igst_amount' => $dataArry['b2b'][$i]['igst'],
				'cess_amount' => $dataArry['b2b'][$i]['cess'],
				'type' => '',
				'original_invoice_number' => '',
				'original_invoice_date' => '',
				'ur_type' => $ur_type,
				'document_type' => $document_type,
				'reason_for_issuing_document' => $dataArry['b2b'][$i]['reason_issuing_document'],
				'pre_gst' => '',
				'port_code' => '',
				'shipping_bill_number' => $dataArry['b2b'][$i]['import_bill_number'],
				'shipping_bill_date' => $dataArry['b2b'][$i]['import_bill_date'],
				'return_period' => date('Y-m', strtotime($dataArry['b2b'][$i]['invoice_date'])) ,
				'financial_year' => $dataArry['b2b'][$i]['financial_year'],
				'created_from' => $dataArry['b2b'][$i]['created_from'],
				'added_by' => $userid,
				'added_date' => date('Y-m-d H:i:s', time()) ,
				'updated_by' => $userid,
				'updated_date' => date('Y-m-d H:i:s', time()) ,
				'deleted_by' => $userid,
				'deleted_date' => date('Y-m-d H:i:s', time())
			);
		}
		$condition['invoice_nature'] = "b2b";
		$condition['return_period'] = $financialMonth;
		$this->deletData($this->tableNames['gstr2_return_summary'], $condition);
		return $arrayB2BData;
	}
	// Gstr2 B2B2UR array Data
	public function addGstr2B2B2URData($userid, $financialMonth, $arr_type = true)

	{
		$dataArry['b2bur'] = $this->getGstr2B2BURQuery($userid, $financialMonth, $arr_type = true);
		$arrayB2BUR = array();
		for ($i = 0; $i < count($dataArry['b2bur']); $i++)
		{
			// supply_type
			if ($dataArry['b2bur'][$i]['supply_place'] != $dataArry['b2bur'][$i]['company_state'])
			{
				$supply_type = 'INTER';
			}
			else
			{
				$supply_type = 'INTRA';
			}
			// reversecharge
			if ($dataArry['b2bur'][$i]['supply_type'] == 'reversecharge')
			{	$reverse_charge = 'Y';
			}else
			{	$reverse_charge = 'N';
			}
			// ur_type
			if ($dataArry['b2bur'][$i]['invoice_total'] > '250000' && $dataArry['b2bur'][$i]['supplier_billing_gstin_number'])
			{
				$ur_type = 'B2CL';
			}elseif ($dataArry['b2bur'][$i]['import_supply_meant'] == 'withpayment')
			{
				$ur_type = 'EXPWP';
			}elseif ($dataArry['b2bur'][$i]['import_supply_meant'] == 'withoutpayment')
			{
				$ur_type = 'EXPWOP';
			}elseif ($dataArry['b2bur'][$i]['import_supply_meant'] == '')
			{
				$ur_type = '';
			}
			// document_type
			if ($dataArry['b2bur'][$i]['invoice_type'] == 'creditnote')
			{
				$document_type = 'C';
			}
			elseif ($dataArry['b2bur'][$i]['invoice_type'] == 'debitnote')
			{
				$document_type = 'D';
			}
			elseif ($dataArry['b2bur'][$i]['invoice_type'] == 'receiptvoucherinvoice')
			{
				$document_type = 'R';
			}
			elseif ($dataArry['b2bur'][$i]['invoice_type'] != '')
			{
				$document_type = $dataArry['b2bur'][$i]['invoice_type'];
			}
			elseif ($dataArry['b2bur'][$i]['invoice_type'] == '')
			{
				$document_type = '';
			}
			$arrayB2BUR[] = array(
				'invoice_nature' => 'b2bur',
				'invoice_type' => '',
				'recipient_gstin' => $dataArry['b2bur'][$i]['supplier_billing_gstin_number'],
				'invoice_number' => $dataArry['b2bur'][$i]['reference_number'],
				'invoice_date' => $dataArry['b2bur'][$i]['invoice_date'],
				'invoice_value' => $dataArry['b2bur'][$i]['invoice_total'],
				'place_of_supply' => $dataArry['b2bur'][$i]['supply_place'],
				'supply_type' => $supply_type,
				'reverse_charge' => $reverse_charge,
				'rate' => $dataArry['b2bur'][$i]['consolidate_rate'],
				'taxable_value' => $dataArry['b2bur'][$i]['taxable_total'],
				'cgst_amount' => $dataArry['b2bur'][$i]['cgst'],
				'sgst_amount' => $dataArry['b2bur'][$i]['sgst'],
				'igst_amount' => $dataArry['b2bur'][$i]['igst'],
				'cess_amount' => $dataArry['b2bur'][$i]['cess'],
				'type' => '',
				'original_invoice_number' => '',
				'original_invoice_date' => '',
				'ur_type' => $ur_type,
				'document_type' => $document_type,
				'reason_for_issuing_document' => $dataArry['b2bur'][$i]['reason_issuing_document'],
				'pre_gst' => '',
				'port_code' => '',
				'shipping_bill_number' => $dataArry['b2bur'][$i]['import_bill_number'],
				'shipping_bill_date' => $dataArry['b2bur'][$i]['import_bill_date'],
				'return_period' => date('Y-m', strtotime($dataArry['b2bur'][$i]['invoice_date'])) ,
				'financial_year' => $dataArry['b2bur'][$i]['financial_year'],
				'created_from' => $dataArry['b2bur'][$i]['created_from'],
				'added_by' => $userid,
				'added_date' => date('Y-m-d H:i:s', time()) ,
				'updated_by' => $userid,
				'updated_date' => date('Y-m-d H:i:s', time()) ,
				'deleted_by' => $userid,
				'deleted_date' => date('Y-m-d H:i:s', time())
			);
		}
		$condition['invoice_nature'] = "b2bur";
		$condition['return_period'] = $financialMonth;
		$this->deletData($this->tableNames['gstr2_return_summary'], $condition);
		return $arrayB2BUR;
	}
	// Gstr2 CDN array Data
	public function addGstr2CDNData($userid, $financialMonth, $arr_type = true)

	{
		$dataArry['cdn'] = $this->getGstr2CDNQuery($userid, $financialMonth, $arr_type = true);
		$arrayCDN = array();
		for ($i = 0; $i < count($dataArry['cdn']); $i++)
		{
			// supply_type
			if ($dataArry['cdn'][$i]['supply_place'] != $dataArry['cdn'][$i]['company_state'])
			{
				$supply_type = 'INTER';
			}
			else
			{
				$supply_type = 'INTRA';
			}
			// reversecharge
			if ($dataArry['cdn'][$i]['supply_type'] == 'reversecharge')
			{
				$reverse_charge = 'Y';
			}
			else
			{
				$reverse_charge = 'N';
			}
			// ur_type
			if ($dataArry['cdn'][$i]['invoice_total'] > '250000' && $dataArry['cdn'][$i]['supplier_billing_gstin_number'])
			{
				$ur_type = 'B2CL';
			}
			elseif ($dataArry['cdn'][$i]['import_supply_meant'] == 'withpayment')
			{
				$ur_type = 'EXPWP';
			}
			elseif ($dataArry['cdn'][$i]['import_supply_meant'] == '')
			{
				$ur_type = '';
			}
			// document_type
			if ($dataArry['cdn'][$i]['invoice_type'] == 'creditnote')
			{
				$document_type = 'C';
			}
			elseif ($dataArry['cdn'][$i]['invoice_type'] == 'debitnote')
			{
				$document_type = 'D';
			}
			elseif ($dataArry['cdn'][$i]['invoice_type'] == 'receiptvoucherinvoice')
			{
				$document_type = 'R';
			}
			elseif ($dataArry['cdn'][$i]['invoice_type'] != '')
			{
				$document_type = $dataArry['cdn'][$i]['invoice_type'];
			}
			elseif ($dataArry['cdn'][$i]['invoice_type'] == '')
			{
				$document_type = '';
			}
			$arrayCDN[] = array(
				'invoice_nature' => 'cdn',
				'invoice_type' => '',
				'recipient_gstin' => $dataArry['cdn'][$i]['supplier_billing_gstin_number'],
				'invoice_number' => $dataArry['cdn'][$i]['reference_number'],
				'invoice_date' => $dataArry['cdn'][$i]['invoice_date'],
				'invoice_value' => $dataArry['cdn'][$i]['invoice_total'],
				'place_of_supply' => $dataArry['cdn'][$i]['supply_place'],
				'supply_type' => $supply_type,
				'reverse_charge' => $reverse_charge,
				'rate' => $dataArry['cdn'][$i]['consolidate_rate'],
				'taxable_value' => $dataArry['cdn'][$i]['taxable_total'],
				'cgst_amount' => $dataArry['cdn'][$i]['cgst'],
				'sgst_amount' => $dataArry['cdn'][$i]['sgst'],
				'igst_amount' => $dataArry['cdn'][$i]['igst'],
				'cess_amount' => $dataArry['cdn'][$i]['cess'],
				'type' => '',
				'original_invoice_number' => '',
				'original_invoice_date' => '',
				'ur_type' => $ur_type,
				'document_type' => $document_type,
				'reason_for_issuing_document' => $dataArry['cdn'][$i]['reason_issuing_document'],
				'pre_gst' => '',
				'port_code' => '',
				'shipping_bill_number' => $dataArry['cdn'][$i]['import_bill_number'],
				'shipping_bill_date' => $dataArry['cdn'][$i]['import_bill_date'],
				'return_period' => date('Y-m', strtotime($dataArry['cdn'][$i]['invoice_date'])) ,
				'financial_year' => $dataArry['cdn'][$i]['financial_year'],
				'created_from' => $dataArry['cdn'][$i]['created_from'],
				'added_by' => $userid,
				'added_date' => date('Y-m-d H:i:s', time()) ,
				'updated_by' => $userid,
				'updated_date' => date('Y-m-d H:i:s', time()) ,
				'deleted_by' => $userid,
				'deleted_date' => date('Y-m-d H:i:s', time())
			);
		}
		$condition['invoice_nature'] = "cdn";
		$condition['return_period'] = $financialMonth;
		$this->deletData($this->tableNames['gstr2_return_summary'], $condition);
		return $arrayCDN;
	}
	// Gstr2 CDNUR array Data
	public function addGstr2CDNDURata($userid, $financialMonth, $arr_type = true)

	{
		$dataArry['cdnur'] = $this->getGstr2CDNURQuery($userid, $financialMonth, $arr_type = true);
		$arrayCDNUR = array();
		for ($i = 0; $i < count($dataArry['cdnur']); $i++)
		{
			// supply_type
			if ($dataArry['cdnur'][$i]['supply_place'] != $dataArry['cdnur'][$i]['company_state'])
			{
				$supply_type = 'INTER';
			}
			else
			{
				$supply_type = 'INTRA';
			}
			// reversecharge
			if ($dataArry['cdnur'][$i]['supply_type'] == 'reversecharge')
			{
				$reverse_charge = 'Y';
			}
			else
			{
				$reverse_charge = 'N';
			}
			// ur_type
			if ($dataArry['cdnur'][$i]['invoice_total'] > '250000')
			{
				$ur_type = 'B2CL';
			}
			elseif ($dataArry['cdnur'][$i]['import_supply_meant'] == 'withpayment')
			{
				$ur_type = 'EXPWP';
			}
			elseif ($dataArry['cdnur'][$i]['import_supply_meant'] == 'withoutpayment')
			{
				$ur_type = 'EXPWOP';
			}
			elseif ($dataArry['cdnur'][$i]['import_supply_meant'] == '')
			{
				$ur_type = '';
			}
			// document_type
			if ($dataArry['cdnur'][$i]['invoice_type'] == 'creditnote')
			{
				$document_type = 'C';
			}
			elseif ($dataArry['cdnur'][$i]['invoice_type'] == 'debitnote')
			{
				$document_type = 'D';
			}
			elseif ($dataArry['cdnur'][$i]['invoice_type'] == 'receiptvoucherinvoice')
			{
				$document_type = 'R';
			}
			elseif ($dataArry['cdnur'][$i]['invoice_type'] != '')
			{
				$document_type = $dataArry['cdnur'][$i]['invoice_type'];
			}
			elseif ($dataArry['cdnur'][$i]['invoice_type'] == '')
			{
				$document_type = '';
			}
			$arrayCDNUR[] = array(
				'invoice_nature' => 'cdnur',
				'invoice_type' => 'b2bur',
				'recipient_gstin' => $dataArry['cdnur'][$i]['supplier_billing_gstin_number'],
				'invoice_number' => $dataArry['cdnur'][$i]['reference_number'],
				'invoice_date' => $dataArry['cdnur'][$i]['invoice_date'],
				'invoice_value' => $dataArry['cdnur'][$i]['invoice_total'],
				'place_of_supply' => $dataArry['cdnur'][$i]['supply_place'],
				'supply_type' => $supply_type,
				'reverse_charge' => $reverse_charge,
				'rate' => $dataArry['cdnur'][$i]['consolidate_rate'],
				'taxable_value' => $dataArry['cdnur'][$i]['taxable_total'],
				'cgst_amount' => $dataArry['cdnur'][$i]['cgst'],
				'sgst_amount' => $dataArry['cdnur'][$i]['sgst'],
				'igst_amount' => $dataArry['cdnur'][$i]['igst'],
				'cess_amount' => $dataArry['cdnur'][$i]['cess'],
				'type' => '',
				'original_invoice_number' => '',
				'original_invoice_date' => '',
				'ur_type' => $ur_type,
				'document_type' => $document_type,
				'reason_for_issuing_document' => $dataArry['cdnur'][$i]['reason_issuing_document'],
				'pre_gst' => '',
				'port_code' => '',
				'shipping_bill_number' => $dataArry['cdnur'][$i]['import_bill_number'],
				'shipping_bill_date' => $dataArry['cdnur'][$i]['import_bill_date'],
				'return_period' => date('Y-m', strtotime($dataArry['cdnur'][$i]['invoice_date'])) ,
				'financial_year' => $dataArry['cdnur'][$i]['financial_year'],
				'created_from' => $dataArry['cdnur'][$i]['created_from'],
				'added_by' => $userid,
				'added_date' => date('Y-m-d H:i:s', time()) ,
				'updated_by' => $userid,
				'updated_date' => date('Y-m-d H:i:s', time()) ,
				'deleted_by' => $userid,
				'deleted_date' => date('Y-m-d H:i:s', time())
			);
		}
		$condition['invoice_nature'] = "cdnur";
		$condition['return_period'] = $financialMonth;
		$this->deletData($this->tableNames['gstr2_return_summary'], $condition);
		return $arrayCDNUR;
	}
	// Gstr2 IMPG array Data
	public function addGstr2IMPSData($userid, $financialMonth, $arr_type = true)

	{
		$dataArry['imp_s'] = $this->getGstr2IMPSQuery($userid, $financialMonth, $arr_type = true);
		$arrayIMPG = array();
		for ($i = 0; $i < count($dataArry['imp_s']); $i++)
		{
			// supply_type
			if ($dataArry['imp_s'][$i]['supply_place'] != $dataArry['imp_s'][$i]['company_state'])
			{
				$supply_type = 'INTER';
			}
			else
			{
				$supply_type = 'INTRA';
			}
			// reversecharge
			if ($dataArry['imp_s'][$i]['supply_type'] == 'reversecharge')
			{
				$reverse_charge = 'Y';
			}
			else
			{
				$reverse_charge = 'N';
			}
			// ur_type
			if ($dataArry['imp_s'][$i]['invoice_total'] > '250000' && $dataArry['imp_s'][$i]['supplier_billing_gstin_number'])
			{
				$ur_type = 'B2CL';
			}
			elseif ($dataArry['imp_s'][$i]['import_supply_meant'] == 'withpayment')
			{
				$ur_type = 'EXPWP';
			}
			elseif ($dataArry['imp_s'][$i]['import_supply_meant'] == 'withoutpayment')
			{
				$ur_type = 'EXPWOP';
			}
			elseif ($dataArry['imp_s'][$i]['import_supply_meant'] == '')
			{
				$ur_type = '';
			}
			// document_type
			if ($dataArry['imp_s'][$i]['invoice_type'] == 'creditnote')
			{
				$document_type = 'C';
			}
			elseif ($dataArry['imp_s'][$i]['invoice_type'] == 'debitnote')
			{
				$document_type = 'D';
			}
			elseif ($dataArry['imp_s'][$i]['invoice_type'] == 'receiptvoucherinvoice')
			{
				$document_type = 'R';
			}
			elseif ($dataArry['imp_s'][$i]['invoice_type'] != '')
			{
				$document_type = $dataArry['imp_s'][$i]['invoice_type'];
			}
			elseif ($dataArry['imp_s'][$i]['invoice_type'] == '')
			{
				$document_type = '';
			}
			$arrayIMPG[] = array(
				'invoice_nature' => 'imp_s',
				'invoice_type' => '',
				'recipient_gstin' => $dataArry['imp_s'][$i]['supplier_billing_gstin_number'],
				'invoice_number' => $dataArry['imp_s'][$i]['reference_number'],
				'invoice_date' => $dataArry['imp_s'][$i]['invoice_date'],
				'invoice_value' => $dataArry['imp_s'][$i]['invoice_total'],
				'place_of_supply' => $dataArry['imp_s'][$i]['supply_place'],
				'supply_type' => $supply_type,
				'reverse_charge' => $reverse_charge,
				'rate' => $dataArry['imp_s'][$i]['consolidate_rate'],
				'taxable_value' => $dataArry['imp_s'][$i]['taxable_total'],
				'igst_amount' => $dataArry['imp_s'][$i]['igst'],
				'cess_amount' => $dataArry['imp_s'][$i]['cess'],
				'cgst_amount' => $dataArry['imp_s'][$i]['cgst'],
				'sgst_amount' => $dataArry['imp_s'][$i]['sgst'],
				'type' => '',
				'original_invoice_number' => '',
				'original_invoice_date' => '',
				'ur_type' => $ur_type,
				'document_type' => $document_type,
				'reason_for_issuing_document' => $dataArry['imp_s'][$i]['reason_issuing_document'],
				'pre_gst' => '',
				'port_code' => '',
				'shipping_bill_number' => $dataArry['imp_s'][$i]['import_bill_number'],
				'shipping_bill_date' => $dataArry['imp_s'][$i]['import_bill_date'],
				'return_period' => date('Y-m', strtotime($dataArry['imp_s'][$i]['invoice_date'])) ,
				'financial_year' => $dataArry['imp_s'][$i]['financial_year'],
				'created_from' => $dataArry['imp_s'][$i]['created_from'],
				'added_by' => $userid,
				'added_date' => date('Y-m-d H:i:s', time()) ,
				'updated_by' => $userid,
				'updated_date' => date('Y-m-d H:i:s', time()) ,
				'deleted_by' => $userid,
				'deleted_date' => date('Y-m-d H:i:s', time())
			);
		}
		$condition['invoice_nature'] = "imp_s";
		$condition['return_period'] = $financialMonth;
		$this->deletData($this->tableNames['gstr2_return_summary'], $condition);
		return $arrayIMPG;
	}
	// Gstr2 IMPS array Data
	public function addGstr2IMPGData($userid, $financialMonth, $arr_type = true)

	{
		$dataArry['imp_g'] = $this->getGstr2IMPGQuery($userid, $financialMonth, $arr_type = true);
		$arrayIMPG = array();
		for ($i = 0; $i < count($dataArry['imp_g']); $i++)
		{
			// supply_type
			if ($dataArry['imp_g'][$i]['supply_place'] != $dataArry['imp_g'][$i]['company_state'])
			{
				$supply_type = 'INTER';
			}
			else
			{
				$supply_type = 'INTRA';
			}
			// reversecharge
			if ($dataArry['imp_g'][$i]['supply_type'] == 'reversecharge')
			{
				$reverse_charge = 'Y';
			}
			else
			{
				$reverse_charge = 'N';
			}
			// ur_type
			if ($dataArry['imp_g'][$i]['invoice_total'] > '250000' && $dataArry['imp_g'][$i]['supplier_billing_gstin_number'])
			{
				$ur_type = 'B2CL';
			}
			elseif ($dataArry['imp_g'][$i]['import_supply_meant'] == 'withpayment')
			{
				$ur_type = 'EXPWP';
			}
			elseif ($dataArry['imp_g'][$i]['import_supply_meant'] == 'withoutpayment')
			{
				$ur_type = 'EXPWOP';
			}
			elseif ($dataArry['imp_g'][$i]['import_supply_meant'] == '')
			{
				$ur_type = '';
			}
			// document_type
			if ($dataArry['imp_g'][$i]['invoice_type'] == 'creditnote')
			{
				$document_type = 'C';
			}
			elseif ($dataArry['imp_g'][$i]['invoice_type'] == 'debitnote')
			{
				$document_type = 'D';
			}
			elseif ($dataArry['imp_g'][$i]['invoice_type'] == 'receiptvoucherinvoice')
			{
				$document_type = 'R';
			}
			elseif ($dataArry['imp_g'][$i]['invoice_type'] != '')
			{
				$document_type = $dataArry['imp_g'][$i]['invoice_type'];
			}
			elseif ($dataArry['imp_g'][$i]['invoice_type'] == '')
			{
				$document_type = '';
			}
			$arrayIMPG[] = array(
				'invoice_nature' => 'imp_g',
				'invoice_type' => $dataArry['imp_g'][$i]['invoice_type'],
				'recipient_gstin' => $dataArry['imp_g'][$i]['supplier_billing_gstin_number'],
				'invoice_number' => $dataArry['imp_g'][$i]['reference_number'],
				'invoice_date' => $dataArry['imp_g'][$i]['invoice_date'],
				'invoice_value' => $dataArry['imp_g'][$i]['invoice_total'],
				'place_of_supply' => $dataArry['imp_g'][$i]['supply_place'],
				'supply_type' => $supply_type,
				'reverse_charge' => $reverse_charge,
				'rate' => $dataArry['imp_g'][$i]['consolidate_rate'],
				'taxable_value' => $dataArry['imp_g'][$i]['taxable_total'],
				'igst_amount' => $dataArry['imp_g'][$i]['igst'],
				'cess_amount' => $dataArry['imp_g'][$i]['cess'],
				'cgst_amount' => $dataArry['imp_g'][$i]['cgst'],
				'sgst_amount' => $dataArry['imp_g'][$i]['sgst'],
				'type' => '',
				'original_invoice_number' => '',
				'original_invoice_date' => '',
				'ur_type' => $ur_type,
				'document_type' => $document_type,
				'reason_for_issuing_document' => $dataArry['imp_g'][$i]['reason_issuing_document'],
				'pre_gst' => '',
				'port_code' => '',
				'shipping_bill_number' => $dataArry['imp_g'][$i]['import_bill_number'],
				'shipping_bill_date' => $dataArry['imp_g'][$i]['import_bill_date'],
				'return_period' => date('Y-m', strtotime($dataArry['imp_g'][$i]['invoice_date'])) ,
				'financial_year' => $dataArry['imp_g'][$i]['financial_year'],
				'created_from' => $dataArry['imp_g'][$i]['created_from'],
				'added_by' => $userid,
				'added_date' => date('Y-m-d H:i:s', time()) ,
				'updated_by' => $userid,
				'updated_date' => date('Y-m-d H:i:s', time()) ,
				'deleted_by' => $userid,
				'deleted_date' => date('Y-m-d H:i:s', time())
			);
		}
		$condition['invoice_nature'] = "imp_g";
		$condition['return_period'] = $financialMonth;
		$this->deletData($this->tableNames['gstr2_return_summary'], $condition);
		return $arrayIMPG;
	}
	// get Gstr2 B2B Query
	public function getGstr2B2BQuery($userid, $financialMonth, $type = '', $ids = '', $group_by = '', $order_by = '', $where = '', $arr_type = true)

	{
		$client_purchase_invoice = $this->tableNames['client_purchase_invoice'];
		$client_purchase_invoice_item = $this->tableNames['client_purchase_invoice_item'];
		$master_state = $this->tableNames['state'];
		$data = array();
		$query = 'select 
        inv.purchase_invoice_id, 
        inv.supplier_billing_name, 
        inv.is_gstr2_uploaded, 
        inv.company_gstin_number, 
        sum(it.igst_amount) as igst, 
        sum(it.sgst_amount) as sgst, 
        sum(it.cgst_amount) as cgst, 
        sum(it.cess_amount) as cess, 
        inv.supplier_billing_state, 
        inv.supply_place,  
        inv.supplier_billing_gstin_number, 
        inv.reference_number, 
        inv.invoice_date, 
        inv.invoice_total_value as invoice_total, 
        inv.company_state, 
        inv.supply_type, 
        inv.invoice_type, 
        inv.financial_year, 
        inv.import_supply_meant, 
        inv.import_bill_number, 
        inv.import_bill_date, 
        inv.created_from, 
        ms.state_tin as place_of_supply, 
        it.consolidate_rate, 
        inv.reason_issuing_document, 
        sum(it.taxable_subtotal) as taxable_total  
        from ' . $client_purchase_invoice . ' inv  
        inner join ' . $client_purchase_invoice_item . ' it on inv.purchase_invoice_id=it.purchase_invoice_id                             
        inner join ' . $master_state . ' ms on ms.state_id=inv.supply_place 
        where inv.added_by="' . $userid . '"  
                and inv.invoice_date like "' . $financialMonth . '%"  
        and inv.is_deleted="0"  
        and inv.is_canceled="0"  
        and inv.status="1"  
        and inv.invoice_nature="purchaseinvoice"  
        and (inv.invoice_type="taxinvoice" or 
                    inv.invoice_type="deemedimportinvoice" or 
                    inv.invoice_type="sezunitinvoice")  
        and inv.supplier_billing_gstin_number!="" ';
		if ($where != '')
		{
			$query.= " and " . $where;
		}
		if ($group_by == '')
		{
			$query.= " group by inv.reference_number, it.consolidate_rate ";
		}
		else
		{
			$query.= " group by " . $group_by . " ";
		}
		if ($order_by == '')
		{
			$query.= 'order by inv.supplier_billing_gstin_number';
		}
		else
		{
			$query.= "order by  " . $order_by . " ";
		}
		//        echo $query."<br />";
		return $this->get_results($query, $arr_type = false);
	}
	public function getGstr2B2BPayload($userid, $financialMonth)

	{
		$dataArr = array();
		$data = $this->getGstr2B2BQuery($userid, $financialMonth);
		$count = 1;
		$igst = array();
		$x = $y = $z = 0;
		if (count($data) > 0)
		{
			$temp_inv = '';
			$temp_ctin = '';
			foreach($data as $gstr2)
			{
				if ($temp_inv != '' and $temp_inv != $gstr2['reference_number'])
				{
					$z = 0;
					$y++;
				}
				if ($temp_ctin != '' and $temp_ctin != $gstr2['supplier_billing_gstin_number'])
				{
					$z = 0;
					$y = 0;
					$x++;
				}
				$dataArr[$x]['ctin'] = $gstr2['supplier_billing_gstin_number'];
				$dataArr[$x]['inv'][$y]['inum'] = $gstr2['reference_number'];
				$dataArr[$x]['inv'][$y]['idt'] = $gstr2['invoice_date'];
				$dataArr[$x]['inv'][$y]['val'] = (float)$gstr2['invoice_total_value'];
				$dataArr[$x]['inv'][$y]['pos'] = $gstr2['place_of_supply'];
				$dataArr[$x]['inv'][$y]['rchrg'] = ($gstr2['supply_type'] == 'reversecharge') ? 'Y' : 'N';
				if ($gstr2['invoice_type'] == 'taxinvoice')
				{
					$dataArr[$x]['inv'][$y]['inv_typ'] = 'R';
				}
				else if ($gstr2['invoice_type'] == 'deemedimportinvoice')
				{
					$dataArr[$x]['inv'][$y]['inv_typ'] = 'DE';
				}
				else if ($gstr2['invoice_type'] == 'sezunitinvoice')
				{
					if ($gstr2['import_supply_meant'] == 'withpayment')
					{
						$dataArr[$x]['inv'][$y]['inv_typ'] = 'SEWP';
					}
					else
					{
						$dataArr[$x]['inv'][$y]['inv_typ'] = 'SEWOP';
					}
				}
				$dataArr[$x]['inv'][$y]['item'][$z]['num'] = (int)$count + $z;
				$dataArr[$x]['inv'][$y]['item'][$z]['itm_det']['rt'] = (float)$gstr2['consolidate_rate'];
				$dataArr[$x]['inv'][$y]['item'][$z]['itm_det']['txval'] = (float)$gstr2['taxable_subtotal'];
				if ($gstr2['supplier_billing_state'] != $gstr2['supply_place'])
				{
					$dataArr[$x]['inv'][$y]['item'][$z]['itm_det']['iamt'] = (float)$gstr2['igst'];
				}
				else
				{
					$dataArr[$x]['inv'][$y]['item'][$z]['itm_det']['camt'] = (float)$gstr2['cgst'];
					$dataArr[$x]['inv'][$y]['item'][$z]['itm_det']['samt'] = (float)$gstr2['sgst'];
				}
				$dataArr[$x]['inv'][$y]['item'][$z]['itm_det']['csamt'] = (float)$gstr2['sgst'];
				$temp_ctin = $gstr2['supplier_billing_gstin_number'];
				$temp_inv = $gstr2['reference_number'];
				$z++;
			}
		}
		return $dataArr;
	}
	// get Gstr2 B2BUR Query
	public function getGstr2B2BURQuery($userid, $financialMonth, $type = '', $ids = '', $group_by = '', $order_by = '', $where = '', $arr_type = true)

	{
		$client_purchase_invoice = $this->tableNames['client_purchase_invoice'];
		$client_purchase_invoice_item = $this->tableNames['client_purchase_invoice_item'];
		$master_state = $this->tableNames['state'];
		$query = 'select  
        inv.reference_number, 
        inv.purchase_invoice_id, 
        inv.supplier_billing_name , 
        inv.supply_type, 
        inv.invoice_type, 
        inv.is_gstr2_uploaded , 
        inv.invoice_date, 
        sum(inv.invoice_total_value) as invoice_total , 
        inv.supply_place, 
        inv.company_state, 
        inv.supply_place, 
        inv.import_supply_meant, 
        inv.reason_issuing_document, 
        inv.import_bill_number, 
        inv.import_bill_date, 
        inv.invoice_date, 
        inv.created_from, 
        inv.supplier_billing_gstin_number, 
        inv.financial_year, 
        it.consolidate_rate, 
        sum(it.taxable_subtotal) as taxable_total , 
        sum(it.cgst_amount) as cgst, 
        sum(it.sgst_amount) as sgst, 
        sum(it.igst_amount) as igst, 
        sum(it.cess_amount) as cess  
        from ' . $client_purchase_invoice . ' inv  
        inner join  ' . $client_purchase_invoice_item . ' it on inv.purchase_invoice_id=it.purchase_invoice_id  
        where inv.added_by="' . $userid . '" 
        and inv.invoice_date like "' . $financialMonth . '%"  
        and inv.is_deleted="0" 
        and inv.is_canceled="0" 
        and inv.status="1" 
        and inv.invoice_nature="purchaseinvoice" 
        and (inv.invoice_type="taxinvoice" or 
              inv.invoice_type="deemedimportinvoice" or 
               inv.invoice_type="sezunitinvoice") 
        and inv.supplier_billing_gstin_number="" ';
		if ($where != '')
		{
			$query.= " and " . $where;
		}
		if ($group_by == '')
		{
			$query.= " group by inv.reference_number,it.consolidate_rate ";
		}
		else
		{
			$query.= " group by " . $group_by . " ";
		}
		$query.= 'order by inv.supplier_billing_gstin_number';
		return $this->get_results($query, $arr_type = false);
	}
	// get Gstr2 B2BUR Payload
	public function getGstr2B2BURPayload($userid, $financialMonth)

	{
		$data = $this->getGstr2B2BURQuery($userid, $financialMonth);
		$dataArr = array();
		$x = $y = $z = 0;
		$sply_ty = '';
		$count = 1;
		if (count($data) > 0)
		{
			$temp_inv = '';
			$temp_ctin = '';
			foreach($data as $b2bur)
			{
				if ($temp_inv != '' and $temp_inv != $b2bur['reference_number'])
				{
					$z = 0;
					$y++;
				}
				if ($temp_ctin != '' and $temp_ctin != $b2bur['supplier_billing_gstin_number'])
				{
					$z = 0;
					$y = 0;
					$x++;
				}
				$dataArr[$x]['inv'][$y]['chksum'] = '';
				$dataArr[$x]['inv'][$y]['inum'] = $b2bur['reference_number'];
				$dataArr[$x]['inv'][$y]['idt'] = $b2bur['invoice_date'];
				$dataArr[$x]['inv'][$y]['val'] = $b2bur['invoice_total'];
				$dataArr[$x]['inv'][$y]['pos'] = (float)$b2bur['supply_place'];
				if ($b2bur['company_state'] != '' and $b2bur['supply_place'] != '')
				{
					if ($b2bur['company_state'] === $b2bur['supply_place'])
					{
						$sply_ty = 'INTER';
					}
					else
					{
						$sply_ty = 'INTRA';
					}
				}
				$dataArr[$x]['inv'][$y]['sply_ty'] = $sply_ty;
				$dataArr[$x]['inv'][$y]['itms'][$z]['num'] = (float)$count + $z;
				$dataArr[$x]['inv'][$y]['itms'][$z]['itm_det']['rt'] = (float)$b2bur['consolidate_rate'];
				$dataArr[$x]['inv'][$y]['itms'][$z]['itm_det']['txval'] = (float)$b2bur['taxable_total'];
				$dataArr[$x]['inv'][$y]['itms'][$z]['itm_det']['camt'] = (float)$b2bur['cgst'];
				$dataArr[$x]['inv'][$y]['itms'][$z]['itm_det']['samt'] = (float)$b2bur['sgst'];
				$dataArr[$x]['inv'][$y]['itms'][$z]['itm_det']['iamt'] = (float)$b2bur['igst'];
				$temp_ctin = $b2bur['supplier_billing_gstin_number'];
				$temp_inv = $b2bur['reference_number'];
				$z++;
			}
		}
		return $dataArr;
	}
	// get Gstr2 cdn query
	public function getGstr2CDNQuery($userid, $financialMonth, $type = '', $ids = '', $group_by = '', $order_by = '', $where = '', $arr_type = true)

	{
		$client_purchase_invoice = $this->tableNames['client_purchase_invoice'];
		$client_purchase_invoice_item = $this->tableNames['client_purchase_invoice_item'];
		$query = 'select  
        inv.purchase_invoice_id, 
        inv.invoice_date, 
        inv.reference_number, 
        inv.invoice_type, 
        inv.import_supply_meant, 
        inv.supplier_billing_name, 
        inv.supplier_billing_gstin_number, 
        sum(it.taxable_subtotal) as taxable_total, 
        inv.invoice_total_value as invoice_total,  
        sum(it.igst_amount) as igst, 
        sum(it.cgst_amount) as cgst, 
        sum(it.sgst_amount) as sgst, 
        sum(it.cess_amount) as cess, 
        inv.is_gstr2_uploaded, 
        inv.company_gstin_number, 
        inv.company_state, 
        inv.invoice_type, 
        inv.supply_type, 
        inv.supply_place, 
        inv.financial_year, 
        inv.import_bill_date, 
        inv.import_bill_number, 
        inv.created_from, 
        inv.reason_issuing_document, 
        inv.corresponding_document_number,     
        inv.corresponding_document_date,     
        it.consolidate_rate  
        from ' . $client_purchase_invoice . ' inv  
        inner join  ' . $client_purchase_invoice_item . ' it on inv.purchase_invoice_id=it.purchase_invoice_id  
        where inv.added_by="' . $userid . '"  
        and inv.invoice_date like "' . $financialMonth . '%"  
        and inv.is_deleted="0"  
        and inv.is_canceled="0"  
        and inv.status="1"  
        and inv.invoice_nature="purchaseinvoice"  
        and inv.supplier_billing_gstin_number!=""';
		if ($where != '')
		{
			$query.= " and inv.invoice_type=" . $where . "";
		}
		else
		{
			$query.= "and ( 
             inv.invoice_type='debitnote' or 
             inv.invoice_type='creditnote' or  
             inv.invoice_type='refundvoucherinvoice')";
		}
		if ($group_by == '')
		{
			$query.= " group by inv.reference_number,it.consolidate_rate ";
		}
		else
		{
			$query.= " group by " . $group_by . " ";
		}
		if ($order_by == '')
		{
			$query.= " order by inv.supplier_billing_gstin_number";
		}
		else
		{
			$query.= 'order by ' . $order_by . '';
		}
		return $this->get_results($query, $arr_type = false);
	}
	// get Gstr2 cdn Payload
	public function getGstr2CDNPayload($userid, $financialMonth)

	{
		$data = $this->getGstr2CDNQuery($userid, $financialMonth);
		$dataArr = array();
		$x = $y = $z = 0;
		$sply_ty = $temp_ctin = $temp_inv = '';
		$count = 1;
		if (count($data) > 0)
		{
			foreach($data as $GstrCDN)
			{
				if ($temp_inv != '' and $temp_inv != $GstrCDN['reference_number'])
				{
					$z = 0;
					$y++;
				}
				if ($temp_ctin != '' and $temp_ctin != $GstrCDN['supplier_billing_gstin_number'])
				{
					$z = 0;
					$y = 0;
					$x++;
				}
				$dataArr[$x]['ctin'] = $GstrCDN['company_gstin_number'];
				if ($GstrCDN['invoice_type'] == 'debitnote')
				{
					$ntty = 'D';
				}
				elseif ($GstrCDN['invoice_type'] == 'creditnote')
				{
					$ntty = 'C';
				}
				elseif ($GstrCDN['invoice_type'] == 'refundvoucherinvoice')
				{
					$ntty = 'R';
				}
				$dataArr[$x]['nt'][$y]['ntty'] = $ntty;
				$dataArr[$x]['nt'][$y]['nt_num'] = $GstrCDN['reference_number'];
				$dataArr[$x]['nt'][$y]['nt_dt'] = date('d-m-Y', strtotime($GstrCDN['invoice_date']));
				$dataArr[$x]['nt'][$y]['rsn'] = 'Y';
				$dataArr[$x]['nt'][$y]['p_gst'] = $GstrCDN['reason_issuing_document'];
				$dataArr[$x]['nt'][$y]['inum'] = $GstrCDN['corresponding_document_number'];
				$dataArr[$x]['nt'][$y]['idt'] = date('d-m-Y', strtotime($GstrCDN['corresponding_document_date']));
				$dataArr[$x]['nt'][$y]['val'] = $GstrCDN['invoice_total'];
				$dataArr[$x]['nt'][$y]['itms'][$z]['num'] = $count + $z;
				$dataArr[$x]['nt'][$y]['itms'][$z]['itm_det'] = (float)$GstrCDN['consolidate_rate'];
				$dataArr[$x]['nt'][$y]['itms'][$z]['txval'] = (float)$GstrCDN['taxable_total'];
				$dataArr[$x]['nt'][$y]['itms'][$z]['iamt'] = (float)$GstrCDN['igst'];
				$dataArr[$x]['nt'][$y]['itms'][$z]['camt'] = (float)$GstrCDN['cgst'];
				$dataArr[$x]['nt'][$y]['itms'][$z]['samt'] = (float)$GstrCDN['sgst'];
				$dataArr[$x]['nt'][$y]['itms'][$z]['csamt'] = (float)$GstrCDN['cess'];
				$temp_ctin = $GstrCDN['supplier_billing_gstin_number'];
				$temp_inv = $GstrCDN['reference_number'];
				$z++;
			}
		}
		return $dataArr;
	}
	// get Gstr2 gstr2 cdn query
	public function getGstr2CDNURQuery($userid, $financialMonth, $type = '', $ids = '', $group_by = '', $order_by = '', $where = '', $arr_type = true)

	{
		$client_purchase_invoice = $this->tableNames['client_purchase_invoice'];
		$client_purchase_invoice_item = $this->tableNames['client_purchase_invoice_item'];
		$query = 'select  
        inv.company_gstin_number, 
        inv.invoice_type, 
        inv.purchase_invoice_id , 
        inv.reference_number, 
        inv.supplier_billing_name, 
        inv.invoice_date, 
        inv.is_gstr2_uploaded , 
        inv.supply_type, 
        inv.company_state, 
        inv.reason_issuing_document, 
        inv.corresponding_document_number,     
        inv.corresponding_document_date,     
        inv.invoice_total_value as invoice_total,     
        inv.import_supply_meant, 
        inv.import_bill_number, 
        inv.import_bill_date, 
        inv.financial_year, 
        inv.supply_place , 
        inv.created_from, 
        inv.supplier_billing_gstin_number, 
        it.consolidate_rate, 
        sum(it.taxable_subtotal) as taxable_total , 
        sum(it.igst_amount) as igst, 
        sum(it.cgst_amount) as cgst, 
        sum(it.sgst_amount) as sgst, 
        sum(it.cess_amount) as cess 
        from ' . $client_purchase_invoice . ' inv  
        inner join  ' . $client_purchase_invoice_item . ' it on inv.purchase_invoice_id=it.purchase_invoice_id  
        where inv.added_by="' . $userid . '"  
        and inv.invoice_date like "' . $financialMonth . '%"  
        and inv.is_deleted="0"  
        and inv.is_canceled="0"  
        and inv.status="1"  
        and inv.invoice_nature="purchaseinvoice"  
        and (inv.invoice_type="debitnote" or 
             inv.invoice_type="creditnote" or  
             inv.invoice_type="refundvoucherinvoice")  
        and inv.supplier_billing_gstin_number=""';
		if ($where != '')
		{
			$query.= " and " . $where;
		}
		if ($group_by == '')
		{
			$query.= " group by inv.reference_number,it.consolidate_rate ";
		}
		else
		{
			$query.= " group by " . $group_by . " ";
		}
		$query.= 'order by inv.supplier_billing_gstin_number';
		// echo $query;
		return $this->get_results($query, $arr_type = false);
	}
	// get gstr2 cdn payload
	public function getGstr2CDNURPayload($userid, $financialMonth)

	{
		$data = $this->getGstr2CDNURQuery($userid, $financialMonth);
		$dataArr = array();
		$x = $y = $z = 0;
		$sply_ty = $temp_ctin = $temp_inv = '';
		$count = 1;
		if (count($data) > 0)
		{
			foreach($data as $GstrCDN)
			{
				if ($temp_inv != '' and $temp_inv != $GstrCDN['reference_number'])
				{
					$z = 0;
					$y++;
				}
				if ($temp_ctin != '' and $temp_ctin != $GstrCDN['supplier_billing_gstin_number'])
				{
					$z = 0;
					$y = 0;
					$x++;
				}
				$dataArr[$x]['ctin'] = $GstrCDN['company_gstin_number'];
				if ($GstrCDN['invoice_type'] == 'debitnote')
				{
					$ntty = 'D';
				}
				elseif ($GstrCDN['invoice_type'] == 'creditnote')
				{
					$ntty = 'C';
				}
				elseif ($GstrCDN['invoice_type'] == 'refundvoucherinvoice')
				{
					$ntty = 'R';
				}
				$dataArr[$x]['nt'][$y]['ntty'] = $ntty;
				$dataArr[$x]['nt'][$y]['nt_num'] = $GstrCDN['reference_number'];
				$dataArr[$x]['nt'][$y]['nt_dt'] = date('d-m-Y', strtotime($GstrCDN['invoice_date']));
				$dataArr[$x]['nt'][$y]['rsn'] = 'Y';
				$dataArr[$x]['nt'][$y]['p_gst'] = $GstrCDN['reason_issuing_document'];
				$dataArr[$x]['nt'][$y]['inum'] = $GstrCDN['corresponding_document_number'];
				$dataArr[$x]['nt'][$y]['idt'] = date('d-m-Y', strtotime($GstrCDN['corresponding_document_date']));
				$dataArr[$x]['nt'][$y]['val'] = $GstrCDN['invoice_total'];
				$dataArr[$x]['nt'][$y]['itms'][$z]['num'] = $count + $z;
				$dataArr[$x]['nt'][$y]['itms'][$z]['itm_det'] = (float)$GstrCDN['consolidate_rate'];
				$dataArr[$x]['nt'][$y]['itms'][$z]['txval'] = (float)$GstrCDN['taxable_total'];
				$dataArr[$x]['nt'][$y]['itms'][$z]['iamt'] = (float)$GstrCDN['igst'];
				$dataArr[$x]['nt'][$y]['itms'][$z]['camt'] = (float)$GstrCDN['cgst'];
				$dataArr[$x]['nt'][$y]['itms'][$z]['samt'] = (float)$GstrCDN['sgst'];
				$dataArr[$x]['nt'][$y]['itms'][$z]['csamt'] = (float)$GstrCDN['cess'];
				$temp_ctin = $GstrCDN['supplier_billing_gstin_number'];
				$temp_inv = $GstrCDN['reference_number'];
				$z++;
			}
		}
		return $dataArr;
	}
	// get gstr2 IMPG query
	public function getGstr2IMPGQuery($userid, $financialMonth, $type = '', $ids = '', $group_by = '', $order_by = '', $where = '', $arr_type = true)

	{
		$client_purchase_invoice = $this->tableNames['client_purchase_invoice'];
		$client_purchase_invoice_item = $this->tableNames['client_purchase_invoice_item'];
		$gst_master_item = $this->tableNames['item'];
		$client_master_item = $this->tableNames['client_master_item'];
		$query = 'select 
        inv.purchase_invoice_id, 
        inv.supplier_billing_name , 
        inv.supply_type, 
        inv.is_gstr2_uploaded , 
        inv.invoice_type, 
        inv.reference_number, 
        inv.company_gstin_number, 
        inv.import_bill_number,  
        inv.supply_type, 
        inv.import_bill_date,  
        inv.company_state, 
        inv.created_from, 
        inv.import_supply_meant, 
        inv.invoice_date, 
        inv.supply_place , 
        inv.reason_issuing_document, 
        inv.financial_year , 
        inv.invoice_total_value as invoice_total,  
        inv.import_bill_port_code,  
        inv.supplier_billing_gstin_number,  
        sum(it.taxable_subtotal) as taxable_total,  
        it.consolidate_rate,  
        sum(it.igst_amount) as igst, 
        sum(it.cess_amount) as cess, 
        sum(it.cgst_amount) as cgst, 
        sum(it.sgst_amount) as sgst, 
        ms.item_type  
        from ' . $client_purchase_invoice . ' inv  
        inner join ' . $client_purchase_invoice_item . ' it on inv.purchase_invoice_id=it.purchase_invoice_id  
        inner join ' . $client_master_item . ' cms on it.item_id=cms.item_id  
        inner join ' . $gst_master_item . ' ms on cms.item_category=ms.item_id  
        where inv.added_by="194"  
        and inv.invoice_date like "2017-09%"  
        and inv.is_deleted="0" 
        and ms.item_type="1"  
        and inv.is_canceled="0"  
        and inv.status="1"  
        and inv.invoice_nature="purchaseinvoice"  
        and inv.supplier_billing_gstin_number!="" ';
		if ($where != '')
		{
			$query.= " and " . $where;
		}
		if ($type == '')
		{
			$query.= 'and ( 
             inv.invoice_type="sezunitinvoice" or 
             inv.invoice_type="importinvoice" or  
             inv.invoice_type="deemedimportinvoice") ';
		}
		else
		{
			$query.= 'and ' . $type . '';
		}
		if ($group_by == '')
		{
			$query.= " group by inv.reference_number,it.consolidate_rate ";
		}
		else
		{
			$query.= " group by " . $group_by . " ";
		}
		if ($order_by == '')
		{
			$query.= 'order by inv.reference_number';
		}
		else
		{
			$query.= " order by " . $order_by . " ";
		}
		// echo $query;
		return $this->get_results($query, $arr_type = false);
	}
	// get gstr2 IMPG Payload
	public function getGstr2IMPGPayload($userid, $financialMonth)

	{
		$data = $this->getGstr2IMPGQuery($userid, $financialMonth);
		$dataArr = array();
		$x = $y = $z = 0;
		$sply_ty = $temp_ctin = $temp_inv = '';
		$count = 1;
		if (count($data) > 0)
		{
			foreach($data as $GstrIMPG)
			{
				if ($temp_inv != '' and $temp_inv != $GstrIMPG['reference_number'])
				{
					$y = 0;
					$x++;
				}
				$dataArr[$x]['is_sez'] = ($GstrIMPG['invoice_type'] == 'sezunitinvoice') ? 'Y' : 'N';
				$dataArr[$x]['stin'] = $GstrIMPG['company_gstin_number'];
				$dataArr[$x]['boe_num'] = $GstrIMPG['import_bill_number'];
				$dataArr[$x]['boe_dt'] = ($GstrIMPG['import_bill_date'] == '0000-00-00') ? '' : date('d-m-Y', strtotime($GstrIMPG['import_bill_date']));
				$dataArr[$x]['boe_val'] = (float)$GstrIMPG['invoice_total'];
				$dataArr[$x]['port_code'] = $GstrIMPG['import_bill_port_code'];
				$dataArr[$x]['itms'][$y]['num'] = $y + $count;
				$dataArr[$x]['itms'][$y]['txval'] = (float)$GstrIMPG['taxable_total'];
				$dataArr[$x]['itms'][$y]['rt'] = (float)$GstrIMPG['consolidate_rate'];
				$dataArr[$x]['itms'][$y]['iamt'] = (float)$GstrIMPG['igst'];
				$dataArr[$x]['itms'][$y]['csamt'] = (float)$GstrIMPG['cess'];
				$temp_ctin = $GstrIMPG['supplier_billing_gstin_number'];
				$temp_inv = $GstrIMPG['reference_number'];
				$y++;
			}
		}
		return $dataArr;
	}
	// get gstr2 IMPS query
	public function getGstr2IMPSQuery($userid, $financialMonth, $type = '', $ids = '', $order_by = '', $group_by = '', $where = '', $arr_type = true)

	{
		$client_purchase_invoice = $this->tableNames['client_purchase_invoice'];
		$client_purchase_invoice_item = $this->tableNames['client_purchase_invoice_item'];
		$gst_master_item = $this->tableNames['item'];
		$client_master_item = $this->tableNames['client_master_item'];
		$query = 'select 
        inv.invoice_date, 
        inv.purchase_invoice_id, 
        inv.reference_number, 
        inv.supplier_billing_name, 
        inv.supplier_billing_gstin_number, 
        sum(it.taxable_subtotal) as taxable_total,  
        inv.invoice_total_value as invoice_total , 
        inv.company_gstin_number, 
        sum(it.igst_amount) as igst, 
        sum(it.cgst_amount) as cgst, 
        sum(it.sgst_amount) as sgst, 
        sum(it.cess_amount) as cess, 
        inv.is_gstr2_uploaded, 
        inv.supply_place, 
        inv.supply_type , 
        it.consolidate_rate,  
        inv.company_state, 
        inv.import_supply_meant, 
        inv.invoice_type , 
        inv.reason_issuing_document , 
        inv.import_bill_date , 
        inv.financial_year , 
        inv.created_from , 
        inv.import_bill_number , 
        ms.item_type  
        from ' . $client_purchase_invoice . ' inv  
        inner join ' . $client_purchase_invoice_item . ' it on inv.purchase_invoice_id=it.purchase_invoice_id  
        inner join ' . $client_master_item . ' cms on it.item_id=cms.item_id  
        inner join ' . $gst_master_item . ' ms on cms.item_category=ms.item_id  
        where inv.added_by="194"  
        and inv.invoice_date like "2017-09%"  
        and inv.is_deleted="0" 
        and ms.item_type="1"  
        and inv.is_canceled="0"  
        and inv.status="1"  
        and inv.invoice_nature="purchaseinvoice"  
        and (inv.invoice_type="sezunitinvoice" or 
			inv.invoice_type="importinvoice" or 
			inv.invoice_type="deemedimportinvoice")  
		and inv.supplier_billing_gstin_number!="" ';
		if ($where != '')
		{
			$query.= " and " . $where;
		}
		if ($group_by == '')
		{
			$query.= " group by inv.reference_number,it.consolidate_rate ";
		}
		else
		{
			$query.= " group by " . $group_by . " ";
		}
		if ($order_by == '')
		{
			$query.= " order by inv.reference_number ";
		}
		else
		{
			$query.= " group by " . $order_by . " ";
		}
		return $this->get_results($query, $arr_type = false);
	}
	
	// get gstr2 IMPS payload
	public function getGstr2IMPSPayload($userid, $financialMonth)
	{
		$data = $this->getGstr2IMPSQuery($userid, $financialMonth);
		$dataArr = array();
		$a = $x = $y = $z = 0;
		$temp_ctin = $temp_inv = '';
		$count = 1;
		if (count($data) > 0)
		{
			foreach($data as $GstrIMPS)
			{
				if ($temp_inv != '' and $temp_inv != $GstrIMPS['reference_number'])
				{
					$y = 0;
					$x++;
				}
				if ($temp_ctin != '' and $temp_ctin != $GstrIMPS['supplier_billing_gstin_number'])
				{
					$z = 0;
					$y = 0;
					$y++;
				}
				$dataArr[$x]['inum'] = $GstrIMPS['reference_number'];
				$dataArr[$x]['idt'] = $GstrIMPS['invoice_date'];
				$dataArr[$x]['ival'] = (float)$GstrIMPS['invoice_total'];
				$dataArr[$x]['pos'] = $GstrIMPS['supply_place'];
				$dataArr[$x]['boe_val'] = (float)$GstrIMPS['invoice_total'];
				$dataArr[$x]['itms'][$y]['num'] = $y + $count;
				$dataArr[$x]['itms'][$y]['itm_det']['txval'] = (float)$GstrIMPS['taxable_total'];
				$dataArr[$x]['itms'][$y]['itm_det']['rt'] = (float)$GstrIMPS['consolidate_rate'];
				$dataArr[$x]['itms'][$y]['itm_det']['iamt'] = (float)$GstrIMPS['igst'];
				$dataArr[$x]['itms'][$y]['itm_det']['camt'] = (float)$GstrIMPS['cess'];
				$temp_ctin = $GstrIMPS['supplier_billing_gstin_number'];
				$temp_inv = $GstrIMPS['reference_number'];
				$y++;
			}
		}
		return $dataArr;
	}
	
	// Supplies from composition taxable person and other exempt/nil rated/non GST supplies (7)
	public function getGstr2NilRatedQuery($userid, $financialMonth, $type = '', $ids = '', $order_by = '', $group_by = '')

	{
		$client_purchase_invoice = $this->tableNames['client_purchase_invoice'];
		$client_purchase_invoice_item = $this->tableNames['client_purchase_invoice_item'];
		$vendor_type = $this->tableNames['vendor_type'];
		$client_master_item = $this->tableNames['client_master_item'];
		$user = $_SESSION['user_detail']['user_id'];
		$query = 'select 
        inv.invoice_date, 
        inv.purchase_invoice_id, 
        inv.reference_number, 
        inv.supplier_billing_name, 
        inv.supplier_billing_gstin_number, 
        sum(it.taxable_subtotal) as taxable_subtotal,  
        inv.invoice_total_value , 
        inv.company_gstin_number, 
        sum(it.igst_amount) as igst, 
        sum(it.cgst_amount) as cgst, 
        sum(it.sgst_amount) as sgst, 
        sum(it.cess_amount) as cess, 
        inv.is_gstr2_uploaded, 
        inv.supply_place, 
        inv.supplier_billing_vendor_type, 
        inv.supply_type , 
        it.consolidate_rate,     
        vt.vendor_name 
        from ' . $client_purchase_invoice . ' inv  
        inner join ' . $client_purchase_invoice_item . ' it on inv.purchase_invoice_id=it.purchase_invoice_id  
        inner join ' . $client_master_item . ' cmi on it.item_id=cmi.item_id  
        inner join ' . $vendor_type . ' vt on inv.supplier_billing_vendor_type=vt.vendor_id  
        where inv.added_by=' . $user . '  
        and inv.invoice_date like "2017-09%"  
        and inv.is_deleted="0" 
        and ( 
				inv.supplier_billing_vendor_type="2" or  
				it.is_applicable="1" or 
				it.is_applicable="2" or 
				(it.consolidate_rate="0")
			) 
        and inv.is_canceled="0"  
        and inv.status="1"  
        and inv.invoice_nature="purchaseinvoice"  
        and (inv.invoice_type="sezunitinvoice" or 
             inv.invoice_type="importinvoice" or 
             inv.invoice_type="deemedimportinvoice")  
        and inv.supplier_billing_gstin_number!="" ';
		if ($group_by == '')
		{
			$query.= " group by inv.reference_number,it.consolidate_rate ";
		}
		else
		{
			$query.= " group by " . $group_by . " ";
		}
		if ($order_by == '')
		{
			$query.= " order by inv.reference_number ";
		}
		else
		{
			$query.= " group by " . $order_by . " ";
		}
		return $this->get_results($query);
	}
	
	// Advance amount paid for reverse charge supplies 10A
	public function getGstr2AdvanceQuery($userid, $financialMonth, $type = '', $ids = '', $order_by = '', $group_by = '')
	{
		// $financialMonth=date('Y-m',time());
		$client_purchase_invoice = $this->tableNames['client_purchase_invoice'];
		$client_purchase_invoice_item = $this->tableNames['client_purchase_invoice_item'];
		$user = $_SESSION['user_detail']['user_id'];
		$query = 'select 
        inv.invoice_date, 
        inv2.purchase_invoice_id, 
        inv.invoice_type, 
        inv.reference_number, 
        inv.supplier_billing_name, 
        inv.supplier_billing_gstin_number, 
        sum(it.taxable_subtotal) as taxable_subtotal,  
        inv.invoice_total_value , 
        inv.company_gstin_number, 
        sum(it.igst_amount) as igst, 
        sum(it.cgst_amount) as cgst, 
        sum(it.sgst_amount) as sgst, 
        sum(it.cess_amount) as cess, 
        inv.is_gstr2_uploaded, 
        inv.supply_place, 
        inv.supplier_billing_vendor_type, 
        inv.supply_type , 
        it.consolidate_rate, 
        inv.advance_adjustment, 
        inv.invoice_date as advancedate     
        from ' . $client_purchase_invoice . ' inv 
        left join ' . $client_purchase_invoice . ' inv2 on inv.purchase_invoice_id=inv2.receipt_voucher_number   
        inner join ' . $client_purchase_invoice_item . ' it on inv.purchase_invoice_id=it.purchase_invoice_id  
        where inv.added_by=' . $user . '  
        and inv.is_deleted="0" 
        and inv.is_canceled="0" 
        and ((DATE_FORMAT(inv.invoice_date, "%Y-%m") < DATE_FORMAT(inv2.invoice_date, "%Y-%m") ||  
        inv2.invoice_date is NULL) && DATE_FORMAT(inv.invoice_date, "%Y-%m") ="' . $financialMonth . '" ) 
        and inv.status="1"  
        and inv.invoice_nature="purchaseinvoice"  
        and (inv.invoice_type="receiptvoucherinvoice")';
		if ($group_by == '')
		{
			$query.= " group by inv.reference_number,it.consolidate_rate ";
		}
		else
		{
			$query.= " group by " . $group_by . " ";
		}
		if ($order_by == '')
		{
			$query.= " order by inv.reference_number,inv2.reference_number ";
		}
		else
		{
			$query.= " group by " . $order_by . "";
		}
		// echo $query;
		return $this->get_results($query);
	}
	
	// Adjustment of advance amount paid earlier for reverse charge supplies
	public function getGstr2AdvanceAdjustQuery($userid, $financialMonth, $type = '', $ids = '', $order_by = '', $group_by = '')
	{
		// $financialMonth=date('Y-m',time());
		$client_purchase_invoice = $this->tableNames['client_purchase_invoice'];
		$client_purchase_invoice_item = $this->tableNames['client_purchase_invoice_item'];
		$user = $_SESSION['user_detail']['user_id'];
		$query = 'select 
        inv.invoice_date, 
        inv.purchase_invoice_id, 
        inv.invoice_type, 
        inv.reference_number, 
        inv.supplier_billing_name, 
        inv.supplier_billing_gstin_number, 
        sum(it.taxable_subtotal) as taxable_subtotal,  
        inv.invoice_total_value , 
        inv.company_gstin_number, 
        inv2.reference_number as receipt_voucher_number, 
        sum(it.igst_amount) as igst, 
        sum(it.cgst_amount) as cgst, 
        sum(it.sgst_amount) as sgst, 
        sum(it.cess_amount) as cess, 
        inv.is_gstr2_uploaded, 
        inv.supply_place, 
        inv.supplier_billing_vendor_type, 
        it.consolidate_rate, 
        inv.advance_adjustment 
        from ' . $client_purchase_invoice . ' inv 
        inner join ' . $client_purchase_invoice . ' inv2 on inv2.purchase_invoice_id=inv.receipt_voucher_number   
        inner join ' . $client_purchase_invoice_item . ' it on inv.purchase_invoice_id=it.purchase_invoice_id  
        where inv.added_by=' . $user . '  
        and inv.is_deleted="0" 
        and inv.is_canceled="0" 
        and inv.status="1"  
        and (DATE_FORMAT(inv.invoice_date, "%Y-%m") > DATE_FORMAT(inv2.invoice_date, "%Y-%m"))  
        and inv.invoice_date like "' . $financialMonth . '%" 
        and inv.invoice_nature="purchaseinvoice"  
        and (inv.advance_adjustment=1 && inv.invoice_type="taxinvoice") 
        and inv.supplier_billing_gstin_number!="" ';
		if ($group_by == '')
		{
			$query.= " group by inv.reference_number,it.consolidate_rate ";
		}
		else
		{
			$query.= " group by " . $group_by . " ";
		}
		if ($order_by == '')
		{
			$query.= " order by inv.reference_number,inv2.reference_number ";
		}
		else
		{
			$query.= " group by " . $order_by . "";
		}
		return $this->get_results($query);
	}
	public function getGSTR2ADownlodedMissingData($userid, $returnMonth, $array_type = true)
	{
		/* Missing Invoices Query */
		$missingQuery = 'Select  
                pi.reference_number, 
                pi.invoice_date, 
                pi.invoice_total_value, 
                sum(pii.taxable_subtotal) as total_taxable_subtotal, 
                pi.supplier_billing_gstin_number as company_gstin_number, 
                sum(pii.cgst_amount) as total_cgst_amount, 
                sum(pii.sgst_amount) as total_sgst_amount, 
                sum(pii.igst_amount) as total_igst_amount, 
                sum(pii.cess_amount) as total_cess_amount, 
                CONCAT(pi.reference_number,pi.supplier_billing_gstin_number) as ref_ctin, 
                ( 
                    CASE 
                        WHEN pi.corresponding_document_number = "0" THEN pi.corresponding_document_number  
                        ELSE (SELECT reference_number FROM ' . $this->tableNames['client_purchase_invoice'] . ' WHERE purchase_invoice_id = pi.corresponding_document_number) 
                    END 
                ) AS nt_num, 
                pi.corresponding_document_date as nt_dt, 
                GROUP_CONCAT(DISTINCT CAST(pii.consolidate_rate AS UNSIGNED) ORDER BY CAST(pii.consolidate_rate AS UNSIGNED) ASC SEPARATOR ",") as rate,
                pi.supply_place as pos, 
                DATE_FORMAT(pi.invoice_date,"%Y-%m") as financial_month  
                from ' . $this->tableNames['client_purchase_invoice'] . ' as pi  
                INNER JOIN ' . $this->tableNames['client_purchase_invoice_item'] . ' as pii  
                ON pi.purchase_invoice_id = pii.purchase_invoice_id  
                where 1=1  
                and pi.added_by = ' . $userid . '  
                and pii.added_by = ' . $_SESSION['user_detail']['user_id'] . '  
                and DATE_FORMAT(pi.invoice_date,"%Y-%m") = "' . $returnMonth . '"  
                and pi.status = "1"  
                and CONCAT(pi.reference_number,pi.supplier_billing_gstin_number) 
                NOT IN(Select CONCAT(reference_number,company_gstin_number)  
                from ' . $this->tableNames['client_reconcile_purchase_invoice1'] . '  
                WHERE status = "1" 
                and DATE_FORMAT(invoice_date,"%Y-%m") = "' . $returnMonth . '"  
                AND added_by = ' . $userid . ')  
                GROUP BY ref_ctin';
		return $this->get_results($missingQuery, $array_type);
	}
	public function getGSTR2ADownlodedAdditionalData($userid, $returnMonth, $array_type = true)

	{
		/* Additional Invoices Query */
		$additionalQuery = 'Select  
                di.reference_number, 
                di.invoice_date, 
                di.invoice_total_value, 
                sum(di.total_taxable_subtotal) as total_taxable_subtotal, 
                di.company_gstin_number, 
                sum(di.total_cgst_amount) as total_cgst_amount, 
                sum(di.total_sgst_amount) as total_sgst_amount, 
                sum(di.total_igst_amount) as total_igst_amount, 
                sum(di.total_cess_amount) as total_cess_amount, 
                di.nt_num, 
                di.nt_dt, 
                GROUP_CONCAT(DISTINCT CAST(di.rate AS UNSIGNED) ORDER BY CAST(di.rate AS UNSIGNED) ASC SEPARATOR ",") as rate, 
                di.pos, 
                di.financial_month  
                from ' . $this->tableNames['client_reconcile_purchase_invoice1'] . ' as di  
                where 1=1 AND di.added_by = ' . $userid . '  
                and DATE_FORMAT(di.invoice_date,"%Y-%m") = "' . $returnMonth . '"  
                and di.status = "1"  
                and CONCAT(di.reference_number,di.company_gstin_number)  
                NOT IN(Select CONCAT(reference_number,supplier_billing_gstin_number) 
                from ' . $this->tableNames['client_purchase_invoice'] . ' WHERE status = "1"  
                and DATE_FORMAT(invoice_date,"%Y-%m") = "' . $returnMonth . '" 
                AND added_by = ' . $userid . ')  
                GROUP BY CONCAT(di.reference_number,di.company_gstin_number)';
		return $this->get_results($additionalQuery, $array_type);
	}
	
	public function getGSTR2ADownlodedMatchMisData($userid, $returnMonth, $array_type = true) {

		$query = 'Select 
				di.id, 
                pi.purchase_invoice_id, 
                di.type, 
                di.invoice_type, 
                pi.invoice_type as pi_invoice_type, 
                di.reference_number, 
                pi.reference_number as pi_reference_number, 
                di.company_gstin_number as ctin, 
                pi.supplier_billing_gstin_number as pi_ctin, 
                CONCAT(di.reference_number,di.company_gstin_number) as ref_ctin, 
                di.invoice_date, 
                pi.invoice_date as pi_invoice_date, 
                di.rate, 
                di.total_cgst_amount as cgst, 
                di.total_sgst_amount as sgst, 
                di.total_igst_amount as igst, 
                di.total_cess_amount as cess, 
                di.total_taxable_subtotal as taxable_total, 
                di.invoice_total_value as invoice_total, 
                pi.invoice_total_value as pi_invoice_total, 
                di.financial_month  
                from ' . $this->tableNames['client_reconcile_purchase_invoice1'] . ' as di  
                INNER JOIN ' . $this->tableNames['client_purchase_invoice'] . ' as pi  
                ON di.reference_number = pi.reference_number and  
                di.company_gstin_number = pi.supplier_billing_gstin_number  
                where 1=1  
                and di.added_by = ' . $userid . '  
                and pi.added_by = ' . $userid . '  
                and DATE_FORMAT(di.invoice_date,"%Y-%m") = "' . $returnMonth . '"  
                and DATE_FORMAT(pi.invoice_date,"%Y-%m") = "' . $returnMonth . '"  
                and di.status = "1"  
                and pi.status = "1"  
                group by ref_ctin  
                order by di.reference_number';
		return $this->get_results($query, $array_type);
	}
	public function getGst2ReconcileFinalQuery($userid, $financialMonth, $where = '')

	{
		$gstr2_reconcile_final = $this->tableNames['gstr2_reconcile_final'];
		$query = 'select * from  
                ' . $gstr2_reconcile_final . ' 
                where added_by=' . $userid . ' 
                and financial_month like "' . $financialMonth . '%"  
                and status="1"';
		if ($where)
		{
			$query.= ' and invoice_status="' . $where . '" ';
		}
		else
		{
			$query.= ' and invoice_status!="" ';
		}
		// echo $query;
		return $this->get_results($query, false);
	}
}
?>