<?php
require_once __DIR__ . '/../services/user.services.php';

class UserController {
    private $service;

    public function __construct($db) {
        $this->service = new UserService($db);
    }

    public function login(array $data) {
        $user_name = $data['user_name'] ?? '';
        $password = $data['password'] ?? '';

        $user = $this->service->login($user_name, $password);
        if ($user) {
            return [
                'message' => 'Đăng nhập thành công',
                'user' => $user
            ];
        } else {
            http_response_code(401);
            return ['message' => 'Sai tài khoản hoặc mật khẩu'];
        }
    }

    public function register(array $data) {
        $user_name = $data['user_name'] ?? '';
    $password = $data['password'] ?? '';
    $confirm = $data['re_password'] ?? '';

    if ($password !== $confirm) {
        http_response_code(400);
        return ['message' => 'Mật khẩu xác nhận không khớp'];
    }
        $success = $this->service->register($user_name, $password,);
        if ($success) {
            return ['message' => 'Đăng ký thành công'];
        } else {
            http_response_code(409);
            return ['message' => 'Tài khoản đã tồn tại'];
        }
    }

    public function changePassword(array $data) {
        $user_name = $data['user_name'] ?? '';
        $old_pass = $data['old_password'] ?? '';
        $new_pass = $data['new_password'] ?? '';

        $success = $this->service->changePassword($user_name, $old_pass, $new_pass);
        if ($success) {
            return ['message' => 'Đổi mật khẩu thành công'];
        } else {
            http_response_code(403);
            return ['message' => 'Mật khẩu cũ không đúng'];
        }
    }
}
?>