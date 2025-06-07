<?php
class Cart
{
    public $conn;
    private $table_name = "cart";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    private function generateID()
    {
        return strtoupper(substr(bin2hex(random_bytes(5)), 0, 10));
    }

    public function create($customer_id)
    {
        $cart_id = $this->generateID();
        $created_at = date('Y-m-d H:i:s');
        $query = "INSERT INTO {$this->table_name} (cart_id, customer_id, created_at) 
                  VALUES (:cart_id, :customer_id, :created_at)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":cart_id", $cart_id);
        $stmt->bindParam(":customer_id", $customer_id);
        $stmt->bindParam(":created_at", $created_at);
        if ($stmt->execute()) {
            return [
                'cart_id' => $cart_id,
                'customer_id' => $customer_id,
                'created_at' => $created_at,
            ];
        }
        return false;
    }

    public function addToCart($cart_id, $product_id, $quantity)
    {
        $query = "SELECT cart_item_id, quantity FROM cart_item WHERE cart_id = :cart_id AND product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cart_id', $cart_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($item) {
            $newQuantity = $item['quantity'] + $quantity;
            if ($newQuantity > 0) {
                $updateQuery = "UPDATE cart_item SET quantity = :quantity WHERE cart_item_id = :cart_item_id AND cart_id = :cart_id";
                $updateStmt = $this->conn->prepare($updateQuery);
                $updateStmt->bindParam(':quantity', $newQuantity);
                $updateStmt->bindParam(':cart_item_id', $item['cart_item_id']);
                $updateStmt->bindParam(':cart_id', $cart_id);
                if ($updateStmt->execute()) {
                    return [
                        'cart_item_id' => $item['cart_item_id'],
                        'cart_id' => $cart_id,
                        'product_id' => $product_id,
                        'quantity' => $newQuantity,
                    ];
                }
            } else {
                $deleteQuery = "DELETE FROM cart_item WHERE cart_item_id = :cart_item_id";
                $deleteStmt = $this->conn->prepare($deleteQuery);
                $deleteStmt->bindParam(':cart_item_id', $item['cart_item_id']);
                $deleteStmt->execute();

                return null;
            }
        } else {
            if ($quantity > 0) {
                $checkCartQuery = "SELECT 1 FROM cart WHERE cart_id = :cart_id";
                $checkCartStmt = $this->conn->prepare($checkCartQuery);
                $checkCartStmt->bindParam(':cart_id', $cart_id);
                $checkCartStmt->execute();

                if ($checkCartStmt->rowCount() === 0) {
                    return null;
                }

                $cart_item_id = $this->generateID();
                $insertQuery = "INSERT INTO cart_item (cart_item_id, cart_id, product_id, quantity) 
                            VALUES (:cart_item_id, :cart_id, :product_id, :quantity)";
                $insertStmt = $this->conn->prepare($insertQuery);
                $insertStmt->bindParam(':cart_item_id', $cart_item_id);
                $insertStmt->bindParam(':cart_id', $cart_id);
                $insertStmt->bindParam(':product_id', $product_id);
                $insertStmt->bindParam(':quantity', $quantity);
                if ($insertStmt->execute()) {
                    return [
                        'cart_item_id' => $cart_item_id,
                        'cart_id' => $cart_id,
                        'product_id' => $product_id,
                        'quantity' => $quantity,
                    ];
                }
            }
        }

        return false;
    }

    public function findOne($id)
    {
        if (empty($id)) {
            return null;
        }
        $query = "SELECT c.*, ci.cart_item_id, ci.product_id, ci.quantity, p.product_name, p.base_price, p.discount_price, p.image_url
              FROM cart c
              LEFT JOIN cart_item ci ON ci.cart_id = c.cart_id
              LEFT JOIN product p ON p.product_id = ci.product_id
              WHERE c.customer_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function removeFromCart($cart_item_id, $product_id)
    {
        $query = "DELETE FROM cart_item WHERE cart_item_id = :cart_item_id AND product_id = :product_id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':cart_item_id', $cart_item_id);
        $stmt->bindParam(':product_id', $product_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getCartWithItemsByCustomer($customer_id)
    {
        $query = "SELECT c.cart_id, c.customer_id, c.created_at,
                         ci.cart_item_id, ci.product_id, ci.quantity,
                         p.product_name, p.base_price, p.discount_price, p.image_url
                  FROM cart c
                  LEFT JOIN cart_item ci ON ci.cart_id = c.cart_id
                  LEFT JOIN product p ON p.product_id = ci.product_id
                  WHERE c.customer_id = :customer_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':customer_id', $customer_id);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        if (!$rows) {
            return null;
        }
    
        $cart = [
            'cart_id' => $rows[0]['cart_id'],
            'customer_id' => $rows[0]['customer_id'],
            'created_at' => $rows[0]['created_at'],
            'items' => []
        ];
    
        foreach ($rows as $row) {
            if ($row['cart_item_id']) {
                $cart['items'][] = [
                    'cart_item_id' => $row['cart_item_id'],
                    'product_id' => $row['product_id'],
                    'quantity' => $row['quantity'],
                    'product' => [
                        'product_name' => $row['product_name'],
                        'base_price' => $row['base_price'],
                        'discount_price' => $row['discount_price'],
                        'image_url' => $row['image_url']
                    ]
                ];
            }
        }

        return $cart;
    }
public function clearCartByCustomer($customer_id)
{
    // Lấy cart_id theo customer_id
    $cart_id_query = "SELECT cart_id FROM cart WHERE customer_id = :customer_id";
    $stmt = $this->conn->prepare($cart_id_query);
    $stmt->bindParam(':customer_id', $customer_id);
    $stmt->execute();
    $cart = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cart) {
        $cart_id = $cart['cart_id'];

        // Xóa các cart_item trước
        $delete_items_query = "DELETE FROM cart_item WHERE cart_id = :cart_id";
        $stmt_items = $this->conn->prepare($delete_items_query);
        $stmt_items->bindParam(':cart_id', $cart_id);
        $stmt_items->execute();

        // Sau đó xóa giỏ hàng
        $delete_cart_query = "DELETE FROM cart WHERE cart_id = :cart_id";
        $stmt_cart = $this->conn->prepare($delete_cart_query);
        $stmt_cart->bindParam(':cart_id', $cart_id);
        return $stmt_cart->execute();
    }

    return false;
}
public function updateCartItemQuantity($cart_item_id, $quantity)
{
    if ($quantity > 0) {
        $query = "UPDATE cart_item SET quantity = :quantity WHERE cart_item_id = :cart_item_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':cart_item_id', $cart_item_id);
        return $stmt->execute();
    } else {
        // Xóa luôn nếu số lượng <= 0
        $deleteQuery = "DELETE FROM cart_item WHERE cart_item_id = :cart_item_id";
        $deleteStmt = $this->conn->prepare($deleteQuery);
        $deleteStmt->bindParam(':cart_item_id', $cart_item_id);
        return $deleteStmt->execute();
    }
}



}