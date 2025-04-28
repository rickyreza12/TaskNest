// src/features/project/projectService.ts
import axiosInstance from '../../services/axiosInstance';

export const projectService = {
  async fetchProjects() {
    const response = await axiosInstance.get('/projects');
    return response.data;
  },

  async createProject(name: string, description: string) {
    const response = await axiosInstance.post('/projects/create', { name, description });
    return response.data;
  },
};
