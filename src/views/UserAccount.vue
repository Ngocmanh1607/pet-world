<template>
  <div class="account-page">
    <!-- Banner -->
    <div class="banner-wrapper">
      <div class="user-card">
        <h2>{{ userData.fullName }}</h2>
        <span class="role">Thành viên</span>
      </div>
    </div>

    <div class="main-container">
      <div class="sidebar">
        <div class="joined-info"></div>
        <!-- Nút Đăng xuất -->
        <button class="logout-btn" @click="logout">Đăng xuất</button>
      </div>

      <div class="content">
        <div class="tabs">
          <button :class="{ active: activeTab === 'account' }" @click="activeTab = 'account'">
            Thông tin tài khoản
          </button>
          <button :class="{ active: activeTab === 'wishlist' }" @click="activeTab = 'wishlist'">
            Wish List
          </button>
          <button :class="{ active: activeTab === 'history' }" @click="activeTab = 'history'">
            Lịch sử mua hàng
          </button>
          <button :class="{ active: activeTab === 'password' }" @click="activeTab = 'password'">
            Đổi mật khẩu
          </button>
        </div>

        <div v-if="activeTab === 'account'" class="account-box">
          <!-- Loading state -->
          <div v-if="isLoading" class="loading-state">
            <div class="spinner"></div>
            <p>Đang tải thông tin...</p>
          </div>

          <!-- Error state -->
          <div v-else-if="error" class="error-state">
            <p class="error-message">{{ error }}</p>
            <button @click="fetchCustomerData" class="retry-btn">Thử lại</button>
          </div>

          <!-- Data display -->
          <form v-else>
            <div class="form-group">
              <label>Họ và tên</label>
              <input type="text" v-model="userData.fullName" disabled />
            </div>

            <div class="form-group">
              <label>Ngày sinh</label>
              <input type="text" v-model="userData.dateOfBirth" disabled />
            </div>

            <div class="form-group">
              <label>Giới tính</label>
              <input type="text" v-model="userData.gender" disabled />
            </div>

            <div class="form-group">
              <label>Số điện thoại</label>
              <input type="text" v-model="userData.phone" disabled />
            </div>

            <div class="form-group">
              <label>Email</label>
              <input type="email" v-model="userData.email" disabled />
            </div>

            <div class="form-group">
              <label>Ngày tham gia</label>
              <input type="text" v-model="userData.joinDate" disabled />
            </div>
          </form>
        </div>
        <div v-if="activeTab === 'wishlist'" class="wishlist-box">
          <div class="search-bar">
            <input type="text" v-model="searchQuery" placeholder="Tìm kiếm sản phẩm..." />
            <button><i class="fas fa-search"></i></button>
          </div>

          <!-- Loading state -->
          <div v-if="wishlistLoading" class="loading-state">
            <div class="spinner"></div>
            <p>Đang tải danh sách yêu thích...</p>
          </div>

          <!-- Error state -->
          <div v-else-if="wishlistError" class="error-state">
            <p class="error-message">{{ wishlistError }}</p>
            <button @click="fetchWishlist" class="retry-btn">Thử lại</button>
          </div>

          <!-- Empty state -->
          <div v-else-if="wishlists.length === 0" class="empty-state">
            <p>Chưa có sản phẩm nào trong danh sách yêu thích</p>
          </div>

          <!-- Wishlist items -->
          <div v-else class="product-list">
            <div v-for="product in wishlists" :key="product.id" class="product-card">
              <img :src="product.image" :alt="product.name" />
              <div class="product-info">
                <h4>{{ product.name }}</h4>
                <p>{{ product.price }}₫</p>
              </div>
              <div class="product-actions">
                <button class="add-to-cart" @click="addToCart(product)">Thêm vào giỏ hàng</button>
              </div>
            </div>
          </div>

          <!-- Pagination -->
          <div class="pagination" v-if="totalWishlistPages > 1">
            <button
              @click="goToWishlistPage(currentWishlistPage - 1)"
              :disabled="currentWishlistPage === 1"
            >
              ←
            </button>
            <button
              v-for="page in totalWishlistPages"
              :key="page"
              @click="goToWishlistPage(page)"
              :class="{ active: currentWishlistPage === page }"
            >
              {{ page }}
            </button>
            <button
              @click="goToWishlistPage(currentWishlistPage + 1)"
              :disabled="currentWishlistPage === totalWishlistPages"
            >
              →
            </button>
          </div>
        </div>

        <!-- Thêm nút Edit vào phần thông tin tài khoản -->
        <div v-if="activeTab === 'account'" class="account-box">
          <!-- Loading state -->
          <div v-if="isLoading" class="loading-state">
            <div class="spinner"></div>
            <p>Đang tải thông tin...</p>
          </div>

          <!-- Error state -->
          <div v-else-if="error" class="error-state">
            <p class="error-message">{{ error }}</p>
            <button @click="fetchCustomerData" class="retry-btn">Thử lại</button>
          </div>

          <!-- Data display -->
          <form v-else>
            <div class="form-group">
              <label>Họ và tên</label>
              <input type="text" v-model="userData.fullName" :disabled="!isEditing" />
            </div>

            <div class="form-group">
              <label>Ngày sinh</label>
              <input type="text" v-model="userData.dateOfBirth" :disabled="!isEditing" />
            </div>

            <div class="form-group">
              <label>Giới tính</label>
              <input type="text" v-model="userData.gender" :disabled="!isEditing" />
            </div>

            <div class="form-group">
              <label>Số điện thoại</label>
              <input type="text" v-model="userData.phone" :disabled="!isEditing" />
            </div>

            <div class="form-group">
              <label>Email</label>
              <input type="email" v-model="userData.email" :disabled="!isEditing" />
            </div>

            <div class="form-group">
              <label>Ngày tham gia</label>
              <input type="text" v-model="userData.joinDate" disabled />
            </div>

            <!-- Thêm nút Edit và Save -->
            <div class="form-actions">
              <button v-if="!isEditing" @click.prevent="startEditing" class="edit-btn"></button>
              <template v-else>
                <button @click.prevent="cancelEditing" class="cancel-btn">Hủy</button>
                <button @click.prevent="saveUserData" class="save-btn">Lưu thay đổi</button>
              </template>
            </div>
          </form>
        </div>

        <div v-if="activeTab === 'history'" class="order-history">
          <div v-if="selectedOrder" class="order-detail-form">
            <h3>Chi tiết đơn hàng #{{ selectedOrder.orderDetails.order_id }}</h3>

            <div v-if="orderLoading" class="loading-state">
              <div class="spinner"></div>
              <p>Đang tải đơn hàng...</p>
            </div>

            <div v-else-if="orderError" class="error-state">
              <p class="error-message">{{ orderError }}</p>
              <button @click="fetchOrders" class="retry-btn">Thử lại</button>
            </div>
            <div v-else class="order-content">
              <div class="customer-info">
                <h4>Thông tin khách hàng</h4>
                <div class="info-row">
                  <span class="label">Tên khách hàng:</span>
                  <span class="value">{{ selectedOrder.orderDetails.customer_name }}</span>
                </div>
                <div class="info-row">
                  <span class="label">Số điện thoại:</span>
                  <span class="value">{{ selectedOrder.orderDetails.phone }}</span>
                </div>
                <div class="info-row">
                  <span class="label">Địa chỉ:</span>
                  <span class="value">{{ selectedOrder.orderDetails.address_line }}</span>
                </div>
              </div>

              <div class="order-info-detail">
                <h4>Thông tin đơn hàng</h4>
                <div class="info-row">
                  <span class="label">Ngày đặt hàng:</span>
                  <span class="value">{{ formatDate(selectedOrder.orderDetails.order_date) }}</span>
                </div>
                <div class="info-row">
                  <span class="label">Phương thức thanh toán:</span>
                  <span class="value">Tiền mặt</span>
                </div>
                <div class="info-row">
                  <span class="label">Trạng thái thanh toán:</span>
                  <span class="value payment-status paid">Đã thanh toán</span>
                </div>
              </div>

              <div class="order-items">
                <h4>Sản phẩm trong đơn hàng</h4>
                <div
                  class="order-item-detail"
                  v-for="(item, idx) in selectedOrder.items"
                  :key="idx"
                >
                  <img :src="item.image" alt="Ảnh sản phẩm" class="product-image" />

                  <div class="order-info">
                    <div class="order-category">{{ item.category }}</div>
                    <div class="order-name">{{ item.name }}</div>
                    <div class="order-quantity">{{ item.quantity }}g × {{ item.count }}</div>
                    <div class="order-price">{{ item.price }}đ</div>
                  </div>

                  <div class="item-actions">
                    <button class="wishlist-btn">
                      <i class="far fa-heart"></i>
                    </button>
                  </div>
                </div>
              </div>

              <div class="order-summary">
                <div class="summary-row">
                  <span>Tình trạng đơn hàng:</span>
                  <span
                    class="status-value"
                    :class="selectedOrder.status === 'Hoàn thành' ? 'completed' : ''"
                  >
                    {{ selectedOrder.status }}
                  </span>
                </div>
                <div class="summary-row total-row">
                  <span>Tổng tiền:</span>
                  <span class="total-price">{{ selectedOrder.total }}</span>
                </div>
              </div>

              <div class="form-actions">
                <button class="back-btn" @click="selectedOrder = null">Quay lại</button>
              </div>
            </div>
          </div>

          <div v-else>
            <div class="search-bar">
              <input type="text" v-model="orderSearchQuery" placeholder="Tìm kiếm đơn hàng..." />
              <button><i class="fas fa-search"></i></button>
            </div>

            <div v-if="orderLoading" class="loading-state">
              <div class="spinner"></div>
              <p>Đang tải đơn hàng...</p>
            </div>

            <div v-else-if="orderError" class="error-state">
              <p class="error-message">{{ orderError }}</p>
              <button @click="fetchOrders" class="retry-btn">Thử lại</button>
            </div>

            <div v-else-if="orders.length === 0" class="empty-state">
              <p>Bạn chưa có đơn hàng nào</p>
            </div>
            <div v-else class="order-list">
              <!-- Change from filteredOrders to paginatedOrders -->
              <div class="order-item" v-for="order in paginatedOrders" :key="order.id">
                <div class="order-product">
                  <img :src="order.image" alt="Ảnh sản phẩm" class="product-image" />

                  <div class="order-info">
                    <div class="order-category">{{ order.category }}</div>
                    <div class="order-name">{{ order.name }}</div>
                    <div class="order-quantity">{{ order.quantity }}g × {{ order.count }}</div>
                    <div class="order-price">{{ order.price }}đ</div>
                  </div>
                </div>

                <div class="order-status">
                  <div class="status-label">
                    <i class="fas fa-truck-moving"></i> Tình trạng đơn hàng:
                  </div>
                  <span
                    class="status-value"
                    :class="order.status === 'Hoàn thành' ? 'completed' : ''"
                  >
                    {{ order.status }}
                  </span>
                </div>

                <div class="order-total">
                  <div>Thành tiền:</div>
                  <div class="total-price">{{ order.total }}</div>
                </div>

                <div class="order-actions">
                  <button class="view-more-btn" @click="viewOrderDetail(order)">Xem thêm</button>
                </div>
              </div>
            </div>

            <!-- Update pagination section -->
            <div class="pagination" v-if="orderTotalPages > 1">
              <button class="prev-btn" @click="prevOrderPage" :disabled="orderCurrentPage === 1">
                <i class="fas fa-chevron-left"></i>
              </button>

              <template v-for="page in orderTotalPages" :key="page">
                <!-- Show first page -->
                <button
                  v-if="page === 1"
                  :class="{ active: orderCurrentPage === 1 }"
                  @click="orderCurrentPage = 1"
                >
                  1
                </button>

                <!-- Show dots if needed -->
                <span v-if="page === 2 && orderCurrentPage > 3">...</span>

                <!-- Show current page and neighbors -->
                <button
                  v-if="
                    page !== 1 && page !== orderTotalPages && Math.abs(page - orderCurrentPage) <= 1
                  "
                  :class="{ active: orderCurrentPage === page }"
                  @click="orderCurrentPage = page"
                >
                  {{ page }}
                </button>

                <!-- Show dots if needed -->
                <span v-if="page === orderTotalPages - 1 && orderCurrentPage < orderTotalPages - 2"
                  >...</span
                >

                <!-- Show last page -->
                <button
                  v-if="page === orderTotalPages"
                  :class="{ active: orderCurrentPage === orderTotalPages }"
                  @click="orderCurrentPage = orderTotalPages"
                >
                  {{ orderTotalPages }}
                </button>
              </template>

              <button
                class="next-btn"
                @click="nextOrderPage"
                :disabled="orderCurrentPage === orderTotalPages"
              >
                <i class="fas fa-chevron-right"></i>
              </button>
            </div>
            <div v-if="filteredOrders.length > 0" class="orders-total">
              <span class="label">Tổng tiền tất cả đơn hàng:</span>
              <span class="amount">{{ calculateAllOrdersTotal() }}</span>
            </div>
          </div>
        </div>

        <div v-if="activeTab === 'password'" class="password-change-form">
          <h3>Đổi mật khẩu</h3>

          <!-- Loading state -->
          <div v-if="passwordLoading" class="loading-state">
            <div class="spinner"></div>
            <p>Đang xử lý...</p>
          </div>

          <!-- Error state -->
          <div v-else-if="passwordError" class="error-state">
            <p class="error-message">{{ passwordError }}</p>
            <button @click="resetPasswordForm" class="retry-btn">Thử lại</button>
          </div>

          <!-- Success message -->
          <div v-else-if="passwordSuccess" class="success-state">
            <p class="success-message">
              <i class="fas fa-check-circle"></i> Mật khẩu đã được thay đổi thành công!
            </p>
            <button @click="resetPasswordForm" class="retry-btn">Đóng</button>
          </div>

          <!-- Form -->
          <form v-else @submit.prevent="changePassword">
            <div class="form-group">
              <label>Tên đăng nhập</label>
              <input type="text" :value="userData.fullName" disabled />
            </div>

            <div class="form-group">
              <label>Mật khẩu hiện tại <span class="required">*</span></label>
              <div class="password-field">
                <input
                  :type="showCurrentPassword ? 'text' : 'password'"
                  v-model="passwordData.current_password"
                  required
                />
                <button
                  type="button"
                  class="toggle-password"
                  @click="showCurrentPassword = !showCurrentPassword"
                >
                  <i :class="showCurrentPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                </button>
              </div>
            </div>

            <div class="form-group">
              <label>Mật khẩu mới <span class="required">*</span></label>
              <div class="password-field">
                <input
                  :type="showNewPassword ? 'text' : 'password'"
                  v-model="passwordData.new_password"
                  required
                  pattern=".{6,}"
                  title="Mật khẩu phải có ít nhất 6 ký tự"
                />
                <button
                  type="button"
                  class="toggle-password"
                  @click="showNewPassword = !showNewPassword"
                >
                  <i :class="showNewPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                </button>
              </div>
              <span class="password-hint">Mật khẩu phải có ít nhất 6 ký tự</span>
            </div>

            <div class="form-group">
              <label>Xác nhận mật khẩu mới <span class="required">*</span></label>
              <div class="password-field">
                <input
                  :type="showConfirmPassword ? 'text' : 'password'"
                  v-model="passwordData.confirm_password"
                  required
                  :class="{ 'error-input': !passwordsMatch && passwordData.confirm_password }"
                />
                <button
                  type="button"
                  class="toggle-password"
                  @click="showConfirmPassword = !showConfirmPassword"
                >
                  <i :class="showConfirmPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                </button>
              </div>
              <span class="error-text" v-if="!passwordsMatch && passwordData.confirm_password">
                Mật khẩu xác nhận không khớp
              </span>
            </div>

            <div class="form-group password-actions">
              <button type="button" class="cancel-btn" @click="resetPasswordForm">Hủy</button>
              <button type="submit" class="save-btn" :disabled="!formValid">Lưu thay đổi</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { orderService } from '@/services/api'
