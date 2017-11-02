
<?php
/*
	* 
	*  Developed By        :   Ishwar Lal Ghiya
	*  Date Created        :   June 6, 2017
	*  Last Modified By    :   Ishwar Lal Ghiya
	*  Last Modification   :   Client Item Listing
	* 
*/

extract($_POST);
parse_str($_POST['salesExportData'], $params);

/* DB table to use */
$client_invoice = $db_obj->getTableName('client_invoice');
$client_invoice_item = $db_obj->getTableName('client_invoice_item');
$state = $db_obj->getTableName('state');
$country = $db_obj->getTableName('country');
$vendor_type = $db_obj->getTableName('vendor_type');



$uWhere='select ci.reference_number,
		ci.invoice_date,
		ci.supply_type,
		st.state_name as supply_place,
			CASE 
				WHEN ci.advance_adjustment="1" THEN "Yes"
				WHEN ci.advance_adjustment="0" THEN "No"
			END AS advance_adjustment,	
			CASE
				WHEN ci.receipt_voucher_number="0" THEN ""
				ELSE ci.receipt_voucher_number
			END AS receipt_voucher_number,
		ci.billing_name,
		ci.billing_company_name as billing_business_name,
		ci.billing_address,
		ci.billing_state_name as billing_state,
		ct.country_name as billing_country,
		vt.vendor_name as billing_vendor_type,
		ci.billing_gstin_number,
		ci.shipping_name,
		ci.shipping_company_name as shipping_business_name,
		ci.shipping_address,
		ci.shipping_state_name,
		ct.country_name as shipping_country,
		vt.vendor_name as shipping_vendor_type,
		ci.shipping_gstin_number,
		it.item_name,
		it.item_hsncode,
		it.item_description,
			CASE
				WHEN it.is_applicable="0" THEN "Applicable"
				WHEN it.is_applicable="1" THEN "Non GST"
				WHEN it.is_applicable="2" THEN "Exempted"
			END AS is_applicable,
		it.item_quantity,
		it.item_unit,
		it.consolidate_rate,
		it.discount,
		it.advance_amount,
		it.subtotal,
		it.cgst_amount,
		it.sgst_amount,
		it.igst_amount,
		it.cess_amount,
		it.total,
		ci.description
		from '.$client_invoice.' ci 
		inner join '.$client_invoice_item. ' it on ci.invoice_id=it.invoice_id
		inner join '.$state. ' st on (st.state_tin = ci.supply_place)
		inner join '.$country. ' ct on (ct.id= ci.billing_country and ct.id=ci.shipping_country)
		inner join '.$vendor_type. ' vt on (vt.vendor_id= ci.billing_vendor_type and vt.vendor_id = ci.shipping_vendor_type)' ;
		

$uWhere .= " where 1=1 AND ci.is_deleted='0' AND ci.added_by='".$db_obj->sanitize($_SESSION['user_detail']['user_id'])."' ";

// check if start date in not blank
if(isset($params['from_date']) && !empty($params['from_date'])) {
	$uWhere .= " AND ci.invoice_date >= '" . $params['from_date'] ."'";
}

// check if end date in not blank
if(isset($params['to_date']) && !empty($params['to_date'])) {
	$uWhere .= " AND ci.invoice_date <= '" . $params['to_date'] ."'";
}

// check if invoice type in not blank
if(isset($params['invoice_type']) && !empty($params['invoice_type'])) {
	$uWhere .= " AND ci.invoice_type = '" . $params['invoice_type'] ."'";
}

// check if supply type in not blank
if(isset($params['supply_type']) && !empty($params['supply_type'])) {
	$uWhere .= " AND ci.supply_type = '" . $params['supply_type'] ."'";
}

// check if reference number in not blank
if(isset($params['reference_number']) && !empty($params['reference_number'])) {
	$uWhere .= " AND ci.reference_number LIKE '%" . $params['reference_number'] ."%'";
}

// check if place of supply in not blank
if(isset($params['place_of_supply']) && !empty($params['place_of_supply'])) {
	$uWhere .= " AND ci.supply_place = " . $params['place_of_supply'];
}

// check if billing state in not blank
if(isset($params['billing_state']) && !empty($params['billing_state'])) {
	$uWhere .= " AND ci.billing_state = " . $params['billing_state'];
}

// check if billing gstin number in not blank
if(isset($params['billing_gstin_number']) && !empty($params['billing_gstin_number'])) {
	$uWhere .= " AND ci.billing_gstin_number LIKE '%" . $params['billing_gstin_number'] ."%'";
}
$uWhere .= " order by ci.invoice_date ASC";
//echo $uWhere;

