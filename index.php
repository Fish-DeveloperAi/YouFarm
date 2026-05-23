<?php

require_once 'app/config/config.php';
require_once 'app/core/Router.php';
require_once 'app/core/Controller.php';
require_once 'app/core/Database.php';
$router = new Router();

$router->add('/', 'HomeController', 'index');
$router->add('home', 'HomeController', 'index');
$router->add('data', 'PageController', 'data');
$router->add('shop', 'ShopController', 'index');
$router->add('about', 'PageController', 'about');
$router->add('contact', 'PageController', 'contact');
$router->add('login', 'AuthController', 'login');
$router->add('register', 'AuthController', 'register');
$router->add('logout', 'AuthController', 'logout');
$router->add('cart', 'CartController', 'showCart');
$router->add('cart/add', 'CartController', 'add');
$router->add('cart/remove', 'CartController', 'remove');
$router->add('cart/clear', 'CartController', 'clear');
$router->add('cart/count', 'CartController', 'count');

$url = isset($_GET['url']) ? $_GET['url'] : '/';
$url = trim($url, '/');
$url = ($url === '') ? '/' : $url;
$router->dispatch($url);