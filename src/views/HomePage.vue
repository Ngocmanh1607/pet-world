<template>
  <div class="pb-24 homepage">
    <!-- BannerSection -->
    <section class="banner-section">
      <div class="banner-content">
        <span class="brand">Pet World</span>
        <h1>Thiên đường mua sắm dành cho thú cưng!</h1>
        <p class="slogan">Dịch vụ tận tâm – Trải nghiệm mua sắm độc đáo!</p>
        <div class="cta-buttons">
          <router-link to="/products" class="cta-btn primary">Mua ngay</router-link>
        </div>
      </div>
      <div class="banner-img">
        <div class="hero-bg"></div>
        <img
          src="https://images.unsplash.com/photo-1518717758536-85ae29035b6d?auto=format&fit=crop&w=600&q=80"
          alt="Dog and Cat"
        />
        <span class="bird-icon">🕊️</span>
        <div class="paw-prints"></div>
      </div>
    </section>
    <!-- Others section -->
    <div class="max-w-6xl mx-auto mt-24">
      <!-- QuickCategorySection -->
      <QuickCategory class="max-w-[1050px] w-full mx-auto"></QuickCategory>

      <!-- ProductCategorySection -->
      <section class="mt-16 category-section">
        <h2 class="section-title">Danh mục sản phẩm</h2>
        <div class="category-container">
          <HomeCategory />
        </div>
      </section>

      <!-- BrandSection -->
      <section class="mt-24">
        <h2 class="text-3xl font-semibold text-center mb-8">1000+ Thương Hiệu Boss Thích</h2>
        <BrandLists class="mt-11"></BrandLists>
      </section>

      <!-- StoreSection -->
      <section class="mt-24 max-w-[1050px] mx-auto">
        <h2
          class="leading-[0.1em] bg-white border-b border-[#234BBB] text-center text-base font-bold"
        >
          <span class="px-2.5 pt-2.5 pb-0 bg-white text-[#234BBB]">Mua Trực Tiếp Tại Cửa Hàng</span>
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
    </div>
  </div>
</template>

<script setup>
import HomeCategory from '@/components/category/HomeCategory.vue'
import QuickCategory from '@/components/category/QuickCategory.vue'
import StoreLocation from '@/components/StoreLocation.vue'

import BrandLists from '@/components/BrandLists.vue'
import { stores } from '@/data/store'
</script>

<style scoped>
/* Thêm CSS layout cơ bản, bạn có thể tuỳ chỉnh thêm */
.homepage {
  background: #fafafa;
}

.header-bar {
  background: #fff;
  border-bottom: 1px solid #eee;
}

.header-top {
  display: flex;
  justify-content: space-between;
  padding: 8px 32px;
  font-size: 0.95em;
  color: #333;
}

.header-user {
  color: #f90;
}

.header-nav {
  display: flex;
  align-items: center;
  gap: 24px;
  padding: 12px 32px;
}

.logo {
  height: 48px;
  margin-right: 16px;
}

.search-bar {
  display: flex;
  align-items: center;
}

.search-bar input {
  padding: 6px 12px;
  border: 1px solid #ddd;
  border-radius: 4px 0 0 4px;
}

.search-bar button {
  padding: 6px 10px;
  border: none;
  background: #f90;
  color: #fff;
  border-radius: 0 4px 4px 0;
}

.cart-link {
  position: relative;
}

.header-user-mobile {
  display: none;
}

.banner-section {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin: 0 40px;
  margin-top: 32px;
  min-height: 420px;
  position: relative;
  z-index: 1;
  background: #fff;
  border-radius: 16px;
  padding: 32px;
  box-shadow: 0 4px 24px 0 rgba(0, 0, 0, 0.04);
}

.banner-content {
  flex: 1;
  z-index: 2;
}

.brand {
  color: #ff9000;
  font-size: clamp(1.1rem, 2vw, 1.375rem);
  font-weight: 700;
}

.banner-content h1 {
  font-size: clamp(2rem, 5vw, 2.375rem);
  font-weight: 800;
  margin: 12px 0 18px 0;
  line-height: 1.2;
  color: #222;
}

.slogan {
  color: #555;
  font-size: 1.06rem;
  margin-bottom: 32px;
}

.cta-buttons {
  display: flex;
  gap: 16px;
}

