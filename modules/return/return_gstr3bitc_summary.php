<?php
$obj_transition = new transition();
$obj_gstr2 = new gstr2();
$obj_gstr3b = new gstr3b();
$obj_gstr = new gstr();
//$obj_login->sendMobileMessage
$returnmonth = date('Y-m');
if(!$obj_gstr2->can_read('returnfile_list'))
{
    $obj_gstr2->setError($obj_gstr2->getValMsg('can_read'));
    $obj_gstr2->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}
if (isset($_POST['returnmonth'])) {
    $returnmonth = $_POST['returnmonth'];
    $obj_gstr2->redirect(PROJECT_URL . "/?page=return_gstr3bitc_summary&returnmonth=" . $returnmonth);
    exit();
}
$returnmonth = date('Y-m');
if (isset($_REQUEST['returnmonth']) && $_REQUEST['returnmonth'] != '') {
    $returnmonth = $_REQUEST['returnmonth'];
}
$returnmonth = date('Y-m');
if ($_REQUEST['returnmonth'] != '') {
    $returnmonth = $_REQUEST['returnmonth'];
}
$resultdata = $obj_gstr3b->getSubmitGSTR3bData();
//$resultdata = $obj_gstr->returnSummary($returnmonth,'','gstr3b');
//$obj_gstr3b->pr($resultdata);die;
$total_igst_other=0;
$total_cgst_other=0;
$total_sgst_other=0;
$total_cess_other=0;
$total_igst_reverse=0;
$total_cgst_reverse=0;
$total_sgst_reverse=0;
$total_cess_reverse=0;
$total_net_igst=0;
$total_net_cgst=0;
$total_net_sgst=0;
$total_net_cess=0;
$paidcash_liab_ldg_id1='';
$paidcash_trans_type1='';
$paidcash_liab_ldg_id2='';
$paidcash_trans_type2='';



if(!empty($resultdata))
{
	$itc_eligible = $resultdata->itc_elg;
	//$obj_gstr3b->pr($itc_eligible);
	$itc_available = $itc_eligible->itc_avl;
	$itc_reverse = $itc_eligible->itc_rev;
	$itc_net = $itc_eligible->itc_net;
	$tax_pmt = $resultdata->tx_pmt;
	$tx_py = $tax_pmt->tx_py;
	//$obj_gstr3b->pr($tx_py);
	if(!empty($tx_py))
	{
		foreach($tx_py as $item)
		{
			if($item->tran_desc=='Reverse charge')
			{
			 $paidcash_liab_ldg_id2=$item->liab_ldg_id;
			$paidcash_trans_type2 = $item->trans_typ;  
			
			}
			if($item->tran_desc=='Other than reverse charge')
			{
			 $paidcash_liab_ldg_id1=$item->liab_ldg_id;
			 $paidcash_trans_type1 = $item->trans_typ;  
			}
		}
	}
	if(!empty($itc_available))
	{
		foreach($itc_available as $item)
		{
			
			$total_sgst_other=$total_sgst_other+$item->samt;
			$total_cess_other=$total_cess_other+$item->csamt;
			$total_cgst_other=$total_cgst_other+$item->camt;
			$total_igst_other=$total_igst_other+$item->iamt;
			
			
		}
	}
	if(!empty($itc_reverse))
	{
		foreach($itc_reverse as $item)
		{
			
			$total_sgst_reverse=$total_sgst_reverse+$item->samt;
			$total_cess_reverse=$total_cess_reverse+$item->csamt;
			$total_cgst_reverse=$total_cgst_reverse+$item->camt;
			$total_igst_reverse=$total_igst_reverse+$item->iamt;
			
			
		}
	}
	if(!empty($itc_net))
	{
		
			
			$total_net_sgst=$itc_net->samt;
			$total_net_cess=$itc_net->csamt;
			$total_net_cgst=$itc_net->camt;
			$total_net_igst=$itc_net->iamt;
			
			
		
	}
}
echo "TotalIGST Available".$total_net_igst;
echo "<br>";
echo "TotalCGST Available".$total_net_cgst;
echo "<br>";
echo "TotalCESS Available".$total_net_cess;
echo "<br>";
echo "TotalSGST Available".$total_net_sgst;
echo "<br>";
if(isset($_POST['offset_id']) && $_POST['offset_id']=='1') {
	
   if($obj_gstr3b->checkoffsetLiability($total_net_igst,$total_net_cgst,$total_net_sgst,$total_net_cess,$paidcash_liab_ldg_id1,$paidcash_trans_type1,$paidcash_liab_ldg_id2,$paidcash_trans_type2,$returnmonth))
   {
	 
   }
}

?>

