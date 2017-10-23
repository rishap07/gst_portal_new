<?php
$obj_gstr2 = new gstr2();
$obj_json = new json();
if(!$obj_gstr2->can_read('returnfile_list'))
{
    $obj_gstr2->setError($obj_gstr2->getValMsg('can_read'));
    $obj_gstr2->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}
$dataCurrentUserArr = $obj_gstr2->getUserDetailsById($obj_gstr2->sanitize($_SESSION['user_detail']['user_id']));
$returnmonth = date('Y-m');


if(isset($_REQUEST['returnmonth']) && !empty($_REQUEST['returnmonth']) && isset($_REQUEST['invoice_status']) && !empty($_REQUEST['invoice_status'])) {
	$returnmonth = $_REQUEST['returnmonth'];
	$invoice_status=$_REQUEST['invoice_status'];
} else {
	$obj_json->setError("Please choose return period");
    $obj_json->redirect(PROJECT_URL."/?page=return_client");
    exit();
}

$fianlData=$obj_json->getGst2ReconcileFinalQuery($_SESSION['user_detail']['user_id'],$returnmonth,$invoice_status);
	//echo "<pre>";print_r($fianlData);die;
?>

<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="col-md-12 col-sm-12 col-xs-12 heading">
      <h1>Reconciliation</h1>
    </div>
    <div class="whitebg formboxcontainer"> 
      
      <!--/row--> 
      <!--/col-12-->
      <div class="col-md-6 col-sm-6 col-xs-6 padleft0">Showing 1-
        <?=count($fianlData);?>
        of
        <?=count($fianlData);?>
      </div>
      <div class="col-md-6 col-sm-6 col-xs-6 text-right padrgtnone">
        <select class="selectbox">
          <option>Records 10</option>
        </select>
      </div>
      <div class="clear height20"></div>
      <div class="tableresponsive" style="overflow-x:scroll;">
        <table width="100%" border="0" cellspacing="0" cellpadding="4" class="table table-striped  tablecontent" >
          
          <thead>
            <tr>
              <th class="">#<span class="header--label"></span></th>
              <th class=""><span class="header--label">MATCH STATUS</span></th>
              <th class=""><span class="header--label">RECONCILIATION/ACTION STATUS</span></th>
             <!-- <th class=""><span class="header--label">DIFFERENCE IN TAX </span></th>-->
              <th class=""><span class="header--label">SUPPLIER GSTIN</span></th>
              <th class=""><span class="header--label">PLACE OF SUPPLY</span></th>
              <th class=""><span class="header--label">INVOICE NUMBER</span></th>
              <th class=""><span class="header--label">Date</span></th>
              <th class=""><span class="header--label">TOTAL TAXABLE VALUE </span></th>
              <th class=""><span class="header--label">TOTAL CST </span></th>
              <th class=""><span class="header--label">TOTAL IGST </span></th>
              <th class=""><span class="header--label">TOTAL SGST </span></th>
              <th class=""><span class="header--label">TOTAL CESS </span></th>
              <th class=""><span class="header--label">TOTAL AMT </span></th>
              <!--<th class=""><span class="header--label">REVERSE CHARGE</span></th>-->
            </tr>
          </thead>
          <?php
		  $count=1;
		   if(count($fianlData)>0){
	  foreach($fianlData as $finaReconcile): 
	  $cgst_amount=explode(',',$finaReconcile['total_cgst_amount']);
	  $sgst_amount=explode(',',$finaReconcile['total_sgst_amount']);
	  $igst_amount=explode(',',$finaReconcile['total_igst_amount']);
	  $cess_amount=explode(',',$finaReconcile['total_cess_amount']);
	  $ctin=explode(',',$finaReconcile['company_gstin_number']);
	//echo "<pre>"; print_r($fianlData); die;
	  ?>
          <tr>
          <td><?=$count++;?></td>
          <td>Pending</td>
         <?php /*?> <td><?=$count++;?></td><?php */?>
          <td></td>
          <td><?=$ctin[0]?></td>
          <td><?=$finaReconcile['pos']?></td>
          <td><?=$finaReconcile['reference_number']?></td>
          <td><?=$finaReconcile['invoice_date']?></td>
          <td><?=$finaReconcile['total_taxable_subtotal'];?></td>
          <td><?=$cgst_amount[0];?></td>
          <td><?=$sgst_amount[0];?></td>
          <td><?=$igst_amount[0];?></td>
          <td><?=$cess_amount[0];?></td>
          <td><?=$finaReconcile['invoice_total_value'];?></td>
         
          </tr>
          <?php
  endforeach;
   }else
 	 {
	  echo " <tr><th colspan='5'><h4>No Data found <h4></th></tr>";
	}?>
        </table>
      </div>
      </form>
    </div>
  </div>
</div>
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