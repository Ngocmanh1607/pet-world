<template>
  <div class="admin-login-wrapper">
    <div class="admin-login-card">
      <h1>Đăng nhập Quản trị</h1>
      <form @submit.prevent="handleLogin">
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" v-model="email" required />
        </div>
        <div class="form-group">
          <label for="password">Mật khẩu</label>
          <input type="password" id="password" v-model="password" required />
        </div>
        <div v-if="error" class="error-message">{{ error }}</div>
        <button type="submit" class="login-btn" :disabled="loading">
          <span v-if="loading">Đang đăng nhập...</span>
          <span v-else>Đăng nhập</span>
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
//import axios from 'axios'

const email = ref('');
const password = ref('');
const error = ref('');
const loading = ref(false);
const router = useRouter();

//const apiBaseUrl = process.env.VUE_APP_API_URL || 'http://localhost/api'

const handleLogin = async () => {
  error.value = '';
  if (!email.value || !password.value) {
    error.value = 'Vui lòng nhập đầy đủ thông tin.';
    return;
  }
  loading.value = true;
  // try {
  //   const res = await axios.post(`${apiBaseUrl}/admin/login`, {
  //     email: email.value,
  //     password: password.value
  //   })
  //   // Giả sử API trả về { token, user: { role } }
  //   if (res.data && res.data.token && res.data.user.role === 'admin') {
  //     localStorage.setItem('token', res.data.token)
  //     localStorage.setItem('role', 'admin')
  //     router.push('/admin')
  //   } else {
  //     error.value = 'Tài khoản không hợp lệ hoặc không phải admin.'
  //   }
  // } catch (e) {
  //   error.value = 'Đăng nhập thất bại. Vui lòng kiểm tra lại thông tin.'
  // } finally {
  //   loading.value = false
  // }

  // GIẢ LẬP: email và password cố định
  if (email.value === 'admin@petshop.com' && password.value === 'admin123') {
    localStorage.setItem('token', 'fake-admin-token');
    localStorage.setItem('role', 'admin');
    router.push('/admin/orders');
  } else {
    error.value = 'Tài khoản hoặc mật khẩu không đúng.';
  }
  loading.value = false;
};
</script>

<style scoped>
.admin-login-wrapper {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f5f7fa;
}
.admin-login-card {
  background: #fff;
  padding: 2.5rem 2rem;
  border-radius: 12px;
  box-shadow: 0 4px 24px 0 rgba(0, 0, 0, 0.08);
  min-width: 340px;
  max-width: 95vw;
}
.admin-login-card h1 {
  text-align: center;
  margin-bottom: 1.5rem;
  font-size: 1.5rem;
  color: #4a90e2;
}
.form-group {
  margin-bottom: 1.25rem;
}
.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 600;
  color: #333;
}
.form-group input {
  width: 100%;
  padding: 0.75rem 1rem;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 1rem;
  background: #f9f9f9;
}
.error-message {
  color: #ff4444;
  margin-bottom: 1rem;
  text-align: center;
}
.login-btn {
  width: 100%;
  padding: 0.75rem;
  background: #4a90e2;
  color: #fff;
  border: none;
  border-radius: 6px;
  font-size: 1.1rem;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s;
}
.login-btn:hover:not(:disabled) {
  background: #357ab8;
}
.login-btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}
</style>
