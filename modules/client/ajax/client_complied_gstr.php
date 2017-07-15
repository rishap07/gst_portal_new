<?php
/*
    * 
    *  Developed By        :   Rishap Gandhi
    *  Date Created        :   Sep 12, 2016
    *  Last Modified       :   Sep 16, 2016
    *  Last Modified By    :   Rishap Gandhi
    *  Last Modification   :   Admin User Listing
    * 
 */

$obj_client = new client();
$finance = isset($_POST['financial']) ? $_POST['financial'] : '';
$finance_data = $obj_client->getClientReturn($finance);
if(count($finance_data)>0)
{
//Columns to fetch from database
$aColumns = array('serial_number','invoice_id', 'invoice_total_value','invoice_date');
$sIndexColumn = "invoice_id";

/* DB table to use */
$uTable = $obj_client->getTableName('client_invoice');

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
        $uOrder = "ORDER BY invoice_id DESC";
    }
}

/*
 * Filtering
 * NOTE this does not match the built-in DataTables filtering which does it
 * word by word on any field. It's possible to do here, but concerned about efficiency
 * on very large tables, and MySQL's regex functionality is very limited
 */

$uWhere = " where  added_by='".$_SESSION['user_detail']['user_id']."' ";
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
            FROM $uTable
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
$uQuery = "SELECT COUNT(" . $sIndexColumn . ") as count FROM $uTable where   added_by='".$_SESSION['user_detail']['user_id']."' and invoice_date like '%".$finance_data[0]->return_month."%'";
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
if(isset($rResult) && !empty($rResult))
{
    //$invoice_ids= array();
foreach($rResult as $aRow) {
    //print_r($aRow);exit; 
    $row = array();
    
    
    $row[] = "<input type='checkbox' id='invoice_value_".$aRow->invoice_id."' name = 'invoice_ids[]' value='".$aRow->invoice_id."' class='check'>";
    $row[] = utf8_decode($aRow->serial_number);
    $row[] = utf8_decode(date('Y-m',strtotime($aRow->invoice_date)));
    $row[] = utf8_decode($aRow->invoice_total_value);
    
    $row[] = '<a href="'.PROJECT_URL.'/?page=client_invoice_view&action=viewInvoice&id='.$aRow->invoice_id.'" class="iconedit hint--bottom" data-hint="Edit" ><i class="fa fa-eye"></i>';
    
    $output['aaData'][] = $row; 
    $temp_x++;
}
}
}
else
{
    $iTotal=0;
    $iFilteredTotal=0;
    $output = array(
        "sEcho" => intval($_POST['sEcho']),
        "iTotalRecords" => $iTotal,
        "iTotalDisplayRecords" => $iFilteredTotal,
        "aaData" => array()
    );
}
echo json_encode($output);
?>
