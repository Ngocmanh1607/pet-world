<?php
class Auth
{
    private $conn;
    private $table_name = "user_account";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function login($user_name, $password)
    {
        $query = "SELECT * FROM {$this->table_name} WHERE user_name = :user_name and password_hash = :password_hash";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_name', $user_name);
        $stmt->bindParam(':password_hash', $password);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }

    /**
     * Kiểm tra tên đăng nhập đã tồn tại chưa
     */
    public function checkUserExists($username) 
    {
        $query = "SELECT COUNT(*) FROM {$this->table_name} WHERE user_name = :user_name";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_name', $username);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Tạo tài khoản người dùng mới
     */
    public function createUser($username, $password) 
    {
        try {
            // Tạo user_id tự động
            $query = "SELECT MAX(CAST(user_id AS UNSIGNED)) as max_id FROM {$this->table_name}";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $user_id = ($result['max_id'] ?? 0) + 1;
            
            $created_at = date('Y-m-d H:i:s');
            $role = 'user';
            $status = 'active';
            
            $query = "INSERT INTO {$this->table_name} (
                user_id, 
                user_name, 
                password_hash, 
                role, 
                status, 
                created_at
            ) VALUES (
                :user_id,
                :user_name,
                :password_hash,
                :role,
                :status,
                :created_at
            )";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':user_name', $username);
            $stmt->bindParam(':password_hash', $password);
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':created_at', $created_at);
            
            $stmt->execute();
            return $user_id;
        } catch (PDOException $e) {
            error_log('Error creating user: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Tạo khách hàng với dữ liệu được cung cấp từ client
     */
    public function createCustomer($userData) 
    {
        try {
            // Tạo customer_id tự động
            $query = "SELECT MAX(CAST(SUBSTRING(customer_id, 4) AS UNSIGNED)) as max_id FROM customer";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $nextId = ($result['max_id'] ?? 0) + 1;
            $customer_id = 'CUS' . $nextId;
            
            // Sử dụng dữ liệu từ client
            $user_id = $userData['user_id'];
            $customer_name = $userData['customer_name'];
            $email = $userData['email'] ?? null;
            $phone = $userData['phone'] ?? null;
            $date_of_birth = $userData['date_of_birth'] ?? null;
            $gender = $userData['gender'] ?? null;
            $created_at = date('Y-m-d H:i:s');
            
            $query = "INSERT INTO customer (
                customer_id, 
                user_id, 
                customer_name, 
                email, 
                phone, 
                date_of_birth, 
                gender, 
                created_at
            ) VALUES (
                :customer_id,
                :user_id,
                :customer_name,
                :email,
                :phone,
                :date_of_birth,
                :gender,
                :created_at
            )";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':customer_id', $customer_id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':customer_name', $customer_name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':date_of_birth', $date_of_birth);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':created_at', $created_at);
            
            $stmt->execute();
            return $customer_id;
        } catch (PDOException $e) {
            error_log('Error creating customer: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Tạo địa chỉ mới - Đã sửa để không bắt đầu transaction trong method này
     */
    public function createAddress($addressData) 
    {
        try {
            // Tạo address_id tự động
            $query = "SELECT MAX(CAST(address_id AS UNSIGNED)) as max_id FROM address";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $address_id = ($result['max_id'] ?? 0) + 1;
            
            // Lấy dữ liệu từ client
            $customer_id = $addressData['customer_id'];
            $address_line = $addressData['address_line'];
            $is_default = $addressData['is_default'] ?? 1;
            $created_at = date('Y-m-d H:i:s');
            
            // Xử lý tỉnh/thành phố, quận/huyện và phường/xã nếu được nhập trực tiếp
            $province_name = $addressData['province_name'] ?? null;
            $district_name = $addressData['district_name'] ?? null;
            $ward_name = $addressData['ward_name'] ?? null;
            
            // Nếu có tên tỉnh/thành phố
            if (!empty($province_name)) {
                // Kiểm tra tỉnh/thành phố đã tồn tại chưa
                $province_id = $this->getProvinceIdByName($province_name);
                
                if (!$province_id) {
                    // Nếu chưa có, tạo mới
                    $province_id = $this->createProvince($province_name);
                }
                
                // Nếu có tên quận/huyện
                if (!empty($district_name) && $province_id) {
                    // Kiểm tra quận/huyện đã tồn tại chưa
                    $district_id = $this->getDistrictIdByName($district_name, $province_id);
                    
                    if (!$district_id) {
                        // Nếu chưa có, tạo mới
                        $district_id = $this->createDistrict($district_name, $province_id);
                    }
                    
                    // Nếu có tên phường/xã
                    if (!empty($ward_name) && $district_id) {
                        // Kiểm tra phường/xã đã tồn tại chưa
                        $ward_id = $this->getWardIdByName($ward_name, $district_id);
                        
                        if (!$ward_id) {
                            // Nếu chưa có, tạo mới
                            $ward_id = $this->createWard($ward_name, $district_id);
                        }
                    }
                }
            } else if (isset($addressData['ward_id'])) {
                // Sử dụng ward_id đã có nếu không nhập trực tiếp
                $ward_id = $addressData['ward_id'];
            } else {
                // Sử dụng ward_id mặc định nếu không có dữ liệu
                $ward_id = 1; // Ward mặc định
            }
            
            // Thực hiện insert vào bảng address
            $query = "INSERT INTO address (
                address_id, 
                customer_id, 
                address_line, 
                ward_id, 
                is_default, 
                created_at
            ) VALUES (
                :address_id,
                :customer_id,
                :address_line,
                :ward_id,
                :is_default,
                :created_at
            )";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':address_id', $address_id);
            $stmt->bindParam(':customer_id', $customer_id);
            $stmt->bindParam(':address_line', $address_line);
            $stmt->bindParam(':ward_id', $ward_id);
            $stmt->bindParam(':is_default', $is_default);
            $stmt->bindParam(':created_at', $created_at);
            
            $stmt->execute();
            
            return $address_id;
        } catch (PDOException $e) {
            error_log('Error creating address: ' . $e->getMessage());
            throw $e; // Ném ngoại lệ để service xử lý rollback
        }
    }
    
    /**
     * Kiểm tra ward_id có tồn tại trong bảng ward không
     */
    public function checkWardExists($ward_id) 
    {
        try {
            $query = "SELECT COUNT(*) FROM ward WHERE ward_id = :ward_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':ward_id', $ward_id);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log('Error checking ward: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lấy province_id theo tên
     */
    private function getProvinceIdByName($province_name) 
    {
        $query = "SELECT province_id FROM province WHERE province_name = :province_name";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':province_name', $province_name);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['province_id'] : null;
    }
    
    /**
     * Tạo mới tỉnh/thành phố
     */
    private function createProvince($province_name) 
    {
        // Tạo province_id tự động
        $query = "SELECT MAX(CAST(province_id AS UNSIGNED)) as max_id FROM province";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $province_id = ($result['max_id'] ?? 0) + 1;
        
        $query = "INSERT INTO province (province_id, province_name) VALUES (:province_id, :province_name)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':province_id', $province_id);
        $stmt->bindParam(':province_name', $province_name);
        $stmt->execute();
        
        return $province_id;
    }
    
    /**
     * Lấy district_id theo tên và province_id
     */
    private function getDistrictIdByName($district_name, $province_id) 
    {
        $query = "SELECT district_id FROM district WHERE district_name = :district_name AND province_id = :province_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':district_name', $district_name);
        $stmt->bindParam(':province_id', $province_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['district_id'] : null;
    }
    
    /**
     * Tạo mới quận/huyện
     */
    private function createDistrict($district_name, $province_id) 
    {
        // Tạo district_id tự động
        $query = "SELECT MAX(CAST(district_id AS UNSIGNED)) as max_id FROM district";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $district_id = ($result['max_id'] ?? 0) + 1;
        
        $query = "INSERT INTO district (district_id, district_name, province_id) 
                  VALUES (:district_id, :district_name, :province_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':district_id', $district_id);
        $stmt->bindParam(':district_name', $district_name);
        $stmt->bindParam(':province_id', $province_id);
        $stmt->execute();
        
        return $district_id;
    }
    
    /**
     * Lấy ward_id theo tên và district_id
     */
    private function getWardIdByName($ward_name, $district_id) 
    {
        $query = "SELECT ward_id FROM ward WHERE ward_name = :ward_name AND district_id = :district_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ward_name', $ward_name);
        $stmt->bindParam(':district_id', $district_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['ward_id'] : null;
    }
    
    /**
     * Tạo mới phường/xã
     */
    private function createWard($ward_name, $district_id) 
    {
        // Tạo ward_id tự động
        $query = "SELECT MAX(CAST(ward_id AS UNSIGNED)) as max_id FROM ward";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $ward_id = ($result['max_id'] ?? 0) + 1;
        
        $query = "INSERT INTO ward (ward_id, ward_name, district_id) 
                  VALUES (:ward_id, :ward_name, :district_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ward_id', $ward_id);
        $stmt->bindParam(':ward_name', $ward_name);
        $stmt->bindParam(':district_id', $district_id);
        $stmt->execute();
        
        return $ward_id;
    }
    
    /**
     * Transaction management methods
     */
    
    /**
     * Bắt đầu transaction an toàn
     */
    public function beginTransaction() 
    {
        try {
            // Kiểm tra xem transaction đã bắt đầu chưa
            if ($this->conn && !$this->conn->inTransaction()) {
                return $this->conn->beginTransaction();
            }
            return true; // Transaction đã bắt đầu hoặc không có kết nối
        } catch (PDOException $e) {
            error_log('Begin transaction error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Commit transaction an toàn
     */
    public function commit() 
    {
        try {
            if ($this->conn && $this->conn->inTransaction()) {
                return $this->conn->commit();
            }
            return true; // Không có transaction nào để commit
        } catch (PDOException $e) {
            error_log('Commit error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Rollback transaction an toàn
     */
    public function rollback() 
    {
        try {
            if ($this->conn && $this->conn->inTransaction()) {
                return $this->conn->rollBack();
            }
            return true; // Không có transaction nào để rollback
        } catch (PDOException $e) {
            error_log('Rollback error: ' . $e->getMessage());
            return false;
        }
    }
}