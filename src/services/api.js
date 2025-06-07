import axios from 'axios';
import { API_ENDPOINTS } from '@/api/endpoints';

// Tạo instance axios với cấu hình mặc định
const axiosInstance = axios.create({
  baseURL:
    process.env.VUE_APP_API_URL ||
    'http://localhost:8000/api/v1',
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
});

// Cache cho các request GET
const cache = new Map();
const CACHE_TTL = 5 * 60 * 1000; // 5 phút

// Thêm interceptor để xử lý request
axiosInstance.interceptors.request.use(
  (config) => {
    // Lấy token từ localStorage
    const token = localStorage.getItem('admin_token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }

    // Xử lý cache cho GET request
    if (config.method === 'get' && !config.params?.noCache) {
      const cacheKey = `${config.url}${JSON.stringify(config.params || {})}`;
      const cachedData = cache.get(cacheKey);
      if (cachedData && Date.now() - cachedData.timestamp < CACHE_TTL) {
        return Promise.reject({
          __CACHE__: true,
          data: cachedData.data,
        });
      }
    }

    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Thêm interceptor để xử lý response
axiosInstance.interceptors.response.use(
  (response) => {
    // Lưu cache cho GET request
    if (response.config.method === 'get' && !response.config.params?.noCache) {
      const cacheKey = `${response.config.url}${JSON.stringify(response.config.params || {})}`;
      cache.set(cacheKey, {
        data: response.data,
        timestamp: Date.now(),
      });
    }
    return response;
  },
  async (error) => {
    // Xử lý cache hit
    if (error.__CACHE__) {
      return { data: error.data };
    }

    // Xử lý lỗi 401
    if (error.response?.status === 401) {
      localStorage.removeItem('admin_token');
      window.location.href = '/admin/login';
      return Promise.reject(error);
    }

    // Retry mechanism cho các request quan trọng
    const config = error.config;
    if (!config || !config.retry) {
      return Promise.reject(error);
    }

    config.retryCount = config.retryCount || 0;
    if (config.retryCount >= config.retry) {
      return Promise.reject(error);
    }

    config.retryCount += 1;
    const backoff = new Promise((resolve) => {
      setTimeout(() => {
        resolve();
      }, config.retryDelay || 1000);
    });

    await backoff;
    return axiosInstance(config);
  }
);

// Helper function để xử lý lỗi
const handleError = (error, defaultMessage = 'Có lỗi xảy ra') => {
  console.error(error);
  if (error.response?.data?.message) {
    throw new Error(error.response.data.message);
  }
  throw new Error(defaultMessage);
};

// Helper function để thêm retry config
const withRetry = (config = {}) => ({
  ...config,
  retry: 3,
  retryDelay: 1000,
});

export const productService = {
  getAll: async (params) => {
    try {
      const response = await axiosInstance.get(API_ENDPOINTS.PRODUCTS.GET_ALL, {
        params,
      });
      return response.data;
    } catch (error) {
      handleError(error, 'Không thể tải danh sách sản phẩm');
    }
  },

  getById: async (id) => {
    try {
      const response = await axiosInstance.get(
        API_ENDPOINTS.PRODUCTS.GET_DETAIL(id)
      );
      return response.data;
    } catch (error) {
      handleError(error, 'Không thể tải thông tin sản phẩm');
    }
  },

  create: async (data) => {
    try {
      const response = await axiosInstance.post(
        API_ENDPOINTS.PRODUCTS.CREATE,
        data,
        withRetry()
      );
      return response.data;
    } catch (error) {
      handleError(error, 'Không thể tạo sản phẩm mới');
    }
  },

  update: async (id, data) => {
    try {
      const response = await axiosInstance.put(
        API_ENDPOINTS.PRODUCTS.UPDATE(id),
        data,
        withRetry()
      );
      return response.data;
    } catch (error) {
      handleError(error, 'Không thể cập nhật sản phẩm');
    }
  },

  delete: async (id) => {
    try {
      await axiosInstance.delete(
        API_ENDPOINTS.PRODUCTS.DELETE(id),
        withRetry()
      );
    } catch (error) {
      handleError(error, 'Không thể xóa sản phẩm');
    }
  },
};

export const orderService = {
  getAll: async (params) => {
    try {
      const response = await axiosInstance.get(API_ENDPOINTS.ORDERS.GET_ALL, {
        params,
      });
      return response.data;
    } catch (error) {
      handleError(error, 'Không thể tải danh sách đơn hàng');
    }
  },
  getAllCus: async (params) => {
    try {
      const response = await axiosInstance.get(`http://localhost:8000/api/v1/orders/customer/${params}`);
      return response.data;
    } catch (error) {
      handleError(error, 'Không thể tải danh sách đơn hàng');
    }
  },
  getById: async (id) => {
    try {
      const response = await axiosInstance.get(
        API_ENDPOINTS.ORDERS.GET_DETAIL(id)
      );
      return response.data;
    } catch (error) {
      handleError(error, 'Không thể tải thông tin đơn hàng');
    }
  },

  update: async (id, data) => {
    try {
      const response = await axiosInstance.put(
        API_ENDPOINTS.ORDERS.UPDATE(id),
        data,
        withRetry()
      );
      return response.data;
    } catch (error) {
      handleError(error, 'Không thể cập nhật đơn hàng');
    }
  },

  delete: async (id) => {
    try {
      await axiosInstance.delete(API_ENDPOINTS.ORDERS.DELETE(id), withRetry());
    } catch (error) {
      handleError(error, 'Không thể xóa đơn hàng');
    }
  },

  getStatistics: async () => {
    try {
      const response = await axiosInstance.get(
        API_ENDPOINTS.ORDERS.GET_STATISTICS
      );
      return response.data;
    } catch (error) {
      handleError(error, 'Không thể tải thống kê đơn hàng');
    }
  },

  getMonthlyRevenue: async () => {
    try {
      const response = await axiosInstance.get(
        API_ENDPOINTS.ORDERS.GET_MONTHLY_REVENUE
      );
      return response.data;
    } catch (error) {
      handleError(error, 'Không thể tải doanh thu theo tháng');
    }
  },
};

export const customerService = {
  getCount: async () => {
    try {
      const response = await axiosInstance.get(
        API_ENDPOINTS.CUSTOMERS.GET_COUNT
      );
      return response.data;
    } catch (error) {
      handleError(error, 'Không thể tải số lượng khách hàng');
    }
  },
  getAddress: async () => {
    try {
      const response = await axiosInstance.get(
        API_ENDPOINTS.CUSTOMERS.GET_ADDRESS
      );
      return response.data;
    } catch (error) {
      handleError(error, 'Không thể tải địa chỉ');
    }
  },
  saveAddress: async (addressData) => {
    try {
      const response = await axiosInstance.post(
        API_ENDPOINTS.CUSTOMERS.SAVE_ADDRESS,
        addressData
      );
      return response.data;
    } catch (error) {
      handleError(error, 'Không thể lưu địa chỉ');
    }
  },
  deleteAddress: async () => {
    try {
      const response = await axiosInstance.delete(
        API_ENDPOINTS.CUSTOMERS.DELETE_ADDRESS
      );
      return response.data;
    } catch (error) {
      handleError(error, 'Không thể tải địa chỉ');
    }
  },
};

export const promotionService = {
  checkCode: async (code) => {
    try {
      const response = await axiosInstance.get(
        API_ENDPOINTS.PROMOTIONS.CHECK_CODE(code)
      );
      return response.data;
    } catch (error) {
      handleError(error, 'Không thể kiểm tra mã giảm giá');
    }
  },
};

export default axiosInstance;
