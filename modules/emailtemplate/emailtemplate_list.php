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

if( isset($_GET['action']) && $_GET['action'] == 'deleteEmailTemplate' && isset($_GET['id'])) {
    
	if(!$obj_emailtemplate->can_delete('emailtemplate_list')) {

		$obj_emailtemplate->setError($obj_emailtemplate->getValMsg('can_delete'));
		$obj_emailtemplate->redirect(PROJECT_URL."/?page=emailtemplate_list");
		exit();
	}
	
    $planid = $_GET['id'];
    $planDetail = $obj_emailtemplate->getEmailTemplateDetails($planid);

       
    if( $planDetail['status'] == "success" ) {
        
        if($obj_emailtemplate->deleteEmailTemplate($planDetail['data']->id)){
            $obj_emailtemplate->redirect(PROJECT_URL."?page=emailtemplate_list");
        }
        
    } else {
        $obj_emailtemplate->setError($obj_emailtemplate->validationMessage['noplanexist']);
        $obj_emailtemplate->redirect(PROJECT_URL."?page=emailtemplate_list");
    }
}
?>

<!--========================sidemenu over=========================-->
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

       <h1>Email Template </h1>
        <div class="whitebg formboxcontainer">
        <div>
            <a class='btn btn-default btn-success btnwidth addnew' href='<?php echo PROJECT_URL;?>/?page=emailtemplate_add'>Add New</a>
        </div>
         <div class="clear height10"></div>
       
          <?php $obj_emailtemplate->showErrorMessage(); ?>
            <?php $obj_emailtemplate->showSuccessMessge(); ?>
            <?php $obj_emailtemplate->unsetMessage(); ?>
        <h2 class="greyheading">Email Template Listing</h2>
        
        <div class="adminformbx">
          
        
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable">
                
                <thead>
                    <tr>
                        <th align='left'>#</th>
                        <th align='left'>Name</th>
                        <th align='left'>Subject</th>
                        <th align='left'>Body</th>
                        <th align='left'>Status</th>
                        <th align='left'>Action</th>
                    </tr>
                </thead>

            </table>
        </div>
<!--========================adminformbox over=========================-->    
    </div>
<!--========================admincontainer over=========================-->
    </div></div>
<div class="clear height80">
</div>
<script>
    $(document).ready(function () {
        TableManaged.init();
    });
    
    var TableManaged = function () {
        return {
            init: function () {
                if (!jQuery().dataTable) {
                    return;
                }
                var sgHREF = window.location.pathname;
                $.ajaxSetup({'type': 'POST', 'url': sgHREF, 'dataType': 'json'});
                $.extend($.fn.dataTable.defaults, {'sServerMethod': 'POST'});
                $('#mainTable').dataTable({
                    "aoColumns": [
                        {"bSortable": false},
                        {"bSortable": false},
                        {"bSortable": false},
                        {"bSortable": false},
                          {"bSortable": false},
                        {"bSortable": false}
                    ],
                    "sDom": "lfrtip",
                    "aLengthMenu": [
                        [10, 20, 50, 100, 500],
                        [10, 20, 50, 100, 500],
                    ],
                    "bProcessing": true,
                    "bServerSide": true,
                    "bStateSave": false,
                    "bDestroy": true,
                    "sAjaxSource": "<?php echo PROJECT_URL; ?>/?ajax=emailtemplate_list",
                    "fnServerParams": function (aoData) {
                    },
                    "iDisplayLength": 50
                });
            }
        };
    }();
</script>