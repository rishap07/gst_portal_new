<?php
/*
 * 
 *  Developed By        :   Rishap Gandhi
 *  Description         :   A simple class for validation to be used throughout the project 
 *  Date Created        :   May 18, 2017
 *  Last Modified       :   May 18, 2017
 *  Last Modified By    :   Rishap Gandhi
 *  Last Modification   :   file creation started
 * 
 */

class cms_validate {

    //protected static $errors = array();
    public static $errors = array();
    protected static $inputs = array();
    protected static $messages = array();
    protected static $_instance = null;

    protected static function _apInstance() {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    private static function setMessage($input, $rule, $message) {
        if (isset(self::$messages[$input][$rule])) {
            self::$errors[$input][] = self::$messages[$input][$rule];
        } else {
            self::$errors[$input][] = $message;
        }
    }

    private static function isBlankField($input) {
        if (empty($input)) {
            return true;
        }
        return false;
    }

    protected static function requiredValidation($input, $name, $lableName) {
        if (empty($input) && $input == '') {
            self::setMessage($name, 'required', $lableName . " is required field");
            return false;
        } else {
            return true;
        }
    }

    protected static function notZeroValidation($input, $name, $lableName) {
        if (empty($input) || $input == 0) {
            self::setMessage($name, 'required', $lableName . " should have valid value.");
            return false;
        } else {
            return true;
        }
    }

    protected static function minValidation($input, $param, $name, $lableName) {
        if (self::isBlankField($input) === false) {
            $length = strlen($input);
            $min = (int) $param;
            if ($length < $min) {
                self::setMessage($name, 'min', "Minimum length should be " . $param . " for " . $lableName);
                return false;
            } else {
                return true;
            }
        }
    }

    protected static function maxValidation($input, $param, $name, $lableName) {
        if (self::isBlankField($input) === false) {
            $length = strlen($input);
            $max = (int) $param;
            if ($length > $max) {
                self::setMessage($name, 'max', "Maximum length should be " . $param . " for " . $lableName);
                return false;
            } else {
                return true;
            }
        }
    }

    protected static function fileValidation($input, $name, $lableName) {
        if (self::isBlankField($input) === false) {
            if (!isset($_FILES[$name])) {
                self::setMessage($name, 'file', $lableName . " is contains no file. Please select file.");
                return false;
            } else {
                return true;
            }
        }
    }

    protected static function dateValidation($input, $name, $lableName) {
        if (self::isBlankField($input) === false) {
            if (strtotime($input) === false) {
                self::setMessage($name, 'date', $lableName . " should be valid date");
                return false;
            } else if (!preg_match("/^(\d\d\d\d)-(\d\d?)-(\d\d?)$/", $input, $matches)) {
                self::setMessage($name, 'date', $lableName . " is not a valid format. Date format should be YYYY-MM-DD");
                return false;
            } else {
                if (!checkdate($matches[2], $matches[3], $matches[1])) {
                    self::setMessage($name, 'date', $lableName . " should be valid date.");
                    return false;
                }
                return true;
            }
        }
    }

    protected static function timeValidation($input, $name, $lableName) {
        if (self::isBlankField($input) === false) {
            if (preg_match("/^([01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]*$/", $input)) {
                return true;
            } else {
                self::setMessage($name, 'time', $lableName . " is not a valid time");
                return false;
            }
        }
    }
    
    protected static function datetimeValidation($input, $name, $lableName) {
        if (self::isBlankField($input) === false) {
            if (strtotime($input) === false) {
                self::setMessage($name, 'datetime', $lableName . " should be valid date");
                return false;
            } else if (!preg_match("/^(\d\d\d\d)-(\d\d?)-(\d\d?)\s([01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]*$/", $input, $matches)) {
                self::setMessage($name, 'date', $lableName . " is not a valid format. Date Time format should be YYYY-MM-DD HH:MM:SS");
                return false;
            } else {
                if (!checkdate($matches[2], $matches[3], $matches[1])) {
                    self::setMessage($name, 'datetime', $lableName . " should be valid datetime.");
                    return false;
                }
                return true;
            }
        }
    }

    protected static function date_maxValidation($input, $param, $name, $lableName) {
        if (self::isBlankField($input) === false) {
            if (strtotime($input) <= strtotime($param)) {
                self::setMessage($name, 'date_max', $lableName . " should be greater than date " . $param);
                return false;
            } else {
                return true;
            }
        }
    }

    protected static function date_minValidation($input, $param, $name, $lableName) {
        if (self::isBlankField($input) === false) {
            if (strtotime($input) >= strtotime($param)) {
                self::setMessage($name, 'date_min', $lableName . " should be smaller than date " . $param);
                return false;
            } else {
                return true;
            }
        }
    }

    protected static function inValidation($input, $param, $name, $lableName) {
        if (self::isBlankField($input) === false) {
            $list = explode(',', $param);
            if (!in_array($input, $list)) {
                self::setMessage($name, 'in', $lableName . " is not allowed.");
                return false;
            } else {
                return true;
            }
        }
    }

    protected static function not_inValidation($input, $param, $name, $lableName) {
        if (self::isBlankField($input) === false) {
            $list = explode(',', $param);
            if (in_array($input, $list)) {
                self::setMessage($name, 'not_in', $lableName . ' is not allowed. Its contains ' . $input);
                return false;
            } else {
                return true;
            }
        }
    }

    protected static function imageValidation($input, $name, $lableName) {
        if (self::isBlankField($input) === false) {
            if (isset($_FILES[$name])) {
                $supportedImage = array('jpg', 'jpeg', 'bmp', 'png', 'gif');
                $givenExt = pathinfo($input);
                $ext = @$givenExt['extension'];
                if (!in_array($ext, $supportedImage)) {
                    self::setMessage($name, 'image', $lableName . " should be image file only");
                    return false;
                } else {
                    return true;
                }
            } else {
                self::setMessage($name, 'image', $lableName . " should be image file only");
                return false;
            }
        }
    }

    protected static function file_typeValidation($input, $param, $name, $lableName) {
        if (self::isBlankField($input) === false) {
            if (isset($_FILES[$name])) {
                $supportedExt = explode(',', $param);
                $givenExt = pathinfo($input);
                $ext = @$givenExt['extension'];
                if (!in_array($ext, $supportedExt)) {
                    self::setMessage($name, 'file_type', $lableName . ": Invalid file type. Should be valid.");
                    return false;
                } else {
                    return true;
                }
            } else {
                return false;
            }
        }
    }

    protected static function sameValidation($input, $param, $name, $lableName) {
        if (self::isBlankField($input) === false) {
            if ($input !== self::$inputs[$param]) {
                self::setMessage($name, 'same', $lableName . " should be same as " . $param);
                return false;
            } else {
                return true;
            }
        }
    }

    protected static function differentValidation($input, $param, $name, $lableName) {
        if (self::isBlankField($input) === false) {
            if ($input === self::$inputs[$param] OR $input == self::$inputs[$param]) {
                self::setMessage($name, 'different', $lableName . " should not be same as " . $param);
                return false;
            } else {
                return true;
            }
        }
    }

    protected static function emailValidation($input, $name, $lableName) {
        if (self::isBlankField($input) === false) {
            if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
                return true;
            } else {
                self::setMessage($name, 'email', $lableName . " should be valid email format");
                return false;
            }
        }
    }

