<?php
$obj_client = new client();
$obj_common= new common();
$financialYear=$obj_common->generateFinancialYear();
$dataTotalYears;
$dataTotalMonths;
$dataTotalsDue;
$month = date('m');
$start_year = date('Y');
$end_year = $start_year + 1;
// $year = $start_year."-".$end_year;
$year = $db_obj->generateFinancialYear();
$dataInvs = $db_obj->get_results('select * from ' . $db_obj->getTableName('client_purchase_invoice') . " where invoice_nature='purchaseinvoice'  and added_by='" . $_SESSION["user_detail"]["user_id"] . "' and is_canceled='0' and is_deleted='0' and financial_year ='" . $year . "' order by purchase_invoice_id desc limit 0,5");
/* code for total invoices created in current financial_year */
$dataTotalinvoices = $db_obj->get_results("select COUNT(purchase_invoice_id) as invoicecount,month(invoice_date) as month from " . $db_obj->getTableName('client_purchase_invoice') . " WHERE invoice_nature='purchaseinvoice'  and added_by='" . $_SESSION["user_detail"]["user_id"] . "' and is_canceled='0' and is_deleted='0' and financial_year ='" . $year . "'
 GROUP by month(invoice_date) desc limit 0,5 ");
 
/* code for total month sale */
$dataTotalMonthSales = $db_obj->get_results("select count(purchase_invoice_id) as monthcount, sum(invoice_total_value) as totalsale,month(invoice_date) as month from " . $db_obj->getTableName('client_purchase_invoice') . " WHERE invoice_nature='purchaseinvoice'  and added_by='" . $_SESSION["user_detail"]["user_id"] . "' and is_canceled='0' and is_deleted='0' and financial_year ='" . $year . "'

  GROUP by month(invoice_date) desc limit 0,5 ");
  
if (isset($_POST['submit']) && $_POST['submit'] == 'Filter') {

    $from_date = isset($_POST['from_date']) ? $_POST['from_date'] : '';
    $to_date = isset($_POST['to_date']) ? $_POST['to_date'] : '';

    if ($from_date < $to_date) {
        // $obj_client->setError('Start date can not be less than to date');
    }
   /* code for current month totalsale */
    $dataTotalMonths = $db_obj->get_results('select COUNT(purchase_invoice_id) as numcount, sum(invoice_total_value) as sum from ' . $db_obj->getTableName('client_purchase_invoice') . " WHERE invoice_nature='purchaseinvoice'  and added_by='" . $_SESSION["user_detail"]["user_id"] . "' and is_canceled='0' and is_deleted='0' and financial_year ='" . $year . "' and invoice_date between '" . $from_date . "' and '" . $to_date . "'");
    $query = 'select COUNT(purchase_invoice_id) as numcount, sum(invoice_total_value) as sum from ' . $db_obj->getTableName('client_purchase_invoice') . " WHERE invoice_nature='purchaseinvoice'  and added_by='" . $_SESSION["user_detail"]["user_id"] . "' and is_canceled='0' and is_deleted='0'";
    if ($from_date != '') {
        $query.="and invoice_date >= '" . $from_date . " 00:00:00'";
    }
    if ($to_date != '') {
        $query.="and invoice_date <= '" . $to_date . " 23:59:59'";
    }
    $dataTotalMonths = $db_obj->get_results($query);
    $query = "SELECT COUNT(i.purchase_invoice_id) as numcount,sum(item.cgst_amount) as cgst_amount,sum(item.sgst_amount) as sgst_amount,sum(igst_amount) as igst_amount,sum(cess_amount) as cess_amount FROM " . $db_obj->getTableName('client_purchase_invoice') . " as i inner join " . $db_obj->getTableName('client_purchase_invoice_item') . " as item on item.purchase_invoice_id = i.purchase_invoice_id WHERE i.invoice_nature='purchaseinvoice'  and i.added_by='" . $_SESSION["user_detail"]["user_id"] . "' and i.is_canceled='0' and i.is_deleted='0'";
    if ($from_date != '') {
        $query.="and invoice_date >= '" . $from_date . " 00:00:00'";
    }
    if ($to_date != '') {
        $query.="and invoice_date <= '" . $to_date . " 23:59:59'";
    }
    $dataTotalsDue = $db_obj->get_results($query);
	//
	/* code for current month totalsale */
    $query = 'select COUNT(invoice_id) as numcount, sum(invoice_total_value) as sum from ' . $db_obj->getTableName('client_invoice') . " WHERE invoice_nature='salesinvoice'  and added_by='" . $_SESSION["user_detail"]["user_id"] . "' and is_canceled='0' and is_deleted='0'";
    if ($from_date != '') {
        $query.="and invoice_date >= '" . $from_date . " 00:00:00'";
    }
    if ($to_date != '') {
        $query.="and invoice_date <= '" . $to_date . " 23:59:59'";
    }
	
    $dataTotalMonthsSale = $db_obj->get_results($query);
    $query = "SELECT COUNT(i.invoice_id) as numcount,sum(item.cgst_amount) as cgst_amount,sum(item.sgst_amount) as sgst_amount,sum(igst_amount) as igst_amount,sum(cess_amount) as cess_amount FROM " . $db_obj->getTableName('client_invoice') . " as i inner join " . $db_obj->getTableName('client_invoice_item') . " as item on item.invoice_id = i.invoice_id WHERE i.invoice_nature='salesinvoice'  and i.added_by='" . $_SESSION["user_detail"]["user_id"] . "' and i.is_canceled='0' and i.is_deleted='0'";
    if ($from_date != '') {
        $query.="and invoice_date >= '" . $from_date . " 00:00:00'";
    }
    if ($to_date != '') {
        $query.="and invoice_date <= '" . $to_date . " 23:59:59'";
    }
    $dataTotalsSale = $db_obj->get_results($query);
	//
	
} else {
   $query = 'select COUNT(purchase_invoice_id) as numcount, sum(invoice_total_value) as sum from ' . $db_obj->getTableName('client_purchase_invoice') . " WHERE invoice_nature='purchaseinvoice'  and added_by='" . $_SESSION["user_detail"]["user_id"] . "' and is_canceled='0' and is_deleted='0'";
    $query.="and invoice_date >= '" . date('Y-m') . "-01 00:00:00'";
    $query.="and invoice_date <= '" . date('Y-m-d') . " 23:59:59'";
    $dataTotalMonths = $db_obj->get_results($query);
   $query = "SELECT COUNT(i.purchase_invoice_id) as numcount,sum(item.cgst_amount) as cgst_amount,sum(item.sgst_amount) as sgst_amount,sum(igst_amount) as igst_amount,sum(cess_amount) as cess_amount FROM " . $db_obj->getTableName('client_purchase_invoice') . " as i inner join " . $db_obj->getTableName('client_purchase_invoice_item') . " as item on item.purchase_invoice_id = i.purchase_invoice_id WHERE i.invoice_nature='purchaseinvoice'  and i.added_by='" . $_SESSION["user_detail"]["user_id"] . "' and i.is_canceled='0' and i.is_deleted='0'";

    $query.="and i.invoice_date >= '" . date('Y-m') . "-01 00:00:00'";
    $query.="and i.invoice_date <= '" . date('Y-m-d') . " 23:59:59'";
    $dataTotalsDue = $db_obj->get_results($query);
    $query = 'select COUNT(invoice_id) as numcount, sum(invoice_total_value) as sum from ' . $db_obj->getTableName('client_invoice') . " WHERE invoice_nature='salesinvoice'  and added_by='" . $_SESSION["user_detail"]["user_id"] . "' and is_canceled='0' and is_deleted='0'";
    $query.="and invoice_date >= '" . date('Y-m') . "-01 00:00:00'";
    $query.="and invoice_date <= '" . date('Y-m-d') . " 23:59:59'";
     $dataTotalMonthsSale  = $db_obj->get_results($query);
  $query = "SELECT COUNT(i.invoice_id) as numcount,sum(item.cgst_amount) as cgst_amount,sum(item.sgst_amount) as sgst_amount,sum(igst_amount) as igst_amount,sum(cess_amount) as cess_amount FROM " . $db_obj->getTableName('client_invoice') . " as i inner join " . $db_obj->getTableName('client_invoice_item') . " as item on item.invoice_id = i.invoice_id WHERE i.invoice_nature='salesinvoice'  and i.added_by='" . $_SESSION["user_detail"]["user_id"] . "' and i.is_canceled='0' and i.is_deleted='0'";

    $query.="and i.invoice_date >= '" . date('Y-m') . "-01 00:00:00'";
    $query.="and i.invoice_date <= '" . date('Y-m-d') . " 23:59:59'";
    $dataTotalsSale = $db_obj->get_results($query);
}
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr">
    <div class="col-md-10 col-sm-9 col-xs-12 mobpadlr">
        <div class="clear"></div>
<?php $db_obj->showErrorMessage(); ?>
<?php $db_obj->showSuccessMessge(); ?>
<?php $db_obj->unsetMessage(); ?>
        <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Dashboard Overview</h1></div>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class=" whitebg dashleftbox">
               <?php
                $currentmonth_total_due_cgst = 0;
                $currentmonth_total_due_sgst = 0;
                $currentmonth_total_due_igst = 0;
                $currentmonth_total_due_cess = 0;
                $current_total_month_due = 0;

                $currentmonth_total_sale = 0;
                if ($dataTotalsDue[0]->numcount > 0) {


                    $currentmonth_total_due_cgst = $dataTotalsDue[0]->cgst_amount;
                    $currentmonth_total_due_sgst = $dataTotalsDue[0]->sgst_amount;
                    $currentmonth_total_due_igst = $dataTotalsDue[0]->igst_amount;
                    $currentmonth_total_due_cess = $dataTotalsDue[0]->cess_amount;
                }
				$currentmonth_total_sale_cgst = 0;
                $currentmonth_total_sale_sgst = 0;
                $currentmonth_total_sale_igst = 0;
                $currentmonth_total_sale_cess = 0;
                $current_total_month_sale = 0;

                $currentmonth_total_sale = 0;
                if ($dataTotalsSale[0]->numcount > 0) {


                    $currentmonth_total_sale_cgst = $dataTotalsSale[0]->cgst_amount;
                    $currentmonth_total_sale_sgst = $dataTotalsSale[0]->sgst_amount;
                    $currentmonth_total_sale_igst = $dataTotalsSale[0]->igst_amount;
                    $currentmonth_total_sale_cess = $dataTotalsSale[0]->cess_amount;
                }
                ?>
              
                <?php
                /* current month total sale */

                if ($dataTotalMonths[0]->numcount > 0) {
                    foreach ($dataTotalMonths as $dataTotalMonth) {
                        $currentmonth_total_sale_purchase = $dataTotalMonth->sum;
                    }
                } else {
                    $currentmonth_total_sale_purchase = 0;
                }
				 if ($dataTotalMonthsSale[0]->numcount > 0) {
                    foreach ($dataTotalMonthsSale as $dataTotalMonth) {
                        $currentmonth_total_sale = $dataTotalMonth->sum;
                    }
                } else {
                    $currentmonth_total_sale = 0;
                }
                ?>
				 <div class="col-md-12">
                 <div class="tab col-md-12 col-sm-12 col-xs-12">
                <a href="<?php echo PROJECT_URL . '/?page=dashboard' ?>" >
                    GSTR1 
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=dashboard&gstr2=view' ?>">
                    GSTR2
                </a>
				  <a href="<?php echo PROJECT_URL . '/?page=dashboard&gstr3=view' ?>" class="active" >
                    GSTR3
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=dashboard&overview=view&financialyear='.$financialYear ?>"> OVERVIEW </a>
           
              
            </div></div><div class="clear height10"> </div>
                <div class="listcontent">

          
                    <div class="row dashtopbox">
                        <form method="post" enctype="multipart/form-data" name="client-dashboard" id='client-dashboard'>
                            <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                <label>From Date<span class="starred">*</span></label>
                                <input type="text" placeholder="yyyy-mm-dd" name="from_date" id="from_date" value="<?php if (isset($_POST["from_date"])) {
                    echo $_POST["from_date"];
                } else {
                    echo date('Y-m-01');
                } ?>" class="required form-control" data-bind="date" 
                                       />
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                <label>To Date<span class="starred">*</span></label>
                                <input type="text" placeholder="yyyy-mm-dd" name="to_date" id="to_date" value="<?php if (isset($_POST["to_date"])) {
                    echo $_POST["to_date"];
                } else {
                    echo date('Y-m-d');
                } ?>" class="required form-control" data-bind="date"
                                       />
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12 form-group text-left">

                                <input type='submit' class="btn btn-danger martop20" name='submit' value='Filter' id='submit'>


                            </div>
                        </form>
                        <div class="clear"></div>



                     <table class="table table-striped invoice-filter-table">
  <thead>
    <tr>
      <th>Sales/Purchase</th>
      <th>Monthly Sales</th>
      <th>CGST</th>
      <th>SGST</th>
	    <th>IGST</th>
		<th>CESS</th>
		
    </tr>
  </thead>
  <tbody>
   <tr>
      <th scope="row">(A)Sales</th>
      <td><?php echo $currentmonth_total_sale; ?></td>
      <td><?php echo $currentmonth_total_sale_cgst; ?></td>
      <td><?php echo $currentmonth_total_sale_sgst; ?></td>
	  <td><?php echo $currentmonth_total_sale_igst; ?></td>
	  <td><?php echo $currentmonth_total_sale_cess; ?></td>
    </tr>
    <tr>
      <th scope="row">(B)Purchase</th>
      <td><?php echo $currentmonth_total_sale_purchase; ?></td>
      <td><?php echo $currentmonth_total_due_cgst; ?></td>
      <td><?php echo $currentmonth_total_due_sgst; ?></td>
	  <td><?php echo $currentmonth_total_due_igst; ?></td>
	  <td><?php echo $currentmonth_total_due_cess; ?></td>
    </tr>
   
	 <tr>
      <th scope="row">GSTR3(A-B)</th>
      <td><?php echo  ($currentmonth_total_sale-$currentmonth_total_sale_purchase); ?></td>
      <td><?php echo ($currentmonth_total_sale_cgst-$currentmonth_total_due_cgst); ?></td>
      <td><?php echo ($currentmonth_total_sale_sgst-$currentmonth_total_due_sgst); ?></td>
	  <td><?php echo ($currentmonth_total_sale_igst-$currentmonth_total_due_igst); ?></td>
	  <td><?php echo ($currentmonth_total_sale_cess-$currentmonth_total_due_cess); ?></td>
    </tr>
   
  </tbody>
</table>


                    </div><!--/row-->    
                    <!--/col-12-->
                </div><!--/row-->


                <div class="clear height30"></div>	
               
               
            </div>   
        </div>
        <div class="dasfooter">Copyright @ by GST Keeper</div>  	
    </div>
    <div class="col-md-2 col-sm-3 col-xs-12 dash-rightnav">
                                <?php include(PROJECT_ROOT . "/modules/dashboard/view/rightpanel.php"); ?>
    </div>
</div>
<!--CONTENT START HERE-->
</div>
</div>
<script>
    $(document).ready(function () {

        /* from date datepicker */
        $("#from_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            yearRange: '2017:<?php echo date("Y"); ?>',
            maxDate: '0'
        });
        /* from date datepicker */
        $("#to_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            yearRange: '2017:<?php echo date("Y"); ?>',
            maxDate: '0'
        });

    });

</script>