import { onMounted, watch } from 'vue'
import { ref, computed } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const userData = ref({
  fullName: '',
  dateOfBirth: '',
  gender: '',
  phone: '',
  email: '',
  joinDate: '',
  customerId: '',
  userId: ''
})

const activeTab = ref('account')
const searchQuery = ref('')
// const currentPage = ref(1);
// const itemsPerPage = 2;
const orderLoading = ref(false)
const orderError = ref(null)
const isLoading = ref(false)
const error = ref(null)
const fetchCustomerData = async () => {
  try {
    isLoading.value = true
    error.value = null

    const response = await axios.get('http://localhost:8000/api/v1/customer', {
      withCredentials: true
    })
    console.log('123', response)

    if (response.data.success) {
      const customer = response.data.success
      userData.value = {
        fullName: customer.customer_name,
        dateOfBirth: formatDate(customer.date_of_birth),
        gender: formatGender(customer.gender),
        phone: formatPhone(customer.phone),
        email: customer.email,
        joinDate: formatDate(customer.created_at),
        customerId: customer.customer_id,
        userId: customer.user_id
      }
    }
  } catch (err) {
    console.error('Error fetching customer data:', err)
    error.value = 'Không thể tải thông tin khách hàng'
  } finally {
    isLoading.value = false
  }
}

