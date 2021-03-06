<?php
/*
 * 
 *  Developed By        :   Rishap Gandhi
 *  Description         :   Class of API to access the application for thrid party. 
 *  Date Created        :   May 19, 2017
 *  Last Modified       :   May 19, 2017
 *  Last Modified By    :   Rishap Gandhi
 *  Last Modification   :   file creation started
 * 
 */

final class api extends validation
{
    protected $validationMessage = array(
        'failed' => "Some error try again to submit.",
        'loginerror' => 'Username or Password Incorrect.',
        'passwordnotmatched' => 'Password not matched.',
        'usernameexist' => 'Username already exist.',
        'emailexist' => 'Email already exist.',
        'usernotexist' => 'User not exist.',
        'userexist' => 'User exist.',
        'apiDataBlank' => 'Enter all mandatory fields.',
        'invalidHashCode' => 'Invalid Hash Code generated.',
        'api' => 'Invalid API access.'
    );
    
    
    /*
    *  API Login Method
    *  Pass Value like (user_name,password,api_code,api_user,secure_hash)
    */
    final public function login()
    {
        //$this->pr($_SERVER);
        $server_name= $_SERVER['SERVER_NAME'];
        $server_addr= $_SERVER['SERVER_ADDR'];
        $remote_addr= $_SERVER['REMOTE_ADDR'];
        $request_method= $_SERVER['REQUEST_METHOD'];
        $dataArr = $this->getLoginParameters();

        if(empty($dataArr))
        {
            return array('msg'=>$this->validationMessage['apiDataBlank'],'code'=>'1');
        }
        if(!$this->validateLogin($dataArr))
        {
            return array('msg'=>$this->getError(),'code'=>'1');
        }
        if(!$this->checkHash($dataArr))
        {
            return array('msg'=>$this->validationMessage['invalidHashCode'],'code'=>'1');
        }

        $data1 = $this->findAll($this->tableNames["api"],array('remote_addr'=>$_SERVER['REMOTE_ADDR'],'api_code'=>$dataArr['api_code'],'api_user'=>$dataArr['api_user'],'status'=>'1','is_deleted'=>'0'));
		if(empty($data1))
        {
            return array('msg'=>$this->validationMessage['api'],'code'=>'1');
        }

        $data['user'] = $this->findAll($this->tableNames["user"],array('password'=>$this->password_encrypt($dataArr['password']),'username'=>$dataArr['username'],'status'=>'1','is_deleted'=>'0'),"user_id,concat(first_name,' ',last_name)as name,username,user_group,email,user_group,profile_pics");
		
        if(empty($data['user'])) {
            return array('msg'=>$this->validationMessage['loginerror'],'code'=>'1');
        }

        $query = "select b.role_page,a.can_read,a.can_create,a.can_update,a.can_delete from ".$this->tableNames['user_role_permission']." a left join ".$this->tableNames['user_role']." b on a.role_id=b.user_role_id where a.group_id='".$data['user']['0']->user_group."' and a.is_deleted='0' and a.status='1'";		
		$data['user_permission'] = $this->get_results($query);
      
        return array('data'=>$data,'msg'=>'success','code'=>'2');
    }
    
    /*
    *  GET API LOGIN PARAMETERS
    */
    private function getLoginParameters()
    {
        if(isset($_POST['api_code']))
        {
            $dataArr['username'] = isset($_POST['user_name'])? $_POST['user_name'] : '';
            $dataArr['password'] = isset($_POST['password']) ? $_POST['password'] : '';
            $dataArr['api_code'] = isset($_POST['api_code']) ? $_POST['api_code'] : '';
            $dataArr['api_user'] = isset($_POST['api_user']) ? $_POST['api_user'] : '';
            $dataArr['secure_hash'] = isset($_POST['secure_hash']) ? $_POST['secure_hash'] : '';
        }
        return $dataArr;
    }
    
    
    /*
    *  VALIDATE LOGIN PARAMETERS
    */
    private function validateLogin($dataArr)
    {
        $rules = array(
            'username' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:User Name',
            'password' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Password',
            'api_code' => 'required|#|lable_name:API CODE',
            'api_user' => 'required|#|lable_name:API USER',
            'secure_hash' => 'required|#|lable_name:Secure Hash',
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
    
    
    /*
    *  CHECK SECURE HASH CODE
    */
    private function checkHash($dataArr)
    {
        $secure_hash = $dataArr['secure_hash'];
        unset($dataArr['secure_hash']);
        $data = '';
        foreach($dataArr as $key=>$value)
        {
            $data.=$value."|";
        }
        $data= strtoupper(md5($data));
        if($secure_hash!==$data)
        {
            return false;
        }
        return true;
    }
	
	
	/*
	*  Get Item by HSN or Name
	*/
	public function getAPIItem()
	{
		$dataArr = array();
		if(isset($_REQUEST['item_id']) && $_REQUEST['item_id']!='')
		{
			$sql = "select item_name,hsn_code,item_type,igst_tax_rate,csgt_tax_rate,sgst_tax_rate,cess_tax_rate from ".TAB_PREFIX."master_item where item_name like '%".$this->sanitize($_REQUEST['item_id'])."%' or hsn_code like '%".$this->sanitize($_REQUEST['item_id'])."%'";
			$dataArr['msg'] = '200';
			$dataArr['status'] = '200';
			$dataArr['data'] = $this->get_results($sql);
		}
		else
		{
			$dataArr['msg'] = '401';
			$dataArr['status'] = 'Item ID not passed';
		}
		return $dataArr;
	}
}