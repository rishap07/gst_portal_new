<style>
table {
    border-collapse: collapse;
}

table, td, th {
    /* border: 1px solid black; */
    padding: 10px;
}
</style>
<?php

$db_obj = new validation();
extract($_POST);



$query ="select * from " . $db_obj->getTableName('notification') . " as n where notification_id='".$_GET["id"]."' and status='1'";
$rResult = $db_obj->get_results($query);
      ?>
  
  
  <div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>View Details</h1></div>
        <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=dashboard"; ?>';" class="btn btn-danger" style="float:right" />
        <div class="whitebg formboxcontainer">
       
    
     
        <div class="clear"></div>
        <?php 
        if(!empty($rResult))
        {
        ?>
        <table style="width:80% ">


    <tr>
        <td>Title</td>
        <td><?php echo $rResult[0]->notification_name ?></td>
    </tr>
     <tr>
        <td>Message</td>
        <td><?php echo $rResult[0]->notification_message ?></td>
    </tr> 
   
</table>

        <?php }
        else
        {
            '<h2> NO Record Found </h2>';
        } 
        ?>
                </div>
                   
                </div>
</div> 