const formatGender = gender => {
  if (!gender) return ''
  return gender.toLowerCase() === 'male' ? 'Nam' : 'Nữ'
}

const formatPhone = phone => {
  if (!phone) return ''
  return `(+84) ${phone.slice(1)}`
}
const wishlists = ref([])
const wishlistLoading = ref(false)
const wishlistError = ref(null)

const fetchWishlist = async () => {
  try {
    wishlistLoading.value = true
    wishlistError.value = null

    const response = await axios.get('http://localhost:8000/api/v1/wishlist', {
      withCredentials: true
    })

    console.log('Wishlist response:', response)

    if (response.data.success && Array.isArray(response.data.items)) {
      wishlists.value = response.data.items.map(item => ({
        id: item.product_id,
        name: item.product_name,
        description: item.description,
        price: formatPrice(item.base_price),
        image: item.image || 'https://via.placeholder.com/150',
        stock: item.stock,
        category: item.category_name
      }))
    } else {
      wishlists.value = []
    }
  } catch (err) {
    console.error('Error fetching wishlist:', err)
    wishlistError.value = 'Không thể tải danh sách yêu thích'
  } finally {
    wishlistLoading.value = false
  }
}

onMounted(() => {
  fetchCustomerData()
})

// const products = ref([]);
// const paginatedProducts = computed(() => {
//   const start = (currentPage.value - 1) * itemsPerPage;
//   return filteredProducts.value.slice(start, start + itemsPerPage);
// });

