// src/features/project/projectSlice.ts
import { createSlice } from '@reduxjs/toolkit';

interface Project {
  id: number;
  name: string;
  description: string;
  owner: boolean;
  owner_name: string;
}

interface ProjectState {
  projects: Project[];
}

export interface FilterProject {
  search?: string;
  sort?: string;
  order?: string;
  page?: number;
  perPage?: number;
}

const initialState: ProjectState = {
  projects: [],
};

const projectSlice = createSlice({
  name: 'project',
  initialState,
  reducers: {
    setProjects: (state, action) => {
      state.projects = action.payload;
    },
  },
});

export const { setProjects } = projectSlice.actions;
export default projectSlice.reducer;
