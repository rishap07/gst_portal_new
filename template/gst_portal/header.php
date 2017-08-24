<?php
	$form_data = array();
	if(isset($_GET['page'])) {
		$form_data = $db_obj->get_results("select * from ".TAB_PREFIX."module where url='".$db_obj->sanitize($_GET['page'])."' and status='1'");
	}
	
?>

<html lang="En">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
	<title><?php echo isset($form_data[0]->Title) ? $form_data[0]->Title : 'GST Keeper'; ?></title>
	
	<!--COMMON CSS START HERE-->
	<link type="text/css" rel="stylesheet" href="<?php echo THEME_URL; ?>/css/bootstrap.min.css?8" />
	<link type="text/css" rel="stylesheet" href="<?php echo THEME_URL; ?>/css/style.css?10" />
	<link type="text/css" rel="stylesheet" href="<?php echo THEME_URL; ?>/css/font-awesome.min.css?8" />
	<!--COMMON CSS END HERE-->

	<link rel="stylesheet" type="text/css" href="<?php echo PROJECT_URL; ?>/script/datatables/media/css/jquery.dataTables.min.css?8" />
	<link rel="stylesheet" type="text/css" href="<?php echo PROJECT_URL; ?>/script/jquery_ui/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo PROJECT_URL; ?>/script/jquery-ui-timepicker/jquery-ui-timepicker-addon.css?8" />
	<link rel="stylesheet" type="text/css" href="<?php echo PROJECT_URL; ?>/script/select2/select2.css?8" />
	<link type="text/css" rel="stylesheet" href="<?php echo THEME_URL; ?>/css/custom.css?8" />
	<link type="text/css" rel="stylesheet" href="<?php echo PROJECT_URL; ?>/script/jalerts/jquery.alerts.css?8" />

	<script src="<?php echo THEME_URL; ?>/js/jquery-1.12.4.js"></script>
	<script type="text/javascript" src="<?php echo THEME_URL; ?>/js/bootstrap.min.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
			$('.row-offcanvas-left').addClass('');
			$(".mobilemenu").click(function() {
				$("#sidebar").toggle();
				$('.row-offcanvas-left').addClass('mobileactive');
			});
		});
	</script>
	

	
