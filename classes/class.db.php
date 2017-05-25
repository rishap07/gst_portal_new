<?php
/*
 * 
 *  Developed By        :   Rishap Gandhi
 *  Description         :   A simple class for database functions to be used throughout the project 
 *  Date Created        :   May 18, 2017
 *  Last Modified       :   May 18, 2017
 *  Last Modified By    :   Rishap Gandhi
 *  Last Modification   :   file creation started
 * 
 */

class db {

    public $db_host = DB_HOST;
    public $db_user = DB_USER;
    public $db_pass = DB_PASS;
    public $db_name = DB_NAME;
    public $counter = 0;
    public $link = '';
    public $lastResult = '';
    public $debugMode = false;
    public $langTable = 'language';
    public $last_insert_id = 1;
    public $id = '';
    public $vali_obj;

    /* Constructor */

    public function __construct() {
        $this->vali_obj = new cms_validate();
        $this->last_insert_id = 1;
        $this->connect();
    }

    /* Destructor */

//    public function __destruct() {
//        $this->disconnect();
//    }


    /* Connection Function */
    public function connect() {
        try {
            mysqli_report(MYSQLI_REPORT_STRICT);
            $this->link = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
            $this->link->set_charset("utf8");
        } catch (Exception $e) {
            die('Unable to connect to database');
        }
    }

    /* FUNCTION TO SET DEBUG MODE ON */

    public function debugMode($bool) {
        if ($bool === true) {
            $this->debugMode = true;
        } else {
            $this->debugMode = false;
        }
    }

    /* FUNCTION TO CONNECT TO DATABASE WITH GIVEN PARAMETERS */

    public function createConnetion($db_host, $db_user, $db_pass, $db_name) {
        try {
            mysqli_report(MYSQLI_REPORT_STRICT);
            $this->link = new mysqli($db_host, $db_user, $db_pass, $db_name);
            //$this->link->set_charset("utf8");
        } catch (Exception $e) {
            die('Unable to connect to database');
        }
    }

    /* DB ERROS LOG CREATION */

    public function log_db_errors($error, $query) {
        $message = '<p>Error at ' . date('Y-m-d H:i:s') . ':</p>';
        $message .= '<p>Query: ' . htmlentities($query) . '<br />';
        $message .= 'Error: ' . $error;
        $message .= '</p>';
        if ($this->debugMode) {
            echo $message;
        }
    }

    /* Sanitize user data */

    public function sanitize($data) {
        if (!is_array($data)) {
            //$data = utf8_encode(htmlentities(trim($data), ENT_COMPAT, 'utf-8'));
            $data = htmlentities(trim($data));
            $data = $this->link->real_escape_string($data);
        } else {
            //Self call function to sanitize array data
            $data = array_map(array('db', 'sanitize'), $data);
        }
        return $data;
    }

    function doit(&$item, $key) {
        //$item = $this->link->real_escape_string(trim($item));
        $item = trim($item);
    }

    /* Sanitize user data */

    public function trimandescape($data) {

        array_walk_recursive($data, array($this, 'doit'));
        return $data;
    }

    /* function to filter when only mysqli_real_escape_string is needed */

    public function escape($data) {
        if (!is_array($data)) {
            $data = $this->link->real_escape_string(trim($data));
        } else {
            //Self call function to sanitize array data
            $data = array_map(array($this, 'escape'), $data);
        }
        return $data;
    }

    /* Normalize sanitized data for display */

    public function clean($data) {
        $data = stripslashes($data);
        $data = html_entity_decode($data, ENT_QUOTES, 'UTF-8');
        $data = nl2br($data);
        $data = urldecode($data);
        return $data;
    }

    /* Perform queries */

    public function query($query) {
        //$this->link->query("SET NAMES utf8");
        $full_query = $this->link->query($query);
        if ($this->link->error) {
            $this->log_db_errors($this->link->error, $query);
            return false;
        } else {
//            if (isset($this->link->insert_id) && $this->link->insert_id > 0) {
//                $this->last_insert_id = $this->link->insert_id;
//            }
            return true;
        }
    }

    /* Determine if database table exists */

