<?php
/*
    * 
    *  Developed By        :   Lokesh Chotiya
    *  Date Created        :   Aug 12, 2017
    *  Last Modified       :   Aug 12, 2017
    *  Last Modified By    :   Lokesh Chotiya
    *  Last Modification   :   Activity Log Listing
    * 
 */

$obj_plan = new plan();
$obj_master = new master();
extract($_POST);

//Columns to fetch from database
$aColumns = array('u.username','u.first_name','u.user_group', 'g.module_name', 'g.msg','g.ip','g.dateoflog','g.userid');
$aSearchColumns = array('g.userid', 'u.first_name','u.username','g.module_name');
$sIndexColumn = "g.id";
 // $sql="select * from " . $db_obj->getTableName('notification') . " as n INNER join " . $db_obj->getTableName('user_notification') . " as u on u.notification_id=n.notification_id  where n.status='1' and  u.user_id='".$_SESSION["user_detail"]["user_id"]."' order by u.notification_id desc";
$spTable='';				
if($_SESSION["user_detail"]["user_group"]==4)
{
 $spTable = "".$db_obj->getTableName('admin_log')." as g inner join ".$db_obj->getTableName('user')." as u on g.userid=u.user_id  where userid='".$_SESSION["user_detail"]["user_id"]."'";
}
if($_SESSION["user_detail"]["user_group"]==3)
{
$sql="select * from ".TAB_PREFIX."user WHERE added_by=".$_SESSION["user_detail"]["user_id"]."";
$dataCurrentArr = $db_obj->get_results($sql);
$userid ="";
if(!empty($dataCurrentArr))
{
	foreach($dataCurrentArr as $user)
	{
		$userid = $userid.$user->user_id.',';
	}
	   $userid  = substr($userid,0,-1);
	   $spTable = "".$db_obj->getTableName('admin_log')." as g inner join ".$db_obj->getTableName('user')." as u on g.userid=u.user_id  where is_deleted='0' and (u.user_group='4' OR u.user_group='3')  OR g.userid='".$_SESSION["user_detail"]["user_id"]."' and g.userid in('".$userid."')";

}	
else
{	
	
 $spTable = "".$db_obj->getTableName('admin_log')." as g inner join ".$db_obj->getTableName('user')." as u on g.userid=u.user_id  where is_deleted='0' and  g.userid='".$_SESSION["user_detail"]["user_id"]."'";
}
}
if($_SESSION["user_detail"]["user_group"]==2)
{
$spTable = "".$db_obj->getTableName('admin_log')." as g inner join ".$db_obj->getTableName('user')." as u on g.userid=u.user_id  where u.added_by='".$_SESSION["user_detail"]["user_id"]."' or is_deleted='0' and (u.user_group='4' OR u.user_group='3' or user_group='2') OR g.userid='".$_SESSION["user_detail"]["user_id"]."'";
}
if($_SESSION["user_detail"]["user_group"]==1)
{
 $spTable = "".$db_obj->getTableName('admin_log')." as g inner join ".$db_obj->getTableName('user')." as u on g.userid=u.user_id  where u.added_by='".$_SESSION["user_detail"]["user_id"]."' or is_deleted='0' and (u.user_group='4' OR u.user_group='3' or user_group='2' or user_group='1') OR g.userid='".$_SESSION["user_detail"]["user_id"]."'";
}

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
$spWhere ="And is_deleted='0'";
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
 $spQuery;
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
	 $sql="select username,first_name from ".TAB_PREFIX."user WHERE user_id='".$aRow->userid."'";
	
	$dataUserArr = $obj_plan->get_results($sql);
	
    $row = array();
      
  
    $row[] = $temp_x;
    $row[] = utf8_decode($aRow->module_name);
    $row[] = utf8_decode($aRow->msg);
	if(!empty($dataUserArr))
	{
	$row[] = utf8_decode($dataUserArr[0]->first_name.'('.$dataUserArr[0]->username.')');
	}
	else
	{
		$row[] = utf8_decode('');
	}
	$row[] = utf8_decode($aRow->ip);
	$row[] = utf8_decode($aRow->dateoflog);
   
    $output['aaData'][] = $row;
    $temp_x++;
}
}

echo json_encode($output);
?>