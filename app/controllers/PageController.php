<?php
class PageController extends Controller {
    public function about() {
        $title = 'About Us - YouFarm';
        $styles = 'about.css';
        $this->view('about', compact('title', 'styles'));
    }

    public function contact() {
        $title = 'Help Center - YouFarm';
        $styles = 'contact.css';
        $this->view('contact', compact('title', 'styles'));
    }

    public function data() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
        $title = 'Live Data - YouFarm';
        $styles = 'Data.css';
        $this->view('data', compact('title', 'styles'));
    }

    public function shop() {
        $title = 'Shop - YouFarm';
        $styles = 'shop.css';
        $this->view('shop', compact('title', 'styles'));
    }
}