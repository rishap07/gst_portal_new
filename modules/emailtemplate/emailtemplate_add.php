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

if(!$obj_emailtemplate->can_create('emailtemplate_list')) {

    $obj_emailtemplate->setError($obj_emailtemplate->getValMsg('can_create'));
    $obj_emailtemplate->redirect(PROJECT_URL."/?page=emailtemplate_list");
    exit();
}

if( isset($_POST['submit_add_emailtemplate']) && $_POST['submit_add_emailtemplate'] == 'SUBMIT' ) {

    if(!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])){
        
        $obj_emailtemplate->setError('Invalid access to files');
    } else {

        if($obj_emailtemplate->addEmailTemplate()){

            $obj_emailtemplate->redirect(PROJECT_URL."?page=emailtemplate_add");
        }
    }
}
?>
<script src="http://cdn.ckeditor.com/4.5.5/standard/ckeditor.js"></script>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Email Template</h1></div>
           <hr class="headingborder">
           <div class="clear"></div>
           <?php $obj_emailtemplate->showErrorMessage(); ?>
            <?php $obj_emailtemplate->showSuccessMessge(); ?>
            <?php $obj_emailtemplate->unsetMessage(); ?>

       
        <div class="whitebg formboxcontainer">
           <h2 class="greyheading">Add Email Template</h2>
     
        <div class="clear"></div>

        <form name="add-emailtemplate" id="add-emailtemplate" method="POST">
            <div class="row">          
                <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                    <label>Name<span class="starred">*</span></label>
                    <input type="text" name="emailtemplate_name" id="emailtemplate_name" placeholder="Enter Template name" class="required form-control" data-bind="content" value="<?php echo isset($_POST['emailtemplate_name']) ? $_POST['emailtemplate_name'] : ''; ?>" />
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                    <label>Subject<span class="starred">*</span></label>
                    <input type="text" name="emailtemplate_subject" id="emailtemplate_subject" placeholder="Enter Template Subject" class="required form-control" data-bind="content" value="<?php echo isset($_POST['emailtemplate_subject']) ? $_POST['emailtemplate_subject'] : ''; ?>" />
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                          
                    <label>Status<span class="starred">*</span></label>
                    <div class="clear"></div>
                    <input type="radio" name="emailtemplate_status" checked="checked" value="1" /><span>Active</span> <input type="radio" name="emailtemplate_status" value="0" /><span>Inactive</span>
                </div>
                <div class="clear"></div>
                <div class="clear"></div>
                <div class="col-md-12 col-sm-4 col-xs-12 form-group">
                    <label>Body<span class="starred">*</span></label>
                    <textarea name="emailtemplate_body" id="emailtemplate_body" placeholder="Enter body" class=" form-control" ><?php echo isset($_POST['emailtemplate_body']) ? $_POST['emailtemplate_body'] : ''; ?></textarea>
					<script>
                      CKEDITOR.replace( 'emailtemplate_body' );
                    </script>
                </div>

                 <div class="adminformbxsubmit" style="width:100%;">
                 
                    <div class="tc">
                    <input type="submit" name="submit_add_emailtemplate" id="submit_add_emailtemplate" value="SUBMIT" class="btn btn-success">
                     <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=emailtemplate_list"; ?>';" class="btn btn-danger"/>
                    <div class="clear height20"></div>
                </div>
            </div>
        </form>
                </div>

<!--========================sidemenu over=========================-->

<script>
    $(document).ready(function () {
        $('#submit_add_emailtemplate').click(function () {
            var mesg = {};
            if (vali.validate(mesg,'add-emailtemplate')) {
                return true;
            }
            return false;
        });
    });
</script>