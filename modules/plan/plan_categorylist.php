<?php
$obj_plan = new plan();
if( !isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '' ) {
    $obj_plan->redirect(PROJECT_URL);
    exit();
}

if(!$obj_plan->can_read('plan_category_list')) {

    $obj_plan->setError($obj_plan->getValMsg('can_read'));
    $obj_plan->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}

if( isset($_GET['action']) && $_GET['action'] == 'deletePlanCategory' && isset($_GET['id'])) {
    
	if(!$obj_plan->can_delete('plan_category_list')) {

		$obj_plan->setError($obj_plan->getValMsg('can_delete'));
		$obj_plan->redirect(PROJECT_URL."/?page=plan_list");
		exit();
	}
	
    $categoryid = $_GET['id'];
    $planCategoryDetail = $obj_plan->getPlanCategoryDetails($categoryid);
       
    if( $planCategoryDetail['status'] == "success" ) {
        
        if($obj_plan->deletePlanCategory($planCategoryDetail['data']->id)){
            $obj_plan->redirect(PROJECT_URL."?page=plan_categorylist");
        }
        
    } else {
        $obj_plan->setError($planCategoryDetail['message']);
        $obj_plan->redirect(PROJECT_URL."?page=plan_categorylist");
    }
}
?>

<!--========================sidemenu over=========================-->
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

         <h1>Plan Category</h1>
        <div class="whitebg formboxcontainer">
        <div>
            <a class="btn btn-default btn-success btnwidth addnew" href='<?php echo PROJECT_URL;?>/?page=plan_addcategory'>Add New</a>
        </div>
     
      <div class="clear height10"></div>
            <?php $obj_plan->showErrorMessage(); ?>
            <?php $obj_plan->showSuccessMessge(); ?>
            <?php $obj_plan->unsetMessage(); ?>
        <h2 class="greyheading">Plan Category Listing</h2>
        
        <div class="adminformbx">
        
        
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable">
                
                <thead>
                    <tr>
                        <th align='left'>#</th>
                        <th align='left'>Name</th>
                        <th align='left'>Description</th>
                        <th align='left'>Status</th>
                        <th align='left'>Action</th>
                    </tr>
                </thead>

            </table>
        </div>
<!--========================adminformbox over=========================-->    
    </div>
<!--========================admincontainer over=========================-->
</div>
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
                    "sAjaxSource": "<?php echo PROJECT_URL; ?>/?ajax=plan_categorylist",
                    "fnServerParams": function (aoData) {
                    },
                    "iDisplayLength": 50
                });
            }
        };
    }();
</script>