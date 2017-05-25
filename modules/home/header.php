<!DOCTYPE html>
<html lang="en">
<head>
<?php
//if($_SERVER['REQUEST_URI']=='/home-opt')
//{
	?>
	<meta name="robots" content="NOINDEX , NOFOLLOW">
<?php
//}

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<?php //include("inc/meta.php");?>
<link rel="stylesheet" href="<?=CDN_SITE_URL;?>css/style.css?i=6">
<link rel="stylesheet" href="<?=CDN_SITE_URL;?>css/slider-new.css">
<!--<link rel="stylesheet" href="<?php echo SITE_URL; ?>css/recently-add-popup-style.css">-->
<link rel="stylesheet" href="<?=CDN_SITE_URL;?>css/allsitemix.css?i=3">
<link rel="stylesheet" href="<?=CDN_SITE_URL;?>css/side-slider.css"> 
<!--[if lt IE 9]>
      <script  src="<?php echo SITE_URL; ?>js/html5shiv.min.js"></script>
      <script  src="<?php echo SITE_URL; ?>js/respond.min.js"></script>
    <![endif]-->
<!--[if IE 8]>
    <link rel="stylesheet" type="text/css" href="<?php echo SITE_URL; ?>css/IE8.css" />
   
    <![endif]-->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script  type="text/javascript" src="<?php echo SITE_URL; ?>js/jquery_1.11.js"></script>
<script>


//window.fbAsyncInit = function() {
//	FB.init({
//	appId      : '825196030899039', // replace your app id here
//	channelUrl : '//'+window.location.hostname+'/channel', 
//	status     : true, 
//	cookie     : true, 
//	xfbml      : true  
//	});
//};
///*(function(d){
//	var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
//	if (d.getElementById(id)) {return;}
//	js = d.createElement('script'); js.id = id; js.async = true;
//	js.src = "https://connect.facebook.net/en_US/all.js";
//	ref.parentNode.insertBefore(js, ref);
//}(document));*/
//
//(function(d){
//        var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
//        js = d.createElement('script'); js.id = id; js.async = true;
//        js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=825196030899039";
//        d.getElementsByTagName('head')[0].appendChild(js);
//      }(document));
//
//function FBLogin(){
//	
//	FB.login(function(response){
//		if(response.authResponse){
//			window.location.href = "<?=SITE_URL;?>actions.php?action=fblogin";
//		}
//	}, {scope: 'email,user_likes'});
//}
//
//function FBLoginCheckout(){
//	
//	FB.login(function(response){
//		if(response.authResponse){
//			window.location.href = "<?=SITE_URL;?>actions.php?action=fblogin&loginViaCheckout=1";
//		}
//	}, {scope: 'email,user_likes'});
//}
//
//function FBLogout(){
//	FB.logout(function(response) {
//		window.location.href = "<?=SITE_URL;?>logout";
//	});
//}


</script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script  src="<?php echo SITE_URL; ?>js/bootstrap.min.js"></script>
<!--Header Login JS START HERE-->
<script  type="text/javascript" src="<?php echo SITE_URL; ?>js/dropdownmenu.js"></script>
<!--
<script  type="text/javascript" src="<?php echo SITE_URL; ?>js/login-hover.js"></script>
<script  type="text/javascript" src="<?php echo SITE_URL; ?>js/login-hover2.js"></script>
<script  type="text/javascript" src="<?php echo SITE_URL; ?>js/login-hover3.js"></script>-->

<?php

$url= 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
if(isset($_REQUEST['submitmsgbox']))
{
	if(!isset($_SESSION['captchaCode']) || $_REQUEST['captcha']!=$_SESSION['captchaCode'])
	{
		echo '<script>window.alert("Invalid captcha code")</script>';
		?>
        <script type="text/javascript">
$(document).ready(function(e) {
    open_panel();
});
</script>
        <?php
	}
	else
	{
		$date= date('YmdHis');
 $insertt= "insert into contact_query (`name`,`email`, `contact`, `enquiry_type`,`comment`,`date`,`url`) values('$_REQUEST[fullname]','$_REQUEST[email]','$_REQUEST[mobile]','','$_REQUEST[requirement]','$date','$url')";

	mysql_query($insertt);
	if($insertt)
	{
		$to='Sangeeta.agarwal@cyfuture.com,shilpi@indianartideas.com';
		$subject='Enquirey form';
		$message='<table width="80%" border="1"><tbody>
		<tr><td>Name:</td><td></td>$_REQUEST[fullname]</tr>
		<tr><td>Email:</td><td></td>$_REQUEST[email]</tr>
		<tr><td>Mobile:</td><td>$_REQUEST[mobile]</td></tr>
		<tr><td>Comment:</td><td>$_REQUEST[requirement]</td></tr>
		</tbody></table>
		';
		$pagename= $_SERVER['REQUEST_URI'];
		$sql="insert into schedule_email(to_email,subject,message,pagename,datetime) values ('".$to."','".$subject."','".addslashes($message)."','".$pagename."','".date('Y-m-d H:i:s')."')";
		mysql_query($sql);
		if($sql)
		header('location: thanks-cq');
	}
}
}
?>

<script  type="text/javascript">
jquery_1_5 = jQuery.noConflict(true);
jquery_1_5(document).ready(function(){

	jquery_1_5('#mega-menu-7').dcMegaMenu({

		rowItems: '3',

		speed: 'fast',

		effect: 'slide'

	});	

});

 function fbs_click(u, t)
    {
        window.open('https://www.facebook.com/sharer.php?u=' + encodeURIComponent(u) + '&t=' + encodeURIComponent(t), 'sharer', 'toolbar=0,status=0,width=626,height=436');
        return false;
    }
    
  function fbs_click_all(u,s,i,t)
    {
        window.open('https://www.facebook.com/sharer.php?u=' + encodeURIComponent(u) + '&p[summary]='+ encodeURIComponent(s) +'&p[images][0]=' + encodeURIComponent(i) + '&t=' + encodeURIComponent(t), 'sharer', 'toolbar=0,status=0,width=626,height=436');
        return false;
    }

</script>
<div id="fb-root" style="float:left; width:1px;"></div>
<script>
window.fbAsyncInit = function() {
	FB.init({
	    appId: '825196030899039',
        cookie: true,
       	xfbml: true,
        oauth: true
   });      
};
(function() {
	var e = document.createElement('script'); e.async = true;
    e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
    document.getElementById('fb-root').appendChild(e);
}());

function FBLogin(){
	FB.login(function(response){
	 if (response.authResponse) {
		  window.location='<?=SITE_URL;?>actions.php?action=fblogin';
	 }
	},{scope: 'publish_stream,email'});
}
function FBLogout(){
	
		window.location.href = "<?=SITE_URL;?>logout";

}
</script>

<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','//connect.facebook.net/en_US/fbevents.js');

fbq('init', '1480568645585408');
fbq('track', "PageView");</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1480568645585408&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->


<!--Header Login JS END HERE-->
<!--Home Page image hover effect start here-->
<?php 
//echo print_r($testhttps);
if(isset($_REQUEST['pagename']) && $_REQUEST['pagename']=='shipping-address_confirm.php')
{
?>
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','//connect.facebook.net/en_US/fbevents.js');

fbq('init', '1480568645585408');
fbq('track', "PageView");
fbq('track', 'Lead');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1480568645585408&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->

<?php	
}
elseif(isset($_REQUEST['pagename']) && $_REQUEST['pagename']=='thanks.php')
{
?><!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','//connect.facebook.net/en_US/fbevents.js');

fbq('init', '1480568645585408');
fbq('track', "PageView");
fbq('track', 'Lead');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1480568645585408&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->
<?php    
}

if(isset($absolute_url) && !empty($absolute_url))
{ 
    if($testhttps['path']=='/artwork/5261')
    { 
?>
<!-- Facebook Conversion Code for Adds to Cart - IndianArtIdeas Artwork 5261 -->
<script>(function() {
  var _fbq = window._fbq || (window._fbq = []);
  if (!_fbq.loaded) {
    var fbds = document.createElement('script');
    fbds.async = true;
    fbds.src = '//connect.facebook.net/en_US/fbds.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(fbds, s);
    _fbq.loaded = true;
  }
})();
window._fbq = window._fbq || [];
window._fbq.push(['track', '6028446477098', {'value':'0.00','currency':'INR'}]);
</script>
<noscript>
<img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?ev=6028446477098&amp;cd[value]=0.00&amp;cd[currency]=INR&amp;noscript=1" />
</noscript>
<?php  
    }else if($testhttps['path']=='/artwork/5583')
    { ?>
<script>
<!-- Facebook Conversion Code for 5583 Checkout -->
(function() {
  var _fbq = window._fbq || (window._fbq = []);
  if (!_fbq.loaded) {
    var fbds = document.createElement('script');
    fbds.async = true;
    fbds.src = '//connect.facebook.net/en_US/fbds.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(fbds, s);
    _fbq.loaded = true;
  }
})();
window._fbq = window._fbq || [];
window._fbq.push(['track', '6033814393546', {'value':'0.01','currency':'INR'}]);
</script>
<noscript>
<img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?ev=6033814393546&amp;cd[value]=0.01&amp;cd[currency]=INR&amp;noscript=1" />
</noscript>
</script>
<?php   }
}

?>
<?php  
if(isset($_REQUEST['pname']) || isset($_REQUEST['pagename']) )
{
if( (isset($_REQUEST['pname']) && $_REQUEST['pname']!="art-search" ) || $_REQUEST['pagename']!="artworks_new.php" )
{
   if (isset($_SESSION['artistkeyword']))
        unset($_SESSION['artistkeyword']);
}
}

?>
<!--Home Page image hover effect end here-->
<!--[if IE]>
  		<script  src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
<?php

	if(isset($_SESSION['userid']) && $_SESSION['userid'] != '')
	{
		$result=mysql_query("Select * from tab_checkout where userid=" . $_SESSION["userid"]) or die(mysql_error());
		$numrecs=mysql_num_rows($result);
	}
	else
	{
		$idsession = session_id();
		if(isset($idsession))
		{
			$qry = "Select * from tab_checkout where sessionid='" . session_id()."'";
			$result=mysql_query($qry) or die(mysql_error());
			$numrecs=mysql_num_rows($result);
		}
	}
	

