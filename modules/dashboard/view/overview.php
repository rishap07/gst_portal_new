<?php
$obj_graph = new graph();
$obj_common = new common();
$financialYear=$obj_common->generateFinancialYear();
$financialYR=explode('-',$financialYear);
$financialYR[0];
$financialYR[1];
$yearData = array();
$yearArray = array();
$arrayData = array();
$cashData = array();
$invoiceData = array();
$invoiceArray = array();

for($x=4; $x <= 12; $x++) {
	
	$yearData=  $financialYR[0].'-'.str_pad($x, 2, "0", STR_PAD_LEFT);
	array_push($yearArray,$yearData);
}

for($y=1; $y <= 3; $y++) {
	
	$yearData=  $financialYR[1].'-'.str_pad($y, 2, "0", STR_PAD_LEFT) ;
	array_push($yearArray,$yearData);
}


$userId= $_SESSION['user_detail']['user_id'];
$financialYearDD ='';
if(isset($_REQUEST['financialyear']) && $_REQUEST['financialyear']!='')
{
	 $financialYearDD=$_REQUEST['financialyear'];
}

foreach($yearArray as $financialMonth) {
	
	$purchaseSales=$obj_graph->purchaseSalesGraph($financialMonth, $financialYearDD, $userId);
	$purchaseSalesAmt[] = $purchaseSales[0];
	$salesInvoiceTotal[] = $purchaseSales[0]['sales'];
	$purchaseInvoiceTotal[] = $purchaseSales[0]['purchase'];
	
	$purchaseSalesInv=$obj_graph->purchaseSalesInvoiceGraph($financialMonth,  $financialYearDD, $userId);
	$purchaseSalesInvoice[] = $purchaseSalesInv[0];
	
	$cancelPurchaseSalesInv=$obj_graph->purchaseSalesCancelInvoiceGraph($financialMonth,  $financialYearDD, $userId);
	$cancelpurchaseSalesInvoice[] = $cancelPurchaseSalesInv[0];
}
?>

    <div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr">
        <div class="col-md-10 col-sm-9 col-xs-12 mobpadlr">
       		<div class="clear"></div>
			<?php $db_obj->showErrorMessage(); ?>
            <?php $db_obj->showSuccessMessge(); ?>
            <?php $db_obj->unsetMessage(); ?>
            <div class="col-md-12 col-sm-12 col-xs-12 heading">
        		<h1>Dashboard Overview</h1>
       		</div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class=" whitebg dashleftbox">
                    <div class="tab"> <a href="<?php echo PROJECT_URL . '/?page=dashboard' ?>"> GSTR1 </a> <a href="<?php echo PROJECT_URL . '/?page=dashboard&gstr2=view' ?>" > GSTR2 </a> <a href="<?php echo PROJECT_URL . '/?page=dashboard&gstr3=view' ?>" > GSTR3 </a> <a href="<?php echo PROJECT_URL . '/?page=dashboard&overview=view&financialyear='.$financialYear ?>" class="active" > OVERVIEW </a> </div>
                    <div class="clear height10"> </div>
                    <div class="dasboardbox">
                        <div class="lightblue dashtopcol">
                            <div class="dashcoltxt"> <span class="boxpricetxt"><i class="fa fa-inr" aria-hidden="true"></i>
                                <?php $sales= array_sum($salesInvoiceTotal);
                                echo number_format($sales,2);
                                ?>
                            </span><br />
                                <div class="txtyear">Total Sales</div>
                                (A)
                            </div>
                        </div>
                    </div>
                    <div class="dasboardbox">
                        <div class="lightyellowbg dashtopcol">
                            <div class="dashcoltxt"> <span class="boxpricetxt"><i class="fa fa-inr" aria-hidden="true"></i> 
                                <?php  $purchase=array_sum($purchaseInvoiceTotal);
                                echo number_format($purchase,2);
                                ?> </span><br />
                                <div class="txtyear">Total Purchase</div>
                                (B)
                            </div>
                        </div>
                    </div>
                    <div class="dasboardbox">
                        <div class="<?php if($sales>$purchase) { echo "lightgreen";} else{ echo "pinkbg";}?>  dashtopcol">
                            <div class="dashcoltxt"> <span class="boxpricetxt"><i class="fa fa-inr" aria-hidden="true"></i> 
                                <?php echo $profitloss= number_format($sales-$purchase,2);?>
                                </span><br />
                                <div class="txtyear"><?php if($sales>$purchase) {?>
                                <span class="text-default">Profit</span>
                                <?php }else{ ?><span class="text-danger">Loss</span><?php }?>
                                <br /> (A - B)
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-3 pull-right">
                        <form method='post' name='getOverview' id="getOverview">
                            <label>Financial Year</label>
                            <select class="form-control" id="financialyear" name="financialyear">
                                <?php  for($z=2017; $z<=$financialYear; $z++){ ?>
                                    <option value="<?php echo $financialYear ?>" <?php if($financialYearDD==$financialYear ){echo 'selected=selected';} ?>><?php echo $financialYear ?></option>
                                <?php }?>
                            </select>
                        </form>
                    </div>
                    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                    <div class="col-md-12 panel panel-default">
                        <div id="curve_chart4"  style=" height: 300px;"></div>
                        <script type="text/javascript">
								google.charts.load('current', {packages: ['corechart', 'line']});
								google.charts.setOnLoadCallback(drawBasic);
								
								function drawBasic() {
								
									var data = new google.visualization.DataTable();
									data.addColumn('number', 'Month');
									data.addColumn('number', 'Sales');
									data.addColumn('number', 'Purchase');
									
									data.addRows([
										<?php 
										$x=1;
										foreach($purchaseSalesAmt as $purchaseSales): ?>
										[{v: <?=$x++;?>, f:'<?=date('M',strtotime($purchaseSales['month']));?>'}, <?php echo isset($purchaseSales['sales'])?$purchaseSales['sales']:'0'?>, <?php echo isset($purchaseSales['purchase'])?$purchaseSales['purchase']:'0'?>],
										<?php endforeach;?>
									]);
								
									var options = {
										curveType: 'function',
										pointSize :5,
										title: '',
										hAxis: {
											title: 'Month',
											titleTextStyle: {
												color: '#333'
											},
											baseline: 0,
											gridlines: {
											color: '#c9d2e0',
											count: 4
											},
											
									
											ticks: [
												<?php  $x=1;foreach($purchaseSalesAmt as $purchaseSales): ?>
												{v: <?=$x++;?>, f:'<?=date('M',strtotime($purchaseSales['month']));?>'},
												<?php endforeach;?>
											]
										},
										vAxis: {
											minValue: 0,
											gridlines: {
												color: '#c9d2e0',
												count: 5
											},
											pointSize :5 
										}
									};
									
									var chart = new google.visualization.LineChart(document.getElementById('curve_chart4'));
									chart.draw(data, options);
										google.visualization.events.addListener(chart, 'select', function() {
										var selectionIdx = chart.getSelection()[0].row;
										chart.getSelection();
										var selection = chart.getSelection();
										chart.setSelection();
										var sales = data.getValue(selectionIdx, 1);
										var purchase = data.getValue(selectionIdx, 2);
										
										var year =$("#financialyear").val();
										var splitYear = year.split('-');
										var current_year =splitYear[0];
										var next_year =splitYear[1];
										sel_idx = '';
										if(selectionIdx<9)
										{
											sel_idx = selectionIdx+3;
										}
										else 
										{
											sel_idx = selectionIdx-9;
											current_year = next_year;
										}
										sel_idx1 = data.getValue(sel_idx, 0);
										
										if(sel_idx1<10)
										{
											sel_idx1= '0'+sel_idx1;
										}
										var date = new Date(current_year+'-'+sel_idx1);
										var firstDay = new Date(date.getFullYear(), date.getMonth());
										var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
										
										var monthFormat = firstDay.getMonth()+1;
										if(monthFormat <= 9)monthFormat = '0'+monthFormat;
										
										var firstDayDate= firstDay.getDate();
										if(firstDayDate <= 9)firstDayDate = '0'+firstDayDate;
										
										var lastDayOfMonth = (lastDay.getFullYear()) + '-' + (monthFormat) + '-' + lastDay.getDate();
										var firstDayOfMonth = (firstDay.getFullYear()) + '-' + (monthFormat) + '-' + firstDayDate;
										
										if (selection[0].column==1){ 
											window.open('<?php echo PROJECT_URL; ?>' + '?page=search_sales_invoice&from_date='+firstDayOfMonth+'&to_date='+lastDayOfMonth);
										}else if (selection[0].column==2) {
											window.open('<?php echo PROJECT_URL; ?>' + '?page=search_purchase_invoice&from_date='+firstDayOfMonth+'&to_date='+lastDayOfMonth);
										} 
									});
								}
							</script>
							
                    </div>
                    
                    <div class="col-md-12 panel panel-default">
                      <div id="chart_div2"  style=" height: 300px;"></div>
                    	    <script type="text/javascript">
								google.charts.load('current', {packages: ['corechart', 'line']});
								google.charts.setOnLoadCallback(drawBasic);
								
								function drawBasic() {
								
									var data = new google.visualization.DataTable();
									data.addColumn('number', 'Month');
									data.addColumn('number', 'Sales');
									data.addColumn('number', 'Purchase');
									
									data.addRows([
										<?php $y=1; foreach($purchaseSalesInvoice as $invoices): ?>
										[{v: <?=$y++;?>, f:'<?=date('M',strtotime($invoices['month']));?>'}, <?php echo isset($invoices['sales'])?$invoices['sales']:'0'?>, <?php echo isset($invoices['purchase'])?$invoices['purchase']:'0'?>],
										<?php endforeach;?>
									]);
								
									var options = {
										curveType: 'function',
										pointSize :5,
										title: '',
										hAxis: {
											title: 'Month',
											titleTextStyle: {
												color: '#333'
											},
											baseline: 0,
											gridlines: {
											color: '#c9d2e0',
											count: 4
											},
									
											ticks: [
												<?php 
												$y=1;
												 foreach($purchaseSalesInvoice as $invoices): ?>
												{v: <?=$y++;?>, f:'<?=date('M',strtotime($invoices['month']));?>'},
												<?php endforeach;?>
											]
										},
										vAxis: {
											minValue: 0,
											gridlines: {
												color: '#c9d2e0',
												count: 5
											},
											pointSize :5 
										}
									};
									 
									
									var chart = new google.visualization.LineChart(document.getElementById('chart_div2'));
									chart.draw(data, options);
									google.visualization.events.addListener(chart, 'select', function() {
										var selectionIdx = chart.getSelection()[0].row;
										var selection = chart.getSelection();
										chart.setSelection();
										var sales = data.getValue(selectionIdx, 1);
										var purchase = data.getValue(selectionIdx, 2);
																				
										var year =$("#financialyear").val();
										var splitYear = year.split('-');
										var current_year =splitYear[0];
										var next_year =splitYear[1];
										sel_idx = '';
										
										if(selectionIdx<9)
										{
											sel_idx = selectionIdx+3;
										}
										else 
										{
											sel_idx = selectionIdx-9;
											current_year = next_year;
										}
										sel_idx1 = data.getValue(sel_idx, 0);
										
										if(sel_idx1<10)
										{
											sel_idx1= '0'+sel_idx1;
										}
										
										var date = new Date(current_year+'-'+sel_idx1);
										var firstDay = new Date(date.getFullYear(), date.getMonth());
										var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
										
										var monthFormat = firstDay.getMonth()+1;
										if(monthFormat <= 9)monthFormat = '0'+monthFormat;
										
										var firstDayDate= firstDay.getDate();
										if(firstDayDate <= 9)firstDayDate = '0'+firstDayDate;
										
										var lastDayOfMonth = (lastDay.getFullYear()) + '-' + (monthFormat) + '-' + lastDay.getDate();
										var firstDayOfMonth = (firstDay.getFullYear()) + '-' + (monthFormat) + '-' + firstDayDate;
										
										
										if (selection[0].column==1){
											window.open('<?php echo PROJECT_URL; ?>' + '?page=search_sales_invoice&from_date='+firstDayOfMonth+'&to_date='+lastDayOfMonth);
										}else if(selection[0].column==2) {
											window.open('<?php echo PROJECT_URL; ?>' + '?page=search_purchase_invoice&from_date='+firstDayOfMonth+'&to_date='+lastDayOfMonth);
										}
									});
								
									}
							</script>
                        </div>
                    <div class="col-md-12 panel panel-default">
                        <div id="chart_div3"  style=" height: 300px;"></div>
                        <?php $financialYearSplit=explode('-',$financialYearDD);
							$yearStart=$financialYearSplit[0];
							 $yearEnd=$financialYearSplit[1]; ?>
                        <script type="text/javascript">
								google.charts.load('current', {packages: ['corechart', 'line']});
								google.charts.setOnLoadCallback(drawBasic);
								
								function drawBasic() {
								
									var data = new google.visualization.DataTable();
									data.addColumn('number', 'Month');
									data.addColumn('number', 'Sales');
									data.addColumn('number', 'Purchase');
									
									data.addRows([
										<?php $z=1;
										 foreach($cancelpurchaseSalesInvoice as $cancelInvoices): ?>
										[{v: <?=$z++;?>, f:'<?=date('M',strtotime($cancelInvoices['month']));?>'}, <?php echo isset($cancelInvoices['sales'])?$cancelInvoices['sales']:'0'?>, <?php echo isset($cancelInvoices['purchase'])?$cancelInvoices['purchase']:'0'?>],
										<?php endforeach;?>
									]);
								
									var options = {
										curveType: 'function',
										pointSize: 5,
										title: '',
										hAxis: {
											title: 'Month',
											titleTextStyle: {
												color: '#333'
											},
											baseline: 0,
											gridlines: {
											color: '#c9d2e0',
											count: 4
											},
											
											ticks: [
												<?php  $z=1;
												 foreach($cancelpurchaseSalesInvoice as $cancelInvoices): ?>
												{v: <?=$z++;?>, f:'<?=date('M',strtotime($cancelInvoices['month']));?>'},
												<?php endforeach;?>
											]
										},
										vAxis: {
											minValue: 0,
											gridlines: {
												color: '#c9d2e0',
												count: 5
											},
											pointSize :5 
										}
									};
									
									var chart = new google.visualization.LineChart(document.getElementById('chart_div3'));
									chart.draw(data, options);
										google.visualization.events.addListener(chart, 'select', function() {
										var selectionIdx = chart.getSelection()[0].row;
										
										var selection = chart.getSelection();
										chart.setSelection();
										var sales = data.getValue(selectionIdx, 1);
										var purchase = data.getValue(selectionIdx, 2);
										
										var year =$("#financialyear").val();
										var splitYear = year.split('-');
										var current_year =splitYear[0];
										var next_year =splitYear[1];
										sel_idx = '';
										if(selectionIdx<9)
										{
											sel_idx = selectionIdx+3;
										}
										else 
										{
											sel_idx = selectionIdx-9;
											current_year = next_year;
										}
										sel_idx1 = data.getValue(sel_idx, 0);
										
										if(sel_idx1<10)
										{
											sel_idx1= '0'+sel_idx1;
										}
										var date = new Date(current_year+'-'+sel_idx1);
										var firstDay = new Date(date.getFullYear(), date.getMonth());
										var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
										
										var monthFormat = firstDay.getMonth()+1;
										if(monthFormat <= 9)monthFormat = '0'+monthFormat;
										
										var firstDayDate= firstDay.getDate();
										if(firstDayDate <= 9)firstDayDate = '0'+firstDayDate;
										
										var lastDayOfMonth = (lastDay.getFullYear()) + '-' + (monthFormat) + '-' + lastDay.getDate();
										var firstDayOfMonth = (firstDay.getFullYear()) + '-' + (monthFormat) + '-' + firstDayDate;
										
										if (selection[0].column==1){ 
											window.open('<?php echo PROJECT_URL; ?>' + '?page=search_sales_invoice&from_date='+firstDayOfMonth+'&to_date='+lastDayOfMonth+'&is_canceled=true');
										}else if(selection[0].column==2) {
											window.open('<?php echo PROJECT_URL; ?>' + '?page=search_purchase_invoice&from_date='+firstDayOfMonth+'&to_date='+lastDayOfMonth+'&is_canceled=true');
										}
									});
									}
							</script>
                        
                            
                    </div>
                    
                    <div class="clear height30"></div>
                </div>
            </div>
            <div class="dasfooter">Copyright @ by GST Keeper</div>
        </div>
        <div class="col-md-2 col-sm-3 col-xs-12 dash-rightnav">
            <?php include(PROJECT_ROOT . "/modules/dashboard/view/rightpanel.php"); ?>
        </div>
    </div>
	<script>
    $(document).ready(function () {
        $('#financialyear').on('change', function () {
            window.location.href = "<?php echo PROJECT_URL; ?>/?page=dashboard&overview=view&financialyear=" + $(this).val();
        });
    });
    </script>