// const totalPages = computed(() => {
//   return Math.ceil(filteredProducts.value.length / itemsPerPage);
// });

// const goToPage = (page) => {
//   if (page >= 1 && page <= totalPages.value) {
//     currentPage.value = page;
//   }
// };
// const filteredProducts = computed(() => {
//   const query = searchQuery.value.toLowerCase();
//   return products.value.filter((p) => p.name.toLowerCase().includes(query));
// });
const addresses = ref([])
// const editAddressMode = ref(false);
// const editingAddress = ref({});
// const editingAddressIndex = ref(-1);

onMounted(async () => {
  await fetchAddresses()
  if (activeTab.value === 'history') {
    await fetchOrders()
  }
  if (activeTab.value === 'wishlist') {
    await fetchWishlist()
  }
})
watch(activeTab, async newTab => {
  if (newTab === 'history' && orders?.value.length === 0) {
    await fetchOrders()
  }
  if (newTab === 'wishlist') {
    await fetchWishlist()
  }
})
const fetchAddresses = async () => {
  try {
    isLoading.value = true
    error.value = null

    const response = await axios.get('http://localhost:8000/api/v1/address', {
      withCredentials: true
    })

    if (response.data && response.data.success) {
      addresses.value = response.data.addresses.map(addr => ({
        address_id: addr.address_id,
        customer_id: addr.customer_id,
        detail: addr.address_line,
        ward_id: addr.ward_id,
        default: addr.is_default === 1,
        created_at: addr.created_at,
        name: addr.customer_name || 'Khách hàng',
        phone: addr.phone || ''
      }))
    } else {
      addresses.value = []
    }
  } catch (error) {
    console.error('Lỗi khi tải địa chỉ:', error)
    error.value = 'Không thể tải danh sách địa chỉ'
    addresses.value = []
  } finally {
    isLoading.value = false
  }
}

// const saveAddress = async () => {
//   try {
//     const addressData = {
//       address_id: editingAddress.value.address_id || '',
//       customer_id: 'CUS1',
//       address_line: editingAddress.value.street,
//       ward_id: editingAddress.value.ward,
//       is_default: editingAddress.value.default ? 1 : 0
//     };

//     const response = await axios.post('http://localhost:8000/api/v1/address/save',
//       addressData,
//       {
//         withCredentials: true,
//       }
//     );

//     if (response.data && response.data.success) {
//       await fetchAddresses();
//       editAddressMode.value = false;
//       editingAddressIndex.value = -1;
//       alert('Đã lưu địa chỉ thành công!');
//     }
//   } catch (error) {
//     console.error("Lỗi khi lưu địa chỉ:", error);
//     alert('Có lỗi xảy ra khi lưu địa chỉ. Vui lòng thử lại.');
//   }
// };

// // Add deleteAddress method
// const deleteAddress = async (index) => {
//   if (!confirm('Bạn có chắc muốn xóa địa chỉ này?')) {
//     return;
//   }

//   try {
//     const addressId = addresses.value[index].address_id;
//     const response = await axios.delete(`http://localhost:8000/api/v1/address/${addressId}`, {
//       withCredentials: true,
//     });

//     if (response.data && response.data.success) {
//       await fetchAddresses();
//       alert('Đã xóa địa chỉ thành công!');
//     }
//   } catch (error) {
//     console.error("Lỗi khi xóa địa chỉ:", error);
//     alert('Có lỗi xảy ra khi xóa địa chỉ. Vui lòng thử lại.');
//   }
// };
const orders = ref([])
const orderSearchQuery = ref('')
const orderCurrentPage = ref(1)
const ordersPerPage = 4
const selectedOrder = ref(null)

const fetchOrders = async () => {
  try {
    orderLoading.value = true
    orderError.value = null

    let customerId =
      localStorage.getItem('customer_id') || localStorage.getItem('customerId') || 'CUS1'

    const response = await orderService.getAllCus(customerId)

    if (response && Array.isArray(response)) {
      console.log('API Response:', response)

      orders.value = response.map(order => {
        const orderItems =
          order.items && Array.isArray(order.items) && order.items.length > 0
            ? order.items.map(item => ({
                product_id: item.product_id || '',
                category: item.category || 'Sản phẩm',
                name: item.name || 'Sản phẩm chưa xác định',
                quantity: item.quantity || '0',
                count: item.count || '1',
                price: item.price || '0',
                image: item.image || 'https://via.placeholder.com/150'
              }))
            : [
                {
                  product_id: order.product_id || '',
                  category: order.category || 'Đơn hàng',
                  name: order.name || `Đơn hàng #${order.order_id}`,
                  quantity: order.quantity || '0',
                  count: order.count || '1',
                  price: formatPrice(order.price) || '0',
                  image: order.image || 'https://via.placeholder.com/150'
                }
              ]

        return {
          id: order.order_id,
          category: orderItems[0].category || 'Đơn hàng',
          name: orderItems[0].name || `Đơn hàng #${order.order_id}`,
          quantity: orderItems[0].quantity || '0',
          count: orderItems[0].count || '1',
          price: orderItems[0].price || '0',
          total: formatPrice(order.total_amount) + 'đ',
          status: getStatusText(order.status),
          image: orderItems[0].image || 'https://via.placeholder.com/150',

          orderDetails: {
            order_id: order.order_id,
            order_date: order.order_date,
            status: order.status,
            total_amount: order.total_amount,
            payment_method: order.payment_method,
            payment_status: order.payment_status,
            customer_name: order.customer_name,
            phone: order.phone,
            address_line: order.address_line,
            ward_id: order.ward_id,
            total_items: order.total_items || 0
          },

          items: orderItems
        }
      })

      console.log('Transformed orders:', orders.value)
    } else {
      console.warn('No orders found or invalid response format')
      orders.value = []
    }
  } catch (error) {
    console.error('Error fetching orders:', error)
    orderError.value = 'Không thể tải danh sách đơn hàng'
  } finally {
    orderLoading.value = false
  }
}

const formatPrice = price => {
  if (!price) return '0'
  console.log('Formatting price:', price)

  return new Intl.NumberFormat('vi-VN').format(price)
}

const getStatusText = status => {
  const statusString = String(status)

  if (statusString === '1') {
    return 'Hoàn thành'
  } else {
    return 'Chưa hoàn thành'
  }
}

