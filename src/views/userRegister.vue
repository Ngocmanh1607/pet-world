<template>
  <div class="register-container">
    <div class="register-card">
      <div class="header">
        <img src="@/assets/Avatar.png" alt="Logo" class="logo" />
        <h2 class="title">Đăng ký tài khoản</h2>
      </div>

      <form @submit.prevent="register" class="register-form">
        <!-- Account Information -->
        <div class="form-section">
          <h3>Thông tin tài khoản</h3>

          <div class="form-group">
            <label>Tên đăng nhập</label>
            <input
              v-model="user_name"
              type="text"
              placeholder="Nhập tên đăng nhập"
              required
              :class="{ error: errors.user_name }"
            />
            <span v-if="errors.user_name" class="error-message">{{ errors.user_name }}</span>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Mật khẩu</label>
              <input
                v-model="password"
                type="password"
                placeholder="Nhập mật khẩu"
                required
                :class="{ error: errors.password }"
              />
              <span v-if="errors.password" class="error-message">{{ errors.password }}</span>
            </div>
            <div class="form-group">
              <label>Nhập lại mật khẩu</label>
              <input
                v-model="confirmPassword"
                type="password"
                placeholder="Nhập lại mật khẩu"
                required
                :class="{ error: errors.confirmPassword }"
              />
              <span v-if="errors.confirmPassword" class="error-message">{{
                errors.confirmPassword
              }}</span>
            </div>
          </div>
        </div>

        <!-- Personal Information -->
        <div class="form-section">
          <h3>Thông tin cá nhân</h3>

          <div class="form-group">
            <label>Họ và tên</label>
            <input
              v-model="customer_name"
              type="text"
              placeholder="Nhập họ và tên đầy đủ"
              required
              :class="{ error: errors.customer_name }"
            />
            <span v-if="errors.customer_name" class="error-message">{{
              errors.customer_name
            }}</span>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Email</label>
              <input
                v-model="email"
                type="email"
                placeholder="example@email.com"
                required
                :class="{ error: errors.email }"
              />
              <span v-if="errors.email" class="error-message">{{ errors.email }}</span>
            </div>
            <div class="form-group">
              <label>Số điện thoại</label>
              <input
                v-model="phone"
                type="tel"
                placeholder="0123 456 789"
                required
                :class="{ error: errors.phone }"
              />
              <span v-if="errors.phone" class="error-message">{{ errors.phone }}</span>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Ngày sinh</label>
              <input
                v-model="date_of_birth"
                type="date"
                required
                :class="{ error: errors.date_of_birth }"
              />
              <span v-if="errors.date_of_birth" class="error-message">{{
                errors.date_of_birth
              }}</span>
            </div>
            <div class="form-group">
              <label>Giới tính</label>
              <select v-model="gender" required :class="{ error: errors.gender }">
                <option value="">Chọn giới tính</option>
                <option value="Male">Nam</option>
                <option value="Female">Nữ</option>
                <option value="Other">Khác</option>
              </select>
              <span v-if="errors.gender" class="error-message">{{ errors.gender }}</span>
            </div>
          </div>
        </div>

        <!-- Address Information -->
        <div class="form-section">
          <h3>Địa chỉ</h3>

          <div class="form-row">
            <div class="form-group">
              <label>Tỉnh/Thành phố</label>
              <input
                v-model="province_name"
                type="text"
                placeholder="Nhập tỉnh/thành phố"
                required
                :class="{ error: errors.province_name }"
              />
              <span v-if="errors.province_name" class="error-message">{{
                errors.province_name
              }}</span>
            </div>
            <div class="form-group">
              <label>Quận/Huyện</label>
              <input
                v-model="district_name"
                type="text"
                placeholder="Nhập quận/huyện"
                required
                :class="{ error: errors.district_name }"
              />
              <span v-if="errors.district_name" class="error-message">{{
                errors.district_name
              }}</span>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Phường/Xã</label>
              <input
                v-model="ward_name"
                type="text"
                placeholder="Nhập phường/xã"
                required
                :class="{ error: errors.ward_name }"
              />
              <span v-if="errors.ward_name" class="error-message">{{ errors.ward_name }}</span>
            </div>
            <div class="form-group">
              <label>Địa chỉ chi tiết</label>
              <input
                v-model="address_line"
                type="text"
                placeholder="Số nhà, tên đường..."
                required
                :class="{ error: errors.address_line }"
              />
              <span v-if="errors.address_line" class="error-message">{{
                errors.address_line
              }}</span>
            </div>
          </div>
        </div>

        <button type="submit" :disabled="loading" class="submit-btn">
          {{ loading ? 'Đang xử lý...' : 'Đăng ký' }}
        </button>
      </form>

      <div class="footer">
        <p>Đã có tài khoản? <a @click.prevent="goToLogin" href="#">Đăng nhập</a></p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      user_name: '',
      password: '',
      confirmPassword: '',
      loading: false,
      customer_name: '',
      email: '',
      phone: '',
      date_of_birth: '',
      gender: '',
      address_line: '',
      ward_name: '',
      district_name: '',
      province_name: '',
      errors: {}
    }
  },
  methods: {
    validateForm() {
      this.errors = {}

      // Validate username
      if (!this.user_name.trim()) {
        this.errors.user_name = 'Tên đăng nhập không được để trống'
      } else if (this.user_name.length < 3) {
        this.errors.user_name = 'Tên đăng nhập phải có ít nhất 3 ký tự'
      } else if (this.user_name.length > 50) {
        this.errors.user_name = 'Tên đăng nhập không được vượt quá 50 ký tự'
      } else if (!/^[a-zA-Z0-9_]+$/.test(this.user_name)) {
        this.errors.user_name = 'Tên đăng nhập chỉ được chứa chữ cái, số và dấu gạch dưới'
      }

      // Validate password
      if (!this.password) {
        this.errors.password = 'Mật khẩu không được để trống'
      } else if (this.password.length < 6) {
        this.errors.password = 'Mật khẩu phải có ít nhất 6 ký tự'
      } else if (this.password.length > 100) {
        this.errors.password = 'Mật khẩu không được vượt quá 100 ký tự'
      }

      // Validate confirm password
      if (!this.confirmPassword) {
        this.errors.confirmPassword = 'Vui lòng nhập lại mật khẩu'
      } else if (this.password !== this.confirmPassword) {
        this.errors.confirmPassword = 'Mật khẩu không khớp'
      }

      // Validate customer name
      if (!this.customer_name.trim()) {
        this.errors.customer_name = 'Họ và tên không được để trống'
      } else if (this.customer_name.trim().length < 2) {
        this.errors.customer_name = 'Họ và tên phải có ít nhất 2 ký tự'
      } else if (this.customer_name.length > 100) {
        this.errors.customer_name = 'Họ và tên không được vượt quá 100 ký tự'
      } else if (!/^[a-zA-ZÀ-ỹ\s]+$/.test(this.customer_name.trim())) {
        this.errors.customer_name = 'Họ và tên chỉ được chứa chữ cái và khoảng trắng'
      }

      // Validate email
      if (!this.email.trim()) {
        this.errors.email = 'Email không được để trống'
      } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email)) {
        this.errors.email = 'Email không đúng định dạng'
      } else if (this.email.length > 255) {
        this.errors.email = 'Email không được vượt quá 255 ký tự'
      }

      // Validate phone
      if (!this.phone.trim()) {
        this.errors.phone = 'Số điện thoại không được để trống'
      } else if (!/^0[0-9]{9,10}$/.test(this.phone.replace(/\s/g, ''))) {
        this.errors.phone = 'Số điện thoại không đúng định dạng (10-11 số, bắt đầu bằng 0)'
      }

      // Validate date of birth
      if (!this.date_of_birth) {
        this.errors.date_of_birth = 'Ngày sinh không được để trống'
      }

      // Validate gender
      if (!this.gender) {
        this.errors.gender = 'Vui lòng chọn giới tính'
      }

      // Validate address fields
      if (!this.province_name.trim()) {
        this.errors.province_name = 'Tỉnh/Thành phố không được để trống'
      } else if (this.province_name.length > 100) {
        this.errors.province_name = 'Tỉnh/Thành phố không được vượt quá 100 ký tự'
      }

      if (!this.district_name.trim()) {
        this.errors.district_name = 'Quận/Huyện không được để trống'
      } else if (this.district_name.length > 100) {
        this.errors.district_name = 'Quận/Huyện không được vượt quá 100 ký tự'
      }

      if (!this.ward_name.trim()) {
        this.errors.ward_name = 'Phường/Xã không được để trống'
      } else if (this.ward_name.length > 100) {
        this.errors.ward_name = 'Phường/Xã không được vượt quá 100 ký tự'
      }

      if (!this.address_line.trim()) {
        this.errors.address_line = 'Địa chỉ chi tiết không được để trống'
      } else if (this.address_line.length > 255) {
        this.errors.address_line = 'Địa chỉ chi tiết không được vượt quá 255 ký tự'
      }

      return Object.keys(this.errors).length === 0
    },

    async register() {
      if (!this.validateForm()) {
        return
      }

      this.loading = true

      try {
        const requestData = {
          user_name: this.user_name.trim(),
          password: this.password,
          customer_name: this.customer_name.trim(),
          email: this.email.trim().toLowerCase(),
          phone: this.phone.replace(/\s/g, ''),
          date_of_birth: this.date_of_birth,
          gender: this.gender,
          address_line: this.address_line.trim(),
          ward_name: this.ward_name.trim(),
          district_name: this.district_name.trim(),
          province_name: this.province_name.trim()
        }

        const response = await fetch('http://localhost:8000/api/v1/register', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(requestData)
        })

        const data = await response.json()

        if (response.ok && data.success) {
          alert('Đăng ký thành công! Vui lòng đăng nhập.')
          this.$router.push('/login')
        } else {
          alert(data.message || 'Đăng ký thất bại!')
        }
      } catch (error) {
        console.error('Lỗi khi đăng ký:', error)
        alert('Có lỗi xảy ra. Vui lòng thử lại sau.')
      } finally {
        this.loading = false
      }
    },

    goToLogin() {
      this.$router.push('/login')
    }
  }
}
</script>

