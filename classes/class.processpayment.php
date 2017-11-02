<?php

/*
 * 
 *  Developed By        :   Himanshu Chittora
 *  Date Created        :   July 06, 2017
 *  Purpose               :   class for Proceed Payment
 * 
 */

class processpayment extends validation
{
    public function __construct()
    {
        parent::__construct();
    }

    /* 
		Main function to proceed for payment.
		$tablename = Tablename here;
		$columnname = array of name of unique id and Amount  fields
		$raturnpage = Page name where user will redirect after payment.
    */

	function pay_now($tablename, $columnname, $returnpage) {
		
		if ($_POST && isset($_POST['submit'])) {

			$get_amount = $this->findAll(TAB_PREFIX . $tablename, $columnname[0] . '=' . $_SESSION['plan_id'], "" . $columnname[1] . " as amount");
            $get_UserDetails = $this->findAll(TAB_PREFIX . 'user', ' user_id=' . $_SESSION['user_detail']['user_id']);
			$userStateData = $this->getStateDetailByStateId($get_UserDetails[0]->state);

            $cur_date = date('Y-m-d H:i:s');
            $ref_id   = date('siHmdy');

			//Update payment process date
            $this->update(TAB_PREFIX . 'user_subscribed_plan', array('ref_id' => $ref_id), array('id' => $_SESSION['subs_id']));

			//coupon
			if(isset($_POST['coupon']) && !empty($_POST['coupon'])) {

				$couponData = $this->get_results("select * from ".TAB_PREFIX . "coupon where name='".$this->sanitize($_POST['coupon'])."'");
				if(!empty($couponData)) {

					$client_datas = $this->get_results("select * from ".TAB_PREFIX."user where coupon='".$this->sanitize($_POST['coupon'])."'");
					if(isset($couponData[0]->coupon_uses) && count($client_datas)<$couponData[0]->coupon_uses) {
						$this->update(TAB_PREFIX . 'user', array('coupon' => $this->sanitize($_POST['coupon'])), array('user_id' => $_SESSION['user_detail']['user_id']));
					} else {
						$this->setError('Coupon Code Expired');
						return false;
					}

				} else {
					$this->setError('Invalid Coupon Code');
					return false;
				}
			}
				
            //Insert data in payment log
            $this->insert(TAB_PREFIX . 'payment_log', array(
                'process_payment_id' => $ref_id,
				'ref_id' => $ref_id,
                'datetime' => $cur_date,
                'status' => '0'
            ));
			
			$price_data = $get_amount[0]->amount;
			if(!empty($couponData)) {

				if(isset($couponData[0]->type) && $couponData[0]->type=='0') {
					$get_amount[0]->amount = $price_data - $couponData[0]->coupon_value;
					$get_amount[0]->amount = $get_amount[0]->amount + ($get_amount[0]->amount*0.18);
				}

				else if(isset($couponData[0]->type) && $couponData[0]->type=='1') {
					$get_amount[0]->amount = $get_amount[0]->amount - round((($price_data*$couponData[0]->coupon_value)/(100+$couponData[0]->coupon_value)), 2, PHP_ROUND_HALF_DOWN);
					$get_amount[0]->amount = $get_amount[0]->amount + ($get_amount[0]->amount*0.18);
					$get_amount[0]->amount = round($get_amount[0]->amount, 2, PHP_ROUND_HALF_DOWN);
				}
			} else {
				$get_amount[0]->amount = $get_amount[0]->amount + ($get_amount[0]->amount * 0.18);
			}
			?>
			<form action="<?php echo PROJECT_URL; ?>/go4hosting/keeper_payment.php" name="payment" method="POST" id="payment"> 
                <input type="hidden" value="0" name="channel"/>
				<input type="hidden" value="25039" name="account_id"/>
				<input type="hidden" value="<?php echo $ref_id; ?>" name="reference_no"/>
				<input type="hidden" value="<?php echo $get_amount[0]->amount; ?>" name="amount"/>
				<input type="hidden" value="INR" name="currency"/>
				<input type="hidden" value="INR" name="display_currency"/>
				<input type="hidden" value="1" name="display_currency_rates"/>
				<input type="hidden" value="Payment information from GST" name="description"/>
				<input type="hidden" value="<?php echo PROJECT_URL . "/go4hosting/keeper_response.php"; ?>" name="return_url"/>
				<input type="hidden" value="LIVE" name="mode"/>
				<input type="hidden" value="<?php echo $get_UserDetails[0]->username; ?>" name="name"/>
				<input type="hidden" value="<?php if(!empty($userStateData['data']) && isset($userStateData['data']->state_name)) { echo $userStateData['data']->state_name; } else { echo "Delhi"; } ?>" name="address"/>
				<input type="hidden" value="<?php if(empty($get_UserDetails[0]->city)) { echo "Delhi"; } else { echo $get_UserDetails[0]->city; } ?>" name="city"/>
				<input type="hidden" value="110010" name="postal_code"/>
				<input type="hidden" value="IND" name="country"/>
				<input type="hidden" value="<?php echo $get_UserDetails[0]->email; ?>" name="email"/>
				<input type="hidden" value="<?php echo $get_UserDetails[0]->phone_number; ?>" name="phone"/>
            </form>

            <script type="text/javascript">
				window.onload=func1;
				function func1(){
					document.payment.submit();
				}
			</script>
			<?php
			exit();
        }
    }

