<?php
$obj_master = new master();
/*if(!$obj_master->can_read('master_unit')){

    $obj_master->setError($obj_master->getValMsg('can_read'));
    $obj_master->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}*/
?>
<div class="admincontainer greybg">
    <div class="formcontainer">
        <div>
            <a class='addnew' href='<?php echo PROJECT_URL;?>/?page=master_unit_update'>Add New</a>
        </div>
        <h1>Unit</h1>
        <hr class="headingborder">
        <h2 class="greyheading">Unit Listing</h2>
        <div class="adminformbx">
            <?php $obj_master->showErrorMessage(); ?>
            <?php $obj_master->showSuccessMessge(); ?>
            <?php $obj_master->unsetMessage(); ?>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tablecontent" id="mainTable">
                <thead>
                    <tr>
                        <th align='left' width="72">#</th>
                        <th align='left'>Unit</th>
                        <th align='left'>Unit Code</th>
                        <th align='left'>Status</th>
                        <th width="72">Action</th>
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
                    "sAjaxSource": "<?php echo PROJECT_URL; ?>/?ajax=master_unit",
                    "fnServerParams": function (aoData) {
                    },
                    "iDisplayLength": 50
                });
            }
        };
    }();
</script>