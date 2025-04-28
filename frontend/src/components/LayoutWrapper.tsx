// src/components/LayoutWrapper.tsx
import { useState } from "react";
import Sidebar from "./Sidebar";
import Topbar from "./TopBar";

interface LayoutWrapperProps {
  children: React.ReactNode;
}

const LayoutWrapper = ({ children }: LayoutWrapperProps) => {
  const [isSidebarOpen, setSidebarOpen] = useState(true);

  const handleToggleSidebar = () => {
    setSidebarOpen(!isSidebarOpen);
  };

  return (
    <div className="flex min-h-screen bg-[#0F0F2D]">
      <Sidebar isOpen={isSidebarOpen} onToggle={handleToggleSidebar} />

      <div className="flex-1 flex flex-col">
        <Topbar onToggleSidebar={handleToggleSidebar} />
        <main className="flex-1 p-6 text-white">
          {children}
        </main>
      </div>
    </div>
  );
};

export default LayoutWrapper;
