<?php
class CartController extends Controller {
    public function add() {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Please login first.']);
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') exit;

        $productId = $_POST['product_id'] ?? null;
        if (!$productId) {
            echo json_encode(['error' => 'No product ID.']);
            return;
        }

        $cartModel = $this->model('Cart');
        $cartModel->add($_SESSION['user_id'], $productId, 1);
        $count = $cartModel->countItems($_SESSION['user_id']);
        echo json_encode(['success' => true, 'cartCount' => $count]);
    }

        public function showCart() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
        $cartModel = $this->model('Cart');
        $items = $cartModel->getItems($_SESSION['user_id']);
        $title = 'My Cart - YouFarm';
        $styles = 'cart.css';
        $this->view('cart', compact('title', 'styles', 'items'));
    }

    public function remove() {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') exit;
        $cartId = $_POST['cart_id'] ?? null;
        if ($cartId) {
            $cartModel = $this->model('Cart');
            $cartModel->remove($cartId);
        }
        header('Location: ' . BASE_URL . 'cart');
        exit;
    }

    public function clear() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
        $cartModel = $this->model('Cart');
        $cartModel->clear($_SESSION['user_id']);
        header('Location: ' . BASE_URL . 'cart');
        exit;
    }

    public function count() {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['count' => 0]);
            return;
        }
        $cartModel = $this->model('Cart');
        $count = $cartModel->countItems($_SESSION['user_id']);
        echo json_encode(['count' => $count]);
    }
}