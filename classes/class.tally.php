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
		
		/* get current user data */
		$dataCurrentUserArray = $this->getUserDetailsById( $this->sanitize($_SESSION['user_detail']['user_id']) );
		
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
		$currentUserStateTin = $dataCurrentUserArray['data']->kyc->state_tin;
		
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
							$resultB2B = $this->uploadClientB2BInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path, $return_period, $currentUserStateTin);
							break;
						case "b2cl":
							$resultB2CL = $this->uploadClientB2CLInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path, $return_period, $currentUserStateTin);
							break;
						case "b2cs":
							$resultB2CS = $this->uploadClientB2CSInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path, $return_period, $currentUserStateTin);
							break;
						case "cdnr":
							$resultCDNR = $this->uploadClientCDNRInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path, $return_period, $currentUserStateTin);
							break;
						case "cdnur":
							$resultCDNUR = $this->uploadClientCDNURInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path, $return_period, $currentUserStateTin);
							break;
						case "exp":
							$resultEXP = $this->uploadClientEXPInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path, $return_period, $currentUserStateTin);
							break;
						case "at":
							$resultAT = $this->uploadClientATInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path, $return_period, $currentUserStateTin);
							break;
						case "atadj":
							$resultATADJ = $this->uploadClientATADJInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path, $return_period, $currentUserStateTin);
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
				die;
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
			$resultDOCSArray = json_decode($resultDOCS);

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
				$resultHSNArray->status === "success" && 
				$resultDOCSArray->status === "success"
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

				$dataDOCSConditionArray['financial_month'] = $return_period;
				$dataDOCSConditionArray['added_by'] = $this->sanitize($_SESSION['user_detail']['user_id']);
				$dataDOCSConditionArray['type'] = "gstr1document";
				$this->deletData($this->tableNames['return_upload_summary'], $dataDOCSConditionArray);
				$this->logMsg("Tally DOCS deleted for return period : " . $return_period . " by User ID : " . $_SESSION['user_detail']['user_id'] . ".","gstr1_docs_deleted");

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
				
				if(count($resultDOCSArray->invoiceDOCSArray) > 0 && !empty($resultDOCSArray->invoiceDOCSArray)) {

					$dataDOCSArray['return_data'] = base64_encode(json_encode($this->objectToArray($resultDOCSArray->invoiceDOCSArray)));
					$dataDOCSArray['type'] = "gstr1document";
					$dataDOCSArray['financial_month'] = $return_period;
					$dataDOCSArray['added_by'] = $this->sanitize($_SESSION['user_detail']['user_id']);
					$dataDOCSArray['added_date'] = date('Y-m-d H:i:s');
					$this->insert($this->tableNames['return_upload_summary'], $dataDOCSArray);
					$this->logMsg("Tally DOCS invoice inserted for return period : " . $return_period . " by User ID : " . $_SESSION['user_detail']['user_id'] . ".","gstr1_docs_inserted");
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
	
	final public function docsKeyFromValue($docsName) {

		$docsTitle = strtoupper($docsName);
		$docsKey = "0";

		switch ($docsTitle) {
			case "INVOICE FOR OUTWARD SUPPLY":
				$docsKey = "1";
				break;
			case "INVOICES FOR OUTWARD SUPPLY":
				$docsKey = "1";
				break;
			case "INVOICE FOR INWARD SUPPLY FROM UNREGISTERED PERSON":
				$docsKey = "2";
				break;
			case "INVOICES FOR INWARD SUPPLY FROM UNREGISTERED PERSON":
				$docsKey = "2";
				break;
			case "REVISED INVOICE":
				$docsKey = "3";
				break;
			case "DEBIT NOTE":
				$docsKey = "4";
				break;
			case "CREDIT NOTE":
				$docsKey = "5";
				break;
			case "RECEIPT VOUCHER":
				$docsKey = "6";
				break;
			case "PAYMENT VOUCHER":
				$docsKey = "7";
				break;
			case "REFUND VOUCHER":
				$docsKey = "8";
				break;
			case "DELIVERY CHALLAN FOR JOB WORK":
				$docsKey = "9";
				break;
			case "DELIVERY CHALLAN FOR SUPPLY ON APPROVAL":
				$docsKey = "10";
				break;
			case "DELIVERY CHALLAN IN CASE OF LIQUID GAS":
				$docsKey = "11";
				break;
			case "DELIVERY CHALLAN IN CASES OF LIQUID GAS":
				$docsKey = "11";
				break;
			case "DELIVERY CHALLAN IN CASE OTHER THAN BY WAY OF SUPPLY (EXCLUDING AT S NO. 9 TO 11)":
				$docsKey = "12";
				break;
			case "DELIVERY CHALLAN IN CASES OTHER THAN BY WAY OF SUPPLY (EXCLUDING AT S NO. 9 TO 11)":
				$docsKey = "12";
				break;
		}
		
		return $docsKey;
	}

	final public function uploadClientDOCSInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path) {

		$flag = true;
		$errorflag = false;
		$dataArray = array();
		$indexArray = array();
		$invoiceArray = array();
		$currentFinancialYear = $this->generateFinancialYear();
		$sheet = $objPHPExcel->getSheetByName("docs");

		foreach($sheetData as $rowKey => $data) {

			if ($flag) {
				$indexArray = $data;
				$flag = false;
				continue;
			}

			$currentItemError = array();
			$docsname = isset($data['A']) ? $data['A'] : '';
			$dataArray['num'] = $this->docsKeyFromValue($docsname);
			$dataArray['from'] = isset($data['B']) ? $data['B'] : '';
			$dataArray['to'] = isset($data['C']) ? $data['C'] : '';
			$dataArray['totnum'] = isset($data['D']) ? $data['D'] : 0;
			$dataArray['cancel'] = isset($data['E']) ? $data['E'] : 0;
			$dataArray['net_issue'] = $dataArray['totnum'] - $dataArray['cancel'];

			if(in_array($dataArray['num'], $this->validateDOCSSerialNumber) == false) {
				$errorflag = true;
				array_push($currentItemError, "Nature of document should be valid.");
			}

			$invoiceErrors = $this->validateClientTallyInvoice($dataArray);
			if ($invoiceErrors !== true || !empty($currentItemError)) {

				$errorflag = true;
				if ($invoiceErrors === true) {
					$invoiceErrors = array();
				}

				$invoiceErrors = array_merge($invoiceErrors, $currentItemError);
				$invoiceErrors = implode(", ", $invoiceErrors);
				$sheet->SetCellValue('F' . $rowKey, $invoiceErrors);
			} else {

				$singleInvoiceArray = array(
					"num" => $dataArray['num'],
					"from" => $dataArray['from'],
					"to" => $dataArray['to'],
					"totnum" => $dataArray['totnum'],
					"cancel" => $dataArray['cancel'],
					"net_issue" => $dataArray['net_issue']
				);
				
				$docs_num = $dataArray['num'];
				$invoiceArray['doc_num' . $docs_num][0] = $singleInvoiceArray;
			}

			/* set format of number cell(D,E) of excel */
			$sheet->getStyle('D' . $rowKey)->getNumberFormat()->setFormatCode('0');
			$sheet->getStyle('E' . $rowKey)->getNumberFormat()->setFormatCode('0');
		}

		if ($errorflag === true) {

			$sheet->SetCellValue('F4', "Error Information");

			/* set stylesheet of excel */
			$sheet->getStyle('A4:F4')->getFill('A4:F4')->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('F8CBAD');
			$sheet->getStyle('A4:F4')->getFont()->setName('Times New Roman')->setSize(11)->setBold(false);
			$sheet->getStyle('A4:F4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A4:F4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			for($col = 'A'; $col <= 'F'; $col++) {
				$sheet->getColumnDimension($col)->setAutoSize(true);
			}

			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			$objWriter->save($invoice_excel_dir_path);
			$resultArray = array("status" => "error");
			return json_encode($resultArray);
		} else {

			/* set stylesheet of excel */
			$sheet->getStyle('A4:E4')->getFill('A4:E4')->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('F8CBAD');
			$sheet->getStyle('A4:E4')->getFont()->setName('Times New Roman')->setSize(11)->setBold(false);
			$sheet->getStyle('A4:E4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A4:E4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			for($col = 'A'; $col <= 'E'; $col++) {
				$sheet->getColumnDimension($col)->setAutoSize(true);
			}

			$resultArray = array("status" => "success", "invoiceDOCSArray" => $invoiceArray);
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

	final public function uploadClientB2BInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path, $return_period, $currentUserStateTin) {

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

				if($dataArray['place_of_supply'] == $currentUserStateTin && $dataArray['invoice_type'] == "R") {

					$supply_type = "INTRA";
					$rateTaxAmount = ($dataArray['rate']/100) * $dataArray['taxable_value'];
					$cgst_amount = number_format(($rateTaxAmount/2), 2, '.', '');
					$sgst_amount = number_format(($rateTaxAmount/2), 2, '.', '');
					$igst_amount = 0.00;
				} else {

					$supply_type = "INTER";
					$rateTaxAmount = ($dataArray['rate']/100) * $dataArray['taxable_value'];
					$cgst_amount = 0.00;
					$sgst_amount = 0.00;
					$igst_amount = number_format($rateTaxAmount, 2, '.', '');
				}

				$singleInvoiceArray = array(
					"invoice_nature" => "b2b",
					"recipient_gstin" => $dataArray['recipient_gstin'],
					"invoice_number" => $dataArray['invoice_number'],
					"invoice_date" => $dataArray['invoice_date'],
					"invoice_value" => $dataArray['invoice_value'],
					"place_of_supply" => $dataArray['place_of_supply'],
					"supply_type" => $supply_type,
					"reverse_charge" => $dataArray['reverse_charge'],
					"invoice_type" => $dataArray['invoice_type'],
					"ecommerce_gstin_number" => $dataArray['ecommerce_gstin_number'],
					"rate" => $dataArray['rate'],
					"taxable_value" => $dataArray['taxable_value'],
					"cgst_amount" => $cgst_amount,
					"sgst_amount" => $sgst_amount,
					"igst_amount" => $igst_amount,
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

			$invoiceValue = array();
			foreach($invoiceArray as $vals){

				if(array_key_exists($vals['invoice_number'],$invoiceValue)){
					$invoiceValue[$vals['invoice_number']]['invoice_value'] += $vals['invoice_value'];
				} else {
					$invoiceValue[$vals['invoice_number']]  = $vals;
				}
			}
			
			foreach($invoiceArray as $key => $invoiceRow) {

				$invoice_number = $invoiceRow['invoice_number'];
				$invoiceArray[$key]['invoice_value'] = $invoiceValue[$invoice_number]['invoice_value'];
			}

			$resultArray = array("status" => "success", "invoiceB2BArray" => $invoiceArray);
			return json_encode($resultArray);
		}
	}

	final public function uploadClientB2CLInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path, $return_period, $currentUserStateTin) {

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

				$supply_type = "INTER";
				$rateTaxAmount = ($dataArray['rate']/100) * $dataArray['taxable_value'];
				$cgst_amount = 0.00;
				$sgst_amount = 0.00;
				$igst_amount = number_format($rateTaxAmount, 2, '.', '');

				$singleInvoiceArray = array(
					"invoice_nature" => "b2cl",
					"invoice_number" => $dataArray['invoice_number'],
					"invoice_date" => $dataArray['invoice_date'],
					"invoice_value" => $dataArray['invoice_value'],
					"place_of_supply" => $dataArray['place_of_supply'],
					"supply_type" => $supply_type,
					"rate" => $dataArray['rate'],
					"taxable_value" => $dataArray['taxable_value'],
					"cgst_amount" => $cgst_amount,
					"sgst_amount" => $sgst_amount,
					"igst_amount" => $igst_amount,
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
			
			$invoiceValue = array();
			foreach($invoiceArray as $vals){

				if(array_key_exists($vals['invoice_number'],$invoiceValue)){
					$invoiceValue[$vals['invoice_number']]['invoice_value'] += $vals['invoice_value'];
				} else {
					$invoiceValue[$vals['invoice_number']]  = $vals;
				}
			}
			
			foreach($invoiceArray as $key => $invoiceRow) {

				$invoice_number = $invoiceRow['invoice_number'];
				$invoiceArray[$key]['invoice_value'] = $invoiceValue[$invoice_number]['invoice_value'];
			}

			$resultArray = array("status" => "success", "invoiceB2CLArray" => $invoiceArray);
			return json_encode($resultArray);
		}
	}

	final public function uploadClientB2CSInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path, $return_period, $currentUserStateTin) {

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

				if($dataArray['place_of_supply'] == $currentUserStateTin) {

					$supply_type = "INTRA";
					$rateTaxAmount = ($dataArray['rate']/100) * $dataArray['taxable_value'];
					$cgst_amount = number_format(($rateTaxAmount/2), 2, '.', '');
					$sgst_amount = number_format(($rateTaxAmount/2), 2, '.', '');
					$igst_amount = 0.00;
				} else {

					$supply_type = "INTER";
					$rateTaxAmount = ($dataArray['rate']/100) * $dataArray['taxable_value'];
					$cgst_amount = 0.00;
					$sgst_amount = 0.00;
					$igst_amount = number_format($rateTaxAmount, 2, '.', '');
				}

				$dataArray['invoice_value'] = $dataArray['taxable_value'] + $cgst_amount + $sgst_amount + $igst_amount + $dataArray['cess_amount'];

				$singleInvoiceArray = array(
					"invoice_nature" => "b2cs",
					"type" => $dataArray['type'],
					"invoice_value" => number_format($dataArray['invoice_value'], 2, '.', ''),
					"place_of_supply" => $dataArray['place_of_supply'],
					"supply_type" => $supply_type,
					"rate" => $dataArray['rate'],
					"taxable_value" => $dataArray['taxable_value'],
					"cgst_amount" => $cgst_amount,
					"sgst_amount" => $sgst_amount,
					"igst_amount" => $igst_amount,
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

	final public function uploadClientCDNRInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path, $return_period, $currentUserStateTin) {

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

				if($dataArray['place_of_supply'] == $currentUserStateTin) {

					$supply_type = "INTRA";
					$rateTaxAmount = ($dataArray['rate']/100) * $dataArray['taxable_value'];
					$cgst_amount = number_format(($rateTaxAmount/2), 2, '.', '');
					$sgst_amount = number_format(($rateTaxAmount/2), 2, '.', '');
					$igst_amount = 0.00;
				} else {

					$supply_type = "INTER";
					$rateTaxAmount = ($dataArray['rate']/100) * $dataArray['taxable_value'];
					$cgst_amount = 0.00;
					$sgst_amount = 0.00;
					$igst_amount = number_format($rateTaxAmount, 2, '.', '');
				}

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
					"supply_type" => $supply_type,
					"invoice_value" => $dataArray['invoice_value'],
					"rate" => $dataArray['rate'],
					"taxable_value" => $dataArray['taxable_value'],
					"cgst_amount" => $cgst_amount,
					"sgst_amount" => $sgst_amount,
					"igst_amount" => $igst_amount,
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
			
			$invoiceValue = array();
			foreach($invoiceArray as $vals){

				if(array_key_exists($vals['invoice_number'],$invoiceValue)){
					$invoiceValue[$vals['invoice_number']]['invoice_value'] += $vals['invoice_value'];
				} else {
					$invoiceValue[$vals['invoice_number']]  = $vals;
				}
			}
			
			foreach($invoiceArray as $key => $invoiceRow) {

				$invoice_number = $invoiceRow['invoice_number'];
				$invoiceArray[$key]['invoice_value'] = $invoiceValue[$invoice_number]['invoice_value'];
			}

			$resultArray = array("status" => "success", "invoiceCDNRArray" => $invoiceArray);
			return json_encode($resultArray);
		}
	}

	final public function uploadClientCDNURInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path, $return_period, $currentUserStateTin) {

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
				
				$supply_type = "INTER";
				$rateTaxAmount = ($dataArray['rate']/100) * $dataArray['taxable_value'];
				$cgst_amount = 0.00;
				$sgst_amount = 0.00;
				$igst_amount = number_format($rateTaxAmount, 2, '.', '');

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
					"supply_type" => $supply_type,
					"invoice_value" => $dataArray['invoice_value'],
					"rate" => $dataArray['rate'],
					"taxable_value" => $dataArray['taxable_value'],
					"cgst_amount" => $cgst_amount,
					"sgst_amount" => $sgst_amount,
					"igst_amount" => $igst_amount,
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
			
			$invoiceValue = array();
			foreach($invoiceArray as $vals){

				if(array_key_exists($vals['invoice_number'],$invoiceValue)){
					$invoiceValue[$vals['invoice_number']]['invoice_value'] += $vals['invoice_value'];
				} else {
					$invoiceValue[$vals['invoice_number']]  = $vals;
				}
			}
			
			foreach($invoiceArray as $key => $invoiceRow) {

				$invoice_number = $invoiceRow['invoice_number'];
				$invoiceArray[$key]['invoice_value'] = $invoiceValue[$invoice_number]['invoice_value'];
			}
			
			$resultArray = array("status" => "success", "invoiceCDNURArray" => $invoiceArray);
			return json_encode($resultArray);
		}
	}

	final public function uploadClientEXPInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path, $return_period, $currentUserStateTin) {

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

			if(isset($dataArray['invoice_type']) && $dataArray['invoice_type'] == "WOPAY") {

				if($dataArray['rate'] != 0.00) {
					$errorflag = true;
					array_push($currentItemError, "IGST rate should be 0.00 instead of ".$dataArray['rate']." for Export Under Bond type of export invoice.");
				}
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

				$rateTaxAmount = ($dataArray['rate']/100) * $dataArray['taxable_value'];
				$cgst_amount = 0.00;
				$sgst_amount = 0.00;
				$igst_amount = number_format($rateTaxAmount, 2, '.', '');

				$singleInvoiceArray = array(
					"invoice_nature" => "exp",
					"invoice_type" => $dataArray['invoice_type'],
					"invoice_number" => $dataArray['invoice_number'],
					"invoice_date" => $dataArray['invoice_date'],
					"invoice_value" => $dataArray['invoice_value'],
					"port_code" => $dataArray['port_code'],
					"shipping_bill_number" => $dataArray['shipping_bill_number'],
					"shipping_bill_date" => $dataArray['shipping_bill_date'],
					"supply_type" => "INTER",
					"rate" => $dataArray['rate'],
					"taxable_value" => $dataArray['taxable_value'],
					"cgst_amount" => $cgst_amount,
					"sgst_amount" => $sgst_amount,
					"igst_amount" => $igst_amount,
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
			
			$invoiceValue = array();
			foreach($invoiceArray as $vals){

				if(array_key_exists($vals['invoice_number'],$invoiceValue)){
					$invoiceValue[$vals['invoice_number']]['invoice_value'] += $vals['invoice_value'];
				} else {
					$invoiceValue[$vals['invoice_number']]  = $vals;
				}
			}
			
			foreach($invoiceArray as $key => $invoiceRow) {

				$invoice_number = $invoiceRow['invoice_number'];
				$invoiceArray[$key]['invoice_value'] = $invoiceValue[$invoice_number]['invoice_value'];
			}

			$resultArray = array("status" => "success", "invoiceEXPArray" => $invoiceArray);
			return json_encode($resultArray);
		}
	}

	final public function uploadClientATInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path, $return_period, $currentUserStateTin) {

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
			
			$taxable_value = isset($data['C']) ? $data['C'] : '';
			if(is_numeric($taxable_value) && $taxable_value >= 0) {
				$dataArray['taxable_value'] = number_format($taxable_value, 2, '.', '');
			} else {
				$dataArray['taxable_value'] = 0.00;
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

				if($dataArray['place_of_supply'] == $currentUserStateTin) {
					
					$supply_type = "INTRA";
					$rateTaxAmount = ($dataArray['rate']/100) * $dataArray['taxable_value'];
					$cgst_amount = number_format(($rateTaxAmount/2), 2, '.', '');
					$sgst_amount = number_format(($rateTaxAmount/2), 2, '.', '');
					$igst_amount = 0.00;
				} else {
					
					$supply_type = "INTER";
					$rateTaxAmount = ($dataArray['rate']/100) * $dataArray['taxable_value'];
					$cgst_amount = 0.00;
					$sgst_amount = 0.00;
					$igst_amount = number_format($rateTaxAmount, 2, '.', '');
				}

				$dataArray['invoice_value'] = $dataArray['taxable_value'] + $cgst_amount + $sgst_amount + $igst_amount + $dataArray['cess_amount'];

				$singleInvoiceArray = array(
					"invoice_nature" => "at",
					"invoice_value" => number_format($dataArray['invoice_value'], 2, '.', ''),
					"place_of_supply" => $dataArray['place_of_supply'],
					"supply_type" => $supply_type,
					"rate" => $dataArray['rate'],
					"taxable_value" => $dataArray['taxable_value'],
					"cgst_amount" => $cgst_amount,
					"sgst_amount" => $sgst_amount,
					"igst_amount" => $igst_amount,
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

	final public function uploadClientATADJInvoice($sheetData, $objPHPExcel, $invoice_excel_dir_path, $return_period, $currentUserStateTin) {

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

			$taxable_value = isset($data['C']) ? $data['C'] : '';
			if(is_numeric($taxable_value) && $taxable_value >= 0) {
				$dataArray['taxable_value'] = number_format($taxable_value, 2, '.', '');
			} else {
				$dataArray['taxable_value'] = 0.00;
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

				if($dataArray['place_of_supply'] == $currentUserStateTin) {

					$supply_type = "INTRA";
					$rateTaxAmount = ($dataArray['rate']/100) * $dataArray['taxable_value'];
					$cgst_amount = number_format(($rateTaxAmount/2), 2, '.', '');
					$sgst_amount = number_format(($rateTaxAmount/2), 2, '.', '');
					$igst_amount = 0.00;
				} else {

					$supply_type = "INTER";
					$rateTaxAmount = ($dataArray['rate']/100) * $dataArray['taxable_value'];
					$cgst_amount = 0.00;
					$sgst_amount = 0.00;
					$igst_amount = number_format($rateTaxAmount, 2, '.', '');
				}

				$dataArray['invoice_value'] = $dataArray['taxable_value'] + $cgst_amount + $sgst_amount + $igst_amount + $dataArray['cess_amount'];

				$singleInvoiceArray = array(
					"invoice_nature" => "atadj",
					"invoice_value" => number_format($dataArray['invoice_value'], 2, '.', ''),
					"place_of_supply" => $dataArray['place_of_supply'],
					"supply_type" => $supply_type,
					"rate" => $dataArray['rate'],
					"taxable_value" => $dataArray['taxable_value'],
					"cgst_amount" => $cgst_amount,
					"sgst_amount" => $sgst_amount,
					"igst_amount" => $igst_amount,
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

	final public function generateB2BPayload() {

		$B2BPayloadArray = array();
		$return_period = "2017-07";
		$user_id = $this->sanitize($_SESSION['user_detail']['user_id']);
		$flag = '';

		/* get current user data */
		$dataCurrentUserArray = $this->getUserDetailsById($user_id);

		$B2BPayloadArray['gstin'] = $dataCurrentUserArray['data']->kyc->gstin_number;
		$B2BPayloadArray['fp'] = $return_period;
		$B2BPayloadArray['gt'] = $dataCurrentUserArray['data']->kyc->gross_turnover;
		$B2BPayloadArray['cur_gt'] = $dataCurrentUserArray['data']->kyc->cur_gross_turnover;

		$rowsB2BData = $this->get_results("SELECT invoice_number,invoice_date,recipient_gstin,return_period,place_of_supply,reverse_charge,ecommerce_gstin_number,invoice_type FROM ".$this->getTableName('gstr1_return_summary')." WHERE 1=1 AND return_period = '".$return_period."' AND invoice_nature = 'b2b' AND added_by='".$user_id."' group by invoice_number ORDER BY id ASC");
		foreach($rowsB2BData as $B2BData) {

			/* start b2b data */
			$B2BPayloadArray['b2b']['ctin'] = $B2BData->recipient_gstin;

			/* start b2b inv data */
			$B2BPayloadArray['b2b']['inv']['inum'] = $B2BData->invoice_number;
			$B2BPayloadArray['b2b']['inv']['idt'] = $B2BData->invoice_date;
			$B2BPayloadArray['b2b']['inv']['pos'] = $B2BData->place_of_supply;
			$B2BPayloadArray['b2b']['inv']['rchrg'] = $B2BData->reverse_charge;
			$B2BPayloadArray['b2b']['inv']['etin'] = $B2BData->ecommerce_gstin_number;
			$B2BPayloadArray['b2b']['inv']['inv_typ'] = $B2BData->invoice_type;

			$invoice_amount = 0.00;
			$itmsCounter = 0;
			$rowB2BData = $this->get_results("SELECT * FROM ".$this->getTableName('gstr1_return_summary')." WHERE 1=1 AND return_period = '".$B2BData->return_period."' AND invoice_nature = 'b2b' AND added_by='".$user_id."' AND invoice_number='".$B2BData->invoice_number."'");
			foreach($rowB2BData as $innerB2BData) {

				$invoice_amount += $innerB2BData->invoice_value;

				/* start b2b inv itms data */
				/* start b2b inv itm num data */
				$B2BPayloadArray['b2b']['inv']['itms'][$itmsCounter]['num'] = 1;

				/* start b2b inv itm det data */
				$B2BPayloadArray['b2b']['inv']['itms'][$itmsCounter]['itm_det']['rt'] = $innerB2BData->rate;
				$B2BPayloadArray['b2b']['inv']['itms'][$itmsCounter]['itm_det']['txval'] = $innerB2BData->taxable_value;

				if($dataCurrentUserArray['data']->kyc->gstin_number == $innerB2BData->place_of_supply) {
					$B2BPayloadArray['b2b']['inv']['itms'][$itmsCounter]['itm_det']['camt'] = $innerB2BData->cgst_amount;
					$B2BPayloadArray['b2b']['inv']['itms'][$itmsCounter]['itm_det']['samt'] = $innerB2BData->sgst_amount;
				} else {
					$B2BPayloadArray['b2b']['inv']['itms'][$itmsCounter]['itm_det']['iamt'] = $innerB2BData->igst_amount;
				}

				$B2BPayloadArray['b2b']['inv']['itms'][$itmsCounter]['itm_det']['csamt'] = $innerB2BData->cess_amount;
				$itmsCounter++;
			}

			$B2BPayloadArray['b2b']['inv']['val'] = $invoice_amount;
		}

		//echo "<pre>";
		//print_r($rowsB2BData);
		//echo "</pre>";

		echo "<pre>";
		print_r($B2BPayloadArray);
		echo "</pre>";
		die;
	}

	public function is_invoices_available($user_id,$returnmonth) {
        $inv = '';
        $sql = "select invoice_id as id from " . $this->getTableName('client_invoice') ." where 1=1 AND added_by = '".$user_id."' AND invoice_date LIKE '".$returnmonth."%' ";
        $invoices = $this->get_results($sql);
        if(!empty($invoices)) {
        	$inv = $invoices[0]->id;

        }
        return $inv;
    }
	public function insertIntoGstr1Table($user_id,$returnmonth) {
		$inv = $this->is_invoices_available($user_id,$returnmonth);
		if(!empty($inv)) {
			$dataArray = $itemArray = array();
			$obj_gstr1 = new gstr1();
			$dataConditionArray['return_period'] = $returnmonth;
			$dataConditionArray['added_by'] = $user_id;

			$this->deletData($this->tableNames['gstr1_return_summary'], $dataConditionArray);
			$this->logMsg("Manual Invoices deleted for return period : " . $returnmonth . " by User ID : " . $user_id . ".","gstr1_deleted");
			$arrayCounter = 0;
			$dataInvB2B = $obj_gstr1->getB2BInvoices($user_id, $returnmonth,'all'); 
			//$this->pr($dataInvB2B);
			if(isset($dataInvB2B) && !empty($dataInvB2B)) {
				
				foreach ($dataInvB2B as $key => $dataIn) {	
					$itemArray[$arrayCounter]['invoice_nature'] = 'b2b';
					$itemArray[$arrayCounter]['recipient_gstin'] = $dataIn->billing_gstin_number;
					$itemArray[$arrayCounter]['invoice_number'] = $dataIn->reference_number;
					$itemArray[$arrayCounter]['invoice_date'] = date('d-m-Y', strtotime($dataIn->invoice_date));
					$itemArray[$arrayCounter]['invoice_value'] = (float) $dataIn->invoice_total_value;
					$itemArray[$arrayCounter]['place_of_supply'] = strlen($dataIn->supply_place) == '1' ? '0' . $dataIn->supply_place : $dataIn->supply_place;
					if ($dataIn->company_state != $dataIn->supply_place) {
	                    $itemArray[$arrayCounter]['supply_type'] = 'INTER';
	                } 
	                else {
	                    $itemArray[$arrayCounter]['supply_type'] = 'INTRA';
	                }
					$in_type = '';

		            if ($dataIn->invoice_type == 'taxinvoice' || $dataIn->invoice_type =='billofsupplyinvoice') {
		                $in_type = 'R';
		            } 
		            else if ($dataIn->invoice_type == 'sezunitinvoice') {
		                if($dataIn->export_supply_meant=='withpayment')
		                {
		                        $in_type = 'SEWP';
		                }
		                else
		                {
		                        $in_type = 'SEWOP';
		                }

		            } else if ($dataIn->invoice_type == 'deemedexportinvoice') {
		                $in_type = 'DE';
		            }
		            $itemArray[$arrayCounter]['invoice_type'] = $in_type;
		            $rever_charge = ($dataIn->supply_type == 'reversecharge') ? 'Y' : 'N';

					$itemArray[$arrayCounter]['reverse_charge'] = $rever_charge;
					$itemArray[$arrayCounter]['ecommerce_gstin_number'] = isset($dataIn->ecommerce_gstin_number)?$dataIn->ecommerce_gstin_number:'';

					$rt = ($dataIn->company_state == $dataIn->supply_place) ? ($dataIn->sgst_rate + $dataIn->cgst_rate) : $dataIn->igst_rate;
					$itemArray[$arrayCounter]['rate'] = isset($rt)?$rt:'0';
					$itemArray[$arrayCounter]['taxable_value'] = isset($dataIn->taxable_subtotal)?$dataIn->taxable_subtotal:'0';
					$itemArray[$arrayCounter]['cgst_amount'] = isset($dataIn->cgst_amount)?$dataIn->cgst_amount:'0';
					$itemArray[$arrayCounter]['sgst_amount'] = isset($dataIn->sgst_amount)?$dataIn->sgst_amount:'0';
					$itemArray[$arrayCounter]['igst_amount'] = isset($dataIn->igst_amount)?$dataIn->igst_amount:'0';
					$itemArray[$arrayCounter]['cess_amount'] = isset($dataIn->cess_amount)?$dataIn->cess_amount:'0';
					$itemArray[$arrayCounter]['financial_year'] = $dataIn->financial_year;
					$itemArray[$arrayCounter]['return_period'] = $returnmonth;
					$itemArray[$arrayCounter]['added_by'] = $user_id;
					$itemArray[$arrayCounter]['added_date'] = date('Y-m-d H:i:s');
					$arrayCounter++;
				}
				$this->insertMultiple($this->tableNames['gstr1_return_summary'], $itemArray);
				$this->logMsg("Manual Invoices inserted for return period : " . $returnmonth . " by User ID : " . $user_id . ".","gstr1_b2b_inserted");
				
			}

			$dataInvB2CL = $obj_gstr1->getB2CLInvoices($user_id, $returnmonth,'all'); 
			if(isset($dataInvB2CL) && !empty($dataInvB2CL)) {
				$arrayCounter = 0;
				$itemArray = array();
				foreach ($dataInvB2CL as $key => $dataIn) {	
					$itemArray[$arrayCounter]['invoice_nature'] = 'b2cl';
					
					$itemArray[$arrayCounter]['recipient_gstin'] = $dataIn->billing_gstin_number;
					$itemArray[$arrayCounter]['invoice_number'] = $dataIn->reference_number;
					$itemArray[$arrayCounter]['invoice_date'] = date('d-m-Y', strtotime($dataIn->invoice_date));
					$itemArray[$arrayCounter]['invoice_value'] = (float) $dataIn->invoice_total_value;
					$itemArray[$arrayCounter]['place_of_supply'] = strlen($dataIn->supply_place) == '1' ? '0' . $dataIn->supply_place : $dataIn->supply_place;
		           
					if ($dataIn->company_state != $dataIn->supply_place) {
	                    $itemArray[$arrayCounter]['supply_type'] = 'INTER';
	                } 
	                else {
	                    $itemArray[$arrayCounter]['supply_type'] = 'INTRA';
	                }
					$itemArray[$arrayCounter]['ecommerce_gstin_number'] = isset($dataIn->ecommerce_gstin_number)?$dataIn->ecommerce_gstin_number:'';

					$rt = ($dataIn->company_state == $dataIn->supply_place) ? ($dataIn->sgst_rate + $dataIn->cgst_rate) : $dataIn->igst_rate;
					$itemArray[$arrayCounter]['rate'] = isset($rt)?$rt:'0';
					$itemArray[$arrayCounter]['taxable_value'] = isset($dataIn->taxable_subtotal)?$dataIn->taxable_subtotal:'0';
					$itemArray[$arrayCounter]['cgst_amount'] = isset($dataIn->cgst_amount)?$dataIn->cgst_amount:'0';
					$itemArray[$arrayCounter]['sgst_amount'] = isset($dataIn->sgst_amount)?$dataIn->sgst_amount:'0';
					$itemArray[$arrayCounter]['igst_amount'] = isset($dataIn->igst_amount)?$dataIn->igst_amount:'0';
					$itemArray[$arrayCounter]['cess_amount'] = isset($dataIn->cess_amount)?$dataIn->cess_amount:'0';
					$itemArray[$arrayCounter]['financial_year'] = $dataIn->financial_year;
					$itemArray[$arrayCounter]['return_period'] = $returnmonth;
					$itemArray[$arrayCounter]['added_by'] = $user_id;
					$itemArray[$arrayCounter]['added_date'] = date('Y-m-d H:i:s');
					$arrayCounter++;
				}
				$this->insertMultiple($this->tableNames['gstr1_return_summary'], $itemArray);
				$this->logMsg("Manual Invoices inserted for return period : " . $returnmonth . " by User ID : " . $user_id . ".","gstr1_b2b_inserted");
				
			}

			$dataInvB2CS = $obj_gstr1->getB2CSInvoices($user_id, $returnmonth,'all'); 
			if(isset($dataInvB2CS) && !empty($dataInvB2CS)) {
				$itemArray = array();
				$arrayCounter = 0;
				foreach ($dataInvB2CS as $key => $dataIn) {	
					$itemArray[$arrayCounter]['invoice_nature'] = 'b2cs';
					$itemArray[$arrayCounter]['recipient_gstin'] = $dataIn->billing_gstin_number;
					$itemArray[$arrayCounter]['invoice_number'] = $dataIn->reference_number;
					$itemArray[$arrayCounter]['invoice_date'] = date('d-m-Y', strtotime($dataIn->invoice_date));
					$itemArray[$arrayCounter]['invoice_value'] = (float) $dataIn->invoice_total_value;
					$itemArray[$arrayCounter]['place_of_supply'] = strlen($dataIn->supply_place) == '1' ? '0' . $dataIn->supply_place : $dataIn->supply_place;
		           
					if ($dataIn->company_state != $dataIn->supply_place) {
	                    $itemArray[$arrayCounter]['supply_type'] = 'INTER';
	                } 
	                else {
	                    $itemArray[$arrayCounter]['supply_type'] = 'INTRA';
	                }
	                if($dataIn->supply_type == 'tcs') {
	                    $itemArray[$arrayCounter]['type'] = 'E';
	                }
	                else {
	                    $itemArray[$arrayCounter]['type'] = 'OE';
	                }
					$itemArray[$arrayCounter]['ecommerce_gstin_number'] = isset($dataIn->ecommerce_gstin_number)?$dataIn->ecommerce_gstin_number:'';

					$rt = ($dataIn->company_state == $dataIn->supply_place) ? ($dataIn->sgst_rate + $dataIn->cgst_rate) : $dataIn->igst_rate;
					$itemArray[$arrayCounter]['rate'] = isset($rt)?$rt:'0';
					$itemArray[$arrayCounter]['taxable_value'] = isset($dataIn->taxable_subtotal)?$dataIn->taxable_subtotal:'0';
					$itemArray[$arrayCounter]['cgst_amount'] = isset($dataIn->cgst_amount)?$dataIn->cgst_amount:'0';
					$itemArray[$arrayCounter]['sgst_amount'] = isset($dataIn->sgst_amount)?$dataIn->sgst_amount:'0';
					$itemArray[$arrayCounter]['igst_amount'] = isset($dataIn->igst_amount)?$dataIn->igst_amount:'0';
					$itemArray[$arrayCounter]['cess_amount'] = isset($dataIn->cess_amount)?$dataIn->cess_amount:'0';
					$itemArray[$arrayCounter]['financial_year'] = $dataIn->financial_year;
					$itemArray[$arrayCounter]['return_period'] = $returnmonth;

					$itemArray[$arrayCounter]['added_by'] = $user_id;
					$itemArray[$arrayCounter]['added_date'] = date('Y-m-d H:i:s');
					$arrayCounter++;
				}
				$this->insertMultiple($this->tableNames['gstr1_return_summary'], $itemArray);
				$this->logMsg("Manual Invoices inserted for return period : " . $returnmonth . " by User ID : " . $user_id . ".","gstr1_b2b_inserted");
				
			}
			$dataInvCDNR = $obj_gstr1->getCDNRInvoices($user_id, $returnmonth,'all'); 
			// $this->pr($dataInvCDNR);
			// die;
			if(isset($dataInvCDNR) && !empty($dataInvCDNR)) {
				$itemArray = array();
				$arrayCounter = 0;
				foreach ($dataInvCDNR as $key => $dataIn) {	
					$itemArray[$arrayCounter]['invoice_nature'] = 'cdnr';
					$itemArray[$arrayCounter]['recipient_gstin'] = $dataIn->billing_gstin_number;
					$itemArray[$arrayCounter]['invoice_number'] = $dataIn->corresponding_document_number;
					$itemArray[$arrayCounter]['invoice_date'] = date('d-m-Y', strtotime($dataIn->corresponding_document_date));
					$itemArray[$arrayCounter]['invoice_value'] = (float) $dataIn->invoice_total_value;
					$itemArray[$arrayCounter]['place_of_supply'] = strlen($dataIn->supply_place) == '1' ? '0' . $dataIn->supply_place : $dataIn->supply_place;
		           	if ($dataIn->company_state != $dataIn->supply_place) {
	                    $itemArray[$arrayCounter]['supply_type'] = 'INTER';
	                } 
	                else {
	                    $itemArray[$arrayCounter]['supply_type'] = 'INTRA';
	                }
					$nt_type = '';
	                if ($dataIn->invoice_type == 'creditnote') {
	                    $nt_type = 'C';
	                }
	                elseif ($dataIn->invoice_type == 'refundvoucherinvoice') {
	                    $nt_type = 'R';
	                } 
	                else {
	                    $nt_type = 'D';
	                }
	                $itemArray[$arrayCounter]['document_type'] = $nt_type;
	                $itemArray[$arrayCounter]['reason_for_issuing_document'] = $dataIn->reason_issuing_document;

	                $itemArray[$arrayCounter]['pre_gst'] = "N";
	                $itemArray[$arrayCounter]['original_invoice_date'] = date('d-m-Y', strtotime($dataIn->invoice_date));
	                $itemArray[$arrayCounter]['original_invoice_number'] = $dataIn->reference_number;

					

					$rt = ($dataIn->company_state == $dataIn->supply_place) ? ($dataIn->sgst_rate + $dataIn->cgst_rate) : $dataIn->igst_rate;
					$itemArray[$arrayCounter]['rate'] = isset($rt)?$rt:'0';
					$itemArray[$arrayCounter]['taxable_value'] = isset($dataIn->taxable_subtotal)?$dataIn->taxable_subtotal:'0';
					$itemArray[$arrayCounter]['cgst_amount'] = isset($dataIn->cgst_amount)?$dataIn->cgst_amount:'0';
					$itemArray[$arrayCounter]['sgst_amount'] = isset($dataIn->sgst_amount)?$dataIn->sgst_amount:'0';
					$itemArray[$arrayCounter]['igst_amount'] = isset($dataIn->igst_amount)?$dataIn->igst_amount:'0';
					$itemArray[$arrayCounter]['cess_amount'] = isset($dataIn->cess_amount)?$dataIn->cess_amount:'0';
					$itemArray[$arrayCounter]['financial_year'] = $dataIn->financial_year;
					$itemArray[$arrayCounter]['return_period'] = $returnmonth;
					$itemArray[$arrayCounter]['added_by'] = $user_id;
					$itemArray[$arrayCounter]['added_date'] = date('Y-m-d H:i:s');
					$arrayCounter++;
				}
				$this->insertMultiple($this->tableNames['gstr1_return_summary'], $itemArray);
				$this->logMsg("Manual Invoices inserted for return period : " . $returnmonth . " by User ID : " . $user_id . ".","gstr1_b2b_inserted");
				
			}
			$dataInvCDNUR = $obj_gstr1->getCDNURInvoices($user_id, $returnmonth,'all'); 
			if(isset($dataInvCDNUR) && !empty($dataInvCDNUR)) {
				$itemArray = array();
				$arrayCounter = 0;
				foreach ($dataInvCDNUR as $key => $dataIn) {	
					$itemArray[$arrayCounter]['invoice_nature'] = 'cdnur';
					$itemArray[$arrayCounter]['recipient_gstin'] = $dataIn->billing_gstin_number;
					$itemArray[$arrayCounter]['invoice_number'] = $dataIn->corresponding_document_number;
					$itemArray[$arrayCounter]['invoice_date'] = date('d-m-Y', strtotime($dataIn->corresponding_document_date));
					$itemArray[$arrayCounter]['invoice_value'] = (float) $dataIn->invoice_total_value;
					$itemArray[$arrayCounter]['place_of_supply'] = strlen($dataIn->supply_place) == '1' ? '0' . $dataIn->supply_place : $dataIn->supply_place;
					if ($dataIn->company_state != $dataIn->supply_place) {
	                    $itemArray[$arrayCounter]['supply_type'] = 'INTER';
	                } 
	                else {
	                    $itemArray[$arrayCounter]['supply_type'] = 'INTRA';
	                }
		           
					$nt_type = '';
	                if ($dataIn->invoice_type == 'creditnote') {
	                    $nt_type = 'C';
	                }
	                elseif ($dataIn->invoice_type == 'refundvoucherinvoice') {
	                    $nt_type = 'R';
	                } 
	                else {
	                    $nt_type = 'D';
	                }
	                $itemArray[$arrayCounter]['document_type'] = $nt_type;
	                $type = '';
	                if ($dataIn->original_type == 'taxinvoice') {
	                    $type = "B2CL";
	                } 
	                else {
	                    if ($dataIn->export_supply_meant == 'withpayment') {
	                        $type = "EXPWP";
	                    }
	                    else {
	                        $type = "EXPWOP";
	                    }
	                }
	                $itemArray[$arrayCounter]['ur_type'] = $type;
	                $itemArray[$arrayCounter]['reason_for_issuing_document'] = $dataIn->reason_issuing_document;

	                $itemArray[$arrayCounter]['pre_gst'] = "N";
	                $itemArray[$arrayCounter]['original_invoice_date'] = date('d-m-Y', strtotime($dataIn->invoice_date));
	                $itemArray[$arrayCounter]['original_invoice_number'] = $dataIn->reference_number;

					

					$rt = ($dataIn->company_state == $dataIn->supply_place) ? ($dataIn->sgst_rate + $dataIn->cgst_rate) : $dataIn->igst_rate;
					$itemArray[$arrayCounter]['rate'] = isset($rt)?$rt:'0';
					$itemArray[$arrayCounter]['taxable_value'] = isset($dataIn->taxable_subtotal)?$dataIn->taxable_subtotal:'0';
					$itemArray[$arrayCounter]['cgst_amount'] = isset($dataIn->cgst_amount)?$dataIn->cgst_amount:'0';
					$itemArray[$arrayCounter]['sgst_amount'] = isset($dataIn->sgst_amount)?$dataIn->sgst_amount:'0';
					$itemArray[$arrayCounter]['igst_amount'] = isset($dataIn->igst_amount)?$dataIn->igst_amount:'0';
					$itemArray[$arrayCounter]['cess_amount'] = isset($dataIn->cess_amount)?$dataIn->cess_amount:'0';
					$itemArray[$arrayCounter]['financial_year'] = $dataIn->financial_year;
					$itemArray[$arrayCounter]['return_period'] = $returnmonth;
					$itemArray[$arrayCounter]['added_by'] = $user_id;
					$itemArray[$arrayCounter]['added_date'] = date('Y-m-d H:i:s');
					$arrayCounter++;
				}
				$this->insertMultiple($this->tableNames['gstr1_return_summary'], $itemArray);
				$this->logMsg("Manual Invoices inserted for return period : " . $returnmonth . " by User ID : " . $user_id . ".","gstr1_b2b_inserted");

			}
			
			$dataInvAt = $obj_gstr1->getATInvoices($user_id, $returnmonth,'all'); 
			if(isset($dataInvAt) && !empty($dataInvAt)) {
				$itemArray = array();
				$arrayCounter = 0;
				foreach ($dataInvAt as $key => $dataIn) {	
					$itemArray[$arrayCounter]['invoice_nature'] = 'at';
					$itemArray[$arrayCounter]['recipient_gstin'] = $dataIn->billing_gstin_number;
					$itemArray[$arrayCounter]['invoice_number'] = $dataIn->reference_number;
					$itemArray[$arrayCounter]['invoice_date'] = date('d-m-Y', strtotime($dataIn->invoice_date));
					$itemArray[$arrayCounter]['invoice_value'] = (float) $dataIn->invoice_total_value;
					$itemArray[$arrayCounter]['place_of_supply'] = strlen($dataIn->supply_place) == '1' ? '0' . $dataIn->supply_place : $dataIn->supply_place;
		           
					if ($dataIn->company_state != $dataIn->supply_place) {
	                    $itemArray[$arrayCounter]['supply_type'] = 'INTER';
	                } 
	                else {
	                    $itemArray[$arrayCounter]['supply_type'] = 'INTRA';
	                }


					$rt = ($dataIn->company_state == $dataIn->supply_place) ? ($dataIn->sgst_rate + $dataIn->cgst_rate) : $dataIn->igst_rate;
					$itemArray[$arrayCounter]['rate'] = isset($rt)?$rt:'0';
					$itemArray[$arrayCounter]['taxable_value'] = isset($dataIn->taxable_subtotal)?$dataIn->taxable_subtotal:'0';
					$itemArray[$arrayCounter]['cgst_amount'] = isset($dataIn->cgst_amount)?$dataIn->cgst_amount:'0';
					$itemArray[$arrayCounter]['sgst_amount'] = isset($dataIn->sgst_amount)?$dataIn->sgst_amount:'0';
					$itemArray[$arrayCounter]['igst_amount'] = isset($dataIn->igst_amount)?$dataIn->igst_amount:'0';
					$itemArray[$arrayCounter]['cess_amount'] = isset($dataIn->cess_amount)?$dataIn->cess_amount:'0';
					$itemArray[$arrayCounter]['financial_year'] = $dataIn->financial_year;
					$itemArray[$arrayCounter]['return_period'] = $returnmonth;
					$itemArray[$arrayCounter]['added_by'] = $user_id;
					$itemArray[$arrayCounter]['added_date'] = date('Y-m-d H:i:s');
					$arrayCounter++;
				}
				$this->insertMultiple($this->tableNames['gstr1_return_summary'], $itemArray);
				$this->logMsg("Manual Invoices inserted for return period : " . $returnmonth . " by User ID : " . $user_id . ".","gstr1_b2b_inserted");
				
			}
			$dataInvTXPD = $obj_gstr1->getTXPDInvoices($user_id, $returnmonth,'all'); 
			if(isset($dataInvTXPD) && !empty($dataInvTXPD)) {
				$itemArray = array();
				$arrayCounter = 0;
				foreach ($dataInvTXPD as $key => $dataIn) {	
					$itemArray[$arrayCounter]['invoice_nature'] = 'atadj';
					$itemArray[$arrayCounter]['recipient_gstin'] = $dataIn->billing_gstin_number;
					$itemArray[$arrayCounter]['invoice_number'] = $dataIn->reference_number;
					$itemArray[$arrayCounter]['invoice_date'] = date('d-m-Y', strtotime($dataIn->invoice_date));
					$itemArray[$arrayCounter]['invoice_value'] = (float) $dataIn->invoice_total_value;
					$itemArray[$arrayCounter]['place_of_supply'] = strlen($dataIn->supply_place) == '1' ? '0' . $dataIn->supply_place : $dataIn->supply_place;
		           
					if ($dataIn->company_state != $dataIn->supply_place) {
	                    $itemArray[$arrayCounter]['supply_type'] = 'INTER';
	                } 
	                else {
	                    $itemArray[$arrayCounter]['supply_type'] = 'INTRA';
	                }


					$rt = ($dataIn->company_state == $dataIn->supply_place) ? ($dataIn->sgst_rate + $dataIn->cgst_rate) : $dataIn->igst_rate;
					$itemArray[$arrayCounter]['rate'] = isset($rt)?$rt:'0';
					$itemArray[$arrayCounter]['taxable_value'] = isset($dataIn->taxable_subtotal)?$dataIn->taxable_subtotal:'0';
					$itemArray[$arrayCounter]['cgst_amount'] = isset($dataIn->cgst_amount)?$dataIn->cgst_amount:'0';
					$itemArray[$arrayCounter]['sgst_amount'] = isset($dataIn->sgst_amount)?$dataIn->sgst_amount:'0';
					$itemArray[$arrayCounter]['igst_amount'] = isset($dataIn->igst_amount)?$dataIn->igst_amount:'0';
					$itemArray[$arrayCounter]['cess_amount'] = isset($dataIn->cess_amount)?$dataIn->cess_amount:'0';
					$itemArray[$arrayCounter]['financial_year'] = $dataIn->financial_year;
					$itemArray[$arrayCounter]['return_period'] = $returnmonth;
					$itemArray[$arrayCounter]['added_by'] = $user_id;
					$itemArray[$arrayCounter]['added_date'] = date('Y-m-d H:i:s');
					$arrayCounter++;
				}
				$this->insertMultiple($this->tableNames['gstr1_return_summary'], $itemArray);
				$this->logMsg("Manual Invoices inserted for return period : " . $returnmonth . " by User ID : " . $user_id . ".","gstr1_b2b_inserted");
				
			}
			$dataInvExp = $obj_gstr1->getEXPInvoices($user_id, $returnmonth,'all'); 
			
			if(isset($dataInvExp) && !empty($dataInvExp)) {
				$itemArray = array();
				$arrayCounter = 0;
				foreach ($dataInvExp as $key => $dataIn) {	
					$itemArray[$arrayCounter]['invoice_nature'] = 'exp';
					$itemArray[$arrayCounter]['recipient_gstin'] = $dataIn->billing_gstin_number;
					$itemArray[$arrayCounter]['invoice_number'] = $dataIn->reference_number;
					$itemArray[$arrayCounter]['invoice_date'] = date('d-m-Y', strtotime($dataIn->invoice_date));
					$itemArray[$arrayCounter]['invoice_value'] = (float) $dataIn->invoice_total_value;
					$itemArray[$arrayCounter]['place_of_supply'] = strlen($dataIn->supply_place) == '1' ? '0' . $dataIn->supply_place : $dataIn->supply_place;
					if ($dataIn->company_state != $dataIn->supply_place) {
	                    $itemArray[$arrayCounter]['supply_type'] = 'INTER';
	                } 
	                else {
	                    $itemArray[$arrayCounter]['supply_type'] = 'INTRA';
	                }
		           
	                $itemArray[$arrayCounter]['invoice_type'] = ($dataIn->export_supply_meant=='withpayment') ? "WPAY" : 'WOPAY';

	                $itemArray[$arrayCounter]['port_code'] = $dataIn->export_bill_port_code;
	                $itemArray[$arrayCounter]['shipping_bill_number'] = (int)$dataIn->export_bill_number;
	                $itemArray[$arrayCounter]['shipping_bill_date'] =$dataIn->export_bill_date > 0 ? date('d-m-Y', strtotime($dataIn->export_bill_date)) : '';

					$rt = ($dataIn->company_state == $dataIn->supply_place) ? ($dataIn->sgst_rate + $dataIn->cgst_rate) : $dataIn->igst_rate;
					$itemArray[$arrayCounter]['rate'] = isset($rt)?$rt:'0';
					$itemArray[$arrayCounter]['taxable_value'] = isset($dataIn->taxable_subtotal)?$dataIn->taxable_subtotal:'0';
					$itemArray[$arrayCounter]['cgst_amount'] = isset($dataIn->cgst_amount)?$dataIn->cgst_amount:'0';
					$itemArray[$arrayCounter]['sgst_amount'] = isset($dataIn->sgst_amount)?$dataIn->sgst_amount:'0';
					$itemArray[$arrayCounter]['igst_amount'] = isset($dataIn->igst_amount)?$dataIn->igst_amount:'0';
					$itemArray[$arrayCounter]['cess_amount'] = isset($dataIn->cess_amount)?$dataIn->cess_amount:'0';
					$itemArray[$arrayCounter]['financial_year'] = $dataIn->financial_year;
					$itemArray[$arrayCounter]['return_period'] = $returnmonth;
					$itemArray[$arrayCounter]['added_by'] = $user_id;
					$itemArray[$arrayCounter]['added_date'] = date('Y-m-d H:i:s');
					$arrayCounter++;
				}

				$this->insertMultiple($this->tableNames['gstr1_return_summary'], $itemArray);
				$this->logMsg("Manual Invoices inserted for return period : " . $returnmonth . " by User ID : " . $user_id . ".","gstr1_b2b_inserted");

				
			}
			return true;
		}
		else {
			$this->setError('Sorry! Invoices are not found for selected month.');
			return false;
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
		
		if (array_key_exists("from", $dataArr)) {
			$rules['from'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/||min:1||max:16|#|lable_name:From Serial Number';
		}

		if (array_key_exists("to", $dataArr)) {
			$rules['to'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/||min:1||max:16|#|lable_name:To Serial Number';
		}

		if (array_key_exists("totnum", $dataArr)) {
			$rules['totnum'] = 'required||numeric||pattern:/^' . $this->validateType['integerwithzero'] . '*$/|#|lable_name:Total Number';
		}

		if (array_key_exists("cancel", $dataArr)) {
			$rules['cancel'] = 'required||numeric||pattern:/^' . $this->validateType['integerwithzero'] . '*$/|#|lable_name:Cancelled';
		}

		if (array_key_exists("net_issue", $dataArr)) {
			$rules['net_issue'] = 'required||numeric||pattern:/^' . $this->validateType['integerwithzero'] . '*$/|#|lable_name:Net Issued';
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