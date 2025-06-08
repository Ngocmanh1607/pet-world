<?php
require_once __DIR__ . '/../services/address.service.php';

class AddressController {
    // Lấy danh sách địa chỉ
    public static function getAddresses() {
        $customer_id = $_GET['customer_id'] ?? '';
        $result = AddressService::getAddresses($customer_id);
        echo json_encode($result);
    }

    // Thêm địa chỉ
public static function addAddress($data) {
    $result = AddressService::addAddress($data);
    echo json_encode($result);
}


    // Sửa địa chỉ
    public static function updateAddress() {
        $address_id = $_POST['address_id'] ?? '';
        $data = [
            'address_line' => $_POST['address_line'] ?? '',
            'ward_id' => $_POST['ward_id'] ?? '',
            'is_default' => $_POST['is_default'] ?? 'FALSE'
        ];
        $result = AddressService::updateAddress($address_id, $data);
        echo json_encode($result);
    }

    // Xoá địa chỉ
    public static function deleteAddress() {
        $address_id = $_POST['address_id'] ?? '';
        $result = AddressService::deleteAddress($address_id);
        echo json_encode($result);
    }
}
?>
