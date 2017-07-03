<?php
/*
    * 
    *  Developed By        :   Rishap Gandhi
    *  Date Created        :   Sep 12, 2016
    *  Last Modified       :   Sep 16, 2016
    *  Last Modified By    :   Rishap Gandhi
    *  Last Modification   :   Admin User Listing
    * 
 */

$obj_client = new client();
extract($_POST);
if(date('Y-m-d')>=date('Y-m')."-01" && date('Y-m-d')<=date('Y-m-t'))
{
    $f_year =date('Y')."-".(date('Y')+1);
    if(date('m')<4)
    {
       $f_year =  (date('Y')-1)."-".(date('Y'));
    }
    $data = $obj_client->get_results("select * from ".$obj_client->getTableName('return')." where type='gstr1' and return_month='".date("Y-m", strtotime("-1 months"))."' and financial_year='".$f_year."'"
            . " and client_id='".$_SESSION['user_detail']['user_id']."'");
    if(empty($data))
    {
        $dataArr = array();
        $dataArr['financial_year'] = $f_year;
        $dataArr['return_month'] =date("Y-m", strtotime("-1 months"));
        $dataArr['type'] = 'gstr1';
        $dataArr['client_id'] = $_SESSION['user_detail']['user_id'];
        $obj_client->insert($obj_client->getTableName('return'),$dataArr);
        
    }
}
//Columns to fetch from database
$aColumns = array('return_id', 'financial_year', 'return_month', 'type','status');
$sIndexColumn = "return_id";

/* DB table to use */
$uTable = $obj_client->getTableName('return');

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
        $uOrder = "ORDER BY return_id DESC";
    }
}

/*
 * Filtering
 * NOTE this does not match the built-in DataTables filtering which does it
 * word by word on any field. It's possible to do here, but concerned about efficiency
 * on very large tables, and MySQL's regex functionality is very limited
 */

$uWhere = " where  client_id='".$_SESSION['user_detail']['user_id']."' ";
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
$uQuery = "SELECT COUNT(" . $sIndexColumn . ") as count FROM $uTable where  client_id='".$_SESSION['user_detail']['user_id']."' ";
//echo $sQuery;
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
    $status='';
    if($aRow->status==0)
    {
        $status = 'Pending';
    }
    else if($aRow->status==1)
    {
        $status = 'Initiated';
    }
    else if($aRow->status==2)
    {
        $status = 'Completed';
    }

    $row[] = $temp_x;
    $row[] = utf8_decode($aRow->financial_year);
    $row[] = utf8_decode($aRow->return_month);
    $row[] = utf8_decode($aRow->type);
    $row[] = $status;
    if($aRow->type=='gstr1')
    {
        $row[] = '<a href="'.PROJECT_URL.'/?page=client_complied_gstr1&finanical='.$aRow->return_id.'" class="iconedit hint--bottom" data-hint="Edit" ><i class="fa fa-eye"></i></a>';
    }
    else
    {
        $row[] = '';
    }
    $output['aaData'][] = $row;
    $temp_x++;
}
}

echo json_encode($output);
?>