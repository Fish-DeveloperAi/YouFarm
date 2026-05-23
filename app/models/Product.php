<?php
class Product {
    private $db;
    public function __construct() {
        $this->db = new Database();
    }

    public function getAll() {
        $this->db->query("SELECT * FROM products ORDER BY name ASC");
        return $this->db->fetchAll();
    }

    public function getById($id) {
        $this->db->query("SELECT * FROM products WHERE id = ?");
        $this->db->bind([$id]);
        return $this->db->fetchSingle();
    }
}