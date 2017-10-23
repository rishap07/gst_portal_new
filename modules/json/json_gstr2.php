<?php
$data= new json();

$financialMonth=date('Y-m',time());
$dataInsertArray =$data->addGstr2Data($_SESSION['user_detail']['user_id'],'2017-09',$arr_type=true);
$getGstr2Payload =$data->getGstr2Payload($_SESSION['user_detail']['user_id'],$financialMonth);


//echo json_encode($getGstr2Payload);
$data->insertMultiple($data->getTableName('gstr2_return_summary'), $dataInsertArray) ;



 ?>