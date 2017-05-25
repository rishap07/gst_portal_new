<?php
/*
 * 
 *  Developed By        :   Love Kumawat
 *  Date Created        :   Sep 12, 2016
 *  Last Modified       :   Sep 16, 2016
 *  Last Modified By    :   Love Kumawat
 *  Last Modification   :   Ternder Listing
 * 
 */
$obj_master = new master();
extract($_POST);
//Columns to fetch from database
$aColumns = array('r.receiver_id','r.gstid','r.name','r.address','s.state_name','r.state_code', 'r.status');
$sIndexColumn = "r.receiver_id";

/* DB table to use */
$sTable = $obj_master->getTableName('receiver')." r left join ".$obj_master->getTableName('state')." s on r.state=s.state_id";

/*
 * Paging
 */
$sLimit = "";
if (isset($_POST['iDisplayStart']) && $_POST['iDisplayLength'] != '-1') {
    $sLimit = "LIMIT " . $obj_master->escape($_POST['iDisplayStart']) . ", " .
            $obj_master->escape($_POST['iDisplayLength']);
}

/*
 * Ordering
 */
$sOrder = "";
if (isset($_POST['iSortCol_0'])) {
    $sOrder = "ORDER BY ";
    for ($i = 0; $i < intval($_POST['iSortingCols']); $i++) {
        if ($_POST['bSortable_' . intval($_POST['iSortCol_' . $i])] == "true") {
            $sOrder .= $aColumns[intval($_POST['iSortCol_' . $i])] . "
				 	" .$obj_master->escape($_POST['sSortDir_' . $i]) . ", ";
        }
    }
    if ($sOrder == "ORDER BY ") {
        $sOrder = "ORDER BY r.receiver_id DESC";
    }
}

/*
 * Filtering
 * NOTE this does not match the built-in DataTables filtering which does it
 * word by word on any field. It's possible to do here, but concerned about efficiency
 * on very large tables, and MySQL's regex functionality is very limited
 */
//$sWhere = "WHERE is_deleted = '0' AND language_id = '".$_SESSION['lang_id']."' ";.
$sWhere=" where r.is_deleted='0'";
if (isset($_POST['sSearch']) && $_POST['sSearch'] != "") {
    for ($i = 0; $i < count($aColumns1); $i++) {
        $sWhere .= $aColumns1[$i] . " LIKE '%" . utf8_encode(htmlentities($_POST['sSearch'],ENT_COMPAT,'utf-8')) . "%' OR ";
    }
    $sWhere = substr_replace($sWhere, "", -3);
    $sWhere .= ')';
}

/* Individual column filtering */
for ($i = 0; $i < count($aColumns); $i++) {
    if (isset($_POST['bSearchable_' . $i]))
        if ((isset($_POST['bSearchable_' . $i]) && $_POST['bSearchable_' . $i] == "true") && (isset($_POST['sSearch_' . $i]) && $_POST['sSearch_' . $i] != '')) {
            $sWhere .= " AND ";
            $sWhere .= $aColumns[$i] . " LIKE '%" . $obj_master->escape($_POST['sSearch_' . $i]) . "%' ";
        }
}

/*
 * SQL queries
 * Get data to display
 */
$sWhere = trim(trim($sWhere), 'AND');
$sQuery = " SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) . "
            FROM $sTable
            $sWhere
            $sOrder
            $sLimit
	";
//echo $sQuery; die;
$rResult = $obj_master->get_results($sQuery);

/* Data set length after filtering */
$sQuery = "SELECT FOUND_ROWS() as rows";
$iFilteredTotal = $obj_master->get_row($sQuery);
$iFilteredTotal = $iFilteredTotal->rows;

/* Total data set length */
$sQuery = "SELECT COUNT(" . $sIndexColumn . ") as count FROM $sTable";
//echo $sQuery;
$iTotal = $obj_master->get_row($sQuery);
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
if(isset($rResult) && !empty($rResult))
{
foreach($rResult as $aRow) {
    $row = array();
    $status = '';
    if($aRow->status == '0'){
        $status = '<span class="inactive">InActive<span>';
    }elseif($aRow->status == '1'){
        $status = '<span class="active">Active<span>';
    }
    
    $row[] = $temp_x;
    $row[] = utf8_decode($aRow->gstid);
    $row[] = utf8_decode($aRow->name);
    $row[] = utf8_decode($aRow->address);
    $row[] = utf8_decode($aRow->state_name);
    $row[] = utf8_decode($aRow->state_code);
    $row[] = $status;
    $row[] = '<a href="'.PROJECT_URL.'/?page=master_receiver_update&id='.$aRow->receiver_id.'" class="iconedit hint--bottom" data-hint="Edit" ><i class="fa fa-pencil"></i></a>';
    $output['aaData'][] = $row;
    $temp_x++;
}
}

echo json_encode($output);
?>