    protected static function urlValidation($input, $name, $lableName) {
        if (self::isBlankField($input) === false) {
            if (filter_var($input, FILTER_VALIDATE_URL)) {
                return true;
            } else {
                self::setMessage($name, 'url', $lableName . " should be valid URL format!");
                return false;
            }
        }
    }

    protected static function ipValidation($input, $name, $lableName) {
        if (self::isBlankField($input) === false) {
            if (filter_var($input, FILTER_VALIDATE_IP)) {
                return true;
            } else {
                self::setMessage($name, 'url', $lableName . " should be valid IP format!");
                return false;
            }
        }
    }

    protected static function alphabetValidation($input, $name, $lableName) {
        if (self::isBlankField($input) === false) {
            if (preg_match("/^[a-zA-Z ]*$/", $input)) {
                return true;
            } else {
                self::setMessage($name, 'alphabet', $lableName . " should be alphabet!");
                return false;
            }
        }
    }

    protected static function alphanumericValidation($input, $name, $lableName) {
        if (self::isBlankField($input) === false) {
            if (preg_match("/^[a-zA-Z0-9 ]*$/", $input)) {
                return true;
            } else {
                self::setMessage($name, 'alphanumeric', $lableName . " should be alphanumeric!");
                return false;
            }
        }
    }

    protected static function numericValidation($input, $name, $lableName) {
        if (self::isBlankField($input) === false) {
            if (is_numeric($input)) {
                return true;
            } else {
                self::setMessage($name, 'numeric', $lableName . " should be numeric only");
                return false;
            }
        }
    }
    
