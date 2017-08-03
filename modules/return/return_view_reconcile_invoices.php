<?php
$obj_gstr2 = new gstr2();
$dataCurrentUserArr = $obj_gstr2->getUserDetailsById($obj_gstr2->sanitize($_SESSION['user_detail']['user_id']));
$returnmonth = date('Y-m');
if (isset($_REQUEST['returnmonth']) && $_REQUEST['returnmonth'] != '') {
    $returnmonth = $_REQUEST['returnmonth'];
}
if (isset($_REQUEST['ids']) && $_REQUEST['ids'] != '') {
    $ids = $_REQUEST['ids'];
}
if (isset($_REQUEST['matchId']) && $_REQUEST['matchId'] != '') {
    $matchId = $_REQUEST['matchId'];
    $ids = explode(",", $matchId);
    $ttl_rec = count($ids);
    $action = "matched";
}
if (isset($_REQUEST['mismatchId']) && $_REQUEST['mismatchId'] != '') {
    $mismatchId = $_REQUEST['mismatchId'];
    $ids = explode(",", $mismatchId);
    $ttl_rec = count($ids);
    $action = "mismatched";
}
if (isset($_REQUEST['missingId']) && $_REQUEST['missingId'] != '') {
    $missingId = $_REQUEST['missingId'];
    $ids = explode(",", $missingId);
    $ttl_rec = count($ids);
    $action = "missing";
}
if (isset($_REQUEST['additionalId']) && $_REQUEST['additionalId'] != '') {
    $additionalId = $_REQUEST['additionalId'];
    $ids = explode(",", $additionalId);
    $ttl_rec = count($ids);
    $action = "additional";
}
switch ($action) {
    case "matched":
?>
       	<div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Reconciliation</h1></div>
                     <div class="whitebg formboxcontainer">
                 
                    <!--/row-->    
                    <!--/col-12-->
<div class="col-md-6 col-sm-6 col-xs-6 padleft0">Showing 1-<?php echo "$ttl_rec of $ttl_rec" ?> </div>  
<div class="col-md-6 col-sm-6 col-xs-6 text-right padrgtnone"><select class="selectbox"><option>Records 10</option></select></div>                
<div class="clear height20"></div>
                     
 <div class="tableresponsive" style="overflow-x:scroll;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="4" class="table table-striped  tablecontent" >
       <tr>
    <th class="active">Inovice Detail</th>
    <th class="active">GSTIN/UID</th>
    <th class="active">Invoice Value</th>
    <th class="active">Taxable Value</th>
    <th class="active">Tax Amount</th>
    <th class="active" width="16%">Status</th>
  </tr>               
                    <?php
        foreach ($ids as $id) {
            $query = "select 
							ci.purchase_invoice_id, 
							ci.reference_number, 
							ci.serial_number, 
							ci.invoice_type, 
							ci.invoice_nature,
							ci.company_gstin_number, 
							ci.supply_type, 
							ci.import_supply_meant, 
							ci.invoice_date, 
							ci.supply_place, 
							ci.supplier_billing_gstin_number, 
							ci.recipient_shipping_gstin_number, 
							ci.invoice_total_value, 
							ci.financial_year, 
							sum(cii.taxable_subtotal) as total_taxable_subtotal, 
							sum(cii.cgst_amount) as total_cgst_amount, 
							sum(cii.sgst_amount) as total_sgst_amount, 
							sum(cii.igst_amount) as total_igst_amount, 
							sum(cii.cess_amount) as total_cess_amount 
							from " . $obj_gstr2->getTableName('client_purchase_invoice') . " as ci INNER JOIN " . $obj_gstr2->getTableName("client_purchase_invoice_item") . " as cii ON ci.purchase_invoice_id = cii.purchase_invoice_id
							where 
							ci.purchase_invoice_id=" . $id;
            $invoideData = $obj_gstr2->get_results($query);
            $invoideData = $invoideData[0];
            $taxAmt = $invoideData->total_igst_amount + $invoideData->total_cgst_amount + $invoideData->total_sgst_amount + $invoideData->total_cess_amount;
?>

	   <tr>
    <td class="boldfont"><?php echo $invoideData->reference_number ?><br/><span class="table-date-txt"><?php echo $invoideData->invoice_date ?></span></td>
    <td><?php echo $invoideData->company_gstin_number ?></td>
    <td><?php echo $invoideData->invoice_total_value ?></td>
    <td><?php echo $invoideData->total_taxable_subtotal ?></td>
    <td><?php echo $taxAmt ?></td> 
    <td><a href="#" class="btnaccepted">Accepted</a></td> 
     <?php
        }
?>

</table>
</div>
      <?php
        break;
    case "mismatched":
?>
       	<div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Reconciliation</h1></div>
                     <div class="whitebg formboxcontainer">
                 
                    <!--/row-->    
                    <!--/col-12-->
<div class="col-md-6 col-sm-6 col-xs-6 padleft0">Showing 1-<?php echo "$ttl_rec of $ttl_rec" ?> </div>  
<div class="col-md-6 col-sm-6 col-xs-6 text-right padrgtnone"><select class="selectbox"><option>Records 10</option></select></div>                
<div class="clear height20"></div>
                     
 <div class="tableresponsive" style="overflow-x:scroll;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="4" class="table table-striped  tablecontent" >
       <tr>
    <th class="active">Inovice Detail</th>
    <th class="active">GSTIN/UID</th>
    <th class="active">Invoice Value</th>
    <th class="active">Taxable Value</th>
    <th class="active">Tax Amount</th>
    <th class="active" width="16%" id="status">Status</th>
    <th class="active" width="16%">Action</th> 
  </tr>               
                    <?php
        foreach ($ids as $id) {
            $query = "select 
							ci.purchase_invoice_id, 
							ci.reference_number, 
							ci.serial_number, 
							ci.invoice_type, 
							ci.invoice_nature,
							ci.company_gstin_number, 
							ci.supply_type, 
							ci.import_supply_meant, 
							ci.invoice_date, 
							ci.supply_place, 
							ci.supplier_billing_gstin_number, 
							ci.recipient_shipping_gstin_number, 
							ci.invoice_total_value, 
							ci.financial_year, 
							sum(cii.taxable_subtotal) as total_taxable_subtotal, 
							sum(cii.cgst_amount) as total_cgst_amount, 
							sum(cii.sgst_amount) as total_sgst_amount, 
							sum(cii.igst_amount) as total_igst_amount, 
							sum(cii.cess_amount) as total_cess_amount 
							from " . $obj_gstr2->getTableName('client_purchase_invoice') . " as ci INNER JOIN " . $obj_gstr2->getTableName("client_purchase_invoice_item") . " as cii ON ci.purchase_invoice_id = cii.purchase_invoice_id
							where 
							ci.purchase_invoice_id=" . $id;
            $invoideData = $obj_gstr2->get_results($query);
            $invoideData = $invoideData[0];
            $statusQuery = "select *
					from " . $obj_gstr2->getTableName('client_reconcile_purchase_invoice1') . " where
					reference_number='" . $invoideData->reference_number . "'";
            $statusData = $obj_gstr2->get_results($statusQuery);
            $taxAmt = $invoideData->total_igst_amount + $invoideData->total_cgst_amount + $invoideData->total_sgst_amount + $invoideData->total_cess_amount;
            $action = isset($statusData[0]->status) ? $statusData[0]->status : '';
            switch ($action) {
                case "2":
                    $status = "update";
                break;
                case "3":
                    $status = "reject";
                break;
                case "4":
                    $status = "pending";
                break;
                default:
                    $status = "--";
                break;
            }
?>

	   <tr>
    <td class="boldfont"><?php echo $invoideData->reference_number ?><br/><span class="table-date-txt"><?php echo $invoideData->invoice_date ?></span></td>
    <td><?php echo $invoideData->company_gstin_number ?></td>
    <td><?php echo $invoideData->invoice_total_value ?></td>
    <td><?php echo $invoideData->total_taxable_subtotal ?></td>
    <td><?php echo $taxAmt ?></td> 
    <td><div class="update_div"><?php echo $status; ?></div></td> 
    <td><div  class="dropdown">
    <a href="#" class="dropdown-toggle" id="drop4" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> Action <span class="caret"></span> </a>
    <ul class="dropdown-menu" id="menu1" aria-labelledby="drop4">
     <li><a href="#" class="gstr2" data-bind="update" data-id=<?php echo $invoideData->purchase_invoice_id ?>>Update GSTR2 File</a></li>
     <li><a href="#" class="gstr2" data-bind="reject" data-id=<?php echo $invoideData->purchase_invoice_id ?>>Reject</a></li>
     <li><a href="#" class="gstr2" data-bind="pending" data-id=<?php echo $invoideData->purchase_invoice_id ?>>Pending</a></li>
      </ul>
   </div></td> 
   </tr>
     <?php
        }
?>
 <script>
	$(document).ready(function () {
		$('.gstr2').on('click', function () {
			$(this).closest('tr').find('.update_div').html($(this).attr('data-bind'));
			$('#loading').show();
			$.ajax({
                data: {invoiceId:$(this).attr('data-id'),returnmonth:<?php echo $returnmonth ?>,status:$(this).attr('data-bind'),case:'mismatched'},
                dataType: 'json',
                type: 'post',
                url: "<?php echo PROJECT_URL; ?>/?ajax=return_reconcile_purchase_invoice",
                success: function(response){
                 $('#loading').hide();
                }
            });

		});
	});
</script>
</table>
</div>
      <?php
        break;
    case "missing":
?>
       	<div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Reconciliation</h1></div>
                     <div class="whitebg formboxcontainer">
                 
                    <!--/row-->    
                    <!--/col-12-->
<div class="col-md-6 col-sm-6 col-xs-6 padleft0">Showing 1-<?php echo "$ttl_rec of $ttl_rec" ?> </div>  
<div class="col-md-6 col-sm-6 col-xs-6 text-right padrgtnone"><select class="selectbox"><option>Records 10</option></select></div>                
<div class="clear height20"></div>
                     
 <div class="tableresponsive" style="overflow-x:scroll;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="4" class="table table-striped  tablecontent" >
       <tr>
    <th class="active">Inovice Detail</th>
    <th class="active">GSTIN/UID</th>
    <th class="active">Invoice Value</th>
    <th class="active">Taxable Value</th>
    <th class="active">Tax Amount</th>
    <th class="active" width="16%" id="status">Status</th>
    <th class="active" width="16%">Action</th> 
  </tr>               
                    <?php
        foreach ($ids as $id) {
            $query = "select i.invoice_id, 
					i.reference_number, 
					i.serial_number, 
					i.invoice_type, 
					i.gstin_number as company_gstin_number, 
					i.supply_type, 
					i.export_supply_meant, 
					i.invoice_date, 
					i.supply_place, 
					i.billing_gstin_number, 
					i.shipping_gstin_number, 
					i.invoice_total_value, 
					i.financial_year, 
					sum(it.taxable_subtotal) as total_taxable_subtotal, 
					sum(it.cgst_amount) as total_cgst_amount, 
					sum(it.sgst_amount) as total_sgst_amount, 
					sum(it.igst_amount) as total_igst_amount, 
					sum(it.cess_amount) as total_cess_amount 
					from " . $obj_gstr2->getTableName('client_invoice') . " i inner join " . $obj_gstr2->getTableName("client_invoice_item") . " it on i.invoice_id=it.invoice_id 
					where 
					i.invoice_id=" . $id;
            $invoideData = $obj_gstr2->get_results($query);
            $invoideData = $invoideData[0];
            $statusQuery = "select *
					from " . $obj_gstr2->getTableName('client_reconcile_purchase_invoice1') . " where
					reference_number='" . $invoideData->reference_number . "'";
            $statusData = $obj_gstr2->get_results($statusQuery);
            $taxAmt = $invoideData->total_igst_amount + $invoideData->total_cgst_amount + $invoideData->total_sgst_amount + $invoideData->total_cess_amount;
            $action = isset($statusData[0]->status) ? $statusData[0]->status : '';
            switch ($action) {
                case "2":
                    $status = "update";
                break;
                case "3":
                    $status = "reject";
                break;
                case "4":
                    $status = "pending";
                break;
                default:
                    $status = "--";
                break;
            }
?>

	   <tr>
    <td class="boldfont"><?php echo $invoideData->reference_number ?><br/><span class="table-date-txt"><?php echo $invoideData->invoice_date ?></span></td>
    <td><?php echo $invoideData->company_gstin_number ?></td>
    <td><?php echo $invoideData->invoice_total_value ?></td>
    <td><?php echo $invoideData->total_taxable_subtotal ?></td>
    <td><?php echo $taxAmt ?></td> 
    <td><div class="update_div"><?php echo $status; ?></div></td> 
    <td><div  class="dropdown">
    <a href="#" class="dropdown-toggle" id="drop4" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> Action <span class="caret"></span> </a>
    <ul class="dropdown-menu" id="menu1" aria-labelledby="drop4">
     <li><a href="#" class="gstr2" data-bind="update" data-id=<?php echo $invoideData->invoice_id ?>>Update GSTR2 File</a></li>
     <li><a href="#" class="gstr2" data-bind="reject" data-id=<?php echo $invoideData->invoice_id ?>>Reject</a></li>
     <li><a href="#" class="gstr2" data-bind="pending" data-id=<?php echo $invoideData->invoice_id ?>>Pending</a></li>
      </ul>
   </div></td> 
   </tr>
  
     <?php
        }
?>
 <script>
	$(document).ready(function () {
		$('.gstr2').on('click', function () {
			$(this).closest('tr').find('.update_div').html($(this).attr('data-bind'));
			$('#loading').show();
			$.ajax({
                data: {invoiceId:$(this).attr('data-id'),returnmonth:<?php echo $returnmonth ?>,status:$(this).attr('data-bind'),case:'missing'},
                dataType: 'json',
                type: 'post',
                url: "<?php echo PROJECT_URL; ?>/?ajax=return_reconcile_purchase_invoice",
                success: function(response){
                	//console.log("response");
                   $('#loading').hide();
                }
            });

		});
	});
</script>
</table>
</div>
      <?php
        break;
    case "additional":
?>
       	<div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Reconciliation</h1></div>
                     <div class="whitebg formboxcontainer">
                 
                    <!--/row-->    
                    <!--/col-12-->
<div class="col-md-6 col-sm-6 col-xs-6 padleft0">Showing 1-<?php echo "$ttl_rec of $ttl_rec" ?> </div>  
<div class="col-md-6 col-sm-6 col-xs-6 text-right padrgtnone"><select class="selectbox"><option>Records 10</option></select></div>                
<div class="clear height20"></div>
                     
 <div class="tableresponsive" style="overflow-x:scroll;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="4" class="table table-striped  tablecontent" >
       <tr>
    <th class="active">Inovice Detail</th>
    <th class="active">GSTIN/UID</th>
    <th class="active">Invoice Value</th>
    <th class="active">Taxable Value</th>
    <th class="active">Tax Amount</th>
    <th class="active" width="16%" id="status">Status</th>
    <th class="active" width="16%">Action</th> 
  </tr>               
                    <?php
        foreach ($ids as $id) {
            $query = "select 
							ci.purchase_invoice_id, 
							ci.reference_number, 
							ci.serial_number, 
							ci.invoice_type, 
							ci.invoice_nature,
							ci.company_gstin_number, 
							ci.supply_type, 
							ci.import_supply_meant, 
							ci.invoice_date, 
							ci.supply_place, 
							ci.supplier_billing_gstin_number, 
							ci.recipient_shipping_gstin_number, 
							ci.invoice_total_value, 
							ci.financial_year, 
							sum(cii.taxable_subtotal) as total_taxable_subtotal, 
							sum(cii.cgst_amount) as total_cgst_amount, 
							sum(cii.sgst_amount) as total_sgst_amount, 
							sum(cii.igst_amount) as total_igst_amount, 
							sum(cii.cess_amount) as total_cess_amount 
							from " . $obj_gstr2->getTableName('client_purchase_invoice') . " as ci INNER JOIN " . $obj_gstr2->getTableName("client_purchase_invoice_item") . " as cii ON ci.purchase_invoice_id = cii.purchase_invoice_id
							where 
							ci.purchase_invoice_id=" . $id;
            $invoideData = $obj_gstr2->get_results($query);
            $invoideData = $invoideData[0];
            //echo $query;
            //$obj_gstr2->pr($invoideData);die;
            $taxAmt = $invoideData->total_igst_amount + $invoideData->total_cgst_amount + $invoideData->total_sgst_amount + $invoideData->total_cess_amount;
            $statusQuery = "select *
					from " . $obj_gstr2->getTableName('client_reconcile_purchase_invoice1') . " where
					reference_number='" . $invoideData->reference_number . "'";
            $statusData = $obj_gstr2->get_results($statusQuery);
            $taxAmt = $invoideData->total_igst_amount + $invoideData->total_cgst_amount + $invoideData->total_sgst_amount + $invoideData->total_cess_amount;
            $action = isset($statusData[0]->status) ? $statusData[0]->status : '';
            switch ($action) {
                case "2":
                    $status = "update";
                break;
                case "3":
                    $status = "reject";
                break;
                case "4":
                    $status = "pending";
                break;
                default:
                    $status = "--";
                break;
            }
?>

	   <tr>
    <td class="boldfont"><?php echo $invoideData->reference_number ?><br/><span class="table-date-txt"><?php echo $invoideData->invoice_date ?></span></td>
    <td><?php echo $invoideData->company_gstin_number ?></td>
    <td><?php echo $invoideData->invoice_total_value ?></td>
    <td><?php echo $invoideData->total_taxable_subtotal ?></td>
    <td><?php echo $taxAmt ?></td> 
    <td><div class="update_div"><?php echo $status; ?></div></td> 
    <td><div  class="dropdown">
    <a href="#" class="dropdown-toggle" id="drop4" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> Action <span class="caret"></span> </a>
    <ul class="dropdown-menu" id="menu1" aria-labelledby="drop4">
     <li><a href="#" class="gstr2" data-bind="update" data-id=<?php echo $invoideData->purchase_invoice_id ?>>Update GSTR2 File</a></li>
     <li><a href="#" class="gstr2" data-bind="reject" data-id=<?php echo $invoideData->purchase_invoice_id ?>>Reject</a></li>
     <li><a href="#" class="gstr2" data-bind="pending" data-id=<?php echo $invoideData->purchase_invoice_id ?>>Pending</a></li>
      </ul>
   </div></td> 
   </tr>
     <?php
        }
?>
 <script>
	$(document).ready(function () {
		$('.gstr2').on('click', function () {
			$(this).closest('tr').find('.update_div').html($(this).attr('data-bind'));
			$('#loading').show();
			$.ajax({
                data: {invoiceId:$(this).attr('data-id'),returnmonth:<?php echo $returnmonth ?>,status:$(this).attr('data-bind'),case:'additional'},
                dataType: 'json',
                type: 'post',
                url: "<?php echo PROJECT_URL; ?>/?ajax=return_reconcile_purchase_invoice",
                success: function(response){
                $('#loading').hide();
                }
            });

		});
	});
</script>
</table>
</div>
      <?php
        break;
    }
?>
