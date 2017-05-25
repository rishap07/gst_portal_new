<?php

/*
 * 
 *  Developed By        :   Mukesh Tiwari
 *  Description         :   A simple class for user login/signup/logout/forget password etc 
 *  Date Created        :   October 20, 2015
 *  Last Modified       :   October 20, 2015
 *  Last Modification   :   file creation started
 * 
 */

class user {

    var $db_user = DB_USER;
    var $db_pass = DB_PASS;
    var $db_host = DB_HOST;
    var $db_name = DB_NAME;
    var $userTable = 'cms_user';
    var $userGroupTable = 'cms_user_group';
    var $userRoleTable = 'cms_user_role';
    var $usernameCol = 'username';
    var $passCol = 'password';
    var $connection = '';

    /* FUNCTION TO CHECK WHETHER THE USER IS LOGGED IN OR NOT */

    public function isLoggedIn() {
        @session_start();
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 1) {
            return true;
        } else {
            return false;
        }
    }

    /* FUNCTION TO CONNECT TO DATABASE */

    public function connectToDB() {
        try {
            mysqli_report(MYSQLI_REPORT_STRICT);
            $this->connection = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
        } catch (Exception $e) {
            die('Unable to connect to database');
        }
    }

    /* FUNCTION TO DISCONNECT FROM DATABASE */

    public function disconnectFromDB() {
        if ($this->connection) {
            $this->connection->close();
        }
    }

    /* FUNCTION TO ENCRYPT PASSWORD */

    public function encryptPass($password) {
        if (!empty($password)) {
            return md5($password);
        }
    }

    /* FUNCTION TO SANITIZE USER DATA */

    public function clean($data) {
        $data = stripcslashes($data);
        $data = $this->connection->real_escape_string($data);
        return $data;
    }

    /* FUNCTION TO CHECK IF THE USER EXIST */

    public function userExist($username, $password) {
        $username = $this->clean($username);
        $password = $this->clean($password);
        if (empty($username) && empty($password)) {
            return false;
        }
        $query = 'SELECT * FROM ' . $this->userTable . ' WHERE ' . $this->usernameCol . ' = "' . $username . '" AND ' . $this->passCol . ' = "' . $this->encryptPass($password) . '" AND status = "1" and is_deleted="0";';
//        echo $query;
        $result = $this->connection->query($query);
        if ($this->connection->error) {
            return false;
        }
        if (!$result) {
            return false;
        } else {
            if ($result->num_rows == 1) {
                return $result;
            } else {
                return false;
            }
        }
    }

    /* FUNCTION TO FETCH USER DETAILS FROM USERS TABLE */

    public function fetchUserDetails($username, $password, $object = false) {
        if (!empty($username) && !empty($password)) {
            $userExist = $this->userExist($username, $password);
            if ($userExist) {
                return ($object) ? $userExist->fetch_object() : $userExist->fetch_assoc();
            } else {
                return false;
            }
        }
    }

    public function fetchUserModules($userDetails) {
        $query = 'SELECT c.role_code FROM ' . $this->userTable . ' a, ' . $this->userGroupTable . " b, " . $this->userRoleTable . " c where a.user_group=b.group_id and find_in_set(c.role_id,b.role_id) and a.user_id='" . $userDetails['user_id'] . "'";
        $result = $this->connection->query($query);
        if ($this->connection->error) {
            return false;
        }
        if (!$result) {
            return false;
        } else {
            if ($result->num_rows > 0) {
                $res = array();
                while ($data = $result->fetch_assoc()) {
                    $res['role_code'][] = $data['role_code'];
                }
                return $res;
            } else {
                return false;
            }
        }
    }

    /* FUNCTION TO SET SESSION VALUES AFTER LOGIN */

    public function setUserSession($userDetailsArr) {
        @session_start();

        /* INITIALIZE SESSION VARIABLES HERE OTHER THAN THE DATA PRESENT IN USER TABLE */
        $_SESSION['logged_in'] = 1;

        /* USER DETAILS SESSION VARIABLE STARTS HERE */
        $_SESSION['user_detail'] = array();
        if (count($userDetailsArr) > 0) {
            foreach ($userDetailsArr as $field => $value) {
                if ($field != $this->passCol) {
                    if($field=='type')
                    {
                        $_SESSION['user_detail']['user_type'] = $value;  
                    }else{
                        $_SESSION['user_detail'][$field] = $value;
                    }
                }
            }
        }
        /* USER DETAILS SESSION VARIABLE ENDS HERE */
    }

    public function setModuleSession($userModulesArr) {

        /* USER DETAILS SESSION VARIABLE STARTS HERE */
        $_SESSION['user_modules'] = array();
        if (count($userModulesArr) > 0) {
            for ($x = 0; $x < count($userModulesArr['role_code']); $x++) {
                $_SESSION['user_modules'][] = $userModulesArr['role_code'][$x];
            }
        }
        /* USER DETAILS SESSION VARIABLE ENDS HERE */
    }

    /* FUNCTION TO UNSET USER SESSION VARIABLES */

    public function unsetUserSession() {
        /* UNSET USER DEFINED SESSION VARIABLES HERE */
        unset($_SESSION['logged_in']);


        /* UNSETTING USER DETAILS SESSION VARIABLE HERE */
        unset($_SESSION['user_detail']);
    }

    /* FUNCTION TO LOGIN A USER */

    public function loginCMS($username, $password) {
        if (!empty($username) && !empty($password)) {
            $obj_common = new common();
            $username = $obj_common->sanitize($username);
            $userExist = $this->userExist($username, $password);
            if ($userExist) {
                $userDetails = $this->fetchUserDetails($username, $password);
                $userModules = $this->fetchUserModules($userDetails);
//                echo '<pre>';print_r($userDetails);
//                print_r($userModules);
//                die();
                $this->setUserSession($userDetails);
                $this->setModuleSession($userModules);
                if($userDetails['user_group']=='4')
                {
                     $obj_common->redirect(ADMIN_URL . '/hrjobs.php?action=evaluationScreen6');
                }
                else
                {
                    $obj_common->redirect(ADMIN_URL . '/home.php');
                }
                exit();
            } else {
                return false;
            }
        }
    }

    /* FUNCTION TO REGISTER A USER */
    /*
     * $signupFormData = array( fieldName1 => value1, fieldName2 => value2, );
     *
     */

    public function signup($signupFormData = array()) {
        if (is_array($signupFormData) && count($signupFormData) > 0) {
            $query = 'INSER INTO ' . $this->userTable . ' (';
            $fields = '';
            $values = '';
            foreach ($signupFormData as $filedName => $value) {
                $fields .= '`' . $filedName . '`, ';
                $values .= '"' . $this->clean($value) . '", ';
            }
            $fields = rtrim($fields, ', ');
            $values = rtrim($values, ', ');
            $query .= $fields;
            $query .= ') VALUES (';
            $query .= $values;
            $query .= ');';
            $this->connection->query($query);
            if ($this->connection->error) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    /* FUNCTION TO HANDLE LOGOUT OF USER */

    public function logout() {
        /* HERE YOU CAN PERFORM ANY SPECIFIC FUNCTION BEFORE LOGOUT OF USER 
         * 
         * 
         */
        $this->unsetUserSession();
        header('location : login.php');
    }

    /* FUNCTION TO HANDLE FORGET PASSWORD */

    public function forgetPassword($username = '', $email = '') {
        if (!empty($username)) {
            
        } else if (!empty($email)) {
            
        }
    }

    /* FUNCTION TO CHANGE PASSWORD OF A USER */

    public function changePassword($username, $oldPass, $newPass) {
        if (!empty($username) && !empty($oldPass)) {
            $username = $this->clean($username);
            $oldPass = $this->clean($oldPass);
            $newPass = $this->clean($newPass);
            $userExist = $this->userExist($username, $oldPass);
            if ($userExist) {
                if (!empty($newPass)) {
                    $query = 'UPDATE ' . $this->userTable . ' SET ' . $this->passCol . ' = "' . $this->encryptPass($newPass) . '" WHERE ' . $this->usernameCol . ' = "' . $username . '" AND ' . $this->passCol . ' = "' . $this->encryptPass($oldPass) . '";';
                    $this->connection->query($query);
                    if ($this->connection->error) {
                        return false;
                    } else {
                        return true;
                    }
                } else {
                    return false;
                }
            }
        }
    }

    /* FUNCTION TO GENERATE RANDOM PASSWORD */

    public function randomPassword($length = 8) {
        $pass = '';
        $lower = range('a', 'z');
        $upper = range('A', 'Z');
        $numbers = range(0, 9);
        $chars = array_merge($lower, $upper, $numbers);
        for ($i = 0; $i <= $length; $i++) {
            $pass .= $chars[mt_rand(0, count($chars) - 1)];
        }
        return $pass;
    }

}
