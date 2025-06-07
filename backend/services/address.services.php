<?php
require_once __DIR__.'/../models/address.model.php';

class AddressService {
    public static function getAddresses($customer_id) {
        return AddressModel::getByCustomer($customer_id);
    }

    public static function addAddress($data) {
        // Check max 3 địa chỉ
        $addresses = AddressModel::getByCustomer($data['customer_id']);
        if (count($addresses) >= 3) {
            return ['success' => false, 'message' => 'Chỉ được lưu tối đa 3 địa chỉ'];
        }

        // Chuẩn hóa dữ liệu
        $data['address_id'] = uniqid('AD');
        $data['is_default'] = ($data['is_default'] == true || $data['is_default'] === "true" || $data['is_default'] == 1) ? 1 : 0;

        $success = AddressModel::add($data);
        return ['success' => $success];
    }


    public static function updateAddress($address_id, $data) {
        $success = AddressModel::update($address_id, $data);
        return ['success' => $success];
    }

    public static function deleteAddress($address_id) {
        $success = AddressModel::delete($address_id);
        return ['success' => $success];
    }
}
?>
