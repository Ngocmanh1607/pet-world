import { ref, computed } from 'vue'
import axios from 'axios'

// State
const items = ref([])
const total = ref(0)
const loading = ref(false)
const error = ref(null)

// Getters
const cartItems = computed(() => items.value)
const cartTotal = computed(() => total.value)
const itemCount = computed(() => items.value.length)

// Actions
const fetchCart = async (customerId) => {
  try {
    loading.value = true
    const response = await axios.get(`/cart/${customerId}`)
    items.value = response.data.items
    calculateTotal()
  } catch (err) {
    error.value = 'Không thể tải giỏ hàng'
    console.error(err)
  } finally {
    loading.value = false
  }
}

const addItem = async (customerId, product) => {
  try {
    loading.value = true
    await axios.post(`/cart/${customerId}`, {
      product_id: product.id,
      quantity: 1
    })
    const existingItem = items.value.find(item => item.id === product.id)
    if (existingItem) {
      existingItem.quantity++
    } else {
      items.value.push({
        ...product,
        quantity: 1
      })
    }
    calculateTotal()
  } catch (err) {
    error.value = 'Không thể thêm sản phẩm vào giỏ hàng'
    console.error(err)
  } finally {
    loading.value = false
  }
}

const removeItem = async (customerId, productId) => {
  try {
    loading.value = true
    const cartItem = items.value.find(item => item.id === productId)
    if (cartItem) {
      await axios.delete(`/cart/${cartItem.cart_item_id}/${productId}`)
      const index = items.value.findIndex(item => item.id === productId)
      if (index > -1) {
        items.value.splice(index, 1)
        calculateTotal()
      }
    }
  } catch (err) {
    error.value = 'Không thể xóa sản phẩm khỏi giỏ hàng'
    console.error(err)
  } finally {
    loading.value = false
  }
}

const updateQuantity = async (customerId, productId, quantity) => {
  try {
    loading.value = true
    const cartItem = items.value.find(item => item.id === productId)
    if (cartItem) {
      await axios.put(`/cart/${customerId}`, {
        product_id: productId,
        quantity: quantity
      })
      cartItem.quantity = quantity
      calculateTotal()
    }
  } catch (err) {
    error.value = 'Không thể cập nhật số lượng sản phẩm'
    console.error(err)
  } finally {
    loading.value = false
  }
}

const calculateTotal = () => {
  total.value = items.value.reduce((sum, item) => {
    return sum + item.price * item.quantity
  }, 0)
}

const clearCart = async (customerId) => {
  try {
    loading.value = true
    await axios.delete(`/cart/${customerId}`)
    items.value = []
    total.value = 0
  } catch (err) {
    error.value = 'Không thể xóa giỏ hàng'
    console.error(err)
  } finally {
    loading.value = false
  }
}

export const useCart = () => {
  return {
    // State
    items,
    total,
    loading,
    error,
    // Getters
    cartItems,
    cartTotal,
    itemCount,
    // Actions
    fetchCart,
    addItem,
    removeItem,
    updateQuantity,
    clearCart
  }
}
