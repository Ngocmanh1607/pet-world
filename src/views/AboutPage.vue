<template>
  <div class="pb-24 about-wrapper">
    <!-- HERO -->
    <section class="about-hero">
      <div class="about-hero-content">
        <span class="brand">Pet World</span>
        <h1>
          Nếu các Boss có thể giao tiếp,<br />
          <span class="highlight">Câu chuyện của họ sẽ là về Chúng tôi!</span>
        </h1>
        <p class="slogan">
          <span>Chất lượng vượt trội</span> – <span>Dịch vụ tận tâm</span> –
          <span>Trải nghiệm mua sắm độc đáo!</span>
          <span>Chất lượng vượt trội</span> – <span>Dịch vụ tận tâm</span> –
          <span>Trải nghiệm mua sắm độc đáo!</span>
        </p>
        <div class="cta-buttons">
          <router-link to="/products" class="cta-btn primary"
            >Mua ngay</router-link
          >
          <router-link to="/contact" class="cta-btn secondary"
            >Liên hệ</router-link
          >
        </div>
      </div>
      <div class="about-hero-img">
        <div class="hero-bg"></div>
        <img
          src="https://images.unsplash.com/photo-1518717758536-85ae29035b6d?auto=format&fit=crop&w=600&q=80"
          alt="Dog and Cat"
        />
        <span class="bird-icon">🕊️</span>
        <div class="paw-prints"></div>
      </div>
    </section>

    <!-- VỀ CHÚNG TÔI -->
    <section class="max-w-5xl mx-auto">
      <h2 class="section-title text-primary">VỀ CHÚNG TÔI</h2>
      <div class="space-y-6">
        <div>
          <b class="text-primary">Pet World </b>
          <span>– Vì một thế giới hoàn hảo cho thú cưng</span>
        </div>
        <div>
          <b class="text-primary">Pet World</b>
          <span>
            không chỉ là một nền tảng mua sắm, mà còn là người bạn đồng hành tin cậy trên hành trình
            chăm sóc thú cưng của bạn. Được thành lập với niềm đam mê dành cho động vật, chúng tôi
            mang đến một hệ sinh thái thú cưng điển hình, giúp bạn dễ dàng tìm thấy mọi thứ mà thú
            cưng cần – từ thực phẩm dinh dưỡng, phụ kiện thời trang, đồ chơi cho đến dịch vụ chăm
            sóc sức khỏe, spa, huấn luyện.</span>
        </div>
        <div>
          Với sự hợp tác từ những thương hiệu hàng đầu và các chuyên gia thú y,
          <b class="text-primary">Pet World cam kết</b> mang đến sản phẩm chất
          lượng cao, an toàn và phù hợp nhất cho từng bé cưng. Chúng tôi tin
          rằng mỗi thú cưng đều xứng đáng được yêu thương, chăm sóc tốt nhất, và
          mỗi người nuôi đều xứng đáng có trải nghiệm mua sắm tiện lợi, nhanh
          chóng và đáng tin cậy.
        </div>
      </div>
      <div class="flex items-center justify-around mt-8 space-x-8">
        <div class="text-center">
          <div class="text-2xl font-bold text-primary">10.000+</div>
          <div class="text-xs">Khách hàng mỗi năm</div>
        </div>
        <div class="text-center">
          <div class="text-2xl font-bold text-primary">10</div>
          <div class="text-xs">Chi nhánh</div>
        </div>
        <div class="text-center">
          <div class="text-2xl font-bold text-primary">200+</div>
          <div class="text-xs">Sản phẩm</div>
        </div>
        <div class="text-center">
          <div class="text-2xl font-bold text-primary">5+</div>
          <div class="text-xs">Năm kinh nghiệm</div>
        </div>
      </div>
    </section>

    <!-- TESTIMONIAL -->
    <div
      class="flex flex-col items-center max-w-5xl mx-auto space-x-8 md:flex-row mt-9"
    >
      <div class="flex-1">
        <div class="text-xs font-bold text-primary">Chứng thực</div>
        <div class="mt-2 text-3xl font-bold">
          Khách hàng nói gì về chúng tôi?
        </div>
        <!-- Dynamic testimoial -->
        <div class="mt-6">
          <div class="flex space-x-2">
            <i class="fa-solid fa-star" style="color: #ffb74d"></i>
            <i class="fa-solid fa-star" style="color: #ffb74d"></i>
            <i class="fa-solid fa-star" style="color: #ffb74d"></i>
            <i class="fa-solid fa-star" style="color: #ffb74d"></i>
            <i class="fa-solid fa-star" style="color: #ffb74d"></i>
          </div>
          <div
            class="mt-2 text-base italic"
            v-html="currentTestimonial.text"
          ></div>
          <div class="flex justify-between mt-8">
            <div>
              <div class="text-[18px] font-bold">
                {{ currentTestimonial.author.name }}
              </div>
              <div class="text-xs font-bold">
                {{ currentTestimonial.author.title }}
              </div>
            </div>
            <div class="flex space-x-4">
              <button
                class="flex items-center justify-center bg-black w-[30px] h-[30px] rounded-full"
                @click="prevTestimonial()"
              >
                <i
                  class="fa-solid fa-angle-left fa-xs"
                  style="color: #ffffff"
                ></i>
              </button>
              <button
                class="flex items-center justify-center bg-black w-[30px] h-[30px] rounded-full"
                @click="nextTestimonial()"
              >
                <i
                  class="fa-solid fa-angle-right fa-xs"
                  style="color: #ffffff"
                ></i>
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="h-[375px] flex-1 flex justify-center">
        <img
          class="object-cover h-full"
          :src="currentTestimonial.author.image"
          :alt="currentTestimonial.author.name"
        />
      </div>
    </div>

    <!-- ĐỐI TÁC CHIẾN LƯỢC -->
    <section class="mt-24 max-w-[1050px] mx-auto">
      <h2 class="text-3xl font-semibold text-primary">
        Các đối tác chiến lược
      </h2>
      <BrandLists class="mt-11"></BrandLists>
    </section>

    <!-- HỆ THỐNG CỬA HÀNG -->
    <section class="mt-24 max-w-[1050px] mx-auto">
      <h2
        class="leading-[0.1em] bg-white border-b border-[#234BBB] text-center text-3xl font-bold"
      >
        <span class="px-2.5 pt-2.5 pb-0 bg-white text-primary"
          >Hệ thống cửa hàng</span
        >
      </h2>
      <div class="flex items-center justify-center mt-6 space-x-6">
        <StoreLocation
          v-for="store in stores"
          :key="store.address"
          :photo="store.photo"
          :shop-name="store.shopName"
          :address="store.address"
        ></StoreLocation>
      </div>
    </section>

    <!-- LIÊN HỆ -->
    <!-- <section class="contact-section">
      <div class="contact-form">
        <h2>Liên hệ chúng tôi</h2>
        <form @submit.prevent="handleSubmit">
          <div class="form-row">
            <div class="form-group">
              <input type="text" v-model="form.lastName" placeholder="Họ" required />
              <span class="error" v-if="errors.lastName">{{ errors.lastName }}</span>
            </div>
            <div class="form-group">
              <input type="text" v-model="form.firstName" placeholder="Tên của bạn" required />
              <span class="error" v-if="errors.firstName">{{ errors.firstName }}</span>
            </div>
          </div>
          <div class="form-group">
            <input type="email" v-model="form.email" placeholder="Địa chỉ e-mail" required />
            <span class="error" v-if="errors.email">{{ errors.email }}</span>
          </div>
          <div class="form-group">
            <textarea v-model="form.message" placeholder="Nội dung" required></textarea>
            <span class="error" v-if="errors.message">{{ errors.message }}</span>
          </div>
          <button type="submit" :disabled="isSubmitting">
            <i class="fas fa-paper-plane"></i>
            {{ isSubmitting ? 'Đang gửi...' : 'Gửi' }}
          </button>
        </form>
      </div>
      <div class="contact-info">
        <h3>Liên hệ chúng tôi</h3>
        <ul>
          <li>
            <i class="fas fa-map-marker-alt"></i>
            <div>
              <b>Địa chỉ:</b>
              <span>123 Lý Thường Kiệt, Phường 3, Quận 10, Thành phố Hồ Chí Minh</span>
            </div>
          </li>
          <li>
            <i class="fas fa-envelope"></i>
            <div>
              <b>Email:</b>
              <span>petworld@gmail.com</span>
            </div>
          </li>
          <li>
            <i class="fas fa-phone"></i>
            <div>
              <b>Điện thoại:</b>
              <span>+84 123 456 789</span>
            </div>
          </li>
          <li>
            <i class="fas fa-clock"></i>
            <div>
              <b>Giờ mở cửa:</b>
              <span>Mon - Fri: 10AM - 10PM</span>
            </div>
          </li>
        </ul>
        <div class="social-links">
          <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
          <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
          <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
          <a href="#" class="social-link"><i class="fab fa-tiktok"></i></a>
        </div>
      </div>
    </section> -->
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import StoreLocation from '@/components/StoreLocation.vue';
import BrandLists from '@/components/BrandLists.vue';
// Form state
// const form = reactive({
//   firstName: '',
//   lastName: '',
//   email: '',
//   message: ''
// })

