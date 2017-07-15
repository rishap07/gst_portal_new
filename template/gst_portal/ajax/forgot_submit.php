<?php
echo "<script> alert('in'); </script> ";
$obj_client = new client();
public function forgotPassword()
	{
		$email = isset($_POST['email']) ? $_POST['email'] : '';
		$sql="select * from ".TAB_PREFIX."user where email='".$email."'";
		
		$data = $this->get_results($sql);
	    if(count($data) > 0)
		{
		 $userid = $data[0]->user_id;
		
		
			if($this->sendMail('Email Verify','User ID : '.$userid.' email forgotPassword',$data[0]->email,'noreply@gstkeeper.com','','rishap07@gmail.com,sheetalprasad95@gmail.com','','GST Keeper Portal Forgot Email Verify',$this->getEmailVerifyMailBody($userid)))
			{
				$this->setError('Kindly check your email for verification.');
				return true;
			}
			else
			{
				$this->setError('Try again some issue in sending in email.');
				return false;
			}
		}
		else
		{
			 // $this->setError($this->validationMessage['failed']);
            //return false;
			$this->setError('Your email does not exists');
			return false;
		}
		
	}
	protected function sendMail($module='',$module_message='',$to_send,$from_send,$cc='',$bcc='',$attachment='',$subject,$body)
	{
		$dataInsertArray['module'] = $module;
		$dataInsertArray['module_message'] = $module_message;
		$dataInsertArray['to_send'] = $to_send;
		$dataInsertArray['from_send'] = $from_send;
		$dataInsertArray['cc'] = $cc;
        $dataInsertArray['bcc'] = $bcc;
		$dataInsertArray['attachment'] = $attachment;
        $dataInsertArray['subject'] = $subject;
		$dataInsertArray['body'] = $body;
		
		 
        if ($this->insert($this->tableNames['email'], $dataInsertArray)) {
		  return true;
		}
		else
		{
		  return false;
		}
	}
	private function getEmailVerifyMailBody($userid)
	{
		$token =  md5(uniqid(rand(),1));
		$data = '<a href="'.PROJECT_URL.'/?page=dashboard&forgotpassword=' . $token . '&passkey='.$userid.'">Click here</a>  or copy the below url and paste on browser to verify your email';
		$this->update(TAB_PREFIX."user",array('email_code'=>$token), array('user_id'=>$userid));
		$this->setSuccess('Kindly check your email for verification.');
		return $data;
	}
	?>