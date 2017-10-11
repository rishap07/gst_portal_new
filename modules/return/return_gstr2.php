<?php
$obj_gstr2 = new gstr2();

if(!$obj_gstr2->can_read('returnfile_list')) {

    $obj_gstr2->setError($obj_gstr2->getValMsg('can_read'));
    $obj_gstr2->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}

$returnmonth = date('Y-m');
if (isset($_POST['returnmonth'])) {
    $returnmonth = $_POST['returnmonth'];
    $obj_gstr2->redirect(PROJECT_URL . "/?page=return_gstr2&returnmonth=" . $returnmonth);
    exit();
}
$returnmonth = date('Y-m');
if (isset($_REQUEST['returnmonth']) && $_REQUEST['returnmonth'] != '') {
    $returnmonth = $_REQUEST['returnmonth'];
    if ($obj_gstr2->startGstr2()) {
        
    }
} else {

    $dataQuery = "SELECT DATE_FORMAT(invoice_date,'%Y-%m') AS niceDate FROM gst_client_invoice group by nicedate ORDER by invoice_date desc LIMIT 0,1";
    $dataRes = $obj_gstr2->get_results($dataQuery);
    if (!empty($dataRes)) {
        $returnmonth = $dataRes[0]->niceDate;
    }
}
$time = strtotime($returnmonth . "-01");
$month = date("M", strtotime("+1 month", $time));
if (isset($_POST['generatesummary']) && $_POST['generatesummary'] == 1) {
  $obj_gstr2->insertGstr2B2bInvoice($_SESSION["user_detail"]["user_id"],$returnmonth);
  $obj_gstr2->insertGstr2B2bInvoiceDetails($_SESSION["user_detail"]["user_id"],$returnmonth);
  $obj_gstr2->insertGstr2B2clInvoice($_SESSION["user_detail"]["user_id"],$returnmonth);
  $obj_gstr2->insertGstr2B2clInvoiceDetails($_SESSION["user_detail"]["user_id"],$returnmonth);
  $obj_gstr2->insertGstr2B2csmallInvoice($_SESSION["user_detail"]["user_id"],$returnmonth);
  $obj_gstr2->insertGstr2B2csmallInvoiceDetails($_SESSION["user_detail"]["user_id"],$returnmonth);
  $obj_gstr2->insertGstr2ImportInvoice($_SESSION["user_detail"]["user_id"],$returnmonth);
  $obj_gstr2->insertGstr2ImportInvoiceDetails($_SESSION["user_detail"]["user_id"],$returnmonth);
 }

?>

<?php
$b2bquery = "SELECT sum(item.taxable_subtotal) as subtotal,count(item.purchase_invoice_id) as totalinvoice, sum(item.cgst_amount) as cgst_amount,sum(item.sgst_amount) as sgst_amount,sum(igst_amount) as igst_amount,sum(cess_amount) as cess_amount from " . $obj_gstr2->getTableName('client_purchase_invoice') . " as p  inner join " . $obj_gstr2->getTableName('client_purchase_invoice_item') . "   as item on p.purchase_invoice_id = item.purchase_invoice_id WHERE p.invoice_nature='purchaseinvoice'  and p.added_by='" . $_SESSION["user_detail"]["user_id"] . "' and p.is_canceled='0' and supplier_billing_gstin_number!='' and p.invoice_date like '%" . $returnmonth . "%'";
$b2bData = $db_obj->get_results($b2bquery);
$unregister_purchase_query = "SELECT sum(item.taxable_subtotal) as subtotal,count(item.purchase_invoice_id) as totalinvoice, sum(item.cgst_amount) as cgst_amount,sum(item.sgst_amount) as sgst_amount,sum(igst_amount) as igst_amount,sum(cess_amount) as cess_amount from " . $obj_gstr2->getTableName('client_purchase_invoice') . " as p  inner join " . $obj_gstr2->getTableName('client_purchase_invoice_item') . "   as item on p.purchase_invoice_id = item.purchase_invoice_id WHERE p.invoice_nature='purchaseinvoice'  and p.added_by='" . $_SESSION["user_detail"]["user_id"] . "' and p.is_canceled='0' and supplier_billing_gstin_number='' and supply_type='reversecharge' and p.invoice_date like '%" . $returnmonth . "%'";
$unregister_purchase_data = $db_obj->get_results($unregister_purchase_query);
?>

