export const API_ENDPOINTS = {
  CART: {
    GET_CART: (customerId) => `/cart/${customerId}`,
    ADD_TO_CART: (customerId) => `/cart/${customerId}`,
    REMOVE_FROM_CART: (cartItemId, productId) =>
      `/cart/${cartItemId}/${productId}`,
  },
  ORDERS: {
    GET_ALL: '/orders',
    GET_ALL_CUS: `/orders/customer`,
    GET_DETAIL: (orderId) => `/orders/${orderId}`,
    CREATE_FROM_CART: '/orders/cart',
    CREATE_BUY_NOW: '/orders/buynow',
    UPDATE: (orderId) => `/orders/${orderId}`,
    DELETE: (orderId) => `/orders/${orderId}`,
    GET_STATISTICS: '/orders/statistics',
    GET_MONTHLY_REVENUE: '/orders/statistics/monthly-revenue',
  },
  PROMOTIONS: {
    GET_ALL: '/promotions',
    GET_DETAIL: (id) => `/promotions/${id}`,
    CREATE: '/promotions',
    UPDATE: (id) => `/promotions/${id}`,
    DELETE: (id) => `/promotions/${id}`,
    CHECK_CODE: (code) => `/promotions/code/${code}`,
  },
  CUSTOMERS: {
    GET_COUNT: '/customers/count',
    GET_ADDRESS: (id) => `/customers/${id}/address`,
    SAVE_ADDRESS: `/customer/address`,
    DELETE_ADDRESS: (id) => `/customers/${id}/address`,
  },
  PRODUCTS: {
    GET_ALL: '/products',
    GET_DETAIL: (id) => `/products/${id}`,
    CREATE: '/products',
    UPDATE: (id) => `/products/${id}`,
    DELETE: (id) => `/products/${id}`,
    GET_CATEGORIES: '/categories',
    GET_BEST_CATEGORIES: '/products/statistics/best-categories',
  },
};
