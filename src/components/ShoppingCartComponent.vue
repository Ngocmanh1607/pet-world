<template>
  <div class="shopping-cart">
    <h2>Shopping Cart</h2>
    <div v-if="cartItems.length === 0" class="empty-cart">
      <p>Your cart is empty</p>
      <router-link to="/products" class="continue-shopping"
        >Continue Shopping</router-link
      >
    </div>
    <div v-else class="cart-content">
      <div class="cart-items">
        <div v-for="item in cartItems" :key="item.id" class="cart-item">
          <img :src="item.image" :alt="item.name" class="item-image" />
          <div class="item-details">
            <h3>{{ item.name }}</h3>
            <p class="price">${{ item.price }}</p>
            <div class="quantity-controls">
              <button
                @click="decreaseQuantity(item)"
                :disabled="item.quantity <= 1"
              >
                -
              </button>
              <span>{{ item.quantity }}</span>
              <button @click="increaseQuantity(item)">+</button>
            </div>
          </div>
          <button @click="removeItem(item)" class="remove-btn">Remove</button>
        </div>
      </div>
      <div class="cart-summary">
        <h3>Order Summary</h3>
        <div class="summary-item">
          <span>Subtotal:</span>
          <span>${{ subtotal }}</span>
        </div>
        <div class="summary-item">
          <span>Shipping:</span>
          <span>${{ shipping }}</span>
        </div>
        <div class="summary-item total">
          <span>Total:</span>
          <span>${{ total }}</span>
        </div>
        <button @click="checkout" class="checkout-btn">
          Proceed to Checkout
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { useCart } from '../store/cart'

export default {
  name: 'ShoppingCartComponent',
  data() {
    return {
      shipping: 5.99,
    };
  },
  computed: {
    cart() {
      return useCart()
    },
    cartItems() {
      return this.cart.items;
    },
    subtotal() {
      return this.cart.cartTotal.toFixed(2);
    },
    total() {
      return (parseFloat(this.subtotal) + this.shipping).toFixed(2);
    },
  },
  methods: {
    increaseQuantity(item) {
      this.cart.updateQuantity(item.id, item.quantity + 1);
    },
    decreaseQuantity(item) {
      if (item.quantity > 1) {
        this.cart.updateQuantity(item.id, item.quantity - 1);
      }
    },
    removeItem(item) {
      this.cart.removeItem(item.id);
    },
    checkout() {
      alert('Proceeding to checkout...')
    }
  },
  setup() {
    const cart = useCart()
    return {
      cart
    }
  }
}
</script>

<style scoped>
.shopping-cart {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

.empty-cart {
  text-align: center;
  padding: 40px;
}

.continue-shopping {
  display: inline-block;
  margin-top: 20px;
  padding: 10px 20px;
  background-color: #4caf50;
  color: white;
  text-decoration: none;
  border-radius: 4px;
}

.cart-content {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 20px;
}

.cart-items {
  background: white;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.cart-item {
  display: flex;
  align-items: center;
  padding: 15px 0;
  border-bottom: 1px solid #eee;
}

.item-image {
  width: 100px;
  height: 100px;
  object-fit: cover;
  border-radius: 4px;
  margin-right: 20px;
}

.item-details {
  flex-grow: 1;
}

.quantity-controls {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-top: 10px;
}

.quantity-controls button {
  width: 30px;
  height: 30px;
  border: 1px solid #ddd;
  background: white;
  border-radius: 4px;
  cursor: pointer;
}

.quantity-controls button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.remove-btn {
  padding: 8px 15px;
  background-color: #ff4444;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.cart-summary {
  background: white;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  height: fit-content;
}

.summary-item {
  display: flex;
  justify-content: space-between;
  margin: 10px 0;
  padding: 10px 0;
  border-bottom: 1px solid #eee;
}

.total {
  font-weight: bold;
  font-size: 1.2em;
}

.checkout-btn {
  width: 100%;
  padding: 15px;
  background-color: #4caf50;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  margin-top: 20px;
  font-size: 1.1em;
}

.checkout-btn:hover {
  background-color: #45a049;
}

@media (max-width: 768px) {
  .shopping-cart {
    padding: 15px;
  }

  .cart-content {
    grid-template-columns: 1fr;
    gap: 20px;
  }

  .cart-item {
    flex-direction: column;
    text-align: center;
    gap: 15px;
  }

  .item-image {
    width: 150px;
    height: 150px;
    margin: 0 auto;
  }

  .item-details {
    width: 100%;
  }

  .quantity-controls {
    justify-content: center;
  }

  .remove-btn {
    width: 100%;
    margin-top: 10px;
  }

  .cart-summary {
    position: sticky;
    bottom: 0;
    background: white;
    z-index: 10;
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
  }
}

@media (max-width: 480px) {
  .shopping-cart {
    padding: 10px;
  }

  .shopping-cart h2 {
    font-size: 20px;
  }

  .cart-item {
    padding: 10px 0;
  }

  .item-image {
    width: 120px;
    height: 120px;
  }

  .item-details h3 {
    font-size: 16px;
  }

  .price {
    font-size: 15px;
  }

  .quantity-controls button {
    width: 25px;
    height: 25px;
  }

  .cart-summary {
    padding: 15px;
  }

  .summary-item {
    font-size: 14px;
  }

  .checkout-btn {
    padding: 12px;
    font-size: 16px;
  }
}

.shopping-cart,
.cart-item,
.cart-summary,
.quantity-controls button,
.remove-btn,
.checkout-btn {
  transition: all 0.3s ease;
}

@media (hover: none) {
  .quantity-controls button,
  .remove-btn,
  .checkout-btn {
    min-height: 44px;
    min-width: 44px;
  }
}
</style>