/*if(isset($_SESSION['cart']))
	$numrecs = count(array_filter($_SESSION['cart']));*/
	
	
if(isset($_REQUEST['itemDelete']))
{
	/*for($i=1;$i<=$numrecs;$i++)
	{
		if(isset($_REQUEST['itemDelete']))
		{
			$pid = $_REQUEST['itemDelete'];
			$j=0;
			foreach($_SESSION['cart'] as $v) { 
			if($v['itemid'] == $pid)
				{
					unset($_SESSION['cart'][$j]);
				}
				$j++;	
			}
			
		}
	}*/

	$result=mysql_query("Delete from tab_checkout where id=" . $_REQUEST['itemDelete']) or die(mysql_error());
	replaceLocation(SITE_URL);
		
}

?>
<script  type="text/javascript">
function deleteCartItem(itemid)
{
	window.location.replace("<?=SITE_URL;?>index.php?itemDelete="+itemid);
}
function startkeysearch(e,obj,stat)
	{
	if(obj.value==''){
		document.getElementById('search').focus();
		return false;
	}
	var kcode=0
	if(stat==true)
		var kcode=e.keyCode
	else
		kcode=e
	if(kcode==13)
		searchByKeyword();
		//window.location.replace("<?=SITE_URL;?>index.php?pagename=artworks.php&utype=Search&cat=Painting&col=1&val=" + obj.value + "&entire=1")
	}
	
function orderNowcart()
	{
		<?php if(isset($_SESSION['userid']) && $_SESSION['userid'] != '' && (isset($_SESSION['ref_userid']) || (isset($_SESSION['usertype']) && $_SESSION['usertype']=='Artist'))){ ?>
			alert("Please login as a collector to add this item to cart"); 
			window.location.replace("<?=SITE_URL;?>members-area");
		<?php } else if(isset($_SESSION['userid']) && $_SESSION['userid'] != '') { ?>
			window.location.replace("<?=SITE_URL;?>shipping-address");
		<?php } else { ?>
			window.location.replace("<?=SITE_URL;?>guest-checkout");
		<?php  } ?>
	}

</script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
 ga('create', 'UA-52972630-1', 'auto');ga('send', 'pageview');
</script>
<script  type="text/javascript">
            function lazyLoad(){var imgs = document.getElementsByTagName('img');for (var i = 0; i < imgs.length; i++){if (imgs[i].getAttribute("data-src"))imgs[i].src = imgs[i].getAttribute("data-src");}}var isloded = false;function lazyLoadCaller() {if (isloded == false){isloded = true;setTimeout("lazyLoad()", 100);}}</script>
<script  type="text/javascript" src="<?php echo SITE_URL; ?>js/modernizr.custom.js"></script>
<script  type="text/javascript" src="<?php echo SITE_URL; ?>js/validation.js"> </script>
<script  type="text/javascript" src="<?=SITE_URL;?>js/lightbox.js"></script>
<script  type="text/javascript" src="<?=SITE_URL;?>js/recently-add-popup-2.js"></script>
<script  type="text/javascript" src="<?=SITE_URL;?>js/recently-add-popup-js-1.js"></script>
<script  type="text/javascript" src="<?=SITE_URL;?>js/common_functions.js"></script>
<script  type="text/javascript" src="<?=SITE_URL;?>js/missing.js"></script>
<script  type="text/javascript" src="<?=SITE_URL;?>js/site_functions.js"></script>
<script  type="text/javascript" src="<?=SITE_URL;?>js/mobilemenu.js"></script>
<script  type="text/javascript" src="<?=SITE_URL;?>js/side-slider.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		$('#search_keyword').on('input', function() {
			var searchKeyword = $(this).val();
			if (searchKeyword.length >= 3) {
				$.post('search/ajax-live-search/search.php', { keywords: searchKeyword }, function(data) {
					//console.log(data.length);
					if(data) {
					$('ul#content').css('display','');
					$('ul#content').empty()
					$.each(data, function(index, value) {
						$('ul#content').append('<li><a class="fillthis" href="javascript:void(0)">' + value + '</a></li>');
					});
					}
					else { $('ul#content').css('display','none');};
				}, "json");
			}
			else
			{
				$('ul#content').css('display','none');	
			}
		});
		
		//Filling selected keyword in box
	$('body').delegate('.fillthis','click',function (){
			var fill = $(this).text();
			$('#search_keyword').val(fill);
			$('ul#content').css('display','none');
		});
	
	//Focusing on search box
	$( "#search_keyword" ).focus(function() {
	  	var searchKeyword = $( "#search_keyword" ).val();
			if (searchKeyword.length >= 3) {
				$('ul#content').css('display','');	
			}
	});
	
	});
    

</script>
</head>
<?php if((isset($_REQUEST['pagename']) && $_REQUEST['pagename']=="artwork_details.php" )){ ?>
<body>
<?php }else{
    
    ?>
<body onload="lazyLoadCaller();">
<?php 
} ?>

<!--Sliding FORM START HERE-->
<div id="slider" style="right:-250px;">
  <div id="sidebar" onclick="open_panel()"> <img src="images/submit-contact.png" alt=""> </div>
 
  <div id="header" class="head-form4">
    <h3>Contact Form</h3>
    <p>
     Please fill in the form below and we will contact you within 24 hours.
    </p>
    <div id="er1" style="position:absolute;font-size:13px;"></div>
    <div id="er1"></div>
     <form method="post" action="">
    <ul>
      <li><input class="inpt-bx" id="fullname" required name="fullname" value="<?php if(isset($_REQUEST['fullname'])) echo $_REQUEST['fullname']; ?>" placeholder="Name" type="text">
      </li>
      
      <li> <input id="email" name="email" class="inpt-bx" required placeholder="Email" type="text" value="<?php if(isset($_REQUEST['email'])) echo $_REQUEST['email']; ?>">
      </li>
      <li><input id="mobile" name="mobile" class="inpt-bx" required placeholder="Mobile" type="text" value="<?php if(isset($_REQUEST['mobile'])) echo $_REQUEST['mobile']; ?>">
      </li>
     
      
      
      <li>
        <textarea id="requirement" name="requirement" class="inpt-bx2" required placeholder="Comment"><?php if(isset($_REQUEST['requirement'])) echo $_REQUEST['requirement']; ?></textarea>
      </li>
      
      <li>
       <img src="captcha.php" style="display:block !important;position:static !important;float:right !important;">
        <input id="captcha" name="captcha" placeholder="Captcha code" class="inpt-bx" style="  width: 115px;height: 35px;margin-left: 0px;float:left;" type="text">
      </li>
      <li>
     
        <input style="cursor:pointer;border:none;" class="sbbtn" name="submitmsgbox" onclick="return validateContact1()" type="submit">
      </li>
    </ul>
     </form>
  </div>
 
