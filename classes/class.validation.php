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
    public function __construct() {
        parent::__construct();
        $this->tableNames = array(
            'state' => TAB_PREFIX . 'state',
            'user' => TAB_PREFIX . 'user',
            'user_group' => TAB_PREFIX . 'user_group',
            'preserve_user' => TAB_PREFIX . 'user_preserve',
            'subscriber_plan_category' => TAB_PREFIX . 'subscriber_plan_category',
            'subscriber_plan' => TAB_PREFIX . 'subscriber_plan',
            'state' => TAB_PREFIX . 'master_state',
            'receiver' => TAB_PREFIX . 'master_receiver',
            'supplier' => TAB_PREFIX . 'master_supplier',
            'item' => TAB_PREFIX . 'master_item',
        );
    }

    protected $validateType = array(
        "username" => "[a-zA-Z\d]+[(_{1}\-{1}\.{1})][a-zA-Z\d]",
        "alphanumeric" => "A-Za-z0-9\n\r\&\/\-\(\)\,\.",
        "mobilenumber" => "\d{10}",
        "content" => "^\\\"<>|",
        "pincode" => "\d{6}",
        "yearmonth" => "[0-9]{4}-(0[1-9]|1[0-2])",
        "datetime" => "[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) (2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]",
        "alphaspace"=>"a-zA-Z\s",
        "integergreaterzero"=>"[1-9][0-9]",
        "decimalgreaterzero"=>"\s*(?=.*[1-9])\d*(?:\.\d{1,2})?\s",
        "onlyzeroone"=>"01"
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
        'enablecookie' => 'Please enable your cookies.',
        'apiDataBlank' => 'Enter all mandatory fields.',
        'invalidHashCode' => 'Invalid Hash Code generated.',
        'api' => 'Invalid API access.',
        'cookie_err' => 'Kindly enable cookie and session on browser.'
    );
    
    public function getTableName($tablename)
    {
        return $this->tableNames[$tablename];
    }
    
    public function getAdmin($is_deleted='0',$orderby='user_id desc',$limit='')
    {
        $query = "select user_id,first_name, last_name,user_group,username, (case when payment_status='0' Then 'pending' when  payment_status='1' then 'accepted' when  payment_status='2' then 'mark as fraud' when  payment_status='3' then 'rejected' when  payment_status='4' then 'refunded' end) as payment_status from ".$this->tableNames['user']." where  is_deleted='".$is_deleted."' and user_group='2' order by ".$orderby." ".$limit;
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
    
    
    public function getSubAdmin($is_deleted='0',$orderby='user_id desc',$limit='')
    {
       $query = "select user_id,first_name, last_name,user_group,username, (case when payment_status='0' Then 'pending' when  payment_status='1' then 'accepted' when  payment_status='2' then 'mark as fraud' when  payment_status='3' then 'rejected' when  payment_status='4' then 'refunded' end) as payment_status from ".$this->tableNames['user']." where  is_deleted='".$is_deleted."' and user_group='3' and added_by='".$_SESSION['user_detail']['user_id']."' order by ".$orderby." ".$limit;
        
        return $this->get_results($query);
    }
}
