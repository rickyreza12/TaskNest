// src/features/auth/authSlice.ts

import { createAsyncThunk, createSlice } from '@reduxjs/toolkit';
import { authService } from './authService';

interface AuthState {
  token: string | null;
  user: {
    id: number;
    name: string;
    email: string;
  } | null;
  loading: boolean;
  error: string | null;
}

const initialState: AuthState = {
  token: localStorage.getItem('token'),
  user: null,
  loading: false,
  error: null
};

export const loginUser = createAsyncThunk(
    'auth/login',
    async (userData: { email: string; password: string }, thunkAPI) => {
      try {
        const response = await authService.login(userData);
        return response;
      } catch (error: any) {
        return thunkAPI.rejectWithValue(error.response?.data?.message || error.message);
      }
    }
  );

const authSlice = createSlice({
  name: 'auth',
  initialState,
  reducers: {
    setCredentials: (state, action) => {
      state.token = action.payload.token;
      state.user = action.payload.user;
    },
    logout: (state) => {
      state.token = null;
      state.user = null;
      localStorage.removeItem('token');
    },
  },
});

export const { setCredentials, logout } = authSlice.actions;
export default authSlice.reducer;
