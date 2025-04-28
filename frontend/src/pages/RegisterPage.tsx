import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { toast } from 'react-hot-toast';
import TaskNestIcon from '../components/icon/TaskNestIcon';
import axiosInstance from '../services/axiosInstance';

const RegisterPage = () => {
  const navigate = useNavigate();
  const [isRegistering, setIsRegistering] = useState(false);

  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');

  const handleRegister = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsRegistering(true);

    try {
      const response = await axiosInstance.post('/auth/register', {
        name,
        email,
        password,
      });

      if (response.data) {
        toast.success('Registration successful! 🎉 Please login.');
        navigate('/login');
      }
    } catch (error: any) {
      toast.error(error.response?.data?.message || 'Registration failed ❌');
    } finally {
      setIsRegistering(false);
    }
  };

  return (
    <>
      {/* Full Screen Loading Overlay */}
      {isRegistering && (
        <div className="fixed inset-0 z-50 bg-black opacity-70 flex flex-col items-center justify-center gap-4">
          <div className="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-[#6667EC]"></div>
          <div className="text-white text-lg">Creating your account, please wait...</div>
        </div>
      )}

      {/* Register Content */}
      <div className="bg-[#0F0F2D] min-h-screen flex items-center justify-center px-4">
        <div className="bg-[#161636] rounded-4xl border-4 border-[#6667EC] p-10 md:p-24 flex flex-col md:flex-row items-center gap-10 shadow-2xl w-full max-w-4xl">

          {/* Left Icon */}
          <div className="hidden md:flex w-1/2 justify-center">
            <TaskNestIcon size={150} primaryColor="#6667EC" textColor="#6667EC" />
          </div>

          {/* Right Form */}
          <div className="w-full md:w-1/2">
            <h2 className="text-white text-2xl font-bold mb-6">Create Your Account</h2>

            <form onSubmit={handleRegister} className="flex flex-col gap-4">
              {/* Full Name */}
              <input
                type="text"
                value={name}
                onChange={(e) => setName(e.target.value)}
                placeholder="Full Name"
                className="border border-gray-500 bg-[#161636] text-white placeholder-gray-400 py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-[#6667EC]"
                required
              />

              {/* Email */}
              <input
                type="email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                placeholder="Email"
                className="border border-gray-500 bg-[#161636] text-white placeholder-gray-400 py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-[#6667EC]"
                required
              />

              {/* Password */}
              <input
                type="password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                placeholder="Password"
                className="border border-gray-500 bg-[#161636] text-white placeholder-gray-400 py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-[#6667EC]"
                required
              />

              {/* Submit Button */}
              <button
                type="submit"
                disabled={isRegistering}
                className={`bg-[#6667EC] hover:bg-white text-white hover:text-[#6667EC] font-semibold py-2 px-4 rounded-md mt-4 flex items-center justify-center gap-2 transition-all duration-300 ${
                  isRegistering ? 'opacity-70 cursor-not-allowed' : ''
                }`}
              >
                {isRegistering ? (
                  <svg className="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                  </svg>
                ) : (
                  'Register'
                )}
              </button>

              {/* Link to Login */}
              <p className="text-gray-400 text-center mt-4 text-sm">
                Already have an account?{' '}
                <a href="/login" className="text-[#6667EC] hover:underline">
                  Login here
                </a>
              </p>
            </form>
          </div>

        </div>
      </div>
    </>
  );
};

export default RegisterPage;
