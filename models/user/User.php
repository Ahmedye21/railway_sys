<?php
// models/User.php

require_once '../core/config.php';

class User {
    private $conn;
    private $table = "users";
    
    public $id;
    public $name;
    public $email;
    public $password;
    public $user_type;
    public $balance;
    
    public function __construct() {
        $this->conn = DatabaseConfig::getConnection();
    }
    

    public function signup() {
        $this->balance = 0.00;
        
        if($this->emailExists()) {
            return false;
        }
        
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        
        $query = "INSERT INTO users SET 
                name        = :name, 
                email       = :email, 
                password    = :password, 
                user_type   = :user_type,
                balance     = :balance";

        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":user_type", $this->user_type);
        $stmt->bindParam(":balance", $this->balance);
        
        if($stmt->execute()) {
            $this->id = $this->getNextId();
            return true;
        }
        
        return false;
    }

    private function getNextId() {
        $query = "SELECT MAX(id) + 1 AS next_id FROM users";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['next_id'] ?: 1;
    }
    
    public function authenticate() {
        $query = "SELECT id, name, email, password, user_type, balance FROM users 
                WHERE email = ? 
                LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row && password_verify($this->password, $row['password'])) {
            $this->id           = $row['id'];
            $this->name         = $row['name'];
            $this->user_type    = $row['user_type'];
            $this->balance      = $row['balance'];
            return true;
        }
        
        return false;
    }
    
    private function emailExists() {
        $query = "SELECT id FROM users WHERE email = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
?>