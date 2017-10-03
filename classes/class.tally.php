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

		$resultB2B = '';
		$resultB2CL = '';
		$resultB2CS = '';
		$resultCDNR = '';
		$resultCDNUR = '';
		$resultEXP = '';
		$resultAT = '';
		$resultATADJ = '';
		$resultEXEMP = '';
		$resultHSN = '';
		
		$return_period = isset($_POST['tally_return_period']) ? (string)$_POST['tally_return_period'] : '';
		if(empty($return_period)) {
			$this->setError('Invalid return period.');
			return false;
		}

		if ($_FILES['invoice_xlsx']['name'] != '' && $_FILES['invoice_xlsx']['error'] == 0) {

			$invoice_excel = $this->imageUploads($_FILES['invoice_xlsx'], 'gstr1-docs', 'upload', $this->allowExcelExt);
            if ($invoice_excel == FALSE) {
                return false;
            }

            $invoice_excel_dir_path = PROJECT_ROOT . UPLOAD_DIR . "/gstr1-docs/" . $invoice_excel;
            $invoice_excel_url_path = PROJECT_URL . UPLOAD_DIR . "/gstr1-docs/" . $invoice_excel;

			$inputFileType = PHPExcel_IOFactory::identify($invoice_excel_dir_path);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objReader->setReadDataOnly(true);
			$objPHPExcel = $objReader->load($invoice_excel_dir_path);

			foreach ($objPHPExcel->getAllSheets() as $sheet) {

				$sheetData = $sheet->toArray(null, true, true, true);
				$sheetData = array_map('array_filter', $sheetData);
				$sheetData = array_filter($sheetData);

				if(!empty($sheetData)) {

					$sheetTitle = strtolower($sheet->getTitle());

					switch ($sheetTitle) {
						case "b2b":
							$resultB2B = $this->uploadClientB2BInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path, $return_period);
							break;
						case "b2cl":
							$resultB2CL = $this->uploadClientB2CLInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path, $return_period);
							break;
						case "b2cs":
							$resultB2CS = $this->uploadClientB2CSInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path, $return_period);
							break;
						case "cdnr":
							$resultCDNR = $this->uploadClientCDNRInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path, $return_period);
							break;
						case "cdnur":
							$resultCDNUR = $this->uploadClientCDNURInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path, $return_period);
							break;
						case "exp":
							$resultEXP = $this->uploadClientEXPInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path, $return_period);
							break;
						case "at":
							$resultAT = $this->uploadClientATInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path, $return_period);
							break;
						case "atadj":
							$resultATADJ = $this->uploadClientATADJInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path, $return_period);
							break;
						case "exemp":
							$resultEXEMP = $this->uploadClientEXEMPInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path);
							break;
						case "hsn":
							$resultHSN = $this->uploadClientHSNInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path);
							break;
						case "docs":
							$resultDOCS = $this->uploadClientDOCSInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path);
							break;
					}
				}
			}

			/*
				echo "<pre>";
				print_r($resultB2B);
				echo "<br>";
				print_r($resultB2CL);
				echo "<br>";
				print_r($resultB2CS);
				echo "<br>";
				print_r($resultCDNR);
				echo "<br>";
				print_r($resultCDNUR);
				echo "<br>";
				print_r($resultEXP);
				echo "<br>";
				print_r($resultAT);
				echo "<br>";
				print_r($resultATADJ);
				echo "<br>";
				print_r($resultEXEMP);
				echo "<br>";
				print_r($resultHSN);
				echo "</pre>";
			*/

			$resultB2BArray = json_decode($resultB2B);
			$resultB2CLArray = json_decode($resultB2CL);
			$resultB2CSArray = json_decode($resultB2CS);
			$resultCDNRArray = json_decode($resultCDNR);
			$resultCDNURArray = json_decode($resultCDNUR);
			$resultEXPArray = json_decode($resultEXP);
			$resultATArray = json_decode($resultAT);
			$resultATADJArray = json_decode($resultATADJ);
			$resultEXEMPArray = json_decode($resultEXEMP);
			$resultHSNArray = json_decode($resultHSN);

			if(
				$resultB2BArray->status === "success" && 
				$resultB2CLArray->status === "success" && 
				$resultB2CSArray->status === "success" && 
				$resultCDNRArray->status === "success" && 
				$resultCDNURArray->status === "success" && 
				$resultEXPArray->status === "success" && 
				$resultATArray->status === "success" && 
				$resultATADJArray->status === "success" && 
				$resultEXEMPArray->status === "success" && 
				$resultHSNArray->status === "success"
			) {

				$dataConditionArray['return_period'] = $return_period;
				$dataConditionArray['added_by'] = $this->sanitize($_SESSION['user_detail']['user_id']);
				$this->deletData($this->tableNames['gstr1_return_summary'], $dataConditionArray);
				$this->logMsg("Tally invoice deleted for return period : " . $return_period . " by User ID : " . $_SESSION['user_detail']['user_id'] . ".","gstr1_deleted");

				$dataEXEMPConditionArray['financial_month'] = $return_period;
				$dataEXEMPConditionArray['added_by'] = $this->sanitize($_SESSION['user_detail']['user_id']);
				$dataEXEMPConditionArray['type'] = "gstr1nil";
				$this->deletData($this->tableNames['return_upload_summary'], $dataEXEMPConditionArray);
				$this->logMsg("Tally EXEMP deleted for return period : " . $return_period . " by User ID : " . $_SESSION['user_detail']['user_id'] . ".","gstr1_exemp_deleted");

				$dataHSNConditionArray['financial_month'] = $return_period;
				$dataHSNConditionArray['added_by'] = $this->sanitize($_SESSION['user_detail']['user_id']);
				$dataHSNConditionArray['type'] = "gstr1hsn";
				$this->deletData($this->tableNames['return_upload_summary'], $dataHSNConditionArray);
				$this->logMsg("Tally HSN deleted for return period : " . $return_period . " by User ID : " . $_SESSION['user_detail']['user_id'] . ".","gstr1_hsn_deleted");

				if(count($resultB2BArray->invoiceB2BArray) > 0 && !empty($resultB2BArray->invoiceB2BArray)) {
					$this->insertMultiple($this->tableNames['gstr1_return_summary'], $this->objectToArray($resultB2BArray->invoiceB2BArray));
					$this->logMsg("Tally B2B invoice inserted for return period : " . $return_period . " by User ID : " . $_SESSION['user_detail']['user_id'] . ".","gstr1_b2b_inserted");
				}

				if(count($resultB2CLArray->invoiceB2CLArray) > 0 && !empty($resultB2CLArray->invoiceB2CLArray)) {
					$this->insertMultiple($this->tableNames['gstr1_return_summary'], $this->objectToArray($resultB2CLArray->invoiceB2CLArray));
					$this->logMsg("Tally B2CL invoice inserted for return period : " . $return_period . " by User ID : " . $_SESSION['user_detail']['user_id'] . ".","gstr1_b2cl_inserted");
				}

				if(count($resultB2CSArray->invoiceB2CSArray) > 0 && !empty($resultB2CSArray->invoiceB2CSArray)) {
					$this->insertMultiple($this->tableNames['gstr1_return_summary'], $this->objectToArray($resultB2CSArray->invoiceB2CSArray));
					$this->logMsg("Tally B2CS invoice inserted for return period : " . $return_period . " by User ID : " . $_SESSION['user_detail']['user_id'] . ".","gstr1_b2cs_inserted");
				}

				if(count($resultCDNRArray->invoiceCDNRArray) > 0 && !empty($resultCDNRArray->invoiceCDNRArray)) {
					$this->insertMultiple($this->tableNames['gstr1_return_summary'], $this->objectToArray($resultCDNRArray->invoiceCDNRArray));
					$this->logMsg("Tally CDNR invoice inserted for return period : " . $return_period . " by User ID : " . $_SESSION['user_detail']['user_id'] . ".","gstr1_cdnr_inserted");
				}

				if(count($resultCDNURArray->invoiceCDNURArray) > 0 && !empty($resultCDNURArray->invoiceCDNURArray)) {
					$this->insertMultiple($this->tableNames['gstr1_return_summary'], $this->objectToArray($resultCDNURArray->invoiceCDNURArray));
					$this->logMsg("Tally CDNUR invoice inserted for return period : " . $return_period . " by User ID : " . $_SESSION['user_detail']['user_id'] . ".","gstr1_cdnur_inserted");
				}

				if(count($resultEXPArray->invoiceEXPArray) > 0 && !empty($resultEXPArray->invoiceEXPArray)) {
					$this->insertMultiple($this->tableNames['gstr1_return_summary'], $this->objectToArray($resultEXPArray->invoiceEXPArray));
					$this->logMsg("Tally EXP invoice inserted for return period : " . $return_period . " by User ID : " . $_SESSION['user_detail']['user_id'] . ".","gstr1_exp_inserted");
				}

				if(count($resultATArray->invoiceATArray) > 0 && !empty($resultATArray->invoiceATArray)) {
					$this->insertMultiple($this->tableNames['gstr1_return_summary'], $this->objectToArray($resultATArray->invoiceATArray));
					$this->logMsg("Tally AT invoice inserted for return period : " . $return_period . " by User ID : " . $_SESSION['user_detail']['user_id'] . ".","gstr1_at_inserted");
				}

				if(count($resultATADJArray->invoiceATADJArray) > 0 && !empty($resultATADJArray->invoiceATADJArray)) {
					$this->insertMultiple($this->tableNames['gstr1_return_summary'], $this->objectToArray($resultATADJArray->invoiceATADJArray));
					$this->logMsg("Tally ATADJ invoice inserted for return period : " . $return_period . " by User ID : " . $_SESSION['user_detail']['user_id'] . ".","gstr1_atadj_inserted");
				}
				
				if(count($resultEXEMPArray->invoiceEXEMPArray) > 0 && !empty($resultEXEMPArray->invoiceEXEMPArray)) {

					$dataEXEMPArray['return_data'] = base64_encode(json_encode($this->objectToArray($resultEXEMPArray->invoiceEXEMPArray)));
					$dataEXEMPArray['type'] = "gstr1nil";
					$dataEXEMPArray['financial_month'] = $return_period;
					$dataEXEMPArray['added_by'] = $this->sanitize($_SESSION['user_detail']['user_id']);
					$dataEXEMPArray['added_date'] = date('Y-m-d H:i:s');
					$this->insert($this->tableNames['return_upload_summary'], $dataEXEMPArray);
					$this->logMsg("Tally EXEMP invoice inserted for return period : " . $return_period . " by User ID : " . $_SESSION['user_detail']['user_id'] . ".","gstr1_exemp_inserted");
				}

				if(count($resultHSNArray->invoiceHSNArray) > 0 && !empty($resultHSNArray->invoiceHSNArray)) {

					$dataHSNArray['return_data'] = base64_encode(json_encode($this->objectToArray($resultHSNArray->invoiceHSNArray)));
					$dataHSNArray['type'] = "gstr1hsn";
					$dataHSNArray['financial_month'] = $return_period;
					$dataHSNArray['added_by'] = $this->sanitize($_SESSION['user_detail']['user_id']);
					$dataHSNArray['added_date'] = date('Y-m-d H:i:s');
					$this->insert($this->tableNames['return_upload_summary'], $dataHSNArray);
					$this->logMsg("Tally HSN invoice inserted for return period : " . $return_period . " by User ID : " . $_SESSION['user_detail']['user_id'] . ".","gstr1_hsn_inserted");
				}

				$this->setSuccess("All Tally invoice imported.");
                return true;

			} else {
				$this->setError($this->validationMessage['excelerror']);
				$resultArray = array("status" => "error", "excelurl" => $invoice_excel_url_path);
				return json_encode($resultArray);
			}
		}		
	}

	function objectToArray($obj) {

		if (is_object($obj)) $obj = (array)$obj;
		if (is_array($obj)) {
			$new = array();
			foreach ($obj as $key => $val) {
				$new[$key] = $this->objectToArray($val);
			}
		} else {
			$new = $obj;
		}

		return $new;
	}

	final public function uploadClientDOCSInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path) {

		$flag = true;
		$errorflag = false;
		$dataArray = array();
		$indexArray = array();
		$invoiceArray = array();
		$currentFinancialYear = $this->generateFinancialYear();
		$sheet = $objPHPExcel->getSheetByName("docs");
		
		echo "<pre>";
		print_r($sheetData);
		die;

		foreach($sheetData as $rowKey => $data) {

			if ($flag) {
				$indexArray = $data;
				$flag = false;
				continue;
			}

			$currentItemError = array();
			$dataArray['hsn'] = isset($data['A']) ? $data['A'] : '';
			$dataArray['description'] = isset($data['B']) ? $data['B'] : '';
			$dataArray['unit'] = isset($data['C']) ? $data['C'] : '';
			$dataArray['qty'] = isset($data['D']) ? $data['D'] : '';

			$invoice_total_value = isset($data['E']) ? $data['E'] : '';
			if(is_numeric($invoice_total_value) && $invoice_total_value >= 0) {
				$dataArray['invoice_total_value'] = number_format($invoice_total_value, 2, '.', '');
			} else {
				$dataArray['invoice_total_value'] = 0.00;
			}

			$taxable_subtotal = isset($data['F']) ? $data['F'] : '';
			if(is_numeric($taxable_subtotal) && $taxable_subtotal >= 0) {
				$dataArray['taxable_subtotal'] = number_format($taxable_subtotal, 2, '.', '');
			} else {
				$dataArray['taxable_subtotal'] = 0.00;
			}

			$igst_amount = isset($data['G']) ? $data['G'] : '';
			if(is_numeric($igst_amount) && $igst_amount >= 0) {
				$dataArray['igst'] = number_format($igst_amount, 2, '.', '');
			} else {
				$dataArray['igst'] = 0.00;
			}
			
			$cgst_amount = isset($data['H']) ? $data['H'] : '';
			if(is_numeric($cgst_amount) && $cgst_amount >= 0) {
				$dataArray['cgst'] = number_format($cgst_amount, 2, '.', '');
			} else {
				$dataArray['cgst'] = 0.00;
			}
			
			$sgst_amount = isset($data['I']) ? $data['I'] : '';
			if(is_numeric($sgst_amount) && $sgst_amount >= 0) {
				$dataArray['sgst'] = number_format($sgst_amount, 2, '.', '');
			} else {
				$dataArray['sgst'] = 0.00;
			}

			$cess_amount = isset($data['J']) ? $data['J'] : '';
			if(is_numeric($cess_amount) && $cess_amount >= 0) {
				$dataArray['cess'] = number_format($cess_amount, 2, '.', '');
			} else {
				$dataArray['cess'] = 0.00;
			}

			$invoiceErrors = $this->validateClientTallyInvoice($dataArray);
			if ($invoiceErrors !== true || !empty($currentItemError)) {

				$errorflag = true;
				if ($invoiceErrors === true) {
					$invoiceErrors = array();
				}

				$invoiceErrors = array_merge($invoiceErrors, $currentItemError);
				$invoiceErrors = implode(", ", $invoiceErrors);
				$sheet->SetCellValue('K' . $rowKey, $invoiceErrors);
			} else {

				$singleInvoiceArray = array(
					"hsn" => $dataArray['hsn'],
					"description" => $dataArray['description'],
					"unit" => $dataArray['unit'],
					"qty" => $dataArray['qty'],
					"invoice_total_value" => $dataArray['invoice_total_value'],
					"taxable_subtotal" => $dataArray['taxable_subtotal'],
					"igst" => $dataArray['igst'],
					"cgst" => $dataArray['cgst'],
					"sgst" => $dataArray['sgst'],
					"cess" => $dataArray['cess']
				);

				array_push($invoiceArray, $singleInvoiceArray);
			}

			/* set format of invoice amount cell(D,E,F,G,H,I,J) of excel */
			$sheet->getStyle('D' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
			$sheet->getStyle('E' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
			$sheet->getStyle('F' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
			$sheet->getStyle('G' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
			$sheet->getStyle('H' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
			$sheet->getStyle('I' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
			$sheet->getStyle('J' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
		}

		if ($errorflag === true) {

			$sheet->SetCellValue('K4', "Error Information");

			/* set stylesheet of excel */
			$sheet->getStyle('A4:K4')->getFill('A4:K4')->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('F8CBAD');
			$sheet->getStyle('A4:K4')->getFont()->setName('Times New Roman')->setSize(11)->setBold(false);
			$sheet->getStyle('A4:K4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A4:K4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			for($col = 'A'; $col <= 'K'; $col++) {
				$sheet->getColumnDimension($col)->setAutoSize(true);
			}

			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			$objWriter->save($invoice_excel_dir_path);
			$resultArray = array("status" => "error");
			return json_encode($resultArray);
		} else {

			/* set stylesheet of excel */
			$sheet->getStyle('A4:J4')->getFill('A4:J4')->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('F8CBAD');
			$sheet->getStyle('A4:J4')->getFont()->setName('Times New Roman')->setSize(11)->setBold(false);
			$sheet->getStyle('A4:J4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A4:J4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			for($col = 'A'; $col <= 'J'; $col++) {
				$sheet->getColumnDimension($col)->setAutoSize(true);
			}

			$resultArray = array("status" => "success", "invoiceHSNArray" => $invoiceArray);
			return json_encode($resultArray);
		}
	}

	final public function uploadClientHSNInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path) {

		$flag = true;
		$errorflag = false;
		$dataArray = array();
		$indexArray = array();
		$invoiceArray = array();
		$currentFinancialYear = $this->generateFinancialYear();
		$sheet = $objPHPExcel->getSheetByName("hsn");

		foreach($sheetData as $rowKey => $data) {

			if ($flag) {
				$indexArray = $data;
				$flag = false;
				continue;
			}

			$currentItemError = array();
			$dataArray['hsn'] = isset($data['A']) ? $data['A'] : '';
			$dataArray['description'] = isset($data['B']) ? $data['B'] : '';
			$dataArray['unit'] = isset($data['C']) ? $data['C'] : '';
			$dataArray['qty'] = isset($data['D']) ? $data['D'] : '';

			$invoice_total_value = isset($data['E']) ? $data['E'] : '';
			if(is_numeric($invoice_total_value) && $invoice_total_value >= 0) {
				$dataArray['invoice_total_value'] = number_format($invoice_total_value, 2, '.', '');
			} else {
				$dataArray['invoice_total_value'] = 0.00;
			}

			$taxable_subtotal = isset($data['F']) ? $data['F'] : '';
			if(is_numeric($taxable_subtotal) && $taxable_subtotal >= 0) {
				$dataArray['taxable_subtotal'] = number_format($taxable_subtotal, 2, '.', '');
			} else {
				$dataArray['taxable_subtotal'] = 0.00;
			}

			$igst_amount = isset($data['G']) ? $data['G'] : '';
			if(is_numeric($igst_amount) && $igst_amount >= 0) {
				$dataArray['igst'] = number_format($igst_amount, 2, '.', '');
			} else {
				$dataArray['igst'] = 0.00;
			}
			
			$cgst_amount = isset($data['H']) ? $data['H'] : '';
			if(is_numeric($cgst_amount) && $cgst_amount >= 0) {
				$dataArray['cgst'] = number_format($cgst_amount, 2, '.', '');
			} else {
				$dataArray['cgst'] = 0.00;
			}
			
			$sgst_amount = isset($data['I']) ? $data['I'] : '';
			if(is_numeric($sgst_amount) && $sgst_amount >= 0) {
				$dataArray['sgst'] = number_format($sgst_amount, 2, '.', '');
			} else {
				$dataArray['sgst'] = 0.00;
			}

			$cess_amount = isset($data['J']) ? $data['J'] : '';
			if(is_numeric($cess_amount) && $cess_amount >= 0) {
				$dataArray['cess'] = number_format($cess_amount, 2, '.', '');
			} else {
				$dataArray['cess'] = 0.00;
			}

			$invoiceErrors = $this->validateClientTallyInvoice($dataArray);
			if ($invoiceErrors !== true || !empty($currentItemError)) {

				$errorflag = true;
				if ($invoiceErrors === true) {
					$invoiceErrors = array();
				}

				$invoiceErrors = array_merge($invoiceErrors, $currentItemError);
				$invoiceErrors = implode(", ", $invoiceErrors);
				$sheet->SetCellValue('K' . $rowKey, $invoiceErrors);
			} else {

				$singleInvoiceArray = array(
					"hsn" => $dataArray['hsn'],
					"description" => $dataArray['description'],
					"unit" => $dataArray['unit'],
					"qty" => $dataArray['qty'],
					"invoice_total_value" => $dataArray['invoice_total_value'],
					"taxable_subtotal" => $dataArray['taxable_subtotal'],
					"igst" => $dataArray['igst'],
					"cgst" => $dataArray['cgst'],
					"sgst" => $dataArray['sgst'],
					"cess" => $dataArray['cess']
				);

				array_push($invoiceArray, $singleInvoiceArray);
			}

			/* set format of invoice amount cell(D,E,F,G,H,I,J) of excel */
			$sheet->getStyle('D' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
			$sheet->getStyle('E' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
			$sheet->getStyle('F' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
			$sheet->getStyle('G' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
			$sheet->getStyle('H' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
			$sheet->getStyle('I' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
			$sheet->getStyle('J' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
		}

		if ($errorflag === true) {

			$sheet->SetCellValue('K4', "Error Information");

			/* set stylesheet of excel */
			$sheet->getStyle('A4:K4')->getFill('A4:K4')->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('F8CBAD');
			$sheet->getStyle('A4:K4')->getFont()->setName('Times New Roman')->setSize(11)->setBold(false);
			$sheet->getStyle('A4:K4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A4:K4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			for($col = 'A'; $col <= 'K'; $col++) {
				$sheet->getColumnDimension($col)->setAutoSize(true);
			}

			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			$objWriter->save($invoice_excel_dir_path);
			$resultArray = array("status" => "error");
			return json_encode($resultArray);
		} else {

			/* set stylesheet of excel */
			$sheet->getStyle('A4:J4')->getFill('A4:J4')->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('F8CBAD');
			$sheet->getStyle('A4:J4')->getFont()->setName('Times New Roman')->setSize(11)->setBold(false);
			$sheet->getStyle('A4:J4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A4:J4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			for($col = 'A'; $col <= 'J'; $col++) {
				$sheet->getColumnDimension($col)->setAutoSize(true);
			}

			$resultArray = array("status" => "success", "invoiceHSNArray" => $invoiceArray);
			return json_encode($resultArray);
		}
	}

	final public function uploadClientEXEMPInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path) {

		$flag = true;
		$errorflag = false;
		$dataArray = array();
		$indexArray = array();
		$invoiceArray = array();
		$currentFinancialYear = $this->generateFinancialYear();
		$sheet = $objPHPExcel->getSheetByName("exemp");

		foreach($sheetData as $rowKey => $data) {

			if ($flag) {
				$indexArray = $data;
				$flag = false;
				continue;
			}

			$currentItemError = array();
			$invoice_description = isset($data['A']) ? $data['A'] : '';
			if ($invoice_description != '' && strtoupper($invoice_description) === 'INTER-STATE SUPPLIES TO REGISTERED PERSONS') {
				$dataArray['sply_ty'] = "INTRB2B";
			} else if ($invoice_description != '' && strtoupper($invoice_description) === 'INTRA-STATE SUPPLIES TO REGISTERED PERSONS') {
				$dataArray['sply_ty'] = "INTRAB2B";
			} else if ($invoice_description != '' && strtoupper($invoice_description) === 'INTER-STATE SUPPLIES TO UNREGISTERED PERSONS') {
				$dataArray['sply_ty'] = "INTRB2C";
			} else if ($invoice_description != '' && strtoupper($invoice_description) === 'INTRA-STATE SUPPLIES TO UNREGISTERED PERSONS') {
				$dataArray['sply_ty'] = "INTRAB2C";
			} else {
				$errorflag = true;
				array_push($currentItemError, "Invalid Supply Type.");
			}

			$nil_rated_supplies = isset($data['B']) ? $data['B'] : '';
			if(is_numeric($nil_rated_supplies) && $nil_rated_supplies >= 0) {
				$dataArray['nil_amt'] = number_format($nil_rated_supplies, 2, '.', '');
			} else {
				$dataArray['nil_amt'] = 0.00;
			}

			$exempted_supplies = isset($data['C']) ? $data['C'] : '';
			if(is_numeric($exempted_supplies) && $exempted_supplies >= 0) {
				$dataArray['expt_amt'] = number_format($exempted_supplies, 2, '.', '');
			} else {
				$dataArray['expt_amt'] = 0.00;
			}

			$non_gst_supplies = isset($data['D']) ? $data['D'] : '';
			if(is_numeric($non_gst_supplies) && $non_gst_supplies >= 0) {
				$dataArray['ngsup_amt'] = number_format($non_gst_supplies, 2, '.', '');
			} else {
				$dataArray['ngsup_amt'] = 0.00;
			}

			$invoiceErrors = $this->validateClientTallyInvoice($dataArray);
			if ($invoiceErrors !== true || !empty($currentItemError)) {

				$errorflag = true;
				if ($invoiceErrors === true) {
					$invoiceErrors = array();
				}

				$invoiceErrors = array_merge($invoiceErrors, $currentItemError);
				$invoiceErrors = implode(", ", $invoiceErrors);
				$sheet->SetCellValue('E' . $rowKey, $invoiceErrors);
			} else {

				$singleInvoiceArray = array(
					"sply_ty" => $dataArray['sply_ty'],
					"nil_amt" => $dataArray['nil_amt'],
					"ngsup_amt" => $dataArray['ngsup_amt'],
					"expt_amt" => $dataArray['expt_amt']
				);

				array_push($invoiceArray, $singleInvoiceArray);
			}

			/* set format of invoice amount cell(B,C,D) of excel */
			$sheet->getStyle('B' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
			$sheet->getStyle('C' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
			$sheet->getStyle('D' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
		}

		if ($errorflag === true) {

			$sheet->SetCellValue('E4', "Error Information");

			/* set stylesheet of excel */
			$sheet->getStyle('A4:E4')->getFill('A4:E4')->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('F8CBAD');
			$sheet->getStyle('A4:E4')->getFont()->setName('Times New Roman')->setSize(11)->setBold(false);
			$sheet->getStyle('A4:E4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A4:E4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			for($col = 'A'; $col <= 'E'; $col++) {
				$sheet->getColumnDimension($col)->setAutoSize(true);
			}

			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			$objWriter->save($invoice_excel_dir_path);
			$resultArray = array("status" => "error");
			return json_encode($resultArray);
		} else {

			/* set stylesheet of excel */
			$sheet->getStyle('A4:D4')->getFill('A4:D4')->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('F8CBAD');
			$sheet->getStyle('A4:D4')->getFont()->setName('Times New Roman')->setSize(11)->setBold(false);
			$sheet->getStyle('A4:D4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A4:D4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			for($col = 'A'; $col <= 'D'; $col++) {
				$sheet->getColumnDimension($col)->setAutoSize(true);
			}

			$resultArray = array("status" => "success", "invoiceEXEMPArray" => $invoiceArray);
			return json_encode($resultArray);
		}
	}

	final public function uploadClientB2BInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path, $return_period) {

		$flag = true;
		$errorflag = false;
		$dataArray = array();
		$indexArray = array();
		$invoiceArray = array();
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
				$dataArray['invoice_date'] = gmdate("d-m-Y", $UNIX_DATE);

				$invoice_return_period = (string)gmdate("Y-m", $UNIX_DATE);
				if($return_period !== $invoice_return_period) {
					$errorflag = true;
					array_push($currentItemError, "Invoice month should be same as submitted return period month.");
				}
			} else {
				$errorflag = true;
				array_push($currentItemError, "Invalid Invoice Date.");
			}

			$invoice_value = isset($data['D']) ? $data['D'] : '';
			if(is_numeric($invoice_value) && $invoice_value >= 0) {
				$dataArray['invoice_value'] = number_format($invoice_value, 2, '.', '');
			} else {
				$dataArray['invoice_value'] = 0.00;
			}

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

			$invoice_rate = isset($data['I']) ? $data['I'] : '';
			if(is_numeric($invoice_rate) && $invoice_rate >= 0) {
				$dataArray['rate'] = number_format($invoice_rate, 2, '.', '');
			} else {
				$dataArray['rate'] = 0.00;
			}

			$taxable_value = isset($data['J']) ? $data['J'] : '';
			if(is_numeric($taxable_value) && $taxable_value >= 0) {
				$dataArray['taxable_value'] = number_format($taxable_value, 2, '.', '');
			} else {
				$dataArray['taxable_value'] = 0.00;
			}

			$cess_amount = isset($data['K']) ? $data['K'] : '';
			if(is_numeric($cess_amount) && $cess_amount >= 0) {
				$dataArray['cess_amount'] = number_format($cess_amount, 2, '.', '');
			} else {
				$dataArray['cess_amount'] = 0.00;
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
				$sheet->SetCellValue('L' . $rowKey, $invoiceErrors);
			} else {

				$singleInvoiceArray = array(
					"invoice_nature" => "b2b",
					"recipient_gstin" => $dataArray['recipient_gstin'],
					"invoice_number" => $dataArray['invoice_number'],
					"invoice_date" => $dataArray['invoice_date'],
					"invoice_value" => $dataArray['invoice_value'],
					"place_of_supply" => $dataArray['place_of_supply'],
					"reverse_charge" => $dataArray['reverse_charge'],
					"invoice_type" => $dataArray['invoice_type'],
					"ecommerce_gstin_number" => $dataArray['ecommerce_gstin_number'],
					"rate" => $dataArray['rate'],
					"taxable_value" => $dataArray['taxable_value'],
					"cess_amount" => $dataArray['cess_amount'],
					"return_period" => $return_period,
					"financial_year" => $this->generateFinancialYear(),
					"created_from" => "E",
					"status" => 1,
					"added_by" => $this->sanitize($_SESSION['user_detail']['user_id']),
					"added_date" => date('Y-m-d H:i:s')
				);

				array_push($invoiceArray, $singleInvoiceArray);
			}
			
			/* set format of invoice date cell(C) of excel */
			$sheet->getStyle('C' . $rowKey)->getNumberFormat()->setFormatCode('dd-mmm-yy');
			$sheet->getStyle('D' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
			$sheet->getStyle('I' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
			$sheet->getStyle('J' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
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
			$resultArray = array("status" => "error");
			return json_encode($resultArray);
		} else {

			/* set stylesheet of excel */
			$sheet->getStyle('A4:K4')->getFill('A4:K4')->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('F8CBAD');
			$sheet->getStyle('A4:K4')->getFont()->setName('Times New Roman')->setSize(11)->setBold(false);
			$sheet->getStyle('A4:K4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A4:K4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			for($col = 'A'; $col <= 'K'; $col++) {
				$sheet->getColumnDimension($col)->setAutoSize(true);
			}
			
			$resultArray = array("status" => "success", "invoiceB2BArray" => $invoiceArray);
			return json_encode($resultArray);
		}
	}

	final public function uploadClientB2CLInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path, $return_period) {

		$flag = true;
		$errorflag = false;
		$dataArray = array();
		$indexArray = array();
		$invoiceArray = array();
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
				$dataArray['invoice_date'] = gmdate("d-m-Y", $UNIX_DATE);
				
				$invoice_return_period = (string)gmdate("Y-m", $UNIX_DATE);
				if($return_period !== $invoice_return_period) {
					$errorflag = true;
					array_push($currentItemError, "Invoice month should be same as submitted return period month.");
				}
			} else {
				$errorflag = true;
				array_push($currentItemError, "Invalid Invoice Date.");
			}

			$invoice_value = isset($data['C']) ? $data['C'] : '';
			if(is_numeric($invoice_value) && $invoice_value >= 0) {
				$dataArray['invoice_value'] = number_format($invoice_value, 2, '.', '');
			} else {
				$dataArray['invoice_value'] = 0.00;
			}

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

			$invoice_rate = isset($data['E']) ? $data['E'] : '';
			if(is_numeric($invoice_rate) && $invoice_rate >= 0) {
				$dataArray['rate'] = number_format($invoice_rate, 2, '.', '');
			} else {
				$dataArray['rate'] = 0.00;
			}

			$taxable_value = isset($data['F']) ? $data['F'] : '';
			if(is_numeric($taxable_value) && $taxable_value >= 0) {
				$dataArray['taxable_value'] = number_format($taxable_value, 2, '.', '');
			} else {
				$dataArray['taxable_value'] = 0.00;
			}

			$cess_amount = isset($data['G']) ? $data['G'] : '';
			if(is_numeric($cess_amount) && $cess_amount >= 0) {
				$dataArray['cess_amount'] = number_format($cess_amount, 2, '.', '');
			} else {
				$dataArray['cess_amount'] = 0.00;
			}

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
			} else {

				$singleInvoiceArray = array(
					"invoice_nature" => "b2cl",
					"invoice_number" => $dataArray['invoice_number'],
					"invoice_date" => $dataArray['invoice_date'],
					"invoice_value" => $dataArray['invoice_value'],
					"place_of_supply" => $dataArray['place_of_supply'],
					"rate" => $dataArray['rate'],
					"taxable_value" => $dataArray['taxable_value'],
					"cess_amount" => $dataArray['cess_amount'],
					"ecommerce_gstin_number" => $dataArray['ecommerce_gstin_number'],
					"return_period" => $return_period,
					"financial_year" => $this->generateFinancialYear(),
					"created_from" => "E",
					"status" => 1,
					"added_by" => $this->sanitize($_SESSION['user_detail']['user_id']),
					"added_date" => date('Y-m-d H:i:s')
				);

				array_push($invoiceArray, $singleInvoiceArray);
			}
			
			/* set format of invoice date cell(B) of excel */
			$sheet->getStyle('B' . $rowKey)->getNumberFormat()->setFormatCode('dd-mmm-yy');
			$sheet->getStyle('C' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
			$sheet->getStyle('E' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
			$sheet->getStyle('F' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
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
			$resultArray = array("status" => "error");
			return json_encode($resultArray);
		} else {

			/* set stylesheet of excel */
			$sheet->getStyle('A4:H4')->getFill('A4:H4')->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('F8CBAD');
			$sheet->getStyle('A4:H4')->getFont()->setName('Times New Roman')->setSize(11)->setBold(false);
			$sheet->getStyle('A4:H4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A4:H4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			for($col = 'A'; $col <= 'H'; $col++) {
				$sheet->getColumnDimension($col)->setAutoSize(true);
			}

			$resultArray = array("status" => "success", "invoiceB2CLArray" => $invoiceArray);
			return json_encode($resultArray);
		}
	}

	final public function uploadClientB2CSInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path, $return_period) {

		$flag = true;
		$errorflag = false;
		$dataArray = array();
		$indexArray = array();
		$invoiceArray = array();
		$currentFinancialYear = $this->generateFinancialYear();
		$sheet = $objPHPExcel->getSheetByName("b2cs");

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

			$invoice_rate = isset($data['C']) ? $data['C'] : '';
			if(is_numeric($invoice_rate) && $invoice_rate >= 0) {
				$dataArray['rate'] = number_format($invoice_rate, 2, '.', '');
			} else {
				$dataArray['rate'] = 0.00;
			}

			$taxable_value = isset($data['D']) ? $data['D'] : '';
			if(is_numeric($taxable_value) && $taxable_value >= 0) {
				$dataArray['taxable_value'] = number_format($taxable_value, 2, '.', '');
			} else {
				$dataArray['taxable_value'] = 0.00;
			}

			$cess_amount = isset($data['E']) ? $data['E'] : '';
			if(is_numeric($cess_amount) && $cess_amount >= 0) {
				$dataArray['cess_amount'] = number_format($cess_amount, 2, '.', '');
			} else {
				$dataArray['cess_amount'] = 0.00;
			}

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

				$singleInvoiceArray = array(
					"invoice_nature" => "b2cs",
					"type" => $dataArray['type'],
					"place_of_supply" => $dataArray['place_of_supply'],
					"rate" => $dataArray['rate'],
					"taxable_value" => $dataArray['taxable_value'],
					"cess_amount" => $dataArray['cess_amount'],
					"ecommerce_gstin_number" => $dataArray['ecommerce_gstin_number'],
					"return_period" => $return_period,
					"financial_year" => $this->generateFinancialYear(),
					"created_from" => "E",
					"status" => 1,
					"added_by" => $this->sanitize($_SESSION['user_detail']['user_id']),
					"added_date" => date('Y-m-d H:i:s')
				);

				array_push($invoiceArray, $singleInvoiceArray);
			}
			
			/* set format of invoice rate cell(C) of excel */
			$sheet->getStyle('C' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
			$sheet->getStyle('D' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
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
			$resultArray = array("status" => "error");
			return json_encode($resultArray);
		} else {

			/* set stylesheet of excel */
			$sheet->getStyle('A4:F4')->getFill('A4:F4')->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('F8CBAD');
			$sheet->getStyle('A4:F4')->getFont()->setName('Times New Roman')->setSize(11)->setBold(false);
			$sheet->getStyle('A4:F4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A4:F4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			for($col = 'A'; $col <= 'F'; $col++) {
				$sheet->getColumnDimension($col)->setAutoSize(true);
			}

			$resultArray = array("status" => "success", "invoiceB2CSArray" => $invoiceArray);
			return json_encode($resultArray);
		}
	}

	final public function uploadClientCDNRInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path, $return_period) {

		$flag = true;
		$errorflag = false;
		$dataArray = array();
		$indexArray = array();
		$invoiceArray = array();
		$currentFinancialYear = $this->generateFinancialYear();
		$sheet = $objPHPExcel->getSheetByName("cdnr");

		foreach($sheetData as $rowKey => $data) {

			if ($flag) {
				$indexArray = $data;
				$flag = false;
				continue;
			}

			$currentItemError = array();
			$dataArray['recipient_gstin'] = isset($data['A']) ? $data['A'] : '';
			
			$dataArray['original_invoice_number'] = isset($data['B']) ? $data['B'] : '';

			$original_invoice_date = isset($data['C']) ? (int)$data['C'] : '';
			if(is_numeric($original_invoice_date) && $original_invoice_date > 25569) {
				$UNIX_DATE = ($original_invoice_date - 25569) * 86400;
				$dataArray['original_invoice_date'] = gmdate("d-m-Y", $UNIX_DATE);
			} else {
				$errorflag = true;
				array_push($currentItemError, "Invalid Original Invoice Date.");
			}

			$dataArray['invoice_number'] = isset($data['D']) ? $data['D'] : '';

			$invoice_date = isset($data['E']) ? (int)$data['E'] : '';
			if(is_numeric($invoice_date) && $invoice_date > 25569) {
				$UNIX_DATE = ($invoice_date - 25569) * 86400;
				$dataArray['invoice_date'] = gmdate("d-m-Y", $UNIX_DATE);

				$invoice_return_period = (string)gmdate("Y-m", $UNIX_DATE);
				if($return_period !== $invoice_return_period) {
					$errorflag = true;
					array_push($currentItemError, "Invoice month should be same as submitted return period month.");
				}
			} else {
				$errorflag = true;
				array_push($currentItemError, "Invalid Note/Refund Voucher Date.");
			}

			$document_type = isset($data['F']) ? $data['F'] : '';
			if ($document_type != '' && strtoupper($document_type) === 'C') {
				$dataArray['document_type'] = "C";
			} else if ($document_type != '' && strtoupper($document_type) === 'D') {
				$dataArray['document_type'] = "D";
			} else if ($document_type != '' && strtoupper($document_type) === 'R') {
				$dataArray['document_type'] = "R";
			} else {
				$errorflag = true;
				array_push($currentItemError, "Invalid Document Type.");
			}

			$reason_for_issuing_document = isset($data['G']) ? strtoupper($data['G']) : '';
			if ($reason_for_issuing_document != '' && $reason_for_issuing_document === '01-SALES RETURN') {
				$dataArray['reason_for_issuing_document'] = "01-Sales Return";
			} else if ($reason_for_issuing_document != '' && $reason_for_issuing_document === '02-POST SALE DISCOUNT') {
				$dataArray['reason_for_issuing_document'] = "02-Post Sale Discount";
			} else if ($reason_for_issuing_document != '' && $reason_for_issuing_document === '03-DEFICIENCY IN SERVICES') {
				$dataArray['reason_for_issuing_document'] = "03-Deficiency in services";
			} else if ($reason_for_issuing_document != '' && $reason_for_issuing_document === '04-CORRECTION IN INVOICE') {
				$dataArray['reason_for_issuing_document'] = "04-Correction in Invoice";
			} else if ($reason_for_issuing_document != '' && $reason_for_issuing_document === '05-CHANGE IN POS') {
				$dataArray['reason_for_issuing_document'] = "05-Change in POS";
			} else if ($reason_for_issuing_document != '' && $reason_for_issuing_document === '06-FINALIZATION OF PROVISIONAL ASSESSMENT') {
				$dataArray['reason_for_issuing_document'] = "06-Finalization of Provisional assessment";
			} else if ($reason_for_issuing_document != '' && $reason_for_issuing_document === '07-OTHERS') {
				$dataArray['reason_for_issuing_document'] = "07-Others";
			} else {
				$errorflag = true;
				array_push($currentItemError, "Invalid Issuing Reason.");
			}
			
			$place_of_supply = isset($data['H']) ? substr($data['H'], 0, 2) : '';
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

			$invoice_value = isset($data['I']) ? $data['I'] : '';
			if(is_numeric($invoice_value) && $invoice_value >= 0) {
				$dataArray['invoice_value'] = number_format($invoice_value, 2, '.', '');
			} else {
				$dataArray['invoice_value'] = 0.00;
			}

			$invoice_rate = isset($data['J']) ? $data['J'] : '';
			if(is_numeric($invoice_rate) && $invoice_rate >= 0) {
				$dataArray['rate'] = number_format($invoice_rate, 2, '.', '');
			} else {
				$dataArray['rate'] = 0.00;
			}

			$taxable_value = isset($data['K']) ? $data['K'] : '';
			if(is_numeric($taxable_value) && $taxable_value >= 0) {
				$dataArray['taxable_value'] = number_format($taxable_value, 2, '.', '');
			} else {
				$dataArray['taxable_value'] = 0.00;
			}

			$cess_amount = isset($data['L']) ? $data['L'] : '';
			if(is_numeric($cess_amount) && $cess_amount >= 0) {
				$dataArray['cess_amount'] = number_format($cess_amount, 2, '.', '');
			} else {
				$dataArray['cess_amount'] = 0.00;
			}

			$pre_gst = isset($data['M']) ? $data['M'] : '';
			if ($pre_gst != '' && strtoupper($pre_gst) === 'Y') {
				$dataArray['pre_gst'] = 'Y';
			} else if ($pre_gst != '' && strtoupper($pre_gst) === 'N') {
				$dataArray['pre_gst'] = 'N';
			} else {
				$errorflag = true;
				array_push($currentItemError, "Invalid Pre GST.");
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
				$sheet->SetCellValue('N' . $rowKey, $invoiceErrors);
			} else {

				$singleInvoiceArray = array(
					"invoice_nature" => "cdnr",
					"recipient_gstin" => $dataArray['recipient_gstin'],
					"invoice_number" => $dataArray['invoice_number'],
					"invoice_date" => $dataArray['invoice_date'],
					"original_invoice_number" => $dataArray['original_invoice_number'],
					"original_invoice_date" => $dataArray['original_invoice_date'],
					"document_type" => $dataArray['document_type'],
					"reason_for_issuing_document" => $dataArray['reason_for_issuing_document'],
					"place_of_supply" => $dataArray['place_of_supply'],
					"invoice_value" => $dataArray['invoice_value'],
					"rate" => $dataArray['rate'],
					"taxable_value" => $dataArray['taxable_value'],
					"cess_amount" => $dataArray['cess_amount'],
					"pre_gst" => $dataArray['pre_gst'],
					"return_period" => $return_period,
					"financial_year" => $this->generateFinancialYear(),
					"created_from" => "E",
					"status" => 1,
					"added_by" => $this->sanitize($_SESSION['user_detail']['user_id']),
					"added_date" => date('Y-m-d H:i:s')
				);

				array_push($invoiceArray, $singleInvoiceArray);
			}

			/* set format of invoice date cell(C) of excel */
			$sheet->getStyle('C' . $rowKey)->getNumberFormat()->setFormatCode('dd-mmm-yy');
			$sheet->getStyle('E' . $rowKey)->getNumberFormat()->setFormatCode('dd-mmm-yy');
			$sheet->getStyle('I' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
			$sheet->getStyle('J' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
			$sheet->getStyle('K' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
			$sheet->getStyle('L' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
		}

		if ($errorflag === true) {

			$sheet->SetCellValue('N4', "Error Information");

			/* set stylesheet of excel */
			$sheet->getStyle('A4:N4')->getFill('A4:N4')->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('F8CBAD');
			$sheet->getStyle('A4:N4')->getFont()->setName('Times New Roman')->setSize(11)->setBold(false);
			$sheet->getStyle('A4:N4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A4:N4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			for($col = 'A'; $col <= 'N'; $col++) {
				$sheet->getColumnDimension($col)->setAutoSize(true);
			}

			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			$objWriter->save($invoice_excel_dir_path);
			$resultArray = array("status" => "error");
			return json_encode($resultArray);
		} else {

			/* set stylesheet of excel */
			$sheet->getStyle('A4:M4')->getFill('A4:M4')->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('F8CBAD');
			$sheet->getStyle('A4:M4')->getFont()->setName('Times New Roman')->setSize(11)->setBold(false);
			$sheet->getStyle('A4:M4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A4:M4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			for($col = 'A'; $col <= 'M'; $col++) {
				$sheet->getColumnDimension($col)->setAutoSize(true);
			}
			
			$resultArray = array("status" => "success", "invoiceCDNRArray" => $invoiceArray);
			return json_encode($resultArray);
		}
	}

	final public function uploadClientCDNURInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path, $return_period) {

		$flag = true;
		$errorflag = false;
		$dataArray = array();
		$indexArray = array();
		$invoiceArray = array();
		$currentFinancialYear = $this->generateFinancialYear();
		$sheet = $objPHPExcel->getSheetByName("cdnur");

		foreach($sheetData as $rowKey => $data) {

			if ($flag) {
				$indexArray = $data;
				$flag = false;
				continue;
			}

			$currentItemError = array();
			$ur_type = isset($data['A']) ? $data['A'] : '';
			if ($ur_type != '' && strtoupper($ur_type) === 'EXPWP') {
				$dataArray['ur_type'] = "EXPWP";
			} else if ($ur_type != '' && strtoupper($ur_type) === 'EXPWOP') {
				$dataArray['ur_type'] = "EXPWOP";
			} else if ($ur_type != '' && strtoupper($ur_type) === 'B2CL') {
				$dataArray['ur_type'] = "B2CL";
			} else {
				$errorflag = true;
				array_push($currentItemError, "Invalid UR Type.");
			}

			$dataArray['invoice_number'] = isset($data['B']) ? $data['B'] : '';

			$invoice_date = isset($data['C']) ? (int)$data['C'] : '';
			if(is_numeric($invoice_date) && $invoice_date > 25569) {
				$UNIX_DATE = ($invoice_date - 25569) * 86400;
				$dataArray['invoice_date'] = gmdate("d-m-Y", $UNIX_DATE);

				$invoice_return_period = (string)gmdate("Y-m", $UNIX_DATE);
				if($return_period !== $invoice_return_period) {
					$errorflag = true;
					array_push($currentItemError, "Invoice month should be same as submitted return period month.");
				}
			} else {
				$errorflag = true;
				array_push($currentItemError, "Invalid Invoice Date.");
			}

			$document_type = isset($data['D']) ? $data['D'] : '';
			if ($document_type != '' && strtoupper($document_type) === 'C') {
				$dataArray['document_type'] = "C";
			} else if ($document_type != '' && strtoupper($document_type) === 'D') {
				$dataArray['document_type'] = "D";
			} else if ($document_type != '' && strtoupper($document_type) === 'R') {
				$dataArray['document_type'] = "R";
			} else {
				$errorflag = true;
				array_push($currentItemError, "Invalid Document Type.");
			}

			$dataArray['original_invoice_number'] = isset($data['E']) ? $data['E'] : '';

			$original_invoice_date = isset($data['F']) ? (int)$data['F'] : '';
			if(is_numeric($original_invoice_date) && $original_invoice_date > 25569) {
				$UNIX_DATE = ($original_invoice_date - 25569) * 86400;
				$dataArray['original_invoice_date'] = gmdate("d-m-Y", $UNIX_DATE);
			} else {
				$errorflag = true;
				array_push($currentItemError, "Invalid Note/Refund Voucher Date.");
			}

			$reason_for_issuing_document = isset($data['G']) ? strtoupper($data['G']) : '';
			if ($reason_for_issuing_document != '' && $reason_for_issuing_document === '01-SALES RETURN') {
				$dataArray['reason_for_issuing_document'] = "01-Sales Return";
			} else if ($reason_for_issuing_document != '' && $reason_for_issuing_document === '02-POST SALE DISCOUNT') {
				$dataArray['reason_for_issuing_document'] = "02-Post Sale Discount";
			} else if ($reason_for_issuing_document != '' && $reason_for_issuing_document === '03-DEFICIENCY IN SERVICES') {
				$dataArray['reason_for_issuing_document'] = "03-Deficiency in services";
			} else if ($reason_for_issuing_document != '' && $reason_for_issuing_document === '04-CORRECTION IN INVOICE') {
				$dataArray['reason_for_issuing_document'] = "04-Correction in Invoice";
			} else if ($reason_for_issuing_document != '' && $reason_for_issuing_document === '05-CHANGE IN POS') {
				$dataArray['reason_for_issuing_document'] = "05-Change in POS";
			} else if ($reason_for_issuing_document != '' && $reason_for_issuing_document === '06-FINALIZATION OF PROVISIONAL ASSESSMENT') {
				$dataArray['reason_for_issuing_document'] = "06-Finalization of Provisional assessment";
			} else if ($reason_for_issuing_document != '' && $reason_for_issuing_document === '07-OTHERS') {
				$dataArray['reason_for_issuing_document'] = "07-Others";
			} else {
				$errorflag = true;
				array_push($currentItemError, "Invalid Issuing Reason.");
			}
			
			$place_of_supply = isset($data['H']) ? substr($data['H'], 0, 2) : '';
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

			$invoice_value = isset($data['I']) ? $data['I'] : '';
			if(is_numeric($invoice_value) && $invoice_value >= 0) {
				$dataArray['invoice_value'] = number_format($invoice_value, 2, '.', '');
			} else {
				$dataArray['invoice_value'] = 0.00;
			}

			$invoice_rate = isset($data['J']) ? $data['J'] : '';
			if(is_numeric($invoice_rate) && $invoice_rate >= 0) {
				$dataArray['rate'] = number_format($invoice_rate, 2, '.', '');
			} else {
				$dataArray['rate'] = 0.00;
			}

			$taxable_value = isset($data['K']) ? $data['K'] : '';
			if(is_numeric($taxable_value) && $taxable_value >= 0) {
				$dataArray['taxable_value'] = number_format($taxable_value, 2, '.', '');
			} else {
				$dataArray['taxable_value'] = 0.00;
			}

			$cess_amount = isset($data['L']) ? $data['L'] : '';
			if(is_numeric($cess_amount) && $cess_amount >= 0) {
				$dataArray['cess_amount'] = number_format($cess_amount, 2, '.', '');
			} else {
				$dataArray['cess_amount'] = 0.00;
			}

			$pre_gst = isset($data['M']) ? $data['M'] : '';
			if ($pre_gst != '' && strtoupper($pre_gst) === 'Y') {
				$dataArray['pre_gst'] = 'Y';
			} else if ($pre_gst != '' && strtoupper($pre_gst) === 'N') {
				$dataArray['pre_gst'] = 'N';
			} else {
				$errorflag = true;
				array_push($currentItemError, "Invalid Pre GST.");
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
				$sheet->SetCellValue('N' . $rowKey, $invoiceErrors);
			} else {

				$singleInvoiceArray = array(
					"invoice_nature" => "cdnur",
					"ur_type" => $dataArray['ur_type'],
					"invoice_number" => $dataArray['invoice_number'],
					"invoice_date" => $dataArray['invoice_date'],
					"document_type" => $dataArray['document_type'],
					"original_invoice_number" => $dataArray['original_invoice_number'],
					"original_invoice_date" => $dataArray['original_invoice_date'],
					"reason_for_issuing_document" => $dataArray['reason_for_issuing_document'],
					"place_of_supply" => $dataArray['place_of_supply'],
					"invoice_value" => $dataArray['invoice_value'],
					"rate" => $dataArray['rate'],
					"taxable_value" => $dataArray['taxable_value'],
					"cess_amount" => $dataArray['cess_amount'],
					"pre_gst" => $dataArray['pre_gst'],
					"return_period" => $return_period,
					"financial_year" => $this->generateFinancialYear(),
					"created_from" => "E",
					"status" => 1,
					"added_by" => $this->sanitize($_SESSION['user_detail']['user_id']),
					"added_date" => date('Y-m-d H:i:s')
				);

				array_push($invoiceArray, $singleInvoiceArray);
			}

			/* set format of invoice date cell(C) of excel */
			$sheet->getStyle('C' . $rowKey)->getNumberFormat()->setFormatCode('dd-mmm-yy');
			$sheet->getStyle('E' . $rowKey)->getNumberFormat()->setFormatCode('dd-mmm-yy');
			$sheet->getStyle('I' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
			$sheet->getStyle('J' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
			$sheet->getStyle('K' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
			$sheet->getStyle('L' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
		}

		if ($errorflag === true) {

			$sheet->SetCellValue('N4', "Error Information");

			/* set stylesheet of excel */
			$sheet->getStyle('A4:N4')->getFill('A4:N4')->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('F8CBAD');
			$sheet->getStyle('A4:N4')->getFont()->setName('Times New Roman')->setSize(11)->setBold(false);
			$sheet->getStyle('A4:N4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A4:N4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			for($col = 'A'; $col <= 'N'; $col++) {
				$sheet->getColumnDimension($col)->setAutoSize(true);
			}

			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			$objWriter->save($invoice_excel_dir_path);
			$resultArray = array("status" => "error");
			return json_encode($resultArray);
		} else {

			/* set stylesheet of excel */
			$sheet->getStyle('A4:M4')->getFill('A4:M4')->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('F8CBAD');
			$sheet->getStyle('A4:M4')->getFont()->setName('Times New Roman')->setSize(11)->setBold(false);
			$sheet->getStyle('A4:M4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A4:M4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			for($col = 'A'; $col <= 'M'; $col++) {
				$sheet->getColumnDimension($col)->setAutoSize(true);
			}
			
			$resultArray = array("status" => "success", "invoiceCDNURArray" => $invoiceArray);
			return json_encode($resultArray);
		}
	}

	final public function uploadClientEXPInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path, $return_period) {

		$flag = true;
		$errorflag = false;
		$dataArray = array();
		$indexArray = array();
		$invoiceArray = array();
		$currentFinancialYear = $this->generateFinancialYear();
		$sheet = $objPHPExcel->getSheetByName("exp");

		foreach($sheetData as $rowKey => $data) {

			if ($flag) {
				$indexArray = $data;
				$flag = false;
				continue;
			}

			$currentItemError = array();
			$invoice_type = isset($data['A']) ? $data['A'] : '';
			if ($invoice_type != '' && strtoupper($invoice_type) === 'WPAY') {
				$dataArray['invoice_type'] = "WPAY";
			} else if ($invoice_type != '' && strtoupper($invoice_type) === 'WOPAY') {
				$dataArray['invoice_type'] = "WOPAY";
			} else {
				$errorflag = true;
				array_push($currentItemError, "Invalid Invoice Type.");
			}

			$dataArray['invoice_number'] = isset($data['B']) ? $data['B'] : '';

			$invoice_date = isset($data['C']) ? (int)$data['C'] : '';
			if(is_numeric($invoice_date) && $invoice_date > 25569) {
				$UNIX_DATE = ($invoice_date - 25569) * 86400;
				$dataArray['invoice_date'] = gmdate("d-m-Y", $UNIX_DATE);

				$invoice_return_period = (string)gmdate("Y-m", $UNIX_DATE);
				if($return_period !== $invoice_return_period) {
					$errorflag = true;
					array_push($currentItemError, "Invoice month should be same as submitted return period month.");
				}
			} else {
				$errorflag = true;
				array_push($currentItemError, "Invalid Invoice Date.");
			}

			$invoice_value = isset($data['D']) ? $data['D'] : '';
			if(is_numeric($invoice_value) && $invoice_value >= 0) {
				$dataArray['invoice_value'] = number_format($invoice_value, 2, '.', '');
			} else {
				$dataArray['invoice_value'] = 0.00;
			}

			$dataArray['port_code'] = isset($data['E']) ? $data['E'] : '';
			$dataArray['shipping_bill_number'] = isset($data['F']) ? $data['F'] : '';

			$shipping_bill_date = isset($data['G']) ? $data['G'] : '';
			if(is_numeric($shipping_bill_date) && $shipping_bill_date > 25569) {
				$UNIX_DATE = ($shipping_bill_date - 25569) * 86400;
				$dataArray['shipping_bill_date'] = gmdate("d-m-Y", $UNIX_DATE);
			} else {
				$errorflag = true;
				array_push($currentItemError, "Invalid Shipping Bill Date.");
			}

			$invoice_rate = isset($data['H']) ? $data['H'] : '';
			if(is_numeric($invoice_rate) && $invoice_rate >= 0) {
				$dataArray['rate'] = number_format($invoice_rate, 2, '.', '');
			} else {
				$dataArray['rate'] = 0.00;
			}

			$taxable_value = isset($data['I']) ? $data['I'] : '';
			if(is_numeric($taxable_value) && $taxable_value >= 0) {
				$dataArray['taxable_value'] = number_format($taxable_value, 2, '.', '');
			} else {
				$dataArray['taxable_value'] = 0.00;
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
				$sheet->SetCellValue('J' . $rowKey, $invoiceErrors);
			} else {

				$singleInvoiceArray = array(
					"invoice_nature" => "exp",
					"invoice_type" => $dataArray['invoice_type'],
					"invoice_number" => $dataArray['invoice_number'],
					"invoice_date" => $dataArray['invoice_date'],
					"invoice_value" => $dataArray['invoice_value'],
					"port_code" => $dataArray['port_code'],
					"shipping_bill_number" => $dataArray['shipping_bill_number'],
					"shipping_bill_date" => $dataArray['shipping_bill_date'],
					"rate" => $dataArray['rate'],
					"taxable_value" => $dataArray['taxable_value'],
					"return_period" => $return_period,
					"financial_year" => $this->generateFinancialYear(),
					"created_from" => "E",
					"status" => 1,
					"added_by" => $this->sanitize($_SESSION['user_detail']['user_id']),
					"added_date" => date('Y-m-d H:i:s')
				);

				array_push($invoiceArray, $singleInvoiceArray);
			}
			
			/* set format of invoice date cell(C) of excel */
			$sheet->getStyle('C' . $rowKey)->getNumberFormat()->setFormatCode('dd-mmm-yy');
			$sheet->getStyle('D' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
			$sheet->getStyle('H' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
			$sheet->getStyle('I' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
		}

		if ($errorflag === true) {

			$sheet->SetCellValue('J4', "Error Information");

			/* set stylesheet of excel */
			$sheet->getStyle('A4:J4')->getFill('A4:J4')->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('F8CBAD');
			$sheet->getStyle('A4:J4')->getFont()->setName('Times New Roman')->setSize(11)->setBold(false);
			$sheet->getStyle('A4:J4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A4:J4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			for($col = 'A'; $col <= 'J'; $col++) {
				$sheet->getColumnDimension($col)->setAutoSize(true);
			}

			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			$objWriter->save($invoice_excel_dir_path);
			$resultArray = array("status" => "error");
			return json_encode($resultArray);
		} else {

			/* set stylesheet of excel */
			$sheet->getStyle('A4:I4')->getFill('A4:I4')->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('F8CBAD');
			$sheet->getStyle('A4:I4')->getFont()->setName('Times New Roman')->setSize(11)->setBold(false);
			$sheet->getStyle('A4:I4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A4:I4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			for($col = 'A'; $col <= 'I'; $col++) {
				$sheet->getColumnDimension($col)->setAutoSize(true);
			}

			$resultArray = array("status" => "success", "invoiceEXPArray" => $invoiceArray);
			return json_encode($resultArray);
		}
	}

	final public function uploadClientATInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path, $return_period) {

		$flag = true;
		$errorflag = false;
		$dataArray = array();
		$indexArray = array();
		$invoiceArray = array();
		$currentFinancialYear = $this->generateFinancialYear();
		$sheet = $objPHPExcel->getSheetByName("at");

		foreach($sheetData as $rowKey => $data) {

			if ($flag) {
				$indexArray = $data;
				$flag = false;
				continue;
			}

			$currentItemError = array();
			$place_of_supply = isset($data['A']) ? substr($data['A'], 0, 2) : '';
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

			$invoice_rate = isset($data['B']) ? $data['B'] : '';
			if(is_numeric($invoice_rate) && $invoice_rate >= 0) {
				$dataArray['rate'] = number_format($invoice_rate, 2, '.', '');
			} else {
				$dataArray['rate'] = 0.00;
			}

			$invoice_value = isset($data['C']) ? $data['C'] : '';
			if(is_numeric($invoice_value) && $invoice_value >= 0) {
				$dataArray['invoice_value'] = number_format($invoice_value, 2, '.', '');
			} else {
				$dataArray['invoice_value'] = 0.00;
			}

			$cess_amount = isset($data['D']) ? $data['D'] : '';
			if(is_numeric($cess_amount) && $cess_amount >= 0) {
				$dataArray['cess_amount'] = number_format($cess_amount, 2, '.', '');
			} else {
				$dataArray['cess_amount'] = 0.00;
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
				$sheet->SetCellValue('E' . $rowKey, $invoiceErrors);
			} else {

				$singleInvoiceArray = array(
					"invoice_nature" => "at",
					"place_of_supply" => $dataArray['place_of_supply'],
					"rate" => $dataArray['rate'],
					"invoice_value" => $dataArray['invoice_value'],
					"cess_amount" => $dataArray['cess_amount'],
					"return_period" => $return_period,
					"financial_year" => $this->generateFinancialYear(),
					"created_from" => "E",
					"status" => 1,
					"added_by" => $this->sanitize($_SESSION['user_detail']['user_id']),
					"added_date" => date('Y-m-d H:i:s')
				);

				array_push($invoiceArray, $singleInvoiceArray);
			}
			
			/* set format of invoice rate cell(B) of excel */
			$sheet->getStyle('B' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
			$sheet->getStyle('C' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
			$sheet->getStyle('D' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
		}

		if ($errorflag === true) {

			$sheet->SetCellValue('E4', "Error Information");

			/* set stylesheet of excel */
			$sheet->getStyle('A4:E4')->getFill('A4:E4')->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('F8CBAD');
			$sheet->getStyle('A4:E4')->getFont()->setName('Times New Roman')->setSize(11)->setBold(false);
			$sheet->getStyle('A4:E4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A4:E4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			for($col = 'A'; $col <= 'E'; $col++) {
				$sheet->getColumnDimension($col)->setAutoSize(true);
			}

			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			$objWriter->save($invoice_excel_dir_path);
			$resultArray = array("status" => "error");
			return json_encode($resultArray);
		} else {

			/* set stylesheet of excel */
			$sheet->getStyle('A4:D4')->getFill('A4:D4')->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('F8CBAD');
			$sheet->getStyle('A4:D4')->getFont()->setName('Times New Roman')->setSize(11)->setBold(false);
			$sheet->getStyle('A4:D4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A4:D4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			for($col = 'A'; $col <= 'D'; $col++) {
				$sheet->getColumnDimension($col)->setAutoSize(true);
			}

			$resultArray = array("status" => "success", "invoiceATArray" => $invoiceArray);
			return json_encode($resultArray);
		}
	}

	final public function uploadClientATADJInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path, $return_period) {

		$flag = true;
		$errorflag = false;
		$dataArray = array();
		$indexArray = array();
		$invoiceArray = array();
		$currentFinancialYear = $this->generateFinancialYear();
		$sheet = $objPHPExcel->getSheetByName("atadj");

		foreach($sheetData as $rowKey => $data) {

			if ($flag) {
				$indexArray = $data;
				$flag = false;
				continue;
			}

			$currentItemError = array();
			$place_of_supply = isset($data['A']) ? substr($data['A'], 0, 2) : '';
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

			$invoice_rate = isset($data['B']) ? $data['B'] : '';
			if(is_numeric($invoice_rate) && $invoice_rate >= 0) {
				$dataArray['rate'] = number_format($invoice_rate, 2, '.', '');
			} else {
				$dataArray['rate'] = 0.00;
			}

			$invoice_value = isset($data['C']) ? $data['C'] : '';
			if(is_numeric($invoice_value) && $invoice_value >= 0) {
				$dataArray['invoice_value'] = number_format($invoice_value, 2, '.', '');
			} else {
				$dataArray['invoice_value'] = 0.00;
			}

			$cess_amount = isset($data['D']) ? $data['D'] : '';
			if(is_numeric($cess_amount) && $cess_amount >= 0) {
				$dataArray['cess_amount'] = number_format($cess_amount, 2, '.', '');
			} else {
				$dataArray['cess_amount'] = 0.00;
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
				$sheet->SetCellValue('E' . $rowKey, $invoiceErrors);
			} else {

				$singleInvoiceArray = array(
					"invoice_nature" => "atadj",
					"place_of_supply" => $dataArray['place_of_supply'],
					"rate" => $dataArray['rate'],
					"invoice_value" => $dataArray['invoice_value'],
					"cess_amount" => $dataArray['cess_amount'],
					"return_period" => $return_period,
					"financial_year" => $this->generateFinancialYear(),
					"created_from" => "E",
					"status" => 1,
					"added_by" => $this->sanitize($_SESSION['user_detail']['user_id']),
					"added_date" => date('Y-m-d H:i:s')
				);

				array_push($invoiceArray, $singleInvoiceArray);
			}
			
			/* set format of invoice rate cell(B) of excel */
			$sheet->getStyle('B' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
			$sheet->getStyle('C' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
			$sheet->getStyle('D' . $rowKey)->getNumberFormat()->setFormatCode('0.00');
		}

		if ($errorflag === true) {

			$sheet->SetCellValue('E4', "Error Information");

			/* set stylesheet of excel */
			$sheet->getStyle('A4:E4')->getFill('A4:E4')->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('F8CBAD');
			$sheet->getStyle('A4:E4')->getFont()->setName('Times New Roman')->setSize(11)->setBold(false);
			$sheet->getStyle('A4:E4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A4:E4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			for($col = 'A'; $col <= 'E'; $col++) {
				$sheet->getColumnDimension($col)->setAutoSize(true);
			}

			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			$objWriter->save($invoice_excel_dir_path);
			$resultArray = array("status" => "error");
			return json_encode($resultArray);
		} else {

			/* set stylesheet of excel */
			$sheet->getStyle('A4:D4')->getFill('A4:D4')->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('F8CBAD');
			$sheet->getStyle('A4:D4')->getFont()->setName('Times New Roman')->setSize(11)->setBold(false);
			$sheet->getStyle('A4:D4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A4:D4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			for($col = 'A'; $col <= 'D'; $col++) {
				$sheet->getColumnDimension($col)->setAutoSize(true);
			}

			$resultArray = array("status" => "success", "invoiceATADJArray" => $invoiceArray);
			return json_encode($resultArray);
		}
	}

	final public function validateClientTallyInvoice($dataArr) {

		if (array_key_exists("recipient_gstin", $dataArr)) {
			$rules['recipient_gstin'] = 'required||pattern:/^' . $this->validateType['gstinnumber'] . '+$/||min:15||max:15|#|lable_name:Recipient GSTIN Number';
		}

		if (array_key_exists("invoice_number", $dataArr)) {
			$rules['invoice_number'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/||min:1||max:16|#|lable_name:Invoice Number';
		}

		if (array_key_exists("original_invoice_number", $dataArr)) {
			$rules['original_invoice_number'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/||min:1||max:16|#|lable_name:Note/Refund Voucher Number';
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

		if (array_key_exists("port_code", $dataArr)) {
			$rules['port_code'] = 'pattern:/^[' . $this->validateType['content'] . ']+$/||min:1||max:6|#|lable_name:Port Code';
		}

		if (array_key_exists("shipping_bill_number", $dataArr)) {
			$rules['shipping_bill_number'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/||min:1||max:7|#|lable_name:Shipping Bill Number';
		}

		if (array_key_exists("nil_amt", $dataArr)) {
			$rules['nil_amt'] = 'required||numeric||decimalzero|#|lable_name:Invoice Nil Amount';
		}

		if (array_key_exists("expt_amt", $dataArr)) {
			$rules['expt_amt'] = 'required||numeric||decimalzero|#|lable_name:Invoice Exempted Amount';
		}

		if (array_key_exists("ngsup_amt", $dataArr)) {
			$rules['ngsup_amt'] = 'required||numeric||decimalzero|#|lable_name:Invoice Non-GST Amount';
		}

		if (array_key_exists("hsn", $dataArr)) {
			$rules['hsn'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/||min:1||max:10|#|lable_name:HSN Code';
		}

		if (array_key_exists("description", $dataArr)) {
			$rules['description'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/||min:1||max:30|#|lable_name:Goods/Service Description';
		}

		if (array_key_exists("unit", $dataArr)) {
			$rules['unit'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/||min:1||max:30|#|lable_name:Goods/Service Unit';
		}

		if (array_key_exists("qty", $dataArr)) {
			$rules['qty'] = 'required||numeric||decimal|#|lable_name:Quantity of HSN';
		}

		if (array_key_exists("invoice_total_value", $dataArr)) {
			$rules['invoice_total_value'] = 'required||numeric||decimalzero|#|lable_name:HSN Total Amount';
		}

		if (array_key_exists("taxable_subtotal", $dataArr)) {
			$rules['taxable_subtotal'] = 'required||numeric||decimalzero|#|lable_name:HSN Taxable Amount';
		}

		if (array_key_exists("igst", $dataArr)) {
			$rules['igst'] = 'required||numeric||decimalzero|#|lable_name:HSN IGST Amount';
		}

		if (array_key_exists("cgst", $dataArr)) {
			$rules['cgst'] = 'required||numeric||decimalzero|#|lable_name:HSN CGST Amount';
		}

		if (array_key_exists("sgst", $dataArr)) {
			$rules['sgst'] = 'required||numeric||decimalzero|#|lable_name:HSN SGST Amount';
		}

		if (array_key_exists("cess", $dataArr)) {
			$rules['cess'] = 'required||numeric||decimalzero|#|lable_name:HSN CESS Amount';
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