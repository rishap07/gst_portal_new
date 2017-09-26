<?php						 
					 
$obj_transition = new transition();
$obj_gstr2 = new gstr2();
$obj_gstr1 = new gstr1();
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
	$obj_gstr2->saveGstr1DocumentSummary();
	/*
 $flag = $obj_transition->checkVerifyUser();
  if($flag=='notverify')
  {
	   $obj_transition->setError("To save document summary first verify your email and mobile number");
		
  }
  else{
    if($obj_gstr2->saveGstr1DocumentSummary()){
        //$obj_master->redirect(PROJECT_URL."/?page=master_receiver");
    }
  }
  */
}




       
	   $sql = "select  *,count(id) as totalinvoice from gst_return_upload_summary where added_by='" . $_SESSION['user_detail']['user_id'] . "' and financial_month like '%" . $returnmonth . "%' and is_deleted='0' and type='gstr1document'  order by id desc limit 0,1";
       $returndata1 = $obj_transition->get_results($sql);
	 
		if($returndata1[0]->totalinvoice > 0)
		{
		$arr = $returndata1[0]->return_data;
		$arr1= base64_decode($arr);
		$summary_arr = json_decode($arr1);
        //$obj_transition->pr($summary_arr);
        $doc_num1=array(); 
		
        $doc_num1=!empty($summary_arr->doc_num1)?$summary_arr->doc_num1:'';
		$doc_num2=array(); 
        $doc_num2=!empty($summary_arr->doc_num2)?$summary_arr->doc_num2:'';
		$doc_num3=array(); 
        $doc_num3=!empty($summary_arr->doc_num3)?$summary_arr->doc_num3:'';
		$doc_num4=array(); 
        $doc_num4=!empty($summary_arr->doc_num4)?$summary_arr->doc_num4:'';
		$doc_num5=array(); 
        $doc_num5=!empty($summary_arr->doc_num5)?$summary_arr->doc_num5:'';
		$doc_num6=array(); 
        $doc_num6=!empty($summary_arr->doc_num6)?$summary_arr->doc_num6:'';
		$doc_num7=array(); 
        $doc_num7=!empty($summary_arr->doc_num7)?$summary_arr->doc_num7:'';
		$doc_num8=array(); 
        $doc_num8=!empty($summary_arr->doc_num8)?$summary_arr->doc_num8:'';
		$doc_num9=array(); 
        $doc_num9=!empty($summary_arr->doc_num9)?$summary_arr->doc_num9:'';
		$doc_num10=array(); 
        $doc_num10=!empty($summary_arr->doc_num10)?$summary_arr->doc_num10:'';
		$doc_num11=array(); 
        $doc_num11=!empty($summary_arr->doc_num11)?$summary_arr->doc_num11:'';
		$doc_num12=array(); 
        $doc_num12=!empty($summary_arr->doc_num12)?$summary_arr->doc_num12:'';	
      
		
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
                                    $dataRes = $obj_gstr1->getInvoiceMonthList($obj_gstr1->getTableName('client_invoice'));
                                   if (!empty($dataRes)) {
                                        ?>
                                        <select class="dateselectbox" id="returnmonth" name="returnmonth">
                                        <?php
                                        foreach ($dataRes as $dataRe) {
                                            ?>
                                                <option value="<?php echo $dataRe->invoiceDate; ?>" <?php if ($dataRe->invoiceDate == $returnmonth) { echo 'selected'; } ?>><?php echo $dataRe->invoiceDate; ?></option>
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
								if(!empty($doc_num1))
								{
															
							$i=0;		
						    foreach($doc_num1 as $data) {
								$i++;
                          ?>
                                <tr>
                                 <td><input type="text" maxlength="16"  name="table1_srno_from[]" class="form-control" value="<?php if(isset($data->from)) { echo $data->from; } else { echo ''; }?>"  placeholder="" /> </td>
                                 <td><input type="text" maxlength="16"  name="table1_srno_to[]" class="form-control" value="<?php if(isset($data->to)) { echo $data->to; } else { echo ''; }?>"  placeholder="" /> </td>
								 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" id="table1_totalno" onchange="table1onchange(this.value)" name="table1_totalno[]"   class="form-control" value="<?php if(isset($data->totnum)) { echo $data->totnum; } else { echo ''; }?>"  placeholder="" /></td>
								 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" id="table1_cancelled" onchange="table1onchange(this.value)" name="table1_cancelled[]"  class="form-control" value="<?php if(isset($data->cancel)) { echo $data->cancel; } else { echo ''; }?>"  placeholder="" /> </td>
								 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" id="table1_netissued" name="table1_netissued[]" id="table1_netissued" class="form-control" value="<?php if(isset($data->net_issue)) { echo $data->net_issue; } else { echo ''; }?>"  placeholder="" /> </td>
					 						 
                                </tr>
								
								
							<?php  }  } else {  ?>
                             <tr>
                              <td><input type="text" maxlength="16"  name="table1_srno_from[]" class="form-control"  placeholder="" /> </td> 
	                          <td><input type="text" maxlength="16"  name="table1_srno_to[]" class="form-control"  placeholder="" /> </td>
							 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table1_totalno[]" id="table1_totalno" onchange="table1onchange(this.value)"  class="form-control"  placeholder="" /> </td>
							<td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table1_cancelled[]" id="table1_cancelled" onchange="table1onchange(this.value)" class="form-control"  placeholder="" /> </td> 
							 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table1_netissued[]" class="form-control"  placeholder="" /></td> 
  						 	
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
								if(!empty($doc_num2))
								{
															
							$i=0;		
						    foreach($doc_num2 as $data) {
								$i++;
                          ?>
                                <tr>
                                 <td><input type="text" maxlength="16"  name="table2_srno_from[]" class="form-control" value="<?php if(isset($data->from)) { echo $data->from; } else { echo ''; }?>"  placeholder="" /> </td>
                                 <td><input type="text" maxlength="16"  name="table2_srno_to[]" class="form-control" value="<?php if(isset($data->to)) { echo $data->to; } else { echo ''; }?>"  placeholder="" /> </td>
								 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" onchange="table2onchange(this.value)" id="table2_totalno" name="table2_totalno[]" class="form-control" value="<?php if(isset($data->totnum)) { echo $data->totnum; } else { echo ''; }?>"  placeholder="" /></td>
								 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" onchange="table2onchange(this.value)" id="table2_cancelled" name="table2_cancelled[]"class="form-control" value="<?php if(isset($data->cancel)) { echo $data->cancel; } else { echo ''; }?>"  placeholder="" /> </td>
								 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" id="table2_netissued" name="table2_netissued[]"class="form-control" value="<?php if(isset($data->net_issue)) { echo $data->net_issue; } else { echo ''; }?>"  placeholder="" /> </td>
						 
					 					 
                                </tr>
					<?php  }  } else {  ?>
 
                              <tr>
                              <td><input type="text" maxlength="16"  name="table2_srno_from[]" class="form-control"  placeholder="" /> </td> 
	                          <td><input type="text" maxlength="16"  name="table2_srno_to[]" class="form-control"  placeholder="" /> </td>
							 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" onchange="table2onchange(this.value)" id="table2_totalno" name="table2_totalno[]"  class="form-control"  placeholder="" /> </td>
							<td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" onchange="table2onchange(this.value)" id="table2_cancelled" name="table2_cancelled[]" class="form-control"  placeholder="" /> </td> 
							 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" id="table2_netissued" name="table2_netissued[]" class="form-control"  placeholder="" /></td> 
  						 	
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
								if(!empty($doc_num3))
								{
															
							$i=0;		
						    foreach($doc_num3 as $data) {
								$i++;
                          ?>
                                <tr>
                                 <td><input type="text" maxlength="16"  name="table3_srno_from[]" class="form-control" value="<?php if(isset($data->from)) { echo $data->from; } else { echo ''; }?>"  placeholder="" /> </td>
                                 <td><input type="text" maxlength="16"  name="table3_srno_to[]" class="form-control" value="<?php if(isset($data->to)) { echo $data->to; } else { echo ''; }?>"  placeholder="" /> </td>
								 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" onchange="table3onchange(this.value)" id="table3_totalno" name="table3_totalno[]" class="form-control" value="<?php if(isset($data->totnum)) { echo $data->totnum; } else { echo ''; }?>"  placeholder="" /></td>
								 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" onchange="table3onchange(this.value)" id="table3_cancelled" name="table3_cancelled[]"class="form-control" value="<?php if(isset($data->cancel)) { echo $data->cancel; } else { echo ''; }?>"  placeholder="" /> </td>
								 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" id="table3_netissued" name="table3_netissued[]"class="form-control" value="<?php if(isset($data->net_issue)) { echo $data->net_issue; } else { echo ''; }?>"  placeholder="" /> </td>
									   							 
                                </tr>
								
								
							<?php  }  } else {  ?>
                             <tr>
                              <td><input type="text" maxlength="16"  name="table3_srno_from[]" class="form-control"  placeholder="" /> </td> 
	                          <td><input type="text" maxlength="16"  name="table3_srno_to[]" class="form-control"  placeholder="" /> </td>
							 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table3_totalno[]" id="table3_totalno" onchange="table3onchange(this.value)"  class="form-control"  placeholder="" /> </td>
							<td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table3_cancelled[]" id="table3_cancelled" onchange="table3onchange(this.value)" class="form-control"  placeholder="" /> </td> 
							 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table3_netissued[]" id="table3_netissued" class="form-control"  placeholder="" /></td> 
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
                                </thead><tbody>
						<?php
								if(!empty($doc_num4))
								{
															
							$i=0;		
						    foreach($doc_num4 as $data) {
								$i++;
                          ?>
                                <tr>
                                 <td><input type="text" maxlength="16"  name="table4_srno_from[]" class="form-control" value="<?php if(isset($data->from)) { echo $data->from; } else { echo ''; }?>"  placeholder="" /> </td>
                                 <td><input type="text" maxlength="16"  name="table4_srno_to[]" class="form-control" value="<?php if(isset($data->to)) { echo $data->to; } else { echo ''; }?>"  placeholder="" /> </td>
								 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" onchange="table4onchange(this.value)" id="table4_totalno"  name="table4_totalno[]" class="form-control" value="<?php if(isset($data->totnum)) { echo $data->totnum; } else { echo ''; }?>"  placeholder="" /></td>
								 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" onchange="table4onchange(this.value)" id="table4_cancelled" name="table4_cancelled[]"class="form-control" value="<?php if(isset($data->cancel)) { echo $data->cancel; } else { echo ''; }?>"  placeholder="" /> </td>
								 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table4_netissued[]" id="table4_netissued" class="form-control" value="<?php if(isset($data->net_issue)) { echo $data->net_issue; } else { echo ''; }?>"  placeholder="" /> </td>
								 
					   						 
                                </tr>
								
								
							<?php  }  } else {  ?>

								<tr>
                              <td><input type="text" maxlength="16"  name="table4_srno_from[]" class="form-control"  placeholder="" /> </td> 
	                          <td><input type="text" maxlength="16"  name="table4_srno_to[]" class="form-control"  placeholder="" /> </td>
							 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table4_totalno[]" onchange="table4onchange(this.value)" id="table4_totalno"  class="form-control"  placeholder="" /> </td>
							<td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table4_cancelled[]" onchange="table4onchange(this.value)" id="table4_cancelled" class="form-control"  placeholder="" /> </td> 
							 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table4_netissued[]" id="table4_netissued" class="form-control"  placeholder="" /></td> 
  						 	
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
								if(!empty($doc_num5))
								{
															
							$i=0;		
						    foreach($doc_num5 as $data) {
								$i++;
                          ?>
                                <tr>
                                 <td><input type="text" maxlength="16"  name="table5_srno_from[]" class="form-control" value="<?php if(isset($data->from)) { echo $data->from; } else { echo ''; }?>"  placeholder="" /> </td>
                                 <td><input type="text" maxlength="16"  name="table5_srno_to[]" class="form-control" value="<?php if(isset($data->to)) { echo $data->to; } else { echo ''; }?>"  placeholder="" /> </td>
								 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" id="table5_totalno" onchange="table5onchange(this.value)" name="table5_totalno[]" class="form-control" value="<?php if(isset($data->totnum)) { echo $data->totnum; } else { echo ''; }?>"  placeholder="" /></td>
								 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" id="table5_cancelled" onchange="table5onchange(this.value)" name="table5_cancelled[]"class="form-control" value="<?php if(isset($data->cancel)) { echo $data->cancel; } else { echo ''; }?>"  placeholder="" /> </td>
								 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" id="table5_netissued" name="table5_netissued[]"class="form-control" value="<?php if(isset($data->net_issue)) { echo $data->net_issue; } else { echo ''; }?>"  placeholder="" /> </td>
									   						 
                                </tr>
								
								
							<?php  }  } else {  ?>

								<tr>
                              <td><input type="text" maxlength="16"  name="table5_srno_from[]" class="form-control"  placeholder="" /> </td> 
	                          <td><input type="text" maxlength="16"  name="table5_srno_to[]" class="form-control"  placeholder="" /> </td>
							 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table5_totalno[]" onchange="table5onchange(this.value)" id="table5_totalno"  class="form-control"  placeholder="" /> </td>
							<td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table5_cancelled[]" onchange="table5onchange(this.value)" id="table5_cancelled class="form-control"  placeholder="" /> </td> 
							 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table5_netissued[]" id="table5_netissued" class="form-control"  placeholder="" /></td> 
  						 	
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
								if(!empty($doc_num6))
								{
															
							$i=0;		
						    foreach($doc_num6 as $data) {
								$i++;
                          ?>
                                <tr>
                                 <td><input type="text" maxlength="16"  name="table6_srno_from[]" class="form-control" value="<?php if(isset($data->from)) { echo $data->from; } else { echo ''; }?>"  placeholder="" /> </td>
                                 <td><input type="text" maxlength="16"  name="table6_srno_to[]" class="form-control" value="<?php if(isset($data->to)) { echo $data->to; } else { echo ''; }?>"  placeholder="" /> </td>
								 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" onchange="table6onchange(this.value)" id="table6_totalno" name="table6_totalno[]" class="form-control" value="<?php if(isset($data->totnum)) { echo $data->totnum; } else { echo ''; }?>"  placeholder="" /></td>
								 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" onchange="table6onchange(this.value)" id="table6_cancelled" name="table6_cancelled[]"class="form-control" value="<?php if(isset($data->cancel)) { echo $data->cancel; } else { echo ''; }?>"  placeholder="" /> </td>
								 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table6_netissued[]" id="table6_netissued" class="form-control" value="<?php if(isset($data->net_issue)) { echo $data->net_issue; } else { echo ''; }?>"  placeholder="" /> </td>
										    						 
                                </tr>
								
								
							<?php  }  } else {  ?>
                              <tr>
                              <td><input type="text" maxlength="16"  name="table6_srno_from[]" class="form-control"  placeholder="" /> </td> 
	                          <td><input type="text" maxlength="16"  name="table6_srno_to[]" class="form-control"  placeholder="" /> </td>
							 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table6_totalno[]" id="table6_totalno" onchange="table6onchange(this.value)"  class="form-control"  placeholder="" /> </td>
							<td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table6_cancelled[]" id="table6_cancelled" onchange="table6onchange(this.value)" class="form-control"  placeholder="" /> </td> 
							 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table6_netissued[]" id="table6_netissued" class="form-control"  placeholder="" /></td> 
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
								if(!empty($doc_num7))
								{
															
							$i=0;		
						    foreach($doc_num7 as $data) {
								$i++;
                          ?>
                                <tr>
                                 <td><input type="text" maxlength="16"  name="table7_srno_from[]" class="form-control" value="<?php if(isset($data->from)) { echo $data->from; } else { echo ''; }?>"  placeholder="" /> </td>
                                 <td><input type="text" maxlength="16"  name="table7_srno_to[]" class="form-control" value="<?php if(isset($data->to)) { echo $data->to; } else { echo ''; }?>"  placeholder="" /> </td>
								 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table7_totalno[]" id="table7_totalno" onchange="table7onchange(this.value)" class="form-control" value="<?php if(isset($data->totnum)) { echo $data->totnum; } else { echo ''; }?>"  placeholder="" /></td>
								 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table7_cancelled[]" id="table7_cancelled" onchange="table7onchange(this.value)" class="form-control" value="<?php if(isset($data->cancel)) { echo $data->cancel; } else { echo ''; }?>"  placeholder="" /> </td>
								 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table7_netissued[]" id="table7_netissued" class="form-control" value="<?php if(isset($data->net_issue)) { echo $data->net_issue; } else { echo ''; }?>"  placeholder="" /> </td>
									   							 
                                </tr>
								
								
							<?php  }  } else {  ?>
                               <tr>
                              <td><input type="text" maxlength="16"  name="table7_srno_from[]" class="form-control"  placeholder="" /> </td> 
	                          <td><input type="text" maxlength="16"  name="table7_srno_to[]" class="form-control"  placeholder="" /> </td>
							 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table7_totalno[]" id="table7_totalno" onchange="table7onchange(this.value)"  class="form-control"  placeholder="" /> </td>
							<td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table7_cancelled[]" id="table7_cancelled" onchange="table7onchange(this.value)" class="form-control"  placeholder="" /> </td> 
							 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table7_netissued[]" id="table7_netissued" class="form-control"  placeholder="" /></td> 
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
								if(!empty($doc_num8))
								{
															
							$i=0;		
						    foreach($doc_num8 as $data) {
								$i++;
                          ?>
                                <tr>
                                 <td><input type="text" maxlength="16"  name="table8_srno_from[]" class="form-control" value="<?php if(isset($data->from)) { echo $data->from; } else { echo ''; }?>"  placeholder="" /> </td>
                                 <td><input type="text" maxlength="16"  name="table8_srno_to[]" class="form-control" value="<?php if(isset($data->to)) { echo $data->to; } else { echo ''; }?>"  placeholder="" /> </td>
								 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" onchange="table8onchange(this.value)" id="table8_totalno" name="table8_totalno[]" class="form-control" value="<?php if(isset($data->totnum)) { echo $data->totnum; } else { echo ''; }?>"  placeholder="" /></td>
								 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" onchange="table8onchange(this.value)" id="table8_cancelled" name="table8_cancelled[]"class="form-control" value="<?php if(isset($data->cancel)) { echo $data->cancel; } else { echo ''; }?>"  placeholder="" /> </td>
								 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table8_netissued[]" id="table8_netissued" class="form-control" value="<?php if(isset($data->net_issue)) { echo $data->net_issue; } else { echo ''; }?>"  placeholder="" /> </td>
						
					    					 
                                </tr>
								
								
							<?php  }  } else {  ?>
					             <tr>
                              <td><input type="text" maxlength="16"  name="table8_srno_from[]" class="form-control"  placeholder="" /> </td> 
	                          <td><input type="text" maxlength="16"  name="table8_srno_to[]" class="form-control"  placeholder="" /> </td>
							 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" id="table8_totalno" onchange="table8onchange(this.value)" name="table8_totalno[]"  class="form-control"  placeholder="" /> </td>
							<td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" id="table8_cancelled" onchange="table8onchange(this.value)" name="table8_cancelled[]" class="form-control"  placeholder="" /> </td> 
							 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" id="table8_netissued" name="table8_netissued[]" class="form-control"  placeholder="" /></td> 
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
								if(!empty($doc_num1))
								{
															
							$i=0;		
						    foreach($doc_num1 as $data) {
								$i++;
                          ?>
                                <tr>
                                 <td><input type="text" maxlength="16"  name="table9_srno_from[]" class="form-control" value="<?php if(isset($data->from)) { echo $data->from; } else { echo ''; }?>"  placeholder="" /> </td>
                                 <td><input type="text" maxlength="16"  name="table9_srno_to[]" class="form-control" value="<?php if(isset($data->to)) { echo $data->to; } else { echo ''; }?>"  placeholder="" /> </td>
								 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" onchange="table9onchange(this.value)" id="table9_totalno" name="table9_totalno[]" class="form-control" value="<?php if(isset($data->totnum)) { echo $data->totnum; } else { echo ''; }?>"  placeholder="" /></td>
								 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" onchange="table9onchange(this.value)" id="table9_cancelled" name="table9_cancelled[]"class="form-control" value="<?php if(isset($data->cancel)) { echo $data->cancel; } else { echo ''; }?>"  placeholder="" /> </td>
								 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table9_netissued[]" id="table9_netissued" class="form-control" value="<?php if(isset($data->net_issue)) { echo $data->net_issue; } else { echo ''; }?>"  placeholder="" /> </td>
						
					    							 
                                </tr>
								
								
							<?php  }  } else {  ?>
                             <tr>
                              <td><input type="text" maxlength="16"  name="table9_srno_from[]" class="form-control"  placeholder="" /> </td> 
	                          <td><input type="text" maxlength="16"  name="table9_srno_to[]" class="form-control"  placeholder="" /> </td>
							 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" id="table9_totalno" onchange="table9onchange(this.value)" name="table9_totalno[]"  class="form-control"  placeholder="" /> </td>
							<td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" id="table9_cancelled" onchange="table9onchange(this.value)" name="table9_cancelled[]" class="form-control"  placeholder="" /> </td> 
							 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" id="table9_netissued" name="table9_netissued[]" class="form-control"  placeholder="" /></td> 
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
								if(!empty($doc_num10))
								{
															
							$i=0;		
						    foreach($doc_num10 as $data) {
								$i++;
                          ?>
                                <tr>
                                 <td><input type="text" maxlength="16"  name="table10_srno_from[]" class="form-control" value="<?php if(isset($data->from)) { echo $data->from; } else { echo ''; }?>"  placeholder="" /> </td>
                                 <td><input type="text" maxlength="16"  name="table10_srno_to[]" class="form-control" value="<?php if(isset($data->to)) { echo $data->to; } else { echo ''; }?>"  placeholder="" /> </td>
								 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" id="table10_totalno" onchange="table10onchange(this.value)" name="table10_totalno[]" class="form-control" value="<?php if(isset($data->totnum)) { echo $data->totnum; } else { echo ''; }?>"  placeholder="" /></td>
								 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" id="table10_cancelled" onchange="table10onchange(this.value)" name="table10_cancelled[]"class="form-control" value="<?php if(isset($data->cancel)) { echo $data->cancel; } else { echo ''; }?>"  placeholder="" /> </td>
								 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" id="table10_netissued" name="table10_netissued[]"class="form-control" value="<?php if(isset($data->net_issue)) { echo $data->net_issue; } else { echo ''; }?>"  placeholder="" /> </td>
								    								 
                                </tr>
								
								
							<?php  }  } else {  ?>
                               <tr>
                              <td><input type="text" maxlength="16"  name="table10_srno_from[]" class="form-control"  placeholder="" /> </td> 
	                          <td><input type="text" maxlength="16"  name="table10_srno_to[]" class="form-control"  placeholder="" /> </td>
							 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table10_totalno[]" id="table10_totalno" onchange="table10onchange(this.value)"  class="form-control"  placeholder="" /> </td>
							<td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table10_cancelled[]" id="table10_cancelled" onchange="table10onchange(this.value)" class="form-control"  placeholder="" /> </td> 
							 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table10_netissued[]" id="table10_netissued" class="form-control"  placeholder="" /></td> 
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
								if(!empty($doc_num11))
								{
															
							$i=0;		
						    foreach($doc_num11 as $data) {
								$i++;
                          ?>
                                <tr>
                                 <td><input type="text" maxlength="16"  name="table11_srno_from[]" class="form-control" value="<?php if(isset($data->from)) { echo $data->from; } else { echo ''; }?>"  placeholder="" /> </td>
                                 <td><input type="text" maxlength="16"  name="table11_srno_to[]" class="form-control" value="<?php if(isset($data->to)) { echo $data->to; } else { echo ''; }?>"  placeholder="" /> </td>
								 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table11_totalno[]" id="table11_totalno" onchange="table11onchange(this.value)" class="form-control" value="<?php if(isset($data->totnum)) { echo $data->totnum; } else { echo ''; }?>"  placeholder="" /></td>
								 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table11_cancelled[]" id="table11_cancelled" onchange="table11onchange(this.value)" class="form-control" value="<?php if(isset($data->cancel)) { echo $data->cancel; } else { echo ''; }?>"  placeholder="" /> </td>
								 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table11_netissued[]" id="table11_netissued" class="form-control" value="<?php if(isset($data->net_issue)) { echo $data->net_issue; } else { echo ''; }?>"  placeholder="" /> </td>
												 
                                </tr>
								
								
							<?php  }  } else {  ?>
                            <tr>
                              <td><input type="text" maxlength="16"  name="table11_srno_from[]" class="form-control"  placeholder="" /> </td> 
	                          <td><input type="text" maxlength="16"  name="table11_srno_to[]" class="form-control"  placeholder="" /> </td>
							 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" onchange="table11onchange(this.value)" id="table11_totalno" name="table11_totalno[]"  class="form-control"  placeholder="" /> </td>
							<td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" onchange="table11onchange(this.value)" id="table11_cancelled" name="table11_cancelled[]" class="form-control"  placeholder="" /> </td> 
							 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table11_netissued[]" id="table11_netissued" class="form-control"  placeholder="" /></td> 
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
								if(!empty($doc_num12))
								{
															
							$i=0;		
						    foreach($doc_num12 as $data) {
								$i++;
                          ?>
                                <tr>
                                 <td><input type="text" maxlength="16"  name="table12_srno_from[]" class="form-control" value="<?php if(isset($data->from)) { echo $data->from; } else { echo ''; }?>"  placeholder="" /> </td>
                                 <td><input type="text" maxlength="16"  name="table12_srno_to[]" class="form-control" value="<?php if(isset($data->to)) { echo $data->to; } else { echo ''; }?>"  placeholder="" /> </td>
								 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" onchange="table12onchange(this.value)" id="table12_totalno" name="table12_totalno[]" class="form-control" value="<?php if(isset($data->totnum)) { echo $data->totnum; } else { echo ''; }?>"  placeholder="" /></td>
								 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" onchange="table12onchange(this.value)" id="table12_cancelled"  name="table12_cancelled[]"class="form-control" value="<?php if(isset($data->cancel)) { echo $data->cancel; } else { echo ''; }?>"  placeholder="" /> </td>
								 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="table12_netissued[]" id="table12_netissued" class="form-control" value="<?php if(isset($data->net_issue)) { echo $data->net_issue; } else { echo ''; }?>"  placeholder="" /> </td>
											 
                                </tr>
								
								
							<?php  }  } else {  ?>

							<tr>
                              <td><input type="text" maxlength="16"  name="table12_srno_from[]" class="form-control"  placeholder="" /> </td> 
	                          <td><input type="text" maxlength="16"  name="table12_srno_to[]" class="form-control"  placeholder="" /> </td>
							 <td><input type="text" maxlength="15" onchange="table12onchange(this.value)" id="table12_totalno" onKeyPress="return  isNumberKey(event,this);" name="table12_totalno[]"  class="form-control"  placeholder="" /> </td>
							<td><input type="text" maxlength="15" onchange="table12onchange(this.value)" id="table12_cancelled" onKeyPress="return  isNumberKey(event,this);" name="table12_cancelled[]" class="form-control"  placeholder="" /> </td> 
							 <td><input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" id="table12_netissued" name="table12_netissued[]" class="form-control"  placeholder="" /></td> 
  							 </tr>
							<?php } ?>                              
							
                                </tbody>
                            </table>
                      			
                        </div>               
							
                         <div class="tableresponsive">
                         <div class="adminformbxsubmit" style="width:100%;"> 
                            <div class="tc">
                                <input type='submit' class="btn btn-success" name='submit' value='submit' id='submit'>
						 </div>
                       </div>
							 	
                          								
                        </div>
                        
                        
                        </div> 
                   
       	  </div>
 		 <div class="clear height40"></div>     
    
    </div>
           <!--CONTENT START HERE-->
		   </form>
        <div class="clear"></div>  	
<script>

function table1onchange(val) {
	
	var element = document.getElementById("table1_cancelled");
	if (element != null) {
	document.getElementById("table1_netissued").value =  document.getElementById("table1_totalno").value - document.getElementById("table1_cancelled").value;
	}
	else
	{
	document.getElementById("table1_netissued").value =  val - 0;

	}
  
  }
</script>
<script>

function table2onchange(val) {
	
	var element = document.getElementById("table2_cancelled");
	if (element != null) {
	document.getElementById("table2_netissued").value =  document.getElementById("table2_totalno").value - document.getElementById("table2_cancelled").value;
	}
	else
	{
	document.getElementById("table2_netissued").value =  val - 0;

	}
  
  }
</script>
<script>

function table3onchange(val) {
	
	var element = document.getElementById("table3_cancelled");
	if (element != null) {
	document.getElementById("table3_netissued").value =  document.getElementById("table3_totalno").value - document.getElementById("table3_cancelled").value;
	}
	else
	{
	document.getElementById("table3_netissued").value =  val - 0;

	}
  
  }
</script>
<script>

function table4onchange(val) {
	
	var element = document.getElementById("table4_cancelled");
	if (element != null) {
	document.getElementById("table4_netissued").value =  document.getElementById("table4_totalno").value - document.getElementById("table4_cancelled").value;
	}
	else
	{
	document.getElementById("table4_netissued").value =  val - 0;

	}
  
  }
</script>
<script>
function table5onchange(val) {
	
	var element = document.getElementById("table5_cancelled");
	if (element != null) {
	document.getElementById("table5_netissued").value =  document.getElementById("table5_totalno").value - document.getElementById("table5_cancelled").value;
	//$(this).closest("tr").find('input[name="table5_netissued[]"]').val(document.getElementById("table5_totalno").value - document.getElementById("table5_cancelled").value);
	}
	else
	{
		
	document.getElementById("table5_netissued").value =  val - 0;
      
	}
  
  }
</script>
<script>
function table6onchange(val) {
	
	var element = document.getElementById("table6_cancelled");
	if (element != null) {
	document.getElementById("table6_netissued").value =  document.getElementById("table6_totalno").value - document.getElementById("table6_cancelled").value;
	}
	else
	{
	document.getElementById("table6_netissued").value =  val - 0;

	}
  
  }
</script>
<script>
function table7onchange(val) {
	
	var element = document.getElementById("table7_cancelled");
	if (element != null) {
	document.getElementById("table7_netissued").value =  document.getElementById("table7_totalno").value - document.getElementById("table7_cancelled").value;
	}
	else
	{
	document.getElementById("table7_netissued").value =  val - 0;

	}
  
  }
</script>
<script>
function table8onchange(val) {
	
	var element = document.getElementById("table8_cancelled");
	if (element != null) {
	document.getElementById("table8_netissued").value =  document.getElementById("table8_totalno").value - document.getElementById("table8_cancelled").value;
	}
	else
	{
	document.getElementById("table8_netissued").value =  val - 0;

	}
  
  }
</script>
<script>
function table9onchange(val) {
	
	var element = document.getElementById("table9_cancelled");
	if (element != null) {
	document.getElementById("table9_netissued").value =  document.getElementById("table9_totalno").value - document.getElementById("table9_cancelled").value;
	}
	else
	{
	document.getElementById("table9_netissued").value =  val - 0;

	}
  
  }
</script>
<script>
function table10onchange(val) {
	
	var element = document.getElementById("table10_cancelled");
	if (element != null) {
	document.getElementById("table10_netissued").value =  document.getElementById("table10_totalno").value - document.getElementById("table10_cancelled").value;
	}
	else
	{
	document.getElementById("table10_netissued").value =  val - 0;

	}
  
  }
</script>
<script>
function table11onchange(val) {
	
	var element = document.getElementById("table11_cancelled");
	if (element != null) {
	document.getElementById("table11_netissued").value =  document.getElementById("table11_totalno").value - document.getElementById("table11_cancelled").value;
	}
	else
	{
	document.getElementById("table11_netissued").value =  val - 0;

	}
  
  }
</script>
<script>
function table12onchange(val) {
	
	var element = document.getElementById("table12_cancelled");
	if (element != null) {
	document.getElementById("table12_netissued").value =  document.getElementById("table12_totalno").value - document.getElementById("table12_cancelled").value;
	}
	else
	{
	document.getElementById("table12_netissued").value =  val - 0;

	}
  
  }
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