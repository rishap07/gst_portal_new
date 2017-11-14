<?php
/*
	* 
	*  Developed By        :   Ishwar Lal Ghiya
	*  Date Created        :   June 6, 2017
	*  Last Modified By    :   Ishwar Lal Ghiya
	*  Last Modification   :   Client Item Listing
	* 
*/
$obj_notification= new notification();
$obj_common= new common();


	extract($_POST);
	parse_str($_POST['purchaseExportData'], $params);
	
	//Columns to fetch from database
	$client_purchase_invoice = $db_obj->getTableName('client_purchase_invoice');
	$client_purchase_invoice_item = $db_obj->getTableName('client_purchase_invoice_item');
	$master_state = $db_obj->getTableName('state');
	$master_country = $db_obj->getTableName('country');
	$vendor_type = $db_obj->getTableName('vendor_type');

	$uQuery = 'select
			inv.reference_number,
			inv.invoice_date,  
			inv.supply_type,
			inv.company_gstin_number, 
			inv.supply_place,
				(CASE 
				  WHEN inv.advance_adjustment = "0" THEN "No"
				  WHEN inv.advance_adjustment = "1" THEN "Yes"
				END) AS advance_adjustment,
				(CASE 
				  WHEN inv.receipt_voucher_number = "0" THEN ""
				  ELSE inv.receipt_voucher_number
				END) AS receipt_voucher_number,
			inv.supplier_billing_name,
			inv.supplier_billing_company_name,
			inv.supplier_billing_address,
			inv.supplier_billing_state_name,
			sct.country_name as supplier_billing_country,
			svt.vendor_name as supplier_billing_vendor_type,
			inv.recipient_shipping_name,
			inv.recipient_shipping_company_name,
			inv.recipient_shipping_address,
			inv.recipient_shipping_state_name,
			rct.country_name as recipient_shipping_country,
			rvt.vendor_name as recipient_shipping_vendor_type,
			inv.recipient_shipping_gstin_number,
			it.item_name,
			it.item_hsncode,
			it.item_description,
				(CASE 
				  WHEN it.is_applicable = "0" THEN "Applicable"
				  WHEN it.is_applicable = "1" THEN "Non-GST"
				  WHEN it.is_applicable = "2" THEN "Exempted"
				END) AS is_applicable,
			it.item_quantity,
			it.item_unit,
			it.item_unit_price,
			it.subtotal,
			it.discount,
			it.advance_amount,
			it.cgst_amount,
			it.sgst_amount,
			it.igst_amount,
			it.cess_amount,
			it.subtotal,
			it.total,
			it.consolidate_rate,
			inv.description,
			st.state_name
			from '.$client_purchase_invoice.' inv  
			inner JOIN '.$client_purchase_invoice_item.'  it on it.purchase_invoice_id= inv.purchase_invoice_id
			inner JOIN '.$master_state.'  st on st.state_id= inv.supply_place
			inner JOIN '.$master_country.'  sct on (sct.id= inv.supplier_billing_country)
			inner JOIN '.$master_country.'  rct on (rct.id= inv.recipient_shipping_country )
			inner JOIN '.$vendor_type.'  svt on (svt.vendor_id= inv.supplier_billing_vendor_type)
			inner JOIN '.$vendor_type.'  rvt on (rvt.vendor_id= inv.recipient_shipping_vendor_type)';

	$uWhere = $uQuery . ' where 1=1 AND inv.invoice_nature="purchaseinvoice"  AND inv.is_deleted = "0" AND inv.added_by="'.$db_obj->sanitize($_SESSION['user_detail']['user_id']).'"';
	
	if(isset($params['from_date']) && !empty($params['from_date'])) {
		$uWhere .= " AND inv.invoice_date >= '" . $params['from_date'] ."'";
	}
	
	if(isset($params['to_date']) && !empty($params['to_date'])) {
		$uWhere .= " AND inv.invoice_date <= '" . $params['to_date'] ."'";
	}
	
	if(isset($params['invoice_type']) && !empty($params['invoice_type'])) {
		$uWhere .= " AND inv.invoice_type = '" . $params['invoice_type'] ."'";
	}
	
	if(isset($params['supply_type']) && !empty($params['supply_type'])) {
		$uWhere .= " AND inv.supply_type = '" . $params['supply_type'] ."'";
	}
	
	if(isset($params['reference_number']) && !empty($params['reference_number'])) {
		$uWhere .= " AND inv.reference_number LIKE '%" . $params['reference_number'] ."%'";
	}
	
	if(isset($params['place_of_supply']) && !empty($params['place_of_supply'])) {
		$uWhere .= " AND inv.supply_place = " . $params['place_of_supply'];
	}
	
	if(isset($params['supplier_billing_state']) && !empty($params['supplier_billing_state'])) {
		$uWhere .= " AND inv.supplier_billing_state = " . $params['supplier_billing_state'];
	}
	
	if(isset($params['supplier_billing_gstin_number']) && !empty($params['supplier_billing_gstin_number'])) {
		$uWhere .= " AND inv.supplier_billing_gstin_number LIKE '%" . $params['supplier_billing_gstin_number'] ."%'";
	}
	
	$uWhere .= " order by inv.invoice_date ASC";
	
	$purchaseExportArray=$obj_common->replaceSpecialChar($db_obj->get_results($uWhere,false));
	
	$range = range('A','Z');
	$objPHPExcel = new PHPExcel();
	$headerStyle1 = new PHPExcel_Style();
	$dataStyle1 = new PHPExcel_Style();

	$headerStyle1->applyFromArray(
		array(
			'borders' => array(
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'top' 	 => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'right'	 => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'left'	 => array('style' => PHPExcel_Style_Border::BORDER_THIN)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			),
			'font' => array(
				'bold' => true,
				'size' => 11,
				'name' => 'Arial'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'F8CBAD')
			)
		)
	);

	$dataStyle1->applyFromArray(
		array(
			'borders'=>	array(
				'bottom'=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'top' 	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'right'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'left'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
			),
			'font'  => array(
				'bold'  => false,
				'size'  => 11,
				'name'  => 'Calibri'
			)
		)
	);

	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setCellValue('A1','Reference Number');
	$objPHPExcel->getActiveSheet()->setCellValue('B1','Invoice Date');
	$objPHPExcel->getActiveSheet()->setCellValue('C1','Supply Type');
	$objPHPExcel->getActiveSheet()->setCellValue('D1','Place of Supply');
	$objPHPExcel->getActiveSheet()->setCellValue('E1','Advance Adjustment');
	$objPHPExcel->getActiveSheet()->setCellValue('F1','Receipt Voucher Number ');
	$objPHPExcel->getActiveSheet()->setCellValue('G1','Billing Name');
	$objPHPExcel->getActiveSheet()->setCellValue('H1','Billing Business Name');
	$objPHPExcel->getActiveSheet()->setCellValue('I1','Billing Address');
	$objPHPExcel->getActiveSheet()->setCellValue('J1','Billing State');
	$objPHPExcel->getActiveSheet()->setCellValue('K1','Billing Country');
	$objPHPExcel->getActiveSheet()->setCellValue('L1','Billing Vendor Type');
	$objPHPExcel->getActiveSheet()->setCellValue('M1','Billing GSTIN Number');
	$objPHPExcel->getActiveSheet()->setCellValue('N1','Shipping Name');
	$objPHPExcel->getActiveSheet()->setCellValue('O1','Shipping Business Name');
	$objPHPExcel->getActiveSheet()->setCellValue('P1','Shipping Address');
	$objPHPExcel->getActiveSheet()->setCellValue('Q1','Shipping State');
	$objPHPExcel->getActiveSheet()->setCellValue('R1','Shipping Country');
	$objPHPExcel->getActiveSheet()->setCellValue('S1','Shipping Vendor Type');
	$objPHPExcel->getActiveSheet()->setCellValue('T1','Shipping GSTIN Number');
	$objPHPExcel->getActiveSheet()->setCellValue('U1','Description of Goods');
	$objPHPExcel->getActiveSheet()->setCellValue('V1','Item HSN/SAC Code');
	$objPHPExcel->getActiveSheet()->setCellValue('W1','Item Description');
	$objPHPExcel->getActiveSheet()->setCellValue('X1','Item Taxes');
	$objPHPExcel->getActiveSheet()->setCellValue('Y1','Quantity');
	$objPHPExcel->getActiveSheet()->setCellValue('Z1','Unit');
	$objPHPExcel->getActiveSheet()->setCellValue('AA1','Rate');
	$objPHPExcel->getActiveSheet()->setCellValue('AB1','Discount in Percent');
	$objPHPExcel->getActiveSheet()->setCellValue('AC1','Advance Amount');
	$objPHPExcel->getActiveSheet()->setCellValue('AD1','Taxable Total');
	$objPHPExcel->getActiveSheet()->setCellValue('AE1','CGST Amount');
	$objPHPExcel->getActiveSheet()->setCellValue('AF1','SGST Amount');
	$objPHPExcel->getActiveSheet()->setCellValue('AG1','IGST Amount');
	$objPHPExcel->getActiveSheet()->setCellValue('AH1','CESS Amount');
	$objPHPExcel->getActiveSheet()->setCellValue('AI1','Invoice Total');
	$objPHPExcel->getActiveSheet()->setCellValue('AJ1','Invoice Description');

	//for HEADING ROW
	$objPHPExcel->getActiveSheet()->freezePane('A2')->setSharedStyle($headerStyle1, "A1:AJ1")->getRowDimension('1')->setRowHeight(40);
				 
	$totn = count($purchaseExportArray);
	if($totn>0) {
		//get row data
		for($i=2;$i<=$totn+1;$i++){
			//get coulmn data
			for($x=0; $x<count($purchaseExportArray); $x++):
				$objPHPExcel->getActiveSheet()->setSharedStyle($dataStyle1,  "A$i:AJ$i");
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $purchaseExportArray[$x]['reference_number']);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $purchaseExportArray[$x]['invoice_date']);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $purchaseExportArray[$x]['supply_type']);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $purchaseExportArray[$x]['state_name']);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $purchaseExportArray[$x]['advance_adjustment']);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $purchaseExportArray[$x]['receipt_voucher_number']);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, $purchaseExportArray[$x]['supplier_billing_name']);
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$i, $purchaseExportArray[$x]['supplier_billing_company_name']);
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, htmlspecialchars_decode($purchaseExportArray[$x]['supplier_billing_address']));
				$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, $purchaseExportArray[$x]['supplier_billing_state_name']);
				$objPHPExcel->getActiveSheet()->setCellValue('K'.$i, $purchaseExportArray[$x]['supplier_billing_country']);
				$objPHPExcel->getActiveSheet()->setCellValue('L'.$i, $purchaseExportArray[$x]['supplier_billing_vendor_type']);
				$objPHPExcel->getActiveSheet()->setCellValue('M'.$i, $purchaseExportArray[$x]['recipient_shipping_gstin_number']);
				$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, $purchaseExportArray[$x]['recipient_shipping_name']);
				$objPHPExcel->getActiveSheet()->setCellValue('O'.$i, $purchaseExportArray[$x]['recipient_shipping_company_name']);
				$objPHPExcel->getActiveSheet()->setCellValue('P'.$i, htmlspecialchars_decode($purchaseExportArray[$x]['recipient_shipping_address']));
				$objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, $purchaseExportArray[$x]['recipient_shipping_state_name']);
				$objPHPExcel->getActiveSheet()->setCellValue('R'.$i, $purchaseExportArray[$x]['recipient_shipping_country']);
				$objPHPExcel->getActiveSheet()->setCellValue('S'.$i, $purchaseExportArray[$x]['recipient_shipping_vendor_type']);
				$objPHPExcel->getActiveSheet()->setCellValue('T'.$i, $purchaseExportArray[$x]['recipient_shipping_gstin_number']);
				$objPHPExcel->getActiveSheet()->setCellValue('U'.$i, htmlspecialchars_decode(str_replace('&amp;','&',$purchaseExportArray[$x]['item_name'])));
				$objPHPExcel->getActiveSheet()->setCellValue('V'.$i, $purchaseExportArray[$x]['item_hsncode']);
				$objPHPExcel->getActiveSheet()->setCellValue('W'.$i, htmlspecialchars_decode($purchaseExportArray[$x]['item_description']));
				$objPHPExcel->getActiveSheet()->setCellValue('X'.$i, $purchaseExportArray[$x]['is_applicable']);
				$objPHPExcel->getActiveSheet()->setCellValue('Y'.$i, $purchaseExportArray[$x]['item_quantity']);
				$objPHPExcel->getActiveSheet()->setCellValue('Z'.$i, $purchaseExportArray[$x]['item_unit']);
				$objPHPExcel->getActiveSheet()->setCellValue('AA'.$i, $purchaseExportArray[$x]['item_unit_price']);
				$objPHPExcel->getActiveSheet()->setCellValue('AB'.$i, $purchaseExportArray[$x]['discount']);
				$objPHPExcel->getActiveSheet()->setCellValue('AC'.$i, $purchaseExportArray[$x]['advance_amount']);
				$objPHPExcel->getActiveSheet()->setCellValue('AD'.$i, $purchaseExportArray[$x]['subtotal']);
				$objPHPExcel->getActiveSheet()->setCellValue('AE'.$i, $purchaseExportArray[$x]['cgst_amount']);
				$objPHPExcel->getActiveSheet()->setCellValue('AF'.$i, $purchaseExportArray[$x]['sgst_amount']);
				$objPHPExcel->getActiveSheet()->setCellValue('AG'.$i, $purchaseExportArray[$x]['igst_amount']);
				$objPHPExcel->getActiveSheet()->setCellValue('AH'.$i, $purchaseExportArray[$x]['cess_amount']);
				$objPHPExcel->getActiveSheet()->setCellValue('AI'.$i, $purchaseExportArray[$x]['total']);
				$objPHPExcel->getActiveSheet()->setCellValue('AJ'.$i, htmlspecialchars_decode($purchaseExportArray[$x]['description']));
				$i++;
			endfor;
		}
	} else {
		$objPHPExcel->getActiveSheet()->mergeCells('A2:AL2')->setCellValue('A2','No data Found.');
		$objPHPExcel->getActiveSheet()->getStyle('A2:AL2')->getFont()->setSize(14)->setBold(true);
	}
			
	// Set BOOK title row bold;
	$objPHPExcel->getActiveSheet()->setTitle('Purchase Invoice');
	
	//heading row auto size
	foreach($range as $char) {
		$objPHPExcel->getActiveSheet()->getColumnDimension($char)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension("A".$char)->setAutoSize(true);
	}

	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
	$objWriter->save(PROJECT_ROOT . UPLOAD_DIR . "/export-docs/purchase-export-invoices-".$_SESSION['user_detail']['user_id'].".xlsx");
	$fileName= PROJECT_URL.UPLOAD_DIR . "/export-docs/purchase-export-invoices-".$_SESSION['user_detail']['user_id'].".xlsx";
	echo json_encode(array('excelPurchaselUrl' => $fileName));
	exit;
?>