<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="col-md-6 col-sm-6 col-xs-12 heading">
      <h1>GSTR-2 Filing</h1>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12 text-right breadcrumb-nav"><a href="#">Home</a> <i class="fa fa-angle-right" aria-hidden="true"></i> <a href="#">File Return</a> <i class="fa fa-angle-right" aria-hidden="true"></i> <span class="active">GSTR-2 Filing</span> </div>
    <div class="whitebg formboxcontainer">
      <div class="pull-right rgtdatetxt">
        <form method='post' name='form1'>
          Month Of Return
          <?php
				$dataQuery = "SELECT DATE_FORMAT(invoice_date,'%Y-%m') AS niceDate FROM gst_client_invoice group by nicedate";
				$dataRes = $obj_gstr2->get_results($dataQuery);
				if (!empty($dataRes)) {
					?>
          <select class="dateselectbox" id="returnmonth" name="returnmonth">
            <?php
					foreach ($dataRes as $dataRe) {
						?>
            <option value="<?php echo $dataRe->niceDate; ?>" <?php if ($dataRe->niceDate == $returnmonth) {
					echo 'selected';
					} ?>><?php echo $dataRe->niceDate; ?></option>
            <?php
					   }
					?>
          </select>
          <?php
                        } else {
                            ?>
          <select class="dateselectbox" id="returnmonth" name="returnmonth">
            <option>July 2017</option>
          </select>
          <?php }
                    ?>
        </form>
      </div>
      <div class="clear"></div>
      <?php $db_obj->showErrorMessage(); ?>
      <?php $db_obj->showSuccessMessge(); ?>
      <?php $db_obj->unsetMessage(); ?>
      <div class="col-md-12 col-sm-12 col-xs-12 heading">
        <div class="tab col-md-12 col-sm-12 col-xs-12">
          <?php include(PROJECT_ROOT."/modules/return/include/tab.php");  ?>
        </div>
      </div>
      <div class="tableresponsive">
        <form method="post" name="form2">
          <div style="text-align: center;">
            <button  type="button"  class="btn btn-success" id="btnConfirm">Generate Summary</button>
            <input type="hidden" name="generatesummary" id="generatesummary" value="1" />
          </div>
        </form>
        <table  class="table  tablecontent tablecontent2">
          <thead>
            <tr>
              <th align="left">TYPE OF INVOICE</th>
              <th align="left">NO. INVOICES</th>
              <th align="left">TAXABLE AMT</th>
              <th class="text-right">TAX AMT</th>
              <th class="text-right">TOTAL AMT INCL. TAX</th>
              <th class=""></th>
            </tr>
            <tr>
              <?php
//$res = $obj_gstr2->getPurchaseB2BInvoices('',$returnmonth,'','');


$b2bItemquery = "select sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_gstr2->getTableName('client_purchase_invoice') . " i inner join " . $obj_gstr2->getTableName("client_purchase_invoice_item") . " it on i.purchase_invoice_id=it.purchase_invoice_id  where i.invoice_nature='purchaseinvoice' and i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and i.supplier_billing_gstin_number!='' and i.invoice_date like '%" . $returnmonth . "%' ";
$b2bItemData = $obj_gstr2->get_results($b2bItemquery);
$b2bquery = "select * from " . $obj_gstr2->getTableName('client_purchase_invoice') . " where invoice_nature='purchaseinvoice' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0' and supplier_billing_gstin_number!='' and invoice_date like '%" . $returnmonth . "%'";
$b2bData = $obj_gstr2->get_results($b2bquery);
$b2bTotquery = "select sum(invoice_total_value) as invoice_total_value from " . $obj_gstr2->getTableName('client_purchase_invoice') . " where invoice_nature='purchaseinvoice' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0' and supplier_billing_gstin_number!='' and invoice_date like '%" . $returnmonth . "%'";
$b2bTotData = $obj_gstr2->get_results($b2bTotquery);

$total = 0;
if (!empty($b2bItemData)) {
    $total = $b2bItemData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $b2bItemData[0]->igst_amount + $b2bItemData[0]->cess_amount;
}
?>
              <td>B2B
                </th>
              <td align='center'><?php echo count($b2bData); ?></td>
              <td align='center'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
              <td align='center'><?php echo $total; ?></td>
              <td align='center'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
            </tr>

            <tr>
              <?php
