<template>
  <!-- Trang thông báo đặt hàng thành công -->
  <div class="order-success">
    <!-- Breadcrumb điều hướng các bước -->
    <div class="breadcrumb">
      <router-link to="/" class="text-gray-600 hover:text-orange-500">Trang chủ</router-link> &gt;
      <router-link to="/cart" class="text-gray-600 hover:text-orange-500">Giỏ hàng</router-link>
      &gt;
      <router-link to="/shipping" class="text-gray-600 hover:text-orange-500">Vận chuyển</router-link>
      &gt;
      <router-link to="/checkout" class="text-gray-600 hover:text-orange-500">Thanh toán</router-link>
      &gt;
      <span class="text-gray-800">Thành công</span>
    </div>

    <div class="success-content">
      <!-- Icon và tiêu đề thành công -->
      <div class="success-header">
        <div class="success-icon">
          <i class="fas fa-check-circle"></i>
        </div>
        <h1 class="text-2xl font-bold mb-2">Đặt hàng thành công!</h1>
        <p class="text-gray-600">Cảm ơn bạn đã đặt hàng tại Pet Shop!</p>
      </div>

      <!-- Thông tin đơn hàng -->
      <div class="order-info">
        <div class="info-card">
          <h3 class="text-lg font-semibold mb-4">Thông tin đơn hàng</h3>
          <div class="info-row">
            <span class="label">Mã đơn hàng:</span>
            <span class="value">{{ orderId }}</span>
          </div>
          <div class="info-row">
            <span class="label">Ngày đặt:</span>
            <span class="value">{{ orderDate }}</span>
          </div>
          <div class="info-row">
            <span class="label">Phương thức thanh toán:</span>
            <span class="value">{{ paymentMethod }}</span>
          </div>
          <div class="info-row">
            <span class="label">Tổng tiền:</span>
            <span class="value text-orange-500 font-bold"
              >{{ total.toLocaleString() }}đ</span
            >
          </div>
        </div>

        <div class="info-card">
          <h3 class="text-lg font-semibold mb-4">Thông tin vận chuyển</h3>
          <div class="info-row">
            <span class="label">Người nhận:</span>
            <span class="value">{{ shippingInfo.name }}</span>
          </div>
          <div class="info-row">
            <span class="label">Số điện thoại:</span>
            <span class="value">{{ shippingInfo.phone }}</span>
          </div>
          <div class="info-row">
            <span class="label">Địa chỉ:</span>
            <span class="value">{{ shippingInfo.address }}</span>
          </div>
          <div class="info-row">
            <span class="label">Phương thức vận chuyển:</span>
            <span class="value">Giao hàng tiêu chuẩn</span>
          </div>
        </div>
      </div>

      <!-- Các nút thao tác sau khi đặt hàng -->
      <div class="order-actions">
        <router-link to="/" class="home-btn">
          <i class="fas fa-home mr-2"></i>
          Về trang chủ
        </router-link>
        <router-link to="/orders" class="order-btn">
          <i class="fas fa-list-alt mr-2"></i>
          Xem đơn hàng
        </router-link>
      </div>

      <!-- Thông tin hỗ trợ khách hàng -->
      <div class="support-info">
        <p class="text-gray-600 mb-2">Bạn cần hỗ trợ?</p>
        <div class="support-options">
          <a href="tel:+84123456789" class="support-option">
            <i class="fas fa-phone-alt"></i>
            <span>Gọi ngay</span>
          </a>
          <a href="mailto:support@petshop.com" class="support-option">
            <i class="fas fa-envelope"></i>
            <span>Email</span>
          </a>
          <a href="#" class="support-option">
            <i class="fas fa-comments"></i>
            <span>Chat</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// Import các thư viện cần thiết
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRoute } from 'vue-router'

// Lấy orderId từ route params
const route = useRoute()
const orderId = route.params.orderId
const order = ref({})

// Các biến lưu thông tin đơn hàng
const orderDate = ref('') // Ngày đặt hàng
const paymentMethod = ref('') // Phương thức thanh toán
const total = ref(0) // Tổng tiền
const shippingInfo = ref({ // Thông tin vận chuyển
  name: '',
  phone: '',
  address: ''
})

// Hàm lấy chi tiết đơn hàng từ API
async function fetchOrder() {
  if (orderId) {
    try {
      const res = await axios.get(`/orders/${orderId}`)
      order.value = res.data
      // Gán dữ liệu vào các biến hiển thị
      orderId.value = order.value.id
      orderDate.value = new Date(order.value.created_at).toLocaleDateString('vi-VN')
      paymentMethod.value = getPaymentMethodText(order.value.payment_method)
      total.value = order.value.total_amount
      shippingInfo.value = {
        name: order.value.shipping_info.address.name,
        phone: order.value.shipping_info.address.phone,
        address: order.value.shipping_info.address.address_line
      }
    } catch (error) {
      console.error('Error fetching order:', error);
    }
  }
}

// Hàm chuyển phương thức thanh toán sang tiếng Việt
const getPaymentMethodText = (method) => {
  const methodMap = {
    cod: 'Thanh toán khi nhận hàng',
    bank_transfer: 'Chuyển khoản ngân hàng',
    credit_card: 'Thẻ tín dụng',
  };
  return methodMap[method] || method;
};

// Khi component mounted, gọi API lấy chi tiết đơn hàng
onMounted(fetchOrder)
</script>

<style scoped>
/* Style cho trang thông báo đặt hàng thành công */
.order-success {
  background: #fff;
  border-radius: 16px;
  padding: 32px;
  max-width: 800px;
  margin: 32px auto;
}

.breadcrumb {
  margin-bottom: 32px;
}

.success-content {
  text-align: center;
}

.success-header {
  margin-bottom: 48px;
}

.success-icon {
  font-size: 64px;
  color: #22c55e;
  margin-bottom: 24px;
}

.order-info {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 24px;
  margin-bottom: 48px;
}

.info-card {
  background: #fff7f0;
  border-radius: 12px;
  padding: 24px;
  text-align: left;
}

.info-row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 12px;
  color: #666;
}

.info-row:last-child {
  margin-bottom: 0;
}

.label {
  font-weight: 500;
}

.value {
  font-weight: 600;
}

.order-actions {
  display: flex;
  gap: 16px;
  justify-content: center;
  margin-bottom: 48px;
}

.home-btn,
.order-btn {
  padding: 12px 24px;
  border-radius: 8px;
  font-weight: 500;
  text-decoration: none;
  display: flex;
  align-items: center;
  transition: all 0.2s;
}

.home-btn {
  background: #f90;
  color: white;
}

.home-btn:hover {
  background: #f80;
  transform: translateY(-2px);
}

.order-btn {
  background: #333;
  color: white;
}

.order-btn:hover {
  background: #444;
  transform: translateY(-2px);
}

.support-info {
  border-top: 1px solid #eee;
  padding-top: 32px;
}

.support-options {
  display: flex;
  gap: 24px;
  justify-content: center;
  margin-top: 16px;
}

.support-option {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
  color: #666;
  text-decoration: none;
  transition: all 0.2s;
}

.support-option:hover {
  color: #f90;
  transform: translateY(-2px);
}

.support-option i {
  font-size: 24px;
}

@media (max-width: 768px) {
  .order-success {
    padding: 16px;
    margin: 16px;
  }

  .order-info {
    grid-template-columns: 1fr;
  }

  .order-actions {
    flex-direction: column;
  }

  .support-options {
    flex-wrap: wrap;
  }
}
</style>
