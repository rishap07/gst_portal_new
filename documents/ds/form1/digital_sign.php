<?php
include('digitalsignlib/File/X509.php');

if(isset($_POST['submit'])) {
    if ($_FILES['certificate']['tmp_name'] != '') {
        $etx = pathinfo($_FILES['certificate']['name'], PATHINFO_EXTENSION);
         if($etx=='cer' || $etx=='crt' || $etx == 'der' || $etx == 'pem' || $etx == 'pfx' ) {
            
			$path = $_FILES['certificate']['tmp_name'];
            $filesize = $_FILES['certificate']['size'];
            if($filesize  > 0) {
                $cert_content = file_get_contents($path);
                if(!empty($cert_content)){

                    $x509 = new File_X509();
					
					
					$cert_content = preg_split('#-+BEGIN CERTIFICATE-+#', $cert_content);
					array_shift($cert_content);
					
					print_r($cert_content);
					die;
					
					for ($i = 0; $i < count($cert_content) - 1; $i++) {
						$x509->loadCA($cert_content[$i]);
					}
					$x509->loadX509($cert_content[count($cert_content) - 1]);
					echo validateDigital() ? 'valid cert' : 'invalid cert';
					
					echo '<pre>';
					print_r($x509);
                    die;

                    $cert = $x509->loadX509($cert_content);
					echo '<pre>';
					print_r($cert);
					die;
					
                    if(!empty($cert)) {
                        if(!empty($x509->validateDate())) {
						   echo validateDigital($cert) ? 'valid' : 'invalid';
                        }
                        else {
                            echo 'File Expired.';
                        }
                    }
                    else {
                        echo 'Invalid File.';
                    }
                }   
                else{
                    echo 'Invalid File.';
                }
            }
            else {
                echo 'Empty File.';
            }
			
			
        }
        else
        {
            echo 'Invalid File Extension';
        }
    }
}


/**** Start Code to check digital signature valid or not ***/
function validateDigital($data) {
    $return = false;
    if(!empty($data)) {

        if(isset($data['tbsCertificate']) && !empty($data['tbsCertificate']) && isset($data['signature']) && !empty($data['signature'])) {
            /**** index key check ***/
            if(isset($data['tbsCertificate']['signature']['algorithm']) && !empty($data['tbsCertificate']['signature']['algorithm']) && isset($data['tbsCertificate']['issuer']) && !empty($data['tbsCertificate']['issuer']) && isset($data['tbsCertificate']['subjectPublicKeyInfo']) && !empty($data['tbsCertificate']['subjectPublicKeyInfo']) && isset($data['tbsCertificate']['validity']) && !empty($data['tbsCertificate']['validity'])) {

                /**** Public key check ***/
                if(isset($data['tbsCertificate']['subjectPublicKeyInfo']['subjectPublicKey']) && !empty($data['tbsCertificate']['subjectPublicKeyInfo']['subjectPublicKey'])){
                    $return = true;
                }
                
            }
            
        }
    }     
    return $return;     
}
/**** End Code to check digital signature valid or not ***/

?>
<form  method="POST" enctype="multipart/form-data">
<h2>Digital Certificate</h2>
  
<input type="file" name="certificate">
<input type='submit' class="btn btn-danger" name='submit' value='submit' id='submit'>
</form>