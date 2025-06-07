<?php
require_once __DIR__ . '/../controllers/wishlist.controller.php';
// Thêm sản phẩm vào wishlist
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_wishlist'])) {
    WishlistController::addWishlist($_POST);
}

// Xóa sản phẩm khỏi wishlist
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_wishlist'])) {
    WishlistController::removeWishlist($_POST);
}

// Lấy danh sách wishlist theo customer
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['customer_id'])) {
    WishlistController::getWishlist($_GET['customer_id']);
}
?>