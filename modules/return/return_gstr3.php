<?php
$obj_client = new client();
$returnmonth = date('Y-m');
if(isset($_POST['returnmonth']))
{
    $returnmonth = $_POST['returnmonth'];
	$obj_client->redirect(PROJECT_URL."/?page=return_client&returnmonth=".$returnmonth);
	exit();
}
$returnmonth= date('Y-m');
if(isset($_REQUEST['returnmonth']) && $_REQUEST['returnmonth'] != '')
{
    $returnmonth= $_REQUEST['returnmonth'];
}
$time = strtotime($returnmonth."-01");
$month = date("M", strtotime("+1 month", $time));
?>
      
          
       <div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
       			<div class="col-md-12 col-sm-12 col-xs-12">
               
                	<div class="col-md-6 col-sm-6 col-xs-12 heading"><h1>GSTR-2 Filing</h1></div>
                    <div class="col-md-6 col-sm-6 col-xs-12 text-right breadcrumb-nav"><a href="#">Home</a>  <i class="fa fa-angle-right" aria-hidden="true"></i>  <a href="#">File Return</a> <i class="fa fa-angle-right" aria-hidden="true"></i> <span class="active">GSTR-2 Filing</span> </div>
                     <div class="whitebg formboxcontainer">
                     <div class="text-right"><a href="#" class="btngreen"><i class="fa fa-cloud-download" aria-hidden="true"></i> Download GSTR2</a> <a href="#" class="btngreen"><i class="fa fa-upload" aria-hidden="true"></i> Upload GSTR 2A</a></div>
                    	<div class="col-md-12 col-sm-12 col-xs-12 tablistnav padleft0">
                        	<ul>
                            	<li><a href="#" class="active">View GSTR2 Summary</a></li>
                                 <li><a href="#"> View My Data</a></li>
                                  <li><a href="#">Vendor Invoices</a></li>
                                  <li><a href="#">Match & Reconcile</a></li>
                                  <li><a href="#">Claim ITC</a></li>
                                 <li><a href="#">GSTR-2 Filing</a></li>
                            </ul>
                            </div>
                           <div class="tableresponsive">
                            <table  class="table  tablecontent tablecontent2">
                                <thead>
                                <tr>
                                <th>TYPE OF INVOICE</th>
                                <th>NO. INVOICES</th>
                                <th>TAXABLE AMT</th>
                                <th class="text-right">TAX AMT</th>
                                <th class="text-right">TOTAL AMT INCL. TAX</th>
                                <th class=""></th></tr>
                                </thead>
                                <tbody>
                                <tr><td colspan="10">No Invoices </td></tr>
                                </tbody>
                            </table>
                        </div>
                        </div> 
                    
       	  </div>
 		 <div class="clear height40"></div>      
    </div>
  <div class="clear"></div>
       
    <script>
    if (screen.width < 992) {
   $('[data-toggle=offcanvas]').click(function() {
    $('.row-offcanvas').toggleClass('active');
    $('.collapse').toggleClass('in').toggleClass('visible-xs').toggleClass('visible-xs');
	$("collapsed").hasClass("<i 
});
}
else {

    $('[data-toggle=offcanvas]').click(function() {
    $('.row-offcanvas').toggleClass('active');
    $('.collapse').toggleClass('in').toggleClass('hidden-xs').toggleClass('visible-xs');
});
}

$(".collapsed").children(".navrgtarrow");
    
    </script>   
        