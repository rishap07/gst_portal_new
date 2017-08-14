<?php
$obj_client = new client();
$obj_return = new gstr3b();
$returnmonth = date('Y-m');
if(isset($_POST['returnmonth']))
{
    $returnmonth = $_POST['returnmonth'];
	$obj_client->redirect(PROJECT_URL."/?page=return_gstr3b_file&returnmonth=".$returnmonth);
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

   

    if($obj_return->saveGstr3b()){
		$obj_return->redirect(PROJECT_URL."/?page=return_gstr3b_file&returnmonth=".$returnmonth);
        //$obj_master->redirect(PROJECT_URL."/?page=master_receiver");
    }
}
//like '%" . $returnmonth . "%'
      // $sql = "select  *,count(return_id) as totalinvoice from gst_client_return_gstr3b where added_by='" . $_SESSION['user_detail']['user_id'] . "' and month(return_filling_date)='".date('m')."' order by return_id desc limit 0,1";
	   $sql = "select  *,count(return_id) as totalinvoice from ".TAB_PREFIX."client_return_gstr3b where added_by='" . $_SESSION['user_detail']['user_id'] . "' and financial_month like '%" . $returnmonth . "%' order by return_id desc limit 0,1";
        $status =0;
     
       $returndata = $obj_return->get_results($sql);
	   ?>
	  
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>

  <script type="text/javascript">
        $(document).ready(function() {
            $('#place_of_supply_unregistered_person').multiselect();
        });
   </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#place_of_supply_taxable_person').multiselect();
        });
   </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#place_of_supply_uin_holder').multiselect();
        });
   </script>
       <div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
       			<div class="col-md-12 col-sm-12 col-xs-12">
               
                	<div class="col-md-6 col-sm-6 col-xs-12 heading"><h1>GSTR-3B</h1></div>
                    <div class="col-md-6 col-sm-6 col-xs-12 text-right breadcrumb-nav"><a href="#">Home</a>
					<i class="fa fa-angle-right" aria-hidden="true"></i>  <a href="#">File Return</a> <i class="fa fa-angle-right" aria-hidden="true"></i> <span class="active">GSTR-3B Filing</span> </div>
                     <div class="whitebg formboxcontainer">
					  <?php $obj_client->showErrorMessage(); ?>
			<?php $obj_client->showSuccessMessge(); ?>
			<?php $obj_client->unsetMessage(); ?>

					  <div class="pull-right rgtdatetxt">
                                <form method='post' name='form2'>
                                    Month Of Return
                                    <?php
                                    $dataQuery = "SELECT DATE_FORMAT(invoice_date,'%Y-%m') AS niceDate FROM gst_client_invoice group by nicedate";
                                    $dataRes = $obj_client->get_results($dataQuery);
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
                       <form method="post" enctype="multipart/form-data" id='form'> 
                    	<div class="greyheading">3.1 Details of Outward Supplies and inward supplies liable to reverse charge</div>
                           <div class="tableresponsive">
						   <form method="post" enctype="multipart/form-data" id='form'>
                            <table  class="table  tablecontent tablecontent2 bordernone">
                                <thead>
                                <tr>
                                <th>Nature of Supplies</th>
                                <th>Total Taxable value</th>
                                <th>Integrated Tax</th>
                                <th>Central Tax</th>
                                <th>State/UT Tax</th>
                                <th>Cess</th>
                                </tr>
                                </thead>
                                
                                <tbody>
                                <tr>
                                <td class="lftheading" width="25%">(a) Outward taxable supplies (other than zero rated, nil rated and exempted)</td>
                                 <td>
								 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->total_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="total_tax_value_supplya" value="<?php echo $returndata[0]->total_tax_value_supplya; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="integrated_tax_value_supplya" value="<?php echo $returndata[0]->integrated_tax_value_supplya; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="central_tax_value_supplya" value="<?php echo $returndata[0]->central_tax_value_supplya; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="state_tax_value_supplya"  value="<?php echo $returndata[0]->state_tax_value_supplya; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_value_supplya" value="<?php echo $returndata[0]->cess_tax_value_supplya; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                               
                                   
                                    
                           
                                </tr>
                                
                                <tr>
                                <td class="lftheading" width="20%">(b) Outward taxable supplies (zero rated )</td>
								 <td>
								 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->total_tax_value_supplyb; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="total_tax_value_supplyb" value="<?php echo $returndata[0]->total_tax_value_supplyb; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td>
								 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_value_supplyb; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="integrated_tax_value_supplyb" value="<?php echo $returndata[0]->integrated_tax_value_supplyb; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td>
								 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_value_supplyb; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="central_tax_value_supplyb" value="<?php echo $returndata[0]->central_tax_value_supplyb; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_value_supplyb; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="state_tax_value_supplyb" value="<?php echo $returndata[0]->state_tax_value_supplyb; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td>
								 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplyb; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_value_supplyb" value="<?php echo $returndata[0]->cess_tax_value_supplyb; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>                             
                                                            
                                    
                            
                                </tr>
                                
                                <tr>
                                <td class="lftheading" width="20%">(c) Other outward supplies (Nil rated, exempted)</td>
								 <td>
								 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->total_tax_value_supplyc; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="total_tax_value_supplyc" value="<?php echo $returndata[0]->total_tax_value_supplyc; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								 <td>
								 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_value_supplyc; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="integrated_tax_value_supplyc" value="<?php echo $returndata[0]->integrated_tax_value_supplyc; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								  <td>
								 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_value_supplyc; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="central_tax_value_supplyc" value="<?php echo $returndata[0]->central_tax_value_supplyc; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								  <td>
								 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_value_supplyc; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="state_tax_value_supplyc" value="<?php echo $returndata[0]->state_tax_value_supplyc; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								  <td>
								 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplyc; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_value_supplyc" value="<?php echo $returndata[0]->cess_tax_value_supplyc; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>              
                               
                                
                                  
                                  
                                </tr>
                                
                                 <tr>
                                <td class="lftheading" width="20%">(d) Inward supplies (liable to reverse charge)</td>
								 <td>
								 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->total_tax_value_supplyd; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="total_tax_value_supplyd" value="<?php echo $returndata[0]->total_tax_value_supplyd; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								  <td>
								 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_value_supplyd; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="integrated_tax_value_supplyd" value="<?php echo $returndata[0]->integrated_tax_value_supplyd; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								 <td>
								 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_value_supplyd; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="central_tax_value_supplyd" value="<?php echo $returndata[0]->central_tax_value_supplyd; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								  <td>
								 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_value_supplyd; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="state_tax_value_supplyd" value="<?php echo $returndata[0]->state_tax_value_supplyd; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                 <td>
								 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplyd; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_value_supplyd" value="<?php echo $returndata[0]->cess_tax_value_supplyd; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 	          
                               
                              
                                   
                                    
                                     
								</tr>
                                 <tr>
                                <td class="lftheading" width="20%">(e) Non-GST outward supplies</td>
                               
								   <td>
								 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->total_tax_value_supplye; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="total_tax_value_supplye" value="<?php echo $returndata[0]->total_tax_value_supplye; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 	   
								  <td>
								 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_value_supplye; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="integrated_tax_value_supplye" value="<?php echo $returndata[0]->integrated_tax_value_supplye; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								  <td>
								 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_value_supplye; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="central_tax_value_supplye" value="<?php echo $returndata[0]->central_tax_value_supplye; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
                                <td>
								 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_value_supplye; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="state_tax_value_supplye" value="<?php echo $returndata[0]->state_tax_value_supplye; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
                                <td>
								 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplye; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_value_supplye" value="<?php echo $returndata[0]->cess_tax_value_supplye; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 								 
								                                                                  
                                   
                                     
								</tr>
                                </tbody>
                            </table>
                        </div>
                        
                        	<div class="greyheading">3.2 Of the supplies shown in 3.1 (a) above, details of inter-State supplies made to unregistered persons,