</div>
<!--Sliding FORM END HERE-->
<div class="container-fluid" style="padding-left:0px; padding-right:0px ;">
  <div class="container">
    <div class="row">
      <div class="col-xs-12 col-sm-4 col-md-6 headerlogo">
        <div itemtype="http://schema.org/Organization" itemscope=""><a href="<?php echo SITE_URL; ?>"class="logo" itemprop="url"><img src="images/logo.jpg" alt="Indian Art Ideas -Online Art Gallery" title="Indian Art Ideas" itemprop="logo" width="215" height="101" /></a></div>
      </div>
      <div class="col-xs-12 col-sm-8 col-md-6 headeright">
        <ul class="headertopinfo">
          <li><img src="<?php echo CDN_IMAGE_SITE_URL; ?>images/icon-mail.jpg" alt="#" width="20" height="19" /><a href="mailto:info@indianartideas.com">info@indianartideas.com</a></li>
          <li><img src="<?php echo CDN_IMAGE_SITE_URL; ?>images/icon-phone.jpg" alt="#" width="20" height="21" />+91-9891517759, +91-9716888845</li>
        </ul>
        <div class="headersearch">
          <form id="keysearch" name="keysearch" onSubmit="return searchByKeyword();">
            <input type="text" id="search_keyword" placeholder="Search all art design paintings" value="<?php if(isset($_SESSION['artistkeyword']) && $_SESSION['artistkeyword']!=""){ echo $_SESSION['artistkeyword']; }else{} ?>" class="inputsearch search_keyword" />
            <input type="submit"class="btnsearch" value=""/>
            <ul id="content" class="searchlist" style="width: 395px !important;
    z-index: 999;
    position: relative;
    height: 200px;
    overflow-y: scroll;
    float: left;
    top: 2px; padding: 10px; background-color:#fff; display:none; left:-10px;"></ul>
          </form>
          <br />
          <span style="text-align:right; width:100%;"><a href="<?php echo SITE_URL.'advanced-search'; ?>">Advanced Search</a></span> </div>
      </div>
      <div style="clear:both; height:10px;"></div>
      <!---MOBILE MENU CSS START HERE-->
      <div class="mobilemenu"> <a class="toggleMenu" href="#"></a>
        <ul class="nav" style="display: none;">
          <li class="test"> <a  href="javascript:void(0);<?php //echo SITE_URL.'Buy-artwork'; ?>">BUY ART</a>
            <ul>
              <li> <a title="Browse All Art" onClick="return onlineartgallery_session('Buy-art-gallery',event);" href="javascript:void(0);">Browse All Art</a></li>
                      <li> <a title="Contemporary" href="<?php echo SITE_URL.'contemporary-art'; ?>" >Contemporary Art</a></li>
                      <li> <a title="Modern Art" href="<?php echo SITE_URL.'modern-art'; ?>" >Modern Art </a></li>
                      <li> <a title="Traditional Art" href="<?php echo SITE_URL.'traditional-art'; ?>" >Traditional Art</a> </li>
                      <li> <a title="Sculptures Art" href="<?php echo SITE_URL.'sculpture'; ?>" >Sculptures Art </a> </li>
                      <li> <a title="Photography" href="<?php echo SITE_URL.'photography'; ?>" >Photography</a></li>
                      <li> <a title="Sketches Art" href="<?php echo SITE_URL.'sketching'; ?>" >Sketches Art </a></li>
                      <li> <a title="Abstract Paintings" href="<?php echo SITE_URL.'abstract-art'; ?>" >Abstract Paintings </a></li>
                      <li> <a title="Landscape Paintings" href="<?php echo SITE_URL.'landscape-art'; ?>" >Landscape Paintings </a></li>
                      <li> <a title="Figurative Paintings" href="<?php echo SITE_URL.'figurative-art'; ?>" >Figurative Paintings </a></li>
                      <li> <a title="Religion Paintings" href="<?php echo SITE_URL.'religion-art'; ?>" >Religion Paintings</a></li>
                      <li> <a title="Still Life Paintings" href="<?php echo SITE_URL.'still-life-art'; ?>" >Still Life Paintings</a></li>
                      <li> <a title="Surrealism Paintings" href="<?php echo SITE_URL.'surrealism'; ?>" >Surrealism Paintings</a></li>
                      <li> <a title="Buddha Paintings" href="<?php echo SITE_URL.'buddha-paintings'; ?>" >Buddha Paintings</a></li>
                      <li> <a title="Oil Paintings" href="<?php echo SITE_URL.'oil-paintings'; ?>" >Oil Paintings </a> </li>
                      <li> <a title="Watercolor Paintings" href="<?php echo SITE_URL.'watercolor-paintings'; ?>" >Watercolor Paintings </a> </li>
                      <li> <a title="Acrylic Paintings" href="<?php echo SITE_URL.'acrylic-paintings'; ?>" >Acrylic Paintings </a> </li>
                      <li> <a title="Charcoal Paintings" href="<?php echo SITE_URL.'charcoal'; ?>" >Charcoal Paintings </a> </li>
                      <li> <a title="Mix Media Art Paintings" href="<?php echo SITE_URL.'mixed-media-art'; ?>" >Mix Media Art </a> </li>
              
            </ul>
          </li>
          <?php
                       $runcollection_count1 =mysql_query('select *  from tab_exibitions where int_to_date>='.date('Ymd').' order by int_from_date Limit 1') or die(mysql_error());
	
			$exibition=mysql_fetch_array($runcollection_count1);
                       ?>
          <li> <a href="#" class="parent">FEATURES </a>
            <ul>
              <li> <a href="<?php echo SITE_URL; ?>exhibition/<?php echo $exibition["int_id"];  ?>">Featured Collection</a></li>
              <li> <a title="Know Your Artist " href="<?php echo SITE_URL.'know-your-artist'; ?>">Know Your Artist</a></li>
              <li> <a title="What’s New" href="<?php echo SITE_URL.'whatsnew'; ?>"> What’s New</a></li>
              <li> <a title="Design Ideas " href="<?php echo SITE_URL.'design-ideas'; ?>">Design Ideas </a></li>
              <li> <a title="Most Loved Art " href="<?php echo SITE_URL.'most-loved-art'; ?>"> Most Loved Art </a></li>
              <li> <a title="Informative Resource" href="<?php echo SITE_URL.'blog'; ?>"> Informative Resource</a></li>
            </ul>
          </li>
          <li> <a title="ART SERVICES" href="<?php echo SITE_URL.'services'; ?>" class="parent">ART SERVICES</a>
            <ul style="height:auto !important;">
              <li class="childhover"> <a title="Art For Decor" href="javascript:void(0);">Art For Decor</a>
                <ul>
                  <li> <a title="Art for Interior designers" href="<?php echo SITE_URL.'interior-design'; ?>">Art for Interior designers</a></li>
                  <li> <a title="Corporate Art" href="<?php echo SITE_URL.'corporate-art'; ?>">Corporate Art</a></li>
                  <li> <a title="Hotel Art" href="<?php echo SITE_URL.'hotel-art'; ?>">Hotel Art</a></li>
                </ul>
              </li>
              <li class="childhover"> <a href="javascript:void(0);">Personalised Art</a>
                <ul>
                  <li> <a title="Custom Portraits" href="<?php echo SITE_URL.'custom-portrait'; ?>">Custom Portraits</a></li>
                  <li> <a title="Customised Art" href="<?php echo SITE_URL.'customised-art'; ?>">Customised Art</a></li>
                  <li> <a title="Photos to Art" href="<?php echo SITE_URL.'photoart-static'; ?>">Photos to Art</a></li>
                </ul>
              </li>
              <li class="childhover"> <a href="javascript:void(0);">Art Advisory</a>
                <ul>
                  <li> <a title="Benefit from our expert art advisory" href="<?php echo SITE_URL.'art-advisory'; ?>">Benefit from our expert art advisory</a></li>
                </ul>
              </li>
            </ul>
          </li>
          <li> <a title="ARTISTS" href="#" class="parent">ARTISTS </a>
            <ul style="height:auto !important;">
              <li> <a href="<?php echo SITE_URL.'indian-artists'; ?>">See All Artist</a></li>
              <?php 
               $artistofthemonth =  mysql_query("select *  from tab_spotlight_artist where setting=1") or die(mysql_error());
      if(mysql_num_rows($artistofthemonth)>0)
      {
          $artistmont=mysql_fetch_array($artistofthemonth); 
       
      }
             ?>
              <li> <a href="<?php echo SITE_URL.'artist-of-the-month/'.$artistmont['artistid']; ?>">Featured Artist</a></li>
              <?php /*    <?php 
                             
         $result_artist =mysql_query("select *  from register where usertype='Artist' and int_hidden='0' And txt_status='Approved' order by firstname,lastname limit 0,21") or die(mysql_error());
	for($i=0,$j=0;$i<mysql_num_rows($result_artist);$i++)
		{
			$j++;
			$row_artist=mysql_fetch_array($result_artist);
                        
                            
                            ?>
              <li><a href="<?php echo SITE_URL."artist/".str_replace(' ','',strtolower($row_artist["firstname"].'-'.$row_artist["lastname"])).'/'.$row_artist["id"] ?>">
                <?php if($row_artist['lastname']!="") {echo $row_artist['firstname'].' '.$row_artist['lastname'];}else{echo $row_artist['firstname'];} ?>
                </a></li>
              <?php } ?> */ ?>
              <?php 
    $artistofthemonth =  mysql_query("select *  from tab_spotlight_artist where setting=1") or die(mysql_error());
      if(mysql_num_rows($artistofthemonth)>0)
      {
          $artistmont=mysql_fetch_array($artistofthemonth); 
       
          
      }
          ?>
            </ul>
            </li>
          <li> <a title="About us" href="<?php echo SITE_URL.'about-us'; ?>" >ABOUT US </a>
            <ul>
              <li><a href="<?php echo SITE_URL; ?>about-us"> About US</a></li>
              <li><a href="<?php echo SITE_URL; ?>press-media">Press</a></li>
              <li class="childhover">
                <!--  <a href="<?php echo SITE_URL; ?>testimonials" > Testimonials</a>-->
                <a href="javascript:void(0);" > Testimonials</a>
                <ul class="childhover">
                  <li><a href="<?php echo SITE_URL; ?>testimonials">Customer Testimonials</a></li>
                  <!--                               <li><a href="<?php echo SITE_URL; ?>artist-testimonials">Artist Testimonials</a></li>-->
                </ul>
              </li>
              </ul>
              </li>
              <li><a href="<?php echo SITE_URL; ?>prints"> PRINTS</a></li>
              <li><a href="<?php echo SITE_URL; ?>contact-us"> CONTACT US</a></li>
            
          
          
        </ul>
      </div>
      <!--MOBILE MENU CSS END HERE-->
    </div>
  </div>
