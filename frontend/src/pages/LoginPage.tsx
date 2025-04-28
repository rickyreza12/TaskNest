import { useState } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { AppDispatch, RootState } from '../app/store';
import { loginUser } from '../features/auth/authSlice'; // <-- Thunk
import { useNavigate } from 'react-router-dom';
import TaskNestIcon from '../components/icon/TaskNestIcon';
import { toast } from 'react-hot-toast';

const LoginPage = () => {
  const dispatch = useDispatch<AppDispatch>();
  const navigate = useNavigate();
  const { loading } = useSelector((state: RootState) => state.auth);

  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [isLoggingIn, setIsLoggingIn] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsLoggingIn(true);

    const result = await dispatch(loginUser({ email, password }));

    if (result.meta.requestStatus === 'fulfilled') {
      toast.success('Login successful! 🎉');
      navigate('/dashboard');
    } else {
      toast.error(result.payload || 'Login failed ❌');
    }
    setIsLoggingIn(false);
  };

  return (
    <>
      {isLoggingIn && (
        <div className="fixed inset-0 z-50 bg-black opacity-70 flex flex-col items-center justify-center gap-2">
          <div className="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-[#6667EC]"></div>
          <div className="text-white">Checking the data, please wait...</div>
        </div>
      )}

      <div className="bg-[#0F0F2D] min-h-screen flex items-center justify-center">
        <div className="bg-[#161636] rounded-4xl border-4 border-[#6667ec] p-10 md:p-24 flex flex-col md:flex-row items-center gap-10 shadow-2xl w-full max-w-4xl">
          
          <div className="hidden md:flex w-1/2 justify-center">
            <TaskNestIcon size={150} primaryColor="#6667ec" textColor="#6667ec" />
          </div>

          <div className="w-full md:w-1/2">
            <h2 className="text-white text-2xl font-bold mb-6">Login To Task Nest</h2>

            <form onSubmit={handleSubmit} className="flex flex-col gap-4">
              <input
                type="email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                placeholder="Email"
                className="border-b border-gray-500 bg-transparent text-white placeholder-gray-400 py-2 focus:outline-none"
                required
              />

              <input
                type="password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                placeholder="Password"
                className="border-b border-gray-500 bg-transparent text-white placeholder-gray-400 py-2 focus:outline-none"
                required
              />

              <button
                type="submit"
                disabled={loading}
                className={`bg-[#6667ec] hover:bg-[#ffffff] text-white hover:text-[#6667ec] font-semibold py-2 px-4 rounded-md mt-4 flex items-center justify-center gap-2 transition-all duration-300 ${
                  loading ? 'opacity-70 cursor-not-allowed' : ''
                }`}
              >
                {loading ? (
                  <svg className="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                  </svg>
                ) : (
                  'Login'
                )}
              </button>

              <p className="text-gray-400 text-center mt-4 text-sm">
                Don’t have an account? <a href="/register" className="text-[#6667ec] hover:underline">Click here</a>
              </p>
            </form>
          </div>

        </div>
      </div>
    </>
  );
};

export default LoginPage;
