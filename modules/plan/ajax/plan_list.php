<?php
/*
    * 
    *  Developed By        :   Love Kumawat
    *  Date Created        :   Sep 12, 2016
    *  Last Modified       :   Sep 16, 2016
    *  Last Modified By    :   Ishwar Lal Ghiya
    *  Last Modification   :   Plan Category Listing
    * 
 */

$obj_plan = new plan();
extract($_POST);

//Columns to fetch from database
$aColumns = array('id', 'name', 'description', 'no_of_client', 'plan_category', 'plan_price', 'visible', 'status');
$sIndexColumn = "id";

/* DB table to use */
$spTable = $obj_plan->getTableName('subscriber_plan');

/*
 * Paging
 */
$spLimit = "";
if (isset($_POST['iDisplayStart']) && $_POST['iDisplayLength'] != '-1') {
    $spLimit = "LIMIT " . $obj_plan->escape($_POST['iDisplayStart']) . ", " . $obj_plan->escape($_POST['iDisplayLength']);
}

/*
 * Ordering
 */
$spOrder = "";
if (isset($_POST['iSortCol_0'])) {

    $spOrder = "ORDER BY ";
    for ($i = 0; $i < intval($_POST['iSortingCols']); $i++) {
        if ($_POST['bSortable_' . intval($_POST['iSortCol_' . $i])] == "true") {
            $spOrder .= $aColumns[intval($_POST['iSortCol_' . $i])] . " " .$obj_plan->escape($_POST['sSortDir_' . $i]) . ", ";
        }
    }
    if ($spOrder == "ORDER BY ") {
        $spOrder = "ORDER BY id ASC";
    }
}

/*
 * Filtering
 * NOTE this does not match the built-in DataTables filtering which does it
 * word by word on any field. It's possible to do here, but concerned about efficiency
 * on very large tables, and MySQL's regex functionality is very limited
 */
if($_SESSION['user_detail']['user_group']=='1')
{
    $spWhere = " where is_deleted='0'";
}
else
{
    $spWhere = " where is_deleted='0' and added_by='".$_SESSION['user_detail']['user_id']."'";
}
if (isset($_POST['sSearch']) && $_POST['sSearch'] != "") {
    for ($i = 0; $i < count($aColumns1); $i++) {
        $spWhere .= $aColumns1[$i] . " LIKE '%" . utf8_encode(htmlentities($_POST['sSearch'],ENT_COMPAT,'utf-8')) . "%' OR ";
    }
    $spWhere = substr_replace($spWhere, "", -3);
    $spWhere .= ')';
}

/* Individual column filtering */
for ($i = 0; $i < count($aColumns); $i++) {
    if (isset($_POST['bSearchable_' . $i]))
        if ((isset($_POST['bSearchable_' . $i]) && $_POST['bSearchable_' . $i] == "true") && (isset($_POST['sSearch_' . $i]) && $_POST['sSearch_' . $i] != '')) {
            $spWhere .= " AND ";
            $spWhere .= $aColumns[$i] . " LIKE '%" . $obj_plan->escape($_POST['sSearch_' . $i]) . "%' ";
        }
}

/*
 * SQL queries
 * Get data to display
 */
$spWhere = trim(trim($spWhere), 'AND');
$spQuery = " SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) . "
            FROM $spTable
            $spWhere
            $spOrder
            $spLimit
	";
//echo $spQuery; die;
$rResult = $obj_plan->get_results($spQuery);

/* Data set length after filtering */
$spQuery = "SELECT FOUND_ROWS() as rows";
$iFilteredTotal = $obj_plan->get_row($spQuery);
$iFilteredTotal = $iFilteredTotal->rows;

/* Total data set length */
$spQuery = "SELECT COUNT(" . $sIndexColumn . ") as count FROM $spTable";
//echo $sQuery;
$iTotal = $obj_plan->get_row($spQuery);
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
    
    $visible = '';
    if($aRow->visible == '0'){
        $visible = '<span class="novisible">No<span>';
    }elseif($aRow->visible == '1'){
        $visible = '<span class="yesvisible">Yes<span>';
    }
    
    $row[] = $temp_x;
    $row[] = utf8_decode($aRow->name);
    $row[] = utf8_decode($aRow->description);
    $row[] = utf8_decode($aRow->no_of_client);
    $row[] = utf8_decode($aRow->plan_category);
    $row[] = utf8_decode($aRow->plan_price);
    $row[] = $visible;
    $row[] = $status;
    $row[] = '<a href="'.PROJECT_URL.'/?page=plan_editplan&action=editPlan&id='.$aRow->id.'" class="iconedit hint--bottom" data-hint="Edit" ><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;<a href="'.PROJECT_URL.'/?page=plan_list&action=deletePlan&id='.$aRow->id.'" class="iconedit hint--bottom" data-hint="Delete" ><i class="fa fa-trash"></i></a>';
    $output['aaData'][] = $row;
    $temp_x++;
}
}

echo json_encode($output);
?>