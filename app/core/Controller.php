<?php
class Controller {
    public function view($view, $data = []) {
        extract($data);
        $viewPath = "app/views/{$view}.php";
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("View '$view' not found.");
        }
    }

    public function model($model) {
        require_once "app/models/{$model}.php";
        return new $model();
    }
}