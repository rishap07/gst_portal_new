<?php

$obj_return = new gstr3b();
$returnmonth = date('Y-m');

if(isset($_POST['returnmonth']))
{
    $returnmonth = $_POST['returnmonth'];
	$obj_return->redirect(PROJECT_URL."/?page=return_gstr3b_file&returnmonth=".$returnmonth);
	exit();
}
$returnmonth= date('Y-m');
if(isset($_REQUEST['returnmonth']) && $_REQUEST['returnmonth'] != '')
{
    $returnmonth= $_REQUEST['returnmonth'];
}
$returnmonth = date('Y-m');
if ($_REQUEST['returnmonth'] != '') {
    $returnmonth = $_REQUEST['returnmonth'];
}
if(isset($_POST['submit']) && $_POST['submit']=='submit') {

    if($obj_return->saveGstr3b()){
        //$obj_master->redirect(PROJECT_URL."/?page=master_receiver");
    }
}


	    
	   ?>
       <div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
       		
               
                	<div class="col-md-6 col-sm-6 col-xs-12 heading"><h1>GSTR-3B</h1></div>
                    <div class="col-md-6 col-sm-6 col-xs-12 text-right breadcrumb-nav"><a href="#">Home</a>
					<i class="fa fa-angle-right" aria-hidden="true"></i>  <a href="#">File Return</a> <i class="fa fa-angle-right" aria-hidden="true"></i> <span class="active">GSTR-3B Filing</span> </div>
                     <div class="whitebg formboxcontainer">
				<?php $obj_return->showErrorMessage(); ?>
				<?php $obj_return->showSuccessMessge(); ?>
				<?php $obj_return->unsetMessage(); ?>
				<div class="tab">
                <a href="<?php echo PROJECT_URL . '/?page=return_gstr3b_file&returnmonth='.$returnmonth ?>" >
                    Prepare GSTR-3B 
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_filegstr3b_file&returnmonth='.$returnmonth ?>" class="active">
                    File GSTR-3B
                </a>
              
            </div>
					  <div class="pull-right rgtdatetxt">
                                <form method='post' name='form2'>
                                    Month Of Return
                                    <?php
                                    $dataQuery = "SELECT DATE_FORMAT(invoice_date,'%Y-%m') AS niceDate FROM " . $db_obj->getTableName('client_invoice') . " group by nicedate";
                                    $dataRes = $obj_return->get_results($dataQuery);
                                    if (!empty($dataRes)) {
                                        ?>
                                        <select class="dateselectbox" id="returnmonth" name="returnmonth">
                                        <?php
                                        foreach ($dataRes as $dataRe) {
                                            ?>
                                                <option value="<?php echo $dataRe->niceDate; ?>" <?php if ($dataRe->niceDate == $returnmonth) { echo 'selected'; } ?>><?php echo $dataRe->niceDate; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <?php
                                    } else {
                                        ?>
                                        <select class="dateselectbox" id="returnmonth" name="returnmonth">
                                            <option>July 2017</option>
                                        </select>
                                    <?php }
                                    ?>
                                </form>
                            </div><div class="clear"></div>
							<div class="container">
  
  <div class="panel-group">
    <div class="panel panel-default">
      <div class="panel-heading">File GSTR-3B Return</div>
      <div class="panel-body" style="text-align:center;"><p>Please proceed to&nbsp;<a href="#">Government portal</a> to file your return.<br>
Simply copy paste your values from GSTKeeper to the government portal.</p>
<p>The Government has not yet provided facility for software providers (ASP/GSP) to enable filing</p><p> of GSTR-3B return. Please note this is applicable only for GSTR-3B return. For GSTR-1 and GSTR-2</p><p> for July tax period you will be able to file return in September from GSTKeeper.</p></div>
    </div>

    

    

   
    
    
  </div>
</div>
                    
        <div class="clear"></div>  	
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-row1").click(function(){
       
			var data1 ='<select class="required form-control" id="place_of_supply_unregistered_person"  name="place_of_supply_unregistered_person[]">';
			 var data='';
			 data +=<?php $dataSupplyStateArrs = $obj_return->get_results("select * from ".$obj_return->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
						<?php if(!empty($dataSupplyStateArrs)) { ?>
							data += '<option value="">Select Place of Supply</option>';
							<?php foreach($dataSupplyStateArrs as $dataSupplyStateArr) { ?>
								data += '<option value="<?php echo $dataSupplyStateArr->state_id; ?>"><?php echo $dataSupplyStateArr->state_name; ?></option>';
							<?php } ?>
						<?php } ?>
							
			data = data1+ data+'</select>';
		
		    
            var markup = "<tr><td><input type='hidden' name='record'></td><td>" + data + "</td><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='total_taxable_value_unregistered_person[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='amount_of_integrated_tax_unregistered_person[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table1').append(markup);
        });
		$('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});
        
		 
        
    }); 

