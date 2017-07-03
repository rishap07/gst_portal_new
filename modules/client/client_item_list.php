<?php
$obj_client = new client();
if(!$obj_client->can_read('client_master_item')) {

    $obj_client->setError($obj_client->getValMsg('can_read'));
    $obj_client->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}

if( isset($_GET['action']) && $_GET['action'] == 'deleteItem' && isset($_GET['id']) && $obj_client->validateId($_GET['id'])) {
        
    if(!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])){
        
        $obj_client->setError('Invalid access to files');
    } else {

        if($obj_client->deleteClientItem($obj_client->sanitize($_GET['id']))){
            $obj_client->redirect(PROJECT_URL."?page=client_item_list");
        }
    }
}
?>
<div class="admincontainer greybg">
    <div class="formcontainer">
        <div>
            <a class='addnew' href='<?php echo PROJECT_URL;?>/?page=client_item_update'>Add New</a>
        </div>
        <h1>Item</h1>
        <hr class="headingborder">
        <h2 class="greyheading">Item Listing</h2>
        <div class="adminformbx">
            <?php $obj_client->showErrorMessage(); ?>
            <?php $obj_client->showSuccessMessge(); ?>
            <?php $obj_client->unsetMessage(); ?>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tablecontent" id="mainTable">
                <thead>
                    <tr>
                        <th align='left'>#</th>
                        <th align='left'>Item</th>
                        <th align='left'>HSN/SAC Category</th>
                        <th align='left'>HSN/SAC Code</th>
                        <th align='left'>Unit Price(Rs)</th>
                        <th align='left'>Status</th>
                        <th align='left'>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
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
                    "sAjaxSource": "<?php echo PROJECT_URL; ?>/?ajax=client_item_list",
                    "fnServerParams": function (aoData) {
                    },
                    "iDisplayLength": 50
                });
            }
        };
    }();
</script>