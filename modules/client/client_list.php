<?php
$obj_client = new client();
if( !isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '' ) {
    $obj_client->redirect(PROJECT_URL);
    exit();
}

if( isset($_GET['action']) && $_GET['action'] == 'deleteClient' && isset($_GET['id']) && $obj_client->validateId($_GET['id'])) {
    
    $userDetail = $obj_client->getUserDetailsById( $obj_client->sanitize($_GET['id']) );
    if( $userDetail['status'] == "success" ) {

        if($obj_client->deleteClientUser($userDetail['data']->user_id)){
            $obj_client->redirect(PROJECT_URL."?page=client_list");
        }
        
    } else {
        $obj_client->setError($obj_plan->validationMessage['usernotexist']);
        $obj_client->redirect(PROJECT_URL."?page=client_list");
    }
}
?>

<!--========================sidemenu over=========================-->
<div class="admincontainer greybg">
    <div class="formcontainer">
        <div>
            <a class='addnew' href='<?php echo PROJECT_URL;?>/?page=client_update'>Add New</a>
        </div>
        <h1>Admin User</h1>
        <hr class="headingborder">
        <h2 class="greyheading">Client User Listing</h2>
        
        <div class="adminformbx">
            <?php $obj_client->showErrorMessage(); ?>
            <?php $obj_client->showSuccessMessge(); ?>
            <?php $obj_client->unsetMessage(); ?>
        
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tablecontent" id="mainTable">
                
                <thead>
                    <tr>
                        <th align='left'>#</th>
                        <th align='left'>Name</th>
                        <th align='left'>Username</th>
                        <th align='left'>Email</th>
                        <th align='left'>Company Name</th>
                        <th align='left'>Phone Number</th>
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
                    "sAjaxSource": "<?php echo PROJECT_URL; ?>/?ajax=client_list",
                    "fnServerParams": function (aoData) {
                    },
                    "iDisplayLength": 50
                });
            }
        };
    }();
</script>