<?php
$obj_client = new client();
$obj_common= new common();
$financialYear=$obj_common->generateFinancialYear();
$dataTotalYears;
$dataTotalMonths;
$dataTotalsDue;
$dataTotalsDue_cr_rv;
$month = date('m');
$start_year = date('Y');
$end_year = $start_year + 1;
// $year = $start_year."-".$end_year;
$year = $db_obj->generateFinancialYear();
$dataInvs = $db_obj->get_results('select * from ' . $db_obj->getTableName('client_invoice') . " where invoice_nature='salesinvoice'  and added_by='" . $_SESSION["user_detail"]["user_id"] . "' and is_canceled='0' and invoice_type <> 'deliverychallaninvoice' and financial_year ='" . $year . "' order by invoice_id desc limit 0,5");


/* code for total invoices created in current financial_year */
$dataTotalinvoices = $db_obj->get_results("select COUNT(invoice_id) as invoicecount,month(invoice_date) as month from " . $db_obj->getTableName('client_invoice') . " WHERE invoice_nature='salesinvoice'  and added_by='" . $_SESSION["user_detail"]["user_id"] . "' and is_canceled='0' and invoice_type <> 'deliverychallaninvoice' and financial_year ='" . $year . "'
 GROUP by month(invoice_date) desc limit 0,5 ");
/* code for total month sale */
 $sql="select count(invoice_id) as monthcount, sum(invoice_total_value) as totalsale,month(invoice_date) as month from " . $db_obj->getTableName('client_invoice') . " WHERE invoice_nature='salesinvoice' and (invoice_type <> 'deliverychallaninvoice' and invoice_type<>'creditnote' and invoice_type<>'refundvoucherinvoice') and added_by='" . $_SESSION["user_detail"]["user_id"] . "' and is_canceled='0' and is_deleted='0' and financial_year ='" . $year . "'

  GROUP by month(invoice_date) desc limit 0,5 ";
$dataTotalMonthSales = $db_obj->get_results($sql);
$sql="select count(invoice_id) as monthcount, sum(invoice_total_value) as totalsale,month(invoice_date) as month from " . $db_obj->getTableName('client_invoice') . " WHERE invoice_nature='salesinvoice' and (invoice_type='creditnote' or invoice_type='refundvoucherinvoice') and added_by='" . $_SESSION["user_detail"]["user_id"] . "' and is_canceled='0' and is_deleted='0' and financial_year ='" . $year . "'

  GROUP by month(invoice_date) desc limit 0,5 ";
  $dataTotalMonthSales_cr_rv = $db_obj->get_results($sql);
if (isset($_POST['submit']) && $_POST['submit'] == 'Filter') {

    $from_date = isset($_POST['from_date']) ? $_POST['from_date'] : '';
    $to_date = isset($_POST['to_date']) ? $_POST['to_date'] : '';

    if ($from_date < $to_date) {
        // $obj_client->setError('Start date can not be less than to date');
    }
	$query ='select * from ' . $db_obj->getTableName('client_invoice') . " where invoice_nature='salesinvoice'  and added_by='" . $_SESSION["user_detail"]["user_id"] . "' and is_canceled='0' and invoice_type <> 'deliverychallaninvoice' and financial_year ='" . $year . "' order by invoice_id desc limit 0,5";
	
    $dataInvs = $db_obj->get_results($query);
    
    
/* code for total invoices created in current financial_year */
$query="select COUNT(invoice_id) as invoicecount,month(invoice_date) as month from " . $db_obj->getTableName('client_invoice') . " WHERE invoice_nature='salesinvoice'  and added_by='" . $_SESSION["user_detail"]["user_id"] . "' and is_canceled='0' and invoice_type <> 'deliverychallaninvoice' and financial_year ='" . $year . "'
  ";
     if ($from_date != '') {
        $query.="and invoice_date >= '" . $from_date . " 00:00:00'";
    }
    if ($to_date != '') {
        $query.="and invoice_date <= '" . $to_date . " 23:59:59'";
    }
	$query.="GROUP by month(invoice_date)";

$dataTotalinvoices = $db_obj->get_results($query);
/* code for total month sale */
$query="select count(invoice_id) as monthcount, sum(invoice_total_value) as totalsale,month(invoice_date) as month from " . $db_obj->getTableName('client_invoice') . " WHERE invoice_nature='salesinvoice' and (invoice_type <> 'deliverychallaninvoice' and invoice_type<>'creditnote' and invoice_type<>'refundvoucherinvoice') and added_by='" . $_SESSION["user_detail"]["user_id"] . "' and is_canceled='0' and is_deleted='0' and financial_year ='" . $year . "'";
   if ($from_date != '') {
        $query.="and invoice_date >= '" . $from_date . " 00:00:00'";
    }
    if ($to_date != '') {
        $query.="and invoice_date <= '" . $to_date . " 23:59:59'";
    }
	$query.="GROUP by month(invoice_date) desc limit 0,5";
 $dataTotalMonthSales = $db_obj->get_results($query);
$query="select count(invoice_id) as monthcount, sum(invoice_total_value) as totalsale,month(invoice_date) as month from " . $db_obj->getTableName('client_invoice') . " WHERE invoice_nature='salesinvoice' and (invoice_type='creditnote' or invoice_type='refundvoucherinvoice') and added_by='" . $_SESSION["user_detail"]["user_id"] . "' and is_canceled='0' and is_deleted='0' and financial_year ='" . $year . "'";
   if ($from_date != '') {
        $query.="and invoice_date >= '" . $from_date . " 00:00:00'";
    }
    if ($to_date != '') {
        $query.="and invoice_date <= '" . $to_date . " 23:59:59'";
    }
	
  $query.="GROUP by month(invoice_date) desc limit 0,5";
  $dataTotalMonthSales_cr_rv = $db_obj->get_results($query);
    /* code for current month totalsale */
  //  $dataTotalMonths = $db_obj->get_results('select COUNT(invoice_id) as numcount, sum(invoice_total_value) as sum from ' . $db_obj->getTableName('client_invoice') . " WHERE invoice_nature='salesinvoice' and invoice_type <> 'deliverychallaninvoice' and added_by='" . $_SESSION["user_detail"]["user_id"] . "' and is_canceled='0' and is_deleted='0' and financial_year ='" . $year . "' and invoice_date between '" . $from_date . "' and '" . $to_date . "'");
    $query = 'select COUNT(invoice_id) as numcount, sum(invoice_total_value) as sum from ' . $db_obj->getTableName('client_invoice') . " WHERE invoice_nature='salesinvoice' and (invoice_type <> 'deliverychallaninvoice' and invoice_type<>'creditnote' and invoice_type<>'refundvoucherinvoice')  and added_by='" . $_SESSION["user_detail"]["user_id"] . "' and is_canceled='0' and is_deleted='0'";
    if ($from_date != '') {
        $query.="and invoice_date >= '" . $from_date . " 00:00:00'";
    }
    if ($to_date != '') {
        $query.="and invoice_date <= '" . $to_date . " 23:59:59'";
    }
	//$query.="GROUP by month(invoice_date) desc limit 0,5";
    $dataTotalMonths = $db_obj->get_results($query);
	$query = 'select COUNT(invoice_id) as numcount, sum(invoice_total_value) as sum from ' . $db_obj->getTableName('client_invoice') . " WHERE invoice_nature='salesinvoice' and (invoice_type='creditnote' or invoice_type='refundvoucherinvoice') and added_by='" . $_SESSION["user_detail"]["user_id"] . "' and is_canceled='0' and is_deleted='0'";
    if ($from_date != '') {
        $query.="and invoice_date >= '" . $from_date . " 00:00:00'";
    }
    if ($to_date != '') {
        $query.="and invoice_date <= '" . $to_date . " 23:59:59'";
    }
	//$query.="GROUP by month(invoice_date) desc limit 0,5";
    $dataTotalMonthsB = $db_obj->get_results($query);
    $query = "SELECT COUNT(i.invoice_id) as numcount,sum(item.cgst_amount) as cgst_amount,sum(item.sgst_amount) as sgst_amount,sum(igst_amount) as igst_amount,sum(cess_amount) as cess_amount FROM " . $db_obj->getTableName('client_invoice') . " as i inner join " . $db_obj->getTableName('client_invoice_item') . " as item on item.invoice_id = i.invoice_id WHERE i.invoice_nature='salesinvoice' and (i.invoice_type <> 'deliverychallaninvoice' and i.invoice_type<>'creditnote' and i.invoice_type<>'refundvoucherinvoice')  and i.added_by='" . $_SESSION["user_detail"]["user_id"] . "' and i.is_canceled='0' and i.is_deleted='0'";
    if ($from_date != '') {
        $query.="and invoice_date >= '" . $from_date . " 00:00:00'";
    }
    if ($to_date != '') {
        $query.="and invoice_date <= '" . $to_date . " 23:59:59'";
    }
    $dataTotalsDue = $db_obj->get_results($query);
	 $query = "SELECT COUNT(i.invoice_id) as numcount,sum(item.cgst_amount) as cgst_amount,sum(item.sgst_amount) as sgst_amount,sum(igst_amount) as igst_amount,sum(cess_amount) as cess_amount FROM " . $db_obj->getTableName('client_invoice') . " as i inner join " . $db_obj->getTableName('client_invoice_item') . " as item on item.invoice_id = i.invoice_id WHERE i.invoice_nature='salesinvoice' and (i.invoice_type='creditnote' or i.invoice_type='refundvoucherinvoice') and i.added_by='" . $_SESSION["user_detail"]["user_id"] . "' and i.is_canceled='0' and i.is_deleted='0'";
    if ($from_date != '') {
        $query.="and invoice_date >= '" . $from_date . " 00:00:00'";
    }
    if ($to_date != '') {
        $query.="and invoice_date <= '" . $to_date . " 23:59:59'";
    }
	
    $dataTotalsDue_cr_rv = $db_obj->get_results($query);
} else {
    $query = 'select COUNT(invoice_id) as numcount, sum(invoice_total_value) as sum from ' . $db_obj->getTableName('client_invoice') . " WHERE invoice_nature='salesinvoice' and (invoice_type <> 'deliverychallaninvoice' and invoice_type<>'creditnote' and invoice_type<>'refundvoucherinvoice') and added_by='" . $_SESSION["user_detail"]["user_id"] . "' and is_canceled='0' and is_deleted='0'";
    $query.="and invoice_date >= '" . date('Y-m') . "-01 00:00:00'";
    $query.="and invoice_date <= '" . date('Y-m-d') . " 23:59:59'";
   // echo $query;
    $dataTotalMonths = $db_obj->get_results($query);
	 $query = 'select COUNT(invoice_id) as numcount, sum(invoice_total_value) as sum from ' . $db_obj->getTableName('client_invoice') . " WHERE invoice_nature='salesinvoice' and (invoice_type='creditnote' or invoice_type='refundvoucherinvoice') and added_by='" . $_SESSION["user_detail"]["user_id"] . "' and is_canceled='0' and is_deleted='0'";
    $query.="and invoice_date >= '" . date('Y-m') . "-01 00:00:00'";
    $query.="and invoice_date <= '" . date('Y-m-d') . " 23:59:59'";


    $dataTotalMonthsB = $db_obj->get_results($query);
    $query = "SELECT COUNT(i.invoice_id) as numcount,sum(item.cgst_amount) as cgst_amount,sum(item.sgst_amount) as sgst_amount,sum(igst_amount) as igst_amount,sum(cess_amount) as cess_amount FROM " . $db_obj->getTableName('client_invoice') . " as i inner join " . $db_obj->getTableName('client_invoice_item') . " as item on item.invoice_id = i.invoice_id WHERE i.invoice_nature='salesinvoice' and (i.invoice_type <> 'deliverychallaninvoice' and i.invoice_type<>'creditnote' and i.invoice_type<>'refundvoucherinvoice')  and i.added_by='" . $_SESSION["user_detail"]["user_id"] . "' and i.is_canceled='0' and i.is_deleted='0'";
    $query.="and i.invoice_date >= '" . date('Y-m') . "-01 00:00:00'";
    $query.="and i.invoice_date <= '" . date('Y-m-d') . " 23:59:59'";
    // echo $query;
    $dataTotalsDue = $db_obj->get_results($query);
	$query = "SELECT COUNT(i.invoice_id) as numcount,sum(item.cgst_amount) as cgst_amount,sum(item.sgst_amount) as sgst_amount,sum(igst_amount) as igst_amount,sum(cess_amount) as cess_amount FROM " . $db_obj->getTableName('client_invoice') . " as i inner join " . $db_obj->getTableName('client_invoice_item') . " as item on item.invoice_id = i.invoice_id WHERE i.invoice_nature='salesinvoice' and (i.invoice_type='creditnote' or i.invoice_type='refundvoucherinvoice') and i.added_by='" . $_SESSION["user_detail"]["user_id"] . "' and i.is_canceled='0' and i.is_deleted='0'";
    $query.="and i.invoice_date >= '" . date('Y-m') . "-01 00:00:00'";
    $query.="and i.invoice_date <= '" . date('Y-m-d') . " 23:59:59'";
	
    $dataTotalsDue_cr_rv = $db_obj->get_results($query);
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
/* code for push data in a array for totalinvoices created in current financial_year */
$data[0] = array("Month", "Total Invoices");

if (count($dataTotalinvoices) > 0) {
    foreach ($dataTotalinvoices as $dataTotalinvoice) {
        array_push($data, array($start_year . "/" . $dataTotalinvoice->month, (int) $dataTotalinvoice->invoicecount));
    }
}

//print_r($data);
//$data[1] = array("2017/06",20);
//$data[2] = array("2017/07",10);
$data = json_encode($data);
$data_month_sale[0] = array("Month", "Total Sale");
/* code for push data in a array for totalmonthsale in current financial_year */
if (count($dataTotalMonthSales) > 0) {
    foreach ($dataTotalMonthSales as $dataTotalMonthSale) {

      //  array_push($data_month_sale, array($start_year . "/" . $dataTotalMonthSale->month, round($dataTotalMonthSale->totalsale)));
   }
 

}

  for($i=0;$i < sizeof($dataTotalMonthSales); $i++) {
	  if((!empty($dataTotalMonthSales[$i]->totalsale)) && (!empty($dataTotalMonthSales_cr_rv[$i]->totalsale)))
	  {
		$sale=0;
		//var_dump($dataTotalMonthSales[$i]->totalsale);
        $sale =round($dataTotalMonthSales[$i]->totalsale-$dataTotalMonthSales_cr_rv[$i]->totalsale);	
      	   
        array_push($data_month_sale, array($start_year . "/" . $dataTotalMonthSales[$i]->month,$sale ));                       
	  }elseif(empty($dataTotalMonthSales_cr_rv))
	  {
		   array_push($data_month_sale, array($start_year . "/" . $dataTotalMonthSales[$i]->month,round($dataTotalMonthSales[$i]->totalsale)));   
	  }else{
		array_push($data_month_sale, array($start_year . "/" . $dataTotalMonthSales[$i]->month,round($dataTotalMonthSales[$i]->totalsale)));   

	  }
  }


//$data[1] = array("2017/06",20);
//$data[2] = array("2017/07",10);
$data_month_sale = json_encode($data_month_sale);
?>

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
				
                $currentmonth_total_due_cgst_cr_rv = 0;
                $currentmonth_total_due_sgst_cr_rv = 0;
                $currentmonth_total_due_igst_cr_rv = 0;
                $currentmonth_total_due_cess_cr_rv = 0;
                $current_total_month_due_cr_rv = 0;

               
                if ($dataTotalsDue_cr_rv[0]->numcount > 0) {


                    $currentmonth_total_due_cgst_cr_rv = $dataTotalsDue_cr_rv[0]->cgst_amount;
                    $currentmonth_total_due_sgst_cr_rv = $dataTotalsDue_cr_rv[0]->sgst_amount;
                    $currentmonth_total_due_igst_cr_rv = $dataTotalsDue_cr_rv[0]->igst_amount;
                    $currentmonth_total_due_cess_cr_rv = $dataTotalsDue_cr_rv[0]->cess_amount;
                }
				
                ?>
               
                <?php
                /* current month total sale */
                $currentmonth_total_sale=0;
				$currentmonth_total_saleb=0;
				$total_sale_a_b=0;
                if (!empty($dataTotalMonths[0]->numcount)) {
                    foreach ($dataTotalMonths as $dataTotalMonth) {
                        $currentmonth_total_sale = $dataTotalMonth->sum;
                    }
                } else {
                    $currentmonth_total_sale = 0;
                }
				if (!empty($dataTotalMonthsB[0]->numcount)) {
                    foreach ($dataTotalMonthsB as $dataTotalMonth) {
                        $currentmonth_total_saleb = $dataTotalMonth->sum;
                    }
                } else {
                    $currentmonth_total_saleb = 0;
                }
				//$total_sale_a_b= $currentmonth_total_sale-$currentmonth_total_saleb;
                ?>
				
                 <div class="tab">
                <a href="<?php echo PROJECT_URL . '/?page=dashboard' ?>" class="active">
                    GSTR1 
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=dashboard&gstr2=view' ?>" >
                    GSTR2
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=dashboard&gstr3=view' ?>" >
                    GSTR3
                </a>
                 <a href="<?php echo PROJECT_URL . '/?page=dashboard&overview=view&financialyear='.$financialYear ?>"> OVERVIEW </a>
           
            </div>
            <div class="clear height10"> </div>
                <div class="listcontent">

  
                    <div class="row dashtopbox">
                        <form method="post" enctype="multipart/form-data" name="client-dashboard" id='client-dashboard'>
                            <div class="col-md-5 col-sm-4 col-xs-12 form-group">
                                <label>From Date<span class="starred">*</span></label>
                                <input type="text" placeholder="yyyy-mm-dd" name="from_date" id="from_date" value="<?php if (isset($_POST["from_date"])) {
                    echo $_POST["from_date"];
                } else {
                    echo date('Y-m-01');
                } ?>" class="required form-control" data-bind="date" 
                                       />
                            </div>
                            <div class="col-md-5 col-sm-4 col-xs-12 form-group">
                                <label>To Date<span class="starred">*</span></label>
                                <input type="text" placeholder="yyyy-mm-dd" name="to_date" id="to_date" value="<?php if (isset($_POST["to_date"])) {
                    echo $_POST["to_date"];
                } else {
                    echo date('Y-m-d');
                } ?>" class="required form-control" data-bind="date"
                                       />
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-12 form-group text-left">

                                <input type='submit' class="btn btnorange boldfont martop20" name='submit' value='Filter' id='submit' style="width:100%;">


                            </div>
                        </form>
                        <div class="clear height20"></div>


                        <div class="dasboardbox">
                            <div class="lightblue dashtopcol">
                                <div class="dashcoltxt">
                                    <span class="boxpricetxt"><i class="fa fa-inr" aria-hidden="true"></i><?php echo $currentmonth_total_sale-$currentmonth_total_saleb; ?></span><br /><div class="txtyear">Monthly Sale</div>
                                </div>
                            </div>
                        </div>


                        <div class="dasboardbox">
                            <div class="lightgreen dashtopcol">
                                <div class="dashcoltxt">
                                    <span class="boxpricetxt"><i class="fa fa-inr" aria-hidden="true"></i>

