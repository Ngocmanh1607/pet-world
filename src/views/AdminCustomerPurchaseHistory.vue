<template>
  <div class="purchase-history">
    <Header />

    <h1 class="title">Lịch sử mua hàng</h1>

    <!-- Bộ lọc -->
    <div class="filter-bar">
      <input type="text" placeholder="Bộ lọc" />
      <select>
        <option>Danh mục</option>
      </select>
      <select>
        <option>Sắp xếp theo</option>
      </select>
      <button class="search-btn">🔍</button>
    </div>

    <!-- Danh sách đơn hàng -->
    <div class="order-list">
      <div class="order-item" v-for="(order, index) in orders" :key="index">
        <img :src="order.image" alt="product" class="product-image" />
        <div class="order-info">
          <p class="category">{{ order.category }}</p>
          <p class="name">{{ order.name }}</p>
          <p class="package">{{ order.package }} x{{ order.quantity }}</p>
          <p class="price">{{ formatPrice(order.price) }}</p>
        </div>
        <div class="status-info">
          <p>📦 Tình trạng đơn hàng: <span class="status">Hoàn thành</span></p>
          <p class="total">
            Thành tiền:
            <span class="money">{{ formatPrice(order.total) }}đ</span>
          </p>
        </div>
      </div>
    </div>

    <!-- Phân trang -->
    <div class="pagination">
      <button class="page active">1</button>
      <button class="page">2</button>
      <button class="page">3</button>
      <span>...</span>
      <button class="page">></button>
    </div>

    <!-- Quay về -->
    <div class="back-button">
      <button @click="goBack">Quay về</button>
    </div>

    <Footer />
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router';
import Header from '@/components/AdminHeader.vue';
import Footer from '@/components/FooterComponent.vue';

const router = useRouter();

const goBack = () => {
  router.back();
};

const formatPrice = (num) => {
  return num.toLocaleString('vi-VN');
};

// Sử dụng ảnh mới từ public/images
const imgUrl = '/images/royal-canin-desc-1.jpg';

const orders = [
  {
    image: imgUrl,
    category: 'THỨC ĂN CHO CHÓ',
    name: 'Hạt Royal Canin',
    package: '300g',
    quantity: 2,
    price: 350000,
    total: 700000,
  },
  {
    image: imgUrl,
    category: 'THỨC ĂN CHO CHÓ',
    name: 'Thức ăn Pedigree',
    package: '300g',
    quantity: 2,
    price: 350000,
    total: 700000,
  },
  {
    image: imgUrl,
    category: 'THỨC ĂN CHO MÈO',
    name: 'Hạt Whiskas',
    package: '300g',
    quantity: 2,
    price: 350000,
    total: 700000,
  },
];
</script>

<style scoped>
.purchase-history {
  font-family: 'Arial', sans-serif;
  padding: 20px;
}

.title {
  font-size: 28px;
  font-weight: bold;
  text-align: center;
  margin: 20px 0;
}

.filter-bar {
  display: flex;
  gap: 10px;
  justify-content: center;
  margin-bottom: 20px;
}

.filter-bar input,
.filter-bar select {
  padding: 8px 12px;
  border: 1px solid #ccc;
  border-radius: 6px;
}

.search-btn {
  padding: 8px 12px;
  background-color: orange;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}

.order-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.order-item {
  display: flex;
  background: #fff;
  border: 1px solid #eee;
  border-radius: 12px;
  padding: 16px;
  align-items: center;
  justify-content: space-between;
}

.product-image {
  width: 80px;
  height: auto;
  object-fit: contain;
}

.order-info {
  flex: 1;
  padding-left: 16px;
}

.category {
  font-size: 12px;
  color: gray;
}

.name {
  font-size: 18px;
  font-weight: bold;
  color: #f37021;
}

.package {
  background-color: #ffcc80;
  display: inline-block;
  padding: 2px 6px;
  border-radius: 4px;
  font-size: 12px;
  margin: 4px 0;
}

.price {
  font-weight: bold;
}

.status-info {
  text-align: right;
}

.status {
  color: green;
  font-weight: bold;
}

.total {
  margin-top: 10px;
}

.money {
  color: orange;
  font-weight: bold;
}

.pagination {
  display: flex;
  justify-content: center;
  margin: 20px 0;
  gap: 8px;
}

.page {
  padding: 6px 12px;
  border: 1px solid #ccc;
  border-radius: 6px;
  background: white;
  cursor: pointer;
}

.page.active {
  background: #222;
  color: white;
}

.back-button {
  text-align: center;
  margin-top: 20px;
}

.back-button button {
  padding: 10px 24px;
  border: 2px solid orange;
  color: orange;
  font-weight: bold;
  border-radius: 6px;
  background: none;
  cursor: pointer;
}
</style>
