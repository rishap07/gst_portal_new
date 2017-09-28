<?php
$data= new json();

$dataArr = $data->getGstr2Payload($_SESSION['user_detail']['user_id'],'2017-09');

echo json_encode($dataArr);

 ?>