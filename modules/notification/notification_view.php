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
if((isset($_GET["id"])) && (!empty($_GET["id"])))
{
        $dataArr = array();
        $dataArr = $db_obj->getUserDetailsById($db_obj->sanitize($_SESSION['user_detail']['user_id']));
		
	    $count=1;
		$flag=0;
		$sql="select *,u.status as nstatus  from " . $db_obj->getTableName('notification') . " as n INNER join " . $db_obj->getTableName('user_notification') . " as u on u.notification_id=n.notification_id  where n.status='1' and u.user_id='".$_SESSION["user_detail"]["user_id"]."' order by u.notification_id desc";
		
		$dataNotification = $db_obj->get_results($sql);
        if(!empty($dataNotification))
				{
					
			foreach($dataNotification as $dataItem)
					  {
					 
						  if((date('Y-m-d H:i:s')>=$dataItem->start_date) && (date('Y-m-d H:i:s') <= $dataItem->end_date))
						  {
								 
							  if($dataItem->vendor_list==0)
							  {
								if($dataItem->notification_id==$_GET["id"])
								{
									$flag=1;
								}
								
								 //$message .="<li><a href=''>".$count.' '.$dataItem->notification_message. "</a></li>";
								  $message .="<li>".$count.' '.$dataItem->notification_message. "</li>";
								  //$message = $message."<br>";
								  $count = $count+1;
							  }
							  if(isset($dataArr['data']->kyc->vendor_type) && $dataArr['data']->kyc->vendor_type==$dataItem->vendor_list)
							  {
								  
								if($dataItem->notification_id==$_GET["id"])
								{
									$flag=1;
								}
						
							    //$message .="<li><a href=''>".$count.' '.$dataItem->notification_message. "</a></li>";
								$message .="<li>".$count.' '.$dataItem->notification_message. "</li>";
								 
									 $count = $count+1;
									
							   }
						  }
						 
						
					     }
			   if($flag==1)
			   {
				 
			   }
			  // return false;
				
		    } 
			else
			{
//return false;
			}
    
		if($flag==1)
		{
		$query ="select * from " . $db_obj->getTableName('notification') . " as n where notification_id='".$_GET["id"]."' and status='1'";
		$rResult = $db_obj->get_results($query);
		$dataConditionArray=array();
		$dataArr=array();
		 $dataConditionArray['notification_id'] = $db_obj->sanitize($_GET["id"]);
		 $dataConditionArray['user_id'] = $_SESSION["user_detail"]["user_id"];
		$dataArr['status_updated_date'] = date('Y-m-d H:i:s');
		$dataArr['updated_by'] = $_SESSION["user_detail"]["user_id"];
		$dataArr['status'] = 1;
		if ($db_obj->update(TAB_PREFIX.'user_notification', $dataArr, $dataConditionArray)) {
      	//$this->setSuccess("Your Notification information updated successfully");
		$db_obj->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . " update the notification info","notification");
												//return true;
											
		} else {

				//$db_obj->setError($db_obj->validationMessage['failed']);
				//return false;							
			}										
																			 
		}else{
			 $db_obj->setError("You are not authorized to view this notification");
			$db_obj->redirect(PROJECT_URL."/?page=dashboard");
		}
}else{
	$db_obj->setError("You are not authorized to direct access this module");
	$db_obj->redirect(PROJECT_URL."/?page=dashboard");
}
      ?>
  
  
  <div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Notification Details</h1></div>
        <div class="whitebg formboxcontainer">
       
    
     
        <div class="clear"></div>
        <?php 
        if(!empty($rResult))
        {
        ?>
        <table style="width:80% ">


    <tr>
        <td>Title</td>
        <td><?php echo $rResult[0]->notification_name ?></td>
    </tr>
     <tr>
        <td>Message</td>
        <td><?php echo html_entity_decode($rResult[0]->notification_message); ?></td>
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