</head>
<body>
	<div class="mobilemenu"><a href="#"><i class="fa fa-bars" aria-hidden="true"></i></a></div>
	<div class="mobileheader shadow">
		<div class="col-sm-4 col-xs-6 moblogo" style="padding-left:0px;">
			<a href="#"><img src="image/mob-GST-logo.jpg" alt="#"></a>
		</div>
	 
		<div class="col-sm-6 col-xs-6 mobuserinfo">
			<ul class="userinfo">
				<li>
					<a href="#"><img src="image/user-img.jpg" alt="#" class="userimg" /> <?php echo ucwords($_SESSION['user_detail']['name']);?>  <i class="fa fa-caret-down" aria-hidden="true"></i></a>
					<ul class="shadow">
						<div class="arrowup"><i class="fa fa-caret-up" aria-hidden="true"></i></div>
						<li><a href="javascript:void(0)"><i class="fa fa-commenting-o" aria-hidden="true"></i> Live Chat</a></li>
						<li><a href="#"><i class="fa fa-bell-o" aria-hidden="true"></i> Nofification<span class="infocount">2</span></a></li>
						<li><a href="#"><i class="fa fa-envelope-o" aria-hidden="true"></i>Message<span class="infocount">2</span></a></li>
						<li><a href="<?php echo PROJECT_URL; ?>/?page=logout"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
	  <?php
						  
						  $obj_notification = new notification();
						  $message='';
						  $notification_status=0;
						  //Show total number of notification
						 
                          //Check user notification
						  $obj_notification->showNotificationData();
						    //Show all notification Message
						  if($message=$obj_notification->showNotificationUpdate())
						  {
						   
						  }
						  else{
							  $count=1;
						  }
						  //Check total user notification
						  $count=$obj_notification->totalNotification(); 
						  if($count==1)
						  {
							  $count=$obj_notification->totalNotificationShow();
						  }
						  $notification_status=$obj_notification->checkNotificationStatus();
						  

					?>
				

	<!--HEADER START HERE--> 
	<div class="headertop shadow">
		<div class="logo"><a href="#"></a></div> 
		<div class="topnav">
			<ul class="topstripnav"> 
			  <?php
			 
				$dataArrSetting = $db_obj->getAdminSetting();
			  if (!empty($dataArrSetting)) {
                 echo  html_entity_decode($dataArrSetting[0]->tollfree_setting);
				 echo  html_entity_decode($dataArrSetting[0]->livechat_setting);			 
				}
				
				 ?>
				<!--
				<li><div class="tollfreenumber" style="margin-top:-15px;">Toll Free<br/> <span>1800-212-2022</span> </div><span class="iconphone"></span></li>
				<li><a href="javascript:void(0)"><div class="tollfreenumber"> <span>Live Chat</span></div> <span class="iconchat"></span></a></li>
		     //-->
				<?php 
				if(($_SESSION["user_detail"]["user_group"]==3) || ($_SESSION["user_detail"]["user_group"]==4))
				{
					
				if($count > 1)
				{
					
					?>
				 <li id="noti_Container"><i class="fa fa-bell-o" aria-hidden="true"></i>
                <div id="noti_Counter"></div>   <!--SHOW NOTIFICATIONS COUNT.-->
                
                <!--A CIRCLE LIKE BUTTON TO DISPLAY NOTIFICATION DROPDOWN.-->
                <div id="noti_Button"></div>    

                <!--THE NOTIFICAIONS DROPDOWN BOX.-->
                <div id="notifications">
                <div class="arrow-up"></div>
                    <div class="txtnotfication">Notifications</div>
                   
						<?php echo $message; ?>
					<div class="clear"></div>
                    <div class="btnseeall"><a href="<?php echo PROJECT_URL; ?>/?page=notification" class="btn">See All</a></div>
                </div>
				</li> <?php } } ?>
               <li><div class="userinfo">
				<?php 
				$profile_pics= '/image/user-img.jpg';
				$dataArr = $db_obj->getUserDetailsById($_SESSION["user_detail"]["user_id"]);
			  
				 if ($dataArr['data'] != '' && isset($dataArr['data']->profile_pics) &&($dataArr['data']->profile_pics!='') ) {
                  $profile_pics="/upload/profile-picture/".$dataArr['data']->profile_pics;					 
				}
				
			    
				?>
					<a href="<?php echo PROJECT_URL; ?>/?page=subscriber_update"><img style="height:40px;" src="<?php echo PROJECT_URL.$profile_pics; ?>" alt="#"></a><?php echo ucwords($_SESSION['user_detail']['name']);?></div></li>
				<li style="border-right:none;"><a href="<?php echo PROJECT_URL; ?>/?page=logout" class="btnlogout"><span class="fa fa-sign-out" aria-hidden="true"></span> LOGOUT</a></li>
			</ul>
		</div>
	</div>
	<!--HEADER END HERE-->

	<div class="wrapper">
		<div class="row row-offcanvas row-offcanvas-left">

		<!-- sidebar -->
		<div class="column col-md-2 col-sm-3 col-xs-6 sidebar-offcanvas  padlr0" id="sidebar"> 

			<ul class="nav" id="menu">
				
				<li><a href="<?php echo PROJECT_URL . "?page=dashboard"; ?>"><i class="fa fa-tachometer" aria-hidden="true"></i><span class="collapse in hidden-xs">Dashboard</span></a></li>
				
				<?php if ($db_obj->can_read('client_list')) { ?>
					<li>
						<a href="#" data-target="#item2" data-toggle="collapse"><i class="fa fa-user-o" aria-hidden="true"></i> 
						<span class="collapse in hidden-xs">Business Users <span class="navrgtarrow"><i class="fa fa-chevron-right" aria-hidden="true"></i></span></span></a>
						<ul class="nav nav-stacked collapse left-submenu" id="item2">
							<li><a href="<?php echo PROJECT_URL; ?>/?page=client_list"><i class="fa fa-circle" aria-hidden="true"></i>All Business User</a></li>
							<li><a href="<?php echo PROJECT_URL; ?>/?page=client_update"><i class="fa fa-circle" aria-hidden="true"></i>Add Business User</a></li>
						</ul>
					</li>
				<?php } ?>

				<?php if ($db_obj->can_read('admin_list')) { ?>
					<li>
						<a href="#" data-target="#item6" data-toggle="collapse"><i class="fa fa-lock"></i> 
						<span class="collapse in hidden-xs">Admin<span class="navrgtarrow"><i class="fa fa-chevron-right" aria-hidden="true"></i></span></span></a>
						<ul class="nav nav-stacked collapse left-submenu" id="item6">
							<li><a href="<?php echo PROJECT_URL; ?>/?page=user_adminupdate"><i class="fa fa-circle" aria-hidden="true"></i>Add New Admin</a></li>
							<li><a href="<?php echo PROJECT_URL; ?>/?page=user_adminlist"><i class="fa fa-circle" aria-hidden="true"></i>All Admin</a></li>
						</ul>
					</li>
				<?php } ?>
                
				 
				<?php if ($db_obj->can_read('plan_category_list') || $db_obj->can_read('plan_list')) { ?>
					<li>
						<a href="#" data-target="#item3" data-toggle="collapse"><i class="fa fa-list"></i> 
						<span class="collapse in hidden-xs">Plan <span class="navrgtarrow"><i class="fa fa-chevron-right" aria-hidden="true"></i></span></span></a>
						<ul class="nav nav-stacked collapse left-submenu" id="item3">
							<?php if ($db_obj->can_read('plan_category_list')) { ?><li><a href="<?php echo PROJECT_URL; ?>/?page=plan_categorylist"><i class="fa fa-circle" aria-hidden="true"></i>Plan Category Listing</a></li><?php } ?>
							<?php if ($db_obj->can_create('plan_category_list')) { ?><li><a href="<?php echo PROJECT_URL; ?>/?page=plan_addcategory"><i class="fa fa-circle" aria-hidden="true"></i>Add Plan Category</a></li><?php } ?>
							<?php if ($db_obj->can_read('plan_list')) { ?><li><a href="<?php echo PROJECT_URL; ?>/?page=plan_list"><i class="fa fa-circle" aria-hidden="true"></i>Plan Listing</a></li><?php } ?>
							<?php if ($db_obj->can_create('plan_list')) { ?><li><a href="<?php echo PROJECT_URL; ?>/?page=plan_addplan"><i class="fa fa-circle" aria-hidden="true"></i>Add Plan</a></li><?php } ?>
						</ul>
					</li>
				<?php } ?>
				<?php if ($db_obj->can_read('coupon_update') || $db_obj->can_read('coupon_list')) { ?>
					<li>
						<a href="#" data-target="#item9" data-toggle="collapse"><i class="fa fa-list"></i> 
						<span class="collapse in hidden-xs">Coupon<span class="navrgtarrow"><i class="fa fa-chevron-right" aria-hidden="true"></i></span></span></a>
						<ul class="nav nav-stacked collapse left-submenu" id="item9">
						<?php if ($db_obj->can_read('coupon_list')) { ?><li><a href="<?php echo PROJECT_URL; ?>/?page=coupon_list"><i class="fa fa-circle" aria-hidden="true"></i>Coupon Listing</a></li><?php } ?>
							
							<?php if ($db_obj->can_read('coupon_update')) { ?><li><a href="<?php echo PROJECT_URL; ?>/?page=coupon_update"><i class="fa fa-circle" aria-hidden="true"></i>Update Coupon</a></li><?php } ?>
							
						</ul>
					</li>
				<?php } ?>
				<?php  if($db_obj->can_read('notification_list')) { ?>
					<li>
						<a href="#" data-target="#item10" data-toggle="collapse"><i class="fa fa-list"></i> 
						<span class="collapse in hidden-xs">Notification<span class="navrgtarrow"><i class="fa fa-chevron-right" aria-hidden="true"></i></span></span></a>
						<ul class="nav nav-stacked collapse left-submenu" id="item10">
						<?php if ($db_obj->can_read('notification_list')) { ?><li><a href="<?php echo PROJECT_URL; ?>/?page=notification_list"><i class="fa fa-circle" aria-hidden="true"></i>Notification Listing</a></li><?php } ?>
							
						
						</ul>
					</li>
				<?php } ?>
				<?php  if($db_obj->can_read('module_list')) { ?>
					<li>
						<a href="#" data-target="#item11" data-toggle="collapse"><i class="fa fa-list"></i> 
						<span class="collapse in hidden-xs">Module<span class="navrgtarrow"><i class="fa fa-chevron-right" aria-hidden="true"></i></span></span></a>
						<ul class="nav nav-stacked collapse left-submenu" id="item11">
						<?php if ($db_obj->can_read('module_list')) { ?><li><a href="<?php echo PROJECT_URL; ?>/?page=module_list"><i class="fa fa-circle" aria-hidden="true"></i>Module Listing</a></li><?php } ?>
							
						
						</ul>
					</li>
				<?php } ?>
				<?php if ($db_obj->can_read('client_kyc') || $db_obj->can_read('subscriber_update'))  { ?>
					<li>
						<a href="#" data-target="#item8" data-toggle="collapse"><i class="fa fa-cog"></i> 
						<span class="collapse in hidden-xs">Business Setting <span class="navrgtarrow"><i class="fa fa-chevron-right" aria-hidden="true"></i></span></span></a>
						<ul class="nav nav-stacked collapse left-submenu" id="item8">
							<?php if ($db_obj->can_read('client_kyc'))
							{
							?>
							<li><a href="<?php echo PROJECT_URL; ?>/?page=client_kycupdate"><i class="fa fa-circle" aria-hidden="true"></i>Company Profile</a></li>
							<li><a href="<?php echo PROJECT_URL; ?>/?page=user_themesetting"><i class="fa fa-circle" aria-hidden="true"></i>Company Setting</a></li>
							<?php
							}
							if ($db_obj->can_read('subscriber_update'))
							{
								?>
							<li><a href="<?php echo PROJECT_URL; ?>/?page=subscriber_update"><i class="fa fa-circle" aria-hidden="true"></i>Profile</a></li>
							<?php
							}
							?>
							
						</ul>
					</li>
				<?php } ?>
				
				<?php if ($db_obj->can_read('user_group') || $db_obj->can_read('user_role')) { ?>
					<li>
						<a href="#" data-target="#item7" data-toggle="collapse"><i class="fa fa-cog"></i> 
						<span class="collapse in hidden-xs">Settings<span class="navrgtarrow"><i class="fa fa-chevron-right" aria-hidden="true"></i></span></span></a>
						<ul class="nav nav-stacked collapse left-submenu" id="item7">
							<?php if ($db_obj->can_read('user_group')) { ?><li><a href="<?php echo PROJECT_URL; ?>/?page=user_group"><i class="fa fa-circle" aria-hidden="true"></i>User Group</a></li><?php } ?>
							<?php if ($db_obj->can_read('user_role')) { ?><li><a href="<?php echo PROJECT_URL; ?>/?page=user_role"><i class="fa fa-circle" aria-hidden="true"></i>User Role</a></li><?php } ?>
						</ul>
					</li>
				<?php } ?>
				
				<?php if (($db_obj->can_read('master_state')) || ($db_obj->can_read('master_unit')) || ($db_obj->can_read('master_receiver')) || ($db_obj->can_read('master_supplier')) || ($db_obj->can_read('master_item')) || ($db_obj->can_read('client_master_item'))) { ?>
					<li>
						<a href="#" data-target="#item4" data-toggle="collapse"><i class="fa fa-asterisk"></i> 
						<span class="collapse in hidden-xs">Master <span class="navrgtarrow"><i class="fa fa-chevron-right" aria-hidden="true"></i></span></span></a>
						<ul class="nav nav-stacked collapse left-submenu" id="item4">
							<?php if ($db_obj->can_read('master_state')) { ?><li><a href="<?php echo PROJECT_URL; ?>/?page=master_state"><i class="fa fa-circle" aria-hidden="true"></i>State</a></li><?php } ?>
							<?php if ($db_obj->can_read('master_unit')) { ?><li><a href="<?php echo PROJECT_URL; ?>/?page=master_unit"><i class="fa fa-circle" aria-hidden="true"></i>Unit</a></li><?php } ?>
							<?php if ($db_obj->can_read('master_receiver')) { ?><li><a href="<?php echo PROJECT_URL; ?>/?page=master_receiver"><i class="fa fa-circle" aria-hidden="true"></i>Receiver/Customer</a></li><?php } ?>
							<?php if ($db_obj->can_read('master_supplier')) { ?><li><a href="<?php echo PROJECT_URL; ?>/?page=master_supplier"><i class="fa fa-circle" aria-hidden="true"></i>Supplier/Seller</a></li><?php } ?>
							<?php if ($db_obj->can_read('master_item')) { ?><li><a href="<?php echo PROJECT_URL; ?>/?page=master_item"><i class="fa fa-circle" aria-hidden="true"></i>Item</a></li><?php } ?>
							<?php if ($db_obj->can_read('master_vendor')) { ?><li><a href="<?php echo PROJECT_URL; ?>/?page=master_vendor"><i class="fa fa-circle" aria-hidden="true"></i>Vendor</a></li><?php } ?>
							<?php if ($db_obj->can_read('master_business_area')) { ?><li><a href="<?php echo PROJECT_URL; ?>/?page=master_business_area"><i class="fa fa-circle" aria-hidden="true"></i>Business Area</a></li><?php } ?>
							<?php if ($db_obj->can_read('master_business_type')) { ?><li><a href="<?php echo PROJECT_URL; ?>/?page=master_business_type"><i class="fa fa-circle" aria-hidden="true"></i>Business Type</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_master_item')) { ?><li><a href="<?php echo PROJECT_URL; ?>/?page=client_item_list"><i class="fa fa-circle" aria-hidden="true"></i>Item</a></li><?php } ?>
						</ul>
					</li>
				<?php } ?>

				 <?php if ($db_obj->can_read('client_invoice')) { ?>
					<li>
						<a href="#" data-target="#item5" data-toggle="collapse"><i class="fa fa-list"></i> 
						<span class="collapse in hidden-xs">Sales Invoices <span class="navrgtarrow"><i class="fa fa-chevron-right" aria-hidden="true"></i></span></span></a>
						<ul class="nav nav-stacked collapse left-submenu" id="item5">
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_upload_invoice'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Upload Invoices</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_invoice_list'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Tax Invoice</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_create_invoice'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Add Tax Invoice</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_create_export_invoice'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Add Tax Export Invoice</a></li><?php } ?>

							<?php if ($db_obj->can_read('client_invoice')) { ?> <li><a href="<?php echo PROJECT_URL . '/?page=client_bill_of_supply_invoice_list'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Bill Of Supply Invoice</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_create_bill_of_supply_invoice'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Add Bill Of Supply Invoice</a></li><?php } ?>

							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_receipt_voucher_invoice_list'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Receipt Voucher Invoice</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_create_receipt_voucher_invoice'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Add Receipt Voucher Invoice</a></li><?php } ?>
							
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_refund_voucher_invoice_list'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Refund Voucher Invoice</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_create_refund_voucher_invoice'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Add Refund Voucher Invoice</a></li><?php } ?>

							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_revised_tax_invoice_list'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Revised Tax Invoice</a></li><?php } ?>
                            <?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_create_revised_tax_invoice'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Add Revised Tax Invoice</a></li><?php } ?>
                            
                            <?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_delivery_challan_invoice_list'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Delivery Challan Invoice</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_create_delivery_challan_invoice'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Add Delivery Challan Invoice</a></li><?php } ?>

						</ul>
					</li>
				 <?php } ?>

				 <?php if ($db_obj->can_read('client_invoice')) { ?>
					<li>
						<a href="#" data-target="#itemPurchase" data-toggle="collapse"><i class="fa fa-list"></i> 
						<span class="collapse in hidden-xs">Purchase Invoices <span class="navrgtarrow"><i class="fa fa-chevron-right" aria-hidden="true"></i></span></span></a>
						<ul class="nav nav-stacked collapse left-submenu" id="itemPurchase">
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=purchase_upload_invoice'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Upload Invoices</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=purchase_invoice_list'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Tax Invoice</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=purchase_invoice_create'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Add Tax Invoice</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=purchase_import_invoice_create'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Add Import Tax Invoice</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=purchase_bill_of_supply_invoice_list'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Bill Of Supply Invoice</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=purchase_bill_of_supply_invoice_create'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Add Bill Of Supply Invoice</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=purchase_receipt_voucher_invoice_list'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Receipt Voucher Invoice</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=purchase_receipt_voucher_invoice_create'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Add Receipt Voucher Invoice</a></li><?php } ?>
						</ul>
					</li>
				 <?php } ?>
				 

					<?php if ($db_obj->can_read('client_invoice')) { ?>
						<li><a href="<?php echo PROJECT_URL . '/?page=return_client'; ?>"><i class="fa fa-refresh"></i> <span class="collapse in hidden-xs">Return</span></a></li>
					<?php } ?>
				 <?php if ($db_obj->can_read('activitylog')) { ?>
					<li>
						<a href="#" data-target="#itemActivity" data-toggle="collapse"><i class="fa fa-list"></i> 
						<span class="collapse in hidden-xs">System Setting <span class="navrgtarrow"><i class="fa fa-chevron-right" aria-hidden="true"></i></span></span></a>
						<ul class="nav nav-stacked collapse left-submenu" id="itemActivity">
							<?php if ($db_obj->can_read('activitylog')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=activitylog'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>ActivityLog</a></li><?php } ?>
						
						</ul>
					</li>
				 <?php } ?>

				<?php if (isset($_SESSION['publisher']['user_id'])) { ?>
					<li><a href="<?php echo PROJECT_URL . '/?page=client_loginas&permission=revert'; ?>"><i class="fa fa-refresh"></i> <span class="collapse in hidden-xs">Revert to Login</span></a></li>
				<?php } ?>
				
				<li class="hidemenu"><a href="#" data-toggle="offcanvas" style="border-bottom:none; margin-top:30px; font-size:13px;"><img src="image/hideicon.png" alt="#" style="margin-right:10px" />Hide menu</a></li>
			</ul>

			<div style="clear:both;"></div>
		</div>
		<!-- /sidebar -->
<script>
    $(document).ready(function () {

        // ANIMATEDLY DISPLAY THE NOTIFICATION COUNTER.
		<?php
		if($notification_status > 0 )
		{
			?>
        $('#noti_Counter')
            .css({ opacity: 0 })
            .text('<?php echo $count-1; ?>')              // ADD DYNAMIC VALUE (YOU CAN EXTRACT DATA FROM DATABASE OR XML).
            .css({ top: '-10px' })
			 .css({ background: '#E1141E' })
            .animate({ top: '-2px', opacity: 1 }, 500);
		<?php } else { 
		?>
           $('#noti_Counter')
            .css({ opacity: 0 })
           // .text('<?php echo $count-1; ?>')              // ADD DYNAMIC VALUE (YOU CAN EXTRACT DATA FROM DATABASE OR XML).
            .css({ top: '-10px' })
			 //.css({ background: 'silver' })
          //  .animate({ top: '-2px', opacity: 1 }, 500);
	<?php	}		?>

        $('#noti_Button').click(function () {

            // TOGGLE (SHOW OR HIDE) NOTIFICATION WINDOW.
            $('#notifications').fadeToggle('fast', 'linear', function () {
                if ($('#notifications').is(':hidden')) {
                   // $('#noti_Button').css('background-color', '#2E467C');
                }
                else
				{					
				//	$('#noti_Button').css('background-color', '#FFF'); 
				}
					// CHANGE BACKGROUND COLOR OF THE BUTTON.
            });

            $('#noti_Counter').fadeOut('slow');                 // HIDE THE COUNTER.

            return false;
        });

        // HIDE NOTIFICATIONS WHEN CLICKED ANYWHERE ON THE PAGE.
        $(document).click(function () {
            $('#notifications').hide();

            // CHECK IF NOTIFICATION COUNTER IS HIDDEN.
            if ($('#noti_Counter').is(':hidden')) {
                // CHANGE BACKGROUND COLOR OF THE BUTTON.
               // $('#noti_Button').css('background-color', '#2E467C');
            }
        });

        $('#notifications').click(function () {
            //return false;       // DO NOTHING WHEN CONTAINER IS CLICKED.
        });
    });
</script>
		<!--CONTENT START HERE-->
		<div class="column col-md-10 col-sm-9 col-xs-6" id="main" style="padding-right:0px; padding-top:0px; padding-left:0px;">