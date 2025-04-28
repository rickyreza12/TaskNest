// src/components/Sidebar.tsx
import { Link } from "react-router-dom";

const Sidebar = () => {
  return (
    <div className="w-64 bg-cardDark p-6 flex flex-col gap-6">
      <h2 className="text-primary font-bold text-2xl mb-6">TaskNest</h2>
      <nav className="flex flex-col gap-4">
        <Link to="/dashboard" className="text-gray-300 hover:text-primary">
          Dashboard
        </Link>
        <Link to="/projects" className="text-gray-300 hover:text-primary">
          Projects
        </Link>
        <Link to="/tasks" className="text-gray-300 hover:text-primary">
          Tasks
        </Link>
        <Link to="/focus" className="text-gray-300 hover:text-primary">
          Focus Mode
        </Link>
      </nav>
    </div>
  );
};

export default Sidebar;
