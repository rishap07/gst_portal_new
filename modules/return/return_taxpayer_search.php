<?php
//session_destroy();
$obj_gstr = new gstr();
if(!$obj_gstr->can_read('returnfile_list'))
{
    $obj_gstr->setError($obj_gstr1->getValMsg('can_read'));
    $obj_gstr->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}

if((isset($_POST['search_gstin']) && $_POST['search_gstin']=='Search'))
{
    $gstin = isset($_POST['gstin'])?$_POST['gstin']:'';
    if ($gstin != '') {
    	$response =  $obj_gstr->commonApiAuthenticationWithTpSearch($gstin);
        if(empty($response)) {
        	$obj_gstr->setError('Sorry! Details not found for GSTIN: '.$gstin);
        }
    }
    else {
        $obj_gstr->setError('Sorry! Please enter GSTIN/UIN of the taxpayer');
    }
    
}

?>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12 heading">
	<h1>Search Taxpayer</h1>
	</div>

</div>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">

		<?php $obj_gstr->showErrorMessage(); ?>
		<?php $obj_gstr->showSuccessMessge(); ?>
		<?php $obj_gstr->unsetMessage(); ?>
	</div>
</div>
<div class="row">
	<form  method="post" name="form1" >						
	<div class="col-md-4 col-sm-4 col-xs-12 form-group" >
		<label>GSTIN/UIN of the taxpayer <span class="starred">*</span></label>
		<input type="text" placeholder="GSTIN/UIN of the taxpayer" name="gstin" id="gstin" class="form-control" value="" data-bind="content" />
		<span style="font-size: 12px;">Demo GSTIN: 07GDEPS8617C3ZA</span><br>
	</div>
	<div class="col-md-4 col-sm-4 col-xs-12 form-group" >
		<br>
		<input type="submit" name="search_gstin" id="search_gstin" class="btn btn-primary" value="Search"  />
	
	</div>

	</form>
</div>
<?php
$getSummary= isset($response)?$response:'';
if(!empty($getSummary)) {
	$jstr1_array = json_decode($getSummary,true);

	if(!empty($jstr1_array)) {
		$ctb= isset($jstr1_array['ctb'])?$jstr1_array['ctb']:'';
		$rgdt= isset($jstr1_array['rgdt'])?$jstr1_array['rgdt']:'';

		$sts= isset($jstr1_array['sts'])?$jstr1_array['sts']:'';
		$stj= isset($jstr1_array['stj'])?$jstr1_array['stj']:'';

		$ctj= isset($jstr1_array['ctj'])?$jstr1_array['ctj']:'';
		$dty= isset($jstr1_array['dty'])?$jstr1_array['dty']:'';

		$lgnm= isset($jstr1_array['lgnm'])?$jstr1_array['lgnm']:'';
		$cxdt= isset($jstr1_array['cxdt'])?$jstr1_array['cxdt']:'';
		$nbas= isset($jstr1_array['nba'])?$jstr1_array['nba']:'';

		$gstin= isset($jstr1_array['gstin'])?$jstr1_array['gstin']:'';


		?>
		<div class="clear"></div><br/>
		<div class="row">
		    <div class="col-md-12 " >
				<?php echo '<b><span style="color: #2F6492;">Search result based on GSTIN/UIN : '.$gstin.'</span></b>' ;?>
			</div>
		</div>
		<div class="clear"></div><br/>
		<div class="row">
		    <div class="col-md-12">
		        <!-- Nav tabs -->
		        <ul class="nav nav-tabs" role="tablist">
		            <li class="active"><a href="#profile" role="tab" data-toggle="tab">Profile</a></li>
		            <li><a href="#Business" role="tab" data-toggle="tab">Place of Business</a></li>
		        </ul>
		    </div>
		    <div class="col-md-12">
			    <!-- Tab panes + Panel body -->
			    <div class="tab-content">
			        <div class="tab-pane active" id="profile">
			        	<div class="clear"></div><br/>
				        <div class="row">
					        <div class="col-md-4">
					        	<span>Legal name of business</span><br>
								<b><?php echo $lgnm;?> </b>
					        	
					        </div>
					        <div class="col-md-4">
					        	<span>GSTIN/UIN</span><br>
								<b><?php echo $gstin;?> </b><br>
					        	
					        </div>
					        <div class="col-md-4">
					        	<span>Center Jurisdiction</span><br>
								<b><?php echo $ctj;?> </b><br>
					        	
					        </div>
				        </div>
				        <div class="clear"></div><br/>
				        <div class="row">
					        <div class="col-md-4">
					        	<span>State Jurisdiction</span><br>
								<b><?php echo $stj;?> </b><br>
					        	
					        </div>
					        <div class="col-md-4">
					        	<span>Date Of Registration</span><br>
								<b><?php echo $rgdt;?> </b><br>
					        	
					        </div>
					        <div class="col-md-4">
					        	<span>Constitution of Business</span><br>
								<b><?php echo $ctb;?> </b><br>
					    	</div>    	
				        </div>
				        <div class="clear"></div><br/>
				        <div class="row">
					        <div class="col-md-4">
					        	<span>GSTIN/UIN Status</span><br>
								<b><?php echo $sts;?> </b><br>
					        	
					        </div>
					        <div class="col-md-4">
					        	<span>Date of Cancellation</span><br>
								<b><?php echo $gstin;?> </b><br>
					        	
					        </div>
					        <div class="col-md-4">
					        	<span>Taxpayer Type</span><br>
								<b><?php echo $dty;?> </b><br>
					        	
					        </div>
				        </div>
				        <div class="clear"></div><br/>

				        <div class="row">
					        <div class="col-md-4">
					        	<span>Compaliance Rating</span><br>
								<b><?php echo 'NA';?> </b><br>
					        	
					        </div>
				        </div>
				        <div class="clear"></div><br/>
				        <hr/>
				        <div class="row">
					        <div class="col-md-6 col-sm-6 col-xs-6">
					            <div class="panel panel-default">
					                <div class="panel-heading">
					                    <span class="panel-title">Name of Proprietor / Director/ Promoter(s)</span>
					                    <span class="pull-right"><i class="fa fa-angle-up"></i></span>
					                </div>
					                <div class="panel-body">
					                    <?php echo 'NA'; ?>
					                </div>
					            </div>  
					        </div>
					        <div class="col-md-6 col-sm-6 col-xs-6">
					            <div class="panel panel-default">
					                <div class="panel-heading">
					                    <span class="panel-title">Nature of Business of Activities</span>
					                    <span class="pull-right "><i class="fa fa-angle-up"></i></span>
					                </div>
					                <div class="panel-body">
					                    <?php if(!empty($nbas)) {
					                    	$i=1;
					                    	foreach ($nbas as $key => $nba) {
					                    		echo $i. ' '.$nba;
					                    		$i++;
					                    	}
					                    }
					                    else {
					                    	echo 'NA';
					                    }
					                    ?>
					                </div>
					            </div>  
					        </div>
				        </div>
			        </div>
			        <div class="tab-pane" id="Business">
			        NA
			        </div>
			    </div>
		    </div>
		</div>
		<?php 
	}

}
?>


