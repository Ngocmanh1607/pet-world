<template>
  <div class="min-h-screen bg-gray-50 pt-8">
    <!-- Tiêu đề trang -->
    <div class="text-center text-3xl font-bold mt-8 mb-8 text-[#5a3a1b] font-quicksand tracking-wide">
      Danh sách đơn hàng
    </div>
    <div class="max-w-5xl mx-auto px-4">
      <!-- Phần tìm kiếm và lọc -->
      <div class="bg-white p-4 rounded-lg shadow mb-4">
        <!-- Ô tìm kiếm -->
        <div class="mb-4">
          <SearchBar
            v-model="searchQuery"
            :placeholder="`Tìm kiếm theo mã đơn hàng, ngày đặt, tên hoặc email...`"
            @input="applyFilters"
          />
        </div>
        <!-- Bộ lọc trạng thái -->
        <div class="flex gap-4">
          <!-- Lọc theo trạng thái đơn hàng -->
          <select v-model="statusFilter" @change="applyFilters" class="flex-1 p-2 border border-gray-300 rounded">
            <option value="">Tất cả trạng thái đơn hàng</option>
            <option value="pending">Chờ xác nhận</option>
            <option value="processing">Đang xử lý</option>
            <option value="shipped">Đang giao hàng</option>
            <option value="delivered">Đã nhận hàng</option>
            <option value="cancelled">Đã hủy</option>
          </select>
          <!-- Lọc theo trạng thái thanh toán -->
          <select v-model="paymentStatusFilter" @change="applyFilters" class="flex-1 p-2 border border-gray-300 rounded">
            <option value="">Tất cả trạng thái thanh toán</option>
            <option value="pending">Chờ thanh toán</option>
            <option value="paid">Đã thanh toán</option>
            <option value="failed">Thanh toán thất bại</option>
          </select>
        </div>
      </div>

      <!-- Bảng danh sách đơn hàng -->
      <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full border-collapse">
          <!-- Header của bảng -->
          <thead>
            <tr>
              <th class="bg-gray-100 font-semibold p-4 text-left">
                ID đơn hàng
              </th>
              <th class="bg-gray-100 font-semibold p-4 text-left">Ngày đặt</th>
              <th class="bg-gray-100 font-semibold p-4 text-left">
                Trạng thái đơn hàng
              </th>
              <th class="bg-gray-100 font-semibold p-4 text-left">
                Tổng giá trị
              </th>
              <th class="bg-gray-100 font-semibold p-4 text-left">
                Phương thức thanh toán
              </th>
              <th class="bg-gray-100 font-semibold p-4 text-left">
                Trạng thái thanh toán
              </th>
              <th class="bg-gray-100 font-semibold p-4 text-left">Thao tác</th>
            </tr>
          </thead>
          <!-- Body của bảng -->
          <tbody>
            <tr
              v-for="order in paginatedOrders"
              :key="order.id"
              class="even:bg-gray-50"
            >
              <td class="p-4">{{ order.id }}</td>
              <td class="p-4">{{ formatDate(order.created_at) }}</td>
              <!-- Hiển thị trạng thái đơn hàng với màu sắc tương ứng -->
              <td class="p-4">
                <span
                  :class="[
                    'px-2 py-1 rounded text-xs font-semibold',
                    order.status === 'pending' &&
                      'bg-yellow-100 text-yellow-800',
                    order.status === 'processing' &&
                      'bg-blue-100 text-blue-800',
                    order.status === 'shipped' && 'bg-green-100 text-green-800',
                    order.status === 'delivered' &&
                      'bg-emerald-100 text-emerald-800',
                    order.status === 'cancelled' && 'bg-red-100 text-red-800',
                  ]"
                >
                  {{ getStatusText(order.status) }}
                </span>
              </td>
              <td class="p-4">{{ formatPrice(order.total_amount) }}</td>
              <td class="p-4">{{ getPaymentMethodText(order.payment_method) }}</td>
              <!-- Hiển thị trạng thái thanh toán với màu sắc tương ứng -->
              <td class="p-4">
                {{ getPaymentMethodText(order.payment_method) }}
              </td>
              <td class="p-4">
                <span
                  :class="[
                    'px-2 py-1 rounded text-xs font-semibold',
                    order.payment_status === 'pending' &&
                      'bg-yellow-100 text-yellow-800',
                    order.payment_status === 'paid' &&
                      'bg-green-100 text-green-800',
                    order.payment_status === 'failed' &&
                      'bg-red-100 text-red-800',
                  ]"
                >
                  {{ getPaymentStatusText(order.payment_status) }}
                </span>
              </td>
              <!-- Nút chỉnh sửa đơn hàng -->
              <td class="p-4 flex gap-2">
                <button @click="editOrder(order.id)" class="p-1 text-blue-600 hover:opacity-70" title="Sửa đơn hàng">
                  <i class="fas fa-edit"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Phân trang -->
      <div class="flex justify-center items-center gap-4 mt-4">
        <BasePagination
          :current-page="currentPage"
          :total-pages="totalPages"
          @prev="currentPage > 1 && (currentPage--, applyFilters())"
          @next="currentPage < totalPages && (currentPage++, applyFilters())"
        />
      </div>
    </div>

    <!-- Modal chỉnh sửa đơn hàng -->
    <div v-if="showEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
      <div class="bg-white rounded-lg w-full max-w-xl max-h-[90vh] overflow-y-auto">
        <!-- Header của modal -->
        <div class="flex justify-between items-center border-b p-4">
          <h2 class="text-lg font-bold">
            Xác nhận đơn hàng #{{ editingOrder.id }}
          </h2>
          <button
            class="text-2xl text-gray-500 hover:text-gray-700"
            @click="showEditModal = false"
          >
            &times;
          </button>
        </div>
        <!-- Body của modal -->
        <div class="p-4">
          <!-- Thông tin cơ bản của đơn hàng -->
          <div class="mb-6">
            <div class="flex mb-3">
              <label class="w-40 font-semibold text-gray-600">Khách hàng:</label
              ><span>{{ editingOrder.customer_name }}</span>
            </div>
            <div class="flex mb-3">
              <label class="w-40 font-semibold text-gray-600">Email:</label
              ><span>{{ editingOrder.customer_email }}</span>
            </div>
            <div class="flex mb-3">
              <label class="w-40 font-semibold text-gray-600">Ngày đặt:</label
              ><span>{{ formatDate(editingOrder.created_at) }}</span>
            </div>
            <div class="flex mb-3">
              <label class="w-40 font-semibold text-gray-600"
                >Tổng giá trị:</label
              ><span>{{ formatPrice(editingOrder.total_amount) }}</span>
            </div>
            <div class="flex mb-3">
              <label class="w-40 font-semibold text-gray-600"
                >Phương thức thanh toán:</label
              ><span>{{
                getPaymentMethodText(editingOrder.payment_method)
              }}</span>
            </div>
          </div>
          <!-- Form chỉnh sửa trạng thái -->
          <div class="bg-gray-50 p-4 rounded mb-4">
            <!-- Dropdown trạng thái đơn hàng -->
            <div class="mb-4">
              <label class="block font-semibold text-gray-600 mb-2">Trạng thái đơn hàng:</label>
              <select v-model="editingOrder.status" class="w-full p-2 border border-gray-300 rounded">
                <!-- Luôn hiển thị tất cả các trạng thái, nhưng chỉ cho phép chọn từ pending sang processing -->
                <option value="pending">Chờ xác nhận</option>
                <option value="processing" :disabled="editingOrder.status !== 'pending'">Đang xử lý</option>
                <option value="shipped" :disabled="editingOrder.status === 'pending'">Đang giao hàng</option>
                <option value="delivered" :disabled="editingOrder.status === 'pending'">Đã nhận hàng</option>
                <option value="cancelled" :disabled="editingOrder.status === 'pending'">Đã hủy</option>
              </select>
            </div>
            <!-- Dropdown trạng thái thanh toán -->
            <div>
              <label class="block font-semibold text-gray-600 mb-2"
                >Trạng thái thanh toán:</label
              >
              <select
                v-model="editingOrder.payment_status"
                class="w-full p-2 border border-gray-300 rounded"
              >
                <option value="pending">Chờ thanh toán</option>
                <option value="paid">Đã thanh toán</option>
                <option value="failed">Thanh toán thất bại</option>
              </select>
            </div>
          </div>
        </div>
        <!-- Footer của modal -->
        <div class="flex justify-end gap-4 border-t p-4">
          <button
            class="px-4 py-2 rounded border font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200"
            @click="showEditModal = false"
          >
            Hủy
          </button>
          <button
            class="px-4 py-2 rounded font-semibold text-white bg-blue-500 hover:bg-blue-700"
            @click="saveOrderChanges"
          >
            Lưu thay đổi
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// Import các thư viện và component cần thiết
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import BasePagination from '@/components/BasePagination.vue'
import SearchBar from '@/components/AdminSearchBar.vue'

