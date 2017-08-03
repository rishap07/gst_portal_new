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
if ($case == 'missing') {
    $query = "select *
					from " . $obj_gstr2->getTableName('client_invoice') . " i inner join " . $obj_gstr2->getTableName("client_invoice_item") . " it on i.invoice_id=it.invoice_id 
					where 
					i.invoice_id=" . $invoiceId;
} else {
    $query = "select *
					from " . $obj_gstr2->getTableName('client_purchase_invoice') . " i inner join " . $obj_gstr2->getTableName("client_purchase_invoice_item") . " it on i.purchase_invoice_id=it.purchase_invoice_id 
					where 
					i.purchase_invoice_id=" . $invoiceId;
}
$invoideData = $obj_gstr2->get_results($query);
$invoideData = $invoideData[0];
$query1 = "SELECT * FROM " . TAB_PREFIX . 'client_reconcile_purchase_invoice1 ' . " WHERE reference_number ='" . $invoideData->reference_number . "'";
$existsRec = $obj_gstr2->get_results($query1);
if ($existsRec) {
    $dataArr1['status'] = $status;
    $dataConditionArray['reference_number'] = $invoideData->reference_number;
    $obj_gstr2->UPDATE($obj_gstr2->getTableName('client_reconcile_purchase_invoice1'), $dataArr1, $dataConditionArray);
    echo json_encode("success");
} else {

        echo json_encode("fail");

}
?>