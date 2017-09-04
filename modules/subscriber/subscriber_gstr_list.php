<?php
$obj_client = new client();
if(isset($_REQUEST['returnmonth']) && $_REQUEST['returnmonth'] != '')
{
    $returnmonth= $_REQUEST['returnmonth'];
	
}
else
{
	
	$dataQuery = "SELECT DATE_FORMAT(invoice_date,'%Y-%m') AS niceDate FROM gst_client_invoice group by nicedate ORDER by invoice_date desc LIMIT 0,1";
	$dataRes = $obj_client->get_results($dataQuery);
	if(!empty($dataRes))
	{
	$returnmonth=$dataRes[0]->niceDate;
	}	
}
if (isset($_POST['returnmonth'])) {
    $returnmonth = $_POST['returnmonth'];
	
    $obj_client->redirect(PROJECT_URL . "/?page=subscriber_gstr_list&returnmonth=" . $returnmonth);
    exit();
}

 ?>
<style>
    table, th, td {
/*   border: 1px solid black;
   text-align: justify;
    padding: 12px;*/
}
    </style>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr">
    <div class="col-md-12 col-sm-11 col-xs-12 mobpadlr">
        <div class="clear"></div>
        <?php $db_obj->showErrorMessage(); ?>
        <?php $db_obj->showSuccessMessge(); ?>
        <?php $db_obj->unsetMessage(); ?>
        <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>GSTR Uploaded Summary</h1></div>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class=" whitebg dashleftbox">
            
				  <div class="pull-right rgtdatetxt">
                 
                <form method='post' name='form2'>
				Month Of Return
				<?php
				
				$dataQuery = "SELECT DATE_FORMAT(invoice_date,'%Y-%m') AS niceDate FROM gst_client_invoice group by nicedate";
				$dataRes = $obj_client->get_results($dataQuery);
				if(!empty($dataRes))
				{
					?>
					<select class="dateselectbox" id="returnmonth" name="returnmonth">
						<?php
						foreach($dataRes as $dataRe)
						{
							?>
							<option value="<?php echo $dataRe->niceDate;?>" <?php if($dataRe->niceDate == $returnmonth){ echo 'selected';}?>><?php echo $dataRe->niceDate;?></option>
							<?php
						}
						?>
						
					</select>
					<?php
				}else
				{
				?>
                <select class="dateselectbox" id="returnmonth" name="returnmonth">
                    <option>July 2017</option>
                </select>
				<?php }
				?>
				</form>
            </div>
                <div class="border1"></div>
                <div class="listcontent">
                    <?php $dataClientArr = $db_obj->getClient("user_id,user_group,vendor_type, CONCAT(first_name,' ',last_name) as name, username, u.email, company_name,k.gstin_number, (case when status='1' Then 'active' when status='0' then 'deactive' end) as status", "u.is_deleted='0' AND u.added_by='" . $_SESSION['user_detail']['user_id'] . "'"); ?>

                   <table class=" table table-bordered table-hover ">
                        <tr>
                         <th>User Name</th>   
                        <th>Company Name</th>
                        <th> Gstn No.</th>
						<th> GSTR1 Status</th>
						<th> GSTR2 Status</th>
						<th> GSTR3 Status</th>
						
						
                       
                        </tr>
                         <?php foreach ($dataClientArr as $dataClient) {
							 $status_msg="Pending";
					$status;
					$status_gstr2_msg="Pending";
					$status_gstr2 = 0;
					$status_gstr3_msg="Pending";
					$status_gstr3 = 0;
                      if($dataClient->vendor_type==2)
						{
						
							$status_msg="NA";
						     $status = 2;
							 
						}
                     else
					 {						 
				    $query="SELECT * FROM " . $db_obj->getTableName('return') . " where client_id ='" . $dataClient->user_id . "' and RETURN_month='".$returnmonth."'";	
					$dataUser = $obj_client->get_results($query);
					
					if(!empty($dataUser))
					{
						
                       		if($dataUser[0]->type=="gstr1")
							{								
						      if($dataUser[0]->status=='0')
								{
									$status_msg="Pending";
									$status = 0;
								}
								else if($dataUser[0]->status=='1')
								{
									$status_msg="Initiated";
									$status = 1;
								}
								else if($dataUser[0]->status=='2')
								{
									$status_msg="Uploaded";
										$status = 2;
								}
								else if($dataUser[0]->status=='3')
								{
									$status_msg="Filed";
										$status = 3;
								}
								else if($dataUser[0]->status=='4')
								{
									$status_msg="downloaded";
										$status = 4;
								}
								else
								{
									$status_msg="Pending";
									$status = 0;
								}	
							}
								if($dataUser[0]->type=="gstr2")
							{								
						      if($dataUser[0]->status=='0')
								{
									$status_gstr2_msg="Pending";
									$status_gstr2 = 0;
								}
								else if($dataUser[0]->status=='1')
								{
									$status_gstr2_msg="Initiated";
									$status_gstr2 = 1;
								}
								else if($dataUser[0]->status=='2')
								{
									$status_gstr2_msg="Uploaded";
									$status_gstr2 = 2;
								}
								else if($dataUser[0]->status=='3')
								{
									$status_gstr2_msg="Filed";
										$status_gstr2 = 3;
								}
								else if($dataUser[0]->status=='4')
								{
									$status_gstr2_msg="downloaded";
									$status_gstr2 = 4;
								}
								else
								{
									$status_gstr2_msg="Pending";
									$status_gstr2 = 0;
								}	
							}
							if($dataUser[0]->type=="gstr3")
							{								
						      if($dataUser[0]->status=='0')
								{
									$status_gstr3_msg="Pending";
									$status_gstr3 = 0;
								}
								else if($dataUser[0]->status=='1')
								{
									$status_gstr3_msg="Initiated";
									$status_gstr3 = 1;
								}
								else if($dataUser[0]->status=='2')
								{
									$status_gstr3_msg="Uploaded";
									$status_gstr3 = 2;
								}
								else if($dataUser[0]->status=='3')
								{
									$status_gstr3_msg="Filed";
										$status_gstr3 = 3;
								}
								else if($dataUser[0]->status=='4')
								{
									$status_gstr3_msg="downloaded";
									$status_gstr3 = 4;
								}
								else
								{
									$status_gstr3_msg="Pending";
									$status_gstr3 = 0;
								}	
							}
						
					 } }

						?>
                        <?php $dataCurrentArr = $db_obj->getUserDetailsById($dataClient->user_id); ?>
                        <tr>
                            <td><?php echo ucwords($dataClient->name);?>(<?php echo $dataClient->username; ?>)</td>
                            <td> <?php echo $dataClient->company_name ?></td> 
                            <td> <?php echo $dataClient->gstin_number ?></td> 
							<td> <?php if($dataClient->user_group==5){ echo 'NA'; } else { echo $status_msg; } ?></td> 
							<td> <?php if($dataClient->user_group==5){ echo 'NA'; } else { echo $status_gstr2_msg; } ?></td> 
							<td> <?php if($dataClient->user_group==5){ echo 'NA'; } else { echo $status_gstr3_msg; } ?></td> 
							
							
							
								
							   
                         
                        </tr>
                        <?php } ?>
                        
                    </table>