const formatDate = dateString => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return date.toLocaleDateString('vi-VN', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const filteredOrders = computed(() => {
  const query = orderSearchQuery.value.toLowerCase()
  return orders?.value.filter(
    order =>
      order.name.toLowerCase().includes(query) || order.category.toLowerCase().includes(query)
  )
})

const paginatedOrders = computed(() => {
  const start = (orderCurrentPage.value - 1) * ordersPerPage
  const end = start + ordersPerPage
  return filteredOrders.value?.slice(start, end) || []
})

const orderTotalPages = computed(() => {
  return Math.ceil(filteredOrders.value.length / ordersPerPage)
})

const nextOrderPage = () => {
  if (orderCurrentPage.value < orderTotalPages.value) {
    orderCurrentPage.value++
  }
}

const prevOrderPage = () => {
  if (orderCurrentPage.value > 1) {
    orderCurrentPage.value--
  }
}

const viewOrderDetail = order => {
  selectedOrder.value = order
}

const calculateAllOrdersTotal = () => {
  if (!orders.value || orders.value.length === 0) return '0đ'

  const total = orders.value.reduce((sum, order) => {
    const amount = parseFloat(order.orderDetails.total_amount || 0)
    return sum + amount
  }, 0)

  return new Intl.NumberFormat('vi-VN').format(total) + 'đ'
}
// Thêm biến trạng thái cho chức năng chỉnh sửa
const isEditing = ref(false)
const userDataBackup = ref({})

// Phương thức để bắt đầu chỉnh sửa
const startEditing = () => {
  // Sao lưu dữ liệu hiện tại để có thể hủy thay đổi
  userDataBackup.value = { ...userData.value }
  isEditing.value = true
}

// Phương thức để hủy chỉnh sửa
const cancelEditing = () => {
  // Khôi phục dữ liệu từ bản sao lưu
  userData.value = { ...userDataBackup.value }
  isEditing.value = false
}

// Phương thức để lưu dữ liệu người dùng
const saveUserData = async () => {
  try {
    isLoading.value = true

    // Chuẩn bị dữ liệu để cập nhật
    const updateData = {
      customer_name: userData.value.fullName,
      date_of_birth: userData.value.dateOfBirth,
      gender: userData.value.gender === 'Nam' ? 'male' : 'female',
      phone: userData.value.phone.replace('(+84) ', '0'), // Chuyển lại định dạng số điện thoại
      email: userData.value.email
    }

    // Gọi API cập nhật thông tin
    const response = await axios.put('http://localhost:8000/api/v1/customer', updateData, {
      withCredentials: true
    })

    if (response.data && response.data.success) {
      alert('Cập nhật thông tin thành công!')
      isEditing.value = false
    } else {
      throw new Error('Cập nhật thông tin không thành công')
    }
  } catch (err) {
    console.error('Error updating customer data:', err)
    alert('Không thể cập nhật thông tin. Vui lòng thử lại sau.')
  } finally {
    isLoading.value = false
  }
}
const passwordData = ref({
  current_password: '',
  new_password: '',
  confirm_password: ''
})

const passwordLoading = ref(false)
const passwordError = ref(null)
const passwordSuccess = ref(false)
const showCurrentPassword = ref(false)
const showNewPassword = ref(false)
const showConfirmPassword = ref(false)

// Kiểm tra mật khẩu khớp nhau
const passwordsMatch = computed(() => {
  return (
    !passwordData.value.confirm_password ||
    passwordData.value.new_password === passwordData.value.confirm_password
  )
})

// Kiểm tra form hợp lệ
const formValid = computed(() => {
  return (
    passwordData.value.current_password &&
    passwordData.value.new_password &&
    passwordData.value.confirm_password &&
    passwordsMatch.value &&
    passwordData.value.new_password.length >= 6
  )
})

// Reset form mật khẩu
const resetPasswordForm = () => {
  passwordData.value = {
    current_password: '',
    new_password: '',
    confirm_password: ''
  }
  passwordError.value = null
  passwordSuccess.value = false
  showCurrentPassword.value = false
  showNewPassword.value = false
  showConfirmPassword.value = false
}
const loadingProductIds = ref([])
const addToCart = async product => {
  try {
    loadingProductIds.value.push(product.id)

    const cartItem = {
      product_id: product.id,
      quantity: 1
    }

    const response = await axios.post('http://localhost:8000/api/v1/cart', cartItem, {
      withCredentials: true
    })

    // Xử lý kết quả
    if (response.data.cart_item_id) {
      alert('Sản phẩm đã được thêm vào giỏ hàng!')
    } else {
      throw new Error(response.data?.message || 'Không thể thêm sản phẩm vào giỏ hàng')
    }
  } catch (error) {
    console.error('Error adding to cart:', error)
  } finally {
    // Xóa sản phẩm khỏi danh sách loading
    loadingProductIds.value = loadingProductIds.value.filter(id => id !== product.id)
  }
}
// Xử lý đổi mật khẩu
const changePassword = async () => {
  if (!formValid.value) {
    return
  }

  try {
    passwordLoading.value = true
    passwordError.value = null

    // Gọi API đổi mật khẩu
    const response = await axios.post(
      'http://localhost:8000/api/v1/password/change',
      passwordData.value,
      { withCredentials: true }
    )

    console.log('Password change response:', response)

    if (response.data && response.data.success) {
      passwordSuccess.value = true
      // Reset sau 3 giây
      setTimeout(() => {
        resetPasswordForm()
      }, 3000)
    } else {
      throw new Error(response.data?.message || 'Đổi mật khẩu không thành công')
    }
  } catch (err) {
    console.error('Error changing password:', err)
    passwordError.value =
      err.response?.data?.message || 'Có lỗi xảy ra khi đổi mật khẩu. Vui lòng thử lại.'
  } finally {
    passwordLoading.value = false
  }
}

const router = useRouter()

const logout = () => {
  // Xóa localStorage/sessionStorage/cookie nếu có
  localStorage.clear()
  sessionStorage.clear()
  // Nếu dùng cookie: document.cookie = 'token=; Max-Age=0'
  // Chuyển hướng về trang đăng nhập hoặc trang chủ
  router.push('/login')
}
</script>

<style scoped>
.account-page {
  background-color: #f9f9f9;
}

.banner-wrapper {
  position: relative;
  width: 100%;
  display: flex;
  height: 100%;
  overflow: hidden;
  justify-content: center;
  background-color: #f9f9f9;
  padding: 20px 0 30px;
}

.banner {
  width: 100%;
  height: auto;
  display: block;
}

.user-card {
  position: absolute;
  bottom: -25px;
  left: 20px;
  background: var(--Primary, #ff7c00);
  border-radius: 0px 757.746px 757.746px 0px;
  box-shadow: 0px 3.031px 3.031px rgba(0, 0, 0, 0.25);
  padding: 20px 40px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  color: white;
  width: 260px;
  z-index: 10;
  margin-bottom: 20px;
}

.user-card h2 {
  margin: 0;
  font-size: 20px;
  font-weight: bold;
}

.user-card .role {
  font-size: 13px;
  background: white;
  color: orange;
  padding: 3px 12px;
  border-radius: 10px;
  display: inline-block;
  margin-top: 5px;
}

.main-container {
  display: flex;
  padding: 20px;
  gap: 20px;
  margin-top: 20px;
}

/* Sidebar */
.sidebar {
  width: 250px;
  background-color: #fff;
  border-right: 1px solid #ddd;
  padding: 10px;
}

.user-info {
  text-align: center;
  padding: 10px;
}

.avatar {
  width: 80px;
  height: 80px;
  background-color: orange;
  border-radius: 40px;
  margin: 0 auto 10px;
}

.role {
  font-size: 14px;
  color: white;
  background: orange;
  padding: 2px 10px;
  border-radius: 8px;
}

.joined-info {
  max-height: 120px;
  overflow: hidden;
  padding: 10px;
  border-top: 1px solid #ddd;
}

.joined-info h4 {
  margin-bottom: 5px;
}

/* Content */
.content {
  flex: 1;
  min-width: 0;
}

.tabs {
  display: flex;
  gap: 10px;
  border-bottom: 2px solid #eee;
  padding-bottom: 10px;
}

.tabs button {
  padding: 6px 12px;
  border: none;
  background: none;
  cursor: pointer;
  border-bottom: 2px solid transparent;
}

.tabs .active {
  color: orange;
  border-color: orange;
  font-weight: bold;
}

/* Form */
.form-box {
  margin-top: 20px;
  background: #fff;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);

  text-align: left;
}

.form-box h3 {
  margin-bottom: 20px;
  text-align: center;
}

.form-box form {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.form-box input {
  padding: 8px;
  border-radius: 6px;
  border: 1px solid #ccc;
}

.form-actions {
  display: flex;
  justify-content: center;
  gap: 10px;
  margin-top: 20px;
}

.edit,
.save {
  padding: 8px 16px;
  border: none;
  border-radius: 6px;
}

.edit {
  background-color: white;
  border: 1px solid orange;
  color: orange;
}

.save {
  background-color: orange;
  color: white;
}

.wishlist-box {
  margin-top: 20px;
}

.search-bar {
  display: flex;
  gap: 10px;
  margin-bottom: 20px;
}

.search-bar input {
  flex: 1;
  padding: 8px;
  border-radius: 6px;
  border: 1px solid #ccc;
}

.search-bar button {
  background-color: orange;
  color: white;
  border: none;
  padding: 8px 12px;
  border-radius: 6px;
  cursor: pointer;
}

.product-list {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.product-card {
  display: flex;
  align-items: center;
  gap: 12px;
  background: #fff;
  padding: 12px;
  border-radius: 10px;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
}

.product-card img {
  width: 60px;
  height: 60px;
  object-fit: cover;
}

.product-info h4 {
  margin: 0;
  font-size: 16px;
}

.product-info p {
  margin: 4px 0 0;
  color: orange;
  font-weight: bold;
}

.wishlist-button {
  margin-left: auto;
  background: none;
  border: none;
  font-size: 20px;
  cursor: pointer;
  color: red;
}

.product-actions {
  margin-left: auto;
  display: flex;
  align-items: center;
  gap: 10px;
}

.add-to-cart {
  background-color: orange;
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
  white-space: nowrap;
  transition: background-color 0.2s ease;
}

.add-to-cart:hover {
  background-color: #e67300;
}

.pagination {
  display: flex;
  justify-content: center;
  margin-top: 20px;
  gap: 8px;
}

.pagination button {
  padding: 6px 10px;
  border: none;
  background-color: #eee;
  border-radius: 4px;
  cursor: pointer;
  min-width: 32px;
}

.pagination button.active {
  background-color: orange;
  color: white;
  font-weight: bold;
}

.pagination button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}

.address-book {
  margin-top: 20px;
  max-width: 100%;
}

.address-card {
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
  padding: 20px;
  margin-bottom: 15px;
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
}

.address-content {
  flex: 1;
}

.address-header {
  display: flex;
  justify-content: flex-start;
  margin-bottom: 5px;
}

.name-phone-group {
  display: flex;
  align-items: center;
  gap: 10px;
}

.address-name {
  font-size: 20px;
  font-weight: bold;
  color: #ff7c00;
}

.address-phone {
  font-size: 14px;
  color: #666;
}

.address-detail {
  font-size: 14px;
  color: #333;
  margin-bottom: 10px;
  line-height: 1.4;
}

.address-label {
  margin-top: 5px;
}

.address-default {
  display: inline-block;
  font-size: 13px;
  padding: 2px 12px;
  border-radius: 20px;
}

.address-default.orange {
  background: #ff7c00;
  color: #fff;
}

.address-default.gray {
  background: #bdbdbd;
  color: #fff;
}

.address-actions {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.action-btn {
  border: 1px solid #ff7c00;
  background: #fff;
  color: #ff7c00;
  border-radius: 20px;
  padding: 5px 15px;
  cursor: pointer;
  font-size: 13px;
  white-space: nowrap;
}

.action-btn:hover {
  background: #ff7c00;
  color: #fff;
}

.add-address-btn {
  margin-top: 10px;
  border: 1px solid #ff7c00;
  background: #fff;
  color: #ff7c00;
  border-radius: 20px;
  padding: 7px 20px;
  cursor: pointer;
  font-size: 15px;
  display: block;
  margin-left: auto;
  margin-right: auto;
}

.add-address-btn:hover {
  background: #ff7c00;
  color: #fff;
}

.address-edit-form {
  background: #fff;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
  margin-bottom: 20px;
}

.address-edit-form h3 {
  color: #ff7c00;
  font-size: 24px;
  text-align: center;
  margin-bottom: 20px;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  margin-bottom: 6px;
  color: #333;
  font-size: 14px;
}

.form-group input,
.form-group select {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid #ddd;
  border-radius: 8px;
  font-size: 15px;
}

.select-wrapper {
  position: relative;
}

.select-arrow {
  position: absolute;
  right: 15px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 10px;
  color: #999;
  pointer-events: none;
}

.default-checkbox {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-top: 20px;
  margin-bottom: 25px;
}

/* Toggle switch style */
.toggle-switch {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 24px;
}

.toggle-switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.toggle-slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  transition: 0.4s;
  border-radius: 34px;
}

.toggle-slider:before {
  position: absolute;
  content: '';
  height: 16px;
  width: 16px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  transition: 0.4s;
  border-radius: 50%;
}

input:checked + .toggle-slider {
  background-color: #ff7c00;
}

input:checked + .toggle-slider:before {
  transform: translateX(26px);
}

.save-btn {
  background: #fff;
  border: 1px solid #ff7c00;
  color: #ff7c00;
  padding: 8px 40px;
  border-radius: 20px;
  font-size: 16px;
  cursor: pointer;
  display: block;
  margin: 0 auto;
  transition: all 0.3s;
}

.save-btn:hover {
  background: #ff7c00;
  color: #fff;
}

.order-history {
  margin-top: 20px;
}

.order-list {
  display: flex;
  flex-direction: column;
  gap: 15px;
  margin-top: 20px;
}

.order-item {
  background: #fff;
  border-radius: 8px;
  padding: 15px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.order-product {
  display: flex;
  gap: 15px;
  padding-bottom: 10px;
  border-bottom: 1px solid #eee;
}

.product-image {
  width: 70px;
  height: 70px;
  object-fit: contain;
}

.order-info {
  flex: 1;
}

.order-category {
  font-size: 12px;
  color: #666;
  margin-bottom: 4px;
}

.order-name {
  font-size: 16px;
  font-weight: bold;
  color: #ff7c00;
  margin-bottom: 4px;
}

.order-quantity {
  font-size: 13px;
  color: #333;
  margin-bottom: 4px;
}

.order-price {
  font-size: 14px;
  font-weight: bold;
}

.order-status {
  display: flex;
  align-items: center;
  gap: 5px;
  margin: 12px 0;
  font-size: 14px;
}

.status-label {
  color: #333;
  display: flex;
  align-items: center;
  gap: 6px;
}

.status-value {
  font-weight: 500;
}

.status-value.completed {
  color: #2eb82e;
}

.order-total {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  gap: 10px;
  margin-bottom: 15px;
  font-size: 14px;
}

.total-price {
  font-size: 16px;
  font-weight: bold;
  color: #ff7c00;
}

.order-actions {
  display: flex;
  justify-content: flex-end;
}

.view-more-btn {
  background: white;
  border: 1px solid #ccc;
  color: #333;
  border-radius: 20px;
  padding: 6px 15px;
  font-size: 13px;
  cursor: pointer;
}

.pagination {
  display: flex;
  align-items: center;
  justify-content: center;
  margin-top: 25px;
  gap: 15px;
}

.prev-btn,
.next-btn {
  border: 1px solid #ccc;
  background: white;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}

.page-info {
  font-size: 14px;
  color: #333;
}

.order-detail-modal {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 100;
}

.modal-content {
  background: white;
  width: 80%;
  max-width: 800px;
  border-radius: 10px;
  padding: 20px;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  padding-bottom: 10px;
  border-bottom: 1px solid #eee;
}

.modal-header h3 {
  color: #ff7c00;
  margin: 0;
}

.close-btn {
  background: none;
  border: none;
  font-size: 24px;
  cursor: pointer;
  color: #666;
}

.order-items {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.order-item-detail {
  display: flex;
  gap: 15px;
  padding: 15px;
  border: 1px solid #eee;
  border-radius: 8px;
  align-items: center;
}

.item-actions {
  display: flex;
  align-items: center;
}

.wishlist-btn {
  background: none;
  border: none;
  font-size: 18px;
  color: #ccc;
  cursor: pointer;
  transition: color 0.2s;
}

.wishlist-btn:hover {
  color: #ff7c00;
}

.summary-row {
  display: flex;
  justify-content: space-between;
  padding: 10px 0;
  font-size: 14px;
}

.total-row {
  font-weight: bold;
  border-top: 1px solid #eee;
  margin-top: 10px;
  padding-top: 15px;
}

.order-summary {
  margin-top: 20px;
  padding: 15px;
  background: #f9f9f9;
  border-radius: 8px;
}

/* Thêm tính năng tổng tiền ở dưới cùng */
.orders-total {
  margin-top: 20px;
  padding: 15px;
  background: #fff;
  border-radius: 8px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.orders-total .label {
  font-size: 16px;
  font-weight: bold;
}

.orders-total .amount {
  font-size: 18px;
  font-weight: bold;
  color: #ff7c00;
}

.order-detail-form {
  background: #fff;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
  margin-bottom: 20px;
}

.order-detail-form h3 {
  color: #ff7c00;
  font-size: 24px;
  text-align: center;
  margin-bottom: 20px;
}

.back-btn {
  background: #fff;
  border: 1px solid #ff7c00;
  color: #ff7c00;
  padding: 8px 40px;
  border-radius: 20px;
  font-size: 16px;
  cursor: pointer;
  display: block;
  margin: 20px auto 0;
  transition: all 0.3s;
}

.back-btn:hover {
  background: #ff7c00;
  color: #fff;
}

.order-detail-modal {
  display: none;
}

.password-change-form {
  background: #fff;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
  margin-top: 20px;
  max-width: 500px;
  margin-left: auto;
  margin-right: auto;
}

.password-change-form h3 {
  color: #ff7c00;
  font-size: 24px;
  text-align: center;
  margin-bottom: 30px;
}

.password-change-form .form-group {
  margin-bottom: 20px;
}

.password-change-form label {
  display: block;
  margin-bottom: 8px;
  font-size: 14px;
  color: #333;
}

.password-change-form input {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 8px;
  font-size: 15px;
}

.password-change-form input:disabled {
  background-color: #f9f9f9;
  color: #666;
}

.password-actions {
  display: flex;
  justify-content: center;
  margin-top: 30px;
  gap: 20px;
}

.cancel-btn {
  background: #fff;
  border: 1px solid #ccc;
  color: #666;
  padding: 8px 40px;
  border-radius: 20px;
  font-size: 16px;
  cursor: pointer;
  transition: all 0.2s;
}

.cancel-btn:hover {
  background: #f5f5f5;
}

.loading-state,
.error-state,
.empty-state {
  text-align: center;
  padding: 40px 20px;
  background: #fff;
  border-radius: 10px;
  margin-top: 20px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #ff7c00;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin: 0 auto 20px;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }

  100% {
    transform: rotate(360deg);
  }
}

.error-message {
  color: #e74c3c;
  margin-bottom: 20px;
}

.retry-btn {
  background: #ff7c00;
  color: white;
  border: none;
  padding: 8px 20px;
  border-radius: 20px;
  cursor: pointer;
}

.empty-state p {
  font-size: 16px;
  color: #666;
}

/* Chi tiết đơn hàng */
.customer-info,
.order-info-detail {
  background: #f9f9f9;
  padding: 15px;
  border-radius: 8px;
  margin-bottom: 20px;
}

.customer-info h4,
.order-info-detail h4 {
  margin-bottom: 15px;
  color: #333;
  font-size: 16px;
}

.info-row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 10px;
  padding: 5px 0;
  border-bottom: 1px solid #eee;
}

.info-row .label {
  font-weight: 500;
  color: #666;
}

.info-row .value {
  color: #333;
}

.payment-status.paid {
  color: #2eb82e;
  font-weight: 500;
}

/* Tổng tiền tất cả đơn hàng */
.orders-total {
  margin-top: 20px;
  padding: 15px;
  background: #fff;
  border-radius: 8px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.orders-total .label {
  font-size: 16px;
  font-weight: bold;
}

.orders-total .amount {
  font-size: 18px;
  font-weight: bold;
  color: #ff7c00;
}

/* Tăng kích thước chữ cho các phần tử quan trọng */
.order-name {
  font-size: 18px;
  /* Tăng từ 16px lên 18px */
  font-weight: bold;
  color: #ff7c00;
  margin-bottom: 6px;
}

.order-category {
  font-size: 14px;
  /* Tăng từ 12px lên 14px */
  color: #666;
  margin-bottom: 5px;
}

.order-quantity {
  font-size: 15px;
  /* Tăng từ 13px lên 15px */
  color: #333;
  margin-bottom: 5px;
}

.order-price {
  font-size: 16px;
  /* Tăng từ 14px lên 16px */
  font-weight: bold;
}

.status-label {
  color: #333;
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 16px;
  /* Tăng từ 14px lên 16px */
}

.status-value {
  font-weight: 500;
  font-size: 16px;
  /* Tăng từ mặc định lên 16px */
}

.total-price {
  font-size: 18px;
  /* Tăng từ 16px lên 18px */
  font-weight: bold;
  color: #ff7c00;
}

/* Tăng kích thước cho phần chi tiết đơn hàng */
.order-detail-form h3 {
  color: #ff7c00;
  font-size: 26px;
  /* Tăng từ 24px lên 26px */
  text-align: center;
  margin-bottom: 25px;
}

.customer-info h4,
.order-info-detail h4 {
  margin-bottom: 15px;
  color: #333;
  font-size: 18px;
  /* Tăng từ 16px lên 18px */
  font-weight: bold;
}

.info-row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 12px;
  padding: 6px 0;
  border-bottom: 1px solid #eee;
  font-size: 16px;
  /* Thêm font size */
}

.info-row .label {
  font-weight: 500;
  color: #666;
}

.info-row .value {
  color: #333;
  font-weight: 500;
}

/* Tăng kích thước nút */
.view-more-btn {
  background: white;
  border: 1px solid #ccc;
  color: #333;
  border-radius: 20px;
  padding: 8px 20px;
  /* Tăng padding */
  font-size: 15px;
  /* Tăng từ 13px lên 15px */
  cursor: pointer;
}

/* Tăng kích thước tổng tiền ở dưới cùng */
.orders-total .label {
  font-size: 18px;
  /* Tăng từ 16px lên 18px */
  font-weight: bold;
}

.orders-total .amount {
  font-size: 22px;
  /* Tăng từ 18px lên 22px */
  font-weight: bold;
  color: #ff7c00;
}

.pagination {
  display: flex;
  align-items: center;
  justify-content: center;
  margin-top: 25px;
  gap: 10px;
}

.pagination button {
  min-width: 35px;
  height: 35px;
  border: 1px solid #ddd;
  background: white;
  border-radius: 4px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s;
  font-size: 14px;
}

.pagination button.active {
  background: #ff7c00;
  color: white;
  border-color: #ff7c00;
}

.pagination button:hover:not(:disabled) {
  border-color: #ff7c00;
  color: #ff7c00;
}

.pagination button:disabled {
  background: #f5f5f5;
  cursor: not-allowed;
  color: #999;
}

.pagination button.prev-btn,
.pagination button.next-btn {
  border-radius: 50%;
}

.pagination span {
  color: #666;
  margin: 0 5px;
}
.password-field {
  position: relative;
  width: 100%;
}

.toggle-password {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  color: #666;
  cursor: pointer;
  padding: 0;
  font-size: 16px;
}

.toggle-password:hover {
  color: #ff7c00;
}

.password-hint {
  font-size: 12px;
  color: #666;
  margin-top: 5px;
  display: block;
}

.error-input {
  border-color: #e74c3c !important;
}

.error-text {
  color: #e74c3c;
  font-size: 12px;
  margin-top: 5px;
  display: block;
}

.required {
  color: #e74c3c;
}

.success-state {
  text-align: center;
  padding: 40px 20px;
  background: #fff;
  border-radius: 10px;
}

.success-message {
  color: #2eb82e;
  font-size: 18px;
  margin-bottom: 20px;
}

.success-message i {
  font-size: 24px;
  margin-right: 10px;
}

.logout-btn {
  width: 100%;
  margin-top: 30px;
  padding: 10px 0;
  background: #ff7c00;
  color: #fff;
  border: none;
  border-radius: 8px;
  font-size: 16px;
  font-weight: bold;
  cursor: pointer;
  transition: background 0.2s;
}

.logout-btn:hover {
  background: #e67300;
}
</style>
