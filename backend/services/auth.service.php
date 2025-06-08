<?php
require_once __DIR__ . '/../model/auth.model.php';

class AuthService
{
    private $authModel;
    private $cus;
    public function __construct($db)
    {
        $this->authModel = new Auth($db);
        $this->cus = new CustomerService($db);
    }

    public function login($user_name, $password)
    {
        if (empty($user_name) || empty($password)) {
            return [
                'error' => true,
                'message' => 'Missing user_name or password'
            ];
        }

        $user = $this->authModel->login($user_name, $password);
        if ($user) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['user_name'];
            $_SESSION['role'] = $user['role'];

            $responseData = [
                'status' => 200,
                'data' => [
                    'success' => true,
                    'message' => 'Đăng nhập thành công',
                    'user' => $user['user_name'], // Mặc định hiển thị user_name
                    'role' => $user['role'],
                    'session_id' => session_id()
                ]
            ];

            if ($user['role'] === 'user') {
                try {
                    $customer_id = $this->cus->getCustomerByCustomerId($_SESSION['user_id']);

                    if ($customer_id && isset($customer_id['customer_id'])) {
                        $customer = $this->cus->getCustomerById($customer_id['customer_id']);
                        $_SESSION['customer_id'] = $customer_id['customer_id'];

                        if ($customer) {
                            $responseData['data']['user'] = $customer['customer_name'];
                            $responseData['data']['customer'] = [
                                'customer_id' => $customer['customer_id'],
                                'customer_name' => $customer['customer_name']
                            ];
                        }
                    }
                } catch (Exception $e) {
                    error_log('Error getting customer info: ' . $e->getMessage());
                }
            }

            $params = session_get_cookie_params();
            setcookie(session_name(), session_id(), [
                'expires' => time() + 86400,
                'path' => '/',
                'domain' => '',
                'secure' => false,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);

            return $responseData;
        }

        return [
            'status' => 401,
            'data' => [
                'success' => false,
                'message' => 'Đăng nhập thất bại'
            ]
        ];
    }
    /**
     * Đăng ký tài khoản mới với thông tin khách hàng và địa chỉ
     */
    public function register($userData)
    {
        // Kiểm tra dữ liệu đầu vào cần thiết
        if (empty($userData['user_name']) || empty($userData['password'])) {
            return [
                'status' => 400,
                'data' => [
                    'success' => false,
                    'message' => 'Vui lòng điền đầy đủ tên đăng nhập và mật khẩu'
                ]
            ];
        }
    
        if ($this->authModel->checkUserExists($userData['user_name'])) {
            return [
                'status' => 400,
                'data' => [
                    'success' => false,
                    'message' => 'Tên đăng nhập đã tồn tại'
                ]
            ];
        }
    
        try {
            $this->authModel->beginTransaction();
    
            // Tạo tài khoản người dùng
            $userId = $this->authModel->createUser($userData['user_name'], $userData['password']);
    
            if (!$userId) {
                throw new Exception("Không thể tạo tài khoản người dùng");
            }
    
            // Tạo thông tin khách hàng
            $customerData = [
                'user_id' => $userId,
                'customer_name' => $userData['customer_name'] ?? $userData['user_name'],
                'email' => $userData['email'] ?? null,
                'phone' => $userData['phone'] ?? null,
                'date_of_birth' => $userData['date_of_birth'] ?? null,
                'gender' => $userData['gender'] ?? null
            ];
    
            $customerId = $this->authModel->createCustomer($customerData);
    
            if (!$customerId) {
                throw new Exception("Không thể tạo thông tin khách hàng");
            }
    
            // Kiểm tra có địa chỉ không
            $hasAddress = !empty($userData['address_line']) && 
                         (
                             !empty($userData['ward_id']) || 
                             (!empty($userData['ward_name']) && !empty($userData['district_name']) && !empty($userData['province_name']))
                         );
    
            if ($hasAddress) {
                // Chuẩn bị dữ liệu địa chỉ
                $addressData = [
                    'customer_id' => $customerId,
                    'address_line' => $userData['address_line'],
                    'ward_id' => $userData['ward_id'] ?? null,
                    'ward_name' => $userData['ward_name'] ?? null,
                    'district_name' => $userData['district_name'] ?? null,
                    'province_name' => $userData['province_name'] ?? null,
                    'is_default' => 1
                ];
    
                $addressId = $this->authModel->createAddress($addressData);
    
                if (!$addressId) {
                    throw new Exception("Không thể tạo địa chỉ");
                }
            }
    
            $this->authModel->commit();
    
            return [
                'status' => 201,
                'data' => [
                    'success' => true,
                    'message' => 'Đăng ký thành công',
                    'user_id' => $userId,
                    'customer_id' => $customerId
                ]
            ];
        } catch (Exception $e) {
            $this->authModel->rollback();
            error_log('Registration error: ' . $e->getMessage());
    
            return [
                'status' => 500,
                'data' => [
                    'success' => false,
                    'message' => 'Đăng ký thất bại: ' . $e->getMessage()
                ]
            ];
        }
    }
}