<?php echo $currentmonth_total_due_cgst-$currentmonth_total_due_cgst_cr_rv; ?>
                                    </span><br /><div class="txtyear">CGST</div>
                                </div>
                            </div>
                        </div>
                        <div class="dasboardbox">
                            <div class="lightyellowbg dashtopcol">
                                <div class="dashcoltxt">
                                    <span class="boxpricetxt"><i class="fa fa-inr" aria-hidden="true"></i>

<?php echo $currentmonth_total_due_sgst-$currentmonth_total_due_sgst_cr_rv; ?>
                                    </span><br /><div class="txtyear">SGST</div>
                                </div>
                            </div>
                        </div>
                        <div class="dasboardbox">
                            <div class="pinkbg dashtopcol">
                                <div class="dashcoltxt">
                                    <span class="boxpricetxt"><i class="fa fa-inr" aria-hidden="true"></i>

<?php echo $currentmonth_total_due_igst-$currentmonth_total_due_igst_cr_rv; ?>
                                    </span><br /><div class="txtyear">IGST</div>
                                </div>
                            </div>
                        </div>
                        <div class="dasboardbox last">
                            <div class="perpalbg dashtopcol">
                                <div class="dashcoltxt">
                                    <span class="boxpricetxt"><i class="fa fa-inr" aria-hidden="true"></i>

