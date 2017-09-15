<?php						 
					 
$obj_transition = new transition();
$obj_gstr2 = new gstr2();
 
//$obj_login->sendMobileMessage
$returnmonth = date('Y-m');

if(isset($_POST['returnmonth']))
{
    $returnmonth = $_POST['returnmonth'];
	$obj_transition->redirect(PROJECT_URL."/?page=return_document_summary&returnmonth=".$returnmonth);
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




       
	  // $sql = "select  *,count(return_id) as totalinvoice from ".TAB_PREFIX."client_return_gstr3b where added_by='" . $_SESSION['user_detail']['user_id'] . "' and financial_month like '%" . $returnmonth . "%' and is_deleted='0'  order by return_id desc limit 0,1";
       $sql = "select  *,count(id) as totalinvoice from gst_return_upload_summary where added_by='" . $_SESSION['user_detail']['user_id'] . "' and financial_month like '%" . $returnmonth . "%' and is_deleted='0' and type='gstr1document'  order by id desc limit 0,1";
       $returndata = $obj_transition->get_results($sql);
	   $sql = "select  *,count(id) as totalinvoice from gst_return_upload_summary where added_by='" . $_SESSION['user_detail']['user_id'] . "' and financial_month like '%" . $returnmonth . "%' and is_deleted='0' and type='gstr1document'  order by id desc limit 0,1";
       $returndata1 = $obj_transition->get_results($sql);
	   
	   
		if($returndata1[0]->totalinvoice > 0)
		{
		$arr = $returndata1[0]->return_data;
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
		$table3_srno_from='';
		$table3_srno_to='';
		$table3_totalno='';
		$table3_cancelled='';
		$table3_netissued='';
		$table4_srno_from='';
		$table4_srno_to='';
		$table4_totalno='';
		$table4_cancelled='';
		$table4_netissued='';
		$table5_srno_from='';
		$table5_srno_to='';
		$table5_totalno='';
		$table5_cancelled='';
		$table5_netissued='';
		$table6_srno_from='';
		$table6_srno_to='';
		$table6_totalno='';
		$table6_cancelled='';
		$table6_netissued='';
		$table7_srno_from='';
		$table7_srno_to='';
		$table7_totalno='';
		$table7_cancelled='';
		$table7_netissued='';
		$table8_srno_from='';
		$table8_srno_to='';
		$table8_totalno='';
		$table8_cancelled='';
		$table8_netissued='';
		$table9_srno_from='';
		$table9_srno_to='';
		$table9_totalno='';
		$table9_cancelled='';
		$table9_netissued='';
		$table10_srno_from='';
		$table10_srno_to='';
		$table10_totalno='';
		$table10_cancelled='';
		$table10_netissued='';
		$table11_srno_from='';
		$table11_srno_to='';
		$table11_totalno='';
		$table11_cancelled='';
		$table11_netissued='';
		$table12_srno_from='';
		$table12_srno_to='';
		$table12_totalno='';
		$table12_cancelled='';
		$table12_netissued='';
		
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
			$table3_srno_from=$item->table3_srno_from;
			$table3_srno_to=$item->table3_srno_to;
			$table3_totalno=$item->table3_totalno;
			$table3_cancelled=$item->table3_cancelled;
			$table3_netissued=$item->table3_netissued;
			$table4_srno_from=$item->table4_srno_from;
			$table4_srno_to=$item->table4_srno_to;
			$table4_totalno=$item->table4_totalno;
			$table4_cancelled=$item->table4_cancelled;
			$table4_netissued=$item->table4_netissued;
			$table5_srno_from=$item->table5_srno_from;
			$table5_srno_to=$item->table5_srno_to;
			$table5_totalno=$item->table5_totalno;
			$table5_cancelled=$item->table5_cancelled;
			$table5_netissued=$item->table5_netissued;
			$table6_srno_from=$item->table6_srno_from;
			$table6_srno_to=$item->table6_srno_to;
			$table6_totalno=$item->table6_totalno;
			$table6_cancelled=$item->table6_cancelled;
			$table6_netissued=$item->table6_netissued;
			$table7_srno_from=$item->table7_srno_from;
			$table7_srno_to=$item->table7_srno_to;
			$table7_totalno=$item->table7_totalno;
			$table7_cancelled=$item->table7_cancelled;
			$table7_netissued=$item->table7_netissued;
			$table8_srno_from=$item->table8_srno_from;
			$table8_srno_to=$item->table8_srno_to;
			$table8_totalno=$item->table8_totalno;
			$table8_cancelled=$item->table8_cancelled;
			$table8_netissued=$item->table8_netissued;
			$table9_srno_from=$item->table9_srno_from;
			$table9_srno_to=$item->table9_srno_to;
			$table9_totalno=$item->table9_totalno;
			$table9_cancelled=$item->table9_cancelled;
			$table9_netissued=$item->table9_netissued;
			$table10_srno_from=$item->table10_srno_from;
			$table10_srno_to=$item->table10_srno_to;
			$table10_totalno=$item->table10_totalno;
			$table10_cancelled=$item->table10_cancelled;
			$table10_netissued=$item->table10_netissued;
			$table11_srno_from=$item->table11_srno_from;
			$table11_srno_to=$item->table11_srno_to;
			$table11_totalno=$item->table11_totalno;
			$table11_cancelled=$item->table11_cancelled;
			$table11_netissued=$item->table11_netissued;
			$table12_srno_from=$item->table12_srno_from;
			$table12_srno_to=$item->table12_srno_to;
			$table12_totalno=$item->table12_totalno;
			$table12_cancelled=$item->table12_cancelled;
			$table12_netissued=$item->table12_netissued;
						
		}
		}	
	  
	   ?>
      <div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
      <div class="col-md-12 col-sm-12 col-xs-12">
        
      <div class="col-md-6 col-sm-6 col-xs-12 heading"><h1>GSTR1-Document Summary</h1></div>
                    <div class="col-md-6 col-sm-6 col-xs-12 text-right breadcrumb-nav"><a href="#">Home</a>
					<i class="fa fa-angle-right" aria-hidden="true"></i>  <a href="#">File Return</a> <i class="fa fa-angle-right" aria-hidden="true"></i> <span class="active">GST-Transition Form</span> </div>
                <div class="whitebg formboxcontainer">
				<?php $obj_transition->showErrorMessage(); ?>
				<?php $obj_transition->showSuccessMessge(); ?>
				<?php $obj_transition->unsetMessage(); ?>
			   <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=return_summary&returnmonth=".$_REQUEST["returnmonth"]; ?>';" class="btn btn-danger" class="redbtn marlef10"/>
			
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
                               
                                <li><a href="<?php echo PROJECT_URL; ?>/?page=return_document_summary&action=downloadInvoice&id=<?php echo $returndata[0]->financial_month; ?>&returnmonth=<?php echo $returnmonth; ?>"><div data-toggle="tooltip" data-placement="bottom" title="PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></div></a></li>
                                <li><a href="<?php echo PROJECT_URL; ?>/?page=return_document_summary&action=printInvoice&id=<?php echo $returndata[0]->financial_month; ?>&returnmonth=<?php echo $returnmonth; ?>" target="_blank"><div data-toggle="tooltip" data-placement="bottom" title="PRINT"><i class="fa fa-print" aria-hidden="true"></i></div></a></li>
                                <li><a href="<?php echo PROJECT_URL; ?>/?page=return_document_summary&action=emailInvoice&id=<?php echo $returndata[0]->financial_month; ?>&returnmonth=<?php echo $returnmonth; ?>"><div data-toggle="tooltip" data-placement="bottom" title="Email"><i class="fa fa-envelope-o" aria-hidden="true"></i></div></a></li>
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
										</td>    
						<?php } else { ?>
                               <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
							  <?php } }  ?>								 
                                </tr>
								
								
							<?php  }  } else {  ?>

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
				   <div class="greyheading">2. Invoice for inward supply from unregistered person</div>
					       <div class="tableresponsive">
						   <form method="post" enctype="multipart/form-data" id='form'>
                            <table  class="table  tablecontent tablecontent2 bordernone" id='table2a'>
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
                                 <td> <?php
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table2_srno_to[]"
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
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table2_totalno[]"
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
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);"  name="table2_cancelled[]"
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
									 <a class="addMoreInvoice add-table2a"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
										</td>   
						<?php } else { ?>
                               <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
							  <?php } }  ?>								 
                                </tr>
								
								
							 <?php  }  } else {  ?>

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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table2_srno_from[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table2_srno_to[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table2_totalno[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table2_cancelled[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table2_netissued[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 							 
                                                                         <td>
									 <a class="addMoreInvoice add-table2a"  href="javascript:void(0)">
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
					 <div class="greyheading">3. Revised Invoice</div>
					       <div class="tableresponsive">
						   <form method="post" enctype="multipart/form-data" id='form'>
                            <table  class="table  tablecontent tablecontent2 bordernone" id='table3a'>
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
								$table3_srno_from=(explode(",",$table3_srno_from));
								$table3_srno_to=(explode(",",$table3_srno_to));
								$table3_totalno=(explode(",",$table3_totalno));
								$table3_cancelled=(explode(",",$table3_cancelled));
								$table3_netissued=(explode(",",$table3_netissued));
										
			                    $start='';
								if(sizeof($table3_srno_from) > 1)
								{
									$start = $table3_srno_from;
							    }
								elseif(sizeof($table3_srno_to) > 1)
								{
									 $start = $table3_srno_to;
								}
								elseif(sizeof($table3_totalno) > 1)
								{
									 $start = $table3_totalno;
									
								}
								elseif(sizeof($table3_cancelled) > 1)
								{
									 $start = $table3_cancelled;
									
								}
								elseif(sizeof($table3_netissued) > 1)
								{
									$start = $table3_netissued;
								}			
													
								else{
									$start = $table3_srno_from;
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
									 <label><?php if(isset($table3_srno_from[$i])) { echo $table3_srno_from[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table3_srno_from[]"
 class="form-control" value="<?php if(isset($table3_srno_from[$i])) { echo $table3_srno_from[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                 <td>
								 <?php
								 if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table3_srno_to[$i])) { echo $table3_srno_to[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table3_srno_to[]"
 class="form-control" value="<?php if(isset($table3_srno_to[$i])) { echo $table3_srno_to[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table3_totalno[$i])) { echo $table3_totalno[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table3_totalno[]"
 class="form-control" value="<?php if(isset($table3_totalno[$i])) { echo $table3_totalno[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table3_cancelled[$i])) { echo $table3_cancelled[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);"  name="table3_cancelled[]"
 class="form-control" value="<?php if(isset($table3_cancelled[$i])) { echo $table3_cancelled[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table3_netissued[$i])) { echo $table3_netissued[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table3_netissued[]" value="<?php if(isset($table3_netissued[$i])) { echo $table3_netissued[$i]; } else { echo ''; }?>"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>				
								 
								 <?php if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 0))
								 {   
							        if($i==0){
									 ?>
                                     <td>
									 <a class="addMoreInvoice add-table3a"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
										</td>   
						<?php } else { ?>
                               <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
							  <?php } }  ?>								 
                                </tr>								
								
							<?php }  } else {  ?>

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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table3_srno_from[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table3_srno_to[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table3_totalno[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table3_cancelled[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table3_netissued[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								 
                                                                         <td>
									 <a class="addMoreInvoice add-table3a"  href="javascript:void(0)">
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
						 <div class="greyheading">4.Debit Note</div>
					       <div class="tableresponsive">
						   <form method="post" enctype="multipart/form-data" id='form'>
                            <table  class="table  tablecontent tablecontent2 bordernone" id='table4a'>
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
								$table4_srno_from=(explode(",",$table4_srno_from));
								$table4_srno_to=(explode(",",$table4_srno_to));
								$table4_totalno=(explode(",",$table4_totalno));
								$table4_cancelled=(explode(",",$table4_cancelled));
								$table4_netissued=(explode(",",$table4_netissued));
										
			                    $start='';
								if(sizeof($table4_srno_from) > 1)
								{
									$start = $table4_srno_from;
							    }
								elseif(sizeof($table4_srno_to) > 1)
								{
									 $start = $table4_srno_to;
								}
								elseif(sizeof($table4_totalno) > 1)
								{
									 $start = $table4_totalno;
									
								}
								elseif(sizeof($table4_cancelled) > 1)
								{
									 $start = $table4_cancelled;
									
								}
								elseif(sizeof($table4_netissued) > 1)
								{
									$start = $table4_netissued;
								}			
													
								else{
									$start = $table4_srno_from;
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
									 <label><?php if(isset($table4_srno_from[$i])) { echo $table4_srno_from[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table4_srno_from[]"
 class="form-control" value="<?php if(isset($table4_srno_from[$i])) { echo $table4_srno_from[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                 <td>
								 <?php
								 if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table4_srno_to[$i])) { echo $table4_srno_to[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table4_srno_to[]"
 class="form-control" value="<?php if(isset($table4_srno_to[$i])) { echo $table4_srno_to[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table4_totalno[$i])) { echo $table4_totalno[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table4_totalno[]"
 class="form-control" value="<?php if(isset($table4_totalno[$i])) { echo $table4_totalno[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table4_cancelled[$i])) { echo $table4_cancelled[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);"  name="table4_cancelled[]"
 class="form-control" value="<?php if(isset($table4_cancelled[$i])) { echo $table4_cancelled[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table4_netissued[$i])) { echo $table4_netissued[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table4_netissued[]" value="<?php if(isset($table4_netissued[$i])) { echo $table4_netissued[$i]; } else { echo ''; }?>"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>				
								 
								 <?php if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 0))
								 {   
							        if($i==0){
									 ?>
                         <td>
									 <a class="addMoreInvoice add-table4a"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
										</td>     
						<?php } else { ?>
                               <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
							  <?php } }  ?>								 
                                </tr>
								
								
							<?php  }  } else {  ?>

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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table4_srno_from[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table4_srno_to[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table4_totalno[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table4_cancelled[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table4_netissued[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								 
                                                                         <td>
									 <a class="addMoreInvoice add-table4a"  href="javascript:void(0)">
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
					 <div class="greyheading">5. Credit Note</div>
					       <div class="tableresponsive">
						   <form method="post" enctype="multipart/form-data" id='form'>
                            <table  class="table  tablecontent tablecontent2 bordernone" id='table5a'>
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
								$table5_srno_from=(explode(",",$table5_srno_from));
								$table5_srno_to=(explode(",",$table5_srno_to));
								$table5_totalno=(explode(",",$table5_totalno));
								$table5_cancelled=(explode(",",$table5_cancelled));
								$table5_netissued=(explode(",",$table5_netissued));
										
			                    $start='';
								if(sizeof($table5_srno_from) > 1)
								{
									$start = $table5_srno_from;
							    }
								elseif(sizeof($table5_srno_to) > 1)
								{
									 $start = $table5_srno_to;
								}
								elseif(sizeof($table5_totalno) > 1)
								{
									 $start = $table5_totalno;
									
								}
								elseif(sizeof($table5_cancelled) > 1)
								{
									 $start = $table5_cancelled;
									
								}
								elseif(sizeof($table5_netissued) > 1)
								{
									$start = $table5_netissued;
								}			
													
								else{
									$start = $table5_srno_from;
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
									 <label><?php if(isset($table5_srno_from[$i])) { echo $table5_srno_from[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table5_srno_from[]"
 class="form-control" value="<?php if(isset($table5_srno_from[$i])) { echo $table5_srno_from[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                 <td>
								 <?php
								 if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table5_srno_to[$i])) { echo $table5_srno_to[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table5_srno_to[]"
 class="form-control" value="<?php if(isset($table5_srno_to[$i])) { echo $table5_srno_to[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table5_totalno[$i])) { echo $table5_totalno[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table5_totalno[]"
 class="form-control" value="<?php if(isset($table5_totalno[$i])) { echo $table5_totalno[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table5_cancelled[$i])) { echo $table5_cancelled[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);"  name="table5_cancelled[]"
 class="form-control" value="<?php if(isset($table5_cancelled[$i])) { echo $table5_cancelled[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table5_netissued[$i])) { echo $table5_netissued[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table5_netissued[]" value="<?php if(isset($table5_netissued[$i])) { echo $table5_netissued[$i]; } else { echo ''; }?>"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>				
								 
								 <?php if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 0))
								 {   
							        if($i==0){
									 ?>
                                 <td>
									 <a class="addMoreInvoice add-table5a"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
										</td>  
						<?php } else { ?>
                               <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
							  <?php } }  ?>								 
                                </tr>
								
								
						   <?php }  } else {  ?>

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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table5_srno_from[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table5_srno_to[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table5_totalno[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table5_cancelled[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table5_netissued[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								 
                                                                         <td>
									 <a class="addMoreInvoice add-table5a"  href="javascript:void(0)">
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
               <div class="greyheading">6. Receipt voucher</div>
					       <div class="tableresponsive">
						   <form method="post" enctype="multipart/form-data" id='form'>
                            <table  class="table  tablecontent tablecontent2 bordernone" id='table6a'>
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
								$table6_srno_from=(explode(",",$table6_srno_from));
								$table6_srno_to=(explode(",",$table6_srno_to));
								$table6_totalno=(explode(",",$table6_totalno));
								$table6_cancelled=(explode(",",$table6_cancelled));
								$table6_netissued=(explode(",",$table6_netissued));
										
			                    $start='';
								if(sizeof($table6_srno_from) > 1)
								{
									$start = $table6_srno_from;
							    }
								elseif(sizeof($table6_srno_to) > 1)
								{
									 $start = $table6_srno_to;
								}
								elseif(sizeof($table6_totalno) > 1)
								{
									 $start = $table6_totalno;
									
								}
								elseif(sizeof($table6_cancelled) > 1)
								{
									 $start = $table6_cancelled;
									
								}
								elseif(sizeof($table6_netissued) > 1)
								{
									$start = $table6_netissued;
								}			
													
								else{
									$start = $table6_srno_from;
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
									 <label><?php if(isset($table6_srno_from[$i])) { echo $table6_srno_from[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table6_srno_from[]"
 class="form-control" value="<?php if(isset($table6_srno_from[$i])) { echo $table6_srno_from[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                 <td>
								 <?php
								 if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table6_srno_to[$i])) { echo $table6_srno_to[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table6_srno_to[]"
 class="form-control" value="<?php if(isset($table6_srno_to[$i])) { echo $table6_srno_to[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table6_totalno[$i])) { echo $table6_totalno[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table6_totalno[]"
 class="form-control" value="<?php if(isset($table6_totalno[$i])) { echo $table6_totalno[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table6_cancelled[$i])) { echo $table6_cancelled[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);"  name="table6_cancelled[]"
 class="form-control" value="<?php if(isset($table6_cancelled[$i])) { echo $table6_cancelled[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table6_netissued[$i])) { echo $table6_netissued[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table6_netissued[]" value="<?php if(isset($table6_netissued[$i])) { echo $table6_netissued[$i]; } else { echo ''; }?>"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>				
								 
								 <?php if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 0))
								 {   
							        if($i==0){
									 ?>
                        <td>
									 <a class="addMoreInvoice add-table6a"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
								</td>      								 
						<?php } else { ?>
                               <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
							  <?php } }  ?>								 
                                </tr>								
								
							<?php }  } else {  ?>

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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table6_srno_from[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table6_srno_to[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table6_totalno[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table6_cancelled[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table6_netissued[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								 
                                                                         <td>
									 <a class="addMoreInvoice add-table6a"  href="javascript:void(0)">
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
				<div class="greyheading">7. Payment Voucher</div>
					       <div class="tableresponsive">
						   <form method="post" enctype="multipart/form-data" id='form'>
                            <table  class="table  tablecontent tablecontent2 bordernone" id='table7a'>
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
								$table7_srno_from=(explode(",",$table7_srno_from));
								$table7_srno_to=(explode(",",$table7_srno_to));
								$table7_totalno=(explode(",",$table7_totalno));
								$table7_cancelled=(explode(",",$table7_cancelled));
								$table7_netissued=(explode(",",$table7_netissued));
										
			                    $start='';
								if(sizeof($table7_srno_from) > 1)
								{
									$start = $table7_srno_from;
							    }
								elseif(sizeof($table7_srno_to) > 1)
								{
									 $start = $table7_srno_to;
								}
								elseif(sizeof($table7_totalno) > 1)
								{
									 $start = $table7_totalno;
									
								}
								elseif(sizeof($table7_cancelled) > 1)
								{
									 $start = $table7_cancelled;
									
								}
								elseif(sizeof($table7_netissued) > 1)
								{
									$start = $table7_netissued;
								}			
													
								else{
									$start = $table7_srno_from;
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
									 <label><?php if(isset($table7_srno_from[$i])) { echo $table7_srno_from[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table7_srno_from[]"
 class="form-control" value="<?php if(isset($table7_srno_from[$i])) { echo $table7_srno_from[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                 <td>
								 <?php
								 if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table7_srno_to[$i])) { echo $table7_srno_to[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table7_srno_to[]"
 class="form-control" value="<?php if(isset($table7_srno_to[$i])) { echo $table7_srno_to[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table7_totalno[$i])) { echo $table7_totalno[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table7_totalno[]"
 class="form-control" value="<?php if(isset($table7_totalno[$i])) { echo $table7_totalno[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table7_cancelled[$i])) { echo $table7_cancelled[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);"  name="table7_cancelled[]"
 class="form-control" value="<?php if(isset($table7_cancelled[$i])) { echo $table7_cancelled[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table7_netissued[$i])) { echo $table7_netissued[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table7_netissued[]" value="<?php if(isset($table7_netissued[$i])) { echo $table7_netissued[$i]; } else { echo ''; }?>"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>				
								 
								 <?php if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 0))
								 {   
							        if($i==0){
									 ?>
                          <td>
									 <a class="addMoreInvoice add-table7a"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
								</td>       
						<?php } else { ?>
                               <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
							  <?php } }  ?>								 
                                </tr>							
								
							 <?php }  } else {  ?>

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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table7_srno_from[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table7_srno_to[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table7_totalno[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table7_cancelled[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table7_netissued[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								 
                                                                         <td>
									 <a class="addMoreInvoice add-table7a"  href="javascript:void(0)">
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
						<div class="greyheading">8. Refund voucher</div>
					       <div class="tableresponsive">
						   <form method="post" enctype="multipart/form-data" id='form'>
                            <table  class="table  tablecontent tablecontent2 bordernone" id='table8a'>
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
								$table8_srno_from=(explode(",",$table8_srno_from));
								$table8_srno_to=(explode(",",$table8_srno_to));
								$table8_totalno=(explode(",",$table8_totalno));
								$table8_cancelled=(explode(",",$table8_cancelled));
								$table8_netissued=(explode(",",$table8_netissued));
										
			                    $start='';
								if(sizeof($table8_srno_from) > 1)
								{
									$start = $table8_srno_from;
							    }
								elseif(sizeof($table8_srno_to) > 1)
								{
									 $start = $table8_srno_to;
								}
								elseif(sizeof($table8_totalno) > 1)
								{
									 $start = $table8_totalno;
									
								}
								elseif(sizeof($table8_cancelled) > 1)
								{
									 $start = $table8_cancelled;
									
								}
								elseif(sizeof($table8_netissued) > 1)
								{
									$start = $table8_netissued;
								}			
													
								else{
									$start = $table8_srno_from;
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
									 <label><?php if(isset($table8_srno_from[$i])) { echo $table8_srno_from[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table8_srno_from[]"
 class="form-control" value="<?php if(isset($table8_srno_from[$i])) { echo $table8_srno_from[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                 <td>
								 <?php
								 if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table8_srno_to[$i])) { echo $table8_srno_to[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table8_srno_to[]"
 class="form-control" value="<?php if(isset($table8_srno_to[$i])) { echo $table8_srno_to[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table8_totalno[$i])) { echo $table8_totalno[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table8_totalno[]"
 class="form-control" value="<?php if(isset($table8_totalno[$i])) { echo $table8_totalno[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table8_cancelled[$i])) { echo $table8_cancelled[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);"  name="table8_cancelled[]"
 class="form-control" value="<?php if(isset($table8_cancelled[$i])) { echo $table8_cancelled[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table8_netissued[$i])) { echo $table8_netissued[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table8_netissued[]" value="<?php if(isset($table8_netissued[$i])) { echo $table8_netissued[$i]; } else { echo ''; }?>"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>				
								 
								 <?php if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 0))
								 {   
							        if($i==0){
									 ?>
                       <td>
									 <a class="addMoreInvoice add-table8a"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
								</td>     
						<?php } else { ?>
                               <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
							  <?php } }  ?>								 
                                </tr>
								
								
							 <?php }   } else {  ?>
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table8_srno_from[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table8_srno_to[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table8_totalno[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table8_cancelled[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table8_netissued[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								 
                                                                         <td>
									 <a class="addMoreInvoice add-table8a"  href="javascript:void(0)">
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
						<div class="greyheading">9. Delivery Challan for job work</div>
					       <div class="tableresponsive">
						   <form method="post" enctype="multipart/form-data" id='form'>
                            <table  class="table  tablecontent tablecontent2 bordernone" id='table9a'>
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
								$table9_srno_from=(explode(",",$table9_srno_from));
								$table9_srno_to=(explode(",",$table9_srno_to));
								$table9_totalno=(explode(",",$table9_totalno));
								$table9_cancelled=(explode(",",$table9_cancelled));
								$table9_netissued=(explode(",",$table9_netissued));
										
			                    $start='';
								if(sizeof($table9_srno_from) > 1)
								{
									$start = $table9_srno_from;
							    }
								elseif(sizeof($table9_srno_to) > 1)
								{
									 $start = $table9_srno_to;
								}
								elseif(sizeof($table9_totalno) > 1)
								{
									 $start = $table9_totalno;
									
								}
								elseif(sizeof($table9_cancelled) > 1)
								{
									 $start = $table9_cancelled;
									
								}
								elseif(sizeof($table9_netissued) > 1)
								{
									$start = $table9_netissued;
								}			
													
								else{
									$start = $table9_srno_from;
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
									 <label><?php if(isset($table9_srno_from[$i])) { echo $table9_srno_from[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table9_srno_from[]"
 class="form-control" value="<?php if(isset($table9_srno_from[$i])) { echo $table9_srno_from[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                 <td>
								 <?php
								 if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table9_srno_to[$i])) { echo $table9_srno_to[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table9_srno_to[]"
 class="form-control" value="<?php if(isset($table9_srno_to[$i])) { echo $table9_srno_to[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table9_totalno[$i])) { echo $table9_totalno[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table9_totalno[]"
 class="form-control" value="<?php if(isset($table9_totalno[$i])) { echo $table9_totalno[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table9_cancelled[$i])) { echo $table9_cancelled[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);"  name="table9_cancelled[]"
 class="form-control" value="<?php if(isset($table9_cancelled[$i])) { echo $table9_cancelled[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table9_netissued[$i])) { echo $table9_netissued[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table9_netissued[]" value="<?php if(isset($table9_netissued[$i])) { echo $table9_netissued[$i]; } else { echo ''; }?>"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>				
								 
								 <?php if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 0))
								 {   
							        if($i==0){
									 ?>
                                 <td>
									 <a class="addMoreInvoice add-table9a"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
								</td>       
						<?php } else { ?>
                               <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
							  <?php } }  ?>								 
                                </tr>
								
								
						 <?php } } else {  ?>

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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table9_srno_from[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table9_srno_to[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table9_totalno[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table9_cancelled[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table9_netissued[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								 
                                                                         <td>
									 <a class="addMoreInvoice add-table9a"  href="javascript:void(0)">
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
						<div class="greyheading">10. Delivery Challan for supply on approval</div>
					       <div class="tableresponsive">
						   <form method="post" enctype="multipart/form-data" id='form'>
                            <table  class="table  tablecontent tablecontent2 bordernone" id='table10a'>
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
								$table10_srno_from=(explode(",",$table10_srno_from));
								$table10_srno_to=(explode(",",$table10_srno_to));
								$table10_totalno=(explode(",",$table10_totalno));
								$table10_cancelled=(explode(",",$table10_cancelled));
								$table10_netissued=(explode(",",$table10_netissued));
										
			                    $start='';
								if(sizeof($table10_srno_from) > 1)
								{
									$start = $table10_srno_from;
							    }
								elseif(sizeof($table10_srno_to) > 1)
								{
									 $start = $table10_srno_to;
								}
								elseif(sizeof($table10_totalno) > 1)
								{
									 $start = $table10_totalno;
									
								}
								elseif(sizeof($table10_cancelled) > 1)
								{
									 $start = $table10_cancelled;
									
								}
								elseif(sizeof($table10_netissued) > 1)
								{
									$start = $table10_netissued;
								}			
													
								else{
									$start = $table10_srno_from;
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
									 <label><?php if(isset($table10_srno_from[$i])) { echo $table10_srno_from[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table10_srno_from[]"
 class="form-control" value="<?php if(isset($table10_srno_from[$i])) { echo $table10_srno_from[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                 <td>
								 <?php
								 if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table10_srno_to[$i])) { echo $table10_srno_to[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table10_srno_to[]"
 class="form-control" value="<?php if(isset($table10_srno_to[$i])) { echo $table10_srno_to[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table10_totalno[$i])) { echo $table10_totalno[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table10_totalno[]"
 class="form-control" value="<?php if(isset($table10_totalno[$i])) { echo $table10_totalno[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table10_cancelled[$i])) { echo $table10_cancelled[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);"  name="table10_cancelled[]"
 class="form-control" value="<?php if(isset($table10_cancelled[$i])) { echo $table10_cancelled[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table10_netissued[$i])) { echo $table10_netissued[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table10_netissued[]" value="<?php if(isset($table10_netissued[$i])) { echo $table10_netissued[$i]; } else { echo ''; }?>"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>				
								 
								 <?php if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 0))
								 {   
							        if($i==0){
									 ?>
                    <td>
									 <a class="addMoreInvoice add-table10a"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
								</td>     
						<?php } else { ?>
                               <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
							  <?php } }  ?>								 
                                </tr>
								
								
							<?php } }  else {  ?>

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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table10_srno_from[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table10_srno_to[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table10_totalno[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table10_cancelled[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table10_netissued[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								 
                                                                         <td>
									 <a class="addMoreInvoice add-table10a"  href="javascript:void(0)">
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
						<div class="greyheading">11. Delivery Challan in case of liquid gas</div>
					       <div class="tableresponsive">
						   <form method="post" enctype="multipart/form-data" id='form'>
                            <table  class="table  tablecontent tablecontent2 bordernone" id='table11a'>
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
								$table11_srno_from=(explode(",",$table11_srno_from));
								$table11_srno_to=(explode(",",$table11_srno_to));
								$table11_totalno=(explode(",",$table11_totalno));
								$table11_cancelled=(explode(",",$table11_cancelled));
								$table11_netissued=(explode(",",$table11_netissued));
										
			                    $start='';
								if(sizeof($table11_srno_from) > 1)
								{
									$start = $table11_srno_from;
							    }
								elseif(sizeof($table11_srno_to) > 1)
								{
									 $start = $table11_srno_to;
								}
								elseif(sizeof($table11_totalno) > 1)
								{
									 $start = $table11_totalno;
									
								}
								elseif(sizeof($table11_cancelled) > 1)
								{
									 $start = $table11_cancelled;
									
								}
								elseif(sizeof($table11_netissued) > 1)
								{
									$start = $table11_netissued;
								}			
													
								else{
									$start = $table11_srno_from;
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
									 <label><?php if(isset($table11_srno_from[$i])) { echo $table11_srno_from[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table11_srno_from[]"
 class="form-control" value="<?php if(isset($table11_srno_from[$i])) { echo $table11_srno_from[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                 <td>
								 <?php
								 if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table11_srno_to[$i])) { echo $table11_srno_to[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table11_srno_to[]"
 class="form-control" value="<?php if(isset($table11_srno_to[$i])) { echo $table11_srno_to[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table11_totalno[$i])) { echo $table11_totalno[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table11_totalno[]"
 class="form-control" value="<?php if(isset($table11_totalno[$i])) { echo $table11_totalno[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table11_cancelled[$i])) { echo $table11_cancelled[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);"  name="table11_cancelled[]"
 class="form-control" value="<?php if(isset($table11_cancelled[$i])) { echo $table11_cancelled[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table11_netissued[$i])) { echo $table11_netissued[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table11_netissued[]" value="<?php if(isset($table11_netissued[$i])) { echo $table11_netissued[$i]; } else { echo ''; }?>"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>				
								 
								 <?php if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 0))
								 {   
							        if($i==0){
									 ?>
                           <td>
									 <a class="addMoreInvoice add-table11a"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
								</td>      
						<?php } else { ?>
                               <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
							  <?php } }  ?>								 
                                </tr>
								
								
							<?php } }  else {  ?>

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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table11_srno_from[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table11_srno_to[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table11_totalno[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table11_cancelled[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table11_netissued[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								 
                                                                         <td>
									 <a class="addMoreInvoice add-table11a"  href="javascript:void(0)">
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
						<div class="greyheading">12. Delivery Challan in cases other than by way of supply (excluding at S no. 9 to 11)</div>
					       <div class="tableresponsive">
						   <form method="post" enctype="multipart/form-data" id='form'>
                            <table  class="table  tablecontent tablecontent2 bordernone" id='table12a'>
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
								$table12_srno_from=(explode(",",$table12_srno_from));
								$table12_srno_to=(explode(",",$table12_srno_to));
								$table12_totalno=(explode(",",$table12_totalno));
								$table12_cancelled=(explode(",",$table12_cancelled));
								$table12_netissued=(explode(",",$table12_netissued));
										
			                    $start='';
								if(sizeof($table12_srno_from) > 1)
								{
									$start = $table12_srno_from;
							    }
								elseif(sizeof($table12_srno_to) > 1)
								{
									 $start = $table12_srno_to;
								}
								elseif(sizeof($table12_totalno) > 1)
								{
									 $start = $table12_totalno;
									
								}
								elseif(sizeof($table12_cancelled) > 1)
								{
									 $start = $table12_cancelled;
									
								}
								elseif(sizeof($table12_netissued) > 1)
								{
									$start = $table12_netissued;
								}			
													
								else{
									$start = $table12_srno_from;
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
									 <label><?php if(isset($table12_srno_from[$i])) { echo $table12_srno_from[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table12_srno_from[]"
 class="form-control" value="<?php if(isset($table12_srno_from[$i])) { echo $table12_srno_from[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                 <td>
								 <?php
								 if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table12_srno_to[$i])) { echo $table12_srno_to[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table12_srno_to[]"
 class="form-control" value="<?php if(isset($table12_srno_to[$i])) { echo $table12_srno_to[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table12_totalno[$i])) { echo $table12_totalno[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table12_totalno[]"
 class="form-control" value="<?php if(isset($table12_totalno[$i])) { echo $table12_totalno[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table12_cancelled[$i])) { echo $table12_cancelled[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);"  name="table12_cancelled[]"
 class="form-control" value="<?php if(isset($table12_cancelled[$i])) { echo $table12_cancelled[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table12_netissued[$i])) { echo $table12_netissued[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table12_netissued[]" value="<?php if(isset($table12_netissued[$i])) { echo $table12_netissued[$i]; } else { echo ''; }?>"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>				
								 
								 <?php if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 0))
								 {   
							        if($i==0){
									 ?>
                                  <td>
									 <a class="addMoreInvoice add-table12a"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
								</td>         
						<?php } else { ?>
                               <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
							  <?php } }  ?>								 
                                </tr>
								
								
							<?php } } else {  ?>

								<tr id="trtable12"><td colspan="5" align="center">Nothing found here</td></tr>
								<tr>
                               <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table12_srno_from[]" class="form-control"  placeholder="" /> </td> 
							                         
							   <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table12_srno_to[]" class="form-control"  placeholder="" /> </td>
								 <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->total_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table12_totalno[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table12_cancelled[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                 <td> <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table12_netissued[]" class="form-control"  placeholder="" /></td> 
  						 
                                <td>
									 <a class="addMoreInvoice add-table12a"  href="javascript:void(0)">
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
                    	
                        
							
                         <div class="tableresponsive">
                         
							<?php
							 if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
							{
								if($returndata[0]->final_submit == 0)
							{
								?>
                                <div class="adminformbxsubmit" style="width:100%;"> 
                            <div class="tc">
                               <input type="button" value="<?php echo ucfirst('Edit'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=return_document_summary_submit&returnmonth=".$_REQUEST["returnmonth"]; ?>';" class="btn btn-success" class="redbtn marlef10"/>
							    <input type='submit' class="btn btn-danger" name='cleardata' value='clear data' id='cleardata'>
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
          var element = document.getElementById('trtable1');
			if (element != null && element.value == '') {
		document.getElementById('trtable1').style.display = 'none';
			}
            var markup = "<tr><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='table1_srno_from[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table1_srno_to[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table1_totalno[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table1_cancelled[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table1_netissued[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table1a').append(markup);
        });
		$('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});
     
		 
        
    }); 

</script>
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-table2a").click(function(){
          var element = document.getElementById('trtable2');
			if (element != null && element.value == '') {
		document.getElementById('trtable2').style.display = 'none';
			}
            var markup = "<tr><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='table2_srno_from[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table2_srno_to[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table2_totalno[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table2_cancelled[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table2_netissued[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table2a').append(markup);
        });
		$('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});    
	
        
    }); 
</script>
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-table3a").click(function(){
           var element = document.getElementById('trtable3');
			if (element != null && element.value == '') {
		document.getElementById('trtable3').style.display = 'none';
			}
            var markup = "<tr><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='table3_srno_from[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table3_srno_to[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table3_totalno[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table3_cancelled[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table3_netissued[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table3a').append(markup);
        });
		$('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});    
	
        
    }); 
</script>
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-table4a").click(function(){
		var element = document.getElementById('trtable4');
			if (element != null && element.value == '') {
		document.getElementById('trtable4').style.display = 'none';
			}
         
            var markup = "<tr><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='table4_srno_from[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table4_srno_to[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table4_totalno[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table4_cancelled[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table4_netissued[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table4a').append(markup);
        });
		$('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});    
	
        
    }); 
</script>
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-table5a").click(function(){
           var element = document.getElementById('trtable5');
			if (element != null && element.value == '') {
		document.getElementById('trtable5').style.display = 'none';
			}
            var markup = "<tr><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='table5_srno_from[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table5_srno_to[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table5_totalno[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table5_cancelled[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table5_netissued[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table5a').append(markup);
        });
		$('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});    
	
        
    }); 
</script>
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-table6a").click(function(){
           var element = document.getElementById('trtable6');
			if (element != null && element.value == '') {
		document.getElementById('trtable6').style.display = 'none';
			}
            var markup = "<tr><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='table6_srno_from[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table6_srno_to[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table6_totalno[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table6_cancelled[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table6_netissued[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table6a').append(markup);
        });
		$('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});    
	
        
    }); 
</script>
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-table7a").click(function(){
           var element = document.getElementById('trtable7');
			if (element != null && element.value == '') {
		document.getElementById('trtable7').style.display = 'none';
			}
            var markup = "<tr><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='table7_srno_from[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table7_srno_to[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table7_totalno[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table7_cancelled[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table7_netissued[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table7a').append(markup);
        });
		$('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});    
	
        
    }); 
</script>
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-table8a").click(function(){
           var element = document.getElementById('trtable8');
			if (element != null && element.value == '') {
		document.getElementById('trtable8').style.display = 'none';
			}
            var markup = "<tr><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='table8_srno_from[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table8_srno_to[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table8_totalno[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table8_cancelled[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table8_netissued[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table8a').append(markup);
        });
		$('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});    
	
        
    }); 
</script>
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-table9a").click(function(){
           var element = document.getElementById('trtable9');
			if (element != null && element.value == '') {
		document.getElementById('trtable9').style.display = 'none';
			}
            var markup = "<tr><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='table9_srno_from[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table9_srno_to[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table9_totalno[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table9_cancelled[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table9_netissued[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table9a').append(markup);
        });
		$('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});    
	
        
    }); 
</script>
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-table10a").click(function(){
          var element = document.getElementById('trtable10');
			if (element != null && element.value == '') {
		document.getElementById('trtable10').style.display = 'none';
			}
            var markup = "<tr><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='table10_srno_from[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table10_srno_to[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table10_totalno[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table10_cancelled[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table10_netissued[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table10a').append(markup);
        });
		$('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});    
	        
    }); 
</script>
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-table11a").click(function(){
           var element = document.getElementById('trtable11');
			if (element != null && element.value == '') {
		document.getElementById('trtable11').style.display = 'none';
			}
            var markup = "<tr><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='table11_srno_from[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table11_srno_to[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table11_totalno[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table11_cancelled[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table11_netissued[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table11a').append(markup);
        });
		$('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});    
	        
    }); 
</script>
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-table12a").click(function(){
           var element = document.getElementById('trtable12');
			if (element != null && element.value == '') {
		document.getElementById('trtable12').style.display = 'none';
			}
            var markup = "<tr><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='table12_srno_from[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table12_srno_to[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table12_totalno[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table12_cancelled[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table12_netissued[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table12a').append(markup);
        });
		$('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});    
	        
    }); 
</script>



<script>
    $(document).ready(function () {
        $('#returnmonth').on('change', function () {
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_document_summary&returnmonth=<?php echo $returnmonth; ?>';
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
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_document_summary&returnmonth=<?php echo $returnmonth; ?>';
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