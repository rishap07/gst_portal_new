<?php
$obj_user = new users();
if( !isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '' ) {
    $obj_user->redirect(PROJECT_URL);
    exit();
}
?>

<!--========================sidemenu over=========================-->
<div class="admincontainer greybg">
    <div class="formcontainer">
<!--        <div>
            <a class='addnew' href='<?php echo PROJECT_URL;?>/?page=user_role_update'>Add New Role</a>
        </div>-->
        <h1>User Group</h1>
        <hr class="headingborder">
        <h2 class="greyheading">Group Listing</h2>
        
        <div class="adminformbx">
            <?php $obj_user->showErrorMessage(); ?>
            <?php $obj_user->showSuccessMessge(); ?>
            <?php $obj_user->unsetMessage(); ?>
        
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tablecontent" id="mainTable">
                
                <thead>
                    <tr>
                        <th align='left'>#</th>
                        <th align='left'>Group Name</th>
                        <th align='left'>Group Description</th>
                        <th align='left'>Action</th>
                    </tr>
                </thead>

            </table>
        </div>
<!--========================adminformbox over=========================-->    
    </div>
<!--========================admincontainer over=========================-->
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
                    "sAjaxSource": "<?php echo PROJECT_URL; ?>/?ajax=user_group",
                    "fnServerParams": function (aoData) {
                    },
                    "iDisplayLength": 50
                });
            }
        };
    }();
</script>