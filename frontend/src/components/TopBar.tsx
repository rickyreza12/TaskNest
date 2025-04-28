import { Bell, LogOut, Sun, Menu } from "lucide-react";
import { useDispatch, useSelector } from "react-redux";
import { AppDispatch, RootState } from "../app/store";
import { useNavigate } from "react-router-dom";
import { logoutUser } from "../features/auth/authSlice";
import { useState } from "react";

interface TopbarProps {
  onToggleSidebar: () => void;
}

const Topbar = ({ onToggleSidebar }: TopbarProps) => {
  const dispatch = useDispatch<AppDispatch>();
  const navigate = useNavigate();
  const [isLoggingOut, setIsLoggingOut] = useState(false);

  const userName = useSelector((state: RootState) => state.auth.user?.name) || "User";


  const handleLogout = async () => {
    setIsLoggingOut(true);

    // Simulate 2 seconds delay
    await new Promise((resolve) => setTimeout(resolve, 2000));

    await dispatch(logoutUser());
    setIsLoggingOut(false);
    navigate('/login');
  };

  return (
    <>
      {/* Loading Overlay */}
      {isLoggingOut && (
        <div className="fixed inset-0 z-50 bg-black opacity-70 flex items-center justify-center">
          <div className="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-[#6667EC]"></div>
        </div>
      )}

      {/* Topbar */}
      <div className="flex items-center justify-between bg-[#161636] p-4 border-b border-[#6667EC]">
        {/* Left side */}
        <div className="flex items-center gap-2">
          {/* Burger only on mobile */}
          <button onClick={onToggleSidebar} className="text-white hover:text-[#6667EC] md:hidden">
            <Menu size={24} />
          </button>
          <h1 className="text-xl text-white font-bold hidden md:block">Welcome back, {userName}!</h1>
        </div>

        {/* Right side */}
        <div className="flex items-center gap-6">
          <button className="text-white hover:text-[#6667EC]">
            <Sun size={22} />
          </button>

          <div className="relative">
            <button className="text-white hover:text-[#6667EC]">
              <Bell size={22} />
            </button>
            <span className="absolute top-0 right-0 h-2 w-2 bg-[#6667EC] rounded-full"></span>
          </div>

          <button 
            onClick={handleLogout} 
            className="text-white hover:text-[#6667EC]"
            disabled={isLoggingOut}
          >
            <LogOut size={22} />
          </button>
        </div>
      </div>
    </>
  );
};

export default Topbar;
