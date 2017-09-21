<?php
$obj_purchase = new purchase();

if(!$obj_purchase->can_read('client_invoice')) {

	$obj_purchase->setError($obj_purchase->getValMsg('can_read'));
	$obj_purchase->redirect(PROJECT_URL."/?page=dashboard");
	exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'downloadPurchaseBOSInvoice' && isset($_GET['id']) && $obj_purchase->validateId($_GET['id'])) {

    $htmlResponse = $obj_purchase->generatePurchaseBOSInvoiceHtml($_GET['id']);
    if ($htmlResponse === false) {

        $obj_purchase->setError("No invoice found.");
        $obj_purchase->redirect(PROJECT_URL . "?page=purchase_bill_of_supply_invoice_list");
        exit();
    }

    $obj_mpdf = new mPDF('utf-8', 'A4');
    $obj_mpdf->SetHeader('Purchase Bill of Supply Invoice');
    $obj_mpdf->WriteHTML($htmlResponse);

    $taxInvoicePdf = 'purchase-bos-invoice-' . $_GET['id'] . '.pdf';
    ob_clean();
    $obj_mpdf->Output($taxInvoicePdf, 'D');
}

if (isset($_GET['action']) && $_GET['action'] == 'emailPurchaseBOSInvoice' && isset($_GET['id']) && $obj_purchase->validateId($_GET['id'])) {

    $htmlResponse = $obj_purchase->generatePurchaseBOSInvoiceHtml($_GET['id']);

    $dataCurrentUserArr = $obj_purchase->getUserDetailsById($obj_purchase->sanitize($_SESSION['user_detail']['user_id']));
    $sendmail = $dataCurrentUserArr['data']->kyc->email;

    $mail = new PHPMailer();
    $message = html_entity_decode($htmlResponse);
    $mail->IsSMTP();
    $mail->Host = "49.50.104.11";
    $mail->Port = 25;
    //$mail->SMTPDebug = 2;

    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    $mail->CharSet = "UTF-8";
    $mail->Subject = 'GST Purchase Bill of Supply Invoice-' . $_GET['id'];
    $mail->SetFrom('noreply.Gstkeeper@gstkeeper.com', 'GST Keeper');
    $mail->MsgHTML($message);
    $mail->AddAddress($sendmail);
    $mail->AddBCC("ishwar.ghiya@cyfuture.com");

    if ($mail->Send()) {

        $obj_purchase->setSuccess("Mail Sent Successfully.");
        $mail->ClearAllRecipients();
        $obj_purchase->redirect(PROJECT_URL . "?page=purchase_bill_of_supply_invoice_list");
    } else {

        $obj_purchase->setError($mail->ErrorInfo);
        $mail->ClearAllRecipients();
        $obj_purchase->redirect(PROJECT_URL . "?page=purchase_bill_of_supply_invoice_list");
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'printPurchaseBOSInvoice' && isset($_GET['id']) && $obj_purchase->validateId($_GET['id'])) {

    $htmlResponse = $obj_purchase->generatePurchaseBOSInvoiceHtml($_GET['id']);

    if ($htmlResponse === false) {

        $obj_purchase->setError("No invoice found.");
        $obj_purchase->redirect(PROJECT_URL . "?page=purchase_bill_of_supply_invoice_list");
        exit();
    }

    $obj_mpdf = new mPDF('utf-8', 'A4');
    $obj_mpdf->SetHeader('Purchase Bill of Supply Invoice');
    $obj_mpdf->WriteHTML($htmlResponse);

    $taxInvoicePdf = 'purchase-bos-invoice-' . $_GET['id'] . '.pdf';
    ob_clean();
    $obj_mpdf->Output($taxInvoicePdf, 'I');
}

$currentFinancialYear = $obj_purchase->generateFinancialYear();
$dataThemeSettingArr = $obj_purchase->getUserThemeSetting( $obj_purchase->sanitize($_SESSION['user_detail']['user_id']) );
$dataInvoiceSettingArr = $obj_purchase->getUserInvoiceSetting( $obj_purchase->sanitize($_SESSION['user_detail']['user_id']) );
?>
<style>
    #mainTable thead{display:none;}
</style>

<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Purchase Bill of Supply Invoice</h1></div>
        <div class="formboxcontainer padleft0 mobinvoicecol" style="padding-top:0px;">

            <?php $obj_purchase->showErrorMessage(); ?>
            <?php $obj_purchase->showSuccessMessge(); ?>
            <?php $obj_purchase->unsetMessage(); ?>

            <div class="row">

                <!--INVOICE LEFT TABLE START HERE-->
				<div class="fixed-left-col col-sm-12 col-xs-12" style="padding-right:0px; padding-left:0px;">

                    <div class="invoiceheaderfixed">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-right:5px;padding-left:10px;">
                            <a href='javascript:void(0)' class="btn btn-warning pull-left checkAll">Check All</a>
                            <a href='javascript:void(0)' class="btn btn-danger pull-left cancelAll" data-toggle="tooltip" title="Cancel All"><i class="fa fa-times" aria-hidden="true"></i></a>
							<a href='javascript:void(0)' class="btn btn-success pull-left revokeAll" data-toggle="tooltip" title="Revoke All"><i class="fa fa-undo" aria-hidden="true"></i></a>
                        </div>
						
						<?php $invoiceMonthYear = $obj_purchase->getInvoiceMonthList($obj_purchase->getTableName('client_purchase_invoice'), "'billofsupplyinvoice'"); ?>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="padding-right:5px;padding-left:5px;">
							<select name="invoiceDateOption" id="invoiceDateOption" class="date-dropdown">
								<option value="all">All</option>
								<?php foreach($invoiceMonthYear as $monthYear) { ?>
									<option value="<?php echo $monthYear->invoiceDate; ?>"><?php echo date("M-y", strtotime($monthYear->invoiceDate)); ?></option>
								<?php } ?>
							</select>
						</div>

                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="padding-right:10px;padding-left:5px;">
                            <a href='<?php echo PROJECT_URL; ?>/?page=purchase_bill_of_supply_invoice_create' class="btn btn-success pull-right"><i class="fa fa-plus" aria-hidden="true"></i> New</a>
                        </div>
                    </div>

                    <div class="tableresponsive">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainTable" class="inovicelefttable" style="margin-top:53px;">
                        </table>
                    </div>
                </div>

                <?php
                /* code for display invoice according to invoice id pass in query string */
                if (isset($_GET['action']) && $_GET['action'] == 'viewPurchaseBOSInvoice' && isset($_GET['id']) && $obj_purchase->validateId($_GET['id'])) {

                    $invoicePurchaseId = $obj_purchase->sanitize($_GET['id']);
                    $invoiceData = $obj_purchase->get_results("select 
												ci.*, 
												cii.purchase_invoice_item_id, 
												cii.item_id, 
												cii.item_name, 
												cii.item_description, 
												cii.item_hsncode, 
												cii.item_quantity, 
												cii.item_unit, 
												cii.item_unit_price, 
												cii.subtotal, 
												cii.discount, 
												cii.taxable_subtotal, 
												cii.total 
												from 
											" . $obj_purchase->getTableName('client_purchase_invoice') . " as ci INNER JOIN " . $obj_purchase->getTableName('client_purchase_invoice_item') . " as cii ON ci.purchase_invoice_id = cii.purchase_invoice_id where ci.purchase_invoice_id = " . $invoicePurchaseId . " AND ci.invoice_type = 'billofsupplyinvoice' AND ci.added_by = '" . $obj_purchase->sanitize($_SESSION['user_detail']['user_id']) . "' AND cii.added_by = '" . $obj_purchase->sanitize($_SESSION['user_detail']['user_id']) . "' AND ci.is_deleted='0' AND cii.is_deleted='0'");
                } else {

					$invoiceData = $obj_purchase->get_results("select 
												ci.*, 
												cii.purchase_invoice_item_id, 
												cii.item_id, 
												cii.item_name, 
												cii.item_description, 
												cii.item_hsncode, 
												cii.item_quantity, 
												cii.item_unit, 
												cii.item_unit_price, 
												cii.subtotal, 
												cii.discount, 
												cii.taxable_subtotal, 
												cii.total 
												from 
											" . $obj_purchase->getTableName('client_purchase_invoice') . " as ci INNER JOIN " . $obj_purchase->getTableName('client_purchase_invoice_item') . " as cii ON ci.purchase_invoice_id = cii.purchase_invoice_id where ci.purchase_invoice_id = (SELECT purchase_invoice_id FROM ".$obj_purchase->getTableName('client_purchase_invoice')." Where invoice_type = 'billofsupplyinvoice' AND added_by = '" . $obj_purchase->sanitize($_SESSION['user_detail']['user_id']) . "' AND is_deleted='0' Order by purchase_invoice_id desc limit 0,1) AND ci.invoice_type = 'billofsupplyinvoice' AND ci.added_by = '" . $obj_purchase->sanitize($_SESSION['user_detail']['user_id']) . "' AND cii.added_by = '" . $obj_purchase->sanitize($_SESSION['user_detail']['user_id']) . "' AND ci.is_deleted='0' AND cii.is_deleted='0'");
				}
                /* Invoice display query code end here */
                ?>
                <!--INVOICE LEFT TABLE END HERE-->

                <!--INVOICE PRINT RIGHT  START HERE-->
                <?php if (isset($invoiceData[0]->purchase_invoice_id)) { ?>

                    <div class="col-md-8 col-sm-12 mobdisplaynone invoicergtcol">

                        <!---INVOICE TOP ICON START HERE-->
                        <div class="inovicergttop">
                            <ul class="iconlist">
								<li><a href="<?php echo PROJECT_URL; ?>/?page=purchase_bill_of_supply_invoice_update&action=editPurchaseBOSInvoice&id=<?php echo $invoiceData[0]->purchase_invoice_id; ?>"><div data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></div></a></li>
                                <li><a href="<?php echo PROJECT_URL; ?>/?page=purchase_bill_of_supply_invoice_list&action=downloadPurchaseBOSInvoice&id=<?php echo $invoiceData[0]->purchase_invoice_id; ?>"><div data-toggle="tooltip" data-placement="bottom" title="PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></div></a></li>
                                <li><a href="<?php echo PROJECT_URL; ?>/?page=purchase_bill_of_supply_invoice_list&action=printPurchaseBOSInvoice&id=<?php echo $invoiceData[0]->purchase_invoice_id; ?>" target="_blank"><div data-toggle="tooltip" data-placement="bottom" title="PRINT"><i class="fa fa-print" aria-hidden="true"></i></div></a></li>
                                <li><a href="<?php echo PROJECT_URL; ?>/?page=purchase_bill_of_supply_invoice_list&action=emailPurchaseBOSInvoice&id=<?php echo $invoiceData[0]->purchase_invoice_id; ?>"><div data-toggle="tooltip" data-placement="bottom" title="Email"><i class="fa fa-envelope-o" aria-hidden="true"></i></div></a></li>
                            </ul>
                        </div>

                        <!---INVOICE div print START HERE-->
                        <div id="taxinvoice_print">

                            <!---INVOICE TOP ICON START HERE-->
                            <div class="height20"></div>
                            <div class="clearfix"></div>

                            <div class="invoice-box" style="overflow-x:scroll;overflow-y:hidden;">
								<table cellpadding="0" cellspacing="0">
                                    <tr class="top">
                                        <td colspan="2">
                                            <table>
                                                <tr>
                                                    <td class="title">
                                                        <?php if(isset($dataThemeSettingArr['data']->show_logo) && $dataThemeSettingArr['data']->show_logo == '1' && isset($dataThemeSettingArr['data']->theme_logo) && $dataThemeSettingArr['data']->theme_logo != "") { ?>
                                                            <img src="<?php echo PROJECT_URL . '/upload/theme-logo/' . $dataThemeSettingArr['data']->theme_logo; ?>" style="max-width:300px;">
                                                        <?php } ?>
                                                    </td>

													<td style="text-align:right;vertical-align:top;">
                                                        <b><?php if(isset($dataInvoiceSettingArr['data']->invoice_label) && !empty($dataInvoiceSettingArr['data']->invoice_label)) { echo $dataInvoiceSettingArr['data']->invoice_label; } else { echo "Invoice #"; } ?></b>: <?php echo $invoiceData[0]->serial_number; ?><br>
                                                        <b><?php if(isset($dataInvoiceSettingArr['data']->reference_label) && !empty($dataInvoiceSettingArr['data']->reference_label)) { echo $dataInvoiceSettingArr['data']->reference_label; } else { echo "Reference #"; } ?></b>: <?php echo $invoiceData[0]->reference_number; ?><br>
                                                        <b><?php if(isset($dataInvoiceSettingArr['data']->type_label) && !empty($dataInvoiceSettingArr['data']->type_label)) { echo $dataInvoiceSettingArr['data']->type_label; } else { echo "Type"; } ?></b>: <?php echo "Bill of Supply Invoice"; ?><br>
                                                        <b><?php if(isset($dataInvoiceSettingArr['data']->nature_label) && !empty($dataInvoiceSettingArr['data']->nature_label)) { echo $dataInvoiceSettingArr['data']->nature_label; } else { echo "Nature"; } ?></b>: <?php echo "Purchase Invoice"; ?><br>
                                                        <b><?php if(isset($dataInvoiceSettingArr['data']->date_label) && !empty($dataInvoiceSettingArr['data']->date_label)) { echo $dataInvoiceSettingArr['data']->date_label; } else { echo "Invoice Date"; } ?></b>: <?php echo $invoiceData[0]->invoice_date; ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>

                                    <tr class="information">
                                        <td colspan="2">
                                            <table>
                                                <tr>
                                                    <td style="text-align:left;vertical-align:top;width:48%;padding-right:2%;">
                                                        <?php echo html_entity_decode($invoiceData[0]->company_name); ?><br>
                                                        <?php echo html_entity_decode($invoiceData[0]->company_address); ?><br>
														<?php if(!empty($invoiceData[0]->company_email)) { ?><b>Email:</b> <?php echo $invoiceData[0]->company_email; ?><br><?php } ?>
														<?php if(!empty($invoiceData[0]->company_phone_number)) { ?><b>Phone:</b> <?php echo $invoiceData[0]->company_phone_number; ?><br><?php } ?>
														<?php $panFromGTIN = substr(substr($invoiceData[0]->company_gstin_number, 2), 0, -3); ?>
														<b>PAN:</b> <?php echo $panFromGTIN; ?><br>
														<b>GSTIN:</b> <?php echo $invoiceData[0]->company_gstin_number; ?>
                                                    </td>

                                                    <td style="text-align:right;vertical-align:top;width:48%;padding-left:2%;">
														<?php if ($invoiceData[0]->is_canceled == 1) { ?> <b>Canceled Invoice:</b> <?php echo "Canceled"; ?> <?php } ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>

                                    <tr class="information">
                                        <td colspan="2">
                                            <table>
                                                <tr>
                                                    <td style="vertical-align:top;text-align:left;width:48%;padding-right:2%;">
                                                        <b>Supplier Detail</b><br>
                                                        <?php echo html_entity_decode($invoiceData[0]->supplier_billing_name); ?><br>
                                                        <?php if($invoiceData[0]->supplier_billing_company_name) { ?> <?php echo $invoiceData[0]->supplier_billing_company_name; ?><br> <?php } ?>
                                                        <?php echo $invoiceData[0]->supplier_billing_address; ?><br>
														<?php $supplier_billing_vendor_data = $obj_purchase->getVendorDetailByVendorId($invoiceData[0]->supplier_billing_vendor_type); ?>
														<?php echo $supplier_billing_vendor_data['data']->vendor_name; ?><br>
														<?php if(!empty($invoiceData[0]->supplier_billing_gstin_number)) { ?>
															<b>GSTIN/UIN:</b> <?php echo $invoiceData[0]->supplier_billing_gstin_number; ?>
                                                        <?php } ?>
                                                    </td>

                                                    <td style="vertical-align:top;text-align:right;width:48%;padding-left:2%;">
														<b>Address Of Recipient / Shipping Detail</b><br>
                                                        <?php echo html_entity_decode($invoiceData[0]->recipient_shipping_name); ?><br>
                                                        <?php if($invoiceData[0]->recipient_shipping_company_name) { ?> <?php echo $invoiceData[0]->recipient_shipping_company_name; ?><br> <?php } ?>
                                                        <?php echo $invoiceData[0]->recipient_shipping_address; ?><br>
														<?php $recipient_shipping_vendor_data = $obj_purchase->getVendorDetailByVendorId($invoiceData[0]->recipient_shipping_vendor_type); ?>
														<?php echo $recipient_shipping_vendor_data['data']->vendor_name; ?><br>
														<?php if(!empty($invoiceData[0]->recipient_shipping_gstin_number)) { ?>
															<b>GSTIN/UIN:</b> <?php echo $invoiceData[0]->recipient_shipping_gstin_number; ?>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>

                                    <tr>
										<td colspan="2">

											<table class="view-invoice-table" align="center">
												<tr class="heading">
                                                    <td>S.No</td>
                                                    <td>Goods/Services</td>
                                                    <td>HSN/SAC Code</td>
													<td>Item Description</td>
                                                    <td>Qty</td>
                                                    <td>Unit</td>
                                                    <td>Rate<br>(<i class="fa fa-inr"></i>)</td>
                                                    <td>Total<br>(<i class="fa fa-inr"></i>)</td>
                                                    <td>Discount<br>(%)</td>
                                                    <td>Net Total Value<br>(<i class="fa fa-inr"></i>)</td>
                                                </tr>

                                                <?php $counter = 1; ?>
												<?php $total_taxable_subtotal = 0.00; ?>
												<?php foreach ($invoiceData as $invData) { ?>

													<tr class="item">
                                                        <td><?php echo $counter; ?></td>
                                                        <td><?php echo $invData->item_name; ?></td>
                                                        <td><?php echo $invData->item_hsncode; ?></td>
														<td><?php echo $invData->item_description; ?></td>
                                                        <td><?php echo $invData->item_quantity; ?></td>
                                                        <td><?php echo $invData->item_unit; ?></td>
                                                        <td><?php echo $invData->item_unit_price; ?></td>
                                                        <td><?php echo $invData->subtotal; ?></td>
                                                        <td><?php echo $invData->discount; ?></td>
                                                        <td><?php echo $invData->taxable_subtotal; ?></td>
                                                    </tr>

													<?php $total_taxable_subtotal += $invData->taxable_subtotal; ?>
													<?php $counter++; ?>
												<?php } ?>
												
												<tr class="total lightblue">
													<td colspan="9" align="right" class="fontbold textsmall">Total Invoice Value:</td>
													<td style="text-align:center;"><span><?php echo $total_taxable_subtotal; ?></span></td>
												</tr>

                                                <tr class="total lightyellow">
                                                    <td colspan="10">
                                                        Total Invoice Value (In Figure): <i class="fa fa-inr"></i><?php echo $invoiceData[0]->invoice_total_value; ?>
                                                    </td>
                                                </tr>

                                                <?php $invoice_total_value_words = $obj_purchase->convert_number_to_words($invoiceData[0]->invoice_total_value); ?>
                                                <tr class="total lightpink">
                                                    <td colspan="10">
                                                        Total Invoice Value (In Words): <?php echo ucwords($invoice_total_value_words); ?>
                                                    </td>
                                                </tr>

                                            </table>

                                        </td>
                                    </tr>

									<?php if(!empty($invoiceData[0]->description)) { ?>
										<tr class="description">
											<td colspan="2">
												<p><b>Additional Notes:</b> <?php echo $invoiceData[0]->description; ?></p>
											</td>
										</tr>
                                    <?php } ?>

                                </table>			
                            </div>
                            <!--INVOICE DIV PRINT END  HERE-->
                        </div>
                    </div>
				<?php } ?>

            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {

		$(".formboxcontainer").on("click", ".checkAll", function(){

			if($('[name="purchase_invoice[]"]:checked').length > 0) {
				$(".checkAll").text("Check All");
				$('.purchaseInvoice').prop('checked', false);
			} else {
				$(".checkAll").text("Uncheck All");
				$('.purchaseInvoice').prop('checked', true);
			}
		});

		$(".formboxcontainer").on("click", ".cancelAll", function(){
			
			var selectedCheckboxes = new Array();
			$('.purchaseInvoice:checkbox:checked').each(function () {
				selectedCheckboxes.push($(this).val());
			});
			
			if(selectedCheckboxes.length > 0) {

				$.ajax({
					data: {purchaseInvoiceIds:selectedCheckboxes, action:"cancelSelectedPurchaseInvoice"},
					dataType: 'json',
					type: 'post',
					url: "<?php echo PROJECT_URL; ?>/?ajax=purchase_invoice_cancel",
					success: function(response){

						if(response.status == "success") {
							window.location.reload();
						} else {
							jAlert(response.message);
						}
					}
				});
			}
		});
		
		$(".formboxcontainer").on("click", ".revokeAll", function () {

            var selectedCheckboxes = new Array();
            $('.purchaseInvoice:checkbox:checked').each(function () {
                selectedCheckboxes.push($(this).val());
            });

            if (selectedCheckboxes.length > 0) {

                $.ajax({
                    data: {purchaseInvoiceIds: selectedCheckboxes, action: "revokeSelectedPurchaseInvoice"},
                    dataType: 'json',
                    type: 'post',
                    url: "<?php echo PROJECT_URL; ?>/?ajax=purchase_invoice_cancel",
                    success: function (response) {

                        if (response.status == "success") {
                            window.location.reload();
                        } else {
                            jAlert(response.message);
                        }
                    }
                });
            }
        });

		$("#mainTable").on("click", ".purchaseInvoice", function(){

			if($('[name="purchase_invoice[]"]:checked').length == 0) {
				$(".checkAll").text("Check All");
			}
		});
		
		$("#mainTable").on("click", ".cancelPurchaseInvoice", function(){

			var dataInvoiceId = $(this).attr("data-invoice-id");
			$.ajax({
                data: {purchaseInvoiceId:dataInvoiceId, action:"cancelPurchaseInvoice"},
                dataType: 'json',
                type: 'post',
                url: "<?php echo PROJECT_URL; ?>/?ajax=purchase_invoice_cancel",
                success: function(response){

                    if(response.status == "success") {

						$('a[data-invoice-id='+dataInvoiceId+']').addClass("revokePurchaseInvoice");
						$('a[data-invoice-id='+dataInvoiceId+']').removeClass("cancelPurchaseInvoice");
						$('a[data-invoice-id='+dataInvoiceId+']').text("Revoke");
						jAlert(response.message);
					} else {
						jAlert(response.message);
					}
                }
            });
		});

		$("#mainTable").on("click", ".revokePurchaseInvoice", function(){
			
			var dataInvoiceId = $(this).attr("data-invoice-id");
			$.ajax({
                data: {purchaseInvoiceId:dataInvoiceId, action:"revokePurchaseInvoice"},
                dataType: 'json',
                type: 'post',
                url: "<?php echo PROJECT_URL; ?>/?ajax=purchase_invoice_cancel",
                success: function(response){

                    if(response.status == "success") {

						$('a[data-invoice-id='+dataInvoiceId+']').addClass("cancelPurchaseInvoice");
						$('a[data-invoice-id='+dataInvoiceId+']').removeClass("revokePurchaseInvoice");
						$('a[data-invoice-id='+dataInvoiceId+']').text("Cancel");
						jAlert(response.message);
					} else {
						jAlert(response.message);
					}
                }
            });
		});

        TableManaged.init();
		
		$("#invoiceDateOption").change(function(){
			TableManaged.init();
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
                    "searching": false,
                    "bLengthChange": false,
                    "sAjaxSource": "<?php echo PROJECT_URL; ?>/?ajax=purchase_bill_of_supply_invoice_list&invoiceDate=" + $("#invoiceDateOption option:selected").val(),
                    "fnServerParams": function (aoData) {
                    },
                    "iDisplayLength": 6
                });
            }
        };
    }();
</script>