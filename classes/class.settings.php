<?php

/*
 * 
 *  Developed By        :   Rishap Gandhi
 *  Date Created        :   Sep 17, 2016
 *  Last Modified       :   Sep 17, 2016
 *  Last Modified By    :   Rishap Gandhi
 *  Last Modification   :   class for Menu 
 * 
 */

class settings extends validation {

    private $begin = "BEGIN";
    private $rollback = "ROLLBACK";
    private $commit = "COMMIT";
    public $tableName = array(
        'theme' => 'theme',
        'mods' => 'mods',
        'language' => 'language'
    );
    public $msg = array(
        'fillall' => 'Fill All mandatory fields',
        'insert_err' => 'Try again to submit',
        'insert_suc' => 'Added successfully',
        'update_suc' => 'Updated successfully',
        'update_fail' => 'Fail to update try again',
    );

    public function __construct() {
        parent::__construct();
    }
    
    
    //
    // Function of Template Starts from Here
    // Create by Rishap 
    //

    public function addTemplate() {
        $dataArr = $this->getTemplateData();
        if (empty($dataArr)) {
            $this->setError($this->msg['fillall']);
            return false;
        }
        $dataArr['created_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['created_date'] = date('Y-m-d H:i:s');
        if (!$this->insert(TAB_PREFIX . $this->tableName['theme'], $dataArr)) {
            $this->setError($this->msg['insert_err']);
            return false;
        }  else {
            $insertedId=$this->getInsertID();
            $this->logMsg("Template Added : Template ID : " . $insertedId, "Setting/Template Management", $_SESSION['user_detail']['user_id']);    
        }
        return true;
    }

    public function getTemplateData() {
        $dataArr = array();
        if (isset($_POST['submit']) && $_POST['submit'] == 'Submit') {
            $dataArr['theme_name'] = $_POST['theme_name'];
            $dataArr['theme_folder'] = $_POST['theme_folder'];
            $dataArr['status'] = $_POST['status'];
        }
        return $dataArr;
    }

    public function editTemplate() {
        $dataArr = $this->getTemplateData();
        if (empty($dataArr)) {
            $this->setError($this->msg['fillall']);
            return false;
        }
        $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['updated_date'] = date('Y-m-d H:i:s');
        if (!$this->update(TAB_PREFIX . $this->tableName['theme'], $dataArr, array("theme_id" => $this->sanitize($_GET['id'])))) {
            $this->setError($this->msg['insert_err']);
            return false;
        }  else {
          $this->logMsg("Existing Template Updated : Template ID : " . $_GET['id'], "Setting/Template Management", $_SESSION['user_detail']['user_id']);    
        }
        return true;
    }

    public function getTemplate($theme_id = '') {
        if ($theme_id != '') {
            return $this->findAll(TAB_PREFIX . $this->tableName['theme_id'], " status='0' and is_deleted='0' and theme_id='" . $this->sanitize($theme_id) . "'");
        }
        return $this->findAll(TAB_PREFIX . $this->tableName['theme_id'], " status='0' and is_deleted='0' ");
    }
    
    //
    // Function of Template Ends from Here
    // Create by Rishap 
    //
    
    
    
    //
    // Function of Modules Start from Here
    // Create by Rishap 
    //
    
    
    public function addMods() {
        $dataArr = $this->getModsData();
        if (empty($dataArr)) {
            $this->setError($this->msg['fillall']);
            return false;
        }
        $dataArr['created_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['created_date'] = date('Y-m-d H:i:s');
        if (!$this->insert(TAB_PREFIX . $this->tableName['mods'], $dataArr)) {
            $this->setError($this->msg['insert_err']);
            return false;
        }
        return true;
    }

    public function getModsData() {
        $dataArr = array();
        if (isset($_POST['submit']) && $_POST['submit'] == 'Submit') {
            $dataArr['theme_name'] = $_POST['theme_name'];
            $dataArr['theme_folder'] = $_POST['theme_folder'];
            $dataArr['status'] = $_POST['status'];
        }
        return $dataArr;
    }

    public function editMods() {
        $dataArr = $this->getModsData();
        if (empty($dataArr)) {
            $this->setError($this->msg['fillall']);
            return false;
        }
        $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['updated_date'] = date('Y-m-d H:i:s');
        if (!$this->update(TAB_PREFIX . $this->tableName['mods'], $dataArr, array("mod_id" => $this->sanitize($_GET['id'])))) {
            $this->setError($this->msg['insert_err']);
            return false;
        }
        return true;
    }

    public function getMods($mod_id = '') {
        if ($mod_id != '') {
            return $this->findAll(TAB_PREFIX . $this->tableName['mods'], " status='0' and is_deleted='0' and theme_id='" . $this->sanitize($mod_id) . "'");
        }
        return $this->findAll(TAB_PREFIX . $this->tableName['mods'], " status='0' and is_deleted='0' ");
    }
    
    //
    // Function of Modules Ends from Here
    // Create by Rishap 
    //
    
    
    
    //
    // Function of Languages Start from Here
    // Create by Rishap 
    //
    
    public function addLanguage() {
        $dataArr = $this->getLanguageData();
        if (empty($dataArr)) {
            $this->setError($this->msg['fillall']);
            return false;
        }
        if($dataArr['default_lang']=='0')
        {
            $this->update(TAB_PREFIX . $this->tableName['language'],array('default_lang'=>'1'),'');
        }
        $dataArr['created_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['created_date'] = date('Y-m-d H:i:s');
        if (!$this->insert(TAB_PREFIX . $this->tableName['language'], $dataArr)) {
            $this->setError($this->msg['insert_err']);
            return false;
        }
        return true;
    }

    public function getLanguageData() {
        $dataArr = array();
        if (isset($_POST['submit']) && $_POST['submit'] == 'Submit') {
            $dataArr['language_name'] = $_POST['language_name'];
            $dataArr['status'] = $_POST['status'];
            $dataArr['default_lang'] = isset($_POST['default_lang'])?$_POST['default_lang']:'1';
        }
        return $dataArr;
    }

    public function editLanguage() {
        $dataArr = $this->getLanguageData();
        if (empty($dataArr)) {
            $this->setError($this->msg['fillall']);
            return false;
        }
        if($dataArr['default_lang']=='0')
        {
            $this->update(TAB_PREFIX . $this->tableName['language'],array('default_lang'=>'1'),'');
        }
        $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['updated_date'] = date('Y-m-d H:i:s');
        if (!$this->update(TAB_PREFIX . $this->tableName['language'], $dataArr, array("language_id" => $this->sanitize($_GET['id'])))) {
            $this->setError($this->msg['insert_err']);
            return false;
        }
        return true;
    }

    public function getLanguage($language_id = '') {
        if ($language_id != '') {
            return $this->findAll(TAB_PREFIX . $this->tableName['language'], " status='0' and is_deleted='0' and theme_id='" . $this->sanitize($language_id) . "'");
        }
        return $this->findAll(TAB_PREFIX . $this->tableName['language'], " status='0' and is_deleted='0' ");
    }
    
    
    //
    // Function of Languages Ends from Here
    // Create by Rishap 
    //
}
