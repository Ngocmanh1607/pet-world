<template>
  <div class="admin-layout">
    <AdminHeader />
    <AdminHero />
    <main>
      <router-view />
    </main>
    <FooterComponent />
  </div>
</template>

<script setup>
import AdminHeader from '@/components/AdminHeader.vue';
import AdminHero from '@/components/AdminHero.vue';
import FooterComponent from '@/components/FooterComponent.vue';
</script>

<style scoped>
.admin-layout {
  min-height: 100vh;
  background: #f5f5f5;
  display: flex;
  flex-direction: column;
}
main {
  flex: 1;
}
</style>
