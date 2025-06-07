<?php
// Nạp file OrderController chứa các logic xử lý liên quan đến đơn hàng
require_once __DIR__ . '/../controllers/OrderController.php';

// Lớp OrderRouter chịu trách nhiệm định nghĩa các tuyến (routes) liên quan đến đơn hàng
class OrderRouter
{
    private $db; // Biến lưu kết nối đến cơ sở dữ liệu
    private $controller; // Biến lưu instance của OrderController

    // Hàm khởi tạo, nhận đối tượng kết nối cơ sở dữ liệu ($db) và khởi tạo OrderController
    public function __construct($db)
    {
        $this->db = $db;
        $this->controller = new OrderController($db);
    }

    // Hàm định nghĩa các tuyến API liên quan đến đơn hàng
    public function addRoutes($router)
    {
        // 1. Tạo đơn hàng từ giỏ hàng (dựa vào thông tin trong giỏ)
        $router->addRoute('POST', '/orders/cart', 'OrderController', 'createOrderFromCart');

        // 2. Mua ngay một sản phẩm mà không cần thêm vào giỏ
        $router->addRoute('POST', '/orders/buynow', 'OrderController', 'buyNow');

        // 3. Lấy danh sách tất cả các đơn hàng của một khách hàng cụ thể
        $router->addRoute('GET', '/orders/customer/{customer_id}', 'OrderController', 'getAllOrdersByCustomer');

        // 4. Xem chi tiết một đơn hàng cụ thể
        $router->addRoute('GET', '/orders/{order_id}', 'OrderController', 'getOrderDetail');

        // 5. Xác nhận đơn hàng (thường dùng bởi admin hoặc sau khi khách hàng thanh toán)
        $router->addRoute('POST', '/orders/confirm', 'OrderController', 'confirmOrder');

        // --- Các tuyến thống kê đơn hàng và dữ liệu kinh doanh ---

        // 6. Đếm tổng số đơn hàng (cho mục đích thống kê)
        $router->addRoute('GET', '/orders/statistics/total', 'OrderController', 'countOrders');

        // 7. Đếm số đơn hàng theo từng trạng thái (ví dụ: pending, confirmed, cancelled...)
        $router->addRoute('GET', '/orders/statistics/by-status', 'OrderController', 'countOrdersByStatus');

        // 8. Thống kê doanh thu trong tháng hiện tại
        $router->addRoute('GET', '/orders/statistics/revenue-this-month', 'OrderController', 'revenueThisMonth');

        // 9. Đếm số lượng mã khuyến mãi đang hoạt động
        $router->addRoute('GET', '/orders/statistics/active-promotions', 'OrderController', 'countActivePromotions');

        // 10. Đếm tổng số sản phẩm đang có (phục vụ dashboard quản lý)
        $router->addRoute('GET', '/orders/statistics/total-products', 'OrderController', 'countProducts');

        // 11. Đếm tổng số khách hàng
        $router->addRoute('GET', '/orders/statistics/total-customers', 'OrderController', 'countCustomers');

        // 12. Tuyến tổng hợp tất cả các thống kê trên một request duy nhất (gộp lại để giảm số lần gọi API)
        $router->addRoute('GET', '/orders/statistics/all', 'OrderController', 'getAllStatistics');
        $router->addRoute('GET', '/admin/orders', 'OrderController', 'getAllOrders');
    }
}
