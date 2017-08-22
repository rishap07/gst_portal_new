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
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
	<div class="col-md-12 col-sm-12 col-xs-12">   
		<h1>Item</h1>	
		<div class="whitebg formboxcontainer">
			<div class="pull-right">
				<a class='btn btn-default btn-success btnwidth' href='<?php echo PROJECT_URL;?>/?page=client_item_update'>Add New</a>
				<a class='btn btn-default btn-success btnwidth bulkupload' href='<?php echo PROJECT_URL;?>/?page=client_item_bulk_upload'>Bulk Upload</a>
			</div>
			<div class="clear height10"></div>

			<?php $obj_client->showErrorMessage(); ?>
			<?php $obj_client->showSuccessMessge(); ?>
			<?php $obj_client->unsetMessage(); ?>

			<h2 class="greyheading">Item Listing</h2>
			<div class="adminformbx">

				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable">
					<thead>
						<tr>
							<th align='left' style="width:5%;">#</th>
							<th align='left' style="width:15%;">Item</th>
							<th align='left' style="width:25%;">HSN/SAC Category</th>
							<th align='left' style="width:25%;">Description</th>
							<th align='left' style="width:10%;">HSN/SAC Code</th>
							<th align='left' style="width:10%;">Unit Price(Rs)</th>
							<th align='left' style="width:5%;">Status</th>
							<th align='left' style="width:5%;">Action</th>
						</tr>
					</thead>
				</table>

			</div>
		</div>
	</div>
</div>
<div class="clear height80"></div>
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
                    "sAjaxSource": "<?php echo PROJECT_URL; ?>/?ajax=client_item_list",
                    "fnServerParams": function (aoData) {
                    },
                    "iDisplayLength": 20
                });
            }
        };
    }();
</script>