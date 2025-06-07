<template>
  <div class="modal-overlay" @click.self="close">
    <div class="modal-content">
      <h3
        class="font-bold text-lg mb-4 flex items-center gap-2 text-orange-500"
      >
        <i class="fas fa-map-marker-alt"></i> Địa chỉ của tôi
      </h3>
      <div v-if="loading" class="text-center py-8">Đang tải...</div>
      <div v-else>
        <div
          v-for="address in addressList"
          :key="address.address_id"
          class="border rounded-lg p-4 mb-3 flex items-center bg-white"
        >
          <input
            type="radio"
            :value="address.address_id"
            v-model="selectedAddressId"
            class="mr-4 accent-orange-500"
          />
          <div class="flex-1">
            <div class="font-semibold">
              {{ address.name }}
              <span class="text-gray-500">({{ address.phone }})</span>
            </div>
            <div class="text-gray-600 text-sm">{{ address.address_line }}</div>
            <span
              v-if="address.is_default"
              class="text-xs text-orange-500 border border-orange-500 rounded px-2 py-1 ml-2"
              >Mặc định</span
            >
          </div>
          <button
            @click.stop="editAddress(address)"
            class="text-orange-500 font-semibold ml-4"
          >
            Cập nhật
          </button>
        </div>
        <div class="flex gap-3 mt-4">
          <button class="border rounded px-4 py-2" @click="close">Hủy</button>
          <button
            class="bg-orange-500 text-white rounded px-4 py-2"
            @click="confirm"
            :disabled="!selectedAddressId"
          >
            Xác nhận
          </button>
        </div>
      </div>
      <div
        v-if="showEditForm"
        class="fixed inset-0 z-60 flex items-center justify-center bg-black bg-opacity-50"
      >
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
          <h4 class="font-bold text-lg mb-4">Cập nhật địa chỉ</h4>
          <form @submit.prevent="saveEdit">
            <div class="mb-3">
              <label class="block mb-1 font-medium">Tên</label>
              <input
                v-model="editingAddress.name"
                class="w-full border rounded px-3 py-2"
                required
              />
            </div>
            <div class="mb-3">
              <label class="block mb-1 font-medium">Số điện thoại</label>
              <input
                v-model="editingAddress.phone"
                class="w-full border rounded px-3 py-2"
                required
              />
            </div>
            <div class="mb-3">
              <label class="block mb-1 font-medium">Địa chỉ</label>
              <input
                v-model="editingAddress.address_line"
                class="w-full border rounded px-3 py-2"
                required
              />
            </div>
            <div class="flex gap-2 mt-4">
              <button
                type="button"
                class="border rounded px-4 py-2"
                @click="showEditForm = false"
              >
                Hủy
              </button>
              <button
                type="submit"
                class="bg-orange-500 text-white rounded px-4 py-2"
              >
                Lưu
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const emit = defineEmits(['close', 'address-selected']);

const apiBaseUrl = process.env.VUE_APP_API_URL || 'http://localhost:8000/api/v1/'
const addressList = ref([])
const selectedAddressId = ref(null)
const loading = ref(false)
const editingAddress = ref(null)
const showEditForm = ref(false)

function close() {
  emit('close');
}

function confirm() {
  const selected = addressList.value.find(
    (a) => a.address_id === selectedAddressId.value
  );
  if (selected) {
    emit('address-selected', selected);
  }
}

function editAddress(address) {
  editingAddress.value = { ...address };
  showEditForm.value = true;
}

async function saveEdit() {
  try {
    await axios.put(`${apiBaseUrl}/address/update`, editingAddress.value)
    const userId = localStorage.getItem('user_id')
    const res = await axios.get(`${apiBaseUrl}/address`, {
      params: { customer_id: userId }
    })
    addressList.value = res.data
    showEditForm.value = false
  } catch (e) {
    alert('Cập nhật địa chỉ thất bại!');
  }
}

onMounted(async () => {
  loading.value = true;
  const userId = localStorage.getItem('user_id');
  try {
    const res = await axios.get(`${apiBaseUrl}/address`, {
      params: { customer_id: userId }
    })
    addressList.value = res.data
    // Chọn mặc định nếu có
    const def = addressList.value.find((a) => a.is_default);
    if (def) selectedAddressId.value = def.address_id;
  } catch (e) {
    // handle error
  } finally {
    loading.value = false;
  }
});
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.7);
  z-index: 50;
  display: flex;
  align-items: center;
  justify-content: center;
}
.modal-content {
  background: #fff;
  border-radius: 16px;
  padding: 32px 24px;
  min-width: 350px;
  max-width: 95vw;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.18);
}
</style>
