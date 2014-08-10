<?php #Database Access class

class DBA {
    private $dsn, $username, $password, $dbname, $host, $pdo_handler;
    
    function __construct($dsn,$username,$password,$dbname) {
        $this->dsn = $dsn;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->host = 'localhost';
    }
    
    public function connect() {
        try{
            $this->pdo_handler = new PDO($this->dsn,$this->username,$this->password);
        } catch(PDOException $exception) {
            if($exception->getCode() == 1049) {//Unknown database/chosen database not found
                $mysqli_connect = new mysqli($this->host,$this->username,$this->password);
                if(mysqli_connect_errno())
                    die("Database connection failed: " . mysqli_error());
                
                $query_string = "CREATE DATABASE IF NOT EXISTS " . $this->dbname;
                $mysqli_query_result = mysqli_query($mysqli_connect, $query_string);
                if(!$mysqli_query_result)
                    die("Database connection failed. Please try again at another time.");
                $this->connect();
            }else {
                die("Database connection failed: " . $exception->getMessage());
            }
        }
    }
    
    public function disconnect() {
        $this->pdo_handler = null;
    }
    
    public function do_query($query_string, $mnemonic_values = array()) {
        if(empty($query_string))
            return false;
        $query = $this->pdo_handler->prepare($query_string);
        $query->execute($mnemonic_values);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }        
}
?>