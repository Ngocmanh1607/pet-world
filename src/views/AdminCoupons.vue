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
  cursor: pointer; /* Thêm con trỏ để người dùng biết có thể click */
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
      <h2 class="mx-1 text-zinc-700 text-xl">Danh sách mã khuyến mãi</h2>
      <button class="btn mx-1 custom-btn" @click="openCreatePopup">
        + Thêm mã khuyến mãi
      </button>
    </div>

    <!-- Thanh Search -->
    <div class="w-container mx-auto mb-3" style="width: 94%">
      <input
        type="text"
        v-model="searchTerm"
        placeholder="Tìm kiếm sản phẩm theo mã khuyến mãi..."
        class="form-control p-2 border border-gray-300 rounded w-full"
      />
    </div>

    <!-- Table -->
    <div class="table-responsive mx-auto" style="width: 94%">
      <table class="table custom-table" style="width: 100%">
        <thead>
          <tr>
            <th @click="sortBy('promotion_id')" style="cursor: pointer">
              ID khuyến mãi
            </th>
            <th @click="sortBy('code')" style="cursor: pointer">
              Mã khuyến mãi
            </th>
            <th @click="sortBy('discount_percent')" style="cursor: pointer">
              % giảm giá
            </th>
            <th @click="sortBy('total_voucher')" style="cursor: pointer">
              Số lượng
            </th>
            <th @click="sortBy('used_voucher')" style="cursor: pointer">
              Đã sử dụng
            </th>
            <th @click="sortBy('start_date')" style="cursor: pointer">
              Ngày bắt đầu
            </th>
            <th @click="sortBy('end_date')" style="cursor: pointer">
              Ngày kết thúc
            </th>
            <th class="text-center">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="item in filteredAndSortedProducts"
            :key="item.promotion_id"
          >
            <td>
              <strong>{{ item.promotion_id }}</strong>
            </td>
            <td>
              <div class="fw-bold">{{ item.code }}</div>
            </td>
            <td class="text-xl font-semibold">{{ item.discount_percent }} %</td>
            <td>{{ item.total_voucher }}</td>
            <td>{{ item.used_voucher }}</td>
            <td>{{ item.start_date }}</td>
            <td>{{ item.end_date }}</td>
            <td class="text-center">
              <i
                class="fas fa-edit action-icon me-2"
                @click="openEditPopup(item)"
                style="cursor: pointer"
              ></i>

              <i
                class="fas fa-trash-alt action-icon"
                @click="deleteProduct(item.promotion_id)"
              ></i>
            </td>
          </tr>
          <tr v-if="filteredAndSortedProducts.length === 0">
            <td colspan="7" class="text-center text-muted">
              Không tìm thấy sản phẩm phù hợp.
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
        <h3 class="text-lg font-semibold mb-2 text-center">
          Thông tin mã khuyến mãi
        </h3>
        <form @submit.prevent="saveEdit">
          <div class="mb-2">
            <label class="block font-medium text-sm">Mã khuyến mãi</label>
            <input
              v-model="editingProduct.code"
              type="text"
              class="w-full border border-gray-300 rounded text-sm"
              required
            />
          </div>

          <div class="mb-2">
            <label class="block mb-1 font-medium text-sm"
              >Mô tả khuyến mãi</label
            >
            <textarea
              v-model="editingProduct.description"
              rows="3"
              class="w-full border border-gray-300 rounded p-2 text-sm"
            ></textarea>
          </div>

          <div class="mb-2">
            <label class="block mb-1 font-medium text-sm">% giảm giá</label>
            <input
              v-model.number="editingProduct.discount_percent"
              type="number"
              min="0"
              class="w-full border border-gray-300 rounded p-1 text-sm"
              required
            />
          </div>

          <div class="mb-2">
            <label class="block mb-1 font-medium text-sm">Số lượng</label>
            <input
              v-model="editingProduct.total_voucher"
              type="text"
              class="w-full border border-gray-300 rounded p-1 text-sm"
            />
          </div>

          <div class="mb-2">
            <label class="block mb-1 font-medium text-sm">Đã sử dụng</label>
            <input
              v-model="editingProduct.used_voucher"
              type="text"
              class="w-full border border-gray-300 rounded p-1 text-sm"
            />
          </div>
          <div class="mb-2">
            <label class="block mb-1 font-medium text-sm">Ngày bắt đầu</label>
            <input
              v-model="editingProduct.start_date"
              type="date"
              class="w-full border border-gray-300 rounded p-1 text-sm"
            />
          </div>

          <div class="mb-2">
            <label class="block mb-1 font-medium text-sm">Ngày kết thúc</label>
            <input
              v-model="editingProduct.end_date"
              type="date"
              class="w-full border border-gray-300 rounded p-1 text-sm"
            />
          </div>

          <div class="mb-2">
            <label class="block mb-1 font-medium text-sm"
              >Trạng thái hoạt động</label
            >
            <div class="flex items-center gap-4">
              <label class="inline-flex items-center text-sm">
                <input
                  type="radio"
                  value="1"
                  v-model="editingProduct.is_active"
                  class="form-radio"
                />
                <span class="ml-2 select-none">Có</span>
              </label>
              <label class="inline-flex items-center text-sm">
                <input
                  type="radio"
                  value="0"
                  v-model="editingProduct.is_active"
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
        <h3 class="text-lg font-semibold mb-4 text-center">
          Thêm mã khuyến mãi mới
        </h3>
        <form @submit.prevent="createProduct">
          <div class="mb-2">
            <label class="block mb-1 font-medium text-sm">Mã khuyến mãi</label>
            <input
              v-model="creatingProduct.code"
              type="text"
              class="w-full border border-gray-300 rounded text-sm"
              required
            />
          </div>

          <div class="mb-2">
            <label class="block mb-1 font-medium text-sm"
              >Mô tả khuyến mãi</label
            >
            <textarea
              v-model="creatingProduct.description"
              rows="3"
              class="w-full border border-gray-300 rounded p-2 text-sm"
            ></textarea>
          </div>

          <div class="mb-2">
            <label class="block mb-1 font-medium text-sm">% giảm giá</label>
            <input
              v-model.number="creatingProduct.discount_percent"
              type="number"
              min="0"
              class="w-full border border-gray-300 rounded p-1 text-sm"
              required
            />
          </div>

          <div class="mb-2">
            <label class="block mb-1 font-medium text-sm">Số lượng</label>
            <input
              v-model="creatingProduct.total_voucher"
              type="text"
              class="w-full border border-gray-300 rounded p-1 text-sm"
            />
          </div>

          <div class="mb-2">
            <label class="block mb-1 font-medium text-sm">Đã sử dụng</label>
            <input
              v-model="creatingProduct.used_voucher"
              type="text"
              class="w-full border border-gray-300 rounded p-1 text-sm"
            />
          </div>

          <div class="mb-2">
            <label class="block mb-1 font-medium text-sm">Ngày bắt đầu</label>
            <input
              v-model="creatingProduct.start_date"
              type="date"
              class="w-full border border-gray-300 rounded p-1 text-sm"
            />
          </div>

          <div class="mb-2">
            <label class="block mb-1 font-medium text-sm">Ngày kết thúc</label>
            <input
              v-model="creatingProduct.end_date"
              type="date"
              class="w-full border border-gray-300 rounded p-1 text-sm"
            />
          </div>

          <div class="mb-2">
            <label class="block mb-1 font-medium text-sm"
              >Trạng thái hoạt động</label
            >
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

          <div class="flex justify-end gap-3 mt-4">
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
  </div>
