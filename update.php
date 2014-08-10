<?php #update.php
require_once('class.dbaccess.php');
require_once('globals.php');
require_once('class.webpage.php');

if(isset($_GET['pid'])) {
    $pid = $_GET['pid'];
    $mysql_connection = new DBA($dsn,$username,$password,$dbname);
    $mysql_connection->connect();
    $query_statement = "SELECT * FROM Persons WHERE pid = :pid";
    $values = array(':pid' => $pid);
    $result = $mysql_connection->do_query($query_statement, $values);
    $row = current($result);

    $forename = $row['forename'];
    $surname = $row['surname'];
    $gender = $row['gender'];
    $home_phone = $row['home_phone'];
    $cell_phone = $row['cell_phone'];
    $alternate_phone = $row['alternate_phone'];

    $option = array('M' => '',
                    'F' => '');
    switch($gender) {
        case "M":
            $option['M'] = "selected";
            break;
        case "F":
            $option['F'] = "selected";
            break;
        default:
            $option['M'] = '';
            $option['F'] = '';
    }
    
    if($_SERVER['REQUEST_METHOD'] == "POST") {
        $forename = $_POST['forename'];
        $surname =$_POST['surname'];
        $gender =$_POST['gender'];
        $home_phone = $_POST['home_phone'];
        $cell_phone = $_POST['cell_phone'];
        $alternate_phone = $_POST['alternate_phone'];
        
        $valid = validate_form($_POST, false);
        
        if($valid === true) {
            $query_statement = "UPDATE `Persons` SET
                                    `forename` = '$forename',
                                    `surname` = '$surname',
                                    `gender` = '$gender',
                                    `home_phone` = '$home_phone',
                                    `cell_phone` = '$cell_phone',
                                    `alternate_phone` = '$alternate_phone'
                                WHERE `pid` = :pid;";
            $values = array(':pid' => $pid);
            $query_result = $mysql_connection->do_query($query_statement, $values);
            if(!is_bool($query_result)) {
                header("Location: read.php?pid=$pid");
                exit;
            }
            else
                $message = "<p id='message' class='fail'>The record failed to update.</p>";       
        }else {
            $error_message = $valid;
        }
    }
$page_content = "";
if(isset($message))
    $page_content .= $message;
    
$page_content .= "
    <a href='index.php'>Homepage</a>
";
if(isset($error_message))
    $page_content .= "<p id='warning'>Please fill in <em>all</em> required fields (" . ucfirst(str_replace("_"," ",implode($error_message,", "))) . ")!</p>";
        
    $page_content .= "
    <form action='update.php?pid=$pid' method='post'>
        <table>
            <tbody>
                <tr>
                    <th>
                        First Name
                    </th>
                    <td>
                        <input type='text' name='forename' value='$forename' />
                    </td>
                </tr>
                <tr>
                    <th>
                        Last Name
                    </th>
                    <td>
                        <input type='text' name='surname' value='$surname' />
                    </td>
                </tr>
                <tr>
                    <th>
                        Gender
                    </th>
                    <td>
                        <select name='gender'>
                            <option $option[M] value='M'>Male</option>
                            <option $option[F] value='F'>Female</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>
                        Day/Night Phone number
                    </th>
                    <td>
                        <input type='text' name='home_phone' value='$home_phone' size='20' />
                    </td>
                </tr>
                <tr>
                    <td>
                        Mobile Phone number
                    </td>
                    <td>
                        <input type='text' name='cell_phone' value='$cell_phone' size='20' />
                    </td>
                </tr>
                <tr>
                    <td>
                        Alternate Phone
                    </td>
                    <td>
                        <input type='text' name='alternate_phone' value='$alternate_phone' size='20' />
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type='submit' value='Submit' />
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
    ";
$update_page = new Webpage("CRUD - Update Record");
$update_page->setContent($page_content);
$update_page->display();
$mysql_connection->disconnect();
} else {
    header('Location: '.$_SERVER['HTTP_REFERER']);
    exit;
}
?>