<template>
  <div class="product-list">
    <!-- Header với breadcrumb -->
    <div class="header-section">
      <img src="@/assets/Banner.png" alt="Banner" class="banner-image" />
    </div>

    <!-- Main layout -->
    <div class="main-content">
      <!-- Left: Filter -->
      <div class="filter-section">
        <div class="filter-title">
          <div class="filter-title-group">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="20"
              height="20"
              viewBox="0 0 20 20"
              fill="none"
              class="icon"
            >
              <g clip-path="url(#clip0_2200_5624)">
                <path
                  d="M18.3332 2.5H1.6665L8.33317 10.3833V15.8333L11.6665 17.5V10.3833L18.3332 2.5Z"
                  stroke="#FF813F"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
              </g>
              <defs>
                <clipPath id="clip0_2200_5624">
                  <rect width="20" height="20" fill="white" />
                </clipPath>
              </defs>
            </svg>
            <span>Filter</span>
          </div>
        </div>

        <div class="filter-group">
          <h3 class="filter-heading">Danh mục sản phẩm</h3>
          <ul class="filter-list">
            <li>
              <label class="checkbox-container">
                <input type="checkbox" value="Thuc an" v-model="selectedCategories" />
                <span class="checkmark"></span>
                Thuc an
              </label>
            </li>
            <li>
              <label class="checkbox-container">
                <input type="checkbox" value="Do choi" v-model="selectedCategories" />
                <span class="checkmark"></span>
                Do choi
              </label>
            </li>
            <li>
              <label class="checkbox-container">
                <input type="checkbox" value="Phu kien cham soc" v-model="selectedCategories" />
                <span class="checkmark"></span>
                Phu kien cham soc
              </label>
            </li>
            <li>
              <label class="checkbox-container">
                <input type="checkbox" value="Vat dung ve sinh" v-model="selectedCategories" />
                <span class="checkmark"></span>
                Vat dung ve sinh
              </label>
            </li>
            <li>
              <label class="checkbox-container">
                <input type="checkbox" value="Chuong va giuong" v-model="selectedCategories" />
                <span class="checkmark"></span>
                Chuong va giuong
              </label>
            </li>
            <li>
              <label class="checkbox-container">
                <input type="checkbox" value="Snack/Banh thuong" v-model="selectedCategories" />
                <span class="checkmark"></span>
                Snack/Banh thuong
              </label>
            </li>
            <li>
              <label class="checkbox-container">
                <input type="checkbox" value="Thuc pham bo sung" v-model="selectedCategories" />
                <span class="checkmark"></span>
                Thuc pham bo sung
              </label>
            </li>
            <li>
              <label class="checkbox-container">
                <input type="checkbox" value="Quan ao/Thoi trang" v-model="selectedCategories" />
                <span class="checkmark"></span>
                Quan ao/Thoi trang
              </label>
            </li>
          </ul>
        </div>

        <hr class="filter-divider" />

        <div class="filter-group">
          <h3 class="filter-heading">Loại thú cưng</h3>
          <ul class="filter-list">
            <li>
              <label class="checkbox-container">
                <input type="checkbox" value="Cho" v-model="selectedPets" />
                <span class="checkmark"></span>
                Cho
              </label>
            </li>
            <li>
              <label class="checkbox-container">
                <input type="checkbox" value="Meo" v-model="selectedPets" />
                <span class="checkmark"></span>
                Meo
              </label>
            </li>
          </ul>
        </div>

        <hr class="filter-divider" />

        <!-- Bộ lọc mức giá -->
        <div class="filter-group">
          <h3 class="filter-heading">Mức giá</h3>
          <div class="price-filter">
            <div class="slider-container">
              <input
                type="range"
                :min="min"
                :max="max"
                v-model.number="minPrice"
                @input="checkMin"
                class="range-min"
              />
              <input
                type="range"
                :min="min"
                :max="max"
                v-model.number="maxPrice"
                @input="checkMax"
                class="range-max"
              />
              <div class="slider-track">
                <div class="slider-range" :style="trackStyle"></div>
              </div>
            </div>
            <div class="price-values">
              VND {{ formatPrice(minPrice) }} - {{ formatPrice(maxPrice) }}
            </div>
          </div>
        </div>

        <!-- Clear Filters Button -->
        <div class="filter-actions">
          <button @click="clearAllFilters" class="clear-filters-btn">Xóa tất cả bộ lọc</button>
        </div>
      </div>

      <!-- Right: Product Cards -->
      <div class="product-section">
        <!-- Sort dropdown và filter summary -->
        <div class="sort-wrapper">
          <div class="sort-dropdown">
            <button @click="toggleDropdown" class="sort-button">
              Sắp xếp
              <span class="arrow" :class="{ rotated: dropdownOpen }">▼</span>
            </button>
            <div v-if="dropdownOpen" class="dropdown-menu">
              <div
                class="dropdown-item"
                v-for="(option, index) in sortOptions"
                :key="index"
                @click="selectOption(option.value)"
                :class="{ selected: sortOption === option.value }"
              >
                {{ option.label }}
                <span v-if="sortOption === option.value" class="checkmark-selected">✔</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="loading-container">
          <div class="loading-spinner"></div>
          <p>Đang tải sản phẩm...</p>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="error-container">
          <div class="error-icon">❌</div>
          <p class="error-message">{{ error }}</p>
          <button @click="fetchProducts" class="retry-button">Thử lại</button>
        </div>

        <!-- Product Grid -->
        <div v-else>
          <!-- Empty state when no products -->
          <div v-if="paginatedProducts.length === 0" class="empty-state">
            <div class="empty-icon">🐾</div>
            <p>Không tìm thấy sản phẩm nào phù hợp với bộ lọc của bạn</p>
            <button @click="clearAllFilters" class="clear-filters-btn">Xóa bộ lọc</button>
          </div>

          <!-- Product Grid -->
          <div v-else class="product-grid">
            <router-link
              v-for="product in paginatedProducts"
              :key="product.product_id"
              :to="{ name: 'ProductDetail', params: { id: product.product_id } }"
              class="product-card"
              @mouseenter="setHoverProduct(product.product_id)"
              @mouseleave="clearHoverProduct"
            >
              <div class="product-image-container">
                <img :src="product.image_url" :alt="product.product_name" class="product-img" />
                <div class="product-overlay">
                  <button class="overlay-btn cart-btn" @click.prevent="addToCart(product)">
                    <i class="fas fa-shopping-cart"></i>
                    Giỏ hàng
                  </button>
                  <button class="overlay-btn buy-btn" @click.prevent="buyNow(product)">
                    Mua ngay
                  </button>
                  <button
                    class="overlay-btn favorite-btn"
                    :class="{ active: product.isFavorite }"
                    @click.prevent="toggleFavorite(product)"
                  >
                    <i :class="['fas', product.isFavorite ? 'fa-heart' : 'fa-heart-o']"></i>
                    {{ product.isFavorite ? 'Đã yêu thích' : 'Yêu thích' }}
                  </button>
                </div>
                <!-- Labels overlay -->
                <div class="product-labels">
                  <span v-if="product.isHot" class="label hot-label">Hot</span>
                  <span v-if="product.isNew" class="label new-label">New</span>
                  <span v-if="product.isFavorite" class="label favorite-label">♥</span>
                </div>
                <div class="product-overlay">
                  <button class="overlay-btn cart-btn" @click.prevent="addToCart(product)">
                    <i class="fas fa-shopping-cart"></i>
                    Giỏ hàng
                  </button>
                  <button class="overlay-btn buy-btn" @click.prevent="buyNow(product)">
                    Mua ngay
                  </button>
                  <button
                    class="overlay-btn favorite-btn"
                    :class="{ active: product.isFavorite }"
                    @click.prevent="toggleFavorite(product)"
                  >
                    <i :class="['fas', product.isFavorite ? 'fa-heart' : 'fa-heart-o']"></i>
                    {{ product.isFavorite ? 'Đã yêu thích' : 'Yêu thích' }}
                  </button>
                </div>
              </div>

              <div class="product-info">
                <h3 class="product-name">{{ product.product_name }}</h3>
                <p class="product-category">{{ product.category_name }}</p>
                <p class="product-price">{{ formatPrice(product.base_price || product.price) }}</p>
              </div>
            </router-link>
          </div>

          <!-- Pagination -->
          <div v-if="totalPages > 1" class="pagination">
            <button @click="goToPrevPage" :disabled="currentPage === 1" class="page-btn">
              ◀ Trước
            </button>

            <button
              v-for="page in visiblePages"
              :key="page"
              @click="goToPage(page)"
              :class="{ active: currentPage === page }"
              class="page-btn"
            >
              {{ page }}
            </button>

            <button @click="goToNextPage" :disabled="currentPage === totalPages" class="page-btn">
              Sau ▶
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { productService } from '@/services/api'
import { useRoute } from 'vue-router'
import { useCart } from '@/store/cart'
import axios from 'axios'
export default {
  name: 'ProductList',
  setup() {
    // Use cart in setup to make it available throughout the component
    const cart = useCart()
    return {
      cart
    }
  },
  data() {
    return {
      selectedProductIndex: null,
      min: 0,
      max: 500000,
      minPrice: 0,
      maxPrice: 500000,
      hoveredProductId: null,
      selectedCategories: [],
      selectedPets: [],
      currentPage: 1,
      itemsPerPage: 9,
      sortOption: 'priceAsc',
      dropdownOpen: false,
      loading: false,
      error: null,
      sortOptions: [
        { label: 'Giá tăng dần', value: 'priceAsc' },
        { label: 'Giá giảm dần', value: 'priceDesc' },
        { label: 'Từ A đến Z', value: 'nameAsc' },
        { label: 'Từ Z đến A', value: 'nameDesc' }
      ],
      products: []
    }
  },

  created() {
    const route = useRoute()
    if (route.query.category) {
      this.selectedCategories = [route.query.category]
    }
  },

  mounted() {
    document.addEventListener('click', this.handleClickOutside)
    this.fetchProducts()
    const route = useRoute()
    if (this.initialLoad && route.query.category) {
      this.handleInitialCategory(route.query.categoryName)
    }
    this.initialLoad = false
  },

  beforeUnmount() {
    document.removeEventListener('click', this.handleClickOutside)
  },

  methods: {
    async fetchProducts() {
      try {
        this.loading = true
        this.error = null

        const data = await productService.getAll()
        console.log('Fetched products from API:', data)

        if (Array.isArray(data)) {
          this.products = data
        } else {
          console.error('Invalid data format:', data)
          throw new Error('Dữ liệu không hợp lệ')
        }
      } catch (error) {
        this.error = error.message || 'Lỗi khi tải sản phẩm'
        console.error('Error fetching products:', error)
      } finally {
        this.loading = false
      }
    },

    async addToCart(product) {
      if (!product) return

      try {
        this.cartLoading = true

        // Format product data for cart
        const productForCart = {
          id: product.product_id,
          name: product.name || product.product_name,
          price: product.base_price || product.price,
          image: product.image,
          category: product.category
        }

        // Use the addItem method from the cart store
        await this.cart.addItem(productForCart)

        // Success message
        alert(`Đã thêm 1 ${productForCart.name} vào giỏ hàng!`)
      } catch (error) {
        console.error('Error adding to cart:', error)
        alert('Không thể thêm sản phẩm vào giỏ hàng. Vui lòng thử lại sau.')
      } finally {
        this.cartLoading = false
      }
    },

    async buyNow(product) {
      if (!product) return

      try {
        this.cartLoading = true

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

        const orderData = {
          address_id: addressId,
          payment_method: 'COD',
          product: {
            product_id: product.product_id,
            quantity: 1,
            unit_price: product.base_price || product.price
          }
        }

        // Call BuyNow API
        const response = await axios.post('http://localhost:8000/api/v1/orders/buynow', orderData, {
          withCredentials: true
        })
        console.log('xx', response)
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

    async toggleFavorite(product) {
      if (!product || !product.product_id) {
        alert('Không thể thêm sản phẩm vào danh sách yêu thích.')
        return
      }

      // Store the original favorite state
      const originalState = product.isFavorite

      // Optimistically update UI for better user experience
      product.isFavorite = !product.isFavorite

      try {
        // Determine endpoint based on the new state
        const endpoint = product.isFavorite ? 'add' : 'remove'

        // Call wishlist API
        await axios.post(
          `http://localhost:8000/api/v1/wishlist/${endpoint}`,
          {
            product_id: product.product_id
          },
          {
            withCredentials: true
          }
        )

        // Show a subtle notification if needed
        // this.$toast.success(product.isFavorite ? 'Đã thêm vào danh sách yêu thích' : 'Đã xóa khỏi danh sách yêu thích');
      } catch (error) {
        console.error('Error updating wishlist:', error)

        // Revert UI state if API call failed
        product.isFavorite = originalState

        alert('Không thể cập nhật trạng thái yêu thích. Vui lòng thử lại sau.')
      }
    },

    handleInitialCategory(categoryName) {
      if (!this.selectedCategories.includes(categoryName)) {
        this.selectedCategories = [categoryName]
      }
      this.handleFiltersChange()
    },

    clearAllFilters() {
      this.selectedCategories = []
      this.selectedPets = []
      this.minPrice = this.min
      this.maxPrice = this.max
      // Clear query params
      this.$router.replace({
        query: {}
      })
    },

    setHoverProduct(id) {
      this.hoveredProductId = id
    },

    clearHoverProduct() {
      this.hoveredProductId = null
    },

    handleClickOutside(event) {
      if (!event.target.closest('.sort-dropdown')) {
        this.dropdownOpen = false
      }
    },

    toggleDropdown() {
      this.dropdownOpen = !this.dropdownOpen
    },

    selectOption(value) {
      this.sortOption = value
      this.dropdownOpen = false
      this.currentPage = 1
    },

    formatPrice(value) {
      return new Intl.NumberFormat('vi-VN').format(value)
    },

    checkMin() {
      if (this.minPrice > this.maxPrice) {
        this.minPrice = this.maxPrice
      }
    },

    checkMax() {
      if (this.maxPrice < this.minPrice) {
        this.maxPrice = this.minPrice
      }
    },

    goToPage(page) {
      this.currentPage = page
    },

    goToPrevPage() {
      if (this.currentPage > 1) {
        this.currentPage--
      }
    },

    goToNextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++
      }
    },

    handleFiltersChange() {
      this.currentPage = 1
    },

    removeCategory(category) {
      const index = this.selectedCategories.indexOf(category)
      if (index > -1) {
        this.selectedCategories.splice(index, 1)
      }
    },

    removePet(pet) {
      const index = this.selectedPets.indexOf(pet)
      if (index > -1) {
        this.selectedPets.splice(index, 1)
      }
    }
  },

  computed: {
    filteredProducts() {
      return this.products.filter(product => {
        // Category filter - check against category_name from API
        const categoryMatch =
          this.selectedCategories.length === 0 ||
          this.selectedCategories.includes(product.category_name)

        // Pet filter - check against pet_type from API
        const petMatch =
          this.selectedPets.length === 0 || this.selectedPets.includes(product.pet_type)

        // Price filter - check against discount_price or price
        const productPrice = product.base_price || product.price
        const priceMatch = productPrice >= this.minPrice && productPrice <= this.maxPrice

        return categoryMatch && petMatch && priceMatch
      })
    },

    paginatedProducts() {
      const start = (this.currentPage - 1) * this.itemsPerPage
      const end = start + this.itemsPerPage

      let sortedProducts = [...this.filteredProducts]

      switch (this.sortOption) {
        case 'priceAsc':
          sortedProducts.sort(
            (a, b) => (a.discount_price || a.price) - (b.discount_price || b.price)
          )
          break
        case 'priceDesc':
          sortedProducts.sort(
            (a, b) => (b.discount_price || b.price) - (a.discount_price || a.price)
          )
          break
        case 'nameAsc':
          sortedProducts.sort((a, b) => a.product_name.localeCompare(b.product_name))
          break
        case 'nameDesc':
          sortedProducts.sort((a, b) => b.product_name.localeCompare(a.product_name))
          break
      }

      return sortedProducts.slice(start, end)
    },

    totalPages() {
      return Math.ceil(this.filteredProducts.length / this.itemsPerPage)
    },

    trackStyle() {
      const minPercent = ((this.minPrice - this.min) / (this.max - this.min)) * 100
      const maxPercent = ((this.maxPrice - this.min) / (this.max - this.min)) * 100
      return {
        left: `${minPercent}%`,
        width: `${maxPercent - minPercent}%`
      }
    },

    hasActiveFilters() {
      return (
        this.selectedCategories.length > 0 || this.selectedPets.length > 0 || this.priceFilterActive
      )
    },

    priceFilterActive() {
      return this.minPrice > this.min || this.maxPrice < this.max
    },

    visiblePages() {
      const pages = []
      const start = Math.max(1, this.currentPage - 2)
      const end = Math.min(this.totalPages, this.currentPage + 2)

      for (let i = start; i <= end; i++) {
        pages.push(i)
      }

      return pages
    }
  },

  watch: {
    selectedCategories() {
      this.handleFiltersChange()
    },
    selectedPets() {
      this.handleFiltersChange()
    },
    minPrice() {
      this.handleFiltersChange()
    },
    maxPrice() {
      this.handleFiltersChange()
    },
    '$route.query': {
      handler(newQuery) {
        if (newQuery.category && this.initialLoad) {
          this.handleInitialCategory(newQuery.categoryName)
        }
      },
      immediate: true
    }
  }
}
</script>

