<?php
$type= isset($_POST['type'])?$_POST['type']:'';
$arrValues= isset($_POST['arrValues'])?$_POST['arrValues']:'';

if(!empty($type) && !empty($arrValues)) {
	if($type == 'AT') {
		$sply_ty= isset($_POST['arrValues'][0])?$_POST['arrValues'][0]:'';
		$pos= isset($_POST['arrValues'][1])?$_POST['arrValues'][1]:'';

		if(!empty($sply_ty) && !empty($pos)) {
			// delete AT invoice

			
		}
	}
}