<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-6 col-sm-6 col-xs-12 heading"><h1>GSTR-3B ITC summary</h1></div>
        <div class="col-md-6 col-sm-6 col-xs-12 text-right breadcrumb-nav"><a href="#">Home</a>
            <i class="fa fa-angle-right" aria-hidden="true"></i>  <a href="#">File Return</a> <i class="fa fa-angle-right" aria-hidden="true"></i> <span class="active">GST-Transition Form</span> </div>
        <div class="whitebg formboxcontainer">
			<?php $obj_gstr2->showErrorMessage(); ?>
		    <?php $obj_gstr2->showSuccessMessge(); ?>
		    <?php $obj_gstr2->unsetMessage(); ?>
             
            <div class="tab">
                <a href="<?php echo PROJECT_URL . '/?page=return_gstr3b_file&returnmonth='.$returnmonth ?>" >
                    Prepare GSTR-3B 
                </a>
				 <a href="<?php echo PROJECT_URL . '/?page=return_gstr3bitc_summary&returnmonth='.$returnmonth ?>" class="active" >
                    ITC Paid
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_filegstr3b_file&returnmonth='.$returnmonth ?>" >
                    File GSTR-3B
                </a>
				
              
             </div>  			
            <div class="pull-right rgtdatetxt">

                <form method='post' name='form2'>
                    Month Of Return
