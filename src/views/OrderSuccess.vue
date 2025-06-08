<template>
  <!-- Trang thông báo đặt hàng thành công -->
  <div class="order-success">
    <!-- Breadcrumb điều hướng các bước -->
    <div class="breadcrumb">
      <router-link to="/" class="text-gray-600 hover:text-orange-500">Trang chủ</router-link> &gt;
      <router-link to="/cart" class="text-gray-600 hover:text-orange-500">Giỏ hàng</router-link>
      &gt;
      <router-link to="/shipping" class="text-gray-600 hover:text-orange-500"
        >Vận chuyển</router-link
      >
      &gt;
      <router-link to="/checkout" class="text-gray-600 hover:text-orange-500"
        >Thanh toán</router-link
      >
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

      <!-- Các nút thao tác sau khi đặt hàng -->
      <div class="order-actions">
        <router-link to="/" class="home-btn">
          <i class="fas fa-home mr-2"></i>
          Về trang chủ
        </router-link>
        <router-link to="/account" class="order-btn">
          <i class="fas fa-list-alt mr-2"></i>
          Xem tài khoản người dùng
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
// Không cần import và xử lý API nữa
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

  .order-actions {
    flex-direction: column;
  }

  .support-options {
    flex-wrap: wrap;
  }
}
</style>
