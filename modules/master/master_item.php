<?php
$obj_master = new master();
if(!$obj_master->can_read('master_item')) {

    $obj_master->setError($obj_master->getValMsg('can_read'));
    $obj_master->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

       <h1>Item</h1>
        <div class="whitebg formboxcontainer">
        <div>
            <a class='btn btn-default btn-success btnwidth addnew' href='<?php echo PROJECT_URL;?>/?page=master_item_update'>Add New</a>
        </div>
       
     
         <?php $obj_master->showErrorMessage(); ?>
            <?php $obj_master->showSuccessMessge(); ?>
            <?php $obj_master->unsetMessage(); ?>
        <h2 class="greyheading">Item Listing</h2>
        <div class="adminformbx">
           
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable">
                <thead>
                    <tr>
                        <th align='left' width="72">Sr</th>
                        <th align='left'>Item</th>
                        <th align='left'>Item Type</th>
                        <th align='left'>HSN Code</th>
                        
                        
                        <th align='left'>IGST Tax Rate</th>
                        <th align='left'>CSGT Tax Rate</th>
                        
                        <th align='left'>SGST Tax Rate</th>
                        <th align='left'>Cess Tax Rate</th>
                        <th align='left'>Status</th>
                        
                        <th width="72">Action</th>
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
        $('#submit').click(function () {
            var mesg = {};
            if (vali.validate(mesg,'form')) {
                return true;
            }
            return false;
        });
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
                    "sAjaxSource": "<?php echo PROJECT_URL; ?>/?ajax=master_item",
                    "fnServerParams": function (aoData) {
                    },
                    "iDisplayLength": 50
                });
            }
        };
    }();
</script>