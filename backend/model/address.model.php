<?php
require_once __DIR__.'/../config/database.php';

class AddressModel {
    public static function getByCustomer($customer_id) {
        global $conn;
        $sql = "SELECT * FROM address WHERE customer_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $customer_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public static function add($data) {
        global $conn;
        $sql = "INSERT INTO address (address_id, customer_id, address_line, ward_id, is_default, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssi', $data['address_id'], $data['customer_id'], $data['address_line'], $data['ward_id'], $data['is_default']);
        return $stmt->execute();
    }

    public static function update($address_id, $data) {
        global $conn;
        $sql = "UPDATE address SET address_line=?, ward_id=?, is_default=? WHERE address_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssis', $data['address_line'], $data['ward_id'], $data['is_default'], $address_id);
        return $stmt->execute();
    }

    public static function delete($address_id) {
        global $conn;
        $sql = "DELETE FROM address WHERE address_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $address_id);
        return $stmt->execute();
    }
}
?>
