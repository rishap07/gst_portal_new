<?php

/*
 * 
 *  Developed By        :   Rishap Gandhi
 *  Date Created        :   Aug 31, 2016
 *  Last Modified       :   Aug 31, 2016
 *  Last Modified By    :   Rishap Gandhi
 *  Last Modification   :   class for HR Jobs
 * 
 */

class hrjobs extends validation {

    public function __construct() {
        parent::__construct();
    }

    private $begin = "BEGIN";
    private $rollback = "ROLLBACK";
    private $commit = "COMMIT";
    public $mimes = array('application/vnd.ms-excel', 'text/csv');
    public $imageExt = array('image/gif', 'image/jpeg', 'image/jpg', 'image/png');
    public $imagePdfExt = array('image/gif', 'image/jpeg', 'image/jpg', 'image/png', 'application/pdf');
    public $tableName = 'ilbs_hrjobs';
    public $table_name = array(
        'registration' => 'hrjobs_registration',
        'hrjobapply' => 'hrjobs_apply',
        'registration_final' => 'hrjobs_registration_final',
        'registration_final_experience' => 'hrjobs_registration_final_experience',
        'registration_final_publication' => 'hrjobs_registration_final_publication',
        'registration_final_qualification' => 'hrjobs_registration_final_qualification',
        'hrjobs_session' => 'hrjobs_session'
        
    );
    public $moduleName = 'hrjobs';
    public $attributeLabels = array(
        'album_id' => 'Album ID',
        'album_cat_id' => 'Album Number',
        'album_name' => 'Album Name',
        'published_date' => 'Publishing Date',
        'published_by' => 'Published By',
        'modified_date' => 'Modification Date',
        'modified_by' => 'Modified By',
        'unpublished_date' => 'Unpublishing Date',
        'feature_image' => 'Image',
        'status' => 'Status',
        'is_deleted' => 'Deleted',
        'entry_date' => 'Created on',
        'deleted_date' => 'Deleted on',
        'language_id' => 'Language'
    );
    public $msg = array(
        'mandatory' => "Kindly fill all mandatory fields.",
        'failed' => "Some error try again to submit.",
        'invalid' => 'Invalid ID selected for edit',
        'insert_suc' => 'New HR Job Posted Successfully.',
        'update_suc' => 'HR Job Updated Successfully.',
        'suc' => 'Results Found',
        'err' => 'No Records Found',
        'passwordnotmatched' => 'Password not matched',
        'saved' => "Form Save Successfully",
        'imagesmsg' => 'Kindly select correct File/Image',
        'logfail' => 'Invalid Email or Password',
        'alexist' => 'Email Already exist',
        'slejob' => 'Select Job',
        'invalidjob' => 'Invalid Job Selected',
        'jobapliedald' => 'You Already applied this job',
        'alredyexist' => 'Job title already exists',
        'invalid_email' => 'Email id not exists',
        'pass_suc' => 'An email has been sent to you on your email address with new password.',
        'pass_failed' => 'Issue in recovery try again',
        'captcha' => 'Invalid Captcha.',
        'applicationsave' => 'Your application is submitted.',
        'invaliduser' => 'Invalid job id applied for the user',
        'ageLimit' => 'As your age exceeds, you are not eligible to apply for this post',
        'cron_stats' => 'You payment is still under process kindly check after sometime.'
    );
    private $validateType = array(
        "alphanumeric" => "A-Za-z0-9\n\r\&\/\-\(\)\,\.",
        "mobilenumber" => "\d{10}",
        "content" => "^\\\"<>|",
        "pincode" => "\d{6}",
        "yearmonth" => "[0-9]{4}-(0[1-9]|1[0-2])",
        "datetime" => "[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) (2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]",
        "alphaspace"=>"a-zA-Z\s"
    );

    public function getHrJobs($id = '') {
        if ($id != '' && !empty($id) && $id != 0) {
            $result = $this->findAll($this->tableName, ' id = ' . $id . "");
            if ($result)
                return $result;
        }else {
            $result = $this->findAll($this->tableName);
            if ($result)
                return $result;
        }
    }

