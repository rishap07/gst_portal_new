<?php
$obj_master = new master();
if (!$obj_master->can_read('master_supplier')) {

    $obj_master->setError($obj_master->getValMsg('can_read'));
    $obj_master->redirect(PROJECT_URL . "/?page=dashboard");
    exit();
}

if (isset($_POST['submit']) && $_POST['submit'] == 'submit') {

	if (!$obj_master->can_create('master_supplier')) {
        $obj_master->setError($obj_master->getValMsg('can_create'));
        $obj_master->redirect(PROJECT_URL . "/?page=master_supplier");
        exit();
    }

    if ($obj_master->addSupplier()) {
        $obj_master->redirect(PROJECT_URL . "/?page=master_supplier");
    }
}

if (isset($_POST['submit']) && $_POST['submit'] == 'update' && isset($_GET['id'])) {

    if (!$obj_master->can_update('master_supplier')) {
        $obj_master->setError($obj_master->getValMsg('can_update'));
        $obj_master->redirect(PROJECT_URL . "/?page=master_supplier");
        exit();
    }

    if ($obj_master->updateSupplier()) {
        $obj_master->redirect(PROJECT_URL . "/?page=master_supplier");
    }
}

$dataArr = array();
if (isset($_GET['id'])) {

    $esupid = $obj_master->sanitize($_GET['id']);
    $supdata = $obj_master->get_row("select * from " . $obj_master->getTableName('supplier') . " where supplier_id = '" . $esupid . "' AND added_by = '" . $obj_master->sanitize($_SESSION['user_detail']['user_id']) . "' AND is_deleted='0'");

    if (empty($supdata)) {
        $obj_master->setError("No supplier found.");
        $obj_master->redirect(PROJECT_URL . "?page=master_supplier");
    }

    $dataArr = $obj_master->findAll($obj_master->getTableName('supplier'), "is_deleted='0' and supplier_id='" . $obj_master->sanitize($_GET['id']) . "'");
}
?>

