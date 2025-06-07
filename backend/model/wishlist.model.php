<?php
require_once __DIR__ . '/../config/database.php';
class WishlistModel {
    public static function add($data) {
        global $conn;
        $sql = "INSERT INTO wishlist (wishlist_id, customer_id, product_id, created_at)
                VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            'sss',
            $data['wishlist_id'],
            $data['customer_id'],
            $data['product_id']
        );
        return $stmt->execute();
    }

    public static function remove($data) {
        global $conn;
        $sql = "DELETE FROM wishlist WHERE customer_id = ? AND product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $data['customer_id'], $data['product_id']);
        return $stmt->execute();
    }

    public static function getByCustomer($customer_id) {
        global $conn;
        $sql = "SELECT * FROM wishlist WHERE customer_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $customer_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
