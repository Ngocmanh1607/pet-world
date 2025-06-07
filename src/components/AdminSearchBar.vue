<template>
  <div class="search-bar">
    <input
      type="text"
      :placeholder="placeholder"
      v-model="inputValue"
      @input="onInput"
    />
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
const props = defineProps({
  modelValue: String,
  placeholder: {
    type: String,
    default: 'Tìm kiếm...',
  },
});
const emit = defineEmits(['update:modelValue', 'input']);

const inputValue = ref(props.modelValue || '');

watch(
  () => props.modelValue,
  (val) => {
    inputValue.value = val;
  }
);

function onInput() {
  emit('update:modelValue', inputValue.value);
  emit('input', inputValue.value);
}
</script>

<style scoped>
.search-bar input {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ddd;
  border-radius: 4px;
}
</style>
