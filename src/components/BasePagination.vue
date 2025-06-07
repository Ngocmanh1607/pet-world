<template>
  <div class="base-pagination">
    <button
      class="page-btn"
      :disabled="currentPage <= 1"
      @click="$emit('prev')"
      aria-label="Trang trước"
    >
      Trước
    </button>
    <span class="page-info">
      <slot> Trang {{ currentPage }} / {{ totalPages }} </slot>
    </span>
    <button
      class="page-btn"
      :disabled="currentPage >= totalPages"
      @click="$emit('next')"
      aria-label="Trang sau"
    >
      Sau
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
  gap: 36px;
  margin: 32px 0 0 0;
  background: transparent;
}
.page-btn {
  min-width: 90px;
  height: 48px;
  padding: 0 24px;
  border-radius: 12px;
  border: 1.5px solid #d1d5db;
  background: transparent;
  color: #222;
  font-size: 1rem;
  font-weight: 600;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
  cursor: pointer;
  transition:
    border 0.2s,
    color 0.2s,
    box-shadow 0.2s;
  outline: none;
  margin: 0 4px;
}
.page-btn:hover:not(:disabled) {
  border-color: #ff9800;
  color: #ff9800;
  box-shadow: 0 4px 16px rgba(255, 152, 0, 0.08);
}
.page-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  background: #f3f4f6;
  color: #888;
  border-color: #e5e7eb;
}
.page-info {
  font-size: 1rem;
  font-weight: 700;
  color: #222;
  min-width: 120px;
  text-align: center;
  letter-spacing: 0.5px;
}
@media (max-width: 600px) {
  .base-pagination {
    gap: 12px;
  }
  .page-btn {
    min-width: 60px;
    height: 36px;
    font-size: 1rem;
    padding: 0 10px;
  }
  .page-info {
    font-size: 1rem;
    min-width: 70px;
  }
}
</style>
