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
$returnmonth = $_GET['returnmonth'];

//Columns to fetch from database
$aColumns = array('ci.purchase_invoice_id', 'ci.invoice_type', 'ci.invoice_nature', 'ci.serial_number', 'ci.reference_number', 'ci.supply_type', 'ci.import_supply_meant', 'ci.invoice_date', 'ci.supply_place', 'ci.supplier_billing_name', 'ci.supplier_billing_state', 'ci.supplier_billing_state_name', 'ci.supplier_billing_gstin_number', 'ci.is_canceled', 'ci.invoice_total_value', 'ci.financial_year');

$aSearchColumns = array('ci.invoice_type', 'ci.invoice_nature', 'ci.serial_number', 'ci.reference_number', 'ci.supply_type', 'ci.import_supply_meant', 'ci.invoice_date', 'ci.supply_place', 'ci.supplier_billing_name', 'ci.supplier_billing_state', 'ci.supplier_billing_state_name', 'ci.supplier_billing_gstin_number', 'ci.is_canceled', 'ci.invoice_total_value', 'ci.financial_year');
$sIndexColumn = "purchase_invoice_id";

/* DB table to use */
$ciTable = $db_obj->getTableName('client_purchase_invoice');

/*
 * Paging
*/
$uLimit = "";
if (isset($_POST['iDisplayStart']) && $_POST['iDisplayLength'] != '-1') {
    $uLimit = "LIMIT " . $db_obj->escape($_POST['iDisplayStart']) . ", " . $db_obj->escape($_POST['iDisplayLength']);
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

if(isset($returnmonth) && !empty($returnmonth)) {
	$uWhere .= " AND DATE_FORMAT(ci.invoice_date,'%Y-%m') = '" . $returnmonth ."'";
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
$uQuery = "SELECT COUNT(" . $sIndexColumn . ") as count FROM $ciTable where is_deleted='0' AND added_by='".$db_obj->sanitize($_SESSION['user_detail']['user_id'])."'";
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
		} else if($aRow->invoice_type == "importinvoice") {
			$invoice_type = "Import";
		} else if($aRow->invoice_type == "sezunitinvoice") {
			$invoice_type = "SEZ Unit";
		} else if($aRow->invoice_type == "deemedimportinvoice") {
			$invoice_type = "Deemed Import";
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
		} else if($aRow->invoice_type == "paymentvoucherinvoice") {
			$invoice_type = "Payment Voucher";
		}

		$row[] = $temp_x;
		$row[] = $invoice_type;
		$row[] = $aRow->reference_number;
		$row[] = $aRow->invoice_date;
		$row[] = html_entity_decode($supply_place);
		$row[] = html_entity_decode($aRow->supplier_billing_name);
		$row[] = $aRow->supplier_billing_gstin_number;
		$row[] = $aRow->supplier_billing_state_name;
		$row[] = $aRow->invoice_total_value;
		$output['aaData'][] = $row;
		$temp_x++;
    }
}
echo json_encode($output);
die;
?>