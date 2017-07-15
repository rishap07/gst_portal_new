
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
			 
					 $month = date('m'); 
					 $start_year = date('Y');
					 $end_year =$start_year+1;
					// $year = $start_year."-".$end_year;
				$year = $db_obj->generateFinancialYear();
				
 			   
                   $dataInvs = $db_obj->get_results('select * from '.$db_obj->getTableName('client_invoice')." where invoice_nature='salesinvoice'  and added_by='".$_SESSION["user_detail"]["user_id"]."' and is_canceled='0' and financial_year ='".$year."' order by invoice_id desc limit 0,5");
				   /* code for current year totalsale */
				   
				   $dataTotalYears = $db_obj->get_results('select COUNT(invoice_id) as numcount, sum(invoice_total_value) as sum from '.$db_obj->getTableName('client_invoice')." WHERE invoice_nature='salesinvoice'  and added_by='".$_SESSION["user_detail"]["user_id"]."' and is_canceled='0' and financial_year ='".$year."'");
				  
				   /* code for totalsale in current month */
				   $dataTotalMonths = $db_obj->get_results('select COUNT(invoice_id) as numcount, sum(invoice_total_value) as sum from '.$db_obj->getTableName('client_invoice')." WHERE invoice_nature='salesinvoice'  and added_by='".$_SESSION["user_detail"]["user_id"]."' and is_canceled='0' and financial_year ='".$year."' and month(invoice_date)='".$month."'");
				   /* code for totaldue in current financial_year */
				   $dataTotalsDue = $db_obj->get_results("SELECT sum(item.cgst_rate) as cgst_rate,sum(item.sgst_rate) as sgst_rate,sum(igst_rate) as igst_rate,sum(cess_rate) as cess_rate FROM ".$db_obj->getTableName('client_invoice')." as i inner join ".$db_obj->getTableName('client_invoice_item')." as item on item.invoice_id = i.invoice_id WHERE i.invoice_nature='salesinvoice'  and i.added_by='".$_SESSION["user_detail"]["user_id"]."' and i.is_canceled='0' and i.financial_year ='".$year."' and month(i.invoice_date)='".$month."'");
				   /* code for total invoices created in current financial_year */
                   $dataTotalinvoices = $db_obj->get_results("select COUNT(invoice_id) as invoicecount,month(invoice_date) as month from ".$db_obj->getTableName('client_invoice')." WHERE invoice_nature='salesinvoice'  and added_by='".$_SESSION["user_detail"]["user_id"]."' and is_canceled='0' and financial_year ='".$year."'
 GROUP by month(invoice_date) desc limit 0,5 ");
 /* code for total month sale */
 $dataTotalMonthSales = $db_obj->get_results("select count(invoice_id) as monthcount, sum(invoice_total_value) as totalsale,month(invoice_date) as month from ".$db_obj->getTableName('client_invoice')." WHERE invoice_nature='salesinvoice'  and added_by='".$_SESSION["user_detail"]["user_id"]."' and is_canceled='0' and financial_year ='".$year."'

  GROUP by month(invoice_date) desc limit 0,5 ");
                  /* code for push data in a array for totalinvoices created in current financial_year */
					$data[0] = array("Month","Total Invoices");
					
					if(count($dataTotalinvoices)>0)
                                {
                                    foreach($dataTotalinvoices as $dataTotalinvoice)
                                    {
									array_push($data,array($start_year."/".$dataTotalinvoice->month,(int)$dataTotalinvoice->invoicecount));
									}
								}
								
					//print_r($data);
					//$data[1] = array("2017/06",20);
					//$data[2] = array("2017/07",10);
					 $data = json_encode($data);
					 $data_month_sale[0] = array("Month","Total Sale");
					/* code for push data in a array for totalmonthsale in current financial_year */
					if(count($dataTotalMonthSales)>0)
                                {
                                    foreach($dataTotalMonthSales as $dataTotalMonthSale)
                                    {
										
									array_push($data_month_sale,array($start_year."/".$dataTotalMonthSale->month,round($dataTotalMonthSale->totalsale)));
									}
								}
								
					//print_r($data);
					//$data[1] = array("2017/06",20);
					//$data[2] = array("2017/07",10);
					 $data_month_sale = json_encode($data_month_sale);
					?>
					
					<?php
					/* total dues current month */
					$currentmonth_total_due_cgst=0;
					$currentmonth_total_due_sgst=0;
					$currentmonth_total_due_igst=0;
					$currentmonth_total_due_cess=0;
					$current_total_month_due=0;
				
					$currentmonth_total_sale=0;
					 if(count($dataTotalsDue)>0)
                                {
                                    foreach($dataTotalsDue as $dataTotalDue)
                                    {
										
										$currentmonth_total_due_cgst=$dataTotalDue->cgst_rate;
										$currentmonth_total_due_sgst=$dataTotalDue->sgst_rate;
										$currentmonth_total_due_igst=$dataTotalDue->igst_rate;
										$currentmonth_total_due_cess=$dataTotalDue->cess_rate;
										$current_total_month_due= $currentmonth_total_due_cgst+$currentmonth_total_due_sgst+$currentmonth_total_due_igst+$currentmonth_total_due_cess;
									}
								}
								?>
					<?php
					/* current year total sale */
					$year_totalsale=0;
					
					 if($dataTotalYears[0]->numcount >0)
                                {
                                    foreach($dataTotalYears as $dataTotalYear)
                                    {
										$year_totalsale=$dataTotalYear->sum;
									}
								}
					
								else
								{
									 $year_totalsale=0;
								}
								?>
					<?php
					/* current month total sale */
					
					 if($dataTotalMonths[0]->numcount >0)
                                {
                                    foreach($dataTotalMonths as $dataTotalMonth)
                                    {
									$currentmonth_total_sale=$dataTotalMonth->sum;
									}
								}
				
								else
								{
									$currentmonth_total_sale=0;
								}
								?>
			  
                <div class="listcontent">
				
 
    <div class="row dashtopbox">
      <div class="col-md-4 col-sm-4 col-xs-12">
      <div class="lightgreen dashtopcol">
        <div class="dashcoltxt">
          <span class="boxpricetxt"><i class="fa fa-inr" aria-hidden="true"></i>
		
		 <?php echo $year_totalsale;?>
		  </span><br /><div class="txtyear">Year Sale</div>
        </div>
        </div>
      </div>
      
	    <div class="col-md-4 col-sm-4 col-xs-12 ">
         <div class="lightblue dashtopcol">
        <div class="dashcoltxt">
          <span class="boxpricetxt"><i class="fa fa-inr" aria-hidden="true"></i><?php echo $currentmonth_total_sale;?></span><br /><div class="txtyear">Monthly Sale</div>
        </div>
        </div>
      </div>
      
	   <div class="col-md-4 col-sm-4 col-xs-12">
           <div class="lightyellowbg dashtopcol">
            <div class="dashcoltxt">
              <span class="boxpricetxt"><i class="fa fa-inr" aria-hidden="true"></i><?php echo $current_total_month_due;?></span><br /><div class="txtyear">Monthly Due</div>
            </div>
            </div>
      </div>
	  
	   
    
    </div><!--/row-->    
<!--/col-12-->
</div><!--/row-->
<?php
if (count($dataTotalinvoices) > 0 )
{
	?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<div class="col-md-6">

 
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawVisualization);

      function drawVisualization() {
        // Some raw data (not necessarily accurate)
		var data = google.visualization.arrayToDataTable(<?=$data?>);
      
 
    var options = {
      title : 'Monthly Invoices created',
      vAxis: {title: 'Cups'},
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
 if(count($dataTotalMonthSales) > 0)
 {
	 ?>
    <div id="chart_month_invoice"></div></div>
    
    
	<div class="col-md-6">
	
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawVisualization);

      function drawVisualization() {
        // Some raw data (not necessarily accurate)
		var data = google.visualization.arrayToDataTable(<?=$data_month_sale?>);
      
 
    var options = {
      title : 'Monthly Sales',
      vAxis: {title: 'Cups'},
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
                                if(count($dataInvs)>0)
                                {
                                    foreach($dataInvs as $dataInv)
                                    {
                                        ?>
                                        <tr>
                                            <td><?php echo $dataInv->serial_number;?></td>
                                            <td><?php echo $dataInv->invoice_date;?></td>
                                            <td><?php echo $dataInv->billing_name.",".$dataInv->billing_address;?></td>
                                            <td style="text-align: right"><?php echo $dataInv->invoice_total_value;?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                else
                                {
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
                <div class="tc"><a href="<?php echo PROJECT_URL;?>/?page=client_create_invoice" class="greenbtnborder animation">+ Add More</a> 
                    <a href="<?php echo PROJECT_URL;?>/?page=client_invoice_list" class="redbtnborder animation" style="margin-left:5px;">VIEW ALL</a>
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