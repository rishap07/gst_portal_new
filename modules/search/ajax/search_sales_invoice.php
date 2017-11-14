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
parse_str($_POST['salesSearchData'], $params);
//print_r($_POST);
//Columns to fetch from database
$aColumns = array('ci.invoice_id', 'ci.invoice_type', 'ci.invoice_nature', 'ci.serial_number', 'ci.reference_number', 'ci.supply_type', 'ci.export_supply_meant', 'ci.invoice_date', 'ci.supply_place', 'ci.billing_name', 'ci.billing_state', 'ci.billing_state_name', 'ci.billing_gstin_number', 'ci.is_canceled', 'ci.invoice_total_value', 'ci.financial_year');

$aSearchColumns = array('ci.invoice_type', 'ci.invoice_nature', 'ci.serial_number', 'ci.reference_number', 'ci.supply_type', 'ci.export_supply_meant', 'ci.invoice_date', 'ci.supply_place', 'ci.billing_name', 'ci.billing_state', 'ci.billing_state_name', 'ci.billing_gstin_number', 'ci.is_canceled', 'ci.invoice_total_value', 'ci.financial_year');
$sIndexColumn = "invoice_id";

/* DB table to use */
$ciTable = $db_obj->getTableName('client_invoice');

/*
 * Paging
*/
$uLimit = "";
if (isset($_POST['iDisplayStart']) && $_POST['iDisplayLength'] != '-1') {
    $uLimit = " LIMIT " . $db_obj->escape($_POST['iDisplayStart']) . ", " . $db_obj->escape($_POST['iDisplayLength']);
}

/*
 * Ordering
*/
$uOrder = "";
if (isset($_POST['iSortCol_0'])) {

    $uOrder = "ORDER BY ";
    for ($i = 0; $i < intval($_POST['iSortingCols']); $i++) {
        if ($_POST['bSortable_' . intval($_POST['iSortCol_' . $i])] == "true") {
            $uOrder .= $aColumns[intval($_POST['iSortCol_' . $i])] . " " .$db_obj->escape($_POST['sSortDir_' . $i]) . ", ";
        }
    }

    if ($uOrder == "ORDER BY ") {
        $uOrder = "ORDER BY ci.serial_number ASC";
    }

	$uOrder = rtrim($uOrder,", ");
}

/*
 * Filtering
 * NOTE this does not match the built-in DataTables filtering which does it
 * word by word on any field. It's possible to do here, but concerned about efficiency
 * on very large tables, and MySQL's regex functionality is very limited
*/

$uWhere = " where 1=1 AND ci.is_deleted='0' AND ci.added_by='".$db_obj->sanitize($_SESSION['user_detail']['user_id'])."' ";

if((isset($params['from_date']) && !empty($params['from_date'])) || (isset($_POST['from_date']) && !empty($_POST['from_date']))) {
	$from_date = (isset($params['from_date']) && $params['from_date'])?$params['from_date']:(isset($_POST['from_date']) &&$_POST['from_date']?$_POST['from_date']:'');
	$uWhere .= " AND ci.invoice_date >= '" . $from_date ."'";
}

if((isset($params['to_date']) && !empty($params['to_date'])) || (isset($_POST['to_date']) && !empty($_POST['to_date']))) {
	$to_date = (isset($params['to_date']) && $params['to_date'])?$params['to_date']:(isset($_POST['to_date']) && $_POST['to_date']?$_POST['to_date']:'');
	$uWhere .= " AND ci.invoice_date <= '" . $to_date ."'";
}

if(isset($params['invoice_type']) && !empty($params['invoice_type'])) {
	$uWhere .= " AND ci.invoice_type = '" . $params['invoice_type'] ."'";
}

if(isset($params['supply_type']) && !empty($params['supply_type'])) {
	$uWhere .= " AND ci.supply_type = '" . $params['supply_type'] ."'";
}

if(isset($params['reference_number']) && !empty($params['reference_number'])) {
	$uWhere .= " AND ci.reference_number LIKE '%" . $params['reference_number'] ."%'";
}

if(isset($params['place_of_supply']) && !empty($params['place_of_supply'])) {
	$uWhere .= " AND ci.supply_place = " . $params['place_of_supply'];
}

if(isset($params['billing_state']) && !empty($params['billing_state'])) {
	$uWhere .= " AND ci.billing_state = " . $params['billing_state'];
}

if(isset($params['billing_gstin_number']) && !empty($params['billing_gstin_number'])) {
	$uWhere .= " AND ci.billing_gstin_number LIKE '%" . $params['billing_gstin_number'] ."%'";
}

if(isset($params['is_canceled']) && !empty($params['is_canceled']) || isset($_POST['is_canceled']) && !empty($_POST['is_canceled'])) {
	$uWhere .= " AND ci.is_canceled = '1'";
}


