<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

final class notification extends validation {
    
    function __construct() {
        parent::__construct();
    }
       
  
  
   
	 
	private function getnotificationData()
	{
		 $dataArr = array();
		
		
		  $dataArr['notification_name'] = isset($_POST['notification_name']) ? $_POST['notification_name'] : '';
		  $dataArr['notification_message'] = isset($_POST['notification_message']) ? $_POST['notification_message'] : '';
		  $dataArr['notification_message']  = str_replace("<p>"," ",$dataArr['notification_message']);
		  $dataArr['notification_message']  = str_replace("</p>"," ",$dataArr['notification_message']);
		  
		 $dataArr['start_date'] = isset($_POST['start_date']) ? $_POST['start_date'] : '';
		  $dataArr['end_date'] = isset($_POST['end_date']) ? $_POST['end_date'] : '';
		 $dataArr['fromtime'] = isset($_POST['fromtime']) ? $_POST['fromtime'] : '';
		 $dataArr['endtime'] = isset($_POST['endtime']) ? $_POST['endtime'] : '';
	     $dataArr['vendor_list'] = isset($_POST['vendor_type']) ? $_POST['vendor_type'] : '';
	
		  $dataArr['status'] = isset($_POST['notification_status']) ? $_POST['notification_status'] : '';
		  //$dataArr['start_date'] =$dataArr['start_date'].':'. $_POST['fromtime'];
	      //$dataArr['end_date'] = $dataArr['end_date'].':'. $_POST['endtime'];
	//	$dataArr['start_date'] =$dataArr['start_date'];
	      //$dataArr['end_date'] = $dataArr['end_date'];
		
		 return $dataArr;
		 
       
	}
	public  function strip_tags_content($str) {

    $str = preg_replace("#<(.*)/(.*)>#iUs", "", $str);
	return $str;

 }
	public function showNotificationData()
	{
		$dataArr = array();
        $dataArr = $this->getUserDetailsById($this->sanitize($_SESSION['user_detail']['user_id']));
		$message="";
	    $count=1;
		$flag=0;
		$dataNotification = $this->get_results("select *, DATE_FORMAT(added_date, '%d %M %Y %r') as added_date from " . $this->getTableName('notification') . " where status='1'");
        if(!empty($dataNotification))
				{
					
			foreach($dataNotification as $dataItem)
					  {
					 
						  if((date('Y-m-d H:i:s')>=$dataItem->start_date) && (date('Y-m-d H:i:s') <= $dataItem->end_date))
						  {
								  $flag=1;
							  if($dataItem->vendor_list==0)
							  {
								 if( $this->checkNotificationDetail($dataItem->notification_id))
								 {
								 }
								  $message= $message.$count.' '.$dataItem->notification_message;
								  $message = $message."<br>";
								  $count = $count+1;
							  }
							 if(isset($dataArr['data']->kyc->vendor_type) && $dataArr['data']->kyc->vendor_type==$dataItem->vendor_list)
							  { 
								if( $this->checkNotificationDetail($dataItem->notification_id))
								 {
								 }
						
							
								   $message= $message.$count.' '.$dataItem->notification_message;
									$message = $message."<br>";
									 $count = $count+1;
									
							   }
						  }
						  else if($dataItem->parentid > 0 )
						  {
							  
							  if( $this->checkReturnNotificationDetail($dataItem->notification_id,$dataItem->parentid))
								 {
								 }
						  }
						  else{
						  }
						 
						
					  }
			   if($flag==1)
			   {
				return $message;
			   }
			   return false;
				
				  } 
					
	}
	public function showNotificationUpdate()
	{
		$dataArr = array();
        $dataArr = $this->getUserDetailsById($this->sanitize($_SESSION['user_detail']['user_id']));
		$message="<ul class='noti-ul'>";
	    $count=1;
		$flag=0;
	 $sql="select *,u.status as nstatus,DATE_FORMAT(added_date, '%d %M %Y %r') as added_date  from " . $this->getTableName('notification') . " as n INNER join " . $this->getTableName('user_notification') . " as u on u.notification_id=n.notification_id  where n.status='1' and u.user_id='".$_SESSION["user_detail"]["user_id"]."' order by u.notification_id desc limit 0,2";
			
		$dataNotification = $this->get_results($sql);
        if(!empty($dataNotification))
				{
					
			foreach($dataNotification as $dataItem)
					  {
					 
						  if((date('Y-m-d H:i:s')>=$dataItem->start_date) && (date('Y-m-d H:i:s') <= $dataItem->end_date))
						  {
								  $flag=1;
							  if($dataItem->vendor_list==0)
							  {
								 if( $this->checkNotificationDetail($dataItem->notification_id))
								 {
								 }
								
								// $message .="<li><a href='".PROJECT_URL. "?page=notification_view&id=".$dataItem->notification_id."'>".$count.' '.$this->strip_tags_content(html_entity_decode($dataItem->notification_message)). "</a></li>";
								$message .="<li><a href='".PROJECT_URL. "?page=notification_view&id=".$dataItem->notification_id."'>".$count.')'.' '.$this->strip_tags_content(html_entity_decode($dataItem->notification_name)). "</a></li>";
								
								 $count = $count+1;
							  }
							  if(isset($dataArr['data']->kyc->vendor_type) && $dataArr['data']->kyc->vendor_type==$dataItem->vendor_list)
							  {
								  
								if( $this->checkNotificationDetail($dataItem->notification_id))
								 {
								 }
						
							   // $message .="<li><a href='".PROJECT_URL. "?page=notification_view&id=".$dataItem->notification_id."'>".$count.' '.$this->strip_tags_content(html_entity_decode($dataItem->notification_message)). "</a></li>";
							 $message .="<li><a href='".PROJECT_URL. "?page=notification_view&id=".$dataItem->notification_id."'>".$count.')'.' '.$this->strip_tags_content(html_entity_decode($dataItem->notification_name)). "</a></li>";
							 $count = $count+1;
									
							   }
						  }
						  else if($dataItem->parentid > 0)
						  {
							 
							 $this->checkReturnNotificationDetail($dataItem->notification_id,$dataItem->parentid);
							 $message .="<li><a href='".PROJECT_URL. "?page=notification_view&id=".$dataItem->notification_id."'>".$count.')'.' '.$this->strip_tags_content(html_entity_decode($dataItem->notification_name)). "</a></li>";
							 $count = $count+1;
						  }
						  else{
							  
						  }
						 
						
					     }
			   if($flag==1)
			   {
				 $message .="</ul>";  
				return $message;
			   }
			   return false;
				
		    } 
			else
			{
				return false;
			}
					
	}
	public function checkNotificationStatus()
	{
		$dataArr = array();
        $dataArr = $this->getUserDetailsById($this->sanitize($_SESSION['user_detail']['user_id']));
		$message="";
	    $count=0;
		$flag=0;
		$sql="select *,u.status as nstatus  from " . $this->getTableName('notification') . " as n INNER join " . $this->getTableName('user_notification') . " as u on u.notification_id=n.notification_id  where u.user_id='".$_SESSION["user_detail"]["user_id"]."' order by u.notification_id desc";
		$dataNotification = $this->get_results($sql);
        if(!empty($dataNotification))
				{
					
			foreach($dataNotification as $dataItem)
					  {
						 if($dataItem->nstatus==0)
						 {
							 $flag=1;
							 $count=$count+1; 
						 }
						  
												
					  }
				   if($flag==1)
				   {
					return $count;
				
				   }
				   return 0;
					
		    } 
			else
			{
				return 0;
			}
					
	}
	public function totalNotification()
	{
		$dataArr = array();
        $dataArr = $this->getUserDetailsById($this->sanitize($_SESSION['user_detail']['user_id']));
		$message="";
	    $count=1;
		$flag=0;
		 $sql="select * from " . $this->getTableName('notification') . " as n INNER join " . $this->getTableName('user_notification') . " as u on u.notification_id=n.notification_id  where u.status='0' and u.user_id='".$_SESSION["user_detail"]["user_id"]."' order by u.notification_id desc";
		$dataNotification = $this->get_results($sql);
        if(!empty($dataNotification))
				{
					
			foreach($dataNotification as $dataItem)
					  {
					 
						  if((date('Y-m-d H:i:s')>=$dataItem->start_date) && (date('Y-m-d H:i:s') <= $dataItem->end_date))
						  {
								  $flag=1;
							  if($dataItem->vendor_list==0)
							  {
								 if( $this->checkNotificationDetail($dataItem->notification_id))
								 {
								 }
								  $message= $message.$count.' '.$dataItem->notification_message;
								  $message = $message."<br>";
								  $count = $count+1;
							  }
							 if(isset($dataArr['data']->kyc->vendor_type) && $dataArr['data']->kyc->vendor_type==$dataItem->vendor_list)
							  {
								  
								if( $this->checkNotificationDetail($dataItem->notification_id))
								 {
								 }
						
							
								   $message= $message.$count.' '.$dataItem->notification_message;
									$message = $message."<br>";
									 $count = $count+1;
									
							   }
						  }
						 
						
					  }
			   if($flag==1)
			   {
				return $count;
			   }
			   return 1;
				
				  } else{
					  return 1;
				  }
					
	}
	public function totalNotificationCount()
	{
		$dataArr = array();
        $dataArr = $this->getUserDetailsById($this->sanitize($_SESSION['user_detail']['user_id']));
		$message="";
	    $count=1;
		$flag=0;
		 $sql="select * from " . $this->getTableName('notification') . " as n INNER join " . $this->getTableName('user_notification') . " as u on u.notification_id=n.notification_id  where u.user_id='".$_SESSION["user_detail"]["user_id"]."' order by u.notification_id desc";
		$dataNotification = $this->get_results($sql);
        if(!empty($dataNotification))
				{
					
			foreach($dataNotification as $dataItem)
					  {
					 
						  //if((date('Y-m-d H:i:s')>=$dataItem->start_date) && (date('Y-m-d H:i:s') <= $dataItem->end_date))
						  //{
								  $flag=1;
							  if($dataItem->vendor_list==0)
							  {
								 if( $this->checkNotificationDetail($dataItem->notification_id))
								 {
								 }
								  $message= $message.$count.' '.$dataItem->notification_message;
								  $message = $message."<br>";
								  $count = $count+1;
							  }
							 if(isset($dataArr['data']->kyc->vendor_type) && $dataArr['data']->kyc->vendor_type==$dataItem->vendor_list)
							  {
								  
								if( $this->checkNotificationDetail($dataItem->notification_id))
								 {
								 }
						
							
								   $message= $message.$count.' '.$dataItem->notification_message;
									$message = $message."<br>";
									 $count = $count+1;
									
							   }
						  //}
						 
						
					  }
			   if($flag==1)
			   {
				return $count;
			   }
			   return 1;
				
				  } else{
					  return 1;
				  }
					
	}
	public function totalNotificationShow()
	{
		$dataArr = array();
        $dataArr = $this->getUserDetailsById($this->sanitize($_SESSION['user_detail']['user_id']));
		//$message="";
		$message="<ul class='noti-ul'>";
	    $count=1;
		$flag=0;
		 $sql="select * from " . $this->getTableName('notification') . " as n INNER join " . $this->getTableName('user_notification') . " as u on u.notification_id=n.notification_id  where n.status='1' and u.user_id='".$_SESSION["user_detail"]["user_id"]."' order by u.notification_id desc limit 0,2";
		$dataNotification = $this->get_results($sql);
        if(!empty($dataNotification))
				{
					
			foreach($dataNotification as $dataItem)
					  {
					 
						  //if((date('Y-m-d H:i:s')>=$dataItem->start_date) && (date('Y-m-d H:i:s') <= $dataItem->end_date))
						  //{
								  $flag=1;
							  if($dataItem->vendor_list==0)
							  {
								 if( $this->checkNotificationDetail($dataItem->notification_id))
								 {
								 }
								 
								 // $message= $message.$count.' '.$this->strip_tags_content(html_entity_decode($dataItem->notification_name));
								  //$message = $message."<br>";
								  $message .="<li><a href='".PROJECT_URL. "?page=notification_view&id=".$dataItem->notification_id."'>".$count.')'.' '.$this->strip_tags_content(html_entity_decode($dataItem->notification_name)). "</a></li>";
								
								  $count = $count+1;
							  }
							 if(isset($dataArr['data']->kyc->vendor_type) && $dataArr['data']->kyc->vendor_type==$dataItem->vendor_list)
							  {
								  
								if( $this->checkNotificationDetail($dataItem->notification_id))
								 {
								 }
						
							
								   //$message= $message.$count.' '.$this->strip_tags_content(html_entity_decode($dataItem->notification_name));
									//$message = $message."<br>";
									$message .="<li><a href='".PROJECT_URL. "?page=notification_view&id=".$dataItem->notification_id."'>".$count.')'.' '.$this->strip_tags_content(html_entity_decode($dataItem->notification_name)). "</a></li>";
								 $count = $count+1;
									
							   }
							    else if($dataItem->parentid > 0)
						  {
							 
							 $this->checkReturnNotificationDetail($dataItem->notification_id,$dataItem->parentid);
							 $message .="<li><a href='".PROJECT_URL. "?page=notification_view&id=".$dataItem->notification_id."'>".$count.')'.' '.$this->strip_tags_content(html_entity_decode($dataItem->notification_name)). "</a></li>";
							 $count = $count+1;
						  }
						  else{
							  
						  }
						  //}
						 
						
					  }
			   if($flag==1)
			   {
				//return $count;
				 $message .="</ul>";  
				return $message;
			   }
			   return 1;
				
				  } 
					
	}
	  public function checkNotificationDetail($notification_id) {
        $sql = "select * from " . TAB_PREFIX . "user_notification where notification_id=" . $notification_id . " and user_id='" . $_SESSION["user_detail"]["user_id"] . "'";
         
        $clientdata = $this->get_results($sql);

        if (empty($clientdata)) {

            $dataArr['user_id'] = $_SESSION["user_detail"]["user_id"];
            $dataArr['notification_id'] = $notification_id;
            $dataArr['status'] = 0;

            if ($this->insert(TAB_PREFIX . 'user_notification', $dataArr)) {
					$this->logMsg("User ID Notification added : " . $_SESSION['user_detail']['user_id'],"notification");
											
                //$this->setSuccess('GSTR2 Saved Successfully');
                return true;
            } else {
               // $this->setError('Failed to save notification data');
                return false;
            }
        }
    }
	 public function checkReturnNotificationDetail($notification_id,$userid) {
        $sql = "select * from " . TAB_PREFIX . "user_notification where notification_id=" . $notification_id . " and user_id='" . $userid . "'";
         
        $clientdata = $this->get_results($sql);

        if (empty($clientdata)) {

            $dataArr['user_id'] = $userid;
            $dataArr['notification_id'] = $notification_id;
            $dataArr['status'] = 0;

            if ($this->insert(TAB_PREFIX . 'user_notification', $dataArr)) {
					$this->logMsg("User ID Notification added : " . $_SESSION['user_detail']['user_id'],"notification");
											
                //$this->setSuccess('GSTR2 Saved Successfully');
                return true;
            } else {
               // $this->setError('Failed to save notification data');
                return false;
            }
        }
    }
	public function updateuserNotificationDetail()
	{
		
		$dataConditionArray['user_id'] = $_SESSION["user_detail"]["user_id"];
		$dataArr['status_updated_date'] = date('Y-m-d H:i:s');
		$dataArr['updated_by'] = $_SESSION["user_detail"]["user_id"];
		$dataArr['status'] = 1;
											
		if ($this->update(TAB_PREFIX.'user_notification', $dataArr, $dataConditionArray)) {
//$this->setSuccess("Your Notification information updated successfully");
		$this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . " update the notification info","notification");
												//return true;
		} else {

			$this->setError($this->validationMessage['failed']);
			return false;
		}		
																			
