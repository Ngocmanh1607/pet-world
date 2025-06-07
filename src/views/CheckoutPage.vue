<template>
  <!-- Trang thanh toán: Kiểm tra lại đơn hàng, chọn phương thức thanh toán, nhập mã giảm giá, xác nhận đặt hàng -->
  <div v-if="!loading" class="bg-white rounded-2xl p-4 md:p-8 max-w-7xl mx-auto my-4 md:my-8">
    <!-- Breadcrumb điều hướng -->
    <div class="mb-4 text-sm md:text-base">
      <router-link to="/" class="text-gray-600 hover:text-orange-500"
        >Trang chủ</router-link
      >
      &gt;
      <router-link to="/cart" class="text-gray-600 hover:text-orange-500"
        >Giỏ hàng</router-link
      >
      &gt;
      <router-link to="/shipping" class="text-gray-600 hover:text-orange-500"
        >Vận chuyển</router-link
      >
      &gt;
      <span class="text-gray-800">Thanh toán</span>
    </div>

    <h1 class="text-xl md:text-2xl font-bold mb-6">Thanh toán</h1>

    <!-- Địa chỉ nhận hàng -->
    <div class="flex flex-col items-center my-4">
      <h3
        class="flex items-center gap-2 text-orange-500 font-semibold text-lg mb-2"
      >
        <i class="fas fa-map-marker-alt"></i> Địa chỉ nhận hàng
      </h3>
      <div>
        <span class="font-semibold">{{ shippingAddress.name }}</span>
        <span class="mx-2">|</span>
        <span>{{ shippingAddress.phone }}</span>
        <span class="mx-2">|</span>
        <span>{{ shippingAddress.address_line }}</span>
        <span
          v-if="shippingAddress.is_default"
          class="border border-orange-500 text-orange-500 rounded px-2 py-1 ml-2 text-xs"
          >Mặc định</span
        >
      </div>
    </div>

    <!-- Thanh tiến trình các bước -->
    <div class="flex items-center justify-center my-6 md:my-8">
      <div class="flex flex-col items-center text-gray-400">
        <div
          class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mb-2 font-bold"
        >
          1
        </div>
        <div class="text-sm md:text-base">Vận chuyển</div>
      </div>
      <div class="w-16 md:w-24 h-0.5 bg-gray-200 mx-2 md:mx-4"></div>
      <div class="flex flex-col items-center text-orange-500">
        <div
          class="w-8 h-8 rounded-full bg-orange-500 text-white flex items-center justify-center mb-2 font-bold"
        >
          2
        </div>
        <div class="text-sm md:text-base">Kiểm tra & Thanh toán</div>
      </div>
      <div class="w-16 md:w-24 h-0.5 bg-gray-200 mx-2 md:mx-4"></div>
      <div class="flex flex-col items-center text-gray-400">
        <div
          class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mb-2 font-bold"
        >
          3
        </div>
        <div class="text-sm md:text-base">Thành công</div>
      </div>
    </div>

    <!-- Nội dung chính: chọn phương thức thanh toán, nhập mã giảm giá, xác nhận -->
    <div v-if="cartItems.length > 0" class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-4 md:gap-8">
      <div>
        <!-- Chọn phương thức thanh toán -->
        <div class="bg-orange-50 rounded-xl p-4 md:p-6 mb-4 md:mb-6">
          <h3 class="text-lg md:text-xl font-semibold mb-4">
            Phương thức thanh toán
          </h3>
          <div class="grid gap-3">
            <label
              v-for="method in paymentMethods"
              :key="method.value"
              class="flex items-center gap-3 p-3 md:p-4 bg-white rounded-lg cursor-pointer transition-colors hover:bg-orange-50"
            >
              <input
                type="radio"
                v-model="paymentMethod"
                :value="method.value"
                class="w-5 h-5 accent-orange-500"
              />
              <div class="flex items-center gap-3 md:gap-4 flex-1">
                <div
                  class="w-10 h-10 bg-orange-50 rounded-lg flex items-center justify-center text-orange-500 text-xl"
                >
                  <i :class="method.icon"></i>
                </div>
                <div class="flex-1">
                  <div class="font-medium mb-1 text-base md:text-lg">
                    {{ method.name }}
                  </div>
                  <div class="text-gray-600 text-sm">
                    {{ method.description }}
                  </div>
                </div>
              </div>
            </label>
          </div>
        </div>

        <!-- Nhập mã giảm giá -->
        <div class="bg-orange-50 rounded-xl p-4 md:p-6 mb-4 md:mb-6">
          <h3 class="text-lg md:text-xl font-semibold mb-4">Mã giảm giá</h3>
          <div class="flex gap-3">
            <div class="flex-1 relative">
              <input
                type="text"
                v-model="discountCode"
                placeholder="Nhập mã giảm giá"
                class="w-full px-3 md:px-4 py-2 md:py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-orange-500"
                :class="{ 'border-red-500': discountError }"
              />
              <div
                v-if="discountError"
                class="absolute -bottom-6 left-0 text-red-500 text-sm"
              >
                {{ discountError }}
              </div>
            </div>
            <button
              @click="applyDiscount"
              class="px-4 md:px-6 py-2 md:py-3 bg-orange-500 text-white rounded-lg transition-colors hover:bg-orange-600 disabled:opacity-50 disabled:cursor-not-allowed"
              :disabled="!discountCode"
            >
              Áp dụng
            </button>
          </div>
          <div
            v-if="discount"
            class="mt-4 md:mt-6 p-3 bg-white rounded-lg flex justify-between items-center"
          >
            <div class="flex items-center gap-3">
              <span
                class="bg-orange-50 text-orange-500 px-2 py-1 rounded font-medium"
                >{{ discountCode }}</span
              >
              <span class="text-green-600 font-semibold"
                >-{{ formatPrice(discount) }}</span
              >
            </div>
            <button
              @click="removeDiscount"
              class="text-gray-600 hover:text-red-500 p-1 transition-colors"
            >
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>

        <!-- Nút hoàn tất thanh toán -->
        <button 
          @click="goSuccess" 
          :disabled="!isValid"
          class="w-full bg-orange-500 text-white rounded-lg py-3 md:py-4 text-base md:text-lg flex items-center justify-center transition-all hover:bg-orange-600 hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          Hoàn tất thanh toán
          <i class="fas fa-arrow-right ml-2"></i>
        </button>
      </div>

      <!-- Bảng tổng kết đơn hàng -->
      <div class="bg-orange-50 rounded-xl p-4 md:p-6 h-fit">
        <h3 class="text-lg md:text-xl font-semibold mb-4">Đơn hàng của bạn</h3>
        <div class="space-y-3 mb-4 md:mb-6">
          <div
            v-for="item in cartItems"
            :key="item.id"
            class="flex items-center gap-3 py-3 border-b border-gray-200"
          >
            <img
              :src="item.image"
              :alt="item.name"
              class="w-12 h-12 md:w-15 md:h-15 object-cover rounded-lg"
            />
            <div class="flex-1">
              <div class="font-medium mb-1 text-sm md:text-base">
                {{ item.name }}
              </div>
              <div class="text-gray-600 text-xs md:text-sm">
                Số lượng: {{ item.quantity }}
              </div>
            </div>
            <div class="font-semibold text-orange-500 text-sm md:text-base">
              {{ formatPrice(item.price * item.quantity) }}
            </div>
          </div>
        </div>

        <div class="space-y-3">
          <div class="flex justify-between text-gray-600 text-sm md:text-base">
            <span>Tạm tính:</span>
            <span class="font-semibold">{{ formatPrice(subtotal) }}</span>
          </div>
          <div class="flex justify-between text-gray-600 text-sm md:text-base">
            <span>Vận chuyển:</span>
            <span class="font-semibold">{{ formatPrice(shippingCost) }}</span>
          </div>
          <div
            v-if="discount"
            class="flex justify-between text-green-600 text-sm md:text-base"
          >
            <span>Giảm giá:</span>
            <span class="font-semibold">-{{ formatPrice(discount) }}</span>
          </div>
          <div class="flex justify-between mt-4 pt-4 border-t border-gray-300">
            <span class="text-base md:text-lg">Tổng cộng:</span>
            <span class="font-bold text-lg md:text-xl text-orange-500">{{
              formatPrice(total)
            }}</span>
          </div>
        </div>
      </div>
    </div>
    <!-- Nếu không có sản phẩm trong giỏ -->
    <div v-else class="text-center py-8">
      <p class="text-gray-600">Không có sản phẩm trong giỏ hàng</p>
      <router-link
        to="/cart"
        class="text-orange-500 hover:text-orange-600 mt-4 inline-block"
      >
        Quay lại giỏ hàng
      </router-link>
    </div>
  </div>
  <!-- Loading khi đang xử lý -->
  <div v-else class="flex items-center justify-center min-h-screen">
    <div
      class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-orange-500"
    ></div>
  </div>
