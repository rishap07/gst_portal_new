<?php
/*
    *  Developed By        :   Ishwar Lal Ghiya
    *  Date Created        :   June 02, 2017
    *  Last Modification   :   Convert Number To Words
    * 
*/

$obj_client = new client();
$result = array();
if(isset($_POST['totalInvoiceValue']) && isset($_POST['action']) && $_POST['action'] == "numberToWords") {
	
	$mantissaValue = '';
	$seperator = '';
	$fractionalValue = '';
	$totalInvoiceValue = (string)$_POST['totalInvoiceValue'];
	$invoicevalue = explode(".", $totalInvoiceValue);

	if($obj_client->convert_number_to_words($invoicevalue[0])) {
		
		$mantissaValue = $obj_client->convert_number_to_words($invoicevalue[0]) . " rupees";
		
		if( isset($invoicevalue[1]) && $invoicevalue[1] > 0) {
			$seperator = " and ";
			$fractionalValue = $obj_client->convert_number_to_words($invoicevalue[1]) . " paise";
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