<?php
require_once __DIR__.'/../models/wishlist.model.php';
class WishlistService {
    public static function addWishlist($data) {
        $data['wishlist_id'] = uniqid('WL');
        $success = WishlistModel::add($data);
        return ['success' => $success];
    }

    public static function removeWishlist($data) {
        $success = WishlistModel::remove($data);
        return ['success' => $success];
    }

    public static function getWishlist($customer_id) {
        $result = WishlistModel::getByCustomer($customer_id);
        return $result;
    }
}
?>