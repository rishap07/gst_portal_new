<?php
$obj_gstr2 = new gstr2();
if(!$obj_gstr2->can_read('returnfile_list'))
{
    $obj_gstr2->setError($obj_gstr2->getValMsg('can_read'));
    $obj_gstr2->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}
$dataCurrentUserArr = $obj_gstr2->getUserDetailsById($obj_gstr2->sanitize($_SESSION['user_detail']['user_id']));
$returnmonth = date('Y-m');

?>  <div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
       			<div class="col-md-12 col-sm-12 col-xs-12">
               
               
       	<div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Reconciliation</h1></div>
        <div class="whitebg formboxcontainer">
                 
                    <!--/row-->    
                    <!--/col-12-->
<div class="col-md-6 col-sm-6 col-xs-6 padleft0">Showing 1-4 of 4</div>  
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
    <th class="active">Status</th>
  </tr>
  
   <tr>
    <td class="boldfont">Invoice/060717-001<br/><span class="table-date-txt">Inovoice Date: 29/7/2017</span></td>
    <td>27GSPMH1002G1Z4</td>
    <td>60,0000</td>
    <td>60,000</td>
    <td>6000</td>
    <td><a href="#" class="btnaccepted">Accepted</a></td>
  </tr>
  
   <tr>
    <td class="boldfont">Invoice/060717-001<br/><span class="table-date-txt">Inovoice Date: 29/7/2017</span></td>
    <td>27GSPMH1002G1Z4</td>
    <td>60,0000</td>
    <td>60,000</td>
    <td>6000</td>
    <td><a href="#" class="btnaccepted">Accepted</a></td>
  </tr>
  
   <tr>
    <td class="boldfont">Invoice/060717-001<br/><span class="table-date-txt">Inovoice Date: 29/7/2017</span></td>
    <td>27GSPMH1002G1Z4</td>
    <td>60,0000</td>
    <td>60,000</td>
    <td>6000</td>
    <td><a href="#" class="btnaccepted">Accepted</a></td>
  </tr>
</table>

                    
                    
           
</div>


</form>
        </div>   </div></div>     
<script>
    if (screen.width < 992) {
   $('[data-toggle=offcanvas]').click(function() {
    $('.row-offcanvas').toggleClass('active');
    $('.collapse').toggleClass('in').toggleClass('visible-xs').toggleClass('visible-xs');
	
});
}
else {

    $('[data-toggle=offcanvas]').click(function() {
    $('.row-offcanvas').toggleClass('active');
    $('.collapse').toggleClass('in').toggleClass('hidden-xs').toggleClass('visible-xs');
});
}
    
    </script>		