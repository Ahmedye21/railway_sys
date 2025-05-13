<?php
// models/User.php


require_once CORE_PATH . '/config.php';


class User {
    private $conn;
    private $table = "users";
    
    public $id;
    public $name;
    public $email;
    public $password;
    public $role;
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
                role   = :role,
                balance     = :balance";

        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":role", $this->role);
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
        $query = "SELECT id, name, email, password, role, balance FROM users 
                WHERE email = ? 
                LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row && password_verify($this->password, $row['password'])) {
            $this->id           = $row['id'];
            $this->name         = $row['name'];
            $this->role         = $row['role'];
            $this->balance      = $row['balance'];
            return true;
        }
        
        return false;
    }
    public function updateProfile() {
        $query = "UPDATE users SET 
                name        = :name, 
                email       = :email, 
                password    = :password, 
                balance     = :balance
                WHERE id    = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":balance", $this->balance);
        $stmt->bindParam(":id", $this->id);
        
        return $stmt->execute();
    }

    public function rechargeBalance($userId, $amount) {
        try {
            $this->conn->beginTransaction();

            // Update user balance
            $stmt = $this->conn->prepare("
                UPDATE users 
                SET balance = balance + ? 
                WHERE id = ?
            ");
            $stmt->execute([$amount, $userId]);

            // Record the transaction
            $stmt = $this->conn->prepare("
                INSERT INTO transactions (
                    user_id, 
                    amount
                ) VALUES (?, ?)
            ");
            $stmt->execute([$userId, $amount]);

            $this->conn->commit();
            return $amount;
        } catch (Exception $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            throw $e;
        }
    }

    public function getBalance($userId) {
        $stmt = $this->conn->prepare("
            SELECT balance FROM users WHERE id = ?
        ");
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (float)$result['balance'];
    }
    
    public function getAllUsers() {
        $query = "SELECT id, name, email, role, balance FROM users";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    

    private function emailExists() {
        $query = "SELECT id FROM users WHERE email = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    
    public function createUser($userData) {
        try {
            if ($this->emailExists($userData['email'])) {
                error_log("Attempt to create user with existing email: " . $userData['email']);
                return false;
            }

            $hashedPassword = password_hash($userData['password'], PASSWORD_DEFAULT);
            
            $query = "INSERT INTO users SET 
                    name = :name, 
                    email = :email, 
                    password = :password, 
                    role = :role,
                    balance = :balance";
    
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":name", $userData['name']);
            $stmt->bindParam(":email", $userData['email']);
            $stmt->bindParam(":password", $hashedPassword);
            $stmt->bindParam(":role", $userData['role']);
            $stmt->bindParam(":balance", $userData['balance']);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error creating user: " . $e->getMessage());
            return false;
        }
    }
    
    public function updateUser($userData, $newPassword = null) {
        try {
            // Check if email is being changed and if the new email already exists for another user
            $currentUserDataStmt = $this->conn->prepare("SELECT email FROM users WHERE id = :id");
            $currentUserDataStmt->bindParam(":id", $userData['id']);
            $currentUserDataStmt->execute();
            $currentUser = $currentUserDataStmt->fetch(PDO::FETCH_ASSOC);

            if ($currentUser && $currentUser['email'] !== $userData['email']) {
                if ($this->emailExists($userData['email'], $userData['id'])) {
                    error_log("Attempt to update user (ID: {$userData['id']}) with existing email: " . $userData['email']);
                    return false; // New email already exists for another user
                }
            }
            
            if ($newPassword) {
                $query = "UPDATE users SET 
                        name = :name, 
                        email = :email, 
                        password = :password, 
                        role = :role,
                        balance = :balance
                        WHERE id = :id";
                        
                $stmt = $this->conn->prepare($query);
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt->bindParam(":password", $hashedPassword);
            } else {
                $query = "UPDATE users SET 
                        name = :name, 
                        email = :email, 
                        role = :role,
                        balance = :balance
                        WHERE id = :id";
                        
                $stmt = $this->conn->prepare($query);
            }
            
            $stmt->bindParam(":name", $userData['name']);
            $stmt->bindParam(":email", $userData['email']);
            $stmt->bindParam(":role", $userData['role']);
            $stmt->bindParam(":balance", $userData['balance']);
            $stmt->bindParam(":id", $userData['id']);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error updating user: " . $e->getMessage());
            return false;
        }
    }
    public function deductBalance($userId, $amount) {
    $stmt = $this->conn->prepare("
        UPDATE users 
        SET balance = balance - :amount 
        WHERE id = :user_id AND balance >= :amount
    ");
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':amount', $amount);
    $stmt->execute();
    return $stmt->rowCount() > 0;
    }

    public function getUserBalance($userId) {
        $stmt = $this->conn->prepare("SELECT balance FROM users WHERE id = :user_id");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['balance'] : 0;
    }
}
?>