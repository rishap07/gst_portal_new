<?php

class gstr_api extends validation{


public function hitUrl($url,$data_string,$header) {
        
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
		$result = curl_exec($ch);
		curl_close($ch);
        //echo $result;  
		return $result;		

    }


}