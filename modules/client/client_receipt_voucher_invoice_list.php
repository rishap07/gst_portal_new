<?php
$obj_client = new client();

//if (isset($_GET['action']) && $_GET['action'] == 'downloadPurchaseInvoice' && isset($_GET['id']) && $obj_client->validateId($_GET['id'])) {
//
//    $htmlResponse = $obj_purchase->generatePurchaseInvoiceHtml($_GET['id']);
//    if ($htmlResponse === false) {
//
//        $obj_purchase->setError("No purchase invoice found.");
//        $obj_purchase->redirect(PROJECT_URL . "?page=purchase_invoice_list");
//        exit();
//    }
//
//    $obj_mpdf = new mPDF();
//    $obj_mpdf->SetHeader('Purchase Tax Invoice');
//    $obj_mpdf->WriteHTML($htmlResponse);
//
//    $taxInvoicePdf = 'purchase-tax-invoice-' . $_GET['id'] . '.pdf';
//    ob_clean();
//    $obj_mpdf->Output($taxInvoicePdf, 'D');
//}
//
//if (isset($_GET['action']) && $_GET['action'] == 'emailPurchaseInvoice' && isset($_GET['id']) && $obj_purchase->validateId($_GET['id'])) {
//
//    $htmlResponse = $obj_purchase->generatePurchaseInvoiceHtml($_GET['id']);
//
//    if ($htmlResponse === false) {
//
//        $obj_purchase->setError("No purchase invoice found.");
//        $obj_purchase->redirect(PROJECT_URL . "?page=purchase_invoice_list");
//        exit();
//    }
//
//    $dataCurrentUserArr = $obj_purchase->getUserDetailsById($obj_purchase->sanitize($_SESSION['user_detail']['user_id']));
//    $sendmail = $dataCurrentUserArr['data']->kyc->email;
//
//    $mail = new PHPMailer();
//    $message = html_entity_decode($htmlResponse);
//    $mail->IsSMTP();
//    $mail->Host = "49.50.104.11";
//    $mail->Port = 25;
//    //$mail->SMTPDebug = 2;
//
//    $mail->SMTPOptions = array(
//        'ssl' => array(
//            'verify_peer' => false,
//            'verify_peer_name' => false,
//            'allow_self_signed' => true
//        )
//    );
//
//    $mail->CharSet = "UTF-8";
//    $mail->Subject = 'GST Invoice-' . $_GET['id'];
//    $mail->SetFrom('noreply.Gstkeeper@gstkeeper.com', 'GST Keeper');
//    $mail->MsgHTML($message);
//    $mail->AddAddress($sendmail);
//    $mail->AddBCC("ishwar.ghiya@cyfuture.com");
//
//    if ($mail->Send()) {
//
//        $obj_purchase->setSuccess("Mail Sent Successfully.");
//        $mail->ClearAllRecipients();
//        $obj_purchase->redirect(PROJECT_URL . "?page=purchase_invoice_list");
//    } else {
//
//        $obj_purchase->setError($mail->ErrorInfo);
//        $mail->ClearAllRecipients();
//        $obj_purchase->redirect(PROJECT_URL . "?page=purchase_invoice_list");
//    }
//}
//
//if (isset($_GET['action']) && $_GET['action'] == 'printPurchaseInvoice' && isset($_GET['id']) && $obj_purchase->validateId($_GET['id'])) {
//
//    $htmlResponse = $obj_purchase->generatePurchaseInvoiceHtml($_GET['id']);
//
//    if ($htmlResponse === false) {
//
//        $obj_purchase->setError("No purchase invoice found.");
//        $obj_purchase->redirect(PROJECT_URL . "?page=purchase_invoice_list");
//        exit();
//    }
//
//    $obj_mpdf = new mPDF();
//    $obj_mpdf->SetHeader('Purchase Tax Invoice');
//    $obj_mpdf->WriteHTML($htmlResponse);
//
//    $taxInvoicePdf = 'purchase-tax-invoice-' . $_GET['id'] . '.pdf';
//    ob_clean();
//    $obj_mpdf->Output($taxInvoicePdf, 'I');
//}
//
//$currentFinancialYear = $obj_purchase->generateFinancialYear();
//$dataThemeSettingArr = $obj_purchase->getUserThemeSetting($obj_purchase->sanitize($_SESSION['user_detail']['user_id']));