</template>

<script setup>
// Import các thư viện cần thiết
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()

// Danh sách phương thức thanh toán
const paymentMethods = [
  {
    value: 'cod',
    name: 'Thanh toán khi nhận hàng',
    description: 'Thanh toán bằng tiền mặt khi nhận hàng',
    icon: 'fas fa-money-bill-wave',
  },
  {
    value: 'card',
    name: 'Thẻ ngân hàng',
    description: 'Thanh toán qua thẻ ATM/Visa/Mastercard',
    icon: 'fas fa-credit-card',
  },
  {
    value: 'ewallet',
    name: 'Ví điện tử',
    description: 'Thanh toán qua MoMo, ZaloPay, VNPay',
    icon: 'fas fa-wallet',
  },
];

// State lưu trữ dữ liệu
const cartItems = ref([]) // Danh sách sản phẩm trong giỏ hàng
const paymentMethod = ref('cod') // Phương thức thanh toán được chọn
const discountCode = ref('') // Mã giảm giá nhập vào
const discountError = ref('') // Lỗi mã giảm giá
const discount = ref(0) // Số tiền giảm giá
const loading = ref(true) // Trạng thái loading
const appliedPromotion = ref(null) // Thông tin khuyến mãi áp dụng
const shippingMethod = ref('standard') // Chỉ còn phương thức 'standard'
const shippingCost = ref(30000) // Phí vận chuyển
const shippingAddress = ref({}) // Địa chỉ giao hàng

