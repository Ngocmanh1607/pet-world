<?php
require_once __DIR__.'/../models/customer.model.php';

class CustomerService {
    public static function getCustomerInfo($customer_id) {
        return CustomerModel::getById($customer_id);
    }

    public static function updateCustomerInfo($customer_id, $data) {
        return CustomerModel::update($customer_id, $data);
    }
}
?>
