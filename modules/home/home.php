<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<script>   jQuery(document).ready(function ($) {

        var _SlideshowTransitions = [
            //Fade
            {$Duration: 1200, $Opacity: 2}
        ];

        var options = {
            $AutoPlay: true, //[Optional] Whether to auto play, to enable slideshow, this option must be set to true, default value is false
            $AutoPlaySteps: 1, //[Optional] Steps to go for each navigation request (this options applys only when slideshow disabled), the default value is 1
            $AutoPlayInterval: 3000, //[Optional] Interval (in milliseconds) to go for next slide since the previous stopped if the slider is auto playing, default value is 3000
            $PauseOnHover: 1, //[Optional] Whether to pause when mouse over if a slider is auto playing, 0 no pause, 1 pause for desktop, 2 pause for touch device, 3 pause for desktop and touch device, 4 freeze for desktop, 8 freeze for touch device, 12 freeze for desktop and touch device, default value is 1

            $ArrowKeyNavigation: true, //[Optional] Allows keyboard (arrow key) navigation or not, default value is false
            $SlideDuration: 500, //[Optional] Specifies default duration (swipe) for slide in milliseconds, default value is 500
            $MinDragOffsetToSlide: 20, //[Optional] Minimum drag offset to trigger slide , default value is 20
            //$SlideWidth: 600,                                 //[Optional] Width of every slide in pixels, default value is width of 'slides' container
            //$SlideHeight: 300,                                //[Optional] Height of every slide in pixels, default value is height of 'slides' container
            $SlideSpacing: 0, //[Optional] Space between each slide in pixels, default value is 0
            $DisplayPieces: 1, //[Optional] Number of pieces to display (the slideshow would be disabled if the value is set to greater than 1), the default value is 1
            $ParkingPosition: 0, //[Optional] The offset position to park slide (this options applys only when slideshow disabled), default value is 0.
            $UISearchMode: 1, //[Optional] The way (0 parellel, 1 recursive, default value is 1) to search UI components (slides container, loading screen, navigator container, arrow navigator container, thumbnail navigator container etc).
            $PlayOrientation: 1, //[Optional] Orientation to play slide (for auto play, navigation), 1 horizental, 2 vertical, 5 horizental reverse, 6 vertical reverse, default value is 1
            $DragOrientation: 3, //[Optional] Orientation to drag slide, 0 no drag, 1 horizental, 2 vertical, 3 either, default value is 1 (Note that the $DragOrientation should be the same as $PlayOrientation when $DisplayPieces is greater than 1, or parking position is not 0)

            $SlideshowOptions: {//[Optional] Options to specify and enable slideshow or not
                $Class: $JssorSlideshowRunner$, //[Required] Class to create instance of slideshow
                $Transitions: _SlideshowTransitions, //[Required] An array of slideshow transitions to play slideshow
                $TransitionsOrder: 1, //[Optional] The way to choose transition to play slide, 1 Sequence, 0 Random
                $ShowLink: true                                    //[Optional] Whether to bring slide link on top of the slider when slideshow is running, default value is false
            },
            $BulletNavigatorOptions: {//[Optional] Options to specify and enable navigator or not
                $Class: $JssorBulletNavigator$, //[Required] Class to create navigator instance
                $ChanceToShow: 2, //[Required] 0 Never, 1 Mouse Over, 2 Always
                $AutoCenter: 1, //[Optional] Auto center navigator in parent container, 0 None, 1 Horizontal, 2 Vertical, 3 Both, default value is 0
                $Steps: 1, //[Optional] Steps to go for each navigation request, default value is 1
                $Lanes: 1, //[Optional] Specify lanes to arrange items, default value is 1
                $SpacingX: 10, //[Optional] Horizontal space between each item in pixel, default value is 0
                $SpacingY: 10, //[Optional] Vertical space between each item in pixel, default value is 0
                $Orientation: 1                                 //[Optional] The orientation of the navigator, 1 horizontal, 2 vertical, default value is 1
            },
            $ArrowNavigatorOptions: {
                $Class: $JssorArrowNavigator$, //[Requried] Class to create arrow navigator instance
                $ChanceToShow: 2, //[Required] 0 Never, 1 Mouse Over, 2 Always
                $Steps: 1                                       //[Optional] Steps to go for each navigation request, default value is 1
            }
        };
        var jssor_slider1 = new $JssorSlider$("slider1_container", options);

        //responsive code begin
        //you can remove responsive code if you don't want the slider scales while window resizes
        function ScaleSlider() {
            var parentWidth = jssor_slider1.$Elmt.parentNode.clientWidth;
            if (parentWidth)
                jssor_slider1.$ScaleWidth(Math.min(parentWidth, 1000));
            else
                window.setTimeout(ScaleSlider, 30);
        }

        ScaleSlider();

        if (!navigator.userAgent.match(/(iPhone|iPod|iPad|BlackBerry|IEMobile)/)) {
            $(window).bind('resize', ScaleSlider);
        }


        //if (navigator.userAgent.match(/(iPhone|iPod|iPad)/)) {
        //    $(window).bind("orientationchange", ScaleSlider);
        //}
        //responsive code end
    });
	


