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
        //$obj_master->redirect(PROJECT_URL."/?page=master_receiver");
    }
}
if(isset($_POST['finalsubmit']) && $_POST['finalsubmit']=='final submit') {

   

    if($obj_return->finalSaveGstr3b()){
        //$obj_master->redirect(PROJECT_URL."/?page=master_receiver");
    }
}
//code for display GSTR3B data
	   $sql = "select  *,count(return_id) as totalinvoice from gst_client_return_gstr3b where added_by='" . $_SESSION['user_detail']['user_id'] . "' and financial_month like '%" . $returnmonth . "%' order by return_id desc limit 0,1";
 
       $returndata = $obj_return->get_results($sql);
	   ?>
	  

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
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->total_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="total_tax_value_supplya"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="integrated_tax_value_supplya"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="central_tax_value_supplya"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="state_tax_value_supplya"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="cess_tax_value_supplya"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                               
                                   
                                    
                           
                                </tr>
                                
                                <tr>
                                <td class="lftheading" width="20%">(b) Outward taxable supplies (zero rated )</td>
								 <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->total_tax_value_supplyb; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="total_tax_value_supplyb"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_value_supplyb; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="integrated_tax_value_supplyb"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_value_supplyb; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="central_tax_value_supplyb"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_value_supplyb; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="state_tax_value_supplyb"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplyb; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="cess_tax_value_supplyb"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>                             
                                                            
                                    
                            
                                </tr>
                                
                                <tr>
                                <td class="lftheading" width="20%">(c) Other outward supplies (Nil rated, exempted)</td>
								 <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->total_tax_value_supplyc; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="total_tax_value_supplyc"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								 <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_value_supplyc; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="integrated_tax_value_supplyc"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_value_supplyc; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="central_tax_value_supplyc"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_value_supplyc; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="state_tax_value_supplyc"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplyc; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="cess_tax_value_supplyc"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>              
                               
                                
                                  
                                  
                                </tr>
                                
                                 <tr>
                                <td class="lftheading" width="20%">(d) Inward supplies (liable to reverse charge)</td>
								 <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->total_tax_value_supplyd; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="total_tax_value_supplyd"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_value_supplyd; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="integrated_tax_value_supplyd"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								 <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_value_supplyd; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="central_tax_value_supplyd"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_value_supplyd; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="state_tax_value_supplyd"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                 <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplyd; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="cess_tax_value_supplyd"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 	          
                               
                              
                                   
                                    
                                     
								</tr>
                                 <tr>
                                <td class="lftheading" width="20%">(e) Non-GST outward supplies</td>
                               
								   <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->total_tax_value_supplye; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="total_tax_value_supplye"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 	   
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_value_supplye; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="integrated_tax_value_supplye"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_value_supplye; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="central_tax_value_supplye"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
                                <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_value_supplye; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="state_tax_value_supplye"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
                                <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplye; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="cess_tax_value_supplye"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 								 
								                                                                  
                                   
                                     
								</tr>
                                </tbody>
                            </table>
                        </div>
                        
                        	<div class="greyheading">3.2 Of the supplies shown in 3.1 (a) above, details of inter-State supplies made to unregistered persons,
