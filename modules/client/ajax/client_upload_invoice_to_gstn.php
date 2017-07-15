<?php
print_r($_POST);
// $obj_client = new client();
// $return_id = $_POST['return_id'];    
// $dataResults = $obj_client->getClientReturn($obj_client->sanitize($return_id));
// $dataKyc = $obj_client->getClientKyc();

// $month = $dataResults[0]->return_month;
// $query = "select  SUM(b.cgst_amount) AS totalcgst, SUM(b.igst_amount) AS totaligst, SUM(b.sgst_amount) AS totalsgst, sum(b.taxable_subtotal) as totalsub from ".TAB_PREFIX."client_invoice a inner join ".TAB_PREFIX."client_invoice_item b on a.invoice_id=b.invoice_id where a.invoice_date like'".$month."%' and a.added_by='".$_SESSION['user_detail']['user_id']."'";
//     $flag=0;
//   	$data2 = $obj_client->get_results($query);
//     $dataArr = array();        
// 	if(!empty($data2))
// 	{
// 	  	$dataArr['msg']='suc';
// 	  	$dataArr['data']['totalcgst']= $data2[0]->totalcgst;
// 	  	$dataArr['data']['totalsgst']= $data2[0]->totalsgst;
// 	  	$dataArr['data']['totaligst']= $data2[0]->totaligst;
// 	  	$dataArr['data']['totalsub']= $data2[0]->totalsub;
// 	  	$flag=1;
//     }
//     $query = "select  SUM(invoice_total_value) AS total,count(invoice_total_value) as invoicecount from ".TAB_PREFIX."client_invoice where invoice_date like'".$month."%' and added_by='".$_SESSION['user_detail']['user_id']."'";
//   	$data2 = $obj_client->get_results($query);
//   	if(!empty($data2))
// 	{
// 	  	$dataArr['data']['total']= $data2[0]->total;
// 	  	$dataArr['data']['invoice_count']= $data2[0]->invoicecount;
// 	  	$flag=1;
//     }

//     if($flag==0)
//     {
//     	$dataArr['msg']='err';
//     }
// echo json_encode($dataArr);
?>