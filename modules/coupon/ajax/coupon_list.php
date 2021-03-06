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
$obj_master = new master();
extract($_POST);

//Columns to fetch from database
$aColumns = array('g.coupon_id', 'g.name', 'g.type', 'g.coupon_value','g.coupon_uses','g.hidden','g.status');
$aSearchColumns = array('g.coupon_id', 'g.name', 'g.type', 'g.coupon_value','g.coupon_uses','g.hidden','g.status');
$sIndexColumn = "g.coupon_id";

/* DB table to use */
//$spTable = "gst_coupon as g";
 /* DB table to use */
$spTable = $obj_master->getTableName('coupon');
 $spTable = $spTable.' '.'as g';

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
        $spOrder = "ORDER BY g.coupon_id ASC";
    }
}

/*
 * Filtering
 * NOTE this does not match the built-in DataTables filtering which does it
 * word by word on any field. It's possible to do here, but concerned about efficiency
 * on very large tables, and MySQL's regex functionality is very limited
 */
$spWhere ="";
if (isset($_POST['sSearch']) && $_POST['sSearch'] != "") {
    
    $spWhere .= 'where (';
    for ($i = 0; $i < count($aSearchColumns); $i++) {
       $spWhere .= $aSearchColumns[$i] . " LIKE '%" . utf8_encode(htmlentities($_POST['sSearch'],ENT_COMPAT,'utf-8')) . "%' OR ";
    }
    $spWhere = substr_replace($spWhere, "", -3);
   $spWhere .= ')';
}

/* Individual column filtering */
for ($i = 0; $i < count($aColumns); $i++) {
    
    if (isset($_POST['bSearchable_' . $i])) {
        
        if ((isset($_POST['bSearchable_' . $i]) && $_POST['bSearchable_' . $i] == "true") && (isset($_POST['sSearch_' . $i]) && $_POST['sSearch_' . $i] != '')) {
            $spWhere .= " AND ";
            $spWhere .= $aColumns[$i] . " LIKE '%" . $obj_plan->escape($_POST['sSearch_' . $i]) . "%' ";
        }
    }
}

/*
 * SQL queries
 * Get data to display
 */
//$spWhere = trim(trim("is_deleted=1"), 'AND');
//$spjoin = "r inner join".$sp1Table."s on r.plan_category=s.id";
$spjoin = $spTable;
 $spQuery = " SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) . "
            FROM $spjoin
            $spWhere
            $spOrder
            $spLimit
	"; 


$rResult = $obj_plan->get_results($spQuery);
// echo "<pre>";
//        print_r($rResult);
//        echo "</pre>";
//        die();
/* Data set length after filtering */
$spQuery = "SELECT FOUND_ROWS() as rows";
$iFilteredTotal = $obj_plan->get_row($spQuery);
$iFilteredTotal = $iFilteredTotal->rows;

/* Total data set length */
$spQuery = "SELECT COUNT(" . $sIndexColumn . ") as count FROM $spTable";

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

$temp_x=isset($_POST['iDisplayStart']) ? $_POST['iDisplayStart']+ 1 : 1;
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
	
	$type='';
	  if($aRow->type == '0'){
        $type = 'Amount';
    }elseif($aRow->type == '1'){
        $type = 'Percentage';
    }
	$hidden='';
	  if($aRow->hidden == '0'){
        $hidden = 'Active';
    }elseif($aRow->type == '1'){
        $hidden = 'Hidden';
    }
    
   
   
    $row[] = $temp_x;
    $row[] = utf8_decode($aRow->name);
    $row[] = utf8_decode($type);
    $row[] = utf8_decode($aRow->coupon_value);
    $row[] = utf8_decode($aRow->coupon_uses);
	$row[] = utf8_decode($hidden);
	$row[] = utf8_decode($status);
   
   
    //$row[] = $status;
    $row[] = '<a href="'.PROJECT_URL.'/?page=coupon_update&action=editCoupon&id='.$aRow->coupon_id.'" class="iconedit hint--bottom" data-hint="Edit" ><i class="fa fa-pencil"></i></a>';
    $output['aaData'][] = $row;
    $temp_x++;
}
}

echo json_encode($output);
?>