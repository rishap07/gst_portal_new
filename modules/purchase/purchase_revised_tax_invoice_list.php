<?php
$obj_purchase = new purchase();

if(!$obj_purchase->can_read('client_invoice')) {

	$obj_purchase->setError($obj_purchase->getValMsg('can_read'));
	$obj_purchase->redirect(PROJECT_URL."/?page=dashboard");
	exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'downloadPurchaseRTInvoice' && isset($_GET['id']) && $obj_purchase->validateId($_GET['id'])) {

    $htmlResponse = $obj_purchase->generatePurchaseRTInvoiceHtml($_GET['id']);
    if ($htmlResponse === false) {

        $obj_purchase->setError("No invoice found.");
        $obj_purchase->redirect(PROJECT_URL . "?page=purchase_revised_tax_invoice_list");
        exit();
    }

    $obj_mpdf = new mPDF();
    $obj_mpdf->SetHeader('Revised Tax Invoice');
    $obj_mpdf->WriteHTML($htmlResponse);

    $taxInvoicePdf = 'revised-tax-invoice-' . $_GET['id'] . '.pdf';
    ob_clean();
    $obj_mpdf->Output($taxInvoicePdf, 'D');
}

if (isset($_GET['action']) && $_GET['action'] == 'emailPurchaseRTInvoice' && isset($_GET['id']) && $obj_purchase->validateId($_GET['id'])) {

    $htmlResponse = $obj_purchase->generatePurchaseRTInvoiceHtml($_GET['id']);

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
    $mail->Subject = 'GST Purchase Revised Tax Invoice-' . $_GET['id'];
    $mail->SetFrom('noreply.Gstkeeper@gstkeeper.com', 'GST Keeper');
    $mail->MsgHTML($message);
    $mail->AddAddress($sendmail);
    $mail->AddBCC("ishwar.ghiya@cyfuture.com");

    if ($mail->Send()) {

        $obj_purchase->setSuccess("Mail Sent Successfully.");
        $mail->ClearAllRecipients();
        $obj_purchase->redirect(PROJECT_URL . "?page=purchase_revised_tax_invoice_list");
    } else {

        $obj_purchase->setError($mail->ErrorInfo);
        $mail->ClearAllRecipients();
        $obj_purchase->redirect(PROJECT_URL . "?page=purchase_revised_tax_invoice_list");
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'printPurchaseRTInvoice' && isset($_GET['id']) && $obj_purchase->validateId($_GET['id'])) {

    $htmlResponse = $obj_purchase->generatePurchaseRTInvoiceHtml($_GET['id']);

    if ($htmlResponse === false) {

        $obj_purchase->setError("No invoice found.");
        $obj_purchase->redirect(PROJECT_URL . "?page=purchase_revised_tax_invoice_list");
        exit();
    }

    $obj_mpdf = new mPDF();
    $obj_mpdf->SetHeader('Purchase Revised Tax Invoice');
    $obj_mpdf->WriteHTML($htmlResponse);

    $taxInvoicePdf = 'revised-tax-invoice-' . $_GET['id'] . '.pdf';
    ob_clean();
    $obj_mpdf->Output($taxInvoicePdf, 'I');
}

$currentFinancialYear = $obj_purchase->generateFinancialYear();
$dataThemeSettingArr = $obj_purchase->getUserThemeSetting( $obj_purchase->sanitize($_SESSION['user_detail']['user_id']) );
?>
<style>
    #mainTable thead{display:none;}
</style>

<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Revised Tax Invoices / Debit Note / Credit Note</h1></div>
        <div class="formboxcontainer padleft0 mobinvoicecol" style="padding-top:0px;">

            <?php $obj_purchase->showErrorMessage(); ?>
            <?php $obj_purchase->showSuccessMessge(); ?>
            <?php $obj_purchase->unsetMessage(); ?>

            <div class="row">

                <!--INVOICE LEFT TABLE START HERE-->
                <div class="fixed-left-col col-sm-12 col-xs-12" style="padding-right:0px; padding-left:0px;">

                    <div class="invoiceheaderfixed">
                        <div class="col-md-8">
                            <a href='javascript:void(0)' class="btn btn-warning pull-left checkAll">Check All</a>
                            <a href='javascript:void(0)' class="btn btn-danger pull-left cancelAll" data-toggle="tooltip" title="Cancel All"><i class="fa fa-times" aria-hidden="true"></i></a>
							<a href='javascript:void(0)' class="btn btn-success pull-left revokeAll" data-toggle="tooltip" title="Revoke All"><i class="fa fa-undo" aria-hidden="true"></i></a>
                        </div>

                        <div class="col-md-4">
                            <a href='<?php echo PROJECT_URL; ?>/?page=purchase_revised_tax_invoice_create' class="btn btn-success pull-right"><i class="fa fa-plus" aria-hidden="true"></i> New</a>
                        </div>
                    </div>

                    <div class="tableresponsive">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainTable" class="inovicelefttable" style="margin-top:53px;">
                        </table>
                    </div>
                </div>

                <?php
                /* code for display invoice according to invoice id pass in query string */
                if (isset($_GET['action']) && $_GET['action'] == 'viewPurchaseRTInvoice' && isset($_GET['id']) && $obj_purchase->validateId($_GET['id'])) {

                    $invid = $obj_purchase->sanitize($_GET['id']);
                    $invoiceData = $obj_purchase->get_results("select 
												ci.*, 
												cii.purchase_invoice_item_id, 
												cii.item_id, 
												cii.item_id, 
												cii.item_name, 
												cii.item_hsncode, 
												cii.item_quantity, 
												cii.item_unit, 
												cii.item_unit_price, 
												cii.subtotal, 
												cii.discount, 
												cii.advance_amount, 
												cii.taxable_subtotal, 
												cii.cgst_rate, 
												cii.cgst_amount, 
												cii.sgst_rate, 
												cii.sgst_amount, 
												cii.igst_rate, 
												cii.igst_amount, 
												cii.cess_rate, 
												cii.cess_amount, 
												cii.total 
												from 
											" . $obj_purchase->getTableName('client_purchase_invoice') . " as ci INNER JOIN " . $obj_purchase->getTableName('client_purchase_invoice_item') . " as cii ON ci.purchase_invoice_id = cii.purchase_invoice_id where ci.purchase_invoice_id = " . $invid . " AND ci.invoice_type IN('revisedtaxinvoice', 'creditnote', 'debitnote') AND ci.added_by = '" . $obj_purchase->sanitize($_SESSION['user_detail']['user_id']) . "' AND cii.added_by = '" . $obj_purchase->sanitize($_SESSION['user_detail']['user_id']) . "' AND ci.is_deleted='0' AND cii.is_deleted='0'");
                } else {

					$invoiceData = $obj_purchase->get_results("select 
												ci.*, 
												cii.purchase_invoice_item_id, 
												cii.item_id, 
												cii.item_name, 
												cii.item_hsncode, 
												cii.item_quantity, 
												cii.item_unit, 
												cii.item_unit_price, 
												cii.subtotal, 
												cii.discount, 
												cii.advance_amount, 
												cii.taxable_subtotal, 
												cii.cgst_rate, 
												cii.cgst_amount, 
												cii.sgst_rate, 
												cii.sgst_amount, 
												cii.igst_rate, 
												cii.igst_amount, 
												cii.cess_rate, 
												cii.cess_amount, 
												cii.total 
												from 
											" . $obj_purchase->getTableName('client_purchase_invoice') . " as ci INNER JOIN " . $obj_purchase->getTableName('client_purchase_invoice_item') . " as cii ON ci.purchase_invoice_id = cii.purchase_invoice_id where ci.purchase_invoice_id = (SELECT purchase_invoice_id FROM ".$obj_purchase->getTableName('client_purchase_invoice')." Where invoice_type IN('revisedtaxinvoice', 'creditnote', 'debitnote') AND added_by = '" . $obj_purchase->sanitize($_SESSION['user_detail']['user_id']) . "' AND is_deleted='0' Order by purchase_invoice_id desc limit 0,1) AND ci.invoice_type IN('revisedtaxinvoice', 'creditnote', 'debitnote') AND ci.added_by = '" . $obj_purchase->sanitize($_SESSION['user_detail']['user_id']) . "' AND cii.added_by = '" . $obj_purchase->sanitize($_SESSION['user_detail']['user_id']) . "' AND ci.is_deleted='0' AND cii.is_deleted='0'");
				}
                /* Invoice display query code end here */
                ?>
                <!--INVOICE LEFT TABLE END HERE-->

                <!--INVOICE PRINT RIGHT  START HERE-->
                <?php if (isset($invoiceData[0]->purchase_invoice_id)) { ?>

                    <div class="col-md-8 col-sm-12 mobdisplaynone invoicergtcol" style="padding-right:0px;">

                        <!---INVOICE TOP ICON START HERE-->
                        <div class="inovicergttop">
                            <ul class="iconlist">
                                
                                <li><a href="<?php echo PROJECT_URL; ?>/?page=purchase_revised_tax_invoice_update&action=editPurchaseRTInvoice&id=<?php echo $invoiceData[0]->purchase_invoice_id; ?>"><div data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></div></a></li>
                                <li><a href="<?php echo PROJECT_URL; ?>/?page=purchase_revised_tax_invoice_list&action=downloadPurchaseRTInvoice&id=<?php echo $invoiceData[0]->purchase_invoice_id; ?>"><div data-toggle="tooltip" data-placement="bottom" title="PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></div></a></li>
                                <li><a href="<?php echo PROJECT_URL; ?>/?page=purchase_revised_tax_invoice_list&action=printPurchaseRTInvoice&id=<?php echo $invoiceData[0]->purchase_invoice_id; ?>" target="_blank"><div data-toggle="tooltip" data-placement="bottom" title="PRINT"><i class="fa fa-print" aria-hidden="true"></i></div></a></li>
                                <li><a href="<?php echo PROJECT_URL; ?>/?page=purchase_revised_tax_invoice_list&action=emailPurchaseRTInvoice&id=<?php echo $invoiceData[0]->purchase_invoice_id; ?>"><div data-toggle="tooltip" data-placement="bottom" title="Email"><i class="fa fa-envelope-o" aria-hidden="true"></i></div></a></li>
							</ul>
                        </div>

                        <!---INVOICE div print START HERE-->
                        <div id="taxinvoice_print">

                            <!---INVOICE TOP ICON START HERE-->
                            <div class="height20"></div>
                            <div class="clearfix"></div>

                            <div class="invoice-box" style="width:650px;overflow-x:scroll;overflow-y:hidden;">
                                <table cellpadding="0" cellspacing="0" style="width:625px;">
                                    <tr class="top">
                                        <td colspan="2">
                                            <table>
                                                <tr>
                                                    <td class="title">
                                                        <?php if (isset($dataThemeSettingArr['data']->theme_logo) && $dataThemeSettingArr['data']->theme_logo != "") { ?>
                                                            <img src="<?php echo PROJECT_URL . '/upload/theme-logo/' . $dataThemeSettingArr['data']->theme_logo; ?>" style="width:100%;max-width:300px;">
                                                        <?php } else { ?>
                                                            <img src="<?php echo PROJECT_URL; ?>/image/gst-k-logo.png" style="width:100%;max-width:300px;">
                                                        <?php } ?>
                                                    </td>

													<td>
                                                        <b>Invoice #</b>: <?php echo $invoiceData[0]->serial_number; ?><br>
                                                        <b>Reference #</b>: <?php echo $invoiceData[0]->reference_number; ?><br>
                                                        <b>Nature:</b> <?php echo "Sales Invoice"; ?><br>
                                                        <b>Invoice Date:</b> <?php echo $invoiceData[0]->invoice_date; ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>

                                    <?php $supply_place_data = $obj_purchase->getStateDetailByStateId($invoiceData[0]->supply_place); ?>

                                    <tr class="information">
                                        <td colspan="2">
                                            <table>
                                                <tr>
                                                    <td>
                                                        <?php echo $invoiceData[0]->company_name; ?><br>
                                                        <?php echo $invoiceData[0]->company_address; ?><br>
                                                        <b>GSTIN:</b> <?php echo $invoiceData[0]->company_gstin_number; ?>
                                                    </td>

                                                    <td>
                                                        <?php if (isset($invoiceData[0]->supply_place) && $invoiceData[0]->supply_place > 0) { ?><b>Place Of Supply:</b> <?php echo $supply_place_data['data']->state_name; ?><br> <?php } ?>
                                                        <b>Document Nature:</b> <?php if($invoiceData[0]->invoice_type == "creditnote") { echo "Credit Note"; } else if($invoiceData[0]->invoice_type == "debitnote") { echo "Debit Note"; } else { echo "Revised Tax Invoice"; } ?><br>
														<b>Corresponding Type:</b> <?php if($invoiceData[0]->invoice_corresponding_type == "taxinvoice") { echo "Tax Invoice" . "<br>"; } else if($invoiceData[0]->invoice_corresponding_type == "billofsupplyinvoice") { echo "Bill of Supply Invoice" . "<br>"; } ?>

														<?php $dataCorresDocumentRow = $obj_purchase->get_row("select * from ".$obj_purchase->getTableName('client_purchase_invoice')." where purchase_invoice_id = '".$invoiceData[0]->corresponding_document_number."' AND invoice_type = '".$invoiceData[0]->invoice_corresponding_type."' AND is_deleted='0' AND added_by = ".$obj_purchase->sanitize($_SESSION['user_detail']['user_id'])); ?>

                                                        <?php if(!empty($dataCorresDocumentRow)) { ?>
                                                            <b>Document Serial:</b> <?php echo $dataCorresDocumentRow->serial_number; ?><br>
                                                            <b>Document Reference:</b> <?php echo $dataCorresDocumentRow->reference_number; ?><br>
                                                            <b>Document Date:</b> <?php echo $dataCorresDocumentRow->invoice_date; ?><br>
														<?php } ?>

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
													<td>
                                                        <b>Supplier Detail</b><br>
                                                        <?php echo html_entity_decode($invoiceData[0]->supplier_billing_name); ?><br>
                                                        <?php if($invoiceData[0]->supplier_billing_company_name) { ?> <?php echo $invoiceData[0]->supplier_billing_company_name; ?><br> <?php } ?>
                                                        <?php echo $invoiceData[0]->supplier_billing_address; ?><br>
														<?php $supplier_billing_vendor_data = $obj_purchase->getVendorDetailByVendorId($invoiceData[0]->supplier_billing_vendor_type); ?>
														<?php echo $supplier_billing_vendor_data['data']->vendor_name; ?><br>
														<?php if(!empty($invoiceData[0]->supplier_billing_gstin_number)) { ?>
															<b>GSTIN:</b> <?php echo $invoiceData[0]->supplier_billing_gstin_number; ?>
                                                        <?php } ?>
                                                    </td>

													<td>
                                                        <b>Address Of Recipient / Shipping Detail</b><br>
                                                        <?php echo html_entity_decode($invoiceData[0]->recipient_shipping_name); ?><br>
                                                        <?php if($invoiceData[0]->recipient_shipping_company_name) { ?> <?php echo $invoiceData[0]->recipient_shipping_company_name; ?><br> <?php } ?>
                                                        <?php echo $invoiceData[0]->recipient_shipping_address; ?><br>
														<?php $recipient_shipping_vendor_data = $obj_purchase->getVendorDetailByVendorId($invoiceData[0]->recipient_shipping_vendor_type); ?>
														<?php echo $recipient_shipping_vendor_data['data']->vendor_name; ?><br>
														<?php if(!empty($invoiceData[0]->recipient_shipping_gstin_number)) { ?>
															<b>GSTIN:</b> <?php echo $invoiceData[0]->recipient_shipping_gstin_number; ?>
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
                                                    <td rowspan="2">S.No</td>
                                                    <td rowspan="2">Goods/Services</td>
                                                    <td rowspan="2">HSN/SAC Code</td>
                                                    <td rowspan="2">Qty</td>
                                                    <td rowspan="2">Unit</td>
                                                    <td rowspan="2">Rate<br>(<i class="fa fa-inr"></i>)</td>
                                                    <td rowspan="2">Total<br>(<i class="fa fa-inr"></i>)</td>
                                                    <td rowspan="2">Discount(%)</td>
                                                    <td rowspan="2">Taxable Value<br>(<i class="fa fa-inr"></i>)</td>
                                                    <td colspan="2" style="border-bottom:1px solid #808080;">CGST</td>
                                                    <td colspan="2" style="border-bottom:1px solid #808080;">SGST</td>
                                                    <td colspan="2" style="border-bottom:1px solid #808080;">IGST</td>
                                                    <td colspan="2" style="border-bottom:1px solid #808080;">CESS</td>
                                                </tr>

                                                <tr class="heading">
                                                    <td>(%)</td>
                                                    <td>Amt (<i class="fa fa-inr"></i>)</td>
                                                    <td>(%)</td>
                                                    <td>Amt (<i class="fa fa-inr"></i>)</td>
                                                    <td>(%)</td>
                                                    <td>Amt (<i class="fa fa-inr"></i>)</td>
                                                    <td>(%)</td>
                                                    <td>Amt (<i class="fa fa-inr"></i>)</td>
                                                </tr>

                                                <?php $counter = 1; ?>
												<?php foreach ($invoiceData as $invData) { ?>

                                                    <tr class="item">
                                                        <td><?php echo $counter; ?></td>
                                                        <td><?php echo $invData->item_name; ?></td>
                                                        <td><?php echo $invData->item_hsncode; ?></td>
                                                        <td><?php echo $invData->item_quantity; ?></td>
                                                        <td><?php echo $invData->item_unit; ?></td>
                                                        <td><?php echo $invData->item_unit_price; ?></td>
                                                        <td><?php echo $invData->subtotal; ?></td>
                                                        <td><?php echo $invData->discount; ?></td>
                                                        <td><?php echo $invData->taxable_subtotal; ?></td>
                                                        <td><?php echo $invData->cgst_rate; ?></td>
                                                        <td><?php echo $invData->cgst_amount; ?></td>
                                                        <td><?php echo $invData->sgst_rate; ?></td>
                                                        <td><?php echo $invData->sgst_amount; ?></td>
                                                        <td><?php echo $invData->igst_rate; ?></td>
                                                        <td><?php echo $invData->igst_amount; ?></td>
                                                        <td><?php echo $invData->cess_rate; ?></td>
                                                        <td><?php echo $invData->cess_amount; ?></td>
                                                    </tr>

													<?php $counter++; ?>
                                                <?php } ?>

                                                <tr class="total">
                                                    <td colspan="17">Total Invoice Value (In Figure): <i class="fa fa-inr"></i><?php echo $invoiceData[0]->invoice_total_value; ?></td>
                                                </tr>
												
												<?php $invoice_total_value_words = $obj_purchase->convert_number_to_words($invoiceData[0]->invoice_total_value); ?>
                                                <tr class="total">
                                                	<td colspan="17">Total Invoice Value (In Words): <?php echo ucwords($invoice_total_value_words); ?></td>
                                                </tr>

                                            </table>

                                        </td>
                                    </tr>

                                    <?php if(!empty($invoiceData[0]->description)) { ?>
                                        <tr class="description">
                                            <td colspan="2">
												<p><b>Description:</b> <?php echo $invoiceData[0]->description; ?></p>
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
                    "sAjaxSource": "<?php echo PROJECT_URL; ?>/?ajax=purchase_revised_tax_invoice_list",
                    "fnServerParams": function (aoData) {
                    },
                    "iDisplayLength": 6
                });
            }
        };
    }();
</script>