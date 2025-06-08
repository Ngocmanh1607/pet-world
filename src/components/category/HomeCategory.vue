<template>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <button v-for="category in categories" :key="category.id" @click="navigateToProducts(category)"
      class="flex flex-col overflow-hidden rounded-2xl hover:shadow-lg transition-shadow">
      <img class="min-h-[162px] w-full object-cover" :src="category.image" :alt="category.name" />
      <div class="px-4 py-3 bg-gray-100">
        <div class="flex items-center justify-between">
          <div class="text-base font-semibold">{{ category.name }}</div>
          <button class="ml-3 flex items-center justify-center shrink-0 w-[18px] h-[18px] rounded-full bg-white">
            <i class="fa-xs fa-solid fa-arrow-right" style="color: #f87537"></i>
          </button>
        </div>
        <div class="mt-2 text-xs text-left">{{ category.productCount }}+ sản phẩm</div>
      </div>
    </button>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const categories = ref([
  {
    id: 1,
    name: 'Thuc an',
    image: '/images/foodcategory.png',
    productCount: 150
  },
  {
    id: 2,
    name: 'Do choi',
    image: '/images/toycategory.png',
    productCount: 80
  },
  {
    id: 3,
    name: 'Phu kien cham soc',
    image: '/images/accessorycategory.png',
    productCount: 120
  },
  {
    id: 4,
    name: 'Vat dung ve sinh',
    image: '/images/cleancategory.png',
    productCount: 90
  }
]);
const router = useRouter();

const navigateToProducts = (category) => {
  router.push({
    path: '/products',
    query: {
      category: category.name,
    }
  });
};
</script>

<style scoped>
.grid {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1.5rem;
}

button {
  position: relative;
  transition: all 0.3s ease;
  border: 1px solid #e5e7eb;
  background: #ffffff;
}

button:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.img-wrapper {
  overflow: hidden;
  position: relative;
}

img {
  transition: transform 0.5s ease;
  width: 100%;
  height: 200px;
  object-fit: cover;
}

button:hover img {
  transform: scale(1.08);
}

.bg-gray-100 {
  background-color: #f9fafb;
  border-top: 1px solid #e5e7eb;
}

.fa-arrow-right {
  transition: transform 0.3s ease;
}

button:hover .fa-arrow-right {
  transform: translateX(3px);
}

@media (max-width: 768px) {
  .grid {
    padding: 0 1rem;
  }

  img {
    height: 180px;
  }
}
</style>