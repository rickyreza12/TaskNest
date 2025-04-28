import { useEffect, useState } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { RootState } from '../app/store'; // adjust if needed
import { setProjects } from '../features/projects/projectSlice';
import { projectService } from '../features/projects/projectService';
import LayoutWrapper from "../components/LayoutWrapper";
import { Plus } from 'lucide-react';
import { toast } from 'react-hot-toast';

const ProjectsPage = () => {
  const [isLoading, setIsLoading] = useState(false);
  const [currentPage, setCurrentPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const projectsPerPage = 6;
  
  const dispatch = useDispatch();
  const projects = useSelector((state: RootState) => state.project.projects); // <-- from Redux

  const fetchProjects = async (page = 1) => {
    setIsLoading(true);
    try {
      const data = await projectService.fetchProjects({
        page: page,
        perPage: projectsPerPage,
      });

      dispatch(setProjects(data.projects));
      setTotalPages(data.pagination.totalPages);
    } catch (error: any) {
      console.error(error);
      toast.error('Failed to fetch projects ❌');
    } finally {
      setIsLoading(false);
    }
  };

  useEffect(() => {
    fetchProjects(currentPage);
  }, [currentPage]);

  return (
    <LayoutWrapper>
      <div className="flex justify-between items-center mb-8">
        <h1 className="text-3xl font-bold">Projects</h1>
        <button className="flex items-center gap-2 bg-[#6667ec] hover:bg-white text-white hover:text-[#6667EC] font-semibold py-2 px-4 rounded-md transition-all duration-300 cursor-pointer">
          <Plus size={20} />
          <span>Create Project</span>
        </button>
      </div>

      {isLoading ? (
        <div className="flex flex-col gap-4 items-center justify-center">
          <div className="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-[#6667EC]"></div>
          <div className="text-white">Loading your projects...</div>
        </div>
      ) : projects.length === 0 ? (
        <div className="text-center text-gray-400 mt-20">
          You don't have any projects yet.
        </div>
      ) : (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {projects.map((project) => (
            <div
              key={project.id}
              className="bg-[#161636] border border-[#6667EC] rounded-lg p-10 shadow-lg transform hover:scale-105 transition-transform duration-300 hover:border-white hover:shadow-2xl cursor-pointer relative"
            >
              <h2 className="text-xl font-bold mb-2">{project.name}</h2>
              <p className="text-gray-400 text-sm mb-2">{project.description || 'No description provided.'}</p>

              <div className="absolute bottom-4 left-10 text-xs text-gray-400">
                Owned by: {project.owner_name || 'Unknown'}
              </div>

              <div className="absolute bottom-4 right-4 text-white hover:text-[#6667EC] cursor-pointer">
                <Plus size={18} />
              </div>
            </div>
          ))}
        </div>
      )}

      <div className="flex justify-center mt-8 gap-2">
        <button
          disabled={currentPage === 1}
          onClick={() => setCurrentPage(prev => Math.max(prev - 1, 1))}
          className="bg-[#6667EC] text-white px-4 py-2 rounded-md hover:bg-white hover:text-[#6667EC] disabled:opacity-50 cursor-pointer"
        >
          Prev
        </button>
        <button
          disabled={currentPage >= totalPages}
          onClick={() => setCurrentPage(prev => Math.min(prev + 1, totalPages))}
          className="bg-[#6667EC] text-white px-4 py-2 rounded-md hover:bg-white hover:text-[#6667EC] disabled:opacity-50 cursor-pointer"
        >
          Next
        </button>
      </div>
    </LayoutWrapper>
  );
};

export default ProjectsPage;