<style scoped>
.register-container {
  min-height: 100vh;
  background-color: #f5f5f5;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem 1rem;
}

.register-card {
  background: white;
  border-radius: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  padding: 2rem;
  width: 100%;
  max-width: 600px;
}

.header {
  text-align: center;
  margin-bottom: 2rem;
}

.logo {
  width: 80px;
  height: 80px;
  margin-bottom: 1rem;
}

.title {
  font-size: 1.8rem;
  font-weight: 600;
  color: #333;
  margin: 0;
}

.form-section {
  margin-bottom: 2rem;
  padding-bottom: 1.5rem;
  border-bottom: 1px solid #eee;
}

.form-section:last-of-type {
  border-bottom: none;
}

.form-section h3 {
  font-size: 1.1rem;
  font-weight: 600;
  color: #555;
  margin: 0 0 1.5rem 0;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  font-weight: 500;
  color: #333;
  margin-bottom: 0.5rem;
  font-size: 0.9rem;
}

.form-group input,
.form-group select {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 0.9rem;
  transition: border-color 0.2s ease;
}

.form-group input.error,
.form-group select.error {
  border-color: #dc3545;
  background-color: #fff5f5;
}

.error-message {
  color: #dc3545;
  font-size: 0.8rem;
  margin-top: 0.25rem;
  display: block;
}

