<?php
$obj_master = new master();
if(!$obj_master->can_read('master_item')) {
    $obj_master->setError($obj_master->getValMsg('can_read'));
    $obj_master->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}

if(isset($_POST['submit']) && $_POST['submit']=='submit') {

    if(!$obj_master->can_create('master_item')) {
        $obj_master->setError($obj_master->getValMsg('can_create'));
        $obj_master->redirect(PROJECT_URL."/?page=master_item");
        exit();
    }
	
    if($obj_master->addItem()) {
        $obj_master->redirect(PROJECT_URL."/?page=master_item");
    }
}

if(isset($_POST['submit']) && $_POST['submit']=='update' && isset($_GET['id'])) {
    
	if(!$obj_master->can_update('master_item')) {
        $obj_master->setError($obj_master->getValMsg('can_update'));
        $obj_master->redirect(PROJECT_URL."/?page=master_item");
        exit();
    }
	
    if($obj_master->updateItem()){
        $obj_master->redirect(PROJECT_URL."/?page=master_item");
    }
}
$dataArr = array();
if(isset($_GET['id'])){
    $dataArr = $obj_master->findAll($obj_master->getTableName('item'),"is_deleted='0' and item_id='".$obj_master->sanitize($_GET['id'])."'");
}
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Item</h1></div>
           <hr class="headingborder">
   <?php $obj_master->showErrorMessage(); ?>
        <?php $obj_master->showSuccessMessge(); ?>
        <?php $obj_master->unsetMessage(); ?>
        <div class="whitebg formboxcontainer">
     
        <h2 class="greyheading"><?php echo isset($_GET['id']) ? 'Edit Item' : 'Add New Item'; ?></h2>
        <form method="post" enctype="multipart/form-data" id='form'>
                <div class="row">
                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                       
                            <label>Item<span class="starred">*</span></label>
                            <input type="text" placeholder="Item" name='item_name' data-bind="content" class="required form-control" value='<?php if(isset($_POST['item_name'])){ echo $_POST['item_name'];}else if(isset($dataArr[0]->item_name)){ echo $dataArr[0]->item_name;}?>' />
                            <span class="greysmalltxt"></span>
						</div>
                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                 
                            <label>HSN Code<span class="starred">*</span></label>
                            <input type="text" placeholder="HSN Code"  name='hsn_code' data-bind="content" class="required form-control" value='<?php if(isset($_POST['hsn_code'])){ echo $_POST['hsn_code'];}else if(isset($dataArr[0]->hsn_code)){ echo $dataArr[0]->hsn_code;}?>'/>
                        </div>
   <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                         
                            <label>Item Type<span class="starred">*</span></label>
                            <select name="item_type" class="required form-control">
                                <option value="">Select Type</option>
								<option value="0" <?php if(isset($_POST['item_type']) && $_POST['item_type']==='0'){ echo 'selected="selected"'; } else if(isset($dataArr[0]->item_type) && $dataArr[0]->item_type==='0'){ echo 'selected="selected"'; } ?>>Goods</option>
                                <option value="1" <?php if(isset($_POST['item_type']) && $_POST['item_type']==='1'){ echo 'selected="selected"'; } else if(isset($dataArr[0]->item_type) && $dataArr[0]->item_type==='1'){ echo 'selected="selected"'; } ?>>Services</option>
                                
                            </select>
                        </div>
						
                        <div class="clear"></div>

                  <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                           
                            <label>Applicable Taxes<span class="starred">*</span></label>
                            <select name="applicable" class="required form-control">
                                <option value="0" <?php if(isset($_POST['item_type']) && $_POST['item_type']==='0'){ echo 'selected="selected"'; } else if(isset($dataArr[0]->item_type) && $dataArr[0]->item_type==='0'){ echo 'selected="selected"'; } ?>>Applicable</option>
                                <option value="1" <?php if(isset($_POST['item_type']) && $_POST['item_type']==='1'){ echo 'selected="selected"'; } else if(isset($dataArr[0]->item_type) && $dataArr[0]->item_type==='1'){ echo 'selected="selected"'; } ?>>Non-GST</option>
                                <option value="2" <?php if(isset($_POST['item_type']) && $_POST['item_type']==='2'){ echo 'selected="selected"'; } else if(isset($dataArr[0]->item_type) && $dataArr[0]->item_type==='2'){ echo 'selected="selected"'; } ?>>Exempted</option>
                            </select>
                        </div>
						 <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                           <!-- added new options exempted and non gst-->
                       
                            <label>IGST Tax Rate(%)<span class="starred">*</span></label>
                            <input type="text" placeholder="IGST Tax Rate" name='igst_tax_rate' data-bind="demical" class="required form-control" id='igst_tax_rate' value='<?php if(isset($_POST['igst_tax_rate'])){ echo $_POST['igst_tax_rate'];}else if(isset($dataArr[0]->igst_tax_rate)){ echo $dataArr[0]->igst_tax_rate;}?>'/>
                        </div>
    <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                           
                            <label>CSGT Tax Rate(%)<span class="starred">*</span></label>
                            <input type="text" placeholder="CSGT Tax Rate" name='csgt_tax_rate' data-bind="demical" class="required form-control" id='csgt_tax_rate' value='<?php if(isset($_POST['csgt_tax_rate'])){ echo $_POST['csgt_tax_rate'];}else if(isset($dataArr[0]->csgt_tax_rate)){ echo $dataArr[0]->csgt_tax_rate;}?>'/>
                        </div>
						  <div class="clear"></div>
						 <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                                  
                            <label>SGST Tax Rate(%)<span class="starred">*</span></label>
                            <input type="text" placeholder="SGST Tax Rate" name='sgst_tax_rate' data-bind="demical" class="required form-control" id='sgst_tax_rate' value='<?php if(isset($_POST['sgst_tax_rate'])){ echo $_POST['sgst_tax_rate'];}else if(isset($dataArr[0]->sgst_tax_rate)){ echo $dataArr[0]->sgst_tax_rate;}?>'/>
                        </div>
						<div class="col-md-4 col-sm-4 col-xs-12 form-group">
                            <label>Cess Tax Rate(%)<span class="starred">*</span></label>
                            <input type="text" placeholder="Cess Tax Rate" name='cess_tax_rate' data-bind="demical" class="required form-control" id='cess_tax_rate' value='<?php if(isset($_POST['cess_tax_rate'])){ echo $_POST['cess_tax_rate'];}else if(isset($dataArr[0]->cess_tax_rate)){ echo $dataArr[0]->cess_tax_rate;}?>'/>
                        </div>
						<div class="col-md-4 col-sm-4 col-xs-12 form-group">
                            <label>Status<span class="starred">*</span></label>
                            <select name="status" class="form-control">
                                <option value="1" <?php if(isset($_POST['status']) && $_POST['status'] === '1'){ echo 'selected="selected"'; } else if(isset($dataArr[0]->status) && $dataArr[0]->status==='1'){ echo 'selected="selected"'; } ?>>Active</option>
                                <option value="0" <?php if(isset($_POST['status']) && $_POST['status'] === '0'){ echo 'selected="selected"'; } else if(isset($dataArr[0]->status) && $dataArr[0]->status==='0'){ echo 'selected="selected"'; } ?>>In-Active</option>
                            </select>
                        </div>
                        
                     <div class="clear height30"></div>
                               						  
						<div class="adminformbxsubmit" style="width:100%;"> 
                            <div class="tc">
                                <input type='submit' class="btn btn-success"  name='submit' value='<?php echo isset($_GET['id']) ? 'update' : 'submit'; ?>' id='submit'>
                                <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=master_item"; ?>';" class="btn btn-danger" />
                            </div> 
                        </div>
                </div>
                   
                </div>
   

              
                    
                </div>

           

              

            </form>
        </div>

<script>
    $(document).ready(function () {
        
		$("#state").change(function () {
           val1 = $(this).val().split(":");
           $("#state_code").val(val1[1]);
        });
        
		$('#submit').click(function () {
            var mesg = {};
            if (vali.validate(mesg,'form')) {
                return true;
            }
            return false;
        });
    });
</script>   