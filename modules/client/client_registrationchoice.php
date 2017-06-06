<?php
$obj_client = new client();
if( !isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '' ) {
    $obj_client->redirect(PROJECT_URL);
    exit();
}

$dataArr = array();
$dataArr = $obj_client->getUserDetailsById( $obj_client->sanitize($_SESSION['user_detail']['user_id']) );
?>


<!--POPUP START HERE-->
  <div style="display: none; position: fixed;" id="popup" class="popupcontainer topanimation">
    <div class="popupform">
      <div class="adminformbx" style="padding-left:0px;">
        <form>
          <div class="formcol" id="formcol">
            <label>VAT<span class="starred">*</span></label>
            <input type="text" placeholder="Legal Name of the Business" />
          </div>
          <div class="formcol">
            <label>Password<span class="starred">*</span></label>
            <input type="password" placeholder="**********" />
            <div class="clear height30"> </div>
            <div class="tc"> <a href="step3-form-migration.php" class="btn orangebg">SUBMIT</a></div>
          </div>
        </form>
        <p style="text-align:center;"> <a class="closebtn" id="btnclose" ><img src="image/icon-close.png" alt="#"></a> </p>
      </div>
    </div>
  </div>
  <div style="display:none;" id="fade" class="black_overlay"></div>
  <!--POPUP END HERE--> 
  
  <!--========================sidemenu over=========================--> 
  <!--========================admincontainer start=========================-->
<div class="admincontainer greybg">
    <div class="formcontainer">

        <?php $obj_client->showErrorMessage(); ?>
        <?php $obj_client->showSuccessMessge(); ?>
        <?php $obj_client->unsetMessage(); ?>

        <h1>Registration</h1>
        <hr class="headingborder">
        <form name="choice-registration">
          <div class="adminformbx">
            
              <div class="kycmainbox">
              <div class="clear height100"></div>
              <div class="tc">
                <div style="width:90%; margin:0 auto;">
                    <div class="migratecol">
                        <a href="javascript:void(0)" id="btn"><input type="radio" />If you want to migrate </a>
                    </div>
                  
                    <div class="orcircle orangebg">OR</div>
                    <div class="migratecol" style="margin-right:0px;">
                        <a href="javascript:void(0)"><input type="radio" />Register</a>
                    </div>
					
                    <div class="orcircle orangebg">OR</div>
                    <div class="migratecol" style="margin-right:0px;">
                        <a href="<?php echo PROJECT_URL."?page=client_gstin"; ?>">Enter GSTN</a>
                    </div>
                  
                </div>
              </div>
              <div class="clear height40"></div>
              <div id="show" style="display:none;">
              <div class="tc">
                <div style="width:70%; margin:0 auto;">

                  <div class="clear height20"></div>
                  <div class="borderorange"><a href="javascript:void(0)" class="orangeborder popupbtn vat"> <input type="radio" /> VAT </a></div>

                  <div class="borderorange" style="margin-right:0px;"><a href="javascript:void(0)"  class="orangeborder popupbtn sat">
                    <input type="radio" />SALES TAX</a></div>
                </div>
              </div>
            </div>
            </div>

          </div>

        </form>

    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	$(".popupbtn").click(function() {
		$("#popup").css({"display":"block"});
		$("#fade").css({"display":"block"});
		
		if($(this).hasClass('vat'))
		 {
			$("#formcol > label").html('VAT<span class="starred">*</span>') ;
			 }
		else
		 {
			
			$("#formcol > label").html('SAT<span class="starred">*</span>') ;
			}
        
    });
	
	
	$('#btnclose').click(function(){
		$("#popup").hide();
		$("#fade").hide();
	});
	
	$("#btn").click(function() {
		$("#show").slideToggle(500);
        
    });

});

</script>