<?php #index.php
require_once('class.dbaccess.php');
require_once('globals.php');
require_once('class.webpage.php');

$mysql_connection = new DBA($dsn,$username,$password,$dbname);
$mysql_connection->connect();
if(!sizeof($mysql_connection->do_query("SHOW TABLES"))) {
   $query_statement = "CREATE TABLE IF NOT EXISTS `Persons` (
                     `pid` int(11) NOT NULL AUTO_INCREMENT,
                     `forename` varchar(255) DEFAULT NULL,
                     `surname` varchar(255) DEFAULT NULL,
                     `gender` enum('M', 'F') NOT NULL,
                     `home_phone` varchar(20) DEFAULT NULL,
                     `cell_phone` varchar(20) DEFAULT NULL,
                     `alternate_phone` varchar(20) DEFAULT NULL,
                     PRIMARY KEY (`pid`)
                     )";
   
   $result = $mysql_connection->do_query($query_statement);
   if(is_bool($result))
      die("There was a problem creating the table `Persons` in the database.");
}

$query_statement = "SELECT `pid`, `forename`, `surname` FROM `Persons` ORDER BY `surname` ASC";
$result = $mysql_connection->do_query($query_statement);
$page_content = "";
$page_content .= "
            <table>
               <tbody>
                  <tr>
                     <th>
                        Last Name
                     </th>
                     <th>
                        First Name
                     </th>
                  </tr>
"; 
         if(empty($result)) {
            $page_content .= "No records found";
         } else {
            foreach($result as $record):
               $pid = $record['pid'];
               $page_content .= "
                  <tr>
                     <td>
                        $record[surname]
                     </td>
                     <td>
                        $record[forename]
                     </td>
                     <td>
                        <a href='read.php?pid=$pid'>view</a>
                        <a href='update.php?pid=$pid'>edit</a> 
                        <a href='delete.php?pid=$pid'>delete</a>
                     </td>
                  </tr>";
            endforeach;
         }
$page_content .= "
               </tbody> 
            </table>
            <a href='create.php'>Create A New Record</a>
";

$crud = new Webpage("CRUD - Record Management");
$crud->setContent($page_content);
$crud->display();
$mysql_connection->disconnect();
#end index.php
?>