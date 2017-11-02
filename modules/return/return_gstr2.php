<?php
$obj_gstr2 = new gstr2();

if(!$obj_gstr2->can_read('returnfile_list')) {

    $obj_gstr2->setError($obj_gstr2->getValMsg('can_read'));
    $obj_gstr2->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}

if(isset($_GET['returnmonth']) && !empty($_GET['returnmonth'])) {
	$returnmonth = $_GET['returnmonth'];
} else {
	$obj_gstr2->setError("Please choose return period.");
    $obj_gstr2->redirect(PROJECT_URL."/?page=return_client");
    exit();
}
?>

<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
	<div class="col-md-12 col-sm-12 col-xs-12">

		<div class="col-md-6 col-sm-6 col-xs-12 heading">
			<h1>GSTR-2 Filing</h1>
		</div>

		<div class="col-md-6 col-sm-6 col-xs-12 text-right breadcrumb-nav">
			<a href="#">Home</a> <i class="fa fa-angle-right" aria-hidden="true"></i> 
			<a href="#">File Return</a> <i class="fa fa-angle-right" aria-hidden="true"></i> 
			<span class="active">GSTR-2 Filing</span>
		</div>

		<div class="whitebg formboxcontainer">
			<div class="pull-right">
				<form method='post' name='gstr2PurchaseSummaryForm' id="gstr2PurchaseSummaryForm">
					Month Of Return
					<?php $invoiceMonthYear = $obj_gstr2->getInvoiceMonthList($obj_gstr2->getTableName('client_purchase_invoice')); ?>
					<select class="monthselectbox" id="returnmonth" name="returnmonth">
						<option value="">Select</option>
						<?php foreach($invoiceMonthYear as $monthYear) { ?>
							<option <?php if($returnmonth == $monthYear->invoiceDate) { echo 'selected="selected"'; } ?> value="<?php echo $monthYear->invoiceDate; ?>"><?php echo date("M-y", strtotime($monthYear->invoiceDate)); ?></option>
						<?php } ?>
					</select>
				</form>
			</div>

			<div class="clear"></div>
			<hr>
			<div class="clear"></div>

			<?php $obj_gstr2->showErrorMessage(); ?>
			<?php $obj_gstr2->showSuccessMessge(); ?>
			<?php $obj_gstr2->unsetMessage(); ?>

			<div class="row heading">
				<div class="tab">
					<?php include(PROJECT_ROOT."/modules/return/include/tab.php"); ?>
				</div>
			</div>
			<div class="clear"></div>

			<div class="tableresponsive">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable">
					<thead>
						<tr>
							<th>#</th>
							<th>Invoice Type</th>
							<th>Reference Number</th>
							<th>Invoice Date</th>
							<th>Place Of Supply</th>
							<th>Supplier Name</th>
							<th>Supplier GSTIN</th>
							<th>Supplier State</th>
							<th>Invoice Value (<i class="fa fa-inr"></i>)</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="clear height40"></div>
</div>

<script>
	$(document).ready(function () {

		$('#returnmonth').on('change', function () {
			window.location.href = "<?php echo PROJECT_URL; ?>/?page=return_gstr2&returnmonth=" + $(this).val();
		});

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
                    "searching": true,
                    "bLengthChange": true,
                    "sAjaxSource": "<?php echo PROJECT_URL; ?>/?ajax=return_gstr2&returnmonth=<?php echo $returnmonth; ?>",
                    "fnServerParams": function (aoData) {
                    },
                    "iDisplayLength": 20
                });
            }
        };
    }();
</script>