<?php

$obj_return = new gstr3b();
$returnmonth = date('Y-m');
if(!$obj_return->can_read('returnfile_list'))
{
    $obj_return->setError($obj_return->getValMsg('can_read'));
    $obj_return->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}
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
							
  
  <div class="panel-group">
    <div class="panel panel-default">
      <div class="panel-heading">File GSTR-3B Return</div>
      <div class="panel-body" style="text-align:center;">
<p>For further process submit and offset liability.</p>
<p>Please Click&nbsp;<a href="https://services.gst.gov.in/services/login" target="_blank" >here</a>&nbsp;to go to government portal.</p>
<p>Also, for your convenience we have added an excel <a href="<?php echo PROJECT_URL . '/?page=return_gstr3b_file&returnmonth='.$returnmonth ?>">export</a> option to make the filing easy.</p></div>
    </div>
       
 
</div>
                    
        <div class="clear"></div>  	
