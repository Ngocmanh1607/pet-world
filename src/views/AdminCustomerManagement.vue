<template>
  <div class="customer-page">
    <!-- Tìm kiếm + bộ lọc -->
    <div class="search-section">
      <span>
        Hiển thị {{ filteredCustomers.length }} từ {{ customers.length }} kết quả tìm kiếm
      </span>
      <AdminSearchBar
        v-model="searchQuery"
        placeholder="Tìm kiếm khách hàng theo tên, email hoặc ID..."
        @input="() => {}"
        class="search-input"
      />
    </div>

    <!-- Hiển thị trạng thái tải hoặc lỗi -->
    <div v-if="loading">Đang tải dữ liệu khách hàng...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <div v-else>
      <!-- Bảng danh sách khách hàng -->
      <table class="customer-table">
        <thead>
          <tr>
            <th>ID khách hàng</th>
            <th>Họ và tên</th>
            <th>Email</th>
            <th>Ngày sinh</th>
            <th>Số điện thoại</th>
            <th>Giới tính</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(customer, index) in paginatedCustomers" :key="index">
            <td>{{ customer.customer_id }}</td>
            <td>{{ customer.customer_name }}</td>
            <td>{{ customer.email }}</td>
            <td>{{ customer.date_of_birth }}</td>
            <td>{{ customer.phone }}</td>
            <td>{{ customer.gender }}</td>
          </tr>
        </tbody>
      </table>
      <div v-if="totalPages > 1" class="flex justify-center items-center gap-4 mt-4">
        <BasePagination
          :current-page="currentPage"
          :total-pages="totalPages"
          @prev="currentPage > 1 && currentPage--"
          @next="currentPage < totalPages && currentPage++"
          @page="currentPage = $event"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import AdminSearchBar from '@/components/AdminSearchBar.vue'
import BasePagination from '@/components/BasePagination.vue'

// Trạng thái
const customers = ref([])
const loading = ref(true)
const error = ref('')
const searchQuery = ref('')
const currentPage = ref(1)
const pageSize = 10

// Gọi API lấy danh sách khách hàng
onMounted(async () => {
  try {
    const response = await fetch('http://localhost:8000/customers', { credentials: 'include' })
    if (!response.ok) throw new Error('Không thể tải danh sách khách hàng')
    const data = await response.json()
    customers.value = data.customers
    console.log(data)
  } catch (err) {
    error.value = err.message || 'Đã xảy ra lỗi khi tải dữ liệu'
  } finally {
    loading.value = false
  }
})

// Lọc kết quả theo tìm kiếm
const filteredCustomers = computed(() => {
  if (!searchQuery.value) return customers.value
  return customers.value.filter(
    c =>
      c.name?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      c.email?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      c.id?.toString().includes(searchQuery.value)
  )
})

const totalPages = computed(() => Math.ceil(filteredCustomers.value.length / pageSize))
const paginatedCustomers = computed(() => {
  const start = (currentPage.value - 1) * pageSize
  return filteredCustomers.value.slice(start, start + pageSize)
})

</script>

<style scoped>
.customer-page {
  padding: 20px;
  font-family: 'Arial', sans-serif;
}

.search-section {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

.search-input {
  padding: 8px 12px;
  border: 1px solid #ccc;
  border-radius: 6px;
  width: 250px;
}

.customer-table {
  width: 100%;
  border-collapse: collapse;
}

.customer-table th,
.customer-table td {
  border: 1px solid #eee;
  padding: 10px;
  text-align: center;
}

.customer-table th {
  background-color: #f8f8f8;
}

.action-buttons button {
  margin: 0 5px;
  padding: 4px 6px;
  font-size: 16px;
  cursor: pointer;
  background: none;
  border: none;
}

.error {
  color: red;
  font-weight: bold;
  margin: 10px 0;
}
</style>
