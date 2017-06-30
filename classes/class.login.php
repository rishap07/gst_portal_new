<?php

/*
 * Created by Ishwar Lal Ghiya
 * Dated: 2017-05-18
 * Created Purpose : For Login Purpose
 */

class login extends validation {

    public function __construct() {
        parent::__construct();
    }

    public function loginUser() {
        
        $dataArr['user_name'] = isset($_POST['login_username']) ? $_POST['login_username'] : '';
        $dataArr['password'] = isset($_POST['login_password']) ? $_POST['login_password'] : '';

        if (!$this->validateLogin($dataArr)) {
			return false;
        }

        $dataArr['api_code'] = 'XYZ';
        $dataArr['api_user'] = 'ABC';
        $val = '';

		foreach ($dataArr as $key => $value) {
            $val .= $value . "|";
        }

        $dataArr['secure_hash'] = strtoupper(md5($val));
        $dataArr['api_method'] = 'login';
        $url = PROJECT_URL."/api.php";

        $server_output = $this->hitCurl($url, $dataArr);
        $server_output = json_decode($server_output);

        if (count($server_output) > 0) {

			if (isset($server_output->msg) && $server_output->msg == 'success' && $server_output->code == '2') {

				$_SESSION['user_detail']['user_id'] = $server_output->data->user[0]->user_id;
                $_SESSION['user_detail']['name'] = $server_output->data->user[0]->name;
                $_SESSION['user_detail']['username'] = $server_output->data->user[0]->username;
                $_SESSION['user_detail']['email'] = $server_output->data->user[0]->email;
                $_SESSION['user_detail']['user_group'] = $server_output->data->user[0]->user_group;

				for($x=0; $x < count($server_output->data->user_permission); $x++) {

					$_SESSION['user_role'][$server_output->data->user_permission[$x]->role_page]['can_read'] = $server_output->data->user_permission[$x]->can_read;
                    $_SESSION['user_role'][$server_output->data->user_permission[$x]->role_page]['can_create'] = $server_output->data->user_permission[$x]->can_create;
                    $_SESSION['user_role'][$server_output->data->user_permission[$x]->role_page]['can_update'] = $server_output->data->user_permission[$x]->can_update;
                    $_SESSION['user_role'][$server_output->data->user_permission[$x]->role_page]['can_delete'] = $server_output->data->user_permission[$x]->can_delete;
                }

                if (isset($_POST['login_rememberme']) && $_POST['login_rememberme'] == 1) {

                    if ($this->setRememberMeCookie($server_output->data->user[0]->user_id)) {
						return true;
                    } else {
                        $this->setError($this->validationMessage['cookie_err']);
                        return false;
                    }
                }
                return true;
            } else {
                $msg = explode('|',$server_output->msg);
                $this->setError($msg);
                return false;
            }
        } else {
            $this->setError($this->validationMessage['loginerror']);
            return false;
        }
    }

    public function validateLogin($dataArr) {

        $rules = array(
            'user_name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:User Name',
            'password' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Password',
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

    public function registerUser() {

        $dataArr['username'] = isset($_POST['username']) ? $_POST['username'] : '';
        $dataArr['emailaddress'] = isset($_POST['emailaddress']) ? $_POST['emailaddress'] : '';
        $dataArr['password'] = isset($_POST['password']) ? $_POST['password'] : '';
        $dataArr['confirmpassword'] = isset($_POST['confirmpassword']) ? $_POST['confirmpassword'] : '';

        if (!$this->validateRegister($dataArr)) {
            return false;
        }

        if ($dataArr['password'] != $_POST['confirmpassword']) {
            $this->setError($this->validationMessage['passwordnotmatched']);
            return false;
        }

        if ($this->checkUsernameExist($dataArr['username'])) {
            $this->setError($this->validationMessage['usernameexist']);
            return false;
        }
		
		if($this->checkEmailAddressExist($dataArr['emailaddress'])){
            $this->setError($this->validationMessage['emailexist']);
            return false;
        }

        /* create insert array */
        $dataInsertArray['username'] = $dataArr['username'];
        $dataInsertArray['email'] = $dataArr['emailaddress'];
        $dataInsertArray['subscriber_code'] = $this->generateSubscriberRandomCode(6, $this->tableNames['user'], "subscriber_code");
        
        $dataInsertArray['password'] = $this->password_encrypt($dataArr['password']); /* encrypt password */
        $dataInsertArray['added_by'] = '22';
        $dataInsertArray['added_date'] = date('Y-m-d H:i:s');

        if ($this->insert($this->tableNames['user'], $dataInsertArray)) {

            /* get user data by its id */
            $userData = $this->getUserDetailsById($this->getInsertID());
            $_SESSION['user_detail']['user_id'] = $userData['data']->user_id;
            $_SESSION['user_detail']['username'] = $userData['data']->username;
            $_SESSION['user_detail']['email'] = $userData['data']->email;
            $_SESSION['user_detail']['name'] = $userData['data']->name;
            $_SESSION['user_detail']['user_group'] = $userData['data']->user_group;

			/* assign user permissions */
			$rolequery = "select b.role_page,a.can_read,a.can_create,a.can_update,a.can_delete from ".$this->tableNames['user_role_permission']." a left join ".$this->tableNames['user_role']." b on a.role_id=b.user_role_id where a.group_id='" . $userData['data']->user_group . "' and a.is_deleted='0' and a.status='1'";
			$userPermission = $this->get_results($rolequery);

			for($x=0; $x < count($userPermission); $x++) {

				$_SESSION['user_role'][$userPermission[$x]->role_page]['can_read'] = $userPermission[$x]->can_read;
				$_SESSION['user_role'][$userPermission[$x]->role_page]['can_create'] = $userPermission[$x]->can_create;
				$_SESSION['user_role'][$userPermission[$x]->role_page]['can_update'] = $userPermission[$x]->can_update;
				$_SESSION['user_role'][$userPermission[$x]->role_page]['can_delete'] = $userPermission[$x]->can_delete;
			}

            if (isset($_POST['rememberme']) && $_POST['rememberme'] == 1) {

                if ($this->setRememberMeCookie($userData['data']->user_id)) {
                    return true;
                } else {
                    return false;
                }
            }

            return true;
        } else {
            $this->setError($this->validationMessage['failed']);
            return false;
        }
    }

    public function validateRegister($dataArr) {

        $rules = array(
            'username' => 'required||pattern:/^' . $this->validateType['username'] . '+$/|#|lable_name:User Name',            
            'emailaddress' => 'required||email|#|lable_name:Email',
            'password' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/||min:8||max:20|#|lable_name:Password'
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
}
?>