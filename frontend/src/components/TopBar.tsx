// src/components/Topbar.tsx
import { Bell } from "lucide-react"; // Lucide icon library

const Topbar = () => {
  return (
    <div className="flex items-center justify-between bg-cardDark p-4 border-b border-primary">
      <h1 className="text-xl text-white font-bold">Welcome back, User!</h1>
      
      <div className="relative">
        <button className="text-white hover:text-primary">
          <Bell size={24} />
        </button>
        {/* Notification dot */}
        <span className="absolute top-0 right-0 h-2 w-2 bg-primary rounded-full"></span>
      </div>
    </div>
  );
};

export default Topbar;
