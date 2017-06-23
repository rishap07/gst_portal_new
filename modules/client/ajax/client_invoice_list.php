<?php
/*
    * 
    *  Developed By        :   Ishwar Lal Ghiya
    *  Date Created        :   June 6, 2017
    *  Last Modified By    :   Ishwar Lal Ghiya
    *  Last Modification   :   Client Item Listing
    * 
 */

$obj_client = new client();
extract($_POST);

//Columns to fetch from database
$aColumns = array('ci.invoice_id', 'ci.invoice_type', 'ci.invoice_nature', 'ci.serial_number', 'ci.company_name', 'ci.company_address', 'ci.gstin_number', 'ci.is_tax_payable', 'ci.invoice_date', 'ci.is_canceled', 'ci.supply_place', 'ms2.state_name as supply_state_name', 'ms2.state_code as supply_state_code', 'ms2.state_tin as supply_state_tin', 'ci.invoice_total_value', 'ci.billing_name', 'ci.billing_state', 'ms.state_name as billing_state_name', 'ms.state_code as billing_state_code', 'ms.state_tin as billing_state_tin', 'ci.shipping_name', 'ci.shipping_state', 'ms1.state_name as shipping_state_name', 'ms1.state_code as shipping_state_code', 'ms1.state_tin as shipping_state_tin');
$aSearchColumns = array('ci.invoice_type', 'ci.invoice_nature', 'ci.serial_number', 'ci.invoice_date', 'ci.invoice_total_value', 'ci.billing_name', 'ci.shipping_name', 'ms.state_name', 'ms.state_code', 'ms.state_tin', 'ms1.state_name', 'ms1.state_code', 'ms1.state_tin', 'ms2.state_name', 'ms2.state_code', 'ms2.state_tin');
$sIndexColumn = "invoice_id";

/* DB table to use */
$ciTable = $obj_client->getTableName('client_invoice');
$msTable = $obj_client->getTableName('state');

/*
 * Paging
*/
$uLimit = "";
if (isset($_POST['iDisplayStart']) && $_POST['iDisplayLength'] != '-1') {
    $uLimit = "LIMIT " . $obj_client->escape($_POST['iDisplayStart']) . ", " . $obj_client->escape($_POST['iDisplayLength']);
}

/*
 * Ordering
*/
$uOrder = "";
if (isset($_POST['iSortCol_0'])) {

    $uOrder = "ORDER BY ";
    for ($i = 0; $i < intval($_POST['iSortingCols']); $i++) {
        if ($_POST['bSortable_' . intval($_POST['iSortCol_' . $i])] == "true") {
            $uOrder .= $aColumns[intval($_POST['iSortCol_' . $i])] . " " .$obj_client->escape($_POST['sSortDir_' . $i]) . ", ";
        }
    }
    if ($uOrder == "ORDER BY ") {
        $uOrder = "ORDER BY ci.invoice_id DESC";
    }
}

/*
 * Filtering
 * NOTE this does not match the built-in DataTables filtering which does it
 * word by word on any field. It's possible to do here, but concerned about efficiency
 * on very large tables, and MySQL's regex functionality is very limited
*/

$uWhere = " where ci.is_deleted='0' AND ci.added_by='".$_SESSION['user_detail']['user_id']."' ";
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
            $uWhere .= $aColumns[$i] . " LIKE '%" . $obj_client->escape($_POST['sSearch_' . $i]) . "%' ";
        }
    }
}

/*
 * SQL queries
 * Get data to display
 */
$uWhere = trim(trim($uWhere), 'AND');
$uQuery = " SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) . "
            FROM $ciTable as ci INNER JOIN $msTable as ms ON ci.billing_state = ms.state_id INNER JOIN $msTable as ms1 ON ci.shipping_state = ms1.state_id INNER JOIN $msTable as ms2 ON ci.supply_place = ms2.state_id
            $uWhere
            $uOrder
            $uLimit
	";
//echo $uQuery; die;
$rResult = $obj_client->get_results($uQuery);

/* Data set length after filtering */
$uQuery = "SELECT FOUND_ROWS() as rows";
$iFilteredTotal = $obj_client->get_row($uQuery);
$iFilteredTotal = $iFilteredTotal->rows;

/* Total data set length */
$uQuery = "SELECT COUNT(" . $sIndexColumn . ") as count FROM $ciTable";
//echo $sQuery;
$iTotal = $obj_client->get_row($uQuery);
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

$temp_x=1;
if(isset($rResult) && !empty($rResult)) {
	
    foreach($rResult as $aRow) {

        $row = array();
        $invoice_type = '';
		$invoice_nature = '';
		$is_tax_payable = '';
		$is_canceled = '';
		
		if($aRow->invoice_type == 'taxinvoice') {
            $invoice_type = 'Tax Invoice';
        } elseif($aRow->invoice_type == 'exportinvoice'){
            $invoice_type = 'Export Invoice';
        } elseif($aRow->invoice_type == 'sezunitinvoice'){
            $invoice_type = 'SEZ Unit Invoice';
        } elseif($aRow->invoice_type == 'deemedexportinvoice'){
            $invoice_type = 'Deemed Export Invoice';
        }
		
		if($aRow->invoice_nature == 'salesinvoice') {
            $invoice_nature = 'Sales Invoice';
        } elseif($aRow->invoice_nature == 'purchaseinvoice'){
            $invoice_nature = 'Purchase Invoice';
        }

        if($aRow->is_tax_payable == '0') {
            $is_tax_payable = '<span class="no">No<span>';
        } elseif($aRow->is_tax_payable == '1'){
            $is_tax_payable = '<span class="yes">Yes<span>';
        }
		
		if($aRow->is_canceled == '0') {
            $is_canceled = '<span class="no">No<span>';
        } elseif($aRow->is_canceled == '1'){
            $is_canceled = '<span class="yes">Yes<span>';
        }

        $row[] = $temp_x;
		$row[] = utf8_decode($aRow->serial_number);
		$row[] = utf8_decode($invoice_type);
		$row[] = utf8_decode($invoice_nature);
        $row[] = utf8_decode($aRow->invoice_date);
		$row[] = utf8_decode($aRow->supply_state_name) . " (" . utf8_decode($aRow->supply_state_tin) . ")";
		$row[] = $is_tax_payable;
		$row[] = utf8_decode($aRow->billing_name);
        $row[] = utf8_decode($aRow->billing_state_name) . " (" . utf8_decode($aRow->billing_state_tin) . ")";
        $row[] = utf8_decode($aRow->shipping_name);
        $row[] = utf8_decode($aRow->shipping_state_name) . " (" . utf8_decode($aRow->shipping_state_tin) . ")";
		$row[] = utf8_decode($aRow->invoice_total_value);
		$row[] = $is_canceled;
        //$row[] = '<a href="'.PROJECT_URL.'/?page=client_update_invoice&action=editInvoice&id='.$aRow->invoice_id.'" class="iconedit hint--bottom" data-hint="Edit" ><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;<a href="'.PROJECT_URL.'/?page=client_invoice_list&action=deleteInvoice&id='.$aRow->invoice_id.'" class="iconedit hint--bottom" data-hint="Delete" ><i class="fa fa-trash"></i></a>';
        $output['aaData'][] = $row;
        $temp_x++;
    }
}

echo json_encode($output);
?>