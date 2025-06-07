<template>
  <div class="edit-form">
    <h2>Chỉnh sửa thông tin</h2>

    <label>Họ và tên</label>
    <input v-model="customer.name" type="text" />

    <label>Ngày sinh</label>
    <input v-model="customer.dob" type="text" placeholder="dd/mm/yyyy" />

    <label>Giới tính</label>
    <input v-model="customer.gender" type="text" />

    <label>Số điện thoại</label>
    <input v-model="customer.phone" type="text" />

    <label>Email</label>
    <input v-model="customer.email" type="email" />

    <div class="actions">
      <button class="cancel" @click="goBack">Hủy</button>
      <button class="save" @click="saveChanges">Lưu</button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';

// Router & Route
const route = useRoute();
const router = useRouter();
const id = parseInt(route.params.id);

// Dummy data (giả lập data thực tế từ database/API)
const allCustomers = ref([
  {
    id: 20462,
    name: 'Matt Dickerson',
    email: 'hat@example.com',
    dob: '13/05/2022',
    phone: '701112233',
    gender: 'Nam',
  },
  {
    id: 18935,
    name: 'Wiktoria',
    email: 'laptop@example.com',
    dob: '22/05/2022',
    phone: '701112233',
    gender: 'Nam',
  },
  {
    id: 54519,
    name: 'Trixie Byrd',
    email: 'phone@example.com',
    dob: '15/06/2022',
    phone: '701112233',
    gender: 'Nam',
  },
]);

const customer = ref({
  id: null,
  name: '',
  dob: '',
  gender: '',
  phone: '',
  email: '',
});

// Lấy dữ liệu khi vào trang
onMounted(() => {
  const found = allCustomers.value.find((c) => c.id === id);
  if (found) {
    customer.value = { ...found };
  }
});

// Lưu dữ liệu chỉnh sửa
const saveChanges = () => {
  // Gọi API hoặc cập nhật danh sách tại đây
  alert('Thông tin đã được lưu!');
  router.push('/');
};

// Hủy chỉnh sửa
const goBack = () => {
  router.back();
};
</script>

<style scoped>
.edit-form {
  max-width: 500px;
  margin: 40px auto;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  background: #fff;
  font-family: 'Arial', sans-serif;
}

h2 {
  text-align: center;
  margin-bottom: 20px;
}

label {
  font-weight: 600;
  display: block;
  margin-top: 15px;
}

input {
  width: 100%;
  padding: 10px;
  margin-top: 5px;
  border-radius: 6px;
  border: 1px solid #ccc;
}

.actions {
  margin-top: 25px;
  display: flex;
  justify-content: space-around;
}

button.cancel {
  background: none;
  border: 2px solid #f57c00;
  color: #f57c00;
  padding: 10px 25px;
  border-radius: 20px;
  cursor: pointer;
}

button.save {
  background-color: #f57c00;
  color: white;
  border: none;
  padding: 10px 25px;
  border-radius: 20px;
  cursor: pointer;
}
</style>