// Tính tổng tiền hàng
const subtotal = computed(() => {
  return cartItems.value.reduce(
    (total, item) => total + item.price * item.quantity,
    0
  );
});

// Tính tổng tiền phải trả
const total = computed(() => subtotal.value + shippingCost.value - discount.value)

// Kiểm tra hợp lệ trước khi đặt hàng
const isValid = computed(() => {
  return (
    paymentMethod.value && cartItems.value.length > 0 && shippingAddress.value
  );
});

// Định dạng giá tiền VND
const formatPrice = (price) => {
  return new Intl.NumberFormat('vi-VN', {
    style: 'currency',
    currency: 'VND'
  }).format(price)
}

// Hàm kiểm tra và áp dụng mã giảm giá
const applyDiscount = async () => {
  if (!discountCode.value) return
  try {
    const res = await axios.get(`/promotions/code/${discountCode.value}`)
    discount.value = subtotal.value * (res.data.discount_percent / 100)
    discountError.value = ''
  } catch (e) {
    discountError.value = 'Mã giảm giá không hợp lệ'
    discount.value = 0
  }
};

// Hàm xóa mã giảm giá
const removeDiscount = () => {
  discountCode.value = ''
  discount.value = 0
  appliedPromotion.value = null
  discountError.value = ''
}

// Hàm hoàn tất đặt hàng
const goSuccess = async () => {
  if (!isValid.value) return;

  try {
    loading.value = true
    const customerId = localStorage.getItem('user_id')
    const orderData = {
      customer_id: customerId,
      payment_method: paymentMethod.value,
      payment_status: 'pending',
      promotion_id: appliedPromotion.value?.promotion_id,
      total_amount: total.value,
      shipping_info: {
        address: shippingAddress.value,
        method: shippingMethod.value,
        cost: shippingCost.value,
      },
      items: cartItems.value.map((item) => ({
        product_id: item.id,
        quantity: item.quantity,
        unit_price: item.price,
      })),
    };

    // Gọi API tạo đơn hàng từ giỏ hàng
    const res = await axios.post('/orders/cart', orderData)
    // Xóa giỏ hàng sau khi đặt hàng thành công
    await axios.delete(`/api/cart/${customerId}`)
    // Chuyển đến trang thành công
    router.push(`/order-success/${res.data.order_id}`)
  } catch (error) {
    console.error(
      'Error creating order:',
      error.response?.data || error.message
    );
    alert(error.response?.data?.message || 'Có lỗi xảy ra khi xử lý đơn hàng');
  } finally {
    loading.value = false;
  }
};

// Khi component mounted, lấy dữ liệu giỏ hàng và địa chỉ giao hàng
onMounted(async () => {
  try {
    loading.value = true;
    const customerId = localStorage.getItem('user_id');
    if (customerId) {
      // Lấy giỏ hàng
      const cartRes = await axios.get(`/cart/${customerId}`)
      cartItems.value = cartRes.data.items

      // Lấy địa chỉ giao hàng
      const addressRes = await axios.get(`/address?customer_id=${customerId}`)
      shippingAddress.value = addressRes.data.find(addr => addr.is_default) || addressRes.data[0]
    }
  } catch (error) {
    console.error('Error loading data:', error.response?.data || error.message);
  } finally {
    loading.value = false;
  }
});
</script>

<style scoped>
/* Style tăng vùng bấm cho các nút trên mobile */
@media (hover: none) {
  input[type='radio'],
  input[type='text'],
  button {
    min-height: 44px;
    min-width: 44px;
  }
}
</style>
