<?php
$obj_gst1 = new gstr1();
$obj_api =  new gstr();


$type= isset($_POST['type'])?$_POST['type']:'';
$deleteType= isset($_POST['deleteType'])?$_POST['deleteType']:'';

$arrValues= isset($_POST['arrValues'])?$_POST['arrValues']:'';
$returnmonth= isset($_POST['returnmonth'])?$_POST['returnmonth']:'';
$response = array();
$data = array();
if(!empty($type) && !empty($arrValues)) {
	if($type == 'B2B') {
		$ctin= isset($_POST['arrValues'][0])?$_POST['arrValues'][0]:'';
		$inum= isset($_POST['arrValues'][1])?$_POST['arrValues'][1]:'';
		$idt= isset($_POST['arrValues'][2])?$_POST['arrValues'][2]:'';
		if(!empty($ctin) && !empty($inum) && !empty($idt)) {
			// delete B2B invoice
			$data['ctin'] = $ctin;
			$data['inum'] = $inum;
			$data['idt'] = $idt;
			$response = $obj_gst1->gstDeleteItemPayload($returnmonth,$type,$data);
			
		}
	}
	if($type == 'B2CL') {
		$pos= isset($_POST['arrValues'][0])?$_POST['arrValues'][0]:'';
		$inum= isset($_POST['arrValues'][1])?$_POST['arrValues'][1]:'';
		$idt= isset($_POST['arrValues'][2])?$_POST['arrValues'][2]:'';
		if(!empty($pos) && !empty($inum) && !empty($idt)) {
			// delete B2CL invoice
			$data['pos'] = $pos;
			$data['inum'] = $inum;
			$data['idt'] = $idt;
			$response = $obj_gst1->gstDeleteItemPayload($returnmonth,$type,$data);
			
		}
	}
	if($type == 'CDNR') {
		$ctin= isset($_POST['arrValues'][0])?$_POST['arrValues'][0]:'';
		$inum= isset($_POST['arrValues'][1])?$_POST['arrValues'][1]:'';
		$idt= isset($_POST['arrValues'][2])?$_POST['arrValues'][2]:'';
		$nt_num= isset($_POST['arrValues'][3])?$_POST['arrValues'][3]:'';
		$nt_dt= isset($_POST['arrValues'][4])?$_POST['arrValues'][4]:'';
		if(!empty($ctin) && !empty($inum) && !empty($idt)) {
			// delete CDNR invoice
			$data['ctin'] = $ctin;
			$data['inum'] = $inum;
			$data['idt'] = $idt;
			$data['nt_num'] = $nt_num;
			$data['nt_dt'] = $nt_dt;
			$response = $obj_gst1->gstDeleteItemPayload($returnmonth,$type,$data);
			
		}
	}
	if($type == 'CDNUR') {
		$inum= isset($_POST['arrValues'][0])?$_POST['arrValues'][0]:'';
		$idt= isset($_POST['arrValues'][1])?$_POST['arrValues'][1]:'';
		$nt_num= isset($_POST['arrValues'][2])?$_POST['arrValues'][2]:'';
		$nt_dt= isset($_POST['arrValues'][3])?$_POST['arrValues'][3]:'';
		$typ= isset($_POST['arrValues'][4])?$_POST['arrValues'][4]:'';
		if(!empty($inum)  && !empty($idt)) {
			// delete CDNUR invoice
			$data['inum'] = $inum;
			$data['idt'] = $idt;
			$data['nt_num'] = $nt_num;
			$data['nt_dt'] = $nt_dt;
			$data['typ'] = $typ;
			$response = $obj_gst1->gstDeleteItemPayload($returnmonth,$type,$data);
		}
	}
	if($type == 'EXP') {
		$inum= isset($_POST['arrValues'][0])?$_POST['arrValues'][0]:'';
		$idt= isset($_POST['arrValues'][1])?$_POST['arrValues'][1]:'';
		$exp_typ= isset($_POST['arrValues'][2])?$_POST['arrValues'][2]:'';
		if(!empty($inum)  && !empty($idt)) {
			// delete EXP invoice
			$data['exp_typ'] = $exp_typ;
			$data['inum'] = $inum;
			$data['idt'] = $idt;
			$response = $obj_gst1->gstDeleteItemPayload($returnmonth,$type,$data);
		}
	}
	if($type == 'HSN') {
		$hsn_sc= isset($_POST['arrValues'][0])?$_POST['arrValues'][0]:'';
		$chksum = isset($_POST['arrValues'][1])?$_POST['arrValues'][1]:'';
		$obj_gst1->pr($_POST);
		if(!empty($hsn_sc) ) {
			// delete HSN invoice
			$data['hsn_sc'] = $hsn_sc;
			$data['chksum'] = $chksum;
			$response = $obj_gst1->gstDeleteItemPayload($returnmonth,$type,$data);
		}
	}
}
if(!empty($type) && empty($arrValues) && !empty($deleteType)) {
	//echo "1";
	$data['json'] =  $obj_api->returnSummary($returnmonth,$type);
	$response = $obj_gst1->gstDeleteItemPayload($returnmonth,$type,$data,$deleteType);
}
 // $obj_gst1->pr($response);
 // die;