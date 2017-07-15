<?php
$obj_user = new users();
if( !isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '' ) {
    $obj_user->redirect(PROJECT_URL);
    exit();
}

if(!$obj_user->can_read('admin_list')) {

    $obj_user->setError($obj_user->getValMsg('can_read'));
    $obj_user->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}

if( isset($_GET['action']) && $_GET['action'] == 'deleteAdmin' && isset($_GET['id']) && $obj_user->validateId($_GET['id'])) {

    $userDetail = $obj_user->getUserDetailsById( $obj_user->sanitize($_GET['id']) );
    if( $userDetail['status'] == "success" ) {

        if($obj_user->deleteUser($userDetail['data']->user_id)){
            $obj_user->redirect(PROJECT_URL."?page=user_adminlist");
        }

    } else {
        $obj_user->setError($obj_plan->validationMessage['usernotexist']);
        $obj_user->redirect(PROJECT_URL."?page=user_adminlist");
    }
}
?>

<!--========================sidemenu over=========================-->
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

            <h1>Admin User</h1>
        <div class="whitebg formboxcontainer">
        <div>
            <a class='btn btn-default btn-success btnwidth addnew' href='<?php echo PROJECT_URL;?>/?page=user_adminupdate'>Add New</a>
        </div>
    <div class="clear height10"></div>
      
           <?php $obj_user->showErrorMessage(); ?>
            <?php $obj_user->showSuccessMessge(); ?>
            <?php $obj_user->unsetMessage(); ?>
        <h2 class="greyheading">Admin User Listing</h2>
        
        <div class="adminformbx">
         
        
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable">
                
                <thead>
                    <tr>
                        <th align='left'>#</th>
                        <th align='left'>Name</th>
                        <th align='left'>Username</th>
                        <th align='left'>Email</th>
                        <th align='left'>Phone Number</th>
                        <th align='left'>Company Name</th>
                        <th align='left'>Company Code</th>
                        <th align='left'>No Of Client</th>                        
                        <th align='left'>Status</th>
                        <th align='left'>Payment Status</th>
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
                    "sAjaxSource": "<?php echo PROJECT_URL; ?>/?ajax=user_adminlist",
                    "fnServerParams": function (aoData) {
                    },
                    "iDisplayLength": 50
                });
            }
        };
    }();
</script>