<?php
$dataQuery = "SELECT DATE_FORMAT(invoice_date,'%Y-%m') AS niceDate FROM " . $db_obj->getTableName('client_invoice') . " group by nicedate";
$dataRes = $obj_gstr2->get_results($dataQuery);
if (!empty($dataRes)) {
    ?>
                        <select class="dateselectbox" id="returnmonth" name="returnmonth">
    <?php
    foreach ($dataRes as $dataRe) {
        ?>
                                <option value="<?php echo $dataRe->niceDate; ?>" <?php if ($dataRe->niceDate == $returnmonth) {
            echo 'selected';
        } ?>><?php echo $dataRe->niceDate; ?></option>
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
            </div>
                   
              <form method="post" enctype="multipart/form-data" id='form' name="form4">
      
                <div class="greyheading">1.ITC Available</div>
                <div class="tableresponsive">
                       <table border="1" bordercolor="#ccc" cellpadding="5" cellspacing="0"  class="table  tablecontent tablecontent2 bordernone" id='table1a'>
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th>Tax payable(<i class="fa fa-inr"></i>)</th>
                                    <th colspan="4">Paid througnh itc(<i class="fa fa-inr"></i>)</th>
									
                                    <th>Tax/cess paid in cash(<i class="fa fa-inr"></i>)</th>
									<th>Interest paid in cash(<i class="fa fa-inr"></i>)</th>
									<th>Late fee paid in cash(<i class="fa fa-inr"></i>)</th>
									
					                                 
                                </tr>
								<tr>
								<th>&nbsp;</th>
								<th>&nbsp;</th>
								<th>integratedtax</th>
								<th>centraltax</th>
								<th>statetax</th>
								<th>cess</th>
								<th>&nbsp;</th>
								<th>&nbsp;</th>
								<th>&nbsp;</th>
								
								
								</tr>
                            </thead>
                            <tbody>
               <tr>    
               <td colspan='9'>Other than reverse charge</td>     
                </tr>
				<tr>
			<td class="lftheading">IntegratedTax</td>     
               
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' readonly="true" name='taxpayable_igst_other' value="<?php  echo $total_igst_other;  ?>"  /></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='paiditcigst_igst' value=""/></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='paiditcigst_cgst' value=""/></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='paiditcigst_sgst'  /></td>
			   <td><input type="text" maxlength="15" disabled="" onkeypress="return  isNumberKey(event,this);" name="" value="" class="form-control" placeholder=""></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='taxpaidigst_igst' /></td>
			  <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='interestpaidigst_igst' /></td>
			  <td><input type="text" maxlength="15" disabled="" onkeypress="return  isNumberKey(event,this);" name="" value="" class="form-control" placeholder=""></td>
			   </tr> 
				</tr>
				<tr>
			<td class="lftheading">CentralTax</td>     
               
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' readonly="true" name='taxpayable_cgst_other' value="<?php  echo $total_cgst_other;  ?>" /></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='paiditccgst_igst' /></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='paiditccgst_cgst' /></td>
			    <td><input type="text" maxlength="15" disabled="" onkeypress="return  isNumberKey(event,this);" name="" value="" class="form-control" placeholder=""></td>
			  <td><input type="text" maxlength="15" disabled="" onkeypress="return  isNumberKey(event,this);" name="" value="" class="form-control" placeholder=""></td>
			 
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='taxpaidcgst_igst' /></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" name="interestpaidcgst_igst"  class="form-control" placeholder=""></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" name="latefee_cash"  class="form-control" placeholder=""></td>
			   </tr> 
				</tr>
				<tr>
			<td class="lftheading">State/UtTax</td>     
               
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' readonly="true" name='taxpayable_sgst_other' value="<?php  echo $total_sgst_other;  ?>" /></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='paiditcsgst_igst' value="<?php  echo (isset($paiditcsgst_igst)) ? $paiditcsgst_igst : '' ?>"/></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' disabled name='' value="<?php  echo (isset($paiditcsgst_cgst)) ? $paiditcsgst_cgst : '' ?>"/></td>
			    <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" name="paiditcsgst_sgst" value="<?php  echo (isset($paiditcsgst_sgst)) ? $paiditcsgst_sgst : '' ?>" class="form-control" placeholder=""></td>
			  <td><input type="text" maxlength="15" disabled="" onkeypress="return  isNumberKey(event,this);" name="" value="" class="form-control" placeholder=""></td>
			 
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='taxpaidsgst_sgst' value="<?php  echo (isset($taxpaidsgst_sgst)) ? $taxpaidsgst_sgst : '' ?>"/></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" name="interestpaidsgst_sgst" value="<?php  echo (isset($interestpaidsgst_sgst)) ? $interestpaidsgst_sgst : '' ?>" class="form-control" placeholder=""></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" name="latefee_sgst" value="<?php  echo (isset($latefee_sgst)) ? $latefee_sgst : '' ?>" class="form-control" placeholder=""></td>
			   </tr> 
				</tr>
				<tr>
			<td class="lftheading">Cess</td>     
               
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' readonly="true" name='taxpayable_cess_other' value="<?php  echo $total_cess_other;  ?>" /></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' disabled name='' value="<?php  echo (isset($paiditccess_igst)) ? $paiditccess_igst : '' ?>"/></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' disabled name='' value="<?php  echo (isset($paiditccess_cgst)) ? $paiditccess_cgst : '' ?>"/></td>
			    <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" disabled name='' value="<?php  echo (isset($paiditcsgst_sgst)) ? $paiditcsgst_sgst : '' ?>" class="form-control" placeholder=""></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" name="paiditccess_cess" value="" class="form-control" placeholder=""></td>
			 
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='taxpaidcess_cess' value="<?php  echo (isset($taxpaidcess_cess)) ? $taxpaidcess_cess : '' ?>"/></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);"  name='interestpaidcess_cess' value="<?php  echo (isset($interestpaidcess_cess)) ? $interestpaidcess_cess : '' ?>" class="form-control" placeholder=""></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" disabled name='' value="<?php  echo (isset($latefee_sgst)) ? $latefee_sgst : '' ?>" class="form-control" placeholder=""></td>
			   </tr> 
				
				 <tr>    
               <td colspan='9'>Reverse Charge</td>     
                </tr>
				<tr>
			<td class="lftheading">IntegratedTax</td>     
               
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' readonly="true" name='taxpayable_igst_reverse' value="<?php  echo $total_igst_reverse;  ?>" /></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' disabled name='' value="<?php  echo (isset($paiditccess_igst)) ? $paiditccess_igst : '' ?>"/></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' disabled name='' value="<?php  echo (isset($paiditccess_cgst)) ? $paiditccess_cgst : '' ?>"/></td>
			    <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" disabled name='' value="<?php  echo (isset($paiditcsgst_sgst)) ? $paiditcsgst_sgst : '' ?>" class="form-control" placeholder=""></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" disabled name='' value="" class="form-control" placeholder=""></td>
			 
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='taxpaidcess_igst' value="<?php  echo $total_igst_reverse;  ?>" /></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);"  disabled name='' value="<?php  echo (isset($interestpaidcess_cess)) ? $interestpaidcess_cess : '' ?>" class="form-control" placeholder=""></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" disabled name='' value="<?php  echo (isset($latefee_sgst)) ? $latefee_sgst : '' ?>" class="form-control" placeholder=""></td>
			   </tr> 
			   <tr>
			<td class="lftheading">CentralTax</td>     
               
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' readonly="true" name='taxpayable_cgst_reverse' value="<?php  echo $total_cgst_reverse;  ?>" /></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' disabled name='' value="<?php  echo (isset($paiditccess_igst)) ? $paiditccess_igst : '' ?>"/></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' disabled name='' value="<?php  echo (isset($paiditccess_cgst)) ? $paiditccess_cgst : '' ?>"/></td>
			    <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" disabled name='' value="<?php  echo (isset($paiditcsgst_sgst)) ? $paiditcsgst_sgst : '' ?>" class="form-control" placeholder=""></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" disabled name='' value="" class="form-control" placeholder=""></td>
			 
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='taxpaidcess_cgst' value="<?php  echo $total_cgst_reverse;  ?>" /></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);"  disabled name='' value="<?php  echo (isset($interestpaidcess_cess)) ? $interestpaidcess_cess : '' ?>" class="form-control" placeholder=""></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" disabled name='' value="<?php  echo (isset($latefee_sgst)) ? $latefee_sgst : '' ?>" class="form-control" placeholder=""></td>
			   </tr> 
			   <tr>
			<td class="lftheading">StateTax</td>     
               
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' readonly="true" name='taxpayable_sgst_reverse' value="<?php  echo $total_sgst_reverse;  ?>"  /></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' disabled name='' value="<?php  echo (isset($paiditccess_igst)) ? $paiditccess_igst : '' ?>"/></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' disabled name='' value="<?php  echo (isset($paiditccess_cgst)) ? $paiditccess_cgst : '' ?>"/></td>
			    <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" disabled name='' value="<?php  echo (isset($paiditcsgst_sgst)) ? $paiditcsgst_sgst : '' ?>" class="form-control" placeholder=""></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" disabled name='' value="" class="form-control" placeholder=""></td>
			 
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='taxpaidcess_sgst' value="<?php  echo $total_sgst_reverse;  ?>" /></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);"  disabled name='' value="<?php  echo (isset($interestpaidcess_cess)) ? $interestpaidcess_cess : '' ?>" class="form-control" placeholder=""></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" disabled name='' value="<?php  echo (isset($latefee_sgst)) ? $latefee_sgst : '' ?>" class="form-control" placeholder=""></td>
			   </tr> 
			   <tr>
			<td class="lftheading">Cess</td>     
               
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' readonly="true" name='taxpayable_cess_reverse' value="<?php  echo $total_cess_reverse;  ?>" /></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' disabled name='' value="<?php  echo (isset($paiditccess_igst)) ? $paiditccess_igst : '' ?>"/></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' disabled name='' value="<?php  echo (isset($paiditccess_cgst)) ? $paiditccess_cgst : '' ?>"/></td>
			    <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" disabled name='' value="<?php  echo (isset($paiditcsgst_sgst)) ? $paiditcsgst_sgst : '' ?>" class="form-control" placeholder=""></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" disabled name='' value="" class="form-control" placeholder=""></td>
			 
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='taxpaidcess_cess' value="<?php  echo $total_cess_reverse;  ?>" /></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);"  disabled name='' value="<?php  echo (isset($interestpaidcess_cess)) ? $interestpaidcess_cess : '' ?>" class="form-control" placeholder=""></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" disabled name='' value="<?php  echo (isset($latefee_sgst)) ? $latefee_sgst : '' ?>" class="form-control" placeholder=""></td>
			   </tr>			   		   
              </tbody>
              </table>
              </div>                						
       <div class="tableresponsive">
        <div class="adminformbxsubmit" style="width:100%;"> 
         <div class="tc" style="float:right;">
		 <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=return_gstr2_mydata&returnmonth=" . $_REQUEST["returnmonth"]; ?>';" class="btn btn-danger" class="redbtn marlef10"/>
     	 <input type='submit' class="btn btn-success" name='offset' value='offset liability' id='gstr1_summary_download'>
		 <input type='hidden' name="btn_type" id="btn_type" readonly value="upload" />
		 <input type='hidden' name="offset_id" id="offset_id" readonly value="1" />
							    	
         </div>                             
        </div>                                            
    
       </div></div> 

        </div>
        <div class="clear height40"></div>     

    </div>
    <!--CONTENT START HERE-->
</form>
<div class="clear"></div>
<?php 

$obj_gstr->DownloadSummaryOtpPopupJs();
?>
<script>
    $(document).ready(function () {
        $('#returnmonth').on('change', function () {
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_gstr3bitc_summary&returnmonth=<?php echo $returnmonth; ?>';
                        document.form2.submit();
                    });
                });
</script>

<script>
    $(document).ready(function () {
        $('#returnmonth').on('change', function () {
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_gstr3bitc_summary&returnmonth=<?php echo $returnmonth; ?>';
                        document.form2.submit();
                    });
                });
</script>



<script type="text/javascript">
    function isNumberKey(evt)
    {

        var charCode = (evt.which) ? evt.which : event.keyCode

        if ((charCode >= 40) && (charCode <= 57) && (charCode != 47) && (charCode != 42) && (charCode != 43) && (charCode != 44) && (charCode != 45) || (charCode == 8))
        {
            return true;

        } else
        {
            return false;

        }
    }
</script>