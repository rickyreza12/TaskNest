// src/features/auth/authService.ts
import axiosInstance from '../../services/axiosInstance';
import qs from 'qs'

export const authService = {
  async login(userData: { email: string; password: string }) {
      const response = await axiosInstance.post(
        '/auth/login', 
        qs.stringify(userData),
        {
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          }
        }
      );

      const token = response.data?.data?.token;
      if (token) {
        localStorage.setItem('token', token);
      } else {
        throw new Error('Cannot save token');
      }

      return response.data.data;
  },

  async register(name: string, email: string, password: string) {
    const response = await axiosInstance.post('/auth/register', { name, email, password });
    return response.data;
  },

  async logout() {
    localStorage.removeItem('token');
  }
};
