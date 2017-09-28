<?php
/*
 * Created by Ishwar Lal Ghiya
 * Dated: 2017-09-22
 * Created Purpose : For Tally Import
*/

class tally extends validation {

    public function __construct() {
        parent::__construct();
    }

	public function uploadClientTallyInvoice() {

		$dataArray = array();
		$indexArray = array();
		$invoiceArray = array();
		$invoiceItemArray = array();

		$invoice_excel_dir_path = PROJECT_ROOT . "/gstr-1.xls";
		$invoice_excel_url_path = PROJECT_URL . "/gstr-1.xls";

		$inputFileType = PHPExcel_IOFactory::identify($invoice_excel_dir_path);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objReader->setReadDataOnly(true);
		$objPHPExcel = $objReader->load($invoice_excel_dir_path);

		//$UNIX_DATE = (42930 - 25569) * 86400;
		//echo gmdate("Y-m-d", $UNIX_DATE);

		echo "<pre>";
        foreach ($objPHPExcel->getAllSheets() as $sheet) {

			$sheetData = $sheet->toArray(null, true, true, true);
			$sheetData = array_map('array_filter', $sheetData);
			$sheetData = array_filter($sheetData);

			if(!empty($sheetData)) {
				
				$sheetTitle = strtolower($sheet->getTitle());

				switch ($sheetTitle) {
					case "b2b":
						$resultB2B = $this->uploadClientB2BInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path);
						break;
					case "b2cl":
						$resultB2CL = $this->uploadClientB2CLInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path);
						break;
					case "b2cs":
						//$resultB2CS = $this->uploadClientB2CSInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path);
						break;
					case "cdnr":
						//$resultCDNR = $this->uploadClientCDNRInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path);
						break;
					case "cdnur":
						echo "This is cdnur.";
						break;
					case "exp":
						echo "This is exp.";
						break;
					case "at":
						echo "This is at.";
						break;
					case "atadj":
						echo "This is atadj.";
						break;
				}
			}
        }
		echo "</pre>";
		die;

		$sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
		$sheetData = array_map('array_filter', $sheetData);
		$sheetData = array_filter($sheetData);

		echo "<pre>";
		print_r($sheetData);
		echo "</pre>";
		die;
	}

	final public function uploadClientB2BInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path) {
		
		$flag = true;
		$errorflag = false;
		$dataArray = array();
		$currentFinancialYear = $this->generateFinancialYear();
		$sheet = $objPHPExcel->getSheetByName("b2b");

		foreach($sheetData as $rowKey => $data) {

			if ($flag) {
				$indexArray = $data;
				$flag = false;
				continue;
			}

			$currentItemError = array();
			$dataArray['recipient_gstin'] = isset($data['A']) ? $data['A'] : '';
			$dataArray['invoice_number'] = isset($data['B']) ? $data['B'] : '';

			$invoice_date = isset($data['C']) ? (int)$data['C'] : '';
			if(is_numeric($invoice_date) && $invoice_date > 25569) {
				$UNIX_DATE = ($invoice_date - 25569) * 86400;
				$dataArray['invoice_date'] = gmdate("Y-m-d", $UNIX_DATE);
			} else {
				$errorflag = true;
				array_push($currentItemError, "Invalid Supply Type.");
			}

			$dataArray['invoice_value'] = isset($data['D']) ? $data['D'] : '';

			$place_of_supply = isset($data['E']) ? substr($data['E'], 0, 2) : '';
			if($place_of_supply != '') {

				$supply_state_data = $this->getStateDetailByStateTin($place_of_supply);
				if ($supply_state_data['status'] === "success") {
					$dataArray['place_of_supply'] = $supply_state_data['data']->state_tin;
				} else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Place Of Supply.");
				}
			} else {
				$errorflag = true;
				array_push($currentItemError, "Invalid Place Of Supply.");
			}

			$reverse_charge = isset($data['F']) ? $data['F'] : '';
			if ($reverse_charge != '' && strtoupper($reverse_charge) === 'Y') {
				$dataArray['reverse_charge'] = 'Y';
			} else if ($reverse_charge != '' && strtoupper($reverse_charge) === 'N') {
				$dataArray['reverse_charge'] = 'N';
			} else {
				$errorflag = true;
				array_push($currentItemError, "Invalid Reverse Charge.");
			}

			$invoice_type = isset($data['G']) ? $data['G'] : '';
			if ($invoice_type != '' && strtoupper($invoice_type) === 'REGULAR') {
				$dataArray['invoice_type'] = "R";
			} else if ($invoice_type != '' && strtoupper($invoice_type) === 'SEZ SUPPLIES WITH PAYMENT') {
				$dataArray['invoice_type'] = "SEWP";
			} else if ($invoice_type != '' && strtoupper($invoice_type) === 'SEZ SUPPLIES WITHOUT PAYMENT') {
				$dataArray['invoice_type'] = "SEWOP";
			} else if ($invoice_type != '' && strtoupper($invoice_type) === 'DEEMED EXP') {
				$dataArray['invoice_type'] = "DE";
			} else {
				$errorflag = true;
				array_push($currentItemError, "Invalid Invoice Type.");
			}

			$dataArray['ecommerce_gstin_number'] = isset($data['H']) ? $data['H'] : '';
			$dataArray['rate'] = isset($data['I']) ? number_format($data['I'], 2, '.', '') : 0.00;
			$dataArray['taxable_value'] = isset($data['J']) ? $data['J'] : 0.00;
			$dataArray['cess_amount'] = isset($data['K']) ? $data['K'] : 0.00;
			
			if(in_array($dataArray['rate'], $this->validateTaxRate) == false) {
				$errorflag = true;
				array_push($currentItemError, "Tax rate should be valid.");
			}

			$invoiceErrors = $this->validateClientTallyInvoice($dataArray);
			if ($invoiceErrors !== true || !empty($currentItemError)) {

				$errorflag = true;
				if ($invoiceErrors === true) {
					$invoiceErrors = array();
				}

				$invoiceErrors = array_merge($invoiceErrors, $currentItemError);
				$invoiceErrors = implode(", ", $invoiceErrors);
				$sheet->SetCellValue('L' . $rowKey, $invoiceErrors);

				/* set format of invoice date cell(C) of excel */
				$sheet->getStyle('C' . $rowKey)->getNumberFormat()->setFormatCode('dd-mmm-yy');
			} else {

				$dataArray['invoice_nature'] = "b2b";
				$checkB2BTallyInvoice = $this->get_row("select * from " . $this->tableNames['gstr1_return_summary'] . " where 1=1 AND invoice_nature = 'b2b' AND invoice_number = '" . $dataArray['invoice_number'] . "' AND rate = '" . $dataArray['rate'] . "' AND financial_year = '" . $currentFinancialYear . "' AND added_by = '" . $_SESSION['user_detail']['user_id'] . "'");
				if (count($checkB2BTallyInvoice) > 0) {

					$dataArray['updated_by'] = $_SESSION['user_detail']['user_id'];
					$dataArray['updated_date'] = date('Y-m-d H:i:s');
					$this->update($this->tableNames['gstr1_return_summary'], $dataArray, array('invoice_number' => $dataArray['invoice_number'], 'rate' => $dataArray['rate'], 'financial_year' => $currentFinancialYear, 'added_by' => $_SESSION['user_detail']['user_id']));
				} else {

					$dataArray['financial_year'] = $this->generateFinancialYear();
					$dataArray['created_from'] = 'E';
					$dataArray['status'] = 1;
					$dataArray['added_by'] = $_SESSION['user_detail']['user_id'];
					$dataArray['added_date'] = date('Y-m-d H:i:s');
					$this->insert($this->tableNames['gstr1_return_summary'], $dataArray);
				}
			}
		}

		if ($errorflag === true) {

			$sheet->SetCellValue('L4', "Error Information");

			/* set stylesheet of excel */
			$sheet->getStyle('A4:L4')->getFill('A4:L4')->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('F8CBAD');
			$sheet->getStyle('A4:L4')->getFont()->setName('Times New Roman')->setSize(11)->setBold(false);
			$sheet->getStyle('A4:L4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A4:L4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			for($col = 'A'; $col <= 'L'; $col++) {
				$sheet->getColumnDimension($col)->setAutoSize(true);
			}

			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			$objWriter->save($invoice_excel_dir_path);
			return "error";
		} else {
			return "success";
		}
	}
	
	final public function uploadClientB2CLInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path) {

		$flag = true;
		$errorflag = false;
		$dataArray = array();
		$currentFinancialYear = $this->generateFinancialYear();
		$sheet = $objPHPExcel->getSheetByName("b2cl");

		foreach($sheetData as $rowKey => $data) {

			if ($flag) {
				$indexArray = $data;
				$flag = false;
				continue;
			}

			$currentItemError = array();
			$dataArray['invoice_number'] = isset($data['A']) ? $data['A'] : '';

			$invoice_date = isset($data['B']) ? (int)$data['B'] : '';
			if(is_numeric($invoice_date) && $invoice_date > 25569) {
				$UNIX_DATE = ($invoice_date - 25569) * 86400;
				$dataArray['invoice_date'] = gmdate("Y-m-d", $UNIX_DATE);
			} else {
				$errorflag = true;
				array_push($currentItemError, "Invalid Supply Type.");
			}

			$dataArray['invoice_value'] = isset($data['C']) ? $data['C'] : '';

			$place_of_supply = isset($data['D']) ? substr($data['D'], 0, 2) : '';
			if($place_of_supply != '') {

				$supply_state_data = $this->getStateDetailByStateTin($place_of_supply);
				if ($supply_state_data['status'] === "success") {
					$dataArray['place_of_supply'] = $supply_state_data['data']->state_tin;
				} else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Place Of Supply.");
				}
			} else {
				$errorflag = true;
				array_push($currentItemError, "Invalid Place Of Supply.");
			}

			$dataArray['rate'] = isset($data['E']) ? number_format($data['E'], 2, '.', '') : 0.00;
			$dataArray['taxable_value'] = isset($data['F']) ? $data['F'] : 0.00;
			$dataArray['cess_amount'] = isset($data['G']) ? $data['G'] : 0.00;
			$dataArray['ecommerce_gstin_number'] = isset($data['H']) ? $data['H'] : '';

			if(in_array($dataArray['rate'], $this->validateTaxRate) == false) {
				$errorflag = true;
				array_push($currentItemError, "Tax rate should be valid.");
			}

			$invoiceErrors = $this->validateClientTallyInvoice($dataArray);
			if ($invoiceErrors !== true || !empty($currentItemError)) {

				$errorflag = true;
				if ($invoiceErrors === true) {
					$invoiceErrors = array();
				}

				$invoiceErrors = array_merge($invoiceErrors, $currentItemError);
				$invoiceErrors = implode(", ", $invoiceErrors);
				$sheet->SetCellValue('I' . $rowKey, $invoiceErrors);

				/* set format of invoice date cell(B) of excel */
				$sheet->getStyle('B' . $rowKey)->getNumberFormat()->setFormatCode('dd-mmm-yy');
			} else {

				$dataArray['invoice_nature'] = "b2cl";
				$checkB2CLTallyInvoice = $this->get_row("select * from " . $this->tableNames['gstr1_return_summary'] . " where 1=1 AND invoice_nature = 'b2cl' AND invoice_number = '" . $dataArray['invoice_number'] . "' AND rate = '" . $dataArray['rate'] . "' AND financial_year = '" . $currentFinancialYear . "' AND added_by = '" . $_SESSION['user_detail']['user_id'] . "'");
				if (count($checkB2CLTallyInvoice) > 0) {

					$dataArray['updated_by'] = $_SESSION['user_detail']['user_id'];
					$dataArray['updated_date'] = date('Y-m-d H:i:s');
					$this->update($this->tableNames['gstr1_return_summary'], $dataArray, array('invoice_number' => $dataArray['invoice_number'], 'rate' => $dataArray['rate'], 'financial_year' => $currentFinancialYear, 'added_by' => $_SESSION['user_detail']['user_id']));
				} else {

					$dataArray['financial_year'] = $this->generateFinancialYear();
					$dataArray['created_from'] = 'E';
					$dataArray['status'] = 1;
					$dataArray['added_by'] = $_SESSION['user_detail']['user_id'];
					$dataArray['added_date'] = date('Y-m-d H:i:s');
					$this->insert($this->tableNames['gstr1_return_summary'], $dataArray);
				}
			}
		}

		if ($errorflag === true) {

			$sheet->SetCellValue('I4', "Error Information");

			/* set stylesheet of excel */
			$sheet->getStyle('A4:I4')->getFill('A4:I4')->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('F8CBAD');
			$sheet->getStyle('A4:I4')->getFont()->setName('Times New Roman')->setSize(11)->setBold(false);
			$sheet->getStyle('A4:I4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A4:I4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			for($col = 'A'; $col <= 'I'; $col++) {
				$sheet->getColumnDimension($col)->setAutoSize(true);
			}

			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			$objWriter->save($invoice_excel_dir_path);
			return "error";
		} else {
			return "success";
		}
	}
	
	final public function uploadClientB2CSInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path) {

		$flag = true;
		$errorflag = false;
		$dataArray = array();
		$currentFinancialYear = $this->generateFinancialYear();
		$sheet = $objPHPExcel->getSheetByName("b2cs");

		print_r($sheetData);
		die;

		foreach($sheetData as $rowKey => $data) {

			if ($flag) {
				$indexArray = $data;
				$flag = false;
				continue;
			}

			$currentItemError = array();
			$type = isset($data['A']) ? $data['A'] : '';
			if ($type != '' && strtoupper($type) === 'OE') {
				$dataArray['type'] = 'OE';
			} else if ($type != '' && strtoupper($type) === 'E') {
				$dataArray['type'] = 'E';
			} else {
				$errorflag = true;
				array_push($currentItemError, "Invalid Type.");
			}

			$place_of_supply = isset($data['B']) ? substr($data['B'], 0, 2) : '';
			if($place_of_supply != '') {

				$supply_state_data = $this->getStateDetailByStateTin($place_of_supply);
				if ($supply_state_data['status'] === "success") {
					$dataArray['place_of_supply'] = $supply_state_data['data']->state_tin;
				} else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Place Of Supply.");
				}
			} else {
				$errorflag = true;
				array_push($currentItemError, "Invalid Place Of Supply.");
			}

			$dataArray['rate'] = isset($data['C']) ? number_format($data['C'], 2, '.', '') : 0.00;
			$dataArray['taxable_value'] = isset($data['D']) ? $data['D'] : 0.00;
			$dataArray['cess_amount'] = isset($data['E']) ? $data['E'] : 0.00;
			$dataArray['ecommerce_gstin_number'] = isset($data['F']) ? $data['F'] : '';

			if(isset($dataArray['type']) && $dataArray['type'] == 'E') {

				if($dataArray['ecommerce_gstin_number'] == '') {
					$errorflag = true;
					array_push($currentItemError, "Ecommerce GSTIN required.");
				}
			}

			if(in_array($dataArray['rate'], $this->validateTaxRate) == false) {
				$errorflag = true;
				array_push($currentItemError, "Tax rate should be valid.");
			}

			$invoiceErrors = $this->validateClientTallyInvoice($dataArray);
			if ($invoiceErrors !== true || !empty($currentItemError)) {

				$errorflag = true;
				if ($invoiceErrors === true) {
					$invoiceErrors = array();
				}

				$invoiceErrors = array_merge($invoiceErrors, $currentItemError);
				$invoiceErrors = implode(", ", $invoiceErrors);
				$sheet->SetCellValue('G' . $rowKey, $invoiceErrors);
			} else {

				$dataArray['invoice_nature'] = "b2cs";
				$dataArray['financial_year'] = $this->generateFinancialYear();
				$dataArray['created_from'] = 'E';
				$dataArray['status'] = 1;
				$dataArray['added_by'] = $_SESSION['user_detail']['user_id'];
				$dataArray['added_date'] = date('Y-m-d H:i:s');
				$this->insert($this->tableNames['gstr1_return_summary'], $dataArray);
			}
		}

		if ($errorflag === true) {

			$sheet->SetCellValue('G4', "Error Information");

			/* set stylesheet of excel */
			$sheet->getStyle('A4:G4')->getFill('A4:G4')->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('F8CBAD');
			$sheet->getStyle('A4:G4')->getFont()->setName('Times New Roman')->setSize(11)->setBold(false);
			$sheet->getStyle('A4:G4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A4:G4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			for($col = 'A'; $col <= 'G'; $col++) {
				$sheet->getColumnDimension($col)->setAutoSize(true);
			}

			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			$objWriter->save($invoice_excel_dir_path);
			return "error";
		} else {
			return "success";
		}
	}

	final public function validateClientTallyInvoice($dataArr) {

		if (array_key_exists("recipient_gstin", $dataArr)) {
			$rules['recipient_gstin'] = 'required||pattern:/^' . $this->validateType['gstinnumber'] . '+$/||min:15||max:15|#|lable_name:Recipient GSTIN Number';
		}

		if (array_key_exists("invoice_number", $dataArr)) {
			$rules['invoice_number'] = 'required||min:1||max:16|#|lable_name:Invoice Number';
		}

		if (array_key_exists("invoice_date", $dataArr)) {
			$rules['invoice_date'] = 'required||date|#|lable_name:Invoice Date';
		}

		if (array_key_exists("invoice_value", $dataArr)) {
			$rules['invoice_value'] = 'required||numeric||decimalzero|#|lable_name:Invoice Value';
		}

		if (array_key_exists("ecommerce_gstin_number", $dataArr)) {
			$rules['ecommerce_gstin_number'] = 'pattern:/^' . $this->validateType['gstinnumber'] . '+$/||min:15||max:15|#|lable_name:Ecommerce GSTIN Number';
		}

		if (array_key_exists("rate", $dataArr)) {
			$rules['rate'] = 'required||numeric||decimalzero|#|lable_name:Invoice Rate';
		}

		if (array_key_exists("taxable_value", $dataArr)) {
			$rules['taxable_value'] = 'required||numeric||decimalzero|#|lable_name:Invoice Taxable Value';
		}

		if (array_key_exists("cess_amount", $dataArr)) {
			$rules['cess_amount'] = 'required||numeric||decimalzero|#|lable_name:Invoice Cess Amount';
		}
		
		$valid = $this->vali_obj->validate($dataArr, $rules);
        if ($valid->hasErrors()) {
            cms_validate::$errors = array();
            $err_arr = $valid->allErrors();
            $valid->clearMessages();
            return $err_arr;
        }
        return true;
	}
}
?>