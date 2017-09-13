<?php						 
					 
$obj_transition = new transition();
$obj_gstr2 = new gstr2();
 
//$obj_login->sendMobileMessage
$returnmonth = date('Y-m');

if(isset($_POST['returnmonth']))
{
    $returnmonth = $_POST['returnmonth'];
	$obj_transition->redirect(PROJECT_URL."/?page=transition_gstr2&returnmonth=".$returnmonth);
	exit();
}
$returnmonth= date('Y-m');
if(isset($_REQUEST['returnmonth']) && $_REQUEST['returnmonth'] != '')
{
    $returnmonth= $_REQUEST['returnmonth'];
}
$returnmonth = date('Y-m');
if ($_REQUEST['returnmonth'] != '') {
    $returnmonth = $_REQUEST['returnmonth'];
}
if(isset($_POST['submit']) && $_POST['submit']=='submit') {
 $flag = $obj_transition->checkVerifyUser();
  if($flag=='notverify')
  {
	  
  }
  else{
    if($obj_gstr2->saveGstr1DocumentSummary()){
        //$obj_master->redirect(PROJECT_URL."/?page=master_receiver");
    }
  }
}
if(isset($_POST['finalsubmit']) && $_POST['finalsubmit']=='final submit') {
 
  $flag = $obj_transition->checkVerifyUser();
  if($flag=='notverify')
{
						  
} else{
 				  
    if($obj_transition->finalSaveGstrTransition2()){
        //$obj_master->redirect(PROJECT_URL."/?page=master_receiver");
    }
}
}
if (isset($_GET['action']) && $_GET['action'] == 'printInvoice' && isset($_GET['id'])) {

    
    $htmlResponse = $obj_transition->generategst_transitionForm2Html($_GET['id'],$_GET['returnmonth']);

    if ($htmlResponse === false) {

        $obj_transition->setError("No Transition form found.");
        $obj_transition->redirect(PROJECT_URL . "?page=client_invoice_list");
        exit();
    }

    $obj_mpdf = new mPDF();
    $obj_mpdf->SetHeader('Tax Invoice');
    $obj_mpdf->WriteHTML($htmlResponse);

    
}
if (isset($_GET['action']) && $_GET['action'] == 'emailInvoice' && isset($_GET['id'])) {

    $htmlResponse = $obj_transition->generategst_transitionHtml($_GET['id'],$_GET['returnmonth']);
    
    
   
}
if (isset($_GET['action']) && $_GET['action'] == 'downloadInvoice' && isset($_GET['id'])) {

    $htmlResponse = $obj_transition->generategst_transitionHtml($_GET['id'],$_GET['returnmonth']);
    if ($htmlResponse === false) {

        $obj_transition->setError("No Transition form found.");
        $obj_transition->redirect(PROJECT_URL . "?page=trasition_gstr");
        exit();
    }

    $obj_mpdf = new mPDF();
    $obj_mpdf->SetHeader('GST-Transition');
    $obj_mpdf->WriteHTML($htmlResponse);

  
}


       
	  // $sql = "select  *,count(return_id) as totalinvoice from ".TAB_PREFIX."client_return_gstr3b where added_by='" . $_SESSION['user_detail']['user_id'] . "' and financial_month like '%" . $returnmonth . "%' and is_deleted='0'  order by return_id desc limit 0,1";
       $sql = "select  *,count(id) as totalinvoice from gst_gstr1_document_summary where added_by='" . $_SESSION['user_detail']['user_id'] . "' and financial_month like '%" . $returnmonth . "%' and is_deleted='0'  order by id desc limit 0,1";
       $returndata = $obj_transition->get_results($sql);
	   $sql = "select  *,count(id) as totalinvoice from gst_gstr1_document_summary where added_by='" . $_SESSION['user_detail']['user_id'] . "' and financial_month like '%" . $returnmonth . "%' and is_deleted='0'  order by id desc limit 0,1";
       $returndata1 = $obj_transition->get_results($sql);
	   
	   
		if($returndata1[0]->totalinvoice > 0)
		{
		$arr = $returndata1[0]->gstr1_summary_data;
		$arr1= base64_decode($arr);
		$summary_arr = json_decode($arr1);	
		$table1_srno_from='';
		$table1_srno_to='';
		$table1_totalno='';
		$table1_cancelled='';
		$table1_netissued='';
		$table2_srno_from='';
		$table2_srno_to='';
		$table2_totalno='';
		$table2_cancelled='';
		$table2_netissued='';
		
		foreach($summary_arr as $item)
		{
			
			$table1_srno_from=$item->table1_srno_from;
			$table1_srno_to=$item->table1_srno_to;
			$table1_totalno=$item->table1_totalno;
			$table1_cancelled=$item->table1_cancelled;
			$table1_netissued=$item->table1_netissued;
			$table2_srno_from=$item->table2_srno_from;
			$table2_srno_to=$item->table2_srno_to;
			$table2_totalno=$item->table2_totalno;
			$table2_cancelled=$item->table2_cancelled;
			$table2_netissued=$item->table2_netissued;
						
		}
		}	
	  
	   ?>
      <div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
      <div class="col-md-12 col-sm-12 col-xs-12">
           
      <div class="col-md-6 col-sm-6 col-xs-12 heading"><h1>GST-Document Series Summary</h1></div>
                    <div class="col-md-6 col-sm-6 col-xs-12 text-right breadcrumb-nav"><a href="#">Home</a>
					<i class="fa fa-angle-right" aria-hidden="true"></i>  <a href="#">File Return</a> <i class="fa fa-angle-right" aria-hidden="true"></i> <span class="active">GST-Transition Form</span> </div>
                <div class="whitebg formboxcontainer">
				<?php $obj_transition->showErrorMessage(); ?>
			    <?php if(isset($_POST['finalsubmit']) && $_POST['finalsubmit']=='final submit') {
				echo "<div id='sucmsg' style='background-color:#DBEDDF;border-radius:4px;padding:8px 35px 8px 14px;text-shadow:0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom:18px;border-color:#D1E8DA;color:#39A25F;'><i class='fa fa-check'></i> <b>GST_Transition form month of ".$returnmonth." successfully submitted </div>";
		    
				}else{
				$obj_transition->showSuccessMessge(); }?>
				<?php $obj_transition->unsetMessage(); ?>
				<?php
				if(isset($_POST['submit']) && $_POST['submit']=='submit') {
                  
					  if($flag=='notverify')
					  {
						  
					  } else{
 
					 //echo "<div id='sucmsg' style='background-color:#DBEDDF;border-radius:4px;padding:8px 35px 8px 14px;text-shadow:0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom:18px;border-color:#D1E8DA;color:#39A25F;'><i class='fa fa-check'></i> <b>GSTR3B successfully submitted </div>";
					  }
                 }
				if(isset($_POST['finalsubmit']) && $_POST['finalsubmit']=='final submit') {
                  
					  if($flag=='notverify')
					  {
						  
					  } else{
 
				 //echo "<div id='sucmsg' style='background-color:#DBEDDF;border-radius:4px;padding:8px 35px 8px 14px;text-shadow:0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom:18px;border-color:#D1E8DA;color:#39A25F;'><i class='fa fa-check'></i> <b>GSTR3B month of return ".$returnmonth." successfully submitted </div>";
		      // echo $obj_transition->showSuccessMessge(); 
				} }
				else{
				 if($returndata[0]->final_submit == 1){
		    echo "<div id='sucmsg' style='background-color:#DBEDDF;border-radius:4px;padding:8px 35px 8px 14px;text-shadow:0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom:18px;border-color:#D1E8DA;color:#39A25F;'><i class='fa fa-check'></i> <b>GST-Transition month of  ".$returnmonth." already submitted </div>";
					
				} }?>
				<div class="tab" style="display:none;">
                <a href="<?php echo PROJECT_URL . '/?page=transition_gstr&returnmonth='.$returnmonth ?>">
                    Transition Form1
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=transition_gstr2&returnmonth='.$returnmonth ?>" class="active" >
                    Transition Form2
                </a>
              
            </div>
					  <div class="pull-right rgtdatetxt">
                                <form method='post' name='form2'>
                                    Month Of Return
                                    <?php
                                    $dataQuery = "SELECT DATE_FORMAT(invoice_date,'%Y-%m') AS niceDate FROM " . $db_obj->getTableName('client_invoice') . " group by nicedate";
                                    $dataRes = $obj_transition->get_results($dataQuery);
                                    if (!empty($dataRes)) {
                                        ?>
                                        <select class="dateselectbox" id="returnmonth" name="returnmonth">
                                        <?php
                                        foreach ($dataRes as $dataRe) {
                                            ?>
                                                <option value="<?php echo $dataRe->niceDate; ?>" <?php if ($dataRe->niceDate == $returnmonth) { echo 'selected'; } ?>><?php echo $dataRe->niceDate; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <?php
                                    } else {
                                        ?>
                                        <select class="dateselectbox" id="returnmonth" name="returnmonth">
                                            <option>July 2017</option>
                                        </select>
                                    <?php }
                                    ?>
                                </form>
                            </div>
							<?php
							if($returndata[0]->final_submit == 1)
							{
								?>
							<div class="inovicergttop">
                            <ul class="iconlist">

                                
                               
                                <li><a href="<?php echo PROJECT_URL; ?>/?page=transition_gstr2&action=downloadInvoice&id=<?php echo $returndata[0]->financial_month; ?>&returnmonth=<?php echo $returnmonth; ?>"><div data-toggle="tooltip" data-placement="bottom" title="PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></div></a></li>
                                <li><a href="<?php echo PROJECT_URL; ?>/?page=transition_gstr2&action=printInvoice&id=<?php echo $returndata[0]->financial_month; ?>&returnmonth=<?php echo $returnmonth; ?>" target="_blank"><div data-toggle="tooltip" data-placement="bottom" title="PRINT"><i class="fa fa-print" aria-hidden="true"></i></div></a></li>
                                <li><a href="<?php echo PROJECT_URL; ?>/?page=transition_gstr2&action=emailInvoice&id=<?php echo $returndata[0]->financial_month; ?>&returnmonth=<?php echo $returnmonth; ?>"><div data-toggle="tooltip" data-placement="bottom" title="Email"><i class="fa fa-envelope-o" aria-hidden="true"></i></div></a></li>
                                <!--<li><a href="#"><div data-toggle="tooltip" data-placement="bottom" title="Attached File"><i class="fa fa-paperclip" aria-hidden="true"></i></div></a></li>-->
                         </ul>
							</div><?php } ?>
                       <form method="post" enctype="multipart/form-data" id='form'> 
					  
                    	<div class="greyheading">1.Invoice for outward supply</div>
					       <div class="tableresponsive">
						   <form method="post" enctype="multipart/form-data" id='form'>
                            <table  class="table  tablecontent tablecontent2 bordernone" id='table1a'>
                                <thead>
                                <tr>
                                <th>Sr.No.from</th>
                                <th>Sr.No.To</th>
                                <th>TotalNumber</th>
                                <th>Cancelled</th>
                                <th>Net Issued</th>                             					
                                </tr>
                                </thead>
                                
                                <tbody>
								<?php
								if(!empty($returndata1[0]->totalinvoice) && ($returndata1[0]->totalinvoice > 0))
								{
								$table1_srno_from=(explode(",",$table1_srno_from));
								$table1_srno_to=(explode(",",$table1_srno_to));
								$table1_totalno=(explode(",",$table1_totalno));
								$table1_cancelled=(explode(",",$table1_cancelled));
								$table1_netissued=(explode(",",$table1_netissued));
										
			                    $start='';
								if(sizeof($table1_srno_from) > 1)
								{
									$start = $table1_srno_from;
							    }
								elseif(sizeof($table1_srno_to) > 1)
								{
									 $start = $table1_srno_to;
								}
								elseif(sizeof($table1_totalno) > 1)
								{
									 $start = $table1_totalno;
									
								}
								elseif(sizeof($table1_cancelled) > 1)
								{
									 $start = $table1_cancelled;
									
								}
								elseif(sizeof($table1_netissued) > 1)
								{
									$start = $table1_netissued;
								}			
													
								else{
									$start = $table1_srno_from;
								}
								
									
						     
							  for($i=0;$i < sizeof($start); $i++) {
								 $sno =0;
								 $sno = $i+1;
								 
                           ?>
                                <tr>
                                 <td>
								 <?php
								 if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table1_srno_from[$i])) { echo $table1_srno_from[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table1_srno_from[]"
 class="form-control" value="<?php if(isset($table1_srno_from[$i])) { echo $table1_srno_from[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                 <td>
								 <?php
								 if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table1_srno_to[$i])) { echo $table1_srno_to[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table1_srno_to[]"
 class="form-control" value="<?php if(isset($table1_srno_to[$i])) { echo $table1_srno_to[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table1_totalno[$i])) { echo $table1_totalno[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table1_totalno[]"
 class="form-control" value="<?php if(isset($table1_totalno[$i])) { echo $table1_totalno[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table1_cancelled[$i])) { echo $table1_cancelled[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);"  name="table1_cancelled[]"
 class="form-control" value="<?php if(isset($table1_cancelled[$i])) { echo $table1_cancelled[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table1_netissued[$i])) { echo $table1_netissued[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table1_netissued[]" value="<?php if(isset($table1_netissued[$i])) { echo $table1_netissued[$i]; } else { echo ''; }?>"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>				
								 
								 <?php if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 0))
								 {   
							        if($i==0){
									 ?>
                                    <td>
									 <a class="addMoreInvoice add-table1a"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
								 </td><?php } else { ?>
                               <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
								 <?php } } ?>								 
                                </tr>
								
								<?php if(($sno==sizeof($start)) && ($returndata[0]->final_submit==1))  
								{
									//echo '<tr><td></td><td>Total</td><td>'.$taxreturn.'</td><td>'.$dateoffilling_return.'</td><td>'.$balance_cenvat_credit.'</td><td>'.$cenvat_credit_admissible.'</td></tr>';
									
								}
									}  } else {  ?>

								<tr>
                               <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->total_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table1_srno_from[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->total_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table1_srno_to[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->total_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table1_totalno[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->total_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table1_cancelled[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                 <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->total_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table1_netissued[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								 
                                    <td>
									 <a class="addMoreInvoice add-table1a"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
										</td>                  
                                </tr>
								<?php } ?>                              
							
                                </tbody>
                            </table>							 
                        </div>
						<div class="greyheading">1.Invoice for outward supply</div>
					       <div class="tableresponsive">
						   <form method="post" enctype="multipart/form-data" id='form'>
                            <table  class="table  tablecontent tablecontent2 bordernone" id='table1a'>
                                <thead>
                                <tr>
                                <th>Sr.No.from</th>
                                <th>Sr.No.To</th>
                                <th>TotalNumber</th>
                                <th>Cancelled</th>
                                <th>Net Issued</th>                             					
                                </tr>
                                </thead>
                                
                                <tbody>
								<?php
								if(!empty($returndata1[0]->totalinvoice) && ($returndata1[0]->totalinvoice > 0))
								{
								$table2_srno_from=(explode(",",$table2_srno_from));
								$table2_srno_to=(explode(",",$table2_srno_to));
								$table2_totalno=(explode(",",$table2_totalno));
								$table2_cancelled=(explode(",",$table2_cancelled));
								$table2_netissued=(explode(",",$table2_netissued));
										
			                    $start='';
								if(sizeof($table2_srno_from) > 1)
								{
									$start = $table2_srno_from;
							    }
								elseif(sizeof($table2_srno_to) > 1)
								{
									 $start = $table2_srno_to;
								}
								elseif(sizeof($table2_totalno) > 1)
								{
									 $start = $table2_totalno;
									
								}
								elseif(sizeof($table2_cancelled) > 1)
								{
									 $start = $table2_cancelled;
									
								}
								elseif(sizeof($table2_netissued) > 1)
								{
									$start = $table2_netissued;
								}			
													
								else{
									$start = $table2_srno_from;
								}
								
									
						     
							  for($i=0;$i < sizeof($start); $i++) {
								 $sno =0;
								 $sno = $i+1;
								 
                           ?>
                                <tr>
                                 <td>
								 <?php
								 if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table2_srno_from[$i])) { echo $table2_srno_from[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table2_srno_from[]"
 class="form-control" value="<?php if(isset($table2_srno_from[$i])) { echo $table2_srno_from[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                 <td>
								 <?php
								 if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table2_srno_to[$i])) { echo $table2_srno_to[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table1_srno_to[]"
 class="form-control" value="<?php if(isset($table2_srno_to[$i])) { echo $table2_srno_to[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table2_totalno[$i])) { echo $table2_totalno[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table1_totalno[]"
 class="form-control" value="<?php if(isset($table2_totalno[$i])) { echo $table2_totalno[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table2_cancelled[$i])) { echo $table2_cancelled[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);"  name="table1_cancelled[]"
 class="form-control" value="<?php if(isset($table2_cancelled[$i])) { echo $table2_cancelled[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table2_netissued[$i])) { echo $table2_netissued[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table2_netissued[]" value="<?php if(isset($table2_netissued[$i])) { echo $table2_netissued[$i]; } else { echo ''; }?>"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>				
								 
								 <?php if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 0))
								 {   
							        if($i==0){
									 ?>
                                    <td>
									 <a class="addMoreInvoice add-table1a"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
								 </td><?php } else { ?>
                               <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
								 <?php } } ?>								 
                                </tr>
								
								<?php if(($sno==sizeof($start)) && ($returndata[0]->final_submit==1))  
								{
									//echo '<tr><td></td><td>Total</td><td>'.$taxreturn.'</td><td>'.$dateoffilling_return.'</td><td>'.$balance_cenvat_credit.'</td><td>'.$cenvat_credit_admissible.'</td></tr>';
									
								}
									}  } else {  ?>

							<tr><td colspan="5" align="center">Nothing to found here</td></tr>
								<?php } ?>                              
							
                                </tbody>
                            </table>							 
                        </div>
						
                    	
                        
							
                         <div class="tableresponsive">
                         
							<?php
							 if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
							{
								if($returndata[0]->final_submit == 0)
							{
								?>
                                <div class="adminformbxsubmit" style="width:100%;"> 
                            <div class="tc">
                               <input type="button" value="<?php echo ucfirst('Edit'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=transition_gstr2_submit&returnmonth=".$_REQUEST["returnmonth"]; ?>';" class="btn btn-success" class="redbtn marlef10"/>
							    <input type='submit' class="btn btn-danger" name='cleardata' value='clear data' id='cleardata'>
							  <input type='submit' class="btn btn-success" name='finalsubmit' value='final submit' id='finalsubmit'>
							  <input type='hidden' name="returnid" id="returnid" value="<?php echo $returndata[0]->return_id; ?>" />
									
                               
                            </div>
                              </div>
                            <?php } }
							else
							{
								?>
								
								  <div class="adminformbxsubmit" style="width:100%;"> 
                            <div class="tc">
                                <input type='submit' class="btn btn-success" name='submit' value='submit' id='submit'>
								<?php 
								if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 0))
							{?>
							  <input type='submit' class="btn btn-success" name='finalsubmit' value='final submit' id='finalsubmit'>
						
							<?php } ?>
								
                               
                            </div>
                              </div>
							  <?php 
							} 
							?>		
                          								
                        </div>
                        
                        
                        </div> 
                   
       	  </div>
 		 <div class="clear height40"></div>     
    
    </div>
           <!--CONTENT START HERE-->
		   </form>
        <div class="clear"></div>  	
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-table1a").click(function(){
           
            var markup = "<tr><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='table1_srno_from[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table1_srno_to[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table1_totalno[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table1_cancelled[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table1_netissued[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table1a').append(markup);
        });
		$('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});
     
		 
        
    }); 