$salesExportArray=$db_obj->get_results($uWhere,false);
//echo "<pre>";print_r($salesExportArray);die;
$range=range('A','Z');
$objPHPExcel = new PHPExcel();
$headerStyle1 = new PHPExcel_Style();
$dataStyle1 = new PHPExcel_Style();
		
			$headerStyle1->applyFromArray(
			array(
				'borders'=>	array(
						'bottom'=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'top' 	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'right'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'left'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
					),
				'alignment'  => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
             			'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
						
					),
				'font'  => array(
						'bold'  => true,
						'size'  => 11,
						'name'  => 'Arial'
					),
				'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'F8CBAD')
        			),
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
					),
				)
			);


			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->setCellValue('A1','Invoice Reference Number');
			$objPHPExcel->getActiveSheet()->setCellValue('B1','Invoice Date');
			$objPHPExcel->getActiveSheet()->setCellValue('C1','Supply Type');
			$objPHPExcel->getActiveSheet()->setCellValue('D1','Ecommerce GSTIN');
			$objPHPExcel->getActiveSheet()->setCellValue('E1','Ecommerce Vendor Code');
			$objPHPExcel->getActiveSheet()->setCellValue('F1','Place of Supply');
			$objPHPExcel->getActiveSheet()->setCellValue('G1','Advance Adjustment');
			$objPHPExcel->getActiveSheet()->setCellValue('H1','Receipt Voucher Number');
			$objPHPExcel->getActiveSheet()->setCellValue('I1','Billing Name');
			$objPHPExcel->getActiveSheet()->setCellValue('J1','Billing Business Name');
			$objPHPExcel->getActiveSheet()->setCellValue('K1','Billing Address');
			$objPHPExcel->getActiveSheet()->setCellValue('L1','Billing State');
			$objPHPExcel->getActiveSheet()->setCellValue('M1','Billing Country');
			$objPHPExcel->getActiveSheet()->setCellValue('N1','Billing Vendor Type');
			$objPHPExcel->getActiveSheet()->setCellValue('O1','Billing GSTIN Number');
			$objPHPExcel->getActiveSheet()->setCellValue('P1','Shipping Name');
			$objPHPExcel->getActiveSheet()->setCellValue('Q1','Shipping Business Name');
			$objPHPExcel->getActiveSheet()->setCellValue('R1','Shipping Address');
			$objPHPExcel->getActiveSheet()->setCellValue('S1','Shipping State');
			$objPHPExcel->getActiveSheet()->setCellValue('T1','Shipping Country');
			$objPHPExcel->getActiveSheet()->setCellValue('U1','Shipping Vendor Type');
			$objPHPExcel->getActiveSheet()->setCellValue('V1','Shipping GSTIN Number');
			$objPHPExcel->getActiveSheet()->setCellValue('W1','Description of Goods');
			$objPHPExcel->getActiveSheet()->setCellValue('X1','Item HSN/SAC Code');
			$objPHPExcel->getActiveSheet()->setCellValue('Y1','Item Description');
			$objPHPExcel->getActiveSheet()->setCellValue('Z1','Item Taxes');
			$objPHPExcel->getActiveSheet()->setCellValue('AA1','Quantity');
			$objPHPExcel->getActiveSheet()->setCellValue('AB1','Unit');
			$objPHPExcel->getActiveSheet()->setCellValue('AC1','Rate');
			$objPHPExcel->getActiveSheet()->setCellValue('AD1','Discount in Percent');
			$objPHPExcel->getActiveSheet()->setCellValue('AE1','Advance Amount');
			$objPHPExcel->getActiveSheet()->setCellValue('AF1','Taxable Subtotal');
			$objPHPExcel->getActiveSheet()->setCellValue('AG1','CGST Amount');
			$objPHPExcel->getActiveSheet()->setCellValue('AH1','SGST Amount');
			$objPHPExcel->getActiveSheet()->setCellValue('AI1','IGST Amount');
			$objPHPExcel->getActiveSheet()->setCellValue('AJ1','CESS Amount');
			$objPHPExcel->getActiveSheet()->setCellValue('AK1','Invoice Total');
			$objPHPExcel->getActiveSheet()->setCellValue('AL1','Invoice Description');
			
			//for HEADING ROW
			$objPHPExcel->getActiveSheet()->freezePane('A2')
			->setSharedStyle($headerStyle1,  "A1:AL1")
			->getRowDimension('1')->setRowHeight(40);
			 
		$totn=count($salesExportArray);
		if($totn>0){
	   //get row data
        for($i=2;$i<=$totn+1;$i++){
			//get coulmn data
			for($x=0; $x<count($salesExportArray); $x++):
				$objPHPExcel->getActiveSheet()->setSharedStyle($dataStyle1,  "A$i:AL$i");
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$salesExportArray[$x]['reference_number']);
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$i,$salesExportArray[$x]['invoice_date']);
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$i,$salesExportArray[$x]['supply_type']);
                $objPHPExcel->getActiveSheet()->setCellValue('D'.$i,'');
                $objPHPExcel->getActiveSheet()->setCellValue('E'.$i,'');
                $objPHPExcel->getActiveSheet()->setCellValue('F'.$i,$salesExportArray[$x]['supply_place']);
                $objPHPExcel->getActiveSheet()->setCellValue('G'.$i,$salesExportArray[$x]['advance_adjustment']);
                $objPHPExcel->getActiveSheet()->setCellValue('H'.$i,$salesExportArray[$x]['receipt_voucher_number']);
                $objPHPExcel->getActiveSheet()->setCellValue('I'.$i,$salesExportArray[$x]['billing_name']);
                $objPHPExcel->getActiveSheet()->setCellValue('J'.$i,$salesExportArray[$x]['billing_business_name']);
                $objPHPExcel->getActiveSheet()->setCellValue('K'.$i,$salesExportArray[$x]['billing_address']);
                $objPHPExcel->getActiveSheet()->setCellValue('L'.$i,$salesExportArray[$x]['billing_state']);
                $objPHPExcel->getActiveSheet()->setCellValue('M'.$i,$salesExportArray[$x]['billing_country']);
                $objPHPExcel->getActiveSheet()->setCellValue('N'.$i,$salesExportArray[$x]['billing_vendor_type']);
				$objPHPExcel->getActiveSheet()->setCellValue('O'.$i,$salesExportArray[$x]['billing_gstin_number']);
				$objPHPExcel->getActiveSheet()->setCellValue('P'.$i,$salesExportArray[$x]['shipping_name']);
				$objPHPExcel->getActiveSheet()->setCellValue('Q'.$i,$salesExportArray[$x]['shipping_business_name']);
				$objPHPExcel->getActiveSheet()->setCellValue('R'.$i,$salesExportArray[$x]['shipping_address']);
				$objPHPExcel->getActiveSheet()->setCellValue('S'.$i,$salesExportArray[$x]['shipping_state_name']);
				$objPHPExcel->getActiveSheet()->setCellValue('T'.$i,$salesExportArray[$x]['shipping_country']);
				$objPHPExcel->getActiveSheet()->setCellValue('U'.$i,$salesExportArray[$x]['shipping_vendor_type']);
				$objPHPExcel->getActiveSheet()->setCellValue('V'.$i,$salesExportArray[$x]['shipping_gstin_number']);
				$objPHPExcel->getActiveSheet()->setCellValue('W'.$i,$salesExportArray[$x]['item_name']);
				$objPHPExcel->getActiveSheet()->setCellValue('X'.$i,$salesExportArray[$x]['item_hsncode']);
				$objPHPExcel->getActiveSheet()->setCellValue('Y'.$i,$salesExportArray[$x]['item_description']);
				$objPHPExcel->getActiveSheet()->setCellValue('Z'.$i,$salesExportArray[$x]['is_applicable']);
				$objPHPExcel->getActiveSheet()->setCellValue('AA'.$i,$salesExportArray[$x]['item_quantity']);
				$objPHPExcel->getActiveSheet()->setCellValue('AB'.$i,$salesExportArray[$x]['item_unit']);
				$objPHPExcel->getActiveSheet()->setCellValue('AC'.$i,$salesExportArray[$x]['consolidate_rate']);
				$objPHPExcel->getActiveSheet()->setCellValue('AD'.$i,$salesExportArray[$x]['discount']);
				$objPHPExcel->getActiveSheet()->setCellValue('AE'.$i,$salesExportArray[$x]['advance_amount']);
				$objPHPExcel->getActiveSheet()->setCellValue('AF'.$i,$salesExportArray[$x]['subtotal']);
				$objPHPExcel->getActiveSheet()->setCellValue('AG'.$i,$salesExportArray[$x]['cgst_amount']);
				$objPHPExcel->getActiveSheet()->setCellValue('AH'.$i,$salesExportArray[$x]['sgst_amount']);
				$objPHPExcel->getActiveSheet()->setCellValue('AI'.$i,$salesExportArray[$x]['igst_amount']);
				$objPHPExcel->getActiveSheet()->setCellValue('AJ'.$i,$salesExportArray[$x]['cess_amount']);
				$objPHPExcel->getActiveSheet()->setCellValue('AK'.$i,$salesExportArray[$x]['total']);
				$objPHPExcel->getActiveSheet()->setCellValue('AL'.$i,$salesExportArray[$x]['description']);
				$i++;
            endfor;
		}
	}else
	{
		$objPHPExcel->getActiveSheet()->mergeCells('A2:AL2')->setCellValue('A2','No data Found.');
		$objPHPExcel->getActiveSheet()->getStyle('A2:AL2')->getFont()->setSize(14)->setBold(true);
	}
		
	
// Set BOOK title row bold;
$objPHPExcel->getActiveSheet()->setTitle('Sales Invoice');

//heading row auto size
foreach($range as $char){
	$objPHPExcel->getActiveSheet()->getColumnDimension($char)->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("A".$char)->setAutoSize(true);
}

$userId=$_SESSION['user_detail']['user_id'];
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
 $objWriter->save(PROJECT_ROOT . UPLOAD_DIR . "/export-docs/sales-export-invoices-".$userId.".xlsx" );
$fileName= PROJECT_URL.UPLOAD_DIR . "/export-docs/sales-export-invoices-".$userId.".xlsx";
echo json_encode(array('salesExcelUrl'=>$fileName));
exit;
?>