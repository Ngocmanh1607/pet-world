<template>
  <section
    class="flex items-center justify-between mx-10 mt-8 min-h-[420px] relative z-10"
  >
    <div class="flex items-center justify-between max-w-7xl mx-auto py-8 gap-8">
      <div class="z-20 flex-1">
        <span class="text-orange-500 text-xl font-bold tracking-wider"
          >Pet World</span
        >
        <h1
          class="text-4xl text-gray-800 mt-3 mb-4.5 font-extrabold leading-tight"
        >
          Thiên đường mua sắm dành cho thú cưng!
        </h1>
        <p class="text-gray-600 text-lg mb-0">
          Dịch vụ tận tâm – Trải nghiệm mua sắm độc đáo!
        </p>
      </div>
      <div
        class="flex-1 relative flex items-center justify-end min-w-[350px] min-h-[350px]"
      >
        <div
          class="absolute bottom-0 w-[420px] h-[420px] bg-gradient-radial from-orange-500 to-transparent rounded-[60%_40%_60%_40%_/_50%_60%_40%_50%] z-20 opacity-95"
        ></div>
        <img
          src="https://images.unsplash.com/photo-1567270671170-fdc10a5bf831?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
          alt="Dog and Cat"
          class="relative w-[340px] h-[340px] object-cover rounded-[40%_60%_50%_50%_/_60%_40%_60%_40%] z-20 shadow-lg"
        />
        <div
          class="absolute right-[30px] bottom-[10px] w-[60px] h-[30px] z-30 pointer-events-none opacity-30 bg-paw-prints"
        ></div>
      </div>
    </div>
  </section>
</template>

<script setup>
// No additional logic needed
</script>

<style>
.bg-gradient-radial {
  background: radial-gradient(circle at 60% 40%, #ff9000 70%, #fff0 100%);
}

.bg-paw-prints {
  background-image: url("data:image/svg+xml,%3Csvg width='60' height='30' viewBox='0 0 60 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cellipse cx='10' cy='15' rx='8' ry='12' fill='%23fff6' /%3E%3Cellipse cx='30' cy='10' rx='6' ry='8' fill='%23fff6' /%3E%3Cellipse cx='50' cy='15' rx='8' ry='12' fill='%23fff6' /%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-size: contain;
}
</style>