    public function addHrJobs() {
        $dataArr = $this->addHrJobsData();
        if (empty($dataArr)) {
            $this->setError($this->msg['mandatory']);
            return false;
        }
        if(!$this->validatePlan($dataArr)){
            return false;
        }
//        $res = $this->findAll($this->tableName, "is_deleted='0' and title='" . $dataArr['title'] . "'");
//        if (!empty($res) && count($res) > 0) {
//            $this->setError($this->msg['alredyexist']);
//            return false;
//        }
        $dataArr['subdate'] = date('Y-m-d H:i:s');
        $dataArr['posted_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['posted_date'] = date('Y-m-d H:i:s');
        $dataArr['posted_from'] = 'Web';
        if (!$this->insert($this->tableName, $dataArr)) {
            $this->setError($this->msg['failed']);
            return false;
        }
        $insertid = $this->getInsertID();
        $this->logMsg("New HR Jobs added. ID : " . $insertid . ".");
        return true;
    }

    public function addHrJobsData() {
        $dataArr = array();
        if (isset($_POST['submit']) && $_POST['submit'] == 'Submit') {
            $dataArr['session_id'] = isset($_POST['session_id']) ? $_POST['session_id'] : '';
            $dataArr['job_order'] = isset($_POST['job_order']) ? $_POST['job_order'] : '';
            $dataArr['job_type'] = isset($_POST['job_type']) ? $_POST['job_type'] : '';
            $dataArr['title'] = isset($_POST['title']) ? $_POST['title'] : '';
            $dataArr['code'] = isset($_POST['code']) ? $_POST['code'] : '';
            $dataArr['remuneration'] = isset($_POST['remuneration']) ? $_POST['remuneration'] : '';
            $dataArr['agelimit'] = isset($_POST['agelimit']) ? $_POST['agelimit'] : '';
            $dataArr['agtilldate'] = isset($_POST['agtilldate']) ? $_POST['agtilldate'] : '';
            $dataArr['experience'] = isset($_POST['experience']) ? $_POST['experience'] : '';
            $dataArr['totaljobs'] = isset($_POST['totaljobs']) ? $_POST['totaljobs'] : '';
            $dataArr['category'] = rtrim(implode(',', $_POST['category']), ',');
//            $dataArr['published_date'] = isset($_POST['published_date']) ? $_POST['published_date'] : '';
//            $dataArr['unpublished_date'] = isset($_POST['unpublished_date']) ? $_POST['unpublished_date'] : '';
            $dataArr['status'] = isset($_POST['status']) ? $_POST['status'] : '';
        } else {
            $dataArr['job_order'] = '';
            $dataArr['job_type'] = '';
            $dataArr['title'] = '';
            $dataArr['code'] = '';
            $dataArr['remuneration'] = '';
            $dataArr['agelimit'] = '';
            $dataArr['agtilldate'] = '';
            $dataArr['experience'] = '';
            $dataArr['totaljobs'] = '';
            $dataArr['category'] = '';
//            $dataArr['published_date'] = '';
//            $dataArr['unpublished_date'] = '';
            $dataArr['status'] = '';
        }
        return $dataArr;
    }

    public function editHrJobs($id) {
        $dataArr = $this->addHrJobsData();
        $checkID = $this->getHrJobs($id);
        if (empty($checkID)) {
            $this->setError($this->msg['invalid']);
            return false;
        }
        if (empty($dataArr)) {
            $this->setError($this->msg['mandatory']);
            return false;
        }
//        $res = $this->findAll($this->tableName, "is_deleted='0' and title='" . $dataArr['title'] . "' and id!='" . $id . "'");
//        if (!empty($res) && count($res) > 0) {
//            $this->setError($this->msg['alredyexist']);
//            return false;
//        }
        if (!$this->update($this->tableName, $dataArr, array("id" => $id))) {
            $this->setError($this->msg['failed']);
            return false;
        }
        $this->logMsg("Jobs ID : " . $id . " in HR Jobs Portal has been updated");
        return true;
    }

    public function getListofAllJobs($id = '') {
        $query = "select a.*,b.published_date,b.unpublished_date from " . $this->tableName . " a," . TAB_PREFIX . $this->table_name['hrjobs_session'] . " b where a.session_id=b.session_id  and a.status='0' and a.is_deleted='0' and b.status='0' and b.is_deleted='0' and b.published_date<='" . date('Y-m-d H:i:s') . "'  order by a.job_order asc";
        $id = $this->sanitize($id);
        if ($id != '') {
            $query .=" and a.id='" . $id . "'";
        }
        $data = $this->get_results($query);
        $dataArr = array();
        if (!empty($data)) {
            $dataArr['data'] = $data;
            $dataArr['msg'] = $this->msg['suc'];
            $dataArr['status'] = 'suc';
        } else {
            $dataArr['data'] = '';
            $dataArr['msg'] = $this->msg['err'];
            $dataArr['status'] = 'err';
        }
        return json_encode($dataArr);
    }

    public function getListofAppliedJobs($id = '') {
        $query = "select a.*,b.published_date,b.unpublished_date from " . $this->tableName . " a," . TAB_PREFIX . $this->table_name['hrjobs_session'] . " b where a.session_id=b.session_id  and b.published_date<='" . date('Y-m-d H:i:s') . "' ";
        $id = $this->sanitize($id);
        if ($id != '') {
            $query .=" and a.id='" . $id . "'";
        }
        $data = $this->get_results($query);
        $dataArr = array();
        if (!empty($data)) {
            $dataArr['data'] = $data;
            $dataArr['msg'] = $this->msg['suc'];
            $dataArr['status'] = 'suc';
        } else {
            $dataArr['data'] = '';
            $dataArr['msg'] = $this->msg['err'];
            $dataArr['status'] = 'err';
        }
        return json_encode($dataArr);
    }

    public function registrationHrJobs() {
        $dataArr = $this->regisHrJobsData();
        if (empty($dataArr)) {
            $this->setError($this->msg['mandatory']);
            return false;
        }
        if (!$this->validateHrJobs($dataArr)) {
            return false;
        }
        if ($dataArr['password'] != $_POST['form_repassword']) {
            $this->setError($this->msg['passwordnotmatched']);
            return false;
        }
        $from = new DateTime($dataArr['dob']);
        $to = new DateTime('today');
        $age = $from->diff($to)->y;
        if ($age < 18) {
            $this->setError('Age should be greater than 18');
            return false;
        }
        if ($dataArr['password'] != $_POST['form_repassword']) {
            $this->setError($this->msg['passwordnotmatched']);
            return false;
        }
        if (!isset($_POST['captcha']) || $_SESSION["captchaCode"] != $_POST['captcha']) {
            $this->setError($this->msg['captcha']);
            return false;
        }
        $password = $dataArr['password'];
        $dataArr['password'] = $this->password_encrypt($dataArr['password']);
        $dataArr['dateofcreation'] = date('Y-m-d H:i:s');
        $res = $this->findAll(TAB_PREFIX . $this->table_name['registration'], "email='" . $dataArr['email'] . "' and is_deleted='0'");
        if (!empty($res)) {
            $this->setError($this->msg['alexist']);
            return false;
        }
        if (!$this->insert(TAB_PREFIX . $this->table_name['registration'], $dataArr)) {
            $this->setError($this->msg['failed']);
            return false;
        }
        $id = $this->getInsertID();
        $this->clientlogMsg("HR Jobs New User Registration : User ID " . $id);
        $this->registrationHrJobsMail($dataArr, $id, $password);
        $_SESSION['hrjobs_user_id'] = $id;
        $_SESSION['hrjobs_user_name'] = $dataArr['first_name'] . " " . $dataArr['last_name'];
        if (isset($_POST['jobid']) && $_POST['jobid'] != '') {
            $dataArr1['userid'] = $id;
            $dataArr1['job_id'] = $_POST['jobid'];
            $dataArr1['applieddatetime'] = date('Y-m-d H:i:s');
            $chkDt = $this->get_results("select * from ".TAB_PREFIX.$this->table_name['hrjobapply']. " where userid='".$id."' and job_id='".$this->sanitize($_POST['jobid'])."'");
            if(empty($chkDt))
            {
                $this->insert(TAB_PREFIX . $this->table_name['hrjobapply'], $dataArr1);
                $this->clientlogMsg("HR Jobs Applied : User ID " . $id . " applied for Job ID " . $_POST['jobid']);
            }
            $this->redirect(PROJECT_URL . "/?page=hrjobs_registration_full&uid=" . $id . "&jobid=" . $_POST['jobid']);
            exit();
        }
        $this->redirect(PROJECT_URL . "/?page=hrjobs_appliedlist&uid=" . $id);
        exit();
    }

    public function registrationHrJobsMail($dataArr, $id, $password) {
        if (!empty($dataArr)) {
            $module = "HR Jobs New Registration";
            $module_msg = "New Registration : " . $id . " mail send to registered user";
            $to = $dataArr['email'];
            $cc = '';
            $bcc = 'rishap07@gmail.com';
            $subject = "New job account on ILBS.";
            $message = "<table width='100%'>
  <tbody><tr>
    <td colspan='2'><img src='" . PROJECT_URL . "/images/logo.png' alt='ILBS' width='479' height='86'></td>
  </tr>
  <tr>
    <td colspan='2'>Dear " . $dataArr['form_title'] . " " . $dataArr['first_name'] . " " . $dataArr['last_name'] . ",</td>
  </tr>
  <tr>
    <td colspan='2'>Thank you for showing interest in working with Institute of Liver &amp; Biliary Sciences, New Delhi.<br><br>
Please found your credentials below for future reference.</td>
  </tr>
  <tr>
    <td width='15%'><strong>Username :</strong></td>
    <td width='85%'>" . $dataArr['email'] . "</td>
  </tr>
  <tr>
    <td width='15%'><strong>Password :</strong></td>
    <td width='85%'>" . $password . "</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td colspan='2'>Regards<br>
      HR<br>
      ILBS, New Delhi<br>
    </td>
  </tr>
</tbody></table>
";
            $from = "hr@ilbs.in";
            $this->email_schedule($module, $module_msg, $to, $cc, $bcc, $subject, $message, $from);
        }
    }

    public function regisHrJobsData() {
        $dataArr = array();
        if (isset($_POST['form_submit']) && $_POST['form_submit'] == 'Submit') {
            $dataArr['form_title'] = isset($_POST['form_title']) ? $_POST['form_title'] : '';
            $dataArr['first_name'] = isset($_POST['form_first_name']) ? $_POST['form_first_name'] : '';
            $dataArr['last_name'] = isset($_POST['form_last_name']) ? $_POST['form_last_name'] : '';
            $dataArr['dob'] = isset($_POST['form_dob']) ? $_POST['form_dob'] : '';
            $dataArr['email'] = isset($_POST['form_email']) ? $_POST['form_email'] : '';
            $dataArr['mobile'] = isset($_POST['form_mobile']) ? $_POST['form_mobile'] : '';
            $dataArr['password'] = isset($_POST['form_password']) ? $_POST['form_password'] : '';
        } else {
            $dataArr['first_name'] = '';
            $dataArr['last_name'] = '';
            $dataArr['dob'] = '';
            $dataArr['email'] = '';
            $dataArr['mobile'] = '';
            $dataArr['password'] = '';
        }
        return $dataArr;
    }

    public function validateHrJobs($dataArr) {
        $rules = array(
            'form_title' => 'required|#|lable_name:Title',
            'first_name' => 'required||pattern:/^[' . $this->validateType['alphaspace'] . ']+$/|#|lable_name:First Name',
            'last_name' => 'pattern:/^[' . $this->validateType['alphaspace'] . ']+$/|#|lable_name:Last Name',
            'dob' => 'required||date|#|lable_name:Date of Birth',
            'email' => 'required||email|#|lable_name:Email',
            'mobile' => 'required||numeric||pattern:/^' . $this->validateType['mobilenumber'] . '$/|#|lable_name:Mobile Number ',
            'password' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/||min:8||max:20|#|lable_name:Password',
        );
        $valid = $this->vali_obj->validate($dataArr, $rules);
        if ($valid->hasErrors()) {
            $err_arr = $valid->allErrors();
            $this->setError($err_arr);
            $valid->clearMessages();
            return false;
        }
        return true;
    }

    public function getJobsResults($jobid, $uid) {
        if ($jobid != '' and $uid != '') {
            //$query = "select * from ".TAB_PREFIX."hrjobs_apply a, ".TAB_PREFIX."hrjobs_registration b,".TAB_PREFIX."hrjobs c where a.userid=b.id and a.job_id=c.id and b.status='0' and b.is_deleted='0' and a.job_id='".$this->sanitize($jobid)."' and a.userid='".$this->sanitize($uid)."'";

            $query = "select * from " . TAB_PREFIX . "hrjobs_apply a  left join " . TAB_PREFIX . "hrjobs_registration b on a.userid=b.id left join " . TAB_PREFIX . "hrjobs c on a.job_id=c.id left join " . TAB_PREFIX . "hrjobs_registration_final d on a.id=d.registration_id left join " . TAB_PREFIX . "hrjobs_session e on c.session_id=e.session_id  where b.is_deleted='0'  and a.job_id='" . $this->sanitize($jobid) . "' and a.userid='" . $this->sanitize($uid) . "'";

            return $this->get_results($query);
        }
        return false;
    }

    public function getJobs($jobid, $uid) {
        if ($jobid != '' and $uid != '') {
            //$query = "select * from ".TAB_PREFIX."hrjobs_apply a, ".TAB_PREFIX."hrjobs_registration b,".TAB_PREFIX."hrjobs c where a.userid=b.id and a.job_id=c.id and b.status='0' and b.is_deleted='0' and a.job_id='".$this->sanitize($jobid)."' and a.userid='".$this->sanitize($uid)."'";

            $query = "select * from " . TAB_PREFIX . "hrjobs_apply a ," . TAB_PREFIX . "hrjobs_registration b , " . TAB_PREFIX . "hrjobs c  , " . TAB_PREFIX . "hrjobs_registration_final d  where b.is_deleted='0' and a.job_id='" . $this->sanitize($jobid) . "' and a.userid='" . $this->sanitize($uid) . "' and  a.userid=b.id and a.job_id=c.id and  a.id=d.registration_id";

            return $this->get_results($query);
        }
        return false;
    }

    //
    //
    //START : NEW CODE FOR STEP WISE : SAVE : STEP 1
    //
    //
    public function saveHrJobFinalStep1() {
        $jobid = $this->sanitize($_GET['jobid']);
        $uid = $this->sanitize($_SESSION['hrjobs_user_id']);
        $regid = $this->findAll(TAB_PREFIX . $this->table_name['hrjobapply'], "userid='" . $uid . "' and job_id='" . $jobid . "'");

        if (empty($regid)) {
            $this->setError($this->msg['invaliduser']);
            return false;
        }
        $dataArr = $this->saveHrJobFinalStep1Data();
        if (empty($dataArr)) {
            $this->setError($this->msg['mandatory']);
            return false;
        }
        if ($dataArr == false) {
            $this->setError($this->msg['imagesmsg']);
            return false;
        }

        $checkInsert = $this->getJobsResults($jobid, $uid);

        $dataArr['registration_id'] = $regid[0]->id;
//        $dataArr['form_step'] = '1';
        
        if (isset($checkInsert[0]) && !$checkInsert[0]->registration_final_id > 0) {
            if($checkInsert[0]->ref_id=='')
            {
                $rand_string = $this->generateRandomString('6', TAB_PREFIX . "hrjobs_registration_final", 'ref_id', 'HRJOBS#');
                $dataArr['ref_id'] = $rand_string;
            }
            if (!$this->insert(TAB_PREFIX . $this->table_name['registration_final'], $dataArr)) {
                $this->setError($this->msg['failed']);
                return false;
            }
            return true;
        } else {
            if (isset($checkInsert[0]->registration_final_id) && $checkInsert[0]->registration_final_id > 0) {
                if($checkInsert[0]->ref_id=='')
                {
                    $rand_string = $this->generateRandomString('6', TAB_PREFIX . "hrjobs_registration_final", 'ref_id', 'HRJOBS#');
                    $dataArr['ref_id'] = $rand_string;
                }
                if (!$this->update(TAB_PREFIX . $this->table_name['registration_final'], $dataArr, array("registration_final_id" => $checkInsert[0]->registration_final_id))) {
                    $this->setError($this->msg['failed']);
                    return false;
                }
                $this->setSuccess($this->msg['saved']);
                return true;
            } else {
                $this->setError($this->msg['failed']);
                return false;
            }
        }
    }

    public function saveHrJobFinalStep1Data() {
        $dataArr = array();
        if ((isset($_POST['submit']) && $_POST['submit'] == 'Save & Complete later') || (isset($_POST['submit']) && $_POST['submit'] == 'Next Step')) {
            $dataArr['user_id'] = $_SESSION['hrjobs_user_id'];
            $dataArr['marital'] = isset($_POST['marital']) ? $_POST['marital'] : '';
            $dataArr['form_first_name'] = $_POST['form_first_name'];
            $dataArr['form_middle_name'] = $_POST['form_middle_name'];
            $dataArr['form_last_name'] = $_POST['form_last_name'];
            $dataArr['add_same'] = isset($_POST['add_same']) ? $_POST['add_same'] : '';

            $dataArr['form_permanent_address'] = $_POST['form_permanent_address'];
            $dataArr['form_permanent_country'] = $_POST['form_permanent_country'];
            $dataArr['form_permanent_state'] = $_POST['form_permanent_state'];
            $dataArr['form_permanent_city'] = $_POST['form_permanent_city'];
            $dataArr['form_permanent_pin'] = $_POST['form_permanent_pin'];
            $dataArr['form_postal_address'] = $_POST['form_postal_address'];
            $dataArr['form_postal_country'] = $_POST['form_postal_country'];
            $dataArr['form_postal_state'] = $_POST['form_postal_state'];
            $dataArr['form_postal_city'] = $_POST['form_postal_city'];
            $dataArr['form_postal_pin'] = $_POST['form_postal_pin'];
            $dataArr['form_mobile'] = $_POST['form_mobile'];
            $dataArr['form_telephonenumber'] = $_POST['form_telephonenumber'];
            $dataArr['form_email'] = $_POST['form_email'];
            $dataArr['form_dob'] = $_POST['form_dob'];
            $dataArr['form_ageproof'] = isset($_POST['form_ageproof1']) ? $_POST['form_ageproof1'] : '';
            $dataArr['form_ageproof_url'] = isset($_POST['form_ageproof_url']) ? $_POST['form_ageproof_url'] : '';
            if ($_FILES['form_ageproof']['name'] != '') {
                $form_ageproof = $this->imageUpload($_FILES['form_ageproof'], 'dob', $this->imagePdfExt, 500000, 'File should be between 100KB to 500KB', 'Age Proof ', 100000);
                if ($form_ageproof == FALSE) {
                    return false;
                } else {
                    $dataArr['form_ageproof'] = $form_ageproof;
                }
            }
            $dataArr['photograph'] = isset($_POST['application_photograph1']) ? $_POST['application_photograph1'] : '';
            if ($_FILES['application_photograph']['name'] != '') {
                $photo = $this->imageUpload($_FILES['application_photograph'], 'photograph', $this->imageExt, 150000, 'File size 50KB to 150 KB', 'Photograph ', 50000);
                if ($photo == FALSE) {
                    return false;
                } else {
                    $dataArr['photograph'] = $photo;
                }
            }
            $dataArr['form_signature'] = isset($_POST['form_signature1']) ? $_POST['form_signature1'] : '';
            if ($_FILES['form_signature']['name'] != '') {
                $form_signature = $this->imageUpload($_FILES['form_signature'], 'signature', $this->imageExt, 20000, 'File size 10KB to 20 KB', 'Signature ', 10000);
                if ($form_signature == FALSE) {
                    return false;
                } else {
                    $dataArr['form_signature'] = $form_signature;
                }
            }
            $dataArr['form_citizen'] = isset($_POST['form_citizen']) ? $_POST['form_citizen'] : '';
            $dataArr['form_indian_origin'] = isset($_POST['form_indian_origin']) ? $_POST['form_indian_origin'] : "";
            $dataArr['form_dual_citizenship'] = isset($_POST['form_dual_citizenship']) ? $_POST['form_dual_citizenship'] : '';
            $dataArr['form_category'] = isset($_POST['form_category']) ? $_POST['form_category'] : '';
			$dataArr['form_category_file_url'] = isset($_POST['form_category_file_url']) ? $_POST['form_category_file_url'] : '';
            $dataArr['form_category_file'] = isset($_POST['form_category_file1']) ? $_POST['form_category_file1'] : '';
            if ($_FILES['form_category_file']['name'] != '') {
                $form_category_file = $this->imageUpload($_FILES['form_category_file'], 'category', $this->imagePdfExt, 500000, 'File should be between 100KB to 500KB', 'Category ', 100000);
                if ($form_category_file == FALSE) {
                    return false;
                } else {
                    $dataArr['form_category_file'] = $form_category_file;
                }
            }
            $dataArr['form_pwd'] = $_POST['form_pwd'];
            $dataArr['form_pwd_file'] = isset($_POST['form_pwd_file1']) ? $_POST['form_pwd_file1'] : '';
            $dataArr['form_pwd_file_url'] = isset($_POST['form_pwd_file_url']) ? $_POST['form_pwd_file_url'] : '';
            if ($_FILES['form_pwd_file']['name'] != '') {
                $form_pwd_file = $this->imageUpload($_FILES['form_pwd_file'], 'pwd', $this->imagePdfExt, 500000, 'File should be between 100KB to 500KB', 'PWD Attachment ', 100000);
                if ($form_pwd_file == FALSE) {
                    return false;
                } else {
                    $dataArr['form_pwd_file'] = $form_pwd_file;
                }
            }
            $dataArr['form_exserviceman'] = isset($_POST['form_exserviceman']) ? $_POST['form_exserviceman'] : '';
            $dataArr['form_exserviceman_year'] = isset($_POST['form_exserviceman_year']) ? $_POST['form_exserviceman_year'] : '';
            $dataArr['form_guardian'] = isset($_POST['form_guardian']) ? $_POST['form_guardian'] : '';
            $dataArr['form_guardian_fname'] = $_POST['form_guardian_fname'];
            $dataArr['form_guardian_mname'] = $_POST['form_guardian_mname'];
            $dataArr['form_guardian_lname'] = $_POST['form_guardian_lname'];
            $dataArr['gender'] = isset($_POST['gender']) ? $_POST['gender'] : '';

            $dataArr['post_applied'] = $this->sanitize($_GET['jobid']);
        }
        return $dataArr;
    }

    //
    //
    //END : NEW CODE FOR STEP WISE : SAVE : STEP 1
    //
    //
    
    
    //
    //
    //START : NEW CODE FOR STEP WISE : SUBMIT : STEP 1 to STEP 2 PROCESS
    //
    //
    public function submitHrJobFinalStep1() {
        $jobid = $this->sanitize($_GET['jobid']);
        $uid = $this->sanitize($_SESSION['hrjobs_user_id']);
        $regid = $this->findAll(TAB_PREFIX . $this->table_name['hrjobapply'], "userid='" . $uid . "' and job_id='" . $jobid . "'");
        if (empty($regid)) 
        {
            $this->setError("Invalid job trying to apply");
            return false;
        }
        if (isset($regid[0]->unpublished_date) && $regid[0]->unpublished_date < date('Y-m-d H:i:s')) 
        {
            $this->setError("The job your are trying to apply is expired");
            return false;
        }

        $jobDetails = $this->findAll(TAB_PREFIX . 'hrjobs', "job_id='" . $jobid . "'");
        if (empty($regid)) {
            $this->setError($this->msg['invaliduser']);
            return false;
        }
        $dateRes = $this->get_results("select b.dob,c.agtilldate,c.job_type from " . TAB_PREFIX . "hrjobs_apply a," . TAB_PREFIX . "hrjobs_registration b," . TAB_PREFIX . "hrjobs c where a.userid=b.id and a.job_id=c.id and a.userid='" . $uid . "' and a.job_id='" . $jobid . "'");
        if (empty($dateRes)) 
        {
            $this->setError($this->msg['invaliduser']);
            return false;
        } 
        else {
            $dob = $dateRes[0]->dob;
            $agetilldate = $dateRes[0]->agtilldate;
            $jobtype = $dateRes['0']->job_type;
            $year_diff = $this->ageCal($dob, $agetilldate);
            if ($year_diff >= 90 && $jobtype == 'nonfaculty') 
            {
                $this->setError($this->msg['ageLimit']);
                return false;
            }
        }
        $dataArr = $this->saveHrJobFinalStep1Data();
        if (empty($dataArr)) {

            $this->setError($this->msg['mandatory']);
            return false;
        }
        if ($dataArr == false) {

            $this->setError($this->msg['imagesmsg']);
            return false;
        }
        $flag = 0;
        if (!$this->validateHrJobFinalStep1($dataArr)) {
            $flag = 1;
        }
        if ($dataArr['form_citizen']== 'no')
        {
            $this->setError("You are not eligible to submit the form.");
            $flag=1;
        }
        if ($flag == 1) {
            return false;
        }

        $age_relax = '';
        if ($dataArr['form_pwd'] < '40') {
            if ($dataArr['form_category'] == 'SC' || $dataArr['form_category'] == 'ST') {
                $age_relax = '5';
                $dataArr['form_amount'] = '100';
            } else if ($dataArr['form_category'] == 'UR') {
                $age_relax = '0';
                $dataArr['form_amount'] = '500';
            } else {
                $age_relax = '3';
                $dataArr['form_amount'] = '500';
            }
        } else if ($dataArr['form_pwd'] >= '40') {
            $dataArr['form_amount'] = '0';
            if ($dataArr['form_category'] == 'SC' || $dataArr['form_category'] == 'ST') {
                $age_relax = '10';
            } else if ($dataArr['form_category'] == 'UR') {
                $age_relax = '5';
            } else {
                $age_relax = '8';
            }
        }
        if ($dataArr['form_exserviceman_year'] > 0 && $dataArr['form_exserviceman'] && $dataArr['form_pwd'] < '40') 
        {
            $age_relax = $age_relax + $dataArr['form_exserviceman_year'];
            $dataArr['form_amount'] = '100';
        } 
        else if ($dataArr['form_exserviceman_year'] > 0 && $dataArr['form_exserviceman'] && $dataArr['form_pwd'] >= '40') 
        {
            $age_relax = $age_relax + $dataArr['form_exserviceman_year'];
        }

        $age_till_date = $this->findAll(TAB_PREFIX . 'hrjobs', " id='" . $jobid . "'", 'agtilldate,agelimit');
        $age_dob = $this->findAll(TAB_PREFIX . 'hrjobs_registration', " id='" . $uid . "'", 'dob');

        $from = new DateTime($age_dob[0]->dob);
        $to = new DateTime($age_till_date[0]->agtilldate);

        $age = $from->diff($to)->y;
        $age_limit = $age_till_date[0]->agelimit + $age_relax;

        if ($age > $age_limit) {
            $this->setError('You are not eligible to apply this job.Age limit exceeded');
            return false;
        }


        $jobid = $this->sanitize($_GET['jobid']);
        $uid = $this->sanitize($_SESSION['hrjobs_user_id']);
        $checkInsert = $this->getJobs($jobid, $uid);
        $dataArr['registration_id'] = $regid[0]->id;
        $dataArr['form_step'] = '1';
        
        if (empty($checkInsert)) {
            if(!isset($checkInsert[0]->ref_id) || $checkInsert[0]->ref_id=='')
            {
                $rand_string = $this->generateRandomString('6', TAB_PREFIX . "hrjobs_registration_final", 'ref_id', 'HRJOBS#');
                $dataArr['ref_id'] = $rand_string;
            }
            if (!$this->insert(TAB_PREFIX . $this->table_name['registration_final'], $dataArr)) {
                $this->setError($this->msg['failed']);
                return false;
            }
            $this->redirect(PROJECT_URL . "/?page=hrjobs_registration_full_step2&uid=" . $uid . "&jobid=" . $jobid);
            exit();
            return true;
        } else {
            if (isset($checkInsert[0]->registration_final_id) && $checkInsert[0]->registration_final_id > 0) {
                if($checkInsert[0]->ref_id=='')
                {
                    $rand_string = $this->generateRandomString('6', TAB_PREFIX . "hrjobs_registration_final", 'ref_id', 'HRJOBS#');
                    $dataArr['ref_id'] = $rand_string;
                }
                if (!$this->update(TAB_PREFIX . $this->table_name['registration_final'], $dataArr, array("registration_final_id" => $checkInsert[0]->registration_final_id))) {
                    $this->setError($this->msg['failed']);
                    return false;
                }
                $this->redirect(PROJECT_URL . "/?page=hrjobs_registration_full_step2&uid=" . $uid . "&jobid=" . $jobid);
                exit();
                return true;
            } else {
                $this->setError($this->msg['failed']);
                return false;
            }
        }
    }

    public function validateHrJobFinalStep1($dataArr) {
        $rules = array(
            'photograph' => 'required|#|lable_name:Photgraph',
            'form_first_name' => 'required||pattern:/^[' . $this->validateType['alphaspace'] . ']*$/|#|lable_name:First Name',
            'form_middle_name' => 'pattern:/^[' . $this->validateType['alphaspace'] . ']*$/|#|lable_name:Middle Name',
            'form_last_name' => 'required||pattern:/^[' . $this->validateType['alphaspace'] . ']*$/|#|lable_name:Last Name',
            'gender' => 'required|#|lable_name:Gender',
            'marital' => 'required|#|lable_name:Marital Status',
            'form_permanent_address' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Permanent Address',
            'form_permanent_country' => 'required||pattern:/^[' . $this->validateType['alphanumeric'] . ']*$/|#|lable_name:Permanent Country',
            'form_permanent_state' => 'required||pattern:/^[' . $this->validateType['content'] . ']*$/|#|lable_name:Permanent State',
            'form_permanent_city' => 'required||pattern:/^[' . $this->validateType['content'] . ']*$/|#|lable_name:Permanent City',
            'form_permanent_pin' => 'pattern:/^' . $this->validateType['pincode'] . '$/|#|lable_name:Permanent Pin',
            'form_postal_address' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Postal Address',
            'form_postal_country' => 'required||pattern:/^[' . $this->validateType['alphanumeric'] . ']*$/|#|lable_name:Postal Country',
            'form_postal_state' => 'required||pattern:/^[' . $this->validateType['content'] . ']*$/|#|lable_name:Postal State',
            'form_postal_city' => 'required||pattern:/^[' . $this->validateType['content'] . ']*$/|#|lable_name:Postal City',
            'form_postal_pin' => 'pattern:/^' . $this->validateType['pincode'] . '$/|#|lable_name:Postal Pin',
            'form_mobile' => 'required||pattern:/^' . $this->validateType['mobilenumber'] . '$/|#|lable_name:Mobile Number',
            'form_telephonenumber' => 'numeric||min:6||max:10|#|lable_name:Telephone Number',
            'form_email' => 'required||email|#|lable_name:Email',
            'form_dob' => 'required||date|#|lable_name:Date of Birth',
            
            'form_citizen' => 'required|#|lable_name:Citizenship',
            'form_category' => 'required|#|lable_name:Category',
            'form_guardian' => 'required|#|lable_name:Guardian',
            'form_guardian_fname' => 'required||pattern:/^[' . $this->validateType['alphanumeric'] . ']*$/|#|lable_name:Guardian First Name',
            'form_guardian_mname' => 'pattern:/^[' . $this->validateType['alphanumeric'] . ']*$/|#|lable_name:Guardian Middle Name',
            'form_guardian_lname' => 'required||pattern:/^[' . $this->validateType['alphanumeric'] . ']*$/|#|lable_name:Guardian Last Name',
            'form_signature' => 'required|#|lable_name:Signature',
            'form_exserviceman_year' => 'numeric||min:0||max:2|#|lable_name:Ex Serviceman Experience'
        );
        $valid = $this->vali_obj->validate($dataArr, $rules);
        if ($valid->hasErrors()) {
            $err_arr = $valid->allErrors();
            $this->setError($err_arr);
            $valid->clearMessages();
            return false;
        }
        return true;
    }

    //
    //
    //END : NEW CODE FOR STEP WISE : SUBMIT : STEP 1 to STEP 2 PROCESS
    //
    //
    
    //
    //
    //START : NEW CODE FOR STEP WISE : SAVE : STEP 2
    //
    //
    public function saveHrJobFinalStep2() {
        $jobid = $this->sanitize($_GET['jobid']);
        $uid = $this->sanitize($_SESSION['hrjobs_user_id']);
        $checkInsert = $this->getJobsResults($jobid, $uid);
        $dataArr = $this->saveHrJobFinalStep2Data();
        if (empty($dataArr)) {
            $this->setError($this->msg['mandatory']);
            return false;
        }
        $dataArrExp = $this->saveHrJobFinalExperienceData();
        if (!is_array($dataArrExp) && $dataArrExp == false) {
            $this->setError($this->msg['imagesmsg']);
            return false;
        }
        if (isset($checkInsert[0]->job_type) && $checkInsert[0]->job_type != 'nonfaculty') 
        {
            $dataArrPub = $this->saveHrJobFinalPublicationData();
            if ($dataArrPub == false) {
                $this->setError($this->msg['imagesmsg']);
                return false;
            }
        }
        $dataArrQua = $this->saveHrJobFinalQualificationData();
        if ($dataArrQua == false) {
            $this->setError($this->msg['imagesmsg']);
            return false;
        }
        if (!empty($checkInsert)) {
            if (isset($checkInsert[0]->registration_final_id) && $checkInsert[0]->registration_final_id > 0) {
                $this->query($this->begin);
                if (!$this->update(TAB_PREFIX . $this->table_name['registration_final'], $dataArr, array("registration_final_id" => $checkInsert[0]->registration_final_id))) {
                    $this->query($this->rollback);
                    $this->setError($this->msg['failed']);
                    return false;
                } else {
                    if (!empty($dataArrExp)) {

                        for ($x = 0; $x < count($dataArrExp); $x++) {
                            $dataArrExp[$x]['registration_final_id'] = $checkInsert[0]->registration_final_id;
                        }
                        if (isset($checkInsert[0]->registration_final_id) && $checkInsert[0]->registration_final_id != '') {
                            $this->deletData(TAB_PREFIX . $this->table_name['registration_final_experience'], "registration_final_id='" . $checkInsert[0]->registration_final_id . "'");
                        }
                        if (!$this->insertMultiple(TAB_PREFIX . $this->table_name['registration_final_experience'], $dataArrExp)) {
                            $this->query($this->rollback);
                            $this->setError($this->msg['failed']);
                            return false;
                        }
                    }
                    if (isset($checkInsert[0]->job_type) && $checkInsert[0]->job_type != 'nonfaculty') 
                    {
                        if (!empty($dataArrPub)) {
                            for ($x = 0; $x < count($dataArrPub); $x++) {
                                $dataArrPub[$x]['registration_final_id'] = $checkInsert[0]->registration_final_id;
                            }
                            if (isset($checkInsert[0]->registration_final_id) && $checkInsert[0]->registration_final_id != '') {
                                $this->deletData(TAB_PREFIX . $this->table_name['registration_final_publication'], "registration_final_id='" . $checkInsert[0]->registration_final_id . "'");
                            }
                            if (!$this->insertMultiple(TAB_PREFIX . $this->table_name['registration_final_publication'], $dataArrPub)) {

                                $this->query($this->rollback);
                                $this->setError($this->msg['failed']);
                                return false;
                            }
                        }
                    }

                    if (!empty($dataArrQua)) {
                        for ($x = 0; $x < count($dataArrQua); $x++) {
                            $dataArrQua[$x]['registration_final_id'] = $checkInsert[0]->registration_final_id;
                        }
                        if (isset($checkInsert[0]->registration_final_id) && $checkInsert[0]->registration_final_id != '') {
                            $this->deletData(TAB_PREFIX . $this->table_name['registration_final_qualification'], "registration_final_id='" . $checkInsert[0]->registration_final_id . "'");
                        }
                        if (!$this->insertMultiple(TAB_PREFIX . $this->table_name['registration_final_qualification'], $dataArrQua)) {
                            $this->query($this->rollback);
                            $this->setError($this->msg['failed']);
                            return false;
                        }
                    }
                    $this->setSuccess($this->msg['saved']);
                    $this->query($this->commit);
                    $this->redirect(PROJECT_URL . "/?page=hrjobs_registration_full_step2&uid=" . $uid . "&jobid=" . $jobid);
                    return true;
                }
            } else {
                $this->setError($this->msg['failed']);
                return false;
            }
        }
    }

    public function saveHrJobFinalStep2Data() {
        $dataArr = array();
        if ((isset($_POST['submit1']) && $_POST['submit1'] == 'Save & Complete later') || (isset($_POST['submit1']) && $_POST['submit1'] == 'Next Step')) {
            $dataArr['form_experience'] = $_POST['form_experience'];
            $dataArr['form_additional_information'] = $_POST['form_additional_information'];
            $dataArr['form_reference1_name'] = $_POST['form_reference1_name'];
            $dataArr['form_reference1_address'] = $_POST['form_reference1_address'];
            $dataArr['form_reference1_phone'] = $_POST['form_reference1_phone'];
            $dataArr['form_reference2_name'] = $_POST['form_reference2_name'];
            $dataArr['form_reference2_address'] = $_POST['form_reference2_address'];
            $dataArr['form_reference2_phone'] = $_POST['form_reference2_phone'];
        }
        return $dataArr;
    }

    //
    //
    //END : NEW CODE FOR STEP WISE : SAVE : STEP 2
    //
    //
    
    
    //
    //
    //START : NEW CODE FOR STEP WISE : SUBMIT : STEP 2 to STEP 3 PROCESS
    //
    //
    public function submitHrJobFinalStep2() {
        $jobid = $this->sanitize($_GET['jobid']);
        $uid = $this->sanitize($_SESSION['hrjobs_user_id']);
        $checkInsert = $this->getJobs($jobid, $uid);
        $dataArr = $this->saveHrJobFinalStep2Data();
        if (empty($dataArr)) {
            $this->setError($this->msg['mandatory']);
            return false;
        }
        $dataArrExp = $this->saveHrJobFinalExperienceData();
        if ($dataArrExp == false) {
            $this->setError($this->msg['imagesmsg']);
            return false;
        }
        if (isset($checkInsert[0]->job_type) && $checkInsert[0]->job_type != 'nonfaculty')
        {
            $dataArrPub = $this->saveHrJobFinalPublicationData();
        }
        $dataArrQua = $this->saveHrJobFinalQualificationData();
        if ($dataArrQua == false) {
            $this->setError($this->msg['imagesmsg']);
            return false;
        }
        $flag = 0;
        if (!$this->validateHrJobFinalStep2($dataArr)) {
            $flag = 1;
        }
        if (!$this->validateHrJobFinalExperience($dataArrExp)) {
            $flag = 1;
        }
        for($x=0;$x<count($dataArrExp);$x++)
        {
            $flag11=0;
            if($dataArrExp[$x]['form_exp_from']=='' && ( ($dataArrExp[$x]['form_exp_till_date']=='' && $dataArrExp[$x]['form_exp_to']=='') || $dataArrExp[$x]['form_exp_post']!='' || $dataArrExp[$x]['form_exp_teper']!=''|| $dataArrExp[$x]['form_exp_dept']!='' || $dataArrExp[$x]['form_exp_inuni']!='' || $dataArrExp[$x]['form_exp_scto']!=''  || ($dataArrExp[$x]['form_exp_file']!='' && $dataArrExp[$x]['form_exp_file_url']!='') ))
            {
                $flag11=1;
            }
            if($x==0)
            {
                if(($dataArrExp[$x]['form_exp_to']=='' &&  $dataArrExp[$x]['form_exp_till_date']=='') && ($dataArrExp[$x]['form_exp_from']!='' || $dataArrExp[$x]['form_exp_post']!='' || $dataArrExp[$x]['form_exp_teper']!=''|| $dataArrExp[$x]['form_exp_dept']!='' || $dataArrExp[$x]['form_exp_inuni']!='' || $dataArrExp[$x]['form_exp_scto']!=''  || ($dataArrExp[$x]['form_exp_file']!='' && $dataArrExp[$x]['form_exp_file_url']!='') ))
                {
                    $flag11=1;
                }
            }
            if($x>0)
            {
                if(($dataArrExp[$x]['form_exp_to']=='') && ($dataArrExp[$x]['form_exp_from']!='' || $dataArrExp[$x]['form_exp_post']!='' || $dataArrExp[$x]['form_exp_teper']!=''|| $dataArrExp[$x]['form_exp_dept']!='' || $dataArrExp[$x]['form_exp_inuni']!='' || $dataArrExp[$x]['form_exp_scto']!=''  || ($dataArrExp[$x]['form_exp_file']!='' && $dataArrExp[$x]['form_exp_file_url']!='') ))
                {
                    $flag11=1;
                }
            }
            if($dataArrExp[$x]['form_exp_post']=='' && ($dataArrExp[$x]['form_exp_from']!='' || ($dataArrExp[$x]['form_exp_till_date']=='' && $dataArrExp[$x]['form_exp_to']=='') || $dataArrExp[$x]['form_exp_teper']!=''|| $dataArrExp[$x]['form_exp_dept']!='' || $dataArrExp[$x]['form_exp_inuni']!='' || $dataArrExp[$x]['form_exp_scto']!=''  || ($dataArrExp[$x]['form_exp_file']!='' && $dataArrExp[$x]['form_exp_file_url']!='') ))
            {
                $flag11=1;
            }
            if($dataArrExp[$x]['form_exp_teper']=='' && ($dataArrExp[$x]['form_exp_from']!='' ||  ($dataArrExp[$x]['form_exp_till_date']=='' && $dataArrExp[$x]['form_exp_to']=='') || $dataArrExp[$x]['form_exp_post']!=''|| $dataArrExp[$x]['form_exp_dept']!='' || $dataArrExp[$x]['form_exp_inuni']!='' || $dataArrExp[$x]['form_exp_scto']!=''  || ($dataArrExp[$x]['form_exp_file']!='' && $dataArrExp[$x]['form_exp_file_url']!='') ))
            {
                $flag11=1;
            }
            if($dataArrExp[$x]['form_exp_dept']=='' && ($dataArrExp[$x]['form_exp_from']!='' ||  ($dataArrExp[$x]['form_exp_till_date']=='' && $dataArrExp[$x]['form_exp_to']=='') || $dataArrExp[$x]['form_exp_post']!=''|| $dataArrExp[$x]['form_exp_teper']!='' || $dataArrExp[$x]['form_exp_inuni']!='' || $dataArrExp[$x]['form_exp_scto']!=''  || ($dataArrExp[$x]['form_exp_file']!='' && $dataArrExp[$x]['form_exp_file_url']!='') ))
            {
                $flag11=1;
            }
            if($dataArrExp[$x]['form_exp_inuni']=='' && ($dataArrExp[$x]['form_exp_from']!='' ||  ($dataArrExp[$x]['form_exp_till_date']=='' && $dataArrExp[$x]['form_exp_to']=='') || $dataArrExp[$x]['form_exp_post']!=''|| $dataArrExp[$x]['form_exp_teper']!='' || $dataArrExp[$x]['form_exp_dept']!='' || $dataArrExp[$x]['form_exp_scto']!=''  || ($dataArrExp[$x]['form_exp_file']!='' && $dataArrExp[$x]['form_exp_file_url']!='') ))
            {
                $flag11=1;
            }
            if($dataArrExp[$x]['form_exp_scto']=='' && ($dataArrExp[$x]['form_exp_from']!='' ||  ($dataArrExp[$x]['form_exp_till_date']=='' && $dataArrExp[$x]['form_exp_to']=='') || $dataArrExp[$x]['form_exp_post']!=''|| $dataArrExp[$x]['form_exp_teper']!='' || $dataArrExp[$x]['form_exp_dept']!='' || $dataArrExp[$x]['form_exp_inuni']!=''  || ($dataArrExp[$x]['form_exp_file']!='' && $dataArrExp[$x]['form_exp_file_url']!='') ))
            {
                $flag11=1;
            }
            if(($dataArrExp[$x]['form_exp_file']=='' && $dataArrExp[$x]['form_exp_file_url']=='') && ($dataArrExp[$x]['form_exp_from']!='' ||  ($dataArrExp[$x]['form_exp_till_date']=='' && $dataArrExp[$x]['form_exp_to']=='') || $dataArrExp[$x]['form_exp_post']!=''|| $dataArrExp[$x]['form_exp_teper']!='' || $dataArrExp[$x]['form_exp_dept']!='' || $dataArrExp[$x]['form_exp_inuni']!=''  || $dataArrExp[$x]['form_exp_scto']!='' ))
            {
                $flag11=1;
            }
            if($flag11==1)
            {
                $this->setError("Fill complete experience row");
                $flag=1;
            }
        }
        if (isset($checkInsert[0]->job_type) && $checkInsert[0]->job_type != 'nonfaculty') 
        {
            if (!$this->validateHrJobFinalPublication($dataArrPub)) {
                $flag = 1;
            }
        }
        if (isset($checkInsert[0]->job_type) && $checkInsert[0]->job_type != 'nonfaculty')
        {
            for($x=0;$x<count($dataArrPub);$x++)
            {
                $flag11=0;
                if($dataArrPub[$x]['form_pub_nat']=='' && ($dataArrPub[$x]['form_pub_acpt']!='' || $dataArrPub[$x]['form_pub_file']!=''))
                {
                    $flag11=1;
                }
                if($dataArrPub[$x]['form_pub_acpt']=='' && ($dataArrPub[$x]['form_pub_nat']!='' || $dataArrPub[$x]['form_pub_file']!=''))
                {
                    $flag11=1;
                }
                if($dataArrPub[$x]['form_pub_file']=='' && ($dataArrPub[$x]['form_pub_acpt']!='' || $dataArrPub[$x]['form_pub_nat']!=''))
                {
                    $flag11=1;
                }
                if($flag11==1)
                {
                    $this->setError("Fill complete expirence row");
                    $flag=1;
                }
                $flag11=0;

                if($dataArrPub[$x]['form_pub_inter']=='' && ($dataArrPub[$x]['form_pub_int_acpt']!='' || $dataArrPub[$x]['form_pub_int_file']!=''))
                {
                    $flag11=1;
                }
                if($dataArrPub[$x]['form_pub_int_acpt']=='' && ($dataArrPub[$x]['form_pub_inter']!='' || $dataArrPub[$x]['form_pub_int_file']!=''))
                {
                    $flag11=1;
                }
                if($dataArrPub[$x]['form_pub_int_file']=='' && ($dataArrPub[$x]['form_pub_int_acpt']!='' || $dataArrPub[$x]['form_pub_inter']!=''))
                {
                    $flag11=1;
                }
                if($flag11==1)
                {
                    $this->setError("Fill complete Publication National or International row");
                    $flag=1;
                }
            }
        }
        if (!$this->validateHrJobFinalQualification($dataArrQua)) {
            $flag = 1;
        }
        for($x=0;$x<count($dataArrQua);$x++)
        {
            $flag11=0;
            if($dataArrQua[$x]['form_quali_name']=='' && ($dataArrQua[$x]['form_quali_myear']!='' || $dataArrQua[$x]['form_quali_speciality']!='' || $dataArrQua[$x]['form_quali_institute']!='' || $dataArrQua[$x]['form_quali_hosinsti']!='' || $dataArrQua[$x]['form_quali_classdiv']!='' && ($dataArrQua[$x]['form_quali_file']!='' || $dataArrQua[$x]['form_quali_file_url']!='')))
            {
                $flag11=1;
            }
            if($dataArrQua[$x]['form_quali_myear']=='' && ( $dataArrQua[$x]['form_quali_speciality']!='' || $dataArrQua[$x]['form_quali_institute']!='' || $dataArrQua[$x]['form_quali_hosinsti']!='' || $dataArrQua[$x]['form_quali_classdiv']!='' || ($dataArrQua[$x]['form_quali_file']!='' && $dataArrQua[$x]['form_quali_file_url']!='')))
            {
                $flag11=1;
            }
            if($dataArrQua[$x]['form_quali_speciality']=='' && ($dataArrQua[$x]['form_quali_myear']!='' || $dataArrQua[$x]['form_quali_institute']!='' || $dataArrQua[$x]['form_quali_hosinsti']!='' || $dataArrQua[$x]['form_quali_classdiv']!='' || ($dataArrQua[$x]['form_quali_file']!='' && $dataArrQua[$x]['form_quali_file_url']!='')))
            {
                $flag11=1;
            }
            if($dataArrQua[$x]['form_quali_institute']=='' && ($dataArrQua[$x]['form_quali_speciality']!='' || $dataArrQua[$x]['form_quali_speciality']!='' || $dataArrQua[$x]['form_quali_hosinsti']!='' || $dataArrQua[$x]['form_quali_classdiv']!='' || ($dataArrQua[$x]['form_quali_file']!='' && $dataArrQua[$x]['form_quali_file_url']!='')))
            {
                $flag11=1;
            }
            if($dataArrQua[$x]['form_quali_hosinsti']=='' && ($dataArrQua[$x]['form_quali_speciality']!='' || $dataArrQua[$x]['form_quali_institute']!='' || $dataArrQua[$x]['form_quali_institute']!='' || $dataArrQua[$x]['form_quali_classdiv']!='' || ($dataArrQua[$x]['form_quali_file']!='' && $dataArrQua[$x]['form_quali_file_url']!='')))
            {
                $flag11=1;
            }
            if($dataArrQua[$x]['form_quali_classdiv']=='' && ($dataArrQua[$x]['form_quali_speciality']!='' || $dataArrQua[$x]['form_quali_institute']!='' || $dataArrQua[$x]['form_quali_hosinsti']!='' || $dataArrQua[$x]['form_quali_hosinsti']!='' || ($dataArrQua[$x]['form_quali_file']!='' && $dataArrQua[$x]['form_quali_file_url']!='')))
            {
                $flag11=1;
            }
            if(($dataArrQua[$x]['form_quali_file']=='' && $dataArrQua[$x]['form_quali_file_url']=='') && ($dataArrQua[$x]['form_quali_speciality']!='' || $dataArrQua[$x]['form_quali_institute']!='' || $dataArrQua[$x]['form_quali_hosinsti']!='' || $dataArrQua[$x]['form_quali_classdiv']!='' || $dataArrQua[$x]['form_quali_classdiv']!=''))
            {
                $flag11=1;
            }
            if($flag11==1)
            {
                $this->setError("Fill complete Qualification row");
                $flag=1;
            }
        }
        if ($flag == 1) {
            return false;
        }

        if (!empty($checkInsert)) {
            if (isset($checkInsert[0]->registration_final_id) && $checkInsert[0]->registration_final_id > 0) {
                $this->query($this->begin);
                $dataArr['form_step'] = '2';
                if (!$this->update(TAB_PREFIX . $this->table_name['registration_final'], $dataArr, array("registration_final_id" => $checkInsert[0]->registration_final_id))) {
                    $this->query($this->rollback);
                    $this->setError($this->msg['failed']);
                    return false;
                } 
                else 
                {
                    if (!empty($dataArrExp)) 
                    {
                        $d1 = 0;
                        $d2 = 0;
                        for ($x = 0; $x < count($dataArrExp); $x++) 
                        {
                            if (isset($dataArrExp[$x]['form_exp_till_date']) && $dataArrExp[$x]['form_exp_till_date'] != '') 
                            {
                                $d1 += strtotime(date('Y-m-d'));
                            }
                            else 
                            {
                                $d1 += strtotime($dataArrExp[$x]['form_exp_to'] . "+1 days");
                            }
                            $d2 += strtotime($dataArrExp[$x]['form_exp_from']);
                        }

                        $to = new DateTime(date('d-m-Y H:i:s', $d1));
                        $from = new DateTime(date('d-m-Y H:i:s', $d2));
                        $diff1 = $from->diff($to);
                        $diff2 = $diff1->y;
                        $diff_month = $diff1->m;
                        $diff_day = $diff1->d;
                        $year_msg = '';
                        $month_msg = '';
//                        $difm = $diff_month / 12;
//                        $diff2 = $diff2 + $difm;
                        if ($checkInsert[0]->experience > $diff2) {
                            if ($diff2 <= 1) {
                                $year_msg = $diff2 . " year ";
                            } else {
                                $year_msg = $diff2 . " years ";
                            }
                            if ($diff_month == 1) {
                                $month_msg = " and " . $diff_month . ' month ';
                            } else {
                                $month_msg = " and " . $diff_month . ' months ';
                            }
                            //$this->setError('Did not meet minimum experience required. Total experience required: '.$checkInsert[0]->experience.' , Your Experience: '. $year_msg.$month_msg);
                            $this->setError("Minimum Experience required for this Post is " . $checkInsert[0]->experience . ". Sorry, You do not meet the specified criteria.");
                            return false;
                        }
                        for ($x = 0; $x < count($dataArrExp); $x++) {
                            $dataArrExp[$x]['registration_final_id'] = $checkInsert[0]->registration_final_id;
                        }
                        if (isset($checkInsert[0]->registration_final_id) && $checkInsert[0]->registration_final_id != '') {
                            $this->deletData(TAB_PREFIX . $this->table_name['registration_final_experience'], "registration_final_id='" . $checkInsert[0]->registration_final_id . "'");
                        }
                        if (!$this->insertMultiple(TAB_PREFIX . $this->table_name['registration_final_experience'], $dataArrExp)) {
                            $this->query($this->rollback);
                            $this->setError($this->msg['failed']);
                            return false;
                        }
                    }
                    if (isset($checkInsert[0]->job_type) && $checkInsert[0]->job_type != 'nonfaculty') {
                        if (!empty($dataArrPub)) {
                            for ($x = 0; $x < count($dataArrPub); $x++) {
                                $dataArrPub[$x]['registration_final_id'] = $checkInsert[0]->registration_final_id;
                            }
                            if (isset($checkInsert[0]->registration_final_id) && $checkInsert[0]->registration_final_id != '') {
                                $this->deletData(TAB_PREFIX . $this->table_name['registration_final_publication'], "registration_final_id='" . $checkInsert[0]->registration_final_id . "'");
                            }
                            if (!$this->insertMultiple(TAB_PREFIX . $this->table_name['registration_final_publication'], $dataArrPub)) {
                                $this->query($this->rollback);
                                $this->setError($this->msg['failed']);
                                return false;
                            }
                        }
                    }
                    if (!empty($dataArrQua)) {
                        for ($x = 0; $x < count($dataArrQua); $x++) {
                            $dataArrQua[$x]['registration_final_id'] = $checkInsert[0]->registration_final_id;
                        }
                        if (isset($checkInsert[0]->registration_final_id) && $checkInsert[0]->registration_final_id != '') {
                            $this->deletData(TAB_PREFIX . $this->table_name['registration_final_qualification'], "registration_final_id='" . $checkInsert[0]->registration_final_id . "'");
                        }
                        if (!$this->insertMultiple(TAB_PREFIX . $this->table_name['registration_final_qualification'], $dataArrQua)) {
                            $this->query($this->rollback);
                            $this->setError($this->msg['failed']);
                            return false;
                        }
                    }
                    $this->setSuccess($this->msg['saved']);
                    $this->query($this->commit);
                    $this->redirect(PROJECT_URL . "/?page=hrjobs_registration_full_step3&uid=" . $uid . "&jobid=" . $jobid);
                    exit();
                }
            } else {
                $this->setError($this->msg['failed']);
                return false;
            }
        }
    }

    public function validateHrJobFinalStep2($dataArr) {
        $rules = array(
            'form_experience' => 'required||numeric|#|lable_name:Total Experience',
            'form_additional_information' => 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Additional Information',
            'form_reference1_name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Reference 1st Name',
            'form_reference1_address' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Reference 1st Address',
            'form_reference1_phone' => 'required||pattern:/^[' . $this->validateType['mobilenumber'] . ']*$/|#|lable_name:Reference 1st Phone',
            'form_reference2_name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Reference 2nd Name',
            'form_reference2_address' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Reference 2nd Address',
            'form_reference2_phone' => 'required||pattern:/^[' . $this->validateType['mobilenumber'] . ']*$/|#|lable_name:Reference 2nd Phone'
        );
        $valid = $this->vali_obj->validate($dataArr, $rules);
        if ($valid->hasErrors()) {
            $err_arr = $valid->allErrors();
            $this->setError($err_arr);
            $valid->clearMessages();
            return false;
        }
        return true;
    }

    //
    //
    //END : NEW CODE FOR STEP WISE : SUBMIT : STEP 2 to STEP 3 PROCESS
    //
    //
    
    
     //
    //
    //START : NEW CODE FOR STEP WISE : SAVE : STEP 2
    //
    //
    public function saveHrJobFinalStep3() {
        $dataArr = $this->saveHrJobFinalStep3Data();
        if (empty($dataArr)) {
            $this->setError($this->msg['mandatory']);
            return false;
        }
        $jobid = $this->sanitize($_GET['jobid']);
        $uid = $this->sanitize($_SESSION['hrjobs_user_id']);
        $checkInsert = $this->getJobsResults($jobid, $uid);
        if (!empty($checkInsert)) {
            if (isset($checkInsert[0]->registration_final_id) && $checkInsert[0]->registration_final_id > 0) {
                if (!$this->update(TAB_PREFIX . $this->table_name['registration_final'], $dataArr, array("registration_final_id" => $checkInsert[0]->registration_final_id))) {
                    $this->setError($this->msg['failed']);
                    return false;
                } else {
                    $this->setSuccess($this->msg['saved']);
                    return true;
                }
            } else {
                $this->setError($this->msg['failed']);
                return false;
            }
        }
    }

    public function saveHrJobFinalStep3Data() {
        $dataArr = array();
        if ((isset($_POST['submit2']) && $_POST['submit2'] == 'Save') || (isset($_POST['submit2']) && $_POST['submit2'] == 'Submit')) {

            $dataArr['form_payment_method'] = $_POST['form_payment_method'];
            $dataArr['form_applied_date'] = $_POST['form_applied_date'];
            $dataArr['form_agreed'] = isset($_POST['form_agreed']) ? $_POST['form_agreed'] : '';
        }
        return $dataArr;
    }

    //
    //
    //END : NEW CODE FOR STEP WISE : SAVE : STEP 3
    //
    //
    
    //
    //
    //START : NEW CODE FOR STEP WISE : SUBMIT : STEP 3 FINAL PROCESS
    //
    //
    public function submitHrJobFinalStep3() {
        $dataArr = $this->saveHrJobFinalStep3Data();
        if (empty($dataArr)) {
            $this->setError($this->msg['mandatory']);
            return false;
        }
        $flag = 0;
        if (!$this->validateHrJobFinalStep3($dataArr)) {
            $flag = 1;
        }
        if ($flag == 1) {
            return false;
        }
        $jobid = $this->sanitize($_GET['jobid']);
        $uid = $this->sanitize($_SESSION['hrjobs_user_id']);
        $checkInsert = $this->getJobs($jobid, $uid);
        $dataArr['form_final_status'] = '1';

        if ($checkInsert[0]->form_amount == '0') {
            $dataArr['payment_status'] = '2';
            $dataArr['response_time'] = date('Y-m-d H:i:s');
            $dataArr['form_final_status'] = '2';
            $dataArr['form_registration_number'] = $this->generateRandomNumber('10', TAB_PREFIX . $this->table_name['registration_final'], 'form_registration_number');
        }
        $dataArr['form_step'] = '3';
        if (!empty($checkInsert)) {
            if (isset($checkInsert[0]->registration_final_id) && $checkInsert[0]->registration_final_id > 0) {
                if (!$this->update(TAB_PREFIX . $this->table_name['registration_final'], $dataArr, array("registration_final_id" => $checkInsert[0]->registration_final_id))) {
                    $this->setError($this->msg['failed']);
                    return false;
                } else {
                    //$this->setSuccess($this->msg['saved']);
                    if ($checkInsert[0]->form_amount > 0) {
                        $_SESSION['id'] = $checkInsert[0]->registration_final_id;
                        $_SESSION['register_name'] = $_SESSION['hrjobs_user_name'];
                        $this->redirect(PROJECT_URL . '/?page=payment_hrjobs');
                        exit();
                    } else {
                        $dataArr = $this->findAll(TAB_PREFIX . $this->table_name['registration_final'], "registration_final_id='" . $checkInsert[0]->registration_final_id . "'");
                        $dataArrExp = $this->findAll(TAB_PREFIX . $this->table_name['registration_final_experience'], "registration_final_id='" . $checkInsert[0]->registration_final_id . "'");
                        $dataArrPub = $this->findAll(TAB_PREFIX . $this->table_name['registration_final_publication'], "registration_final_id='" . $checkInsert[0]->registration_final_id . "'");
                        $dataArrQua = $this->findAll(TAB_PREFIX . $this->table_name['registration_final_qualification'], "registration_final_id='" . $checkInsert[0]->registration_final_id . "'");
                        $this->registrationHrJobsFinalMail($dataArr, $dataArrExp, $dataArrPub, $dataArrQua);
                        $this->setSuccess($this->msg['applicationsave']);
                        $this->redirect(PROJECT_URL . "/?page=hrjobs_registration_full_view&uid=" . $uid . "&jobid=" . $jobid);
                        exit();
                    }
                }
            } else {
                $this->setError($this->msg['failed']);
                return false;
            }
        }
    }

    public function validateHrJobFinalStep3($dataArr) {
        $rules = array(
            'form_payment_method' => 'required|#|lable_name:Payment Method Required',
            'form_applied_date' => 'required||date|#|lable_name:Applied Date',
            'form_agreed' => 'required|#|lable_name:Agreed'
        );
        $valid = $this->vali_obj->validate($dataArr, $rules);
        if ($valid->hasErrors()) {
            $err_arr = $valid->allErrors();
            $this->setError($err_arr);
            $valid->clearMessages();
            return false;
        }
        return true;
    }

    //
    //
    //END : NEW CODE FOR STEP WISE : SUBMIT : STEP 3 FINAL PROCESS
    //
    //
    
    
    //
    //
    //OLD CODE SINGLE PAGE : SAVE
    //
    //
    public function saveHrJobFinal() {
        $dataArr = $this->saveHrJobFinalData();

        if (empty($dataArr)) {
            $this->setError($this->msg['mandatory']);
            return false;
        }
        if ($dataArr == false) {
            $this->setError($this->msg['imagesmsg']);
            return false;
        }
        $dataArrExp = $this->saveHrJobFinalExperienceData();
        if ($dataArrExp == false) {
            $this->setError($this->msg['imagesmsg']);
            return false;
        }
        $dataArrPub = $this->saveHrJobFinalPublicationData();
        $dataArrQua = $this->saveHrJobFinalQualificationData();
        if ($dataArrQua == false) {
            $this->setError($this->msg['imagesmsg']);
            return false;
        }
        $dataArr['form_final_status'] = '1';
        $dataArr['form_amount'] = '500';
        $this->query($this->begin);
        $resid = $this->get_results("select registration_final_id from " . TAB_PREFIX . $this->table_name['registration_final'] . " where user_id='" . $this->sanitize($_SESSION['hrjobs_user_id']) . "' and post_applied='" . $this->sanitize($_GET['jobid']) . "'");
        if (isset($resid[0]->registration_final_id) && $resid[0]->registration_final_id != '') {
            $this->deletData(TAB_PREFIX . $this->table_name['registration_final'], "user_id='" . $this->sanitize($_SESSION['hrjobs_user_id']) . "' and post_applied='" . $this->sanitize($_GET['jobid']) . "'");
        }
        if ($this->insert(TAB_PREFIX . $this->table_name['registration_final'], $dataArr)) {
            $id = $this->getInsertID();
            if (!empty($dataArrExp)) {
                for ($x = 0; $x < count($dataArrExp); $x++) {
                    $dataArrExp[$x]['registration_final_id'] = $id;
                }
                if (isset($resid[0]->registration_final_id) && $resid[0]->registration_final_id != '') {
                    $this->deletData(TAB_PREFIX . $this->table_name['registration_final_experience'], "registration_final_id='" . $resid[0]->registration_final_id . "'");
                }
                if (!$this->insertMultiple(TAB_PREFIX . $this->table_name['registration_final_experience'], $dataArrExp)) {
                    $this->query($this->rollback);
                    $this->setError($this->msg['failed']);
                    return false;
                }
            }

            if (!empty($dataArrPub)) {
                for ($x = 0; $x < count($dataArrPub); $x++) {
                    $dataArrPub[$x]['registration_final_id'] = $id;
                }
                if (isset($resid[0]->registration_final_id) && $resid[0]->registration_final_id != '') {
                    $this->deletData(TAB_PREFIX . $this->table_name['registration_final_publication'], "registration_final_id='" . $resid[0]->registration_final_id . "'");
                }
                if (!$this->insertMultiple(TAB_PREFIX . $this->table_name['registration_final_publication'], $dataArrPub)) {
                    $this->query($this->rollback);
                    $this->setError($this->msg['failed']);
                    return false;
                }
            }

            if (!empty($dataArrQua)) {
                for ($x = 0; $x < count($dataArrQua); $x++) {
                    $dataArrQua[$x]['registration_final_id'] = $id;
                }
                if (isset($resid[0]->registration_final_id) && $resid[0]->registration_final_id != '') {
                    $this->deletData(TAB_PREFIX . $this->table_name['registration_final_qualification'], "registration_final_id='" . $resid[0]->registration_final_id . "'");
                }
                if (!$this->insertMultiple(TAB_PREFIX . $this->table_name['registration_final_qualification'], $dataArrQua)) {
                    $this->query($this->rollback);
                    $this->setError($this->msg['failed']);
                    return false;
                }
            }
            $this->setSuccess($this->msg['saved']);
            $this->query($this->commit);
            return true;
        }
        $this->query($this->rollback);
        $this->setError($this->msg['failed']);
        return false;
    }

    public function saveHrJobFinalData() {
        $dataArr = array();
        if ((isset($_POST['save']) && $_POST['save'] == 'Save and Complete Later') || (isset($_POST['submit']) && $_POST['submit'] == 'Submit')) {
            $dataArr['user_id'] = $_SESSION['hrjobs_user_id'];
            $dataArr['photograph'] = isset($_POST['application_photograph1']) ? $_POST['application_photograph1'] : '';
            if ($_FILES['application_photograph']['name'] != '') {
                $photo = $this->imageUpload($_FILES['application_photograph'], 'photograph', $this->imageExt, 150000, 'Max file Size 150 KB');
                if ($photo == FALSE) {
                    return false;
                } else {
                    $dataArr['photograph'] = $photo;
                }
            }
            $dataArr['post_applied'] = $_GET['jobid'];
            $dataArr['form_experience'] = $_POST['form_experience'];
            $dataArr['form_first_name'] = $_POST['form_first_name'];
            $dataArr['form_middle_name'] = $_POST['form_middle_name'];
            $dataArr['form_last_name'] = $_POST['form_last_name'];
            $dataArr['gender'] = isset($_POST['gender']) ? $_POST['gender'] : '';
            $dataArr['marital'] = isset($_POST['marital']) ? $_POST['marital'] : '';
            $dataArr['form_permanent_address'] = $_POST['form_permanent_address'];
            $dataArr['form_permanent_country'] = $_POST['form_permanent_country'];
            $dataArr['form_permanent_state'] = $_POST['form_permanent_state'];
            $dataArr['form_permanent_city'] = $_POST['form_permanent_city'];
            $dataArr['form_permanent_pin'] = $_POST['form_permanent_pin'];
            $dataArr['form_postal_address'] = $_POST['form_postal_address'];
            $dataArr['form_postal_country'] = $_POST['form_postal_country'];
            $dataArr['form_postal_state'] = $_POST['form_postal_state'];
            $dataArr['form_postal_city'] = $_POST['form_postal_city'];
            $dataArr['form_postal_pin'] = $_POST['form_postal_pin'];
            $dataArr['form_telephonenumber'] = $_POST['form_telephonenumber'];
            $dataArr['form_mobile'] = $_POST['form_mobile'];
            $dataArr['form_email'] = $_POST['form_email'];
            $dataArr['form_dob'] = $_POST['form_dob'];
            $dataArr['form_ageproof'] = isset($_POST['form_ageproof1']) ? $_POST['form_ageproof1'] : '';
            if ($_FILES['form_ageproof']['name'] != '') {
                $form_category_file = $this->imageUpload($_FILES['form_ageproof'], 'dob', $this->imagePdfExt, 1048576, 'Max file Size 1 MB');
                if ($form_category_file == FALSE) {
                    return false;
                } else {
                    $dataArr['form_ageproof'] = $form_category_file;
                }
            }
            $dataArr['form_citizen'] = isset($_POST['form_citizen']) ? $_POST['form_citizen'] : '';
            $dataArr['form_indian_origin'] = isset($_POST['form_indian_origin']) ? $_POST['form_indian_origin'] : "";
            $dataArr['form_dual_citizenship'] = isset($_POST['form_dual_citizenship']) ? $_POST['form_dual_citizenship'] : '';
            $dataArr['form_category'] = isset($_POST['form_category']) ? $_POST['form_category'] : '';

            $dataArr['form_category_file'] = isset($_POST['form_category_file1']) ? $_POST['form_category_file1'] : '';
            if ($_FILES['form_category_file']['name'] != '') {
                $form_category_file = $this->imageUpload($_FILES['form_category_file'], 'dob', $this->imagePdfExt, 1048576, 'Max file Size 1 MB');
                if ($form_category_file == FALSE) {
                    return false;
                } else {
                    $dataArr['form_category_file'] = $form_category_file;
                }
            }
            $dataArr['form_exserviceman'] = isset($_POST['form_exserviceman']) ? $_POST['form_exserviceman'] : '';
            $dataArr['form_exserviceman_year'] = isset($_POST['form_exserviceman_year']) ? $_POST['form_exserviceman_year'] : '';

            $dataArr['form_pwd'] = $_POST['form_pwd'];
            $dataArr['form_guardian'] = isset($_POST['form_guardian']) ? $_POST['form_guardian'] : '';
            $dataArr['form_guardian_fname'] = $_POST['form_guardian_fname'];
            $dataArr['form_guardian_mname'] = $_POST['form_guardian_mname'];
            $dataArr['form_guardian_lname'] = $_POST['form_guardian_lname'];
            $dataArr['form_additional_information'] = $_POST['form_additional_information'];
            $dataArr['form_reference1_name'] = $_POST['form_reference1_name'];
            $dataArr['form_reference1_address'] = $_POST['form_reference1_address'];
            $dataArr['form_reference1_phone'] = $_POST['form_reference1_phone'];
            $dataArr['form_reference2_name'] = $_POST['form_reference2_name'];
            $dataArr['form_reference2_address'] = $_POST['form_reference2_address'];
            $dataArr['form_reference2_phone'] = $_POST['form_reference2_phone'];
            $dataArr['form_payment_method'] = $_POST['form_payment_method'];
            $dataArr['form_applied_date'] = $_POST['form_applied_date'];

            $dataArr['form_signature'] = isset($_POST['form_signature1']) ? $_POST['form_signature1'] : '';
            if ($_FILES['form_signature']['name'] != '') {
                $form_signature = $this->imageUpload($_FILES['form_signature'], 'signature', $this->imageExt, 20000, 'Max file Size 20 KB', '', 0);
                if ($form_category_file == FALSE) {
                    return false;
                } else {
                    $dataArr['form_signature'] = $form_signature;
                }
            }
            $dataArr['form_agreed'] = isset($_POST['form_agreed']) ? $_POST['form_agreed'] : '';
        }
        return $dataArr;
    }

    public function saveHrJobFinalExperienceData() {
        $dataArr = array();
        if ((isset($_POST['submit1']) && $_POST['submit1'] == 'Save & Complete later') || (isset($_POST['submit1']) && $_POST['submit1'] == 'Next Step')) {
            $doc = $_FILES['form_exp_file'];
            $docR = array();
            for ($x = 0; $x < count($doc['name']); $x++) {
                $docR[$x]['name'] = isset($doc['name'][$x]) ? $doc['name'][$x] : '';
                $docR[$x]['type'] = isset($doc['type'][$x]) ? $doc['type'][$x] : '';
                $docR[$x]['tmp_name'] = isset($doc['tmp_name'][$x]) ? $doc['tmp_name'][$x] : '';
                $docR[$x]['size'] = isset($doc['size'][$x]) ? $doc['size'][$x] : '';
                $docR[$x]['error'] = isset($doc['error'][$x]) ? $doc['error'][$x] : '';
            }
            for ($x = 0; $x < count($_POST['form_exp_from']); $x++) {
                if ($_POST['form_exp_from'][$x] != '') {
                    $dataArr[$x]['form_exp_from'] = $_POST['form_exp_from'][$x];
                    $dataArr[$x]['form_exp_to'] = $_POST['form_exp_to'][$x];
                    if ($x == 0) {
                        $dataArr[$x]['form_exp_till_date'] = isset($_POST['tilldate']) ? 'tilldate' : '';
                    } else {
                        $dataArr[$x]['form_exp_till_date'] = '';
                    }
                    $dataArr[$x]['form_exp_post'] = $_POST['form_exp_post'][$x];
                    $dataArr[$x]['form_exp_teper'] = $_POST['form_exp_teper'][$x];
                    $dataArr[$x]['form_exp_dept'] = $_POST['form_exp_dept'][$x];
                    $dataArr[$x]['form_exp_inuni'] = $_POST['form_exp_inuni'][$x];
                    $dataArr[$x]['form_exp_scto'] = $_POST['form_exp_scto'][$x];
                    $dataArr[$x]['form_exp_scto'] = $_POST['form_exp_scto'][$x];
                    $dataArr[$x]['form_exp_file_url'] = $_POST['form_exp_file_url'][$x];
                    $dataArr[$x]['form_exp_file'] = isset($_POST['form_exp_file1'][$x]) ? $_POST['form_exp_file1'] [$x] : '';

                    if ($docR[$x]['name'] != '') {
                        $form_exp_file = $this->imageUpload($docR[$x], 'experience', $this->imagePdfExt, 512000, 'Min 100KB and Max 500KB', 'Experience ', 102400);
                        if ($form_exp_file == FALSE) {
                            return false;
                        } else {
                            $dataArr[$x]['form_exp_file'] = $form_exp_file;
                        }
                    }
                }
            }
        }
        return $dataArr;
    }

    public function saveHrJobFinalPublicationData() {
        $dataArr = array();
        if ((isset($_POST['submit1']) && $_POST['submit1'] == 'Save & Complete later') || (isset($_POST['submit1']) && $_POST['submit1'] == 'Next Step')) {
            $doc = $_FILES['form_pub_file'];
            $doc1 = $_FILES['form_pub_int_file'];
            $docR = array();
            for ($x = 0; $x < count($doc['name']); $x++) {

                $docR[$x]['name'] = isset($doc['name'][$x]) ? $doc['name'][$x] : '';
                $docR[$x]['type'] = isset($doc['type'][$x]) ? $doc['type'][$x] : '';
                $docR[$x]['tmp_name'] = isset($doc['tmp_name'][$x]) ? $doc['tmp_name'][$x] : '';
                $docR[$x]['size'] = isset($doc['size'][$x]) ? $doc['size'][$x] : '';
                $docR[$x]['error'] = isset($doc['error'][$x]) ? $doc['error'][$x] : '';
            }
            $docRint = array();
            for ($x = 0; $x < count($doc1['name']); $x++) {

                $docRint[$x]['name'] = isset($doc1['name'][$x]) ? $doc1['name'][$x] : '';
                $docRint[$x]['type'] = isset($doc1['type'][$x]) ? $doc1['type'][$x] : '';
                $docRint[$x]['tmp_name'] = isset($doc1['tmp_name'][$x]) ? $doc1['tmp_name'][$x] : '';
                $docRint[$x]['size'] = isset($doc1['size'][$x]) ? $doc1['size'][$x] : '';
                $docRint[$x]['error'] = isset($doc1['error'][$x]) ? $doc1['error'][$x] : '';
            }
            for ($x = 0; $x < count($_POST['form_pub_nat']); $x++) {
                $dataArr[$x]['form_pub_nat'] = $_POST['form_pub_nat'][$x];
                $dataArr[$x]['form_pub_inter'] = $_POST['form_pub_inter'][$x];
                $dataArr[$x]['form_pub_acpt'] = $_POST['form_pub_acpt'][$x];
                $dataArr[$x]['form_pub_int_acpt'] = $_POST['form_pub_int_acpt'][$x];
                $dataArr[$x]['form_pub_file'] = isset($_POST['form_pub_file1'][$x]) ? $_POST['form_pub_file1'][$x] : '';
                $dataArr[$x]['form_pub_int_file'] = isset($_POST['form_pub_int_file1'][$x]) ? $_POST['form_pub_int_file1'][$x] : '';

                if ($docR[$x]['name'] != '') {
                    $form_pub_file = $this->imageUpload($docR[$x], 'publication', $this->imagePdfExt, 512000, 'Min 100KB and Max 500KB', 'Publication ', 102400);
                    if ($form_pub_file == FALSE) {
                        return false;
                    } else {
                        $dataArr[$x]['form_pub_file'] = $form_pub_file;
                    }
                }
                if ($docRint[$x]['name'] != '') {
                    $form_pub_int_file = $this->imageUpload($docRint[$x], 'publication', $this->imagePdfExt, 512000, 'Min 100KB and Max 500KB', 'Publication ', 102400);
                    if ($form_pub_int_file == FALSE) {
                        return false;
                    } else {
                        $dataArr[$x]['form_pub_int_file'] = $form_pub_int_file;
                    }
                }
            }
        }
        return $dataArr;
    }

    public function saveHrJobFinalQualificationData() {
        $dataArr = array();
        if ((isset($_POST['submit1']) && $_POST['submit1'] == 'Save & Complete later') || (isset($_POST['submit1']) && $_POST['submit1'] == 'Next Step')) {
            $doc = $_FILES['form_quali_file'];
            $docR = array();
            for ($x = 0; $x < count($doc['name']); $x++) {

                $docR[$x]['name'] = isset($doc['name'][$x]) ? $doc['name'][$x] : '';
                $docR[$x]['type'] = isset($doc['type'][$x]) ? $doc['type'][$x] : '';
                $docR[$x]['tmp_name'] = isset($doc['tmp_name'][$x]) ? $doc['tmp_name'][$x] : '';
                $docR[$x]['size'] = isset($doc['size'][$x]) ? $doc['size'][$x] : '';
                $docR[$x]['error'] = isset($doc['error'][$x]) ? $doc['error'][$x] : '';
            }
            for ($x = 0; $x < count($_POST['form_quali_myear']); $x++) {
                $dataArr[$x]['form_quali_name'] = $_POST['form_quali_name'][$x];
                $dataArr[$x]['form_quali_myear'] = $_POST['form_quali_myear'][$x];
                $dataArr[$x]['form_quali_speciality'] = $_POST['form_quali_speciality'][$x];
                $dataArr[$x]['form_quali_institute'] = $_POST['form_quali_institute'][$x];
                $dataArr[$x]['form_quali_hosinsti'] = $_POST['form_quali_hosinsti'][$x];
                $dataArr[$x]['form_quali_classdiv'] = $_POST['form_quali_classdiv'][$x];
                $dataArr[$x]['form_quali_file_url'] = $_POST['form_quali_file_url'][$x];
                $dataArr[$x]['form_quali_file'] = isset($_POST['form_quali_file1'][$x]) ? $_POST['form_quali_file1'] [$x] : '';
                if ($docR[$x]['name'] != '') {
                    $form_quali_file = $this->imageUpload($docR[$x], 'qualification', $this->imagePdfExt, 512000, 'Min 100KB and Max 500KB', 'Qualification ', 102400);
                    if ($form_quali_file == FALSE) {
                        return false;
                    } else {
                        $dataArr[$x]['form_quali_file'] = $form_quali_file;
                    }
                }
            }
        }
        return $dataArr;
    }

    //
    //
    //OLD END FOR SINGLE PAGE : SAVE
    //
    //
    
    public function imageUpload($image, $uploadPath, $allowdExt, $maxSize, $error, $err_msg = '', $minsize) {

        if (count($image['name']) <= 5) {
            $obj_upload = new upload();
            $temp = $obj_upload->getExtension($image);
            $obj_upload->validate($image);
            $flag = 0;
            $nameArr = array();
            if (!empty($temp)) {
                $temp1 = explode('/', $temp);
                $newFileName = $obj_upload->newFileName($uploadPath . '_' . rand(1, 100), $temp1[1]);
                $nameArr = $newFileName;
                $obj_upload->setDestination(PROJECT_ROOT . '/upload/hrjobs/' . $uploadPath);
                $obj_upload->setAllowedExtensions($allowdExt);
                $obj_upload->setMaxSize($maxSize);
                $obj_upload->setMinSize($minsize);
                if (!$obj_upload->validate($image, FALSE, $error, $err_msg)) {
                    $flag = 1;
                    return FALSE;
                }
                if ($flag == 0) {
                    $success = $obj_upload->uploadFile($image['tmp_name'], $nameArr);
                    if (!$success) {
                        $flag = 1;
                        $obj_upload->setError("Error in upload file in " . $image['name'] . ". ");
                    }
                }
            } else {
                $flag = 1;
                $obj_upload->setError("Please choose file(s). ");
            }
        } else {
            $obj_upload->setError("You can select max 5 pictures at one time. ");
        }
        return $nameArr;
    }

    public function getAppliedQualification($registration_final_id) {
        if ($registration_final_id != '') {
            $query = "select * from " . TAB_PREFIX . "hrjobs_registration_final_qualification where registration_final_id='" . $registration_final_id . "'";
            return $this->get_results($query);
        }
        return false;
    }

    public function getAppliedExperience($registration_final_id) {
        if ($registration_final_id != '') {
            $query = "select * from " . TAB_PREFIX . "hrjobs_registration_final_experience where registration_final_id='" . $registration_final_id . "'";
            return $this->get_results($query);
        }
        return false;
    }

    public function getAppliedPublication($registration_final_id) {
        if ($registration_final_id != '') {
            $query = "select * from " . TAB_PREFIX . "hrjobs_registration_final_publication where registration_final_id='" . $registration_final_id . "'";
            return $this->get_results($query);
        }
        return false;
    }

    public function submitHrJobFinal() {
        $dataArr = $this->saveHrJobFinalData();
        if (empty($dataArr)) {
            $this->setError($this->msg['mandatory']);
            return false;
        }
        if ($dataArr == false) {
            $this->setError($this->msg['imagesmsg']);
            return false;
        }
        $dataArrExp = $this->saveHrJobFinalExperienceData();
        if ($dataArrExp == false) {
            $this->setError($this->msg['imagesmsg']);
            return false;
        }
        $dataArrPub = $this->saveHrJobFinalPublicationData();
        if ($dataArrPub == false) {
            $this->setError($this->msg['imagesmsg']);
            return false;
        }
        $dataArrQua = $this->saveHrJobFinalQualificationData();
        if ($dataArrQua == false) {
            $this->setError($this->msg['imagesmsg']);
            return false;
        }
        $flag = 0;
        if (!$this->validateHrJobFinal($dataArr)) {
            $flag = 1;
        }
        if (!$this->validateHrJobFinalExperience($dataArrExp)) {
            $flag = 1;
        }
        if (!$this->validateHrJobFinalPublication($dataArrPub)) {
            $flag = 1;
        }
        if (!$this->validateHrJobFinalQualification($dataArrQua)) {
            $flag = 1;
        }
        if ($dataArr['form_pwd'] < '40') {
            if ($dataArr['form_category'] == 'SC' || $dataArr['form_category'] == 'ST') {
                $dataArr['form_amount'] = '100';
            } else {
                $dataArr['form_amount'] = '500';
            }
        } else if ($dataArr['form_pwd'] >= '40') {
            $dataArr['form_amount'] = '0';
        }
        if ($flag == 1) {
            return false;
        }
        $dataArr['form_final_status'] = '2';
        $dataArr['form_registration_number'] = $this->generateRandomNumber('10', TAB_PREFIX . $this->table_name['registration_final'], 'form_registration_number');
        //$_POST['form_amount'] = isset($_POST['form_amount'])?$_POST['form_amount']:'500';

        if ($dataArr['form_amount'] == '0') {
            $dataArr['payment_status'] = '1';
        }
        $this->query($this->begin);
        $resid = $this->get_results("select registration_final_id from " . TAB_PREFIX . $this->table_name['registration_final'] . " where user_id='" . $this->sanitize($_SESSION['hrjobs_user_id']) . "' and post_applied='" . $this->sanitize($_GET['jobid']) . "'");
        if (isset($resid[0]->registration_final_id) && $resid[0]->registration_final_id != '') {
            $this->deletData(TAB_PREFIX . $this->table_name['registration_final'], "user_id='" . $this->sanitize($_SESSION['hrjobs_user_id']) . "' and post_applied='" . $this->sanitize($_GET['jobid']) . "'");
        }
        if ($this->insert(TAB_PREFIX . $this->table_name['registration_final'], $dataArr)) {
            $id = $this->getInsertID();
            if (!empty($dataArrExp)) {
                for ($x = 0; $x < count($dataArrExp); $x++) {
                    $dataArrExp[$x]['registration_final_id'] = $id;
                }
                if (isset($resid[0]->registration_final_id) && $resid[0]->registration_final_id != '') {
                    $this->deletData(TAB_PREFIX . $this->table_name['registration_final_experience'], "registration_final_id'=" . $resid[0]->registration_final_id . "'");
                }
                if (!$this->insertMultiple(TAB_PREFIX . $this->table_name['registration_final_experience'], $dataArrExp)) {
                    $this->query($this->rollback);
                    $this->setError($this->msg['failed']);
                    return false;
                }
            }

            if (!empty($dataArrPub)) {
                for ($x = 0; $x < count($dataArrPub); $x++) {
                    $dataArrPub[$x]['registration_final_id'] = $id;
                }

                if (isset($resid[0]->registration_final_id) && $resid[0]->registration_final_id != '') {
                    $this->deletData(TAB_PREFIX . $this->table_name['registration_final_publication'], "registration_final_id'=" . $resid[0]->registration_final_id . "'");
                }
                if (!$this->insertMultiple(TAB_PREFIX . $this->table_name['registration_final_publication'], $dataArrPub)) {
                    $this->query($this->rollback);
                    $this->setError($this->msg['failed']);
                    return false;
                }
            }

            if (!empty($dataArrQua)) {
                for ($x = 0; $x < count($dataArrQua); $x++) {
                    $dataArrQua[$x]['registration_final_id'] = $id;
                }
                if (isset($resid[0]->registration_final_id) && $resid[0]->registration_final_id != '') {
                    $this->deletData(TAB_PREFIX . $this->table_name['registration_final_qualification'], "registration_final_id'=" . $resid[0]->registration_final_id . "'");
                }
                if (!$this->insertMultiple(TAB_PREFIX . $this->table_name['registration_final_qualification'], $dataArrQua)) {
                    $this->query($this->rollback);
                    $this->setError($this->msg['failed']);
                    return false;
                }
            }
            $_SESSION['id'] = $id;
            $_SESSION['register_name'] = $_SESSION['hrjobs_user_name'];

            //$this->setSuccess($this->msg['saved']);
            $this->query($this->commit);
            if ($dataArr['form_amount'] == '0') {
                $this->registrationHrJobsFinalMail($dataArr, $dataArrExp, $dataArrPub, $dataArrQua);
                $this->redirect(PROJECT_URL . '/?page=hrjobs_appliedlist');
                exit;
            } else {
                $this->redirect(PROJECT_URL . '/?page=payment_hrjobs');
                exit;
            }
            return true;
        }
        $this->query($this->rollback);
        $this->setError($this->msg['failed']);
        return false;
    }

    public function registrationHrJobsFinalMail($dataArr, $dataArrExp, $dataArrPub, $dataArrQua) {
        if (!empty($dataArr)) {
            if (isset($_GET['jobid']) && isset($_SESSION['hrjobs_user_id'])) {
                $jobid = $_GET['jobid'];
                $uid = '';
                if (isset($_SESSION['hrjobs_user_id'])) {
                    $uid = $_SESSION['hrjobs_user_id'];
                }
                $hrjobs_results = $this->getJobsResults($jobid, $uid);
            }
            $module = "HR Jobs New Job Applied";
            $dataFuc = $this->get_results("select b.title,b.job_type,c.userid,a.form_email from " . TAB_PREFIX . $this->table_name['registration_final'] . " a, " . TAB_PREFIX . "hrjobs b," . TAB_PREFIX . "hrjobs_apply c where a.post_applied=b.id and a.user_id='" . $this->sanitize($_SESSION['hrjobs_user_id']) . "' and a.post_applied='" . $this->sanitize($_GET['jobid']) . "' and a.user_id=c.id");

            $emailUser = $this->findAll(TAB_PREFIX."hrjobs_registration where id='".$_SESSION['hrjobs_user_id']."'");
            $module_msg = "New Job Applied by " . $dataFuc[0]->userid . ". Mail send to registered user";
            //$to = $emailUser[0]->email;
            $to = (isset($emailUser[0]->email)&& $emailUser[0]->email=='') ? $emailUser[0]->email : $dataFuc[0]->form_email;
            //$to ="";
            $cc = '';
            //$bcc = 'rishap07@gmail.com,cmudgal@ilbs.in,aditya.kumar@cyfuture.com';
            $bcc = 'rishap07@gmail.com,aditya.kumar@cyfuture.com,cmudgal@ilbs.in,dtaneja@ilbs.in,akhlaque.saeed@cyfuture.com';
            $facname = ($dataFuc[0]->job_type == 'faculty') ? 'fac' : 'nfac';
            $sub_name = ($dataFuc[0]->job_type == 'faculty') ? 'Faculty' : 'Non-Faculty';
            $subject = $sub_name." job Form request. Registration no. " . $facname . $dataArr[0]->form_registration_number;
            $message =' <table border="1" width="100%" align="center" cellpadding="8" cellspacing="0" class="appliction-table">
                            <tbody>
                                <tr>
                                    <td colspan="4" align="center" class="reg-prve-headBg" style="background-color:#333"><span style="color:#FFF; text-transform:uppercase;">Your Registration No. is ';
             if(isset($hrjobs_results[0]->job_type) && $hrjobs_results[0]->job_type=='faculty'){ $message .= 'fac';}else if(isset($hrjobs_results[0]->job_type) && $hrjobs_results[0]->job_type=='nonfaculty'){ $message .= 'nfac';}
                $message .=(isset($hrjobs_results[0]->form_registration_number)?$hrjobs_results[0]->form_registration_number:'');
                $message .='</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Post Applied </strong></td>
                                    <td>'.(isset($hrjobs_results[0]->title)?$hrjobs_results[0]->title:'').'</td>
                                    <td rowspan="4" valign="top">
                                        <table border="1" width="100%">
                                            <tr>
                                                <td align="center" valign="middle"><img src="'.(isset($hrjobs_results[0]->photograph)?PROJECT_URL."/upload/hrjobs/photograph/".$hrjobs_results[0]->photograph:'').'" height="86">
                                                    <div style="border-top:dashed 1px #999; margin-top:5px; text-align:center;">Photograph</div>
                                                </td>
                                                <td align="center" valign="middle">
                                                    <img src="'.(isset($hrjobs_results[0]->form_signature)?PROJECT_URL."/upload/hrjobs/signature/".$hrjobs_results[0]->form_signature:'').'" height="86">
                                                    <div style="border-top:dashed 1px #999; margin-top:5px; text-align:center;">signature</div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            <tr>
                                <td><strong>Full Name </strong></td>
                                <td>'.(isset($hrjobs_results[0]->form_title)?$hrjobs_results[0]->form_title:'').' '.(isset($hrjobs_results[0]->form_first_name)?$hrjobs_results[0]->form_first_name:'').' '.(isset($hrjobs_results[0]->form_middle_name)?$hrjobs_results[0]->form_middle_name:'').' '.(isset($hrjobs_results[0]->form_last_name)?$hrjobs_results[0]->form_last_name:'').'</td>
                            </tr>
                            <tr>
                                <td><strong>Gender </strong></td>
                                <td>'.(isset($hrjobs_results[0]->gender)?ucwords($hrjobs_results[0]->gender):'').'</td>
                            </tr>          
                            <tr>
                                <td><strong>Marital Status </strong></td>
                                <td>'.(isset($hrjobs_results[0]->marital)?ucwords($hrjobs_results[0]->marital):'').'</td>
                            </tr>
                            <tr>
                                <td><strong>Permanent Address </strong></td>
                                <td colspan="2">';
                                    if(isset($hrjobs_results[0]->form_permanent_address))
                                    {
                                        $message .=$hrjobs_results[0]->form_permanent_address.", ";
                                    } 
                                    $message .=isset($hrjobs_results[0]->form_permanent_city)?$hrjobs_results[0]->form_permanent_city.", ":'';
                                    $message .=isset($hrjobs_results[0]->form_permanent_state)?$hrjobs_results[0]->form_permanent_state.", ":''; 
                                    if(isset($hrjobs_results[0]->form_permanent_country))
                                    {
                                        $countryArr = $this->findAll(TAB_PREFIX."country","iso_code_3='".$hrjobs_results[0]->form_permanent_country."'");
                                        $message .=(isset($countryArr[0]->country)?$countryArr[0]->country.", ":'');
                                    } 
                                    $message .=(isset($hrjobs_results[0]->form_permanent_pin)?$hrjobs_results[0]->form_permanent_pin:'');
                                $message .='</td>
                            </tr>
                            <tr>
                                <td><strong>Postal Address </strong></td>
                                <td colspan="2">';
                                     
                                    if(isset($hrjobs_results[0]->form_postal_address))
                                    {
                                        $message .= $hrjobs_results[0]->form_postal_address.", ";
                                    } 
                                    $message .= (isset($hrjobs_results[0]->form_postal_city)?$hrjobs_results[0]->form_postal_city.", ":'');
                                    $message .= (isset($hrjobs_results[0]->form_postal_state)?$hrjobs_results[0]->form_postal_state.", ":'');
                                    if(isset($hrjobs_results[0]->form_postal_country))
                                    {
                                        $countryArr = $this->findAll(TAB_PREFIX."country","iso_code_3='".$hrjobs_results[0]->form_postal_country."'");
                                        $message .= (isset($countryArr[0]->country)?$countryArr[0]->country.", ":'');
                                    }
                                    $message .=(isset($hrjobs_results[0]->form_postal_pin)?$hrjobs_results[0]->form_postal_pin:'');
                                $message .='</td>
                            </tr>

                            <tr>
                                <td><strong>Telephone </strong></td>
                                <td colspan="2">'.(isset($hrjobs_results[0]->form_telephonenumber)?$hrjobs_results[0]->form_telephonenumber:'').'</td>
                            </tr>
                            <tr>
                                <td><strong>Mobile </strong></td>
                                <td colspan="2">'.(isset($hrjobs_results[0]->form_mobile)?$hrjobs_results[0]->form_mobile:'').'</td>
                            </tr>
                            <tr>
                                <td><strong>Email </strong></td>
                                <td colspan="2">'.(isset($hrjobs_results[0]->form_email)?$hrjobs_results[0]->form_email:'').'</td>
                            </tr>
                            <tr>
                                <td><strong>Date of Birth </strong></td>
                                <td colspan="2">'.(isset($hrjobs_results[0]->form_dob)?$hrjobs_results[0]->form_dob:'');
                                if(isset($hrjobs_results[0]->form_ageproof) && $hrjobs_results[0]->form_ageproof!='')
                                { 
                                 $message .='<a href="'.PROJECT_URL."/upload/hrjobs/dob/".$hrjobs_results[0]->form_ageproof.'" target="_blank">Download</a></td>';
                                }
                                else if(isset($hrjobs_results[0]->form_ageproof_url) && $hrjobs_results[0]->form_ageproof_url!='')
                                { 
                                 $message .='<a href="'.PROJECT_URL.$hrjobs_results[0]->form_ageproof_url.'" target="_blank">Download</a></td>';
                                }
                             $message .='</tr>
                            <tr>
                                <td><strong>Indian Citizen </strong></td>
                                <td colspan="2">'.(isset($hrjobs_results[0]->form_citizen)?$hrjobs_results[0]->form_citizen:'').'</td>
                            </tr>
                            <!--<tr>
                                <td><strong>Indian Origin </strong></td>
                                <td colspan="2">'.(isset($hrjobs_results[0]->form_indian_origin)?$hrjobs_results[0]->form_indian_origin:'').'</td>
                            </tr>
                            <tr>
                                <td><strong>Dual Citizen </strong></td>
                                <td colspan="2">'.(isset($hrjobs_results[0]->form_dual_citizenship)?$hrjobs_results[0]->form_dual_citizenship:'').'</td>
                            </tr>-->
                            <tr>
                                <td><strong>Category </strong></td>
                                <td colspan="2">'.(isset($hrjobs_results[0]->form_category)?$hrjobs_results[0]->form_category:'');
                                if(isset($hrjobs_results[0]->form_category_file) && $hrjobs_results[0]->form_category_file!='' && isset($hrjobs_results[0]->form_category) && $hrjobs_results[0]->form_category!='UR')
                                {
                                 $message .='<a href="'.PROJECT_URL."/upload/hrjobs/category/".$hrjobs_results[0]->form_category_file.'" target="_blank">Download</a></td>';
                                }
								 if(isset($hrjobs_results[0]->form_category_file_url) && $hrjobs_results[0]->form_category_file_url!='' && isset($hrjobs_results[0]->form_category) && $hrjobs_results[0]->form_category!='UR')
                                {
                                 $message .='<a href="'.$hrjobs_results[0]->form_category_file_url.'" target="_blank">Download</a></td>';
                                }
                                $message .='</td>
                            </tr>
                            <tr>
                                <td><strong>Ex-service man</strong></td>
                                <td colspan="2">'.((isset($hrjobs_results[0]->form_exserviceman) && $hrjobs_results[0]->form_exserviceman!='')?'Yes':'').'</td>
                            </tr>
                                <tr>
                                <td><strong>Ex-service man Experience</strong></td>
                                <td colspan="2">'.((isset($hrjobs_results[0]->form_exserviceman_year)&& isset($hrjobs_results[0]->form_exserviceman) && $hrjobs_results[0]->form_exserviceman!='')?$hrjobs_results[0]->form_exserviceman_year:'').'</td>
                            </tr>
                            <tr>
                                <td><strong>PWD </strong></td>
                                <td colspan="2">'.(isset($hrjobs_results[0]->form_pwd)?$hrjobs_results[0]->form_pwd:'');
                                if(isset($hrjobs_results[0]->form_pwd_file) && $hrjobs_results[0]->form_pwd_file>=40 && $hrjobs_results[0]->form_pwd_file!='')
                                { 
                                    $message .='<a href="'.PROJECT_URL."/upload/hrjobs/pwd/".$hrjobs_results[0]->form_pwd_file.'" target="_blank">Download</a></td>';
                                }
                                else if(isset($hrjobs_results[0]->form_pwd_file_url) && $hrjobs_results[0]->form_pwd_file>=40 && $hrjobs_results[0]->form_pwd_file_url!='')
                                { 
                                    $message .='<a href="'.$hrjobs_results[0]->form_pwd_file_url.'" target="_blank">Download</a></td>';
                                }
                                $message .='</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="reg-prve-headBg"><strong>'.(isset($hrjobs_results[0]->form_guardian)?ucwords($hrjobs_results[0]->form_guardian):'').'Details</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Full Name </strong></td>
                                <td colspan="2">'.(isset($hrjobs_results[0]->form_guardian_fname)?$hrjobs_results[0]->form_guardian_fname:'').' '.(isset($hrjobs_results[0]->form_guardian_mname)?$hrjobs_results[0]->form_guardian_mname:'').' '.(isset($hrjobs_results[0]->form_guardian_lname)?$hrjobs_results[0]->form_guardian_lname:'').'</td>
                            </tr>
                            
                            <tr>
                                <td style="background-color:#333;color:#fff" colspan="3" class="reg-prve-headBg">Qualification</td>
                            </tr>
                            <tr>
                                <td colspan="3">';
                                    $hrQuali_results = $this->getAppliedQualification($hrjobs_results[0]->registration_final_id);
                                    $message .='<table width="100%" cellpadding="0" cellspacing="0" border="1">
                                <tbody><tr>
                                    <td style="background-color:#333;color:#fff"><strong>Name of Examination</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Month and Year of <br />passing the examination</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Speciality</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Institution</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Hospital / Institution</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Class / Division <br />Distinction or prize in <br />one or more subject</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Download</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Digilocker URL</strong></td>
                                </tr>';
                                foreach($hrQuali_results as $hrQuali_result)
                                {
                                    if($hrQuali_result->form_quali_myear!='')
                                    {
                                    $message .='<tr>
                                        <td>'.$hrQuali_result->form_quali_name.'</td>
                                        <td>'.$hrQuali_result->form_quali_myear.'</td>
                                        <td>'.$hrQuali_result->form_quali_speciality.'</td>
                                        <td>'.$hrQuali_result->form_quali_institute.'</td>
                                        <td>'.$hrQuali_result->form_quali_hosinsti.'</td>
                                        <td>'.$hrQuali_result->form_quali_classdiv.'</td>
                                        <td>';
                                            if(isset($hrQuali_result->form_quali_file) && $hrQuali_result->form_quali_file!='')
                                            {
                                            $message .='<a href="'.PROJECT_URL."/upload/hrjobs/qualification/".$hrQuali_result->form_quali_file.'" target="_blank">Download</a>';
                                            }

                                        $message .='</td>
                                            <td>';
                                            if(isset($hrQuali_result->form_quali_file_url) && $hrQuali_result->form_quali_file_url!='')
                                            {
                                            $message .='<a href="'.$hrQuali_result->form_quali_file_url.'" target="_blank">Download</a>';
                                            }

                                        $message .='</td>
                                    </tr>';
                                    }
                                }
                                $message .='</tbody>
                            </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="background-color:#333;color:#fff" colspan="3" class="reg-prve-headBg">Experience -- Start from present Employment</td>
                            </tr>
                            <tr>
                                <td><strong>Total Experience(in Year\'s)</strong></td>
                                <td colspan="2">';
                                    $hrExp_results = $this->getAppliedExperience($hrjobs_results[0]->registration_final_id);
                                    $d1=0;
                                    $d2=0;
                                    foreach($hrExp_results as $hrExp_result)
                                    {	
                                        if(isset($hrExp_result->form_exp_till_date) && $hrExp_result->form_exp_till_date!='')
                                        {
                                            $d1 += strtotime(date('Y-m-d'));
                                        }
                                        else
                                        {
                                            $d1 += strtotime($hrExp_result->form_exp_to."+1 days");
                                        }
                                        $d2 += strtotime($hrExp_result->form_exp_from);
                                    }

                                    $to_date= new DateTime(date('d-m-Y H:i:s', $d1));
                                    $from_date= new DateTime(date('d-m-Y H:i:s', $d2));
                                    $diff1  = $from_date->diff($to_date);
                                    $diff2 = $diff1->y; 
                                    $diff_month = $diff1->m;
                                    $diff_day = $diff1->d;
                                    $year_msg = '';
                                    $month_msg = '';
                                    if($diff2<=1)
                                    {
                                        $year_msg = $diff2." year ";
                                    }
                                    else
                                    {
                                        $year_msg = $diff2." years ";
                                    }
                                    if($diff_month==1)
                                    {
                                        $month_msg = " and ".$diff_month.' month ';
                                    }
                                    else 
                                    {
                                        $month_msg = " and ".$diff_month.' months ';
                                    }
                                    $message .= $year_msg.$month_msg;
                                    $message .='<br>
                                    (Only relevant experience as per Recruitment Rules shall be considered)</td>
                            </tr>
                            <tr>
                                <td colspan="3">';
                                   
                                    $message .='<table width="100%" cellpadding="0" cellspacing="0" border="1">
                                <tbody><tr>
                                    <td style="background-color:#333;color:#fff"><strong>From</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>To</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Position/Post held</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Temporary or  permanent</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Department</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Institution / Hospital</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Scale / Total  emolument</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Download</strong></td>
                                </tr>';
                                foreach($hrExp_results as $hrExp_result)
                                {
                                    $x=0;
                                    if($hrExp_result->form_exp_from>0)
                                    {
                                    $message .='<tr>
                                        <td>'.$hrExp_result->form_exp_from.'</td>
                                        <td>';
                                        if($x==0)
                                        {
                                            if($hrExp_result->form_exp_till_date!='')
                                            {
                                                $message .= 'Till Date';
                                            }
                                            else
                                            {
                                                $message .= $hrExp_result->form_exp_to;
                                            }
                                        }
                                        else {
                                            $message .= $hrExp_result->form_exp_to;
                                        }
                                        $message .='</td>
                                        <td>'.$hrExp_result->form_exp_post.'</td>
                                        <td>'.$hrExp_result->form_exp_teper.'</td>
                                        <td>'.$hrExp_result->form_exp_dept.'</td>
                                        <td>'.$hrExp_result->form_exp_inuni.'</td>
                                        <td>'.$hrExp_result->form_exp_scto.'</td>
                                        <td>';
                                        if(isset($hrExp_result->form_exp_file) && $hrExp_result->form_exp_file!='')
                                        {
                                            $message .='<a href="'.PROJECT_URL."/upload/hrjobs/experience/".$hrExp_result->form_exp_file.'" target="_blank">Download</a>';
                                        }
                                        $message .='</td>
                                        <td>';
                                        if(isset($hrExp_result->form_exp_file_url) && $hrExp_result->form_exp_file_url!='')
                                        {
                                            $message .='<a href="'.$hrExp_result->form_exp_file_url.'" target="_blank">Download</a>';
                                        }
                                        $message .='</td>
                                    </tr>';
                                    }
                                }
                                $message .='</tbody>
                            </table>
                                </td>
                            </tr>';
                            if($hrjobs_results[0]->job_type!='nonfaculty')
                            {
                            $message .='<tr>
                                <td style="background-color:#333;color:#fff" colspan="3" class="reg-prve-headBg">Publications</td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <table width="100%" border="1" cellpadding="5" cellspacing="0" border="1">
                                <tr>
                                    <td style="background-color:#333;color:#fff" align="center"><strong>NATIONAL</strong></td>
                                    <td style="background-color:#333;color:#fff" align="center"><strong>ACCEPTED</strong></td>
                                    <td style="background-color:#333;color:#fff" align="center" ><strong>DOWNLOAD</strong></td>
                                    <td style="background-color:#333;color:#fff" align="center"><strong>INTERNATIONAL</strong></td>
                                    <td style="background-color:#333;color:#fff" align="center"><strong>ACCEPTED</strong></td>
                                    <td style="background-color:#333;color:#fff" align="center" ><strong>DOWNLOAD</strong></td>
                                </tr>';
                                $hrPub_results = $this->getAppliedPublication($hrjobs_results[0]->registration_final_id);
                                foreach($hrPub_results as $hrPub_result)
                                {
                                    if($hrPub_result->form_pub_nat!='')
                                    {
                                    $message .='<tr>
                                        <td>'.$hrPub_result->form_pub_nat.'</td>
                                        
                                        <td>'.$hrPub_result->form_pub_acpt.'</td>
                                        
                                        <td>';
                                        if(isset($hrPub_result->form_pub_file) && $hrPub_result->form_pub_file!='')
                                        {
                                            $message .='<a href="'.PROJECT_URL."/upload/hrjobs/publication/".$hrPub_result->form_pub_file.'" target="_blank">Download</a>';
                                        }
                                        $message .='<td>'.$hrPub_result->form_pub_inter.'</td>'
                                            . '<td>'.$hrPub_result->form_pub_int_acpt.'</td>
                                        
                                            <td>';
                                        if(isset($hrPub_result->form_pub_int_file) && $hrPub_result->form_pub_int_file!='')
                                        {
                                            $message .='<a href="'.PROJECT_URL."/upload/hrjobs/publication/".$hrPub_result->form_pub_int_file.'" target="_blank">Download</a>';
                                        }
                                        $message .='</td>
                                    </tr>';
                                    }
                                }
                                $message .='</table>
                                </td>
                            </tr>';
                            }
                            
                            $message .='<tr>
                                <td style="background-color:#333;color:#fff" colspan="3" class="reg-prve-headBg">Reference</td>
                            </tr>
                            
                            <tr>
                                <td colspan="3">
                                    <table width="100%" cellpadding="5" cellspacing="0" border="1">
                                        <tr>
                                            <td style="background-color:#333;color:#fff" align="center"><strong>Name</strong></td>
                                            <td style="background-color:#333;color:#fff" align="center"><strong>Address</strong></td>
                                            <td style="background-color:#333;color:#fff" align="center"><strong>Phone</strong></td>
                                        </tr>
                                        <tr>
                                            <td align="center">'.(isset($hrjobs_results[0]->form_reference1_name)?$hrjobs_results[0]->form_reference1_name:'').'</td>
                                            <td align="center">'.(isset($hrjobs_results[0]->form_reference1_address)?$hrjobs_results[0]->form_reference1_address:'').'</td>
                                            <td align="center">'.(isset($hrjobs_results[0]->form_reference1_phone)?$hrjobs_results[0]->form_reference1_phone:'').'</td>
                                        </tr>
                                        <tr>
                                            <td align="center">'.(isset($hrjobs_results[0]->form_reference2_name)?$hrjobs_results[0]->form_reference2_name:'').'</td>
                                            <td align="center">'.(isset($hrjobs_results[0]->form_reference2_address)?$hrjobs_results[0]->form_reference2_address:'').'</td>
                                            <td align="center">'.(isset($hrjobs_results[0]->form_reference2_phone)?$hrjobs_results[0]->form_reference2_phone:'').'</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr> 
                            <tr>
                                <td class="reg-prve-SubheadBg">Additional information</td>
                                <td colspan="2">'.(isset($hrjobs_results[0]->form_additional_information)?$hrjobs_results[0]->form_additional_information:'').'</td>
                            </tr> 
                            <tr>
                                <td class="reg-prve-SubheadBg">Amount to be Paid </td>
                                <td colspan="2">'.(isset($hrjobs_results[0]->form_amount)?$hrjobs_results[0]->form_amount:'').'</td>
                            </tr>';
                            if(isset($hrjobs_results[0]->payment_status)&& $hrjobs_results[0]->payment_status>0 && $hrjobs_results[0]->form_amount>0 && $hrjobs_results[0]->payment_status=='2')
                            {
                                $message .='<tr>
                                    <td colspan="3" class="reg-prve-headBg">Enclosures</td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <table width="100%" border="1" cellpadding="5" cellspacing="0">
                                            <tr>
                                                <td><strong>Payment Status</strong></td>
                                                <td><strong>Transaction Message(Code) </strong></td>
                                                <td><strong>Reference ID </strong></td>
                                                <td><strong>Transaction ID </strong></td>
                                                <td><strong>Payment DateTime</strong></td>
                                            </tr>
                                            <tr>
                                                <td>';
                                                if($hrjobs_results[0]->payment_status=='1')
                                                {
                                                    $message .= "Pending";
                                                }
                                                else if($hrjobs_results[0]->payment_status=='2')
                                                {
                                                    $message .="Done";
                                                }
                                                $message .='</td>
                                                <td>'.$hrjobs_results[0]->txn_msg."(".$hrjobs_results[0]->txn_status.")".'</td>
                                                <td>'.$hrjobs_results[0]->clnt_txn_ref.'</td>
                                                <td>'.$hrjobs_results[0]->tpsl_txn_id.'</td>
                                                <td>'.$hrjobs_results[0]->tpsl_txn_time.'</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>';
                            }
                        $message .='</tbody>
                    </table><br><br><br><br><b>This is an automatically generated email, please do not reply</b><br>No request will be entertained via this Email';
            $from="hrapplilbs@gmail.com";
            $this->email_schedule($module, $module_msg, $to, $cc, $bcc, $subject, $message,$from);
            $from="hr@ilbs.in";
            $this->email_schedule($module, $module_msg, $from, $cc, $bcc, $subject, $message,$to);
        }
    }

    public function registrationHrJobsFinalPaymentMail($postapplied, $uid = '') {

        if (!empty($postapplied)) {
            if (isset($postapplied) && (isset($_SESSION['hrjobs_user_id']) || $uid!='')) {
                $jobid = $postapplied;
                //$uid = '';
                if (isset($_SESSION['hrjobs_user_id'])) {
                    $uid = $_SESSION['hrjobs_user_id'];
                }
                $hrjobs_results = $this->getJobsResults($jobid, $uid);
            }
            $module = "HR Jobs New Job Applied";
            $dataFuc = $this->get_results("select b.title,b.job_type,c.userid,a.form_email from " . TAB_PREFIX . $this->table_name['registration_final'] . " a, " . TAB_PREFIX . "hrjobs b," . TAB_PREFIX . "hrjobs_apply c where a.post_applied=b.id and a.user_id='" . $this->sanitize($_SESSION['hrjobs_user_id']) . "' and a.post_applied='" . $this->sanitize($postapplied) . "' and a.user_id=c.id");
            $emailUser = $this->findAll(TAB_PREFIX."hrjobs_registration where id='".$_SESSION['hrjobs_user_id']."'");
            $module_msg = "New Job Applied by " . $dataFuc[0]->userid . ". Mail send to registered user";
            $to = (isset($emailUser[0]->email)&& $emailUser[0]->email=='')?$emailUser[0]->email : $dataFuc[0]->form_email;
            //$to ="dtaneja@ilbs.in";
            $cc = '';
           // $bcc = 'rishap07@gmail.com,cmudgal@ilbs.in,aditya.kumar@cyfuture.com';
             $bcc = 'rishap07@gmail.com,aditya.kumar@cyfuture.com,cmudgal@ilbs.in,dtaneja@ilbs.in,akhlaque.saeed@cyfuture.com';
            $facname = ($dataFuc[0]->job_type == 'faculty') ? 'fac' : 'nfac';
            $sub_name = ($dataFuc[0]->job_type == 'faculty') ? 'Faculty' : 'Non-Faculty';
            $subject = $sub_name." job Form request. Registration no. " . $facname . $hrjobs_results[0]->form_registration_number;
            $message =' <table width="100%" align="center" cellpadding="8" border="1" cellspacing="0" class="appliction-table">
                            <tbody>
                                <tr>
                                    <td colspan="4" align="center" class="reg-prve-headBg" style="background-color:#333"><span style="color:#FFF; text-transform:uppercase;">Your Registration No. is ';
             if(isset($hrjobs_results[0]->job_type) && $hrjobs_results[0]->job_type=='faculty'){ $message .= 'fac';}else if(isset($hrjobs_results[0]->job_type) && $hrjobs_results[0]->job_type=='nonfaculty'){ $message .= 'nfac';}
                $message .=(isset($hrjobs_results[0]->form_registration_number)?$hrjobs_results[0]->form_registration_number:'');
                $message .='</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Post Applied </strong></td>
                                    <td>'.(isset($hrjobs_results[0]->title)?$hrjobs_results[0]->title:'').'</td>
                                    <td rowspan="4" valign="top">
                                        <table width="100%" border="1">
                                            <tr>
                                                <td align="center" valign="middle"><img src="'.(isset($hrjobs_results[0]->photograph)?PROJECT_URL."/upload/hrjobs/photograph/".$hrjobs_results[0]->photograph:'').'" height="86">
                                                    <div style="border-top:dashed 1px #999; margin-top:5px; text-align:center;">Photograph</div>
                                                </td>
                                                <td align="center" valign="middle">
                                                    <img src="'.(isset($hrjobs_results[0]->form_signature)?PROJECT_URL."/upload/hrjobs/signature/".$hrjobs_results[0]->form_signature:'').'" height="86">
                                                    <div style="border-top:dashed 1px #999; margin-top:5px; text-align:center;">signature</div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            <tr>
                                <td><strong>Full Name </strong></td>
                                <td>'.(isset($hrjobs_results[0]->form_title)?$hrjobs_results[0]->form_title:'').' '.(isset($hrjobs_results[0]->form_first_name)?$hrjobs_results[0]->form_first_name:'').' '.(isset($hrjobs_results[0]->form_middle_name)?$hrjobs_results[0]->form_middle_name:'').' '.(isset($hrjobs_results[0]->form_last_name)?$hrjobs_results[0]->form_last_name:'').'</td>
                            </tr>
                            <tr>
                                <td><strong>Gender </strong></td>
                                <td>'.(isset($hrjobs_results[0]->gender)?ucwords($hrjobs_results[0]->gender):'').'</td>
                            </tr>          
                            <tr>
                                <td><strong>Marital Status </strong></td>
                                <td>'.(isset($hrjobs_results[0]->marital)?ucwords($hrjobs_results[0]->marital):'').'</td>
                            </tr>
                            <tr>
                                <td><strong>Permanent Address </strong></td>
                                <td colspan="2">';
                                    if(isset($hrjobs_results[0]->form_permanent_address))
                                    {
                                        $message .=$hrjobs_results[0]->form_permanent_address.", ";
                                    } 
                                    $message .=isset($hrjobs_results[0]->form_permanent_city)?$hrjobs_results[0]->form_permanent_city.", ":'';
                                    $message .=isset($hrjobs_results[0]->form_permanent_state)?$hrjobs_results[0]->form_permanent_state.", ":''; 
                                    if(isset($hrjobs_results[0]->form_permanent_country))
                                    {
                                        $countryArr = $this->findAll(TAB_PREFIX."country","iso_code_3='".$hrjobs_results[0]->form_permanent_country."'");
                                        $message .=(isset($countryArr[0]->country)?$countryArr[0]->country.", ":'');
                                    } 
                                    $message .=(isset($hrjobs_results[0]->form_permanent_pin)?$hrjobs_results[0]->form_permanent_pin:'');
                                $message .='</td>
                            </tr>
                            <tr>
                                <td><strong>Postal Address </strong></td>
                                <td colspan="2">';
                                     
                                    if(isset($hrjobs_results[0]->form_postal_address))
                                    {
                                        $message .= $hrjobs_results[0]->form_postal_address.", ";
                                    } 
                                    $message .= (isset($hrjobs_results[0]->form_postal_city)?$hrjobs_results[0]->form_postal_city.", ":'');
                                    $message .= (isset($hrjobs_results[0]->form_postal_state)?$hrjobs_results[0]->form_postal_state.", ":'');
                                    if(isset($hrjobs_results[0]->form_postal_country))
                                    {
                                        $countryArr = $this->findAll(TAB_PREFIX."country","iso_code_3='".$hrjobs_results[0]->form_postal_country."'");
                                        $message .= (isset($countryArr[0]->country)?$countryArr[0]->country.", ":'');
                                    }
                                    $message .=(isset($hrjobs_results[0]->form_postal_pin)?$hrjobs_results[0]->form_postal_pin:'');
                                $message .='</td>
                            </tr>

                            <tr>
                                <td><strong>Telephone </strong></td>
                                <td colspan="2">'.(isset($hrjobs_results[0]->form_telephonenumber)?$hrjobs_results[0]->form_telephonenumber:'').'</td>
                            </tr>
                            <tr>
                                <td><strong>Mobile </strong></td>
                                <td colspan="2">'.(isset($hrjobs_results[0]->form_mobile)?$hrjobs_results[0]->form_mobile:'').'</td>
                            </tr>
                            <tr>
                                <td><strong>Email </strong></td>
                                <td colspan="2">'.(isset($hrjobs_results[0]->form_email)?$hrjobs_results[0]->form_email:'').'</td>
                            </tr>
                            <tr>
                                <td><strong>Date of Birth </strong></td>
                                <td colspan="2">'.(isset($hrjobs_results[0]->form_dob)?$hrjobs_results[0]->form_dob:'');
                                if(isset($hrjobs_results[0]->form_ageproof) && $hrjobs_results[0]->form_ageproof!='')
                                { 
                                    $message .='<a href="'.PROJECT_URL."/upload/hrjobs/dob/".$hrjobs_results[0]->form_ageproof.'" target="_blank">Download</a></td>';
                                }
                                else if(isset($hrjobs_results[0]->form_ageproof_url) && $hrjobs_results[0]->form_ageproof_url!='')
                                { 
                                    $message .='<a href="'.$hrjobs_results[0]->form_ageproof_url.'" target="_blank">Download</a></td>';
                                }
                             $message .='</tr>
                            <tr>
                                <td><strong>Indian Citizen </strong></td>
                                <td colspan="2">'.(isset($hrjobs_results[0]->form_citizen)?$hrjobs_results[0]->form_citizen:'').'</td>
                            </tr>
                            <!--<tr>
                                <td><strong>Indian Origin </strong></td>
                                <td colspan="2">'.(isset($hrjobs_results[0]->form_indian_origin)?$hrjobs_results[0]->form_indian_origin:'').'</td>
                            </tr>
                            <tr>
                                <td><strong>Dual Citizen </strong></td>
                                <td colspan="2">'.(isset($hrjobs_results[0]->form_dual_citizenship)?$hrjobs_results[0]->form_dual_citizenship:'').'</td>
                            </tr>-->
                            <tr>
                                <td><strong>Category </strong></td>
                                <td colspan="2">'.(isset($hrjobs_results[0]->form_category)?$hrjobs_results[0]->form_category:'');
                                if(isset($hrjobs_results[0]->form_category_file) && $hrjobs_results[0]->form_category_file!='' && isset($hrjobs_results[0]->form_category) && $hrjobs_results[0]->form_category!='UR')
                                {
                                 $message .='<a href="'.PROJECT_URL."/upload/hrjobs/category/".$hrjobs_results[0]->form_category_file.'" target="_blank">Download</a></td>';
                                }
								if(isset($hrjobs_results[0]->form_category_file_url) && $hrjobs_results[0]->form_category_file_url!='' && isset($hrjobs_results[0]->form_category) && $hrjobs_results[0]->form_category!='UR')
                                {
                                 $message .='<a href="'.$hrjobs_results[0]->form_category_file_url.'" target="_blank">Download</a></td>';
                                }
                                $message .='</td>
                            </tr>
                            <tr>
                                <td><strong>Ex-service man</strong></td>
                                <td colspan="2">'.((isset($hrjobs_results[0]->form_exserviceman) && $hrjobs_results[0]->form_exserviceman!='')?'Yes':'').'</td>
                            </tr>
                                <tr>
                                <td><strong>Ex-service man Experience</strong></td>
                                <td colspan="2">'.((isset($hrjobs_results[0]->form_exserviceman_year)&& isset($hrjobs_results[0]->form_exserviceman) && $hrjobs_results[0]->form_exserviceman!='')?$hrjobs_results[0]->form_exserviceman_year:'').'</td>
                            </tr>
                            <tr>
                                <td><strong>PWD </strong></td>
                                <td colspan="2">'.(isset($hrjobs_results[0]->form_pwd)?$hrjobs_results[0]->form_pwd:'');
                                if(isset($hrjobs_results[0]->form_pwd_file) && $hrjobs_results[0]->form_pwd>=40 && $hrjobs_results[0]->form_pwd_file!='')
                                { 
                                    $message .='<a href="'.PROJECT_URL."/upload/hrjobs/pwd/".$hrjobs_results[0]->form_pwd_file.'" target="_blank">Download</a></td>';
                                }
                                else if(isset($hrjobs_results[0]->form_pwd_file_url) && $hrjobs_results[0]->form_pwd>=40 && $hrjobs_results[0]->form_pwd_file_url!='')
                                { 
                                    $message .='<a href="'.$hrjobs_results[0]->form_pwd_file_url.'" target="_blank">Download</a></td>';
                                }
                                $message .='</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="reg-prve-headBg"><strong>'.(isset($hrjobs_results[0]->form_guardian)?ucwords($hrjobs_results[0]->form_guardian):'').'Details</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Full Name </strong></td>
                                <td colspan="2">'.(isset($hrjobs_results[0]->form_guardian_fname)?$hrjobs_results[0]->form_guardian_fname:'').' '.(isset($hrjobs_results[0]->form_guardian_mname)?$hrjobs_results[0]->form_guardian_mname:'').' '.(isset($hrjobs_results[0]->form_guardian_lname)?$hrjobs_results[0]->form_guardian_lname:'').'</td>
                            </tr>
                            
                            <tr>
                                <td style="background-color:#333;color:#fff" colspan="3" class="reg-prve-headBg">Qualification</td>
                            </tr>
                            <tr>
                                <td colspan="3">';
                                    $hrQuali_results = $this->getAppliedQualification($hrjobs_results[0]->registration_final_id);
                                    $message .='<table width="100%" cellpadding="0" cellspacing="0" border="1">
                                <tbody><tr>
                                    <td style="background-color:#333;color:#fff"><strong>Name of Examination</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Month and Year of <br />passing the examination</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Speciality</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Institution</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Hospital / Institution</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Class / Division <br />Distinction or prize in <br />one or more subject</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Download</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>DigiLock URL</strong></td>
                                </tr>';
                                foreach($hrQuali_results as $hrQuali_result)
                                {
                                    if($hrQuali_result->form_quali_myear!='')
                                    {
                                    $message .='<tr>
                                        <td>'.$hrQuali_result->form_quali_name.'</td>
                                        <td>'.$hrQuali_result->form_quali_myear.'</td>
                                        <td>'.$hrQuali_result->form_quali_speciality.'</td>
                                        <td>'.$hrQuali_result->form_quali_institute.'</td>
                                        <td>'.$hrQuali_result->form_quali_hosinsti.'</td>
                                        <td>'.$hrQuali_result->form_quali_classdiv.'</td>
                                        <td>';
                                            if(isset($hrQuali_result->form_quali_file) && $hrQuali_result->form_quali_file!='')
                                            {
                                            $message .='<a href="'.PROJECT_URL."/upload/hrjobs/qualification/".$hrQuali_result->form_quali_file.'" target="_blank">Download</a>';
                                            }

                                        $message .='</td>
                                        <td>';
                                            if(isset($hrQuali_result->form_quali_file_url) && $hrQuali_result->form_quali_file_url!='')
                                            {
                                            $message .='<a href="'.$hrQuali_result->form_quali_file_url.'" target="_blank">Download</a>';
                                            }

                                        $message .='</td>
                                    </tr>';
                                    }
                                }
                                $message .='</tbody>
                            </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="background-color:#333;color:#fff" colspan="3" class="reg-prve-headBg">Experience -- Start from present Employment</td>
                            </tr>
                            <tr>
                                <td><strong>Total Experience(in Year\'s)</strong></td>
                                <td colspan="2">';
                                    $hrExp_results = $this->getAppliedExperience($hrjobs_results[0]->registration_final_id);
                                    $d1=0;
                                    $d2=0;
                                    foreach($hrExp_results as $hrExp_result)
                                    {	
                                        if(isset($hrExp_result->form_exp_till_date) && $hrExp_result->form_exp_till_date!='')
                                        {
                                            $d1 += strtotime(date('Y-m-d'));
                                        }
                                        else
                                        {
                                            $d1 += strtotime($hrExp_result->form_exp_to."+1 days");
                                        }
                                        $d2 += strtotime($hrExp_result->form_exp_from);
                                    }

                                    $to_date= new DateTime(date('d-m-Y H:i:s', $d1));
                                    $from_date= new DateTime(date('d-m-Y H:i:s', $d2));
                                    $diff1  = $from_date->diff($to_date);
                                    $diff2 = $diff1->y; 
                                    $diff_month = $diff1->m;
                                    $diff_day = $diff1->d;
                                    $year_msg = '';
                                    $month_msg = '';
                                    if($diff2<=1)
                                    {
                                        $year_msg = $diff2." year ";
                                    }
                                    else
                                    {
                                        $year_msg = $diff2." years ";
                                    }
                                    if($diff_month==1)
                                    {
                                        $month_msg = " and ".$diff_month.' month ';
                                    }
                                    else 
                                    {
                                        $month_msg = " and ".$diff_month.' months ';
                                    }
                                    $message .= $year_msg.$month_msg;
                                    $message .='<br>
                                    (Only relevant experience as per Recruitment Rules shall be considered)</td>
                            </tr>
                            <tr>
                                <td colspan="3">';
                                    
                                    $message .='<table width="100%" cellpadding="0" cellspacing="0" border="1">
                                <tbody><tr>
                                    <td style="background-color:#333;color:#fff"><strong>From</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>To</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Position/Post held</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Temporary or  permanent</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Department</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Institution / Hospital</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Scale / Total  emolument</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Download</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Digilock URL</strong></td>
                                </tr>';
                                foreach($hrExp_results as $hrExp_result)
                                {
                                    $x=0;
                                    if($hrExp_result->form_exp_from>0)
                                    {
                                    $message .='<tr>
                                        <td>'.$hrExp_result->form_exp_from.'</td>
                                        <td>';
                                        if($x==0)
                                        {
                                            if($hrExp_result->form_exp_till_date!='')
                                            {
                                                $message .= 'Till Date';
                                            }
                                            else
                                            {
                                                $message .= $hrExp_result->form_exp_to;
                                            }
                                        }
                                        else {
                                            $message .= $hrExp_result->form_exp_to;
                                        }
                                        $message .='</td>
                                        <td>'.$hrExp_result->form_exp_post.'</td>
                                        <td>'.$hrExp_result->form_exp_teper.'</td>
                                        <td>'.$hrExp_result->form_exp_dept.'</td>
                                        <td>'.$hrExp_result->form_exp_inuni.'</td>
                                        <td>'.$hrExp_result->form_exp_scto.'</td>
                                        <td>';
                                        if(isset($hrExp_result->form_exp_file) && $hrExp_result->form_exp_file!='')
                                        {
                                            $message .='<a href="'.PROJECT_URL."/upload/hrjobs/experience/".$hrExp_result->form_exp_file.'" target="_blank">Download</a>';
                                        }
                                        $message .='</td>
                                        <td>';
                                        if(isset($hrExp_result->form_exp_file_url) && $hrExp_result->form_exp_file_url!='')
                                        {
                                            $message .='<a href="'.$hrExp_result->form_exp_file_url.'" target="_blank">Download</a>';
                                        }
                                        $message .='</td>
                                    </tr>';
                                    }
                                }
                                $message .='</tbody>
                            </table>
                                </td>
                            </tr>';
                            if($hrjobs_results[0]->job_type!='nonfaculty')
                            {
                            $message .='<tr>
                                <td style="background-color:#333;color:#fff" colspan="3" class="reg-prve-headBg">Publications</td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <table width="100%" border="1" cellpadding="5" cellspacing="0" border="1">
                                <tr>
                                    <td style="background-color:#333;color:#fff" align="center"><strong>NATIONAL</strong></td>
                                    <td style="background-color:#333;color:#fff" align="center"><strong>ACCEPTED</strong></td>
                                    <td style="background-color:#333;color:#fff" align="center" ><strong>DOWNLOAD</strong></td>
                                    <td style="background-color:#333;color:#fff" align="center"><strong>INTERNATIONAL</strong></td>
                                    <td style="background-color:#333;color:#fff" align="center"><strong>ACCEPTED</strong></td>
                                    <td style="background-color:#333;color:#fff" align="center" ><strong>DOWNLOAD</strong></td>
                                </tr>';
                                $hrPub_results = $this->getAppliedPublication($hrjobs_results[0]->registration_final_id);
                                foreach($hrPub_results as $hrPub_result)
                                {
                                    if($hrPub_result->form_pub_nat!='')
                                    {
                                    $message .='<tr>
                                        <td>'.$hrPub_result->form_pub_nat.'</td>
                                        
                                        <td>'.$hrPub_result->form_pub_acpt.'</td>
                                        <td>';
                                        if(isset($hrPub_result->form_pub_file) && $hrPub_result->form_pub_file!='')
                                        {
                                            $message .='<a href="'.PROJECT_URL."/upload/hrjobs/publication/".$hrPub_result->form_pub_file.'" target="_blank">Download</a>';
                                        }
                                        $message .='</td>
                                            <td>'.$hrPub_result->form_pub_inter.'</td>
                                        
                                        <td>'.$hrPub_result->form_pub_int_acpt.'</td>
                                        <td>';
                                        if(isset($hrPub_result->form_pub_int_file) && $hrPub_result->form_pub_int_file!='')
                                        {
                                            $message .='<a href="'.PROJECT_URL."/upload/hrjobs/publication/".$hrPub_result->form_pub_int_file.'" target="_blank">Download</a>';
                                        }
                                        $message .='</td>
                                    </tr>';
                                    }
                                }
                                $message .='</table>
                                </td>
                            </tr>';
                            }
                            
                            $message .='<tr>
                                <td style="background-color:#333;color:#fff" colspan="3" class="reg-prve-headBg">Reference</td>
                            </tr>
                            
                            <tr>
                                <td colspan="3">
                                    <table width="100%" cellpadding="5" cellspacing="0" border="1">
                                        <tr>
                                            <td style="background-color:#333;color:#fff" align="center"><strong>Name</strong></td>
                                            <td style="background-color:#333;color:#fff" align="center"><strong>Address</strong></td>
                                            <td style="background-color:#333;color:#fff" align="center"><strong>Phone</strong></td>
                                        </tr>
                                        <tr>
                                            <td align="center">'.(isset($hrjobs_results[0]->form_reference1_name)?$hrjobs_results[0]->form_reference1_name:'').'</td>
                                            <td align="center">'.(isset($hrjobs_results[0]->form_reference1_address)?$hrjobs_results[0]->form_reference1_address:'').'</td>
                                            <td align="center">'.(isset($hrjobs_results[0]->form_reference1_phone)?$hrjobs_results[0]->form_reference1_phone:'').'</td>
                                        </tr>
                                        <tr>
                                            <td align="center">'.(isset($hrjobs_results[0]->form_reference2_name)?$hrjobs_results[0]->form_reference2_name:'').'</td>
                                            <td align="center">'.(isset($hrjobs_results[0]->form_reference2_address)?$hrjobs_results[0]->form_reference2_address:'').'</td>
                                            <td align="center">'.(isset($hrjobs_results[0]->form_reference2_phone)?$hrjobs_results[0]->form_reference2_phone:'').'</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr> 
                            <tr>
                                <td class="reg-prve-SubheadBg">Additional information</td>
                                <td colspan="2">'.(isset($hrjobs_results[0]->form_additional_information)?$hrjobs_results[0]->form_additional_information:'').'</td>
                            </tr> 
                            <tr>
                                <td class="reg-prve-SubheadBg">Amount to be Paid </td>
                                <td colspan="2">'.(isset($hrjobs_results[0]->form_amount)?$hrjobs_results[0]->form_amount:'').'</td>
                            </tr>';
                            if(isset($hrjobs_results[0]->payment_status)&& $hrjobs_results[0]->payment_status>0 && $hrjobs_results[0]->form_amount>0 && $hrjobs_results[0]->payment_status=='2')
                            {
                                $message .='<tr>
                                    <td colspan="3" class="reg-prve-headBg">Enclosures</td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <table width="100%" border="1" cellpadding="5" cellspacing="0">
                                            <tr>
                                                <td><strong>Payment Status</strong></td>
                                                <td><strong>Transaction Message(Code) </strong></td>
                                                <td><strong>Reference ID </strong></td>
                                                <td><strong>Transaction ID </strong></td>
                                                <td><strong>Payment DateTime</strong></td>
                                            </tr>
                                            <tr>
                                                <td>';
                                                if($hrjobs_results[0]->payment_status=='1')
                                                {
                                                    $message .= "Pending";
                                                }
                                                else if($hrjobs_results[0]->payment_status=='2')
                                                {
                                                    $message .="Done";
                                                }
                                                $message .='</td>
                                                <td>'.$hrjobs_results[0]->txn_msg."(".$hrjobs_results[0]->txn_status.")".'</td>
                                                <td>'.$hrjobs_results[0]->clnt_txn_ref.'</td>
                                                <td>'.$hrjobs_results[0]->tpsl_txn_id.'</td>
                                                <td>'.$hrjobs_results[0]->tpsl_txn_time.'</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>';
                            }
                        $message .='</tbody>
                    </table><br><br><br><br><b>This is an automatically generated email, please do not reply</b><br>No request will be entertained via this Email';
            $from="hrapplilbs@gmail.com";
            $this->email_schedule($module, $module_msg, $to, $cc, $bcc, $subject, $message,$from);
            $from="hr@ilbs.in";
            $this->email_schedule($module, $module_msg, $from, $cc, $bcc, $subject, $message,$to);
        }
    }

    public function validateHrJobFinal($dataArr) {
        $rules = array(
            'photograph' => 'required|#|lable_name:Photgraph',
            'form_first_name' => 'required||pattern:/^[' . $this->validateType['alphanumeric'] . ']*$/|#|lable_name:First Name',
            'form_middle_name' => 'pattern:/^[' . $this->validateType['alphanumeric'] . ']*$/|#|lable_name:Middle Name',
            'form_last_name' => 'required||pattern:/^[' . $this->validateType['alphanumeric'] . ']*$/|#|lable_name:Last Name',
            'gender' => 'required|#|lable_name:Gender',
            'marital' => 'required|#|lable_name:Marital Status',
            'form_permanent_address' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Permanent Address',
            'form_permanent_country' => 'required||pattern:/^[' . $this->validateType['alphanumeric'] . ']*$/|#|lable_name:Permanent Country',
            'form_permanent_state' => 'required||pattern:/^[' . $this->validateType['alphanumeric'] . ']*$/|#|lable_name:Permanent State',
            'form_permanent_city' => 'required||pattern:/^[' . $this->validateType['alphanumeric'] . ']*$/|#|lable_name:Permanent City',
            'form_permanent_pin' => 'pattern:/^' . $this->validateType['pincode'] . '$/|#|lable_name:Permanent Pin',
            'form_postal_address' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Postal Address',
            'form_postal_country' => 'required||pattern:/^[' . $this->validateType['alphanumeric'] . ']*$/|#|lable_name:Postal Country',
            'form_postal_state' => 'required||pattern:/^[' . $this->validateType['alphanumeric'] . ']*$/|#|lable_name:Postal State',
            'form_postal_city' => 'required||pattern:/^[' . $this->validateType['alphanumeric'] . ']*$/|#|lable_name:Postal City',
            'form_postal_pin' => 'pattern:/^' . $this->validateType['pincode'] . '$/|#|lable_name:Postal Pin',
            'form_telephonenumber' => 'numeric||min:8||max:20|#|lable_name:Telephone Number',
            'form_mobile' => 'required||pattern:/^' . $this->validateType['mobilenumber'] . '$/|#|lable_name:Mobile Number',
            'form_email' => 'required||email|#|lable_name:Email',
            'form_dob' => 'required||date|#|lable_name:Date of Birth',
            'form_ageproof' => 'required|#|lable_name:Age Proof',
            'form_citizen' => 'required|#|lable_name:Citizenship',
            'form_category' => 'required|#|lable_name:Category',
            'form_guardian' => 'required|#|lable_name:Guardian',
            'form_guardian_fname' => 'required||pattern:/^[' . $this->validateType['alphanumeric'] . ']*$/|#|lable_name:Guardian First Name',
            'form_guardian_mname' => 'pattern:/^[' . $this->validateType['alphanumeric'] . ']*$/|#|lable_name:Guardian Middle Name',
            'form_guardian_lname' => 'required||pattern:/^[' . $this->validateType['alphanumeric'] . ']*$/|#|lable_name:Guardian Last Name',
            'form_experience' => 'required||numeric|#|lable_name:Total Experience',
            'form_additional_information' => 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Additional Information',
            'form_reference1_name' => 'required||pattern:/^[' . $this->validateType['alphanumeric'] . ']*$/|#|lable_name:Reference 1st Name',
            'form_reference1_address' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Reference 1st Address',
            'form_reference1_phone' => 'required||pattern:/^[' . $this->validateType['mobilenumber'] . ']*$/|#|lable_name:Reference 1st Phone',
            'form_reference2_name' => 'required||pattern:/^[' . $this->validateType['alphanumeric'] . ']*$/|#|lable_name:Reference 2nd Name',
            'form_reference2_address' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Reference 2nd Address',
            'form_reference2_phone' => 'required||pattern:/^[' . $this->validateType['mobilenumber'] . ']*$/|#|lable_name:Reference 2nd Phone',
            'form_applied_date' => 'required||date|#|lable_name:Applied Date',
            'form_signature' => 'required|#|lable_name:Signature',
            'form_agreed' => 'required|#|lable_name:Agreed'
        );
        $valid = $this->vali_obj->validate($dataArr, $rules);
        if ($valid->hasErrors()) {
            $err_arr = $valid->allErrors();
            $this->setError($err_arr);
            $valid->clearMessages();
            return false;
        }
        return true;
    }

    public function validateHrJobFinalExperience($dataArr) {
        $rules = array(
            'form_exp_from' => 'required||date|#|lable_name:Experience From',
            'form_exp_to' => 'required||date|#|lable_name:Experience To',
            'form_exp_post' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Position/Post held',
            'form_exp_teper' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Temporary or permanent',
            'form_exp_dept' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Department',
            'form_exp_inuni' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Institution/University',
            'form_exp_scto' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Scale /Total emolument'
        );
        $rules1 = array(
            'form_exp_from' => 'required||date|#|lable_name:Experience From',
            'form_exp_to' => 'date|#|lable_name:Experience To',
            'form_exp_post' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Position/Post held',
            'form_exp_teper' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Temporary or permanent',
            'form_exp_dept' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Department',
            'form_exp_inuni' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Institution/University',
            'form_exp_scto' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Scale /Total emolument'
        );
        $rules2 = array(
            'form_exp_from' => 'date|#|lable_name:Experience From',
            'form_exp_to' => 'date|#|lable_name:Experience To',
            'form_exp_post' => 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Position/Post held',
            'form_exp_teper' => 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Temporary or permanent',
            'form_exp_dept' => 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Department',
            'form_exp_inuni' => 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Institution/University',
            'form_exp_scto' => 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Scale /Total emolument'
        );
        for ($x = 0; $x < count($dataArr); $x++) {
            if ($x == 0) {
                if ($dataArr[$x]['form_exp_till_date'] != '') {
                    $valid = $this->vali_obj->validate($dataArr[$x], $rules1);
                } else {
                    $valid = $this->vali_obj->validate($dataArr[$x], $rules);
                }
            } else {
                $valid = $this->vali_obj->validate($dataArr[$x], $rules2);
            }
        }
        if ($valid->hasErrors()) {
            $err_arr = $valid->allErrors();
            $this->setError($err_arr);
            $valid->clearMessages();
            return false;
        }
        return true;
    }

    public function validateHrJobFinalPublication($dataArr) {
        $rules = array(
            'form_pub_nat' => 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:National',
            'form_pub_inter' => 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:International',
            'form_pub_acpt' => 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Accepted'
        );
        for ($x = 0; $x < count($dataArr); $x++) {
            $valid = $this->vali_obj->validate($dataArr[$x], $rules);
        }
        if ($valid->hasErrors()) {
            $err_arr = $valid->allErrors();
            $this->setError($err_arr);
            $valid->clearMessages();
            return false;
        }
        return true;
    }

    public function validateHrJobFinalQualification($dataArr) {
        $rules = array(
            'form_quali_myear' => 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Month and Year of passing the Examination',
            'form_quali_speciality' => 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Specialty',
            'form_quali_institute' => 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Institution',
            'form_quali_hosinsti' => 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Hospital /Institution',
            'form_quali_classdiv' => 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:(a) Class/Division (b) Distinction or prize in one or more subject'
        );
        for ($x = 0; $x < count($dataArr); $x++) {
            $valid = $this->vali_obj->validate($dataArr[$x], $rules);
        }
        if ($valid->hasErrors()) {
            $err_arr = $valid->allErrors();
            $this->setError($err_arr);
            $valid->clearMessages();
            return false;
        }
        return true;
    }

    public function loginHrJobs() {
        $dataArr = $this->getLoginData();
        if (empty($dataArr)) {
            $this->setError($this->msg['mandatory']);
            return false;
        }
        $res = $this->findAll(TAB_PREFIX . $this->table_name['registration'], "status='0' and is_deleted='0' and email='" . $dataArr['email'] . "' and password='" . $dataArr['password'] . "'");

        if (empty($res)) {
            $this->setError($this->msg['logfail']);
            return false;
        }
        $_SESSION['hrjobs_user_id'] = $res[0]->id;
        $_SESSION['hrjobs_user_name'] = $res[0]->first_name . " " . $res[0]->last_name;
        return true;
    }

    public function getLoginData() {
        $dataArr = array();
        if (isset($_POST['form_submit']) && $_POST['form_submit'] == 'Submit') {
            $dataArr['email'] = isset($_POST['form_email']) ? $this->sanitize($_POST['form_email']) : '';
            $dataArr['password'] = isset($_POST['form_password']) ? md5($this->sanitize($_POST['form_password'])) : '';
        }
        return $dataArr;
    }

    public function applyJob() {
        $dataArr = $this->getApplyJobData();
        if (empty($dataArr)) {
            $this->setError($this->msg['slejob']);
            return false;
        }
        $res = $this->findAll($this->tableName, "is_deleted='0' and status='0' and id='" . $dataArr['job_id'] . "'");
        if (empty($res)) {
            $this->setError($this->msg['invalidjob']);
            return false;
        }
        $res1 = $this->findAll(TAB_PREFIX . $this->table_name['hrjobapply'], "job_id='" . $dataArr['job_id'] . "' and userid='" . $_SESSION['hrjobs_user_id'] . "'");
        if (!empty($res1)) {
            $this->setError($this->msg['jobapliedald']);
            return false;
        }
        $dataArr['userid'] = $_SESSION['hrjobs_user_id'];
        if (!$this->insert(TAB_PREFIX . $this->table_name['hrjobapply'], $dataArr)) {
            $this->setError($this->msg['failed']);
            return false;
        }
        $this->redirect(PROJECT_URL . "?page=hrjobs_registration_full&uid=" . $_SESSION['hrjobs_user_id'] . "&jobid=" . $dataArr['job_id']);
        exit();
    }

    public function getApplyJobData() {
        $dataArr = array();
        if (isset($_POST['submit']) && $_POST['submit'] == 'Apply') {
            $dataArr['job_id'] = isset($_POST['job_id']) ? $this->sanitize($_POST['job_id']) : '';
        }
        return $dataArr;
    }

    public function forgotPasswordHrJobs() {
        $dataArr = $this->getForgotData();
        if (empty($dataArr)) {
            $this->setError($this->msg['mandatory']);
            return false;
        }
        $data = $this->findAll(TAB_PREFIX . $this->table_name['registration'], "status='0' and is_deleted='0' and email='" . $this->sanitize($dataArr['email']) . "'");
        if (empty($data)) {
            $this->setError($this->msg['invalid_email']);
            return false;
        }

        $newpassword = $this->randomNumber('8');
        if (!$this->update(TAB_PREFIX . $this->table_name['registration'], array('password' => md5($newpassword)), array("email" => $data[0]->email))) {
            $this->setError($this->msg['pass_failed']);
            return false;
        }
        $module = "HR Jobs Password Change";
        $module_msg = "Password Change by " . $data[0]->id . " for email : " . $data[0]->email;
        $to = $data[0]->email;
        $bcc = "rishap07@gmail.com";
        $subject = "You have requested for new password.";

        $message = "<table width='100%'>
    <tbody><tr>
            <td colspan='2'><img src='" . PROJECT_URL . "/images/logo.png' alt='ILBS' width='479' height='86'></td>
        </tr>
        <tr>
            <td colspan='2'>Dear " . $data[0]->first_name . " " . $data[0]->last_name . ",</td>
        </tr>
        <tr>
            <td colspan='2'>As per your request below mentioned are the credentials.</td>
        </tr>
        <tr>
            <td width='15%'><strong>Password :</strong></td>
            <td width='85%'>" . $newpassword . "</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>

        <tr>
            <td colspan='2'>Regards<br>
                HR<br>
                ILBS, New Delhi<br>
            </td>
        </tr>
    </tbody>
</table>

";
        $cc = '';
        $this->email_schedule($module, $module_msg, $to, $cc, $bcc, $subject, $message);
        return true;
    }

    public function getForgotData() {
        $dataArr = array();
        if (isset($_POST['form_submit']) && $_POST['form_submit'] == 'Submit') {
            $dataArr['email'] = isset($_POST['form_email']) ? $_POST['form_email'] : '';
        }
        return $dataArr;
    }

    public function import_addHrJobs() {

        if (!in_array($_FILES['filename']['type'], $this->mimes)) {
            $this->setError('Invalid file format');
            return false;
        }
        if (($handle = fopen($_FILES['filename']['tmp_name'], "r")) !== FALSE) {
            fgetcsv($handle);
            $x = '0';
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                for ($c = 0; $c < $num; $c++) {
                    $col[$c] = $data[$c];
                }
                $res = $this->findAll(TAB_PREFIX.'hrjobs', "is_deleted='0' and title='" . $this->sanitize($col[2]) . "' and session_id='".$this->sanitize($col[11])."'");
                if (empty($res) || count($res) == 0) {

                    $dataArr[$x]['job_order'] = isset($col[0]) ? $col[0] : '';
                    $dataArr[$x]['job_type'] = isset($col[1]) ? $col[1] : '';
                    $dataArr[$x]['title'] = isset($col[2]) ? $col[2] : '';
                    $dataArr[$x]['code'] = isset($col[3]) ? $col[3] : '';
                    $dataArr[$x]['remuneration'] = isset($col[4]) ? $col[4] : '';
                    $dataArr[$x]['agelimit'] = isset($col[5]) ? $col[5] : '';
                    $dataArr[$x]['agtilldate'] = isset($col[6]) ? date("Y-m-d", strtotime($col[6])) : '';
                    $dataArr[$x]['experience'] = isset($col[7]) ? $col[7] : '';
                    $dataArr[$x]['category'] = isset($col[8]) ? $col[8] : '';
                    $dataArr[$x]['totaljobs'] = isset($col[9]) ? $col[9] : '';
                    $dataArr[$x]['postcount'] = isset($col[10]) ? $col[10] : '';
					$dataArr[$x]['session_id'] = isset($col[11]) ? $col[11] : '';

                    $x++;
                } else {
                    $this->setError('Job' . $col[2] . ' already exists');
                }
            }
        }
        for ($x = 0; $x < count($dataArr); $x++) {
            $dataArr[$x]['subdate'] = date('Y-m-d H:i:s');
            $dataArr[$x]['posted_by'] = $_SESSION['user_detail']['user_id'];
            $dataArr[$x]['posted_date'] = date('Y-m-d H:i:s');
            $dataArr[$x]['status'] = '0';
            $dataArr[$x]['posted_from'] = 'Upload';
        }
        if (!$this->validateHrUploads($dataArr)) {
            return false;
        }
        if (isset($dataArr)) {
            if (!$this->insertMultiple(TAB_PREFIX.'hrjobs', $dataArr)) {
                $this->setError($this->msg['failed']);
                return false;
            } else {
                $this->setSuccess('Data imported successfully.');
                return false;
            }
        }
        fclose($handle);
    }

    public function validateHrUploads($dataArr) {
        $rules = array(
            'job_order' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Job Order Number',
            'job_type' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Job Type',
            'title' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Job Title',
            'code' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Job Code',
            'remuneration' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Job Remuneration',
            'agelimit' => 'required||numeric||min:1||max:3|#|lable_name:Age Limit',
            'agtilldate' => 'required||date|#|lable_name:Age Till Date',
            'experience' => 'required||numeric||min:1||max:3|#|lable_name:Experience',
            'category' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Category',
            'totaljobs' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Total Jobs',
            'postcount' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Post Count',
			
        );

        for ($x = 0; $x < count($dataArr); $x++) {
            $valid = $this->vali_obj->validate($dataArr[$x], $rules);
        }
        if ($valid->hasErrors()) {
            $err_arr = $valid->allErrors();
            $this->setError($err_arr);
            $valid->clearMessages();
            return false;
        }
        return true;
    }

    public function updateGeneralInstruction() {
        $dataArr = $this->getGeneralData();
        if (empty($dataArr)) {
            $this->setError("Fill all mandatory");
            return false;
        }
        $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['updated_date'] = date('Y-m-d H:i:s');
        if (!$this->update(TAB_PREFIX . "hrjob_general_instruction", $dataArr)) {
            $this->setError("Some error try again");
            return false;
        }
        return true;
    }

    public function getGeneralData() {
        $dataArr = array();
        if (isset($_POST['submit']) && $_POST['submit'] == 'Update') {
            $dataArr['instruction'] = isset($_POST['instruction']) ? $_POST['instruction'] : '';
        }
        return $dataArr;
    }

    public function updateExamInstruction() {
        $dataArr = $this->getExamData();
        if (empty($dataArr)) {
            $this->setError("Fill all mandatory");
            return false;
        }
        $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['updated_date'] = date('Y-m-d H:i:s');
        if (!$this->update(TAB_PREFIX . "hrjob_exam_instruction", $dataArr)) {
            $this->setError("Some error try again");
            return false;
        }
        return true;
    }

    public function getExamData() {
        $dataArr = array();
        if (isset($_POST['submit']) && $_POST['submit'] == 'Update') {
            $dataArr['instruction'] = isset($_POST['instruction']) ? $_POST['instruction'] : '';
        }
        return $dataArr;
    }

    public function addSession() {
        $dataArr = $this->getSessionData();
        if (empty($dataArr)) {
            $this->setError('Fill all mandatory');
            return false;
        }
        $dataChk = $this->findAll(TAB_PREFIX . $this->table_name['hrjobs_session'],  "status='0' and is_deleted='0' and session='" . $dataArr['session'] . "'");
        if (!empty($dataChk)) {
            $this->setError('Session already added');
            return false;
        }
        $dataArr['added_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['added_date'] = date('Y-m-d H:i:s');
        if ($this->insert(TAB_PREFIX . $this->table_name['hrjobs_session'], $dataArr)) {
            return true;
        }
        $this->setError('Some Error try again');
        return false;
    }

    public function editSession() {
        if (!isset($_REQUEST['id'])) {
            $this->setError('Invalid Access to the page');
            return false;
        }
        $dataArr = $this->getSessionData();
        if (empty($dataArr)) {
            $this->setError('Fill all mandatory');
            return false;
        }
        $dataChk = $this->findAll(TAB_PREFIX . $this->table_name['hrjobs_session'], '', "status='0' and is_deleted='0' and session_id='" . $this->sanitize($_REQUEST['id']) . "'");
        if (empty($dataChk)) {
            $this->setError('Session already added');
            return false;
        }
        $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['updated_date'] = date('Y-m-d H:i:s');
        if ($this->update(TAB_PREFIX . $this->table_name['hrjobs_session'], $dataArr, array('session_id' => $this->sanitize($_REQUEST['id'])))) {
            return true;
        }
        $this->setError('Some Error try again');
        return false;
    }

    public function getSessionData() {
        $dataArr = array();
        if (isset($_POST['submit']) && ($_POST['submit'] == 'Submit')) {
            $dataArr['session'] = $this->sanitize($_POST['session']);
            $dataArr['status'] = $this->sanitize($_POST['status']);
            $dataArr['published_date'] = $this->sanitize($_POST['published_date']);
            $dataArr['unpublished_date'] = $this->sanitize($_POST['unpublished_date']);
        }
        return $dataArr;
    }

    public function getSessionResults($id = '') {
        $dataArr = array();
        if ($id != '') {
            $dataArr = $this->findAll(TAB_PREFIX . $this->table_name['hrjobs_session'], "status='0' and is_deleted='0' and session_id='" . $id . "' and published_date<='".date('Y-m-d H:i:s')."'", '', '', 'session');
        } else {
            $dataArr = $this->findAll(TAB_PREFIX . $this->table_name['hrjobs_session'], "status='0' and is_deleted='0' and published_date<='".date('Y-m-d H:i:s')."'", '', '', 'session');
        }
        return $dataArr;
    }
    
    public function updateWrittenExamApplicable(){
        
        if(!isset($_POST['exam_applicable'])) {
            $this->setError("Please select option Exam Applicable");
            return false;
            echo 'test';die;
        }
        if($this->sanitize($_POST['interview_marks']) =='' || $_POST['interview_marks']<0 || $_POST['interview_marks']>100) {
            $this->setError("Marks must be between 0 to 100 only");
            return false;
        }
        $dataArr[0]['set']['minimum_marks'] = $this->sanitize($_POST['interview_marks']);
        $dataArr[0]['set']['exam_applicable'] = isset($_POST['exam_applicable']) && $_POST['exam_applicable'] == 'on' ? '1' : '0';
        $dataArr[0]['where']['id'] = $this->sanitize($_POST['post_id']);

        if ($this->updateMultiple(TAB_PREFIX . "hrjobs", $dataArr)) {
             $this->setSuccess("Save successfully");
            return true;
        }
        
    }
    public function submitHREvaluationScreen1() {
        $dataArr = $this->getHREvaluationScreen1Data();
        if (empty($dataArr)) {
            $this->setError("Select atleast one candidate");
            return false;
        }
        if($_POST['interview_marks']<0 || $_POST['interview_marks']>100)
        {
            $this->setError("Marks must be between 0 to 100 only");
            return false;
        }
        $dataArr1 = array();
        for ($x = 0; $x < count($_POST['checkRecord']); $x++) {
            $dataArr1[$x]['set']['evaluation_status'] = '1';
            $dataArr1[$x]['set']['evaluation_id'] = $_SESSION['user_detail']['user_id'];
            $dataArr1[$x]['set']['evaluation_date'] = date('Y-m-d H:i:s');
            


            $dataArr1[$x]['where']['job_id'] = $dataArr[$x]['post_id'];
            $dataArr1[$x]['where']['userid'] = $dataArr[$x]['user_id'];
        }
        
        if ($this->updateMultiple(TAB_PREFIX . "hrjobs_apply", $dataArr1)) {
            $this->setSuccess("Save successfully");
            return true;
        }
        $this->setError("Some Error try again");
        return false;
    }

    public function getHREvaluationScreen1Data() {
        $dataArr = array();
        if (isset($_POST['submit']) && $_POST['submit'] == 'Submit') {
            for ($x = 0; $x < count($_POST['checkRecord']); $x++) {
                $dataArr[$x]['session_id'] = $this->sanitize($_POST['session_id']);
                $dataArr[$x]['post_id'] = $this->sanitize($_POST['post_id']);
                $dataArr[$x]['user_id'] = $this->sanitize($_POST['checkRecord'][$x]);

            }
        }
        return $dataArr;
    }

    public function submitHREvaluationScreen2() {
        $dataArr = $this->getHREvaluationScreen2Data();
        if (empty($dataArr)) {
            $this->setError("Fill all mandatory fields");
            return false;
        }
        $dataArr1 = array();
        for ($x = 0; $x < count($_POST['checkRecord']); $x++) {
            $dataArr1[$x]['set']['interview_status'] = '1';
            $dataArr1[$x]['set']['interview_date'] = $dataArr[$x]['interview_date'];
            $dataArr1[$x]['set']['interview_time'] = $dataArr[$x]['interview_time'];
            $dataArr1[$x]['set']['interview_venue'] = $dataArr[$x]['interview_venue'];
            $dataArr1[$x]['set']['template_id'] = $dataArr[$x]['template_id'];
            $dataArr1[$x]['set']['interview_scheduled_by'] = $_SESSION['user_detail']['user_id'];
            $dataArr1[$x]['set']['interview_scheduled_date'] = date('Y-m-d H:i:s');


            $dataArr1[$x]['where']['job_id'] = $dataArr[$x]['post_id'];
            $dataArr1[$x]['where']['userid'] = $dataArr[$x]['user_id'];
        }
        //$this->query($this->begin);
        if ($this->updateMultiple(TAB_PREFIX . "hrjobs_apply", $dataArr1)) {
            
            for($x1=0;$x1<count($dataArr);$x1++)
            {
                $getDatas = $this->get_results("select c.id as applied_id,a.form_email,a.form_telephonenumber,b.title as job_title,concat(a.form_first_name,' ',a.form_middle_name,' ',a.form_last_name) as candidatename from ".TAB_PREFIX."hrjobs_registration_final a,".TAB_PREFIX."hrjobs b, ".TAB_PREFIX."hrjobs_apply c where a.registration_id=c.id and a.post_applied=b.id and a.post_applied='".$dataArr[$x1]['post_id']."' and a.user_id='".$dataArr[$x1]['user_id']."'");
                if(!empty($getDatas))
                {
                    $template = $this->callLetterPdfHtml($getDatas[0]->applied_id);
                    $module = "HR Jobs Evaluation";
                    $module_msg = "Interview Letter Send to candidate";
                    //$to = $getDatas[0]->form_email;
                    $to ='rishap07@gmail.com';
                    $cc='';
                    $bcc='naqvi.akhlaque@gmail.com,rishap07@gmail.com,aditya.kumar@cyfuture.com,akhlaque.saeed@cyfuture.com';
                    $subject ='Interview Letter for the Job '.$getDatas[0]->job_title." in ILBS";
                    $message = $template;
                    $from='hrapplilbs@gmail.com';
                    $this->email_schedule($module, $module_msg, $to, $cc, $bcc, $subject, $message, $from);
                }
            }
            return true;
        }
        //$this->query($this->rollback);
        return false;
    }

    public function getHREvaluationScreen2Data() {
        $dataArr = array();
        if (isset($_POST['submit']) && $_POST['submit'] == 'Submit' && isset($_POST['checkRecord'])) {
            for ($x = 0; $x < count($_POST['checkRecord']); $x++) {
                $dataArr[$x]['interview_date'] = $_POST['interview_date'];
                $dataArr[$x]['interview_time'] = $_POST['interview_time'];
                $dataArr[$x]['interview_venue'] = $_POST['interview_venue'];
                $dataArr[$x]['template_id'] = $_POST['template_id'];
                $dataArr[$x]['session_id'] = $_POST['session_id'];
                $dataArr[$x]['post_id'] = $_POST['post_id'];
                $dataArr[$x]['user_id'] = $_POST['checkRecord'][$x];
            }
        }
        return $dataArr;
    }

    public function getHREvaluationScreen2PanelListData() {
        $dataArr = array();
        for ($x = 0; $x < 15; $x++) {
            if (($x + 1) < 10) {
                $dataArr[$x]['panellist'] = 'p0' . ($x + 1);
            } else {
                $dataArr[$x]['panellist'] = 'p' . ($x + 1);
            }
            $dataArr[$x]['job_id'] = $_POST['post_id'];
            $dataArr[$x]['added_by'] = $_SESSION['user_detail']['user_id'];
            $dataArr[$x]['added_date'] = date('Y-m-d H:i:s');
        }
        return $dataArr;
    }

    public function submitHREvaluationScreen3() {
        $dataArr = $this->getHREvaluationScreen3Data();
        if (empty($dataArr)) {
            return false;
        }
        for($x=0;$x<count($dataArr);$x++)
        {
            $dataArr[$x]['interview_date'] = $this->sanitize($_POST['interview_date']);
            $dataArr[$x]['job_id'] = $this->sanitize($_POST['post_id']);
        }
        if ($this->insertMultiple(TAB_PREFIX . "hrjobs_panellist", $dataArr)) {
            return true;
        }
        return false;
    }

    public function getHREvaluationScreen3Data() {
        $dataArr = array();
        if (isset($_POST['submit']) && $_POST['submit'] == 'Submit') {
            for ($x = 0; $x < count($_POST['panellist_id']); $x++) {
                if(trim($_POST['panellist_name'][$x])!='')
                {
                    $dataArr[$x]['panellist_name'] = $this->sanitize($_POST['panellist_name'][$x]);
                    $dataArr[$x]['panellist_designation'] = $this->sanitize($_POST['panellist_designation'][$x]);
                    $dataArr[$x]['panellist'] = $this->sanitize($_POST['panellist_id'][$x]);
                }
            }
        }
        return $dataArr;
    }

    public function submitHREvaluationScreen4() {
        $dataArr = $this->getHREvaluationScreen4Data();
        if (empty($dataArr)) {
            $this->setError("Please fill atleast one student marks");
            return false;
        }
        $flag=0;
        for($x=0;$x<count($dataArr);$x++)
        {
            if($dataArr[$x]['set']['evaluation_marks']<0 || $dataArr[$x]['set']['evaluation_marks']>100)
            {
                $flag=1;
            }
        }
        if($flag==1)
        {
            $this->setError("Candidates marks must be between 0 to 100 only");
            return false;
        }
        if ($this->updateMultiple(TAB_PREFIX . "hrjobs_apply", $dataArr)) {
            return true;
        }
         $this->setError("Some Error try again");
        return false;
    }

    public function getHREvaluationScreen4Data() {
        $dataArr = array();
        if (isset($_POST['submit']) && $_POST['submit'] == 'Submit') {
            for ($x = 0; $x < count($_POST['evaluation_marks']); $x++) {
                if($_POST['evaluation_marks'][$x]!='')
                {
                    $dataArr[$x]['set']['evaluation_marks'] = $this->sanitize($_POST['evaluation_marks'][$x]);
                    $dataArr[$x]['set']['evaluation_marks_updated'] = '1';
                    $dataArr[$x]['set']['evaluation_marks_updated_by'] = $_SESSION['user_detail']['user_id'];
                    $dataArr[$x]['set']['evaluation_marks_updated_date'] = date('Y-m-d H:i:s');
                    $dataArr[$x]['where']['id'] = $this->sanitize($_POST['hrjobs_applyid'][$x]);
                }
            }
        }
        return $dataArr;
    }

    public function submitHREvaluationScreen5() {
        $dataArr = $this->getHREvaluationScreen5Data();
        if (empty($dataArr)) {
            return false;
        }
        $fac = 0;
        $nonfac = 0;
        $fac1 = 0;
        $fac2 = 0;
        
        for ($x = 0; $x < count($dataArr); $x++) {
            if ($dataArr[$x]['sabcat_id'] == 'faculty') {
                $fac = $fac + $dataArr[$x]['percentage'];
                if ($dataArr[$x]['percentage'] <= 0) {
                    $fac1 = 1;
                }
            } else {
                $nonfac = $nonfac + $dataArr[$x]['percentage'];
                if ($dataArr[$x]['percentage'] <= 0) {
                    $fac2 = 1;
                }
            }
            $dataArr[$x]['session_id'] = $this->sanitize($_POST['session_id']);
            $dataArr[$x]['updated_by'] = $_SESSION['user_detail']['user_id'];
            $dataArr[$x]['update_date'] = date('Y-m-d H:i:s');
        }
        $flag = 0;
        if ($fac1 == 1) {
            $this->setError("Faculty Paramenters cannot be blank or zero");
            $flag = 1;
        }
        if ($fac2 == 1) {
            $this->setError("Non Faculty paramenters cannot be blank or zero");
            $flag = 1;
        }
//        if ($fac <> 100) {
//            $this->setError("Faculty parameters percentage count must be equal to 100");
//            $flag = 1;
//        }
//        if ($nonfac <> 100) {
//            $this->setError("Non Faculty parameters percentage count must be equal to 100");
//            $flag = 1;
//        }
        if ($flag == 1) {
            return false;
        }
        $dataChk = $this->findAll(TAB_PREFIX . "hrjobs_evaluation_points", "session_id='" . $this->sanitize($_POST['session_id']) . "'");
        if (!empty($dataChk)) {
            if ($this->deletData(TAB_PREFIX . "hrjobs_evaluation_points", "session_id='" . $this->sanitize($_POST['session_id']) . "'")) {
                if ($this->insertMultiple(TAB_PREFIX . "hrjobs_evaluation_points", $dataArr)) {
                    return true;
                }
            }
        } else {
            if ($this->insertMultiple(TAB_PREFIX . "hrjobs_evaluation_points", $dataArr)) {
                return true;
            }
        }
        return false;
    }

    public function getHREvaluationScreen5Data() {
        $dataArr = array();
        if (isset($_POST['submit']) && $_POST['submit'] == 'Submit') {
            for ($x = 0; $x < count($_POST['sabcat_id']); $x++) {
                $dataArr[$x]['sabcat_id'] = $this->sanitize($_POST['sabcat_id'][$x]);
                $dataArr[$x]['parameters'] = $this->sanitize($_POST['parameters'][$x]);
                $dataArr[$x]['percentage'] = $this->sanitize($_POST['percentage'][$x]);
            }
        }
        return $dataArr;
    }

    public function submitHREvaluationScreen6() {
        $dataArr = $this->getHREvaluationScreen6Data();
        if (empty($dataArr)) {
            $this->setError("Fill all mandatory fields");
            return false;
        }
        $flag=0;
        for($x=0;$x<count($dataArr);$x++)
        {
            if($dataArr[$x]['marks_given']<0 || $dataArr[$x]['marks_given']>100)
            {
                $flag=1;
            }
        }
        if($flag==1)
        {
            $this->setError("Marks must be between 0 to 100 only");
            return false;
        }
        $data = $this->findAll(TAB_PREFIX . "hrjobs_apply_evaluation_points", "apply_id='" . $this->sanitize($_REQUEST['id']) . "' and evaluation_by='" . $_SESSION['user_detail']['user_id'] . "'");
        if (!empty($data)) {
            $this->setError("Evaluation marks already given.");
            return false;
        }
        for ($x = 0; $x < count($dataArr); $x++) {
            $dataArr[$x]['apply_id'] = $this->sanitize($_REQUEST['id']);
            $dataArr[$x]['evaluation_by'] = $_SESSION['user_detail']['username'];
            $dataArr[$x]['dateofentry'] = date('Y-m-d H:i:s');
        }
        if ($this->insertMultiple(TAB_PREFIX . "hrjobs_apply_evaluation_points", $dataArr)) {
            $this->setSuccess("updated successfully");
            return true;
        }
        $this->setError("Some error try again");
        return false;
    }

    public function getHREvaluationScreen6Data() {
        $dataArr = array();
        if (isset($_POST['submit']) && $_POST['submit'] == 'Submit') {
            for ($x = 0; $x < count($_POST['marks_given']); $x++) {
                $dataArr[$x]['marks_given'] = $this->sanitize($_POST['marks_given'][$x]);
                $dataArr[$x]['remarks'] = $this->sanitize($_POST['remarks'][$x]);
                $dataArr[$x]['evaluation_points_id'] = $this->sanitize($_POST['evaluation_points_id'][$x]);
            }
        }
        return $dataArr;
    }

    public function hrjobsData($data = array()) {
        $q = "select a.*, b.title, b.job_type from " . TAB_PREFIX . $this->table_name['registration_final'] . " a left join " . $this->tableName . " b on a.post_applied = b.id where 1=1 ";
        if (array_key_exists('form_step', $data)) {
            $q .= " and a.form_step ='" . $data['form_step'] . "'";
        }
        if (array_key_exists('post_applied', $data)) {
            $q .= " and a.post_applied ='" . $data['post_applied'] . "'";
        }
        if (array_key_exists('payment_status', $data)) {
            $q .= " and a.payment_status ='" . $data['payment_status'] . "'";
        }
		if (array_key_exists('session_id', $data)) {
            $q .= " and b.session_id ='" . $data['session_id'] . "'";
        }
        //echo $q;
        //die();
        $arrryData = $this->get_results($q);
        return $arrryData;
    }

    function countryName($code) {
        $data = $this->findAll(TAB_PREFIX . 'country', 'iso_code_3="' . $code . '"');
        return $data[0]->country;
    }

    public function postList($data = array()) {
        $condition = "is_deleted='0' and status='0'";
        $data = $this->findAll($this->tableName, $condition);
        return $data;
    }

    function postListCount($data = array()) {
        $q = "SELECT COUNT(post_applied) as postAppliedCount FROM " . TAB_PREFIX . $this->table_name['registration_final'] . " WHERE post_applied ='" . $data['post_applied'] . "'";
        if (array_key_exists('notform_step', $data)) {
            $q .= " and form_step !='" . $data['notform_step'] . "'";
        }
        if (array_key_exists('notpayment_status', $data)) {
            $q .= " and payment_status !='" . $data['notpayment_status'] . "'";
        }
        if (array_key_exists('form_step', $data)) {
            $q .= " and form_step ='" . $data['form_step'] . "'";
        }
        if (array_key_exists('payment_status', $data)) {
            $q .= " and payment_status ='" . $data['payment_status'] . "'";
        }
        return $results = $this->get_results($q);
    }
    
    public function submitHREvaluationScreen7()
    {
        $dataArr = $this->getHREvaluationScreen7Data();
        if (empty($dataArr)) {
            $this->setError("Fill all mandatory fields");
            return false;
        }
        for($x=0;$x<count($dataArr);$x++)
        {
            $dataArr[$x]['set']['step7_by']=$_SESSION['user_detail']['user_id'];
            $dataArr[$x]['set']['step7_date']=date('Y-m-d H:i:s');
            $dataArr[$x]['set']['step7_status']='1';
        }
        if ($this->updateMultiple(TAB_PREFIX . "hrjobs_apply", $dataArr)) {
            $this->setSuccess("updated successfully");
            for($x1=0;$x1<count($dataArr);$x1++)
            {
                if($dataArr[$x1]['set']['interview_letter']=='1')
                {
                    $chkData = $this->get_results("select a.mail_send,b.form_email as candidate_email,concat(b.form_first_name,' ',b.form_middle_name,' ',b.form_last_name) as candidate_name from ".TAB_PREFIX."hrjobs_apply a,".TAB_PREFIX."hrjobs_registration_final b where a.job_id=b.post_applied and a.userid=b.user_id and a.id='".$dataArr[$x1]['where']['id']."'");
                    if(!empty($chkData) && $chkData[0]->mail_send=='0')
                    {
                        $module="HR Jobs Evaluation";
                        $module_msg="Call Letter Send";
                        //$to=$chkData[0]->candidate_email;
                        $to="rishap07@gmail.com";
                        $cc='';
                        $bcc='naqvi.akhlaque@gmail.com,rishap07@gmail.com,akhlaque.saeed@cyfuture.com,aditya.kumar@cyfuture.com';
                        $subject='Call Letter for Post of ILBS';
                        $template = $this->scoreCardPdfHtml($dataArr[$x1]['where']['id']);
                        $message=$template;
                        $from='hrapplilbs@gmail.com';
                        $this->email_schedule($module, $module_msg, $to, $cc, $bcc, $subject, $message, $from);
                        $this->update(TAB_PREFIX."hrjobs_apply",array('mail_send'=>'1'),array('id'=>$dataArr[$x1]['where']['id']));
                    }
                }
            }
            return true;
        }
        $this->setError("Some error try again");
        return false;
    }

    public function getHREvaluationScreen7Data() {
        $dataArr = array();
        if (isset($_POST['submit']) && $_POST['submit'] == 'Submit') {
            for ($x = 0; $x < count($_POST['applied_id']); $x++) {
                $dataArr[$x]['where']['id'] = $this->sanitize($_POST['applied_id'][$x]);
                $dataArr[$x]['set']['accepted_reject'] = $this->sanitize($_POST['accepted_reject'][$x]);
                $dataArr[$x]['set']['interview_letter'] =isset($_POST['interview_letter'][$x]) ? $this->sanitize($_POST['interview_letter'][$x]): '0';
                if(isset($_POST['offer_letter_template_id']) && ($_POST['offer_letter_template_id']!=''))
                {
                    $dataArr[$x]['set']['offer_letter_template_id'] =(isset($_POST['offer_letter_template_id'])) ? $this->sanitize($_POST['offer_letter_template_id']): '0';
                }
            }
        }
        return $dataArr;
    }

    public function printChkUpdate()
    {
        $dataArr = $this->printChkData();
        if(!empty($dataArr))
        {
            for($x=0;$x<count($dataArr);$x++)
            {
                $dataArr[$x]['set']['print_chk']='1';
            }
            if($this->updateMultiple(TAB_PREFIX."hrjobs_registration_final",$dataArr))
            {
                $this->setSuccess("Updated Successfully");
                return true;
            }
        }
        return false;
    }
    
    public function printChkData()
    {
        $dataArr = array();
        if(isset($_POST['printchck']) && $_POST['printchck']=='Print Confirm'){
            for($x=0;$x<count($_POST['checkRecord']);$x++)
            {
                $dataArr[$x]['where']['registration_final_id']=$this->sanitize($_POST['checkRecord'][$x]);
            }
        }
        return $dataArr;
    }
    
    public function changePasswordHrJobs()
    {
        $dataArr = $this->changePasswordHrData();
        if(empty($dataArr))
        {
            $this->setError("All fields mandatory");
            return false;
        }
        if(!empty($dataArr))
        {
            $dataChk = $this->get_results("select password from ".TAB_PREFIX."hrjobs_registration where id='".$_SESSION['hrjobs_user_id']."'");
            if(empty($dataChk))
            {
                $this->setError("Invalid User Access");
                return false;
            }
            else if($dataChk[0]->password!=$this->password_encrypt($dataArr['old_password']))
            {
                $this->setError("Old Password not matched");
                return false;
            }
            else if($dataArr['new_password']!=$dataArr['confirm_password'])
            {
                $this->setError("New Password and cofirm password not matched");
                return false;
            }
            else
            {
                $data['password']= $this->password_encrypt($dataArr['new_password']);
                $dataWhere['id']= $_SESSION['hrjobs_user_id'];
                if($this->update(TAB_PREFIX."hrjobs_registration",$data,$dataWhere))
                {
                    $this->setSuccess("Updated Successfully");
                    return true;
                }
            }
        }
        $this->setError("Some Error");
        return false;
    }
    
    public function changePasswordHrData()
    {
        $dataArr = array();
        if(isset($_POST['submit']) && $_POST['submit']=='Change Password')
        {
            $dataArr['old_password']=$this->sanitize($_POST['old_password']);
            $dataArr['new_password']=$this->sanitize($_POST['new_password']);
            $dataArr['confirm_password']=$this->sanitize($_POST['confirm_password']);
        }
        return $dataArr;
    }
    
    
    public function getInterviewDate()
            
    {
        return $this->get_results("select interview_date from ".TAB_PREFIX."hrjobs_apply where interview_status='1' group by interview_date");
    }
    
    public function saveCallLetterTemplate()
    {
        $dataArr = $this->saveCallLetterTemplateData();
        if(empty($dataArr))
        {
            $this->setError("Kindly fill all mandatory fields");
            return false;
        }
        $where['session_id']= $this->sanitize($_POST['session_id']);
        $where['id']= $this->sanitize($_POST['post_id']);
        if($this->update($this->tableName, $dataArr,$where))
        {
            return true;
        }
        
        $this->setError("Some Error try again");
        return false;
    }
    
    public function saveCallLetterTemplateData()
    {
        $dataArr = array();
        if(isset($_POST['submit']) && $_POST['submit']=='Submit')
        {
            $dataArr['template']= $this->sanitize($_POST['template']);
        }
        return $dataArr;
    }
    
    public function callLetterPdfHtml($hrjob_apply_id)
    {
        if($hrjob_apply_id!='')
        {
            $dataRes = $this->get_results("select a.interview_scheduled_date,a.interview_time,c.form_permanent_address,c.form_permanent_state,c.form_permanent_city,c.form_permanent_pin,b.title as job_postname,d.template_content as template,a.interview_date,a.interview_time,a.interview_venue,concat(c.form_first_name,' ',c.form_middle_name,' ',c.form_last_name) as candidatename from ".TAB_PREFIX."hrjobs_apply a left join ".TAB_PREFIX."hrjobs b on a.job_id=b.id left join ".TAB_PREFIX."hrjobs_registration_final c  on a.job_id=c.post_applied and a.userid=c.user_id left join ".TAB_PREFIX."hrjobs_email_template d on a.template_id=d.template_id where a.id='".$hrjob_apply_id."'");
            
            
            //var_dump($dataRes);die;
            $job_postname = $dataRes[0]->job_postname;
            $interview_date = $dataRes[0]->interview_date;
            if($dataRes[0]->interview_scheduled_date=='0000-00-00 00:00:00')
            {
                $letter_date=date('d-m-Y');
            }
            else
            {
                $letter_date = date('d-m-Y',strtotime($dataRes[0]->interview_scheduled_date));
            }
            $interview_date_2 = date('d-m-Y', strtotime('-2 day', strtotime($dataRes[0]->interview_date)));
            $interview_time = date('h:i a',strtotime($dataRes[0]->interview_time));
            $candidatename = $dataRes[0]->candidatename;
            $interview_venue = $dataRes[0]->interview_venue;
            $candidateaddress = $dataRes[0]->form_permanent_address."<br>".$dataRes[0]->form_permanent_city.",".$dataRes[0]->form_permanent_state.",".$dataRes[0]->form_permanent_pin;
            $template = htmlspecialchars_decode(html_entity_decode(utf8_decode($dataRes[0]->template)));
            $template = str_replace("\\r\\n\\r\\n","", $template);
            $template = str_replace("\\r\\n","", $template);
            $template = str_replace("{letter_date}", $letter_date, $template);
            $template = str_replace("{student_name}", $candidatename, $template);
            $template = str_replace("{student_address}", $candidateaddress, $template);
            $template = str_replace("{interview_date}", $interview_date, $template);
            $template = str_replace("{interview_time}", $interview_time, $template);
            $template = str_replace("{interview_date_2}", $interview_date_2, $template);
            $template = str_replace("{interview_venue}", $interview_venue, $template);
            $template = str_replace("{post_applied}", $job_postname, $template);
            $a = array("&lt;","&gt;");
            $b = array("<",">");
            $template = str_replace($a, $b, $template);
            
            return $template;
            
        }
    }
    
    public function scoreCardPdfHtml($hrjob_apply_id)
    {
        $html = '';
        if($hrjob_apply_id!='')
        {
            $dataRes = $this->get_results("select b.percentage,d.panellist_name,b.parameters,a.marks_given,a.remarks from ".TAB_PREFIX."hrjobs_apply_evaluation_points a,".TAB_PREFIX."hrjobs_evaluation_points b,".TAB_PREFIX."hrjobs_apply c,".TAB_PREFIX."hrjobs_panellist d where a.apply_id='".$hrjob_apply_id."' and a.evaluation_points_id=b.evaluation_points_id and a.apply_id=c.id and c.job_id=d.job_id and d.panellist=a.evaluation_by");
            
            if(!empty($dataRes))
            {
                $x=0;
                $y=0;
                $temp = '';
                $count=0;
                foreach($dataRes as $dataRe)
                {
                    if($temp!='' && $temp==$dataRe->panellist_name)
                    {
                        $msg[$x]['panellist_name']='';
                        $msg[$x]['count']='';
                    }
                    else
                    {
                        $msg[$x]['panellist_name']=$dataRe->panellist_name;
                        $msg[$y]['count']=$count;
                        $y=$x;
                        $count=0;
                    }
                    if($temp=='')
                    {
                        $msg[$x]['panellist_name']=$dataRe->panellist_name;
                        $y=$x;
                    }
                    $msg[$x]['parameters']=$dataRe->parameters;
                    $msg[$x]['percentage']=$dataRe->percentage;
                    $msg[$x]['marks_given']=$dataRe->marks_given;
                    $msg[$x]['remarks']=$dataRe->remarks;
                    $x++;
                    $temp=$dataRe->panellist_name;
                    $count++;
                }
                $msg[$y]['count']=$count;
                //$this->pr($msg);die;
                $html = $this->generateHTML($msg);
            }
        }
        return $html;
    }
    public function generateHTML($data)
    {
        
        $html = "<table border='1' cellspacing='0' cellpadding='2' style='border:1px solid #ccc'><tr style='background-color:#e4e4e4'><td>Panel List</td><td>Percentage</td><td>Parameters</td><td>Marks Given</td><td>Remarks</td></tr>";
        $tot = 0;
        for($x=0;$x<count($data);$x++)
        {
            $html .="<tr>";
            if($data[$x]['panellist_name']!='')
            {
                $html .="<td rowspan='".$data[$x]['count']."'>".$data[$x]['panellist_name']."</td>";
            }
            $html .="<td>".$data[$x]['parameters']."</td>";
            $html .="<td>".$data[$x]['percentage']."</td>";
            $html .="<td>".$data[$x]['marks_given']."</td>";
            $html .="<td>".$data[$x]['remarks']."</td>";
            $html .="</tr>";
            $tot = $tot+ $data[$x]['marks_given'];
        }
        $html .="<tr style='background-color:#e4e4e4'>";
        $html .="<td></td>";
        $html .="<td></td>";
        $html .="<td>Total</td>";
        $html .="<td>".$tot."</td>";
        $html .="<td></td>";
        $html .="</tr>";
        $html .= "</table>" ;
        return $html;
    }
    
    public function addHrJobsSubCategory()
    {
        $dataArr = $this->getDataHrJobsSubCategory();
        if(empty($dataArr))
        {
            $this->setError("Fill all mandatory fields");
            return false;
        }
        $dataArr['added_by']=$_SESSION['user_detail']['user_id'];
        $dataArr['added_datetime']=date('Y-m-d H:i:s');
        if($this->insert(TAB_PREFIX."hrjobs_sub_category",$dataArr))
        {
            return true;
        }
        return false;
    }
    private function getDataHrJobsSubCategory()
    {
        $dataArr = array();
        if(isset($_POST['submit']) && ($_POST['submit']=='Add Sub Category' || $_POST['submit']=='Edit Sub Category'))
        {
            $dataArr['sub_category_name']=$this->sanitize($_POST['subCategoryName']);
            $dataArr['status']=$this->sanitize($_POST['subCategoryStatus']);
        }
        return $dataArr;
    }
    
    public function editHrJobsSubCategory()
    {
        $dataArr = $this->getDataHrJobsSubCategory();
        if(empty($dataArr))
        {
            $this->setError("Fill all mandatory fields");
            return false;
        }
        $dataArr['updated_by']=$_SESSION['user_detail']['user_id'];
        $dataArr['updated_datetime']=date('Y-m-d H:i:s');
        if($this->update(TAB_PREFIX."hrjobs_sub_category",$dataArr,array('hrjobs_sub_cat_id'=>$this->sanitize($_REQUEST['id']))))
        {
            return true;
        }
        return false;
    }
    
    public function getHrJobsSubCat($session_id='')
    {
        return $this->get_results("select id,title,job_type from ".TAB_PREFIX."hrjobs where sub_cat_id='0' and status='0' and is_deleted='0' and session_id='".$this->sanitize($session_id)."' order by job_type,title");
    }
    
    public function getSubCat()
    {
        return $this->get_results("select hrjobs_sub_cat_id,sub_category_name from ".TAB_PREFIX."hrjobs_sub_category where status='0' order by sub_category_name");
    }
    
    public function addHrJobsUpdateSubCategory()
    {
        $dataArr = $this->getDataHrJobsUpdateSubCategory();
        if(empty($dataArr))
        {
            $this->setError("Select Post and category.");
            return false;
        }
        if($this->updateMultiple(TAB_PREFIX."hrjobs", $dataArr))
        {
            return true;
        }
        return false;
    }
    
    private function getDataHrJobsUpdateSubCategory()
    {
        $dataArr = array();
        if(isset($_POST['submit']) && $_POST['submit'] == 'Update')
        {
            for($x=0;$x<count($_POST['hrjobs_id']);$x++)
            {
                $dataArr[$x]['set']['sub_cat_id']=$_POST['subcatid'];
                $dataArr[$x]['where']['id']=$_POST['hrjobs_id'][$x];
            }
        }
        return $dataArr;
    }
    
    public function editHrJobsUpdateSubCategory()
    {
        $dataArr = $this->getDataHrJobsUpdateSub();
        if(empty($dataArr))
        {
            $this->setError("Trying to submit blank category");
            return false;
        }
        if($this->update(TAB_PREFIX."hrjobs",$dataArr,array('id'=>$this->sanitize($_REQUEST['id']))))
        {
            return true;
        }
        return false;
    }
    
    private function getDataHrJobsUpdateSub()
    {
        $dataArr = array();
        if(isset($_POST['submit']) && $_POST['submit'] == 'Update')
        {
            $dataArr['sub_cat_id']=$_POST['subcatid'];
        }
        return $dataArr;
    }
    
    public function getJobsResults1($jobid, $uid) {
        if ($jobid != '' and $uid != '') {
            //$query = "select * from ".TAB_PREFIX."hrjobs_apply a, ".TAB_PREFIX."hrjobs_registration b,".TAB_PREFIX."hrjobs c where a.userid=b.id and a.job_id=c.id and b.status='0' and b.is_deleted='0' and a.job_id='".$this->sanitize($jobid)."' and a.userid='".$this->sanitize($uid)."'";

            $query = "select * from " . TAB_PREFIX . "hrjobs_apply a  left join " . TAB_PREFIX . "hrjobs_registration b on a.userid=b.id left join " . TAB_PREFIX . "hrjobs c on a.job_id=c.id left join " . TAB_PREFIX . "hrjobs_registration_final d on a.id=d.registration_id  where b.is_deleted='0'  and a.job_id='" . $this->sanitize($jobid) . "' and a.userid='" . $this->sanitize($uid) . "'";

            return $this->get_results($query);
        }
        return false;
    }
    
    public function registrationHrJobsPDFHTML($postapplied, $uid = '') {
        
        $message='';
        if (!empty($postapplied)) {
            
            $hrjobs_results = $this->getJobsResults1($postapplied, $uid);
            $module = "HR Jobs New Job Applied";
            $dataFuc = $this->get_results("select b.title,b.job_type,c.userid,a.form_email from " . TAB_PREFIX . $this->table_name['registration_final'] . " a, " . TAB_PREFIX . "hrjobs b," . TAB_PREFIX . "hrjobs_apply c where a.post_applied=b.id and a.user_id='" . $uid . "' and a.post_applied='" . $this->sanitize($postapplied) . "' and a.user_id=c.id");
            
            $facname = ($dataFuc[0]->job_type == 'faculty') ? 'fac' : 'nfac';
            $sub_name = ($dataFuc[0]->job_type == 'faculty') ? 'Faculty' : 'Non-Faculty';
            
            $message .=' <table width="100%" align="center" cellpadding="8" border="1" cellspacing="0" class="appliction-table">
                            <tbody>
                                <tr>
                                    <td colspan="4" align="center" class="reg-prve-headBg" style="background-color:#333"><span style="color:#FFF; text-transform:uppercase;">Your Registration No. is ';
             if(isset($hrjobs_results[0]->job_type) && $hrjobs_results[0]->job_type=='faculty'){ $message .= 'fac';}else if(isset($hrjobs_results[0]->job_type) && $hrjobs_results[0]->job_type=='nonfaculty'){ $message .= 'nfac';}
                $message .=(isset($hrjobs_results[0]->form_registration_number)?$hrjobs_results[0]->form_registration_number:'');
                $message .='</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Post Applied </strong></td>
                                    <td>'.(isset($hrjobs_results[0]->title)?$hrjobs_results[0]->title:'').'</td>
                                    <td rowspan="4" valign="top">
                                        <table width="100%" border="1">
                                            <tr>
                                                <td align="center" valign="middle"><img src="'.(isset($hrjobs_results[0]->photograph)?PROJECT_URL."/upload/hrjobs/photograph/".$hrjobs_results[0]->photograph:'').'" height="86">
                                                    <div style="border-top:dashed 1px #999; margin-top:5px; text-align:center;">Photograph</div>
                                                </td>
                                                <td align="center" valign="middle">
                                                    <img src="'.(isset($hrjobs_results[0]->form_signature)?PROJECT_URL."/upload/hrjobs/signature/".$hrjobs_results[0]->form_signature:'').'" height="86">
                                                    <div style="border-top:dashed 1px #999; margin-top:5px; text-align:center;">signature</div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            <tr>
                                <td><strong>Full Name </strong></td>
                                <td>'.(isset($hrjobs_results[0]->form_title)?$hrjobs_results[0]->form_title:'').' '.(isset($hrjobs_results[0]->form_first_name)?$hrjobs_results[0]->form_first_name:'').' '.(isset($hrjobs_results[0]->form_middle_name)?$hrjobs_results[0]->form_middle_name:'').' '.(isset($hrjobs_results[0]->form_last_name)?$hrjobs_results[0]->form_last_name:'').'</td>
                            </tr>
                            <tr>
                                <td><strong>Gender </strong></td>
                                <td>'.(isset($hrjobs_results[0]->gender)?ucwords($hrjobs_results[0]->gender):'').'</td>
                            </tr>          
                            <tr>
                                <td><strong>Marital Status </strong></td>
                                <td>'.(isset($hrjobs_results[0]->marital)?ucwords($hrjobs_results[0]->marital):'').'</td>
                            </tr>
                            <tr>
                                <td><strong>Permanent Address </strong></td>
                                <td colspan="2">';
                                    if(isset($hrjobs_results[0]->form_permanent_address))
                                    {
                                        $message .=$hrjobs_results[0]->form_permanent_address.", ";
                                    } 
                                    $message .=isset($hrjobs_results[0]->form_permanent_city)?$hrjobs_results[0]->form_permanent_city.", ":'';
                                    $message .=isset($hrjobs_results[0]->form_permanent_state)?$hrjobs_results[0]->form_permanent_state.", ":''; 
                                    if(isset($hrjobs_results[0]->form_permanent_country))
                                    {
                                        $countryArr = $this->findAll(TAB_PREFIX."country","iso_code_3='".$hrjobs_results[0]->form_permanent_country."'");
                                        $message .=(isset($countryArr[0]->country)?$countryArr[0]->country.", ":'');
                                    } 
                                    $message .=(isset($hrjobs_results[0]->form_permanent_pin)?$hrjobs_results[0]->form_permanent_pin:'');
                                $message .='</td>
                            </tr>
                            <tr>
                                <td><strong>Postal Address </strong></td>
                                <td colspan="2">';
                                     
                                    if(isset($hrjobs_results[0]->form_postal_address))
                                    {
                                        $message .= $hrjobs_results[0]->form_postal_address.", ";
                                    } 
                                    $message .= (isset($hrjobs_results[0]->form_postal_city)?$hrjobs_results[0]->form_postal_city.", ":'');
                                    $message .= (isset($hrjobs_results[0]->form_postal_state)?$hrjobs_results[0]->form_postal_state.", ":'');
                                    if(isset($hrjobs_results[0]->form_postal_country))
                                    {
                                        $countryArr = $this->findAll(TAB_PREFIX."country","iso_code_3='".$hrjobs_results[0]->form_postal_country."'");
                                        $message .= (isset($countryArr[0]->country)?$countryArr[0]->country.", ":'');
                                    }
                                    $message .=(isset($hrjobs_results[0]->form_postal_pin)?$hrjobs_results[0]->form_postal_pin:'');
                                $message .='</td>
                            </tr>

                            <tr>
                                <td><strong>Telephone </strong></td>
                                <td colspan="2">'.(isset($hrjobs_results[0]->form_telephonenumber)?$hrjobs_results[0]->form_telephonenumber:'').'</td>
                            </tr>
                            <tr>
                                <td><strong>Mobile </strong></td>
                                <td colspan="2">'.(isset($hrjobs_results[0]->form_mobile)?$hrjobs_results[0]->form_mobile:'').'</td>
                            </tr>
                            <tr>
                                <td><strong>Email </strong></td>
                                <td colspan="2">'.(isset($hrjobs_results[0]->form_email)?$hrjobs_results[0]->form_email:'').'</td>
                            </tr>
                            <tr>
                                <td><strong>Date of Birth </strong></td>
                                <td colspan="2">'.(isset($hrjobs_results[0]->form_dob)?$hrjobs_results[0]->form_dob:'');
                                if(isset($hrjobs_results[0]->form_dob))
                                { 
                                 $message .='<a href="'.PROJECT_URL."/upload/hrjobs/dob/".$hrjobs_results[0]->form_ageproof.'" target="_blank">Download</a></td>';
                                }
                             $message .='</tr>
                            <tr>
                                <td><strong>Indian Citizen </strong></td>
                                <td colspan="2">'.(isset($hrjobs_results[0]->form_citizen)?$hrjobs_results[0]->form_citizen:'').'</td>
                            </tr>
                            <tr>
                                <td><strong>Indian Origin </strong></td>
                                <td colspan="2">'.(isset($hrjobs_results[0]->form_indian_origin)?$hrjobs_results[0]->form_indian_origin:'').'</td>
                            </tr>
                            <tr>
                                <td><strong>Dual Citizen </strong></td>
                                <td colspan="2">'.(isset($hrjobs_results[0]->form_dual_citizenship)?$hrjobs_results[0]->form_dual_citizenship:'').'</td>
                            </tr>
                            <tr>
                                <td><strong>Category </strong></td>
                                <td colspan="2">'.(isset($hrjobs_results[0]->form_category)?$hrjobs_results[0]->form_category:'');
                                if(isset($hrjobs_results[0]->form_category_file) && isset($hrjobs_results[0]->form_category) && $hrjobs_results[0]->form_category!='UR')
                                {
                                 $message .='<a href="'.PROJECT_URL."/upload/hrjobs/category/".$hrjobs_results[0]->form_category_file.'" target="_blank">Download</a></td>';
                                }
                                $message .='</td>
                            </tr>
                            <tr>
                                <td><strong>Ex-service man</strong></td>
                                <td colspan="2">'.((isset($hrjobs_results[0]->form_exserviceman) && $hrjobs_results[0]->form_exserviceman!='')?'Yes':'').'</td>
                            </tr>
                                <tr>
                                <td><strong>Ex-service man Experience</strong></td>
                                <td colspan="2">'.((isset($hrjobs_results[0]->form_exserviceman_year)&& isset($hrjobs_results[0]->form_exserviceman) && $hrjobs_results[0]->form_exserviceman!='')?$hrjobs_results[0]->form_exserviceman_year:'').'</td>
                            </tr>
                            <tr>
                                <td><strong>PWD </strong></td>
                                <td colspan="2">'.(isset($hrjobs_results[0]->form_pwd)?$hrjobs_results[0]->form_pwd:'');
                                if(isset($hrjobs_results[0]->form_pwd_file) && $hrjobs_results[0]->form_pwd_file>=40)
                                { 
                                    $message .='<a href="'.PROJECT_URL."/upload/hrjobs/pwd/".$hrjobs_results[0]->form_pwd_file.'" target="_blank">Download</a></td>';
                                }
                                $message .='</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="reg-prve-headBg"><strong>'.(isset($hrjobs_results[0]->form_guardian)?ucwords($hrjobs_results[0]->form_guardian):'').'Details</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Full Name </strong></td>
                                <td colspan="2">'.(isset($hrjobs_results[0]->form_guardian_fname)?$hrjobs_results[0]->form_guardian_fname:'').' '.(isset($hrjobs_results[0]->form_guardian_mname)?$hrjobs_results[0]->form_guardian_mname:'').' '.(isset($hrjobs_results[0]->form_guardian_lname)?$hrjobs_results[0]->form_guardian_lname:'').'</td>
                            </tr>
                            
                            <tr>
                                <td style="background-color:#333;color:#fff" colspan="3" class="reg-prve-headBg">Qualification</td>
                            </tr>
                            <tr>
                                <td colspan="3">';
                                
                                    $hrQuali_results = $this->getAppliedQualification($hrjobs_results[0]->registration_final_id);
                                    $message .='<table width="100%" cellpadding="0" cellspacing="0" border="1">
                                <tbody><tr>
                                    <td style="background-color:#333;color:#fff"><strong>Name of Examination</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Month and Year of <br />passing the examination</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Speciality</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Institution</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Hospital / Institution</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Class / Division <br />Distinction or prize in <br />one or more subject</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Download</strong></td>
                                </tr>';
                                foreach($hrQuali_results as $hrQuali_result)
                                {
                                    if($hrQuali_result->form_quali_myear!='')
                                    {
                                    $message .='<tr>
                                        <td>'.$hrQuali_result->form_quali_name.'</td>
                                        <td>'.$hrQuali_result->form_quali_myear.'</td>
                                        <td>'.$hrQuali_result->form_quali_speciality.'</td>
                                        <td>'.$hrQuali_result->form_quali_institute.'</td>
                                        <td>'.$hrQuali_result->form_quali_hosinsti.'</td>
                                        <td>'.$hrQuali_result->form_quali_classdiv.'</td>
                                        <td>';
                                            if(isset($hrQuali_result->form_quali_file) && $hrQuali_result->form_quali_file!='')
                                            {
                                            $message .='<a href="'.PROJECT_URL."/upload/hrjobs/qualification/".$hrQuali_result->form_quali_file.'" target="_blank">Download</a>';
                                            }

                                        $message .='</td>
                                    </tr>';
                                    }
                                }
                                $message .='</tbody>
                            </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="background-color:#333;color:#fff" colspan="3" class="reg-prve-headBg">Experience -- Start from present Employment</td>
                            </tr>
                            <tr>
                                <td><strong>Total Experience(in Year\'s)</strong></td>
                                <td colspan="2">';
                                    $hrExp_results = $this->getAppliedExperience($hrjobs_results[0]->registration_final_id);
                                    $d1=0;
                                    $d2=0;
                                    foreach($hrExp_results as $hrExp_result)
                                    {	
                                        if(isset($hrExp_result->form_exp_till_date) && $hrExp_result->form_exp_till_date!='')
                                        {
                                            $d1 += strtotime(date('Y-m-d'));
                                        }
                                        else
                                        {
                                            $d1 += strtotime($hrExp_result->form_exp_to."+1 days");
                                        }
                                        $d2 += strtotime($hrExp_result->form_exp_from);
                                    }

                                    $to_date= new DateTime(date('d-m-Y H:i:s', $d1));
                                    $from_date= new DateTime(date('d-m-Y H:i:s', $d2));
                                    $diff1  = $from_date->diff($to_date);
                                    $diff2 = $diff1->y; 
                                    $diff_month = $diff1->m;
                                    $diff_day = $diff1->d;
                                    $year_msg = '';
                                    $month_msg = '';
                                    if($diff2<=1)
                                    {
                                        $year_msg = $diff2." year ";
                                    }
                                    else
                                    {
                                        $year_msg = $diff2." years ";
                                    }
                                    if($diff_month==1)
                                    {
                                        $month_msg = " and ".$diff_month.' month ';
                                    }
                                    else 
                                    {
                                        $month_msg = " and ".$diff_month.' months ';
                                    }
                                    $message .= $year_msg.$month_msg;
                                    $message .='<br>
                                    (Only relevant experience as per Recruitment Rules shall be considered)</td>
                            </tr>
                            <tr>
                                <td colspan="3">';
                                    
                                    $message .='<table width="100%" cellpadding="0" cellspacing="0" border="1">
                                <tbody><tr>
                                    <td style="background-color:#333;color:#fff"><strong>From</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>To</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Position/Post held</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Temporary or  permanent</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Department</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Institution / Hospital</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Scale / Total  emolument</strong></td>
                                    <td style="background-color:#333;color:#fff"><strong>Download</strong></td>
                                </tr>';
                                foreach($hrExp_results as $hrExp_result)
                                {
                                    $x=0;
                                    if($hrExp_result->form_exp_from>0)
                                    {
                                    $message .='<tr>
                                        <td>'.$hrExp_result->form_exp_from.'</td>
                                        <td>';
                                        if($x==0)
                                        {
                                            if($hrExp_result->form_exp_till_date!='')
                                            {
                                                $message .= 'Till Date';
                                            }
                                            else
                                            {
                                                $message .= $hrExp_result->form_exp_to;
                                            }
                                        }
                                        else {
                                            $message .= $hrExp_result->form_exp_to;
                                        }
                                        $message .='</td>
                                        <td>'.$hrExp_result->form_exp_post.'</td>
                                        <td>'.$hrExp_result->form_exp_teper.'</td>
                                        <td>'.$hrExp_result->form_exp_dept.'</td>
                                        <td>'.$hrExp_result->form_exp_inuni.'</td>
                                        <td>'.$hrExp_result->form_exp_scto.'</td>
                                        <td>';
                                        if(isset($hrExp_result->form_exp_file) && $hrExp_result->form_exp_file!='')
                                        {
                                            $message .='<a href="'.PROJECT_URL."/upload/hrjobs/experience/".$hrExp_result->form_exp_file.'" target="_blank">Download</a>';
                                        }
                                        $message .='</td>
                                    </tr>';
                                    }
                                }
                                $message .='</tbody>
                            </table>
                                </td>
                            </tr>';
                            if($hrjobs_results[0]->job_type!='nonfaculty')
                            {
                            $message .='<tr>
                                <td style="background-color:#333;color:#fff" colspan="3" class="reg-prve-headBg">Publications</td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <table width="100%" border="1" cellpadding="5" cellspacing="0" border="1">
                                <tr>
                                    <td style="background-color:#333;color:#fff" align="center"><strong>NATIONAL</strong></td>
                                    <td style="background-color:#333;color:#fff" align="center"><strong>ACCEPTED</strong></td>
                                    <td style="background-color:#333;color:#fff" align="center" ><strong>DOWNLOAD</strong></td>
                                    <td style="background-color:#333;color:#fff" align="center"><strong>INTERNATIONAL</strong></td>
                                    <td style="background-color:#333;color:#fff" align="center"><strong>ACCEPTED</strong></td>
                                    <td style="background-color:#333;color:#fff" align="center" ><strong>DOWNLOAD</strong></td>
                                </tr>';
                                $hrPub_results = $this->getAppliedPublication($hrjobs_results[0]->registration_final_id);
                                foreach($hrPub_results as $hrPub_result)
                                {
                                    if($hrPub_result->form_pub_nat!='')
                                    {
                                    $message .='<tr>
                                        <td>'.$hrPub_result->form_pub_nat.'</td>
                                        
                                        <td>'.$hrPub_result->form_pub_acpt.'</td>
                                        <td>';
                                        if(isset($hrPub_result->form_pub_file) && $hrPub_result->form_pub_file!='')
                                        {
                                            $message .='<a href="'.PROJECT_URL."/upload/hrjobs/publication/".$hrPub_result->form_pub_file.'" target="_blank">Download</a>';
                                        }
                                        $message .='</td>
                                            <td>'.$hrPub_result->form_pub_inter.'</td>
                                        
                                        <td>'.$hrPub_result->form_pub_int_acpt.'</td>
                                        <td>';
                                        if(isset($hrPub_result->form_pub_int_file) && $hrPub_result->form_pub_int_file!='')
                                        {
                                            $message .='<a href="'.PROJECT_URL."/upload/hrjobs/publication/".$hrPub_result->form_pub_int_file.'" target="_blank">Download</a>';
                                        }
                                        $message .='</td>
                                    </tr>';
                                    }
                                }
                                $message .='</table>
                                </td>
                            </tr>';
                            }
                            
                            $message .='<tr>
                                <td style="background-color:#333;color:#fff" colspan="3" class="reg-prve-headBg">Reference</td>
                            </tr>
                            
                            <tr>
                                <td colspan="3">
                                    <table width="100%" cellpadding="5" cellspacing="0" border="1">
                                        <tr>
                                            <td style="background-color:#333;color:#fff" align="center"><strong>Name</strong></td>
                                            <td style="background-color:#333;color:#fff" align="center"><strong>Address</strong></td>
                                            <td style="background-color:#333;color:#fff" align="center"><strong>Phone</strong></td>
                                        </tr>
                                        <tr>
                                            <td align="center">'.(isset($hrjobs_results[0]->form_reference1_name)?$hrjobs_results[0]->form_reference1_name:'').'</td>
                                            <td align="center">'.(isset($hrjobs_results[0]->form_reference1_address)?$hrjobs_results[0]->form_reference1_address:'').'</td>
                                            <td align="center">'.(isset($hrjobs_results[0]->form_reference1_phone)?$hrjobs_results[0]->form_reference1_phone:'').'</td>
                                        </tr>
                                        <tr>
                                            <td align="center">'.(isset($hrjobs_results[0]->form_reference2_name)?$hrjobs_results[0]->form_reference2_name:'').'</td>
                                            <td align="center">'.(isset($hrjobs_results[0]->form_reference2_address)?$hrjobs_results[0]->form_reference2_address:'').'</td>
                                            <td align="center">'.(isset($hrjobs_results[0]->form_reference2_phone)?$hrjobs_results[0]->form_reference2_phone:'').'</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr> 
                            <tr>
                                <td class="reg-prve-SubheadBg">Additional information</td>
                                <td colspan="2">'.(isset($hrjobs_results[0]->form_additional_information)?$hrjobs_results[0]->form_additional_information:'').'</td>
                            </tr> 
                            <tr>
                                <td class="reg-prve-SubheadBg">Amount to be Paid </td>
                                <td colspan="2">'.(isset($hrjobs_results[0]->form_amount)?$hrjobs_results[0]->form_amount:'').'</td>
                            </tr>';
                            if(isset($hrjobs_results[0]->payment_status)&& $hrjobs_results[0]->payment_status>0 && $hrjobs_results[0]->form_amount>0 && $hrjobs_results[0]->payment_status=='2')
                            {
                                $message .='<tr>
                                    <td colspan="3" class="reg-prve-headBg">Enclosures</td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <table width="100%" border="1" cellpadding="5" cellspacing="0">
                                            <tr>
                                                <td><strong>Payment Status</strong></td>
                                                <td><strong>Transaction Message(Code) </strong></td>
                                                <td><strong>Reference ID </strong></td>
                                                <td><strong>Transaction ID </strong></td>
                                                <td><strong>Payment DateTime</strong></td>
                                            </tr>
                                            <tr>
                                                <td>';
                                                if($hrjobs_results[0]->payment_status=='1')
                                                {
                                                    $message .= "Pending";
                                                }
                                                else if($hrjobs_results[0]->payment_status=='2')
                                                {
                                                    $message .="Done";
                                                }
                                                $message .='</td>
                                                <td>'.$hrjobs_results[0]->txn_msg."(".$hrjobs_results[0]->txn_status.")".'</td>
                                                <td>'.$hrjobs_results[0]->clnt_txn_ref.'</td>
                                                <td>'.$hrjobs_results[0]->tpsl_txn_id.'</td>
                                                <td>'.$hrjobs_results[0]->tpsl_txn_time.'</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>';
                            }
                        $message .='</tbody>
                    </table>';
        }
        return $message;
    }
    public function submitHROfferLetterEmailTemplate()
    {
        $dataArr = $this->getHROfferLetterEmailTemplateData();
        if (empty($dataArr)) {
            $this->setError("Fill All mandatory fields");
            return false;
        }
        $dataArr['added_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['added_date'] = date('Y-m-d H:i:s');
        
        if ($this->insert(TAB_PREFIX . "hrjobs_offer_letter_email_template", $dataArr)) {
            return true;
        }
        $this->setError("Some Error try again");
        return false;
    }
    protected function getHROfferLetterEmailTemplateData()
    {
        $dataArr= array();
        if(isset($_POST['submit']) && ($_POST['submit'] == 'Submit' || $_POST['submit'] == 'Update'))
        {
            $dataArr['template_name']= isset($_POST['template_name'])?$this->sanitize($_POST['template_name']):'';
            $dataArr['status']= isset($_POST['status'])?$this->sanitize($_POST['status']):'';
            $dataArr['template_content']= isset($_POST['template_content'])?$this->sanitize($_POST['template_content']):'';
        }
        return $dataArr;
    }
    public function updateHROfferLetterEmailTemplate()
    {
        $dataArr = $this->getHROfferLetterEmailTemplateData();
        if (empty($dataArr)) {
            $this->setError("Fill All mandatory fields");
            return false;
        }
        $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['updated_date'] = date('Y-m-d H:i:s');
        
        if ($this->update(TAB_PREFIX . "hrjobs_offer_letter_email_template", $dataArr,array('template_id'=>$this->sanitize($_REQUEST['id'])))) {
            return true;
        }
        $this->setError("Some Error try again");
        return false;
    }
    
    public function submitHREmailTemplate()
    {
        $dataArr = $this->getHREmailTemplateData();
        if (empty($dataArr)) {
            $this->setError("Fill All mandatory fields");
            return false;
        }
        $dataArr['added_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['added_date'] = date('Y-m-d H:i:s');
        
        if ($this->insert(TAB_PREFIX . "hrjobs_email_template", $dataArr)) {
            return true;
        }
        $this->setError("Some Error try again");
        return false;
    }
    protected function getHREmailTemplateData()
    {
        $dataArr= array();
        if(isset($_POST['submit']) && ($_POST['submit'] == 'Submit' || $_POST['submit'] == 'Update'))
        {
            $dataArr['template_name']= isset($_POST['template_name'])?$this->sanitize($_POST['template_name']):'';
            $dataArr['status']= isset($_POST['status'])?$this->sanitize($_POST['status']):'';
            $dataArr['template_content']= isset($_POST['template_content'])?$this->sanitize($_POST['template_content']):'';
        }
        return $dataArr;
    }
    public function updateHREmailTemplate()
    {
        $dataArr = $this->getHREmailTemplateData();
        if (empty($dataArr)) {
            $this->setError("Fill All mandatory fields");
            return false;
        }
        $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['updated_date'] = date('Y-m-d H:i:s');
        
        if ($this->update(TAB_PREFIX . "hrjobs_email_template", $dataArr,array('template_id'=>$this->sanitize($_REQUEST['id'])))) {
            return true;
        }
        $this->setError("Some Error try again");
        return false;
    }
    
    public function offerLetterPdfHtml($hrjob_apply_id)
    {
        if($hrjob_apply_id!='')
        {
            $dataRes = $this->get_results("select b.remuneration,a.interview_scheduled_date,a.interview_time,c.form_permanent_address,c.form_permanent_state,c.form_permanent_city,c.form_permanent_pin,b.title as job_postname,d.template_content as template,a.interview_date,a.interview_time,a.interview_venue,concat(c.form_first_name,' ',c.form_middle_name,' ',c.form_last_name) as candidatename from ".TAB_PREFIX."hrjobs_apply a left join ".TAB_PREFIX."hrjobs b on a.job_id=b.id left join ".TAB_PREFIX."hrjobs_registration_final c  on a.job_id=c.post_applied and a.userid=c.user_id left join ".TAB_PREFIX."hrjobs_offer_letter_email_template d on a.offer_letter_template_id=d.template_id where a.id='".$hrjob_apply_id."'");
            
            
            //var_dump($dataRes);die;
            $job_postname = $dataRes[0]->job_postname;
            $interview_date = $dataRes[0]->interview_date;
            if($dataRes[0]->interview_scheduled_date=='0000-00-00 00:00:00')
            {
                $letter_date=date('d-m-Y');
            }
            else
            {
                $letter_date = date('d-m-Y',strtotime($dataRes[0]->interview_scheduled_date));
            }
            $interview_date_2 = date('d-m-Y', strtotime('-2 day', strtotime($dataRes[0]->interview_date)));
            $interview_time = date('h:i a',strtotime($dataRes[0]->interview_time));
            $candidatename = $dataRes[0]->candidatename;
            $interview_venue = $dataRes[0]->interview_venue;
            $candidateaddress = $dataRes[0]->form_permanent_address."<br>".$dataRes[0]->form_permanent_city.",".$dataRes[0]->form_permanent_state.",".$dataRes[0]->form_permanent_pin;
            $template = htmlspecialchars_decode(html_entity_decode(utf8_decode($dataRes[0]->template)));
            $template = str_replace("\\r\\n\\r\\n","", $template);
            $template = str_replace("\\r\\n","", $template);
            $template = str_replace("{letter_date}", $letter_date, $template);
            $template = str_replace("{student_name}", $candidatename, $template);
            $template = str_replace("{student_address}", $candidateaddress, $template);
            $template = str_replace("{interview_date}", $interview_date, $template);
            $template = str_replace("{interview_time}", $interview_time, $template);
            $template = str_replace("{interview_date_2}", $interview_date_2, $template);
            $template = str_replace("{interview_venue}", $interview_venue, $template);
            $template = str_replace("{post_applied}", $job_postname, $template);
            $template = str_replace("{remuneration}", $dataRes[0]->remuneration, $template);
            $a = array("&lt;","&gt;");
            $b = array("<",">");
            $template = str_replace($a, $b, $template);
            
            return $template;
            
        }
    }
}