// Cấu hình axios instance
const axiosInstance = axios.create({
  baseURL: process.env.VUE_APP_API_URL || 'http://localhost:8000/api/v1',
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
})

// Thêm interceptor để xử lý request
axiosInstance.interceptors.request.use(
  config => {
    const token = localStorage.getItem('admin_token')
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  },
  error => {
    return Promise.reject(error)
  }
)

// Thêm interceptor để xử lý response
axiosInstance.interceptors.response.use(
  response => response,
  error => {
    if (error.response?.status === 401) {
      localStorage.removeItem('admin_token')
      window.location.href = '/admin/login'
      return Promise.reject(error)
    }
    return Promise.reject(error)
  }
)

// Helper function để xử lý lỗi
const handleError = (error, defaultMessage = 'Có lỗi xảy ra') => {
  console.error(error)
  if (error.response?.data?.message) {
    throw new Error(error.response.data.message)
  }
  throw new Error(defaultMessage)
}

// Order Service
const orderService = {
  getAll: async (params) => {
    try {
      const response = await axiosInstance.get('/orders', { params })
      return response.data
    } catch (error) {
      handleError(error, 'Không thể tải danh sách đơn hàng')
    }
  },

  getById: async (id) => {
    try {
      const response = await axiosInstance.get(`/orders/${id}`)
      return response.data
    } catch (error) {
      handleError(error, 'Không thể tải thông tin đơn hàng')
    }
  },

  update: async (id, data) => {
    try {
      const response = await axiosInstance.put(`/orders/${id}`, data)
      return response.data
    } catch (error) {
      handleError(error, 'Không thể cập nhật đơn hàng')
    }
  }
}