.cta-btn {
  padding: 14px 38px;
  font-size: 1.125rem;
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

.banner-img {
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

.banner-img img {
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

.quick-category-section {
  display: flex;
  gap: 24px;
  justify-content: center;
  margin: 32px 0;
}

.quick-category {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 2px 8px #eee;
  padding: 16px 32px;
  text-align: center;
  cursor: pointer;
}

.quick-category img {
  width: 120px;
  height: 80px;
  object-fit: cover;
  border-radius: 8px;
}

.product-category-section {
  background: #fff;
  border-radius: 16px;
  padding: 32px;
  margin-bottom: 24px;
}

.product-category-list {
  display: flex;
  gap: 32px;
  justify-content: center;
}

.product-category-item {
  text-align: center;
}

.product-category-item img {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border-radius: 8px;
  margin-bottom: 8px;
}

.product-category-item span {
  font-size: 1.125rem;
}

.bestseller-section {
  background: #fff;
  border-radius: 16px;
  padding: 32px;
  margin-bottom: 24px;
}

.bestseller-list {
  display: flex;
  gap: 32px;
  justify-content: center;
}

.bestseller-item {
  text-align: center;
}

.bestseller-item img {
  width: 120px;
  height: 160px;
  object-fit: cover;
  border-radius: 8px;
  margin-bottom: 8px;
}

.bestseller-item span {
  font-size: 1.125rem;
}

.brand-section {
  background: #fff;
  border-radius: 16px;
  padding: 32px;
  margin-bottom: 24px;
}

.brand-list {
  display: flex;
  gap: 24px;
  flex-wrap: wrap;
  justify-content: center;
}

.brand-list img {
  width: 100px;
  height: 40px;
  object-fit: contain;
  margin-bottom: 8px;
}

.store-section {
  background: #fff;
  border-radius: 16px;
  padding: 32px;
  margin-bottom: 24px;
}

.store-list {
  display: flex;
  gap: 32px;
  justify-content: center;
}

.store-item {
  text-align: center;
}

.store-item img {
  width: 180px;
  height: 120px;
  object-fit: cover;
  border-radius: 8px;
  margin-bottom: 8px;
}

.store-item span {
  font-size: 1.125rem;
}

@media (max-width: 900px) {
  .banner-section {
    flex-direction: column;
    margin: 0 10px;
    padding: 18px;
  }

  .banner-img {
    margin-top: 24px;
  }

  .banner-content {
    max-width: 100%;
  }

  .product-category-list,
  .bestseller-list,
  .store-list {
    flex-direction: column;
    gap: 16px;
  }
}

/* Tablet (768px) */
@media (max-width: 768px) {
  .hero {
    flex-direction: column;
    text-align: center;
    padding: 40px 20px;
  }

  .hero-content {
    margin-bottom: 30px;
  }

  .hero-content h1 {
    font-size: clamp(1.5rem, 5vw, 2rem);
  }

  .hero-content p {
    font-size: 1rem;
  }

  .cta-buttons {
    justify-content: center;
  }

  .featured-products {
    padding: 30px 20px;
  }

  .products-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
  }

  .product-card {
    padding: 15px;
  }

  .product-card img {
    height: 200px;
  }

  .categories {
    padding: 30px 20px;
  }

  .category-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
  }
}

/* Mobile (480px) */
@media (max-width: 480px) {
  .hero {
    padding: 30px 15px;
  }

  .hero-content h1 {
    font-size: 1.75rem;
  }

  .hero-content p {
    font-size: 0.94rem;
  }

  .cta-btn {
    padding: 10px 20px;
    font-size: 1rem;
  }

  .featured-products,
  .categories {
    padding: 20px 15px;
  }

  .section-title {
    font-size: 1.375rem;
  }

  .products-grid {
    grid-template-columns: 1fr;
  }

  .product-card {
    padding: 12px;
  }

  .product-card img {
    height: 180px;
  }

  .product-card h3,
  .category-card h3 {
    font-size: 1rem;
  }

  .product-card .price {
    font-size: 0.94rem;
  }

  .category-grid {
    grid-template-columns: 1fr;
  }

  .category-card {
    height: 120px;
  }

  .category-card h3 {
    font-size: 1rem;
  }
}

/* Add smooth transitions */
.hero,
.product-card,
.category-card,
.cta-btn {
  transition: all 0.3s ease;
}

category-section {
  max-width: 1200px;
  margin: 4rem auto 0;
  padding: 0 1.5rem;
}

.section-title {
  font-size: 1.875rem;
  font-weight: 600;
  color: #1a1a1a;
  margin-bottom: 2.75rem;
  text-align: center;
}

.category-container {
  width: 100%;
  background: transparent;
  border-radius: 1rem;
  padding: 1rem 0;
}

@media (max-width: 1024px) {
  .category-container {
    padding: 1rem;
  }
}

@media (max-width: 768px) {
  .category-section {
    margin-top: 3rem;
    padding: 0 1rem;
  }

  .section-title {
    font-size: 1.5rem;
    margin-bottom: 2rem;
  }
}

@media (max-width: 640px) {
  .category-section {
    margin-top: 2rem;
  }

  .section-title {
    font-size: 1.25rem;
    margin-bottom: 1.5rem;
  }
}

/* Improve touch targets for mobile */
@media (hover: none) {
  .cta-btn,
  .product-card button,
  .category-card {
    min-height: 44px;
    min-width: 44px;
  }
}
</style>