$b2bItemquery = "select sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_gstr2->getTableName('client_purchase_invoice') . " i inner join " . $obj_gstr2->getTableName("client_purchase_invoice_item") . " it on i.purchase_invoice_id=it.purchase_invoice_id  where i.invoice_nature='purchaseinvoice' and i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and i.supplier_billing_gstin_number='' and i.invoice_total_value>'250000'  and i.invoice_date like '%" . $returnmonth . "%' ";
$b2bItemData = $obj_gstr2->get_results($b2bItemquery);
$b2bquery = "select * from " . $obj_gstr2->getTableName('client_purchase_invoice') . " where invoice_nature='purchaseinvoice' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0' and supplier_billing_gstin_number='' and invoice_total_value>'250000'  and invoice_date like '%" . $returnmonth . "%'";
$b2bData = $obj_gstr2->get_results($b2bquery);
$b2bTotquery = "select sum(invoice_total_value) as invoice_total_value from " . $obj_gstr2->getTableName('client_purchase_invoice') . " where invoice_nature='purchaseinvoice' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0' and supplier_billing_gstin_number='' and invoice_total_value>'250000'  and invoice_date like '%" . $returnmonth . "%'";
$b2bTotData = $obj_gstr2->get_results($b2bTotquery);
$total = 0;
if (!empty($b2bItemData)) {
    $total = $b2bItemData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $b2bItemData[0]->igst_amount + $b2bItemData[0]->cess_amount;
}
?>
              <td>B2C Large</td>
              <td align='center'><?php echo count($b2bData); ?></td>
              <td align='center'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
              <td align='center'><?php echo $total; ?></td>
              <td align='center'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
            </tr>

            <tr>
              <?php
$b2bItemquery = "select sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_gstr2->getTableName('client_purchase_invoice') . " i inner join " . $obj_gstr2->getTableName("client_purchase_invoice_item") . " it on i.purchase_invoice_id=it.purchase_invoice_id  where i.invoice_nature='purchaseinvoice' and i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and i.supplier_billing_gstin_number='' and i.invoice_total_value<='250000'  and i.invoice_date like '%" . $returnmonth . "%' ";
$b2bItemData = $obj_gstr2->get_results($b2bItemquery);
$b2bquery = "select * from " . $obj_gstr2->getTableName('client_purchase_invoice') . " where invoice_nature='purchaseinvoice' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0' and supplier_billing_gstin_number='' and invoice_total_value<='250000'  and invoice_date like '%" . $returnmonth . "%'";
$b2bData = $obj_gstr2->get_results($b2bquery);
$b2bTotquery = "select sum(invoice_total_value) as invoice_total_value from " . $obj_gstr2->getTableName('client_purchase_invoice') . " where invoice_nature='purchaseinvoice' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0' and supplier_billing_gstin_number='' and invoice_total_value<='250000'  and invoice_date like '%" . $returnmonth . "%'";
$b2bTotData = $obj_gstr2->get_results($b2bTotquery);
$total = 0;
if (!empty($b2bItemData)) {
    $total = $b2bItemData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $b2bItemData[0]->igst_amount + $b2bItemData[0]->cess_amount;
}
?>
              <td>B2C Small</td>
              <td align='center'><?php echo count($b2bData); ?></td>
              <td align='center'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
              <td align='center'><?php echo $total; ?></td>
              <td align='center'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
            </tr>

            <tr>
              <?php
$b2bItemquery = "select sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_gstr2->getTableName('client_purchase_invoice') . " i inner join " . $obj_gstr2->getTableName("client_purchase_invoice_item") . " it on i.purchase_invoice_id=it.purchase_invoice_id  where i.invoice_nature='purchaseinvoice' and i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0'  and i.invoice_date like '%" . $returnmonth . "%' and i.invoice_type='exportinvoice' ";
$b2bItemData = $obj_gstr2->get_results($b2bItemquery);
$b2bquery = "select * from " . $obj_gstr2->getTableName('client_purchase_invoice') . " where invoice_nature='purchaseinvoice' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%'  and invoice_type='exportinvoice'";
$b2bData = $obj_gstr2->get_results($b2bquery);
$b2bTotquery = "select sum(invoice_total_value) as invoice_total_value from " . $obj_gstr2->getTableName('client_purchase_invoice') . " where invoice_nature='salesinvoice' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%'  and invoice_type='exportinvoice'";
$b2bTotData = $obj_gstr2->get_results($b2bTotquery);
$total = 0;
if (!empty($b2bItemData)) {
    $total = $b2bItemData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $b2bItemData[0]->igst_amount + $b2bItemData[0]->cess_amount;
}
?>
              <td>Import</td>
              <td align='center'><?php echo count($b2bData); ?></td>
              <td align='center'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
              <td align='center'><?php echo $total; ?></td>
              <td align='center'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
            </tr>

            <tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="clear height40"></div>
</div>
<div class="clear"></div>
<script>
    $(document).ready(function () {
        $('#returnmonth').on('change', function () {
            document.form1.action = '<?php echo PROJECT_URL; ?>/?page=return_gstr2&returnmonth=<?php echo $returnmonth; ?>';
                        document.form1.submit();
                    });
                });
