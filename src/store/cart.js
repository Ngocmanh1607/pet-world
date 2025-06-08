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
const fetchCart = async () => {
  try {
    loading.value = true
    const response = await axios.get('http://localhost:8000/api/v1/cart', {
      withCredentials: true
    })
    console.log('Cart response:', response);
    
    items.value = response.data.items || []
    calculateTotal()
  } catch (err) {
    error.value = 'Không thể tải giỏ hàng'
    console.error(err)
  } finally {
    loading.value = false
  }
}

const addItem = async (product, quantity = 1) => {
  try {
    loading.value = true
    
    // Extract product ID from different possible structures
    const productId = product.id || product.product_id || 
                    (product.product && product.product.product_id);
    
    if (!productId) {
      throw new Error('Cannot find product ID');
    }
    
    // Use POST to add item or update quantity (handles both positive and negative)
    await axios.post('http://localhost:8000/api/v1/cart', {
      product_id: productId,
      quantity: quantity
    }, {
      withCredentials: true
    })
    
    // After API call, refresh cart to get updated state from server
    await fetchCart()
    
  } catch (err) {
    error.value = 'Không thể thêm sản phẩm vào giỏ hàng'
    console.error('Error adding product to cart:', err)
  } finally {
    loading.value = false
  }
}

const removeItem = async (item) => {
  try {
    loading.value = true
    
    // Extract cart_item_id and product_id from the item
    let cartItemId, productId;
    
    if (typeof item === 'object') {
      cartItemId = item.cart_item_id;
      productId = item.product_id || 
                 (item.product && item.product.product_id);
    } else {
      // If item is just an ID, use it as both cart_item_id and product_id
      cartItemId = item;
      productId = item;
    }
    
    if (!cartItemId || !productId) {
      throw new Error('Missing cart_item_id or product_id');
    }
    
    // Use DELETE with the correct path parameters
    await axios.delete(`http://localhost:8000/api/v1/cart/${cartItemId}/${productId}`, {
      withCredentials: true
    })
    
    // After API call, refresh cart to get updated state from server
    await fetchCart()
    
  } catch (err) {
    error.value = 'Không thể xóa sản phẩm khỏi giỏ hàng'
    console.error('Error removing product from cart:', err)
  } finally {
    loading.value = false
  }
}

const updateQuantity = async (item, newQuantity) => {
  try {
    loading.value = true
    
    // Extract product ID from different possible structures
    let productId;
    
    if (typeof item === 'object') {
      productId = item.product_id || 
                 (item.product && item.product.product_id);
    } else {
      // If item is just an ID
      productId = item;
    }
    
    if (!productId) {
      throw new Error('Cannot find product ID');
    }
    
    // If the new quantity is 0 or negative, remove the item
    if (newQuantity <= 0) {
      await removeItem(item);
      return;
    }
    
    // Calculate quantity difference
    let currentQuantity = 1;
    if (typeof item === 'object') {
      currentQuantity = item.quantity || 1;
    }
    
    // Calculate difference (can be positive or negative)
    const quantityDiff = newQuantity - currentQuantity;
    
    // Use POST to update the quantity
    if (quantityDiff !== 0) {
      await axios.post('http://localhost:8000/api/v1/cart', {
        product_id: productId,
        quantity: quantityDiff // Send the difference (positive to add, negative to reduce)
      }, {
        withCredentials: true
      })
      
      // After API call, refresh cart to get updated state from server
      await fetchCart()
    }
    
  } catch (err) {
    error.value = 'Không thể cập nhật số lượng sản phẩm'
    console.error('Error updating product quantity:', err)
  } finally {
    loading.value = false
  }
}

const calculateTotal = () => {
  total.value = items.value.reduce((sum, item) => {
    const price = Number(item.price || 
                         (item.product && item.product.base_price) || 0);
    const quantity = Number(item.quantity || 1);
    return sum + price * quantity;
  }, 0)
}

const clearCart = async () => {
  try {
    loading.value = true
    
    // Since there's no direct "clear cart" endpoint, 
    // we need to remove each item individually
    const deletePromises = items.value.map(item => removeItem(item));
    await Promise.all(deletePromises);
    
    // Reset local state
    items.value = []
    total.value = 0
    
  } catch (err) {
    error.value = 'Không thể xóa giỏ hàng'
    console.error('Error clearing cart:', err)
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
    clearCart,
    calculateTotal
  }
}