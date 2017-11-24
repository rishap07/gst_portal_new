<?php
$obj_subscriber = new subscriber();
/*if (!isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '') {
    $obj_subscriber->redirect(PROJECT_URL);
    exit();
}
if (!$obj_subscriber->can_read('subscriber_subuser_list')) {

    $obj_subscriber->setError($obj_subscriber->getValMsg('can_read'));
    $obj_subscriber->redirect(PROJECT_URL . "/?page=dashboard");
    exit();
}
      $sql="select * from ".TAB_PREFIX."user WHERE (user_group='3' or user_group='4' or user_group='5') and user_id='".$obj_subscriber->sanitize($_GET["id"])."'";
	  $dataCurrentArr = $obj_subscriber->get_results($sql);	
	
      if($dataCurrentArr[0]->added_by==$_SESSION["user_detail"]["user_id"])
	  {		  
	     
	  }else{
		$obj_subscriber->setError('You are not authorize to view this user profile.');
        $obj_subscriber->redirect(PROJECT_URL . "?page=dashboard");
	  }
if (isset($_GET['action']) && $_GET['action'] == 'deleteClient' && isset($_GET['id']) && $obj_subscriber->validateId($_GET['id'])) {
    if (!$obj_subscriber->can_delete('subscriber_subuser_list')) {

        $obj_subscriber->setError($obj_subscriber->getValMsg('can_delete'));
        $obj_subscriber->redirect(PROJECT_URL . "/?page=subscriber_subuser_list");
        exit();
    }
    $userDetail = $obj_subscriber->getUserDetailsById($obj_subscriber->sanitize($_GET['id']));
    if ($userDetail['status'] == "success") {
        if ($obj_subscriber->deleteClientUser($userDetail['data']->user_id)) {
            $obj_subscriber->redirect(PROJECT_URL . "?page=subscriber_subuser_list");
        }
    } else {
        $obj_subscriber->setError($obj_plan->validationMessage['usernotexist']);
        $obj_subscriber->redirect(PROJECT_URL . "?page=subscriber_subuser_list");
    }
}*/


$clientSummary=$obj_subscriber->subscriberSummary();
//$obj_subscriber->pr($clientSummary);
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <h1>Subscriber Client Summarrry</h1>
        <div class="whitebg formboxcontainer">
            
            <div class="clear height10"></div>
            <?php $obj_subscriber->showErrorMessage(); ?>
            <?php $obj_subscriber->showSuccessMessge(); ?>
            <?php $obj_subscriber->unsetMessage(); ?>
            <h2 class="greyheading">Subscriber Client Summarrry</h2>
            <div class="adminformbx">
                <div class="clear"></div>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable">
                    <thead>
                        <tr>
                            <th align='left'>#</th>
                            <th align='left'>User Id</th>
                            <th align='left'>Username</th>
                            <th align='left'>Name</th>
                            <th align='left'>Plan</th>
							<th align='left'>Plan Start Date</th>
                            <th align='left'>Plan End Date</th>
                            <th align='left'>No of Clients</th>
                            <th align='left'>Clients</th>
                            <th align='left'>Total Sales(invoices)</th>
                            <th align='left'>Total Purchase(invoices)</th>
                           
                        </tr>
                    </thead>
                </table>
            </div> 
        </div>
    </div>
</div>
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
                        {"bSortable": false},                                           
                        {"bSortable": false},
                        {"bSortable": false},
						{"bSortable": false},
                        {"bSortable": false},
                        {"bSortable": false},
                        {"bSortable": false},                                           
                        {"bSortable": false}
                    ],
                    "sDom": "lfrtip",
                    "aLengthMenu": [
                        [5,10, 20, 50, 100, 500],
                        [5,10, 20, 50, 100, 500],
                    ],
                    "bProcessing": true,
                    "bServerSide": true,
                    "bStateSave": false,
                    "bDestroy": true,
                    "sAjaxSource": "<?php echo PROJECT_URL; ?>/?ajax=subscriber_client_summarrry",
                    "fnServerParams": function (aoData) {
                    },
                    "iDisplayLength": 20
                });
            }
        };
    }();
</script>