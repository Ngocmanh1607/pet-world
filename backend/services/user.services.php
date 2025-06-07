<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/user.model.php';

class UserService {
    public function login($user_name, $password) {
        $user = getUserByUsername($user_name);
        if ($user && $user['password'] === $password) {
            return $user;
        }
        return false;
    }

    public function register($user_name, $password) {
        $existing = getUserByUsername($user_name);
        if ($existing) return false;

        return insertUser($user_name, $password);
    }

    public function changePassword($user_name, $old_pass, $new_pass) {
        $user = getUserByUsername($user_name);
        if (!$user || $user['password'] !== $old_pass) return false;

        return updatePassword($user_name, $new_pass);
    }
}
?>