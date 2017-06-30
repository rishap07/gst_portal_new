<?php
$obj_client = new client();
if( !isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '' ) {
    $obj_client->redirect(PROJECT_URL);
    exit();
}

if( isset($_GET['action']) && $_GET['action'] == 'deleteSTInvoice' && isset($_GET['id']) && $obj_client->validateId($_GET['id'])) {

    if(!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])){

        $obj_client->setError('Invalid access to files');
    } else {

        $obj_client->redirect(PROJECT_URL."?page=client_special_tax_invoice_list");
    }
}
?>
<div class="admincontainer greybg">
    <div class="formcontainer">
        <div>
            <a class='addnew' href='<?php echo PROJECT_URL;?>/?page=client_create_special_tax_invoice'>Add Special Tax Invoice</a>
        </div>
        <h1>Special Tax Invoice</h1>
        <hr class="headingborder">
        <h2 class="greyheading">Special Tax Invoice Listing</h2>
        <div class="adminformbx">
            <?php $obj_client->showErrorMessage(); ?>
            <?php $obj_client->showSuccessMessge(); ?>
            <?php $obj_client->unsetMessage(); ?>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tablecontent" id="mainTable">
                <thead>
                    <tr>
                        <th align='left'>#</th>
                        <th align='left'>Invoice Number</th>
                        <th align='left'>Invoice Date</th>
                        <th align='left'>Supply Place</th>
						<th align='left'>Billing To</th>
						<th align='left'>Billing State</th>
						<th align='left'>Shipping To</th>
						<th align='left'>Shipping State</th>
						<th align='left'>Total<br><i class="fa fa-inr"></i></th>
						<th align='left'>Canceled</th>
                        <!--<th align='left'>Action</th>-->
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
						{"bSortable": false},
						{"bSortable": false},
						{"bSortable": false},
                        /*{"bSortable": false},*/
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
                    "sAjaxSource": "<?php echo PROJECT_URL; ?>/?ajax=client_special_tax_invoice_list",
                    "fnServerParams": function (aoData) {
                    },
                    "iDisplayLength": 50
                });
            }
        };
    }();
</script>