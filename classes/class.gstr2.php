<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

final class gstr2 extends validation {
    
    function __construct() {
        parent::__construct();
    }

    public function downloadGSTR2() {

		$gstr2ReturnMonth = isset($_POST['gstr2ReturnMonth']) ? $_POST['gstr2ReturnMonth'] : '';

		if (empty($gstr2ReturnMonth)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }
		$dataRes = $this->generalGSTR2InvoiceList($gstr2ReturnMonth);

        if(!empty($dataRes)) {

            $x=0;
            $dataUpdate= array();
            foreach($dataRes as $dataRe) {
                $dataUpdate[$x]['set']['is_gstr2_downloaded']= '1';
                $dataUpdate[$x]['where']['invoice_id']= $dataRe->invoice_id;
                $x++;
            }

			if($this->updateMultiple($this->getTableName('client_invoice'), $dataUpdate)) {
				
				$this->setSuccess("GSTR2 Data Downloaded") ;
            } else {
                $this->setError("Failed to Download Data");
            }
        } else {
			$this->setError("There is no invoice to Download");
		}
    }
}