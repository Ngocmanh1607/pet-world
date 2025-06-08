<template>
  <div class="flex flex-col max-h-[196px] h-full flex-1">
    <img
      class="object-cover w-full"
      :src="photo"
      :alt="`Cửa hàng ${shopName}`"
    />
    <div class="mt-4 space-y-2 text-center">
      <div class="font-bold">{{ shopName }}</div>
      <p class="text-xs">{{ address }}</p>
    </div>
  </div>
</template>

<script setup>
defineProps({
  shopName: String,
  address: String,
  photo: String,
});
</script>

<style scoped></style>