</div>
<div class="container-fluid headernav" style="position:relative;">
  <div class="container">
    <div class="row">
      <div class="headermenu">
        <div class="headerleftnav">
          <div class="nav">
            <ul class="mega-menu " id="mega-menu-7">
              <li> <a  href="javascript:void(0);<?php //echo SITE_URL.'Buy-artwork'; ?>" onClick="return onlineartgallery_session('Buy-art-gallery',event);" href="javascript:void(0);">BUY ART</a>
                <div style="left: 0px; top: 33px; z-index: 1000;" class="sub-container mega">
                  <ul style="display: none;" class="sub">
                    <div class="container headernavmid" style="height:290px;">
                    <div class="headerdropdownone">
                      <div class="navleftimg"><a onClick="return onlineartgallery_session('Buy-art-gallery',event);" href="javascript:void(0);"><img src="<?php echo CDN_IMAGE_SITE_URL; ?>images/browse-art-img.jpg" width="204" height="199" alt="#" /></a></div>
                      <div class="headeronemidmenu">
                        <h2>Top Categories</h2>
                        <div class="navwid158">
                          <ul>
                            <li> <a title="Contemporary" href="<?php echo SITE_URL.'contemporary-art'; ?>" class="parent">Contemporary Art</a></li>
                            <li> <a title="Modern Art" href="<?php echo SITE_URL.'modern-art'; ?>" class="parent">Modern Art </a></li>
                            <li> <a title="Traditional Art" href="<?php echo SITE_URL.'traditional-art'; ?>" class="parent">Traditional Art</a> </li>
                            <li> <a title="Sculptures Art" href="<?php echo SITE_URL.'sculpture'; ?>" class="parent">Sculptures Art </a> </li>
                            <li> <a title="Photography" href="<?php echo SITE_URL.'photography'; ?>" class="parent">Photography</a></li>
                            <li> <a title="Sketches Art" href="<?php echo SITE_URL.'sketching'; ?>" class="parent">Sketches Art </a></li>
                           
                          </ul>
                        </div>
                        <div class="navwid191 padlef20">
                          <ul>
                           <li> <a title="Abstract Paintings" href="<?php echo SITE_URL.'abstract-art'; ?>" class="parent">Abstract Paintings </a></li>
                            <li> <a title="Landscape Paintings" href="<?php echo SITE_URL.'landscape-art'; ?>" class="parent">Landscape Paintings </a></li>
                            <li> <a title="Figurative Paintings" href="<?php echo SITE_URL.'figurative-art'; ?>" class="parent">Figurative Paintings </a></li>
                            <li> <a title="Religion Paintings" href="<?php echo SITE_URL.'religion-art'; ?>" class="parent">Religion Paintings</a></li>
                            <li> <a title="Still Life Paintings" href="<?php echo SITE_URL.'still-life-art'; ?>" class="parent">Still Life Paintings</a></li>
                            <li> <a title="Surrealism Paintings" href="<?php echo SITE_URL.'surrealism'; ?>" class="parent">Surrealism Paintings</a></li>
                             <li> <a title="Buddha Paintings" href="<?php echo SITE_URL.'buddha-paintings'; ?>" class="parent">Buddha Paintings</a></li>
                            
                          </ul>
                        </div>
                        <div class="navwid158 padlef20">
                          <ul>
                             <li> <a title="Oil Paintings" href="<?php echo SITE_URL.'oil-paintings'; ?>" class="parent">Oil Paintings </a> </li>
                             <li> <a title="Watercolor Paintings" href="<?php echo SITE_URL.'watercolor-paintings'; ?>" class="parent">Watercolor Paintings </a> </li>
                             <li> <a title="Acrylic Paintings" href="<?php echo SITE_URL.'acrylic-paintings'; ?>" class="parent">Acrylic Paintings </a> </li>
                             <li> <a title="Charcoal Paintings" href="<?php echo SITE_URL.'charcoal'; ?>" class="parent">Charcoal Paintings </a> </li>
                             <li> <a title="Mix Media Art Paintings" href="<?php echo SITE_URL.'mixed-media-art'; ?>" class="parent">Mix Media Art </a> </li>
                             
                            
                            
                          </ul>
                        </div>
                        <div class="navwid190 padlef20" style="border-right:none; border-left:1px solid #CCC !important;">
                          <ul>
                             <li> <a title="Radha Krishna Paintings" href="<?php echo SITE_URL.'radha-krishna-paintings'; ?>" class="parent">Radha Krishna Paintings </a> </li>
                             <li> <a title="Ganesha Paintings" href="<?php echo SITE_URL.'ganesha-paintings'; ?>" class="parent">Ganesha Paintings </a> </li>
                             <li> <a title="Shiva Paintings" href="<?php echo SITE_URL.'shiva-paintings'; ?>" class="parent">Shiva Paintings </a> </li>
                             <li> <a title="Durga Paintings" href="<?php echo SITE_URL.'durga-paintings'; ?>" class="parent">Durga Paintings </a> </li>
                             <li> <a title="Lakshmi Paintings" href="<?php echo SITE_URL.'lakshmi-paintings'; ?>" class="parent">Lakshmi Paintings </a> </li>
                             <li> <a title="Saraswati Paintings" href="<?php echo SITE_URL.'saraswati-paintings'; ?>" class="parent">Saraswati Paintings </a> </li>
                            
                            
                          </ul>
                        </div>
                      </div>
                      <!--<div class="headernav-rgtimg"><img src="<?php echo CDN_IMAGE_SITE_URL; ?>images/headerrig-painting-img.jpg" alt="#" /></div>-->
                      
                    </div>
                  </ul>
                </div>
              </li>
              <li><a href="#">FEATURES </a>
                <div style="left: 0px; top: 33px; z-index: 1000;" class="sub-container mega">
                  <ul style="display: none;" class="sub">
                    <div class="container headernavmid" style="height:243px;">
                    <div class="headerdropdownone">
                      <ul class="headernavsecond">
                        <?php
                       $runcollection_countweb =mysql_query('select *  from tab_exibitions where int_to_date>='.date('Ymd').' order by int_from_date Limit 1') or die(mysql_error());
	
			$exibitionweb=mysql_fetch_array($runcollection_countweb);
                       ?>
                        <li><a href="<?php echo SITE_URL; ?>exhibition/<?php echo $exibitionweb["int_id"];  ?>"><img src="<?php echo CDN_IMAGE_SITE_URL; ?>images/feature-nav-img1.jpg" alt="#" width="144" height="144" /><br />
                          Featured<br />
                          <span>Collection</span></a></li>
                        <li>
                          <?php 
              // $latestartist = mysql_query('select * from register where usertype="Artist" and int_hidden="0" And addbycollector="0" And txt_status="Approved" order by id DESC limit 0,1');
             // if(mysql_num_rows($latestartist)>0)
             // {
               //   $latestart = mysql_fetch_array($latestartist); 
             // }
              ?>
                          <!--                             <a href="<?php echo SITE_URL.'know-your-artist'; ?>"><img src="<?php echo SITE_URL; ?>images/profile_images/<?php echo $latestart['profile_image']; ?>" alt="Image Not Found" />-->
                          <a href="<?php echo SITE_URL.'know-your-artist'; ?>"><img src="<?php echo CDN_IMAGE_SITE_URL; ?>images/feature-nav-img2.jpg" alt="#" width="144" height="144" /><br />
                          Know Your<br />
                          <span>Artist</span></a></li>
                        <li><a href="<?php echo SITE_URL.'whatsnew'; ?>"><img src="<?php echo CDN_IMAGE_SITE_URL; ?>images/feature-nav-img3.jpg" alt="#" width="144" height="144" /><br />
                          What’s<br />
                          <span>New</span></a></li>
                        <li><a href="<?php echo SITE_URL.'design-ideas'; ?>"><img src="<?php echo CDN_IMAGE_SITE_URL; ?>images/feature-nav-img4.jpg" alt="#" width="144" height="144" /><br />
                          Design<br />
                          <span>Ideas</span></a></li>
                        <li ><a href="<?php echo SITE_URL.'most-loved-art'; ?>" ><img src="<?php echo CDN_IMAGE_SITE_URL; ?>images/feature-nav-img5.jpg" width="144" height="144" alt="#" /><br />
                          Most Loved<br />
                          <span>Art</span></a></li>
                        <li style="margin-right:0px !important;"><a href="<?php echo SITE_URL.'blog'; ?>"><img src="<?php echo CDN_IMAGE_SITE_URL; ?>images/art-blog-img.jpg" width="144" height="144" alt="#" /><br />
                          Informative<br />
                          <span>Resource</span></a></li>
                      </ul>
                    </div>
                  </ul>
                </div>
              </li>
              <li><a href="<?php echo SITE_URL.'services'; ?>">ART SERVICES</a>
                <div style="left: 0px; top: 33px; z-index: 1000;" class="sub-container mega">
                  <ul style="display: none;" class="sub">
                    <div class="container headernavmid" style="height:210px;">
                    <div class="headerdropdownone">
                      <div class="navartserivebox">
                        <div class="artservicetext">
                          <h2> Art For <br />
                            Decor</h2>
                          <ul>
                            <li> <a title="Art for Interior designers" href="<?php echo SITE_URL.'interior-design'; ?>">Art for Interior designers</a></li>
                            <li> <a title="Corporate Art" href="<?php echo SITE_URL.'corporate-art'; ?>">Corporate Art</a></li>
                            <li> <a title="Hotel Art" href="<?php echo SITE_URL.'hotel-art'; ?>">Hotel Art</a></li>
                          </ul>
                        </div>
                        <div class="artserviceimgbox"><img src="<?php echo CDN_IMAGE_SITE_URL; ?>images/art-decor-img.jpg" alt="#" width="132" height="132" /></div>
                      </div>
                      <div class="navartserivebox">
                        <div class="artservicetext">
                          <h2> Personalised <br />
                            Art</h2>
                          <ul>
                            <li> <a title="Custom Portraits" href="<?php echo SITE_URL.'custom-portrait'; ?>">Custom Portraits</a></li>
                            <li> <a title="Customised Art" href="<?php echo SITE_URL.'customised-art'; ?>">Customised Art</a></li>
                            <li> <a title="Photos to Art" href="<?php echo SITE_URL.'photoart-static'; ?>">Photos to Art</a></li>
                          </ul>
                        </div>
                        <div class="artserviceimgbox"><img src="<?php echo CDN_IMAGE_SITE_URL; ?>images/personalised-art-img.jpg" width="132" height="132" alt="#" /></div>
                      </div>
                      <div class="navartserivebox" style="margin-right:0px;">
                        <div class="artservicetext">
                          <h2> Art<br />
                            Advisory</h2>
                          <ul>
                            <li><a href="<?php echo SITE_URL.'art-advisory'; ?>">Benefit from our expert art advisory</a> </li>
                          </ul>
                        </div>
                        <div class="artserviceimgbox"><img src="<?php echo CDN_IMAGE_SITE_URL; ?>images/art-advisory.jpg" alt="#" width="132" height="132" /></div>
                      </div>
                    </div>
                  </ul>
                </div>
              </li>
              <li><a href="<?php echo SITE_URL.'indian-artists'; ?>">ARTISTS</a>
                <div style="left: 0px; top: 33px; z-index: 1000;" class="sub-container mega">
                  <ul style="display: none;" class="sub">
                    <div class="container headernavmid" style="height:502px;">
                    <div class="headerdropdownone">
                      <ul class="headernavthird">
                        <?php 
                        $result_artist_rand =mysql_query("select *  from register where usertype='Artist' and int_hidden='0' And txt_status='Approved' and addbycollector='0' and profile_image!='' ORDER BY RAND() LIMIT 6") or die(mysql_error());
	for($k=0,$l=0;$k<mysql_num_rows($result_artist_rand);$k++)
		{
			$l++;
			$row_artist_rand=mysql_fetch_array($result_artist_rand);
                
                       ?>
                        <li ><a  href="<?php if($row_artist_rand["lastname"]!="") { echo SITE_URL."artist/".str_replace(' ','',strtolower($row_artist_rand["firstname"].'-'.$row_artist_rand["lastname"])).'/'.$row_artist_rand["id"]; } else { echo SITE_URL."artist/".$row_artist_rand["firstname"].'/'.$row_artist_rand["id"]; }  ?>">
                          <?php if($row_artist_rand['profile_image']!="") { ?>
                          <div class="artistborder"> <img src="<?php echo SITE_URL; ?>images/profile_images/<?php echo $row_artist_rand['profile_image']; ?>" alt="#"  /></div>
                          <?php echo  $row_artist_rand["firstname"].' '.$row_artist_rand["lastname"]; ?>
                          <?php }else{ ?>
                          <div class="artistborder"> <img src="<?php echo CDN_IMAGE_SITE_URL; ?>images/small-no-artist.png" alt="#"  /></div>
                          <?php echo  $row_artist_rand["firstname"].' '.$row_artist_rand["lastname"]; ?>
                          <?php } ?>
                          </a></li>
                        <!--                    <li><a href="#"><img src="<?php echo SITE_URL; ?>images/gurukinkar-img.jpg" alt="#" /><br />Gurukinkar Dhang</a></li>
                    <li><a href="#"><img src="<?php echo SITE_URL; ?>images/pankaj-img.jpg" alt="#" /><br />Pankaj Saroj</a></li>
                    <li><a href="#"><img src="<?php echo SITE_URL; ?>images/priti.jpg" alt="#" /><br />Priti Parikh</a></li>
                     <li ><a href="#" ><img src="<?php echo SITE_URL; ?>images/Iqbal.jpg" alt="#" /><br />Iqbal Gurtu</a></li>
                      <li style="margin-right:0px !important;"><a href="#"><img src="<?php echo SITE_URL; ?>images/babita.jpg" alt="#" /><br />Babita Das</a></li>-->
                        <?php } ?>
                      </ul>
                      <div class="clear" style="height:10px;"></div>
                      <div class="navgreybotcontainer">
                        <div class="artistgreyleft650">
                          <div class="navgreyboldtext">Other Artists</div>
                          <div class="greywid121" style="border-left:none;">
                            <ul class="artistnamelist">
                              <?php 
                             
         $result_artist =mysql_query("select *  from register where usertype='Artist' and int_hidden='0' And txt_status='Approved' and addbycollector='0' order by firstname,lastname limit 0,21") or die(mysql_error());
	for($i=0,$j=0;$i<mysql_num_rows($result_artist);$i++)
		{
			$j++;
			$row_artist=mysql_fetch_array($result_artist);
                        
                            
                            ?>
                              <li><a href="<?php echo SITE_URL."artist/".str_replace(' ','',strtolower($row_artist["firstname"].'-'.$row_artist["lastname"])).'/'.$row_artist["id"] ?>">
                                <?php if($row_artist['lastname']!="") {echo $row_artist['firstname'].' '.$row_artist['lastname'];}else{echo $row_artist['firstname'];} ?>
                                </a></li>
                              <?php } ?>
                             
                            </ul>
                          </div>
                        
                          <div class="artistmid277"> See All<br />
                            <span>Artists </span><br />
                            <?php 
    $artistofthemonth =  mysql_query("select *  from tab_spotlight_artist where setting=1") or die(mysql_error());
      if(mysql_num_rows($artistofthemonth)>0)
      {
          $artistmont=mysql_fetch_array($artistofthemonth); 
       
             if($artistmont['artistid']!="")
              {
              $monthimages_count=    mysql_query("select *  from register where id='".$artistmont['artistid']."' and txt_status='Approved'") or die(mysql_error());
                $artistmontimage=mysql_fetch_array($monthimages_count);          
              }
      }
          ?>
                            <a href="<?php echo SITE_URL.'indian-artists'; ?>"> <img src="<?php echo CDN_IMAGE_SITE_URL; ?>images/a-z-img.jpg" alt="#" width="136" height="58" /></a></div>
                        </div>
                        <div class="artistrgtimg">
                          <div class="artistmonthtext"><a href="<?php echo SITE_URL.'artist-of-the-month/'.$artistmont['artistid']; ?>">Featured Artist </a></div>
                          <div class="artisttopmenuimg"><a href="<?php echo SITE_URL.'artist-of-the-month/'.$artistmont['artistid']; ?>"> <img src="<?php echo SITE_URL; ?>images/profile_images/<?php echo $artistmontimage['profile_image']; ?>" alt="<?php echo CDN_IMAGE_SITE_URL; ?>images/default_user_icon.png" /></a></div>
                          <br />
                          <div class="clear"></div>
                          <p>Upclose and personal with your favorite artist </p>
                        </div>
                        <div class="clear"></div>
                      </div>
                    </div>
                  </ul>
                </div>
              </li>
              <li><a href="<?php echo SITE_URL.'about-us'; ?>">ABOUT US</a>
                <div style="left: 0px; top: 33px; z-index: 1000;" class="sub-container mega">
                  <ul style="display: none;" class="sub">
                    <div class="container headernavmid" style="height:165px;">
                      <div class="headernavfour">
                        <ul>
                          <li><a href="<?php echo SITE_URL; ?>about-us"style="width:240px;"> <img src="<?php echo CDN_IMAGE_SITE_URL; ?>images/icon-aboutus.jpg" width="73" height="73" alt="#" />About US</a></li>
                          <li><a href="<?php echo SITE_URL; ?>press-media"><img src="<?php echo CDN_IMAGE_SITE_URL; ?>images/icon-press.jpg" width="73" height="73" alt="#" />Press</a></li>
                          <li><a href="<?php echo SITE_URL; ?>testimonials" style="width:270px;"> <img src="<?php echo CDN_IMAGE_SITE_URL; ?>images/icon-news.jpg" width="73" height="73" alt="#" />Testimonials</a>
                            <ul>
                              <li><a href="<?php echo SITE_URL; ?>testimonials">Customer Testimonials</a><br />
                                <!--                                    <a href="<?php echo SITE_URL; ?>artist-testimonials">Artist Testimonials</a>-->
                              </li>
                            </ul>
                          </li>
                          </ul>
                         <!-- <li><a href="<?php echo SITE_URL; ?>contact-us"> <img src="<?php echo CDN_IMAGE_SITE_URL; ?>images/icon-contact-us.jpg" width="73" height="73" alt="#" />Contact us</a></li> -->
                         <div class="headernav-rgtimg"><img src="<?php echo IMAGE_URL; ?>aboutmenu-rgt-img.jpg" alt="#" /></div>
                      </div>
                    </div>
                  </ul>
                </div>
              </li>
             <li><a href="<?php echo SITE_URL.'prints'; ?>">PRINTS </a> </li>
             <li><a href="<?php echo SITE_URL.'contact-us'; ?>">CONTACT US</a> </li>
            </ul>
          </div>
        </div>
        <div class="headernavright">
          <script  type="text/javascript">

    function toggle_visibility(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'block')
          e.style.display = 'none';
       else
          e.style.display = 'block';
		 
    }

