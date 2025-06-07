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
          <SearchBar v-model="searchQuery" placeholder="Tìm kiếm theo mã đơn hàng, tên khách hàng..."
            @input="applyFilters" />
        </div>
        <!-- Bộ lọc trạng thái -->
        <div class="flex gap-4">
          <!-- Lọc theo trạng thái đơn hàng -->
          <select v-model="statusFilter" @change="applyFilters" class="flex-1 p-2 border border-gray-300 rounded">
            <option value="">Tất cả trạng thái đơn hàng</option>
            <option value="0">Đã hủy</option>
            <option value="1">Đang xử lý</option>
            <option value="2">Đang giao hàng</option>
            <option value="3">Đã hoàn thành</option>
          </select>
          <!-- Lọc theo trạng thái thanh toán -->
          <select v-model="paymentStatusFilter" @change="fetchOrders"
            class="flex-1 p-2 border border-gray-300 rounded">
            <option value="">Tất cả trạng thái thanh toán</option>
            <option value="0">Chưa thanh toán</option>
            <option value="1">Đã thanh toán</option>
          </select>
          <!-- Nút tìm kiếm -->
          <button @click="fetchOrders" 
            class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 transition-colors">
            Tìm kiếm
          </button>
        </div>
      </div>

      <!-- Loading indicator -->
      <div v-if="loading" class="text-center py-10">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-orange-500 border-t-transparent"></div>
        <p class="mt-2 text-gray-600">Đang tải dữ liệu...</p>
      </div>

      <!-- Error message -->
      <div v-else-if="error" class="bg-red-50 p-4 rounded-lg text-center text-red-600">
        {{ error }}
        <button @click="fetchOrders" class="ml-2 underline">Thử lại</button>
      </div>

      <!-- Empty state -->
      <div v-else-if="filteredOrders.length === 0" class="bg-white rounded-lg shadow p-10 text-center">
        <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
        <p class="text-gray-500">Không tìm thấy đơn hàng nào phù hợp với bộ lọc</p>
      </div>

      <!-- Bảng danh sách đơn hàng -->
      <div v-else class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full border-collapse">
          <!-- Header của bảng -->
          <thead>
            <tr>
              <th class="bg-gray-100 font-semibold p-4 text-left">
                ID đơn hàng
              </th>
              <th class="bg-gray-100 font-semibold p-4 text-left">Ngày đặt</th>
              <th class="bg-gray-100 font-semibold p-4 text-left">
                Khách hàng
              </th>
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
            <tr v-for="order in paginatedOrders" :key="order.order_id" class="even:bg-gray-50">
              <td class="p-4">{{ order.order_id }}</td>
              <td class="p-4">{{ formatDate(order.order_date) }}</td>
              <td class="p-4">{{ order.customer_name || 'Không có thông tin' }}</td>
              <!-- Hiển thị trạng thái đơn hàng với màu sắc tương ứng -->
              <td class="p-4">
                <span :class="[
                  'px-2 py-1 rounded text-xs font-semibold',
                  order.status === '0' && 'bg-red-100 text-red-800',
                  order.status === '1' && 'bg-yellow-100 text-yellow-800',
                  order.status === '2' && 'bg-blue-100 text-blue-800',
                  order.status === '3' && 'bg-green-100 text-green-800',
                ]">
                  {{ getStatusText(order.status) }}
                </span>
              </td>
              <td class="p-4">{{ formatPrice(order.total_amount) }}</td>
              <td class="p-4">{{ order.payment_method }}</td>
              <!-- Hiển thị trạng thái thanh toán với màu sắc tương ứng -->
              <td class="p-4">
                <span :class="[
                  'px-2 py-1 rounded text-xs font-semibold',
                  order.payment_status === '0' && 'bg-yellow-100 text-yellow-800',
                  order.payment_status === '1' && 'bg-green-100 text-green-800',
                ]">
                  {{ getPaymentStatusText(order.payment_status) }}
                </span>
              </td>
              <!-- Nút xem chi tiết đơn hàng -->
              <td class="p-4">
                <button @click="viewOrderDetail(order)" class="p-1 text-gray-600 hover:opacity-70" title="Xem chi tiết">
                  <i class="fas fa-eye"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Phân trang -->
      <div v-if="filteredOrders.length > 0" class="flex justify-center items-center gap-4 mt-4">
        <BasePagination :current-page="currentPage" :total-pages="totalPages"
          @prev="currentPage > 1 && (currentPage--, applyFilters())"
          @next="currentPage < totalPages && (currentPage++, applyFilters())" />
      </div>
    </div>

    <!-- Order Detail Modal -->
    <div v-if="selectedOrder && showDetailModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
      <div class="bg-white rounded-lg w-full max-w-3xl max-h-[90vh] overflow-y-auto">
        <!-- Header của modal -->
        <div class="flex justify-between items-center border-b p-4">
          <h2 class="text-lg font-bold">
            Chi tiết đơn hàng #{{ selectedOrder.order_id }}
          </h2>
          <button class="text-2xl text-gray-500 hover:text-gray-700" @click="closeDetailModal">
            &times;
          </button>
        </div>

        <!-- Body của modal -->
        <div class="p-4">
          <!-- Thông tin cơ bản của đơn hàng -->
          <div class="mb-6">
            <h3 class="text-gray-700 font-semibold mb-3">Thông tin đơn hàng</h3>
            <div class="bg-gray-50 p-4 rounded grid grid-cols-2 gap-x-4 gap-y-2">
              <div class="flex items-center">
                <span class="font-medium text-gray-600 mr-2">Mã đơn hàng:</span>
                <span>{{ selectedOrder.order_id }}</span>
              </div>
              <div class="flex items-center">
                <span class="font-medium text-gray-600 mr-2">Ngày đặt:</span>
                <span>{{ formatDate(selectedOrder.order_date) }}</span>
              </div>
              <div class="flex items-center">
                <span class="font-medium text-gray-600 mr-2">Trạng thái:</span>
                <span :class="[
                  'px-2 py-1 rounded text-xs font-semibold',
                  selectedOrder.status === '0' && 'bg-red-100 text-red-800',
                  selectedOrder.status === '1' && 'bg-yellow-100 text-yellow-800',
                  selectedOrder.status === '2' && 'bg-blue-100 text-blue-800',
                  selectedOrder.status === '3' && 'bg-green-100 text-green-800',
                ]">
                  {{ getStatusText(selectedOrder.status) }}
                </span>
              </div>
              <div class="flex items-center">
                <span class="font-medium text-gray-600 mr-2">Thanh toán:</span>
                <span :class="[
                  'px-2 py-1 rounded text-xs font-semibold',
                  selectedOrder.payment_status === '0' && 'bg-yellow-100 text-yellow-800',
                  selectedOrder.payment_status === '1' && 'bg-green-100 text-green-800',
                ]">
                  {{ getPaymentStatusText(selectedOrder.payment_status) }}
                </span>
              </div>
              <div class="flex items-center">
                <span class="font-medium text-gray-600 mr-2">Phương thức thanh toán:</span>
                <span>{{ selectedOrder.payment_method || 'Không có thông tin' }}</span>
              </div>
              <div class="flex items-center">
                <span class="font-medium text-gray-600 mr-2">Tổng giá trị:</span>
                <span class="font-bold text-orange-500">{{ formatPrice(selectedOrder.total_amount) }}</span>
              </div>
            </div>
          </div>

          <!-- Thông tin khách hàng -->
          <div class="mb-6">
            <h3 class="text-gray-700 font-semibold mb-3">Thông tin khách hàng</h3>
            <div class="bg-gray-50 p-4 rounded">
              <div class="mb-2">
                <span class="font-medium text-gray-600 mr-2">Tên khách hàng:</span>
                <span>{{ selectedOrder.customer_name || 'Không có thông tin' }}</span>
              </div>
              <div class="mb-2">
                <span class="font-medium text-gray-600 mr-2">Số điện thoại:</span>
                <span>{{ selectedOrder.phone || 'Không có thông tin' }}</span>
              </div>
              <div class="mb-2">
                <span class="font-medium text-gray-600 mr-2">Địa chỉ:</span>
                <span>{{ selectedOrder.address_line || 'Không có thông tin' }}</span>
              </div>
            </div>
          </div>

          <!-- Danh sách sản phẩm -->
          <div class="mb-6">
            <h3 class="text-gray-700 font-semibold mb-3">Sản phẩm trong đơn</h3>
            
            <!-- Loading state for product details -->
            <div v-if="loading" class="py-4 text-center text-gray-500">
              <div class="inline-block animate-spin rounded-full h-5 w-5 border-2 border-orange-500 border-t-transparent mr-2"></div>
              Đang tải chi tiết sản phẩm...
            </div>
            
            <!-- Show product details from API -->
            <div v-else-if="selectedOrder.productDetails && selectedOrder.productDetails.length > 0" 
                 class="bg-white border rounded divide-y">
              <div v-for="(item, index) in selectedOrder.productDetails" :key="index" class="p-3 flex items-center">
                <div class="w-16 h-16 mr-3 flex-shrink-0">
                  <img :src="item.image" :alt="item.name" class="w-full h-full object-cover rounded" />
                </div>
                <div class="flex-1">
                  <div class="font-medium">{{ item.name }}</div>
                  <div class="text-sm text-gray-500">{{ item.category }}</div>
                  <div class="text-sm">Số lượng: {{ item.count }}</div>
                </div>
                <div class="text-right">
                  <div class="font-medium">{{ formatPrice(item.price) }}</div>
                  <div class="text-sm text-gray-500">x {{ item.count }}</div>
                  <div class="font-semibold text-orange-500">{{ formatPrice(item.price * item.count) }}</div>
                </div>
              </div>
            </div>
            
            <!-- Fallback to show items from list view -->
            <div v-else-if="selectedOrder.items && selectedOrder.items.length > 0" 
                 class="bg-white border rounded divide-y">
              <div v-for="(item, index) in selectedOrder.items" :key="index" class="p-3 flex items-center">
                <div class="w-16 h-16 mr-3 flex-shrink-0">
                  <img :src="item.image" :alt="item.name" class="w-full h-full object-cover rounded" />
                </div>
                <div class="flex-1">
                  <div class="font-medium">{{ item.name }}</div>
                  <div class="text-sm text-gray-500">{{ item.category }}</div>
                  <div class="text-sm">Số lượng: {{ item.count }}</div>
                </div>
                <div class="text-right">
                  <div class="font-medium">{{ formatPrice(item.price) }}</div>
                  <div class="text-sm text-gray-500">x {{ item.count }}</div>
                  <div class="font-semibold text-orange-500">{{ formatPrice(item.price * item.count) }}</div>
                </div>
              </div>
            </div>
            
            <!-- No products state -->
            <div v-else class="bg-gray-50 p-4 text-center text-gray-500 rounded">
              Không có sản phẩm nào trong đơn hàng này
            </div>
          </div>
        </div>

        <!-- Footer của modal -->
        <div class="flex justify-end gap-4 border-t p-4">
          <button class="px-4 py-2 rounded border border-gray-300 font-semibold text-gray-700 hover:bg-gray-100"
            @click="closeDetailModal">
            Đóng
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import BasePagination from '@/components/BasePagination.vue'
import SearchBar from '@/components/AdminSearchBar.vue'


