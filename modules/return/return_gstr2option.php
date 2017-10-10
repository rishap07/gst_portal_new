<?php
$obj_client = new client();
$obj_gstr2 = new gstr2();
if(!$obj_gstr2->can_read('returnfile_list'))
{
    $obj_gstr2->setError($obj_gstr2->getValMsg('can_read'));
    $obj_gstr2->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}
$returnmonth = date('Y-m');
if (isset($_POST['returnmonth']) && isset($_POST['source'])) {
    $returnmonth = $_POST['returnmonth'];
	$source = $_POST['source'];
	$obj_client->redirect(PROJECT_URL . "/?page=return_gstr2option&returnmonth=" . $returnmonth."&source=". $source);
    exit();
}
$returnmonth = date('Y-m');
if ((isset($_REQUEST['returnmonth']) && $_REQUEST['returnmonth'] != '')
&& (isset($_REQUEST['source']) && $_REQUEST['source'] != '')) {
    $returnmonth = $_REQUEST['returnmonth'];
	 $source = $_REQUEST['source'];
    if ($obj_gstr2->startGstr2()) {
        
    }
} else {

    $dataQuery = "SELECT DATE_FORMAT(invoice_date,'%Y-%m') AS niceDate FROM gst_client_invoice group by nicedate ORDER by invoice_date desc LIMIT 0,1";
    $dataRes = $obj_client->get_results($dataQuery);
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
// $b2bquery = "select * from " . $obj_client->getTableName('client_invoice') . " where invoice_nature='salesinvoice' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0' and billing_gstin_number!='' and invoice_date like '%" . $returnmonth . "%'";
$b2bquery = "SELECT sum(item.taxable_subtotal) as subtotal,count(item.purchase_invoice_id) as totalinvoice, sum(item.cgst_amount) as cgst_amount,sum(item.sgst_amount) as sgst_amount,sum(igst_amount) as igst_amount,sum(cess_amount) as cess_amount from " . $obj_client->getTableName('client_purchase_invoice') . " as p  inner join " . $obj_client->getTableName('client_purchase_invoice_item') . "   as item on p.purchase_invoice_id = item.purchase_invoice_id WHERE p.invoice_nature='purchaseinvoice'  and p.added_by='" . $_SESSION["user_detail"]["user_id"] . "' and p.is_canceled='0' and supplier_billing_gstin_number!='' and p.invoice_date like '%" . $returnmonth . "%'";
$b2bData = $db_obj->get_results($b2bquery);
$unregister_purchase_query = "SELECT sum(item.taxable_subtotal) as subtotal,count(item.purchase_invoice_id) as totalinvoice, sum(item.cgst_amount) as cgst_amount,sum(item.sgst_amount) as sgst_amount,sum(igst_amount) as igst_amount,sum(cess_amount) as cess_amount from " . $obj_client->getTableName('client_purchase_invoice') . " as p  inner join " . $obj_client->getTableName('client_purchase_invoice_item') . "   as item on p.purchase_invoice_id = item.purchase_invoice_id WHERE p.invoice_nature='purchaseinvoice'  and p.added_by='" . $_SESSION["user_detail"]["user_id"] . "' and p.is_canceled='0' and supplier_billing_gstin_number='' and supply_type='reversecharge' and p.invoice_date like '%" . $returnmonth . "%'";
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
          <label>Source</label>
          <select class="dateselectbox" id="source" name="source">
            <option value="manual" <?php if (isset($source) && $source=='manual') {echo 'selected';} ?>>Manual</option>
            <option value="import" <?php if (isset($source) && $source=='import') {echo 'selected';} ?>>Import</option>
          </select>
          Month Of Return
          <?php
				$dataQuery = "SELECT DATE_FORMAT(invoice_date,'%Y-%m') AS niceDate FROM gst_client_invoice group by nicedate";
				$dataRes = $obj_client->get_results($dataQuery);
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
      <?php 
		if(isset($_REQUEST['source']) && $_REQUEST['source']=='import')
		{?>
      <form name="form2" method="post" id="form2">
        <div class="row">
        
          <div class="col-sm-4">
           
            <input type="file" name='importinvoice' class="form-control"/>
          </div>
          <div class="col-sm-4">
             <input type="submit" name='submitinvoice' class="btn btn-block btn-success"/>
          </div>
        </div>
      </form>
      <?php }?>
    </div>
  </div>
  <div class="clear height40"></div>
</div>
<div class="clear"></div>
<script>
    $(document).ready(function () {
        $('#returnmonth,#source').on('change', function () {
			<?php if(isset($_REQUEST['source']) &&  $_REQUEST['source']!=''){$option='&source='.$source.'';} ?>
            document.form1.action = '<?php echo PROJECT_URL; ?>/?page=return_gstr2option&returnmonth=<?php echo $returnmonth.$option; ?>';
                        document.form1.submit();
                    });
                });
</script> 
