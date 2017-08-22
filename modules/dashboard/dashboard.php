<?php

if(isset($_SESSION['user_detail']['user_group']) && $_SESSION['user_detail']['user_group']!='')
{
	if($_SESSION['user_detail']['user_group']=='3' || $_SESSION['user_detail']['user_group']=='4')
	{
		if(isset($_GET['verifyemail']) && isset($_GET['passkey']))
		{
			$db_obj->emailVerify();
		}
		
	}
	
    if($_SESSION['user_detail']['user_group']=='1')
    {
        include(PROJECT_ROOT."/modules/dashboard/view/suadmin.php");
    }
    else if($_SESSION['user_detail']['user_group']=='2')
    {
        include(PROJECT_ROOT."/modules/dashboard/view/admin.php");
    }
    else if($_SESSION['user_detail']['user_group']=='3')
    {
        include(PROJECT_ROOT."/modules/dashboard/view/subscriber.php");
    }
    else if($_SESSION['user_detail']['user_group']=='4')
    {
		if(isset($_REQUEST["gstr2"]) && ($_REQUEST["gstr2"]=="view"))
		{
			include(PROJECT_ROOT."/modules/dashboard/view/gstr2client.php");
		}
		else if(isset($_REQUEST["gstr3"]) && ($_REQUEST["gstr3"]=="view"))
		{
			include(PROJECT_ROOT."/modules/dashboard/view/gstr3client.php");
		}
		
		
		else
		{
        include(PROJECT_ROOT."/modules/dashboard/view/client.php");
		}
    }
	
	
} else {
	$db_obj->redirect(PROJECT_URL);
}

?>