// src/pages/DashboardPage.tsx
import Sidebar from "../components/Sidebar";
import Topbar from "../components/TopBar";

const DashboardPage = () => {
  return (
    <div className="flex min-h-screen bg-backgroundDark">
      <Sidebar />

      <div className="flex-1 flex flex-col">
        <Topbar />
        
        <main className="flex-1 p-6 text-white">
          <h1 className="text-2xl font-bold mb-4">Dashboard</h1>
          <p className="text-gray-300">Tracking projects, tasks, and notifications here...</p>
        </main>
      </div>
    </div>
  );
};

export default DashboardPage;