</script>
          <div class="mobsearch" id="mobilesearch" style="display:none;">
            <form id="keysearch2" name="keysearch2" onSubmit="return searchByKeyword2();">
              <input type="text" id="search_keyword2" placeholder="Search all art design paintings" value="<?php if(isset($_SESSION['artistkeyword']) && $_SESSION['artistkeyword']!=""){ echo $_SESSION['artistkeyword']; }else{} ?>" class="inputsearch search_keyword" />
              <input type="submit" class="btnsearch" value=""/>
            </form>
          </div>
          <ul>
            <li style="background:none;" class="mobsearchicon"><a href="#" onClick="toggle_visibility('mobilesearch');"><img src="<?php echo CDN_IMAGE_SITE_URL; ?>images/header-search-icon.png" width="19" height="19"></a></li>
            <li  class="cartimg" style="background:none;"><a title="Shopping Cart"  href="<?php echo SITE_URL.'cart'; ?>"> <img src="<?php echo SITE_URL; ?>images/cart-img.jpg" alt="#" class="fl"></a>
              <!--                <a class="yellowcart" title="Shopping Cart" onClick="document.getElementById('lightCart').style.display = 'block';
                                        document.getElementById('fadeCart').style.display = 'block';
                                        overflownoneCart('enable');" href="javascript:void(0)">-->
              <a class="yellowcart" title="Shopping Cart"  href="<?php echo SITE_URL.'cart'; ?>">
              <?php if(isset($numrecs) && $numrecs > 0) { ?>
              <?=$numrecs?>
              <?php } else { echo "0"; } ?>
              </a>
              <!--                <a href="#" class="yellowcart">2</a>-->
            </li>
            <?php if( (isset( $_SESSION["userid"]) &&  !empty($_SESSION["userid"]) && !isset($_SESSION["ref_userid"]) )) { 
       
        $User = mysql_query('select * from register where id="'.$_SESSION["userid"].'"');
        if(mysql_num_rows($User)>0)
        {
            while ($rowuser= mysql_fetch_array($User))
            {
                $username=$rowuser['username'];
                $fullname=$rowuser['firstname'];
                $lastname=$rowuser['lastname'];
                $profileimage=$rowuser['profile_image'];
            }
            ?>
            <li >
              <?php if(isset($profileimage) && $profileimage!="") { ?>
              <a href="javascript:void(0)"> <img src="<?php echo SITE_URL; ?>images/profile_images/<?php echo $profileimage; ?>" height="21" width="20" alt="" class="sign-up-icon" align="left" /> </a>
              <ul>
                <?php    if( $_SESSION["usertype"]=="Artist"){ 
                   
               if(isset($_SESSION["userid"]))
               {
        $User_artist = mysql_query('select * from register where id="'.$_SESSION["userid"].'"');
        if(mysql_num_rows($User_artist)>0)
        {
            while ($rowuser_artist= mysql_fetch_array($User_artist))
            {
              
                $fullname=$rowuser_artist['firstname'];
                 $lastname=$rowuser_artist['lastname'];
                $dob=$rowuser_artist['dob'];
                 $profileimage=$rowuser_artist['profile_image'];                
                $image1=$rowuser_artist['image1'];
                $image2=$rowuser_artist['image2'];
                $image3=$rowuser_artist['image3'];
                $address=$rowuser_artist['address'];
                $status=$rowuser_artist['txt_status'];
                  $unique_id=$rowuser_artist['txt_unique_id'];
            }
        }
               }    
               
               if($fullname!="" && $lastname!="" && $image1!="" && $image2!="" && $image3!="" && $address!="")
               {
               ?>
                <!--                  <li><a href="<?php echo SITE_URL.'my-profile';  ?>"><?php echo $fullname.' '.$lastname; ?></a></li>-->
                <li><a href="<?php echo SITE_URL.'my-profile';  ?>">My Profile</a></li>
                <li><a href="<?php echo SITE_URL.'post-artwork';  ?>">Upload an Art</a></li>
                <li><a href="<?php echo SITE_URL.'view-collection/'.$_SESSION["userid"];  ?>">My Artwork</a></li>
                <li><a href="<?php echo SITE_URL.'artist-speak';  ?>">Artist Speak</a></li>
                <li><a href="<?php echo SITE_URL.'track-orders'; ?>">Track Orders </a></li>
                <li><a href="<?php echo SITE_URL.'completed-orders'; ?>">Sold Artwork</a></li>
                <!--                <li><a href="<?php echo SITE_URL.'change-password';  ?>">Change Password</a></li>-->
                <?php
             if(!empty($_SESSION['user_fbid'])) 
		   {
			   ?>
                <li style="border:none;"><a href="javascript:void(0);" onClick="FBLogout(); return false;"> Log Out </a> </li>
                <?php }else{ ?>
                <li style="border:none;"><a href="<?php echo SITE_URL; ?>logout"> Log Out </a> </li>
                <?php } ?>
                <?php
               } else { ?>
                <li style="text-align:center; font-size:13px; font-family: 'Din-light'; color:#e94a78; padding:10px 10px 0 10px;">Please complete your registration by filling all the details !</li>
                <li><a href="<?php echo SITE_URL.'register-stepone/'.$unique_id;  ?>">Edit</a></li>
                <?php
             if(!empty($_SESSION['user_fbid'])) 
		   {
			   ?>
                <li style="border:none; background:none;"><a href="javascript:void(0);" onClick="FBLogout(); return false;"> Log Out </a> </li>
                <?php }else{ ?>
                <li style="border:none; background:none;"><a href="<?php echo SITE_URL; ?>logout"> Log Out </a> </li>
                <?php } ?>
                <?php       }
               
               
               
               } else{
                   ?>
                <!--            <li><a href="<?php echo SITE_URL.'my-profile';  ?>"><?php echo $fullname.' '.$lastname; ?></a></li>-->
                <!--                <li><a href="<?php echo SITE_URL.'view-collection/'.$_SESSION["userid"];  ?>">My Collection</a></li>-->
                <li><a href="<?php echo SITE_URL.'my-artists';  ?>">My Artist</a></li>
                <li><a href="<?php echo SITE_URL.'favourites'; ?>">My Favorites</a></li>
                <li><a href="<?php echo SITE_URL.'track-orders'; ?>">Track My Orders </a></li>
                <!--                <li><a href="<?php echo SITE_URL.'completed-orders'; ?>">Orders Completed</a></li>-->
                <li><a href="<?php echo SITE_URL.'view-collection/'.$_SESSION['userid']; ?>">My Collections</a></li>
                <li><a href="<?php echo SITE_URL.'resaledetail/received'; ?>">Track Re-Sale Orders</a></li>
                <!--                <li><a href="<?php echo SITE_URL.'check-messages';  ?>">Check Messages </a></li>-->
                <li><a href="<?php echo SITE_URL.'my-profile';  ?>">My Profile</a></li>
                <!--                <li><a href="<?php echo SITE_URL.'change-password';  ?>">Change Password</a></li>-->
                <?php
             if(!empty($_SESSION['user_fbid'])) 
		   {
			   ?>
                <li style="border:none;"><a href="javascript:void(0);" onClick="FBLogout(); return false;"> Log Out </a> </li>
                <?php }else{ ?>
                <li style="border:none;"><a href="<?php echo SITE_URL; ?>logout"> Log Out </a> </li>
                <?php } ?>
                <?php  } ?>
              </ul>
            </li>
            <?php } else { ?>
            <li> <a href="javascript:void(0)"> <img src="<?php echo CDN_IMAGE_SITE_URL; ?>images/sign-up-icon.jpg" height="21" width="20" alt="Sign Up" class="sign-up-icon" align="left" /> </a>
              <ul>
                <?php    if( $_SESSION["usertype"]=="Artist"){ 
                   
               if(isset($_SESSION["userid"]))
               {
        $User_artist = mysql_query('select * from register where id="'.$_SESSION["userid"].'"');
        if(mysql_num_rows($User_artist)>0)
        {
            while ($rowuser_artist= mysql_fetch_array($User_artist))
            {
              
                $fullname=$rowuser_artist['firstname'];
                 $lastname=$rowuser_artist['lastname'];
                $dob=$rowuser_artist['dob'];
                 $profileimage=$rowuser_artist['profile_image'];                
                $image1=$rowuser_artist['image1'];
                $image2=$rowuser_artist['image2'];
                $image3=$rowuser_artist['image3'];
                $address=$rowuser_artist['address'];
                  $status=$rowuser_artist['txt_status'];
                 $unique_id=$rowuser_artist['txt_unique_id'];
            }
        }
               }    
               
               if($fullname!="" && $lastname!="" && $image1!="" && $image2!="" && $image3!="" && $address!="")
               {
               ?>
                <!--                      <li><a href="<?php echo SITE_URL.'my-profile';  ?>"><?php echo $fullname.' '.$lastname; ?></a></li>-->
                <li><a href="<?php echo SITE_URL.'my-profile';  ?>">My Profile</a></li>
                <li><a href="<?php echo SITE_URL.'post-artwork';  ?>">Upload an Art</a></li>
                <li><a href="<?php echo SITE_URL.'view-collection/'.$_SESSION["userid"];  ?>">My Artwork</a></li>
                <li><a href="<?php echo SITE_URL.'artist-speak';  ?>">Artist Speak</a></li>
                <li><a href="<?php echo SITE_URL.'track-orders'; ?>">Track My Orders </a></li>
                <li><a href="<?php echo SITE_URL.'completed-orders'; ?>">Sold Artwork</a></li>
                <!--                <li><a href="<?php echo SITE_URL.'change-password';  ?>">Change Password</a></li>-->
                <?php
             if(!empty($_SESSION['user_fbid'])) 
		   {
			   ?>
                <li style="border:none; "><a href="javascript:void(0);" onClick="FBLogout(); return false;"> Log Out </a> </li>
                <?php }else{ ?>
                <li style="border:none;"><a href="<?php echo SITE_URL; ?>logout"> Log Out </a> </li>
                <?php } ?>
                <?php
               } else { ?>
                <li style="text-align:center; font-size:13px; font-family: 'Din-light'; color:#e94a78; padding:10px 10px 0 10px;">Please complete your registration by filling all the details !</li>
                <li><a href="<?php echo SITE_URL.'register-stepone/'.$unique_id;  ?>">Edit</a></li>
                <?php
             if(!empty($_SESSION['user_fbid'])) 
		   {
			   ?>
                <li style="border:none;"><a href="javascript:void(0);" onClick="FBLogout(); return false;"> Log Out </a> </li>
                <?php }else{ ?>
                <li style="border:none;"><a href="<?php echo SITE_URL; ?>logout"> Log Out </a> </li>
                <?php } ?>
                <?php       }
               
               
               
               } else{
                   ?>
                <li>
                  <!--                <a href="<?php echo SITE_URL.'my-profile';  ?>"><?php echo $fullname.' '.$lastname; ?></a></li>-->
                  <!--                <li><a href="<?php echo SITE_URL.'view-collection/'.$_SESSION["userid"];  ?>">My Collection</a></li>-->
                <li><a href="<?php echo SITE_URL.'my-artists';  ?>">My Artist</a></li>
                <li><a href="<?php echo SITE_URL.'favourites'; ?>">My Favorites</a></li>
                <li><a href="<?php echo SITE_URL.'track-orders'; ?>">Track My Orders </a></li>
                <!--                <li><a href="<?php echo SITE_URL.'completed-orders'; ?>">Orders Completed</a></li>-->
                <li><a href="<?php echo SITE_URL.'view-collection/'.$_SESSION['userid']; ?>">My Collections</a></li>
                <li><a href="<?php echo SITE_URL.'resaledetail/received'; ?>">Track Re-Sale Orders</a></li>
                <!--                <li><a href="<?php echo SITE_URL.'check-messages';  ?>">Check Messages </a></li>-->
                <li><a href="<?php echo SITE_URL.'my-profile';  ?>">My Profile</a></li>
                <!--                <li><a href="<?php echo SITE_URL.'change-password';  ?>">Change Password</a></li>-->
                <?php
             if(!empty($_SESSION['user_fbid'])) 
		   {
			   ?>
                <li style="border:none;"><a href="javascript:void(0);" onClick="FBLogout(); return false;"> Log Out </a> </li>
                <?php }else{ ?>
                <li style="border:none;"><a href="<?php echo SITE_URL; ?>logout"> Log Out </a> </li>
                <?php } ?>
                <?php  } ?>
              </ul>
            </li>
            <?php } ?>
            <?php if(isset($status) && $status=='Unverified' && $_SESSION['usertype']=='Artist'){  ?>
            <li style="background:none;" class="mobwid76"><a href="javascript:void(0);" style="background:none;"><?php echo "".$fullname;  ?></a> </li>
            <?php }else{ ?>
            <li style="background:none;" class="mobwid76"><a href="<?php echo SITE_URL.'my-profile';  ?>" style="background:none;"><?php echo "".$fullname;  ?></a> </li>
            <?php } ?>
            <li style="background:none;"> |</li>
            <?php
             if(!empty($_SESSION['user_fbid'])) 
		   {
			   ?>
            <li style="background:none;"><a href="javascript:void(0);" onClick="FBLogout(); return false;" style="background:none;"> Log Out </a> </li>
            <?php }else{ ?>
            <li style="background:none;"><a href="<?php echo SITE_URL; ?>logout" style="background:none;"> Log Out </a> </li>
            <?php } ?>
            <?php
        }
            
        } else if (isset($_SESSION["ref_userid"]) && $_SESSION["ref_userid"]!=""){ 
            
           
                   
               if(isset($_SESSION["ref_userid"]))
               {
        $ref_artist = mysql_query('select * from tab_referrals where int_id="'.$_SESSION["ref_userid"].'"');
        if(mysql_num_rows($ref_artist)>0)
        {
            while ($rowref= mysql_fetch_array($ref_artist))
            {
              
                $fullname=$rowref['txt_first_name'];
              $image=$rowref['profile_image'];
                           
              
            }
        }
               }   
            
            ?>
            <li> <a href="javascript:void(0)">
              <?php if(isset($image) && $image!="") {?>
              <img src="<?php echo SITE_URL; ?>images/profile_images/<?php echo $image; ?>" height="21" width="20" alt="Sign Up" class="sign-up-icon" align="left" />
              <?php }else{ ?>
              <img src="<?php echo CDN_IMAGE_SITE_URL; ?>images/sign-up-icon.jpg" height="21" width="20" alt="Sign Up" class="sign-up-icon" align="left" /> </a>
              <?php } ?>
              <ul>
                <li>
                  <!--                          <a href="<?php echo SITE_URL.'my-profile';  ?>"><?php echo $fullname; ?></a></li>-->
                <li><a href="<?php echo SITE_URL.'favourites'; ?>">My Favourite </a></li>
                <li><a href="<?php echo SITE_URL.'ref-sales'; ?>">My Sales </a></li>
                <li><a href="<?php echo SITE_URL.'ref-payments';  ?>">Payment History </a></li>
                <li><a href="<?php echo SITE_URL.'ref-buyers';  ?>">My Buyers </a></li>
                <li><a href="<?php echo SITE_URL.'my-artists';  ?>">My Artist </a></li>
                <li><a href="<?php echo SITE_URL.'my-profile';  ?>">My Profile</a></li>
                <li><a href="<?php echo SITE_URL.'refterms';  ?>">Terms & Conditions</a></li>
                <li><a href="<?php echo SITE_URL.'ref-change-password';  ?>">Change Password</a></li>
                <li style="background:none;"><a href="<?php echo SITE_URL; ?>logout"> Log Out </a> </li>
              </ul>
            </li>
            <li style="background:none;" class="mobwid76"><a href="<?php echo SITE_URL.'my-profile';  ?>" style="background:none;"><?php echo $fullname;  ?> </a> </li>
            <li style="background:none;"> |</li>
            <li style="border:none;"><a href="<?php echo SITE_URL; ?>logout"> Log Out </a> </li>
            <?php   } else if (isset($_COOKIE['txtuniqueid'])) {
        
            $User_artist = mysql_query('select * from register where txt_unique_id="'.$_COOKIE['txtuniqueid'].'"');
        if(mysql_num_rows($User_artist)>0)
        {
            while ($rowuser_artist= mysql_fetch_array($User_artist))
            {
               $id=$rowuser_artist['id'];
                $fullname=$rowuser_artist['firstname'];
                 $lastname=$rowuser_artist['lastname'];
                $dob=$rowuser_artist['dob'];
                 $profileimage=$rowuser_artist['profile_image'];                
                $image1=$rowuser_artist['image1'];
                $image2=$rowuser_artist['image2'];
                $image3=$rowuser_artist['image3'];
                $address=$rowuser_artist['address'];
                $status=$rowuser_artist['txt_status'];
                 $unique_id=$rowuser_artist['txt_unique_id'];
            }
        }
         ?>
            <li > <a href="javascript:void(0)">
              <?php if(isset($profileimage) && $profileimage!="" ){ ?>
              <img src="<?php echo SITE_URL; ?>images/profile_images/<?php echo $profileimage; ?>" height="21" width="20" alt="" class="sign-up-icon" align="left" />
              <?php }else{ ?>
              <img src="<?php echo CDN_IMAGE_SITE_URL; ?>images/sign-up-icon.jpg">
              <?php } ?>
              </a>
              <ul>
                <?php      if($fullname!="" && $lastname!="" &&  $image1!="" && $image2!="" && $image3!="" && $address!="")
               {
               ?>
                <!--                  <li><a href="<?php echo SITE_URL.'my-profile';  ?>"><?php echo $fullname.' '.$lastname; ?></a></li>-->
                <li><a href="<?php echo SITE_URL.'my-profile';  ?>">My Profile</a></li>
                <li><a href="<?php echo SITE_URL.'post-artwork';  ?>">Upload an Artwork</a></li>
                <li><a href="<?php echo SITE_URL.'view-collection/'.$_SESSION["userid"];  ?>">My Artwork</a></li>
                <li><a href="<?php echo SITE_URL.'artist-speak';  ?>">Artist Speak</a></li>
                <li><a href="<?php echo SITE_URL.'track-orders'; ?>">Track Orders </a></li>
                <li><a href="<?php echo SITE_URL.'completed-orders'; ?>">Sold Artwork</a></li>
                <!--                <li><a href="<?php echo SITE_URL.'change-password';  ?>">Change Password</a></li>-->
                <?php
               } else{?>
                <li style="text-align:center; font-size:13px; font-family: 'Din-light'; color:#e94a78; padding:10px 10px 0 10px;">Please complete your registration by filling all the details !</li>
                <li><a href="<?php echo SITE_URL.'register-stepone/'.$unique_id;  ?>">Edit</a></li>
                <li style="border:none;"><a href="<?php echo SITE_URL; ?>logout"> Log Out </a> </li>
                <?php } ?>
              </ul>
            </li>
            <?php if(isset($status) && $status=='Unverified' && isset($_COOKIE['txtuniqueid']) && !empty($_COOKIE['txtuniqueid']) ){  ?>
            <li style="background:none;"><a href="javascript:void(0);" style="background:none;"><?php echo "".$fullname;  ?></a> </li>
            <?php }else{ ?>
            <li style="background:none;"><a href="<?php echo SITE_URL.'my-profile';  ?>" style="background:none;"><?php echo "".$fullname;  ?></a> </li>
            <?php } ?>
            <li style="background:none;"> |</li>
            <li style="border:none;"><a href="<?php echo SITE_URL; ?>logout"> Log Out </a> </li>
            <?php  }
        else{ ?>
            <li style="background:none;">
              <!--              <img src="<?php echo SITE_URL; ?>images/sign-up-icon.jpg" height="21" width="20" alt="Sign Up" class="sign-up-icon" align="left" />-->
              <!--              <a href="<?php echo SITE_URL.'sign-up';?>" style="background:none;">Sign up </a> -->
              <a href="javascript:void(0)" onClick="document.getElementById('signuppopup').style.display = 'block';document.getElementById('fadelogin2').style.display = 'block';overflowsignuppop('enable');" style="background:none;">Sign up</a> </li>
            <li style="background:none;"> |</li>
            <!--            <li style="background:none;"> <a href="<?php echo SITE_URL; ?>login" style="background:none;"> Log in </a> </li>-->
            <li style="background:none;"> <a href="javascript:void(0)" onClick="document.getElementById('loginpopup').style.display = 'block';document.getElementById('fadelogin').style.display = 'block';overflowloginpop('enable');" id="favroitepopup" style="background:none;"> Log in </a> </li>
            <?php } ?>
            <!-- <img src="<?php echo SITE_URL; ?>images/white-arrow.png" height="8" width="13" class="white-arrow-icon" />-->
            <!--            <div class="sub-container mega" style="left: 0px; top: 41px; z-index: 1000;">
              <ul class="sub" style="display: none;">
                <div class="header-menu-N">
                  <div class="fl login-signup">
                    <input name="" type="text" class="login-txtbox" onFocus="if(this.value=='E-mail')this.value=''" onBlur="if(this.value=='')this.value='E-mail'" value="E-mail" />
                    <br />
                    <input name="" type="text" class="login-txtbox" onFocus="if(this.value=='Password')this.value=''" onBlur="if(this.value=='')this.value='Password'" value="Password" />
                    <br />
                    <div class="fl forgot-pwd" style="padding-top:10px;"><a href="#">Forgot Password</a></div>
                    <div class="fr">
                      <input name="" type="button" value="Login Now!" class="bluelogin-btn" />
                    </div>
                  </div>
                  <div class="fr fb-signup tc"><img src="<?php echo SITE_URL; ?>images/fb-signup.png" /><br />
                    Signup with<br />
                    <span>facebook</span></div>
                  <div class="fr">
                    <div class="or-crcle tc">or</div>
                  </div>
                  <div class="fr artist-signup tc"><img src="<?php echo SITE_URL; ?>images/artist-signup.png" /><br />
                    Artist<br />
                    Signup</div>
                  <div class="fr collector-signup tc"><img src="<?php echo SITE_URL; ?>images/image-gallery-icon.png" /><br />
                    Gallery<br />
                    Signup</div>
                  <div class="fr collector-signup tc"><img src="<?php echo SITE_URL; ?>images/collecter-signup.png" /><br />
                    Collector<br />
                    Signup</div>
                </div>
              </ul>
            </div>-->
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<!--<script>
console.log('<?php// echo ltrim($_SERVER["REQUEST_URI"], '/'); ?>');
</script>-->
<!--------- BREADCRUMB STARTS HERE--------------->
<?php

