<?php
$obj_tally = new tally();
$excelError = false;
$returnMessage = '';

if(!$obj_tally->can_read('client_invoice')) {

    $obj_tally->setError($obj_tally->getValMsg('can_read'));
	$obj_tally->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}

if(!$obj_tally->can_create('client_invoice')) {

	$obj_tally->setError($obj_tally->getValMsg('can_create'));
	$obj_tally->redirect(PROJECT_URL."/?page=client_invoice_list");
	exit();
}
$returnmonth = '2017-07';
if(isset($_POST["generate"]) && $_POST["generate"]=='Generate invoice data')
{
	echo "working";die;

$flag=$ids=$type='';

$obj_tally->insertIntoGstr1Table($_SESSION['user_detail']['user_id'],$returnmonth);
}
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr">
    <div class="col-md-11 col-sm-12 col-xs-12 mobpadlr">
        <div class="col-md-12 col-sm-12 col-xs-12 heading">
	
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="col-md-6 col-sm-12 col-xs-12"><h3>GSTR1 Summary</h3></div>
                </div>
			
                <div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
				
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="whitebg formboxcontainer">
						<div class="tab col-md-12 col-sm-12 col-xs-12">			
              
                         
                            <div class="pull-right rgtdatetxt">
                                <form method='post' name='form2'>
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
                            </div> </div>
							
						 <input type="submit" value="Generate invoice data" name="generate"  class="btn btn-success" />
				        
                            <?php $obj_tally->showErrorMessage(); ?>
                            <?php $obj_tally->showSuccessMessge(); ?>
                            <?php $obj_tally->unsetMessage(); ?>
                            <div class="clearfix height80"></div>
                          
                                
                            </div>  
                        </div>
                    </div>
					</form>
                </div>
            </div>
        </div>
   


