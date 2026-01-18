import axios from 'axios';

const API_BASE_URL = 'http://localhost:8000/api'; // Adjust this to match your backend URL

const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
  },
});

// Request interceptor to add token to requests
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Response interceptor to handle token expiration
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      // Token expired or invalid, remove it and redirect to login
      localStorage.removeItem('token');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

export default api;

// Authentication endpoints
export const authAPI = {
  login: (credentials: { username: string; password: string }) => 
    api.post('/login', credentials),
  
  register: (userData: { 
    name: string; 
    username: string; 
    password: string; 
    password_confirmation: string;
    nik?: string;
    address?: string;
    city?: string;
    phone?: string;
  }) => api.post('/register', userData),
  
  logout: () => api.post('/logout'),
};

// Product endpoints
export const productAPI = {
  getAllProducts: (params?: { page?: number; limit?: number; search?: string }) => 
    api.get('/products', { params }),
  
  getProductById: (id: number) => 
    api.get(`/products/${id}`),
};

// Cart endpoints
export const cartAPI = {
  getCart: () => 
    api.get('/cart'),
  
  addToCart: (data: { product_detail_id: number; quantity: number }) => 
    api.post('/cart', data),
  
  updateCartItem: (id: number, data: { quantity: number }) => 
    api.put(`/cart/${id}`, data),
  
  removeFromCart: (id: number) => 
    api.delete(`/cart/${id}`),
};

// Order endpoints
export const orderAPI = {
  getOrders: () => 
    api.get('/orders'),
  
  createOrder: (data: {
    shipping_rate_id: number;
    destination_city: string;
    payment_method: string;
  }) => api.post('/orders', data),
  
  getOrderById: (id: number) => 
    api.get(`/orders/${id}`),
};

// Chat endpoints
export const chatAPI = {
  getMessages: (params?: { sender_id?: number; receiver_id?: number }) => 
    api.get('/chat/messages', { params }),
  
  sendMessage: (data: { 
    receiver_id: number; 
    message: string; 
  }) => api.post('/chat/send', data),
};