<?php echo $currentmonth_total_due_cess-$currentmonth_total_due_cess_cr_rv; ?>
                                    </span><br /><div class="txtyear">CESS</div>
                                </div>
                            </div>
                        </div>



                    </div><!--/row-->    
                    <!--/col-12-->
                </div><!--/row-->
<?php
if (count($dataTotalinvoices) > 0) {
    ?>
                    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

                    <div class="col-md-6">


                        <script type="text/javascript">
                            google.charts.load('current', {'packages': ['corechart']});
                            google.charts.setOnLoadCallback(drawVisualization);

                            function drawVisualization() {
                                // Some raw data (not necessarily accurate)
                                var data = google.visualization.arrayToDataTable(<?= $data ?>);


                                var options = {
                                    title: 'Monthly Invoices created',
                                    vAxis: {title: 'Number of Invoices'},
                                    hAxis: {title: 'Month'},
                                    seriesType: 'bars',
                                    series: {5: {type: 'line'}}
                                };

                                var chart = new google.visualization.ComboChart(document.getElementById('chart_month_invoice'));
                                chart.draw(data, options);
                            }
                        </script>
    <?php
}
?>
<?php
if (count($dataTotalMonthSales) > 0) {
    ?>
                        <div id="chart_month_invoice"></div></div>


                    <div class="col-md-6">

                        <script type="text/javascript">
                            google.charts.load('current', {'packages': ['corechart']});
                            google.charts.setOnLoadCallback(drawVisualization);

                            function drawVisualization() {
                                // Some raw data (not necessarily accurate)
                                var data = google.visualization.arrayToDataTable(<?= $data_month_sale ?>);


                                var options = {
                                    title: 'Monthly Sales',
                                    vAxis: {title: 'Monthly Sale'},
                                    hAxis: {title: 'Month'},
                                    seriesType: 'bars',
                                    series: {5: {type: 'line'}}
                                };

                                var chart = new google.visualization.ComboChart(document.getElementById('chart_monthsale'));
                                chart.draw(data, options);
                            }
                        </script>

                        <div id="chart_monthsale"></div></div>
<?php } ?>

                <div class="clear height30"></div>	
                <div class="boxheading">New Tax Invoice List</div>
                <div class="border"></div>
                <div class="listcontent">



                    <div class="tableresponsive">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="inovicelefttable">
                            <thead>
                                <tr>
                                    <th style="width:150px">Invoice Number</th>
                                    <th style="width:150px">Invoice Date</th>
                                    <th>Recipient/Customer Details</th>
                                    <th style="width:150px">Invoice Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                <?php
                if (count($dataInvs) > 0) {
                    foreach ($dataInvs as $dataInv) {
                        ?>
                                        <tr>
                                            <td><?php echo $dataInv->serial_number; ?></td>
                                            <td><?php echo $dataInv->invoice_date; ?></td>
                                            <td><?php echo $dataInv->billing_name . "," . $dataInv->billing_address; ?></td>
                                            <td style="text-align: right"><?php echo $dataInv->invoice_total_value; ?></td>
                                        </tr>
        <?php
    }
} else {
    ?>
                                    <tr>
                                        <td colspan="4">No Invoices Added</td>
                                    </tr>
    <?php
}
?>
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="clear height30"></div>
                <div class="tc"><a href="<?php echo PROJECT_URL; ?>/?page=client_create_invoice" class="greenbtnborder animation">+ Add More</a> 
                    <a href="<?php echo PROJECT_URL; ?>/?page=client_invoice_list" class="redbtnborder animation" style="margin-left:5px;">VIEW ALL</a>
                </div>
                <div class="clear height30"></div>
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
