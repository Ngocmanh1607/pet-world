<template>
  <!-- Trang thống kê tổng quan cho admin -->
  <div class="admin-statistics min-h-screen bg-[#fafbfc] py-8">
    <!-- Container căn giữa, giới hạn chiều rộng -->
    <div class="max-w-7xl mx-auto px-4">
      <!-- Tiêu đề trang, căn giữa, font lớn -->
      <h2 class="text-center text-3xl font-bold mt-8 mb-8 text-[#5a3a1b] font-quicksand tracking-wide">
        Thống kê tình hình kinh doanh
      </h2>
      <!-- Hiển thị loading khi đang lấy dữ liệu -->
      <div v-if="loading" class="flex justify-center items-center min-h-[200px]">
        <span class="animate-spin rounded-full h-12 w-12 border-t-4 border-orange-400 border-solid"></span>
      </div>
      <!-- Grid 5 card thống kê, responsive -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-8 justify-items-center">
        <!-- Card: Tổng số đơn hàng -->
        <div class="stat-card aspect-square h-60">
          <div class="stat-title">TỔNG SỐ ĐƠN HÀNG</div>
          <div class="stat-icon">🛒</div>
          <div class="stat-value">{{ totalOrders }}</div>
        </div>
        <!-- Card: Tổng số sản phẩm -->
        <div class="stat-card aspect-square h-60">
          <div class="stat-title">TỔNG SỐ SẢN PHẨM</div>
          <div class="stat-icon">📦</div>
          <div class="stat-value">{{ totalProducts }}</div>
        </div>
        <!-- Card: Tổng số khách hàng -->
        <div class="stat-card aspect-square h-60">
          <div class="stat-title">TỔNG SỐ KHÁCH HÀNG</div>
          <div class="stat-icon">🛍️</div>
          <div class="stat-value">{{ totalCustomers }}</div>
        </div>
        <!-- Card: Tổng số mã giảm giá -->
        <div class="stat-card aspect-square h-60">
          <div class="stat-title">TỔNG SỐ MÃ GIẢM GIÁ</div>
          <div class="stat-icon">🏷️</div>
          <div class="stat-value">{{ activePromotions }}</div>
        </div>
        <!-- Card: Doanh thu trong tháng -->
        <div class="stat-card aspect-square h-60">
          <div class="stat-title">DOANH THU TRONG THÁNG</div>
          <div class="stat-icon">💰</div>
          <div class="stat-value">{{ totalRevenue.toLocaleString() }}đ</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// Import các hàm reactive và lifecycle từ Vue
import { ref, onMounted } from 'vue'
// Import axios để gọi API
import axios from 'axios'

// Các biến reactive lưu số liệu thống kê
const totalOrders = ref(0) // Tổng số đơn hàng
const totalProducts = ref(0) // Tổng số sản phẩm
const totalCustomers = ref(0) // Tổng số khách hàng
const activePromotions = ref(0) // Tổng số mã giảm giá đang hoạt động
const totalRevenue = ref(0) // Doanh thu trong tháng
const loading = ref(true) // Trạng thái loading khi lấy dữ liệu
const error = ref(null) // Biến lưu lỗi nếu có

// Khi component mounted, gọi API lấy số liệu tổng hợp
onMounted(async () => {
  try {
    loading.value = true
    // Gọi API tổng hợp
    const res = await axios.get('http://localhost:8000/api/v1/orders/statistics/all', {
      withCredentials: true // Include cookies if needed for authentication
    })
    const stats = res.data
    // Gán số liệu vào biến reactive, có fallback cho các kiểu tên trường khác nhau
    totalOrders.value = stats.total_orders || stats.totalOrders || 0
    totalProducts.value = stats.total_products || stats.totalProducts || 0
    totalCustomers.value = stats.total_customers || stats.totalCustomers || 0
    activePromotions.value = stats.active_promotions || stats.activePromotions || 0
    totalRevenue.value = stats.revenue_this_month || stats.revenueThisMonth || 0
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false;
  }
});
</script>

<style scoped>
/* Style cho trang thống kê tổng quan */
.admin-statistics {
  min-height: 100vh;
  background: #fafbfc;
  padding-bottom: 40px;
}
/* Card thống kê: bo góc, bóng, border cam nhạt, hover nổi, vuông đều */
.stat-card {
  @apply bg-white rounded-2xl shadow-lg border border-orange-100 flex flex-col items-center justify-center transition-transform duration-200 hover:-translate-y-1 hover:shadow-2xl;
  height: 15rem;
  min-width: 0;
}
/* Icon lớn, nền cam nhạt, bo tròn */
.stat-icon {
  @apply flex items-center justify-center w-16 h-16 rounded-full bg-orange-50 mb-4 text-4xl;
}
/* Tiêu đề card: cam, in hoa, nhỏ, đậm, tracking rộng */
.stat-title {
  @apply text-orange-500 font-bold text-xs mb-2 uppercase tracking-widest;
}
/* Số liệu: rất to, đậm, xám đậm, căn giữa */
.stat-value {
  @apply text-5xl font-extrabold text-gray-900 mt-2;
  letter-spacing: 1px;
}
</style>
