
<style>
    .dasboardbox {
        width: 22%!important;
    }
</style>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr">
    <div class="col-md-12 col-sm-11 col-xs-12 mobpadlr">
        <?php $db_obj->showErrorMessage(); ?>
        <?php $db_obj->showSuccessMessge(); ?>
        <?php
        $db_obj->unsetMessage();
        $is_deleted = 0;
        
        $from_date = isset($_POST['from_date']) ? $_POST['from_date'] : date('Y-m')."-01";
        $to_date = isset($_POST['to_date']) ? $_POST['to_date'] :  date('Y-m-d');
        
    
            $query = "select count(user_id) as totalsubcriber "
                    . "from " . $db_obj->getTableName('user') . " where  "
                    . "is_deleted='" . $is_deleted . "' and user_group='3' ";
            
            $queryplan = "select count(user_id) as totalsubcriber "
                    . "from " . $db_obj->getTableName('user') . " where  "
                     . " is_deleted='" . $is_deleted . "' and user_group='3' and plan_id!='21' and email not like 'aditya.kumar_@cyfuture.com' and payment_status='1' ";
                    //. "is_deleted='" . $is_deleted . "' and user_group='3' and payment_status='1' ";
            
            $queryprice = "select u.user_id,u.email, p.plan_price as planprice,c.name,c.coupon_value,c.type "
                    . "from " . $db_obj->getTableName('user') .
                    " u Inner join " . TAB_PREFIX . "subscriber_plan p"
                    . " on u.plan_id =p.id" .
                    " Left join " . TAB_PREFIX . "coupon c"
                    . " on u.coupon =c.name"
                    . " where u.user_group='3' and u.plan_id!='21' and u.email not like 'aditya.kumar_@cyfuture.com' and u.payment_status='1' ";
            if ($from_date < $to_date) {
                // $obj_client->setError('Start date can not be less than to date');
            }

            /* code for current month totalsale */

            if ($from_date != '') {
                $query.="and added_date >= '" . $from_date . " 00:00:00'";
                $queryplan.="and added_date >= '" . $from_date . " 00:00:00'";
                $queryprice.="and u.added_date >= '" . $from_date . " 00:00:00'";
            }
            if ($to_date != '') {
                $query.="and added_date <= '" . $to_date . " 23:59:59'";
                $queryplan.="and added_date <= '" . $to_date . " 23:59:59'";
                $queryprice.="and u.added_date <= '" . $to_date . " 23:59:59'";
            }

            
            
            //echo $queryprice; die; old
            $Total_sbuscriber = $db_obj->get_results($query);
            $Total_Purchase_Plan = $db_obj->get_results($queryplan);
            $Total_incomearr = $db_obj->get_results($queryprice);
                                    
            $subsprice = 0;
            $discountpriceper = 0;
            $discountpricers = 0;
            $totaldiscount = 0;
            $TotalIncome = 0;
            $TotalIgst = 0;
            if (!empty($Total_incomearr)) {
                foreach ($Total_incomearr as $key => $value) {
                    if (!empty($value->coupon_value)) {
                        if (!empty($value->type)) {
                            $discountpriceper+=(($value->planprice * $value->coupon_value) / 118);
                        } else {
                            $discountpricers+=$value->coupon_value;
                        }
                    }
                    $subsprice+= $value->planprice;
                }
                $totaldiscount+=$discountpriceper + $discountpricers;
            //  echo 'total ='.$subsprice.'discount ='.$totaldiscount; die();
                $TotalIncome = round($subsprice, 2, PHP_ROUND_HALF_DOWN) - round($totaldiscount, 2, PHP_ROUND_HALF_DOWN);
               $TotalIgst = round(($TotalIncome * 0.18), 2, PHP_ROUND_HALF_DOWN);
            }
            
      
            
            
            
//             $Total_sbuscriber = $db_obj->get_results($query);
//            $Total_Purchase_Plan = $db_obj->get_results($queryplan);
//            $Total_incomearr = $db_obj->get_results($queryprice);
//            
//            $subsprice = 0;
//            $discountpriceper = 0;
//            $discountpricers = 0;
//            $totaldiscount = 0;
//            $TotalIncome = 0;
//            $TotalIgst = 0;
           