// =====================
// Biến trạng thái reactive
// =====================
// Danh sách đơn hàng gốc lấy từ API
const orders = ref([])
// Danh sách đơn hàng sau khi lọc, tìm kiếm, sắp xếp
const filteredOrders = ref([])
// Trạng thái loading khi gọi API
const loading = ref(false)
// Biến lưu lỗi nếu có
const error = ref(null)
// Giá trị tìm kiếm từ ô input
const searchQuery = ref('')
// Lọc theo trạng thái đơn hàng
const statusFilter = ref('')
// Lọc theo trạng thái thanh toán
const paymentStatusFilter = ref('')
// Trường dùng để sắp xếp (mặc định là ngày tạo)
const sortField = ref('created_at')
// Chiều sắp xếp (mặc định giảm dần)
const sortDirection = ref('desc')
// Phân trang: trang hiện tại
const currentPage = ref(1)
// Số đơn hàng mỗi trang
const itemsPerPage = ref(10)
// Modal chỉnh sửa đơn hàng
const showEditModal = ref(false)
// Đơn hàng đang chỉnh sửa
const editingOrder = ref({})

// =====================
// Các computed property
// =====================
// Tính tổng số trang dựa trên số lượng đơn hàng đã lọc
const totalPages = computed(() => Math.ceil(filteredOrders.value.length / itemsPerPage.value))
// Lấy danh sách đơn hàng cho trang hiện tại
const paginatedOrders = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return filteredOrders.value.slice(start, end);
});

// =====================
// Hàm lọc, tìm kiếm, sắp xếp
// =====================
// Hàm này sẽ lọc, tìm kiếm và sắp xếp lại danh sách đơn hàng mỗi khi có thay đổi
const applyFilters = () => {
  let filtered = [...orders.value]
  // --- Tìm kiếm ---
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase().trim();
    filtered = filtered.filter((order) => {
      // Gom các trường cần tìm kiếm vào mảng
      const fields = [
        order.id?.toString().toLowerCase(),
        (order.created_at || order.order_date)?.toLowerCase(),
        (order.customer_name || '').toLowerCase(),
        (order.customer_email || '').toLowerCase(),
        // Ngày đặt dạng vi-VN
        order.created_at || order.order_date
          ? new Date(order.created_at || order.order_date)
              .toLocaleDateString('vi-VN')
              .toLowerCase()
          : '',
      ];
      // Nếu query xuất hiện ở bất kỳ trường nào thì giữ lại
      return fields.some((field) => field && field.includes(query));
    });
  }
  // --- Lọc theo trạng thái đơn hàng ---
  if (statusFilter.value) {
    filtered = filtered.filter((order) => order.status === statusFilter.value);
  }
  // --- Lọc theo trạng thái thanh toán ---
  if (paymentStatusFilter.value) {
    filtered = filtered.filter(
      (order) => order.payment_status === paymentStatusFilter.value
    );
  }
  // --- Sắp xếp ---
  filtered.sort((a, b) => {
    let aVal = a[sortField.value];
    let bVal = b[sortField.value];
    if (sortField.value === 'total_amount') {
      aVal = parseFloat(aVal);
      bVal = parseFloat(bVal);
    }
    if (sortDirection.value === 'asc') {
      return aVal > bVal ? 1 : -1;
    } else {
      return aVal < bVal ? 1 : -1;
    }
  })
  filteredOrders.value = filtered
  currentPage.value = 1 // Reset về trang đầu khi lọc
}

