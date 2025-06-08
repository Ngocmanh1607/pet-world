<style scoped>
.w-container {
  width: 94%;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.custom-btn {
  background-color: white;
  color: #fd7e14;
  border: 1px solid #fd7e14;
  transition: all 0.3s ease;
  padding: 6px 16px;
  font-weight: 500;
}

.custom-btn:hover {
  background-color: #fd7e14;
  color: white;
  border-color: #fd7e14;
}

.custom-table thead {
  background-color: #f1f3f5;
}

.custom-table th {
  font-weight: 600;
  font-size: 14px;
  white-space: nowrap;
  vertical-align: middle;
  cursor: pointer;
  /* Thêm con trỏ để người dùng biết có thể click */
}

.custom-table td {
  vertical-align: middle;
  font-size: 14px;
  text-align: center;
}

.custom-table tbody tr:nth-child(even) {
  background-color: #faf5ff;
}

.action-icon {
  color: #fd7e14;
  cursor: pointer;
  transition: 0.2s ease;
}

.action-icon:hover {
  transform: scale(1.1);
  opacity: 0.8;
}

.sort-indicator {
  font-size: 12px;
  margin-left: 6px;
  color: #fd7e14;
}
</style>

<template>
  <div class="container-fluid bg-white">
    <div
      class="w-container mb-3 mx-auto p-3 rounded flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3"
    >
      <h2 class="mx-1 text-zinc-700 text-xl">Danh sách sản phẩm</h2>
      <button class="btn mx-1 custom-btn" @click="openCreatePopup">+ Thêm sản phẩm</button>
    </div>

    <!-- Thanh Search -->
    <div class="w-container mx-auto mb-3" style="width: 94%">
      <input
        type="text"
        v-model="searchTerm"
        placeholder="Tìm kiếm sản phẩm theo tên..."
        class="form-control p-2 border border-gray-300 rounded w-full"
      />
    </div>

    <!-- Loading indicator -->
    <div v-if="loading" class="text-center py-10">
      <div
        class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-orange-500 border-t-transparent"
      ></div>
      <p class="mt-2 text-gray-600">Đang tải dữ liệu...</p>
    </div>

    <!-- Error message -->
    <div v-else-if="error" class="bg-red-50 p-4 rounded-lg text-center text-red-600">
      {{ error }}
      <button @click="fetchProducts" class="ml-2 underline">Thử lại</button>
    </div>

    <!-- Empty state -->
    <div
      v-else-if="filteredAndSortedProducts.length === 0"
      class="bg-white rounded-lg shadow p-10 text-center"
    >
      <i class="fas fa-box text-4xl text-gray-300 mb-3"></i>
      <p class="text-gray-500">Không tìm thấy sản phẩm nào phù hợp với bộ lọc</p>
    </div>

    <!-- Bảng danh sách sản phẩm -->
    <div v-else class="bg-white rounded-lg shadow overflow-x-auto">
      <table class="w-full border-collapse">
        <thead>
          <tr>
            <th class="bg-gray-100 font-semibold p-4 text-left">ID sản phẩm</th>
            <th class="bg-gray-100 font-semibold p-4 text-left">Tên sản phẩm</th>
            <th class="bg-gray-100 font-semibold p-4 text-left">Loại thú nuôi</th>
            <th class="bg-gray-100 font-semibold p-4 text-left">Giá gốc</th>
            <th class="bg-gray-100 font-semibold p-4 text-left">Giá tiền</th>
            <th class="bg-gray-100 font-semibold p-4 text-left">Hàng tồn kho</th>
            <th class="bg-gray-100 font-semibold p-4 text-left">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in paginatedProducts" :key="item.product_id" class="even:bg-gray-50">
            <td class="p-4">{{ item.product_id }}</td>
            <td class="p-4">{{ item.product_name }}</td>
            <td class="p-4">{{ item.pet_type }}</td>
            <td class="p-4">{{ formatCurrency(item.base_price) }}</td>
            <td class="p-4">{{ formatCurrency(item.discount_price) }}</td>
            <td class="p-4">{{ item.stock }}</td>
            <td class="p-4">
              <button
                @click="openEditPopup(item)"
                class="p-1 text-gray-600 hover:opacity-70"
                title="Chỉnh sửa"
              >
                <i class="fas fa-edit"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- Popup Edit -->
    <div
      v-if="editingProduct"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
      <div class="bg-white rounded p-6 w-[420px] max-w-full">
        <h3 class="text-lg font-semibold mb-4 text-center">Thông tin sản phẩm</h3>
        <form @submit.prevent="saveEdit">
          <div class="mb-1">
            <label class="block mb-1 font-medium text-sm">Tên sản phẩm</label>
            <input
              v-model="editingProduct.product_name"
              type="text"
              class="w-full border border-gray-300 rounded text-sm"
              required
            />
          </div>

          <div class="mb-3">
            <label class="block mb-1 font-medium text-sm">Giá gốc</label>
            <input
              v-model.number="editingProduct.base_price"
              type="number"
              min="0"
              class="w-full border border-gray-300 rounded p-1 text-sm"
              required
            />
          </div>

          <div class="mb-3">
            <label class="block mb-1 font-medium text-sm">Giá tiền</label>
            <input
              v-model.number="editingProduct.discount_price"
              type="number"
              min="0"
              class="w-full border border-gray-300 rounded p-1 text-sm"
              required
            />
          </div>

          <div class="mb-3">
            <label class="block mb-1 font-medium text-sm">Loại thú cưng</label>
            <input
              v-model="editingProduct.pet_type"
              type="text"
              class="w-full border border-gray-300 rounded p-1 text-sm"
            />
          </div>

          <div class="mb-3">
            <label class="block mb-1 font-medium text-sm">Hàng tồn kho</label>
            <input
              v-model.number="editingProduct.stock"
              type="number"
              min="0"
              class="w-full border border-gray-300 rounded p-1 text-sm"
            />
          </div>

          <div class="mb-3">
            <label class="block mb-1 font-medium text-sm">Mô tả sản phẩm</label>
            <textarea
              v-model="editingProduct.description"
              rows="3"
              class="w-full border border-gray-300 rounded p-2 text-sm"
            ></textarea>
          </div>

          <div class="mb-3">
            <label class="block mb-1 font-medium text-sm">Trạng thái hoạt động</label>
            <div class="flex items-center gap-4">
              <label class="inline-flex items-center text-sm">
                <input
                  type="radio"
                  :value="1"
                  v-model.number="editingProduct.is_active"
                  class="form-radio"
                />
                <span class="ml-2 select-none">Có</span>
              </label>

              <label class="inline-flex items-center text-sm">
                <input
                  type="radio"
                  :value="0"
                  v-model.number="editingProduct.is_active"
                  class="form-radio"
                />
                <span class="ml-2 select-none">Không</span>
              </label>
            </div>
          </div>

          <div class="flex justify-end gap-3 mt-4">
            <button
              type="button"
              @click="closeEditPopup"
              class="px-4 py-2 border rounded hover:bg-gray-200 text-sm"
            >
              Hủy
            </button>
            <button
              type="submit"
              class="px-4 py-2 bg-[#fd7e14] text-white rounded hover:bg-[#e26a00] text-sm"
            >
              Lưu
            </button>
          </div>
        </form>
      </div>
    </div>
    <!-- Popup Create -->
    <div
      v-if="creatingProduct"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
      <div class="bg-white rounded p-6 w-[420px] max-w-full">
        <h3 class="text-lg font-semibold mt-4 text-center">Thêm sản phẩm mới</h3>
        <form @submit.prevent="createProduct">
          <div class="mb-1">
            <label class="block mb-1 font-medium text-sm">Tên sản phẩm</label>
            <input
              v-model="creatingProduct.product_name"
              type="text"
              class="w-full border border-gray-300 rounded text-sm"
              required
            />
          </div>

          <div class="mb-1">
            <label class="block mb-1 font-medium text-sm">Giá gốc</label>
            <input
              v-model.number="creatingProduct.base_price"
              type="number"
              min="0"
              class="w-full border border-gray-300 rounded text-sm"
              required
            />
          </div>
          <div class="mb-1">
            <label class="block mb-1 font-medium text-sm">Giá tiền</label>
            <input
              v-model.number="creatingProduct.discount_price"
              type="number"
              min="0"
              class="w-full border border-gray-300 rounded text-sm"
              required
            />
          </div>
          <div class="mb-1">
            <label class="block mb-1 font-medium text-sm">Loại thú cưng</label>
            <input
              v-model="creatingProduct.pet_type"
              type="text"
              class="w-full border border-gray-300 rounded text-sm"
            />
          </div>

          <div class="mb-1">
            <label class="block mb-1 font-medium text-sm">Hàng tồn kho</label>
            <input
              v-model.number="creatingProduct.stock"
              type="number"
              min="0"
              class="w-full border border-gray-300 rounded text-sm"
            />
          </div>
          <div class="mb-1">
            <label class="block mb-1 font-medium text-sm">Danh mục sản phẩm</label>
            <select
              v-model="creatingProduct.category_id"
              class="w-full border border-gray-300 rounded p-1 text-sm"
            >
              <option value="" disabled>-- Chọn danh mục --</option>
              <option
                v-for="category in categories"
                :key="category.category_id"
                :value="category.category_id"
              >
                {{ category.category_name }}
              </option>
            </select>
          </div>

          <div class="mb-1">
            <label class="block mb-1 font-medium text-sm">Ảnh</label>
            <input
              type="file"
              @change="handleImageUpload"
              accept="image/*"
              class="w-full border border-gray-300 rounded p-1 text-sm"
            />
          </div>

          <div class="mb-1">
            <label class="block mb-1 font-medium text-sm">Mô tả sản phẩm</label>
            <textarea
              v-model="creatingProduct.description"
              rows="3"
              class="w-full border border-gray-300 rounded p-2 text-sm"
            ></textarea>
          </div>

          <div class="mb-1">
            <label class="block mb-1 font-medium text-sm">Trạng thái hoạt động</label>
            <div class="flex items-center gap-4">
              <label class="inline-flex items-center text-sm">
                <input
                  type="radio"
                  value="1"
                  v-model="creatingProduct.is_active"
                  class="form-radio"
                />
                <span class="ml-2 select-none">Có</span>
              </label>
              <label class="inline-flex items-center text-sm">
                <input
                  type="radio"
                  value="0"
                  v-model="creatingProduct.is_active"
                  class="form-radio"
                />
                <span class="ml-2 select-none">Không</span>
              </label>
            </div>
          </div>

          <div class="flex justify-end gap-3 mb-3">
            <button
              type="button"
              @click="closeCreatePopup"
              class="px-4 py-2 border rounded hover:bg-gray-200 text-sm"
            >
              Hủy
            </button>
            <button
              type="submit"
              class="px-4 py-2 bg-[#fd7e14] text-white rounded hover:bg-[#e26a00] text-sm"
            >
              Thêm
            </button>
          </div>
        </form>
      </div>
    </div>
    <!-- Phân trang -->
    <div v-if="filteredAndSortedProducts.length > 0" class="flex justify-center items-center gap-4 mt-4">
      <BasePagination
        :current-page="currentPage"
        :total-pages="totalPages"
        @prev="currentPage > 1 && currentPage--"
        @next="currentPage < totalPages && currentPage++"
        @page="currentPage = $event"
      />
    </div>
  </div>
</template>

<script>
import BasePagination from '@/components/BasePagination.vue'

export default {
  components: {
    BasePagination
  },
  data() {
    return {
      searchTerm: '',
      products: [],
      categories: [],
      currentSort: 'product_id',
      currentSortDir: 'asc',
      editingProduct: null,
      loading: false,
      error: null,
      creatingProduct: null,
      currentPage: 1,
      pageSize: 8
    }
  },
  async mounted() {
    this.fetchProducts()
    this.fetchCategories()
  },
  computed: {
    filteredAndSortedProducts() {
      let filtered = this.products.filter(item =>
        item.product_name.toLowerCase().includes(this.searchTerm.toLowerCase())
      )
      return filtered.slice().sort((a, b) => {
        let modifier = this.currentSortDir === 'asc' ? 1 : -1
        let aVal = a[this.currentSort]
        let bVal = b[this.currentSort]

        if (!isNaN(parseFloat(aVal)) && !isNaN(parseFloat(bVal))) {
          aVal = parseFloat(aVal)
          bVal = parseFloat(bVal)
          if (aVal < bVal) return -1 * modifier
          if (aVal > bVal) return 1 * modifier
          return 0
        }

        aVal = (aVal || '').toString().toLowerCase()
        bVal = (bVal || '').toString().toLowerCase()
        if (aVal < bVal) return -1 * modifier
        if (aVal > bVal) return 1 * modifier
        return 0
      })
    },
    paginatedProducts() {
      const start = (this.currentPage - 1) * this.pageSize
      return this.filteredAndSortedProducts.slice(start, start + this.pageSize)
    },

    totalPages() {
      return Math.ceil(this.filteredAndSortedProducts.length / this.pageSize)
    }
  },
  methods: {
    getCookie(name) {
      const value = `; ${document.cookie}`
      const parts = value.split(`; ${name}=`)
      if (parts.length === 2) return parts.pop().split(';').shift()
      return null
    },
    async fetchProducts() {
      this.loading = true
      try {
        const res = await fetch('http://localhost:8000/api/v1/products', {
          credentials: 'include'
        })
        if (!res.ok) throw new Error('Failed to fetch products')
        this.products = await res.json()
      } catch (err) {
        this.error = err.message
        console.error(err)
      } finally {
        this.loading = false
      }
    },
    formatCurrency(value) {
      if (typeof value === 'number') {
        return value.toLocaleString('vi-VN') + 'đ'
      }
      return String(value) + ' ' + 'đ'
    },
    sortBy(column) {
      if (this.currentSort === column) {
        this.currentSortDir = this.currentSortDir === 'asc' ? 'desc' : 'asc'
      } else {
        this.currentSort = column
        this.currentSortDir = 'asc'
      }
    },
    openEditPopup(product) {
      this.editingProduct = { ...product }
      console.log(this.editingProduct)
    },
    closeEditPopup() {
      this.editingProduct = null
    },
    async saveEdit() {
      if (!this.editingProduct) return
      this.editingProduct.is_active = this.editingProduct.is_active ? 1 : 0

      try {
        this.loading = true
        const res = await fetch(
          `http://localhost:8000/api/v1/products/${this.editingProduct.product_id}`,
          {
            method: 'PUT',
            credentials: 'include',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(this.editingProduct)
          }
        )
        if (!res.ok) {
          const errorText = await res.text()
          throw new Error(`Lỗi khi tạo sản phẩm: ${errorText}`)
        }

        const updatedProduct = await res.json()
        await this.fetchProducts()
        await this.fetchCategories()

        const index = this.products.findIndex(p => p.product_id === updatedProduct.product_id)
        if (index !== -1) {
          this.$set(this.products, index, updatedProduct)
        }

        this.editingProduct = null
      } catch (error) {
        alert(error.message)
      } finally {
        this.loading = false
      }
    },
    openCreatePopup() {
      const newId = this.products.length ? Math.max(...this.products.map(p => p.product_id)) + 1 : 1
      this.creatingProduct = {
        product_id: newId,
        product_name: '',
        base_price: 0,
        price: 0,
        pet_type: '',
        stock: 0,
        description: '',
        is_active: 1
      }
    },
    closeCreatePopup() {
      this.creatingProduct = null
    },
    async createProduct() {
      try {
        this.loading = true
        const res = await fetch('http://localhost:8000/api/v1/products', {
          method: 'POST',
          credentials: 'include',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(this.creatingProduct)
        })
        const contentType = res.headers.get('content-type') || ''

        if (!res.ok) {
          const errorText = await res.text()
          console.log('response text', errorText)
          throw new Error(`Lỗi khi tạo sản phẩm: ${errorText}`)
        }

        if (!contentType.includes('application/json')) {
          const text = await res.text()
          throw new Error(`Phản hồi không phải JSON: ${text}`)
        }

        const data = await res.json()

        // Lấy product mới từ data.success (theo cấu trúc server trả về)
        const newProduct = data.success

        this.products.push(newProduct)

        // Reset form tạo mới
        this.creatingProduct = {
          product_name: '',
          category_id: '',
          description: '',
          pet_type: '',
          base_price: '',
          discount_price: '',
          stock: '',
          image_url: '',
          is_active: 1
        }

        this.closeCreatePopup()
      } catch (error) {
        alert(error.message)
        console.error(error)
      } finally {
        this.loading = false
      }
    },
    async fetchCategories() {
      this.loadingCategories = true
      try {
        const res = await fetch('http://localhost:8000/api/v1/categories')
        if (!res.ok) throw new Error('Lỗi khi lấy danh sách categories')
        this.categories = await res.json()
      } catch (error) {
        this.errorCategories = error.message
        console.error(error)
      } finally {
        this.loadingCategories = false
      }
    },

    deleteProduct() {
      if (confirm('Bạn có chắc muốn xóa sản phẩm này?')) {
        //BE todo
      }
    },
    handleImageUpload(event) {
      const file = event.target.files[0]
      if (file) {
        const reader = new FileReader()
        reader.onload = e => {
          this.creatingProduct.image_url = e.target.result
        }
        reader.readAsDataURL(file)

        this.creatingProduct.image_file = file
      }
    },
    goToPage(page) {
      if (page < 1) page = 1
      if (page > this.totalPages) page = this.totalPages
      this.currentPage = page
    },

    prevPage() {
      this.goToPage(this.currentPage - 1)
    },

    nextPage() {
      this.goToPage(this.currentPage + 1)
    }
  }
}
</script>
