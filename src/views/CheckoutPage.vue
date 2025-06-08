<template>
  <div class="bg-white rounded-2xl p-8 max-w-7xl mx-auto my-8">
    <!-- Breadcrumb điều hướng -->
    <div class="mb-4">
      <router-link to="/" class="text-gray-600 hover:text-orange-500">Trang chủ</router-link>
      &gt;
      <router-link to="/cart" class="text-gray-600 hover:text-orange-500">Giỏ hàng</router-link>
      &gt;
      <span class="text-gray-800">Thanh toán</span>
    </div>
    <h1 class="text-2xl font-bold mb-6">Thanh toán</h1>

    <!-- Thanh tiến trình các bước mua hàng -->
    <div class="flex items-center justify-center my-8">
      <!-- Bước 1: Vận chuyển -->
      <div class="flex flex-col items-center text-green-500">
        <div
          class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center mb-2 font-bold"
        >
          <i class="fas fa-check"></i>
        </div>
        <div>Vận chuyển</div>
      </div>
      <div class="w-24 h-0.5 bg-orange-500 mx-4"></div>
      <!-- Bước 2: Thanh toán -->
      <div class="flex flex-col items-center text-orange-500">
        <div
          class="w-8 h-8 rounded-full bg-orange-500 text-white flex items-center justify-center mb-2 font-bold"
        >
          2
        </div>
        <div>Kiểm tra & Thanh toán</div>
      </div>
      <div class="w-24 h-0.5 bg-gray-200 mx-4"></div>
      <!-- Bước 3: Thành công -->
      <div class="flex flex-col items-center text-gray-400">
        <div
          class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mb-2 font-bold"
        >
          3
        </div>
        <div>Thành công</div>
      </div>
    </div>

    <!-- Loading state -->
    <div v-if="loading" class="text-center py-16">
      <div
        class="w-16 h-16 border-4 border-gray-200 border-t-orange-500 rounded-full mx-auto animate-spin"
      ></div>
      <p class="mt-4 text-gray-600">Đang tải thông tin thanh toán...</p>
    </div>

    <!-- Error state -->
    <div v-else-if="error" class="text-center py-16 text-red-500">
      <i class="fas fa-exclamation-circle text-5xl mb-4"></i>
      <p>{{ error }}</p>
      <button @click="goBackToCart" class="mt-4 bg-orange-500 text-white px-6 py-2 rounded">
        Quay lại giỏ hàng
      </button>
    </div>

    <!-- Content -->
    <div v-else class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-8">
      <!-- Checkout information -->
      <div>
        <!-- Shipping address -->
        <div class="bg-gray-50 p-6 rounded-xl mb-6">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Địa chỉ giao hàng</h3>
            <button @click="goBackToCart" class="text-orange-500 font-medium">Thay đổi</button>
          </div>
          <div class="bg-white p-4 rounded-lg">
            <p class="font-medium">{{ checkoutData.shippingAddress.name }}</p>
            <p class="text-gray-600">{{ checkoutData.shippingAddress.phone }}</p>
            <p class="text-gray-600">{{ checkoutData.shippingAddress.address_line }}</p>
            <span
              v-if="checkoutData.shippingAddress.is_default"
              class="inline-block mt-2 border border-orange-500 text-orange-500 rounded px-2 py-1 text-xs"
            >
              Mặc định
            </span>
          </div>
        </div>

        <!-- Payment method -->
        <div class="bg-gray-50 p-6 rounded-xl mb-6">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Phương thức thanh toán</h3>
            <button @click="goBackToCart" class="text-orange-500 font-medium">Thay đổi</button>
          </div>
          <div class="bg-white p-4 rounded-lg">
            <div class="flex items-center gap-3">
              <i class="fas fa-money-bill-wave text-green-500 text-xl"></i>
              <div>
                <p class="font-medium">{{ paymentMethodLabel }}</p>
                <p class="text-gray-600 text-sm">Thanh toán khi nhận được hàng</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Order items -->
        <div class="bg-gray-50 p-6 rounded-xl">
          <h3 class="text-lg font-semibold mb-4">Đơn hàng của bạn</h3>
          <div class="space-y-4">
            <div
              v-for="item in checkoutData.cartItems"
              :key="item.cart_item_id"
              class="bg-white p-4 rounded-lg flex gap-4"
            >
              <img
                :src="item.product.image_url || 'https://via.placeholder.com/100'"
                :alt="item.product.product_name"
                class="w-20 h-20 object-cover rounded-lg"
              />
              <div class="flex-1">
                <h4 class="font-semibold">{{ item.product.product_name }}</h4>
                <p class="text-gray-600">Số lượng: {{ item.quantity }}</p>
                <p class="font-medium text-orange-500">{{ formatPrice(getItemTotal(item)) }}đ</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Order summary -->
      <div class="bg-orange-50 rounded-xl p-6 h-fit">
        <h2 class="text-xl font-bold mb-4">Tổng đơn hàng</h2>
        <div class="flex justify-between text-gray-600 mb-3">
          <span>Tạm tính:</span>
          <span class="font-semibold">{{ formatPrice(checkoutData.subtotal) }}đ</span>
        </div>
        <div class="flex justify-between text-gray-600 mb-3">
          <span>Vận chuyển:</span>
          <span class="font-semibold">{{ formatPrice(checkoutData.shipping) }}đ</span>
        </div>
        <div class="flex justify-between mt-4 pt-4 border-t border-gray-300 mb-6">
          <span>Tổng cộng:</span>
          <span class="font-bold text-xl text-orange-500"
            >{{ formatPrice(checkoutData.total) }}đ</span
          >
        </div>

        <!-- Place order button -->
        <button
          @click="placeOrder"
          class="w-full bg-orange-500 text-white py-3 rounded-md font-semibold hover:bg-orange-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
          :disabled="isPlacingOrder"
        >
          <span v-if="isPlacingOrder">
            <i class="fas fa-spinner fa-spin mr-2"></i> Đang xử lý...
          </span>
          <span v-else>Đặt hàng</span>
        </button>

        <!-- Checkout info -->
        <div class="mt-6 text-sm text-gray-600">
          <p class="flex items-center gap-2">
            <i class="fas fa-shield-alt text-orange-500"></i>
            Thông tin thanh toán của bạn được bảo mật.
          </p>
          <p class="flex items-center gap-2 mt-2">
            <i class="fas fa-truck text-orange-500"></i>
            Đơn hàng sẽ được giao trong 3-5 ngày làm việc.
          </p>
          <p class="flex items-center gap-2 mt-2">
            <i class="fas fa-exchange-alt text-orange-500"></i>
            Dễ dàng đổi trả trong vòng 7 ngày.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { useCart } from '@/store/cart'