</script>
<script>
    $(document).ready(function () {
        $('#returnmonth').on('change', function () {
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=transition_gstr2&returnmonth=<?php echo $returnmonth; ?>';
                        document.form2.submit();
                    });
                });
</script>

  <script type="text/javascript">
        $(document).ready(function() {
           // $('#place_of_supply_unregistered_person').multiselect();
        });
   </script>
    <script type="text/javascript">
        $(document).ready(function() {
          //  $('#place_of_supply_taxable_person').multiselect();
        });
   </script>
    <script type="text/javascript">
        $(document).ready(function() {
          //  $('#place_of_supply_uin_holder').multiselect();
        });
   </script>
  
<script>
    $(document).ready(function () {
        $('#returnmonth').on('change', function () {
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=transition_gstr2&returnmonth=<?php echo $returnmonth; ?>';
                        document.form2.submit();
                    });
                });
</script>

       
 <script>
	$(document).ready(function () {
		
		/* select2 js for state */
		//$("#place_of_supply_unregistered_person").select2();
	
	
	});
</script>
 
        <script type="text/javascript">
        function isNumberKey(evt)
      {
         
        var charCode = (evt.which) ? evt.which : event.keyCode
                
        if ((charCode >= 40) && (charCode <= 57) &&(charCode!=47) &&(charCode!=42) && (charCode!=43) && (charCode!=44) && (charCode!=45) || (charCode == 8))
       {
       return true;
           
       }
    else
    {
     return false;

     }
	  }
</script>