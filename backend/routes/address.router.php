<?php
require_once __DIR__ . '/../controllers/address.controller.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_address'])) {
    AddressController::addAddress([
        'customer_id'   => $_POST['customer_id'],
        'address_line'  => $_POST['address_line'],
        'ward_id'       => $_POST['ward_id'],
        'is_default'    => $_POST['is_default'],
    ]);
}
?>