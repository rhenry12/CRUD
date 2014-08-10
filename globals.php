<?php #Global variables and functions
$username = "user1";
$password = "password1234";
$dbname = "mysql_db";
$dsn = "mysql:host=localhost;dbname=$dbname";

function validate_form($form, $required=true) {
    $empty_fields = array();
    
    if($required) {
        $assoc_keys = array('first_name','last_name','gender','phone_number');
        foreach($form['required'] as $field => $value) {
            if(strlen(trim($value)) == 0)
                array_push($empty_fields, $assoc_keys[$field]);
        }
    }else {
        foreach($form as $field => $value) {
            if(strlen(trim($value)) == 0)
                array_push($empty_fields, $field);
        }
    }
    
    if(count($empty_fields)>0)
        return $empty_fields;
    
    return true;
}
?>