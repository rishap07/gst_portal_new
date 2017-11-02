<?php
class json extends validation {

	public function __construct() {
		parent::__construct();
	}

	public function getGSTR2ADownlodedMissingData($userid, $returnMonth, $array_type = true) {

		/* Missing Invoices Query */
		$missingQuery = 'Select 
				pi.purchase_invoice_id, 
				pi.invoice_type, 
				pi.serial_number, 
				pi.reference_number, 
				pi.supply_type, 
				pi.import_supply_meant, 
				pi.invoice_date, 
				pi.reason_issuing_document, 
				pi.invoice_corresponding_type, 
				( 
                    CASE 
                        WHEN pi.corresponding_document_number = "0" THEN pi.corresponding_document_number  
                        ELSE (SELECT reference_number FROM ' . $this->tableNames['client_purchase_invoice'] . ' WHERE purchase_invoice_id = pi.corresponding_document_number) 
                    END 
                ) AS nt_num, 
                pi.corresponding_document_date as nt_dt, 
				(
					CASE 
						WHEN pi.is_tax_payable = "1" THEN "Y" 
						ELSE "N" 
					END
				) AS reverse_charge, 
				s.state_tin as pos, 
				pi.advance_adjustment, 
				( 
                    CASE 
                        WHEN pi.receipt_voucher_number = "0" THEN pi.receipt_voucher_number 
						ELSE (SELECT reference_number FROM ' . $this->tableNames['client_purchase_invoice'] . ' WHERE purchase_invoice_id = pi.receipt_voucher_number) 
                    END 
                ) AS receipt_voucher_number, 
				pi.supplier_billing_gstin_number as company_gstin_number, pi.import_bill_number, 
				pi.import_bill_date, 
				pi.import_bill_port_code, 
				pi.invoice_total_value, 
				sum(pii.taxable_subtotal) as total_taxable_subtotal, 
                sum(pii.cgst_amount) as total_cgst_amount, 
                sum(pii.sgst_amount) as total_sgst_amount, 
                sum(pii.igst_amount) as total_igst_amount, 
                sum(pii.cess_amount) as total_cess_amount, 
				sum(pii.total) as rate_amount_total, 
				CONVERT(pii.consolidate_rate USING utf8) as rate, 
				CONCAT(pi.reference_number,pi.supplier_billing_gstin_number) as ref_ctin, 
                DATE_FORMAT(pi.invoice_date,"%Y-%m") as financial_month 
				from ' . $this->tableNames['client_purchase_invoice'] . ' as pi  
                INNER JOIN ' . $this->tableNames['client_purchase_invoice_item'] . ' as pii 
				ON pi.purchase_invoice_id = pii.purchase_invoice_id 
				LEFT JOIN ' . $this->tableNames['state'] . ' s ON s.state_id = pi.supply_place 
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
				GROUP BY pi.serial_number, pii.consolidate_rate';
		return $this->get_results($missingQuery, $array_type);
	}

	public function getGSTR2ADownlodedAdditionalData($userid, $returnMonth, $array_type = true) {
		
		/* Additional Invoices Query */
		$additionalQuery = 'Select 
				di.id, 
				di.reference_number, 
                di.invoice_date, 
                di.invoice_total_value, 
                sum(di.total_taxable_subtotal) as total_taxable_subtotal, 
                di.company_gstin_number, 
				CONCAT(di.reference_number,di.company_gstin_number) as ref_ctin, 
                sum(di.total_cgst_amount) as total_cgst_amount, 
                sum(di.total_sgst_amount) as total_sgst_amount, 
                sum(di.total_igst_amount) as total_igst_amount, 
                sum(di.total_cess_amount) as total_cess_amount, 
                di.nt_num, 
                di.nt_dt, 
				di.p_gst, 
				CONVERT(di.rate USING utf8) as rate, 
                di.pos, 
				di.inv_typ, 
				di.ntty, 
				(
					CASE 
						WHEN di.rchrg = "Y" THEN "Y" 
						ELSE "N" 
					END
				) AS reverse_charge, 
				di.rsn, 
				di.chksum, 
				di.financial_month 
				from ' . $this->tableNames['client_reconcile_purchase_invoice1'] . ' as di 
				where 1=1 AND di.added_by = ' . $userid . ' 
				and DATE_FORMAT(di.invoice_date,"%Y-%m") = "' . $returnMonth . '"  
                and di.status = "1"  
                and CONCAT(di.reference_number,di.company_gstin_number)  
                NOT IN(Select CONCAT(reference_number,supplier_billing_gstin_number) 
                from ' . $this->tableNames['client_purchase_invoice'] . ' WHERE status = "1" and DATE_FORMAT(invoice_date,"%Y-%m") = "' . $returnMonth . '" 
                AND added_by = ' . $userid . ') 
				GROUP BY CONCAT(di.reference_number,di.company_gstin_number), di.rate';
		return $this->get_results($additionalQuery, $array_type);
	}

	public function getGSTR2ADownlodedMatchMisData($userid, $returnMonth, $array_type = true) {

		$query = 'Select 
				di.id, 
                pi.purchase_invoice_id, 
                di.type, 
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

	public function getGst2ReconcileFinalQuery($financialMonth, $invoice_status = 'pending', $field = "*", $condition = '', $group_by = '') {

		$reconcile_query = 'Select '.$field.' from ' . $this->tableNames['gstr2_reconcile_final'] . ' where 1=1 AND added_by = ' . $_SESSION['user_detail']['user_id'] . ' AND financial_month = "' . $financialMonth . '" AND invoice_status = "' . $invoice_status . '"';
		if($condition != '') {
			$reconcile_query .= " AND " . $condition;
		}

		return $this->get_results($reconcile_query, false);
	}
}
?>