    protected static function decimalValidation($input, $name, $lableName) {
        if (self::isBlankField($input) === false) {
            if (preg_match("/^\s*(?=.*[1-9])\d*(?:\.\d{1,2})?\s*$/", $input)) {
                return true;
            } else {
                self::setMessage($name, 'decimal', $lableName . " should be greater than zero");
                return false;
            }
        }
    }
	
	protected static function decimalzeroValidation($input, $name, $lableName) {
        if (self::isBlankField($input) === false) {
            if (preg_match("/^\s*(?=.*[0-9])\d*(?:\.\d{1,2})?\s*$/", $input)) {
                return true;
            } else {
                self::setMessage($name, 'decimal', $lableName . " should be valid");
                return false;
            }
        }
    }
	
    protected static function genderValidation($input, $name, $lableName) {
            if (self::isBlankField($input) === false) {
                    if (preg_match("/^(?:M|F)$/", $input)) {
                            return true;
                    } else {
                            self::setMessage($name, 'gender', $lableName . " should be valid");
                            return false;
                    }
            }
    }

    protected static function martialstatusValidation($input, $name, $lableName) {
            if (self::isBlankField($input) === false) {
                    if (preg_match("/^(?:S|M)$/", $input)) {
                            return true;
                    } else {
                            self::setMessage($name, 'martialstatus', $lableName . " should be valid");
                            return false;
                    }
            }
    }

    protected static function nationalityValidation($input, $name, $lableName) {
            if (self::isBlankField($input) === false) {
                    if (preg_match("/^(?:I|O)$/", $input)) {
                            return true;
                    } else {
                            self::setMessage($name, 'nationality', $lableName . " should be valid");
                            return false;
                    }
            }
    }

    protected static function identityproofValidation($input, $name, $lableName) {
            if (self::isBlankField($input) === false) {
                    if (preg_match("/^(?:UID|P|VI|DL|O)$/", $input)) {
                            return true;
                    } else {
                            self::setMessage($name, 'identityproof', $lableName . " should be valid");
                            return false;
                    }
            }
    }

    protected static function invoicetypeValidation($input, $name, $lableName) {
        if (self::isBlankField($input) === false) {
            if (preg_match("/^(?:taxinvoice|exportinvoice|sezunitinvoice|deemedexportinvoice)$/", $input)) {
                return true;
            } else {
                self::setMessage($name, 'invoicetype', $lableName . " should be valid");
                return false;
            }
        }
    }
    
    protected static function invoicenatureValidation($input, $name, $lableName) {
        if (self::isBlankField($input) === false) {
            if (preg_match("/^(?:salesinvoice|purchaseinvoice)$/", $input)) {
                return true;
            } else {
                self::setMessage($name, 'invoicenature', $lableName . " should be valid");
                return false;
            }
        }
    }
    
    protected static function supplytypeValidation($input, $name, $lableName) {
        if (self::isBlankField($input) === false) {
            if (preg_match("/^(?:normal|reversecharge|tds|tcs)$/", $input)) {
                return true;
            } else {
                self::setMessage($name, 'supplytype', $lableName . " should be valid");
                return false;
            }
        }
    }

    protected static function invoicedocumentnatureValidation($input, $name, $lableName) {
        if (self::isBlankField($input) === false) {
            if (preg_match("/^(?:revisedtaxinvoice|creditnote|debitnote)$/", $input)) {
                return true;
            } else {
                self::setMessage($name, 'invoicedocumentnature', $lableName . " should be valid");
                return false;
            }
        }
    }

    protected static function invoiecorrespondingValidation($input, $name, $lableName) {
        if (self::isBlankField($input) === false) {
            if (preg_match("/^(?:taxinvoice|bosinvoice)$/", $input)) {
                return true;
            } else {
                self::setMessage($name, 'invoiecorresponding', $lableName . " should be valid");
                return false;
            }
        }
    }

    protected static function integerValidation($input, $name, $lableName) {
        if (self::isBlankField($input) === false) {
            if (is_numeric($input) AND strpos($input, '.') === false) {
                if (is_integer((int) $input)) {
                    return true;
                } else {
                    self::setMessage($name, 'integer', $lableName . " should be interger only");
                    return false;
                }
            } else {
                self::setMessage($name, 'integer', $lableName . " should be interger only");
                return false;
            }
        }
    }

    protected static function rangeValidation($input, $param, $name, $lableName) {
        
        if (self::isBlankField($input) === false) {
            $range = explode(",", $param);
            if (($input >= $range[0]) && ($input <= $range[1])) {
                return true;
            } else {
                self::setMessage($name, 'range', $lableName . " must be between " . $range[0] . " and " . $range[1]);
                return false;
            }
        }
    }

