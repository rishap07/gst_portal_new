<?php
$obj_plan = new plan();
if( !isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '' ) {
    $obj_plan->redirect(PROJECT_URL);
    exit();
}

if( isset($_GET['action']) && $_GET['action'] == 'deletePlan' && isset($_GET['id'])) {
    
    $planid = $_GET['id'];
    $planDetail = $obj_plan->getPlanDetails($planid);
       
    if( $planDetail['status'] == "success" ) {
        
        if($obj_plan->deletePlan($planDetail['data']->id)){
            $obj_plan->redirect(PROJECT_URL."?page=plan_list");
        }
        
    } else {
        $obj_plan->setError($obj_plan->validationMessage['noplanexist']);
        $obj_plan->redirect(PROJECT_URL."?page=plan_list");
    }
}
?>

<!--========================sidemenu over=========================-->
<div class="admincontainer greybg">
    <div class="formcontainer">
        <div>
            <a class='addnew' href='<?php echo PROJECT_URL;?>/?page=plan_addplan'>Add New</a>
        </div>
        <h1>Plan Category</h1>
        <hr class="headingborder">
        <h2 class="greyheading">Plan Category Listing</h2>
        
        <div class="adminformbx">
            <?php $obj_plan->showErrorMessage(); ?>
            <?php $obj_plan->showSuccessMessge(); ?>
            <?php $obj_plan->unsetMessage(); ?>
        
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tablecontent" id="mainTable">
                
                <thead>
                    <tr>
                        <th align='left'>#</th>
                        <th align='left' width="100px;">Name</th>
                        <th align='left' width="500px;">Description</th>
                        <th align='left'>No Of Client</th>
                        <th align='left'>Category</th>
                        <th align='left'>Price</th>
                        <th align='left'>Visible</th>
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
                    "sAjaxSource": "<?php echo PROJECT_URL; ?>/?ajax=plan_list",
                    "fnServerParams": function (aoData) {
                    },
                    "iDisplayLength": 50
                });
            }
        };
    }();
</script>