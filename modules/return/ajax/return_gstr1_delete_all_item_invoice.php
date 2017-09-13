<?php
/*
    * 
    *  Developed By        :   Monika Deswal
    *  Date Created        :   July 27, 2017
    *  Last Modification   :   Summary of JSTR1
    * 
*/
$obj_gst1 = new gstr1();
$obj_api =  new gstr();


$type= isset($_POST['type'])?$_POST['type']:'';
$responseB2CS = $obj_api->returnSummary($returnmonth,'B2CS');
$responseAT = $obj_api->returnSummary($returnmonth,'AT');
$responseTXPD = $obj_api->returnSummary($returnmonth,'TXPD');

$returnmonth= isset($_POST['returnmonth'])?$_POST['returnmonth']:'';
$response = array();
$data = array();
if(!empty($type)) {
	$data['json'] =  $obj_api->returnSummary($returnmonth,$type);
	$response = $obj_gst1->gstDeleteItemPayload($returnmonth,$type,$data,'all');
}
 //$obj_gst1->pr($response);
 //die;