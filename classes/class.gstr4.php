<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

final class gstr4 extends validation {

    function __construct() {
        parent::__construct();
    }
	

   
    
   public function startGstr4() {
        $sql = "select * from " . TAB_PREFIX . "return where client_id='" . $_SESSION["user_detail"]["user_id"]. "' and return_month='" . $_GET["returnmonth"] . "' and type='gstr4'";

        $clientdata = $this->get_results($sql);

        if (empty($clientdata)) {

            $dataArr['return_month'] = $this->sanitize($_GET['returnmonth']);
            $dataArr['type'] = 'gstr4';
            $dataArr['client_id'] = $_SESSION["user_detail"]["user_id"];
            $year = $this->generateFinancialYear();
            $dataArr['financial_year'] = $year;
            $dataArr['status'] = 1;

            if ($this->insert(TAB_PREFIX . 'return', $dataArr)) {
                //$this->setSuccess('GSTR2 Saved Successfully');
                $this->logMsg("User ID : " . $_SESSION["user_detail"]["user_id"] . "Initiated the GSTR4 Filling", "gstr4");

                return true;
            } else {
                $this->setError('Failed to save GSTR4 data');
                return false;
            }
        }
    }

	public function getPurchaseB2BInvoices($user_id, $returnmonth) {
		$queryB2B="select COUNT(DISTINCT i.purchase_invoice_id) as totalinvoice,sum(i.invoice_total_value) as totalamount,sum(item.cgst_amount) as cgst,sum(item.igst_amount) as igst,sum(item.sgst_amount) as sgst,sum(item.cess_amount) as cess from " . $this->getTableName('client_purchase_invoice') . " as i INNER join " . $this->getTableName('client_purchase_invoice_item') . " as item on item.purchase_invoice_id=i.purchase_invoice_id where i.invoice_nature='purchaseinvoice' and i.added_by='".$user_id . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and i.supplier_billing_gstin_number!='' and (i.invoice_type='taxinvoice' or i.invoice_type='sezunitinvoice' or i.invoice_type='importinvoice') and i.invoice_nature='purchaseinvoice' AND i.supply_type='reversecharge' and i.is_tax_payable='1' and i.invoice_date like '%".$returnmonth."%'"; 
	
        return $this->get_results($queryB2B);
    }
	public function getPurchaseB2BInvoicesDetails($user_id, $returnmonth) {
	  $queryB2B="select i.purchase_invoice_id as invoice_id,item.taxable_subtotal, COUNT(DISTINCT i.purchase_invoice_id) as totalinvoice,i.supplier_billing_gstin_number as supplier_gstn, i.serial_number as invoice_number,DATE_FORMAT(i.invoice_date, '%d-%m-%Y') as invoice_date, i.invoice_total_value as totalamount,i.supply_place,state.state_name, i.supply_type,item.consolidate_rate ,sum(item.cgst_amount) as cgst,sum(item.igst_amount) as igst,sum(item.sgst_amount) as sgst,sum(item.cess_amount) as cess from " . $this->getTableName('client_purchase_invoice') . " as i INNER join " . $this->getTableName('client_purchase_invoice_item') . " as item on item.purchase_invoice_id=i.purchase_invoice_id
inner join " . $this->getTableName('state') . " as state on state.state_id = i.supply_place
where i.invoice_nature='purchaseinvoice' and i.added_by='".$user_id."' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and i.supplier_billing_gstin_number!='' and (i.invoice_type='taxinvoice' or i.invoice_type='sezunitinvoice' or i.invoice_type='importinvoice') and i.invoice_nature='purchaseinvoice' AND i.supply_type='reversecharge' and i.is_tax_payable='1' and i.invoice_date like '%".$returnmonth."%'  group by i.purchase_invoice_id";
        return $this->get_results($queryB2B);
    }
	public function getPurchaseATInvoices($userid,$returnmonth)
	{
		$queryat="select COUNT(DISTINCT i.purchase_invoice_id) as totalinvoice,sum(i.invoice_total_value) as totalamount,sum(item.cgst_amount) as cgst,sum(item.igst_amount) as igst,sum(item.sgst_amount) as sgst,sum(item.cess_amount) as cess from " . $this->getTableName('client_purchase_invoice') . " as i INNER join " . $this->getTableName('client_purchase_invoice_item') . " as item on item.purchase_invoice_id=i.purchase_invoice_id where i.invoice_nature='purchaseinvoice' and i.added_by='".$userid."' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and i.invoice_type='receiptvoucherinvoice' and i.invoice_nature='purchaseinvoice' AND i.supply_type='reversecharge' and i.invoice_date like '%".$returnmonth."%' ";
		return $this->get_results($queryat);
	}
	public function getPurchaseATInvoicesDetails($user_id, $returnmonth) {
	  $queryB2B="select i.company_state, i.purchase_invoice_id as invoice_id,item.taxable_subtotal, COUNT(DISTINCT i.purchase_invoice_id) as totalinvoice,i.supplier_billing_gstin_number as supplier_gstn, i.serial_number as invoice_number,DATE_FORMAT(i.invoice_date, '%d-%m-%Y') as invoice_date, i.invoice_total_value as totalamount,i.supply_place,state.state_name, i.supply_type,item.consolidate_rate ,sum(item.cgst_amount) as cgst,sum(item.igst_amount) as igst,sum(item.sgst_amount) as sgst,sum(item.cess_amount) as cess from " . $this->getTableName('client_purchase_invoice') . " as i INNER join " . $this->getTableName('client_purchase_invoice_item') . " as item on item.purchase_invoice_id=i.purchase_invoice_id
inner join " . $this->getTableName('state') . " as state on state.state_id = i.supply_place
where i.invoice_nature='purchaseinvoice' and i.added_by='".$user_id."' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and i.invoice_type='receiptvoucherinvoice' and i.invoice_nature='purchaseinvoice' AND i.supply_type='reversecharge' and i.invoice_date like '%".$returnmonth."%'  group by i.purchase_invoice_id";
        return $this->get_results($queryB2B);
    }
	
