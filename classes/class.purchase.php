<?php
/*
	* 
	*  Developed By        :   Ishwar Lal Ghiya
	*  Date Created        :   July 12, 2017
	*  Last Modified       :   July 12, 2017
	*  Last Modified By    :   Ishwar Lal Ghiya
	*  Last Modification   :   class for purchase 
	* 
*/

final class purchase extends validation {
    
    public function __construct() {
        parent::__construct();
    }

	/* validate client purchase invoice */
	public function validateClientPurchaseInvoice($dataArr) {
		
		if (array_key_exists("invoice_type", $dataArr)) {
            $rules['invoice_type'] = 'required||invoicetype|#|lable_name:Invoice Type';
        }

        if (array_key_exists("invoice_nature", $dataArr)) {
            $rules['invoice_nature'] = 'required||invoicenature|#|lable_name:Invoice Nature';
        }
		
		if (array_key_exists("invoice_date", $dataArr)) {
            $rules['invoice_date'] = 'required||date|#|lable_name:Invoice Date';
        }
		
		if (array_key_exists("reference_number", $dataArr)) {
            $rules['reference_number'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/||max:16|#|lable_name:Reference Number';
        }

		if (array_key_exists("company_name", $dataArr)) {
            $rules['company_name'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Company Name';
        }

        if (array_key_exists("company_address", $dataArr)) {
            $rules['company_address'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Company Address';
        }

        if (array_key_exists("company_state", $dataArr)) {
            $rules['company_state'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Company State';
        }

        if (array_key_exists("company_gstin_number", $dataArr)) {
            $rules['company_gstin_number'] = 'required||pattern:/^' . $this->validateType['gstinnumber'] . '+$/||min:15||max:15|#|lable_name:Company GSTIN Number';
        }

        if (array_key_exists("supply_type", $dataArr)) {
            $rules['supply_type'] = 'required||supplytype|#|lable_name:Supply Type';
        }
		
		if( array_key_exists("import_supply_meant", $dataArr) ) {
            $rules['import_supply_meant'] = 'required||supplymeant|#|lable_name:Supply Meant';
        }

		if (array_key_exists("import_bill_number", $dataArr)) {
            $rules['import_bill_number'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Import Bill Number';
        }

		if (array_key_exists("import_bill_port_code", $dataArr)) {
            $rules['import_bill_port_code'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/||max:6|#|lable_name:Import Bill Port Code';
        }

		if (array_key_exists("import_bill_date", $dataArr)) {
            $rules['import_bill_date'] = 'required||date|#|lable_name:Import Bill Date';
        }
		
		if (array_key_exists("invoice_corresponding_type", $dataArr)) {
            $rules['invoice_corresponding_type'] = 'required||invoiecorresponding|#|lable_name:Invoice Corresponding Type';
        }

        if (array_key_exists("corresponding_document_number", $dataArr)) {
            $rules['corresponding_document_number'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Corresponding Document Number';
        }

        if (array_key_exists("corresponding_document_date", $dataArr)) {
            $rules['corresponding_document_date'] = 'required||date|#|lable_name:Corresponding Document Date';
        }

        if (array_key_exists("is_tax_payable", $dataArr)) {
            $rules['is_tax_payable'] = 'required||pattern:/^[' . $this->validateType['onlyzeroone'] . ']*$/|#|lable_name:Tax Reverse Charge';
        }

		if (array_key_exists("supply_place", $dataArr)) {
            $rules['supply_place'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Place Of Supply';
        }

		if( array_key_exists("description", $dataArr) ) {
            $rules['description'] = 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Description';
        }

		if (array_key_exists("advance_adjustment", $dataArr)) {
            $rules['advance_adjustment'] = 'required||pattern:/^[' . $this->validateType['onlyzeroone'] . ']*$/|#|lable_name:Advance Adjustment';
        }

		if (array_key_exists("refund_voucher_receipt", $dataArr)) {
            $rules['refund_voucher_receipt'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Receipt Voucher';
        }

		if (array_key_exists("supplier_billing_name", $dataArr)) {
            $rules['supplier_billing_name'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Billing Name';
        }
		
		if (array_key_exists("supplier_billing_company_name", $dataArr)) {
            $rules['supplier_billing_company_name'] = 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Billing Company Name';
        }

        if (array_key_exists("supplier_billing_address", $dataArr)) {
            $rules['supplier_billing_address'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Billing Address';
        }

        if (array_key_exists("supplier_billing_state", $dataArr)) {
            $rules['supplier_billing_state'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Billing State';
        }

		if (array_key_exists("supplier_billing_state_name", $dataArr)) {
            $rules['supplier_billing_state_name'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Billing State Name';
        }

		if (array_key_exists("supplier_billing_country", $dataArr)) {
            $rules['supplier_billing_country'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Billing Country';
        }

		if (array_key_exists("supplier_billing_vendor_type", $dataArr)) {
            $rules['supplier_billing_vendor_type'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Billing Vendor Type';
        }

        if (array_key_exists("supplier_billing_gstin_number", $dataArr)) {
            $rules['supplier_billing_gstin_number'] = 'pattern:/^' . $this->validateType['gstinnumber'] . '+$/||min:15||max:15|#|lable_name:Billing GSTIN Number';
        }

		if (array_key_exists("recipient_shipping_name", $dataArr)) {
            $rules['recipient_shipping_name'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Recipient Shipping Name';
        }
		
		if (array_key_exists("recipient_shipping_company_name", $dataArr)) {
            $rules['recipient_shipping_company_name'] = 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Recipient Shipping Company Name';
        }

        if (array_key_exists("recipient_shipping_address", $dataArr)) {
            $rules['recipient_shipping_address'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Recipient Shipping Address';
        }

        if (array_key_exists("recipient_shipping_state", $dataArr)) {
            $rules['recipient_shipping_state'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Recipient Shipping State';
        }

		if (array_key_exists("recipient_shipping_state_name", $dataArr)) {
            $rules['recipient_shipping_state_name'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Recipient Shipping State Name';
        }

		if (array_key_exists("recipient_shipping_country", $dataArr)) {
            $rules['recipient_shipping_country'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Recipient Shipping Country';
        }

		if (array_key_exists("recipient_shipping_vendor_type", $dataArr)) {
            $rules['recipient_shipping_vendor_type'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Recipient Shipping Vendor Type';
        }

        if (array_key_exists("recipient_shipping_gstin_number", $dataArr)) {
            $rules['recipient_shipping_gstin_number'] = 'pattern:/^' . $this->validateType['gstinnumber'] . '+$/||min:15||max:15|#|lable_name:Recipient Shipping GSTIN Number';
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
	/* end of validate client invoice */

	/* validate client invoice items */
    public function validateClientPurchaseInvoiceItem($dataArr, $serialno) {
		
		if (array_key_exists("invoice_itemid", $dataArr)) {
            $rules['invoice_itemid'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Invoice Item no. ' . $serialno;
        }
		
		if (array_key_exists("invoice_description", $dataArr)) {
            $rules['invoice_description'] = 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Description of Item no. ' . $serialno;
        }

		if (array_key_exists("invoice_quantity", $dataArr)) {
            $rules['invoice_quantity'] = 'required||numeric||decimal|#|lable_name:Quantity of Item no. ' . $serialno;
        }

        if (array_key_exists("invoice_discount", $dataArr)) {
            $rules['invoice_discount'] = 'numeric||decimalzero|#|lable_name:Discount of Item no. ' . $serialno;
        }

		if (array_key_exists("invoice_rate", $dataArr)) {
            $rules['invoice_rate'] = 'required||numeric||decimal|#|lable_name:Rate of Item no. ' . $serialno;
        }

        if (array_key_exists("invoice_taxablevalue", $dataArr)) {
            $rules['invoice_taxablevalue'] = 'required||numeric||decimalzero|#|lable_name:Taxable Amount of Item no. ' . $serialno;
        }

		if (array_key_exists("invoice_advancevalue", $dataArr)) {
            $rules['invoice_advancevalue'] = 'numeric||decimalzero|#|lable_name:Advance Amount of Item no. ' . $serialno;
        }

		if (array_key_exists("invoice_cgstrate", $dataArr)) {
            $rules['invoice_cgstrate'] = 'numeric|#|lable_name:CGST Rate of Item no. ' . $serialno;
        }

		if (array_key_exists("invoice_sgstrate", $dataArr)) {
            $rules['invoice_sgstrate'] = 'numeric|#|lable_name:SGST Rate of Item no. ' . $serialno;
        }

		if (array_key_exists("invoice_igstrate", $dataArr)) {
            $rules['invoice_igstrate'] = 'numeric|#|lable_name:IGST Rate of Item no. ' . $serialno;
        }

		if (array_key_exists("invoice_cessrate", $dataArr)) {
            $rules['invoice_cessrate'] = 'numeric|#|lable_name:CESS Rate of Item no. ' . $serialno;
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
	/* end of validate client invoice items */

	/* validate client purchase invoice excel file */
    public function validateClientPurchaseInvoiceExcel($dataArr) {

        if (array_key_exists("invoice_type", $dataArr)) {
			$rules['invoice_type'] = 'required||invoicetype|#|lable_name:Invoice Type';
		}

		if (array_key_exists("invoice_date", $dataArr)) {
			$rules['invoice_date'] = 'required||date|#|lable_name:Invoice Date';
		}

		if (array_key_exists("reference_number", $dataArr)) {
			$rules['reference_number'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/||max:16|#|lable_name:Reference Number';
		}

		if (array_key_exists("supply_type", $dataArr)) {
			$rules['supply_type'] = 'required||supplytype|#|lable_name:Supply Type';
		}

		if( array_key_exists("import_supply_meant", $dataArr) ) {
			$rules['import_supply_meant'] = 'required||supplymeant|#|lable_name:Supply Meant';
		}

		if (array_key_exists("import_bill_number", $dataArr)) {
			$rules['import_bill_number'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Import Bill Number';
		}

		if (array_key_exists("import_bill_port_code", $dataArr)) {
			$rules['import_bill_port_code'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/||max:6|#|lable_name:Import Bill Port Code';
		}

		if (array_key_exists("import_bill_date", $dataArr)) {
			$rules['import_bill_date'] = 'required||date|#|lable_name:Import Bill Date';
		}

		if (array_key_exists("invoice_corresponding_type", $dataArr)) {
			$rules['invoice_corresponding_type'] = 'required||invoiecorresponding|#|lable_name:Invoice Corresponding Type';
		}

		if (array_key_exists("corresponding_document_number", $dataArr)) {
			$rules['corresponding_document_number'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Corresponding Document Number';
		}

		if (array_key_exists("corresponding_document_date", $dataArr)) {
			$rules['corresponding_document_date'] = 'required||date|#|lable_name:Corresponding Document Date';
		}

		if (array_key_exists("is_tax_payable", $dataArr)) {
			$rules['is_tax_payable'] = 'required||pattern:/^[' . $this->validateType['onlyzeroone'] . ']*$/|#|lable_name:Tax Reverse Charge';
		}

		if (array_key_exists("supply_place", $dataArr)) {
			$rules['supply_place'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Place Of Supply';
		}

		if( array_key_exists("description", $dataArr) ) {
			$rules['description'] = 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Description';
		}

		if (array_key_exists("advance_adjustment", $dataArr)) {
			$rules['advance_adjustment'] = 'required||pattern:/^[' . $this->validateType['onlyzeroone'] . ']*$/|#|lable_name:Advance Adjustment';
		}

		if (array_key_exists("refund_voucher_receipt", $dataArr)) {
			$rules['refund_voucher_receipt'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Receipt Voucher';
		}

		if (array_key_exists("supplier_billing_name", $dataArr)) {
			$rules['supplier_billing_name'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Billing Name';
		}
		
		if (array_key_exists("supplier_billing_company_name", $dataArr)) {
			$rules['supplier_billing_company_name'] = 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Billing Company Name';
		}

		if (array_key_exists("supplier_billing_address", $dataArr)) {
			$rules['supplier_billing_address'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Billing Address';
		}

		if (array_key_exists("supplier_billing_state", $dataArr)) {
			$rules['supplier_billing_state'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Billing State';
		}
		
		if (array_key_exists("supplier_billing_state_name", $dataArr)) {
			$rules['supplier_billing_state_name'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Billing State Name';
		}

		if (array_key_exists("supplier_billing_country", $dataArr)) {
			$rules['supplier_billing_country'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Billing Country';
		}

		if (array_key_exists("supplier_billing_vendor_type", $dataArr)) {
			$rules['supplier_billing_vendor_type'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Billing Vendor Type';
		}

		if (array_key_exists("supplier_billing_gstin_number", $dataArr)) {
			$rules['supplier_billing_gstin_number'] = 'pattern:/^' . $this->validateType['gstinnumber'] . '+$/||min:15||max:15|#|lable_name:Billing GSTIN Number';
		}

		if (array_key_exists("recipient_shipping_name", $dataArr)) {
			$rules['recipient_shipping_name'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Recipient Shipping Name';
		}
		
		if (array_key_exists("recipient_shipping_company_name", $dataArr)) {
			$rules['recipient_shipping_company_name'] = 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Recipient Shipping Company Name';
		}

		if (array_key_exists("recipient_shipping_address", $dataArr)) {
			$rules['recipient_shipping_address'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Recipient Shipping Address';
		}

		if (array_key_exists("recipient_shipping_state", $dataArr)) {
			$rules['recipient_shipping_state'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Recipient Shipping State';
		}

		if (array_key_exists("recipient_shipping_state_name", $dataArr)) {
			$rules['recipient_shipping_state_name'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Recipient Shipping State Name';
		}

		if (array_key_exists("recipient_shipping_country", $dataArr)) {
			$rules['recipient_shipping_country'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Recipient Shipping Country';
		}

		if (array_key_exists("recipient_shipping_vendor_type", $dataArr)) {
			$rules['recipient_shipping_vendor_type'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Recipient Shipping Vendor Type';
		}

		if (array_key_exists("recipient_shipping_gstin_number", $dataArr)) {
			$rules['recipient_shipping_gstin_number'] = 'pattern:/^' . $this->validateType['gstinnumber'] . '+$/||min:15||max:15|#|lable_name:Recipient Shipping GSTIN Number';
		}

		if (array_key_exists("item_id", $dataArr)) {
			$rules['item_id'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '$/|#|lable_name:Item Id';
		}

        if (array_key_exists("item_name", $dataArr)) {
            $rules['item_name'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Item Name';
        }

        if (array_key_exists("item_hsncode", $dataArr)) {
            $rules['item_hsncode'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Item HSN Code';
        }

		if (array_key_exists("item_quantity", $dataArr)) {
			$rules['item_quantity'] = 'required||numeric||decimal|#|lable_name:Item Quantity';
		}
		
		if (array_key_exists("item_unit", $dataArr)) {
            $rules['item_unit'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Item Unit Code';
        }

		if (array_key_exists("item_rate", $dataArr)) {
			$rules['item_rate'] = 'required||numeric||decimal|#|lable_name:Item Price';
		}

		if (array_key_exists("item_discount", $dataArr)) {
			$rules['item_discount'] = 'numeric||decimalzero|#|lable_name:Item Discount';
		}

		if (array_key_exists("advance_amount", $dataArr)) {
			$rules['advance_amount'] = 'numeric||decimalzero|#|lable_name:Advance Amount';
		}

        if (array_key_exists("item_taxablevalue", $dataArr)) {
            $rules['item_taxablevalue'] = 'required||numeric||decimalzero|#|lable_name:Taxable Amount of Item';
        }

		if (array_key_exists("cgst_rate", $dataArr)) {
			$rules['cgst_rate'] = 'numeric|#|lable_name:CGST Rate of Item';
		}

		if (array_key_exists("sgst_rate", $dataArr)) {
			$rules['sgst_rate'] = 'numeric|#|lable_name:SGST Rate of Item';
		}

		if (array_key_exists("igst_rate", $dataArr)) {
			$rules['igst_rate'] = 'numeric|#|lable_name:IGST Rate of Item';
		}

		if (array_key_exists("cess_rate", $dataArr)) {
			$rules['cess_rate'] = 'numeric|#|lable_name:CESS Rate of Item';
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

    /* upload client purchase invoice */
    public function uploadPurchaseClientInvoice() {

        $flag = true;
        $errorflag = false;
        $dataArray = array();
        $indexArray = array();
        $invoiceArray = array();
        $invoiceItemArray = array();
		$currentFinancialYear = $this->generateFinancialYear();

        if ($_FILES['invoice_xlsx']['name'] != '' && $_FILES['invoice_xlsx']['error'] == 0) {

            $invoice_excel = $this->imageUploads($_FILES['invoice_xlsx'], 'invoice-docs', 'upload', $this->allowExcelExt);
            if ($invoice_excel == FALSE) {
                return false;
            }

            $invoice_excel_dir_path = PROJECT_ROOT . UPLOAD_DIR . "/invoice-docs/" . $invoice_excel;
            $invoice_excel_url_path = PROJECT_URL . UPLOAD_DIR . "/invoice-docs/" . $invoice_excel;

            $objPHPExcel = PHPExcel_IOFactory::load($invoice_excel_dir_path);
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
			$sheetData = array_map('array_filter', $sheetData);
			$sheetData = array_filter($sheetData);

			foreach ($sheetData as $rowKey => $data) {

                if ($flag) {
                    $indexArray = $data;
                    $flag = false;
                    continue;
                }

                $currentItemError = array();
                $dataArray['reference_number'] = isset($data['A']) ? $data['A'] : '';
				$dataArray['invoice_date'] = isset($data['B']) ? $data['B'] : '';

                $supply_type = isset($data['C']) ? $data['C'] : '';
				if ($supply_type != '' && strtoupper($supply_type) === 'NORMAL') {
                    $dataArray['supply_type'] = "normal";
                } else if ($supply_type != '' && strtoupper($supply_type) === 'REVERSE CHARGE') {
                    $dataArray['supply_type'] = "reversecharge";
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Supply Type.");
                }

                $supply_place = isset($data['D']) ? $data['D'] : '';
                if ($supply_place != '') {

                    $supply_state_data = $this->getStateDetailByStateNameCode($supply_place);
                    if ($supply_state_data['status'] === "success") {
                        $dataArray['supply_place'] = $supply_state_data['data']->state_id;
                    } else {
                        $errorflag = true;
                        array_push($currentItemError, "Invalid Place Of Supply.");
                    }
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Place Of Supply.");
                }

                $advance_adjustment = isset($data['E']) ? $data['E'] : '';
                if ($advance_adjustment != '' && strtoupper($advance_adjustment) === 'Y') {
                    $dataArray['advance_adjustment'] = 1;
                } else if ($advance_adjustment != '' && strtoupper($advance_adjustment) === 'N') {
                    $dataArray['advance_adjustment'] = 0;
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Advance Adjustment.");
                }

				if($dataArray['advance_adjustment'] == 1) {
					
					$receipt_voucher_serial = isset($data['F']) ? $data['F'] : '';
					$dataReceiptVoucherArrs = $this->get_row("select purchase_invoice_id, serial_number, invoice_date, supply_place, is_canceled from ".$this->tableNames['client_purchase_invoice']." where 1=1 AND serial_number = '".$receipt_voucher_serial."' AND invoice_type = 'receiptvoucherinvoice' AND is_canceled='0' AND status='1' AND is_deleted='0' AND financial_year = '".$currentFinancialYear."' AND added_by = ".$this->sanitize($_SESSION['user_detail']['user_id']));
					if (!empty($dataReceiptVoucherArrs) && isset($dataReceiptVoucherArrs->purchase_invoice_id)) {
						$dataArray['receipt_voucher_number'] = $dataReceiptVoucherArrs->purchase_invoice_id;
                    } else {
                        $errorflag = true;
                        array_push($currentItemError, "Invalid Receipt Voucher.");
                    }
				}

                $dataArray['supplier_billing_name'] = isset($data['G']) ? $data['G'] : '';
				$dataArray['supplier_billing_company_name'] = isset($data['H']) ? $data['H'] : '';
                $dataArray['supplier_billing_address'] = isset($data['I']) ? $data['I'] : '';

				$supplier_billing_state = isset($data['J']) ? $data['J'] : '';
                if ($supplier_billing_state != '') {

                    $supplier_billing_state_data = $this->getStateDetailByStateNameCode($supplier_billing_state);
                    if ($supplier_billing_state_data['status'] === "success") {
						$dataArray['supplier_billing_state'] = $supplier_billing_state_data['data']->state_id;
						$dataArray['supplier_billing_state_name'] = $supplier_billing_state_data['data']->state_name;
                    } else {
                        $errorflag = true;
                        array_push($currentItemError, "Invalid Supplier Billing State.");
                    }
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Supplier Billing State.");
                }

				$supplier_billing_country = isset($data['K']) ? $data['K'] : '';
				if ($supplier_billing_country != '') {

                    $supplier_billing_country_data = $this->getCountryDetailByCountryCode($supplier_billing_country);
                    if ($supplier_billing_country_data['status'] === "success") {
						$dataArray['supplier_billing_country'] = $supplier_billing_country_data['data']->id;
                    } else {
                        $errorflag = true;
                        array_push($currentItemError, "Invalid Supplier Billing Country.");
                    }
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Supplier Billing Country.");
                }

				$supplier_billing_vendor_type = isset($data['L']) ? $data['L'] : '';
				if ($supplier_billing_vendor_type != '') {

					$dataVendorNameArrs = $this->get_row("select vendor_id, vendor_name from ".$this->tableNames['vendor_type']." where 1=1 AND UPPER(vendor_name) = '".strtoupper($supplier_billing_vendor_type)."' AND status='1' AND is_deleted='0'");
                    if (!empty($dataVendorNameArrs) && isset($dataVendorNameArrs->vendor_id)) {
						$dataArray['supplier_billing_vendor_type'] = $dataVendorNameArrs->vendor_id;
                    } else {
                        $errorflag = true;
                        array_push($currentItemError, "Invalid Supplier Billing Vendor Type.");
                    }
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Supplier Billing Vendor Type.");
                }

				$dataArray['supplier_billing_gstin_number'] = isset($data['M']) ? $data['M'] : '';

				$dataArray['recipient_shipping_name'] = isset($data['N']) ? $data['N'] : '';
				$dataArray['recipient_shipping_company_name'] = isset($data['O']) ? $data['O'] : '';
                $dataArray['recipient_shipping_address'] = isset($data['P']) ? $data['P'] : '';

				$recipient_shipping_state = isset($data['Q']) ? $data['Q'] : '';
                if ($recipient_shipping_state != '') {

                    $recipient_shipping_state_data = $this->getStateDetailByStateNameCode($recipient_shipping_state);
                    if ($recipient_shipping_state_data['status'] === "success") {
						$dataArray['recipient_shipping_state'] = $recipient_shipping_state_data['data']->state_id;
						$dataArray['recipient_shipping_state_name'] = $recipient_shipping_state_data['data']->state_name;
                    } else {
                        $errorflag = true;
                        array_push($currentItemError, "Invalid Recipient Shipping State.");
                    }
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Recipient Shipping State.");
                }

				$recipient_shipping_country = isset($data['R']) ? $data['R'] : '';
				if ($recipient_shipping_country != '') {

                    $recipient_shipping_country_data = $this->getCountryDetailByCountryCode($recipient_shipping_country);
                    if ($recipient_shipping_country_data['status'] === "success") {
						$dataArray['recipient_shipping_country'] = $recipient_shipping_country_data['data']->id;
                    } else {
                        $errorflag = true;
                        array_push($currentItemError, "Invalid Recipient Shipping Country.");
                    }
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Recipient Shipping Country.");
                }

				$recipient_shipping_vendor_type = isset($data['S']) ? $data['S'] : '';
				if ($recipient_shipping_vendor_type != '') {

					$dataVendorNameArrs = $this->get_row("select vendor_id, vendor_name from ".$this->tableNames['vendor_type']." where 1=1 AND UPPER(vendor_name) = '".strtoupper($recipient_shipping_vendor_type)."' AND status='1' AND is_deleted='0'");
                    if (!empty($dataVendorNameArrs) && isset($dataVendorNameArrs->vendor_id)) {
						$dataArray['recipient_shipping_vendor_type'] = $dataVendorNameArrs->vendor_id;
                    } else {
                        $errorflag = true;
                        array_push($currentItemError, "Invalid Recipient Shipping Vendor Type.");
                    }
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Recipient Shipping Vendor Type.");
                }

				$dataArray['recipient_shipping_gstin_number'] = isset($data['T']) ? $data['T'] : '';

                $item_name = isset($data['U']) ? trim($data['U']) : '';
                $item_hsncode = isset($data['V']) ? trim($data['V']) : '';
				$dataArray['item_quantity'] = isset($data['W']) ? round($data['W'], 2) : '';

				$dataArray['item_unit'] = isset($data['X']) ? $data['X'] : '';
				$item_unit =  $dataArray['item_unit'];
				
                $dataArray['item_rate'] = isset($data['Y']) ? round($data['Y'], 2) : 0.00;
				$item_rate = round($dataArray['item_rate'], 2);

                $dataArray['item_discount'] = isset($data['Z']) ? round($data['Z'], 2) : 0.00;
                $dataArray['advance_amount'] = isset($data['AA']) ? round($data['AA'], 2) : 0.00;
				
				if(!empty($item_name) && !empty($item_hsncode)) {

                    $checkClientMasterItem = $this->get_row("select cm.item_id, cm.item_name, cm.unit_price, cm.item_description, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, m.igst_tax_rate, m.csgt_tax_rate, m.sgst_tax_rate, m.cess_tax_rate, cm.item_unit from " . $this->tableNames['client_master_item'] . " as cm, " . $this->tableNames['item'] . " as m where 1=1 AND cm.item_category = m.item_id AND cm.item_name = '" . $item_name . "' && m.hsn_code = '" . $item_hsncode . "' AND cm.is_deleted='0' AND cm.added_by = '" . $this->sanitize($_SESSION['user_detail']['user_id']) . "'");
					if (!empty($checkClientMasterItem)) {

						$dataArray['item_id'] = $checkClientMasterItem->item_id;
                        $dataArray['item_name'] = $item_name;
                        $dataArray['item_hsncode'] = $item_hsncode;
                    } else {

						$masterItem = $this->get_row("select item_id, item_name, hsn_code from " . $this->tableNames['item'] . " where hsn_code='".$item_hsncode."' and is_deleted='0' AND status='1'");						
						if(!empty($masterItem)) {

							$masterUnit = $this->get_row("select unit_id from " . $this->tableNames['unit'] . " as u where u.unit_code='".$item_unit."' and u.is_deleted='0' AND u.status = '1'");
							if(!empty($masterUnit)) {
								$master_unit_id = $masterUnit->unit_id;
							} else {
								
								$masterNUnit = $this->get_row("select unit_id from " . $this->tableNames['unit'] . " as u where u.unit_code='NA' and u.is_deleted='0' AND u.status = '1'");
								if(!empty($masterNUnit)) {
									$master_unit_id = $masterNUnit->unit_id;
								} else {
									$master_unit_id = 0;
								}
							}

							$dataInsertItemArray['item_name'] = $item_name;
							$dataInsertItemArray['item_category'] = $masterItem->item_id;
							$dataInsertItemArray['unit_price'] = $item_rate;
							$dataInsertItemArray['item_unit'] = $master_unit_id;
							$dataInsertItemArray['status'] = '1';
							$dataInsertItemArray['added_by'] = $this->sanitize($_SESSION['user_detail']['user_id']);
							$dataInsertItemArray['added_date'] = date('Y-m-d H:i:s');

							if ($this->insert($this->tableNames['client_master_item'], $dataInsertItemArray)) {

								$iteminsertid = $this->getInsertID();
								$dataArray['item_id'] = $iteminsertid;
								$dataArray['item_name'] = $item_name;
								$dataArray['item_hsncode'] = $masterItem->hsn_code;
							} else {
								$errorflag = true;
								array_push($currentItemError, $this->getValMsg('failed'));
							}
						} else {
							$errorflag = true;
							array_push($currentItemError, "Description of Goods and HSN Code should be valid.");
						}
                    }
                } else {
					$errorflag = true;
					array_push($currentItemError, "Description of Goods and HSN Code should be valid.");
                }

				$dataArray['cgst_rate'] = isset($data['AB']) ? round($data['AB'], 3) : 0.000;
				$dataArray['sgst_rate'] = isset($data['AC']) ? round($data['AC'], 3) : 0.000;
				$dataArray['igst_rate'] = isset($data['AD']) ? round($data['AD'], 3) : 0.000;
				$dataArray['cess_rate'] = isset($data['AE']) ? round($data['AE'], 3) : 0.000;

				/* get current user data */
                $dataCurrentUserArr = $this->getUserDetailsById($this->sanitize($_SESSION['user_detail']['user_id']));

				if($dataCurrentUserArr['data']->kyc->state_id === $dataArray['supply_place']) {

					if($dataArray['cgst_rate'] != $dataArray['sgst_rate']) {
						$errorflag = true;
						array_push($currentItemError, "CGST and SGST rate should be same for item number.");
					}
				}

				/* check reference number */
				$referenceStatus = $this->checkPurchaseReferenceNumberExist($dataArray['reference_number'], $this->sanitize($_SESSION['user_detail']['user_id']));
				if($referenceStatus == true) {
					$errorflag = true;
					array_push($currentItemError, "You have already used this reference number.");
				}

				/* Invoice Description */
				$dataArray['description'] = isset($data['AF']) ? $data['AF'] : '';

                $invoiceErrors = $this->validateClientPurchaseInvoiceExcel($dataArray);
                if ($invoiceErrors !== true || !empty($currentItemError)) {

                    $errorflag = true;
                    if ($invoiceErrors === true) {
                        $invoiceErrors = array();
                    }
                    $invoiceErrors = array_merge($invoiceErrors, $currentItemError);
                    $invoiceErrors = implode(", ", $invoiceErrors);
                    $objPHPExcel->getActiveSheet()->SetCellValue('AG' . $rowKey, $invoiceErrors);
                }

				if ($errorflag === false) {

					/* create invoice array */
					$arrayKey = $dataArray['reference_number'];

					$invoiceArray[$arrayKey]['invoice_type'] = 'taxinvoice';
					$invoiceArray[$arrayKey]['invoice_nature'] = 'purchaseinvoice';
					$invoiceArray[$arrayKey]['reference_number'] = $dataArray['reference_number'];
					$invoiceArray[$arrayKey]['company_name'] = $dataCurrentUserArr['data']->kyc->name;
					$invoiceArray[$arrayKey]['company_address'] = $dataCurrentUserArr['data']->kyc->full_address;
					$invoiceArray[$arrayKey]['company_state'] = $dataCurrentUserArr['data']->kyc->state_id;
					$invoiceArray[$arrayKey]['company_gstin_number'] = $dataCurrentUserArr['data']->kyc->gstin_number;
					$invoiceArray[$arrayKey]['invoice_date'] = $dataArray['invoice_date'];
					$invoiceArray[$arrayKey]['supply_type'] = $dataArray['supply_type'];
					$invoiceArray[$arrayKey]['supply_place'] = $dataArray['supply_place'];
					$invoiceArray[$arrayKey]['supplier_billing_name'] = $dataArray['supplier_billing_name'];
					$invoiceArray[$arrayKey]['supplier_billing_company_name'] = $dataArray['supplier_billing_company_name'];
					$invoiceArray[$arrayKey]['supplier_billing_address'] = $dataArray['supplier_billing_address'];
					$invoiceArray[$arrayKey]['supplier_billing_state'] = $dataArray['supplier_billing_state'];
					$invoiceArray[$arrayKey]['supplier_billing_state_name'] = $dataArray['supplier_billing_state_name'];
					$invoiceArray[$arrayKey]['supplier_billing_country'] = $dataArray['supplier_billing_country'];
					$invoiceArray[$arrayKey]['supplier_billing_vendor_type'] = $dataArray['supplier_billing_vendor_type'];
					$invoiceArray[$arrayKey]['supplier_billing_gstin_number'] = $dataArray['supplier_billing_gstin_number'];
					$invoiceArray[$arrayKey]['recipient_shipping_name'] = $dataArray['recipient_shipping_name'];
					$invoiceArray[$arrayKey]['recipient_shipping_company_name'] = $dataArray['recipient_shipping_company_name'];
					$invoiceArray[$arrayKey]['recipient_shipping_address'] = $dataArray['recipient_shipping_address'];
					$invoiceArray[$arrayKey]['recipient_shipping_state'] = $dataArray['recipient_shipping_state'];
					$invoiceArray[$arrayKey]['recipient_shipping_state_name'] = $dataArray['recipient_shipping_state_name'];
					$invoiceArray[$arrayKey]['recipient_shipping_country'] = $dataArray['recipient_shipping_country'];
					$invoiceArray[$arrayKey]['recipient_shipping_vendor_type'] = $dataArray['recipient_shipping_vendor_type'];
					$invoiceArray[$arrayKey]['recipient_shipping_gstin_number'] = $dataArray['recipient_shipping_gstin_number'];

					$invoiceArray[$arrayKey]['advance_adjustment'] = $dataArray['advance_adjustment'];				
					if($dataArray['advance_adjustment'] == 1) {
						$invoiceArray[$arrayKey]['receipt_voucher_number'] = $dataArray['receipt_voucher_number'];
					}

					$invoiceArray[$arrayKey]['description'] = $dataArray['description'];

					//items
					$invoiceItemArray['item_id'] = $dataArray['item_id'];
					$invoiceItemArray['item_name'] = $dataArray['item_name'];
					$invoiceItemArray['item_hsncode'] = $dataArray['item_hsncode'];
					$invoiceItemArray['item_quantity'] = $dataArray['item_quantity'];
					$invoiceItemArray['item_unit'] = $dataArray['item_unit'];
					$invoiceItemArray['item_unit_price'] = $dataArray['item_rate'];
					$invoiceItemArray['item_discount'] = $dataArray['item_discount'];

					if($dataArray['advance_adjustment'] == 1) {
						$invoiceItemArray['advance_amount'] = $dataArray['advance_amount'];
					} else {
						$invoiceItemArray['advance_amount'] = 0.00;
					}

					$invoiceItemArray['cgst_rate'] = $dataArray['cgst_rate'];
					$invoiceItemArray['sgst_rate'] = $dataArray['sgst_rate'];
					$invoiceItemArray['igst_rate'] = $dataArray['igst_rate'];
					$invoiceItemArray['cess_rate'] = $dataArray['cess_rate'];

					$invoiceArray[$arrayKey]['items'][] = $invoiceItemArray;
				}
            }

            if ($errorflag === true) {

                $objPHPExcel->getActiveSheet()->SetCellValue('AG1', "Error Information");
                $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
                $objWriter->save($invoice_excel_dir_path);
                $this->setError($this->validationMessage['excelerror']);
                $resultArray = array("status" => "error", "excelurl" => $invoice_excel_url_path);
                return json_encode($resultArray);
            } else {

                foreach ($invoiceArray as $invoiceRow) {

                    $invoiceItemArray = array();
                    $invoiceTotalAmount = 0.00;
					$consolidateRate = 0.00;

                    foreach ($invoiceRow['items'] as $invoiceInnerRow) {

						$itemUnitPrice = (float) $invoiceInnerRow['item_unit_price'];
						$invoiceItemQuantity = (float) $invoiceInnerRow['item_quantity'];
						$invoiceItemDiscount = (float) $invoiceInnerRow['item_discount'];
						$invoiceItemAdvanceAmount = (float) $invoiceInnerRow['advance_amount'];

						$invoiceItemTotal = $invoiceItemQuantity * $itemUnitPrice;
						$invoiceItemDiscountAmount = ($invoiceItemDiscount / 100) * $invoiceItemTotal;
						$invoiceItemReduceAmount = $invoiceItemAdvanceAmount + $invoiceItemDiscountAmount;
						$invoiceItemTaxableAmount = $invoiceItemTotal - $invoiceItemReduceAmount;

						if($invoiceRow['company_state'] === $invoiceRow['supply_place']) {

							$itemCSGTTax = (float)$invoiceInnerRow['cgst_rate'];
							$itemSGSTTax = (float)$invoiceInnerRow['sgst_rate'];
							$itemIGSTTax = 0.00;
							$itemCESSTax = (float)$invoiceInnerRow['cess_rate'];
							$consolidateRate = $itemCSGTTax + $itemSGSTTax;

							$invoiceItemCSGTTaxAmount = ($itemCSGTTax/100) * $invoiceItemTaxableAmount;
							$invoiceItemSGSTTaxAmount = ($itemSGSTTax/100) * $invoiceItemTaxableAmount;
							$invoiceItemIGSTTaxAmount = 0.00;
							$invoiceItemCESSTaxAmount = ($itemCESSTax/100) * $invoiceItemTaxableAmount;
						} else {
							
							$itemCSGTTax = 0.00;
							$itemSGSTTax = 0.00;
							$itemIGSTTax = (float)$invoiceInnerRow['igst_rate'];
							$itemCESSTax = (float)$invoiceInnerRow['cess_rate'];
							$consolidateRate = $itemIGSTTax;

							$invoiceItemCSGTTaxAmount = 0.00;
							$invoiceItemSGSTTaxAmount = 0.00;
							$invoiceItemIGSTTaxAmount = ($itemIGSTTax/100) * $invoiceItemTaxableAmount;
							$invoiceItemCESSTaxAmount = ($itemCESSTax/100) * $invoiceItemTaxableAmount;
						}

						if ($invoiceRow['supply_type'] == "reversecharge") {

							$invoiceItemTotalAmount = $invoiceItemTaxableAmount;
							$invoiceTotalAmount += $invoiceItemTotalAmount;
						} else {

							$invoiceItemTotalAmount = ($invoiceItemTaxableAmount + $invoiceItemCSGTTaxAmount + $invoiceItemSGSTTaxAmount + $invoiceItemIGSTTaxAmount + $invoiceItemCESSTaxAmount);
							$invoiceTotalAmount += $invoiceItemTotalAmount;
						}

						$ItemArray = array(
							"item_id" => $invoiceInnerRow['item_id'],
							"item_name" => $invoiceInnerRow['item_name'],
							"item_hsncode" => $invoiceInnerRow['item_hsncode'],
							"item_quantity" => $invoiceItemQuantity,
							"item_unit" => $invoiceInnerRow['item_unit'],
							"item_unit_price" => $itemUnitPrice,
							"subtotal" => round($invoiceItemTotal, 2),
							"discount" => $invoiceItemDiscount,
							"advance_amount" => $invoiceItemAdvanceAmount,
							"taxable_subtotal" => round($invoiceItemTaxableAmount, 2),
							"cgst_rate" => $itemCSGTTax,
							"cgst_amount" => round($invoiceItemCSGTTaxAmount, 2),
							"sgst_rate" => $itemSGSTTax,
							"sgst_amount" => round($invoiceItemSGSTTaxAmount, 2),
							"igst_rate" => $itemIGSTTax,
							"igst_amount" => round($invoiceItemIGSTTaxAmount, 2),
							"cess_rate" => $itemCESSTax,
							"cess_amount" => round($invoiceItemCESSTaxAmount, 2),
							"consolidate_rate" => $consolidateRate,
							"total" => round($invoiceItemTotalAmount, 2),
							"status" => 1,
							"added_by" => $this->sanitize($_SESSION['user_detail']['user_id']),
							"added_date" => date('Y-m-d H:i:s')
						);

						array_push($invoiceItemArray, $ItemArray);
                    }

                    if (!empty($invoiceItemArray) && count($invoiceItemArray) > 0) {

                        $InsertArray['invoice_type'] = $invoiceRow['invoice_type'];
						$InsertArray['invoice_nature'] = $invoiceRow['invoice_nature'];
                        $InsertArray['reference_number'] = $invoiceRow['reference_number'];
                        $InsertArray['serial_number'] = $this->generatePurchaseInvoiceNumber($this->sanitize($_SESSION['user_detail']['user_id']));
                        $InsertArray['company_name'] = $invoiceRow['company_name'];
                        $InsertArray['company_address'] = $invoiceRow['company_address'];
                        $InsertArray['company_state'] = $invoiceRow['company_state'];
                        $InsertArray['company_gstin_number'] = $invoiceRow['company_gstin_number'];
                        $InsertArray['invoice_date'] = $invoiceRow['invoice_date'];
                        $InsertArray['supply_place'] = $invoiceRow['supply_place'];
						$InsertArray['supply_type'] = $invoiceRow['supply_type'];
                        $InsertArray['supplier_billing_name'] = $invoiceRow['supplier_billing_name'];
						$InsertArray['supplier_billing_company_name'] = $invoiceRow['supplier_billing_company_name'];
                        $InsertArray['supplier_billing_address'] = $invoiceRow['supplier_billing_address'];
                        $InsertArray['supplier_billing_state'] = $invoiceRow['supplier_billing_state'];
                        $InsertArray['supplier_billing_state_name'] = $invoiceRow['supplier_billing_state_name'];
						$InsertArray['supplier_billing_country'] = $invoiceRow['supplier_billing_country'];
						$InsertArray['supplier_billing_vendor_type'] = $invoiceRow['supplier_billing_vendor_type'];
                        $InsertArray['supplier_billing_gstin_number'] = $invoiceRow['supplier_billing_gstin_number'];
                        $InsertArray['recipient_shipping_name'] = $invoiceRow['recipient_shipping_name'];
                        $InsertArray['recipient_shipping_company_name'] = $invoiceRow['recipient_shipping_company_name'];
						$InsertArray['recipient_shipping_address'] = $invoiceRow['recipient_shipping_address'];
                        $InsertArray['recipient_shipping_state'] = $invoiceRow['recipient_shipping_state'];
                        $InsertArray['recipient_shipping_state_name'] = $invoiceRow['recipient_shipping_state_name'];
						$InsertArray['recipient_shipping_country'] = $invoiceRow['recipient_shipping_country'];
						$InsertArray['recipient_shipping_vendor_type'] = $invoiceRow['recipient_shipping_vendor_type'];
                        $InsertArray['recipient_shipping_gstin_number'] = $invoiceRow['recipient_shipping_gstin_number'];

						$InsertArray['advance_adjustment'] = $invoiceRow['advance_adjustment'];
						if($invoiceRow['advance_adjustment'] == 1) {
							$InsertArray['receipt_voucher_number'] = $invoiceRow['receipt_voucher_number'];
						}

						$InsertArray['description'] = $invoiceRow['description'];						
                        $InsertArray['invoice_total_value'] = number_format($invoiceTotalAmount, 2, '.', '');
                        $InsertArray['financial_year'] = $this->generateFinancialYear();
                        $InsertArray['status'] = 1;
						$InsertArray['created_from'] = 'E';
                        $InsertArray['added_by'] = $_SESSION['user_detail']['user_id'];
                        $InsertArray['added_date'] = date('Y-m-d H:i:s');

                        if ($this->insert($this->tableNames['client_purchase_invoice'], $InsertArray)) {

                            $insertid = $this->getInsertID();
                            $this->logMsg("Purchase Tax Invoice Added. ID : " . $insertid . ".", "client_create_purchase_invoice");

                            $processedInvoiceItemArray = array();
                            foreach ($invoiceItemArray as $itemArr) {

                                $itemArr['purchase_invoice_id'] = $insertid;
                                array_push($processedInvoiceItemArray, $itemArr);
                            }

                            if ($this->insertMultiple($this->tableNames['client_purchase_invoice_item'], $processedInvoiceItemArray)) {

                                $iteminsertid = $this->getInsertID();
                                $this->logMsg("Purchase Tax Invoice Item Added. ID : " . $iteminsertid . ".", "client_create_purchase_invoice_item");
                            }
                        }
                    }
                }

                $this->setSuccess($this->validationMessage['invoiceadded']);
                return true;
            }
        }
    }

	/* upload client purchase import invoice */
    public function uploadPurchaseClientImportInvoice() {

		$flag = true;
        $errorflag = false;
        $dataArray = array();
        $indexArray = array();
        $invoiceArray = array();
        $invoiceItemArray = array();
		$currentFinancialYear = $this->generateFinancialYear();

        if ($_FILES['invoice_xlsx']['name'] != '' && $_FILES['invoice_xlsx']['error'] == 0) {

            $invoice_excel = $this->imageUploads($_FILES['invoice_xlsx'], 'invoice-docs', 'upload', $this->allowExcelExt);
            if ($invoice_excel == FALSE) {
                return false;
            }

            $invoice_excel_dir_path = PROJECT_ROOT . UPLOAD_DIR . "/invoice-docs/" . $invoice_excel;
            $invoice_excel_url_path = PROJECT_URL . UPLOAD_DIR . "/invoice-docs/" . $invoice_excel;

            $objPHPExcel = PHPExcel_IOFactory::load($invoice_excel_dir_path);
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
			$sheetData = array_map('array_filter', $sheetData);
			$sheetData = array_filter($sheetData);

			foreach ($sheetData as $rowKey => $data) {

                if ($flag) {
                    $indexArray = $data;
                    $flag = false;
                    continue;
                }

                $currentItemError = array();
                $dataArray['reference_number'] = isset($data['A']) ? $data['A'] : '';

				$invoice_type = isset($data['B']) ? $data['B'] : '';
				if ($invoice_type != '' && strtoupper($invoice_type) === 'DEEMED IMPORT') {
                    $dataArray['invoice_type'] = "deemedimportinvoice";
                } else if ($invoice_type != '' && strtoupper($invoice_type) === 'IMPORT') {
					$dataArray['invoice_type'] = "importinvoice";
                } else if ($invoice_type != '' && strtoupper($invoice_type) === 'SEZ') {
                    $dataArray['invoice_type'] = "sezunitinvoice";
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Invoice Type.");
                }
				
				$supply_meant = isset($data['C']) ? $data['C'] : '';
				if ($supply_meant != '' && strtoupper($supply_meant) === 'WITH PAYMENT') {
                    $dataArray['import_supply_meant'] = "withpayment";
                } else if ($supply_meant != '' && strtoupper($supply_meant) === 'WITHOUT PAYMENT') {
                    $dataArray['import_supply_meant'] = "withoutpayment";
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Import Supply Meant.");
                }

				$dataArray['invoice_date'] = isset($data['D']) ? $data['D'] : '';

				$supply_place = isset($data['E']) ? $data['E'] : '';
                if ($supply_place != '') {

                    $supply_state_data = $this->getStateDetailByStateNameCode($supply_place);
                    if ($supply_state_data['status'] === "success") {
                        $dataArray['supply_place'] = $supply_state_data['data']->state_id;
                    } else {
                        $errorflag = true;
                        array_push($currentItemError, "Invalid Place Of Supply.");
                    }
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Place Of Supply.");
                }

                $advance_adjustment = isset($data['F']) ? $data['F'] : '';
                if ($advance_adjustment != '' && strtoupper($advance_adjustment) === 'Y') {
                    $dataArray['advance_adjustment'] = 1;
                } else if ($advance_adjustment != '' && strtoupper($advance_adjustment) === 'N') {
                    $dataArray['advance_adjustment'] = 0;
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Advance Adjustment.");
                }

				if($dataArray['advance_adjustment'] == 1) {

					$receipt_voucher_serial = isset($data['G']) ? $data['G'] : '';
					$dataReceiptVoucherArrs = $this->get_row("select purchase_invoice_id, serial_number, invoice_date, supply_place, is_canceled from ".$this->tableNames['client_purchase_invoice']." where 1=1 AND serial_number = '".$receipt_voucher_serial."' AND invoice_type = 'receiptvoucherinvoice' AND is_canceled='0' AND status='1' AND is_deleted='0' AND financial_year = '".$currentFinancialYear."' AND added_by = ".$this->sanitize($_SESSION['user_detail']['user_id']));
					if (!empty($dataReceiptVoucherArrs) && isset($dataReceiptVoucherArrs->purchase_invoice_id)) {
						$dataArray['receipt_voucher_number'] = $dataReceiptVoucherArrs->purchase_invoice_id;
                    } else {
                        $errorflag = true;
                        array_push($currentItemError, "Invalid Receipt Voucher.");
                    }
				}

                $dataArray['supplier_billing_name'] = isset($data['H']) ? $data['H'] : '';
				$dataArray['supplier_billing_company_name'] = isset($data['I']) ? $data['I'] : '';
                $dataArray['supplier_billing_address'] = isset($data['J']) ? $data['J'] : '';

				$supplier_billing_state = isset($data['K']) ? $data['K'] : '';
                if ($supplier_billing_state != '') {

                    $supplier_billing_state_data = $this->getStateDetailByStateNameCode($supplier_billing_state);
                    if ($supplier_billing_state_data['status'] === "success") {
						$dataArray['supplier_billing_state'] = $supplier_billing_state_data['data']->state_id;
						$dataArray['supplier_billing_state_name'] = $supplier_billing_state_data['data']->state_name;
                    } else {
                        $errorflag = true;
                        array_push($currentItemError, "Invalid Supplier Billing State.");
                    }
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Supplier Billing State.");
                }

				$supplier_billing_country = isset($data['L']) ? $data['L'] : '';
				if ($supplier_billing_country != '') {

                    $supplier_billing_country_data = $this->getCountryDetailByCountryCode($supplier_billing_country);
                    if ($supplier_billing_country_data['status'] === "success") {
						$dataArray['supplier_billing_country'] = $supplier_billing_country_data['data']->id;
                    } else {
                        $errorflag = true;
                        array_push($currentItemError, "Invalid Supplier Billing Country.");
                    }
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Supplier Billing Country.");
                }

				$supplier_billing_vendor_type = isset($data['M']) ? $data['M'] : '';
				if ($supplier_billing_vendor_type != '') {

					$dataVendorNameArrs = $this->get_row("select vendor_id, vendor_name from ".$this->tableNames['vendor_type']." where 1=1 AND UPPER(vendor_name) = '".strtoupper($supplier_billing_vendor_type)."' AND status='1' AND is_deleted='0'");
                    if (!empty($dataVendorNameArrs) && isset($dataVendorNameArrs->vendor_id)) {
						$dataArray['supplier_billing_vendor_type'] = $dataVendorNameArrs->vendor_id;
                    } else {
                        $errorflag = true;
                        array_push($currentItemError, "Invalid Supplier Billing Vendor Type.");
                    }
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Supplier Billing Vendor Type.");
                }

				$dataArray['supplier_billing_gstin_number'] = isset($data['N']) ? $data['N'] : '';

				$dataArray['recipient_shipping_name'] = isset($data['O']) ? $data['O'] : '';
				$dataArray['recipient_shipping_company_name'] = isset($data['P']) ? $data['P'] : '';
                $dataArray['recipient_shipping_address'] = isset($data['Q']) ? $data['Q'] : '';

				$recipient_shipping_state = isset($data['R']) ? $data['R'] : '';
                if ($recipient_shipping_state != '') {

                    $recipient_shipping_state_data = $this->getStateDetailByStateNameCode($recipient_shipping_state);
                    if ($recipient_shipping_state_data['status'] === "success") {
						$dataArray['recipient_shipping_state'] = $recipient_shipping_state_data['data']->state_id;
						$dataArray['recipient_shipping_state_name'] = $recipient_shipping_state_data['data']->state_name;
                    } else {
                        $errorflag = true;
                        array_push($currentItemError, "Invalid Recipient Shipping State.");
                    }
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Recipient Shipping State.");
                }

				$recipient_shipping_country = isset($data['S']) ? $data['S'] : '';
				if ($recipient_shipping_country != '') {

                    $recipient_shipping_country_data = $this->getCountryDetailByCountryCode($recipient_shipping_country);
                    if ($recipient_shipping_country_data['status'] === "success") {
						$dataArray['recipient_shipping_country'] = $recipient_shipping_country_data['data']->id;
                    } else {
                        $errorflag = true;
                        array_push($currentItemError, "Invalid Recipient Shipping Country.");
                    }
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Recipient Shipping Country.");
                }

				$recipient_shipping_vendor_type = isset($data['T']) ? $data['T'] : '';
				if ($recipient_shipping_vendor_type != '') {

					$dataVendorNameArrs = $this->get_row("select vendor_id, vendor_name from ".$this->tableNames['vendor_type']." where 1=1 AND UPPER(vendor_name) = '".strtoupper($recipient_shipping_vendor_type)."' AND status='1' AND is_deleted='0'");
                    if (!empty($dataVendorNameArrs) && isset($dataVendorNameArrs->vendor_id)) {
						$dataArray['recipient_shipping_vendor_type'] = $dataVendorNameArrs->vendor_id;
                    } else {
                        $errorflag = true;
                        array_push($currentItemError, "Invalid Recipient Shipping Vendor Type.");
                    }
                } else {
					$errorflag = true;
					array_push($currentItemError, "Invalid Recipient Shipping Vendor Type.");
                }

				$dataArray['recipient_shipping_gstin_number'] = isset($data['U']) ? $data['U'] : '';

				if($dataArray['invoice_type'] == "importinvoice") {

					$dataArray['import_bill_number'] = isset($data['V']) ? $data['V'] : '';
					$dataArray['import_bill_port_code'] = isset($data['W']) ? $data['W'] : '';
					$dataArray['import_bill_date'] = isset($data['X']) ? $data['X'] : '';
				}

                $item_name = isset($data['Y']) ? trim($data['Y']) : '';
                $item_hsncode = isset($data['Z']) ? trim($data['Z']) : '';
				$dataArray['item_quantity'] = isset($data['AA']) ? round($data['AA'], 2) : '';

				$dataArray['item_unit'] = isset($data['AB']) ? $data['AB'] : '';
				$item_unit =  $dataArray['item_unit'];

                $dataArray['item_rate'] = isset($data['AC']) ? round($data['AC'], 2) : 0.00;
				$item_rate = round($dataArray['item_rate'], 2);

                $dataArray['item_discount'] = isset($data['AD']) ? round($data['AD'], 2) : 0.00;
                $dataArray['advance_amount'] = isset($data['AE']) ? round($data['AE'], 2) : 0.00;

				if(!empty($item_name) && !empty($item_hsncode)) {

                    $checkClientMasterItem = $this->get_row("select cm.item_id, cm.item_name, cm.unit_price, cm.item_description, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, m.igst_tax_rate, m.csgt_tax_rate, m.sgst_tax_rate, m.cess_tax_rate, cm.item_unit from " . $this->tableNames['client_master_item'] . " as cm, " . $this->tableNames['item'] . " as m where 1=1 AND cm.item_category = m.item_id AND cm.item_name = '" . $item_name . "' && m.hsn_code = '" . $item_hsncode . "' AND cm.is_deleted='0' AND cm.added_by = '" . $this->sanitize($_SESSION['user_detail']['user_id']) . "'");
					if (!empty($checkClientMasterItem)) {

						$dataArray['item_id'] = $checkClientMasterItem->item_id;
                        $dataArray['item_name'] = $item_name;
                        $dataArray['item_hsncode'] = $item_hsncode;
                    } else {

						$masterItem = $this->get_row("select item_id, item_name, hsn_code from " . $this->tableNames['item'] . " where hsn_code='".$item_hsncode."' and is_deleted='0' AND status='1'");						
						if(!empty($masterItem)) {

							$masterUnit = $this->get_row("select unit_id from " . $this->tableNames['unit'] . " as u where u.unit_code='".$item_unit."' and u.is_deleted='0' AND u.status = '1'");
							if(!empty($masterUnit)) {
								$master_unit_id = $masterUnit->unit_id;
							} else {
								
								$masterNUnit = $this->get_row("select unit_id from " . $this->tableNames['unit'] . " as u where u.unit_code='NA' and u.is_deleted='0' AND u.status = '1'");
								if(!empty($masterNUnit)) {
									$master_unit_id = $masterNUnit->unit_id;
								} else {
									$master_unit_id = 0;
								}
							}

							$dataInsertItemArray['item_name'] = $item_name;
							$dataInsertItemArray['item_category'] = $masterItem->item_id;
							$dataInsertItemArray['unit_price'] = $item_rate;
							$dataInsertItemArray['item_unit'] = $master_unit_id;
							$dataInsertItemArray['status'] = '1';
							$dataInsertItemArray['added_by'] = $this->sanitize($_SESSION['user_detail']['user_id']);
							$dataInsertItemArray['added_date'] = date('Y-m-d H:i:s');

							if ($this->insert($this->tableNames['client_master_item'], $dataInsertItemArray)) {

								$iteminsertid = $this->getInsertID();
								$dataArray['item_id'] = $iteminsertid;
								$dataArray['item_name'] = $item_name;
								$dataArray['item_hsncode'] = $masterItem->hsn_code;
							} else {
								$errorflag = true;
								array_push($currentItemError, $this->getValMsg('failed'));
							}
						} else {
							$errorflag = true;
							array_push($currentItemError, "Description of Goods and HSN Code should be valid.");
						}
                    }
                } else {
					$errorflag = true;
					array_push($currentItemError, "Description of Goods and HSN Code should be valid.");
                }

				$dataArray['igst_rate'] = isset($data['AF']) ? round($data['AF'], 3) : 0.000;
				$dataArray['cess_rate'] = isset($data['AG']) ? round($data['AG'], 3) : 0.000;

				/* get current user data */
                $dataCurrentUserArr = $this->getUserDetailsById($this->sanitize($_SESSION['user_detail']['user_id']));

				/* check reference number */
				$referenceStatus = $this->checkPurchaseReferenceNumberExist($dataArray['reference_number'], $this->sanitize($_SESSION['user_detail']['user_id']));
				if($referenceStatus == true) {
					$errorflag = true;
					array_push($currentItemError, "You have already used this reference number.");
				}

				/* Invoice Description */
				$dataArray['description'] = isset($data['AH']) ? $data['AH'] : '';

                $invoiceErrors = $this->validateClientPurchaseInvoiceExcel($dataArray);
				if ($invoiceErrors !== true || !empty($currentItemError)) {

					$errorflag = true;
					if ($invoiceErrors === true) {
						$invoiceErrors = array();
					}
					$invoiceErrors = array_merge($invoiceErrors, $currentItemError);
					$invoiceErrors = implode(", ", $invoiceErrors);
					$objPHPExcel->getActiveSheet()->SetCellValue('AI' . $rowKey, $invoiceErrors);
				}

				if ($errorflag === false) {
				
					/* create invoice array */
					$arrayKey = $dataArray['reference_number'];

					$invoiceArray[$arrayKey]['invoice_type'] = $dataArray['invoice_type'];
					$invoiceArray[$arrayKey]['invoice_nature'] = 'purchaseinvoice';
					$invoiceArray[$arrayKey]['reference_number'] = $dataArray['reference_number'];
					$invoiceArray[$arrayKey]['company_name'] = $dataCurrentUserArr['data']->kyc->name;
					$invoiceArray[$arrayKey]['company_address'] = $dataCurrentUserArr['data']->kyc->full_address;
					$invoiceArray[$arrayKey]['company_state'] = $dataCurrentUserArr['data']->kyc->state_id;
					$invoiceArray[$arrayKey]['company_gstin_number'] = $dataCurrentUserArr['data']->kyc->gstin_number;
					$invoiceArray[$arrayKey]['invoice_date'] = $dataArray['invoice_date'];
					$invoiceArray[$arrayKey]['import_supply_meant'] = $dataArray['import_supply_meant'];
					$invoiceArray[$arrayKey]['supply_place'] = $dataArray['supply_place'];
					$invoiceArray[$arrayKey]['supplier_billing_name'] = $dataArray['supplier_billing_name'];
					$invoiceArray[$arrayKey]['supplier_billing_company_name'] = $dataArray['supplier_billing_company_name'];
					$invoiceArray[$arrayKey]['supplier_billing_address'] = $dataArray['supplier_billing_address'];
					$invoiceArray[$arrayKey]['supplier_billing_state'] = $dataArray['supplier_billing_state'];
					$invoiceArray[$arrayKey]['supplier_billing_state_name'] = $dataArray['supplier_billing_state_name'];
					$invoiceArray[$arrayKey]['supplier_billing_country'] = $dataArray['supplier_billing_country'];
					$invoiceArray[$arrayKey]['supplier_billing_vendor_type'] = $dataArray['supplier_billing_vendor_type'];
					$invoiceArray[$arrayKey]['supplier_billing_gstin_number'] = $dataArray['supplier_billing_gstin_number'];
					$invoiceArray[$arrayKey]['recipient_shipping_name'] = $dataArray['recipient_shipping_name'];
					$invoiceArray[$arrayKey]['recipient_shipping_company_name'] = $dataArray['recipient_shipping_company_name'];
					$invoiceArray[$arrayKey]['recipient_shipping_address'] = $dataArray['recipient_shipping_address'];
					$invoiceArray[$arrayKey]['recipient_shipping_state'] = $dataArray['recipient_shipping_state'];
					$invoiceArray[$arrayKey]['recipient_shipping_state_name'] = $dataArray['recipient_shipping_state_name'];
					$invoiceArray[$arrayKey]['recipient_shipping_country'] = $dataArray['recipient_shipping_country'];
					$invoiceArray[$arrayKey]['recipient_shipping_vendor_type'] = $dataArray['recipient_shipping_vendor_type'];
					$invoiceArray[$arrayKey]['recipient_shipping_gstin_number'] = $dataArray['recipient_shipping_gstin_number'];

					if($dataArray['invoice_type'] == "importinvoice") {
						$invoiceArray[$arrayKey]['import_bill_number'] = $dataArray['import_bill_number'];
						$invoiceArray[$arrayKey]['import_bill_port_code'] = $dataArray['import_bill_port_code'];
						$invoiceArray[$arrayKey]['import_bill_date'] = $dataArray['import_bill_date'];
					}

					$invoiceArray[$arrayKey]['advance_adjustment'] = $dataArray['advance_adjustment'];
					if($dataArray['advance_adjustment'] == 1) {
						$invoiceArray[$arrayKey]['receipt_voucher_number'] = $dataArray['receipt_voucher_number'];
					}

					$invoiceArray[$arrayKey]['description'] = $dataArray['description'];

					//items
					$invoiceItemArray['item_id'] = $dataArray['item_id'];
					$invoiceItemArray['item_name'] = $dataArray['item_name'];
					$invoiceItemArray['item_hsncode'] = $dataArray['item_hsncode'];
					$invoiceItemArray['item_quantity'] = $dataArray['item_quantity'];
					$invoiceItemArray['item_unit'] = $dataArray['item_unit'];
					$invoiceItemArray['item_unit_price'] = $dataArray['item_rate'];
					$invoiceItemArray['item_discount'] = $dataArray['item_discount'];

					if($dataArray['advance_adjustment'] == 1) {
						$invoiceItemArray['advance_amount'] = $dataArray['advance_amount'];
					} else {
						$invoiceItemArray['advance_amount'] = 0.00;
					}

					$invoiceItemArray['igst_rate'] = $dataArray['igst_rate'];
					$invoiceItemArray['cess_rate'] = $dataArray['cess_rate'];

					$invoiceArray[$arrayKey]['items'][] = $invoiceItemArray;
				}
            }

			if ($errorflag === true) {

				$objPHPExcel->getActiveSheet()->SetCellValue('AI1', "Error Information");
				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
				$objWriter->save($invoice_excel_dir_path);
				$this->setError($this->validationMessage['excelerror']);
				$resultArray = array("status" => "error", "excelurl" => $invoice_excel_url_path);
				return json_encode($resultArray);
			} else {

                foreach ($invoiceArray as $invoiceRow) {

                    $invoiceItemArray = array();
                    $invoiceTotalAmount = 0.00;
					$consolidateRate = 0.00;

                    foreach ($invoiceRow['items'] as $invoiceInnerRow) {

						$itemUnitPrice = (float) $invoiceInnerRow['item_unit_price'];
						$invoiceItemQuantity = (float) $invoiceInnerRow['item_quantity'];
						$invoiceItemDiscount = (float) $invoiceInnerRow['item_discount'];
						$invoiceItemAdvanceAmount = (float) $invoiceInnerRow['advance_amount'];

						$invoiceItemTotal = $invoiceItemQuantity * $itemUnitPrice;
						$invoiceItemDiscountAmount = ($invoiceItemDiscount / 100) * $invoiceItemTotal;
						$invoiceItemReduceAmount = $invoiceItemAdvanceAmount + $invoiceItemDiscountAmount;
						$invoiceItemTaxableAmount = $invoiceItemTotal - $invoiceItemReduceAmount;

						if($invoiceRow['import_supply_meant'] === "withpayment") {

							$itemCSGTTax = 0.00;
							$itemSGSTTax = 0.00;
							$itemIGSTTax = (float)$invoiceInnerRow['igst_rate'];
							$itemCESSTax = (float)$invoiceInnerRow['cess_rate'];
							$consolidateRate = $itemIGSTTax;

							$invoiceItemCSGTTaxAmount = 0.00;
							$invoiceItemSGSTTaxAmount = 0.00;
							$invoiceItemIGSTTaxAmount = ($itemIGSTTax/100) * $invoiceItemTaxableAmount;
							$invoiceItemCESSTaxAmount = ($itemCESSTax/100) * $invoiceItemTaxableAmount;
						} else {
							
							$itemCSGTTax = 0.00;
							$itemSGSTTax = 0.00;
							$itemIGSTTax = 0.00;
							$itemCESSTax = (float)$invoiceInnerRow['cess_rate'];
							$consolidateRate = $itemIGSTTax;

							$invoiceItemCSGTTaxAmount = 0.00;
							$invoiceItemSGSTTaxAmount = 0.00;
							$invoiceItemIGSTTaxAmount = 0.00;
							$invoiceItemCESSTaxAmount = ($itemCESSTax/100) * $invoiceItemTaxableAmount;
						}

						$invoiceItemTotalAmount = ($invoiceItemTaxableAmount + $invoiceItemCSGTTaxAmount + $invoiceItemSGSTTaxAmount + $invoiceItemIGSTTaxAmount + $invoiceItemCESSTaxAmount);
						$invoiceTotalAmount += $invoiceItemTotalAmount;

						$ItemArray = array(
							"item_id" => $invoiceInnerRow['item_id'],
							"item_name" => $invoiceInnerRow['item_name'],
							"item_hsncode" => $invoiceInnerRow['item_hsncode'],
							"item_quantity" => $invoiceItemQuantity,
							"item_unit" => $invoiceInnerRow['item_unit'],
							"item_unit_price" => $itemUnitPrice,
							"subtotal" => round($invoiceItemTotal, 2),
							"discount" => $invoiceItemDiscount,
							"advance_amount" => $invoiceItemAdvanceAmount,
							"taxable_subtotal" => round($invoiceItemTaxableAmount, 2),
							"cgst_rate" => $itemCSGTTax,
							"cgst_amount" => round($invoiceItemCSGTTaxAmount, 2),
							"sgst_rate" => $itemSGSTTax,
							"sgst_amount" => round($invoiceItemSGSTTaxAmount, 2),
							"igst_rate" => $itemIGSTTax,
							"igst_amount" => round($invoiceItemIGSTTaxAmount, 2),
							"cess_rate" => $itemCESSTax,
							"cess_amount" => round($invoiceItemCESSTaxAmount, 2),
							"consolidate_rate" => $consolidateRate,
							"total" => round($invoiceItemTotalAmount, 2),
							"status" => 1,
							"added_by" => $this->sanitize($_SESSION['user_detail']['user_id']),
							"added_date" => date('Y-m-d H:i:s')
						);

						array_push($invoiceItemArray, $ItemArray);
                    }

                    if (!empty($invoiceItemArray) && count($invoiceItemArray) > 0) {

                        $InsertArray['invoice_type'] = $invoiceRow['invoice_type'];
						$InsertArray['invoice_nature'] = $invoiceRow['invoice_nature'];
                        $InsertArray['reference_number'] = $invoiceRow['reference_number'];
                        $InsertArray['serial_number'] = $this->generatePurchaseInvoiceNumber($this->sanitize($_SESSION['user_detail']['user_id']));
                        $InsertArray['company_name'] = $invoiceRow['company_name'];
                        $InsertArray['company_address'] = $invoiceRow['company_address'];
                        $InsertArray['company_state'] = $invoiceRow['company_state'];
                        $InsertArray['company_gstin_number'] = $invoiceRow['company_gstin_number'];
                        $InsertArray['invoice_date'] = $invoiceRow['invoice_date'];
                        $InsertArray['supply_place'] = $invoiceRow['supply_place'];
						$InsertArray['import_supply_meant'] = $invoiceRow['import_supply_meant'];
						$InsertArray['supplier_billing_name'] = $invoiceRow['supplier_billing_name'];
						$InsertArray['supplier_billing_company_name'] = $invoiceRow['supplier_billing_company_name'];
						$InsertArray['supplier_billing_address'] = $invoiceRow['supplier_billing_address'];
						$InsertArray['supplier_billing_state'] = $invoiceRow['supplier_billing_state'];
						$InsertArray['supplier_billing_state_name'] = $invoiceRow['supplier_billing_state_name'];
						$InsertArray['supplier_billing_country'] = $invoiceRow['supplier_billing_country'];
						$InsertArray['supplier_billing_vendor_type'] = $invoiceRow['supplier_billing_vendor_type'];
						$InsertArray['supplier_billing_gstin_number'] = $invoiceRow['supplier_billing_gstin_number'];
						$InsertArray['recipient_shipping_name'] = $invoiceRow['recipient_shipping_name'];
						$InsertArray['recipient_shipping_company_name'] = $invoiceRow['recipient_shipping_company_name'];
						$InsertArray['recipient_shipping_address'] = $invoiceRow['recipient_shipping_address'];
						$InsertArray['recipient_shipping_state'] = $invoiceRow['recipient_shipping_state'];
						$InsertArray['recipient_shipping_state_name'] = $invoiceRow['recipient_shipping_state_name'];
						$InsertArray['recipient_shipping_country'] = $invoiceRow['recipient_shipping_country'];
						$InsertArray['recipient_shipping_vendor_type'] = $invoiceRow['recipient_shipping_vendor_type'];
						$InsertArray['recipient_shipping_gstin_number'] = $invoiceRow['recipient_shipping_gstin_number'];

						if($invoiceRow['invoice_type'] == "importinvoice") {
							$InsertArray['import_bill_number'] = $invoiceRow['import_bill_number'];
							$InsertArray['import_bill_port_code'] = $invoiceRow['import_bill_port_code'];
							$InsertArray['import_bill_date'] = $invoiceRow['import_bill_date'];
						}

                        $InsertArray['advance_adjustment'] = $invoiceRow['advance_adjustment'];
						if($invoiceRow['advance_adjustment'] == 1) {
							$InsertArray['receipt_voucher_number'] = $invoiceRow['receipt_voucher_number'];
						}

						$InsertArray['description'] = $invoiceRow['description'];						
						$InsertArray['invoice_total_value'] = number_format($invoiceTotalAmount, 2, '.', '');
						$InsertArray['financial_year'] = $this->generateFinancialYear();
						$InsertArray['status'] = 1;
						$InsertArray['created_from'] = 'E';
						$InsertArray['added_by'] = $_SESSION['user_detail']['user_id'];
						$InsertArray['added_date'] = date('Y-m-d H:i:s');

                        if ($this->insert($this->tableNames['client_purchase_invoice'], $InsertArray)) {

                            $insertid = $this->getInsertID();
                            $this->logMsg("Purchase Import Tax Invoice Added. ID : " . $insertid . ".", "client_create_purchase_invoice");

                            $processedInvoiceItemArray = array();
                            foreach ($invoiceItemArray as $itemArr) {

                                $itemArr['purchase_invoice_id'] = $insertid;
                                array_push($processedInvoiceItemArray, $itemArr);
                            }

                            if ($this->insertMultiple($this->tableNames['client_purchase_invoice_item'], $processedInvoiceItemArray)) {

                                $iteminsertid = $this->getInsertID();
                                $this->logMsg("Purchase Import Tax Invoice Item Added. ID : " . $iteminsertid . ".", "client_create_purchase_invoice_item");
                            }
                        }
                    }
                }

                $this->setSuccess($this->validationMessage['invoiceadded']);
                return true;
            }
        }
    }

	/* generate invoice html */
	public function generatePurchaseInvoiceHtml($invoiceid) {

		$currentFinancialYear = $this->generateFinancialYear();

		/* get current user data */
		$dataCurrentUserArr = $this->getUserDetailsById($this->sanitize($_SESSION['user_detail']['user_id']));

		$invoiceData = $this->get_results("select 
												ci.*, 
												cii.purchase_invoice_item_id, 
												cii.item_id, 
												cii.item_name, 
												cii.item_hsncode, 
												cii.item_description, 
												cii.item_quantity, 
												cii.item_unit, 
												cii.item_unit_price, 
												cii.subtotal, 
												cii.discount, 
												cii.advance_amount, 
												cii.taxable_subtotal, 
												cii.cgst_rate, 
												cii.cgst_amount, 
												cii.sgst_rate, 
												cii.sgst_amount, 
												cii.igst_rate, 
												cii.igst_amount, 
												cii.cess_rate, 
												cii.cess_amount, 
												cii.total 
												from 
												" . $this->tableNames['client_purchase_invoice'] ." as ci INNER JOIN " . $this->tableNames['client_purchase_invoice_item'] ." as cii ON ci.purchase_invoice_id = cii.purchase_invoice_id where ci.purchase_invoice_id = ".$invoiceid." AND ci.invoice_type IN('taxinvoice','importinvoice','sezunitinvoice','deemedimportinvoice') AND ci.added_by = '".$this->sanitize($_SESSION['user_detail']['user_id'])."' AND cii.added_by = '".$this->sanitize($_SESSION['user_detail']['user_id'])."' AND ci.is_deleted='0' AND cii.is_deleted='0'");
		if (empty($invoiceData)) {
			return false;
		}

		$dataThemeSettingArr = $this->getUserThemeSetting($this->sanitize($_SESSION['user_detail']['user_id']));

		$mpdfHtml = '';
		$mpdfHtml .= '<div style="margin:auto;font-size:16px;line-height:24px;color:#555;">';
			$mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;" cellpadding="0" cellspacing="0">';
				$mpdfHtml .= '<tr>';
					$mpdfHtml .= '<td colspan="2" style="vertical-align:top;">';
						$mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;">';
							$mpdfHtml .= '<tr>';
								$mpdfHtml .= '<td style="font-size:45px;line-height:45px;color:#333;padding:5px;vertical-align:top;padding-bottom:20px;">';

									if (isset($dataThemeSettingArr['data']->theme_logo) && $dataThemeSettingArr['data']->theme_logo != "") {
										$mpdfHtml .= '<img src="upload/theme-logo/' . $dataThemeSettingArr['data']->theme_logo . '" style="width:100%;max-width:300px;">';
									} else {
										$mpdfHtml .= '<img src="image/gst-k-logo.png" style="width:100%;max-width:300px;">';
									}

								$mpdfHtml .= '</td>';

								if($invoiceData[0]->invoice_type == "importinvoice") { $invoiceType = "Import Invoice"; } 
								else if($invoiceData[0]->invoice_type == "sezunitinvoic") { $invoiceType = "SEZ Unit Invoice"; } 
								else if($invoiceData[0]->invoice_type == "deemedimportinvoice") { $invoiceType = "Deemed Import Invoice"; } 
								else { $invoiceType = "Tax Invoice"; }
								
								$mpdfHtml .= '<td style="vertical-align:top;text-align:right;padding-bottom:20px;">';
									$mpdfHtml .= '<b>Invoice #</b>: ' . $invoiceData[0]->serial_number . '<br>';
									$mpdfHtml .= '<b>Reference #</b>: ' . $invoiceData[0]->reference_number . '<br>';
									$mpdfHtml .= '<b>Type:</b> ' . $invoiceType . '<br>';
									$mpdfHtml .= '<b>Nature:</b> Purchase Invoice<br>';
									$mpdfHtml .= '<b>Invoice Date:</b>' . $invoiceData[0]->invoice_date;
								$mpdfHtml .= '</td>';

							$mpdfHtml .= '</tr>';
						$mpdfHtml .= '</table>';
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';

				$supply_place_data = $this->getStateDetailByStateId($invoiceData[0]->supply_place);

				$mpdfHtml .= '<tr>';
					$mpdfHtml .= '<td colspan="2" style="vertical-align:top;">';
						$mpdfHtml .= '<table style="width:100%;line-height:inherit;">';
							$mpdfHtml .= '<tr>';
								$mpdfHtml .= '<td style="text-align:left;vertical-align:top;padding-bottom:20px;width:48%;padding-right:2%;">';
									$mpdfHtml .= $invoiceData[0]->company_name . '<br>';
									$mpdfHtml .= $invoiceData[0]->company_address . '<br>';
									if(!empty($invoiceData[0]->company_email)) { $mpdfHtml .= '<b>Email:</b> ' . $invoiceData[0]->company_email . '<br>'; }
									if(!empty($invoiceData[0]->company_phone_number)) { $mpdfHtml .= '<b>Phone:</b> ' . $invoiceData[0]->company_phone_number . '<br>'; }
									$panFromGTIN = substr(substr($invoiceData[0]->company_gstin_number, 2), 0, -3);
									$mpdfHtml .= '<b>PAN:</b> ' . $panFromGTIN  . '<br>';
									$mpdfHtml .= '<b>GSTIN:</b> ' . $invoiceData[0]->company_gstin_number;
								$mpdfHtml .= '</td>';

								$mpdfHtml .= '<td style="vertical-align:top;text-align:right;padding-bottom:20px;width:48%;padding-left:2%;">';

									if($invoiceData[0]->invoice_type === "importinvoice") {
			
										if($invoiceData[0]->import_supply_meant == "withpayment") { $importSupplyMeant = "Payment of Integrated Tax"; } 
										else { $importSupplyMeant = "Without Payment of Integrated Tax"; }

										$mpdfHtml .= '<b>Import Supply Meant:</b> ' . $importSupplyMeant . '<br>';
										
										if (isset($invoiceData[0]->supply_place) && $invoiceData[0]->supply_place > 0) {
											if($supply_place_data['data']->state_tin == 97) {
												$mpdfHtml .= '<b>Place Of Supply:</b> ' . $supply_place_data['data']->state_name . '<br>';
											} else {
												$mpdfHtml .= '<b>Place Of Supply:</b> ' . $supply_place_data['data']->state_name . '(' . $supply_place_data['data']->state_tin . ')' . '<br>';
											}
										}

										if ($invoiceData[0]->is_canceled == 1) { $mpdfHtml .= '<b>Canceled Invoice:</b> Canceled <br>'; }
										if ($invoiceData[0]->advance_adjustment == 1) { $mpdfHtml .= '<b>Advance Adjustment:</b> Yes <br>'; }

										if ($invoiceData[0]->advance_adjustment == 1) {

											$receiptVoucher = $this->get_row("select purchase_invoice_id, serial_number, invoice_date, supply_place, is_canceled from " . $this->tableNames['client_purchase_invoice'] . " where invoice_type = 'receiptvoucherinvoice' AND invoice_id = " . $invoiceData[0]->receipt_voucher_number . " AND is_deleted='0' AND financial_year = '" . $currentFinancialYear . "' AND added_by = " . $this->sanitize($_SESSION['user_detail']['user_id']) . " order by serial_number asc");
											if ($receiptVoucher) {
												$mpdfHtml .= '<b>Receipt Voucher:</b> ' . $receiptVoucher->serial_number . '<br>';
											}
										}
										
										$mpdfHtml .= '<b>Import Bill Number:</b> ' . $invoiceData[0]->import_bill_number . '<br>';
										$mpdfHtml .= '<b>Import Bill Date:</b> ' . $invoiceData[0]->import_bill_date . '<br>';
										$mpdfHtml .= '<b>Import Bill Port Code:</b> ' . $invoiceData[0]->import_bill_port_code;
									
									} else if($invoiceData[0]->invoice_type === "sezunitinvoice" || $invoiceData[0]->invoice_type === "deemedimportinvoice") {

										if($invoiceData[0]->import_supply_meant == "withpayment") { $importSupplyMeant = "Payment of Integrated Tax"; } 
										else { $importSupplyMeant = "Without Payment of Integrated Tax"; }
										
										$mpdfHtml .= '<b>Import Supply Meant:</b> ' . $importSupplyMeant . '<br>';
										
										if (isset($invoiceData[0]->supply_place) && $invoiceData[0]->supply_place > 0) {
											if($supply_place_data['data']->state_tin == 97) {
												$mpdfHtml .= '<b>Place Of Supply:</b> ' . $supply_place_data['data']->state_name . '<br>';
											} else {
												$mpdfHtml .= '<b>Place Of Supply:</b> ' . $supply_place_data['data']->state_name . '(' . $supply_place_data['data']->state_tin . ')' . '<br>';
											}
										}

										if ($invoiceData[0]->is_canceled == 1) { $mpdfHtml .= '<b>Canceled Invoice:</b> Canceled <br>'; }
										if ($invoiceData[0]->advance_adjustment == 1) { $mpdfHtml .= '<b>Advance Adjustment:</b> Yes <br>'; }

										if ($invoiceData[0]->advance_adjustment == 1) {

											$receiptVoucher = $this->get_row("select purchase_invoice_id, serial_number, invoice_date, supply_place, is_canceled from " . $this->tableNames['client_purchase_invoice'] . " where invoice_type = 'receiptvoucherinvoice' AND invoice_id = " . $invoiceData[0]->receipt_voucher_number . " AND is_deleted='0' AND financial_year = '" . $currentFinancialYear . "' AND added_by = " . $this->sanitize($_SESSION['user_detail']['user_id']) . " order by serial_number asc");
											if ($receiptVoucher) {
												$mpdfHtml .= '<b>Receipt Voucher:</b> ' . $receiptVoucher->serial_number;
											}
										}

									} else {

										if($invoiceData[0]->supply_type == "reversecharge") { $supplyType = "Reverse Charge"; } 
										else { $supplyType = "Normal"; }

										$mpdfHtml .= '<b>Supply Type:</b>' . $supplyType . '<br>';
										if (isset($invoiceData[0]->supply_place) && $invoiceData[0]->supply_place > 0) {
											if($supply_place_data['data']->state_tin == 97) {
												$mpdfHtml .= '<b>Place Of Supply:</b> ' . $supply_place_data['data']->state_name . '<br>';
											} else {
												$mpdfHtml .= '<b>Place Of Supply:</b> ' . $supply_place_data['data']->state_name . '(' . $supply_place_data['data']->state_tin . ')' . '<br>';
											}
										}

										if ($invoiceData[0]->is_canceled == 1) { $mpdfHtml .= '<b>Canceled Invoice:</b> Canceled <br>'; }
										if ($invoiceData[0]->advance_adjustment == 1) { $mpdfHtml .= '<b>Advance Adjustment:</b> Yes <br>'; }

										if ($invoiceData[0]->advance_adjustment == 1) {

											$receiptVoucher = $this->get_row("select purchase_invoice_id, serial_number, invoice_date, supply_place, is_canceled from " . $this->tableNames['client_purchase_invoice'] . " where invoice_type = 'receiptvoucherinvoice' AND invoice_id = " . $invoiceData[0]->receipt_voucher_number . " AND is_deleted='0' AND financial_year = '" . $currentFinancialYear . "' AND added_by = " . $this->sanitize($_SESSION['user_detail']['user_id']) . " order by serial_number asc");
											if ($receiptVoucher) {
												$mpdfHtml .= '<b>Receipt Voucher:</b> ' . $receiptVoucher->serial_number . '<br>';
											}
										}
									}

								$mpdfHtml .= '</td>';
							$mpdfHtml .= '</tr>';
						$mpdfHtml .= '</table>';
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';

				$mpdfHtml .= '<tr>';
					$mpdfHtml .= '<td colspan="2" style="vertical-align:top;">';
						$mpdfHtml .= '<table style="width:100%;line-height:inherit;">';
							$mpdfHtml .= '<tr>';
						   
								$mpdfHtml .= '<td style="vertical-align:top;text-align:left;padding-bottom:40px;width:48%;padding-right:2%;">';
									$mpdfHtml .= '<b>Supplier Detail</b><br>';
									$mpdfHtml .= html_entity_decode($invoiceData[0]->supplier_billing_name) . '<br>';
									if ($invoiceData[0]->supplier_billing_company_name) { $mpdfHtml .= $invoiceData[0]->supplier_billing_company_name . '<br>'; }
									$mpdfHtml .= $invoiceData[0]->supplier_billing_address . '<br>';
									
									$supplier_billing_vendor_data = $this->getVendorDetailByVendorId($invoiceData[0]->supplier_billing_vendor_type);
									$mpdfHtml .= $supplier_billing_vendor_data['data']->vendor_name . '<br>';
									
									if (!empty($invoiceData[0]->supplier_billing_gstin_number)) { $mpdfHtml .= '<b>GSTIN/UIN:</b>' . $invoiceData[0]->supplier_billing_gstin_number; }
								$mpdfHtml .= '</td>';

								$mpdfHtml .= '<td style="vertical-align:top;text-align:right;padding-bottom:40px;width:48%;padding-left:2%;">';
									$mpdfHtml .= '<b>Address Of Recipient / Shipping Detail</b><br>';
									$mpdfHtml .= $invoiceData[0]->recipient_shipping_name . '<br>';
									if ($invoiceData[0]->recipient_shipping_company_name) { $mpdfHtml .= $invoiceData[0]->recipient_shipping_company_name . '<br>'; }
									$mpdfHtml .= $invoiceData[0]->recipient_shipping_address . '<br>';
									
									$recipient_shipping_vendor_data = $this->getVendorDetailByVendorId($invoiceData[0]->recipient_shipping_vendor_type);
									$mpdfHtml .= $recipient_shipping_vendor_data['data']->vendor_name . '<br>';
									
									if (!empty($invoiceData[0]->recipient_shipping_gstin_number)) { $mpdfHtml .= '<b>GSTIN/UIN:</b>' . $invoiceData[0]->recipient_shipping_gstin_number; }
								$mpdfHtml .= '</td>';

							$mpdfHtml .= '</tr>';
						$mpdfHtml .= '</table>';
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';
				
			$mpdfHtml .= '</table>';
			
			$mpdfHtml .= '<table border="1" style="border-collapse:collapse;width:100%;line-height:inherit;text-align:center;">';
				$mpdfHtml .= '<tr>';
					$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">S.No</td>';
					$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Goods/Services</td>';
					$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">HSN/SAC Code</td>';
					$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Item Description</td>';
					$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Qty</td>';
					$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Unit</td>';
					$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Rate (₹)</td>';
					$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Total (₹)</td>';
					$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Discount(%)</td>';

					if ($invoiceData[0]->advance_adjustment == 1) {
						$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Advance (₹)</td>';
					}

					$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Taxable Value (₹)</td>';
					$mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">CGST</td>';
					$mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">SGST</td>';
					$mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">IGST</td>';
					$mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">CESS</td>';
				$mpdfHtml .= '</tr>';

				$mpdfHtml .= '<tr class="heading">';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
				$mpdfHtml .= '</tr>';

				$counter = 1;
				$total_taxable_subtotal = 0.00;
				$total_advance_subtotal = 0.00;
				$total_cgst_amount = 0.00;
				$total_sgst_amount = 0.00;
				$total_igst_amount = 0.00;
				$total_cess_amount = 0.00;
				foreach($invoiceData as $invData) {

					$mpdfHtml .= '<tr>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $counter;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->item_name;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->item_hsncode;
						$mpdfHtml .= '</td>';
					
						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->item_description;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->item_quantity;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->item_unit;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->item_unit_price;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->subtotal;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->discount;
						$mpdfHtml .= '</td>';

						if ($invoiceData[0]->advance_adjustment == 1) {
							$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
								$mpdfHtml .= $invData->advance_amount;
							$mpdfHtml .= '</td>';
						}

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->taxable_subtotal;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->cgst_rate;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->cgst_amount;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->sgst_rate;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->sgst_amount;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->igst_rate;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->igst_amount;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->cess_rate;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->cess_amount;
						$mpdfHtml .= '</td>';

					$mpdfHtml .= '</tr>';

					$total_taxable_subtotal += $invData->taxable_subtotal;
					$total_advance_subtotal += $invData->advance_amount;
					$total_cgst_amount += $invData->cgst_amount;
					$total_sgst_amount += $invData->sgst_amount;
					$total_igst_amount += $invData->igst_amount;
					$total_cess_amount += $invData->cess_amount;

					$counter++;
				}
				
				$mpdfHtml .= '<tr style="background:#d9edf7;">';
					$mpdfHtml .= '<td colspan="9" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Total Invoice Value</td>';
					if($invoiceData[0]->advance_adjustment == 1) { $mpdfHtml .= '<td>'.$total_advance_subtotal.'</td>'; }
					$mpdfHtml .= '<td>'.$total_taxable_subtotal.'</td>';
					$mpdfHtml .= '<td>&nbsp;</td>';
					$mpdfHtml .= '<td>'.$total_cgst_amount.'</td>';
					$mpdfHtml .= '<td>&nbsp;</td>';
					$mpdfHtml .= '<td>'.$total_sgst_amount.'</td>';
					$mpdfHtml .= '<td>&nbsp;</td>';
					$mpdfHtml .= '<td>'.$total_igst_amount.'</td>';
					$mpdfHtml .= '<td>&nbsp;</td>';
					$mpdfHtml .= '<td>'.$total_cess_amount.'</td>';
				$mpdfHtml .= '</tr>';

				if($invoiceData[0]->supply_type == "reversecharge") {

					if($invoiceData[0]->supplier_billing_state === $invoiceData[0]->supply_place) {

						$mpdfHtml .= '<tr style="background:#e9ffdb;font-size:14px;">';

							if ($invoiceData[0]->advance_adjustment == 1) {
								$mpdfHtml .= '<td colspan="11" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Amount of Tax Subject to Reverse Charge</td>';
							} else {
								$mpdfHtml .= '<td colspan="10" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Amount of Tax Subject to Reverse Charge</td>';
							}

							$mpdfHtml .= '<td>-</td>';
							$mpdfHtml .= '<td>'. $total_cgst_amount .'</td>';
							$mpdfHtml .= '<td>-</td>';
							$mpdfHtml .= '<td>'.$total_sgst_amount .'</td>';
							$mpdfHtml .= '<td>-</td>';
							$mpdfHtml .= '<td>0.00</td>';
							$mpdfHtml .= '<td>-</td>';
							$mpdfHtml .= '<td>'. $total_cess_amount .'</td>';
						$mpdfHtml .= '</tr>';
					} else {
					
						$mpdfHtml .= '<tr style="background:#e9ffdb;font-size:14px;">';

							if ($invoiceData[0]->advance_adjustment == 1) {
								$mpdfHtml .= '<td colspan="11" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Amount of Tax Subject to Reverse Charge</td>';
							} else {
								$mpdfHtml .= '<td colspan="10" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Amount of Tax Subject to Reverse Charge</td>';
							}

							$mpdfHtml .= '<td>-</td>';
							$mpdfHtml .= '<td>0.00</td>';
							$mpdfHtml .= '<td>-</td>';
							$mpdfHtml .= '<td>0.00</td>';
							$mpdfHtml .= '<td>-</td>';
							$mpdfHtml .= '<td>'. $total_igst_amount .'</td>';
							$mpdfHtml .= '<td>-</td>';
							$mpdfHtml .= '<td>'. $total_cess_amount .'</td>';
						$mpdfHtml .= '</tr>';
					}
				}

				$mpdfHtml .= '<tr style="background:#ffefbf;">';
					if ($invoiceData[0]->advance_adjustment == 1) {
						$mpdfHtml .= '<td colspan="19" style="padding:5px;vertical-align:top;text-align:right;font-weight:bold;">';
					} else {
						$mpdfHtml .= '<td colspan="18" style="padding:5px;vertical-align:top;text-align:right;font-weight:bold;">';
					}
						$mpdfHtml .= 'Total Invoice Value (In Figure): ₹' . $invoiceData[0]->invoice_total_value;
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';

				$invoice_total_value_words = $this->convert_number_to_words($invoiceData[0]->invoice_total_value);

				$mpdfHtml .= '<tr style="background:#f2dede;">';
					if ($invoiceData[0]->advance_adjustment == 1) {
						$mpdfHtml .= '<td colspan="19" style="padding:5px;vertical-align:top;text-align:right;font-weight:bold;">';
					} else {
						$mpdfHtml .= '<td colspan="18" style="padding:5px;vertical-align:top;text-align:right;font-weight:bold;">';
					}
						$mpdfHtml .= 'Total Invoice Value (In Words): ' . ucwords($invoice_total_value_words);
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';
			
			$mpdfHtml .= '</table>';
			
			$mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;" cellpadding="0" cellspacing="0">';

				if(!empty($invoiceData[0]->description)) {
					$mpdfHtml .= '<tr class="description">';
						$mpdfHtml .= '<td colspan="2" style="padding-top:20px;vertical-align:top;">';
							$mpdfHtml .= '<p><b>Additional Notes:</b> '. $invoiceData[0]->description .'</p>';
						$mpdfHtml .= '</td>';
					$mpdfHtml .= '</tr>';
				}

			$mpdfHtml .= '</table>';
		$mpdfHtml .= '</div>';
		
		return $mpdfHtml;
	}
	
	/* generate invoice html */
	public function generatePurchaseBOSInvoiceHtml($invoiceid) {

		$currentFinancialYear = $this->generateFinancialYear();

		$invoiceData = $this->get_results("select 
												ci.*, 
												cii.purchase_invoice_item_id, 
												cii.item_id, 
												cii.item_name, 
												cii.item_hsncode, 
												cii.item_description, 
												cii.item_quantity, 
												cii.item_unit, 
												cii.item_unit_price, 
												cii.subtotal, 
												cii.discount, 
												cii.taxable_subtotal, 
												cii.total 
												from 
												" . $this->tableNames['client_purchase_invoice'] ." as ci INNER JOIN " . $this->tableNames['client_purchase_invoice_item'] ." as cii ON ci.purchase_invoice_id = cii.purchase_invoice_id where ci.purchase_invoice_id = ".$invoiceid." AND ci.invoice_type = 'billofsupplyinvoice' AND ci.added_by = '".$this->sanitize($_SESSION['user_detail']['user_id'])."' AND cii.added_by = '".$this->sanitize($_SESSION['user_detail']['user_id'])."' AND ci.is_deleted='0' AND cii.is_deleted='0'");
		if (empty($invoiceData)) {
			return false;
		}

		$dataThemeSettingArr = $this->getUserThemeSetting($this->sanitize($_SESSION['user_detail']['user_id']));

		$mpdfHtml = '';
		$mpdfHtml .= '<div style="margin:auto;font-size:16px;line-height:24px;color:#555;">';
			$mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;" cellpadding="0" cellspacing="0">';
				$mpdfHtml .= '<tr>';
					$mpdfHtml .= '<td colspan="2" style="vertical-align:top;">';
						$mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;">';
							$mpdfHtml .= '<tr>';
								$mpdfHtml .= '<td style="font-size:45px;line-height:45px;color:#333;padding:5px;vertical-align:top;padding-bottom:20px;">';

									if (isset($dataThemeSettingArr['data']->theme_logo) && $dataThemeSettingArr['data']->theme_logo != "") {
										$mpdfHtml .= '<img src="upload/theme-logo/' . $dataThemeSettingArr['data']->theme_logo . '" style="width:100%;max-width:300px;">';
									} else {
										$mpdfHtml .= '<img src="image/gst-k-logo.png" style="width:100%;max-width:300px;">';
									}

								$mpdfHtml .= '</td>';

								$mpdfHtml .= '<td style="vertical-align:top;text-align:right;padding-bottom:20px;">';
									$mpdfHtml .= '<b>Invoice #</b>: ' . $invoiceData[0]->serial_number . '<br>';
									$mpdfHtml .= '<b>Reference #</b>: ' . $invoiceData[0]->reference_number . '<br>';
									$mpdfHtml .= '<b>Type:</b> Bill of Supply Invoice<br>';
									$mpdfHtml .= '<b>Nature:</b> Purchase Invoice<br>';
									$mpdfHtml .= '<b>Invoice Date:</b>' . $invoiceData[0]->invoice_date;
								$mpdfHtml .= '</td>';

							$mpdfHtml .= '</tr>';
						$mpdfHtml .= '</table>';
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';

				$mpdfHtml .= '<tr>';
					$mpdfHtml .= '<td colspan="2" style="vertical-align:top;">';
						$mpdfHtml .= '<table style="width:100%;line-height:inherit;">';
							$mpdfHtml .= '<tr>';
								$mpdfHtml .= '<td style="text-align:left;vertical-align:top;padding-bottom:20px;width:48%;padding-right:2%;">';
									$mpdfHtml .= $invoiceData[0]->company_name . '<br>';
									$mpdfHtml .= $invoiceData[0]->company_address . '<br>';
									if(!empty($invoiceData[0]->company_email)) { $mpdfHtml .= '<b>Email:</b> ' . $invoiceData[0]->company_email . '<br>'; }
									if(!empty($invoiceData[0]->company_phone_number)) { $mpdfHtml .= '<b>Phone:</b> ' . $invoiceData[0]->company_phone_number . '<br>'; }
									$panFromGTIN = substr(substr($invoiceData[0]->company_gstin_number, 2), 0, -3);
									$mpdfHtml .= '<b>PAN:</b> ' . $panFromGTIN  . '<br>';
									$mpdfHtml .= '<b>GSTIN:</b> ' . $invoiceData[0]->company_gstin_number;
								$mpdfHtml .= '</td>';

								$mpdfHtml .= '<td style="vertical-align:top;text-align:right;padding-bottom:20px;width:48%;padding-left:2%;">';
									if ($invoiceData[0]->is_canceled == 1) { $mpdfHtml .= '<b>Canceled Invoice:</b> Canceled'; }
								$mpdfHtml .= '</td>';
							$mpdfHtml .= '</tr>';
						$mpdfHtml .= '</table>';
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';

				$mpdfHtml .= '<tr>';
					$mpdfHtml .= '<td colspan="2" style="vertical-align:top;">';
						$mpdfHtml .= '<table style="width:100%;line-height:inherit;">';
							$mpdfHtml .= '<tr>';
						   
								$mpdfHtml .= '<td style="vertical-align:top;text-align:left;padding-bottom:40px;width:48%;padding-right:2%;">';
									$mpdfHtml .= '<b>Supplier Detail</b><br>';
									$mpdfHtml .= html_entity_decode($invoiceData[0]->supplier_billing_name) . '<br>';
									if ($invoiceData[0]->supplier_billing_company_name) { $mpdfHtml .= $invoiceData[0]->supplier_billing_company_name . '<br>'; }
									$mpdfHtml .= $invoiceData[0]->supplier_billing_address . '<br>';
									
									$supplier_billing_vendor_data = $this->getVendorDetailByVendorId($invoiceData[0]->supplier_billing_vendor_type);
									$mpdfHtml .= $supplier_billing_vendor_data['data']->vendor_name . '<br>';
									
									if (!empty($invoiceData[0]->supplier_billing_gstin_number)) { $mpdfHtml .= '<b>GSTIN/UIN:</b>' . $invoiceData[0]->supplier_billing_gstin_number; }
								$mpdfHtml .= '</td>';

								$mpdfHtml .= '<td style="vertical-align:top;text-align:right;padding-bottom:40px;width:48%;padding-left:2%;">';
									$mpdfHtml .= '<b>Address Of Recipient / Shipping Detail</b><br>';
									$mpdfHtml .= $invoiceData[0]->recipient_shipping_name . '<br>';
									if ($invoiceData[0]->recipient_shipping_company_name) { $mpdfHtml .= $invoiceData[0]->recipient_shipping_company_name . '<br>'; }
									$mpdfHtml .= $invoiceData[0]->recipient_shipping_address . '<br>';
									
									$recipient_shipping_vendor_data = $this->getVendorDetailByVendorId($invoiceData[0]->recipient_shipping_vendor_type);
									$mpdfHtml .= $recipient_shipping_vendor_data['data']->vendor_name . '<br>';
									
									if (!empty($invoiceData[0]->recipient_shipping_gstin_number)) { $mpdfHtml .= '<b>GSTIN/UIN:</b>' . $invoiceData[0]->recipient_shipping_gstin_number; }
								$mpdfHtml .= '</td>';

							$mpdfHtml .= '</tr>';
						$mpdfHtml .= '</table>';
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';
				
			$mpdfHtml .= '</table>';

			$mpdfHtml .= '<table border="1" style="border-collapse:collapse;width:100%;line-height:inherit;text-align:center;">';
				$mpdfHtml .= '<tr>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">S.No</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Goods/Services</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">HSN/SAC Code</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Item Description</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Qty</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Unit</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Rate (₹)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Total (₹)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Discount(%)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Net Total Value (₹)</td>';
				$mpdfHtml .= '</tr>';

				$counter = 1;
				$total_taxable_subtotal = 0.00;
				foreach($invoiceData as $invData) {

					$mpdfHtml .= '<tr>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $counter;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->item_name;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->item_hsncode;
						$mpdfHtml .= '</td>';
					
						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->item_description;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->item_quantity;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->item_unit;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->item_unit_price;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->subtotal;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->discount;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->taxable_subtotal;
						$mpdfHtml .= '</td>';

					$mpdfHtml .= '</tr>';

					$total_taxable_subtotal += $invData->taxable_subtotal;
					$counter++;
				}

				$mpdfHtml .= '<tr style="background:#d9edf7;">';
					$mpdfHtml .= '<td colspan="9" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Total Invoice Value</td>';
					$mpdfHtml .= '<td>'.$total_taxable_subtotal.'</td>';
				$mpdfHtml .= '</tr>';

				$mpdfHtml .= '<tr style="background:#ffefbf;">';
					$mpdfHtml .= '<td colspan="10" style="padding:5px;vertical-align:top;text-align:right;border-top:2px solid #eee;font-weight:bold;">';
						$mpdfHtml .= 'Total Invoice Value (In Figure): ₹' . $invoiceData[0]->invoice_total_value;
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';

				$invoice_total_value_words = $this->convert_number_to_words($invoiceData[0]->invoice_total_value);

				$mpdfHtml .= '<tr style="background:#f2dede;">';
					$mpdfHtml .= '<td colspan="10" style="padding:5px;vertical-align:top;text-align:right;border-top:2px solid #eee;font-weight:bold;">';
						$mpdfHtml .= 'Total Invoice Value (In Words): ' . ucwords($invoice_total_value_words);
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';

			$mpdfHtml .= '</table>';

			$mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;" cellpadding="0" cellspacing="0">';

				if(!empty($invoiceData[0]->description)) {
					$mpdfHtml .= '<tr class="description">';
						$mpdfHtml .= '<td colspan="2" style="padding-top:20px;vertical-align:top;">';
							$mpdfHtml .= '<p><b>Additional Notes:</b> '. $invoiceData[0]->description .'</p>';
						$mpdfHtml .= '</td>';
					$mpdfHtml .= '</tr>';
				}

			$mpdfHtml .= '</table>';
		$mpdfHtml .= '</div>';

		return $mpdfHtml;
	}

	/* generate Purchase RV invoice html */
	public function generatePurchaseRVInvoiceHtml($invoiceid) {

		$currentFinancialYear = $this->generateFinancialYear();

		$invoiceData = $this->get_results("select 
												ci.*, 
												cii.purchase_invoice_item_id, 
												cii.item_id, 
												cii.item_name, 
												cii.item_hsncode, 
												cii.item_description, 
												cii.taxable_subtotal, 
												cii.cgst_rate, 
												cii.cgst_amount, 
												cii.sgst_rate, 
												cii.sgst_amount, 
												cii.igst_rate, 
												cii.igst_amount, 
												cii.cess_rate, 
												cii.cess_amount, 
												cii.total 
												from 
												" . $this->tableNames['client_purchase_invoice'] ." as ci INNER JOIN " . $this->tableNames['client_purchase_invoice_item'] ." as cii ON ci.purchase_invoice_id = cii.purchase_invoice_id where ci.purchase_invoice_id = ".$invoiceid." AND ci.invoice_type = 'receiptvoucherinvoice' AND ci.added_by = '".$this->sanitize($_SESSION['user_detail']['user_id'])."' AND cii.added_by = '".$this->sanitize($_SESSION['user_detail']['user_id'])."' AND ci.is_deleted='0' AND cii.is_deleted='0'");
		if (empty($invoiceData)) {
			return false;
		}

		$dataThemeSettingArr = $this->getUserThemeSetting($this->sanitize($_SESSION['user_detail']['user_id']));

		$mpdfHtml = '';
		$mpdfHtml .= '<div style="margin:auto;font-size:16px;line-height:24px;color:#555;">';
			$mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;" cellpadding="0" cellspacing="0">';
				$mpdfHtml .= '<tr>';
					$mpdfHtml .= '<td colspan="2" style="vertical-align:top;">';
						$mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;">';
							$mpdfHtml .= '<tr>';
								$mpdfHtml .= '<td style="font-size:45px;line-height:45px;color:#333;padding:5px;vertical-align:top;padding-bottom:20px;">';

									if (isset($dataThemeSettingArr['data']->theme_logo) && $dataThemeSettingArr['data']->theme_logo != "") {
										$mpdfHtml .= '<img src="upload/theme-logo/' . $dataThemeSettingArr['data']->theme_logo . '" style="width:100%;max-width:300px;">';
									} else {
										$mpdfHtml .= '<img src="image/gst-k-logo.png" style="width:100%;max-width:300px;">';
									}

								$mpdfHtml .= '</td>';
								
								$mpdfHtml .= '<td style="vertical-align:top;text-align:right;padding-bottom:20px;">';
									$mpdfHtml .= '<b>Invoice #</b>: ' . $invoiceData[0]->serial_number . '<br>';
									$mpdfHtml .= '<b>Reference #</b>: ' . $invoiceData[0]->reference_number . '<br>';
									$mpdfHtml .= '<b>Type:</b> Receipt Voucher<br>';
									$mpdfHtml .= '<b>Nature:</b> Purchase Invoice<br>';
									$mpdfHtml .= '<b>Invoice Date:</b>' . $invoiceData[0]->invoice_date;
								$mpdfHtml .= '</td>';

							$mpdfHtml .= '</tr>';
						$mpdfHtml .= '</table>';
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';

				$supply_place_data = $this->getStateDetailByStateId($invoiceData[0]->supply_place);

				$mpdfHtml .= '<tr>';
					$mpdfHtml .= '<td colspan="2" style="vertical-align:top;">';
						$mpdfHtml .= '<table style="width:100%;line-height:inherit;">';
							$mpdfHtml .= '<tr>';
								$mpdfHtml .= '<td style="text-align:left;vertical-align:top;padding-bottom:20px;width:48%;padding-right:2%;">';
									$mpdfHtml .= $invoiceData[0]->company_name . '<br>';
									$mpdfHtml .= $invoiceData[0]->company_address . '<br>';
									if(!empty($invoiceData[0]->company_email)) { $mpdfHtml .= '<b>Email:</b> ' . $invoiceData[0]->company_email . '<br>'; }
									if(!empty($invoiceData[0]->company_phone_number)) { $mpdfHtml .= '<b>Phone:</b> ' . $invoiceData[0]->company_phone_number . '<br>'; }
									$panFromGTIN = substr(substr($invoiceData[0]->company_gstin_number, 2), 0, -3);
									$mpdfHtml .= '<b>PAN:</b> ' . $panFromGTIN  . '<br>';
									$mpdfHtml .= '<b>GSTIN:</b> ' . $invoiceData[0]->company_gstin_number;
								$mpdfHtml .= '</td>';

								$mpdfHtml .= '<td style="vertical-align:top;text-align:right;padding-bottom:20px;width:48%;padding-left:2%;">';

									if (isset($invoiceData[0]->supply_place) && $invoiceData[0]->supply_place > 0) {
										if($supply_place_data['data']->state_tin == 97) {
											$mpdfHtml .= '<b>Place Of Supply:</b> ' . $supply_place_data['data']->state_name . '<br>';
										} else {
											$mpdfHtml .= '<b>Place Of Supply:</b> ' . $supply_place_data['data']->state_name . '(' . $supply_place_data['data']->state_tin . ')' . '<br>';
										}
									}

									if ($invoiceData[0]->is_tax_payable == '1') {
										$mpdfHtml .= '<b>Reverse Charge:</b> Yes<br>';
									} else {
										$mpdfHtml .= '<b>Reverse Charge:</b> No<br>';
									}

									if ($invoiceData[0]->is_canceled == 1) { $mpdfHtml .= '<b>Canceled Invoice:</b> Canceled'; }

								$mpdfHtml .= '</td>';
							$mpdfHtml .= '</tr>';
						$mpdfHtml .= '</table>';
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';

				$mpdfHtml .= '<tr>';
					$mpdfHtml .= '<td colspan="2" style="vertical-align:top;">';
						$mpdfHtml .= '<table style="width:100%;line-height:inherit;">';
							$mpdfHtml .= '<tr>';
						   
								$mpdfHtml .= '<td style="vertical-align:top;text-align:left;padding-bottom:40px;width:48%;padding-right:2%;">';
									$mpdfHtml .= '<b>Supplier Detail</b><br>';
									$mpdfHtml .= html_entity_decode($invoiceData[0]->supplier_billing_name) . '<br>';
									if ($invoiceData[0]->supplier_billing_company_name) { $mpdfHtml .= $invoiceData[0]->supplier_billing_company_name . '<br>'; }
									$mpdfHtml .= $invoiceData[0]->supplier_billing_address . '<br>';
									
									$supplier_billing_vendor_data = $this->getVendorDetailByVendorId($invoiceData[0]->supplier_billing_vendor_type);
									$mpdfHtml .= $supplier_billing_vendor_data['data']->vendor_name . '<br>';
									
									if (!empty($invoiceData[0]->supplier_billing_gstin_number)) { $mpdfHtml .= '<b>GSTIN/UIN:</b>' . $invoiceData[0]->supplier_billing_gstin_number; }
								$mpdfHtml .= '</td>';

								$mpdfHtml .= '<td style="vertical-align:top;text-align:right;padding-bottom:40px;width:48%;padding-left:2%;">';
									$mpdfHtml .= '<b>Address Of Recipient / Shipping Detail</b><br>';
									$mpdfHtml .= $invoiceData[0]->recipient_shipping_name . '<br>';
									if ($invoiceData[0]->recipient_shipping_company_name) { $mpdfHtml .= $invoiceData[0]->recipient_shipping_company_name . '<br>'; }
									$mpdfHtml .= $invoiceData[0]->recipient_shipping_address . '<br>';
									
									$recipient_shipping_vendor_data = $this->getVendorDetailByVendorId($invoiceData[0]->recipient_shipping_vendor_type);
									$mpdfHtml .= $recipient_shipping_vendor_data['data']->vendor_name . '<br>';
									
									if (!empty($invoiceData[0]->recipient_shipping_gstin_number)) { $mpdfHtml .= '<b>GSTIN/UIN:</b>' . $invoiceData[0]->recipient_shipping_gstin_number; }
								$mpdfHtml .= '</td>';

							$mpdfHtml .= '</tr>';
						$mpdfHtml .= '</table>';
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';

			$mpdfHtml .= '</table>';
			
			$mpdfHtml .= '<table border="1" style="border-collapse:collapse;width:100%;line-height:inherit;text-align:center;">';
				$mpdfHtml .= '<tr>';
					$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">S.No</td>';
					$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Goods/Services</td>';
					$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">HSN/SAC Code</td>';
					$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Item Description</td>';
					$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Advance Value (₹)</td>';
					$mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">CGST</td>';
					$mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">SGST</td>';
					$mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">IGST</td>';
					$mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">CESS</td>';
				$mpdfHtml .= '</tr>';

				$mpdfHtml .= '<tr class="heading">';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
				$mpdfHtml .= '</tr>';
					
				$counter = 1;
				$total_taxable_subtotal = 0.00;
				$total_cgst_amount = 0.00;
				$total_sgst_amount = 0.00;
				$total_igst_amount = 0.00;
				$total_cess_amount = 0.00;
				foreach ($invoiceData as $invData) {

					$mpdfHtml .= '<tr>';
						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $counter;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->item_name;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->item_hsncode;
						$mpdfHtml .= '</td>';
						
						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->item_description;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->taxable_subtotal;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->cgst_rate;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->cgst_amount;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->sgst_rate;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->sgst_amount;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->igst_rate;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->igst_amount;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->cess_rate;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->cess_amount;
						$mpdfHtml .= '</td>';

					$mpdfHtml .= '</tr>';

					$total_taxable_subtotal += $invData->taxable_subtotal;
					$total_cgst_amount += $invData->cgst_amount;
					$total_sgst_amount += $invData->sgst_amount;
					$total_igst_amount += $invData->igst_amount;
					$total_cess_amount += $invData->cess_amount;

					$counter++;
				}
				
				$mpdfHtml .= '<tr style="background:#d9edf7;">';
					$mpdfHtml .= '<td colspan="4" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Total Invoice Value</td>';
					$mpdfHtml .= '<td>'.$total_taxable_subtotal.'</td>';
					$mpdfHtml .= '<td>&nbsp;</td>';
					$mpdfHtml .= '<td>'.$total_cgst_amount.'</td>';
					$mpdfHtml .= '<td>&nbsp;</td>';
					$mpdfHtml .= '<td>'.$total_sgst_amount.'</td>';
					$mpdfHtml .= '<td>&nbsp;</td>';
					$mpdfHtml .= '<td>'.$total_igst_amount.'</td>';
					$mpdfHtml .= '<td>&nbsp;</td>';
					$mpdfHtml .= '<td>'.$total_cess_amount.'</td>';
				$mpdfHtml .= '</tr>';

				if ($invoiceData[0]->is_tax_payable == "1") {

					if($invoiceData[0]->supplier_billing_state === $invoiceData[0]->supply_place) {

						$mpdfHtml .= '<tr style="background:#e9ffdb;font-size:14px;">';
							$mpdfHtml .= '<td colspan="5" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Amount of Tax Subject to Reverse Charge</td>';
							$mpdfHtml .= '<td>-</td>';
							$mpdfHtml .= '<td>' . $total_cgst_amount . '</td>';
							$mpdfHtml .= '<td>-</td>';
							$mpdfHtml .= '<td>' . $total_sgst_amount . '</td>';
							$mpdfHtml .= '<td>-</td>';
							$mpdfHtml .= '<td>0.00</td>';
							$mpdfHtml .= '<td>-</td>';
							$mpdfHtml .= '<td>' . $total_cess_amount . '</td>';
						$mpdfHtml .= '</tr>';
					} else {

						$mpdfHtml .= '<tr style="background:#e9ffdb;font-size:14px;">';
							$mpdfHtml .= '<td colspan="5" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Amount of Tax Subject to Reverse Charge</td>';
							$mpdfHtml .= '<td>-</td>';
							$mpdfHtml .= '<td>0.00</td>';
							$mpdfHtml .= '<td>-</td>';
							$mpdfHtml .= '<td>0.00</td>';
							$mpdfHtml .= '<td>-</td>';
							$mpdfHtml .= '<td>' . $total_igst_amount . '</td>';
							$mpdfHtml .= '<td>-</td>';
							$mpdfHtml .= '<td>' . $total_cess_amount . '</td>';
						$mpdfHtml .= '</tr>';
					}
				}
	
				$mpdfHtml .= '<tr style="background:#ffefbf;">';
					$mpdfHtml .= '<td colspan="13" style="padding:5px;vertical-align:top;text-align:right;font-weight:bold;">';
						$mpdfHtml .= 'Total Invoice Value (In Figure): ₹' . $invoiceData[0]->invoice_total_value;
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';

				$invoice_total_value_words = $this->convert_number_to_words($invoiceData[0]->invoice_total_value);

				$mpdfHtml .= '<tr style="background:#f2dede;">';
					$mpdfHtml .= '<td colspan="13" style="padding:5px;vertical-align:top;text-align:right;font-weight:bold;">';
						$mpdfHtml .= 'Total Invoice Value (In Words): ' . ucwords($invoice_total_value_words);
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';

			$mpdfHtml .= '</table>';

			$mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;" cellpadding="0" cellspacing="0">';

				if(!empty($invoiceData[0]->description)) {
					$mpdfHtml .= '<tr class="description">';
						$mpdfHtml .= '<td colspan="2" style="padding-top:20px;vertical-align:top;">';
							$mpdfHtml .= '<p><b>Additional Notes:</b> '. $invoiceData[0]->description .'</p>';
						$mpdfHtml .= '</td>';
					$mpdfHtml .= '</tr>';
				}

			$mpdfHtml .= '</table>';
		$mpdfHtml .= '</div>';

		return $mpdfHtml;
	}

	/* generate Purchase PV invoice html */
	public function generatePurchasePVInvoiceHtml($invoiceid) {

		$currentFinancialYear = $this->generateFinancialYear();

		$invoiceData = $this->get_results("select 
												ci.*, 
												cii.purchase_invoice_item_id, 
												cii.item_id, 
												cii.item_name, 
												cii.item_hsncode, 
												cii.item_description, 
												cii.taxable_subtotal, 
												cii.cgst_rate, 
												cii.cgst_amount, 
												cii.sgst_rate, 
												cii.sgst_amount, 
												cii.igst_rate, 
												cii.igst_amount, 
												cii.cess_rate, 
												cii.cess_amount, 
												cii.total 
												from 
												" . $this->tableNames['client_purchase_invoice'] ." as ci INNER JOIN " . $this->tableNames['client_purchase_invoice_item'] ." as cii ON ci.purchase_invoice_id = cii.purchase_invoice_id where ci.purchase_invoice_id = ".$invoiceid." AND ci.invoice_type = 'paymentvoucherinvoice' AND ci.added_by = '".$this->sanitize($_SESSION['user_detail']['user_id'])."' AND cii.added_by = '".$this->sanitize($_SESSION['user_detail']['user_id'])."' AND ci.is_deleted='0' AND cii.is_deleted='0'");
		if (empty($invoiceData)) {
			return false;
		}

		$dataThemeSettingArr = $this->getUserThemeSetting($this->sanitize($_SESSION['user_detail']['user_id']));

		$mpdfHtml = '';
		$mpdfHtml .= '<div style="margin:auto;font-size:16px;line-height:24px;color:#555;">';
			$mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;" cellpadding="0" cellspacing="0">';
				$mpdfHtml .= '<tr>';
					$mpdfHtml .= '<td colspan="2" style="vertical-align:top;">';
						$mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;">';
							$mpdfHtml .= '<tr>';
								$mpdfHtml .= '<td style="font-size:45px;line-height:45px;color:#333;padding:5px;vertical-align:top;padding-bottom:20px;">';

									if (isset($dataThemeSettingArr['data']->theme_logo) && $dataThemeSettingArr['data']->theme_logo != "") {
										$mpdfHtml .= '<img src="upload/theme-logo/' . $dataThemeSettingArr['data']->theme_logo . '" style="width:100%;max-width:300px;">';
									} else {
										$mpdfHtml .= '<img src="image/gst-k-logo.png" style="width:100%;max-width:300px;">';
									}

								$mpdfHtml .= '</td>';
								
								$mpdfHtml .= '<td style="vertical-align:top;text-align:right;padding-bottom:20px;">';
									$mpdfHtml .= '<b>Invoice #</b>: ' . $invoiceData[0]->serial_number . '<br>';
									$mpdfHtml .= '<b>Reference #</b>: ' . $invoiceData[0]->reference_number . '<br>';
									$mpdfHtml .= '<b>Type:</b> Payment Voucher<br>';
									$mpdfHtml .= '<b>Nature:</b> Purchase Invoice<br>';
									$mpdfHtml .= '<b>Invoice Date:</b>' . $invoiceData[0]->invoice_date;
								$mpdfHtml .= '</td>';

							$mpdfHtml .= '</tr>';
						$mpdfHtml .= '</table>';
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';

				$supply_place_data = $this->getStateDetailByStateId($invoiceData[0]->supply_place);

				$mpdfHtml .= '<tr>';
					$mpdfHtml .= '<td colspan="2" style="vertical-align:top;">';
						$mpdfHtml .= '<table style="width:100%;line-height:inherit;">';
							$mpdfHtml .= '<tr>';
								$mpdfHtml .= '<td style="text-align:left;vertical-align:top;padding-bottom:20px;width:48%;padding-right:2%;">';
									$mpdfHtml .= $invoiceData[0]->company_name . '<br>';
									$mpdfHtml .= $invoiceData[0]->company_address . '<br>';
									if(!empty($invoiceData[0]->company_email)) { $mpdfHtml .= '<b>Email:</b> ' . $invoiceData[0]->company_email . '<br>'; }
									if(!empty($invoiceData[0]->company_phone_number)) { $mpdfHtml .= '<b>Phone:</b> ' . $invoiceData[0]->company_phone_number . '<br>'; }
									$panFromGTIN = substr(substr($invoiceData[0]->company_gstin_number, 2), 0, -3);
									$mpdfHtml .= '<b>PAN:</b> ' . $panFromGTIN  . '<br>';
									$mpdfHtml .= '<b>GSTIN:</b> ' . $invoiceData[0]->company_gstin_number;
								$mpdfHtml .= '</td>';

								$mpdfHtml .= '<td style="vertical-align:top;text-align:right;padding-bottom:20px;width:48%;padding-left:2%;">';

									if (isset($invoiceData[0]->supply_place) && $invoiceData[0]->supply_place > 0) {
										if($supply_place_data['data']->state_tin == 97) {
											$mpdfHtml .= '<b>Place Of Supply:</b> ' . $supply_place_data['data']->state_name . '<br>';
										} else {
											$mpdfHtml .= '<b>Place Of Supply:</b> ' . $supply_place_data['data']->state_name . '(' . $supply_place_data['data']->state_tin . ')' . '<br>';
										}
									}
									
									$mpdfHtml .= '<b>Reverse Charge:</b> Yes<br>';
									if ($invoiceData[0]->is_canceled == 1) { $mpdfHtml .= '<b>Canceled Invoice:</b> Canceled'; }

								$mpdfHtml .= '</td>';
							$mpdfHtml .= '</tr>';
						$mpdfHtml .= '</table>';
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';

				$mpdfHtml .= '<tr>';
					$mpdfHtml .= '<td colspan="2" style="vertical-align:top;">';
						$mpdfHtml .= '<table style="width:100%;line-height:inherit;">';
							$mpdfHtml .= '<tr>';
						   
								$mpdfHtml .= '<td style="vertical-align:top;text-align:left;padding-bottom:40px;width:48%;padding-right:2%;">';
									$mpdfHtml .= '<b>Supplier Detail</b><br>';
									$mpdfHtml .= html_entity_decode($invoiceData[0]->supplier_billing_name) . '<br>';
									if ($invoiceData[0]->supplier_billing_company_name) { $mpdfHtml .= $invoiceData[0]->supplier_billing_company_name . '<br>'; }
									$mpdfHtml .= $invoiceData[0]->supplier_billing_address . '<br>';
									
									$supplier_billing_vendor_data = $this->getVendorDetailByVendorId($invoiceData[0]->supplier_billing_vendor_type);
									$mpdfHtml .= $supplier_billing_vendor_data['data']->vendor_name . '<br>';
									
									if (!empty($invoiceData[0]->supplier_billing_gstin_number)) { $mpdfHtml .= '<b>GSTIN/UIN:</b>' . $invoiceData[0]->supplier_billing_gstin_number; }
								$mpdfHtml .= '</td>';

								$mpdfHtml .= '<td style="vertical-align:top;text-align:right;padding-bottom:40px;width:48%;padding-left:2%;">';
									$mpdfHtml .= '<b>Address Of Recipient / Shipping Detail</b><br>';
									$mpdfHtml .= $invoiceData[0]->recipient_shipping_name . '<br>';
									if ($invoiceData[0]->recipient_shipping_company_name) { $mpdfHtml .= $invoiceData[0]->recipient_shipping_company_name . '<br>'; }
									$mpdfHtml .= $invoiceData[0]->recipient_shipping_address . '<br>';
									
									$recipient_shipping_vendor_data = $this->getVendorDetailByVendorId($invoiceData[0]->recipient_shipping_vendor_type);
									$mpdfHtml .= $recipient_shipping_vendor_data['data']->vendor_name . '<br>';
									
									if (!empty($invoiceData[0]->recipient_shipping_gstin_number)) { $mpdfHtml .= '<b>GSTIN/UIN:</b>' . $invoiceData[0]->recipient_shipping_gstin_number; }
								$mpdfHtml .= '</td>';

							$mpdfHtml .= '</tr>';
						$mpdfHtml .= '</table>';
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';

			$mpdfHtml .= '</table>';
			
			$mpdfHtml .= '<table border="1" style="border-collapse:collapse;width:100%;line-height:inherit;text-align:center;">';
				$mpdfHtml .= '<tr>';
					$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">S.No</td>';
					$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Goods/Services</td>';
					$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">HSN/SAC Code</td>';
					$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Item Description</td>';
					$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Advance Value (₹)</td>';
					$mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">CGST</td>';
					$mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">SGST</td>';
					$mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">IGST</td>';
					$mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">CESS</td>';
				$mpdfHtml .= '</tr>';

				$mpdfHtml .= '<tr class="heading">';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
				$mpdfHtml .= '</tr>';
					
				$counter = 1;
				$total_taxable_subtotal = 0.00;
				$total_cgst_amount = 0.00;
				$total_sgst_amount = 0.00;
				$total_igst_amount = 0.00;
				$total_cess_amount = 0.00;
				foreach ($invoiceData as $invData) {

					$mpdfHtml .= '<tr>';
						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $counter;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->item_name;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->item_hsncode;
						$mpdfHtml .= '</td>';
						
						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->item_description;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->taxable_subtotal;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->cgst_rate;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->cgst_amount;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->sgst_rate;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->sgst_amount;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->igst_rate;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->igst_amount;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->cess_rate;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->cess_amount;
						$mpdfHtml .= '</td>';

					$mpdfHtml .= '</tr>';

					$total_taxable_subtotal += $invData->taxable_subtotal;
					$total_cgst_amount += $invData->cgst_amount;
					$total_sgst_amount += $invData->sgst_amount;
					$total_igst_amount += $invData->igst_amount;
					$total_cess_amount += $invData->cess_amount;

					$counter++;
				}
				
				$mpdfHtml .= '<tr style="background:#d9edf7;">';
					$mpdfHtml .= '<td colspan="4" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Total Invoice Value</td>';
					$mpdfHtml .= '<td>'.$total_taxable_subtotal.'</td>';
					$mpdfHtml .= '<td>&nbsp;</td>';
					$mpdfHtml .= '<td>'.$total_cgst_amount.'</td>';
					$mpdfHtml .= '<td>&nbsp;</td>';
					$mpdfHtml .= '<td>'.$total_sgst_amount.'</td>';
					$mpdfHtml .= '<td>&nbsp;</td>';
					$mpdfHtml .= '<td>'.$total_igst_amount.'</td>';
					$mpdfHtml .= '<td>&nbsp;</td>';
					$mpdfHtml .= '<td>'.$total_cess_amount.'</td>';
				$mpdfHtml .= '</tr>';

				if($invoiceData[0]->supplier_billing_state === $invoiceData[0]->supply_place) {

					$mpdfHtml .= '<tr style="background:#e9ffdb;font-size:14px;">';
						$mpdfHtml .= '<td colspan="5" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Amount of Tax Subject to Reverse Charge</td>';
						$mpdfHtml .= '<td>-</td>';
						$mpdfHtml .= '<td>' . $total_cgst_amount . '</td>';
						$mpdfHtml .= '<td>-</td>';
						$mpdfHtml .= '<td>' . $total_sgst_amount . '</td>';
						$mpdfHtml .= '<td>-</td>';
						$mpdfHtml .= '<td>0.00</td>';
						$mpdfHtml .= '<td>-</td>';
						$mpdfHtml .= '<td>' . $total_cess_amount . '</td>';
					$mpdfHtml .= '</tr>';
				} else {

					$mpdfHtml .= '<tr style="background:#e9ffdb;font-size:14px;">';
						$mpdfHtml .= '<td colspan="5" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Amount of Tax Subject to Reverse Charge</td>';
						$mpdfHtml .= '<td>-</td>';
						$mpdfHtml .= '<td>0.00</td>';
						$mpdfHtml .= '<td>-</td>';
						$mpdfHtml .= '<td>0.00</td>';
						$mpdfHtml .= '<td>-</td>';
						$mpdfHtml .= '<td>' . $total_igst_amount . '</td>';
						$mpdfHtml .= '<td>-</td>';
						$mpdfHtml .= '<td>' . $total_cess_amount . '</td>';
					$mpdfHtml .= '</tr>';
				}

				$mpdfHtml .= '<tr style="background:#ffefbf;">';
					$mpdfHtml .= '<td colspan="13" style="padding:5px;vertical-align:top;text-align:right;font-weight:bold;">';
						$mpdfHtml .= 'Total Invoice Value (In Figure): ₹' . $invoiceData[0]->invoice_total_value;
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';

				$invoice_total_value_words = $this->convert_number_to_words($invoiceData[0]->invoice_total_value);

				$mpdfHtml .= '<tr style="background:#f2dede;">';
					$mpdfHtml .= '<td colspan="13" style="padding:5px;vertical-align:top;text-align:right;font-weight:bold;">';
						$mpdfHtml .= 'Total Invoice Value (In Words): ' . ucwords($invoice_total_value_words);
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';

			$mpdfHtml .= '</table>';

			$mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;" cellpadding="0" cellspacing="0">';

				if(!empty($invoiceData[0]->description)) {
					$mpdfHtml .= '<tr class="description">';
						$mpdfHtml .= '<td colspan="2" style="padding-top:20px;vertical-align:top;">';
							$mpdfHtml .= '<p><b>Additional Notes:</b> '. $invoiceData[0]->description .'</p>';
						$mpdfHtml .= '</td>';
					$mpdfHtml .= '</tr>';
				}

			$mpdfHtml .= '</table>';
		$mpdfHtml .= '</div>';

		return $mpdfHtml;
	}

	/* generate refund voucher invoice html */
    public function generatePurchaseRFInvoiceHtml($invoiceid) {

        $currentFinancialYear = $this->generateFinancialYear();

		$invoiceData = $this->get_results("select 
												ci.*, 
												cii.purchase_invoice_item_id, 
												cii.item_id, 
												cii.item_name, 
												cii.item_hsncode, 
												cii.item_description, 
												cii.taxable_subtotal, 
												cii.cgst_rate, 
												cii.cgst_amount, 
												cii.sgst_rate, 
												cii.sgst_amount, 
												cii.igst_rate, 
												cii.igst_amount, 
												cii.cess_rate, 
												cii.cess_amount, 
												cii.total 
												from 
												" . $this->tableNames['client_purchase_invoice'] ." as ci INNER JOIN " . $this->tableNames['client_purchase_invoice_item'] ." as cii ON ci.purchase_invoice_id = cii.purchase_invoice_id where ci.purchase_invoice_id = ".$invoiceid." AND ci.invoice_type = 'refundvoucherinvoice' AND ci.added_by = '".$this->sanitize($_SESSION['user_detail']['user_id'])."' AND cii.added_by = '".$this->sanitize($_SESSION['user_detail']['user_id'])."' AND ci.is_deleted='0' AND cii.is_deleted='0'");
		if (empty($invoiceData)) {
			return false;
		}

        $dataThemeSettingArr = $this->getUserThemeSetting($this->sanitize($_SESSION['user_detail']['user_id']));

        $mpdfHtml = '';
		$mpdfHtml .= '<div style="margin:auto;font-size:16px;line-height:24px;color:#555;">';
			$mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;" cellpadding="0" cellspacing="0">';
				$mpdfHtml .= '<tr>';
					$mpdfHtml .= '<td colspan="2" style="vertical-align:top;">';
						$mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;">';
							$mpdfHtml .= '<tr>';
								$mpdfHtml .= '<td style="font-size:45px;line-height:45px;color:#333;padding:5px;vertical-align:top;padding-bottom:20px;">';

									if (isset($dataThemeSettingArr['data']->theme_logo) && $dataThemeSettingArr['data']->theme_logo != "") {
										$mpdfHtml .= '<img src="upload/theme-logo/' . $dataThemeSettingArr['data']->theme_logo . '" style="width:100%;max-width:300px;">';
									} else {
										$mpdfHtml .= '<img src="image/gst-k-logo.png" style="width:100%;max-width:300px;">';
									}

								$mpdfHtml .= '</td>';
								
								$mpdfHtml .= '<td style="vertical-align:top;text-align:right;padding-bottom:20px;">';
									$mpdfHtml .= '<b>Invoice #</b>: ' . $invoiceData[0]->serial_number . '<br>';
									$mpdfHtml .= '<b>Reference #</b>: ' . $invoiceData[0]->reference_number . '<br>';
									$mpdfHtml .= '<b>Type:</b> Refund Voucher<br>';
									$mpdfHtml .= '<b>Nature:</b> Purchase Invoice<br>';
									$mpdfHtml .= '<b>Invoice Date:</b>' . $invoiceData[0]->invoice_date;
								$mpdfHtml .= '</td>';
							$mpdfHtml .= '</tr>';
						$mpdfHtml .= '</table>';
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';
				
				$supply_place_data = $this->getStateDetailByStateId($invoiceData[0]->supply_place);

				
				$mpdfHtml .= '<tr>';
					$mpdfHtml .= '<td colspan="2" style="vertical-align:top;">';
						$mpdfHtml .= '<table style="width:100%;line-height:inherit;">';
							$mpdfHtml .= '<tr>';
								$mpdfHtml .= '<td style="text-align:left;vertical-align:top;padding-bottom:20px;width:48%;padding-right:2%;">';
									$mpdfHtml .= $invoiceData[0]->company_name . '<br>';
									$mpdfHtml .= $invoiceData[0]->company_address . '<br>';
									if(!empty($invoiceData[0]->company_email)) { $mpdfHtml .= '<b>Email:</b> ' . $invoiceData[0]->company_email . '<br>'; }
									if(!empty($invoiceData[0]->company_phone_number)) { $mpdfHtml .= '<b>Phone:</b> ' . $invoiceData[0]->company_phone_number . '<br>'; }
									$panFromGTIN = substr(substr($invoiceData[0]->company_gstin_number, 2), 0, -3);
									$mpdfHtml .= '<b>PAN:</b> ' . $panFromGTIN  . '<br>';
									$mpdfHtml .= '<b>GSTIN:</b> ' . $invoiceData[0]->company_gstin_number;
								$mpdfHtml .= '</td>';
								
								$mpdfHtml .= '<td style="vertical-align:top;text-align:right;padding-bottom:20px;width:48%;padding-left:2%;">';

									if (isset($invoiceData[0]->supply_place) && $invoiceData[0]->supply_place > 0) {
										if($supply_place_data['data']->state_tin == 97) {
											$mpdfHtml .= '<b>Place Of Supply:</b> ' . $supply_place_data['data']->state_name . '<br>';
										} else {
											$mpdfHtml .= '<b>Place Of Supply:</b> ' . $supply_place_data['data']->state_name . '(' . $supply_place_data['data']->state_tin . ')' . '<br>';
										}
									}

									if ($invoiceData[0]->is_tax_payable == '1') {
										$mpdfHtml .= '<b>Reverse Charge:</b> Yes<br>';
									} else {
										$mpdfHtml .= '<b>Reverse Charge:</b> No<br>';
									}

									$dataReceiptVoucherRow = $this->get_row("select * from ".$this->tableNames['client_purchase_invoice']." where purchase_invoice_id = '".$invoiceData[0]->refund_voucher_receipt."' AND invoice_type = 'receiptvoucherinvoice' AND is_deleted='0' AND added_by = ".$this->sanitize($_SESSION['user_detail']['user_id']));
									if(!empty($dataReceiptVoucherRow)) {
										$mpdfHtml .= '<b>Receipt Voucher Serial:</b> '. $dataReceiptVoucherRow->serial_number .'<br>';
										$mpdfHtml .= '<b>Receipt Voucher Reference:</b> '. $dataReceiptVoucherRow->reference_number .'<br>';
										$mpdfHtml .= '<b>Receipt Voucher Date:</b> '. $dataReceiptVoucherRow->invoice_date .'<br>';
									}
									
									if ($invoiceData[0]->is_canceled == 1) { $mpdfHtml .= '<b>Canceled Invoice:</b> Canceled'; }

								$mpdfHtml .= '</td>';
							$mpdfHtml .= '</tr>';
						$mpdfHtml .= '</table>';
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';
				
				$mpdfHtml .= '<tr>';
					$mpdfHtml .= '<td colspan="2" style="vertical-align:top;">';
						$mpdfHtml .= '<table style="width:100%;line-height:inherit;">';
							$mpdfHtml .= '<tr>';
						   
								$mpdfHtml .= '<td style="vertical-align:top;text-align:left;padding-bottom:40px;width:48%;padding-right:2%;">';
									$mpdfHtml .= '<b>Supplier Detail</b><br>';
									$mpdfHtml .= html_entity_decode($invoiceData[0]->supplier_billing_name) . '<br>';
									if ($invoiceData[0]->supplier_billing_company_name) { $mpdfHtml .= $invoiceData[0]->supplier_billing_company_name . '<br>'; }
									$mpdfHtml .= $invoiceData[0]->supplier_billing_address . '<br>';
									
									$supplier_billing_vendor_data = $this->getVendorDetailByVendorId($invoiceData[0]->supplier_billing_vendor_type);
									$mpdfHtml .= $supplier_billing_vendor_data['data']->vendor_name . '<br>';
									
									if (!empty($invoiceData[0]->supplier_billing_gstin_number)) { $mpdfHtml .= '<b>GSTIN/UIN:</b>' . $invoiceData[0]->supplier_billing_gstin_number; }
								$mpdfHtml .= '</td>';

								$mpdfHtml .= '<td style="vertical-align:top;text-align:right;padding-bottom:40px;width:48%;padding-left:2%;">';
									$mpdfHtml .= '<b>Address Of Recipient / Shipping Detail</b><br>';
									$mpdfHtml .= $invoiceData[0]->recipient_shipping_name . '<br>';
									if ($invoiceData[0]->recipient_shipping_company_name) { $mpdfHtml .= $invoiceData[0]->recipient_shipping_company_name . '<br>'; }
									$mpdfHtml .= $invoiceData[0]->recipient_shipping_address . '<br>';
									
									$recipient_shipping_vendor_data = $this->getVendorDetailByVendorId($invoiceData[0]->recipient_shipping_vendor_type);
									$mpdfHtml .= $recipient_shipping_vendor_data['data']->vendor_name . '<br>';
									
									if (!empty($invoiceData[0]->recipient_shipping_gstin_number)) { $mpdfHtml .= '<b>GSTIN/UIN:</b>' . $invoiceData[0]->recipient_shipping_gstin_number; }
								$mpdfHtml .= '</td>';

							$mpdfHtml .= '</tr>';
						$mpdfHtml .= '</table>';
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';
			$mpdfHtml .= '</table>';
			
			$mpdfHtml .= '<table border="1" style="border-collapse:collapse;width:100%;line-height:inherit;text-align:center;">';
				$mpdfHtml .= '<tr>';
					$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">S.No</td>';
					$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Goods/Services</td>';
					$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">HSN/SAC Code</td>';
					$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Item Description</td>';
					$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Refund Value (₹)</td>';
					$mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">CGST</td>';
					$mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">SGST</td>';
					$mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">IGST</td>';
					$mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">CESS</td>';
				$mpdfHtml .= '</tr>';

				$mpdfHtml .= '<tr class="heading">';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
				$mpdfHtml .= '</tr>';

				$counter = 1;
				$total_taxable_subtotal = 0.00;
				$total_cgst_amount = 0.00;
				$total_sgst_amount = 0.00;
				$total_igst_amount = 0.00;
				$total_cess_amount = 0.00;
				foreach ($invoiceData as $invData) {
					
					$mpdfHtml .= '<tr>';
						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $counter;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->item_name;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->item_hsncode;
						$mpdfHtml .= '</td>';
					
						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->item_description;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->taxable_subtotal;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->cgst_rate;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->cgst_amount;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->sgst_rate;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->sgst_amount;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->igst_rate;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->igst_amount;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->cess_rate;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->cess_amount;
						$mpdfHtml .= '</td>';

					$mpdfHtml .= '</tr>';

					$total_taxable_subtotal += $invData->taxable_subtotal;
					$total_cgst_amount += $invData->cgst_amount;
					$total_sgst_amount += $invData->sgst_amount;
					$total_igst_amount += $invData->igst_amount;
					$total_cess_amount += $invData->cess_amount;

					$counter++;
				}
				
				$mpdfHtml .= '<tr style="background:#d9edf7;">';
					$mpdfHtml .= '<td colspan="4" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Total Invoice Value</td>';
					$mpdfHtml .= '<td>'.$total_taxable_subtotal.'</td>';
					$mpdfHtml .= '<td>&nbsp;</td>';
					$mpdfHtml .= '<td>'.$total_cgst_amount.'</td>';
					$mpdfHtml .= '<td>&nbsp;</td>';
					$mpdfHtml .= '<td>'.$total_sgst_amount.'</td>';
					$mpdfHtml .= '<td>&nbsp;</td>';
					$mpdfHtml .= '<td>'.$total_igst_amount.'</td>';
					$mpdfHtml .= '<td>&nbsp;</td>';
					$mpdfHtml .= '<td>'.$total_cess_amount.'</td>';
				$mpdfHtml .= '</tr>';
				
				if ($invoiceData[0]->is_tax_payable == "1") {

					if ($invoiceData[0]->supplier_billing_state === $invoiceData[0]->supply_place) {

						$mpdfHtml .= '<tr style="background:#e9ffdb;font-size:14px;">';
						$mpdfHtml .= '<td colspan="5" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Amount of Tax Subject to Reverse Charge</td>';
						$mpdfHtml .= '<td>-</td>';
						$mpdfHtml .= '<td>' . $total_cgst_amount . '</td>';
						$mpdfHtml .= '<td>-</td>';
						$mpdfHtml .= '<td>' . $total_sgst_amount . '</td>';
						$mpdfHtml .= '<td>-</td>';
						$mpdfHtml .= '<td>0.00</td>';
						$mpdfHtml .= '<td>-</td>';
						$mpdfHtml .= '<td>' . $total_cess_amount . '</td>';
						$mpdfHtml .= '</tr>';
					} else {

						$mpdfHtml .= '<tr style="background:#e9ffdb;font-size:14px;">';
						$mpdfHtml .= '<td colspan="5" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Amount of Tax Subject to Reverse Charge</td>';
						$mpdfHtml .= '<td>-</td>';
						$mpdfHtml .= '<td>0.00</td>';
						$mpdfHtml .= '<td>-</td>';
						$mpdfHtml .= '<td>0.00</td>';
						$mpdfHtml .= '<td>-</td>';
						$mpdfHtml .= '<td>' . $total_igst_amount . '</td>';
						$mpdfHtml .= '<td>-</td>';
						$mpdfHtml .= '<td>' . $total_cess_amount . '</td>';
						$mpdfHtml .= '</tr>';
					}
				}
				
				$mpdfHtml .= '<tr style="background:#ffefbf;">';
					$mpdfHtml .= '<td colspan="13" style="padding:5px;vertical-align:top;text-align:right;font-weight:bold;">';
						$mpdfHtml .= 'Total Invoice Value (In Figure): ₹' . $invoiceData[0]->invoice_total_value;
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';

				$invoice_total_value_words = $this->convert_number_to_words($invoiceData[0]->invoice_total_value);

				$mpdfHtml .= '<tr style="background:#f2dede;">';
					$mpdfHtml .= '<td colspan="13" style="padding:5px;vertical-align:top;text-align:right;font-weight:bold;">';
						$mpdfHtml .= 'Total Invoice Value (In Words): ' . ucwords($invoice_total_value_words);
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';

			$mpdfHtml .= '</table>';
			
			$mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;" cellpadding="0" cellspacing="0">';

				if(!empty($invoiceData[0]->description)) {
					$mpdfHtml .= '<tr class="description">';
						$mpdfHtml .= '<td colspan="2" style="padding-top:20px;vertical-align:top;">';
							$mpdfHtml .= '<p><b>Additional Notes:</b> '. $invoiceData[0]->description .'</p>';
						$mpdfHtml .= '</td>';
					$mpdfHtml .= '</tr>';
				}

			$mpdfHtml .= '</table>';
        $mpdfHtml .= '</div>';

        return $mpdfHtml;
    }
	
	/* generate purchase revised tax invoice html */
    public function generatePurchaseRTInvoiceHtml($invid) {

        $currentFinancialYear = $this->generateFinancialYear();

		/* get current user data */
		$dataCurrentUserArr = $this->getUserDetailsById($this->sanitize($_SESSION['user_detail']['user_id']));

        $invoiceData = $this->get_results("select 
											ci.*, 
											cii.purchase_invoice_item_id, 
											cii.item_id, 
											cii.item_name, 
											cii.item_hsncode, 
											cii.item_description, 
											cii.item_quantity, 
											cii.item_unit, 
											cii.item_unit_price, 
											cii.subtotal, 
											cii.discount, 
											cii.advance_amount, 
											cii.taxable_subtotal, 
											cii.cgst_rate, 
											cii.cgst_amount, 
											cii.sgst_rate, 
											cii.sgst_amount, 
											cii.igst_rate, 
											cii.igst_amount, 
											cii.cess_rate, 
											cii.cess_amount, 
											cii.total 
											from 
										" . $this->tableNames['client_purchase_invoice'] . " as ci INNER JOIN " . $this->tableNames['client_purchase_invoice_item'] . " as cii ON ci.purchase_invoice_id = cii.purchase_invoice_id where ci.purchase_invoice_id = " . $invid . " AND ci.invoice_type IN('revisedtaxinvoice', 'creditnote', 'debitnote') AND ci.added_by = '" . $this->sanitize($_SESSION['user_detail']['user_id']) . "' AND cii.added_by = '" . $this->sanitize($_SESSION['user_detail']['user_id']) . "' AND ci.is_deleted='0' AND cii.is_deleted='0'");

        if (empty($invoiceData)) {
            return false;
        }

        $dataThemeSettingArr = $this->getUserThemeSetting($this->sanitize($_SESSION['user_detail']['user_id']));

        $mpdfHtml = '';
			$mpdfHtml .= '<div style="margin:auto;font-size:16px;line-height:24px;color:#555;">';
				$mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;" cellpadding="0" cellspacing="0">';
					$mpdfHtml .= '<tr>';
						$mpdfHtml .= '<td colspan="2" style="vertical-align:top;">';
							$mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;">';
								$mpdfHtml .= '<tr>';
									$mpdfHtml .= '<td style="font-size:45px;line-height:45px;color:#333;padding:5px;vertical-align:top;padding-bottom:20px;">';

										if (isset($dataThemeSettingArr['data']->theme_logo) && $dataThemeSettingArr['data']->theme_logo != "") {
											$mpdfHtml .= '<img src="upload/theme-logo/' . $dataThemeSettingArr['data']->theme_logo . '" style="width:100%;max-width:300px;">';
										} else {
											$mpdfHtml .= '<img src="image/gst-k-logo.png" style="width:100%;max-width:300px;">';
										}

									$mpdfHtml .= '</td>';

									if($invoiceData[0]->invoice_type == "creditnote") { $invoiceType = "Credit Note"; } 
									else if($invoiceData[0]->invoice_type == "debitnote") { $invoiceType = "Debit Note"; } 
									else { $invoiceType = "Revised Tax Invoice"; }

									$mpdfHtml .= '<td style="vertical-align:top;text-align:right;padding-bottom:20px;">';
										$mpdfHtml .= '<b>Invoice #</b>: ' . $invoiceData[0]->serial_number . '<br>';
										$mpdfHtml .= '<b>Reference #</b>: ' . $invoiceData[0]->reference_number . '<br>';
										$mpdfHtml .= '<b>Type:</b> ' . $invoiceType . '<br>';
										$mpdfHtml .= '<b>Nature:</b> Purchase Invoice<br>';
										$mpdfHtml .= '<b>Invoice Date:</b> ' . $invoiceData[0]->invoice_date;
								$mpdfHtml .= '</td>';
							$mpdfHtml .= '</tr>';
						$mpdfHtml .= '</table>';
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';

				$supply_place_data = $this->getStateDetailByStateId($invoiceData[0]->supply_place);

				$mpdfHtml .= '<tr>';
					$mpdfHtml .= '<td colspan="2" style="vertical-align:top;">';
						$mpdfHtml .= '<table style="width:100%;line-height:inherit;">';
							$mpdfHtml .= '<tr>';
								$mpdfHtml .= '<td style="text-align:left;vertical-align:top;padding-bottom:20px;width:48%;padding-right:2%;">';
									$mpdfHtml .= $invoiceData[0]->company_name . '<br>';
									$mpdfHtml .= $invoiceData[0]->company_address . '<br>';
									if(!empty($invoiceData[0]->company_email)) { $mpdfHtml .= '<b>Email:</b> ' . $invoiceData[0]->company_email . '<br>'; }
									if(!empty($invoiceData[0]->company_phone_number)) { $mpdfHtml .= '<b>Phone:</b> ' . $invoiceData[0]->company_phone_number . '<br>'; }
									$panFromGTIN = substr(substr($invoiceData[0]->company_gstin_number, 2), 0, -3);
									$mpdfHtml .= '<b>PAN:</b> ' . $panFromGTIN  . '<br>';
									$mpdfHtml .= '<b>GSTIN:</b> ' . $invoiceData[0]->company_gstin_number;
								$mpdfHtml .= '</td>';

								$mpdfHtml .= '<td style="vertical-align:top;text-align:right;padding-bottom:20px;width:48%;padding-left:2%;">';

									if (isset($invoiceData[0]->supply_place) && $invoiceData[0]->supply_place > 0) {
										if($supply_place_data['data']->state_tin == 97) {
											$mpdfHtml .= '<b>Place Of Supply:</b> ' . $supply_place_data['data']->state_name . '<br>';
										} else {
											$mpdfHtml .= '<b>Place Of Supply:</b> ' . $supply_place_data['data']->state_name . '(' . $supply_place_data['data']->state_tin . ')' . '<br>';
										}
									}

									$mpdfHtml .= '<b>Reason Issuing Document:</b> ' . $invoiceData[0]->reason_issuing_document . '<br>';

									if($invoiceData[0]->invoice_corresponding_type == "taxinvoice") { $invoiceType = "Tax Invoice"; } 
									else if($invoiceData[0]->invoice_corresponding_type == "billofsupplyinvoice") { $invoiceType = "Bill of Supply Invoice"; }

									$mpdfHtml .= '<b>Corresponding Type:</b> ' . $invoiceType . '<br>';

									$dataCorresDocumentRow = $this->get_row("select * from " . $this->tableNames['client_purchase_invoice'] . " where purchase_invoice_id = '".$invoiceData[0]->corresponding_document_number."' AND invoice_type = '".$invoiceData[0]->invoice_corresponding_type."' AND is_deleted='0' AND added_by = ".$this->sanitize($_SESSION['user_detail']['user_id']));

									if(!empty($dataCorresDocumentRow)) {
										$mpdfHtml .= '<b>Document Serial:</b> '. $dataCorresDocumentRow->serial_number .'<br>';
										$mpdfHtml .= '<b>Document Reference:</b> '. $dataCorresDocumentRow->reference_number .'<br>';
										$mpdfHtml .= '<b>Document Date:</b> '. $dataCorresDocumentRow->invoice_date .'<br>';
									}

									if ($invoiceData[0]->is_canceled == 1) { $mpdfHtml .= '<b>Canceled Invoice:</b> Canceled'; }

								$mpdfHtml .= '</td>';
							$mpdfHtml .= '</tr>';
						$mpdfHtml .= '</table>';
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';

				$mpdfHtml .= '<tr>';
					$mpdfHtml .= '<td colspan="2" style="vertical-align:top;">';
						$mpdfHtml .= '<table style="width:100%;line-height:inherit;">';
							$mpdfHtml .= '<tr>';
						   
								$mpdfHtml .= '<td style="vertical-align:top;text-align:left;padding-bottom:40px;width:48%;padding-right:2%;">';
									$mpdfHtml .= '<b>Supplier Detail</b><br>';
									$mpdfHtml .= html_entity_decode($invoiceData[0]->supplier_billing_name) . '<br>';
									if ($invoiceData[0]->supplier_billing_company_name) { $mpdfHtml .= $invoiceData[0]->supplier_billing_company_name . '<br>'; }
									$mpdfHtml .= $invoiceData[0]->supplier_billing_address . '<br>';
									
									$supplier_billing_vendor_data = $this->getVendorDetailByVendorId($invoiceData[0]->supplier_billing_vendor_type);
									$mpdfHtml .= $supplier_billing_vendor_data['data']->vendor_name . '<br>';
									
									if (!empty($invoiceData[0]->supplier_billing_gstin_number)) { $mpdfHtml .= '<b>GSTIN/UIN:</b>' . $invoiceData[0]->supplier_billing_gstin_number; }
								$mpdfHtml .= '</td>';

								$mpdfHtml .= '<td style="vertical-align:top;text-align:right;padding-bottom:40px;width:48%;padding-left:2%;">';
									$mpdfHtml .= '<b>Address Of Recipient / Shipping Detail</b><br>';
									$mpdfHtml .= $invoiceData[0]->recipient_shipping_name . '<br>';
									if ($invoiceData[0]->recipient_shipping_company_name) { $mpdfHtml .= $invoiceData[0]->recipient_shipping_company_name . '<br>'; }
									$mpdfHtml .= $invoiceData[0]->recipient_shipping_address . '<br>';
									
									$recipient_shipping_vendor_data = $this->getVendorDetailByVendorId($invoiceData[0]->recipient_shipping_vendor_type);
									$mpdfHtml .= $recipient_shipping_vendor_data['data']->vendor_name . '<br>';
									
									if (!empty($invoiceData[0]->recipient_shipping_gstin_number)) { $mpdfHtml .= '<b>GSTIN/UIN:</b>' . $invoiceData[0]->recipient_shipping_gstin_number; }
								$mpdfHtml .= '</td>';

							$mpdfHtml .= '</tr>';
						$mpdfHtml .= '</table>';
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';

			$mpdfHtml .= '</table>';

			$mpdfHtml .= '<table border="1" style="border-collapse:collapse;width:100%;line-height:inherit;text-align:center;">';
				$mpdfHtml .= '<tr>';
					$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">S.No</td>';
					$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Goods/Services</td>';
					$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">HSN/SAC Code</td>';
					$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Item Description</td>';
					$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Qty</td>';
					$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Unit</td>';
					$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Rate (₹)</td>';
					$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Total (₹)</td>';
					$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Discount(%)</td>';
					$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Taxable Value (₹)</td>';
					$mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">CGST</td>';
					$mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">SGST</td>';
					$mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">IGST</td>';
					$mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">CESS</td>';
				$mpdfHtml .= '</tr>';

				$mpdfHtml .= '<tr class="heading">';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">(%)</td>';
					$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;font-weight:bold;">Amt (₹)</td>';
				$mpdfHtml .= '</tr>';

				$counter = 1;
				$total_taxable_subtotal = 0.00;
				$total_cgst_amount = 0.00;
				$total_sgst_amount = 0.00;
				$total_igst_amount = 0.00;
				$total_cess_amount = 0.00;
				foreach ($invoiceData as $invData) {

					$mpdfHtml .= '<tr>';
						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $counter;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->item_name;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->item_hsncode;
						$mpdfHtml .= '</td>';
						
						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->item_description;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->item_quantity;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->item_unit;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->item_unit_price;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->subtotal;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->discount;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->taxable_subtotal;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->cgst_rate;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->cgst_amount;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->sgst_rate;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->sgst_amount;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->igst_rate;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->igst_amount;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->cess_rate;
						$mpdfHtml .= '</td>';

						$mpdfHtml .= '<td style="padding:5px;vertical-align:top;">';
							$mpdfHtml .= $invData->cess_amount;
						$mpdfHtml .= '</td>';

					$mpdfHtml .= '</tr>';

					$total_taxable_subtotal += $invData->taxable_subtotal;
					$total_cgst_amount += $invData->cgst_amount;
					$total_sgst_amount += $invData->sgst_amount;
					$total_igst_amount += $invData->igst_amount;
					$total_cess_amount += $invData->cess_amount;
					$counter++;
				}

				$mpdfHtml .= '<tr style="background:#d9edf7;">';
					$mpdfHtml .= '<td colspan="9" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Total Invoice Value</td>';
					$mpdfHtml .= '<td>'.$total_taxable_subtotal.'</td>';
					$mpdfHtml .= '<td>&nbsp;</td>';
					$mpdfHtml .= '<td>'.$total_cgst_amount.'</td>';
					$mpdfHtml .= '<td>&nbsp;</td>';
					$mpdfHtml .= '<td>'.$total_sgst_amount.'</td>';
					$mpdfHtml .= '<td>&nbsp;</td>';
					$mpdfHtml .= '<td>'.$total_igst_amount.'</td>';
					$mpdfHtml .= '<td>&nbsp;</td>';
					$mpdfHtml .= '<td>'.$total_cess_amount.'</td>';
				$mpdfHtml .= '</tr>';

				$mpdfHtml .= '<tr style="background:#ffefbf;">';
					$mpdfHtml .= '<td colspan="18" style="padding:5px;vertical-align:top;text-align:right;font-weight:bold;">';
						$mpdfHtml .= 'Total Invoice Value (In Figure): ₹' . $invoiceData[0]->invoice_total_value;
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';

				$invoice_total_value_words = $this->convert_number_to_words($invoiceData[0]->invoice_total_value);

				$mpdfHtml .= '<tr style="background:#f2dede;">';
					$mpdfHtml .= '<td colspan="18" style="padding:5px;vertical-align:top;text-align:right;font-weight:bold;">';
						$mpdfHtml .= 'Total Invoice Value (In Words): ' . ucwords($invoice_total_value_words);
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';

			$mpdfHtml .= '</table>';

			$mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;" cellpadding="0" cellspacing="0">';

				if(!empty($invoiceData[0]->description)) {
					$mpdfHtml .= '<tr class="description">';
						$mpdfHtml .= '<td colspan="2" style="padding-top:20px;vertical-align:top;">';
							$mpdfHtml .= '<p><b>Additional Notes:</b> '. $invoiceData[0]->description .'</p>';
						$mpdfHtml .= '</td>';
					$mpdfHtml .= '</tr>';
				}

			$mpdfHtml .= '</table>';
        $mpdfHtml .= '</div>';

        return $mpdfHtml;
    }
}
?>