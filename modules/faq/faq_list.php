<?php
$obj_faq = new faq();

if( !isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '' ) {
    $obj_faq->redirect(PROJECT_URL);
    exit();
}

/*if(!$obj_faq->can_read('faq_list')) {

    $obj_faq->setError($obj_faq->getValMsg('can_read'));
    $obj_faq->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}

if(isset($_GET['id']) && $_GET['id']!='') {
    
	if(!$obj_faq->can_delete('faq_list')) {

		$obj_faq->setError($obj_faq->getValMsg('can_delete'));
		$obj_faq->redirect(PROJECT_URL."/?page=faq_list");
		exit();
	}
	
    $planid = $_GET['id'];
    $planDetail = $obj_faq->getPlanDetails($planid);

       
    if( $planDetail['status'] == "success" ) {
        
        if($obj_faq->deletePlan($planDetail['data']->id)){
            $obj_faq->redirect(PROJECT_URL."?page=faq_list");
        }
        
    } else {
        $obj_faq->setError($obj_faq->validationMessage['noplanexist']);
        $obj_faq->redirect(PROJECT_URL."?page=faq_list");
    }
}*/
/* get current user data */

?>

<!--========================sidemenu over=========================-->
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

       <h1>FAQ List</h1>
        <div class="whitebg formboxcontainer">
        <div>
            <a class='btn btn-default btn-success btnwidth addnew' href='<?php echo PROJECT_URL;?>/?page=faq_update'>Add New</a>
        </div>
         <div class="clear height10"></div>
       
		<?php $obj_faq->showErrorMessage(); ?>
        <?php $obj_faq->showSuccessMessge(); ?>
        <?php $obj_faq->unsetMessage(); ?>
        <h2 class="greyheading">FAQ Listing</h2>
        
        <div class="adminformbx">
          
        
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable">
                
                <thead>
                    <tr>
                        <th align='left'>#</th>
                    
                        <th align='left'>Question</th>
                        <th align='left' width="200px">Answer</th>
                        <th align='left'>Status</th>
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
                    "sAjaxSource": "<?php echo PROJECT_URL; ?>/?ajax=faq_list",
                    "fnServerParams": function (aoData) {
                    },
                    "iDisplayLength": 50
                });
            }
        };
    }();
</script>