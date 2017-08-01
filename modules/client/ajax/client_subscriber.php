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

$db_obj = new validation();
extract($_POST);


//Columns to fetch from database
$aColumns = array('u.user_id', 'u.first_name', 'u.last_name', 'u.username', 'u.email', 'u.phone_number', 's.name', 'p.name planname',
    'u.payment_status');
$aSearchColumns = array('u.first_name', 'u.last_name', 'u.username', 'u.email', 'u.phone_number');
$sIndexColumn = "u.user_id";

/* DB table to use */
$spTable = $db_obj->getTableName('user') . " u inner join " . TAB_PREFIX . "subscriber_plan p"
        . " on u.plan_id =p.id Left Join " . TAB_PREFIX . "subscriber_plan_category s"
        . " on p.plan_category =s.id";



/*
  // * Paging
  // */
$spLimit = "";
if (isset($_POST['iDisplayStart']) && $_POST['iDisplayLength'] != '-1') {
    $spLimit = "LIMIT " . $db_obj->escape($_POST['iDisplayStart']) . ", " . $db_obj->escape($_POST['iDisplayLength']);
}

///*
// * Ordering
// */
$spOrder = "";
if (isset($_POST['iSortCol_0'])) {

    $spOrder = "ORDER BY ";
    for ($i = 0; $i < intval($_POST['iSortingCols']); $i++) {
        if ($_POST['bSortable_' . intval($_POST['iSortCol_' . $i])] == "true") {
            $spOrder .= $aColumns[intval($_POST['iSortCol_' . $i])] . " " . $db_obj->escape($_POST['sSortDir_' . $i]) . ", ";
        }
    }
    if ($spOrder == "ORDER BY ") {
        $spOrder = "ORDER BY u.payment_status=1 , u.user_id DESC";
    }
}
//
///*
// * Filtering
// * NOTE this does not match the built-in DataTables filtering which does it
// * word by word on any field. It's possible to do here, but concerned about efficiency
// * on very large tables, and MySQL's regex functionality is very limited
// */
//if($_SESSION['user_detail']['user_group']=='1'){
//    $spWhere = " where r.is_deleted='0' ";
//    $spWhere1 = " where r.is_deleted='0' ";
//} else {
//    $spWhere = " where r.is_deleted='0' and r.added_by='".$_SESSION['user_detail']['user_id']."' ";
//    $spWhere1 = " where r.is_deleted='0' and r.added_by='".$_SESSION['user_detail']['user_id']."' ";
//}
//
$spWhere = " where u.user_group='3' and u.is_deleted='0' and u.plan_id!='21' and u.email not like 'aditya.kumar_@cyfuture.com'";
if (isset($_POST['sSearch']) && $_POST['sSearch'] != "") {

    $spWhere .= 'AND (';
    for ($i = 0; $i < count($aSearchColumns); $i++) {
        $spWhere .= $aSearchColumns[$i] . " LIKE '%" . utf8_encode(htmlentities($_POST['sSearch'], ENT_COMPAT, 'utf-8')) . "%' OR ";
    }
    $spWhere = substr_replace($spWhere, "", -3);
    $spWhere .= ')';
}

//
/* Individual column filtering */
for ($i = 0; $i < count($aColumns); $i++) {

    if (isset($_POST['bSearchable_' . $i])) {

        if ((isset($_POST['bSearchable_' . $i]) && $_POST['bSearchable_' . $i] == "true") && (isset($_POST['sSearch_' . $i]) && $_POST['sSearch_' . $i] != '')) {
            $spWhere .= " AND ";
            $spWhere .= $aColumns[$i] . " LIKE '%" . $db_obj->escape($_POST['sSearch_' . $i]) . "%' ";
        }
    }
}
//
///*
// * SQL queries
// * Get data to display
// */
$spWhere = trim(trim($spWhere), 'AND');
//$spjoin = "r inner join".$sp1Table."s on r.plan_category=s.id";
$spjoin = $db_obj->getTableName('user') . " u Left join " . TAB_PREFIX . "subscriber_plan p"
        . " on u.plan_id =p.id Left Join " . TAB_PREFIX . "subscriber_plan_category s"
        . " on p.plan_category =s.id";
$spQuery = " SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) . "
            FROM $spjoin
            $spWhere
            $spOrder
            $spLimit
	";
//
//echo $spQuery; die;
$rResult = $db_obj->get_results($spQuery);
//die("yes");
// echo "<pre>";
//        print_r($rResult);
//        echo "</pre>";
//        die();
///* Data set length after filtering */
$spQuery = "SELECT FOUND_ROWS() as rows";
$iFilteredTotal = $db_obj->get_row($spQuery);
$iFilteredTotal = $iFilteredTotal->rows;
//
///* Total data set length */
$spQuery = "SELECT COUNT(" . $sIndexColumn . ") as count FROM $spTable";
//echo $spQuery;
$iTotal = $db_obj->get_row($spQuery);
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

$temp_x = isset($_POST['iDisplayStart']) ? $_POST['iDisplayStart'] + 1 : 1;
if (isset($rResult) && !empty($rResult)) {
    foreach ($rResult as $aRow) {
        $row = array();
        $status = '';
//    if($aRow->status == '0'){
//        $status = '<span class="inactive">InActive<span>';
//    }elseif($aRow->status == '1'){
//        $status = '<span class="active">Active<span>';
//    }
//    $visible = '';
//    if($aRow->visible == '0'){
//        $visible = '<span class="novisible">No<span>';
//    }elseif($aRow->visible == '1'){
//        $visible = '<span class="yesvisible">Yes<span>';
//    }
        //    (case when u.payment_status='0' Then 'pending' when  u.payment_status='1' then 'accepted' when  u.payment_status='2' then 'mark as fraud' when  u.payment_status='3' then 'rejected' when  u.payment_status='4' then 'refunded' end) as payment_status
        if ($aRow->payment_status == 0) {
            $status = 'pending';
        } elseif ($aRow->payment_status == 1) {
             $status = 'accepted';
        } elseif ($aRow->payment_status == 2) {
             $status = 'mark as fraud';
        } elseif ($aRow->payment_status == 3) {
             $status = 'rejected';
        } else {
             $status = 'refunded';
        }

        
        $row[] = $temp_x;
         $row[] = utf8_decode($aRow->first_name.' '.$aRow->last_name);
        $row[] = utf8_decode($aRow->username);
        $row[] = utf8_decode($aRow->email);
        $row[] = utf8_decode($aRow->phone_number);
        $row[] = utf8_decode($aRow->name . ':' . $aRow->planname);
        $row[] = utf8_decode($status);

        //$row[] = $visible;
//    $row[] = $status;
        $row[] = '<a href="' . PROJECT_URL . '/?page=client_subscribeview&action=view&id=' . $aRow->user_id . '" class="iconedit hint--bottom" '
                . 'data-hint="view" ><i class="fa fa-eye"></i></a>'
        ;
        $output['aaData'][] = $row;
        $temp_x++;
    }
}

echo json_encode($output);
?>