// =====================
// Biến trạng thái reactive
// =====================
const orders = ref([]);
const filteredOrders = ref([]);
const loading = ref(false);
const error = ref(null);
const searchQuery = ref('');
const statusFilter = ref('');
const paymentStatusFilter = ref('');
const sortField = ref('order_date');
const sortDirection = ref('desc');
const currentPage = ref(1);
const itemsPerPage = ref(10);
const selectedOrder = ref(null);
const showDetailModal = ref(false);

// =====================
// Các computed property
// =====================
const totalPages = computed(() => Math.ceil(filteredOrders.value.length / itemsPerPage.value));
const paginatedOrders = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return filteredOrders.value.slice(start, end);
});

// =====================
// Fetch orders from API
// =====================
const fetchOrders = async () => {
  loading.value = true;
  error.value = null;

  try {
    let url = 'http://localhost:8000/api/v1/orders/filter?';

    if (statusFilter.value) {
      url += `status=${statusFilter.value}&`;
    }

    if (paymentStatusFilter.value) {
      url += `payment_status=${paymentStatusFilter.value}&`;
    }

    const response = await fetch(url, {
      method: 'GET',
      credentials: 'include',
      headers: {
        'Content-Type': 'application/json'
      }
    });

    const data = await response.json();

    if (data.success) {
      orders.value = data.orders || [];
      applyFilters();
    } else {
      error.value = data.message || 'Không thể tải danh sách đơn hàng';
    }
  } catch (err) {
    console.error('Error fetching orders:', err);
    error.value = 'Lỗi kết nối đến máy chủ. Vui lòng thử lại sau.';
  } finally {
    loading.value = false;
  }
};

