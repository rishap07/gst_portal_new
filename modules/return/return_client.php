<?php
$obj_client = new client();
$returnmonth = date('Y-m');
if(!$obj_client->can_read('returnfile_list'))
{
    $obj_client->setError($obj_client->getValMsg('can_read'));
    $obj_client->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}
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
else
{
	
	$dataQuery = "SELECT DATE_FORMAT(invoice_date,'%Y-%m') AS niceDate FROM gst_client_invoice group by nicedate ORDER by invoice_date desc LIMIT 0,1";
	$dataRes = $obj_client->get_results($dataQuery);
	if(!empty($dataRes))
	{
	$returnmonth=$dataRes[0]->niceDate;
	}	
}
$time = strtotime($returnmonth."-01");
$month = date("M", strtotime("+1 month", $time));
$dataArr = array();

			
$dataArr = $obj_client->getUserDetailsById($obj_client->sanitize($_SESSION['user_detail']['user_id']));
 $sql='select * from ' . $db_obj->getTableName('return') . " where client_id='" . $_SESSION["user_detail"]["user_id"] . "' and return_month = '".$returnmonth."' and type='gstr1'";
$dataInvs = $db_obj->get_results($sql);

$status_msg="";
$status="";
$sale_status="";
$sale_file_status = 0;
$sale_download_status = 0;
$sale_upload_status = 0;
$sale_initiate_status = 0;
if(!empty($dataInvs))
{
	if($dataInvs[0]->status=='0')
	{
		$status_msg="Pending";
		$status = 0;
		$sale_file_status = 0;
		$sale_download_status = 0;
		$sale_upload_status = 0;
		$sale_initiate_status = 0;
	}
	else if($dataInvs[0]->status=='1')
	{
		$status_msg="Initiated";
			$status = 1;
		$sale_file_status = 0;
		$sale_download_status = 0;
		$sale_upload_status = 0;
		$sale_initiate_status = 1;
	}
	else if($dataInvs[0]->status=='2')
	{
		 $status_msg="Uploaded";
			$status = 2;
			$sale_file_status = 0;
		$sale_download_status = 0;
		$sale_upload_status = 1;
		$sale_initiate_status = 1;
	}
	else if($dataInvs[0]->status=='3')
	{
		$status_msg="Filed";
			$status = 3;
			$sale_file_status = 1;
		$sale_download_status = 0;
		$sale_upload_status = 1;
		$sale_initiate_status = 1;
	}
	else if($dataInvs[0]->status=='4')
	{
		$status_msg="downloaded";
			$status = 4;
			$sale_file_status = 1;
		$sale_download_status = 1;
		$sale_upload_status = 1;
		$sale_initiate_status = 1;
	}
	else
	{
		$status_msg="Pending";
			$status = 0;
	}
}
$sql='select * from ' . $db_obj->getTableName('return') . " where client_id='" . $_SESSION["user_detail"]["user_id"] . "' and return_month = '".$returnmonth."' and type='gstr2'";
$dataInvPurchase = $db_obj->get_results($sql);

