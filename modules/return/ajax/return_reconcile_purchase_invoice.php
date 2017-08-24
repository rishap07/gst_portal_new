<?php
$obj_gstr2 = new gstr2();
$staus = $_POST['status'];
$case = $_POST['case'];
if ($status == 'update') {
    $status = 2;
} else if ($status == 'reject') {
    $status = 3;
} else if ($status == 'pending') {
    $status = 4;
}
$invoiceId = $_POST['invoiceId'];
$returnmonth = $_POST['returnmonth'];


    $dataArr1['status'] = $status;
    $dataConditionArray['reference_number'] = $invoiceId;
    $obj_gstr2->UPDATE($obj_gstr2->getTableName('client_reconcile_purchase_invoice1'), $dataArr1, $dataConditionArray);
    echo json_encode("success");

?>