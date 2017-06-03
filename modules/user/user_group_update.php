<?php
$obj_users = new users();
if(isset($_POST['submit']) && $_POST['submit']=='update' && isset($_GET['id']))
{
    if($obj_users->userGroupPermission())
    {
        $obj_users->redirect(PROJECT_URL."/?page=user_group");
    }
}
?>
<div class="admincontainer greybg">
    <div class="formcontainer">
        <?php $obj_users->showErrorMessage(); ?>
        <?php $obj_users->showSuccessMessge(); ?>
        <?php $obj_users->unsetMessage(); ?>
        <h1>User Group Permission</h1>
        <hr class="headingborder">
        <h2 class="greyheading">Add/Update Group Permission</h2>
        <form method="post" enctype="multipart/form-data" id='form'>
            <div class="adminformbx">
                <div class="kycform">
                    <div class="kycmainbox">
                        <div class="clear"></div>
                        <table width="100%" border="1" cellspacing="0" bordercolor="#c6c6c6">
                            <thead>
                                <tr style="background-color:#dedcdc;font-weight: bold">
                                    <td valign="top">
                                        Role Name  : ( Description )
                                    </td>
                                    <td align="center" valign="top">
                                        View
                                    </td>
                                    <td align="center" valign="top">
                                        Create
                                    </td>
                                    <td align="center" valign="top">
                                        Update
                                    </td>
                                    <td align="center" valign="top">
                                        Delete
                                    </td>
                                </tr>
                            </thead>
                            <?php
                            $query = "select * from ".$obj_users->getTableName("user_role")." a left join ".$obj_users->getTableName("user_role_permission")." b on a.user_role_id=b.role_id and b.group_id='".$obj_users->sanitize($_GET['id'])."' and b.is_deleted='0' where  a.status='1' and a.is_deleted='0' order by a.role_name ";
                            $dataRoleArrs = $obj_users->get_results($query);
                            ?>
                            <tbody>
                                <?php
                                if(!empty($dataRoleArrs))
                                {
                                    foreach($dataRoleArrs as $dataRoleArr)
                                    {
                                        ?>
                                        <tr>
                                            
                                            <td valign="top">
                                                <?php echo $dataRoleArr->role_name;?>  : ( <?php echo $dataRoleArr->role_description;?> )
                                                <input type="hidden" name="user_role_id[]" value="<?php echo $dataRoleArr->user_role_id;?>">
                                            </td>
                                            <td align="center" valign="top">
                                                <input type="checkbox" name="view[<?php echo $dataRoleArr->user_role_id;?>]" <?php echo ($dataRoleArr->can_read=='1')? 'checked':'';?> value="1" class="checkall">
                                                
                                            </td>
                                            <td align="center" valign="top">
                                                <input type="checkbox" name="create[<?php echo $dataRoleArr->user_role_id;?>]" <?php echo ($dataRoleArr->can_create=='1')? 'checked':'';?> value="1" class="checkall">
                                            </td>
                                            <td align="center" valign="top">
                                                <input type="checkbox" name="update[<?php echo $dataRoleArr->user_role_id;?>]" <?php echo ($dataRoleArr->can_update=='1')? 'checked':'';?> value="1" class="checkall">
                                            </td>
                                            <td align="center" valign="top">
                                                <input type="checkbox" name="delete[<?php echo $dataRoleArr->user_role_id;?>]" <?php echo ($dataRoleArr->can_delete=='1')? 'checked':'';?> value="1" class="checkall">
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                                
                            </tbody>
                        </table>
                        <div class="clear height30"></div>
                        <div class="adminformbxsubmit" style="width:100%;"> 
                            <div class="tc">
                                <input type='submit' class="btn orangebg" name='submit' value='<?php echo isset($_GET['id']) ? 'update' : 'submit'; ?>' id='submit'>
                                <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=user_group"; ?>';" class="btn redbg" class="redbtn marlef10"/>
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
    