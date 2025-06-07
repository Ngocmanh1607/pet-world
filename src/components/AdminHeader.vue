<template>
  <header class="admin-header">
    <!-- Topbar -->
    <div class="admin-topbar">
      <div class="topbar-left">
        <span><i class="fas fa-phone"></i> +84 123 456 789</span>
        <span><i class="fas fa-envelope"></i> wearepet@petshop.com</span>
      </div>
      <div class="topbar-right">
        <span class="admin-link">
          <i class="fas fa-user"></i>
          <span>Quản lý cửa hàng</span>
        </span>
      </div>
    </div>
    <!-- Navbar -->
    <nav class="admin-navbar">
      <router-link to="/" class="logo">
        <i class="fas fa-paw"></i> Pet World
      </router-link>
      <ul>
        <li>
          <router-link to="/admin/customers" active-class="active-menu"
            >Khách hàng</router-link
          >
        </li>
        <li>
          <router-link to="/admin/orders" active-class="active-menu"
            >Đơn hàng</router-link
          >
        </li>
        <li>
          <router-link to="/admin/products" active-class="active-menu"
            >Sản phẩm</router-link
          >
        </li>
        <li>
          <router-link to="/admin/coupons" active-class="active-menu"
            >Mã khuyến mãi</router-link
          >
        </li>
        <li>
          <router-link to="/admin/statistics" active-class="active-menu"
            >Thống kê</router-link
          >
        </li>
      </ul>
    </nav>
  </header>
</template>

<script>
import { useCart } from '../store/cart'

export default {
  name: 'CustomerHeader',
  data() {
    return {
      searchQuery: '',
      isLoggedIn: false,
      userName: '',
    };
  },
  computed: {
    menuItems() {
      return [
        { name: 'Trang chủ', path: '/', isActive: this.isHome },
        { name: 'Sản phẩm', path: '/products', isActive: this.isProducts },
        { name: 'Về chúng tôi', path: '/about', isActive: this.isAbout },
      ];
    },
    isHome() {
      return this.$route.path === '/';
    },
    isProducts() {
      return this.$route.path.startsWith('/products');
    },
    isAbout() {
      return this.$route.path === '/about';
    },
    cartItemCount() {
      return this.cart.itemCount;
    },
  },
  methods: {
    handleSearch() {
      if (this.searchQuery.trim()) {
        this.$router.push({
          path: '/products',
          query: { search: this.searchQuery.trim() },
        });
      }
    },
    logout() {
      localStorage.removeItem('user_name');
      localStorage.removeItem('role');
      this.isLoggedIn = false;
      this.userName = '';
      this.$router.push('/login');
    }
  },
  mounted() {
    const userName = localStorage.getItem('user_name');
    if (userName) {
      this.userName = userName;
      this.isLoggedIn = true;
    }
  },
  setup() {
    const cart = useCart()
    return { cart }
  }
}
</script>


<style scoped>
.admin-header {
  background: #fafbfc;
  font-family: 'Inter', Arial, sans-serif;
}
.admin-topbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 15px;
  color: #222;
  padding: 8px 40px 0 40px;
}
.topbar-left {
  display: flex;
  gap: 24px;
}
.topbar-left i {
  color: #ff9800;
  margin-right: 4px;
}
.topbar-right {
  min-width: 160px;
  display: flex;
  justify-content: flex-end;
}
.admin-link {
  color: #ff9800;
  font-weight: 600;
  font-size: 1rem;
  display: flex;
  align-items: center;
  gap: 6px;
  border-bottom: 2px solid #ff9800;
  cursor: pointer;
  transition: color 0.2s;
}
.admin-link i {
  font-size: 1.1rem;
}
.admin-navbar {
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
.logo {
  font-weight: 700;
  font-size: 1.2rem;
  color: #222;
  display: flex;
  align-items: center;
  gap: 6px;
  text-decoration: none;
}
.logo i {
  color: #ff9800;
  font-size: 1.3rem;
}
.admin-navbar ul {
  display: flex;
  gap: 36px;
  list-style: none;
  margin: 0;
  padding: 0;
  align-items: center;
}
.admin-navbar ul li {
  font-weight: 600;
  font-size: 1.1rem;
  position: relative;
}
.admin-navbar ul li .router-link-exact-active,
.admin-navbar ul li .active-menu {
  color: #ff9800 !important;
  border-bottom: 3px solid #ff9800;
  padding-bottom: 2px;
}
.admin-navbar ul li a {
  color: #222;
  text-decoration: none;
  transition: color 0.2s;
  border-bottom: none !important;
}
.admin-navbar ul li a:hover {
  color: #ff9800;
  border-bottom: none !important;
}
</style>
