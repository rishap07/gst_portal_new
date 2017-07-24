<!DOCTYPE html>
<html lang="En">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
	<title>GST Keeper</title>
	<!--COMMON CSS START HERE-->
	<!--COMMON CSS START HERE-->
	<link type="text/css" rel="stylesheet" href="<?php echo THEME_URL; ?>/css/bootstrap.min.css?7" />
	<link type="text/css" rel="stylesheet" href="<?php echo THEME_URL; ?>/css/style.css?7" />
	<link type="text/css" rel="stylesheet" href="<?php echo THEME_URL; ?>/css/font-awesome.min.css?7" />
	<!--COMMON CSS END HERE-->

	<link rel="stylesheet" type="text/css" href="<?php echo PROJECT_URL; ?>/script/datatables/media/css/jquery.dataTables.min.css?7" />
	<link rel="stylesheet" type="text/css" href="<?php echo PROJECT_URL; ?>/script/jquery_ui/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo PROJECT_URL; ?>/script/jquery-ui-timepicker/jquery-ui-timepicker-addon.css?7" />
	<link rel="stylesheet" type="text/css" href="<?php echo PROJECT_URL; ?>/script/select2/select2.css?7" />
	<link type="text/css" rel="stylesheet" href="<?php echo THEME_URL; ?>/css/custom.css?7" />
	<link type="text/css" rel="stylesheet" href="<?php echo PROJECT_URL; ?>/script/jalerts/jquery.alerts.css?7" />

	<script src="<?php echo THEME_URL; ?>/js/jquery-1.12.4.js"></script>
	<script type="text/javascript" src="<?php echo THEME_URL; ?>/js/bootstrap.min.js"></script>

	<script>
		$(document).ready(function() {
			$('.row-offcanvas-left').addClass('');
			$(".mobilemenu").click(function() {
				$("#sidebar").toggle();
				$('.row-offcanvas-left').addClass('mobileactive');
			});
		});
	</script>
	
	<script type="text/javascript">
		$(document).ready(function (e) {

			$(".sidemenu li").click(function (e) {

				if ($(this).hasClass("active")) {
					$(".sidemenu li").removeClass("active");
					$(".sidemenu li strong").removeClass("fa-minus").addClass("fa-plus");
				} else {
					$(".sidemenu li").removeClass("active");
					$(".sidemenu li strong").removeClass("fa-minus").addClass("fa-plus");
					$(this).addClass("active");
					$(this).children().children("strong").removeClass("fa-plus").addClass("fa-minus");
				}
			});

			var w = $(window).width();
			if (w >= 0 && w <= 992) {
				$(".sidemenu").before("<div class='mobmenu'><img src='image/menu-icon.png' title='menu' alt='menu' /></div>");
				$(".mobmenu").click(function (e) {
					$(".mainmenu").toggleClass("slow");
				});
			}
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
				<li><a href="#"><img src="image/user-img.jpg" alt="#" class="userimg" /> <?php echo ucwords($_SESSION['user_detail']['name']);?>  <i class="fa fa-caret-down" aria-hidden="true"></i></a>
					<ul class="shadow">
						<div class="arrowup"><i class="fa fa-caret-up" aria-hidden="true"></i></div>
						<li><a href="javascript:void(0)"><i class="fa fa-commenting-o" aria-hidden="true"></i> Live Chat</a></li>
						<li> <a href="#"><i class="fa fa-bell-o" aria-hidden="true"></i> Nofification<span class="infocount">2</span></a> </li>
						<li> <a href="#"><i class="fa fa-envelope-o" aria-hidden="true"></i>Message<span class="infocount">2</span></a> </li>
						<li> <a href="<?php echo PROJECT_URL; ?>/?page=logout"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>

	<!--HEADER START HERE--> 
	<div class="headertop shadow">
		<div class="logo"><a href="#"></a></div> 
		<div class="topnav">
			<ul class="topstripnav"> 
				<li><div class="tollfreenumber" style="margin-top:-15px;">Toll Free<br/> <span>1800-212-2022</span> </div><span class="iconphone"></span></li>
				<li><a href="javascript:void(0)"><div class="tollfreenumber"> <span>Live Chat</span></div> <span class="iconchat"></span></a></li>
				<!--<li><span class="messagecircle">2</span><strong class="fa fa-bell-o" aria-hidden="true"></strong></li>-->
				<!--<li><span class="messagecircle">2</span><strong class="fa fa-envelope-o" aria-hidden="true"></strong></li>-->
				<li><div class="userinfo"><img src="<?php echo PROJECT_URL; ?>/image/user-img.jpg" alt="#"><?php echo ucwords($_SESSION['user_detail']['name']);?></div></li>
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
                </li> <?php } ?>
				 <?php if ($db_obj->can_read('client_kyc')) { ?>

                          <li>
                    <a href="#" data-target="#item8" data-toggle="collapse"><i class="fa fa-cog"></i> 
                    <span class="collapse in hidden-xs">Business Setting <span class="navrgtarrow"><i class="fa fa-chevron-right" aria-hidden="true"></i></span></span></a>
                             <ul class="nav nav-stacked collapse left-submenu" id="item8">
                                <li><a href="<?php echo PROJECT_URL; ?>/?page=client_kycupdate"><i class="fa fa-circle" aria-hidden="true"></i>Company Profile</a></li>
                                <li><a href="<?php echo PROJECT_URL; ?>/?page=user_themesetting"><i class="fa fa-circle" aria-hidden="true"></i>Company Setting</a></li>
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
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_invoice_list'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Tax Invoices</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_create_invoice'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Add Tax Invoice</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_create_export_invoice'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Add Tax Export Invoice</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?> <li><a href="<?php echo PROJECT_URL . '/?page=client_bill_of_supply_invoice_list'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Bill Of Supply Invoice</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_create_bill_of_supply_invoice'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Add Bill Of Supply Invoice</a></li><?php } ?>

							<?php /* if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_receipt_voucher_invoice_list'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Receipt Voucher Invoice</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_create_receipt_voucher_invoice'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Add Receipt Voucher Invoice</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_refund_voucher_invoice_list'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Refund Voucher Invoice</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_create_refund_voucher_invoice'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Add Refund Voucher Invoice</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_payment_voucher_invoice_list'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Payment Voucher Invoice</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_create_payment_voucher_invoice'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Add Payment Voucher Invoice</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_revised_tax_invoice_list'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Revised Tax Invoice</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_create_revised_tax_invoice'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Add Revised Tax Invoice</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_special_tax_invoice_list'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Special Cases Tax Invoice</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_create_special_tax_invoice'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Add Special Cases Tax Invoice</a></li>
							<?php } */ ?>
						</ul>
					</li>
                 <?php } ?>

				 <?php if ($db_obj->can_read('client_invoice')) { ?>
					<li>
						<a href="#" data-target="#itemPurchase" data-toggle="collapse"><i class="fa fa-list"></i> 
						<span class="collapse in hidden-xs">Purchase Invoices <span class="navrgtarrow"><i class="fa fa-chevron-right" aria-hidden="true"></i></span></span></a>
						<ul class="nav nav-stacked collapse left-submenu" id="itemPurchase">
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=purchase_invoice_list'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Tax Invoices</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=purchase_invoice_create'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Add Tax Invoice</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=purchase_import_invoice_create'; ?>"><i class="fa fa-circle" aria-hidden="true"></i>Add Import Tax Invoice</a></li><?php } ?>
						</ul>
					</li>
                 <?php } ?>
  
				<!--<li><a href="#"><i class="fa fa-hourglass-half" aria-hidden="true"></i> <span class="collapse in hidden-xs">Time of Supply</span></a></li>
                <li><a href="#"><i class="fa fa-refresh"></i> <span class="collapse in hidden-xs">Payment</span></a></li>
                <li><a href="#"><i class="fa fa-file-excel-o" aria-hidden="true"></i> <span class="collapse in hidden-xs">TDS</span></a></li>
                <li><a href="#"><i class="fa fa-credit-card" aria-hidden="true"></i> <span class="collapse in hidden-xs">TCS</span></a></li>-->
				
				<?php if ($db_obj->can_read('client_invoice')) { ?>
					<li><a href="<?php echo PROJECT_URL . '/?page=return_client'; ?>"><i class="fa fa-refresh"></i> <span class="collapse in hidden-xs">Return</span></a></li>
                <?php }
               
                ?>
                <?php if (isset($_SESSION['publisher']['user_id'])) { ?>
                <li><a href="<?php echo PROJECT_URL . '/?page=client_loginas&permission=revert'; ?>"><i class="fa fa-refresh"></i> <span class="collapse in hidden-xs">Revert to Login</span></a></li>
                <?php } ?>
                <li class="hidemenu"><a href="#" data-toggle="offcanvas" style="border-bottom:none; margin-top:30px; font-size:13px;"><img src="image/hideicon.png" alt="#" style="margin-right:10px" />Hide menu</a></li>
            </ul>
           
            <div style="clear:both;"></div>
        </div>
        <!-- /sidebar -->

		<!--CONTENT START HERE-->
		<div class="column col-md-10 col-sm-9 col-xs-6" id="main" style="padding-right:0px; padding-top:0px; padding-left:0px;">