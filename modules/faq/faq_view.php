<?php
$db_faq = new faq();
extract($_POST);

	if((isset($_GET["id"])) && (!empty($_GET["id"])))
	{
		$dataArr = array();
		$dataArr = $db_faq->getUserDetailsById($db_faq->sanitize($_SESSION['user_detail']['user_id']));
		
		$count=1;
		$flag=0;
		$sql="select * 
		from " . $db_obj->getTableName('faq')." where 
		status='1' and 
		is_deleted='0' and 
		id='".$_GET["id"]."' 
		order by id desc";
		
		$dataFaq= $db_faq->get_row($sql);
		if(!empty($dataFaq))
		{
		} 
				
	}else
	{
		$db_faq->setError('Invalid Attempt');
		
		$db_faq->redirect(PROJECT_URL . "/?page=faq_list");
		return true;
	}
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="col-md-12 col-sm-12 col-xs-12 heading">
      <h1>FAQ Details</h1>
    </div>
    <div class="whitebg formboxcontainer">
      <div class="clear"></div>
      <?php 
        if(!empty($dataFaq) && $dataFaq->status!='0')
        {
        ?>
      <table style="width:100% " class="invoice-itemtable dataTable no-footer">
        <tr>
          <td style="font-size:12px;"  align="left"><h3><?php echo ucfirst($dataFaq->question) ?></h3></td>
          <td style="font-size:12px;"  align="right"><h5><?php echo $dataFaq->added_date ?></h5></td>
        </tr>
        <tr>
          <td style="font-size:14px;" colspan="2" align="left">
          <div><p><?php echo html_entity_decode($dataFaq->answer);?></p></div>
          </td>
        </tr>
      </table>
               <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=faq_list"; ?>';" class="btn btn-danger"/>
    
      <?php }
        else
        {
			
            echo'<h2> No Record Found </h2>';
        } 
        ?>
    </div>
  </div>
</div>