<?php
class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function findByEmail($email) {
        $this->db->query("SELECT * FROM users WHERE email = ?");
        $this->db->bind([$email]);
        return $this->db->fetchSingle();
    }

    public function register($data) {
        $hashed = password_hash($data['password'], PASSWORD_DEFAULT);
        $this->db->query("INSERT INTO users (FirstName, LastName, email, password, `National ID`, `Property Title`, age) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $this->db->bind([
            $data['FirstName'],
            $data['LastName'],
            $data['email'],
            $hashed,
            $data['NationalID'],
            $data['PropertyTitle'],
            $data['age']
        ]);
        return $this->db->execute();
    }
}