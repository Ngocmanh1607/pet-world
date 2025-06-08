<template>
  <div class="base-pagination">
    <button
      class="page-btn nav-btn"
      :disabled="currentPage <= 1"
      @click="$emit('prev')"
      aria-label="Trang trước"
    >
      <i class="fa-solid fa-chevron-left"></i>
    </button>
    <button
      v-for="page in totalPages"
      :key="page"
      class="page-btn page-number"
      :class="{ active: page === currentPage }"
      @click="$emit('page', page)"
      :disabled="page === currentPage"
    >
      {{ page }}
    </button>
    <button
      class="page-btn nav-btn"
      :disabled="currentPage >= totalPages"
      @click="$emit('next')"
      aria-label="Trang sau"
    >
      <i class="fa-solid fa-chevron-right"></i>
    </button>
  </div>
</template>

<script setup>
defineProps({
  currentPage: {
    type: Number,
    required: true,
  },
  totalPages: {
    type: Number,
    required: true,
  },
});
</script>

<style scoped>
.base-pagination {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  margin: 32px 0 0 0;
  background: transparent;
}
.page-btn {
  min-width: 40px;
  height: 40px;
  padding: 0 12px;
  border-radius: 8px;
  border: 2px solid #e5e7eb;
  background: #fff;
  color: #222;
  font-size: 1rem;
  font-weight: 600;
  box-shadow: none;
  cursor: pointer;
  transition: border 0.2s, color 0.2s, box-shadow 0.2s, background 0.2s;
  outline: none;
  margin: 0 2px;
}
.page-btn:hover:not(:disabled) {
  border-color: #ff9800;
  color: #ff9800;
  background: #fff7ed;
}
.page-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  background: #f3f4f6;
  color: #888;
  border-color: #e5e7eb;
}
.page-number.active {
  background: #ff9800;
  color: #fff;
  border: none;
  box-shadow: none;
  font-weight: 700;
  z-index: 1;
}
.nav-btn {
  font-size: 1.2rem;
  font-weight: bold;
  padding: 0 10px;
}
@media (max-width: 600px) {
  .base-pagination {
    gap: 4px;
  }
  .page-btn {
    min-width: 28px;
    height: 32px;
    font-size: 1rem;
    padding: 0 6px;
  }
}
</style>
