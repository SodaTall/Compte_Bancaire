<?php
class Client {
    private $conn;
    private $table_name = "clients";

    public $id;
    public $first_name;
    public $last_name;
    public $address;
    public $phone_number;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET first_name=:first_name, last_name=:last_name, address=:address, phone_number=:phone_number";
        $stmt = $this->conn->prepare($query);

        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->phone_number = htmlspecialchars(strip_tags($this->phone_number));

        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":phone_number", $this->phone_number);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
