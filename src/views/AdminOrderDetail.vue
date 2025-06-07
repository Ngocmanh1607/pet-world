<template>
  <div class="admin-order-detail">
    <!-- Hiển thị loading khi đang tải dữ liệu -->
    <div v-if="loading" class="loading">
      <div class="spinner"></div>
    </div>
    <!-- Hiển thị lỗi nếu có -->
    <div v-else-if="error" class="error">
      {{ error }}
    </div>
    <!-- Hiển thị chi tiết đơn hàng -->
    <div v-else class="order-detail">
      <!-- Header với tiêu đề và các nút thao tác -->
      <div class="header">
        <h1>Chi tiết đơn hàng #{{ order.id }}</h1>
        <div class="actions">
          <button @click="goBack" class="btn-back">
            <i class="fas fa-arrow-left"></i> Quay lại
          </button>
          <button @click="showEditModal = true" class="btn-edit">
            <i class="fas fa-edit"></i> Chỉnh sửa
          </button>
        </div>
      </div>

      <!-- Các section thông tin đơn hàng -->
      <div class="order-info">
        <!-- Thông tin cơ bản của đơn hàng -->
        <div class="info-section">
          <h2>Thông tin đơn hàng</h2>
          <div class="info-grid">
            <div class="info-item">
              <label>Trạng thái đơn hàng:</label>
              <span :class="['status', order.status]">{{
                getStatusText(order.status)
              }}</span>
            </div>
            <div class="info-item">
              <label>Ngày đặt:</label>
              <span>{{ formatDate(order.created_at) }}</span>
            </div>
            <div class="info-item">
              <label>Tổng tiền:</label>
              <span class="price">{{ formatPrice(order.total_amount) }}</span>
            </div>
            <div class="info-item">
              <label>Phương thức thanh toán:</label>
              <span>{{ getPaymentMethodText(order.payment_method) }}</span>
            </div>
            <div class="info-item">
              <label>Trạng thái thanh toán:</label>
              <span :class="['status', order.payment_status]">{{
                getPaymentStatusText(order.payment_status)
              }}</span>
            </div>
          </div>
        </div>

        <!-- Thông tin khách hàng -->
        <div class="info-section">
          <h2>Thông tin khách hàng</h2>
          <div class="info-grid">
            <div class="info-item">
              <label>Tên khách hàng:</label>
              <span>{{ order.customer_name }}</span>
            </div>
            <div class="info-item">
              <label>Email:</label>
              <span>{{ order.customer_email }}</span>
            </div>
            <div class="info-item">
              <label>Số điện thoại:</label>
              <span>{{ order.customer_phone }}</span>
            </div>
          </div>
        </div>

        <!-- Thông tin vận chuyển -->
        <div class="info-section">
          <h2>Thông tin vận chuyển</h2>
          <div class="info-grid">
            <div class="info-item">
              <label>Địa chỉ:</label>
              <span>{{ order.shipping_address }}</span>
            </div>
            <div class="info-item">
              <label>Phương thức vận chuyển:</label>
              <span>{{ getShippingMethodText(order.shipping_method) }}</span>
            </div>
            <div class="info-item">
              <label>Phí vận chuyển:</label>
              <span class="price">{{ formatPrice(order.shipping_cost) }}</span>
            </div>
          </div>
        </div>

        <!-- Chi tiết sản phẩm trong đơn hàng -->
        <div class="info-section">
          <h2>Chi tiết sản phẩm</h2>
          <div class="products-table">
            <table>
              <thead>
                <tr>
                  <th>Sản phẩm</th>
                  <th>Đơn giá</th>
                  <th>Số lượng</th>
                  <th>Thành tiền</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in order.items" :key="item.id">
                  <td>
                    <div class="product-info">
                      <img
                        :src="item.image"
                        :alt="item.name"
                        class="product-image"
                      />
                      <span>{{ item.name }}</span>
                    </div>
                  </td>
                  <td>{{ formatPrice(item.unit_price) }}</td>
                  <td>{{ item.quantity }}</td>
                  <td class="price">
                    {{ formatPrice(item.unit_price * item.quantity) }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal chỉnh sửa đơn hàng -->
    <div v-if="showEditModal" class="modal">
      <div class="modal-content">
        <!-- Header của modal -->
        <div class="modal-header">
          <h2>Chỉnh sửa đơn hàng</h2>
          <button @click="showEditModal = false" class="close-btn">
            &times;
          </button>
        </div>
        <!-- Body của modal -->
        <div class="modal-body">
          <!-- Form chỉnh sửa trạng thái -->
          <div class="form-group">
            <label>Trạng thái đơn hàng:</label>
            <select v-model="order.status" class="form-control">
              <!-- Luôn hiển thị tất cả các trạng thái, nhưng chỉ cho phép chọn từ pending sang processing -->
              <option value="pending">Chờ xác nhận</option>
              <option value="processing" :disabled="order.status !== 'pending'">Đang xử lý</option>
              <option value="shipped" :disabled="order.status === 'pending'">Đang giao hàng</option>
              <option value="delivered" :disabled="order.status === 'pending'">Đã nhận hàng</option>
              <option value="cancelled" :disabled="order.status === 'pending'">Đã hủy</option>
            </select>
          </div>
          <div class="form-group">
            <label>Trạng thái thanh toán:</label>
            <select v-model="order.payment_status" class="form-control">
              <option value="pending">Chờ thanh toán</option>
              <option value="paid">Đã thanh toán</option>
              <option value="failed">Thanh toán thất bại</option>
            </select>
          </div>
        </div>
        <!-- Footer của modal -->
        <div class="modal-footer">
          <button @click="showEditModal = false" class="btn-cancel">Hủy</button>
          <button @click="saveChanges" class="btn-save">Lưu thay đổi</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// Import các thư viện và component cần thiết
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

// Cấu hình axios instance
const axiosInstance = axios.create({
  baseURL: process.env.VUE_APP_API_URL || 'http://localhost:8000/api/v1',
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
})

// Thêm interceptor để xử lý request
axiosInstance.interceptors.request.use(
  config => {
    const token = localStorage.getItem('admin_token')
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  },
  error => {
    return Promise.reject(error)
  }
)

// Thêm interceptor để xử lý response
axiosInstance.interceptors.response.use(
  response => response,
  error => {
    if (error.response?.status === 401) {
      localStorage.removeItem('admin_token')
      window.location.href = '/admin/login'
      return Promise.reject(error)
    }
    return Promise.reject(error)
  }
)

// Helper function để xử lý lỗi
const handleError = (error, defaultMessage = 'Có lỗi xảy ra') => {
  console.error(error)
  if (error.response?.data?.message) {
    throw new Error(error.response.data.message)
  }
  throw new Error(defaultMessage)
}

// Order Service
const orderService = {
  getById: async (id) => {
    try {
      const response = await axiosInstance.get(`/orders/${id}`)
      return response.data
    } catch (error) {
      handleError(error, 'Không thể tải thông tin đơn hàng')
    }
  },

  update: async (id, data) => {
    try {
      const response = await axiosInstance.put(`/orders/${id}`, data)
      return response.data
    } catch (error) {
      handleError(error, 'Không thể cập nhật đơn hàng')
    }
  }
}

// =====================
// Biến trạng thái reactive
// =====================
// Lấy thông tin route (lấy orderId từ URL)
const route = useRoute()
// Đối tượng router để điều hướng
const router = useRouter()
// Lấy orderId từ params
const orderId = route.params.id
// Đơn hàng hiện tại
const order = ref({})
// Trạng thái loading khi gọi API
const loading = ref(false)
// Biến lưu lỗi nếu có
const error = ref(null)
// Hiển thị modal chỉnh sửa
const showEditModal = ref(false)

// =====================
// Hàm điều hướng quay lại trang trước
// =====================
const goBack = () => {
  router.back();
};

// =====================
// Các hàm format dữ liệu hiển thị
// =====================
// Định dạng ngày theo chuẩn Việt Nam
const formatDate = (date) => {
  return new Date(date).toLocaleDateString('vi-VN');
};

// Định dạng giá tiền VND
const formatPrice = (price) => {
  return new Intl.NumberFormat('vi-VN', {
    style: 'currency',
    currency: 'VND',
  }).format(price);
};

// =====================
// Hàm mapping trạng thái sang tiếng Việt
// =====================
// Chuyển trạng thái đơn hàng sang tiếng Việt
const getStatusText = (status) => {
  const statusMap = {
    pending: 'Chờ xác nhận',
    processing: 'Đang xử lý',
    shipped: 'Đang giao hàng',
    delivered: 'Đã nhận hàng',
    cancelled: 'Đã hủy',
  };
  return statusMap[status] || status;
};

// Chuyển phương thức thanh toán sang tiếng Việt
const getPaymentMethodText = (method) => {
  const methodMap = {
    cod: 'Thanh toán khi nhận hàng',
    bank_transfer: 'Chuyển khoản ngân hàng',
    credit_card: 'Thẻ tín dụng',
  };
  return methodMap[method] || method;
};

// Chuyển trạng thái thanh toán sang tiếng Việt
const getPaymentStatusText = (status) => {
  const statusMap = {
    pending: 'Chờ thanh toán',
    paid: 'Đã thanh toán',
    failed: 'Thanh toán thất bại',
  };
  return statusMap[status] || status;
};

// Chuyển phương thức vận chuyển sang tiếng Việt
const getShippingMethodText = (method) => {
  const methodMap = {
    standard: 'Giao hàng tiêu chuẩn',
    express: 'Giao hàng nhanh',
  };
  return methodMap[method] || method;
};

// =====================
// Hàm lưu thay đổi đơn hàng
// =====================
const saveChanges = async () => {
  try {
    loading.value = true
    // Gọi API cập nhật đơn hàng
    const updatedOrder = await orderService.update(orderId, {
      status: order.value.status,
      payment_status: order.value.payment_status
    })
    if (updatedOrder) {
      order.value = { ...updatedOrder };
      showEditModal.value = false;
    }
  } catch (err) {
    error.value = err.message;
    console.error(err);
  } finally {
    loading.value = false;
  }
};

// =====================
// Lifecycle: Khi component mounted, gọi API lấy chi tiết đơn hàng
// =====================
onMounted(async () => {
  try {
    loading.value = true
    // Gọi API lấy chi tiết đơn hàng theo orderId
    const response = await axiosInstance.get(`/orders/${orderId}`)
    order.value = response.data
  } catch (err) {
    error.value = 'Không thể tải thông tin đơn hàng';
    console.error(err);
  } finally {
    loading.value = false;
  }
});
</script>

<style scoped>
/* Style cho trang chi tiết đơn hàng */
.admin-order-detail {
  padding: 24px;
  max-width: 1200px;
  margin: 0 auto;
}

/* Style cho loading spinner */
.loading {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 400px;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #ff813f;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

/* Style cho thông báo lỗi */
.error {
  color: #dc2626;
  text-align: center;
  padding: 24px;
}

/* Style cho header */
.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.actions {
  display: flex;
  gap: 12px;
}

/* Style cho các nút */
.btn-back,
.btn-edit {
  padding: 8px 16px;
  border-radius: 8px;
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: all 0.2s;
}

.btn-back {
  background: #f3f4f6;
  color: #374151;
}

.btn-back:hover {
  background: #e5e7eb;
}

.btn-edit {
  background: #ff813f;
  color: white;
}

.btn-edit:hover {
  background: #f97316;
}

/* Style cho các section thông tin */
.info-section {
  background: white;
  border-radius: 12px;
  padding: 24px;
  margin-bottom: 24px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.info-section h2 {
  font-size: 1.25rem;
  font-weight: 600;
  margin-bottom: 16px;
  color: #111827;
}

.info-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 16px;
}

.info-item {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.info-item label {
  color: #6b7280;
  font-size: 0.875rem;
}

.info-item span {
  font-weight: 500;
  color: #111827;
}

/* Style cho trạng thái */
.status {
  display: inline-block;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 0.875rem;
  font-weight: 500;
}

.status.pending {
  background: #fef3c7;
  color: #92400e;
}

.status.processing {
  background: #dbeafe;
  color: #1e40af;
}

.status.shipped {
  background: #dcfce7;
  color: #166534;
}

.status.delivered {
  background: #f3f4f6;
  color: #374151;
}

.status.cancelled {
  background: #fee2e2;
  color: #991b1b;
}

/* Style cho giá tiền */
.price {
  color: #ff813f;
  font-weight: 600;
}

/* Style cho bảng sản phẩm */
.products-table {
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th,
td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #e5e7eb;
}

th {
  background: #f9fafb;
  font-weight: 500;
  color: #6b7280;
}

.product-info {
  display: flex;
  align-items: center;
  gap: 12px;
}

.product-image {
  width: 48px;
  height: 48px;
  object-fit: cover;
  border-radius: 4px;
}

/* Style cho modal */
.modal {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 50;
}

.modal-content {
  background: white;
  border-radius: 12px;
  width: 100%;
  max-width: 500px;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-header {
  padding: 16px 24px;
  border-bottom: 1px solid #e5e7eb;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.modal-header h2 {
  font-size: 1.25rem;
  font-weight: 600;
  color: #111827;
}

.close-btn {
  font-size: 1.5rem;
  color: #6b7280;
  cursor: pointer;
}

.modal-body {
  padding: 24px;
}

.form-group {
  margin-bottom: 16px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  color: #374151;
  font-weight: 500;
}

.form-control {
  width: 100%;
  padding: 8px 12px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 0.875rem;
}

.modal-footer {
  padding: 16px 24px;
  border-top: 1px solid #e5e7eb;
  display: flex;
  justify-content: flex-end;
  gap: 12px;
}

.btn-cancel,
.btn-save {
  padding: 8px 16px;
  border-radius: 6px;
  font-weight: 500;
  transition: all 0.2s;
}

.btn-cancel {
  background: #f3f4f6;
  color: #374151;
}

.btn-cancel:hover {
  background: #e5e7eb;
}

.btn-save {
  background: #ff813f;
  color: white;
}

.btn-save:hover {
  background: #f97316;
}
</style>
