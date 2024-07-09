<?php
class Database {
    private $host = "mysql-banquiere.alwaysdata.net";
    private $db_name = "banquiere_soda";
    private $username = "banquiere";
    private $password = "Som@rieme1967";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>
