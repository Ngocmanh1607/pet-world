<template>
  <footer class="footer-wrapper">
    <div class="footer-content">
      <!-- Cột 1: Logo + mô tả -->
      <div class="footer-col col-logo">
        <div class="footer-logo">
          <i class="fas fa-paw"></i>
          <span>Pet World</span>
        </div>
        <div class="footer-desc">
          Chúng tôi không chỉ phục vụ thú cưng – mà còn đồng hành cùng bạn trên
          hành trình yêu thương chúng.
        </div>
      </div>
      <!-- Cột 2: Pet Shop -->
      <div class="footer-col">
        <div class="footer-title">Pet World</div>
        <ul>
          <li>About us</li>
          <li>Gift cards</li>
        </ul>
      </div>
      <!-- Cột 3: Customer Service -->
      <div class="footer-col">
        <div class="footer-title">Customer Service</div>
        <ul>
          <li>Contact Us</li>
          <li>Shipping</li>
          <li>Returns</li>
        </ul>
      </div>
      <!-- Cột 4: Store -->
      <div class="footer-col">
        <div class="footer-title">Store</div>
        <div class="footer-store">
          <div>
            <b>123 Lý Thường Kiệt, Phường 3, Quận 10, Thành phố Hồ Chí Minh</b>
          </div>
          <div>+84 123 456 789</div>
          <div>wearepet@petshop.com</div>
        </div>
      </div>
    </div>
    <!-- Dòng logo thanh toán -->
    <div class="footer-bottom">
      <div class="footer-copyright">© Copyright Pet World 2025</div>
      <div class="footer-payments">
        <img
          src="https://upload.wikimedia.org/wikipedia/commons/4/41/Visa_Logo.png"
          alt="VISA"
        />
        <img
          src="https://upload.wikimedia.org/wikipedia/commons/0/04/Mastercard-logo.png"
          alt="MasterCard"
        />
        <img
          src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg"
          alt="PayPal"
        />
      </div>
    </div>
    <!-- Dấu chân mờ trang trí -->
    <div class="footer-paws">
      <span class="paw paw1"><i class="fas fa-paw"></i></span>
      <span class="paw paw2"><i class="fas fa-paw"></i></span>
      <span class="paw paw3"><i class="fas fa-paw"></i></span>
      <span class="paw paw4"><i class="fas fa-paw"></i></span>
      <span class="paw paw5"><i class="fas fa-paw"></i></span>
    </div>
  </footer>
</template>

<script setup>
// Không cần logic JS cho footer này
</script>

<style scoped>
.footer-wrapper {
  position: relative;
  width: 100%;
  background: #fafbfc;
  padding: 48px 0 0 0;
  overflow: hidden;
}
.footer-content {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  max-width: 1600px;
  margin: 0 auto;
  padding: 0 48px;
  gap: 32px;
}
.footer-col {
  flex: 1;
  min-width: 180px;
}
.col-logo {
  flex: 1.5;
  min-width: 260px;
}
.footer-logo {
  display: flex;
  align-items: center;
  font-size: clamp(1.3rem, 2vw, 1.75rem);
  font-weight: 700;
  gap: 8px;
  margin-bottom: 16px;
}
.footer-logo i {
  font-size: 32px;
  color: #222;
}
.footer-desc {
  font-size: 1.25rem;
  color: #222;
  margin-bottom: 32px;
}
.footer-copyright {
  color: #888;
  font-size: 1rem;
  margin-top: 32px;
}
.footer-title {
  font-size: clamp(1.1rem, 2vw, 1.25rem);
  font-weight: 700;
  margin-bottom: 16px;
  color: #222;
}
.footer-col ul {
  list-style: none;
  padding: 0;
  margin: 0;
}
.footer-col li {
  font-size: 1.125rem;
  color: #222;
  margin-bottom: 12px;
  cursor: pointer;
  transition: color 0.2s;
}
.footer-col li:hover {
  color: #ff9000;
}
.footer-store {
  font-size: 1.125rem;
  color: #222;
  margin-top: 8px;
}
.footer-bottom {
  display: flex;
  justify-content: space-between;
  align-items: center;
  max-width: 1600px;
  margin: 32px auto 0 auto;
  padding: 0 48px 24px 48px;
  gap: 16px;
}
.footer-payments {
  display: flex;
  align-items: center;
  gap: 16px;
}
.footer-payments img {
  height: 24px;
  filter: grayscale(1) contrast(0.8);
  opacity: 0.8;
  object-fit: contain;
}
.footer-copyright {
  color: #888;
  font-size: 1rem;
  margin: 0;
}
/* Dấu chân mờ trang trí */
.footer-paws {
  position: absolute;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  pointer-events: none;
  z-index: 0;
}
.paw {
  position: absolute;
  color: #e6e6e6;
  font-size: clamp(2.2rem, 6vw, 3.5rem);
  opacity: 0.35;
  user-select: none;
}
.paw1 {
  left: 18%;
  top: 18%;
  transform: rotate(-10deg);
}
.paw2 {
  left: 38%;
  top: 8%;
  font-size: 2.5rem;
}
.paw3 {
  left: 55%;
  top: 22%;
  font-size: 3rem;
}
.paw4 {
  left: 30%;
  top: 55%;
  font-size: 2.25rem;
}
.paw5 {
  left: 60%;
  top: 60%;
  font-size: 2.75rem;
}
@media (max-width: 900px) {
  .footer-content,
  .footer-bottom {
    flex-direction: column;
    align-items: flex-start;
    padding: 0 16px;
    gap: 16px;
  }
  .footer-content {
    gap: 24px;
  }
  .footer-payments img {
    margin-left: 16px;
    height: 24px;
  }
}

/* Tablet (768px) */
@media (max-width: 768px) {
  .footer {
    padding: 40px 20px;
  }

  .footer-content {
    flex-direction: column;
    gap: 30px;
  }

  .footer-section {
    width: 100%;
    text-align: center;
  }

  .footer-section h3 {
    font-size: 1.125rem;
    margin-bottom: 15px;
  }

  .footer-section p,
  .footer-section a {
    font-size: 0.88rem;
  }

  .social-links {
    justify-content: center;
    gap: 15px;
  }

  .social-links a {
    font-size: 1.25rem;
  }

  .footer-bottom {
    flex-direction: column;
    gap: 10px;
    text-align: center;
  }

  .footer-bottom p {
    font-size: 0.81rem;
  }
}

/* Mobile (480px) */
@media (max-width: 480px) {
  .footer {
    padding: 30px 15px;
  }

  .footer-content {
    gap: 25px;
  }

  .footer-section h3 {
    font-size: 1rem;
  }

  .footer-section p,
  .footer-section a {
    font-size: 0.81rem;
  }

  .social-links {
    gap: 12px;
  }

  .social-links a {
    font-size: 1.125rem;
  }

  .footer-bottom p {
    font-size: 0.75rem;
  }
}

/* Add smooth transitions */
.footer,
.footer-section,
.social-links a {
  transition: all 0.3s ease;
}

/* Improve touch targets for mobile */
@media (hover: none) {
  .footer-section a,
  .social-links a {
    min-height: 44px;
    min-width: 44px;
  }
}
</style>

<script>
export default {
  name: 'FooterComponent',
};
</script>
