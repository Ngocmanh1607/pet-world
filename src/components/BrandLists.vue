<template>
  <div>
    <div
      class="grid grid-cols-3 gap-4 place-items-center md:grid-cols-4 lg:grid-cols-8"
    >
      <div
        class="flex flex-col justify-between rounded py-4 space-y-6 shadow-md max-w-[120px] w-full"
        v-for="brand in brands"
        :key="brand.name"
      >
        <img class="w-full" :src="brand.photo" :alt="brand.name" />
        <div class="text-xs text-center">{{ brand.name }}</div>
      </div>
    </div>
  </div>
</template>

<script setup>
const brands = [
  {
    photo: '/images/brand/nutrience_logo.png',
    name: 'Nutrience',
  },
  {
    photo: '/images/brand/royal_canin_logo.png',
    name: 'Royal Canin',
  },
  {
    photo: '/images/brand/hi-raw_logo.png',
    name: 'Hi Raw!',
  },
  {
    photo: '/images/brand/equilibrio_logo.png',
    name: 'Equilibrio',
  },
  {
    photo: '/images/brand/kit_cat_logo.png',
    name: 'Kit Cat',
  },
  {
    photo: '/images/brand/tropiclean_logo.png',
    name: 'Tropiclean',
  },
  {
    photo: '/images/brand/ciao_logo.png',
    name: 'Ciao Churu',
  },
  {
    photo: '/images/brand/nekko_logo.png',
    name: 'Nekko',
  },
  {
    photo: '/images/brand/monge_logo.png',
    name: 'Monge',
  },
  {
    photo: '/images/brand/zenith_logo.png',
    name: 'Zenith',
  },
  {
    photo: '/images/brand/anf_logo.png',
    name: 'ANF',
  },
  {
    photo: '/images/brand/gimcat_logo.png',
    name: 'Gimcat',
  },
  {
    photo: '/images/brand/inaba_logo.png',
    name: 'Inaba',
  },
  {
    photo: '/images/brand/meo_logo.png',
    name: 'Me-O',
  },
  {
    photo: '/images/brand/inaba_logo.png',
    name: 'Lapaw',
  },
  {
    photo: '/images/brand/absolute_hoistic_logo.png',
    name: 'Absolute Hoistic',
  },
];
</script>

<style scoped></style>
