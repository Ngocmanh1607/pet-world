<template>
  <div class="banner-wrapper">
    <img class="banner" src="@/assets/Banner.png" alt="Product Banner" />
  </div>

  <!-- Loading State -->
  <div v-if="loading" class="loading-container">
    <div class="loading-spinner"></div>
    <p>Đang tải thông tin sản phẩm...</p>
  </div>

  <!-- Error State -->
  <div v-else-if="error" class="error-container">
    <div class="error-icon">❌</div>
    <p class="error-message">{{ error }}</p>
    <button @click="fetchProduct" class="retry-button">Thử lại</button>
  </div>

  <!-- Product Detail Content -->
  <div v-else-if="product" class="product-detail-wrapper">
    <!-- Bên trái: Ảnh sản phẩm -->
    <div class="product-image">
      <img :src="product.image_url" :alt="product.name" />
    </div>

    <!-- Bên phải: Thông tin sản phẩm -->
    <div class="product-info">
      <div class="top-row">
        <div class="left-info">
          <h2 class="product-name">{{ product.product_name }}</h2>
          <p class="category">{{ product.category?.toUpperCase() }}</p>
          <p class="pet-type">LOẠI THÚ CƯNG: {{ product.pet_type?.toUpperCase() }}</p>
          <div class="price-container">
            <span class="current-price">{{ formatPrice(product.base_price) }}</span>
            <span class="price-unit">VND</span>
          </div>
          <div v-if="product.brand" class="brand-info">
            <span class="brand-label">Thương hiệu: </span>
            <span class="brand-name">{{ product.brand }}</span>
          </div>
          <div v-if="product.stock" class="stock-info">
            <span class="stock-label">Còn lại: </span>
            <span class="stock-count">{{ product.stock }} sản phẩm</span>
          </div>
        </div>
        <button class="like-button" :class="{ active: isLiked }" @click="toggleLike">
          <i class="fas fa-heart"></i>
        </button>
      </div>

      <div class="quantity-section">
        <p class="quantity-label">Số lượng</p>
        <div class="quantity-control">
          <button @click="decrease">−</button>
          <input type="text" v-model="quantity" min="1" readonly />
          <button @click="increase">+</button>
        </div>
      </div>

      <div class="action-buttons">
        <button class="add-to-cart" @click="addToCart">Thêm vào giỏ hàng</button>
        <button class="buy-now" @click="buyNow">Mua ngay</button>
      </div>

      <div class="shipping-note">
        <i class="fas fa-truck"></i>
        <span>Miễn phí shipping: Cho đơn trên 500,000đ</span>
      </div>
    </div>
  </div>

  <!-- Mô tả sản phẩm -->
  <div v-if="product" class="product-description-section">
    <h3 class="section-title">Mô tả</h3>
    <div class="description-content">
      <p><strong>Tên sản phẩm:</strong> {{ product.name }}</p>
      <p v-if="product.brand"><strong>Thương hiệu:</strong> {{ product.brand }}</p>
      <p v-if="product.category"><strong>Danh mục:</strong> {{ product.category }}</p>
      <p v-if="product.petType"><strong>Dành cho:</strong> {{ product.petType }}</p>
      <p v-if="product.weight"><strong>Trọng lượng:</strong> {{ product.weight }}</p>
      <p v-if="product.size"><strong>Kích thước:</strong> {{ product.size }}</p>

      <div v-if="product.description" class="product-description">
        <p>{{ product.description }}</p>
      </div>

      <div v-if="product.ingredients && product.ingredients.length > 0">
        <h4>Thành phần:</h4>
        <ul class="ingredients-list">
          <li v-for="ingredient in product.ingredients" :key="ingredient">{{ ingredient }}</li>
        </ul>
      </div>

      <div v-if="product.nutritionFacts">
        <h4>Thông tin dinh dưỡng:</h4>
        <ul class="nutrition-list">
          <li v-if="product.nutritionFacts.protein">
            Protein: {{ product.nutritionFacts.protein }}
          </li>
          <li v-if="product.nutritionFacts.fat">Chất béo: {{ product.nutritionFacts.fat }}</li>
          <li v-if="product.nutritionFacts.fiber">Chất xơ: {{ product.nutritionFacts.fiber }}</li>
          <li v-if="product.nutritionFacts.moisture">
            Độ ẩm: {{ product.nutritionFacts.moisture }}
          </li>
        </ul>
      </div>

      <div v-if="product.features && product.features.length > 0">
        <h4>Tính năng nổi bật:</h4>
        <ul class="features-list">
          <li v-for="feature in product.features" :key="feature">{{ feature }}</li>
        </ul>
      </div>

      <div v-if="product.ageGroup">
        <h4>Độ tuổi phù hợp:</h4>
        <p>{{ product.ageGroup }}</p>
      </div>
    </div>
  </div>
