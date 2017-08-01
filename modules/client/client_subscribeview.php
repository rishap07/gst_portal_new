<style>
table {
    border-collapse: collapse;
}

table, td, th {
    /* border: 1px solid black; */
    padding: 10px;
}
</style>
<?php

$db_obj = new validation();
extract($_POST);


//Columns to fetch from database
//$aColumns = array('u.user_id','u.first_name', 'u.last_name', 'u.username', 
//    'u.email','u.phone_number', 's.name','p.name planname','us.*','P.no_of_client',
//    'P.company_no','P.pan_num','p.support','p.period_of_service',
//    'p.web_mobile_app','p.cloud_storage_gb','p.gst_expert_help','p.plan_price');
//
//$spWhere = " where u.user_group='3' and u.user_id = ".$_REQUEST['id']." order by us.added_date DESC LIMIT 1";
//
//$spjoin = $db_obj->getTableName('user')." u inner join " . TAB_PREFIX."subscriber_plan p"
//        ." on u.plan_id =p.id Inner Join ". TAB_PREFIX."subscriber_plan_category s"
//        ." on p.plan_category =s.id Left Join ". TAB_PREFIX."user_subscribed_plan us"
//        ." on u.plan_id =us.plan_id";
//$spQuery = " SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) . "
//            FROM $spjoin
//            $spWhere
//	";  
//
////echo $spQuery; die;
//$rResult = $db_obj->get_results($spQuery);

//select u.user_id,u.first_name, u.last_name, u.username, 
//    u.email,u.phone_number, s.name,p.name planname,us.*,P.no_of_client,
//    P.company_no,P.pan_num,p.support,p.period_of_service,
//    p.web_mobile_app,p.cloud_storage_gb,p.gst_expert_help,p.plan_price

 $query = "select u.user_id,u.first_name, u.last_name, u.username,u.gstin_number, 
    u.email,u.company_name,u.phone_number, s.name as categoryname,p.name planname,p.*,us.*,
    (case when u.payment_status='0' Then 'pending' when  u.payment_status='1' then 'accepted' when  u.payment_status='2' then 'mark as fraud' when  u.payment_status='3' then 'rejected' when  u.payment_status='4' then 'refunded' end) as payment_status
     from ".$db_obj->getTableName('user')." u Left join " . TAB_PREFIX."subscriber_plan p"
        ." on u.plan_id =p.id Left Join ". TAB_PREFIX."subscriber_plan_category s"
        ." on p.plan_category =s.id Left Join ". TAB_PREFIX."user_subscribed_plan us"
        ." on u.plan_id =us.plan_id"
         . " where u.user_group='3' and u.user_id = ".$_REQUEST['id']." order by us.added_date DESC LIMIT 1";   


        $rResult = $db_obj->get_results($query);
      //  echo $query; die;
// echo "<pre>";
//        print_r($rResult);
//        echo "</pre>";
//        die();

//select u.user_id, u.first_name, u.last_name, u.username, u.email, u.phone_number, 
//        s.name, p.name planname, us.*, P.no_of_client, P.company_no, P.pan_num, 
//        p.support, p.period_of_service, p.web_mobile_app, p.cloud_storage_gb, 
//        p.gst_expert_help, p.plan_price from gst_user u 
//        INNER join gst_subscriber_plan p on u.plan_id = p.id 
//        Inner Join gst_subscriber_plan_category s on p.plan_category =s.id 
//        Left Join gst_user_subscribed_plan us on u.user_id =us.added_by 
//        where u.user_group='3'and u.user_id=29 order by us.added_date DESC LIMIT 1 
//(case when payment_status='0' Then 'pending' when  payment_status='1' then 'accepted' when  payment_status='2' then 'mark as fraud' when  payment_status='3' then 'rejected' when  payment_status='4' then 'refunded' end) as payment_status
        ?>

  
  
  <div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>View Details</h1></div>
        <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=client_subscriber"; ?>';" class="btn btn-danger" style="float:right" />
        <div class="whitebg formboxcontainer">
       
    
     
        <div class="clear"></div>
        <?php 
        if(!empty($rResult))
        {
        ?>
        <table style="width:80% ">


    <tr>
        <td>User Name</td>
        <td><?php echo $rResult[0]->username ?></td>
    </tr>
     <tr>
        <td>Email</td>
        <td><?php echo $rResult[0]->email ?></td>
    </tr> 
    <tr>
        <td>Company Name</td>
        <td><?php echo $rResult[0]->company_name ?></td>
    </tr>
     <tr>
        <td>Gstn No.</td>
        <td><?php echo $rResult[0]->gstin_number ?></td>
    </tr>
    
    
     <tr>
        <td>Phone No.</td>
        <td><?php echo $rResult[0]->phone_number ?></td>
    </tr>
     <tr>
        <td>Plan Name</td>
        <td><?php echo '<b>'. $rResult[0]->categoryname.'</b>:'.$rResult[0]->planname ?></td>
    </tr>
    <tr>
        <td>Plan Start date</td>
        <td><?php echo date('Y-m-d', strtotime($rResult[0]->plan_start_date)) ?></td>
    </tr>
     <tr>
        <td>Plan Due Date</td>
        <td><?php echo date('Y-m-d', strtotime($rResult[0]->plan_due_date)) ?></td>
    </tr>
     <tr>
        <td>Plan Payment Method</td>
        <td><?php echo $rResult[0]->payment_method ?></td>
    </tr>
     <tr>
        <td>Payment Status</td>
        <td><?php echo $rResult[0]->payment_status ?></td>
    </tr>
     <tr>
        <td>NO. Of Client</td>
        <td><?php echo $rResult[0]->no_of_client ?></td>
    </tr>
    <tr>
        <td>No. of company</td>
        <td><?php echo $rResult[0]->company_no ?></td>
    </tr>
    <tr>
        <td>Pan No.</td>
        <td><?php echo $rResult[0]->pan_num ?></td>
    </tr>
    <tr>
        <td>Support</td>
        <td><?php echo $rResult[0]->support ?></td>
    </tr>
    <tr>
        <td>Period of Service</td>
        <td><?php echo $rResult[0]->period_of_service ?></td>
    </tr>
    <tr>
        <td>Web APP</td>
        <td><?php echo $rResult[0]->web_mobile_app ?></td>
    </tr>
    <tr>
        <td>Cloud Storage in GB</td>
        <td><?php echo $rResult[0]->cloud_storage_gb ?></td>
    </tr>
    <tr>
        <td>Gst Expert Help</td>
        <td><?php echo $rResult[0]->gst_expert_help ?></td>
    </tr>
    <tr>
        <td>Plan Price</td>
        <td><?php echo $rResult[0]->plan_price ?></td>
    </tr>

</table>

        <?php }
        else
        {
            '<h2> NO Record Found </h2>';
        } 
        ?>
                </div>
                   
                </div>
</div> 

<?php
//select u.user_id, p.plan_price as planprice,c.name,c.coupon_value,c.type from gst_user u 
//Left join gst_subscriber_plan p 
//on u.plan_id =p.id 
//left join  gst_coupon c
//on u.coupon=c.name
//and user_group='3' and payment_status='1'
?>