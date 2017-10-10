<?php
$obj_user = new users();

if( !isset($_SESSION['user_detail']['user_group']) || $_SESSION['user_detail']['user_group'] != '1' ) {
    $obj_user->redirect(PROJECT_URL);
    exit();
}

if( isset($_GET['action']) && $_GET['action'] == 'deleteRole' && isset($_GET['id']) && $obj_user->validateId($_GET['id'])) {

	$dataConditionArray['user_role_id'] = $obj_user->sanitize($_GET['id']);
	$dataUpdateArray['is_deleted'] = "1";
	$dataUpdateArray['deleted_by'] = $_SESSION['user_detail']['user_id'];
	$dataUpdateArray['deleted_date'] = date('Y-m-d H:i:s');

	if ($obj_user->update($obj_user->getTableName('user_role'), $dataUpdateArray, $dataConditionArray)) {

		$obj_user->setSuccess("Role Deleted Successfully.");
		$obj_user->logMsg("Role ID : " . $_GET['id'] . " in User Role has been deleted","user_role_delete");
		$obj_user->redirect(PROJECT_URL."?page=user_role");
	} else {

		$obj_user->setError($obj_user->validationMessage['failed']);
		$obj_user->redirect(PROJECT_URL."?page=user_role");
	}	
}
?>

<!--========================sidemenu over=========================-->
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

            <h1>User Role</h1>
        <div class="whitebg formboxcontainer">
        <div>
            <a class='btn btn-default btn-success btnwidth addnew' href='<?php echo PROJECT_URL;?>/?page=user_role_update'>Add New Role</a>
        </div>
  
       <div class="clear height10"></div>
            <?php $obj_user->showErrorMessage(); ?>
            <?php $obj_user->showSuccessMessge(); ?>
            <?php $obj_user->unsetMessage(); ?>
        <h2 class="greyheading">Role Listing</h2>
        
        <div class="adminformbx">
        
        
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable">
                
                <thead>
                    <tr>
                        <th align='left'>#</th>
                        <th align='left'>Role Name</th>
                        <th align='left'>Role Description</th>
                        <th align='left'>Role Page</th>
                        <th align='left'>Status</th>
                        <th align='left'>Action</th>
                    </tr>
                </thead>

            </table>
        </div>
<!--========================adminformbox over=========================-->    
    </div>
<!--========================admincontainer over=========================-->
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
                    "sAjaxSource": "<?php echo PROJECT_URL; ?>/?ajax=user_role",
                    "fnServerParams": function (aoData) {
                    },
                    "iDisplayLength": 50
                });
            }
        };
    }();
</script>