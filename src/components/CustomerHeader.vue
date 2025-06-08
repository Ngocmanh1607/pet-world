<template>
  <header class="bg-gray-50 font-['Inter']">
    <!-- Topbar -->
    <div class="topbar">
      <div class="flex items-center gap-2">
        <span class="text-primary"><i class="fas fa-phone"></i></span>
        <span>+84 123 456 789</span>
        <span class="text-primary"><i class="fas fa-envelope"></i></span>
        <span>wearepet@petshop.com</span>

        <span class="text-primary"><i class="fas fa-map-marker-alt"></i></span>
        <span>123 Lý Thường Kiệt, Phường 3, Quận 10, Thành phố Hồ Chí Minh</span>
      </div>
      <div class="flex items-center gap-2 min-w-[160px] justify-end">
        <span class="text-primary"><i class="fas fa-user"></i></span>
        <router-link v-if="isLoggedIn" to="/account" class="text-gray-800 hover:text-orange-500">
          {{ userName }}
        </router-link>
        <router-link v-else to="/login" class="text-gray-800 hover:text-orange-500">
          Đăng nhập
        </router-link>
      </div>
    </div>

    <!-- Navbar -->
    <nav>
      <div class="flex items-center font-bold text-xl">
        <span class="text-2xl mr-2 text-primary"><i class="fas fa-paw"></i></span>
        <router-link to="/" class="text-gray-800 hover:text-orange-500">Pet World</router-link>
      </div>
      <ul>
        <li
          v-for="(item, index) in menuItems"
          :key="index"
          :class="{ 'active-menu': item.isActive }"
        >
          <router-link
            :to="item.path"
            class="text-gray-800 hover:text-orange-500 transition-all duration-200"
          >
            {{ item.name }}
          </router-link>
        </li>
      </ul>
      <div class="flex items-center">
        <div class="search-box">
          <input
            type="text"
            v-model="searchQuery"
            placeholder="Tìm kiếm sản phẩm"
            @keyup.enter="handleSearch"
          />
          <span class="search-icon" @click="handleSearch">
            <i class="fas fa-search"></i>
          </span>
        </div>
        <router-link to="/cart" class="icon-btn">
          <i class="fas fa-shopping-cart"></i>
          <span class="badge">{{ cartItemCount }}</span>
        </router-link>
      </div>
    </nav>
  </header>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useCart } from '../store/cart'

const router = useRouter()
const route = useRoute()

const cart = useCart()

const searchQuery = ref('')
const userName = ref('')
const isLoggedIn = ref(false)

const menuItems = computed(() => [
  { name: 'Trang chủ', path: '/', isActive: route.path === '/' },
  { name: 'Sản phẩm', path: '/products', isActive: route.path.startsWith('/products') },
  { name: 'Về chúng tôi', path: '/about', isActive: route.path === '/about' }
])

const cartItemCount = computed(() => cart.itemCount)

const handleSearch = () => {
  if (searchQuery.value.trim()) {
    router.push({
      path: '/products',
      query: { search: searchQuery.value.trim() }
    })
  }
}

onMounted(() => {
  const storedName = localStorage.getItem('user_name')
  if (storedName) {
    userName.value = storedName
    isLoggedIn.value = true
  }
})
</script>

<style scoped>
header {
  background: #fafbfc;
}

.topbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.875rem;
  color: #222;
  padding: 10px 40px 0 40px;
}

.topbar .flex-1 {
  flex: 1;
  display: flex;
  justify-content: center;
}

.topbar span,
.topbar a {
  margin-right: 10px;
  color: #222;
}

.topbar .text-primary {
  color: #ff9800;
}

nav {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: #fff;
  margin: 20px 40px 0 40px;
  border-radius: 2rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
  padding: 0 32px;
  height: 60px;
  position: relative;
}

nav .font-bold {
  font-weight: 700;
  font-size: clamp(1.1rem, 2vw, 1.3rem);
}

nav ul {
  display: flex;
  gap: 36px;
  list-style: none;
  margin: 0;
  padding: 0;
  align-items: center;
}

nav ul li {
  font-weight: 600;
  font-size: 1.1rem;
  position: relative;
}

nav ul li.active-menu > a,
nav ul li.active-menu {
  color: #ff9800 !important;
  border-bottom: 3px solid #ff9800;
  padding-bottom: 2px;
}

nav ul li a {
  color: #222;
  text-decoration: none;
  transition: color 0.2s;
  border-bottom: none !important;
}

nav ul li a:hover {
  color: #ff9800;
  border-bottom: none !important;
}

nav .flex.items-center {
  gap: 18px;
}

.search-box {
  position: relative;
  display: flex;
  align-items: center;
}

.search-box input {
  width: 180px;
  padding: 8px 36px 8px 16px;
  border-radius: 999px;
  border: none;
  background: #f5f5f5;
  font-size: 0.94rem;
  outline: none;
  color: #222;
}

.search-box .search-icon {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: #aaa;
  cursor: pointer;
  font-size: 1.1em;
}

.icon-btn {
  position: relative;
  margin-right: 8px;
  cursor: pointer;
}

.icon-btn:last-child {
  margin-right: 0;
}

.icon-btn i {
  font-size: 2rem;
  color: #222;
}

.badge {
  position: absolute;
  top: -8px;
  right: -10px;
  background: #ff9800;
  color: #fff;
  border-radius: 50%;
  width: 22px;
  height: 22px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.81rem;
  font-weight: bold;
  border: 2px solid #fff;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.07);
}

@media (max-width: 900px) {
  .topbar,
  nav {
    padding-left: 10px;
    padding-right: 10px;
  }

  nav {
    margin: 20px 10px 0 10px;
  }

  nav ul {
    gap: 18px;
  }
}

/* Tablet (768px) */
@media (max-width: 768px) {
  .header {
    padding: 10px 20px;
  }

  .logo {
    font-size: clamp(1.1rem, 4vw, 1.25rem);
  }

  .nav-links {
    display: none;
  }

  .mobile-menu-btn {
    display: block;
  }

  .mobile-menu {
    display: block;
  }

  .mobile-menu.active {
    transform: translateX(0);
  }

  .mobile-menu-links {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }

  .mobile-menu-links a {
    font-size: 1.125rem;
    padding: 10px 0;
  }

  .search-bar {
    width: 200px;
  }

  .search-bar input {
    font-size: 0.88rem;
  }

  .cart-icon {
    font-size: 1.25rem;
  }
}

/* Mobile (480px) */
@media (max-width: 480px) {
  .header {
    padding: 8px 15px;
  }

  .logo {
    font-size: 1.125rem;
  }

  .search-bar {
    width: 150px;
  }

  .search-bar input {
    font-size: 0.81rem;
    padding: 6px 10px;
  }

  .cart-icon {
    font-size: 1.125rem;
  }

  .mobile-menu {
    width: 100%;
  }

  .mobile-menu-links a {
    font-size: 1rem;
  }
}

/* Add smooth transitions */
.header,
.mobile-menu,
.search-bar,
.cart-icon {
  transition: all 0.3s ease;
}

/* Improve touch targets for mobile */
@media (hover: none) {
  .mobile-menu-btn,
  .cart-icon,
  .mobile-menu-links a {
    min-height: 44px;
    min-width: 44px;
  }
}
</style>