if (isset($_POST['sSearch']) && $_POST['sSearch'] != "") {

    $uWhere .= 'AND (';
    for ($i = 0; $i < count($aSearchColumns); $i++) {
        $uWhere .= $aSearchColumns[$i] . " LIKE '%" . utf8_encode(htmlentities($_POST['sSearch'],ENT_COMPAT,'utf-8')) . "%' OR ";
    }
    $uWhere = substr_replace($uWhere, "", -3);
    $uWhere .= ')';
}

/* Individual column filtering */
for ($i = 0; $i < count($aColumns); $i++) {
    
    if (isset($_POST['bSearchable_' . $i])) {
        
        if ((isset($_POST['bSearchable_' . $i]) && $_POST['bSearchable_' . $i] == "true") && (isset($_POST['sSearch_' . $i]) && $_POST['sSearch_' . $i] != '')) {
            $uWhere .= " AND ";
            $uWhere .= $aColumns[$i] . " LIKE '%" . $db_obj->escape($_POST['sSearch_' . $i]) . "%' ";
        }
    }
}

/*
 * SQL queries
 * Get data to display
 */
$uWhere = trim(trim($uWhere), 'AND');
$uQuery = " SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) . "
            FROM $ciTable as ci 
            $uWhere
            $uOrder
            $uLimit
	";
//echo $uQuery; die;
$rResult = $db_obj->get_results($uQuery);

/* Data set length after filtering */
$uQuery = "SELECT FOUND_ROWS() as rows";
$iFilteredTotal = $db_obj->get_row($uQuery);
$iFilteredTotal = $iFilteredTotal->rows;

