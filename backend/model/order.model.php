<?php
class Order
{
    private $conn;
    private $table_name = "order";

    // Constructor khởi tạo kết nối CSDL
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Hàm tạo mã ID ngẫu nhiên cho order hoặc order_detail
    private function generateID()
    {
        return strtoupper(substr(bin2hex(random_bytes(5)), 0, 10)); // Tạo chuỗi hex ngẫu nhiên dài 10 ký tự
    }

    // Cập nhật trạng thái đơn hàng
    public function updateOrderStatus($order_id, $status)
    {
        $query = "UPDATE `order` SET status = :status WHERE order_id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':order_id', $order_id);
        return $stmt->execute(); // Trả về true/false tùy vào việc cập nhật có thành công hay không
    }

    // Tạo đơn hàng mới từ giỏ hàng
    public function createOrderFromCart($data, $cart_items)
    {
        $order_id = $this->generateID(); // Tạo ID đơn hàng mới
        $order_date = date('Y-m-d H:i:s'); // Ngày tạo đơn hàng

        // Chuẩn bị truy vấn tạo đơn hàng
        $query = "INSERT INTO `order` (
            order_id, customer_id, address_id, promotion_id, order_date, status, total_amount, payment_method, payment_status
        ) VALUES (
            :order_id, :customer_id, :address_id, :promotion_id, :order_date, :status, :total_amount, :payment_method, :payment_status
        )";
        $stmt = $this->conn->prepare($query);
        
