<?php
/*
  *
  *  Developed By  : Ishwar lal Ghiya
  *  Date Created  : Sep 12, 2017
  *  Developed For : Master Receiver Listing
  *
*/

$obj_master = new json();
extract($_POST);

//Columns to fetch from database


$aColumns = array('inv.supplier_billing_gstin_number', 'it.igst_amount', 'it.sgst_amount', 'it.cgst_amount', 'it.cess_amount', 'inv.reference_number', 'inv.invoice_total_value','inv.invoice_date','inv.supplier_billing_name','it.taxable_subtotal');
$aSearchColumns = array('inv.supplier_billing_gstin_number', 'it.igst_amount', 'it.sgst_amount', 'it.cgst_amount', 'it.cess_amount', 'inv.reference_number', 'inv.invoice_total_value','inv.invoice_date','inv.supplier_billing_name','it.taxable_subtotal');
$sIndexColumn = "inv.reference_number";


/*
 * Paging
 */
$sLimit = "";
if (isset($_POST['iDisplayStart']) && $_POST['iDisplayLength'] != '-1') {
    $sLimit = "LIMIT " . $obj_master->escape($_POST['iDisplayStart']) . ", " . $obj_master->escape($_POST['iDisplayLength']);
}



/*
 * Filtering
 * NOTE this does not match the built-in DataTables filtering which does it
 * word by word on any field. It's possible to do here, but concerned about efficiency
 * on very large tables, and MySQL's regex functionality is very limited
 */
$sWhere=" ";
if (isset($_POST['sSearch']) && $_POST['sSearch'] != "") {
    
	$sWhere .= 'AND (';
	for ($i = 0; $i < count($aSearchColumns); $i++) {
        $sWhere .= $aSearchColumns[$i] . " LIKE '%" . utf8_encode(htmlentities($_POST['sSearch'],ENT_COMPAT,'utf-8')) . "%' OR ";
    }
    $sWhere = substr_replace($sWhere, "", -3);
    $sWhere .= ')';
}

/* Individual column filtering */
for ($i = 0; $i < count($aColumns); $i++) {
	
	if (isset($_POST['bSearchable_' . $i])) {
		if ((isset($_POST['bSearchable_' . $i]) && $_POST['bSearchable_' . $i] == "true") && (isset($_POST['sSearch_' . $i]) && $_POST['sSearch_' . $i] != '')) {
			$sWhere .= " AND ";
			$sWhere .= $aColumns[$i] . " LIKE '%" . $obj_master->escape($_POST['sSearch_' . $i]) . "%' ";
		}
	}
}

/*
 * SQL queries
 * Get data to display
 */
$sWhere = trim(trim($sWhere), 'AND');
$data = array();
	if($type!='' && $type=='b2b')
	{
		
		$order_by = 'inv.reference_number';
		$data = $obj_master->getGstr2B2BQuery($_SESSION['user_detail']['user_id'],$returnmonth,'','','',$order_by,$sWhere);
	}elseif($type!='' && $type=='b2bur')
	{
		$order_by = 'inv.reference_number';
		$data = $obj_master->getGstr2B2BURQuery($_SESSION['user_detail']['user_id'],$returnmonth,'','','',$order_by,$sWhere);
		
	}elseif($type!='' && $type=='cdnur' )
	{	
		$order_by = 'inv.reference_number';
		$data = $obj_master->getGstr2CDNURQuery($_SESSION['user_detail']['user_id'],$returnmonth,$type,'','',$order_by,$sWhere);
		
	}elseif($type!='' && $type=='imps' )
	{	
		$order_by = 'inv.reference_number';
		$data = $obj_master->getGstr2IMPSQuery($_SESSION['user_detail']['user_id'],$returnmonth,$type,'','',$order_by,$sWhere);
		
		
	}
	elseif($type!='' && $type=='cdn' )
	{	
		$order_by = 'inv.reference_number';
		$data = $obj_master->getGstr2CDNQuery($_SESSION['user_detail']['user_id'],$returnmonth,$type,'','',$order_by,$sWhere='');
	}
	elseif($type!='' && $type=='impg' )
	{	 $sWhere='sezunitinvoice';
		$order_by = 'inv.reference_number';
		$data = $obj_master->getGstr2CDNQuery($_SESSION['user_detail']['user_id'],$returnmonth,$type,'','',$order_by,$sWhere);
	}


$rResult = $data;

/* Data set length after filtering */
$sQuery = "SELECT FOUND_ROWS() as rows";
$iFilteredTotal = $obj_master->get_row($sQuery);
$iFilteredTotal = $iFilteredTotal->rows;

$iTotal = count($data);


/*
 * Output
 */
$output = array(
    "sEcho" => intval($_POST['sEcho']),
    "iTotalRecords" => $iTotal,
    "iTotalDisplayRecords" => $iFilteredTotal,
    "aaData" => array()
);

$temp_x=isset($_POST['iDisplayStart']) ? $_POST['iDisplayStart']+ 1 : 1;
if(isset($rResult) && !empty($rResult)) {
$temp_inv = '';
	
	$taxable_subtotal=$tax=$tax1=$tax2=$tax3= 0;
	$temp_x=0;
	foreach($rResult as $aRow) {

		if($temp_inv!='' and $temp_inv!=$aRow['reference_number'])
		{
			$row = array();
			
			$row[] = $invoice_date;
			$row[] = $reference_number;
			$row[] = $vender_name;
			$row[] = $supplier_billing_gstin_number;
			$row[] = number_format($taxable_subtotal,2);
			$row[] = number_format($tax,2);
			$row[] = number_format($tax1,2);
			$row[] = number_format($tax2,2);
			$row[] = number_format($tax3,3);
			$row[] = $invoice_total_value;
			$row[] = $status;
			$output['aaData'][] = $row;
			$temp_x++;
			$taxable_subtotal=$tax=$tax1=$tax2=$tax3= 0;
		}
		$inv_id = $aRow['purchase_invoice_id'];
		$invoice_date = $aRow['invoice_date'];
		$reference_number = $aRow['reference_number'];
		$vender_name = $aRow['supplier_billing_name'];
		$supplier_billing_gstin_number = $aRow['supplier_billing_gstin_number'];
		$taxable_subtotal += $aRow['taxable_subtotal'];
		$invoice_total_value = $aRow['invoice_total_value'];
		$tax += $aRow['cgst'];
		$tax1 += $aRow['sgst'];
		$tax2 += $aRow['igst'];
		$tax3 += $aRow['cess'];
		$status = ($aRow['is_gstr2_uploaded']==0)? 'Pending' : 'Uploaded';
		$temp_inv=$aRow['reference_number'];
	}
	
	if($temp_inv!='')
	{
		$row = array();
		$row[] = $invoice_date;
		$row[] = $reference_number;
		$row[] = $vender_name;
		$row[] = $supplier_billing_gstin_number;
		$row[] = $taxable_subtotal;
		$row[] = $tax;
		$row[] = $tax1;
		$row[] = $tax2;
		$row[] = $tax3;
		$row[] = $invoice_total_value;
		$row[] = $status;
		$output['aaData'][] = $row;
		$temp_x++;
		$taxable_subtotal=$tax= 0;
	}
}
$output['iTotalRecords']=$temp_x;
$output['iTotalDisplayRecords']=$temp_x;
echo json_encode($output);
?>