/* Total data set length */
if(isset($params['invoice_type']) && !empty($params['invoice_type'])) {	
	$uQuery = "SELECT COUNT(" . $sIndexColumn . ") as count FROM $ciTable where invoice_type IN('" . $params['invoice_type'] ."') AND is_deleted='0' AND added_by='".$db_obj->sanitize($_SESSION['user_detail']['user_id'])."'";
} else {
	$uQuery = "SELECT COUNT(" . $sIndexColumn . ") as count FROM $ciTable where is_deleted='0' AND added_by='".$db_obj->sanitize($_SESSION['user_detail']['user_id'])."'";
}
//echo $sQuery;
$iTotal = $db_obj->get_row($uQuery);
$iTotal = $iTotal->count;

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
	
    foreach($rResult as $aRow) {

        $row = array();

		$supply_place_data = $db_obj->getStateDetailByStateId($aRow->supply_place);
		if($supply_place_data['status'] == 'success') {
			$supply_place = $supply_place_data['data']->state_name;
		} else {
			$supply_place = "-";
		}
		
		if($aRow->invoice_type == "taxinvoice") {
			$invoice_type = "Tax";
		} else if($aRow->invoice_type == "exportinvoice") {
			$invoice_type = "Export";
		} else if($aRow->invoice_type == "sezunitinvoice") {
			$invoice_type = "SEZ Unit";
		} else if($aRow->invoice_type == "deemedexportinvoice") {
			$invoice_type = "Deemed Export";
		} else if($aRow->invoice_type == "billofsupplyinvoice") {
			$invoice_type = "Bill Of Supply";
		} else if($aRow->invoice_type == "receiptvoucherinvoice") {
			$invoice_type = "Receipt Voucher";
		} else if($aRow->invoice_type == "refundvoucherinvoice") {
			$invoice_type = "Refund Voucher";
		} else if($aRow->invoice_type == "revisedtaxinvoice") {
			$invoice_type = "Revised Tax";
		} else if($aRow->invoice_type == "creditnote") {
			$invoice_type = "Credit Note";
		} else if($aRow->invoice_type == "debitnote") {
			$invoice_type = "Debit Note";
		} else if($aRow->invoice_type == "deliverychallaninvoice") {
			$invoice_type = "Delivery Challan";
		}

		$row[] = $temp_x;
		$row[] = $invoice_type;
		$row[] = $aRow->reference_number;
		$row[] = $aRow->invoice_date;
		$row[] = html_entity_decode($supply_place);
		$row[] = html_entity_decode($aRow->billing_name);
		$row[] = $aRow->billing_gstin_number;
		$row[] = $aRow->billing_state_name;
		$row[] = $aRow->invoice_total_value;
		
		if($aRow->invoice_type == "taxinvoice") {
			$row[] = '<a target="_blank" href="'.PROJECT_URL.'/?page=client_invoice_list&action=printInvoice&id='.$aRow->invoice_id.'" class="btn btn-success mr-r-5">Print</a><a target="_blank" href="'.PROJECT_URL.'/?page=client_update_invoice&action=editInvoice&id='.$aRow->invoice_id.'" class="btn btn-warning">Edit</a>';
		} else if($aRow->invoice_type == "exportinvoice") {
			$row[] = '<a target="_blank" href="'.PROJECT_URL.'/?page=client_invoice_list&action=printInvoice&id='.$aRow->invoice_id.'" class="btn btn-success mr-r-5">Print</a><a target="_blank" href="'.PROJECT_URL.'/?page=client_update_export_invoice&action=editInvoice&id='.$aRow->invoice_id.'" class="btn btn-warning">Edit</a>';
		} else if($aRow->invoice_type == "sezunitinvoice") {
			$row[] = '<a target="_blank" href="'.PROJECT_URL.'/?page=client_invoice_list&action=printInvoice&id='.$aRow->invoice_id.'" class="btn btn-success mr-r-5">Print</a><a target="_blank" href="'.PROJECT_URL.'/?page=client_update_export_invoice&action=editInvoice&id='.$aRow->invoice_id.'" class="btn btn-warning">Edit</a>';
		} else if($aRow->invoice_type == "deemedexportinvoice") {
			$row[] = '<a target="_blank" href="'.PROJECT_URL.'/?page=client_invoice_list&action=printInvoice&id='.$aRow->invoice_id.'" class="btn btn-success mr-r-5">Print</a><a target="_blank" href="'.PROJECT_URL.'/?page=client_update_export_invoice&action=editInvoice&id='.$aRow->invoice_id.'" class="btn btn-warning">Edit</a>';
		} else if($aRow->invoice_type == "billofsupplyinvoice") {
			$row[] = '<a target="_blank" href="'.PROJECT_URL.'/?page=client_bill_of_supply_invoice_list&action=printBOSInvoice&id='.$aRow->invoice_id.'" class="btn btn-success mr-r-5">Print</a><a target="_blank" href="'.PROJECT_URL.'/?page=client_update_bill_of_supply_invoice&action=editBOSInvoice&id='.$aRow->invoice_id.'" class="btn btn-warning">Edit</a>';
		} else if($aRow->invoice_type == "receiptvoucherinvoice") {
			$row[] = '<a target="_blank" href="'.PROJECT_URL.'/?page=client_receipt_voucher_invoice_list&action=printRVInvoice&id='.$aRow->invoice_id.'" class="btn btn-success mr-r-5">Print</a><a target="_blank" href="'.PROJECT_URL.'/?page=client_update_receipt_voucher_invoice&action=editRVInvoice&id='.$aRow->invoice_id.'" class="btn btn-warning">Edit</a>';
		} else if($aRow->invoice_type == "refundvoucherinvoice") {
			$row[] = '<a target="_blank" href="'.PROJECT_URL.'/?page=client_refund_voucher_invoice_list&action=printRFInvoice&id='.$aRow->invoice_id.'" class="btn btn-success mr-r-5">Print</a><a target="_blank" href="'.PROJECT_URL.'/?page=client_update_refund_voucher_invoice&action=editRFInvoice&id='.$aRow->invoice_id.'" class="btn btn-warning">Edit</a>';
		} else if($aRow->invoice_type == "revisedtaxinvoice") {
			$row[] = '<a target="_blank" href="'.PROJECT_URL.'/?page=client_revised_tax_invoice_list&action=printRTInvoice&id='.$aRow->invoice_id.'" class="btn btn-success mr-r-5">Print</a><a target="_blank" href="'.PROJECT_URL.'/?page=client_update_revised_tax_invoice&action=editRTInvoice&id='.$aRow->invoice_id.'" class="btn btn-warning">Edit</a>';
		} else if($aRow->invoice_type == "creditnote") {
			$row[] = '<a target="_blank" href="'.PROJECT_URL.'/?page=client_revised_tax_invoice_list&action=printRTInvoice&id='.$aRow->invoice_id.'" class="btn btn-success mr-r-5">Print</a><a target="_blank" href="'.PROJECT_URL.'/?page=client_update_revised_tax_invoice&action=editRTInvoice&id='.$aRow->invoice_id.'" class="btn btn-warning">Edit</a>';
		} else if($aRow->invoice_type == "debitnote") {
			$row[] = '<a target="_blank" href="'.PROJECT_URL.'/?page=client_revised_tax_invoice_list&action=printRTInvoice&id='.$aRow->invoice_id.'" class="btn btn-success mr-r-5">Print</a><a target="_blank" href="'.PROJECT_URL.'/?page=client_update_revised_tax_invoice&action=editRTInvoice&id='.$aRow->invoice_id.'" class="btn btn-warning">Edit</a>';
		} else if($aRow->invoice_type == "deliverychallaninvoice") {
			$row[] = '<a target="_blank" href="'.PROJECT_URL.'/?page=client_delivery_challan_invoice_list&action=printDCInvoice&id='.$aRow->invoice_id.'" class="btn btn-success mr-r-5">Print</a><a target="_blank" href="'.PROJECT_URL.'/?page=client_update_delivery_challan_invoice&action=editDCInvoice&id='.$aRow->invoice_id.'" class="btn btn-warning">Edit</a>';
		}

		$output['aaData'][] = $row;
		$temp_x++;
    }
}
echo json_encode($output);
die;
?>