.form-group input:focus,
.form-group select:focus {
  outline: none;
  border-color: #f49a00;
}

.form-group input::placeholder {
  color: #999;
}

.form-group select:disabled {
  background-color: #f8f8f8;
  color: #999;
  cursor: not-allowed;
}

.submit-btn {
  width: 100%;
  padding: 1rem;
  background-color: #f49a00;
  color: white;
  font-weight: 600;
  font-size: 1rem;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: background-color 0.2s ease;
  margin-top: 1rem;
}

.submit-btn:hover:not(:disabled) {
  background-color: #d88400;
}

.submit-btn:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}

.footer {
  text-align: center;
  margin-top: 1.5rem;
  padding-top: 1.5rem;
  border-top: 1px solid #eee;
}

.footer p {
  color: #666;
  margin: 0;
  font-size: 0.9rem;
}

.footer a {
  color: #f49a00;
  text-decoration: none;
  font-weight: 500;
}

.footer a:hover {
  text-decoration: underline;
}

/* Mobile responsive */
@media (max-width: 768px) {
  .register-container {
    padding: 1rem;
  }

  .register-card {
    padding: 1.5rem;
  }

  .form-row {
    grid-template-columns: 1fr;
  }

  .title {
    font-size: 1.5rem;
  }
}
</style>
