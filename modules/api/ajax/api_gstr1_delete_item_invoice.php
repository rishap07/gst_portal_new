<?php
$obj_gst1 = new gstr1();
$type= isset($_POST['type'])?$_POST['type']:'';
$arrValues= isset($_POST['arrValues'])?$_POST['arrValues']:'';
$returnmonth= isset($_POST['returnmonth'])?$_POST['returnmonth']:'';
$data = array();
if(!empty($type) && !empty($arrValues)) {
	if($type == 'AT') {
		$sply_ty= isset($_POST['arrValues'][0])?$_POST['arrValues'][0]:'';
		$pos= isset($_POST['arrValues'][1])?$_POST['arrValues'][1]:'';

		if(!empty($sply_ty) && !empty($pos)) {
			// delete AT invoice
			$data['sply_ty'] = $sply_ty;
			$data['pos'] = $pos;
			$response = $obj_gst1->gstDeleteItemPayload($returnmonth,$type,$data);
			
			
			
		}
	}

	if($type == 'B2CS') {

	}
}

