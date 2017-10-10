<?php
/*
    * 
    *  Developed By        :   Rishap Gandhi
    *  Date Created        :   Sep 12, 2016
    *  Last Modified       :   Sep 16, 2016
    *  Last Modified By    :   Ishwar Lal Ghiya
    *  Last Modification   :   Admin User Listing
    * 
 */

$obj_client = new client();
extract($_POST);

//Columns to fetch from database
$aColumns = array('user_id', 'CONCAT(first_name," ",last_name) as name', 'username', 'email', 'company_name', 'phone_number', 'status');
$aSearchColumns = array('first_name', 'last_name', 'username', 'email', 'company_name', 'phone_number', 'status');
$sIndexColumn = "user_id";

/* DB table to use */
$uTable = $obj_client->getTableName('user');

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
        $uOrder = "ORDER BY user_id DESC";
    }
}

/*
 * Filtering
 * NOTE this does not match the built-in DataTables filtering which does it
 * word by word on any field. It's possible to do here, but concerned about efficiency
 * on very large tables, and MySQL's regex functionality is very limited
 */

if($_SESSION['user_detail']['user_group'] == 1 || $_SESSION['user_detail']['user_group'] == 2) {
	$uWhere = " where is_deleted='0' AND user_group = '4' ";
} else {
	$uWhere = " where is_deleted='0' AND user_group = '4' AND added_by='".$_SESSION['user_detail']['user_id']."' ";
}
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
$uQuery = "SELECT COUNT(" . $sIndexColumn . ") as count FROM $uTable where  is_deleted='0' AND user_group = '4' AND added_by='".$_SESSION['user_detail']['user_id']."' ";
//echo $uQuery;die;
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
	$dataCurrentArr = $obj_client->getUserDetailsById($aRow->user_id);

    if($aRow->status == '0'){
        $status = '<span class="inactive">InActive<span>';
    }elseif($aRow->status == '1'){
        $status = '<span class="active">Active<span>';
    }

    $row[] = $temp_x;
    $row[] = utf8_decode($aRow->name);
    $row[] = utf8_decode($aRow->username);
    $row[] = utf8_decode($aRow->email);
    $row[] = utf8_decode($aRow->company_name);
    $row[] = utf8_decode($aRow->phone_number);
    $row[] = $status;
	
	if($_SESSION['user_detail']['user_group'] == 1 || $_SESSION['user_detail']['user_group'] == 2) {

		if ($dataCurrentArr['data']->kyc == '') {
			$row[] = '';
		} else {
			$row[] = '<a href="'.PROJECT_URL.'/?page=client_loginas&id='.$aRow->user_id.'" class="iconedit hint--bottom" data-hint="Login" >Login As Client</a>';
		}

	} else {

		if ($dataCurrentArr['data']->kyc == '') {
			$row[] = '<a href="'.PROJECT_URL.'/?page=client_update&action=editClient&id='.$aRow->user_id.'" class="iconedit hint--bottom" data-hint="Edit" ><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;<a href="'.PROJECT_URL.'/?page=client_kycupdate_by_subscriber&action=updateClientKYC&id='.$aRow->user_id.'" class="iconedit hint--bottom" data-hint="Update KYC" >Update KYC</a>';
		} else {
			$row[] = '<a href="'.PROJECT_URL.'/?page=client_update&action=editClient&id='.$aRow->user_id.'" class="iconedit hint--bottom" data-hint="Edit" ><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;<a href="'.PROJECT_URL.'/?page=client_loginas&id='.$aRow->user_id.'" class="iconedit hint--bottom" data-hint="Login" >Login As Client</a>&nbsp;&nbsp;<a href="'.PROJECT_URL.'/?page=client_kycupdate_by_subscriber&action=updateClientKYC&id='.$aRow->user_id.'" class="iconedit hint--bottom" data-hint="Update KYC" >Update KYC</a>';
		}
	}

	$output['aaData'][] = $row;
    $temp_x++;
}
}
echo json_encode($output);
?>