$url= strtolower(ltrim($_SERVER["REQUEST_URI"], '/'));
$select_bred=mysql_query("select * from bredcrumbs where url='$url'");
$row=mysql_fetch_assoc($select_bred);
//print_r($row=mysql_fetch_assoc($select_bred));
if($row['position']=='top')
{
	?>
<div class="container breadcramelink" style="text-transform:capitalize;">

<?php



$return = array();
//$return[] = $parent_array;
function getparent($parent_id)
{
	global $return;

	$select_parent=mysql_query("select * from bredcrumbs where id='$parent_id'");
	$row1=mysql_fetch_assoc($select_parent);
	
	$newparentid = $row1['parent_id'];

	if($newparentid>1)
	{
		//print_r($parent_array);die;
		getparent($newparentid);
			
	}
	$return[] = $row1;
	return $return;
}




if($row['parent_id']>0)
{
$result = getparent($row['id']);
}

/*echo '<pre>';
print_r($result);
die;*/
echo '<a href="'.SITE_URL.'"><img src="'.SITE_URL.'images/icon-home.png" alt=""> Home </a>  » ';
$last = end(array_keys($result));
$x=0;
foreach($result as $p=>$val)
{
if ($x != $last)
		if($val['url'] && $val['url']!='')
	echo '<a href="'.SITE_URL.$val['url'].'">'.strtolower($val['breadcrumb_name']).'</a>  » ';
	else
	echo strtolower($val['breadcrumb_name']).'  » ';
else
	echo strtolower($val['breadcrumb_name']);
$x++;
}

?>

</div>
<?php
}
?>