    public function payment_method() {

		$dataArr 						= array();
		$dataAr['ref_id'] 				= isset($_POST['MerchantRefNo']) ? $_POST['MerchantRefNo'] : '';
		$dataArr['process_payment_id'] 	= isset($_POST['PaymentID']) ? $_POST['PaymentID'] : '';
		$dataArr['datetime'] 			= isset($_POST['DateCreated']) ? $_POST['DateCreated'] : '';
		$dataArr['response_datetime'] 	= date('Y-m-d H:i:s');
		$dataArr['response_data'] 		= json_encode($_POST);
		
		
		$check_data_exists = $this->get_results("select * from ".TAB_PREFIX . "payment_log where process_payment_id='".$dataArr['process_payment_id']."' and response_data='".$dataArr['response_data']."' and ref_id='".$dataAr['ref_id']."' " );
		if(count($check_data_exists)>0)
		{
			$this->setError('Invalid Access of page.');
			return false;
		}
		
		
        $this->update(TAB_PREFIX . "payment_log", $dataArr, $dataAr);
		$dataArr['ref_id'] = isset($_POST['MerchantRefNo']) ? $_POST['MerchantRefNo'] : '';
		$this->insert(TAB_PREFIX . "payment_log_chck", $dataArr);

        $dataArr['ResponseCode'] 	= isset($_POST['ResponseCode']) ? $_POST['ResponseCode'] : '';
        $dataArr['ResponseMessage'] = isset($_POST['ResponseMessage']) ? $_POST['ResponseMessage'] : '';

        if ($dataArr['ResponseCode'] == '0') {

			$this->update(TAB_PREFIX . "user_subscribed_plan", array(
                'payment_status' => '1'
            ), array(
                'ref_id' => $dataArr['ref_id'],
                'added_by' => $_SESSION['user_detail']['user_id']
            ));

			$dataUsPl = $this->get_results("select * from " . TAB_PREFIX . "user_subscribed_plan where ref_id='" . $dataArr['ref_id'] . "' and added_by = '" . $_SESSION['user_detail']['user_id'] . "'");
			$dataPl = $this->get_results("select * from " . TAB_PREFIX . "subscriber_plan where id = '" . $dataUsPl[0]->plan_id . "'");

            $dataUpdateArr['no_of_client']    = $dataPl[0]->no_of_client;
            $dataUpdateArr['payment_status']  = "1";
            $dataUpdateArr['plan_id']         = $dataPl[0]->id;
            $dataUpdateArr['plan_start_date'] = date('Y-m-d H:i:s');
            $dataUpdateArr['plan_due_date']   = (date('Y')+1) . "-03-31 23:59:59";

            $this->update(TAB_PREFIX . "user", $dataUpdateArr, array('user_id' => $_SESSION['user_detail']['user_id']));
            $this->setSuccess('Your payment is successful.');

			/**********Mail function added by sheetal*********************************/
            $email = $this->get_results("select state,company_name,gstin_number,email,coupon,first_name,last_name from " . TAB_PREFIX . "user where user_id='" . $_SESSION['user_detail']['user_id']."'");

			$companyaddress=array(
				'name'=>'CYFUTURE INDIA PRIVATE LIMITED',
				'address'=>'G1-227/228, H1 236-239, Export Promotion Industrial Park (EPIP)',
				'address1'=>'Sitapura Industrial Area, Jaipur -302 022',
				'gstin'=>'08AABCC7015R1ZB',
				'sac'=>'998314'
			);
			
			$useraddress = array(
				'name'=>$email[0]->first_name." ".$email[0]->last_name,
				'company_name'=>$email[0]->company_name,
				'address'=>'',
				'address1'=>'',
				'gstin'=>$email[0]->gstin_number,
				'state' => $email[0]->state
			);

			$invoiceDta = $this->get_results("select max(invoice_number) as invoice_number from ".TAB_PREFIX."invoices");
			if(!empty($invoiceDta) && count($invoiceDta)>0) {
				$invoiceDta=$invoiceDta[0]->invoice_number+1;
			} else {
				$invoiceDta='10000';
			}

			$couponData = array();
			if($email[0]->coupon!='') {
				$couponData = $this->get_results("select * from ".TAB_PREFIX."coupon where name='".$email[0]->coupon."'");
			}

			$dataInvoice['invoice_number'] = $invoiceDta;
			$dataInvoice['user_id'] = $_SESSION['user_detail']['user_id'];
			$dataInvoice['invoice_value'] = $dataPl[0]->plan_price;
			$dataInvoice['coupon'] = $email[0]->coupon;
			$dataInvoice['coupon_type'] = (isset($couponData[0]->type)) ? $couponData[0]->type : '0';
			$dataInvoice['coupon_value'] = (isset($couponData[0]->coupon_value)) ? $couponData[0]->coupon_value : '0';
			
			if($dataInvoice['coupon_type']=='1') {
				$discount = ($dataPl[0]->plan_price * $dataInvoice['coupon_value']) / (100+$dataInvoice['coupon_value']);
			} else {
				$discount = $dataInvoice['coupon_value'];
			}
			
			$dataInvoice['discount'] = $discount;
			$dataInvoice['tax_percentage'] = '18.00';
			$plan_price = $dataInvoice['invoice_value'] - $dataInvoice['discount'];
			$dataInvoice['taxes'] = ($plan_price * $dataInvoice['tax_percentage']) / 100;
			$dataInvoice['total'] = $plan_price + $dataInvoice['taxes'];
			$dataInvoice['payment_status'] = 1;
			$dataInvoice['plan_subscription_id'] = $dataPl[0]->id;
			$dataInvoice['invoice_date'] = date('Y-m-d H:i:s');
			$dataInvoice['invoice_paid_date'] = date('Y-m-d H:i:s');
			$this->insert(TAB_PREFIX.'invoices',$dataInvoice);

			$planDetail = $this->get_results("select b.name as cat_name,a.name,a.no_of_client,a.company_no,a.pan_num,a.sub_user,a.invoice_num ,a.support,a.period_of_service,a.web_mobile_app,a.cloud_storage_gb,a.gst_expert_help from ".TAB_PREFIX."subscriber_plan a left join ".TAB_PREFIX."subscriber_plan_category b on a.plan_category=b.id where a.id='".$dataUpdateArr['plan_id']."'");
			if($dataUpdateArr['plan_id'] == '22') {
				$planDetail[0]->no_of_client = 'Unlimited';
				$planDetail[0]->company_no = 'Unlimited';
				$planDetail[0]->sub_user = 'Unlimited';
			}

			$htmlResponse = $this->generatePlanPdf($_SESSION['user_detail']['user_id'], $planDetail, $dataUpdateArr['plan_due_date'], $companyaddress, $useraddress, $dataInvoice);
			
			if ($htmlResponse === false) {
				$obj_client->setError("No Plan Pdf found.");
				return false;
			}

			$obj_mpdf = new mPDF();
			$obj_mpdf->SetHeader('Plan Invoice');
			$obj_mpdf->WriteHTML($htmlResponse);
			$datetime=date('YmdHis');
			$taxInvoicePdf = 'plan-invoice-' . $_SESSION['user_detail']['username'] . '_' .$datetime. '.pdf';
			
			ob_clean();
			$pic = $taxInvoicePdf;
			$path = "/upload/plan-invoice/".$taxInvoicePdf; 
			$content = $obj_mpdf->Output("upload/plan-invoice/".$taxInvoicePdf);

			$module    = "Request Plan for Purchase";
            $moduleMsg = $_SESSION['user_detail']['user_id']." has purchased plan";
            $to        = $email[0]->email;
			$from = 'noreply@gstkeeper.com';
			$cc='';
			$bcc  = 'rishap.gandhi@cyfuture.com,aditya.kumar@cyfuture.com,ishwar.ghiya@cyfuture.com,Manish.sarthak@cyfuture.com,jagat.singh@cyfuture.com';
			$attachment=$path;
            $subject   = 'Thank you for Purchasing a Plan on GSTKeeper!';
            $body=$this->getMailBody();
			
            $this->sendMail($module, $moduleMsg, $to,$from,$cc,$bcc,$attachment,$subject, $body);
			
			/**********Mail Code End function added by sheetal*********************************/
			$_SESSION['res']='1';
			return true;
            
        } else {

			$this->update(TAB_PREFIX . "user_subscribed_plan", array(
                'status' => '0'
            ), array(
                'ref_id' => $dataArr['ref_id'],
                'added_by' => $_SESSION['user_detail']['user_id']
            ));

            $this->setError('Your payment is failed try again');
            $_SESSION['res']='2';
            return false;
        }
    }
	
