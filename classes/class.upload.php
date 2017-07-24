<?php
/*
 * 
 *  Developed By        :   Rishap Gandhi
 *  Description         :   A simple class for upload functions to be used throughout the project 
 *  Date Created        :   May 18, 2017
 *  Last Modified       :   May 18, 2017
 *  Last Modified By    :   Rishap Gandhi
 *  Last Modification   :   file creation started
 * 
 */

/*
 * PROCEDURE TO UPLOAD A FILE 
 * 
 * $obj_upload = new upload();
 * $obj_upload->setDestination($newDestination);    //In case you want to change the destination
 * $obj_upload->setAllowedExtensions($array_of_extensions_allowed_to_upload);
 * $obj_upload->setMaxSize();       //In case you want to set a file size limit
 * $obj_upload->validate($_FILES['filename']);         //For validation of the file to be uploaded
 * $success = $obj_upload->uploadFile($_FILES['filename']['tmp_name'], $newFileName);
 * if($success){
 *      File Uploaded successfully.
 * }else{
 *      Error in file uploading.
 * }
 * 
 */

class upload extends common {

    public $destination = '';
    public $fileName = '';
    public $maxSize = 0; // bytes (1048576 bytes = 1 meg)
    public $minSize = 0; // bytes (1048576 bytes = 1 meg)
    
    public $allowedExtensions = array();    // mime types
    public $error = '';
    public $widthStart = 0;
    public $widthEnd = 0;
    public $heightStart = 0;
    public $heightEnd = 0;

    public function __construct() {
        parent::__construct();
    }

    /*
     * START :: FUNCTIONS TO CHANGE DEFAULT VALUES
     */

    /* Function to Change File Upload Destination */

    public function setDestination($newDestination) {
        $this->destination = $newDestination;
    }

    /* Function to Change Filename of the file to be uploaded  */

    public function setFileName($newFileName) {
        $this->fileName = $newFileName;
    }

    /* Function to Change Filesize of the file to be uploaded  */

    public function setMaxSize($newSize) {
        $this->maxSize = $newSize;
    }
    public function setMinSize($newSize) {
        $this->minSize = $newSize;
    }

    /* Function to Change allowed filetype extensions */

    public function setAllowedExtensions($newExtensions) {
        if (is_array($newExtensions)) {
            $this->allowedExtensions = $newExtensions;
        } else {
            $this->allowedExtensions = array($newExtensions);
        }
    }

    /*
     * END :: FUNCTIONS TO CHANGE DEFAULT VALUES
     */



    /* FUNCTION TO SET ALLOWED EXTENSIONS FOR ALL IMAGE TYPES */

