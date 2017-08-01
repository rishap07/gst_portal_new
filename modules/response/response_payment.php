<?php

if($_SESSION['id'] && $_SESSION['subs_id']!='')
{
$obj_pay= new processpayment();
if($_POST){

    $response = $_POST;

    if(is_array($response)){
        $str = $response['msg'];
    }else if(is_string($response) && strstr($response, 'msg=')){
        $outputStr = str_replace('msg=', '', $response);
        $outputArr = explode('&', $outputStr);
        $str = $outputArr[0];
    }else {
        $str = $response;
    }

    $transactionResponseBean = new TransactionResponseBean();

    $transactionResponseBean->setResponsePayload($str);
    $transactionResponseBean->setKey($_SESSION['key']);
    $transactionResponseBean->setIv($_SESSION['iv']);

    $response = $transactionResponseBean->getResponsePayload();
	//$ss = explode('|',$response);
    
    $response =explode('|',$response);
	$res= array();
	for($x=0;$x<count($response);$x++)
	{
		$temp = explode('=',$response[$x]);
		$res[$temp[0]]=$temp[1];
		
	}
	$response_json= json_encode($response);
	if($response)
		{
			//Update paymetn log table 
            $obj_pay->update(TAB_PREFIX . 'payment_log', array('status' => '1', 'response_datetime' => date('Y-m-d H:i:s'),'response_data'=>$response_json), array('process_payment_id' => $res['clnt_txn_ref']));
		}
		
	$originalDate = $res['tpsl_txn_time'];
	$newDate = date("Y-m-d H:i:s", strtotime($originalDate));
	
	
$update = $obj_pay->update('ilbs_onlinepayment', array('response_time'=>date('Y-m-d H:i:s'),'txn_status'=>$res['txn_status'],'txn_msg'=>$res['txn_msg'],'txn_err_msg'=>$res['txn_err_msg'],'clnt_txn_ref'=>$res['clnt_txn_ref'],'tpsl_bank_cd'=>$res['tpsl_bank_cd'],'tpsl_txn_id'=>$res['tpsl_txn_id'],'txn_amt'=>$res['txn_amt'],'clnt_rqst_meta'=>$res['clnt_rqst_meta'],'tpsl_txn_time'=>$newDate,'tpsl_rfnd_id'=>$res['tpsl_rfnd_id'],'bal_amt'=>$res['bal_amt'],'rqst_token'=>$res['rqst_token'],'hash'=>$res['hash']),array('Id'=>$_SESSION['id']));
	
	if($update)
	{
		$msg= 'Success !!';
		$obj_payment= new payment();
		$obj_payment->payment_mail($_SESSION['id']);
	}
	else
	
	{
		$msg= 'Failed !!';
	}
	unset($_SESSION["id"]);
	unset($_SESSION["iv"]);
	unset($_SESSION["key"]);
	$_SESSION['txn_id']= $res['tpsl_txn_id'];
	
	if($res['txn_status']=='0300' && $res['txn_msg']=='success')
	echo "<script>window.location = '".PROJECT_URL."?page=payment_success'</script>";
	else
	echo "<script>window.location = '".PROJECT_URL."?page=payment_error'</script>";	
	
}
	
}
else
{
	echo "<script>window.location = '".PROJECT_URL."'</script>";	
}

?>