		/*
		        $message="";
				$count=1;
				$flag=0;
		        $sql="select * from " . $this->getTableName('notification') . " as n INNER join " . $this->getTableName('user_notification') . " as u on u.notification_id=n.notification_id  where n.status='1' and  u.user_id='".$_SESSION["user_detail"]["user_id"]."' order by u.notification_id desc";
				$dataNotification = $this->get_results($sql);
                if(!empty($dataNotification))
				{
					
						foreach($dataNotification as $dataItem)
					   {

						 
						  if((date('Y-m-d H:i:s')>=$dataItem->start_date) && (date('Y-m-d H:i:s') <= $dataItem->end_date))
						  {
							  $flag=1;
						  if($dataItem->vendor_list==0)
						  {
							 
									 $sql = "select * from " . TAB_PREFIX . "user_notification where notification_id=" . $dataItem->notification_id . " and user_id='" . $_SESSION["user_detail"]["user_id"] . "'";

									$clientdata = $this->get_results($sql);

									if (empty($clientdata)) {

										$dataArr['user_id'] = $_SESSION["user_detail"]["user_id"];
										$dataArr['notification_id'] = $notification_id;
										$dataArr['status'] = 1;

										if ($this->insert(TAB_PREFIX . 'user_notification', $dataArr)) {
											//$this->setSuccess('GSTR2 Saved Successfully');
												$this->logMsg("User ID Notification added : " . $_SESSION['user_detail']['user_id'],"notification");
											
											return true;
										} else {
											$this->setError('Failed to save notification data');
											return false;
										}
									}
									else
									{
											 //$dataConditionArray['notification_id'] = $dataItem->notification_id;
											  $dataConditionArray['user_id'] = $_SESSION["user_detail"]["user_id"];
											 $dataArr['status_updated_date'] = date('Y-m-d H:i:s');
											 $dataArr['updated_by'] = $_SESSION["user_detail"]["user_id"];
											 $dataArr['status'] = 1;
											
											if ($this->update(TAB_PREFIX.'user_notification', $dataArr, $dataConditionArray)) {

												//$this->setSuccess("Your Notification information updated successfully");
												$this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . " update the notification info","notification");
												//return true;
											} else {

												$this->setError($this->validationMessage['failed']);
												return false;
											}
									}
									
			                 
							 
						  
					       
						  }
						 if(isset($dataArr['data']->kyc->vendor_type) && $dataArr['data']->kyc->vendor_type==$dataItem->vendor_list)
							  { 
						  
							    $sql = "select * from " . TAB_PREFIX . "user_notification where notification_id=" . $dataItem->notification_id . " and user_id='" . $_SESSION["user_detail"]["user_id"] . "'";

									$clientdata = $this->get_results($sql);

									if (empty($clientdata)) {

										$dataArr['user_id'] = $_SESSION["user_detail"]["user_id"];
										$dataArr['notification_id'] = $notification_id;
										$dataArr['status'] = 1;

										if ($this->insert(TAB_PREFIX . 'user_notification', $dataArr)) {
											//$this->setSuccess('GSTR2 Saved Successfully');
											$this->logMsg("User ID Notification added : " . $_SESSION['user_detail']['user_id'],"notification");
											
											return true;
										} else {
											$this->setError('Failed to save notification data');
											return false;
										}
									}
									else
									{
											// $dataConditionArray['notification_id'] = $dataItem->notification_id;
											  $dataConditionArray['user_id'] = $_SESSION["user_detail"]["user_id"];
											 $dataArr['status_updated_date'] = date('Y-m-d H:i:s');
											 $dataArr['updated_by'] = $_SESSION["user_detail"]["user_id"];
											 $dataArr['status'] = 1;
											
											if ($this->update(TAB_PREFIX.'user_notification', $dataArr, $dataConditionArray)) {

												//$this->setSuccess("Your Notification information updated successfully");
												$this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . " update the notification info","notification");
												//return true;
											} else {

												$this->setError($this->validationMessage['failed']);
												return false;
											}
									}
			                 
								
						  }
						  }
						 
						
					  }
				 } 
				 */
				                   
	}
	 public function updateNotificationDetail($notification_id) {
        $sql = "select * from " . TAB_PREFIX . "user_notification where notification_id=" . $notification_id . " and user_id='" . $_SESSION["user_detail"]["user_id"] . "'";

        $clientdata = $this->get_results($sql);

        if (empty($clientdata)) {

            $dataArr['user_id'] = $_SESSION["user_detail"]["user_id"];
            $dataArr['notification_id'] = $notification_id;
            $dataArr['status'] = 1;

            if ($this->insert(TAB_PREFIX . 'user_notification', $dataArr)) {
                //$this->setSuccess('GSTR2 Saved Successfully');
					$this->logMsg("User ID Notification added : " . $_SESSION['user_detail']['user_id'],"notification");
											
                return true;
            } else {
                $this->setError('Failed to save notification data');
                return false;
            }
        }
		else
		{
			     $dataConditionArray['notification_id'] = $notification_id;
				  $dataConditionArray['user_id'] = $_SESSION["user_detail"]["user_id"];
				 $dataArr['status_updated_date'] = date('Y-m-d H:i:s');
				 $dataArr['updated_by'] = $_SESSION["user_detail"]["user_id"];
				 $dataArr['status'] = 1;
				
				if ($this->update(TAB_PREFIX.'user_notification', $dataArr, $dataConditionArray)) {

					//$this->setSuccess("Your Notification information updated successfully");
					$this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . " update the notification info","notification");
					//return true;
				} else {

					$this->setError($this->validationMessage['failed']);
					return false;
				}
		}
    }
     
    public function updateNotification()
    {
		$dataArr =array();
		$dataArr = $this->getnotificationData();
		
		if(!empty($_GET["id"]) && (!empty($_GET["action"])))
		{
			$sql="select count(notification_id) as numcount from ".TAB_PREFIX."notification WHERE notification_id=".$_GET["id"]."";
			$dataCurrentArr = $this->get_results($sql);
			
			if($dataCurrentArr[0]->numcount > 0)
			{
				$dataConditionArray['notification_id'] = $_GET["id"];
				 $dataArr['updated_date'] = date('Y-m-d H:i:s');
				 $dataArr['updated_by'] = $_SESSION["user_detail"]["user_id"];
				//var_dump($dataArr);
				if ($this->update(TAB_PREFIX.'notification', $dataArr, $dataConditionArray)) {

					$this->setSuccess("Your Notification information updated successfully");
					$this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . " update the notification info","notification");
					return true;
				} else {

					$this->setError($this->validationMessage['failed']);
					return false;
				}
				
  			}
			else
			{
				/*
				$sql="select count(notification_id) as numcount from ".TAB_PREFIX."notification WHERE notification_name='".$dataArr["notification_name"]."'";
				$dataCurrentArr = $this->get_results($sql);
				
				if($dataCurrentArr[0]->numcount > 0)

				{
					$this->setError('Notification Name already exists');
					   return false; 
				}	
				else
				{
					*/
				
					if ($this->insert(TAB_PREFIX.'notification', $dataArr)) {
						$this->setSuccess('Notification Saved Successfully');
							$this->logMsg("User ID Notification added : " . $_SESSION['user_detail']['user_id'],"notification");
											
						return true;
					}
					else
					{
						$this->setError('Failed to save Notification data');
					   return false;    	   
				   }
				//}

			}
		}
		else
		{
			/*
			$sql="select count(notification_id) as numcount from ".TAB_PREFIX."notification WHERE notification_name='".$dataArr["notification_name"]."'";
			$dataCurrentArr = $this->get_results($sql);
			
			if($dataCurrentArr[0]->numcount > 0)

			{
				$this->setError('Notification Name already exists');
				   return false; 
			}	
			else
			{
				*/
				 $dataArr['added_date'] = date('Y-m-d H:i:s');
				 $dataArr['added_by'] = $_SESSION["user_detail"]["user_id"];
				if ($this->insert(TAB_PREFIX.'notification', $dataArr)) {
						$this->logMsg("User ID Notification added : " . $_SESSION['user_detail']['user_id'],"notification");
											
					$this->setSuccess('Notification Saved Successfully');
					return true;
				}
				else
				{
					$this->setError('Failed to save Notification data');
				   return false;    	   
			   }
			//}
		}

   }
    
}