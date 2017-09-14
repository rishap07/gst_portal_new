<?php						 
					 
$obj_transition = new transition();
$obj_gstr2 = new gstr2();
 
//$obj_login->sendMobileMessage
$returnmonth = date('Y-m');

if(isset($_POST['returnmonth']))
{
    $returnmonth = $_POST['returnmonth'];
	$obj_transition->redirect(PROJECT_URL."/?page=return_hsnwise_summary&returnmonth=".$returnmonth);
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
    if($obj_gstr2->saveGstr1HsnSummary()){
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

        $table1_hsn='';
		$table1_description='';
		$table1_unit='';
		$table1_qty='';
		$table1_totalvalue='';
		$table1_taxablevalue='';
		$table1_igst='';
		$table1_cgst='';
		$table1_sgst='';
		$table1_cess='';
$autoflag=0;
		$dataflag=0;		
if(isset($_POST['autoname']) && $_POST['autoname']==1) {
	
        $returndatahsn = $db_obj->getHSNInvoices($_SESSION["user_detail"]["user_id"],$returnmonth);
		
	    if(!empty($returndatahsn))
		{
			$autoflag=1;
		  
		}
}
	  // $sql = "select  *,count(return_id) as totalinvoice from ".TAB_PREFIX."client_return_gstr3b where added_by='" . $_SESSION['user_detail']['user_id'] . "' and financial_month like '%" . $returnmonth . "%' and is_deleted='0'  order by return_id desc limit 0,1";
        $sql = "select  *,count(id) as totalinvoice from gst_gstr1_hsnwise_summary where added_by='" . $_SESSION['user_detail']['user_id'] . "' and financial_month like '%" . $returnmonth . "%' and is_deleted='0'  order by id desc limit 0,1";
        $returndata1 = $obj_transition->get_results($sql);
	  	if($returndata1[0]->totalinvoice > 0)
		{
		$arr = $returndata1[0]->gstr1_hsnwise_data;
		$arr1= base64_decode($arr);
		$summary_arr = json_decode($arr1);	
			
		foreach($summary_arr as $item)
		{
						
			$table1_hsn=$item->table1_hsn;
			$table1_description=$item->table1_description;
			$table1_unit=$item->table1_unit;
			$table1_qty=$item->table1_qty;
			$table1_totalvalue=$item->table1_totalvalue;
			$table1_taxablevalue=$item->table1_taxablevalue;
			$table1_igst=$item->table1_igst;
			$table1_cgst=$item->table1_cgst;
			$table1_sgst=$item->table1_sgst;
			$table1_cess=$item->table1_cess;
			if($item->table1_hsn!='' || $item->table1_description!='' || $item->table1_unit!='' || $item->table1_qty!='' || $item->table1_totalvalue!='' || $item->table1_taxablevalue!='' || $item->table1_igst!='' || $item->table1_cgst!='' || $item->table1_sgst!='' || $item->table1_cess!='')
			{
				
				$dataflag=1;
			}				
		}
		}	
	  if($autoflag==1)
	  {
		  $dataflag=0;
	  }
	  
	   ?>

      <div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
      <div class="col-md-12 col-sm-12 col-xs-12">
       
      <div class="col-md-6 col-sm-6 col-xs-12 heading"><h1>HSN-wise summary</h1></div>
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
				 if($returndata1[0]->final_submit == 1){
		    echo "<div id='sucmsg' style='background-color:#DBEDDF;border-radius:4px;padding:8px 35px 8px 14px;text-shadow:0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom:18px;border-color:#D1E8DA;color:#39A25F;'><i class='fa fa-check'></i> <b>GST-Transition month of  ".$returnmonth." already submitted </div>";
					
				} }?>
				<div class="tab" style="display:none;">
                <a href="<?php echo PROJECT_URL . '/?page=transition_gstr&returnmonth='.$returnmonth ?>">
                    Transition Form1
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_hsnwise_summary&returnmonth='.$returnmonth ?>" class="active" >
                    Transition Form2
                </a>
              
            </div>
			
		 
		
			<form method="post" id="auto" name="auto">
			 <button  type="button"  class="btn btn-success" id="btnConfirm">autopopulate</button>
		     <input type="hidden" name="autoname" id="autoname" value="1" />
			 <input style="display:none;" type='submit' class="btn btn-success" name='autopopulate' value='autopopulate'>
			</form>	   			
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
							if($returndata1[0]->final_submit == 1)
							{
								?>
							<div class="inovicergttop">
                            <ul class="iconlist">                               
                               
                                <li><a href="<?php echo PROJECT_URL; ?>/?page=return_hsnwise_summary&action=downloadInvoice&id=<?php echo $returndata1[0]->financial_month; ?>&returnmonth=<?php echo $returnmonth; ?>"><div data-toggle="tooltip" data-placement="bottom" title="PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></div></a></li>
                                <li><a href="<?php echo PROJECT_URL; ?>/?page=return_hsnwise_summary&action=printInvoice&id=<?php echo $returndata1[0]->financial_month; ?>&returnmonth=<?php echo $returnmonth; ?>" target="_blank"><div data-toggle="tooltip" data-placement="bottom" title="PRINT"><i class="fa fa-print" aria-hidden="true"></i></div></a></li>
                                <li><a href="<?php echo PROJECT_URL; ?>/?page=return_hsnwise_summary&action=emailInvoice&id=<?php echo $returndata1[0]->financial_month; ?>&returnmonth=<?php echo $returnmonth; ?>"><div data-toggle="tooltip" data-placement="bottom" title="Email"><i class="fa fa-envelope-o" aria-hidden="true"></i></div></a></li>
                                <!--<li><a href="#"><div data-toggle="tooltip" data-placement="bottom" title="Attached File"><i class="fa fa-paperclip" aria-hidden="true"></i></div></a></li>-->
                         </ul>
							</div><?php } ?>
                       <form method="post" enctype="multipart/form-data" id='form'> 
					   <div class="greyheading">1.HSN-wise summary of outward supplies</div>
					       <div class="tableresponsive">
						   <form method="post" enctype="multipart/form-data" id='form'>
                            <table  class="table  tablecontent tablecontent2 bordernone" id='table1a'>
                                <thead>
                                <tr>
                                <th>HSN</th>
                                <th>Description</th>
                                <th>Unit</th>
                                <th>TotalQty</th>
                                <th>TotalValue(<i class="fa fa-inr"></i>)</th>
                                <th>TaxableValue(<i class="fa fa-inr"></i>)</th>
								<th>IGST Amount(<i class="fa fa-inr"></i>)</th>
								<th>CGST Amount(<i class="fa fa-inr"></i>)</th>
								<th>SGST Amount(<i class="fa fa-inr"></i>)</th>
								<th>CESS Amount(<i class="fa fa-inr"></i>)</th>
                                </tr>
                                </thead>
                                
                                <tbody>
				<?php
                     if($autoflag==1)
					 {
                       foreach($returndatahsn as $data) {
							
                           ?>
                                <tr>
                                 <td>
								 <?php
								 if(($returndata1[0]->totalinvoice > 0) && ($returndata1[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($data->item_hsncode)) { echo $data->item_hsncode; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table1_hsn[]"
 class="form-control" value="<?php if(isset($data->item_hsncode)) { echo $data->item_hsncode; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                 <td>
								 <?php
								 if(($returndata1[0]->totalinvoice > 0) && ($returndata1[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table1_description[$i])) { echo $table1_description[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table1_description[]"
 class="form-control" value="<?php if(isset($data->item_hsncode)) { echo $data->item_hsncode.'_'.$data->item_unit; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata1[0]->totalinvoice > 0) && ($returndata1[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table1_unit[$i])) { echo $table1_unit[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								<select  name="table1_unit[]"   id='table1_unit' class="required form-control">
							<?php $dataSupplyStateArrs = $obj_transition->get_results("select * from ".$obj_transition->getTableName('unit')." where status='1' and is_deleted='0' order by unit_name asc"); ?>
							<?php if(!empty($dataSupplyStateArrs)) { ?>
								<option value=''>Select unit</option>
								<?php foreach($dataSupplyStateArrs as $dataSupplyStateArr) { ?>
								<option value='<?php echo $dataSupplyStateArr->unit_id; ?>' <?php
                                    
										
										if($dataSupplyStateArr->unit_name==$data->item_unit)
										{
                                        echo "selected='selected'";
                                        }
									
                                    ?>><?php echo $dataSupplyStateArr->unit_name; ?></option>
								<?php } ?>
							<?php } ?>
						</select>  
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata1[0]->totalinvoice > 0) && ($returndata1[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table1_qty[$i])) { echo $table1_qty[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);"  name="table1_qty[]"
 class="form-control" value="<?php if(isset($data->item_quantity)) { echo $data->item_quantity; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata1[0]->totalinvoice > 0) && ($returndata1[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($data->invoice_total_value)) { echo $data->invoice_total_value; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table1_totalvalue[]" value="<?php if(isset($data->invoice_total_value)) { echo $data->invoice_total_value; } else { echo ''; }?>"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                <td>
								 <?php
								  if(($returndata1[0]->totalinvoice > 0) && ($returndata1[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table1_taxablevalue[$i])) { echo $table1_taxablevalue[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table1_taxablevalue[]" value="<?php if(isset($data->taxable_subtotal)) { echo $data->taxable_subtotal; } else { echo ''; }?>"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                <td>
								 <?php
								  if(($returndata1[0]->totalinvoice > 0) && ($returndata1[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($data->igst_amount)) { echo $data->igst_amount; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table1_igst[]" value="<?php if(isset($data->igst)) { echo $data->igst; } else { echo ''; }?>"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                <td>
								 <?php
								  if(($returndata1[0]->totalinvoice > 0) && ($returndata1[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($data->cgst_amount)) { echo $data->cgst_amount; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table1_cgst[]" value="<?php if(isset($data->cgst)) { echo $data->cgst; } else { echo ''; }?>"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                <td>
								 <?php
								  if(($returndata1[0]->totalinvoice > 0) && ($returndata1[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table1_sgst[$i])) { echo $table1_sgst[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table1_sgst[]" value="<?php if(isset($data->sgst_amount)) { echo $data->sgst_amount; } else { echo ''; }?>"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>	
                               <td>
								 <?php
								  if(($returndata1[0]->totalinvoice > 0) && ($returndata1[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table1_cess[$i])) { echo $table1_cess[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table1_cess[]" value="<?php if(isset($data->cess_amount)) { echo $data->cess_amount; } else { echo ''; }?>"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>									 
								 
								 <?php if(($returndata1[0]->totalinvoice > 0) && ($returndata1[0]->final_submit == 0))
								 {   
							       
									?>
                                           
                               <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
															 
                                </tr>						 
					 <?php } } }
								else if(!empty($returndata1[0]->totalinvoice) && ($returndata1[0]->totalinvoice > 0) && ($dataflag==1))
								{
									
								$table1_hsn=(explode(",",$table1_hsn));
								$table1_description=(explode(",",$table1_description));
								$table1_unit=(explode(",",$table1_unit));
								$table1_qty=(explode(",",$table1_qty));
								$table1_totalvalue=(explode(",",$table1_totalvalue));
								$table1_taxablevalue=(explode(",",$table1_taxablevalue));
								$table1_igst=(explode(",",$table1_igst));
								$table1_cgst=(explode(",",$table1_cgst));
								$table1_sgst=(explode(",",$table1_sgst));
								$table1_cess=(explode(",",$table1_cess));
											
			                    $start='';
								if(sizeof($table1_hsn) > 1)
								{
									$start = $table1_hsn;
							    }
								elseif(sizeof($table1_description) > 1)
								{
									 $start = $table1_description;
								}
								elseif(sizeof($table1_unit) > 1)
								{
									 $start = $table1_unit;
									
								}
								elseif(sizeof($table1_qty) > 1)
								{
									 $start = $table1_qty;
									
								}
								elseif(sizeof($table1_totalvalue) > 1)
								{
									$start = $table1_totalvalue;
								}
                                elseif(sizeof($table1_taxablevalue) > 1)
								{
									$start = $table1_taxablevalue;
								}
								elseif(sizeof($table1_igst) > 1)
								{
									$start = $table1_igst;
								}
								elseif(sizeof($table1_cgst) > 1)
								{
									$start = $table1_cgst;
								}
								elseif(sizeof($table1_sgst) > 1)
								{
									$start = $table1_sgst;
								}
								elseif(sizeof($table1_cess) > 1)
								{
									$start = $table1_cess;
								}							
								else{
									$start = $table1_hsn;
								}
								
									
						    for($i=0;$i < sizeof($start); $i++) {
								 $sno =0;
								 $sno = $i+1;
							if($table1_hsn[$i]!='' || $table1_description[$i]!='' || $table1_unit[$i]!='' || $table1_qty[$i]!='' || $table1_totalvalue[$i]!='' || $table1_taxablevalue[$i]!='' || $table1_igst[$i]!='' || $table1_cgst[$i]!='' || $table1_sgst[$i]!='' || $table1_cess[$i]!='')
							{	   
                           ?>
                                <tr>
                                 <td>
								 <?php
								 if(($returndata1[0]->totalinvoice > 0) && ($returndata1[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table1_hsn[$i])) { echo $table1_hsn[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table1_hsn[]"
 class="form-control" value="<?php if(isset($table1_hsn[$i])) { echo $table1_hsn[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                 <td>
								 <?php
								 if(($returndata1[0]->totalinvoice > 0) && ($returndata1[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table1_description[$i])) { echo $table1_description[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table1_description[]"
 class="form-control" value="<?php if(isset($table1_description[$i])) { echo $table1_description[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata1[0]->totalinvoice > 0) && ($returndata1[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table1_unit[$i])) { echo $table1_unit[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								<select  name="table1_unit[]"   id='table1_unit' class="required form-control">
							<?php $dataSupplyStateArrs = $obj_transition->get_results("select * from ".$obj_transition->getTableName('unit')." where status='1' and is_deleted='0' order by unit_name asc"); ?>
							<?php if(!empty($dataSupplyStateArrs)) { ?>
								<option value=''>Select unit</option>
								<?php foreach($dataSupplyStateArrs as $dataSupplyStateArr) { ?>
								<option value='<?php echo $dataSupplyStateArr->unit_id; ?>' <?php
                                    
										
										if($dataSupplyStateArr->unit_id==$table1_unit[$i])
										{
                                        echo "selected='selected'";
                                        }
									
                                    ?>><?php echo $dataSupplyStateArr->unit_name; ?></option>
								<?php } ?>
							<?php } ?>
						</select>  
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata1[0]->totalinvoice > 0) && ($returndata1[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table1_qty[$i])) { echo $table1_qty[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);"  name="table1_qty[]"
 class="form-control" value="<?php if(isset($table1_qty[$i])) { echo $table1_qty[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata1[0]->totalinvoice > 0) && ($returndata1[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table1_totalvalue[$i])) { echo $table1_totalvalue[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table1_totalvalue[]" value="<?php if(isset($table1_totalvalue[$i])) { echo $table1_totalvalue[$i]; } else { echo ''; }?>"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                <td>
								 <?php
								  if(($returndata1[0]->totalinvoice > 0) && ($returndata1[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table1_taxablevalue[$i])) { echo $table1_taxablevalue[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table1_taxablevalue[]" value="<?php if(isset($table1_taxablevalue[$i])) { echo $table1_taxablevalue[$i]; } else { echo ''; }?>"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                <td>
								 <?php
								  if(($returndata1[0]->totalinvoice > 0) && ($returndata1[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table1_igst[$i])) { echo $table1_igst[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table1_igst[]" value="<?php if(isset($table1_igst[$i])) { echo $table1_igst[$i]; } else { echo ''; }?>"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                <td>
								 <?php
								  if(($returndata1[0]->totalinvoice > 0) && ($returndata1[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table1_cgst[$i])) { echo $table1_cgst[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table1_cgst[]" value="<?php if(isset($table1_cgst[$i])) { echo $table1_cgst[$i]; } else { echo ''; }?>"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                <td>
								 <?php
								  if(($returndata1[0]->totalinvoice > 0) && ($returndata1[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table1_sgst[$i])) { echo $table1_sgst[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table1_sgst[]" value="<?php if(isset($table1_sgst[$i])) { echo $table1_sgst[$i]; } else { echo ''; }?>"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>	
                               <td>
								 <?php
								  if(($returndata1[0]->totalinvoice > 0) && ($returndata1[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($table1_cess[$i])) { echo $table1_cess[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table1_cess[]" value="<?php if(isset($table1_cess[$i])) { echo $table1_cess[$i]; } else { echo ''; }?>"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>									 
								 
								 <?php if(($returndata1[0]->totalinvoice > 0) && ($returndata1[0]->final_submit == 0))
								 {   
							        if($i==0){
									 ?>
                                            <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
						<?php } else { ?>
                               <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
							  <?php } }  ?>								 
                                </tr>
								
								
							<?php } else { ?> <tr id="trtable1"><td colspan="10" align="center">Nothing data to display here</td></tr> <?php } }  } else {  ?>

								<tr id="trtable2"><td colspan="10" align="center">Nothing found here</td></tr>
								<?php } ?>                              
							
                                </tbody>
                            </table>
                           <input type="button" value="Add New Row" class="btn btn-success add-table1a"  href="javascript:void(0)">
											
                        </div>                						
                    	
                        
							
                         <div class="tableresponsive">
                         
							<?php
							 if(($returndata1[0]->totalinvoice > 0) && ($returndata1[0]->final_submit == 1))
							{
								if($returndata1[0]->final_submit == 0)
							{
								?>
                                <div class="adminformbxsubmit" style="width:100%;"> 
                            <div class="tc">
                               <input type="button" value="<?php echo ucfirst('Edit'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=return_hsnwise_summary_submit&returnmonth=".$_REQUEST["returnmonth"]; ?>';" class="btn btn-success" class="redbtn marlef10"/>
							  <input type='submit' class="btn btn-danger" name='cleardata' value='clear data' id='cleardata'>
							  <input type='hidden' name="returnid" id="returnid" value="<?php echo $returndata1[0]->return_id; ?>" />
									
                               
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
								if(($returndata1[0]->totalinvoice > 0) && ($returndata1[0]->final_submit == 0))
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
			var element = document.getElementById('trtable2');
			if (element != null && element.value == '') {
		document.getElementById('trtable2').style.display = 'none';
			}
			var data1 ='<select class="required form-control" id="table1_unit"  name="table1_unit[]">';
			 var data='';
			 data +=<?php $dataSupplyStateArrs = $obj_transition->get_results("select * from ".$obj_transition->getTableName('unit')." where status='1' and is_deleted='0' order by unit_name asc"); ?>
						<?php if(!empty($dataSupplyStateArrs)) { ?>
							data += '<option value="">Select Unit</option>';
							<?php foreach($dataSupplyStateArrs as $dataSupplyStateArr) { ?>
								data += '<option value="<?php echo $dataSupplyStateArr->unit_id; ?>"><?php echo $dataSupplyStateArr->unit_name; ?></option>';
							<?php } ?>
						<?php } ?>
							
			data = data1+ data+'</select>';
		
          //  var markup = "<tr><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='table1_srno_from[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table1_srno_to[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table1_totalno[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table1_cancelled[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table1_netissued[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
		      var markup = "<tr><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='table1_hsn[]'/></td><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='table1_description[]'/></td><td>" + data + "</td><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='table1_qty[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='table1_totalvalue[]'/></td><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='table1_taxablevalue[]'/></td><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='table1_igst[]'/></td><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='table1_cgst[]'/></td><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='table1_sgst[]'/></td><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='table1_cess[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
        
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
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_hsnwise_summary&returnmonth=<?php echo $returnmonth; ?>';
                        document.form2.submit();
                    });
                });
</script>
  
<script>
    $(document).ready(function () {
        $('#returnmonth').on('change', function () {
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_hsnwise_summary&returnmonth=<?php echo $returnmonth; ?>';
                        document.form2.submit();
                    });
                });
</script>
<script>
function ezBSAlert (options) {
	var deferredObject = $.Deferred();
	var defaults = {
		type: "alert", //alert, prompt,confirm 
		modalSize: 'modal-sm', //modal-sm, modal-lg
		okButtonText: 'Confirm',
		cancelButtonText: 'Cancel',
		yesButtonText: 'Yes',
		noButtonText: 'No',
		headerText: 'Important : Please Read And Confirm',
		messageText: 'Message',
		alertType: 'default', //default, primary, success, info, warning, danger
		inputFieldType: 'text', //could ask for number,email,etc
	}
	$.extend(defaults, options);
  
	var _show = function(){
		var headClass = "navbar-default";
		switch (defaults.alertType) {
			case "primary":
				headClass = "alert-primary";
				break;
			case "success":
				headClass = "alert-success";
				break;
			case "info":
				headClass = "alert-info";
				break;
			case "warning":
				headClass = "alert-warning";
				break;
			case "danger":
				headClass = "alert-danger";
				break;
        }
		$('BODY').append(
			'<div id="ezAlerts" style="z-index: 99999" class="modal fade">' +
			'<div class="modal-dialog" class="' + defaults.modalSize + '">' +
			'<div class="modal-content">' +
			'<div id="ezAlerts-header" class="modal-header ' + headClass + '">' +
			'<button id="close-button" type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>' +
			'<h4 id="ezAlerts-title" class="modal-title">Modal title</h4>' +
			'</div>' +
			'<div id="ezAlerts-body" class="modal-body">' +
			'<div id="ezAlerts-message" ></div>' +
			'</div>' +
			'<div id="ezAlerts-footer" class="modal-footer">' +
			'</div>' +
			'</div>' +
			'</div>' +
			'</div>'
		);

		$('.modal-header').css({
			'padding': '15px 15px',
			'-webkit-border-top-left-radius': '5px',
			'-webkit-border-top-right-radius': '5px',
			'-moz-border-radius-topleft': '5px',
			'-moz-border-radius-topright': '5px',
			'border-top-left-radius': '5px',
			'border-top-right-radius': '5px'
		});
    
		$('#ezAlerts-title').text(defaults.headerText);
		$('#ezAlerts-message').html(defaults.messageText);

		var keyb = "false", backd = "static";
		var calbackParam = "";
		switch (defaults.type) {
			case 'alert':
				keyb = "true";
				backd = "true";
				$('#ezAlerts-footer').html('<button class="btn btn-' + defaults.alertType + '">' + defaults.okButtonText + '</button>').on('click', ".btn", function () {
					calbackParam = true;
					$('#ezAlerts').modal('hide');
				});
				break;
			case 'confirm':
				var btnhtml = '<button id="ezok-btn" class="btn btn-primary">' + defaults.yesButtonText + '</button>';
				if (defaults.noButtonText && defaults.noButtonText.length > 0) {
					btnhtml += '<button id="ezclose-btn" class="btn btn-default">' + defaults.noButtonText + '</button>';
				}
				$('#ezAlerts-footer').html(btnhtml).on('click', 'button', function (e) {
						if (e.target.id === 'ezok-btn') {
							calbackParam = true;
							$('#ezAlerts').modal('hide');
						} else if (e.target.id === 'ezclose-btn') {
							calbackParam = false;
							$('#ezAlerts').modal('hide');
						}
					});
				break;
			case 'prompt':
				$('#ezAlerts-message').html(defaults.messageText + '<br /><br /><div class="form-group"><input type="' + defaults.inputFieldType + '" class="form-control" id="prompt" /></div>');
				$('#ezAlerts-footer').html('<button class="btn btn-primary">' + defaults.okButtonText + '</button>').on('click', ".btn", function () {
					calbackParam = $('#prompt').val();
					$('#ezAlerts').modal('hide');
				});
				break;
		}
   
		$('#ezAlerts').modal({ 
          show: false, 
          backdrop: backd, 
          keyboard: keyb 
        }).on('hidden.bs.modal', function (e) {
			$('#ezAlerts').remove();
			deferredObject.resolve(calbackParam);
		}).on('shown.bs.modal', function (e) {
			if ($('#prompt').length > 0) {
				$('#prompt').focus();
			}
		}).modal('show');
	}
    
  _show();  
  return deferredObject.promise();    
}





$(document).ready(function(){
  
  
  $("#btnConfirm").on("click", function(){  	
    ezBSAlert({
      type: "confirm",
      messageText: "Auto-compute for HSN summary is based only on Invoices data.<br><br>Your current data for this section will be erased and it will be reset based on summary computed from Invoice level data.",
      alertType: "danger"
    }).done(function (e) {
      //$("body").append('<div>Callback from confirm ' + e + '</div>');
	  if(e==true)
	  {
		   document.auto.action = '<?php echo PROJECT_URL; ?>/?page=return_hsnwise_summary&returnmonth=<?php echo $returnmonth; ?>';
           document.auto.submit();
	  }
    });
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