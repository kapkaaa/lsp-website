import axios from 'axios';

// Create axios instance with base configuration
const apiClient = axios.create({
    baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8000/api',
    withCredentials: true,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
    timeout: 15000,
});

// Request interceptor to add auth token
apiClient.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('auth_token');
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Response interceptor for error handling
apiClient.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        if (error.response) {
            // Handle specific error codes
            if (error.response.status === 401) {
                // Unauthorized - clear token and redirect to login
                localStorage.removeItem('auth_token');
                localStorage.removeItem('user');
                window.location.href = '/login';
            } else if (error.response.status === 403) {
                console.error('Forbidden: You do not have permission to access this resource');
            } else if (error.response.status === 404) {
                console.error('Resource not found');
            } else if (error.response.status === 500) {
                console.error('Server error. Please try again later.');
            }
        } else if (error.request) {
            console.error('Network error. Please check your connection.');
        } else {
            console.error('An error occurred:', error.message);
        }
        return Promise.reject(error);
    }
);

export default apiClient;

// Helper function to handle file uploads
export const uploadFile = async (endpoint: string, file: File, additionalData?: Record<string, any>) => {
    const formData = new FormData();
    formData.append('file', file);

    if (additionalData) {
        Object.keys(additionalData).forEach((key) => {
            formData.append(key, additionalData[key]);
        });
    }

    return apiClient.post(endpoint, formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    });
};