<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
	<div class="col-md-12 col-sm-12 col-xs-12">

		<div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Supplier/Seller</h1></div>
        
        <div class="clear"></div>
		<?php $obj_master->showErrorMessage(); ?>
		<?php $obj_master->showSuccessMessge(); ?>
		<?php $obj_master->unsetMessage(); ?>
        <div class="clear"></div>
        
		<div class="whitebg formboxcontainer">
			<h2 class="greyheading"><?php echo isset($_GET['id']) ? 'Edit Supplier/Seller' : 'Add Supplier/Seller'; ?></h2>
			<form method="post" enctype="multipart/form-data" id='form'>

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
						<label>Email</label>
						<input type="text" placeholder="Email"  name='email' class="form-control" data-bind="email" value='<?php if(isset($_POST['email'])){ echo $_POST['email']; } else if(isset($dataArr[0]->email)){ echo $dataArr[0]->email; } ?>'/>
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
						<?php $dataStateArrs = $obj_master->get_results("select * from ".$obj_master->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
						<?php if(!empty($dataStateArrs)) { ?>
							<option value=''>Select State</option>
							<?php foreach($dataStateArrs as $dataStateArr) { ?>
								<option value='<?php echo $dataStateArr->state_id; ?>' data-code='<?php echo $dataStateArr->state_code; ?>' data-tin='<?php echo $dataStateArr->state_tin; ?>' <?php if(isset($_POST['state']) && $_POST['state'] === $dataStateArr->state_id){ echo 'selected="selected"'; } else if(isset($dataArr[0]->state) && $dataStateArr->state_id == $dataArr[0]->state){ echo 'selected="selected"'; } ?>><?php echo $dataStateArr->state_name; ?></option>
							<?php } ?>
						<?php } else { ?>
								<option value=''>No State Found</option>
						<?php } ?>
						</select>
					</div>
					<div class="clear"></div>
                    
                    <div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Country <span class="starred">*</span></label>
						<select name='country' id='country' class='required form-control'>
							<?php $dataSCountryArrs = $obj_master->get_results("select * from ".$obj_master->getTableName('country')." order by country_name asc"); ?>
							<?php if(!empty($dataSCountryArrs)) { ?>
								<option value=''>Select Country</option>
								<?php foreach($dataSCountryArrs as $dataSCountryArr) { ?>
									<option value='<?php echo $dataSCountryArr->id; ?>' data-code="<?php echo $dataSCountryArr->country_code; ?>" <?php if(isset($_POST['country']) && $_POST['country'] === $dataSCountryArr->id){ echo 'selected="selected"'; } else if(isset($dataArr[0]->country) && $dataSCountryArr->id == $dataArr[0]->country){ echo 'selected="selected"'; } ?>><?php echo $dataSCountryArr->country_name . " (" . $dataSCountryArr->country_code . ")"; ?></option>
								<?php } ?>
							<?php } ?>
						</select>
					</div>
					
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Zipcode <span class="starred">*</span></label>
						<input type="text" placeholder="Zipcode" name='zipcode' class='form-control required' data-bind="number" value='<?php if(isset($_POST['zipcode'])){ echo $_POST['zipcode']; } else if(isset($dataArr[0]->zipcode)){ echo $dataArr[0]->zipcode; } ?>'/>
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Phone </label>
						<input type="text" placeholder="Phone" name='phone' class="form-control" data-bind="mobilenumber" value='<?php if(isset($_POST['phone'])){ echo $_POST['phone']; } else if(isset($dataArr[0]->phone)){ echo $dataArr[0]->phone; } ?>'/>
					</div>
					<div class="clear"></div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Fax </label>
						<input type="text" placeholder="Fax" name='fax' class="form-control" data-bind="number" value='<?php if(isset($_POST['fax'])){ echo $_POST['fax']; } else if(isset($dataArr[0]->fax)){ echo $dataArr[0]->fax; } ?>'/>
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>PAN Number </label>
						<input type="text" placeholder="PAN Number" class="form-control" name='pannumber' data-bind="pancard" value='<?php if(isset($_POST['pannumber'])){ echo $_POST['pannumber']; } else if(isset($dataArr[0]->pannumber)){ echo $dataArr[0]->pannumber; } ?>'/>
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>GSTIN</label>
						<input type="text" placeholder="GSTIN" class="form-control" name='gstid' data-bind="gstin" value='<?php if(isset($_POST['gstid'])){ echo $_POST['gstid'];}else if(isset($dataArr[0]->gstid)){ echo $dataArr[0]->gstid; } ?>' />
					</div>
					<div class="clear"></div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Website</label>
						<input type="text" placeholder="Website URL" class="form-control" name='website' data-bind="url" value='<?php if(isset($_POST['website'])){ echo $_POST['website'];}else if(isset($dataArr[0]->website)){ echo $dataArr[0]->website; } ?>' />
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Remarks</label>
						<textarea placeholder="Remarks" name='remarks' class="form-control" data-bind="content"><?php if(isset($_POST['remarks'])){ echo $_POST['remarks'];}else if(isset($dataArr[0]->remarks)){ echo $dataArr[0]->remarks; } ?></textarea>
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Vendor Type <span class="starred">*</span></label>
						<select name='vendor_type' id='vendor_type' class='required form-control'>
							<?php $dataVendorArrs = $obj_master->get_results("select * from " . $obj_master->getTableName('vendor_type') . " where status='1' and is_deleted='0' order by vendor_name asc"); ?>
							<?php if (!empty($dataVendorArrs)) { ?>
								<option value=''>Select Vendor Type</option>
								<?php foreach ($dataVendorArrs as $dataVendorArr) { ?>
									<option value='<?php echo $dataVendorArr->vendor_id; ?>' <?php if(isset($_POST['vendor_type']) && $_POST['vendor_type'] == $dataVendorArr->vendor_id) { echo 'selected="selected"'; } else if(isset($dataArr[0]->vendor_type) && $dataArr[0]->vendor_type == $dataVendorArr->vendor_id) { echo 'selected="selected"'; } ?>><?php echo $dataVendorArr->vendor_name; ?></option>
								<?php } ?>
							<?php } ?>
						</select>
					</div>
					<div class="clear"></div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Status <span class="starred">*</span></label>
						<select name="status" class="form-control">
							<option value="1" <?php if(isset($_POST['status']) && $_POST['status'] === '1'){ echo 'selected="selected"'; } else if(isset($dataArr[0]->status) && $dataArr[0]->status === '1'){ echo 'selected="selected"'; } ?>>Active</option>
							<option value="0" <?php if(isset($_POST['status']) && $_POST['status'] === '0'){ echo 'selected="selected"'; } else if(isset($dataArr[0]->status) && $dataArr[0]->status === '0'){ echo 'selected="selected"'; } ?>>In-Active</option>
						</select>
					</div>
					<div class="clear height30"></div>
					
					<div class="adminformbxsubmit" style="width:100%;"> 
						<div class="tc">
							<input type='submit' class="btn btn-success" name='submit' value='<?php echo isset($_GET['id']) ? 'update' : 'submit'; ?>' id='submit'>
							<input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=master_supplier"; ?>';" class="btn btn-danger" class="redbtn marlef10"/>
						</div>
					</div>
				</div>

			</form>
		</div>
	</div>
</div>
<script>
	$(document).ready(function () {
		
		/* select2 js for state */
		$("#state").select2();
		$("#country").select2();
		$("#vendor_type").select2();
		
		$("#state").on("change", function(event){
			var stateCode = $('option:selected', this).attr('data-code');
			if(stateCode == "OI") {
				$('#country').val("");
				$("#country").select2();
			} else {
				$('#country').val($('#country option[data-code="IN"]').val());
				$("#country").select2();
			}
		});
		
		$("#country").on("change", function(event){
			var countryCode = $('option:selected', this).attr('data-code');
			if(countryCode == "IN") {
				var stateCode = $('option:selected', "#state").attr('data-code');
				if(stateCode == "OI") {
					$('#state').val("");
					$("#state").select2();
				}
			} else {
				$('#state').val($('#state option[data-code="OI"]').val());
				$("#state").select2();
			}
		});

		$('#submit').click(function () {
			var mesg = {};
			if (vali.validate(mesg, 'form')) {
				return true;
			}
			return false;
		});
	});
</script>