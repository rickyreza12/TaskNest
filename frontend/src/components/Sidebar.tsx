import { Link } from "react-router-dom";
import { Home, Folder, ClipboardList, Target, Menu } from "lucide-react";

interface SidebarProps {
  isOpen: boolean;
  onToggle: () => void;
}

const Sidebar = ({ isOpen, onToggle }: SidebarProps) => {
  return (
    <div
      className={`
        bg-[#161636] p-6 flex flex-col gap-6 
        min-h-screen
        fixed md:static top-0 left-0 z-40
        ${isOpen ? "w-64" : "w-20"} 
        transition-all duration-300
        ${isOpen ? "translate-x-0" : "-translate-x-full"} 
        md:translate-x-0
      `}
    >
      {/* Top Section */}
      <div className="flex justify-between items-center mb-6">
        <h2 className={`text-[#6667EC] font-bold text-2xl ${isOpen ? "block" : "hidden"}`}>TaskNest</h2>
        <button onClick={onToggle} className="text-white hover:text-[#6667EC]">
          <Menu size={24} className="cursor-pointer" />
        </button>
      </div>

      {/* Navigation */}
      <nav className="flex flex-col gap-4">
        <Link to="/dashboard" className="flex items-center gap-2 text-gray-300 hover:text-[#6667EC]">
          <Home size={22} />
          {isOpen && <span>Dashboard</span>}
        </Link>
        <Link to="/projects" className="flex items-center gap-2 text-gray-300 hover:text-[#6667EC]">
          <Folder size={22} />
          {isOpen && <span>Projects</span>}
        </Link>
        <Link to="/tasks" className="flex items-center gap-2 text-gray-300 hover:text-[#6667EC]">
          <ClipboardList size={22} />
          {isOpen && <span>Tasks</span>}
        </Link>
        <Link to="/focus" className="flex items-center gap-2 text-gray-300 hover:text-[#6667EC]">
          <Target size={22} />
          {isOpen && <span>Focus Mode</span>}
        </Link>
      </nav>
    </div>
  );
};

export default Sidebar;
