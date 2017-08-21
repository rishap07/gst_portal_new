<?php
$obj_gst1 = new gstr1();
$type= isset($_POST['type'])?$_POST['type']:'';
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
	if($type == 'CDNR') {
		$ctin= isset($_POST['arrValues'][0])?$_POST['arrValues'][0]:'';
		$inum= isset($_POST['arrValues'][1])?$_POST['arrValues'][1]:'';
		$idt= isset($_POST['arrValues'][2])?$_POST['arrValues'][2]:'';
		$nt_num= isset($_POST['arrValues'][3])?$_POST['arrValues'][3]:'';
		$nt_dt= isset($_POST['arrValues'][4])?$_POST['arrValues'][4]:'';
		if(!empty($ctin) && !empty($inum) && !empty($idt)) {
			// delete B2B invoice
			$data['ctin'] = $ctin;
			$data['inum'] = $inum;
			$data['idt'] = $idt;
			$data['nt_num'] = $nt_num;
			$data['nt_dt'] = $nt_dt;
			$response = $obj_gst1->gstDeleteItemPayload($returnmonth,$type,$data);
			
		}
	}

	if($type == 'CDNUR') {

	}

	if($type == 'AT') {
		$sply_ty= isset($_POST['arrValues'][0])?$_POST['arrValues'][0]:'';
		$pos= isset($_POST['arrValues'][1])?$_POST['arrValues'][1]:'';
		$chksum= isset($_POST['arrValues'][2])?$_POST['arrValues'][2]:'';

		if(!empty($sply_ty) && !empty($pos)) {
			// delete AT invoice
			$data['sply_ty'] = $sply_ty;
			$data['pos'] = $pos;
			$data['chksum'] = $chksum;
			$response = $obj_gst1->gstDeleteItemPayload($returnmonth,$type,$data);
			
		}
	}

	if($type == 'B2CS') {
		$sply_ty= isset($_POST['arrValues'][0])?$_POST['arrValues'][0]:'';
		$pos= isset($_POST['arrValues'][1])?$_POST['arrValues'][1]:'';
		$typ= isset($_POST['arrValues'][2])?$_POST['arrValues'][2]:'';
		$rt= isset($_POST['arrValues'][3])?$_POST['arrValues'][3]:'';
		$chksum= isset($_POST['arrValues'][4])?$_POST['arrValues'][4]:'';
		if(!empty($sply_ty) && !empty($pos)) {
			// delete B2CS invoice
			$data['sply_ty'] = $sply_ty;
			$data['pos'] = $pos;
			$data['typ'] = $typ;
			$data['rt'] = $rt;
			$data['chksum'] = $chksum;
			$response = $obj_gst1->gstDeleteItemPayload($returnmonth,$type,$data);
		}
	}
}

