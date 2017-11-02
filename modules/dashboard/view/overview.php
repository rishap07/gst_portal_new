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
$cancelPurchaseSalesInv=$obj_graph->testDemoQuery('2017-07');
//$obj_graph->pr($cancelpurchaseSalesInvoice,true);
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
             <?php  
				   echo $profitloss= number_format($sales-$purchase,2);
			 ?>
            
            </span><br />
              <div class="txtyear"><?php if($sales>$purchase) {?>
              	<span class="text-default">Profit</span>
				<?php }else{ ?><span class="text-danger">Loss</span><?php }?>
               <br /> (A - B)
                </div>
            </div>
          </div>
        </div>
        <div class="col-md-2 col-sm-2 col-xs-2 pull-right">
          <label>Financial Year</label>
          <form method='post' name='getOverview' id="getOverview">
            <select class="form-control" id="financialyear" name="financialyear">
              <?php  for($z=2017; $z<=$financialYear; $z++){ ?>
              <option value="<?php echo $financialYear ?>" <?php if($financialYearDD==$financialYear ){echo 'selected=selected';} ?>><?php echo $financialYear ?></option>
              <?php }?>
            </select>
          </form>
        </div>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <div class="col-md-12 panel panel-default">
           	<div id="curve_chart" style=" height: 300px;"></div>
			<script>
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawVisualization);
                
                function drawVisualization() {
                // Some raw data (not necessarily accurate)
                var data = google.visualization.arrayToDataTable([
					['Month', 'Sales', 'Pruchase'],
					<?php foreach($purchaseSalesAmt as $purchaseSales): ?>
					['<?php echo date('M',strtotime($purchaseSales['month']));?>',
					<?php echo isset($purchaseSales['sales'])?$purchaseSales['sales']:'0'?>,
					<?php echo isset($purchaseSales['purchase'])?$purchaseSales['purchase']:'0'?>,],
					<?php endforeach;?>
                ]);
                
                var options = {
					title : 'Cashflow',
					vAxis: {title: 'Amount in Rs'},
					hAxis: {title: 'Month'},
					seriesType: 'bars',
					series: {5: {type: 'line'}}
                };
                
                var chart = new google.visualization.ComboChart(document.getElementById('curve_chart'));
                chart.draw(data, options);
                }
                </script> 
        </div>
        <div class="col-md-12 panel panel-default">
      		<div id="chart_div2"  style=" height: 300px;"></div>
			<script>
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawVisualization);
                
                function drawVisualization() {
                // Some raw data (not necessarily accurate)
                var data = google.visualization.arrayToDataTable([
                    ['Month', 'Sales', 'Pruchase'],
                    <?php foreach($purchaseSalesInvoice as $invoices): ?>
                    ['<?php echo date('M',strtotime($invoices['month']));?>',
                    <?php echo isset($invoices['sales'])?$invoices['sales']:'0'?>, 
                    <?php echo isset($invoices['purchase'])?$invoices['purchase']:'0'?>],
                    <?php endforeach;?>
                    ]);
                
                var options = {
                    title : 'Invoices',
                    vAxis: {title: 'No of Invoices'},
                    hAxis: {title: 'Month'},
                    seriesType: 'bars',
                    series: {5: {type: 'line'}}
                    };
                
                var chart = new google.visualization.ComboChart(document.getElementById('chart_div2'));
                    chart.draw(data, options);
                }
            </script> 
        </div>
        <div class="col-md-12 panel panel-default">
       		<div id="chart_div3"  style=" height: 300px;"></div>
        	<script>
					google.charts.load('current', {'packages':['corechart']});
					google.charts.setOnLoadCallback(drawVisualization);

					function drawVisualization() {
						// Some raw data (not necessarily accurate)
						var data = google.visualization.arrayToDataTable([
							['Month', 'Sales', 'Pruchase'],
							<?php foreach($cancelpurchaseSalesInvoice as $cancelInvoices): ?>
							['<?php echo date('M',strtotime($cancelInvoices['month']));?>',
							<?php echo isset($cancelInvoices['sales'])?$cancelInvoices['sales']:'0'?>, 
							<?php echo isset($cancelInvoices['purchase'])?$cancelInvoices['purchase']:'0'?>],
							<?php endforeach;?>
						]);
						
						var options = {
							title : 'Cancel Invoices',
							vAxis: {title: 'No of Invoices'},
							hAxis: {title: 'Month'},
							seriesType: 'bars',
							series: {5: {type: 'line'}}
						};
						
						var chart = new google.visualization.ComboChart(document.getElementById('chart_div3'));
						chart.draw(data, options);
						
						var selectHandler = function(e) {
							window.location = data.getValue(chart.getSelection()[0]['row'], 1 );
						}
						google.visualization.events.addListener(chart, 'select', selectHandler);
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
<!--CONTENT START HERE-->
</div>
</div>
<script>
$(document).ready(function () {
	$('#financialyear').on('change', function () {
		window.location.href = "<?php echo PROJECT_URL; ?>/?page=dashboard&overview=view&financialyear=" + $(this).val();
	});
});
</script>