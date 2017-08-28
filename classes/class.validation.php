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
    public $allowImageExt = array('image/gif', 'image/jpeg', 'image/jpg', 'image/png', 'application/pdf');
    public $allowExcelExt = array('application/vnd.ms-excel', 'application/octet-stream', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    
    public function __construct() {

        parent::__construct();
        
        $this->tableNames = array(
            'user' => TAB_PREFIX . 'user',
            'user_group' => TAB_PREFIX . 'user_group',
            'user_subscribed_plan' => TAB_PREFIX . 'user_subscribed_plan',
            'preserve_user' => TAB_PREFIX . 'user_preserve',
            'subscriber_plan_category' => TAB_PREFIX . 'subscriber_plan_category',
            'subscriber_plan' => TAB_PREFIX . 'subscriber_plan',
            'state' => TAB_PREFIX . 'master_state',
            'country' => TAB_PREFIX . 'master_country',
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
            'client_invoice' => TAB_PREFIX . 'client_invoice',
            'client_invoice_item' => TAB_PREFIX . 'client_invoice_item',
			'client_purchase_invoice' => TAB_PREFIX . 'client_purchase_invoice',
            'client_purchase_invoice_item' => TAB_PREFIX . 'client_purchase_invoice_item',
            'client_reconcile_purchase_invoice' => TAB_PREFIX . 'client_reconcile_purchase_invoice ',
            'client_reconcile_purchase_invoice1' => TAB_PREFIX . 'client_reconcile_purchase_invoice1',            
            'business_type' => TAB_PREFIX . 'business_type',
            'business_area' => TAB_PREFIX . 'business_area',
            'vendor_type' => TAB_PREFIX . 'vendor_type',
            'forgot_email' => TAB_PREFIX . 'forgot_email',
            'api' => TAB_PREFIX . 'api',
            'return' => TAB_PREFIX . 'return',
            'email'=>TAB_PREFIX.'email',
            'coupon'=>TAB_PREFIX.'coupon',
            'client_return_gstr3b'=>TAB_PREFIX.'client_return_gstr3b',
			'client_return_gstr3b_pos'=>TAB_PREFIX.'client_return_gstr3b_pos',
			'client_upload_gstr2'=>TAB_PREFIX.'client_upload_gstr2',
            'user_gstr1'=>TAB_PREFIX.'user_gstr1',
			'notification'=>TAB_PREFIX.'notification',
			'user_notification'=>TAB_PREFIX.'user_notification',
			'module'=>TAB_PREFIX.'module',
			'place_of_supply'=>TAB_PREFIX.'place_of_supply',
			'admin_log'=>TAB_PREFIX.'admin_log',
			'admin_setting'=>TAB_PREFIX.'admin_setting',
            'email_templates'=>TAB_PREFIX.'email_templates'
			
			
        );

        $this->checkUserPortalAccess();
        $this->checkUserAccess();
    }

    public function checkUserPortalAccess() {

        if( (isset($_REQUEST['page']) || isset($_REQUEST['ajax'])) && (!isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '') ) {
            $this->redirect(PROJECT_URL);
            exit();
        }
    }

    //onedash   /^[a-zA-Z\d]+[(-{1})|(a-zA-Z\d)][a-zA-Z\d]+$/
    protected $validateType = array(
		"username" => "[a-zA-Z\d]+[(_{1}\-{1}\.{1})|(a-zA-Z\d)][a-zA-Z\d]",
		"firstname" => "[a-zA-Z\d]+[(_{1}\-{1}\.{1})|(a-zA-Z\d)][a-zA-Z\d]",
		"lastname" => "[a-zA-Z\d]+[(_{1}\-{1}\.{1})|(a-zA-Z\d)][a-zA-Z\d]",
		"companyname" => "[a-zA-Z\d]+[(_{1}\-{1}\.{1})|(a-zA-Z\d)][a-zA-Z\d]",
        "invoicenumber" => "[a-zA-Z\d]+[(-{1})|(a-zA-Z\d)][a-zA-Z\d]",
        "alphanumeric" => "A-Za-z0-9\n\r\&\/\-\(\)\,\.",
        "mobilenumber" => "\d{10}",
        "content" => "^\\\"<>|",
        "pincode" => "\d{6}",
        "yearmonth" => "[0-9]{4}-(0[1-9]|1[0-2])",
        "datetime" => "[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) (2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]",
        "alphaspace" => "a-zA-Z\s",
        "integergreaterzero" => "(0*[1-9][0-9]*)",
        "pancard" => "(([A-Z]){5}([0-9]){4}([A-Z]){1})",
        "gstinnumber" => "(([0-9]){2}([A-Z]){5}([0-9]){4}([A-Z]){1}([A-Z0-9]){1}([Z]){1}([A-Z0-9]){1})",
        "onlyzeroone" => "01"
    );

    protected $validationMessage = array(
        'mandatory'=> 'Fill all mandatory fields',
        'failed' => "Some error try again to submit.",
        'loginerror' => 'Username or Password you entered is incorrect.',
        'passwordnotmatched' => 'Password not matched.',
        'usernameexist' => 'Username already exists.',
        'emailexist' => 'Email already exists.',
        'companycodeexist' => 'Company Code already exists.',
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
        'statetinexist' => 'State tin already exist.',
        'unitcodeexist' => 'Unit code already exist.',
        'invoiceadded' => 'Invoice added successfully.',
        'invoiceupdated' => 'Invoice updated successfully.',
        'invoicedeleted' => 'Invoice deleted successfully.',
        'noiteminvoice' => 'There is no item in invoice.',
        'receiveradded' => ' Receiver added successfully.',
        'supplieradded' => ' Supplier added successfully.',
        'itemadded' => ' Item added successfully.',
        'excelerror' => 'There is an error in uploaded excel. Download and check in error information column.'
    );

    public function getTableName($tablename)
    {
        return $this->tableNames[$tablename];
    }
    /* Perform query to retrieve object of result */

    public function getValMsg($msg)
    {
        return $this->validationMessage[$msg];
    }
    
    public function checkUserAccess() {
        
        if( isset($_SESSION['user_detail']['user_id']) && $_SESSION['user_detail']['user_id'] != '' ) {
            
            $currentUserDetails = $this->getUserDetailsById( $_SESSION['user_detail']['user_id'] );
            if($currentUserDetails['data']->user_group == 3) {

                if( isset($_GET['page']) && $_GET['page'] != "plan_chooseplan" && $_GET['page'] != "logout" && $_GET['page'] != "payment_online"  && $_GET['page'] != "payment_error"  && $_GET['page'] != "payment_success" && $_GET['page'] != "payment_response") {

                    if($currentUserDetails['data']->plan_id == 0) {
                        $this->redirect(PROJECT_URL . "?page=plan_chooseplan");
                    }
                }
            } else if($currentUserDetails['data']->user_group == 4) {

                if( isset($_GET['page']) && $_GET['page'] != "client_loginas")        
                {
                    if( isset($_GET['page']) && $_GET['page'] != "client_kycupdate" && $_GET['page'] != "logout") {

                        if($currentUserDetails['data']->kyc == '') {
                            $this->redirect(PROJECT_URL . "?page=client_kycupdate");
                        }
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
       // $query = "select ".$field."  from ".$this->tableNames['user']." where 1=1 ";
        $query = "select ".$field."  from ".$this->tableNames['user']." u inner join ".$this->tableNames['client_kyc']." k on u.user_id=k.added_by ";
        //." p join ".$this->tableNames['subscriber_plan_category']." c join ".$this->tableNames['user']." u on p.added_by=u.user_id 
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

    public function gstReturnSummary()
    {
        $return_id = $_POST['return_id'];    
        $dataResults = $this->getClientReturn($this->sanitize($return_id));
        $dataKyc = $this->getClientKyc();

        $month = $dataResults[0]->return_month;
        $query = "select  SUM(b.cgst_amount) AS totalcgst, SUM(b.igst_amount) AS totaligst, SUM(b.sgst_amount) AS totalsgst, sum(b.taxable_subtotal) as totalsub from ".TAB_PREFIX."client_invoice a inner join ".TAB_PREFIX."client_invoice_item b on a.invoice_id=b.invoice_id where a.invoice_date like'".$month."%' and a.added_by='".$_SESSION['user_detail']['user_id']."'";
        $flag=0;
        $data2 = $this->get_results($query);
        $dataArr = array();        
        if(!empty($data2))
        {
            $dataArr['msg']='suc';
            $dataArr['data']['totalcgst']= $data2[0]->totalcgst;
            $dataArr['data']['totalsgst']= $data2[0]->totalsgst;
            $dataArr['data']['totaligst']= $data2[0]->totaligst;
            $dataArr['data']['totalsub']= $data2[0]->totalsub;
            $dataArr['data']['retperiod']= $month;
            $flag=1;
        }
        $query = "select  SUM(invoice_total_value) AS total,count(invoice_total_value) as invoicecount from ".TAB_PREFIX."client_invoice where invoice_date like'".$month."%' and added_by='".$_SESSION['user_detail']['user_id']."'";
        $data2 = $this->get_results($query);
        if(!empty($data2))
        {
            $dataArr['data']['total']= $data2[0]->total;
            $dataArr['data']['invoice_count']= $data2[0]->invoicecount;
            $flag=1;
        }

        if($flag==0)
        {
            $dataArr['msg']='err';
        }
        return $dataArr;
    }

    public function getB2BInvoices($user_id,$returnmonth,$type=''){
        $queryB2B =  "select a.invoice_id,a.invoice_type,a.company_state,a.billing_gstin_number,a.reference_number,a.invoice_date,a.invoice_total_value,a.supply_place,a.invoice_type,a.supply_type,b.igst_rate,b.cgst_rate,b.sgst_rate,b.consolidate_rate,b.taxable_subtotal, sum(b.igst_amount) as igst_amount, sum(b.cgst_amount) as cgst_amount, sum(b.sgst_amount) as sgst_amount,sum(b.cess_amount) as cess_amount from ".$this->getTableName('client_invoice')." a inner join ".$this->getTableName('client_invoice_item')." b on a.invoice_id=b.invoice_id where 1 ";
        
        if($type != '') {
            if($type != 'all') {
                $queryB2B .=  " and a.is_gstr1_uploaded='".$type."'  ";            
            }
            
        }
        else if($type == '') {
            $queryB2B .=  " and a.is_gstr1_uploaded='0' ";
        }

        $queryB2B .= "and a.status='1' and a.added_by='".$user_id."'  and a.invoice_date like '%".$returnmonth."%' and a.billing_gstin_number!='' and a.invoice_type='taxinvoice' and a.invoice_nature='salesinvoice' and a.is_canceled='0' and a.is_deleted='0'  group by a.reference_number, b.consolidate_rate";

        //echo $queryB2B.'<br/>';
        return $this->get_results($queryB2B);
    }

    public function getB2CLInvoices($user_id,$returnmonth,$type=''){
        $queryB2CL =  "select a.invoice_id,a.invoice_type,a.company_state,a.billing_gstin_number,a.reference_number,a.invoice_date,a.invoice_total_value,a.supply_place,a.invoice_type,a.supply_type,b.igst_rate,b.cgst_rate,b.sgst_rate,b.consolidate_rate,b.taxable_subtotal, sum(b.igst_amount) as igst_amount, sum(b.cgst_amount) as cgst_amount, sum(b.sgst_amount) as sgst_amount,sum(b.cess_amount) as cess_amount from ".$this->getTableName('client_invoice')." a inner join ".$this->getTableName('client_invoice_item')." b on a.invoice_id=b.invoice_id where 1 ";

        if($type != '') {
            if($type != 'all') {
                $queryB2CL .=  " and a.is_gstr1_uploaded='".$type."'  ";            
            }
            
        }
        else if($type == '') {
            $queryB2CL .=  " and a.is_gstr1_uploaded='0' ";
        }

        $queryB2CL .= "and a.status='1' and a.added_by='".$user_id."' and a.invoice_date like '%".$returnmonth."%' and a.billing_gstin_number='' and a.invoice_total_value>'250000' and a.supply_place!=a.company_state and a.invoice_type='taxinvoice' and a.invoice_nature='salesinvoice' and a.is_canceled='0' and a.is_deleted='0' group by a.reference_number, b.consolidate_rate order by a.supply_place ";
        //echo $queryB2CL.'<br/>';
        return $this->get_results($queryB2CL); 
    }

    public function getB2CSInvoices($user_id,$returnmonth,$type=''){
        $queryB2CS =  "select a.invoice_id,a.invoice_type,a.company_state,a.billing_gstin_number,a.reference_number,a.invoice_date,a.invoice_total_value,a.supply_place,a.invoice_type,a.supply_type,a.ecommerce_gstin_number,b.igst_rate,b.cgst_rate,b.sgst_rate,b.consolidate_rate,b.taxable_subtotal, sum(b.igst_amount) as igst_amount, sum(b.cgst_amount) as cgst_amount, sum(b.sgst_amount) as sgst_amount,sum(b.cess_amount) as cess_amount from ".$this->getTableName('client_invoice')." a inner join ".$this->getTableName('client_invoice_item')." b on a.invoice_id=b.invoice_id where 1";

        if($type != '') {
            if($type != 'all') {
                $queryB2CS .=  " and a.is_gstr1_uploaded='".$type."'  ";            
            }
            
        }
        else if($type == '') {
            $queryB2CS .=  " and a.is_gstr1_uploaded='0' ";
        }

        $queryB2CS .= " and a.status='1' and a.added_by='".$user_id."'  and a.invoice_date like '%".$returnmonth."%' and a.billing_gstin_number='' and (a.supply_place=a.company_state  or (a.supply_place!=a.company_state and a.invoice_total_value<='250000')) and a.invoice_type='taxinvoice' and a.invoice_nature='salesinvoice' and a.is_canceled='0' and a.is_deleted='0' group by a.reference_number, b.consolidate_rate order by a.supply_place ";
        //echo $queryB2CS.'<br/>';
        return $this->get_results($queryB2CS);
    }

    public function getCDNRInvoices($user_id,$returnmonth,$type=''){
       $queryCDNR =  "select a.invoice_id,a.invoice_type,a.reference_number ,c.reference_number as corresponding_document_number,a.corresponding_document_date, a.invoice_id,a.company_state,a.billing_gstin_number,a.reference_number,a.invoice_date,a.invoice_total_value, a.supply_place,b.igst_rate,b.cgst_rate,b.sgst_rate,b.taxable_subtotal, sum(b.igst_amount) as igst_amount, sum(b.cgst_amount) as cgst_amount, sum(b.sgst_amount) as sgst_amount,sum(b.cess_amount) as cess_amount from ".$this->getTableName('client_invoice')." a inner join ".$this->getTableName('client_invoice_item')." b on a.invoice_id=b.invoice_id inner join ".$this->getTableName('client_invoice')." c  on a.corresponding_document_number=c.invoice_id where 1 ";

        if($type != '') {
            if($type != 'all') {
                $queryCDNR .=  " and a.is_gstr1_uploaded='".$type."'  ";            
            }
            
        }
        else if($type == '') {
            $queryCDNR .=  " and a.is_gstr1_uploaded='0' ";
        }
        $queryCDNR .= " and a.status='1' and a.billing_gstin_number!='' and a.added_by='".$user_id."' and a.invoice_date like '%".$returnmonth."%' and (a.invoice_type='creditnote' or a.invoice_type='debitnote' or a.invoice_type='refundvoucherinvoice' ) and a.is_canceled='0' and a.is_deleted='0' group by a.reference_number order by a.supply_place ";
       //echo $queryCDNR.'<br/>';
        return $this->get_results($queryCDNR);
    }

    public function getCDNURInvoices($user_id,$returnmonth,$type=''){
        $queryCDNUR =  "select a.invoice_id,a.invoice_type,a.reference_number,c.reference_number as corresponding_document_number,c.export_supply_meant,c.invoice_type as original_type,a.corresponding_document_date,a.company_state,a.billing_gstin_number,a.reference_number,a.invoice_date,a.invoice_total_value,a.supply_place,b.igst_rate,b.cgst_rate,b.sgst_rate,b.consolidate_rate,b.taxable_subtotal, sum(b.igst_amount) as igst_amount, sum(b.cgst_amount) as cgst_amount, sum(b.sgst_amount) as sgst_amount,sum(b.cess_amount) as cess_amount from ".$this->getTableName('client_invoice')." a inner join ".$this->getTableName('client_invoice_item')." b on a.invoice_id=b.invoice_id inner join ".$this->getTableName('client_invoice')." c  on a.corresponding_document_number=c.invoice_id where 1";

        if($type != '') {
            if($type != 'all') {
                $queryCDNUR .=  " and a.is_gstr1_uploaded='".$type."'  ";            
            }
            
        }
        else if($type == '') {
            $queryCDNUR .=  " and a.is_gstr1_uploaded='0' ";
        }
        $queryCDNUR .= " and a.status='1' and a.added_by='".$user_id."' and a.invoice_date like '".$returnmonth."%' 
            and a.supply_place!=a.company_state and a.invoice_corresponding_type='taxinvoice' 
            and a.billing_gstin_number='' and a.invoice_total_value >'250000' 
            and (a.invoice_type='creditnote' or a.invoice_type='debitnote' or a.invoice_type='refundvoucherinvoice' ) 
            and (c.invoice_type='exportinvoice' or c.invoice_type='sezunitinvoice' or c.invoice_type='deemedexportinvoice' or c.invoice_type='taxinvoice') 
            and a.is_canceled='0' and a.is_deleted='0' group by a.reference_number, b.consolidate_rate order by a.supply_place ";
        //echo $queryCDNUR;
        return $this->get_results($queryCDNUR);
    }

    public function getATInvoices($user_id,$returnmonth,$type=''){
       $queryAt =  "select a.invoice_id,a.invoice_type,a.company_state,a.reference_number,a.billing_gstin_number,a.reference_number,a.supply_place,a.invoice_date,a.invoice_total_value,b.item_name,b.taxable_subtotal, sum(b.igst_amount) as igst_amount, sum(b.cgst_amount) as cgst_amount, sum(b.sgst_amount) as sgst_amount,sum(b.cess_amount) as cess_amount,b.igst_rate,b.cgst_rate,b.sgst_rate,b.consolidate_rate from ".$this->getTableName('client_invoice')." a inner join ".$this->getTableName('client_invoice_item')." b on a.invoice_id=b.invoice_id  where 1 ";


        if($type != '') {
            if($type != 'all') {
                $queryAt .=  " and a.is_gstr1_uploaded='".$type."'  ";            
            }
            
        }
        else if($type == '') {
            $queryAt .=  "and a.is_gstr1_uploaded='0' ";
        }

        $queryAt .= " and a.status='1'  and a.added_by='".$user_id."' and a.invoice_date like '%".$returnmonth."%' and a.invoice_type='receiptvoucherinvoice' and a.is_canceled='0' and a.is_deleted='0' group by a.supply_place ,b.consolidate_rate order by a.supply_place ";
        //echo 'AT====='.$queryAt.'<br/>';
        return $this->get_results($queryAt);
    }

    public function getEXPInvoices($user_id,$returnmonth,$type=''){
       $queryExp =  "select a.export_bill_number,a.invoice_type,a.export_bill_date,a.export_bill_port_code,a.invoice_id,a.export_supply_meant,a.company_state,a.billing_gstin_number,a.reference_number,a.invoice_date,a.invoice_total_value,b.item_name,a.supply_place,a.invoice_type,b.taxable_subtotal,b.igst_rate,b.cgst_rate,b.sgst_rate,b.consolidate_rate, sum(b.igst_amount) as igst_amount, sum(b.cgst_amount) as cgst_amount, sum(b.sgst_amount) as sgst_amount,sum(b.cess_amount) as cess_amount from ".$this->getTableName('client_invoice')." a inner join ".$this->getTableName('client_invoice_item')." b on a.invoice_id=b.invoice_id where 1 ";

       if($type != '') {
            if($type != 'all') {
                $queryExp .=  " and a.is_gstr1_uploaded='".$type."'  ";            
            }
            
        }
        else if($type == '') {
            $queryExp .=  "and a.is_gstr1_uploaded='0' ";
        }

       $queryExp .= " and a.status='1' and a.added_by='".$user_id."' and a.invoice_date like '%".$returnmonth."%' and (a.invoice_type='exportinvoice' or a.invoice_type='sezunitinvoice' or a.invoice_type='deemedexportinvoice') and a.invoice_nature='salesinvoice' and a.is_canceled='0' and a.is_deleted='0' and a.export_bill_number !='' and a.export_bill_date != '' and a.export_bill_port_code != '' group by a.invoice_id,b.consolidate_rate order by a.export_supply_meant";
        //echo 'Exp=>>>>>>'.$queryExp.'<br/>';
        return $this->get_results($queryExp); 
    }

    protected function getHSNInvoices($user_id,$returnmonth,$type=''){
        $queryHsn =  "select a.invoice_id,a.invoice_type,a.company_state,a.invoice_date,a.invoice_total_value,b.item_name,a.supply_place,a.invoice_type,b.item_hsncode,b.item_quantity,b.item_unit,b.taxable_subtotal, sum(b.igst_amount) as igst_amount, sum(b.cgst_amount) as cgst_amount, sum(b.sgst_amount) as sgst_amount,sum(b.cess_amount) as cess_amount from ".$this->getTableName('client_invoice')." a inner join ".$this->getTableName('client_invoice_item')." b on a.invoice_id=b.invoice_id where 1 ";

        if($type != '') {
            if($type != 'all') {
                $queryHsn .=  " and a.is_gstr1_uploaded='".$type."'  ";            
            }
            
        }
        else if($type == '') {
            $queryHsn .=  " and a.is_gstr1_uploaded='0' ";
        }

        $queryHsn .= " and a.status='1' and a.added_by='".$user_id."' and a.invoice_date like '%".$returnmonth."%' and (a.invoice_type='creditnote' or a.invoice_type='debitnote' or a.invoice_type='taxinvoice' or a.invoice_type='receiptvoucherinvoice' or  a.invoice_type='exportinvoice' or a.invoice_type='sezunitinvoice' or a.invoice_type='deemedexportinvoice' or a.invoice_type='refundvoucherinvoice' ) and a.is_canceled='0' and a.is_deleted='0' group by b.item_hsncode";
        //echo $queryHsn.'<br/>';
        return $this->get_results($queryHsn); 
    }
    
    public function getNILInvoices($user_id,$returnmonth){
        $query1 =  "select a.invoice_id,a.invoice_type,a.company_state,a.billing_gstin_number,a.reference_number,a.supply_place,a.invoice_date,a.invoice_total_value,b.item_name,a.invoice_type,b.taxable_subtotal, sum(b.igst_amount) as igst_amount, sum(b.cgst_amount) as cgst_amount, sum(b.sgst_amount) as sgst_amount,sum(b.cess_amount) as cess_amount from ".$this->getTableName('client_invoice')." a inner join ".$this->getTableName('client_invoice_item')." b on a.invoice_id=b.invoice_id  where a.is_gstr1_uploaded='0' and a.status='1' and a.added_by='".$user_id."' and a.invoice_date like '%".$returnmonth."%' and a.billing_gstin_number!='' and a.is_canceled='0' and a.is_deleted='0' ";

       $query2 =  "select a.invoice_id,a.invoice_type,a.company_state,a.billing_gstin_number,a.reference_number,a.supply_place,a.invoice_date,a.invoice_total_value,b.item_name,a.invoice_type,b.taxable_subtotal, sum(b.igst_amount) as igst_amount, sum(b.cgst_amount) as cgst_amount, sum(b.sgst_amount) as sgst_amount,sum(b.cess_amount) as cess_amount from ".$this->getTableName('client_invoice')." a inner join ".$this->getTableName('client_invoice_item')." b on a.invoice_id=b.invoice_id where a.is_gstr1_uploaded='0' and a.status='1' and a.added_by='".$user_id."' and a.invoice_date like '%".$returnmonth."%' and a.billing_gstin_number='' and a.is_canceled='0' and a.is_deleted='0' ";
        $dataInv1 = $this->get_results($query1);
        $dataInv2 = $this->get_results($query2);
        $data = array($dataInv1,$dataInv2);
        return $data;

    }

    protected function getDOCSalesInvoices($user_id,$returnmonth){
        $querySales =  "select a.invoice_id,a.reference_number from ".$this->getTableName('client_invoice')." a where a.is_gstr1_uploaded='0' and a.status='1' and a.added_by='".$user_id."'  and a.invoice_date like '%".$returnmonth."%' and a.reference_number != '' and   a.invoice_type in('taxinvoice','exportinvoice','sezunitinvoice','deemedexportinvoice','sezunitinvoice')  and a.is_canceled='0' and a.is_deleted='0' order by a.reference_number";
        $queryCancle =  "select a.invoice_id,a.reference_number from ".$this->getTableName('client_invoice')." a where a.is_gstr1_uploaded='0' and a.status='1' and a.added_by='".$user_id."'  and a.invoice_date like '%".$returnmonth."%' and a.reference_number != '' and a.is_canceled = '1'  and  a.invoice_type in('taxinvoice','exportinvoice','sezunitinvoice','deemedexportinvoice','sezunitinvoice') group by a.reference_number order by a.reference_number";

        $dataInvSales = $this->get_results($querySales);
        $dataInvCancelSales = $this->get_results($queryCancle);
        $data = array($dataInvSales,$dataInvCancelSales);
        return $data;

    }
    protected function getDOCRevisedInvoices($user_id,$returnmonth){
        $queryRevised =  "select a.invoice_id,a.reference_number from ".$this->getTableName('client_invoice')." a where a.is_gstr1_uploaded='0' and a.status='1' and a.added_by='".$user_id."'  and a.invoice_date like '%".$returnmonth."%' and a.reference_number != '' and  a.invoice_type = 'revisedtaxinvoice' and a.is_canceled='0' and a.is_deleted='0'  order by a.reference_number";

        $queryRevisedCancle =  "select a.invoice_id,a.reference_number from ".$this->getTableName('client_invoice')." a where a.is_gstr1_uploaded='0' and a.status='1' and a.added_by='".$user_id."'  and a.invoice_date like '%".$returnmonth."%' and a.reference_number != '' and a.is_canceled = '1'  and  a.invoice_type = 'revisedtaxinvoice'  group by a.reference_number order by a.reference_number";

        $dataInvRevised = $this->get_results($queryRevised);
        $dataInvCancleRevised = $this->get_results($queryRevisedCancle);
        $data = array($dataInvRevised,$dataInvCancleRevised);
        return $data;

    }
    protected function getDOCDebitInvoices($user_id,$returnmonth){
        $queryDebit =  "select a.invoice_id,a.reference_number from ".$this->getTableName('client_invoice')." a where a.is_gstr1_uploaded='0' and a.status='1' and a.added_by='".$user_id."'  and a.invoice_date like '%".$returnmonth."%' and a.reference_number != '' and  a.invoice_type = 'debitnote' and a.is_canceled='0' and a.is_deleted='0'  order by a.reference_number";

        $queryDebitCancle =  "select a.invoice_id,a.reference_number from ".$this->getTableName('client_invoice')." a where a.is_gstr1_uploaded='0' and a.status='1' and a.added_by='".$user_id."'  and a.invoice_date like '%".$returnmonth."%' and a.reference_number != '' and a.is_canceled = '1'  and  a.invoice_type = 'debitnote'  group by a.reference_number order by a.reference_number";

        $dataInvDebit = $this->get_results($queryDebit);
        $dataInvCancleDebit = $this->get_results($queryDebitCancle);
        $data = array($dataInvDebit,$dataInvCancleDebit);
        return $data;

    }

    protected function getDOCCreditInvoices($user_id,$returnmonth){
        $queryCredit =  "select a.invoice_id,a.reference_number from ".$this->getTableName('client_invoice')." a where a.is_gstr1_uploaded='0' and a.status='1' and a.added_by='".$user_id."'  and a.invoice_date like '%".$returnmonth."%' and a.reference_number != '' and  a.invoice_type = 'creditnote' and a.is_canceled='0' and a.is_deleted='0' order by a.reference_number";

        $queryCreditCancle =  "select a.invoice_id,a.reference_number from ".$this->getTableName('client_invoice')." a where a.is_gstr1_uploaded='0' and a.status='1' and a.added_by='".$user_id."'  and a.invoice_date like '%".$returnmonth."%' and a.reference_number != '' and a.is_canceled = '1'  and  a.invoice_type = 'creditnote'  group by a.reference_number order by a.reference_number";

        $dataInvCredit = $this->get_results($queryCredit);
        $dataInvCancleCredit = $this->get_results($queryCreditCancle);
        $data = array($dataInvCredit,$dataInvCancleCredit);
        return $data;

    }
    protected function getDOCReceiptInvoices($user_id,$returnmonth){
        $queryReceipt =  "select a.invoice_id,a.reference_number from ".$this->getTableName('client_invoice')." a where a.is_gstr1_uploaded='0' and a.status='1' and a.added_by='".$user_id."'  and a.invoice_date like '%".$returnmonth."%' and a.reference_number != '' and  a.invoice_type = 'receiptvoucherinvoice' and a.is_canceled='0' and a.is_deleted='0'  order by a.reference_number";

        $queryReceiptCancle =  "select a.invoice_id,a.reference_number from ".$this->getTableName('client_invoice')." a where a.is_gstr1_uploaded='0' and a.status='1' and a.added_by='".$user_id."'  and a.invoice_date like '%".$returnmonth."%' and a.reference_number != '' and a.is_canceled = '1'  and  a.invoice_type = 'receiptvoucherinvoice'  group by a.reference_number order by a.reference_number";

        //End Code For Doc
        $dataInvReceipt = $this->get_results($queryReceipt);
        $dataInvCancleReceipt = $this->get_results($queryReceiptCancle);
        $data = array($dataInvReceipt,$dataInvCancleReceipt);
        return $data;
    }
    protected function getDOCRefundInvoices($user_id,$returnmonth){
        $queryReceipt =  "select a.invoice_id,a.reference_number from ".$this->getTableName('client_invoice')." a where a.is_gstr1_uploaded='0' and a.status='1' and a.added_by='".$user_id."'  and a.invoice_date like '%".$returnmonth."%' and a.reference_number != '' and  a.invoice_type = 'refundvoucherinvoice' and a.is_canceled='0' and a.is_deleted='0' order by a.reference_number";

        $queryReceiptCancle =  "select a.invoice_id,a.reference_number from ".$this->getTableName('client_invoice')." a where a.is_gstr1_uploaded='0' and a.status='1' and a.added_by='".$user_id."'  and a.invoice_date like '%".$returnmonth."%' and a.reference_number != '' and a.is_canceled = '1'  and  a.invoice_type = 'refundvoucherinvoice'  group by a.reference_number order by a.reference_number";

        $dataInvRefund = $this->get_results($queryReceipt);
        $dataInvCancleRefund = $this->get_results($queryReceiptCancle);
        $data = array($dataInvRefund,$dataInvCancleRefund);
        return $data;
    }

    protected function getDOCDeliveryChallanJobWorkInvoices($user_id,$returnmonth){
        $queryDeliveryJobWork =  "select a.invoice_id,a.reference_number from ".$this->getTableName('client_invoice')." a where a.is_gstr1_uploaded='0' and a.status='1' and a.added_by='".$user_id."'  and a.invoice_date like '%".$returnmonth."%' and a.reference_number != '' and  a.invoice_type = 'deliverychallaninvoice' and  a.delivery_challan_type = 'jobwork' and a.is_canceled='0' and a.is_deleted='0' order by a.reference_number";

        $queryDeliveryJobWorkCancle =  "select a.invoice_id,a.reference_number from ".$this->getTableName('client_invoice')." a where a.is_gstr1_uploaded='0' and a.status='1' and a.added_by='".$user_id."'  and a.invoice_date like '%".$returnmonth."%' and a.reference_number != '' and a.is_canceled = '1'  and  a.invoice_type = 'deliverychallaninvoice' and  a.delivery_challan_type = 'jobwork' group by a.reference_number order by a.reference_number";

        $dataInvDeliveryJobWork = $this->get_results($queryDeliveryJobWork);
        $dataInvCancleDeliveryJobWork = $this->get_results($queryDeliveryJobWorkCancle);
        $data = array($dataInvDeliveryJobWork,$dataInvCancleDeliveryJobWork);
        return $data;
    }

    protected function getDOCDeliveryChallanSupplyOnApprovalInvoices($user_id,$returnmonth){
        $queryDeliverySUAP =  "select a.invoice_id,a.reference_number from ".$this->getTableName('client_invoice')." a where a.is_gstr1_uploaded='0' and a.status='1' and a.added_by='".$user_id."'  and a.invoice_date like '%".$returnmonth."%' and a.reference_number != '' and  a.invoice_type = 'deliverychallaninvoice' and  a.delivery_challan_type = 'supplyonapproval' and a.is_canceled='0' and a.is_deleted='0' order by a.reference_number";

        $queryDeliverySUAPCancle =  "select a.invoice_id,a.reference_number from ".$this->getTableName('client_invoice')." a where a.is_gstr1_uploaded='0' and a.status='1' and a.added_by='".$user_id."'  and a.invoice_date like '%".$returnmonth."%' and a.reference_number != '' and a.is_canceled = '1'  and  a.invoice_type = 'deliverychallaninvoice' and  a.delivery_challan_type = 'supplyonapproval'  group by a.reference_number order by a.reference_number";

        $dataInvDeliverySUAP = $this->get_results($queryDeliverySUAP);
        $dataInvCancleDeliverySUAP = $this->get_results($queryDeliverySUAPCancle);
        $data = array($dataInvDeliverySUAP,$dataInvCancleDeliverySUAP);
        return $data;
    }

    protected function getDOCDeliveryChallanInCaseLiquidGasInvoices($user_id,$returnmonth){
        $queryDeliverySULGAS =  "select a.invoice_id,a.reference_number from ".$this->getTableName('client_invoice')." a where a.is_gstr1_uploaded='0' and a.status='1' and a.added_by='".$user_id."'  and a.invoice_date like '%".$returnmonth."%' and a.reference_number != '' and  a.invoice_type = 'deliverychallaninvoice' and  a.delivery_challan_type = 'supplyofliquidgas' and a.is_canceled='0' and a.is_deleted='0' order by a.reference_number";

        $queryDeliverySULGASCancle =  "select a.invoice_id,a.reference_number from ".$this->getTableName('client_invoice')." a where a.is_gstr1_uploaded='0' and a.status='1' and a.added_by='".$user_id."'  and a.invoice_date like '%".$returnmonth."%' and a.reference_number != '' and a.is_canceled = '1'  and  invoice_type = 'deliverychallaninvoice' and  a.delivery_challan_type = 'supplyofliquidgas' group by a.reference_number order by a.reference_number";

        $dataInvDeliverySULGAS = $this->get_results($queryDeliverySULGAS);
        $dataInvCancleDeliverySULGAS = $this->get_results($queryDeliverySULGASCancle);
        $data = array($dataInvDeliverySULGAS,$dataInvCancleDeliverySULGAS);
        return $data;
    }

    protected function getDOCDeliveryChallanInCaseOtherInvoices($user_id,$returnmonth){

        $queryDeliverySupplyOther =  "select a.invoice_id,a.reference_number from ".$this->getTableName('client_invoice')." a where a.is_gstr1_uploaded='0' and a.status='1' and a.added_by='".$user_id."'  and a.invoice_date like '%".$returnmonth."%' and a.reference_number != '' and  a.invoice_type = 'deliverychallaninvoice' and  a.delivery_challan_type = 'supplyofliquidgas' and a.is_canceled='0' and a.is_deleted='0' order by a.reference_number";

        $queryDeliverySupplyOtherCancle =  "select a.invoice_id,a.reference_number from ".$this->getTableName('client_invoice')." a where a.is_gstr1_uploaded='0' and a.status='1' and a.added_by='".$user_id."'  and a.invoice_date like '%".$returnmonth."%' and a.reference_number != '' and a.is_canceled = '1'  and  invoice_type = 'deliverychallaninvoice' and  a.delivery_challan_type = 'others'  group by a.reference_number order by a.reference_number";

        $dataInvDeliverySupplyOther = $this->get_results($queryDeliverySupplyOther);
        $dataInvCancleDeliverySupplyOther= $this->get_results($queryDeliverySupplyOtherCancle);
        $data = array($dataInvDeliverySupplyOther,$dataInvCancleDeliverySupplyOther);
        return $data;
    }

}