composition taxable persons and UIN holders</div>
                            
                            <div class="tableresponsive" id="unregistered">
                            <table  class="table  tablecontent tablecontent2 bordernone" id="table1">
                                <thead>
                                
                                <tr>
                                <th></th>
                                <th>Place of Supply (State/UT)</th>
                                <th>Total Taxable value</th>
                                <th>Amount of Integrated Tax</th>
								
                                </tr>
                                </thead>
                                
                                <tbody>
								<?php
							 $sql = "select  *,count(returnid) as totalinvoice from ".TAB_PREFIX."place_of_supply where added_by='" . $_SESSION['user_detail']['user_id'] . "' and financial_month like '%" . $returnmonth . "%' and type='0'   order by id desc limit 0,1";
                             $editflag=0;
                            $return_a = $obj_return->get_results($sql);
							if($return_a[0]->totalinvoice > 0 )
							{
								 if (isset($return_a[0]->totalinvoice)) {
									 $editflag=1;
									    $str1  = substr($return_a[0]->place_of_supply,0,-1);
										$str1 = (explode(",",$str1));
										$str2  = substr($return_a[0]->totaltaxable_value,0,-1);
										$str2 = (explode(",",$str2));
										$str3  = substr($return_a[0]->amount_of_integrated_tax,0,-1);
										$str3 = (explode(",",$str3));
								
						
							  for($i=0;$i < sizeof($str1); $i++) {
                               
							  
             
                           ?>
                                    <tr>
                                    <td class="lftheading" width="25%">Supplies made to Unregistered Persons</td>
                                     <td>
										 <div id='TextBoxesGroup3'>
	                               <div id="TextBoxDiv3">
								   <div class="input_fields_wrap3">
						<select  name="place_of_supply_unregistered_person[]"   id='place_of_supply_unregistered_person' class="required form-control">
							<?php $dataSupplyStateArrs = $obj_client->get_results("select * from ".$obj_client->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
							<?php if(!empty($dataSupplyStateArrs)) { ?>
								<option value=''>Select Place Of Supply</option>
								<?php foreach($dataSupplyStateArrs as $dataSupplyStateArr) { ?>
								<option value='<?php echo $dataSupplyStateArr->state_id; ?>' <?php
                                    
										
										if($dataSupplyStateArr->state_id==$str1[$i])
										{
                                        echo "selected='selected'";
                                        }
									
                                    ?>><?php echo $dataSupplyStateArr->state_name . " (" . $dataSupplyStateArr->state_tin . ")"; ?></option>
								<?php } ?>
							<?php } ?>
						</select> </div></div></div>
									 </td>
									  <td>
								 <?php
								 if (!isset($return_a[0]->totalinvoice)) {
							
									
									 ?>
									 <label><?php echo $returndata[0]->total_taxable_value_unregistered_person; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								<div id='TextBoxesGroup'>
								<div id="TextBoxDiv1">
								
								<div class="input_fields_wrap1">
	
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="total_taxable_value_unregistered_person[]" id ="total_taxable_value_unregistered_person[]" value="<?php if(isset($return_a[0]->totalinvoice)) { echo $str2[$i]; } else { echo 0.00; } ?>"
 class="form-control"  placeholder="" /></div></div>
</div>
								 <?php } ?>
                                 </td> 	
                                <td>
								 <?php
								if (!isset($return_a[0]->totalinvoice)) {
							
								
									 ?>
									 <label><?php echo $returndata[0]->amount_of_integrated_tax_unregistered_person; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <div id='TextBoxesGroup2'>
	<div id="TextBoxDiv2">
	<div class="input_fields_wrap2">
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="amount_of_integrated_tax_unregistered_person[]" id="amount_of_integrated_tax_unregistered_person" value="<?php if(isset($return_a[0]->totalinvoice)) { echo $str3[$i]; } else { echo 0.00; } ?>"
 class="form-control"  placeholder="" /> </div></div></div>
								 <?php } ?>
                                 </td> 	
                                  		<?php if($i==0)
										{
                                     ?>											
                                     <td>
									  <a class="addMoreInvoice add-row1"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
										</td><?php } else { ?>	
									<td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
										<?php } ?>
						</tr><?php } } } else {
						
							?>
							 <tr>
                                    <td class="lftheading" width="25%">Supplies made to Unregistered Persons</td>
                                     <td>
										 <div id='TextBoxesGroup3'>
	                               <div id="TextBoxDiv3">
								   <div class="input_fields_wrap3">
						<select  name="place_of_supply_unregistered_person[]"   id='place_of_supply_unregistered_person' class="required form-control">
							<?php $dataSupplyStateArrs = $obj_client->get_results("select * from ".$obj_client->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
							<?php if(!empty($dataSupplyStateArrs)) { ?>
								<option value=''>Select Place Of Supply</option>
								<?php foreach($dataSupplyStateArrs as $dataSupplyStateArr) { ?>
								<option value='<?php echo $dataSupplyStateArr->state_id; ?>' <?php
                                     if (isset($returndata[0]->place_of_supply_uin_holder)) {
										$str = (explode(",",$returndata[0]->place_of_supply_uin_holder));

                                        foreach($str as $s)
                                    {
										if($dataSupplyStateArr->state_id==$s)
										{
                                        echo "selected='selected'";
                                        }
									}
									}
                                    ?>><?php echo $dataSupplyStateArr->state_name . " (" . $dataSupplyStateArr->state_tin . ")"; ?></option>
								<?php } ?>
							<?php } ?>
						</select> </div></div></div>
									 </td>
									  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
							
									
									 ?>
									 <label><?php echo $returndata[0]->total_taxable_value_unregistered_person; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								<div id='TextBoxesGroup'>
								<div id="TextBoxDiv1">
								
								<div class="input_fields_wrap1">
	
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="total_taxable_value_unregistered_person[]" id ="total_taxable_value_unregistered_person[]" value="<?php if(isset($returndata[0]->total_taxable_value_unregistered_person)) { echo $returndata[0]->total_taxable_value_unregistered_person; } else { echo 0.00; } ?>"
 class="form-control"  placeholder="" /></div></div>
</div>
								 <?php } ?>
                                 </td> 	
                                <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
								
									 ?>
									 <label><?php echo $returndata[0]->amount_of_integrated_tax_unregistered_person; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <div id='TextBoxesGroup2'>
	<div id="TextBoxDiv2">
	<div class="input_fields_wrap2">
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="amount_of_integrated_tax_unregistered_person[]" id="amount_of_integrated_tax_unregistered_person" value="<?php if(isset($returndata[0]->amount_of_integrated_tax_unregistered_person)) { echo $returndata[0]->amount_of_integrated_tax_unregistered_person; } else { echo 0.00; } ?>"
 class="form-control"  placeholder="" /> </div></div></div>
								 <?php } ?>
                                 </td> 										                       
                                     <td>
									  <a class="addMoreInvoice add-row1"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
    
                                    </td>									 
						</tr>
						<?php } 
						?>
                                   </tbody>
                            </table></div>
							   <div class="tableresponsive">
						  <table  class="table  tablecontent tablecontent2 bordernone" id="table2">
						  
							<?php
							 $sql = "select  *,count(returnid) as totalinvoice from ".TAB_PREFIX."place_of_supply where added_by='" . $_SESSION['user_detail']['user_id'] . "' and financial_month like '%" . $returnmonth . "%' and type='1'   order by id desc limit 0,1";
                             $editflag=0;
                            $return_a = $obj_return->get_results($sql);
							if($return_a[0]->totalinvoice > 0 )
							{
								 if (isset($return_a[0]->totalinvoice)) {
									 $editflag=1;
									    $str1  = substr($return_a[0]->place_of_supply,0,-1);
										$str1 = (explode(",",$str1));
										$str2  = substr($return_a[0]->totaltaxable_value,0,-1);
										$str2 = (explode(",",$str2));
										$str3  = substr($return_a[0]->amount_of_integrated_tax,0,-1);
										$str3 = (explode(",",$str3));
								
						
							  for($i=0;$i < sizeof($str1); $i++) {
                               
							  
             
                           ?>
                                     <tr>
                                    <td class="lftheading" width="25%">Supplies made to Composition Taxable Persons</td>
                                     <td>
									 
						<select name='place_of_supply_taxable_person[]'  id='place_of_supply_taxable_person' class="required form-control">
							<?php $dataSupplyStateArrs = $obj_client->get_results("select * from ".$obj_client->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
							<?php if(!empty($dataSupplyStateArrs)) { ?>
								<option value=''>Select Place Of Supply</option>
								<?php foreach($dataSupplyStateArrs as $dataSupplyStateArr) { ?>
								<option value='<?php echo $dataSupplyStateArr->state_id; ?>' <?php
                                    
										
										if($dataSupplyStateArr->state_id==$str1[$i])
										{
                                        echo "selected='selected'";
                                        }
									
                                    ?>><?php echo $dataSupplyStateArr->state_name . " (" . $dataSupplyStateArr->state_tin . ")"; ?></option>
								<?php } ?>
							<?php } ?>
						</select>
									 </td>
									  <td>
								 <?php
								  if (!isset($return_a[0]->totalinvoice)) {
							
									 ?>
									 <label><?php echo $returndata[0]->total_taxable_value_taxable_person; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="total_taxable_value_taxable_person[]"
 class="form-control" value="<?php if(isset($return_a[0]->totalinvoice)) { echo $str2[$i]; } else { echo '0.00'; } ?>"  placeholder="" /> 
								 <?php } ?>
                                 </td> 	
                                  <td>
								 <?php
								 if (!isset($return_a[0]->totalinvoice)) {
							
									 ?>
									 <label><?php echo $returndata[0]->amount_of_integrated_tax_taxable_person; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="amount_of_integrated_tax_taxable_person[]"
 class="form-control" value="<?php if(isset($return_a[0]->totalinvoice)) { echo $str3[$i]; } else { echo '0.00'; } ?>"  placeholder="" /> 
								 <?php } ?>
                                 </td> 									 
                                      
                                   <?php if($i==0)
										{
                                     ?>											
                                     <td>
									 <a class="addMoreInvoice add-row2"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
										</td><?php } else { ?>	
									<td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>							 
										<?php } ?>
                                    </tr>
									  
                                   </tr><?php } } } else {
							?>
							  <tr>
                                  
                                     <td>
									 
						<select name='place_of_supply_taxable_person[]'  id='place_of_supply_taxable_person' class="required form-control">
								<?php $dataSupplyStateArrs = $obj_client->get_results("select * from ".$obj_client->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
							<?php if(!empty($dataSupplyStateArrs)) { ?>
								<option value=''>Select Place Of Supply</option>
								<?php foreach($dataSupplyStateArrs as $dataSupplyStateArr) { ?>
								<option value='<?php echo $dataSupplyStateArr->state_id; ?>' <?php
                                     if (isset($returndata[0]->place_of_supply_uin_holder)) {
										$str = (explode(",",$returndata[0]->place_of_supply_uin_holder));

                                        foreach($str as $s)
                                    {
										if($dataSupplyStateArr->state_id==$s)
										{
                                        echo "selected='selected'";
                                        }
									}
									}
                                    ?>><?php echo $dataSupplyStateArr->state_name . " (" . $dataSupplyStateArr->state_tin . ")"; ?></option>
								<?php } ?>
							<?php } ?>
						</select>
									 </td>
									  <td>
								 <?php
								   if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->total_taxable_value_taxable_person; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="total_taxable_value_taxable_person[]"
 class="form-control" value="<?php if(isset($returndata[0]->total_taxable_value_taxable_person)) { echo $returndata[0]->total_taxable_value_taxable_person; } else { echo '0.00'; } ?>"  placeholder="" /> 
								 <?php } ?>
                                 </td> 	
                                  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->amount_of_integrated_tax_taxable_person; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="amount_of_integrated_tax_taxable_person[]"
 class="form-control" value=""  placeholder="" /> 
								 <?php } ?>
                                 </td> 									 
                                      
                                    <td>
									 <a class="addMoreInvoice add-row2"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a></td>
                                      </tr> <?php } ?></table></div>
									   <div class="tableresponsive">
									  <table  class="table  tablecontent tablecontent2 bordernone" id="table3">
									  <tbody>
                                 
								   <?php
							 $sql = "select  *,count(returnid) as totalinvoice from ".TAB_PREFIX."place_of_supply where added_by='" . $_SESSION['user_detail']['user_id'] . "' and financial_month like '%" . $returnmonth . "%' and type='2'   order by id desc limit 0,1";
                             $editflag=0;
                            $return_a = $obj_return->get_results($sql);
							if($return_a[0]->totalinvoice > 0 )
							{
								 if (isset($return_a[0]->totalinvoice)) {
									 $editflag=1;
									    $str1  = substr($return_a[0]->place_of_supply,0,-1);
										$str1 = (explode(",",$str1));
										$str2  = substr($return_a[0]->totaltaxable_value,0,-1);
										$str2 = (explode(",",$str2));
										$str3  = substr($return_a[0]->amount_of_integrated_tax,0,-1);
										$str3 = (explode(",",$str3));
								
						
							  for($i=0;$i < sizeof($str1); $i++) {
                               
							  
             
                           ?>
								   <tr>
                                    <td class="lftheading" width="25%">Supplies made to UIN holders</td>
                                     <td>
									 
									
						<select name='place_of_supply_uin_holder[]'  id='place_of_supply_uin_holder' class="required form-control">
							<?php $dataSupplyStateArrs = $obj_client->get_results("select * from ".$obj_client->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
							<?php if(!empty($dataSupplyStateArrs)) { ?>
								<option value=''>Select Place Of Supply</option>
								<?php foreach($dataSupplyStateArrs as $dataSupplyStateArr) { ?>
								<option value='<?php echo $dataSupplyStateArr->state_id; ?>' <?php
                                    
										
										if($dataSupplyStateArr->state_id==$str1[$i])
										{
                                        echo "selected='selected'";
                                        }
									
                                    ?>><?php echo $dataSupplyStateArr->state_name . " (" . $dataSupplyStateArr->state_tin . ")"; ?></option>
								<?php } ?>
							<?php } ?>
						</select>
									 </td>
									  <td>
								 <?php
								 if (!isset($return_a[0]->totalinvoice)) {
							
									 ?>
									
									 <label><?php echo $returndata[0]->total_taxable_value_uin_holder; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="total_taxable_value_uin_holder[]"
 class="form-control" value="<?php if(isset($return_a[0]->totalinvoice)) { echo $str2[$i]; } else { echo '0.00'; } ?>"   placeholder="" /> 
								 <?php } ?>
                                 </td>
                                 <td>
								 <?php
								 if (!isset($return_a[0]->totalinvoice)) {
							
									 ?>
									 <label><?php echo $returndata[0]->amount_of_integrated_uin_holder; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="amount_of_integrated_uin_holder[]"
 class="form-control" value="<?php if(isset($return_a[0]->totalinvoice)) { echo $str3[$i]; } else { echo '0.00'; } ?>" placeholder="" /> 
								 <?php } ?>
                                 </td> 										 
                                     <?php if($i==0)
										{
                                     ?>											
                                     <td>
									  <a class="addMoreInvoice add-row3"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
										</td><?php } else { ?>	
									<td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
										<?php } ?>
                                      
                                   </tr><?php } } } else {
							?>
							<tr>
                                    <td class="lftheading" width="25%">Supplies made to UIN holders</td>
                                     <td>
									 
									
						<select name='place_of_supply_uin_holder[]'  id='place_of_supply_uin_holder' class="required form-control">
							<?php $dataSupplyStateArrs = $obj_client->get_results("select * from ".$obj_client->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
							<?php if(!empty($dataSupplyStateArrs)) { ?>
								<option value=''>Select Place Of Supply</option>
								<?php foreach($dataSupplyStateArrs as $dataSupplyStateArr) { ?>
								<option value='<?php echo $dataSupplyStateArr->state_id; ?>' <?php
                                     if (isset($returndata[0]->place_of_supply_uin_holder)) {
										$str = (explode(",",$returndata[0]->place_of_supply_uin_holder));

                                        foreach($str as $s)
                                    {
										if($dataSupplyStateArr->state_id==$s)
										{
                                        echo "selected='selected'";
                                        }
									}
									}
                                    ?>><?php echo $dataSupplyStateArr->state_name . " (" . $dataSupplyStateArr->state_tin . ")"; ?></option>
								<?php } ?>
							<?php } ?>
						</select>
									 </td>
									  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->total_taxable_value_uin_holder; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="total_taxable_value_uin_holder[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                 <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->amount_of_integrated_uin_holder; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="amount_of_integrated_uin_holder[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 										 
                                      <td>
									 <a class="addMoreInvoice add-row3"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a></td>
                                      
                                   </tr><?php } ?>
                               
                            </table>
                        </div>
                        
                        <div class="greyheading">4. Eligible ITC</div>
                        
                        <div class="tableresponsive">
                            <table  class="table  tablecontent tablecontent2 bordernone">
                                <thead>
                                
                                <tr>
                                <th>Details</th>
                                <th>Integrated Tax</th>
                                <th>Central Tax</th>
                                <th>State/UT Tax</th>
                                 <th>Cess</th>
   
                                </tr>
                                </thead>
                                
                                <tbody>
                                    <tr>
                                    <td class="lftheading" width="25%"><strong>(A) ITC Available (whether in full or part)</strong></td>
                          					 
                                                                 
                                    
                                    </tr>
                                    
                                    <tr>
                                    <td class="lftheading">(1) Import of goods</td>
									  <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_import_of_goods; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="integrated_tax_import_of_goods" value="<?php echo $returndata[0]->integrated_tax_import_of_goods; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								  <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_import_of_goods; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="central_tax_import_of_goods" value="<?php echo $returndata[0]->central_tax_import_of_goods; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                 <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_import_of_goods; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="state_tax_import_of_goods" value="<?php echo $returndata[0]->state_tax_import_of_goods; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 	
                                <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_import_of_goods; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_import_of_goods" value="<?php echo $returndata[0]->cess_tax_import_of_goods; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>							 
						 				 
                                  
                                    
                                      
                                      
                                    </tr>
                                    
                                     <tr>
                                    <td class="lftheading">(2) Import of services</td>
									 <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_import_of_services; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="integrated_tax_import_of_services" value="<?php echo $returndata[0]->integrated_tax_import_of_services; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>	
								  <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_import_of_services; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="central_tax_import_of_services" value="<?php echo $returndata[0]->central_tax_import_of_services; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_import_of_services; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="state_tax_import_of_services" value="<?php echo $returndata[0]->state_tax_import_of_services; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_import_of_services; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_import_of_services" value="<?php echo $returndata[0]->cess_tax_import_of_services; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>                                 
                                                                      
                                      
                                    </tr>
                                    
                                    <tr>
                                    <td class="lftheading">(3) Inward supplies liable to reverse charge (other than 1 & 2 above)</td>
									 <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_inward_supplies_reverse_charge; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="integrated_tax_inward_supplies_reverse_charge" value="<?php echo $returndata[0]->integrated_tax_inward_supplies_reverse_charge; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								 <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_inward_supplies_reverse_charge; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="central_tax_inward_supplies_reverse_charge" value="<?php echo $returndata[0]->central_tax_inward_supplies_reverse_charge; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
                               <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_inward_supplies_reverse_charge; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="state_tax_inward_supplies_reverse_charge" value="<?php echo $returndata[0]->state_tax_inward_supplies_reverse_charge; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
                                <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_inward_supplies_reverse_charge; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_inward_supplies_reverse_charge" value="<?php echo $returndata[0]->cess_tax_inward_supplies_reverse_charge; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>	 
                                   
                                       
                                      
                                    </tr>
                                    <tr>
                                    <td class="lftheading">(4) Inward supplies from ISD</td>
									 <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_inward_supplies; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="integrated_tax_inward_supplies" value="<?php echo $returndata[0]->integrated_tax_inward_supplies; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>	
								 <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_inward_supplies; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="central_tax_inward_supplies" value="<?php echo $returndata[0]->central_tax_inward_supplies; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_inward_supplies; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="state_tax_inward_supplies" value="<?php echo $returndata[0]->state_tax_inward_supplies; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                               <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_inward_supplies; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_inward_supplies" value="<?php echo $returndata[0]->cess_tax_inward_supplies; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>						 
                                                                 
                                    
                                       
                                    </tr>
                                    
                                     <tr>
                                    <td class="lftheading" >(5) All other ITC</td>
									<td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_allother_itc; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="integrated_tax_allother_itc" value="<?php echo $returndata[0]->integrated_tax_allother_itc; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>	
								 <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_allother_itc; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="central_tax_allother_itc" value="<?php echo $returndata[0]->central_tax_allother_itc; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>	
								  <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_allother_itc; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="state_tax_allother_itc"  value="<?php echo $returndata[0]->state_tax_allother_itc; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_allother_itc; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_allother_itc" value="<?php echo $returndata[0]->cess_tax_allother_itc; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                                
                                     
                                    </tr>
                                    <tr>
                                    <td class="lftheading"><strong>(B) ITC Reversed</strong></td>
									
									                     
                                      
                                      
                                    </tr>
                                    
                                     <tr>
                                    <td class="lftheading" >(1) As per rules 42 & 43 of CGST Rules</td>
									<td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_itc_reversed_cgstrules; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="integrated_tax_itc_reversed_cgstrules" value="<?php echo $returndata[0]->integrated_tax_itc_reversed_cgstrules; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								 <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_itc_reversed_cgstrules; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="central_tax_itc_reversed_cgstrules" value="<?php echo $returndata[0]->central_tax_itc_reversed_cgstrules; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								  <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_itc_reversed_cgstrules; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="state_tax_itc_reversed_cgstrules" value="<?php echo $returndata[0]->state_tax_itc_reversed_cgstrules; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								  <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_itc_reversed_cgstrules; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_itc_reversed_cgstrules" value="<?php echo $returndata[0]->cess_tax_itc_reversed_cgstrules; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>                        
                                  
                                      
                                       
                                    </tr>
                                    
                                     <tr>
                                    <td class="lftheading" >(2) Others</td>
									
									  <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_itc_reversed_other; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="integrated_tax_itc_reversed_other" value="<?php echo $returndata[0]->integrated_tax_itc_reversed_other; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>  
                                <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_itc_reversed_other; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="central_tax_itc_reversed_other" value="<?php echo $returndata[0]->central_tax_itc_reversed_other; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
                                <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_itc_reversed_other; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="state_tax_itc_reversed_other" value="<?php echo $returndata[0]->state_tax_itc_reversed_other; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                 <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_itc_reversed_other; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_itc_reversed_other" value="<?php echo $returndata[0]->cess_tax_itc_reversed_other; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>    								 
                                    
                                     
                                      
                                      
                                    </tr>
                                    <tr>
                                    <td class="lftheading"><strong>(C) Net ITC Available (A) – (B)</strong></td>
									<td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_net_itc_a_b; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="integrated_tax_net_itc_a_b" value="<?php echo $returndata[0]->integrated_tax_net_itc_a_b; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_net_itc_a_b; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="central_tax_net_itc_a_b" value="<?php echo $returndata[0]->central_tax_net_itc_a_b; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_net_itc_a_b; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="state_tax_net_itc_a_b" value="<?php echo $returndata[0]->state_tax_net_itc_a_b; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_net_itc_a_b; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_net_itc_a_b" value="<?php echo $returndata[0]->cess_tax_net_itc_a_b; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>                       
                                                                        
                                      
                                    </tr>
                                    
                                    <tr>
                                    <td class="lftheading"><strong>(D) Ineligible ITC</strong></td>
													          
                                      
                                      
                                      
                                    </tr>
                                     <tr>
                                    <td class="lftheading">(1) As per section 17(5)</td>
									 <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_inligible_itc_17_5; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="integrated_tax_inligible_itc_17_5" value="<?php echo $returndata[0]->integrated_tax_inligible_itc_17_5; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_inligible_itc_17_5; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="central_tax_inligible_itc_17_5" value="<?php echo $returndata[0]->central_tax_inligible_itc_17_5; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_inligible_itc_17_5; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="state_tax_inligible_itc_17_5" value="<?php echo $returndata[0]->state_tax_inligible_itc_17_5; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_inligible_itc_17_5; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_inligible_itc_17_5" value="<?php echo $returndata[0]->cess_tax_inligible_itc_17_5; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>                      
                            </tr>
                                     <tr>
                                    <td class="lftheading">(2) Others</td>
									 <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_inligible_itc_others; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="integrated_tax_inligible_itc_others" value="<?php echo $returndata[0]->integrated_tax_inligible_itc_others; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>  
								 <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_inligible_itc_others; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="central_tax_inligible_itc_others" value="<?php echo $returndata[0]->central_tax_inligible_itc_others; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                               <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_inligible_itc_others; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="state_tax_inligible_itc_others" value="<?php echo $returndata[0]->state_tax_inligible_itc_others; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                               <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_inligible_itc_others; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_inligible_itc_others" value="<?php echo $returndata[0]->cess_tax_inligible_itc_others; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 								 
                                    
                                     
                                       
                                     
                                    </tr>
                                    
                                    
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="greyheading">5. Values of exempt, nil-rated and non-GST inward supplies</div>
                         <div class="tableresponsive">
                            <table  class="table  tablecontent tablecontent2 bordernone">
                                <thead>
                                
                                <tr>
                                <th>Nature of supplies</th>
                                <th>Inter-State supplies</th>
                                <th>Intra-State supplies</th>                               
                                   </tr>
                                </thead>
                                
                                <tbody>
                                    <tr>
                                    <td class="lftheading" width="25%">From a supplier under composition scheme, Exempt and Nil rated supply</td>
									 <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->inter_state_supplies_composition_scheme; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="inter_state_supplies_composition_scheme" value="<?php echo $returndata[0]->inter_state_supplies_composition_scheme; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 	
                              <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->intra_state_supplies_composition_scheme; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="intra_state_supplies_composition_scheme" value="<?php echo $returndata[0]->intra_state_supplies_composition_scheme; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 	                                   
                                    
                                    </tr>
                                    
                                     <tr>
                                    <td class="lftheading" width="25%">Non GST supply</td>
									<td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->inter_state_supplies_nongst_supply; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="inter_state_supplies_nongst_supply" value="<?php echo $returndata[0]->inter_state_supplies_nongst_supply; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 	
                               <td> 
							 <?php
								 if($status > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->intra_state_supplies_nongst_supply; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="intra_state_supplies_nongst_supply" value="<?php echo $returndata[0]->intra_state_supplies_nongst_supply; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 									 
                                    
                                  
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </div>
                        
                        
                        
                       
                         <div class="tableresponsive">
                           
							
                                <div class="adminformbxsubmit" style="width:100%;"> 
                            <div class="tc">
                                <input type='submit' class="btn btn-success" name='submit' value='submit' id='submit'>
								<input type="hidden" name="returnid" id="returnid" value=<?php echo $returndata[0]->return_id; ?> />
                                
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
     
  <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-row1").click(function(){
       
			var data1 ='<select class="required form-control" id="place_of_supply_unregistered_person"  name="place_of_supply_unregistered_person[]">';
			 var data='';
			 data +=<?php $dataSupplyStateArrs = $obj_client->get_results("select * from ".$obj_client->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
						<?php if(!empty($dataSupplyStateArrs)) { ?>
							data += '<option value="">Select Place of Supply</option>';
							<?php foreach($dataSupplyStateArrs as $dataSupplyStateArr) { ?>
								data += '<option value="<?php echo $dataSupplyStateArr->state_id; ?>"><?php echo $dataSupplyStateArr->state_name; ?></option>';
							<?php } ?>
						<?php } ?>
							
			data = data1+ data+'</select>';
		
		    
            var markup = "<tr><td><input type='hidden' name='record'></td><td>" + data + "</td><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='total_taxable_value_unregistered_person[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='amount_of_integrated_tax_unregistered_person[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table1').append(markup);
        });
		$('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});
        
		 
        
    }); 

</script>
<script>

</script>
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-row2").click(function(){
       
			var data1 ='<select class="required form-control" id="place_of_supply_taxable_person"  name="place_of_supply_taxable_person[]">';
			 var data='';
			 data +=<?php $dataSupplyStateArrs = $obj_client->get_results("select * from ".$obj_client->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
						<?php if(!empty($dataSupplyStateArrs)) { ?>
							data += '<option value="">Select Place of Supply</option>';
							<?php foreach($dataSupplyStateArrs as $dataSupplyStateArr) { ?>
								data += '<option value="<?php echo $dataSupplyStateArr->state_id; ?>"><?php echo $dataSupplyStateArr->state_name; ?></option>';
							<?php } ?>
						<?php } ?>
							
			data = data1+ data+'</select>';
		
		    
            var markup = "<tr><td><input type='hidden' name='record'></td><td>" + data + "</td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='total_taxable_value_taxable_person[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='amount_of_integrated_tax_taxable_person[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table2').append(markup);
        });
        
		 
      $('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});
    });    
</script>
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-row3").click(function(){
       
			var data1 ='<select class="required form-control" id="place_of_supply_uin_holder"  name="place_of_supply_uin_holder[]">';
			 var data='';
			 data +=<?php $dataSupplyStateArrs = $obj_client->get_results("select * from ".$obj_client->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
						<?php if(!empty($dataSupplyStateArrs)) { ?>
							data += '<option value="">Select Place of Supply</option>';
							<?php foreach($dataSupplyStateArrs as $dataSupplyStateArr) { ?>
								data += '<option value="<?php echo $dataSupplyStateArr->state_id; ?>"><?php echo $dataSupplyStateArr->state_name; ?></option>';
							<?php } ?>
						<?php } ?>
							
			data = data1+ data+'</select>';
		
		    
            var markup = "<tr><td><input type='hidden' name='record'></td><td>" + data + "</td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='total_taxable_value_uin_holder[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='amount_of_integrated_uin_holder[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table3').append(markup);
        });
         $('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});
		 
        
    });    
</script>
<script>
    $(document).ready(function () {
        $('#returnmonth').on('change', function () {
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_gstr3b_file&returnmonth=<?php echo $returnmonth; ?>';
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
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_gstr3b_file&returnmonth=<?php echo $returnmonth; ?>';
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