// const errors = reactive({})
// const isSubmitting = ref(false)

// Testimonials
const testimonials = [
  {
    text: '<b>Pet World thực sự là thiên đường cho thú cưng!</b> Tôi đã tìm thấy mọi thứ mình cần cho bé cún của mình, từ thức ăn, phụ kiện đến dịch vụ spa. Đội ngũ nhân viên rất nhiệt tình tư vấn và cũng chuyên nghiệp!',
    author: {
      name: 'Phạm Nhật Vượng',
      title: 'Khách hàng',
      image: '/images/phamnhatvuong.png',
    },
  },
  {
    text: '<b>Dịch vụ tuyệt vời!</b> Tôi rất ấn tượng với sự chuyên nghiệp và tận tâm của đội ngũ Pet World. Các sản phẩm đều chất lượng và giá cả hợp lý.',
    author: {
      name: 'Nguyễn Thị Phương Thảo',
      title: 'Khách hàng thân thiết',
      image: '/images/phuongthao.png',
    },
  },
  {
    text: '<b>Meo nhà tôi rất thích!</b> Thức ăn và đồ chơi từ Pet World luôn là lựa chọn hàng đầu của tôi. Giao hàng nhanh chóng và đóng gói cẩn thận.',
    author: {
      name: 'Elon musk',
      title: 'Khách hàng',
      image: '/images/elonmusk.png',
    },
  },
];
// Index hiện tại
const currentIndex = ref(0);