/* Display client all tax invoice */


$obj_client = new client();
if (!isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '') {
    $obj_client->redirect(PROJECT_URL);
    exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'deleteRVInvoice' && isset($_GET['id']) && $obj_client->validateId($_GET['id'])) {

    if (!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])) {

        $obj_client->setError('Invalid access to files');
    } else {

        $obj_client->redirect(PROJECT_URL . "?page=client_receipt_voucher_invoice_list");
    }
}
$ciTable = $obj_client->getTableName('client_rv_invoice');
$cirTable = $obj_client->getTableName('client_rv_invoice_item');
$msTable = $obj_client->getTableName('state');
?>
<style>
    #mainTable thead{display:none;}
</style>

<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>View Receipt Voucher Invoice</h1></div>
        <div class="formboxcontainer padleft0 mobinvoicecol" style="padding-top:0px;">

            <?php $obj_client->showErrorMessage(); ?>
            <?php $obj_client->showSuccessMessge(); ?>
            <?php $obj_client->unsetMessage(); ?>

            <div class="row">

                <!--INVOICE LEFT TABLE START HERE-->
                <div class="fixed-left-col col-sm-12 col-xs-12" style="padding-right:0px; padding-left:0px;">

                    <div class="invoiceheaderfixed">
                        <div class="col-md-8">

                            <a href='javascript:void(0)' class="btn btn-warning pull-left checkAll">Check All</a>
                            <a href='javascript:void(0)' class="btn btn-danger pull-left cancelAll"><i class="fa fa-times" aria-hidden="true"></i> Cancel</a>

                            <!--
                                    <ul class="nav pull-left nav-pills" role="tablist">
                                            <li role="presentation" class="dropdown">
                                                    <a href="#" class="dropdown-toggle greyborder" id="drop5" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> All Invoice <span class="caret"></span> </a>
                                                    <ul class="dropdown-menu" id="menu2" aria-labelledby="drop5">
                                                            <li><a href="#">Action</a></li>
                                                            <li><a href="#">Another action</a></li> 
                                                            <li><a href="#">Something else here</a></li>
                                                            <li role="separator" class="divider"></li> <li><a href="#">Separated link</a></li> 
                                                    </ul>
                                            </li>
                                    </ul>
                            -->
                        </div>

                        <div class="col-md-4">

                            <a href='<?php echo PROJECT_URL; ?>/?page=client_create_receipt_voucher_invoice' class="btn btn-success pull-right"><i class="fa fa-plus" aria-hidden="true"></i> New</a>

                            <!--
                                    <ul class="nav nav-pills" role="tablist">
                                            <li role="presentation" class="dropdown"> 
                                                    <a href="#" class="dropdown-toggle iconmenu" id="drop5" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-bars" aria-hidden="true"></i></span> </a>
                                                    <ul class="dropdown-menu" id="menu2" aria-labelledby="drop5">
                                                            <li><a href="#">Action</a></li>
                                                            <li><a href="#">Another action</a></li> 
                                                            <li><a href="#">Something else here</a></li>
                                                            <li role="separator" class="divider"></li> <li><a href="#">Separated link</a></li> 
                                                    </ul>
                                            </li>
                                    </ul>
                            -->
                        </div>
                    </div>

                    <div class="tableresponsive">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainTable" class="inovicelefttable" style="margin-top:53px;">
                        </table>
                    </div>
                </div>

                <?php
                /* code for display invoice according to invoice id pass in query string */
                if (isset($_GET['action']) && $_GET['action'] == 'viewInvoice' && isset($_GET['id']) && $obj_client->validateId($_GET['id'])) {
                    //die('yes');
                    $invoicePurchaseId = $obj_client->sanitize($_GET['id']);
                    $uWhere = " where ci.is_deleted='0' AND ci.added_by='" . $invoicePurchaseId . "' ";
                    

                    $invoiceData = $obj_client->get_results("select ci.invoice_id,ci.serial_number"
                            . " ci.reference_number,ci.invoice_date,ci.is_canceled,ci.supply_place"
                            . ",ms2.state_code as supply_state_code,"
                            . "ms2.state_tin as supply_state_tin,ci.invoice_total_value, ci.billing_name, ci.billing_state,"
                            . "ms.state_name as billing_state_name, ms.state_code as billing_state_code,
                              ms.state_tin as billing_state_tin, ci.shipping_name, ci.shipping_state,
                              ms1.state_name as shipping_state_name,ms1.state_code as shipping_state_code, ms1.state_tin as shipping_state_tin
                            FROM $ciTable as ci INNER JOIN $msTable as ms ON ci.billing_state = ms.state_id "
                            . "INNER JOIN $msTable as ms1 ON ci.shipping_state = ms1.state_id "
                            . "INNER JOIN $msTable as ms2 ON ci.supply_place = ms2.state_id ".$uWhere."                            
                     ORDER BY ci.invoice_id DESC ");
                    
//                    $query="select ci.invoice_id,ci.serial_number"
//                            . " ci.reference_number,ci.invoice_date,ci.is_canceled,ci.supply_place"
//                            . "ms2.state_name as supply_state_name,"
//                            . "ms2.state_tin as supply_state_tin,ci.invoice_total_value, ci.billing_name, ci.billing_state,"
//                            . "ms.state_name as billing_state_name, ms.state_code as billing_state_code,
//                              ms.state_tin as billing_state_tin, ci.shipping_name, ci.shipping_state,
//                              ms1.state_name as shipping_state_name,ms1.state_code as shipping_state_code, ms1.state_tin as shipping_state_tin
//                            FROM $ciTable as ci INNER JOIN $msTable as ms ON ci.billing_state = ms.state_id "
//                            . "INNER JOIN $msTable as ms1 ON ci.shipping_state = ms1.state_id "
//                            . "INNER JOIN $msTable as ms2 ON ci.supply_place = ms2.state_id ".$uWhere."                            
//                     ORDER BY ci.invoice_id DESC ";
//                    echo $query ; die();
//                    echo "<pre>";
//                    print_r($invoiceData);
//                    
//                    echo "<pre>";
                    //die();
                } else {

                    $invoiceData = $obj_client->get_results("select 
												ci.purchase_invoice_id, 
												ci.reference_number, 
												ci.serial_number, 
												(case 
													when ci.invoice_type='taxinvoice' Then 'Tax Invoice' 
													when ci.invoice_type='importinvoice' then 'Import Invoice' 
													when ci.invoice_type='sezunitinvoice' then 'SEZ Unit Invoice' 
													when ci.invoice_type='deemedimportinvoice' then 'Deemed Import Invoice' 
												end) as invoice_type, 
												(case 
													when ci.invoice_nature='salesinvoice' Then 'Sales Invoice' 
													when ci.invoice_nature='purchaseinvoice' then 'Purchase Invoice' 
												end) as invoice_nature, 
												ci.company_name, 
												ci.company_address, 
												ci.company_state, 
												ci.company_gstin_number, 
												(case 
													when ci.supply_type='normal' Then 'Normal' 
													when ci.supply_type='reversecharge' then 'Reverse Charge' 
													when ci.supply_type='tds' then 'TDS' 
													when ci.supply_type='tcs' then 'TCS' 
												end) as supply_type, 
												(case 
													when ci.import_supply_meant='withpayment' Then 'With Payment' 
													when ci.import_supply_meant='withoutpayment' then 'Without Payment' 
												end) as import_supply_meant, 
												ci.invoice_date, 
												ci.supply_place, 
												ci.advance_adjustment, 
												ci.receipt_voucher_number, 
												ci.supplier_billing_name, 
												ci.supplier_billing_company_name, 
												ci.supplier_billing_address, 
												ci.supplier_billing_state, 
												ci.supplier_billing_state_name, 
												ci.supplier_billing_country, 
												ci.supplier_billing_gstin_number, 
												ci.recipient_shipping_name, 
												ci.recipient_shipping_company_name, 
												ci.recipient_shipping_address, 
												ci.recipient_shipping_state, 
												ci.recipient_shipping_state_name, 
												ci.recipient_shipping_country, 
												ci.recipient_shipping_gstin_number, 
												ci.import_bill_number, 
												ci.import_bill_date, 
												ci.description, 
												ci.invoice_total_value, 
												ci.financial_year, 
												(case 
													when ci.status='0' Then 'Active' 
													when ci.status='1' then 'Inactive' 
												end) as status, 
												ci.is_canceled, 
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
											" . $obj_client->getTableName('client_purchase_invoice') . " as ci INNER JOIN " . $obj_client->getTableName('client_purchase_invoice_item') . " as cii ON ci.purchase_invoice_id = cii.purchase_invoice_id where ci.purchase_invoice_id = (SELECT purchase_invoice_id FROM " . $obj_client->getTableName('client_purchase_invoice') . " Where added_by = '" . $obj_client->sanitize($_SESSION['user_detail']['user_id']) . "' AND is_deleted='0' Order by purchase_invoice_id desc limit 0,1) AND ci.added_by = '" . $obj_client->sanitize($_SESSION['user_detail']['user_id']) . "' AND cii.added_by = '" . $obj_client->sanitize($_SESSION['user_detail']['user_id']) . "' AND ci.is_deleted='0' AND cii.is_deleted='0'");
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

                                <?php if ($invoiceData[0]->invoice_type == "Import Invoice") { ?>
                                    <li><a href="<?php echo PROJECT_URL; ?>/?page=purchase_import_invoice_update&action=editPurchaseInvoice&id=<?php echo $invoiceData[0]->purchase_invoice_id; ?>"><div data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></div></a></li>
                                <?php } else { ?>
                                    <li><a href="<?php echo PROJECT_URL; ?>/?page=purchase_invoice_update&action=editPurchaseInvoice&id=<?php echo $invoiceData[0]->purchase_invoice_id; ?>"><div data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></div></a></li>
                                <?php } ?>

                                <li><a href="<?php echo PROJECT_URL; ?>/?page=purchase_invoice_list&action=downloadPurchaseInvoice&id=<?php echo $invoiceData[0]->purchase_invoice_id; ?>"><div data-toggle="tooltip" data-placement="bottom" title="PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></div></a></li>
                                <li><a href="<?php echo PROJECT_URL; ?>/?page=purchase_invoice_list&action=printPurchaseInvoice&id=<?php echo $invoiceData[0]->purchase_invoice_id; ?>" target="_blank"><div data-toggle="tooltip" data-placement="bottom" title="PRINT"><i class="fa fa-print" aria-hidden="true"></i></div></a></li>
                                <li><a href="<?php echo PROJECT_URL; ?>/?page=purchase_invoice_list&action=emailPurchaseInvoice&id=<?php echo $invoiceData[0]->purchase_invoice_id; ?>"><div data-toggle="tooltip" data-placement="bottom" title="Email"><i class="fa fa-envelope-o" aria-hidden="true"></i></div></a></li>
                                <!--<li><a href="#"><div data-toggle="tooltip" data-placement="bottom" title="Attached File"><i class="fa fa-paperclip" aria-hidden="true"></i></div></a></li>-->
                            </ul>

                            <!--
                                    <div class="col-md-7">
                                            <ul class="nav nav-pills pull-left" role="tablist" style="margin-left:10px;">
                                                    <li role="presentation" class="dropdown">
                                                            <a href="#" class="dropdown-toggle greyborder btngrey" id="drop5" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> All Invoice <span class="caret"></span> </a>
                                                            <ul class="dropdown-menu" id="menu2" aria-labelledby="drop5">
                                                                    <li><a href="#">Action</a></li>
                                                                    <li><a href="#">Another action</a></li> 
                                                                    <li><a href="#">Something else here</a></li>
                                                                    <li role="separator" class="divider"></li> <li><a href="#">Separated link</a></li> 
                                                            </ul>
                                                    </li>
                                            </ul>
                                    </div>
                            -->
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
                                                        <b>Type:</b> <?php echo $invoiceData[0]->invoice_type; ?><br>
                                                        <b>Nature:</b> <?php echo $invoiceData[0]->invoice_nature; ?><br>
                                                        <b>Invoice Date:</b> <?php echo $invoiceData[0]->invoice_date; ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>

                                    <?php $company_state_data = $obj_client->getStateDetailByStateId($invoiceData[0]->company_state); ?>
                                    <?php $supply_place_data = $obj_client->getStateDetailByStateId($invoiceData[0]->supply_place); ?>

                                    <tr class="information">
                                        <td colspan="2">
                                            <table>
                                                <tr>
                                                    <td>
                                                        <?php echo $invoiceData[0]->company_name; ?><br>
                                                        <?php echo $invoiceData[0]->company_address; ?><br>
                                                        <?php echo $company_state_data['data']->state_name; ?><br>
                                                        <b>GSTIN:</b> <?php echo $invoiceData[0]->company_gstin_number; ?>
                                                    </td>

                                                    <td>
                                                        <?php if ($invoiceData[0]->invoice_type === "Import Invoice") { ?>

                                                            <b>Import Supply Meant:</b> <?php echo $invoiceData[0]->import_supply_meant; ?><br>
                                                            <?php if ($invoiceData[0]->is_canceled == 1) { ?> <b>Canceled Invoice:</b> <?php echo "Canceled"; ?><br><?php } ?>
                                                            <?php if ($invoiceData[0]->advance_adjustment == 1) { ?> <b>Advance Adjustment:</b> <?php echo "Yes"; ?> <?php } ?>

                                                        <?php } else { ?>

                                                            <b>Supply Type:</b> <?php echo $invoiceData[0]->supply_type; ?><br>
                                                            <?php if (isset($invoiceData[0]->supply_place) && $invoiceData[0]->supply_place > 0) { ?><b>Place Of Supply:</b> <?php echo $supply_place_data['data']->state_name; ?><br> <?php } ?>
                                                            <?php if ($invoiceData[0]->is_canceled == 1) { ?> <b>Canceled Invoice:</b> <?php echo "Canceled"; ?><br> <?php } ?>
                                                            <?php if ($invoiceData[0]->advance_adjustment == 1) { ?> <b>Advance Adjustment:</b> <?php echo "Yes"; ?> <?php } ?>

                                                        <?php } ?>
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
                                                        <?php if ($invoiceData[0]->supplier_billing_company_name) { ?> <?php echo $invoiceData[0]->supplier_billing_company_name; ?><br> <?php } ?>
                                                        <?php echo $invoiceData[0]->supplier_billing_address; ?><br>
                                                        <?php echo $invoiceData[0]->supplier_billing_state_name; ?><br>

                                                        <?php if (intval($invoiceData[0]->supplier_billing_state) == 0) { ?>
                                                            <?php $billing_country_data = $obj_client->getCountryDetailByCountryId($invoiceData[0]->supplier_billing_country); ?>
                                                            <?php echo $billing_country_data['data']->country_name; ?><br>
                                                        <?php } ?>

                                                        <?php if (!empty($invoiceData[0]->supplier_billing_gstin_number)) { ?>
                                                            <b>Supplier GSTIN:</b> <?php echo $invoiceData[0]->supplier_billing_gstin_number; ?>
                                                        <?php } ?>
                                                    </td>

                                                    <td>
                                                        <b>Address Of Recipient / Shipping Detail</b><br>
                                                        <?php echo html_entity_decode($invoiceData[0]->recipient_shipping_name); ?><br>
                                                        <?php if ($invoiceData[0]->recipient_shipping_company_name) { ?> <?php echo $invoiceData[0]->recipient_shipping_company_name; ?><br> <?php } ?>
                                                        <?php echo $invoiceData[0]->recipient_shipping_address; ?><br>
                                                        <?php echo $invoiceData[0]->recipient_shipping_state_name; ?><br>

                                                        <?php if (intval($invoiceData[0]->recipient_shipping_state) == 0) { ?>
                                                            <?php $shipping_country_data = $obj_client->getCountryDetailByCountryId($invoiceData[0]->recipient_shipping_country); ?>
                                                            <?php echo $shipping_country_data['data']->country_name; ?><br>
                                                        <?php } ?>

                                                        <?php if (!empty($invoiceData[0]->recipient_shipping_gstin_number)) { ?>
                                                            <b>Recipient GSTIN:</b> <?php echo $invoiceData[0]->recipient_shipping_gstin_number; ?><br>
                                                        <?php } ?>

                                                        <?php if ($invoiceData[0]->invoice_type === "Import Invoice") { ?>
                                                            <b>Import Bill Number:</b> <?php echo $invoiceData[0]->import_bill_number; ?><br>
                                                            <b>Import Bill Date:</b> <?php echo $invoiceData[0]->import_bill_date; ?>
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
                                                    <td rowspan="2" class="advancecol" <?php
                                                    if ($invoiceData[0]->advance_adjustment == 1) {
                                                        echo 'style="display:table-cell;"';
                                                    }
                                                    ?>>Advance</td>
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
                                                <?php $total_taxable_subtotal = 0.00; ?>
                                                <?php $total_cgst_amount = 0.00; ?>
                                                <?php $total_sgst_amount = 0.00; ?>
                                                <?php $total_igst_amount = 0.00; ?>
                                                <?php $total_cess_amount = 0.00; ?>
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
                                                        <td class="advancecol" <?php
                                                        if ($invoiceData[0]->advance_adjustment == 1) {
                                                            echo 'style="display:table-cell;"';
                                                        }
                                                        ?>><?php echo $invData->advance_amount; ?></td>
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

                                                    <?php $total_taxable_subtotal += $invData->taxable_subtotal; ?>
                                                    <?php $total_cgst_amount += $invData->cgst_amount; ?>
                                                    <?php $total_sgst_amount += $invData->sgst_amount; ?>
                                                    <?php $total_igst_amount += $invData->igst_amount; ?>
                                                    <?php $total_cess_amount += $invData->cess_amount; ?>

                                                    <?php $counter++; ?>
                                                <?php } ?>

                                                <tr class="total">
                                                    <td <?php
                                                    if ($invoiceData[0]->advance_adjustment == 1) {
                                                        echo 'colspan="18"';
                                                    } else {
                                                        echo 'colspan="17"';
                                                    }
                                                    ?>>
                                                        Total Invoice Value (In Figure): <i class="fa fa-inr"></i><?php echo $invoiceData[0]->invoice_total_value; ?>
                                                    </td>
                                                </tr>

                                                <?php $invoice_total_value_words = $obj_client->convert_number_to_words($invoiceData[0]->invoice_total_value); ?>

                                                <tr class="total">
                                                    <td <?php
                                                    if ($invoiceData[0]->advance_adjustment == 1) {
                                                        echo 'colspan="18"';
                                                    } else {
                                                        echo 'colspan="17"';
                                                    }
                                                    ?>>
                                                        Total Invoice Value (In Words): <?php echo ucwords($invoice_total_value_words); ?>
                                                    </td>
                                                </tr>

                                                <?php if ($invoiceData[0]->supply_type === "Reverse Charge") { ?>

                                                    <?php if ($invoiceData[0]->supplier_billing_state === $invoiceData[0]->supply_place) { ?>

                                                        <tr class="lightgreen">
                                                            <td <?php
                                                            if ($invoiceData[0]->advance_adjustment == 1) {
                                                                echo 'colspan="10"';
                                                            } else {
                                                                echo 'colspan="9"';
                                                            }
                                                            ?> align="right" class="fontbold textsmall">Amount of Tax Subject to Reverse Charge</td>
                                                            <td>-</td>
                                                            <td><i class="fa fa-inr"></i><?php echo $total_cgst_amount; ?></td>
                                                            <td>-</td>
                                                            <td><i class="fa fa-inr"></i><?php echo $total_sgst_amount; ?></td>
                                                            <td>-</td>
                                                            <td><i class="fa fa-inr"></i>0.00</td>
                                                            <td>-</td>
                                                            <td><i class="fa fa-inr"></i><?php echo $total_cess_amount; ?></td>
                                                        </tr>

                                                    <?php } else { ?>

                                                        <tr class="lightgreen">
                                                            <td <?php
                                                            if ($invoiceData[0]->advance_adjustment == 1) {
                                                                echo 'colspan="10"';
                                                            } else {
                                                                echo 'colspan="9"';
                                                            }
                                                            ?> align="right" class="fontbold textsmall">Amount of Tax Subject to Reverse Charge</td>
                                                            <td>-</td>
                                                            <td><i class="fa fa-inr"></i>0.00</td>
                                                            <td>-</td>
                                                            <td><i class="fa fa-inr"></i>0.00</td>
                                                            <td>-</td>
                                                            <td><i class="fa fa-inr"></i><?php echo $total_igst_amount; ?></td>
                                                            <td>-</td>
                                                            <td><i class="fa fa-inr"></i><?php echo $total_cess_amount; ?></td>
                                                        </tr>

                                                    <?php } ?>

                                                <?php } ?>

                                            </table>

                                        </td>
                                    </tr>

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

        $(".formboxcontainer").on("click", ".checkAll", function () {

            if ($('[name="purchase_invoice[]"]:checked').length > 0) {
                $(".checkAll").text("Check All");
                $('.purchaseInvoice').prop('checked', false);
            } else {
                $(".checkAll").text("Uncheck All");
                $('.purchaseInvoice').prop('checked', true);
            }
        });

        $(".formboxcontainer").on("click", ".cancelAll", function () {

            var selectedCheckboxes = new Array();
            $('.purchaseInvoice:checkbox:checked').each(function () {
                selectedCheckboxes.push($(this).val());
            });

            if (selectedCheckboxes.length > 0) {

                $.ajax({
                    data: {purchaseInvoiceIds: selectedCheckboxes, action: "cancelSelectedPurchaseInvoice"},
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

        $("#mainTable").on("click", ".purchaseInvoice", function () {

            if ($('[name="purchase_invoice[]"]:checked').length == 0) {
                $(".checkAll").text("Check All");
            }
        });

        $("#mainTable").on("click", ".cancelPurchaseInvoice", function () {

            var dataInvoiceId = $(this).attr("data-invoice-id");
            $.ajax({
                data: {purchaseInvoiceId: dataInvoiceId, action: "cancelPurchaseInvoice"},
                dataType: 'json',
                type: 'post',
                url: "<?php echo PROJECT_URL; ?>/?ajax=purchase_invoice_cancel",
                success: function (response) {

                    if (response.status == "success") {

                        $('a[data-invoice-id=' + dataInvoiceId + ']').addClass("revokePurchaseInvoice");
                        $('a[data-invoice-id=' + dataInvoiceId + ']').removeClass("cancelPurchaseInvoice");
                        $('a[data-invoice-id=' + dataInvoiceId + ']').text("Revoke");
                        jAlert(response.message);
                    } else {
                        jAlert(response.message);
                    }
                }
            });
        });

        $("#mainTable").on("click", ".revokePurchaseInvoice", function () {

            var dataInvoiceId = $(this).attr("data-invoice-id");
            $.ajax({
                data: {purchaseInvoiceId: dataInvoiceId, action: "revokePurchaseInvoice"},
                dataType: 'json',
                type: 'post',
                url: "<?php echo PROJECT_URL; ?>/?ajax=purchase_invoice_cancel",
                success: function (response) {

                    if (response.status == "success") {

                        $('a[data-invoice-id=' + dataInvoiceId + ']').addClass("cancelPurchaseInvoice");
                        $('a[data-invoice-id=' + dataInvoiceId + ']').removeClass("revokePurchaseInvoice");
                        $('a[data-invoice-id=' + dataInvoiceId + ']').text("Cancel");
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
                    "sAjaxSource": "<?php echo PROJECT_URL; ?>/?ajax=client_receipt_voucher_invoice_list",
                    "fnServerParams": function (aoData) {
                    },
                    "iDisplayLength": 6
                });
            }
        };
    }();
</script>