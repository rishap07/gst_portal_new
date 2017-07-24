<?php
$obj_master = new master();
$obj_client = new client();
if(!$obj_master->can_create('master_receiver') && !isset($_GET['id'])) {
    
	$obj_master->setError($obj_master->getValMsg('can_create'));
    $obj_master->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}

if(!$obj_master->can_update('master_receiver') && isset($_GET['id'])) {

	$obj_master->setError($obj_master->getValMsg('can_update'));
	$obj_master->redirect(PROJECT_URL."/?page=master_receiver");
	exit(); 
}

if(isset($_POST['submit']) && $_POST['submit']=='submit') {

    if(!$obj_master->can_create('master_receiver')) {

        $obj_master->setError($obj_master->getValMsg('can_create'));
        $obj_master->redirect(PROJECT_URL."/?page=master_receiver");
        exit();
    }

    if($obj_master->addReceiver()){
        $obj_master->redirect(PROJECT_URL."/?page=master_receiver");
    }
}

if(isset($_POST['submit']) && $_POST['submit']=='update' && isset($_GET['id'])) {

    if(!$obj_master->can_update('master_receiver')) {

        $obj_master->setError($obj_master->getValMsg('can_update'));
        $obj_master->redirect(PROJECT_URL."/?page=master_receiver");
        exit();
	}
	
    if($obj_master->updateReceiver()) {
        $obj_master->redirect(PROJECT_URL."/?page=master_receiver");
    }
}

