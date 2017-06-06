<?php
/*
 * 
 *  Developed By        :   Rishap Gandhi
 *  Description         :   A simple class for page validation to be used throughout the project 
 *  Date Created        :   May 18, 2017
 *  Last Modified       :   May 18, 2017
 *  Last Modified By    :   Rishap Gandhi
 *  Last Modification   :   file creation started
 * 
 */

class validation extends upload {
    
    protected $tableNames = array();
    public $allowImageExt = array('image/gif', 'image/jpeg', 'image/jpg', 'image/png');

    public function __construct() {

        parent::__construct();
        
        $this->tableNames = array(
            'state' => TAB_PREFIX . 'state',
            'user' => TAB_PREFIX . 'user',
            'user_group' => TAB_PREFIX . 'user_group',
            'user_subscribed_plan' => TAB_PREFIX . 'user_subscribed_plan',
            'preserve_user' => TAB_PREFIX . 'user_preserve',
            'subscriber_plan_category' => TAB_PREFIX . 'subscriber_plan_category',
            'subscriber_plan' => TAB_PREFIX . 'subscriber_plan',
            'state' => TAB_PREFIX . 'master_state',
            'receiver' => TAB_PREFIX . 'master_receiver',
            'supplier' => TAB_PREFIX . 'master_supplier',
            'item' => TAB_PREFIX . 'master_item',
            'user_role' => TAB_PREFIX . 'user_role',
            'user_group' => TAB_PREFIX . 'user_group',
            'user_theme_setting' => TAB_PREFIX . 'user_theme_setting',
            'client_kyc' => TAB_PREFIX . 'client_kyc',
            'client_gstin_detail' => TAB_PREFIX . 'client_gstin_detail',
            'user_role_permission' => TAB_PREFIX . 'user_role_permission',
            'client_master_item' => TAB_PREFIX . 'client_master_item',
            'unit' => TAB_PREFIX . 'master_unit',
            'api' => TAB_PREFIX . 'api'
        );
        
        $this->checkUserAccess();
    }
    
    /*[a-zA-Z\d]+[(_{1}\-{1}\.{1})][a-zA-Z\d]*/
    protected $validateType = array(
        "username" => "[a-zA-Z\d]+[(_{1}\-{1}\.{1})|(a-zA-Z\d)][a-zA-Z\d]",
        "alphanumeric" => "A-Za-z0-9\n\r\&\/\-\(\)\,\.",
        "mobilenumber" => "\d{10}",
        "content" => "^\\\"<>|",
        "pincode" => "\d{6}",
        "yearmonth" => "[0-9]{4}-(0[1-9]|1[0-2])",
        "datetime" => "[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) (2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]",
        "alphaspace" => "a-zA-Z\s",
        "integergreaterzero" => "[1-9][0-9]",
        "decimalgreaterzero" => "\s*(?=.*[1-9])\d*(?:\.\d{1,2})?\s",
        "pancard" => "(([A-Z]){5}([0-9]){4}([A-Z]){1})",
        "onlyzeroone" => "01"
    );

    protected $validationMessage = array(
        'mandatory'=> 'Fill all mandatory fields',
        'failed' => "Some error try again to submit.",
        'loginerror' => 'Username or Password Incorrect.',
        'passwordnotmatched' => 'Password not matched.',
        'usernameexist' => 'Username already exist.',
        'emailexist' => 'Email already exist.',
        'companycodeexist' => 'Company Code already exist.',
        'usernotexist' => 'User not exist.',
        'userexist' => 'User already exist.',
        'useradded' => 'User added successfully.',
        'useredited' => 'User updated successfully.',
        'profileupdated' => 'Profile updated successfully.',
        'userdeleted' => 'User deleted successfully.',
        'categoryexist' => 'Category exist.',
        'nocategoryexist' => 'Category doesn\'t exist.',
        'plancategoryadd' => 'Plan Category added successfully.',
        'plancategoryedit' => 'Plan Category edited successfully.',
        'plancategorydelete' => 'Plan Category deleted successfully.',
        'planadd' => 'Plan added successfully.',
        'planedit' => 'Plan edited successfully.',
        'plandelete' => 'Plan deleted successfully.',
        'planexist' => 'Plan exist.',
        'noplanexist' => 'Plan doesn\'t exist.',
        'plansubscribed' => 'Plan subscribed successfully.',
        'enablecookie' => 'Please enable your cookies.',
        'apiDataBlank' => 'Enter all mandatory fields.',
        'invalidHashCode' => 'Invalid Hash Code generated.',
        'api' => 'Invalid API access.',
        'cookie_err' => 'Kindly enable cookie and session on browser.',
        'kycupdated' => 'KYC updated successfully.',
        'gstinexist' => 'This GSTIN number already associated with another client.',
        'gstinupdated' => 'GSTIN number updated successfully.',
        'themesettingsaved' => 'Theme setting saved successfully.',
        'iteminserted' => 'Item Inserted Successfully',
        'itemupdated' => 'Item Updated Successfully',
        'itemdeleted' => 'Item Deleted Successfully',
        'can_read' => 'You are not authorised to access this module',
        'can_create' => 'You are not authorised to add in this module',
        'can_update' => 'You are not authorised to update in this module',
        'can_delete' => 'You are not authorised to delete this module',
        'update' => 'Updated Successfully',
        'inserted' => 'Added Successfully',
        'statecodeexist' => 'State code already exist.',
        'unitcodeexist' => 'Unit code already exist.'
    );
    
