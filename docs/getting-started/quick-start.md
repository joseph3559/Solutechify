# Quick Start Guide

This guide will help you get Solutechify up and running quickly.

## Prerequisites

- PHP 8.2 or higher
- Composer 2.x
- Node.js 18.x or higher
- PostgreSQL 13 or higher
- Git

## Technology Stack

- **Backend:** Laravel 11+
- **Frontend:** Nuxt 3 with TypeScript
- **Database:** PostgreSQL
- **Styling:** Tailwind CSS
- **Package Managers:** Composer (PHP) and npm (Node.js)

## Installation Steps

1. **Clone the Repository**
   ```bash
   git clone https://github.com/yourusername/solutechify.git
   cd solutechify
   ```

2. **Backend Setup**
   ```bash
   # Install PHP dependencies
   cd backend
   composer install

   # Copy environment file
   cp .env.example .env

   # Generate application key
   php artisan key:generate

   # Configure database in .env
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=solutechify
   DB_USERNAME=your_username
   DB_PASSWORD=your_password

   # Run migrations and seeders
   php artisan migrate
   php artisan db:seed  # Optional: Add sample data

   # Generate JWT secret
   php artisan jwt:secret

   # Link storage
   php artisan storage:link
   ```

3. **Frontend Setup**
   ```bash
   # Install Node.js dependencies
   cd ../frontend
   npm install

   # Copy environment file
   cp .env.example .env

   # Configure frontend environment
   NUXT_PUBLIC_API_BASE=http://localhost:8000/api
   ```

4. **Start Development Servers**
   ```bash
   # Start Laravel development server (from backend directory)
   php artisan serve

   # Start Nuxt development server (from frontend directory)
   npm run dev
   ```

5. **Access the Application**
   - Frontend: http://localhost:3000
   - Admin Panel: http://localhost:3000/admin
   - API: http://localhost:8000/api

## Initial Setup

1. **Create an Organization**
   - Visit http://localhost:3000/register
   - Fill in organization details
   - Verify email address

2. **Create Your First Event**
   - Log in to admin panel
   - Navigate to Events → Create New
   - Fill in event details
   - Publish the event

3. **Invite Team Members**
   - Go to Organization Settings
   - Click "Invite Members"
   - Enter email addresses
   - Set appropriate roles

## Development Tools

### Recommended VSCode Extensions
- Laravel Intellisense
- Volar (Vue)
- TypeScript Vue Plugin (Volar)
- Tailwind CSS IntelliSense
- ESLint
- Prettier

### Useful Commands

**Backend (Laravel)**
```bash
# Run tests
php artisan test

# Clear cache
php artisan cache:clear

# Create new migration
php artisan make:migration create_table_name

# Create new controller
php artisan make:controller NameController --api
```

**Frontend (Nuxt 3)**
```bash
# Type check
npm run typecheck

# Lint
npm run lint

# Build for production
npm run build

# Preview production build
npm run preview
```

## Common Issues

### Backend Issues
- Ensure PHP version matches requirements
- Check PostgreSQL connection settings
- Verify storage permissions
- Run `composer dump-autoload` if classes aren't found

### Frontend Issues
- Clear Nuxt cache if you see stale data: `npm run clean`
- Check TypeScript errors: `npm run typecheck`
- Verify API endpoint configuration in `.env`
- Run `npm install` after pulling new changes

### Database Issues
- Ensure PostgreSQL service is running
- Check database user permissions
- Run migrations: `php artisan migrate:status`

## Getting Help

- [Join our Discord](https://discord.gg/solutechify)
- [GitHub Issues](https://github.com/joseph3559/solutechify/issues)
- [Laravel Documentation](https://laravel.com/docs/11.x)
- [Nuxt 3 Documentation](https://nuxt.com/docs)
- Email: scottjoe3559@gmail.com 