$status_msg="";
$purchase_status="";
$purchase_file_status = 0;
$purchase_download_status = 0;
$purchase_upload_status = 0;
$purchase_initiate_status = 0;
if(!empty($dataInvPurchase))
{
	if($dataInvPurchase[0]->status=='0')
	{
		$status_msg="Pending";
		$purchase_status = 0;
	}
	else if($dataInvPurchase[0]->status=='1')
	{
		    $status_msg="Initiated";
			$purchase_status = 1;
			$purchase_file_status = 0;
			$purchase_download_status = 0;
			$purchase_upload_status = 0;
		    $purchase_initiate_status = 1;
	}
	else if($dataInvPurchase[0]->status=='2')
	{
		   $status_msg="Uploaded";
			$purchase_status = 2;
			$purchase_file_status = 0;
			$purchase_download_status = 1;
			$purchase_upload_status = 0;
		    $purchase_initiate_status = 1;
	}
	else if($dataInvPurchase[0]->status=='3')
	{
		    $status_msg="Filed";
			$purchase_status = 3;
			$purchase_file_status = 1;
			$purchase_download_status = 1;
			$purchase_upload_status = 1;
		    $purchase_initiate_status = 1;
	}
	else if($dataInvPurchase[0]->status=='4')
	{
		$status_msg="downloaded";
			$purchase_status = 4;
			$purchase_file_status = 0;
			$purchase_download_status = 1;
			$purchase_upload_status = 0;
		    $purchase_initiate_status = 1;
	}
	else
	{
		$dataInvPurchase="Pending";
		$purchase_status = 0;
	}
}
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr">
    <div class="col-md-11 col-sm-12 col-xs-12 mobpadlr">
        <div class="col-md-12 col-sm-12 col-xs-12 heading">
            <div class="pull-left">
                <h1>Return summary for the select month</h1>
            </div>
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
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="returnbox">
                <div class="col-md-6 col-sm-6 col-xs-6 boxheading">SALES INVOICE</div>
                <div class="col-md-6 col-sm-6 col-xs-6 text-right">  
                    <a href="<?php echo PROJECT_URL;?>/?page=client_upload_invoice" class="btn btnimport">Choose File</a>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <?php
                    $querySales = "select * from ".$obj_client->getTableName('client_invoice')." where invoice_nature='salesinvoice' and added_by='".$_SESSION['user_detail']['user_id']."' and status='1' and is_canceled='0' and is_deleted='0' and invoice_date like '%".$returnmonth."%'";
                    $salesData = $obj_client->get_results($querySales);
                    ?>
                    Total Sales Invoices : <?php echo count($salesData);?>
                    <div class="clear" style="height:20px;"></div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="returnbox">
                <div class="col-md-6 col-sm-6 col-xs-6 boxheading">PURCHASE INVOICE</div>
                <div class="col-md-6 col-sm-6 col-xs-6 text-right">
                    <a href="<?php echo PROJECT_URL;?>/?page=client_upload_invoice" class="btn btnimport">Choose File</a></div>
                <div class="col-md-12 col-sm-12 col-xs-12">
				 <?php
                    $queryPurchase = "select * from ".$obj_client->getTableName('client_purchase_invoice')." where invoice_nature='purchaseinvoice' and added_by='".$_SESSION['user_detail']['user_id']."' and status='1' and is_canceled='0' and is_deleted='0' and invoice_date like '%".$returnmonth."%'";
                    $purchaseData = $obj_client->get_results($queryPurchase);
                    ?>
                    Total Purchase Invoices :<?php echo count($purchaseData);?>
                    <div class="clear" style="height:20px;"></div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12"> <p class="hr--text"><span class="text--uppercase">GST Returns</span></p></div>
           <!--GSTR STEP END HERE--->   
		       <!--GSTR STEP END HERE--->   
         <div  class="col-md-12 col-sm-12 col-xs-12 whitebg gstr-box">
            <div class="lightyellow roundbtn"><span>GST-Transition Form</span>  | <span>Monthly Filing</span></div>
            <div class="clearfix"></div>
            <div class="gstr-step-row">
                <div class="row">
                    <div class="col-md-8 col-sm-8 modpadlr">
                        <div class="rowsteps">
                
                        </div>
                   
                       
                    </div>
                    <div class="col-md-4 col-sm-4" style="background:#fde7e0; margin-top:20px; border-radius:3px; padding:15px">
                        <div class="gstrrgtbox">Fill GST-Transition<br/><span>To work on GST Transition Form</span></div>
                        <a href="<?php echo PROJECT_URL;?>/?page=transition_gstr&returnmonth=<?php echo $returnmonth;?>" class="btn btn-orange" style="width:100%;">Start Now</a>
                    </div>
                </div>
            </div>
        </div>
      <!--GSTR STEP START HERE--->
		<?php
			function getReturn($month)
		{
			$month = date('n');

			if ($month <= 3) return 1;
			if ($month <= 6) return 2;
			if ($month <= 9) return 3;

			return 4;
		}		
	   $flag = $db_obj->getVendorName('registered');
	   $vendor_id1=0;
		if($flag!=0)
		{
			$vendor_id1 = $flag;
		}	
    $sql="SELECT c.returntofile_vendor_id, c.returnfile_type,c.returntofile_vendor_id as vendorid,c.return_url as return_url,c.return_name,c.id as cat_id,c.return_subheading as return_heading,subcat.id as subcat_id,subcat.subcat_name FROM gst_return_categories as c INNER join gst_return_subcategories as subcat on subcat.cat_id = c.id left join gst_vendor_type as v on v.vendor_id = c.returntofile_vendor_id where c.status='1' and c.is_deleted='0' GROUP by c.id order by c.order_value asc";
	$dataGstr1 = $db_obj->get_results($sql);
    
	 if(!empty($dataGstr1))
	 {
		 foreach($dataGstr1 as $data)
		 {	
            $returnurl = $data->return_url; 		 
			 if($data->vendorid==$dataArr['data']->kyc->vendor_type || $data->returntofile_vendor_id==0)
			 {
      	 ?>
        <div class="col-md-12 col-sm-12 col-xs-12 whitebg gstr-box">
            <div class="lightyellow roundbtn"><span><?php echo $data->return_name; ?></span>  | <span><?php echo $data->return_heading; ?> Filing</span></div>
            <div class="clearfix"></div>
            <div class="gstr-step-row">
            <div class="row">
            <div class="col-md-8 col-sm-8 modpadlr">
			<?php 
			  $sql="SELECT c.returnfile_type,c.return_name,c.id as cat_id,c.return_subheading as return_heading,subcat.id as subcat_id,subcat.subcat_name FROM gst_return_categories as c INNER join gst_return_subcategories as subcat on subcat.cat_id = c.id INNER join gst_vendor_type as v on v.vendor_id = c.returntofile_vendor_id where c.id='".$data->cat_id."' order by c.id asc";
	          $dataGstr1 = $db_obj->get_results($sql);
	
			$i=0;
           foreach($dataGstr1 as $data)
		   { $i = $i+1; 
		   if(isset($_REQUEST["returnmonth"]) && $_REQUEST["returnmonth"]!='')
		   {
			$str1 = (explode("-",$_REQUEST["returnmonth"])); 
            $month = $str1[1];	
            if($data->returnfile_type=='0')
			{ 
			}
			else if($data->returnfile_type=='1')
			{
			 $month = getReturn($month);	
			}			
		   $sql="SELECT DATE_FORMAT(returnfile_date, '%M') as month,DATE_FORMAT(returnfile_date, '%d') as day FROM " . $db_obj->getTableName("returnfile_dates") . " where cat_id='".$data->cat_id."' and subcat_id='".$data->subcat_id."' and  is_deleted='0' and status='1' and return_month='".$month."'";
		   }
		   else
		   {
			$month = date('m');
            if($data->returnfile_type=='0')
			{ 
			}
			else if($data->returnfile_type=='1')
			{
			 $month = getReturn($month);		
			}				
		   $sql="SELECT DATE_FORMAT(returnfile_date, '%M') as month,DATE_FORMAT(returnfile_date, '%d') as day FROM " . $db_obj->getTableName("returnfile_dates") . " where cat_id='".$data->cat_id."' and subcat_id='".$data->subcat_id."' and  is_deleted='0' and status='1' and return_month='".$month."'";
		   }
		   $res = $db_obj->get_results($sql);
		   
		   ?>		   
                        <div class="rowsteps">
						<div class="row step-col actionable">
                        <div class="col-md-5 col-sm-5 col-xs-5"><span class="statusbullet"></span>
                        <span class="txtinovice"><?php echo $data->subcat_name; ?></span></div>
                                <div class="col-md-3 col-sm-3 col-xs-3"><?php 
					//code for check status of GSTR-1 either initiated,uploaded,downloaded e.tc.			
							$status='';
							if($data->cat_id==1)
							{								
								if($i==1)
								{
								$status = $sale_initiate_status;
								}
								else if($i==2)
								{
									$status = $sale_upload_status;
								}
								else if($i==3)
								{
									$status = $sale_file_status;
								}
								else if($i==4)
								{
									$status = '';
								}
								else{
									$status = '';
								}
							}
						//code end here for check GSTR-1 status
               //Code for check GSTR-2 status initiated,downloaded,Match & reconcile,Claim,File GSTR-2						
							if($data->cat_id==2)
							{   if($i==1)
								{
								$status = $purchase_initiate_status;
								}
								else if($i==2)
								{
									$status = $purchase_download_status;
								}
								else if($i==3)
								{
									$status = '';
								}
								else if($i==4)
								{
									$status = '';
								}
								else if($i==5)
								{
									$status = $purchase_file_status;
								}
								else{
									$status = '';
								}
							}
						//Code end here for check GSTR-2 Return fill status	
								if($status==1) { ?> <span class="txtinovice"> <?php echo  "Completed"; ?> </span> <?php } else {  ?> <span class="txtorange"> <?php echo "pending"; ?> </span> <?php } ?></div>
								<?php if($status==1) { 
								?>
                                <div class="col-md-4 col-sm-4 txtinovice col-xs-4"><i class="fa fa-check" aria-hidden="true"></i> Completed</div>
								<?php } else {  ?>
								  <div class="col-md-4 col-sm-4 txtorange col-xs-4"><i class="fa fa-clock-o" aria-hidden="true"></i> Due on <?php if(isset($res[0]->day)) {  echo $res[0]->day; } else { echo ''; } ?> <?php if(isset($res[0]->month)) {  echo $res[0]->month; } else { echo ''; } ?></div>
								<?php } ?>
                            </div>
							</div>
		   <?php } ?>                    
                        
                    </div>
                    <div class="col-md-4 col-sm-4" style="background:#fde7e0;margin-top:20px;  border-radius:3px; padding:15px">
                        <div class="gstrrgtbox">File <?php echo $data->return_name; ?><br/><span>To work on <?php echo $data->return_heading; ?></span></div>
                        <a href="<?php echo PROJECT_URL;?>/?page=<?php echo $returnurl; ?>&returnmonth=<?php echo $returnmonth;?>" class="btn btn-orange" style="width:100%;">Start Now</a>
                    </div>
                </div>
            </div>
        </div>
<?php } } } ?>       
      
				
    <div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer" style="display:none;">
        <div class="col-md-12 col-sm-12 col-xs-12">

            <h1>Returns</h1>
            <div class="whitebg formboxcontainer">

                <?php $obj_client->showErrorMessage(); ?>
                <?php $obj_client->showSuccessMessge(); ?>
                <?php $obj_client->unsetMessage(); ?>
                <h2 class="greyheading">Returns Listing</h2>
                <div class="adminformbx">

                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable">
                        <thead>
                            <tr>
                                <th align='left'>#</th>
                                <th align='left'>Financial Year</th>
                                <th align='left'>Month</th>
                                <th align='left'>GSTR</th>
                                <th align='left'>Status</th>
                                <th align='left'>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>  
            </div>
        </div>
    </div>
    <div class="clear height80">
    </div>
    <script>
        $(document).ready(function () {
            $('#returnmonth').on('change',function(){
				
				<?php
				if(isset($_REQUEST['returnmonth']) && $_REQUEST['returnmonth'] != '')
				{
					?>
					document.form2.action='<?php echo PROJECT_URL;?>/?page=return_client&returnmonth=<?php echo $_REQUEST['returnmonth'];?>';
					<?php
				}else
				{
					?>
					document.form2.action='<?php echo PROJECT_URL;?>/?page=return_client';
					<?php
				}
				?>
				document.form2.submit();
			});
        });

    </script>