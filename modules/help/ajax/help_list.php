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
$obj_help = new help();
extract($_POST);

//Columns to fetch from database
$aColumns = array('h.id', 'h.help_name', 'h.help_message', 'h.help_document','h.start_date','h.end_date','h.status');
$aSearchColumns = array('h.id', 'h.help_name');
$sIndexColumn = "h.id";

/* DB table to use */
//$spTable = "gst_coupon as g";
 /* DB table to use */
//$spTable = $obj_master->getTableName('coupon');
// $spTable = $spTable.' '.'as g';
$spTable = "gst_help as h";

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
        $spOrder = "ORDER BY h.id ASC";
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
        $status = '<span class="inactive btn btn-danger">InActive<span>';
    }elseif($aRow->status == '1'){
        $status = '<span class="active btn btn-success">Active<span>';
    }
	
	$image='';
	  if(!$aRow->help_document){
        $image = '';
    }else{
        $image = "<a  href=".PROJECT_URL.'/upload/help-images/'.$aRow->help_document." download ><i class='fa fa-download' aria-hidden='true'></i></a>";
    }
	
    
   
   
    $row[] = $temp_x;
    $row[] = utf8_decode(substr($aRow->help_name,0,35));
	$message=$obj_help->strip_tags_content(html_entity_decode($aRow->help_message));
	$readmore='<a href="'.PROJECT_URL.'/?page=help_view&id='.$aRow->id.'" class="iconedit hint--bottom" data-hint="Edit" >Read more</a>';
   	$row[] = utf8_decode(substr($message,0,80).'&nbsp;'.$readmore);
    $row[] = utf8_decode($aRow->start_date);
	$row[] = utf8_decode($aRow->end_date);
	$row[] = utf8_decode($image);
    $row[] = utf8_decode($status);
   
 
    //$row[] = $status;
    $row[] = '<a href="'.PROJECT_URL.'/?page=help_update&action=editHelp&id='.$aRow->id.'" class="iconedit hint--bottom" data-hint="Edit" ><i class="fa fa-pencil"></i></a>';
    $output['aaData'][] = $row;
    $temp_x++;
}
}

echo json_encode($output);
?>