        // Gán các giá trị vào câu lệnh SQL
        $stmt->bindParam(':order_id', $order_id);
        $stmt->bindParam(':customer_id', $data['customer_id']);
        $stmt->bindParam(':address_id', $data['address_id']);
        $stmt->bindParam(':promotion_id', $data['promotion_id']);
        $stmt->bindParam(':order_date', $order_date);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':total_amount', $data['total_amount']);
        $stmt->bindParam(':payment_method', $data['payment_method']);
        $stmt->bindParam(':payment_status', $data['payment_status']);

        // Nếu tạo đơn hàng thành công, tiến hành thêm từng sản phẩm trong giỏ hàng vào order_detail
        if ($stmt->execute()) {
            foreach ($cart_items as $item) {
                $order_detail_id = $this->generateID();
                $detailQuery = "INSERT INTO order_detail (
                    order_detail_id, order_id, product_id, quantity, unit_price
                ) VALUES (
                    :order_detail_id, :order_id, :product_id, :quantity, :unit_price
                )";
                $detailStmt = $this->conn->prepare($detailQuery);
                $detailStmt->bindParam(':order_detail_id', $order_detail_id);
                $detailStmt->bindParam(':order_id', $order_id);
                $detailStmt->bindParam(':product_id', $item['product_id']);
                $detailStmt->bindParam(':quantity', $item['quantity']);
                $detailStmt->bindParam(':unit_price', $item['unit_price']);
                $detailStmt->execute();
            }
            return $order_id; // Trả về order_id nếu thành công
        }
        return false; // Trả về false nếu thất bại
    }

    // Tạo đơn hàng khi người dùng chọn "Mua ngay"
    public function buyNow($data, $product)
    {
        $order_id = $this->generateID(); // Tạo ID đơn hàng mới
        $order_date = date('Y-m-d H:i:s'); // Ngày tạo đơn

        // Tương tự như createOrderFromCart, nhưng chỉ có 1 sản phẩm
        $query = "INSERT INTO `order` (
            order_id, customer_id, address_id, promotion_id, order_date, status, total_amount, payment_method, payment_status
        ) VALUES (
            :order_id, :customer_id, :address_id, :promotion_id, :order_date, :status, :total_amount, :payment_method, :payment_status
        )";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->bindParam(':customer_id', $data['customer_id']);
        $stmt->bindParam(':address_id', $data['address_id']);
        $stmt->bindParam(':promotion_id', $data['promotion_id']);
        $stmt->bindParam(':order_date', $order_date);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':total_amount', $data['total_amount']);
        $stmt->bindParam(':payment_method', $data['payment_method']);
        $stmt->bindParam(':payment_status', $data['payment_status']);

        if ($stmt->execute()) {
            $order_detail_id = $this->generateID();
            $detailQuery = "INSERT INTO order_detail (
                order_detail_id, order_id, product_id, quantity, unit_price
            ) VALUES (
                :order_detail_id, :order_id, :product_id, :quantity, :unit_price
            )";
            $detailStmt = $this->conn->prepare($detailQuery);
            $detailStmt->bindParam(':order_detail_id', $order_detail_id);
            $detailStmt->bindParam(':order_id', $order_id);
            $detailStmt->bindParam(':product_id', $product['product_id']);
            $detailStmt->bindParam(':quantity', $product['quantity']);
            $detailStmt->bindParam(':unit_price', $product['unit_price']);
            $detailStmt->execute();

            return $order_id; // Trả về order_id nếu thành công
        }
        return false; // Trả về false nếu thất bại
    }

    // Lấy tất cả đơn hàng theo customer_id (dành cho user)
    public function getAllOrdersByCustomer($customer_id)
    {
        $query = "SELECT 
            o.order_id, 
            o.order_date, 
            o.status, 
            o.total_amount,
            o.payment_method, 
            o.payment_status,
            c.customer_name, 
            c.phone,
            a.address_line, 
            a.ward_id,
            COUNT(od.product_id) as total_items
          FROM `order` o
          JOIN customer c ON o.customer_id = c.customer_id
          JOIN address a ON o.address_id = a.address_id
          LEFT JOIN order_detail od ON o.order_id = od.order_id
          WHERE o.customer_id = :customer_id
          GROUP BY o.order_id, o.order_date, o.status, o.total_amount, 
                  o.payment_method, o.payment_status, c.customer_name, 
                  c.phone, a.address_line, a.ward_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':customer_id', $customer_id);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($orders as &$order) {
            $detailQuery = "SELECT 
                          od.product_id,
                          od.quantity as count,
                          od.unit_price as price,
                          p.product_name as name,
                          p.stock as quantity,
                          p.image_url,
                          c.category_name as category
                        FROM order_detail od
                        JOIN product p ON od.product_id = p.product_id
                        LEFT JOIN category c ON p.category_id = c.category_id
                        WHERE od.order_id = :order_id";

            $detailStmt = $this->conn->prepare($detailQuery);
            $detailStmt->bindParam(':order_id', $order['order_id']);
            $detailStmt->execute();
            $items = $detailStmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($items as &$item) {
                $item['price'] = number_format($item['price'], 0, ',', '.');
                $item['count'] = (string) $item['count'];
                $item['quantity'] = (string) $item['quantity'];
                $item['image'] = $item['image_url'];
                unset($item['image_url']);
            }

            $order['items'] = $items;
            $order['id'] = $order['order_id'];
            $order['status'] = $order['status'];
            $order['total'] = number_format($order['total_amount'], 0, ',', '.') . 'đ';

            if (!empty($items)) {
                $firstItem = $items[0];
                $order['image'] = $firstItem['image'];
                $order['name'] = $firstItem['name'];
                $order['category'] = $firstItem['category'];
                $order['quantity'] = $firstItem['quantity'];
                $order['count'] = $firstItem['count'];
                $order['price'] = $firstItem['price'];

                $numericPrice = (int) str_replace('.', '', $firstItem['price']);
                $numericCount = (int) $firstItem['count'];
                $order['total'] = number_format($numericPrice * $numericCount, 0, ',', '.') . 'đ';
            }
        }

        return $orders;
    }

    // Lấy chi tiết một đơn hàng (bao gồm sản phẩm bên trong)
    public function getOrderDetail($order_id)
    {
        $query = "SELECT o.*, od.order_detail_id, od.product_id, od.quantity, od.unit_price, p.product_name
                  FROM `order` o
                  JOIN order_detail od ON od.order_id = o.order_id
                  JOIN product p ON p.product_id = od.product_id
                  WHERE o.order_id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Trả về thông tin chi tiết đơn hàng
    }

    // Đếm tổng số đơn hàng trong hệ thống
    public function countOrders()
    {
        $query = "SELECT COUNT(*) as total FROM `order`";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // Đếm số lượng đơn hàng theo từng trạng thái (vd: pending, completed, canceled)
    public function countOrdersByStatus()
    {
        $query = "SELECT status, COUNT(*) as total FROM `order` GROUP BY status";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tính tổng doanh thu trong tháng hiện tại từ các đơn hàng đã hoàn tất
    public function revenueThisMonth()
    {
        $query = "SELECT SUM(total_amount) as revenue FROM `order` 
                  WHERE status = 'completed' AND MONTH(order_date) = MONTH(CURDATE()) AND YEAR(order_date) = YEAR(CURDATE())";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['revenue'] ?? 0; // Trả về 0 nếu không có đơn
    }

    // Đếm số lượng khuyến mãi đang hoạt động
    public function countActivePromotions()
    {
        $query = "SELECT COUNT(*) as total FROM promotion WHERE is_active = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // Đếm tổng số sản phẩm
    public function countProducts()
    {
        $query = "SELECT COUNT(*) as total FROM product";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // Đếm tổng số người dùng có role là 'user' (khách hàng)
    public function countCustomers()
    {
        $query = "SELECT COUNT(*) as total FROM user_account WHERE role = 'user'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
    public function getAllOrders()
    {
        $query = "SELECT * FROM `order` ORDER BY order_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}