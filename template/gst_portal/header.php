<?php
//$db_obj->pr($_SESSION);
?>
<!DOCTYPE html>
<html lang="En">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
        <title>Form Design</title>
        <link type="text/css" rel="stylesheet" href="<?php echo THEME_URL; ?>/css/style.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo THEME_URL; ?>/css/theme-color.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo THEME_URL; ?>/css/custom.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo THEME_URL; ?>/css/font-awesome.min.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo PROJECT_URL; ?>/script/datatables/media/css/jquery.dataTables.min.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo PROJECT_URL; ?>/script/jquery_ui/jquery-ui.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo PROJECT_URL; ?>/script/jquery-ui-timepicker/jquery-ui-timepicker-addon.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo PROJECT_URL; ?>/script/select2/select2.css" />

        <script src="<?php echo THEME_URL; ?>/js/jquery-1.12.4.js"></script>
        <script src="<?php echo THEME_URL; ?>/js/jquery.slimscroll.js"></script>
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

                $(function () {
                    $('.inner-content-list').slimScroll({
                        height: 'auto'
                    });
                });

                var w = $(window).width();
                if (w >= 0 && w <= 992)
                {
                    $(".sidemenu").before("<div class='mobmenu'><img src='image/menu-icon.png' title='menu' alt='menu' /></div>");
                    $(".mobmenu").click(function (e) {
                        $(".mainmenu").toggleClass("slow");
                    });
                }
            });
        </script>
    </head>
    <body>
        <div class="adminpanelbody greycolorbg">
            <div class="headertop orangebg">
                <div class="logo">
                    <a href="<?php echo PROJECT_URL . "?page=dashboard"; ?>"><img src="image/logo2.png" title="logo" alt="logo" /></a>
                </div>
                <div class="topnav">
                    <ul class="topstripnav">
                        <li style="font-family: opensans_bold; font-size:14px;"><a href="javascript:void(0)"><strong class="fa fa-commenting" aria-hidden="true"></strong> Live Chat</a></li>
                        <li style="font-family: opensans_bold; font-size:14px;"><strong class="fa fa-phone" aria-hidden="true"></strong> Toll Free : 1800-212-2022</li>
                        <li><span class="messagecircle bluecolor">2</span> <strong class="fa fa-bell" aria-hidden="true"></strong> Notification</li>
                        <li><span class="messagecircle greencolor">2</span> <a href="mailto:efilingwebmanager@gstkeeper.gov.in"><strong class="fa fa-envelope" aria-hidden="true"></strong>efilingwebmanager@gstkeeper.gov.in</a></li>
                        <!--<li><strong class="fa fa-question" aria-hidden="true"></strong> Support : 1800 4250 0025 / +91 80 2650 0025</li>-->
                        <li><strong class="fa fa-sign-out" aria-hidden="true"></strong> <a href="<?php echo PROJECT_URL; ?>/?page=logout">LOGOUT</a></li>
                    </ul>
                </div>
                <div class="clear"></div>
            </div>
            <!--========================header top over sidemenu start=========================-->
            <div class="sidemenu">
                <ul class="mainmenu">
                    <li class="one"><a href="<?php echo PROJECT_URL; ?>/?page=dashboard"><span class="iconpic"></span>Dashboard <strong class="fa fa-plus" aria-hidden="true"></strong></a></li>
					
					<?php if ($db_obj->can_read('client_list')) { ?>

						<li class="iconadmin"><a href="javascript:void(0)"><span class="iconpic"></span>Admin <strong class="fa fa-users" aria-hidden="true"></strong></a>
							<ul class="inner-content-list">
								<?php if ($db_obj->can_read('client_list')) { ?><li><a href="<?php echo PROJECT_URL; ?>/?page=client_list">All Client</a></li><?php } ?>
								<?php if ($db_obj->can_create('client_list')) { ?><li><a href="<?php echo PROJECT_URL; ?>/?page=client_update">Add New Client</a></li><?php } ?>
							</ul>
						</li>

					<?php } ?>

                    <?php if ($db_obj->can_read('admin_list')) { ?>

						<li class="iconadmin"><a href="javascript:void(0)"><span class="iconpic"></span>Admin <strong class="fa fa-users" aria-hidden="true"></strong></a>
                            <ul class="inner-content-list">
                                <li><a href="<?php echo PROJECT_URL; ?>/?page=user_adminupdate">Add New Admin</a></li>
                                <li><a href="<?php echo PROJECT_URL; ?>/?page=user_adminlist">All Admin</a></li>
                            </ul>
                        </li>

                    <?php } ?>
					
					<?php if ($db_obj->can_read('plan_category_list') || $db_obj->can_read('plan_list')) { ?>

						<li class="seven"><a href="javascript:void(0)"><span class="iconpic"></span>Plan <strong class="fa fa-plus" aria-hidden="true"></strong></a>
							<ul class="inner-content-list">
								<?php if ($db_obj->can_read('plan_category_list')) { ?><li><a href="<?php echo PROJECT_URL; ?>/?page=plan_categorylist">Plan Category Listing</a></li><?php } ?>
								<?php if ($db_obj->can_create('plan_category_list')) { ?><li><a href="<?php echo PROJECT_URL; ?>/?page=plan_addcategory">Add Plan Category</a></li><?php } ?>
								<?php if ($db_obj->can_read('plan_list')) { ?><li><a href="<?php echo PROJECT_URL; ?>/?page=plan_list">Plan Listing</a></li><?php } ?>
								<?php if ($db_obj->can_create('plan_list')) { ?><li><a href="<?php echo PROJECT_URL; ?>/?page=plan_addplan">Add Plan</a></li><?php } ?>
							</ul>
						</li>

					<?php } ?>

                    <?php if ($db_obj->can_read('user_group') || $db_obj->can_read('user_role')) { ?>

						<li class="iconsetting"><a href="javascript:void(0)"><span class="iconpic"></span>Settings <strong class="fa fa-plus" aria-hidden="true"></strong></a>
                            <ul class="inner-content-list">
                                <?php if ($db_obj->can_read('user_group')) { ?><li><a href="<?php echo PROJECT_URL; ?>/?page=user_group">User Group</a></li><?php } ?>
                                <?php if ($db_obj->can_read('user_role')) { ?><li><a href="<?php echo PROJECT_URL; ?>/?page=user_role">User Role</a></li><?php } ?>
                            </ul>
                        </li>

                    <?php } ?>
					
					<?php if ($db_obj->can_read('client_kyc')) { ?>

						<li class="iconsetting"><a href="javascript:void(0)"><span class="iconpic"></span>Client Settings <strong class="fa fa-plus" aria-hidden="true"></strong></a>
							<ul class="inner-content-list">
								<li><a href="<?php echo PROJECT_URL; ?>/?page=client_kycupdate">Update KYC</a></li>
							</ul>
						</li>

					<?php } ?>

                    <?php if (($db_obj->can_read('master_state')) || ($db_obj->can_read('master_unit'))  || ($db_obj->can_read('master_receiver')) || ($db_obj->can_read('master_supplier')) || ($db_obj->can_read('master_item')) || ($db_obj->can_read('client_master_item'))) { ?>

						<li class="iconsetting"><a href="javascript:void(0)"><span class="iconpic"></span>Master <strong class="fa fa-plus" aria-hidden="true"></strong></a>
							<ul class="inner-content-list">
								<?php if ($db_obj->can_read('master_state')) { ?><li><a href="<?php echo PROJECT_URL; ?>/?page=master_state">State</a></li><?php } ?>
								<?php if ($db_obj->can_read('master_unit')) { ?><li><a href="<?php echo PROJECT_URL; ?>/?page=master_unit">Unit</a></li><?php } ?>
								<?php if ($db_obj->can_read('master_receiver')) { ?><li><a href="<?php echo PROJECT_URL; ?>/?page=master_receiver">Receiver</a></li><?php } ?>
								<?php if ($db_obj->can_read('master_supplier')) { ?><li><a href="<?php echo PROJECT_URL; ?>/?page=master_supplier">Supplier</a></li><?php } ?>
								<?php if ($db_obj->can_read('master_item')) { ?><li><a href="<?php echo PROJECT_URL; ?>/?page=master_item">Item</a></li><?php } ?>
								<?php if ($db_obj->can_read('client_master_item')) { ?><li><a href="<?php echo PROJECT_URL; ?>/?page=client_item_list">Item</a></li><?php } ?>
							</ul>
						</li>

					<?php } ?>

					<!--<li class="three"><a href="javascript:void(0)"><span class="iconpic"></span>Valuation<strong class="fa fa-plus" aria-hidden="true"></strong></a></li>-->
                    
					<li class="two"><a href="javascript:void(0)"><span class="iconpic"></span>Invoices<strong class="fa fa-plus" aria-hidden="true"></strong></a>
                        <ul class="inner-content-list">
                            <?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_invoice_list'; ?>">All Tax Invoices</a></li><?php } ?>
                            <?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_create_invoice'; ?>">Add New Tax Invoice</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_upload_invoice'; ?>">Upload Tax Invoice</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_bill_of_supply_invoice_list'; ?>">All Bill Of Supply Invoice</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_create_bill_of_supply_invoice'; ?>">Add New Bill Of Supply Invoice</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_receipt_voucher_invoice_list'; ?>">All Receipt Voucher Invoice</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_create_receipt_voucher_invoice'; ?>">Add New Receipt Voucher Invoice</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_create_refund_voucher_invoice'; ?>">Add New Refund Voucher Invoice</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_create_payment_voucher_invoice'; ?>">Add New Payment Voucher Invoice</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_create_revised_tax_invoice'; ?>">Add New Revised Tax Invoice</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_create_special_tax_invoice'; ?>">Add New Special Cases Tax Invoice</a></li><?php } ?>
							<?php if ($db_obj->can_read('client_invoice')) { ?><li><a href="<?php echo PROJECT_URL . '/?page=client_create_transportation_of_goods_invoice'; ?>">Add New Transportation of Goods Invoice</a></li><?php } ?>
                        </ul>
                    </li>

                    <!-- /* comment menus */
						<li class="three"><a href="javascript:void(0)"><span class="iconpic"></span>Time of Supply<strong class="fa fa-plus" aria-hidden="true"></strong></a></li>
						<li class="four"><a href="javascript:void(0)"><span class="iconpic"></span>Payment <strong class="fa fa-plus" aria-hidden="true"></strong></a></li>
						<li class="five"><a href="javascript:void(0)"><span class="iconpic"></span>TDS<strong class="fa fa-plus" aria-hidden="true"></strong></a></li>
						<li class="six"><a href="javascript:void(0)"><span class="iconpic"></span>TCS<strong class="fa fa-plus" aria-hidden="true"></strong></a></li>
						<li class="seven"><a href="javascript:void(0)"><span class="iconpic"></span>Return <strong class="fa fa-plus" aria-hidden="true"></strong></a></li>
						<li class="seven"><a href="javascript:void(0)"><span class="iconpic"></span>Invoice Matching <strong class="fa fa-plus" aria-hidden="true"></strong></a></li>
						<li class="seven"><a href="javascript:void(0)"><span class="iconpic"></span>Input Tax Credit <strong class="fa fa-plus" aria-hidden="true"></strong></a></li>
						<li class="seven"><a href="javascript:void(0)"><span class="iconpic"></span>Refund <strong class="fa fa-plus" aria-hidden="true"></strong></a></li>
						<li class="eight"><a href="javascript:void(0)"><span class="iconpic"></span>Ticketing Tools <strong class="fa fa-plus" aria-hidden="true"></strong></a></li>
						<li class="nine"><a href="javascript:void(0)"><span class="iconpic"></span>Reports <strong class="fa fa-plus" aria-hidden="true"></strong></a></li>
					-->
                </ul>
            </div>