<?php
$obj_gstr2 = new gstr2();
$dataCurrentUserArr = $obj_gstr2->getUserDetailsById($obj_gstr2->sanitize($_SESSION['user_detail']['user_id']));
$returnmonth = date('Y-m');
if (isset($_REQUEST['returnmonth']) && $_REQUEST['returnmonth'] != '') {
    $returnmonth = $_REQUEST['returnmonth'];
        $startdate=$returnmonth."-10";
    $enddate=$returnmonth."-18";
}
if (isset($_REQUEST['action']) && $_REQUEST['action'] != '') {
    $action = $_REQUEST['action'];
}

switch ($action) {
    case "missing":
            $query = "select 
                            ci.reference_number, 
                            ci.invoice_date, 
                            ci.company_gstin_number, 
                            ci.invoice_total_value, 
                            ci.total_taxable_subtotal,
                            ci.company_gstin_number ,
                            ci.total_cgst_amount, 
                            ci.total_sgst_amount, 
                            ci.total_igst_amount, 
                            ci.total_cess_amount,
                            ci.is_uploaded,
                            ci.status 
                            from " . $obj_gstr2->getTableName('client_reconcile_purchase_invoice1') . " as ci
                            where 
                             ci.invoice_date BETWEEN '".$startdate."' AND  '".$enddate."'      AND ci.invoice_status='1'";

            $invoideData = $obj_gstr2->get_results($query);
         
?>
    <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Reconciliation</h1></div>
                     <div class="whitebg formboxcontainer">
                 
                    <!--/row-->    
                    <!--/col-12-->
<div class="col-md-6 col-sm-6 col-xs-6 padleft0">Showing 1-<?php echo "" ?> </div>  
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
        foreach ($invoideData as $invoideData) {
           // print_r($invoideData);
$taxAmt = $invoideData->total_igst_amount + $invoideData->total_cgst_amount + $invoideData->total_sgst_amount + $invoideData->total_cess_amount;
            $action = isset($invoideData->status) ? $invoideData->status : '';
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
     <?php if($invoideData->is_uploaded==0) { ?>
    <a href="#" class="dropdown-toggle" id="drop4" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> Action <span class="caret"></span> </a>
    <ul class="dropdown-menu" id="menu1" aria-labelledby="drop4">
    
     <li><a href="#" class="gstr2" data-bind="update" data-id=<?php echo $invoideData->reference_number ?>>Update GSTR2 File</a></li>
     <li><a href="#" class="gstr2" data-bind="reject" data-id=<?php echo $invoideData->reference_number ?>>Reject</a></li>
     <li><a href="#" class="gstr2" data-bind="pending" data-id=<?php echo $invoideData->reference_number ?>>Pending</a></li>
    <?php } else{  ?>
       <div class="alert-success"">Invoice Uploaded</div>

      <?php }?>
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




            case "match":
            $query = "select 
                            ci.reference_number, 
                            ci.invoice_date, 
                            ci.company_gstin_number, 
                            ci.invoice_total_value, 
                            ci.total_taxable_subtotal,
                            ci.company_gstin_number ,
                            ci.total_cgst_amount, 
                            ci.total_sgst_amount, 
                            ci.total_igst_amount, 
                            ci.total_cess_amount,
                            ci.is_uploaded,
                            ci.status 
                            from " . $obj_gstr2->getTableName('client_reconcile_purchase_invoice1') . " as ci
                            where 
                             ci.invoice_date BETWEEN '".$startdate."' AND  '".$enddate."'
                        AND ci.invoice_status='0'";
  
            $invoideData = $obj_gstr2->get_results($query);
         
?>
    <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Reconciliation</h1></div>
                     <div class="whitebg formboxcontainer">
                 
                    <!--/row-->    
                    <!--/col-12-->
<div class="col-md-6 col-sm-6 col-xs-6 padleft0">Showing 1-<?php echo "" ?> </div>  
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
    </tr>               
  <?php
        foreach ($invoideData as $invoideData) {
           // print_r($invoideData);
$taxAmt = $invoideData->total_igst_amount + $invoideData->total_cgst_amount + $invoideData->total_sgst_amount + $invoideData->total_cess_amount;
            $action = isset($invoideData->status) ? $invoideData->status : '';
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
    <td><a href="#" class="btnaccepted">Accepted</a></td>   </tr>
  
     <?php
        }
?>
 <script>
    $(document).ready(function () {
        $('.gstr2').on('click', function () {
            $(this).closest('tr').find('.update_div').html($(this).attr('data-bind'));
            $('#loading').show();
            $.ajax({
                data: {invoiceId:$(this).attr('data-id'),returnmonth:<?php echo $returnmonth ?>,status:$(this).attr('data-bind'),case:'match'},
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

            case "mismatch":
            $query = "select 
                            ci.reference_number, 
                            ci.invoice_date, 
                            ci.company_gstin_number, 
                            ci.invoice_total_value, 
                            ci.total_taxable_subtotal,
                            ci.company_gstin_number ,
                            ci.total_cgst_amount, 
                            ci.total_sgst_amount, 
                            ci.total_igst_amount, 
                            ci.total_cess_amount,
                            ci.is_uploaded,
                            ci.status 
                            from " . $obj_gstr2->getTableName('client_reconcile_purchase_invoice1') . " as ci
                            where 
                             ci.invoice_date BETWEEN '".$startdate."' AND  '".$enddate."'
                        AND ci.invoice_status='3'";
                        
            $invoideData = $obj_gstr2->get_results($query);
         
?>
    <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Reconciliation</h1></div>
                     <div class="whitebg formboxcontainer">
                 
                    <!--/row-->    
                    <!--/col-12-->
<div class="col-md-6 col-sm-6 col-xs-6 padleft0">Showing 1-<?php echo "" ?> </div>  
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
        foreach ($invoideData as $invoideData) {
           // print_r($invoideData);
$taxAmt = $invoideData->total_igst_amount + $invoideData->total_cgst_amount + $invoideData->total_sgst_amount + $invoideData->total_cess_amount;
            $action = isset($invoideData->status) ? $invoideData->status : '';
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
     <?php if($invoideData->is_uploaded==0) { ?>
    <a href="#" class="dropdown-toggle" id="drop4" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> Action <span class="caret"></span> </a>
    <ul class="dropdown-menu" id="menu1" aria-labelledby="drop4">
    
     <li><a href="#" class="gstr2" data-bind="update" data-id=<?php echo $invoideData->reference_number ?>>Update GSTR2 File</a></li>
     <li><a href="#" class="gstr2" data-bind="reject" data-id=<?php echo $invoideData->reference_number ?>>Reject</a></li>
     <li><a href="#" class="gstr2" data-bind="pending" data-id=<?php echo $invoideData->reference_number ?>>Pending</a></li>
    <?php } else{  ?>
       <div class="alert-success"">Invoice Uploaded</div>

      <?php }?>
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
                data: {invoiceId:$(this).attr('data-id'),returnmonth:<?php echo $returnmonth ?>,status:$(this).attr('data-bind'),case:'mismatch'},
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
            $query = "select 
                            ci.reference_number, 
                            ci.invoice_date, 
                            ci.company_gstin_number, 
                            ci.invoice_total_value, 
                            ci.total_taxable_subtotal,
                            ci.company_gstin_number ,
                            ci.total_cgst_amount, 
                            ci.total_sgst_amount, 
                            ci.total_igst_amount, 
                            ci.total_cess_amount,
                            ci.is_uploaded,
                            ci.status 
                            from " . $obj_gstr2->getTableName('client_reconcile_purchase_invoice1') . " as ci
                            where 
                             ci.invoice_date BETWEEN '".$startdate."' AND  '".$enddate."'
                        AND ci.invoice_status='2'";
                 
            $invoideData = $obj_gstr2->get_results($query);
         
?>
    <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Reconciliation</h1></div>
                     <div class="whitebg formboxcontainer">
                 
                    <!--/row-->    
                    <!--/col-12-->
<div class="col-md-6 col-sm-6 col-xs-6 padleft0">Showing 1-<?php echo "" ?> </div>  
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
        foreach ($invoideData as $invoideData) {
           // print_r($invoideData);
$taxAmt = $invoideData->total_igst_amount + $invoideData->total_cgst_amount + $invoideData->total_sgst_amount + $invoideData->total_cess_amount;
            $action = isset($invoideData->status) ? $invoideData->status : '';
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
     <?php if($invoideData->is_uploaded==0) { ?>
    <a href="#" class="dropdown-toggle" id="drop4" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> Action <span class="caret"></span> </a>
    <ul class="dropdown-menu" id="menu1" aria-labelledby="drop4">
    
     <li><a href="#" class="gstr2" data-bind="update" data-id=<?php echo $invoideData->reference_number ?>>Update GSTR2 File</a></li>
     <li><a href="#" class="gstr2" data-bind="reject" data-id=<?php echo $invoideData->reference_number ?>>Reject</a></li>
     <li><a href="#" class="gstr2" data-bind="pending" data-id=<?php echo $invoideData->reference_number ?>>Pending</a></li>
    <?php } else{  ?>
       <div class="alert-success"">Invoice Uploaded</div>

      <?php }?>
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

}





