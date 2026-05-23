<?php
class ShopController extends Controller {
    public function index() {
        $productModel = $this->model('Product');
        $products = $productModel->getAll();
        $title = 'Shop - YouFarm';
        $styles = 'shop.css';
        $this->view('shop', compact('title', 'styles', 'products'));
    }
}