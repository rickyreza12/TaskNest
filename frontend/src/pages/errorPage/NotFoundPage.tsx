const NotFoundPage = () => {
    return (
      <div className="bg-[#0F0F2D] min-h-screen flex items-center justify-center text-white flex-col gap-4">
        <h1 className="text-5xl font-bold">404</h1>
        <p className="text-xl">Oops! Page not found.</p>
        <a href="/login" className="text-[#6667ec] hover:underline">
          Go back to Login
        </a>
      </div>
    );
  };
  
  export default NotFoundPage;
  