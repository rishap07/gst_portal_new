<?php
$obj_faq = new faq();
if( !isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '' ) {
    $obj_faq->redirect(PROJECT_URL);
    exit();
}
$faqData=$obj_faq->getFaq();

?>

<!--========================sidemenu over=========================-->

<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <h1>FAQ List</h1>
    <div class="whitebg formboxcontainer">
      <div class="clear height10"></div>
      <?php $obj_faq->showErrorMessage(); ?>
      <?php $obj_faq->showSuccessMessge(); ?>
      <?php $obj_faq->unsetMessage(); ?>
      <h2 class="greyheading">FAQ Listing</h2>
      <div class="adminformbx">
        <div class="clear height10"> </div>
        <div class="panel-group" id="faqAccordion">
          <?php if(count($faqData)>0){
			foreach($faqData as $faqContent):?>
          <div class="panel panel-default ">
            <div class="panel-heading accordion-toggle question-toggle collapsed" data-toggle="collapse" data-parent="#faqAccordion" data-target="#<?php echo $faqContent->id; ?>">
              <h4 class="panel-title"> <a href="javascript:void(0)" class="ing">Q: <?php echo $faqContent->question; ?></a> </h4>
            </div>
            <div id="<?php echo $faqContent->id; ?>" class="panel-collapse collapse" style="height: 0px;">
              <div class="panel-body">
                <h5><span class="label label-primary">Answer</span></h5>
                <?php echo html_entity_decode($faqContent->answer); ?> </div>
            </div>
          </div>
          <?php 
			endforeach;
			}?>
        </div>
      </div>
      <!--========================adminformbox over=========================--> 
    </div>
    <!--========================admincontainer over=========================--> 
  </div>
</div>
<div class="clear height80"> </div>