// Lấy testimonial hiện tại từ index
const currentTestimonial = computed(() => testimonials[currentIndex.value]);

// Hàm chuyển testimonial sang phải
function nextTestimonial() {
  currentIndex.value = (currentIndex.value + 1) % testimonials.length;
}

// Hàm chuyển testimonial sang trái
function prevTestimonial() {
  currentIndex.value =
    (currentIndex.value - 1 + testimonials.length) % testimonials.length;
}

// Form submission handler
// function handleSubmit() {
//   Object.keys(errors).forEach(key => delete errors[key])
//   isSubmitting.value = true

//   // Validate form
//   if (!form.firstName) {
//     errors.firstName = 'Vui lòng nhập tên'
//   }
//   if (!form.lastName) {
//     errors.lastName = 'Vui lòng nhập họ'
//   }
//   if (!form.email) {
//     errors.email = 'Vui lòng nhập email'
//   } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
//     errors.email = 'Email không hợp lệ'
//   }
//   if (!form.message) {
//     errors.message = 'Vui lòng nhập nội dung'
//   }

//   if (Object.keys(errors).length === 0) {
//     // Simulate API call
//     setTimeout(() => {
//       isSubmitting.value = false
//       form.firstName = ''
//       form.lastName = ''
//       form.email = ''
//       form.message = ''
//       alert('Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất có thể.')
//     }, 1500)
//   } else {
//     isSubmitting.value = false
//   }
// }
// Auto slide on mounted
onMounted(() => {
  setInterval(() => {
    nextTestimonial();
  }, 5000);
});
import { stores } from '@/data/store';
</script>