</script> 
<script>
    function ezBSAlert(options) {
        var deferredObject = $.Deferred();
        var defaults = {
            type: "alert", //alert, prompt,confirm 
            modalSize: 'modal-sm', //modal-sm, modal-lg
            okButtonText: 'Confirm',
            cancelButtonText: 'Cancel',
            yesButtonText: 'Yes',
            noButtonText: 'No',
            headerText: 'Important : Please Read And Confirm',
            messageText: 'Message',
            alertType: 'default', //default, primary, success, info, warning, danger
            inputFieldType: 'text', //could ask for number,email,etc
        }
        $.extend(defaults, options);

        var _show = function () {
            var headClass = "navbar-default";
            switch (defaults.alertType) {
                case "primary":
                    headClass = "alert-primary";
                    break;
                case "success":
                    headClass = "alert-success";
                    break;
                case "info":
                    headClass = "alert-info";
                    break;
                case "warning":
                    headClass = "alert-warning";
                    break;
                case "danger":
                    headClass = "alert-danger";
                    break;
            }
            $('BODY').append(
                    '<div id="ezAlerts" style="z-index: 99999" class="modal fade">' +
                    '<div class="modal-dialog" class="' + defaults.modalSize + '">' +
                    '<div class="modal-content">' +
                    '<div id="ezAlerts-header" class="modal-header ' + headClass + '">' +
                    '<button id="close-button" type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>' +
                    '<h4 id="ezAlerts-title" class="modal-title">Modal title</h4>' +
                    '</div>' +
                    '<div id="ezAlerts-body" class="modal-body">' +
                    '<div id="ezAlerts-message" ></div>' +
                    '</div>' +
                    '<div id="ezAlerts-footer" class="modal-footer">' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>'
                    );

            $('.modal-header').css({
                'padding': '15px 15px',
                '-webkit-border-top-left-radius': '5px',
                '-webkit-border-top-right-radius': '5px',
                '-moz-border-radius-topleft': '5px',
                '-moz-border-radius-topright': '5px',
                'border-top-left-radius': '5px',
                'border-top-right-radius': '5px'
            });

            $('#ezAlerts-title').text(defaults.headerText);
            $('#ezAlerts-message').html(defaults.messageText);

            var keyb = "false", backd = "static";
            var calbackParam = "";
            switch (defaults.type) {
                case 'alert':
                    keyb = "true";
                    backd = "true";
                    $('#ezAlerts-footer').html('<button class="btn btn-' + defaults.alertType + '">' + defaults.okButtonText + '</button>').on('click', ".btn", function () {
                        calbackParam = true;
                        $('#ezAlerts').modal('hide');
                    });
                    break;
                case 'confirm':
                    var btnhtml = '<button id="ezok-btn" class="btn btn-primary">' + defaults.yesButtonText + '</button>';
                    if (defaults.noButtonText && defaults.noButtonText.length > 0) {
                        btnhtml += '<button id="ezclose-btn" class="btn btn-default">' + defaults.noButtonText + '</button>';
                    }
                    $('#ezAlerts-footer').html(btnhtml).on('click', 'button', function (e) {
                        if (e.target.id === 'ezok-btn') {
                            calbackParam = true;
                            $('#ezAlerts').modal('hide');
                        } else if (e.target.id === 'ezclose-btn') {
                            calbackParam = false;
                            $('#ezAlerts').modal('hide');
                        }
                    });
                    break;
                case 'prompt':
                    $('#ezAlerts-message').html(defaults.messageText + '<br /><br /><div class="form-group"><input type="' + defaults.inputFieldType + '" class="form-control" id="prompt" /></div>');
                    $('#ezAlerts-footer').html('<button class="btn btn-primary">' + defaults.okButtonText + '</button>').on('click', ".btn", function () {
                        calbackParam = $('#prompt').val();
                        $('#ezAlerts').modal('hide');
                    });
                    break;
            }

            $('#ezAlerts').modal({
                show: false,
                backdrop: backd,
                keyboard: keyb
            }).on('hidden.bs.modal', function (e) {
                $('#ezAlerts').remove();
                deferredObject.resolve(calbackParam);
            }).on('shown.bs.modal', function (e) {
                if ($('#prompt').length > 0) {
                    $('#prompt').focus();
                }
            }).modal('show');
        }

        _show();
        return deferredObject.promise();
    }





    $(document).ready(function () {


        $("#btnConfirm").on("click", function () {
            ezBSAlert({
                type: "confirm",
                messageText: "Generate summary is based only on Invoices data.<br><br>Your current data for this section will be erased and it will be reset based on summary computed from Invoice level data.",
                alertType: "danger"
            }).done(function (e) {
                if (e == true)
                {
                    document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_gstr2&returnmonth=<?php echo $returnmonth; ?>';
                    document.form2.submit();
                                    }
                                });
                            });

                        });
</script>