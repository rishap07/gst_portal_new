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
$aColumns = array('cm.item_id','cm.item_name', 'cm.item_category','CONCAT(UCASE(LEFT(m.item_name,1)),LCASE(SUBSTRING(m.item_name,2))) as category_name','m.hsn_code', 'cm.unit_price', 'cm.status');
$aSearchColumns = array('cm.item_name', 'm.item_name', 'm.hsn_code', 'cm.unit_price', 'cm.status');
$sIndexColumn = "item_id";
$sIndexColumn1 = "cm.item_id";

/* DB table to use */
$cmTable = $obj_client->getTableName('client_master_item');
$mTable = $obj_client->getTableName('item');

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
        $uOrder = "ORDER BY cm.item_id DESC";
    }
}

/*
 * Filtering
 * NOTE this does not match the built-in DataTables filtering which does it
 * word by word on any field. It's possible to do here, but concerned about efficiency
 * on very large tables, and MySQL's regex functionality is very limited
 */

$uWhere = " where cm.is_deleted='0' AND cm.added_by='".$_SESSION['user_detail']['user_id']."' AND cm.item_category = m.item_id ";
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
            FROM $cmTable as cm, $mTable as m
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
$uQuery = "SELECT COUNT(" . $sIndexColumn1 . ") as count FROM $cmTable as cm, $mTable as m $uWhere";
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
    foreach($rResult as $aRow) {

        $row = array();
        $status = '';

        if($aRow->status == '0') {
            $status = '<span class="inactive">InActive<span>';
        } elseif($aRow->status == '1'){
            $status = '<span class="active">Active<span>';
        }

        $row[] = $temp_x;
        $row[] = utf8_decode($aRow->item_name);
        $row[] = utf8_decode($aRow->category_name);
        $row[] = utf8_decode($aRow->hsn_code);
        $row[] = utf8_decode($aRow->unit_price);
        $row[] = $status;
        $row[] = '<a href="'.PROJECT_URL.'/?page=client_item_update&action=editItem&id='.$aRow->item_id.'" class="iconedit hint--bottom" data-hint="Edit" ><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;<a href="'.PROJECT_URL.'/?page=client_item_list&action=deleteItem&id='.$aRow->item_id.'" class="iconedit hint--bottom" data-hint="Delete" ><i class="fa fa-trash"></i></a>';
        $output['aaData'][] = $row;
        $temp_x++;
    }
}

echo json_encode($output);
?>