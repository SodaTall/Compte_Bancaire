<?php

class OperationBancaire {
    private $conn;
    private $table_name = "operations_bancaires";

    public $id;
    public $account_number;
    public $type;
    public $amount;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($account_number, $type, $amount) {
        if (!$this->accountExists($account_number)) {
            return false; // Account does not exist
        }
        $query = "INSERT INTO " . $this->table_name . " (account_number, type, amount) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $account_number);
        $stmt->bindParam(2, $type);
        $stmt->bindParam(3, $amount);
        return $stmt->execute();
    }

    public function readByAccount($account_number) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE account_number = ? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $account_number);
        $stmt->execute();
        return $stmt;
    }

    private function accountExists($account_number) {
        $query = "SELECT COUNT(*) as count FROM comptes_bancaires WHERE account_number = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $account_number);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'] > 0;
    }
}

?>