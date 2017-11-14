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
$obj_notification = new notification();
extract($_POST);

//Columns to fetch from database
$aColumns = array('n.notification_id', 'n.notification_name','DATE_FORMAT(n.added_date, "%e %M %Y %r") as added_date', 'n.notification_message');
$aSearchColumns = array('n.notification_id', 'n.notification_name','n.added_date');
$sIndexColumn = "n.notification_id";
 // $sql="select * from " . $db_obj->getTableName('notification') . " as n INNER join " . $db_obj->getTableName('user_notification') . " as u on u.notification_id=n.notification_id  where n.status='1' and  u.user_id='".$_SESSION["user_detail"]["user_id"]."' order by u.notification_id desc";
				

 $spTable = "gst_notification as n inner join gst_user_notification as u on u.notification_id=n.notification_id  where n.status='1' AND  u.user_id='".$_SESSION["user_detail"]["user_id"]."'";

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
        $spOrder = "ORDER BY n.notification_id DESC";
    }
}

/*
 * Filtering
 * NOTE this does not match the built-in DataTables filtering which does it
 * word by word on any field. It's possible to do here, but concerned about efficiency
 * on very large tables, and MySQL's regex functionality is very limited
 */

if (isset($_POST['sSearch']) && $_POST['sSearch'] != "") {
    
   // $spWhere .= 'AND (';
   // for ($i = 0; $i < count($aSearchColumns); $i++) {
    //    $spWhere .= $aSearchColumns[$i] . " LIKE '%" . utf8_encode(htmlentities($_POST['sSearch'],ENT_COMPAT,'utf-8')) . "%' OR ";
  //  }
  //  $spWhere = substr_replace($spWhere, "", -3);
   // $spWhere .= ')';
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
      
    
    $row[] = $temp_x;
    $row[] = utf8_decode($aRow->notification_name);
   // $row[] = utf8_decode(html_entity_decode(mb_substr($aRow->notification_message, 0, 100).$readmore));
    $message=$obj_notification->strip_tags_content(html_entity_decode($aRow->notification_message));
	$readmore='&nbsp<a href="'.PROJECT_URL.'/?page=notification_view&id='.$aRow->notification_id.'" class="iconedit hint--bottom" data-hint="Edit" >Read more</a>';
    $row[] = utf8_decode(html_entity_decode(implode(' ', array_slice(str_word_count($message, 2), 0,20)).$readmore));
    $row[] = $aRow->added_date;
    $output['aaData'][] = $row;
    $temp_x++;
}
}

echo json_encode($output);
?>