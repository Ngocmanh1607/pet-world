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
      <img :src="product.image" :alt="product.name" />
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
        <button class="like-button" :class="{ 'active': isLiked }" @click="toggleLike">
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
          <li v-if="product.nutritionFacts.protein">Protein: {{ product.nutritionFacts.protein }}</li>
          <li v-if="product.nutritionFacts.fat">Chất béo: {{ product.nutritionFacts.fat }}</li>
          <li v-if="product.nutritionFacts.fiber">Chất xơ: {{ product.nutritionFacts.fiber }}</li>
          <li v-if="product.nutritionFacts.moisture">Độ ẩm: {{ product.nutritionFacts.moisture }}</li>
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

  <div class="related-products-section">
    <h3 class="section-title">Sản phẩm liên quan</h3>
    <div class="related-products-grid">
      <div class="related-product-card" v-for="product in relatedProducts" :key="product.id">
        <div class="product-image">
          <img :src="product.image_url" :alt="product.name">
        </div>
        <div class="product-info">
          <h4 class="product-name">{{ product.name }}</h4>
          <p class="product-category">{{ product.category }}</p>
          <p class="product-price">{{ product.price.toLocaleString() }}</p>
        </div>
      </div>
    </div>
    <button class="show-more-btn">Hiện thị thêm</button>
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
  color: #FF5722;
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
  border: 1px solid #FF5722;
  border-radius: 4px;
  overflow: hidden;
}

.quantity-control button {
  width: 36px;
  height: 36px;
  border: none;
  background: white;
  color: #FF5722;
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
  border-left: 1px solid #FF5722;
  border-right: 1px solid #FF5722;
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
  border: 1px solid #F87537;
}

.add-to-cart {
  background: white;
  color: #F87537;
}

.buy-now {
  background: #F87537;
  color: white;
}

.shipping-note {
  display: flex;
  align-items: center;
  gap: 8px;
  color: #F87537;
  font-size: 14px;
}

.shipping-note i {
  color: #F87537;
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
  color: #F87537;
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
  color: #F87537;
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
  color: #FF5722;
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
  border: 1px solid #F87537;
  color: #F87537;
  border-radius: 4px;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.show-more-btn:hover {
  background: #FFF3E0;
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
  border-top: 4px solid #F87537;
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
  background-color: #F87537;
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
  color: #F87537;
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
  color: #F87537;
  font-size: 16px;
}

.product-description {
  margin: 20px 0;
  padding: 20px;
  background-color: #f8f9fa;
  border-radius: 8px;
  border-left: 4px solid #F87537;
}

.product-description p {
  font-size: 16px;
  line-height: 1.7;
  color: #333;
  margin: 0;
}

/* Like button active state */
.like-button.active {
  color: #F87537;
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
import { productService } from '@/services/api'; // Enable real API

export default {
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
      ]
    }
  },

  async mounted() {
    await this.fetchProduct();
  },

  methods: {
    async fetchProduct() {
      try {
        this.loading = true;
        this.error = null;

        const productId = parseInt(this.$route.params.id);
        console.log('Fetching product with ID:', productId);
        const productData = await productService.getById(productId);
        if (!productData) {
          throw new Error('Không tìm thấy sản phẩm');
        }

        this.product = productData;
        this.isLiked = productData.isFavorite || false;

        console.log('Product data from API:', productData);
      } catch (error) {
        this.error = error.message;
        console.error('Error fetching product:', error);
      } finally {
        this.loading = false;
      }
    },

    decrease() {
      if (this.quantity > 1) {
        this.quantity--
      }
    },

    increase() {
      this.quantity++
    },

    toggleLike() {
      this.isLiked = !this.isLiked;
      // TODO: Call API to update favorite status
    },

    addToCart() {
      // TODO: Implement add to cart functionality
      console.log(`Added ${this.quantity} ${this.product?.name} to cart`);
    },

    buyNow() {
      // TODO: Implement buy now functionality
      console.log(`Buy now ${this.quantity} ${this.product?.name}`);
    },

    formatPrice(price) {
      return new Intl.NumberFormat('vi-VN').format(price);
    }
  }
}
</script>