	public function getMailBody()
	{
		return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<title>gst</title> 
</head> 

<body> 
<div style="width:720px; margin:auto; border:solid #CCC 1px;"> 
<table cellpadding="0" cellspacing="0" width="100%" > 
  <tbody> 
    <tr> 
      <td height="auto"><table width="720" cellpadding="0" cellspacing="0" bgcolor="#fff" style="font-family:Arial, Helvetica, sans-serif;margin:0px auto;"> 
          <tbody> 
            <tr> 
              <td width="30"></td> 
              <td><table width="100%" cellpadding="0" cellspacing="0"> 
                  <tbody> 
                    <tr> 
                      <td align="left" valign="middle" height="80"><a target="_blank" href="https://www.gstkeeper.com/"><img src="https://gstkeeper.com/newsletter/4july2017/gst-logo.png" alt="" border="0"></a></td> 
                      <td align="right" valign="middle" style="font-size:18px;color:#cf3502;font-family:Arial, Helvetica, sans-serif;" height="80px"> <span><img src="https://gstkeeper.com/newsletter/6july2017/phone-icon.jpg" alt=""></span>1-800-212-2022<br> 
                        <span><img src="https://gstkeeper.com/newsletter/6july2017/mail-icon.jpg" alt=""></span><a href="mailto:contact@gstkeeper.com" style="font-size:14px;color:#cf3502;text-decoration:none;"> contact@gstkeeper.com</a></td> 
                    </tr> 
                  </tbody> 
                </table></td> 
              <td width="30"></td> 
            </tr> 
            <tr> 
              <td width="30"></td> 
              <td><table width="100%" cellpadding="0" cellspacing="0"> 
                  <tbody> 
                    <tr> 
                      <td align="center" valign="middle"><img src="https://www.gstkeeper.com/newsletter/7july-planpurchase/images/banner.jpg" width="700" height="132" /></td> 
                    </tr> 
                  </tbody> 
                </table></td> 
              <td width="30"></td> 
            </tr> 
         
         
           
            <tr> 
              <td width="30"  ></td> 
              <td><table width="100%" cellpadding="0" cellspacing="0"> 
                <tbody> 
                  <tr> 
                    <td height="157"   align="center" valign="top"><table width="100%" cellpadding="0px" cellspacing="0" > 
                      <tbody> 
                         
                        <tr> 
                          <td width="13"></td> 
                          <td width="350"  style="font-size:15px;color:#090909;font-family:Arial, Helvetica, sans-serif; padding-top:10px; "><strong>Hi There! 
</strong></td> 
                          <td width="20"></td> 
                          </tr> 
                        <tr> 
                          <td colspan="3" height="10"></td> 
                          </tr> 
                        <tr> 
                          <td width="13"></td> 
                          <td height="110" align="justify"  valign="top" style="font-size:13px;color:#191919;font-family:Arial, Helvetica, sans-serif; line-height:18px; "><p>Hope you are having an amazing day! 
 </p> 
                            <p>We look forward to assist you with our best-in-class GST compliance software. </p> 
                             
                           
                           
                             
                           
     <p><strong>Thanks!</strong><BR /> 
The GST Keeper Team </p></td> 
                          <td width="20"></td> 
                          </tr> 
                         
                        </tbody> 
                    </table></td> 
                    </tr> 
                </tbody> 
              </table></td> 
               
            </tr> 
            <!--<tr> 
         
         <td  align="center" height="29"><img src="http://cdn.go4hosting.in/mailer/12-oct/resources-img.jpg"  alt=""    /></td> 
         
         </tr>--> 
             
            <tr> 
              <td colspan="3" height="15"></td> 
            </tr> 
         
           
            <tr> 
              <td width="30"></td> 
              <td><table width="98%" align="right" cellpadding="0" cellspacing="0" style="background-color:#f1f1f1; height:80px; padding:10px;"> 
                <tbody> 
                  <tr> 
                    <td width="47%"><a href="http://www.cyfuture.com/" target="_blank"><img src="https://gstkeeper.com/newsletter/4july2017/cyfuture-logo.png" alt="" border="0" /></a></td> 
                    <td width="53%" align="right"><table width="100%" cellpadding="0" cellspacing="0"> 
                      <tbody> 
                        <tr> 
                          <td width="20" height="50"></td> 
                          <td valign="middle" style="font-size:14px;color:#333;font-family:Arial, Helvetica, sans-serif;"><strong><i>Connect with us</i></strong></td> 
                          <td valign="middle" width="50" align="center"><a target="_blank" href="https://www.facebook.com/GST-Keeper-632910016898628/"><img src="https://gstkeeper.com/newsletter/4july2017/fb-icon.png" alt="" border="0" /></a></td> 
                          <td valign="middle" width="40" align="left"><a target="_blank" href="https://plus.google.com/101841021110541536034"><img src="https://gstkeeper.com/newsletter/4july2017/g+-icon.png" alt="" border="0" /></a></td> 
                          <td valign="middle" width="40" align="left"><a target="_blank" href="https://twitter.com/GstKeeper"><img src="https://gstkeeper.com/newsletter/4july2017/twit-icon.png" alt="" border="0" /></a></td> 
                          <td valign="middle" width="40" align="left"><a target="_blank" href="https://www.youtube.com/channel/UCsDdNFR8kJ3YVWpEvBrFeSA"><img src="https://gstkeeper.com/newsletter/4july2017/utube-icon.png" alt="" border="0" /></a></td> 
                          <td valign="middle" width="40" align="left"><a target="_blank" href="https://www.linkedin.com/company/gst-keeper"><img src="https://gstkeeper.com/newsletter/4july2017/in-icon.jpg" alt="" border="0" /></a></td> 
                        </tr> 
                      </tbody> 
                    </table></td> 
                  </tr> 
                </tbody> 
              </table></td> 
              <td width="30"></td> 
            </tr> 
            <tr> 
              <td width="30"></td> 
              <td height="76" valign="middle"><table width="100%" cellpadding="0" cellspacing="0"> 
                  <tbody> 
                    <tr> 
                      <td width="20"></td> 
                      <td align="center"><font style="font-size:14px;color:#444;font-family:Arial, Helvetica, sans-serif;">Cyfuture ( India ) Pvt. Ltd.</font><br> 
                        <font style="font-size:12px;color:#444;font-family:Arial, Helvetica, sans-serif;">Plot No. 197-198 Noida Special Economic Zone (NSEZ) Phase II, Noida 201 305</font><br> 
                      <font style="font-size:12px;color:#444;font-family:Arial, Helvetica, sans-serif;">E-mail: <a style="text-decoration:none;color:#3194d5;" href="mailto:contact@gstkeeper.com">contact@gstkeeper.com</a></font><br></td> 
                      <td width="15" align="left">&nbsp;</td> 
                    </tr> 
                  </tbody> 
              </table></td> 
             
         
          </tbody> 
        </table></td> 
    </tr> 
  </tbody> 
</table> 




</div> 
</body> 
</html> ';
	}
	public function generatePlanPdf($invid, $planDetail, $planduedate, $companyaddress, $useraddress, $dataInvoice) {
        $mpdfHtml = '';
        $mpdfHtml .= '<div style="margin:auto;font-size:16px;line-height:24px;color:#555;">';
        $mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;" cellpadding="0" cellspacing="0">';
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;">';
        $mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;">';
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td style="font-size:45px;line-height:45px;color:#333;padding:5px;vertical-align:top;padding-bottom:20px;">';

        $mpdfHtml .= '<img src="image/gst-k-logo.png" style="width:100%;max-width:300px;">';

        $mpdfHtml .= '</td>';

        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;text-align:right;padding-bottom:20px;">';
        $mpdfHtml .= '<b>Invoice #</b>: ' . $dataInvoice['invoice_number'] . '<br>';
        $mpdfHtml .= '<b>Reference #</b>: ' . $dataInvoice['invoice_number'] . '<br>';
        $mpdfHtml .= '<b>Type:</b> ' . 'Plan Invoice' . '<br>';
        $mpdfHtml .= '<b>Invoice Date:</b>' . date("Y-m-d", strtotime($dataInvoice['invoice_date']));
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';
        $mpdfHtml .= '</table>';
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';

        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;">';
        $mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;">';
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;padding-bottom:20px;">';
        $mpdfHtml .= $companyaddress['name'] . '<br>';
        $mpdfHtml .= $companyaddress['address'] . '<br>';
        $mpdfHtml .= $companyaddress['address1'] . '<br>';
        $mpdfHtml .= '<b>GSTIN:</b> ' . $companyaddress['gstin'] . '<br>';
        $mpdfHtml .= '<b>SAC CODE:</b> ' . $companyaddress['sac'];
        $mpdfHtml .= '</td>';

        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;text-align:right;padding-bottom:20px;">';
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';
        $mpdfHtml .= '</table>';
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';


        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;">';
        $mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;">';
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;padding-bottom:40px;width:50%;">';

        $mpdfHtml .= '<b>Recipient Detail</b><br>';
        $mpdfHtml .= $useraddress['name'] . '<br>';
        $mpdfHtml .= $useraddress['company_name'] . '<br>';
        $mpdfHtml .= $useraddress['address'] . '<br>';
        $mpdfHtml .= $useraddress['address1'] . '';
        if ($useraddress['gstin'] != '') {
            $mpdfHtml .= '<br><b>GSTIN:</b> ' . $useraddress['gstin'];
        }
        $mpdfHtml .= '</td>';

        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;text-align:right;padding-bottom:20px;">';
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';
        $mpdfHtml .= '</table>';
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';

        $mpdfHtml .= '<tr>';

        $mpdfHtml .= '<td colspan="2">';

        $mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:center;">';
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">S.No</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;text-align:left;">Goods/Services</td>';

        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;text-align:right;">Qty</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;text-align:right;">Unit</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;text-align:right;">Rate</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;text-align:right;">Total</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;text-align:right;">Discount(%)</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;text-align:right;">Taxable Value</td>';
        if ($useraddress['state'] != '22') {
            $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;text-align:right;">IGST</td>';
        }
        else 
        {
            $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;text-align:right;">SGST</td>';
            $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;text-align:right;">CGST</td>';
        }
        
        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;text-align:right;">CESS</td>';
        $mpdfHtml .= '</tr>';

        $mpdfHtml .= '<tr class="heading">';
		if ($useraddress['state'] != '22') {
			$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;text-align:right;">(%)</td>';
			$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Amt (₹)</td>';
		}
		else
		{
			$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;text-align:right;">(%)</td>';
			$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Amt (₹)</td>';
			$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;text-align:right;">(%)</td>';
			$mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Amt (₹)</td>';
		}
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;text-align:right;">(%)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;text-align:right;">Amt (₹)</td>';
        $mpdfHtml .= '</tr>';
        $counter = 1;


        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
        $mpdfHtml .= $counter;
        $mpdfHtml .= '</td>';

        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;text-align:left;">';
        $mpdfHtml .= '<b>' . $planDetail['0']->cat_name . ':' . $planDetail['0']->name . '</b><br>';
        $mpdfHtml .= '<b> GSTN :</b>' . $planDetail['0']->no_of_client . '<br>';
        $mpdfHtml .= '<b> Company :</b>' . $planDetail['0']->company_no . '<br>';
        $mpdfHtml .= '<b> Pan :</b>' . $planDetail['0']->pan_num . '<br>';
		$mpdfHtml .= '<b> Sub Users :</b>' . $planDetail['0']->sub_user . '<br>';
        $mpdfHtml .= '<b> Invoice number :</b>' . $planDetail['0']->invoice_num . '<br>';
        $mpdfHtml .= '<b> support :</b>' . $planDetail['0']->support . '<br>';
        $mpdfHtml .= '<b> period_of_service :</b>' . $planDetail['0']->period_of_service . '<br>';
        $mpdfHtml .= '<b> Web Mobile App :</b>' . $planDetail['0']->web_mobile_app . '<br>';
        $mpdfHtml .= '<b> Cloud Storage :</b>' . $planDetail['0']->cloud_storage_gb . '<br>';
        $mpdfHtml .= '<b> Expert Help :</b>' . $planDetail['0']->gst_expert_help . '<br>';
        $mpdfHtml .= '</td>';

        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;text-align:right;">';
        $mpdfHtml .= 1;
        $mpdfHtml .= '</td>';

        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;text-align:right;">';
        $mpdfHtml .= 1;
        $mpdfHtml .= '</td>';

        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;text-align:right;">';
        $mpdfHtml .= round($dataInvoice['invoice_value'], 2, PHP_ROUND_HALF_DOWN);
        $mpdfHtml .= '</td>';

        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;text-align:right;">';
        $mpdfHtml .= round($dataInvoice['invoice_value'], 2, PHP_ROUND_HALF_DOWN);
        $mpdfHtml .= '</td>';

        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;text-align:right;">';
        $mpdfHtml .= round($dataInvoice['discount'], 2, PHP_ROUND_HALF_DOWN);
        $mpdfHtml .= '</td>';

        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;text-align:right;">';
        $mpdfHtml .= round($dataInvoice['invoice_value'], 2, PHP_ROUND_HALF_DOWN) - round($dataInvoice['discount'], 2, PHP_ROUND_HALF_DOWN);
        $mpdfHtml .= '</td>';

        if ($useraddress['state'] != '22') {
            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;text-align:right;">';
            $mpdfHtml .= round($dataInvoice['tax_percentage'], 2, PHP_ROUND_HALF_DOWN);
            $mpdfHtml .= '</td>';
			$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;text-align:right;">';
			$mpdfHtml .= round($dataInvoice['taxes'], 2, PHP_ROUND_HALF_DOWN);
			$mpdfHtml .= '</td>';
        }
        else 
        {
            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;text-align:right;">';
            $mpdfHtml .= round(($dataInvoice['tax_percentage']/2), 2, PHP_ROUND_HALF_DOWN);
            $mpdfHtml .= '</td>';
			$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;text-align:right;">';
			$mpdfHtml .= round(($dataInvoice['taxes']/2), 2, PHP_ROUND_HALF_DOWN);
			$mpdfHtml .= '</td>';
            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;text-align:right;">';
            $mpdfHtml .= round(($dataInvoice['tax_percentage']/2), 2, PHP_ROUND_HALF_DOWN);
            $mpdfHtml .= '</td>';
			$mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;text-align:right;">';
			$mpdfHtml .= round(($dataInvoice['taxes']/2), 2, PHP_ROUND_HALF_DOWN);
			$mpdfHtml .= '</td>';
        }

        

        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;text-align:right;">';
        $mpdfHtml .= '0';
        $mpdfHtml .= '</td>';

        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;text-align:right;">';
        $mpdfHtml .= '0';
        $mpdfHtml .= '</td>';

        $mpdfHtml .= '</tr>';
        $mpdfHtml .= '<tr>';
        if ($useraddress['state'] != '22') {
            $mpdfHtml .= '<td colspan="17" style="padding:5px;vertical-align:top;text-align:right;border-top:2px solid #eee;font-weight:bold;">';
            $mpdfHtml .= 'Total Invoice Value (In Figure): ' . round($dataInvoice['total'], 2, PHP_ROUND_HALF_DOWN);
            $mpdfHtml .= '</td>';
        }
        else
        {
             $mpdfHtml .= '<td colspan="19" style="padding:5px;vertical-align:top;text-align:right;border-top:2px solid #eee;font-weight:bold;">';
            $mpdfHtml .= 'Total Invoice Value (In Figure): ' . round($dataInvoice['total'], 2, PHP_ROUND_HALF_DOWN);
            $mpdfHtml .= '</td>';
        }
        $mpdfHtml .= '</tr>';

        $mpdfHtml .= '</br></br>';
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td colspan="10" style="padding:5px;vertical-align:top;text-align:right;border-top:2px solid #eee;font-weight:bold;">';
        $mpdfHtml .= 'This is a computer generated invoice. No signature is required.';
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';
        $mpdfHtml .= '</table>';

        $mpdfHtml .= '</td>';

        $mpdfHtml .= '</tr>';

        $mpdfHtml .= '</table>';
        $mpdfHtml .= '</div>';
        return $mpdfHtml;
    }
    
}
?>