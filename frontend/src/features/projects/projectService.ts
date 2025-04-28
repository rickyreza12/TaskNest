// src/features/project/projectService.ts
import axiosInstance from '../../services/axiosInstance';
import { FilterProject } from './projectSlice';
import qs from 'qs';

export const projectService = {
  async fetchProjects(filterData: FilterProject) {
    const response = await axiosInstance.post(
      '/projects',
      qs.stringify(filterData),
      {
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
      }
    );
    return response.data.data; // return only clean data
  },

  async createProject(name: string, description: string) {
    const response = await axiosInstance.post('/projects/create', { name, description });
    return response.data.data;
  },
};