// =====================
// Hàm lọc, tìm kiếm, sắp xếp
// =====================
const applyFilters = () => {
  let filtered = [...orders.value];

  // Áp dụng tìm kiếm
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase().trim();
    filtered = filtered.filter(order => {
      return (
        (order.order_id?.toString() || '').toLowerCase().includes(query) ||
        (order.customer_name || '').toLowerCase().includes(query) ||
        (order.phone || '').toLowerCase().includes(query)
      );
    });
  }

  // Sắp xếp
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
  });

  filteredOrders.value = filtered;
  currentPage.value = 1; // Reset về trang đầu khi lọc
};

// =====================
// Các hàm format dữ liệu hiển thị
// =====================
// Định dạng ngày theo chuẩn Việt Nam
const formatDate = date => {
  if (!date) return 'N/A';
  return new Date(date).toLocaleDateString('vi-VN', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
  });
};

// Định dạng giá tiền VND
const formatPrice = price => {
  if (!price) return '0 đ';
  return new Intl.NumberFormat('vi-VN', {
    style: 'currency',
    currency: 'VND',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(price);
};

// =====================
// Hàm mapping trạng thái sang tiếng Việt
// =====================
// Chuyển trạng thái đơn hàng sang tiếng Việt
const getStatusText = status => {
  const statusMap = {
    '0': 'Đã hủy',
    '1': 'Đang xử lý',
    '2': 'Đang giao hàng',
    '3': 'Đã hoàn thành'
  };
  return statusMap[status] || 'Không xác định';
};

// Chuyển trạng thái thanh toán sang tiếng Việt
const getPaymentStatusText = status => {
  const statusMap = {
    '0': 'Chưa thanh toán',
    '1': 'Đã thanh toán'
  };
  return statusMap[status] || 'Không xác định';
};

// ...existing code...

// Xem chi tiết đơn hàng trong popup
const viewOrderDetail = async (order) => {
  try {
    loading.value = true;
    showDetailModal.value = true;
    
    // Set basic order info from the list first
    selectedOrder.value = order;
    
    // Then fetch full details
    const response = await fetch(`http://localhost:8000/api/v1/orders/${order.order_id}`, {
      method: 'GET',
      credentials: 'include',
      headers: {
        'Content-Type': 'application/json'
      }
    });

    const data = await response.json();
    
    if (data) {
      // Process the order details
      const orderDetails = data;
      
      // Create a structured order object with details
      const detailedOrder = {
        ...order,
        orderDetails: orderDetails,
        productDetails: processOrderDetails(orderDetails)
      };
      
      // Update the selected order with detailed information
      selectedOrder.value = detailedOrder;
      
    }
  } catch (err) {
    console.error('Error fetching order details:', err);
    error.value = 'Không thể tải chi tiết đơn hàng';
  } finally {
    loading.value = false;
  }
};

// Process order details to group products
const processOrderDetails = (details) => {
  if (!Array.isArray(details) || details.length === 0) return [];

  // Group by product_id to avoid duplicates
  const productMap = {};
  
  details.forEach(item => {
    if (!productMap[item.product_id]) {
      productMap[item.product_id] = {
        product_id: item.product_id,
        name: item.product_name,
        count: parseInt(item.quantity) || 0,
        price: parseFloat(item.unit_price) || 0,
        image: item.image || "https://via.placeholder.com/100",
        category: item.category || "Sản phẩm"
      };
    } else {
      // If product exists, add to the count
      productMap[item.product_id].count += parseInt(item.quantity) || 0;
    }
  });
  
  return Object.values(productMap);
};

// Đóng modal chi tiết đơn hàng
const closeDetailModal = () => {
  showDetailModal.value = false;
  selectedOrder.value = null;
};

// =====================
// Load dữ liệu ban đầu
// =====================
onMounted(() => {
  fetchOrders();
});
</script>