</script> 
<link rel="stylesheet" type="text/css" href="css/testimonial-slider-style.css">
<!-- include jQuery library -->

<script type="text/javascript" src="script/jquery_002.js.download"></script>

<!-- include Cycle plugin -->

<script type="text/javascript" src="script/jquery.js.download"></script>

<script type="text/javascript">

$(document).ready(function() {

    $('#testimonials')

	<!--.before('<div id="nav">')--><!------for paging------->

	.cycle({

        fx: 'fade', // choose your transition type, ex: fade, scrollUp, scrollRight, shuffle

		pager:  '#nav'

     });

});

</script>
<!-- Jssor Slider Begin -->

 
<!-- You can move inline styles to css file or css block. -->
<div id="slider1_container" style="position: relative; top: 0px; left: 0px; width: 1000px; height: 372px; overflow: hidden; "> 

    <!-- Loading Screen -->
    <div u="loading" style="position: absolute; top: 0px; left: 0px;">
        <div style="filter: alpha(opacity=70); opacity:0.7; position: absolute; display: block; background-color: #000000; top: 0px; left: 0px;width: 100%;height:100%;"></div>
        <div style="position: absolute; display: block; background: url('/../img/loading.gif') no-repeat center center;top: 0px; left: 0px;width: 100%;height:100%;"></div>
    </div>

    <!-- Slides Container -->
    <div u="slides" style="cursor: move; position: absolute; left: 0px; top: 0px; width: 1000px; height: 340px; overflow: hidden;"> 

        <!--           <div>
                                                            <img u="image" src="/new_slider/newyear.jpg" />
                                                        </div> -->

        <!--<div><img u="image" src="images/Happy-New-Year-2017_07.jpg" /></div>-->
        <div><img u="image" src="images/infrastucture.jpg" /></div>
        <!--<div><img u="image" src="images/infrastucture_NEW.jpg" /></div>-->
        <div><a u="image" href="?page=internal&itemid=34"><img src="images/doc.jpg" /></a></div>
        <!--               <div>
                                                            <img u="image" src="/new_slider/banner4.jpg" />
                                                        </div> -->
        <div><img u="image" src="images/sarin_4.jpg" /></div>
    </div>

    <!-- Bullet Navigator Skin Begin -->
    <style>
        /* jssor slider bullet navigator skin 05 css */
        /*
        .jssorb05 div           (normal)
        .jssorb05 div:hover     (normal mouseover)
        .jssorb05 .av           (active)
        .jssorb05 .av:hover     (active mouseover)
        .jssorb05 .dn           (mousedown)
        */
        .jssorb05 div, .jssorb05 div:hover, .jssorb05 .av {
            background: url(new_slider/b05.png) no-repeat;
            overflow: hidden;
            cursor: pointer;
        }

        .jssorb05 div {
            background-position: -7px -7px;
        }

        .jssorb05 div:hover, .jssorb05 .av:hover {
            background-position: -37px -7px;
        }

        .jssorb05 .av {
            background-position: -67px -7px;
        }

        .jssorb05 .dn, .jssorb05 .dn:hover {
            background-position: -97px -7px;
        }
    </style>
    <!-- bullet navigator container -->
    <div u="navigator" class="jssorb05" style="position: absolute; bottom: 16px; right: 6px;"> 
        <!-- bullet navigator item prototype -->
        <div u="prototype" style="POSITION: absolute; WIDTH: 16px; HEIGHT: 16px;"></div>
    </div>
    <!-- Bullet Navigator Skin End --> 
    <!-- Arrow Navigator Skin Begin -->
    <style>
        /* jssor slider arrow navigator skin 12 css */
        /*
        .jssora12l              (normal)
        .jssora12r              (normal)
        .jssora12l:hover        (normal mouseover)
        .jssora12r:hover        (normal mouseover)
        .jssora12ldn            (mousedown)
        .jssora12rdn            (mousedown)
        */
        .jssora12l, .jssora12r, .jssora12ldn, .jssora12rdn {
            position: absolute;
            cursor: pointer;
            display: block;
            background: url(images/a12.png) no-repeat;
            overflow: hidden;
        }

        .jssora12l {
            background-position: -16px -37px;
        }

        .jssora12r {
            background-position: -75px -37px;
        }

        .jssora12l:hover {
            background-position: -136px -37px;
        }

        .jssora12r:hover {
            background-position: -195px -37px;
        }

        .jssora12ldn {
            background-position: -256px -37px;
        }

        .jssora12rdn {
            background-position: -315px -37px;
        }
    </style>
    <!-- Arrow Left --> 
    <span u="arrowleft" class="jssora12l" style="width: 30px; height: 46px; top: 123px; left: 0px;"></span> 
    <!-- Arrow Right --> 
    <span u="arrowright" class="jssora12r" style="width: 30px; height: 46px; top: 123px; right: 0px"></span> 
    <!-- Arrow Navigator Skin End --> 
    <a style="display: none" href="http://www.jssor.com">jquery slider plugin</a></div>
