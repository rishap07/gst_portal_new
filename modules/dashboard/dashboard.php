<?php
if(isset($_SESSION['user_detail']['user_group']) && $_SESSION['user_detail']['user_group']!='')
{
    if($_SESSION['user_detail']['user_group']=='1')
    {
        include(PROJECT_ROOT."/modules/dashboard/view/suadmin.php");
    }
    else if($_SESSION['user_detail']['user_group']=='2' || $_SESSION['user_detail']['user_group']=='3')
    {
        include(PROJECT_ROOT."/modules/dashboard/view/admin.php");
    }
    else if($_SESSION['user_detail']['user_group']=='4')
    {
        include(PROJECT_ROOT."/modules/dashboard/view/subscriber.php");
    }
    else if($_SESSION['user_detail']['user_group']=='5')
    {
        include(PROJECT_ROOT."/modules/dashboard/view/client.php");
    }
}
?>
