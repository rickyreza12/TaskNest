import { useState } from "react";
import Sidebar from "../components/Sidebar";
import Topbar from "../components/TopBar";

const DashboardPage = () => {
  const [isSidebarOpen, setSidebarOpen] = useState(true); // Start opened

  const handleToggleSidebar = () => {
    setSidebarOpen(!isSidebarOpen);
  };

  return (
    <div className="flex min-h-screen bg-[#0F0F2D]">
      <Sidebar isOpen={isSidebarOpen} onToggle={handleToggleSidebar} />

      <div className="flex-1 flex flex-col">
      <Topbar onToggleSidebar={handleToggleSidebar} />
        
        <main className="flex-1 p-6 text-white">
          <h1 className="text-2xl font-bold mb-4">Dashboard</h1>
          <p className="text-gray-400">Tracking projects, tasks, and notifications here...</p>
        </main>
      </div>
    </div>
  );
};

export default DashboardPage;