<!--                        <ul>
                            <?php foreach ($dataClientArr as $dataClient) { ?>
                                <?php //$dataCurrentArr = $db_obj->getUserDetailsById($dataClient->user_id); ?>
                                <li>
                                    <?php //echo $dataClient->company_name ?>
                                </li>
                                <li>
                                    <?php echo $dataClient->gstin_number ?>
                                </li>
                                <li><?php echo ucwords($dataClient->name); ?> (<?php echo $dataClient->username; ?>) <?php if ($dataCurrentArr['data']->kyc != '') { ?><span class="pull-right"><a href="<?php echo PROJECT_URL; ?>?page=client_loginas&id=<?php echo ($dataClient->user_id); ?>" class="txt">Login As Client</a></span><?php } ?> <span class="pull-right"><a href="<?php echo PROJECT_URL; ?>?page=client_kycupdate_by_subscriber&action=updateClientKYC&id=<?php echo ($dataClient->user_id); ?>" class="txt">Update KYC</a></span></li>
                            <?php } ?>
                        </ul>-->
                    <?php  ?>
                </div>
                <div class="clear height30"></div>
                <div class="tc">
                    <a href="<?php echo PROJECT_URL; ?>/?page=client_update" class="greenbtnborder animation">+ Add More</a> 
                    <a href="<?php echo PROJECT_URL; ?>/?page=client_list" class="redbtnborder animation" style="margin-left:5px;">VIEW ALL</a>
                </div>
            </div>   
        </div>
        <!--        <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class=" whitebg dashleftbox">
                        <div class="boxheading">Plans List</div>
                        <div class="border"></div>
                        <div class="listcontent">
                            
                        </div>
                        <div class="clear height30"></div>
                        <div class="tc"><a href="javascript:void(0)" class="greenbtnborder">+ Add More</a> 
                            <a href="javascript:void(0)" class="redbtnborder" style="margin-left:5px;">VIEW ALL</a>
                        </div>
                    </div>   
                </div>-->
        <div class="dasfooter">Copyright @ by GST Keeper</div>  	
    </div>
    <div class="col-md-2 col-sm-3 col-xs-12 dash-rightnav" style="display:none;">
        <?php include(PROJECT_ROOT . "/modules/dashboard/view/rightpanel.php"); ?>
    </div>
</div>
  <script>
        $(document).ready(function () {
            $('#returnmonth').on('change',function(){
				
				<?php
				if(isset($_REQUEST['returnmonth']) && $_REQUEST['returnmonth'] != '')
				{
					?>
					document.form2.action='<?php echo PROJECT_URL;?>/?page=subscriber_gstr_list&returnmonth=<?php echo $_REQUEST['returnmonth'];?>';
					<?php
				}else
				{
					?>
					document.form2.action='<?php echo PROJECT_URL;?>/?page=subscriber_gstr_list';
					<?php
				}
				?>
				document.form2.submit();
			});
        });

    </script>