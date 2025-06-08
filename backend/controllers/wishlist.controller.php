<?php
require_once __DIR__ . '/../services/wishlist.service.php';

class WishlistController {
    public static function addWishlist($data) {
        $result = WishlistService::addWishlist($data);
        echo json_encode($result);
    }

    public static function removeWishlist($data) {
        $result = WishlistService::removeWishlist($data);
        echo json_encode($result);
    }

    public static function getWishlist($customer_id) {
        $result = WishlistService::getWishlist($customer_id);
        echo json_encode($result);
    }
}

?>