<style scoped>
.about-wrapper {
  background: #fafbfc;
  font-family: 'Inter', Arial, sans-serif;
}

/* HERO */
.about-hero {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin: 0 40px;
  margin-top: 32px;
  min-height: 420px;
  position: relative;
  z-index: 1;
}

.about-hero-content {
  flex: 1;
  z-index: 2;
}

.brand {
  color: #ff9000;
  font-size: 22px;
  font-weight: 700;
}

.about-hero-content h1 {
  font-size: 38px;
  font-weight: 800;
  margin: 12px 0 18px 0;
  line-height: 1.2;
}

.about-hero-content .highlight {
  color: #222;
}

.slogan {
  color: #555;
  font-size: 17px;
  margin-bottom: 32px;
}

.cta-buttons {
  display: flex;
  gap: 16px;
}

.cta-btn {
  padding: 14px 38px;
  font-size: 18px;
  font-weight: 600;
  border-radius: 12px;
  text-decoration: none;
  transition: all 0.2s;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.cta-btn.primary {
  background: #111;
  color: #fff;
}

.cta-btn.primary:hover {
  background: #ff9000;
  transform: translateY(-2px);
}

.cta-btn.secondary {
  background: #fff;
  color: #111;
  border: 2px solid #111;
}

.cta-btn.secondary:hover {
  background: #111;
  color: #fff;
  transform: translateY(-2px);
}

.about-hero-img {
  flex: 1;
  position: relative;
  display: flex;
  align-items: center;
  justify-content: flex-end;
  min-width: 350px;
  min-height: 350px;
}

.hero-bg {
  position: absolute;
  right: 0;
  width: 420px;
  height: 420px;
  background: radial-gradient(circle at 60% 40%, #ff9000 70%, #fff0 100%);
  border-radius: 60% 40% 60% 40% / 50% 60% 40% 50%;
  z-index: 1;
  opacity: 0.95;
}

.about-hero-img img {
  position: relative;
  width: 340px;
  height: 340px;
  object-fit: cover;
  border-radius: 40% 60% 50% 50% / 60% 40% 60% 40%;
  z-index: 2;
  box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.1);
}

.bird-icon {
  position: absolute;
  left: 30px;
  top: 30px;
  font-size: 60px;
  color: #ccc;
  opacity: 0.5;
  z-index: 3;
  pointer-events: none;
}

.paw-prints {
  position: absolute;
  right: 80px;
  top: 80px;
  width: 180px;
  height: 180px;
  z-index: 3;
  pointer-events: none;
  background: url('data:image/svg+xml;utf8,<svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg"><ellipse cx="20" cy="20" rx="8" ry="12" fill="%23fff6" /><ellipse cx="60" cy="20" rx="8" ry="12" fill="%23fff6" /><ellipse cx="30" cy="50" rx="10" ry="14" fill="%23fff6" /><ellipse cx="50" cy="50" rx="10" ry="14" fill="%23fff6" /></svg>');
  opacity: 0.3;
  background-repeat: no-repeat;
  background-size: contain;
}

/* ABOUT SECTION */
.about-section {
  background: #fff;
  border-radius: 16px;
  margin: 40px 40px 0 40px;
  padding: 40px 32px 32px 32px;
  box-shadow: 0 4px 24px 0 rgba(0, 0, 0, 0.04);
}

.section-title {
  font-size: 28px;
  font-weight: 700;
  margin-bottom: 24px;
  text-align: center;
}

.about-desc {
  color: #555;
  line-height: 1.8;
  margin-bottom: 40px;
  text-align: center;
}

.about-stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 32px;
  margin-top: 40px;
}