</template>

<script>
export default {
  data() {
    return {
      searchTerm: '',
      products: [],
      currentSort: 'promotion_id',
      currentSortDir: 'asc',
      editingProduct: null,
      loading: false,
      error: null,
      creatingProduct: null,
    };
  },
  async mounted() {
    this.fetchProducts();
  },
  computed: {
    filteredAndSortedProducts() {
      let filtered = this.products.filter((item) =>
        item.code.toLowerCase().includes(this.searchTerm.toLowerCase())
      );
      return filtered.slice().sort((a, b) => {
        let modifier = this.currentSortDir === 'asc' ? 1 : -1;
        let aVal = a[this.currentSort];
        let bVal = b[this.currentSort];

        if (!isNaN(parseFloat(aVal)) && !isNaN(parseFloat(bVal))) {
          aVal = parseFloat(aVal);
          bVal = parseFloat(bVal);
          if (aVal < bVal) return -1 * modifier;
          if (aVal > bVal) return 1 * modifier;
          return 0;
        }

        aVal = (aVal || '').toString().toLowerCase();
        bVal = (bVal || '').toString().toLowerCase();
        if (aVal < bVal) return -1 * modifier;
        if (aVal > bVal) return 1 * modifier;
        return 0;
      });
    },
  },
  methods: {
    getCookie(name) {
      const value = `; ${document.cookie}`;
      const parts = value.split(`; ${name}=`);
      if (parts.length === 2) return parts.pop().split(';').shift();
      return null;
    },
    async fetchProducts() {
      this.loading = true;
      try {
        const res = await fetch('http://localhost:8000/api/v1/promotions');
        if (!res.ok) throw new Error('Failed to fetch products');
        this.products = await res.json();
      } catch (err) {
        this.error = err.message;
        console.error(err);
      } finally {
        this.loading = false;
      }
    },
    formatCurrency(value) {
      if (typeof value === 'number') {
        return value.toLocaleString('vi-VN') + 'đ';
      }
      return value;
    },
    sortBy(column) {
      if (this.currentSort === column) {
        this.currentSortDir = this.currentSortDir === 'asc' ? 'desc' : 'asc';
      } else {
        this.currentSort = column;
        this.currentSortDir = 'asc';
      }
    },
    openEditPopup(product) {
      this.editingProduct = { ...product };
    },

    closeEditPopup() {
      this.editingProduct = null;
    },
    async saveEdit() {
      if (!this.editingProduct) return;
      this.editingProduct.is_active = this.editingProduct.is_active ? 1 : 0;

      try {
        this.loading = true;
        const token = this.getCookie('token');
        const res = await fetch(
          `http://localhost:8000/api/v1/promotions/${this.editingProduct.promotion_id}`,
          {
            method: 'PUT',
            headers: {
              'Content-Type': 'application/json',
              Authorization: `Bearer ${token}`,
            },
            body: JSON.stringify(this.editingProduct),
          }
        );
        if (!res.ok) throw new Error('Lỗi cập nhật');

        const updatedProduct = await res.json();

        const index = this.products.findIndex(
          (p) => p.promotion_id === updatedProduct.promotion_id
        );
        if (index !== -1) {
          this.$set(this.products, index, updatedProduct);
        }

        this.editingProduct = null;
        this.fetchProducts();
      } catch (error) {
        alert(error.message);
      } finally {
        this.loading = false;
      }
    },

    deleteProduct() {
      if (confirm('Bạn có chắc muốn xóa sản phẩm này?')) {
        alert('BE todo');
      }
    },
    openCreatePopup() {
      const newId = this.products.length
        ? Math.max(...this.products.map((p) => p.promotion_id)) + 1
        : 1;
      this.creatingProduct = {
        promotion_id: newId,
        code: '',
        description: '',
        discount_percent: 0,
        total_voucher: '',
        used_voucher: 0,
        start_date: '',
        end_date: '',
        is_active: 1,
      };
    },
    closeCreatePopup() {
      this.creatingProduct = null;
    },
    async createProduct() {
      try {
        this.loading = true;
        const token = this.getCookie('token');
        const res = await fetch('http://localhost:8000/api/v1/promotions', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            Authorization: `Bearer ${token}`,
          },
          body: JSON.stringify(this.creatingProduct),
        });
        const contentType = res.headers.get('content-type') || '';

        if (!res.ok) {
          const errorText = await res.text();
          throw new Error(`Lỗi khi tạo sản phẩm: ${errorText}`);
        }

        if (!contentType.includes('application/json')) {
          const text = await res.text();
          throw new Error(`Phản hồi không phải JSON: ${text}`);
        }

        const data = await res.json();

        const newProduct = data.success;

        this.products.push(newProduct);

        // Reset form tạo mới
        this.creatingProduct = {
          promotion_id: '',
          code: '',
          description: '',
          discount_percent: '',
          total_voucher: '',
          used_voucher: '',
          start_date: '',
          end_date: '',
          is_active: 0,
        };

        this.closeCreatePopup();
        this.fetchProducts();
      } catch (error) {
        alert(error.message);
        console.error(error);
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>