    public function table_exists($name) {
        $query = "SELECT 1 FROM " . $name;
        $check = $this->link->query($query);
        if ($check !== false) {
            if ($check->numRows > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            if ($this->link->error) {
                $this->log_db_errors($this->link->error, $query);
            }
            return false;
        }
    }

    /* Count number of rows found matching a specific query */

    public function numRows($query) {
        $num_rows = $this->link->query($query);
        if ($this->link->error) {
            $this->log_db_errors($this->link->error, $query);
            return false;
        } else {
            return $num_rows->num_rows;
        }
    }

    /* Return specific row based on db query */

    public function get_row($query, $object = true) {
        $row = $this->link->query($query);
        if ($this->link->error) {
            $this->log_db_errors($this->link->error, $query);
            return false;
        } else {
            $r = ($object ) ? $row->fetch_object() : $row->fetch_row();
            return $r;
        }
    }

    /* Perform query to retrieve object of result */

    public function get_results($query, $object = true) {
        //Overwrite the $row var to null
//        echo $query;
//        die; 
        $row = null;
        $results = $this->link->query($query);

        if ($this->link->error) {
            $this->log_db_errors($this->link->error, $query);
            return false;
        } else {
            $row = array();
            while ($r = ( $object ) ? $results->fetch_object() : $results->fetch_assoc()) {
                $row[] = $r;
            }
            //print_r($row);
            return $row;
        }
    }

    /* INSERT DATA IN TABLE [SINGLE ROW] */

    public function insert($table, $variables = array()) {
        //Make sure the array isn't empty
        if (empty($variables)) {
            return false;
        }
        $sql = "INSERT INTO " . $table;
        $fields = array();
        $values = array();
        foreach ($variables as $field => $value) {
            $fields[] = $field;
            $values[] = "'" . $this->sanitize($value) . "'";
        }
        $fields = ' (' . implode(', ', $fields) . ')';
        $values = '(' . implode(', ', $values) . ')';

        $sql .= $fields . ' VALUES ' . $values;
//		echo $sql; die;
		$query = $this->link->query($sql);
        if ($this->link->error) {
            $this->log_db_errors($this->link->error, $query);
            return false;
        } else {
            $this->id = $this->link->insert_id;
            $x = $this->usersLog($table, "insert", json_encode($variables));
            return $this->id;
//            return $this->link->insert_id;
        }
    }

    /* FUNCTION TO INSERT MULTIPLE DATA IN A TABLE 
     * $dataArr = array(
     *  '0' => array(
     *                  'col1'=>val1,
     *                  'col2' => val2
     *          ),
     *  '1' => array(
     *                  'col1'=>val1,
     *                  'col2' => val2
     *          )
     *  );
     */

    public function insertMultiple($tableName, $dataArr = array()) {
        if ($tableName != '') {
            if (is_array($dataArr) && count($dataArr) > 0) {
                $query = '';
                $colNames = array_keys($dataArr[0]);
                $query .= 'INSERT INTO ' . $tableName . ' (`';
                $query .= implode("`,`", $colNames) . "`) VALUES ";
                foreach ($dataArr as $rowData) {
                    $query .= '(';
                    foreach ($rowData as $colData) {
                        $query .= "'" . $this->sanitize($colData) . "',";
                    }
                    $query = rtrim($query, ',');
                    $query .= '), ';
                }
                $query = rtrim($query, ', ');
                //echo $query; die();
                $this->query($query);
                if ($this->link->error) {
                    $this->log_db_errors($this->link->error, $query);
                    return false;
                } else {
                    $this->id = $this->link->insert_id;
                    $x = $this->usersLog($tableName, "insert", json_encode($dataArr));
                    return $this->id;
                }
            }
        }
    }

    /* FUNCTION TO GET LANGUAGES DEFINED */

    public function getLanguages() {
        $query = "SELECT * FROM " .TAB_PREFIX. $this->langTable . " WHERE status = '0' and is_deleted='0'";
        $results = $this->get_results($query);
        if ($results) {
            return $results;
        } else {
            return false;
        }
    }

    /* INSERT MULTIPLE DATA IN A TABLE AS PER LANGUAGES DEFINED */

    public function formLangArray($postArr = array()) {
        $langs = $this->getLanguages();
        $post_keys = array_keys($postArr);
        $tmpArr = array();
        $arrKey = array();
        foreach ($langs as $lang) {
            $arrKey[] = $lang->abrv;
            $tmpArr[$lang->abrv] = array();
            $tmpArr[$lang->abrv]['language_id'] = $lang->language_id;
        }
        foreach ($postArr as $postKey => $postVal) {
            //if($postVal != ''){
            if (!in_array($postKey, $arrKey)) {
                foreach ($tmpArr as $indKey => $langArr) {
                    $tmpArr[$indKey][$postKey] = nl2br($postVal);
                }
            }
            //}
        }
        foreach ($tmpArr as $indKey => $langArr) {
            if (isset($postArr[$indKey]) && is_array($postArr[$indKey])) {
                $tmpArr[$indKey] = array_merge($tmpArr[$indKey], $postArr[$indKey]);
            }
        }
        $output = array();
        foreach ($tmpArr as $tmpKey => $tmpVal) {
            $output[] = $tmpVal;
        }
        return $output;
    }

    /* Function to Update Data in a Table */

    public function update($table, $dataArr = array(), $where = array(), $in = '') {
//        $this->pr($dataArr);
        if (empty($dataArr)) {
            return false;
        }
        $sql = "UPDATE " . $table . " SET ";
        foreach ($dataArr as $field => $value) {
            $updates[] = "`$field` = '" . $this->sanitize($value) . "'";
        }
        $sql .= implode(', ', $updates);
        if (!empty($where)) {
            if (is_array($where)) {
                if ($in == '') {
                    foreach ($where as $field => $value) {
                        $value = $value;
                        $clause[] = "$field = '" . $this->sanitize($value) . "'";
                    }
                } else if ($in == '1') {
                    foreach ($where as $field => $value) {
                        $value = $value;
                        $clause[] = "$field " . $this->sanitize($value) . "";
                    }
                }
                $sql .= ' WHERE ' . implode(' AND ', $clause);
            } else {
                $sql .= ' WHERE ' . $where;
            }
        }
//	echo $sql; 	
//        die();
        $query = $this->query($sql);

        if ($this->link->error) {
            $this->log_db_errors($this->link->error, $query);
            return false;
        } else {
            $this->usersLog($table, "update", json_encode(array("data" => $dataArr, "where" => $where)));
            return true;
        }
    }

    /*
     * FUNCTION TO UPDATE MULTIPLE RECORDS IN A TABLE
     * 
     * $dataArr Format
     * $dataArr = array(
     *                  '0' => array(
     *                          'set' => array( 'id' => 5, 'name' => 'Mukesh'),
     *                          'where' => array( 'ID' => '5')
     *                         ),
     *                  '1' => array(
     *                          'set' => array( 'id' => 6, 'name' => 'Nikhil'),
     *                          'where' => ' id = 5'
     *                          )
     *          );
     * 
     */

    public function updateMultiple($tableName, $dataArr = array()) {
        if (empty($dataArr)) {
            return false;
        }
        $flag = true;
        $this->link->autocommit(false);
        foreach ($dataArr as $setdata) {
            $query = '';
            $query .= 'UPDATE ' . $tableName . ' SET ';
            if (is_array($setdata['set'])) {
                //echo __line__;
                foreach ($setdata['set'] as $setCol => $setVal) {
                    $query .= $setCol . ' = "' . $this->sanitize($setVal) . '", ';
                }
                $query = rtrim($query, ', ');
            } else {
                $query .= $setdata['set'];
            }

            $condition = '';
            if (!empty($setdata['where'])) {
                $condition .= is_array($setdata['where']) ? $this->createConditionFromArray($setdata['where']) : ' WHERE ' . $setdata['where'];
            }
            $query .= $condition;
//            echo $query;
//            die;
            $result = $this->query($query);
            if (!$result) {
                $flag = false;
                break;
            }
        }
        if ($flag) {
            $this->link->commit();
            $this->connect();
            $this->usersLog($tableName, "update multiple", json_encode($dataArr));
            return true;
        } else {
            $this->link->rollback();
            $this->connect();
            return false;
        }
        $this->link->autocommit(true);
        $this->connect();
    }

    /* UPDATE MULTIPLE TABLE WITH ONE FUNCTION */

    public function multiTableUpdate($tableArr, $colArr, $valArr, $conditionArr = array()) {
        if (is_array($tableArr) && count($tableArr) > 0) {
            $flag = true;
            $this->link->autocommit(false);
            $query = '';
            foreach ($tableArr as $index => $table) {
                $query = 'UPDATE ' . $table;
                $setData = 'SET ';
                if (is_array($colArr[$index]) && is_array($valArr[$index]) && count($colArr[$index]) == count($valArr[$index])) {
                    foreach ($colArr[$index] as $colIdx => $colName) {
                        $setData .= $colName . ' = "' . $this->sanitize($valArr[$index][$colIdx]) . '", ';
                    }
                    $setData = trim($setData, ', ');
                    $condition = ' WHERE ';
                    if (isset($conditionArr[$index]) && is_array($conditionArr[$index])) {
                        foreach ($conditionArr[$index] as $col => $val) {
                            $condition .= $col . ' = "' . $this->link->real_escape_string($val) . '" AND ';
                        }
                        $condition = trim($condition, 'AND ');
                    }
                }
                $query = trim($query, ', ');
                $query .= ' ' . $setData . ' ' . $condition . ';';
                $result = $this->query($query);
                if (!$result) {
                    $flag = false;
                    break;
                }
            }
            if ($flag) {
                $this->link->commit();
                return true;
            } else {
                $this->link->rollback();
                return false;
            }
            $this->link->autocommit(true);
        }
    }

    /* Create Condition for WHERE clause */

    public function createConditionFromArray($whereArr = array()) {
        $where = ' WHERE ';
        if (is_array($whereArr)) {
            foreach ($whereArr as $fname => $fvalue) {
                $where .= $this->escape($fname) . '= "' . $this->escape($fvalue) . '" AND ';
            }
            return $where = trim($where, ' AND ');
        } else {
            return $where .= '1';
        }
    }

    /* Create Query from Given Parameters */

    public function createSelectQuery($params = '', $table, $condition = '', $group_by = '', $order_by = '', $limit = '', $offset = '') {
        $params = $params != '' ? $params : '*';
        $whereCondition = '';
        if (!empty($condition)) {
            $whereCondition = is_array($condition) ? $this->createConditionFromArray($condition) : ' WHERE ' . $condition;
        }
        $group_by = ($group_by != '') ? 'GROUP BY ' . $group_by : '';
        $order_by = $order_by != '' ? ' ORDER BY ' . $order_by : '';
        $limit = $limit != '' ? ' LIMIT ' . $limit : '';
        if ($limit != '' && $offset != '') {
            $limit .= ', ' . $offset;
        }
        $query = '';
        $query .= 'SELECT ' . $params . ' FROM ' . $table . ' ' . $whereCondition . ' ' . $group_by . ' ' . $order_by . ' ' . $limit;
        return $query;
    }

    /* Function to Select Data */

    public function findAll($table, $condition = '', $params = '', $group_by = '', $order_by = '', $limit = '', $offset = '') {
        $query = $this->createSelectQuery($params, $table, $condition, $group_by, $order_by, $limit, $offset);
//        echo $query;
        $results = $this->get_results($query);

        if ($this->link->error) {
            $this->log_db_errors($this->link->error, $query);
            return false;
        } else {
            return $results;
        }
    }

    /* SELECT QUERY WITH LEFT JOIN */
    /*
     * 
     * USES EXAMPLE
     * $obj_db->selectOnLeftJoin(
     *                   'a.ID, b.meta_key, b.meta_value, c.user_login',
     *                   'wp_posts a',
     *                   array(  'wp_postmeta b'=>'a.ID = b.post_id',
     *                           'wp_users c'=>'a.post_author = c.ID'
     *                   ),
     *                  'a.ID = 5', 
     *                  'b.meta_id DESC',
     *                  '2'
     *               ) 
     */

    public function selectOnLeftJoin($paramsToSelect, $table_main, $tableAndJoiningColumnArray, $whereCondition = '', $order_by = '', $limit = '', $group_by = '') {

        $order_by = $order_by != '' ? ' ORDER BY ' . $order_by : '';
        $group_by = $group_by != '' ? ' GROUP BY ' . $group_by : '';
        $limit = $limit != '' ? ' LIMIT ' . $limit : '';
        $whereCondition = $whereCondition != '' ? is_array($whereCondition) ? $this->createConditionFromArray($whereCondition) : ' WHERE ' . $whereCondition : '';
        $joinPart = '';
        if (is_array($tableAndJoiningColumnArray)) {
            foreach ($tableAndJoiningColumnArray as $table => $columnToJoin) {
                $joinPart .= ' LEFT JOIN ' . $table . ' ON ';
                if (is_array($columnToJoin)) {
                    foreach ($columnToJoin as $col1 => $col2) {
                        $joinPart .= $col1 . ' = "' . $col2 . '" AND';
                    }
                    $joinPart = rtrim($joinPart, ' AND');
                } else {
                    $joinPart .= $columnToJoin;
                }
            }
            $joinPart = rtrim($joinPart, ',');
            $query = "SELECT " . $paramsToSelect . " FROM " . $table_main . " " . $joinPart . " " . $whereCondition . " " . $group_by . " " . $order_by . " " . $limit;
           //echo $query; die();
            $results = $this->get_results($query);
            if ($this->link->error) {
                $this->log_db_errors($this->link->error, $query);
                return false;
            } else {
                return $results;
            }
        } else {
            return false;
        }
    }

    /* SELECT QUERY WITH INNER JOIN
     *
     * FOR USES SEE LEFT JOIN FUNCTION
     * 
     */

    public function selectOnInnerJoin($paramsToSelect, $table_main, $tableAndJoiningColumnArray, $whereCondition = '', $order_by = '', $limit = '') {

        $order_by = $order_by != '' ? ' ORDER BY ' . $order_by : '';
        $limit = $limit != '' ? ' LIMIT ' . $limit : '';
        $whereCondition = $whereCondition != '' ? is_array($whereCondition) ? $this->createConditionFromArray($whereCondition) : ' WHERE ' . $whereCondition : '';
        $joinPart = '';
        if (is_array($tableAndJoiningColumnArray)) {
            foreach ($tableAndJoiningColumnArray as $table => $columnToJoin) {
                $joinPart .= ' INNER JOIN ' . $table . ' ON ';
                if (is_array($columnToJoin)) {
                    foreach ($columnToJoin as $col1 => $col2) {
                        $joinPart .= $col1 . ' = "' . $col2 . '" AND';
                    }
                    $joinPart = rtrim($joinPart, ' AND');
                } else {
                    $joinPart .= $columnToJoin;
                }
            }
            $joinPart = rtrim($joinPart, ',');
            $query = "SELECT " . $paramsToSelect . " FROM " . $table_main . " " . $joinPart . " " . $whereCondition . " " . $order_by . " " . $limit;
            $results = $this->get_results($query);
            if ($this->link->error) {
                $this->log_db_errors($this->link->error, $query);
                return false;
            } else {
                return $results;
            }
        } else {
            return false;
        }
    }

    /* FUNCTION TO GET LAST INSERT ID */

    public function getInsertID() {
        return $this->id;
        //return $this->last_insert_id;
    }

    /* Function to create selectbox from a given table */

    public function selectBox($tableName, $name, $id = '', $optionValue, $optionText, $defaultText, $whereCondition, $htmlParams, $selectedValue, $order_by_column) {
        $query = $this->createSelectQuery($optionValue . ',' . $optionText, $tableName, $whereCondition, '', $order_by_column);
        $data = $this->get_results($query);
        $html = '<select name="' . $name . '" id="' . $id . '" ' . $htmlParams . '>';
        $html .= '<option value="">' . $defaultText . '</option>';
        if ($data) {
            foreach ($data as $options) {
                $selected = $options->$optionValue == $selectedValue ? 'selected' : '';
                $html .= '<option value="' . $options->$optionValue . '" ' . $selected . '>' . $options->$optionText . '</option>';
            }
        }
        $html .= '</select>';
        return $html;
    }

    /* Function to get primary key of a table */

    public function getPrimaryKey($tableName) {
        if ($tableName != '') {
            $query = "SHOW KEYS FROM $tableName WHERE Key_name = 'PRIMARY'";
            $results = $this->get_row($query);
            if ($this->link->error) {
                $this->log_db_errors($this->link->error, $query);
                return false;
            } else {
                return $results->Column_name;
            }
        } else {
            return false;
        }
    }

    /* Function to get Data from a table from its orimary Key */

    public function findByPk($tableName, $primary_key, $paramsToSelect = '*') {
        if ($tableName != '') {
            $condition = $this->getPrimaryKey($tableName) . " = '" . $primary_key . "'";
            $query = $this->createSelectQuery($paramsToSelect, $tableName, $condition);
            $results = $this->get_row($query);
            if ($this->link->error) {
                $this->log_db_errors($this->link->error, $query);
                return false;
            } else {
                return $results;
            }
        } else {
            return false;
        }
    }

    /* Function to get column names of a table */

    public function getColumnNames($tableName) {
        $query = "SHOW COLUMNS FROM " . $tableName . ";";
        $results = $this->get_results($query);
        if ($this->link->error) {
            $this->log_db_errors($this->link->error, $query);
            return false;
        } else {
            return $results;
        }
    }

    /* FUNCTION TO CREATE DELETE ALL QUERY */

    public function createDeleteAllQuery($tableName, $whereCondition= array(), $limit = '') {
        $query = '';
        if (!empty($tableName)) {
            $query .= 'DELETE FROM ' . $tableName;
        }
        
        if (!empty($whereCondition)) {
            $codition = '';
            if (is_array($whereCondition)) {
                $codition .= $this->createConditionFromArray($whereCondition);
            } else {
                $codition .= $whereCondition;
            }
            $query .= ' ' . $codition;
        }
        return $query;
    }

    /* FUNCTION TO DELETE DATA FROM A TABLE */

    public function deletData($tableName, $whereCondition, $limit = '') {
        if (!empty($tableName)) {
            $query = $this->createDeleteAllQuery($tableName, $whereCondition , $limit);            
            $this->query($query);
            if ($this->link->error) {
                $this->log_db_errors($this->link->error, $query);
                return false;
            } else {
                $this->usersLog($tableName, "delete", $whereCondition);
                return true;
            }
        }
    }

    /* FUNCTION TO CALCULATE MAX OF ANY COLUMN ON ANY TABLE */

    public function max($tableName, $columnname, $whereCondition = '') {
        if (!empty($tableName) && !empty($columnname)) {
            $query = 'SELECT MAX(' . $columnname . ') as maximum FROM ' . $tableName;
        }
        $condition = '';
        if ($whereCondition != '') {
            $condition = is_array($whereCondition) ? $this->createConditionFromArray($whereCondition) : ' WHERE ' . $whereCondition;
        }
        $query .= $condition;
        $result = $this->get_row($query);
        if ($this->link->error) {
            $this->log_db_errors($this->link->error, $query);
            return false;
        } else {
            return $result->maximum;
        }
    }

    /* FUNCTION TO CALCULATE MIN OF ANY COLUMN ON ANY TABLE */

    public function min($tableName, $columnname, $whereCondition = '') {
        if (!empty($tableName) && !empty($columnname)) {
            $query = 'SELECT MIN(' . $columnname . ') as minimum FROM ' . $tableName;
        }
        $condition = '';
        if ($whereCondition != '') {
            $condition = is_array($whereCondition) ? $this->createConditionFromArray($whereCondition) : ' WHERE ' . $whereCondition;
        }
        $query .= $condition;
        $result = $this->get_row($query);
        if ($this->link->error) {
            $this->log_db_errors($this->link->error, $query);
            return false;
        } else {
            return $result->minimum;
        }
    }

    /* FUNCTION TO COUNT ANY COLUMN OF ANY TABLE */

    public function count($tableName, $columnname, $whereCondition = '') {
        $tableName = trim($tableName);
        $columnname = trim($columnname);
        if (!empty($tableName) && !empty($columnname)) {
            $query = 'SELECT COUNT(' . $columnname . ') as counted FROM ' . $tableName;
        }
        $condition = '';
        if ($whereCondition != '') {
            $condition = is_array($whereCondition) ? $this->createConditionFromArray($whereCondition) : ' WHERE ' . $whereCondition;
        }
        $query .= $condition;
        $result = $this->get_row($query);
        if ($this->link->error) {
            $this->log_db_errors($this->link->error, $query);
            return false;
        } else {
            return $result->counted;
        }
    }

    /* FUNTION TO GET AUTO-INCREMENT ID FROM ANY TABLE */

    public function getNextAutoIncrement($tableName) {
        $tableName = trim($tableName);
        if (!empty($tableName)) {
            $query = "SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_NAME = '" . $tableName . "'";
            $results = $this->get_row($query);
            if ($results) {
                return $results->AUTO_INCREMENT;
            } else {
                if ($this->link->error) {
                    $this->log_db_errors($this->link->error, $query);
                }
                return false;
            }
        }
    }

    /* FUNCTION TO DELETE DATA FROM A TABLE USING THE PRIMARY KEY */

    public function deleteByPk($tableName, $primary_key) {
        if (trim($tableName) != '' && trim($primary_key) != '') {
            $condition = $this->getPrimaryKey($tableName);
            $condition .= " = " . $primary_key;
            $query = $this->createDeleteAllQuery($tableName, $whereCondition, '');
            $this->query($query);
            if ($this->link->error) {
                $this->log_db_errors($this->link->error, $query);
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    /* Revised verson FUNCTION TO DELETE DATA FROM A TABLE USING THE PRIMARY KEY */

    public function deleteByPrimaryk($tableName, $primary_key) {
        if (trim($tableName) != '' && trim($primary_key) != '') {
            $condition = ' where ' . $this->getPrimaryKey($tableName);
            $condition .= " = " . $primary_key;

            $query = $this->createDeleteAllQuery($tableName, $condition, '');
            $this->query($query);
            if ($this->link->error) {
                $this->log_db_errors($this->link->error, $query);
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    /* FUNCTION TO DROP A TABLE */

    public function dropTable($tableName) {
        $tablename = trim($tablename);
        if (empty($tablename)) {
            return false;
        }
        $query = "DROP TABLE IF EXISTS " . $tableName;
        $this->link->query($query);
        if ($this->link->error) {
            $this->log_db_errors($this->link->error, $query);
            return false;
        } else {
            return true;
        }
    }

    /* FUNCTION TO TRUNCATE A TABLE */

    public function truncateTable($tableName) {
        $tableName = trim($tableName);
        if (empty($tableName)) {
            return false;
        }
        $query = 'TRUNCATE TABLE ' . $tableName;
        $this->link->query($query);
        if ($this->link->error) {
            $this->log_db_errors($this->link->error, $query);
            return false;
        } else {
            return true;
        }
    }

    /* FUNCTION TO CHECK WHETHER THE DATA IS UNIQUE OR NOT */

    public function isUnique($tableName, $dataArr) {
        $tableName = trim($tableName);
        if (empty($tableName)) {
            return false;
        }
        $condition = '';
        if (!empty($dataArr)) {
            $condition = is_array($dataArr) ? $this->createConditionFromArray($dataArr) : ' WHERE ' . $dataArr;
        }
        $query = 'SELECT * FROM ' . $tableName . ' ' . $condition;
        if ($this->numRows($query) > 0) {
            return false;
        } else {
            return true;
        }
    }

    /* DISCONNECT CURRENT CONNECTION */

    public function disconnect() {
        if ($this->link) {
            $this->link->kill($this->link->thread_id);
            $this->link->close();
            unset($this->link);
        }
    }

    /* Collect Data for Multiple Update 
     * Need to pass four parameter
     * tableName on which you want to update. Type string
     * dataArr send you post or get data
     * field is an array type used to set condition
     * language in array for making language array
     *  */

    public function collectData($tableName, $dataArr, $feild, $languages) {
        if (empty($tableName) || empty($languages) || empty($feild) || empty($languages)) {
            return false;
        }
        for ($x = 0; $x < count($languages); $x++) {
            $temp = $dataArr[$x];
            foreach ($temp as $arry) {
                if (key($temp) != 'language_id') {
                    $flag = 0;
                    for ($y = 0; $y < count($feild); $y++) {
                        if (key($temp) == $feild[$y]) {
                            $flag = 1;
                        }
                    }
                    if ($flag == 0) {
                        $dataArry[$x]['set'][key($temp)] = $arry;
                    }
                }
                next($temp);
            }

            $dataArry[$x]['where']['language_id'] = $dataArr[$x]['language_id'];

            for ($x1 = 0; $x1 < count($feild); $x1++) {
                $dataArry[$x]['where'][$feild[$x1]] = $dataArr[$x][$feild[$x1]];
            }
        }
//        $this->pr($dataArry);
        $return = $this->updateMultiple($tableName, $dataArry);
        if ($return) {
            return true;
        } else {
            return false;
        }
    }

    public function getLabels($moduleName, $languageId) {
        $query = "select * from cms_labels where module='" . $moduleName . "' and language_id='" . $languageId . "'";
        $keyword = $this->get_results($query);
        if (is_array($keyword)) {
            foreach ($keyword as $key) {
                $arr[$key->keyword] = $key->content;
            }
        }
        if ($this->link->error) {
            $this->log_db_errors($this->link->error, $query);
            return false;
        }

        return $arr;
    }

    /* This function is use for get the single data from specific module in cms */

    public function getSinglefdata($moduleName, $moduleCatId = '') {
        if (trim($moduleName) == '') {
            return false;
        }
        if (trim($moduleName) == '14') {
            return false;
        }
        $res = $this->findAll("cms_modules", "id='" . $moduleName . "'");
        if (!empty($res)) {
            if (trim($moduleCatId) == '') {
                $query = "select * from " . $res[0]->table_name . " where published_date<='" . date('Y-m-d H:i:s') . "' and unpublished_date>='" . date('Y-m-d H:i:s') . "' and status= '1' and is_deleted= '0' and language_id='" . $_SESSION['language'] . "' order by " . $res[0]->field_name . " desc ";
            } else {
                $query = "select * from " . $res[0]->table_name . " where published_date<='" . date('Y-m-d H:i:s') . "' and unpublished_date>='" . date('Y-m-d H:i:s') . "' and status= '1' and is_deleted= '0' and language_id='" . $_SESSION['language'] . "'";

                $query.=" and " . $res[0]->field_name . "='" . $moduleCatId . "'";
                $query .="order by " . $res[0]->field_name . " desc";
            }
        } else {
            return false;
        }
        return $this->get_results($query);
    }

    /* This function is use for fetch data from multiple tables with union  Pending */

    public function findAllUnion($tableName = array(), $params = array(), $condition = array(), $group_by = '', $order_by = '', $limit = '', $offset = '') {


        if (is_array($tableName) || !empty($tableName)) {

            $parmeters = array();
            //$whereCondition = $whereCondition != '' ? is_array($whereCondition) ? $this->createConditionFromArray($whereCondition) : ' WHERE '.$whereCondition : '';
//            $whereConditions = array();
            $query = '';
            for ($x = 0; $x < count($tableName); $x++) {
//                $parmeters[]= implode(',', $params[$tableName[$x]]);
                $query .= 'SELECT ' . implode(',', $params[$tableName[$x]]) . ' FROM ' . $tableName[$x] . ' ';
                if (isset($condition[$tableName[$x]]) && is_array($condition[$tableName[$x]]) && !empty($condition[$tableName[$x]])) {
                    $query .=' WHERE ' . implode(' and ', $condition[$tableName[$x]]);
                }
                $query .= ' UNION ' . $group_by . ' ' . $order_by . ' ' . $limit;
            }
            $query = rtrim(trim($query), 'UNION');
//            $this->pr($query);
        }
        if ($this->link->error) {
            $this->log_db_errors($this->link->error, $query);
            return false;
        } else {
            return $this->get_results($query);
        }
        return $this->get_results($query);
    }

    public function usersLog($action_table, $action_type, $description) {
		
        $qry = "insert into ".TAB_PREFIX."data_log set `action_by` = '". (isset($_SESSION['user_detail']['user_id']) ? $_SESSION['user_detail']['user_id'] : '0') ."', `action_table` = '" . $action_table . "', `action_type` = '" . $action_type . "', `description` = '" . $this->escape($description) . "', `date_time` = now()";
        $this->query($qry);
    }

}