	public function getPurchaseB2BurInvoices($user_id, $returnmonth) {
		$queryB2Bur="select COUNT(DISTINCT i.purchase_invoice_id) as totalinvoice,sum(i.invoice_total_value) as totalamount,sum(item.cgst_amount) as cgst,sum(item.igst_amount) as igst,sum(item.sgst_amount) as sgst,sum(item.cess_amount) as cess from " . $this->getTableName('client_purchase_invoice') . " as i INNER join " . $this->getTableName('client_purchase_invoice_item') . " as item on item.purchase_invoice_id=i.purchase_invoice_id where i.invoice_nature='purchaseinvoice' and i.added_by='".$user_id . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and i.supplier_billing_gstin_number='' and (i.invoice_type='taxinvoice' or i.invoice_type='sezunitinvoice' or i.invoice_type='importinvoice') and i.invoice_nature='purchaseinvoice' AND i.invoice_date like '%".$returnmonth."%'"; 
        return $this->get_results($queryB2Bur);
    }
	public function getPurchaseB2BurInvoicesDetails($user_id, $returnmonth) {
	 $queryB2Bur="select i.purchase_invoice_id as invoice_id,item.taxable_subtotal, COUNT(DISTINCT i.purchase_invoice_id) as totalinvoice,i.supplier_billing_gstin_number as supplier_gstn, i.serial_number as invoice_number,DATE_FORMAT(i.invoice_date, '%d-%m-%Y') as invoice_date, i.invoice_total_value as totalamount,i.supply_place,state.state_name, i.supply_type,item.consolidate_rate ,sum(item.cgst_amount) as cgst,sum(item.igst_amount) as igst,sum(item.sgst_amount) as sgst,sum(item.cess_amount) as cess from " . $this->getTableName('client_purchase_invoice') . " as i INNER join " . $this->getTableName('client_purchase_invoice_item') . " as item on item.purchase_invoice_id=i.purchase_invoice_id
inner join " . $this->getTableName('state') . " as state on state.state_id = i.supply_place
where i.invoice_nature='purchaseinvoice' and i.added_by='".$user_id."' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and i.supplier_billing_gstin_number='' and (i.invoice_type='taxinvoice' or i.invoice_type='sezunitinvoice' or i.invoice_type='importinvoice') and i.invoice_nature='purchaseinvoice' AND i.supply_type='reversecharge' and i.is_tax_payable='1' and i.invoice_date like '%".$returnmonth."%'  group by i.purchase_invoice_id";
      return $this->get_results($queryB2Bur);
    }
	public function getPurchaseImportInvoices($user_id, $returnmonth) {
		$queryimps="select COUNT(DISTINCT i.purchase_invoice_id) as totalinvoice,sum(i.invoice_total_value) as totalamount,sum(item.cgst_amount) as cgst,sum(item.igst_amount) as igst,sum(item.sgst_amount) as sgst,sum(item.cess_amount) as cess from " . $this->getTableName('client_purchase_invoice') . " as i INNER join " . $this->getTableName('client_purchase_invoice_item') . " as item on item.purchase_invoice_id=i.purchase_invoice_id where i.invoice_nature='purchaseinvoice' and i.added_by='".$user_id . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' AND (i.invoice_type='importinvoice' OR i.invoice_type='deemedimportinvoice' OR i.invoice_type='sezunitinvoice') and i.invoice_nature='purchaseinvoice' AND i.invoice_date like '%".$returnmonth."%'"; 
        return $this->get_results($queryimps);
    }
	public function getPurchaseImportInvoicesDetails($user_id, $returnmonth) {
	 $queryB2Bur="select i.purchase_invoice_id as invoice_id,item.taxable_subtotal, COUNT(DISTINCT i.purchase_invoice_id) as totalinvoice,i.supplier_billing_gstin_number as supplier_gstn, i.serial_number as invoice_number,DATE_FORMAT(i.invoice_date, '%d-%m-%Y') as invoice_date, i.invoice_total_value as totalamount,i.supply_place,state.state_name, i.supply_type,item.consolidate_rate ,sum(item.cgst_amount) as cgst,sum(item.igst_amount) as igst,sum(item.sgst_amount) as sgst,sum(item.cess_amount) as cess from " . $this->getTableName('client_purchase_invoice') . " as i INNER join " . $this->getTableName('client_purchase_invoice_item') . " as item on item.purchase_invoice_id=i.purchase_invoice_id
inner join " . $this->getTableName('state') . " as state on state.state_id = i.supply_place
where i.invoice_nature='purchaseinvoice' and i.added_by='".$user_id."' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' AND (i.invoice_type='importinvoice' OR i.invoice_type='deemedimportinvoice' OR i.invoice_type='sezunitinvoice') AND i.invoice_nature='purchaseinvoice' AND i.invoice_date like '%".$returnmonth."%'  group by i.purchase_invoice_id";
      return $this->get_results($queryB2Bur);
    }
	public function getPurchaseCdnrInvoices($user_id, $returnmonth) {
		$querycdnr="select COUNT(DISTINCT i.purchase_invoice_id) as totalinvoice,sum(i.invoice_total_value) as totalamount,sum(item.cgst_amount) as cgst,sum(item.igst_amount) as igst,sum(item.sgst_amount) as sgst,sum(item.cess_amount) as cess from " . $this->getTableName('client_purchase_invoice') . " as i INNER join " . $this->getTableName('client_purchase_invoice_item') . " as item on item.purchase_invoice_id=i.purchase_invoice_id where i.invoice_nature='purchaseinvoice' and i.added_by='".$user_id . "' AND i.supplier_billing_gstin_number!='' and (i.invoice_type='debitnote' or i.invoice_type='creditnote') AND i.invoice_date like '%" . $returnmonth . "%'";
        return $this->get_results($querycdnr);
    }
	public function getPurchaseCdnrInvoicesDetails($user_id, $returnmonth) {
	  $queryB2Bur="select i.recipient_shipping_state, I.reference_number,i.invoice_date as refund_voucher_date,i.corresponding_document_number as payment_voucher_number,i.corresponding_document_date as payment_voucher_date,I.invoice_type,I.reason_issuing_document,I.supply_type,i.supplier_billing_state, i.purchase_invoice_id as invoice_id,item.taxable_subtotal, COUNT(DISTINCT i.purchase_invoice_id) as totalinvoice,i.supplier_billing_gstin_number as supplier_gstn, i.serial_number as invoice_number,DATE_FORMAT(i.invoice_date, '%d-%m-%Y') as invoice_date, i.invoice_total_value as totalamount,i.supply_place,state.state_name, i.supply_type,item.consolidate_rate ,sum(item.cgst_amount) as cgst,sum(item.igst_amount) as igst,sum(item.sgst_amount) as sgst,sum(item.cess_amount) as cess from " . $this->getTableName('client_purchase_invoice') . " as i INNER join " . $this->getTableName('client_purchase_invoice_item') . " as item on item.purchase_invoice_id=i.purchase_invoice_id inner join " . $this->getTableName('state') . " as state on state.state_id = i.supply_place where i.invoice_nature='purchaseinvoice' and i.added_by='".$_SESSION["user_detail"]["user_id"]."' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' AND i.supplier_billing_gstin_number!='' and (i.invoice_type='debitnote' or i.invoice_type='creditnote') AND i.invoice_nature='purchaseinvoice' AND i.invoice_date like '%".$returnmonth."%' group by i.purchase_invoice_id";
      return $this->get_results($queryB2Bur);
    }
	public function getPurchaseCdnurInvoices($user_id, $returnmonth) {
		$querycdnr="select COUNT(DISTINCT i.purchase_invoice_id) as totalinvoice,sum(i.invoice_total_value) as totalamount,sum(item.cgst_amount) as cgst,sum(item.igst_amount) as igst,sum(item.sgst_amount) as sgst,sum(item.cess_amount) as cess from " . $this->getTableName('client_purchase_invoice') . " as i INNER join " . $this->getTableName('client_purchase_invoice_item') . " as item on item.purchase_invoice_id=i.purchase_invoice_id where i.invoice_nature='purchaseinvoice' and i.added_by='".$user_id . "' AND i.supplier_billing_gstin_number='' and (i.invoice_type='debitnote' or i.invoice_type='creditnote') AND i.invoice_date like '%" . $returnmonth . "%'";
        return $this->get_results($querycdnr);
    }
	public function getPurchaseCdnurInvoicesDetails($user_id, $returnmonth) {
	  $queryB2Bur="select i.recipient_shipping_state, I.reference_number,i.invoice_date as refund_voucher_date,i.corresponding_document_number as payment_voucher_number,i.corresponding_document_date as payment_voucher_date,I.invoice_type,I.reason_issuing_document,I.supply_type,i.supplier_billing_state, i.purchase_invoice_id as invoice_id,item.taxable_subtotal, COUNT(DISTINCT i.purchase_invoice_id) as totalinvoice,i.supplier_billing_gstin_number as supplier_gstn, i.serial_number as invoice_number,DATE_FORMAT(i.invoice_date, '%d-%m-%Y') as invoice_date, i.invoice_total_value as totalamount,i.supply_place,state.state_name, i.supply_type,item.consolidate_rate ,sum(item.cgst_amount) as cgst,sum(item.igst_amount) as igst,sum(item.sgst_amount) as sgst,sum(item.cess_amount) as cess from " . $this->getTableName('client_purchase_invoice') . " as i INNER join " . $this->getTableName('client_purchase_invoice_item') . " as item on item.purchase_invoice_id=i.purchase_invoice_id inner join " . $this->getTableName('state') . " as state on state.state_id = i.supply_place where i.invoice_nature='purchaseinvoice' and i.added_by='".$_SESSION["user_detail"]["user_id"]."' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' AND i.supplier_billing_gstin_number='' and (i.invoice_type='debitnote' or i.invoice_type='creditnote') AND i.invoice_nature='purchaseinvoice' AND i.invoice_date like '%".$returnmonth."%' group by i.purchase_invoice_id";
      return $this->get_results($queryB2Bur);
    }
	public function getPurchaseAtadjInvoices($user_id, $returnmonth) {
		$queryatadj="select count(DISTINCT p.purchase_invoice_id) as totalinvoice,sum(p.invoice_total_value) as totalamount, p.purchase_invoice_id as invoice_id, p.invoice_type, p.supplier_billing_name, p.financial_year, p.invoice_date, p.reference_number, p.supplier_billing_gstin_number, cs.state_tin as company_state, ps.state_tin as supply_place, sum(pi.taxable_subtotal) as taxable_subtotal, sum(pi.cgst_amount) as cgst, sum(pi.sgst_amount) as sgst, sum(pi.igst_amount) as igst, sum(pi.cess_amount) as cess, pi.consolidate_rate from " . $this->getTableName('client_purchase_invoice') . " p left join " . $this->getTableName('client_purchase_invoice') . " as inv on p.purchase_invoice_id = inv.receipt_voucher_number AND (inv.invoice_date > p.invoice_date AND (DATE_FORMAT(inv.invoice_date, '%Y-%m') = '".$returnmonth."' ) )inner join " . $this->getTableName('client_purchase_invoice_item') . " pi on p.purchase_invoice_id = pi.purchase_invoice_id inner join " . $this->getTableName('state') . " cs on cs.state_id = p.company_state inner join " . $this->getTableName('state') . " ps on p.supply_place = ps.state_id where 1=1 AND p.status='1' AND p.added_by='".$user_id."' AND DATE_FORMAT(p.invoice_date,'%Y-%m') = '".$returnmonth."' AND p.invoice_type='receiptvoucherinvoice' AND p.is_canceled='0' AND p.is_deleted='0'";
        return $this->get_results($queryatadj);
    }
	public function getPurchaseAtadjInvoicesDetails($user_id, $returnmonth) {
		$queryatadj="select P.company_state,ps.state_name,count(DISTINCT p.purchase_invoice_id) as totalinvoice,sum(p.invoice_total_value) as totalamount, p.purchase_invoice_id as invoice_id, p.invoice_type, p.supplier_billing_name, p.financial_year, p.invoice_date, p.reference_number, p.supplier_billing_gstin_number, cs.state_tin as company_state, ps.state_tin as supply_place, sum(pi.taxable_subtotal) as taxable_subtotal, sum(pi.cgst_amount) as cgst, sum(pi.sgst_amount) as sgst, sum(pi.igst_amount) as igst, sum(pi.cess_amount) as cess, pi.consolidate_rate from " . $this->getTableName('client_purchase_invoice') . " p left join " . $this->getTableName('client_purchase_invoice') . " as inv on p.purchase_invoice_id = inv.receipt_voucher_number AND (inv.invoice_date > p.invoice_date AND (DATE_FORMAT(inv.invoice_date, '%Y-%m') = '".$returnmonth."' ) )inner join " . $this->getTableName('client_purchase_invoice_item') . " pi on p.purchase_invoice_id = pi.purchase_invoice_id inner join " . $this->getTableName('state') . " cs on cs.state_id = p.company_state inner join " . $this->getTableName('state') . " ps on p.supply_place = ps.state_id where 1=1 AND p.status='1' AND p.added_by='".$user_id."' AND DATE_FORMAT(p.invoice_date,'%Y-%m') = '".$returnmonth."' AND p.invoice_type='receiptvoucherinvoice' AND p.is_canceled='0' AND p.is_deleted='0' GROUP by p.purchase_invoice_id,p.supply_place";
        return $this->get_results($queryatadj);
    }
	public function getTotalSale($user_id, $returnmonth) {
		$querysales="select count(invoice_id) as monthcount, sum(invoice_total_value) as totalsale,month(invoice_date) as month from " . $this->getTableName('client_invoice') . " WHERE invoice_nature='salesinvoice' and (invoice_type <> 'deliverychallaninvoice' and invoice_type<>'creditnote' and invoice_type<>'refundvoucherinvoice') and added_by='".$user_id . "' and is_canceled='0' and is_deleted='0' and invoice_date like '%".$returnmonth."%'";
        return $this->get_results($querysales);
    }
	public function getGstr4B2BInvoices($user_id,$returnmonth)
	{
	 $query="select i.purchase_invoice_id as invoice_id,item.taxable_subtotal, COUNT(DISTINCT i.purchase_invoice_id) as totalinvoice,i.supplier_billing_gstin_number as supplier_gstn, i.serial_number as inum,DATE_FORMAT(i.invoice_date, '%d-%m-%Y') as idt, i.invoice_total_value as val,i.supply_place as pos,state.state_name, i.supply_type,item.consolidate_rate as rate ,item.cgst_amount as camt,item.igst_amount as iamt,item.sgst_amount as samt,item.cess_amount as csamt from gst_client_purchase_invoice as i INNER join gst_client_purchase_invoice_item as item on item.purchase_invoice_id=i.purchase_invoice_id inner join gst_master_state as state on state.state_id = i.supply_place where i.invoice_nature='purchaseinvoice' and i.added_by='".$user_id."' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and i.supplier_billing_gstin_number!='' and (i.invoice_type='taxinvoice' or i.invoice_type='sezunitinvoice' or i.invoice_type='importinvoice') and i.invoice_nature='purchaseinvoice' AND i.supply_type='reversecharge' and i.is_tax_payable='1' and i.invoice_date like '%".$returnmonth."%' group by i.purchase_invoice_id";
	return $this->get_results($query);
	}
	public function getGstr4B2BItemDetails($user_id,$returnmonth)
	{
	echo $query="select i.purchase_invoice_id as invoice_id,item.taxable_subtotal, COUNT(DISTINCT i.purchase_invoice_id) as totalinvoice,i.supplier_billing_gstin_number as supplier_gstn, i.serial_number as inum,DATE_FORMAT(i.invoice_date, '%d-%m-%Y') as idt, i.invoice_total_value as val,i.supply_place as pos,state.state_name, i.supply_type,item.consolidate_rate as rate ,item.cgst_amount as camt,item.igst_amount as iamt,item.sgst_amount as samt,item.cess_amount as csamt from gst_client_purchase_invoice as i INNER join gst_client_purchase_invoice_item as item on item.purchase_invoice_id=i.purchase_invoice_id inner join gst_master_state as state on state.state_id = i.supply_place where i.invoice_nature='purchaseinvoice' and i.added_by='".$user_id."' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and i.supplier_billing_gstin_number!='' and (i.invoice_type='taxinvoice' or i.invoice_type='sezunitinvoice' or i.invoice_type='importinvoice') and i.invoice_nature='purchaseinvoice' AND i.supply_type='reversecharge' and i.is_tax_payable='1' and i.invoice_date like '%".$returnmonth."%' group by i.purchase_invoice_id";
	return $this->get_results($query);
	}
	public function getGstr4B2BurInvoices($user_id,$returnmonth)
	{
	$query="select i.company_state,i.purchase_invoice_id as invoice_id,item.taxable_subtotal, COUNT(DISTINCT i.purchase_invoice_id) as totalinvoice,i.supplier_billing_gstin_number as supplier_gstn, i.serial_number as inum,DATE_FORMAT(i.invoice_date, '%d-%m-%Y') as idt, i.invoice_total_value as val,i.supply_place as place_of_supply,state.state_name, i.supply_type,item.consolidate_rate as rate ,item.cgst_amount as camt,item.igst_amount as iamt,item.sgst_amount as samt,item.cess_amount as csamt from gst_client_purchase_invoice as i INNER join gst_client_purchase_invoice_item as item on item.purchase_invoice_id=i.purchase_invoice_id inner join gst_master_state as state on state.state_id = i.supply_place where i.invoice_nature='purchaseinvoice' and i.added_by='".$user_id."' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and i.supplier_billing_gstin_number='' and (i.invoice_type='taxinvoice' or i.invoice_type='sezunitinvoice' or i.invoice_type='importinvoice') and i.invoice_nature='purchaseinvoice' AND i.supply_type='reversecharge' and i.is_tax_payable='1' and i.invoice_date like '%".$returnmonth."%' group by i.purchase_invoice_id";
	return $this->get_results($query);
	}
	public function getGstr4ImpsInvoices($user_id,$returnmonth)
	{
	echo $query="select i.company_state,i.purchase_invoice_id as invoice_id,item.taxable_subtotal, COUNT(DISTINCT i.purchase_invoice_id) as totalinvoice,i.supplier_billing_gstin_number as supplier_gstn, i.serial_number as inum,DATE_FORMAT(i.invoice_date, '%d-%m-%Y') as idt, i.invoice_total_value as val,i.supply_place as place_of_supply,state.state_name, i.supply_type,item.consolidate_rate as rate ,item.cgst_amount as camt,item.igst_amount as iamt,item.sgst_amount as samt,item.cess_amount as csamt from gst_client_purchase_invoice as i INNER join gst_client_purchase_invoice_item as item on item.purchase_invoice_id=i.purchase_invoice_id inner join gst_master_state as state on state.state_id = i.supply_place where i.invoice_nature='purchaseinvoice' and i.added_by='".$user_id."' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' AND (i.invoice_type='importinvoice' OR i.invoice_type='deemedimportinvoice' OR i.invoice_type='sezunitinvoice') AND i.invoice_nature='purchaseinvoice' AND i.invoice_date like '%".$returnmonth."%'  group by i.purchase_invoice_id";
	return $this->get_results($query);
	}
	 public function gstr4Data($user_id, $returnmonth) {
        $dataArr = $this->gstr4PayloadHeader($user_id, $returnmonth);
		
        /***** Start Code For Payload ********** */
        $data = $this->GSTR4Payload($user_id, $returnmonth);
        if (!empty($data)) {
            $data_arr = $data['data_arr'];
            $dataArr = array_merge($dataArr, $data_arr);
        }
        /***** End Code For Payload ********** */

		$this->pr($dataArr);
		die;
        $response['data_arr'] = $dataArr;
        return $response;
    }
		public function gstr4PayloadHeader($user_id, $returnmonth) {
			$obj_gst = new gstr();
			$dataArr = array();
			$api_return_period = $obj_gst->getRetrunPeriodFormat($returnmonth);
			if(API_TYPE == 'Demo') {
			   $gstin = API_GSTIN;
			}
			else {
				$gstin = $obj_gst->gstin();
			}
			$dataArr["gstin"] = $gstin;
			$dataArr["ret_period"] = $api_return_period ;
			return $dataArr;
		}
		public function GSTR4Payload($user_id, $returnmonth) {
    	$dataArr = $data_ids = array();
		$dataInv = $this->getGstr4B2BInvoices($user_id,$returnmonth);
		if (isset($dataInv) && !empty($dataInv)) {
            $x = 0;
            $y=0;
			
			$u=0;
			$count=0;
            foreach ($dataInv as $dataIn) {
				$dataArr['b2b']['ctin']=$dataIn->supplier_gstn;
                $dataArr['b2b']['inv'][$u]['inum'] = $dataIn->inum;
                $dataArr['b2b']['inv'][$u]['idt'] = $dataIn->idt;
                $dataArr['b2b']['inv'][$u]['val'] = (float)$dataIn->val;
                $dataArr['b2b']['inv'][$u]['rchrg'] = 'Y';
                $dataArr['b2b']['inv'][$u]['inv_typ'] = 'R';
				$itemquery="select item.item_name,item.purchase_invoice_id, item.taxable_subtotal,item.consolidate_rate as rate ,item.cgst_amount as camt,item.igst_amount as iamt,item.sgst_amount as samt,item.cess_amount as csamt from gst_client_purchase_invoice_item as item   where item.purchase_invoice_id=".$dataIn->invoice_id."";
				$dataInvItem = $this->get_results($itemquery);
			
			 if (isset($dataInvItem) && !empty($dataInvItem)) {
				 
				$x=0;
				foreach ($dataInvItem as $dataInItem) { 
				$dataArr['b2b']['inv'][$u]['itms']['itm_det'][$x]['rt'] = (float)$dataInItem->rate;
				$dataArr['b2b']['inv'][$u]['itms']['itm_det'][$x]['txval'] = (float)$dataInItem->taxable_subtotal;
				$dataArr['b2b']['inv'][$u]['itms']['itm_det'][$x]['iamt'] = (float)$dataInItem->iamt;
				$dataArr['b2b']['inv'][$u]['itms']['itm_det'][$x]['camt'] = (float)$dataInItem->camt;
				$dataArr['b2b']['inv'][$u]['itms']['itm_det'][$x]['samt'] = (float)$dataInItem->samt;
				$dataArr['b2b']['inv'][$u]['itms']['itm_det'][$x]['csamt'] = (float)$dataInItem->csamt;
				$x++;
				$count++;
				}
				$dataArr['b2b']['itms']['num'] = $count;
			 }
			 $u++;
			
			}
				
		}
		/*
		$dataInv = $this->getGstr4B2BurInvoices($user_id,$returnmonth);
	
		
		if (isset($dataInv) && !empty($dataInv)) {
            $x = 0;
            $y=0;
			$u=0;
			$count=0;
            foreach ($dataInv as $dataIn) {
               
                $dataArr['b2bur']['inv'][$u]['inum'] = $dataIn->inum;
                $dataArr['b2bur']['inv'][$u]['idt'] = $dataIn->idt;
                $dataArr['b2bur']['inv'][$u]['val'] = (float)$dataIn->val;
                $dataArr['b2bur']['inv'][$u]['pos'] = $dataIn->place_of_supply;
				if($dataIn->place_of_supply==$dataIn->company_state)
				{
                $dataArr['b2bur']['inv']['sply_ty'] = 'INTER';
				}else{
					$dataArr['b2bur']['inv']['sply_ty'] = 'INTRA';
				}
				$itemquery="select item.item_name,item.purchase_invoice_id, item.taxable_subtotal,item.consolidate_rate as rate ,item.cgst_amount as camt,item.igst_amount as iamt,item.sgst_amount as samt,item.cess_amount as csamt from gst_client_purchase_invoice_item as item   where item.purchase_invoice_id=".$dataIn->invoice_id."";
				$dataInvItem = $this->get_results($itemquery);
				if (isset($dataInvItem) && !empty($dataInvItem)) {
				foreach ($dataInvItem as $dataInItem) { 
				$dataArr['b2bur']['itms']['itm_det']['rt'] = (float)$dataIn->rate;
				$dataArr['b2bur']['itms']['itm_det']['txval'] = (float)$dataIn->taxable_subtotal;
				$dataArr['b2bur']['itms']['itm_det']['iamt'] = (float)$dataIn->iamt;
				$dataArr['b2bur']['itms']['itm_det']['camt'] = (float)$dataIn->camt;
				$dataArr['b2bur']['itms']['itm_det']['samt'] = (float)$dataIn->samt;
				$dataArr['b2bur']['itms']['itm_det']['csamt'] = (float)$dataIn->csamt;
				$u++;
				$count++;
				}
			}
			}
			$dataArr['b2bur']['itms']['num'] = $count;
		}
		$dataInv = $this->getGstr4ImpsInvoices($user_id,$returnmonth);
		if (isset($dataInv) && !empty($dataInv)) {
            $x = 0;
            $y=0;
			$u=0;
			$count=0;
            foreach ($dataInv as $dataIn) {
               
                $dataArr['imp_s']['inum'] = $dataIn->inum;
                $dataArr['imp_s']['idt'] = $dataIn->idt;
                $dataArr['imp_s']['val'] = (float)$dataIn->val;
                $dataArr['imp_s']['pos'] = $dataIn->place_of_supply;
				$itemquery="select item.item_name,item.purchase_invoice_id, item.taxable_subtotal,item.consolidate_rate as rate ,item.cgst_amount as camt,item.igst_amount as iamt,item.sgst_amount as samt,item.cess_amount as csamt from gst_client_purchase_invoice_item as item   where item.purchase_invoice_id=".$dataIn->invoice_id."";
				$dataInvItem = $this->get_results($itemquery);
				if (isset($dataInvItem) && !empty($dataInvItem)) {
				foreach ($dataInvItem as $dataInItem) { 	
				$dataArr['imp_s']['itms']['rt'] = (float)$dataIn->rate;
				$dataArr['imp_s']['itms']['txval'] = (float)$dataIn->taxable_subtotal;
				$dataArr['imp_s']['itms']['iamt'] = (float)$dataIn->iamt;				
				$dataArr['imp_s']['itms']['csamt'] = (float)$dataIn->csamt;
				$u++;
				$count++;
				}
				}
			}
			
		}
		*/
		
	    $response['data_arr'] = $dataArr;
		//echo json_encode($dataArr);
        return $response;
		}

  

    

   

     

    
}
    
   
  
  
  
  
  
 
   
  
   
  
	
	
	
	

  
	


	
  
   
   
  
	
	
	
	
	
	
	

	
	
	
	
	
	
	
	
	
	
	
	
	
	