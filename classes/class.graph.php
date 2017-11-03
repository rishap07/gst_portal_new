<?php
/*
 * 
 *  Developed By        :   Rishap Gandhi
 *  Description         :   A simple class for common functions to be used throughout the project 
 *  Date Created        :   May 18, 2017
 *  Last Modified       :   May 18, 2017
 *  Last Modified By    :   Rishap Gandhi
 *  Last Modification   :   file creation started
 * 
*/

class graph extends validation {
    /* FUNCTION TO PRINT AN ARRAY AND DIE */

    
    public function __construct() {
        parent::__construct();
    }

	//get total purchase and sales for graph
    public function purchaseSalesGraph($financial_month, $financialYearDD, $userId)
	{
		
		$arrayData=array();
		$cashData=array();
		//total sales in amount
			$saleQuery='SELECT sum(invoice_total_value) as total_sales FROM '.$this->tableNames['client_invoice'].'
			where DATE_FORMAT(invoice_date,"%Y-%m") = "'.$financial_month.'" 
			and financial_year  = "'.$financialYearDD.'" 
			and added_by="'.$userId.'" 
			and is_deleted="0" 
			and is_canceled="0"';
			
			//total purchase in amount
			$purchaseQuery = 'SELECT sum(invoice_total_value) as total_purchase FROM '.$this->tableNames['client_purchase_invoice'].'
			where DATE_FORMAT(invoice_date,"%Y-%m") = "'.$financial_month.'" 
			and financial_year  = "'.$financialYearDD.'" 
			and added_by="'.$userId.'"
			and is_deleted="0" 
			and is_canceled="0"';

			$totalSales = $this->get_row($saleQuery,false);
			$totalPurchase = $this->get_row($purchaseQuery,false);
			
			if(empty($totalSales)) {
				$cashData['sales'] = "0.00";
			} else {
				$cashData['sales'] = $totalSales[0];
			}
			
			if(empty($totalPurchase)) {
				$cashData['purchase'] = "0.00";
			} else {
				$cashData['purchase'] = $totalPurchase[0];
				$cashData['month'] = $financial_month;
			}
			array_push($arrayData, $cashData);
			return $arrayData;
	}
	
	//get total purchase and sales for graph
    public function purchaseSalesInvoiceGraph($financial_month, $financialYearDD, $userId)
	{
		$invoiceArray=array();
		$invoiceData=array();
		
		$saleInvoice='SELECT count(reference_number) as sales_invoice 
		FROM '.$this->tableNames['client_invoice'].'
		where DATE_FORMAT(invoice_date,"%Y-%m") = "'.$financial_month.'" 
		and financial_year  = "'.$financialYearDD.'" 
		and added_by="'.$userId.'"
		and is_deleted="0" 
		and is_canceled="0"';
		
		//total purchase invoice
		$purchaseInvoice = 'SELECT count(reference_number) as purchase_invoice 
		FROM '.$this->tableNames['client_purchase_invoice'].'
		where DATE_FORMAT(invoice_date,"%Y-%m") = "'.$financial_month.'" 
		and  financial_year  = "'.$financialYearDD.'" 
		and added_by="'.$userId.'"
		and is_deleted="0" 
		and is_canceled="0"';

		$totalSalesInvoice = $this->get_row($saleInvoice,false);
		$totalPurchaseInvoice = $this->get_row($purchaseInvoice,false);
		
		if(empty($totalSalesInvoice)) {
			$invoiceData['sales'] = "0";
		} else {
			$invoiceData['sales'] = $totalSalesInvoice[0];
		}
		
		if(empty($totalPurchaseInvoice)) {
			$invoiceData['purchase'] = "0.00";
		} else {
			$invoiceData['purchase'] = $totalPurchaseInvoice[0];
			$invoiceData['month'] = $financial_month;
		}
		array_push($invoiceArray, $invoiceData);
		return $invoiceArray;
	}
	
	//get total cancel purchase and cancel  sales for graph
    public function purchaseSalesCancelInvoiceGraph($financial_month, $financialYearDD, $userId)
	{
		$cancelInvoiceArray=array();
		$cancelInvoiceData=array();
		
		$cancelSaleInvoice='SELECT count(reference_number) as sales_invoice FROM '.$this->tableNames['client_invoice'].'
		where DATE_FORMAT(invoice_date,"%Y-%m") = "'.$financial_month.'" 
		and financial_year  = "'.$financialYearDD.'" 
		and added_by="'.$userId.'"
		and is_deleted="0" 
		and is_canceled="1"';
		
		//total purchase invoice
		$cancelPurchaseInvoice = 'SELECT count(reference_number) as purchase_invoice 
		FROM '.$this->tableNames['client_purchase_invoice'].'
		where DATE_FORMAT(invoice_date,"%Y-%m") = "'.$financial_month.'" 
		and  financial_year  = "'.$financialYearDD.'" 
		and added_by="'.$userId.'"
		and is_deleted="0" 
		and is_canceled="1"';

		$totalCancelSalesInvoice = $this->get_row($cancelSaleInvoice,false);
		$totalCancelPurchaseInvoice = $this->get_row($cancelPurchaseInvoice,false);
		
		if(empty($totalCancelSalesInvoice)) {
			$cancelInvoiceData['sales'] = "0";
		} else {
			$cancelInvoiceData['sales'] = $totalCancelSalesInvoice[0];
		}
		
		if(empty($totalCancelPurchaseInvoice)) {
			$invoiceData['purchase'] = "0.00";
		} else {
			$cancelInvoiceData['purchase'] = $totalCancelPurchaseInvoice[0];
			$cancelInvoiceData['month'] = $financial_month;
		}
		array_push($cancelInvoiceArray, $cancelInvoiceData);
		return $cancelInvoiceArray;
	}

    

}
?>