</template>

<style scoped>
.banner-wrapper {
  width: 100%;
  height: 300px;
  overflow: hidden;
}

.banner {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.product-detail-wrapper {
  max-width: 1200px;
  margin: 40px auto;
  padding: 0 20px;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 40px;
}

.product-image {
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.product-image img {
  width: 100%;
  height: auto;
  display: block;
}

.product-info {
  position: relative;
}

.top-row {
  position: relative;
  padding-right: 40px;
}

.left-info {
  margin-right: 40px;
}

.product-name {
  font-size: 20px;
  color: #ff5722;
  margin-bottom: 16px;
  font-family: 'Poppins', sans-serif;
  font-weight: 500;
}

.category,
.pet-type {
  color: #666;
  font-size: 14px;
  margin: 8px 0;
  text-transform: uppercase;
}

.price-container {
  margin: 24px 0;
  display: flex;
  align-items: baseline;
  gap: 8px;
}

.current-price {
  font-size: 32px;
  color: #333;
  font-weight: 600;
}

.price-unit {
  color: #333;
  font-size: 16px;
}

.original-price {
  color: #999;
  text-decoration: line-through;
  font-size: 16px;
}

.quantity-label {
  color: #333;
  margin-bottom: 8px;
  font-size: 14px;
}

.quantity-control {
  display: inline-flex;
  align-items: center;
  border: 1px solid #ff5722;
  border-radius: 4px;
  overflow: hidden;
}

.quantity-control button {
  width: 36px;
  height: 36px;
  border: none;
  background: white;
  color: #ff5722;
  font-size: 20px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
}

.quantity-control input {
  width: 50px;
  height: 36px;
  border: none;
  border-left: 1px solid #ff5722;
  border-right: 1px solid #ff5722;
  text-align: center;
  font-size: 16px;
}

.action-buttons {
  display: flex;
  gap: 16px;
  margin: 24px 0;
}

.add-to-cart,
.buy-now {
  flex: 1;
  padding: 12px 24px;
  border-radius: 4px;
  font-size: 16px;
  font-weight: 500;
  cursor: pointer;
  border: 1px solid #f87537;
}

.add-to-cart {
  background: white;
  color: #f87537;
}

.buy-now {
  background: #f87537;
  color: white;
}

.shipping-note {
  display: flex;
  align-items: center;
  gap: 8px;
  color: #f87537;
  font-size: 14px;
}

.shipping-note i {
  color: #f87537;
}

.like-button {
  position: absolute;
  top: 0;
  right: 0;
  width: 40px;
  height: 40px;
  border: none;
  background: none;
  font-size: 24px;
  color: #ddd;
  cursor: pointer;
  transition: all 0.3s ease;
}

.like-button.active {
  color: #f87537;
}

.like-button:hover {
  transform: scale(1.1);
}

/* Related Products Section */
.related-products-section {
  max-width: 1200px;
  margin: 40px auto;
  padding: 24px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.related-products-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 24px;
  margin: 24px 0;
}

.related-product-card {
  background: white;
  border-radius: 8px;
  overflow: hidden;
  transition: transform 0.3s ease;
  cursor: pointer;
}

.related-product-card:hover {
  transform: translateY(-4px);
}

.section-title {
  color: #f87537;
  font-size: 24px;
  font-weight: 600;
  margin-bottom: 24px;
  font-family: 'Poppins', sans-serif;
  position: relative;
  padding-bottom: 12px;
}

.product-description-section {
  max-width: 1200px;
  margin: 40px auto;
  padding: 32px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.product-description-section .section-title {
  color: #333;
  font-size: 24px;
  font-weight: 600;
  margin-bottom: 24px;
  font-family: 'Poppins', sans-serif;
  text-transform: uppercase;
}

.description-content {
  color: #666;
  line-height: 1.8;
}

.description-content p {
  margin: 12px 0;
  font-size: 15px;
}

.description-content h4 {
  color: #333;
  font-size: 18px;
  font-weight: 600;
  margin: 24px 0 16px;
}

.feeding-list {
  list-style: none;
  padding: 0;
  margin: 16px 0;
}

.feeding-list li {
  padding-left: 24px;
  position: relative;
  margin: 12px 0;
  font-size: 15px;
  color: #666;
}

.feeding-list li::before {
  content: '•';
  position: absolute;
  left: 8px;
  color: #ff5722;
  font-size: 16px;
}

/* Responsive for description section */
@media (max-width: 768px) {
  .product-description-section {
    padding: 24px;
    margin: 24px 20px;
  }

  .description-content p,
  .feeding-list li {
    font-size: 14px;
  }
}

.show-more-btn {
  display: block;
  margin: 0 auto;
  padding: 12px 24px;
  background: white;
  border: 1px solid #f87537;
  color: #f87537;
  border-radius: 4px;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.show-more-btn:hover {
  background: #fff3e0;
}

/* Responsive Design */
@media (max-width: 992px) {
  .product-detail-wrapper {
    grid-template-columns: 1fr;
  }

  .related-products-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 768px) {
  .related-products-section .section-title {
    font-size: 18px;
    margin-bottom: 16px;
  }
}

@media (max-width: 576px) {
  .action-buttons {
    flex-direction: column;
  }

  .related-products-grid {
    grid-template-columns: 1fr;
  }
}

/* Loading and Error States */
.loading-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 80px 20px;
  text-align: center;
  max-width: 1200px;
  margin: 40px auto;
}

.loading-spinner {
  width: 50px;
  height: 50px;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #f87537;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin-bottom: 20px;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }

  100% {
    transform: rotate(360deg);
  }
}

.loading-container p {
  color: #666;
  font-size: 18px;
  font-family: 'Poppins', sans-serif;
  margin: 0;
}

.error-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 80px 20px;
  text-align: center;
  background-color: #fff5f5;
  border-radius: 12px;
  margin: 40px auto;
  max-width: 600px;
}

.error-icon {
  font-size: 64px;
  margin-bottom: 20px;
}

.error-message {
  color: #e53e3e;
  font-size: 18px;
  font-family: 'Poppins', sans-serif;
  font-weight: 500;
  margin: 0 0 24px 0;
}

.retry-button {
  background-color: #f87537;
  color: white;
  border: none;
  padding: 12px 24px;
  border-radius: 8px;
  font-size: 16px;
  font-weight: 600;
  font-family: 'Poppins', sans-serif;
  cursor: pointer;
  transition: background-color 0.2s;
}

.retry-button:hover {
  background-color: #e67e22;
}

/* Brand and Stock Info */
.brand-info,
.stock-info {
  margin: 12px 0;
  font-size: 14px;
}

.brand-label,
.stock-label {
  color: #666;
  font-weight: 500;
}

.brand-name {
  color: #f87537;
  font-weight: 600;
}

.stock-count {
  color: #28a745;
  font-weight: 600;
}

/* Enhanced Description Lists */
.ingredients-list,
.nutrition-list,
.features-list {
  list-style: none;
  padding: 0;
  margin: 16px 0;
}

.ingredients-list li,
.nutrition-list li,
.features-list li {
  padding-left: 24px;
  position: relative;
  margin: 8px 0;
  font-size: 15px;
  color: #666;
}

.ingredients-list li::before,
.nutrition-list li::before,
.features-list li::before {
  content: '•';
  position: absolute;
  left: 8px;
  color: #f87537;
  font-size: 16px;
}

.product-description {
  margin: 20px 0;
  padding: 20px;
  background-color: #f8f9fa;
  border-radius: 8px;
  border-left: 4px solid #f87537;
}

.product-description p {
  font-size: 16px;
  line-height: 1.7;
  color: #333;
  margin: 0;
}

/* Like button active state */
.like-button.active {
  color: #f87537;
  transform: scale(1.1);
}

/* Enhanced action buttons */
.add-to-cart:hover,
.buy-now:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.add-to-cart:hover {
  background-color: #f8f9fa;
}

.buy-now:hover {
  background-color: #e67e22;
}
</style>
<script>
import { productService } from '@/services/api'
import { useCart } from '@/store/cart'
import axios from 'axios'
import { useRouter } from 'vue-router'

export default {
  setup() {
    // Use cart in setup to make it available throughout the component
    const cart = useCart()
    const router = useRouter()

    return {
      cart,
      router
    }
  },

  data() {
    return {
      quantity: 1,
      loading: false,
      error: null,
      product: null,
      isLiked: false,
      relatedProducts: [
        {
          id: 1,
          name: 'Royal Canin for Adult Dogs',
          category: 'Sản phẩm',
          price: 360000,
          image: require('@/assets/product1.png')
        },
        {
          id: 2,
          name: 'Royal Canin for Mother & Baby',
          category: 'Sản phẩm',
          price: 360000,
          image: require('@/assets/product2.png')
        },
        {
          id: 3,
          name: 'Royal Canin for Persian',
          category: 'Sản phẩm',
          price: 360000,
          image: require('@/assets/product3.png')
        },
        {
          id: 4,
          name: 'Royal Canin for Hair&Skin',
          category: 'Sản phẩm',
          price: 400000,
          image: require('@/assets/product4.png')
        }
      ],
      cartLoading: false
    }
  },

  async mounted() {
    await this.fetchProduct()
  },

  methods: {
    async fetchProduct() {
      try {
        this.loading = true
        this.error = null
        console.log('Fetching product details...', this.$route.params.id)
        const productId = this.$route.params.id
        console.log('Fetching product with ID:', productId)
        const productData = await productService.getById(productId)

        if (!productData) {
          throw new Error('Không tìm thấy sản phẩm')
        }

        this.product = productData

        // Kiểm tra trạng thái yêu thích sau khi lấy thông tin sản phẩm
        await this.checkWishlistStatus()

        console.log('Product data from API:', productData)
      } catch (error) {
        this.error = error.message
        console.error('Error fetching product:', error)
      } finally {
        this.loading = false
      }
    },

    async checkWishlistStatus() {
      if (!this.product.product_id) return

      try {
        // Lấy danh sách yêu thích
        const response = await axios.get('http://localhost:8000/api/v1/wishlist', {
          withCredentials: true
        })

        // Kiểm tra xem sản phẩm hiện tại có trong danh sách yêu thích không
        if (response.data && Array.isArray(response.data)) {
          // Tìm sản phẩm trong danh sách yêu thích
          const isInWishlist = response.data.some(
            item => item.product_id === this.product.id || item.id === this.product.id
          )

          this.isLiked = isInWishlist
        }
      } catch (error) {
        console.error('Error checking wishlist status:', error)
      }
    },

    decrease() {
      if (this.quantity > 1) {
        this.quantity--
      }
    },

    increase() {
      if (this.quantity < (this.product.stock || 99)) {
        this.quantity++
      }
    },

    async toggleLike() {
      if (!this.product.product_id) {
        alert('Không thể thêm sản phẩm vào danh sách yêu thích.')
        return
      }

      const previousState = this.isLiked
      this.isLiked = !this.isLiked

      try {
        const endpoint = this.isLiked ? '/api/v1/wishlist/add' : '/api/v1/wishlist/remove'

        // Gọi API yêu thích sản phẩm
        await axios.post(
          `http://localhost:8000${endpoint}`,
          {
            product_id: this.product.product_id
          },
          {
            withCredentials: true
          }
        )
      } catch (error) {
        console.error('Error updating wishlist:', error)

        // Khôi phục trạng thái UI nếu API gọi thất bại
        this.isLiked = previousState

        // Hiển thị thông báo lỗi
        alert(
          this.isLiked
            ? 'Không thể thêm sản phẩm vào danh sách yêu thích. Vui lòng thử lại sau.'
            : 'Không thể xóa sản phẩm khỏi danh sách yêu thích. Vui lòng thử lại sau.'
        )
      }
    },

    async addToCart() {
      if (!this.product) return

      try {
        this.cartLoading = true

        // Format product data for cart
        const productForCart = {
          id: this.product.id || this.product.product_id,
          name: this.product.name || this.product.product_name,
          price: this.product.base_price || this.product.price,
          image: this.product.image_url,
          category: this.product.category
        }

        // Use the addItem method from the cart store
        await this.cart.addItem(productForCart)

        // Success message
        alert(`Đã thêm ${this.quantity} ${productForCart.name} vào giỏ hàng!`)
      } catch (error) {
        console.error('Error adding to cart:', error)
        alert('Không thể thêm sản phẩm vào giỏ hàng. Vui lòng thử lại sau.')
      } finally {
        this.cartLoading = false
      }
    },

    async buyNow() {
      if (!this.product) return

      try {
        this.cartLoading = true

        // Get default address for user
        let addressId = null
        try {
          const addressRes = await axios.get('http://localhost:8000/api/v1/address', {
            withCredentials: true
          })
          console.log('xx', addressRes)

          if (
            addressRes.data.addresses &&
            Array.isArray(addressRes.data.addresses) &&
            addressRes.data.addresses.length > 0
          ) {
            const defaultAddress =
              addressRes.data.addresses.find(addr => addr.is_default) || addressRes.data[0]
            addressId = defaultAddress.id || defaultAddress.address_id
          }
        } catch (error) {
          console.error('Error fetching address:', error)
        }

        if (!addressId) {
          alert('Vui lòng thêm địa chỉ giao hàng trước khi mua hàng')
          this.$router.push('/profile')
          return
        }

        // Prepare order data
        const orderData = {
          address_id: addressId,
          payment_method: 'COD',
          product: {
            product_id: this.product.id || this.product.product_id,
            quantity: this.quantity,
            unit_price: this.product.base_price || this.product.price
          }
        }

        // Call BuyNow API
        const response = await axios.post('http://localhost:8000/api/v1/orders/buynow', orderData, {
          withCredentials: true
        })

        if (response.data && response.data.order_id) {
          alert('Đặt hàng thành công!')
        } else {
          throw new Error(response.data?.message || 'Không thể đặt hàng')
        }
      } catch (error) {
        console.error('Error with buy now:', error)
        alert(
          'Không thể mua ngay: ' +
            (error.response?.data?.message || error.message || 'Lỗi không xác định')
        )
      } finally {
        this.cartLoading = false
      }
    },

    formatPrice(price) {
      return new Intl.NumberFormat('vi-VN').format(price)
    }
  }
}
</script>
