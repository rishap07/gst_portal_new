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
$aColumns = array('ci.invoice_id', 'ci.invoice_type', 'ci.invoice_nature', 'ci.serial_number', 'ci.company_name', 'ci.company_address', 'ci.gstin_number', 'ci.supply_type', 'ci.invoice_date', 'ci.is_canceled', 'ci.invoice_total_value', 'ci.billing_name', 'ci.shipping_name');

$aSearchColumns = array('ci.invoice_type', 'ci.invoice_nature', 'ci.serial_number', 'ci.invoice_date', 'ci.invoice_total_value', 'ci.billing_name', 'ci.shipping_name');
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

$temp_x=isset($_POST['iDisplayStart']) ? $_POST['iDisplayStart']+ 1 : 1;
if(isset($rResult) && !empty($rResult)) {
	
    foreach($rResult as $aRow) {

        $row = array();
        $invoice_type = '';
		$invoice_nature = '';
		$supply_type = '';
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

        if($aRow->supply_type == 'reversecharge') {
            $supply_type = 'Reverse Charge';
        } elseif($aRow->supply_type == 'tds'){
            $supply_type = 'TDS';
        } elseif($aRow->supply_type == 'tcs'){
            $supply_type = 'TCS';
        } else {
			$supply_type = 'Normal';
		}

		if($aRow->is_canceled == '0') {
            $is_canceled = '<span class="no">No<span>';
        } elseif($aRow->is_canceled == '1'){
            $is_canceled = '<span class="yes">Yes<span>';
        }
		$row[]= '<tr><td valign="top"><input type="checkbox"></td></td>';

		if($aRow->invoice_type == 'exportinvoice') {
			$row[] = '<td><div class="list-primary pull-left"><div title="Mr. c c"  class="name"><A href="#">'.$aRow->billing_name.'</A></div><a href="'.PROJECT_URL.'/?page=client_invoice_list&action=viewInvoice&id='.$aRow->invoice_id.'" data-bind="'.$aRow->invoice_id.'">'.$aRow->serial_number.'</a> | ' . $aRow->invoice_date . '</div><span class="pull-right"><div class="amount"><i class="fa fa-inr" aria-hidden="true"></i>'.$aRow->invoice_total_value.'</div><div class="greylinktext"><a href="'.PROJECT_URL.'/?page=client_update_export_invoice&action=editInvoice&id='.$aRow->invoice_id.'">Edit Invoice</a></div></span></td></tr>';
		} else {
			$row[] = '<td><div class="list-primary pull-left"><div title="Mr. c c"  class="name"><A href="#">'.$aRow->billing_name.'</A></div><a href="'.PROJECT_URL.'/?page=client_invoice_list&action=viewInvoice&id='.$aRow->invoice_id.'" data-bind="'.$aRow->invoice_id.'">'.$aRow->serial_number.'</a> | ' . $aRow->invoice_date . '</div><span class="pull-right"><div class="amount"><i class="fa fa-inr" aria-hidden="true"></i>'.$aRow->invoice_total_value.'</div><div class="greylinktext"><a href="'.PROJECT_URL.'/?page=client_update_invoice&action=editInvoice&id='.$aRow->invoice_id.'">Edit Invoice</a></div></span></td></tr>';
		}

		/*$row[] = utf8_decode($aRow->serial_number);
		//$row[] = utf8_decode($invoice_type);
		//$row[] = utf8_decode($invoice_nature);
        $row[] = utf8_decode($aRow->invoice_date);
		//$row[] = $supply_type;
		$row[] = utf8_decode($aRow->billing_name);
        //$row[] = utf8_decode($aRow->shipping_name);
		$row[] = utf8_decode($aRow->invoice_total_value);
		//$row[] = $is_canceled;
        $row[] = '<a href="'.PROJECT_URL.'/?page=client_update_invoice&action=editInvoice&id='.$aRow->invoice_id.'" class="iconedit hint--bottom" data-hint="Edit" ><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;<a href="'.PROJECT_URL.'/?page=client_invoice_list&action=deleteInvoice&id='.$aRow->invoice_id.'" class="iconedit hint--bottom" data-hint="Delete" ><i class="fa fa-trash"></i></a>';
		*/
        $output['aaData'][] = $row;
        $temp_x++;
		
    }
}
echo json_encode($output);
?>