<?php
$obj_master = new master();
if(isset($_POST['submit']) && $_POST['submit']=='submit')
{
    if($obj_master->addReceiver())
    {
        $obj_master->redirect(PROJECT_URL."/?page=master_receiver");
    }
}
if(isset($_POST['submit']) && $_POST['submit']=='update' && isset($_GET['id']))
{
    if($obj_master->updateReceiver())
    {
        $obj_master->redirect(PROJECT_URL."/?page=master_receiver");
    }
}
$dataArr = array();
if(isset($_GET['id']))
{
    $dataArr = $obj_master->findAll($obj_master->getTableName('receiver'),"is_deleted='0' and receiver_id='".$obj_master->sanitize($_GET['id'])."'");
}
?>
<div class="admincontainer greybg">
    <div class="formcontainer">
        <?php $obj_master->showErrorMessage(); ?>
        <?php $obj_master->showSuccessMessge(); ?>
        <?php $obj_master->unsetMessage(); ?>
        <h1>Receiver</h1>
        <hr class="headingborder">
        <h2 class="greyheading"><?php echo isset($_GET['id']) ? 'Edit Receiver' : 'Add New Receiver'; ?></h2>
        <form method="post" enctype="multipart/form-data" id='form'>
            <div class="adminformbx">
                <div class="kycform">
                    <div class="kycmainbox">
                        <div class="clear"></div>
                        <div class="formcol">
                            <label>GSTID<span class="starred">*</span></label>
                            <input type="text" placeholder="GSTID" name='gstid' data-bind="content" class="required" value='<?php if(isset($_POST['gstid'])){ echo $_POST['gstid'];}else if(isset($dataArr[0]->gstid)){ echo $dataArr[0]->gstid;}?>' />
                            <span class="greysmalltxt"></span> </div>
                        <div class="formcol two">
                            <label>Name<span class="starred">*</span></label>
                            <input type="text" placeholder="Name"  name='name' data-bind="content" class="required" value='<?php if(isset($_POST['name'])){ echo $_POST['name'];}else if(isset($dataArr[0]->name)){ echo $dataArr[0]->name;}?>'/>
                        </div>
                        <div class="formcol third">
                            <label>Address<span class="starred">*</span></label>
                            <input type="text" placeholder="Address" name='address'  data-bind="content" class="required" value='<?php if(isset($_POST['address'])){ echo $_POST['address'];}else if(isset($dataArr[0]->address)){ echo $dataArr[0]->address;}?>'/>
                        </div>
                        <div class="formcol">
                            <label>State<span class="starred">*</span></label>
                            <select name='state' id='state' name='state' class='required' placeholder="GSTID">
                                <?php
                                $dataStateArrs = $obj_master->get_results("select * from ".$obj_master->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc");
                                if(!empty($dataStateArrs))
                                {
                                    ?>
                                    <option value=''>Select State</option>
                                    <?php
                                    foreach($dataStateArrs as $dataStateArr)
                                    {
                                        ?>
                                        <option value='<?php echo $dataStateArr->state_id;?>:<?php echo $dataStateArr->state_code;?>' <?php if(isset($_POST['state']) && $_POST['state']===$dataStateArr->state_id.":".$dataStateArr->state_code){ echo 'selected';}else if(isset($dataArr[0]->state_code) && isset($dataArr[0]->state) && $dataStateArr->state_id.":".$dataStateArr->state_code ==$dataArr[0]->state.":".$dataArr[0]->state_code){ echo 'selected';} ?>><?php echo $dataStateArr->state_name;?></option>
                                        <?php
                                    }
                                }
                                else
                                {
                                    ?>
                                    <option value=''>No State Found</option>
                                    <?php
                                }
                                ?>
                            </select>
                            <span class="greysmalltxt"></span> 
                        </div>
                        <div class="formcol two">
                            <label>State Code<span class="starred">*</span></label>
                            <input type="text" placeholder="State Code" name='state_code' readonly="" class="readonly required" id='state_code' value='<?php if(isset($_POST['state_code'])){ echo $_POST['state_code'];}else if(isset($dataArr[0]->address)){ echo $dataArr[0]->state_code;}?>'/>
                        </div>
                        <div class="formcol third">
                            <label>Status<span class="starred">*</span></label>
                            <select name="status">
                                <option value="1" <?php if(isset($_POST['status']) &&  $_POST['status']==='1'){ echo 'selected';}else if(isset($dataArr[0]->state_code) && $dataArr[0]->state_code==='1'){ echo 'selected';}?>>Active</option>
                                <option value="0" <?php if(isset($_POST['status']) &&  $_POST['status']==='0'){ echo 'selected';}else if(isset($dataArr[0]->state_code) && $dataArr[0]->state_code==='0'){ echo 'selected';}?>>In-Active</option>
                            </select>
                        </div>
                        <div class="clear height30"></div>
                        <div class="adminformbxsubmit" style="width:100%;"> 
                            <div class="tc">
                                <input type='submit' class="btn orangebg" name='submit' value='<?php echo isset($_GET['id']) ? 'update' : 'submit'; ?>' id='submit'>
                                <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=master_receiver"; ?>';" class="btn redbg" class="redbtn marlef10"/>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
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
    