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

if(isset($_POST['submit']) && $_POST['submit']=='submit') {

    if($obj_return->saveGstr3b()){
        //$obj_master->redirect(PROJECT_URL."/?page=master_receiver");
    }
}


	    
	   ?>
       <div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
       		
               
                	<div class="col-md-6 col-sm-6 col-xs-12 heading"><h1>Transition Form</h1></div>
                    <div class="col-md-6 col-sm-6 col-xs-12 text-right breadcrumb-nav"><a href="#">Home</a>
					<i class="fa fa-angle-right" aria-hidden="true"></i>  <a href="#">File Return</a> <i class="fa fa-angle-right" aria-hidden="true"></i> <span class="active">GSTR-Transition</span> </div>
                     <div class="whitebg formboxcontainer">
				<?php $obj_return->showErrorMessage(); ?>
				<?php $obj_return->showSuccessMessge(); ?>
				<?php $obj_return->unsetMessage(); ?>
				
					  
							
  
  <div class="panel-group">
    <div class="panel panel-default">
      <div class="panel-heading">Transition Form</div>
      <div class="panel-body" style="text-align:center;"><p>Transition form1 coming soon.</p>For more details download the <a target="_blank" href="<?php echo PROJECT_URL .'/upload/Transitionforms.pdf'?>">Pdf</a></p>
</div>
    </div>
       
 
</div>
                    
        <div class="clear"></div>  	