<!--------- BREADCRUMB END HERE --------------->

<script  type="text/javascript">
function onlineartgallery_session(link_name,Event){
       
	$.ajax({   
            type: "POST",
       
        data: null,
		  url: '<?=SITE_URL?>ajax_onlineartgallery.php', 
		  success: function(data)          //on recieve of reply
		  {
			window.location.href='<?php echo SITE_URL.'Buy-artwork'; ?>';
		  }
	});
	return false;
}
</script>
<style>
   .error{
/*        	border-style: solid;
    border-color: red;*/
    color: red;
    
     padding: 5px;
    }
</style>
<script  type="text/javascript">
     
          
    function searchByKeyword() {
        
        var value = '';
        var artstyle = '';
        var artcolor = '';
        var artkeyword = '';
        var artsubject = '';
        var artshipping = '';
        var artcategory = '';
        var artmedium = '';
        var col = '';
        var colorShadeParam = '';
        //var colorshade = ''; 
        var artprice = '';
        var artistcheckval = '';

        //condition for keyword
      var  artkeyword = document.getElementById('search_keyword').value;

        openModal();
        $.ajax({
            url: '<?php echo SITE_URL; ?>getrecords_new.php', //the script to call to get data          
            data: {exhibition: "", utype: "Entire", cat: "", col: '', val: '', entire: 1, searchart: '', collection: "", exhibitionid: "", artistkeyword: artkeyword, artistcheck: artistcheckval, artstyles: artstyle, artcolor: artcolor, artistshipping: artshipping, artistcategory: artcategory, artistmedium: artmedium, artistsubject: artsubject, pricelist: artprice, pgnum: 0, recperpage: 27, nrecords: ""},
            dataType: 'json', //data format      
            success: function (data)          //on recieve of reply
            {
                
                document.location.href = "<?= SITE_URL; ?>art-search";
               closeModal();
            }
        });
        return false;
        
    }
    
          
    function searchByKeyword2() {
        
        var value = '';
        var artstyle = '';
        var artcolor = '';
        var artkeyword = '';
        var artsubject = '';
        var artshipping = '';
        var artcategory = '';
        var artmedium = '';
        var col = '';
        var colorShadeParam = '';
        //var colorshade = ''; 
        var artprice = '';
        var artistcheckval = '';

        //condition for keyword
      var  artkeyword = document.getElementById('search_keyword2').value;

        openModal();
        $.ajax({
            url: '<?php echo SITE_URL; ?>getrecords_new.php', //the script to call to get data          
            data: {exhibition: "", utype: "Entire", cat: "", col: '', val: '', entire: 1, searchart: '', collection: "", exhibitionid: "", artistkeyword: artkeyword, artistcheck: artistcheckval, artstyles: artstyle, artcolor: artcolor, artistshipping: artshipping, artistcategory: artcategory, artistmedium: artmedium, artistsubject: artsubject, pricelist: artprice, pgnum: 0, recperpage: 27, nrecords: ""},
            dataType: 'json', //data format      
            success: function (data)          //on recieve of reply
            {
                
                document.location.href = "<?= SITE_URL; ?>art-search";
               closeModal();
            }
        });
        return false;
        
    }
