<?php #delete.php
require_once('class.dbaccess.php');
require_once('globals.php');
require_once('class.webpage.php');
    
if(isset($_GET['pid'])) {
    $pid = intval($_GET['pid']);
    $mysql_connection = new DBA($dsn,$username,$password,$dbname);
    $mysql_connection->connect();
    $query_statement = "SELECT * FROM `Persons` WHERE `pid` = :pid;";
    $values = array(':pid' => $pid);
    $result = $mysql_connection->do_query($query_statement,$values);


    if($_SERVER['REQUEST_METHOD'] == "POST") {
        $pid = intval($_POST['pid']);
        $query_statement = "DELETE FROM `Persons` WHERE `pid` = :pid;";
        $values = array(':pid' => $pid);
        $result = $mysql_connection->do_query($query_statement, $values);
        if(!is_bool($result)) {
            header('Location: index.php');
            exit;
        }else {
            $message = "<p id='message' class='fail'>Unable to delete the requested record.</p>"; 
        }
    }
    
    $page_content = "";
    $page_content .= "
        <a href='index.php'>Homepage</a>
        <form action='' method='post'>
        <table>
            <tbody>
                <tr>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Home Phone</th>
                    <th>Cell Phone</th>
                    <th>Alternate Phone</th>
                </tr>
    ";
    foreach($result as $row):
        $page_content .= "
                <tr>
                    <td>
                        $row[forename] $row[surname]
                    </td>
                    <td>
                        $row[gender]
                    </td>
                    <td>
                        $row[home_phone]
                    </td>
                    <td>
                        $row[cell_phone]
                    </td>
                    <td>
                        $row[alternate_phone]
                    </td>
                </tr>
        ";
    endforeach;
    $page_content .= "
            </tbody>
        </table>
        <input type='hidden' name='pid' value='$pid' />
        <input type='submit' name='deletion' value='Delete Record' />
        </form>
    ";
    $delete_page = new Webpage("CRUD - Delete Record");
    $delete_page->setContent($page_content);
    $delete_page->display();
    
    $mysql_connection->disconnect();
}else {
    header('Location: '.$_SERVER['HTTP_REFERER']);
    exit;
}
?>