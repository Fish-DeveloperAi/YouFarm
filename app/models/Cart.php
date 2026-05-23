<?php
class Cart {
    private $db;
    public function __construct() {
        $this->db = new Database();
    }

    // Get all cart items for a user, with product info
    public function getItems($userId) {
        $this->db->query("SELECT ci.id AS cart_id, ci.quantity, p.* 
                          FROM cart_items ci 
                          JOIN products p ON ci.product_id = p.id 
                          WHERE ci.user_id = ? 
                          ORDER BY ci.added_at DESC");
        $this->db->bind([$userId]);
        return $this->db->fetchAll();
    }

    // Add a product (if exists, increase quantity)
    public function add($userId, $productId, $qty = 1) {
        // Check if already in cart
        $this->db->query("SELECT id, quantity FROM cart_items WHERE user_id = ? AND product_id = ?");
        $this->db->bind([$userId, $productId]);
        $existing = $this->db->fetchSingle();

        if ($existing) {
            $newQty = $existing['quantity'] + $qty;
            $this->db->query("UPDATE cart_items SET quantity = ? WHERE id = ?");
            return $this->db->bind([$newQty, $existing['id']])->execute();
        } else {
            $this->db->query("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)");
            return $this->db->bind([$userId, $productId, $qty])->execute();
        }
    }

    // Remove one item
    public function remove($cartId) {
        $this->db->query("DELETE FROM cart_items WHERE id = ?");
        return $this->db->bind([$cartId])->execute();
    }

    // Clear whole cart for a user
    public function clear($userId) {
        $this->db->query("DELETE FROM cart_items WHERE user_id = ?");
        return $this->db->bind([$userId])->execute();
    }

    // Get total items count (for badge)
    public function countItems($userId) {
        $this->db->query("SELECT SUM(quantity) AS total FROM cart_items WHERE user_id = ?");
        $this->db->bind([$userId]);
        $result = $this->db->fetchSingle();
        return $result['total'] ?? 0;
    }
}