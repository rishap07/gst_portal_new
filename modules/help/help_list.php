<?php
$obj_help = new help();

if( !isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '' ) {
    $obj_help->redirect(PROJECT_URL);
    exit();
}

/*if(!$obj_help->can_read('help_list')) {

    $obj_help->setError($obj_help->getValMsg('can_read'));
    $obj_help->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}

if(isset($_GET['id']) && $_GET['id']!='') {
    
	if(!$obj_help->can_delete('help_list')) {

		$obj_help->setError($obj_help->getValMsg('can_delete'));
		$obj_help->redirect(PROJECT_URL."/?page=help_list");
		exit();
	}
	
    $planid = $_GET['id'];
    $planDetail = $obj_help->getPlanDetails($planid);

       
    if( $planDetail['status'] == "success" ) {
        
        if($obj_plan->deletePlan($planDetail['data']->id)){
            $obj_help->redirect(PROJECT_URL."?page=help_list");
        }
        
    } else {
        $obj_help->setError($obj_help->validationMessage['noplanexist']);
        $obj_help->redirect(PROJECT_URL."?page=help_list");
    }
}*/
/* get current user data */

?>

<!--========================sidemenu over=========================-->
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

       <h1>Help List</h1>
        <div class="whitebg formboxcontainer">
        <div>
            <a class='btn btn-default btn-success btnwidth addnew' href='<?php echo PROJECT_URL;?>/?page=help_update'>Add New</a>
        </div>
         <div class="clear height10"></div>
       
          <?php $obj_help->showErrorMessage(); ?>
            <?php $obj_help->showSuccessMessge(); ?>
            <?php $obj_help->unsetMessage(); ?>
        <h2 class="greyheading">Help Listing</h2>
        
        <div class="adminformbx">
          
        
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable">
                
                <thead>
                    <tr>
                        <th align='left'>#</th>
                    
                        <th align='left'>Title</th>
                        <th align='left' width="200px">Message</th>
                       
                        <th align='left'>Start Time</th>
                         <th align='left'>End Time</th>
                          <th align='left'>Document</th>
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
                    "sAjaxSource": "<?php echo PROJECT_URL; ?>/?ajax=help_list",
                    "fnServerParams": function (aoData) {
                    },
                    "iDisplayLength": 50
                });
            }
        };
    }();
</script>