.stat-item {
  text-align: center;
  padding: 24px;
  background: #fff7f0;
  border-radius: 12px;
  transition: transform 0.2s;
}

.stat-item:hover {
  transform: translateY(-4px);
}

.stat-icon {
  font-size: 32px;
  color: #ff9000;
  margin-bottom: 16px;
}

.stat-number {
  display: block;
  color: #ff9000;
  font-size: 32px;
  font-weight: 800;
  display: block;
}
.stat-label {
  color: #555;
  font-size: 16px;
}

/* TESTIMONIAL */
.testimonial-section {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin: 40px 40px 0 40px;
  background: #fff;
  border-radius: 16px;
  padding: 32px;
  box-shadow: 0 4px 24px 0 rgba(0, 0, 0, 0.04);
}
.testimonial-content {
  flex: 2;
}
.testimonial-label {
  color: #ff9000;
  font-weight: 700;
  font-size: 18px;
}
.testimonial-quote {
  margin-top: 18px;
  font-size: 18px;
  color: #222;
  background: #fff7e6;
  border-radius: 12px;
  padding: 18px 24px;
  margin-bottom: 12px;
}
.testimonial-author {
  margin-top: 12px;
  color: #555;
  font-size: 16px;
}
.testimonial-img {
  flex: 1;
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
}
.testimonial-img-bg {
  position: absolute;
  width: 180px;
  height: 180px;
  background: #ff9000;
  border-radius: 50%;
  opacity: 0.2;
  z-index: 1;
}
.testimonial-img img {
  position: relative;
  width: 140px;
  height: 140px;
  object-fit: cover;
  border-radius: 50%;
  z-index: 2;
  border: 6px solid #fff;
  box-shadow: 0 4px 16px 0 rgba(0, 0, 0, 0.1);
}

/* PARTNER */
.partner-section {
  background: #fff;
  border-radius: 16px;
  margin: 40px 40px 0 40px;
  padding: 32px;
  box-shadow: 0 4px 24px 0 rgba(0, 0, 0, 0.04);
  text-align: center;
}
.partner-logos {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 32px;
  margin-top: 24px;
}
.partner-logos img {
  height: 48px;
  object-fit: contain;
  background: #fff;
  padding: 8px 16px;
  border-radius: 8px;
  box-shadow: 0 2px 8px #eee;
}

/* STORE */
.store-section {
  background: #fff;
  border-radius: 16px;
  margin: 40px 40px 0 40px;
  padding: 32px;
  box-shadow: 0 4px 24px 0 rgba(0, 0, 0, 0.04);
}
.store-list {
  display: flex;
  gap: 32px;
  justify-content: center;
  margin-top: 24px;
}
.store-item {
  text-align: center;
  background: #fff7e6;
  border-radius: 12px;
  padding: 18px 12px;
  width: 260px;
  box-shadow: 0 2px 8px #eee;
}
.store-item img {
  width: 220px;
  height: 120px;
  object-fit: cover;
  border-radius: 8px;
  margin-bottom: 8px;
}
.store-item span {
  font-weight: 700;
  color: #ff9000;
  font-size: 18px;
}
.store-item p {
  color: #555;
  font-size: 15px;
  margin-top: 4px;
}