// =====================
// Các hàm format dữ liệu hiển thị
// =====================
// Định dạng ngày theo chuẩn Việt Nam
const formatDate = date => {
  return new Date(date).toLocaleDateString('vi-VN')
}
// Định dạng giá tiền VND
const formatPrice = price => {
  return new Intl.NumberFormat('vi-VN', {
    style: 'currency',
    currency: 'VND',
  }).format(price);
};

// =====================
// Hàm mapping trạng thái sang tiếng Việt
// =====================
// Chuyển trạng thái đơn hàng sang tiếng Việt
const getStatusText = status => {
  const statusMap = {
    pending: 'Chờ xác nhận',
    processing: 'Đang xử lý',
    shipped: 'Đang giao hàng',
    delivered: 'Đã nhận hàng',
    cancelled: 'Đã hủy',
    'Chờ xác nhận': 'Chờ xác nhận',
    'Đang xử lý': 'Đang xử lý',
    'Đang giao hàng': 'Đang giao hàng',
    'Đã nhận': 'Đã nhận hàng',
    'Đã hủy': 'Đã hủy'
  }
  return statusMap[status] || status
}
// Chuyển phương thức thanh toán sang tiếng Việt
const getPaymentMethodText = method => {
  const methodMap = {
    cod: 'Thanh toán khi nhận hàng',
    bank_transfer: 'Chuyển khoản ngân hàng',
    credit_card: 'Thẻ tín dụng',
    'Thanh toán khi nhận hàng': 'Thanh toán khi nhận hàng',
    'Chuyển khoản ngân hàng': 'Chuyển khoản ngân hàng',
    'Thẻ tín dụng': 'Thẻ tín dụng'
  }
  return methodMap[method] || method
}
// Chuyển trạng thái thanh toán sang tiếng Việt
const getPaymentStatusText = status => {
  const statusMap = {
    pending: 'Chờ thanh toán',
    paid: 'Đã thanh toán',
    failed: 'Thanh toán thất bại',
    'Chờ thanh toán': 'Chờ thanh toán',
    'Đã thanh toán': 'Đã thanh toán',
    'Thanh toán thất bại': 'Thanh toán thất bại',
  };
  return statusMap[status] || status;
};

// =====================
// Cập nhật trạng thái đơn hàng
// =====================
// Mở modal cập nhật trạng thái đơn hàng
const editOrder = async orderId => {
  try {
    loading.value = true
    // Tìm đơn hàng theo id trong danh sách đã lấy về
    const order = orders.value.find(o => o.id === orderId)
    if (order) {
      editingOrder.value = { ...order };
      showEditModal.value = true;
    }
  } catch (err) {
    error.value = 'Không thể tải thông tin đơn hàng';
    console.error(err);
  } finally {
    loading.value = false;
  }
}
// Lưu thay đổi trạng thái đơn hàng
const saveOrderChanges = async () => {
  try {
    loading.value = true
    // Gọi API cập nhật trạng thái đơn hàng
    const updatedOrder = await orderService.update(editingOrder.value.id, {
      status: editingOrder.value.status,
      payment_status: editingOrder.value.payment_status
    })
    if (updatedOrder) {
      // Cập nhật lại đơn hàng trong danh sách
      const index = orders.value.findIndex(order => order.id === editingOrder.value.id)
      if (index !== -1) {
        orders.value[index] = { ...editingOrder.value };
        applyFilters();
        showEditModal.value = false;
      }
    }
  } catch (err) {
    error.value = err.message;
    console.error(err);
  } finally {
    loading.value = false;
  }
};

// =====================
// Load dữ liệu ban đầu
// =====================
onMounted(async () => {
  try {
    loading.value = true
    // Gọi API lấy danh sách đơn hàng khi trang được tải
    const response = await axios.get('/orders')
    orders.value = response.data
    applyFilters()
  } catch (err) {
    error.value = 'Không thể tải danh sách đơn hàng';
    console.error(err);
  } finally {
    loading.value = false;
  }
});
</script>