composition taxable persons and UIN holders</div>
                            
                            <div class="tableresponsive">
                            <table  class="table  tablecontent tablecontent2 bordernone">
                                <thead>
                                
                                <tr>
                                <th></th>
                                <th>Place of Supply (State/UT)</th>
                                <th>Total Taxable value</th>
                                <th>Amount of Integrated Tax</th>
   
                                </tr>
                                </thead>
                                
                                <tbody>
                                    <tr>
                                    <td class="lftheading" width="25%">Supplies made to Unregistered Persons</td>
                                     <td>
									
						<select name='place_of_supply_unregistered_person' id='place_of_supply_unregistered_person' class="required form-control">
							<?php $dataSupplyStateArrs = $obj_client->get_results("select * from ".$obj_client->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
							<?php if(!empty($dataSupplyStateArrs)) { ?>
								<option value=''>Select Place Of Supply</option>
								<?php foreach($dataSupplyStateArrs as $dataSupplyStateArr) { ?>
								<option value='<?php echo $dataSupplyStateArr->state_id; ?>' <?php
                                    if (isset($returndata[0]->place_of_supply_unregistered_person) && $returndata[0]->place_of_supply_unregistered_person == $dataSupplyStateArr->state_id) {
                                        echo "selected='selected'";
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
									 <label><?php echo $returndata[0]->total_taxable_value_unregistered_person; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="total_taxable_value_unregistered_person"
 class="form-control"  placeholder="" /> 
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
								 <input type="text" name="amount_of_integrated_tax_unregistered_person"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 										                       
                                      
                                    </tr>
                                    
                                     <tr>
                                    <td class="lftheading" width="25%">Supplies made to Composition Taxable Persons</td>
                                     <td>
									 
						<select name='place_of_supply_taxable_person' id='place_of_supply_taxable_person' class="required form-control">
								<?php $dataSupplyStateArrs = $obj_client->get_results("select * from ".$obj_client->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
							<?php if(!empty($dataSupplyStateArrs)) { ?>
								<option value=''>Select Place Of Supply</option>
								<?php foreach($dataSupplyStateArrs as $dataSupplyStateArr) { ?>
								<option value='<?php echo $dataSupplyStateArr->state_id; ?>' <?php
                                    if (isset($returndata[0]->place_of_supply_taxable_person) && $returndata[0]->place_of_supply_taxable_person == $dataSupplyStateArr->state_id) {
                                        echo "selected='selected'";
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
								 <input type="text" name="total_taxable_value_taxable_person"
 class="form-control"  placeholder="" /> 
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
								 <input type="text" name="amount_of_integrated_tax_taxable_person"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 									 
                                      
                                    
                                    </tr>
                                     <tr>
                                    <td class="lftheading" width="25%">Supplies made to UIN holders</td>
                                     <td>
									 
									
						<select name='place_of_supply_uin_holder' id='place_of_supply_uin_holder' class="required form-control">
							<?php $dataSupplyStateArrs = $obj_client->get_results("select * from ".$obj_client->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
							<?php if(!empty($dataSupplyStateArrs)) { ?>
								<option value=''>Select Place Of Supply</option>
								<?php foreach($dataSupplyStateArrs as $dataSupplyStateArr) { ?>
								<option value='<?php echo $dataSupplyStateArr->state_id; ?>' <?php
                                    if (isset($returndata[0]->place_of_supply_uin_holder) && $returndata[0]->place_of_supply_uin_holder == $dataSupplyStateArr->state_id) {
                                        echo "selected='selected'";
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
								 <input type="text" name="total_taxable_value_uin_holder"
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
								 <input type="text" name="amount_of_integrated_uin_holder"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 										 
                                     
                                      
                                    </tr>
                                </tbody>
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
                                     <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_itcavailable_a; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="integrated_tax_itcavailable_a"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 		
                             <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_itcavailable_a; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="central_tax_itcavailable_a"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                             <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_itcavailable_a; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="state_tax_itcavailable_a"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
                              <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_itcavailable_a; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="cess_tax_itcavailable_a"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 						 
                                                                 
                                    
                                    </tr>
                                    
                                    <tr>
                                    <td class="lftheading">(1) Import of goods</td>
									  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_import_of_goods; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="integrated_tax_import_of_goods"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_import_of_goods; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="central_tax_import_of_goods"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_import_of_goods; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="state_tax_import_of_goods"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 	
                                <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_import_of_goods; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="cess_tax_import_of_goods"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>							 
						 				 
                                  
                                    
                                      
                                      
                                    </tr>
                                    
                                     <tr>
                                    <td class="lftheading">(2) Import of services</td>
									 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_import_of_services; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="integrated_tax_import_of_services"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>	
								  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_import_of_services; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="central_tax_import_of_services"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_import_of_services; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="state_tax_import_of_services"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_import_of_services; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="cess_tax_import_of_services"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>                                 
                                                                      
                                      
                                    </tr>
                                    
                                    <tr>
                                    <td class="lftheading">(3) Inward supplies liable to reverse charge (other than 1 & 2 above)</td>
									 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_inward_supplies_reverse_charge; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="integrated_tax_inward_supplies_reverse_charge"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_inward_supplies_reverse_charge; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="central_tax_inward_supplies_reverse_charge"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
                               <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_inward_supplies_reverse_charge; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="state_tax_inward_supplies_reverse_charge"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
                                <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_inward_supplies_reverse_charge; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="cess_tax_inward_supplies_reverse_charge"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>	 
                                   
                                       
                                      
                                    </tr>
                                    <tr>
                                    <td class="lftheading">(4) Inward supplies from ISD</td>
									 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_inward_supplies; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="integrated_tax_inward_supplies"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>	
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_inward_supplies; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="central_tax_inward_supplies"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_inward_supplies; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="state_tax_inward_supplies"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                               <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_inward_supplies; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="cess_tax_inward_supplies"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>						 
                                                                 
                                    
                                       
                                    </tr>
                                    
                                     <tr>
                                    <td class="lftheading" >(5) All other ITC</td>
									<td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_allother_itc; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="integrated_tax_allother_itc"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>	
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_allother_itc; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="central_tax_allother_itc"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>	
								  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_allother_itc; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="state_tax_allother_itc"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_allother_itc; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="cess_tax_allother_itc"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                                
                                     
                                    </tr>
                                    <tr>
                                    <td class="lftheading"><strong>(B) ITC Reversed</strong></td>
									
									 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_itc_reversed_b; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="integrated_tax_itc_reversed_b"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_itc_reversed_b; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="central_tax_itc_reversed_b"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                     <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_itc_reversed_b; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="state_tax_itc_reversed_b"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_itc_reversed_b; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="cess_tax_itc_reversed_b"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>                            
                                      
                                      
                                    </tr>
                                    
                                     <tr>
                                    <td class="lftheading" >(1) As per rules 42 & 43 of CGST Rules</td>
									<td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_itc_reversed_cgstrules; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="integrated_tax_itc_reversed_cgstrules"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_itc_reversed_cgstrules; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="central_tax_itc_reversed_cgstrules"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_itc_reversed_cgstrules; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="state_tax_itc_reversed_cgstrules"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_itc_reversed_cgstrules; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="cess_tax_itc_reversed_cgstrules"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>                        
                                  
                                      
                                       
                                    </tr>
                                    
                                     <tr>
                                    <td class="lftheading" >(2) Others</td>
									
									  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_itc_reversed_other; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="integrated_tax_itc_reversed_other"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>  
                                <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_itc_reversed_other; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="central_tax_itc_reversed_other"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
                                <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_itc_reversed_other; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="state_tax_itc_reversed_other"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_itc_reversed_other; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="cess_tax_itc_reversed_other"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>    								 
                                    
                                     
                                      
                                      
                                    </tr>
                                    <tr>
                                    <td class="lftheading"><strong>(C) Net ITC Available (A) – (B)</strong></td>
									<td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_net_itc_a_b; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="integrated_tax_net_itc_a_b"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_net_itc_a_b; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="central_tax_net_itc_a_b"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_net_itc_a_b; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="state_tax_net_itc_a_b"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_net_itc_a_b; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="cess_tax_net_itc_a_b"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>                       
                                                                        
                                      
                                    </tr>
                                    
                                    <tr>
                                    <td class="lftheading"><strong>(D) Ineligible ITC</strong></td>
									 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_inligible_itc; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="integrated_tax_inligible_itc"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>  
                             <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_inligible_itc; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="central_tax_inligible_itc"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>   
                             <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_inligible_itc; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="state_tax_inligible_itc"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>	
                               <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_inligible_itc; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="cess_tax_inligible_itc"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>									          
                                      
                                      
                                      
                                    </tr>
                                     <tr>
                                    <td class="lftheading">(1) As per section 17(5)</td>
									 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_inligible_itc_17_5; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="integrated_tax_inligible_itc_17_5"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_inligible_itc_17_5; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="central_tax_inligible_itc_17_5"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_inligible_itc_17_5; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="state_tax_inligible_itc_17_5"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_inligible_itc_17_5; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="cess_tax_inligible_itc_17_5"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>                      
                            </tr>
                                     <tr>
                                    <td class="lftheading">(2) Others</td>
									 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_inligible_itc_others; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="integrated_tax_inligible_itc_others"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>  
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_inligible_itc_others; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="central_tax_inligible_itc_others"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                               <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_inligible_itc_others; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="state_tax_inligible_itc_others"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                               <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_inligible_itc_others; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="cess_tax_inligible_itc_others"
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
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->inter_state_supplies_composition_scheme; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="inter_state_supplies_composition_scheme"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 	
                              <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->intra_state_supplies_composition_scheme; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="intra_state_supplies_composition_scheme"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 	                                   
                                    
                                    </tr>
                                    
                                     <tr>
                                    <td class="lftheading" width="25%">Non GST supply</td>
									<td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->inter_state_supplies_nongst_supply; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="inter_state_supplies_nongst_supply"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 	
                               <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->intra_state_supplies_nongst_supply; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="intra_state_supplies_nongst_supply"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 									 
                                    
                                  
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="greyheading">6.1 Payment of tax</div>
                         <div class="tableresponsive">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table  tablecontent tablecontent2 bordernone">
                                  <tr>
                                    <th>Description</th>
                                    <th>Tax payable</th>
                                    <th colspan="4" align="center">Paid through ITC</th>
                                    <th>Tax paid <br/>TDS./TCS</th>
                                    <th>Tax/Cess <br/>paid in<br/>cash</th>
                                    <th>Interest</th>
                                    <th>Late Fee</th>
                                  </tr>
                                  
                                  <tr>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>Integrated Fee<br> Tax</th>
                                    <th>Central<br>Tax</th>
                                    <th>State/UT<br>Tax</th>
                                    <th>Cess</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                  </tr>
                                  <tr>
                                    <td class="lftheading" width="25%">Integrated Tax</td>
									 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->tax_payable_integrated_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="tax_payable_integrated_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_fee_integrated_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="integrated_fee_integrated_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_integrated_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="central_integrated_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                             <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_integrated_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="state_integrated_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_integrated_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="cess_integrated_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                               <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->taxpaid_tdstcs_integrated_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="taxpaid_tdstcs_integrated_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                               <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->taxpaid_cess_integrated_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="taxpaid_cess_integrated_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>	
                              <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->interest_integrated_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="interest_integrated_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                            <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->latefee_integrated_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="latefee_integrated_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>								 
                               
                                    
                                  </tr>
                                  
                                   <tr>
                                    <td class="lftheading">Central Tax</td>
									 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->tax_payable_central_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="tax_payable_central_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>	
								  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_fee_central_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="integrated_fee_central_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_central_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="central_central_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_central_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="state_central_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_central_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="cess_central_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                             <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->taxpaid_tdstcs_central_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="taxpaid_tdstcs_central_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>	
                             <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->taxpaid_cess_central_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="taxpaid_cess_central_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                             <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->interest_central_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="interest_central_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>	
                             <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->latefee_central_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="latefee_central_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>									 
                                   
                                  </tr>
                                  
                                   <tr>
                                    <td class="lftheading">State/UT Tax</td>
									  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->tax_payable_stateut_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="tax_payable_stateut_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_stateut_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="integrated_stateut_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_stateut_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="central_stateut_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_stateut_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="state_stateut_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_stateut_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="cess_stateut_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->taxpaid_tcs_stateut_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="taxpaid_tcs_stateut_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->taxpaid_cess_stateut_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="taxpaid_cess_stateut_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->interest_stateut_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="interest_stateut_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->latefee_stateut_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="latefee_stateut_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                  </tr>
                                  
                                </table>
                            </div>
                        
                        <div class="greyheading">6.2 TDS/TCS Credit</div>
                         <div class="tableresponsive">
                            <table  class="table  tablecontent tablecontent2 bordernone">
                                <thead>
                                
                                <tr>
                                <th>Details</th>
                                <th>Integrated Tax</th>
                                <th>Central Tax</th> 
                                 <th>State/UT Tax</th>                                  
                                   </tr>
                                </thead>
                                
                                <tbody>
                                    <tr>
                                    <td class="lftheading" width="25%">TDS</td>
									 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_tds; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="integrated_tax_tds"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_tds; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="central_tax_tds"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_tds; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="state_tax_tds"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                    </tr>
                                    
                                     <tr>
                                    <td class="lftheading" width="25%">TCS</td>
									 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_tcs; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="integrated_tax_tcs"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_tcs; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="central_tax_tcs"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_tcs; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="state_tax_tcs"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                    </tr>
                                    
                                   
                                    
                                </tbody>
                            </table>
							<?php
							if($returndata[0]->totalinvoice > 0)
							{
								if($returndata[0]->final_submit == 0)
							{
								?>
                                <div class="adminformbxsubmit" style="width:100%;"> 
                            <div class="tc">
                               <input type="button" value="<?php echo ucfirst('Edit'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=return_gstr3b_file_submit&returnmonth=".$_REQUEST["returnmonth"]; ?>';" class="btn btn-danger" class="redbtn marlef10"/>
							  <input type='submit' class="btn btn-danger" name='finalsubmit' value='final submit' id='finalsubmit'>
							  <input type='hidden' name="returnid" id="returnid" value="<?php echo $returndata[0]->return_id; ?>" />
									
                               
                            </div>
                              </div>
                            <?php } }
							else
							{
								?>
								  <div class="adminformbxsubmit" style="width:100%;"> 
                            <div class="tc">
                                <input type='submit' class="btn btn-danger" name='submit' value='submit' id='submit'>
								
                               
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
     
    <script>
    if (screen.width < 992) {
   $('[data-toggle=offcanvas]').click(function() {
    $('.row-offcanvas').toggleClass('active');
    $('.collapse').toggleClass('in').toggleClass('visible-xs').toggleClass('visible-xs');
	$("collapsed").hasClass("<i 
});
}
else {

    $('[data-toggle=offcanvas]').click(function() {
    $('.row-offcanvas').toggleClass('active');
    $('.collapse').toggleClass('in').toggleClass('hidden-xs').toggleClass('visible-xs');
});
}

$(".collapsed").children(".navrgtarrow");
    
    </script>
<script>
    $(document).ready(function () {
        $('#returnmonth').on('change', function () {
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_gstr3b_file&returnmonth=<?php echo $returnmonth; ?>';
                        document.form2.submit();
                    });
                });
</script>	
        