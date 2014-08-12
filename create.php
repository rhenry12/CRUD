<?php #create.php
require_once('globals.php');
require_once('class.dbaccess.php');
require_once('class.webpage.php');

$mysql_connection = new DBA($dsn,$username,$password,$dbname);
if($_SERVER['REQUEST_METHOD'] == "POST") {
    $forename = $_POST['required'][0];
    $surname = $_POST['required'][1];
    $gender = $_POST['required'][2];
    $home_phone = $_POST['required'][3];
    $cell_phone = $_POST['cell_phone'];
    $alternate_phone = $_POST['alternate_phone'];
    $valid = validate_form($_POST);
    if($valid === true) {
        $mysql_connection->connect();
        $query_statement = "INSERT INTO `Persons`(`forename`, `surname`, `gender`, `home_phone`, `cell_phone`, `alternate_phone`)
                            VALUES(':forename', ':surname', ':gender', ':home_phone', ':cell_phone', ':alternate_phone')";
        $values = array(':forename' => $forename,
                        ':surname' => $surname,
                        ':gender' => $gender,
                        ':home_phone' => $home_phone,
                        ':cell_phone' => $cell_phone,
                        ':alternate_phone' => $alternate_phone);
        $query_result = $mysql_connection->do_query($query_statement, $values);
        if(!is_bool($query_result)) {
            $message = "<p id='message' class='success'>Your data was successfully entered.</p>";
            $forename = $surname = $gender = $home_phone = $cell_phone = $alternate_phone = '';
        }
        else
            $message = "<p id='message' class='fail'>The data was not entered into the database.</p>";       
    } else {
        $error_message = $valid;
    }
}
$page_content = "";

if(isset($message))
    $page_content .= $message;

$page_content .= "
<a href='index.php'>Homepage</a>
<h3>Fill in the fields below and submit</h3>
";
if(isset($error_message)):
    $page_content .= "<p id='warning'>Please fill in <em>all</em> required fields (" . ucfirst(str_replace("_"," ",implode($error_message,", "))) . ")!</p>";
endif;

$option = array('M' => '',
                'F' => '');

if(isset($gender)):
    switch($gender) {
        case 'M':
            $option['M'] = 'selected';
            break;
        case 'F':
            $option['F'] = 'selected';
            break;
        default:
            $option['M'] = '';
            $option['F'] = '';
    }
endif;

$page_content .= "
<form action='create.php' method='post'>
    <label for='forename'>First Name</label><br />
    <input type='text' id ='forename' name='required[]' value='";
    if(isset($forename)&&!empty($forename))
        $page_content .= $forename;
    $page_content .= "' /> *<br />
    
    <label for='surname'>Last Name</label><br />
    <input type='text' id ='surname' name='required[]' value='";
    if(isset($surname)&&!empty($surname))
        $page_content .= $surname;
    $page_content .= "' /> *<br />
    
    <label for='gender'>Gender</label><br />
    <select name='required[]' id ='gender'>
        <option value=''></option>
        <option value='M' $option[M]>Male</option>
        <option value='F' $option[F]>Female</option>
    </select> *<br />
    
    <label for='home_phone'>Day\Night phone number</label><br />
    <input id ='home_phone' type='text' name='required[]' value='";
    if(isset($home_phone)&&!empty($home_phone))
        $page_content .= $home_phone;
    $page_content .= "' size='20' /> *<br />
    
    <label for='cell_phone'>Mobile phone number</label><br />
    <input id ='cell_phone' type='text' name='cell_phone' value='";
    if(isset($cell_phone)&&!empty($cell_phone))
        $page_content .= $cell_phone;
    $page_content .= "' size='20' /><br />
    
    <label for='alternate_phone'>Alternate Phone</label><br />
    <input id ='alternate_phone' type='text' name='alternate_phone' value='";
    if(isset($alternate_phone)&&!empty($alternate_phone))
        $page_content .= $alternate_phone;
    $page_content .= "' size='20' /><br />

    <input type='submit' value='Submit' />
</form>
";

$create_page = new Webpage("CRUD - Create a New Record");
$create_page->setContent($page_content);
$create_page->display();
$mysql_connection = $mysql_connection->disconnect();
#end Create.php
?>