/* CONTACT */
.contact-section {
  display: flex;
  gap: 48px;
  justify-content: center;
  margin: 40px 40px 0 40px;
  background: #fff;
  border-radius: 16px;
  padding: 32px;
  box-shadow: 0 4px 24px 0 rgba(0, 0, 0, 0.04);
}
.contact-form {
  flex: 1;
}
.contact-form h2 {
  color: #ff9000;
  margin-bottom: 18px;
}
.contact-form form {
  display: flex;
  flex-direction: column;
  gap: 14px;
}
.form-row {
  display: flex;
  gap: 12px;
}
.contact-form input,
.contact-form textarea {
  padding: 10px 14px;
  border-radius: 8px;
  border: 1px solid #eee;
  font-size: 16px;
  background: #fafbfc;
}
.contact-form textarea {
  min-height: 60px;
  resize: vertical;
}
.contact-form button {
  background: #ff9000;
  color: #fff;
  border: none;
  border-radius: 8px;
  padding: 12px 0;
  font-size: 18px;
  font-weight: 700;
  cursor: pointer;
  margin-top: 8px;
}
.contact-info {
  flex: 1;
  padding-left: 32px;
}
.contact-info h3 {
  color: #ff9000;
  margin-bottom: 12px;
}
.contact-info ul {
  list-style: none;
  padding: 0;
  color: #222;
  font-size: 16px;
}
.contact-info li {
  margin-bottom: 10px;
}

/* Tablet (768px) */
@media (max-width: 768px) {
  .about-hero {
    flex-direction: column;
    text-align: center;
    margin: 20px;
    min-height: auto;
  }

  .about-hero-content {
    margin-bottom: 40px;
  }

  .about-hero-content h1 {
    font-size: 28px;
  }

  .about-hero-img {
    justify-content: center;
    min-width: 250px;
    min-height: 250px;
  }

  .hero-bg {
    width: 300px;
    height: 300px;
  }

  .about-hero-img img {
    width: 250px;
    height: 250px;
  }

  .cta-buttons {
    justify-content: center;
  }

  .about-section,
  .testimonial-section,
  .partner-section,
  .store-section,
  .contact-section {
    margin: 20px;
    padding: 24px;
  }

  .about-stats {
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
  }

  .store-list {
    flex-direction: column;
    align-items: center;
    gap: 20px;
  }

  .store-item {
    width: 100%;
    max-width: 400px;
  }

  .contact-section {
    flex-direction: column;
    gap: 32px;
  }

  .contact-info {
    padding-left: 0;
  }

  .form-row {
    flex-direction: column;
    gap: 14px;
  }
}

/* Mobile (480px) */
@media (max-width: 480px) {
  .about-hero {
    margin: 10px;
  }

  .about-hero-content h1 {
    font-size: 24px;
  }

  .brand {
    font-size: 20px;
  }

  .slogan {
    font-size: 15px;
  }

  .cta-btn {
    padding: 10px 20px;
    font-size: 16px;
  }

  .about-section,
  .testimonial-section,
  .partner-section,
  .store-section,
  .contact-section {
    margin: 10px;
    padding: 16px;
  }

  .section-title {
    font-size: 22px;
  }

  .about-stats {
    grid-template-columns: 1fr;
  }

  .stat-item {
    padding: 12px;
  }

  .stat-number {
    font-size: 24px;
  }

  .testimonial-quote {
    font-size: 15px;
    padding: 12px 16px;
  }

  .testimonial-author {
    font-size: 14px;
  }

  .partner-logos {
    gap: 12px;
  }

  .partner-logos img {
    height: 32px;
  }

  .store-item {
    padding: 12px;
  }

  .store-item img {
    width: 100%;
    height: 100px;
  }

  .contact-form input,
  .contact-form textarea {
    font-size: 14px;
    padding: 8px 12px;
  }

  .contact-form button {
    font-size: 16px;
    padding: 10px 0;
  }

  .contact-info ul {
    font-size: 14px;
  }

  .social-links {
    justify-content: center;
  }
}

/* Add smooth transitions */
.about-hero,
.about-section,
.testimonial-section,
.partner-section,
.store-section,
.contact-section,
.stat-item,
.store-item,
.testimonial-quote {
  transition: all 0.3s ease;
}

/* Improve touch targets for mobile */
@media (hover: none) {
  .cta-btn,
  .store-map-btn,
  .social-link,
  .dot {
    min-height: 44px;
    min-width: 44px;
  }

  .form-group input,
  .form-group textarea {
    min-height: 44px;
  }
}
</style>
