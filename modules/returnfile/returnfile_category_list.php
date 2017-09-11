<?php
$obj_plan = new plan();
if( !isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '' ) {
    $obj_plan->redirect(PROJECT_URL);
    exit();
}

if(!$obj_plan->can_read('returnfile_list')) {

    $obj_plan->setError($obj_plan->getValMsg('can_read'));
    $obj_plan->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}


/* get current user data */

?>

<!--========================sidemenu over=========================-->
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

       <h1>ReturnCategory List</h1>
        <div class="whitebg formboxcontainer">
        <div>
            <a class='btn btn-default btn-success btnwidth addnew' href='<?php echo PROJECT_URL;?>/?page=returnfile_category_update'>Add New</a>
        </div>
         <div class="clear height10"></div>
       
          <?php $obj_plan->showErrorMessage(); ?>
            <?php $obj_plan->showSuccessMessge(); ?>
            <?php $obj_plan->unsetMessage(); ?>
        <h2 class="greyheading">ReturnCategory Listing</h2>
        <div class="adminformbx">     
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable">
            
                <thead>
                    <tr>
                        <th align='left'>#</th>                                        
                        <th align='left'>Category</th>
						<th align='left'>Vendorto File</th>
						<th align='left'>ReturnType</th>									
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
                    "sAjaxSource": "<?php echo PROJECT_URL; ?>/?ajax=returnfile_category_list",
                    "fnServerParams": function (aoData) {
                    },
                    "iDisplayLength": 50
                });
            }
        };
    }();
</script>