</script>
<?php 
            if(isset($_SESSION['usertype']) && $_SESSION['usertype'] == "Artist"){
   
         $artredirect = mysql_query('select * from register where id="'.$_SESSION["userid"].'"');
        if(mysql_num_rows($artredirect)>0)
        {
          $roart= mysql_fetch_array($artredirect);
       
             if($roart['txt_status']=="Unverified")
             {
                 if ($_REQUEST['pagename']=="artist-register-stepone.php" || $_REQUEST['pagename']=="artist-register-steptwo.php" || $_REQUEST['pagename']=="artist-register-stepthree.php" || $_REQUEST['pagename']=="artist-register-stepfour.php" || $_REQUEST['pagename']=="artist-register.php" || $_REQUEST['pagename']=="artist-register-success.php")
    {
       
    }else 
    {
         echo "<script>alert('Error: Please fill the registration details !'); window.location.href='".SITE_URL."register-stepone/".$unique_id."'</script>";
    }
                 
             }
       }
      
      
    
  }else if(isset($_COOKIE['txtuniqueid']) && !empty($_COOKIE['txtuniqueid'])){
      
         if ($_REQUEST['pagename']=="artist-register-stepone.php" || $_REQUEST['pagename']=="artist-register-steptwo.php" || $_REQUEST['pagename']=="artist-register-stepthree.php" || $_REQUEST['pagename']=="artist-register-stepfour.php" || $_REQUEST['pagename']=="artist-register.php" || $_REQUEST['pagename']=="artist-register-success.php")
    {
       
    }else 
    {
         echo "<script>alert('Error: Please fill the registration details !'); window.location.href='".SITE_URL."register-stepone/".$unique_id."'</script>";
    }
  }      
                    
                    
                     function generateFacebookShare($url, $title='', $caption='', $image_url, $description='', $redirect=''){

//url encode the necessary fields.
$title=urlencode($title);
$caption=urlencode($caption);
$description=urlencode($description);

if($redirect==''){
$redirect='https://www.facebook.com';
}

//create the link string
$finalString = 'https://www.facebook.com/dialog/feed?'.
'app_id=825196030899039&'.
'link='.$url.
        '&picture='.$image_url.
//($picture != '' ? ('&picture='.$image_url): '').
($title != '' ? ('&name='.$title) : '').
($caption != '' ? ('&caption='.$caption) : '').
($description != '' ? ('&description='.$description) : '').
'&redirect_uri='.$redirect;

return $finalString;
}
// Code to set the page name in the session so that when cart is empty then page redirect to the last session page//

//-----------------------to set login page--------------------//
if( (isset($_REQUEST['pname']) && !empty($_REQUEST['pname']) ) || (isset($_REQUEST['pagename']) && !empty($_REQUEST['pagename']) ) )
{  $pname='';
if( (isset($_REQUEST['pagename']) && $_REQUEST['pagename']=="artworks_new.php" ))
{
  
      $pname = $_REQUEST['pname']; 

}else if((isset($_REQUEST['pagename']) && $_REQUEST['pagename']=="artworks.php" )) {
    $exhibition = explode('/',$_SERVER['REQUEST_URI']);

      $pname = $exhibition[1].'/'.$exhibition[2];

    
}else if((isset($_REQUEST['pagename']) && $_REQUEST['pagename']=="whats-new.php" )) {
  
  
      $pname = 'whatsnew';

    
}else if((isset($_REQUEST['pagename']) && $_REQUEST['pagename']=="most_lover_art.php" )) {
  
 
      $pname = 'most-loved-art';
  
    
}
else if((isset($_REQUEST['pagename']) && $_REQUEST['pagename']=="artwork_details.php" )) {
  
 $artwork = explode('/',$_SERVER['REQUEST_URI']);
 
      $pname = $artwork[1].'/'.$artwork[2];
  
  
}else if((isset($_REQUEST['pagename']) && $_REQUEST['pagename']=="artwork-for-sale.php" )) {
  
 $saleartwork = explode('/',$_SERVER['REQUEST_URI']);
 
     $pname = $saleartwork[1].'/'.$saleartwork[2].'/'.$saleartwork[3];
   
 
}
else if((isset($_REQUEST['pagename']) && $_REQUEST['pagename']=="artistDetails.php" )) {
  
 $saleartwork = explode('/',$_SERVER['REQUEST_URI']);
 
     $pname = $saleartwork[1].'/'.$saleartwork[2].'/'.$saleartwork[3];
   
 
}
else if((isset($_REQUEST['pagename']) && $_REQUEST['pagename']=="artist-sold-artwork.php" )) {
  
 $saleartwork = explode('/',$_SERVER['REQUEST_URI']);
 
     $pname = $saleartwork[1].'/'.$saleartwork[2].'/'.$saleartwork[3];
   
 
}
else if((isset($_REQUEST['pagename']) && $_REQUEST['pagename']=="know-your-artist.php" )) {
  
 $saleartwork = explode('/',$_SERVER['REQUEST_URI']);
 
     $pname = 'know-your-artist';
   
 
}




}

                ?>
<script>

function twitter(u,s)
{
         window.open('https://twitter.com/share?url=' + encodeURIComponent(u) + '&text='+ encodeURIComponent(s), 'sharer', 'toolbar=0,status=0,width=626,height=436');
        return false;
}
function linkedin(u,s,d,i)
{
         window.open('https://www.linkedin.com/shareArticle?mini=true&url=' + encodeURIComponent(u) + '&title='+ encodeURIComponent(s)+'&summary='+encodeURIComponent('Click on the link to view this beautiful artwork - '+u)+'&source='+encodeURIComponent(u), 'sharer', 'toolbar=0,status=0,width=626,height=436');
        return false;
}

function pintrest(u,s,d)
{
         window.open('https://pinterest.com/pin/create/button/?url=' + encodeURIComponent(u) + '&media='+ encodeURIComponent(s)+'&description='+encodeURIComponent(d), 'sharer', 'toolbar=0,status=0,width=626,height=436');
        return false;
}
function googleplus(u,s,d,i)
{
         window.open('https://plus.google.com/share?url=' + encodeURIComponent(u) + '&title='+ encodeURIComponent(s)+'&description='+encodeURIComponent(d)+'&image='+encodeURIComponent(i), 'sharer', 'toolbar=0,status=0,width=626,height=436');
        return false;
}
function linkedinartist(u,s,d,i)
{
         window.open('https://www.linkedin.com/shareArticle?mini=true&url=' + encodeURIComponent(u) + '&title='+ encodeURIComponent(s)+'&summary='+encodeURIComponent(u)+'&source='+encodeURIComponent(u), 'sharer', 'toolbar=0,status=0,width=626,height=436');
        return false;
}

$('body').on('contextmenu', 'img', function(e){ return false; });



</script>
