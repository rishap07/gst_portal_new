<?php
$db_obj = new help();
extract($_POST);

if((isset($_GET["id"])) && (!empty($_GET["id"])))
{
        $dataArr = array();
        $dataArr = $db_obj->getUserDetailsById($db_obj->sanitize($_SESSION['user_detail']['user_id']));
		
	    $count=1;
		$flag=0;
		$sql="select * 
		from " . $db_obj->getTableName('help')." where 
		status='1' and 
		is_deleted='0' and 
		id='".$_GET["id"]."' 
		order by id desc";
		
		$dataHelp= $db_obj->get_row($sql);
		//$db_obj->pr($dataHelp);
        if(!empty($dataHelp))
		{
		 } 
			
}else
{
	$db_obj->setError('Invalid Attempt');
	
	$db_obj->redirect(PROJECT_URL . "/?page=help_list");
	return true;
}
      ?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="col-md-12 col-sm-12 col-xs-12 heading">
      <h1>Help Details</h1>
    </div>
    <div class="whitebg formboxcontainer">
      <div class="clear"></div>
      <?php 
        if(!empty($dataHelp) && $dataHelp->status!='0')
        {
        ?>
      <table style="width:100% " class="invoice-itemtable dataTable no-footer">
        <tr>
          <td style="font-size:12px;"  align="left"><h3><?php echo ucfirst($dataHelp->help_name) ?></h3></td>
          <td style="font-size:12px;"  align="right"><h5><?php echo $dataHelp->added_date ?></h5></td>
        </tr>
        <tr>
          <td style="font-size:14px;" colspan="2" align="left">
          <div><p><?php echo html_entity_decode($dataHelp->help_message);?></p></div>
         <div><p> <a class="height10" href="<?php echo PROJECT_URL.'/upload/help-images/'.$dataHelp->help_document ?>"  download>Click Here to Download File</a></p> </div>
          </td>
        </tr>
      </table>
             <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=help"; ?>';" class="btn btn-danger"/>
                     
      <?php }
        else
        {
            '<h2> NO Record Found </h2>';
        } 
        ?>
    </div>
  </div>
</div>