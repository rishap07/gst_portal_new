<?php
$obj_plan = new plan();
if( !isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '' ) {
    $obj_plan->redirect(PROJECT_URL);
    exit();
}
$obj_notification->updateuserNotificationDetail();
             /*
 
				$message="";
				$count=1;
				$flag=0;
		        $sql="select * from " . $db_obj->getTableName('notification') . " as n INNER join " . $db_obj->getTableName('user_notification') . " as u on u.notification_id=n.notification_id  where n.status='1' and  u.user_id='".$_SESSION["user_detail"]["user_id"]."' order by u.notification_id desc";
				$dataNotification = $db_obj->get_results($sql);
                if(!empty($dataNotification))
				{
					
						foreach($dataNotification as $dataItem)
					   {

						  ?> 
						  <tr>
						  <?php
						  if((date('Y-m-d H:i:s')>=$dataItem->start_date) && (date('Y-m-d H:i:s') <= $dataItem->end_date))
						  {
							  $flag=1;
						  if($dataItem->vendor_list==0)
						  {
							   if($obj_notification->updateNotificationDetail($dataItem->notification_id))
		                    	{
			                   }
							 
						  
					       
						  }
						 if(isset($dataArr['data']->kyc->vendor_type) && $dataArr['data']->kyc->vendor_type==$dataItem->vendor_list)
							  { 
							    if($obj_notification->updateNotificationDetail($dataItem->notification_id))
		                    	{
			                   }
							  
								
						  }
						  }
						 
						
					  }
				if($flag==1){
			
				
				
				 } }  */?>


<!--========================sidemenu over=========================-->
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

       <h1>Notification List</h1>
        <div class="whitebg formboxcontainer">
      
         <div class="clear height10"></div>
       
          <?php $obj_plan->showErrorMessage(); ?>
            <?php $obj_plan->showSuccessMessge(); ?>
            <?php $obj_plan->unsetMessage(); ?>
        <h2 class="greyheading">Notification Listing</h2>
        
        <div class="adminformbx">
          
        
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable">
                
                <thead>
                    <tr>
                        <th align='left'>#</th>
                    
                        <th align='left' width="200px">Title</th>
                        <th align='left'>Message</th>
						<th align='left'>PostedDate</th>
                        
                    </tr>
                </thead>

            </table>
        </div>
<!--========================adminformbox over=========================-->    
    </div>
<!--========================admincontainer over=========================-->
    </div></div>
<div class="clear height80">
</div>
<script>
    $(document).ready(function () {
        TableManaged.init();
    });
    
    var TableManaged = function () {
        return {
            init: function () {
                if (!jQuery().dataTable) {
                    return;
                }
                var sgHREF = window.location.pathname;
                $.ajaxSetup({'type': 'POST', 'url': sgHREF, 'dataType': 'json'});
                $.extend($.fn.dataTable.defaults, {'sServerMethod': 'POST'});
                $('#mainTable').dataTable({
                    "aoColumns": [
                        {"bSortable": false},
                        {"bSortable": false},
						{"bSortable": false},
						{"bSortable": false}
                     
                    ],
                    "sDom": "lfrtip",
                    "aLengthMenu": [
                        [10, 20, 50, 100, 500],
                        [10, 20, 50, 100, 500],
                    ],
                    "bProcessing": true,
                    "bServerSide": true,
                    "bStateSave": false,
                    "bDestroy": true,
                    "sAjaxSource": "<?php echo PROJECT_URL; ?>/?ajax=notification",
                    "fnServerParams": function (aoData) {
                    },
                    "iDisplayLength": 50
                });
            }
        };
    }();
</script>