    public function getTableName($tablename)
    {
        return $this->tableNames[$tablename];
    }
    
    public function getValMsg($msg)
    {
        return $this->validationMessage[$msg];
    }
    
    public function checkUserAccess() {
        
        if( isset($_SESSION['user_detail']['user_id']) && $_SESSION['user_detail']['user_id'] != '' ) {
        
            $currentUserDetails = $this->getUserDetailsById( $_SESSION['user_detail']['user_id'] );
            if($currentUserDetails['data']->user_group == 3) {

                if( isset($_GET['page']) && $_GET['page'] != "plan_chooseplan") {

                    if($currentUserDetails['data']->plan_id == 0) {
                        $this->redirect(PROJECT_URL . "?page=plan_chooseplan");
                    }
                }
            } else if($currentUserDetails['data']->user_group == 4) {

                if( isset($_GET['page']) && $_GET['page'] != "user_clientkycupdate") {

                    if($currentUserDetails['data']->kyc == '') {
                        $this->redirect(PROJECT_URL . "?page=user_clientkycupdate");
                    }
                }
            }
        }
    }
    
    public function getAdmin($is_deleted='0',$orderby='user_id desc',$limit='')
    {
        $query = "select user_id,first_name, last_name,user_group,username, (case when payment_status='0' Then 'pending' when  payment_status='1' then 'accepted' when  payment_status='2' then 'mark as fraud' when  payment_status='3' then 'rejected' when  payment_status='4' then 'refunded' end) as payment_status from ".$this->tableNames['user']." where  is_deleted='".$is_deleted."' and user_group='2' order by ".$orderby." ".$limit;
        return $this->get_results($query);
    }
    
    public function getClient($field = "*",$condition='',$orderby='user_id desc',$limit='',$group_by='')
    {
        $query = "select ".$field."  from ".$this->tableNames['user']." where 1=1 ";
        if($condition !='')
        {
            $query .= " and ".$condition;
        }
        if($group_by !='')
        {
            $query .= " group by ".$group_by;
        }
        $query .= " order by ".$orderby." ".$limit;        
        return $this->get_results($query);
    }
    
    public function getPlanCategory($field = "*",$condition='',$orderby='id asc',$limit='',$group_by='')
    {
        $query = "select ".$field."  from ".$this->tableNames['subscriber_plan_category']." where 1=1 ";
        if($condition !='')
        {
            $query .= " and ".$condition;
        }
        if($group_by !='')
        {
            $query .= " group by ".$group_by;
        }
        $query .= " order by ".$orderby." ".$limit;
        return $this->get_results($query);
    }
    
    public function getPlan($field = "*",$condition='',$orderby='id desc',$limit='',$group_by='')
    {
        $query = "select ".$field."  from ".$this->tableNames['subscriber_plan']." where 1=1 ";
        if($condition !='')
        {
            $query .= " and ".$condition;
        }
        if($group_by !='')
        {
            $query .= " group by ".$group_by;
        }
        $query .= " order by ".$orderby." ".$limit;
        return $this->get_results($query);
    }
    
    public function getAllActivePlanSuAdmin($field='*',$condition='',$orderby='',$limit='',$group_by='')
    {
        $query = "select ".$field."  from ".$this->tableNames['subscriber_plan']." p join ".$this->tableNames['subscriber_plan_category']." c join ".$this->tableNames['user']." u on p.added_by=u.user_id where p.plan_category=c.id ";
        if($condition !='')
        {
            $query .= " and ".$condition;
        }
        if($group_by !='')
        {
            $query .= " group by ".$group_by;
        }
        $query .= " order by ".$orderby." ".$limit;
        
        return $this->get_results($query);
    }
    
    public function getSubscriber($is_deleted='0',$orderby='user_id desc',$limit='') {
        
        $query = "select user_id, first_name, last_name, user_group, username, (case when payment_status='0' Then 'pending' when  payment_status='1' then 'accepted' when  payment_status='2' then 'mark as fraud' when  payment_status='3' then 'rejected' when  payment_status='4' then 'refunded' end) as payment_status from ".$this->tableNames['user']." where  is_deleted='".$is_deleted."' and user_group='3' and added_by='".$_SESSION['user_detail']['user_id']."' order by ".$orderby." ".$limit;
        return $this->get_results($query);
    }
}