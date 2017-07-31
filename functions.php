<?php
/**
 * Classes Autoloader
 * @param string $class_name
 */
function __autoload($class_name) {
    include_once LIB_PATH.'class.' . $class_name . '.php';
}

/**
 * Page Redirect
 * @param string $location
 */
function redirect_to( $location = NULL ) {
  if ($location != NULL) {
    header("Location: {$location}");
    exit;
  }
}

/**
 * Print the message if it's not empty
 * @param string $message
 * @return string
 */
function output_message($message = '') {
    if (!empty($message)) {
        return "<section class='system_message'>{$message}</section>";
    } else {
        return "";
    }
}

/**
 * get a variable from $_GET, $_POST 
 * @param string $varname
 * @param mixed $alternative
 * @param string $m
 * @param int $filter
 */
function get_env($varname='', $alternative=null, $m='post', $filter = FILTER_SANITIZE_STRING) {
    $method = strtolower($m);
    switch ($method) {
        case 'get': 
            $input_method = INPUT_GET;
            break;
        case 'server': 
            $input_method = INPUT_SERVER;
            break;
        default:
            $input_method = INPUT_POST;
    }
    $ret_value = filter_input($input_method, $varname, $filter);
    if (!$ret_value) {
        $ret_value = $alternative;
    }
    return  $ret_value;
}

/**
 * Short for mysqli->real_escape_string
 * @param string $str
 * @return string
 */
function escape_string($str) {
    global $db;
    return $db->real_escape_string($str);
}

/**
 * Returns an array safe to insert to SQL request
 * @param array $arr
 * @return array
 */
function sanitize_array($arr) {
    $clean_attributes = [];
    foreach($arr as $key => $value){
        $clean_attributes[$key] = escape_string($value);
    }
    return $clean_attributes;
}

/**
 *  Loads element and returns it
 * @param string $element
 * @return string
 */
function get_element($element) {
    include ELEMENTS_PATH."element_".$element.".php";
    return $output;
}

/**
 *  Adds leading zero if the number < 10
 * @param mixed $v
 * @return string
 */
function zero($v) {
    if ( (is_int($v)) && $v >= 0) {
        return ($v > 0 && $v < 10)  ? "0".$v : $v;
    } else {
        return '';
    }    
}

/**
 * Returns time in Hour:Minute format 
 * @param int $datetime
 * @return string
 */
function f_HM($datetime) {
    if (TIME_FORMAT == 12) {
        return strftime('%I:%M %P', $datetime) ;
    } else {
        return strftime('%H:%M', $datetime);
    }
}