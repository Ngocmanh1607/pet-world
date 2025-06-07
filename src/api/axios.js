import axios from 'axios'

const axiosInstance = axios.create({
  baseURL: process.env.VUE_APP_API_URL || 'http://localhost:8000/api/v1',
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json'
  }
})

// Add a request interceptor
axiosInstance.interceptors.request.use(
  config => {
    const token = document.cookie
      .split('; ')
      .find(row => row.startsWith('token='))
      ?.split('=')[1]
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  },
  error => {
    return Promise.reject(error)
  }
)

// Add a response interceptor
axiosInstance.interceptors.response.use(
  response => response,
  error => {
    if (error.response) {
      // The request was made and the server responded with a status code
      // that falls out of the range of 2xx
      console.error('Response error:', error.response.data)
      return Promise.reject(error.response.data)
    } else if (error.request) {
      // The request was made but no response was received
      console.error('Request error:', error.request)
      return Promise.reject(new Error('No response from server'))
    } else {
      // Something happened in setting up the request that triggered an Error
      console.error('Error:', error.message)
      return Promise.reject(error)
    }
  }
)

export default axiosInstance
