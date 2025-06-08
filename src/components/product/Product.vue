<template>
  <div v-if="loading" class="loading">
    Loading...
  </div>

  <div v-else-if="error" class="error">
    {{ error }}
  </div>

  <div v-else class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
    <div v-for="product in products" :key="product.id"
      class="flex flex-col overflow-hidden border border-gray-100 rounded-2xl">
      <img class="min-h-[162px] object-cover" :src="product.image" :alt="product.name" />
      <div class="px-4 py-3 bg-white">
        <div class="flex items-center justify-between">
          <div class="text-base font-semibold">{{ product.name }}</div>
          <button class="flex items-center justify-center w-5 h-5 ml-3 bg-gray-100 rounded-full shrink-0"
            @click="toggleFavorite(product)">
            <i class="fa-xs" :class="[product.isFavorite ? 'fa-solid' : 'fa-regular', 'fa-heart']"
              style="color: #f87537"></i>
          </button>
        </div>
        <div class="mt-2 text-xs font-semibold text-left">
          {{ formatPrice(product.price) }}
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue';

const products = ref([]);
const loading = ref(false);
const error = ref(null);

// Thêm hàm toggleFavorite
const toggleFavorite = async (product) => {
  try {
    // Đảo trạng thái yêu thích
    product.isFavorite = !product.isFavorite;
    
    if (product.isFavorite) {
      // Gọi API để thêm vào danh sách yêu thích
      await axios.post('http://localhost:8000/api/v1/wishlist/add', {
        product_id: product.id
      }, {
        withCredentials: true
      });
    } else {
      // Gọi API để xóa khỏi danh sách yêu thích
      await axios.delete(`http://localhost:8000/api/v1/wishlist/${product.id}`, {
        withCredentials: true
      });
    }
  } catch (err) {
    console.error('Error toggling favorite:', err);
    // Khôi phục trạng thái nếu có lỗi
    product.isFavorite = !product.isFavorite;
    // Thông báo lỗi (tùy chọn)
    alert('Không thể cập nhật danh sách yêu thích. Vui lòng thử lại sau.');
  }
};

const fetchProducts = async () => {
  try {
    loading.value = true;
    error.value = null;

    const response = await axios.get('http://localhost:8000/api/v1/products/bestsellers?limit=3');
    console.log(response);
    
    products.value = response.data.products;
  } catch (err) {
    console.error('Error fetching products:', err);
    error.value = 'Could not load products';
  } finally {
    loading.value = false;
  }
};

// Format price to VND
const formatPrice = (price) => {
  return new Intl.NumberFormat('vi-VN', {
    style: 'currency',
    currency: 'VND'
  }).format(price);
};

onMounted(() => {
  fetchProducts();
});
</script>

<style scoped>
.loading,
.error {
  text-align: center;
  padding: 2rem;
  color: #666;
}

.error {
  color: #ef4444;
}
</style>