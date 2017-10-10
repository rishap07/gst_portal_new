<?php
$obj_client = new json();

 $page=$_REQUEST['page'];
 $returnmonth=$_REQUEST['returnmonth'];
 $type=$_REQUEST['type'];
 
if(!$obj_client->can_read('returnfile_list'))
{
    $obj_client->setError($obj_client->getValMsg('can_read'));
    $obj_client->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}

if (!isset($_REQUEST['returnmonth']) || $_REQUEST['returnmonth'] == '') {
    $obj_client->redirect(PROJECT_URL . "/?page=return_client");
    exit();
}
$returnmonth = '2017-07';
if ($_REQUEST['returnmonth'] != '') {
    $returnmonth = $_REQUEST['returnmonth'];
}

//$obj_gstr1->pr($_POST);\


if (isset($_POST['returnmonth'])) 
{
    $returnmonth = $_POST['returnmonth'];	 
    $obj_client->redirect(PROJECT_URL . "/?page=return_purchase_invoice&returnmonth=" . $returnmonth."&type=".$_REQUEST['type']);
     exit();
}
?>
<link type="text/css" rel="stylesheet" href="<?php echo THEME_URL; ?>/css/bootstrap-multiselect.css"/>
<script type="text/javascript" src="<?php echo THEME_URL; ?>/js/bootstrap-multiselect.js"></script>

<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="col-md-6 col-sm-6 col-xs-12 heading">
      <h1>GSTR-2 View Invoices</h1>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12 text-right breadcrumb-nav"><a href="#">Home</a> <i class="fa fa-angle-right" aria-hidden="true"></i> <a href="#">File Return</a> <i class="fa fa-angle-right" aria-hidden="true"></i> <span class="active">GSTR-2 View Invoices</span> </div>
    <div class="whitebg formboxcontainer">
      <div class="pull-right rgtdatetxt">
        <form method='post' name='form2'>
          Month Of Return
          <?php  $dataQuery = "SELECT DATE_FORMAT(invoice_date,'%Y-%m') AS niceDate FROM gst_client_invoice group by nicedate";
		         $dataRes = $obj_client->get_results($dataQuery);
                 if (!empty($dataRes)) {
           ?>
          <select class="dateselectbox" id="returnmonth" name="returnmonth">
            <?php
			foreach ($dataRes as $dataRe) {
			?>
		<option value="<?php echo $dataRe->niceDate; ?>" <?php if ($dataRe->niceDate == $returnmonth) { echo 'selected'; } ?>><?php echo $dataRe->niceDate; ?></option>
            <?php
			}
			?>
          </select>
          <?php
	} else {
		?>
          <select class="dateselectbox" id="returnmonth" name="returnmonth">
            <option>July 2017</option>
          </select>
          <?php }
                                    ?>
        </form>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-12 heading">
        <div class="tab col-md-12 col-sm-12 col-xs-12">
          <?php
                        include(PROJECT_ROOT."/modules/return/include/tab.php");
               ?>
        </div>
      </div>
      <br>
      <?php $obj_client->showErrorMessage(); ?>
      <?php $obj_client->showSuccessMessge(); ?>
      <?php $obj_client->unsetMessage(); ?>
      <div class="clear"></div>
      <div class="text-right"> <a href="<?php echo PROJECT_URL . '/?page=purchase_invoice_create' ?>" class="btngreen"><i class="fa fa-cloud-download" aria-hidden="true"></i>Add New Invoice</a> </div>
      <br>
      
      <div class="tableresponsive">
        <table  class="table  tablecontent tablecontent2" id="mainTable" style="font-size:14px">
          <thead>
            <tr>
              <th>Date</th>
              <th>Reference</th>
              <th>Vendor</th>
              <th class="text-right">GSTIN</th>
              <th class="text-right">TaxableAmt.</th>
              <th class="text-right">CGST</th>
              <th class="text-right">SGST</th>
              <th class="text-right">IGST</th>
              <th class="text-right">CESS</th>
              <th class="text-right">TotalAmount</th>
              <th class="text-right">Status</th>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="clear height40"></div>
</div>
<div class="clear"></div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#multiple-checkboxes').multiselect();
    });
</script> 

<script>
    $(document).ready(function () {
        $('#returnmonth,.type').on('change', function () {
            document.form2.action = '<?=PROJECT_URL; ?>/?page=return_purchase_invoice&returnmonth=<?=$returnmonth; ?>&type=<?=$_REQUEST['type'];?>';
            document.form2.submit();
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        TableManaged.init('<?=$_REQUEST['returnmonth'];?>','<?=$_REQUEST['type'];?>');
    });

    var TableManaged = function () {
        return {
            init: function (retmonth,type) {
                if (!jQuery().dataTable) {
                    return;
                }
                var sgHREF = window.location.pathname;
                $.ajaxSetup({'type': 'POST', 'url': sgHREF,data:{'returnmonth':retmonth,'type':type}, 'dataType': 'json'});
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
                        [10, 20, 50, 100, 500],
                        [10, 20, 50, 100, 500],
                    ],
                    "bProcessing": true,
                    "bServerSide": true,
                    "bStateSave": false,
                    "bDestroy": true,
                    "sAjaxSource": "<?php echo PROJECT_URL; ?>/?ajax=return_purchase_invoice",
                    "fnServerParams": function (aoData) {
                    },
                    "iDisplayLength": 50
                });
            }
        };
    }();
</script>