    public function allowImageFiles() {
        $allowedExtensions = array('image/jpg', 'image/jpeg', 'image/gif', 'image/png', 'image/png', 'application/pdf', 'application/vnd.ms-excel', 'application/octet-stream', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->setAllowedExtensions($allowedExtensions);
    }

    /* FILE TO UPLOAD FILE */

    public function uploadFile($targetFile, $newName) {
       //echo $this->destination . '/' . $newName;die();
        if ($this->error == '') {
            if ($this->destination != '') {
                $upload = move_uploaded_file($targetFile, $this->destination . '/' . $newName);
                if (!$upload) {
                    $this->error = 'Upload failed.';
                    if ($this->debugMode) {
                        echo $this->error;
                    }
                    return false;
                } else {
                    return true;
                }
            } else {
                $this->error = 'Define destination first.';
                if ($this->debugMode) {
                    echo $this->error;
                }
            }
        }
    }

    public function delete($file) {
        if (file_exists($file)) {
            unlink($file) or $this->error .= 'Destination Directory Permission Problem.<br />';
        } else {
            $this->error .= 'File not found! Could not delete: ' . $file . '<br />';
        }
        if ($this->error != '' && $this->debugMode) {
            echo $this->error;
        }
    }

    public function validate($file, $validateDimension = false, $msg = '', $err_msg = '') {
		
		

		$error = '';
        /* VALIDATION FOR DIMENSIONS */
        if ($validateDimension) {
            If ($this->widthStart > 0 && $this->widthEnd > 0 && $this->heightStart > 0 && $this->heightEnd > 0) {
                $dims = $this->getDimensions($file);
                if (isset($dims['width'])) {
                    if ($dims['width'] < $this->widthStart || $dims['width'] > $this->widthEnd || $dims['hieght'] < $this->heightStart || $dims['height'] > $this->heightEnd) {
                        $error .= $err_msg. ' Invalid Image Dimensions . ';
                    }
                } else {
                    foreach ($dims as $key => $fileDims) {
                        if ($fileDims['width'] < $this->widthStart || $fileDims['width'] > $this->widthEnd || $fileDims['hieght'] < $this->heightStart || $fileDims['height'] > $this->heightEnd) {
                            $error .= $err_msg.' Invalid Image Dimensions of file ' . $key . '. ';
                        }
                    }
                }
            }
        }

        /* VALIDATION FOR MAX FILE SIZE */
        if ($this->maxSize > 0) {
            if (is_array($file['name'])) {
                foreach ($file['name'] as $key => $fileName) {
                    if ($file['size'][$key] > $this->maxSize) {
                        if ($msg == '') {
                            $error .= $err_msg. ". ";
                        } else {
                            $error .= $err_msg. $msg;
                        }
                    }
                }
            } else {
                if ($file['size'] > $this->maxSize) {
                    if ($msg == '') {
                        $error .= $err_msg.".  ";
                    } else {
                        $error .= $err_msg.$msg;
                    }
                }
            }
        }
        if ($this->minSize > 0) {
            if (is_array($file['name'])) {
                foreach ($file['name'] as $key => $fileName) {
                    if ($file['size'][$key] < $this->minSize) {
                        if ($msg == '') {
                            $error .= $err_msg. ". ";
                        } else {
                            $error .= $err_msg. $msg;
                        }
                    }
                }
            } else {
                if ($file['size'] < $this->minSize) {
                    if ($msg == '') {
                        $error .= $err_msg.".  ";
                    } else {
                        $error .= $err_msg.$msg;
                    }
                }
            }
        }

        /* VALIDATION FOR FILETYPE */
        if (!empty($this->allowedExtensions)) {
            $ext = $this->getExtension($file);			
            if (is_array($ext)) {
                foreach ($ext as $key => $extnsn) {
                    
					if (!in_array(strtolower($extnsn), $this->allowedExtensions)) {
                        //$error .= "Invalid filetype for file ".$key.". ";
                        $error .= $err_msg." Invalid filetype. ";
                    }
                }
            } else {
                if (!in_array(strtolower($ext), $this->allowedExtensions)) {
                    $error .= $err_msg." Invalid filetype. ";
                }
            }
        }
        $this->error = $error;
        if ($error != '') {
            $this->setError($error);
            return false;
        }
        return true;
    }

    public function getExtension($file) {
        if (is_array($file['name'])) {
            $output = array();
            foreach ($file['name'] as $fileKey => $fileName) {
                if ($fileName != '') {
                    $filepath = $file['tmp_name'][$fileKey];
                    $file_info = new finfo(FILEINFO_MIME);
//                    $this->pr(file_get_contents($filepath));
                    $mime_type = $file_info->buffer(file_get_contents($filepath));
                    $mime = explode(';', $mime_type);
//                    $this->pr($mime_type);
                    $output[] = $mime[0];
                }
            }
        } else {
            $output = '';
            $filepath = $file['tmp_name'];
            $file_info = new finfo(FILEINFO_MIME);
            $mime_type = $file_info->buffer(file_get_contents($filepath));
            $mime = explode(';', $mime_type);
            $output = $mime[0];
        }

        return $output;
    }

    public function getDimensions($file) {
        if (is_array($file)) {
            $output = array();
            if (is_array($file['name'])) {
                if (!empty($file['name'][0])) {
                    for ($i = 0; $i < count($file['name']); $i++) {
                        $tmpArr = '';
                        $tmpArr = getimagesize($file['tmp_name'][$i]);
                        $output[$i]['width'] = $tmpArr[0];
                        $output[$i]['height'] = $tmpArr[1];
                    }
                    return $output;
                } else {
                    $this->error = 'No Files supplied.';
                    if ($this->debugMode) {
                        echo $this->error;
                    }
                    return false;
                }
            } else {
                if (!empty($file['name'])) {
                    $tmpArr = '';
                    $tmpArr = getimagesize($file['tmp_name']);
                    $output['width'] = $tmpArr[0];
                    $output['height'] = $tmpArr[1];
                    return $output;
                }
            }
        }
    }

    public function uploadImage($file, $destination, $newname, $maxfilesizeInKb = '', $widthStart = '', $widthEnd = '', $heightStart = '', $heightEnd = '') {
        $this->allowImageFiles();
        $maxfilesize = 0;

        if ($maxfilesizeInKb != '') {
            $maxfilesize = $maxfilesizeInKb * 1024;
            $this->setMaxSize($maxfilesize);
        }

        if ($widthStart != '') {
            $this->widthStart = $widthStart;
        }

        if ($widthEnd != '') {
            $this->widthEnd = $widthEnd;
        }

        if ($heightStart != '') {
            $this->heightStart = $heightStart;
        }

        if ($heightEnd != '') {
            $this->heightEnd = $heightEnd;
        }


        $this->validate($file);
        if ($this->error != '') {
            if (is_array($file['name'])) {
                if (!empty($file['name'][0])) {
                    foreach ($file['name'] as $fileKey => $fileName) {
                        $this->uploadFile($destination[$fileKey], $newname[$fileKey]);
                    }
                } else {
                    $this->error = 'No files to upload.';
                }
            } else {
                if (!empty($file['name'])) {
                    $upload = $this->uploadFile($destination, $newname);
                    if (!$upload) {
                        if ($this->error != '') {
                            if ($this->debugMode) {
                                echo $this->error;
                            }
                        }
                    }
                } else {
                    $this->error = 'No files to upload.';
                }
            }
        }
    }

    public function newFileName($suffix = '', $ext) {
        if ($suffix != '') {
            return date('YmdHis') . "_" . $suffix . "." . $ext;
        } else {
            return date('YmdHis') . "." . $ext;
        }
    }

}
