<?php
class HomeController extends Controller {
    public function index() {
        $title = 'YouFarm - Smart Agriculture';
        $styles = 'Home.css';
        $this->view('home', compact('title', 'styles'));
    }
}