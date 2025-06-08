<?php
require_once __DIR__.'/../services/customer.services.php';

class CustomerController {
    public static function getInfo($customer_id) {
        $info = CustomerService::getCustomerInfo($customer_id);
        echo json_encode($info);
    }

    public static function updateInfo($customer_id, $data) {
        $success = CustomerService::updateCustomerInfo($customer_id, $data);
        echo json_encode(['success' => $success]);
    }
}
?>
