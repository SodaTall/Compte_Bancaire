<?php
class CompteBancaire {
    private $conn;
    private $table_name = "comptes_bancaires";

    public $account_number;
    public $balance;
    public $client_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET balance=:balance, client_id=:client_id";
        $stmt = $this->conn->prepare($query);

        $this->balance = htmlspecialchars(strip_tags($this->balance));
        $this->client_id = htmlspecialchars(strip_tags($this->client_id));

        $stmt->bindParam(":balance", $this->balance);
        $stmt->bindParam(":client_id", $this->client_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function readAll() {
        $query = "SELECT comptes_bancaires.account_number, comptes_bancaires.balance, clients.first_name, clients.last_name
                  FROM " . $this->table_name . "
                  JOIN clients ON comptes_bancaires.client_id = clients.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    public function readOne($account_number) {
        $query = "SELECT comptes_bancaires.account_number, comptes_bancaires.balance, clients.first_name, clients.last_name
                  FROM " . $this->table_name . "
                  JOIN clients ON comptes_bancaires.client_id = clients.id
                  WHERE comptes_bancaires.account_number = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $account_number);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function deposit($account_number, $amount) {
        $query = "UPDATE " . $this->table_name . " SET balance = balance + ? WHERE account_number = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $amount);
        $stmt->bindParam(2, $account_number);
        return $stmt->execute();
    }

    public function withdraw($account_number, $amount) {
        $query = "UPDATE " . $this->table_name . " SET balance = balance - ? WHERE account_number = ? AND balance >= ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $amount);
        $stmt->bindParam(2, $account_number);
        $stmt->bindParam(3, $amount);
        return $stmt->execute();
    }

    public function transfer($from_account_number, $to_account_number, $amount) {
        try {
            $this->conn->beginTransaction();

            $withdraw = $this->withdraw($from_account_number, $amount);
            $deposit = $this->deposit($to_account_number, $amount);

            if ($withdraw && $deposit) {
                $this->conn->commit();
                return true;
            } else {
                $this->conn->rollBack();
                return false;
            }
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

}
?>
