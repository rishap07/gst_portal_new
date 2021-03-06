<?php
	$obj_master = new master();

	if(!$obj_master->can_read('master_receiver')) {
		$obj_master->setError($obj_master->getValMsg('can_read'));
		$obj_master->redirect(PROJECT_URL."/?page=dashboard");
		exit();
	}
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
	<div class="col-md-12 col-sm-12 col-xs-12">   
		<h1>Receiver/Customer</h1>
		<div class="whitebg formboxcontainer">
			<div class="pull-right">
				<a class='btn btn-default btn-success btnwidth' href='<?php echo PROJECT_URL;?>/?page=master_receiver_update'>Add New</a>
				<a class='btn btn-default btn-success btnwidth bulkupload' href='<?php echo PROJECT_URL;?>/?page=master_receiver_bulk_upload'>Bulk Upload</a>
			</div>
			<div class="clear height10"></div>
			
			<?php $obj_master->showErrorMessage(); ?>
			<?php $obj_master->showSuccessMessge(); ?>
			<?php $obj_master->unsetMessage(); ?>
			
			<h2 class="greyheading">Receiver/Customer Listing</h2>
			<div class="adminformbx">

				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable">
					<thead>
						<tr>
							<th align='left' width="72">#</th>
							<th align='left'>GSTIN</th>
							<th align='left'>Name</th>
							<th align='left'>Address</th>
							<th align='left'>City</th>
							<th align='left'>State</th>
							<th align='left'>Country</th>
							<th align='left'>Status</th>
							<th width="72">Action</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="clear height80"></div>
<script type="text/javascript">
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
                    "sAjaxSource": "<?php echo PROJECT_URL; ?>/?ajax=master_receiver",
                    "fnServerParams": function (aoData) {
                    },
                    "iDisplayLength": 50
                });
            }
        };
    }();
</script>