// src/features/auth/authService.ts
import axiosInstance from '../../services/axiosInstance';

export const authService = {
  async login(userData: { email: string; password: string }) {
    const response = await axiosInstance.post('/auth/login', userData);
    return response.data;
  },

  async register(name: string, email: string, password: string) {
    const response = await axiosInstance.post('/auth/register', { name, email, password });
    return response.data;
  },

  async logout(){
    localStorage.removeItem('token')
  }
};
