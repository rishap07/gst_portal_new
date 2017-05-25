<?php
$obj_plan = new plan();
if( !isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '' ) {
    $obj_plan->redirect(PROJECT_URL);
    exit();
}
?>

<!--========================sidemenu over=========================-->
<div class="admincontainer greybg">
    <div class="formcontainer">
        
        <h1>Choose Plan</h1>
        <hr class="headingborder">
        <h2 class="greyheading">Available Plan Listing</h2>
        
        <div class="adminformbx">
            
            <div id="tabs">
                <ul>
                  <li><a href="#tabs-1">Monthly</a></li>
                  <li><a href="#tabs-2">Quarterly</a></li>
                  <li><a href="#tabs-3">Half Yearly</a></li>
                  <li><a href="#tabs-4">Yearly</a></li>
                </ul>
                <div id="tabs-1">
                    
                    <div class="columns">
                        <ul class="price">
                            <li class="header">Basic</li>
                            <li class="grey">$ 9.99 / year</li>
                            <li>10GB Storage</li>
                            <li>10 Emails</li>
                            <li>10 Domains</li>
                            <li>1GB Bandwidth</li>
                            <li class="grey"><a href="#" class="button">Sign Up</a></li>
                        </ul>
                    </div>

                    <div class="columns">
                        <ul class="price">
                            <li class="header" style="background-color:#4CAF50">Pro</li>
                            <li class="grey">$ 24.99 / year</li>
                            <li>25GB Storage</li>
                            <li>25 Emails</li>
                            <li>25 Domains</li>
                            <li>2GB Bandwidth</li>
                            <li class="grey"><a href="#" class="button">Sign Up</a></li>
                        </ul>
                    </div>

                    <div class="columns">
                        <ul class="price">
                            <li class="header">Premium</li>
                            <li class="grey">$ 49.99 / year</li>
                            <li>50GB Storage</li>
                            <li>50 Emails</li>
                            <li>50 Domains</li>
                            <li>5GB Bandwidth</li>
                            <li class="grey"><a href="#" class="button">Sign Up</a></li>
                        </ul>
                    </div>
                    
                    <div class="columns">
                        <ul class="price">
                            <li class="header">Premium</li>
                            <li class="grey">$ 49.99 / year</li>
                            <li>50GB Storage</li>
                            <li>50 Emails</li>
                            <li>50 Domains</li>
                            <li>5GB Bandwidth</li>
                            <li class="grey"><a href="#" class="button">Sign Up</a></li>
                        </ul>
                    </div>
                    
                    <div class="clear"></div>
                    
                </div>
                <div id="tabs-2">
                  
                    <div class="columns">
                        <ul class="price">
                            <li class="header">Basic</li>
                            <li class="grey">$ 9.99 / year</li>
                            <li>10GB Storage</li>
                            <li>10 Emails</li>
                            <li>10 Domains</li>
                            <li>1GB Bandwidth</li>
                            <li class="grey"><a href="#" class="button">Sign Up</a></li>
                        </ul>
                    </div>

                    <div class="columns">
                        <ul class="price">
                            <li class="header" style="background-color:#4CAF50">Pro</li>
                            <li class="grey">$ 24.99 / year</li>
                            <li>25GB Storage</li>
                            <li>25 Emails</li>
                            <li>25 Domains</li>
                            <li>2GB Bandwidth</li>
                            <li class="grey"><a href="#" class="button">Sign Up</a></li>
                        </ul>
                    </div>

                    <div class="columns">
                        <ul class="price">
                            <li class="header">Premium</li>
                            <li class="grey">$ 49.99 / year</li>
                            <li>50GB Storage</li>
                            <li>50 Emails</li>
                            <li>50 Domains</li>
                            <li>5GB Bandwidth</li>
                            <li class="grey"><a href="#" class="button">Sign Up</a></li>
                        </ul>
                    </div>
                    
                    <div class="columns">
                        <ul class="price">
                            <li class="header">Premium</li>
                            <li class="grey">$ 49.99 / year</li>
                            <li>50GB Storage</li>
                            <li>50 Emails</li>
                            <li>50 Domains</li>
                            <li>5GB Bandwidth</li>
                            <li class="grey"><a href="#" class="button">Sign Up</a></li>
                        </ul>
                    </div>
                    
                    <div class="clear"></div>
                    
                </div>
                <div id="tabs-3">
                    
                    <div class="columns">
                        <ul class="price">
                            <li class="header">Basic</li>
                            <li class="grey">$ 9.99 / year</li>
                            <li>10GB Storage</li>
                            <li>10 Emails</li>
                            <li>10 Domains</li>
                            <li>1GB Bandwidth</li>
                            <li class="grey"><a href="#" class="button">Sign Up</a></li>
                        </ul>
                    </div>

                    <div class="columns">
                        <ul class="price">
                            <li class="header" style="background-color:#4CAF50">Pro</li>
                            <li class="grey">$ 24.99 / year</li>
                            <li>25GB Storage</li>
                            <li>25 Emails</li>
                            <li>25 Domains</li>
                            <li>2GB Bandwidth</li>
                            <li class="grey"><a href="#" class="button">Sign Up</a></li>
                        </ul>
                    </div>

                    <div class="columns">
                        <ul class="price">
                            <li class="header">Premium</li>
                            <li class="grey">$ 49.99 / year</li>
                            <li>50GB Storage</li>
                            <li>50 Emails</li>
                            <li>50 Domains</li>
                            <li>5GB Bandwidth</li>
                            <li class="grey"><a href="#" class="button">Sign Up</a></li>
                        </ul>
                    </div>
                    
                    <div class="columns">
                        <ul class="price">
                            <li class="header">Premium</li>
                            <li class="grey">$ 49.99 / year</li>
                            <li>50GB Storage</li>
                            <li>50 Emails</li>
                            <li>50 Domains</li>
                            <li>5GB Bandwidth</li>
                            <li class="grey"><a href="#" class="button">Sign Up</a></li>
                        </ul>
                    </div>
                    
                    <div class="clear"></div>
                </div>
                <div id="tabs-4">
                    
                    <div class="columns">
                        <ul class="price">
                            <li class="header">Basic</li>
                            <li class="grey">$ 9.99 / year</li>
                            <li>10GB Storage</li>
                            <li>10 Emails</li>
                            <li>10 Domains</li>
                            <li>1GB Bandwidth</li>
                            <li class="grey"><a href="#" class="button">Sign Up</a></li>
                        </ul>
                    </div>

                    <div class="columns">
                        <ul class="price">
                            <li class="header" style="background-color:#4CAF50">Pro</li>
                            <li class="grey">$ 24.99 / year</li>
                            <li>25GB Storage</li>
                            <li>25 Emails</li>
                            <li>25 Domains</li>
                            <li>2GB Bandwidth</li>
                            <li class="grey"><a href="#" class="button">Sign Up</a></li>
                        </ul>
                    </div>

                    <div class="columns">
                        <ul class="price">
                            <li class="header">Premium</li>
                            <li class="grey">$ 49.99 / year</li>
                            <li>50GB Storage</li>
                            <li>50 Emails</li>
                            <li>50 Domains</li>
                            <li>5GB Bandwidth</li>
                            <li class="grey"><a href="#" class="button">Sign Up</a></li>
                        </ul>
                    </div>
                    
                    <div class="columns">
                        <ul class="price">
                            <li class="header">Premium</li>
                            <li class="grey">$ 49.99 / year</li>
                            <li>50GB Storage</li>
                            <li>50 Emails</li>
                            <li>50 Domains</li>
                            <li>5GB Bandwidth</li>
                            <li class="grey"><a href="#" class="button">Sign Up</a></li>
                        </ul>
                    </div>
                    
                    <div class="clear"></div>
                </div>
              </div>
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
        </div>
<!--========================adminformbox over=========================-->    
    </div>
<!--========================admincontainer over=========================-->
</div>
<script>
    $(document).ready(function () {
        $( "#tabs" ).tabs();
    });
</script>