<?php
class UserModel {
    private $db;
    private $table = 'users';
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    public function getUser($id) {
        $sql = "SELECT id, name, email, username, role, created_at FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getUserByEmail($email) {
        $sql = "SELECT id, name, email, username, role, created_at FROM {$this->table} WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getUserByUsername($username) {
        $sql = "SELECT id, name, email, username, role, created_at FROM {$this->table} WHERE username = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function createUser($data) {
        $sql = "INSERT INTO {$this->table} (name, email, username, password, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['email'],
            $data['username'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['role']
        ]);
    }
    
    public function updateUser($id, $data) {
        $sql = "UPDATE {$this->table} SET name = ?, email = ?, username = ?, role = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['email'],
            $data['username'],
            $data['role'],
            $id
        ]);
    }
    
    public function validateLogin($username, $password) {
        $sql = "SELECT id, password FROM {$this->table} WHERE username = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            return $this->getUser($user['id']);
        }
        return false;
    }
}
