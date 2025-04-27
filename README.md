# TaskNest
Fullstack Project Management System — Backend (PHP 8, CodeIgniter 4) and Frontend (React 18) in one monorepo. Features: Project collaboration, task assignments, focus mode, JWT authentication, RabbitMQ queues, and FCM push notifications.

**TaskNest** is a fullstack project management system built with:

- **Backend:** PHP 8, CodeIgniter 4
- **Frontend:** React 18 (Vite)
- **Database:** MySQL
- **Queue:** RabbitMQ
- **Push Notifications:** Firebase Cloud Messaging (FCM)
- **Authentication:** JSON Web Tokens (JWT)

## Folder Structure

- `backend/` — CodeIgniter 4 project
- `frontend/` — React 18 project

## Quick Start

```bash
# Backend
cd backend
composer install
cp .env.example .env
php spark serve

# Frontend
cd frontend
npm install
npm run dev



---