</script>
<script>

</script>
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-row2").click(function(){
       
			var data1 ='<select class="required form-control" id="place_of_supply_taxable_person"  name="place_of_supply_taxable_person[]">';
			 var data='';
			 data +=<?php $dataSupplyStateArrs = $obj_return->get_results("select * from ".$obj_return->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
						<?php if(!empty($dataSupplyStateArrs)) { ?>
							data += '<option value="">Select Place of Supply</option>';
							<?php foreach($dataSupplyStateArrs as $dataSupplyStateArr) { ?>
								data += '<option value="<?php echo $dataSupplyStateArr->state_id; ?>"><?php echo $dataSupplyStateArr->state_name; ?></option>';
							<?php } ?>
						<?php } ?>
							
			data = data1+ data+'</select>';
		
		    
            var markup = "<tr><td><input type='hidden' name='record'></td><td>" + data + "</td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='total_taxable_value_taxable_person[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='amount_of_integrated_tax_taxable_person[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table2').append(markup);
        });
        
		 
      $('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});
    });    
</script>
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-row3").click(function(){
       
			var data1 ='<select class="required form-control" id="place_of_supply_uin_holder"  name="place_of_supply_uin_holder[]">';
			 var data='';
			 data +=<?php $dataSupplyStateArrs = $obj_return->get_results("select * from ".$obj_return->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
						<?php if(!empty($dataSupplyStateArrs)) { ?>
							data += '<option value="">Select Place of Supply</option>';
							<?php foreach($dataSupplyStateArrs as $dataSupplyStateArr) { ?>
								data += '<option value="<?php echo $dataSupplyStateArr->state_id; ?>"><?php echo $dataSupplyStateArr->state_name; ?></option>';
							<?php } ?>
						<?php } ?>
							
			data = data1+ data+'</select>';
		
		    
            var markup = "<tr><td><input type='hidden' name='record'></td><td>" + data + "</td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='total_taxable_value_uin_holder[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='amount_of_integrated_uin_holder[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table3').append(markup);
        });
         $('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});
		 
        
    });    
</script>
<script>
    $(document).ready(function () {
        $('#returnmonth').on('change', function () {
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_gstr3b_file&returnmonth=<?php echo $returnmonth; ?>';
                        document.form2.submit();
                    });
                });
</script>

  <script type="text/javascript">
        $(document).ready(function() {
           // $('#place_of_supply_unregistered_person').multiselect();
        });
   </script>
    <script type="text/javascript">
        $(document).ready(function() {
          //  $('#place_of_supply_taxable_person').multiselect();
        });
   </script>
    <script type="text/javascript">
        $(document).ready(function() {
          //  $('#place_of_supply_uin_holder').multiselect();
        });
   </script>
  
<script>
    $(document).ready(function () {
        $('#returnmonth').on('change', function () {
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_gstr3b_file&returnmonth=<?php echo $returnmonth; ?>';
                        document.form2.submit();
                    });
                });
</script>

       
 <script>
	$(document).ready(function () {
		
		/* select2 js for state */
		//$("#place_of_supply_unregistered_person").select2();
	
	
	});
</script>
 
        <script type="text/javascript">
        function isNumberKey(evt)
      {
         
        var charCode = (evt.which) ? evt.which : event.keyCode
                
        if ((charCode >= 40) && (charCode <= 57) &&(charCode!=47) &&(charCode!=42) && (charCode!=43) && (charCode!=44) && (charCode!=45) || (charCode == 8))
       {
       return true;
           
       }
    else
    {
     return false;

     }
	  }
</script>