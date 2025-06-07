<?php
require_once __DIR__ . '/../controllers/user.controller.php';

class UserRouter {
    private $controller;

    public function __construct($db) {
        $this->controller = new UserController($db);
    }

    public function addRoutes($router): void {
        // Đăng nhập
        $router->addRoute('POST', '/user/login', 'UserController', 'login');

        // Đăng ký
        $router->addRoute('POST', '/user/register', 'UserController', 'register');

        // Đổi mật khẩu
        $router->addRoute('PUT', '/user/change-pass', 'UserController', 'changePassword');
    }
}
?>