const router = useRouter()
const cart = useCart()

// State variables
const checkoutData = ref({
  cartItems: [],
  shippingAddress: {},
  subtotal: 0,
  shipping: 0,
  total: 0,
  paymentMethod: 'COD'
})
const loading = ref(true)
const error = ref(null)
const isPlacingOrder = ref(false)

// Computed properties
const paymentMethodLabel = computed(() => {
  const methods = {
    COD: 'Thanh toán khi nhận hàng (COD)'
  }
  return methods[checkoutData.value.paymentMethod] || checkoutData.value.paymentMethod
})

// Format price helper function
const formatPrice = price => {
  // Make sure price is a number and not undefined
  const safePrice = Number(price) || 0
  return safePrice.toLocaleString('vi-VN')
}

// Get total for a specific item
const getItemTotal = item => {
  if (!item || !item.product) return 0
  const price = Number(item.product.base_price) || 0
  const quantity = Number(item.quantity) || 1
  return price * quantity
}

// Load checkout data from localStorage
onMounted(() => {
  try {
    const savedData = localStorage.getItem('checkout_data')
    if (!savedData) {
      error.value = 'Không tìm thấy thông tin thanh toán'
      loading.value = false
      return
    }

    const parsedData = JSON.parse(savedData)
    if (!parsedData.cartItems || parsedData.cartItems.length === 0) {
      error.value = 'Giỏ hàng trống'
      loading.value = false
      return
    }

    checkoutData.value = parsedData
    loading.value = false
  } catch (err) {
    console.error('Error loading checkout data:', err)
    error.value = 'Không thể tải thông tin thanh toán'
    loading.value = false
  }
})

// Navigate back to cart page
function goBackToCart() {
  router.push('/cart')
}

// Place order
async function placeOrder() {
  if (isPlacingOrder.value) return

  try {
    isPlacingOrder.value = true

    // Format cart items for API
    const items = checkoutData.value.cartItems.map(item => ({
      product_id: item.product_id,
      quantity: item.quantity,
      unit_price: item.product.base_price
    }))

    // Prepare order data
    const orderData = {
      address_id: checkoutData.value.shippingAddress.address_id,
      payment_method: checkoutData.value.paymentMethod,
      payment_status: 'unpaid',
      total_amount: checkoutData.value.total,
      cart_items: items
    }

    // Call checkout API
    const response = await axios.post('http://localhost:8000/api/v1/orders/cart', orderData, {
      withCredentials: true
    })

    if (response.data && response.data.order_id) {
      // Clear checkout data from localStorage
      localStorage.removeItem('checkout_data')

      // Clear cart after successful order
      await cart.clearCart()

      // Navigate to success page with order ID
      router.push({
        path: '/order-success',
        query: { order_id: response.data.order_id }
      })
    } else {
      throw new Error(response.data?.message || 'Không thể hoàn tất đơn hàng')
    }
  } catch (error) {
    console.error('Error processing order:', error)
    alert(
      'Không thể hoàn tất đơn hàng: ' +
        (error.response?.data?.message || error.message || 'Lỗi không xác định')
    )
  } finally {
    isPlacingOrder.value = false
  }
}
</script>

<style scoped>
.animate-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
</style>
