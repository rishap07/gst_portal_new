<?php
$obj_client = new client();
if (!isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '') {
    $obj_client->redirect(PROJECT_URL);
    exit();
}
if (!$obj_client->can_read('subscriber_subuser_list')) {

    $obj_client->setError($obj_client->getValMsg('can_read'));
    $obj_client->redirect(PROJECT_URL . "/?page=dashboard");
    exit();
}
if (isset($_GET['action']) && $_GET['action'] == 'deleteClient' && isset($_GET['id']) && $obj_client->validateId($_GET['id'])) {
    if (!$obj_client->can_delete('subscriber_subuser_list')) {

        $obj_client->setError($obj_client->getValMsg('can_delete'));
        $obj_client->redirect(PROJECT_URL . "/?page=subscriber_subuser_list");
        exit();
    }
    $userDetail = $obj_client->getUserDetailsById($obj_client->sanitize($_GET['id']));
    if ($userDetail['status'] == "success") {
        if ($obj_client->deleteClientUser($userDetail['data']->user_id)) {
            $obj_client->redirect(PROJECT_URL . "?page=subscriber_subuser_list");
        }
    } else {
        $obj_client->setError($obj_plan->validationMessage['usernotexist']);
        $obj_client->redirect(PROJECT_URL . "?page=subscriber_subuser_list");
    }
}
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <h1>Business SubUser</h1>
        <div class="whitebg formboxcontainer">
            <div>
                <a class='btn btn-default btn-success btnwidth addnew' href='<?php echo PROJECT_URL; ?>/?page=subscriber_subuser_update'>Add New</a>
            </div>
            <div class="clear height10"></div>
            <?php $obj_client->showErrorMessage(); ?>
            <?php $obj_client->showSuccessMessge(); ?>
            <?php $obj_client->unsetMessage(); ?>
            <h2 class="greyheading">Business SubUser Listing</h2>
            <div class="adminformbx">
                <div class="clear"></div>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable">
                    <thead>
                        <tr>
                            <th align='left'>#</th>
                            <th align='left'>Name</th>
                            <th align='left'>Username</th>
                            <th align='left'>Email</th>
							  <th align='left'>Number of Client</th>
                            <th align='left'>Company Name</th>
                            <th align='left'>Phone Number</th>
                            <th align='left'>Status</th>
                            <th align='left'>Action</th>
                        </tr>
                    </thead>
                </table>
            </div> 
        </div>
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
						{"bSortable": false}
                    ],
                    "sDom": "lfrtip",
                    "aLengthMenu": [
                        [5,10, 20, 50, 100, 500],
                        [5,10, 20, 50, 100, 500],
                    ],
                    "bProcessing": true,
                    "bServerSide": true,
                    "bStateSave": false,
                    "bDestroy": true,
                    "sAjaxSource": "<?php echo PROJECT_URL; ?>/?ajax=subscriber_subuser_list",
                    "fnServerParams": function (aoData) {
                    },
                    "iDisplayLength": 20
                });
            }
        };
    }();
</script>