//           echo "<pre>";
//           print_r($Total_incomearr);
//           die();
//           echo "</pre>";
//            if (!empty($Total_incomearr)) {
//                foreach ($Total_incomearr as $key => $value) {
//                    $discount=0;
//                    $discountpriceper=0;
//                    $discountpricers=0;
//                    if (!empty($value->coupon_value)) {
//                        if (!empty($value->type)) {
//                            $discountpriceper=(($value->planprice * $value->coupon_value) / 118);
//                            //$discountpriceper+=(($value->planprice * $value->coupon_value) / 118);
//                        } else {
//                            $discountpricers=$value->coupon_value;
//                            //$discountpricers+=$value->coupon_value;
//                        }
//                    }
//                    $subsprice= $value->planprice;
//                   // $subsprice+= $value->planprice;
//                    if(!empty($discountpriceper)|| !empty($discountpricers))
//                    {
//                        if(!empty($discountpriceper))
//                        {
//                          $discount= $discountpriceper;
//                        }
//                        elseif(!empty($discountpricers))
//                        {
//                          $discount= $discountpricers;   
//                        }
//                        else
//                        {
//                           $discount= 0;  
//                        }
//                    }
//                    
//                    echo 'userid '.$value->user_id.' name '.$value->email .' total ='.$subsprice.' discount ='.$discount."</br>";
//                   
//                }
//                $totaldiscount+=$discountpriceper + $discountpricers;
//                //echo 'total ='.$subsprice.'discount ='.$totaldiscount; die();
//                $TotalIncome = round($subsprice, 2, PHP_ROUND_HALF_DOWN) - round($totaldiscount, 2, PHP_ROUND_HALF_DOWN);
//               $TotalIgst = round(($TotalIncome * 0.18), 2, PHP_ROUND_HALF_DOWN);
//            }
        
        ?>
        <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Dashboard Overview</h1></div>


        <div class="listcontent">


            <div class="row dashtopbox">
                <form method="post" enctype="multipart/form-data" name="client-dashboard" id='client-dashboard'>
                    <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <label>From Date<span class="starred">*</span></label>
                        <input type="text" placeholder="yyyy-mm-dd" name="from_date" id="from_date" value="<?php
        if (isset($_POST["from_date"])) {
            echo $_POST["from_date"];
        } else {
            echo date('Y-m-01');
        }
        ?>" class="required form-control" data-bind="date" 
                               />
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <label>To Date<span class="starred">*</span></label>
                        <input type="text" placeholder="yyyy-mm-dd" name="to_date" id="to_date" value="<?php
                        if (isset($_POST["to_date"])) {
                            echo $_POST["to_date"];
                        } else {
                            echo date('Y-m-d');
                        }
        ?>" class="required form-control" data-bind="date"
                               />
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12 form-group text-left">

                        <input type='submit' class="btn btn-danger martop20" name='submit' value='Filter' id='submit'>


                    </div>
                </form>
                <div class="clear"></div>


                <div class="dasboardbox">
                    <div class="lightblue dashtopcol">
                        <div class="dashcoltxt">
                            <span class="boxpricetxt">
                                <?php echo $Total_sbuscriber[0]->totalsubcriber; ?></span><br />
                            <div class="txtyear">No of subscriber</div>
                        </div>
                    </div>
                </div>


                <div class="dasboardbox">
                    <div class="lightgreen dashtopcol">
                        <div class="dashcoltxt">
                            <span class="boxpricetxt">

                                <?php echo $Total_Purchase_Plan[0]->totalsubcriber; ?>
                            </span><br /><div class="txtyear">No of Plan Purchase</div>
                        </div>
                    </div>
                </div>
                
                <div class="dasboardbox">
                    <div class="pinkbg dashtopcol">
                        <div class="dashcoltxt">
                            <span class="boxpricetxt"><i class="fa fa-inr" aria-hidden="true"></i>

                                <?php echo $TotalIncome; ?>
                            </span><br /><div class="txtyear">Total Sale</div>
                        </div>
                    </div>
                </div>
                <div class="dasboardbox ">
                    <div class="perpalbg dashtopcol">
                        <div class="dashcoltxt">
                            <span class="boxpricetxt"><i class="fa fa-inr" aria-hidden="true"></i>

                                <?php echo $TotalIgst; ?>
                            </span><br /><div class="txtyear">Total IGST</div>
                        </div>
                    </div>
                </div>



            </div><!--/row-->    
            <!--/col-12-->
        </div><!--/row-->



        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class=" whitebg dashleftbox">
                <div class="boxheading">New Subscriber List <a href="<?php echo PROJECT_URL;?>/?page=client_demouser_list" style="float: right;border: 1px solid;padding: 7px 5px;margin-top: -10px;">Demo User</a></div>
                <div class="border"></div>
                <div class="listcontent">
                    <?php $dataArrs = $db_obj->getSubscriber($is_deleted = '0', $orderby = 'user_id desc', $limit = 'limit 0,5'); ?>
                    <ul>
                        <?php
                        if (!empty($dataArrs)) {
                            foreach ($dataArrs as $dataArr) {
                                $color = '';
                                switch ($dataArr->payment_status) {
                                    case 'pending':
                                        $color = 'blue';
                                        break;
                                    case 'accepted':
                                        $color = 'green';
                                        break;
                                    case 'rejected':
                                        $color = 'red';
                                        break;
                                    case 'mark as fraud':
                                        $color = 'orange';
                                        break;
                                    default:
                                        $color = 'blue';
                                }
                                ?>
                                <li><?php echo ucwords($dataArr->first_name . " " . $dataArr->last_name); ?> (<?php echo $dataArr->username; ?>) <span><a href="#" class="<?php echo $color; ?>txt"><?php echo ucwords($dataArr->payment_status); ?></a></span></li>
                                <?php
                            }
                        } else {
                            ?>
                            <li>No User Added Yet</li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
                <div class="clear height30"></div>
                <div class="tc">
                    <!--                    <a href="javascript:void(0)" class="greenbtnborder animation">+ Add More</a> -->

                    <a href="<?php echo PROJECT_URL; ?>/?page=client_subscriber" class="redbtnborder animation" style="margin-left:5px;">VIEW ALL</a></div>
            </div>   
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class=" whitebg dashleftbox">
                <div class="boxheading">Plans List</div>
                <div class="border"></div>
                <div class="listcontent">
                    <?php
                    $dataArrs = $db_obj->getAllActivePlanSuAdmin("p.id,p.name,(case when p.status='1' Then 'active' when p.status='0' then 'deactive' end) as status,c.name as cat_name", "p.is_deleted='0' and p.added_by='" . $_SESSION['user_detail']['user_id'] . "'", $orderby = 'p.id desc', $limit = 'limit 0,5');
                    ?>
                    <ul>
                        <?php
                        if (!empty($dataArrs)) {
                            foreach ($dataArrs as $dataArr) {
                                $color = '';
                                switch ($dataArr->status) {
                                    case 'active':
                                        $color = 'green';
                                        break;
                                    case 'deactive':
                                        $color = 'red';
                                        break;
                                    default:
                                        $color = 'blue';
                                }
                                ?>
                                <li><?php echo ucwords($dataArr->name); ?> (<?php echo ucwords($dataArr->cat_name); ?>) <span><a href="<?php echo PROJECT_URL; ?>/?page=plan_editplan&action=editPlan&id=<?php echo $dataArr->id; ?>" class="<?php echo $color; ?>txt"><?php echo ucwords($dataArr->status); ?></a></span></li>
                                <?php
                            }
                        } else {
                            ?>
                            <li>No Plans Added Yet</li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
                <div class="clear height30"></div>
                <div class="tc"><a href="<?php echo PROJECT_URL; ?>/?page=plan_addplan" class="greenbtnborder">+ Add More</a> 
                    <a href="<?php echo PROJECT_URL; ?>/?page=plan_list" class="redbtnborder" style="margin-left:5px;">VIEW ALL</a>
                </div>
            </div>   
        </div>
        <div class="dasfooter">Copyright @ by GST Keeper</div>  	
    </div>
<!--    <div class="col-md-2 col-sm-3 col-xs-12 dash-rightnav">
        <?php include(PROJECT_ROOT . "/modules/dashboard/view/rightpanel.php"); ?>
    </div>-->
</div>
<script>
    $(document).ready(function () {

        /* from date datepicker */
        $("#from_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            yearRange: '2017:<?php echo date("Y"); ?>',
            maxDate: '0'
        });
        /* from date datepicker */
        $("#to_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            yearRange: '2017:<?php echo date("Y"); ?>',
            maxDate: '0'
        });

    });

</script>