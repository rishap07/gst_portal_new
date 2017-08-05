<?php
/*
    * 
    *  Developed By        :   Ishwar Lal Ghiya
    *  Date Created        :   June 6, 2017
    *  Last Modified By    :   Ishwar Lal Ghiya
    *  Last Modification   :   Client Bill of Supply Listing
    * 
*/

$obj_client = new client();
extract($_POST);

//Columns to fetch from database
$aColumns = array('ci.invoice_id', 'ci.invoice_type', 'ci.serial_number', 'ci.reference_number', 'ci.invoice_date', 'ci.is_canceled', 'ci.invoice_total_value', 'ci.billing_name');

$aSearchColumns = array('ci.invoice_type', 'ci.serial_number', 'ci.reference_number', 'ci.invoice_date', 'ci.reference_number', 'ci.invoice_total_value', 'ci.billing_name');
$sIndexColumn = "invoice_id";

/* DB table to use */
$ciTable = $obj_client->getTableName('client_invoice');

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

$uWhere = " where ci.invoice_type = 'refundvoucherinvoice' AND ci.is_deleted='0' AND ci.added_by='".$obj_client->sanitize($_SESSION['user_detail']['user_id'])."' ";
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
            FROM $ciTable as ci 
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
$uQuery = "SELECT COUNT(" . $sIndexColumn . ") as count FROM $ciTable where invoice_type = 'refundvoucherinvoice' AND is_deleted='0' AND added_by='".$obj_client->sanitize($_SESSION['user_detail']['user_id'])."'";
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

$temp_x = isset($_POST['iDisplayStart']) ? $_POST['iDisplayStart']+ 1 : 1;
if(isset($rResult) && !empty($rResult)) {
	
    foreach($rResult as $aRow) {

		$row = array();
		$cancelLink = '';

		if($aRow->is_canceled == '0') {
            $cancelLink = '<a class="cancelSalesInvoice" data-invoice-id="'.$aRow->invoice_id.'" href="javascript:void(0)">Cancel</a>';
        } elseif($aRow->is_canceled == '1'){
            $cancelLink = '<a class="revokeSalesInvoice" data-invoice-id="'.$aRow->invoice_id.'" href="javascript:void(0)">Revoke</a>';
        }

		$row[]= '<tr><td valign="top"><input name="sales_invoice[]" value="'.$aRow->invoice_id.'" class="salesInvoice" type="checkbox"></td></td>';

		$row[] = '<td><div class="list-primary pull-left"><div class="name"><a href="'.PROJECT_URL.'/?page=client_refund_voucher_invoice_list&action=viewRFInvoice&id='.$aRow->invoice_id.'">'.$aRow->billing_name.'</a></div><a href="'.PROJECT_URL.'/?page=client_refund_voucher_invoice_list&action=viewRFInvoice&id='.$aRow->invoice_id.'">'.$aRow->serial_number.'</a> | ' . $aRow->invoice_date . '</div><span class="pull-right"><div class="amount"><i class="fa fa-inr" aria-hidden="true"></i>'.$aRow->invoice_total_value.'</div><div class="greylinktext"><a href="'.PROJECT_URL.'/?page=client_update_refund_voucher_invoice&action=editRFInvoice&id='.$aRow->invoice_id.'">Edit</a>&nbsp;&nbsp;'.$cancelLink.'</div></span></td></tr>';

		$output['aaData'][] = $row;
        $temp_x++;
    }
}
echo json_encode($output);
?>