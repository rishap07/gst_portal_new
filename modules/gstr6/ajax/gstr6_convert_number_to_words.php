<?php
/*
    * 
    *  Developed By        :   Ishwar Lal Ghiya
    *  Date Created        :   October 11, 2017
    * 
*/

$gstr6 = new gstr6();
$result = array();
if(isset($_POST['totalInvoiceValue']) && isset($_POST['action']) && $_POST['action'] == "numberToWords") {

	$mantissaValue = '';
	$seperator = '';
	$fractionalValue = '';
	$totalInvoiceValue = (string)$_POST['totalInvoiceValue'];
	$invoicevalue = explode(".", $totalInvoiceValue);

	if($gstr6->convert_number_to_words($invoicevalue[0])) {

		$mantissaValue = $gstr6->convert_number_to_words($invoicevalue[0]) . " rupees";

		if( isset($invoicevalue[1]) && $invoicevalue[1] > 0) {
			$seperator = " and ";
			$fractionalValue = $gstr6->convert_number_to_words($invoicevalue[1]) . " paise";
		}

		$result['status'] = "success";
		$result['invoicevalue'] = ucwords($mantissaValue) . $seperator . ucwords($fractionalValue) . " Only";
	} else {
		$result['status'] = "error";
		$result['invoicevalue'] = '';
	}
}

echo json_encode($result);
die;
?>