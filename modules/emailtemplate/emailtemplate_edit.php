<?php
$obj_emailtemplate = new emailtemplate();
if( !isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '' ) {
    $obj_emailtemplate->redirect(PROJECT_URL);
    exit();
}

if(!$obj_emailtemplate->can_read('emailtemplate_list')) {

    $obj_emailtemplate->setError($obj_emailtemplate->getValMsg('can_read'));
    $obj_emailtemplate->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}

if( isset($_GET['action']) && $_GET['action'] == 'editEmailTemplate' && isset($_GET['id']) && intval($_GET['id']) > 0) {
    
    $emailtemplte_id = $_GET['id'];
    $emailtemplte_Detail = $obj_emailtemplate->getEmailTemplateDetails($emailtemplte_id);
    
    if( $emailtemplte_Detail['status'] == "success" ) {
        $emailtemplte_Data = $emailtemplte_Detail['data'];
    } else {
        $obj_emailtemplate->setError($emailtemplte_Detail['message']);
        $obj_emailtemplate->redirect(PROJECT_URL."?page=emailtemplate_list");
    }

} else {
    $obj_emailtemplate->redirect(PROJECT_URL."?page=emailtemplate_list");
}

if( isset($_POST['submit_edit_emailtemplate']) && $_POST['submit_edit_emailtemplate'] == 'SUBMIT' ) {

    if(!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])){
        
        $obj_emailtemplate->setError('Invalid access to files');
    } else {

        if($obj_emailtemplate->editEmailTemplate()){

            $obj_emailtemplate->redirect(PROJECT_URL."?page=emailtemplate_list");
        }
    }
}

?>
<script src="http://cdn.ckeditor.com/4.5.5/standard/ckeditor.js"></script>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Edit plan</h1></div>
           <hr class="headingborder">
            <?php $obj_emailtemplate->showErrorMessage(); ?>
            <?php $obj_emailtemplate->showSuccessMessge(); ?>
            <?php $obj_emailtemplate->unsetMessage(); ?>

            <div class="clear"></div>
       
            <div class="whitebg formboxcontainer">
           <h2>Edit Email Template Period</h2>
            <div class="clear"></div>

            <form name="edit-emailtemplate" id="edit-emailtemplate" method="POST">
            <div class="row">
    			<div class="col-md-4 col-sm-4 col-xs-12 form-group">
                    <input type="hidden" name="emailtemplate_id" id="emailtemplate_id" value="<?php echo $emailtemplte_Data->id; ?>">
             
                    <label>Name<span class="starred">*</span></label>
                    <input type="text" name="emailtemplate_name" id="emailtemplate_name" placeholder="Enter emailtemplate name" class="required form-control" data-bind="content" value="<?php echo isset($emailtemplte_Data->name) ?  $emailtemplte_Data->name : ''; ?>"/>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12 form-group">                  
                    
                    <label>Subject<span class="starred">*</span></label>
                    <input type="text" name="emailtemplate_subject" id="emailtemplate_name" placeholder="Enter emailtemplate subject" class="required form-control" data-bind="content" value="<?php echo isset($emailtemplte_Data->subject) ?  $emailtemplte_Data->subject : ''; ?>"/>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                <label>Status<span class="starred">*</span></label>
                <div class="clear"></div>
                <input type="radio" name="emailtemplate_status" <?php if($emailtemplte_Data->status == '1') { echo 'checked="checked"'; } ?> value="1" /><span>Active</span> <input type="radio" name="emailtemplate_status" <?php if($emailtemplte_Data->status == '0') { echo 'checked="checked"'; } ?> value="0" /><span>Inactive</span>
            </div>                                     
                <div class="clear"></div>
                <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                    <label>Body<span class="starred">*</span></label>
                    <textarea name="emailtemplate_body" id="emailtemplate_body" placeholder="Enter body" class=" form-control" ><?php echo isset($emailtemplte_Data->body) ?  $emailtemplte_Data->body : ''; ?></textarea>
                    <script>
                      CKEDITOR.replace( 'emailtemplate_body' );
                    </script>
                </div>
                <div class="clear"></div>
    			<div class="adminformbxsubmit" style="width:100%;">
    			  <div class="tc">
                    <input type="submit" name="submit_edit_emailtemplate" id="submit_edit_emailtemplate" value="SUBMIT" class="btn btn-danger">
                    <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=emailtemplate_list"; ?>';" class="btn btn-danger" />
                    <div class="clear height20"></div>
                </div>
    				
            </div>
               
            </div>

            </form>
        </div>
                   

       

<!--========================sidemenu over=========================-->

<script>
    $(document).ready(function () {
        $('#submit_edit_emailtemplate').click(function () {
            var mesg = {};
            if (vali.validate(mesg,'edit-emailtemplate')) {
                return true;
            }
            return false;
        });
    });
</script>