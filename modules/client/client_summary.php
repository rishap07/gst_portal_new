<?php
$obj_client = new client();
?>
  
<style type="text/css">


div.tab {
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
div.tab button {
    background-color: inherit;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 16px;
    transition: 0.3s;
    font-size: 17px;
}

/* Change background color of buttons on hover */
div.tab button:hover {
    background-color: #ddd;
}

/* Create an active/current tablink class */
div.tab button.active {
    background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;
}
</style>



<div class="tab">
<a href="<?php echo PROJECT_URL.'/?page=client_summary'?>">
  <button class="tablinks">View GSTR1 Summary</button></a>
<a href="<?php echo PROJECT_URL.'/?page=client_view_invoices'?>">
  
  <button class="tablinks">View My Invoice</button></a>
 <a href="<?php echo PROJECT_URL.'/?page=client_upload_invoices'?>">
 
  <button class="tablinks">Upload To GSTN</button>
</div>
<div  class="tabcontent">
  <h3>GSTR-1 Summary</h3>
  
  <p><div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

       
        <div class="whitebg formboxcontainer">
      
         <?php $obj_client->showErrorMessage(); ?>
            <?php $obj_client->showSuccessMessge(); ?>
            <?php $obj_client->unsetMessage(); ?>
       
        <div class="adminformbx">
          
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">
                <thead>
                    <tr>
                        <th align='left'>Type Of Invoice</th>
                        <th align='left'>No. Invoices</th>
                        <th align='left'>Taxable Amount</th>
                        <th align='left'>Tax Amt</th>
                        <th align='left'>TotalAmount</th>
                       
                    </tr>
					 
					<tr>
					  <td>B2B</th>
                        <td>1</th>
                        <td>201.05</th>
                        <td>14.05</th>
                        <td>201.05</th>
					</tr>
					<tr>
					  <td>Advance Receipt</th>
                        <td>2</th>
                        <td>15000</th>
                        <td>5.05</th>
                        <td>15000</th>
					</tr>
                </thead>
            </table>
        </div>  
    </div>
</div>
</div>
<div class="clear height80">
</div></p>
</div>



<script>
function openCity(evt, cityName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
       // tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}
</script>        

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
                    "sAjaxSource": "<?php echo PROJECT_URL; ?>/?ajax=client_return",
                    "fnServerParams": function (aoData) {
                    },
                    "iDisplayLength": 50
                });
            }
        };
    }();
</script>