<!-- Jssor Slider End --> 

<!--Banner--> 
</div>
</div>
</div>
<div class="mainwrapper">
    <div id="wrapper_main">
        <div id="wrapper_main_inner" style="background:none;">

            <!--<div id="top_manu">
          <ul class="menu"><li class="level1"><a href="/index.php?option=com_content&amp;view=article&amp;id=115&amp;Itemid=83" class="level1 topdaddy"><span>Emergency Contact</span></a></li><li class="level1"><a href="/index.php?option=com_content&amp;view=section&amp;id=15&amp;Itemid=81" class="level1 topdaddy"><span>News &amp; Events</span></a></li><li class="level1"><a href="/index.php?option=com_content&amp;view=article&amp;id=100&amp;Itemid=84" class="level1 topdaddy"><span>Maps &amp; Direction</span></a></li><li class="level1"><a href="/index.php?option=com_content&amp;view=article&amp;id=134&amp;Itemid=153" class="level1 topdaddy"><span>Appointments</span></a></li><li class="level1"><a href="images/stock_pharmacy.pdf" class="level1 topdaddy"><span>Hosp. Inventory</span></a></li></ul>
          <div id="top_contact">
            
      
          </div>
          <a href="/index.php?option=com_content&amp;view=article&amp;id=123&amp;Itemid=137"><div id="tender_bt"></div></a>
        </div>-->

            <div id="wrapper_top" class="clearfix" >
                <!--<div style="width:140px; height:15px; float:right; padding:5px 10px 0 0; text-align:right;">
              
            </div>-->

                <div id="wrapper_top_content">
                  <div style="color:#00FF00; float:left;"><!--<a href="#"><img src="images/nabh.png" width="58" height="58" /></a>--></div>
                </div>
            </div>

            <!--<div id="ilbs_header" class="clearfix">
          
      
              
              
              
        <object width="990" height="261" type="application/x-shockwave-flash" data="images/home.swf">
      <param value="images/home.swf" name="movie" /> 	
      <param name="quality" value="high" /> 
      <param name="wmode" value="transparent" />
      
      <img height="260" width="990" alt="Home Flash Banner Image" src="images/home.jpg">			
      </object>
            
              
              
              
              
        </div>-->
            <div id="wrapper_content" >
                <div id="left">
                    <div id="left_1">
                        <div id="left_2"></div>
                    </div>
                </div>
                <div id="component-left" style="width:990px;"></div>
            </div>
            <!--<h3 id="bottom_wrapper_header"></h3>-->
            <div id="wrapper_bottom2" class="home_box" style=" border-bottom:2px solid #dcdcd7; position:relative; margin-bottom:15px; width:97%; float:left; " >
                <div class="us_width-33" id="ani_tab">
                    <div class="moduletable">
                        <div class="moduletable_content">
                            <h3 class="headstyle"><img src="images/patient_care.png">Patient Care</h3>
                            <span style="color:#d23001; line-height:20px;">The Institute of Liver and Biliary Sciences</span><br>
                            <p style="text-align: justify">The mission of Team ILBS is “to serve as a torch-bearer model of health care in the country by amalgamating the skills and structure of academic universities, clinical and research acumen of the super specialists and the managerial skills of the corporate world.” Many visitors to ILBS are pleased by the professional look and feel of the hospital. They encounter smiling staff at the front desk, neat clean and well maintained facilities, kind and...</p>
                            <div class="readmore" style="margin-top:20px;"><a href="?page=internal&itemid=3">read more</a></div>
                        </div>
                    </div>
                    <div class="moduletable">
                        <div class="moduletable_content">
                            <div id="slider1" class="
                                 sliderwrapper">
                                <div class="contentdiv">
                                    <h3 class="scroll_head">Liver</h3>
                                    <p><strong>Liver (State of the art facility available for)</strong></p>
                                    <ol>
                                        <li>Cirrhosis</li>
                                        <li>Chronic Hepatitis B</li>
                                        <li>Chronic Hepatitis C</li>
                                        <li>Fatty Liver</li>
                                        <li>Liver Coma</li>
                                        <li>GI Bleed</li>
                                        <li>Jaundice<br />
                                        <li>Hepatocellular Carcinoma<br />
                                        </li>
                                        </li>
                                    </ol>
                                </div>
                                <div class="contentdiv">
                                    <h3 class="scroll_head">Pancreas</h3>
                                    <p><strong>PANCREAS (State of the art facility available for)</strong></p>
                                    <ol>
                                        <li>Acute Pancreatitis</li>
                                        <li>Chronic Pancreatitis</li>
                                        <li>Pancreatic Stones</li>
                                        <li>Pancreatic Cancer<br />
                                        </li>
                                    </ol>
                                </div>
                                <div class="contentdiv">
                                    <h3 class="scroll_head">Gall bladder</h3>
                                    <p><strong>GALL BLADDER (State of the art facility available for)</strong></p>
                                    <ol>
                                        <li>Gall Bladder Stones</li>
                                        <li>Bile Duct Stones/Cholelithiasis/Cholecystitis</li>
                                        <li>Gall Bladder Cancer</li>
                                        <li>Obstructive Jaundice</li>
                                        <li>Biliary Atresia</li>
                                    </ol>
                                </div>
                                <div class="contentdiv">
                                    <h3 class="scroll_head">Others</h3>
                                    <p><strong>Speciality (State of the art facility available for)</strong></p>
                                    <ol>
                                        <li><a href="?page=content&itemid=304" style="color:#2a2a2a; text-decoration:none;">Neurology Services</a></li>
                                        <li><a href="?page=internal&itemid=40" style="color:#2a2a2a; text-decoration:none;">Cardiology Services</a></li>
                                        <li><a href="?page=internal&itemid=39" style="color:#2a2a2a; text-decoration:none;">Nephrology Services</a></li>
                                        <li><a href="?page=content&itemid=94" style="color:#2a2a2a; text-decoration:none;">Pulmonary Medicine</a></li>
                                        <li><a href="?page=internal&itemid=107" style="color:#2a2a2a; text-decoration:none;">Bariatric Surgery</a></li>
                                        <li><a href="?page=internal&itemid=41" style="color:#2a2a2a; text-decoration:none;">Oncology Services</a></li>
                                    </ol>
                                </div>
                            </div>
                            <div id="paginate-slider1" class="pagination"></div>
                            <script type="text/javascript">

                                featuredcontentslider.init({
                                    id: "slider1", //id of main slider DIV
                                    contentsource: ["inline", ""], //Valid values: ["inline", ""] or ["ajax", "path_to_file"]
                                    toc: "#increment", //Valid values: "#increment", "markup", ["label1", "label2", etc]
                                    nextprev: ["Previous", "Next"], //labels for "prev" and "next" links. Set to "" to hide.
                                    revealtype: "click", //Behavior of pagination links to reveal the slides: "click" or "mouseover"
                                    enablefade: [true, 0.2], //[true/false, fadedegree]
                                    autorotate: [true, 3000], //[true/false, pausetime]
                                    onChange: function (previndex, curindex, contentdivs) {  //event handler fired whenever script changes slide
                                        //previndex holds index of last slide viewed b4 current (0=1st slide, 1=2nd etc)
                                        //curindex holds index of currently shown slide (0=1st slide, 1=2nd etc)
                                    }
                                })

                            </script></div>
                    </div>
                </div>
                <div class="us_width-33" id="news_right">
                    <div class="moduletable">
                        <div class="moduletable_content">
                            <h3 class="headstyle"><img src="images/academics.png">Academics</h3>
                            <span style="color:#d23001; line-height:20px;">The Institute of Liver & Biliary Sciences</span><br>
                            <span style="text-align: justify">has been granted Deemed-to-be-University status by the University Grants Commission (UGC) under Section 3 of the UGC Act, 1956 under de-novo category through the Ministry of Human Resource Development, Government of India.   ILBS is a<strong><em>‘de-novo institute with a promise for excellence’</em></strong>in Hepatobiliary Medicine, Surgery and Research and is a unique institute that is dedicated to patient management, teaching...
                                <div class="readmore" style="margin-top:20px;"><a href="?page=internal&itemid=6">read more</a></div>
                        </div>
                    </div>
                    <div class="moduletable">
                        <h3><span>Announcements</span></h3>
                        <div class="moduletable_content">
                            <div class="announc">
                                <marquee onMouseOver="this.stop();" onMouseOut="this.start();" style="height: 233px;" direction="up" scrolldelay="500" height="233">
                                    
                                    <ul class="announcement-list">
                                        <?php
                                        $dataAnnos = $db_obj->findAll(TAB_PREFIX."announcement","status='0' and is_deleted='0' and published_date<='".date('Y-m-d')."' and unpublished_date >= '".date('Y-m-d')."'",'','','published_date desc');
                                        if(!empty($dataAnnos))
                                        {
                                            foreach($dataAnnos as $dataAnno)
                                            {
                                                if($dataAnno->type=='0')
                                                {
                                                    ?>
                                                    <li><span class="circle">&nbsp;</span><strong><?php echo $dataAnno->title;?></strong></li>
                                                    <?php
                                                }
                                                else
                                                {
                                                    $url = '';
                                                    if($dataAnno->type=='2')
                                                    {
                                                        $url = PROJECT_URL."?page=content&itemid=".$dataAnno->details;
                                                    }
                                                    else if($dataAnno->type=='4')
                                                    {
                                                        $url = $dataAnno->details;
                                                    }
                                                    else if($dataAnno->type=='5')
                                                    {
                                                        $url = PROJECT_URL.$dataAnno->details;
                                                    }
                                                    else if($dataAnno->type=='6')
                                                    {
                                                        $url = PROJECT_URL."/images/announcement/".$dataAnno->details;
                                                    }
                                                    ?>
                                                    <li><span class="circle">&nbsp;</span><strong><a href="<?php echo $url; ?>" target="_blank"><span style="float:left; width:90%"><?php echo $dataAnno->title; ?></span><?php if ($dataAnno->menu_newicon == 'new') { echo '<img src="' . PROJECT_URL . '/images/new_ann_image.gif" height="15" style="float: right; width:10%;">';}
                               ?></a></strong></li>
                                                    <?php
                                                }
                                                ?>
                                                <?php
                                            }
                                        }
                                        ?>
                                        
                                        </ul>
                                    <strong><strong><br />
                                        </strong></strong>
                                </marquee>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="us_width-33">
                    <div class="moduletable">
                        <div class="moduletable_content">
                            <h3 class="headstyle"><img src="images/r_d.png">Research and Development</h3>
                            <span style="color:#d23001; line-height:20px;">The Institute of Liver & Biliary Sciences</span><br>
                            <span style="text-align: justify">is unique in nurturing a vibrant research culture in a clinical setting to ridge the gap between the bench and bedside. To fully understand the diseases that affect the “hepato-pancreato-billiary highway” it is imperative to have cutting edge technologies established at the institute in order to test hypothesis and implement ideas in a multi-disciplinary format. In this regard a major thrust areas of the department includes Genomics and...</span>
                            <div class="readmore" style="margin-top:20px;"><a href="?page=content&depid=23&itemid=102">read more</a></div>
                        </div>
                    </div>
                    <div class="moduletable">
                        <h3><span>Testimonials</span></h3>
                        <div class="moduletable_content testimonialcontent">
                          <!--<iframe width="289px" height="150" frameborder="0" src="http://delhi.iridiuminteractive.in/ilbs/testimonil_slider/" scrolling="no">
            
            </iframe>-->