<style scoped>
.product-list {
  background-color: #f8f9fa;
  min-height: 100vh;
}

/* Header Section */
.header-section {
  width: 100%;
  height: 300px;
  overflow: hidden;
  position: relative;
}

.banner-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

/* Media queries cho responsive */
@media (max-width: 768px) {
  .header-section {
    height: 200px;
  }
}

@media (max-width: 480px) {
  .header-section {
    height: 150px;
  }
}

/* Layout chính */
.main-content {
  display: flex;
  gap: 32px;
  padding: 40px 32px;
  max-width: 1200px;
  margin: 0 auto;
}

/* Filter Section */
.filter-section {
  width: 280px;
  background: white;
  border-radius: 12px;
  padding: 24px;
  height: fit-content;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.filter-title {
  display: flex;
  justify-content: center;
  margin-bottom: 32px;
}

.filter-title-group {
  display: flex;
  align-items: center;
  gap: 8px;
}

.filter-title-group span {
  color: #ff813f;
  font-family: 'Poppins', sans-serif;
  font-size: 24px;
  font-weight: 600;
}

.filter-heading {
  margin-bottom: 16px;
  color: #333;
  font-family: 'Poppins', sans-serif;
  font-size: 16px;
  font-weight: 600;
}

.filter-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.filter-list li {
  margin-bottom: 12px;
}

.checkbox-container {
  display: flex;
  align-items: center;
  cursor: pointer;
  font-size: 14px;
  color: #555;
  font-family: 'Poppins', sans-serif;
}

.checkbox-container input {
  display: none;
}

.checkmark {
  width: 18px;
  height: 18px;
  border: 2px solid #ddd;
  border-radius: 3px;
  margin-right: 12px;
  position: relative;
  transition: all 0.2s;
}

.checkbox-container input:checked + .checkmark {
  background-color: #ff813f;
  border-color: #ff813f;
}

.checkbox-container input:checked + .checkmark::after {
  content: '✓';
  position: absolute;
  color: white;
  font-size: 12px;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
}

.filter-divider {
  width: 100%;
  height: 1px;
  border: none;
  background-color: #eee;
  margin: 24px 0;
}

/* Price Filter */
.price-filter {
  margin-top: 16px;
}

.slider-container {
  position: relative;
  height: 20px;
  margin-bottom: 16px;
}

.slider-track {
  position: absolute;
  width: 100%;
  height: 4px;
  background-color: #ddd;
  border-radius: 2px;
  top: 50%;
  transform: translateY(-50%);
}

.slider-range {
  position: absolute;
  height: 4px;
  background-color: #ff813f;
  border-radius: 2px;
}

input[type='range'] {
  -webkit-appearance: none;
  width: 100%;
  position: absolute;
  height: 4px;
  background: transparent;
  pointer-events: none;
  top: 50%;
  transform: translateY(-50%);
}

input[type='range']::-webkit-slider-thumb {
  -webkit-appearance: none;
  height: 18px;
  width: 18px;
  border-radius: 50%;
  background: #ff813f;
  cursor: pointer;
  pointer-events: auto;
  border: 2px solid white;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.price-values {
  font-size: 14px;
  color: #666;
  text-align: center;
  font-family: 'Poppins', sans-serif;
}

/* Product Section */
.product-section {
  flex: 1;
}

.sort-wrapper {
  display: flex;
  justify-content: flex-end;
  margin-bottom: 24px;
}

.sort-dropdown {
  position: relative;
}

.sort-button {
  background-color: white;
  border: 2px solid #ff813f;
  border-radius: 8px;
  padding: 12px 20px;
  font-size: 14px;
  color: #ff813f;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
  font-family: 'Poppins', sans-serif;
  transition: all 0.2s;
}

.sort-button:hover {
  background-color: #ff813f;
  color: white;
}

.arrow {
  transition: transform 0.3s ease;
}

.arrow.rotated {
  transform: rotate(180deg);
}

.dropdown-menu {
  position: absolute;
  top: calc(100% + 8px);
  right: 0;
  z-index: 10;
  background-color: white;
  border: 1px solid #eee;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  min-width: 180px;
  overflow: hidden;
}

.dropdown-item {
  padding: 12px 16px;
  font-size: 14px;
  color: #333;
  cursor: pointer;
  font-family: 'Poppins', sans-serif;
  transition: background-color 0.2s;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.dropdown-item:hover {
  background-color: #f8f9fa;
}

.dropdown-item.selected {
  background-color: #fff3ed;
  color: #ff813f;
}

.checkmark-selected {
  color: #ff813f;
  font-weight: bold;
}

/* Product Grid */
.product-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 24px;
  margin-bottom: 40px;
}

/* Responsive cho màn hình tablet */
@media (max-width: 992px) {
  .product-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

/* Responsive cho màn hình mobile */
@media (max-width: 576px) {
  .product-grid {
    grid-template-columns: 1fr;
  }
}

.product-card {
  display: block;
  text-decoration: none;
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
  cursor: pointer;
}

.product-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.product-image-container {
  position: relative;
  overflow: hidden;
}

.product-img {
  width: 100%;
  height: 200px;
  object-fit: cover;
}

.product-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  gap: 16px;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.product-card:hover .product-overlay {
  opacity: 1;
}

/* Cập nhật style cho các nút */
.overlay-btn {
  min-width: 140px;
  padding: 12px 24px;
  border: none;
  font-family: 'Poppins', sans-serif;
  font-size: 16px;
  font-weight: 600;
  text-align: center;
  cursor: pointer;
  transition: all 0.2s ease;
}

.cart-btn {
  background: #000000;
  color: white;
}

.buy-btn {
  background: #ff5722;
  color: white;
}

.favorite-btn {
  background: #8b0000;
  color: white;
  width: 140px;
}

.cart-btn:hover {
  background: #333333;
}

.buy-btn:hover {
  background: #ff7043;
}

.favorite-btn:hover {
  background: #b71c1c;
}

/* Animation cho các nút */
.product-card:hover .overlay-btn {
  transform: translateY(0);
  opacity: 1;
}

.overlay-btn {
  transform: translateY(20px);
  opacity: 0;
  transition: all 0.3s ease;
}

.product-labels {
  position: absolute;
  top: 12px;
  left: 12px;
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.label {
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 12px;
  font-weight: 600;
  color: white;
  font-family: 'Poppins', sans-serif;
}

.hot-label {
  background-color: #dc3545;
}

.new-label {
  background-color: #ff813f;
}

.favorite-label {
  background-color: #28a745;
}

.product-info {
  padding: 16px;
}

.product-name {
  margin: 0 0 8px 0;
  color: #ff813f;
  font-size: 16px;
  font-weight: 600;
  font-family: 'Poppins', sans-serif;
}

.product-category {
  margin: 0 0 8px 0;
  color: #666;
  font-size: 14px;
  font-family: 'Poppins', sans-serif;
}

.product-price {
  margin: 0;
  color: #ff813f;
  font-weight: 700;
  font-size: 18px;
  font-family: 'Poppins', sans-serif;
}

/* Pagination */
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 8px;
  margin-top: 40px;
}

.page-btn {
  padding: 10px 16px;
  border: 2px solid #ff813f;
  border-radius: 8px;
  background-color: white;
  color: #ff813f;
  cursor: pointer;
  font-weight: 600;
  font-family: 'Poppins', sans-serif;
  transition: all 0.2s;
}

.page-btn:hover:not(:disabled) {
  background-color: #ff813f;
  color: white;
}

.page-btn.active {
  background-color: #ff813f;
  color: white;
}

.page-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

@media (max-width: 768px) {
  .main-content {
    flex-direction: column;
    padding: 20px;
  }

  .filter-section {
    width: 100%;
  }

  .product-grid {
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 16px;
  }

  .hero-banner {
    flex-direction: column;
    text-align: center;
    padding: 20px;
  }

  .hero-content {
    margin-bottom: 24px;
  }

  .hero-title {
    font-size: 32px;
  }

  .pet-circle {
    width: 160px;
    height: 160px;
  }

  .pet-img {
    width: 120px;
    height: 120px;
  }

  .sort-wrapper {
    justify-content: center;
    margin: 16px 0;
  }

  .dropdown-menu {
    left: 0;
    right: 0;
  }
}

@media (max-width: 480px) {
  .product-grid {
    grid-template-columns: 1fr;
  }

  .hero-title {
    font-size: 28px;
  }

  .breadcrumb {
    font-size: 14px;
  }

  .filter-section {
    padding: 16px;
  }

  .pagination {
    flex-wrap: wrap;
  }

  .page-btn {
    padding: 8px 12px;
    font-size: 14px;
  }

  .sort-button {
    width: 100%;
    justify-content: center;
  }
}

/* Animation */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.product-card {
  animation: fadeIn 0.3s ease-out;
}

/* Hover effects */
.product-card:hover .product-img {
  transform: scale(1.05);
  transition: transform 0.3s ease;
}

.sort-button:active {
  transform: scale(0.98);
}

/* Accessibility */
@media (prefers-reduced-motion: reduce) {
  .product-card,
  .product-card:hover .product-img {
    animation: none;
    transform: none;
    transition: none;
  }
}

/* Focus styles */
.sort-button:focus,
.page-btn:focus {
  outline: 2px solid #ff813f;
  outline-offset: 2px;
}

/* Print styles */
@media print {
  .filter-section,
  .sort-wrapper,
  .pagination {
    display: none;
  }

  .product-grid {
    grid-template-columns: repeat(2, 1fr);
  }

  .product-card {
    break-inside: avoid;
    box-shadow: none;
    border: 1px solid #ddd;
  }
}

/* Loading and Error States */
.loading-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 60px 20px;
  text-align: center;
}

.loading-spinner {
  width: 50px;
  height: 50px;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #ff813f;
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
  font-size: 16px;
  font-family: 'Poppins', sans-serif;
  margin: 0;
}

.error-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 60px 20px;
  text-align: center;
  background-color: #fff5f5;
  border-radius: 12px;
  margin: 20px 0;
}

.error-icon {
  font-size: 48px;
  margin-bottom: 16px;
}

.error-message {
  color: #e53e3e;
  font-size: 18px;
  font-family: 'Poppins', sans-serif;
  font-weight: 500;
  margin: 0 0 20px 0;
}

.retry-button {
  background-color: #ff813f;
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

/* Empty state */
.empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 60px 20px;
  text-align: center;
}

.empty-state .empty-icon {
  font-size: 64px;
  color: #ddd;
  margin-bottom: 20px;
}

.empty-state p {
  color: #666;
  font-size: 18px;
  font-family: 'Poppins', sans-serif;
  margin: 0;
}
</style>