$dataArr = array();
if(isset($_GET['id'])) {
    
	$erecid = $obj_master->sanitize($_GET['id']);
	$recdata = $obj_master->get_row("select * from " . $obj_master->getTableName('receiver') ." where receiver_id = '".$erecid."' AND added_by = '".$obj_master->sanitize($_SESSION['user_detail']['user_id'])."' AND is_deleted='0'");

	if (empty($recdata)) {
		$obj_master->setError("No receiver found.");
        $obj_master->redirect(PROJECT_URL."?page=master_receiver");
	}

	$dataArr = $obj_master->findAll($obj_master->getTableName('receiver'),"is_deleted='0' and receiver_id='".$obj_master->sanitize($_GET['id'])."'");
}
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Receiver/Customer</h1></div>
         <?php $obj_master->showErrorMessage(); ?>
        <?php $obj_master->showSuccessMessge(); ?>
        <?php $obj_master->unsetMessage(); ?>
       
        
       
      
	   <div class="whitebg formboxcontainer">
      	
        <form method="post" enctype="multipart/form-data" id='form'>
              <h2 class="greyheading"><?php echo isset($_GET['id']) ? 'Edit Receiver/Customer' : 'Add New Receiver/Customer'; ?></h2>
                <div class="row">
                  
                   <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                              <label>Contact Name <span class="starred">*</span></label>
                            <input type="text" placeholder="Name"  name='name' data-bind="content" class="form-control required" value='<?php if(isset($_POST['name'])){ echo $_POST['name']; } else if(isset($dataArr[0]->name)){ echo $dataArr[0]->name; } ?>'/>
							
                        </div>
                  <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <label>Business Name</label>
                            <input type="text" placeholder="Business Name" class="form-control"  name='company_name' data-bind="content" value='<?php if(isset($_POST['company_name'])){ echo $_POST['company_name']; } else if(isset($dataArr[0]->company_name)){ echo $dataArr[0]->company_name; } ?>'/>
							</div>

                     <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                       <label>Email <span class="starred">*</span></label>
                           <input type="text" placeholder="Email"  name='email' class="form-control required" data-bind="email" value='<?php if(isset($_POST['email'])){ echo $_POST['email']; } else if(isset($dataArr[0]->email)){ echo $dataArr[0]->email; } ?>'/>
							</div>

                      <div class="clear"></div>
                        <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <label>Address <span class="starred">*</span></label>
                            <input type="text" placeholder="Address" name='address' data-bind="content" class="form-control required" value='<?php if(isset($_POST['address'])){ echo $_POST['address']; } else if(isset($dataArr[0]->address)){ echo $dataArr[0]->address; } ?>'/>
							
							</div>
							 <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                         <label>City <span class="starred">*</span></label>
                            <input type="text" placeholder="City" name='city' data-bind="content" class="form-control required" value='<?php if(isset($_POST['city'])){ echo $_POST['city']; } else if(isset($dataArr[0]->city)){ echo $dataArr[0]->city; } ?>'/>
							
							</div>
							 <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                       <label>State <span class="starred">*</span></label>
                            <select name='state' id='state' class='form-control required'>
                                <?php
                                $dataStateArrs = $obj_master->get_results("select * from ".$obj_master->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc");
                                if(!empty($dataStateArrs)) { ?>
                                    <option value=''>Select State</option>
                                    <?php
                                    foreach($dataStateArrs as $dataStateArr) { ?>
                                        <option value='<?php echo $dataStateArr->state_id; ?>' <?php if(isset($_POST['state']) && $_POST['state'] === $dataStateArr->state_id){ echo 'selected="selected"'; } else if(isset($dataArr[0]->state) && $dataStateArr->state_id == $dataArr[0]->state){ echo 'selected="selected"'; } ?>><?php echo $dataStateArr->state_name; ?></option>
                                    <?php }
                                } else { ?>
                                    <option value=''>No State Found</option>
								<?php } ?>
                            </select>
                            <span class="greysmalltxt"></span> </div>
                    <div class="clear"></div>
					 <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                         <label>Zipcode <span class="starred">*</span></label>
                            <input type="text" placeholder="Zipcode" name='zipcode' class='form-control required' data-bind="number" value='<?php if(isset($_POST['zipcode'])){ echo $_POST['zipcode']; } else if(isset($dataArr[0]->zipcode)){ echo $dataArr[0]->zipcode; } ?>'/>
                        </div>
						 <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <label>Phone </label>
                            <input type="text" placeholder="Phone" name='phone' data-bind="mobilenumber" class='form-control' value='<?php if(isset($_POST['phone'])){ echo $_POST['phone']; } else if(isset($dataArr[0]->phone)){ echo $dataArr[0]->phone; } ?>'/>
                        </div>
						<div class="col-md-4 col-sm-4 col-xs-12 form-group">
                            <label>Fax </label>
                            <input type="text" placeholder="Fax" class="form-control" name='fax' data-bind="number" value='<?php if(isset($_POST['fax'])){ echo $_POST['fax']; } else if(isset($dataArr[0]->fax)){ echo $dataArr[0]->fax; } ?>'/>
                        </div>
						  <div class="clear"></div>
						  <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                            <label>PAN Number </label>
                            <input type="text" placeholder="PAN Number" class="form-control" name='pannumber' data-bind="pancard" value='<?php if(isset($_POST['pannumber'])){ echo $_POST['pannumber']; } else if(isset($dataArr[0]->pannumber)){ echo $dataArr[0]->pannumber; } ?>'/>
                        </div>
						  <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                            <label>GSTIN</label>
                            <input type="text" placeholder="GSTIN" class="form-control" name='gstid' data-bind="gstin" value='<?php if(isset($_POST['gstid'])){ echo $_POST['gstid'];}else if(isset($dataArr[0]->gstid)){ echo $dataArr[0]->gstid; } ?>' />
						</div>
					 <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                            <label>Website</label>
                            <input type="text" placeholder="Website URL" class="form-control" name='website' data-bind="url" value='<?php if(isset($_POST['website'])){ echo $_POST['website'];}else if(isset($dataArr[0]->website)){ echo $dataArr[0]->website; } ?>' />
						</div>
						<div class="clear"></div>
						<div class="col-md-4 col-sm-4 col-xs-12 form-group">
                            <label>Remarks</label>
                            <textarea placeholder="Remarks" class="form-control" name='remarks' data-bind="content"><?php if(isset($_POST['remarks'])){ echo $_POST['remarks'];}else if(isset($dataArr[0]->remarks)){ echo $dataArr[0]->remarks; } ?></textarea>
						</div>
						<div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <label>Vendor Type<span class="starred">*</span></label>
                        <select name='vendor_type' id='vendor_type' class='required form-control'>
                            <?php $dataVendorArrs = $obj_client->get_results("select * from " . $obj_client->getTableName('vendor_type') . " where status='1' and is_deleted='0' order by vendor_name asc"); ?>

                            <?php if (!empty($dataVendorArrs)) { ?>
                                <option value=''>Select Vendor Type</option>
                                <?php foreach ($dataVendorArrs as $dataVendorArr) { ?>
                                    <option value='<?php echo $dataVendorArr->vendor_id; ?>'
									<?php if(isset($_POST['vendor_type']) && $_POST['vendor_type'] == $dataVendorArr->vendor_id) { echo 'selected="selected"'; } else if(isset($dataArr[0]->vendor_type) && $dataArr[0]->vendor_type == $dataVendorArr->vendor_id) { echo 'selected="selected"'; } ?>><?php echo $dataVendorArr->vendor_name; ?></option>
								<?php } ?>
                            <?php } ?>
                        </select>
						
                    </div>
						<div class="col-md-4 col-sm-4 col-xs-12 form-group">
                            <label>Status<span class="starred">*</span></label>
                            <select name="status" class="form-control">
                                <option value="1" <?php if(isset($_POST['status']) && $_POST['status']==='1'){ echo 'selected="selected"'; } else if(isset($dataArr[0]->status) && $dataArr[0]->status==='1'){ echo 'selected="selected"'; } ?>>Active</option>
                                <option value="0" <?php if(isset($_POST['status']) && $_POST['status']==='0'){ echo 'selected="selected"'; } else if(isset($dataArr[0]->status) && $dataArr[0]->status==='0'){ echo 'selected="selected"'; } ?>>In-Active</option>
                            </select>
                        </div>

								 <div class="clear height30"></div>
                        <div class="adminformbxsubmit" style="width:100%;"> 
                            <div class="tc">
                                <input type='submit' class="btn btn-danger" name='submit' value='<?php echo isset($_GET['id']) ? 'update' : 'submit'; ?>' id='submit'>
                                <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=master_receiver"; ?>';" class="btn btn-danger" class="redbtn marlef10"/>
                            </div>
                        </div>
                </div>
                   
                
        </form></div>
           </div>
       

              
                 
  


                   


                 
                    
                </div>

              

              

          


<script>
    $(document).ready(function () {
		
		/* select2 js for state */
        $("#state").select2();
		 $("#vendor_type").select2();
		
        $('#submit').click(function () {
            var mesg = {};
            if (vali.validate(mesg,'form')) {
                return true;
            }
            return false;
        });
    });
</script>
    