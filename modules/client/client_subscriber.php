<?php

if( !isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '' ) {
    $db_obj->redirect(PROJECT_URL);
    exit();
}

//if(!$db_obj->can_read('client_subscriber')) {
//
//    $db_obj->setError($db_obj->getValMsg('can_read'));
//    $db_obj->redirect(PROJECT_URL."/?page=dashboard");
//    exit();
//}

//if( isset($_GET['action']) && $_GET['action'] == 'deletePlan' && isset($_GET['id'])) {
//    
//	if(!$db_obj->can_delete('client_subscriber')) {
//
//		$db_obj->setError($db_obj->getValMsg('can_delete'));
//		$db_obj->redirect(PROJECT_URL."/?page=client_subscriber");
//		exit();
//	}
//	
//    $planid = $_GET['id'];
//    $planDetail = $db_obj->getPlanDetails($planid);
//
//       
//    if( $planDetail['status'] == "success" ) {
//        
//        if($db_obj->deletePlan($planDetail['data']->id)){
//            $db_obj->redirect(PROJECT_URL."?page=client_subscriber");
//        }
//        
//    } else {
//        $db_obj->setError($db_obj->validationMessage['noplanexist']);
//        $db_obj->redirect(PROJECT_URL."?page=client_subscriber");
//    }
//}
//?>

<!--========================sidemenu over=========================-->
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

       <h1>Subscribe List</h1>
        <div class="whitebg formboxcontainer">
<!--        <div>
            <a class='btn btn-default btn-success btnwidth addnew' href='<?php echo PROJECT_URL;?>/?page=plan_addplan'>Add New</a>
        </div>-->
         <div class="clear height10"></div>
       
          <?php $db_obj->showErrorMessage(); ?>
            <?php $db_obj->showSuccessMessge(); ?>
            <?php $db_obj->unsetMessage(); ?>
        <h2 class="greyheading">Subscribe Listing</h2>
        
        <div class="adminformbx">
          
        
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable">
                
                <thead>
                    <tr>
                        <th align='left'>#</th>
                        
                        <th align='left'>Name</th>
<!--                        <th align='left'>Last Name</th>-->
                        <th align='left'>User Name</th>
                        <th align='left'>Email</th>
                        <th align='left'>Phone No.</th>
                        <th align='left'>Plan Name</th>
                        <th align='left'>Plan Status</th>
                        <th align='left'>Action</th>
                        
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
                        {"bSortable": false},
                        {"bSortable": false},
                        {"bSortable": false},
                        {"bSortable": false},
                        {"bSortable": false}
//                        {"bSortable": false}
                       
                        
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
                    "sAjaxSource": "<?php echo PROJECT_URL; ?>/?ajax=client_subscriber",
                    "fnServerParams": function (aoData) {
                    },
                    "iDisplayLength": 50
                });
            }
        };
    }();
</script>
