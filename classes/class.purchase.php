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

	/* generate invoice html */
	public function generatePurchaseInvoiceHtml($invoiceid) {

		$currentFinancialYear = $this->generateFinancialYear();

		$invoiceData = $this->get_results("select 
												ci.*, 
												cii.purchase_invoice_item_id, 
												cii.item_id, 
												cii.item_name, 
												cii.item_hsncode, 
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
					$mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;">';
						$mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;">';
							$mpdfHtml .= '<tr>';
								$mpdfHtml .= '<td style="font-size:45px;line-height:45px;color:#333;padding:5px;vertical-align:top;padding-bottom:20px;">';

									if (isset($dataThemeSettingArr['data']->theme_logo) && $dataThemeSettingArr['data']->theme_logo != "") {
										$mpdfHtml .= '<img src="' . PROJECT_URL . '/upload/theme-logo/' . $dataThemeSettingArr['data']->theme_logo . '" style="width:100%;max-width:300px;">';
									} else {
										$mpdfHtml .= '<img src="' . PROJECT_URL . '/image/gst-k-logo.png" style="width:100%;max-width:300px;">';
									}

								$mpdfHtml .= '</td>';
								
								if($invoiceData[0]->invoice_type == "importinvoice") { $invoiceType = "Import Invoice"; } 
								else if($invoiceData[0]->invoice_type == "sezunitinvoic") { $invoiceType = "SEZ Unit Invoice"; } 
								else if($invoiceData[0]->invoice_type == "deemedimportinvoice") { $invoiceType = "Deemed Import Invoice"; } 
								else { $invoiceType = "Tax Invoice"; }
								
								$mpdfHtml .= '<td style="padding:5px;vertical-align:top;text-align:right;padding-bottom:20px;">';
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
					$mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;">';
						$mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;">';
							$mpdfHtml .= '<tr>';

								$mpdfHtml .= '<td style="padding:5px;vertical-align:top;padding-bottom:20px;">';
									$mpdfHtml .= $invoiceData[0]->company_name . '<br>';
									$mpdfHtml .= $invoiceData[0]->company_address . '<br>';
									$mpdfHtml .= '<b>GSTIN:</b> ' . $invoiceData[0]->company_gstin_number;
								$mpdfHtml .= '</td>';

								$mpdfHtml .= '<td style="padding:5px;vertical-align:top;text-align:right;padding-bottom:20px;">';

									if($invoiceData[0]->invoice_type === "importinvoice") {
			
										if($invoiceData[0]->import_supply_meant == "withpayment") { $importSupplyMeant = "Payment of Integrated Tax"; } 
										else { $importSupplyMeant = "Without Payment of Integrated Tax"; }

										$mpdfHtml .= '<b>Import Supply Meant:</b> ' . $importSupplyMeant . '<br>';

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
											$mpdfHtml .= '<b>Place Of Supply:</b>' . $supply_place_data['data']->state_name . '<br>';
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
					$mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;">';
						$mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;">';
							$mpdfHtml .= '<tr>';
								$mpdfHtml .= '<td style="padding:5px;vertical-align:top;padding-bottom:40px;width:50%;">';
									$mpdfHtml .= '<b>Supplier Detail</b><br>';
									$mpdfHtml .= html_entity_decode($invoiceData[0]->supplier_billing_name) . '<br>';
									if ($invoiceData[0]->supplier_billing_company_name) { $mpdfHtml .= $invoiceData[0]->supplier_billing_company_name . '<br>'; }
									$mpdfHtml .= $invoiceData[0]->supplier_billing_address . '<br>';
									
									$supplier_billing_vendor_data = $this->getVendorDetailByVendorId($invoiceData[0]->supplier_billing_vendor_type);
									$mpdfHtml .= $supplier_billing_vendor_data['data']->vendor_name . '<br>';
									
									if (!empty($invoiceData[0]->supplier_billing_gstin_number)) { $mpdfHtml .= '<b>Supplier GSTIN:</b>' . $invoiceData[0]->supplier_billing_gstin_number; }
								$mpdfHtml .= '</td>';

								$mpdfHtml .= '<td style="padding:5px;vertical-align:top;text-align:right;padding-bottom:40px;width:50%;">';
									$mpdfHtml .= '<b>Address Of Recipient / Shipping Detail</b><br>';
									$mpdfHtml .= $invoiceData[0]->recipient_shipping_name . '<br>';
									if ($invoiceData[0]->recipient_shipping_company_name) { $mpdfHtml .= $invoiceData[0]->recipient_shipping_company_name . '<br>'; }
									$mpdfHtml .= $invoiceData[0]->recipient_shipping_address . '<br>';
									
									$recipient_shipping_vendor_data = $this->getVendorDetailByVendorId($invoiceData[0]->recipient_shipping_vendor_type);
									$mpdfHtml .= $recipient_shipping_vendor_data['data']->vendor_name . '<br>';
									
									if (!empty($invoiceData[0]->recipient_shipping_gstin_number)) { $mpdfHtml .= '<b>Recipient GSTIN:</b>' . $invoiceData[0]->recipient_shipping_gstin_number; }
								$mpdfHtml .= '</td>';

							$mpdfHtml .= '</tr>';
						$mpdfHtml .= '</table>';
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';

				$mpdfHtml .= '<tr>';

					$mpdfHtml .= '<td colspan="2">';

						$mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:center;">';
							$mpdfHtml .= '<tr>';
								$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">S.No</td>';
								$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Goods/Services</td>';
								$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">HSN/SAC Code</td>';
								$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Qty</td>';
								$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Unit</td>';
								$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Rate</td>';
								$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Total</td>';
								$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Discount(%)</td>';

								if($invoiceData[0]->advance_adjustment == 1) {
									$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Discount(%)</td>';
								}

								$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Taxable Value</td>';
								$mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">CGST</td>';
								$mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">SGST</td>';
								$mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">IGST</td>';
								$mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">CESS</td>';
							$mpdfHtml .= '</tr>';
							
							$mpdfHtml .= '<tr class="heading">';
								$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">(%)</td>';
								$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Amt (₹)</td>';
								$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">(%)</td>';
								$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Amt (₹)</td>';
								$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">(%)</td>';
								$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Amt (₹)</td>';
								$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">(%)</td>';
								$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Amt (₹)</td>';
							$mpdfHtml .= '</tr>';

							$counter = 1;
							$total_taxable_subtotal = 0.00;
							$total_cgst_amount = 0.00;
							$total_sgst_amount = 0.00;
							$total_igst_amount = 0.00;
							$total_cess_amount = 0.00;
							foreach($invoiceData as $invData) {

								$mpdfHtml .= '<tr>';
									$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
										$mpdfHtml .= $counter;
									$mpdfHtml .= '</td>';

									$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
										$mpdfHtml .= $invData->item_name;
									$mpdfHtml .= '</td>';

									$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
										$mpdfHtml .= $invData->item_hsncode;
									$mpdfHtml .= '</td>';

									$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
										$mpdfHtml .= $invData->item_quantity;
									$mpdfHtml .= '</td>';

									$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
										$mpdfHtml .= $invData->item_unit;
									$mpdfHtml .= '</td>';

									$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
										$mpdfHtml .= $invData->item_unit_price;
									$mpdfHtml .= '</td>';

									$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
										$mpdfHtml .= $invData->subtotal;
									$mpdfHtml .= '</td>';

									$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
										$mpdfHtml .= $invData->discount;
									$mpdfHtml .= '</td>';

									if($invoiceData[0]->advance_adjustment == 1) {
										$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
											$mpdfHtml .= $invData->advance_amount;
										$mpdfHtml .= '</td>';
									}

									$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
										$mpdfHtml .= $invData->taxable_subtotal;
									$mpdfHtml .= '</td>';
									
									$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
										$mpdfHtml .= $invData->cgst_rate;
									$mpdfHtml .= '</td>';

									$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
										$mpdfHtml .= $invData->cgst_amount;
									$mpdfHtml .= '</td>';

									$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
										$mpdfHtml .= $invData->sgst_rate;
									$mpdfHtml .= '</td>';

									$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
										$mpdfHtml .= $invData->sgst_amount;
									$mpdfHtml .= '</td>';

									$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
										$mpdfHtml .= $invData->igst_rate;
									$mpdfHtml .= '</td>';
									
									$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
										$mpdfHtml .= $invData->igst_amount;
									$mpdfHtml .= '</td>';
									
									$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
										$mpdfHtml .= $invData->cess_rate;
									$mpdfHtml .= '</td>';
									
									$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
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

							$mpdfHtml .= '<tr>';
								if($invoiceData[0]->advance_adjustment == 1) {
									$mpdfHtml .= '<td colspan="18" style="padding:5px;vertical-align:top;text-align:right;border-top:2px solid #eee;font-weight:bold;">';
								} else {
									$mpdfHtml .= '<td colspan="17" style="padding:5px;vertical-align:top;text-align:right;border-top:2px solid #eee;font-weight:bold;">';
								}
								   $mpdfHtml .= 'Total Invoice Value (In Figure): ' . $invoiceData[0]->invoice_total_value;
								$mpdfHtml .= '</td>';
							$mpdfHtml .= '</tr>';

							$invoice_total_value_words = $this->convert_number_to_words($invoiceData[0]->invoice_total_value);

							$mpdfHtml .= '<tr>';
								if($invoiceData[0]->advance_adjustment == 1) {
									$mpdfHtml .= '<td colspan="18" style="padding:5px;vertical-align:top;text-align:right;border-top:2px solid #eee;font-weight:bold;">';
								} else {
									$mpdfHtml .= '<td colspan="17" style="padding:5px;vertical-align:top;text-align:right;border-top:2px solid #eee;font-weight:bold;">';
								}
								   $mpdfHtml .= 'Total Invoice Value (In Words): ' . ucwords($invoice_total_value_words);
								$mpdfHtml .= '</td>';
							$mpdfHtml .= '</tr>';

							if($invoiceData[0]->supply_type === "Reverse Charge") {

								if($invoiceData[0]->supplier_billing_state === $invoiceData[0]->supply_place) {

									$mpdfHtml .= '<tr style="background:#e9ffdb;font-size:14px;">';
										
										if($invoiceData[0]->advance_adjustment == 1) {
											$mpdfHtml .= '<td colspan="10" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Value of supply subject to reverse charge</td>';
										} else {
											$mpdfHtml .= '<td colspan="9" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Value of supply subject to reverse charge</td>';
										}

										$mpdfHtml .= '<td>-</td>';
										$mpdfHtml .= '<td>₹'. $total_cgst_amount .'</td>';
										$mpdfHtml .= '<td>-</td>';
										$mpdfHtml .= '<td>₹'.$total_sgst_amount .'</td>';
										$mpdfHtml .= '<td>-</td>';
										$mpdfHtml .= '<td>₹0.00</td>';
										$mpdfHtml .= '<td>-</td>';
										$mpdfHtml .= '<td>₹'. $total_cess_amount .'</td>';
									$mpdfHtml .= '</tr>';
								} else {
								
									$mpdfHtml .= '<tr style="background:#e9ffdb;font-size:14px;">';

										if($invoiceData[0]->advance_adjustment == 1) {
											$mpdfHtml .= '<td colspan="10" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Amount of Tax Subject to Reverse Charge</td>';
										} else {
											$mpdfHtml .= '<td colspan="9" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Amount of Tax Subject to Reverse Charge</td>';
										}

										$mpdfHtml .= '<td>-</td>';
										$mpdfHtml .= '<td>₹0.00</td>';
										$mpdfHtml .= '<td>-</td>';
										$mpdfHtml .= '<td>₹0.00</td>';
										$mpdfHtml .= '<td>-</td>';
										$mpdfHtml .= '<td>₹'. $total_igst_amount .'</td>';
										$mpdfHtml .= '<td>-</td>';
										$mpdfHtml .= '<td>₹'. $total_cess_amount .'</td>';
									$mpdfHtml .= '</tr>';
								}
							}

						$mpdfHtml .= '</table>';

					$mpdfHtml .= '</td>';

				$mpdfHtml .= '</tr>';
				
				if(!empty($invoiceData[0]->description)) {
					$mpdfHtml .= '<tr class="description">';
						$mpdfHtml .= '<td colspan="2">';
							$mpdfHtml .= '<p><b>Description:</b> '. $invoiceData[0]->description .'</p>';
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
												" . $this->tableNames['client_purchase_invoice'] ." as ci INNER JOIN " . $this->tableNames['client_purchase_invoice_item'] ." as cii ON ci.purchase_invoice_id = cii.purchase_invoice_id where ci.purchase_invoice_id = ".$invoiceid." AND ci.invoice_type = 'receiptvoucherinvoice' AND ci.added_by = '".$this->sanitize($_SESSION['user_detail']['user_id'])."' AND cii.added_by = '".$this->sanitize($_SESSION['user_detail']['user_id'])."' AND ci.is_deleted='0' AND cii.is_deleted='0'");
		if (empty($invoiceData)) {
			return false;
		}

		$dataThemeSettingArr = $this->getUserThemeSetting($this->sanitize($_SESSION['user_detail']['user_id']));

		$mpdfHtml = '';
		$mpdfHtml .= '<div style="margin:auto;font-size:16px;line-height:24px;color:#555;">';
			$mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;" cellpadding="0" cellspacing="0">';
				$mpdfHtml .= '<tr>';
					$mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;">';
						$mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;">';
							$mpdfHtml .= '<tr>';
								$mpdfHtml .= '<td style="font-size:45px;line-height:45px;color:#333;padding:5px;vertical-align:top;padding-bottom:20px;">';

									if (isset($dataThemeSettingArr['data']->theme_logo) && $dataThemeSettingArr['data']->theme_logo != "") {
										$mpdfHtml .= '<img src="' . PROJECT_URL . '/upload/theme-logo/' . $dataThemeSettingArr['data']->theme_logo . '" style="width:100%;max-width:300px;">';
									} else {
										$mpdfHtml .= '<img src="' . PROJECT_URL . '/image/gst-k-logo.png" style="width:100%;max-width:300px;">';
									}

								$mpdfHtml .= '</td>';

								if($invoiceData[0]->invoice_type == "importinvoice") { $invoiceType = "Import Invoice"; } 
								else if($invoiceData[0]->invoice_type == "sezunitinvoic") { $invoiceType = "SEZ Unit Invoice"; } 
								else if($invoiceData[0]->invoice_type == "deemedimportinvoice") { $invoiceType = "Deemed Import Invoice"; } 
								else { $invoiceType = "Tax Invoice"; }
								
								$mpdfHtml .= '<td style="padding:5px;vertical-align:top;text-align:right;padding-bottom:20px;">';
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
					$mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;">';
						$mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;">';
							$mpdfHtml .= '<tr>';

								$mpdfHtml .= '<td style="padding:5px;vertical-align:top;padding-bottom:20px;">';
									$mpdfHtml .= $invoiceData[0]->company_name . '<br>';
									$mpdfHtml .= $invoiceData[0]->company_address . '<br>';
									$mpdfHtml .= '<b>GSTIN:</b> ' . $invoiceData[0]->company_gstin_number;
								$mpdfHtml .= '</td>';

								$mpdfHtml .= '<td style="padding:5px;vertical-align:top;text-align:right;padding-bottom:20px;">';

									if($invoiceData[0]->invoice_type === "importinvoice") {
			
										if($invoiceData[0]->import_supply_meant == "withpayment") { $importSupplyMeant = "Payment of Integrated Tax"; } 
										else { $importSupplyMeant = "Without Payment of Integrated Tax"; }

										$mpdfHtml .= '<b>Import Supply Meant:</b> ' . $importSupplyMeant . '<br>';

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
											$mpdfHtml .= '<b>Place Of Supply:</b>' . $supply_place_data['data']->state_name . '<br>';
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
					$mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;">';
						$mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;">';
							$mpdfHtml .= '<tr>';
								$mpdfHtml .= '<td style="padding:5px;vertical-align:top;padding-bottom:40px;width:50%;">';
									$mpdfHtml .= '<b>Supplier Detail</b><br>';
									$mpdfHtml .= html_entity_decode($invoiceData[0]->supplier_billing_name) . '<br>';
									if ($invoiceData[0]->supplier_billing_company_name) { $mpdfHtml .= $invoiceData[0]->supplier_billing_company_name . '<br>'; }
									$mpdfHtml .= $invoiceData[0]->supplier_billing_address . '<br>';
									
									$supplier_billing_vendor_data = $this->getVendorDetailByVendorId($invoiceData[0]->supplier_billing_vendor_type);
									$mpdfHtml .= $supplier_billing_vendor_data['data']->vendor_name . '<br>';
									
									if (!empty($invoiceData[0]->supplier_billing_gstin_number)) { $mpdfHtml .= '<b>Supplier GSTIN:</b>' . $invoiceData[0]->supplier_billing_gstin_number; }
								$mpdfHtml .= '</td>';

								$mpdfHtml .= '<td style="padding:5px;vertical-align:top;text-align:right;padding-bottom:40px;width:50%;">';
									$mpdfHtml .= '<b>Address Of Recipient / Shipping Detail</b><br>';
									$mpdfHtml .= $invoiceData[0]->recipient_shipping_name . '<br>';
									if ($invoiceData[0]->recipient_shipping_company_name) { $mpdfHtml .= $invoiceData[0]->recipient_shipping_company_name . '<br>'; }
									$mpdfHtml .= $invoiceData[0]->recipient_shipping_address . '<br>';
									
									$recipient_shipping_vendor_data = $this->getVendorDetailByVendorId($invoiceData[0]->recipient_shipping_vendor_type);
									$mpdfHtml .= $recipient_shipping_vendor_data['data']->vendor_name . '<br>';
									
									if (!empty($invoiceData[0]->recipient_shipping_gstin_number)) { $mpdfHtml .= '<b>Recipient GSTIN:</b>' . $invoiceData[0]->recipient_shipping_gstin_number; }
								$mpdfHtml .= '</td>';

							$mpdfHtml .= '</tr>';
						$mpdfHtml .= '</table>';
					$mpdfHtml .= '</td>';
				$mpdfHtml .= '</tr>';

				$mpdfHtml .= '<tr>';

					$mpdfHtml .= '<td colspan="2">';

						$mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:center;">';
							$mpdfHtml .= '<tr>';
								$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">S.No</td>';
								$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Goods/Services</td>';
								$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">HSN/SAC Code</td>';
								$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Qty</td>';
								$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Unit</td>';
								$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Rate</td>';
								$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Total</td>';
								$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Discount(%)</td>';

								if($invoiceData[0]->advance_adjustment == 1) {
									$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Discount(%)</td>';
								}

								$mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Taxable Value</td>';
								$mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">CGST</td>';
								$mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">SGST</td>';
								$mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">IGST</td>';
								$mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">CESS</td>';
							$mpdfHtml .= '</tr>';
							
							$mpdfHtml .= '<tr class="heading">';
								$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">(%)</td>';
								$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Amt (₹)</td>';
								$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">(%)</td>';
								$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Amt (₹)</td>';
								$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">(%)</td>';
								$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Amt (₹)</td>';
								$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">(%)</td>';
								$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Amt (₹)</td>';
							$mpdfHtml .= '</tr>';

							$counter = 1;
							$total_taxable_subtotal = 0.00;
							$total_cgst_amount = 0.00;
							$total_sgst_amount = 0.00;
							$total_igst_amount = 0.00;
							$total_cess_amount = 0.00;
							foreach($invoiceData as $invData) {

								$mpdfHtml .= '<tr>';
									$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
										$mpdfHtml .= $counter;
									$mpdfHtml .= '</td>';

									$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
										$mpdfHtml .= $invData->item_name;
									$mpdfHtml .= '</td>';

									$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
										$mpdfHtml .= $invData->item_hsncode;
									$mpdfHtml .= '</td>';

									$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
										$mpdfHtml .= $invData->item_quantity;
									$mpdfHtml .= '</td>';

									$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
										$mpdfHtml .= $invData->item_unit;
									$mpdfHtml .= '</td>';

									$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
										$mpdfHtml .= $invData->item_unit_price;
									$mpdfHtml .= '</td>';

									$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
										$mpdfHtml .= $invData->subtotal;
									$mpdfHtml .= '</td>';

									$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
										$mpdfHtml .= $invData->discount;
									$mpdfHtml .= '</td>';

									if($invoiceData[0]->advance_adjustment == 1) {
										$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
											$mpdfHtml .= $invData->advance_amount;
										$mpdfHtml .= '</td>';
									}

									$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
										$mpdfHtml .= $invData->taxable_subtotal;
									$mpdfHtml .= '</td>';
									
									$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
										$mpdfHtml .= $invData->cgst_rate;
									$mpdfHtml .= '</td>';

									$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
										$mpdfHtml .= $invData->cgst_amount;
									$mpdfHtml .= '</td>';

									$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
										$mpdfHtml .= $invData->sgst_rate;
									$mpdfHtml .= '</td>';

									$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
										$mpdfHtml .= $invData->sgst_amount;
									$mpdfHtml .= '</td>';

									$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
										$mpdfHtml .= $invData->igst_rate;
									$mpdfHtml .= '</td>';
									
									$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
										$mpdfHtml .= $invData->igst_amount;
									$mpdfHtml .= '</td>';
									
									$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
										$mpdfHtml .= $invData->cess_rate;
									$mpdfHtml .= '</td>';
									
									$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
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

							$mpdfHtml .= '<tr>';
								if($invoiceData[0]->advance_adjustment == 1) {
									$mpdfHtml .= '<td colspan="18" style="padding:5px;vertical-align:top;text-align:right;border-top:2px solid #eee;font-weight:bold;">';
								} else {
									$mpdfHtml .= '<td colspan="17" style="padding:5px;vertical-align:top;text-align:right;border-top:2px solid #eee;font-weight:bold;">';
								}
								   $mpdfHtml .= 'Total Invoice Value (In Figure): ' . $invoiceData[0]->invoice_total_value;
								$mpdfHtml .= '</td>';
							$mpdfHtml .= '</tr>';

							$invoice_total_value_words = $this->convert_number_to_words($invoiceData[0]->invoice_total_value);

							$mpdfHtml .= '<tr>';
								if($invoiceData[0]->advance_adjustment == 1) {
									$mpdfHtml .= '<td colspan="18" style="padding:5px;vertical-align:top;text-align:right;border-top:2px solid #eee;font-weight:bold;">';
								} else {
									$mpdfHtml .= '<td colspan="17" style="padding:5px;vertical-align:top;text-align:right;border-top:2px solid #eee;font-weight:bold;">';
								}
								   $mpdfHtml .= 'Total Invoice Value (In Words): ' . ucwords($invoice_total_value_words);
								$mpdfHtml .= '</td>';
							$mpdfHtml .= '</tr>';

							if($invoiceData[0]->supply_type === "Reverse Charge") {

								if($invoiceData[0]->supplier_billing_state === $invoiceData[0]->supply_place) {

									$mpdfHtml .= '<tr style="background:#e9ffdb;font-size:14px;">';
										
										if($invoiceData[0]->advance_adjustment == 1) {
											$mpdfHtml .= '<td colspan="10" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Value of supply subject to reverse charge</td>';
										} else {
											$mpdfHtml .= '<td colspan="9" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Value of supply subject to reverse charge</td>';
										}

										$mpdfHtml .= '<td>-</td>';
										$mpdfHtml .= '<td>₹'. $total_cgst_amount .'</td>';
										$mpdfHtml .= '<td>-</td>';
										$mpdfHtml .= '<td>₹'.$total_sgst_amount .'</td>';
										$mpdfHtml .= '<td>-</td>';
										$mpdfHtml .= '<td>₹0.00</td>';
										$mpdfHtml .= '<td>-</td>';
										$mpdfHtml .= '<td>₹'. $total_cess_amount .'</td>';
									$mpdfHtml .= '</tr>';
								} else {
								
									$mpdfHtml .= '<tr style="background:#e9ffdb;font-size:14px;">';

										if($invoiceData[0]->advance_adjustment == 1) {
											$mpdfHtml .= '<td colspan="10" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Amount of Tax Subject to Reverse Charge</td>';
										} else {
											$mpdfHtml .= '<td colspan="9" align="right" style="font-size:14px;padding:5px;vertical-align:top;font-family:opensans_bold;font-weight:normal;">Amount of Tax Subject to Reverse Charge</td>';
										}

										$mpdfHtml .= '<td>-</td>';
										$mpdfHtml .= '<td>₹0.00</td>';
										$mpdfHtml .= '<td>-</td>';
										$mpdfHtml .= '<td>₹0.00</td>';
										$mpdfHtml .= '<td>-</td>';
										$mpdfHtml .= '<td>₹'. $total_igst_amount .'</td>';
										$mpdfHtml .= '<td>-</td>';
										$mpdfHtml .= '<td>₹'. $total_cess_amount .'</td>';
									$mpdfHtml .= '</tr>';
								}
							}

						$mpdfHtml .= '</table>';

					$mpdfHtml .= '</td>';

				$mpdfHtml .= '</tr>';
				
				if(!empty($invoiceData[0]->description)) {
					$mpdfHtml .= '<tr class="description">';
						$mpdfHtml .= '<td colspan="2">';
							$mpdfHtml .= '<p><b>Description:</b> '. $invoiceData[0]->description .'</p>';
						$mpdfHtml .= '</td>';
					$mpdfHtml .= '</tr>';
				}

			$mpdfHtml .= '</table>';
		$mpdfHtml .= '</div>';
		
		return $mpdfHtml;
	}
}