    protected static function patternValidation($input, $param, $name, $lableName) {

        /*if($param == "/^(([0-9]){2}([A-Z]){5}([0-9]){4}([A-Z]){1}([A-Z0-9]){1}([Z]){1}([A-Z0-9]){1})+$/") {
            echo $param;
            echo "<br>";
            die;
        }*/

        if (self::isBlankField($input) === false) {

            if (preg_match($param, $input)) {
                return true;
            } else {
                self::setMessage($name, 'pattern', $lableName . " should be valid");
                return false;
            }
        }
    }

    /*
     * @param array $inputs: Form fields value that will be validated
     * @param array $rules: Validation Rules that will be applied for $input
     * @param array $messages: Custom message for input.
     */

    public static function validate(array $inputs, array $rules, array $messages = null) {
        /* echo "<pre>";
          print_r($inputs);
          echo "<pre>";
          print_r($rules);
          die; */
        //check the param are exactly array
        if (is_array($inputs) && is_array($rules)) {
            self::$inputs = $inputs;
            self::$messages = $messages;

            //contact the all elements of rules array
            foreach ($rules as $key => $val) {

                //split the variable $val to find out Lable Name 
                $val = explode("|#|", $val);
                if (isset($val[1])) {
                    $lableNameStr = $val[1];
                    $lableNameVal = explode("lable_name:", $lableNameStr);
                    $lableName = trim($lableNameVal[1]);
                    if (empty($lableName))
                        $lableName = $key;
                }
                else {
                    $lableName = $key;
                }

                //split the variable $val to find out all rules 
                //and store all rules in $getRule variable
                $rule = explode("||", $val[0]);
                foreach ($rule as $getRule) {

                    //split the variable $getRules to find out its additional paramenter
                    $param = explode(':', $getRule);

                    $input = isset($inputs[$key]) ? trim($inputs[$key]) : $_FILES[$key]['name'];

                    if (count($param) > 1) {
                        $method = $param[0] . "Validation";
                        self::$method($input, $param[1], $key, $lableName);
                    } else {
                        $method = $getRule . "Validation";
                        self::$method($input, $key, $lableName);
                    }
                }
            }

            //print_r(self::$errors);die;
            if (isset(self::$errors)) {
                $_SESSION['_ap']['_validation'] = self::$errors;
            }

            return self::_apInstance();
        }
    }

    /*
     * this function return is Error is occurd or not
     */

    public static function hasErrors() {
        if (!empty(self::$errors)) {
            if (is_array(self::$errors)) {
                if (count(self::$errors) >= 1) {
                    return true;
                } else {
                    return false;
                }
            }
        } elseif (isset($_SESSION['_ap']['_validation'])) {
            if (count($_SESSION['_ap']['_validation']) >= 1) {
                return true;
            } else {
                return false;
            }
        }
    }

    /*
     * this function is gather all errors in given field 
     * if the error are occurd
     */

    public static function getErrors($fieldName) {
        $errors = array();

        if (!empty(self::$errors)) {
            if (array_key_exists($fieldName, self::$errors)) {
                foreach (self::$errors[$fieldName] as $error) {
                    $errors[] = $error;
                }
            }
        } elseif (isset($_SESSION['_ap']['_validation'])) {
            if (array_key_exists($fieldName, $_SESSION['_ap']['_validation'])) {
                foreach ($_SESSION['_ap']['_validation'][$fieldName] as $err) {
                    $errors[] = $err;
                }
            }
        }

        return $errors;
    }

    /*
     * this function is gather all errors in the validation
     * if the error are occurd
     */

    public static function allErrors() {
        $errors = array();
        if (!empty(self::$errors)) {
            if (is_array(self::$errors)) {
                foreach (self::$errors as $key => $val) {
                    foreach (self::$errors[$key] as $err) {
                        $errors[] = $err;
                    }
                }
            }
        } elseif (isset($_SESSION['_ap']['_validation'])) {
            foreach ($_SESSION['_ap']['_validation'] as $key => $val) {
                foreach ($_SESSION['_ap']['_validation'][$key] as $err) {
                    $errors[] = $err;
                }
            }
        }
        return $errors;
    }

    public static function getTrimmedValue($postArray) {
        $trimmedArray = array_map('trim', $postArray);
        return $trimmedArray;
    }

    public static function clearMessages() {
        if (isset($_SESSION['_ap'])) {
            unset($_SESSION['_ap']);
        }
    }

}