<!--            				===============testimonial start==================-->
            <img src="images/quot1.png" style="z-index:9">

<div style="position: relative; width: 290px; height: 74px; margin:-15px 0 0 15px;" id="testimonials">

<?php
                            $testimonial_obj = new testimonial();
                            $testimonialLatestDatas = $testimonial_obj->lastestTestimonial();
							
                            ?>
                         
                                <?php
                                if(!empty($testimonialLatestDatas))
                                {
                                    foreach($testimonialLatestDatas as $testimonialLatestData)
                                    {
                                    ?>
                                    <blockquote style="position: absolute; top: 0px; left: 0px;  z-index: 7; opacity: 0; width: 240px; height: 74px;">
                  <p><?php echo isset($testimonialLatestData->description) ? substr($testimonialLatestData->description,0,300) : ''; ?>
<img src="images/quot2.png" style="float:right;">
                <cite>- <?php echo isset($testimonialLatestData->testimonial_by) ? $testimonialLatestData->testimonial_by : ''; ?></cite></p><div class="readmore" style="margin-top:10px;"><a href="?page=testimonial">read more</a></div></blockquote>
                
                <?php
                                    }
                                }
                                ?>


</div>



           

<!--  ===========================testimonial over==============================-->
				
                            
                             
                          
                            
                        </div>
                    </div>
                </div>
            </div>
            <br clear="all" />
            <!-- <div style="width:971px; margin:0 auto;">
      
      <div class="home_left" style="float:left; width:227px;"></div>
      <div class="home_middle" style="width:436px; margin-left:20px; float:left;"></div>
      <div class="home_right" style="float:right; margin-right:20px; width:264px;"></div>
      
      </div> -->
        </div>
        <div style="width:769px; float:left; margin-bottom:20px; margin-left:24px; ">
            <iframe width="769px" height="236" frameborder="0"  src="<?php echo PROJECT_URL;?>/slider_home/" scrolling="no"></iframe>
        </div>
        <div class="photo_video">
        <div class="home_gallery"><a href="https://play.google.com/store/apps/details?id=com.wiprohis.ilbs" target="_blank" style="color:#2a2a2a; text-decoration:none;"><img src="images/yakrit.gif" style="margin-top:-7px; margin-bottom: 5px;"></a></div>
            <div class="home_gallery"><a href="https://play.google.com/store/apps/details?id=in.ilbs.calculator&hl=en" target="_blank" style="color:#2a2a2a; text-decoration:none;"><img src="images/ilap.gif" style="margin-top:-7px; margin-bottom: 15px;"></a></div>
            <div class="home_gallery"><a href="?page=gallery_image" style="color:#2a2a2a; text-decoration:none;"><img src="images/photo_gallery.png" style="margin-top:-10px;">Photo Gallery</a></div>
            <div class="home_gallery"><a href="?page=video_gallery" style="color:#2a2a2a; text-decoration:none;"><img src="images/video.png" style="margin-top:-16px;">Videos</a></div>
        </div>
    </div>
