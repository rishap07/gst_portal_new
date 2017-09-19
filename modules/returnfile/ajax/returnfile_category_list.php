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
$aColumns = array('g.return_name','g.order_value','g.returntofile_vendor_id','g.returnfile_type','g.return_subheading','g.return_url','g.id','g.status');
$aSearchColumns = array('g.return_name', 'g.status');
$sIndexColumn = "g.id";

/* DB table to use */
//$spTable = "gst_coupon as g";
 /* DB table to use */
//$spTable = $obj_master->getTableName('coupon');
// $spTable = $spTable.' '.'as g';
$spTable = "gst_return_categories as g";

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
        $spOrder = "ORDER BY g.id DESC";
    }
}

/*
 * Filtering
 * NOTE this does not match the built-in DataTables filtering which does it
 * word by word on any field. It's possible to do here, but concerned about efficiency
 * on very large tables, and MySQL's regex functionality is very limited
 */
 $spWhere = "where is_deleted='0'";
if (isset($_POST['sSearch']) && $_POST['sSearch'] != "") {
    
    $spWhere .= 'AND (';
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
//echo $spQuery;
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
    $vendor_name='';
	  if($aRow->returntofile_vendor_id == '0'){
        $vendor_name = 'All';
    }else{
		$vendordata = $db_obj->get_results("select * from gst_vendor_type where vendor_id='".$aRow->returntofile_vendor_id."'");
        //$type = $aRow->returntofile_vendor_id;
		$vendor_name = $vendordata[0]->vendor_name;
    } 
    $return_type='';
	if($aRow->returnfile_type=='0')
	{
		$return_type='Monthly';
	}
	elseif($aRow->returnfile_type=='1')
	{
		$return_type='Quartaly';
	}
	elseif($aRow->returnfile_type=='2')
	{
		$return_type='Yearly';
	}
	
   
    $row[] = $temp_x;
    $row[] = utf8_decode($aRow->return_name);
	$row[] = utf8_decode($vendor_name);
	$row[] = utf8_decode($return_type);
	$row[] = utf8_decode($aRow->order_value);
	$row[] = utf8_decode($status);
	
   
    //$row[] = $status;
    $row[] = '<a href="'.PROJECT_URL.'/?page=returnfile_category_update&action=editReturnFile&id='.$aRow->id.'" class="iconedit hint--bottom" data-hint="Edit" ><i class="fa fa-pencil"></i></a>';
    $output['aaData'][] = $row;
    $temp_x++;
}
}

echo json_encode($output);
?>