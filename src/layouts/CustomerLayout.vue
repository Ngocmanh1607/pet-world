<template>
  <div class="customer-layout">
    <CustomerHeader v-if="!isAuthPage" />
    <main>
      <router-view />
    </main>
    <FooterComponent v-if="!isAuthPage" />
  </div>
</template>

<script setup>
import { useRoute } from 'vue-router';
import { computed } from 'vue';
import CustomerHeader from '@/components/CustomerHeader.vue';
import FooterComponent from '@/components/FooterComponent.vue';

const route = useRoute();
// Ẩn header/footer cho các trang auth
const authPaths = ['/login', '/register', '/forgot', '/reset-password'];
const isAuthPage = computed(() =>
  authPaths.some((p) => route.path === p || route.path.startsWith(p + '/'))
);
</script>

<style scoped>
.customer-layout {
  min-height: 100vh;
  background: #ffffff;
  display: flex;
  flex-direction: column;
}
main {
  flex: 1;
}
</style>
