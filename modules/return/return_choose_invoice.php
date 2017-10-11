<?php
$obj_tally = new tally();
$excelError = false;
$returnMessage = '';

if(!$obj_tally->can_read('returnfile_list')) {

    $obj_tally->setError($obj_tally->getValMsg('can_read'));
	$obj_tally->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}

if(!$obj_tally->can_create('returnfile_list')) {

	$obj_tally->setError($obj_tally->getValMsg('can_create'));
	$obj_tally->redirect(PROJECT_URL."/?page=client_invoice_list");
	exit();
}
if (isset($_POST['returnmonth'])) {
    $returnmonth = $_POST['returnmonth'];
    $obj_tally->redirect(PROJECT_URL . "/?page=return_choose_invoice&returnmonth=" . $returnmonth);
    exit();
}
$returnmonth = date('Y-m');
if (isset($_REQUEST['returnmonth']) && $_REQUEST['returnmonth'] != '') {
    $returnmonth = $_REQUEST['returnmonth'];
}
if (isset($_POST['returnmethod']) && $_POST['returnmethod'] != '') {
    $returnmethod = $_POST['returnmethod'];
	if($returnmethod==1)
	{
		//$obj_tally->redirect(PROJECT_URL."/?page=tally_generate_gstr1_summary&returnmonth=" . $returnmonth);
	}
	else if($returnmethod==2)
	{
		$obj_tally->redirect(PROJECT_URL."/?page=tally_import_invoice&returnmonth=" . $returnmonth);
	}else{
		$obj_tally->redirect(PROJECT_URL."/?page=tally_choose_invoice&returnmonth=" . $returnmonth);
	}
}
if(isset($_POST["generate"]) && $_POST["generate"]=='Generate invoice data')
{
	//$flag=$ids=$type='';
	if($obj_tally->insertIntoGstr1Table($_SESSION['user_detail']['user_id'],$returnmonth))
	{
		$obj_tally->setSuccess("Your invoices are imported. Kindly move to setp-2");
		$obj_tally->redirect(PROJECT_URL."/?page=return_choose_invoice&returnmonth=" . $returnmonth);
		exit();
	}
}

?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Choose GSTR-1 Return upload method</h1></div>
	<div class="whitebg formboxcontainer">
			
			<?php $obj_tally->showErrorMessage(); ?>
			<?php $obj_tally->showSuccessMessge(); ?>
			<?php $obj_tally->unsetMessage(); ?>

			
			<div class="clear"></div>
			<div class="tab col-md-12 col-sm-12 col-xs-12 form-group">
			 <div class="pull-right rgtdatetxt">
             <form method='post' name='form3'>
				Month Of Return
                                    <?php
                                    $dataQuery = "SELECT DATE_FORMAT(invoice_date,'%Y-%m') AS niceDate FROM gst_client_invoice group by nicedate ";
                                    $dataRes = $obj_tally->get_results($dataQuery);
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
                            </div></div>
		      <div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12 form-group">
			<div class="col-md-6 col-sm-6 col-xs-12 form-group">
            <h3>Step-1</h3> Any new invoice update kindly follow below steps : -</br></br>			
			<form name="form2" id="form2" method="POST" enctype="multipart/form-data">
				
					<div class="col-md-6 col-sm-6 col-xs-12 form-group">
						
					  <select class="required form-control" id="returnmethod" name="returnmethod">
							<option value=''>Select Return Method</option>
							<option value='1'>Using Invoices</option>
							<option value='2'>Using Tally</option>			

							
						</select>
					</div>
			<div class="col-md-4 col-sm-4 col-xs-12 form-group" style="display:none;" id="divgenerate">
						<form method="post" id="form" name="form4">	
						 <input type="submit" value="Generate invoice data" name="generate"  class="btn btn-success" />
				        </form>
						</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12 form-group">
			<h3>Step-2</h3> If you have already done step-1 then click here to move on step-1 </br></br>
			<input type="button" value="<?php echo ucfirst('Go to return summary'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=return_summary&returnmonth=".$_REQUEST["returnmonth"]; ?>';" class="btn btn-success" class="redbtn marlef10"/>
				</div>
				
				</div>	
					
					<div class="clear"></div>

					
				</div>
			</form>
		</div>
	</div>
</div>
<script>
    $(document).ready(function () {
        $('#returnmethod').on('change', function () {
			if(document.getElementById("returnmethod").value==1)
			{
				document.getElementById('divgenerate').style.display = 'block';
			}
			else{
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_choose_invoice&returnmonth=<?php echo $returnmonth; ?>';
            document.form2.submit();
			}
                    });
                });
</script>
<script>
    $(document).ready(function () {
        $('#returnmonth').on('change', function () {
            document.form3.action = '<?php echo PROJECT_URL; ?>/?page=return_choose_invoice&returnmonth=<?php echo $returnmonth; ?>';
                        document.form3.submit();
                    });
                });
</script>
<script>
    $(document).ready(function () {
		
		/* Tally Return Period */
		$("#returnmethod").select2();

        $('#submit').click(function () {
            var mesg = {};
            if (vali.validate(mesg,'import-tally-invoice')) {
				return true;
            }
            return false;
        });
    });
</script>


