<?php #read.php
require_once('class.dbaccess.php');
require_once('globals.php');
require_once('class.webpage.php');

if(isset($_GET['pid'])) {
    $pid = $_GET['pid'];
      
    $mysql_connection = new DBA($dsn,$username,$password,$dbname);
    $mysql_connection->connect();
    $query_statement = "SELECT * FROM Persons WHERE pid = $pid";
    $result = $mysql_connection->do_query($query_statement);
    
    $page_content = "";
    $page_content .= "
    <a href='index.php'>Homepage</a>
    <table>
        <tr>
            <th>Name</th>
            <th>Gender</th>
            <th>Home Phone</th>
            <th>Cell Phone</th>
            <th>Alternate Phone</th>
        </tr>";
    foreach($result as $record):
    $page_content .= "
    <tr>
        <td>$record[forename] $record[surname]</td>
        <td>$record[gender]</td>
        <td>$record[home_phone]</td>
        <td>$record[cell_phone]</td>
        <td>$record[alternate_phone]</td>
    </tr>";
    endforeach;
    $page_content .= "</table>";
    
    $view_page = new Webpage("CRUD - Read Record");
    $view_page->setContent($page_content);
    $view_page->display();
    $mysql_connection->disconnect();
    
} else {
    header('Location: index.php');
    exit;
}
?>