<?php						 
					 
$obj_transition = new transition();
 
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
    if($obj_transition->saveGstrTransition2()){
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

    $htmlResponse = $obj_transition->generategst_transitionForm2Html($_GET['id'],$_GET['returnmonth']);
    
    
   
}
if (isset($_GET['action']) && $_GET['action'] == 'downloadInvoice' && isset($_GET['id'])) {

    $htmlResponse = $obj_transition->generategst_transitionForm2Html($_GET['id'],$_GET['returnmonth']);
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
       $sql = "select  *,count(id) as totalinvoice from ".TAB_PREFIX."transition_form where added_by='" . $_SESSION['user_detail']['user_id'] . "' and type='transitionform2' and financial_month like '%" . $returnmonth . "%' and is_deleted='0'  order by id desc limit 0,1";
       $returndata = $obj_transition->get_results($sql);
	   $sql = "select  *,count(id) as totalinvoice from ".TAB_PREFIX."transition_form where added_by='" . $_SESSION['user_detail']['user_id'] . "' and type='transitionform2' and financial_month like '%" . $returnmonth . "%' and is_deleted='0'  order by id desc limit 0,1";
       $returndata1 = $obj_transition->get_results($sql);
	   $sql = "select * from " . TAB_PREFIX . "client_kyc where added_by='" . $_SESSION['user_detail']['user_id'] . "' order by id desc limit 0,1";
	   $clientdata = $obj_transition->get_results($sql);
	   $client_gstin_number;
	   $client_name;
	   $taxable_name='';
	   
	   if(count($clientdata) > 0 )
	   {
		   $client_gstin_number = $clientdata[0]->gstin_number;
		   $client_name = $clientdata[0]->name;
		  
		   
	   }
		if($returndata1[0]->totalinvoice > 0)
		{
			 $taxable_name=$returndata1[0]->taxable_name;
		$arr = $returndata1[0]->gstr_transition_data;
		$arr1= base64_decode($arr);
		$transition_arr = json_decode($arr1);	
		$a4_hsn='';
		$a4_unit='';
		$a4_qty1='';
		$a4_qty2='';
		$a4_value='';
		$a4_centraltax='';
		$a4_integrated='';
		$a4_itcallowed='';
		$a4_qty3='';
        $b4_hsn='';
		$b4_unit='';
		$b4_qty1='';
		$b4_qty2='';
		$b4_value='';
		$b4_centraltax='';
		$b4_integrated='';
		$b4_itcallowed='';
		$b4_qty3='';
		

		
		foreach($transition_arr as $item)
		{
			
			$a4_hsn=$item->a4_hsn;
			$a4_unit=$item->a4_unit;
			$a4_qty1=$item->a4_qty1;
			$a4_qty2=$item->a4_qty2;
			$a4_value=$item->a4_value;
			$a4_centraltax=$item->a4_centraltax;
			$a4_integrated=$item->a4_integrated;
			$a4_itcallowed=$item->a4_itcallowed;
			$a4_qty3=$item->a4_qty3;
            $b4_hsn=$item->b4_hsn;
			$b4_unit=$item->b4_unit;
			$b4_qty1=$item->b4_qty1;
			$b4_qty2=$item->b4_qty2;
			$b4_value=$item->b4_value;
			$b4_centraltax=$item->b4_centraltax;
			$b4_integrated=$item->b4_integrated;
			$b4_itcallowed=$item->b4_itcallowed;
			$b4_qty3=$item->b4_qty3;	
            			
		}
		}
		 
	
		 
		
	  
	   ?>
       <div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
       			<div class="col-md-12 col-sm-12 col-xs-12">
               
                	<div class="col-md-6 col-sm-6 col-xs-12 heading"><h1>GST-Transition Form</h1></div>
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
				<div class="tab">
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
					  <div class="row">
                     	 <div class="col-md-12 col-sm-12 col-xs-12 form-group">

                            <label>1.GSTIN-<span class="starred"></span></label>
							 <label><strong><?php echo $client_gstin_number; ?></strong></label>
						     </div>
							 
							    
							   <div class="col-md-4 col-sm-4 col-xs-12 form-group">

                            <label>2.Name of taxable person-<span class="starred"></span></label>
							 <input type="text" maxlength="100" id="taxable_name"  name="taxable_name" value="<?php if(isset($returndata1[0]->taxable_name)) { echo $returndata1[0]->taxable_name; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
						    
							   </div><div class="clear"></div>
							     <div class="col-md-12 col-sm-12 col-xs-12 form-group">

                            <label>3.Tax Period: month Year-<span class="starred"></span></label>
							 <label><strong><?php echo $returnmonth; ?></strong></label>
						 
							   </div>
							   
							    
                            
							   </div>
                    	<div class="greyheading">4.Details of Input held on stock on appointment date of in which respect of which he is not in possession of any invoice/document evidencing payment of tax forward to electronic credit ledger</div>
					       <div class="tableresponsive">
						   <form method="post" enctype="multipart/form-data" id='form'>
                            <table  class="table  tablecontent tablecontent2 bordernone" id='table4a'>
                                <thead>
                                <tr>
                                <th>HSN at 6 Digit level</th>
                                <th>Unit</th>
                                <th>Qty</th>
                                <th>Qty.</th>
                                <th>Value</th>
                                <th>CentralTax</th>
							    <th>IntegratedTax</th>
							    <th>Itc Allowed</th>
								<th>Qty</th>
								
                                </tr>
                                </thead>
                                
                                <tbody>
								<?php
								if(!empty($returndata1[0]->totalinvoice) && ($returndata1[0]->totalinvoice > 0))
								{
								$a4_hsn=(explode(",",$a4_hsn));
								$a4_unit=(explode(",",$a4_unit));
								$a4_qty1=(explode(",",$a4_qty1));
								$a4_qty2=(explode(",",$a4_qty2));
								$a4_value=(explode(",",$a4_value));
								$a4_centraltax=(explode(",",$a4_centraltax));
								$a4_integrated=(explode(",",$a4_integrated));
								$a4_itcallowed=(explode(",",$a4_itcallowed));
								$a4_qty3=(explode(",",$a4_qty3));
								
								
								
			                    $start='';
								if(sizeof($a4_hsn) > 1)
								{
									$start = $a4_hsn;
									
								}
								elseif(sizeof($a4_unit) > 1)
								{
									 $start = $a4_unit;
								}
								elseif(sizeof($a4_qty1) > 1)
								{
									 $start = $a4_qty1;
									
								}
								elseif(sizeof($a4_qty2) > 1)
								{
									$start = $a4_qty2;
								}
								elseif(sizeof($a4_value) > 1)
								{
									$start = $a4_value;
								}
								elseif(sizeof($a4_centraltax) > 1)
								{
									$start = $a4_centraltax;
								}
								elseif(sizeof($a4_integrated) > 1)
								{
									$start = $a4_integrated;
								}
								elseif(sizeof($a4_itcallowed) > 1)
								{
									$start = $a4_itcallowed;
								}
								elseif(sizeof($a4_qty3) > 1)
								{
									$start = $a4_qty3;
							     }			
													
								else{
									$start = $a4_hsn;
								}
								
									
						       $taxreturn=0;
							   $dateoffilling_return=0;
							   $balance_cenvat_credit=0;
							   $cenvat_credit_admissible=0;
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
									 <label><?php if(isset($a4_hsn[$i])) { echo $a4_hsn[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="4a_hsn[]"
 class="form-control" value="<?php if(isset($a4_hsn[$i])) { echo $a4_hsn[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                 <td>
								 <?php
								 if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a4_unit[$i])) { echo $a4_unit[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15"  name="4a_unit[]"
 class="form-control" value="<?php if(isset($a4_unit[$i])) { echo $a4_unit[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a4_qty1[$i])) { echo $a4_qty1[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="4a_qty1[]"
 class="form-control" value="<?php if(isset($a4_qty1[$i])) { echo $a4_qty1[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a4_qty2[$i])) { echo $a4_qty2[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);"  name="4a_qty2[]"
 class="form-control" value="<?php if(isset($a4_qty2[$i])) { echo $a4_qty2[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a4_value[$i])) { echo $a4_value[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="4a_value[]" value="<?php if(isset($a4_value[$i])) { echo $a4_value[$i]; } else { echo ''; }?>"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a4_centraltax[$i])) { echo $a4_centraltax[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a4_centraltax[$i])) { echo $a4_centraltax[$i]; } else { echo ''; }?>" name="4a_centraltax[]"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a4_integrated[$i])) { echo $a4_integrated[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a4_integrated[$i])) { echo $a4_integrated[$i]; } else { echo ''; }?>" name="4a_integrated[]"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a4_itcallowed[$i])) { echo $a4_itcallowed[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a4_itcallowed[$i])) { echo $a4_itcallowed[$i]; } else { echo ''; }?>" name="4a_itcallowed[]"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a4_qty3[$i])) { echo $a4_qty3[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a4_qty3[$i])) { echo $a4_qty3[$i]; } else { echo ''; }?>" name="4a_qty3[]"  class="form-control"  placeholder="" /> 
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
								 </td><?php } else { ?>
                               <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
								 <?php } } ?>								 
                                </tr>
								
								<?php if(($sno==sizeof($start)) && ($returndata[0]->final_submit==1))  
								{
									//echo '<tr><td></td><td>Total</td><td>'.$taxreturn.'</td><td>'.$dateoffilling_return.'</td><td>'.$balance_cenvat_credit.'</td><td>'.$cenvat_credit_admissible.'</td></tr>';
									
								}
									}  } else { ?>
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="4a_hsn[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="4a_unit[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="4a_qty1[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="4a_qty2[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="4a_value[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="4a_centraltax[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="4a_integrated[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="4a_itcallowed[]"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="4a_qty3[]"  class="form-control"  placeholder="" /> 
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
								<tr style="display:none;"><td></td><td>Total</td><td></td><td></td><td></td><td></td></tr>
								           
                                                       
                              
                                     
							
                                </tbody>
                            </table>
                        </div>
                    <div class="greyheading">5.Credit on State Tax on the stock mentioned in 4 above(To be there only in states having vat at single point</div>
					       <div class="tableresponsive">
						   <form method="post" enctype="multipart/form-data" id='form'>
                            <table  class="table  tablecontent tablecontent2 bordernone" id='table4b'>
                                <thead>
								 <tr>
								 <th colspan="3" align="center">Opening stock for the tax period</th>
								  <th colspan="5" align="center">Outward Supply made</th>
								  <th>Closing balance</th>
								 </tr>
                                <tr>
                                <th>HSN at 6 Digit level</th>
                                <th>Unit</th>
                                <th>Qty</th>
                                <th>Qty.</th>
                                <th>Value</th>
                                <th>CentralTax</th>
							    <th>IntegratedTax</th>
							    <th>Itc Allowed</th>
								<th>Qty</th>
								  </tr>
                                </thead>
                                
                                <tbody>
								<?php
								if(!empty($returndata1[0]->totalinvoice) && ($returndata1[0]->totalinvoice > 0))
								{
								$b4_hsn=(explode(",",$b4_hsn));
								$b4_unit=(explode(",",$b4_unit));
								$b4_qty1=(explode(",",$b4_qty1));
								$b4_qty2=(explode(",",$b4_qty2));
								$b4_value=(explode(",",$b4_value));
								$b4_centraltax=(explode(",",$b4_centraltax));
								$b4_integrated=(explode(",",$b4_integrated));
								$b4_itcallowed=(explode(",",$b4_itcallowed));
								$b4_qty3=(explode(",",$b4_qty3));
								
								
								
			                    $start='';
								if(sizeof($b4_hsn) > 1)
								{
									$start = $b4_hsn;
									
								}
								elseif(sizeof($b4_unit) > 1)
								{
									 $start = $b4_unit;
								}
								elseif(sizeof($b4_qty1) > 1)
								{
									 $start = $b4_qty1;
									
								}
								elseif(sizeof($b4_qty2) > 1)
								{
									$start = $b4_qty2;
								}
								elseif(sizeof($b4_value) > 1)
								{
									$start = $b4_value;
								}
								elseif(sizeof($b4_centraltax) > 1)
								{
									$start = $b4_centraltax;
								}
								elseif(sizeof($b4_integrated) > 1)
								{
									$start = $b4_integrated;
								}
								elseif(sizeof($b4_itcallowed) > 1)
								{
									$start = $b4_itcallowed;
								}
								elseif(sizeof($b4_qty3) > 1)
								{
									$start = $b4_qty3;
							     }			
													
								else{
									$start = $b4_hsn;
								}
								
									
						       $taxreturn=0;
							   $dateoffilling_return=0;
							   $balance_cenvat_credit=0;
							   $cenvat_credit_admissible=0;
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
									 <label><?php if(isset($b4_hsn[$i])) { echo $b4_hsn[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="4b_hsn[]"
 class="form-control" value="<?php if(isset($b4_hsn[$i])) { echo $b4_hsn[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                 <td>
								 <?php
								 if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b4_unit[$i])) { echo $b4_unit[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15"  name="4b_unit[]"
 class="form-control" value="<?php if(isset($b4_unit[$i])) { echo $b4_unit[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b4_qty1[$i])) { echo $b4_qty1[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="4b_qty1[]"
 class="form-control" value="<?php if(isset($b4_qty1[$i])) { echo $b4_qty1[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b4_qty2[$i])) { echo $b4_qty2[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);"  name="4b_qty2[]"
 class="form-control" value="<?php if(isset($b4_qty2[$i])) { echo $b4_qty2[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b4_value[$i])) { echo $b4_value[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="4b_value[]" value="<?php if(isset($b4_value[$i])) { echo $b4_value[$i]; } else { echo ''; }?>"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b4_centraltax[$i])) { echo $b4_centraltax[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b4_centraltax[$i])) { echo $b4_centraltax[$i]; } else { echo ''; }?>" name="4b_centraltax[]"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b4_integrated[$i])) { echo $b4_integrated[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b4_integrated[$i])) { echo $b4_integrated[$i]; } else { echo ''; }?>" name="4b_integrated[]"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b4_itcallowed[$i])) { echo $b4_itcallowed[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b4_itcallowed[$i])) { echo $b4_itcallowed[$i]; } else { echo ''; }?>" name="4b_itcallowed[]"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b4_qty3[$i])) { echo $b4_qty3[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b4_qty3[$i])) { echo $b4_qty3[$i]; } else { echo ''; }?>" name="4b_qty3[]"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <?php if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 0))
								 {   
							        if($i==0){
									 ?>
                                    <td>
									 <a class="addMoreInvoice add-table4b"  href="javascript:void(0)">
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
									}  } else { ?>
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="4b_hsn[]"
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
									 <input type="text" maxlength="15"  name="4b_unit[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="4b_qty1[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="4b_qty2[]"
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
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="4b_value[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="4b_centraltax[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="4b_integrated[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="4b_itcallowed[]"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="4b_qty3[]"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                    <td>
									 <a class="addMoreInvoice add-table4b"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
										</td>                  
                                </tr>
								<?php } ?>
								<tr style="display:none;"><td></td><td>Total</td><td></td><td></td><td></td><td></td></tr>
								           
                                                       
                              
                                     
							
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
								<div>
								<?php if($returndata[0]->terms_condition==1) {
									?>
								<input type="checkbox" class='form' value="1" checked name="accept" />
								<?php } else { ?>
								<input type="checkbox" class='form' value="1"  name="accept" />
								<?php } ?>
								
								I here by solemnly affirm and declare that the information given herein above is true and correct to the best of my knowledge and belief and nothing has been concealed thereform
								</div>
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
		
        $(".add-table4a").click(function(){
       
			
		    
            var markup = "<tr><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='4a_hsn[]'/></td><td><input type='text'  class='required form-control' name='4a_unit[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='4a_qty1[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='4a_qty2[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='4a_value[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='4a_centraltax[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='4a_integrated[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='4a_itcallowed[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='4a_qty[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
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
		
        $(".add-table4b").click(function(){
       
			
		    
            var markup = "<tr><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='4b_hsn[]'/></td><td><input type='text'  class='required form-control' name='4b_unit[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='4b_qty1[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='4b_qty2[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='4b_value[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='4b_centraltax[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='4b_integrated[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='4b_itcallowed[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='4b_qty[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table4b').append(markup);
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